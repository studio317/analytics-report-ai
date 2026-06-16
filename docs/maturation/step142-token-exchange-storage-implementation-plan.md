# Step 142: Token Exchange And Storage Implementation Plan

## Step Summary

Step 142 creates a docs-only implementation plan for OAuth token exchange and
token storage.

This step follows the Step 141 human-controlled approval/callback smoke result.
It does not implement token exchange, token storage, refresh, revoke, reconnect
UI, GA4 Fetch, OpenAI Generate, Plugin Check, browser OAuth, Google navigation,
Google Cloud Console operation, callback execution, or external API
communication.

Production PHP, JavaScript, CSS, assets, `readme.txt`, build scripts, release
package files, settings save logic, GA4 client behavior, OpenAI client
behavior, OAuth behavior, token lifecycle behavior, and admin UI behavior were
not changed.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step141-human-controlled-oauth-approval-callback-smoke-results.md`
- `docs/maturation/step140-controlled-oauth-approval-callback-smoke-plan.md`
- `docs/maturation/step139-oauth-approval-callback-boundary-decision-checkpoint.md`
- `docs/maturation/step121-google-oauth-state-protection-callback-validation-boundary-implementation-results.md`
- `docs/maturation/step120-google-oauth-skeleton-admin-action-boundary-implementation-results.md`

## Step 141 Baseline

| Field | Status-Level Result |
|---|---|
| Authorization approval completed by human | Yes |
| Callback return observed | Yes |
| Authorization code presence category | `not_observed` |
| Raw authorization code recorded | No |
| Token exchange occurred | No |
| Token stored | No |
| Google connection state shown in Settings after return | `not_connected` |

The Step 141 result confirms approval/callback progress at status level only.
It does not provide a raw authorization code for implementation, and it does
not include token endpoint evidence.

## Evidence Boundary

Only status-level implementation planning evidence may be recorded.

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

## Implementation Planning Matrix

| Area | Implementation Goal | Planned Behavior | Allowed Status-Level Evidence | Forbidden Evidence | Security / Privacy Notes | Dependencies |
|---|---|---|---|---|---|---|
| Callback code/status category boundary | Convert callback status categories into a safe decision point for exchange. | Continue to classify callback state, provider error, and code presence without displaying raw callback values. Exchange should only be considered after a safe code-present category is available inside server-side control flow. | `callback_state_valid_category_only`, `authorization_code_presence_category_only`, `provider_error_category_only`, `exchange_not_started` | Raw authorization code, callback URL, query string, raw state, raw provider error, provider error code, browser URL. | The raw code must remain request-local and must not be logged, displayed, documented, or copied into support evidence. | Existing callback validation boundary, capability checks, nonce/state handling. |
| Token exchange trigger point | Define when server-side token exchange may start. | Exchange should be triggered only after capability, state validation, provider error absence, and code presence checks pass. No exchange should run during planning. | `exchange_trigger_planned`, `exchange_preconditions_met_category_only`, `exchange_deferred` | Token endpoint request, authorization code value, client secret, request body, Network evidence. | Prevent accidental exchange on invalid state, provider error, missing code, or repeated callback. | Callback taxonomy, token storage lifecycle decision, error notice taxonomy. |
| Token endpoint request construction | Plan a WordPress HTTP API request without executing it in this step. | Future implementation may use the WordPress HTTP API from server-side PHP to submit only required exchange fields. This step performs no external communication. | `token_request_construction_planned`, `wordpress_http_api_planned`, `external_call_not_executed` | Token endpoint URL with parameters, request body, client secret, authorization code, response body, headers. | Request construction must avoid logs and admin output. Debug evidence must never include request bodies or secret-bearing fields. | Storage lifecycle decision, client secret constant boundary, callback code handling. |
| Token response classification | Convert provider response into safe categories. | Classify responses into success/failure categories such as success, invalid grant, provider error, network error, malformed response, and missing token category. Do not record raw response bodies. | `token_exchange_success_category`, `invalid_grant_category`, `provider_error_category`, `network_error_category`, `malformed_response_category`, `missing_token_category` | Token endpoint response body, access token, refresh token, ID token, token type fragments, real expiry values, provider raw error. | Raw token responses are high-risk and must not be support evidence. Categories should be enough for admin notices and QA. | Token exchange implementation, redaction policy, admin notice taxonomy. |
| Token storage format | Decide where and how OAuth token metadata will be stored. | `wp_options` may be considered, but storage format must be decided before implementation. Option values, serialized values, token values, and token fragments must never be displayed or documented. | `storage_format_decision_pending`, `wp_options_considered`, `token_stored_yes_no_only`, `storage_not_implemented` | Option values, serialized option values, access token, refresh token, token fragments, database rows, backups. | Current MVP credential storage risk remains relevant; storage should minimize autoload and support cleanup. Encryption or external secret storage remains a future design question unless explicitly scoped. | Credential storage strategy, uninstall cleanup plan, autoload decision, lifecycle decision. |
| Access token vs refresh token handling | Define separate handling for short-lived access and longer-lived refresh categories. | Access token may enable GA4 calls while valid. Refresh token, if present, requires stronger storage and revocation decisions. If no refresh token is returned, reconnect or reapproval may be required. | `access_token_received_category`, `refresh_token_received_category`, `refresh_token_missing_category`, `reapproval_required_category`, `token_not_stored` | Actual access token, refresh token, ID token, token fragments, token response body. | Refresh tokens increase storage risk. A missing refresh token should not be treated as a fatal implementation error without a reconnect/reapproval path. | Token response classification, storage lifecycle decision, revoke/reconnect UI plan. |
| Expiry/lifetime handling | Track token validity without exposing real provider response values. | Store or derive expiry metadata only after a storage decision. Display status categories such as valid, expired, refresh needed, or reconnect required. | `token_valid_category`, `token_expired_or_refresh_needed`, `reconnect_required`, `expiry_tracking_pending` | Real `expires_in` value from provider response, exact token response body, token value. | Exact expiry values tied to real responses should not appear in docs or support. Admin UI should show user-actionable status only. | Token storage format, refresh handling, connection state display. |
| Connection state display | Present OAuth state safely in Settings. | Settings can display status-level states such as `connected`, `not_connected`, `token_expired_or_refresh_needed`, `reconnect_required`, and `oauth_error_category`. | `connected`, `not_connected`, `token_expired_or_refresh_needed`, `reconnect_required`, `oauth_error_category` | Token values, account identifiers, provider raw errors, option values, screenshots with sensitive state. | Connection state should not prove account identity or expose credentials. It should be safe for support screenshots only after redaction guidance. | Storage lifecycle decision, admin notice taxonomy, support wording. |
| Error handling and admin notices | Normalize exchange/storage errors into safe admin categories. | Admin notices should use categories only: provider error, invalid grant, network error, malformed response, storage unavailable, refresh needed, reconnect required. | `invalid_grant_category`, `provider_error_category`, `network_error_category`, `storage_unavailable_category`, `reconnect_required` | Raw provider error, provider error code, response body, request body, callback URL, query string. | Notices must be actionable but non-sensitive. They should not encourage users to paste raw responses into support. | Token response classification, support/debug redaction policy. |
| Support/debug redaction | Keep support evidence value-redacted. | Support should request only status labels, notice categories, connection state, and safe high-level steps. | `status_label_only`, `notice_category_only`, `connection_state_only`, `forbidden_evidence_recorded_no` | Token endpoint request/response, access token, refresh token, Authorization header, option values, authorization code, callback URL, query string. | Debug docs and support scripts must not ask for raw OAuth evidence or token data. | Step 86 redaction posture, admin wording, future support docs. |
| Manual Google Access Token field coexistence / retirement | Decide transition from manual token entry to OAuth connection. | During MVP maturation, manual token entry can coexist as a controlled fallback. After OAuth connection is complete, retiring or hiding manual entry should be a separate decision. | `manual_token_entry_remains_for_mvp_maturation`, `manual_token_retirement_pending`, `oauth_connection_not_complete` | Saved manual token value, token fragments, option values. | Coexistence avoids breaking current MVP verification, but public release should reduce ambiguous credential paths. | OAuth connection completion, token storage lifecycle, Settings UX plan. |
| Uninstall cleanup implications | Define cleanup for future stored OAuth material. | Future uninstall should clean OAuth token options, state transients, and credential-related options according to a documented policy. | `uninstall_cleanup_decision_pending`, `oauth_token_cleanup_required`, `state_transient_cleanup_required` | Option values, token values, serialized credential values, database dumps. | Cleanup policy must avoid leaving tokens behind while preserving user expectations for uninstall vs deactivate. | Storage format decision, uninstall policy step, option naming. |
| Future revoke / reconnect UI implications | Plan user controls for replacing or removing OAuth connection. | Future UI should support reconnect and revoke/disconnect paths once storage and token lifecycle are implemented. | `revoke_ui_decision_pending`, `reconnect_ui_decision_pending`, `disconnect_path_required` | Revoke endpoint request/response, token values, account identifiers, screenshots with sensitive values. | Revoke/reconnect affects user trust and credential lifecycle. It should not be bolted on after public release. | Token storage, refresh handling, connection state display, revoke endpoint plan. |

## Token Endpoint Request Construction Notes

Future implementation may use the WordPress HTTP API for server-side token
exchange. Step 142 does not perform that request and does not construct,
display, or record a token endpoint request.

Implementation constraints for a later step:

- do not log request bodies,
- do not display request bodies in admin UI,
- do not include client secrets, authorization codes, callback URLs, or token
  endpoint details in docs,
- classify transport failures at status level,
- keep support evidence limited to safe categories.

## Token Response Classification Notes

Future implementation should classify token responses into safe categories
without exposing raw response content.

Candidate categories:

- `token_exchange_success_category`,
- `invalid_grant_category`,
- `provider_error_category`,
- `network_error_category`,
- `malformed_response_category`,
- `missing_token_category`,
- `token_exchange_not_executed`.

Raw token endpoint response bodies, provider raw errors, access tokens, refresh
tokens, ID tokens, token type fragments, and real provider expiry values must
not be recorded.

## Token Storage Format Notes

`wp_options` can be considered as an implementation target, but the storage
format must be decided before token exchange implementation.

Decision items:

- whether OAuth tokens remain in the existing plugin settings option or move to
  a dedicated option,
- whether the option should avoid autoload,
- what metadata is needed for connection state,
- how refresh token absence is represented,
- how expiry is represented without exposing real response evidence,
- how uninstall cleanup will find and remove stored OAuth material,
- whether encryption, constants, or external secret storage remain future
  candidates.

No option value, serialized value, token value, or token fragment should appear
in docs, admin notices, support/debug output, or logs.

## Access Token / Refresh Token / Expiry Notes

Refresh-token behavior must be explicit before implementation:

- if a refresh token is returned, storage risk and revoke/reconnect needs
  increase,
- if no refresh token is returned, reconnect or reapproval may be required,
- access-token expiry should become a status-level connection state rather than
  a raw provider response detail,
- refresh failure should be categorized without raw response bodies,
- access-token-only states should not masquerade as durable connection states.

Safe UI states can include:

- `connected`,
- `not_connected`,
- `token_expired_or_refresh_needed`,
- `reconnect_required`,
- `oauth_error_category`.

## Manual Token Field Coexistence Decision

Option 1: coexist during MVP maturation.

- Pros: preserves current GA4 verification workflow and avoids breaking existing
  MVP behavior.
- Cons: leaves two credential paths and requires clear Settings wording.
- Status label: `manual_token_entry_remains_for_mvp_maturation`.

Option 2: retire after OAuth connection is complete.

- Pros: simplifies public-release credential UX and reduces manual token risk.
- Cons: depends on reliable token exchange, storage, refresh, revoke, and
  reconnect behavior.
- Status label: `manual_token_retirement_pending`.

Recommended posture for Step 142:

```text
manual_token_entry_remains_for_mvp_maturation
```

Retirement should be decided after OAuth token storage lifecycle is settled.

## Uninstall Cleanup Decision Items

Future uninstall cleanup must decide how to remove:

- OAuth token option material,
- OAuth token metadata,
- OAuth state transients,
- credential-related settings,
- manual token data if still present,
- connection-state markers.

The cleanup policy should distinguish deactivate from uninstall. It should not
record option values or token values as evidence.

## Explicit Non-Execution

The following were not executed or implemented in Step 142:

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
| Option A | `Step 143: Token exchange implementation boundary` | Define the exact production code boundary for performing token exchange. | Moves closer to functional OAuth. | Premature if storage, refresh, revoke, reconnect, and uninstall cleanup are still undecided. |
| Option B | `Step 143: Token storage lifecycle decision checkpoint` | Decide storage format, access/refresh token handling, expiry state, reconnect/revoke implications, uninstall cleanup, and manual token retirement posture. | Settles the credential lifecycle before implementing token exchange. | Still docs-only and does not add functional OAuth. |
| Option C | `Step 143: Callback taxonomy and admin notice refinement` | Refine callback/admin notice categories after Step 141 showed no notice observed. | Improves UX and diagnosis before exchange. | Does not settle token storage lifecycle. |

Recommended next step:

```text
Step 143: Token storage lifecycle decision checkpoint
```

Reason: token exchange should not be implemented before deciding how access
tokens, refresh tokens, expiry, reconnect, revoke, uninstall cleanup, and manual
token entry retirement will be handled. A storage lifecycle checkpoint reduces
the chance of implementing token exchange into a storage model that needs to be
reworked before public release.

## Confirmation Commands

Planned docs-only confirmation commands:

```bash
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```
