<?php
/**
 * OpenAI API client.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Analytics_Report_AI_OpenAI_Client {

	/**
	 * Generate report text using OpenAI Responses API.
	 *
	 * @param array $payload AI payload.
	 * @param array $settings Plugin settings.
	 * @return string|WP_Error
	 */
	public static function generate_report( $payload, $settings ) {
		$api_key = isset( $settings['openai_api_key'] ) ? trim( (string) $settings['openai_api_key'] ) : '';

		if ( '' === $api_key ) {
			return new WP_Error(
				'analytics_report_ai_openai_api_key_missing',
				__( 'OpenAI API key is not saved. Please save an API key in Settings.', 'analytics-report-ai' )
			);
		}

		$request_body = array(
			'model'              => self::get_model(),
			'instructions'       => Analytics_Report_AI_Prompt_Builder::build_system_prompt(),
			'input'              => Analytics_Report_AI_Prompt_Builder::build_user_input( $payload ),
			'max_output_tokens'  => 2200,
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
				sprintf(
					/* translators: %s: error message */
					__( 'OpenAI API request failed: %s', 'analytics-report-ai' ),
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
				'analytics_report_ai_openai_invalid_json',
				__( 'OpenAI API returned an invalid JSON response.', 'analytics-report-ai' )
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
	 * @param array|null $data Response data.
	 * @return WP_Error
	 */
	private static function build_api_error( $status_code, $data ) {
		$message = '';

		if ( is_array( $data ) && ! empty( $data['error']['message'] ) ) {
			$message = sanitize_text_field( (string) $data['error']['message'] );
		}

		if ( '' === $message ) {
			$message = __( 'Unknown API error.', 'analytics-report-ai' );
		}

		$permission_note = __(
			' If you are using a Restricted API key, make sure Model capabilities and Responses (/v1/responses) are set to Request.',
			'analytics-report-ai'
		);

		return new WP_Error(
			'analytics_report_ai_openai_api_error',
			sprintf(
				/* translators: 1: HTTP status code, 2: API error message, 3: permission note */
				__( 'OpenAI API returned an error. HTTP status: %1$d. Message: %2$s%3$s', 'analytics-report-ai' ),
				$status_code,
				$message,
				$permission_note
			)
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