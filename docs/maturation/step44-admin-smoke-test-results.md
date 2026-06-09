# Step 44 Admin Smoke Test Results

## 1. Overview

This document records a redacted admin smoke test pass based on the Step 40
Admin Smoke Test Checklist.

This Step 44 pass executed only checks that were safe without GA4, OpenAI,
Google OAuth, or other external API communication. Browser-only checks and
real external request checks were not forced. They are recorded as `Blocked` or
`Not tested` as appropriate.

No production PHP, JavaScript, CSS, `readme.txt`, plugin header metadata,
`phpcs.xml.dist`, `.distignore`, `.gitignore`, Composer files, dry-run script,
version, or `Stable tag` is changed in this step.

## 2. Test Environment

| Item | Value |
| --- | --- |
| Source path | `/var/www/html/analytics-report-ai` |
| WordPress test path | `/var/www/html/wp-dev` |
| Date/time | `2026-06-09 17:00:16 JST +0900` |
| Plugin version | `0.1.0` |
| WordPress version | `7.0` |
| PHP version | `8.1.2-1ubuntu2.24` |
| PHPCS | `PHP_CodeSniffer version 3.13.5`; Step 44 run was clean. |
| Plugin Check | Local Plugin Check plugin `2.0.0`; staged package check was clean. |
| Credentials | No real Google Access Token or OpenAI API Key was used. |

The WordPress test site did not list `analytics-report-ai` as an installed
plugin. Activation and browser admin rendering checks were therefore marked
`Blocked` instead of installing or symlinking the plugin during this docs-only
step.

## 3. Summary

| Metric | Count / Result |
| --- | --- |
| Total ASM tests reviewed | 45 |
| Pass | 24 |
| Fail | 0 |
| Blocked | 20 |
| Not tested | 1 |
| Overall result | Static/package/helper subset passed; browser and install-dependent items remain blocked. |
| Notes | No external API communication was performed, and no secrets or payload bodies were recorded. |

No production code issue was found in the executed subset.

## 4. Executed Static / Packaging Baseline

| Check | Result |
| --- | --- |
| `git status --short --untracked-files=all` before docs edit | Clean. |
| `php -l analytics-report-ai.php` | Clean: no syntax errors. |
| `find includes -name '*.php' -print0 \| xargs -0 -n1 php -l` | Clean for all PHP files under `includes/`. |
| `vendor/bin/phpcs -ps` | Clean: `5 / 5 (100%)`, `0 errors / 0 warnings`. |
| `vendor/bin/phpcs --report=summary` | No violation output. |
| `bash -n tools/build-release-zip-dry-run.sh` | Clean. |
| `./tools/build-release-zip-dry-run.sh` | Dry-run package regenerated successfully at `build/release-dry-run/analytics-report-ai-0.1.0.zip`. |
| Dry-run credential scan | Completed without high-risk credential pattern matches. Documentation keyword warnings were filename-level warnings only and did not print values. |
| Zip tooling-file exclusion grep | No output, as expected. |
| `wp --path=/var/www/html/wp-dev plugin check build/release-dry-run/stage/analytics-report-ai` | Clean: Plugin Check completed with no errors. |
| `git diff --stat` before docs edit | No output. |
| `git diff --name-only` before docs edit | No output. |
| `git diff --check` before docs edit | No output. |

The dry-run zip contained the plugin root, `analytics-report-ai.php`, `assets/`,
`includes/`, and `readme.txt`. The tooling-file grep found no `vendor/`,
Composer files, PHPCS config files, or `.phpcs` files in the zip.

## 5. Executed WordPress / Activation Checks

| Check | Result |
| --- | --- |
| `wp --path=/var/www/html/wp-dev plugin list --fields=name,status,version --format=csv` | The test site listed `akismet`, `cuerda-feed-total`, and `plugin-check`; `analytics-report-ai` was not listed. |
| `wp --path=/var/www/html/wp-dev plugin status analytics-report-ai` | Blocked: WP-CLI reported that the plugin could not be found in the test site. |
| Activation / deactivation / reactivation | Blocked: plugin was not installed or symlinked into the WordPress test site. |
| Active-state restoration | Blocked for the same reason. |
| Tracked source changes after attempted availability checks | Clean before this docs edit. No production file changes were made. |

No symlink, install, activation, deactivation, or reactivation operation was
performed in this docs-only step.

## 6. Settings / Report Builder Structural Checks

Static checks confirmed the following structures without loading a browser or
submitting real credentials:

- Admin menu registration uses `analytics-report-ai` and
  `analytics-report-ai-settings` slugs.
- Admin pages use the `manage_options` capability.
- Settings use the `analytics_report_ai_settings` option name.
- Settings credential inputs keep `value=""` for Google Access Token and
  OpenAI API Key fields.
- Saved credential status is shown without redisplaying values.
- Clear controls exist for `clear_google_access_token` and
  `clear_openai_api_key`.
- External service usage and Credential Storage (MVP) notice strings exist.
- Report Builder contains the staged flow copy for GA4 fetch, payload review,
  and OpenAI generation.
- Report Builder forms include nonce fields and action handling.
- Admin JavaScript selectors for scope switching, confirm prompts, copy status,
  and single-submit guard exist.

Because browser rendering was not available and the plugin was not installed in
the WordPress test site, visual admin page rendering is recorded as `Blocked`.

## 7. Validation / Permission / Nonce Checks

Safe local helper checks were run through `wp eval` after loading the plugin
source file. These checks did not call GA4, OpenAI, Google OAuth, or any other
external API.

| Check | Result |
| --- | --- |
| Max report days | `31`. |
| Inclusive day count | 31-day and 32-day ranges returned `31` and `32`, respectively. |
| Invalid date helper | Invalid date was rejected. |
| GA4 property ID helper | Measurement ID-shaped value was detected/rejected; numeric property ID-shaped value was accepted. |
| Host normalization helper | URL-shaped host input normalized to host name only. |
| Full URL path | Rejected with `analytics_report_ai_full_url_not_allowed`. |
| Directory path normalization | `blog` normalized to `/blog/`. |
| Page path normalization | `about` normalized to `/about`. |
| Query/fragment path handling | `/blog/?utm=x#top` normalized without query/fragment. |
| Empty path | Rejected with `analytics_report_ai_empty_path`. |
| Valid minimal payload shape | Accepted by `analytics_report_ai_validate_ai_payload()`. |
| Invalid payload version | Rejected with `analytics_report_ai_payload_unexpected_payload_version`. |

Private Report Builder validation and request handling were called via
reflection only for branches that stop before external API communication.

| Check | Result |
| --- | --- |
| End date before start date | Validation returned `error`. |
| Range longer than maximum | Validation returned `error`. |
| Invalid date strings | Validation returned `error`. |
| Invalid comparison / scope | Validation returned `error`. |
| Valid directory input | Validation returned `success` and normalized path `/blog/`. |
| POST without nonce as admin | Returned `error` before external work. |
| POST with invalid nonce as admin | Returned `error` before external work. |
| POST without permission | Returned `error` before external work. |
| Generate without payload transient | Returned `error` before OpenAI work. |

No direct POST was sent through a browser, and no fetch/generate action was
allowed to continue to an external client.

## 8. Browser-Dependent Checks

The following were not claimed as passed because no browser admin verification
was performed:

- Admin menu visual presence.
- Settings screen visual rendering.
- Report Builder visual rendering.
- Default form state as rendered in the browser.
- Scope switching UI behavior.
- Browser console JavaScript error review.
- Copy button and textarea browser behavior.
- Admin notice visual readability.
- Payload preview display with a browser fixture.
- PHP error log review after browser page loads.

These are recorded as `Blocked: browser-based manual verification required`,
or `Blocked: plugin not installed in the WordPress test site`, in the result
table.

## 9. External API-Dependent Checks

The following were not executed in Step 44:

- Clicking Fetch GA4 Data against real Google Analytics Data API.
- Clicking Generate AI Report against real OpenAI API.
- Saving real Google Access Tokens.
- Saving real OpenAI API Keys.
- Inspecting raw GA4 responses.
- Inspecting raw OpenAI responses.
- Recording payload bodies or generated reports.

External API-dependent items are deferred to Step 45 manual E2E, with redacted
or status-level evidence only.

## 10. Result Table

| Test ID | Area | Result | Evidence summary | Redaction check | Follow-up |
| --- | --- | --- | --- | --- | --- |
| ASM-001 | Activation | Blocked | WP test site did not list `analytics-report-ai`; `plugin status` could not find it. | No secrets used or recorded. | Install or symlink plugin in a manual smoke environment. |
| ASM-002 | Activation | Blocked | Deactivation/reactivation was not attempted because the plugin was not installed in the WP test site. | No secrets used or recorded. | Run after installation is available. |
| ASM-003 | Admin menu | Blocked | Browser menu was not observed; static code contains expected menu slugs and `manage_options`. | No secrets used or recorded. | Verify in browser after plugin installation. |
| ASM-004 | Admin rendering | Blocked | Browser rendering was not performed; PHP lint remained clean. | No screenshots or logs recorded. | Verify Settings and Report Builder in browser. |
| ASM-010 | Settings | Blocked | Settings browser render was not performed; static code contains expected controls and non-redisplay fields. | No credential values used. | Verify Settings screen manually. |
| ASM-011 | Settings disclosure | Pass | Static check found external service usage notice strings for Google Analytics Data API and OpenAI API. | No secrets used or recorded. | Browser readability remains manual. |
| ASM-012 | Settings disclosure | Pass | Static check found Credential Storage (MVP) notice strings and public-use redesign warning. | No secrets used or recorded. | Browser readability remains manual. |
| ASM-013 | Settings credentials | Blocked | Actual form save was not performed; static code indicates empty credential inputs keep existing values. | No credential values used. | Verify with dummy local credentials only. |
| ASM-014 | Settings credentials | Pass | Static check found credential clear controls and sanitize branches for both saved values. | No real credentials used. | Browser save behavior remains manual. |
| ASM-015 | Settings validation | Pass | Helper check detected/rejected measurement ID-shaped input and accepted numeric property ID-shaped input. | Placeholder values only. | None. |
| ASM-016 | Settings host filter | Pass | Helper check normalized URL-shaped host input to host name only; static code contains host filter controls. | No sensitive hostnames recorded. | Browser save behavior remains manual. |
| ASM-020 | Report Builder | Blocked | Browser render was not performed. | No screenshots recorded. | Verify manually after plugin installation. |
| ASM-021 | Report Builder form | Blocked | Browser default form state was not observed. | No sensitive data recorded. | Verify manually in browser. |
| ASM-022 | Report Builder disclosure | Pass | Static check found staged flow copy for GA4 fetch, payload review, and OpenAI generation. | No payload bodies recorded. | Browser readability remains manual. |
| ASM-023 | Report Builder state | Blocked | AI button and payload preview initial visibility were not observed in browser. | No payload bodies recorded. | Verify manually in browser. |
| ASM-030 | Scope UI | Blocked | Whole-site scope UI behavior requires browser JS verification. | No external requests made. | Verify manually in browser. |
| ASM-031 | Scope UI | Blocked | Directory scope UI behavior requires browser JS verification; localized string exists in code. | No external requests made. | Verify manually in browser. |
| ASM-032 | Scope UI | Blocked | Page scope UI behavior requires browser JS verification; localized string exists in code. | No external requests made. | Verify manually in browser. |
| ASM-033 | Admin JavaScript | Blocked | Browser console and JS interaction checks were not performed. | No screenshots or console logs recorded. | Verify manually in browser. |
| ASM-040 | Validation | Pass | Private validation returned `error` for end date before start date before any external work. | No request body recorded. | None. |
| ASM-041 | Validation | Pass | Private validation returned `error` for date range longer than maximum before any external work. | No request body recorded. | None. |
| ASM-042 | Validation | Pass | Helper/private validation rejected invalid date strings. | No request body recorded. | None. |
| ASM-043 | Path validation | Pass | Helper rejected full URL input with `analytics_report_ai_full_url_not_allowed`. | Placeholder URL only. | None. |
| ASM-044 | Path validation | Pass | Private validation normalized directory input to `/blog/`. | Placeholder path only. | None. |
| ASM-045 | Path validation | Pass | Helper normalized page input to `/about`. | Placeholder path only. | None. |
| ASM-046 | Path validation | Pass | Helper stripped query/fragment from path input. | Placeholder path only. | None. |
| ASM-047 | Path validation | Pass | Helper rejected empty page path with `analytics_report_ai_empty_path`. | No request body recorded. | None. |
| ASM-048 | Validation | Pass | Private validation returned `error` for invalid comparison/scope values. | No request body recorded. | None. |
| ASM-050 | Nonce validation | Pass | Direct private request path returned `error` for missing nonce before external work. | No credentials or request body recorded. | None. |
| ASM-051 | Nonce validation | Pass | Direct private request path returned `error` for invalid nonce before external work. | No credentials or request body recorded. | None. |
| ASM-052 | Permissions | Pass | Direct private request path returned `error` for no current permission before external work. | No credentials or request body recorded. | Browser URL access remains manual if needed. |
| ASM-060 | Payload state | Blocked | Browser state with no payload transient was not observed. | No payload body recorded. | Verify manually in browser. |
| ASM-061 | Payload state | Pass | Generate path with valid nonce and no payload transient returned `error` before OpenAI work. | No payload body recorded. | None. |
| ASM-062 | Payload preview | Blocked | Payload preview disclosure needs browser or safe fixture state; not performed. | No payload body recorded. | Verify manually with redacted fixture or Step 45 flow. |
| ASM-070 | Report output | Blocked | Textarea initial state was not observed in browser. | No generated report recorded. | Verify manually in browser. |
| ASM-071 | Copy UI | Blocked | Copy button without report text requires browser JS verification. | No generated report recorded. | Verify manually in browser. |
| ASM-072 | Copy UI | Blocked | Copy current textarea behavior requires browser JS verification. | No generated report recorded. | Verify manually with non-sensitive text. |
| ASM-080 | Notices | Blocked | Visual notice readability/escaping was not inspected in browser. | No screenshots recorded. | Verify manually in browser. |
| ASM-081 | Error messaging | Not tested | No non-network GA4/OpenAI error stub was available; real API failures are deferred. | No raw responses recorded. | Defer to Step 45 or a future stubbed test. |
| ASM-082 | Runtime diagnostics | Blocked | PHP error log review after browser page loads was not performed. | No logs pasted. | Verify manually when browser smoke is run. |
| ASM-090 | Static baseline | Pass | Main plugin file and all `includes/` PHP files passed syntax checks. | No secrets involved. | None. |
| ASM-091 | Static baseline | Pass | PHPCS/WPCS completed clean with `0 errors / 0 warnings`. | No secrets involved. | None. |
| ASM-092 | Packaging | Pass | Dry-run zip regenerated successfully and included runtime files only. | Credential scan did not print values. | None. |
| ASM-093 | Packaging | Pass | Zip tooling-file grep produced no output for excluded tooling patterns. | No secrets involved. | None. |
| ASM-094 | Static baseline | Pass | Plugin Check against staged package completed with no errors. | No secrets involved. | None. |

## 11. Issues / Follow-up

Fail items:

- None.

Blocked items:

- Activation and active-state checks are blocked because `analytics-report-ai`
  is not installed or symlinked in `/var/www/html/wp-dev`.
- Browser rendering and visual UI checks are blocked because no browser admin
  verification was performed in this Codex environment.
- JavaScript behavior checks are blocked until browser verification is run.
- Payload preview and generated report UI checks are blocked until a safe
  browser or fixture state exists.
- PHP error log review after browser page loads is blocked until browser smoke
  testing is performed.

Not tested items:

- Stubbed or real GA4/OpenAI external API error shape checks were not tested.
  They are deferred to Step 45 manual E2E or a future non-network stub.

No production code issue was found in the executed static/helper subset.

## 12. Safety Notes

This step did not perform GA4 API communication, OpenAI API communication,
Google OAuth communication, other external API communication, Composer
install/update, `phpcbf`, SVN operations, GitHub release operations,
WordPress.org publication actions, or formal release publication.

No real Google Access Token or OpenAI API Key was used. No credential values,
API keys, Authorization headers, full request bodies, full AI payload bodies,
raw GA4 responses, raw OpenAI responses, screenshots, database exports, or full
generated report text were recorded.
