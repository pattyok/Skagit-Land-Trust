<?php
$featured   = get_field( 'events_featured_image', 'options' );
$page_title = ( ! empty( get_field( 'events_page_title', 'options' ) ) ) ? get_field( 'events_page_title', 'options' ) : 'Events';
$parent     = get_field( 'events_page_parent', 'options' );
$intro      = get_field( 'events_intro_text', 'options' );

if ( ! empty( $featured ) ) {
	$header_class .= 'has-post-thumbnail';
}
?>
	<header class="page-header <?php echo esc_attr( $header_class ); ?>">
		<?php
		if ( ! empty( $featured ) && isset( $featured['ID'] ) ) {
			echo wp_get_attachment_image(
				$featured['ID'],
				'wp-rig-featured',
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
		}
		?>
		<div class="entry-title">
			<?php

				echo '<span>' . wp_kses_post( $parent ) . '</span>';
				echo '<h1>' . wp_kses_post( $page_title ) . '</h1>';
			?>
		</div>
	</header><!-- .page-header -->
	<?php if ( ! empty( $intro ) ) { ?>
	<div class="events-custom-content"><?php the_field( 'events_intro_text', 'options' ); ?> </div>
	<?php } ?>
