<?php
/**
 * Render your site front page, whether the front page displays the blog posts index or a static page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#front-page-display
 *
 * @package Lazar_theme
 */

namespace WP_Rig\WP_Rig;

get_header();

Lazar_theme()->print_styles( 'Lazar-theme-content', 'Lazar-theme-front-page' );

?>
	<main id="primary" class="site-main">
		<?php
		get_template_part( 'template-parts/content/page_header' );
		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content/entry_page' );
		}

		get_template_part( 'template-parts/content/pagination' );
		?>
	</main><!-- #primary -->
<?php
get_footer();
