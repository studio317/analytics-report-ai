# Step 52: Human Settings Save Smoke Results

## Purpose

This document records the human-run Settings save smoke test results from
Step 51 for Analytics Report AI.

The purpose is to formally record safe Settings save behavior before any
external API end-to-end testing. The result includes one observed failure:
the save completion notice was not displayed after clicking Save Settings.

Codex did not perform the browser save operation in this step. The results
below are recorded from the human Settings save smoke report provided for
Step 51.

## Scope

In scope:

- Settings screen save flow using dummy or non-secret values.
- Non-credential field persistence after save.
- Credential field non-redisplay after save.
- Secret-free Settings status and notice display.
- Report Builder availability after Settings save.
- JavaScript console checks around Settings display, save, and reload.
- Cleanup / restore status reported by the human tester.

Out of scope:

- GA4 Fetch click.
- Generate AI Report / OpenAI Generate click.
- Google OAuth flow.
- Real credential entry or save.
- `wp_options` credential or plugin settings option inspection.
- Full payload inspection.
- Raw response inspection.
- Generated report inspection.
- External API end-to-end confirmation.

No production PHP, JavaScript, CSS, `readme.txt`, Composer file, PHPCS config,
distribution config, dry-run script, version, or metadata file was changed.

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
| Browser | Not provided. |
| WordPress user role | Administrator. |
| Human test date/time | Not provided. |
| WP-CLI plugin status during Step 52 preflight | Active. |

Preflight checks before creating this document:

| Check | Result |
|---|---|
| Source path | `/var/www/html/analytics-report-ai` |
| Git branch | `main` |
| Git status before docs edit | Clean. |
| Step 45 docs existence | Present. |
| Step 46 docs existence | Present. |
| Step 47 docs existence | Present. |
| Step 49 docs existence | Present. |
| Step 50 docs existence | Present. |
| WP-CLI plugin status | Active, version `0.1.0`. |
| WP-CLI plugin list | Includes `analytics-report-ai`. |

## Source of Results

The Settings save results in this document come from the Step 51 human browser
smoke test report provided to Codex.

Codex did not open the browser, did not click Save Settings, did not inspect
screenshots, and did not infer browser `Pass` results independently.

Screenshots are not recorded in this document.

## Test Data

| Field | Test value / status |
|---|---|
| GA4 Property ID | `123456789` |
| Hostname filter | Checked |
| Hostname | `localhost` |
| Google Access Token | Not entered |
| OpenAI API Key | Not entered |

The provided test data is treated as dummy or non-secret test data. Real
credentials were not used.

## Safety Constraints

The Step 51 result is recorded under these constraints:

- Real credentials were not entered or saved.
- Google Access Token and OpenAI API Key fields were not touched.
- GA4 Fetch was not clicked.
- Generate AI Report / OpenAI Generate was not clicked.
- Google OAuth was not started.
- GA4, OpenAI, Google OAuth, and other external API communication were not
  performed as part of this smoke result.
- Credential values, API keys, access tokens, Authorization headers, full
  request bodies, full payload bodies, raw response bodies, generated report
  bodies, and plugin credential option values were not recorded.
- `wp_options` credential or plugin settings option values were not displayed
  or recorded.

## Results Summary

| Category | Pass | Fail | Blocked | Not tested |
|---|---:|---:|---:|---:|
| Manual Settings save | 15 | 1 | 0 | 0 |
| Post-save display | 7 | 0 | 0 | 0 |
| JavaScript console | 4 | 0 | 0 | 0 |
| Cleanup / restore | 3 | 0 | 0 | 0 |
| Total | 29 | 1 | 0 | 0 |

Overall result: Settings save smoke mostly passed, with one failure in the
save-completion notice display. The reported evidence suggests that persistence
itself likely occurred because non-credential values were retained after reload.

## Detailed Results

| ID | Category | Check | Status | Notes |
|---|---|---|---|---|
| SAVE-001 | Manual Settings save | Settings screen opens. | Pass | Human Settings save smoke reported pass. |
| SAVE-002 | Manual Settings save | No visible fatal/warning/notice on initial display. | Pass | Human Settings save smoke reported pass. |
| SAVE-003 | Manual Settings save | Dummy GA4 Property ID can be entered. | Pass | Value used: `123456789`. |
| SAVE-004 | Manual Settings save | Hostname filter can be set. | Pass | Human Settings save smoke reported pass; state was checked. |
| SAVE-005 | Manual Settings save | Safe hostname can be entered. | Pass | Value used: `localhost`. |
| SAVE-006 | Manual Settings save | Real Google Access Token was not entered. | Pass | Credential field was not touched. |
| SAVE-007 | Manual Settings save | Real OpenAI API Key was not entered. | Pass | Credential field was not touched. |
| SAVE-008 | Manual Settings save | Save Settings can be clicked. | Pass | Human Settings save smoke reported pass. |
| SAVE-009 | Manual Settings save | Save completion notice is displayed. | Fail | Save completion notice was not displayed after clicking Save Settings. |
| SAVE-010 | Manual Settings save | No visible fatal/warning/notice after save. | Pass | Human Settings save smoke reported pass. |
| SAVE-011 | Manual Settings save | Settings screen can be reloaded. | Pass | Human Settings save smoke reported pass. |
| SAVE-012 | Manual Settings save | Non-credential values are retained after save. | Pass | Human Settings save smoke reported pass; this suggests persistence likely worked. |
| SAVE-013 | Manual Settings save | Credential fields are not redisplayed in plaintext. | Pass | Human Settings save smoke reported pass. |
| SAVE-014 | Manual Settings save | Credential status / notice does not expose secret values. | Pass | Human Settings save smoke reported pass. |
| SAVE-015 | Manual Settings save | Report Builder still opens. | Pass | Human Settings save smoke reported pass. |
| SAVE-016 | Manual Settings save | No visible fatal/warning/notice on Report Builder initial display. | Pass | Human Settings save smoke reported pass. |
| POST-001 | Post-save display | GA4 Property ID displays as expected after save. | Pass | Human Settings save smoke reported pass. |
| POST-002 | Post-save display | Hostname filter displays as expected after save. | Pass | Human Settings save smoke reported pass. |
| POST-003 | Post-save display | Hostname displays as expected after save. | Pass | Human Settings save smoke reported pass. |
| POST-004 | Post-save display | Google Access Token saved value is not shown in plaintext. | Pass | Human Settings save smoke reported pass; token was not entered. |
| POST-005 | Post-save display | OpenAI API Key saved value is not shown in plaintext. | Pass | Human Settings save smoke reported pass; key was not entered. |
| POST-006 | Post-save display | Authorization header or token-like value is not shown on screen. | Pass | Human Settings save smoke reported pass. |
| POST-007 | Post-save display | Request body / payload / raw response / generated report is not shown on screen. | Pass | Human Settings save smoke reported pass. |
| JS-001 | JavaScript console | No obvious JavaScript error on Settings initial display. | Pass | Human Settings save smoke reported pass. |
| JS-002 | JavaScript console | No obvious JavaScript error after Settings save. | Pass | Human Settings save smoke reported pass. |
| JS-003 | JavaScript console | No obvious JavaScript error after Settings reload. | Pass | Human Settings save smoke reported pass. |
| JS-004 | JavaScript console | Console does not show credentials, tokens, API keys, Authorization headers, payloads, raw responses, or generated reports. | Pass | Human Settings save smoke reported pass. |
| CLEAN-001 | Cleanup / restore | Dummy values were not left behind. | Pass | Human Settings save smoke reported `no`. |
| CLEAN-002 | Cleanup / restore | Settings were restored. | Pass | Human Settings save smoke reported `yes`. |
| CLEAN-003 | Cleanup / restore | Credential fields were not touched. | Pass | Human Settings save smoke reported `no`. |

## Failure Summary

Failure:

- Save completion notice was not displayed after clicking Save Settings.

Impact observed:

- Non-credential values were retained after reload.
- No visible fatal/warning/notice was observed after save.
- Settings screen could be reloaded.
- Report Builder still opened.
- No JavaScript console error was observed.
- Credential fields were not redisplayed in plaintext.
- No secret exposure was reported.

Preliminary classification:

- Likely UI feedback / admin notice issue, not necessarily a persistence
  failure.

Recommended follow-up:

- Investigate the Settings save redirect / admin notice rendering path in a
  later implementation step.
- Keep remediation minimal.
- Do not change credential handling or external API behavior as part of this
  follow-up unless directly required.

## Security Notes

- This document records human Settings save smoke results only.
- Codex did not perform browser save verification.
- Screenshots are not recorded.
- Real credentials were not entered or saved.
- Google Access Token and OpenAI API Key fields were not touched.
- GA4 Fetch was not clicked.
- Generate AI Report / OpenAI Generate was not clicked.
- Google OAuth was not started.
- GA4, OpenAI, Google OAuth, and other external API communication were not
  performed as part of this Settings save smoke result.
- No credential values were recorded.
- No API keys were recorded.
- No access tokens were recorded.
- No Authorization headers were recorded.
- No full request bodies were recorded.
- No full AI payload bodies were recorded.
- No raw GA4 or OpenAI response bodies were recorded.
- No generated report bodies were recorded.
- No `wp_options` credential or plugin settings option values were displayed or
  recorded.

## Not Performed

The following actions were intentionally not performed and are not counted in
the Results Summary:

- GA4 Fetch click.
- Generate AI Report / OpenAI Generate click.
- Google OAuth flow.
- Real credential entry.
- `wp_options` inspection.
- Full payload inspection.
- Raw response inspection.
- Generated report inspection.
- External API end-to-end confirmation.

## Cleanup / Restore Result

| Cleanup item | Result |
|---|---|
| Dummy values were left behind | No. |
| Settings were restored | Yes. |
| Credential fields were touched | No. |

Cleanup was recorded only as status-level evidence. Credential option values
were not displayed, inspected, or recorded.

## Remaining Risks / Follow-up

- Save completion notice did not display and needs follow-up.
- Persistence appears likely based on retained non-credential values, but the
  missing notice creates unclear user feedback after saving Settings.
- Follow-up should focus on the Settings save redirect/admin notice path.
- Credential handling, credential storage, GA4 request behavior, OpenAI request
  behavior, and external API behavior should remain unchanged unless directly
  required by the notice fix.
- Manual Google Access Token entry and MVP database credential storage remain
  separate public-release risks documented in earlier maturation docs.

## Next Step Notes

- Treat Step 52 as a mostly passing Settings save smoke result with one UI
  feedback failure.
- Prefer a narrow future implementation step for the missing save completion
  notice.
- Keep any future remediation separate from GA4/OpenAI E2E testing.
- Continue recording only status-level or redacted evidence in future manual
  tests.
