# Step 12 MVP Code Audit and Risk Classification

## 1. Overview

This document inventories the current Analytics Report AI MVP code after Steps 1-11 and classifies risks to address during the maturation phase.

Scope:

- `analytics-report-ai.php`
- `includes/class-plugin.php`
- `includes/class-admin.php`
- `includes/class-settings.php`
- `includes/class-report-builder.php`
- `includes/class-ga4-client.php`
- `includes/class-report-data-formatter.php`
- `includes/class-openai-client.php`
- `includes/class-prompt-builder.php`
- `includes/functions-utils.php`
- `assets/js/admin.js`
- `assets/css/admin.css`

Audit constraints:

- No production PHP, JS, or CSS implementation changes were made.
- No existing behavior was changed.
- No external API communication was performed for this audit.
- No Google Access Token, OpenAI API Key, or other credential value was recorded.

Line numbers and function names refer to the current MVP code at the time of this Step 12 audit.

## 2. Current MVP status

The MVP is an admin-only WordPress plugin that helps generate Japanese analytics report drafts from GA4 data.

Current flow:

1. `analytics-report-ai.php` defines plugin constants and loads `Analytics_Report_AI_Plugin`.
2. `Analytics_Report_AI_Plugin` loads dependencies and boots admin screens only when `is_admin()` is true.
3. `Analytics_Report_AI_Admin` registers a top-level admin menu and settings submenu for users with `manage_options`.
4. `Analytics_Report_AI_Settings` stores GA4 Property ID, host filter settings, a temporary Google Access Token, and an OpenAI API Key in `wp_options`.
5. `Analytics_Report_AI_Report_Builder` validates report conditions, fetches GA4 preset reports, formats an AI payload, stores it temporarily in a user-scoped transient, previews the payload, and can send the payload to OpenAI.
6. `Analytics_Report_AI_GA4_Client` sends report requests to the GA4 Data API.
7. `Analytics_Report_AI_Report_Data_Formatter` converts GA4 response data into a minimized payload shape.
8. `Analytics_Report_AI_OpenAI_Client` sends the formatted payload to the OpenAI Responses API.

Good MVP baseline:

- Admin screens are capability-gated with `manage_options`.
- Report Builder POST actions have a nonce check.
- Settings use the WordPress Settings API and `settings_fields()`.
- Output in PHP templates is broadly escaped with `esc_html()`, `esc_attr()`, and `esc_textarea()`.
- The OpenAI payload does not intentionally include credentials or the GA4 property ID.
- GA4 report rows are limited for top pages, channels, sources, and regional trends before sending to OpenAI.

Main maturation themes:

- Replace the manual Google Access Token flow.
- Define a safer credential storage strategy.
- Make external data transmission to OpenAI explicit and minimal.
- Redact or normalize raw external API error messages.
- Prepare for WordPress.org requirements around external services, privacy, JavaScript localization, Plugin Check, and coding standards.

## 3. File responsibility inventory

| File | Current responsibility | Security / data handling notes | Audit judgment |
| --- | --- | --- | --- |
| `analytics-report-ai.php` | Plugin header, constants, option name, OpenAI model constant, bootstrap include. | Has `ABSPATH` guard. Defines `ANALYTICS_REPORT_AI_OPTION_NAME` and `ANALYTICS_REPORT_AI_OPENAI_MODEL`. | Responsibility is clear. Public-release readiness depends on confirming the hard-coded model and external service documentation. |
| `includes/class-plugin.php` | Singleton-style bootstrap, dependency loading, textdomain loading, admin boot. | Has `ABSPATH` guard. Loads all PHP dependencies before admin boot. | No direct input/output risk found. Dependency loading is simple and acceptable for MVP. |
| `includes/class-admin.php` | Admin menu registration and admin asset enqueueing. | Menus require `manage_options`. Assets are enqueued only when the current admin screen ID contains `analytics-report-ai`. | Capability check is appropriate. Asset scope is acceptable for MVP. |
| `includes/class-settings.php` | Settings registration, settings sanitization, Settings page rendering. | Uses `register_setting()` with `sanitize_settings()`. Uses `settings_fields()` and checks `current_user_can( 'manage_options' )` in `render_page()`. Stores Google token and OpenAI key in plugin settings. | Form security and escaping are good. Credential storage is the largest risk in this file. |
| `includes/class-report-builder.php` | Report Builder UI, POST handling, report condition validation, GA4 fetch orchestration, payload transient storage, payload preview, OpenAI generation. | `render_page()` and `maybe_handle_request()` check `manage_options`. POST actions verify `analytics_report_ai_report_builder_nonce`. User input is unslashed and sanitized before validation. | Core MVP flow is coherent. Main concerns are external-send disclosure, transient data retention, raw API error messages, and cost/range controls. |
| `includes/class-ga4-client.php` | GA4 Data API request construction, response parsing, GA4 API error handling. | Sends Google Access Token only in the `Authorization` header. Request body includes date ranges, metrics, dimensions, host/path filters, and a Japan country filter for regional trends. Response dimensions are sanitized before payload formatting. | Request minimization is generally reasonable. The manual token model and raw API error display need maturation. |
| `includes/class-report-data-formatter.php` | Converts dummy or real GA4 data into the AI payload. Limits row counts for most preset reports. | Payload includes host filter status, host name, report conditions, summary metrics, top page paths, traffic sources, and city-level regional data. It does not include credentials or GA4 property ID. | Payload shape is compact and understandable. Add explicit policy for which analytics dimensions may leave WordPress. |
| `includes/class-openai-client.php` | OpenAI Responses API request construction, response parsing, OpenAI API error handling. | Sends OpenAI API Key only in the `Authorization` header. Sends prompt instructions and the full formatted payload as the request input. | The client is small and understandable. Public use needs API key storage policy, external-send consent, model verification, and safer error handling. |
| `includes/class-prompt-builder.php` | Builds system instructions and user input JSON for OpenAI. | Uses `wp_json_encode()` with Unicode/slash preservation and falls back to `{}` if encoding fails. | Good MVP behavior. Consider adding privacy and non-inference wording once the data policy is finalized. |
| `includes/functions-utils.php` | Default settings, option read helper, validation helpers, date utilities, path normalization, transient key/expiration, metric definitions, metric casting. | Default settings include `google_tokens` and `openai_api_key`. Payload transient key is scoped to the current user ID and expires after 30 minutes. | Utility boundaries are clear. Credential defaults and transient policy need maturation before public release. |
| `assets/js/admin.js` | Admin UI behavior for scope/path field, copy button, and confirmation buttons. | Uses `textContent` for dynamic UI messages. No credential values are read or written by JS. | Behavior is safe for MVP. Hard-coded English strings are a WordPress.org/i18n readiness issue. |
| `assets/css/admin.css` | Admin screen styling for cards, status table, payload preview, generated report, and copy status. | No data handling. | No security concern found. Future polish only. |

## 4. Security review

### Nonce and capability checks

| Area | Current status | Judgment |
| --- | --- | --- |
| Admin menus | `Analytics_Report_AI_Admin::register_menus()` uses `manage_options` for all plugin screens. | Good. Only administrators or equivalent roles can access these screens by default. |
| Settings form | `Analytics_Report_AI_Settings::render_page()` checks `current_user_can( 'manage_options' )`. The form uses `settings_fields( $this->settings_group )`, which provides nonce and Settings API handling. | Good. This is the expected WordPress settings pattern. |
| Report Builder form | `Analytics_Report_AI_Report_Builder::render_page()` checks `manage_options` and prints `wp_nonce_field()` for both GA4 fetch and AI generation forms. | Good. |
| Report Builder POST handling | `Analytics_Report_AI_Report_Builder::maybe_handle_request()` checks `current_user_can( 'manage_options' )` and verifies `wp_verify_nonce()` before action dispatch. | Good. |
| AJAX / REST endpoints | No AJAX or REST endpoint is registered in the audited code. | No issue for current MVP. |

### Sanitization and validation

Current strengths:

- `Analytics_Report_AI_Settings::sanitize_settings()` validates numeric GA4 Property IDs and rejects GA4 Measurement IDs such as `G-...`.
- Host names are normalized in `analytics_report_ai_normalize_host_name()`.
- Report dates are validated as `Y-m-d` via `analytics_report_ai_is_valid_date_string()`.
- Report scope and comparison values are checked against known option arrays in `Analytics_Report_AI_Report_Builder::validate_report_conditions()`.
- Full URLs are rejected for report paths in `analytics_report_ai_normalize_report_path()`.
- GA4 dimension values are passed through `sanitize_text_field()` in `Analytics_Report_AI_GA4_Client::extract_dimension_rows()`.

Concerns:

- `Analytics_Report_AI_Settings::sanitize_settings()` uses `sanitize_text_field()` for opaque credentials. This avoids HTML/control characters but can still mutate credential strings. For public use, validate token/key shape separately from storage and avoid unexpected credential corruption.
- No maximum date range is enforced in `Analytics_Report_AI_Report_Builder::validate_report_conditions()`. Large ranges can increase GA4 work and payload size, even though formatted rows are later limited.
- `Analytics_Report_AI_Report_Builder::handle_generate_ai_report()` trusts the transient payload shape once it exists. Because only admins can trigger the flow and the transient key is user-scoped, this is acceptable for MVP, but a shape/version check should be added before public use.

### Escaping

Current strengths:

- PHP UI output is broadly escaped with `esc_html()`, `esc_attr()`, `esc_html__()`, `esc_attr__()`, and `esc_textarea()`.
- Payload JSON preview is encoded with `wp_json_encode()` and escaped with `esc_html()` before display.
- Generated report text is rendered in a textarea with `esc_textarea()`.
- Error messages are rendered with `esc_html()` in `render_submission_notices()`.
- JavaScript dynamic text uses `textContent`, not `innerHTML`.

No immediate XSS issue was found in the audited display paths.

### `wp_options` sensitive data

Sensitive values currently saved under `ANALYTICS_REPORT_AI_OPTION_NAME` / `analytics_report_ai_settings`:

- `google_tokens['access_token']`
- `openai_api_key`

The settings UI intentionally avoids echoing saved credential values back into form fields. It shows only saved/not-saved placeholders and provides delete checkboxes. That is good MVP behavior.

The remaining risk is storage-level exposure: credentials are plain option values visible to database administrators, backups, object-cache/debug tooling, and any code with option access. This is not acceptable as-is for WordPress.org-oriented public release.

## 5. External service and data transmission review

### Google GA4 Data API

External call site:

- `Analytics_Report_AI_GA4_Client::run_report()` posts to `https://analyticsdata.googleapis.com/v1beta/properties/{property_id}:runReport`.

Data sent to Google:

- GA4 property ID in the request URL.
- Google Access Token in the `Authorization` header.
- Date range.
- Metric names such as `screenPageViews`, `activeUsers`, `newUsers`, `sessions`, `engagedSessions`, `engagementRate`, `bounceRate`, and `averageSessionDuration`.
- Dimension names such as `date`, `pagePath`, `sessionDefaultChannelGroup`, `sessionSource`, and `city`.
- Optional dimension filters for `hostName`, `pagePath`, and `country = Japan`.

Good points:

- The OpenAI API Key is not sent to Google.
- The request body does not include WordPress user data.
- Scope/path filters are derived from validated report conditions.

Risks:

- The manual Google Access Token is a temporary MVP method and has no refresh, expiry tracking, scope validation, revocation UI, or proper OAuth consent flow.
- API error messages are passed through to the admin UI after sanitization. They are escaped, but they may still expose implementation details or confuse non-technical users.
- The user-facing UI should explain exactly that clicking "Fetch GA4 Data" sends the selected date range, metrics, and dimensions to Google.

### OpenAI Responses API

External call site:

- `Analytics_Report_AI_OpenAI_Client::generate_report()` posts to `https://api.openai.com/v1/responses`.

Data sent to OpenAI:

- OpenAI API Key in the `Authorization` header.
- Model name from `ANALYTICS_REPORT_AI_OPENAI_MODEL`.
- System instructions from `Analytics_Report_AI_Prompt_Builder::build_system_prompt()`.
- User input containing the full formatted payload JSON from `Analytics_Report_AI_Prompt_Builder::build_user_input()`.

Payload fields sent to OpenAI:

- Plugin/payload metadata.
- Language and report type.
- Host filter status and host name.
- Date range, comparison setting, scope, and normalized path.
- Summary metrics and calculated differences.
- Daily trend rows, limited to 31 rows by `Analytics_Report_AI_Report_Data_Formatter::create_payload_from_ga4_summary()`.
- Top pages, limited to 10 rows.
- Traffic channels, limited to 10 rows.
- Traffic sources, limited to 10 rows.
- Regional city trends, limited to 10 rows.

Good points:

- The GA4 property ID is not included in the AI payload.
- Google Access Token and OpenAI API Key are not included in the payload.
- Raw GA4 responses are not sent wholesale. The code sends a reduced report payload.

Risks:

- Page paths, traffic source domains, city-level trends, and host names can still be sensitive business analytics data.
- The UI currently has a button description, but it should more explicitly state that payload data will be sent to OpenAI when the user generates a report.
- The prompt asks the model not to infer beyond the payload, which is good. It does not yet include privacy-oriented wording such as avoiding disclosure of sensitive identifiers if they appear in paths or source names.

### Payload preview safety

Display site:

- `Analytics_Report_AI_Report_Builder::render_payload_preview()`

Current behavior:

- Preview appears only after a successful payload creation or report generation.
- Preview is admin-only because the Report Builder page and POST handling are capability-gated.
- JSON preview is behind a `<details>` element.
- JSON is escaped before output.

Risk:

- The preview may display analytics details such as page paths, traffic sources, cities, and host names. These are not credentials, but they may be sensitive operational data. This is acceptable for an admin-only MVP, but the UI should label the preview as data that may be sent externally.

## 6. Credential storage review

### Google Access Token temporary method

Files/functions:

- `Analytics_Report_AI_Settings::sanitize_settings()`
- `Analytics_Report_AI_Settings::render_page()`
- `Analytics_Report_AI_GA4_Client::run_report()`

Current behavior:

- A Google Access Token can be pasted into a password field.
- Empty input preserves the existing token.
- A checkbox can remove the saved token.
- The token is stored under `google_tokens['access_token']`.
- The token is sent only as a Bearer token to the GA4 Data API.

MVP risk:

- This is explicitly temporary and appropriate only for local/self-use validation.
- Access tokens expire quickly and cannot be refreshed by the plugin.
- There is no OAuth consent flow, refresh-token handling, scope selection, revocation state, expiry timestamp, or reconnect UX.
- Storing a bearer token in `wp_options` means database or backup exposure can become account/API exposure until the token expires.

Recommended direction:

- Treat this as P0 for maturation.
- Replace manual token paste with a proper OAuth flow before any public or multi-user release.
- Track token expiry and connection state without exposing token values.
- Define minimum required Google scopes and document them in UI and readme.

### OpenAI API Key storage

Files/functions:

- `Analytics_Report_AI_Settings::sanitize_settings()`
- `Analytics_Report_AI_Settings::render_page()`
- `Analytics_Report_AI_OpenAI_Client::generate_report()`

Current behavior:

- An OpenAI API Key can be saved through a password field.
- Empty input preserves the existing key.
- A checkbox can remove the saved key.
- The key is stored under `openai_api_key`.
- The key is sent only as a Bearer token to the OpenAI API.

MVP risk:

- The key is not displayed in the admin UI after saving, which is good.
- The key is still stored as a plain option value.
- There is no key validation beyond non-empty input when generating.
- There is no alternative configuration path such as a constant, environment variable, or integration with a secrets manager.
- There is no autoload/storage policy separating credentials from ordinary plugin settings.

Recommended direction:

- Define whether the plugin should store OpenAI keys at all for public use.
- If storing is retained, split credentials from ordinary settings, prevent unnecessary autoload, add clear delete/revoke UX, and document backup/database exposure.
- Consider supporting a constant-based key for single-site self-hosted use.

## 7. Error handling and UI message review

Current strengths:

- Missing configuration errors are understandable: missing GA4 Property ID, missing Google token, missing OpenAI key, invalid date, invalid path, invalid nonce, and invalid action.
- Error output is escaped.
- The OpenAI restricted-key permission note in `Analytics_Report_AI_OpenAI_Client::build_api_error()` is useful for MVP debugging.

Concerns:

- `Analytics_Report_AI_GA4_Client::build_api_error()` displays sanitized API error messages directly.
- `Analytics_Report_AI_OpenAI_Client::build_api_error()` displays sanitized API error messages directly.
- `Analytics_Report_AI_GA4_Client::run_report()` and `Analytics_Report_AI_OpenAI_Client::generate_report()` include raw `WP_Error` messages from failed remote requests.
- These messages are admin-only and escaped, but before public release they should be mapped to safer, friendlier messages with optional developer diagnostics.
- `Analytics_Report_AI_Report_Builder::render_page()` still says actual OpenAI API integration will be implemented later, although `handle_generate_ai_report()` now calls the OpenAI API.

Recommended UI copy additions:

- Settings page: clearly mark Google Access Token input as local/MVP-only and not for public production use.
- Settings page: explain how stored API keys/tokens are protected and what storage limitations remain.
- Report Builder: explain that fetching data communicates with Google GA4.
- Payload Preview: explain that the preview contains the data that may be sent to OpenAI.
- Generate AI Report: explicitly state that clicking the button sends the payload to OpenAI.
- Generated Report Draft: remind users to review the draft before publishing or sending it.

## 8. WordPress Coding Standards / Plugin Check readiness

Current positives:

- PHP files have `ABSPATH` guards.
- Text domain is consistent: `analytics-report-ai`.
- PHP output escaping is generally present and appropriate.
- Report Builder POST actions use nonce and capability checks.
- Settings page uses the Settings API.
- No new dependency is introduced.
- No frontend/public output is registered.

Readiness concerns:

- JavaScript UI strings in `assets/js/admin.js` are hard-coded English strings and are not localized through WordPress i18n tooling.
- External services require clear readme/admin disclosure before WordPress.org submission: Google GA4 Data API and OpenAI Responses API, including what data is sent and when.
- Credential storage in `wp_options` is the biggest public-release concern.
- Plugin Check may flag direct remote API usage if service documentation and privacy disclosures are incomplete.
- Plugin Check/WPCS should be run as a dedicated step after the P0/P1 design decisions because several findings are likely policy/documentation issues rather than syntax issues.
- Public-release metadata should be reviewed outside this scoped file list, especially `readme.txt`, required/tested WordPress versions, PHP version, stable tag, license fields, and privacy/service disclosures.

No production code changes were made in this audit, so this document records readiness risks rather than applying coding-standard fixes.

## 9. Risk classification

Risk counts:

- P0: 3
- P1: 8
- P2: 7
- P3: 5

### P0: MVP maturation phase first

| ID | Area | Files/functions | Issue and reason | Recommended direction |
| --- | --- | --- | --- | --- |
| P0-1 | Google authentication | `Analytics_Report_AI_Settings::sanitize_settings()`, `Analytics_Report_AI_GA4_Client::run_report()` | Manual Google Access Token paste is explicitly temporary. It has no OAuth flow, refresh, expiry tracking, scope validation, or revocation UX. | Replace with a proper OAuth connection model before broad or repeated use beyond self-validation. |
| P0-2 | Credential storage | `analytics_report_ai_get_default_settings()`, `analytics_report_ai_get_settings()`, `Analytics_Report_AI_Settings::sanitize_settings()` | Google token and OpenAI key are stored as plain values in one `wp_options` setting. This is exposed to DB access, backups, and any code that can read options. | Define a credential storage policy: split credentials from normal settings, avoid unnecessary autoload, support deletion/revocation, document limitations, and consider constants or external secret storage for self-hosted use. |
| P0-3 | External-send consent and minimization | `Analytics_Report_AI_Report_Data_Formatter::create_payload_from_ga4_summary()`, `Analytics_Report_AI_Prompt_Builder::build_user_input()`, `Analytics_Report_AI_OpenAI_Client::generate_report()` | The OpenAI payload excludes credentials but includes analytics/business data such as host name, page paths, traffic sources, and city trends. The UI does not yet make this external transmission explicit enough. | Add a clear data transmission boundary: show what will be sent, why, and to whom; allow users to review before sending; finalize a minimum payload policy. |

### P1: Must address before WordPress.org/public release

| ID | Area | Files/functions | Issue and reason | Recommended direction |
| --- | --- | --- | --- | --- |
| P1-1 | Error safety | `Analytics_Report_AI_GA4_Client::build_api_error()`, `Analytics_Report_AI_OpenAI_Client::build_api_error()`, remote request failure handling | Raw external API and transport error messages are displayed to admins after sanitization. Escaping is good, but messages may expose implementation details or be too technical. | Map known error cases to safe user messages and keep detailed diagnostics behind an explicit debug mode or internal-only channel. |
| P1-2 | Credential validation | `Analytics_Report_AI_Settings::sanitize_settings()` | `sanitize_text_field()` may mutate opaque credential strings. It also does not validate expected key/token shape or provide targeted feedback. | Use credential-specific validation that avoids accidental mutation, rejects clearly invalid input, and never echoes values back. |
| P1-3 | Transient data policy | `analytics_report_ai_get_payload_transient_key()`, `analytics_report_ai_get_payload_transient_expiration()`, `Analytics_Report_AI_Report_Builder::handle_fetch_ga4_summary()` | The full AI payload is stored in a user-scoped transient for 30 minutes. It contains analytics data, not credentials, but may still be sensitive. | Confirm retention duration, user scope, cleanup behavior, and object-cache implications. Consider storing only a payload hash or shorter-lived payload if practical. |
| P1-4 | Range and payload-size controls | `Analytics_Report_AI_Report_Builder::validate_report_conditions()`, `Analytics_Report_AI_GA4_Client::run_daily_trend_report()` | No maximum report date range is enforced. Large ranges can create slow GA4 requests and large intermediate data even if the final payload is partially limited. | Add a maximum date range and explain it in the UI. Keep payload row limits aligned with that policy. |
| P1-5 | Cost and abuse guardrails | `Analytics_Report_AI_Report_Builder::handle_fetch_ga4_summary()`, `Analytics_Report_AI_Report_Builder::handle_generate_ai_report()` | Admin users can repeatedly trigger multiple GA4 requests and OpenAI generations. This is acceptable for self-use but risky for shared sites. | Add lightweight throttling, confirmation, or usage visibility before public release. |
| P1-6 | External service disclosure | `readme.txt` outside this audit scope, Settings UI, Report Builder UI | Public release requires clear disclosure of Google and OpenAI service usage, data sent, endpoints, and user action that triggers transmission. | Update readme/privacy/admin copy before WordPress.org submission. |
| P1-7 | JavaScript localization | `assets/js/admin.js` | Dynamic strings such as path help text, copy status, and copy failure text are hard-coded in English. | Localize JS strings using WordPress i18n/localized script data before public release. |
| P1-8 | Automated standards readiness | Full plugin | WPCS and Plugin Check were not remediated in this docs-only step. Several policy-oriented issues likely remain. | Run WPCS and Plugin Check in a dedicated readiness step, then fix findings without changing MVP behavior unnecessarily. |

### P2: Acceptable for continued MVP self-use, but improvement candidates

| ID | Area | Files/functions | Issue and reason | Recommended direction |
| --- | --- | --- | --- | --- |
| P2-1 | Stale UI copy | `Analytics_Report_AI_Report_Builder::render_page()` | Current Settings text says actual OpenAI API integration will be implemented later, but the MVP now calls OpenAI. | Update wording during UI polish. |
| P2-2 | Settings visibility | `Analytics_Report_AI_Report_Builder::render_page()` | Current Settings shows GA4 Property ID, host filter, and OpenAI key status, but not Google token status. | Add Google connection/token status once credential flow is redesigned. |
| P2-3 | Host/path validation strictness | `analytics_report_ai_normalize_host_name()`, `analytics_report_ai_normalize_report_path()` | Current normalization is reasonable for MVP but not a complete validator for all malformed host/path inputs. | Add stricter validation and clearer messages if public users will input arbitrary values. |
| P2-4 | Payload shape validation before OpenAI | `Analytics_Report_AI_Report_Builder::handle_generate_ai_report()` | Existing transient payload is trusted if it is an array. For MVP admin-only use this is low risk. | Verify `payload_version`, required keys, and expected value types before sending to OpenAI. |
| P2-5 | JSON encoding/decoding diagnostics | `Analytics_Report_AI_Prompt_Builder::build_user_input()`, GA4/OpenAI client response parsing | Encoding failure falls back to `{}` and invalid JSON errors are generic. This is safe but may be hard to debug. | Add clearer admin diagnostics without exposing secrets. |
| P2-6 | Test coverage | `includes/functions-utils.php`, `includes/class-report-data-formatter.php` | Date calculation, path normalization, metric casting, and payload formatting are logic-heavy but currently only manually inspected in this step. | Add focused tests or manual verification scripts during maturation. |
| P2-7 | Generated report persistence | `Analytics_Report_AI_Report_Builder::render_generated_report()` | Generated report text is not saved, which is safer for MVP, but users can lose output on navigation. | Keep unsaved-by-default behavior, but consider optional export/copy UX after privacy decisions. |

### P3: Future or safe to defer

| ID | Area | Files/functions | Issue and reason | Recommended direction |
| --- | --- | --- | --- | --- |
| P3-1 | Dummy fixture cleanup | `Analytics_Report_AI_Report_Data_Formatter::create_dummy_payload()` | Dummy payload generation remains in the code. It is not harmful, but it may be legacy Step 1-11 scaffolding. | Keep while useful for testing, or remove after real-data tests are stable. |
| P3-2 | Service abstraction | `Analytics_Report_AI_GA4_Client`, `Analytics_Report_AI_OpenAI_Client` | Static client methods are simple and fine for MVP, but harder to mock or extend. | Consider injectable services only if tests or multiple providers require it. |
| P3-3 | Clipboard fallback | `assets/js/admin.js` | `document.execCommand( 'copy' )` is used only as a fallback. | Keep for compatibility or replace later. |
| P3-4 | Regional configuration | `Analytics_Report_AI_GA4_Client::run_regional_trends_report()` | Regional trend filter is hard-coded to `country = Japan`. That matches the Japanese report MVP but is not configurable. | Add configuration only if reports need non-Japan audiences. |
| P3-5 | CSS/UI polish | `assets/css/admin.css` | The admin UI is functional but basic. | Improve responsive layout and accessibility polish after security and data-policy work. |

## 10. Recommended next steps

Recommended maturation order:

1. Define credential strategy first: Google OAuth flow, OpenAI key storage policy, delete/revoke behavior, and storage/autoload decisions.
2. Define data transmission policy: exact payload fields, preview labels, OpenAI consent wording, and minimum necessary analytics dimensions.
3. Normalize external API errors into safe user-facing messages, with optional debug-only diagnostics.
4. Add retention and cost guardrails: transient policy, maximum report range, payload size limits, and repeated-request controls.
5. Update admin UI copy for current behavior, especially OpenAI generation and Google token status.
6. Prepare WordPress.org readiness: readme disclosures, privacy/service documentation, JavaScript i18n, WPCS, and Plugin Check.
7. Add focused tests for date range calculations, path normalization, payload formatting, and API request body construction.

No production code change is recommended in this Step 12 document itself. The highest-value next implementation phase should start with P0-1, P0-2, and P0-3 because they define the safety boundary for all later polish.
