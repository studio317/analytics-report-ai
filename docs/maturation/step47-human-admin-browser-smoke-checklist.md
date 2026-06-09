# Step 47: Human Admin Browser Smoke Checklist

## Purpose

This document is a human-run browser smoke checklist and result-recording
template for the browser/admin items that remained blocked after Step 46.

Codex did not perform browser verification in this step and does not mark any
browser item as `Pass`. Manual browser results should be recorded by a human
tester after logging in to the WordPress admin area.

This checklist is limited to safe admin UI observation. It must not trigger
GA4, OpenAI, Google OAuth, or other external API communication.

## Preconditions

- Source checkout exists at `/var/www/html/analytics-report-ai`.
- WordPress test environment exists at `/var/www/html/wp-dev`.
- The plugin is installed in the WordPress test environment by symlink:
  `/var/www/html/wp-dev/wp-content/plugins/analytics-report-ai`.
- The symlink target is `/var/www/html/analytics-report-ai`.
- WP-CLI recognizes the plugin as `analytics-report-ai`.
- WP-CLI reports Analytics Report AI as `Active`.
- Step 45 docs exist:
  `docs/maturation/step45-wp-test-install-admin-browser-smoke-results.md`.
- Step 46 docs exist:
  `docs/maturation/step46-admin-browser-smoke-results.md`.
- The human tester can log in to the WordPress admin area for
  `http://localhost/wp-dev`.
- No real Google Access Token or OpenAI API Key is required for this checklist.

Preflight checks run before creating this document:

| Check | Result |
|---|---|
| Source path | `/var/www/html/analytics-report-ai` |
| Git branch | `main` |
| Git status before docs edit | Clean. |
| Step 45 docs existence | Present. |
| Step 46 docs existence | Present. |
| WP test path | `/var/www/html/wp-dev` |
| Plugin symlink | Present. |
| Symlink target | `/var/www/html/analytics-report-ai` |
| WP-CLI plugin status | Active, version `0.1.0`. |
| WP-CLI plugin list | Includes `analytics-report-ai`. |
| Site URL | `http://localhost/wp-dev` |

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
| Install method | Symlink to the development checkout. |
| Browser verification status | Pending manual verification. |

## Safety Rules

- Use only status-level observations.
- Prefer short text notes over screenshots.
- If screenshots are used, confirm they do not contain credential state,
  payload content, raw responses, generated report text, personal information,
  or other sensitive data.
- Do not enter credentials.
- Do not save settings.
- Do not inspect or record plugin credential option values.
- Do not record full request bodies, full payload bodies, raw responses, or
  generated reports.
- If a failure appears, record only a short secret-free summary.

## Do Not Perform

- Do not click **Fetch GA4 Data**.
- Do not click **Generate AI Report**.
- Do not start a Google OAuth flow.
- Do not enter a real Google Access Token.
- Do not enter a real OpenAI API Key.
- Do not save credential fields.
- Do not display or record existing credential values.
- Do not inspect or record `wp_options` credential or plugin settings option
  values.
- Do not record Authorization headers.
- Do not record full request bodies.
- Do not record full AI payload bodies.
- Do not record raw GA4 or OpenAI response bodies.
- Do not record full generated report text.

## Browser URLs

- Plugins:
  `http://localhost/wp-dev/wp-admin/plugins.php`
- Report Builder:
  `http://localhost/wp-dev/wp-admin/admin.php?page=analytics-report-ai`
- Settings:
  `http://localhost/wp-dev/wp-admin/admin.php?page=analytics-report-ai-settings`

## Manual Execution Checklist

Use only `Pass`, `Fail`, `Blocked`, `Not tested`, or
`Pending manual verification` in the Result column.

| ID | Screen | Action | Expected Result | Result | Notes |
|---|---|---|---|---|---|
| MAN-001 | Login | Log in to the WordPress admin area. | Admin dashboard is reachable. | Pending manual verification | Do not record credentials or cookies. |
| MAN-002 | Plugins | Open `/wp-admin/plugins.php`. | Plugins screen opens. | Pending manual verification | Do not record unrelated plugin details unless needed. |
| MAN-003 | Plugins | Locate Analytics Report AI. | Analytics Report AI appears in the plugin list. | Pending manual verification | Record status-level result only. |
| MAN-004 | Plugins | Check Analytics Report AI activation state. | Plugin appears active. | Pending manual verification | WP-CLI already reported Active; browser visual confirmation remains pending. |
| MAN-005 | Admin menu | Inspect the admin menu. | Analytics Report AI menu appears for the administrator. | Pending manual verification | Do not click external-action buttons. |
| MAN-006 | Report Builder | Open `/wp-admin/admin.php?page=analytics-report-ai`. | Report Builder screen opens. | Pending manual verification | Do not click Fetch GA4 Data. |
| MAN-007 | Settings | Open `/wp-admin/admin.php?page=analytics-report-ai-settings`. | Settings screen opens. | Pending manual verification | Do not enter or save credentials. |
| MAN-008 | Report Builder | Inspect the page after initial load. | No visible fatal error, warning, or notice appears. | Pending manual verification | If failing, record a short secret-free summary. |
| MAN-009 | Settings | Inspect the page after initial load. | No visible fatal error, warning, or notice appears. | Pending manual verification | If failing, record a short secret-free summary. |

## JavaScript Console Checklist

Open browser developer tools only for the current admin page. Do not copy
console output that includes sensitive data.

| ID | Screen | Check | Expected Result | Result | Notes |
|---|---|---|---|---|---|
| JS-001 | Report Builder | Check console on initial load. | No obvious JavaScript console error appears. | Pending manual verification | Record only error type if a failure occurs. |
| JS-002 | Settings | Check console on initial load. | No obvious JavaScript console error appears. | Pending manual verification | Record only error type if a failure occurs. |
| JS-003 | Report Builder | Switch scope options without submitting the form. | No obvious JavaScript console error appears. | Pending manual verification | Do not click Fetch GA4 Data. |
| JS-004 | Report Builder / Settings | Review visible console output. | Console does not show credentials, tokens, Authorization headers, full payloads, raw responses, or generated reports. | Pending manual verification | If sensitive data appears, mark Fail and do not copy the sensitive value. |

## Report Builder UI Checklist

Do not submit the Report Builder form. The Fetch GA4 Data and Generate AI
Report buttons may be observed for presence only.

| ID | Check | Expected Result | Result | Notes |
|---|---|---|---|---|
| RB-001 | Start date and end date inputs display. | Both date inputs are visible. | Pending manual verification | Record status only. |
| RB-002 | Comparison UI displays. | Comparison choices are visible. | Pending manual verification | Do not submit the form. |
| RB-003 | Data scope UI displays. | Scope choices are visible. | Pending manual verification | Record status only. |
| RB-004 | Site / directory / page switching UI displays. | All scope choices are visible and selectable. | Pending manual verification | Do not submit the form. |
| RB-005 | Scope switching changes path input visibility/state. | Path input visibility or text updates according to selected scope. | Pending manual verification | Do not submit the form. |
| RB-006 | Initial state does not show unintended payload content. | No unexpected full payload is displayed on initial page load. | Pending manual verification | Do not open or record payload JSON. |
| RB-007 | Initial state does not show raw external responses. | No raw GA4 or OpenAI response is displayed. | Pending manual verification | If visible, mark Fail without copying response text. |
| RB-008 | Initial state does not show generated report text unexpectedly. | No generated report body is shown unless a known safe local state exists. | Pending manual verification | Do not record generated text. |
| RB-009 | Fetch GA4 Data button presence. | Button is visible if the form is available. | Pending manual verification | Presence only; do not click. |
| RB-010 | Generate AI Report button presence/state. | Button is absent or visible only according to current safe state. | Pending manual verification | Presence/state only; do not click. |
| RB-011 | Textarea / copy button UI display state. | UI is absent or visible only according to current safe non-generated state. | Pending manual verification | Use only non-sensitive local text if a safe state exists. |

## Settings UI Checklist

Do not save the Settings form and do not enter credentials.

| ID | Check | Expected Result | Result | Notes |
|---|---|---|---|---|
| SET-001 | GA4 Property ID field displays. | Field is visible. | Pending manual verification | Do not enter real property details unless a later step allows it. |
| SET-002 | Google authentication status display appears. | Status area is visible without showing token values. | Pending manual verification | Do not record existing token state if it could be sensitive. |
| SET-003 | Google Access Token input displays. | Input is visible but saved value is not displayed. | Pending manual verification | Do not enter or save a token. |
| SET-004 | Hostname filter field displays. | Hostname filter control is visible. | Pending manual verification | Do not save settings. |
| SET-005 | Hostname field displays. | Hostname field is visible. | Pending manual verification | Avoid recording sensitive internal hostnames. |
| SET-006 | OpenAI API Key field displays. | Input is visible but saved value is not displayed. | Pending manual verification | Do not enter or save an API key. |
| SET-007 | Credential clear controls display if applicable. | Clear controls are understandable without exposing saved values. | Pending manual verification | Do not click clear controls in this smoke pass. |
| SET-008 | Save button presence. | Save button is visible. | Pending manual verification | Presence only; do not click. |
| SET-009 | External service and credential storage notices display. | Notices are visible and readable. | Pending manual verification | Record status-level result only. |

## Result Recording Rules

Use only these values after manual execution:

- `Pass`: the expected result was observed.
- `Fail`: the expected result was not observed.
- `Blocked`: the check could not be performed because access, account, browser,
  environment, or safe state was unavailable.
- `Not tested`: the check was intentionally skipped, usually because it would
  require external API communication, credentials, payload inspection, or a
  generated report.

Before manual execution, `Pending manual verification` may remain in the
Result column.

For `Fail` results:

- Keep the note short.
- Do not include secrets.
- Do not include full payloads, raw responses, request bodies, or generated
  reports.
- Record enough context to route a follow-up, such as page name and broad error
  type.

## Security / Redaction Rules

- Do not input credentials.
- Do not record credentials.
- Do not record access tokens.
- Do not record OpenAI API keys.
- Do not record Authorization headers.
- Do not record full request bodies.
- Do not record full AI payload bodies.
- Do not record raw GA4 responses.
- Do not record raw OpenAI responses.
- Do not record full generated report text.
- Do not record plugin credential option values.
- Do not inspect or record `wp_options` credential or plugin settings option
  values.
- If screenshots are used, first confirm they do not contain credential state,
  payload content, raw responses, generated report text, personal information,
  private site details, or other sensitive data.
- Prefer status-level text notes over screenshots wherever possible.

## Summary Template After Manual Run

Fill this table only after a human browser run is completed.

| Category | Pass | Fail | Blocked | Not tested |
|---|---:|---:|---:|---:|
| Manual execution | 0 | 0 | 0 | 0 |
| JavaScript console | 0 | 0 | 0 | 0 |
| Report Builder UI | 0 | 0 | 0 | 0 |
| Settings UI | 0 | 0 | 0 | 0 |
| External API / credential actions | 0 | 0 | 0 | 0 |
| Total | 0 | 0 | 0 | 0 |

Manual run summary fields:

| Field | Value |
|---|---|
| Tester | Name / initials |
| Date/time | YYYY-MM-DD HH:MM timezone |
| Browser | Browser and version |
| WordPress user role | Administrator / other |
| Overall result | Pass / Fail / Blocked / Not tested mix |
| Fail summary | Secret-free short summary, or `None`. |
| Blocked summary | Short reason, or `None`. |
| Not tested summary | Short reason, or `None`. |
| Redaction confirmation | Confirm no secrets, payloads, raw responses, or generated reports were recorded. |

## Next Step Notes

- After a human browser run, copy the completed status-level results into a
  follow-up results document rather than editing production code.
- Keep browser smoke separate from Step 45/46 WP-CLI evidence.
- Keep GA4/OpenAI E2E checks in a separate step that explicitly allows
  controlled external API testing.
- If any browser check fails, record a short secret-free issue summary and
  decide the remediation scope separately.
