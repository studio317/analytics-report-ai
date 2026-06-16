# Step 151: GA4 OAuth Token Integration Implementation Boundary

## Step Summary

Step 151 converts the Step 150 GA4 OAuth token integration boundary into a
docs-only and source-review-only implementation boundary for a future narrow
production code step.

This step does not implement GA4 OAuth token integration, run GA4 Fetch, run
OpenAI Generate, execute browser OAuth, navigate to Google, operate Google
Cloud Console, execute callbacks, call token endpoints, exchange tokens, inspect
token storage values, refresh tokens, revoke tokens, run Plugin Check, or access
`wp-dev-check`.

This step does not change production PHP, JavaScript, CSS, assets, `readme.txt`,
build scripts, package files, settings save logic, GA4 behavior, OpenAI
behavior, OAuth behavior, token storage behavior, cleanup behavior, or admin UI
behavior.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step150-ga4-oauth-token-integration-boundary.md`
- `docs/maturation/step149-human-controlled-oauth-token-option-cleanup-execution-results.md`
- `docs/maturation/step147-human-controlled-token-exchange-smoke-results.md`
- `docs/maturation/step145-narrow-token-exchange-production-implementation-results.md`
- `docs/maturation/step143-token-storage-lifecycle-decision-checkpoint.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`
- `docs/maturation/step104-readme-privacy-wording-alignment-implementation-results.md`

## Step 150 Baseline

Status-level baseline:

- Current GA4 Fetch credential source: `manual_token_source_currently_used`
- Dedicated OAuth token option GA4 usage: `oauth_token_option_not_used_by_ga4_fetch`
- Recommended credential precedence: OAuth token option preferred, manual token
  fallback during MVP maturation
- Step 149 OAuth token option cleanup status: `option_deleted_category`
- Local OAuth constants after Step 149: removed
- Manual Google Access Token path: still preserved

This baseline records only status-level categories and option-name-level design
context. It does not record OAuth token option values, serialized values, plugin
settings option values, manual Google Access Token values, access tokens,
refresh tokens, Authorization headers, GA4 Property ID values, analytics values,
database rows, screenshots, or Network evidence.

## Source Review Summary

Source review covered GA4 client, Report Builder, Settings, credential helper,
and OAuth token helper areas without running GA4 Fetch and without reading
stored option values.

Status-level findings:

| Area | Current Posture | Implementation Boundary Result |
|---|---|---|
| Report Builder GA4 Fetch | Fetch handler gets the manual token from settings and passes one token argument to all GA4 client calls. | Future credential source resolution should be inserted before the first GA4 client call. |
| GA4 client | GA4 client receives token material from the caller and builds the request internally. | Keep token request-local; do not move UI/status decisions into GA4 client unless needed for safe error categories. |
| Utility helpers | Default/settings helpers normalize manual token storage; OAuth storage and connection state helpers exist. | Add a safe resolver/accessor helper near existing utility helpers. |
| Settings | Settings shows manual token saved/not-saved and OAuth connection state as labels. | Future UI should show credential source labels only, never values. |
| OAuth token option | Dedicated option exists in code, but Step 149 recorded local cleanup as `option_deleted_category`. | Future integration must tolerate missing OAuth option and fall back safely during MVP maturation. |

No OAuth token option value, manual token value, plugin settings option value,
Authorization header, GA4 raw response, request body, AI payload JSON, analytics
value, or database row was inspected, displayed, generated, or recorded.

## Credential Source Precedence

Recommended future production precedence:

```text
1. Usable OAuth token option
2. Manual Google Access Token fallback during MVP maturation
3. Missing credential category
```

The resolver should return a safe status label for UI/error handling and
request-local token material only for the GA4 Fetch runtime path.

Candidate safe labels:

```text
credential_source_oauth_connected
credential_source_manual_token
credential_source_missing
credential_source_oauth_refresh_needed
credential_source_oauth_error_category
manual_token_fallback_used
```

These labels must not include token values, token fragments, option values,
Authorization headers, GA4 response bodies, analytics values, or request bodies.

## Narrow Implementation Boundary Matrix

| Boundary | Purpose | Planned Production Behavior | Allowed Status-Level Evidence | Forbidden Evidence | Implementation Dependencies | Likely Files / Classes / Functions To Touch Later |
|---|---|---|---|---|---|---|
| Credential source resolution helper boundary | Centralize GA4 credential source selection. | Add a helper that resolves a usable OAuth token first, falls back to manual Google Access Token during MVP maturation, and returns a missing credential category when neither is available. The helper may return request-local token material only to the caller. | `credential_source_oauth_connected`, `credential_source_manual_token`, `credential_source_missing`, `manual_token_fallback_used` | OAuth token option value, manual token value, plugin settings option value, token fragments, serialized values, database rows. | Existing settings helper, OAuth connection state helper, Step 143 storage posture. | `includes/functions-utils.php`, `includes/class-report-builder.php` |
| OAuth token option safe accessor boundary | Allow runtime use of OAuth token material without exposing it. | Add a safe accessor that reads the dedicated OAuth token option and returns token material only to request-local runtime code when usable. Admin UI, docs, logs, support evidence, and WP_Error messages must receive only labels/categories. | `oauth_access_token_available_category`, `credential_source_oauth_connected`, `credential_source_oauth_refresh_needed`, `credential_source_oauth_error_category` | OAuth token option value, access token, refresh token, token fragments, serialized option values, database rows. | Dedicated OAuth option name constant, existing storage shape, connection state helper. | `includes/functions-utils.php` |
| Manual token fallback boundary | Preserve existing MVP path while making fallback explicit. | If OAuth token option is missing/unusable and a manual token exists, use the manual token as fallback for GA4 Fetch and expose only a fallback label. Do not display or record the manual token value. | `credential_source_manual_token`, `manual_token_fallback_used`, `manual_token_missing` | Manual Google Access Token value, plugin settings option value, token fragments. | Existing manual token settings behavior and future manual retirement decision. | `includes/functions-utils.php`, `includes/class-report-builder.php`, `includes/class-settings.php` |
| Connection state / expiry category boundary | Prevent expired or refresh-needed OAuth state from becoming ambiguous GA4 failure. | Treat expired, refresh-needed, reconnect-required, and OAuth error states as safe categories. Because refresh is not implemented, do not attempt refresh in the narrow GA4 integration step. Manual fallback may be allowed during MVP maturation if explicitly labeled. | `token_expired_or_refresh_needed`, `reconnect_required`, `credential_source_oauth_refresh_needed`, `ga4_oauth_refresh_needed_category` | Refresh token value, refresh request/response, provider raw error, token endpoint evidence, expiry values from real responses. | Existing connection state helper; future refresh/reconnect lifecycle plan. | `includes/functions-utils.php`, `includes/class-report-builder.php`, `includes/class-settings.php` |
| Report Builder credential status boundary | Make selected credential source understandable without exposing values. | Report Builder may display or return status labels such as OAuth connected, manual fallback, missing credential, refresh needed, or OAuth error. It must not show token values. | `credential_source_oauth_connected`, `credential_source_manual_token`, `credential_source_missing`, `credential_source_oauth_refresh_needed`, `credential_source_oauth_error_category`, `manual_token_fallback_used` | OAuth token value, manual token value, plugin settings option value, Authorization header, provider raw text. | Credential resolver helper and translatable admin copy. | `includes/class-report-builder.php`, `includes/class-settings.php` |
| GA4 client Authorization header boundary | Keep credential material request-local inside GA4 transport. | GA4 client can continue receiving a token argument and constructing the Authorization header internally. Token material and Authorization header must never be displayed, logged, documented, returned in errors, or included in support evidence. | `authorization_header_request_local_only`, `authorization_header_not_recorded`, `ga4_request_attempted_yes_no` | Authorization header, bearer token, access token, token fragments, request body. | Existing GA4 client transport and redaction review. | `includes/class-ga4-client.php`, possibly `includes/class-report-builder.php` |
| GA4 Fetch error classification boundary | Keep credential-source and GA4 provider failures safe and category-based. | Add credential-source-aware categories before GA4 request when credentials are missing/refresh-needed. Preserve existing safe GA4 error messages for provider responses; do not record raw GA4 response bodies. | `ga4_auth_error_category`, `ga4_permission_error_category`, `ga4_network_error_category`, `ga4_invalid_json_category`, `ga4_api_error_category`, `ga4_credential_source_missing_category`, `ga4_oauth_refresh_needed_category` | Raw GA4 response, provider raw body, provider details, request body, Authorization header, analytics values. | Existing GA4 error classification and future credential resolver. | `includes/class-ga4-client.php`, `includes/class-report-builder.php`, `includes/functions-utils.php` |
| No-data handling compatibility boundary | Preserve Step 91 no-data semantics. | Credential errors must be classified before payload creation and must not be represented as no-data. Real GA4 no-data responses should continue through `payload_status`, `data_availability`, and `value_semantics`. | `payload_status_preserved`, `data_availability_preserved`, `value_semantics_preserved`, `credential_error_not_no_data` | Raw GA4 response, AI payload JSON, analytics values, page/source/city values. | Step 91 formatter, validation, generation gate, and GA4 extraction behavior. | `includes/class-report-builder.php`, `includes/class-report-data-formatter.php`, `includes/functions-utils.php` |
| Support/debug redaction boundary | Prevent credentials and analytics evidence from entering support flows. | Support/debug should use credential source labels, OAuth connection state labels, GA4 error categories, no-data/warning labels, and redacted UI state only. | `status_label_only`, `credential_source_label_only`, `error_category_only`, `forbidden_evidence_recorded_no` | OAuth token option value, manual token value, Authorization header, GA4 raw response, AI payload JSON, analytics values, generated report body, screenshots, Network evidence, database dumps. | Step 86 redaction policy and Step 104 privacy/support wording. | `docs/maturation/*`, possible future admin/readme wording |
| Settings / Report Builder wording boundary | Align UI wording with selected credential source without expanding UI scope too much. | UI wording may mention OAuth credential source, manual fallback, missing credential, and refresh-needed categories. It must stay translatable and escaped. | `credential_source_oauth_connected`, `credential_source_manual_token`, `credential_source_missing`, `credential_source_oauth_refresh_needed`, `manual_token_fallback_used` | Token values, option values, raw provider text, Authorization header, GA4 response bodies. | Credential resolver, Settings status display, Report Builder status display. | `includes/class-settings.php`, `includes/class-report-builder.php` |
| Migration / manual token retirement deferral boundary | Avoid mixing migration/retirement with narrow GA4 integration. | Preserve manual Google Access Token during MVP maturation. Do not migrate, delete, hide, or retire manual token storage in the same narrow implementation step. Record retirement as a later public-release decision. | `manual_token_path_preserved`, `manual_token_retirement_pending`, `oauth_primary_path_planned` | Manual token value, option value, migration dumps, credential fragments. | Credential lifecycle and public-release UX plan. | Later docs and Settings implementation step, not the narrow GA4 integration step. |

## Candidate Helper Shape

Future helper shape should keep sensitive values request-local:

```text
analytics_report_ai_resolve_google_ga4_credential_source()
```

Possible request-local return categories:

- `status`: safe credential source label
- `access_token`: request-local runtime value only, never for UI/docs/logs
- `connection_state`: safe OAuth connection state label
- `fallback_used`: boolean/category only

The exact helper name and return shape can be adjusted during implementation,
but the boundary should remain:

```text
status labels may leave the helper; token values may only flow to GA4 runtime.
```

## Implementation Target Source Review

Likely future touch points:

| File / Class / Function | Likely Role |
|---|---|
| `includes/functions-utils.php` | Add credential source resolver and OAuth safe accessor helpers. |
| `includes/class-report-builder.php` | Replace direct manual-token lookup in GA4 Fetch with resolver output; pass request-local token to GA4 client calls; show safe status/error labels. |
| `includes/class-ga4-client.php` | Keep current caller-supplied token boundary; optionally refine safe credential-source-aware errors without exposing request/response data. |
| `includes/class-settings.php` | Optionally align status wording after implementation; show labels only. |

Files that should not be changed in the narrow GA4 credential source step unless
explicitly scoped:

- `readme.txt`
- JavaScript
- CSS
- build scripts
- release package files
- OpenAI client
- token exchange implementation
- refresh/revoke/reconnect implementation

## GA4 Fetch Error Categories

Candidate safe categories for the future implementation:

```text
ga4_auth_error_category
ga4_permission_error_category
ga4_network_error_category
ga4_invalid_json_category
ga4_api_error_category
ga4_credential_source_missing_category
ga4_oauth_refresh_needed_category
```

These categories must not include raw GA4 responses, provider raw bodies,
request bodies, Authorization headers, token values, analytics values, GA4
Property ID values, page paths, source values, city values, screenshots, or
Network evidence.

## No-Data Compatibility

The future implementation must keep credential/source failures separate from
Step 91 no-data handling.

Boundary:

- Missing credentials, expired OAuth state, refresh-needed OAuth state, and
  manual fallback absence are credential-source errors before GA4 Fetch.
- Credential-source errors must not create payloads and must not be interpreted
  as `no_data`.
- GA4 responses with no reportable current-period data should continue through
  the existing `payload_status`, `data_availability`, and `value_semantics`
  model.
- Generation allowed/blocked behavior should remain based on validated payload
  metadata, not credential source labels.

## Evidence Safety

This document does not record:

- OAuth token option values,
- serialized option values,
- plugin settings option values,
- manual Google Access Token values,
- access tokens,
- refresh tokens,
- Authorization headers,
- token endpoint requests or responses,
- authorization codes,
- callback URLs,
- query strings,
- raw state values,
- raw provider errors,
- client ID values,
- client secret values,
- GA4 Property ID values,
- analytics values,
- request bodies,
- GA4 raw responses,
- AI payload JSON,
- OpenAI raw responses,
- generated report bodies,
- screenshots,
- browser Network evidence,
- database rows or database dumps,
- email addresses or Google account identifiers,
- project IDs or project identifiers,
- hostname/domain values.

## Next Step Options

| Option | Candidate | Summary | Notes |
|---|---|---|---|
| A | `Step 152: Narrow GA4 OAuth credential source production implementation` | Implement the resolver/accessor boundary narrowly in production code and wire Report Builder GA4 Fetch to it without running real GA4 Fetch. | Recommended because Step 150 and Step 151 define credential source resolution and redaction boundaries clearly enough for a scoped implementation. |
| B | `Step 152: Credential source UI wording plan` | Plan UI wording before runtime integration. | Useful if UI copy is the main concern, but runtime credential source selection is now the sharper next boundary. |
| C | `Step 152: Refresh / reconnect lifecycle plan` | Plan refresh, reconnect, and recovery before GA4 integration. | Important public-release work, but refresh is explicitly out of the narrow GA4 credential source step and manual fallback remains available during MVP maturation. |

Recommended next step:

```text
Step 152: Narrow GA4 OAuth credential source production implementation
```

Rationale: Step 150 and Step 151 define credential source resolution, fallback,
status labels, and redaction boundaries. A narrow production implementation can
now add GA4 Fetch credential source selection while keeping GA4 Fetch execution,
OpenAI Generate, browser OAuth, Google navigation, token endpoint live
communication, Plugin Check, and `wp-dev-check` activity in separate steps that
CODEX must not execute unless explicitly scoped later.

## Explicit Non-Goals

- production code changes
- JavaScript, CSS, asset, `readme.txt`, build script, or package changes
- GA4 OAuth token integration implementation
- GA4 Fetch
- OpenAI Generate
- browser OAuth execution by CODEX
- Google navigation by CODEX
- Google Cloud Console operation by CODEX
- OAuth approval by CODEX
- callback live browser execution by CODEX
- token endpoint live communication by CODEX
- token exchange execution by CODEX
- token storage data inspection
- OAuth token option value inspection
- `wp option get analytics_report_ai_oauth_tokens`
- plugin settings option value display
- manual token value display
- database dump
- refresh implementation
- revoke implementation
- reconnect UI implementation
- uninstall cleanup implementation
- manual Google Access Token retirement
- Plugin Check
- `wp-dev-check` access
- forbidden evidence recording
