<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Lazar_theme
 */

namespace WP_Rig\WP_Rig;

get_header();

Lazar_theme()->print_styles( 'Lazar-theme-content' );
$nbrs = Lazar_theme()->get_random_images_array();

?>
	<main id="primary" class="site-main">
		<?php
		if ( have_posts() ) {

				get_template_part( 'template-parts/content/page_header' );

			if ( ! is_singular() ) {
				?>
				<div class="archive-wrapper">
				<?php
			}
			while ( have_posts() ) {
				the_post();

				if ( ! is_singular() ) {
					// track random number so that we can try not to repeat placeholder images.
					if ( 'post' === get_post_type() && ! has_post_thumbnail() ) {
						if ( count( $nbrs ) > 0 ) {
							$random_number = array_shift( $nbrs );
						} else {
							$nbrs          = Lazar_theme()->get_random_images_array();
							$random_number = array_shift( $nbrs );
						}
						// pass vars onto entry template.
						include locate_template( 'template-parts/content/entry.php', false, false );
					} else {
						get_template_part( 'template-parts/content/entry', get_post_type() );
					}
				} else {
					get_template_part( 'template-parts/content/single', get_post_type() );
				}
			}

			if ( ! is_singular() ) {
				?>
				<!-- end .archive-wrapper -->
			</div>
				<?php
				get_template_part( 'template-parts/content/pagination' );
			}
		} else {
			get_template_part( 'template-parts/content/error' );
		}
		?>
	</main><!-- #primary -->
<?php

get_footer();
