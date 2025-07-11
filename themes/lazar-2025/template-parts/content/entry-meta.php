<?php
/**
 * Template part for displaying a post's header
 *
 * @package lazar_2025
 */

namespace WP_Rig\WP_Rig;

$post_categories = wp_get_post_categories( $post->ID, array( 'fields' => 'names' ) );
?>

	<div class="postmeta">
	<?php
	if ( 'post' == get_post_type() ) {
		if ( true == $args['show_social'] ) {
			lazar_2025()->make_social_share_links( true );
		}
	}

	?>
	</div>


