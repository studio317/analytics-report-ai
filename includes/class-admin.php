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
		add_action( 'admin_post_analytics_report_ai_google_managed_oauth_connect', array( $this, 'handle_google_managed_oauth_connect' ) );
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

		if ( $this->settings_hook_suffix !== $hook_suffix ) {
			return;
		}

		$settings_form_css_path    = ANALYTICS_REPORT_AI_DIR . 'assets/css/settings-form.css';
		$settings_form_css_version = file_exists( $settings_form_css_path ) ? (string) filemtime( $settings_form_css_path ) : ANALYTICS_REPORT_AI_VERSION;

		wp_enqueue_style(
			'studio317-report-drafts-google-analytics-settings-form',
			ANALYTICS_REPORT_AI_URL . 'assets/css/settings-form.css',
			array( 'studio317-report-drafts-google-analytics-admin' ),
			$settings_form_css_version
		);
	}

	/**
	 * Handle the Studio317-managed Google OAuth connect action.
	 *
	 * This creates a short-lived local transaction and asks the managed OAuth
	 * Worker to construct the Google authorization redirect. It does not
	 * exchange authorization codes or store Google tokens.
	 *
	 * @return void
	 */
	public function handle_google_managed_oauth_connect() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(
				esc_html__( 'You do not have permission to manage Studio317 Report Drafts for Google Analytics credentials.', 'studio317-report-drafts-google-analytics' ),
				esc_html__( 'Permission denied', 'studio317-report-drafts-google-analytics' ),
				array( 'response' => 403 )
			);
		}

		check_admin_referer(
			'analytics_report_ai_google_managed_oauth_connect',
			'analytics_report_ai_google_managed_oauth_nonce'
		);

		$transaction = analytics_report_ai_create_managed_oauth_transaction(
			get_current_user_id()
		);

		if ( ! is_array( $transaction ) ) {
			$this->redirect_managed_oauth_start_failure(
				'managed_oauth_transaction_unavailable'
			);
		}

		$transaction_id = isset( $transaction['transaction_id'] )
			&& is_string( $transaction['transaction_id'] )
			? $transaction['transaction_id']
			: '';

		$authorization_url =
			$this->request_managed_google_oauth_authorization_url(
				$transaction
			);

		unset( $transaction );

		if ( '' === $authorization_url ) {
			analytics_report_ai_delete_managed_oauth_transaction(
				$transaction_id
			);

			$this->redirect_managed_oauth_start_failure(
				'managed_oauth_worker_start_failed'
			);
		}

		unset( $transaction_id );

		$this->redirect_to_google_oauth_authorization_url(
			$authorization_url
		);
	}

	/**
	 * Request a Google authorization URL from the managed OAuth Worker.
	 *
	 * @param array $transaction Managed OAuth transaction.
	 * @return string Empty string on failure.
	 */
	private function request_managed_google_oauth_authorization_url( $transaction ) {
		if ( ! is_array( $transaction ) ) {
			return '';
		}

		$endpoint = analytics_report_ai_get_managed_oauth_start_endpoint();

		if ( ! $this->is_valid_managed_oauth_start_endpoint( $endpoint ) ) {
			return '';
		}

		$payload = array(
			'protocol_version' => '1',
			'transaction_id'   => isset( $transaction['transaction_id'] )
				? $transaction['transaction_id']
				: '',
			'site_instance_id' => isset( $transaction['site_instance_id'] )
				? $transaction['site_instance_id']
				: '',
			'callback_url'     => $this->get_google_oauth_redirect_uri(),
			'transaction_key'  => isset( $transaction['transaction_key'] )
				? $transaction['transaction_key']
				: '',
			'issued_at'        => isset( $transaction['issued_at'] )
				? $transaction['issued_at']
				: 0,
		);

		$body = wp_json_encode( $payload );

		unset( $payload );

		if ( ! is_string( $body ) || '' === $body ) {
			return '';
		}

		$response = wp_safe_remote_post(
			$endpoint,
			array(
				'timeout'     => 15,
				'redirection' => 0,
				'headers'     => array(
					'Accept'       => 'application/json',
					'Content-Type' => 'application/json',
				),
				'body'        => $body,
			)
		);

		unset( $body );

		if ( is_wp_error( $response ) ) {
			unset( $response );

			return '';
		}

		$status_code = (int) wp_remote_retrieve_response_code( $response );
		$location    = wp_remote_retrieve_header( $response, 'location' );

		unset( $response );

		if (
			303 !== $status_code ||
			! is_string( $location ) ||
			! $this->is_valid_managed_google_oauth_authorization_url(
				$location,
				$endpoint
			)
		) {
			return '';
		}

		return $location;
	}

	/**
	 * Validate the configured managed OAuth start endpoint.
	 *
	 * @param string $endpoint Worker endpoint.
	 * @return bool
	 */
	private function is_valid_managed_oauth_start_endpoint( $endpoint ) {
		if ( ! is_string( $endpoint ) || '' === $endpoint ) {
			return false;
		}

		$parts = wp_parse_url( $endpoint );

		if ( ! is_array( $parts ) ) {
			return false;
		}

		return isset(
			$parts['scheme'],
			$parts['host'],
			$parts['path']
		)
			&& 'https' === strtolower( $parts['scheme'] )
			&& '' !== $parts['host']
			&& '/v1/oauth/start' === $parts['path']
			&& empty( $parts['user'] )
			&& empty( $parts['pass'] )
			&& empty( $parts['query'] )
			&& empty( $parts['fragment'] );
	}

	/**
	 * Validate the Google authorization URL returned by the Worker.
	 *
	 * @param string $authorization_url Authorization URL.
	 * @param string $start_endpoint    Managed OAuth start endpoint.
	 * @return bool
	 */
	private function is_valid_managed_google_oauth_authorization_url(
		$authorization_url,
		$start_endpoint
	) {
		if (
			! is_string( $authorization_url ) ||
			'' === $authorization_url ||
			strlen( $authorization_url ) > 16384
		) {
			return false;
		}

		$parts = wp_parse_url( $authorization_url );

		if (
			! is_array( $parts ) ||
			empty( $parts['query'] ) ||
			! isset( $parts['scheme'], $parts['host'], $parts['path'] ) ||
			'https' !== strtolower( $parts['scheme'] ) ||
			'accounts.google.com' !== strtolower( $parts['host'] ) ||
			'/o/oauth2/v2/auth' !== $parts['path'] ||
			! empty( $parts['user'] ) ||
			! empty( $parts['pass'] ) ||
			! empty( $parts['fragment'] )
		) {
			return false;
		}

		wp_parse_str( $parts['query'], $query );

		$expected_keys = array(
			'access_type',
			'client_id',
			'prompt',
			'redirect_uri',
			'response_type',
			'scope',
			'state',
		);

		$actual_keys = array_keys( $query );

		sort( $expected_keys );
		sort( $actual_keys );

		if ( $expected_keys !== $actual_keys ) {
			return false;
		}

		if (
			empty( $query['client_id'] ) ||
			! is_string( $query['client_id'] ) ||
			'code' !== $query['response_type'] ||
			self::GOOGLE_OAUTH_ANALYTICS_READONLY_SCOPE !== $query['scope'] ||
			'offline' !== $query['access_type'] ||
			'select_account consent' !== $query['prompt'] ||
			! is_string( $query['state'] ) ||
			1 !== preg_match(
				'/^s1\.[A-Za-z0-9_-]{1,32}\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$/',
				$query['state']
			)
		) {
			return false;
		}

		$redirect_uri = wp_parse_url( $query['redirect_uri'] );
		$worker_start = wp_parse_url( $start_endpoint );

		if (
			! is_array( $redirect_uri ) ||
			! is_array( $worker_start ) ||
			! isset(
				$redirect_uri['scheme'],
				$redirect_uri['host'],
				$redirect_uri['path'],
				$worker_start['scheme'],
				$worker_start['host']
			)
		) {
			return false;
		}

		return strtolower( $redirect_uri['scheme'] )
				=== strtolower( $worker_start['scheme'] )
			&& strtolower( $redirect_uri['host'] )
				=== strtolower( $worker_start['host'] )
			&& '/v1/google/callback' === $redirect_uri['path']
			&& empty( $redirect_uri['user'] )
			&& empty( $redirect_uri['pass'] )
			&& empty( $redirect_uri['query'] )
			&& empty( $redirect_uri['fragment'] );
	}

	/**
	 * Redirect back to Settings after managed OAuth start failure.
	 *
	 * @param string $status Safe failure category.
	 * @return void
	 */
	private function redirect_managed_oauth_start_failure( $status ) {
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

		if (
			filter_has_var( INPUT_GET, 'transaction_id' ) ||
			filter_has_var( INPUT_GET, 'handoff' )
		) {
			$callback_status = $this->classify_managed_google_oauth_callback();

			wp_safe_redirect(
				$this->get_settings_url(
					array(
						'analytics_report_ai_google_oauth_status' => $callback_status,
					)
				)
			);

			exit;
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
	 * Classify, consume, and exchange a managed Google OAuth callback handoff.
	 *
	 * OAuth codes, exchange tickets, transaction keys, and resulting token
	 * values remain request-local and are never displayed or logged here.
	 *
	 * @return string Safe status category.
	 */
	private function classify_managed_google_oauth_callback() {
		$transaction_id = filter_input(
			INPUT_GET,
			'transaction_id',
			FILTER_UNSAFE_RAW
		);
		$handoff        = filter_input(
			INPUT_GET,
			'handoff',
			FILTER_UNSAFE_RAW
		);

		if (
			! is_string( $transaction_id ) ||
			! is_string( $handoff ) ||
			'' === $transaction_id ||
			'' === $handoff
		) {
			return 'managed_oauth_callback_missing';
		}

		if (
			! analytics_report_ai_is_managed_oauth_identifier(
				$transaction_id
			) ||
			strlen( $handoff ) > 40960 ||
			1 !== preg_match(
				'/^h1\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$/',
				$handoff
			)
		) {
			return 'managed_oauth_callback_invalid';
		}

		$transaction =
			analytics_report_ai_consume_managed_oauth_transaction(
				$transaction_id,
				get_current_user_id()
			);

		if ( ! is_array( $transaction ) ) {
			return 'managed_oauth_transaction_unavailable';
		}

		$transaction_key = isset( $transaction['transaction_key'] )
			&& is_string( $transaction['transaction_key'] )
			? $transaction['transaction_key']
			: '';

		$payload = analytics_report_ai_decrypt_oauth_handoff(
			$handoff,
			$transaction_key
		);

		unset( $handoff );

		if ( ! is_array( $payload ) ) {
			unset( $transaction, $transaction_key );

			return 'managed_oauth_handoff_invalid';
		}

		if (
			! isset(
				$payload['transaction_id'],
				$payload['site_instance_id'],
				$payload['exchange_ticket'],
				$payload['issued_at'],
				$payload['expires_at']
			) ||
			! is_string( $payload['transaction_id'] ) ||
			! is_string( $payload['site_instance_id'] ) ||
			! is_string( $payload['exchange_ticket'] ) ||
			! hash_equals(
				$transaction_id,
				$payload['transaction_id']
			) ||
			! hash_equals(
				$transaction['site_instance_id'],
				$payload['site_instance_id']
			)
		) {
			unset( $payload, $transaction, $transaction_key );

			return 'managed_oauth_handoff_invalid';
		}

		$now = time();

		if (
			$payload['issued_at'] > $now + 300 ||
			$payload['expires_at'] < $now
		) {
			unset( $payload, $transaction, $transaction_key );

			return 'managed_oauth_handoff_expired';
		}

		$exchange_status =
			$this->request_managed_google_oauth_exchange(
				$transaction_id,
				$transaction['site_instance_id'],
				$payload['exchange_ticket'],
				$transaction_key
			);

		unset(
			$payload,
			$transaction,
			$transaction_key,
			$transaction_id
		);

		return $exchange_status;
	}

	/**
	 * Get the managed OAuth exchange endpoint from the configured start endpoint.
	 *
	 * @return string Empty string on failure.
	 */
	private function get_managed_oauth_exchange_endpoint() {
		$start_endpoint =
			analytics_report_ai_get_managed_oauth_start_endpoint();

		if (
			! $this->is_valid_managed_oauth_start_endpoint(
				$start_endpoint
			)
		) {
			return '';
		}

		$parts = wp_parse_url( $start_endpoint );

		if (
			! is_array( $parts ) ||
			! isset( $parts['host'] )
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

		return $endpoint . '/v1/oauth/exchange';
	}

	/**
	 * Validate a managed OAuth exchange endpoint.
	 *
	 * @param string $endpoint Worker exchange endpoint.
	 * @return bool
	 */
	private function is_valid_managed_oauth_exchange_endpoint( $endpoint ) {
		if ( ! is_string( $endpoint ) || '' === $endpoint ) {
			return false;
		}

		$parts = wp_parse_url( $endpoint );

		if ( ! is_array( $parts ) ) {
			return false;
		}

		return isset(
			$parts['scheme'],
			$parts['host'],
			$parts['path']
		)
			&& 'https' === strtolower( $parts['scheme'] )
			&& '' !== $parts['host']
			&& '/v1/oauth/exchange' === $parts['path']
			&& empty( $parts['user'] )
			&& empty( $parts['pass'] )
			&& empty( $parts['query'] )
			&& empty( $parts['fragment'] );
	}

	/**
	 * Exchange a managed OAuth ticket through the Worker.
	 *
	 * Token values returned inside the encrypted response remain request-local.
	 *
	 * @param string $transaction_id   Managed transaction identifier.
	 * @param string $site_instance_id Managed site identifier.
	 * @param string $exchange_ticket  Worker-encrypted exchange ticket.
	 * @param string $transaction_key  Base64URL transaction key.
	 * @return string Safe status category.
	 */
	private function request_managed_google_oauth_exchange(
		$transaction_id,
		$site_instance_id,
		$exchange_ticket,
		$transaction_key
	) {
		if (
			! analytics_report_ai_is_managed_oauth_identifier(
				$transaction_id
			) ||
			! analytics_report_ai_is_managed_oauth_identifier(
				$site_instance_id
			) ||
			! is_string( $exchange_ticket ) ||
			strlen( $exchange_ticket ) > 24576 ||
			1 !== preg_match(
				'/^x1\.[A-Za-z0-9_-]{1,32}\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$/',
				$exchange_ticket
			)
		) {
			return 'managed_oauth_exchange_invalid_response';
		}

		$endpoint = $this->get_managed_oauth_exchange_endpoint();

		if (
			! $this->is_valid_managed_oauth_exchange_endpoint(
				$endpoint
			)
		) {
			return 'managed_oauth_exchange_unavailable';
		}

		$transaction_key_raw =
			analytics_report_ai_decode_oauth_transaction_key(
				$transaction_key
			);

		if ( false === $transaction_key_raw ) {
			return 'managed_oauth_exchange_invalid_response';
		}

		$issued_at = time();

		$canonical_request = implode(
			"\n",
			array(
				'studio317-report-drafts-google-analytics-oauth:exchange-request:v1',
				'POST',
				'/v1/oauth/exchange',
				$transaction_id,
				$site_instance_id,
				(string) $issued_at,
				$exchange_ticket,
			)
		);

		$signature = analytics_report_ai_base64url_encode(
			hash_hmac(
				'sha256',
				$canonical_request,
				$transaction_key_raw,
				true
			)
		);

		unset(
			$transaction_key_raw,
			$canonical_request
		);

		$request_payload = array(
			'protocol_version' => '1',
			'transaction_id'   => $transaction_id,
			'site_instance_id' => $site_instance_id,
			'exchange_ticket'  => $exchange_ticket,
			'issued_at'        => $issued_at,
			'signature'        => $signature,
		);

		unset( $signature );

		$body = wp_json_encode( $request_payload );

		unset( $request_payload );

		if ( ! is_string( $body ) || '' === $body ) {
			return 'managed_oauth_exchange_invalid_response';
		}

		$response = wp_safe_remote_post(
			$endpoint,
			array(
				'timeout'             => 20,
				'redirection'         => 0,
				'limit_response_size' => 98304,
				'headers'             => array(
					'Accept'       => 'application/json',
					'Content-Type' => 'application/json',
				),
				'body'                => $body,
			)
		);

		unset( $body );

		if ( is_wp_error( $response ) ) {
			unset( $response );

			return 'managed_oauth_exchange_network_failed';
		}

		$status_code   =
			(int) wp_remote_retrieve_response_code( $response );
		$response_body =
			wp_remote_retrieve_body( $response );

		unset( $response );

		if ( 200 !== $status_code ) {
			$worker_error_code = '';
			$error_payload     = json_decode(
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
				is_string( $error_payload['error']['code'] )
			) {
				$worker_error_code =
					$error_payload['error']['code'];
			}

			unset(
				$error_payload,
				$response_body
			);

			switch ( $worker_error_code ) {
				case 'google_token_invalid_grant':
					return 'managed_oauth_exchange_invalid_grant';

				case 'google_token_service_unavailable':
					return 'managed_oauth_exchange_unavailable';

				case 'google_token_network_error':
					return 'managed_oauth_exchange_google_network_error';

				case 'google_token_scope_mismatch':
					return 'managed_oauth_exchange_scope_mismatch';

				case 'google_token_missing_token':
					return 'managed_oauth_exchange_missing_token';

				case 'google_token_malformed_response':
					return 'managed_oauth_exchange_malformed_response';

				case 'google_token_provider_error':
					return 'managed_oauth_exchange_provider_error';

				case 'invalid_exchange_request_authentication':
					return 'managed_oauth_exchange_rejected';

				default:
					return 'managed_oauth_exchange_invalid_response';
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
			return 'managed_oauth_exchange_invalid_response';
		}

		$expected_keys = array(
			'exchange_response',
			'protocol_version',
			'result',
		);
		$actual_keys   = array_keys( $response_payload );

		sort( $expected_keys );
		sort( $actual_keys );

		if (
			$expected_keys !== $actual_keys ||
			'1' !== $response_payload['protocol_version'] ||
			'success' !== $response_payload['result'] ||
			! is_string( $response_payload['exchange_response'] ) ||
			strlen( $response_payload['exchange_response'] ) > 73728 ||
			1 !== preg_match(
				'/^r1\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$/',
				$response_payload['exchange_response']
			)
		) {
			unset( $response_payload );

			return 'managed_oauth_exchange_invalid_response';
		}

		$exchange_payload =
			analytics_report_ai_decrypt_oauth_exchange_response(
				$response_payload['exchange_response'],
				$transaction_key
			);

		unset( $response_payload );

		if ( ! is_array( $exchange_payload ) ) {
			return 'managed_oauth_exchange_invalid_response';
		}

		$expected_fingerprint =
			analytics_report_ai_base64url_encode(
				hash(
					'sha256',
					$exchange_ticket,
					true
				)
			);

		$now = time();

		if (
			! isset(
				$exchange_payload['transaction_id'],
				$exchange_payload['site_instance_id'],
				$exchange_payload['exchange_ticket_fingerprint'],
				$exchange_payload['issued_at'],
				$exchange_payload['expires_at']
			) ||
			! is_string(
				$exchange_payload['transaction_id']
			) ||
			! is_string(
				$exchange_payload['site_instance_id']
			) ||
			! is_string(
				$exchange_payload['exchange_ticket_fingerprint']
			) ||
			! hash_equals(
				$transaction_id,
				$exchange_payload['transaction_id']
			) ||
			! hash_equals(
				$site_instance_id,
				$exchange_payload['site_instance_id']
			) ||
			! hash_equals(
				$expected_fingerprint,
				$exchange_payload['exchange_ticket_fingerprint']
			) ||
			$exchange_payload['issued_at'] > $now + 300 ||
			$exchange_payload['expires_at'] < $now
		) {
			unset(
				$exchange_payload,
				$expected_fingerprint
			);

			return 'managed_oauth_exchange_invalid_response';
		}

		$stored_at = time();

		if (
			! isset(
				$exchange_payload['access_token'],
				$exchange_payload['refresh_token'],
				$exchange_payload['expires_in'],
				$exchange_payload['refresh_token_expires_in'],
				$exchange_payload['scope'],
				$exchange_payload['token_type']
			) ||
			! is_string( $exchange_payload['access_token'] ) ||
			! is_string( $exchange_payload['refresh_token'] ) ||
			! is_int( $exchange_payload['expires_in'] ) ||
			$exchange_payload['expires_in'] <= 0 ||
			$exchange_payload['expires_in'] > PHP_INT_MAX - $stored_at ||
			(
				null !== $exchange_payload['refresh_token_expires_in'] &&
				(
					! is_int( $exchange_payload['refresh_token_expires_in'] ) ||
					$exchange_payload['refresh_token_expires_in'] <= 0 ||
					$exchange_payload['refresh_token_expires_in'] >
						PHP_INT_MAX - $stored_at
				)
			)
		) {
			unset(
				$exchange_payload,
				$expected_fingerprint,
				$exchange_ticket,
				$transaction_key
			);

			return 'managed_oauth_exchange_invalid_response';
		}

		$token_payload = array(
			'access_token'             => $exchange_payload['access_token'],
			'refresh_token'            => $exchange_payload['refresh_token'],
			'expires_at'               => $stored_at + $exchange_payload['expires_in'],
			'refresh_token_expires_at' => null !== $exchange_payload['refresh_token_expires_in']
				? $stored_at + $exchange_payload['refresh_token_expires_in']
				: null,
			'scope'                    => $exchange_payload['scope'],
			'token_type'               => $exchange_payload['token_type'],
			'created_at'               => $stored_at,
			'updated_at'               => $stored_at,
		);

		$stored =
			analytics_report_ai_store_managed_oauth_token_payload(
				$token_payload
			);

		unset(
			$token_payload,
			$exchange_payload,
			$expected_fingerprint,
			$exchange_ticket,
			$transaction_key
		);

		return $stored
			? 'managed_oauth_token_stored'
			: 'managed_oauth_token_storage_failed';
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
