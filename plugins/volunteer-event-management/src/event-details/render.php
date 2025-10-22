<?php
$event_id = isset( $_GET['event_wp_id'] ) ? sanitize_text_field( wp_unslash( $_GET['event_wp_id'] ) ) : '';
$shift_id = isset( $_GET['shift_wp_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shift_wp_id'] ) ) : '';

?>
<div <?php echo get_block_wrapper_attributes( ); ?>>

	<?php
	if ( $event_id && $shift_id ) {
		$event_name = get_the_title( $event_id );
		$event_link = get_permalink( $event_id );
		$shift_times = VEMgmt_Helpers::get_formatted_shift_times( $shift_id, 'l, M j' );
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
	} else {
		?>
		<p><?php esc_html_e( 'Event details are not available. Please check your link and try again.', 'wp-rig' ); ?></p>
		<?php
	}
	?>
</div>
