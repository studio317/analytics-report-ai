# Step 21 Focused Utility and Formatter Verification

## 1. Purpose

This document collects lightweight verification commands for important MVP helpers before introducing PHPUnit or a fuller test framework.

The commands are designed to run locally with `wp eval`. They do not fetch GA4 data, do not call the OpenAI API over the network, and do not print credential values, Authorization headers, full request bodies, or full AI payloads.

## 2. Runtime Assumptions

- Run from an environment where WordPress is available.
- The examples use `wp --path=/var/www/html/wp-dev --skip-plugins --skip-themes eval`.
- Each command explicitly loads `/var/www/html/analytics-report-ai/analytics-report-ai.php`.
- Adjust the `--path` value or plugin path if the local checkout moves.

## 3. Functions Covered

- Date range validation:
  - `analytics_report_ai_get_report_period_day_count()`
  - `analytics_report_ai_get_max_report_days()`
  - `analytics_report_ai_calculate_comparison_period()`
- Path normalization:
  - `analytics_report_ai_normalize_report_path()`
- Metric definitions and casting:
  - `analytics_report_ai_get_summary_metric_definitions()`
  - `analytics_report_ai_cast_metric_value()`
- Payload formatting and validation:
  - `Analytics_Report_AI_Report_Data_Formatter::create_payload_from_ga4_summary()`
  - `analytics_report_ai_validate_ai_payload()`
  - `analytics_report_ai_get_payload_row_limits()`
  - `analytics_report_ai_get_payload_version()`
- Prompt and pre-request construction:
  - `Analytics_Report_AI_Prompt_Builder::build_system_prompt()`
  - `Analytics_Report_AI_Prompt_Builder::build_user_input()`
  - `Analytics_Report_AI_OpenAI_Client::generate_report()` with `pre_http_request` interception only

## 4. Date Range and Comparison Periods

```bash
wp --path=/var/www/html/wp-dev --skip-plugins --skip-themes eval 'require_once "/var/www/html/analytics-report-ai/analytics-report-ai.php"; $result = array("max_days" => analytics_report_ai_get_max_report_days(), "may_31_days" => analytics_report_ai_get_report_period_day_count("2026-05-01", "2026-05-31"), "may_32_days" => analytics_report_ai_get_report_period_day_count("2026-05-01", "2026-06-01"), "reversed_days" => analytics_report_ai_get_report_period_day_count("2026-06-01", "2026-05-01"), "previous_month" => analytics_report_ai_calculate_comparison_period("2026-05-01", "2026-05-31", "previous_month"), "previous_year_leap" => analytics_report_ai_calculate_comparison_period("2024-02-29", "2024-03-31", "previous_year")); echo wp_json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";'
```

Expected result:

- `max_days` is `31`.
- `may_31_days` is `31`.
- `may_32_days` is `32`, which the Report Builder validation can reject against the max.
- `reversed_days` is `0`.
- `previous_month` maps May 2026 to April 2026 with the end date clamped to `2026-04-30`.
- `previous_year_leap` clamps `2024-02-29` to `2023-02-28`.

## 5. Path Normalization

```bash
wp --path=/var/www/html/wp-dev --skip-plugins --skip-themes eval 'require_once "/var/www/html/analytics-report-ai/analytics-report-ai.php"; $cases = array(array("label" => "site ignores path", "path" => "/blog/?utm_source=x#top", "scope" => "site"), array("label" => "directory query fragment", "path" => "blog/news?utm_source=x#top", "scope" => "directory"), array("label" => "page duplicate slashes", "path" => "//about///?x=1", "scope" => "page"), array("label" => "full URL rejected", "path" => "https://example.com/blog/", "scope" => "directory"), array("label" => "empty directory rejected", "path" => "   ", "scope" => "directory")); $result = array(); foreach ($cases as $case) { $value = analytics_report_ai_normalize_report_path($case["path"], $case["scope"]); $result[$case["label"]] = is_wp_error($value) ? array("error" => $value->get_error_code()) : array("path" => $value); } echo wp_json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";'
```

Expected result:

- Site scope returns an empty path.
- Directory scope strips query strings and fragments, adds a leading slash, collapses duplicate slashes, and keeps a trailing slash.
- Page scope strips query strings and fragments, adds a leading slash, and does not force a trailing slash. Existing trailing slashes may remain after duplicate slash normalization.
- Full URL input returns `analytics_report_ai_full_url_not_allowed`.
- Empty directory/page input returns `analytics_report_ai_empty_path`.

## 6. Metric Definitions and Casting

```bash
wp --path=/var/www/html/wp-dev --skip-plugins --skip-themes eval 'require_once "/var/www/html/analytics-report-ai/analytics-report-ai.php"; $definitions = analytics_report_ai_get_summary_metric_definitions(); $result = array("metric_count" => count($definitions), "screenPageViews_type" => $definitions["screenPageViews"]["type"], "engagementRate_type" => $definitions["engagementRate"]["type"], "screenPageViews_cast" => analytics_report_ai_cast_metric_value("screenPageViews", "12.9"), "engagementRate_cast" => analytics_report_ai_cast_metric_value("engagementRate", "0.625"), "unknown_metric_cast" => analytics_report_ai_cast_metric_value("unknownMetric", "7.25")); echo wp_json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";'
```

Expected result:

- Summary metric definitions include the MVP summary metrics.
- Integer metrics such as `screenPageViews` cast to integer values.
- Ratio/float metrics such as `engagementRate` cast to floats.
- Unknown metrics default to float casting.

## 7. Payload Formatting and Row Limits

```bash
wp --path=/var/www/html/wp-dev --skip-plugins --skip-themes eval 'require_once "/var/www/html/analytics-report-ai/analytics-report-ai.php"; $conditions = array("period" => array("start_date" => "2026-05-01", "end_date" => "2026-05-31"), "comparison" => "previous_month", "comparison_label" => "Previous month", "comparison_period" => array("start_date" => "2026-04-01", "end_date" => "2026-04-30"), "scope" => "directory", "scope_label" => "Directory", "path" => "/blog/"); $settings = array("host_filter_enabled" => 1, "host_name" => "example.test"); $current = array("screenPageViews" => "1200", "activeUsers" => "450", "newUsers" => "300", "sessions" => "620", "engagedSessions" => "410", "engagementRate" => "0.661", "bounceRate" => "0.339", "averageSessionDuration" => "83.5"); $comparison = array("screenPageViews" => "1000", "activeUsers" => "400", "newUsers" => "260", "sessions" => "580", "engagedSessions" => "360", "engagementRate" => "0.621", "bounceRate" => "0.379", "averageSessionDuration" => "77.0"); $daily = array(); for ($i = 1; $i <= 33; $i++) { $daily[] = array("date" => "2026-05-" . str_pad((string) min($i, 31), 2, "0", STR_PAD_LEFT), "screenPageViews" => $i * 10, "activeUsers" => $i); } $top_pages = array(); for ($i = 1; $i <= 12; $i++) { $top_pages[] = array("pagePath" => "/blog/page-" . $i . "/", "screenPageViews" => $i * 20, "activeUsers" => $i); } $preset = array("daily_trend" => $daily, "top_pages" => $top_pages, "traffic_channels" => array(array("defaultChannelGroup" => "Organic Search", "activeUsers" => 100)), "traffic_sources" => array(array("sessionSource" => "google", "activeUsers" => 90)), "regional_trends" => array(array("city" => "Tokyo", "screenPageViews" => 300))); $payload = Analytics_Report_AI_Report_Data_Formatter::create_payload_from_ga4_summary($conditions, $settings, $current, $comparison, $preset); $validation = analytics_report_ai_validate_ai_payload($payload); $result = array("payload_version" => $payload["payload_version"], "validation" => true === $validation ? "pass" : $validation->get_error_code(), "daily_trend_count" => count($payload["daily_trend"]), "top_pages_count" => count($payload["top_pages"]), "host_name" => $payload["site"]["host_name"], "path" => $payload["conditions"]["path"], "views_current" => $payload["summary"]["screenPageViews"]["current"], "views_comparison" => $payload["summary"]["screenPageViews"]["comparison"], "views_diff" => $payload["summary"]["screenPageViews"]["diff"]); echo wp_json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";'
```

Expected result:

- `payload_version` matches `analytics_report_ai_get_payload_version()`.
- Validation returns `pass`.
- `daily_trend_count` is capped at `31`.
- `top_pages_count` is capped at `10`.
- Host, path, summary current/comparison/diff values are present without printing the full payload.

## 8. Payload Validation Failures

```bash
wp --path=/var/www/html/wp-dev --skip-plugins --skip-themes eval 'require_once "/var/www/html/analytics-report-ai/analytics-report-ai.php"; $conditions = array("period" => array("start_date" => "2026-05-01", "end_date" => "2026-05-31"), "comparison" => "none", "comparison_label" => "No comparison", "comparison_period" => null, "scope" => "site", "scope_label" => "Entire site", "path" => ""); $settings = array("host_filter_enabled" => 1, "host_name" => "example.test"); $current = array("screenPageViews" => "1200", "activeUsers" => "450", "newUsers" => "300", "sessions" => "620", "engagedSessions" => "410", "engagementRate" => "0.661", "bounceRate" => "0.339", "averageSessionDuration" => "83.5"); $payload = Analytics_Report_AI_Report_Data_Formatter::create_payload_from_ga4_summary($conditions, $settings, $current, array(), array()); $bad_version = $payload; $bad_version["payload_version"] = "0.1.0-dummy"; $missing_summary = $payload; unset($missing_summary["summary"]); $too_many_rows = $payload; $too_many_rows["top_pages"] = array_fill(0, 11, array("pagePath" => "/x/", "screenPageViews" => 1)); $cases = array("valid" => $payload, "bad_version" => $bad_version, "missing_summary" => $missing_summary, "too_many_rows" => $too_many_rows, "not_array" => "not an array"); $result = array(); foreach ($cases as $label => $case_payload) { $validation = analytics_report_ai_validate_ai_payload($case_payload); $result[$label] = true === $validation ? "pass" : $validation->get_error_code(); } echo wp_json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";'
```

Expected result:

- `valid` is `pass`.
- `bad_version` returns an unexpected payload version error.
- `missing_summary` returns a missing summary error.
- `too_many_rows` returns a row limit error.
- `not_array` returns a not-array error.

## 9. Prompt and User Input Construction

```bash
wp --path=/var/www/html/wp-dev --skip-plugins --skip-themes eval 'require_once "/var/www/html/analytics-report-ai/analytics-report-ai.php"; $conditions = array("period" => array("start_date" => "2026-05-01", "end_date" => "2026-05-31"), "comparison" => "none", "comparison_label" => "No comparison", "comparison_period" => null, "scope" => "page", "scope_label" => "Page", "path" => "/about"); $settings = array("host_filter_enabled" => 1, "host_name" => "example.test"); $current = array("screenPageViews" => "1200", "activeUsers" => "450", "newUsers" => "300", "sessions" => "620", "engagedSessions" => "410", "engagementRate" => "0.661", "bounceRate" => "0.339", "averageSessionDuration" => "83.5"); $payload = Analytics_Report_AI_Report_Data_Formatter::create_payload_from_ga4_summary($conditions, $settings, $current); $system = Analytics_Report_AI_Prompt_Builder::build_system_prompt(); $input = Analytics_Report_AI_Prompt_Builder::build_user_input($payload); $result = array("system_length" => strlen($system), "input_length" => strlen($input), "system_mentions_japanese" => false !== strpos($system, "日本語"), "input_has_payload_version" => false !== strpos($input, analytics_report_ai_get_payload_version()), "input_has_path" => false !== strpos($input, "\"path\": \"/about\""), "input_starts_with_instruction" => 0 === strpos($input, "以下のGA4集計payload")); echo wp_json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";'
```

Expected result:

- System prompt and user input are non-empty.
- The system prompt includes Japanese report-writing guidance.
- The user input contains the payload version and selected path.
- The command does not print the full prompt or payload.

## 10. OpenAI Pre-Request Body Summary Without Network

Use this only to verify that `generate_report()` builds the expected pre-request shape. The `pre_http_request` filter short-circuits the HTTP call before any external API communication occurs. The command prints only request body keys and booleans, not the Authorization header and not the full request body.

```bash
wp --path=/var/www/html/wp-dev --skip-plugins --skip-themes eval 'require_once "/var/www/html/analytics-report-ai/analytics-report-ai.php"; $conditions = array("period" => array("start_date" => "2026-05-01", "end_date" => "2026-05-31"), "comparison" => "none", "comparison_label" => "No comparison", "comparison_period" => null, "scope" => "site", "scope_label" => "Entire site", "path" => ""); $settings = array("host_filter_enabled" => 1, "host_name" => "example.test"); $current = array("screenPageViews" => "1200", "activeUsers" => "450", "newUsers" => "300", "sessions" => "620", "engagedSessions" => "410", "engagementRate" => "0.661", "bounceRate" => "0.339", "averageSessionDuration" => "83.5"); $payload = Analytics_Report_AI_Report_Data_Formatter::create_payload_from_ga4_summary($conditions, $settings, $current); $summary = array(); add_filter("pre_http_request", function ($pre, $args, $url) use (&$summary) { $body = isset($args["body"]) ? json_decode($args["body"], true) : array(); $summary = array("url_host" => wp_parse_url($url, PHP_URL_HOST), "body_keys" => is_array($body) ? array_keys($body) : array(), "has_instructions" => isset($body["instructions"]) && is_string($body["instructions"]) && "" !== $body["instructions"], "has_input" => isset($body["input"]) && is_string($body["input"]) && "" !== $body["input"], "input_has_payload_version" => isset($body["input"]) && false !== strpos($body["input"], analytics_report_ai_get_payload_version()), "max_output_tokens" => isset($body["max_output_tokens"]) ? (int) $body["max_output_tokens"] : 0); return array("headers" => array(), "body" => wp_json_encode(array("output_text" => "stub report text")), "response" => array("code" => 200, "message" => "OK"), "cookies" => array(), "filename" => null); }, 10, 3); $result_text = Analytics_Report_AI_OpenAI_Client::generate_report($payload, array("openai_api_key" => "not-a-real-key")); $summary["result_is_error"] = is_wp_error($result_text); $summary["result_text_length"] = is_wp_error($result_text) ? 0 : strlen($result_text); echo wp_json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";'
```

Expected result:

- `url_host` is `api.openai.com`.
- `body_keys` contains `model`, `instructions`, `input`, and `max_output_tokens`.
- `has_instructions`, `has_input`, and `input_has_payload_version` are `true`.
- `result_is_error` is `false` because the HTTP response is stubbed locally.
- No external API call is made because WordPress returns from `pre_http_request`.

## 11. Intentionally Not Introduced

This step intentionally does not introduce:

- PHPUnit,
- Composer changes,
- a mock framework,
- a new fixtures directory,
- dependency injection or service rewiring,
- GA4 external API smoke tests,
- OpenAI external API smoke tests,
- credential validation against real services.

## 12. Future Test Foundation

When the plugin is ready for a fuller test setup, convert these checks into focused tests around:

- inclusive date range validation and max-day rejection,
- comparison period clamping,
- report path normalization and URL rejection,
- metric casting,
- payload row limits and required payload shape,
- prompt construction,
- safe OpenAI request assembly with HTTP mocked before network.
