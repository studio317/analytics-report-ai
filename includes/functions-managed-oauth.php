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
	 * Check whether managed OAuth is explicitly enabled for this environment.
	 *
	 * Managed OAuth remains disabled unless the environment opts in.
	 *
	 * @return bool
	 */
	function analytics_report_ai_is_managed_oauth_enabled() {
		return defined( 'ANALYTICS_REPORT_AI_MANAGED_OAUTH_ENABLED' )
			&& true === constant( 'ANALYTICS_REPORT_AI_MANAGED_OAUTH_ENABLED' );
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
