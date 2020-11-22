<?php
/**
 * WP_Rig\WP_Rig\Editor\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Editor;

use WP_Rig\WP_Rig\Component_Interface;
use function add_action;
use function add_theme_support;

/**
 * Class for integrating with the block editor.
 *
 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'editor';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', array( $this, 'action_add_editor_support' ) );
		add_filter( 'editorskit_block_editor_classnames', array( $this, 'editorskit_classnames' ) );
		add_action( 'init', array( $this, 'projects_template' ) );
		add_action( 'init', array( $this, 'people_template' ) );
	}

	/**
	 * Adds support for various editor features.
	 */
	public function action_add_editor_support() {
		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for default block styles.
		remove_theme_support( 'wp-block-styles' );

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );

		// Add support for wide-aligned images.
		add_theme_support( 'align-wide' );

		// Disable custom colors in the color picker.
		add_theme_support( 'disable-custom-colors' );

		// Disable custom gradients.
		add_theme_support( '__experimental-disable-custom-gradients' );

		// Disable gradients.
		add_theme_support( '__experimental-editor-gradient-presets', array() );

		// remove core block patterns
		remove_theme_support( 'core-block-patterns' );

		/**
		 * Add support for color palettes.
		 *
		 * To preserve color behavior across themes, use these naming conventions:
		 * - Use primary and secondary color for main variations.
		 * - Use `theme-[color-name]` naming standard for standard colors (red, blue, etc).
		 * - Use `custom-[color-name]` for non-standard colors.
		 *
		 * Add the line below to disable the custom color picker in the editor.
		 * add_theme_support( 'disable-custom-colors' );
		 * --color-theme-black: #1c2833;
		 */

		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => __( 'Green', 'wp-rig' ),
					'slug'  => 'theme-green',
					'color' => '#6d8d39',
				),
				array(
					'name'  => __( 'Green Light', 'wp-rig' ),
					'slug'  => 'theme-green-light',
					'color' => '#c0da95',
				),
				array(
					'name'  => __( 'Grey', 'wp-rig' ),
					'slug'  => 'theme-grey',
					'color' => '#757575',
				),
				array(
					'name'  => __( 'Grey Dark', 'wp-rig' ),
					'slug'  => 'theme-grey-dark',
					'color' => '#3c3b3b',
				),
				array(
					'name'  => __( 'Grey Light', 'wp-rig' ),
					'slug'  => 'theme-grey-light',
					'color' => '#f2f2f2',
				),

				array(
					'name'  => __( 'White', 'wp-rig' ),
					'slug'  => 'theme-white',
					'color' => '#fff',
				),
			)
		);

		/*
		 * Add support custom font sizes.
		 *
		 * Add the line below to disable the custom color picker in the editor.
		 * add_theme_support( 'disable-custom-font-sizes' );
		 */
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => __( 'Small', 'wp-rig' ),
					'shortName' => __( 'S', 'wp-rig' ),
					'size'      => 16,
					'slug'      => 'small',
				),
				array(
					'name'      => __( 'Medium', 'wp-rig' ),
					'shortName' => __( 'M', 'wp-rig' ),
					'size'      => 18,
					'slug'      => 'medium',
				),
				array(
					'name'      => __( 'Large', 'wp-rig' ),
					'shortName' => __( 'L', 'wp-rig' ),
					'size'      => 31,
					'slug'      => 'large',
				),
				array(
					'name'      => __( 'Larger', 'wp-rig' ),
					'shortName' => __( 'XL', 'wp-rig' ),
					'size'      => 39,
					'slug'      => 'larger',
				),
			)
		);

		/*
		 Register custom block patterns
		*
		*/
		register_block_pattern(
			'carkeek-blocks/offset-columns',
			array(
				'title'       => __( 'Offset Columns', 'wp-rig' ),
				'description' => _x( 'Two column layout 1/3 - 2/3, columns are stacked 100% on mobile and table', 'Block pattern description', 'wp-rig' ),
				'content'     => '<!-- wp:columns {"className":"lazar-cols"} -->
				<div class="wp-block-columns lazar-cols"><!-- wp:column {"className":"lazar-col-left"} -->
				<div class="wp-block-column lazar-col-left"></div>
				<!-- /wp:column -->

				<!-- wp:column {"className":"lazar-col-right"} -->
				<div class="wp-block-column lazar-col-right"><!-- wp:carkeek-blocks/title-block /-->

				<!-- wp:paragraph {"placeholder":"Add project description"} -->
				<p></p>
				<!-- /wp:paragraph --></div>
				<!-- /wp:column --></div>
				<!-- /wp:columns -->',
			)
		);

		register_block_pattern(
			'carkeek-blocks/image-columns',
			array(
				'title'       => __( 'Image Columns', 'wp-rig' ),
				'description' => _x( 'Two column layout 1/3 - 2/3, right column is hidden on mobile', 'Block pattern description', 'wp-rig' ),
				'content'     => '<!-- wp:columns {"align":"wide","className":"lazar-cols lazar-cols-images"} -->
				<div class="wp-block-columns alignwide lazar-cols lazar-cols-images"><!-- wp:column {"className":"lazar-col-left"} -->
				<div class="wp-block-column lazar-col-left"><!-- wp:image {"align":"wide","id":47,"sizeSlug":"full"} -->
				<figure class="wp-block-image alignwide size-full"><img src="http://lazar-landscape.local/wp-content/uploads/2019/02/textpage_des.png" alt="" class="wp-image-47"/></figure>
				<!-- /wp:image --></div>
				<!-- /wp:column -->

				<!-- wp:column {"className":"lazar-col-right","editorskit":{"mobile":false,"devices":false,"desktop":true,"tablet":true,"loggedin":true,"loggedout":true,"acf_visibility":"","acf_field":"","acf_condition":"","acf_value":"","migrated":false,"unit_test":false}} -->
				<div class="wp-block-column lazar-col-right editorskit-no-mobile"><!-- wp:image {"id":176,"sizeSlug":"large"} -->
				<figure class="wp-block-image size-large"><img src="http://lazar-landscape.local/wp-content/uploads/2019/02/textpage_banner-1024x506.png" alt="" class="wp-image-176"/></figure>
				<!-- /wp:image --></div>
				<!-- /wp:column --></div>
				<!-- /wp:columns -->',
			)
		);

		register_block_pattern(
			'carkeek-blocks/people-layout',
			array(
				'title'       => __( 'Image Columns', 'wp-rig' ),
				'description' => _x( 'Two column layout 1/3 - 2/3, right column is hidden on mobile', 'Block pattern description', 'wp-rig' ),
				'content'     => '<!-- wp:columns {"className":"lazar-cols-people"} -->
				<div class="wp-block-columns lazar-cols-people"><!-- wp:column {"className":"lazar-col-left"} -->
				<div class="wp-block-column lazar-col-left"><!-- wp:image {"id":893,"sizeSlug":"large"} -->
				<figure class="wp-block-image size-large"><img src="http://lazar-landscape.local/wp-content/uploads/2019/02/New-Project-46-1024x566.jpg" alt="" class="wp-image-893"/></figure>
				<!-- /wp:image -->

				<!-- wp:columns {"className":"lazar-gallery-inner"} -->
				<div class="wp-block-columns lazar-gallery-inner"><!-- wp:column {"className":"lazar-gallery-left"} -->
				<div class="wp-block-column lazar-gallery-left"><!-- wp:image {"id":564,"sizeSlug":"large"} -->
				<figure class="wp-block-image size-large"><img src="http://lazar-landscape.local/wp-content/uploads/2019/02/Boots.jpg" alt="" class="wp-image-564"/></figure>
				<!-- /wp:image --></div>
				<!-- /wp:column -->

				<!-- wp:column {"className":"lazar-gallery-right"} -->
				<div class="wp-block-column lazar-gallery-right"><!-- wp:image {"id":566,"sizeSlug":"large"} -->
				<figure class="wp-block-image size-large"><img src="http://lazar-landscape.local/wp-content/uploads/2019/02/Agave-1.jpg" alt="" class="wp-image-566"/></figure>
				<!-- /wp:image --></div>
				<!-- /wp:column --></div>
				<!-- /wp:columns -->

				<!-- wp:columns {"className":"lazar-gallery-inner"} -->
				<div class="wp-block-columns lazar-gallery-inner"><!-- wp:column {"className":"lazar-gallery-left"} -->
				<div class="wp-block-column lazar-gallery-left"><!-- wp:image {"id":565,"sizeSlug":"large"} -->
				<figure class="wp-block-image size-large"><img src="http://lazar-landscape.local/wp-content/uploads/2019/02/Doggies.jpg" alt="" class="wp-image-565"/></figure>
				<!-- /wp:image --></div>
				<!-- /wp:column -->

				<!-- wp:column {"className":"lazar-gallery-right"} -->
				<div class="wp-block-column lazar-gallery-right"><!-- wp:image {"id":567,"sizeSlug":"large"} -->
				<figure class="wp-block-image size-large"><img src="http://lazar-landscape.local/wp-content/uploads/2019/02/Drawing.jpg" alt="" class="wp-image-567"/></figure>
				<!-- /wp:image --></div>
				<!-- /wp:column --></div>
				<!-- /wp:columns --></div>
				<!-- /wp:column -->

				<!-- wp:column {"className":"lazar-col-right"} -->
				<div class="wp-block-column lazar-col-right"><!-- wp:carkeek-blocks/title-block /-->

				<!-- wp:heading {"placeholder":"Title"} -->
				<h2>Owner/lead designer</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"placeholder":"About me..."} -->
				<p>Pam is the principal and lead designer at Lazar. She’s been at Lazar for over 15 years, and so much of what makes Lazar special is thanks to Pam’s nurturing leadership style and great taste. Many of Pam’s clients are repeat customers, which is a testament to her listening skills and ability to give a client exactly what they want even though they can’t always articulate what that is. She is the emotional center of Lazar and creates a culture where everyone feels supported to do their best work. She’s an avid animal lover, a great cook, and of course a serious gardener.</p>
				<!-- /wp:paragraph --></div>
				<!-- /wp:column --></div>
				<!-- /wp:columns -->',
			)
		);
	}

	/**
	 * Add classname suggestions
	 *
	 * @param array $classes default classnames.
	 * @return array Add theme utility classes.
	 */
	public function editorskit_classnames( $classes ) {
		$theme_classes = array(
			'h1',
			'h2',
			'h3',
			'h3',
			'h5',
			'h6',
			'no-top-margin',
			'no-bottom-margin',
			'is-style-arrow-cta',
			'is-style-small-image',
			'is-page-header',
			'is-crop-form',
			'add-top-margin-small',
			'add-top-margin',
			'add-bottom-margin',
			'add-bottom-margin-small',
			'anchor-top',
			'anchor-bottom',
			'no-padding',
			'no-padding-top',
			'no-padding-right',
			'no-padding-bottom',
			'no-padding-left',
			'lazar-cols',
			'lazar-col-left',
			'lazar-col-right',
		);

		return $theme_classes;
	}

	/**
	 * Add group block to new pages.
	 *
	 * @link https://developer.wordpress.org/block-editor/developers/block-api/block-templates/
	 */
	public function projects_template() {
		$page_type_object           = get_post_type_object( 'projects' );
		$page_type_object->template = array(
			array(
				'carkeek-blocks/image-slider',
				array(
					'align'           => 'full',
					'autoPlay'        => true,
					'transitionSpeed' => 1000,
					'showDots'        => true,
					'fixHeight'       => true,
					'align'           => 'full',
				),
				array(
					array( 'core/image' ),
				),
			),
			array(
				'core/columns',
				array( 'className' => 'lazar-cols' ),
				array(
					array(
						'core/column',
						array( 'className' => 'lazar-col-left' ),
						array(),
					),
					array(
						'core/column',
						array( 'className' => 'lazar-col-right' ),
						array(
							array(
								'carkeek-blocks/title-block',
							),
							array(
								'core/paragraph',
								array(
									'placeholder' => 'Add project description',
								),
							),
						),
					),
				),
			),
		);
	}

	public function people_template() {
		$page_type_object           = get_post_type_object( 'people' );
		$page_type_object->template = array(
			array(
				'core/columns',
				array( 'className' => 'lazar-cols-people' ),
				array(
					array(
						'core/column',
						array( 'className' => 'lazar-col-left' ),
						array(
							array(
								'core/image',
								array( 'sizeSlug' => 'large' ),
								array(),
							),
							array(
								'core/columns',
								array( 'className' => 'lazar-gallery-inner' ),
								array(
									array(
										'core/column',
										array( 'className' => 'lazar-gallery-left' ),
										array(
											array(
												'core/image',
												array( 'sizeSlug' => 'large' ),
											),
										),
									),
									array(
										'core/column',
										array( 'className' => 'lazar-gallery-right' ),
										array(
											array(
												'core/image',
												array( 'sizeSlug' => 'large' ),
											),
										),
									),
								),
							),
							array(
								'core/columns',
								array( 'className' => 'lazar-gallery-inner' ),
								array(
									array(
										'core/column',
										array( 'className' => 'lazar-gallery-left' ),
										array(
											array(
												'core/image',
												array( 'sizeSlug' => 'large' ),
											),
										),
									),
									array(
										'core/column',
										array( 'className' => 'lazar-gallery-right' ),
										array(
											array(
												'core/image',
												array( 'sizeSlug' => 'large' ),
											),
										),
									),
								),
							),
						),
					),
					array(
						'core/column',
						array( 'className' => 'lazar-col-right' ),
						array(
							array(
								'carkeek-blocks/title-block',
							),
							array(
								'core/heading',
								array(
									'placeholder' => 'Title',
									'level'       => 2,
								),
							),
							array(
								'core/paragraph',
								array(
									'placeholder' => 'About me...',
								),
							),
						),
					),
				),
			),
		);
	}

}
