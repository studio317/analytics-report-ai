# Step 143: Token Storage Lifecycle Decision Checkpoint

## Step Summary

Step 143 creates a docs-only decision checkpoint for OAuth token storage and
lifecycle posture before any token exchange implementation.

This step follows the Step 142 token exchange and storage implementation plan.
It does not execute or implement token exchange, token storage, refresh, revoke,
reconnect UI, GA4 Fetch, OpenAI Generate, Plugin Check, browser OAuth, Google
navigation, Google Cloud Console operation, callback execution, or external API
communication.

Production PHP, JavaScript, CSS, assets, `readme.txt`, build scripts, release
package files, settings save logic, GA4 client behavior, OpenAI client
behavior, OAuth behavior, token lifecycle behavior, and admin UI behavior were
not changed.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step142-token-exchange-storage-implementation-plan.md`
- `docs/maturation/step141-human-controlled-oauth-approval-callback-smoke-results.md`
- `docs/maturation/step139-oauth-approval-callback-boundary-decision-checkpoint.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`

## Evidence Boundary

Only status-level lifecycle decisions and safe option-name proposals are
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

## Storage Lifecycle Decisions

| Area | Decision / Proposed Posture | Rationale | Allowed Status-Level Evidence | Forbidden Evidence | Implementation Dependency |
|---|---|---|---|---|---|
| Storage option structure | Store OAuth material in a dedicated plugin option separate from general settings. Hypothetical option name: `analytics_report_ai_oauth_tokens`. | Separating OAuth material from general settings reduces accidental display, makes cleanup easier, and avoids overloading the existing settings option. | `dedicated_oauth_option_planned`, `general_settings_separation_planned`, option name only. | Option values, serialized values, token values, token fragments, database dumps. | Token exchange boundary, settings read helpers, uninstall cleanup. |
| Autoload policy | Dedicated OAuth token option should be non-autoloaded. | Token material is not needed on every WordPress request and should not be loaded broadly. | `oauth_option_non_autoload_planned`, `autoload_suppressed_for_oauth_option`. | Option value, autoload row with serialized value, database output. | Add/update option call design and migration policy. |
| Access token storage | Store only what is required for connection operation and GA4 access. Never display, log, document, or include access token values in support evidence. | Access token is credential material and may grant API access until expiry. | `access_token_storage_planned`, `access_token_value_redacted`, `token_stored_yes_no_only`. | Access token value, token fragments, Authorization header, option value. | Token exchange implementation, GA4 client integration boundary. |
| Refresh token storage | Store refresh token only if returned and needed for lifecycle. Treat missing refresh token as a supported status requiring reconnect or reapproval fallback. | Refresh tokens are higher-risk durable credentials and may not be returned in every authorization flow. | `refresh_token_received_category`, `refresh_token_missing_category`, `reapproval_required_category`, `refresh_token_value_redacted`. | Refresh token value, token fragments, token response body, account identifiers. | Token response classification, reconnect/reapproval UX, revoke/disconnect policy. |
| Expiry metadata | Store minimal expiry metadata required to derive connection state. Do not record real provider response expiry values in docs or support. | Expiry is required for safe GA4 use, but raw provider response details are not useful support evidence. | `expiry_metadata_planned`, `token_valid_category`, `token_expired_or_refresh_needed`. | Real `expires_in` value from provider response, raw response body, token values. | Token response parser, connection state derivation. |
| Connection state derivation | Derive Settings state from stored lifecycle metadata as status-level categories only. | Admin UI should show actionable state without exposing account or credential details. | `connected`, `not_connected`, `token_expired_or_refresh_needed`, `reconnect_required`, `oauth_error_category`. | Tokens, account identifiers, option values, raw provider errors. | Storage helper, Settings UI wording, admin notice taxonomy. |
| Refresh behavior | Refresh should be attempted only when a refresh token is available, current capability checks pass, and request construction is implemented. Missing refresh token should route to reconnect/reapproval. | Avoid treating refresh-token absence as a fatal mystery state and avoid repeated failed refreshes. | `refresh_available_category`, `refresh_token_missing_category`, `refresh_required_category`, `reconnect_required`. | Refresh token, refresh endpoint request/response, Authorization header, raw provider error. | Refresh implementation plan, retry limits, reconnect UI. |
| Reconnect behavior | Reconnect should provide a safe path for missing refresh token, expired/unrefreshable token, or invalid grant category. | Users need a recovery path without manual database cleanup or support access to credentials. | `reconnect_required_category`, `reconnect_available_category`, `reapproval_required_category`. | Authorization URL, callback URL, query string, raw state, raw code, client values. | Admin UI plan, OAuth redirect boundary, state validation. |
| Revoke / disconnect behavior | Revoke/disconnect should be a later explicit UI and lifecycle decision. Disconnect can remove local token material; remote revoke requires a separate endpoint boundary. | Local deletion and remote revoke have different security and reliability implications. | `disconnect_path_required`, `revoke_ui_decision_pending`, `token_revoked_or_disconnected_category`. | Revoke endpoint request/response, token values, account identifiers, option values. | Revoke endpoint plan, uninstall cleanup, Settings UI. |
| Admin notice taxonomy | Use token-specific notice categories only; no raw provider text or token evidence. | Notices must be actionable and safe to share in support. | `token_exchange_success_category`, `token_exchange_invalid_grant_category`, `token_exchange_provider_error_category`, `token_exchange_network_error_category`, `token_storage_unavailable_category`, `token_refresh_required_category`, `reconnect_required_category`, `token_revoked_or_disconnected_category`. | Raw provider error, provider error code, token response body, request body, callback URL, query string. | Token response classification, Settings notice renderer. |
| Support/debug redaction | Support may request only status-level labels, connection state, admin notice category, and safe result category. | OAuth support is high risk because raw evidence can include credentials, tokens, callbacks, and account identifiers. | `status_label_only`, `connection_state_only`, `admin_notice_category_only`, `safe_result_category_only`, `forbidden_evidence_recorded_no`. | Token endpoint request/response, access token, refresh token, Authorization header, option values, authorization code, callback URL, query string. | Support docs, admin help wording, future QA templates. |
| Manual Google Access Token field coexistence / retirement | Keep manual Google Access Token field during MVP maturation. Public release requires a later retirement decision once OAuth connection is practical. | Coexistence preserves current MVP verification while OAuth storage/lifecycle is still being designed. | `manual_token_entry_remains_for_mvp_maturation`, `manual_token_retirement_pending`, `oauth_connection_not_complete`. | Saved manual token value, token fragments, existing settings option values. | OAuth connection implementation, Settings UX, migration/retirement plan. |
| Uninstall cleanup | Uninstall should remove OAuth token option material and relevant state transients. Manual token setting cleanup remains a decision item tied to overall credential cleanup policy. | Public release should not leave credential material behind after uninstall. | `oauth_token_cleanup_required`, `state_transient_cleanup_required`, `manual_token_cleanup_decision_pending`. | Option values, serialized option values, token values, database dumps. | Uninstall implementation plan, option naming, state transient naming. |
| Deactivate vs uninstall behavior | Deactivate should not delete OAuth token material unless a later explicit design says otherwise. Uninstall is the destructive cleanup boundary. | Deactivation is often temporary; deleting credentials on deactivate would surprise users. | `deactivate_preserves_oauth_material`, `uninstall_removes_oauth_material`. | Option values, token values, serialized credential values. | Uninstall policy, admin documentation. |
| Future public release implications | Dedicated storage, non-autoloading, redaction, cleanup, reconnect/revoke, and manual token retirement all remain release-readiness gates. | OAuth token lifecycle is a public-release blocker, not just a local smoke-test concern. | `public_release_hold`, `oauth_lifecycle_release_blocker`, `storage_lifecycle_decision_recorded`. | Token values, option values, provider raw evidence. | Token exchange implementation, lifecycle UI, privacy/support wording, Plugin Check rerun. |

## Proposed Storage Shape

The dedicated option name may be planned as:

```text
analytics_report_ai_oauth_tokens
```

This is an option-name proposal only. The option value is not defined or
recorded in this step.

Planned value categories, without values:

- access token category,
- refresh token category,
- expiry metadata category,
- scope/category metadata,
- connection status metadata,
- last error category,
- update timestamp category.

Implementation must never display, log, document, or include the option value,
serialized value, token values, token fragments, Authorization headers, or raw
provider responses in support/debug evidence.

## Admin Notice Taxonomy

Candidate token-specific safe labels:

- `token_exchange_success_category`
- `token_exchange_invalid_grant_category`
- `token_exchange_provider_error_category`
- `token_exchange_network_error_category`
- `token_storage_unavailable_category`
- `token_refresh_required_category`
- `reconnect_required_category`
- `token_revoked_or_disconnected_category`

These labels are safe only when they remain category-level. They must not
include raw provider text, provider error codes, token endpoint request or
response bodies, callback URLs, query strings, raw state, authorization codes,
tokens, option values, or account identifiers.

## Support And Debug Redaction

Support/debug requests should be limited to:

- status-level labels,
- connection state,
- admin notice category,
- safe result category,
- whether forbidden evidence was avoided.

Support/debug must not request or include:

- token endpoint requests or responses,
- access tokens,
- refresh tokens,
- ID tokens,
- Authorization headers,
- option values,
- serialized option values,
- authorization codes,
- callback URLs,
- query strings,
- raw state values,
- raw provider errors,
- provider error codes,
- screenshots,
- browser Network evidence,
- account identifiers,
- project identifiers.

## Manual Google Access Token Field Decision

Decision:

```text
manual_token_entry_remains_for_mvp_maturation
```

Rationale:

- Existing MVP GA4 verification relies on the manual token path.
- OAuth connection is not yet fully implemented.
- Refresh/reconnect/revoke/uninstall behavior is not yet implemented.
- Removing the manual token field now would create a workflow gap.

Public-release implication:

```text
manual_token_retirement_pending
```

Before public release, the project should decide whether to retire, hide,
de-emphasize, or migrate away from manual Google Access Token entry after OAuth
connection becomes practical.

## Uninstall Cleanup Decision Items

Future uninstall cleanup should include:

- dedicated OAuth token option material,
- OAuth metadata categories,
- user-scoped OAuth state transients,
- connection state markers,
- token-related admin notice leftovers if any,
- manual token setting cleanup if the manual path still exists at release time.

Decision carried forward:

```text
uninstall_removes_oauth_material
deactivate_preserves_oauth_material
manual_token_cleanup_decision_pending
```

No option values, token values, serialized values, database rows, or transient
payload values should be copied into docs or support evidence while designing
cleanup.

## Explicit Non-Execution

The following were not executed or implemented in Step 143:

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
| Option A | `Step 144: Token exchange implementation boundary` | Define the narrow production-code boundary for token exchange now that storage lifecycle posture is recorded. | Can keep implementation scoped to callback success, safe response classification, and non-recording evidence rules. | Must still avoid storing or outputting token values incorrectly. |
| Option B | `Step 144: Token storage lifecycle implementation boundary` | Define production-code storage helpers, option creation/update policy, non-autoload handling, and cleanup hooks before exchange. | Further reduces storage drift before exchange. | May delay functional OAuth exchange and still need exchange boundary planning. |
| Option C | `Step 144: Admin notice taxonomy refinement` | Refine user-visible token exchange/storage notice categories before production code changes. | Improves UX and support safety. | Does not implement token exchange or storage boundaries. |

Recommended next step:

```text
Step 144: Token exchange implementation boundary
```

Reason: Step 143 records a storage lifecycle posture: dedicated non-autoloaded
OAuth option, no token value display/logging/support evidence, status-level
connection state, manual token coexistence during MVP maturation, uninstall
cleanup on uninstall, and no destructive deletion on deactivate. With that
posture in place, the next implementation boundary can stay narrow around token
exchange without inventing storage policy during code work.

## Confirmation Commands

Planned docs-only confirmation commands:

```bash
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```
