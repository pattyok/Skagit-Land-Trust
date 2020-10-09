<?php
/**
 * Register post meta.
 *
 * @package   CarkeekBlocks
 * @author    Patty O'Hara from CarkeekBlocks
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CarkeekBlocks_Post_Meta Class
 *
 * @since 1.6.0
 */
class CarkeekBlocks_Post_Meta {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'init', array( $this, 'register_meta' ) );
		add_filter( 'body_class', array( $this, 'set_body_classes' ) );
	}

	/**
	 * Register meta.
	 */
	public function register_meta() {
		register_meta(
			'post',
			'_carkeekblocks_title_hidden',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'boolean',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		register_meta(
			'post',
			'_carkeekblocks_featuredimage_hidden',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'boolean',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		register_meta(
			'post',
			'_carkeekblocks_archive_background_color',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		register_meta(
			'post',
			'byline',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);

	}

	public function set_body_classes( $classes ) {

		global $post;

		if ( is_page() || is_single() ) {
			if ( true == get_post_meta( $post->ID, '_carkeekblocks_featuredimage_hidden', true ) ) {
				$classes[] = 'carkeek-blocks-featured-image-hidden';
			}
			if ( true == get_post_meta( $post->ID, '_carkeekblocks_title_hidden', true ) ) {
				$classes[] = 'carkeek-blocks-title-hidden';
			}
		}
		return $classes;
	}

	public function get_selected_or_random_color( $post_id, $nbr = null ) {
		$selected_color = get_post_meta( $post_id, '_carkeekblocks_archive_background_color', true );
		$colors         = array(
			array(
				'name'  => __( 'Blue', 'wp-rig' ),
				'slug'  => 'theme-blue',
				'color' => '#a2c6d2',
			),
			array(
				'name'  => __( 'Green', 'wp-rig' ),
				'slug'  => 'theme-green',
				'color' => '#637d36',
			),
			array(
				'name'  => __( 'Yellow', 'wp-rig' ),
				'slug'  => 'theme-yellow',
				'color' => '#CAB44B',
			),
			array(
				'name'  => __( 'Orange', 'wp-rig' ),
				'slug'  => 'theme-orange',
				'color' => '#bc8a24',
			),
			array(
				'name'  => __( 'Red', 'wp-rig' ),
				'slug'  => 'theme-red',
				'color' => '#a55525',
			),
			array(
				'name'  => __( 'Green Light', 'wp-rig' ),
				'slug'  => 'theme-green-light',
				'color' => '#a7ad37',
			),
		);
		if ( empty( $selected_color ) ) {
			if ( isset( $nbr ) && $nbr < count( $colors ) ) {
				return $colors[ $nbr ]['slug'];
			} else {
				return $colors[ wp_rand( 0, count( $colors ) - 1 ) ]['slug'];
			}
		} else {
			return $selected_color;
		}
	}

}

return new CarkeekBlocks_Post_Meta();
