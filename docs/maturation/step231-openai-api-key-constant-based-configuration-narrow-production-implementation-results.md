# Step 231: OpenAI API Key Constant-based Configuration Narrow Production Implementation Results

## Step Purpose

Step 231 implements the narrow production boundary planned in Step 230 for
OpenAI API key constant-based configuration.

The implementation makes `ANALYTICS_REPORT_AI_OPENAI_API_KEY` the preferred
OpenAI API key source, retains the Settings-saved OpenAI API key as fallback,
keeps saved values hidden, and preserves the administrator-triggered Generate
AI Report request boundary.

WordPress.org release readiness remains `Hold`.

## Implementation Summary

Implemented:

- OpenAI API key source priority:
  1. `ANALYTICS_REPORT_AI_OPENAI_API_KEY` constant, if configured and non-empty,
  2. Settings-saved OpenAI API key fallback, if saved and non-empty,
  3. missing.
- Safe source/category helpers for OpenAI API key source status.
- Request-local OpenAI API key resolver for Generate AI Report runtime use.
- Settings UI status/category labels for active source, Settings fallback
  status, and value-hidden status.
- Report Builder status/category display for OpenAI API key source readiness.
- OpenAI client key lookup through the resolver instead of direct Settings-only
  access.

Not implemented:

- `readme.txt` wording update,
- browser admin smoke,
- Plugin Check,
- external API calls,
- OpenAI Generate execution,
- GA4 Fetch execution,
- Settings fallback migration or deletion,
- generated report persistence changes,
- payload or request body changes.

## Changed Files

| File | Change summary |
|---|---|
| `includes/functions-utils.php` | Added OpenAI API key constant name, source category, request-local resolver, and lifecycle/category helpers. |
| `includes/class-settings.php` | Added Settings UI source/category labels and wording for constant-first OpenAI API key behavior and Settings fallback clear scope. |
| `includes/class-report-builder.php` | Replaced Settings-only OpenAI key status with source/category-level OpenAI key readiness labels. |
| `includes/class-openai-client.php` | Uses the resolver to obtain request-local OpenAI API key material and updates the missing-key message to include constant or fallback configuration. |
| `docs/maturation/step231-openai-api-key-constant-based-configuration-narrow-production-implementation-results.md` | Records Step 231 implementation results and boundaries. |

Explicit unchanged boundary files:

- `readme.txt`
- `uninstall.php`
- `tools/`
- `assets/`
- `includes/class-ga4-client.php`

## Constant Source Priority Implementation

Implemented constant name category:

```text
ANALYTICS_REPORT_AI_OPENAI_API_KEY
```

Source priority:

```text
OpenAI API key source priority: Constant first / Settings fallback / Missing
```

The constant value is not stored back into plugin settings. It is treated as
request-local credential material and is not displayed in admin UI, docs,
logs, support/debug evidence, or command output.

## Helper / Resolver Implementation

Added helpers:

| Helper | Purpose |
|---|---|
| `analytics_report_ai_get_openai_api_key_constant_name()` | Returns the OpenAI API key constant name without reading its value. |
| `analytics_report_ai_get_openai_api_key_source()` | Returns safe source/category metadata without credential values. |
| `analytics_report_ai_resolve_openai_api_key()` | Resolves request-local OpenAI API key material using constant-first priority. |
| `analytics_report_ai_get_openai_api_key_lifecycle_categories()` | Returns status/category labels for Settings and Report Builder UI. |

Implemented safe categories:

```text
openai_api_key_source_category: constant_configured
openai_api_key_source_category: settings_saved
openai_api_key_source_category: missing
openai_api_key_value_visibility: hidden
openai_api_key_settings_fallback_status: saved
openai_api_key_settings_fallback_status: not_saved
```

The source/category helper does not return credential values. The resolver
returns credential material only for immediate runtime request use.

## Settings UI Status/category Implementation

Settings UI now displays:

- active OpenAI API key source category,
- Settings fallback status category,
- value display status category.

Implemented UI boundary:

- constant configured state is shown only as status/category-level wording,
- constant value is never displayed,
- constant value is not placed in the password field `value`,
- constant value is not used as a placeholder or description fragment,
- Settings fallback field remains available,
- Settings fallback is described as lower priority when a constant is active,
- Settings-saved fallback value remains hidden,
- clear checkbox appears only for Settings-saved fallback state,
- clear checkbox wording states that constant-based configuration is not
  changed.

## Settings Save / Existing Key Handling

Preserved behavior:

- existing saved Settings fallback key is not displayed,
- empty input preserves the Settings-saved fallback key,
- entering a new value replaces only the Settings-saved fallback key,
- clear checkbox deletes only the Settings-saved fallback key,
- Settings save never modifies or deletes the constant,
- existing saved Settings key is preserved unless clear is explicitly checked,
- no migration or automatic deletion is implemented in Step 231.

## OpenAI Client / Report Builder Boundary

OpenAI client:

- now resolves the key through `analytics_report_ai_resolve_openai_api_key()`,
- receives request-local key material only inside Generate AI Report execution,
- preserves OpenAI request body structure,
- preserves response parsing and error category handling.

Report Builder:

- displays OpenAI API key source as safe category labels,
- no longer assumes Settings-saved key is the only possible source,
- continues to treat missing key as status/category-level readiness warning.

Preserved boundaries:

- AI payload structure is unchanged,
- OpenAI request body structure is unchanged,
- generated report body storage posture remains non-storage,
- GA4 client and GA4 Fetch behavior are unchanged,
- no Authorization header, request body, raw response, payload JSON, or
  generated report body is recorded.

## Explicit Unchanged Boundaries

Step 231 did not change:

- `readme.txt`,
- `uninstall.php`,
- `tools/`,
- `assets/`,
- `includes/class-ga4-client.php`,
- GA4 request construction,
- OpenAI request body structure,
- AI payload structure,
- generated report persistence behavior,
- OAuth token endpoint behavior,
- refresh request behavior,
- provider-side revoke behavior,
- local-only disconnect implementation,
- uninstall cleanup implementation.

## Forbidden Evidence Boundary

Step 231 records only source file names, function/method names, option key
names, UI field names, constant name categories, source categories, storage
categories, wording categories, docs-level references, status/category-level
conclusions, file-level change summaries, and command result categories.

Step 231 does not record:

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

## Commands Executed

Verification commands executed for this step:

```bash
php -l includes/functions-utils.php
php -l includes/class-settings.php
php -l includes/class-report-builder.php
php -l includes/class-openai-client.php
find includes -name '*.php' -print0 | xargs -0 -n1 php -l
rg -n "ANALYTICS_REPORT_AI_OPENAI_API_KEY|openai_api_key|OpenAI API Key|clear_openai_api_key|constant_configured|settings_saved|missing|Authorization|payload|generated report|support|debug" includes
git diff --check
git diff --name-only
git diff --name-only -- readme.txt uninstall.php tools assets includes/class-ga4-client.php
git status --short --untracked-files=all
```

Result categories:

| Command | Result |
|---|---|
| `php -l includes/functions-utils.php` | Pass |
| `php -l includes/class-settings.php` | Pass |
| `php -l includes/class-report-builder.php` | Pass |
| `php -l includes/class-openai-client.php` | Pass |
| `find includes -name '*.php' -print0 | xargs -0 -n1 php -l` | Pass |
| `rg -n ... includes` | Completed; source/file/symbol/category-level matches only. |
| `git diff --check` | Pass |
| `git diff --name-only` | Tracked PHP changes only. New docs file appears in `git status`. |
| `git diff --name-only -- readme.txt uninstall.php tools assets includes/class-ga4-client.php` | Pass; no output. |
| `git status --short --untracked-files=all` | Expected modified PHP files and new Step 231 docs file only. |

## Acceptance Criteria

| Acceptance criterion | Status |
|---|---|
| constant-based source priority is implemented | Pass |
| `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is used consistently | Pass |
| Settings fallback behavior is explicit | Pass |
| Settings fallback remains compatible | Pass |
| constant value is never displayed | Pass |
| Settings-saved value is never redisplayed | Pass |
| clear control affects only Settings fallback key | Pass |
| Settings save never modifies or deletes the constant | Pass |
| existing saved Settings key is preserved unless clear is explicitly requested | Pass |
| OpenAI client receives key material only for administrator-triggered Generate AI Report | Pass |
| missing key result is status/category-level | Pass |
| generated report body storage posture remains non-storage | Pass |
| AI payload structure and OpenAI request body structure are unchanged | Pass |
| GA4 client and GA4 Fetch behavior are unchanged | Pass |
| no Authorization header / request body / raw response / payload JSON / generated report body is recorded | Pass |
| production changes are limited to intended PHP files | Pass |
| `readme.txt` update is deferred to later wording step | Pass |
| WordPress.org release readiness remains Hold | Pass |

## Result Classification

```text
OpenAI API key constant-based configuration narrow production implementation: Completed
Constant name: ANALYTICS_REPORT_AI_OPENAI_API_KEY
OpenAI API key source priority: Constant first / Settings fallback / Missing
Settings fallback behavior: Retained / Explicit
Existing saved OpenAI API key handling: Preserve / Never redisplay
Settings clear control: Settings fallback only / Implemented
OpenAI request boundary: Administrator-triggered Generate AI Report only / Preserved
Generated report body storage posture: Non-storage / Preserved
Readme/privacy wording: Deferred
Production code changes: Implemented narrowly
WordPress.org release readiness: Hold
```

## Recommended Next Step

Recommended next step:

```text
Step 232: OpenAI API key constant-based configuration source-level verification
```

Step 232 should be docs-only / source-level verification. It should verify the
constant-first resolver, Settings fallback preservation, value-hidden UI,
OpenAI client boundary, unchanged GA4/readme/uninstall/assets/tools boundaries,
and forbidden evidence posture without running external API calls or browser
smoke.
