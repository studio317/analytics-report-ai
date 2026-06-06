<?php
/**
 * Utility functions.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'analytics_report_ai_get_default_host' ) ) {
	/**
	 * Get default host from home_url().
	 *
	 * @return string
	 */
	function analytics_report_ai_get_default_host() {
		$host = wp_parse_url( home_url(), PHP_URL_HOST );

		if ( empty( $host ) ) {
			return '';
		}

		return sanitize_text_field( $host );
	}
}

if ( ! function_exists( 'analytics_report_ai_get_default_settings' ) ) {
	/**
	 * Get default settings.
	 *
	 * @return array
	 */
	function analytics_report_ai_get_default_settings() {
		return array(
			'ga4_property_id'     => '',
			'google_auth_status'  => 'not_connected',
			'host_filter_enabled' => 1,
			'host_name'           => analytics_report_ai_get_default_host(),
			'google_tokens'       => array(),
			'openai_api_key'      => '',
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_get_settings' ) ) {
	/**
	 * Get plugin settings.
	 *
	 * @return array
	 */
	function analytics_report_ai_get_settings() {
		$defaults = analytics_report_ai_get_default_settings();
		$saved    = get_option( ANALYTICS_REPORT_AI_OPTION_NAME, array() );

		if ( ! is_array( $saved ) ) {
			$saved = array();
		}

		return wp_parse_args( $saved, $defaults );
	}
}