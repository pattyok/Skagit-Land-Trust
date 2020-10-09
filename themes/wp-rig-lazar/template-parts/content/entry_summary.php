<?php
/**
 * Template part for displaying a post's summary
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>

<div class="entry-summary">
	<?php echo wp_kses_post( get_the_excerpt() ); ?>
</div><!-- .entry-summary -->
