# Step 230: OpenAI API Key Constant-based Configuration Implementation Plan

## Step Purpose

Step 230 is a docs-only and planning-only implementation plan for the
OpenAI API key constant-based configuration track selected in Step 229.

The purpose is to define a narrow production implementation path where an
OpenAI API key configured by constant is preferred over the Settings-saved
fallback, while preserving the current non-redisplay, support-safe evidence,
and administrator-triggered OpenAI request boundaries.

This step does not implement the constant, resolver, Settings UI changes,
OpenAI client changes, `readme.txt` updates, or verification smoke. It prepares
the implementation boundary for Step 231.

## Scope

In scope:

- target OpenAI API key source priority,
- proposed constant name category,
- source-level implementation areas,
- helper / resolver design,
- Settings UI status/category plan,
- Settings save and existing saved-key handling plan,
- OpenAI client and Report Builder boundary plan,
- readme/privacy wording follow-up plan,
- verification sequence,
- Step 231 acceptance criteria,
- forbidden evidence boundary.

Out of scope:

- production code changes,
- `readme.txt` changes,
- Settings UI behavior changes,
- credential resolver changes,
- OpenAI client changes,
- GA4 client changes,
- `uninstall.php` changes,
- tools, JavaScript, or CSS changes,
- Plugin Check,
- browser admin smoke,
- external API calls,
- option value inspection,
- release-readiness approval.

## Explicit Non-goals

Step 230 does not:

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

- `docs/maturation/step229-openai-api-key-storage-public-release-decision-checkpoint.md`
- `docs/maturation/step228-openai-api-key-storage-posture-checkpoint.md`
- `docs/maturation/step227-readme-privacy-wording-alignment-after-manual-token-retirement-maturation-checkpoint.md`
- `docs/maturation/step226-readme-privacy-wording-alignment-after-manual-token-retirement-source-level-verification-results.md`
- `docs/maturation/step225-readme-privacy-wording-alignment-after-manual-token-retirement-implementation-results.md`
- `docs/maturation/step223-manual-google-access-token-fallback-retirement-maturation-checkpoint.md`
- `docs/maturation/step215-uninstall-cleanup-maturation-checkpoint.md`
- `docs/maturation/step209-credential-storage-public-release-posture-checkpoint.md`
- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`

## Decision Inputs From Step 229

Step 229 selected Option B as the public-release target:

```text
Decision: Select Option B as public-release target
Recommended public-release posture: Constant-based OpenAI API key configuration preferred over settings storage
Settings storage posture: Fallback / Needs implementation plan
Existing saved OpenAI API key handling: Preserve until explicit implementation decision / Never redisplay
OpenAI API key support evidence boundary: Status/category-level only / Preserved
OpenAI API request boundary: Administrator-triggered Generate AI Report only / Preserved
Generated report body storage posture: Non-storage / Preserved
WordPress.org release readiness: Hold
```

Step 230 converts that decision into an implementation plan while keeping
production behavior unchanged.

## Target Source Priority

Planned OpenAI API key source priority:

```text
1. constant-based configuration
2. Settings-saved fallback, if retained
3. missing
```

Target behavior:

- constant-based configuration is preferred whenever configured and non-empty,
- Settings-saved key remains a fallback unless a later implementation decision
  removes or restricts it,
- existing Settings-saved keys remain compatible and are never redisplayed,
- missing-key behavior remains status/category-level and user-facing,
- no credential value is printed to admin UI, docs, logs, support evidence, or
  verification output.

## Proposed Constant Name Category

Proposed constant name:

```text
ANALYTICS_REPORT_AI_OPENAI_API_KEY
```

Step 231 should use this name consistently unless implementation review finds a
clear naming conflict.

The constant value must be treated as request-local credential material:

- never displayed in Settings UI,
- never used as a placeholder,
- never included in support/debug output,
- never included in docs as a concrete value,
- never logged,
- never exposed through screenshots or Network evidence.

## Source-level Implementation Areas

Planned implementation areas:

| Area | Source-level reference | Planned role |
|---|---|---|
| Settings/default normalization | `includes/functions-utils.php`, `analytics_report_ai_get_default_settings()`, `analytics_report_ai_get_settings()` | Preserve Settings fallback storage and normalized key category. Add resolver helpers near existing credential helpers. |
| Constant value helper pattern | `includes/functions-utils.php`, `analytics_report_ai_get_non_empty_constant_value()` | Reuse the existing request-local constant-value pattern for the new OpenAI key resolver. |
| Settings save handling | `includes/class-settings.php`, `sanitize_settings()` | Preserve empty-input and clear-control behavior for Settings fallback only. Do not modify constants. |
| Settings UI | `includes/class-settings.php`, Settings page | Add source/category and value-hidden wording; ensure constant values and Settings values are never redisplayed. |
| Report Builder source guidance | `includes/class-report-builder.php` | Replace simple saved/not-saved OpenAI key wording with source/category-aware guidance if needed. |
| OpenAI request boundary | `includes/class-openai-client.php`, `generate_report()` | Resolve the request-local key through the helper or receive a resolved request-local key without exposing values. |
| Readme/privacy wording | `readme.txt` | Defer wording update until after implementation and source-level verification. |
| Maturation docs | `docs/maturation/` | Record implementation, verification, admin smoke, wording follow-up, and maturation checkpoint. |

The implementation should avoid changing GA4 client behavior, AI payload
structure, generated report persistence, transient behavior, or OAuth token
lifecycle behavior.

## Helper / Resolver Design Plan

Step 231 should add small helpers following the existing plugin prefix and
credential-helper style.

Candidate helper names:

```text
analytics_report_ai_get_openai_api_key_source()
analytics_report_ai_resolve_openai_api_key()
analytics_report_ai_get_openai_api_key_lifecycle_categories()
```

Actual names can be adjusted to match local patterns, but the responsibilities
should remain narrow.

### `analytics_report_ai_get_openai_api_key_source()`

Planned responsibility:

- detect whether the proposed OpenAI API key constant is defined and non-empty,
- detect whether a Settings-saved fallback key category exists,
- classify the source without exposing values,
- return a safe source category for UI/support.

Planned categories:

```text
constant_configured
settings_saved
missing
```

The helper may also return fallback status categories such as:

```text
settings_fallback_status: saved
settings_fallback_status: not_saved
```

### `analytics_report_ai_resolve_openai_api_key()`

Planned responsibility:

- resolve the request-local OpenAI API key value using source priority,
- return only what runtime needs for the Generate AI Report request,
- avoid logging, rendering, or recording the value,
- preserve missing-key behavior as a safe status/category-level error.

Planned source priority:

```text
constant value if configured and non-empty
Settings-saved fallback value if retained and non-empty
empty string / missing category
```

The resolved key value should remain request-local and should not be written
back to plugin settings.

### `analytics_report_ai_get_openai_api_key_lifecycle_categories()`

Planned responsibility:

- provide safe Settings / Report Builder labels,
- report source category,
- report value visibility category,
- report fallback availability category,
- avoid value output.

Candidate labels:

```text
openai_api_key_source_category: constant_configured
openai_api_key_source_category: settings_saved
openai_api_key_source_category: missing
openai_api_key_value_visibility: hidden
openai_api_key_settings_fallback_status: saved
openai_api_key_settings_fallback_status: not_saved
```

This helper should support admin UI and support/debug guidance without exposing
the key itself.

## Settings UI Plan

Planned Settings UI posture:

- If the constant is configured, show only a safe source/category label such as
  `openai_api_key_source_category: constant_configured`.
- Do not display the constant value.
- Do not place the constant value in the field `value`.
- Do not use the constant value as a placeholder or description fragment.
- Always keep value visibility as `hidden`.
- If Settings fallback remains available, explain that it is lower priority
  than the constant.
- If a Settings-saved fallback key exists, show only saved/not-saved and
  value-hidden state.
- If a constant is configured and a Settings fallback also exists, explain that
  the constant is active and the Settings fallback is lower-priority stored
  fallback material.
- Keep the UI status/category-level only.

Potential visible wording categories:

```text
OpenAI API key source: constant configured
OpenAI API key source: Settings fallback saved
OpenAI API key source: missing
OpenAI API key value: hidden
Settings fallback status: saved / not saved
```

Settings UI should also clarify:

- clearing the Settings fallback does not delete or modify the constant,
- constants cannot be deleted from the plugin UI,
- Settings fallback storage remains in the WordPress database if retained,
- database administrators, backups, server administrators, or code that can
  read WordPress options may access Settings fallback credential categories.

## Settings Save / Existing Key Handling Plan

Settings fallback posture for Step 231:

```text
Settings storage fallback: retained pending implementation
```

Planned save behavior:

- existing saved Settings fallback key is never redisplayed,
- empty input preserves the existing Settings-saved fallback key if fallback is
  retained,
- entering a new value replaces only the Settings-saved fallback key,
- clear checkbox deletes only the Settings-saved fallback key,
- constant-based key is never modified by Settings save,
- constant-based key is never cleared by the Settings fallback clear control,
- migration/deletion of existing saved Settings keys is not implemented in
  Step 231 unless a later request explicitly changes scope.

Existing saved-key category handling:

```text
existing_settings_saved_key: preserve
existing_settings_saved_key_value: never redisplay
settings_fallback_clear_control: settings fallback only
constant_value_mutation_by_settings: no
```

Step 231 should add or update admin wording only as needed to make those
boundaries visible.

## OpenAI Client / Report Builder Boundary Plan

Planned OpenAI request boundary:

- resolve the API key only when Generate AI Report is executed,
- keep the request tied to administrator-triggered Generate AI Report,
- pass only request-local key material to the OpenAI request path,
- do not store the resolved constant value in plugin settings,
- do not change AI payload structure,
- do not change OpenAI request body structure,
- do not change generated report body storage behavior,
- do not change GA4 fetch behavior.

Planned missing-key behavior:

- return a safe missing-key error category if neither source is available,
- avoid mentioning or exposing any key value,
- update user-facing wording if needed so it does not imply Settings is the
  only possible source after constants are introduced.

Planned Report Builder guidance:

- show source/category-level readiness, if useful,
- preserve "Generate AI Report" as the only OpenAI request trigger,
- preserve support guidance that users should not share API keys,
  Authorization headers, option values, request bodies, raw responses, AI
  payload JSON, or generated report bodies.

## Readme/privacy Wording Follow-up Plan

Step 230 does not change `readme.txt`.

After implementation and source-level verification, a wording alignment step
should update `readme.txt` and related privacy/support wording to explain:

- constant-based OpenAI API key configuration is preferred,
- Settings storage is fallback and carries database/options exposure risk,
- saved values are not redisplayed,
- Settings clear control deletes only the Settings fallback key,
- Settings clear control does not delete or modify constants,
- OpenAI requests remain administrator-triggered through Generate AI Report,
- support should not include API keys, Authorization headers, option values,
  request bodies, raw responses, AI payload JSON, or generated report bodies.

The wording step should continue to avoid recording concrete credential values,
option values, hostnames, analytics values, request bodies, raw responses,
payload JSON, screenshots, or Network evidence.

## Verification Sequence

Recommended sequence:

| Step | Purpose | Expected scope |
|---|---|---|
| Step 231 | OpenAI API key constant-based configuration narrow production implementation | PHP-only narrow implementation; no readme update. |
| Step 232 | OpenAI API key constant-based configuration source-level verification | Docs-only / source-level verification after implementation. |
| Step 233 | OpenAI API key source human admin smoke plan | Docs-only / planning-only browser smoke plan. |
| Step 234 | OpenAI API key source controlled human admin smoke results | Docs-only human-provided status/category-level results. |
| Step 235 | Readme/privacy wording alignment after OpenAI key constant implementation | Wording-only readme/privacy follow-up. |
| Step 236 | Source-level verification for readme/privacy wording | Docs-only verification of wording boundary. |
| Step 237 | OpenAI API key storage constant configuration maturation checkpoint | Docs-only checkpoint for current MVP boundary. |

Production implementation and verification should remain separated.

## Acceptance Criteria For Step 231

Step 231 should be accepted only if all relevant criteria are met:

- constant-based source priority is implemented,
- proposed constant name `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is used
  consistently,
- Settings fallback behavior is explicit,
- Settings fallback remains compatible unless explicitly removed later,
- constant value is never displayed,
- Settings-saved value is never redisplayed,
- clear control affects only the Settings fallback key,
- Settings save never modifies or deletes the constant,
- existing saved Settings key is preserved unless clear is explicitly requested,
- OpenAI client receives key material only for administrator-triggered Generate
  AI Report,
- missing key result is status/category-level,
- generated report body storage posture remains non-storage,
- AI payload structure and OpenAI request body structure are unchanged,
- GA4 client and GA4 Fetch behavior are unchanged,
- no Authorization header, request body, raw response, payload JSON, or
  generated report body is recorded,
- production changes are limited to intended PHP files,
- `readme.txt` update is deferred to a later wording step,
- WordPress.org release readiness remains `Hold`.

## Forbidden Evidence Boundary

Allowed evidence:

- source file name,
- function / method / option key name,
- UI field name,
- proposed constant name category,
- storage category,
- wording category,
- docs-level reference,
- status/category-level conclusion,
- planned / deferred / retained / rejected classification,
- file-level change summary,
- command result category.

Forbidden evidence:

- option values,
- credential values,
- API keys,
- access token values,
- refresh token values,
- OAuth client ID values,
- OAuth client secret values,
- Authorization headers,
- serialized option values,
- database row contents,
- request bodies,
- raw responses,
- AI payload JSON,
- generated report bodies,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces,
- GA4 Property ID values,
- hostname/domain values,
- analytics values.

## Recommended Next Step

Recommended next step:

```text
Step 231: OpenAI API key constant-based configuration narrow production implementation
```

Step 231 should be a narrow PHP-only implementation step unless a later user
request changes the scope. It should not update `readme.txt`, run external API
flows, run Plugin Check, perform browser smoke, or record forbidden evidence.

## Result Classification

```text
OpenAI API key constant-based configuration implementation plan: Completed
Decision basis: Step 229 Option B selected
Proposed constant name: ANALYTICS_REPORT_AI_OPENAI_API_KEY
OpenAI API key source priority: Constant first / Settings fallback / Missing
Settings storage posture: Fallback retained pending implementation
Existing saved OpenAI API key handling: Preserve / Never redisplay
Settings clear control: Settings fallback only / Planned
OpenAI request boundary: Administrator-triggered Generate AI Report only / Preserved
Readme/privacy wording: Future alignment after implementation
Production code changes: Not implemented in Step 230
WordPress.org release readiness: Hold
```
