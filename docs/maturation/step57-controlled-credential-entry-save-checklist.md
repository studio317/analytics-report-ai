# Step 57: Controlled Credential Entry Save Checklist

## Purpose

This document prepares a human-run checklist for controlled credential entry,
save, and non-redisplay verification before any GA4 or OpenAI real E2E test.

Step 57 is preparation only. Codex does not open the browser, enter
credentials, save credentials, display credentials, record credentials, click
GA4 Fetch, click OpenAI Generate, start Google OAuth, or perform external API
communication in this step.

The actual credential entry/save/non-redisplay run must happen in a later
explicit human test step.

## Scope

In scope:

- Repository and WordPress test environment preflight checks.
- Reference documentation presence checks.
- Human-only Settings screen credential entry checklist.
- Post-save credential non-redisplay checklist.
- JavaScript console checklist around Settings display/save/reload.
- Cleanup or credential retention checklist.
- Strict evidence, redaction, and result-recording rules.

Out of scope:

- Codex browser operation.
- GA4 Fetch click.
- OpenAI Generate / Generate AI Report click.
- Google OAuth flow.
- GA4, OpenAI, Google OAuth, or other external API communication.
- Credential value inspection.
- `wp_options` credential or plugin settings option inspection.
- Production code changes.
- Commit, release, SVN, GitHub release, or WordPress.org publication action.

## Preconditions

- Source checkout exists at `/var/www/html/analytics-report-ai`.
- WordPress test environment exists at `/var/www/html/wp-dev`.
- WordPress site URL is `http://localhost/wp-dev`.
- Analytics Report AI is installed at
  `/var/www/html/wp-dev/wp-content/plugins/analytics-report-ai`.
- The human tester can log in as a WordPress administrator.
- The human tester controls any real credential used for this later manual
  test.
- Step 56 decision is `Ready for controlled local developer E2E testing`.
- WordPress.org release position remains `Hold`.

Preflight checks run before creating this document:

| Check | Result |
|---|---|
| Source path | `/var/www/html/analytics-report-ai` |
| Git status before docs edit | Clean. |
| Git branch | `main` |
| Recent history | Step 56 checkpoint commit was present at `HEAD`. |
| Step 50 docs existence | Present: `docs/maturation/step50-safe-settings-save-smoke-checklist.md`. |
| Step 52 docs existence | Present: `docs/maturation/step52-human-settings-save-smoke-results.md`. |
| Step 55 docs existence | Present: `docs/maturation/step55-settings-save-notice-recheck-results.md`. |
| Step 56 docs existence | Present: `docs/maturation/step56-pre-e2e-readiness-checkpoint.md`. |
| WP-CLI plugin status | Active, version `0.1.0`. |
| WP-CLI plugin list | Includes `analytics-report-ai`. |

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
| Settings URL | `http://localhost/wp-dev/wp-admin/admin.php?page=analytics-report-ai-settings` |
| Report Builder URL | `http://localhost/wp-dev/wp-admin/admin.php?page=analytics-report-ai` |
| Test status | Pending manual verification. |

## Safety Rules

- Treat all credentials as secrets, even in local developer E2E.
- Keep real credentials in the browser only.
- Keep evidence status-level and redacted.
- Do not record credential values, partial credential values, option values,
  request bodies, payload bodies, raw responses, or generated report bodies.
- Do not use CLI or database queries to print plugin settings or credential
  option values.
- If screenshots are used later, verify that they contain no credential state,
  payloads, raw responses, generated reports, personal information, private
  site details, or other sensitive data.
- Prefer secret-free text notes over screenshots.
- If a failure occurs, record only a short secret-free summary.

## Human-Only Credential Handling

- Real credentials are handled only by the human tester in the browser.
- Do not give real credentials to Codex.
- Do not paste real credentials into chat, docs, terminal, logs, screenshots,
  git commits, issues, pull requests, or release notes.
- Do not record even part of a real credential.
- Do not record API key prefixes or suffixes.
- Do not record Access Token fragments, JWT headers, or JWT payloads.
- Do not record values that begin with `sk-`.
- Be aware that browser password managers, autofill, clipboard history, and
  browser history may retain sensitive data.
- Use private handling appropriate to the human tester's local machine and
  browser profile.
- After testing, decide whether credentials should be cleared or retained for
  the next local E2E step.
- If credentials are cleared, use the browser UI clear controls.
- Do not use CLI, database tools, or `wp option get` to display credential
  option values.

## Do Not Perform

- Do not let Codex operate the browser.
- Do not click **Fetch GA4 Data** in this step.
- Do not click **Generate AI Report** or any OpenAI Generate action in this
  step.
- Do not start a Google OAuth flow.
- Do not test GA4 API connectivity.
- Do not test OpenAI API connectivity.
- Do not inspect DevTools network request bodies.
- Do not display, copy, or record existing credential, token, API key, or
  option values.
- Do not display or record `wp_options` credential or plugin settings option
  values.
- Do not record Authorization headers.
- Do not record full request bodies.
- Do not record full AI payload bodies.
- Do not record raw GA4 or OpenAI response bodies.
- Do not record full generated report text.

## Browser URL

Settings:

`http://localhost/wp-dev/wp-admin/admin.php?page=analytics-report-ai-settings`

Report Builder follow-up check:

`http://localhost/wp-dev/wp-admin/admin.php?page=analytics-report-ai`

## Test Data Policy

This checklist is for real credential save and non-redisplay preparation. The
human tester may use real local verification credentials only in the browser,
and only in a later explicit manual run.

Allowed test inputs:

| Field | Allowed value policy | Notes |
|---|---|---|
| Google Access Token | Human tester-managed local verification token. | Browser only. Never record the value or any fragment. |
| OpenAI API Key | Human tester-managed local verification key. | Browser only. Never record the value, prefix, suffix, or any fragment. |
| GA4 Property ID | Use the real value intended for later GA4 Fetch E2E, or a dummy value if this run only verifies credential non-redisplay. | Do not record a private production property ID. |
| Hostname filter | Set to the state needed for the later E2E path, or any safe state for credential-only verification. | Record only status-level result. |
| Hostname | Use the later E2E target hostname if needed, or `localhost` for credential-only verification. | Do not record private site details. |

Allowed result evidence:

- Google Access Token: `entered`, `not entered`, or `cleared`.
- OpenAI API Key: `entered`, `not entered`, or `cleared`.
- Plaintext credential non-redisplay: `Pass` or `Fail`.
- Secret-free credential status: `Pass` or `Fail`.
- Cleanup status: `cleared via UI`, `retained for next local E2E`, or
  `not tested`.

Do not record:

- Real Google Access Token full value.
- Any part of a real Google Access Token.
- Real OpenAI API Key full value.
- Any part of a real OpenAI API Key.
- Values containing an `sk-` prefix.
- JWT-like fragments.
- Authorization header.
- Saved option values from `wp_options`.
- Browser DevTools network request bodies.
- Full request body, payload body, raw response body, or generated report body.

## Manual Credential Entry Checklist

Use only `Pass`, `Fail`, `Blocked`, `Not tested`, or
`Pending manual verification` in the Result column. Initial values are
`Pending manual verification`.

| ID | Action | Expected Result | Result | Notes |
|---|---|---|---|---|
| CRED-001 | Log in to the WordPress admin area. | Admin dashboard is reachable. | Pending manual verification | Do not record usernames, passwords, cookies, or session values. |
| CRED-002 | Open the Settings screen. | Settings screen opens at the Settings URL. | Pending manual verification | Browser operation is human-only. |
| CRED-003 | Inspect initial Settings display. | No visible fatal error, warning, or unexpected notice appears. | Pending manual verification | A normal WordPress/admin notice may be recorded only as a secret-free summary. |
| CRED-004 | Prepare GA4 Property ID. | Field is filled with the intended real E2E value or a dummy value, or an existing safe value is retained. | Pending manual verification | Do not record a private production property ID. |
| CRED-005 | Prepare Hostname filter. | Hostname filter is set to the intended state or existing state is retained. | Pending manual verification | Record only status-level result. |
| CRED-006 | Prepare Hostname. | Hostname is filled with the intended E2E hostname or `localhost`, or an existing safe value is retained. | Pending manual verification | Do not record private site details. |
| CRED-007 | Human tester enters Google Access Token in the browser. | Token is entered only by the human tester. | Pending manual verification | Record only `entered`, `not entered`, or `cleared`. Do not record any fragment. |
| CRED-008 | Human tester enters OpenAI API Key in the browser. | Key is entered only by the human tester. | Pending manual verification | Record only `entered`, `not entered`, or `cleared`. Do not record prefix, suffix, or any fragment. |
| CRED-009 | Click Save Settings. | Save action completes. | Pending manual verification | Do not click GA4 Fetch, Generate AI Report, or OAuth controls. |
| CRED-010 | Confirm save completion notice. | A normal save completion notice is displayed. | Pending manual verification | Notice must be secret-free. |
| CRED-011 | Confirm save notice content. | Notice does not include credential, token, API key, Authorization, payload, raw response, or generated report content. | Pending manual verification | If any sensitive value appears, mark Fail without copying it. |
| CRED-012 | Inspect page after save. | No visible fatal error, warning, or unexpected notice appears after save. | Pending manual verification | Record only secret-free issue summaries. |
| CRED-013 | Confirm prohibited actions were not taken. | GA4 Fetch, Generate AI Report, and Google OAuth were not clicked or started. | Pending manual verification | External API actions remain out of scope. |

## Post-Save Non-Redisplay Checklist

Use only `Pass`, `Fail`, `Blocked`, `Not tested`, or
`Pending manual verification` in the Result column. Initial values are
`Pending manual verification`.

| ID | Check | Expected Result | Result | Notes |
|---|---|---|---|---|
| REDISPLAY-001 | Reload the Settings screen. | Settings screen reloads successfully. | Pending manual verification | Do not inspect option values by CLI or DB. |
| REDISPLAY-002 | Google Access Token field. | Plaintext token value is not redisplayed. | Pending manual verification | Do not record field value, placeholder content that reveals a secret, or token fragments. |
| REDISPLAY-003 | OpenAI API Key field. | Plaintext key value is not redisplayed. | Pending manual verification | Do not record key prefix, suffix, or fragments. |
| REDISPLAY-004 | Credential status and notices. | Status/notice content remains secret-free. | Pending manual verification | Record only `Pass` or a short secret-free failure summary. |
| REDISPLAY-005 | Authorization/token-like screen exposure. | No Authorization header, token-like value, or API key-like value appears on screen. | Pending manual verification | If found, mark Fail without copying the value. |
| REDISPLAY-006 | Request/payload/response/report exposure. | No full request body, AI payload, raw response, or generated report appears on screen. | Pending manual verification | Do not open or copy payload/raw response/generated report content. |
| REDISPLAY-007 | Non-credential fields. | GA4 Property ID, hostname filter, and hostname show the expected saved or retained state. | Pending manual verification | Record only status-level result. |
| REDISPLAY-008 | Report Builder availability. | Report Builder screen still opens. | Pending manual verification | Do not click Fetch GA4 Data. |
| REDISPLAY-009 | Report Builder initial display. | No visible fatal error, warning, or unexpected notice appears on initial display. | Pending manual verification | Do not click Generate AI Report. |

## JavaScript Console Checklist

Open browser developer tools only for the current Settings or Report Builder
page. Do not copy console output containing sensitive data.

Use only `Pass`, `Fail`, `Blocked`, `Not tested`, or
`Pending manual verification` in the Result column. Initial values are
`Pending manual verification`.

| ID | Check | Expected Result | Result | Notes |
|---|---|---|---|---|
| JS-001 | Settings initial display console. | No obvious JavaScript error appears on initial Settings display. | Pending manual verification | Record only error type if a failure occurs. |
| JS-002 | Settings save console. | No obvious JavaScript error appears after Save Settings. | Pending manual verification | Do not record request bodies or form values. |
| JS-003 | Settings reload console. | No obvious JavaScript error appears after Settings reload. | Pending manual verification | Record status-level result only. |
| JS-004 | Secret exposure in console. | Console does not show credentials, tokens, API keys, Authorization headers, payloads, raw responses, or generated reports. | Pending manual verification | If sensitive data appears, mark Fail and do not copy the sensitive value. |
| JS-005 | Report Builder initial display console. | No obvious JavaScript error appears when Report Builder opens after Settings save. | Pending manual verification | Do not click Fetch GA4 Data or Generate AI Report. |

## Cleanup / Credential Removal Checklist

After the manual run, the human tester decides whether to clear or retain
credentials.

- If the next explicit step is GA4 Fetch or OpenAI E2E, credentials may be
  temporarily retained for the next local E2E step.
- If credentials are retained, record only
  `credentials retained for next local E2E step`.
- If credentials are removed, use the browser UI clear controls.
- Do not use CLI, database tools, or `wp option get` to display credential
  option values.
- Cleanup results should use status-level wording such as `cleared via UI`,
  `retained for next local E2E`, or `not tested`.
- Never record real values.

Use only `Pass`, `Fail`, `Blocked`, `Not tested`, or
`Pending manual verification` in the Result column. Initial values are
`Pending manual verification`.

| ID | Action | Expected Result | Result | Notes |
|---|---|---|---|---|
| CLEANUP-001 | Decide whether credentials should be retained for the next local E2E step. | Decision is recorded as status-level only. | Pending manual verification | Allowed notes: `retained for next local E2E`, `clear via UI`, or `not tested`. |
| CLEANUP-002 | If clearing Google Access Token, use the browser UI clear control. | Google Access Token is cleared without displaying or recording its value. | Pending manual verification | Do not use CLI or DB inspection. |
| CLEANUP-003 | If clearing OpenAI API Key, use the browser UI clear control. | OpenAI API Key is cleared without displaying or recording its value. | Pending manual verification | Do not record prefix, suffix, or fragments. |
| CLEANUP-004 | Save Settings after choosing cleanup state. | Save action completes. | Pending manual verification | Do not click GA4/OpenAI/OAuth actions. |
| CLEANUP-005 | Recheck credential fields after cleanup save. | Cleared credentials are not displayed; retained credentials are not redisplayed in plaintext. | Pending manual verification | Record only status-level result. |
| CLEANUP-006 | Record cleanup outcome. | Outcome is recorded as `cleared via UI`, `retained for next local E2E`, or `not tested`. | Pending manual verification | Never record real values. |

## Result Recording Rules

Before manual execution, checklist rows must remain
`Pending manual verification`.

After manual execution, use only:

- `Pass`: the expected result was observed.
- `Fail`: the expected result was not observed.
- `Blocked`: the check could not be performed because access, account, browser,
  environment, credential availability, or safe state was unavailable.
- `Not tested`: the check was intentionally skipped.

For failures:

- Record only a short secret-free summary.
- Do not paste credential values, fragments, prefixes, suffixes, request
  bodies, payloads, raw responses, generated reports, cookies, nonces, or
  option values.
- If a secret appears on screen or in console, mark `Fail` and summarize the
  exposure category without copying the value.

## Security / Redaction Rules

Allowed status-level notes:

- `entered`
- `not entered`
- `cleared`
- `cleared via UI`
- `retained for next local E2E`
- `credentials retained for next local E2E step`
- `secret-free`
- `no plaintext redisplay`
- `no obvious JavaScript error`

Prohibited evidence:

- Real Google Access Token.
- Any part of a real Google Access Token.
- Real OpenAI API Key.
- Any part of a real OpenAI API Key.
- API key prefix or suffix.
- Values beginning with `sk-`.
- JWT header or payload fragments.
- Authorization header.
- Full request body.
- Full AI payload body.
- Raw GA4 response body.
- Raw OpenAI response body.
- Full generated report body.
- `wp_options` credential or plugin settings option values.
- Browser cookie values.
- Session values.
- Nonce values.
- Personal information.
- Private site data.
- Screenshots containing credential state, payloads, raw responses, generated
  reports, personal information, private site details, or other sensitive data.

## Summary Template After Manual Run

Fill this table only after the later human manual run. Until then, the detail
rows above remain `Pending manual verification`.

| Category | Pass | Fail | Blocked | Not tested |
|---|---:|---:|---:|---:|
| Manual credential entry | 0 | 0 | 0 | 0 |
| Post-save non-redisplay | 0 | 0 | 0 | 0 |
| JavaScript console | 0 | 0 | 0 | 0 |
| Cleanup / credential removal | 0 | 0 | 0 | 0 |
| External API actions intentionally not performed | 0 | 0 | 0 | 0 |
| Total | 0 | 0 | 0 | 0 |

## Next Step Notes

- Step 58 can be the human controlled credential entry/save/non-redisplay run.
- Step 59 can document the Step 58 results.
- GA4 Fetch E2E should remain separate and should not start until credential
  save/non-redisplay is confirmed.
- OpenAI Generate E2E should remain after GA4 Fetch E2E.
- WordPress.org release remains `Hold`.
