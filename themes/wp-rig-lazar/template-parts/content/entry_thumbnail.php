<?php
/**
 * Template part for displaying a post's featured image
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

// Audio or video attachments can have featured images, so they need to be specifically checked.
$support_slug = get_post_type();
if ( 'attachment' === $support_slug ) {
	if ( wp_attachment_is( 'audio' ) ) {
		$support_slug .= ':audio';
	} elseif ( wp_attachment_is( 'video' ) ) {
		$support_slug .= ':video';
	}
}

if ( post_password_required() || ! post_type_supports( $support_slug, 'thumbnail' ) ) {
	return;
}

if ( is_singular( get_post_type() ) ) {
	if ( ! has_post_thumbnail() ) {
		return;
	}
	?>
	<div class="post-thumbnail">
		<?php the_post_thumbnail( 'wp-rig-featured', array( 'class' => 'skip-lazy' ) ); ?>
	</div><!-- .post-thumbnail -->
	<?php $caption = wp_get_attachment_caption( get_post_thumbnail_id() ); ?>
	<?php if ( ! empty( $caption ) ) { ?>
	<div class="image-caption"><?php echo esc_html( $caption ); ?></div>
	<?php } ?>
	<?php
} else {
	if ( ! has_post_thumbnail() ) {
		$feat_image = wp_rig()->get_random_thumbnail( 'large', true, $random_number );
		$feat_image_id = $feat_image['ID'];
	} else {
		$feat_image_id = get_post_thumbnail_id();
	}
	?>
	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php
		global $wp_query;
		if ( 0 === $wp_query->current_post ) {
			echo wp_get_attachment_image(
				$feat_image_id,
				'medium_large',
				false,
				array(
					'class' => 'skip-lazy',
					'alt'   => the_title_attribute(
						array(
							'echo' => false,
						)
					),
				)
			);
		} else {
			echo wp_get_attachment_image(
				$feat_image_id,
				'medium_large',
				false,
				array(
					'alt' => the_title_attribute(
						array(
							'echo' => false,
						)
					),
				)
			);
		}
		?>
	</a><!-- .post-thumbnail -->
	<?php
}
