<?php
/**
 * Template part for displaying the header branding
 *
 * @package Lazar_theme
 */

namespace WP_Rig\WP_Rig;

$mobile_logo_id = get_theme_mod( 'mobile_logo' );

?>

<div class="site-branding">
	<?php
	if ( ! empty( $mobile_logo_id ) ) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$dt_image       = wp_get_attachment_image( $custom_logo_id, 'full' );
		$mobile_image   = wp_get_attachment_image( $mobile_logo_id, 'full' );
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link hidden-mobile hidden-tablet" rel="home"><?php echo wp_kses_post( $dt_image ); ?></a>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link hidden-desktop" rel="home"><?php echo wp_kses_post( $mobile_image ); ?></a>
		<?php
	} else {
		?>
		<?php the_custom_logo(); ?>

	<?php } ?>

	<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>

	<?php
	$Lazar_theme_description = get_bloginfo( 'description', 'display' );
	if ( $Lazar_theme_description || is_customize_preview() ) {
		?>
		<p class="site-description">
			<?php echo $Lazar_theme_description; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
		</p>
		<?php
	}
	?>
</div><!-- .site-branding -->
