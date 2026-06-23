# Step 270: OpenAI Legacy / Transitional Fallback Storage, Migration, and Uninstall Release-boundary Decision Checkpoint

## Step Purpose

Step 270 is a docs-only / release-boundary decision checkpoint for the existing
legacy / transitional OpenAI Settings fallback.

The purpose is to classify the current source-level behavior and public-release
decision status for:

- storage;
- normal plugin updates;
- migration to constant-based configuration;
- automatic deletion;
- fallback-only existing installations;
- explicit saved-fallback removal;
- uninstall cleanup;
- multisite / network behavior;
- remaining public-release gates.

Step 270 does not approve public release.

WordPress.org release readiness remains:

```text
Hold
```

## Working-tree Baseline

Baseline commands were run before adding this Step 270 document:

```text
git status --short --untracked-files=all
git diff --name-only
git diff --check
```

Baseline classification:

```text
Clean / Step 263-269 documentation tranche already committed or otherwise not present as uncommitted changes
```

No unexpected production-facing changes were present at the Step 270 baseline.

## Referenced Documents

Requested uninstall doc names were checked against the repository. Some
requested names differed from the current actual file names, so the existing
nearby Step 210 through Step 215 uninstall docs were used without inventing
missing filenames.

Uninstall / cleanup track references:

- `docs/maturation/step210-uninstall-cleanup-boundary-inventory.md`
- `docs/maturation/step211-uninstall-cleanup-policy-decision-checkpoint.md`
- `docs/maturation/step212-uninstall-cleanup-narrow-implementation-plan.md`
- `docs/maturation/step213-uninstall-cleanup-narrow-production-implementation-results.md`
- `docs/maturation/step214-uninstall-cleanup-source-level-verification-results.md`
- `docs/maturation/step215-uninstall-cleanup-maturation-checkpoint.md`

OpenAI fallback / documentation track references:

- `docs/maturation/step253-openai-constant-based-configuration-public-release-boundary-checkpoint.md`
- `docs/maturation/step254-openai-settings-fallback-public-release-storage-disposition-decision-checkpoint.md`
- `docs/maturation/step255-openai-settings-fallback-public-release-disposition-narrow-implementation-plan.md`
- `docs/maturation/step256-openai-settings-fallback-legacy-transitional-narrow-production-implementation-results.md`
- `docs/maturation/step257-openai-settings-fallback-legacy-transitional-source-level-verification-results.md`
- `docs/maturation/step259-openai-settings-fallback-legacy-transitional-controlled-human-admin-smoke-results.md`
- `docs/maturation/step260-openai-settings-fallback-legacy-transitional-post-smoke-release-boundary-checkpoint.md`
- `docs/maturation/step261-openai-constant-based-public-setup-and-legacy-transitional-fallback-disclosure-plan.md`
- `docs/maturation/step262-openai-constant-based-public-setup-and-legacy-transitional-fallback-disclosure-narrow-documentation-implementation-plan.md`
- `docs/maturation/step263-openai-constant-based-public-setup-and-legacy-transitional-fallback-readme-only-narrow-documentation-implementation-results.md`
- `docs/maturation/step264-openai-constant-based-public-setup-and-legacy-transitional-fallback-readme-only-source-level-verification-results.md`
- `docs/maturation/step265-openai-constant-based-public-setup-and-legacy-transitional-fallback-readme-only-wording-correction-plan.md`
- `docs/maturation/step266-openai-constant-based-public-setup-and-legacy-transitional-fallback-readme-only-narrow-wording-correction-implementation-results.md`
- `docs/maturation/step267-openai-constant-based-public-setup-and-legacy-transitional-fallback-readme-only-post-correction-source-level-verification-results.md`
- `docs/maturation/step268-openai-constant-based-public-setup-and-legacy-transitional-fallback-readme-only-human-wording-review-plan.md`
- `docs/maturation/step269-openai-constant-based-public-setup-and-legacy-transitional-fallback-readme-only-controlled-human-wording-review-results.md`

## Read-only Source Inventory

Reviewed source files:

- `readme.txt`
- `uninstall.php`
- `includes/class-settings.php`
- `includes/class-report-builder.php`
- `includes/class-openai-client.php`
- `includes/functions-utils.php`
- `includes/class-plugin.php`

No actual credential, API key value, constant value, Settings option value,
token, placeholder, serialized option data, request body, response body,
payload JSON, generated report body, or database contents were inspected or
recorded.

## Current Implementation Inventory

| Concern | Current source-level behavior | Safe evidence boundary | Release interpretation | Status |
| ------- | ----------------------------- | ---------------------- | ---------------------- | ------ |
| Preferred constant-based source | `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is the named preferred source; resolver/source helper checks the constant category before Settings fallback. | Source file names, helper names, category labels only. | Preferred public route. | Decided for current compatibility boundary |
| Existing Settings fallback source | Existing `openai_api_key` Settings fallback can still be a lower-priority compatibility source when no constant source is available. | Option key name and category labels only; no option values. | Compatibility only, not normal setup. | Provisional release-boundary decision |
| Missing source | Missing category is used when neither constant nor saved fallback category is available. | Category labels only. | Missing state should guide to constant-based setup. | Decided for current compatibility boundary |
| Source priority when constant and fallback both exist | Source category resolves constant first; fallback status can remain saved and hidden. | Source branch order and category labels only. | Constant activation does not imply fallback deletion. | Decided for current compatibility boundary |
| Normal Settings fallback entry/replacement behavior | Normal Settings UI does not render a new fallback password entry; save path no longer creates/replaces fallback from normal input. | Source wording and save-path structure only. | New fallback setup is not the normal public route. | Decided for current compatibility boundary |
| Explicit fallback removal behavior | `clear_openai_api_key` clears only the saved Settings fallback slot; constants are not changed. | Checkbox name, method branch, wording only. | Explicit user-initiated cleanup path. | Decided for current compatibility boundary |
| Fallback-only installation behavior | Existing saved fallback can remain usable for compatibility when no constant source is configured. | Category labels and source branch order only. | Compatibility currently available, not public setup recommendation. | Provisional release-boundary decision |
| Constant plus fallback behavior | Constant remains active/preferred; saved fallback remains lower-priority, hidden, and removable only by explicit fallback clear. | Status labels and source branch order only. | No automatic deletion solely because constant is active. | Decided for current compatibility boundary |
| Uninstall cleanup of plugin-owned settings | Root `uninstall.php` deletes `analytics_report_ai_settings` and `analytics_report_ai_oauth_tokens` with a `WP_UNINSTALL_PLUGIN` guard. | File-level and option-key-level evidence only. | Deterministic plugin-owned option cleanup on uninstall. | Decided for current MVP boundary |
| Uninstall provider-side behavior | `uninstall.php` does not contain external request or provider revoke behavior. | Absence of provider-call source terms in uninstall file. | Local plugin data cleanup only. | Deferred / separate release gate |
| Multisite / network behavior | Activation notes multisite network activation is outside MVP support scope; uninstall does not show per-site iteration or site-option cleanup. | Source-level terms only. | Multisite/network cleanup is not source-confirmed. | Deferred / separate release gate |
| Readme disclosure boundary | Readme describes constant-based setup as preferred, existing fallback as hidden compatibility, and source/readiness as not provider success. | Public wording only. | Disclosure/setup boundary, not migration procedure. | Decided for current documentation tranche |

## Required Release-boundary Decisions

### A. Normal Plugin Update

Decision status:

```text
Provisional release-boundary decision
```

Disposition:

```text
No automatic migration or automatic deletion of an existing legacy / transitional Settings fallback during a normal plugin update.
```

Reasoning:

- automatic deletion could unexpectedly interrupt service for fallback-only
  existing installations;
- automatic deletion could remove a legacy compatibility path without explicit
  user intent;
- no actual value inspection, copying, or provider/runtime verification is
  needed for this decision;
- the constant source remains preferred without being written by the plugin;
- Step 270 makes no code behavior change.

### B. Automatic Migration to Constant-based Configuration

Decision status:

```text
Decided for current compatibility boundary
```

Disposition:

```text
The plugin must not attempt to create, modify, or migrate a secret into ANALYTICS_REPORT_AI_OPENAI_API_KEY automatically.
```

Reasoning:

- a PHP constant is deployment-controlled configuration, not a plugin-owned
  writable settings destination;
- automatic migration would require secret handling outside the current mature
  boundary;
- source docs and UI posture describe constants as preferred and value-hidden,
  not plugin-written;
- Step 270 makes no code behavior change.

### C. Constant Configured While Fallback Remains Saved

Decision status:

```text
Provisional release-boundary decision
```

Disposition:

```text
When constant-based configuration is active and an existing fallback also remains saved, do not automatically delete the fallback. The source priority remains constant-first. The saved fallback may be removed only through the explicit saved-fallback removal control.
```

Boundary:

- priority is not proof of provider authorization or request success;
- automatic deletion is not implied by constant activation;
- fallback removal does not edit constant-based configuration;
- this does not establish actual fallback contents or actual constant
  preservation.

### D. Fallback-only Existing Installation

Decision status:

```text
Provisional release-boundary decision
```

Disposition:

```text
An existing saved fallback may remain a legacy / transitional compatibility source in the current implementation, but it is not the normal public setup route.
```

Distinctions:

- compatibility currently available in source behavior: Yes, as
  `settings_saved` when no constant source is available;
- public setup recommendation: constant-based configuration;
- future deprecation / removal timing: not decided in Step 270;
- public release approval: not established.

Step 270 does not decide a deprecation date, version, or automatic deletion
behavior.

### E. Explicit Saved-fallback Removal Control

Decision status:

```text
Decided for current compatibility boundary
```

Disposition:

- removal is explicit and user-initiated;
- removal applies only to the saved Settings fallback;
- removal does not alter constant-based configuration;
- removal is not automatic migration;
- removal does not verify provider/runtime success.

The current source behavior and wording are clear enough to keep this as the
ordinary fallback cleanup path within the current compatibility boundary.

### F. Uninstall Cleanup Boundary

Decision status:

```text
Decided for current MVP boundary
```

Single-site source-level boundary:

- `uninstall.php` is guarded by `WP_UNINSTALL_PLUGIN`;
- cleanup is limited to deterministic plugin-owned option keys;
- cleanup includes the main plugin settings option category;
- cleanup includes the dedicated OAuth token option category;
- the main settings option can contain legacy / transitional fallback
  categories, but uninstall cleanup does not inspect nested option contents;
- uninstall is local plugin data cleanup only;
- uninstall does not perform provider-side revoke, refresh, token endpoint
  communication, or external request;
- Step 214 source-level verification already passed this boundary.

This is not provider-side key invalidation and does not prove external account
cleanup.

### G. Multisite and Network Boundary

Decision status:

```text
Deferred / separate release gate
```

Source-level disposition:

- multisite support / network activation behavior is not implemented and
  source-confirmed as complete for uninstall cleanup;
- activation source notes that multisite network activation is outside the MVP
  support scope;
- `uninstall.php` does not source-confirm per-site option cleanup,
  site-option cleanup, network-wide uninstall iteration, or multisite
  completeness.

Multisite / network activation / network uninstall requires a separate release
gate.

## Required Decision Matrix

| Topic | Current behavior / evidence | Decision status | Decision or provisional disposition | What must not happen | Remaining gate |
| ----- | --------------------------- | --------------- | ----------------------------------- | -------------------- | -------------- |
| Normal update with existing fallback | Step 256/257 source-level behavior preserves existing fallback; no migration/deletion path was added. | Provisional release-boundary decision | Do not auto-migrate or auto-delete during normal update. | Do not delete a saved fallback without explicit user action. | Later public-release storage/migration checkpoint if policy changes. |
| Automatic migration to constant | Constant name is read as deployment configuration; source does not write constants. | Decided for current compatibility boundary | Do not create, modify, or migrate a secret into `ANALYTICS_REPORT_AI_OPENAI_API_KEY`. | Do not treat constants as plugin-owned writable storage. | None for current boundary; future migration design only if explicitly scoped. |
| Automatic deletion after constant activation | Source priority is constant-first while fallback status may remain saved. | Provisional release-boundary decision | Do not automatically delete fallback solely because constant source is active. | Do not infer cleanup from priority. | Later deprecation/removal policy if needed. |
| Fallback-only existing installation | Existing fallback remains `settings_saved` compatibility when no constant source exists. | Provisional release-boundary decision | Allow as legacy / transitional compatibility only. | Do not present as normal public setup route. | Future deprecation timing / migration policy. |
| Explicit fallback removal | `clear_openai_api_key` clears only Settings fallback and leaves constants unchanged. | Decided for current compatibility boundary | Explicit saved-fallback removal remains the ordinary cleanup path. | Do not call it migration or provider verification. | Human/runtime recheck only if UI changes later. |
| Uninstall on a single-site installation | Root `uninstall.php` deletes deterministic plugin-owned options. | Decided for current MVP boundary | Single-site local plugin-owned option cleanup is accepted. | Do not read option contents or imply provider cleanup. | None for current single-site MVP boundary. |
| Uninstall provider-side effects | No provider request/revoke behavior in `uninstall.php`; Step 214 keeps provider-side revoke separate. | Deferred / separate release gate | Provider-side revoke is not included in uninstall cleanup. | Do not contact external providers during uninstall. | Separate revoke/provider lifecycle track. |
| Multisite / network activation / uninstall | Network activation is outside MVP support scope; no per-site/network cleanup source-confirmed. | Deferred / separate release gate | Treat multisite/network cleanup as unresolved. | Do not claim network cleanup completeness. | Step 271 candidate. |
| Public readme wording | Step 269 human wording review passed; readme is disclosure/setup boundary. | Decided for current documentation tranche | Keep constant route preferred and fallback conditional compatibility only. | Do not add fallback creation/restoration/injection instructions. | Future wording updates only if storage policy changes. |
| WordPress.org release readiness | Multiple storage/multisite/provider gates remain unresolved. | Deferred / separate release gate | Release remains Hold. | Do not treat this checkpoint as approval. | Release readiness consolidation after remaining gates. |

## Current Compatibility Boundary

Current compatibility boundary:

- constant-based configuration remains the preferred public route;
- existing fallback remains compatibility only;
- no automatic fallback migration;
- no automatic fallback deletion during normal updates;
- no automatic deletion solely because constant source is active;
- explicit saved-fallback removal remains the only ordinary cleanup path.

## Uninstall Boundary

Source-based uninstall boundary:

- uninstall cleanup is limited to plugin-owned local data as implemented;
- current source-level verification applies to deterministic option cleanup
  only;
- the single-site implementation deletes `analytics_report_ai_settings` and
  `analytics_report_ai_oauth_tokens`;
- uninstall does not itself prove or perform provider-side revoke;
- uninstall does not establish provider-side token/key invalidation;
- uninstall does not automatically establish multisite cleanup completeness.

## Public Documentation Boundary

Public documentation boundary:

- readme wording is a disclosure and setup boundary, not a migration procedure;
- readme must not instruct users to create, restore, inject, inspect, or
  directly edit a fallback;
- readme must not claim encryption, provider verification, policy compliance,
  or public release approval.

## Explicit Non-conclusions

Step 270 does not:

- modify storage behavior;
- modify normal update behavior;
- modify fallback source priority;
- add automatic migration;
- add automatic deletion;
- modify `uninstall.php`;
- execute uninstall;
- decide a fallback deprecation version or date;
- decide a migration UI or wizard;
- decide multisite cleanup implementation;
- inspect any option or credential value;
- verify actual provider authorization;
- verify OpenAI request or response success;
- verify real external communication;
- run Plugin Check;
- approve public release;
- establish WordPress.org policy compliance.

## Security and Evidence Boundary

Step 270 did not inspect or record:

- actual credentials;
- API key values;
- constant values;
- Settings option values;
- token values;
- placeholders;
- serialized option data;
- database contents;
- request bodies;
- response bodies;
- payload JSON;
- generated report bodies;
- screenshots;
- browser Network evidence.

Step 270 did not perform:

- browser admin smoke;
- Settings save;
- fallback removal operation;
- WP-CLI state mutation;
- `wp option get`;
- raw SQL / database dump;
- option / constant / credential value inspection;
- plugin uninstall execution;
- OpenAI Generate;
- GA4 Fetch;
- OAuth;
- external HTTP;
- provider communication;
- Plugin Check.

## Result Classification

```text
Release-boundary decision checkpoint completed
```

WordPress.org release readiness remains:

```text
Hold
```

## Recommended Next Step

```text
Step 271 candidate — OpenAI legacy/transitional fallback multisite and uninstall source-level release-boundary plan
```

Step 271 is not started in this document.
