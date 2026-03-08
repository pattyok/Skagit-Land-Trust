<?php
/**
 * Template part for displaying the page header of events
 *
 * @package skagit_land_trust
 */

namespace WP_Rig\WP_Rig;

use WP_Rig\WP_Rig\Helpers;

$post_id       = get_the_ID();
$date_range     = \CarkeekEvents_Display::get_date_range_html( $post_id, '<br/>' );
$location_html  = \CarkeekEvents_Display::get_event_location_html( $post_id );
$organizer_html = \CarkeekEvents_Display::get_event_organizer_html( $post_id );
$event_link     = \CarkeekEvents_Display::get_event_link_html( $post_id );

?>
<div class="page-header-event">
	<div class="entry-title">
			<?php
			skagit_land_trust()->make_breadcrumbs( get_post_type() );
			?>
	</div>
	<div class="event-header">
		<div class="event-details">

				<?php if ( $date_range ) : ?>
					<div class="event-dates">
						<p class="event-meta-label">Date and Time:</p>
						<p class="event-dates-value"><?php echo wp_kses_post( $date_range ); ?></p>
					</div>
			<?php endif; ?>

			<?php if ( $location_html ) : ?>

				<div class="event-location">
					<p class="event-meta-label">Location:</p>
					<?php echo wp_kses_post( $location_html ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $organizer_html ) : ?>

				<div class="event-organizer">
					<p class="event-meta-label">Organizer:</p><?php echo wp_kses_post( $organizer_html ); ?></div>
			<?php endif; ?>

			<?php if ( $event_link ) : ?>
				<div class="event-cta"><?php echo wp_kses_post( $event_link ); ?></div>
			<?php endif; ?>
		</div>

		<div class="event-image">
			<?php get_template_part( 'template-parts/content/entry-thumbnail' ); ?>
		</div>
	</div>
</div>