<?php
/**
 * GA4 Data API client.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
				__( 'Google access token is not saved. Please save a temporary access token in Settings.', 'analytics-report-ai' )
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
				sprintf(
					/* translators: %s: error message */
					__( 'GA4 Data API request failed: %s', 'analytics-report-ai' ),
					$response->get_error_message()
				)
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
				__( 'GA4 Data API returned an invalid JSON response.', 'analytics-report-ai' )
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
		$summary = array_fill_keys( $metric_names, 0 );

		if ( empty( $data['rows'][0]['metricValues'] ) || ! is_array( $data['rows'][0]['metricValues'] ) ) {
			return $summary;
		}

		foreach ( $metric_names as $index => $metric_name ) {
			if ( isset( $data['rows'][0]['metricValues'][ $index ]['value'] ) ) {
				$summary[ $metric_name ] = analytics_report_ai_cast_metric_value(
					$metric_name,
					$data['rows'][0]['metricValues'][ $index ]['value']
				);
			}
		}

		return $summary;
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
		$message = '';

		if ( is_array( $data ) && ! empty( $data['error']['message'] ) ) {
			$message = sanitize_text_field( (string) $data['error']['message'] );
		}

		if ( '' === $message ) {
			$message = __( 'Unknown GA4 Data API error.', 'analytics-report-ai' );
		}

		return new WP_Error(
			'analytics_report_ai_ga4_api_error',
			sprintf(
				/* translators: 1: HTTP status code, 2: API error message */
				__( 'GA4 Data API returned an error. HTTP status: %1$d. Message: %2$s', 'analytics-report-ai' ),
				$status_code,
				$message
			)
		);
	}
}