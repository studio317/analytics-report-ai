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
	 * @return string
	 */
	public static function build_system_prompt() {
		return implode(
			"\n",
			array(
				'あなたはWebアクセス解析レポートを作成する日本語の業務支援アシスタントです。',
				'PHP側で整理・計算済みのGA4集計payloadだけを根拠に、日本語の報告文下書きを作成してください。',
				'数値計算、増減率計算、GA4データの再集計は行わないでください。',
				'payloadに存在しない項目には言及しないでください。',
				'payloadに含まれない情報を推測しないでください。',
				'page path、source、cityなどの分析情報から、個人情報や非公開情報を断定しないでください。',
				'要因は断定せず、「可能性があります」「傾向が見られます」など慎重な表現を使ってください。',
				'出力は日本語のプレーンテキストのみとしてください。',
				'Markdown、HTML、表、箇条書き、コードブロックは使用しないでください。',
				'比較対象がある場合は、差分や増減率に自然に触れてください。',
				'比較対象がない場合は、対象期間内の傾向のみを説明してください。',
				'文章量は概ね800〜1,200字程度を目安にしてください。',
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

		return "以下のGA4集計payloadをもとに、日本語のアクセス解析レポート本文を作成してください。\n\n" . $json;
	}
}
