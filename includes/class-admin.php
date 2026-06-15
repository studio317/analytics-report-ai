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

		wp_safe_redirect(
			$this->get_settings_url(
				array(
					'analytics_report_ai_google_oauth_status' => 'connect_placeholder',
				)
			)
		);
		exit;
	}

	/**
	 * Handle the local Google OAuth callback skeleton.
	 *
	 * This callback intentionally ignores raw OAuth query values for now. Future
	 * steps can add state validation, error handling, token exchange, and safe
	 * status mapping without exposing codes, tokens, or raw provider responses.
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

		wp_safe_redirect(
			$this->get_settings_url(
				array(
					'analytics_report_ai_google_oauth_status' => 'callback_placeholder',
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
}
