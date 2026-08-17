<?php
/**
 * OAuth cryptographic helpers.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'analytics_report_ai_base64url_encode' ) ) {
	/**
	 * Encode binary data as unpadded Base64URL.
	 *
	 * @param string $value Binary value.
	 * @return string
	 */
	function analytics_report_ai_base64url_encode( $value ) {
		return rtrim(
			strtr(
				base64_encode( $value ),
				'+/',
				'-_'
			),
			'='
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_base64url_decode_canonical' ) ) {
	/**
	 * Decode canonical unpadded Base64URL.
	 *
	 * @param string $value Encoded value.
	 * @return string|false
	 */
	function analytics_report_ai_base64url_decode_canonical( $value ) {
		if (
			! is_string( $value ) ||
			'' === $value ||
			1 === strlen( $value ) % 4 ||
			1 !== preg_match( '/^[A-Za-z0-9_-]+$/', $value )
		) {
			return false;
		}

		$base64 = strtr( $value, '-_', '+/' );
		$base64 = str_pad(
			$base64,
			(int) ( ceil( strlen( $base64 ) / 4 ) * 4 ),
			'=',
			STR_PAD_RIGHT
		);

		$decoded = base64_decode( $base64, true );

		if ( false === $decoded ) {
			return false;
		}

		if ( ! hash_equals( analytics_report_ai_base64url_encode( $decoded ), $value ) ) {
			return false;
		}

		return $decoded;
	}
}

if ( ! function_exists( 'analytics_report_ai_decode_oauth_transaction_key' ) ) {
	/**
	 * Decode a 32-byte OAuth transaction key.
	 *
	 * @param string $transaction_key Base64URL transaction key.
	 * @return string|false
	 */
	function analytics_report_ai_decode_oauth_transaction_key( $transaction_key ) {
		if (
			! is_string( $transaction_key ) ||
			43 !== strlen( $transaction_key )
		) {
			return false;
		}

		$key = analytics_report_ai_base64url_decode_canonical( $transaction_key );

		if ( false === $key || 32 !== strlen( $key ) ) {
			return false;
		}

		return $key;
	}
}

if ( ! function_exists( 'analytics_report_ai_validate_oauth_handoff_payload' ) ) {
	/**
	 * Validate a decrypted managed OAuth handoff payload.
	 *
	 * Current-time expiry is intentionally validated by the callback layer,
	 * rather than by this structural crypto helper.
	 *
	 * @param mixed $payload Decoded payload.
	 * @return bool
	 */
	function analytics_report_ai_validate_oauth_handoff_payload( $payload ) {
		if ( ! is_array( $payload ) ) {
			return false;
		}

		$expected_keys = array(
			'protocol_version',
			'result',
			'transaction_id',
			'site_instance_id',
			'exchange_ticket',
			'issued_at',
			'expires_at',
			'jti',
		);

		$actual_keys = array_keys( $payload );

		sort( $expected_keys );
		sort( $actual_keys );

		if ( $expected_keys !== $actual_keys ) {
			return false;
		}

		if (
			'1' !== $payload['protocol_version'] ||
			'success' !== $payload['result']
		) {
			return false;
		}

		if (
			! is_string( $payload['transaction_id'] ) ||
			1 !== preg_match( '/^[a-f0-9]{32}$/', $payload['transaction_id'] ) ||
			! is_string( $payload['site_instance_id'] ) ||
			1 !== preg_match( '/^[a-f0-9]{32}$/', $payload['site_instance_id'] )
		) {
			return false;
		}

		if (
			! is_string( $payload['exchange_ticket'] ) ||
			'' === $payload['exchange_ticket'] ||
			strlen( $payload['exchange_ticket'] ) > 24576 ||
			1 !== preg_match(
				'/^x1\.[A-Za-z0-9_-]{1,32}\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$/',
				$payload['exchange_ticket']
			)
		) {
			return false;
		}

		if (
			! is_int( $payload['issued_at'] ) ||
			! is_int( $payload['expires_at'] ) ||
			$payload['expires_at'] <= $payload['issued_at'] ||
			$payload['expires_at'] - $payload['issued_at'] > 300
		) {
			return false;
		}

		if (
			! is_string( $payload['jti'] ) ||
			22 !== strlen( $payload['jti'] ) ||
			1 !== preg_match( '/^[A-Za-z0-9_-]{22}$/', $payload['jti'] )
		) {
			return false;
		}

		return true;
	}
}

if ( ! function_exists( 'analytics_report_ai_decrypt_oauth_handoff' ) ) {
	/**
	 * Decrypt a managed OAuth handoff with its transaction-specific K_tx key.
	 *
	 * @param string $handoff         Opaque h1 handoff.
	 * @param string $transaction_key Base64URL transaction key.
	 * @return array|false Validated payload, or false on failure.
	 */
	function analytics_report_ai_decrypt_oauth_handoff( $handoff, $transaction_key ) {
		if (
			! is_string( $handoff ) ||
			'' === $handoff ||
			strlen( $handoff ) > 40960
		) {
			return false;
		}

		$parts = explode( '.', $handoff );

		if (
			3 !== count( $parts ) ||
			'h1' !== $parts[0]
		) {
			return false;
		}

		$iv         = analytics_report_ai_base64url_decode_canonical( $parts[1] );
		$encrypted  = analytics_report_ai_base64url_decode_canonical( $parts[2] );
		$key        = analytics_report_ai_decode_oauth_transaction_key( $transaction_key );
		$tag_length = 16;

		if (
			false === $iv ||
			12 !== strlen( $iv ) ||
			false === $encrypted ||
			strlen( $encrypted ) <= $tag_length ||
			false === $key
		) {
			return false;
		}

		$ciphertext = substr( $encrypted, 0, -$tag_length );
		$tag        = substr( $encrypted, -$tag_length );

		if ( ! function_exists( 'openssl_decrypt' ) ) {
			return false;
		}

		$plaintext = openssl_decrypt(
			$ciphertext,
			'aes-256-gcm',
			$key,
			OPENSSL_RAW_DATA,
			$iv,
			$tag,
			'studio317-report-drafts-google-analytics-oauth:handoff:v1'
		);

		unset( $key, $ciphertext, $tag, $encrypted );

		if ( false === $plaintext ) {
			return false;
		}

		$payload = json_decode( $plaintext, true );

		unset( $plaintext );

		if (
			JSON_ERROR_NONE !== json_last_error() ||
			! analytics_report_ai_validate_oauth_handoff_payload( $payload )
		) {
			return false;
		}

		return $payload;
	}
}

/**
 * Validate a decrypted managed OAuth exchange response.
 *
 * @param mixed $payload Decrypted payload.
 * @return bool
 */
function analytics_report_ai_validate_oauth_exchange_response_payload( $payload ) {
	if ( ! is_array( $payload ) ) {
		return false;
	}

	$expected_keys = array(
		'protocol_version',
		'result',
		'transaction_id',
		'site_instance_id',
		'exchange_ticket_fingerprint',
		'access_token',
		'refresh_token',
		'expires_in',
		'refresh_token_expires_in',
		'scope',
		'token_type',
		'issued_at',
		'expires_at',
		'jti',
	);

	$payload_keys = array_keys( $payload );

	sort( $expected_keys );
	sort( $payload_keys );

	if ( $expected_keys !== $payload_keys ) {
		return false;
	}

	if (
		'1' !== $payload['protocol_version'] ||
		'success' !== $payload['result']
	) {
		return false;
	}

	if (
		! is_string( $payload['transaction_id'] ) ||
		1 !== preg_match( '/^[a-f0-9]{32}$/', $payload['transaction_id'] ) ||
		! is_string( $payload['site_instance_id'] ) ||
		1 !== preg_match( '/^[a-f0-9]{32}$/', $payload['site_instance_id'] )
	) {
		return false;
	}

	if (
		! is_string( $payload['exchange_ticket_fingerprint'] ) ||
		43 !== strlen( $payload['exchange_ticket_fingerprint'] )
	) {
		return false;
	}

	$fingerprint = analytics_report_ai_base64url_decode_canonical(
		$payload['exchange_ticket_fingerprint']
	);

	if (
		false === $fingerprint ||
		32 !== strlen( $fingerprint )
	) {
		return false;
	}

	unset( $fingerprint );

	foreach ( array( 'access_token', 'refresh_token' ) as $token_key ) {
		if (
			! is_string( $payload[ $token_key ] ) ||
			'' === $payload[ $token_key ] ||
			strlen( $payload[ $token_key ] ) > 16384 ||
			1 === preg_match( '/[\x00-\x1F\x7F]/', $payload[ $token_key ] )
		) {
			return false;
		}
	}

	if (
		! is_int( $payload['expires_in'] ) ||
		$payload['expires_in'] <= 0
	) {
		return false;
	}

	if (
		null !== $payload['refresh_token_expires_in'] &&
		(
			! is_int( $payload['refresh_token_expires_in'] ) ||
			$payload['refresh_token_expires_in'] <= 0
		)
	) {
		return false;
	}

	if (
		'https://www.googleapis.com/auth/analytics.readonly' !== $payload['scope'] ||
		'Bearer' !== $payload['token_type']
	) {
		return false;
	}

	if (
		! is_int( $payload['issued_at'] ) ||
		! is_int( $payload['expires_at'] ) ||
		$payload['expires_at'] <= $payload['issued_at'] ||
		$payload['expires_at'] - $payload['issued_at'] > 300
	) {
		return false;
	}

	if (
		! is_string( $payload['jti'] ) ||
		22 !== strlen( $payload['jti'] )
	) {
		return false;
	}

	$jti = analytics_report_ai_base64url_decode_canonical(
		$payload['jti']
	);

	if (
		false === $jti ||
		16 !== strlen( $jti )
	) {
		return false;
	}

	return true;
}

/**
 * Decrypt a managed OAuth exchange response using the transaction key.
 *
 * @param string $response_token Encrypted r1 response.
 * @param string $transaction_key Base64URL-encoded 32-byte transaction key.
 * @return array|false Validated payload or false.
 */
function analytics_report_ai_decrypt_oauth_exchange_response( $response_token, $transaction_key ) {
	if (
		! is_string( $response_token ) ||
		'' === $response_token ||
		strlen( $response_token ) > 73728 ||
		! is_string( $transaction_key )
	) {
		return false;
	}

	if ( ! function_exists( 'openssl_decrypt' ) ) {
		return false;
	}

	$key = analytics_report_ai_decode_oauth_transaction_key(
		$transaction_key
	);

	if ( false === $key ) {
		return false;
	}

	$parts = explode( '.', $response_token );

	if (
		3 !== count( $parts ) ||
		'r1' !== $parts[0]
	) {
		return false;
	}

	$iv                 = analytics_report_ai_base64url_decode_canonical(
		$parts[1]
	);
	$ciphertext_and_tag = analytics_report_ai_base64url_decode_canonical(
		$parts[2]
	);

	if (
		false === $iv ||
		12 !== strlen( $iv ) ||
		false === $ciphertext_and_tag ||
		strlen( $ciphertext_and_tag ) <= 16
	) {
		return false;
	}

	$tag        = substr( $ciphertext_and_tag, -16 );
	$ciphertext = substr( $ciphertext_and_tag, 0, -16 );

	$plaintext = openssl_decrypt(
		$ciphertext,
		'aes-256-gcm',
		$key,
		OPENSSL_RAW_DATA,
		$iv,
		$tag,
		'studio317-report-drafts-google-analytics-oauth:exchange-response:v1'
	);

	unset(
		$key,
		$iv,
		$tag,
		$ciphertext,
		$ciphertext_and_tag
	);

	if ( false === $plaintext ) {
		return false;
	}

	$payload = json_decode(
		$plaintext,
		true,
		32,
		JSON_BIGINT_AS_STRING
	);

	unset( $plaintext );

	if (
		JSON_ERROR_NONE !== json_last_error() ||
		! analytics_report_ai_validate_oauth_exchange_response_payload(
			$payload
		)
	) {
		return false;
	}

	return $payload;
}
