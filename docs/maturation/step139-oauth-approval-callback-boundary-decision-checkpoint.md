# Step 139: OAuth Approval And Callback Boundary Decision Checkpoint

## Step Summary

Step 139 creates a docs-only decision checkpoint before moving from OAuth
redirect smoke work into OAuth approval, callback handling, token exchange, and
token storage work.

This step does not execute OAuth approval, callback handling, token exchange,
token storage, refresh, revoke, reconnect UI, GA4 Fetch, OpenAI Generate, Plugin
Check, Google navigation, Google Cloud Console operation, or external API
communication. It records only status-level planning and evidence boundaries.

Production PHP, JavaScript, CSS, assets, `readme.txt`, build scripts, release
package files, settings save logic, GA4 client behavior, OpenAI client
behavior, OAuth behavior, token lifecycle behavior, and admin UI behavior were
not changed.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step138-human-controlled-oauth-scope-category-ui-recheck-results.md`
- `docs/maturation/step137-source-defined-oauth-scope-category-recheck-results.md`
- `docs/maturation/step136-oauth-scope-category-follow-up-plan.md`
- `docs/maturation/step135-human-controlled-oauth-provider-configuration-recheck-results.md`
- `docs/maturation/step129-google-authorization-redirect-execution-implementation-results.md`
- `docs/maturation/step121-google-oauth-state-protection-callback-validation-boundary-implementation-results.md`
- `docs/maturation/step120-google-oauth-skeleton-admin-action-boundary-implementation-results.md`

## Evidence Boundary

Only status-level evidence may be recorded.

This document does not record:

- authorization URLs,
- browser address bar URLs,
- callback URLs,
- query strings,
- raw state values,
- authorization codes,
- raw provider errors,
- provider error codes,
- actual scope strings,
- actual scope-bearing URLs or query values,
- raw Google screen text,
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
- access tokens,
- Authorization headers,
- plugin settings option values,
- GA4 Property ID values,
- analytics values,
- request bodies,
- AI payload JSON,
- raw API responses,
- generated report bodies,
- email addresses or Google account identifiers,
- project IDs or project identifiers.

## Step 138 Baseline

Current status-level baseline:

| Field | Status-Level Result |
|---|---|
| Source-defined scope category result | `analytics_readonly_scope_expected` |
| Google Data Access category result | `analytics_readonly_scope_expected` |
| Consent screen scope category result | `scope_category_not_confirmed` |
| Scope category final status-level result | `analytics_readonly_scope_expected_with_consent_screen_not_confirmed` |
| OAuth redirect smoke status | `google_oauth_screen_reached` |
| Current connection state after manual return | `not_connected` |
| Authorization approval completed | No |
| Authorization code observed/recorded | No |
| Token exchange occurred | No |
| Token stored | No |

This baseline means the redirect path has reached a Google OAuth-related screen
category, but the plugin is not connected, no approval has completed, no code
has been observed or recorded, and no token exchange or token storage has
occurred.

## Lifecycle Stage Breakdown

| Stage | Implementation Status | Human Browser Execution | CODEX Execution | Allowed Evidence | Forbidden Evidence | Safe Status-Level Labels | Next-Step Dependency |
|---|---|---|---|---|---|---|---|
| Authorization approval | Not executed in this step; approval boundary not yet planned in detail. | Not allowed in Step 139. May be considered only in a future controlled smoke plan. | Not allowed. | approval not completed, user stopped before approval, approval path planned/not planned. | Authorization URL, browser URL, raw screen text, screenshots, account identifiers, approval confirmation details. | `authorization_approval_not_started`, `authorization_approval_not_completed`, `approval_boundary_needs_plan` | Controlled approval/callback smoke plan. |
| Callback return | Callback placeholder exists from earlier boundary work, but no real callback return is executed in this step. | Not allowed in Step 139. Future human smoke may allow return only under a documented evidence boundary. | Not allowed. | returned automatically yes/no, returned manually yes/no, callback category observed/not observed. | Callback URL, query string, raw state, authorization code, provider error details, browser address bar. | `callback_not_executed`, `callback_return_not_observed`, `callback_boundary_needs_plan` | Controlled callback evidence boundary. |
| Callback state validation | Existing placeholder can classify state status without recording raw state; no live callback executed in this step. | Not allowed in Step 139. Future test must keep raw state unrecorded. | Not allowed. | status-level state category only. | Raw state, state-bearing URL, query string, callback URL, screenshots. | `callback_state_not_tested`, `callback_state_valid_category_only`, `callback_state_invalid_category_only`, `callback_state_missing_category_only` | Callback smoke plan and evidence template. |
| Authorization code presence handling | Existing placeholder can classify code presence without display, saving, or exchange; no live code observed in this step. | Not allowed in Step 139. Future test must not copy or record code values. | Not allowed. | code present yes/no category only. | Raw authorization code, callback URL, query string, browser address bar, screenshots, Network evidence. | `authorization_code_not_observed`, `authorization_code_presence_category_only`, `authorization_code_not_recorded` | Callback smoke plan and token-exchange decision. |
| Provider error category handling | Existing placeholder has category-level provider error handling; no provider error is observed in this step. | Not allowed in Step 139. Future test may record category only. | Not allowed. | provider error category only, no raw detail. | Raw provider error, provider error code, raw Google screen text, screenshots, browser URL, query string. | `provider_error_not_observed`, `provider_error_category_only`, `no_notice_observed` | Callback smoke plan and error taxonomy review. |
| Token exchange decision | Not implemented or executed in this step. | Not allowed. Human browser approval should not imply token exchange until this is planned. | Not allowed. | token exchange not implemented/executed, exchange deferred. | Token endpoint request/response, authorization code, client secret, access token, refresh token, raw response. | `token_exchange_not_implemented`, `token_exchange_not_executed`, `token_exchange_deferred` | Token exchange implementation plan. |
| Token storage decision | Not implemented or executed in this step. | Not allowed. | Not allowed. | storage format undecided, token not stored, connection state unchanged. | Access token, refresh token, expiry values tied to real account, option values, database rows, screenshots. | `token_storage_not_implemented`, `token_storage_not_executed`, `connection_state_not_connected` | Credential storage and token lifecycle design. |
| Refresh token / access token lifetime decision | Not implemented or executed in this step. | Not allowed. | Not allowed. | lifetime decision pending, refresh handling pending. | Token values, expiry values from a real provider response, raw token response, account identifiers. | `token_lifetime_decision_pending`, `refresh_handling_pending` | Token lifecycle design checkpoint. |
| Revoke / reconnect UI decision | Not implemented or executed in this step. | Not allowed. | Not allowed. | revoke/reconnect decision pending. | Revoke endpoint request/response, token values, account identifiers, screenshots. | `revoke_ui_decision_pending`, `reconnect_ui_decision_pending` | Revoke/reconnect UI plan. |
| Manual token entry retirement decision | Not changed in this step. | Not applicable. | Not applicable. | manual token field remains/retirement pending. | Existing option values, saved token values, token fragments. | `manual_token_entry_retirement_pending`, `manual_token_entry_remains_for_mvp_maturation` | Credential UX transition decision. |

## Approval And Callback Evidence Rules

Authorization approval and callback return can expose sensitive material through
the browser URL and callback request.

Before any future approval/callback smoke:

- raw authorization code must not be recorded,
- callback URL must not be recorded,
- query string must not be recorded,
- raw state must not be recorded,
- browser address bar must not be copied,
- screenshots must not be used,
- browser Network evidence must not be used,
- raw provider error must not be recorded,
- provider error code must not be recorded,
- evidence must remain status-level only.

Any future human smoke should use only safe labels such as:

- `authorization_approval_completed_yes_no_only`,
- `callback_return_observed_yes_no_only`,
- `callback_state_valid_category_only`,
- `authorization_code_presence_category_only`,
- `provider_error_category_only`,
- `token_exchange_not_executed`,
- `token_stored_no`.

These labels must not include URL fragments, query values, raw state, raw code,
raw provider text, account identifiers, client values, token values, or
screenshots.

## Token Exchange Decision Before Implementation

Token exchange should not be implemented as an incidental follow-on to browser
approval. It needs its own decision and implementation plan.

Decision points before token exchange:

- whether token exchange should be implemented before any approval recheck,
- whether token exchange should remain deferred until callback evidence is
  safely proven at status level,
- whether token storage format needs a separate decision,
- whether refresh token handling needs a separate decision,
- whether revoke / reconnect UI needs a separate decision,
- whether manual Google Access Token entry should remain during MVP maturation
  or be retired later.

Recommended posture:

- Do not implement token exchange before a controlled approval/callback smoke
  plan exists.
- Do not store tokens before a token storage and lifecycle decision exists.
- Do not request or record token endpoint evidence in support/debug docs.
- Keep manual Google Access Token entry during MVP maturation until an explicit
  retirement decision replaces it.

## Explicit Non-Execution

The following were not executed or implemented in Step 139:

- browser OAuth by CODEX,
- Google navigation by CODEX,
- Google Cloud Console operation by CODEX,
- external API communication,
- OAuth authorization approval,
- callback execution,
- token endpoint requests,
- revoke endpoint requests,
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
| Option A | `Step 140: Controlled OAuth approval callback smoke plan` | Define the evidence boundary and human observation template for approval/callback smoke before execution. | Keeps raw code, callback URL, query string, state, screenshots, and Network evidence out of docs before any approval attempt. | Still does not implement token exchange or storage. |
| Option B | `Step 140: Token exchange and storage implementation plan` | Plan token endpoint request, token response handling, storage format, expiry, and error handling. | Moves toward functional OAuth connection. | Premature before approval/callback evidence boundary is settled; token storage and refresh decisions are still unresolved. |
| Option C | `Step 140: Manual Google Access Token retirement decision checkpoint` | Decide when and how to retire manual token entry. | Clarifies future UX and credential transition. | Depends on OAuth approval/callback and token lifecycle strategy; may be too early before token exchange/storage planning. |

Recommended next step:

```text
Step 140: Controlled OAuth approval callback smoke plan
```

Reason: the next risk boundary is not token exchange. It is the approval and
callback smoke itself, where authorization code, callback URL, query string,
raw state, raw provider details, screenshots, and Network evidence could be
accidentally exposed. Step 140 should define a status-level human observation
template before any approval/callback test occurs.

Step 140 should keep the same evidence boundary:

- no authorization code recording,
- no callback URL recording,
- no query string recording,
- no raw state recording,
- no browser address bar copying,
- no screenshots,
- no Network evidence,
- no raw provider error recording,
- no token exchange,
- no token storage.

## Confirmation Commands

Planned docs-only confirmation commands:

```bash
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```
