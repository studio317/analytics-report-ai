# Step 116: Google OAuth / Token Lifecycle Implementation Plan

## Step Summary

This step records a docs-only implementation plan for Google OAuth and token
lifecycle work.

Step 115 defined this as Phase 1 of the credential implementation roadmap. The
goal is to plan authorization start, callback handling, state protection,
capability checks, token exchange, access token expiration, refresh or
reconnect strategy, disconnect / revoke behavior, storage boundary,
admin-facing status/error messages, support/debug evidence boundaries, QA, and
future implementation slices before code changes begin.

This step does not implement OAuth, token exchange, refresh, revoke, reconnect
UI, credential storage changes, Settings save logic changes, `uninstall.php`,
option deletion, admin UI changes, readme changes, package rebuilds, Plugin
Check reruns, or external API calls.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step115-credential-implementation-roadmap-phase-boundary-plan.md`
- `docs/maturation/step114-integrated-credential-architecture-plan.md`
- `docs/maturation/step113-credential-lifecycle-decision-summary-next-implementation-boundary.md`
- `docs/maturation/step112-uninstall-credential-cleanup-policy-decision-checkpoint.md`
- `docs/maturation/step111-openai-api-key-storage-posture-decision-checkpoint.md`
- `docs/maturation/step110-google-oauth-token-lifecycle-strategy-decision-checkpoint.md`
- `docs/maturation/step109-release-readiness-blocker-reprioritization-after-plugin-check-pass.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`

## Current Baseline

| Area | Baseline |
|---|---|
| Manual Google Access Token entry | Developer verification only. |
| WordPress.org release | `Hold`. |
| Public release requirement | Google OAuth / token lifecycle needs implementation planning and implementation before release, unless Google integration is removed from public scope. |
| Roadmap phase | Step 115 Phase 1 docs-only. |
| External API communication | Not allowed until Phase 8 by default; Phase 8 still requires explicit approval for any real external API E2E. |
| Plugin Check | Not run until Phase 9. |
| Package rebuild | Not run until Phase 9. |
| Current Settings model | Status-level source review shows the current MVP stores Google credential state in plugin settings and does not redisplay the saved credential value. |
| Current GA4 usage | Status-level source review shows Report Builder reads the saved Google credential category and passes it to the GA4 client for GA4 Fetch. |

## Future Implementation Goals

Future Google OAuth implementation should:

- avoid relying on manual Google Access Token entry as the public release
  default,
- let an authorized admin user safely start a Google connection,
- handle the OAuth callback safely,
- use callback state protection,
- require appropriate capability checks,
- define a token exchange boundary,
- handle access token expiration,
- decide between refresh-token behavior and reconnect-only behavior,
- distinguish disconnect from provider-side revoke,
- distinguish local disconnect from provider-side revoke,
- provide status-level admin messages for connection and failure states,
- preserve credential redaction and non-redisplay behavior,
- limit support/debug evidence to status-level information,
- avoid recording credentials, option values, request bodies, raw responses,
  payload bodies, or generated report bodies.

## Planned Implementation Areas

Future implementation planning should cover at least:

- authorization start endpoint / admin action,
- OAuth callback handler,
- callback state generation,
- callback state validation,
- nonce checks,
- capability checks,
- redirect / return URL handling,
- token exchange boundary,
- token response normalization,
- access token expiration tracking,
- refresh or reconnect strategy,
- disconnect action,
- provider-side revoke action, if adopted,
- credential storage boundary,
- redaction / non-redisplay behavior,
- Settings UI connection status,
- admin-facing error messages,
- GA4 client credential resolution impact,
- support/debug evidence boundary,
- QA / manual smoke plan.

## Strategy Choices To Decide Before Implementation

The following decisions must be made before code changes begin:

| Decision point | Notes |
|---|---|
| Refresh token strategy vs reconnect-only strategy | A refresh-token model has different storage, expiry, support, and uninstall implications than a reconnect-only model. |
| Provider-side revoke in MVP public release | Decide whether revoke is implemented in the first public-ready OAuth version or deferred behind local disconnect wording. |
| Manual token entry after OAuth | Decide whether manual token entry is hidden, removed, or retained as developer-only after OAuth is implemented. |
| Storage location and option names | Decide storage structure without exposing values. This must align with OpenAI storage and uninstall cleanup plans. |
| Separate Google credential storage | Decide whether Google credential storage remains in general settings or moves to a dedicated storage boundary in a later implementation plan. |
| Admin capability | Define required capability for connect, callback finalization, disconnect, and revoke actions. |
| Expired-token messaging | Define status-level messaging for expired credentials and reconnect paths. |
| Insufficient-permission messaging | Define safe messages that do not expose raw provider responses. |
| Property-access failure messaging | Define safe messages that do not expose identifiers or raw provider responses. |
| Safe support evidence | Define status labels, redacted saved-state, connection state, and generic error categories that support can request. |
| Excluded support evidence | Keep credentials, option values, request bodies, raw responses, payload JSON, generated report bodies, identifiers, and analytics values out of support evidence. |

## Proposed Implementation Slices

### Slice A: OAuth Skeleton / Admin Action Boundary

Purpose:

- Add the minimal future structure for starting OAuth and receiving callbacks.
- Establish route/action names, connect button placement, callback handler
  skeleton, and result routing without external token exchange.

Likely changed files:

- `includes/class-admin.php`
- `includes/class-settings.php`
- possibly a new OAuth-focused include file if the accepted implementation plan
  keeps OAuth logic outside Settings rendering.
- docs under `docs/maturation/`

Explicit non-goals:

- No token exchange.
- No external API calls.
- No token storage.
- No refresh, revoke, or reconnect implementation.
- No GA4 client behavior changes.

QA scope:

- PHP syntax checks.
- Admin route/action source review.
- Capability and nonce source review.
- No browser OAuth execution unless a later approved verification step allows
  it.

External API requirement:

- No.

Risk level:

- Medium, because admin action routing and callback surfaces are security
  sensitive even before token exchange.

### Slice B: State Protection And Capability Checks

Purpose:

- Implement callback state generation and validation.
- Ensure connect, callback, disconnect, and future revoke paths are guarded by
  capability checks and request validation.

Likely changed files:

- `includes/class-admin.php`
- `includes/class-settings.php`
- possible OAuth helper include.
- docs under `docs/maturation/`

Explicit non-goals:

- No token exchange.
- No external API calls.
- No token storage.
- No GA4 client behavior changes.

QA scope:

- PHP syntax checks.
- Focused source review for state validation and capability boundaries.
- Safe local request validation checks that do not expose secrets or option
  values.

External API requirement:

- No.

Risk level:

- High, because state and capability checks are core OAuth security controls.

### Slice C: Token Exchange And Normalized Token Storage

Purpose:

- Implement the accepted token exchange boundary.
- Normalize token response handling.
- Store only the approved credential categories according to the accepted
  storage model.
- Preserve redaction and non-redisplay behavior.

Likely changed files:

- OAuth helper include or credential service include.
- `includes/class-settings.php`
- possibly `includes/functions-utils.php`
- docs under `docs/maturation/`

Explicit non-goals:

- No broad GA4 report logic changes.
- No OpenAI API key storage changes.
- No uninstall cleanup.
- No raw token response logging.
- No raw provider response display.

QA scope:

- PHP syntax checks.
- Source review for redaction and non-redisplay.
- Local validation of state transitions without recording token values.
- Real token exchange only in a later explicitly approved controlled
  verification phase.

External API requirement:

- Yes for real token exchange, but not during this planning step or default
  implementation verification. Any real exchange requires separate explicit
  approval and redacted evidence rules.

Risk level:

- High, because this slice handles credential categories and external provider
  responses.

### Slice D: Expiration Handling And Reconnect Strategy

Purpose:

- Implement the accepted expiration model.
- Implement either refresh behavior or reconnect-only status handling,
  depending on the approved strategy.
- Provide safe admin status and error states.

Likely changed files:

- OAuth helper include or credential service include.
- `includes/class-settings.php`
- `includes/class-report-builder.php`
- possibly `includes/class-ga4-client.php` only if credential resolution or
  error mapping needs a narrow change.
- docs under `docs/maturation/`

Explicit non-goals:

- No OpenAI storage changes.
- No uninstall cleanup.
- No raw provider response display.
- No generated report or payload behavior changes.

QA scope:

- PHP syntax checks.
- Source review for expired/invalid states.
- Local status transition checks without exposing option values.
- External API E2E only with separate explicit approval.

External API requirement:

- No for reconnect-only local status logic; yes if testing refresh behavior
  against the provider. Any real provider call requires separate explicit
  approval.

Risk level:

- High, because expiry/refresh behavior affects credential storage and support
  expectations.

### Slice E: Disconnect And Optional Provider-side Revoke

Purpose:

- Implement local disconnect behavior.
- Optionally implement provider-side revoke if the approved strategy includes
  it.
- Make the UI and documentation distinguish local disconnect from provider-side
  revoke.

Likely changed files:

- OAuth helper include or credential service include.
- `includes/class-settings.php`
- docs under `docs/maturation/`

Explicit non-goals:

- No uninstall cleanup unless separately scoped.
- No OpenAI storage changes.
- No raw revoke response display or logging.
- No provider-side revoke unless explicitly adopted.

QA scope:

- PHP syntax checks.
- Local disconnect behavior checks without exposing option values.
- Provider-side revoke only in a later explicitly approved controlled
  verification phase.

External API requirement:

- No for local disconnect. Yes only if provider-side revoke is implemented and
  verified, and only with separate explicit approval.

Risk level:

- Medium to high, depending on whether provider-side revoke is included.

### Slice F: GA4 Client Integration And Safe Error Mapping

Purpose:

- Connect the selected credential resolution path to GA4 Fetch.
- Preserve GA4 fetch behavior while replacing manual-token assumptions with
  OAuth connection status.
- Ensure missing, expired, invalid, insufficient-permission, and property-access
  failures map to safe status-level errors.

Likely changed files:

- `includes/class-report-builder.php`
- `includes/class-ga4-client.php`
- OAuth helper or credential service include.
- docs under `docs/maturation/`

Explicit non-goals:

- No AI payload structure changes.
- No OpenAI request changes.
- No GA4 report metric/dimension behavior changes.
- No raw request or raw response recording.
- No generated report behavior changes.

QA scope:

- PHP syntax checks.
- Source review for credential resolution and safe error mapping.
- Local error-state checks where possible.
- External GA4 E2E only in a later explicitly approved controlled
  verification phase.

External API requirement:

- No for source-level integration and local error mapping. Yes for real GA4
  fetch verification, only with separate explicit approval.

Risk level:

- High, because this slice touches the GA4 fetch boundary and user-facing error
  behavior.

## Recommended Implementation Sequence

Recommended order:

```text
1. OAuth skeleton / admin action boundary
2. State protection / capability checks
3. Token exchange boundary and storage model
4. Expiration handling / reconnect strategy
5. Disconnect / optional revoke behavior
6. GA4 client integration and safe error mapping
7. Admin UI wording alignment
8. Controlled verification with explicit approval only
```

Rationale:

- Admin action and callback surfaces should exist before token exchange logic.
- State protection and capability checks should be in place before any
  credential-bearing exchange is implemented.
- Storage model decisions must precede expiration and reconnect behavior.
- Disconnect/revoke semantics should follow the accepted storage and lifecycle
  model.
- GA4 client integration should happen only after credential resolution is
  stable.
- Wording alignment should follow behavior changes.
- Controlled verification must be explicit and must not record sensitive
  evidence.

## Recommended Next Step

Recommended next step:

```text
Step 117: OpenAI API key storage implementation plan
```

Reason:

- Step 115 defines Phase 2 as the OpenAI API key storage implementation plan.
- Google OAuth should not move into implementation until OpenAI storage and
  uninstall cleanup plans are also aligned at docs-only level.
- Planning all three credential areas first reduces later storage, cleanup,
  wording, and QA rework.

## Support / Debug Evidence Boundary

All future Google OAuth / token lifecycle work must preserve this evidence
boundary:

- Do not record credentials, API keys, access tokens, or Authorization headers.
- Do not record plugin settings option values.
- Do not record request bodies.
- Do not record raw responses.
- Do not record AI payload JSON.
- Do not record generated report bodies.
- Do not record GA4 Property ID real values, hostname/domain real values,
  analytics values, page paths, traffic source values, or city values.
- Prefer status-level labels, redacted saved-state, error categories,
  connection state, generation allowed/blocked state, and safe UI wording.
- Avoid screenshots and browser Network tab data by default.
- If external API E2E is later approved, result docs must not record secrets,
  identifiers, analytics values, raw responses, request bodies, payload bodies,
  or generated report bodies.

## Explicit Non-goals

This step does not:

- change code,
- change `readme.txt`,
- change `.distignore`,
- change build scripts,
- rebuild a release package,
- rerun Plugin Check,
- run Plugin Check in `wp-dev`,
- touch `wp-dev-check`,
- call external APIs,
- run GA4 Fetch,
- run OpenAI Generate,
- implement OAuth,
- implement token exchange,
- implement refresh,
- implement revoke,
- implement reconnect UI,
- change credential storage,
- change Settings save logic,
- create `uninstall.php`,
- implement option deletion,
- record raw payloads,
- record raw request bodies,
- record raw response bodies,
- record generated report bodies,
- capture screenshots,
- inspect browser Network tab data,
- inspect or display credentials,
- inspect or display plugin settings option values,
- change admin UI behavior.

## Security / Evidence Notes

This document records only status-level Google OAuth / token lifecycle planning.

It does not record real credentials, API keys, access tokens, Authorization
headers, plugin settings option values, GA4 Property IDs, hostname/domain
values, analytics values, page paths, traffic sources, city values, request
bodies, AI payload JSON, OpenAI request bodies, raw GA4/OpenAI response bodies,
generated report bodies, screenshots, browser Network tab data, cookies,
sessions, or nonces.

## Verification

Commands run for this docs-only step:

```text
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```

Expected result:

- whitespace check passes,
- no production code files change,
- no PHP, JavaScript, CSS, `readme.txt`, `.distignore`, build script, or
  uninstall behavior files change,
- only this Step 116 documentation file is added.
