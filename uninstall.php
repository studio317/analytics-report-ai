<?php
/**
 * Uninstall cleanup for Analytics Report AI.
 *
 * Deletes deterministic plugin-owned options only.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'analytics_report_ai_settings' );
delete_option( 'analytics_report_ai_oauth_tokens' );
