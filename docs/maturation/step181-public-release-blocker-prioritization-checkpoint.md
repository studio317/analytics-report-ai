# Step 181: Public Release Blocker Prioritization Checkpoint

## Step Purpose

Step 181 is a docs-only and planning-only checkpoint for prioritizing the
remaining public-release risks from Step 180.

The purpose is to classify remaining items into release blockers,
pre-release follow-up, post-release defer, and already matured or verified
tracks. This step focuses on ordering the next release-readiness decisions. It
does not implement, verify, or execute external-service behavior.

This step does not change production code, `readme.txt`, tools, build scripts,
JavaScript, CSS, admin behavior, credential storage, OAuth lifecycle behavior,
GA4 behavior, OpenAI behavior, payload handling, transient handling, release
packaging, or generated report persistence.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step180-mvp-maturation-remaining-risk-checkpoint.md`
- `docs/maturation/step179-support-debug-wording-maturation-checkpoint.md`
- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`
- `docs/maturation/step108-isolated-plugin-check-rerun-clean-package-results.md`
- `docs/maturation/step104-readme-privacy-wording-alignment-implementation-results.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step96-generated-report-handling-policy-finalization.md`
- `docs/maturation/step95-payload-preview-json-visibility-final-decision.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`

## Evidence Boundary

This checkpoint records only status-level and category-level release blocker
classification.

It does not display, inspect, or record:

- credentials,
- API keys,
- access tokens,
- refresh tokens,
- Authorization headers,
- OAuth client ID values,
- OAuth client secret values,
- plugin settings option values,
- OAuth token option values,
- serialized option values,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- page path/source/city values,
- request bodies,
- raw GA4 responses,
- raw OpenAI responses,
- AI payload JSON,
- generated report bodies,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces,
- database rows,
- database dumps.

## Classification Policy

Step 181 uses these classifications:

| Classification | Meaning |
|---|---|
| `release_blocker` | Must be decided, implemented, or verified before public release. This includes credentials, tokens, OAuth, uninstall cleanup, and public storage posture. |
| `pre_release_followup` | Should be rerun, rechecked, or finalized close to release after blockers are resolved, such as final Plugin Check, final admin smoke, final package review, and final wording review. |
| `post_release_defer` | Useful improvement, but not required for an initial public release if release blockers and follow-up checks are completed safely. |
| `already_matured_or_verified` | Already matured or sufficiently verified for the current MVP maturation scope. Preserve behavior and evidence boundaries. |

Priority values:

| Priority | Meaning |
|---|---|
| `P0` | Highest-priority public-release blocker. |
| `P1` | Required before release readiness, usually after P0 strategy decisions. |
| `P2` | Release support or documentation polish that should be planned before submission. |
| `P3` | Lower-risk cleanup or consolidation. |
| `Defer` | Post-release or future-track candidate. |

## Prioritization Table

| Item | Classification | Current status | Release impact | Recommended next action | Priority | Suggested next step |
|---|---|---|---|---|---|---|
| OAuth production readiness / app verification / consent screen readiness | `release_blocker` | `needs_followup` | `release_blocker` | Decide the public OAuth posture, provider configuration expectations, app verification boundary, and safe evidence rules. | P0 | Step 182 |
| Token exchange / refresh / revoke / reconnect lifecycle | `release_blocker` | `needs_followup` | `release_blocker` | Define the lifecycle model before public release, including whether refresh/revoke/reconnect are implemented, deferred, or explicitly unsupported. | P0 | Step 182 |
| Credential storage strategy for public release | `release_blocker` | `hold_for_public_release` | `release_blocker` | Decide whether WordPress option storage, constant-based configuration, separation, or another model is acceptable for public release. | P0 | Step 182 |
| Token encryption / secret handling strategy | `release_blocker` | `needs_followup` | `release_blocker` | Decide the secret-handling posture as part of the credential strategy, without ad hoc implementation. | P0 | Step 182 |
| Manual Google Access Token fallback public-release posture | `release_blocker` | `hold_for_public_release` | `release_blocker` | Decide whether to remove, hide, restrict, or keep manual token entry as developer-verification-only before release readiness. | P0 | Step 182 |
| OpenAI API key storage posture | `release_blocker` | `needs_followup` | `release_readiness_risk` | Decide the public storage/configuration posture and whether current Settings storage remains acceptable. | P0 | Step 182 |
| Uninstall cleanup policy for credential-bearing data | `release_blocker` | `needs_followup` | `release_blocker` | Define uninstall cleanup boundaries for credential-bearing options and related release wording. | P0 | Step 182 |
| Privacy policy / external services wording final release alignment | `pre_release_followup` | `needs_followup` | `documentation_risk` | Recheck wording after OAuth and credential decisions are finalized. | P1 | Step 183 or later |
| Readme final release review | `pre_release_followup` | `needs_followup` | `documentation_risk` | Recheck `readme.txt`, Stable tag readiness, support wording, and external service disclosure after P0 decisions. | P1 | Step 183 or later |
| Final isolated Plugin Check timing | `pre_release_followup` | `needs_followup` | `release_readiness_risk` | Rerun against a clean release target only after release-affecting changes are complete. | P1 | Later final release QA step |
| Release package process stability | `pre_release_followup` | `verified_for_current_scope` | `release_readiness_risk` | Rebuild and inspect a clean package near release timing. | P1 | Later final package QA step |
| Final admin smoke timing | `pre_release_followup` | `needs_followup` | `qa_risk` | Run safe admin smoke after P0 strategy and any resulting wording or code changes. | P1 | Later final admin smoke step |
| Production external API behavior risk | `pre_release_followup` | `needs_followup` | `operational_risk` | Re-run only in an explicitly authorized controlled external-action QA step if needed. | P1 | Later controlled external QA step |
| WordPress.org assets / screenshots / FAQ / support instructions | `pre_release_followup` | `needs_followup` | `documentation_risk` | Prepare final public-facing assets and support instructions without forbidden evidence. | P2 | Later release documentation step |
| Browser automation limitations / Playwright blocked status | `post_release_defer` | `blocked_by_environment` | `qa_risk` | Keep human-controlled smoke as the release fallback; revisit automation when environment support is stable. | Defer | Future automation track |
| Data minimization / support evidence boundary maintenance | `already_matured_or_verified` | `verified_for_current_scope` | `documentation_risk` | Preserve the current category/status-level evidence boundary. | P3 | Preserve in future steps |
| Credential source UI wording track | `already_matured_or_verified` | `matured_for_mvp_scope` | `none_for_mvp` | Preserve OAuth-first wording, manual fallback labeling, and value-hidden posture. | P3 | Preserve in future steps |
| Support/debug wording track | `already_matured_or_verified` | `matured_for_mvp_scope` | `none_for_mvp` | Preserve support-safe hints and forbidden-evidence avoidance. | P3 | Preserve in future steps |
| Raw AI payload JSON removal from normal admin UI | `already_matured_or_verified` | `verified_for_current_scope` | `none_for_mvp` | Preserve structured preview posture and do not reintroduce raw JSON support evidence. | P3 | Preserve in future steps |
| Generated report non-persistence posture | `already_matured_or_verified` | `verified_for_current_scope` | `none_for_mvp` | Preserve non-persistence and support boundary. | P3 | Preserve in future steps |
| GA4 no-data / partial-data / generation-blocked handling | `already_matured_or_verified` | `verified_for_current_scope` | `qa_risk` | Preserve status metadata and server-side generation gate. | P3 | Preserve in future steps |
| Controlled GA4 Fetch / OpenAI Generate E2E happy path | `already_matured_or_verified` | `verified_for_current_scope` | `qa_risk` | Preserve as known pass; rerun only if a future step explicitly authorizes external-service QA. | P3 | Preserve in future steps |

## Recommended P0 / P1 Order

Recommended `release_blocker` order:

1. OAuth production readiness / app verification / consent screen readiness.
2. Token exchange / refresh / revoke / reconnect lifecycle.
3. Manual Google Access Token fallback public-release posture.
4. Credential storage strategy for public release.
5. Token encryption / secret handling strategy.
6. OpenAI API key storage posture.
7. Uninstall cleanup policy for credential-bearing data.

Rationale:

- OAuth and token lifecycle decisions determine whether manual token fallback
  remains, is restricted, or is removed for public release.
- Credential storage and secret handling depend on which credentials are
  supported for public release.
- OpenAI API key storage can be decided alongside the broader credential
  storage posture.
- Uninstall cleanup should follow the final storage model so cleanup targets
  match the release design.

Recommended `pre_release_followup` order after P0 decisions:

1. Final privacy/external-services/readme wording review.
2. Final admin smoke.
3. Final controlled external API QA, only if explicitly authorized and needed.
4. Clean release package rebuild and review.
5. Final isolated Plugin Check against the clean package target.
6. WordPress.org assets / FAQ / support instructions.

## Explicit Non-blockers For Current MVP Scope

The following are not treated as current release blockers because they are
already matured or verified for the current MVP maturation scope:

- credential source UI wording track,
- support/debug wording track,
- raw AI payload JSON removal from normal admin UI,
- generated report non-persistence posture,
- data minimization / support evidence boundaries,
- controlled GA4 Fetch / OpenAI Generate E2E happy path,
- GA4 no-data / partial-data / generation-blocked handling.

These items should still be preserved during later changes. A future regression
or release-scope change can reopen any of them, but Step 181 does not classify
them as active blockers.

## Release Status Decision

Decision:

```text
WordPress.org release status: Hold
```

Rationale:

- Active `release_blocker` categories remain, especially OAuth production
  readiness, token lifecycle, credential storage, manual token fallback posture,
  OpenAI API key storage posture, and uninstall cleanup.
- Step 181 is a prioritization checkpoint, not a release-readiness decision.
- Final release readiness still needs independent review after blocker
  decisions and follow-up checks.

## Recommended Next Step

Recommended next step:

```text
Step 182: OAuth and credential public-release strategy checkpoint
```

Recommended Step 182 scope:

- docs-only,
- planning-only,
- treat OAuth production readiness, credential storage, token lifecycle,
  manual token fallback, OpenAI API key storage, and uninstall cleanup as one
  public-release strategy track,
- keep WordPress.org release status as `Hold`,
- do not execute OAuth Connect, token endpoint communication, GA4 Fetch,
  OpenAI Generate, Plugin Check, browser admin smoke, screenshots, or browser
  Network evidence collection.

## Acceptance Criteria

Step 181 is complete when:

- this docs-only checkpoint file is added,
- production code, `readme.txt`, tools, build scripts, JavaScript, and CSS have
  no additional Step 181 changes,
- Step 180 remaining risks are classified as `release_blocker`,
  `pre_release_followup`, `post_release_defer`, or
  `already_matured_or_verified`,
- P0 / P1 order is explicit,
- WordPress.org release remains `Hold`,
- forbidden-evidence non-recording policy remains explicit,
- the recommended next step is explicit.

## Not Executed

Step 181 did not execute:

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
- option value inspection.

## Commands Executed

Safe docs-only commands for this checkpoint:

```bash
git status --short --untracked-files=all
test -f docs/maturation/step181-public-release-blocker-prioritization-checkpoint.md && echo "step181_doc_exists"
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```

## Result Classification

Result: `Public release blocker prioritization checkpoint completed`

WordPress.org release remains `Hold`.
