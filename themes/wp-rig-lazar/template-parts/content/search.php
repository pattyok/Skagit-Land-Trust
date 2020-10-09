<?php
/**
 * Template part for displaying a post
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry search-entry' ); ?>>


	<div class="search-entry--content">
	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<?php
	get_template_part( 'template-parts/content/entry_summary', get_post_type() );
	$posttype = get_post_type();
	if ( 'page' === $posttype ) {
		if ( 0 !== $post->post_parent && ! empty( $post->post_parent ) ) {
			echo '<div class="search-breadcrumb"><span>' . wp_kses_post( get_the_title( $post->post_parent ) ) . '</span> &rsaquo; <a href="' . esc_url( get_the_permalink() ) . '">' . wp_kses_post( get_the_title() ) . '</a></div>';
		}
	} elseif ( 'post' === $posttype ) {
		$page_for_posts = get_option( 'page_for_posts' );
		echo '<div class="search-breadcrumb">';
		echo '<a href="' . esc_url( get_the_permalink( $page_for_posts ) ) . '">' . wp_kses_post( get_the_title( $page_for_posts ) ) . '</a> &rsaquo; ';
		get_template_part( 'template-parts/content/entry_taxonomies', get_post_type() );
		echo '</div>';
	} else {
		$pt_object = get_post_type_object( $posttype );
		$slug = '';
		if ( isset( $pt_object->rewrite ) && isset( $pt_object->rewrite['slug'] ) ) {
			$slug = $pt_object->rewrite['slug'];
		}
		echo '<div class="search-breadcrumb">';
		if ( ! empty( $slug ) ) {
			echo '<a href="/' . esc_attr( $slug ) . '">' . wp_kses_post( $pt_object->labels->name ) . '</a>';
		} else {
			echo '<span>' . wp_kses_post( $pt_object->labels->name ) . '</span>';
		}
		echo ' &rsaquo; <a href="' . esc_url( get_the_permalink() ) . '">' . wp_kses_post( get_the_title() ) . '</a></div>';
	}




	?>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
