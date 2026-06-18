# Step 200: OAuth Production Readiness and Token Lifecycle Decision Checkpoint

## Step Purpose

Step 200 is a docs-only and planning-only decision checkpoint for the remaining
OAuth production readiness and token lifecycle release blockers identified in
Step 199.

This step focuses on:

- `P0-1: OAuth production readiness / full OAuth flow boundary`
- `P0-2: token lifecycle: expiry / refresh / reconnect / disconnect / revoke policy`

It also records the dependency relationship with:

- `P0-3: manual Google Access Token fallback public-release treatment`
- `P0-4: credential storage strategy for OAuth tokens and OpenAI API key`
- `P0-5: uninstall cleanup`

This step does not change production code, `readme.txt`, tools, build scripts,
JavaScript, CSS, OAuth runtime behavior, OAuth resolver behavior, Settings
save/delete behavior, credential storage behavior, token storage behavior, GA4
behavior, or OpenAI behavior.

No OAuth execution, Google navigation, authorization approval, token endpoint
communication, browser admin smoke, GA4 Fetch, OpenAI Generate, or Plugin Check
is performed in this step.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step199-public-release-remaining-blocker-reprioritization-checkpoint.md`
- `docs/maturation/step198-oauth-client-configuration-hybrid-source-final-maturation-checkpoint.md`
- `docs/maturation/step183-oauth-public-release-readiness-implementation-plan.md`
- `docs/maturation/step184-oauth-source-inventory-current-boundary-review.md`
- `docs/maturation/step185-oauth-app-ownership-provider-configuration-decision-checkpoint.md`
- `docs/maturation/step182-oauth-credential-public-release-strategy-checkpoint.md`
- `docs/maturation/step181-public-release-blocker-prioritization-checkpoint.md`
- `docs/maturation/step179-support-debug-wording-maturation-checkpoint.md`
- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`

Additional context:

- `docs/maturation/step186-oauth-client-configuration-source-strategy-implementation-plan.md`
- `docs/maturation/step187-oauth-client-configuration-source-level-inventory.md`
- `docs/maturation/step188-oauth-client-configuration-hybrid-source-implementation-plan.md`
- `docs/maturation/step189-oauth-client-configuration-hybrid-source-narrow-implementation-results.md`
- `docs/maturation/step190-oauth-client-configuration-hybrid-source-source-level-verification-results.md`
- `docs/maturation/step191-oauth-client-configuration-hybrid-source-human-admin-smoke-plan.md`
- `docs/maturation/step192-oauth-client-configuration-hybrid-source-human-admin-smoke-results.md`
- `docs/maturation/step193-oauth-client-configuration-hybrid-source-maturation-checkpoint.md`
- `docs/maturation/step194-oauth-client-configuration-placeholder-description-wording-follow-up.md`
- `docs/maturation/step195-oauth-client-configuration-placeholder-description-narrow-wording-fix-results.md`
- `docs/maturation/step196-oauth-client-configuration-placeholder-description-wording-source-level-verification-results.md`
- `docs/maturation/step197-oauth-client-configuration-placeholder-description-follow-up-closure-checkpoint.md`

## Current Status Summary

| Area | Current status | Release impact |
|---|---|---|
| OAuth client configuration hybrid source track | `matured_for_current_mvp_scope` | Source strategy is no longer an active release blocker. |
| OAuth client source strategy | `removed_from_active_release_blockers` | Preserve constants-preferred + Settings fallback category posture. |
| Full OAuth flow readiness | `not_release_ready` | Remains a P0 release blocker. |
| Token lifecycle | `not_finalized` | Remains a P0 release blocker. |
| Manual Google Access Token fallback | `public_release_treatment_undecided` | Remains linked to OAuth readiness and release posture. |
| Credential storage strategy | `not_finalized` | Remains linked to token lifecycle and uninstall cleanup. |
| OpenAI API key storage strategy | `not_finalized` | Needs alignment with broader credential storage posture. |
| Uninstall cleanup | `not_finalized` | Needs policy and implementation after storage decisions. |
| WordPress.org release | `Hold` | Release readiness is not claimed. |

## Full OAuth Flow Readiness Boundary

| OAuth flow element | Current classification | Release-readiness implication | Notes |
|---|---|---|---|
| OAuth Connect precondition | `implemented_source_boundary` | Needs preservation and later controlled QA. | Uses capability, nonce, and resolved client configuration categories before redirect. |
| Authorization URL construction | `implemented_but_not_executed` | Needs later controlled QA without recording URLs or query strings. | Construction boundary exists, but Step 200 does not generate or record URL evidence. |
| State generation / validation | `implemented_source_boundary` | Needs preservation and later controlled QA. | Status/category evidence only; raw state is forbidden evidence. |
| Google authorization navigation | `needs_controlled_qa_later` | Public readiness cannot be claimed until safely verified. | Not executed in Step 200. |
| Authorization callback handling | `implemented_but_not_executed` | Needs controlled callback-path QA and lifecycle decisions. | Raw callback values remain forbidden evidence. |
| Authorization code handling | `implemented_but_not_executed` | Needs controlled QA before public release. | Authorization code values must not be displayed or recorded. |
| Token endpoint request construction | `implemented_but_not_executed` | Needs lifecycle/storage policy before release claim. | No token endpoint communication in Step 200. |
| Token endpoint response handling | `implemented_but_not_executed` | Needs controlled QA and category-only support labels. | Raw response bodies remain forbidden evidence. |
| Access token storage | `implemented_but_not_public_release_finalized` | Storage posture must be finalized before release readiness. | Storage values are not inspected in this step. |
| Refresh token storage | `not_public_release_finalized` | Needs explicit policy before refresh-capable release posture. | Public release should not rely on unspecified refresh-token behavior. |
| Error category handling | `implemented_source_boundary` | Needs preservation and final wording review. | Safe categories are useful but not sufficient for release readiness. |
| Reconnect path | `not_public_release_finalized` | Required for practical public OAuth lifecycle. | Must be planned with expiry/refresh failure behavior. |
| Disconnect path | `not_implemented_or_not_finalized` | Needed if public OAuth connection management is supported. | Must define local token deletion behavior. |
| Revoke path | `not_implemented_or_not_finalized` | Needed if provider-side access revocation is a release requirement. | Must define provider-side revoke boundary and manual guidance fallback. |
| Support/debug evidence boundary | `implemented_source_boundary` | Preserve through final release docs. | Status/category-level labels only. |
| Privacy/readme wording impact | `needs_policy_decision` | Final wording must follow lifecycle/storage decisions. | Needs later P1 wording pass after P0 decisions. |

## Token Lifecycle Decision Areas

| Lifecycle area | Current status | Release blocker impact | Notes |
|---|---|---|---|
| Access token expiry detection | `partial_boundary_exists` | Needs policy and QA. | Expiry-related status can exist, but release UX is not finalized. |
| Refresh token availability | `not_finalized` | Blocks refresh-capable readiness. | Must define whether refresh tokens are required, optional, or unsupported. |
| Refresh token storage | `not_finalized` | Blocks storage and uninstall decisions. | Must be decided with storage posture and cleanup. |
| Refresh flow trigger | `not_implemented_or_not_finalized` | Blocks automatic refresh posture. | Needs before/after expiry behavior decision. |
| Refresh failure handling | `not_finalized` | Blocks public support guidance. | Needs reconnect/error category behavior. |
| Reconnect UX | `not_finalized` | Blocks practical public OAuth UX. | Must define admin state and safe support labels. |
| Disconnect local token deletion | `not_finalized` | Blocks user control and cleanup posture. | Needs local deletion semantics without provider revoke confusion. |
| Provider-side revoke | `not_finalized` | Blocks revoke posture if supported. | Needs provider request boundary or explicit manual guidance. |
| Manual revoke guidance | `not_finalized` | Needed if provider-side revoke is deferred. | Must avoid asking users to share forbidden evidence. |
| Token invalid / expired admin notice | `partial_boundary_exists` | Needs final wording. | Should remain category/status-level. |
| GA4 Fetch behavior when token is expired | `not_public_release_finalized` | Blocks release flow clarity. | Must define block/reconnect/manual fallback behavior. |
| Support/debug safe status labels | `partial_boundary_exists` | Needs final list. | Should use safe categories only. |
| Uninstall cleanup relationship | `not_finalized` | Blocks release readiness. | Cleanup must match token storage model. |

## Recommended Lifecycle Posture Options

| Option | Public release suitability | Security/privacy posture | UX | Implementation cost | Support burden | Storage implications | Uninstall implications | Readme/privacy wording impact | Testing requirements |
|---|---|---|---|---|---|---|---|---|---|
| Option A: Minimal lifecycle: detect invalid/expired token, show reconnect guidance, no automatic refresh | Potentially acceptable only if clearly documented and intentionally scoped. | Avoids refresh-token storage if refresh is not used, reducing stored-secret impact. | Users may need to reconnect manually when tokens expire or fail. | Medium. Requires reliable invalid/expired detection and reconnect guidance. | Higher support burden around reconnect events. | May avoid refresh token persistence, but access-token storage policy still needed. | Must delete local tokens and settings according to final cleanup policy. | Must clearly state no automatic refresh and expected reconnect behavior. | Needs controlled OAuth flow, expiry/invalid-token paths, and reconnect guidance QA. |
| Option B: Refresh-capable lifecycle: store refresh token, refresh before/after expiry, show reconnect only on refresh failure | Stronger public UX if implemented and verified fully. | Higher stored-secret impact; requires stronger storage, support, and cleanup posture. | Best user experience when refresh succeeds. | High. Requires refresh request flow, retry/failure categories, and reconnect fallback. | Lower routine support, but higher complexity for failure diagnosis. | Requires explicit refresh-token storage policy and non-redisplay boundaries. | Must delete/revoke/clean refresh-capable credential material according to policy. | Must disclose refresh behavior, storage, revocation, and cleanup clearly. | Needs controlled token exchange, refresh success/failure, expiry, reconnect, disconnect, revoke, and uninstall QA. |
| Option C: Conservative release hold: keep OAuth source implemented but do not claim public OAuth readiness until lifecycle is implemented and verified | Safest if lifecycle/storage decisions are not ready. | Avoids premature public OAuth claims and incomplete credential handling. | No public-ready OAuth UX claim. | Low immediate implementation cost. | Support can clearly say public OAuth readiness remains blocked. | Defers storage decisions but does not solve them. | Cleanup remains unresolved until storage model is chosen. | Must keep release documentation in Hold posture. | No external OAuth QA now; later full lifecycle QA still required. |

## Recommended Decision

Recommended decision for Step 200:

```text
OAuth production readiness status: Not release-ready yet
Token lifecycle decision status: Needs implementation plan
Recommended direction: Keep public release Hold until token lifecycle, reconnect/disconnect, revoke, storage, and uninstall cleanup are planned together
```

Recommended implementation direction for future planning:

```text
Refresh-capable lifecycle for public release should be considered the preferred target only if storage, reconnect, disconnect, revoke, uninstall cleanup, support-safe labels, and controlled QA are planned together before release readiness.
```

Rationale:

- A public OAuth release claim depends on more than a configured client source.
- The matured client configuration source strategy does not settle token
  exchange execution, refresh capability, reconnect/disconnect UX,
  provider-side revoke, token storage posture, or uninstall cleanup.
- A minimal no-refresh lifecycle may reduce storage risk but creates UX and
  support burden that must be explicitly accepted.
- A refresh-capable lifecycle has better UX but increases storage and cleanup
  responsibility.
- A conservative Hold remains appropriate until the lifecycle/storage/uninstall
  plan is explicit.

## Manual Google Access Token Fallback Relationship

| Candidate posture | Current classification | Relationship to OAuth readiness |
|---|---|---|
| `remove_before_public_release` | Possible future direction | Reduces public credential handling risk but requires OAuth lifecycle to be release-ready or release remains blocked. |
| `restrict_to_development_or_debug` | Possible future direction | Preserves controlled verification while avoiding normal public UI exposure. Needs access boundary and wording. |
| `keep_but_not_recommended` | High-risk option | Would require explicit risk acceptance, storage disclosure, support boundaries, and final wording. |
| `needs_policy_decision` | Current status | Manual token fallback remains a public-release blocker linked to OAuth lifecycle and storage decisions. |

Current recommendation:

```text
manual_token_fallback_status_category: public_release_unresolved
```

Manual Google Access Token fallback should remain a public-release blocker until
it is removed, restricted to development/debug use, or explicitly accepted with
release-safe storage, wording, and support boundaries.

## Credential Storage / OpenAI API Key Storage Relationship

| Storage area | Relationship to token lifecycle decision | Current status |
|---|---|---|
| OAuth access token storage | Needed for any OAuth-connected GA4 request flow. | `not_public_release_finalized` |
| OAuth refresh token storage | Required if refresh-capable lifecycle is selected. | `not_finalized` |
| OpenAI API key storage | Should align with broader credential storage posture. | `needs_policy_decision` |
| Settings UI value-hidden non-redisplay | Matured posture to preserve. | `preserve` |
| Option storage disclosure | Must match final storage model and lifecycle behavior. | `needs_final_wording_after_policy` |
| Encryption / ad hoc encryption non-adoption | Step 182 rejected ad hoc encryption as a narrow patch. | `avoid_ad_hoc_encryption_decision` |
| Uninstall cleanup | Must match the final token/key storage model. | `release_blocker_remaining` |
| Privacy/readme wording | Must describe actual external-service and storage posture. | `pre_release_followup_after_p0` |

Storage decisions should not be made independently from token lifecycle. If
refresh-capable OAuth is selected, refresh-token storage and uninstall cleanup
become central release-readiness requirements.

## Support/debug Evidence Boundary

Potential safe status/category labels for future UI, support, and QA:

```text
oauth_flow_status_category: not_executed
oauth_flow_status_category: needs_controlled_qa
token_lifecycle_status_category: not_finalized
token_refresh_status_category: not_implemented
token_disconnect_status_category: not_finalized
token_revoke_status_category: not_finalized
manual_token_fallback_status_category: public_release_unresolved
```

These labels are examples of status/category-level evidence only. They must not
include raw OAuth values, token values, request bodies, response bodies,
provider details, option values, screenshots, browser Network evidence, or
analytics data.

## Forbidden Evidence Boundary

This Step 200 checkpoint does not record, display, infer, request, or rely on:

- OAuth client ID values,
- OAuth client secret values,
- OAuth client value fragments,
- OAuth client value prefixes or suffixes,
- masked OAuth client values,
- credentials,
- API keys,
- access tokens,
- refresh tokens,
- Authorization headers,
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

## Updated Release Blocker Impact

| Step 199 P0 item | Step 200 update | Updated blocker status | Notes |
|---|---|---|---|
| OAuth production readiness / full OAuth flow | Full flow remains unexecuted and not public-release finalized. | `release_blocker_remaining` | Needs implementation/lifecycle plan and later controlled QA. |
| Token lifecycle | Expiry, refresh, reconnect, disconnect, revoke, and failure handling remain undecided. | `release_blocker_remaining` | Needs dedicated implementation plan before code changes. |
| Manual token fallback | Remains linked to OAuth readiness and storage posture. | `release_blocker_remaining_linked_followup` | Needs remove/restrict/accept policy. |
| Credential storage strategy | Remains linked to access/refresh token and OpenAI key storage posture. | `release_blocker_remaining_linked_followup` | Needs policy before public release readiness. |
| Uninstall cleanup | Remains dependent on final storage model. | `release_blocker_remaining_linked_followup` | Needs policy and later implementation plan. |

## Current Decision

Current decision:

```text
Public release status: Hold
OAuth production readiness / full OAuth flow: release_blocker_remaining
Token lifecycle: release_blocker_remaining
Manual token fallback: release_blocker_remaining_linked_followup
Credential storage strategy: release_blocker_remaining_linked_followup
Uninstall cleanup: release_blocker_remaining_linked_followup
```

This is a decision checkpoint, not a release-readiness decision.

## Acceptance Criteria

| Criterion | Status | Notes |
|---|---|---|
| Docs-only decision checkpoint file added | Pass | This file records Step 200. |
| Production code / readme / tools / JS / CSS unchanged | Pass | Step 200 adds this docs file only. |
| Full OAuth flow readiness boundary organized | Pass | Boundary table records current classification for each flow element. |
| Token lifecycle decision areas organized | Pass | Lifecycle table records expiry, refresh, reconnect, disconnect, revoke, GA4 behavior, support labels, and uninstall relationship. |
| Lifecycle posture options compared | Pass | Options A, B, and C are compared across release, security/privacy, UX, implementation, support, storage, uninstall, wording, and testing. |
| Manual token fallback / credential storage / uninstall cleanup dependencies organized | Pass | Dependency sections and updated blocker table connect these P0 items to lifecycle decisions. |
| Forbidden evidence not recorded | Pass | This checkpoint records no values, screenshots, Network evidence, option values, request/response bodies, payloads, or generated content. |
| WordPress.org release remains `Hold` | Pass | Release status is unchanged. |
| Next recommended step is clear | Pass | Step 201 is recommended below. |

## Not Executed

Not executed in Step 200:

- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- browser admin smoke,
- screenshots,
- browser Network evidence collection,
- database dump,
- option value output.

## Recommended Next Step

Recommended next step:

```text
Step 201: OAuth token lifecycle implementation plan
```

Step 201 should be docs-only / planning-only. It should plan refresh, expiry,
reconnect, disconnect, revoke, local token deletion, provider-side revoke,
admin notices, support-safe labels, storage implications, uninstall cleanup
relationship, and controlled QA boundaries before any production code changes.

Step 201 should not execute OAuth, token endpoint communication, Plugin Check,
browser admin smoke, screenshots, browser Network evidence, GA4 Fetch, OpenAI
Generate, option value output, or database dumps.

## Result Classification

```text
OAuth production readiness and token lifecycle decision checkpoint completed
```
