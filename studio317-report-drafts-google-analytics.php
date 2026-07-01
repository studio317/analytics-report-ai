<?php
/**
 * Plugin Name: Studio317 Report Drafts for Google Analytics
 * Description: Creates AI-assisted report drafts from GA4 data in the WordPress user language with admin review, editing, and copy tools.
 * Version: 0.1.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Kimiya Watabe / Studio317
 * Author URI: https://business.s317.com/
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: studio317-report-drafts-google-analytics
 * Domain Path: /languages
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
define( 'ANALYTICS_REPORT_AI_GOOGLE_OAUTH_TOKEN_OPTION_NAME', 'analytics_report_ai_oauth_tokens' );
define( 'ANALYTICS_REPORT_AI_OPENAI_MODEL', 'gpt-5.4-mini' );
define( 'ANALYTICS_REPORT_AI_MAX_REPORT_DAYS', 31 );
define( 'ANALYTICS_REPORT_AI_PAYLOAD_VERSION', '0.1.0-mvp-report-language' );

require_once ANALYTICS_REPORT_AI_DIR . 'includes/class-plugin.php';

register_activation_hook( ANALYTICS_REPORT_AI_FILE, array( 'Analytics_Report_AI_Plugin', 'activate' ) );

Analytics_Report_AI_Plugin::init();
