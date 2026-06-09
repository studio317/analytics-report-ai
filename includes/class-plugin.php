<?php
/**
 * Main plugin bootstrap class.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Boots the plugin, loads dependencies, and registers activation behavior.
 *
 * @since 0.1.0
 */
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
	 * Activate plugin.
	 *
	 * @param bool $network_wide Whether the plugin is network activated.
	 * @return void
	 */
	public static function activate( $network_wide = false ) {
		/*
		 * Multisite network activation is outside the MVP support scope.
		 * Keep the parameter for the WordPress activation hook signature.
		 */
		unset( $network_wide );

		require_once ANALYTICS_REPORT_AI_DIR . 'includes/functions-utils.php';

		analytics_report_ai_maybe_add_default_settings_option();
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->load_dependencies();

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
