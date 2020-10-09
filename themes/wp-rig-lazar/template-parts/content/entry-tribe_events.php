<?php
/**
 * Template part for displaying a post
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;
//$custom_post  = new \CarkeekBlocks_CustomPost();
$bgcolor_class = 'has-background has-' . \CarkeekBlocks_Post_Meta::get_selected_or_random_color( $post->ID, $nbr = null ) . '-background-color';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry archive-entry alignfull ' . $bgcolor_class ); ?>>
	<div class="archive-entry--thumb">
		<?php


		if ( ! is_search() ) {
			include locate_template( 'template-parts/content/entry_thumbnail.php', false, false );
		}

		?>
		</div>
		<div class="archive-entry--content">
		<?php
		get_template_part( 'template-parts/content/entry_header', get_post_type() );

		echo wp_rig()->get_custom_excerpt( 30 );
		?>

		<div class="tribe-events-calendar-list__event-more-link arrow-link">
				<a
					href="<?php the_permalink(); ?>"
					title="<?php the_title(); ?>"
					rel="bookmark"
					class=""
				>
					<?php
					// phpcs:ignore
					echo "Learn More";
					?>
				</a>
		</div>

	</div>
</article><!-- #post-<?php the_ID(); ?> -->

