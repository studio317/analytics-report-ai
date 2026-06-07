<?php
/**
 * Report Builder admin screen.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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

		$ga4_property_id     = isset( $settings['ga4_property_id'] ) ? $settings['ga4_property_id'] : '';
		$host_filter_enabled = ! empty( $settings['host_filter_enabled'] );
		$host_name           = isset( $settings['host_name'] ) ? $settings['host_name'] : '';
		$has_openai_api_key  = ! empty( $settings['openai_api_key'] );
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
									<?php if ( '' !== $host_name ) : ?>
										:
										<code><?php echo esc_html( $host_name ); ?></code>
									<?php endif; ?>
								<?php else : ?>
									<?php echo esc_html__( 'Disabled', 'analytics-report-ai' ); ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'OpenAI API Key', 'analytics-report-ai' ); ?></th>
							<td>
								<?php if ( $has_openai_api_key ) : ?>
									<?php echo esc_html__( 'Saved', 'analytics-report-ai' ); ?>
								<?php else : ?>
									<span class="analytics-report-ai-status-warning">
										<?php echo esc_html__( 'Not saved', 'analytics-report-ai' ); ?>
									</span>
								<?php endif; ?>
							</td>
						</tr>
					</tbody>
				</table>

				<p class="description">
					<?php echo esc_html__( 'This step creates a dummy AI payload preview. Actual GA4 data fetching will be implemented later.', 'analytics-report-ai' ); ?>
				</p>
			</div>

			<form method="post" action="" class="analytics-report-ai-card analytics-report-ai-report-form">
				<?php wp_nonce_field( 'analytics_report_ai_report_builder_action', 'analytics_report_ai_report_builder_nonce' ); ?>
				<input type="hidden" name="analytics_report_ai_report_action" value="create_dummy_payload" />

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
									<?php echo esc_html__( 'The comparison period is calculated by shifting the selected period to the previous month or previous year.', 'analytics-report-ai' ); ?>
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
									<?php echo esc_html__( 'Full URLs are not allowed. Query strings and fragments are removed during normalization.', 'analytics-report-ai' ); ?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>

				<p>
					<button type="submit" class="button button-primary">
						<?php echo esc_html__( 'Create Dummy Payload', 'analytics-report-ai' ); ?>
					</button>
				</p>

				<p class="description">
					<?php echo esc_html__( 'This button validates the conditions and creates a dummy AI payload preview. It does not call the GA4 API yet.', 'analytics-report-ai' ); ?>
				</p>
			</form>

			<?php $this->render_validated_conditions( $submission_result ); ?>
			<?php $this->render_payload_preview( $submission_result ); ?>
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

		$raw_input = isset( $_POST['analytics_report_ai_report'] ) && is_array( $_POST['analytics_report_ai_report'] )
			? wp_unslash( $_POST['analytics_report_ai_report'] )
			: array();

		$form_values = array(
			'start_date' => isset( $raw_input['start_date'] ) ? sanitize_text_field( $raw_input['start_date'] ) : '',
			'end_date'   => isset( $raw_input['end_date'] ) ? sanitize_text_field( $raw_input['end_date'] ) : '',
			'comparison' => isset( $raw_input['comparison'] ) ? sanitize_text_field( $raw_input['comparison'] ) : '',
			'scope'      => isset( $raw_input['scope'] ) ? sanitize_text_field( $raw_input['scope'] ) : '',
			'path'       => isset( $raw_input['path'] ) ? sanitize_text_field( $raw_input['path'] ) : '',
		);

		$errors = array();

		$start_date = $form_values['start_date'];
		$end_date   = $form_values['end_date'];

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

		$comparison_options = analytics_report_ai_get_comparison_options();
		$comparison         = $form_values['comparison'];

		if ( ! isset( $comparison_options[ $comparison ] ) ) {
			$errors[]   = __( 'Comparison option is invalid.', 'analytics-report-ai' );
			$comparison = 'previous_month';
		}

		$scope_options = analytics_report_ai_get_scope_options();
		$scope         = $form_values['scope'];

		if ( ! isset( $scope_options[ $scope ] ) ) {
			$errors[] = __( 'Data scope is invalid.', 'analytics-report-ai' );
			$scope    = 'site';
		}

		$normalized_path = analytics_report_ai_normalize_report_path( $form_values['path'], $scope );

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

		$settings = analytics_report_ai_get_settings();
		$payload  = Analytics_Report_AI_Report_Data_Formatter::create_dummy_payload( $conditions, $settings );

		$transient_key = analytics_report_ai_get_payload_transient_key();
		$expiration    = analytics_report_ai_get_payload_transient_expiration();

		set_transient( $transient_key, $payload, $expiration );

		return array(
			'status'        => 'success',
			'conditions'    => $conditions,
			'payload'       => $payload,
			'transient_key' => $transient_key,
			'expiration'    => $expiration,
			'form_values'   => $form_values,
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

		if ( 'success' === $submission_result['status'] ) {
			?>
			<div class="notice notice-success">
				<p><?php echo esc_html__( 'Dummy AI payload was created successfully.', 'analytics-report-ai' ); ?></p>
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
			|| 'success' !== $submission_result['status']
			|| empty( $submission_result['conditions'] )
			|| ! is_array( $submission_result['conditions'] )
		) {
			return;
		}

		$conditions        = $submission_result['conditions'];
		$comparison_period = $conditions['comparison_period'];
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
			|| 'success' !== $submission_result['status']
			|| empty( $submission_result['payload'] )
			|| ! is_array( $submission_result['payload'] )
		) {
			return;
		}

		$payload    = $submission_result['payload'];
		$expiration = isset( $submission_result['expiration'] ) ? absint( $submission_result['expiration'] ) : 0;
		$json       = wp_json_encode(
			$payload,
			JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);
		?>
		<div class="analytics-report-ai-card">
			<h2><?php echo esc_html__( 'AI Payload Preview', 'analytics-report-ai' ); ?></h2>

			<p>
				<?php
				printf(
					esc_html__( 'The dummy AI payload has been saved temporarily for %d minutes.', 'analytics-report-ai' ),
					(int) floor( $expiration / MINUTE_IN_SECONDS )
				);
				?>
			</p>

			<?php $this->render_summary_preview_table( $payload ); ?>
			<?php $this->render_list_preview_table( __( 'Top Pages', 'analytics-report-ai' ), isset( $payload['top_pages'] ) ? $payload['top_pages'] : array() ); ?>
			<?php $this->render_list_preview_table( __( 'Traffic Channels', 'analytics-report-ai' ), isset( $payload['traffic_channels'] ) ? $payload['traffic_channels'] : array() ); ?>
			<?php $this->render_list_preview_table( __( 'Traffic Sources', 'analytics-report-ai' ), isset( $payload['traffic_sources'] ) ? $payload['traffic_sources'] : array() ); ?>
			<?php $this->render_list_preview_table( __( 'Regional Trends', 'analytics-report-ai' ), isset( $payload['regional_trends'] ) ? $payload['regional_trends'] : array() ); ?>

			<details class="analytics-report-ai-json-preview">
				<summary><?php echo esc_html__( 'Show payload JSON', 'analytics-report-ai' ); ?></summary>
				<pre><code><?php echo esc_html( $json ); ?></code></pre>
			</details>

			<p>
				<button type="button" class="button button-primary" disabled="disabled">
					<?php echo esc_html__( 'Generate AI Report', 'analytics-report-ai' ); ?>
				</button>
			</p>

			<p class="description">
				<?php echo esc_html__( 'AI report generation will be implemented in the next step using the payload saved in transient.', 'analytics-report-ai' ); ?>
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