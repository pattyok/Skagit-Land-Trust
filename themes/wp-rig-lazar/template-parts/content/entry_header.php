<?php
/**
 * Template part for displaying a post's header
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>

<div class="entry-title">
	<?php get_template_part( 'template-parts/content/entry_taxonomies', get_post_type() ); ?>
	<?php
	if ( is_archive() || is_home() ) {
		?>
		<h2><a class="post-permalink" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php
		the_title();
		?>
		</a></h2>
		<?php
	} else {
		the_title( '<h1>', '</h1>' );
	}
	?>
	<div class="postmeta">
	<div class="postdate"><?php echo get_the_date( 'F j, Y' ); ?></div>
	<?php if ( get_field( 'byline' ) ) { ?>
		<div class="byline">By <?php the_field( 'byline' ); ?></div>
	<?php } ?>
	</div>
	<?php
	if ( ! is_archive() && ! is_home() ) {
		if ( function_exists( 'sharing_display' ) ) {
			sharing_display( '', true );
		}

		if ( class_exists( 'Jetpack_Likes' ) ) {
			$custom_likes = new \Jetpack_Likes();
			echo $custom_likes->post_likes( '' );
		}
		wp_rig()->make_social_share_links();
	}
	?>

</div>
