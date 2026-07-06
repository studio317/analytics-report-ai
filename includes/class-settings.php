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
 * Registers, sanitizes, and renders Studio317 Report Drafts for Google Analytics settings.
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
				__( 'GA4 Property ID must be a numeric property ID. Measurement IDs such as G-XXXXXXXXXX cannot be used.', 'studio317-report-drafts-google-analytics' ),
				'error'
			);
		} elseif ( ! analytics_report_ai_is_valid_ga4_property_id( $ga4_property_id ) ) {
			add_settings_error(
				ANALYTICS_REPORT_AI_OPTION_NAME,
				'analytics_report_ai_property_id_error',
				__( 'GA4 Property ID must contain numbers only.', 'studio317-report-drafts-google-analytics' ),
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
				__( 'Host name was empty, so the default host name was restored.', 'studio317-report-drafts-google-analytics' ),
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
				__( 'Saved Google OAuth client settings were deleted. Server configuration, Google connection tokens, and provider access were not changed.', 'studio317-report-drafts-google-analytics' ),
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

		unset( $settings['openai_api_key'] );


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
		$host_filter_enabled     = ! empty( $settings['host_filter_enabled'] );
		$ga4_property_id         = isset( $settings['ga4_property_id'] ) ? $settings['ga4_property_id'] : '';
		$host_name               = isset( $settings['host_name'] ) ? $settings['host_name'] : analytics_report_ai_get_default_host();
		$google_oauth_client_configuration      = analytics_report_ai_resolve_google_oauth_client_configuration( $settings );
		$google_oauth_client_source_category    = isset( $google_oauth_client_configuration['source_category'] ) ? $google_oauth_client_configuration['source_category'] : 'missing';
		$google_oauth_client_settings_status    = isset( $google_oauth_client_configuration['settings_status'] ) ? $google_oauth_client_configuration['settings_status'] : 'missing';
		$has_google_oauth_client_fallback_id    = ! empty( $settings['google_oauth_client']['client_id'] );
		$has_google_oauth_client_fallback_secret = ! empty( $settings['google_oauth_client']['client_secret'] );
		$has_google_oauth_client_fallback       = 'missing' !== $google_oauth_client_settings_status;
		$google_oauth_lifecycle_categories      = analytics_report_ai_get_google_oauth_token_lifecycle_categories();
		$google_oauth_connection_state          = isset( $google_oauth_lifecycle_categories['oauth_connection_status_category'] ) ? $google_oauth_lifecycle_categories['oauth_connection_status_category'] : analytics_report_ai_get_google_oauth_connection_state();
		$google_oauth_token_lifecycle_status    = isset( $google_oauth_lifecycle_categories['token_lifecycle_status_category'] ) ? $google_oauth_lifecycle_categories['token_lifecycle_status_category'] : 'reconnect_required';
		$has_google_oauth_token_storage         = analytics_report_ai_google_oauth_token_storage_exists();
		$google_oauth_redirect_uri              = $this->get_google_oauth_redirect_uri();
		$google_oauth_is_connected              = 'connected' === $google_oauth_connection_state && 'usable' === $google_oauth_token_lifecycle_status;
		$google_oauth_needs_reconnect           = ! $google_oauth_is_connected && $has_google_oauth_token_storage;
		?>
		<div class="wrap studio317-report-drafts-google-analytics-admin">
			<h1><?php echo esc_html__( 'Studio317 Report Drafts for Google Analytics Settings', 'studio317-report-drafts-google-analytics' ); ?></h1>

			<?php settings_errors(); ?>
			<?php $this->render_google_oauth_status_notice(); ?>

			<p class="description">
				<?php echo esc_html__( 'Configure report settings and Google connection controls. Detailed setup and data-use guidance is available in help dialogs.', 'studio317-report-drafts-google-analytics' ); ?>
			</p>

			<p>
				<?php
				Analytics_Report_AI_Help_Dialog::render_button(
					'studio317-report-drafts-google-analytics-setup-checklist-help',
					__( 'Setup checklist', 'studio317-report-drafts-google-analytics' ),
					__( 'Setup checklist', 'studio317-report-drafts-google-analytics' )
				);
				Analytics_Report_AI_Help_Dialog::render_button(
					'studio317-report-drafts-google-analytics-external-service-usage-help',
					__( 'External service usage', 'studio317-report-drafts-google-analytics' ),
					__( 'External service usage', 'studio317-report-drafts-google-analytics' )
				);
				?>
			</p>

			<?php
			Analytics_Report_AI_Help_Dialog::render_dialog(
				'studio317-report-drafts-google-analytics-setup-checklist-help',
				__( 'Setup checklist', 'studio317-report-drafts-google-analytics' ),
				array(
					__( 'Configure the GA4 property and optional host filter if needed.', 'studio317-report-drafts-google-analytics' ),
					__( 'Enter the Google OAuth client manually, or configure it in wp-config.php or another server configuration file.', 'studio317-report-drafts-google-analytics' ),
					__( 'Configure a compatible AI text-generation provider in WordPress Settings > Connectors.', 'studio317-report-drafts-google-analytics' ),
					__( 'Click Connect Google Account.', 'studio317-report-drafts-google-analytics' ),
					__( 'Open Report Builder and click Fetch GA4 Data.', 'studio317-report-drafts-google-analytics' ),
					__( 'Review the Data Preview, generate the report draft in the current WordPress user language, then review, edit, and copy it.', 'studio317-report-drafts-google-analytics' ),
				),
				'',
				'ol'
			);

			Analytics_Report_AI_Help_Dialog::render_dialog(
				'studio317-report-drafts-google-analytics-external-service-usage-help',
				__( 'External service usage', 'studio317-report-drafts-google-analytics' ),
				array(
					__( 'Studio317 Report Drafts for Google Analytics contacts Google only when you start Google authorization or fetch GA4 data. It sends reviewed report data through the WordPress AI Client only when you click Generate AI Report after reviewing the Data Preview.', 'studio317-report-drafts-google-analytics' ),
					__( 'Fetch GA4 Data sends the selected report conditions and required metrics or dimensions to the Google Analytics Data API.', 'studio317-report-drafts-google-analytics' ),
					__( 'Generate AI Report sends the reviewed report data and selected report output language through the WordPress AI Client to the AI provider configured by the site administrator.', 'studio317-report-drafts-google-analytics' ),
					__( 'AI provider credentials and provider selection are managed by WordPress Connectors, not by this plugin.', 'studio317-report-drafts-google-analytics' ),
					__( 'Saved Google credential values are hidden. Empty password fields keep existing saved values, and delete checkboxes remove only the matching saved value.', 'studio317-report-drafts-google-analytics' ),
					__( 'For support, share visible status messages or general error names only. Do not share credentials, tokens, option values, request bodies, raw responses, AI data JSON, generated report text, screenshots, or browser Network evidence.', 'studio317-report-drafts-google-analytics' ),
				)
			);
			?>

			<div id="studio317-report-drafts-google-analytics-google-connection-settings" class="studio317-report-drafts-google-analytics-card">
				<h2><?php echo esc_html__( 'Google connection', 'studio317-report-drafts-google-analytics' ); ?></h2>

				<p>
					<?php echo esc_html__( 'Connect Google before fetching GA4 data. Token values, authorization codes, provider responses, and option values are never displayed on this screen.', 'studio317-report-drafts-google-analytics' ); ?>
				</p>

				<p>
					<?php
					Analytics_Report_AI_Help_Dialog::render_button(
						'studio317-report-drafts-google-analytics-google-oauth-setup-help',
						__( 'Google OAuth setup', 'studio317-report-drafts-google-analytics' ),
						__( 'Google OAuth setup', 'studio317-report-drafts-google-analytics' )
					);
					?>
				</p>

				<?php
				Analytics_Report_AI_Help_Dialog::render_dialog(
					'studio317-report-drafts-google-analytics-google-oauth-setup-help',
					__( 'Google OAuth setup', 'studio317-report-drafts-google-analytics' ),
					array(
						__( 'Server configuration takes precedence over saved Settings values. When server configuration is active, it cannot be edited or deleted here.', 'studio317-report-drafts-google-analytics' ),
						__( 'If OAuth client settings are incomplete, fix the client ID and client secret before starting Google authorization.', 'studio317-report-drafts-google-analytics' ),
						__( 'The redirect URI shown on this screen must be registered in the Google OAuth client used by this site.', 'studio317-report-drafts-google-analytics' ),
						__( 'Refresh is not performed automatically. If the Google connection needs recovery, reconnect the Google account.', 'studio317-report-drafts-google-analytics' ),
						__( 'Disconnecting Google deletes only Google connection data stored by this plugin. It does not contact Google, revoke provider access, delete OAuth client settings, or change AI provider configuration.', 'studio317-report-drafts-google-analytics' ),
						__( 'Manual Google access token entry is not available. Use Google OAuth for GA4 access.', 'studio317-report-drafts-google-analytics' ),
					)
				);
				?>

				<p>
					<label for="studio317-report-drafts-google-analytics-google-oauth-redirect-uri">
						<strong><?php echo esc_html__( 'Redirect URI for Google OAuth setup:', 'studio317-report-drafts-google-analytics' ); ?></strong>
					</label>
					<input
						type="text"
						id="studio317-report-drafts-google-analytics-google-oauth-redirect-uri"
						value="<?php echo esc_attr( $google_oauth_redirect_uri ); ?>"
						class="large-text code"
						readonly="readonly"
					/>
				</p>

				<?php if ( $google_oauth_is_connected ) : ?>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="analytics_report_ai_google_oauth_disconnect" />
						<?php wp_nonce_field( 'analytics_report_ai_google_oauth_disconnect', 'analytics_report_ai_google_oauth_disconnect_nonce' ); ?>
						<?php submit_button( __( 'Disconnect Google Account', 'studio317-report-drafts-google-analytics' ), 'secondary', 'submit', false ); ?>
					</form>

					<p class="description">
						<?php echo esc_html__( 'This deletes only Google connection data saved by this plugin. It does not delete Google provider access, Google OAuth client settings, or AI provider configuration.', 'studio317-report-drafts-google-analytics' ); ?>
					</p>
				<?php else : ?>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="analytics_report_ai_google_oauth_connect" />
						<?php wp_nonce_field( 'analytics_report_ai_google_oauth_connect', 'analytics_report_ai_google_oauth_nonce' ); ?>
						<?php submit_button( $google_oauth_needs_reconnect ? __( 'Reconnect Google Account', 'studio317-report-drafts-google-analytics' ) : __( 'Connect Google Account', 'studio317-report-drafts-google-analytics' ), 'secondary', 'submit', false ); ?>
					</form>
				<?php endif; ?>
			</div>

			<form method="post" action="options.php" class="studio317-report-drafts-google-analytics-card">
				<?php settings_fields( $this->settings_group ); ?>

				<h2><?php echo esc_html__( 'Report and service settings', 'studio317-report-drafts-google-analytics' ); ?></h2>

				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">
								<label for="studio317-report-drafts-google-analytics-ga4-property-id">
									<?php echo esc_html__( 'GA4 Property ID', 'studio317-report-drafts-google-analytics' ); ?>
								</label>
							</th>
							<td>
								<input
									type="text"
									id="studio317-report-drafts-google-analytics-ga4-property-id"
									name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[ga4_property_id]"
									value="<?php echo esc_attr( $ga4_property_id ); ?>"
									class="regular-text"
									inputmode="numeric"
								/>
								<p class="description">
									<?php echo esc_html__( 'Enter the numeric GA4 property ID. Measurement IDs such as G-XXXXXXXXXX are not supported.', 'studio317-report-drafts-google-analytics' ); ?>
								</p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="studio317-report-drafts-google-analytics-google-oauth-client-id">
									<?php echo esc_html__( 'Google OAuth Client ID', 'studio317-report-drafts-google-analytics' ); ?>
								</label>
								<?php
								Analytics_Report_AI_Help_Dialog::render_button(
									'studio317-report-drafts-google-analytics-google-oauth-client-id-help',
									__( 'Google OAuth Client ID Help', 'studio317-report-drafts-google-analytics' )
								);
								?>
							</th>
							<td>
								<input
									type="password"
									id="studio317-report-drafts-google-analytics-google-oauth-client-id"
									<?php if ( 'constants' !== $google_oauth_client_source_category ) : ?>
										name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[google_oauth_client][client_id]"
									<?php endif; ?>
									value=""
									class="regular-text"
									autocomplete="off"
									placeholder="<?php echo esc_attr( $has_google_oauth_client_fallback_id ? __( 'Saved value is hidden. Leave blank to keep it.', 'studio317-report-drafts-google-analytics' ) : __( 'Enter a client ID, or leave blank if using server configuration.', 'studio317-report-drafts-google-analytics' ) ); ?>"
									<?php disabled( 'constants' === $google_oauth_client_source_category ); ?>
								/>

								<p class="description">
									<?php
									if ( 'constants' === $google_oauth_client_source_category ) {
										echo esc_html__( 'This value is managed by server configuration and cannot be edited here.', 'studio317-report-drafts-google-analytics' );
									} elseif ( $has_google_oauth_client_fallback_id ) {
										echo esc_html__( 'A saved client ID exists and is hidden. Leave this field empty to keep it, or enter a new value to replace it.', 'studio317-report-drafts-google-analytics' );
									} else {
										echo esc_html__( 'Enter the client ID from a Google OAuth web application client.', 'studio317-report-drafts-google-analytics' );
									}
									?>
								</p>
								<?php $this->render_google_oauth_client_id_help_dialog(); ?>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="studio317-report-drafts-google-analytics-google-oauth-client-secret">
									<?php echo esc_html__( 'Google OAuth Client Secret', 'studio317-report-drafts-google-analytics' ); ?>
								</label>
								<?php
								Analytics_Report_AI_Help_Dialog::render_button(
									'studio317-report-drafts-google-analytics-google-oauth-client-secret-help',
									__( 'Google OAuth Client Secret Help', 'studio317-report-drafts-google-analytics' )
								);
								?>
							</th>
							<td>
								<input
									type="password"
									id="studio317-report-drafts-google-analytics-google-oauth-client-secret"
									<?php if ( 'constants' !== $google_oauth_client_source_category ) : ?>
										name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[google_oauth_client][client_secret]"
									<?php endif; ?>
									value=""
									class="regular-text"
									autocomplete="off"
									placeholder="<?php echo esc_attr( $has_google_oauth_client_fallback_secret ? __( 'Saved value is hidden. Leave blank to keep it.', 'studio317-report-drafts-google-analytics' ) : __( 'Enter a client secret, or leave blank if using server configuration.', 'studio317-report-drafts-google-analytics' ) ); ?>"
									<?php disabled( 'constants' === $google_oauth_client_source_category ); ?>
								/>

								<p class="description">
									<?php
									if ( 'constants' === $google_oauth_client_source_category ) {
										echo esc_html__( 'This value is managed by server configuration and cannot be edited here.', 'studio317-report-drafts-google-analytics' );
									} elseif ( $has_google_oauth_client_fallback_secret ) {
										echo esc_html__( 'A saved client secret exists and is hidden. Leave this field empty to keep it, or enter a new value to replace it.', 'studio317-report-drafts-google-analytics' );
									} else {
										echo esc_html__( 'Enter the client secret from the same Google OAuth web application client.', 'studio317-report-drafts-google-analytics' );
									}
									?>
								</p>
								<?php $this->render_google_oauth_client_secret_help_dialog(); ?>

								<p class="description">
									<?php echo esc_html__( 'For support, do not share OAuth client identifiers or secrets. Share only whether the setup is configured, not configured, or needs review.', 'studio317-report-drafts-google-analytics' ); ?>
								</p>

								<?php if ( $has_google_oauth_client_fallback ) : ?>
									<p>
										<label for="studio317-report-drafts-google-analytics-clear-google-oauth-client-fallback">
											<input
												type="checkbox"
												id="studio317-report-drafts-google-analytics-clear-google-oauth-client-fallback"
												name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[clear_google_oauth_client_fallback]"
												value="1"
											/>
											<?php echo esc_html__( 'Delete the saved Google OAuth client settings. Server configuration, Google tokens, and provider access are not changed.', 'studio317-report-drafts-google-analytics' ); ?>
										</label>
									</p>
								<?php endif; ?>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<?php echo esc_html__( 'Host Name Filter', 'studio317-report-drafts-google-analytics' ); ?>
							</th>
							<td>
								<label for="studio317-report-drafts-google-analytics-host-filter-enabled">
									<input
										type="checkbox"
										id="studio317-report-drafts-google-analytics-host-filter-enabled"
										name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[host_filter_enabled]"
										value="1"
										<?php checked( $host_filter_enabled ); ?>
									/>
									<?php echo esc_html__( 'Filter GA4 data by host name.', 'studio317-report-drafts-google-analytics' ); ?>
								</label>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="studio317-report-drafts-google-analytics-host-name">
									<?php echo esc_html__( 'Host Name', 'studio317-report-drafts-google-analytics' ); ?>
								</label>
							</th>
							<td>
								<input
									type="text"
									id="studio317-report-drafts-google-analytics-host-name"
									name="<?php echo esc_attr( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>[host_name]"
									value="<?php echo esc_attr( $host_name ); ?>"
									class="regular-text"
								/>
								<p class="description">
									<?php echo esc_html__( 'Default value is based on home_url(). Example: example.com', 'studio317-report-drafts-google-analytics' ); ?>
								</p>
							</td>
						</tr>

					</tbody>
				</table>

				<?php submit_button( __( 'Save Settings', 'studio317-report-drafts-google-analytics' ) ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render the Google OAuth Client ID help dialog.
	 *
	 * @return void
	 */
	private function render_google_oauth_client_id_help_dialog() {
		Analytics_Report_AI_Help_Dialog::render_dialog(
			'studio317-report-drafts-google-analytics-google-oauth-client-id-help',
			__( 'Google OAuth Client ID setup', 'studio317-report-drafts-google-analytics' ),
			array(
				__( 'Enter the Client ID from a Google Cloud OAuth client configured as a Web application.', 'studio317-report-drafts-google-analytics' ),
				__( 'Register the Redirect URI shown on this Settings screen in the same Google OAuth client.', 'studio317-report-drafts-google-analytics' ),
				sprintf(
					/* translators: %s: Redirect URI field label. */
					__( 'Use the value displayed in the %s field; do not guess or share the value in support requests.', 'studio317-report-drafts-google-analytics' ),
					__( 'Redirect URI for Google OAuth setup', 'studio317-report-drafts-google-analytics' )
				),
				__( 'Use a Client ID and Client Secret from the same OAuth client.', 'studio317-report-drafts-google-analytics' ),
				__( 'When entering it manually, paste the value into this field and click Save Settings.', 'studio317-report-drafts-google-analytics' ),
				__( 'When wp-config.php or another server configuration is active, that value takes precedence and cannot be viewed, changed, or deleted on this Settings screen.', 'studio317-report-drafts-google-analytics' ),
				__( 'If you cannot edit wp-config.php, you can use the normal input field on this screen.', 'studio317-report-drafts-google-analytics' ),
			),
			"define( 'ANALYTICS_REPORT_AI_GOOGLE_OAUTH_CLIENT_ID', 'YOUR_GOOGLE_OAUTH_CLIENT_ID' );"
		);
	}

	/**
	 * Render the Google OAuth Client Secret help dialog.
	 *
	 * @return void
	 */
	private function render_google_oauth_client_secret_help_dialog() {
		Analytics_Report_AI_Help_Dialog::render_dialog(
			'studio317-report-drafts-google-analytics-google-oauth-client-secret-help',
			__( 'Google OAuth Client Secret setup', 'studio317-report-drafts-google-analytics' ),
			array(
				__( 'Enter the Client Secret from the same Google OAuth client as the Client ID.', 'studio317-report-drafts-google-analytics' ),
				__( 'The Client Secret is sensitive. Do not paste it into email, chat, screenshots, public repositories, or support requests.', 'studio317-report-drafts-google-analytics' ),
				__( 'When entering it manually, paste the value into this field and click Save Settings.', 'studio317-report-drafts-google-analytics' ),
				__( 'When wp-config.php or another server configuration is active, that value takes precedence and cannot be viewed, changed, or deleted on this Settings screen.', 'studio317-report-drafts-google-analytics' ),
				__( 'If you cannot edit wp-config.php, you can use the normal input field on this screen.', 'studio317-report-drafts-google-analytics' ),
			),
			"define( 'ANALYTICS_REPORT_AI_GOOGLE_OAUTH_CLIENT_SECRET', 'YOUR_GOOGLE_OAUTH_CLIENT_SECRET' );"
		);
	}

	/**
	 * Get a user-facing Google OAuth client source label.
	 *
	 * @param string $source_category Internal source category.
	 * @return string
	 */
	private function get_google_oauth_client_source_label( $source_category ) {
		switch ( $source_category ) {
			case 'constants':
				return __( 'Managed by server configuration', 'studio317-report-drafts-google-analytics' );
			case 'settings':
				return __( 'Saved in Settings', 'studio317-report-drafts-google-analytics' );
			case 'conflict':
			case 'incomplete':
				return __( 'Needs review', 'studio317-report-drafts-google-analytics' );
			case 'missing':
			default:
				return __( 'Not configured', 'studio317-report-drafts-google-analytics' );
		}
	}

	/**
	 * Get a user-facing Google OAuth client Settings label.
	 *
	 * @param string $fallback_status Internal Settings fallback status.
	 * @return string
	 */
	private function get_google_oauth_client_fallback_label( $fallback_status ) {
		switch ( $fallback_status ) {
			case 'saved':
				return __( 'Saved and hidden', 'studio317-report-drafts-google-analytics' );
			case 'incomplete':
				return __( 'Partially saved; review both fields', 'studio317-report-drafts-google-analytics' );
			case 'not_saved':
			default:
				return __( 'Not saved', 'studio317-report-drafts-google-analytics' );
		}
	}

	/**
	 * Get a user-facing Google OAuth connection label.
	 *
	 * @param string $connection_state Internal connection state category.
	 * @return string
	 */
	private function get_google_oauth_connection_label( $connection_state ) {
		switch ( $connection_state ) {
			case 'connected':
				return __( 'Connected', 'studio317-report-drafts-google-analytics' );
			case 'expired':
				return __( 'Reconnect required', 'studio317-report-drafts-google-analytics' );
			case 'not_connected':
			default:
				return __( 'Not connected', 'studio317-report-drafts-google-analytics' );
		}
	}

	/**
	 * Get a user-facing Google OAuth token lifecycle label.
	 *
	 * @param string $lifecycle_status Internal token lifecycle status.
	 * @return string
	 */
	private function get_google_oauth_token_lifecycle_label( $lifecycle_status ) {
		switch ( $lifecycle_status ) {
			case 'usable':
				return __( 'Ready for GA4 requests', 'studio317-report-drafts-google-analytics' );
			case 'expired':
			case 'refresh_unavailable':
			case 'reconnect_required':
			default:
				return __( 'Reconnect before fetching GA4 data', 'studio317-report-drafts-google-analytics' );
		}
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
			'connect_placeholder'                   => __( 'Google connection was not started. No Google request was made and no token was saved.', 'studio317-report-drafts-google-analytics' ),
			'connect_state_prepared'                => __( 'Google authorization was prepared. You may be redirected to Google to continue. Token values are not displayed.', 'studio317-report-drafts-google-analytics' ),
			'google_oauth_redirect_client_config_missing' => __( 'Google authorization cannot start because OAuth client settings are not configured. Google was not contacted and no token was saved.', 'studio317-report-drafts-google-analytics' ),
			'google_oauth_redirect_client_config_unavailable' => __( 'Google authorization cannot start because OAuth client settings are missing, incomplete, or need review. Google was not contacted and no token was saved.', 'studio317-report-drafts-google-analytics' ),
			'google_oauth_redirect_url_unavailable' => __( 'Google authorization could not be prepared. Google was not contacted and no token was saved.', 'studio317-report-drafts-google-analytics' ),
			'callback_placeholder'                  => __( 'Google returned to the site, but the connection was not completed. No token was saved.', 'studio317-report-drafts-google-analytics' ),
			'callback_state_missing'                => __( 'Google connection could not be completed because the return request was missing required state information. No token exchange was attempted.', 'studio317-report-drafts-google-analytics' ),
			'callback_state_expired'                => __( 'Google connection could not be completed because the local authorization state expired. Start Google authorization again.', 'studio317-report-drafts-google-analytics' ),
			'callback_state_invalid'                => __( 'Google connection could not be completed because the return request did not match the local authorization state. No token exchange was attempted.', 'studio317-report-drafts-google-analytics' ),
			'callback_state_valid_provider_error'   => __( 'Google returned an authorization error. Raw provider details are not displayed or saved.', 'studio317-report-drafts-google-analytics' ),
			'callback_state_valid_code_present'     => __( 'Google returned authorization information, but it was not displayed or saved directly.', 'studio317-report-drafts-google-analytics' ),
			'callback_state_valid_no_code'          => __( 'Google connection could not be completed because the return request did not include the required authorization result. No token exchange was attempted.', 'studio317-report-drafts-google-analytics' ),
			'token_exchange_success_category'       => __( 'Google OAuth token exchange completed and the OAuth connection state was updated. Token values are not displayed.', 'studio317-report-drafts-google-analytics' ),
			'token_exchange_invalid_grant_category' => __( 'Google connection was not completed because the authorization result was rejected. No token value is displayed.', 'studio317-report-drafts-google-analytics' ),
			'token_exchange_provider_error_category' => __( 'Google connection was not completed because Google returned an error. Raw provider details are not displayed.', 'studio317-report-drafts-google-analytics' ),
			'token_exchange_network_error_category' => __( 'Google connection was not completed because the token request failed. Request and response details are not displayed.', 'studio317-report-drafts-google-analytics' ),
			'token_exchange_malformed_response_category' => __( 'Google connection was not completed because the token response could not be read safely. Raw response details are not displayed.', 'studio317-report-drafts-google-analytics' ),
			'token_exchange_missing_token_category' => __( 'Google connection was not completed because the response did not include the required token data. Raw response details are not displayed.', 'studio317-report-drafts-google-analytics' ),
			'token_exchange_not_executed'           => __( 'Google OAuth token exchange was not executed because the callback preconditions were not complete.', 'studio317-report-drafts-google-analytics' ),
			'token_storage_unavailable_category'    => __( 'Google OAuth token exchange completed, but token storage did not complete. No token value is displayed.', 'studio317-report-drafts-google-analytics' ),
			'google_oauth_local_disconnect_success' => __( 'Google connection data saved by this plugin was deleted. Google was not contacted, provider-side access was not revoked, and OAuth client settings and AI provider configuration were not changed.', 'studio317-report-drafts-google-analytics' ),
			'google_oauth_local_disconnect_failed'  => __( 'Google connection data was not deleted. Token values and option values are not displayed, and provider-side revoke was not requested.', 'studio317-report-drafts-google-analytics' ),
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
