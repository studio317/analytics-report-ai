<?php
/**
 * Settings admin screen.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers, sanitizes, and renders Analytics Report AI settings.
 *
 * @since 0.1.0
 */
final class Analytics_Report_AI_Settings {

	/**
	 * Google OAuth client ID constant name.
	 *
	 * @var string
	 */
	private const GOOGLE_OAUTH_CLIENT_ID_CONSTANT = 'ANALYTICS_REPORT_AI_GOOGLE_OAUTH_CLIENT_ID';

	/**
	 * Google OAuth client secret constant name.
	 *
	 * @var string
	 */
	private const GOOGLE_OAUTH_CLIENT_SECRET_CONSTANT = 'ANALYTICS_REPORT_AI_GOOGLE_OAUTH_CLIENT_SECRET';

	/**
	 * Settings group.
	 *
	 * @var string
	 */
	private $settings_group = 'analytics_report_ai_settings_group';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			$this->settings_group,
			ANALYTICS_REPORT_AI_OPTION_NAME,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'default'           => analytics_report_ai_get_default_settings(),
			)
		);
	}

	/**
	 * Sanitize settings.
	 *
	 * @param array $input Input values.
	 * @return array
	 */
	public function sanitize_settings( $input ) {
		$existing = analytics_report_ai_get_settings();
		$settings = $existing;

		if ( ! is_array( $input ) ) {
			return $settings;
		}

		/*
		 * GA4 property ID.
		 */
		$ga4_property_id = isset( $input['ga4_property_id'] ) ? trim( (string) $input['ga4_property_id'] ) : '';

		if ( '' === $ga4_property_id ) {
			$settings['ga4_property_id'] = '';
		} elseif ( analytics_report_ai_is_measurement_id( $ga4_property_id ) ) {
			add_settings_error(
				ANALYTICS_REPORT_AI_OPTION_NAME,
				'analytics_report_ai_measurement_id_error',
				__( 'GA4 Property ID must be a numeric property ID. Measurement IDs such as G-XXXXXXXXXX cannot be used.', 'analytics-report-ai' ),
				'error'
			);
		} elseif ( ! analytics_report_ai_is_valid_ga4_property_id( $ga4_property_id ) ) {
			add_settings_error(
				ANALYTICS_REPORT_AI_OPTION_NAME,
				'analytics_report_ai_property_id_error',
				__( 'GA4 Property ID must contain numbers only.', 'analytics-report-ai' ),
				'error'
			);
		} else {
			$settings['ga4_property_id'] = sanitize_text_field( $ga4_property_id );
		}

		/*
		 * Host filter.
		 */
		$settings['host_filter_enabled'] = ! empty( $input['host_filter_enabled'] ) ? 1 : 0;

		/*
		 * Host name.
		 */
		$host_name = isset( $input['host_name'] ) ? analytics_report_ai_normalize_host_name( $input['host_name'] ) : '';

		if ( '' === $host_name && ! empty( $settings['host_filter_enabled'] ) ) {
			$host_name = analytics_report_ai_get_default_host();

			add_settings_error(
				ANALYTICS_REPORT_AI_OPTION_NAME,
				'analytics_report_ai_host_name_error',
				__( 'Host name was empty, so the default host name was restored.', 'analytics-report-ai' ),
				'warning'
			);
		}

		$settings['host_name'] = $host_name;

		/*
		 * OpenAI API key.
		 *
		 * Empty input keeps the existing key.
		 */
		$clear_openai_api_key = ! empty( $input['clear_openai_api_key'] );
		$openai_api_key       = isset( $input['openai_api_key'] ) && is_scalar( $input['openai_api_key'] ) ? trim( (string) $input['openai_api_key'] ) : '';

		if ( $clear_openai_api_key ) {
			$settings['openai_api_key'] = '';
		} elseif ( '' !== $openai_api_key ) {
			$sanitized_openai_api_key = analytics_report_ai_sanitize_credential_value( $openai_api_key );

			if ( '' !== $sanitized_openai_api_key ) {
				$settings['openai_api_key'] = $sanitized_openai_api_key;
			}
		}

		/*
		 * Google access token for MVP development.
		 *
		 * Empty input keeps the existing token.
		 */
		$google_tokens = isset( $existing['google_tokens'] ) && is_array( $existing['google_tokens'] ) ? $existing['google_tokens'] : array();

		$clear_google_access_token = ! empty( $input['clear_google_access_token'] );
		$google_access_token       = isset( $input['google_access_token'] ) && is_scalar( $input['google_access_token'] ) ? trim( (string) $input['google_access_token'] ) : '';

		if (
			'' === $google_access_token
			&& isset( $input['google_tokens']['access_token'] )
			&& is_scalar( $input['google_tokens']['access_token'] )
		) {
			$google_access_token = trim( (string) $input['google_tokens']['access_token'] );
		}

		if ( $clear_google_access_token ) {
			unset( $google_tokens['access_token'] );
		} elseif ( '' !== $google_access_token ) {
			$sanitized_google_access_token = analytics_report_ai_sanitize_credential_value( $google_access_token );

			if ( '' !== $sanitized_google_access_token ) {
				$google_tokens['access_token'] = $sanitized_google_access_token;
			}
		}

		$settings['google_tokens']      = $google_tokens;
		$settings['google_auth_status'] = ! empty( $google_tokens['access_token'] ) ? 'connected' : 'not_connected';

		return $settings;
	}

	/**
	 * Render Settings page.
	 *
	 * @return void
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings                = analytics_report_ai_get_settings();
		$has_openai_api_key      = ! empty( $settings['openai_api_key'] );
		$google_auth_status      = isset( $settings['google_auth_status'] ) ? $settings['google_auth_status'] : 'not_connected';
		$has_google_access_token = ! empty( $settings['google_tokens']['access_token'] );
		$host_filter_enabled     = ! empty( $settings['host_filter_enabled'] );
		$ga4_property_id         = isset( $settings['ga4_property_id'] ) ? $settings['ga4_property_id'] : '';
		$host_name               = isset( $settings['host_name'] ) ? $settings['host_name'] : analytics_report_ai_get_default_host();
		$google_oauth_client_config_status = $this->get_google_oauth_client_configuration_status();
		$google_oauth_redirect_uri         = $this->get_google_oauth_redirect_uri();
		?>
		<div class="wrap analytics-report-ai-admin">
			<h1><?php echo esc_html__( 'Analytics Report AI Settings', 'analytics-report-ai' ); ?></h1>

			<?php settings_errors(); ?>
			<?php $this->render_google_oauth_status_notice(); ?>

			<div class="analytics-report-ai-card">
				<h2><?php echo esc_html__( 'External service usage', 'analytics-report-ai' ); ?></h2>

				<p>
					<?php echo esc_html__( 'Analytics Report AI uses the Google Analytics Data API to fetch selected GA4 report data and uses the OpenAI API to generate a Japanese report draft from the reviewed payload.', 'analytics-report-ai' ); ?>
				</p>

				<ul class="analytics-report-ai-notice-list">
					<li>
						<?php echo esc_html__( 'Google Analytics Data API requests use the saved Google Access Token.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'OpenAI API requests use the saved OpenAI API Key.', 'analytics-report-ai' ); ?>
					</li>
				</ul>
			</div>

			<div class="analytics-report-ai-card">
				<h2><?php echo esc_html__( 'Credential Storage (MVP)', 'analytics-report-ai' ); ?></h2>

				<p>
					<?php echo esc_html__( 'In the current MVP, the Google Access Token and OpenAI API Key are saved in the WordPress database as plugin settings.', 'analytics-report-ai' ); ?>
				</p>

				<ul class="analytics-report-ai-notice-list">
					<li>
						<?php echo esc_html__( 'Saved credential values are not displayed again. Empty fields keep existing values; delete checkboxes remove saved values.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'Database administrators, backups, server administrators, and code that can read WordPress options may be able to access saved credentials.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'This storage method is for MVP and developer verification. Public or multi-user use needs a redesigned credential flow.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'The manual Google Access Token field is temporary and needs OAuth connection, expiry handling, scope checks, and revoke or reconnect controls before public use.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'For OpenAI, use a Restricted API key with the minimum permissions needed for Responses API requests where possible.', 'analytics-report-ai' ); ?>
					</li>
				</ul>
			</div>

			<div class="analytics-report-ai-card">
				<h2><?php echo esc_html__( 'Google OAuth Connection (Planned)', 'analytics-report-ai' ); ?></h2>

				<p>
					<?php echo esc_html__( 'A Google OAuth connection flow is planned for public release readiness, but it is not complete in this step.', 'analytics-report-ai' ); ?>
				</p>

				<p>
					<strong><?php echo esc_html__( 'Client configuration status:', 'analytics-report-ai' ); ?></strong>
					<code><?php echo esc_html( $google_oauth_client_config_status ); ?></code>
				</p>

				<p>
					<label for="analytics-report-ai-google-oauth-redirect-uri">
						<strong><?php echo esc_html__( 'Redirect URI for future Google OAuth setup:', 'analytics-report-ai' ); ?></strong>
					</label>
					<input
						type="text"
						id="analytics-report-ai-google-oauth-redirect-uri"
						value="<?php echo esc_attr( $google_oauth_redirect_uri ); ?>"
						class="large-text code"
						readonly="readonly"
					/>
				</p>

				<ul class="analytics-report-ai-notice-list">
					<li>
						<?php echo esc_html__( 'Client configuration is detected only as status-level constant presence. Client ID and client secret values are not displayed.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php
						echo esc_html(
							sprintf(
								/* translators: 1: Google OAuth client ID constant name. 2: Google OAuth client secret constant name. */
								__( 'Expected constants: %1$s for client ID and %2$s for client secret.', 'analytics-report-ai' ),
								self::GOOGLE_OAUTH_CLIENT_ID_CONSTANT,
								self::GOOGLE_OAUTH_CLIENT_SECRET_CONSTANT
							)
						);
						?>
					</li>
					<li>
						<?php echo esc_html__( 'The client secret is expected to be configured outside plugin settings by constant. This plugin does not save the client secret in Settings.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'The redirect URI is shown only for future Google OAuth client setup. Copy it into the Google OAuth client configuration when OAuth support is completed.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'This placeholder prepares a temporary local state value for future OAuth validation, but it does not display the state, contact Google, generate an authorization URL, exchange authorization codes, save tokens, refresh tokens, revoke access, or change GA4 fetch behavior.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'The temporary manual Google Access Token field below remains available for controlled developer verification only.', 'analytics-report-ai' ); ?>
					</li>
				</ul>

				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="analytics_report_ai_google_oauth_connect" />
					<?php wp_nonce_field( 'analytics_report_ai_google_oauth_connect', 'analytics_report_ai_google_oauth_nonce' ); ?>
					<?php submit_button( __( 'Prepare Local OAuth State Placeholder', 'analytics-report-ai' ), 'secondary', 'submit', false ); ?>
				</form>
			</div>

			<form method="post" action="options.php" class="analytics-report-ai-card">
				<?php settings_fields( $this->settings_group ); ?>

				<h2><?php echo esc_html__( 'Basic Settings', 'analytics-report-ai' ); ?></h2>

				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">
								<label for="analytics-report-ai-ga4-property-id">
									<?php echo esc_html__( 'GA4 Property ID', 'analytics-report-ai' ); ?>
								</label>
							</th>
							<td>
								<input
									type="text"
									id="analytics-report-ai-ga4-property-id"
									name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[ga4_property_id]"
									value="<?php echo esc_attr( $ga4_property_id ); ?>"
									class="regular-text"
									inputmode="numeric"
								/>
								<p class="description">
									<?php echo esc_html__( 'Enter the numeric GA4 property ID. Measurement IDs such as G-XXXXXXXXXX are not supported.', 'analytics-report-ai' ); ?>
								</p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<?php echo esc_html__( 'Google Access Token Status', 'analytics-report-ai' ); ?>
							</th>
							<td>
								<code><?php echo esc_html( $google_auth_status ); ?></code>
								<p class="description">
									<?php echo esc_html__( 'This status is based on whether a temporary Google Access Token is saved. The token value is not displayed.', 'analytics-report-ai' ); ?>
								</p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="analytics-report-ai-google-access-token">
									<?php echo esc_html__( 'Google Access Token', 'analytics-report-ai' ); ?>
								</label>
							</th>
							<td>
								<input
									type="password"
									id="analytics-report-ai-google-access-token"
									name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[google_access_token]"
									value=""
									class="regular-text"
									autocomplete="off"
									placeholder="<?php echo esc_attr( $has_google_access_token ? __( 'Saved. Enter a new token only when replacing it.', 'analytics-report-ai' ) : __( 'Not saved.', 'analytics-report-ai' ) ); ?>"
								/>

								<p class="description">
									<?php
									echo $has_google_access_token
										? esc_html__( 'A Google access token is currently saved. Leave this field empty to keep the existing token.', 'analytics-report-ai' )
										: esc_html__( 'No Google access token is currently saved.', 'analytics-report-ai' );
									?>
								</p>

								<p class="description">
									<?php echo esc_html__( 'This temporary MVP/developer verification field must contain a token with GA4 read access. The token may expire in about one hour.', 'analytics-report-ai' ); ?>
								</p>

								<?php if ( $has_google_access_token ) : ?>
									<p>
										<label for="analytics-report-ai-clear-google-access-token">
											<input
												type="checkbox"
												id="analytics-report-ai-clear-google-access-token"
												name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[clear_google_access_token]"
												value="1"
											/>
											<?php echo esc_html__( 'Delete the saved Google access token.', 'analytics-report-ai' ); ?>
										</label>
									</p>
								<?php endif; ?>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<?php echo esc_html__( 'Host Name Filter', 'analytics-report-ai' ); ?>
							</th>
							<td>
								<label for="analytics-report-ai-host-filter-enabled">
									<input
										type="checkbox"
										id="analytics-report-ai-host-filter-enabled"
										name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[host_filter_enabled]"
										value="1"
										<?php checked( $host_filter_enabled ); ?>
									/>
									<?php echo esc_html__( 'Filter GA4 data by host name.', 'analytics-report-ai' ); ?>
								</label>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="analytics-report-ai-host-name">
									<?php echo esc_html__( 'Host Name', 'analytics-report-ai' ); ?>
								</label>
							</th>
							<td>
								<input
									type="text"
									id="analytics-report-ai-host-name"
									name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[host_name]"
									value="<?php echo esc_attr( $host_name ); ?>"
									class="regular-text"
								/>
								<p class="description">
									<?php echo esc_html__( 'Default value is based on home_url(). Example: example.com', 'analytics-report-ai' ); ?>
								</p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="analytics-report-ai-openai-api-key">
									<?php echo esc_html__( 'OpenAI API Key', 'analytics-report-ai' ); ?>
								</label>
							</th>
							<td>
								<input
									type="password"
									id="analytics-report-ai-openai-api-key"
									name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[openai_api_key]"
									value=""
									class="regular-text"
									autocomplete="off"
									placeholder="<?php echo esc_attr( $has_openai_api_key ? __( 'Saved. Enter a new key only when replacing it.', 'analytics-report-ai' ) : __( 'Not saved.', 'analytics-report-ai' ) ); ?>"
								/>

								<p class="description">
									<?php
									echo $has_openai_api_key
										? esc_html__( 'An OpenAI API key is currently saved. Leave this field empty to keep the existing key.', 'analytics-report-ai' )
										: esc_html__( 'No OpenAI API key is currently saved.', 'analytics-report-ai' );
									?>
								</p>

								<p class="description">
									<?php echo esc_html__( 'For a safer setup, use a Restricted key and set Model capabilities and Responses (/v1/responses) to Request.', 'analytics-report-ai' ); ?>
									<br>
									<?php echo esc_html__( 'We recommend keeping unused features set to None.', 'analytics-report-ai' ); ?>
								</p>

								<?php if ( $has_openai_api_key ) : ?>
									<p>
										<label for="analytics-report-ai-clear-openai-api-key">
											<input
												type="checkbox"
												id="analytics-report-ai-clear-openai-api-key"
												name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[clear_openai_api_key]"
												value="1"
											/>
											<?php echo esc_html__( 'Delete the saved OpenAI API key.', 'analytics-report-ai' ); ?>
										</label>
									</p>
								<?php endif; ?>
							</td>
						</tr>
					</tbody>
				</table>

				<?php submit_button( __( 'Save Settings', 'analytics-report-ai' ) ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render a status-level notice for local Google OAuth skeleton results.
	 *
	 * @return void
	 */
	private function render_google_oauth_status_notice() {
		$status = filter_input( INPUT_GET, 'analytics_report_ai_google_oauth_status', FILTER_UNSAFE_RAW );
		$status = is_string( $status ) ? sanitize_key( $status ) : '';

		if ( '' === $status ) {
			return;
		}

		$messages = array(
			'connect_placeholder'                   => __( 'Google OAuth connection is planned, but this placeholder did not contact Google, exchange a code, or save a token.', 'analytics-report-ai' ),
			'connect_state_prepared'                => __( 'A temporary local OAuth state placeholder was prepared. The state value is not displayed. Google was not contacted and no token was saved.', 'analytics-report-ai' ),
			'callback_placeholder'                  => __( 'Google OAuth callback placeholder received a local return. No authorization code was exchanged and no token was saved.', 'analytics-report-ai' ),
			'callback_state_missing'                => __( 'Google OAuth callback placeholder received no state value. No token exchange was attempted.', 'analytics-report-ai' ),
			'callback_state_expired'                => __( 'Google OAuth callback placeholder could not find an active local state. No token exchange was attempted.', 'analytics-report-ai' ),
			'callback_state_invalid'                => __( 'Google OAuth callback placeholder received a state value that did not match the local placeholder. No token exchange was attempted.', 'analytics-report-ai' ),
			'callback_state_valid_provider_error'   => __( 'Google OAuth callback placeholder validated the local state and detected a provider error category. Raw error details were not displayed or saved.', 'analytics-report-ai' ),
			'callback_state_valid_code_present'     => __( 'Google OAuth callback placeholder validated the local state and detected an authorization code, but no code was displayed, saved, or exchanged.', 'analytics-report-ai' ),
			'callback_state_valid_no_code'          => __( 'Google OAuth callback placeholder validated the local state, but no authorization code was present. No token exchange was attempted.', 'analytics-report-ai' ),
		);

		if ( ! isset( $messages[ $status ] ) ) {
			return;
		}
		?>
		<div class="notice notice-info">
			<p><?php echo esc_html( $messages[ $status ] ); ?></p>
		</div>
		<?php
	}

	/**
	 * Get Google OAuth client configuration status without exposing values.
	 *
	 * @return string
	 */
	private function get_google_oauth_client_configuration_status() {
		$has_client_id     = $this->is_non_empty_constant( self::GOOGLE_OAUTH_CLIENT_ID_CONSTANT );
		$has_client_secret = $this->is_non_empty_constant( self::GOOGLE_OAUTH_CLIENT_SECRET_CONSTANT );

		if ( $has_client_id && $has_client_secret ) {
			return 'google_oauth_client_configured';
		}

		if ( $has_client_id ) {
			return 'google_oauth_client_id_configured_secret_missing';
		}

		if ( $has_client_secret ) {
			return 'google_oauth_client_id_missing_secret_configured';
		}

		return 'google_oauth_client_not_configured';
	}

	/**
	 * Get the Google OAuth callback redirect URI for setup display.
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
	 * Check whether a constant is defined and non-empty without returning value.
	 *
	 * @param string $constant_name Constant name.
	 * @return bool
	 */
	private function is_non_empty_constant( $constant_name ) {
		if ( ! defined( $constant_name ) ) {
			return false;
		}

		$value = constant( $constant_name );

		return is_scalar( $value ) && '' !== trim( (string) $value );
	}
}
