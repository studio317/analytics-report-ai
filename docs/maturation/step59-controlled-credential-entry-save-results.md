# Step 59: Controlled Credential Entry Save Results

## Purpose

This document records the human-run Step 58 controlled credential entry, save,
and non-redisplay results for Analytics Report AI.

The main purpose is to formally capture the credential plaintext
non-redisplay, secret-free notice/status behavior, cleanup result, and the new
Google Access Token saved-state failure that should be investigated before
GA4/OpenAI real E2E continues.

Codex did not operate the browser, enter credentials, save credentials, display
credentials, inspect option values, or perform external API communication in
this step.

## Scope

In scope:

- Human Settings screen credential entry/save result recording.
- Credential plaintext non-redisplay result recording.
- Secret-free notice/status and console result recording.
- Cleanup result recording.
- Failure summary for Google Access Token saved-state behavior.
- Follow-up scope for the next investigation step.

Out of scope:

- Production PHP, JavaScript, CSS, `readme.txt`, Composer, PHPCS, distribution,
  version, or metadata changes.
- Codex browser operation.
- GA4 Fetch click.
- OpenAI Generate / Generate AI Report click.
- Google OAuth flow.
- GA4, OpenAI, Google OAuth, or other external API communication.
- Credential value inspection.
- `wp_options` credential or plugin settings option inspection.
- Request body, payload, raw response, or generated report inspection.
- Screenshot recording.
- Commit, release, SVN, GitHub release, or WordPress.org publication action.

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
| Test URL | `http://localhost/wp-dev` |
| Browser | `Chrome 149.0.7827.103` |
| WordPress user role | Administrator |
| Human test date/time | `2026/06/10 09:26:00` |
| WP-CLI plugin status during Step 59 preflight | Active |

Preflight checks before creating this document:

| Check | Result |
|---|---|
| Source path | `/var/www/html/analytics-report-ai` |
| Git status before docs edit | Clean. |
| Git branch | `main` |
| Recent history | Step 57 controlled credential entry checklist commit was present at `HEAD`. |
| Step 56 docs existence | Present: `docs/maturation/step56-pre-e2e-readiness-checkpoint.md`. |
| Step 57 docs existence | Present: `docs/maturation/step57-controlled-credential-entry-save-checklist.md`. |
| WP-CLI plugin status | Active, version `0.1.0`. |
| WP-CLI plugin list | Includes `analytics-report-ai`. |

## Source of Results

The results in this document come from the Step 58 human controlled credential
entry/save/non-redisplay run report provided to Codex.

Codex did not perform the browser test, did not enter or save credentials, did
not inspect screenshots, and did not independently verify browser behavior.

Screenshots are not recorded in this document.

## Test Data / Status

Only status-level credential evidence is recorded.

| Field | Status |
|---|---|
| GA4 Property ID | entered |
| Hostname filter | checked |
| Hostname | `localhost` |
| Google Access Token | entered |
| OpenAI API Key | entered |

Notes:

- Real credential values were not provided to Codex.
- Real credential values are not recorded in this document.
- Credential prefixes, suffixes, fragments, token-like strings, JWT fragments,
  and values beginning with `sk-` are not recorded.
- Google Access Token and OpenAI API Key are recorded only as status-level
  `entered` evidence.

## Safety Constraints

The Step 58 human run and this Step 59 result document are recorded under these
constraints:

- Real credential values were not shared with Codex.
- Credential values, prefixes, suffixes, fragments, API keys, access tokens,
  Authorization headers, full request bodies, full payloads, raw responses, and
  generated reports are not recorded.
- `wp_options` credential or plugin settings option values were not inspected,
  displayed, or recorded.
- GA4 Fetch was not clicked.
- Generate AI Report / OpenAI Generate was not clicked.
- Google OAuth was not started.
- GA4, OpenAI, Google OAuth, and other external API communication were not
  performed as part of this result-recording step.
- Browser DevTools network request bodies were not recorded.
- Screenshots are not recorded.
- Cleanup was performed through the browser UI.

## Results Summary

| Category | Pass | Fail | Blocked | Not tested |
|---|---:|---:|---:|---:|
| Manual credential entry | 8 | 1 | 0 | 0 |
| Post-save non-redisplay | 9 | 0 | 0 | 0 |
| JavaScript console | 5 | 0 | 0 | 0 |
| Cleanup / credential removal | 5 | 0 | 0 | 0 |
| Total | 27 | 1 | 0 | 0 |

Not-performed external/API actions are listed separately and are not included
in the summary totals.

## Detailed Results

| ID | Category | Check | Status | Notes |
|---|---|---|---|---|
| CRED-001 | Manual credential entry | Settings screen opens. | Pass | Human Step 58 result reported pass. |
| CRED-002 | Manual credential entry | No visible fatal error, warning, or notice on initial display. | Pass | Human Step 58 result reported pass. |
| CRED-003 | Manual credential entry | GA4 Property ID can be entered or retained. | Fail | Recorded as the observed Google Access Token saved-state issue, not a GA4 Property ID input failure. See Failure Summary. |
| CRED-004 | Manual credential entry | Hostname filter can be set or retained. | Pass | Human Step 58 result reported pass. |
| CRED-005 | Manual credential entry | Hostname can be entered or retained. | Pass | Hostname status: `localhost`. |
| CRED-006 | Manual credential entry | Save Settings can be clicked. | Pass | Human Step 58 result reported pass. |
| CRED-007 | Manual credential entry | Save completion notice is displayed. | Pass | Human Step 58 result reported pass. |
| CRED-008 | Manual credential entry | Save notice is secret-free. | Pass | Human Step 58 result reported pass. |
| CRED-009 | Manual credential entry | No visible fatal error, warning, or notice after save. | Pass | Human Step 58 result reported pass. |
| EVIDENCE-001 | Status evidence | Google Access Token was entered by the human tester in the browser. | entered | Status evidence only; not included in Pass totals. No value, prefix, suffix, or fragment recorded. |
| EVIDENCE-002 | Status evidence | OpenAI API Key was entered by the human tester in the browser. | entered | Status evidence only; not included in Pass totals. No value, prefix, suffix, or fragment recorded. |
| REDISPLAY-001 | Post-save non-redisplay | Settings screen can be reloaded. | Pass | Human Step 58 result reported pass. |
| REDISPLAY-002 | Post-save non-redisplay | Google Access Token is not redisplayed in plaintext. | Pass | Human Step 58 result reported pass. |
| REDISPLAY-003 | Post-save non-redisplay | OpenAI API Key is not redisplayed in plaintext. | Pass | Human Step 58 result reported pass. |
| REDISPLAY-004 | Post-save non-redisplay | Credential status / notice does not expose secret values. | Pass | Human Step 58 result reported pass. |
| REDISPLAY-005 | Post-save non-redisplay | Authorization header or token-like value is not shown on screen. | Pass | Human Step 58 result reported pass. |
| REDISPLAY-006 | Post-save non-redisplay | Request body, payload, raw response, and generated report are not shown on screen. | Pass | Human Step 58 result reported pass. |
| REDISPLAY-007 | Post-save non-redisplay | Non-credential fields display as expected. | Pass | Human Step 58 result reported pass. |
| REDISPLAY-008 | Post-save non-redisplay | Report Builder screen still opens. | Pass | Human Step 58 result reported pass. |
| REDISPLAY-009 | Post-save non-redisplay | No visible fatal error, warning, or notice on Report Builder initial display. | Pass | Human Step 58 result reported pass. |
| JS-001 | JavaScript console | No obvious JavaScript error on Settings initial display. | Pass | Human Step 58 result reported pass. |
| JS-002 | JavaScript console | No obvious JavaScript error after Settings save. | Pass | Human Step 58 result reported pass. |
| JS-003 | JavaScript console | No obvious JavaScript error after Settings reload. | Pass | Human Step 58 result reported pass. |
| JS-004 | JavaScript console | Console does not show credentials, tokens, API keys, Authorization headers, payloads, raw responses, or generated reports. | Pass | Human Step 58 result reported pass. |
| JS-005 | JavaScript console | No obvious JavaScript error on Report Builder initial display. | Pass | Human Step 58 result reported pass. |
| CLEANUP-001 | Cleanup / credential removal | Cleanup decision was recorded. | Pass | Outcome: `cleared via UI`. |
| CLEANUP-002 | Cleanup / credential removal | Google Access Token was cleared through browser UI. | Pass | Status-level evidence only; no value recorded. |
| CLEANUP-003 | Cleanup / credential removal | OpenAI API Key was cleared through browser UI. | Pass | Status-level evidence only; no value recorded. |
| CLEANUP-004 | Cleanup / credential removal | Cleanup result was recorded. | Pass | Outcome: `cleared via UI`. |
| CLEANUP-005 | Cleanup / credential removal | Plaintext credentials were not displayed after cleanup. | Pass | Human Step 58 result reported pass. |

## Failure Summary

Step 58 found a new failure related to Google Access Token saved-state behavior.

Summary:

- When GA4 Property ID, Google Access Token, and OpenAI API Key are saved
  together from an empty/not-saved Settings state, Google Access Token may not
  appear as saved afterward.
- OpenAI API Key appears saved under the same simultaneous-save condition.
- When GA4 Property ID is saved first, then Google Access Token and OpenAI API
  Key are saved afterward, Google Access Token appears saved.
- The issue may be in Google Access Token save handling, credential update
  conditions, saved-status display conditions, empty/new value detection,
  option update order, or post-redirect display state.
- Because `wp_options` values were not inspected, it is not yet known whether
  the underlying option save failed or only the saved-status display failed.

Current classification:

- `Fail`
- Area: Settings credential save/status behavior.
- Specific target: Google Access Token saved state.
- Severity for E2E progression: must investigate before GA4/OpenAI real E2E.

## Reproduction Notes

Primary reproduction:

1. Open the Settings screen.
2. Confirm GA4 Property ID, Google Access Token, and OpenAI API Key are empty
   and not in a saved state.
3. Enter a dummy GA4 Property ID value.
4. Enter a dummy Google Access Token value.
5. Enter a dummy OpenAI API Key value.
6. Click Save Settings.
7. Google Access Token is not shown as saved.
8. OpenAI API Key is shown as saved.

Additional finding:

1. Open the Settings screen.
2. Confirm GA4 Property ID, Google Access Token, and OpenAI API Key are empty
   and not in a saved state.
3. Enter a dummy GA4 Property ID value.
4. Click Save Settings.
5. Enter a dummy Google Access Token value.
6. Enter a dummy OpenAI API Key value.
7. Click Save Settings.
8. Google Access Token is shown as saved.
9. OpenAI API Key is shown as saved.

No dummy credential strings, real credential strings, credential prefixes,
credential suffixes, token fragments, JWT fragments, or API key-like strings
are recorded in these notes.

## Security Notes

- This document records human Step 58 results only.
- Codex did not perform browser verification.
- Codex did not enter, save, display, copy, or record credential values.
- Google Access Token and OpenAI API Key are recorded only as status-level
  `entered` and `cleared via UI` evidence.
- Credential plaintext was not redisplayed according to the Step 58 human
  result.
- Notices and credential status were reported as secret-free.
- Console output was reported as secret-free.
- Screenshots are not recorded.
- `wp_options` credential or plugin settings option values were not displayed,
  inspected, or recorded.
- Authorization headers were not recorded.
- Full request bodies were not recorded.
- Full AI payload bodies were not recorded.
- Raw GA4 and OpenAI response bodies were not recorded.
- Full generated report bodies were not recorded.

## Cleanup / Restore Result

Cleanup result: `cleared via UI`.

| Item | Result | Notes |
|---|---|---|
| Credentials retained after Step 58 | No | Cleared through the browser UI. |
| Google Access Token cleanup | cleared via UI | No value recorded. |
| OpenAI API Key cleanup | cleared via UI | No value recorded. |
| Plaintext display after cleanup | Pass | Human Step 58 result reported pass. |
| CLI/DB option value inspection | Not performed | Credential values were not inspected. |

## Not Performed

The following were intentionally not performed and are not counted in the
Results Summary:

| Action | Status | Notes |
|---|---|---|
| GA4 Fetch click | Not Performed | External API E2E remains separate. |
| Generate AI Report / OpenAI Generate click | Not Performed | External API E2E remains separate. |
| Google OAuth start | Not Performed | OAuth redesign remains separate. |
| `wp_options` credential/plugin settings value inspection | Not Performed | Values must not be displayed or recorded. |
| Request body inspection | Not Performed | Full request bodies must not be recorded. |
| Full AI payload inspection | Not Performed | Full payloads must not be recorded. |
| Raw response inspection | Not Performed | Raw GA4/OpenAI response bodies must not be recorded. |
| Generated report body inspection | Not Performed | Full generated report text must not be recorded. |

## Remaining Risks / Follow-up

- Google Access Token does not appear saved when GA4 Property ID, Google Access
  Token, and OpenAI API Key are saved together from an empty/not-saved state.
- OpenAI API Key appears saved under the same simultaneous-save condition.
- If GA4 Property ID is saved first, Google Access Token appears saved on the
  next credential save.
- The root cause is not yet confirmed.
- The next step should investigate with minimal production-code scope:
  Settings sanitize/save handling, Google Access Token update conditions,
  saved-status display conditions, empty/new value detection, option update
  order, and post-redirect display state.
- The next step must avoid displaying or recording credential values.
- The next step should distinguish between an underlying option-save failure
  and a saved-status display failure without leaking secrets.
- GA4/OpenAI real E2E should wait until this Google Access Token saved-state
  issue is understood and resolved or explicitly accepted with mitigation.

## Next Step Notes

- The next step should be a focused investigation of the Google Access Token
  saved-state failure.
- Keep the investigation limited to Settings credential save/status behavior.
- Do not start GA4 Fetch E2E in the next step.
- Do not start OpenAI Generate E2E in the next step.
- Do not start Google OAuth redesign in the next step.
- Continue to avoid credential value display, option value dumps, screenshots
  containing sensitive state, and full request/payload/response/report bodies.
- WordPress.org release remains `Hold`.
