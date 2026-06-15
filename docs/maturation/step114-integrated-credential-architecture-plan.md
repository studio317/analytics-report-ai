# Step 114: Integrated Credential Architecture Plan

## Step Summary

This step records an integrated credential architecture plan before any
credential lifecycle implementation begins.

Step 113 recommended an integrated plan because Google OAuth / token lifecycle,
OpenAI API key storage, uninstall credential cleanup, admin UI wording,
`readme.txt` / privacy wording, support/debug evidence, QA, and Plugin Check
sequence are connected release-readiness concerns.

This step does not implement Google OAuth, OpenAI API key storage changes,
uninstall cleanup, Settings save logic changes, credential storage changes,
`uninstall.php`, option deletion, admin UI changes, readme changes, package
rebuilds, Plugin Check reruns, or external API calls.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step113-credential-lifecycle-decision-summary-next-implementation-boundary.md`
- `docs/maturation/step112-uninstall-credential-cleanup-policy-decision-checkpoint.md`
- `docs/maturation/step111-openai-api-key-storage-posture-decision-checkpoint.md`
- `docs/maturation/step110-google-oauth-token-lifecycle-strategy-decision-checkpoint.md`
- `docs/maturation/step109-release-readiness-blocker-reprioritization-after-plugin-check-pass.md`
- `docs/maturation/step108-isolated-plugin-check-rerun-clean-package-results.md`
- `docs/maturation/step104-readme-privacy-wording-alignment-implementation-results.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`

## Architecture Goals

The integrated credential architecture should:

- keep developer verification posture and public release posture clearly
  separate,
- treat Google and OpenAI credentials under a consistent credential lifecycle
  policy,
- align storage, redaction, non-redisplay, delete, disconnect, uninstall
  cleanup, and support evidence,
- avoid deciding Google, OpenAI, and uninstall behavior in isolation,
- identify required admin UI wording updates before public release,
- identify required `readme.txt` / privacy wording updates before public
  release,
- define the QA sequence after credential changes,
- define when Plugin Check should be rerun against a clean package target,
- keep WordPress.org release on `Hold` until credential lifecycle blockers are
  resolved.

## Current Posture Baseline

| Area | Current baseline |
|---|---|
| Manual Google Access Token entry | Developer verification only. |
| Current settings-based OpenAI API key storage | Developer verification only. |
| Uninstall credential cleanup | Not implemented; policy not finalized. |
| `uninstall.php` | Not present in the current source repository. |
| Clean package Plugin Check | Step 108 Pass, with errors 0, warnings 0 observed, notices 0 observed. |
| Package contents blocker | Resolved for the clean package target. |
| Credential lifecycle blocker | Not resolved. |
| Final release evidence | Not final while credential posture remains unresolved. |
| WordPress.org release | `Hold`. |

The clean package Plugin Check pass is important package-readiness evidence,
but it does not make the current credential lifecycle public-release ready.

## Integrated Architecture Components

The architecture should cover the following connected components:

- Google OAuth authorization flow.
- Google token lifecycle.
- OpenAI API key storage posture.
- Credential source precedence.
- Credential redaction / non-redisplay.
- Disconnect / delete behavior.
- Uninstall cleanup.
- Admin UI wording.
- `readme.txt` / privacy wording.
- Support/debug evidence boundaries.
- QA / manual smoke sequence.
- Plugin Check / clean package rerun sequence.

## Proposed Public-release Architecture Direction

### Google

The public-release path should not use manual Google Access Token entry as the
default authentication model.

Public release planning should treat OAuth authorization flow as a required
design target unless a later release-scope decision removes Google integration
from public release.

The Google credential design should define:

- authorization start behavior,
- callback handling,
- token expiration handling,
- refresh or reconnect strategy,
- disconnect behavior,
- provider-side revoke behavior,
- the difference between provider-side revoke and local disconnect,
- status-level error messaging for expired, invalid, insufficient-permission,
  or property-access failure states,
- support/debug evidence boundaries.

### OpenAI

The current settings-based OpenAI API key storage model should remain
developer verification only.

Before public release, the OpenAI API key storage posture should be redesigned
or explicitly accepted. Constant-based configuration should remain a candidate
for the architecture.

If UI-based persistence remains available, the architecture should define:

- non-redisplay behavior,
- explicit delete behavior,
- rotation expectations,
- redaction expectations,
- support wording,
- site-owner versus administrator responsibility,
- missing / invalid / permission-limited key messaging.

If multiple credential sources are supported, the architecture should define
credential source precedence before implementation.

### Uninstall

Credential-bearing data must be explicitly covered by public release cleanup
policy before release proceeds.

Uninstall cleanup should be treated as a separate responsibility from
provider-side revoke and OAuth disconnect. Uninstall can remove plugin-owned
local data, but it should not be described as equivalent to revoking access at
an external provider unless provider-side revoke behavior is intentionally
implemented.

Whether to introduce `uninstall.php` and which `delete_option()` targets are
used should be decided in a later implementation plan after the credential
architecture is settled.

## Implementation Sequence Options

### Option A: Create Google OAuth Architecture And Implementation Plan First

Pros:

- Starts the largest Google credential blocker.
- Creates a clearer path toward public GA4 connection UX.
- Can define token lifecycle, reconnect, disconnect, and revoke semantics.

Cons:

- May choose token storage assumptions before OpenAI storage and uninstall
  cleanup are aligned.
- Can force later readme, support, and cleanup revisions.
- Leaves OpenAI key storage and uninstall behavior as parallel unresolved
  blockers.

Rework risk:

- High.

QA impact:

- Requires substantial OAuth, callback, token lifecycle, Settings UI, and
  external API error-path QA after implementation.

Release readiness impact:

- Important, but not sufficient by itself.

Recommendation:

- Not recommended as the immediate next step.

### Option B: Create OpenAI API Key Storage Architecture And Implementation Plan First

Pros:

- Addresses a narrower credential storage blocker.
- Can clarify constant-based configuration and UI persistence options.
- May be simpler than the Google OAuth path.

Cons:

- May choose OpenAI storage precedence before Google token storage and uninstall
  cleanup are aligned.
- Can require later support/readme revisions after Google OAuth architecture is
  chosen.

Rework risk:

- Medium to high.

QA impact:

- Requires Settings UI, missing/invalid key messaging, generation-path, and
  support wording QA after implementation.

Release readiness impact:

- Useful, but incomplete without Google and uninstall architecture.

Recommendation:

- Not recommended as the immediate next step.

### Option C: Create Uninstall Cleanup Architecture And Implementation Plan First

Pros:

- Targets a concrete cleanup blocker.
- Can improve privacy expectations around plugin-owned credential-bearing data.
- Helps define uninstall/reinstall behavior.

Cons:

- Cleanup targets depend on the final Google and OpenAI credential models.
- Local cleanup behavior can differ depending on OAuth tokens, settings-based
  keys, constant-based keys, or mixed sources.
- Provider-side revoke and local uninstall can be confused if planned too
  early.

Rework risk:

- High.

QA impact:

- Requires uninstall/reinstall, local deletion, and possible multisite QA after
  implementation.

Release readiness impact:

- Required before public release, but should follow credential architecture
  decisions.

Recommendation:

- Not recommended as the immediate next step.

### Option D: Implement Google / OpenAI / Uninstall In One Credential Phase

Pros:

- Keeps all credential lifecycle changes synchronized.
- Can align storage, cleanup, wording, support guidance, and QA in one large
  effort.

Cons:

- Scope is too large for a safe MVP maturation implementation step.
- Larger blast radius across Settings, GA4, OpenAI, uninstall, docs, QA, and
  package verification.
- Harder to review and rollback.

Rework risk:

- Medium. Alignment improves, but oversized scope increases implementation
  risk.

QA impact:

- Requires broad end-to-end QA across credential setup, fetch, generate,
  cleanup, wording, packaging, and Plugin Check in one phase.

Release readiness impact:

- Could advance readiness if successful, but risk is high.

Recommendation:

- Not recommended.

### Option E: Create A Phase-based Credential Implementation Roadmap Before Code Changes

Pros:

- Keeps the next step docs-only and low risk.
- Defines implementation order before any code changes.
- Can sequence Google OAuth, OpenAI storage, uninstall cleanup, wording, QA,
  and Plugin Check deliberately.
- Reduces rework without creating one oversized implementation phase.
- Preserves clear public release and developer verification boundaries.

Cons:

- Adds another planning step before implementation.
- Does not itself resolve the credential lifecycle blocker.
- Requires discipline to keep the roadmap actionable.

Rework risk:

- Low.

QA impact:

- Positive. QA can be planned per phase and then consolidated before final
  release review.

Release readiness impact:

- Strong planning impact. It should reduce release-readiness churn before code
  changes begin.

Recommendation:

- Recommended.

## Recommended Decision

Recommended:

```text
Option E - create a phase-based credential implementation roadmap before code changes.
```

Decision:

- Do not start credential-related code changes yet.
- Do not implement Google OAuth, OpenAI key storage changes, or uninstall
  cleanup in this step.
- Create a docs-only roadmap next.
- Use the roadmap to define implementation order, changed file boundaries, QA
  sequence, Plugin Check rerun timing, clean package rebuild timing, and manual
  smoke timing.

Rationale:

- Google OAuth, OpenAI API key storage, and uninstall cleanup are
  interdependent.
- A single combined implementation phase would be too large.
- A phase-based roadmap can preserve alignment while keeping later code changes
  small and reviewable.

## Proposed Phases For The Next Roadmap

The next roadmap should consider these phases:

| Phase | Purpose |
|---|---|
| Phase 1 | Credential architecture implementation roadmap docs-only. |
| Phase 2 | Google OAuth / token lifecycle implementation plan docs-only. |
| Phase 3 | OpenAI API key storage implementation plan docs-only. |
| Phase 4 | Uninstall credential cleanup implementation plan docs-only. |
| Phase 5 | Selected implementation phase 1. |
| Phase 6 | Admin UI / readme / support wording alignment after credential changes. |
| Phase 7 | Manual admin smoke / controlled verification. |
| Phase 8 | Clean package rebuild and Plugin Check rerun in `wp-dev-check` only. |
| Phase 9 | Final release decision review. |

## Support / Debug Evidence Boundary

Credential architecture work must preserve the current support/debug evidence
boundary:

- Do not record credentials, API keys, access tokens, or Authorization headers.
- Do not record plugin settings option values.
- Do not record request bodies.
- Do not record raw responses.
- Do not record AI payload JSON.
- Do not record generated report bodies.
- Prefer status-level labels, redacted saved-state, error categories,
  generation allowed/blocked state, and safe UI wording.
- Avoid screenshots and browser Network tab data by default.
- If a later human-run smoke test uses screenshots locally, do not store
  unredacted screenshots in the repository.

## Recommended Next Step

Recommended next step:

```text
Step 115: Credential implementation roadmap and phase boundary plan
```

Step 115 should also be docs-only. It should decide:

- which implementation phase comes first,
- which files each phase may touch,
- what remains explicitly out of scope,
- when admin UI wording changes happen,
- when `readme.txt` / privacy wording changes happen,
- when controlled verification happens,
- when clean package rebuild happens,
- when Plugin Check reruns in `wp-dev-check` only,
- when final release decision review can begin.

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

This document records only status-level credential architecture planning.

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
- only this Step 114 documentation file is added.
