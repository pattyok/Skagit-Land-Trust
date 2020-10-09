<?php
/**
 * Load assets for our blocks.
 *
 * @package   CarkeekBlocks
 * @author    Patty O'Hara
 * @link      https://carkeekstudios.com
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load general assets for our blocks.
 *
 * @since 1.0.0
 */
class CarkeekBlocks_CustomPost {

	/**
	 * This plugin's instance.
	 *
	 * @var CarkeekBlocks_CustomPost
	 */
	private static $instance;

	/**
	 * Registers the plugin.
	 */
	public static function register() {
		if ( null === self::$instance ) {
			self::$instance = new CarkeekBlocks_CustomPost();
		}
	}

	/**
	 * The Plugin slug.
	 *
	 * @var string $slug
	 */
	private $slug;

	/**
	 * The base URL path (without trailing slash).
	 *
	 * @var string $url
	 */
	private $url;

	/**
	 * The Plugin version.
	 *
	 * @var string $version
	 */
	private $version;

	/**
	 * The Constructor.
	 */
	private function __construct() {
		// add_action( 'init', array( $this, 'carkeek_blocks_register_portfolio' ), 9999 );
	}

	/**
	 * Register post type
	 */
	public function carkeek_blocks_register_portfolio() {
		register_post_type(
			'ck_portfolio',
			array(
				'labels'       => array(
					'name'          => __( 'Portfolio', 'textdomain' ),
					'singular_name' => __( 'Portfolio Item', 'textdomain' ),
				),
				'public'       => true,
				'has_archive'  => true,
				'rewrite'      => array( 'slug' => 'portfolio' ),
				'menu_icon'    => 'dashicons-book-alt',
				'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'excerpt' ),
				'show_in_rest' => true,
			)
		);
	}

	/**
	 * Render Custom Post Type Archive
	 *
	 * @param array $attributes Attributes passed to callback.
	 * @return string HTML of dynamic content.
	 */
	public function carkeek_blocks_render_custom_posttype_archive( $attributes ) {
		if ( empty( $attributes['postTypeSelected'] ) ) {
			return;
		}
		$layout = $attributes['postLayout'];
		$args   = array(
			'posts_per_page' => $attributes['numberOfPosts'],
			'post_type'      => $attributes['postTypeSelected'],
			'order'          => 'DESC',
		);
		if ( true === $attributes['filterByTaxonomy'] && ! empty( $attributes['taxonomySelected'] ) && ! empty( $attributes['taxTermsSelected'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => $attributes['taxonomySelected'],
					'field'    => 'term_id',
					'terms'    => explode( ',', $attributes['taxTermsSelected'] ),
				),
			);
		}

		$args  = apply_filters( 'carkeek_block_custom_post_layout_' . $attributes['postTypeSelected'] . '__query_args', $args, $attributes );
		$query = new WP_Query( $args );
		$posts = '';

		$class_pre         = 'wp-block-carkeek-blocks-custom-archive';
		$css_classes_outer = array(
			$class_pre,
			'is-' . $attributes['postLayout'],
			'post-type-' . $attributes['postTypeSelected'],
			'align' . $attributes['align'],
		);

		$css_classes_outer = apply_filters( 'carkeek_block_custom_post_layout__css_classes_outer', $css_classes_outer, $attributes );
		$block_start       = '<div class="' . implode( ' ', $css_classes_outer ) . '">';
		if ( ! empty( $attributes['headline'] ) ) {
			$tag_name     = 'h' . $attributes['headlineLevel'];
			$block_start .= '<' . $tag_name . ' class="' . $class_pre . '__headline">' . $attributes['headline'] . '</' . $tag_name . '>';
		}
		if ( $query->have_posts() ) {
			$posts           .= $block_start;
			$css_classes_list = array(
				$class_pre . '__list',
			);
			if ( 'link-tile' === $layout ) {
				$css_classes_list[] = 'wp-block-carkeek-blocks-link-tiles';
				$css_classes_list[] = 'wp-block-columns';
			}

			$css_classes_list = apply_filters( 'carkeek_block_custom_post_layout__css_classes_list', $css_classes_list, $attributes );
			$posts           .= '<div class="' . implode( ' ', $css_classes_list ) . '">';
			$count            = 0;
			while ( $query->have_posts() ) {
				$query->the_post();
				global $post;

				$css_classes_item = array(
					$class_pre . '__item',
				);
				if ( 'link-tile' === $layout ) {
					$css_classes_item[] = 'wp-block-carkeek-blocks-link-tile';
					$css_classes_item[] = 'wp-block-column';
					$css_classes_item[] = 'has-' . CarkeekBlocks_Post_Meta::get_selected_or_random_color( $post->ID, $count ) . '-background-color';
				}

				$css_classes_list = apply_filters( 'carkeek_block_custom_post_layout__css_classes_item', $css_classes_item, $attributes );

				$post_title     = get_the_title();
				$featured_image = get_the_post_thumbnail_url( null, 'medium_large' );
				$excerpt        = '';

				if ( true == $attributes['displayPostExcerpt'] ) {
					$excerpt = get_the_excerpt();
					$limit   = $attributes['excerptLength'];
					if ( str_word_count( $excerpt, 0 ) > $limit ) {
						$words   = str_word_count( $excerpt, 2 );
						$pos     = array_keys( $words );
						$excerpt = substr( $excerpt, 0, $pos[ $limit ] );
					}
				}
				error_log('1: -- ' . $post->ID);
				error_log('carkeek_block_custom_post_' . $attributes['postTypeSelected'] . '__link');
				$permalink = apply_filters( 'carkeek_block_custom_post_' . $attributes['postTypeSelected'] . '__link', get_permalink(), $post->ID, $attributes );
				error_log($permalink);
				$post_html = '<div class="' . implode( ' ', $css_classes_item ) . '">';
				if ( 'link-tile' == $attributes['postLayout'] ) {
					$post_html .= '<a class="wp-block-carkeek-blocks-link-tile__link wp-block-carkeek-blocks-link-tile__inner" href="' . esc_url( $permalink ) . '">
									<div style="background-image:url(' . $featured_image . ')"
										class="wp-block-carkeek-blocks-link-tile__img wp-block-carkeek-blocks-link-tile__inner"
										>
										<span class="link-tile__title">' . $post_title . '</span>
									</div>
									<span class="link-tile__hover_title">' . $excerpt . '</span>
									</a>';
				} else {

					$post_html .= '<a class="' . $class_pre . '__image_link" href="' . esc_url( $permalink ) . '">
									<img src="' . $featured_image . '"/>
								  </a>
								  <div class="' . $class_pre . '__content-wrap">
									  <a class="' . $class_pre . '__title_link" href="' . esc_url( $permalink ) . '"><h2>' . $post_title . '</h2></a>';
					if ( ! empty( $excerpt ) ) {
							$post_html .= '<div class="' . $class_pre . '__excerpt">' . $excerpt . '</div>';
					}
					$post_html .= '</div>';
				}
				$post_html .= '</div>';
				$posts     .= apply_filters( 'carkeek_block_custom_post_layout', $post_html, $post, $attributes );
				$count++;
			}
			$posts .= '</div></div>';
			wp_reset_postdata();
			return $posts;
		} else {
			if ( false === $attributes['hideIfEmpty'] ) {
				$block_content = '<div class="' . $class_pre . '__list empty">' . $attributes['emptyMessage'] . '</div>';
				return $block_start . $block_content . '</div>';
			} else {
				return;
			}
		}
	}

}

CarkeekBlocks_CustomPost::register();


