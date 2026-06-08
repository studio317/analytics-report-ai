<?php
/**
 * Admin screen controller.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
}
