<?php
/**
 * Admin screen controller.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers admin menus, screens, and assets for Studio317 Report Drafts for Google Analytics.
 *
 * @since 0.1.0
 */
final class Analytics_Report_AI_Admin {

	/**
	 * Temporary OAuth state lifetime in seconds.
	 *
	 * @var int
	 */
	private const GOOGLE_OAUTH_STATE_TTL = 600;

	/**
	 * Google OAuth authorization endpoint.
	 *
	 * @var string
	 */
	private const GOOGLE_OAUTH_AUTHORIZATION_ENDPOINT = 'https://accounts.google.com/o/oauth2/v2/auth';

	/**
	 * Google OAuth token endpoint.
	 *
	 * @var string
	 */
	private const GOOGLE_OAUTH_TOKEN_ENDPOINT = 'https://oauth2.googleapis.com/token';

	/**
	 * Google Analytics read-only OAuth scope.
	 *
	 * @var string
	 */
	private const GOOGLE_OAUTH_ANALYTICS_READONLY_SCOPE = 'https://www.googleapis.com/auth/analytics.readonly';

	/**
	 * Settings screen instance.
	 *
	 * @var Analytics_Report_AI_Settings
	 */
	private $settings;

	/**
	 * Report builder screen instance.
	 *
	 * @var Analytics_Report_AI_Report_Builder
	 */
	private $report_builder;

	/**
	 * Current status screen instance.
	 *
	 * @var Analytics_Report_AI_Status_Page
	 */
	private $status_page;

	/**
	 * Report Builder top-level hook suffix.
	 *
	 * @var string
	 */
	private $report_builder_hook_suffix = '';

	/**
	 * Report Builder submenu hook suffix.
	 *
	 * @var string
	 */
	private $report_builder_submenu_hook_suffix = '';

	/**
	 * Current Status submenu hook suffix.
	 *
	 * @var string
	 */
	private $current_status_hook_suffix = '';

	/**
	 * Settings submenu hook suffix.
	 *
	 * @var string
	 */
	private $settings_hook_suffix = '';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->settings       = new Analytics_Report_AI_Settings();
		$this->report_builder = new Analytics_Report_AI_Report_Builder();
		$this->status_page    = new Analytics_Report_AI_Status_Page();

		add_action( 'admin_menu', array( $this, 'register_menus' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_post_analytics_report_ai_google_oauth_connect', array( $this, 'handle_google_oauth_connect' ) );
		add_action( 'admin_post_analytics_report_ai_google_oauth_callback', array( $this, 'handle_google_oauth_callback' ) );
		add_action( 'admin_post_analytics_report_ai_google_oauth_disconnect', array( $this, 'handle_google_oauth_disconnect' ) );
	}

	/**
	 * Register admin menus.
	 *
	 * @return void
	 */
	public function register_menus() {
		$this->report_builder_hook_suffix = add_menu_page(
			__( 'Studio317 Report Drafts for Google Analytics', 'studio317-report-drafts-google-analytics' ),
			__( 'Studio317 Report Drafts for Google Analytics', 'studio317-report-drafts-google-analytics' ),
			'manage_options',
			'studio317-report-drafts-google-analytics',
			array( $this->report_builder, 'render_page' ),
			'dashicons-chart-area',
			65
		);

		$this->report_builder_submenu_hook_suffix = add_submenu_page(
			'studio317-report-drafts-google-analytics',
			__( 'Report Builder', 'studio317-report-drafts-google-analytics' ),
			__( 'Report Builder', 'studio317-report-drafts-google-analytics' ),
			'manage_options',
			'studio317-report-drafts-google-analytics',
			array( $this->report_builder, 'render_page' )
		);

		$this->current_status_hook_suffix = add_submenu_page(
			'studio317-report-drafts-google-analytics',
			__( 'Current Status', 'studio317-report-drafts-google-analytics' ),
			__( 'Current Status', 'studio317-report-drafts-google-analytics' ),
			'manage_options',
			'studio317-report-drafts-google-analytics-status',
			array( $this->status_page, 'render_page' )
		);

		$this->settings_hook_suffix = add_submenu_page(
			'studio317-report-drafts-google-analytics',
			__( 'Settings', 'studio317-report-drafts-google-analytics' ),
			__( 'Settings', 'studio317-report-drafts-google-analytics' ),
			'manage_options',
			'studio317-report-drafts-google-analytics-settings',
			array( $this->settings, 'render_page' )
		);
	}

	/**
	 * Enqueue admin assets only on plugin screens.
	 *
	 * @param string $hook_suffix Current admin screen hook suffix.
	 * @return void
	 */
	public function enqueue_assets( $hook_suffix = '' ) {
		$screen = get_current_screen();

		$plugin_hook_suffixes = array_filter(
			array(
				$this->report_builder_hook_suffix,
				$this->report_builder_submenu_hook_suffix,
				$this->current_status_hook_suffix,
				$this->settings_hook_suffix,
			)
		);
		$is_plugin_screen     = $screen && false !== strpos( $screen->id, 'studio317-report-drafts-google-analytics' );

		if ( ! $is_plugin_screen && ! in_array( $hook_suffix, $plugin_hook_suffixes, true ) ) {
			return;
		}

		wp_enqueue_style(
			'studio317-report-drafts-google-analytics-admin',
			ANALYTICS_REPORT_AI_URL . 'assets/css/admin.css',
			array(),
			ANALYTICS_REPORT_AI_VERSION
		);

		wp_enqueue_script(
			'studio317-report-drafts-google-analytics-admin',
			ANALYTICS_REPORT_AI_URL . 'assets/js/admin.js',
			array(),
			ANALYTICS_REPORT_AI_VERSION,
			true
		);

		wp_localize_script(
			'studio317-report-drafts-google-analytics-admin',
			'analyticsReportAiAdmin',
			array(
				'strings' => array(
					'directoryScopeDescription' => __( 'Directory scope matches paths that start with the entered path, such as /blog/.', 'studio317-report-drafts-google-analytics' ),
					'pageScopeDescription'      => __( 'Page scope matches the exact normalized path, such as /about.', 'studio317-report-drafts-google-analytics' ),
					'copied'                    => __( 'Copied.', 'studio317-report-drafts-google-analytics' ),
					'copyFailed'                => __( 'Copy failed. Please select and copy manually.', 'studio317-report-drafts-google-analytics' ),
					'nothingToCopy'             => __( 'Nothing to copy.', 'studio317-report-drafts-google-analytics' ),
				),
			)
		);

		$help_dialog_hook_suffixes = array_filter(
			array(
				$this->report_builder_hook_suffix,
				$this->report_builder_submenu_hook_suffix,
				$this->settings_hook_suffix,
			)
		);

		if ( ! in_array( $hook_suffix, $help_dialog_hook_suffixes, true ) ) {
			return;
		}

		$help_dialog_css_path     = ANALYTICS_REPORT_AI_DIR . 'assets/css/help-dialog.css';
		$help_dialog_css_version  = file_exists( $help_dialog_css_path ) ? (string) filemtime( $help_dialog_css_path ) : ANALYTICS_REPORT_AI_VERSION;
		$help_dialog_script_path  = ANALYTICS_REPORT_AI_DIR . 'assets/js/help-dialog.js';
		$help_dialog_script_version = file_exists( $help_dialog_script_path ) ? (string) filemtime( $help_dialog_script_path ) : ANALYTICS_REPORT_AI_VERSION;

		wp_enqueue_style(
			'studio317-report-drafts-google-analytics-help-dialog',
			ANALYTICS_REPORT_AI_URL . 'assets/css/help-dialog.css',
			array( 'studio317-report-drafts-google-analytics-admin' ),
			$help_dialog_css_version
		);

		wp_enqueue_script(
			'studio317-report-drafts-google-analytics-help-dialog',
			ANALYTICS_REPORT_AI_URL . 'assets/js/help-dialog.js',
			array(),
			$help_dialog_script_version,
			true
		);
	}

	/**
	 * Handle the Google OAuth connect action.
	 *
	 * This action redirects to Google authorization only after local capability,
	 * nonce, client ID, and state boundaries pass. It intentionally does not run
	 * token exchange, token storage, refresh, or revoke during the connect step.
	 *
	 * @return void
	 */
	public function handle_google_oauth_connect() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(
				esc_html__( 'You do not have permission to manage Studio317 Report Drafts for Google Analytics credentials.', 'studio317-report-drafts-google-analytics' ),
				esc_html__( 'Permission denied', 'studio317-report-drafts-google-analytics' ),
				array( 'response' => 403 )
			);
		}

		check_admin_referer( 'analytics_report_ai_google_oauth_connect', 'analytics_report_ai_google_oauth_nonce' );

		$client_configuration = analytics_report_ai_resolve_google_oauth_client_configuration();

		if ( empty( $client_configuration['can_start_oauth'] ) ) {
			wp_safe_redirect(
				$this->get_settings_url(
					array(
						'analytics_report_ai_google_oauth_status' => 'google_oauth_redirect_client_config_unavailable',
					)
				)
			);
			exit;
		}

		$state             = $this->create_google_oauth_state_placeholder();
		$authorization_url = $this->build_google_oauth_authorization_url( $state, $client_configuration );

		unset( $client_configuration );

		if ( '' === $authorization_url ) {
			wp_safe_redirect(
				$this->get_settings_url(
					array(
						'analytics_report_ai_google_oauth_status' => 'google_oauth_redirect_url_unavailable',
					)
				)
			);
			exit;
		}

		$this->redirect_to_google_oauth_authorization_url( $authorization_url );
	}

	/**
	 * Redirect to the Google OAuth authorization URL.
	 *
	 * The URL is not displayed, logged, or stored. Only the Google authorization
	 * host is temporarily allowed for this redirect boundary.
	 *
	 * @param string $authorization_url Authorization URL.
	 * @return void
	 */
	private function redirect_to_google_oauth_authorization_url( $authorization_url ) {
		add_filter( 'allowed_redirect_hosts', array( $this, 'allow_google_oauth_redirect_host' ) );

		$redirected = wp_safe_redirect( $authorization_url );

		remove_filter( 'allowed_redirect_hosts', array( $this, 'allow_google_oauth_redirect_host' ) );

		if ( $redirected ) {
			exit;
		}

		wp_safe_redirect(
			$this->get_settings_url(
				array(
					'analytics_report_ai_google_oauth_status' => 'google_oauth_redirect_url_unavailable',
				)
			)
		);
		exit;
	}

	/**
	 * Allow the Google OAuth authorization host for the redirect boundary.
	 *
	 * @param string[] $hosts Allowed redirect hosts.
	 * @return string[]
	 */
	public function allow_google_oauth_redirect_host( $hosts ) {
		$hosts[] = 'accounts.google.com';

		return array_values( array_unique( $hosts ) );
	}

	/**
	 * Handle the local Google OAuth callback skeleton.
	 *
	 * This callback validates only the temporary state placeholder and classifies
	 * query value presence without displaying or storing raw OAuth values.
	 *
	 * @return void
	 */
	public function handle_google_oauth_callback() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(
				esc_html__( 'You do not have permission to manage Studio317 Report Drafts for Google Analytics credentials.', 'studio317-report-drafts-google-analytics' ),
				esc_html__( 'Permission denied', 'studio317-report-drafts-google-analytics' ),
				array( 'response' => 403 )
			);
		}

		$callback_result = $this->classify_google_oauth_callback();
		$callback_status = isset( $callback_result['status'] ) && is_string( $callback_result['status'] )
			? $callback_result['status']
			: 'callback_state_invalid';

		if ( 'callback_state_valid_code_present' === $callback_status ) {
			$authorization_code = isset( $callback_result['authorization_code'] ) && is_string( $callback_result['authorization_code'] )
				? $callback_result['authorization_code']
				: '';

			$callback_status = $this->exchange_google_oauth_authorization_code_for_tokens( $authorization_code );

			unset( $authorization_code );
		}

		unset( $callback_result );

		wp_safe_redirect(
			$this->get_settings_url(
				array(
					'analytics_report_ai_google_oauth_status' => $callback_status,
				)
			)
		);
		exit;
	}

	/**
	 * Handle local Google OAuth token disconnect.
	 *
	 * This deletes only local OAuth token data. It does not contact Google,
	 * refresh tokens, revoke provider-side access, delete the manual Google
	 * Access Token fallback, or change AI provider configuration.
	 *
	 * @return void
	 */
	public function handle_google_oauth_disconnect() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(
				esc_html__( 'You do not have permission to manage Studio317 Report Drafts for Google Analytics credentials.', 'studio317-report-drafts-google-analytics' ),
				esc_html__( 'Permission denied', 'studio317-report-drafts-google-analytics' ),
				array( 'response' => 403 )
			);
		}

		check_admin_referer( 'analytics_report_ai_google_oauth_disconnect', 'analytics_report_ai_google_oauth_disconnect_nonce' );

		$deleted = analytics_report_ai_delete_google_oauth_tokens();
		$status  = $deleted ? 'google_oauth_local_disconnect_success' : 'google_oauth_local_disconnect_failed';

		wp_safe_redirect(
			$this->get_settings_url(
				array(
					'analytics_report_ai_google_oauth_status' => $status,
				)
			)
		);
		exit;
	}

	/**
	 * Build the plugin Settings screen URL.
	 *
	 * @param array $args Optional query arguments.
	 * @return string
	 */
	private function get_settings_url( $args = array() ) {
		$url = admin_url( 'admin.php?page=studio317-report-drafts-google-analytics-settings' );

		if ( empty( $args ) ) {
			return $url;
		}

		return add_query_arg( $args, $url );
	}

	/**
	 * Create a temporary user-scoped OAuth state placeholder.
	 *
	 * The raw state value is intentionally not displayed or logged. It is
	 * returned only to the current request so the authorization redirect URL can
	 * be constructed without storing OAuth tokens or credentials here.
	 *
	 * @return string Raw state value for immediate redirect construction.
	 */
	private function create_google_oauth_state_placeholder() {
		$user_id = get_current_user_id();

		if ( $user_id <= 0 ) {
			return '';
		}

		$state = $this->generate_google_oauth_state_value();

		set_transient(
			$this->get_google_oauth_state_transient_key( $user_id ),
			array(
				'state_hash' => wp_hash( $state ),
				'created_at'  => time(),
			),
			self::GOOGLE_OAUTH_STATE_TTL
		);

		return $state;
	}

	/**
	 * Generate a random OAuth state value for the local placeholder boundary.
	 *
	 * @return string
	 */
	private function generate_google_oauth_state_value() {
		try {
			return bin2hex( random_bytes( 32 ) );
		} catch ( Exception $exception ) {
			unset( $exception );

			return wp_generate_password( 64, false, false );
		}
	}

	/**
	 * Build a Google OAuth authorization URL without executing a redirect.
	 *
	 * This helper intentionally does not call Google, exchange codes, store
	 * tokens, refresh tokens, revoke access, or output the generated URL.
	 *
	 * @param string     $state                Raw OAuth state value for the future redirect request.
	 * @param array|null $client_configuration Resolved client configuration.
	 * @return string
	 */
	private function build_google_oauth_authorization_url( $state, $client_configuration = null ) {
		if ( ! is_array( $client_configuration ) ) {
			$client_configuration = analytics_report_ai_resolve_google_oauth_client_configuration();
		}

		$client_id = isset( $client_configuration['client_id'] ) && is_scalar( $client_configuration['client_id'] )
			? analytics_report_ai_sanitize_credential_value( (string) $client_configuration['client_id'] )
			: '';
		$state     = is_scalar( $state ) ? trim( (string) $state ) : '';

		if ( '' === $client_id || '' === $state ) {
			return '';
		}

		return add_query_arg(
			array(
				'client_id'     => $client_id,
				'redirect_uri'  => $this->get_google_oauth_redirect_uri(),
				'response_type' => 'code',
				'scope'         => self::GOOGLE_OAUTH_ANALYTICS_READONLY_SCOPE,
				'state'         => $state,
			),
			self::GOOGLE_OAUTH_AUTHORIZATION_ENDPOINT
		);
	}

	/**
	 * Get the Google OAuth callback redirect URI.
	 *
	 * @return string
	 */
	private function get_google_oauth_redirect_uri() {
		return add_query_arg(
			'action',
			'analytics_report_ai_google_oauth_callback',
			admin_url( 'admin-post.php' )
		);
	}

	/**
	 * Classify a callback request without exposing raw OAuth query values.
	 *
	 * @return array{status:string,authorization_code:string}
	 */
	private function classify_google_oauth_callback() {
		$user_id       = get_current_user_id();
		$state         = filter_input( INPUT_GET, 'state', FILTER_UNSAFE_RAW );
		$has_state     = is_string( $state ) && '' !== $state;
		$code          = filter_input( INPUT_GET, 'code', FILTER_UNSAFE_RAW );
		$code          = is_string( $code ) ? analytics_report_ai_sanitize_credential_value( $code ) : '';
		$has_code      = filter_has_var( INPUT_GET, 'code' );
		$has_error     = filter_has_var( INPUT_GET, 'error' );
		$transient_key = $this->get_google_oauth_state_transient_key( $user_id );
		$stored_state  = get_transient( $transient_key );

		delete_transient( $transient_key );

		if ( ! $has_state ) {
			return array(
				'status'             => 'callback_state_missing',
				'authorization_code' => '',
			);
		}

		if ( false === $stored_state ) {
			return array(
				'status'             => 'callback_state_expired',
				'authorization_code' => '',
			);
		}

		if ( ! is_array( $stored_state ) || empty( $stored_state['state_hash'] ) || ! is_string( $stored_state['state_hash'] ) ) {
			return array(
				'status'             => 'callback_state_invalid',
				'authorization_code' => '',
			);
		}

		if ( ! hash_equals( $stored_state['state_hash'], wp_hash( $state ) ) ) {
			return array(
				'status'             => 'callback_state_invalid',
				'authorization_code' => '',
			);
		}

		if ( $has_error ) {
			return array(
				'status'             => 'callback_state_valid_provider_error',
				'authorization_code' => '',
			);
		}

		if ( $has_code && '' !== $code ) {
			return array(
				'status'             => 'callback_state_valid_code_present',
				'authorization_code' => $code,
			);
		}

		return array(
			'status'             => 'callback_state_valid_no_code',
			'authorization_code' => '',
		);
	}

	/**
	 * Exchange a request-local authorization code and store resulting tokens.
	 *
	 * Raw code and token values are not returned, displayed, logged, or saved in
	 * admin notices.
	 *
	 * @param string $authorization_code Request-local authorization code.
	 * @return string Safe status category.
	 */
	private function exchange_google_oauth_authorization_code_for_tokens( $authorization_code ) {
		$authorization_code = analytics_report_ai_sanitize_credential_value( $authorization_code );

		if ( '' === $authorization_code ) {
			return 'token_exchange_not_executed';
		}

		$client_configuration = analytics_report_ai_resolve_google_oauth_client_configuration();
		$client_id            = isset( $client_configuration['client_id'] ) && is_scalar( $client_configuration['client_id'] )
			? analytics_report_ai_sanitize_credential_value( (string) $client_configuration['client_id'] )
			: '';
		$client_secret        = isset( $client_configuration['client_secret'] ) && is_scalar( $client_configuration['client_secret'] )
			? analytics_report_ai_sanitize_credential_value( (string) $client_configuration['client_secret'] )
			: '';

		if ( empty( $client_configuration['can_start_oauth'] ) || '' === $client_id || '' === $client_secret ) {
			unset( $client_configuration, $client_id, $client_secret );

			return 'token_exchange_not_executed';
		}

		$exchange_result = $this->request_google_oauth_tokens( $authorization_code, $client_id, $client_secret );

		unset( $authorization_code, $client_configuration, $client_id, $client_secret );

		$status = isset( $exchange_result['status'] ) && is_string( $exchange_result['status'] )
			? $exchange_result['status']
			: 'token_exchange_malformed_response_category';

		if ( 'token_exchange_success_category' !== $status ) {
			unset( $exchange_result );

			return $status;
		}

		$tokens = isset( $exchange_result['tokens'] ) && is_array( $exchange_result['tokens'] )
			? $exchange_result['tokens']
			: array();

		$stored = analytics_report_ai_store_google_oauth_tokens( $tokens );

		unset( $exchange_result, $tokens );

		if ( ! $stored ) {
			return 'token_storage_unavailable_category';
		}

		return 'token_exchange_success_category';
	}

	/**
	 * Request Google OAuth tokens with the WordPress HTTP API.
	 *
	 * The response body is classified in memory only and is never returned raw.
	 *
	 * @param string $authorization_code Request-local authorization code.
	 * @param string $client_id          Google OAuth client ID.
	 * @param string $client_secret      Google OAuth client secret.
	 * @return array{status:string,tokens?:array}
	 */
	private function request_google_oauth_tokens( $authorization_code, $client_id, $client_secret ) {
		$response = wp_remote_post(
			self::GOOGLE_OAUTH_TOKEN_ENDPOINT,
			array(
				'timeout'     => 15,
				'redirection' => 0,
				'headers'     => array(
					'Accept' => 'application/json',
				),
				'body'        => array(
					'code'          => $authorization_code,
					'client_id'     => $client_id,
					'client_secret' => $client_secret,
					'redirect_uri'  => $this->get_google_oauth_redirect_uri(),
					'grant_type'    => 'authorization_code',
				),
			)
		);

		unset( $authorization_code, $client_id, $client_secret );

		if ( is_wp_error( $response ) ) {
			unset( $response );

			return array(
				'status' => 'token_exchange_network_error_category',
			);
		}

		$status_code = (int) wp_remote_retrieve_response_code( $response );
		$body        = wp_remote_retrieve_body( $response );
		$data        = is_string( $body ) && '' !== $body ? json_decode( $body, true ) : null;

		unset( $body, $response );

		if ( ! is_array( $data ) ) {
			return array(
				'status' => 'token_exchange_malformed_response_category',
			);
		}

		if ( $status_code < 200 || $status_code >= 300 ) {
			$error_code = isset( $data['error'] ) && is_scalar( $data['error'] ) ? (string) $data['error'] : '';

			unset( $data );

			return array(
				'status' => 'invalid_grant' === $error_code ? 'token_exchange_invalid_grant_category' : 'token_exchange_provider_error_category',
			);
		}

		if ( empty( $data['access_token'] ) || ! is_scalar( $data['access_token'] ) ) {
			unset( $data );

			return array(
				'status' => 'token_exchange_missing_token_category',
			);
		}

		return array(
			'status' => 'token_exchange_success_category',
			'tokens' => $data,
		);
	}

	/**
	 * Get the transient key for the current user's temporary OAuth state.
	 *
	 * @param int $user_id User ID.
	 * @return string
	 */
	private function get_google_oauth_state_transient_key( $user_id ) {
		return 'analytics_report_ai_google_oauth_state_' . absint( $user_id );
	}
}
