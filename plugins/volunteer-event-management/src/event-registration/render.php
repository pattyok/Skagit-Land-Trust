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
		// Use the global form ID from settings; fall back to the per-block attribute for backward compatibility.
		$vemgmt_settings = get_option( 'vemgmt_settings', array() );
		$form_id         = ! empty( $vemgmt_settings['form_id'] ) ? $vemgmt_settings['form_id'] : $attributes['formID'];

		$short_code = sprintf( '[gravityform title="false" ajax="true" description="false" id="%s" field_values="shift_id=%s&amp;job_id=%s&amp;campaign_id=%s&amp;hours=%s&amp;start=%s&amp;event_name=%s&amp;event_link=%s&amp;wp_shift_id=%s"]',
			$form_id,
			$sf_shift_id,
			$sf_job_id,
			$sf_campaign_id,
			$sf_hours,
			$sf_shift_times['start_date'],
			$event_name,
			$event_link,
			$shift_id
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
		<?php
		//only render the form if there are volunteer shifts available. If there are no shifts, the form will not submit and the user will get an error message.
		$vol_needed = get_post_meta( $shift_id, 'vol_shift_volunteers_needed', true );
		if ( $vol_needed > 0 ) {
			echo do_shortcode( $short_code );
		} else {
			?>
			<p><?php esc_html_e( 'Uh-oh! This shift has no available volunteer spots.', 'wp-rig' ); ?></p>
			<?php
		}
	} else {
		?>
		<p><?php esc_html_e( 'Event details are not available. Please check your link and try again.', 'wp-rig' ); ?></p>
		<?php
	}
	?>
</div>
