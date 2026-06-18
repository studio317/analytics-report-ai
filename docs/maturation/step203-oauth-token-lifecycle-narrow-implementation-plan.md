# Step 203: OAuth Token Lifecycle Narrow Implementation Plan

## Step Purpose

Step 203 is a docs-only and planning-only narrow implementation plan for the
OAuth token lifecycle track.

The purpose is to convert the Step 202 source-level inventory into a safe,
narrow production implementation boundary for Step 204. This plan focuses on
refresh / expiry / reconnect / disconnect / revoke boundaries, local token
deletion, admin notices, safe labels, and storage relationships without
changing production code in this step.

This step does not change production code, `readme.txt`, tools, build scripts,
JavaScript, CSS, OAuth runtime behavior, OAuth resolver behavior, Settings
save/delete behavior, credential storage behavior, token storage behavior, GA4
behavior, or OpenAI behavior.

This step does not execute OAuth, Google navigation, authorization approval,
token endpoint communication, refresh requests, revoke requests, browser admin
smoke, GA4 Fetch, OpenAI Generate, or Plugin Check.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step202-oauth-token-lifecycle-source-level-inventory.md`
- `docs/maturation/step201-oauth-token-lifecycle-implementation-plan.md`
- `docs/maturation/step200-oauth-production-readiness-token-lifecycle-decision-checkpoint.md`
- `docs/maturation/step199-public-release-remaining-blocker-reprioritization-checkpoint.md`
- `docs/maturation/step198-oauth-client-configuration-hybrid-source-final-maturation-checkpoint.md`
- `docs/maturation/step184-oauth-source-inventory-current-boundary-review.md`
- `docs/maturation/step182-oauth-credential-public-release-strategy-checkpoint.md`
- `docs/maturation/step179-support-debug-wording-maturation-checkpoint.md`
- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`

## Current Source Baseline From Step 202

Step 202 established this source-level baseline:

- OAuth token option/storage helper exists.
- Access token storage exists behind a dedicated helper boundary.
- Optional refresh token storage boundary exists when classified token material
  includes refresh-token material.
- Expiry metadata storage exists and can support connection-state categories.
- Connection-state category boundary exists for connected, reconnect-needed,
  refresh-needed, and not-connected states.
- GA4 credential source resolver exists and can choose OAuth token material,
  manual Google Access Token fallback, missing credential, refresh-needed, or
  OAuth error category.
- Manual Google Access Token fallback remains a separate public-release
  unresolved track.
- Refresh flow is not implemented.
- Refresh request construction and refresh response handling are not
  implemented.
- Dedicated reconnect UX is partial and not public-release finalized.
- Disconnect local token deletion is not implemented.
- Provider-side revoke is not implemented.
- Uninstall cleanup is not implemented.
- Lifecycle-specific labels for refresh, disconnect, and revoke are not fully
  formalized.

## Narrow Implementation Scope Proposal

Recommended Step 204 direction:

```text
Implement lifecycle status/category and local lifecycle management boundaries first.
Do not implement provider-side revoke request in the first narrow implementation.
Do not execute refresh/token endpoint communication during implementation.
```

Recommended Step 204 scope:

- formal token lifecycle status labels,
- connection state normalization,
- refresh-needed / reconnect-required categorization,
- refresh token availability categorization,
- safe admin notices for expired / refresh unavailable / reconnect required,
- local disconnect action boundary,
- local token deletion helper boundary,
- disconnect success / failure notice categories,
- GA4 Fetch precondition behavior when OAuth token is expired,
- support/debug safe label wording,
- manual fallback separation wording.

Explicitly out of scope for Step 204:

- provider-side revoke request,
- actual refresh request execution,
- refresh endpoint request construction,
- controlled OAuth/token endpoint QA,
- manual token fallback policy resolution,
- OpenAI API key storage policy,
- uninstall cleanup implementation,
- final `readme.txt` / privacy release wording.

## Recommended Step 204 Implementation Boundary

Recommended Step 204 boundary:

```text
Step 204 should implement source-level lifecycle status/category boundaries, reconnect-required notices, refresh-availability categorization, and local disconnect token deletion boundary, while deferring provider-side revoke request and manual token fallback policy to separate tracks.
```

Refresh request helper recommendation:

```text
Refresh request implementation: Deferred
```

Rationale:

- Refresh request implementation adds token endpoint communication behavior and
  requires controlled external QA before public-release confidence.
- Refresh-capable lifecycle also increases storage and uninstall cleanup
  responsibility because refresh-token storage becomes operationally important.
- Step 204 can still improve lifecycle safety by formalizing refresh-needed /
  reconnect-required categories and preventing expired OAuth token material from
  being treated as silently usable.
- Local disconnect can be planned and implemented without provider-side revoke
  or token endpoint communication.

## Planned Implementation Table

| Area | Current status | Planned Step 204 change | Files likely affected | Behavior change level | Safety boundary | Deferred items | Risk level |
|---|---|---|---|---|---|---|---|
| Token lifecycle status labels | `partial_boundary_exists` | Formalize status/category constants or helper-return labels for usable, expired, refresh unavailable, and reconnect required. | `includes/functions-utils.php`, possibly `includes/class-settings.php` | Low to medium | Labels only; no token values. | Refresh request execution. | Medium |
| Connection state normalization | `partial_boundary_exists` | Normalize connection state into consistent safe categories for Settings and resolver use. | `includes/functions-utils.php`, `includes/class-settings.php` | Low to medium | Status/category-level only. | Full refresh lifecycle. | Medium |
| Refresh token availability category | `partial_boundary_exists` | Distinguish refresh token present, missing, unavailable, and deferred states without displaying values. | `includes/functions-utils.php` | Medium | Presence/category only; no token fragments. | Refresh request helper. | High |
| Access token expired category | `partial_boundary_exists` | Return explicit expired / refresh unavailable / reconnect-required categories when expiry metadata indicates expiration. | `includes/functions-utils.php`, `includes/class-report-builder.php` | Medium | No request execution. | Refresh-before-fetch. | High |
| GA4 credential resolver expired-token behavior | `partial_boundary_exists` | Keep expired OAuth token from being treated as usable; route to safe category or current MVP manual fallback category. | `includes/functions-utils.php`, `includes/class-report-builder.php` | Medium | Request-local token only; no UI value output. | Manual fallback public-release policy. | High |
| Report Builder expired-token / reconnect-required message | `partial_boundary_exists` | Add or refine safe message using reconnect-required / refresh-unavailable labels. | `includes/class-report-builder.php` | Low | Translatable, escaped, no values. | Real GA4 fetch QA. | Medium |
| Settings connection status message | `partial_boundary_exists` | Display formalized lifecycle status labels and local disconnect control if implemented. | `includes/class-settings.php` | Medium | Status labels only; value-hidden posture preserved. | Provider-side revoke UI. | Medium |
| Local disconnect action | `not_implemented` | Add admin-post action with nonce and capability check for local token deletion only. | `includes/class-admin.php`, `includes/class-settings.php` | Medium | No provider request; no values displayed. | Provider-side revoke. | High |
| Local token deletion helper | `not_implemented` | Add helper to delete or reset local OAuth token option/data. | `includes/functions-utils.php` | Medium | Deletes local OAuth token data only. | Manual token fallback deletion and OpenAI key changes. | High |
| Disconnect admin notice | `not_implemented` | Add success / failure status categories after local disconnect. | `includes/class-settings.php`, possibly `includes/class-admin.php` | Low | Category-only notices. | Revoke result notices. | Medium |
| Support/debug safe labels | `partial_boundary_exists` | Add lifecycle-specific safe labels for refresh unavailable, reconnect required, and local disconnect result. | `includes/class-settings.php`, `includes/class-report-builder.php` | Low | No forbidden evidence. | Support docs final release pass. | Low |
| Manual fallback separation | `not_public_release_finalized` | Preserve existing fallback behavior but label it as separate / unresolved for public release. | `includes/functions-utils.php`, `includes/class-settings.php`, `includes/class-report-builder.php` | Low to medium | No token values; no policy resolution. | Manual fallback removal/restriction. | High |
| Provider-side revoke | `not_implemented` | No Step 204 implementation. Keep deferred category or manual-action wording only if needed. | Possibly `includes/class-settings.php` wording only | Low if wording only | No external request. | Revoke request helper and controlled QA. | High |
| Uninstall cleanup relationship | `not_implemented` | No Step 204 uninstall implementation. Record dependency and avoid conflating disconnect with uninstall. | Possibly docs only in Step 204 result | None to low | No uninstall execution. | Dedicated uninstall cleanup track. | High |

## Proposed Files Likely Affected In Step 204

| File | Likely Step 204 use | Change possibility | Notes |
|---|---|---|---|
| `includes/functions-utils.php` | Token lifecycle helper, connection-state normalization, refresh availability category, local token deletion helper. | Likely changed | Highest-value location for shared lifecycle categories and local storage helper boundaries. |
| `includes/class-settings.php` | Settings status labels, local disconnect control, disconnect notice, support-safe wording. | Likely changed | Must preserve value-hidden posture and avoid provider-side revoke request. |
| `includes/class-report-builder.php` | Reconnect-required / refresh-unavailable GA4 credential source message. | Possibly changed | Keep GA4 flow unchanged except safe precondition/message categories. |
| `includes/class-ga4-client.php` | Safe invalid/expired credential wording category. | Possibly unchanged | Prefer avoiding GA4 client changes unless needed for wording consistency. |
| `includes/class-admin.php` | Local disconnect admin-post action, nonce/capability check, status redirect. | Possibly changed | OAuth callback/token exchange behavior should remain unchanged except status label integration if unavoidable. |

Files that should not be touched in Step 204 unless a later plan explicitly
widens scope:

- `readme.txt`,
- JavaScript,
- CSS,
- tools / build scripts,
- release package files.

## Refresh Plan For Narrow Implementation

| Option | Description | Public release safety | Implementation risk | Storage impact | Support/debug boundary | Testing requirement | External QA likelihood |
|---|---|---|---|---|---|---|---|
| Option A | Defer refresh request implementation; implement refresh-needed and reconnect-required categories first. | Strongest immediate safety because no new token endpoint behavior is added. | Medium. Requires careful categories and UI notices but no refresh request path. | Does not expand storage beyond existing token material/metadata. | Easiest to keep status/category-level. | Source-level and local admin verification can cover most behavior. | Low in Step 204/205. |
| Option B | Implement refresh request helper but do not execute it in QA until a later controlled step. | Mixed. Adds sensitive request construction before execution evidence exists. | High. Request body, retry, response, and storage handling must be correct before QA. | Refresh token storage becomes operationally release-critical. | Harder to verify without accidentally exposing request/response assumptions. | Needs later controlled token endpoint QA. | High. |
| Option C | Implement full refresh-before-GA4-Fetch behavior now. | Not recommended for narrow Step 204. | Very high. Changes GA4 Fetch boundary and token endpoint behavior. | High. Requires robust storage/update/failure policy. | More support labels and failure paths needed. | Requires controlled external OAuth/token/GA4 QA. | Very high. |

Current recommendation:

```text
Refresh request implementation: Deferred
Recommended refresh option for Step 204: Option A
```

Step 204 should formalize refresh-needed / refresh-unavailable /
reconnect-required categories first, without implementing refresh request
execution.

## Disconnect Plan For Narrow Implementation

If local disconnect is included in Step 204, the plan should include:

- admin action routed through WordPress admin-post,
- capability check using the same administrative boundary as Settings/OAuth
  actions,
- nonce check for the disconnect action,
- local OAuth token option deletion or local token data reset,
- deletion of OAuth access token material,
- deletion of OAuth refresh token material,
- deletion of expiry metadata,
- deletion or reset of connection metadata,
- Settings UI control wording that says this is local disconnect only,
- success notice category,
- failure notice category,
- no effect on manual Google Access Token fallback,
- no effect on OpenAI API key,
- no provider-side revoke request,
- no option value or token value display.

Recommended local disconnect posture:

```text
Local disconnect: Include as narrow Step 204 candidate
Provider-side revoke during disconnect: No
Manual token fallback affected by disconnect: No
OpenAI API key affected by disconnect: No
```

Potential safe labels:

```text
token_disconnect_status_category: local_tokens_deleted
token_disconnect_status_category: failed
```

## Revoke Plan

Provider-side revoke decision for Step 204:

```text
Provider-side revoke request: Defer
Manual revoke guidance: Plan later after local disconnect boundary
```

Rationale:

- Revoke adds a new external request boundary.
- Revoke requires controlled QA in a later explicitly authorized step.
- Revoke failure can involve raw provider response categories that must be
  normalized carefully.
- Revoke can be confused with local disconnect unless local storage deletion is
  implemented and documented first.
- Local disconnect and storage cleanup should be designed before provider-side
  revoke.

Step 204 should not add provider-side revoke request construction, execution,
or response handling.

## Manual Token Fallback Relationship

Manual Google Access Token fallback must remain outside the Step 204 lifecycle
implementation scope.

```text
manual_token_fallback_status_category: public_release_unresolved
manual_token_fallback_status_category: separate_track
```

Planned relationship:

- Preserve current MVP boundary where the GA4 credential resolver may use
  manual fallback under existing conditions.
- Do not resolve public-release treatment for manual fallback in Step 204.
- Do not delete manual fallback when local OAuth disconnect is requested.
- Do not combine manual fallback cleanup with OAuth token local disconnect.
- Keep manual fallback removal/restriction as a separate policy and
  implementation track.

## Credential Storage / Uninstall Cleanup Relationship

| Area | Step 204 relationship | Step 204 posture |
|---|---|---|
| OAuth access token storage | Local disconnect may delete local OAuth token data. | Can be affected only by explicit local disconnect helper. |
| OAuth refresh token storage | Local disconnect may delete local refresh token data if present. | Can be affected only by explicit local disconnect helper. |
| Expiry metadata | Local disconnect should delete/reset expiry metadata. | Include in local deletion target. |
| Local disconnect deletion target | OAuth token option/data only. | Do not affect manual fallback or OpenAI key. |
| Uninstall cleanup target | Related but separate release blocker. | Do not implement in Step 204. |
| OpenAI API key storage | Separate credential storage track. | Do not change in Step 204. |
| Ad hoc encryption | Not adopted as a narrow lifecycle patch. | Do not introduce in Step 204. |
| Privacy/readme final wording | Later P1 follow-up after lifecycle/storage decisions. | Do not change in Step 204. |

## Safe Status / Category Labels Plan

Candidate safe labels to introduce or formalize in Step 204 and later:

```text
oauth_connection_status_category: connected
oauth_connection_status_category: not_connected
oauth_connection_status_category: reconnect_required
oauth_connection_status_category: token_expired_or_refresh_needed
oauth_connection_status_category: disconnected

token_lifecycle_status_category: usable
token_lifecycle_status_category: expired
token_lifecycle_status_category: refresh_unavailable
token_lifecycle_status_category: reconnect_required

token_refresh_status_category: not_attempted
token_refresh_status_category: unavailable
token_refresh_status_category: deferred

token_disconnect_status_category: not_requested
token_disconnect_status_category: local_tokens_deleted
token_disconnect_status_category: failed

token_revoke_status_category: deferred
token_revoke_status_category: manual_action_required
```

These labels must not include real values, token fragments, OAuth client
fragments, request bodies, raw responses, provider details, option values,
screenshots, browser Network evidence, or analytics data.

## Admin Notices / Support-safe Wording Plan

Future notices and wording should remain category-level:

| Notice / wording topic | Planned category-level direction | Forbidden evidence boundary |
|---|---|---|
| Connected | Show connected / usable status only. | No token value, client value, option value, or expiry value. |
| Not connected | Show not-connected status and safe action hint. | No option value inspection. |
| Reconnect required | Explain that the Google connection must be reconnected before OAuth credential use. | No raw provider details or callback values. |
| Token expired | Explain expired / refresh-unavailable category only. | No token value or refresh-token presence detail beyond category. |
| Refresh unavailable | Say refresh is unavailable or deferred and reconnect may be needed. | No refresh request/response evidence. |
| Local disconnect completed | Say local OAuth token data was deleted. | No deleted value details. |
| Local disconnect failed | Say local disconnect did not complete. | No option value or serialized data output. |
| Provider revoke not implemented / manual action required | Explain provider-side revoke is deferred or manual. | No provider URLs, account IDs, screenshots, or Network evidence. |
| Support request guidance | Ask for visible status labels / warning labels only. | Do not request credentials, tokens, option values, request/response bodies, screenshots, Network evidence, or generated content. |

## Verification Plan For Step 204 / Step 205

Future implementation verification should include syntax and diff checks:

```bash
php -l includes/functions-utils.php
php -l includes/class-settings.php
php -l includes/class-report-builder.php
php -l includes/class-ga4-client.php
php -l includes/class-admin.php
find includes -name '*.php' -print0 | xargs -0 -n1 php -l
git diff --check
git diff --stat
git diff --name-only
git status --short --untracked-files=all
```

Future source-level verification should check:

- token values are not displayed,
- option values are not output,
- notices are status/category-level,
- disconnect does not affect manual fallback,
- disconnect does not affect OpenAI API key,
- provider-side revoke request is not implemented if deferred,
- refresh request is not implemented if deferred,
- GA4 Fetch is not executed during verification,
- OpenAI Generate is not executed during verification,
- OAuth Connect / Authorize is not executed during verification,
- browser admin smoke is only done in a later scoped step if needed.

## Forbidden Evidence Boundary

This Step 203 plan does not record, display, infer, request, or rely on:

- OAuth client ID values,
- OAuth client secret values,
- OAuth client value fragments,
- access token values,
- refresh token values,
- Authorization headers,
- credentials,
- API keys,
- plugin option values,
- OAuth token option values,
- serialized option values,
- request bodies,
- raw responses,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces,
- database dumps,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- AI payload JSON,
- generated report bodies.

Allowed evidence remains status/category-level and source-level only.

## Current Decision

Current Step 203 decision:

```text
OAuth token lifecycle narrow implementation status: Planning complete / ready for narrow implementation
Recommended Step 204 scope: lifecycle status/category boundaries, reconnect-required notices, refresh availability categories, and local disconnect boundary
Refresh request implementation: Deferred
Provider-side revoke request: Deferred
Manual token fallback status: Public release unresolved separate track
Credential storage status: Linked release blocker
Uninstall cleanup status: Linked release blocker
WordPress.org release status: Hold
```

This is a narrow implementation plan, not a production implementation and not a
release-readiness decision.

## Acceptance Criteria

| Criterion | Status | Notes |
|---|---|---|
| Docs-only narrow implementation plan file added | Pass | This file records Step 203. |
| Production code / readme / tools / JS / CSS unchanged | Pass | Step 203 adds this docs file only. |
| Step 202 inventory converted to Step 204 implementation scope | Pass | Planned implementation table maps inventory items to Step 204 candidates. |
| Refresh / reconnect / disconnect / revoke direction clear | Pass | Refresh request and provider-side revoke are deferred; reconnect categories and local disconnect boundary are Step 204 candidates. |
| Manual fallback / storage / uninstall relationship organized | Pass | Manual fallback remains separate; storage and uninstall remain linked blockers. |
| Safe labels / admin notices plan organized | Pass | Candidate labels and notice wording directions are recorded. |
| Forbidden evidence not recorded | Pass | No credentials, token values, option values, request/response bodies, screenshots, Network evidence, payloads, or generated report bodies are recorded. |
| WordPress.org release remains `Hold` | Pass | Release status is unchanged. |
| Next recommended step is clear | Pass | Step 204 is recommended below. |

## Not Executed

Not executed in Step 203:

- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- refresh request,
- revoke request,
- browser admin smoke,
- screenshots,
- browser Network evidence collection,
- database dump,
- option value output.

## Recommended Next Step

Recommended next step:

```text
Step 204: OAuth token lifecycle narrow production implementation
```

Step 204 should be a narrow production implementation limited to the Step 203
scope: lifecycle status/category boundaries, reconnect-required notices,
refresh availability categories, and local disconnect boundary.

Step 204 should not execute OAuth, token endpoint communication, refresh
requests, revoke requests, Plugin Check, browser admin smoke, screenshots,
browser Network evidence, GA4 Fetch, OpenAI Generate, option value inspection,
or database dumps.

## Result Classification

```text
OAuth token lifecycle narrow implementation plan completed
```
