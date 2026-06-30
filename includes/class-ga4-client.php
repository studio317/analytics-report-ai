<?php
/**
 * GA4 Data API client.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles GA4 Data API requests for Analytics Report AI reports.
 *
 * @since 0.1.0
 */
final class Analytics_Report_AI_GA4_Client {

	/**
	 * Run GA4 summary report.
	 *
	 * @param string $property_id  GA4 property ID.
	 * @param string $access_token Google OAuth access token.
	 * @param array  $period       Period.
	 * @param array  $settings     Plugin settings.
	 * @param array  $conditions   Validated conditions.
	 * @return array|WP_Error
	 */
	public static function run_summary_report( $property_id, $access_token, $period, $settings, $conditions ) {
		$metric_definitions = analytics_report_ai_get_summary_metric_definitions();
		$metric_names       = array_keys( $metric_definitions );

		$result = self::run_report(
			$property_id,
			$access_token,
			$period,
			$settings,
			$conditions,
			array(),
			$metric_names
		);

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return self::extract_summary_values( $result, $metric_names );
	}

	/**
	 * Run daily trend report.
	 *
	 * @param string $property_id  GA4 property ID.
	 * @param string $access_token Google OAuth access token.
	 * @param array  $period       Period.
	 * @param array  $settings     Plugin settings.
	 * @param array  $conditions   Validated conditions.
	 * @return array|WP_Error
	 */
	public static function run_daily_trend_report( $property_id, $access_token, $period, $settings, $conditions ) {
		$result = self::run_report(
			$property_id,
			$access_token,
			$period,
			$settings,
			$conditions,
			array( 'date' ),
			array( 'screenPageViews', 'activeUsers' )
		);

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		$rows = self::extract_dimension_rows(
			$result,
			array( 'date' ),
			array( 'screenPageViews', 'activeUsers' )
		);

		usort(
			$rows,
			static function ( $a, $b ) {
				$date_a = isset( $a['date'] ) ? (string) $a['date'] : '';
				$date_b = isset( $b['date'] ) ? (string) $b['date'] : '';

				return strcmp( $date_a, $date_b );
			}
		);

		foreach ( $rows as $index => $row ) {
			if ( isset( $row['date'] ) && preg_match( '/^\d{8}$/', (string) $row['date'] ) ) {
				$rows[ $index ]['date'] = substr( $row['date'], 0, 4 ) . '-' . substr( $row['date'], 4, 2 ) . '-' . substr( $row['date'], 6, 2 );
			}
		}

		return $rows;
	}

	/**
	 * Run top pages report.
	 *
	 * @param string $property_id  GA4 property ID.
	 * @param string $access_token Google OAuth access token.
	 * @param array  $period       Period.
	 * @param array  $settings     Plugin settings.
	 * @param array  $conditions   Validated conditions.
	 * @return array|WP_Error
	 */
	public static function run_top_pages_report( $property_id, $access_token, $period, $settings, $conditions ) {
		$result = self::run_report(
			$property_id,
			$access_token,
			$period,
			$settings,
			$conditions,
			array( 'pagePath' ),
			array( 'screenPageViews', 'activeUsers' ),
			'screenPageViews',
			10
		);

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return self::extract_dimension_rows(
			$result,
			array( 'pagePath' ),
			array( 'screenPageViews', 'activeUsers' )
		);
	}

	/**
	 * Run traffic channels report.
	 *
	 * @param string $property_id  GA4 property ID.
	 * @param string $access_token Google OAuth access token.
	 * @param array  $period       Period.
	 * @param array  $settings     Plugin settings.
	 * @param array  $conditions   Validated conditions.
	 * @return array|WP_Error
	 */
	public static function run_traffic_channels_report( $property_id, $access_token, $period, $settings, $conditions ) {
		$result = self::run_report(
			$property_id,
			$access_token,
			$period,
			$settings,
			$conditions,
			array( 'sessionDefaultChannelGroup' ),
			array( 'activeUsers' ),
			'activeUsers',
			10
		);

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return self::extract_dimension_rows(
			$result,
			array( 'sessionDefaultChannelGroup' ),
			array( 'activeUsers' )
		);
	}

	/**
	 * Run traffic sources report.
	 *
	 * @param string $property_id  GA4 property ID.
	 * @param string $access_token Google OAuth access token.
	 * @param array  $period       Period.
	 * @param array  $settings     Plugin settings.
	 * @param array  $conditions   Validated conditions.
	 * @return array|WP_Error
	 */
	public static function run_traffic_sources_report( $property_id, $access_token, $period, $settings, $conditions ) {
		$result = self::run_report(
			$property_id,
			$access_token,
			$period,
			$settings,
			$conditions,
			array( 'sessionSource' ),
			array( 'activeUsers' ),
			'activeUsers',
			10
		);

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return self::extract_dimension_rows(
			$result,
			array( 'sessionSource' ),
			array( 'activeUsers' )
		);
	}

	/**
	 * Run regional trends report.
	 *
	 * @param string $property_id  GA4 property ID.
	 * @param string $access_token Google OAuth access token.
	 * @param array  $period       Period.
	 * @param array  $settings     Plugin settings.
	 * @param array  $conditions   Validated conditions.
	 * @return array|WP_Error
	 */
	public static function run_regional_trends_report( $property_id, $access_token, $period, $settings, $conditions ) {
		$extra_filters = array(
			array(
				'filter' => array(
					'fieldName'    => 'country',
					'stringFilter' => array(
						'matchType' => 'EXACT',
						'value'     => 'Japan',
					),
				),
			),
		);

		$result = self::run_report(
			$property_id,
			$access_token,
			$period,
			$settings,
			$conditions,
			array( 'city' ),
			array( 'screenPageViews' ),
			'screenPageViews',
			10,
			$extra_filters
		);

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return self::extract_dimension_rows(
			$result,
			array( 'city' ),
			array( 'screenPageViews' )
		);
	}

	/**
	 * Run GA4 report.
	 *
	 * @param string $property_id       GA4 property ID.
	 * @param string $access_token      Google OAuth access token.
	 * @param array  $period            Period.
	 * @param array  $settings          Plugin settings.
	 * @param array  $conditions        Validated conditions.
	 * @param array  $dimension_names   Dimension names.
	 * @param array  $metric_names      Metric names.
	 * @param string $order_metric_name Order metric name.
	 * @param int    $limit             Limit.
	 * @param array  $extra_filters     Extra dimension filter expressions.
	 * @return array|WP_Error
	 */
	private static function run_report( $property_id, $access_token, $period, $settings, $conditions, $dimension_names, $metric_names, $order_metric_name = '', $limit = 0, $extra_filters = array() ) {
		$property_id  = trim( (string) $property_id );
		$access_token = trim( (string) $access_token );

		if ( '' === $property_id ) {
			return new WP_Error(
				'analytics_report_ai_ga4_property_id_missing',
				__( 'GA4 Property ID is not configured.', 'analytics-report-ai' )
			);
		}

		if ( '' === $access_token ) {
			return new WP_Error(
				'analytics_report_ai_google_access_token_missing',
				__( 'No Google connection is available for GA4 Fetch. Connect Google in Settings and try again.', 'analytics-report-ai' )
			);
		}

		if (
			empty( $period['start_date'] )
			|| empty( $period['end_date'] )
			|| ! analytics_report_ai_is_valid_date_string( $period['start_date'] )
			|| ! analytics_report_ai_is_valid_date_string( $period['end_date'] )
		) {
			return new WP_Error(
				'analytics_report_ai_ga4_invalid_period',
				__( 'GA4 report period is invalid.', 'analytics-report-ai' )
			);
		}

		$request_body = array(
			'dateRanges' => array(
				array(
					'startDate' => $period['start_date'],
					'endDate'   => $period['end_date'],
				),
			),
			'metrics'    => self::build_metric_requests( $metric_names ),
		);

		if ( ! empty( $dimension_names ) ) {
			$request_body['dimensions'] = self::build_dimension_requests( $dimension_names );
		}

		$dimension_filter = self::build_dimension_filter( $settings, $conditions, $extra_filters );

		if ( ! empty( $dimension_filter ) ) {
			$request_body['dimensionFilter'] = $dimension_filter;
		}

		if ( '' !== $order_metric_name ) {
			$request_body['orderBys'] = array(
				array(
					'metric' => array(
						'metricName' => $order_metric_name,
					),
					'desc'   => true,
				),
			);
		}

		if ( $limit > 0 ) {
			$request_body['limit'] = $limit;
		}

		$response = wp_remote_post(
			'https://analyticsdata.googleapis.com/v1beta/properties/' . rawurlencode( $property_id ) . ':runReport',
			array(
				'timeout' => 60,
				'headers' => array(
					'Authorization' => 'Bearer ' . $access_token,
					'Content-Type'  => 'application/json; charset=utf-8',
				),
				'body'    => wp_json_encode( $request_body ),
			)
		);

		if ( is_wp_error( $response ) ) {
			return new WP_Error(
				'analytics_report_ai_ga4_request_failed',
				__( 'Could not connect to Google Analytics Data API. Check the server network connection and try again.', 'analytics-report-ai' )
			);
		}

		$status_code = (int) wp_remote_retrieve_response_code( $response );
		$body        = wp_remote_retrieve_body( $response );
		$data        = json_decode( $body, true );

		if ( $status_code < 200 || $status_code >= 300 ) {
			return self::build_api_error( $status_code, $data );
		}

		if ( ! is_array( $data ) ) {
			return new WP_Error(
				'analytics_report_ai_ga4_invalid_json',
				__( 'Google Analytics Data API returned an unreadable response. Please try again later.', 'analytics-report-ai' )
			);
		}

		return $data;
	}

	/**
	 * Build dimension requests.
	 *
	 * @param array $dimension_names Dimension names.
	 * @return array
	 */
	private static function build_dimension_requests( $dimension_names ) {
		$dimensions = array();

		foreach ( $dimension_names as $dimension_name ) {
			$dimensions[] = array(
				'name' => $dimension_name,
			);
		}

		return $dimensions;
	}

	/**
	 * Build metric requests.
	 *
	 * @param array $metric_names Metric names.
	 * @return array
	 */
	private static function build_metric_requests( $metric_names ) {
		$metrics = array();

		foreach ( $metric_names as $metric_name ) {
			$metrics[] = array(
				'name' => $metric_name,
			);
		}

		return $metrics;
	}

	/**
	 * Build dimension filter.
	 *
	 * @param array $settings      Plugin settings.
	 * @param array $conditions    Validated conditions.
	 * @param array $extra_filters Extra dimension filter expressions.
	 * @return array
	 */
	private static function build_dimension_filter( $settings, $conditions, $extra_filters = array() ) {
		$expressions = array();

		if ( ! empty( $settings['host_filter_enabled'] ) && ! empty( $settings['host_name'] ) ) {
			$expressions[] = array(
				'filter' => array(
					'fieldName'    => 'hostName',
					'stringFilter' => array(
						'matchType' => 'EXACT',
						'value'     => (string) $settings['host_name'],
					),
				),
			);
		}

		$scope = isset( $conditions['scope'] ) ? (string) $conditions['scope'] : 'site';
		$path  = isset( $conditions['path'] ) ? (string) $conditions['path'] : '';

		if ( 'directory' === $scope && '' !== $path ) {
			$expressions[] = array(
				'filter' => array(
					'fieldName'    => 'pagePath',
					'stringFilter' => array(
						'matchType' => 'BEGINS_WITH',
						'value'     => $path,
					),
				),
			);
		}

		if ( 'page' === $scope && '' !== $path ) {
			$expressions[] = array(
				'filter' => array(
					'fieldName'    => 'pagePath',
					'stringFilter' => array(
						'matchType' => 'EXACT',
						'value'     => $path,
					),
				),
			);
		}

		if ( ! empty( $extra_filters ) && is_array( $extra_filters ) ) {
			foreach ( $extra_filters as $extra_filter ) {
				if ( is_array( $extra_filter ) ) {
					$expressions[] = $extra_filter;
				}
			}
		}

		if ( empty( $expressions ) ) {
			return array();
		}

		if ( 1 === count( $expressions ) ) {
			return $expressions[0];
		}

		return array(
			'andGroup' => array(
				'expressions' => $expressions,
			),
		);
	}

	/**
	 * Extract summary values from GA4 response.
	 *
	 * @param array $data         GA4 response.
	 * @param array $metric_names Metric names.
	 * @return array
	 */
	private static function extract_summary_values( $data, $metric_names ) {
		$summary             = array_fill_keys( $metric_names, 0 );
		$metric_values       = isset( $data['rows'][0]['metricValues'] ) && is_array( $data['rows'][0]['metricValues'] )
			? $data['rows'][0]['metricValues']
			: array();
		$row_present         = isset( $data['rows'][0] ) && is_array( $data['rows'][0] );
		$present_metric_keys = array();
		$non_zero_present    = false;

		if ( empty( $metric_values ) ) {
			$summary['_availability'] = self::build_summary_availability(
				$row_present ? 'missing_metric_values' : 'missing_row',
				false,
				0,
				count( $metric_names ),
				array(),
				false
			);

			return $summary;
		}

		foreach ( $metric_names as $index => $metric_name ) {
			if ( isset( $metric_values[ $index ]['value'] ) ) {
				$summary[ $metric_name ] = analytics_report_ai_cast_metric_value(
					$metric_name,
					$metric_values[ $index ]['value']
				);

				$present_metric_keys[] = $metric_name;

				if ( 0.0 !== (float) $summary[ $metric_name ] ) {
					$non_zero_present = true;
				}
			}
		}

		$present_count = count( $present_metric_keys );
		$missing_count = max( 0, count( $metric_names ) - $present_count );

		if ( 0 === $present_count ) {
			$status = 'missing_metric_values';
		} elseif ( $missing_count > 0 ) {
			$status = 'partial_metric_values';
		} elseif ( $non_zero_present ) {
			$status = 'present_non_zero';
		} else {
			$status = 'present_zero';
		}

		$summary['_availability'] = self::build_summary_availability(
			$status,
			0 === $missing_count && $present_count > 0,
			$present_count,
			$missing_count,
			$present_metric_keys,
			$non_zero_present
		);

		return $summary;
	}

	/**
	 * Build safe summary availability metadata.
	 *
	 * @param string $status              Availability status.
	 * @param bool   $all_requested_found Whether all requested metrics were present.
	 * @param int    $present_count       Present metric count.
	 * @param int    $missing_count       Missing metric count.
	 * @param array  $present_metric_keys Present metric keys.
	 * @param bool   $non_zero_present    Whether any present metric is non-zero.
	 * @return array
	 */
	private static function build_summary_availability( $status, $all_requested_found, $present_count, $missing_count, $present_metric_keys, $non_zero_present ) {
		$status = sanitize_key( (string) $status );

		return array(
			'status'               => $status,
			'has_reportable_data'  => $present_count > 0,
			'all_requested_found'  => (bool) $all_requested_found,
			'present_metric_count' => absint( $present_count ),
			'missing_metric_count' => absint( $missing_count ),
			'present_metric_keys'  => array_values( array_map( 'sanitize_key', $present_metric_keys ) ),
			'value_state'          => self::get_summary_value_state( $status, $non_zero_present ),
		);
	}

	/**
	 * Get summary value state for no-data classification.
	 *
	 * @param string $status           Availability status.
	 * @param bool   $non_zero_present Whether any present metric is non-zero.
	 * @return string
	 */
	private static function get_summary_value_state( $status, $non_zero_present ) {
		if ( in_array( $status, array( 'missing_row', 'missing_metric_values' ), true ) ) {
			return 'missing';
		}

		if ( $non_zero_present ) {
			return 'non_zero';
		}

		return 'zero';
	}

	/**
	 * Extract dimension rows from GA4 response.
	 *
	 * @param array $data            GA4 response.
	 * @param array $dimension_names Dimension names.
	 * @param array $metric_names    Metric names.
	 * @return array
	 */
	private static function extract_dimension_rows( $data, $dimension_names, $metric_names ) {
		if ( empty( $data['rows'] ) || ! is_array( $data['rows'] ) ) {
			return array();
		}

		$rows = array();

		foreach ( $data['rows'] as $row ) {
			$item = array();

			foreach ( $dimension_names as $index => $dimension_name ) {
				$item[ $dimension_name ] = isset( $row['dimensionValues'][ $index ]['value'] )
					? sanitize_text_field( (string) $row['dimensionValues'][ $index ]['value'] )
					: '';
			}

			foreach ( $metric_names as $index => $metric_name ) {
				$item[ $metric_name ] = isset( $row['metricValues'][ $index ]['value'] )
					? analytics_report_ai_cast_metric_value( $metric_name, $row['metricValues'][ $index ]['value'] )
					: 0;
			}

			$rows[] = $item;
		}

		return $rows;
	}

	/**
	 * Build API error.
	 *
	 * @param int        $status_code HTTP status code.
	 * @param array|null $data        Response data.
	 * @return WP_Error
	 */
	private static function build_api_error( $status_code, $data ) {
		$message = self::get_safe_api_error_message( $status_code, $data );

		return new WP_Error(
			'analytics_report_ai_ga4_api_error',
			self::append_http_status( $message, $status_code )
		);
	}

	/**
	 * Get a safe user-facing GA4 API error message.
	 *
	 * @param int        $status_code HTTP status code.
	 * @param array|null $data        Response data.
	 * @return string
	 */
	private static function get_safe_api_error_message( $status_code, $data ) {
		if ( 400 === $status_code ) {
			return __( 'Google Analytics Data API rejected the report request. Check the GA4 property ID, date range, and report filters.', 'analytics-report-ai' );
		}

		if ( 401 === $status_code ) {
			return __( 'Google credential is invalid or expired. Reconnect Google in Settings and try again.', 'analytics-report-ai' );
		}

		if ( 403 === $status_code ) {
			return __( 'Google Analytics Data API permission was denied. Check that the token has access to the selected GA4 property.', 'analytics-report-ai' );
		}

		if ( 404 === $status_code ) {
			return __( 'GA4 property was not found. Check that the property ID is correct and that the token can access it.', 'analytics-report-ai' );
		}

		if ( 429 === $status_code ) {
			return __( 'Google Analytics Data API rate limit may have been exceeded. Please wait and try again later.', 'analytics-report-ai' );
		}

		if ( $status_code >= 500 ) {
			return __( 'Google Analytics Data API is temporarily unavailable. Please try again later.', 'analytics-report-ai' );
		}

		if ( self::has_api_error_status( $data, array( 'INVALID_ARGUMENT' ) ) ) {
			return __( 'Google Analytics Data API rejected the report request. Check the GA4 property ID, date range, and report filters.', 'analytics-report-ai' );
		}

		if ( self::has_api_error_status( $data, array( 'UNAUTHENTICATED' ) ) ) {
			return __( 'Google credential is invalid or expired. Reconnect Google in Settings and try again.', 'analytics-report-ai' );
		}

		if ( self::has_api_error_status( $data, array( 'PERMISSION_DENIED' ) ) ) {
			return __( 'Google Analytics Data API permission was denied. Check that the token has access to the selected GA4 property.', 'analytics-report-ai' );
		}

		if ( self::has_api_error_status( $data, array( 'NOT_FOUND' ) ) ) {
			return __( 'GA4 property was not found. Check that the property ID is correct and that the token can access it.', 'analytics-report-ai' );
		}

		if ( self::has_api_error_status( $data, array( 'RESOURCE_EXHAUSTED' ) ) ) {
			return __( 'Google Analytics Data API rate limit may have been exceeded. Please wait and try again later.', 'analytics-report-ai' );
		}

		if ( self::has_api_error_status( $data, array( 'ABORTED', 'DEADLINE_EXCEEDED', 'INTERNAL', 'UNAVAILABLE', 'UNKNOWN' ) ) ) {
			return __( 'Google Analytics Data API is temporarily unavailable. Please try again later.', 'analytics-report-ai' );
		}

		return __( 'Google Analytics Data API returned an unexpected error. Check the settings and try again.', 'analytics-report-ai' );
	}

	/**
	 * Check whether the GA4 error status matches one of the expected safe codes.
	 *
	 * @param array|null $data     Response data.
	 * @param array      $statuses Allowed API error statuses.
	 * @return bool
	 */
	private static function has_api_error_status( $data, $statuses ) {
		if ( ! is_array( $data ) || empty( $data['error']['status'] ) ) {
			return false;
		}

		$status = sanitize_key( (string) $data['error']['status'] );
		$status = strtoupper( str_replace( '-', '_', $status ) );

		return in_array( $status, $statuses, true );
	}

	/**
	 * Append the HTTP status code without exposing the response body.
	 *
	 * @param string $message     Safe user-facing message.
	 * @param int    $status_code HTTP status code.
	 * @return string
	 */
	private static function append_http_status( $message, $status_code ) {
		if ( $status_code <= 0 ) {
			return $message;
		}

		return sprintf(
			/* translators: 1: safe user-facing error message, 2: HTTP status code. */
			__( '%1$s HTTP status: %2$d', 'analytics-report-ai' ),
			$message,
			$status_code
		);
	}
}
