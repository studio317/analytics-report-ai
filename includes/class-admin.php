<?php
/**
 * Admin screen controller.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers admin menus, screens, and assets for Analytics Report AI.
 *
 * @since 0.1.0
 */
final class Analytics_Report_AI_Admin {

	/**
	 * Temporary OAuth state lifetime in seconds.
	 *
	 * @var int
	 */
	private const GOOGLE_OAUTH_STATE_TTL = 600;

	/**
	 * Settings screen instance.
	 *
	 * @var Analytics_Report_AI_Settings
	 */
	private $settings;

	/**
	 * Report builder screen instance.
	 *
	 * @var Analytics_Report_AI_Report_Builder
	 */
	private $report_builder;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->settings       = new Analytics_Report_AI_Settings();
		$this->report_builder = new Analytics_Report_AI_Report_Builder();

		add_action( 'admin_menu', array( $this, 'register_menus' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_post_analytics_report_ai_google_oauth_connect', array( $this, 'handle_google_oauth_connect' ) );
		add_action( 'admin_post_analytics_report_ai_google_oauth_callback', array( $this, 'handle_google_oauth_callback' ) );
	}

	/**
	 * Register admin menus.
	 *
	 * @return void
	 */
	public function register_menus() {
		add_menu_page(
			__( 'Analytics Report AI', 'analytics-report-ai' ),
			__( 'Analytics Report AI', 'analytics-report-ai' ),
			'manage_options',
			'analytics-report-ai',
			array( $this->report_builder, 'render_page' ),
			'dashicons-chart-area',
			65
		);

		add_submenu_page(
			'analytics-report-ai',
			__( 'Report Builder', 'analytics-report-ai' ),
			__( 'Report Builder', 'analytics-report-ai' ),
			'manage_options',
			'analytics-report-ai',
			array( $this->report_builder, 'render_page' )
		);

		add_submenu_page(
			'analytics-report-ai',
			__( 'Settings', 'analytics-report-ai' ),
			__( 'Settings', 'analytics-report-ai' ),
			'manage_options',
			'analytics-report-ai-settings',
			array( $this->settings, 'render_page' )
		);
	}

	/**
	 * Enqueue admin assets only on plugin screens.
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		$screen = get_current_screen();

		if ( ! $screen || false === strpos( $screen->id, 'analytics-report-ai' ) ) {
			return;
		}

		wp_enqueue_style(
			'analytics-report-ai-admin',
			ANALYTICS_REPORT_AI_URL . 'assets/css/admin.css',
			array(),
			ANALYTICS_REPORT_AI_VERSION
		);

		wp_enqueue_script(
			'analytics-report-ai-admin',
			ANALYTICS_REPORT_AI_URL . 'assets/js/admin.js',
			array(),
			ANALYTICS_REPORT_AI_VERSION,
			true
		);

		wp_localize_script(
			'analytics-report-ai-admin',
			'analyticsReportAiAdmin',
			array(
				'strings' => array(
					'directoryScopeDescription' => __( 'Directory scope matches paths that start with the entered path, such as /blog/.', 'analytics-report-ai' ),
					'pageScopeDescription'      => __( 'Page scope matches the exact normalized path, such as /about.', 'analytics-report-ai' ),
					'copied'                    => __( 'Copied.', 'analytics-report-ai' ),
					'copyFailed'                => __( 'Copy failed. Please select and copy manually.', 'analytics-report-ai' ),
					'nothingToCopy'             => __( 'Nothing to copy.', 'analytics-report-ai' ),
				),
			)
		);
	}

	/**
	 * Handle the local Google OAuth connect skeleton.
	 *
	 * This action intentionally does not redirect to Google, exchange tokens, or
	 * store credentials. It only establishes the future admin action boundary.
	 *
	 * @return void
	 */
	public function handle_google_oauth_connect() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(
				esc_html__( 'You do not have permission to manage Analytics Report AI credentials.', 'analytics-report-ai' ),
				esc_html__( 'Permission denied', 'analytics-report-ai' ),
				array( 'response' => 403 )
			);
		}

		check_admin_referer( 'analytics_report_ai_google_oauth_connect', 'analytics_report_ai_google_oauth_nonce' );

		$this->create_google_oauth_state_placeholder();

		wp_safe_redirect(
			$this->get_settings_url(
				array(
					'analytics_report_ai_google_oauth_status' => 'connect_state_prepared',
				)
			)
		);
		exit;
	}

	/**
	 * Handle the local Google OAuth callback skeleton.
	 *
	 * This callback validates only the temporary state placeholder and classifies
	 * query value presence without displaying or storing raw OAuth values.
	 *
	 * @return void
	 */
	public function handle_google_oauth_callback() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(
				esc_html__( 'You do not have permission to manage Analytics Report AI credentials.', 'analytics-report-ai' ),
				esc_html__( 'Permission denied', 'analytics-report-ai' ),
				array( 'response' => 403 )
			);
		}

		$callback_status = $this->classify_google_oauth_callback();

		wp_safe_redirect(
			$this->get_settings_url(
				array(
					'analytics_report_ai_google_oauth_status' => $callback_status,
				)
			)
		);
		exit;
	}

	/**
	 * Build the plugin Settings screen URL.
	 *
	 * @param array $args Optional query arguments.
	 * @return string
	 */
	private function get_settings_url( $args = array() ) {
		$url = admin_url( 'admin.php?page=analytics-report-ai-settings' );

		if ( empty( $args ) ) {
			return $url;
		}

		return add_query_arg( $args, $url );
	}

	/**
	 * Create a temporary user-scoped OAuth state placeholder.
	 *
	 * The raw state value is intentionally not displayed, logged, or returned
	 * from the local placeholder flow. A later OAuth redirect step can use this
	 * boundary without storing OAuth tokens or credentials here.
	 *
	 * @return void
	 */
	private function create_google_oauth_state_placeholder() {
		$user_id = get_current_user_id();

		if ( $user_id <= 0 ) {
			return;
		}

		$state = $this->generate_google_oauth_state_value();

		set_transient(
			$this->get_google_oauth_state_transient_key( $user_id ),
			array(
				'state_hash' => wp_hash( $state ),
				'created_at'  => time(),
			),
			self::GOOGLE_OAUTH_STATE_TTL
		);
	}

	/**
	 * Generate a random OAuth state value for the local placeholder boundary.
	 *
	 * @return string
	 */
	private function generate_google_oauth_state_value() {
		try {
			return bin2hex( random_bytes( 32 ) );
		} catch ( Exception $exception ) {
			unset( $exception );

			return wp_generate_password( 64, false, false );
		}
	}

	/**
	 * Classify a callback request without exposing raw OAuth query values.
	 *
	 * @return string
	 */
	private function classify_google_oauth_callback() {
		$user_id       = get_current_user_id();
		$state         = filter_input( INPUT_GET, 'state', FILTER_UNSAFE_RAW );
		$has_state     = is_string( $state ) && '' !== $state;
		$has_code      = filter_has_var( INPUT_GET, 'code' );
		$has_error     = filter_has_var( INPUT_GET, 'error' );
		$transient_key = $this->get_google_oauth_state_transient_key( $user_id );
		$stored_state  = get_transient( $transient_key );

		delete_transient( $transient_key );

		if ( ! $has_state ) {
			return 'callback_state_missing';
		}

		if ( false === $stored_state ) {
			return 'callback_state_expired';
		}

		if ( ! is_array( $stored_state ) || empty( $stored_state['state_hash'] ) || ! is_string( $stored_state['state_hash'] ) ) {
			return 'callback_state_invalid';
		}

		if ( ! hash_equals( $stored_state['state_hash'], wp_hash( $state ) ) ) {
			return 'callback_state_invalid';
		}

		if ( $has_error ) {
			return 'callback_state_valid_provider_error';
		}

		if ( $has_code ) {
			return 'callback_state_valid_code_present';
		}

		return 'callback_state_valid_no_code';
	}

	/**
	 * Get the transient key for the current user's temporary OAuth state.
	 *
	 * @param int $user_id User ID.
	 * @return string
	 */
	private function get_google_oauth_state_transient_key( $user_id ) {
		return 'analytics_report_ai_google_oauth_state_' . absint( $user_id );
	}
}
