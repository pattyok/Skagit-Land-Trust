<?php
/**
 * Template part for displaying the page header of events
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

use WP_Rig\WP_Rig\Helpers;

$shift_data = array();
if ( 'vol_event' == get_post_type() ) {
	$shift_data = \VEMgmt_Helpers::get_shift_data_for_job( get_the_ID(), '/volunteer-registration/', 'l, M j' );
}
?>
<div class="page-header-event">
	<div class="entry-title">
			<?php
			wp_rig()->make_breadcrumbs( get_post_type() );
			?>
	</div>
	<div class="event-header">
		<div class="event-details">
			<ul class="event-meta no-bullets">
				<?php
				if ( $shift_data && ! empty( $shift_data['shifts'] ) ) {
					//date formats are unreliable from meta so use parsed/formatted values
					$first_shift = $shift_data['shifts'][0];
					$event_date = $first_shift['start_date'];
					$event_time = $first_shift['start_time'];
					?>
					<li class="meta-label">
						<?php esc_html_e( 'Date and Time:', 'wp-rig' ); ?>
					</li>
					<li class="meta-value">
						<?php echo esc_html( $event_date ); ?>
					</li>
					<li class="meta-value">
						<?php echo esc_html($event_time ); ?>
						<?php if ( $shift_data['last_end_time'] ) : ?>
							- <?php echo esc_html( $shift_data['last_end_time'] ); ?>
						<?php endif; ?>
					</li>
				<?php
				} ?>
			</ul>
			<?php if ('vol_event' === get_post_type() ) : ?>
				<?php
				if ( $shift_data && ! empty( $shift_data['shifts'] ) 	) :
						?>
						<div class="event-registration-links">
							<?php
							foreach ( $shift_data['shifts'] as $shift ) {
								if ( $shift['vol_needed'] > 0 ) {
									echo '<a class="button" href="' . esc_url( $shift['reg_link'] ) . '">Sign Up</a>';
								} else {
									echo '<a class="button button-disabled" href="">Sign Up (Full)</a>';
								}
							}
							?>
						</div>
					<?php
					endif;
				endif;
				?>
		</div>
		<div class="event-image">
			<?php get_template_part( 'template-parts/content/entry-thumbnail' ); ?>
		</div>
</div>