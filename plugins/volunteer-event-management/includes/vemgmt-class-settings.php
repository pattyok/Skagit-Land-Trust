<?php
/**
 * Settings page for Volunteer Event Management plugin.
 *
 * Adds a Settings submenu under the Volunteer Events admin menu.
 * Options are stored as a single array in the `vemgmt_settings` WP option.
 *
 * @package volunteer-event-management
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'VEMgmt_Settings' ) ) {

	/**
	 * Class VEMgmt_Settings
	 *
	 * @since 2.0.0
	 */
	class VEMgmt_Settings {

		/**
		 * The class instance.
		 *
		 * @var VEMgmt_Settings
		 */
		private static $instance;

		/**
		 * Returns the single instance.
		 *
		 * @return VEMgmt_Settings
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new VEMgmt_Settings();
				self::$instance->setup_hooks();
			}
			return self::$instance;
		}

		/**
		 * Register WordPress hooks.
		 */
		private function setup_hooks() {
			add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_settings_scripts' ) );
		}

		/**
		 * Add Settings submenu under the Volunteer Events post type menu.
		 */
		public function add_settings_page() {
			add_submenu_page(
				'edit.php?post_type=vol_event',
				__( 'Volunteer Event Settings', 'volunteer-event-management' ),
				__( 'Settings', 'volunteer-event-management' ),
				'manage_options',
				'vemgmt-settings',
				array( $this, 'render_page' )
			);
		}

		/**
		 * Enqueue admin scripts for the settings page only.
		 *
		 * Adds a small inline script that toggles the "Cutoff Hours" field
		 * based on the selected Cutoff Type, avoiding a full JS file for minimal logic.
		 *
		 * @param string $hook_suffix The current admin page hook suffix.
		 */
		public function enqueue_settings_scripts( string $hook_suffix ): void {
			// The hook suffix for add_submenu_page under vol_event CPT with slug vemgmt-settings.
			if ( 'vol_event_page_vemgmt-settings' !== $hook_suffix ) {
				return;
			}
			wp_add_inline_script(
				'jquery',
				"(function($){
					function toggleCutoffHours() {
						var row = $('#vemgmt_cutoff_hours').closest('tr');
						( 'x_hours_before' === $('#vemgmt_cutoff_type').val() )
							? row.show()
							: row.hide();
					}
					$(document).ready(function(){
						toggleCutoffHours();
						$('#vemgmt_cutoff_type').on('change', toggleCutoffHours);
					});
				})(jQuery);"
			);
		}

		/**
		 * Register settings, sections, and fields.
		 */
		public function register_settings() {
			register_setting(
				'vemgmt_settings_group',
				'vemgmt_settings',
				array( $this, 'sanitize_settings' )
			);

			// ── Gravity Forms section ──────────────────────────────────────────────
			add_settings_section(
				'vemgmt_gf_section',
				__( 'Gravity Forms Integration', 'volunteer-event-management' ),
				array( $this, 'render_gf_section_description' ),
				'vemgmt-settings'
			);

			add_settings_field(
				'vemgmt_form_id',
				__( 'Volunteer Registration Form ID', 'volunteer-event-management' ),
				array( $this, 'render_form_id_field' ),
				'vemgmt-settings',
				'vemgmt_gf_section'
			);

			add_settings_field(
				'vemgmt_capacity_error_message',
				__( 'Shift Full Error Message', 'volunteer-event-management' ),
				array( $this, 'render_error_message_field' ),
				'vemgmt-settings',
				'vemgmt_gf_section'
			);

			// ── Registration section ───────────────────────────────────────────────
			add_settings_section(
				'vemgmt_registration_section',
				__( 'Registration Settings', 'volunteer-event-management' ),
				array( $this, 'render_registration_section_description' ),
				'vemgmt-settings'
			);

			add_settings_field(
				'vemgmt_registration_base_url',
				__( 'Registration Page URL', 'volunteer-event-management' ),
				array( $this, 'render_registration_base_url_field' ),
				'vemgmt-settings',
				'vemgmt_registration_section'
			);

			add_settings_field(
				'vemgmt_cutoff_type',
				__( 'Registration Cutoff Type', 'volunteer-event-management' ),
				array( $this, 'render_cutoff_type_field' ),
				'vemgmt-settings',
				'vemgmt_registration_section'
			);

			add_settings_field(
				'vemgmt_cutoff_hours',
				__( 'Cutoff Hours Before Shift', 'volunteer-event-management' ),
				array( $this, 'render_cutoff_hours_field' ),
				'vemgmt-settings',
				'vemgmt_registration_section'
			);

			add_settings_field(
				'vemgmt_registration_closed_message',
				__( 'Registration Closed Message', 'volunteer-event-management' ),
				array( $this, 'render_registration_closed_message_field' ),
				'vemgmt-settings',
				'vemgmt_registration_section'
			);
		}

		/**
		 * Sanitize settings on save.
		 *
		 * @param array $input Raw input values.
		 * @return array Sanitized values.
		 */
		public function sanitize_settings( $input ) {
			$output = array();

			// ── Gravity Forms fields ───────────────────────────────────────────────
			$output['form_id']                = absint( $input['form_id'] ?? 0 );
			$output['capacity_error_message'] = sanitize_text_field( $input['capacity_error_message'] ?? '' );

			// ── Registration fields ────────────────────────────────────────────────

			// URL: use esc_url_raw() — normalizes the URL without HTML-encoding ampersands,
			// so the stored value remains usable as a raw URL in add_query_arg().
			$output['registration_base_url'] = esc_url_raw( $input['registration_base_url'] ?? '/volunteer-registration/' );

			// Cutoff type: allowlist validation — sanitize_text_field() would allow arbitrary
			// strings to be stored, causing the switch() in compute_cutoff() to silently fall through.
			$allowed_cutoff_types  = array( 'start_of_event', 'day_before_5pm', 'x_hours_before' );
			$raw_cutoff_type       = $input['cutoff_type'] ?? 'day_before_5pm';
			$output['cutoff_type'] = in_array( $raw_cutoff_type, $allowed_cutoff_types, true )
				? $raw_cutoff_type
				: 'day_before_5pm';

			// Cutoff hours: absint() + floor of 1 — a value of 0 or negative would produce
			// a cutoff in the future or present, effectively disabling the guard.
			$output['cutoff_hours'] = max( 1, absint( $input['cutoff_hours'] ?? 24 ) );

			// Closed message: plain text, no HTML.
			$output['registration_closed_message'] = sanitize_text_field( $input['registration_closed_message'] ?? '' );

			return $output;
		}

		/**
		 * Render the Gravity Forms section description.
		 */
		public function render_gf_section_description() {
			echo '<p>';
			esc_html_e(
				'Configure the Gravity Forms form used for volunteer shift registrations and the message shown when a shift is full.',
				'volunteer-event-management'
			);
			echo '</p>';
			echo '<p>';
			printf(
				/* translators: 1: opening strong tag, 2: closing strong tag */
				esc_html__( '%1$sSetup required:%2$s In the Gravity Forms editor, add a Hidden field to your registration form. Set its Admin Label to %1$swp_shift_id%2$s and its Parameter Name to %1$swp_shift_id%2$s. This field carries the volunteer shift post ID to the server for capacity validation.', 'volunteer-event-management' ),
				'<strong>',
				'</strong>'
			);
			echo '</p>';
		}

		/**
		 * Render the Registration section description.
		 */
		public function render_registration_section_description() {
			echo '<p>';
			esc_html_e(
				'Configure the registration page URL and when registration closes for each shift.',
				'volunteer-event-management'
			);
			echo '</p>';
		}

		/**
		 * Render the form ID field.
		 */
		public function render_form_id_field() {
			$settings = get_option( 'vemgmt_settings', array() );
			$form_id  = absint( $settings['form_id'] ?? 0 );
			?>
			<input
				type="number"
				min="0"
				name="vemgmt_settings[form_id]"
				id="vemgmt_form_id"
				value="<?php echo esc_attr( $form_id ); ?>"
				class="small-text"
			/>
			<p class="description">
				<?php esc_html_e( 'Enter the numeric ID of the Gravity Forms form used for volunteer shift registrations.', 'volunteer-event-management' ); ?>
			</p>
			<?php
		}

		/**
		 * Render the shift full error message field.
		 */
		public function render_error_message_field() {
			$settings = get_option( 'vemgmt_settings', array() );
			$message  = $settings['capacity_error_message'] ?? '';
			if ( empty( $message ) ) {
				$message = __( 'Sorry, this shift is now full. Please choose another shift.', 'volunteer-event-management' );
			}
			?>
			<input
				type="text"
				name="vemgmt_settings[capacity_error_message]"
				id="vemgmt_capacity_error_message"
				value="<?php echo esc_attr( $message ); ?>"
				class="regular-text"
			/>
			<p class="description">
				<?php esc_html_e( 'Message shown to volunteers when a shift has no available spots at the time they submit the registration form.', 'volunteer-event-management' ); ?>
			</p>
			<?php
		}

		/**
		 * Render the registration base URL field.
		 */
		public function render_registration_base_url_field() {
			$settings = get_option( 'vemgmt_settings', array() );
			$url      = $settings['registration_base_url'] ?? '/volunteer-registration/';
			?>
			<input
				type="url"
				name="vemgmt_settings[registration_base_url]"
				id="vemgmt_registration_base_url"
				value="<?php echo esc_url( $url ); ?>"
				class="regular-text"
			/>
			<p class="description">
				<?php esc_html_e( 'The URL of the volunteer registration page. Shift query parameters are appended automatically.', 'volunteer-event-management' ); ?>
			</p>
			<?php
		}

		/**
		 * Render the cutoff type select field.
		 */
		public function render_cutoff_type_field() {
			$settings    = get_option( 'vemgmt_settings', array() );
			$cutoff_type = $settings['cutoff_type'] ?? 'day_before_5pm';
			?>
			<select name="vemgmt_settings[cutoff_type]" id="vemgmt_cutoff_type">
				<option value="start_of_event" <?php selected( $cutoff_type, 'start_of_event' ); ?>>
					<?php esc_html_e( 'At shift start time', 'volunteer-event-management' ); ?>
				</option>
				<option value="day_before_5pm" <?php selected( $cutoff_type, 'day_before_5pm' ); ?>>
					<?php esc_html_e( '5:00 PM the day before the shift', 'volunteer-event-management' ); ?>
				</option>
				<option value="x_hours_before" <?php selected( $cutoff_type, 'x_hours_before' ); ?>>
					<?php esc_html_e( 'X hours before shift start', 'volunteer-event-management' ); ?>
				</option>
			</select>
			<p class="description">
				<?php esc_html_e( 'When registration closes for all shifts. This setting applies site-wide.', 'volunteer-event-management' ); ?>
			</p>
			<?php
		}

		/**
		 * Render the cutoff hours field.
		 * Shown/hidden via JavaScript based on the cutoff type selection.
		 */
		public function render_cutoff_hours_field() {
			$settings     = get_option( 'vemgmt_settings', array() );
			$cutoff_hours = max( 1, absint( $settings['cutoff_hours'] ?? 24 ) );
			?>
			<input
				type="number"
				min="1"
				name="vemgmt_settings[cutoff_hours]"
				id="vemgmt_cutoff_hours"
				value="<?php echo esc_attr( $cutoff_hours ); ?>"
				class="small-text"
			/>
			<p class="description">
				<?php esc_html_e( 'Number of hours before shift start when registration closes. Only used when "X hours before shift start" is selected above.', 'volunteer-event-management' ); ?>
			</p>
			<?php
		}

		/**
		 * Render the registration closed message field.
		 */
		public function render_registration_closed_message_field() {
			$settings = get_option( 'vemgmt_settings', array() );
			$message  = $settings['registration_closed_message'] ?? '';
			if ( empty( $message ) ) {
				$message = __( 'Registration is closed for this event', 'volunteer-event-management' );
			}
			?>
			<input
				type="text"
				name="vemgmt_settings[registration_closed_message]"
				id="vemgmt_registration_closed_message"
				value="<?php echo esc_attr( $message ); ?>"
				class="regular-text"
			/>
			<p class="description">
				<?php esc_html_e( 'Message shown when the registration cutoff time has passed for a shift.', 'volunteer-event-management' ); ?>
			</p>
			<?php
		}

		/**
		 * Render the settings page.
		 */
		public function render_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			?>
			<div class="wrap">
				<h1><?php esc_html_e( 'Volunteer Event Settings', 'volunteer-event-management' ); ?></h1>
				<form method="post" action="options.php">
					<?php
					settings_fields( 'vemgmt_settings_group' );
					do_settings_sections( 'vemgmt-settings' );
					submit_button();
					?>
				</form>
			</div>
			<?php
		}
	}
}

VEMgmt_Settings::instance();
