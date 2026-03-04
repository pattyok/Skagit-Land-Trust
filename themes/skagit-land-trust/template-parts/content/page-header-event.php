<?php
/**
 * Template part for displaying the page header of events
 *
 * @package skagit_land_trust
 */

namespace WP_Rig\WP_Rig;

use WP_Rig\WP_Rig\Helpers;

$shift_data = array();
$shift_data = \VEMgmt_Helpers::get_shift_data_for_job( get_the_ID(), '/volunteer-registration/', 'l, M j' );

?>
<div class="page-header-event">
	<div class="entry-title">
			<?php
			skagit_land_trust()->make_breadcrumbs( get_post_type() );
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
						<?php echo esc_html($shift['start_time'] ); ?>
						<?php if ( $shift['end_time'] ) : ?>
							- <?php echo esc_html( $shift['end_time'] ); ?>
						<?php endif; ?>
					</li>

					<li class="meta-value meta-signup">

					<?
					if ( $shift['vol_needed'] > 0 ) {
							echo '<a class="button" href="' . esc_url( $shift['reg_link'] ) . '">Sign Up</a>';
					} else {
						echo '<div class="button button-disabled">Sign Up</div><span class="full-shift-note">This event is currently full</span>';
					}

					?>
</li>
</ul>
				<?php
				} }?>

		</div>
		<div class="event-image">
			<?php get_template_part( 'template-parts/content/entry-thumbnail' ); ?>
		</div>
</div>