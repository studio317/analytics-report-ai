# Step 118: Uninstall Credential Cleanup Implementation Plan

## Step Summary

This step records a docs-only implementation plan for uninstall credential
cleanup.

Step 115 defined this as Phase 3 of the credential implementation roadmap. The
goal is to plan cleanup scope, delete targets, credential-bearing data,
non-sensitive settings, constant-based configuration limitations, OAuth
disconnect/revoke boundaries, multisite scope, support/debug evidence
boundaries, QA, and future implementation slices before code changes begin.

This step does not create `uninstall.php`, delete options, migrate data, add
retention prompts, change Settings save logic, change credential storage,
implement Google OAuth, implement OpenAI API key storage changes, rebuild
packages, rerun Plugin Check, or call external APIs.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step117-openai-api-key-storage-implementation-plan.md`
- `docs/maturation/step116-google-oauth-token-lifecycle-implementation-plan.md`
- `docs/maturation/step115-credential-implementation-roadmap-phase-boundary-plan.md`
- `docs/maturation/step114-integrated-credential-architecture-plan.md`
- `docs/maturation/step113-credential-lifecycle-decision-summary-next-implementation-boundary.md`
- `docs/maturation/step112-uninstall-credential-cleanup-policy-decision-checkpoint.md`
- `docs/maturation/step111-openai-api-key-storage-posture-decision-checkpoint.md`
- `docs/maturation/step110-google-oauth-token-lifecycle-strategy-decision-checkpoint.md`
- `docs/maturation/step109-release-readiness-blocker-reprioritization-after-plugin-check-pass.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`

## Current Baseline

| Area | Baseline |
|---|---|
| Uninstall credential cleanup | Not implemented; policy not finalized. |
| `uninstall.php` | Not present in the current source repository. |
| Manual Google Access Token entry | Developer verification only. |
| Current settings-based OpenAI API key storage | Developer verification only. |
| Google plan | Step 116 created the Google OAuth / token lifecycle implementation plan. |
| OpenAI plan | Step 117 created the OpenAI API key storage implementation plan. |
| Main plugin option | Status-level source review identifies `analytics_report_ai_settings` as the plugin settings option. |
| Temporary payload data | Status-level source review identifies a user-scoped payload transient helper; values were not inspected. |
| WordPress.org release | `Hold`. |
| External API communication | Not allowed until Phase 8 by default; Phase 8 still requires explicit approval for any real external API E2E. |
| Plugin Check | Not run until Phase 9. |
| Package rebuild | Not run until Phase 9. |

## Future Implementation Goals

Future uninstall credential cleanup implementation should:

- explicitly cover plugin-owned credential-bearing data before public release,
- distinguish credential-bearing settings from non-sensitive preferences,
- define whether non-sensitive settings are retained or deleted,
- distinguish local uninstall cleanup from OAuth disconnect,
- distinguish local uninstall cleanup from provider-side revoke,
- explain that constant-based configuration or externally managed secrets
  cannot be deleted by plugin uninstall,
- align uninstall behavior with the final Google and OpenAI credential storage
  models,
- avoid recording option values during implementation or QA,
- limit support/debug evidence to status-level information.

## Planned Implementation Areas

Future implementation planning should cover at least:

- whether to introduce `uninstall.php`,
- uninstall guard / direct access guard,
- `delete_option()` targets,
- `delete_site_option()` targets, if multisite support is required,
- plugin-owned credential-bearing settings cleanup,
- non-sensitive settings retention / deletion policy,
- transient cleanup targets, if any,
- scheduled action / cron cleanup targets, if any,
- OAuth local disconnect versus uninstall cleanup boundary,
- provider-side revoke boundary,
- constant-based configuration cleanup limitation,
- reinstall behavior expectations,
- multisite support requirements,
- activation / deactivation / uninstall responsibility separation,
- support/debug evidence boundary,
- QA / manual smoke plan.

## Strategy Choices To Decide Before Implementation

The following decisions must be made before code changes begin:

| Decision point | Notes |
|---|---|
| Introduce `uninstall.php` | Decide whether uninstall cleanup belongs in a dedicated uninstall file for public release. |
| Delete all plugin-owned settings vs split cleanup | Decide whether uninstall removes the whole plugin settings option or separates credential-bearing data from non-sensitive preferences. |
| Google OAuth token data cleanup | Decide which local Google token/connection data is deleted and how it relates to disconnect/revoke behavior. |
| OpenAI option-based key cleanup | Decide whether option-stored OpenAI key data is always deleted on uninstall. |
| Constant-based OpenAI configuration | Treat constants or externally managed secrets as outside uninstall cleanup scope. |
| Provider-side revoke on uninstall | Decide whether provider-side revoke is excluded from uninstall and handled only by explicit admin UI disconnect/revoke. |
| Multisite scope | Decide whether multisite cleanup is in the first public-release scope and whether site options are used. |
| Transient cleanup | Decide whether user-scoped payload transients should be actively deleted or allowed to expire naturally. |
| Temporary data cleanup | Decide whether any other temporary data, scheduled actions, or cron entries exist and need cleanup. |
| Reinstall expectations | Decide whether uninstall means a clean reinstall with no previous plugin-owned settings. |
| Safe support evidence | Define status-level evidence support may request for uninstall/reinstall issues. |
| Excluded support evidence | Keep credentials, option values, headers, request bodies, raw responses, payload JSON, generated report bodies, identifiers, and analytics values out of support evidence. |

## Proposed Implementation Slices

### Slice A: Cleanup Target Inventory Docs/source Review Only

Purpose:

- Inventory plugin-owned options, transients, scheduled actions, and other
  cleanup candidates at source level.
- Produce a final delete-target decision before any code change.

Likely changed files:

- docs under `docs/maturation/`
- no production files

Explicit non-goals:

- No `uninstall.php` creation.
- No option deletion implementation.
- No database value inspection.
- No option value display.
- No external API calls.

QA scope:

- Source review only.
- Repository diff/status checks.

External API requirement:

- No.

Risk level:

- Low.

### Slice B: `uninstall.php` Skeleton With Guard Only

Purpose:

- Add an uninstall entrypoint with direct-access guard only.
- Establish the file boundary without deleting options yet.

Likely changed files:

- `uninstall.php`
- docs under `docs/maturation/`

Explicit non-goals:

- No `delete_option()`.
- No `delete_site_option()`.
- No transient deletion.
- No migration.
- No provider-side revoke.
- No Settings save logic change.

QA scope:

- PHP syntax checks.
- Source review for the guard and no-op behavior.
- No uninstall execution unless a later step explicitly scopes it.

External API requirement:

- No.

Risk level:

- Low to medium, because adding an uninstall entrypoint creates a release-facing
  lifecycle file even before deletion behavior is added.

### Slice C: Plugin-owned Non-sensitive Settings Cleanup Decision Implementation

Purpose:

- Implement the accepted behavior for non-sensitive plugin-owned settings if
  the final policy chooses deletion or retention handling.
- Keep credential-bearing cleanup decisions separate if needed.

Likely changed files:

- `uninstall.php`
- possible docs under `docs/maturation/`

Explicit non-goals:

- No credential-bearing option deletion unless the same step explicitly scopes
  it.
- No Google OAuth changes.
- No OpenAI key storage changes.
- No provider-side revoke.

QA scope:

- PHP syntax checks.
- Source review for exact delete targets.
- Safe local behavior planning without printing option values.

External API requirement:

- No.

Risk level:

- Medium, because non-sensitive settings may affect reinstall expectations.

### Slice D: Credential-bearing Option Cleanup Implementation

Purpose:

- Delete accepted plugin-owned credential-bearing option data on uninstall.
- Ensure Google and OpenAI credential categories follow the public release
  cleanup policy.

Likely changed files:

- `uninstall.php`
- possible credential helper docs or implementation result docs

Explicit non-goals:

- No provider-side revoke unless separately adopted.
- No OpenAI constant deletion.
- No external secret manager deletion.
- No database value display.
- No Settings save logic change.

QA scope:

- PHP syntax checks.
- Source review for exact option names.
- Safe local uninstall/reinstall test only if separately scoped and without
  printing option values.

External API requirement:

- No.

Risk level:

- High, because this slice deletes credential-bearing local data.

### Slice E: Transient / Temporary Data Cleanup Implementation, If Applicable

Purpose:

- Implement cleanup for temporary plugin-owned data if final policy requires
  active deletion rather than natural expiration.

Likely changed files:

- `uninstall.php`
- possible helper functions if an accepted implementation needs enumerated
  transient cleanup
- docs under `docs/maturation/`

Explicit non-goals:

- No payload value display.
- No AI payload JSON recording.
- No generated report body recording.
- No external API calls.

QA scope:

- PHP syntax checks.
- Source review for transient cleanup patterns.
- Safe local status-level checks only.

External API requirement:

- No.

Risk level:

- Medium to high, because user-scoped transient cleanup can be difficult to
  enumerate safely without broad database operations.

### Slice F: Multisite Cleanup Handling, If Adopted

Purpose:

- Implement multisite cleanup only if multisite is accepted in public release
  scope.
- Define whether per-site options, site options, or network-level state exist.

Likely changed files:

- `uninstall.php`
- possible helper functions
- docs under `docs/maturation/`

Explicit non-goals:

- No multisite support unless explicitly adopted.
- No broad database scans without a reviewed target list.
- No option value display.

QA scope:

- PHP syntax checks.
- Source review for multisite branches.
- Multisite test plan or execution only in a later scoped step.

External API requirement:

- No.

Risk level:

- High if adopted, because network cleanup can affect multiple sites.

### Slice G: Uninstall/reinstall QA And Safe Evidence Documentation

Purpose:

- Verify uninstall/reinstall behavior using safe status-level evidence.
- Confirm cleanup behavior without recording option values or credential
  fragments.

Likely changed files:

- docs under `docs/maturation/`
- no production files unless a separate fix step is opened

Explicit non-goals:

- No opportunistic fixes inside the QA results step.
- No external API calls.
- No option value dumps.
- No screenshots by default.

QA scope:

- Safe status-level uninstall/reinstall checks.
- Confirm whether plugin-owned settings are present or absent without printing
  values.
- Confirm no credential values, identifiers, analytics values, payload bodies,
  raw responses, or generated report bodies are recorded.

External API requirement:

- No.

Risk level:

- Medium, because uninstall/reinstall QA can accidentally expose option values
  if commands are not constrained.

## Recommended Implementation Sequence

Recommended order:

```text
1. Cleanup target inventory and final delete-target decision
2. uninstall.php skeleton with direct-access guard
3. Credential-bearing option cleanup
4. Non-sensitive settings cleanup or retention behavior
5. Transient / temporary data cleanup, if applicable
6. Multisite cleanup handling, if adopted
7. Uninstall/reinstall QA with safe status-level evidence
8. Admin UI/readme/support wording alignment after credential behavior changes
```

Rationale:

- A final target inventory should happen before any uninstall code is added.
- The uninstall entrypoint can be introduced separately from deletion behavior.
- Credential-bearing cleanup should be explicit and reviewed.
- Non-sensitive settings retention/deletion can then be aligned with user
  expectations.
- Transient and multisite cleanup should not be implemented unless the target
  set and scope are clear.
- QA must avoid option value output.
- Wording alignment should follow the accepted credential behavior.

## Recommended Next Step

Recommended next step:

```text
Step 119: Credential implementation readiness checkpoint
```

Reason:

- Step 116, Step 117, and Step 118 now provide docs-only implementation plans
  for Google OAuth, OpenAI API key storage, and uninstall credential cleanup.
- The next step should decide which implementation slice starts first and
  restate the exact scope before code changes begin.
- A readiness checkpoint reduces the chance of mixing OAuth, OpenAI storage,
  uninstall cleanup, wording, QA, and Plugin Check in one oversized step.

## Support / Debug Evidence Boundary

All future uninstall cleanup work must preserve this evidence boundary:

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
- In uninstall/reinstall QA, confirm presence/absence status only and never
  print option values.

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
- create `uninstall.php`,
- implement option deletion,
- implement migration,
- implement retention prompts,
- implement Google OAuth,
- implement OpenAI API key storage,
- change credential storage,
- change Settings save logic,
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

This document records only status-level uninstall credential cleanup planning.

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
- only this Step 118 documentation file is added.
