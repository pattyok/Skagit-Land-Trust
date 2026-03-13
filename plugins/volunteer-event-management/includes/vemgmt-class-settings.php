<?php
/**
 * Settings page for Volunteer Event Management plugin.
 *
 * Adds a Settings submenu under the Volunteer Events admin menu.
 * Options are stored as a single array in the `vemgmt_settings` WP option.
 *
 * @package volunteer-event-management
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'VEMgmt_Settings' ) ) {

	/**
	 * Class VEMgmt_Settings
	 *
	 * @since 2.0.0
	 */
	class VEMgmt_Settings {

		/**
		 * The class instance.
		 *
		 * @var VEMgmt_Settings
		 */
		private static $instance;

		/**
		 * Returns the single instance.
		 *
		 * @return VEMgmt_Settings
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new VEMgmt_Settings();
				self::$instance->setup_hooks();
			}
			return self::$instance;
		}

		/**
		 * Register WordPress hooks.
		 */
		private function setup_hooks() {
			add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}

		/**
		 * Add Settings submenu under the Volunteer Events post type menu.
		 */
		public function add_settings_page() {
			add_submenu_page(
				'edit.php?post_type=vol_event',
				__( 'Volunteer Event Settings', 'volunteer-event-management' ),
				__( 'Settings', 'volunteer-event-management' ),
				'manage_options',
				'vemgmt-settings',
				array( $this, 'render_page' )
			);
		}

		/**
		 * Register settings, sections, and fields.
		 */
		public function register_settings() {
			register_setting(
				'vemgmt_settings_group',
				'vemgmt_settings',
				array( $this, 'sanitize_settings' )
			);

			add_settings_section(
				'vemgmt_gf_section',
				__( 'Gravity Forms Integration', 'volunteer-event-management' ),
				array( $this, 'render_section_description' ),
				'vemgmt-settings'
			);

			add_settings_field(
				'vemgmt_form_id',
				__( 'Volunteer Registration Form ID', 'volunteer-event-management' ),
				array( $this, 'render_form_id_field' ),
				'vemgmt-settings',
				'vemgmt_gf_section'
			);

			add_settings_field(
				'vemgmt_capacity_error_message',
				__( 'Shift Full Error Message', 'volunteer-event-management' ),
				array( $this, 'render_error_message_field' ),
				'vemgmt-settings',
				'vemgmt_gf_section'
			);
		}

		/**
		 * Sanitize settings on save.
		 *
		 * @param array $input Raw input values.
		 * @return array Sanitized values.
		 */
		public function sanitize_settings( $input ) {
			$output                            = array();
			$output['form_id']                 = absint( $input['form_id'] ?? 0 );
			$output['capacity_error_message']  = sanitize_text_field( $input['capacity_error_message'] ?? '' );
			return $output;
		}

		/**
		 * Render the section description.
		 */
		public function render_section_description() {
			echo '<p>';
			esc_html_e(
				'Configure the Gravity Forms form used for volunteer shift registrations and the message shown when a shift is full.',
				'volunteer-event-management'
			);
			echo '</p>';
			echo '<p>';
			printf(
				/* translators: 1: opening strong tag, 2: closing strong tag */
				esc_html__( '%1$sSetup required:%2$s In the Gravity Forms editor, add a Hidden field to your registration form. Set its Admin Label to %1$swp_shift_id%2$s and its Parameter Name to %1$swp_shift_id%2$s. This field carries the volunteer shift post ID to the server for capacity validation.', 'volunteer-event-management' ),
				'<strong>',
				'</strong>'
			);
			echo '</p>';
		}

		/**
		 * Render the form ID field.
		 */
		public function render_form_id_field() {
			$settings = get_option( 'vemgmt_settings', array() );
			$form_id  = absint( $settings['form_id'] ?? 0 );
			?>
			<input
				type="number"
				min="0"
				name="vemgmt_settings[form_id]"
				id="vemgmt_form_id"
				value="<?php echo esc_attr( $form_id ); ?>"
				class="small-text"
			/>
			<p class="description">
				<?php esc_html_e( 'Enter the numeric ID of the Gravity Forms form used for volunteer shift registrations.', 'volunteer-event-management' ); ?>
			</p>
			<?php
		}

		/**
		 * Render the shift full error message field.
		 */
		public function render_error_message_field() {
			$settings = get_option( 'vemgmt_settings', array() );
			$message  = $settings['capacity_error_message'] ?? '';
			if ( empty( $message ) ) {
				$message = __( 'Sorry, this shift is now full. Please choose another shift.', 'volunteer-event-management' );
			}
			?>
			<input
				type="text"
				name="vemgmt_settings[capacity_error_message]"
				id="vemgmt_capacity_error_message"
				value="<?php echo esc_attr( $message ); ?>"
				class="regular-text"
			/>
			<p class="description">
				<?php esc_html_e( 'Message shown to volunteers when a shift has no available spots at the time they submit the registration form.', 'volunteer-event-management' ); ?>
			</p>
			<?php
		}

		/**
		 * Render the settings page.
		 */
		public function render_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			?>
			<div class="wrap">
				<h1><?php esc_html_e( 'Volunteer Event Settings', 'volunteer-event-management' ); ?></h1>
				<form method="post" action="options.php">
					<?php
					settings_fields( 'vemgmt_settings_group' );
					do_settings_sections( 'vemgmt-settings' );
					submit_button();
					?>
				</form>
			</div>
			<?php
		}
	}
}

VEMgmt_Settings::instance();
