# Step 115: Credential Implementation Roadmap And Phase Boundary Plan

## Step Summary

This step records the phase-based roadmap and phase boundaries for credential
lifecycle work before any implementation begins.

Step 114 recommended a phase-based credential implementation roadmap so Google
OAuth / token lifecycle, OpenAI API key storage, uninstall credential cleanup,
admin UI wording, `readme.txt` / privacy wording, support/debug evidence, QA,
and Plugin Check sequence are not planned in isolation.

This step does not implement Google OAuth, OpenAI API key storage changes,
uninstall cleanup, Settings save logic changes, credential storage changes,
`uninstall.php`, option deletion, admin UI changes, readme changes, package
rebuilds, Plugin Check reruns, or external API calls.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step114-integrated-credential-architecture-plan.md`
- `docs/maturation/step113-credential-lifecycle-decision-summary-next-implementation-boundary.md`
- `docs/maturation/step112-uninstall-credential-cleanup-policy-decision-checkpoint.md`
- `docs/maturation/step111-openai-api-key-storage-posture-decision-checkpoint.md`
- `docs/maturation/step110-google-oauth-token-lifecycle-strategy-decision-checkpoint.md`
- `docs/maturation/step109-release-readiness-blocker-reprioritization-after-plugin-check-pass.md`
- `docs/maturation/step108-isolated-plugin-check-rerun-clean-package-results.md`
- `docs/maturation/step104-readme-privacy-wording-alignment-implementation-results.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`

## Current Baseline

| Area | Baseline |
|---|---|
| Clean package Plugin Check | Step 108 passed against the clean package target. |
| Package blocker | Resolved for the clean package target. |
| Credential lifecycle blocker | Not resolved. |
| Manual Google Access Token entry | Developer verification only. |
| Current settings-based OpenAI API key storage | Developer verification only. |
| Uninstall credential cleanup | Not implemented; policy not finalized. |
| `uninstall.php` | Not present in the current source repository. |
| Final manual smoke | Should happen after credential posture is resolved or explicitly deferred. |
| Final release decision review | Should happen after credential implementation, wording alignment, controlled verification, clean package rebuild, and Plugin Check rerun. |
| WordPress.org release | `Hold`. |

## Roadmap Goals

The credential implementation roadmap should:

- split implementation into small and reviewable phases,
- keep Google OAuth, OpenAI API key storage, and uninstall cleanup aligned,
- avoid choosing cleanup behavior before credential storage behavior is known,
- avoid wording alignment before credential behavior is stable,
- define changed-file boundaries for each implementation phase,
- define explicit non-goals for each phase,
- preserve the support/debug evidence boundary,
- avoid recording credentials, option values, raw payloads, raw responses, or
  generated report bodies,
- define when controlled verification and manual smoke are allowed,
- define when clean package rebuild is allowed,
- define that Plugin Check reruns happen only in `wp-dev-check` and only at the
  correct phase.

## Proposed Roadmap Phases

| Phase | Name | Summary |
|---:|---|---|
| 1 | Google OAuth / token lifecycle implementation plan docs-only | Plan Google authentication, token lifecycle, storage boundary, safe errors, and QA without code changes. |
| 2 | OpenAI API key storage implementation plan docs-only | Plan OpenAI key storage posture, source precedence, non-redisplay, delete/rotation, and QA without code changes. |
| 3 | Uninstall credential cleanup implementation plan docs-only | Plan cleanup scope and uninstall behavior after credential storage plans are clear. |
| 4 | Google OAuth / token lifecycle selected implementation phase | Implement the selected Google credential phase only after Phase 1 is accepted. |
| 5 | OpenAI API key storage selected implementation phase | Implement the selected OpenAI storage phase only after Phase 2 is accepted and Google storage boundaries are known. |
| 6 | Uninstall credential cleanup selected implementation phase | Implement cleanup only after Google/OpenAI credential storage behavior is known. |
| 7 | Admin UI / readme / support wording alignment after credential changes | Align user-facing and support wording after behavior changes are implemented. |
| 8 | Controlled verification / manual admin smoke | Verify credential behavior and admin flows under controlled rules. |
| 9 | Clean package rebuild and Plugin Check rerun in `wp-dev-check` only | Rebuild clean package and rerun Plugin Check only after implementation and wording are stable. |
| 10 | Final release decision review | Decide release readiness based on completed implementation, wording, QA, and Plugin Check evidence. |

## Phase Boundary Details

### Phase 1: Google OAuth / Token Lifecycle Implementation Plan Docs-only

Purpose:

- Plan Google OAuth and token lifecycle implementation before code changes.
- Define authorization start, callback handling, callback state protection,
  capability checks, token exchange, access token expiration, refresh or
  reconnect strategy, disconnect behavior, provider-side revoke behavior, local
  disconnect versus provider revoke, storage boundary, admin-facing status/error
  messages, support/debug evidence boundaries, QA plan, and implementation
  non-goals.

Expected file boundary:

- New docs under `docs/maturation/`.
- No production PHP, JavaScript, CSS, `readme.txt`, `.distignore`, build script,
  or package changes.

Explicit non-goals:

- No OAuth implementation.
- No token exchange, refresh, revoke, or reconnect implementation.
- No Settings save logic change.
- No credential storage change.
- No external API calls.

QA / verification scope:

- Docs review and repository diff checks only.

External API calls allowed:

- No.

Plugin Check allowed:

- No.

Package rebuild allowed:

- No.

Exit criteria:

- A docs-only Google OAuth / token lifecycle implementation plan exists.
- Changed-file and non-goal boundaries for the future implementation phase are
  explicit.

### Phase 2: OpenAI API Key Storage Implementation Plan Docs-only

Purpose:

- Plan OpenAI API key storage implementation before code changes.
- Define storage location, constant-based configuration option, source
  precedence, UI persistence policy, non-redisplay, delete/rotation behavior,
  missing/invalid/permission-limited key messaging, support/debug boundaries,
  and QA plan.

Expected file boundary:

- New docs under `docs/maturation/`.
- No production PHP, JavaScript, CSS, `readme.txt`, `.distignore`, build script,
  or package changes.

Explicit non-goals:

- No storage implementation.
- No option migration.
- No encryption implementation.
- No constant-based configuration implementation.
- No secret manager integration.
- No Settings save logic change.

QA / verification scope:

- Docs review and repository diff checks only.

External API calls allowed:

- No.

Plugin Check allowed:

- No.

Package rebuild allowed:

- No.

Exit criteria:

- A docs-only OpenAI API key storage implementation plan exists.
- Credential source precedence and future implementation boundaries are clear.

### Phase 3: Uninstall Credential Cleanup Implementation Plan Docs-only

Purpose:

- Plan uninstall cleanup after Google and OpenAI credential storage plans are
  clear.
- Define whether to introduce `uninstall.php`, delete targets, credential-
  bearing cleanup scope, non-sensitive settings policy, multisite expectations,
  activation/deactivation/uninstall responsibility boundaries, support/debug
  evidence boundaries, and wording implications.

Expected file boundary:

- New docs under `docs/maturation/`.
- No `uninstall.php` creation.
- No production PHP, JavaScript, CSS, `readme.txt`, `.distignore`, build script,
  or package changes.

Explicit non-goals:

- No uninstall implementation.
- No option deletion.
- No migration.
- No retention prompt.
- No provider-side revoke implementation.

QA / verification scope:

- Docs review and repository diff checks only.

External API calls allowed:

- No.

Plugin Check allowed:

- No.

Package rebuild allowed:

- No.

Exit criteria:

- A docs-only uninstall credential cleanup implementation plan exists.
- Cleanup targets and boundaries are ready for later implementation.

### Phase 4: Google OAuth / Token Lifecycle Selected Implementation Phase

Purpose:

- Implement the selected Google OAuth / token lifecycle slice from Phase 1.
- Keep the implementation narrowly scoped to the accepted plan.

Expected file boundary:

- Likely production PHP in authentication/settings/admin-related areas.
- Possible admin UI text updates only if included in the accepted Phase 1 plan.
- Docs for implementation results.
- No unrelated GA4 reporting, OpenAI generation, payload, or package changes.

Explicit non-goals:

- No OpenAI API key storage redesign unless explicitly scoped.
- No uninstall cleanup unless explicitly scoped.
- No release package rebuild.
- No Plugin Check rerun.

QA / verification scope:

- PHP syntax checks.
- Focused source review.
- No external API E2E unless a later human-approved verification step explicitly
  allows it.

External API calls allowed:

- No by default. Any real OAuth or GA4 external API verification must be a later
  explicitly approved controlled verification step.

Plugin Check allowed:

- No.

Package rebuild allowed:

- No.

Exit criteria:

- The selected Google credential implementation slice is complete.
- No secrets or raw request/response bodies are recorded.
- Follow-up QA and wording impacts are documented.

### Phase 5: OpenAI API Key Storage Selected Implementation Phase

Purpose:

- Implement the selected OpenAI API key storage slice from Phase 2.
- Align with Google credential source boundaries decided in Phase 4.

Expected file boundary:

- Likely production PHP in settings/admin/OpenAI credential resolution areas.
- Possible admin UI text updates only if included in the accepted Phase 2 plan.
- Docs for implementation results.
- No unrelated GA4 reporting, payload, or package changes.

Explicit non-goals:

- No Google OAuth changes unless explicitly scoped.
- No uninstall cleanup unless explicitly scoped.
- No OpenAI Generate external API calls.
- No Plugin Check rerun.
- No package rebuild.

QA / verification scope:

- PHP syntax checks.
- Focused source review.
- Safe local configuration behavior checks that do not expose option values.
- No external OpenAI call unless a later human-approved verification step
  explicitly allows it.

External API calls allowed:

- No by default.

Plugin Check allowed:

- No.

Package rebuild allowed:

- No.

Exit criteria:

- The selected OpenAI credential storage implementation slice is complete.
- Non-redisplay and redaction expectations remain intact.
- Follow-up QA and wording impacts are documented.

### Phase 6: Uninstall Credential Cleanup Selected Implementation Phase

Purpose:

- Implement the selected uninstall cleanup behavior after Google and OpenAI
  credential storage behavior is known.

Expected file boundary:

- Possible `uninstall.php`, if selected by Phase 3.
- Possible minimal production PHP/docs updates required by the accepted cleanup
  plan.
- Docs for implementation results.

Explicit non-goals:

- No OAuth feature expansion.
- No OpenAI key storage redesign.
- No provider-side revoke unless explicitly scoped.
- No Plugin Check rerun.
- No package rebuild.

QA / verification scope:

- PHP syntax checks.
- Focused source review.
- Safe uninstall/reinstall planning or dry-run checks only if they do not
  expose option values.
- No option value dumps.

External API calls allowed:

- No.

Plugin Check allowed:

- No.

Package rebuild allowed:

- No.

Exit criteria:

- Cleanup behavior is implemented according to the accepted plan.
- Credential-bearing cleanup scope is documented.
- No credential or option values are recorded.

### Phase 7: Admin UI / Readme / Support Wording Alignment After Credential Changes

Purpose:

- Align admin UI wording, `readme.txt` / privacy wording, and support/debug
  guidance after credential behavior changes are implemented.

Expected file boundary:

- Admin UI PHP text where needed.
- `readme.txt` where needed.
- Support/debug docs where needed.
- No credential behavior implementation unless explicitly scoped separately.

Explicit non-goals:

- No new OAuth behavior.
- No new storage behavior.
- No uninstall behavior changes.
- No external API calls.
- No package rebuild or Plugin Check rerun.

QA / verification scope:

- PHP syntax checks if PHP wording changes occur.
- Diff review for wording and evidence-safety rules.
- No secret or option value inspection.

External API calls allowed:

- No.

Plugin Check allowed:

- No.

Package rebuild allowed:

- No.

Exit criteria:

- User-facing and support-facing wording matches implemented credential
  behavior.
- Evidence-safety boundaries are clear.

### Phase 8: Controlled Verification / Manual Admin Smoke

Purpose:

- Verify implemented credential behavior and admin flows under controlled rules.
- Run manual admin smoke only after implementation and wording alignment are
  stable.

Expected file boundary:

- Results docs under `docs/maturation/`.
- No production code changes unless a separate later fix step is opened.

Explicit non-goals:

- No opportunistic fixes inside verification docs.
- No Plugin Check rerun.
- No package rebuild.
- No raw evidence recording.

QA / verification scope:

- Controlled admin smoke.
- Safe status-level verification.
- External API E2E only if explicitly approved in a separate scoped step.

External API calls allowed:

- No by default. If external API E2E is needed, it requires a separate explicit
  approval and must not record secrets, identifiers, analytics values, raw
  responses, request bodies, payload bodies, or generated report bodies.

Plugin Check allowed:

- No.

Package rebuild allowed:

- No.

Exit criteria:

- Verification results are recorded status-level.
- Remaining blockers are classified without sensitive evidence.

### Phase 9: Clean Package Rebuild And Plugin Check Rerun In `wp-dev-check` Only

Purpose:

- Rebuild the clean package after implementation and wording changes are
  complete.
- Rerun Plugin Check against the clean package target in `wp-dev-check` only.

Expected file boundary:

- Release/build artifacts outside the source tree or in approved ignored
  locations according to release tooling policy.
- Results docs under `docs/maturation/`.
- No production code fixes in the same step.

Explicit non-goals:

- No Plugin Check in `wp-dev`.
- No raw source tree Plugin Check target.
- No production code changes mixed into the rerun results step.
- No external API calls.

QA / verification scope:

- Clean package contents review.
- Plugin Check against clean package target in `wp-dev-check`.
- Diff/status checks.

External API calls allowed:

- No.

Plugin Check allowed:

- Yes, in `wp-dev-check` only.

Package rebuild allowed:

- Yes.

Exit criteria:

- Clean package contents are verified.
- Plugin Check results are recorded status-level.
- New findings are triaged without mixing fixes into the same step.

### Phase 10: Final Release Decision Review

Purpose:

- Decide whether WordPress.org release can proceed or remains on hold after
  credential implementation, wording alignment, controlled verification, clean
  package rebuild, and Plugin Check rerun.

Expected file boundary:

- Release decision docs under `docs/maturation/`.
- No production code changes.

Explicit non-goals:

- No implementation fixes.
- No external API calls unless a separate verification step is opened first.
- No package rebuild unless Phase 9 needs to be repeated separately.

QA / verification scope:

- Review accumulated evidence.
- Confirm unresolved blockers.
- Confirm release posture.

External API calls allowed:

- No.

Plugin Check allowed:

- No by default. If needed, repeat Phase 9 as a separate step.

Package rebuild allowed:

- No by default. If needed, repeat Phase 9 as a separate step.

Exit criteria:

- Release status is explicitly recorded as proceed, hold, or blocked with
  reasons.
- Any remaining blockers have next-step recommendations.

## Recommended Implementation Order

Recommended order:

```text
1. Google OAuth / token lifecycle implementation plan docs-only
2. OpenAI API key storage implementation plan docs-only
3. Uninstall credential cleanup implementation plan docs-only
4. Google OAuth / token lifecycle implementation
5. OpenAI API key storage implementation
6. Uninstall credential cleanup implementation
7. UI/readme/support wording alignment
8. controlled verification / manual smoke
9. clean package rebuild + Plugin Check in wp-dev-check only
10. final release decision review
```

Rationale:

- Google OAuth is the largest public release credential blocker.
- OpenAI storage must align with Google credential source handling, Settings UI,
  and support wording.
- Uninstall cleanup is safer after the final credential storage model is known.
- Wording alignment should follow implemented credential behavior to reduce
  rework.
- Final verification, Plugin Check, package review, and release decision should
  come after implementation and wording alignment.

## Immediate Next Phase

Immediate next step:

```text
Step 116: Google OAuth / token lifecycle implementation plan
```

Step 116 should be docs-only and should not implement OAuth.

Step 116 should plan at least:

- authorization start,
- callback handling,
- callback state protection,
- capability checks,
- token exchange,
- access token expiration,
- refresh or reconnect strategy,
- disconnect behavior,
- provider-side revoke behavior,
- local disconnect versus provider revoke,
- storage boundary,
- admin-facing status / error messages,
- support/debug evidence boundaries,
- QA plan,
- non-goals and implementation boundary.

## Support / Debug Evidence Boundary

All roadmap phases must preserve the support/debug evidence boundary:

- Do not record credentials, API keys, access tokens, or Authorization headers.
- Do not record plugin settings option values.
- Do not record request bodies.
- Do not record raw responses.
- Do not record AI payload JSON.
- Do not record generated report bodies.
- Prefer status-level labels, redacted saved-state, error categories,
  generation allowed/blocked state, and safe UI wording.
- Avoid screenshots and browser Network tab data by default.
- If external API E2E is later approved, result docs must not record secrets,
  identifiers, analytics values, raw responses, request bodies, payload bodies,
  or generated report bodies.

## Recommended Next Step

Recommended next step:

```text
Step 116: Google OAuth / token lifecycle implementation plan
```

Step 116 should also be docs-only. It should create a Google OAuth
implementation plan with no code changes.

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
- change OpenAI API key storage,
- create `uninstall.php`,
- implement option deletion,
- change Settings save logic,
- change credential storage,
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

This document records only status-level roadmap and phase boundary planning.

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
- only this Step 115 documentation file is added.
