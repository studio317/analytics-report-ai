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

		return sanitize_text_field( strtolower( $host ) );
	}
}

if ( ! function_exists( 'analytics_report_ai_normalize_host_name' ) ) {
	/**
	 * Normalize host name.
	 *
	 * @param string $host Host name.
	 * @return string
	 */
	function analytics_report_ai_normalize_host_name( $host ) {
		$host = trim( (string) $host );

		if ( '' === $host ) {
			return '';
		}

		$host = preg_replace( '#^https?://#i', '', $host );
		$host = preg_replace( '#/.*$#', '', $host );
		$host = preg_replace( '#:\d+$#', '', $host );
		$host = strtolower( $host );

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

if ( ! function_exists( 'analytics_report_ai_is_valid_ga4_property_id' ) ) {
	/**
	 * Check whether GA4 property ID is valid.
	 *
	 * GA4 Data API uses numeric property IDs, not measurement IDs like G-XXXXXXXXXX.
	 *
	 * @param string $property_id GA4 property ID.
	 * @return bool
	 */
	function analytics_report_ai_is_valid_ga4_property_id( $property_id ) {
		$property_id = trim( (string) $property_id );

		if ( '' === $property_id ) {
			return true;
		}

		return (bool) preg_match( '/^\d+$/', $property_id );
	}
}

if ( ! function_exists( 'analytics_report_ai_is_measurement_id' ) ) {
	/**
	 * Check whether the given value looks like a GA4 measurement ID.
	 *
	 * @param string $value Value.
	 * @return bool
	 */
	function analytics_report_ai_is_measurement_id( $value ) {
		return (bool) preg_match( '/^G-[A-Z0-9]+$/i', trim( (string) $value ) );
	}
}