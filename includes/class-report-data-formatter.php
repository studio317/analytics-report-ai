<?php
/**
 * Report data formatter.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Formats GA4 response data into the validated AI payload structure.
 *
 * @since 0.1.0
 */
final class Analytics_Report_AI_Report_Data_Formatter {

	/**
	 * Create AI payload from GA4 data.
	 *
	 * @param array $conditions         Validated conditions.
	 * @param array $settings           Plugin settings.
	 * @param array $current_summary    Current GA4 summary.
	 * @param array $comparison_summary Comparison GA4 summary.
	 * @param array $preset_reports     GA4 preset reports.
	 * @return array
	 */
	public static function create_payload_from_ga4_summary( $conditions, $settings, $current_summary, $comparison_summary = array(), $preset_reports = array() ) {
		$has_comparison = ! empty( $conditions['comparison'] ) && 'none' !== $conditions['comparison'];
		$row_limits     = analytics_report_ai_get_payload_row_limits();

		return array(
			'plugin'           => 'Analytics Report AI',
			'payload_version'  => analytics_report_ai_get_payload_version(),
			'language'         => 'ja',
			'report_type'      => 'ga4_summary',
			'site'             => self::build_site_payload( $settings ),
			'conditions'       => self::build_conditions_payload( $conditions ),
			'summary'          => self::build_summary_from_ga4_values(
				$current_summary,
				$has_comparison ? $comparison_summary : array(),
				$has_comparison
			),
			'daily_trend'      => self::limit_rows(
				isset( $preset_reports['daily_trend'] ) && is_array( $preset_reports['daily_trend'] )
					? $preset_reports['daily_trend']
					: array(),
				$row_limits['daily_trend']
			),
			'top_pages'        => self::limit_rows(
				isset( $preset_reports['top_pages'] ) && is_array( $preset_reports['top_pages'] )
					? $preset_reports['top_pages']
					: array(),
				$row_limits['top_pages']
			),
			'traffic_channels' => self::limit_rows(
				isset( $preset_reports['traffic_channels'] ) && is_array( $preset_reports['traffic_channels'] )
					? $preset_reports['traffic_channels']
					: array(),
				$row_limits['traffic_channels']
			),
			'traffic_sources'  => self::limit_rows(
				isset( $preset_reports['traffic_sources'] ) && is_array( $preset_reports['traffic_sources'] )
					? $preset_reports['traffic_sources']
					: array(),
				$row_limits['traffic_sources']
			),
			'regional_trends'  => self::limit_rows(
				isset( $preset_reports['regional_trends'] ) && is_array( $preset_reports['regional_trends'] )
					? $preset_reports['regional_trends']
					: array(),
				$row_limits['regional_trends']
			),
		);
	}

	/**
	 * Build site payload.
	 *
	 * @param array $settings Plugin settings.
	 * @return array
	 */
	private static function build_site_payload( $settings ) {
		return array(
			'host_filter_enabled' => ! empty( $settings['host_filter_enabled'] ),
			'host_name'           => isset( $settings['host_name'] ) ? (string) $settings['host_name'] : '',
		);
	}

	/**
	 * Build conditions payload.
	 *
	 * @param array $conditions Validated conditions.
	 * @return array
	 */
	private static function build_conditions_payload( $conditions ) {
		return array(
			'period'            => isset( $conditions['period'] ) ? $conditions['period'] : array(),
			'comparison'        => isset( $conditions['comparison'] ) ? $conditions['comparison'] : 'none',
			'comparison_label'  => isset( $conditions['comparison_label'] ) ? $conditions['comparison_label'] : '',
			'comparison_period' => isset( $conditions['comparison_period'] ) ? $conditions['comparison_period'] : null,
			'scope'             => isset( $conditions['scope'] ) ? $conditions['scope'] : 'site',
			'scope_label'       => isset( $conditions['scope_label'] ) ? $conditions['scope_label'] : '',
			'path'              => isset( $conditions['path'] ) ? $conditions['path'] : '',
		);
	}

	/**
	 * Limit rows.
	 *
	 * @param array $rows  Rows.
	 * @param int   $limit Limit.
	 * @return array
	 */
	private static function limit_rows( $rows, $limit ) {
		if ( empty( $rows ) || ! is_array( $rows ) ) {
			return array();
		}

		return array_slice( array_values( $rows ), 0, absint( $limit ) );
	}

	/**
	 * Build summary from GA4 values.
	 *
	 * @param array $current_summary    Current summary.
	 * @param array $comparison_summary Comparison summary.
	 * @param bool  $has_comparison     Whether comparison exists.
	 * @return array
	 */
	private static function build_summary_from_ga4_values( $current_summary, $comparison_summary, $has_comparison ) {
		$definitions = analytics_report_ai_get_summary_metric_definitions();
		$summary     = array();

		foreach ( $definitions as $metric_name => $definition ) {
			$current    = isset( $current_summary[ $metric_name ] ) ? $current_summary[ $metric_name ] : 0;
			$comparison = isset( $comparison_summary[ $metric_name ] ) ? $comparison_summary[ $metric_name ] : 0;

			$summary[ $metric_name ] = self::build_metric(
				$definition['label'],
				$current,
				$comparison,
				$definition['unit'],
				$has_comparison
			);
		}

		return $summary;
	}

	/**
	 * Build one metric payload.
	 *
	 * @param string    $label Label.
	 * @param int|float $current Current value.
	 * @param int|float $comparison Comparison value.
	 * @param string    $unit Unit.
	 * @param bool      $has_comparison Whether comparison exists.
	 * @return array
	 */
	private static function build_metric( $label, $current, $comparison, $unit, $has_comparison ) {
		$current    = is_numeric( $current ) ? $current + 0 : 0;
		$comparison = is_numeric( $comparison ) ? $comparison + 0 : 0;

		$diff        = null;
		$change_rate = null;

		if ( $has_comparison ) {
			$diff = $current - $comparison;

			if ( 0 !== (float) $comparison ) {
				$change_rate = $diff / $comparison;
			}
		}

		return array(
			'label'       => $label,
			'current'     => $current,
			'comparison'  => $has_comparison ? $comparison : null,
			'diff'        => $has_comparison ? $diff : null,
			'change_rate' => $has_comparison ? $change_rate : null,
			'unit'        => $unit,
		);
	}
}
