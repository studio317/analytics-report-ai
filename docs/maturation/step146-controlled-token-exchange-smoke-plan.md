# Step 146: Controlled Token Exchange Smoke Plan

## Step Summary

Step 146 creates a docs-only plan for a future human-controlled token exchange
smoke after the narrow token exchange and storage boundary implemented in Step
145.

This step does not execute browser OAuth, navigate to Google, operate Google
Cloud Console, execute a callback in a browser, call the token endpoint, perform
token exchange, inspect token storage values, refresh tokens, revoke tokens, run
GA4 Fetch, run OpenAI Generate, run Plugin Check, or access `wp-dev-check`.

This step does not change production PHP, JavaScript, CSS, assets, `readme.txt`,
build scripts, package files, settings save logic, GA4 behavior, OpenAI
behavior, OAuth behavior, token storage behavior, or admin UI behavior.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step145-narrow-token-exchange-production-implementation-results.md`
- `docs/maturation/step144-token-exchange-implementation-boundary.md`
- `docs/maturation/step143-token-storage-lifecycle-decision-checkpoint.md`
- `docs/maturation/step142-token-exchange-storage-implementation-plan.md`
- `docs/maturation/step141-human-controlled-oauth-approval-callback-smoke-results.md`
- `docs/maturation/step140-controlled-oauth-approval-callback-smoke-plan.md`

## Step 145 Baseline

Status-level baseline from the Step 145 implementation:

- Token exchange helper added: Yes
- WordPress HTTP API token exchange implementation exists in code: Yes
- Dedicated OAuth token option exists in code: Yes
- Dedicated option name: `analytics_report_ai_oauth_tokens`
- Dedicated option autoload posture: non-autoload
- Token response categories implemented: Yes
- Token endpoint executed by CODEX: No
- Browser OAuth executed by CODEX: No
- Manual Google Access Token path preserved: Yes
- Refresh implementation: Not implemented
- Revoke implementation: Not implemented
- Reconnect UI: Not implemented
- Uninstall cleanup for OAuth token option: Not implemented

## Future Smoke Purpose

The future human-controlled token exchange smoke should be limited to confirming
status-level behavior:

- human tester can complete OAuth approval
- callback return occurs
- token exchange branch reaches a safe status category
- token storage success/failure is observed only as status-level category
- Settings connection state becomes connected / not_connected /
  oauth_error_category / unknown
- no raw authorization code, callback URL, query string, raw state, token
  endpoint request/response, token value, option value, screenshot, or Network
  evidence is recorded

The future smoke must be human-executed. CODEX must not execute browser OAuth,
Google navigation, callback execution, token endpoint communication, token
exchange, or token storage inspection.

## Evidence Boundary

Only status-level evidence may be recorded.

Allowed evidence examples:

- `local_oauth_constants_configured_yes_no`
- `settings_loaded_yes_no`
- `client_config_status_category`
- `connect_action_triggered_yes_no`
- `google_oauth_screen_reached_yes_no`
- `authorization_approval_completed_yes_no`
- `callback_return_observed_yes_no`
- `token_exchange_status_category`
- `token_storage_status_category`
- `wordpress_admin_notice_category_only`
- `settings_connection_state_category`
- `forbidden_evidence_recorded_no`

Forbidden evidence:

- authorization URLs
- browser address bar URLs
- callback URLs
- query strings
- raw state values
- authorization codes
- raw provider errors
- provider error codes
- token endpoint request bodies
- token endpoint response bodies
- token endpoint URLs with parameters
- access tokens
- refresh tokens
- ID tokens
- token type or token fragments tied to a real response
- real `expires_in` values from a real response
- Authorization headers
- plugin settings option values
- OAuth token option values
- serialized option values
- client ID values
- client secrets
- hostname/domain values
- screenshots
- browser Network evidence
- cookies, sessions, or nonce values
- credentials or API keys
- GA4 Property ID values
- analytics values
- request bodies
- AI payload JSON
- raw API responses
- generated report bodies
- email addresses or Google account identifiers
- project IDs or project identifiers

## Allowed Token Exchange Status Labels

The future smoke may use only these token exchange status labels:

- `token_exchange_success_category`
- `token_exchange_invalid_grant_category`
- `token_exchange_provider_error_category`
- `token_exchange_network_error_category`
- `token_exchange_malformed_response_category`
- `token_exchange_missing_token_category`
- `token_exchange_not_executed`
- `unknown_token_exchange_status`

## Allowed Token Storage Status Labels

The future smoke may use only these token storage status labels:

- `token_storage_success_category`
- `token_storage_unavailable_category`
- `token_storage_not_executed`
- `unknown_token_storage_status`

## Allowed Settings Connection State Labels

The future smoke may use only these Settings connection state labels:

- `connected`
- `not_connected`
- `token_expired_or_refresh_needed`
- `reconnect_required`
- `oauth_error_category`
- `unknown`
- `not_visible`

## Step 147 Human Observation Template

Use this status-level template for the next human-controlled token exchange
smoke:

```text
Step 147 human observation template:

- Local OAuth constants configured before smoke: Yes / No
- PHP syntax check passed after constants update: Yes / No / Not checked
- Settings page loaded before smoke: Yes / No
- Client config status before smoke: client_configured / client_config_missing / unexpected_status
- Existing OAuth connection state before smoke: connected / not_connected / token_expired_or_refresh_needed / reconnect_required / oauth_error_category / unknown / not_visible
- Connect action triggered: Yes / No
- Google OAuth-related screen reached: Yes / No / Not reached
- OAuth app label expected: Yes / No / Not checked
- Authorization approval attempted by human: Yes / No
- Authorization approval completed by human: Yes / No
- Callback return observed: Yes / No / Not sure
- Returned to WordPress admin automatically: Yes / No
- Returned to Settings manually: Yes / No
- WordPress admin notice category:
  - token_exchange_success_category
  - token_exchange_invalid_grant_category
  - token_exchange_provider_error_category
  - token_exchange_network_error_category
  - token_exchange_malformed_response_category
  - token_exchange_missing_token_category
  - token_storage_unavailable_category
  - oauth_callback_success_category_only
  - oauth_callback_error_category_only
  - callback_state_missing_category_only
  - callback_state_expired_category_only
  - callback_state_invalid_category_only
  - provider_error_category_only
  - no_notice_observed
  - not_applicable
  - unknown_notice_category
- Token exchange status category:
  - token_exchange_success_category
  - token_exchange_invalid_grant_category
  - token_exchange_provider_error_category
  - token_exchange_network_error_category
  - token_exchange_malformed_response_category
  - token_exchange_missing_token_category
  - token_exchange_not_executed
  - unknown_token_exchange_status
- Token storage status category:
  - token_storage_success_category
  - token_storage_unavailable_category
  - token_storage_not_executed
  - unknown_token_storage_status
- Google connection state shown in Settings after return:
  - connected
  - not_connected
  - token_expired_or_refresh_needed
  - reconnect_required
  - oauth_error_category
  - unknown
  - not_visible
- Raw authorization code recorded: No
- Callback URL recorded: No
- Query string recorded: No
- Raw state recorded: No
- Token endpoint request/response recorded: No
- Access token recorded: No
- Refresh token recorded: No
- Authorization header recorded: No
- OAuth token option value inspected or recorded: No
- Screenshot or Network evidence recorded: No
- Forbidden evidence recorded: No
```

## Option Value Inspection Boundary

The next smoke must not use `wp option get analytics_report_ai_oauth_tokens`,
database dumps, serialized option output, debug logs, screenshots, or any other
mechanism to display or record OAuth token option values.

If token storage confirmation is needed, it must rely only on:

- Settings connection state category,
- safe admin notice category,
- safe token exchange status category,
- safe token storage status category.

The smoke must not confirm storage by printing, copying, logging, screenshotting,
or otherwise exposing token option values.

## Cleanup Planning Notes

If token storage succeeds, real OAuth token material may exist in the local dev
database.

Cleanup boundary:

- Do not inspect or print the option value.
- Do not use `wp option get` to display the value.
- A later cleanup or disconnect/uninstall step should remove the dedicated OAuth
  token option safely.
- Until cleanup is implemented, cleanup should be planned carefully and
  status-level only.

This step does not delete, inspect, print, update, or otherwise manipulate the
dedicated OAuth token option.

## Next Step Options

| Option | Candidate | Summary | Notes |
|---|---|---|---|
| A | `Step 147: Human-controlled token exchange smoke result recording` | Record a human-executed token exchange smoke result using status-level evidence only. | Recommended, because Step 145 added the narrow implementation and Step 146 defines the evidence boundary. CODEX must not execute browser OAuth, Google navigation, callback execution, token endpoint communication, or option inspection. |
| B | `Step 147: Token option cleanup boundary plan` | Plan cleanup for local dev OAuth token material if a human smoke stores a token. | Useful after or immediately before a storage-producing smoke, but it does not verify the new exchange path. |
| C | `Step 147: Admin notice taxonomy recheck plan` | Recheck whether the implemented token exchange/storage notices cover future smoke outcomes. | Useful follow-up if the human smoke reveals unclear status categories. |

Recommended next step:

```text
Step 147: Human-controlled token exchange smoke result recording
```

Step 147 should remain human-executed. CODEX must not execute browser OAuth,
Google navigation, callback execution, token endpoint live communication, token
exchange, token storage inspection, refresh, revoke, GA4 Fetch, OpenAI Generate,
Plugin Check, or `wp-dev-check` activity.

## Explicit Non-Goals

- production code changes
- JavaScript, CSS, asset, `readme.txt`, build script, or package changes
- browser OAuth execution by CODEX
- Google navigation by CODEX
- Google Cloud Console operation by CODEX
- OAuth approval by CODEX
- callback live browser execution by CODEX
- token endpoint live communication by CODEX
- token exchange execution by CODEX
- token storage data inspection
- `wp option get` for plugin settings or OAuth token option values
- refresh implementation
- revoke implementation
- reconnect UI implementation
- uninstall cleanup implementation
- GA4 Fetch
- OpenAI Generate
- Plugin Check
- `wp-dev-check` access
- forbidden evidence recording
