# Step 256: OpenAI Settings Fallback Legacy / Transitional Narrow Production Implementation Results

## Step Purpose

Step 256 implements the narrow boundary planned in Step 255:

```text
OpenAI Settings fallback legacy/transitional narrow production implementation
```

The implementation keeps the current OpenAI API key source model:

```text
Constant first / Settings fallback / Missing
```

It removes the normal Settings UI path for creating or replacing a new OpenAI
Settings fallback, while preserving existing `settings_saved` fallback
compatibility, value-hidden posture, and fallback-only clear behavior.

WordPress.org release readiness remains `Hold`.

## Changed Files

Production files changed:

- `includes/class-settings.php`
- `includes/class-report-builder.php`
- `includes/class-openai-client.php`

Docs added:

- `docs/maturation/step256-openai-settings-fallback-legacy-transitional-narrow-production-implementation-results.md`

Files intentionally unchanged:

- `includes/functions-utils.php`
- `readme.txt`
- `uninstall.php`
- `tools/`
- JavaScript
- CSS
- existing docs
- `wp-dev`
- `wp-dev-check`

## Implemented Narrow Boundary

The implementation follows Step 255's Option C planning boundary:

- Resolver source priority is unchanged.
- Existing `settings_saved` fallback remains operational for compatibility.
- New fallback entry / replacement is removed from the normal Settings UI.
- The Settings save path no longer creates or replaces an OpenAI Settings
  fallback from normal `openai_api_key` submissions.
- Explicit `clear_openai_api_key` still deletes only the Settings fallback.
- Value-hidden status labels remain visible.
- Settings and Report Builder wording now describe the Settings fallback as a
  legacy / transitional compatibility state.
- Missing-state guidance now points to the preferred constant-based source.

## Unchanged Resolver / Runtime Boundaries

The following runtime boundaries were not changed:

- `analytics_report_ai_get_openai_api_key_source()` source categories.
- `analytics_report_ai_resolve_openai_api_key()` resolver priority.
- `constant_configured` / `settings_saved` / `missing` category names.
- OpenAI request construction.
- Authorization header construction.
- Endpoint selection.
- Model selection.
- Prompt / payload construction.
- Response parsing.
- Generated report behavior.

`includes/functions-utils.php` was reviewed as the resolver/source helper
location and was not changed.

## Settings Rendering Behavior by State

| State | Settings rendering behavior |
|---|---|
| `constant_configured` + no saved fallback | Shows the preferred constant-based source and does not show a new Settings fallback entry field or clear control. |
| `constant_configured` + saved fallback | Shows the preferred constant-based source, notes that a legacy / transitional Settings fallback is saved and hidden for compatibility, and allows fallback-only clear. |
| `settings_saved` | Shows the saved fallback as a legacy / transitional compatibility state, keeps the value hidden, and encourages migration to the preferred constant-based source. |
| `missing` | Shows missing source status and guides administrators to configure the preferred constant-based source. No new Settings fallback entry field is shown. |

The OpenAI Settings fallback password input was removed from the normal Settings
UI. Stored values are not displayed.

## Normal Save Path Behavior by State

The normal Settings save path no longer reads a non-empty `openai_api_key`
submission as a create / replace instruction.

| Submitted state | Save-path result |
|---|---|
| No `clear_openai_api_key` submitted | Existing Settings fallback is preserved. No new fallback is created from crafted, stale, or hidden `openai_api_key` input. |
| `clear_openai_api_key` submitted | Settings fallback is cleared. Constant-based configuration is not changed. |
| `clear_openai_api_key` and stale non-empty `openai_api_key` submitted together | Clear operation wins; fallback is cleared and the stale value is not used to recreate or replace the fallback. |

No credential values are exposed, copied, logged, inspected, migrated, or
automatically deleted outside the explicit fallback clear path.

## Clear-control Behavior

`clear_openai_api_key` remains:

- Settings fallback only,
- visible only when fallback status is `saved`,
- available even when `constant_configured` is active and a saved fallback also
  exists,
- scoped so constants are not changed,
- value-hidden.

The clear control does not affect OpenAI constants, OAuth data, GA4 credentials,
provider-side access, request payloads, or generated report text.

## Report Builder Wording Behavior by State

Report Builder source-aware wording now distinguishes:

- `constant_configured`: preferred constant-based source is ready for UI
  purposes; source category does not prove provider acceptance or request
  success.
- `settings_saved`: existing legacy / transitional Settings fallback can be
  used for compatibility, while constant-based configuration remains preferred.
- `missing`: configure the preferred constant-based source before generating.

Report Builder no longer guides missing-state administrators to save a current
MVP Settings fallback as the primary readiness path.

## OpenAI Error Wording Alignment

The missing-key `WP_Error` message was narrowed so it points to the preferred
OpenAI API key constant source before generating. This is wording alignment
only. It does not change request execution, request body construction, response
parsing, or error categorization.

## Preservation of Value-hidden Posture

Value-hidden posture is preserved:

- no stored OpenAI API key value is printed in Settings,
- no constant value is printed,
- no option value is printed,
- no fallback value is used as support/debug evidence,
- status/category labels remain the visible evidence boundary.

## No Automatic Migration or Deletion

The implementation does not:

- automatically migrate Settings fallback values,
- automatically delete existing fallback values,
- copy fallback values into constants,
- display stored fallback values,
- inspect option values,
- change uninstall cleanup,
- create a new option,
- change the settings schema.

Existing `settings_saved` fallback remains a compatibility source until the
administrator explicitly clears it or a later step defines a separate migration
or retirement path.

## Static Verification Performed

Verification was limited to source-level and local syntax checks:

```text
php -l includes/class-settings.php
php -l includes/class-report-builder.php
php -l includes/class-openai-client.php
find includes -name '*.php' -print0 | xargs -0 -n1 php -l
git diff --check
git diff --stat
git diff --name-only
git status --short --untracked-files=all
```

Source-level inspection confirmed:

- normal OpenAI Settings fallback password input is no longer rendered,
- `openai_api_key` is no longer used by `sanitize_settings()` to create or
  replace a fallback,
- `clear_openai_api_key` remains fallback-only,
- resolver helpers were not changed,
- OpenAI request construction was not changed.

## Verification Intentionally Not Performed

The following were not performed:

- browser admin smoke,
- Settings save through the browser,
- `clear_openai_api_key` operation through the browser,
- OpenAI Generate,
- GA4 Fetch,
- OAuth Connect / Authorize,
- external HTTP,
- provider communication,
- Plugin Check,
- screenshots,
- browser Network evidence collection,
- option value inspection,
- constant value inspection,
- credential or token value inspection.

## Credential and Evidence Boundary

This step did not display or record:

- actual OpenAI API key values,
- actual constant values,
- Settings fallback option values,
- OAuth token values,
- credentials,
- Authorization headers,
- request bodies,
- raw responses,
- AI payload JSON,
- generated report bodies,
- screenshots,
- browser Network evidence,
- database dumps.

## WordPress.org Release Readiness

WordPress.org release readiness remains:

```text
Hold
```

This implementation narrows the Settings fallback public-release posture but
does not verify actual provider behavior, public-release approval, Plugin Check
status, policy compliance, or real OpenAI request success.

## Recommended Next Step

Recommended next step:

```text
Step 257 candidate — OpenAI Settings fallback legacy/transitional source-level verification
```

Step 257 should verify the Step 256 source-level implementation without
browser smoke, external HTTP, provider communication, credential inspection, or
Plugin Check unless a later step explicitly scopes those actions.

## Result Classification

```text
Step 256 status: Implemented
Resolver priority: Unchanged
OpenAI runtime request behavior: Unchanged
New Settings fallback entry in normal UI: Removed
Normal save path fallback create/replace: Disabled
Existing settings_saved compatibility: Preserved
Clear control: Fallback-only
Value-hidden posture: Preserved
Browser human smoke: Not performed
Plugin Check: Not performed
External provider communication: Not performed
WordPress.org release readiness: Hold
Recommended next step: Step 257 candidate — OpenAI Settings fallback legacy/transitional source-level verification
```
