<?php // mu-plugins/action-scheduler-failure-alerts.php
/**
 * Email the site admin when a Salesforce sync Action Scheduler task fails.
 *
 * Added after the object_sync_for_salesforce_pull_check_records recurring
 * action failed silently on 2026-09-16 and Action Scheduler stopped
 * rescheduling it, which quietly broke Salesforce pulls until someone
 * noticed and re-saved the plugin's schedule settings.
 */

add_action( 'action_scheduler_failed_execution', 'mts_as_alert_on_failed_execution', 10, 3 );
add_action( 'action_scheduler_unexpected_shutdown', 'mts_as_alert_on_unexpected_shutdown', 10, 2 );

/**
 * Hook prefixes we want to be alerted about. Defaults to Object Sync for
 * Salesforce's own scheduled actions (pull/push checks). Widen with the
 * filter below if you want alerts for other plugins' scheduled actions too.
 */
function mts_as_alert_hook_prefixes() {
	return apply_filters( 'mts_action_scheduler_alert_hook_prefixes', array( 'object_sync_for_salesforce_' ) );
}

function mts_as_alert_should_notify( $hook ) {
	if ( '' === $hook ) {
		return false;
	}
	foreach ( mts_as_alert_hook_prefixes() as $prefix ) {
		if ( 0 === strpos( $hook, $prefix ) ) {
			return true;
		}
	}
	return false;
}

function mts_as_alert_get_hook_name( $action_id ) {
	if ( ! class_exists( 'ActionScheduler' ) ) {
		return '';
	}
	$action = ActionScheduler::store()->fetch_action( $action_id );
	return $action ? $action->get_hook() : '';
}

/**
 * Send the alert email, throttled to one per hook per 30 minutes so a
 * fast-retrying action doesn't flood the inbox before it gives up
 * rescheduling itself (Action Scheduler's own "consistently failing"
 * cutoff is 5 failures).
 */
function mts_as_alert_send( $action_id, $hook, $message ) {
	$throttle_key = 'mts_as_alert_' . md5( $hook );
	if ( get_transient( $throttle_key ) ) {
		return;
	}
	set_transient( $throttle_key, 1, 30 * MINUTE_IN_SECONDS );

	//$to      = apply_filters( 'mts_action_scheduler_alert_email', get_option( 'admin_email' ) );
	$to      = 'patty@carkeekstudios.com';
	$subject = sprintf( '[%s] Scheduled task failed: %s', wp_parse_url( home_url(), PHP_URL_HOST ), $hook );
	$body    = "A scheduled action failed on " . home_url() . ".\n\n"
		. "Hook: {$hook}\n"
		. "Action ID: {$action_id}\n"
		. "Error: {$message}\n\n"
		. "Check wp-admin -> Tools -> Scheduled Actions for the full log.\n"
		. "If this is a recurring action and it fails 5 times in a row, Action Scheduler stops rescheduling it entirely — it will need to be manually re-armed (e.g. by re-saving the schedule on the Object Sync for Salesforce settings page).";

	wp_mail( $to, $subject, $body );
}

function mts_as_alert_on_failed_execution( $action_id, $exception, $context ) {
	$hook = mts_as_alert_get_hook_name( $action_id );
	if ( ! mts_as_alert_should_notify( $hook ) ) {
		return;
	}

	$message = ( $exception instanceof Exception || $exception instanceof Throwable ) ? $exception->getMessage() : '';
	if ( '' === $message ) {
		$message = '(no exception message was provided)';
	}

	mts_as_alert_send( $action_id, $hook, $message . " (context: {$context})" );
}

function mts_as_alert_on_unexpected_shutdown( $action_id, $error ) {
	$hook = mts_as_alert_get_hook_name( $action_id );
	if ( ! mts_as_alert_should_notify( $hook ) ) {
		return;
	}

	$message = '(no error details available)';
	if ( is_array( $error ) && isset( $error['message'] ) ) {
		$message = $error['message'];
		if ( isset( $error['file'], $error['line'] ) ) {
			$message .= " in {$error['file']} on line {$error['line']}";
		}
	}

	mts_as_alert_send( $action_id, $hook, 'PHP fatal error: ' . $message );
}
