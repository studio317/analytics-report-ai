<?php
/**
 * Uninstall cleanup for Studio317 Report Drafts for Google Analytics.
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
