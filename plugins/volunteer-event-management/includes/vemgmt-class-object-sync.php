<?php
/**
 * Define the salesforce functionality
 *
 * Defines the salesforce customizations for the site.
 * so that it is ready for translation.
 *
 * @link       https://carkeekstudios.com
 * @since      1.0.0
 *
 * @package    VEMgmt
 * @subpackage VEMgmt/includes
 */

/**
 * Class VEMgmt_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package     VEMgmt
 * @subpackage  Classes/VEMgmt_Run
 * @author      Patty O\'Hara
 * @since       1.0.0
 */
class VEMgmt_Object_Sync {


	/**
	 * ######################
	 * ###
	 * #### WordPress HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * On Class Init.
	 */
	public function __construct() {
		add_action( 'object_sync_for_salesforce_pull_object_allowed', array( $this, 'sf_pull_allowed' ), 10, 3 );
		//Run these to add extra data to the the objects.
		//add_filter( 'object_sync_for_salesforce_create_custom_wordpress_item', array( $this, 'add_update_object' ), 10, 1 );

		add_action( 'object_sync_for_salesforce_pull_success', array( $this, 'sf_pull_success' ), 10, 3 );
	}

	/**
	 * Helper Function to do Logging
	 *
	 * @param mixed $value Pass an object of unknown type and log it properly.
	 */
	public function log( $value ) {
		$show_logs = false;
		if ( 'local' == wp_get_environment_type() ) {
			$show_logs = true;
		}
		if ( $show_logs ) {
			if ( is_array( $value ) || is_object( $value ) ) {
				error_log( print_r( $value, true ) );
			} elseif ( is_bool( $value ) ) {
				if ( $value ) {
					error_log( 'TRUE' );
				} else {
					error_log( 'FALSE' );
				}
			} else {
				error_log( $value );
			}
		}
	}

	/**
	 * Only pull event details over if display on website is true and the start date is not in the past.
	 *
	 * @param string $allowed current rule on from sf.
	 * @param string $object_type Type of object we are working with.
	 * @param array  $object the object we are pulling.
	 * */
	public function sf_pull_allowed( $allowed, $object_type, $object ) {
		self::log( 'pull_allowed' );
		self::log( $object_type );
		self::log( $object );
		$pull_allowed = true;

		self::log( $object_type );
		self::log( $object );

		if ( 'GW_Volunteers__Volunteer_Job__c' === $object_type ) {
			$pull_allowed = false;

			if ( $object['GW_Volunteers__Display_on_Website__c'] == true) {
				if ( ! empty( $object['GW_Volunteers__First_Shift__c'] ) ) {
					try {
						$dt_utc      = new DateTime( $object['GW_Volunteers__First_Shift__c'], new DateTimeZone( 'UTC' ) );
						$dt_pacific  = clone $dt_utc;
						$dt_pacific->setTimezone( new DateTimeZone( 'America/Los_Angeles' ) );
						$now = new DateTime( 'now', new DateTimeZone( 'America/Los_Angeles' ) );

						// Only allow pull if the first shift is now or in the future
						if ( $dt_pacific->getTimestamp() >= $now->getTimestamp() ) {
							$pull_allowed = true;
						} else {
							$pull_allowed = false;
						}
					} catch ( Exception $e ) {
						// If parsing fails, default to allowing the pull when display flag is set
						$pull_allowed = true;
					}
				}
			}
		} elseif ( 'GW_Volunteers__Volunteer_Shift__c' === $object_type ) {
			$pull_allowed = false;
			if ( ! empty( $object['GW_Volunteers__Start_Date_Time__c'] ) ) {
				try {
					$dt_utc      = new DateTime( $object['GW_Volunteers__Start_Date_Time__c'], new DateTimeZone( 'UTC' ) );
					$dt_pacific  = clone $dt_utc;
					$dt_pacific->setTimezone( new DateTimeZone( 'America/Los_Angeles' ) );
					$now = new DateTime( 'now', new DateTimeZone( 'America/Los_Angeles' ) );

					// Only allow pull if the first shift is now or in the future
					if ( $dt_pacific->getTimestamp() >= $now->getTimestamp() ) {
						$pull_allowed = true;
					} else {
						$pull_allowed = false;
					}
				} catch ( Exception $e ) {
					// If parsing fails, default to allowing the pull when display flag is set
					$pull_allowed = true;
				}
			}
		}
		self::log( $pull_allowed );
		return $pull_allowed;
	}

	/** Modify the pull parameters to include more fields.
	 */
	public function modify_sf_pull_params( $wordpress_id, $salesforce_mapping, $object, $wordpress_id_field_name, $params ) {
		self::log( 'MODIFY_PULL_PARAMS' );
		self::log( $object );


	}


	/**
	 * Runs on Salesforce Update Object
	 *
	 * @param array $update_data Data passed to create function.
	 * */
	public function add_update_object( $update_data ) {
		self::log( 'UPDATE_OBJECT' );
		self::log( $update_data );
		$result = $update_data;
		return $result;
	}

	/** Somewhere the date/time stamp is getting translated incorrctly, so we manually save the date/time to our
	 * custom fields.
	 */
	function sf_pull_success( $op, $result, $synced_object ) {
		self::log( 'SF_PULL_SUCCESS' );
		//self::log( $op );
		//self::log( $result );
		//self::log( $synced_object );
		$sf_object   = $synced_object['salesforce_object'];
		self::log( $sf_object );
		if ( 'GW_Volunteers__Volunteer_Job__c' === $sf_object['attributes']['type'] ) {
			$wordpress_id = $result['parent'];
			if ( isset( $sf_object['GW_Volunteers__First_Shift__c'] ) ) {
				// Parse the incoming timestamp as UTC and convert to America/Los_Angeles (handles PST/PDT)
				$dt_utc = new DateTime( $sf_object['GW_Volunteers__First_Shift__c'], new DateTimeZone( 'UTC' ) );
				$dt_pacific = clone $dt_utc;
				$dt_pacific->setTimezone( new DateTimeZone( 'America/Los_Angeles' ) );

				// Update both date and time using Pacific time
				update_post_meta( $wordpress_id, 'event_start_date', $dt_pacific->format( 'm/d/Y' ) );
				update_post_meta( $wordpress_id, 'event_start_time', $dt_pacific->format( 'H:i:s' ) );
			}
		}
		if ( 'GW_Volunteers__Volunteer_Shift__c' === $sf_object['attributes']['type'] ) {
			$wordpress_id = $result['parent'];
			if ( isset( $sf_object['GW_Volunteers__Start_Date_Time__c'] ) ) {
				// Parse the incoming timestamp as UTC and convert to America/Los_Angeles (handles PST/PDT)
				$dt_utc = new DateTime( $sf_object['GW_Volunteers__Start_Date_Time__c'], new DateTimeZone( 'UTC' ) );
				$dt_pacific = clone $dt_utc;
				$dt_pacific->setTimezone( new DateTimeZone( 'America/Los_Angeles' ) );
				// Update both date and time using Pacific time
				update_post_meta( $wordpress_id, 'vol_shift_start_date', $dt_pacific->format( 'm/d/Y' ) );
				update_post_meta( $wordpress_id, 'vol_shift_start_time', $dt_pacific->format( 'H:i:s' ) );
				// Take duration from Salesforce field and calculate end time
				$duration_hours = isset( $sf_object['GW_Volunteers__Duration__c'] ) ? floatval( $sf_object['GW_Volunteers__Duration__c'] ) : 2.0;
				$dt_end = clone $dt_pacific;
				$dt_end->modify( '+' . $duration_hours . ' hours' );
				update_post_meta( $wordpress_id, 'vol_shift_end_time', $dt_end->format( 'H:i:s' ) );

				// Update relationship on job post if ACF Relationship field is used
				if( class_exists('ACF') ) :
				$job_sf_id = isset( $sf_object['GW_Volunteers__Volunteer_Job__c'] ) ? $sf_object['GW_Volunteers__Volunteer_Job__c'] : '';
				if ( ! empty( $job_sf_id ) ) {
					$args = array(
						'post_type'      => 'vol_event',
						'posts_per_page' => 1,
						'meta_key'       => 'event_sf_id',
						'meta_value'     => $job_sf_id,
						'post_status'    => 'any',
					);
					$job_posts = get_posts( $args );
					if ( ! empty( $job_posts ) ) {
						$relationship_field = 'event_shifts'; // ACF Relationship field name on vol_shift post type
						$job_post_id = $job_posts[0]->ID;
						$existing_shifts = get_field( $relationship_field, $job_post_id );
						if ( ! is_array( $existing_shifts ) ) {
							$existing_shifts = array();
						}
						// Add current shift to the relationship field if not already present
						if ( ! in_array( $wordpress_id, $existing_shifts ) ) {
							$existing_shifts[] = $wordpress_id;
							update_field( $relationship_field, $existing_shifts, $job_post_id );
						}
					}
				}

				endif;


			}
		}
	}
}

return new VEMgmt_Object_Sync();
