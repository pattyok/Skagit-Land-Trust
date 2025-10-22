<?php
/**
 * Register Helper Functions for Volunteer Event Management
 *
 * @package   VEMgmt
 * @author    Patty O'Hara from VEMgmt
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * VEMgmt_Helpers Class
 *
 * @since 1.6.0
 */
class VEMgmt_Helpers {

	/**
	 * Constructor
	 */
	public function __construct() {
		//add_action( 'rest_api_init', array( $this, 'pass_metafields_to_restapi' ) );
		//add_action( 'init', array( $this, 'register_meta_fields' ), 20 ); // Using ACF for now...
	}

	/** Get all shifts for an event

	 */
	public static function get_all_shifts_for_job( $event_id ) {
		$job_id = get_post_meta( $event_id, 'event_sf_id', true );
		var_dump($job_id);
		if ( empty( $job_id ) ) {
			return array();
		}
		$args = array(
			'post_type'      => 'vol_shift',
			'posts_per_page' => -1,
			'meta_key'       => 'vol_shift_job_id',
			'meta_value'     => $job_id,
			'orderby'        => 'meta_value',
			'meta_type'      => 'vol_shift_start_time',
			'order'          => 'ASC',
		);
		$shifts = get_posts( $args );
		return $shifts;

	}

	/** Get Formatted Shift Dates and Times */
	public static function get_formatted_shift_times( $shift_id, $date_format = 'M j, Y', $time_format = 'g:i a' ) {
		$start_date = get_post_meta( $shift_id, 'vol_shift_start_date', true );
		$start_time = get_post_meta( $shift_id, 'vol_shift_start_time', true );
		$end_time = get_post_meta( $shift_id, 'vol_shift_end_time', true );

		//format is unreliable from meta, so parse and reformat
		$dt_start = DateTime::createFromFormat( 'm/d/Y H:i:s', $start_date . ' ' . $start_time );
		$dt_end = DateTime::createFromFormat( 'm/d/Y H:i:s', $start_date . ' ' . $end_time );

		return array(
			'start_date' => $dt_start->format( $date_format ),
			'start_time' => $dt_start->format( $time_format ),
			'end_time'   => $dt_end->format( $time_format ),
		);
	}

	/** Make register links for the shifts */
	public static function get_shift_data_for_job( $event_id, $reg_link, $date_format = 'M j, Y', $time_format = 'g:i a' ) {
		$shift_data = array();
		$shifts = get_field( 'event_shifts', $event_id );
		if ( empty( $shifts ) ) {
			return $shift_data;
		}

		foreach ( $shifts as $shift ) {

			$shift_times = self::get_formatted_shift_times( $shift->ID, $date_format, $time_format );

			$vol_needed = get_post_meta( $shift->ID, 'vol_shift_volunteers_needed', true );
			$shift_id = get_post_meta( $shift->ID, 'vol_shift_id', true );
			$shift_data[] = array(
				"start_date" => $shift_times['start_date'],
				"start_time" => $shift_times['start_time'],
				"end_time"   => $shift_times['end_time'],
				"vol_needed" => $vol_needed,
				"reg_link"   => esc_url( add_query_arg(
					array(
						'shift_id' => $shift_id,
						'event_wp_id' => $event_id,
						'shift_wp_id' => $shift->ID,
					),
					$reg_link
				) ),
			);
		}
		// if shift_data is more than 1, sort by start date/time ascending
		usort( $shift_data, function ( $a, $b ) {
			$dt_a = DateTime::createFromFormat( 'M j, Y g:i a', $a['start_date'] . ' ' . $a['start_time'] );
			$dt_b = DateTime::createFromFormat( 'M j, Y g:i a', $b['start_date'] . ' ' . $b['start_time'] );
			return $dt_a <=> $dt_b;
		} );
		// get the last end time
		$last_shift = end( $shift_data );
		$last_end_time = $last_shift['end_time'] ?? '';
		$job_data = array(
			"shifts" => $shift_data,
			"last_end_time" => $last_end_time,
		);

		return $job_data;
	}
}

return new VEMgmt_Helpers();
