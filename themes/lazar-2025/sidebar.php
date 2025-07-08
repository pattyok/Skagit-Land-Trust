<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package lazar_2025
 */

namespace WP_Rig\WP_Rig;

if ( ! lazar_2025()->is_primary_sidebar_active() ) {
	return;
}


?>
<aside id="secondary" class="primary-sidebar widget-area">
	<?php lazar_2025()->display_primary_sidebar(); ?>
</aside><!-- #secondary -->
