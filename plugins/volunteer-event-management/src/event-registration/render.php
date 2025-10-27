<?php
$event_id = get_query_var( 'wp_event_id' );
$shift_id = get_query_var( 'wp_shift_id' );

?>
<div <?php echo get_block_wrapper_attributes( ); ?>>

	<?php
	if ( $event_id && $shift_id ) {
		// get event details
		$event_name = get_the_title( $event_id );
		$event_link = get_permalink( $event_id );
		$shift_times = VEMgmt_Helpers::get_formatted_shift_times( $shift_id, 'l, M j' );
		// get data that we need to pass back to sf via hidden fields
		$sf_job_id = get_post_meta( $event_id, 'event_sf_id', true );
		$sf_shift_id = get_post_meta( $shift_id, 'vol_shift_id', true );
		$sf_campaign_id = get_post_meta( $event_id, 'event_campaign_id', true );
		$sf_hours = get_post_meta( $shift_id, 'vol_shift_duration', true );
		$sf_shift_times = VEMgmt_Helpers::get_formatted_shift_times( $shift_id, 'Y-m-d' );

		// need this again in sf format
		//$shift_time_sf = VEMgmt_Helpers::get_formatted_shift_times( $shift_id, 'y-m-d' );
		$short_code = sprintf( '[gravityform title="false" description="false" id="%s" field_values="shift_id=%s&amp;job_id=%s&amp;campaign_id=%s&amp;hours=%s&amp;start=%s&amp;event_name=%s&amp;event_link=%s"]',
			$attributes['formID'],
			$sf_shift_id,
			$sf_job_id,
			$sf_campaign_id,
			$sf_hours,
			$sf_shift_times['start_date'],
			$event_name,
			$event_link
		);

		?>

		<ul class="event-meta no-bullets">
			<li class="meta-label">
				<a target="_blank" href="<?php echo esc_url( $event_link ); ?>"><?php echo esc_html( $event_name ); ?></a>
			</li>
			<li class="meta-value">
				<?php echo esc_html( $shift_times['start_date'] ); ?>
			</li>
			<li class="meta-value">
				<?php echo esc_html( $shift_times['start_time'] ); ?>
				- <?php echo esc_html( $shift_times['end_time'] ); ?>
			</li>
		</ul>
		<?php echo do_shortcode( $short_code ); ?>
		<?php
	} else {
		?>
		<p><?php esc_html_e( 'Event details are not available. Please check your link and try again.', 'wp-rig' ); ?></p>
		<?php
	}
	?>
</div>
