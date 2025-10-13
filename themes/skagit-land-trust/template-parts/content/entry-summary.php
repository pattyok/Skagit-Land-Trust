<?php
/**
 * Template part for displaying a post's summary
 *
 * @package skagit_land_trust
 */

namespace WP_Rig\WP_Rig;

?>

<div class="entry-summary">
	<?php echo wp_kses_post( skagit_land_trust()->get_custom_excerpt( 30 ) ); ?>
</div><!-- .entry-summary -->
