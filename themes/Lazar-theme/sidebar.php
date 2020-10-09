<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Lazar_theme
 */

namespace WP_Rig\WP_Rig;

if ( ! Lazar_theme()->is_primary_sidebar_active() ) {
	return;
}

Lazar_theme()->print_styles( 'Lazar-theme-sidebar', 'Lazar-theme-widgets' );

?>
<aside id="secondary" class="primary-sidebar widget-area">
	<?php Lazar_theme()->display_primary_sidebar(); ?>
</aside><!-- #secondary -->
