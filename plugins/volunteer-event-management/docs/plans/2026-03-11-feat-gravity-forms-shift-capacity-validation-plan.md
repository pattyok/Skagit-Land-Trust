---
title: "feat: Gravity Forms Shift Capacity Validation"
type: feat
status: completed
date: 2026-03-11
---

# feat: Gravity Forms Shift Capacity Validation

## Overview

When a volunteer submits the Gravity Forms shift registration form, validate in real-time that spots are still available on the `vol_shift` post. If capacity remains, allow the submission and immediately decrement the `vol_shift_volunteers_needed` meta field. If the shift is full, fail validation with a configurable error message. A new settings page under the Volunteer Events admin menu allows site admins to configure the GF form ID and the "shift full" error message.

## Problem Statement / Motivation

Currently, `vol_shift_volunteers_needed` is only checked at block render time — when the page loads. Because Salesforce syncs every few minutes, the displayed count may be stale. More critically, there is no guard at form submission time: two volunteers could both see an open shift, both submit the form, and both be registered even if only one slot remained. This feature closes that gap.

## Proposed Solution

Three coordinated changes:

1. **Settings page** — new `VEMgmt_Settings` class adds a submenu page under `edit.php?post_type=vol_event` to store `vemgmt_gf_form_id` and `vemgmt_shift_full_message` as WordPress options.

2. **Pass `wp_shift_id` to the form** — update `render.php` to include `wp_shift_id` (the WP post ID of the `vol_shift`) as a hidden `field_values` parameter in the GF shortcode. This avoids an expensive reverse meta-query in the submission callback.

3. **Gravity Forms hooks** — new `VEMgmt_Form_Validation` class registers:
   - `gform_validation` — checks `vol_shift_volunteers_needed > 0`; fails validation with the configured message if full.
   - `gform_after_submission` — decrements `vol_shift_volunteers_needed` by 1 (floor at 0) immediately after a successful entry is saved.

## Technical Considerations

### Architecture Impacts

- Two new singleton classes following the `VEMgmt_` prefix convention, loaded from `volunteer-event-management.php::includes()`.
- `formID` moves from a per-block attribute (`$attributes['formID']`) to the global option `vemgmt_gf_form_id`. The render.php should fall back to `$attributes['formID']` if the option is empty, preserving backward compatibility for existing block instances.
- The `gform_validation` and `gform_after_submission` hooks are scoped to the configured form ID only (no side effects on other GF forms on the site).

### Race Condition Risk

A brief window exists between the capacity check and the decrement. Two simultaneous submissions could both pass validation before either decrement completes, resulting in overbooking by one. Mitigation options (in order of complexity):

- **Acceptable for MVP:** Salesforce will re-sync accurate counts within minutes. Minor temporary overbooking is low-risk for a volunteer org.
- **Lightweight transient lock:** Set a WordPress transient keyed by `vol_shift` post ID during the check+decrement cycle to serialize submissions for a given shift.
- **DB-level atomic update:** Use `$wpdb->query()` with a conditional `UPDATE ... WHERE meta_value > 0` and check `$wpdb->rows_affected`.

Recommend the transient lock approach as a practical middle ground.

### Performance

- `get_post_meta()` for a single post is fast; no WP_Query needed if `wp_shift_id` is passed via the form.
- Settings are retrieved with `get_option()` (cached by WordPress object cache after first load).

### Security

- GF form field values are submitted as POST data. The `wp_shift_id` field value must be validated as a positive integer before use in any database query.
- The settings page must use `current_user_can('manage_options')` capability check and nonce verification on save.
- `update_post_meta()` with an integer cast is safe from injection.

## System-Wide Impact

- **Interaction graph:** GF submit → `gform_validation` fires → capacity check runs → if valid, GF saves entry → `gform_after_submission` fires → `update_post_meta` decrements counter. Salesforce Object Sync plugin independently fires `object_sync_for_salesforce_pull_success` on its own schedule, which will overwrite `vol_shift_volunteers_needed` with the Salesforce authoritative count.
- **State lifecycle risks:** If `gform_after_submission` fails silently (e.g., an exception), the entry is already saved but the counter is not decremented — slot is used but count remains incorrect until next Salesforce sync. This is acceptable given the sync cadence.
- **Integration test scenarios:** Two simultaneous submissions for a shift with 1 remaining slot; admin saves form ID then loads registration page; form ID global option vs. block attribute fallback.

## Acceptance Criteria

### Settings Page

- [x] A "Settings" submenu page appears under the Volunteer Events admin menu (`edit.php?post_type=vol_event`)
- [x] The page contains a text field for the Gravity Forms form ID (`vemgmt_gf_form_id`)
- [x] The page contains a textarea/text field for the "shift full" error message (`vemgmt_shift_full_message`)
- [x] Default error message is populated if the option is empty (e.g., "Sorry, this shift is now full.")
- [x] Settings save correctly with nonce verification and `current_user_can('manage_options')` check
- [x] `sanitize_text_field()` / `absint()` used on saved values

### Form Rendering

- [x] `render.php` passes `wp_shift_id` (WP post ID integer) as a hidden field value to the GF shortcode
- [x] `render.php` uses `get_option('vemgmt_gf_form_id')` as the form ID, falling back to `$attributes['formID']` if the option is empty

### Capacity Validation

- [x] On GF form submission, `gform_validation` hook fires only for the configured form ID
- [x] If `vol_shift_volunteers_needed <= 0`, validation fails and the configured error message is shown on the form
- [x] If `vol_shift_volunteers_needed > 0`, validation passes and the entry proceeds to save
- [x] Validation uses the `wp_shift_id` from the submitted field values (validated as positive integer)

### Counter Decrement

- [x] On successful form submission (`gform_after_submission`), `vol_shift_volunteers_needed` is decremented by 1
- [x] Counter never goes below 0
- [x] Decrement only fires for the configured form ID

### Edge Cases

- [x] If `vemgmt_settings['form_id']` is not set or empty, the validation and decrement hooks are skipped entirely (no fatal errors)
- [x] If `wp_shift_id` is missing or not a valid positive integer in the submitted data, submission is blocked with a generic "unable to verify" message
- [x] If the `vol_shift` post does not exist, submission is blocked with the same generic message
- [x] If `vol_shift_volunteers_needed` meta is missing (not set), treat as 0 — submission is blocked (safe default)
- [x] Counter never goes below 0 after decrement
- [x] If GF is not installed or not active, `VEMgmt_Form_Validation` registers an admin notice instead of GF hooks — no fatal errors
- [x] On a multi-page GF form, capacity check fires only on the final page submission (not intermediate page changes)
- [x] Validation error appears as a form-level error at the top of the form, not attached to a specific field

## Key Design Decisions

These were clarified during SpecFlow analysis and must be treated as settled contracts for implementation:

### `wp_shift_id` Field Contract
The GF form requires a hidden field to carry the WP post ID of the `vol_shift`. The server-side hook will identify it by iterating `$form['fields']` and matching on `adminLabel === 'wp_shift_id'`. This is more robust than relying on field position. The field value is set via the `field_values` shortcode parameter (already the pattern used for `shift_id`, `job_id`, etc.).

### Options Key Structure
Store settings as a single serialized array: `get_option('vemgmt_settings')` with keys:
- `form_id` (integer, `absint()` on save)
- `capacity_error_message` (string, `sanitize_text_field()` on save — plain text only)

Default error message: `"Sorry, this shift is now full. Please choose another shift."`

### Race Condition Tolerance
**Accept overbooking for MVP.** Salesforce is the authoritative source and resyncs within minutes. The decrement is a "best effort" immediately-after-submission courtesy update to prevent most double-bookings. A code comment must document this decision explicitly.

### Counter Floor
`vol_shift_volunteers_needed` must never go below 0. In `gform_after_submission`, decrement is: `max( 0, (int) $current - 1 )`. If the counter is already 0 or negative when the after-submission hook fires (race condition), the floor of 0 is applied silently (no error, no log needed for MVP).

### Missing Meta = Blocked
If `vol_shift_volunteers_needed` meta is not set on the post, `get_post_meta()` returns `''`. `'' > 0` is `false` in PHP, so the submission is blocked. This is the safe default and must be documented in a code comment.

### Multi-page Forms
If the GF form has multiple pages, the capacity check fires only on the final page. In `gform_validation`, check `rgpost('gform_target_page_number_' . $form_id) == 0` (GF convention for final submission) before running the capacity logic.

### Error Message Placement
Use a **form-level** validation error (set `$validation_result['is_valid'] = false`). Do not attach to a specific field, as the `wp_shift_id` hidden field would not be visible. GF renders form-level errors in its `.gform_validation_errors` container at the top of the form.

### Invalid `wp_shift_id` Handling
If `wp_shift_id` is missing from the entry or is not a valid positive integer, block the submission with a generic message: `"Unable to verify shift availability. Please refresh and try again."` (hard-coded, not the configurable message, since this is a data integrity issue not a capacity issue).

### Salesforce Resync Scope
Out of scope for this feature. The Object Sync field mapping is assumed to already handle writing `vol_shift_volunteers_needed` back from Salesforce. Verify this in the Object Sync admin configuration — if it is not mapped, that is a separate fix.

### Settings Page Capability
`current_user_can('manage_options')` — standard for site-wide configuration options.

### `formID` Block Attribute Migration
`render.php` will use: `$form_id = get_option('vemgmt_settings')['form_id'] ?? $attributes['formID'];`
The block attribute is NOT removed — it stays as a fallback. Existing block instances continue to work.

## Dependencies & Risks

- **Gravity Forms must be active.** Check `class_exists('GFForms')` in `VEMgmt_Form_Validation::__construct()` before registering any hooks. If GF is not active, register an `admin_notices` hook to warn the admin.
- **Hidden `wp_shift_id` field must exist in the GF form.** An editor must add a Hidden field to the GF form and set its admin label to `wp_shift_id`. The `field_values` shortcode parameter will pre-populate it. Without this field, the validation hook blocks all submissions. Document this setup step in a README or inline admin notice.
- **`field_values` vs. entry data:** GF `field_values` shortcode parameters pre-populate fields but only appear in the saved entry if the field is actually in the form. A hidden field with admin label `wp_shift_id` must be present in the GF form builder for the value to be in `$entry` at `gform_after_submission` time.
- **Salesforce field mapping:** Confirm in the Object Sync admin that `vol_shift_volunteers_needed` is mapped from the Salesforce object. If not, the self-correcting resync assumption is invalid.
- **formID block attribute fallback** ensures existing block instances are not broken by the migration to the global option.

## Implementation Files

### New Files

#### `includes/vemgmt-class-settings.php`
```php
class VEMgmt_Settings {
    // add_submenu_page under edit.php?post_type=vol_event
    // register_setting, add_settings_section, add_settings_field
    // render_page(), sanitize_form_id(), sanitize_message()
}
```

#### `includes/vemgmt-class-form-validation.php`
```php
class VEMgmt_Form_Validation {
    // add_filter( 'gform_validation', [ $this, 'check_shift_capacity' ] )
    // add_action( 'gform_after_submission', [ $this, 'decrement_shift_counter' ], 10, 2 )
    // check_shift_capacity( $validation_result )
    // decrement_shift_counter( $entry, $form )
    // get_wp_shift_id_from_entry( $entry, $form )
}
```

### Modified Files

#### `src/event-registration/render.php`
- Add `wp_shift_id=%s` to `$short_code` sprintf with `$shift_id` (WP post ID) as the value
- Change form ID source to `get_option('vemgmt_gf_form_id', $attributes['formID'])`

#### `volunteer-event-management.php`
- Add `vemgmt-class-settings.php` and `vemgmt-class-form-validation.php` to `includes()` method

## Sources & References

- Similar meta update patterns: `includes/vemgmt-class-object-sync.php:140-167`
- `vol_shift_volunteers_needed` read: `src/event-registration/render.php:50`
- GF shortcode construction: `src/event-registration/render.php:23-32`
- `vol_shift` CPT `show_in_menu` setting: `includes/vemgmt-class-post-types.php:124`
- Plugin bootstrap `includes()`: `volunteer-event-management.php:123-126`
- Gravity Forms `gform_validation` docs: https://docs.gravityforms.com/gform_validation/
- Gravity Forms `gform_after_submission` docs: https://docs.gravityforms.com/gform_after_submission/
