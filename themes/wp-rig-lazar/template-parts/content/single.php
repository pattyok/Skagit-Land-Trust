<?php
/**
 * Template part for displaying a post
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>

	<?php
	if ( is_search() ) {
		get_template_part( 'template-parts/content/entry_summary', get_post_type() );
	} else {
		get_template_part( 'template-parts/content/entry_content', get_post_type() );
	}

	get_template_part( 'template-parts/content/entry_footer', get_post_type() );
	?>
</article><!-- #post-<?php the_ID(); ?> -->

<?php
if ( is_singular( get_post_type() ) ) {
	// Show post navigation only when the post type is 'post' or has an archive.

	// Show comments only when the post type supports it and when comments are open or at least one comment exists.
	if ( post_type_supports( get_post_type(), 'comments' ) && ( comments_open() || get_comments_number() ) ) {
		comments_template();
	}
}
