<?php
/**
 * Template part for displaying a post's header
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>

<div class="entry-title">
	<h2><?php echo tribe_get_start_date( null, false ) ?></h2>


	<?php get_template_part( 'template-parts/content/entry_taxonomies', get_post_type() ); ?>
	<h3 class="h4 event-title"><a class="post-permalink" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php
		the_title();
		?>
		</a></h3>

</div>
