<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package skagit_land_trust
 */

namespace WP_Rig\WP_Rig;

if ( ! skagit_land_trust()->is_primary_sidebar_active() ) {
	return;
}


?>
<aside id="secondary" class="primary-sidebar widget-area">
	<?php skagit_land_trust()->display_primary_sidebar(); ?>
</aside><!-- #secondary -->
