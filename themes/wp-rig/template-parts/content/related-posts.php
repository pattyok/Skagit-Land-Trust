<?php

$rel_posts = get_field( 'related_posts' );
var_dump($rel_posts);
if ( !is_array( $rel_posts ) ) {
	$rel_posts = array();
}
$count = count( $rel_posts );
if ( $count < 3 ) {
	/*  Fill up with posts  */
	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => 3 - $count,
	);
	if ( $count > 0 ) {
		$args['post__not_in'] = $rel_posts;
	}
	$additional_posts = get_posts( $args );
	$rel_posts        = array_merge( $rel_posts, wp_list_pluck( $additional_posts, 'ID' ) );
}
// foreach ( $rel_posts as $post_id ) :
// 	setup_postdata( $post_id );
// 	?>
// 		<div class="related-post-item">
// 			<a href="<?php the_permalink( $post_id ); ?>">
// 				<?php
// 				if ( has_post_thumbnail( $post_id ) ) {
// 					echo get_the_post_thumbnail( $post_id, 'medium' );
// 				}
// 				?>
// 				<h3 class="ck-custom-archive-title-header"><?php echo get_the_title( $post_id ); // phpcs:ignore ?></h3>
// 				<p class="ck-custom-archive-item-date"><?php echo get_the_date( '', $post_id ); // phpcs:ignore ?></p>
// 			</a>
// 		</div>
// 	?>
// 	<?php
// endforeach;
wp_reset_postdata();
?>
