<?php
/**
 * Managed Google OAuth transaction helpers.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'analytics_report_ai_is_managed_oauth_identifier' ) ) {
	/**
	 * Validate a lowercase 128-bit hexadecimal identifier.
	 *
	 * @param mixed $value Candidate identifier.
	 * @return bool
	 */
	function analytics_report_ai_is_managed_oauth_identifier( $value ) {
		return is_string( $value )
			&& 32 === strlen( $value )
			&& 1 === preg_match( '/^[a-f0-9]{32}$/', $value );
	}
}

if ( ! function_exists( 'analytics_report_ai_generate_managed_oauth_identifier' ) ) {
	/**
	 * Generate a cryptographically random 128-bit identifier.
	 *
	 * @return string Empty string on failure.
	 */
	function analytics_report_ai_generate_managed_oauth_identifier() {
		try {
			return bin2hex( random_bytes( 16 ) );
		} catch ( Exception $exception ) {
			unset( $exception );

			return '';
		}
	}
}

if ( ! function_exists( 'analytics_report_ai_get_managed_oauth_site_instance_id' ) ) {
	/**
	 * Get the existing managed OAuth site instance identifier.
	 *
	 * This helper never creates or repairs the option.
	 *
	 * @return string Empty string when missing or invalid.
	 */
	function analytics_report_ai_get_managed_oauth_site_instance_id() {
		$value = get_option(
			ANALYTICS_REPORT_AI_MANAGED_OAUTH_SITE_INSTANCE_OPTION_NAME,
			false
		);

		if ( ! analytics_report_ai_is_managed_oauth_identifier( $value ) ) {
			return '';
		}

		return $value;
	}
}

if ( ! function_exists( 'analytics_report_ai_ensure_managed_oauth_site_instance_id' ) ) {
	/**
	 * Get or create the persistent non-secret site instance identifier.
	 *
	 * An existing malformed value is not silently replaced.
	 *
	 * @return string Empty string on failure.
	 */
	function analytics_report_ai_ensure_managed_oauth_site_instance_id() {
		$existing = get_option(
			ANALYTICS_REPORT_AI_MANAGED_OAUTH_SITE_INSTANCE_OPTION_NAME,
			false
		);

		if ( analytics_report_ai_is_managed_oauth_identifier( $existing ) ) {
			return $existing;
		}

		if ( false !== $existing ) {
			return '';
		}

		$generated = analytics_report_ai_generate_managed_oauth_identifier();

		if ( '' === $generated ) {
			return '';
		}

		if (
			add_option(
				ANALYTICS_REPORT_AI_MANAGED_OAUTH_SITE_INSTANCE_OPTION_NAME,
				$generated,
				'',
				false
			)
		) {
			return $generated;
		}

		unset( $generated );

		return analytics_report_ai_get_managed_oauth_site_instance_id();
	}
}

if ( ! function_exists( 'analytics_report_ai_derive_managed_oauth_site_master' ) ) {
	/**
	 * Derive a site-specific managed OAuth master key.
	 *
	 * @param string $site_instance_id Site instance identifier.
	 * @return string|false Raw 32-byte key, or false.
	 */
	function analytics_report_ai_derive_managed_oauth_site_master( $site_instance_id ) {
		if (
			! analytics_report_ai_is_managed_oauth_identifier(
				$site_instance_id
			) ||
			! function_exists( 'hash_hkdf' )
		) {
			return false;
		}

		$secret = wp_salt( 'auth' );
		$salt   = hex2bin( $site_instance_id );

		if (
			! is_string( $secret ) ||
			'' === $secret ||
			false === $salt
		) {
			return false;
		}

		try {
			$key = hash_hkdf(
				'sha256',
				$secret,
				32,
				'studio317-report-drafts-google-analytics-oauth:site-master:v1',
				$salt
			);
		} catch ( Throwable $throwable ) {
			unset( $throwable, $secret, $salt );

			return false;
		}

		unset( $secret, $salt );

		if ( ! is_string( $key ) || 32 !== strlen( $key ) ) {
			return false;
		}

		return $key;
	}
}

if ( ! function_exists( 'analytics_report_ai_derive_managed_oauth_transaction_key' ) ) {
	/**
	 * Derive transaction-specific K_tx without storing the key.
	 *
	 * @param string $transaction_id  Transaction identifier.
	 * @param string $site_instance_id Site instance identifier.
	 * @return string Base64URL K_tx, or empty string.
	 */
	function analytics_report_ai_derive_managed_oauth_transaction_key( $transaction_id, $site_instance_id ) {
		if (
			! analytics_report_ai_is_managed_oauth_identifier( $transaction_id ) ||
			! analytics_report_ai_is_managed_oauth_identifier( $site_instance_id )
		) {
			return '';
		}

		$site_master = analytics_report_ai_derive_managed_oauth_site_master(
			$site_instance_id
		);
		$salt        = hex2bin( $transaction_id );

		if ( false === $site_master || false === $salt ) {
			return '';
		}

		try {
			$key = hash_hkdf(
				'sha256',
				$site_master,
				32,
				'studio317-report-drafts-google-analytics-oauth:transaction-key:v1',
				$salt
			);
		} catch ( Throwable $throwable ) {
			unset( $throwable, $site_master, $salt );

			return '';
		}

		unset( $site_master, $salt );

		if ( ! is_string( $key ) || 32 !== strlen( $key ) ) {
			return '';
		}

		return analytics_report_ai_base64url_encode( $key );
	}
}

if ( ! function_exists( 'analytics_report_ai_get_managed_oauth_transaction_transient_key' ) ) {
	/**
	 * Build a transaction transient key.
	 *
	 * @param string $transaction_id Transaction identifier.
	 * @return string Empty string when invalid.
	 */
	function analytics_report_ai_get_managed_oauth_transaction_transient_key( $transaction_id ) {
		if ( ! analytics_report_ai_is_managed_oauth_identifier( $transaction_id ) ) {
			return '';
		}

		return 'analytics_report_ai_managed_oauth_tx_' . $transaction_id;
	}
}

if ( ! function_exists( 'analytics_report_ai_create_managed_oauth_transaction' ) ) {
	/**
	 * Create a temporary managed OAuth transaction.
	 *
	 * K_tx is returned request-locally but is not stored in the transient.
	 *
	 * @param int $user_id Current WordPress user ID.
	 * @return array|false
	 */
	function analytics_report_ai_create_managed_oauth_transaction( $user_id ) {
		$user_id = absint( $user_id );

		if ( $user_id <= 0 ) {
			return false;
		}

		$site_instance_id = analytics_report_ai_ensure_managed_oauth_site_instance_id();
		$transaction_id   = analytics_report_ai_generate_managed_oauth_identifier();

		if ( '' === $site_instance_id || '' === $transaction_id ) {
			return false;
		}

		$transaction_key = analytics_report_ai_derive_managed_oauth_transaction_key(
			$transaction_id,
			$site_instance_id
		);

		if ( '' === $transaction_key ) {
			return false;
		}

		$created_at    = time();
		$expires_at    = $created_at + 600;
		$transient_key = analytics_report_ai_get_managed_oauth_transaction_transient_key(
			$transaction_id
		);

		$stored = set_transient(
			$transient_key,
			array(
				'user_id'          => $user_id,
				'site_instance_id' => $site_instance_id,
				'created_at'       => $created_at,
				'expires_at'       => $expires_at,
			),
			600
		);

		if ( ! $stored ) {
			unset( $transaction_key );

			return false;
		}

		return array(
			'transaction_id'   => $transaction_id,
			'site_instance_id' => $site_instance_id,
			'transaction_key'  => $transaction_key,
			'issued_at'        => $created_at,
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_consume_managed_oauth_transaction' ) ) {
	/**
	 * Consume a temporary managed OAuth transaction.
	 *
	 * The transient is deleted immediately after retrieval. K_tx is re-derived
	 * from WordPress secret material and is never read from persistent storage.
	 *
	 * @param string $transaction_id Transaction identifier.
	 * @param int    $user_id        Current WordPress user ID.
	 * @return array|false
	 */
	function analytics_report_ai_consume_managed_oauth_transaction( $transaction_id, $user_id ) {
		$user_id = absint( $user_id );

		if (
			$user_id <= 0 ||
			! analytics_report_ai_is_managed_oauth_identifier( $transaction_id )
		) {
			return false;
		}

		$transient_key = analytics_report_ai_get_managed_oauth_transaction_transient_key(
			$transaction_id
		);

		$stored = get_transient( $transient_key );

		delete_transient( $transient_key );

		if ( ! is_array( $stored ) ) {
			return false;
		}

		$expected_keys = array(
			'user_id',
			'site_instance_id',
			'created_at',
			'expires_at',
		);

		$actual_keys = array_keys( $stored );

		sort( $expected_keys );
		sort( $actual_keys );

		if ( $expected_keys !== $actual_keys ) {
			return false;
		}

		if (
			! is_int( $stored['user_id'] ) ||
			$user_id !== $stored['user_id'] ||
			! analytics_report_ai_is_managed_oauth_identifier(
				$stored['site_instance_id']
			) ||
			! is_int( $stored['created_at'] ) ||
			! is_int( $stored['expires_at'] ) ||
			$stored['expires_at'] <= $stored['created_at'] ||
			$stored['expires_at'] - $stored['created_at'] > 600 ||
			$stored['expires_at'] < time()
		) {
			return false;
		}

		$current_site_instance_id =
			analytics_report_ai_get_managed_oauth_site_instance_id();

		if (
			'' === $current_site_instance_id ||
			! hash_equals(
				$current_site_instance_id,
				$stored['site_instance_id']
			)
		) {
			return false;
		}

		$transaction_key =
			analytics_report_ai_derive_managed_oauth_transaction_key(
				$transaction_id,
				$stored['site_instance_id']
			);

		if ( '' === $transaction_key ) {
			return false;
		}

		return array(
			'transaction_id'   => $transaction_id,
			'site_instance_id' => $stored['site_instance_id'],
			'transaction_key'  => $transaction_key,
			'created_at'       => $stored['created_at'],
			'expires_at'       => $stored['expires_at'],
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_delete_managed_oauth_transaction' ) ) {
	/**
	 * Delete a managed OAuth transaction without reading its contents.
	 *
	 * @param string $transaction_id Transaction identifier.
	 * @return bool
	 */
	function analytics_report_ai_delete_managed_oauth_transaction( $transaction_id ) {
		$transient_key =
			analytics_report_ai_get_managed_oauth_transaction_transient_key(
				$transaction_id
			);

		if ( '' === $transient_key ) {
			return false;
		}

		return delete_transient( $transient_key );
	}
}

if ( ! function_exists( 'analytics_report_ai_get_managed_oauth_start_endpoint' ) ) {
	/**
	 * Get the managed OAuth Worker start endpoint.
	 *
	 * Development environments may override the endpoint through
	 * ANALYTICS_REPORT_AI_MANAGED_OAUTH_START_ENDPOINT.
	 *
	 * @return string
	 */
	function analytics_report_ai_get_managed_oauth_start_endpoint() {
		if ( defined( 'ANALYTICS_REPORT_AI_MANAGED_OAUTH_START_ENDPOINT' ) ) {
			$value = constant(
				'ANALYTICS_REPORT_AI_MANAGED_OAUTH_START_ENDPOINT'
			);

			if ( ! is_scalar( $value ) ) {
				return '';
			}

			return trim( (string) $value );
		}

		return 'https://oauth.s317.jp/v1/oauth/start';
	}
}

if ( ! function_exists( 'analytics_report_ai_is_managed_oauth_enabled' ) ) {
	/**
	 * Check whether managed OAuth is enabled for this environment.
	 *
	 * An explicit environment override takes priority. Otherwise, sites with
	 * existing legacy OAuth configuration or token storage remain in legacy
	 * mode, while new and unconfigured sites default to managed OAuth.
	 *
	 * @return bool
	 */
	function analytics_report_ai_is_managed_oauth_enabled() {
		if ( defined( 'ANALYTICS_REPORT_AI_MANAGED_OAUTH_ENABLED' ) ) {
			return true === constant( 'ANALYTICS_REPORT_AI_MANAGED_OAUTH_ENABLED' );
		}

		$client_configuration = analytics_report_ai_resolve_google_oauth_client_configuration();

		if (
			'missing' !== $client_configuration['constants_status'] ||
			'missing' !== $client_configuration['settings_status'] ||
			analytics_report_ai_google_oauth_token_storage_exists()
		) {
			return false;
		}

		return true;
	}
}

if ( ! function_exists( 'analytics_report_ai_derive_managed_oauth_store_key' ) ) {
	/**
	 * Derive the site-specific managed OAuth token storage key.
	 *
	 * K_store is separated from the site master and transaction keys by its
	 * dedicated HKDF context. The returned key is request-local only.
	 *
	 * @param string $site_instance_id Site instance identifier.
	 * @return string|false Raw 32-byte key, or false.
	 */
	function analytics_report_ai_derive_managed_oauth_store_key( $site_instance_id ) {
		if (
			! analytics_report_ai_is_managed_oauth_identifier(
				$site_instance_id
			) ||
			! function_exists( 'hash_hkdf' )
		) {
			return false;
		}

		$site_master = analytics_report_ai_derive_managed_oauth_site_master(
			$site_instance_id
		);
		$salt        = hex2bin( $site_instance_id );

		if ( false === $site_master || false === $salt ) {
			return false;
		}

		try {
			$key = hash_hkdf(
				'sha256',
				$site_master,
				32,
				'studio317-report-drafts-google-analytics-oauth:store-key:v1',
				$salt
			);
		} catch ( Throwable $throwable ) {
			unset( $throwable, $site_master, $salt );

			return false;
		}

		unset( $site_master, $salt );

		if ( ! is_string( $key ) || 32 !== strlen( $key ) ) {
			return false;
		}

		return $key;
	}
}

if ( ! function_exists( 'analytics_report_ai_managed_oauth_token_storage_exists' ) ) {
	/**
	 * Check whether managed OAuth encrypted token storage exists.
	 *
	 * Token material is not decrypted or exposed by this helper.
	 *
	 * @return bool
	 */
	function analytics_report_ai_managed_oauth_token_storage_exists() {
		return false !== get_option(
			ANALYTICS_REPORT_AI_MANAGED_OAUTH_TOKEN_OPTION_NAME,
			false
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_store_managed_oauth_token_payload' ) ) {
	/**
	 * Encrypt and store managed OAuth token material.
	 *
	 * Only the m1 encrypted envelope is persisted. K_store and plaintext token
	 * material remain request-local.
	 *
	 * @param array $payload Managed OAuth token payload.
	 * @return bool
	 */
	function analytics_report_ai_store_managed_oauth_token_payload( $payload ) {
		if (
			! analytics_report_ai_validate_managed_oauth_token_payload(
				$payload
			)
		) {
			return false;
		}

		$site_instance_id =
			analytics_report_ai_get_managed_oauth_site_instance_id();

		if (
			! analytics_report_ai_is_managed_oauth_identifier(
				$site_instance_id
			)
		) {
			return false;
		}

		$store_key =
			analytics_report_ai_derive_managed_oauth_store_key(
				$site_instance_id
			);

		if ( false === $store_key ) {
			return false;
		}

		$envelope =
			analytics_report_ai_encrypt_managed_oauth_token_payload(
				$payload,
				$store_key
			);

		unset( $store_key, $site_instance_id );

		if ( '' === $envelope ) {
			return false;
		}

		$current = get_option(
			ANALYTICS_REPORT_AI_MANAGED_OAUTH_TOKEN_OPTION_NAME,
			false
		);

		if ( false === $current ) {
			$stored = add_option(
				ANALYTICS_REPORT_AI_MANAGED_OAUTH_TOKEN_OPTION_NAME,
				$envelope,
				'',
				false
			);

			unset( $envelope, $current );

			return $stored;
		}

		$updated = update_option(
			ANALYTICS_REPORT_AI_MANAGED_OAUTH_TOKEN_OPTION_NAME,
			$envelope,
			false
		);

		if ( $updated ) {
			unset( $envelope, $current );

			return true;
		}

		$stored_envelope = get_option(
			ANALYTICS_REPORT_AI_MANAGED_OAUTH_TOKEN_OPTION_NAME,
			false
		);

		$matches = is_string( $stored_envelope ) &&
			hash_equals( $envelope, $stored_envelope );

		unset(
			$envelope,
			$current,
			$stored_envelope
		);

		return $matches;
	}
}

if ( ! function_exists( 'analytics_report_ai_get_managed_oauth_token_payload' ) ) {
	/**
	 * Read and decrypt managed OAuth token material for request-local use.
	 *
	 * @return array|false Valid decrypted token payload, or false.
	 */
	function analytics_report_ai_get_managed_oauth_token_payload() {
		$envelope = get_option(
			ANALYTICS_REPORT_AI_MANAGED_OAUTH_TOKEN_OPTION_NAME,
			false
		);

		if (
			! is_string( $envelope ) ||
			'' === $envelope ||
			strlen( $envelope ) > 147456 ||
			1 !== preg_match(
				'/^m1\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$/',
				$envelope
			)
		) {
			return false;
		}

		$site_instance_id =
			analytics_report_ai_get_managed_oauth_site_instance_id();

		if (
			! analytics_report_ai_is_managed_oauth_identifier(
				$site_instance_id
			)
		) {
			unset( $envelope );

			return false;
		}

		$store_key =
			analytics_report_ai_derive_managed_oauth_store_key(
				$site_instance_id
			);

		unset( $site_instance_id );

		if ( false === $store_key ) {
			unset( $envelope );

			return false;
		}

		$payload =
			analytics_report_ai_decrypt_managed_oauth_token_payload(
				$envelope,
				$store_key
			);

		unset( $envelope, $store_key );

		return $payload;
	}
}

if ( ! function_exists( 'analytics_report_ai_delete_managed_oauth_tokens' ) ) {
	/**
	 * Delete only locally stored managed OAuth token ciphertext.
	 *
	 * This does not contact or revoke access at Google.
	 *
	 * @return bool
	 */
	function analytics_report_ai_delete_managed_oauth_tokens() {
		if (
			! analytics_report_ai_managed_oauth_token_storage_exists()
		) {
			return true;
		}

		return delete_option(
			ANALYTICS_REPORT_AI_MANAGED_OAUTH_TOKEN_OPTION_NAME
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_derive_managed_oauth_refresh_key' ) ) {
	/**
	 * Derive the site-specific managed OAuth refresh authentication key.
	 *
	 * K_refresh is separated from K_store and transaction keys by a dedicated
	 * HKDF context. The key is derived when needed and is never persisted.
	 *
	 * @param string $site_instance_id Site instance identifier.
	 * @return string Base64URL-encoded 32-byte K_refresh, or empty string.
	 */
	function analytics_report_ai_derive_managed_oauth_refresh_key( $site_instance_id ) {
		if (
			! analytics_report_ai_is_managed_oauth_identifier(
				$site_instance_id
			) ||
			! function_exists( 'hash_hkdf' )
		) {
			return '';
		}

		$site_master =
			analytics_report_ai_derive_managed_oauth_site_master(
				$site_instance_id
			);
		$salt        = hex2bin( $site_instance_id );

		if ( false === $site_master || false === $salt ) {
			return '';
		}

		try {
			$key = hash_hkdf(
				'sha256',
				$site_master,
				32,
				'studio317-report-drafts-google-analytics-oauth:refresh-key:v1',
				$salt
			);
		} catch ( Throwable $throwable ) {
			unset( $throwable, $site_master, $salt );

			return '';
		}

		unset( $site_master, $salt );

		if ( ! is_string( $key ) || 32 !== strlen( $key ) ) {
			return '';
		}

		return analytics_report_ai_base64url_encode( $key );
	}
}

if ( ! function_exists( 'analytics_report_ai_build_managed_oauth_refresh_canonical_request' ) ) {
	/**
	 * Build the canonical managed OAuth refresh request string.
	 *
	 * @param string $site_instance_id  Site instance identifier.
	 * @param int    $issued_at         Request issue time.
	 * @param string $refresh_capability Worker-issued refresh capability.
	 * @return string Empty string when invalid.
	 */
	function analytics_report_ai_build_managed_oauth_refresh_canonical_request(
		$site_instance_id,
		$issued_at,
		$refresh_capability
	) {
		if (
			! analytics_report_ai_is_managed_oauth_identifier(
				$site_instance_id
			) ||
			! is_int( $issued_at ) ||
			$issued_at <= 0 ||
			! is_string( $refresh_capability ) ||
			'' === $refresh_capability ||
			strlen( $refresh_capability ) > 49152 ||
			1 !== preg_match(
				'/^c1\.[A-Za-z0-9_-]{1,32}\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$/',
				$refresh_capability
			)
		) {
			return '';
		}

		return implode(
			"\n",
			array(
				'studio317-report-drafts-google-analytics-oauth:refresh-request:v1',
				'POST',
				'/v1/oauth/refresh',
				$site_instance_id,
				(string) $issued_at,
				$refresh_capability,
			)
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_create_managed_oauth_refresh_request_payload' ) ) {
	/**
	 * Create an authenticated managed OAuth refresh request payload.
	 *
	 * K_refresh is derived request-locally and is never included in the
	 * returned payload.
	 *
	 * @param string   $site_instance_id  Site instance identifier.
	 * @param string   $refresh_capability Worker-issued refresh capability.
	 * @param int|null $issued_at         Optional request issue time.
	 * @return array|false
	 */
	function analytics_report_ai_create_managed_oauth_refresh_request_payload(
		$site_instance_id,
		$refresh_capability,
		$issued_at = null
	) {
		if ( null === $issued_at ) {
			$issued_at = time();
		}

		$canonical_request =
			analytics_report_ai_build_managed_oauth_refresh_canonical_request(
				$site_instance_id,
				$issued_at,
				$refresh_capability
			);

		if ( '' === $canonical_request ) {
			return false;
		}

		$refresh_key =
			analytics_report_ai_derive_managed_oauth_refresh_key(
				$site_instance_id
			);

		if (
			! is_string( $refresh_key ) ||
			43 !== strlen( $refresh_key )
		) {
			unset( $canonical_request, $refresh_key );

			return false;
		}

		$refresh_key_raw =
			analytics_report_ai_base64url_decode_canonical(
				$refresh_key
			);

		unset( $refresh_key );

		if (
			false === $refresh_key_raw ||
			32 !== strlen( $refresh_key_raw )
		) {
			unset(
				$canonical_request,
				$refresh_key_raw
			);

			return false;
		}

		$signature =
			analytics_report_ai_base64url_encode(
				hash_hmac(
					'sha256',
					$canonical_request,
					$refresh_key_raw,
					true
				)
			);

		unset(
			$canonical_request,
			$refresh_key_raw
		);

		if (
			43 !== strlen( $signature ) ||
			1 !== preg_match(
				'/^[A-Za-z0-9_-]{43}$/',
				$signature
			)
		) {
			unset( $signature );

			return false;
		}

		return array(
			'protocol_version'   => '1',
			'site_instance_id'   => $site_instance_id,
			'refresh_capability' => $refresh_capability,
			'issued_at'          => $issued_at,
			'signature'          => $signature,
		);
	}
}

if ( ! function_exists( 'analytics_report_ai_get_managed_oauth_refresh_endpoint' ) ) {
	/**
	 * Get the managed OAuth refresh endpoint from the configured start endpoint.
	 *
	 * @return string Empty string on failure.
	 */
	function analytics_report_ai_get_managed_oauth_refresh_endpoint() {
		$start_endpoint =
			analytics_report_ai_get_managed_oauth_start_endpoint();

		$parts = wp_parse_url( $start_endpoint );

		if (
			! is_array( $parts ) ||
			! isset(
				$parts['scheme'],
				$parts['host'],
				$parts['path']
			) ||
			'https' !== strtolower( $parts['scheme'] ) ||
			'' === $parts['host'] ||
			'/v1/oauth/start' !== $parts['path'] ||
			! empty( $parts['user'] ) ||
			! empty( $parts['pass'] ) ||
			! empty( $parts['query'] ) ||
			! empty( $parts['fragment'] )
		) {
			return '';
		}

		$endpoint = 'https://' . $parts['host'];

		if ( isset( $parts['port'] ) ) {
			$port = (int) $parts['port'];

			if ( $port < 1 || $port > 65535 ) {
				return '';
			}

			$endpoint .= ':' . $port;
		}

		return $endpoint . '/v1/oauth/refresh';
	}
}

if ( ! function_exists( 'analytics_report_ai_request_managed_oauth_refresh' ) ) {
	/**
	 * Refresh managed OAuth access credentials through the Worker.
	 *
	 * Temporary Worker or Google failures preserve the existing encrypted
	 * credential material. A confirmed Google invalid_grant removes the local
	 * managed OAuth credential because reconnect is required.
	 *
	 * @param array|false|null $tokens Optional request-local decrypted token payload.
	 * @return string Safe refresh status category.
	 */
	function analytics_report_ai_request_managed_oauth_refresh( $tokens = null ) {
		if ( null === $tokens ) {
			$tokens =
				analytics_report_ai_get_managed_oauth_token_payload();
		}

		if (
			! analytics_report_ai_validate_managed_oauth_token_payload(
				$tokens
			)
		) {
			return 'managed_oauth_refresh_invalid_local_token';
		}

		$site_instance_id =
			analytics_report_ai_get_managed_oauth_site_instance_id();

		if (
			! analytics_report_ai_is_managed_oauth_identifier(
				$site_instance_id
			)
		) {
			return 'managed_oauth_refresh_unavailable';
		}

		$endpoint =
			analytics_report_ai_get_managed_oauth_refresh_endpoint();

		if ( '' === $endpoint ) {
			return 'managed_oauth_refresh_unavailable';
		}

		$request_payload =
			analytics_report_ai_create_managed_oauth_refresh_request_payload(
				$site_instance_id,
				$tokens['refresh_capability']
			);

		if ( ! is_array( $request_payload ) ) {
			return 'managed_oauth_refresh_unavailable';
		}

		$refresh_key =
			analytics_report_ai_derive_managed_oauth_refresh_key(
				$site_instance_id
			);

		if (
			! is_string( $refresh_key ) ||
			43 !== strlen( $refresh_key )
		) {
			unset( $request_payload, $refresh_key );

			return 'managed_oauth_refresh_unavailable';
		}

		$body = wp_json_encode( $request_payload );

		unset( $request_payload );

		if ( ! is_string( $body ) || '' === $body ) {
			unset( $refresh_key );

			return 'managed_oauth_refresh_invalid_response';
		}

		$response = wp_safe_remote_post(
			$endpoint,
			array(
				'timeout'             => 20,
				'redirection'         => 0,
				'limit_response_size' => 131072,
				'headers'             => array(
					'Accept'       => 'application/json',
					'Content-Type' => 'application/json',
				),
				'body'                => $body,
			)
		);

		unset( $body );

		if ( is_wp_error( $response ) ) {
			unset( $response, $refresh_key );

			return 'managed_oauth_refresh_network_failed';
		}

		$status_code =
			(int) wp_remote_retrieve_response_code( $response );

		$response_body =
			wp_remote_retrieve_body( $response );

		unset( $response );

		if ( 200 !== $status_code ) {
			$worker_error_code = '';

			$error_payload = json_decode(
				$response_body,
				true,
				8
			);

			if (
				JSON_ERROR_NONE === json_last_error() &&
				is_array( $error_payload ) &&
				isset( $error_payload['error'] ) &&
				is_array( $error_payload['error'] ) &&
				isset( $error_payload['error']['code'] ) &&
				is_string(
					$error_payload['error']['code']
				)
			) {
				$worker_error_code =
					$error_payload['error']['code'];
			}

			unset(
				$error_payload,
				$response_body,
				$refresh_key
			);

			switch ( $worker_error_code ) {
				case 'google_token_invalid_grant':
					return analytics_report_ai_delete_managed_oauth_tokens()
						? 'managed_oauth_refresh_invalid_grant'
						: 'managed_oauth_refresh_invalid_grant_cleanup_failed';

				case 'refresh_capability_expired':
					return 'managed_oauth_refresh_reconnect_required';

				case 'refresh_service_unavailable':
				case 'google_token_service_unavailable':
					return 'managed_oauth_refresh_unavailable';

				case 'google_token_network_error':
					return 'managed_oauth_refresh_google_network_error';

				case 'google_token_scope_mismatch':
					return 'managed_oauth_refresh_scope_mismatch';

				case 'google_token_missing_token':
					return 'managed_oauth_refresh_missing_token';

				case 'google_token_malformed_response':
					return 'managed_oauth_refresh_malformed_response';

				case 'google_token_provider_error':
					return 'managed_oauth_refresh_provider_error';

				case 'invalid_refresh_request_authentication':
					return 'managed_oauth_refresh_rejected';

				default:
					return 'managed_oauth_refresh_invalid_response';
			}
		}

		$response_payload = json_decode(
			$response_body,
			true,
			8
		);

		unset( $response_body );

		if (
			JSON_ERROR_NONE !== json_last_error() ||
			! is_array( $response_payload )
		) {
			unset( $refresh_key );

			return 'managed_oauth_refresh_invalid_response';
		}

		$expected_keys = array(
			'protocol_version',
			'refresh_response',
			'result',
		);
		$actual_keys   = array_keys( $response_payload );

		sort( $expected_keys );
		sort( $actual_keys );

		if (
			$expected_keys !== $actual_keys ||
			'1' !== $response_payload['protocol_version'] ||
			'success' !== $response_payload['result'] ||
			! is_string(
				$response_payload['refresh_response']
			) ||
			strlen(
				$response_payload['refresh_response']
			) > 98304 ||
			1 !== preg_match(
				'/^rr1\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$/',
				$response_payload['refresh_response']
			)
		) {
			unset(
				$response_payload,
				$refresh_key
			);

			return 'managed_oauth_refresh_invalid_response';
		}

		$refresh_payload =
			analytics_report_ai_decrypt_oauth_refresh_response(
				$response_payload['refresh_response'],
				$refresh_key
			);

		unset(
			$response_payload,
			$refresh_key
		);

		if ( ! is_array( $refresh_payload ) ) {
			return 'managed_oauth_refresh_invalid_response';
		}

		$expected_fingerprint =
			analytics_report_ai_base64url_encode(
				hash(
					'sha256',
					$tokens['refresh_capability'],
					true
				)
			);

		$now = time();

		if (
			! hash_equals(
				$site_instance_id,
				$refresh_payload['site_instance_id']
			) ||
			! hash_equals(
				$expected_fingerprint,
				$refresh_payload['refresh_capability_fingerprint']
			) ||
			$refresh_payload['issued_at'] > $now + 300 ||
			$refresh_payload['expires_at'] < $now ||
			$refresh_payload['expires_in'] >
				PHP_INT_MAX - $now
		) {
			unset(
				$refresh_payload,
				$expected_fingerprint
			);

			return 'managed_oauth_refresh_invalid_response';
		}

		$updated_tokens = array(
			'access_token'             =>
				$refresh_payload['access_token'],
			'refresh_token'            =>
				$tokens['refresh_token'],
			'refresh_capability'       =>
				$refresh_payload['refresh_capability'],
			'expires_at'               =>
				$now + $refresh_payload['expires_in'],
			'refresh_token_expires_at' =>
				$tokens['refresh_token_expires_at'],
			'scope'                    =>
				$refresh_payload['scope'],
			'token_type'               =>
				$refresh_payload['token_type'],
			'created_at'               =>
				$tokens['created_at'],
			'updated_at'               => $now,
		);

		unset(
			$refresh_payload,
			$expected_fingerprint
		);

		$stored =
			analytics_report_ai_store_managed_oauth_token_payload(
				$updated_tokens
			);

		unset(
			$updated_tokens,
			$tokens,
			$site_instance_id
		);

		return $stored
			? 'managed_oauth_refresh_token_stored'
			: 'managed_oauth_refresh_storage_failed';
	}
}
