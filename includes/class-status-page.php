<?php
/**
 * Current Status admin screen.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Renders read-only setup and Google connection status.
 *
 * @since 0.1.0
 */
final class Analytics_Report_AI_Status_Page {

	/**
	 * Render Current Status page.
	 *
	 * @return void
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings                          = analytics_report_ai_get_settings();
		$google_oauth_client_configuration = analytics_report_ai_resolve_google_oauth_client_configuration( $settings );
		$google_oauth_client_source        = $this->get_scalar_status( $google_oauth_client_configuration, 'source_category', 'missing' );
		$google_oauth_client_fallback      = $this->get_scalar_status( $google_oauth_client_configuration, 'settings_fallback_status', 'not_saved' );
		$google_oauth_client_value_display = $this->get_scalar_status( $google_oauth_client_configuration, 'value_hidden_status', 'hidden' );
		unset( $google_oauth_client_configuration['client_id'], $google_oauth_client_configuration['client_secret'] );

		$google_oauth_lifecycle_categories   = analytics_report_ai_get_google_oauth_token_lifecycle_categories();
		$google_oauth_connection_status      = $this->get_scalar_status( $google_oauth_lifecycle_categories, 'oauth_connection_status_category', 'not_connected' );
		$google_oauth_token_lifecycle_status = $this->get_scalar_status( $google_oauth_lifecycle_categories, 'token_lifecycle_status_category', 'reconnect_required' );
		$google_oauth_refresh_status         = $this->get_scalar_status( $google_oauth_lifecycle_categories, 'token_refresh_status_category', 'unavailable' );
		$google_oauth_disconnect_status      = $this->get_scalar_status( $google_oauth_lifecycle_categories, 'token_disconnect_status_category', 'not_requested' );
		$google_oauth_revoke_status          = $this->get_scalar_status( $google_oauth_lifecycle_categories, 'token_revoke_status_category', 'deferred' );
		$google_oauth_storage_status         = $this->get_scalar_status( $google_oauth_lifecycle_categories, 'oauth_token_storage_status_category', 'not_stored' );

		$ai_client_runtime_ready = function_exists( 'wp_ai_client_prompt' );
		$report_language         = analytics_report_ai_get_report_language_profile();
		$settings_url            = $this->get_settings_url();
		$google_settings_url     = $this->get_settings_url( 'studio317-report-drafts-google-analytics-google-connection-settings' );
		?>
		<div class="wrap studio317-report-drafts-google-analytics-admin">
			<h1><?php echo esc_html__( 'Current Status', 'studio317-report-drafts-google-analytics' ); ?></h1>

			<p class="description">
				<?php echo esc_html__( 'Review the current setup and Google connection status. This page is read-only and does not display credential, token, option, analytics, or generated report values.', 'studio317-report-drafts-google-analytics' ); ?>
			</p>

			<div class="studio317-report-drafts-google-analytics-card">
				<h2><?php echo esc_html__( 'Current Settings', 'studio317-report-drafts-google-analytics' ); ?></h2>

				<table class="widefat striped studio317-report-drafts-google-analytics-status-table">
					<tbody>
						<tr>
							<th scope="row"><?php echo esc_html__( 'OAuth client setup', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td><?php echo esc_html( $this->get_google_oauth_client_source_label( $google_oauth_client_source ) ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Saved Settings client', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td><?php echo esc_html( $this->get_google_oauth_client_fallback_label( $google_oauth_client_fallback ) ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Value display', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td><?php echo esc_html( $this->get_value_display_label( $google_oauth_client_value_display ) ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'AI generation provider', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td>
								<?php if ( ! $ai_client_runtime_ready ) : ?>
									<span class="studio317-report-drafts-google-analytics-status-warning">
										<?php echo esc_html( $this->get_ai_generation_readiness_label( $ai_client_runtime_ready ) ); ?>
									</span>
								<?php else : ?>
									<?php echo esc_html( $this->get_ai_generation_readiness_label( $ai_client_runtime_ready ) ); ?>
								<?php endif; ?>
								<p class="description">
									<?php echo esc_html__( 'AI provider credentials and model details are managed by WordPress Settings > Connectors and are not displayed by this plugin.', 'studio317-report-drafts-google-analytics' ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Report language', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td>
								<?php $this->render_report_language_summary( $report_language ); ?>
							</td>
						</tr>
					</tbody>
				</table>

				<p>
					<a class="button" href="<?php echo esc_url( $settings_url ); ?>">
						<?php echo esc_html__( 'Open Settings', 'studio317-report-drafts-google-analytics' ); ?>
					</a>
				</p>
			</div>

			<div class="studio317-report-drafts-google-analytics-card">
				<h2><?php echo esc_html__( 'Google Connection', 'studio317-report-drafts-google-analytics' ); ?></h2>

				<table class="widefat striped studio317-report-drafts-google-analytics-status-table">
					<tbody>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Google account connection', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td><?php echo esc_html( $this->get_google_oauth_connection_label( $google_oauth_connection_status ) ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Token status', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td>
								<?php if ( 'usable' !== $google_oauth_token_lifecycle_status ) : ?>
									<span class="studio317-report-drafts-google-analytics-status-warning">
										<?php echo esc_html( $this->get_google_oauth_token_lifecycle_label( $google_oauth_token_lifecycle_status ) ); ?>
									</span>
								<?php else : ?>
									<?php echo esc_html( $this->get_google_oauth_token_lifecycle_label( $google_oauth_token_lifecycle_status ) ); ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Token storage', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td><?php echo esc_html( $this->get_token_storage_label( $google_oauth_storage_status ) ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Refresh handling', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td><?php echo esc_html( $this->get_token_refresh_label( $google_oauth_refresh_status ) ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Disconnect handling', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td><?php echo esc_html( $this->get_token_disconnect_label( $google_oauth_disconnect_status ) ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Provider revoke handling', 'studio317-report-drafts-google-analytics' ); ?></th>
							<td><?php echo esc_html( $this->get_token_revoke_label( $google_oauth_revoke_status ) ); ?></td>
						</tr>
					</tbody>
				</table>

				<p class="description">
					<?php echo esc_html__( 'Refresh requests and provider-side revoke are not performed automatically. If Google access needs recovery, use the Google connection controls in Settings.', 'studio317-report-drafts-google-analytics' ); ?>
				</p>

				<p>
					<a class="button" href="<?php echo esc_url( $google_settings_url ); ?>">
						<?php echo esc_html__( 'Open Google connection settings', 'studio317-report-drafts-google-analytics' ); ?>
					</a>
				</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Get a scalar status value from a status array.
	 *
	 * @param array  $statuses Status array.
	 * @param string $key      Status key.
	 * @param string $default  Default value.
	 * @return string
	 */
	private function get_scalar_status( $statuses, $key, $default ) {
		if ( isset( $statuses[ $key ] ) && is_scalar( $statuses[ $key ] ) ) {
			return (string) $statuses[ $key ];
		}

		return $default;
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
	 * Get a user-facing value display label.
	 *
	 * @param string $value_display_status Internal value display status.
	 * @return string
	 */
	private function get_value_display_label( $value_display_status ) {
		if ( 'hidden' === $value_display_status ) {
			return __( 'Hidden', 'studio317-report-drafts-google-analytics' );
		}

		return __( 'Hidden', 'studio317-report-drafts-google-analytics' );
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
			case 'token_expired_or_refresh_needed':
			case 'reconnect_required':
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
	 * Get a user-facing token storage label.
	 *
	 * @param string $storage_status Internal storage category.
	 * @return string
	 */
	private function get_token_storage_label( $storage_status ) {
		if ( 'stored' === $storage_status ) {
			return __( 'Stored locally by this plugin; values are hidden', 'studio317-report-drafts-google-analytics' );
		}

		return __( 'Not stored', 'studio317-report-drafts-google-analytics' );
	}

	/**
	 * Get a user-facing token refresh label.
	 *
	 * @param string $refresh_status Internal refresh category.
	 * @return string
	 */
	private function get_token_refresh_label( $refresh_status ) {
		switch ( $refresh_status ) {
			case 'not_attempted':
				return __( 'Not attempted', 'studio317-report-drafts-google-analytics' );
			case 'deferred':
				return __( 'Deferred; reconnect Google if recovery is needed', 'studio317-report-drafts-google-analytics' );
			case 'unavailable':
			default:
				return __( 'Unavailable; reconnect Google if needed', 'studio317-report-drafts-google-analytics' );
		}
	}

	/**
	 * Get a user-facing token disconnect label.
	 *
	 * @param string $disconnect_status Internal disconnect category.
	 * @return string
	 */
	private function get_token_disconnect_label( $disconnect_status ) {
		switch ( $disconnect_status ) {
			case 'local_tokens_deleted':
				return __( 'Local Google connection data deleted', 'studio317-report-drafts-google-analytics' );
			case 'not_requested':
			default:
				return __( 'Not requested', 'studio317-report-drafts-google-analytics' );
		}
	}

	/**
	 * Get a user-facing provider revoke label.
	 *
	 * @param string $revoke_status Internal revoke category.
	 * @return string
	 */
	private function get_token_revoke_label( $revoke_status ) {
		if ( 'deferred' === $revoke_status ) {
			return __( 'Deferred; provider-side revoke is not performed by this plugin', 'studio317-report-drafts-google-analytics' );
		}

		return __( 'Deferred; provider-side revoke is not performed by this plugin', 'studio317-report-drafts-google-analytics' );
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
	 * Render a safe report language summary.
	 *
	 * @param array $report_language Report language profile.
	 * @return void
	 */
	private function render_report_language_summary( $report_language ) {
		$language_name = isset( $report_language['language_name'] ) && is_scalar( $report_language['language_name'] )
			? (string) $report_language['language_name']
			: __( 'English', 'studio317-report-drafts-google-analytics' );
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
	 * Build the plugin Settings screen URL.
	 *
	 * @param string $fragment Optional fragment identifier.
	 * @return string
	 */
	private function get_settings_url( $fragment = '' ) {
		$url = admin_url( 'admin.php?page=studio317-report-drafts-google-analytics-settings' );

		if ( '' === $fragment ) {
			return $url;
		}

		return $url . '#' . rawurlencode( $fragment );
	}
}
