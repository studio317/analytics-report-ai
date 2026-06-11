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
		$limited_reports = array();

		foreach ( $row_limits as $preset_key => $limit ) {
			$limited_reports[ $preset_key ] = self::limit_rows(
				isset( $preset_reports[ $preset_key ] ) && is_array( $preset_reports[ $preset_key ] )
					? $preset_reports[ $preset_key ]
					: array(),
				$limit
			);
		}

		$metadata = self::build_no_data_metadata(
			$current_summary,
			$has_comparison ? $comparison_summary : array(),
			$limited_reports,
			$has_comparison
		);

		return array(
			'plugin'             => 'Analytics Report AI',
			'payload_version'    => analytics_report_ai_get_payload_version(),
			'language'           => 'ja',
			'report_type'        => 'ga4_summary',
			'payload_status'    => $metadata['payload_status'],
			'data_availability' => $metadata['data_availability'],
			'value_semantics'   => $metadata['value_semantics'],
			'site'               => self::build_site_payload( $settings ),
			'conditions'         => self::build_conditions_payload( $conditions ),
			'summary'            => self::build_summary_from_ga4_values(
				$current_summary,
				$has_comparison ? $comparison_summary : array(),
				$has_comparison
			),
			'daily_trend'        => $limited_reports['daily_trend'],
			'top_pages'          => $limited_reports['top_pages'],
			'traffic_channels'   => $limited_reports['traffic_channels'],
			'traffic_sources'    => $limited_reports['traffic_sources'],
			'regional_trends'    => $limited_reports['regional_trends'],
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
	 * Build no-data metadata for payload preview and generation gating.
	 *
	 * @param array $current_summary    Current summary.
	 * @param array $comparison_summary Comparison summary.
	 * @param array $preset_reports     Limited preset reports.
	 * @param bool  $has_comparison     Whether comparison exists.
	 * @return array
	 */
	private static function build_no_data_metadata( $current_summary, $comparison_summary, $preset_reports, $has_comparison ) {
		$current_availability    = self::get_summary_availability( $current_summary );
		$comparison_availability = $has_comparison ? self::get_summary_availability( $comparison_summary ) : self::get_not_requested_summary_availability();
		$detail_presets          = self::build_detail_preset_availability( $preset_reports );
		$has_detail_rows         = self::has_detail_rows( $detail_presets );
		$has_current_data        = ! empty( $current_availability['has_reportable_data'] ) || $has_detail_rows;
		$warnings                = array();
		$generation_allowed      = $has_current_data;
		$generation_block_reason = $generation_allowed ? '' : 'current_period_no_data';

		if ( ! empty( $current_availability['has_reportable_data'] ) && 'zero' === $current_availability['value_state'] && ! empty( $current_availability['all_requested_found'] ) ) {
			$warnings[] = self::build_warning( 'current_period_zero_activity', 'summary', 'warning' );
		}

		if ( ! empty( $current_availability['has_reportable_data'] ) && empty( $current_availability['all_requested_found'] ) ) {
			$warnings[] = self::build_warning( 'current_summary_partial_metric_values', 'summary', 'warning' );
		}

		if ( empty( $current_availability['has_reportable_data'] ) && $has_detail_rows ) {
			$warnings[] = self::build_warning( 'current_summary_missing', 'summary', 'warning' );
		}

		if ( $has_comparison && empty( $comparison_availability['has_reportable_data'] ) ) {
			$warnings[] = self::build_warning( 'comparison_period_no_data', 'comparison_period', 'warning' );
		} elseif ( $has_comparison && 'zero' === $comparison_availability['value_state'] && ! empty( $comparison_availability['all_requested_found'] ) ) {
			$warnings[] = self::build_warning( 'comparison_period_zero_activity', 'comparison_period', 'warning' );
		} elseif ( $has_comparison && empty( $comparison_availability['all_requested_found'] ) ) {
			$warnings[] = self::build_warning( 'comparison_summary_partial_metric_values', 'comparison_period', 'warning' );
		}

		foreach ( $detail_presets as $preset_key => $preset_availability ) {
			if ( 'empty' === $preset_availability['status'] ) {
				$warnings[] = self::build_warning( 'detail_preset_empty', $preset_key, 'info' );
			}
		}

		$overall_status = 'ga4_payload_created';
		$severity       = 'success';

		if ( ! $generation_allowed ) {
			$overall_status = 'ga4_current_period_no_data';
			$severity       = 'blocking';
			$warnings       = array();
		} elseif ( ! empty( $warnings ) ) {
			$overall_status = 'ga4_payload_created_with_warnings';
			$severity       = 'warning';
		}

		return array(
			'payload_status'    => array(
				'overall_status'          => $overall_status,
				'severity'                => $severity,
				'generation_allowed'      => $generation_allowed,
				'generation_block_reason' => $generation_block_reason,
				'warnings'                => $warnings,
			),
			'data_availability' => array(
				'current_period'    => self::build_period_availability( $current_availability, $has_detail_rows, $generation_allowed ),
				'comparison_period' => self::build_comparison_period_availability( $comparison_availability, $has_comparison ),
				'summary'           => array(
					'current'    => $current_availability,
					'comparison' => $comparison_availability,
				),
				'detail_presets'    => $detail_presets,
			),
			'value_semantics'   => array(
				'zero_values_are_real'          => self::has_explicit_zero_summary( $current_availability, $comparison_availability ),
				'missing_values_are_unavailable' => true,
			),
		);
	}

	/**
	 * Get normalized summary availability metadata.
	 *
	 * @param array $summary Summary values.
	 * @return array
	 */
	private static function get_summary_availability( $summary ) {
		if ( isset( $summary['_availability'] ) && is_array( $summary['_availability'] ) ) {
			return self::normalize_summary_availability( $summary['_availability'] );
		}

		$definitions      = analytics_report_ai_get_summary_metric_definitions();
		$present_count    = 0;
		$missing_count    = 0;
		$non_zero_present = false;
		$present_keys     = array();

		foreach ( array_keys( $definitions ) as $metric_name ) {
			if ( isset( $summary[ $metric_name ] ) ) {
				++$present_count;
				$present_keys[] = $metric_name;

				if ( 0 !== (float) $summary[ $metric_name ] ) {
					$non_zero_present = true;
				}
			} else {
				++$missing_count;
			}
		}

		if ( 0 === $present_count ) {
			$status = 'missing_metric_values';
		} elseif ( $missing_count > 0 ) {
			$status = 'partial_metric_values';
		} elseif ( $non_zero_present ) {
			$status = 'present_non_zero';
		} else {
			$status = 'present_zero';
		}

		return self::normalize_summary_availability(
			array(
				'status'               => $status,
				'has_reportable_data'  => $present_count > 0,
				'all_requested_found'  => 0 === $missing_count && $present_count > 0,
				'present_metric_count' => $present_count,
				'missing_metric_count' => $missing_count,
				'present_metric_keys'  => $present_keys,
				'value_state'          => $non_zero_present ? 'non_zero' : ( $present_count > 0 ? 'zero' : 'missing' ),
			)
		);
	}

	/**
	 * Get comparison availability metadata for reports without comparison.
	 *
	 * @return array
	 */
	private static function get_not_requested_summary_availability() {
		return array(
			'status'               => 'not_requested',
			'has_reportable_data'  => false,
			'all_requested_found'  => false,
			'present_metric_count' => 0,
			'missing_metric_count' => 0,
			'present_metric_keys'  => array(),
			'value_state'          => 'not_requested',
		);
	}

	/**
	 * Normalize summary availability metadata.
	 *
	 * @param array $availability Availability metadata.
	 * @return array
	 */
	private static function normalize_summary_availability( $availability ) {
		$status      = isset( $availability['status'] ) ? sanitize_key( (string) $availability['status'] ) : 'missing_metric_values';
		$value_state = isset( $availability['value_state'] ) ? sanitize_key( (string) $availability['value_state'] ) : 'missing';

		return array(
			'status'               => $status,
			'has_reportable_data'  => ! empty( $availability['has_reportable_data'] ),
			'all_requested_found'  => ! empty( $availability['all_requested_found'] ),
			'present_metric_count' => isset( $availability['present_metric_count'] ) ? absint( $availability['present_metric_count'] ) : 0,
			'missing_metric_count' => isset( $availability['missing_metric_count'] ) ? absint( $availability['missing_metric_count'] ) : 0,
			'present_metric_keys'  => isset( $availability['present_metric_keys'] ) && is_array( $availability['present_metric_keys'] )
				? array_values( array_map( 'sanitize_key', $availability['present_metric_keys'] ) )
				: array(),
			'value_state'          => $value_state,
		);
	}

	/**
	 * Build detail preset availability metadata.
	 *
	 * @param array $preset_reports Limited preset reports.
	 * @return array
	 */
	private static function build_detail_preset_availability( $preset_reports ) {
		$availability = array();

		foreach ( analytics_report_ai_get_payload_row_limits() as $preset_key => $limit ) {
			$rows  = isset( $preset_reports[ $preset_key ] ) && is_array( $preset_reports[ $preset_key ] ) ? $preset_reports[ $preset_key ] : array();
			$count = count( $rows );

			$availability[ $preset_key ] = array(
				'status'              => $count > 0 ? 'present' : 'empty',
				'row_count'           => $count,
				'row_limit'           => absint( $limit ),
				'has_reportable_rows' => $count > 0,
			);
		}

		return $availability;
	}

	/**
	 * Check whether any detail preset has rows.
	 *
	 * @param array $detail_presets Detail preset availability.
	 * @return bool
	 */
	private static function has_detail_rows( $detail_presets ) {
		foreach ( $detail_presets as $preset_availability ) {
			if ( ! empty( $preset_availability['has_reportable_rows'] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Build current-period availability metadata.
	 *
	 * @param array $summary_availability Summary availability.
	 * @param bool  $has_detail_rows      Whether detail rows exist.
	 * @param bool  $generation_allowed   Whether generation is allowed.
	 * @return array
	 */
	private static function build_period_availability( $summary_availability, $has_detail_rows, $generation_allowed ) {
		$status = 'present';

		if ( ! $generation_allowed ) {
			$status = 'no_data';
		} elseif ( 'zero' === $summary_availability['value_state'] && ! empty( $summary_availability['all_requested_found'] ) ) {
			$status = 'zero_activity';
		} elseif ( empty( $summary_availability['all_requested_found'] ) || ! $has_detail_rows ) {
			$status = 'partial_data';
		}

		return array(
			'status'              => $status,
			'has_reportable_data' => $generation_allowed,
			'summary_status'      => $summary_availability['status'],
			'detail_rows_present' => (bool) $has_detail_rows,
		);
	}

	/**
	 * Build comparison-period availability metadata.
	 *
	 * @param array $summary_availability Summary availability.
	 * @param bool  $has_comparison       Whether comparison exists.
	 * @return array
	 */
	private static function build_comparison_period_availability( $summary_availability, $has_comparison ) {
		if ( ! $has_comparison ) {
			return array(
				'status'              => 'not_requested',
				'has_reportable_data' => false,
				'summary_status'      => 'not_requested',
			);
		}

		$status = 'present';

		if ( empty( $summary_availability['has_reportable_data'] ) ) {
			$status = 'no_data';
		} elseif ( 'zero' === $summary_availability['value_state'] && ! empty( $summary_availability['all_requested_found'] ) ) {
			$status = 'zero_activity';
		} elseif ( empty( $summary_availability['all_requested_found'] ) ) {
			$status = 'partial_data';
		}

		return array(
			'status'              => $status,
			'has_reportable_data' => ! empty( $summary_availability['has_reportable_data'] ),
			'summary_status'      => $summary_availability['status'],
		);
	}

	/**
	 * Check whether either summary explicitly contains zero values.
	 *
	 * @param array $current_availability Current availability.
	 * @param array $comparison_availability Comparison availability.
	 * @return bool
	 */
	private static function has_explicit_zero_summary( $current_availability, $comparison_availability ) {
		return (
			'zero' === $current_availability['value_state']
			&& ! empty( $current_availability['all_requested_found'] )
		) || (
			'zero' === $comparison_availability['value_state']
			&& ! empty( $comparison_availability['all_requested_found'] )
		);
	}

	/**
	 * Build one safe no-data warning entry.
	 *
	 * @param string $code     Warning code.
	 * @param string $category Warning category.
	 * @param string $severity Warning severity.
	 * @return array
	 */
	private static function build_warning( $code, $category, $severity ) {
		return array(
			'code'     => sanitize_key( $code ),
			'category' => sanitize_key( $category ),
			'severity' => sanitize_key( $severity ),
		);
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

			if ( 0.0 !== (float) $comparison ) {
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
