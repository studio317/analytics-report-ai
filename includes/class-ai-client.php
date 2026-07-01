<?php
/**
 * Provider-neutral WordPress AI Client wrapper.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles AI report generation through the WordPress AI Client public API.
 *
 * @since 0.1.0
 */
final class Analytics_Report_AI_AI_Client {

	/**
	 * Maximum generated output tokens.
	 *
	 * @var int
	 */
	private const MAX_OUTPUT_TOKENS = 2200;

	/**
	 * Check whether text generation is available for the current report payload.
	 *
	 * This support check uses the same prompt configuration as generation and
	 * does not expose provider, model, credential, request, or response details.
	 *
	 * @param array $payload AI payload.
	 * @return bool
	 */
	public static function is_text_generation_available( $payload ) {
		if ( ! function_exists( 'wp_ai_client_prompt' ) ) {
			return false;
		}

		$prompt = self::build_prompt_parts( $payload );

		if ( is_wp_error( $prompt ) ) {
			return false;
		}

		try {
			return true === wp_ai_client_prompt( $prompt['user_prompt'] )
				->using_system_instruction( $prompt['system_instruction'] )
				->using_max_tokens( self::MAX_OUTPUT_TOKENS )
				->is_supported_for_text_generation();
		} catch ( Throwable $e ) {
			unset( $e );

			return false;
		}
	}

	/**
	 * Generate report text using the WordPress AI Client public API.
	 *
	 * @param array $payload AI payload.
	 * @return string|WP_Error
	 */
	public static function generate_report( $payload ) {
		if ( ! function_exists( 'wp_ai_client_prompt' ) ) {
			return self::build_error( 'ai_text_generation_unavailable' );
		}

		$prompt = self::build_prompt_parts( $payload );

		if ( is_wp_error( $prompt ) ) {
			return self::build_error( 'ai_generation_failed' );
		}

		try {
			$builder = wp_ai_client_prompt( $prompt['user_prompt'] )
				->using_system_instruction( $prompt['system_instruction'] )
				->using_max_tokens( self::MAX_OUTPUT_TOKENS );

			if ( true !== $builder->is_supported_for_text_generation() ) {
				return self::build_error( 'ai_text_generation_unavailable' );
			}

			$generated_text = $builder->generate_text();
		} catch ( Throwable $e ) {
			unset( $e );

			return self::build_error( 'ai_generation_failed' );
		}

		if ( is_wp_error( $generated_text ) ) {
			return self::build_error( 'ai_generation_failed' );
		}

		if ( ! is_string( $generated_text ) ) {
			return self::build_error( 'ai_generation_empty_text' );
		}

		$generated_text = trim( $generated_text );

		if ( '' === $generated_text ) {
			return self::build_error( 'ai_generation_empty_text' );
		}

		return $generated_text;
	}

	/**
	 * Build provider-neutral prompt parts.
	 *
	 * @param array $payload AI payload.
	 * @return array|WP_Error
	 */
	private static function build_prompt_parts( $payload ) {
		$validation = analytics_report_ai_validate_ai_payload( $payload );

		if ( is_wp_error( $validation ) ) {
			return $validation;
		}

		return array(
			'system_instruction' => Analytics_Report_AI_Prompt_Builder::build_system_prompt(
				isset( $payload['report_language'] ) && is_array( $payload['report_language'] )
					? $payload['report_language']
					: array()
			),
			'user_prompt'        => Analytics_Report_AI_Prompt_Builder::build_user_input( $payload ),
		);
	}

	/**
	 * Build a safe provider-neutral WP_Error.
	 *
	 * @param string $category Error category.
	 * @return WP_Error
	 */
	private static function build_error( $category ) {
		switch ( $category ) {
			case 'ai_text_generation_unavailable':
				return new WP_Error(
					'ai_text_generation_unavailable',
					__( 'AI text generation is unavailable. Configure a compatible text-generation provider in WordPress Settings > Connectors before generating a report draft.', 'studio317-report-drafts-google-analytics' )
				);

			case 'ai_generation_empty_text':
				return new WP_Error(
					'ai_generation_empty_text',
					__( 'AI generation did not return usable report text. Review the AI provider configuration and try again.', 'studio317-report-drafts-google-analytics' )
				);

			case 'ai_generation_failed':
			default:
				return new WP_Error(
					'ai_generation_failed',
					__( 'AI generation failed. Review the WordPress AI provider configuration and try again. Do not share credentials, request data, provider responses, or generated report content in support requests.', 'studio317-report-drafts-google-analytics' )
				);
		}
	}
}
