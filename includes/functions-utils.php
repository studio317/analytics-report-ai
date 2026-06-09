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

		$settings = wp_parse_args( $saved, $defaults );

		if ( ! isset( $settings['google_tokens'] ) || ! is_array( $settings['google_tokens'] ) ) {
			$settings['google_tokens'] = array();
		}

		$settings['openai_api_key'] = isset( $settings['openai_api_key'] ) && is_scalar( $settings['openai_api_key'] )
			? (string) $settings['openai_api_key']
			: '';

		if ( isset( $settings['google_tokens']['access_token'] ) && is_scalar( $settings['google_tokens']['access_token'] ) ) {
			$settings['google_tokens']['access_token'] = (string) $settings['google_tokens']['access_token'];
		} else {
			unset( $settings['google_tokens']['access_token'] );
		}

		$settings['google_auth_status'] = ! empty( $settings['google_tokens']['access_token'] ) ? 'connected' : 'not_connected';

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
			'none'           => __( 'No comparison', 'analytics-report-ai' ),
			'previous_month' => __( 'Previous month', 'analytics-report-ai' ),
			'previous_year'  => __( 'Previous year', 'analytics-report-ai' ),
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
			'site'      => __( 'Entire site', 'analytics-report-ai' ),
			'directory' => __( 'Directory', 'analytics-report-ai' ),
			'page'      => __( 'Page', 'analytics-report-ai' ),
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
	 * Get the maximum inclusive report period length for the MVP.
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
				__( 'Path is required when Directory or Page is selected.', 'analytics-report-ai' )
			);
		}

		if ( preg_match( '#^[a-z][a-z0-9+\-.]*://#i', $path ) ) {
			return new WP_Error(
				'analytics_report_ai_full_url_not_allowed',
				__( 'Full URLs are not allowed. Enter only the path, such as /blog/ or /about.', 'analytics-report-ai' )
			);
		}

		$path = preg_replace( '/[?#].*$/', '', $path );
		$path = trim( $path );

		if ( '' === $path ) {
			return new WP_Error(
				'analytics_report_ai_empty_path_after_normalization',
				__( 'Path is empty after removing query strings and fragments.', 'analytics-report-ai' )
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

if ( ! function_exists( 'analytics_report_ai_get_payload_version' ) ) {
	/**
	 * Get expected MVP AI payload version.
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
	 * Get expected MVP AI payload row limits.
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
	 * Validate the MVP AI payload shape before storage or OpenAI submission.
	 *
	 * @param mixed $payload AI payload.
	 * @return true|WP_Error
	 */
	function analytics_report_ai_validate_ai_payload( $payload ) {
		if ( ! is_array( $payload ) ) {
			return new WP_Error(
				'analytics_report_ai_payload_not_array',
				__( 'AI payload is invalid.', 'analytics-report-ai' )
			);
		}

		$expected_values = array(
			'payload_version' => analytics_report_ai_get_payload_version(),
			'language'        => 'ja',
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
					__( 'AI payload is invalid.', 'analytics-report-ai' )
				);
			}
		}

		$required_array_keys = array(
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
					__( 'AI payload is invalid.', 'analytics-report-ai' )
				);
			}
		}

		foreach ( analytics_report_ai_get_payload_row_limits() as $key => $limit ) {
			if ( count( $payload[ $key ] ) > absint( $limit ) ) {
				return new WP_Error(
					'analytics_report_ai_payload_row_limit_' . sanitize_key( $key ),
					__( 'AI payload is invalid.', 'analytics-report-ai' )
				);
			}
		}

		return true;
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
