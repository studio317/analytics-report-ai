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
		$display_host_name       = '' !== $host_name ? $host_name : analytics_report_ai_get_default_host();
		$credential_source       = analytics_report_ai_resolve_google_ga4_credential_source( $settings );
		$credential_source_label = isset( $credential_source['status'] ) && is_scalar( $credential_source['status'] )
			? (string) $credential_source['status']
			: 'credential_source_missing';
		$credential_lifecycle_status = isset( $credential_source['token_lifecycle_status_category'] ) && is_scalar( $credential_source['token_lifecycle_status_category'] )
			? (string) $credential_source['token_lifecycle_status_category']
			: 'reconnect_required';
		$max_report_days         = analytics_report_ai_get_max_report_days();
		$settings_url            = admin_url( 'admin.php?page=studio317-report-drafts-google-analytics-settings' );
		$ga4_connection_label    = $this->get_ga4_connection_label( $credential_source_label, $credential_lifecycle_status );
		$ga4_connection_ready    = $this->is_ga4_connection_ready( $credential_source_label, $credential_lifecycle_status );
		$ai_client_runtime_ready = function_exists( 'wp_ai_client_prompt' );
		$ai_generation_label     = $this->get_ai_generation_readiness_label( $ai_client_runtime_ready );
		$planned_report_language = analytics_report_ai_get_report_language_profile();
		?>
		<div class="wrap studio317-report-drafts-google-analytics-admin">
			<h1><?php echo esc_html__( 'Report Builder', 'studio317-report-drafts-google-analytics' ); ?></h1>

			<?php $this->render_submission_notices( $submission_result ); ?>

			<div class="studio317-report-drafts-google-analytics-card">
				<h2>
					<?php
					printf(
						wp_kses(
							/* translators: %s: Settings screen link. */
							__( 'Current setup (configure %s first)', 'studio317-report-drafts-google-analytics' ),
							array(
								'a' => array(
									'href' => array(),
								),
							)
						),
						'<a href="' . esc_url( $settings_url ) . '">' . esc_html__( 'Settings', 'studio317-report-drafts-google-analytics' ) . '</a>'
					);
					?>
				</h2>

				<table class="widefat striped studio317-report-drafts-google-analytics-status-table">
					<tbody>
						<tr>
							<th scope="row"><?php echo esc_html__( 'GA4 Property ID', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td>
								<?php if ( '' !== $ga4_property_id ) : ?>
									<code><?php echo esc_html( $ga4_property_id ); ?></code>
								<?php else : ?>
									<span class="studio317-report-drafts-google-analytics-status-warning">
										<?php echo esc_html__( 'Not configured', 'studio317-report-drafts-google-analytics' ); ?>
									</span>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Host Name Filter', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td>
								<?php if ( $host_filter_enabled ) : ?>
									<?php echo esc_html__( 'Enabled', 'studio317-report-drafts-google-analytics' ); ?>
								<?php else : ?>
									<?php echo esc_html__( 'Not in use', 'studio317-report-drafts-google-analytics' ); ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Host Name', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td>
								<?php if ( '' !== $display_host_name ) : ?>
									<code><?php echo esc_html( $display_host_name ); ?></code>
								<?php else : ?>
									<span class="studio317-report-drafts-google-analytics-status-warning">
										<?php echo esc_html__( 'Not configured', 'studio317-report-drafts-google-analytics' ); ?>
									</span>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Google connection', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td>
								<?php if ( ! $ga4_connection_ready ) : ?>
									<span class="studio317-report-drafts-google-analytics-status-warning">
										<?php echo esc_html( $ga4_connection_label ); ?>
									</span>
								<?php else : ?>
									<?php echo esc_html( $ga4_connection_label ); ?>
								<?php endif; ?>
								<p class="description">
									<?php echo esc_html__( 'Connect or reconnect Google in Settings before fetching GA4 data. Token values are hidden.', 'studio317-report-drafts-google-analytics' ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'AI generation provider', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td>
								<?php if ( ! $ai_client_runtime_ready ) : ?>
									<span class="studio317-report-drafts-google-analytics-status-warning">
										<?php echo esc_html( $ai_generation_label ); ?>
									</span>
								<?php else : ?>
									<?php echo esc_html( $ai_generation_label ); ?>
								<?php endif; ?>
								<p class="description">
									<?php echo esc_html__( 'AI generation uses the provider configured by the site administrator in WordPress Settings > Connectors. Provider credentials and model details are not displayed by this plugin.', 'studio317-report-drafts-google-analytics' ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Report language', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td>
								<?php $this->render_report_language_summary( $planned_report_language ); ?>
								<p class="description">
									<?php echo esc_html__( 'The report draft language is resolved from the current WordPress user language, then the site language, then English. The WordPress timezone is not used to choose the report language.', 'studio317-report-drafts-google-analytics' ); ?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>

				<p class="description">
					<?php echo esc_html__( 'This screen uses a two-step flow: fetch GA4 data first, review the Data Preview, then generate a report draft in the current WordPress user language through the WordPress AI Client.', 'studio317-report-drafts-google-analytics' ); ?>
				</p>
			</div>

			<form method="post" action="" class="studio317-report-drafts-google-analytics-card studio317-report-drafts-google-analytics-report-form">
				<?php wp_nonce_field( 'analytics_report_ai_report_builder_action', 'analytics_report_ai_report_builder_nonce' ); ?>
				<input type="hidden" name="analytics_report_ai_report_action" value="fetch_ga4_summary" />

				<h2><?php echo esc_html__( 'Report Conditions', 'studio317-report-drafts-google-analytics' ); ?></h2>

				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">
								<?php echo esc_html__( 'Date Range', 'studio317-report-drafts-google-analytics' ); ?>
							</th>
							<td>
								<label for="studio317-report-drafts-google-analytics-start-date">
									<?php echo esc_html__( 'Start Date', 'studio317-report-drafts-google-analytics' ); ?>
								</label>
								<input
									type="date"
									id="studio317-report-drafts-google-analytics-start-date"
									name="analytics_report_ai_report[start_date]"
									value="<?php echo esc_attr( $form_values['start_date'] ); ?>"
								/>

								<label for="studio317-report-drafts-google-analytics-end-date" class="studio317-report-drafts-google-analytics-inline-label">
									<?php echo esc_html__( 'End Date', 'studio317-report-drafts-google-analytics' ); ?>
								</label>
								<input
									type="date"
									id="studio317-report-drafts-google-analytics-end-date"
									name="analytics_report_ai_report[end_date]"
									value="<?php echo esc_attr( $form_values['end_date'] ); ?>"
								/>

								<p class="description">
									<?php echo esc_html__( 'The default date range is the previous month based on the WordPress timezone.', 'studio317-report-drafts-google-analytics' ); ?>
								</p>

								<p class="description">
									<?php
									printf(
										/* translators: %d: maximum number of report days. */
										esc_html__( 'The report period is limited to %d days to keep GA4 requests and report data manageable.', 'studio317-report-drafts-google-analytics' ),
										(int) $max_report_days
									);
									?>
								</p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<?php echo esc_html__( 'Comparison', 'studio317-report-drafts-google-analytics' ); ?>
							</th>
							<td>
								<fieldset>
									<legend class="screen-reader-text">
										<?php echo esc_html__( 'Comparison', 'studio317-report-drafts-google-analytics' ); ?>
									</legend>

									<?php foreach ( analytics_report_ai_get_comparison_options() as $value => $label ) : ?>
										<label class="studio317-report-drafts-google-analytics-radio-label">
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
									<?php echo esc_html__( 'When comparison is enabled, the selected period is shifted to the previous month or previous year and fetched separately from GA4.', 'studio317-report-drafts-google-analytics' ); ?>
								</p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<?php echo esc_html__( 'Data Scope', 'studio317-report-drafts-google-analytics' ); ?>
							</th>
							<td>
								<fieldset>
									<legend class="screen-reader-text">
										<?php echo esc_html__( 'Data Scope', 'studio317-report-drafts-google-analytics' ); ?>
									</legend>

									<?php foreach ( analytics_report_ai_get_scope_options() as $value => $label ) : ?>
										<label class="studio317-report-drafts-google-analytics-radio-label">
											<input
												type="radio"
												name="analytics_report_ai_report[scope]"
												value="<?php echo esc_attr( $value ); ?>"
												<?php checked( $form_values['scope'], $value ); ?>
												data-studio317-report-drafts-google-analytics-scope
											/>
											<?php echo esc_html( $label ); ?>
										</label>
									<?php endforeach; ?>
								</fieldset>

								<div class="studio317-report-drafts-google-analytics-path-field" data-studio317-report-drafts-google-analytics-path-field>
									<label for="studio317-report-drafts-google-analytics-path">
										<?php echo esc_html__( 'Path', 'studio317-report-drafts-google-analytics' ); ?>
									</label>

									<input
										type="text"
										id="studio317-report-drafts-google-analytics-path"
										name="analytics_report_ai_report[path]"
										value="<?php echo esc_attr( $form_values['path'] ); ?>"
										class="regular-text"
										placeholder="/blog/"
										data-studio317-report-drafts-google-analytics-path-input
									/>

									<p class="description" data-studio317-report-drafts-google-analytics-path-description>
										<?php echo esc_html__( 'For directory scope, enter a path such as /blog/. For page scope, enter a path such as /about.', 'studio317-report-drafts-google-analytics' ); ?>
									</p>
								</div>

								<p class="description">
									<?php echo esc_html__( 'Site scope covers all paths. Directory scope matches paths that start with the entered path. Page scope matches the exact normalized path. Full URLs are not allowed.', 'studio317-report-drafts-google-analytics' ); ?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="studio317-report-drafts-google-analytics-info-block">
					<h3><?php echo esc_html__( 'Data sent to Google Analytics Data API', 'studio317-report-drafts-google-analytics' ); ?></h3>

					<p>
						<?php echo esc_html__( 'When you click Fetch GA4 Data, the selected date range, comparison setting, data scope, host name/path filters, and required metrics/dimensions are sent to the Google Analytics Data API.', 'studio317-report-drafts-google-analytics' ); ?>
					</p>

					<ul class="studio317-report-drafts-google-analytics-notice-list">
						<li>
							<?php echo esc_html__( 'AI provider credentials are not sent to Google by this plugin.', 'studio317-report-drafts-google-analytics' ); ?>
						</li>
						<li>
							<?php echo esc_html__( 'WordPress user identifiers, cookies, and IP addresses are not included in this GA4 request body by design.', 'studio317-report-drafts-google-analytics' ); ?>
						</li>
					</ul>
				</div>

				<p>
					<button type="submit" class="button button-primary">
						<?php echo esc_html__( 'Fetch GA4 Data', 'studio317-report-drafts-google-analytics' ); ?>
					</button>
				</p>

				<p class="description">
					<?php echo esc_html__( 'Fetch GA4 Data validates the conditions, fetches GA4 preset reports, and creates a Data Preview.', 'studio317-report-drafts-google-analytics' ); ?>
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
					__( 'You do not have permission to perform this action.', 'studio317-report-drafts-google-analytics' ),
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
					__( 'Security check failed. Please reload the page and try again.', 'studio317-report-drafts-google-analytics' ),
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
				__( 'Invalid action.', 'studio317-report-drafts-google-analytics' ),
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
			$preset_reports,
			analytics_report_ai_get_report_language_profile()
		);

		$payload_validation = analytics_report_ai_validate_ai_payload( $payload );

		if ( is_wp_error( $payload_validation ) ) {
			return array(
				'status'      => 'error',
				'errors'      => array(
					__( 'The GA4 data could not be converted into valid data for AI generation. Please check the settings and try again.', 'studio317-report-drafts-google-analytics' ),
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
		if ( 'credential_source_oauth_refresh_needed' === $status ) {
			return __( 'Google connection needs to be renewed before GA4 data can be fetched. Open Settings, reconnect Google, and try again. Credential values are not displayed.', 'studio317-report-drafts-google-analytics' );
		}

		if ( 'credential_source_oauth_error_category' === $status ) {
			return __( 'Google connection is not ready for GA4 requests. Open Settings, reconnect Google, and try again. Credential values are not displayed.', 'studio317-report-drafts-google-analytics' );
		}

		return __( 'Google connection is not configured. Open Settings, configure Google OAuth, connect your Google account, and try again. Credential values are not displayed.', 'studio317-report-drafts-google-analytics' );
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
					__( 'The saved report data is missing, expired, or no longer valid. Please fetch GA4 data again.', 'studio317-report-drafts-google-analytics' ),
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

		$report_text = Analytics_Report_AI_AI_Client::generate_report( $payload );

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
			$errors[] = __( 'Start date is invalid.', 'studio317-report-drafts-google-analytics' );
		}

		if ( ! analytics_report_ai_is_valid_date_string( $end_date ) ) {
			$errors[] = __( 'End date is invalid.', 'studio317-report-drafts-google-analytics' );
		}

		if (
			analytics_report_ai_is_valid_date_string( $start_date )
			&& analytics_report_ai_is_valid_date_string( $end_date )
			&& $start_date > $end_date
		) {
			$errors[] = __( 'End date must be the same as or later than start date.', 'studio317-report-drafts-google-analytics' );
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
					__( 'The report period cannot exceed %d days. Please choose a shorter date range.', 'studio317-report-drafts-google-analytics' ),
					$max_report_days
				);
			}
		}

		$comparison_options = analytics_report_ai_get_comparison_options();
		$comparison         = isset( $form_values['comparison'] ) ? $form_values['comparison'] : '';

		if ( ! isset( $comparison_options[ $comparison ] ) ) {
			$errors[]   = __( 'Comparison option is invalid.', 'studio317-report-drafts-google-analytics' );
			$comparison = 'previous_month';
		}

		$scope_options = analytics_report_ai_get_scope_options();
		$scope         = isset( $form_values['scope'] ) ? $form_values['scope'] : '';

		if ( ! isset( $scope_options[ $scope ] ) ) {
			$errors[] = __( 'Data scope is invalid.', 'studio317-report-drafts-google-analytics' );
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
					__( 'Comparison period could not be calculated. Please choose a different date range or comparison option.', 'studio317-report-drafts-google-analytics' ),
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
					<p><strong><?php echo esc_html__( 'GA4 data was fetched and the data for AI generation was prepared with warnings.', 'studio317-report-drafts-google-analytics' ); ?></strong></p>
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
				<p><?php echo esc_html__( 'GA4 preset reports were fetched and the data for AI generation was prepared successfully.', 'studio317-report-drafts-google-analytics' ); ?></p>
			</div>
			<?php
			return;
		}

		if ( 'report_generated' === $submission_result['status'] ) {
			?>
			<div class="notice notice-success">
				<p>
					<?php echo esc_html__( 'AI report was generated.', 'studio317-report-drafts-google-analytics' ); ?>
					<a href="#studio317-report-drafts-google-analytics-generated-report-section"><?php echo esc_html__( 'View the generated report draft.', 'studio317-report-drafts-google-analytics' ); ?></a>
				</p>
			</div>
			<?php
			return;
		}

		if ( empty( $submission_result['errors'] ) || ! is_array( $submission_result['errors'] ) ) {
			return;
		}
		?>
		<div class="notice notice-error">
			<p><strong><?php echo esc_html__( 'Please fix the following errors.', 'studio317-report-drafts-google-analytics' ); ?></strong></p>
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
		<div class="studio317-report-drafts-google-analytics-card">
			<h2><?php echo esc_html__( 'Validated Conditions', 'studio317-report-drafts-google-analytics' ); ?></h2>

			<table class="widefat striped studio317-report-drafts-google-analytics-status-table">
				<tbody>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Date Range', 'studio317-report-drafts-google-analytics' ); ?></th>
						<td>
							<code><?php echo esc_html( $conditions['period']['start_date'] ); ?></code>
							-
							<code><?php echo esc_html( $conditions['period']['end_date'] ); ?></code>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Comparison', 'studio317-report-drafts-google-analytics' ); ?></th>
						<td><?php echo esc_html( $conditions['comparison_label'] ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Comparison Period', 'studio317-report-drafts-google-analytics' ); ?></th>
						<td>
							<?php if ( is_array( $comparison_period ) ) : ?>
								<code><?php echo esc_html( $comparison_period['start_date'] ); ?></code>
								-
								<code><?php echo esc_html( $comparison_period['end_date'] ); ?></code>
							<?php else : ?>
								<?php echo esc_html__( 'None', 'studio317-report-drafts-google-analytics' ); ?>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Data Scope', 'studio317-report-drafts-google-analytics' ); ?></th>
						<td><?php echo esc_html( $conditions['scope_label'] ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Normalized Path', 'studio317-report-drafts-google-analytics' ); ?></th>
						<td>
							<?php if ( '' !== $conditions['path'] ) : ?>
								<code><?php echo esc_html( $conditions['path'] ); ?></code>
							<?php else : ?>
								<?php echo esc_html__( 'None', 'studio317-report-drafts-google-analytics' ); ?>
							<?php endif; ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Render the report language selected for AI generation.
	 *
	 * @param array $report_language Report language profile.
	 * @return void
	 */
	private function render_report_language_summary( $report_language ) {
		if ( is_wp_error( analytics_report_ai_validate_report_language_profile( $report_language ) ) ) {
			$report_language = analytics_report_ai_get_report_language_profile_from_locale( 'en_US', 'default_locale' );
		}

		$language_name = analytics_report_ai_get_report_language_display_name( $report_language );
		$output_locale = isset( $report_language['output_locale'] ) && is_scalar( $report_language['output_locale'] )
			? (string) $report_language['output_locale']
			: 'en-US';

		printf(
			/* translators: 1: report language name, 2: output locale. */
			esc_html__( 'Report language: %1$s (%2$s)', 'studio317-report-drafts-google-analytics' ),
			esc_html( $language_name ),
			esc_html( $output_locale )
		);
	}

	/**
	 * Render data preview.
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
		$ai_text_generation_available = $generation_allowed && Analytics_Report_AI_AI_Client::is_text_generation_available( $payload );
		$generate_button_available    = $generation_allowed && $ai_text_generation_available;
		?>
		<div class="studio317-report-drafts-google-analytics-card">
			<h2><?php echo esc_html__( 'AI Data Preview', 'studio317-report-drafts-google-analytics' ); ?></h2>

			<p>
				<?php
				printf(
					/* translators: %d: number of minutes the report data is saved temporarily. */
					esc_html__( 'The data to be sent through the WordPress AI Client has been saved temporarily for %d minutes.', 'studio317-report-drafts-google-analytics' ),
					(int) floor( $expiration / MINUTE_IN_SECONDS )
				);
				?>
			</p>

			<p class="description">
				<?php echo esc_html__( 'The reviewed report data is stored temporarily for this user and expires automatically.', 'studio317-report-drafts-google-analytics' ); ?>
			</p>

			<?php $this->render_payload_status_notices( $payload ); ?>

			<p>
				<?php
				$this->render_report_language_summary(
					isset( $payload['report_language'] ) && is_array( $payload['report_language'] )
						? $payload['report_language']
						: array()
				);
				?>
			</p>

			<div class="studio317-report-drafts-google-analytics-info-block">
				<h3><?php echo esc_html__( 'Structured pre-send review', 'studio317-report-drafts-google-analytics' ); ?></h3>

				<p>
					<?php echo esc_html__( 'Review this structured summary before sending data to AI.', 'studio317-report-drafts-google-analytics' ); ?>
				</p>

				<p>
					<?php echo esc_html__( 'This preview focuses on report conditions, data availability, warnings, and generation readiness.', 'studio317-report-drafts-google-analytics' ); ?>
				</p>

				<p>
					<?php echo esc_html__( 'Use the status and warning information below to decide whether to generate a report.', 'studio317-report-drafts-google-analytics' ); ?>
				</p>

				<p>
					<?php echo esc_html__( 'The reviewed report data can include host name, page path, traffic channel/source, city, summary metrics, and comparison differences.', 'studio317-report-drafts-google-analytics' ); ?>
				</p>

				<p>
					<?php echo esc_html__( 'Credentials are not included in the data sent through the WordPress AI Client. Google access tokens, AI provider credentials, GA4 Property ID, WordPress user identifiers, cookies, and IP addresses are not included by design.', 'studio317-report-drafts-google-analytics' ); ?>
				</p>

				<p>
					<?php echo esc_html__( 'Page paths and traffic sources can still be sensitive business analytics information, so review the data before sending it.', 'studio317-report-drafts-google-analytics' ); ?>
				</p>

				<p>
					<?php echo esc_html__( 'For support, share visible status messages, warning messages, or general error names only. Do not share credentials, option values, request or response bodies, AI data JSON, generated report text, screenshots, or browser Network evidence.', 'studio317-report-drafts-google-analytics' ); ?>
				</p>
			</div>

			<?php $this->render_summary_preview_table( $payload ); ?>
			<?php $this->render_list_preview_table( __( 'Daily Trend', 'studio317-report-drafts-google-analytics' ), isset( $payload['daily_trend'] ) ? $payload['daily_trend'] : array() ); ?>
			<?php $this->render_list_preview_table( __( 'Top Pages', 'studio317-report-drafts-google-analytics' ), isset( $payload['top_pages'] ) ? $payload['top_pages'] : array() ); ?>
			<?php $this->render_list_preview_table( __( 'Traffic Channels', 'studio317-report-drafts-google-analytics' ), isset( $payload['traffic_channels'] ) ? $payload['traffic_channels'] : array() ); ?>
			<?php $this->render_list_preview_table( __( 'Traffic Sources', 'studio317-report-drafts-google-analytics' ), isset( $payload['traffic_sources'] ) ? $payload['traffic_sources'] : array() ); ?>
			<?php $this->render_list_preview_table( __( 'Regional Trends', 'studio317-report-drafts-google-analytics' ), isset( $payload['regional_trends'] ) ? $payload['regional_trends'] : array() ); ?>

			<div class="studio317-report-drafts-google-analytics-info-block">
				<h3><?php echo esc_html__( 'Data sent through the WordPress AI Client', 'studio317-report-drafts-google-analytics' ); ?></h3>

				<p>
					<?php echo esc_html__( 'When you generate an AI report, the reviewed report data and selected report language are sent through the WordPress AI Client to the AI provider configured by the site administrator.', 'studio317-report-drafts-google-analytics' ); ?>
				</p>

				<p>
					<?php echo esc_html__( 'Generating a report may consume usage with the configured AI provider. Provider terms, privacy practices, billing, retention, and credential management depend on the provider configured through WordPress.', 'studio317-report-drafts-google-analytics' ); ?>
				</p>

				<p>
					<?php echo esc_html__( 'The generated result is a draft. Review and edit it before publishing, sharing, or sending it.', 'studio317-report-drafts-google-analytics' ); ?>
				</p>
			</div>

			<form method="post" action="" class="studio317-report-drafts-google-analytics-generate-form" data-studio317-report-drafts-google-analytics-single-submit>
				<?php wp_nonce_field( 'analytics_report_ai_report_builder_action', 'analytics_report_ai_report_builder_nonce' ); ?>
				<input type="hidden" name="analytics_report_ai_report_action" value="generate_ai_report" />
				<p>
					<button
						type="submit"
						class="button button-primary"
						data-studio317-report-drafts-google-analytics-submit-button
						<?php disabled( ! $generate_button_available ); ?>
						<?php if ( 'report_generated' === $submission_result['status'] ) : ?>
							data-studio317-report-drafts-google-analytics-confirm="<?php echo esc_attr__( 'The current report text will be overwritten. Continue?', 'studio317-report-drafts-google-analytics' ); ?>"
						<?php endif; ?>
					>
						<?php
						echo 'report_generated' === $submission_result['status']
							? esc_html__( 'Regenerate AI Report', 'studio317-report-drafts-google-analytics' )
							: esc_html__( 'Generate AI Report', 'studio317-report-drafts-google-analytics' );
						?>
					</button>
				</p>
			</form>

			<p class="description">
				<?php
				if ( ! $generation_allowed ) {
					echo esc_html__( 'AI generation is not available because the current-period data is not reportable for the selected conditions.', 'studio317-report-drafts-google-analytics' );
				} elseif ( ! $ai_text_generation_available ) {
					echo esc_html__( 'AI text generation is unavailable. Configure a compatible text-generation provider in WordPress Settings > Connectors before generating a report draft.', 'studio317-report-drafts-google-analytics' );
				} else {
					echo esc_html__( 'Use Generate AI Report only after reviewing the structured Data Preview and any visible warnings. The report draft is generated through the WordPress AI Client.', 'studio317-report-drafts-google-analytics' );
				}
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
			<p><strong><?php echo esc_html__( 'Review these GA4 data warnings before generating a report.', 'studio317-report-drafts-google-analytics' ); ?></strong></p>
			<ul>
				<?php foreach ( $warnings as $warning ) : ?>
					<li><?php echo esc_html( $warning ); ?></li>
				<?php endforeach; ?>
			</ul>
			<p><?php echo esc_html__( 'Generation is available, but warnings may limit what the generated draft should claim.', 'studio317-report-drafts-google-analytics' ); ?></p>
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
		return __( 'AI generation is not available because the current-period data is not reportable for the selected conditions. Change the date range or scope and fetch GA4 data again.', 'studio317-report-drafts-google-analytics' );
		}

		return __( 'AI generation is not available because the saved report data is not reportable. Fetch GA4 data again before generating a report.', 'studio317-report-drafts-google-analytics' );
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
			return __( 'The current-period summary contains explicit zero activity. This is treated as measured zero activity, not as a GA4 API error.', 'studio317-report-drafts-google-analytics' );
		}

		if ( 'current_summary_partial_metric_values' === $code ) {
			return __( 'Some current-period summary metrics were unavailable, so the report should treat the summary as partial.', 'studio317-report-drafts-google-analytics' );
		}

		if ( 'current_summary_missing' === $code ) {
			return __( 'Current-period summary metrics were unavailable, but detail rows were present. Review the partial report data before generating.', 'studio317-report-drafts-google-analytics' );
		}

		if ( 'comparison_period_no_data' === $code ) {
			return __( 'Comparison-period data is unavailable. Generated text should avoid comparison claims.', 'studio317-report-drafts-google-analytics' );
		}

		if ( 'comparison_period_zero_activity' === $code ) {
			return __( 'The comparison period contains explicit zero activity, so comparison wording may be limited.', 'studio317-report-drafts-google-analytics' );
		}

		if ( 'comparison_summary_partial_metric_values' === $code ) {
			return __( 'Some comparison-period summary metrics were unavailable, so comparison wording may be limited.', 'studio317-report-drafts-google-analytics' );
		}

		if ( 'detail_preset_empty' === $code ) {
			return sprintf(
				/* translators: %s: safe GA4 preset label. */
				__( '%s rows are unavailable for the selected conditions.', 'studio317-report-drafts-google-analytics' ),
				$this->get_payload_warning_category_label( $category )
			);
		}

		return __( 'The report data includes a GA4 data availability warning.', 'studio317-report-drafts-google-analytics' );
	}

	/**
	 * Get a safe category label for no-data warnings.
	 *
	 * @param string $category Warning category.
	 * @return string
	 */
	private function get_payload_warning_category_label( $category ) {
		$labels = array(
			'daily_trend'       => __( 'Daily Trend', 'studio317-report-drafts-google-analytics' ),
			'top_pages'         => __( 'Top Pages', 'studio317-report-drafts-google-analytics' ),
			'traffic_channels'  => __( 'Traffic Channels', 'studio317-report-drafts-google-analytics' ),
			'traffic_sources'   => __( 'Traffic Sources', 'studio317-report-drafts-google-analytics' ),
			'regional_trends'   => __( 'Regional Trends', 'studio317-report-drafts-google-analytics' ),
			'summary'           => __( 'Summary Metrics', 'studio317-report-drafts-google-analytics' ),
			'comparison_period' => __( 'Comparison Period', 'studio317-report-drafts-google-analytics' ),
		);

		return isset( $labels[ $category ] ) ? $labels[ $category ] : __( 'GA4 detail', 'studio317-report-drafts-google-analytics' );
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
		<div id="studio317-report-drafts-google-analytics-generated-report-section" class="studio317-report-drafts-google-analytics-card">
			<h2><?php echo esc_html__( 'Generated Report Draft', 'studio317-report-drafts-google-analytics' ); ?></h2>

			<p class="description">
				<?php echo esc_html__( 'Review and edit the generated draft before using it.', 'studio317-report-drafts-google-analytics' ); ?>
			</p>

			<p class="description">
				<?php echo esc_html__( 'The plugin does not save generated report text.', 'studio317-report-drafts-google-analytics' ); ?>
			</p>

			<p class="description">
				<?php echo esc_html__( 'This AI-generated text is a draft. Review and edit it before publishing, sharing, or sending it.', 'studio317-report-drafts-google-analytics' ); ?>
			</p>

			<p class="description">
				<?php echo esc_html__( 'Copying the report text is a user-initiated action.', 'studio317-report-drafts-google-analytics' ); ?>
			</p>

			<textarea
				id="studio317-report-drafts-google-analytics-generated-report"
				class="large-text studio317-report-drafts-google-analytics-generated-report"
				rows="18"
				data-studio317-report-drafts-google-analytics-report-textarea
			><?php echo esc_textarea( $report_text ); ?></textarea>

			<p class="studio317-report-drafts-google-analytics-copy-actions">
				<button
					type="button"
					class="button"
					data-studio317-report-drafts-google-analytics-copy-report
				>
					<?php echo esc_html__( 'Copy Report Text', 'studio317-report-drafts-google-analytics' ); ?>
				</button>
				<span
					class="studio317-report-drafts-google-analytics-copy-status"
					aria-live="polite"
					data-studio317-report-drafts-google-analytics-copy-status
				></span>
			</p>
		</div>
		<?php
	}

	/**
	 * Get a user-facing GA4 connection label.
	 *
	 * @param string $credential_source_label Internal credential source label.
	 * @param string $lifecycle_status        Internal token lifecycle status.
	 * @return string
	 */
	private function get_ga4_connection_label( $credential_source_label, $lifecycle_status ) {
		if ( $this->is_ga4_connection_ready( $credential_source_label, $lifecycle_status ) ) {
			return __( 'Ready to fetch GA4 data', 'studio317-report-drafts-google-analytics' );
		}

		if ( $this->is_ga4_connection_reconnect_required( $credential_source_label, $lifecycle_status ) ) {
			return __( 'Reconnect Google before fetching GA4 data', 'studio317-report-drafts-google-analytics' );
		}

		return __( 'Not connected', 'studio317-report-drafts-google-analytics' );
	}

	/**
	 * Check whether the GA4 OAuth connection is usable.
	 *
	 * @param string $credential_source_label Internal credential source label.
	 * @param string $lifecycle_status        Internal token lifecycle status.
	 * @return bool
	 */
	private function is_ga4_connection_ready( $credential_source_label, $lifecycle_status ) {
		return 'credential_source_oauth_connected' === $credential_source_label && 'usable' === $lifecycle_status;
	}

	/**
	 * Check whether the GA4 OAuth connection needs reconnecting.
	 *
	 * @param string $credential_source_label Internal credential source label.
	 * @param string $lifecycle_status        Internal token lifecycle status.
	 * @return bool
	 */
	private function is_ga4_connection_reconnect_required( $credential_source_label, $lifecycle_status ) {
		return in_array(
			$credential_source_label,
			array(
				'credential_source_oauth_refresh_needed',
				'credential_source_oauth_error_category',
			),
			true
		) || in_array(
			$lifecycle_status,
			array(
				'expired',
				'refresh_unavailable',
				'reconnect_required',
			),
			true
		);
	}

	/**
	 * Get a user-facing AI generation readiness label.
	 *
	 * @param bool $runtime_ready Whether the WordPress AI Client entry point is available.
	 * @return string
	 */
	private function get_ai_generation_readiness_label( $runtime_ready ) {
		if ( $runtime_ready ) {
			return __( 'Ready to check before generation', 'studio317-report-drafts-google-analytics' );
		}

		return __( 'AI text generation unavailable', 'studio317-report-drafts-google-analytics' );
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
		<h3><?php echo esc_html__( 'Summary Metrics', 'studio317-report-drafts-google-analytics' ); ?></h3>

		<table class="widefat striped studio317-report-drafts-google-analytics-preview-table">
			<thead>
				<tr>
					<th><?php echo esc_html__( 'Metric', 'studio317-report-drafts-google-analytics' ); ?></th>
					<th><?php echo esc_html__( 'Current', 'studio317-report-drafts-google-analytics' ); ?></th>
					<th><?php echo esc_html__( 'Comparison', 'studio317-report-drafts-google-analytics' ); ?></th>
					<th><?php echo esc_html__( 'Diff', 'studio317-report-drafts-google-analytics' ); ?></th>
					<th><?php echo esc_html__( 'Change Rate', 'studio317-report-drafts-google-analytics' ); ?></th>
					<th><?php echo esc_html__( 'Unit', 'studio317-report-drafts-google-analytics' ); ?></th>
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

		<table class="widefat striped studio317-report-drafts-google-analytics-preview-table">
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
