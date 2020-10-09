<?php
$slider = get_field( 'slide' );
if ( have_rows( 'slide' ) ) :
	?>
	<div class="section-slider">
	<?php
	while ( have_rows( 'slide' ) ) :
		the_row();
		$img   = get_sub_field( 'image' );
		$style = get_sub_field( 'style' );
		?>
	<div class="slide">
		<?php echo wp_get_attachment_image( $img, 'x-large' ); ?>
		<div class="slide-content__wrapper">
			<div class="slide-content__<?php echo esc_attr( $style ); ?>">
				<?php the_sub_field( 'content' ); ?>
				<?php if ( get_sub_field( 'attribution' ) ) { ?>
				<div class="slide-content__attribution"><?php the_sub_field( 'attribution' ); ?></div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php endwhile; ?>
</div>
<?php endif; ?>
