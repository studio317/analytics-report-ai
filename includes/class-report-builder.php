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
		?>
		<div class="wrap analytics-report-ai-admin">
			<h1><?php echo esc_html__( 'Analytics Report AI', 'analytics-report-ai' ); ?></h1>

			<div class="analytics-report-ai-card">
				<h2><?php echo esc_html__( 'Report Builder', 'analytics-report-ai' ); ?></h2>

				<p>
					<?php echo esc_html__( 'This screen will be used to fetch GA4 data, review the AI payload, and generate a Japanese report draft.', 'analytics-report-ai' ); ?>
				</p>

				<p class="description">
					<?php echo esc_html__( 'Step 1: Plugin skeleton is active. Report form implementation will be added in the next steps.', 'analytics-report-ai' ); ?>
				</p>
			</div>
		</div>
		<?php
	}
}