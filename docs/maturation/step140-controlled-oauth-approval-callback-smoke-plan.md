# Step 140: Controlled OAuth Approval Callback Smoke Plan

## Step Summary

Step 140 creates a docs-only plan for a future human-controlled OAuth approval
and callback smoke.

This step does not execute OAuth approval, callback handling, token exchange,
token storage, refresh, revoke, reconnect UI, GA4 Fetch, OpenAI Generate, Plugin
Check, Google navigation, Google Cloud Console operation, or external API
communication. It defines the evidence boundary and observation template for a
future Step 141 human smoke.

Production PHP, JavaScript, CSS, assets, `readme.txt`, build scripts, release
package files, settings save logic, GA4 client behavior, OpenAI client
behavior, OAuth behavior, token lifecycle behavior, and admin UI behavior were
not changed.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step139-oauth-approval-callback-boundary-decision-checkpoint.md`
- `docs/maturation/step138-human-controlled-oauth-scope-category-ui-recheck-results.md`
- `docs/maturation/step137-source-defined-oauth-scope-category-recheck-results.md`
- `docs/maturation/step121-google-oauth-state-protection-callback-validation-boundary-implementation-results.md`
- `docs/maturation/step120-google-oauth-skeleton-admin-action-boundary-implementation-results.md`

## Current Baseline

| Field | Status-Level Baseline |
|---|---|
| OAuth redirect smoke status | `google_oauth_screen_reached` |
| Source-defined scope category result | `analytics_readonly_scope_expected` |
| Google Data Access category result | `analytics_readonly_scope_expected` |
| Consent screen scope category result | `scope_category_not_confirmed` |
| Current connection state | `not_connected` |
| Authorization approval completed | No |
| Authorization code observed/recorded | No |
| Token exchange occurred | No |
| Token stored | No |
| Local OAuth constants currently present | Not required for docs-only planning |

This baseline means the redirect path has reached a Google OAuth-related screen
category and the source / Google Data Access scope categories are aligned at
status level. The consent screen displayed scope category is still not
confirmed. The plugin is not connected, and no approval, authorization code
recording, token exchange, or token storage has occurred.

## Future Smoke Purpose

The next human-controlled approval/callback smoke should be limited to these
questions:

- authorization approval can be completed by the human tester,
- callback return occurs or does not occur,
- callback state validation category is observed at status level,
- authorization code presence category is observed at status level,
- provider error category is observed at status level if applicable,
- token exchange remains not executed,
- token storage remains not executed.

The smoke is not a token lifecycle test. If token exchange remains unimplemented,
then a callback success category may be observed without token exchange or token
storage occurring.

## Allowed Evidence

Future smoke evidence must remain status-level only.

Allowed labels include:

- `approval_attempted_yes_no`,
- `authorization_approval_completed_yes_no`,
- `callback_return_observed_yes_no`,
- `callback_state_valid_category_only`,
- `callback_state_missing_category_only`,
- `callback_state_expired_category_only`,
- `callback_state_invalid_category_only`,
- `authorization_code_presence_category_only`,
- `authorization_code_absence_category_only`,
- `provider_error_category_only`,
- `wordpress_admin_notice_category_only`,
- `token_exchange_not_executed`,
- `token_stored_no`,
- `forbidden_evidence_recorded_no`.

Allowed supporting notes:

- whether the Settings page loaded,
- whether client configuration status was shown as configured or missing,
- whether Connect was triggered,
- whether a Google OAuth-related screen was reached,
- whether the tester returned automatically or manually,
- whether the Settings connection state was `not_connected`, `connected`,
  `unknown`, or not visible.

These notes must not include raw URLs, raw query values, raw provider text,
credentials, tokens, account identifiers, screenshots, or Network evidence.

## Forbidden Evidence

The next smoke must not record:

- raw authorization codes,
- authorization URLs,
- browser address bar URLs,
- callback URLs,
- query strings,
- raw state values,
- screenshots,
- browser Network evidence,
- raw provider errors,
- provider error codes,
- actual scope strings,
- actual scope-bearing URLs or query values,
- raw Google screen text,
- client ID values,
- client secret values,
- hostname/domain values,
- cookies,
- sessions,
- nonce values,
- credentials,
- API keys,
- access tokens,
- Authorization headers,
- plugin settings option values,
- request bodies,
- response bodies,
- token endpoint responses,
- revoke endpoint responses,
- GA4 Property ID values,
- analytics values,
- AI payload JSON,
- raw API responses,
- generated report bodies,
- email addresses or Google account identifiers,
- project IDs or project identifiers.

The browser address bar must not be copied. Screenshots and Network evidence
must not be used.

## Human Observation Template For Step 141

Use this status-level template for the future human-controlled smoke:

```text
- Local OAuth constants configured before smoke: Yes / No
- Settings page loaded before smoke: Yes / No
- Client config status before smoke:
  - client_configured
  - client_config_missing
  - unexpected_status
- Connect action triggered: Yes / No
- Google OAuth-related screen reached: Yes / No / Not reached
- Authorization approval attempted by human: Yes / No
- Authorization approval completed by human: Yes / No
- Callback return observed: Yes / No / Not sure
- Returned to WordPress admin automatically: Yes / No
- Returned to Settings manually: Yes / No
- WordPress admin notice category:
  - oauth_callback_success_category_only
  - oauth_callback_error_category_only
  - callback_state_missing_category_only
  - callback_state_expired_category_only
  - callback_state_invalid_category_only
  - provider_error_category_only
  - no_notice_observed
  - not_applicable
- Authorization code presence category:
  - code_presence_category_observed
  - code_absence_category_observed
  - not_observed
- Raw authorization code recorded: No
- Callback URL recorded: No
- Query string recorded: No
- Raw state recorded: No
- Screenshot or Network evidence recorded: No
- Token exchange occurred: No
- Token stored: No
- Google connection state shown in Settings after return:
  - not_connected
  - connected
  - unknown
  - not_visible
- Forbidden evidence recorded: No
```

If additional unexpected status-level categories are observed, record only the
safe category label and do not include raw browser, provider, token, credential,
or option evidence.

## Callback Category Expectations

Earlier callback boundary work supports status-level callback categories without
recording raw callback values.

Expected safe category families:

- callback state missing category,
- callback state expired category,
- callback state invalid category,
- callback state valid category,
- authorization code presence category,
- authorization code absence category,
- provider error category.

The smoke should not attempt to prove token exchange. A callback category that
indicates code presence still means only that the presence category was
detected. It must not imply that the raw code was recorded or exchanged.

## Token Exchange And Storage Boundary

If token exchange is still unimplemented at the time of the future smoke, then
authorization approval and a callback success category are expected to stop at
the callback boundary.

Required status-level expectations:

- token exchange remains not executed,
- token storage remains not executed,
- refresh remains not executed,
- revoke remains not executed,
- reconnect UI remains not tested,
- GA4 Fetch remains not executed,
- OpenAI Generate remains not executed.

Token exchange, token storage, refresh token handling, revoke behavior,
reconnect UI, and manual token entry retirement require separate planning or
implementation steps.

## CODEX Execution Boundary

CODEX must not run the Step 141 browser smoke. CODEX must not navigate to
Google, operate Google Cloud Console, call external APIs, approve OAuth
authorization, execute callback handling, exchange tokens, store tokens,
refresh tokens, revoke tokens, run GA4 Fetch, run OpenAI Generate, run Plugin
Check, or access `wp-dev-check`.

CODEX must not generate, display, inspect, or record authorization URLs,
browser URLs, callback URLs, query strings, raw state values, authorization
codes, raw provider errors, provider error codes, client ID values, client
secret values, hostnames, credentials, token values, option values,
screenshots, browser Network evidence, request bodies, payloads, raw responses,
or generated report bodies.

## Out Of Scope

The following are out of scope and were not executed or implemented in Step
140:

- OAuth approval,
- callback execution,
- token endpoint requests,
- revoke endpoint requests,
- token exchange,
- token storage,
- token refresh,
- token revoke,
- reconnect UI,
- manual token entry retirement,
- GA4 Fetch,
- OpenAI Generate,
- Plugin Check,
- production code change,
- `wp-dev-check` access.

## Next Step

Recommended next step:

```text
Step 141: Human-controlled OAuth approval callback smoke result recording
```

Step 141 should record the human-provided result using the observation template
above. It should remain status-level only and must not record authorization
codes, callback URLs, query strings, raw state, raw provider errors, provider
error codes, screenshots, Network evidence, client values, token values,
credentials, option values, request/response bodies, payloads, analytics values,
or generated report bodies.

## Confirmation Commands

Planned docs-only confirmation commands:

```bash
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```
