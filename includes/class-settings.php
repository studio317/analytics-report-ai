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
		 * Google OAuth client configuration fallback.
		 *
		 * Empty input keeps the existing Settings fallback entries.
		 */
		$google_oauth_client = isset( $existing['google_oauth_client'] ) && is_array( $existing['google_oauth_client'] ) ? $existing['google_oauth_client'] : array();

		$google_oauth_client_id = isset( $google_oauth_client['client_id'] ) && is_scalar( $google_oauth_client['client_id'] )
			? analytics_report_ai_sanitize_credential_value( (string) $google_oauth_client['client_id'] )
			: '';

		$google_oauth_client_secret = isset( $google_oauth_client['client_secret'] ) && is_scalar( $google_oauth_client['client_secret'] )
			? analytics_report_ai_sanitize_credential_value( (string) $google_oauth_client['client_secret'] )
			: '';

		$clear_google_oauth_client_fallback = ! empty( $input['clear_google_oauth_client_fallback'] );

		if ( $clear_google_oauth_client_fallback ) {
			$google_oauth_client_id     = '';
			$google_oauth_client_secret = '';

			add_settings_error(
				ANALYTICS_REPORT_AI_OPTION_NAME,
				'analytics_report_ai_google_oauth_client_fallback_deleted',
				__( 'Settings fallback Google OAuth client configuration was deleted. Status: oauth_client_settings_fallback_status: deleted. Constants, OAuth tokens, and provider access were not changed.', 'analytics-report-ai' ),
				'updated'
			);
		} elseif ( isset( $input['google_oauth_client'] ) && is_array( $input['google_oauth_client'] ) ) {
			$input_google_oauth_client_id = isset( $input['google_oauth_client']['client_id'] ) && is_scalar( $input['google_oauth_client']['client_id'] )
				? analytics_report_ai_sanitize_credential_value( (string) $input['google_oauth_client']['client_id'] )
				: '';

			$input_google_oauth_client_secret = isset( $input['google_oauth_client']['client_secret'] ) && is_scalar( $input['google_oauth_client']['client_secret'] )
				? analytics_report_ai_sanitize_credential_value( (string) $input['google_oauth_client']['client_secret'] )
				: '';

			if ( '' !== $input_google_oauth_client_id ) {
				$google_oauth_client_id = $input_google_oauth_client_id;
			}

			if ( '' !== $input_google_oauth_client_secret ) {
				$google_oauth_client_secret = $input_google_oauth_client_secret;
			}
		}

		$settings['google_oauth_client'] = array(
			'client_id'     => $google_oauth_client_id,
			'client_secret' => $google_oauth_client_secret,
		);

		/*
		 * OpenAI API key Settings fallback.
		 *
		 * The normal Settings UI no longer creates or replaces this legacy /
		 * transitional fallback. Existing fallback values are preserved for
		 * compatibility and can be removed only by the explicit clear control.
		 */
		$clear_openai_api_key = ! empty( $input['clear_openai_api_key'] );

		if ( $clear_openai_api_key ) {
			$settings['openai_api_key'] = '';
		}

		/*
		 * Retired manual Google Access Token fallback.
		 *
		 * Settings save no longer accepts manual token input and clears the
		 * plugin-owned fallback category without reading or displaying values.
		 */
		$settings['google_tokens']      = array();
		$settings['google_auth_status'] = 'not_connected';

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

		$settings                  = analytics_report_ai_get_settings();
		$openai_api_key_categories = analytics_report_ai_get_openai_api_key_lifecycle_categories( $settings );
		$openai_api_key_source_category = isset( $openai_api_key_categories['openai_api_key_source_category'] )
			? $openai_api_key_categories['openai_api_key_source_category']
			: 'missing';
		$openai_api_key_settings_fallback_status = isset( $openai_api_key_categories['openai_api_key_settings_fallback_status'] )
			? $openai_api_key_categories['openai_api_key_settings_fallback_status']
			: 'not_saved';
		$openai_api_key_value_visibility = isset( $openai_api_key_categories['openai_api_key_value_visibility'] )
			? $openai_api_key_categories['openai_api_key_value_visibility']
			: 'hidden';
		$openai_api_key_constant_name = analytics_report_ai_get_openai_api_key_constant_name();
		$has_openai_api_key_settings_fallback = 'saved' === $openai_api_key_settings_fallback_status;
		$host_filter_enabled     = ! empty( $settings['host_filter_enabled'] );
		$ga4_property_id         = isset( $settings['ga4_property_id'] ) ? $settings['ga4_property_id'] : '';
		$host_name               = isset( $settings['host_name'] ) ? $settings['host_name'] : analytics_report_ai_get_default_host();
		$google_oauth_client_configuration      = analytics_report_ai_resolve_google_oauth_client_configuration( $settings );
		$google_oauth_client_source_category    = isset( $google_oauth_client_configuration['source_category'] ) ? $google_oauth_client_configuration['source_category'] : 'missing';
		$google_oauth_client_fallback_status    = isset( $google_oauth_client_configuration['settings_fallback_status'] ) ? $google_oauth_client_configuration['settings_fallback_status'] : 'not_saved';
		$google_oauth_client_value_hidden_status = isset( $google_oauth_client_configuration['value_hidden_status'] ) ? $google_oauth_client_configuration['value_hidden_status'] : 'hidden';
		$google_oauth_client_settings_status    = isset( $google_oauth_client_configuration['settings_status'] ) ? $google_oauth_client_configuration['settings_status'] : 'missing';
		$google_oauth_client_constant_names     = analytics_report_ai_get_google_oauth_client_constant_names();
		$has_google_oauth_client_fallback_id    = ! empty( $settings['google_oauth_client']['client_id'] );
		$has_google_oauth_client_fallback_secret = ! empty( $settings['google_oauth_client']['client_secret'] );
		$has_google_oauth_client_fallback       = 'missing' !== $google_oauth_client_settings_status;
		$google_oauth_lifecycle_categories      = analytics_report_ai_get_google_oauth_token_lifecycle_categories();
		$google_oauth_connection_state          = isset( $google_oauth_lifecycle_categories['oauth_connection_status_category'] ) ? $google_oauth_lifecycle_categories['oauth_connection_status_category'] : analytics_report_ai_get_google_oauth_connection_state();
		$google_oauth_token_lifecycle_status    = isset( $google_oauth_lifecycle_categories['token_lifecycle_status_category'] ) ? $google_oauth_lifecycle_categories['token_lifecycle_status_category'] : 'reconnect_required';
		$google_oauth_token_refresh_status      = isset( $google_oauth_lifecycle_categories['token_refresh_status_category'] ) ? $google_oauth_lifecycle_categories['token_refresh_status_category'] : 'unavailable';
		$google_oauth_token_disconnect_status   = isset( $google_oauth_lifecycle_categories['token_disconnect_status_category'] ) ? $google_oauth_lifecycle_categories['token_disconnect_status_category'] : 'not_requested';
		$google_oauth_token_revoke_status       = isset( $google_oauth_lifecycle_categories['token_revoke_status_category'] ) ? $google_oauth_lifecycle_categories['token_revoke_status_category'] : 'deferred';
		$has_google_oauth_token_storage         = analytics_report_ai_google_oauth_token_storage_exists();
		$google_oauth_redirect_uri              = $this->get_google_oauth_redirect_uri();
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
						<?php echo esc_html__( 'Google Analytics Data API requests use the resolved Google OAuth credential source. If the OAuth token state requires recovery, reconnect is the bounded recovery posture.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'OpenAI API requests use the resolved OpenAI API key source.', 'analytics-report-ai' ); ?>
					</li>
				</ul>
			</div>

			<div class="analytics-report-ai-card">
				<h2><?php echo esc_html__( 'Credential Storage (MVP)', 'analytics-report-ai' ); ?></h2>

				<p>
					<?php echo esc_html__( 'In the current MVP, the OpenAI API key uses a constant-based source first. Existing Settings fallback keys remain a legacy / transitional compatibility state. OAuth client Settings fallback can be saved in the WordPress database as plugin settings. Google OAuth tokens are stored in a dedicated plugin option.', 'analytics-report-ai' ); ?>
				</p>

				<ul class="analytics-report-ai-notice-list">
					<li>
						<?php echo esc_html__( 'Saved credential values are not displayed again. Empty fields keep existing values; delete checkboxes remove saved values.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'Database administrators, backups, server administrators, and code that can read WordPress options may be able to access saved credentials.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'OpenAI API key constants take precedence over the legacy / transitional Settings fallback. Settings fallback clear controls do not delete or change constants.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'This storage method is for MVP and developer verification. Public or multi-user use needs a redesigned credential flow.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'The manual Google Access Token fallback is retired from the normal Settings UI and is no longer a normal GA4 credential source.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'For OpenAI, use a Restricted API key with the minimum permissions needed for Responses API requests where possible.', 'analytics-report-ai' ); ?>
					</li>
				</ul>

				<p class="description">
					<?php echo esc_html__( 'For support, share status or category labels only. Do not share credentials, API keys, tokens, option values, OAuth client identifiers or secrets, request or response bodies, AI payload JSON, generated report text, screenshots, or browser Network evidence.', 'analytics-report-ai' ); ?>
				</p>
			</div>

			<div class="analytics-report-ai-card">
				<h2><?php echo esc_html__( 'Google OAuth Connection (MVP)', 'analytics-report-ai' ); ?></h2>

				<p>
					<?php echo esc_html__( 'Google OAuth is the preferred GA4 credential source. Authorization can attempt token exchange after callback state validation. Token values are not displayed. Automatic refresh is not a public-release capability; reconnect is the bounded recovery posture. Provider-side revoke is not performed in this selected public scope.', 'analytics-report-ai' ); ?>
				</p>

				<p>
					<strong><?php echo esc_html__( 'OAuth client source:', 'analytics-report-ai' ); ?></strong>
					<code><?php echo esc_html( 'oauth_client_source_category: ' . $google_oauth_client_source_category ); ?></code>
				</p>

				<p>
					<strong><?php echo esc_html__( 'Settings fallback status:', 'analytics-report-ai' ); ?></strong>
					<code><?php echo esc_html( 'oauth_client_settings_fallback_status: ' . $google_oauth_client_fallback_status ); ?></code>
				</p>

				<p>
					<strong><?php echo esc_html__( 'Value display status:', 'analytics-report-ai' ); ?></strong>
					<code><?php echo esc_html( 'oauth_client_value_hidden_status: ' . $google_oauth_client_value_hidden_status ); ?></code>
				</p>

				<p>
					<strong><?php echo esc_html__( 'OAuth connection state:', 'analytics-report-ai' ); ?></strong>
					<code><?php echo esc_html( 'oauth_connection_status_category: ' . $google_oauth_connection_state ); ?></code>
				</p>

				<p>
					<strong><?php echo esc_html__( 'Token lifecycle status:', 'analytics-report-ai' ); ?></strong>
					<code><?php echo esc_html( 'token_lifecycle_status_category: ' . $google_oauth_token_lifecycle_status ); ?></code>
				</p>

				<p>
					<strong><?php echo esc_html__( 'Refresh status:', 'analytics-report-ai' ); ?></strong>
					<code><?php echo esc_html( 'token_refresh_status_category: ' . $google_oauth_token_refresh_status ); ?></code>
				</p>

				<p>
					<strong><?php echo esc_html__( 'Local disconnect status:', 'analytics-report-ai' ); ?></strong>
					<code><?php echo esc_html( 'token_disconnect_status_category: ' . $google_oauth_token_disconnect_status ); ?></code>
				</p>

				<p>
					<strong><?php echo esc_html__( 'Provider revoke status:', 'analytics-report-ai' ); ?></strong>
					<code><?php echo esc_html( 'token_revoke_status_category: ' . $google_oauth_token_revoke_status ); ?></code>
				</p>

				<p>
					<label for="analytics-report-ai-google-oauth-redirect-uri">
						<strong><?php echo esc_html__( 'Redirect URI for Google OAuth setup:', 'analytics-report-ai' ); ?></strong>
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
						<?php echo esc_html__( 'OAuth client configuration uses constants first. Settings fallback is used only when constants are missing and the Settings fallback is complete.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php
						echo esc_html(
							sprintf(
								/* translators: 1: Google OAuth client ID constant name. 2: Google OAuth client secret constant name. */
								__( 'Expected constants: %1$s for client ID and %2$s for client secret.', 'analytics-report-ai' ),
								$google_oauth_client_constant_names['client_id'],
								$google_oauth_client_constant_names['client_secret']
							)
						);
						?>
					</li>
					<li>
						<?php echo esc_html__( 'If constants are partially configured, OAuth authorization is blocked as a safe configuration conflict. The plugin does not combine client ID and client secret configuration from different sources.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'Settings fallback configuration is saved only when entered below, is never displayed again, is inactive whenever complete constants are available, and is not the primary public setup guidance.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'The redirect URI is used for Google OAuth client setup. Copy the displayed value into the Google OAuth client configuration used by this site.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'Starting OAuth authorization can redirect the browser to Google. If the callback validates, this plugin can attempt token exchange and store OAuth tokens in a dedicated non-autoloaded option.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'The plugin displays only status-level OAuth state. It does not display authorization codes, token values, token endpoint responses, or option values. Refresh requests are not automatic recovery, and provider-side revoke requests are not part of the selected public scope.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'The manual Google Access Token fallback is retired from normal public-release behavior. Use Google OAuth as the GA4 credential source.', 'analytics-report-ai' ); ?>
					</li>
					<li>
						<?php echo esc_html__( 'Local disconnect deletes only local OAuth token data. It does not contact Google, revoke provider access, delete OAuth client Settings fallback values, or delete the OpenAI API key.', 'analytics-report-ai' ); ?>
					</li>
				</ul>

				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="analytics_report_ai_google_oauth_connect" />
					<?php wp_nonce_field( 'analytics_report_ai_google_oauth_connect', 'analytics_report_ai_google_oauth_nonce' ); ?>
					<?php submit_button( __( 'Start Google OAuth Authorization', 'analytics-report-ai' ), 'secondary', 'submit', false ); ?>
				</form>

				<?php if ( $has_google_oauth_token_storage ) : ?>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="analytics_report_ai_google_oauth_disconnect" />
						<?php wp_nonce_field( 'analytics_report_ai_google_oauth_disconnect', 'analytics_report_ai_google_oauth_disconnect_nonce' ); ?>
						<?php submit_button( __( 'Disconnect Local Google OAuth Tokens', 'analytics-report-ai' ), 'secondary', 'submit', false ); ?>
					</form>

					<p class="description">
						<?php echo esc_html__( 'This deletes only local OAuth token data saved by this plugin. It does not revoke provider-side access, delete OAuth client Settings fallback values, or delete the OpenAI API key.', 'analytics-report-ai' ); ?>
					</p>
				<?php endif; ?>
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
								<?php echo esc_html__( 'Google OAuth Client Fallback Status', 'analytics-report-ai' ); ?>
							</th>
							<td>
								<p>
									<strong><?php echo esc_html__( 'Active source:', 'analytics-report-ai' ); ?></strong>
									<code><?php echo esc_html( 'oauth_client_source_category: ' . $google_oauth_client_source_category ); ?></code>
								</p>

								<p>
									<strong><?php echo esc_html__( 'Settings fallback:', 'analytics-report-ai' ); ?></strong>
									<code><?php echo esc_html( 'oauth_client_settings_fallback_status: ' . $google_oauth_client_fallback_status ); ?></code>
								</p>

								<p class="description">
									<?php echo esc_html__( 'Constants take precedence. Settings fallback configuration is stored only for OAuth client configuration fallback and is never displayed again.', 'analytics-report-ai' ); ?>
								</p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="analytics-report-ai-google-oauth-client-id">
									<?php echo esc_html__( 'OAuth Client ID Fallback (Settings)', 'analytics-report-ai' ); ?>
								</label>
							</th>
							<td>
								<input
									type="password"
									id="analytics-report-ai-google-oauth-client-id"
									name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[google_oauth_client][client_id]"
									value=""
									class="regular-text"
									autocomplete="off"
									placeholder="<?php echo esc_attr( $has_google_oauth_client_fallback_id ? __( 'Saved fallback is hidden. Leave blank unless changing this setting.', 'analytics-report-ai' ) : __( 'No Settings fallback saved.', 'analytics-report-ai' ) ); ?>"
								/>

								<p class="description">
									<?php
									echo $has_google_oauth_client_fallback_id
										? esc_html__( 'Settings fallback OAuth client ID configuration is currently saved and hidden. Leave this field empty to keep the saved fallback.', 'analytics-report-ai' )
										: esc_html__( 'No Settings fallback OAuth client ID configuration is currently saved.', 'analytics-report-ai' );
									?>
								</p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="analytics-report-ai-google-oauth-client-secret">
									<?php echo esc_html__( 'OAuth Client Secret Fallback (Settings)', 'analytics-report-ai' ); ?>
								</label>
							</th>
							<td>
								<input
									type="password"
									id="analytics-report-ai-google-oauth-client-secret"
									name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[google_oauth_client][client_secret]"
									value=""
									class="regular-text"
									autocomplete="off"
									placeholder="<?php echo esc_attr( $has_google_oauth_client_fallback_secret ? __( 'Saved fallback is hidden. Leave blank unless changing this setting.', 'analytics-report-ai' ) : __( 'No Settings fallback saved.', 'analytics-report-ai' ) ); ?>"
								/>

								<p class="description">
									<?php
									echo $has_google_oauth_client_fallback_secret
										? esc_html__( 'Settings fallback OAuth client secret configuration is currently saved and hidden. Leave this field empty to keep the saved fallback.', 'analytics-report-ai' )
										: esc_html__( 'No Settings fallback OAuth client secret configuration is currently saved.', 'analytics-report-ai' );
									?>
								</p>

								<p class="description">
									<?php echo esc_html__( 'For support, share only the OAuth client source category, Settings fallback status, and value-hidden status labels. Do not share OAuth client identifiers or secrets.', 'analytics-report-ai' ); ?>
								</p>

								<?php if ( $has_google_oauth_client_fallback ) : ?>
									<p>
										<label for="analytics-report-ai-clear-google-oauth-client-fallback">
											<input
												type="checkbox"
												id="analytics-report-ai-clear-google-oauth-client-fallback"
												name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[clear_google_oauth_client_fallback]"
												value="1"
											/>
											<?php echo esc_html__( 'Delete the saved Settings fallback OAuth client configuration. Constants, OAuth tokens, and provider access are not changed.', 'analytics-report-ai' ); ?>
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
								<?php echo esc_html__( 'OpenAI API Key Source', 'analytics-report-ai' ); ?>
							</th>
							<td>
								<p>
									<strong><?php echo esc_html__( 'Active source:', 'analytics-report-ai' ); ?></strong>
									<code><?php echo esc_html( 'openai_api_key_source_category: ' . $openai_api_key_source_category ); ?></code>
								</p>

								<p>
									<strong><?php echo esc_html__( 'Settings fallback:', 'analytics-report-ai' ); ?></strong>
									<code><?php echo esc_html( 'openai_api_key_settings_fallback_status: ' . $openai_api_key_settings_fallback_status ); ?></code>
								</p>

								<p>
									<strong><?php echo esc_html__( 'Value display status:', 'analytics-report-ai' ); ?></strong>
									<code><?php echo esc_html( 'openai_api_key_value_visibility: ' . $openai_api_key_value_visibility ); ?></code>
								</p>

								<p class="description">
									<?php
									if ( 'constant_configured' === $openai_api_key_source_category && $has_openai_api_key_settings_fallback ) {
										echo esc_html__( 'A constant-based OpenAI API key is active. A legacy / transitional Settings fallback key is also saved and hidden for compatibility. Use the delete checkbox below only if you want to remove that Settings fallback.', 'analytics-report-ai' );
									} elseif ( 'constant_configured' === $openai_api_key_source_category ) {
										echo esc_html__( 'A constant-based OpenAI API key is active. The normal Settings UI does not create a new Settings fallback because constant-based configuration is preferred.', 'analytics-report-ai' );
									} elseif ( $has_openai_api_key_settings_fallback ) {
										echo esc_html__( 'A legacy / transitional Settings fallback OpenAI API key is currently saved and hidden for compatibility. Move to the preferred constant-based configuration when possible.', 'analytics-report-ai' );
									} else {
										echo esc_html__( 'No OpenAI API key source is currently configured. Configure the preferred constant-based source before generating reports.', 'analytics-report-ai' );
									}
									?>
								</p>

								<p class="description">
									<?php echo esc_html__( 'New Settings fallback entry is not shown in the normal Settings UI. Existing fallback values remain hidden and are not changed by saving this page unless the delete checkbox is used.', 'analytics-report-ai' ); ?>
								</p>

								<p class="description">
									<?php
									echo esc_html(
										sprintf(
											/* translators: %s: OpenAI API key constant name. */
											__( 'Preferred constant: %s. Constant values are never displayed and are not changed by this Settings field.', 'analytics-report-ai' ),
											$openai_api_key_constant_name
										)
									);
									?>
								</p>

								<p class="description">
									<?php echo esc_html__( 'For a safer setup, use a Restricted key and set Model capabilities and Responses (/v1/responses) to Request.', 'analytics-report-ai' ); ?>
									<br>
									<?php echo esc_html__( 'We recommend keeping unused features set to None.', 'analytics-report-ai' ); ?>
								</p>

								<?php if ( $has_openai_api_key_settings_fallback ) : ?>
									<p>
										<label for="analytics-report-ai-clear-openai-api-key">
											<input
												type="checkbox"
												id="analytics-report-ai-clear-openai-api-key"
												name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[clear_openai_api_key]"
												value="1"
											/>
											<?php echo esc_html__( 'Delete the saved legacy / transitional Settings fallback OpenAI API key. Constant-based configuration is not changed.', 'analytics-report-ai' ); ?>
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
			'google_oauth_redirect_client_config_missing' => __( 'Google OAuth authorization cannot start because the client ID constant is not configured. Google was not contacted and no token was saved.', 'analytics-report-ai' ),
			'google_oauth_redirect_client_config_unavailable' => __( 'Google OAuth authorization cannot start because the OAuth client source is missing, incomplete, or in conflict. Google was not contacted and no token was saved.', 'analytics-report-ai' ),
			'google_oauth_redirect_url_unavailable' => __( 'Google OAuth authorization could not be prepared. Google was not contacted and no token was saved.', 'analytics-report-ai' ),
			'callback_placeholder'                  => __( 'Google OAuth callback placeholder received a local return. No authorization code was exchanged and no token was saved.', 'analytics-report-ai' ),
			'callback_state_missing'                => __( 'Google OAuth callback placeholder received no state value. No token exchange was attempted.', 'analytics-report-ai' ),
			'callback_state_expired'                => __( 'Google OAuth callback placeholder could not find an active local state. No token exchange was attempted.', 'analytics-report-ai' ),
			'callback_state_invalid'                => __( 'Google OAuth callback placeholder received a state value that did not match the local placeholder. No token exchange was attempted.', 'analytics-report-ai' ),
			'callback_state_valid_provider_error'   => __( 'Google OAuth callback placeholder validated the local state and detected a provider error category. Raw error details were not displayed or saved.', 'analytics-report-ai' ),
			'callback_state_valid_code_present'     => __( 'Google OAuth callback placeholder validated the local state and detected an authorization code, but no code was displayed, saved, or exchanged.', 'analytics-report-ai' ),
			'callback_state_valid_no_code'          => __( 'Google OAuth callback placeholder validated the local state, but no authorization code was present. No token exchange was attempted.', 'analytics-report-ai' ),
			'token_exchange_success_category'       => __( 'Google OAuth token exchange completed and the OAuth connection state was updated. Token values are not displayed.', 'analytics-report-ai' ),
			'token_exchange_invalid_grant_category' => __( 'Google OAuth token exchange was not completed because the authorization result was rejected. No token value was displayed.', 'analytics-report-ai' ),
			'token_exchange_provider_error_category' => __( 'Google OAuth token exchange was not completed because the provider returned an error category. Raw provider details are not displayed.', 'analytics-report-ai' ),
			'token_exchange_network_error_category' => __( 'Google OAuth token exchange was not completed because the token endpoint request failed. Request and response details are not displayed.', 'analytics-report-ai' ),
			'token_exchange_malformed_response_category' => __( 'Google OAuth token exchange was not completed because the token response could not be classified safely. Raw response details are not displayed.', 'analytics-report-ai' ),
			'token_exchange_missing_token_category' => __( 'Google OAuth token exchange was not completed because the response did not include the required token category. Raw response details are not displayed.', 'analytics-report-ai' ),
			'token_exchange_not_executed'           => __( 'Google OAuth token exchange was not executed because the callback preconditions were not complete.', 'analytics-report-ai' ),
			'token_storage_unavailable_category'    => __( 'Google OAuth token exchange completed, but token storage did not complete. No token value is displayed.', 'analytics-report-ai' ),
			'google_oauth_local_disconnect_success' => __( 'Local Google OAuth token data was deleted. Status: token_disconnect_status_category: local_tokens_deleted. Google was not contacted, provider-side access was not revoked, and OAuth client Settings fallback values and the OpenAI API key were not changed.', 'analytics-report-ai' ),
			'google_oauth_local_disconnect_failed'  => __( 'Local Google OAuth token disconnect did not complete. Status: token_disconnect_status_category: failed. Token values and option values are not displayed, and provider-side revoke was not requested.', 'analytics-report-ai' ),
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
}
