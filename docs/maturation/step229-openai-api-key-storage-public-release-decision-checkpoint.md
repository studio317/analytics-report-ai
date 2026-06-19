# Step 229: OpenAI API Key Storage Public-release Decision Checkpoint

## Step Purpose

Step 229 is a docs-only and planning-only decision checkpoint for the
OpenAI API key storage posture before any public-release readiness decision.

Step 228 completed the inventory and option comparison for current MVP OpenAI
API key handling. This step selects the public-release target posture for the
OpenAI API key storage track without changing production code.

Decision outcome:

```text
Decision: Select Option B as public-release target
Recommended public-release posture: Constant-based OpenAI API key configuration preferred over settings storage
Settings storage posture: Fallback / Needs implementation plan
WordPress.org release readiness: Hold
```

## Scope

In scope:

- OpenAI API key public-release storage posture decision,
- current MVP Settings storage and non-redisplay posture,
- constant-based configuration as the public-release target,
- Settings storage fallback classification,
- Settings UI status/category-level direction,
- OpenAI client key resolution boundary,
- readme/privacy/support wording follow-up,
- uninstall cleanup relationship,
- remaining public-release risks.

Out of scope:

- production implementation,
- Settings UI changes,
- credential resolver changes,
- OpenAI client changes,
- GA4 client changes,
- `readme.txt` changes,
- `uninstall.php` changes,
- option value inspection,
- external API calls,
- release-readiness approval.

## Explicit Non-goals

Step 229 does not:

- change production code,
- change `readme.txt`,
- change Settings UI,
- change the credential resolver,
- change OpenAI client behavior,
- change GA4 client behavior,
- change `uninstall.php`,
- change tools or build scripts,
- change JavaScript or CSS,
- run Plugin Check,
- run GA4 Fetch,
- run OpenAI Generate,
- start OAuth Connect / Authorize,
- navigate to Google,
- call token endpoints,
- execute refresh requests,
- execute revoke requests,
- run browser admin smoke,
- execute plugin uninstall,
- collect screenshots,
- collect browser Network evidence,
- inspect database dumps,
- run `wp option get` for plugin option values,
- inspect option values,
- inspect token values,
- inspect credential values,
- inspect OAuth client values,
- inspect request bodies,
- inspect raw responses,
- inspect AI payload JSON,
- inspect generated report bodies.

## Referenced Prior Steps

- `docs/maturation/step228-openai-api-key-storage-posture-checkpoint.md`
- `docs/maturation/step227-readme-privacy-wording-alignment-after-manual-token-retirement-maturation-checkpoint.md`
- `docs/maturation/step226-readme-privacy-wording-alignment-after-manual-token-retirement-source-level-verification-results.md`
- `docs/maturation/step225-readme-privacy-wording-alignment-after-manual-token-retirement-implementation-results.md`
- `docs/maturation/step223-manual-google-access-token-fallback-retirement-maturation-checkpoint.md`
- `docs/maturation/step215-uninstall-cleanup-maturation-checkpoint.md`
- `docs/maturation/step209-credential-storage-public-release-posture-checkpoint.md`
- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`
- `docs/maturation/step96-generated-report-handling-policy-finalization.md`

## Decision Inputs From Step 228

Step 228 established the following source-level and docs-level inputs:

| Input | Step 228 conclusion | Step 229 impact |
|---|---|---|
| Current storage category | OpenAI API key is stored in the main plugin settings option category. | Current behavior remains MVP-compatible but should not become the public-release target by default. |
| Non-redisplay posture | Settings UI uses an empty password value and saved/not-saved state text. | Must be preserved for any Settings fallback. |
| Delete control | Saved Settings key can be cleared through the Settings UI. | Must remain scoped to Settings fallback only if constants are introduced. |
| OpenAI request boundary | OpenAI communication occurs only through administrator-triggered Generate AI Report. | Must be preserved. |
| Generated report storage | Generated report text is not saved by the plugin. | Must be preserved. |
| Support/debug boundary | Support evidence is status/category-level only. | Must be preserved. |
| Public-release posture | OpenAI API key storage remained `Hold / Needs decision`. | Step 229 closes the decision by selecting Option B as target. |
| Recommended candidate | Option B was preferred; Option A was current-MVP-compatible baseline only. | Select Option B unless a new blocker is found. |

This checkpoint records only file, symbol, option key, UI field, storage, and
wording categories. It does not record credential values, option values,
request bodies, raw responses, AI payload JSON, generated report bodies,
screenshots, or Network evidence.

## Public-release Decision Candidates

### Option A: Continue storing OpenAI API Key in plugin settings with strict non-redisplay and explicit disclosure

Option A matches the current MVP workflow:

- Settings storage remains low-friction for administrators.
- Saved values are not redisplayed.
- Empty input preserves the saved key.
- Delete control is available.
- Disclosure can explain database/options exposure.

However, Option A keeps the OpenAI API key in the plugin settings option as
the primary public-release posture. Database administrators, backups, server
administrators, and code that can read WordPress options may access stored
credential categories.

Step 229 classification:

```text
Option A: Current MVP-compatible baseline only / Not selected as public-release target
```

### Option B: Support constant-based OpenAI API Key configuration and prefer constants over settings

Option B introduces a public-release target where a site owner can configure
the OpenAI API key outside the plugin settings option, while preserving a
status/category-level admin posture.

Expected direction:

- constant-based configuration is preferred over Settings storage,
- Settings storage may remain as fallback pending implementation planning,
- no key value is displayed in admin UI,
- Settings UI reports only source/status categories,
- support/debug evidence remains status/category-level only.

Step 229 classification:

```text
Option B: Selected public-release target
```

### Option C: Remove stored OpenAI API Key from public-release build and require per-run entry or external configuration

Option C avoids persistent Settings storage, but it increases UX friction and
may introduce repeated sensitive entry handling in the admin UI.

Step 229 classification:

```text
Option C: Deferred / Not selected for current public-release target
```

### Option D: Use external proxy / service-side key management

Option D can isolate credentials from the WordPress site, but it introduces a
new external service, trust model, billing/privacy implications, and a broader
review burden.

Step 229 classification:

```text
Option D: Deferred / Not selected for near-term MVP maturation
```

### Option E: Defer final decision and keep WordPress.org release readiness on Hold

Option E would keep the Step 228 posture unchanged. Since Step 228 already
identified Option B as the preferred candidate and no new blocker was found in
this checkpoint, Step 229 should make the decision rather than defer it again.

Step 229 classification:

```text
Option E: Rejected as the decision outcome / WordPress.org readiness still remains Hold
```

## Option Comparison Table

| Option | Decision | UX impact | Storage posture | Implementation impact | Release-review posture |
|---|---|---|---|---|---|
| Option A: Settings storage with disclosure | Not selected as target | Low friction | Keeps key in plugin settings as primary path | Low | Risk remains higher because database/options exposure is primary. |
| Option B: Constants preferred over settings | Selected target | Moderate; technical but manageable | Reduces database/backups exposure when constants are used | Moderate | Stronger public-release target if documented and verified. |
| Option C: No stored key / per-run or external config | Deferred | High friction | Avoids plugin settings storage | Moderate to high | Not aligned with current MVP flow. |
| Option D: External proxy / service-side key management | Deferred | Variable | Moves key management outside WordPress | High | Adds external service and privacy/review burden. |
| Option E: Hold without decision | Rejected as outcome | No change | Current risk unresolved | Low now | Keeps readiness blocked without a target. |

## Decision

Step 229 selects Option B as the OpenAI API key public-release target.

```text
Decision: Select Option B as public-release target
OpenAI API key source priority: constant-based configuration preferred over settings storage
Settings storage: fallback / needs implementation plan
Production code changes: Not implemented in Step 229
WordPress.org release readiness: Hold
```

This decision does not make the current implementation release-ready. It
defines the target posture for the next implementation planning step.

## Rationale

Option B is selected because it gives the plugin a safer public-release target
without forcing a large architecture redesign.

Key reasons:

- Current Settings storage is coherent for MVP and developer verification, but
  it keeps the OpenAI API key in a WordPress option category.
- Constant-based configuration can reduce database and backup exposure for
  deployments that can manage secrets in server configuration.
- Constant-based configuration is consistent with the already matured
  status/category-level evidence boundary.
- Admin UI can describe only source categories such as configured via constant,
  saved in Settings fallback, or missing.
- Settings fallback can remain a compatibility path if Step 230 confirms the
  exact implementation and wording boundaries.
- Option B avoids the high friction of per-run entry and the large trust model
  introduced by an external proxy.
- WordPress.org release readiness should remain on Hold until implementation,
  source-level verification, admin smoke, readme/privacy alignment, and final
  release checks are complete.

## Selected Posture Details

### Constant-based Configuration Priority

The public-release target should prefer a constant-based OpenAI API key source
over Settings storage.

Proposed constant name category:

```text
ANALYTICS_REPORT_AI_OPENAI_API_KEY
```

The exact implementation should be planned in Step 230. Step 229 only records
the target source category and does not add the constant.

Expected source priority:

```text
1. constant-based OpenAI API key configuration
2. Settings-saved OpenAI API key fallback, if retained
3. missing OpenAI API key category
```

### Settings UI Status/category Direction

If a constant-based key is configured, Settings UI should avoid displaying the
key value and should report only a safe source/status category.

Possible visible categories for later planning:

```text
openai_api_key_source_category: constant_configured
openai_api_key_source_category: settings_saved
openai_api_key_source_category: missing
openai_api_key_value_visibility: hidden
```

The Settings OpenAI API Key field should not display a constant value. Step 230
should decide whether the field remains editable as a fallback, is disabled
when a constant exists, or is shown with clear wording that it affects only the
Settings fallback.

### Settings Storage Fallback

Step 229 does not remove Settings storage.

Recommended fallback posture:

```text
Settings storage posture: Retain as fallback pending implementation plan
```

Step 230 should decide:

- whether Settings fallback remains available in public release,
- whether it is documented as lower-preference,
- whether it becomes developer-only or restricted later,
- how existing saved Settings keys are reported as status/category-level state,
- whether a Settings fallback clear control remains visible when applicable.

### Existing Saved OpenAI API Key

Existing saved Settings keys should not be displayed or recorded.

Step 229 does not clear or migrate existing saved keys. The implementation plan
should preserve compatibility until an explicit migration/deletion behavior is
chosen.

Expected category-level handling:

```text
existing_settings_saved_key: preserve until explicit implementation decision
existing_settings_saved_key_value: never displayed
clear_settings_saved_key_control: retain or re-scope in implementation plan
```

### Settings Save / Clear Control

If Settings fallback remains available, the current save/empty-input/clear
semantics should remain explicit:

- entering a new Settings fallback key replaces the Settings fallback key,
- leaving the field empty preserves the Settings fallback key,
- clear checkbox deletes only the Settings fallback key,
- constant-based configuration is not deleted by Settings fallback clear
  controls.

Step 230 should verify that wording and control scope make this clear before
any production change.

### OpenAI Client Resolution Boundary

The OpenAI client should continue to receive only the key needed for the
administrator-triggered Generate AI Report request.

Implementation should avoid leaking key source details, key values, request
bodies, raw responses, or Authorization headers to admin UI, logs, docs, or
support evidence.

Expected future boundary:

```text
OpenAI key resolution: source/status handled before or at settings normalization boundary
OpenAI request execution: administrator-triggered Generate AI Report only
OpenAI client evidence: no key value / no Authorization header output
```

### Readme/privacy Wording

Readme and privacy/support wording will need a follow-up after implementation.

Expected direction:

- document that constant-based OpenAI API key configuration is preferred when
  available,
- explain that Settings fallback storage may remain and carries database,
  backup, server, and option-read access risk,
- state that saved values are not redisplayed,
- keep OpenAI communication tied to administrator-triggered Generate AI Report,
- preserve the support boundary that API keys, Authorization headers, option
  values, request bodies, raw responses, AI payload JSON, and generated report
  bodies are not requested.

### Support/debug Evidence Boundary

The support/debug evidence boundary is preserved.

Allowed evidence remains limited to status/category-level labels, visible
warning categories, file/symbol names, and command-result categories.

Forbidden evidence remains:

- option values,
- credential values,
- API keys,
- Authorization headers,
- request bodies,
- raw responses,
- AI payload JSON,
- generated report bodies,
- screenshots,
- browser Network evidence,
- cookies, sessions, and nonces,
- GA4 property identifiers,
- hostname/domain values,
- analytics values.

### Uninstall Cleanup Boundary

Current uninstall cleanup deletes the deterministic plugin-owned settings
option. That covers Settings-saved OpenAI API key categories as part of main
settings option cleanup.

Constant-based configuration is not plugin-owned database storage and should
not be deleted by plugin uninstall.

Step 230 should preserve this distinction:

```text
Settings fallback key cleanup: covered by main settings option deletion
Constant-based key cleanup: not plugin-owned / not uninstall target
```

### Database / Backup / Server / Code Access Risk

Option B reduces database/options and backup exposure when constants are used,
but does not eliminate all secret-access risk.

Risk categories to keep documented:

- Settings fallback storage may be readable by database administrators,
  backups, server administrators, or code that can read WordPress options.
- Constant-based configuration may be accessible to server administrators or
  code that can read configuration files/constants.
- Neither source should be exposed in support/debug evidence.

## Rejected / Deferred Alternatives

| Alternative | Classification | Reason |
|---|---|---|
| Option A as final public-release target | Rejected as target / retained as MVP baseline | Settings storage alone leaves database/options exposure as the primary posture. |
| Option C per-run entry or external-only config | Deferred | Higher UX friction and repeated sensitive input handling are not aligned with current MVP flow. |
| Option D external proxy / service-side key management | Deferred | Too broad for this maturation track; adds a service trust and disclosure model. |
| Option E hold without decision | Rejected as outcome | Step 229 can safely choose the target posture while keeping release readiness on Hold. |
| Encryption / obfuscation | Deferred / Separate track | Not selected as part of this decision checkpoint. |
| Immediate production implementation | Deferred to Step 230+ | Step 229 is docs-only / planning-only. |

## Public Release Boundary

This decision does not connect the plugin to WordPress.org release readiness.

WordPress.org release readiness remains `Hold` because:

- constant-based OpenAI API key configuration is not implemented,
- Settings fallback source precedence is not implemented,
- Settings UI wording and clear-control scoping are not implemented for the
  selected posture,
- readme/privacy wording is not aligned to the final implementation,
- source-level verification has not been performed after implementation,
- human admin smoke has not been performed after implementation,
- Plugin Check and release package checks remain outside this step,
- broader credential storage consolidation remains open.

## Remaining Risks

| Risk | Status | Notes |
|---|---|---|
| Current Settings-stored key remains primary implementation | Hold | Step 229 selects a target but does not implement it. |
| Settings fallback public-release acceptance | Needs implementation plan | Step 230 must decide fallback wording, controls, and status categories. |
| Existing saved Settings keys | Needs implementation plan | Do not redisplay values; decide compatibility and clear behavior before code changes. |
| Constant source configuration access | Accepted risk category / Needs wording | Constants reduce database exposure but remain visible to server/config access. |
| Readme/privacy alignment | Needs future alignment | Must follow implementation. |
| Support/debug leakage | Preserved boundary / Ongoing risk | Status/category-only evidence must remain enforced. |
| Release-readiness overclaiming | Hold | This decision is not a final release approval. |

## Recommended Next Step

Recommended next step:

```text
Step 230: OpenAI API key constant-based configuration implementation plan
```

Step 230 should remain docs-only / planning-only. It should define the exact
constant name, resolver boundary, Settings UI status/categories, fallback
behavior, existing saved-key handling, delete-control scoping, readme/privacy
follow-up, verification sequence, and forbidden-evidence boundary before
production implementation begins.

## Result Classification

```text
OpenAI API key storage public-release decision checkpoint: Completed
Current OpenAI API key handling: MVP settings storage with non-redisplay / Inventory accepted
Decision: Select Option B as public-release target
Recommended public-release posture: Constant-based OpenAI API key configuration preferred over settings storage
Settings storage posture: Fallback / Needs implementation plan
Existing saved OpenAI API key handling: Preserve until explicit implementation decision / Never redisplay
OpenAI API key support evidence boundary: Status/category-level only / Preserved
OpenAI API request boundary: Administrator-triggered Generate AI Report only / Preserved
Generated report body storage posture: Non-storage / Preserved
Readme/privacy wording: Needs future alignment after implementation
Production code changes: Not implemented in Step 229
WordPress.org release readiness: Hold
```
