<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package skagit_land_trust
 */

namespace WP_Rig\WP_Rig;

if ( ! skagit_land_trust()->is_primary_nav_menu_active() ) {
	return;
}

?>

<nav id="site-navigation" class="main-navigation nav--toggle-sub nav--toggle-small" aria-label="<?php esc_attr_e( 'Main menu', 'skagit-land-trust' ); ?>">


	<button class="header-toggle menu-toggle hamurger hamburger--spring" aria-label="<?php esc_attr_e( 'Open menu', 'skagit-land-trust' ); ?>" aria-controls="primary-menu" aria-expanded="false">
		<span class="menu-toggle-label menu-closed"><?php esc_html_e( 'Menu', 'skagit-land-trust' ); ?></span>
		<span class="menu-toggle-label menu-open"><?php esc_html_e( 'Close', 'skagit-land-trust' ); ?></span>
		<span class="hamburger-box">
			<span class="hamburger-inner"></span>
		</span>
	</button>


	<div class="primary-menu-container" id="primary-menu-container">
		<?php skagit_land_trust()->display_primary_nav_menu( array( 'menu_id' => 'primary-menu' ) ); ?>


		<div class="menu-extras hide-desktop-menu">
			<?php get_template_part( 'template-parts/header/navigation-top' ); ?>
		</div>


		</div><!-- .primary-menu-container -->
</nav><!-- #site-navigation -->
