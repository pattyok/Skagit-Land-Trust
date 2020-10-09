<?php
/**
 * Template part for displaying a post's taxonomy terms
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

$taxonomies = wp_list_filter(
	get_object_taxonomies( $post, 'objects' ),
	array(
		'public' => true,
	)
);

?>
<div class="entry-taxonomies tribe_events_cats">
	<?php
	// Show terms for all taxonomies associated with the post.
	foreach ( $taxonomies as $taxonomy ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		/* translators: separator between taxonomy terms */
		$separator = _x( ' | ', 'list item separator', 'wp-rig' );

		switch ( $taxonomy->name ) {
			case 'tribe_events_cat':
				$class = str_replace( '_', '-', $taxonomy->name ) . '-links term-links';
				$list  = get_the_term_list( $post->ID, $taxonomy->name, '', esc_html( $separator ), '' );
				$list  = strip_tags( $list );
		}

		if ( empty( $list ) ) {
			continue;
		}
		?>
		<span class="<?php echo esc_attr( $class ); ?>">
			<?php
			printf(
				$list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			?>
		</span>
		<?php
	}
	?>
</div><!-- .entry-taxonomies -->
