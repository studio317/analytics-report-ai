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

		$settings       = analytics_report_ai_get_settings();
		$default_period = analytics_report_ai_get_default_report_period();

		$ga4_property_id     = isset( $settings['ga4_property_id'] ) ? $settings['ga4_property_id'] : '';
		$host_filter_enabled = ! empty( $settings['host_filter_enabled'] );
		$host_name           = isset( $settings['host_name'] ) ? $settings['host_name'] : '';
		$has_openai_api_key  = ! empty( $settings['openai_api_key'] );
		?>
		<div class="wrap analytics-report-ai-admin">
			<h1><?php echo esc_html__( 'Report Builder', 'analytics-report-ai' ); ?></h1>

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
					<?php echo esc_html__( 'GA4 data fetching and AI report generation will be implemented in later steps.', 'analytics-report-ai' ); ?>
				</p>
			</div>

			<form method="post" action="" class="analytics-report-ai-card analytics-report-ai-report-form">
				<?php wp_nonce_field( 'analytics_report_ai_report_builder_action', 'analytics_report_ai_report_builder_nonce' ); ?>

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
									value="<?php echo esc_attr( $default_period['start_date'] ); ?>"
								/>

								<label for="analytics-report-ai-end-date" class="analytics-report-ai-inline-label">
									<?php echo esc_html__( 'End Date', 'analytics-report-ai' ); ?>
								</label>
								<input
									type="date"
									id="analytics-report-ai-end-date"
									name="analytics_report_ai_report[end_date]"
									value="<?php echo esc_attr( $default_period['end_date'] ); ?>"
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

									<label class="analytics-report-ai-radio-label">
										<input
											type="radio"
											name="analytics_report_ai_report[comparison]"
											value="none"
										/>
										<?php echo esc_html__( 'No comparison', 'analytics-report-ai' ); ?>
									</label>

									<label class="analytics-report-ai-radio-label">
										<input
											type="radio"
											name="analytics_report_ai_report[comparison]"
											value="previous_month"
											checked="checked"
										/>
										<?php echo esc_html__( 'Previous month', 'analytics-report-ai' ); ?>
									</label>

									<label class="analytics-report-ai-radio-label">
										<input
											type="radio"
											name="analytics_report_ai_report[comparison]"
											value="previous_year"
										/>
										<?php echo esc_html__( 'Previous year', 'analytics-report-ai' ); ?>
									</label>
								</fieldset>

								<p class="description">
									<?php echo esc_html__( 'Comparison period calculation will be implemented in the validation step.', 'analytics-report-ai' ); ?>
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

									<label class="analytics-report-ai-radio-label">
										<input
											type="radio"
											name="analytics_report_ai_report[scope]"
											value="site"
											checked="checked"
											data-analytics-report-ai-scope
										/>
										<?php echo esc_html__( 'Entire site', 'analytics-report-ai' ); ?>
									</label>

									<label class="analytics-report-ai-radio-label">
										<input
											type="radio"
											name="analytics_report_ai_report[scope]"
											value="directory"
											data-analytics-report-ai-scope
										/>
										<?php echo esc_html__( 'Directory', 'analytics-report-ai' ); ?>
									</label>

									<label class="analytics-report-ai-radio-label">
										<input
											type="radio"
											name="analytics_report_ai_report[scope]"
											value="page"
											data-analytics-report-ai-scope
										/>
										<?php echo esc_html__( 'Page', 'analytics-report-ai' ); ?>
									</label>
								</fieldset>

								<div class="analytics-report-ai-path-field" data-analytics-report-ai-path-field>
									<label for="analytics-report-ai-path">
										<?php echo esc_html__( 'Path', 'analytics-report-ai' ); ?>
									</label>

									<input
										type="text"
										id="analytics-report-ai-path"
										name="analytics_report_ai_report[path]"
										value=""
										class="regular-text"
										placeholder="/blog/"
										data-analytics-report-ai-path-input
									/>

									<p class="description" data-analytics-report-ai-path-description>
										<?php echo esc_html__( 'For directory scope, enter a path such as /blog/. For page scope, enter a path such as /about.', 'analytics-report-ai' ); ?>
									</p>
								</div>

								<p class="description">
									<?php echo esc_html__( 'Path normalization and validation will be implemented in the next step.', 'analytics-report-ai' ); ?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>

				<p>
					<button type="button" class="button button-primary" disabled="disabled">
						<?php echo esc_html__( 'Fetch GA4 Data', 'analytics-report-ai' ); ?>
					</button>
				</p>

				<p class="description">
					<?php echo esc_html__( 'This button is disabled in Step 3. It will be enabled after validation and dummy payload handling are implemented.', 'analytics-report-ai' ); ?>
				</p>
			</form>
		</div>
		<?php
	}
}