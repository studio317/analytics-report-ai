<?php
/**
 * Plugin Name: Analytics Report AI
 * Plugin URI: https://cuerda.org/
 * Description: Helps create Japanese analytics report drafts from GA4 data using AI.
 * Version: 0.1.0
 * Author: cuerda
 * Author URI: https://cuerda.org/
 * License: GPL-2.0-or-later
 * Text Domain: analytics-report-ai
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ANALYTICS_REPORT_AI_VERSION', '0.1.0' );
define( 'ANALYTICS_REPORT_AI_FILE', __FILE__ );
define( 'ANALYTICS_REPORT_AI_DIR', plugin_dir_path( __FILE__ ) );
define( 'ANALYTICS_REPORT_AI_URL', plugin_dir_url( __FILE__ ) );
define( 'ANALYTICS_REPORT_AI_OPTION_NAME', 'analytics_report_ai_settings' );
define( 'ANALYTICS_REPORT_AI_OPENAI_MODEL', 'gpt-5.4-mini' );

require_once ANALYTICS_REPORT_AI_DIR . 'includes/class-plugin.php';

register_activation_hook( ANALYTICS_REPORT_AI_FILE, array( 'Analytics_Report_AI_Plugin', 'activate' ) );

Analytics_Report_AI_Plugin::init();
