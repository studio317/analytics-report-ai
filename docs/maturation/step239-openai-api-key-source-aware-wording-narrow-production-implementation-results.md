# Step 239: OpenAI API Key Source-aware Wording Narrow Production Implementation Results

## Step Purpose

Step 239 is a narrow production implementation for static OpenAI API key
source-aware admin wording.

The implementation updates only static WordPress admin/user-facing wording that
could otherwise imply a Settings-only OpenAI API key configuration path. It does
not change credential resolution, source categories, Settings save/clear
behavior, OpenAI request construction, HTTP handling, GA4 behavior, OAuth
behavior, generated report handling, JavaScript, CSS, `readme.txt`, tools, or
uninstall cleanup.

WordPress.org release readiness remains `Hold`.

## Changed Files

| File | Class / area | Change type |
|---|---|---|
| `includes/class-settings.php` | `Analytics_Report_AI_Settings::render_settings_page()` / OpenAI API key fallback field missing-state description | Static Settings wording update |
| `includes/class-report-builder.php` | `Analytics_Report_AI_Report_Builder::render()` / Current Settings table, OpenAI API Key Source missing category | Static Report Builder wording update |
| `includes/class-openai-client.php` | `Analytics_Report_AI_OpenAI_Client::generate_report()` / missing key branch | Static missing-readiness error wording update |
| `includes/class-openai-client.php` | `Analytics_Report_AI_OpenAI_Client::get_safe_api_error_message()` / authentication and generic fallback messages | Static safe error wording update |

## Settings Wording Implementation

Changed area:

- `includes/class-settings.php`
- `Analytics_Report_AI_Settings::render_settings_page()`
- OpenAI API Key Fallback (Settings) missing-state description

Applied source-aware principle:

- Missing-state guidance now points to the preferred constant source first and
  the current MVP Settings fallback second.
- Existing value-hidden behavior remains unchanged.
- Existing Settings fallback wording remains scoped to fallback values.
- Existing clear checkbox behavior remains unchanged and is still shown only
  when the existing fallback-clear condition is met.

Runtime behavior change:

```text
No
```

## Report Builder Wording Implementation

Changed area:

- `includes/class-report-builder.php`
- `Analytics_Report_AI_Report_Builder::render()`
- Current Settings table / OpenAI API Key Source section

Applied source-aware principle:

- When the OpenAI API key source category is `missing`, Report Builder now gives
  source-aware readiness/action guidance.
- The guidance names the preferred constant source before the current MVP
  Settings fallback.
- The guidance does not imply that source category guarantees provider-side
  validity, permissions, quota, endpoint availability, or OpenAI request success.

Runtime behavior change:

```text
No
```

## OpenAI Error Wording Implementation

Changed areas:

- `Analytics_Report_AI_OpenAI_Client::generate_report()` missing key branch
- `Analytics_Report_AI_OpenAI_Client::get_safe_api_error_message()` 401 branch
- `Analytics_Report_AI_OpenAI_Client::get_safe_api_error_message()`
  authentication error branch
- `Analytics_Report_AI_OpenAI_Client::get_safe_api_error_message()` generic
  fallback branch

Applied source-aware principle:

- Missing readiness now asks the administrator to configure the preferred
  OpenAI API key constant source or the current MVP Settings fallback.
- Authentication failure wording no longer points only to Settings.
- Non-missing request failure wording remains generic and safe.
- The wording does not reproduce raw provider error text.
- The wording does not request API key values, tokens, Authorization headers,
  request bodies, raw responses, AI payload JSON, or generated report bodies.

Runtime behavior change:

```text
No
```

## Candidates Not Changed

| Candidate | Reason not changed |
|---|---|
| `includes/class-settings.php` / broad credential storage notice | Already describes constant precedence, Settings fallback, hidden values, and support/debug evidence boundary at category level. |
| `includes/class-settings.php` / OpenAI fallback clear checkbox | Already scopes deletion to the saved Settings fallback and states constant-based configuration is not changed. |
| `includes/class-settings.php` / Settings save and sanitize logic | Behavior and option handling are explicitly out of scope. |
| `includes/class-report-builder.php` / payload preview and Generate AI Report flow | These areas describe payload review and OpenAI generation, not OpenAI key source recovery; changing them would widen the step. |
| `includes/class-report-builder.php` / generated report draft area | Generated report handling is unrelated to OpenAI key source wording. |
| `includes/class-openai-client.php` / request construction, headers, body, response parsing | Runtime OpenAI request behavior and raw response handling are explicitly out of scope. |
| `includes/functions-utils.php` | Credential resolver priority and source category logic are explicitly out of scope. |
| `readme.txt`, tools, JavaScript, CSS, `uninstall.php` | Explicitly out of scope for Step 239. |

## Source Category Boundary

Safe source categories remain:

```text
constant_configured
settings_saved
missing
```

The implementation does not treat source category as proof of:

- actual API key value validity,
- provider-side acceptance,
- permissions,
- quota,
- endpoint availability,
- model availability,
- OpenAI request success.

Source categories are used only as safe UI/readiness labels.

## Explicit Non-change Confirmation

The implementation did not change:

- credential resolver priority logic,
- constant / Settings fallback resolution behavior,
- option save / clear behavior,
- API key value-hidden behavior,
- OpenAI request payload construction,
- OpenAI request execution,
- HTTP handling,
- response parsing,
- error classification logic,
- GA4 Fetch behavior,
- OAuth behavior,
- generated report storage posture,
- raw provider response handling,
- `readme.txt`,
- tools,
- JavaScript,
- CSS,
- uninstall cleanup.

## Source-level Verification

Commands executed:

```text
php -l includes/class-settings.php
php -l includes/class-report-builder.php
php -l includes/class-openai-client.php
find includes -name '*.php' -print0 | xargs -0 -n1 php -l
rg -n "constant_configured|settings_saved|missing|OpenAI API key source is missing|OpenAI report generation needs|OpenAI API authentication failed|Check the API key in Settings|Check the settings and try again" includes/class-settings.php includes/class-report-builder.php includes/class-openai-client.php includes/functions-utils.php
git diff --check
git diff --name-only
git diff --stat
```

Results:

| Check | Result |
|---|---|
| `php -l includes/class-settings.php` | Pass; no syntax errors detected |
| `php -l includes/class-report-builder.php` | Pass; no syntax errors detected |
| `php -l includes/class-openai-client.php` | Pass; no syntax errors detected |
| All PHP files under `includes/` | Pass; no syntax errors detected |
| Source category names | Present as `constant_configured`, `settings_saved`, and `missing` |
| Old Settings-only OpenAI key error wording | Not found in touched OpenAI error wording search |
| `git diff --check` | Pass; no output |

## Forbidden Evidence Confirmation

Step 239 did not display, record, or request:

- credentials,
- API keys,
- key fragments, prefixes, or suffixes,
- constant values,
- option values,
- tokens,
- Authorization headers,
- request bodies,
- raw responses,
- AI payload JSON,
- generated report bodies,
- screenshots,
- browser Network evidence,
- cookies, sessions, or nonces,
- database dumps.

## Prohibited Operations Confirmation

Step 239 did not run:

- browser admin smoke,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- refresh request,
- revoke request,
- Plugin Check,
- screenshots,
- Network evidence collection,
- `wp option get`,
- database dump.

No external API communication was performed.

## Step 232 Residual Handling

Step 232 residual:

```text
OpenAI error wording source-awareness: Needs follow-up wording alignment
```

Step 239 addressed the static wording implementation portion of this residual.
It does not claim runtime OpenAI error paths were executed or fully verified.
Runtime error-path coverage remains out of scope unless a later step explicitly
plans and authorizes it.

## Step 234 Observation Handling

Step 234 observation:

```text
Report Builder Settings-only OpenAI key guidance visible
```

Step 239 addressed this as a source-aware readiness wording input. This does not
change the Step 234 scope-bound Pass result, because Step 234 verified only the
observed `missing` category path at status/category level.

## Recommended Next Step

Recommended next step:

```text
Step 240: OpenAI API key source-aware wording source-level verification results
```

Step 240 should be docs-only and should record source-level verification results
for the Step 239 wording implementation. It should not run browser smoke,
OpenAI Generate, GA4 Fetch, OAuth, external API communication, or Plugin Check.

## Result Classification

```text
Step 239 result: Narrow production wording implementation completed
Static wording only: Yes
Settings wording updated: Yes
Report Builder wording updated: Yes
OpenAI error wording updated: Yes
Runtime behavior changed: No
Source category logic changed: No
Request logic changed: No
Forbidden evidence recorded: No
External API communication: No
Plugin Check: Not run
WordPress.org release readiness: Hold
```
