<?php
/**
 * WP_Rig\WP_Rig\AMP\Component class
 *
 * @package Lazar_theme
 */

namespace WP_Rig\WP_Rig\Helpers;

use Tribe__Template;
use WP_Rig\WP_Rig\Component_Interface;
use WP_Rig\WP_Rig\Templating_Component_Interface;

/**
 * Class for template helpers
 *
 * Exposes template tags:
 * * `Lazar_theme()->get_social_links()`
 *
 * @link https://wordpress.org/plugins/amp/
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'helpers';
	}

	/**
	 * Need this function even though its empty
	 */
	public function initialize() {
		add_filter( 'get_the_terms', array( $this, 'hide_categories_terms' ), 10, 3 );
		add_filter( 'excerpt_more', array( $this, 'my_theme_excerpt_more' ) );
		add_filter( 'carkeek_block_custom_post_people__link', array( $this, 'carkeek_block_custom_post_people__link' ), 10, 3 );
		add_filter( 'carkeek_block_custom_post_layout_people__query_args', array( $this, 'carkeek_block_custom_post_people__query_args' ), 10, 2 );
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `Lazar_theme()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags() : array {
		return array(
			'get_social_links'        => array( $this, 'get_social_links' ),
			'get_accreditation'       => array( $this, 'get_accreditation' ),
			'get_random_thumbnail'    => array( $this, 'get_random_thumbnail' ),
			'get_random_images_array' => array( $this, 'get_random_images_array' ),
			'get_custom_excerpt'      => array( $this, 'get_custom_excerpt' ),
			'make_social_share_links' => array( $this, 'make_social_share_links' ),
		);
	}

	/**
	 * Get Social links as defined in the Theme options
	 *
	 * @param string $styles Optional. Css classes to add to component.
	 * @return string Whether the AMP plugin is active and the current request is for an AMP endpoint.
	 */
	public function get_social_links( $styles = null ) : string {
		$social = get_field( 'social_icons', 'option' );
		$html   = '';
		if ( ! empty( $social ) ) {
			$html = '<ul class="list-inline social-links ' . $styles . '">';
			foreach ( $social as $soc ) {
				if ( ! empty( $soc['link'] ) ) {
					$html .= '<li><a href="' . $soc['link'] . '" title="' . $soc['link_title'] . '" target="_blank"><i class="icon-' . $soc['type'] . '"></i></a></li>';
				}
			}
			$html .= '</ul>';
		}
		return $html;
	}

	/**
	 * Get Accreditaion as defined in the Theme options
	 *
	 * @param string $styles Optional. Css classes to add to component.
	 * @return string Html of component.
	 */
	public function get_accreditation( $styles = null ) : string {
		$accs = get_field( 'accreditation', 'option' );
		$html = '';
		if ( ! empty( $accs ) ) {
			$html = '<ul class="no-list accreditation ' . $styles . '">';
			foreach ( $accs as $acc ) {
				if ( ! empty( $acc['image'] ) ) {
					$html .= '<li>';
					$img   = '<img src="' . $acc['image']['url'] . '" alt="' . $acc['link_title'] . '" />';
					if ( ! empty( $acc['link'] ) ) {
						$html .= '<a href="' . $acc['link'] . '" target="_blank">' . $img . '</a>';
					} else {
						$html .= $img;
					}
					$html .= '</li>';
				}
			}
			$html .= '</ul>';
		}
		return $html;
	}

	/**
	 * Hide the blog category when getting terms
	 *
	 * @param array $terms array of terms to filter.
	 * @param array $post_id id of current post.
	 * @param array $taxonomy id of current taxonomy.
	 * @return array $terms updated terms list.
	 */
	public function hide_categories_terms( $terms, $post_id, $taxonomy ) {
		if ( 'category' !== $taxonomy ) {
			return $terms;
		}

		$term_keys = array_map(
			function( $t ) {
				return $t->slug;
			},
			$terms
		);

		// get term to exclude by name, by id or maybe by slug.
		$exclude = array( 'blog' );
		if ( ! is_admin() ) {
			foreach ( $term_keys as $i => $key ) {
				if ( in_array( $key, $exclude ) ) {
					unset( $terms[ $i ] );
				}
			}
		}
		return $terms;
	}

	public function get_random_images_array() {
		$images = get_field( 'placeholder_images', 'options' );
		if ( ! empty( $images ) ) {
			$nbrs = range( 0, count( $images ) - 1 );
			shuffle( $nbrs );
			return $nbrs;
		} else {
			return array();
		}
	}

	/**
	 * Get a randomized image from the collection - use when blog post has no image
	 *
	 * @param string  $size desired image size.
	 * @param boolean $object whether to return just url or full object.
	 * @param integer $nbr tracks random number from index.
	 * @return array  $url of image.
	 * TODO Backup image as default;
	 */
	public function get_random_thumbnail( $size = 'large', $object = false, $nbr = null ) {
		$images = get_field( 'placeholder_images', 'options' );
		if ( empty( $images ) || 0 == count( $images ) ) {
			return;
		}

		if ( is_numeric( $nbr ) ) {
			$random = $nbr;
		} else {
			$random = rand( 0, count( $images ) - 1 );
		}

		$feat_image = $images[ $random ];

		if ( $object ) {

			return $feat_image;

		} else {
			$feat_image_url = $feat_image['sizes'][ $size ];

			return $feat_image_url;
		}

	}
	/**
	 * Customize the length of an excerpt
	 *
	 * @param integer $limit the number of words to return.
	 */
	public function get_custom_excerpt( $limit ) {
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
	 * Customize excerpt more ending
	 *
	 * @param string $more current value.
	 */
	public function my_theme_excerpt_more( $more ) {
		return '&hellip;';
	}

	/**
	 * Make New Window Script
	 */
	private function make_new_window() {
		return "onclick=\"javascript:window.open(this.href, '_blank', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;\"";
	}


	/**
	 * Make FB Links
	 *
	 * @param string $text optional text before the icon.
	 */
	private function make_fb_button( $text = null ) {
		$url = get_the_permalink();

		$fb_link = '<a class="share-link" href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $url ) . '"' . $this->make_new_window() . ' title="Share on Facebook"><i class="icon-facebook" aria-hidden="true"></i> ' . $text . '</a>';
		return $fb_link;
	}

	/**
	 * Make Twttter Links
	 *
	 * @param string $text optional text before the icon.
	 */
	private function make_twitter_button( $text = null ) {
		$url   = get_the_permalink();
		$title = get_the_title();
		$tweet = '<a class="share-link" href="http://twitter.com/intent/tweet?text=' . $title . '&url=' . $url . '"' . $this->make_new_window() . ' title="Share on Twitter"><i class="icon-twitter" aria-hidden="true"></i>' . $text . '</a>';
		return $tweet;
	}

	/**
	 * Make Email Links
	 *
	 * @param string $text optional text before the icon.
	 */
	private function make_email_button( $text = null ) {
		$url   = get_the_permalink();
		$title = get_the_title();
		$email = '<a class="share-link" href="mailto:?subject=' . $title . '&body=' . urlencode( $url ) . '" title="Share Via Email"><i class="icon-mail"  aria-hidden="true"></i> ' . $text . '</a>';
		return $email;
	}

	/**
	 * Make Social Links
	 */
	public function make_social_share_links() {
		echo '<ul class="social-share-links list-inline">
			<li class="list-inline-item social-share-links__label">Share: </li>
			<li class="list-inline-item">' . $this->make_fb_button() . '</li>
			<li class="list-inline-item">' . $this->make_twitter_button() . '</li>
			<li class="list-inline-item">' . $this->make_email_button() . '</li>
		</ul>';
	}

	public function carkeek_block_custom_post_people__query_args( $args, $attributes ) {
			$args['orderby'] = 'menu_order title';
			$args['order']   = 'ASC';
			return $args;
	}

	public function carkeek_block_custom_post_people__link( $link, $post_id, $attributes ) {
		$redirects = get_field( 'team_member_redirect', 'options' );
		foreach ( $redirects as $redirect ) {
			if ( has_term( $redirect['term'], 'poeple_category', $post_id ) ) {
				return $redirect['links_to'];
			}
		}
		return $link;
	}

	public function carkeek_block_custom_post_layout( $post_html, $post, $attributes ) {
		$post_type = $attributes['postTypeSelected'];
		$layout    = $post_type . '_' . $attributes['postLayout'];
		switch ( $layout ) {
			case 'post_grid':
			case 'post_list':
				ob_start();
					get_template_part( 'template-parts/content/entry', get_post_type() );
				return ob_get_clean();
			case 'tribe_events_list':
				ob_start();
				get_template_part( 'template-parts/content/entry', 'tribe_events' );
				return ob_get_clean();
			default:
				return $post_html;
		}
	}

}


