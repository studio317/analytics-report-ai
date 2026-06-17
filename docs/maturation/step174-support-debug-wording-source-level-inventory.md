# Step 174: Support / Debug Wording Source-level Inventory

## Step Purpose

Step 174 is a docs-only and inspection-only source-level inventory before
deciding whether Step 175 should perform narrow production wording changes for
support/debug-safe messages.

The purpose is to inventory current support/debug/admin wording surfaces,
classify their support/debug relevance, check for forbidden-evidence request
wording, and recommend a minimal Step 175 scope.

This step does not change production code, `readme.txt`, tools, build scripts,
admin UI behavior, credential storage, resolver logic, GA4 request behavior,
OpenAI request behavior, OAuth lifecycle behavior, or release packaging.

Result classification: `Support/debug wording source-level inventory completed`

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step168-credential-source-ui-wording-source-level-verification-results.md`
- `docs/maturation/step169-credential-source-ui-wording-admin-smoke-plan.md`
- `docs/maturation/step170-credential-source-ui-wording-human-admin-smoke-results.md`
- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`
- `docs/maturation/step172-support-debug-wording-alignment-checkpoint.md`
- `docs/maturation/step173-support-debug-wording-implementation-plan.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`

## Inspection Boundary

Inspection was limited to:

- production PHP source wording,
- localized admin JavaScript string source,
- `readme.txt` support/privacy wording source,
- existing maturation docs for Step 172 and Step 173,
- file paths,
- class, method, and function names,
- admin screen category,
- wording category,
- status-level label categories,
- translation/escaping patterns,
- source-level checks for wording that asks users to provide forbidden
  evidence.

This step did not inspect or record:

- database option values,
- OAuth token option values,
- serialized option values,
- credentials,
- API keys,
- access tokens,
- refresh tokens,
- Authorization headers,
- OAuth client ID values,
- OAuth client secret values,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- page path/source/city values,
- request bodies,
- raw GA4 responses,
- raw OpenAI responses,
- AI payload JSON,
- generated report bodies,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces,
- database rows,
- database dumps.

This step did not run Plugin Check, GA4 Fetch, OpenAI Generate, OAuth Connect /
Authorize, Google navigation, token endpoint communication, or browser admin
smoke.

## Inventory Table

| Surface category | Source file | Class / method / function | Current wording role | Support/debug relevance | Safety posture | Potential Step 175 action | Risk level |
|---|---|---|---|---|---|---|---|
| Settings admin notices / validation messages | `includes/class-settings.php` | `Analytics_Report_AI_Settings::sanitize_settings()` | Settings validation notices for GA4 property ID and host-name restoration. | Low support relevance; can be referenced as safe admin error category. | mostly_aligned | no_change | Low |
| Settings external service usage help text | `includes/class-settings.php` | `Analytics_Report_AI_Settings::render_page()` | Explains Google Analytics Data API and OpenAI API usage at status/category level. | Relevant to support/privacy orientation. | mostly_aligned | no_change | Low |
| Settings credential storage help text | `includes/class-settings.php` | `Analytics_Report_AI_Settings::render_page()` | Explains saved credentials, value-hidden posture, database access risk, manual token fallback, and Restricted OpenAI key recommendation. | Relevant to support/debug because it describes value-hidden credential posture. | mostly_aligned | add_safe_support_hint | Medium |
| Settings OAuth connection help text | `includes/class-settings.php` | `Analytics_Report_AI_Settings::render_page()` | Explains OAuth-first posture, status-level client configuration, connection state, and token value non-display. | Relevant to support/debug OAuth triage. | mostly_aligned | minor_wording_adjustment | Medium |
| Settings OAuth status notices | `includes/class-settings.php` | `Analytics_Report_AI_Settings::render_google_oauth_status_notice()` | Maps local OAuth/connect/callback/token-exchange categories to safe admin notices. | Highly relevant to support/debug category-level evidence. | aligned | no_change | Low |
| Settings manual token saved/not-saved label | `includes/class-settings.php` | `Analytics_Report_AI_Settings::render_page()` | Shows status-level manual token fallback state and keeps the value hidden. | Relevant to credential source support/debug triage. | aligned | no_change | Low |
| Settings OpenAI API key saved/not-saved label | `includes/class-settings.php` | `Analytics_Report_AI_Settings::render_page()` | Shows value-hidden OpenAI API key state and replacement/delete behavior. | Relevant to support/debug saved-state triage. | aligned | no_change | Low |
| Settings redirect URI setup field | `includes/class-settings.php` | `Analytics_Report_AI_Settings::render_page()` | Shows setup-oriented redirect URI field for future OAuth client configuration. | Not a support/debug evidence surface; screenshots/URLs should still be avoided. | defer | defer_until_later_track | Medium |
| Report Builder current settings table | `includes/class-report-builder.php` | `Analytics_Report_AI_Report_Builder::render_page()` | Shows current configuration status and credential source category. | Mixed: credential source label is safe; other displayed settings are admin UI state, not support evidence. | mostly_aligned | add_safe_support_hint | Medium |
| Report Builder credential source label | `includes/class-report-builder.php` | `Analytics_Report_AI_Report_Builder::render_page()` | Shows safe category label for GA4 credential source and states credential values are hidden. | Highly relevant to support/debug credential triage. | aligned | no_change | Low |
| Report Builder external service disclosure | `includes/class-report-builder.php` | `Analytics_Report_AI_Report_Builder::render_page()` | Explains GA4 Fetch inputs and that OpenAI key is not sent to Google. | Relevant to support/privacy orientation but includes data categories that should not become support evidence. | mostly_aligned | no_change | Medium |
| Missing credential messages | `includes/class-report-builder.php` | `Analytics_Report_AI_Report_Builder::get_ga4_credential_source_error_message()` | Returns safe missing/OAuth credential source messages with value-hidden wording. | Highly relevant to support/debug missing credential path. | aligned | no_change | Low |
| Submission notices | `includes/class-report-builder.php` | `Analytics_Report_AI_Report_Builder::render_submission_notices()` | Shows success, warning, and error summaries from submission results. | Relevant to status-level support triage. | mostly_aligned | minor_wording_adjustment | Medium |
| Payload Preview support/debug hint | `includes/class-report-builder.php` | `Analytics_Report_AI_Report_Builder::render_payload_preview()` | Instructs support sharing to status labels, warning messages, or error categories only, and excludes credentials, raw payloads, raw responses, and generated report text. | Core support/debug wording surface. | mostly_aligned | minor_wording_adjustment | Medium |
| Payload status warnings | `includes/class-report-builder.php` | `Analytics_Report_AI_Report_Builder::render_payload_status_notices()` | Frames no-data/partial/comparison conditions as status-level warnings and generation readiness cues. | Relevant to support/debug warning category evidence. | aligned | no_change | Low |
| Generation blocked messages | `includes/class-report-builder.php` | `Analytics_Report_AI_Report_Builder::get_generation_block_message()` | Explains generation blocked state without raw payload details. | Relevant to generation allowed/blocked support category. | aligned | no_change | Low |
| Payload warning messages | `includes/class-report-builder.php` | `Analytics_Report_AI_Report_Builder::get_payload_warning_messages()` / `get_payload_warning_message()` / `get_payload_warning_category_label()` | Converts payload warning metadata to safe user-facing warning labels. | Relevant to data availability and warning category support evidence. | aligned | no_change | Low |
| Generated report surrounding text | `includes/class-report-builder.php` | `Analytics_Report_AI_Report_Builder::render_generated_report()` | Explains generated report as draft, user reviewed/edited/copied, and not saved by plugin. | Relevant because generated report body should not be requested for support/debug. | mostly_aligned | minor_wording_adjustment | Medium |
| Copy report UI strings | `includes/class-admin.php` / `assets/js/admin.js` | `Analytics_Report_AI_Admin::enqueue_assets()` / `initializeCopyReport()` | Localized copy status strings for user-initiated copy action. | Low support/debug relevance. | aligned | no_change | Low |
| Admin permission messages | `includes/class-admin.php` | `handle_google_oauth_connect()` / `handle_google_oauth_callback()` | Permission-denied messages for credential-related admin actions. | Relevant as safe admin error category. | aligned | no_change | Low |
| OAuth implementation comments / helper boundaries | `includes/class-admin.php` | OAuth connect/callback/token helper methods | Source comments state raw state/code/token values are not displayed/logged and token responses are classified safely. | Relevant to source-level safety posture, not user-facing support wording. | aligned | no_change | Low |
| GA4 external API error messages | `includes/class-ga4-client.php` | `get_safe_api_error_message()` / `append_http_status()` | Normalizes GA4 API errors to safe user-facing categories and HTTP status without response body. | Relevant to safe admin error category. | mostly_aligned | no_change | Medium |
| OpenAI external API error messages | `includes/class-openai-client.php` | `get_safe_api_error_message()` / `append_http_status()` | Normalizes OpenAI API errors to safe user-facing categories and HTTP status without response body. | Relevant to safe admin error category. | mostly_aligned | no_change | Medium |
| Payload validation errors | `includes/functions-utils.php` | `analytics_report_ai_validate_ai_payload()` | Returns generic invalid-payload messages without raw payload output. | Relevant to payload status category. | aligned | no_change | Low |
| Path normalization errors | `includes/functions-utils.php` | `analytics_report_ai_normalize_report_path()` | Returns safe path validation messages. | Relevant to status-level input error support. | aligned | no_change | Low |
| Readme support guidance | `readme.txt` | Support/privacy wording section | Already states support requests should use status-level information and not include credentials, payloads, raw responses, report text, identifiers, or analytics values. | Relevant, but immediate Step 175 should not change readme. | mostly_aligned | defer_until_later_track | Medium |

## Forbidden Evidence Check

Source-level inspection did not find user-facing support/debug wording that asks
users to paste, upload, screenshot, dump, or record the following forbidden
evidence categories:

- credentials,
- API keys,
- access tokens,
- refresh tokens,
- Authorization headers,
- OAuth client ID values,
- OAuth client secret values,
- plugin option values,
- serialized option values,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- page path/source/city values,
- request bodies,
- raw GA4 responses,
- raw OpenAI responses,
- AI payload JSON,
- generated report bodies,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces,
- database rows,
- database dumps.

Observed safe posture:

- Report Builder includes an explicit support hint to share status labels,
  warning messages, or error categories only.
- Settings and Report Builder credential source wording keeps credential/token
  values hidden and uses status/category labels.
- OAuth status notices are category-level and state that raw details or token
  values are not displayed.
- GA4/OpenAI API error messages are normalized to safe messages plus HTTP
  status, without asking for raw request/response evidence.
- Payload validation errors are generic and do not ask users to share payload
  JSON.
- Readme support guidance already says support requests should avoid
  credentials, raw payloads, raw responses, generated report text,
  identifiers, and analytics values.

Notes:

- Some admin UI surfaces display configured identifiers or analytics-derived
  values for legitimate admin use. These are not support/debug evidence
  requests, but future support wording should continue to discourage
  screenshots or copied screen contents that include those values.
- The Settings redirect URI setup field is an OAuth setup surface, not a
  support/debug evidence request. Future support wording should avoid asking
  for full URLs or screenshots around this area.

## Translation / Escaping Readiness

Source-level readiness observations:

- Settings and Report Builder user-facing PHP strings are translation-ready
  through `__()`, `esc_html__()`, `esc_attr__()`, or related WordPress i18n
  helpers.
- Dynamic status/category labels are escaped before output with `esc_html()`.
- Input attribute values and placeholders are escaped with `esc_attr()` or
  `esc_attr__()` patterns.
- Generated report text is output with `esc_textarea()`.
- Admin JavaScript strings are passed through the localized
  `analyticsReportAiAdmin` object from PHP and have fallback strings for
  resilience.
- External API error messages are generated as translated strings and are
  later rendered through existing escaped notice output paths.

Potential follow-up:

- If Step 175 adds or adjusts admin UI wording, keep it in PHP strings with
  text domain `analytics-report-ai` and escape on output.
- If Step 175 adjusts JavaScript-facing copy, keep using the existing localized
  object and avoid hard-coded user-facing strings.
- If Step 175 changes readme/support docs later, keep that in a separate
  readme-scoped step rather than combining it with production PHP changes.

## Recommended Step 175 Scope

Recommended next step:

```text
Step 175: Narrow production wording implementation for support/debug-safe messages
```

Recommended minimal Step 175 scope:

- Keep the change limited to admin UI wording only.
- Do not change resolver, storage, GA4, OpenAI, OAuth, payload, transient, or
  generated report persistence behavior.
- Consider adding a short Settings support-safe hint that asks for
  status/category labels only and says not to share credentials, tokens, option
  values, OAuth client values, screenshots, Network evidence, raw responses,
  payload JSON, or generated report text.
- Consider lightly adjusting the existing Report Builder support hint to also
  mention screenshots/Network evidence and status-level categories, if the
  wording remains concise.
- Avoid `readme.txt` changes in Step 175; reserve readme alignment for a later
  scoped step if needed.
- Avoid any support bundle/export feature.

Alternative if production change is deferred:

```text
Step 175: Support/debug wording no-change checkpoint
```

This alternative is acceptable if the project decides the existing Report
Builder, Settings, and readme posture is sufficient for the current MVP
maturation scope. However, the inventory suggests a small Settings-side support
hint could improve consistency with Step 172 and Step 173 without changing
runtime behavior.

## Acceptance Criteria

Step 174 is complete when:

- the docs-only inventory file is added,
- production code has no Step 174 changes,
- `readme.txt` has no Step 174 changes,
- tools and build scripts have no Step 174 changes,
- current support/debug/admin wording surfaces are inventoried at source level,
- forbidden-evidence request wording is checked at category level,
- Step 175 recommended scope is clear,
- WordPress.org release remains `Hold`.

## Result Classification

Result: `Support/debug wording source-level inventory completed`

Rationale:

- Current Settings, Report Builder, admin JavaScript, external API error,
  utility error, and readme support surfaces were inventoried at source level.
- Forbidden-evidence request wording was checked at category level.
- Translation and escaping readiness was summarized.
- A minimal Step 175 production wording scope was recommended.

WordPress.org release remains `Hold`.

## Commands Executed

Safe docs-only / inspection-only commands:

```bash
git status --short --untracked-files=all
rg -n "support|debug|share|paste|screenshot|Network|raw|payload|response|credential|token|error|warning|Generate|generated|copy|option value|settings option" includes/class-settings.php includes/class-report-builder.php includes/class-admin.php assets/js/admin.js readme.txt
rg -n "esc_html__|esc_html_e|__\\(|esc_attr__|wp_kses_post|esc_html\\(|esc_attr\\(|esc_textarea\\(" includes/class-settings.php includes/class-report-builder.php includes/class-admin.php
rg -n "function |render_|get_.*message|status_message|settings_errors|add_settings_error|Payload Preview|Generated Report|Credential Storage|Google OAuth|Support" includes/class-settings.php includes/class-report-builder.php includes/class-admin.php
rg -n "share|paste|screenshot|Network|network|browser|raw|option value|generated report text|payload JSON|support|Do not share|Request and response details|Raw .*details" includes/class-settings.php includes/class-report-builder.php includes/class-admin.php assets/js/admin.js readme.txt docs/maturation/step172-support-debug-wording-alignment-checkpoint.md docs/maturation/step173-support-debug-wording-implementation-plan.md
rg -n "WP_Error|__\\(|error|response|request|raw|Authorization|payload|generated report|API key|access token" includes/class-ga4-client.php includes/class-openai-client.php includes/functions-utils.php
test -f docs/maturation/step174-support-debug-wording-source-level-inventory.md && echo "step174_doc_exists"
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```

