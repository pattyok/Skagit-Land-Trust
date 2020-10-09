<?php
/**
 * Template part for displaying the footer info
 *
 * @package Lazar_theme
 */

namespace WP_Rig\WP_Rig;

?>

<footer class="footer-main">
	<?php if ( is_active_sidebar( 'sidebar-footer-right' ) ) { ?>
		<section class="footer-section footer-right">
			<div class="widget_wrapper">
				<?php dynamic_sidebar( 'sidebar-footer-right' ); ?>
			</div>
		</section>
	<?php } ?>

		<section class="footer-section footer-left">
		<?php if ( is_active_sidebar( 'sidebar-footer-left' ) ) { ?>
			<div class="widget_wrapper">
				<?php dynamic_sidebar( 'sidebar-footer-left' ); ?>
			</div>
			<?php } ?>
			<div class="footer-social">
					<?php echo wp_kses_post( Lazar_theme()->get_social_links() ); ?>
				</div>
			<div class="colophon-wrapper">
				<div class="colophon">
					<ul class="colophon-info no-bullets">
						<li class="copyright">&copy; <?php echo esc_attr( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. All Rights Reserved.
						<a class="info-popover" href="#" data-popover="site-credit-pop">Site Credits</a>.
							<div class="gpopover no-bullets" id="site-credit-pop">
								<ul class="no-bullets">
									<li class="contact-info">Wesbite Development: <a href="https://carkeekstudios.com"  target="_blank">Carkeek Studios</a></li>
								</ul>
							</div>
						</li>
					</ul>

			</div>
			</div>
		</section>

</footer>
