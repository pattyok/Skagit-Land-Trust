<?php
/**
 * Load assets for our blocks.
 *
 * @package   CarkeekMappedPosts
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
class CarkeekMappedPosts_CustomPost {

	/**
	 * This plugin's instance.
	 *
	 * @var CarkeekMappedPosts_CustomPost
	 */
	private static $instance;

	/**
	 * Registers the plugin.
	 */
	public static function register() {
		if ( null === self::$instance ) {
			self::$instance = new CarkeekMappedPosts_CustomPost();
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
		add_action( 'init', array( $this, 'register_custompost' ) );
		add_action( 'init', array( $this, 'register_custompost_template' ), 9999 );
		add_action( 'acf/init', array( $this, 'my_acf_init' ) );
		add_filter( 'rest_prepare_skgt_location', array( $this, 'acf_to_rest_api_locations' ), 10, 3 );

	}

	/**
	 * Register post type
	 */
	public function register_custompost() {
		register_post_type(
			'skgt_location',
			array(
				'labels'       => array(
					'name'          => __( 'Locations', 'textdomain' ),
					'singular_name' => __( 'Location', 'textdomain' ),
					'menu_name'     => __( 'Locations', 'textdomain' ),
				),
				'public'       => true,
				'has_archive'  => false,
				'rewrite'      => array(
					'slug'       => 'locations',
					'with_front' => false,
				),
				'menu_icon'    => 'dashicons-location-alt',
				'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'excerpt', 'custom-fields' ),
				'show_in_rest' => true,
				'show_in_nav_menus' => false,
				'map_meta_cap' => true,
			)
		);

		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => _x( 'Activities', 'taxonomy general name', 'textdomain' ),
			'singular_name'     => _x( 'Activity', 'taxonomy singular name', 'textdomain' ),
			'search_items'      => __( 'Search Activities', 'textdomain' ),
			'all_items'         => __( 'All Activities', 'textdomain' ),
			'parent_item'       => __( 'Parent Activity', 'textdomain' ),
			'parent_item_colon' => __( 'Parent Activity:', 'textdomain' ),
			'edit_item'         => __( 'Edit Activity', 'textdomain' ),
			'update_item'       => __( 'Update Activity', 'textdomain' ),
			'add_new_item'      => __( 'Add New Activity', 'textdomain' ),
			'new_item_name'     => __( 'New Activity Name', 'textdomain' ),
			'menu_name'         => __( 'Activities', 'textdomain' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'activity' ),
			'show_in_rest'      => true,
		);

		register_taxonomy( 'skgt_location_cat', array( 'skgt_location' ), $args );

	}

	/**
	 * Add Blocks to Custom Post Template
	 */
	public function register_custompost_template() {
		$post_type_object           = get_post_type_object( 'skgt_location' );
		$post_type_object->template = array(
			array( 'core/paragraph' ),
		);

		// use the same template as a pattern for imported items

		register_block_pattern_category(
			'locations',
			array( 'label' => __( 'Location', 'skgt_locations' ) )
		);
		register_block_pattern(
			'skgt_locations/location-pattern',
			array(
				'title'       => __( 'Location Page Template', 'member-page-template' ),

				'description' => _x( 'Includes details block, space for paragraph text and an image.', 'Block pattern description', 'ck_members' ),

				'content'     => '<!-- wp:paragraph -->
				<p></p>
				<!-- /wp:paragraph -->
				',
				'categories'  => array( 'projects' ),
			)
		);
	}
	/**
	 * Google Maps API Key for ACF
	 *
	 * TO DO ADD to Site Settings
	 */
	function my_acf_init() {

		acf_update_setting( 'google_api_key', 'AIzaSyB8FLwBvlXOBwU3Jk69JP9tt1QX_VPxGr4' );

		// Check function exists.
		if ( function_exists( 'acf_register_block_type' ) ) {
			// Register a block.
			acf_register_block_type(
				array(
					'name'            => 'member_contact',
					'title'           => __( 'Member Location & Contact Details' ),
					'description'     => __( 'Contact details for the member.' ),
					'render_template' => plugin_dir_path( dirname( __FILE__ ) ) . 'templates/member-details.php',
					'category'        => 'widgets',
					'icon'            => 'location',
					'keywords'        => array( 'member', 'contact', 'address' ),
				)
			);
		}

	}

	/** If we have an override for the popup on the location we use that, otherwise we use the popup description from the project
	 *
	 * @param string $location id of location.
	 * @param string $project id of project.
	 */
	public static function get_popup_description( $location, $project ) {
		$popup = get_field( 'acf_location_pop-up_description', $location );
		if ( empty( $popup ) ) {
			$popup = get_field( 'acf_project_short_description', $project );
		}
		return $popup;
	}

	/** Get the image for the project.
	 *
	 * @param string $project id of project.
	 */
	public static function get_project_image( $project ) {
		$img_id = get_field( 'acf_project_thumbnail', $project );
		if ( empty( $img_id ) ) {
			$img_id = get_post_thumbnail_id( $project );
		}

		$img = wp_get_attachment_image_src( $img_id, 'medium' );
		return $img[0];
	}

	/** Get only the parent term for a project, if for some reason multiple are assigned, we just return the first one
	 *
	 * @param string $project id of project.
	 */
	public static function get_parent_term( $project ) {
		$all     = get_the_terms( $project, 'sws_project_cat' );
		$parents = array();
		$parent  = '';
		foreach ( $all as $term ) {
			if ( $term->parent == 0 ) {
				$parents[] = $term->name;
			}
		}
		if ( ! empty( $parents ) ) {
			$parent = $parents[0];
		}
		return $parent;
	}

	/**
	 * Pass Fields from parent project to the rest API.
	 *
	 * Items URL = http://trc-sound-water-stewards.local/wp-json/wp/v2/sws_locations?per_page=100&_fields=id,title,acf,project,cat
	 *
	 * @param object $response original rest response.
	 * @param object $post current post object.
	 * @param object $request current request.
	 */
	public function acf_to_rest_api_locations( $response, $post, $request ) {
		error_log( 'In acf_to_rest_api_locations' );
		//add the featured image
		if ( !empty( $response->data['featured_media'] ) ) {
			error_log( 'Getting featured image' );
			$img = wp_get_attachment_image_src( $response->data['featured_media'], 'medium' );
			$response->data['featured_image'] = $img[0];
		}
		if ( ! function_exists( 'get_fields' ) ) {
			return $response;
		}

		if ( isset( $post ) ) {
			$acf                   = get_fields( $post->id );
			$response->data['acf'] = $acf;
			// get the data for the associated project.
		}
		error_log(print_r($response->data, true));
		return $response;
	}

	/**
	 * Pass Fields from parent project to the rest API.
	 *
	 * Single Item = http://trc-sound-water-stewards.local/wp-json/wp/v2/sws_projects/420/?_fields=project
	 *
	 * @param object $response original rest response.
	 * @param object $post current post object.
	 * @param object $request current request.
	 */
	public function acf_to_rest_api_projects( $response, $post, $request ) {
		if ( ! function_exists( 'get_fields' ) ) {
			return $response;
		}

		if ( isset( $post ) ) {
			$project = $post->id;

			// get the fun fact data from the block.
			$post_blocks = parse_blocks( get_the_content( '', false, $project ) );

			$project_facts = null;
			// TODO Could write this better to be recursive but just going after what I think should be in place now.

			foreach ( $post_blocks as $block ) {
				if ( 'core/columns' === $block['blockName'] ) {
					foreach ( $block['innerBlocks'] as $column ) {
						foreach ( $column['innerBlocks'] as $innerblock ) {
							if ( 'acf/rhzm-project-stats' !== $innerblock['blockName'] ) {
								continue;
							}
							$project_facts = array(
								'metric' => $innerblock['attrs']['data']['acf_project_metric'],
								'text'   => $innerblock['attrs']['data']['acf_project_metric_description'],
							);
						}
					}
				}
			}

			$response->data['project'] = array(
				'title'    => get_the_title( $project ),
				'link'     => get_the_permalink( $project ),
				'excerpt'  => get_field( 'acf_project_sidebar_description_of_project', $project ),
				'img'      => self::get_project_image( $project ),
				'category' => self::get_parent_term( $project ),
				'facts'    => $project_facts,
			);
		}
		return $response;
	}

	/** Get the excerpt with a limit
	 *
	 * @param int $limit number of words to limit  the excerpt
	 */
	public static function excerpt( $limit ) {
		$excerpt = explode( ' ', get_the_excerpt(), $limit );
		if ( count( $excerpt ) >= $limit ) {
			array_pop( $excerpt );
			$excerpt = implode( ' ', $excerpt ) . '...';
		} else {
			$excerpt = implode( ' ', $excerpt );
		}
		$excerpt = preg_replace( '`[[^]]*]`', '', $excerpt );
		return $excerpt;
	}

	/**
	 * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
	 * placed under a 'children' member of their parent term.
	 *
	 * @param Array   $cats     taxonomy term objects to sort
	 * @param Array   $into     result array to put them in
	 * @param integer $parent_id the current parent ID to put them in
	 */
	public static function sort_terms_hierarchically( array &$cats, array &$into, $parent_id = 0 ) {
		foreach ( $cats as $i => $cat ) {
			if ( $cat->parent === $parent_id ) {
				$into[] = $cat;
				unset( $cats[ $i ] );
			}
		}

		foreach ( $into as $top_cat ) {
			$top_cat->children = array();
			self::sort_terms_hierarchically( $cats, $top_cat->children, $top_cat->term_id );
		}
	}



	/**
	 * Build the list for the terms from the hierarchical list
	 * child terms are in the 'children' items
	 *
	 * @param Array $cats     hierarchical list of term objects
	 */
	public static function build_terms_list( $cats, $tax ) {
		$html = '<ul>';
		foreach ( $cats as $i => $cat ) {
			$term_id = $tax . '-' . $cat->slug;
			$html   .= '<li data-id="' . $cat->term_id . '"><input type="checkbox" value="' . $cat->slug . '" id="' . $term_id . '"><label for="' . $term_id . '">' . $cat->name . '</label>';
			if ( ! empty( $cat->children ) ) {
				$html .= self::build_terms_list( $cat->children, $tax );
			}
			$html .= '</li>';
		}
		$html .= '</ul>';

		return $html;
	}


	/**
	 * Return div that we add the data to from the front end app
	 */
	public function carkeek_blocks_render_custom_posttype_archive( $attributes ) {
		// this is an array, but we are only accepting single value, in the future that may change.
		$tax_urls = array();
		$taxes    = array();
		$base_url = get_rest_url() . 'wp/v2/';

		// $tax        = $attributes['taxonomySelected'];
		$tax        = 'sws_project_cat';
		$taxes[]    = $tax;
		$rest_url   = $base_url . $tax . '?per_page=100&_fields=id,count,name,slug,parent';
		$tax_urls[] = $rest_url;

		$tax_urls      = apply_filters( 'ck_maparchive_taxurl', $tax_urls, $attributes );
		$tax_rest_urls = implode( '|', $tax_urls );
		$data_taxes    = implode( ',', $taxes );

		$data_url = $base_url . $attributes['postTypeSelected'] . '?per_page=100&_fields=id,title,acf,project,cat';

		$classname = 'wp-block-carkeek-map-archive';
		if ( isset( $attributes['align'] ) && ! empty( $attributes['align'] ) ) {
			$classname .= ' align' . $attributes['align'];
		}

		return '<div class="' . $classname . '"><div id="mapped-posts-map"
		data-post="' . $attributes['postTypeSelected'] . '"
		data-thumb="' . $attributes['popupImage'] . '"
		data-taxonomy="' . $data_taxes . '"
		data-taxurl="' . $tax_rest_urls . '"
		data-items="' . $data_url . '"
		data-address="' . $attributes['latFieldSelected'] . '"></div></div>';
	}

}

CarkeekMappedPosts_CustomPost::register();


