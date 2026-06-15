# Step 117: OpenAI API Key Storage Implementation Plan

## Step Summary

This step records a docs-only implementation plan for OpenAI API key storage.

Step 115 defined this as Phase 2 of the credential implementation roadmap. The
goal is to plan storage location, constant-based configuration, credential
source precedence, UI persistence policy, non-redisplay, delete/rotation
behavior, missing/invalid/permission-limited key messaging, support/debug
evidence boundaries, QA, and future implementation slices before code changes
begin.

This step does not implement OpenAI API key storage changes, option migration,
encryption, constant-based configuration, secret manager integration, Settings
save logic changes, credential storage changes, `uninstall.php`, option
deletion, admin UI changes, readme changes, package rebuilds, Plugin Check
reruns, or external API calls.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step116-google-oauth-token-lifecycle-implementation-plan.md`
- `docs/maturation/step115-credential-implementation-roadmap-phase-boundary-plan.md`
- `docs/maturation/step114-integrated-credential-architecture-plan.md`
- `docs/maturation/step113-credential-lifecycle-decision-summary-next-implementation-boundary.md`
- `docs/maturation/step112-uninstall-credential-cleanup-policy-decision-checkpoint.md`
- `docs/maturation/step111-openai-api-key-storage-posture-decision-checkpoint.md`
- `docs/maturation/step110-google-oauth-token-lifecycle-strategy-decision-checkpoint.md`
- `docs/maturation/step109-release-readiness-blocker-reprioritization-after-plugin-check-pass.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`
- `docs/maturation/step104-readme-privacy-wording-alignment-implementation-results.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`

## Current Baseline

| Area | Baseline |
|---|---|
| Current settings-based OpenAI API key storage | Developer verification only. |
| WordPress.org release | `Hold`. |
| Public release requirement | OpenAI API key storage posture needs redesign or explicit acceptance before public release, unless OpenAI integration is removed from public scope. |
| Roadmap phase | Step 115 Phase 2 docs-only. |
| Prior phase | Step 116 created the Google OAuth / token lifecycle implementation plan. |
| External API communication | Not allowed until Phase 8 by default; Phase 8 still requires explicit approval for any real external API E2E. |
| Plugin Check | Not run until Phase 9. |
| Package rebuild | Not run until Phase 9. |
| Current Settings model | Status-level source review shows Settings saves an OpenAI key category, preserves empty-input existing value behavior, supports clear/delete behavior, and does not redisplay the saved value. |
| Current OpenAI usage | Status-level source review shows the OpenAI client reads the key category from settings before calling OpenAI. |

## Future Implementation Goals

Future OpenAI API key storage implementation should:

- keep public release posture and developer verification posture separate,
- decide whether UI-based persistent API key storage is acceptable for public
  release,
- include constant-based configuration as a candidate,
- define credential source precedence,
- keep saved/not-saved state as redacted status-level UI,
- preserve the guarantee that stored key values are not redisplayed,
- define delete, replace, and rotation behavior,
- map missing, invalid, and permission-limited API key failures to safe
  status-level messages,
- avoid exposing Authorization headers, request bodies, raw responses, payloads,
  or generated report bodies,
- limit support/debug evidence to status-level information.

## Planned Implementation Areas

Future implementation planning should cover at least:

- storage location,
- option-based storage boundary,
- constant-based configuration boundary,
- credential source precedence,
- Settings UI persistence policy,
- non-redisplay behavior,
- explicit delete behavior,
- replacement / rotation behavior,
- missing key handling,
- invalid key handling,
- restricted / permission-limited key handling,
- OpenAI client credential resolution impact,
- admin-facing status / error messages,
- support/debug evidence boundary,
- QA / manual smoke plan,
- relationship to uninstall cleanup,
- relationship to Google credential architecture.

## Strategy Choices To Decide Before Implementation

The following decisions must be made before code changes begin:

| Decision point | Notes |
|---|---|
| UI-based persistent storage in public release | Decide whether settings-based persistence remains acceptable, is developer-only, or is replaced by another model. |
| Constant-based configuration | Decide whether a constant is supported as a public-release storage option. |
| Source precedence | Decide behavior when both an option value and a constant are present. |
| Settings UI shape | Decide whether the key input remains, becomes status/delete/replace UI, or changes when a constant is active. |
| Delete / replace / rotation semantics | Define what delete does for option-based keys and what it cannot do for constants or external configuration. |
| Non-redisplay guarantee | Preserve the rule that stored key values are never rendered back into the admin UI. |
| Safe error categories | Define safe messages for missing, invalid, restricted, permission-limited, quota, model, and endpoint failures. |
| Multi-admin expectations | Decide which capability can save, delete, replace, or view status for the credential source. |
| Site-owner vs administrator responsibility | Define who owns site configuration when constants or deployment-managed secrets are used. |
| Safe support evidence | Define status labels, redacted saved-state, active source category, and safe error categories that support can request. |
| Excluded support evidence | Keep credentials, option values, headers, request bodies, raw responses, payload JSON, generated report bodies, identifiers, and analytics values out of support evidence. |
| Uninstall cleanup | Decide which credential-bearing option data should be removed by uninstall cleanup after storage posture is accepted. |

## Proposed Implementation Slices

### Slice A: Credential Source Resolution Skeleton

Purpose:

- Introduce a single future credential resolution boundary for OpenAI API key
  lookup.
- Keep current option-based behavior while making a future constant source
  boundary explicit.
- Avoid user-visible behavior changes in the initial skeleton.

Likely changed files:

- `includes/functions-utils.php`
- `includes/class-openai-client.php`
- possibly `includes/class-settings.php`
- docs under `docs/maturation/`

Explicit non-goals:

- No constant-based configuration behavior yet.
- No option migration.
- No Settings UI behavior change.
- No OpenAI request payload change.
- No external API calls.

QA scope:

- PHP syntax checks.
- Focused source review for credential resolution.
- Local checks that do not display option values.

External API requirement:

- No.

Risk level:

- Medium, because credential resolution affects OpenAI Generate behavior.

### Slice B: Constant-based Configuration Support And Source Precedence

Purpose:

- Add the accepted constant-based configuration path if adopted.
- Define precedence when both option and constant sources exist.
- Ensure the UI can report status without exposing values.

Likely changed files:

- `includes/functions-utils.php`
- `includes/class-openai-client.php`
- `includes/class-settings.php`
- possibly `analytics-report-ai.php` if a constant name or documented default
  boundary is needed.
- docs under `docs/maturation/`

Explicit non-goals:

- No secret manager integration.
- No encryption implementation.
- No option migration.
- No raw key display.
- No OpenAI request body changes.

QA scope:

- PHP syntax checks.
- Source review for precedence and redaction.
- Safe local constant/option status checks without printing values.

External API requirement:

- No.

Risk level:

- Medium to high, because precedence and UI state can confuse users if not
  implemented clearly.

### Slice C: Settings UI Saved-state / Non-redisplay / Replace Behavior Alignment

Purpose:

- Align Settings UI with the accepted storage source model.
- Preserve non-redisplay behavior.
- Make saved-state, configured-by-site-configuration state, replace behavior,
  and disabled/delete behavior clear.

Likely changed files:

- `includes/class-settings.php`
- possibly `assets/js/admin.js` only if the accepted UI design requires
  existing behavior to react to source state.
- docs under `docs/maturation/`

Explicit non-goals:

- No credential value redisplay.
- No option migration.
- No OpenAI request behavior changes.
- No generated report behavior changes.

QA scope:

- PHP syntax checks.
- Admin UI source review.
- Manual admin smoke in a later phase, without entering or recording real
  credentials unless explicitly approved.

External API requirement:

- No.

Risk level:

- Medium, because UI wording and controls directly affect credential safety.

### Slice D: Explicit Delete / Rotation Behavior

Purpose:

- Define and implement delete behavior for option-based OpenAI API key storage.
- Define replace/rotation behavior.
- Make clear what delete does not do for constants or external configuration.

Likely changed files:

- `includes/class-settings.php`
- possibly `includes/functions-utils.php`
- docs under `docs/maturation/`

Explicit non-goals:

- No constant deletion.
- No external secret manager changes.
- No uninstall cleanup unless separately scoped.
- No OpenAI API calls.

QA scope:

- PHP syntax checks.
- Safe local status checks that do not print option values.
- Later manual smoke for delete/replace state only if explicitly scoped.

External API requirement:

- No.

Risk level:

- Medium, because delete/rotation behavior affects credential retention and
  user trust.

### Slice E: OpenAI Client Credential Resolution And Safe Error Mapping

Purpose:

- Ensure the OpenAI client uses the accepted credential resolution boundary.
- Preserve safe errors for missing, invalid, permission-limited, quota, model,
  endpoint, and temporary service states.
- Avoid exposing Authorization headers, raw responses, request bodies, payloads,
  or generated report bodies.

Likely changed files:

- `includes/class-openai-client.php`
- possible credential helper include or `includes/functions-utils.php`
- `includes/class-report-builder.php` only if status-level reporting needs a
  narrow integration change.
- docs under `docs/maturation/`

Explicit non-goals:

- No OpenAI request payload structure changes.
- No AI payload structure changes.
- No generated report persistence changes.
- No raw response logging or display.
- No external API calls during implementation.

QA scope:

- PHP syntax checks.
- Source review for safe error mapping.
- Local missing-key/status checks without option values.
- External OpenAI E2E only in a later explicitly approved controlled
  verification phase.

External API requirement:

- No for implementation and local checks. Yes only for real OpenAI E2E in a
  later approved verification phase.

Risk level:

- High, because credential resolution and error mapping affect the OpenAI
  Generate boundary.

### Slice F: Support/debug Evidence And Admin-facing Status Wording Hook Points

Purpose:

- Identify and implement hook points for user-facing and support-facing status
  wording after storage behavior is accepted.
- Keep evidence requests limited to status-level labels and safe UI state.

Likely changed files:

- `includes/class-settings.php`
- `includes/class-report-builder.php`
- `readme.txt` in a later wording phase, not during the storage implementation
  slice unless explicitly scoped.
- docs under `docs/maturation/`

Explicit non-goals:

- No storage behavior changes unless separately scoped.
- No raw key, option value, request body, raw response, payload, or generated
  report body display.
- No Plugin Check rerun.
- No package rebuild.

QA scope:

- PHP syntax checks if PHP wording changes occur.
- Wording review for support/debug evidence boundaries.
- Later manual admin smoke.

External API requirement:

- No.

Risk level:

- Low to medium, but poor wording can undermine credential safety.

## Recommended Implementation Sequence

Recommended order:

```text
1. Credential source resolution skeleton
2. Constant-based configuration and source precedence
3. Settings UI saved-state / non-redisplay / replace behavior alignment
4. Explicit delete / rotation behavior
5. OpenAI client credential resolution and safe error mapping
6. Support/debug evidence and admin-facing status wording alignment
7. Controlled verification with explicit approval only
```

Rationale:

- A single credential resolution boundary should exist before adding more
  sources.
- Constant-based configuration and precedence should be settled before Settings
  UI wording is finalized.
- Delete and rotation semantics depend on which source is active.
- OpenAI client integration should follow the accepted credential resolution
  model.
- Wording alignment should follow behavior changes.
- Controlled verification must be explicit and must not record sensitive
  evidence.

## Recommended Next Step

Recommended next step:

```text
Step 118: Uninstall credential cleanup implementation plan
```

Reason:

- Step 115 defines Phase 3 as the uninstall credential cleanup implementation
  plan.
- Google OAuth and OpenAI storage plans should inform cleanup targets and
  boundaries.
- Cleanup should be planned before credential-related code changes begin so the
  implementation phases do not create retention behavior that later needs to be
  redesigned.

## Support / Debug Evidence Boundary

All future OpenAI API key storage work must preserve this evidence boundary:

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
- implement OpenAI API key storage,
- implement option migration,
- implement encryption,
- implement constant-based configuration,
- integrate a secret manager,
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

This document records only status-level OpenAI API key storage planning.

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
- only this Step 117 documentation file is added.
