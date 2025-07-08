<?php
/**
 * Template part for displaying a post
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>


<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry page-content single-entry' ); ?>>

	<?php if ( get_post_type() == 'post' ) : ?>
		<div class="entry-header">
		<div class="entry-title">
			<?php
			the_title( '<h1>', '</h1>' );
			?>
		</div>
		<div class="entry-date"><?php the_date(); ?></div>
	</div>
	<?php endif; ?>

	<?php
		get_template_part( 'template-parts/content/entry-content', get_post_type() );
	?>

</article><!-- #post-<?php the_ID(); ?> -->
<?php
	if ( get_post_type() == 'post' ) {
		?>
		<footer class="single-footer">
		<?php
		get_template_part( 'template-parts/content/pagination', get_post_type() );
		?>
		</footer>
	<?php
	}

if ( comments_open() ) {
	comments_template();
}
?>

