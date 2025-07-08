<?php
/**
 * Template part for displaying a post's summary
 *
 * @package lazar_2025
 */

namespace WP_Rig\WP_Rig;

?>

<div class="entry-summary">
	<?php echo wp_kses_post( lazar_2025()->get_custom_excerpt( 30 ) ); ?>
</div><!-- .entry-summary -->
