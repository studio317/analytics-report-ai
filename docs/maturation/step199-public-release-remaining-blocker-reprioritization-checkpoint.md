# Step 199: Public Release Remaining Blocker Re-prioritization Checkpoint

## Step Purpose

Step 199 is a docs-only and planning-only checkpoint for re-prioritizing the
remaining public-release blockers after the OAuth client configuration hybrid
source track reached matured status for the current MVP maturation scope in
Step 198.

This step updates the Step 181 / Step 182 release blocker view to reflect that
the OAuth client configuration source strategy itself is no longer an active
remaining release blocker, while full OAuth flow readiness, token lifecycle,
credential storage, manual fallback posture, uninstall cleanup, and final
release verification remain unresolved.

This step does not change production code, `readme.txt`, tools, build scripts,
JavaScript, CSS, OAuth runtime behavior, OAuth resolver behavior, Settings
save/delete behavior, credential storage behavior, token storage behavior, GA4
behavior, or OpenAI behavior.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step198-oauth-client-configuration-hybrid-source-final-maturation-checkpoint.md`
- `docs/maturation/step181-public-release-blocker-prioritization-checkpoint.md`
- `docs/maturation/step182-oauth-credential-public-release-strategy-checkpoint.md`
- `docs/maturation/step183-oauth-public-release-readiness-implementation-plan.md`
- `docs/maturation/step184-oauth-source-inventory-current-boundary-review.md`
- `docs/maturation/step185-oauth-app-ownership-provider-configuration-decision-checkpoint.md`
- `docs/maturation/step179-support-debug-wording-maturation-checkpoint.md`
- `docs/maturation/step180-mvp-maturation-remaining-risk-checkpoint.md`

Additional OAuth client configuration source track context:

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

## Updated Matured / Completed Track Summary

| Track | Updated status | Evidence | Release impact |
|---|---|---|---|
| OAuth client configuration hybrid source track | `matured_for_current_mvp_scope` | Step 198 | Remove this specific source-strategy item from active release blockers. Preserve behavior and evidence boundaries. |
| OAuth client placeholder/description wording follow-up | `resolved_at_source_level` | Step 197 / Step 198 | No remaining source-level wording blocker for this follow-up. |
| Support/debug wording track | `matured_for_current_mvp_scope` | Step 179 | Preserve status/category-only support evidence and forbidden-evidence avoidance. |
| Credential source UI wording track | `matured_for_current_mvp_scope` | Step 171 | Preserve OAuth-first wording, manual fallback labeling, safe status labels, and value-hidden posture. |
| Raw AI payload JSON normal UI removal | `implemented_or_matured_for_current_scope` | Step 95 / Step 102 context from Step 180 | Preserve structured preview posture and do not reintroduce raw JSON as normal support evidence. |
| Generated report non-persistence wording | `matured_for_current_scope` | Step 96 / Step 104 context from Step 180 | Preserve non-persistence and avoid support requests for generated report bodies. |
| GA4 no-data handling | `implemented_or_matured_for_current_scope` | Step 91 context from Step 180 | Preserve no-data / zero / partial / comparison warning and generation-gate behavior. |
| Clean release package Plugin Check previous isolated pass | `historical_pass_final_rerun_required` | Step 108 context from Step 181 | Treat as useful historical evidence only; final isolated rerun remains required before release readiness. |

## Updated Release Blocker Table

| Area | Previous classification | Updated classification | Reason | Current status | Recommended next step | Priority |
|---|---|---|---|---|---|---|
| OAuth client configuration source strategy | `release_blocker` / `needs implementation strategy` | `matured_for_current_mvp_scope` | Step 186-198 selected, implemented, verified, human-smoked, clarified, and closed the hybrid source track. | `matured` | Preserve source/category labels and value-hidden posture. | Completed |
| OAuth production readiness / full OAuth flow | `release_blocker` | `release_blocker_remaining` | OAuth execution, Google navigation, authorization approval, token endpoint communication, and full OAuth flow readiness remain out of scope for the matured source strategy track. | `needs_decision_checkpoint` | Step 200 | P0-1 |
| Token lifecycle | `release_blocker` | `release_blocker_remaining` | Expiry, refresh, reconnect, disconnect, revoke, and related UX/policy remain unresolved for public release. | `needs_policy_decision` | Step 200 | P0-2 |
| Manual Google Access Token fallback | `release_blocker` | `release_blocker_remaining` | Manual token fallback remains an MVP maturation fallback; public-release treatment still needs remove/restrict/accept decision. | `hold_for_public_release` | Step 200 or follow-up strategy step | P0-3 |
| Credential storage strategy | `release_blocker` | `release_blocker_remaining` | OAuth token and credential storage public-release posture remains unresolved beyond the matured OAuth client source configuration track. | `needs_policy_decision` | Step 200 or storage strategy step | P0-4 |
| OpenAI API key storage strategy | `release_blocker` | `release_blocker_remaining_or_needs_policy_decision` | OpenAI key Settings storage posture still needs alignment with broader credential storage policy. | `needs_policy_decision` | Storage strategy follow-up | P0-4 |
| Secret handling / encryption decision | `release_blocker` | `release_blocker_remaining` | Step 182 rejected ad hoc encryption; final secret-handling posture depends on storage and token lifecycle decisions. | `defer_until_storage_strategy_complete` | Storage / lifecycle follow-up | P0-4 |
| Uninstall cleanup | `release_blocker` | `release_blocker_remaining` | Credential-bearing data cleanup policy and implementation are still not release-finalized. | `needs_source_inventory_or_policy` | Dedicated uninstall cleanup checkpoint | P0-5 |
| Privacy / readme final wording | `pre_release_followup` | `pre_release_followup` | Current wording is matured for current scope but must be rechecked after OAuth, token, storage, fallback, and uninstall decisions. | `needs_final_alignment_after_p0` | Later wording review | P1-1 |
| Controlled external QA | `pre_release_followup` | `pre_release_followup` | External-service E2E evidence is historical/current-scope only; final controlled QA should be explicitly authorized if still needed. | `defer_until_p0_decisions` | Later controlled QA plan | P1-2 |
| Final admin smoke | `pre_release_followup` | `pre_release_followup` | Final smoke should happen after P0 decisions and any related UI/wording changes. | `needs_final_plan` | Later admin smoke plan | P1-3 |
| Release package review | `pre_release_followup` | `pre_release_followup` | Clean package review remains needed near final release timing. | `needs_final_review` | Later package review | P1-4 |
| Final isolated Plugin Check | `pre_release_followup` | `pre_release_followup` | Previous clean-package pass is historical; final rerun should occur after release-affecting changes are complete. | `needs_final_rerun` | Later final Plugin Check | P1-5 |
| WordPress.org assets / FAQ / support wording | `pre_release_followup` | `pre_release_followup` | Public-facing support materials still need final release-safe wording after P0 decisions. | `needs_final_docs` | Later release documentation step | P1 |
| Playwright/browser automation limitation | `post_release_defer` | `post_release_defer_or_non_blocking_known_limitation` | Human-controlled smoke remains the fallback when automation is blocked by environment limitations. | `known_limitation` | Future tooling track | P2-1 |

## Updated Priority Order

### P0: Release Blockers

| Priority | Item | Current status | Why next |
|---|---|---|---|
| P0-1 | OAuth production readiness / full OAuth flow boundary | `release_blocker_remaining` | Public release cannot be considered while full OAuth flow readiness, Google navigation boundary, and token endpoint behavior remain undecided. |
| P0-2 | Token lifecycle: expiry / refresh / reconnect / disconnect / revoke policy | `release_blocker_remaining` | OAuth storage and UX cannot be release-final without lifecycle policy. |
| P0-3 | Manual Google Access Token fallback public-release treatment | `release_blocker_remaining` | Manual token entry remains a public-release risk until removed, restricted, or deliberately accepted. |
| P0-4 | Credential storage strategy for OAuth tokens and OpenAI API key | `release_blocker_remaining` | Storage posture affects privacy wording, support guidance, uninstall cleanup, and release acceptance. |
| P0-5 | Uninstall cleanup | `release_blocker_remaining` | Cleanup policy must match the final credential/token storage model before release readiness. |

### P1: Pre-release Follow-up

| Priority | Item | Current status | Timing |
|---|---|---|---|
| P1-1 | Privacy/readme final wording alignment | `pre_release_followup` | After P0 decisions and any resulting implementation changes. |
| P1-2 | Controlled external QA plan | `pre_release_followup` | Only if explicitly authorized after P0 decisions. |
| P1-3 | Final admin smoke plan | `pre_release_followup` | After release-affecting UI/wording changes. |
| P1-4 | Release package review | `pre_release_followup` | Near final package timing. |
| P1-5 | Final isolated Plugin Check | `pre_release_followup` | Against the final clean package or clean target after release-affecting changes are complete. |

### P2: Defer / Known Limitation

| Priority | Item | Current status | Timing |
|---|---|---|---|
| P2-1 | Playwright/browser automation limitation | `post_release_defer_or_non_blocking_known_limitation` | Keep human-controlled smoke as fallback; revisit automation separately. |

## What Changed After Step 198

Step 198 changes release blocker prioritization as follows:

- OAuth client configuration source strategy can be removed from the active
  remaining release blocker list.
- Settings fallback source labels, value-hidden posture, constants precedence,
  conservative conflict handling, no mixed-source client pair behavior, and
  delete semantics can be treated as matured evidence for the current MVP
  maturation scope.
- The placeholder/description wording follow-up is treated as source-level
  resolved.
- Full OAuth execution, Google navigation, authorization approval, token
  endpoint communication, token exchange, token storage, refresh/reconnect,
  disconnect, revoke, and full OAuth flow readiness remain unresolved for
  public release.
- The OAuth client configuration source maturation does not settle broader
  credential storage, OpenAI API key storage, manual token fallback,
  uninstall cleanup, privacy/readme final wording, package review, or final
  Plugin Check.

## Forbidden Evidence Boundary

This Step 199 checkpoint does not record, display, infer, request, or rely on:

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

## Current Decision

Current decision:

```text
Public release status: Hold
OAuth client configuration source strategy: Matured for current MVP maturation scope
Remaining release blocker status: Re-prioritized after OAuth client configuration source maturation
```

This is a blocker re-prioritization checkpoint. It is not a public release
readiness decision.

## Acceptance Criteria

| Criterion | Status | Notes |
|---|---|---|
| Docs-only blocker re-prioritization checkpoint file added | Pass | This file records Step 199. |
| Production code / readme / tools / JS / CSS unchanged | Pass | Step 199 adds this docs file only. |
| Step 198 matured status reflected in release blocker reclassification | Pass | OAuth client configuration source strategy moves to `matured_for_current_mvp_scope`. |
| Remaining release blockers organized by P0 / P1 / P2 | Pass | Priority order is recorded above. |
| Forbidden evidence not recorded | Pass | This checkpoint records no values, screenshots, Network evidence, option values, request/response bodies, payloads, or generated content. |
| WordPress.org release remains `Hold` | Pass | Release status is unchanged. |
| Next recommended step is clear | Pass | Step 200 is recommended below. |

## Not Executed

Not executed in Step 199:

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
Step 200: OAuth production readiness and token lifecycle decision checkpoint
```

Step 200 should be docs-only / planning-only. It should not execute OAuth,
Google navigation, token endpoint communication, Plugin Check, browser admin
smoke, screenshots, browser Network evidence, GA4 Fetch, OpenAI Generate,
option value output, or database dumps.

Recommended Step 200 scope:

- organize full OAuth flow readiness without executing OAuth,
- decide token lifecycle boundaries for expiry, refresh, reconnect, disconnect,
  and revoke,
- keep manual token fallback, credential storage, OpenAI API key storage, and
  uninstall cleanup linked to the token lifecycle decision,
- keep WordPress.org release status as `Hold`.

## Result Classification

```text
Public release remaining blocker re-prioritization checkpoint completed
```
