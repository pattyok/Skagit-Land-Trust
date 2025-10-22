<?php
/**
 * Define the salesforce functionality
 *
 * Defines the salesforce customizations for the site.
 * so that it is ready for translation.
 *
 * @link       https://carkeekstudios.com
 * @since      1.0.0
 *
 * @package    VEMgmt
 * @subpackage VEMgmt/includes
 */

/**
 * Class VEMgmt_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package     VEMgmt
 * @subpackage  Classes/VEMgmt_Run
 * @author      Patty O\'Hara
 * @since       1.0.0
 */
class VEMgmt_Object_Sync_Admin {

	/**
	 * Our VEMgmt_Object_Sync_Admin constructor
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->add_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### WordPress HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function add_hooks() {

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_backend_scripts_and_styles' ), 20 );
		add_filter( 'object_sync_for_salesforce_settings_tabs', array( $this, 'minnpost_salesforce_additional_tabs' ), 10, 1 );
		add_action( 'admin_init', array( $this, 'minnpost_salesforce_settings_forms' ) );
	}

	/**
	 * ######################
	 * ###
	 * #### WordPress HOOK CALLBACKS
	 * ###
	 * ######################
	 */

	/**
	 * Enqueue the backend related scripts and styles for this plugin.
	 * All of the added scripts andstyles will be available on every page within the backend.
	 *
	 * @access  public
	 * @since   1.0.0
	 *
	 * @param string $hook the slug of the current page.
	 * @return  void
	 */
	public function enqueue_backend_scripts_and_styles( $hook ) {

		if ( 'settings_page_object-sync-salesforce-admin' !== $hook ) {
			return;
		}
		wp_enqueue_script( 'sitecustom-backend-scripts', VEMGMT_PLUGIN_URL . '/assets/backend-scripts.js', array( 'jquery' ), VEMGMT_VERSION, false );

	}

	/** Add Tabs to salesforce settings page.
	 *
	 * @param array $tabs Array of existing tabs.
	 */
	public function minnpost_salesforce_additional_tabs( $tabs ) {
		$tabs['advanced'] = 'Advanced';
		return $tabs;
	}


	/**
	 * Create default WordPress admin settings form for MinnPost-specific salesforce things
	 * This is for the Settings page/tab
	 */
	public function minnpost_salesforce_settings_forms() {
		$get_data                 = filter_input_array( INPUT_GET, FILTER_UNSAFE_RAW );
		$page                     = isset( $get_data['tab'] ) ? sanitize_key( $get_data['tab'] ) : 'settings';
		$section                  = isset( $get_data['tab'] ) ? sanitize_key( $get_data['tab'] ) : 'settings';
		$input_callback_default   = array( $this, 'display_input_field' );
		$input_checkboxes_default = array( $this, 'display_checkboxes' );
		$input_callback_button    = array( $this, 'display_buttons' );
		self::fields_minnpost_settings(
			'advanced',
			'advanced',
			array(
				'text'       => $input_callback_default,
				'checkboxes' => $input_checkboxes_default,
				'button'     => $input_callback_button,
			)
		);
	}
	/**
	 * Fields for the Log Settings tab
	 * This runs add_settings_section once, as well as add_settings_field and register_setting methods for each option
	 *
	 * @param string $page Current page.
	 * @param string $section Current section.
	 * @param array  $callbacks Callback for the fields.
	 */
	public function fields_minnpost_settings( $page, $section, $callbacks ) {
		add_settings_section( $page, ucwords( str_replace( '_', ' ', $page ) ), null, $page );
		$minnpost_salesforce_settings = array(
			'object_sync_manual_pull' => array(
				'title'    => 'Manual Pull',
				'callback' => $callbacks['button'],
				'page'     => $page,
				'section'  => $section,
				'args'     => array(
					'type'     => 'button',
					'desc'     => '',
					'constant' => '',
				),
			),
		);
		foreach ( $minnpost_salesforce_settings as $key => $attributes ) {
			$id       = $key;
			$name     = $key;
			$title    = $attributes['title'];
			$callback = $attributes['callback'];
			$page     = $attributes['page'];
			$section  = $attributes['section'];
			$args     = array_merge(
				$attributes['args'],
				array(
					'title'     => $title,
					'id'        => $id,
					'label_for' => $id,
					'name'      => $name,
				)
			);
			add_settings_field( $id, $title, $callback, $page, $section, $args );
			register_setting( $section, $id );
		}
	}

	/**
	 * Default display for <input> fields
	 *
	 * @param array $args Args for the input fields.
	 */
	public function display_input_field( $args ) {
		// TODO - format date fields.
		$type    = $args['type'];
		$id      = $args['label_for'];
		$name    = $args['name'];
		$desc    = $args['desc'];
		$checked = '';
		$class   = 'regular-text';
		if ( 'checkbox' === $type ) {
			$class = 'checkbox';
		}
		if ( ! isset( $args['constant'] ) || ! defined( $args['constant'] ) ) {
			$value = esc_attr( get_option( $id, '' ) );
			if ( 'checkbox' === $type ) {
				if ( '1' === $value ) {
					$checked = 'checked ';
				}
				$value = 1;
			}
			if ( '' === $value && isset( $args['default'] ) && '' !== $args['default'] ) {
				$value = $args['default'];
			}
			echo sprintf(
				'<input type="%1$s" value="%2$s" name="%3$s" id="%4$s" class="%5$s"%6$s>',
				esc_attr( $type ),
				esc_attr( $value ),
				esc_attr( $name ),
				esc_attr( $id ),
				sanitize_html_class( $class . esc_html( ' code' ) ),
				esc_html( $checked )
			);
			if ( '' !== $desc ) {
				echo sprintf(
					'<p class="description">%1$s</p>',
					esc_html( $desc )
				);
			}
		} else {
			echo sprintf(
				'<p><code>%1$s</code></p>',
				esc_html__( 'Defined in wp-config.php', 'object-sync-for-salesforce' )
			);
		}

	}
	/**
	 * Display button for manual synching
	 *
	 * @param array $args pass args to the button.
	 **/
	public function display_buttons( $args ) {
		$schedule_name = 'salesforce_pull';
		$next          = wp_next_scheduled( 'object_sync_for_salesforce_pull_check_records' );// returns timestamp or false.
		if ( ! $next ) {
			$next = 'No pull scheduled';
		} else {
			$next = get_date_from_gmt( gmdate( 'Y-m-d h:i:s', $next ), 'F j, Y h:i:s' );
		}
		$schedule_number = get_option( 'object_sync_for_salesforce_' . $schedule_name . '_schedule_number', '' );
		$schedule_unit   = get_option( 'object_sync_for_salesforce_' . $schedule_name . '_schedule_unit', '' );
		$time            = current_time( 'F j, Y h:i:s', 0 );
		$last_sync       = get_option( 'object_sync_for_salesforce_pull_last_sync_GW_Volunteers__Volunteer_Job__c' );

		if ( empty( $last_sync ) ) {
			$last_sync = 'None';
		} else {
			$last_sync = get_date_from_gmt( gmdate( 'Y-m-d h:i:s', $last_sync ), 'F j, Y h:i:s' );
		}

		echo '<strong>Last Sync:</strong> ' . wp_kses_post( $last_sync ) . '<br/><br/>';
		echo '<strong>Current Server Time:</strong> ' . wp_kses_post( $time ) . '<br/><br/>';
		echo '<strong>Manual Pull</strong><br/>';
		echo '<input type="text" name="sf-manual-pull-salesforce-id" placeholder="Salesforce Id"/>&nbsp;&nbsp;';
		echo '&nbsp;&nbsp;<a href="#" class="btn btn_salesforce_manual_pull" style="background-color:#008185;padding:10px;color:#fff;text-decoration:none" data-wp-object="tribe_events">Manual Pull</a>';
		echo '<div style="padding: 10px" class="manual-pull-status"></div>';
	}

	/**
	 * Display for multiple checkboxes
	 * Above method can handle a single checkbox as it is
	 *
	 * @param array $args Args passed to function.
	 */
	public function display_checkboxes( $args ) {
		$type    = 'checkbox';
		$name    = $args['name'];
		$options = get_option( $name, array() );
		foreach ( $args['items'] as $key => $value ) {
			$text    = $value['text'];
			$id      = $value['id'];
			$desc    = $value['desc'];
			$checked = '';
			if ( is_array( $options ) && in_array( $key, $options, true ) ) {
				$checked = 'checked';
			} elseif ( is_array( $options ) && empty( $options ) ) {
				if ( isset( $value['default'] ) && true === $value['default'] ) {
					$checked = 'checked';
				}
			}
			echo sprintf(
				'<div class="checkbox"><label><input type="%1$s" value="%2$s" name="%3$s[]" id="%4$s"%5$s>%6$s</label></div>',
				esc_attr( $type ),
				esc_attr( $key ),
				esc_attr( $name ),
				esc_attr( $id ),
				esc_html( $checked ),
				esc_html( $text )
			);
			if ( '' !== $desc ) {
				echo sprintf(
					'<p class="description">%1$s</p>',
					esc_html( $desc )
				);
			}
		}
	}
}
return new VEMgmt_Object_Sync_Admin();