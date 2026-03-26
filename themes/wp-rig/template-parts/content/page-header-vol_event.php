<?php
/**
 * Template part for displaying the page header of events
 *
 * @package skagit_land_trust
 */

namespace WP_Rig\WP_Rig;

use WP_Rig\WP_Rig\Helpers;

// Resolve settings and messages once before the loop — get_signup_button_html()
// is called once per shift, so we avoid repeated get_option() calls inside it.
$vemgmt_settings  = get_option( 'vemgmt_settings', array() );
$registration_url = ! empty( $vemgmt_settings['registration_base_url'] )
	? $vemgmt_settings['registration_base_url']
	: '/volunteer-registration/';

$full_msg   = ! empty( $vemgmt_settings['capacity_error_message'] )
	? $vemgmt_settings['capacity_error_message']
	: 'This event is currently full';

$closed_msg = ! empty( $vemgmt_settings['registration_closed_message'] )
	? $vemgmt_settings['registration_closed_message']
	: 'Registration is closed for this event';

$shift_data = \VEMgmt_Helpers::get_shift_data_for_job( get_the_ID(), $registration_url, 'l, M j' );

?>
<div class="page-header-event">
	<div class="entry-title">
			<?php
			wp_rig()->make_breadcrumbs( get_post_type() );
			?>
	</div>
	<div class="event-header">
		<div class="event-details">

				<?php
				if ( $shift_data && ! empty( $shift_data['shifts'] ) ) {
					foreach ( $shift_data['shifts'] as $shift ) {

					?>
					<ul class="event-meta event-shift no-bullets">
					<li class="meta-value meta-date">
						<?php echo esc_html( $shift['start_date'] ); ?><br/>
						<?php echo esc_html( $shift['start_time'] ); ?>
						<?php if ( $shift['end_time'] ) : ?>
							- <?php echo esc_html( $shift['end_time'] ); ?>
						<?php endif; ?>
					</li>

					<li class="meta-value meta-signup">
					<?php echo \VEMgmt_Helpers::get_signup_button_html( $shift, $full_msg, $closed_msg ); ?>
</li>
</ul>
				<?php
				} }?>

		</div>
		<div class="event-image">
			<?php get_template_part( 'template-parts/content/entry-thumbnail' ); ?>
		</div>
</div>
