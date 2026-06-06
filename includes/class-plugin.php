<?php
/**
 * Main plugin bootstrap class.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Analytics_Report_AI_Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var Analytics_Report_AI_Plugin|null
	 */
	private static $instance = null;

	/**
	 * Initialize plugin.
	 *
	 * @return Analytics_Report_AI_Plugin
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->load_dependencies();

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( $this, 'boot' ) );
	}

	/**
	 * Load required files.
	 *
	 * @return void
	 */
	private function load_dependencies() {
		require_once ANALYTICS_REPORT_AI_DIR . 'includes/functions-utils.php';
		require_once ANALYTICS_REPORT_AI_DIR . 'includes/class-admin.php';
		require_once ANALYTICS_REPORT_AI_DIR . 'includes/class-settings.php';
		require_once ANALYTICS_REPORT_AI_DIR . 'includes/class-report-builder.php';
		require_once ANALYTICS_REPORT_AI_DIR . 'includes/class-ga4-client.php';
		require_once ANALYTICS_REPORT_AI_DIR . 'includes/class-report-data-formatter.php';
		require_once ANALYTICS_REPORT_AI_DIR . 'includes/class-openai-client.php';
		require_once ANALYTICS_REPORT_AI_DIR . 'includes/class-prompt-builder.php';
	}

	/**
	 * Load translation files.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'analytics-report-ai',
			false,
			dirname( plugin_basename( ANALYTICS_REPORT_AI_FILE ) ) . '/languages'
		);
	}

	/**
	 * Boot plugin.
	 *
	 * @return void
	 */
	public function boot() {
		if ( is_admin() ) {
			new Analytics_Report_AI_Admin();
		}
	}
}