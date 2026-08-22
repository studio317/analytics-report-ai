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
delete_option( 'analytics_report_ai_managed_oauth_site_instance_id' );
delete_option( 'analytics_report_ai_managed_oauth_tokens' );
