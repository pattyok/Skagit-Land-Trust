<?php
/**
 * Load assets for our blocks.
 *
 * @package   CarkeekSiteBlocks
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
class CarkeekSiteBlocks_Block_Assets {

	/**
	 * This plugin's instance.
	 *
	 * @var CarkeekSiteBlocks_Block_Assets
	 */
	private static $instance;

	/**
	 * Registers the plugin.
	 */
	public static function register() {
		if ( null === self::$instance ) {
			self::$instance = new CarkeekSiteBlocks_Block_Assets();
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
	 * The Plugin directory.
	 *
	 * @var string $dir
	 */
	private $dir;


	/**
	 * The Constructor.
	 */
	private function __construct() {
		$this->slug = 'carkeek-site-blocks';
		$this->url  = untrailingslashit( plugins_url( '/', dirname( __FILE__ ) ) );
		$this->dir  = plugin_dir_path( dirname( __FILE__ ) );

		add_action( 'enqueue_block_assets', array( $this, 'carkeek_site_blocks_enqueue_global_assets' ), 9999 );

		// add_action( 'init', array( $this, 'carkeek_blocks_register_assets' ), 9999 );
		// add_action( 'wp_enqueue_scripts', array( $this, 'carkeek_blocks_enqueue_frontend_assets' ) );
	}

	/**
	 * Enqueue block frontend JS & CSS
	 */
	/** Enqueue Assets
	 * Enqueue front end scripts to ensure that the dependencies are loaded.
	 */
	public function carkeek_site_blocks_enqueue_global_assets() {

		$frontend_js_path = '/src/expand-collapse-section/script.js';
		// load shared assets for specific blocks only.

		if ( has_block( 'carkeek-blocks/expand-collapse' ) ) {
			wp_enqueue_script(
				'expand-collapse',
				$this->url . $frontend_js_path,
				array( 'jquery', 'jquery-ui-slider' ),
				filemtime( $this->dir . $frontend_js_path ),
				true
			);
		}
	}

}

CarkeekSiteBlocks_Block_Assets::register();




