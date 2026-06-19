# Step 232: OpenAI API Key Constant-based Configuration Source-level Verification Results

## Step Purpose

Step 232 is a docs-only / source-level verification step for the Step 231
OpenAI API key constant-based configuration implementation.

The purpose is to verify that Step 231 stayed within the Step 230 narrow
implementation plan and that it preserves the public-release decision from Step
229:

```text
OpenAI API key source priority: Constant first / Settings fallback / Missing
Constant name: ANALYTICS_REPORT_AI_OPENAI_API_KEY
Settings fallback behavior: Retained / Explicit
Existing saved OpenAI API key handling: Preserve / Never redisplay
WordPress.org release readiness: Hold
```

Step 232 does not modify production code.

## Verification Scope

In scope:

- constant-first source priority verification,
- helper / resolver source-level verification,
- Settings UI source/category and value-hidden verification,
- Settings save and existing-key handling verification,
- OpenAI client and Report Builder boundary verification,
- unchanged boundary verification,
- forbidden evidence verification,
- command-result verification.

Out of scope:

- production code changes,
- `readme.txt` changes,
- Settings UI changes,
- credential resolver changes,
- OpenAI client changes,
- GA4 client changes,
- `uninstall.php` changes,
- tools, JavaScript, or CSS changes,
- Plugin Check,
- browser admin smoke,
- GA4 Fetch,
- OpenAI Generate,
- external API calls,
- option value inspection.

## Explicit Non-goals

Step 232 does not:

- add or modify production PHP,
- change `readme.txt`,
- change Settings UI,
- change credential resolver behavior,
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

- `docs/maturation/step231-openai-api-key-constant-based-configuration-narrow-production-implementation-results.md`
- `docs/maturation/step230-openai-api-key-constant-based-configuration-implementation-plan.md`
- `docs/maturation/step229-openai-api-key-storage-public-release-decision-checkpoint.md`
- `docs/maturation/step228-openai-api-key-storage-posture-checkpoint.md`
- `docs/maturation/step227-readme-privacy-wording-alignment-after-manual-token-retirement-maturation-checkpoint.md`
- `docs/maturation/step215-uninstall-cleanup-maturation-checkpoint.md`
- `docs/maturation/step209-credential-storage-public-release-posture-checkpoint.md`

## Source-level Verification Method

Verification used source/file/symbol/category-level evidence only:

- PHP syntax checks,
- source review of Step 231 diff,
- targeted source reads of helper, Settings UI, Report Builder, and OpenAI
  client boundaries,
- `rg` search for symbol/category-level references,
- diff checks for unchanged boundary files.

No option values, credential values, API keys, Authorization headers, request
bodies, raw responses, AI payload JSON, generated report bodies, screenshots,
browser Network evidence, database rows, hostnames, GA4 identifiers, or
analytics values were inspected or recorded.

## Constant-first Source Priority Verification

| Check | Status | Source-level evidence |
|---|---|---|
| Proposed constant name is used consistently | Pass | `analytics_report_ai_get_openai_api_key_constant_name()` returns the constant name category `ANALYTICS_REPORT_AI_OPENAI_API_KEY`. |
| Constant configured category exists | Pass | `analytics_report_ai_get_openai_api_key_source()` returns `source_category: constant_configured` when the constant source is non-empty. |
| Constant has priority over Settings fallback | Pass | `analytics_report_ai_get_openai_api_key_source()` returns immediately after constant source classification. |
| Settings fallback category exists | Pass | `analytics_report_ai_get_openai_api_key_source()` returns `source_category: settings_saved` when no constant source is active and the Settings fallback exists. |
| Missing category exists | Pass | Source helper defaults to `source_category: missing`. |
| Constant value is not written back to settings | Pass | Resolver reads request-local constant value and does not call settings update APIs. |
| Constant / Settings values are not displayed | Pass | UI receives categories from `analytics_report_ai_get_openai_api_key_lifecycle_categories()` and password field value remains empty. |

Result:

```text
OpenAI API key source priority: Constant first / Settings fallback / Missing / Verified
```

## Helper / Resolver Verification

Verified helpers:

| Helper | Verification status | Notes |
|---|---|---|
| `analytics_report_ai_get_openai_api_key_constant_name()` | Pass | Returns the constant name category only. |
| `analytics_report_ai_get_openai_api_key_source()` | Pass | Returns source/status booleans and categories; does not return credential values. |
| `analytics_report_ai_resolve_openai_api_key()` | Pass | Returns request-local key material using constant-first priority for runtime use. |
| `analytics_report_ai_get_openai_api_key_lifecycle_categories()` | Pass | Returns UI/support-safe category labels only. |

Safe categories verified:

```text
openai_api_key_source_category: constant_configured
openai_api_key_source_category: settings_saved
openai_api_key_source_category: missing
openai_api_key_value_visibility: hidden
openai_api_key_settings_fallback_status: saved
openai_api_key_settings_fallback_status: not_saved
```

Verification conclusions:

- source/category helper does not return credential values,
- request-local resolver is the only helper that returns key material,
- resolver priority is constant first / Settings fallback / missing,
- lifecycle categories are safe for UI/support,
- value visibility is classified as `hidden`,
- Authorization headers, request bodies, raw responses, payload JSON, and
  generated report bodies are not recorded by this verification.

## Settings UI Verification

| Check | Status | Source-level evidence |
|---|---|---|
| Constant configured state is status/category-level | Pass | Settings UI displays `openai_api_key_source_category`. |
| Constant value is not displayed | Pass | Only constant name category and status/category labels are shown. |
| Constant value is not placed in password field value | Pass | OpenAI API key password input uses `value=""`. |
| Constant value is not used as placeholder/description fragment | Pass | Placeholder describes Settings fallback saved/not-saved state only. |
| Settings fallback is explained as lower priority | Pass | Settings UI wording says constant source is active and Settings fallback is lower priority when applicable. |
| Settings-saved fallback value is not redisplayed | Pass | UI uses saved/hidden wording and empty password input. |
| Constant + Settings fallback saved state is explained | Pass | UI describes constant as active and Settings fallback as saved/hidden lower-priority fallback. |
| `clear_openai_api_key` scope is Settings fallback only | Pass | Clear label says it deletes the saved Settings fallback OpenAI API key. |
| Clear control does not delete constants | Pass | Clear label says constant-based configuration is not changed. |
| Credential / option values are not displayed | Pass | UI uses status/category labels and empty value attributes. |

## Settings Save / Existing Key Handling Verification

| Check | Status | Source-level evidence |
|---|---|---|
| Empty input preserves existing Settings fallback key | Pass | `sanitize_settings()` keeps existing `openai_api_key` unless clear is checked or a new value is entered. |
| New input replaces only Settings fallback key | Pass | `sanitize_settings()` assigns sanitized input to `openai_api_key`. |
| Clear checkbox deletes only Settings fallback key | Pass | `clear_openai_api_key` sets only `openai_api_key` to empty. |
| Settings save never modifies/deletes constant | Pass | Settings save path does not reference the OpenAI API key constant. |
| Existing Settings key is preserved unless clear is checked | Pass | Existing settings are used as the base before selective updates. |
| Migration / automatic deletion is not implemented | Pass | No migration or automatic deletion path is present in Step 231 changes. |
| Saved Settings key value is never redisplayed | Pass | Settings UI password input remains empty and uses hidden/saved wording. |

## OpenAI Client / Report Builder Boundary Verification

| Check | Status | Source-level evidence |
|---|---|---|
| OpenAI client uses resolver | Pass | `Analytics_Report_AI_OpenAI_Client::generate_report()` calls `analytics_report_ai_resolve_openai_api_key()`. |
| Runtime boundary remains Generate AI Report | Pass | Report Builder calls OpenAI client only from `handle_generate_ai_report()`. |
| Request-local key value is passed only to OpenAI request path | Pass | Resolver result is used inside OpenAI client runtime flow only. |
| Constant value is not written back to settings | Pass | OpenAI client and resolver do not update plugin settings. |
| AI payload structure unchanged | Pass | Step 231 did not modify formatter, prompt builder, or payload validation structure. |
| OpenAI request body structure unchanged | Pass | Step 231 changed key resolution only; request body fields are unchanged. |
| Generated report storage unchanged | Pass | Generated report display/non-storage flow is unchanged. |
| Missing key result is status/category-level | Pass | Missing-key error uses a safe error code and user-facing configuration category wording. |
| Report Builder is not Settings-only for OpenAI key state | Pass | Current Settings table now shows OpenAI key source/category labels. |
| Forbidden evidence not recorded | Pass | Verification records no API key, Authorization header, request body, response body, payload JSON, or generated report body. |

Follow-up noted:

```text
OpenAI error wording source-awareness: Needs follow-up wording alignment
```

Reason:

- Step 231 updated the missing-key generation error to mention the constant or
  Settings fallback path.
- Existing OpenAI API error wording for invalid/missing key categories still
  contains Settings-oriented guidance.
- This does not break the constant-first source priority implementation, but it
  should be aligned in a later wording step so error guidance fully matches the
  new source model.

## Unchanged Boundary Verification

Diff boundary check:

```text
git diff --name-only -- readme.txt uninstall.php tools assets includes/class-ga4-client.php
```

Result:

```text
Pass / no output
```

Unchanged files / directories:

- `readme.txt`
- `uninstall.php`
- `tools/`
- `assets/`
- `includes/class-ga4-client.php`

Preserved behavior:

- GA4 request construction unchanged,
- GA4 Fetch behavior unchanged,
- AI payload structure unchanged,
- OpenAI request body structure unchanged,
- generated report persistence behavior unchanged,
- OAuth token endpoint behavior unchanged,
- refresh request behavior unchanged,
- provider-side revoke behavior unchanged,
- local-only disconnect implementation unchanged,
- uninstall cleanup implementation unchanged.

## Forbidden Evidence Verification

Step 232 did not display, inspect, or record:

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

Evidence recorded is limited to source file names, function/method names,
option key names, UI field names, constant name categories, source categories,
storage categories, wording categories, docs-level references,
status/category-level conclusions, file-level change summaries, and command
result categories.

## Commands Executed

```bash
php -l includes/functions-utils.php
php -l includes/class-settings.php
php -l includes/class-report-builder.php
php -l includes/class-openai-client.php
find includes -name '*.php' -print0 | xargs -0 -n1 php -l
rg -n "ANALYTICS_REPORT_AI_OPENAI_API_KEY|analytics_report_ai_get_openai_api_key_constant_name|analytics_report_ai_get_openai_api_key_source|analytics_report_ai_resolve_openai_api_key|analytics_report_ai_get_openai_api_key_lifecycle_categories|openai_api_key|OpenAI API Key|clear_openai_api_key|constant_configured|settings_saved|missing|Authorization|payload|generated report|support|debug|Settings" includes
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
| `git diff --name-only` | Step 231 production PHP files only; Step 231/232 docs appear as untracked in status. |
| `git diff --name-only -- readme.txt uninstall.php tools assets includes/class-ga4-client.php` | Pass / no output |
| `git status --short --untracked-files=all` | Expected Step 231 modified PHP files and Step 231/232 docs files. |

## Acceptance Criteria

| Acceptance criterion | Status |
|---|---|
| constant-based source priority is implemented | Pass |
| `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is used consistently | Pass |
| constant source category becomes `constant_configured` when configured | Pass |
| Settings fallback source category becomes `settings_saved` when no constant exists and fallback is saved | Pass |
| missing source category exists | Pass |
| constant value is not written back to settings | Pass |
| source/category helper does not return credential values | Pass |
| request-local resolver returns runtime key material only | Pass |
| lifecycle categories are safe for UI/support | Pass |
| Settings UI source/category labels are present | Pass |
| Settings fallback behavior is retained and explicit | Pass |
| existing saved OpenAI API key is preserved and never redisplayed | Pass |
| Settings clear control affects only Settings fallback key | Pass |
| OpenAI request boundary remains administrator-triggered Generate AI Report only | Pass |
| generated report body storage posture remains non-storage | Pass |
| AI payload and OpenAI request body structures are unchanged | Pass |
| GA4 client / readme / uninstall / tools / assets boundaries are unchanged | Pass |
| forbidden evidence is not recorded | Pass |
| OpenAI error wording is fully source-aware | Needs follow-up wording alignment |
| WordPress.org release readiness remains Hold | Pass |

## Result Classification

```text
OpenAI API key constant-based configuration source-level verification: Pass
Constant name: ANALYTICS_REPORT_AI_OPENAI_API_KEY / Verified
OpenAI API key source priority: Constant first / Settings fallback / Missing / Verified
Helper / resolver implementation: Verified
Settings UI source/category labels: Verified
Settings fallback behavior: Retained / Explicit / Verified
Existing saved OpenAI API key handling: Preserve / Never redisplay / Verified
Settings clear control: Settings fallback only / Verified
OpenAI request boundary: Administrator-triggered Generate AI Report only / Preserved
Generated report body storage posture: Non-storage / Preserved
Readme/privacy wording: Deferred
OpenAI error wording source-awareness: Needs follow-up wording alignment
Production code changes: Step 231 only
Step 232 production code changes: None
WordPress.org release readiness: Hold
```

## Recommended Next Step

Recommended next step:

```text
Step 233: OpenAI API key source human admin smoke plan
```

Step 233 should be docs-only / planning-only and should define a controlled
human admin smoke plan for Settings and Report Builder source/category labels,
value-hidden posture, Settings fallback clear-scope wording, and the
source-awareness wording follow-up boundary. It should not run browser smoke,
external API calls, OpenAI Generate, GA4 Fetch, Plugin Check, or forbidden
evidence collection.
