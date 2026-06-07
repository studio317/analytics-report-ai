<?php
/**
 * OpenAI API client.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Analytics_Report_AI_OpenAI_Client {

	/**
	 * Create dummy report text from payload.
	 *
	 * This method does not call the OpenAI API.
	 * It will be replaced by the actual API request in a later step.
	 *
	 * @param array $payload AI payload.
	 * @return string
	 */
	public static function create_dummy_report( $payload ) {
		$conditions = isset( $payload['conditions'] ) && is_array( $payload['conditions'] ) ? $payload['conditions'] : array();
		$summary    = isset( $payload['summary'] ) && is_array( $payload['summary'] ) ? $payload['summary'] : array();

		$period            = isset( $conditions['period'] ) && is_array( $conditions['period'] ) ? $conditions['period'] : array();
		$comparison_period = isset( $conditions['comparison_period'] ) && is_array( $conditions['comparison_period'] ) ? $conditions['comparison_period'] : null;
		$scope_label       = isset( $conditions['scope_label'] ) ? (string) $conditions['scope_label'] : '';
		$path              = isset( $conditions['path'] ) ? (string) $conditions['path'] : '';

		$start_date = isset( $period['start_date'] ) ? (string) $period['start_date'] : '';
		$end_date   = isset( $period['end_date'] ) ? (string) $period['end_date'] : '';

		$paragraphs = array();

		$intro = '本レポートは、Analytics Report AI のMVP検証用に生成されたダミーレポートです。';

		if ( '' !== $start_date && '' !== $end_date ) {
			$intro .= '対象期間は' . $start_date . 'から' . $end_date . 'です。';
		}

		if ( '' !== $scope_label ) {
			$intro .= '取得範囲は「' . $scope_label . '」です。';
		}

		if ( '' !== $path ) {
			$intro .= '対象パスは ' . $path . ' です。';
		}

		if ( is_array( $comparison_period ) ) {
			$intro .= '比較期間は' . $comparison_period['start_date'] . 'から' . $comparison_period['end_date'] . 'です。';
		} else {
			$intro .= '比較対象は設定されていません。';
		}

		$paragraphs[] = $intro;

		if ( ! empty( $summary ) ) {
			$views          = isset( $summary['screenPageViews'] ) ? $summary['screenPageViews'] : null;
			$active_users   = isset( $summary['activeUsers'] ) ? $summary['activeUsers'] : null;
			$new_users      = isset( $summary['newUsers'] ) ? $summary['newUsers'] : null;
			$sessions       = isset( $summary['sessions'] ) ? $summary['sessions'] : null;
			$engagement     = isset( $summary['engagementRate'] ) ? $summary['engagementRate'] : null;
			$bounce_rate    = isset( $summary['bounceRate'] ) ? $summary['bounceRate'] : null;
			$avg_duration   = isset( $summary['averageSessionDuration'] ) ? $summary['averageSessionDuration'] : null;
			$summary_text   = '主要指標を見ると、';
			$summary_parts  = array();

			if ( is_array( $views ) ) {
				$summary_parts[] = '表示回数は' . self::format_metric_value( $views ) . 'でした';
			}

			if ( is_array( $active_users ) ) {
				$summary_parts[] = 'アクティブユーザー数は' . self::format_metric_value( $active_users ) . 'でした';
			}

			if ( is_array( $new_users ) ) {
				$summary_parts[] = '新規ユーザー数は' . self::format_metric_value( $new_users ) . 'でした';
			}

			if ( is_array( $sessions ) ) {
				$summary_parts[] = 'セッション数は' . self::format_metric_value( $sessions ) . 'でした';
			}

			if ( ! empty( $summary_parts ) ) {
				$summary_text .= implode( '、', $summary_parts ) . '。';
			}

			if ( is_array( $engagement ) || is_array( $bounce_rate ) || is_array( $avg_duration ) ) {
				$quality_parts = array();

				if ( is_array( $engagement ) ) {
					$quality_parts[] = 'エンゲージメント率は' . self::format_metric_value( $engagement );
				}

				if ( is_array( $bounce_rate ) ) {
					$quality_parts[] = '直帰率は' . self::format_metric_value( $bounce_rate );
				}

				if ( is_array( $avg_duration ) ) {
					$quality_parts[] = '平均セッション時間は' . self::format_metric_value( $avg_duration );
				}

				$summary_text .= implode( '、', $quality_parts ) . 'となっています。';
			}

			if ( is_array( $views ) && isset( $views['change_rate'] ) && null !== $views['change_rate'] ) {
				$summary_text .= '表示回数は比較期間に対して' . self::format_change_rate_sentence( $views['change_rate'] ) . 'しています。';
			}

			$paragraphs[] = $summary_text;
		}

		if ( ! empty( $payload['top_pages'] ) && is_array( $payload['top_pages'] ) ) {
			$top_page = reset( $payload['top_pages'] );

			if ( is_array( $top_page ) && ! empty( $top_page['pagePath'] ) ) {
				$paragraphs[] = '上位ページでは、' . $top_page['pagePath'] . ' の閲覧が最も多く、サイト内の主要な入口または閲覧対象になっている可能性があります。その他の上位ページも含め、どのページがアクセスを集めているかを確認することで、今後の改善対象を整理しやすくなります。';
			}
		}

		if ( ! empty( $payload['traffic_channels'] ) && is_array( $payload['traffic_channels'] ) ) {
			$top_channel = reset( $payload['traffic_channels'] );

			if ( is_array( $top_channel ) && ! empty( $top_channel['defaultChannelGroup'] ) ) {
				$paragraphs[] = '流入チャネルでは、' . $top_channel['defaultChannelGroup'] . ' からの利用が目立っています。ただし、この結果だけで流入増減の要因を断定することは避け、参照元や上位ページの傾向と合わせて確認する必要があります。';
			}
		}

		if ( ! empty( $payload['traffic_sources'] ) && is_array( $payload['traffic_sources'] ) ) {
			$top_source = reset( $payload['traffic_sources'] );

			if ( is_array( $top_source ) && ! empty( $top_source['sessionSource'] ) ) {
				$paragraphs[] = '参照元では、' . $top_source['sessionSource'] . ' からのアクセスが上位に入っています。検索、直接流入、外部サイト経由などの内訳を継続的に確認することで、集客経路の変化を把握しやすくなります。';
			}
		}

		if ( ! empty( $payload['regional_trends'] ) && is_array( $payload['regional_trends'] ) ) {
			$top_region = reset( $payload['regional_trends'] );

			if ( is_array( $top_region ) && ! empty( $top_region['city'] ) ) {
				$paragraphs[] = '地域別では、' . $top_region['city'] . ' からの表示回数が多い傾向です。地域データは推定を含むため、単独で判断するのではなく、サイトの対象エリアや流入経路と合わせて参考情報として扱うのが適切です。';
			}
		}

		$paragraphs[] = '以上はダミーデータに基づく検証用の文章です。実データ連携後は、GA4から取得した集計値をもとに、同じ画面フローで日本語レポート本文を生成します。';

		return implode( "\n\n", $paragraphs );
	}

	/**
	 * Format metric current value.
	 *
	 * @param array $metric Metric payload.
	 * @return string
	 */
	private static function format_metric_value( $metric ) {
		$value = isset( $metric['current'] ) ? $metric['current'] : null;
		$unit  = isset( $metric['unit'] ) ? (string) $metric['unit'] : '';

		if ( null === $value ) {
			return '';
		}

		if ( 'ratio' === $unit ) {
			return round( (float) $value * 100, 1 ) . '%';
		}

		if ( 'seconds' === $unit ) {
			return round( (float) $value, 1 ) . '秒';
		}

		if ( is_numeric( $value ) ) {
			return number_format_i18n( (float) $value );
		}

		return (string) $value;
	}

	/**
	 * Format change rate sentence.
	 *
	 * @param float $change_rate Change rate.
	 * @return string
	 */
	private static function format_change_rate_sentence( $change_rate ) {
		$percentage = round( abs( (float) $change_rate ) * 100, 1 );

		if ( (float) $change_rate > 0 ) {
			return $percentage . '%増加';
		}

		if ( (float) $change_rate < 0 ) {
			return $percentage . '%減少';
		}

		return '横ばいで推移';
	}
}