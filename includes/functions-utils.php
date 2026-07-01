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
			'google_oauth_client' => array(),
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

		$settings = wp_parse_args( $saved, $defaults );

		if ( ! isset( $settings['google_tokens'] ) || ! is_array( $settings['google_tokens'] ) ) {
			$settings['google_tokens'] = array();
		}

		if ( ! isset( $settings['google_oauth_client'] ) || ! is_array( $settings['google_oauth_client'] ) ) {
			$settings['google_oauth_client'] = array();
		}

		$settings['google_oauth_client']['client_id'] = isset( $settings['google_oauth_client']['client_id'] ) && is_scalar( $settings['google_oauth_client']['client_id'] )
			? analytics_report_ai_sanitize_credential_value( (string) $settings['google_oauth_client']['client_id'] )
			: '';

		$settings['google_oauth_client']['client_secret'] = isset( $settings['google_oauth_client']['client_secret'] ) && is_scalar( $settings['google_oauth_client']['client_secret'] )
			? analytics_report_ai_sanitize_credential_value( (string) $settings['google_oauth_client']['client_secret'] )
			: '';

		unset( $settings['openai_api_key'] );

		unset( $settings['google_tokens']['access_token'] );

		$settings['google_auth_status'] = 'not_connected';

		return $settings;
	}
}

if ( ! function_exists( 'analytics_report_ai_maybe_add_default_settings_option' ) ) {
	/**
	 * Add the default settings option without autoloading it on new installs.
	 *
	 * @return void
	 */
	function analytics_report_ai_maybe_add_default_settings_option() {
		if ( false !== get_option( ANALYTICS_REPORT_AI_OPTION_NAME, false ) ) {
			return;
		}

		add_option( ANALYTICS_REPORT_AI_OPTION_NAME, analytics_report_ai_get_default_settings(), '', false );
	}
}

if ( ! function_exists( 'analytics_report_ai_cleanup_legacy_openai_api_key' ) ) {
	/**
	 * Remove only the retired legacy AI provider key entry from the main settings option.
	 *
	 * The value is never displayed, compared, logged, serialized into docs, or
	 * copied elsewhere. Absence of the array key is the idempotent completion
	 * condition.
	 *
	 * @return void
	 */
	function analytics_report_ai_cleanup_legacy_openai_api_key() {
		$settings = get_option( ANALYTICS_REPORT_AI_OPTION_NAME, null );

		if ( ! is_array( $settings ) || ! array_key_exists( 'openai_api_key', $settings ) ) {
			return;
		}

		unset( $settings['openai_api_key'] );

		update_option( ANALYTICS_REPORT_AI_OPTION_NAME, $settings );
	}
}

if ( ! function_exists( 'analytics_report_ai_sanitize_credential_value' ) ) {
	/**
	 * Sanitize an opaque credential while preserving non-control characters.
	 *
	 * @param string $credential Credential value.
	 * @return string
	 */
	function analytics_report_ai_sanitize_credential_value( $credential ) {
		$credential = trim( (string) $credential );

		if ( '' === $credential ) {
			return '';
		}

		$credential = wp_check_invalid_utf8( $credential );
		$credential = str_replace( array( "\r", "\n", "\t" ), '', $credential );
		$credential = preg_replace( '/[\x00-\x1F\x7F]/', '', $credential );

		if ( ! is_string( $credential ) ) {
			return '';
		}

		return $credential;
	}
}

if ( ! function_exists( 'analytics_report_ai_get_google_oauth_client_constant_names' ) ) {
	/**
	 * Get Google OAuth client constant names without reading values.
	 *
	 * @return array
	 */
	function analytics_report_ai_get_google_oauth_client_constant_names() {
		return array(
			'client_id'     => 'ANALYTICS_REPORT_AI_GOOGLE_OAUTH_CLIENT_ID',
			'client_secret' => 'ANALYTICS_REPORT_AI_GOOGLE_OAUTH_CLIENT_SECRET',
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_get_non_empty_constant_value' ) ) {
	/**
	 * Get a scalar constant value for request-local use only.
	 *
	 * @param string $constant_name Constant name.
	 * @return string
	 */
	function analytics_report_ai_get_non_empty_constant_value( $constant_name ) {
		if ( ! defined( $constant_name ) ) {
			return '';
		}

		$value = constant( $constant_name );

		if ( ! is_scalar( $value ) ) {
			return '';
		}

		return analytics_report_ai_sanitize_credential_value( (string) $value );
	}
}

if ( ! function_exists( 'analytics_report_ai_get_google_oauth_client_pair_status' ) ) {
	/**
	 * Classify a client ID / client secret pair without exposing values.
	 *
	 * @param string $client_id     OAuth client ID.
	 * @param string $client_secret OAuth client secret.
	 * @return string
	 */
	function analytics_report_ai_get_google_oauth_client_pair_status( $client_id, $client_secret ) {
		$has_client_id     = '' !== analytics_report_ai_sanitize_credential_value( $client_id );
		$has_client_secret = '' !== analytics_report_ai_sanitize_credential_value( $client_secret );

		if ( $has_client_id && $has_client_secret ) {
			return 'complete';
		}

		if ( $has_client_id || $has_client_secret ) {
			return 'incomplete';
		}

		return 'missing';
	}
}

if ( ! function_exists( 'analytics_report_ai_resolve_google_oauth_client_configuration' ) ) {
	/**
	 * Resolve the active Google OAuth client source without exposing values.
	 *
	 * Client values are returned only for immediate request-local OAuth runtime
	 * use. Admin UI, docs, logs, and support evidence must use status/category
	 * labels instead.
	 *
	 * @param array|null $settings Plugin settings.
	 * @return array
	 */
	function analytics_report_ai_resolve_google_oauth_client_configuration( $settings = null ) {
		if ( ! is_array( $settings ) ) {
			$settings = analytics_report_ai_get_settings();
		}

		$constant_names = analytics_report_ai_get_google_oauth_client_constant_names();

		$constant_client_id     = analytics_report_ai_get_non_empty_constant_value( $constant_names['client_id'] );
		$constant_client_secret = analytics_report_ai_get_non_empty_constant_value( $constant_names['client_secret'] );
		$constant_status        = analytics_report_ai_get_google_oauth_client_pair_status( $constant_client_id, $constant_client_secret );

		$settings_client = isset( $settings['google_oauth_client'] ) && is_array( $settings['google_oauth_client'] )
			? $settings['google_oauth_client']
			: array();

		$settings_client_id = isset( $settings_client['client_id'] ) && is_scalar( $settings_client['client_id'] )
			? analytics_report_ai_sanitize_credential_value( (string) $settings_client['client_id'] )
			: '';

		$settings_client_secret = isset( $settings_client['client_secret'] ) && is_scalar( $settings_client['client_secret'] )
			? analytics_report_ai_sanitize_credential_value( (string) $settings_client['client_secret'] )
			: '';

		$settings_status          = analytics_report_ai_get_google_oauth_client_pair_status( $settings_client_id, $settings_client_secret );
		$settings_fallback_status = 'complete' === $settings_status ? 'saved' : 'not_saved';

		if ( 'incomplete' === $settings_status ) {
			$settings_fallback_status = 'incomplete';
		}

		$result = array(
			'source_category'          => 'missing',
			'constants_status'         => $constant_status,
			'settings_status'          => $settings_status,
			'settings_fallback_status' => $settings_fallback_status,
			'value_hidden_status'      => 'hidden',
			'client_id'                => '',
			'client_secret'            => '',
			'can_start_oauth'          => false,
			'has_conflict'             => false,
		);

		if ( 'complete' === $constant_status ) {
			$result['source_category'] = 'constants';
			$result['client_id']       = $constant_client_id;
			$result['client_secret']   = $constant_client_secret;
			$result['can_start_oauth'] = true;
			$result['has_conflict']    = 'missing' !== $settings_status;

			return $result;
		}

		if ( 'incomplete' === $constant_status && 'complete' === $settings_status ) {
			$result['source_category'] = 'conflict';
			$result['has_conflict']    = true;

			return $result;
		}

		if ( 'missing' === $constant_status && 'complete' === $settings_status ) {
			$result['source_category'] = 'settings';
			$result['client_id']       = $settings_client_id;
			$result['client_secret']   = $settings_client_secret;
			$result['can_start_oauth'] = true;

			return $result;
		}

		if ( 'missing' === $constant_status && 'missing' === $settings_status ) {
			return $result;
		}

		$result['source_category'] = 'incomplete';

		return $result;
	}
}

if ( ! function_exists( 'analytics_report_ai_store_google_oauth_tokens' ) ) {
	/**
	 * Store Google OAuth token material in the dedicated non-autoloaded option.
	 *
	 * Token values are never returned or displayed by this helper.
	 *
	 * @param array $token_response Token response data.
	 * @return bool
	 */
	function analytics_report_ai_store_google_oauth_tokens( $token_response ) {
		if ( ! is_array( $token_response ) ) {
			return false;
		}

		$access_token = isset( $token_response['access_token'] ) && is_scalar( $token_response['access_token'] )
			? analytics_report_ai_sanitize_credential_value( (string) $token_response['access_token'] )
			: '';

		if ( '' === $access_token ) {
			return false;
		}

		$refresh_token = isset( $token_response['refresh_token'] ) && is_scalar( $token_response['refresh_token'] )
			? analytics_report_ai_sanitize_credential_value( (string) $token_response['refresh_token'] )
			: '';

		$expires_in = isset( $token_response['expires_in'] ) && is_numeric( $token_response['expires_in'] )
			? absint( $token_response['expires_in'] )
			: 0;

		$stored_tokens = array(
			'access_token'     => $access_token,
			'expires_at'       => $expires_in > 0 ? time() + $expires_in : 0,
			'connection_state' => 'connected',
			'created_at'       => time(),
			'updated_at'       => time(),
		);

		if ( '' !== $refresh_token ) {
			$stored_tokens['refresh_token'] = $refresh_token;
		}

		if ( false === get_option( ANALYTICS_REPORT_AI_GOOGLE_OAUTH_TOKEN_OPTION_NAME, false ) ) {
			return add_option( ANALYTICS_REPORT_AI_GOOGLE_OAUTH_TOKEN_OPTION_NAME, $stored_tokens, '', false );
		}

		$updated = update_option( ANALYTICS_REPORT_AI_GOOGLE_OAUTH_TOKEN_OPTION_NAME, $stored_tokens, false );

		if ( $updated ) {
			return true;
		}

		$current_tokens = get_option( ANALYTICS_REPORT_AI_GOOGLE_OAUTH_TOKEN_OPTION_NAME, array() );

		return is_array( $current_tokens ) && $current_tokens === $stored_tokens;
	}
}

if ( ! function_exists( 'analytics_report_ai_google_oauth_token_storage_exists' ) ) {
	/**
	 * Check whether the dedicated OAuth token option exists without exposing values.
	 *
	 * @return bool
	 */
	function analytics_report_ai_google_oauth_token_storage_exists() {
		return false !== get_option( ANALYTICS_REPORT_AI_GOOGLE_OAUTH_TOKEN_OPTION_NAME, false );
	}
}

if ( ! function_exists( 'analytics_report_ai_get_google_oauth_token_lifecycle_categories' ) ) {
	/**
	 * Get safe OAuth token lifecycle categories without exposing token values.
	 *
	 * This helper does not refresh, revoke, or call any external endpoint.
	 *
	 * @param array|false|null $tokens Optional token option data for request-local classification.
	 * @return array
	 */
	function analytics_report_ai_get_google_oauth_token_lifecycle_categories( $tokens = null ) {
		if ( null === $tokens ) {
			$tokens = get_option( ANALYTICS_REPORT_AI_GOOGLE_OAUTH_TOKEN_OPTION_NAME, false );
		}

		$has_oauth_store = false !== $tokens;
		$result          = array(
			'oauth_connection_status_category'    => 'not_connected',
			'token_lifecycle_status_category'     => 'reconnect_required',
			'token_refresh_status_category'       => 'unavailable',
			'token_disconnect_status_category'    => 'not_requested',
			'token_revoke_status_category'        => 'deferred',
			'oauth_token_storage_status_category' => $has_oauth_store ? 'stored' : 'not_stored',
		);

		if ( ! is_array( $tokens ) || empty( $tokens['access_token'] ) || ! is_scalar( $tokens['access_token'] ) ) {
			return $result;
		}

		$access_token = analytics_report_ai_sanitize_credential_value( (string) $tokens['access_token'] );

		if ( '' === $access_token ) {
			return $result;
		}

		$expires_at        = isset( $tokens['expires_at'] ) && is_numeric( $tokens['expires_at'] ) ? absint( $tokens['expires_at'] ) : 0;
		$has_refresh_token = isset( $tokens['refresh_token'] ) && is_scalar( $tokens['refresh_token'] ) && '' !== analytics_report_ai_sanitize_credential_value( (string) $tokens['refresh_token'] );

		unset( $access_token );

		if ( $expires_at > 0 && $expires_at <= time() ) {
			$result['oauth_connection_status_category'] = $has_refresh_token ? 'token_expired_or_refresh_needed' : 'reconnect_required';
			$result['token_lifecycle_status_category']  = $has_refresh_token ? 'expired' : 'refresh_unavailable';
			$result['token_refresh_status_category']    = $has_refresh_token ? 'deferred' : 'unavailable';

			return $result;
		}

		$result['oauth_connection_status_category'] = 'connected';
		$result['token_lifecycle_status_category']  = 'usable';
		$result['token_refresh_status_category']    = 'not_attempted';

		return $result;
	}
}

if ( ! function_exists( 'analytics_report_ai_delete_google_oauth_tokens' ) ) {
	/**
	 * Delete local Google OAuth token data without contacting Google.
	 *
	 * This helper deletes only the dedicated local OAuth token option. It does
	 * not revoke provider-side access, delete saved OAuth client settings,
	 * values, or change AI provider configuration.
	 *
	 * @return bool
	 */
	function analytics_report_ai_delete_google_oauth_tokens() {
		if ( ! analytics_report_ai_google_oauth_token_storage_exists() ) {
			return true;
		}

		return delete_option( ANALYTICS_REPORT_AI_GOOGLE_OAUTH_TOKEN_OPTION_NAME );
	}
}

if ( ! function_exists( 'analytics_report_ai_get_google_oauth_connection_state' ) ) {
	/**
	 * Get Google OAuth connection state without exposing token values.
	 *
	 * @return string
	 */
	function analytics_report_ai_get_google_oauth_connection_state() {
		$categories = analytics_report_ai_get_google_oauth_token_lifecycle_categories();

		return isset( $categories['oauth_connection_status_category'] ) ? $categories['oauth_connection_status_category'] : 'not_connected';
	}
}

if ( ! function_exists( 'analytics_report_ai_resolve_google_ga4_credential_source' ) ) {
	/**
	 * Resolve the request-local credential source for GA4 requests.
	 *
	 * Token values are returned only for immediate runtime use by GA4 client
	 * calls. Manual Settings token entry is not used as a normal GA4
	 * credential source. Admin UI, docs, logs, and support evidence should use
	 * the status labels instead.
	 *
	 * @param array|null $settings Plugin settings.
	 * @return array
	 */
	function analytics_report_ai_resolve_google_ga4_credential_source( $settings = null ) {
		if ( ! is_array( $settings ) ) {
			$settings = analytics_report_ai_get_settings();
		}

		$tokens               = get_option( ANALYTICS_REPORT_AI_GOOGLE_OAUTH_TOKEN_OPTION_NAME, false );
		$has_oauth_store     = false !== $tokens;
		$lifecycle_categories = analytics_report_ai_get_google_oauth_token_lifecycle_categories( $tokens );
		$lifecycle_categories['connection_state'] = isset( $lifecycle_categories['oauth_connection_status_category'] )
			? $lifecycle_categories['oauth_connection_status_category']
			: 'not_connected';

		$result = array(
			'status'        => 'credential_source_missing',
			'access_token'  => '',
			'fallback_used' => false,
		);
		$result = array_merge( $result, $lifecycle_categories );

		if ( is_array( $tokens ) && isset( $tokens['access_token'] ) && is_scalar( $tokens['access_token'] ) ) {
			$oauth_access_token = analytics_report_ai_sanitize_credential_value( (string) $tokens['access_token'] );

			if ( '' !== $oauth_access_token ) {
				if ( 'connected' !== $lifecycle_categories['oauth_connection_status_category'] ) {
					return array_merge(
						$lifecycle_categories,
						array(
							'status'        => 'credential_source_oauth_refresh_needed',
							'access_token'  => '',
							'fallback_used' => false,
						)
					);
				}

				return array_merge(
					$lifecycle_categories,
					array(
						'status'        => 'credential_source_oauth_connected',
						'access_token'  => $oauth_access_token,
						'fallback_used' => false,
					)
				);
			}
		}

		if ( $has_oauth_store ) {
			return array_merge(
				$lifecycle_categories,
				array(
					'status'        => 'credential_source_oauth_error_category',
					'access_token'  => '',
					'fallback_used' => false,
				)
			);
		}

		return $result;
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

if ( ! function_exists( 'analytics_report_ai_get_default_report_period' ) ) {
	/**
	 * Get default report period.
	 *
	 * Default period is the first day to the last day of the previous month
	 * based on the WordPress timezone.
	 *
	 * @return array
	 */
	function analytics_report_ai_get_default_report_period() {
		$timezone = wp_timezone();
		$now      = new DateTimeImmutable( 'now', $timezone );

		$first_day_this_month = $now->modify( 'first day of this month' )->setTime( 0, 0, 0 );
		$start_date           = $first_day_this_month->modify( '-1 month' )->format( 'Y-m-01' );
		$end_date             = $first_day_this_month->modify( '-1 day' )->format( 'Y-m-d' );

		return array(
			'start_date' => $start_date,
			'end_date'   => $end_date,
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_get_comparison_options' ) ) {
	/**
	 * Get comparison options.
	 *
	 * @return array
	 */
	function analytics_report_ai_get_comparison_options() {
		return array(
			'none'           => __( 'No comparison', 'studio317-report-drafts-google-analytics' ),
			'previous_month' => __( 'Previous month', 'studio317-report-drafts-google-analytics' ),
			'previous_year'  => __( 'Previous year', 'studio317-report-drafts-google-analytics' ),
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_get_scope_options' ) ) {
	/**
	 * Get data scope options.
	 *
	 * @return array
	 */
	function analytics_report_ai_get_scope_options() {
		return array(
			'site'      => __( 'Entire site', 'studio317-report-drafts-google-analytics' ),
			'directory' => __( 'Directory', 'studio317-report-drafts-google-analytics' ),
			'page'      => __( 'Page', 'studio317-report-drafts-google-analytics' ),
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_is_valid_date_string' ) ) {
	/**
	 * Check whether the given value is a valid Y-m-d date.
	 *
	 * @param string $date Date string.
	 * @return bool
	 */
	function analytics_report_ai_is_valid_date_string( $date ) {
		$date = trim( (string) $date );

		if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
			return false;
		}

		$parts = explode( '-', $date );

		return checkdate(
			(int) $parts[1],
			(int) $parts[2],
			(int) $parts[0]
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_get_max_report_days' ) ) {
	/**
	 * Get the maximum inclusive report period length.
	 *
	 * @return int
	 */
	function analytics_report_ai_get_max_report_days() {
		if ( defined( 'ANALYTICS_REPORT_AI_MAX_REPORT_DAYS' ) ) {
			return max( 1, absint( ANALYTICS_REPORT_AI_MAX_REPORT_DAYS ) );
		}

		return 31;
	}
}

if ( ! function_exists( 'analytics_report_ai_get_report_period_day_count' ) ) {
	/**
	 * Get inclusive day count for a valid report period.
	 *
	 * @param string $start_date Start date.
	 * @param string $end_date   End date.
	 * @return int
	 */
	function analytics_report_ai_get_report_period_day_count( $start_date, $end_date ) {
		if (
			! analytics_report_ai_is_valid_date_string( $start_date )
			|| ! analytics_report_ai_is_valid_date_string( $end_date )
		) {
			return 0;
		}

		if ( $start_date > $end_date ) {
			return 0;
		}

		$start = DateTimeImmutable::createFromFormat( '!Y-m-d', $start_date );
		$end   = DateTimeImmutable::createFromFormat( '!Y-m-d', $end_date );

		if ( ! $start || ! $end ) {
			return 0;
		}

		return (int) $start->diff( $end )->days + 1;
	}
}

if ( ! function_exists( 'analytics_report_ai_shift_date_with_clamp' ) ) {
	/**
	 * Shift date to previous month or previous year with day clamping.
	 *
	 * Examples:
	 * - 2026-05-31 previous_month => 2026-04-30
	 * - 2024-02-29 previous_year  => 2023-02-28
	 *
	 * @param string $date Date string.
	 * @param string $comparison Comparison type.
	 * @return string
	 */
	function analytics_report_ai_shift_date_with_clamp( $date, $comparison ) {
		$parts = explode( '-', $date );

		$year  = (int) $parts[0];
		$month = (int) $parts[1];
		$day   = (int) $parts[2];

		if ( 'previous_month' === $comparison ) {
			--$month;

			if ( $month < 1 ) {
				$month = 12;
				--$year;
			}
		} elseif ( 'previous_year' === $comparison ) {
			--$year;
		}

		$first_day = DateTimeImmutable::createFromFormat(
			'!Y-m-d',
			sprintf( '%04d-%02d-01', $year, $month )
		);

		if ( ! $first_day ) {
			return $date;
		}

		$last_day = (int) $first_day->modify( 'last day of this month' )->format( 'd' );
		$day      = min( $day, $last_day );

		return sprintf( '%04d-%02d-%02d', $year, $month, $day );
	}
}

if ( ! function_exists( 'analytics_report_ai_calculate_comparison_period' ) ) {
	/**
	 * Calculate comparison period.
	 *
	 * @param string $start_date Start date.
	 * @param string $end_date End date.
	 * @param string $comparison Comparison type.
	 * @return array|null
	 */
	function analytics_report_ai_calculate_comparison_period( $start_date, $end_date, $comparison ) {
		if ( 'none' === $comparison ) {
			return null;
		}

		return array(
			'start_date' => analytics_report_ai_shift_date_with_clamp( $start_date, $comparison ),
			'end_date'   => analytics_report_ai_shift_date_with_clamp( $end_date, $comparison ),
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_normalize_report_path' ) ) {
	/**
	 * Normalize report path by scope.
	 *
	 * @param string $path Raw path.
	 * @param string $scope Scope.
	 * @return string|WP_Error
	 */
	function analytics_report_ai_normalize_report_path( $path, $scope ) {
		$path = trim( (string) $path );

		if ( 'site' === $scope ) {
			return '';
		}

		if ( '' === $path ) {
			return new WP_Error(
				'analytics_report_ai_empty_path',
				__( 'Path is required when Directory or Page is selected.', 'studio317-report-drafts-google-analytics' )
			);
		}

		if ( preg_match( '#^[a-z][a-z0-9+\-.]*://#i', $path ) ) {
			return new WP_Error(
				'analytics_report_ai_full_url_not_allowed',
				__( 'Full URLs are not allowed. Enter only the path, such as /blog/ or /about.', 'studio317-report-drafts-google-analytics' )
			);
		}

		$path = preg_replace( '/[?#].*$/', '', $path );
		$path = trim( $path );

		if ( '' === $path ) {
			return new WP_Error(
				'analytics_report_ai_empty_path_after_normalization',
				__( 'Path is empty after removing query strings and fragments.', 'studio317-report-drafts-google-analytics' )
			);
		}

		if ( '/' !== substr( $path, 0, 1 ) ) {
			$path = '/' . $path;
		}

		$path = preg_replace( '#/+#', '/', $path );

		if ( 'directory' === $scope && '/' !== substr( $path, -1 ) ) {
			$path .= '/';
		}

		return sanitize_text_field( $path );
	}
}

if ( ! function_exists( 'analytics_report_ai_get_payload_transient_key' ) ) {
	/**
	 * Get transient key for AI payload preview.
	 *
	 * @param int $user_id User ID.
	 * @return string
	 */
	function analytics_report_ai_get_payload_transient_key( $user_id = 0 ) {
		$user_id = absint( $user_id );

		if ( 0 === $user_id ) {
			$user_id = get_current_user_id();
		}

		return 'analytics_report_ai_payload_' . $user_id;
	}
}

if ( ! function_exists( 'analytics_report_ai_get_payload_transient_expiration' ) ) {
	/**
	 * Get transient expiration seconds for AI payload preview.
	 *
	 * @return int
	 */
	function analytics_report_ai_get_payload_transient_expiration() {
		return 30 * MINUTE_IN_SECONDS;
	}
}

if ( ! function_exists( 'analytics_report_ai_get_report_language_profiles' ) ) {
	/**
	 * Get safe language labels for report output prompts.
	 *
	 * @return array
	 */
	function analytics_report_ai_get_report_language_profiles() {
		return array(
			'ar'    => 'Arabic',
			'de'    => 'German',
			'en'    => 'English',
			'en_GB' => 'English (United Kingdom)',
			'en_US' => 'English (United States)',
			'es'    => 'Spanish',
			'fr'    => 'French',
			'id'    => 'Indonesian',
			'it'    => 'Italian',
			'ja'    => 'Japanese',
			'ko'    => 'Korean',
			'nl'    => 'Dutch',
			'pl'    => 'Polish',
			'pt'    => 'Portuguese',
			'pt_BR' => 'Portuguese (Brazil)',
			'pt_PT' => 'Portuguese (Portugal)',
			'ru'    => 'Russian',
			'th'    => 'Thai',
			'tr'    => 'Turkish',
			'uk'    => 'Ukrainian',
			'vi'    => 'Vietnamese',
			'zh'    => 'Chinese',
			'zh_CN' => 'Simplified Chinese',
			'zh_HK' => 'Traditional Chinese',
			'zh_MO' => 'Traditional Chinese',
			'zh_TW' => 'Traditional Chinese',
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_normalize_wordpress_locale' ) ) {
	/**
	 * Normalize a WordPress locale to a safe underscore-separated form.
	 *
	 * @param mixed $locale Locale value.
	 * @return string
	 */
	function analytics_report_ai_normalize_wordpress_locale( $locale ) {
		if ( ! is_scalar( $locale ) ) {
			return '';
		}

		$locale = trim( (string) $locale );

		if ( '' === $locale ) {
			return '';
		}

		$locale = wp_check_invalid_utf8( $locale );
		$locale = str_replace( '-', '_', $locale );

		if ( ! is_string( $locale ) || ! preg_match( '/^[A-Za-z]{2,3}(?:_[A-Za-z0-9]{2,8}){0,2}$/', $locale ) ) {
			return '';
		}

		$parts = explode( '_', $locale );

		if ( empty( $parts[0] ) ) {
			return '';
		}

		$normalized = array( strtolower( $parts[0] ) );

		foreach ( array_slice( $parts, 1 ) as $part ) {
			$normalized[] = strtoupper( $part );
		}

		return implode( '_', $normalized );
	}
}

if ( ! function_exists( 'analytics_report_ai_get_report_language_profile_from_locale' ) ) {
	/**
	 * Build a safe report language profile from a WordPress locale.
	 *
	 * @param mixed  $locale WordPress locale.
	 * @param string $source Source category.
	 * @return array
	 */
	function analytics_report_ai_get_report_language_profile_from_locale( $locale, $source ) {
		$allowed_sources = array( 'user_locale', 'site_locale', 'default_locale' );
		$source          = in_array( $source, $allowed_sources, true ) ? $source : 'default_locale';
		$locale          = analytics_report_ai_normalize_wordpress_locale( $locale );

		if ( '' === $locale ) {
			$locale = 'en_US';
			$source = 'default_locale';
		}

		$parts         = explode( '_', $locale );
		$language_code = isset( $parts[0] ) ? strtolower( $parts[0] ) : 'en';
		$profiles      = analytics_report_ai_get_report_language_profiles();

		if ( isset( $profiles[ $locale ] ) ) {
			$language_name = $profiles[ $locale ];
		} elseif ( isset( $profiles[ $language_code ] ) ) {
			$language_name = $profiles[ $language_code ];
		} else {
			$language_name = sprintf( 'Language code %s', $language_code );
		}

		return array(
			'wordpress_locale' => $locale,
			'output_locale'    => str_replace( '_', '-', $locale ),
			'language_code'    => $language_code,
			'language_name'    => $language_name,
			'source'           => $source,
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_get_report_language_profile' ) ) {
	/**
	 * Resolve the report output language profile for the current request.
	 *
	 * @param int $user_id User ID.
	 * @return array
	 */
	function analytics_report_ai_get_report_language_profile( $user_id = 0 ) {
		$user_id = absint( $user_id );

		if ( 0 === $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( $user_id > 0 ) {
			$user_locale = analytics_report_ai_normalize_wordpress_locale( get_user_locale( $user_id ) );

			if ( '' !== $user_locale ) {
				return analytics_report_ai_get_report_language_profile_from_locale( $user_locale, 'user_locale' );
			}
		}

		$site_locale = analytics_report_ai_normalize_wordpress_locale( get_locale() );

		if ( '' !== $site_locale ) {
			return analytics_report_ai_get_report_language_profile_from_locale( $site_locale, 'site_locale' );
		}

		return analytics_report_ai_get_report_language_profile_from_locale( 'en_US', 'default_locale' );
	}
}

if ( ! function_exists( 'analytics_report_ai_validate_report_language_profile' ) ) {
	/**
	 * Validate a report language profile stored in report data.
	 *
	 * @param mixed $profile Language profile.
	 * @return true|WP_Error
	 */
	function analytics_report_ai_validate_report_language_profile( $profile ) {
		if ( ! is_array( $profile ) ) {
			return new WP_Error(
				'analytics_report_ai_report_language_not_array',
				__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
			);
		}

		$required_keys = array(
			'wordpress_locale',
			'output_locale',
			'language_code',
			'language_name',
			'source',
		);

		foreach ( $required_keys as $key ) {
			if ( ! isset( $profile[ $key ] ) || ! is_scalar( $profile[ $key ] ) || '' === trim( (string) $profile[ $key ] ) ) {
				return new WP_Error(
					'analytics_report_ai_report_language_missing_' . sanitize_key( $key ),
					__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
				);
			}
		}

		$normalized_locale = analytics_report_ai_normalize_wordpress_locale( $profile['wordpress_locale'] );

		if ( '' === $normalized_locale ) {
			return new WP_Error(
				'analytics_report_ai_report_language_invalid_wordpress_locale',
				__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
			);
		}

		if ( ! preg_match( '/^[a-z]{2,3}(?:-[A-Z0-9]{2,8}){0,2}$/', (string) $profile['output_locale'] ) ) {
			return new WP_Error(
				'analytics_report_ai_report_language_invalid_output_locale',
				__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
			);
		}

		if ( ! preg_match( '/^[a-z]{2,3}$/', (string) $profile['language_code'] ) ) {
			return new WP_Error(
				'analytics_report_ai_report_language_invalid_code',
				__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
			);
		}

		if ( ! preg_match( '/^[A-Za-z][A-Za-z0-9 ()+\-]{1,80}$/', (string) $profile['language_name'] ) ) {
			return new WP_Error(
				'analytics_report_ai_report_language_invalid_name',
				__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
			);
		}

		if ( ! in_array( (string) $profile['source'], array( 'user_locale', 'site_locale', 'default_locale' ), true ) ) {
			return new WP_Error(
				'analytics_report_ai_report_language_invalid_source',
				__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
			);
		}

		$expected_profile = analytics_report_ai_get_report_language_profile_from_locale(
			$normalized_locale,
			(string) $profile['source']
		);

		foreach ( array( 'wordpress_locale', 'output_locale', 'language_code', 'language_name', 'source' ) as $key ) {
			if ( (string) $profile[ $key ] !== (string) $expected_profile[ $key ] ) {
				return new WP_Error(
					'analytics_report_ai_report_language_mismatch_' . sanitize_key( $key ),
					__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
				);
			}
		}

		return true;
	}
}

if ( ! function_exists( 'analytics_report_ai_get_report_language_display_name' ) ) {
	/**
	 * Get a translatable display name for a report language profile.
	 *
	 * @param array $profile Report language profile.
	 * @return string
	 */
	function analytics_report_ai_get_report_language_display_name( $profile ) {
		$locale = isset( $profile['wordpress_locale'] ) && is_scalar( $profile['wordpress_locale'] )
			? analytics_report_ai_normalize_wordpress_locale( (string) $profile['wordpress_locale'] )
			: '';
		$code   = isset( $profile['language_code'] ) && is_scalar( $profile['language_code'] )
			? strtolower( (string) $profile['language_code'] )
			: '';

		$labels = array(
			'ar'    => __( 'Arabic', 'studio317-report-drafts-google-analytics' ),
			'de'    => __( 'German', 'studio317-report-drafts-google-analytics' ),
			'en'    => __( 'English', 'studio317-report-drafts-google-analytics' ),
			'en_GB' => __( 'English (United Kingdom)', 'studio317-report-drafts-google-analytics' ),
			'en_US' => __( 'English (United States)', 'studio317-report-drafts-google-analytics' ),
			'es'    => __( 'Spanish', 'studio317-report-drafts-google-analytics' ),
			'fr'    => __( 'French', 'studio317-report-drafts-google-analytics' ),
			'id'    => __( 'Indonesian', 'studio317-report-drafts-google-analytics' ),
			'it'    => __( 'Italian', 'studio317-report-drafts-google-analytics' ),
			'ja'    => __( 'Japanese', 'studio317-report-drafts-google-analytics' ),
			'ko'    => __( 'Korean', 'studio317-report-drafts-google-analytics' ),
			'nl'    => __( 'Dutch', 'studio317-report-drafts-google-analytics' ),
			'pl'    => __( 'Polish', 'studio317-report-drafts-google-analytics' ),
			'pt'    => __( 'Portuguese', 'studio317-report-drafts-google-analytics' ),
			'pt_BR' => __( 'Portuguese (Brazil)', 'studio317-report-drafts-google-analytics' ),
			'pt_PT' => __( 'Portuguese (Portugal)', 'studio317-report-drafts-google-analytics' ),
			'ru'    => __( 'Russian', 'studio317-report-drafts-google-analytics' ),
			'th'    => __( 'Thai', 'studio317-report-drafts-google-analytics' ),
			'tr'    => __( 'Turkish', 'studio317-report-drafts-google-analytics' ),
			'uk'    => __( 'Ukrainian', 'studio317-report-drafts-google-analytics' ),
			'vi'    => __( 'Vietnamese', 'studio317-report-drafts-google-analytics' ),
			'zh'    => __( 'Chinese', 'studio317-report-drafts-google-analytics' ),
			'zh_CN' => __( 'Simplified Chinese', 'studio317-report-drafts-google-analytics' ),
			'zh_HK' => __( 'Traditional Chinese', 'studio317-report-drafts-google-analytics' ),
			'zh_MO' => __( 'Traditional Chinese', 'studio317-report-drafts-google-analytics' ),
			'zh_TW' => __( 'Traditional Chinese', 'studio317-report-drafts-google-analytics' ),
		);

		if ( isset( $labels[ $locale ] ) ) {
			return $labels[ $locale ];
		}

		if ( isset( $labels[ $code ] ) ) {
			return $labels[ $code ];
		}

		if ( isset( $profile['language_name'] ) && is_scalar( $profile['language_name'] ) ) {
			return sanitize_text_field( (string) $profile['language_name'] );
		}

		return __( 'English (United States)', 'studio317-report-drafts-google-analytics' );
	}
}

if ( ! function_exists( 'analytics_report_ai_get_payload_version' ) ) {
	/**
	 * Get expected AI payload version.
	 *
	 * @return string
	 */
	function analytics_report_ai_get_payload_version() {
		if ( defined( 'ANALYTICS_REPORT_AI_PAYLOAD_VERSION' ) ) {
			return (string) ANALYTICS_REPORT_AI_PAYLOAD_VERSION;
		}

		return '0.1.0-mvp';
	}
}

if ( ! function_exists( 'analytics_report_ai_get_payload_row_limits' ) ) {
	/**
	 * Get expected AI payload row limits.
	 *
	 * @return array
	 */
	function analytics_report_ai_get_payload_row_limits() {
		return array(
			'daily_trend'      => 31,
			'top_pages'        => 10,
			'traffic_channels' => 10,
			'traffic_sources'  => 10,
			'regional_trends'  => 10,
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_validate_ai_payload' ) ) {
	/**
	 * Validate the AI payload shape before storage or AI generation.
	 *
	 * @param mixed $payload AI payload.
	 * @return true|WP_Error
	 */
	function analytics_report_ai_validate_ai_payload( $payload ) {
		if ( ! is_array( $payload ) ) {
			return new WP_Error(
				'analytics_report_ai_payload_not_array',
				__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
			);
		}

		$expected_values = array(
			'payload_version' => analytics_report_ai_get_payload_version(),
			'report_type'     => 'ga4_summary',
		);

		foreach ( $expected_values as $key => $expected_value ) {
			if (
				! isset( $payload[ $key ] )
				|| ! is_scalar( $payload[ $key ] )
				|| (string) $payload[ $key ] !== $expected_value
			) {
				return new WP_Error(
					'analytics_report_ai_payload_unexpected_' . sanitize_key( $key ),
					__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
				);
			}
		}

		if ( empty( $payload['report_language'] ) || ! is_array( $payload['report_language'] ) ) {
			return new WP_Error(
				'analytics_report_ai_payload_missing_report_language',
				__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
			);
		}

		$report_language_validation = analytics_report_ai_validate_report_language_profile( $payload['report_language'] );

		if ( is_wp_error( $report_language_validation ) ) {
			return $report_language_validation;
		}

		if (
			! isset( $payload['language'] )
			|| ! is_scalar( $payload['language'] )
			|| (string) $payload['language'] !== (string) $payload['report_language']['language_code']
		) {
			return new WP_Error(
				'analytics_report_ai_payload_invalid_language_code',
				__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
			);
		}

		$required_array_keys = array(
			'payload_status',
			'data_availability',
			'value_semantics',
			'site',
			'conditions',
			'summary',
			'daily_trend',
			'top_pages',
			'traffic_channels',
			'traffic_sources',
			'regional_trends',
		);

		foreach ( $required_array_keys as $key ) {
			if ( ! isset( $payload[ $key ] ) || ! is_array( $payload[ $key ] ) ) {
				return new WP_Error(
					'analytics_report_ai_payload_missing_' . sanitize_key( $key ),
					__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
				);
			}
		}

		if (
			empty( $payload['payload_status']['overall_status'] )
			|| ! is_scalar( $payload['payload_status']['overall_status'] )
			|| ! isset( $payload['payload_status']['generation_allowed'] )
			|| ! is_bool( $payload['payload_status']['generation_allowed'] )
			|| ! isset( $payload['payload_status']['warnings'] )
			|| ! is_array( $payload['payload_status']['warnings'] )
		) {
			return new WP_Error(
				'analytics_report_ai_payload_invalid_status',
				__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
			);
		}

		if (
			isset( $payload['payload_status']['generation_block_reason'] )
			&& ! is_scalar( $payload['payload_status']['generation_block_reason'] )
			&& null !== $payload['payload_status']['generation_block_reason']
		) {
			return new WP_Error(
				'analytics_report_ai_payload_invalid_block_reason',
				__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
			);
		}

		foreach ( $payload['payload_status']['warnings'] as $warning ) {
			if (
				! is_array( $warning )
				|| empty( $warning['code'] )
				|| empty( $warning['category'] )
				|| empty( $warning['severity'] )
				|| ! is_scalar( $warning['code'] )
				|| ! is_scalar( $warning['category'] )
				|| ! is_scalar( $warning['severity'] )
			) {
				return new WP_Error(
					'analytics_report_ai_payload_invalid_warning',
					__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
				);
			}
		}

		if (
			empty( $payload['data_availability']['current_period'] )
			|| ! is_array( $payload['data_availability']['current_period'] )
			|| empty( $payload['data_availability']['comparison_period'] )
			|| ! is_array( $payload['data_availability']['comparison_period'] )
			|| empty( $payload['data_availability']['summary'] )
			|| ! is_array( $payload['data_availability']['summary'] )
			|| ! isset( $payload['data_availability']['detail_presets'] )
			|| ! is_array( $payload['data_availability']['detail_presets'] )
		) {
			return new WP_Error(
				'analytics_report_ai_payload_invalid_availability',
				__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
			);
		}

		foreach ( analytics_report_ai_get_payload_row_limits() as $key => $limit ) {
			if (
				! isset( $payload['data_availability']['detail_presets'][ $key ] )
				|| ! is_array( $payload['data_availability']['detail_presets'][ $key ] )
				|| empty( $payload['data_availability']['detail_presets'][ $key ]['status'] )
				|| ! is_scalar( $payload['data_availability']['detail_presets'][ $key ]['status'] )
			) {
				return new WP_Error(
					'analytics_report_ai_payload_invalid_detail_availability_' . sanitize_key( $key ),
					__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
				);
			}
		}

		if (
			! isset( $payload['value_semantics']['zero_values_are_real'] )
			|| ! is_bool( $payload['value_semantics']['zero_values_are_real'] )
			|| ! isset( $payload['value_semantics']['missing_values_are_unavailable'] )
			|| ! is_bool( $payload['value_semantics']['missing_values_are_unavailable'] )
		) {
			return new WP_Error(
				'analytics_report_ai_payload_invalid_value_semantics',
				__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
			);
		}

		foreach ( analytics_report_ai_get_payload_row_limits() as $key => $limit ) {
			if ( count( $payload[ $key ] ) > absint( $limit ) ) {
				return new WP_Error(
					'analytics_report_ai_payload_row_limit_' . sanitize_key( $key ),
					__( 'Data for AI generation is invalid.', 'studio317-report-drafts-google-analytics' )
				);
			}
		}

		return true;
	}
}

if ( ! function_exists( 'analytics_report_ai_payload_allows_generation' ) ) {
	/**
	 * Check whether a validated payload permits AI generation.
	 *
	 * @param array $payload AI payload.
	 * @return bool
	 */
	function analytics_report_ai_payload_allows_generation( $payload ) {
		return (
			is_array( $payload )
			&& isset( $payload['payload_status']['generation_allowed'] )
			&& true === $payload['payload_status']['generation_allowed']
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_get_summary_metric_definitions' ) ) {
	/**
	 * Get summary metric definitions.
	 *
	 * @return array
	 */
	function analytics_report_ai_get_summary_metric_definitions() {
		return array(
			'screenPageViews'        => array(
				'label' => '表示回数',
				'unit'  => 'count',
				'type'  => 'integer',
			),
			'activeUsers'            => array(
				'label' => 'アクティブユーザー数',
				'unit'  => 'count',
				'type'  => 'integer',
			),
			'newUsers'               => array(
				'label' => '新規ユーザー数',
				'unit'  => 'count',
				'type'  => 'integer',
			),
			'sessions'               => array(
				'label' => 'セッション数',
				'unit'  => 'count',
				'type'  => 'integer',
			),
			'engagedSessions'        => array(
				'label' => 'エンゲージメントのあったセッション数',
				'unit'  => 'count',
				'type'  => 'integer',
			),
			'engagementRate'         => array(
				'label' => 'エンゲージメント率',
				'unit'  => 'ratio',
				'type'  => 'float',
			),
			'bounceRate'             => array(
				'label' => '直帰率',
				'unit'  => 'ratio',
				'type'  => 'float',
			),
			'averageSessionDuration' => array(
				'label' => '平均セッション時間',
				'unit'  => 'seconds',
				'type'  => 'float',
			),
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_cast_metric_value' ) ) {
	/**
	 * Cast metric value.
	 *
	 * @param string $metric_name Metric name.
	 * @param mixed  $value Raw value.
	 * @return int|float
	 */
	function analytics_report_ai_cast_metric_value( $metric_name, $value ) {
		$definitions = analytics_report_ai_get_summary_metric_definitions();
		$type        = isset( $definitions[ $metric_name ]['type'] ) ? $definitions[ $metric_name ]['type'] : 'float';

		if ( 'integer' === $type ) {
			return (int) $value;
		}

		return (float) $value;
	}
}
