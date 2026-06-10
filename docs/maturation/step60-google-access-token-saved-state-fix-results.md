# Step 60: Google Access Token Saved-State Fix Results

## Purpose

This document records the focused Step 60 investigation and minimal fix for the
Google Access Token saved-state issue documented in Step 59.

The goal was limited to Settings credential save/status behavior. This step did
not proceed to GA4 Fetch, OpenAI Generate, Google OAuth, or any external API
communication.

## Scope

In scope:

- Settings form rendering review.
- Settings registration and sanitize callback review.
- Credential save/update condition review.
- Google Access Token saved-status review.
- OpenAI API Key behavior comparison.
- Minimal production code fix for the confirmed Google Access Token issue.
- Static checks and WP runtime smoke checks without real credentials.
- Result documentation.

Out of scope:

- GA4 Fetch click.
- OpenAI Generate / Generate AI Report click.
- Google OAuth flow.
- GA4, OpenAI, Google OAuth, or other external API communication.
- Real credential entry, save, display, or recording.
- Credential option value dumps.
- Request body, AI payload, raw response, or generated report inspection.
- OAuth redesign.
- Credential storage redesign.
- Release, SVN, GitHub release, WordPress.org publication, or commit action.

## Environment

| Item | Value |
|---|---|
| Plugin | Analytics Report AI |
| Slug | `analytics-report-ai` |
| Version | `0.1.0` |
| Source checkout | `/var/www/html/analytics-report-ai` |
| WordPress test environment | `/var/www/html/wp-dev` |
| WordPress site URL | `http://localhost/wp-dev` |
| WordPress plugin path | `/var/www/html/wp-dev/wp-content/plugins/analytics-report-ai` |
| Source branch | `main` |
| WP-CLI plugin status during Step 60 preflight | Active |

## Source / Evidence Reviewed

Preflight checks before the fix:

| Check | Result |
|---|---|
| Source path | `/var/www/html/analytics-report-ai` |
| Git status before changes | Clean. |
| Git branch | `main` |
| Recent history | Step 59 controlled credential result commit was present at `HEAD`. |
| Step 57 docs existence | Present: `docs/maturation/step57-controlled-credential-entry-save-checklist.md`. |
| Step 59 docs existence | Present: `docs/maturation/step59-controlled-credential-entry-save-results.md`. |
| WP-CLI plugin status | Active, version `0.1.0`. |
| WP-CLI plugin list | Includes `analytics-report-ai`. |

Code reviewed:

- `includes/class-settings.php`
- `includes/functions-utils.php`
- `analytics-report-ai.php`
- `includes/class-plugin.php`
- `includes/class-admin.php`
- `includes/class-report-builder.php`

Search/review targets:

- `register_setting()`
- `sanitize_settings()`
- Google Access Token input name.
- Google Access Token clear flag.
- Google Access Token saved-status conditions.
- OpenAI API Key save/status behavior.
- `analytics_report_ai_get_settings()`
- option array merge/default handling.

Credential values and option values were not displayed or recorded.

## Observed Failure From Step 59

Step 59 recorded this failure:

- From an empty/not-saved Settings state, saving GA4 Property ID, Google Access
  Token, and OpenAI API Key at the same time could leave Google Access Token not
  shown as saved.
- OpenAI API Key was shown as saved in the same scenario.
- If GA4 Property ID was saved first, then Google Access Token and OpenAI API
  Key were saved afterward, Google Access Token was shown as saved.
- It was not known whether the underlying option save failed or only the saved
  status display failed, because `wp_options` values were not inspected.

## Investigation Summary

The Settings form uses these credential inputs:

- Google Access Token input name:
  `analytics_report_ai_settings[google_access_token]`
- OpenAI API Key input name:
  `analytics_report_ai_settings[openai_api_key]`

The stored option uses different shapes:

- Google Access Token is stored under nested option data:
  `google_tokens.access_token`
- OpenAI API Key is stored under the same top-level key used by the form:
  `openai_api_key`

`sanitize_settings()` initially handled raw Google Access Token input from
`google_access_token`, then wrote it to `google_tokens.access_token`.

In the WordPress Settings API/update path, the registered sanitize callback can
be applied to an already-normalized settings array. After the first sanitize
pass, the Google token no longer exists at the top-level input key and exists
only under `google_tokens.access_token`.

Before this fix, a later sanitize pass ignored the nested Google token in the
already-normalized array. OpenAI API Key did not have the same problem because
its form key and stored option key are both `openai_api_key`.

Status-level WP runtime smoke reproduced the Step 59 pattern before the fix:

- Simultaneous first save: Google saved status was false, OpenAI saved status
  was true.
- Property-first then credentials-second save: Google saved status was true,
  OpenAI saved status was true.
- Clear flags cleared both saved statuses.

The investigation did not display or record credential option values.

## Root Cause

Root cause:

- `Analytics_Report_AI_Settings::sanitize_settings()` was not idempotent for
  Google Access Token.

More specifically:

- The raw Settings form posts Google Access Token as `google_access_token`.
- The sanitized option stores it as `google_tokens.access_token`.
- When the registered sanitize callback receives an already-normalized settings
  array, the previous code did not read `google_tokens.access_token`.
- As a result, a second sanitize pass could drop the just-sanitized Google
  Access Token during simultaneous first save.
- OpenAI API Key survived the same path because its raw input key and stored
  option key are the same.

The issue was in Settings credential sanitize/save behavior, not in GA4 API
fetching, OpenAI generation, OAuth, or external API communication.

## Changes Made

`includes/class-settings.php` was changed so Google Access Token sanitize logic
also accepts the already-normalized nested token shape:

- If top-level `google_access_token` input is empty.
- And `google_tokens.access_token` exists and is scalar.
- Then sanitize that nested value through the same credential sanitizer.

The existing behavior remains:

- Empty credential input keeps the existing token.
- Clear checkbox removes the saved token.
- New top-level Google Access Token input replaces the saved token.
- Saved credential values are not rendered back into the form value attribute.

No GA4 API logic, OpenAI API logic, payload structure, transient behavior,
credential storage design, OAuth flow, or Report Builder flow was changed.

## Files Changed

| File | Change |
|---|---|
| `includes/class-settings.php` | Made Google Access Token sanitize handling idempotent for already-normalized `google_tokens.access_token` input. |
| `docs/maturation/step60-google-access-token-saved-state-fix-results.md` | Added this Step 60 investigation/fix result document. |

## Verification Summary

| Check | Result |
|---|---|
| WP-CLI plugin status | Active, version `0.1.0`. |
| WP-CLI plugin list | Includes `analytics-report-ai`. |
| PHP lint | Pass: no syntax errors detected. |
| PHPCS/WPCS | Pass: `vendor/bin/phpcs` produced no output. |
| `git diff --check` | Pass: no whitespace errors. |
| Runtime smoke A | Pass after fix. |
| Runtime smoke B | Pass after fix. |
| Runtime smoke C | Pass after fix. |
| Production code change scope | Minimal: Settings sanitize logic only. |
| External API communication | Not performed. |
| Real credentials | Not used. |
| Credential/option value display | Not performed. |

## Runtime Smoke Results

Runtime smoke used dummy credential-like inputs only. The smoke temporarily
updated the local WordPress test option and restored the original option
afterward. Output was limited to saved status and Google status. Credential
option values were not printed.

Allowed output categories only:

- `saved status: true / false`
- `google_status: connected / not_connected`

| ID | Scenario | Result | Notes |
|---|---|---|---|
| Smoke A | Simultaneous first save with dummy GA4 Property ID, dummy Google Access Token, and dummy OpenAI API Key. | Pass | After fix: Google saved status `true`; OpenAI saved status `true`; Google status `connected`. |
| Smoke B | GA4 Property ID saved first, then dummy Google Access Token and dummy OpenAI API Key saved second. | Pass | After fix: Google saved status `true`; OpenAI saved status `true`; Google status `connected`. |
| Smoke C | Clear flags from a dummy saved credential state. | Pass | After fix: Google saved status `false`; OpenAI saved status `false`; Google status `not_connected`. |

Before the fix, runtime smoke A reproduced the issue with Google saved status
`false` and OpenAI saved status `true`. After the fix, smoke A passes.

## Security Notes

- Real Google Access Token was not entered, saved, displayed, or recorded.
- Real OpenAI API Key was not entered, saved, displayed, or recorded.
- Dummy credential-like inputs were used only for local WP runtime smoke.
- Dummy option values were not printed.
- Existing credential or plugin settings option values were not displayed or
  recorded.
- `wp_options` values were not dumped.
- Authorization headers were not recorded.
- Full request bodies were not recorded.
- Full AI payload bodies were not recorded.
- Raw GA4 and OpenAI response bodies were not recorded.
- Full generated report bodies were not recorded.
- Browser DevTools network request bodies were not inspected or recorded.
- Screenshots were not recorded.

## Not Performed

- GA4 Fetch.
- Generate AI Report / OpenAI Generate.
- Google OAuth.
- GA4 API communication.
- OpenAI API communication.
- Google OAuth communication.
- Other external API communication.
- Real credential entry.
- Real credential save.
- Existing credential value display.
- `wp_options` value dump.
- Request body inspection.
- Payload body inspection.
- Raw response inspection.
- Generated report inspection.
- WordPress.org release action.
- Commit.

## Remaining Risks / Follow-up

- A human browser recheck should confirm the fixed Settings behavior in the
  same controlled credential entry/save/non-redisplay flow used in Step 58.
- The recheck must continue to avoid recording credential values, prefixes,
  suffixes, fragments, option values, request bodies, payloads, raw responses,
  or generated report text.
- GA4/OpenAI real E2E should wait until the human Settings recheck confirms
  simultaneous first-save behavior in the browser.
- Public-release risks around OAuth and credential storage remain unchanged.

## Next Step Notes

- The next step can be a focused human browser recheck of the Google Access
  Token saved-state fix.
- Keep the next step limited to Settings credential save/status behavior.
- Do not start GA4 Fetch E2E until the browser recheck passes.
- Do not start OpenAI Generate E2E until GA4 Fetch E2E is separately confirmed.
- Do not start Google OAuth redesign in the next step.
- WordPress.org release remains `Hold`.
