<?php

$rel_posts = get_field( 'related_posts' );
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

?>
<div class="wp-block-group alignfull is-style-half-tone is-layout-constrained wp-block-group-is-layout-constrained">
<div class="carkeek-archive is-grid post-type-post is-layout-constrained ck-columns has-3-columns has-1-columns-mobile has-3-columns-tablet alignwide  wp-block-carkeek-blocks-related-posts-archive has-background has-bg-light-background-color">
	<h2 class="ck-custom-archive__headline">You might also like</h2>
	<div class="ck-custom-archive__list alignwide ck-columns__wrap" style="--ck-column-gap-vert: var(--wp--preset--spacing--30); --ck-column-gap: var(--wp--preset--spacing--30);">
<?php
// only show 3 posts
$n = 0;
while ( $n < 3 ) :
	if ( !isset( $rel_posts[ $n ] ) ) {
		break;
	}
	$post_id = $rel_posts[ $n ];
	$link_label = __( 'Read More', 'wp-rig' );
	// if event make sure event date is in the future
	if ( get_post_type( $post_id ) == 'skgt_event' || get_post_type( $post_id ) == 'vol_event' ) {
		$event_date = get_field( 'event_start_date', $post_id );
		$today      = date( 'Ymd' );
		$link_label = __( 'Join Us', 'wp-rig' );
		if ( $event_date < $today ) {
			continue;
		}
	}
	setup_postdata( $post_id );
	?>
		<div class="ck-columns-item ck-custom-archive-item  archive-item-id-1">
			<a class="ck-custom-archive-image-link layout-landscape" href="<?php the_permalink( $post_id ); ?>">
				<?php
				if ( has_post_thumbnail( $post_id ) ) {
					echo get_the_post_thumbnail( $post_id, 'large' );
				}
				?>
			</a>

				<h3 class="ck-custom-archive-title-header"><a class="ck-custom-archive-title_link" href="<?php the_permalink( $post_id ); ?>"><?php echo get_the_title( $post_id ); //phpcs:ignore ?></a></h3>
				<p class="ck-custom-archive-excerpt"><?php echo wp_trim_words( get_the_excerpt( $post_id ), 20 ); //phpcs:ignore ?></p>
				<a class="ck-custom-archive-more-link arrow-link" href="<?php the_permalink( $post_id ); ?>"><?php echo esc_html( $link_label ); ?><span class="screen-reader-text"><?php echo get_the_title( $post_id ); //phpcs:ignore ?></span></a>
			</a>
		</div>
	<?php
	$n++;
endwhile;
wp_reset_postdata();
?>
	</div>
</div>
</div>


