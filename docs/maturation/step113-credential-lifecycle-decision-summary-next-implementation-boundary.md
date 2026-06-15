# Step 113: Credential Lifecycle Decision Summary And Next Implementation Boundary

## Step Summary

This step consolidates the credential lifecycle decision checkpoints from Step
110, Step 111, and Step 112.

The goal is to summarize the current release posture, confirm the developer
verification boundary, and decide whether the project should move directly into
implementation or add another planning step before implementation begins.

This is a docs-only decision summary. It does not implement Google OAuth,
OpenAI API key storage changes, uninstall cleanup, Settings save logic changes,
credential storage changes, `uninstall.php`, option deletion, admin UI changes,
readme changes, package rebuilds, Plugin Check reruns, or external API calls.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step112-uninstall-credential-cleanup-policy-decision-checkpoint.md`
- `docs/maturation/step111-openai-api-key-storage-posture-decision-checkpoint.md`
- `docs/maturation/step110-google-oauth-token-lifecycle-strategy-decision-checkpoint.md`
- `docs/maturation/step109-release-readiness-blocker-reprioritization-after-plugin-check-pass.md`
- `docs/maturation/step108-isolated-plugin-check-rerun-clean-package-results.md`
- `docs/maturation/step104-readme-privacy-wording-alignment-implementation-results.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`

## Consolidated Decisions

### Step 110: Google OAuth / Token Lifecycle

Decision summary:

- Manual Google Access Token entry remains developer verification only.
- WordPress.org public release remains `Hold`.
- Before public release proceeds, an OAuth authorization flow and token
  lifecycle strategy are required unless a later release-scope decision removes
  Google integration.
- Step 110 did not implement OAuth, token exchange, refresh, revocation,
  reconnect UI, storage changes, or uninstall changes.

### Step 111: OpenAI API Key Storage

Decision summary:

- Current settings-based OpenAI API key storage remains developer verification
  only.
- WordPress.org public release remains `Hold`.
- Before public release proceeds, OpenAI API key storage posture must be
  redesigned or explicitly accepted unless a later release-scope decision
  removes OpenAI integration.
- Step 111 did not implement storage changes, option migration, encryption,
  constant-based configuration, secret manager integration, Settings save
  logic changes, or uninstall changes.

### Step 112: Uninstall Credential Cleanup

Decision summary:

- Uninstall cleanup implementation is deferred for the current developer
  verification posture.
- WordPress.org public release remains `Hold`.
- Before public release proceeds, cleanup policy must align with the final
  Google and OpenAI credential storage posture.
- Credential-bearing data must be explicitly covered by the public release
  cleanup policy.
- Step 112 did not create `uninstall.php`, delete options, migrate options,
  add retention prompts, change Settings save logic, change credential storage,
  or change uninstall behavior.

## Current Overall Posture

Analytics Report AI can continue as an MVP/developer verification project.

However, WordPress.org public release remains blocked by credential lifecycle
decisions. The current Google and OpenAI credential-related implementation
should not be treated as public-release ready.

Clean package Plugin Check passed in Step 108, and the Step 105 package
contents findings are resolved for the clean package target. That result does
not resolve the credential lifecycle blockers.

Current posture:

- MVP/developer verification can continue.
- WordPress.org public release remains `Hold`.
- Manual Google Access Token entry is developer verification only.
- Current settings-based OpenAI API key storage is developer verification only.
- Uninstall credential cleanup is not implemented and not release-finalized.
- Final release evidence should not treat external API E2E or manual smoke as
  release-ready while credential posture remains unsettled.

## Remaining Credential Lifecycle Implementation Candidates

The following areas may become implementation candidates after a consolidated
architecture plan:

- Google OAuth authorization flow.
- Google token lifecycle handling.
- Access token expiration handling.
- Refresh or reconnect strategy.
- Disconnect and provider-side revoke behavior.
- OpenAI API key storage posture redesign.
- Constant-based OpenAI API key configuration, if adopted.
- Settings UI status and non-redisplay behavior for credential sources.
- Credential delete / disconnect behavior.
- Uninstall credential cleanup implementation.
- Related admin UI wording updates.
- Related `readme.txt` / privacy wording updates.
- Related support/debug evidence updates.
- Related QA, smoke checks, and Plugin Check rerun after implementation.

## Implementation Boundary Options

### Option A: Move Directly To Google OAuth Implementation Planning

Pros:

- Starts the largest public-release credential blocker.
- Creates a path toward normal Google connection UX.
- Could unblock GA4 authentication strategy if completed.

Cons:

- Google OAuth storage decisions affect uninstall cleanup and support wording.
- OAuth token storage may need to align with OpenAI key storage posture.
- May force later changes if OpenAI storage or uninstall cleanup decisions
  introduce different assumptions.

Rework risk:

- High. OAuth implementation details can be invalidated by later storage,
  cleanup, readme, or support evidence decisions.

Release readiness impact:

- Important for public release, but not enough by itself.

Recommendation:

- Not recommended as the immediate next step.

### Option B: Move Directly To OpenAI API Key Storage Redesign Implementation Planning

Pros:

- Addresses the narrower credential storage blocker first.
- Could clarify constant-based configuration and UI storage posture.
- May be smaller than Google OAuth planning.

Cons:

- OpenAI storage decisions still need to align with uninstall cleanup.
- Google OAuth/token storage may later introduce different cleanup or support
  expectations.
- Focusing on OpenAI first may leave the larger Google credential model
  disconnected.

Rework risk:

- Medium to high. OpenAI storage planning may need revision after Google OAuth
  and cleanup architecture is chosen.

Release readiness impact:

- Useful, but incomplete without Google and uninstall decisions.

Recommendation:

- Not recommended as the immediate next step.

### Option C: Move Directly To Uninstall Cleanup Implementation Planning

Pros:

- Targets a concrete remaining blocker.
- Can improve privacy posture around credential-bearing data retention.
- Could be implemented with a relatively clear list of plugin-owned settings if
  storage posture were already final.

Cons:

- Cleanup targets depend on the final Google and OpenAI storage architecture.
- Local deletion semantics differ from OAuth disconnect/revoke behavior.
- Constant-based configuration or other future storage paths may not belong to
  uninstall cleanup.

Rework risk:

- High. Cleanup behavior can change substantially after credential storage
  redesign.

Release readiness impact:

- Required before public release, but should follow credential architecture
  decisions.

Recommendation:

- Not recommended as the immediate next step.

### Option D: Create An Integrated Credential Architecture Plan Before Implementation

Pros:

- Treats Google OAuth, OpenAI key storage, uninstall cleanup, support wording,
  readme/privacy wording, admin UI wording, and QA sequence as connected
  decisions.
- Reduces the chance that storage and cleanup semantics diverge across Google
  and OpenAI credentials.
- Allows implementation order to be chosen deliberately.
- Keeps public release posture, MVP hold posture, and developer verification
  posture separate.
- Supports a safer later implementation plan.

Cons:

- Adds another docs-only step before implementation.
- Delays direct code changes.
- Requires careful scope control so the plan stays actionable.

Rework risk:

- Low. A consolidated plan should reduce later rework by aligning credential
  storage, cleanup, wording, and QA before implementation starts.

Release readiness impact:

- Strong positive planning impact. It does not itself make the plugin
  release-ready, but it creates a safer implementation boundary.

Recommendation:

- Recommended.

## Recommended Decision

Recommended:

```text
Option D - create an integrated credential architecture plan before implementation.
```

Decision:

- Do not move directly into Google OAuth implementation.
- Do not move directly into OpenAI API key storage implementation.
- Do not move directly into uninstall cleanup implementation.
- Create a docs-only integrated credential architecture plan first.
- Use that plan to decide implementation order, storage model, cleanup model,
  support evidence boundaries, readme/admin wording impacts, and QA sequence.

Rationale:

- Google OAuth, OpenAI API key storage, and uninstall cleanup are
  interdependent.
- Implementing any one area in isolation can create later mismatch in storage,
  cleanup, support wording, readme wording, and QA.
- The project should first define one credential architecture boundary across
  Google and OpenAI, then choose implementation order.

## Recommended Next Step

Recommended next step:

```text
Step 114: Integrated credential architecture plan
```

Step 114 should also be docs-only. It should produce an integrated plan for:

- Google OAuth.
- Google token lifecycle.
- OpenAI API key storage.
- Constant-based configuration, if adopted.
- Credential-bearing Settings UI behavior.
- Uninstall cleanup.
- Admin UI wording.
- `readme.txt` / privacy wording.
- Support/debug evidence boundaries.
- QA sequence after implementation.
- Plugin Check rerun sequence after implementation and packaging changes, if
  needed.

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

This document records only status-level credential lifecycle decisions and
implementation boundary planning.

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
- only this Step 113 documentation file is added.
