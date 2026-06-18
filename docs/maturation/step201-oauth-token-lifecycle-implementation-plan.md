# Step 201: OAuth Token Lifecycle Implementation Plan

## Step Purpose

Step 201 is a docs-only and planning-only implementation plan for the OAuth
token lifecycle release blocker identified in Step 200.

This plan organizes the future implementation scope for refresh, expiry,
reconnect, disconnect, revoke, local token deletion, provider-side revoke,
admin notices, support-safe labels, storage implications, and uninstall cleanup
relationships before any production code changes.

This step does not change production code, `readme.txt`, tools, build scripts,
JavaScript, CSS, OAuth runtime behavior, OAuth resolver behavior, Settings
save/delete behavior, credential storage behavior, token storage behavior, GA4
behavior, or OpenAI behavior.

This step does not execute OAuth, Google navigation, authorization approval,
token endpoint communication, refresh requests, revoke requests, browser admin
smoke, GA4 Fetch, OpenAI Generate, or Plugin Check.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step200-oauth-production-readiness-token-lifecycle-decision-checkpoint.md`
- `docs/maturation/step199-public-release-remaining-blocker-reprioritization-checkpoint.md`
- `docs/maturation/step198-oauth-client-configuration-hybrid-source-final-maturation-checkpoint.md`
- `docs/maturation/step183-oauth-public-release-readiness-implementation-plan.md`
- `docs/maturation/step184-oauth-source-inventory-current-boundary-review.md`
- `docs/maturation/step182-oauth-credential-public-release-strategy-checkpoint.md`
- `docs/maturation/step181-public-release-blocker-prioritization-checkpoint.md`
- `docs/maturation/step179-support-debug-wording-maturation-checkpoint.md`
- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`

## Lifecycle Implementation Scope

The lifecycle implementation track should cover the following areas before
public-release readiness is reconsidered:

| Area | Planning status | Release impact | Notes |
|---|---|---|---|
| Access token expiry detection | `needs_source_inventory` | Blocks reliable connected/expired state. | Inventory current expiry metadata and status-category behavior before implementation. |
| Refresh token availability | `needs_policy_and_inventory` | Blocks refresh-capable lifecycle. | Decide whether refresh token presence is required, optional, or unsupported. |
| Refresh token storage | `needs_storage_policy` | Blocks release storage posture. | Must align with credential storage, value-hidden UI, and uninstall cleanup. |
| Refresh flow trigger | `needs_implementation_plan` | Blocks public OAuth UX. | Decide whether refresh runs before GA4 Fetch, after invalid response, or both with strict retry limits. |
| Refresh request construction | `not_executed_in_step_201` | Requires later controlled implementation/QA. | Must not expose request body, headers, token values, or provider details. |
| Refresh response handling | `not_executed_in_step_201` | Requires safe category-only handling. | Success/failure categories should not include raw response details. |
| Refresh failure handling | `needs_admin_notice_plan` | Blocks reconnect UX. | Failure should move to `reconnect_required` without exposing token/provider values. |
| Reconnect UX | `needs_implementation_plan` | Required for public OAuth lifecycle. | Must cover expired, invalid, missing refresh token, and manual disconnect states. |
| Disconnect local token deletion | `needs_implementation_plan` | Required for user control. | Must define local deletion of access token, refresh token, expiry metadata, and connection state. |
| Provider-side revoke | `needs_policy_decision` | Needed for revoke posture. | Decide explicit revoke request vs manual revoke guidance vs defer. |
| Manual revoke guidance | `needed_if_revoke_deferred` | Affects privacy/support wording. | Guidance must avoid requesting provider evidence, URLs, screenshots, or identifiers. |
| Token invalid / expired admin notice | `needs_final_wording` | Required for safe support. | Notices should use status/category labels only. |
| GA4 Fetch behavior when token is expired | `needs_behavior_decision` | Blocks report-generation UX. | Decide refresh-before-fetch, reconnect block, or controlled fallback behavior. |
| Support/debug safe status labels | `needs_final_label_set` | Required for support posture. | Preserve status/category-level evidence boundary from Step 179. |
| Uninstall cleanup relationship | `needs_storage_inventory` | Blocks release readiness. | Cleanup must follow the final token/key storage model. |

## Recommended Lifecycle Model

Recommended lifecycle model for public release planning:

```text
Refresh-capable lifecycle should be the target for public release planning, but public release remains Hold until refresh, reconnect, disconnect, revoke, storage, uninstall cleanup, and controlled QA are planned and verified together.
```

Rationale:

- A refresh-capable lifecycle gives the best normal admin UX once implemented
  and verified.
- Refresh-capable OAuth also increases stored-secret impact because refresh
  token storage, non-redisplay posture, safe notices, and uninstall cleanup
  become release-critical.
- A minimal reconnect-only lifecycle should remain a comparison option if the
  storage or QA cost of refresh-capable OAuth is intentionally rejected.
- Neither model should be treated as release-ready until storage, manual
  fallback, reconnect/disconnect, revoke, uninstall cleanup, readme/privacy
  wording, and controlled QA are aligned.

## Planned Behavior Table

| Lifecycle event | Planned behavior | Admin UI status category | Storage impact | Support/debug evidence allowed | Implementation risk | Recommended implementation step |
|---|---|---|---|---|---|---|
| No token stored | Show not connected / credential missing state and prompt OAuth connection or configured fallback per final policy. | `oauth_connection_status_category: not_connected` | No OAuth token value should exist for OAuth storage. | Status/category label only. | Low | Step 202 inventory, then later UI wording verification if changed. |
| Access token present | Treat as connected only when token metadata is usable and not expired according to final policy. | `oauth_connection_status_category: connected` | Access token and expiry metadata remain credential-bearing storage. | Connected/disconnected category only. | Medium | Step 202 inventory and Step 203 plan. |
| Access token expired | Attempt refresh only if refresh-capable lifecycle is implemented and refresh token is available; otherwise require reconnect. | `token_lifecycle_status_category: reconnect_required` or `token_refresh_status_category: not_attempted` | May update expiry metadata only after successful refresh. | Expired/reconnect category only. | High | Step 203 plan before code. |
| Access token invalid | Move to refresh or reconnect handling without displaying provider/token details. | `token_lifecycle_status_category: reconnect_required` | May delete or preserve local token according to final failure policy. | Invalid/reconnect category only. | High | Step 203 plan, Step 204 implementation. |
| Refresh token present | Permit refresh attempt only inside final refresh-capable lifecycle boundary. | `token_lifecycle_status_category: refresh_capable_planned` | Refresh token is high-impact credential storage. | Presence category only; no token fragment. | High | Step 202 inventory, storage decision track. |
| Refresh token missing | Do not attempt refresh; require reconnect or configured non-OAuth fallback according to final policy. | `token_refresh_status_category: unavailable` | No refresh-token storage update. | Missing/unavailable category only. | Medium | Step 203 plan. |
| Refresh success | Store new access token and expiry metadata; preserve or update refresh token according to provider response and final policy. | `token_refresh_status_category: success` | Updates credential-bearing token storage. | Success category only. | High | Step 204 implementation after plan. |
| Refresh failure | Do not expose response details; set reconnect-required state and show safe admin notice. | `token_refresh_status_category: failed` and `token_lifecycle_status_category: reconnect_required` | May clear access token or mark unusable per final policy. | Failure category only. | High | Step 204 implementation after plan. |
| Reconnect required | Show explicit reconnect action and status-level explanation. | `oauth_connection_status_category: reconnect_required` | Existing local token handling must be defined before reconnect. | Reconnect-required category only. | Medium | Step 203 plan. |
| Disconnect requested | Delete local OAuth access token, refresh token, expiry metadata, and connection state; do not affect OpenAI key. | `token_disconnect_status_category: local_tokens_deleted` | Removes OAuth token storage; no provider request unless revoke is separate. | Local deletion category only. | Medium | Separate disconnect implementation step after inventory. |
| Provider revoke requested | If implemented, perform explicit provider revoke action with safe category-only result. | `token_revoke_status_category: success` or `token_revoke_status_category: failed` | May delete local tokens after successful or attempted revoke per final policy. | Revoke result category only. | High | Dedicated revoke plan/implementation after Step 203. |
| Provider revoke unavailable or deferred | Keep local disconnect available and provide manual revoke guidance. | `oauth_connection_status_category: revoke_not_configured` | Local tokens can still be deleted by disconnect. | Manual-action category only. | Medium | Step 203 policy decision. |
| Manual revoke guidance needed | Provide non-sensitive guidance without requesting screenshots, URLs, identifiers, or provider raw errors. | `token_revoke_status_category: manual_action_required` | No direct storage change unless paired with local disconnect. | Manual guidance category only. | Medium | Later wording plan. |
| Uninstall cleanup | Delete credential-bearing token/settings data according to final storage model. | `uninstall_cleanup_status_category: credential_data_cleanup_planned` | Removes OAuth token data and possibly selected credential-bearing settings. | Cleanup category only. | High | Dedicated uninstall cleanup track after storage inventory. |

## Safe Status / Category Labels

Candidate safe labels for future UI, support, QA, and documentation:

```text
oauth_connection_status_category: not_connected
oauth_connection_status_category: connected
oauth_connection_status_category: reconnect_required
oauth_connection_status_category: disconnected
oauth_connection_status_category: revoke_not_configured

token_lifecycle_status_category: not_finalized
token_lifecycle_status_category: refresh_capable_planned
token_lifecycle_status_category: refresh_not_available
token_lifecycle_status_category: refresh_failed
token_lifecycle_status_category: reconnect_required

token_refresh_status_category: not_attempted
token_refresh_status_category: success
token_refresh_status_category: failed
token_refresh_status_category: unavailable

token_disconnect_status_category: local_tokens_deleted
token_revoke_status_category: not_attempted
token_revoke_status_category: success
token_revoke_status_category: failed
token_revoke_status_category: manual_action_required
```

These labels must never include token values, token fragments, OAuth client
values, raw provider errors, request bodies, response bodies, Authorization
headers, option values, screenshots, browser Network evidence, or analytics
data.

## Refresh Flow Plan

If refresh-capable lifecycle is adopted, the future implementation plan should
include these boundaries:

- Refresh only when a refresh token is stored and the final storage policy
  accepts refresh-token persistence.
- Detect access token expiry through stored expiry metadata when available.
- Detect invalid-token categories from GA4 response handling only through safe
  normalized categories, not raw response bodies.
- Prefer a deterministic refresh-before-GA4-Fetch decision when expiry
  metadata says the token is expired or near expiry.
- Consider one controlled refresh-after-invalid-token attempt only if it can be
  done without repeated external calls, request-body exposure, or ambiguous
  support evidence.
- On refresh success, store the new access token and expiry metadata, and
  update refresh token only according to provider response and final storage
  rules.
- On refresh failure, show `reconnect_required` and a safe admin notice.
- If no refresh token exists, do not attempt refresh; show
  `refresh_not_available` / `reconnect_required`.
- Keep automatic retry count narrow, preferably one refresh attempt per user
  action boundary.
- Do not display or request refresh request bodies, response bodies, token
  values, provider details, or Authorization headers in UI/support/docs.

Planned safe admin notice direction:

```text
The Google connection needs to be reconnected before GA4 data can be fetched.
For support, share only the connection status category or warning label.
```

## Reconnect Plan

Reconnect UX should cover the following:

- Show `reconnect_required` when refresh is unavailable, refresh fails, token
  metadata is unusable, or a manual disconnect happened.
- Define whether existing local tokens are deleted before reconnect starts or
  replaced only after a successful new callback/token exchange.
- Keep OAuth Connect / Reconnect button wording category-level and value-safe.
- Use reconnect after refresh failure, access-token invalid state, and manual
  disconnect state.
- Do not expose raw callback values, authorization codes, state values, token
  exchange request/response details, or provider raw errors.
- Support/debug guidance should request only status/category labels and visible
  safe admin notices.

Recommended reconnect posture:

```text
Reconnect should be the safe fallback state whenever refresh cannot establish a usable OAuth credential.
```

## Disconnect Plan

Local disconnect should be designed separately from provider-side revoke.

Planned local disconnect behavior:

- Delete local OAuth access token data.
- Delete local OAuth refresh token data.
- Delete token expiry metadata.
- Delete or reset OAuth connection status metadata.
- Show a status/category-level success or failure notice only.
- Do not affect manual Google Access Token fallback unless a later policy
  explicitly removes or restricts that fallback.
- Do not affect the OpenAI API key.
- Do not perform provider-side revoke unless the user chooses a separate
  explicit revoke action and revoke implementation is release-planned.
- Do not display option values, token values, serialized data, request bodies,
  or raw provider responses.

Recommended disconnect label:

```text
token_disconnect_status_category: local_tokens_deleted
```

## Revoke Plan

Provider-side revoke needs a separate policy decision because it introduces a
new external request boundary.

| Option | Description | Security/privacy posture | Implementation cost | Support burden | Risk of external request handling | Admin UX | Readme/privacy wording | Testing requirements |
|---|---|---|---|---|---|---|---|---|
| Option A | Implement provider-side revoke request as explicit admin action. | Strongest user-control posture if implemented safely. | High. Requires revoke request construction, response categories, local cleanup coordination, and controlled QA. | Medium after implementation, higher during failures. | High because it adds a new external request path. | Best if clear: Disconnect local data vs Revoke provider access. | Must disclose revoke behavior and failure limits. | Requires controlled revoke success/failure QA in a later authorized step. |
| Option B | Do not implement provider-side revoke initially; provide manual revoke guidance only. | Acceptable only if clearly documented and local disconnect is reliable. | Medium. Requires careful wording and manual guidance. | Higher because users may ask how to revoke provider-side access. | Low from plugin runtime because no revoke request is sent. | Simpler UI, but less complete control from inside plugin. | Must explain that provider-side revoke is manual. | Needs admin wording/source verification, no external revoke QA. |
| Option C | Defer revoke decision until storage/disconnect implementation is planned. | Safest for Step 201 because storage and disconnect semantics are not finalized. | Low now, but not a release-ready endpoint. | Keeps blocker visible. | No new external request now. | No public-release claim. | Release remains Hold until decided. | Later policy and QA required. |

Recommended Step 201 posture:

```text
Provider-side revoke decision: Defer until storage/disconnect implementation is planned
```

Rationale:

- Revoke should not be implemented as a narrow patch before token storage,
  local disconnect, uninstall cleanup, and support-safe result labels are
  finalized.
- Local disconnect can be planned first as a narrower user-control boundary.
- Manual revoke guidance should remain an option if provider-side revoke is not
  included in the first public-release lifecycle implementation.

## Manual Google Access Token Fallback Relationship

Manual Google Access Token fallback should remain a separate public-release
blocker and should not be mixed into the OAuth token lifecycle implementation.

| Candidate posture | Step 201 planning category | Notes |
|---|---|---|
| `remove_before_public_release` | Possible later policy | Reduces normal public credential handling risk but depends on OAuth lifecycle readiness or continued release Hold. |
| `restrict_to_development_or_debug` | Possible later policy | Preserves developer verification while avoiding normal public UI exposure. Needs explicit access boundary and wording. |
| `keep_but_not_recommended` | High-risk fallback | Would require explicit public risk acceptance, storage disclosure, and support boundaries. |
| `needs_policy_decision` | Current status | Keep as public-release blocker outside the Step 201 lifecycle implementation plan. |

Current category:

```text
manual_token_fallback_status_category: public_release_unresolved
```

Recommendation:

- Do not solve manual token fallback inside the lifecycle implementation step.
- Keep manual fallback policy as a linked but separate release blocker.
- Revisit after source-level OAuth lifecycle inventory and storage implications
  are clear.

## Credential Storage / OpenAI Key / Uninstall Cleanup Relationship

| Area | Relationship to lifecycle plan | Step 201 posture |
|---|---|---|
| OAuth access token storage | Required for OAuth-connected GA4 requests. | Must be inventoried and aligned with expiry/refresh/disconnect behavior. |
| OAuth refresh token storage | Required for refresh-capable lifecycle. | Higher-impact storage; must not be finalized without cleanup/support policy. |
| Token expiry metadata | Required for refresh-before-fetch and safe status labels. | Should be stored and deleted with OAuth token lifecycle data. |
| OpenAI API key storage | Separate credential, but part of overall public credential posture. | Do not change in lifecycle implementation; align later with broader storage strategy. |
| Settings UI value-hidden non-redisplay | Existing matured posture. | Preserve. Do not redisplay token, client, or key values. |
| Option storage disclosure | Must match final storage model. | Update only after lifecycle/storage decisions are final. |
| Ad hoc encryption non-adoption | Prior strategy rejected opportunistic encryption. | Do not add ad hoc encryption as part of token lifecycle implementation. |
| Uninstall cleanup of credential-bearing data | Must follow final storage inventory. | Remains linked release blocker and needs dedicated plan. |
| Privacy/readme wording impact | Must describe real OAuth/storage/revoke/disconnect behavior. | Recheck after lifecycle implementation decisions. |

## Implementation Sequence Proposal

Recommended sequence after Step 201:

```text
Step 202: OAuth token lifecycle source-level inventory
Step 203: OAuth token lifecycle narrow implementation plan
Step 204: OAuth token lifecycle narrow production implementation
Step 205: OAuth token lifecycle source-level verification
Step 206: OAuth token lifecycle human admin smoke plan
Step 207: OAuth token lifecycle controlled QA plan
```

Recommended separation:

- Manual Google Access Token fallback should remain a separate policy track.
- Credential storage strategy should remain a linked storage/public-release
  track rather than being silently changed inside token lifecycle work.
- Uninstall cleanup should be planned after token/key storage inventory is
  clear.
- Provider-side revoke may need its own implementation plan if selected.

## Verification Plan For Future Implementation

Future implementation steps should use safe verification such as:

- PHP syntax checks for changed PHP files.
- `find includes -name '*.php' -print0 | xargs -0 -n1 php -l` after PHP
  changes.
- `git diff --check`.
- `git diff --stat`.
- `git diff --name-only`.
- `git status --short --untracked-files=all`.
- Source-level status/category label checks.
- Source-level forbidden-evidence absence checks.
- Verification that token values are not shown in notices.
- Verification that raw request/response bodies are not logged, displayed, or
  requested for support.
- Verification that option values are not output in docs, commands, or UI.
- Verification that screenshots and browser Network evidence are not required.
- Controlled OAuth/token QA only in an explicitly authorized later step.

Future controlled QA must remain separate from implementation planning and
must not record token values, client values, raw URLs, callback query strings,
request bodies, response bodies, screenshots, Network evidence, cookies,
sessions, nonces, option values, or analytics data.

## Forbidden Evidence Boundary

This Step 201 plan does not record, display, infer, request, or rely on:

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

Current Step 201 decision:

```text
OAuth token lifecycle status: Implementation plan required
Recommended lifecycle direction: Refresh-capable lifecycle planning, with public release remaining Hold until refresh/reconnect/disconnect/revoke/storage/uninstall cleanup are planned and verified
Manual token fallback status: Public release unresolved
Credential storage status: Linked release blocker
Uninstall cleanup status: Linked release blocker
```

This is an implementation plan checkpoint, not a production implementation and
not a release-readiness decision.

## Acceptance Criteria

| Criterion | Status | Notes |
|---|---|---|
| Docs-only implementation plan file added | Pass | This file records Step 201. |
| Production code / readme / tools / JS / CSS unchanged | Pass | Step 201 adds this docs file only. |
| Token lifecycle implementation scope organized | Pass | Scope table covers expiry, refresh, reconnect, disconnect, revoke, notices, GA4 behavior, support labels, and uninstall relationship. |
| Refresh / reconnect / disconnect / revoke plan organized | Pass | Dedicated sections define planning boundaries without execution. |
| Manual token fallback relationship organized | Pass | Manual fallback remains a separate public-release blocker. |
| Credential storage / uninstall cleanup dependencies organized | Pass | Storage and cleanup are linked to lifecycle decisions but not changed here. |
| Safe status/category labels organized | Pass | Candidate labels are listed without raw values. |
| Forbidden evidence not recorded | Pass | No credentials, token values, option values, request/response bodies, screenshots, Network evidence, payloads, or generated report bodies are recorded. |
| WordPress.org release remains `Hold` | Pass | Release status is unchanged. |
| Next recommended step is clear | Pass | Step 202 is recommended below. |

## Not Executed

Not executed in Step 201:

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
Step 202: OAuth token lifecycle source-level inventory
```

Step 202 should be docs-only / inspection-only. It should inventory the current
OAuth token storage, expiry, refresh, reconnect, disconnect, revoke, GA4 token
use, admin notices, and support-safe labels at source level.

Step 202 should not execute OAuth, token endpoint communication, refresh
requests, revoke requests, Plugin Check, browser admin smoke, screenshots,
browser Network evidence, GA4 Fetch, OpenAI Generate, option value output, or
database dumps.

## Result Classification

```text
OAuth token lifecycle implementation plan completed
```
