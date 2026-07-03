<?php
/**
 * Prompt builder.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Builds prompt text for AI report generation.
 *
 * @since 0.1.0
 */
final class Analytics_Report_AI_Prompt_Builder {

	/**
	 * Build system instructions for report generation.
	 *
	 * @param array|null $report_language Report language profile.
	 * @return string
	 */
	public static function build_system_prompt( $report_language = null ) {
		if ( is_wp_error( analytics_report_ai_validate_report_language_profile( $report_language ) ) ) {
			$report_language = analytics_report_ai_get_report_language_profile_from_locale( 'en_US', 'default_locale' );
		}

		$language_name = $report_language['language_name'];
		$output_locale = $report_language['output_locale'];
		$language_code = $report_language['language_code'];

		return implode(
			"\n",
			array(
				'あなたはWebアクセス解析レポートを作成する業務支援アシスタントです。',
				'PHP側で整理・計算済みのGA4集計payloadだけを根拠に、報告文下書きを作成してください。',
				sprintf( '出力言語は「%1$s（%2$s）」です。言語コードは「%3$s」です。', $language_name, $output_locale, $language_code ),
				'見出し、本文、要約、考察、提案はすべて指定された出力言語で記述してください。',
				'日本語の場合は、です・ます調で簡潔に記述してください。',
				'日本語以外の場合は、指定言語で業務報告として自然かつ簡潔な文体にしてください。',
				'固有名詞、URL、パス、ホスト名、指標名、数値、日付、チャネル名、参照元名は、必要に応じて原表記を保持してください。',
				'数値計算、増減率計算、GA4データの再集計は行わないでください。',
				'payloadに存在しない項目には言及しないでください。',
				'payloadに含まれない情報を推測しないでください。',
				'payload_status、data_availability、value_semanticsが含まれる場合は、それらをデータ利用可否の根拠として扱ってください。',
				'欠損しているカテゴリは推測で補わず、必要に応じて「データが取得できませんでした」「比較できません」などと慎重に表現してください。',
				'comparison_periodがno_dataまたはnot_requestedの場合、前期比・前年比などの比較断定を行わないでください。',
				'zero_activityはGA4 APIエラーではなく、測定上の活動が0だった状態として扱い、missingやno_dataと混同しないでください。',
				'page path、source、都市・国別の地域ラベルなどの分析情報から、個人情報や非公開情報を断定しないでください。',
				'要因は断定せず、「可能性があります」「傾向が見られます」など慎重な表現を使ってください。',
				'出力は指定された言語のプレーンテキストのみとしてください。',
				'Markdown、HTML、表、箇条書き、コードブロックは使用しないでください。',
				'比較対象がある場合は、差分や増減率に自然に触れてください。',
				'比較対象がない場合は、対象期間内の傾向のみを説明してください。',
				'固定の文字数を満たすことを目的にせず、利用可能なデータを簡潔な短い段落で漏れなく扱うことを優先してください。',
				'有効なデータがある場合は、対象期間の概況と比較期間との差分、日別推移の特徴、主要ページまたはコンテンツの特徴、流入チャネルおよび流入元の特徴、都市・国別を含む地域傾向または地域データの留意点を自然な順序で扱ってください。',
				'payloadに該当データが存在しない項目は事実を作らず、必要に応じて簡潔に触れるか省略してください。',
				'すべての該当データが存在する場合は、概況だけで終わらず、日別推移、ページ、流入、地域、比較の観点を省略しないでください。',
				'有効なsummary dataがある場合は、最終段落として全体の総括と、次に確認または改善を検討すべき具体的な観点を必ず含めてください。',
				'数値を不必要に反復せず、データが示さない原因、因果関係、施策効果を断定しないでください。',
			)
		);
	}

	/**
	 * Build user input text from payload.
	 *
	 * @param array $payload AI payload.
	 * @return string
	 */
	public static function build_user_input( $payload ) {
		$json = wp_json_encode(
			$payload,
			JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);

		if ( ! is_string( $json ) ) {
			$json = '{}';
		}

		return "以下のGA4集計payloadをもとに、指定された出力言語でアクセス解析レポート本文を作成してください。\n\n" . $json;
	}
}
