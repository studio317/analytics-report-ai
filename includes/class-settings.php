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
		$openai_api_key       = isset( $input['openai_api_key'] ) ? trim( (string) $input['openai_api_key'] ) : '';

		if ( $clear_openai_api_key ) {
			$settings['openai_api_key'] = '';
		} elseif ( '' !== $openai_api_key ) {
			$settings['openai_api_key'] = sanitize_text_field( $openai_api_key );
		}

		/*
		 * These values are not edited on this screen yet.
		 */
		$settings['google_auth_status'] = isset( $existing['google_auth_status'] ) ? sanitize_text_field( $existing['google_auth_status'] ) : 'not_connected';
		$settings['google_tokens']      = isset( $existing['google_tokens'] ) && is_array( $existing['google_tokens'] ) ? $existing['google_tokens'] : array();

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

		$settings             = analytics_report_ai_get_settings();
		$has_openai_api_key   = ! empty( $settings['openai_api_key'] );
		$google_auth_status   = isset( $settings['google_auth_status'] ) ? $settings['google_auth_status'] : 'not_connected';
		$host_filter_enabled  = ! empty( $settings['host_filter_enabled'] );
		$ga4_property_id      = isset( $settings['ga4_property_id'] ) ? $settings['ga4_property_id'] : '';
		$host_name            = isset( $settings['host_name'] ) ? $settings['host_name'] : analytics_report_ai_get_default_host();
		?>
		<div class="wrap analytics-report-ai-admin">
			<h1><?php echo esc_html__( 'Analytics Report AI Settings', 'analytics-report-ai' ); ?></h1>

			<?php settings_errors( ANALYTICS_REPORT_AI_OPTION_NAME ); ?>

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
								<?php echo esc_html__( 'Google Auth Status', 'analytics-report-ai' ); ?>
							</th>
							<td>
								<code><?php echo esc_html( $google_auth_status ); ?></code>
								<p class="description">
									<?php echo esc_html__( 'Google OAuth connection will be implemented in a later step.', 'analytics-report-ai' ); ?>
								</p>
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
}