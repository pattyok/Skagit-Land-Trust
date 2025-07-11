<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package lazar_2025
 */

namespace WP_Rig\WP_Rig;

if ( ! lazar_2025()->is_primary_nav_menu_active() ) {
	return;
}

?>

<nav id="site-navigation" class="main-navigation nav--toggle-sub nav--toggle-small" aria-label="<?php esc_attr_e( 'Main menu', 'lazar-2025' ); ?>">


	<button class="header-toggle menu-toggle hamurger hamburger--spring" aria-label="<?php esc_attr_e( 'Open menu', 'lazar-2025' ); ?>" aria-controls="primary-menu" aria-expanded="false">
		<span class="menu-toggle-label menu-closed"><?php esc_html_e( 'Menu', 'lazar-2025' ); ?></span>
		<span class="menu-toggle-label menu-open"><?php esc_html_e( 'Close', 'lazar-2025' ); ?></span>
		<span class="hamburger-box">
			<span class="hamburger-inner"></span>
		</span>
	</button>


	<div class="primary-menu-container" id="primary-menu-container">
		<?php if ( is_active_sidebar( 'menu-extras' ) ) : ?>

			<div class="menu-extras hide-desktop-menu">
				<?php dynamic_sidebar( 'menu-extras' ); ?>
			</div>

		<?php endif; ?>
		<?php lazar_2025()->display_primary_nav_menu( array( 'menu_id' => 'primary-menu' ) ); ?>
		<?php if ( is_active_sidebar( 'menu-extras' ) ) : ?>

			<div class="menu-extras hide-mobile-menu">
				<?php dynamic_sidebar( 'menu-extras' ); ?>
			</div>

			<?php endif; ?>
		</div><!-- .primary-menu-container -->
</nav><!-- #site-navigation -->
