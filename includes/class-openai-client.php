<?php
/**
 * OpenAI API client.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles OpenAI API requests and response parsing for report generation.
 *
 * @since 0.1.0
 */
final class Analytics_Report_AI_OpenAI_Client {

	/**
	 * Generate report text using OpenAI Responses API.
	 *
	 * @param array $payload AI payload.
	 * @param array $settings Plugin settings.
	 * @return string|WP_Error
	 */
	public static function generate_report( $payload, $settings ) {
		$api_key = analytics_report_ai_resolve_openai_api_key( $settings );

		if ( '' === $api_key ) {
			return new WP_Error(
				'analytics_report_ai_openai_api_key_missing',
				__( 'OpenAI API key is not configured. Open Settings and add an API key, or configure it on the server before generating.', 'analytics-report-ai' )
			);
		}

		$request_body = array(
			'model'             => self::get_model(),
			'instructions'      => Analytics_Report_AI_Prompt_Builder::build_system_prompt(),
			'input'             => Analytics_Report_AI_Prompt_Builder::build_user_input( $payload ),
			'max_output_tokens' => 2200,
		);

		$response = wp_remote_post(
			'https://api.openai.com/v1/responses',
			array(
				'timeout' => 60,
				'headers' => array(
					'Authorization' => 'Bearer ' . $api_key,
					'Content-Type'  => 'application/json',
				),
				'body'    => wp_json_encode( $request_body ),
			)
		);

		if ( is_wp_error( $response ) ) {
			return new WP_Error(
				'analytics_report_ai_openai_request_failed',
				__( 'Could not connect to OpenAI API. Check the server network connection and try again.', 'analytics-report-ai' )
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
				'analytics_report_ai_openai_invalid_json',
				__( 'OpenAI API returned an unreadable response. Please try again later.', 'analytics-report-ai' )
			);
		}

		$text = self::extract_response_text( $data );

		if ( '' === $text ) {
			return new WP_Error(
				'analytics_report_ai_openai_empty_text',
				__( 'OpenAI API returned no report text.', 'analytics-report-ai' )
			);
		}

		return $text;
	}

	/**
	 * Get OpenAI model.
	 *
	 * @return string
	 */
	private static function get_model() {
		if ( defined( 'ANALYTICS_REPORT_AI_OPENAI_MODEL' ) && ANALYTICS_REPORT_AI_OPENAI_MODEL ) {
			return ANALYTICS_REPORT_AI_OPENAI_MODEL;
		}

		return 'gpt-5.4-mini';
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
			'analytics_report_ai_openai_api_error',
			self::append_http_status( $message, $status_code )
		);
	}

	/**
	 * Get a safe user-facing OpenAI API error message.
	 *
	 * @param int        $status_code HTTP status code.
	 * @param array|null $data        Response data.
	 * @return string
	 */
	private static function get_safe_api_error_message( $status_code, $data ) {
		if ( 400 === $status_code ) {
			return __( 'OpenAI API rejected the request. Check the report data and model configuration.', 'analytics-report-ai' );
		}

		if ( 401 === $status_code ) {
			return __( 'OpenAI API authentication failed. Review the configured OpenAI API key without sharing key values.', 'analytics-report-ai' );
		}

		if ( 403 === $status_code ) {
			return __( 'OpenAI API permission was denied. If you use a restricted key, allow Model capabilities and Responses (/v1/responses) requests.', 'analytics-report-ai' );
		}

		if ( 404 === $status_code ) {
			return __( 'The configured OpenAI model or endpoint was not found. Check the model configuration.', 'analytics-report-ai' );
		}

		if ( 429 === $status_code ) {
			return __( 'OpenAI API rate limit or quota may have been exceeded. Check your OpenAI Platform usage and billing.', 'analytics-report-ai' );
		}

		if ( $status_code >= 500 ) {
			return __( 'OpenAI API is temporarily unavailable. Please try again later.', 'analytics-report-ai' );
		}

		if ( self::has_api_error_value( $data, 'type', array( 'invalid_request_error' ) ) ) {
			return __( 'OpenAI API rejected the request. Check the report data and model configuration.', 'analytics-report-ai' );
		}

		if ( self::has_api_error_value( $data, 'type', array( 'authentication_error' ) ) ) {
			return __( 'OpenAI API authentication failed. Review the configured OpenAI API key without sharing key values.', 'analytics-report-ai' );
		}

		if (
			self::has_api_error_value( $data, 'type', array( 'permission_error' ) )
			|| self::has_api_error_value( $data, 'code', array( 'insufficient_permissions' ) )
		) {
			return __( 'OpenAI API permission was denied. If you use a restricted key, allow Model capabilities and Responses (/v1/responses) requests.', 'analytics-report-ai' );
		}

		if ( self::has_api_error_value( $data, 'code', array( 'model_not_found' ) ) ) {
			return __( 'The configured OpenAI model or endpoint was not found. Check the model configuration.', 'analytics-report-ai' );
		}

		if (
			self::has_api_error_value( $data, 'type', array( 'rate_limit_error' ) )
			|| self::has_api_error_value( $data, 'code', array( 'insufficient_quota', 'rate_limit_exceeded' ) )
		) {
			return __( 'OpenAI API rate limit or quota may have been exceeded. Check your OpenAI Platform usage and billing.', 'analytics-report-ai' );
		}

		return __( 'OpenAI API returned an unexpected error. Review the OpenAI configuration and try again without sharing request or response details.', 'analytics-report-ai' );
	}

	/**
	 * Check whether an OpenAI error field matches a safe known value.
	 *
	 * @param array|null $data   Response data.
	 * @param string     $field  Error field name.
	 * @param array      $values Allowed field values.
	 * @return bool
	 */
	private static function has_api_error_value( $data, $field, $values ) {
		if ( ! is_array( $data ) || empty( $data['error'][ $field ] ) ) {
			return false;
		}

		$value = sanitize_key( (string) $data['error'][ $field ] );

		return in_array( $value, $values, true );
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

	/**
	 * Extract output text from Responses API response.
	 *
	 * @param array $data Response data.
	 * @return string
	 */
	private static function extract_response_text( $data ) {
		if ( isset( $data['output_text'] ) && is_string( $data['output_text'] ) ) {
			return trim( $data['output_text'] );
		}

		if ( empty( $data['output'] ) || ! is_array( $data['output'] ) ) {
			return '';
		}

		$texts = array();

		foreach ( $data['output'] as $output_item ) {
			if ( empty( $output_item['content'] ) || ! is_array( $output_item['content'] ) ) {
				continue;
			}

			foreach ( $output_item['content'] as $content_item ) {
				if (
					isset( $content_item['type'], $content_item['text'] )
					&& 'output_text' === $content_item['type']
					&& is_string( $content_item['text'] )
				) {
					$texts[] = $content_item['text'];
				}
			}
		}

		return trim( implode( "\n", $texts ) );
	}
}
