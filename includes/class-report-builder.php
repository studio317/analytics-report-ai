<?php
/**
 * Report Builder admin screen.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Controls the Report Builder screen flow from GA4 fetch to AI generation.
 *
 * @since 0.1.0
 */
final class Analytics_Report_AI_Report_Builder {

	/**
	 * Render Report Builder page.
	 *
	 * @return void
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings          = analytics_report_ai_get_settings();
		$default_period    = analytics_report_ai_get_default_report_period();
		$submission_result = $this->maybe_handle_request();

		$form_values = array(
			'start_date' => $default_period['start_date'],
			'end_date'   => $default_period['end_date'],
			'comparison' => 'previous_month',
			'scope'      => 'site',
			'path'       => '',
		);

		if ( isset( $submission_result['form_values'] ) && is_array( $submission_result['form_values'] ) ) {
			$form_values = wp_parse_args( $submission_result['form_values'], $form_values );
		}

		$ga4_property_id         = isset( $settings['ga4_property_id'] ) ? $settings['ga4_property_id'] : '';
		$host_filter_enabled     = ! empty( $settings['host_filter_enabled'] );
		$host_name               = isset( $settings['host_name'] ) ? $settings['host_name'] : '';
		$credential_source       = analytics_report_ai_resolve_google_ga4_credential_source( $settings );
		$credential_source_label = isset( $credential_source['status'] ) && is_scalar( $credential_source['status'] )
			? (string) $credential_source['status']
			: 'credential_source_missing';
		$credential_connection_status = isset( $credential_source['oauth_connection_status_category'] ) && is_scalar( $credential_source['oauth_connection_status_category'] )
			? (string) $credential_source['oauth_connection_status_category']
			: 'not_connected';
		$credential_lifecycle_status = isset( $credential_source['token_lifecycle_status_category'] ) && is_scalar( $credential_source['token_lifecycle_status_category'] )
			? (string) $credential_source['token_lifecycle_status_category']
			: 'reconnect_required';
		$credential_refresh_status = isset( $credential_source['token_refresh_status_category'] ) && is_scalar( $credential_source['token_refresh_status_category'] )
			? (string) $credential_source['token_refresh_status_category']
			: 'unavailable';
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
		$max_report_days         = analytics_report_ai_get_max_report_days();
		?>
		<div class="wrap analytics-report-ai-admin">
			<h1><?php echo esc_html__( 'Report Builder', 'analytics-report-ai' ); ?></h1>

			<?php $this->render_submission_notices( $submission_result ); ?>

			<div class="analytics-report-ai-card">
				<h2><?php echo esc_html__( 'Current Settings', 'analytics-report-ai' ); ?></h2>

				<table class="widefat striped analytics-report-ai-status-table">
					<tbody>
						<tr>
							<th scope="row"><?php echo esc_html__( 'GA4 Property ID', 'analytics-report-ai' ); ?></th>
							<td>
								<?php if ( '' !== $ga4_property_id ) : ?>
									<code><?php echo esc_html( $ga4_property_id ); ?></code>
								<?php else : ?>
									<span class="analytics-report-ai-status-warning">
										<?php echo esc_html__( 'Not configured', 'analytics-report-ai' ); ?>
									</span>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Host Name Filter', 'analytics-report-ai' ); ?></th>
							<td>
								<?php if ( $host_filter_enabled ) : ?>
									<?php echo esc_html__( 'Enabled', 'analytics-report-ai' ); ?>
								<?php else : ?>
									<?php echo esc_html__( 'Disabled', 'analytics-report-ai' ); ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Host Name', 'analytics-report-ai' ); ?></th>
							<td>
								<?php if ( '' !== $host_name ) : ?>
									<code><?php echo esc_html( $host_name ); ?></code>
								<?php else : ?>
									<span class="analytics-report-ai-status-warning">
										<?php echo esc_html__( 'Not configured', 'analytics-report-ai' ); ?>
									</span>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'GA4 Credential Source', 'analytics-report-ai' ); ?></th>
							<td>
								<?php if ( 'credential_source_missing' === $credential_source_label || 'credential_source_oauth_refresh_needed' === $credential_source_label || 'credential_source_oauth_error_category' === $credential_source_label ) : ?>
									<span class="analytics-report-ai-status-warning">
										<code><?php echo esc_html( $credential_source_label ); ?></code>
									</span>
								<?php else : ?>
									<code><?php echo esc_html( $credential_source_label ); ?></code>
								<?php endif; ?>
								<p class="description">
									<?php echo esc_html__( 'This status is a safe category label. Google OAuth is the normal GA4 credential source, and credential values are hidden.', 'analytics-report-ai' ); ?>
								</p>
								<p class="description">
									<code><?php echo esc_html( 'oauth_connection_status_category: ' . $credential_connection_status ); ?></code>
									<br>
									<code><?php echo esc_html( 'token_lifecycle_status_category: ' . $credential_lifecycle_status ); ?></code>
									<br>
									<code><?php echo esc_html( 'token_refresh_status_category: ' . $credential_refresh_status ); ?></code>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'OpenAI API Key Source', 'analytics-report-ai' ); ?></th>
							<td>
								<?php if ( 'missing' === $openai_api_key_source_category ) : ?>
									<span class="analytics-report-ai-status-warning">
										<code><?php echo esc_html( 'openai_api_key_source_category: ' . $openai_api_key_source_category ); ?></code>
									</span>
								<?php else : ?>
									<code><?php echo esc_html( 'openai_api_key_source_category: ' . $openai_api_key_source_category ); ?></code>
								<?php endif; ?>
								<p class="description">
									<?php echo esc_html__( 'This status is a safe category label. Constant-based configuration is preferred, Settings fallback remains lower priority, and credential values are hidden.', 'analytics-report-ai' ); ?>
								</p>
								<?php if ( 'missing' === $openai_api_key_source_category ) : ?>
									<p class="description">
										<?php echo esc_html__( 'OpenAI report generation needs an OpenAI API key source. Configure the preferred constant source or save the current MVP Settings fallback before generating.', 'analytics-report-ai' ); ?>
									</p>
								<?php endif; ?>
								<p class="description">
									<code><?php echo esc_html( 'openai_api_key_settings_fallback_status: ' . $openai_api_key_settings_fallback_status ); ?></code>
									<br>
									<code><?php echo esc_html( 'openai_api_key_value_visibility: ' . $openai_api_key_value_visibility ); ?></code>
								</p>
							</td>
						</tr>
					</tbody>
				</table>

				<p class="description">
					<?php echo esc_html__( 'This screen uses a two-step flow: fetch GA4 data, review the AI payload, then generate a report draft with the OpenAI API.', 'analytics-report-ai' ); ?>
				</p>
			</div>

			<form method="post" action="" class="analytics-report-ai-card analytics-report-ai-report-form">
				<?php wp_nonce_field( 'analytics_report_ai_report_builder_action', 'analytics_report_ai_report_builder_nonce' ); ?>
				<input type="hidden" name="analytics_report_ai_report_action" value="fetch_ga4_summary" />

				<h2><?php echo esc_html__( 'Report Conditions', 'analytics-report-ai' ); ?></h2>

				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">
								<?php echo esc_html__( 'Date Range', 'analytics-report-ai' ); ?>
							</th>
							<td>
								<label for="analytics-report-ai-start-date">
									<?php echo esc_html__( 'Start Date', 'analytics-report-ai' ); ?>
								</label>
								<input
									type="date"
									id="analytics-report-ai-start-date"
									name="analytics_report_ai_report[start_date]"
									value="<?php echo esc_attr( $form_values['start_date'] ); ?>"
								/>

								<label for="analytics-report-ai-end-date" class="analytics-report-ai-inline-label">
									<?php echo esc_html__( 'End Date', 'analytics-report-ai' ); ?>
								</label>
								<input
									type="date"
									id="analytics-report-ai-end-date"
									name="analytics_report_ai_report[end_date]"
									value="<?php echo esc_attr( $form_values['end_date'] ); ?>"
								/>

								<p class="description">
									<?php echo esc_html__( 'The default date range is the previous month based on the WordPress timezone.', 'analytics-report-ai' ); ?>
								</p>

								<p class="description">
									<?php
									printf(
										/* translators: %d: maximum number of report days. */
										esc_html__( 'For the MVP, the report period is limited to %d days to keep GA4 requests and AI payloads manageable.', 'analytics-report-ai' ),
										(int) $max_report_days
									);
									?>
								</p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<?php echo esc_html__( 'Comparison', 'analytics-report-ai' ); ?>
							</th>
							<td>
								<fieldset>
									<legend class="screen-reader-text">
										<?php echo esc_html__( 'Comparison', 'analytics-report-ai' ); ?>
									</legend>

									<?php foreach ( analytics_report_ai_get_comparison_options() as $value => $label ) : ?>
										<label class="analytics-report-ai-radio-label">
											<input
												type="radio"
												name="analytics_report_ai_report[comparison]"
												value="<?php echo esc_attr( $value ); ?>"
												<?php checked( $form_values['comparison'], $value ); ?>
											/>
											<?php echo esc_html( $label ); ?>
										</label>
									<?php endforeach; ?>
								</fieldset>

								<p class="description">
									<?php echo esc_html__( 'When comparison is enabled, the selected period is shifted to the previous month or previous year and fetched separately from GA4.', 'analytics-report-ai' ); ?>
								</p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<?php echo esc_html__( 'Data Scope', 'analytics-report-ai' ); ?>
							</th>
							<td>
								<fieldset>
									<legend class="screen-reader-text">
										<?php echo esc_html__( 'Data Scope', 'analytics-report-ai' ); ?>
									</legend>

									<?php foreach ( analytics_report_ai_get_scope_options() as $value => $label ) : ?>
										<label class="analytics-report-ai-radio-label">
											<input
												type="radio"
												name="analytics_report_ai_report[scope]"
												value="<?php echo esc_attr( $value ); ?>"
												<?php checked( $form_values['scope'], $value ); ?>
												data-analytics-report-ai-scope
											/>
											<?php echo esc_html( $label ); ?>
										</label>
									<?php endforeach; ?>
								</fieldset>

								<div class="analytics-report-ai-path-field" data-analytics-report-ai-path-field>
									<label for="analytics-report-ai-path">
										<?php echo esc_html__( 'Path', 'analytics-report-ai' ); ?>
									</label>

									<input
										type="text"
										id="analytics-report-ai-path"
										name="analytics_report_ai_report[path]"
										value="<?php echo esc_attr( $form_values['path'] ); ?>"
										class="regular-text"
										placeholder="/blog/"
										data-analytics-report-ai-path-input
									/>

									<p class="description" data-analytics-report-ai-path-description>
										<?php echo esc_html__( 'For directory scope, enter a path such as /blog/. For page scope, enter a path such as /about.', 'analytics-report-ai' ); ?>
									</p>
								</div>

								<p class="description">
									<?php echo esc_html__( 'Site scope covers all paths. Directory scope matches paths that start with the entered path. Page scope matches the exact normalized path. Full URLs are not allowed.', 'analytics-report-ai' ); ?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="analytics-report-ai-info-block">
					<h3><?php echo esc_html__( 'Data sent to Google Analytics Data API', 'analytics-report-ai' ); ?></h3>

					<p>
						<?php echo esc_html__( 'When you click Fetch GA4 Data, the selected date range, comparison setting, data scope, host/path filters, and required metrics/dimensions are sent to the Google Analytics Data API.', 'analytics-report-ai' ); ?>
					</p>

					<ul class="analytics-report-ai-notice-list">
						<li>
							<?php echo esc_html__( 'The OpenAI API Key is not sent to Google.', 'analytics-report-ai' ); ?>
						</li>
						<li>
							<?php echo esc_html__( 'WordPress user information, cookies, and IP addresses are not included in this GA4 request body by design.', 'analytics-report-ai' ); ?>
						</li>
					</ul>
				</div>

				<p>
					<button type="submit" class="button button-primary">
						<?php echo esc_html__( 'Fetch GA4 Data', 'analytics-report-ai' ); ?>
					</button>
				</p>

				<p class="description">
					<?php echo esc_html__( 'Fetch GA4 Data validates the conditions, fetches GA4 preset reports, and creates a Payload Preview.', 'analytics-report-ai' ); ?>
				</p>
			</form>

			<?php $this->render_validated_conditions( $submission_result ); ?>
			<?php $this->render_payload_preview( $submission_result ); ?>
			<?php $this->render_generated_report( $submission_result ); ?>
		</div>
		<?php
	}

	/**
	 * Handle report builder request.
	 *
	 * @return array|null
	 */
	private function maybe_handle_request() {
		if ( empty( $_POST['analytics_report_ai_report_action'] ) ) {
			return null;
		}

		$action = sanitize_key( wp_unslash( $_POST['analytics_report_ai_report_action'] ) );

		if ( ! current_user_can( 'manage_options' ) ) {
			return array(
				'status' => 'error',
				'errors' => array(
					__( 'You do not have permission to perform this action.', 'analytics-report-ai' ),
				),
			);
		}

		$nonce = isset( $_POST['analytics_report_ai_report_builder_nonce'] )
			? sanitize_text_field( wp_unslash( $_POST['analytics_report_ai_report_builder_nonce'] ) )
			: '';

		if ( ! wp_verify_nonce( $nonce, 'analytics_report_ai_report_builder_action' ) ) {
			return array(
				'status' => 'error',
				'errors' => array(
					__( 'Security check failed. Please reload the page and try again.', 'analytics-report-ai' ),
				),
			);
		}

		if ( 'fetch_ga4_summary' === $action ) {
			$report_input = isset( $_POST['analytics_report_ai_report'] ) && is_array( $_POST['analytics_report_ai_report'] )
				? map_deep( wp_unslash( $_POST['analytics_report_ai_report'] ), 'sanitize_text_field' )
				: array();

			return $this->handle_fetch_ga4_summary( $this->normalize_report_input( $report_input ) );
		}

		if ( 'generate_ai_report' === $action ) {
			return $this->handle_generate_ai_report();
		}

		return array(
			'status' => 'error',
			'errors' => array(
				__( 'Invalid action.', 'analytics-report-ai' ),
			),
		);
	}

	/**
	 * Normalize sanitized report condition input.
	 *
	 * @param array $report_input Sanitized report condition input.
	 * @return array
	 */
	private function normalize_report_input( $report_input ) {
		if ( ! is_array( $report_input ) ) {
			return array();
		}

		return array(
			'start_date' => isset( $report_input['start_date'] ) && is_scalar( $report_input['start_date'] ) ? (string) $report_input['start_date'] : '',
			'end_date'   => isset( $report_input['end_date'] ) && is_scalar( $report_input['end_date'] ) ? (string) $report_input['end_date'] : '',
			'comparison' => isset( $report_input['comparison'] ) && is_scalar( $report_input['comparison'] ) ? (string) $report_input['comparison'] : '',
			'scope'      => isset( $report_input['scope'] ) && is_scalar( $report_input['scope'] ) ? (string) $report_input['scope'] : '',
			'path'       => isset( $report_input['path'] ) && is_scalar( $report_input['path'] ) ? (string) $report_input['path'] : '',
		);
	}

	/**
	 * Handle GA4 summary fetching.
	 *
	 * @param array $form_values Sanitized report condition input from a nonce-verified request.
	 * @return array
	 */
	private function handle_fetch_ga4_summary( $form_values ) {
		$validation_result = $this->validate_report_conditions( $form_values );

		if ( 'error' === $validation_result['status'] ) {
			return $validation_result;
		}

		$conditions = $validation_result['conditions'];
		$settings   = analytics_report_ai_get_settings();

		$property_id       = isset( $settings['ga4_property_id'] ) ? (string) $settings['ga4_property_id'] : '';
		$credential_source = analytics_report_ai_resolve_google_ga4_credential_source( $settings );
		$access_token      = isset( $credential_source['access_token'] ) && is_scalar( $credential_source['access_token'] )
			? (string) $credential_source['access_token']
			: '';

		if ( '' === $access_token ) {
			return array(
				'status'      => 'error',
				'errors'      => array(
					$this->get_ga4_credential_source_error_message( $credential_source ),
				),
				'form_values' => $validation_result['form_values'],
			);
		}

		$current_summary = Analytics_Report_AI_GA4_Client::run_summary_report(
			$property_id,
			$access_token,
			$conditions['period'],
			$settings,
			$conditions
		);

		if ( is_wp_error( $current_summary ) ) {
			return array(
				'status'      => 'error',
				'errors'      => array(
					$current_summary->get_error_message(),
				),
				'form_values' => $validation_result['form_values'],
			);
		}

		$comparison_summary = array();

		if ( ! empty( $conditions['comparison_period'] ) && is_array( $conditions['comparison_period'] ) ) {
			$comparison_summary = Analytics_Report_AI_GA4_Client::run_summary_report(
				$property_id,
				$access_token,
				$conditions['comparison_period'],
				$settings,
				$conditions
			);

			if ( is_wp_error( $comparison_summary ) ) {
				return array(
					'status'      => 'error',
					'errors'      => array(
						$comparison_summary->get_error_message(),
					),
					'form_values' => $validation_result['form_values'],
				);
			}
		}

		$preset_reports = array();

		$daily_trend = Analytics_Report_AI_GA4_Client::run_daily_trend_report(
			$property_id,
			$access_token,
			$conditions['period'],
			$settings,
			$conditions
		);

		if ( is_wp_error( $daily_trend ) ) {
			return array(
				'status'      => 'error',
				'errors'      => array(
					$daily_trend->get_error_message(),
				),
				'form_values' => $validation_result['form_values'],
			);
		}

		$preset_reports['daily_trend'] = $daily_trend;

		$top_pages = Analytics_Report_AI_GA4_Client::run_top_pages_report(
			$property_id,
			$access_token,
			$conditions['period'],
			$settings,
			$conditions
		);

		if ( is_wp_error( $top_pages ) ) {
			return array(
				'status'      => 'error',
				'errors'      => array(
					$top_pages->get_error_message(),
				),
				'form_values' => $validation_result['form_values'],
			);
		}

		$preset_reports['top_pages'] = $top_pages;

		$traffic_channels = Analytics_Report_AI_GA4_Client::run_traffic_channels_report(
			$property_id,
			$access_token,
			$conditions['period'],
			$settings,
			$conditions
		);

		if ( is_wp_error( $traffic_channels ) ) {
			return array(
				'status'      => 'error',
				'errors'      => array(
					$traffic_channels->get_error_message(),
				),
				'form_values' => $validation_result['form_values'],
			);
		}

		$preset_reports['traffic_channels'] = $traffic_channels;

		$traffic_sources = Analytics_Report_AI_GA4_Client::run_traffic_sources_report(
			$property_id,
			$access_token,
			$conditions['period'],
			$settings,
			$conditions
		);

		if ( is_wp_error( $traffic_sources ) ) {
			return array(
				'status'      => 'error',
				'errors'      => array(
					$traffic_sources->get_error_message(),
				),
				'form_values' => $validation_result['form_values'],
			);
		}

		$preset_reports['traffic_sources'] = $traffic_sources;

		$regional_trends = Analytics_Report_AI_GA4_Client::run_regional_trends_report(
			$property_id,
			$access_token,
			$conditions['period'],
			$settings,
			$conditions
		);

		if ( is_wp_error( $regional_trends ) ) {
			return array(
				'status'      => 'error',
				'errors'      => array(
					$regional_trends->get_error_message(),
				),
				'form_values' => $validation_result['form_values'],
			);
		}

		$preset_reports['regional_trends'] = $regional_trends;

		$payload = Analytics_Report_AI_Report_Data_Formatter::create_payload_from_ga4_summary(
			$conditions,
			$settings,
			$current_summary,
			$comparison_summary,
			$preset_reports
		);

		$payload_validation = analytics_report_ai_validate_ai_payload( $payload );

		if ( is_wp_error( $payload_validation ) ) {
			return array(
				'status'      => 'error',
				'errors'      => array(
					__( 'The GA4 data could not be converted into a valid AI payload. Please check the settings and try again.', 'analytics-report-ai' ),
				),
				'form_values' => $validation_result['form_values'],
			);
		}

		if ( ! analytics_report_ai_payload_allows_generation( $payload ) ) {
			delete_transient( analytics_report_ai_get_payload_transient_key() );

			return array(
				'status'      => 'no_data_blocked',
				'errors'      => array(
					$this->get_generation_block_message( $payload ),
				),
				'form_values' => $validation_result['form_values'],
			);
		}

		$transient_key = analytics_report_ai_get_payload_transient_key();
		$expiration    = analytics_report_ai_get_payload_transient_expiration();

		set_transient( $transient_key, $payload, $expiration );

		return array(
			'status'             => 'payload_created',
			'conditions'         => $conditions,
			'payload'            => $payload,
			'current_summary'    => $current_summary,
			'comparison_summary' => $comparison_summary,
			'preset_reports'     => $preset_reports,
			'transient_key'      => $transient_key,
			'expiration'         => $expiration,
			'warnings'           => $this->get_payload_warning_messages( $payload ),
			'form_values'        => $validation_result['form_values'],
		);
	}

	/**
	 * Get a safe credential source error message for GA4 fetching.
	 *
	 * @param array $credential_source Credential source result.
	 * @return string
	 */
	private function get_ga4_credential_source_error_message( $credential_source ) {
		$status = isset( $credential_source['status'] ) && is_scalar( $credential_source['status'] )
			? (string) $credential_source['status']
			: 'credential_source_missing';
		$lifecycle_status = isset( $credential_source['token_lifecycle_status_category'] ) && is_scalar( $credential_source['token_lifecycle_status_category'] )
			? (string) $credential_source['token_lifecycle_status_category']
			: 'reconnect_required';
		$refresh_status   = isset( $credential_source['token_refresh_status_category'] ) && is_scalar( $credential_source['token_refresh_status_category'] )
			? (string) $credential_source['token_refresh_status_category']
			: 'unavailable';

		if ( 'credential_source_oauth_refresh_needed' === $status ) {
			return sprintf(
				/* translators: 1: token lifecycle status category. 2: token refresh status category. */
				__( 'Google OAuth credential source needs reconnect before OAuth credential use. Status: token_lifecycle_status_category: %1$s; token_refresh_status_category: %2$s. Refresh requests are deferred in this MVP boundary. Credential values are not displayed.', 'analytics-report-ai' ),
				$lifecycle_status,
				$refresh_status
			);
		}

		if ( 'credential_source_oauth_error_category' === $status ) {
			return sprintf(
				/* translators: 1: token lifecycle status category. 2: token refresh status category. */
				__( 'Google OAuth credential source is not usable. Status: token_lifecycle_status_category: %1$s; token_refresh_status_category: %2$s. Reconnect Google OAuth. Credential values are not displayed.', 'analytics-report-ai' ),
				$lifecycle_status,
				$refresh_status
			);
		}

		return __( 'Missing Google credential. Connect Google OAuth in Settings. Credential values are not displayed.', 'analytics-report-ai' );
	}

	/**
	 * Handle AI report generation.
	 *
	 * @return array
	 */
	private function handle_generate_ai_report() {
		$transient_key = analytics_report_ai_get_payload_transient_key();
		$payload       = get_transient( $transient_key );

		$payload_validation = analytics_report_ai_validate_ai_payload( $payload );

		if ( is_wp_error( $payload_validation ) ) {
			delete_transient( $transient_key );

			return array(
				'status' => 'error',
				'errors' => array(
					__( 'The saved AI payload is missing, expired, or no longer valid. Please fetch GA4 data again.', 'analytics-report-ai' ),
				),
			);
		}

		if ( ! analytics_report_ai_payload_allows_generation( $payload ) ) {
			return array(
				'status'     => 'generation_blocked',
				'errors'     => array(
					$this->get_generation_block_message( $payload ),
				),
				'conditions' => isset( $payload['conditions'] ) && is_array( $payload['conditions'] ) ? $payload['conditions'] : array(),
				'payload'    => $payload,
				'warnings'   => $this->get_payload_warning_messages( $payload ),
			);
		}

		$settings    = analytics_report_ai_get_settings();
		$report_text = Analytics_Report_AI_OpenAI_Client::generate_report( $payload, $settings );

		if ( is_wp_error( $report_text ) ) {
			return array(
				'status'     => 'error',
				'errors'     => array(
					$report_text->get_error_message(),
				),
				'conditions' => isset( $payload['conditions'] ) && is_array( $payload['conditions'] ) ? $payload['conditions'] : array(),
				'payload'    => $payload,
			);
		}

		$conditions = isset( $payload['conditions'] ) && is_array( $payload['conditions'] ) ? $payload['conditions'] : array();

		return array(
			'status'      => 'report_generated',
			'conditions'  => $conditions,
			'payload'     => $payload,
			'report_text' => $report_text,
		);
	}

	/**
	 * Validate report conditions.
	 *
	 * @param array $form_values Form values.
	 * @return array
	 */
	private function validate_report_conditions( $form_values ) {
		$errors = array();

		$start_date = isset( $form_values['start_date'] ) ? $form_values['start_date'] : '';
		$end_date   = isset( $form_values['end_date'] ) ? $form_values['end_date'] : '';

		if ( ! analytics_report_ai_is_valid_date_string( $start_date ) ) {
			$errors[] = __( 'Start date is invalid.', 'analytics-report-ai' );
		}

		if ( ! analytics_report_ai_is_valid_date_string( $end_date ) ) {
			$errors[] = __( 'End date is invalid.', 'analytics-report-ai' );
		}

		if (
			analytics_report_ai_is_valid_date_string( $start_date )
			&& analytics_report_ai_is_valid_date_string( $end_date )
			&& $start_date > $end_date
		) {
			$errors[] = __( 'End date must be the same as or later than start date.', 'analytics-report-ai' );
		}

		if (
			analytics_report_ai_is_valid_date_string( $start_date )
			&& analytics_report_ai_is_valid_date_string( $end_date )
			&& $start_date <= $end_date
		) {
			$report_days     = analytics_report_ai_get_report_period_day_count( $start_date, $end_date );
			$max_report_days = analytics_report_ai_get_max_report_days();

			if ( $report_days > $max_report_days ) {
				$errors[] = sprintf(
					/* translators: %d: maximum report period days. */
					__( 'The report period cannot exceed %d days for the MVP. Please choose a shorter date range.', 'analytics-report-ai' ),
					$max_report_days
				);
			}
		}

		$comparison_options = analytics_report_ai_get_comparison_options();
		$comparison         = isset( $form_values['comparison'] ) ? $form_values['comparison'] : '';

		if ( ! isset( $comparison_options[ $comparison ] ) ) {
			$errors[]   = __( 'Comparison option is invalid.', 'analytics-report-ai' );
			$comparison = 'previous_month';
		}

		$scope_options = analytics_report_ai_get_scope_options();
		$scope         = isset( $form_values['scope'] ) ? $form_values['scope'] : '';

		if ( ! isset( $scope_options[ $scope ] ) ) {
			$errors[] = __( 'Data scope is invalid.', 'analytics-report-ai' );
			$scope    = 'site';
		}

		$path            = isset( $form_values['path'] ) ? $form_values['path'] : '';
		$normalized_path = analytics_report_ai_normalize_report_path( $path, $scope );

		if ( is_wp_error( $normalized_path ) ) {
			$errors[]        = $normalized_path->get_error_message();
			$normalized_path = '';
		}

		if ( ! empty( $errors ) ) {
			return array(
				'status'      => 'error',
				'errors'      => $errors,
				'form_values' => $form_values,
			);
		}

		$form_values['comparison'] = $comparison;
		$form_values['scope']      = $scope;
		$form_values['path']       = $normalized_path;

		$comparison_period = analytics_report_ai_calculate_comparison_period(
			$start_date,
			$end_date,
			$comparison
		);

		if (
			is_array( $comparison_period )
			&& (
				empty( $comparison_period['start_date'] )
				|| empty( $comparison_period['end_date'] )
				|| ! analytics_report_ai_is_valid_date_string( $comparison_period['start_date'] )
				|| ! analytics_report_ai_is_valid_date_string( $comparison_period['end_date'] )
				|| $comparison_period['start_date'] > $comparison_period['end_date']
			)
		) {
			return array(
				'status'      => 'error',
				'errors'      => array(
					__( 'Comparison period could not be calculated. Please choose a different date range or comparison option.', 'analytics-report-ai' ),
				),
				'form_values' => $form_values,
			);
		}

		$conditions = array(
			'period'            => array(
				'start_date' => $start_date,
				'end_date'   => $end_date,
			),
			'comparison'        => $comparison,
			'comparison_label'  => $comparison_options[ $comparison ],
			'comparison_period' => $comparison_period,
			'scope'             => $scope,
			'scope_label'       => $scope_options[ $scope ],
			'path'              => $normalized_path,
		);

		return array(
			'status'      => 'success',
			'conditions'  => $conditions,
			'form_values' => $form_values,
		);
	}

	/**
	 * Render submission notices.
	 *
	 * @param array|null $submission_result Submission result.
	 * @return void
	 */
	private function render_submission_notices( $submission_result ) {
		if ( empty( $submission_result ) || empty( $submission_result['status'] ) ) {
			return;
		}

		if ( 'payload_created' === $submission_result['status'] ) {
			$warnings = isset( $submission_result['warnings'] ) && is_array( $submission_result['warnings'] )
				? $submission_result['warnings']
				: array();

			if ( ! empty( $warnings ) ) {
				?>
				<div class="notice notice-warning">
					<p><strong><?php echo esc_html__( 'GA4 data was fetched and the AI payload was created with warnings.', 'analytics-report-ai' ); ?></strong></p>
					<ul>
						<?php foreach ( $warnings as $warning ) : ?>
							<li><?php echo esc_html( $warning ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php
				return;
			}
			?>
			<div class="notice notice-success">
				<p><?php echo esc_html__( 'GA4 preset reports were fetched and AI payload was created successfully.', 'analytics-report-ai' ); ?></p>
			</div>
			<?php
			return;
		}

		if ( 'report_generated' === $submission_result['status'] ) {
			?>
			<div class="notice notice-success">
				<p><?php echo esc_html__( 'AI report was generated successfully.', 'analytics-report-ai' ); ?></p>
			</div>
			<?php
			return;
		}

		if ( empty( $submission_result['errors'] ) || ! is_array( $submission_result['errors'] ) ) {
			return;
		}
		?>
		<div class="notice notice-error">
			<p><strong><?php echo esc_html__( 'Please fix the following errors.', 'analytics-report-ai' ); ?></strong></p>
			<ul>
				<?php foreach ( $submission_result['errors'] as $error ) : ?>
					<li><?php echo esc_html( $error ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	}

	/**
	 * Render validated conditions.
	 *
	 * @param array|null $submission_result Submission result.
	 * @return void
	 */
	private function render_validated_conditions( $submission_result ) {
		if (
			empty( $submission_result )
			|| empty( $submission_result['status'] )
			|| ! in_array( $submission_result['status'], array( 'payload_created', 'report_generated', 'generation_blocked' ), true )
			|| empty( $submission_result['conditions'] )
			|| ! is_array( $submission_result['conditions'] )
		) {
			return;
		}

		$conditions        = $submission_result['conditions'];
		$comparison_period = isset( $conditions['comparison_period'] ) ? $conditions['comparison_period'] : null;
		?>
		<div class="analytics-report-ai-card">
			<h2><?php echo esc_html__( 'Validated Conditions', 'analytics-report-ai' ); ?></h2>

			<table class="widefat striped analytics-report-ai-status-table">
				<tbody>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Date Range', 'analytics-report-ai' ); ?></th>
						<td>
							<code><?php echo esc_html( $conditions['period']['start_date'] ); ?></code>
							-
							<code><?php echo esc_html( $conditions['period']['end_date'] ); ?></code>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Comparison', 'analytics-report-ai' ); ?></th>
						<td><?php echo esc_html( $conditions['comparison_label'] ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Comparison Period', 'analytics-report-ai' ); ?></th>
						<td>
							<?php if ( is_array( $comparison_period ) ) : ?>
								<code><?php echo esc_html( $comparison_period['start_date'] ); ?></code>
								-
								<code><?php echo esc_html( $comparison_period['end_date'] ); ?></code>
							<?php else : ?>
								<?php echo esc_html__( 'None', 'analytics-report-ai' ); ?>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Data Scope', 'analytics-report-ai' ); ?></th>
						<td><?php echo esc_html( $conditions['scope_label'] ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Normalized Path', 'analytics-report-ai' ); ?></th>
						<td>
							<?php if ( '' !== $conditions['path'] ) : ?>
								<code><?php echo esc_html( $conditions['path'] ); ?></code>
							<?php else : ?>
								<?php echo esc_html__( 'None', 'analytics-report-ai' ); ?>
							<?php endif; ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Render payload preview.
	 *
	 * @param array|null $submission_result Submission result.
	 * @return void
	 */
	private function render_payload_preview( $submission_result ) {
		if (
			empty( $submission_result )
			|| empty( $submission_result['status'] )
			|| ! in_array( $submission_result['status'], array( 'payload_created', 'report_generated', 'generation_blocked' ), true )
			|| empty( $submission_result['payload'] )
			|| ! is_array( $submission_result['payload'] )
		) {
			return;
		}

		$payload    = $submission_result['payload'];
		$expiration = isset( $submission_result['expiration'] ) ? absint( $submission_result['expiration'] ) : analytics_report_ai_get_payload_transient_expiration();
		$generation_allowed = analytics_report_ai_payload_allows_generation( $payload );
		?>
		<div class="analytics-report-ai-card">
			<h2><?php echo esc_html__( 'AI Payload Preview', 'analytics-report-ai' ); ?></h2>

			<p>
				<?php
				printf(
					/* translators: %d: number of minutes the AI payload is saved temporarily. */
					esc_html__( 'The AI payload has been saved temporarily for %d minutes.', 'analytics-report-ai' ),
					(int) floor( $expiration / MINUTE_IN_SECONDS )
				);
				?>
			</p>

			<p class="description">
				<?php echo esc_html__( 'The reviewed AI payload is stored temporarily for this user and expires automatically.', 'analytics-report-ai' ); ?>
			</p>

			<?php $this->render_payload_status_notices( $payload ); ?>

			<div class="analytics-report-ai-info-block">
				<h3><?php echo esc_html__( 'Structured pre-send review', 'analytics-report-ai' ); ?></h3>

				<p>
					<?php echo esc_html__( 'Review this structured summary before sending data to AI.', 'analytics-report-ai' ); ?>
				</p>

				<p>
					<?php echo esc_html__( 'This preview focuses on report conditions, data availability, warnings, and generation readiness.', 'analytics-report-ai' ); ?>
				</p>

				<p>
					<?php echo esc_html__( 'Use the status and warning information below to decide whether to generate a report.', 'analytics-report-ai' ); ?>
				</p>

				<p>
					<?php echo esc_html__( 'The reviewed report data can include host name, page path, traffic channel/source, city, summary metrics, and comparison differences.', 'analytics-report-ai' ); ?>
				</p>

				<p>
					<?php echo esc_html__( 'Credentials are not included in the AI payload. Google Access Token, OpenAI API Key, GA4 Property ID, WordPress user information, cookies, and IP addresses are not included by design.', 'analytics-report-ai' ); ?>
				</p>

				<p>
					<?php echo esc_html__( 'Page paths and traffic sources can still be sensitive business analytics information, so review the payload before sending it.', 'analytics-report-ai' ); ?>
				</p>

				<p>
					<?php echo esc_html__( 'For support, share status/category labels, warning messages, or error categories only. Do not share credentials, option values, request or response bodies, AI payload JSON, generated report text, screenshots, or browser Network evidence.', 'analytics-report-ai' ); ?>
				</p>
			</div>

			<?php $this->render_summary_preview_table( $payload ); ?>
			<?php $this->render_list_preview_table( __( 'Daily Trend', 'analytics-report-ai' ), isset( $payload['daily_trend'] ) ? $payload['daily_trend'] : array() ); ?>
			<?php $this->render_list_preview_table( __( 'Top Pages', 'analytics-report-ai' ), isset( $payload['top_pages'] ) ? $payload['top_pages'] : array() ); ?>
			<?php $this->render_list_preview_table( __( 'Traffic Channels', 'analytics-report-ai' ), isset( $payload['traffic_channels'] ) ? $payload['traffic_channels'] : array() ); ?>
			<?php $this->render_list_preview_table( __( 'Traffic Sources', 'analytics-report-ai' ), isset( $payload['traffic_sources'] ) ? $payload['traffic_sources'] : array() ); ?>
			<?php $this->render_list_preview_table( __( 'Regional Trends', 'analytics-report-ai' ), isset( $payload['regional_trends'] ) ? $payload['regional_trends'] : array() ); ?>

			<div class="analytics-report-ai-info-block">
				<h3><?php echo esc_html__( 'Data sent to OpenAI API', 'analytics-report-ai' ); ?></h3>

				<p>
					<?php echo esc_html__( 'When you click Generate AI Report, the reviewed report data is sent to the OpenAI API.', 'analytics-report-ai' ); ?>
				</p>

				<p>
					<?php echo esc_html__( 'Generating a report sends the reviewed data to OpenAI API and may consume API usage.', 'analytics-report-ai' ); ?>
				</p>

				<p>
					<?php echo esc_html__( 'The generated result is a draft. Review and edit it before publishing, sharing, or sending it.', 'analytics-report-ai' ); ?>
				</p>
			</div>

			<form method="post" action="" class="analytics-report-ai-generate-form" data-analytics-report-ai-single-submit>
				<?php wp_nonce_field( 'analytics_report_ai_report_builder_action', 'analytics_report_ai_report_builder_nonce' ); ?>
				<input type="hidden" name="analytics_report_ai_report_action" value="generate_ai_report" />
				<p>
					<button
						type="submit"
						class="button button-primary"
						data-analytics-report-ai-submit-button
						<?php disabled( ! $generation_allowed ); ?>
						<?php if ( 'report_generated' === $submission_result['status'] ) : ?>
							data-analytics-report-ai-confirm="<?php echo esc_attr__( 'The current report text will be overwritten. Continue?', 'analytics-report-ai' ); ?>"
						<?php endif; ?>
					>
						<?php
						echo 'report_generated' === $submission_result['status']
							? esc_html__( 'Regenerate AI Report', 'analytics-report-ai' )
							: esc_html__( 'Generate AI Report', 'analytics-report-ai' );
						?>
					</button>
				</p>
			</form>

			<p class="description">
				<?php
				echo $generation_allowed
					? esc_html__( 'Use Generate AI Report only after reviewing the structured Payload Preview and any status-level warnings.', 'analytics-report-ai' )
					: esc_html__( 'Generation is blocked because the current-period data is not reportable for the selected conditions.', 'analytics-report-ai' );
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Render payload status warnings near the preview.
	 *
	 * @param array $payload Payload.
	 * @return void
	 */
	private function render_payload_status_notices( $payload ) {
		if ( ! analytics_report_ai_payload_allows_generation( $payload ) ) {
			?>
			<div class="notice notice-error inline">
				<p><?php echo esc_html( $this->get_generation_block_message( $payload ) ); ?></p>
			</div>
			<?php
			return;
		}

		$warnings = $this->get_payload_warning_messages( $payload );

		if ( empty( $warnings ) ) {
			return;
		}
		?>
		<div class="notice notice-warning inline">
			<p><strong><?php echo esc_html__( 'Review these status-level GA4 data warnings before generating a report.', 'analytics-report-ai' ); ?></strong></p>
			<ul>
				<?php foreach ( $warnings as $warning ) : ?>
					<li><?php echo esc_html( $warning ); ?></li>
				<?php endforeach; ?>
			</ul>
			<p><?php echo esc_html__( 'Generation is available, but warnings may limit what the generated draft should claim.', 'analytics-report-ai' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Get a safe user-facing generation block message.
	 *
	 * @param array $payload Payload.
	 * @return string
	 */
	private function get_generation_block_message( $payload ) {
		$reason = isset( $payload['payload_status']['generation_block_reason'] ) && is_scalar( $payload['payload_status']['generation_block_reason'] )
			? sanitize_key( (string) $payload['payload_status']['generation_block_reason'] )
			: '';

		if ( 'current_period_no_data' === $reason ) {
			return __( 'Generation is blocked because the current-period data is not reportable for the selected conditions. Change the date range or scope and fetch GA4 data again.', 'analytics-report-ai' );
		}

		return __( 'AI generation is blocked because the saved payload is not reportable. Fetch GA4 data again before generating a report.', 'analytics-report-ai' );
	}

	/**
	 * Get safe user-facing warning messages from payload metadata.
	 *
	 * @param array $payload Payload.
	 * @return array
	 */
	private function get_payload_warning_messages( $payload ) {
		if ( empty( $payload['payload_status']['warnings'] ) || ! is_array( $payload['payload_status']['warnings'] ) ) {
			return array();
		}

		$messages = array();

		foreach ( $payload['payload_status']['warnings'] as $warning ) {
			if ( ! is_array( $warning ) ) {
				continue;
			}

			$code     = isset( $warning['code'] ) ? sanitize_key( (string) $warning['code'] ) : '';
			$category = isset( $warning['category'] ) ? sanitize_key( (string) $warning['category'] ) : '';

			$messages[] = $this->get_payload_warning_message( $code, $category );
		}

		return array_values( array_unique( array_filter( $messages ) ) );
	}

	/**
	 * Get one safe user-facing warning message.
	 *
	 * @param string $code Warning code.
	 * @param string $category Warning category.
	 * @return string
	 */
	private function get_payload_warning_message( $code, $category ) {
		if ( 'current_period_zero_activity' === $code ) {
			return __( 'The current-period summary contains explicit zero activity. This is treated as measured zero activity, not as a GA4 API error.', 'analytics-report-ai' );
		}

		if ( 'current_summary_partial_metric_values' === $code ) {
			return __( 'Some current-period summary metrics were unavailable, so the report should treat the summary as partial.', 'analytics-report-ai' );
		}

		if ( 'current_summary_missing' === $code ) {
			return __( 'Current-period summary metrics were unavailable, but detail rows were present. Review the partial payload before generating.', 'analytics-report-ai' );
		}

		if ( 'comparison_period_no_data' === $code ) {
			return __( 'Comparison-period data is unavailable. Generated text should avoid comparison claims.', 'analytics-report-ai' );
		}

		if ( 'comparison_period_zero_activity' === $code ) {
			return __( 'The comparison period contains explicit zero activity, so comparison wording may be limited.', 'analytics-report-ai' );
		}

		if ( 'comparison_summary_partial_metric_values' === $code ) {
			return __( 'Some comparison-period summary metrics were unavailable, so comparison wording may be limited.', 'analytics-report-ai' );
		}

		if ( 'detail_preset_empty' === $code ) {
			return sprintf(
				/* translators: %s: safe GA4 preset label. */
				__( '%s rows are unavailable for the selected conditions.', 'analytics-report-ai' ),
				$this->get_payload_warning_category_label( $category )
			);
		}

		return __( 'The payload includes a GA4 data availability warning.', 'analytics-report-ai' );
	}

	/**
	 * Get a safe category label for no-data warnings.
	 *
	 * @param string $category Warning category.
	 * @return string
	 */
	private function get_payload_warning_category_label( $category ) {
		$labels = array(
			'daily_trend'       => __( 'Daily Trend', 'analytics-report-ai' ),
			'top_pages'         => __( 'Top Pages', 'analytics-report-ai' ),
			'traffic_channels'  => __( 'Traffic Channels', 'analytics-report-ai' ),
			'traffic_sources'   => __( 'Traffic Sources', 'analytics-report-ai' ),
			'regional_trends'   => __( 'Regional Trends', 'analytics-report-ai' ),
			'summary'           => __( 'Summary Metrics', 'analytics-report-ai' ),
			'comparison_period' => __( 'Comparison Period', 'analytics-report-ai' ),
		);

		return isset( $labels[ $category ] ) ? $labels[ $category ] : __( 'GA4 detail', 'analytics-report-ai' );
	}

	/**
	 * Render generated report.
	 *
	 * @param array|null $submission_result Submission result.
	 * @return void
	 */
	private function render_generated_report( $submission_result ) {
		if (
			empty( $submission_result )
			|| empty( $submission_result['status'] )
			|| 'report_generated' !== $submission_result['status']
			|| ! isset( $submission_result['report_text'] )
		) {
			return;
		}

		$report_text = (string) $submission_result['report_text'];
		?>
		<div class="analytics-report-ai-card">
			<h2><?php echo esc_html__( 'Generated Report Draft', 'analytics-report-ai' ); ?></h2>

			<p class="description">
				<?php echo esc_html__( 'Review and edit the generated draft before using it.', 'analytics-report-ai' ); ?>
			</p>

			<p class="description">
				<?php echo esc_html__( 'The plugin does not save generated report text.', 'analytics-report-ai' ); ?>
			</p>

			<p class="description">
				<?php echo esc_html__( 'This AI-generated text is a draft. Review and edit it before publishing, sharing, or sending it.', 'analytics-report-ai' ); ?>
			</p>

			<p class="description">
				<?php echo esc_html__( 'Copying the report text is a user-initiated action.', 'analytics-report-ai' ); ?>
			</p>

			<textarea
				id="analytics-report-ai-generated-report"
				class="large-text analytics-report-ai-generated-report"
				rows="18"
				data-analytics-report-ai-report-textarea
			><?php echo esc_textarea( $report_text ); ?></textarea>

			<p class="analytics-report-ai-copy-actions">
				<button
					type="button"
					class="button"
					data-analytics-report-ai-copy-report
				>
					<?php echo esc_html__( 'Copy Report Text', 'analytics-report-ai' ); ?>
				</button>
				<span
					class="analytics-report-ai-copy-status"
					aria-live="polite"
					data-analytics-report-ai-copy-status
				></span>
			</p>
		</div>
		<?php
	}

	/**
	 * Render summary preview table.
	 *
	 * @param array $payload Payload.
	 * @return void
	 */
	private function render_summary_preview_table( $payload ) {
		if ( empty( $payload['summary'] ) || ! is_array( $payload['summary'] ) ) {
			return;
		}
		?>
		<h3><?php echo esc_html__( 'Summary Metrics', 'analytics-report-ai' ); ?></h3>

		<table class="widefat striped analytics-report-ai-preview-table">
			<thead>
				<tr>
					<th><?php echo esc_html__( 'Metric', 'analytics-report-ai' ); ?></th>
					<th><?php echo esc_html__( 'Current', 'analytics-report-ai' ); ?></th>
					<th><?php echo esc_html__( 'Comparison', 'analytics-report-ai' ); ?></th>
					<th><?php echo esc_html__( 'Diff', 'analytics-report-ai' ); ?></th>
					<th><?php echo esc_html__( 'Change Rate', 'analytics-report-ai' ); ?></th>
					<th><?php echo esc_html__( 'Unit', 'analytics-report-ai' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $payload['summary'] as $metric ) : ?>
					<tr>
						<td><?php echo esc_html( isset( $metric['label'] ) ? $metric['label'] : '' ); ?></td>
						<td><?php echo esc_html( isset( $metric['current'] ) ? $this->format_preview_value( $metric['current'] ) : '' ); ?></td>
						<td><?php echo esc_html( isset( $metric['comparison'] ) ? $this->format_preview_value( $metric['comparison'] ) : '-' ); ?></td>
						<td><?php echo esc_html( isset( $metric['diff'] ) ? $this->format_preview_value( $metric['diff'] ) : '-' ); ?></td>
						<td>
							<?php
							echo esc_html(
								isset( $metric['change_rate'] ) && null !== $metric['change_rate']
									? round( (float) $metric['change_rate'] * 100, 1 ) . '%'
									: '-'
							);
							?>
						</td>
						<td><?php echo esc_html( isset( $metric['unit'] ) ? $metric['unit'] : '' ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Render list preview table.
	 *
	 * @param string $title Table title.
	 * @param array  $items Items.
	 * @return void
	 */
	private function render_list_preview_table( $title, $items ) {
		if ( empty( $items ) || ! is_array( $items ) ) {
			return;
		}

		$columns = array_keys( reset( $items ) );
		?>
		<h3><?php echo esc_html( $title ); ?></h3>

		<table class="widefat striped analytics-report-ai-preview-table">
			<thead>
				<tr>
					<?php foreach ( $columns as $column ) : ?>
						<th><?php echo esc_html( $column ); ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $items as $item ) : ?>
					<tr>
						<?php foreach ( $columns as $column ) : ?>
							<td>
								<?php
								echo esc_html(
									isset( $item[ $column ] )
										? $this->format_preview_value( $item[ $column ] )
										: ''
								);
								?>
							</td>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Format preview value.
	 *
	 * @param mixed $value Value.
	 * @return string
	 */
	private function format_preview_value( $value ) {
		if ( is_float( $value ) ) {
			return (string) round( $value, 4 );
		}

		if ( is_int( $value ) ) {
			return number_format_i18n( $value );
		}

		if ( null === $value ) {
			return '-';
		}

		return (string) $value;
	}
}
