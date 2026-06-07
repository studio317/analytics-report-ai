<?php
/**
 * Report data formatter.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Analytics_Report_AI_Report_Data_Formatter {

	/**
	 * Create dummy AI payload.
	 *
	 * @param array $conditions Validated conditions.
	 * @param array $settings Plugin settings.
	 * @return array
	 */
	public static function create_dummy_payload( $conditions, $settings ) {
		$has_comparison = ! empty( $conditions['comparison'] ) && 'none' !== $conditions['comparison'];

		return array(
			'plugin'           => 'Analytics Report AI',
			'payload_version'  => '0.1.0-dummy',
			'language'         => 'ja',
			'report_type'      => 'ga4_summary',
			'site'             => self::build_site_payload( $settings ),
			'conditions'       => self::build_conditions_payload( $conditions ),
			'summary'          => self::build_dummy_summary( $has_comparison ),
			'daily_trend'      => self::build_dummy_daily_trend( $conditions ),
			'top_pages'        => self::build_dummy_top_pages(),
			'traffic_channels' => self::build_dummy_traffic_channels(),
			'traffic_sources'  => self::build_dummy_traffic_sources(),
			'regional_trends'  => self::build_dummy_regional_trends(),
		);
	}

	/**
	 * Create AI payload from GA4 summary data.
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
		$payload        = self::create_dummy_payload( $conditions, $settings );

		$payload['payload_version'] = '0.1.0-ga4-presets';
		$payload['summary']         = self::build_summary_from_ga4_values(
			$current_summary,
			$has_comparison ? $comparison_summary : array(),
			$has_comparison
		);

		if ( isset( $preset_reports['top_pages'] ) && is_array( $preset_reports['top_pages'] ) ) {
			$payload['top_pages'] = array_slice( $preset_reports['top_pages'], 0, 10 );
		}

		if ( isset( $preset_reports['traffic_channels'] ) && is_array( $preset_reports['traffic_channels'] ) ) {
			$payload['traffic_channels'] = array_slice( $preset_reports['traffic_channels'], 0, 10 );
		}

		if ( isset( $preset_reports['traffic_sources'] ) && is_array( $preset_reports['traffic_sources'] ) ) {
			$payload['traffic_sources'] = array_slice( $preset_reports['traffic_sources'], 0, 10 );
		}

		if ( isset( $preset_reports['regional_trends'] ) && is_array( $preset_reports['regional_trends'] ) ) {
			$payload['regional_trends'] = array_slice( $preset_reports['regional_trends'], 0, 10 );
		}

		return $payload;
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
	 * Build dummy summary.
	 *
	 * @param bool $has_comparison Whether comparison exists.
	 * @return array
	 */
	private static function build_dummy_summary( $has_comparison ) {
		return array(
			'screenPageViews'        => self::build_metric( '表示回数', 12840, 11320, 'count', $has_comparison ),
			'activeUsers'            => self::build_metric( 'アクティブユーザー数', 4210, 3980, 'count', $has_comparison ),
			'newUsers'               => self::build_metric( '新規ユーザー数', 2860, 2710, 'count', $has_comparison ),
			'sessions'               => self::build_metric( 'セッション数', 5360, 5010, 'count', $has_comparison ),
			'engagedSessions'        => self::build_metric( 'エンゲージメントのあったセッション数', 3180, 2940, 'count', $has_comparison ),
			'engagementRate'         => self::build_metric( 'エンゲージメント率', 0.593, 0.587, 'ratio', $has_comparison ),
			'bounceRate'             => self::build_metric( '直帰率', 0.407, 0.413, 'ratio', $has_comparison ),
			'averageSessionDuration' => self::build_metric( '平均セッション時間', 86.4, 81.2, 'seconds', $has_comparison ),
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
	 * @param string     $label Label.
	 * @param int|float  $current Current value.
	 * @param int|float  $comparison Comparison value.
	 * @param string     $unit Unit.
	 * @param bool       $has_comparison Whether comparison exists.
	 * @return array
	 */
	private static function build_metric( $label, $current, $comparison, $unit, $has_comparison ) {
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

	/**
	 * Build dummy daily trend.
	 *
	 * @param array $conditions Validated conditions.
	 * @return array
	 */
	private static function build_dummy_daily_trend( $conditions ) {
		$start_date = isset( $conditions['period']['start_date'] ) ? $conditions['period']['start_date'] : '';

		if ( ! analytics_report_ai_is_valid_date_string( $start_date ) ) {
			return array();
		}

		$date = DateTimeImmutable::createFromFormat( '!Y-m-d', $start_date );

		if ( ! $date ) {
			return array();
		}

		$rows = array();

		for ( $i = 0; $i < 7; $i++ ) {
			$rows[] = array(
				'date'            => $date->modify( '+' . $i . ' days' )->format( 'Y-m-d' ),
				'screenPageViews' => 320 + ( $i * 24 ),
				'activeUsers'     => 110 + ( $i * 7 ),
			);
		}

		return $rows;
	}

	/**
	 * Build dummy top pages.
	 *
	 * @return array
	 */
	private static function build_dummy_top_pages() {
		return array(
			array(
				'pagePath'        => '/',
				'screenPageViews' => 2540,
				'activeUsers'     => 980,
			),
			array(
				'pagePath'        => '/blog/',
				'screenPageViews' => 1860,
				'activeUsers'     => 720,
			),
			array(
				'pagePath'        => '/service/',
				'screenPageViews' => 1320,
				'activeUsers'     => 510,
			),
			array(
				'pagePath'        => '/contact/',
				'screenPageViews' => 640,
				'activeUsers'     => 210,
			),
		);
	}

	/**
	 * Build dummy traffic channels.
	 *
	 * @return array
	 */
	private static function build_dummy_traffic_channels() {
		return array(
			array(
				'defaultChannelGroup' => 'Organic Search',
				'activeUsers'         => 1840,
			),
			array(
				'defaultChannelGroup' => 'Direct',
				'activeUsers'         => 960,
			),
			array(
				'defaultChannelGroup' => 'Referral',
				'activeUsers'         => 520,
			),
			array(
				'defaultChannelGroup' => 'Organic Social',
				'activeUsers'         => 310,
			),
		);
	}

	/**
	 * Build dummy traffic sources.
	 *
	 * @return array
	 */
	private static function build_dummy_traffic_sources() {
		return array(
			array(
				'sessionSource' => 'google',
				'activeUsers'   => 1720,
			),
			array(
				'sessionSource' => '(direct)',
				'activeUsers'   => 960,
			),
			array(
				'sessionSource' => 'example.com',
				'activeUsers'   => 260,
			),
			array(
				'sessionSource' => 'bing',
				'activeUsers'   => 180,
			),
		);
	}

	/**
	 * Build dummy regional trends.
	 *
	 * @return array
	 */
	private static function build_dummy_regional_trends() {
		return array(
			array(
				'city'            => 'Tokyo',
				'screenPageViews' => 3240,
			),
			array(
				'city'            => 'Osaka',
				'screenPageViews' => 980,
			),
			array(
				'city'            => 'Yokohama',
				'screenPageViews' => 760,
			),
			array(
				'city'            => 'Nagoya',
				'screenPageViews' => 620,
			),
		);
	}
}