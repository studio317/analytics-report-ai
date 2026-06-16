# Step 144: Token Exchange Implementation Boundary

## Step Summary

Step 144 creates a docs-only and source-review-only production-code boundary
for a future narrow OAuth token exchange implementation.

This step follows the Step 143 token storage lifecycle decision checkpoint. It
does not implement token exchange, token storage, refresh, revoke, reconnect
UI, GA4 Fetch, OpenAI Generate, Plugin Check, browser OAuth, Google navigation,
Google Cloud Console operation, callback execution, or external API
communication.

Production PHP, JavaScript, CSS, assets, `readme.txt`, build scripts, release
package files, settings save logic, GA4 client behavior, OpenAI client
behavior, OAuth behavior, token lifecycle behavior, and admin UI behavior were
not changed.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step143-token-storage-lifecycle-decision-checkpoint.md`
- `docs/maturation/step142-token-exchange-storage-implementation-plan.md`
- `docs/maturation/step141-human-controlled-oauth-approval-callback-smoke-results.md`
- `docs/maturation/step121-google-oauth-state-protection-callback-validation-boundary-implementation-results.md`
- `docs/maturation/step120-google-oauth-skeleton-admin-action-boundary-implementation-results.md`

## Step 143 Storage Lifecycle Baseline

| Field | Baseline |
|---|---|
| OAuth material storage | Dedicated plugin option |
| Proposed option name | `analytics_report_ai_oauth_tokens` |
| Autoload policy | Non-autoload |
| Access token / refresh token values | Never displayed, logged, documented, or included in support evidence |
| Connection state | Status-level categories only |
| Manual Google Access Token field | Remains during MVP maturation |
| Uninstall | Removes OAuth token option material and relevant state transients |
| Deactivate | Preserves OAuth token material |

This baseline is a boundary for later implementation. This step records no
option values, serialized option values, token values, token fragments, request
bodies, response bodies, or credential material.

## Evidence Boundary

Only status-level source-review and implementation-boundary evidence is
recorded.

This document does not record:

- authorization URLs,
- browser address bar URLs,
- callback URLs,
- query strings,
- raw state values,
- authorization codes,
- raw provider errors,
- provider error codes,
- token endpoint request bodies,
- token endpoint response bodies,
- token endpoint URLs with parameters,
- access tokens,
- refresh tokens,
- ID tokens,
- token type values or token fragments tied to a real response,
- `expires_in` values from a real response,
- Authorization headers,
- plugin settings option values,
- serialized option values,
- client ID values,
- client secrets,
- hostname/domain values,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonce values,
- credentials,
- API keys,
- GA4 Property ID values,
- analytics values,
- request bodies,
- AI payload JSON,
- raw API responses,
- generated report bodies,
- email addresses or Google account identifiers,
- project IDs or project identifiers.

## Source Review Summary

Status-level source review found the following likely insertion points for a
future narrow production implementation:

| Area | Existing Source Boundary | Likely Later Insertion Point |
|---|---|---|
| Connect action | `includes/class-admin.php` handles capability, nonce, client configuration, state placeholder creation, authorization URL construction, and redirect. | No token exchange should be added to connect. Keep exchange in callback after state validation. |
| State placeholder | `includes/class-admin.php` creates a user-scoped state placeholder and stores only a hash plus metadata. | Reuse existing state validation boundary; keep raw state request-local and never output it. |
| Callback action | `includes/class-admin.php` handles callback capability and redirects back to Settings with a safe status category. | Add token exchange only after valid state, no provider error, and authorization code presence are established. |
| Callback classification | `includes/class-admin.php` classifies state missing, expired, invalid, provider error, code present, and no code categories without recording raw values. | A future implementation may need a request-local internal exchange branch for valid code-present callbacks without exposing the raw code to notices or docs. |
| Settings notices | `includes/class-settings.php` maps OAuth status categories to translatable admin notices. | Add token exchange/storage notice categories as safe labels only. |
| Client config status | `includes/class-settings.php` reports client configuration as status-level constant presence only. | Continue using status-level configuration labels; never display client values. |
| Manual token path | `includes/class-settings.php`, `includes/functions-utils.php`, and Report Builder currently support manual Google Access Token storage/use. | Preserve during MVP maturation; do not force retirement in token exchange slice. |
| OAuth token storage | No dedicated OAuth token option helper is implemented yet. | Introduce a dedicated non-autoload option helper in a later implementation step, without exposing values. |
| OAuth token exchange HTTP call | No OAuth token endpoint request implementation was found. | Add a narrow WordPress HTTP API helper later, with request/response redaction by design. |

The source review did not execute browser OAuth, callbacks, external API calls,
token endpoint requests, token storage, refresh, revoke, GA4 Fetch, or OpenAI
Generate.

## Production-Code Boundary Matrix

| Boundary | Purpose | Planned Production Behavior | Allowed Status-Level Evidence | Forbidden Evidence | Implementation Dependencies | Likely Files / Classes / Functions To Touch Later |
|---|---|---|---|---|---|---|
| Callback preconditions before exchange | Ensure exchange starts only from a valid admin callback state. | Recommended order: current user capability / admin context check, nonce or action boundary where applicable, OAuth state validation, provider error absence, authorization code presence, token exchange trigger. | `exchange_preconditions_met_category_only`, `exchange_preconditions_failed_category_only`, `token_exchange_not_executed`. | Raw authorization code, callback URL, query string, raw state, provider raw error, provider error code. | Existing callback action and state validation; admin notice taxonomy. | `Analytics_Report_AI_Admin::handle_google_oauth_callback()`, callback classification helper. |
| Capability / nonce / state validation boundary | Keep token exchange behind existing admin and state boundaries. | Capability remains required. Connect nonce remains on connect. Callback must validate state before exchange. Raw state remains request-local and is never logged or displayed. | `callback_state_valid_category_only`, `callback_state_invalid_category_only`, `callback_state_missing_category_only`, `callback_state_expired_category_only`. | Raw state, state hash, callback URL, query string, cookies, nonces, screenshots. | Existing state transient behavior and single-use invalidation. | `Analytics_Report_AI_Admin::classify_google_oauth_callback()`, state transient helper. |
| Provider error short-circuit boundary | Avoid exchange when provider returned an error category. | If provider error category is detected, stop before token exchange and return safe notice category. | `provider_error_category_only`, `token_exchange_not_executed`. | Raw provider error, provider error code, raw Google screen text, callback URL, query string. | Callback classification and notice taxonomy. | Callback classification helper, Settings notice renderer. |
| Authorization code presence boundary | Trigger exchange only when code presence is detected after valid state and no provider error. | Raw code may be consumed only inside request-local server-side control flow. It must never be displayed, saved directly, logged, documented, or passed to support evidence. | `authorization_code_presence_category_only`, `authorization_code_absence_category_only`, `token_exchange_triggered_category_only`. | Raw authorization code, callback URL, query string, browser URL, screenshots, Network evidence. | Callback classification may need internal result structure instead of string-only status. | Callback handler and a future exchange helper. |
| One-time callback handling / replay prevention boundary | Prevent duplicate callbacks from reusing state or causing repeated exchange. | State transient remains single-use. Duplicate callbacks after deletion should become expired/invalid category and should not exchange. | `callback_state_expired_category_only`, `duplicate_callback_no_exchange`, `token_exchange_not_executed`. | Raw state, raw code, token response, request body. | Existing transient deletion behavior; exchange idempotency design. | State transient helper, callback handler. |
| WordPress HTTP API request boundary | Define how future token endpoint exchange will be called. | Future implementation should use the WordPress HTTP API from server-side PHP. Step 144 performs no external communication and generates no request body or endpoint evidence. | `wordpress_http_api_planned`, `token_request_not_executed`, `external_call_not_executed`. | Token endpoint request body, token endpoint response body, token endpoint URL with parameters, client secret, authorization code. | Client secret constant boundary, callback code boundary, response classification. | New private helper in `Analytics_Report_AI_Admin` or a dedicated OAuth client class. |
| Token endpoint response classification boundary | Convert provider responses into safe categories only. | Classify response success/failure without logging or displaying raw response body. | `token_exchange_success_category`, `token_exchange_invalid_grant_category`, `token_exchange_provider_error_category`, `token_exchange_network_error_category`, `token_exchange_malformed_response_category`, `token_exchange_missing_token_category`, `token_exchange_not_executed`. | Raw response body, access token, refresh token, ID token, token type fragments, real expiry values, provider raw error. | HTTP helper, response parser, redaction policy. | Future exchange helper, Settings notice renderer. |
| Token storage handoff boundary | Hand off classified token material to storage without exposing values. | On success, pass request-local token material to dedicated storage helper. Store only required lifecycle fields in dedicated non-autoload option. | `token_storage_handoff_planned`, `token_stored_yes_no_only`, `oauth_option_non_autoload_planned`. | Option value, serialized option value, token value, token fragment, database row, backups. | Step 143 storage posture, storage helper implementation. | Future OAuth token storage helper, possibly functions/utilities and uninstall cleanup. |
| Admin notice category boundary | Keep admin UI notices category-only. | Settings notices should report success/failure category and connection state without raw provider text or token evidence. | `token_exchange_success_category`, `token_exchange_invalid_grant_category`, `token_exchange_provider_error_category`, `token_exchange_network_error_category`, `token_storage_unavailable_category`, `reconnect_required_category`. | Raw provider text, provider error code, request body, response body, callback URL, query string, token values. | Notice taxonomy and Settings renderer. | `Analytics_Report_AI_Settings::render_google_oauth_status_notice()`. |
| Connection state update boundary | Derive connection state from storage lifecycle metadata. | Update status-level connection state only after successful storage. Failure should leave connection not connected or safe error category. | `connected`, `not_connected`, `token_expired_or_refresh_needed`, `reconnect_required`, `oauth_error_category`. | Token values, account identifiers, option values, provider raw errors. | Storage helper, expiry metadata, Settings UI. | Settings helper, future OAuth storage helper, Report Builder credential status display. |
| Support/debug evidence boundary | Prevent sensitive OAuth evidence from entering support flows. | Support evidence remains limited to status labels, connection state, and admin notice category. | `status_label_only`, `connection_state_only`, `admin_notice_category_only`, `forbidden_evidence_recorded_no`. | Authorization code, callback URL, query string, raw state, token endpoint request/response, tokens, Authorization header, option values. | Step 86 redaction policy and future support wording. | Support docs, Settings help text, QA templates. |
| Logging prohibition boundary | Prevent accidental logging or display of OAuth secrets. | Do not log, display, document, or include sensitive callback/token material in admin UI, debug output, docs, or support evidence. | `sensitive_logging_prohibited`, `forbidden_evidence_recorded_no`. | Authorization code, callback URL, query string, raw state, token endpoint request body, token endpoint response body, access token, refresh token, Authorization header, option values, client secret. | Code review checklist, error handling helper, no raw exception output. | Future exchange helper, storage helper, admin notices. |
| Retry / duplicate callback behavior | Define safe behavior for repeated callback attempts. | Duplicate callback should not exchange after state is consumed. Retry should require a fresh connect flow and new state. | `duplicate_callback_no_exchange`, `fresh_connect_required`, `reconnect_required_category`. | Old state, old code, callback URL, query string, token response. | State transient single-use behavior, admin notice taxonomy. | Callback handler, state helper, Settings notice renderer. |
| Failure rollback behavior | Avoid partial credential persistence on failed exchange/storage. | If exchange or storage fails, do not save partial token material. Set safe error/connection status. Keep manual token path available during MVP maturation. | `partial_token_material_not_saved`, `token_storage_failed_category`, `oauth_error_category`, `manual_token_entry_remains_for_mvp_maturation`. | Partial token values, option values, serialized values, raw response body. | Storage helper transaction-like behavior, error taxonomy. | Future exchange helper, storage helper, Settings notice renderer. |

## Recommended Callback Preconditions

The future implementation should evaluate callback exchange preconditions in
this order:

1. Current user capability / admin context check.
2. Nonce or action boundary where applicable.
3. OAuth state validation.
4. Provider error absence.
5. Authorization code presence.
6. Token exchange trigger.

Any failed precondition should return a safe status-level notice category and
must not attempt token exchange.

## Token Exchange Request Boundary

Future implementation may use the WordPress HTTP API for token exchange.

Step 144 does not:

- perform external communication,
- generate token endpoint request bodies,
- display endpoint details,
- use client secrets,
- use authorization code values,
- record request/response evidence.

The future helper should be designed so request construction and response
handling cannot leak sensitive values into logs, admin notices, docs, or support
output.

## Token Response Classification Boundary

Future implementation should classify token endpoint outcomes into safe labels:

- `token_exchange_success_category`
- `token_exchange_invalid_grant_category`
- `token_exchange_provider_error_category`
- `token_exchange_network_error_category`
- `token_exchange_malformed_response_category`
- `token_exchange_missing_token_category`
- `token_exchange_not_executed`

Raw provider response bodies, provider error codes, token endpoint request
bodies, access tokens, refresh tokens, ID tokens, token fragments, token type
values tied to real responses, and real expiry values must not be recorded.

## Storage Handoff Boundary

The exchange helper should hand off successful token material to the Step 143
dedicated non-autoload option posture.

Boundary:

```text
exchange classification -> request-local token material -> storage helper -> status-level connection state
```

No option value, serialized value, token value, token fragment, database row, or
backup evidence should be recorded.

## Connection State Update Boundary

Safe connection states:

- `connected`
- `not_connected`
- `token_expired_or_refresh_needed`
- `reconnect_required`
- `oauth_error_category`

Connection state should be derived from storage lifecycle metadata and should
not reveal account identity, token values, provider raw errors, or option
values.

## Admin Notice Boundary

Admin notices should use safe category labels only.

They must not display:

- raw provider text,
- provider error codes,
- token endpoint request bodies,
- token endpoint response bodies,
- callback URLs,
- query strings,
- raw state,
- authorization codes,
- token values,
- option values,
- client secret values.

## Logging Prohibition Boundary

The following must not be written to logs, docs, support evidence, admin UI, or
debug output:

- authorization code,
- callback URL,
- query string,
- raw state,
- token endpoint request body,
- token endpoint response body,
- access token,
- refresh token,
- Authorization header,
- option values,
- serialized option values,
- client secret.

## Failure Rollback Boundary

If token exchange fails:

- do not save partial token material,
- do not update connection state to connected,
- return a safe error category,
- keep the manual Google Access Token path available during MVP maturation,
- do not expose raw provider response or request details.

If token storage fails after a successful exchange category:

- do not leave partial option material,
- return a safe storage failure category,
- keep connection state not connected or error-category only,
- keep manual token path available.

## Explicit Non-Execution

The following were not executed or implemented in Step 144:

- browser OAuth by CODEX,
- Google navigation by CODEX,
- Google Cloud Console operation by CODEX,
- external API communication,
- OAuth authorization approval,
- callback execution,
- token endpoint request,
- token endpoint response handling,
- token exchange,
- token storage,
- token refresh,
- token revoke,
- reconnect UI,
- GA4 Fetch,
- OpenAI Generate,
- Plugin Check,
- `wp-dev-check` access.

## Next Step Options

| Option | Candidate Step | What It Would Do | Pros | Risks / Deferrals |
|---|---|---|---|---|
| Option A | `Step 145: Narrow token exchange production implementation` | Implement the smallest production PHP slice for callback-valid token exchange classification and storage handoff boundaries. | Builds on Step 143 storage posture and Step 144 exchange boundary. Keeps implementation narrow. | Must avoid browser/API smoke by CODEX and must not output token/request/response values. |
| Option B | `Step 145: Token storage helper implementation boundary` | Implement or plan storage helpers before exchange code. | Further isolates non-autoload option handling and redaction. | May delay exchange implementation and still require exchange boundary work. |
| Option C | `Step 145: Admin notice taxonomy implementation boundary` | Implement or plan token-specific notice category mapping first. | Improves UI safety before exchange. | Does not implement exchange or storage handoff. |

Recommended next step:

```text
Step 145: Narrow token exchange production implementation
```

Reason: Step 143 records the storage lifecycle posture and Step 144 records the
token exchange implementation boundary. With both in place, token exchange can
be implemented as a narrow production PHP slice without deciding storage policy
during code work.

Step 145 should still keep real OAuth approval, browser execution, external API
smoke, and Google navigation separate. CODEX should not run Google OAuth or
external communication. Any implementation should be verified by source review
and local syntax checks only unless a later step explicitly authorizes a
human-controlled smoke.

## Confirmation Commands

Planned docs-only/source-review-only confirmation commands:

```bash
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```
