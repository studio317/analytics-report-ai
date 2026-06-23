# Step 255: OpenAI Settings Fallback Public-release Disposition Narrow Implementation Plan

## Plan Purpose and Decision Input

Step 255 is a docs-only / planning-only implementation plan for translating
Step 254's recommended disposition into a later narrow production
implementation plan.

Step 254 recommended:

```text
Recommended disposition: Option C
```

Option C means the OpenAI Settings fallback remains a developer-oriented /
transitional release disposition concept and is removed from primary user
guidance. Exposure, routing, wording, support, and privacy boundaries should be
strictly limited.

This plan preserves the following distinction:

- Option C is a recommended release disposition, not completed implementation.
- Current Settings fallback remains an MVP capability until a later
  implementation step changes it.
- "Developer-only" is not a technical access-control claim unless a later
  implementation establishes such a control.
- Public-facing wording should use a technically accurate `legacy` /
  `transitional` concept unless an actual gate is implemented.

Fixed inputs maintained by this plan:

```text
Current MVP source model: Constant first / Settings fallback / Missing
Public-release preferred direction: constant-based OpenAI API key configuration preferred over Settings storage
Settings fallback: current MVP fallback
Step 252: Scope-bound Pass within limited human-visible UI scope
WordPress.org release readiness: Hold
Actual credential validity / real OpenAI success / provider behavior: Not verified
```

This step does not change production PHP, JavaScript, CSS, `readme.txt`,
`uninstall.php`, tools, existing docs, `wp-dev`, or `wp-dev-check`.

## Current-State Inventory

The inventory below records source/category-level behavior only. It does not
record credential values, option values, constant values, request bodies,
responses, payload JSON, or generated report bodies.

| Area | Current behavior | Relevant source category or control | Planning implication |
| ---- | ---------------- | ----------------------------------- | -------------------- |
| Constant-first resolution | `analytics_report_ai_get_openai_api_key_source()` checks the constant source before the Settings fallback. | `constant_configured` / `analytics_report_ai_get_openai_api_key_constant_name()` | Keep resolver priority unchanged unless a later step explicitly changes the source model. |
| Settings fallback resolution | If no constant source is configured and a Settings fallback is saved, the source category becomes `settings_saved`. | `settings_saved` / `openai_api_key_settings_fallback_status: saved` | Existing saved fallback can remain operational during transition without promoting it as the normal setup route. |
| Missing resolution | If neither source is present, the source category is `missing`. | `missing` / `openai_api_key_settings_fallback_status: not_saved` | Missing-state guidance should point primarily to constant-based configuration. |
| Settings API key field exposure | The Settings page renders an OpenAI fallback password field with an empty `value` attribute and source-aware descriptions. | `includes/class-settings.php` / OpenAI API Key Fallback field | Later implementation should avoid presenting this field as a normal public-release setup route. |
| Settings save path | `sanitize_settings()` can store a non-empty submitted `openai_api_key`; empty input preserves the existing fallback; clear input deletes it. | `openai_api_key` / `clear_openai_api_key` | Any change to new fallback creation or editing is a data mutation boundary and needs narrow verification. |
| Clear control visibility and effect | `clear_openai_api_key` is rendered only when a Settings fallback is saved and clears only the Settings fallback value. | `clear_openai_api_key` / fallback-only delete wording | Preserve fallback-only scope. Consider keeping `settings_saved` clear-only in normal UI. |
| Settings source-aware wording | Settings displays source category, fallback status, value-hidden status, preferred constant name, and support/debug safety wording. | `openai_api_key_source_category` / `openai_api_key_value_visibility: hidden` | Wording must shift from "current MVP fallback" guidance toward legacy / transitional wording for public-release alignment. |
| Report Builder readiness wording | Report Builder displays OpenAI source category, fallback status, value-hidden status, and missing-state guidance. | `includes/class-report-builder.php` / Current Settings table | Readiness should not imply Settings fallback is the ordinary public-release route. |
| Value-hidden posture | UI does not redisplay stored OpenAI key values; password field value is empty. | `openai_api_key_value_visibility: hidden` | Must be preserved across all states and smoke checks. |
| Uninstall cleanup relationship | Root `uninstall.php` deletes deterministic plugin-owned options, including the main settings option. | `analytics_report_ai_settings` cleanup | Uninstall cleanup already removes the fallback with the main settings option, but public-release storage posture remains a separate decision. |
| Readme / privacy / support-debug relationship | Existing docs and wording avoid requesting key values or raw support evidence. | status/category-level evidence posture | Any public-release wording must maintain no-value, no-raw-evidence support boundaries. |

## Target Public-release UI and Behavior Model

The target model below describes a later implementation if Option C is adopted.
It is not an implementation record.

| Source state | Resolver behavior target | Settings UI target | Report Builder target | Clear-control target | Primary guidance target | Compatibility / migration note |
| ------------ | ------------------------ | ------------------ | --------------------- | -------------------- | ----------------------- | ------------------------------ |
| `constant_configured` | Continue resolving the constant source before any Settings fallback. | Show the constant source as preferred. Do not promote fallback entry or mutation controls. If a saved fallback also exists, show status and optionally clear-only fallback cleanup. | Show readiness as satisfied by the preferred source category, while stating that category does not prove provider success. | Visible only if a Settings fallback is saved; fallback-only scope. | Configure and maintain the server constant. | No automatic deletion of any saved fallback. Existing fallback remains lower priority until explicitly cleared or separately migrated. |
| `settings_saved` | Continue accepting the existing saved fallback during the transition unless a later step removes it. | Treat as legacy / transitional. Preserve hidden value posture. Prefer status plus clear-only UI over edit/replace UI in normal admin guidance. | Show current readiness as satisfied by a legacy / transitional fallback, not by the preferred source. | Visible and scoped only to the saved Settings fallback. | Move to constant-based configuration when possible. | Existing value should not be exposed, copied, migrated, or deleted automatically. |
| `missing` | Continue returning `missing` when no constant or saved fallback is present. | Guide primarily to constant-based configuration. Avoid presenting Settings storage as the normal public-release setup route. | Show missing readiness with constant-based guidance first. | Hidden / not applicable. | Configure the preferred constant source. | Do not create or inspect fallback values during planning. Any retained fallback entry mechanism needs a separate implementation decision. |

This model intentionally does not assume automatic deletion, automatic
migration, or automatic conversion of existing Settings fallback values.

## Narrow Implementation Surface

| Candidate area | Current responsibility | Proposed narrow change | Why needed for Option C | Data mutation risk | Browser verification needed |
| -------------- | ---------------------- | ---------------------- | ----------------------- | ------------------ | --------------------------- |
| OpenAI API key resolver or credential source helper | Defines `constant_configured`, `settings_saved`, and `missing`, and resolves request-local key material. | No change for the first narrow implementation. Keep constant-first / Settings fallback / missing. | Avoids changing request behavior while UI disposition is aligned. | High if changed incorrectly. | Source-level verification only if unchanged. |
| Settings field rendering and descriptive wording | Renders the fallback password field, source category labels, fallback status, value-hidden status, and guidance. | Reword to legacy / transitional fallback. In normal UI, avoid presenting fallback entry as a primary setup path. Prefer no new fallback entry in `missing` and `constant_configured` states; status + clear-only when `settings_saved` exists. | This is the main Option C alignment surface. | Medium if UI still posts fallback values or blocks intended clearing. | Yes, for all three source states. |
| Settings save / validation path | Saves a new non-empty Settings fallback, preserves existing fallback on empty input, and clears fallback when `clear_openai_api_key` is checked. | If UI removes new fallback entry, align save handling so accidental or crafted normal Settings submissions do not silently create a new public-route fallback unless a later explicit gate exists. Preserve existing value and clear behavior. | Prevents UI-only de-emphasis from being bypassed by the same normal Settings save path. | Medium / high because stored fallback behavior can change. | Yes, with controlled local-only state categories. |
| `clear_openai_api_key` handling | Deletes only the saved Settings fallback; constants are not changed. | Keep fallback-only delete behavior. Show only when a Settings fallback is saved. | Required for safe transition and cleanup without exposing values. | Low if unchanged; medium if visibility changes. | Yes, for saved fallback state. |
| Settings source / lifecycle category labels | Shows safe source category, fallback status, and value-hidden status. | Keep labels; adjust surrounding descriptions to avoid ordinary public-release setup framing. | Maintains safe support/debug evidence while narrowing fallback meaning. | Low. | Yes. |
| Report Builder readiness and guidance wording | Shows source category and missing-readiness guidance before Generate. | Align with Settings: constant source preferred; Settings fallback is legacy / transitional if already saved; missing state should not promote Settings-only setup. | Prevents Report Builder from re-promoting the fallback route. | Low if wording-only. | Yes. |
| OpenAI error wording | Missing API key branch currently mentions preferred constant or current MVP Settings fallback. | Consider follow-up wording so missing errors point to constant first and avoid normalizing Settings storage for public release. | Error messages can otherwise keep the old primary guidance alive. | Low if wording-only. | Local-only missing-branch verification, no external request. |
| Uninstall cleanup review point | Root `uninstall.php` deletes the main settings option. | No narrow change. Reconfirm if fallback storage semantics change later. | Cleanup already removes deterministic plugin-owned options, but it does not decide public-release storage posture. | Low if unchanged. | Source-level only. |
| Readme / privacy / support-debug follow-up points | Disclose external-service and support boundaries. | Plan later wording updates after UI behavior is fixed. Do not update in Step 256 unless explicitly scoped. | Prevents documentation from promising behavior before implementation. | Low if docs-only later. | Review after implementation. |

## Recommended Narrow Implementation Boundary

Recommended boundary for the later Step 256 implementation:

```text
OpenAI Settings fallback legacy/transitional narrow production implementation
```

Recommended implementation posture:

- Keep the resolver source model unchanged for compatibility:
  `constant_configured` first, then existing `settings_saved`, then `missing`.
- Do not change OpenAI request construction, headers, payload construction,
  response handling, model selection, or generated report behavior.
- Treat `settings_saved` as an existing legacy / transitional compatibility
  state, not an ordinary new-user setup route.
- Keep existing `settings_saved` operational during the transition so existing
  MVP installations are not broken by a UI wording step.
- Prefer clear-only handling for existing Settings fallback in normal admin UI:
  value hidden, status shown, clear control scoped to Settings fallback only.
- Do not expose fallback mutation controls as primary guidance when
  `constant_configured` is active.
- In `missing`, guide administrators to constant-based configuration as the
  primary route.
- If normal UI removes the new fallback entry, align the normal save path so it
  does not create a new Settings fallback from hidden or stale form input.
- Keep support/debug evidence status/category-level only.

This recommendation is a plan, not an implementation record. It does not claim
that a developer-only technical gate already exists.

## Migration, Compatibility, and Rollback Plan

### Existing `settings_saved` State

- Preserve resolver compatibility during the first narrow implementation.
- Do not display, copy, log, inspect, migrate, or automatically delete the saved
  fallback value.
- Show source/status labels and value-hidden posture.
- Prefer clear-only UI for transition cleanup.

### Existing `constant_configured` State

- Preserve constant-first resolution.
- Do not mutate constants from Settings.
- If a saved fallback also exists, keep it lower priority and hidden.
- Any clear control must affect only the Settings fallback.

### Existing `missing` State

- Guide to constant-based configuration first.
- Avoid presenting Settings storage as the normal public-release setup route.
- If fallback entry is removed or restricted, verify that missing-state wording
  does not imply a Settings-only path.

### New Installation / No Prior Configuration

- Treat `missing` as the default safe category.
- Provide deployment guidance for the constant source.
- Do not create a stored fallback automatically.

### Rollback From Later Implementation To Current MVP Behavior

- Rollback should be possible by restoring the prior Settings input rendering
  and prior save acceptance behavior.
- Existing option schema should remain unchanged during the narrow
  implementation.
- No migration should be required if the narrow step avoids data conversion.

### Uninstall Relationship

- Root `uninstall.php` already deletes the deterministic main settings option.
- No uninstall change is required for the first narrow implementation.
- If later steps add a separate option, migration record, or retained fallback
  storage, uninstall cleanup must be revisited.

Mandatory mutation boundaries:

```text
No credential values are to be exposed, copied, logged, or manually inspected.
No automatic credential deletion or migration should be introduced without a later explicit implementation decision.
Any mutation of stored fallback data requires a separate, narrowly scoped implementation and controlled verification plan.
```

## Documentation, Privacy, and Support Alignment Plan

| Area | Alignment plan |
|---|---|
| Admin UI wording changes | Use `legacy` / `transitional` wording for Settings fallback unless a real technical gate is implemented. Keep constant-based configuration as the preferred route. |
| `readme.txt` wording changes | Defer until the UI behavior is implemented. Later wording should say constant-based configuration is preferred and Settings fallback is legacy / transitional if retained. |
| Privacy disclosure implications | Keep disclosure that stored credentials may exist in plugin settings when fallback is retained. Do not imply Settings storage is the recommended public-release route. |
| Support/debug wording changes | Continue asking for source category, fallback status, value-hidden status, and safe error categories only. |
| Deployment guidance for constant configuration | Add or refine instructions in a later docs/readme step without recording constant values. |
| Legacy / transitional fallback explanation | Explain that existing fallback may remain for compatibility and transition, but credential values are hidden and support should not ask for them. |

Support/debug evidence must remain status/category-level. Support should not ask
for API keys, constant values, option values, request bodies, raw responses,
payload JSON, generated report bodies, screenshots, or browser Network evidence.

## Verification Plan and Sequence

If a later implementation is approved, verify in this order:

1. Static code review / narrow source-category checks.
2. Controlled local-only state preparation, if needed.
3. Human Settings smoke.
4. Human Report Builder smoke.
5. Transition / cleanup or rollback observation.
6. Readme / privacy / support wording review.
7. Isolated Plugin Check in `wp-dev-check` only.
8. Separate provider/runtime verification track, if later approved.

Human smoke should cover:

- `constant_configured`,
- `settings_saved` legacy / transitional state,
- `missing`,
- value-hidden posture,
- clear-control visibility and scope,
- no Settings-only primary route,
- Settings / Report Builder wording consistency,
- absence of fatal errors.

The same smoke scope must not include:

- real credential validity,
- OpenAI Generate,
- GA4 Fetch,
- OAuth,
- external provider communication,
- screenshots,
- Network evidence,
- option / constant / token value inspection.

## Risks and Decision Dependencies

| Risk / dependency | Impact | Mitigation |
|---|---|---|
| Ambiguity between "developer-only" concept and actual technical enforcement | UI may overclaim a boundary that does not exist. | Use `legacy` / `transitional` wording unless a technical gate is implemented. |
| Existing `settings_saved` compatibility | Removing fallback too quickly could break MVP installations. | Keep existing saved fallback operational during the first transition. |
| Accidental re-promotion of Settings fallback through UI wording | Missing-state or Report Builder copy could keep Settings storage as a normal route. | Make constant guidance primary and fallback wording transitional. |
| Unintended mutation / cleanup of stored fallback data | Stored credentials could be changed or deleted outside user intent. | Do not add automatic deletion or migration in the narrow implementation. |
| Divergence between Settings and Report Builder wording | Users may see conflicting readiness guidance. | Verify both screens in the same human smoke plan. |
| Readme / privacy / support wording drift | Docs may promise behavior not yet implemented. | Update docs/readme after production wording is implemented and verified. |
| Uninstall behavior mismatch | Future storage changes might not be covered by current uninstall cleanup. | Revisit uninstall only if storage shape changes. |
| Need for later isolated Plugin Check | Packaging/readiness may still have findings. | Run Plugin Check only after implementation and wording are aligned. |
| Provider/runtime verification remaining independent | UI category pass could be mistaken for OpenAI success. | Keep provider success, quota, permission, endpoint, and model checks as a separate track. |

## Explicit Non-conclusions

Step 255 does not confirm or record:

- actual API key validity,
- actual constant value,
- actual Settings fallback option value,
- actual Settings fallback storage contents,
- actual constant preservation,
- actual OpenAI request success,
- provider authentication,
- provider quota,
- provider permission,
- endpoint availability,
- model availability,
- WordPress.org review outcome,
- public-release approval.

## Recommended Step 256

Recommended next step:

```text
Step 256 candidate — OpenAI Settings fallback legacy/transitional narrow production implementation
```

Step 256 should implement only the approved narrow boundary. It should not
verify real credentials, run OpenAI Generate, run GA4 Fetch, perform OAuth,
communicate with external providers, inspect option values, update `readme.txt`,
or run Plugin Check unless explicitly scoped in a later step.

## Result Classification

```text
Step 255 result: Planning completed
Recommended disposition input: Option C
Recommended implementation boundary: legacy/transitional narrow production implementation
Resolver behavior change planned for Step 256: No, unless separately approved
Settings fallback public-release posture: legacy / transitional planning target
Existing settings_saved compatibility: Preserve during first transition
New Settings fallback primary guidance: Remove / avoid
Value-hidden posture: Preserve
WordPress.org release readiness: Hold
Production implementation performed: No
External HTTP performed: No
Plugin Check performed: No
Recommended next step: Step 256 candidate — OpenAI Settings fallback legacy/transitional narrow production implementation
```
