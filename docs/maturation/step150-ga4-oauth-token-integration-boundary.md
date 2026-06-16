# Step 150: GA4 OAuth Token Integration Boundary

## Step Summary

Step 150 records a docs-only and source-review-only boundary for future GA4
Fetch integration with the dedicated Google OAuth token option.

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

- `docs/maturation/step149-human-controlled-oauth-token-option-cleanup-execution-results.md`
- `docs/maturation/step147-human-controlled-token-exchange-smoke-results.md`
- `docs/maturation/step145-narrow-token-exchange-production-implementation-results.md`
- `docs/maturation/step143-token-storage-lifecycle-decision-checkpoint.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`
- `docs/maturation/step104-readme-privacy-wording-alignment-implementation-results.md`

## Step 149 Baseline

Status-level baseline:

- Step 147 token exchange smoke status: `token_exchange_success_category`
- Step 147 token storage smoke status: `token_storage_success_category`
- Step 147 Settings connection state after smoke: `connected`
- Step 149 OAuth token option cleanup status: `option_deleted_category`
- Local OAuth constants after Step 149: removed
- OAuth state transients cleanup: deferred
- Manual Google Access Token path: still preserved

This baseline records categories only. It does not record OAuth token option
values, serialized values, token values, plugin settings option values, manual
Google Access Token values, Authorization headers, GA4 Property ID values,
analytics values, database rows, screenshots, or Network evidence.

## Source Review Summary

Source review covered GA4 client, Report Builder, Settings, and credential
helper areas without running GA4 Fetch and without reading option values.

Status-level findings:

| Area | Current Source Posture | Status-Level Result |
|---|---|---|
| Current GA4 Fetch credential source | Report Builder reads the manual Google Access Token from plugin settings via `analytics_report_ai_get_settings()` and passes it to GA4 client calls. | `manual_token_source_currently_used` |
| Dedicated OAuth token option | Token storage and connection state helpers exist for the dedicated OAuth option, but GA4 Fetch does not yet use that option as a credential source. | `oauth_token_option_not_used_by_ga4_fetch` |
| GA4 client boundary | GA4 client methods accept an access token from the caller and build the GA4 request internally. | `ga4_client_accepts_caller_supplied_token` |
| Settings status | Settings can show OAuth connection state and manual token saved/not-saved status as labels without displaying values. | `settings_status_labels_available` |
| Report Builder status | Report Builder currently shows manual Google Access Token saved/not-saved status. | `report_builder_manual_status_only` |
| Insertion point | Future integration should happen before GA4 client calls, where Report Builder resolves the credential source. | `credential_resolution_before_ga4_client_call` |

No OAuth token option value, manual token value, plugin settings option value,
Authorization header, GA4 raw response, request body, analytics value, or
database row was inspected, displayed, or recorded.

## Credential Source Precedence Options

| Option | Precedence | Pros | Risks / Gaps | Status |
|---|---|---|---|---|
| A | OAuth token option preferred, manual token fallback | Aligns with the successful OAuth token exchange/storage smoke as the future primary path. Preserves the manual token path during MVP maturation. Allows narrow integration without removing existing Settings behavior. | Requires safe credential resolution helper and clear status labels. Refresh/reconnect lifecycle is still incomplete. Manual token retirement remains a public-release decision. | Recommended |
| B | Manual token preferred, OAuth token option fallback | Minimizes current behavior change and keeps existing MVP path primary. | Makes OAuth connection less useful after successful setup and delays transition away from manual token storage. May conflict with public-release posture. | Not recommended |
| C | Explicit user choice between OAuth connection and manual token | Most transparent and avoids hidden precedence. | Requires additional UI, state, wording, and testing. Larger than a narrow integration step. | Later consideration |

Recommended precedence:

```text
OAuth token option preferred, manual token fallback during MVP maturation
```

Rationale:

- OAuth connection reached successful status categories in Step 147, so it is a
  natural future primary credential path.
- The manual Google Access Token path should remain as an MVP maturation
  fallback until public-release lifecycle work is complete.
- Public release still needs a manual token retirement decision after refresh,
  reconnect, revoke, cleanup, and support wording are stable.

## GA4 Fetch Integration Boundary Matrix

| Boundary | Purpose | Planned Behavior | Allowed Status-Level Evidence | Forbidden Evidence | Implementation Dependencies | Likely Files / Classes / Functions To Touch Later |
|---|---|---|---|---|---|---|
| Credential source resolution helper | Centralize which credential source GA4 Fetch should use. | Add a helper that resolves OAuth token option first, then manual token fallback during MVP maturation. Return token material only to request-local runtime code, plus status labels for UI/errors. | `credential_source_oauth_connected`, `credential_source_manual_token`, `credential_source_missing`, `credential_source_oauth_refresh_needed`, `credential_source_oauth_error_category` | OAuth token option value, manual token value, plugin settings option value, token fragments, database rows. | Dedicated OAuth option helper, existing settings helper, Step 143 storage posture. | `includes/functions-utils.php`, `includes/class-report-builder.php` |
| OAuth connection state check | Avoid using missing or expired OAuth material as a GA4 credential. | Check OAuth connection state before selecting OAuth credential. Expired or refresh-needed states should not silently fall through unless policy says manual fallback is allowed. | `connected`, `not_connected`, `token_expired_or_refresh_needed`, `reconnect_required`, `oauth_error_category` | Token values, expiry values from real responses, refresh token values, option values. | Existing connection state helper; future refresh/reconnect plan. | `includes/functions-utils.php`, `includes/class-settings.php`, `includes/class-report-builder.php` |
| Access token availability category | Separate token availability from token value exposure. | If OAuth is selected, expose only whether request-local access token material is available to GA4 Fetch. Do not display, log, or document the value. | `oauth_access_token_available_category`, `oauth_access_token_missing_category`, `manual_access_token_available_category`, `access_token_missing_category` | Access token value, token fragments, Authorization header, option values, serialized values. | Safe accessor helper and redaction policy. | `includes/functions-utils.php`, `includes/class-report-builder.php` |
| Token expiry / refresh-needed category | Route expired OAuth state to safe UX before GA4 request. | If stored OAuth state is expired or refresh-needed, return a safe category. Do not attempt refresh until refresh implementation exists. Manual fallback may remain allowed during MVP maturation if explicitly categorized. | `credential_source_oauth_refresh_needed`, `reconnect_required`, `manual_token_fallback_used`, `ga4_fetch_blocked_refresh_needed` | Refresh token value, refresh request/response, provider raw error, token endpoint evidence. | Refresh/reconnect lifecycle plan. | `includes/functions-utils.php`, `includes/class-report-builder.php`, `includes/class-settings.php` |
| Manual token fallback category | Preserve MVP verification without hiding the selected credential path. | If OAuth is missing/unusable and manual token exists, use manual token as fallback and expose only a status label. | `credential_source_manual_token`, `manual_token_fallback_used`, `manual_token_missing` | Manual Google Access Token value, plugin settings option value, token fragments. | Existing settings storage and future manual retirement decision. | `includes/functions-utils.php`, `includes/class-report-builder.php`, `includes/class-settings.php` |
| GA4 request Authorization header boundary | Keep Authorization header request-local and non-recorded. | GA4 client may construct the Authorization header internally from a request-local token. It must never be displayed, logged, documented, included in support evidence, or returned in errors. | `authorization_header_request_local_only`, `authorization_header_not_recorded`, `ga4_request_attempted_yes_no` | Authorization header, bearer token, access token, token fragments, request body. | Existing GA4 client transport and redaction review. | `includes/class-ga4-client.php`, possibly `includes/class-report-builder.php` |
| GA4 error classification boundary | Keep provider failures safe and category-based. | GA4 errors should continue to map to safe WP_Error codes/messages. Do not record raw GA4 responses or provider raw body. Add credential-source-aware categories only if needed. | `ga4_request_failed_category`, `ga4_auth_error_category`, `ga4_permission_error_category`, `ga4_invalid_json_category`, `ga4_api_error_category` | Raw GA4 response, provider raw body, request body, Authorization header, analytics values. | Existing GA4 error classification and future credential source taxonomy. | `includes/class-ga4-client.php`, `includes/class-report-builder.php` |
| No-data handling compatibility | Preserve Step 91 no-data semantics. | Credential failures should remain transport/auth/configuration errors before payload creation. GA4 no-data responses should continue through `payload_status`, `data_availability`, and `value_semantics` without credential-source changes altering their meaning. | `payload_status_preserved`, `data_availability_preserved`, `value_semantics_preserved`, `credential_error_not_no_data` | Raw GA4 response, AI payload JSON, analytics values, page/source/city values. | Step 91 formatter and validation behavior. | `includes/class-report-data-formatter.php`, `includes/functions-utils.php`, `includes/class-report-builder.php` |
| Support/debug redaction boundary | Keep credential and GA4 evidence out of support flows. | Support should use status labels, credential source labels, warning/error categories, and redacted UI state only. | `status_label_only`, `credential_source_label_only`, `error_category_only`, `forbidden_evidence_recorded_no` | OAuth token option value, manual token value, Authorization header, GA4 raw response, AI payload JSON, raw API response, generated report body, screenshots, Network evidence. | Step 86 redaction policy and Step 104 wording posture. | `docs/maturation/*`, possible future admin/readme wording |
| Admin notice / Report Builder error category boundary | Make selected credential source understandable without exposing values. | Report Builder and Settings may show credential source status labels, not token values. Missing/expired/refresh-needed states should produce safe labels and messages. | `credential_source_oauth_connected`, `credential_source_manual_token`, `credential_source_missing`, `credential_source_oauth_refresh_needed`, `credential_source_oauth_error_category` | Token values, option values, plugin settings values, Authorization header, provider raw text. | Credential resolution helper and translatable admin copy. | `includes/class-report-builder.php`, `includes/class-settings.php` |

## Candidate Status Labels

Credential source labels for future Report Builder / Settings display:

```text
credential_source_oauth_connected
credential_source_manual_token
credential_source_missing
credential_source_oauth_refresh_needed
credential_source_oauth_error_category
```

These labels are safe only if they remain status-level. They must not include
OAuth token option values, manual token values, plugin settings option values,
Authorization headers, GA4 raw responses, analytics values, or request bodies.

## No-Data Handling Compatibility

The future GA4 OAuth credential integration should not change Step 91 no-data
semantics.

Compatibility boundary:

- Credential resolution happens before GA4 Fetch.
- Missing, expired, refresh-needed, or invalid credentials should be classified
  as credential/source errors, not as GA4 no-data.
- GA4 responses that contain no reportable current-period data should continue
  through the existing `payload_status`, `data_availability`, and
  `value_semantics` model.
- Generation blocking and warning behavior should remain driven by validated
  payload metadata, not by credential source labels.
- Raw GA4 responses, AI payload JSON, analytics values, and credential values
  must not be recorded as evidence.

## Support And Debug Redaction

Future support/debug evidence should not include:

- OAuth token option values,
- manual Google Access Token values,
- plugin settings option values,
- Authorization headers,
- GA4 raw responses,
- GA4 request bodies,
- AI payload JSON,
- OpenAI raw responses,
- generated report bodies,
- GA4 Property ID real values,
- hostnames/domains,
- page path, source, city, or analytics metric values,
- screenshots,
- browser Network evidence,
- database rows or database dumps.

Support/debug may use only:

- credential source labels,
- OAuth connection state labels,
- GA4 error categories,
- no-data / partial-data / comparison availability labels,
- generation allowed/blocked categories,
- redacted UI state.

## Future Implementation Notes

Future implementation should be narrow:

1. Add a credential source resolution helper.
2. Use OAuth token option first when connection state is usable.
3. Fall back to manual Google Access Token during MVP maturation.
4. Pass only request-local token material to GA4 client calls.
5. Return safe credential source labels for notices and Report Builder status.
6. Preserve existing GA4 no-data handling and payload validation.
7. Do not implement refresh, reconnect, revoke, manual token retirement, or
   uninstall cleanup in the same narrow GA4 integration step unless explicitly
   scoped.

## Next Step Options

| Option | Candidate | Summary | Notes |
|---|---|---|---|
| A | `Step 151: GA4 OAuth token integration implementation boundary` | Convert this boundary into a narrow implementation plan for credential resolution and GA4 Fetch integration. | Recommended because Step 150 defines credential source resolution and redaction boundaries clearly enough for a scoped production-code step. |
| B | `Step 151: Refresh / reconnect lifecycle plan` | Plan refresh, reconnect, and recovery behavior before integrating GA4 Fetch. | Important, but it can also follow the narrow integration boundary because manual fallback remains available during MVP maturation. |
| C | `Step 151: Credential source UI wording plan` | Plan Report Builder and Settings wording before production integration. | Useful if UI copy becomes the main risk, but the implementation boundary should define the runtime source selection first. |

Recommended next step:

```text
Step 151: GA4 OAuth token integration implementation boundary
```

Rationale: After Step 150, credential source resolution and redaction boundaries
are explicit. A narrow implementation boundary can now design production code
integration for GA4 Fetch without executing real GA4 Fetch and without exposing
credential material.

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
- database dump
- refresh implementation
- revoke implementation
- reconnect UI implementation
- uninstall cleanup implementation
- manual Google Access Token retirement
- Plugin Check
- `wp-dev-check` access
- forbidden evidence recording
