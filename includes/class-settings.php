<?php
/**
 * Settings admin screen.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Analytics_Report_AI_Settings {

	/**
	 * Render Settings page.
	 *
	 * @return void
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = analytics_report_ai_get_settings();
		?>
		<div class="wrap analytics-report-ai-admin">
			<h1><?php echo esc_html__( 'Analytics Report AI Settings', 'analytics-report-ai' ); ?></h1>

			<div class="analytics-report-ai-card">
				<h2><?php echo esc_html__( 'Current Defaults', 'analytics-report-ai' ); ?></h2>

				<table class="widefat striped">
					<tbody>
						<tr>
							<th scope="row"><?php echo esc_html__( 'GA4 Property ID', 'analytics-report-ai' ); ?></th>
							<td><?php echo esc_html( $settings['ga4_property_id'] ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Google Auth Status', 'analytics-report-ai' ); ?></th>
							<td><?php echo esc_html( $settings['google_auth_status'] ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Host Filter Enabled', 'analytics-report-ai' ); ?></th>
							<td><?php echo ! empty( $settings['host_filter_enabled'] ) ? esc_html__( 'Enabled', 'analytics-report-ai' ) : esc_html__( 'Disabled', 'analytics-report-ai' ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Host Name', 'analytics-report-ai' ); ?></th>
							<td><?php echo esc_html( $settings['host_name'] ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'OpenAI API Key', 'analytics-report-ai' ); ?></th>
							<td>
								<?php
								echo ! empty( $settings['openai_api_key'] )
									? esc_html__( 'Saved', 'analytics-report-ai' )
									: esc_html__( 'Not saved', 'analytics-report-ai' );
								?>
							</td>
						</tr>
					</tbody>
				</table>

				<p class="description">
					<?php echo esc_html__( 'Settings form and save processing will be implemented in Step 2.', 'analytics-report-ai' ); ?>
				</p>
			</div>
		</div>
		<?php
	}
}