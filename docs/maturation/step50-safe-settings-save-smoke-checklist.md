# Step 50: Safe Settings Save Smoke Checklist

## Purpose

This document is a human-run checklist and result template for safely testing
the Analytics Report AI Settings save flow before moving to any external API
end-to-end checks.

Step 49 confirmed that the Settings screen renders in the browser. Step 50 does
not record a save result yet. It prepares a safe manual procedure that uses only
dummy or non-secret values, avoids GA4/OpenAI/OAuth communication, and verifies
that saved credential fields are not redisplayed in plaintext.

Codex did not perform the browser save operation in this step and does not mark
any save result as `Pass`.

## Scope

In scope:

- Settings screen manual save flow using safe dummy or non-secret values.
- Non-credential field persistence after save.
- Credential field non-redisplay after save.
- Credential status/notice display without secret exposure.
- Settings page reload after save.
- Report Builder still opens after Settings save.
- JavaScript console smoke checks around Settings display/save/reload.

Out of scope:

- GA4 Fetch click.
- Generate AI Report / OpenAI Generate click.
- Google OAuth flow.
- Real Google Access Token entry or save.
- Real OpenAI API Key entry or save.
- Existing credential value inspection.
- `wp_options` credential or plugin settings option inspection.
- External API end-to-end confirmation.

No production PHP, JavaScript, CSS, `readme.txt`, Composer file, PHPCS config,
distribution config, dry-run script, version, or metadata file is changed by
this step.

## Preconditions

- Source checkout exists at `/var/www/html/analytics-report-ai`.
- WordPress test environment exists at `/var/www/html/wp-dev`.
- WordPress site URL is `http://localhost/wp-dev`.
- Analytics Report AI is installed by symlink at
  `/var/www/html/wp-dev/wp-content/plugins/analytics-report-ai`.
- WP-CLI recognizes `analytics-report-ai`.
- WP-CLI reports Analytics Report AI as `Active`.
- Step 45 docs exist:
  `docs/maturation/step45-wp-test-install-admin-browser-smoke-results.md`.
- Step 46 docs exist:
  `docs/maturation/step46-admin-browser-smoke-results.md`.
- Step 47 docs exist:
  `docs/maturation/step47-human-admin-browser-smoke-checklist.md`.
- Step 49 docs exist:
  `docs/maturation/step49-human-admin-browser-smoke-results.md`.
- The human tester can log in as a WordPress administrator.
- No real credential is needed for this checklist.

Preflight checks run before creating this document:

| Check | Result |
|---|---|
| Source path | `/var/www/html/analytics-report-ai` |
| Git branch | `main` |
| Git status before docs edit | Clean. |
| Step 45 docs existence | Present. |
| Step 46 docs existence | Present. |
| Step 47 docs existence | Present. |
| Step 49 docs existence | Present. |
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
| Settings URL | `http://localhost/wp-dev/wp-admin/admin.php?page=analytics-report-ai-settings` |
| Report Builder URL | `http://localhost/wp-dev/wp-admin/admin.php?page=analytics-report-ai` |
| Install method | Symlink to the development checkout. |
| Save verification status | Pending manual verification. |

## Safety Rules

- Use only dummy or non-secret values.
- Keep evidence status-level and redacted.
- Do not copy values from real services into the form.
- Do not inspect existing saved credential values.
- Do not record credential status in a way that reveals a secret or production
  setup.
- Do not record full request bodies, full payloads, raw responses, or generated
  reports.
- If a failure occurs, record only a short secret-free summary.
- Prefer text notes over screenshots. If screenshots are used, verify that they
  do not contain credential state, payloads, raw responses, generated reports,
  personal information, private site details, or other sensitive data.

## Do Not Perform

- Do not click **Fetch GA4 Data**.
- Do not click **Generate AI Report**.
- Do not start a Google OAuth flow.
- Do not enter or save a real Google Access Token.
- Do not enter or save a real OpenAI API Key.
- Do not display or record existing credential, token, API key, or option
  values.
- Do not inspect or record `wp_options` credential or plugin settings option
  values.
- Do not record Authorization headers.
- Do not record full request bodies.
- Do not record full AI payload bodies.
- Do not record raw GA4 or OpenAI response bodies.
- Do not record full generated report text.

## Test Data Policy

Use only obvious dummy or non-secret values. Treat the values below as local
smoke-test placeholders, not real credentials or real production identifiers.

Values allowed for this checklist:

| Field | Allowed examples | Notes |
|---|---|---|
| GA4 Property ID | `123456789` | Treat as a dummy numeric value, not a real GA4 property. |
| Hostname filter | Checked or unchecked | Safe to toggle for UI/save behavior. |
| Hostname | `localhost`, `example.test` | Do not use real production hostnames. |
| Google Access Token | Prefer leaving empty. If credential UI behavior must be tested, use `DUMMY_GOOGLE_ACCESS_TOKEN_DO_NOT_USE`. | Do not use a value that resembles a real token. |
| OpenAI API Key | Prefer leaving empty. If credential UI behavior must be tested, use `DUMMY_OPENAI_API_KEY_DO_NOT_USE`. | Do not use values beginning with `sk-`. |

Values prohibited for this checklist:

- Real Google Access Token.
- Real OpenAI API Key.
- Any value beginning with `sk-`.
- JWT-like strings.
- Real GA4 Property ID.
- Real production hostname.
- Values that resemble actual credentials.
- Existing saved values copied from the UI, database, logs, browser storage, or
  CLI.
- Values obtained from `wp_options`.

Recommended default path:

1. First run the save smoke without touching credential fields.
2. Confirm non-credential fields save and reload safely.
3. Only if credential UI behavior must be checked, use obvious dummy credential
   strings and clear them afterward using the browser UI clear controls.

## Browser URL

Settings:

`http://localhost/wp-dev/wp-admin/admin.php?page=analytics-report-ai-settings`

Report Builder follow-up check:

`http://localhost/wp-dev/wp-admin/admin.php?page=analytics-report-ai`

## Manual Settings Save Checklist

Use only `Pass`, `Fail`, `Blocked`, `Not tested`, or
`Pending manual verification` in the Result column. Initial values are
`Pending manual verification`.

| ID | Action | Expected Result | Result | Notes |
|---|---|---|---|---|
| SAVE-001 | Log in to the WordPress admin area. | Admin dashboard is reachable. | Pending manual verification | Do not record credentials or cookies. |
| SAVE-002 | Open the Settings screen. | Settings screen opens. | Pending manual verification | Use the Settings URL above. |
| SAVE-003 | Inspect initial Settings display. | No visible fatal error, warning, or notice appears. | Pending manual verification | Record only secret-free issue summaries. |
| SAVE-004 | Enter dummy GA4 Property ID `123456789`. | Field accepts the dummy numeric value. | Pending manual verification | Do not use a real GA4 property ID. |
| SAVE-005 | Set Hostname filter to a safe chosen state. | Checked/unchecked state is selected as intended. | Pending manual verification | Record only the intended state, not private site details. |
| SAVE-006 | Enter Hostname `localhost` or `example.test`. | Field accepts the safe non-production hostname. | Pending manual verification | Do not use production hostnames. |
| SAVE-007 | Leave real Google Access Token empty. | No real token is entered. | Pending manual verification | Preferred path: do not touch the credential field. |
| SAVE-008 | Leave real OpenAI API Key empty. | No real key is entered. | Pending manual verification | Preferred path: do not touch the credential field. |
| SAVE-009 | Save with credential fields untouched. | Settings save completes without entering credentials. | Pending manual verification | This is the recommended main smoke path. |
| SAVE-010 | Optional: if credential save UI must be checked, use only `DUMMY_GOOGLE_ACCESS_TOKEN_DO_NOT_USE`. | Dummy value can be used for UI behavior only. | Pending manual verification | Do not use token-like or real values. Clear afterward if used. |
| SAVE-011 | Optional: if credential save UI must be checked, use only `DUMMY_OPENAI_API_KEY_DO_NOT_USE`. | Dummy value can be used for UI behavior only. | Pending manual verification | Do not use `sk-` or real values. Clear afterward if used. |
| SAVE-012 | Click Save Settings. | Save action completes. | Pending manual verification | Do not click GA4/OpenAI actions. |
| SAVE-013 | Confirm save completion notice. | A normal save/update notice is shown. | Pending manual verification | Do not record sensitive status details. |
| SAVE-014 | Inspect page after save. | No visible fatal error, warning, or notice appears. | Pending manual verification | A normal save notice is acceptable. |
| SAVE-015 | Reload the Settings screen. | Settings screen reloads successfully. | Pending manual verification | Do not inspect option values by CLI. |
| SAVE-016 | Confirm non-credential values persisted. | Dummy GA4 Property ID, host filter state, and hostname display as expected. | Pending manual verification | Record status-level result only. |
| SAVE-017 | Confirm credential fields do not redisplay plaintext values. | Credential inputs do not show saved plaintext values. | Pending manual verification | Do not record placeholder/status if it reveals sensitive context. |
| SAVE-018 | Confirm saved credential status, if any, does not expose the value. | Status/notice remains secret-free. | Pending manual verification | If dummy credential was used, do not copy it into evidence beyond the allowed dummy string. |
| SAVE-019 | Open Report Builder after Settings save. | Report Builder screen still opens. | Pending manual verification | Do not click Fetch GA4 Data. |
| SAVE-020 | Inspect Report Builder initial display after Settings save. | No visible fatal error, warning, or notice appears. | Pending manual verification | Do not record payloads or generated reports. |

## Post-Save Display Checklist

| ID | Check | Expected Result | Result | Notes |
|---|---|---|---|---|
| POST-001 | GA4 Property ID after save. | Dummy numeric value displays as expected. | Pending manual verification | Do not use real property IDs. |
| POST-002 | Hostname filter after save. | Saved checked/unchecked state displays as expected. | Pending manual verification | Record only status-level result. |
| POST-003 | Hostname after save. | Safe hostname displays as expected. | Pending manual verification | Use `localhost` or `example.test`. |
| POST-004 | Google Access Token display. | Saved token value is not shown in plaintext. | Pending manual verification | Do not use or record real tokens. |
| POST-005 | OpenAI API Key display. | Saved key value is not shown in plaintext. | Pending manual verification | Do not use or record real keys. |
| POST-006 | Credential status / notice. | Status and notice do not expose secret values. | Pending manual verification | Record only secret-free observations. |
| POST-007 | Authorization header exposure. | No Authorization header or token-like value appears on screen. | Pending manual verification | If found, mark Fail without copying the value. |
| POST-008 | Request/payload/raw response/generated report exposure. | No full request body, AI payload, raw response, or generated report is shown. | Pending manual verification | Do not open or copy payload JSON. |

## JavaScript Console Checklist

Open browser developer tools only for the current Settings or Report Builder
page. Do not copy console output containing sensitive data.

| ID | Check | Expected Result | Result | Notes |
|---|---|---|---|---|
| JS-001 | Settings initial display console. | No obvious JavaScript error appears. | Pending manual verification | Record only error type if a failure occurs. |
| JS-002 | Settings save console. | No obvious JavaScript error appears after save. | Pending manual verification | Do not record request bodies or form values. |
| JS-003 | Settings reload console. | No obvious JavaScript error appears after reload. | Pending manual verification | Record status-level result only. |
| JS-004 | Sensitive console output. | Console does not show credentials, tokens, API keys, Authorization headers, payloads, raw responses, or generated reports. | Pending manual verification | If sensitive data appears, mark Fail and do not copy the sensitive value. |

## Security / Redaction Rules

- Do not input real credentials.
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
- Do not record real GA4 property IDs or production hostnames.
- If screenshots are used, first confirm they do not contain credential state,
  payload content, raw responses, generated report text, personal information,
  private site details, or other sensitive data.
- Prefer status-level text notes over screenshots wherever possible.

## Result Recording Rules

Use only these values after manual execution:

- `Pass`: the expected result was observed.
- `Fail`: the expected result was not observed.
- `Blocked`: the check could not be performed because access, account, browser,
  environment, or safe state was unavailable.
- `Not tested`: the check was intentionally skipped, usually because it would
  require real credentials, external API communication, payload inspection, raw
  response inspection, generated report inspection, or unsafe state changes.

Before manual execution, `Pending manual verification` may remain in the
Result column.

For `Fail` results:

- Keep the note short.
- Do not include secrets.
- Do not include full payloads, raw responses, request bodies, or generated
  reports.
- Record only enough context to route follow-up work, such as page name and
  broad error type.

## Summary Template After Manual Run

Fill this table only after a human Settings save smoke run is completed.

| Category | Pass | Fail | Blocked | Not tested |
|---|---:|---:|---:|---:|
| Manual Settings save | 0 | 0 | 0 | 0 |
| Post-save display | 0 | 0 | 0 | 0 |
| JavaScript console | 0 | 0 | 0 | 0 |
| Cleanup / restore | 0 | 0 | 0 | 0 |
| External API / real credential actions | 0 | 0 | 0 | 0 |
| Total | 0 | 0 | 0 | 0 |

Manual run summary fields:

| Field | Value |
|---|---|
| Tester | Name / initials |
| Date/time | YYYY-MM-DD HH:MM timezone |
| Browser | Browser and version |
| WordPress user role | Administrator / other |
| Test data used | Dummy GA4 ID / safe hostname / credential fields untouched or dummy credential values. |
| Overall result | Pass / Fail / Blocked / Not tested mix |
| Fail summary | Secret-free short summary, or `None`. |
| Blocked summary | Short reason, or `None`. |
| Not tested summary | Short reason, or `None`. |
| Cleanup status | Secret-free status-level summary. |
| Redaction confirmation | Confirm no secrets, option values, payloads, raw responses, or generated reports were recorded. |

## Cleanup / Restore Notes

After the manual test, restore safe Settings state if needed.

- If dummy non-production values remain, use the browser Settings screen to
  change them back to the intended safe local values.
- If dummy credential strings were saved, use only the browser UI clear controls
  to remove them when available.
- Do not display or record credential option values.
- Do not use `wp option get` or database queries to print plugin settings or
  credential option contents.
- Do not paste option values into notes, issues, pull requests, chat, logs, or
  screenshots.
- Record cleanup results only as status-level notes, such as `dummy credential
  cleared via UI` or `credential fields were not touched`.

## Next Step Notes

- After a human Settings save run, record the completed status-level results in
  a follow-up results document.
- Keep Settings save smoke separate from GA4/OpenAI E2E testing.
- Do not proceed to external API E2E until a later step explicitly allows it and
  defines redacted evidence rules.
- Continue treating manual Google Access Token entry and MVP database
  credential storage as public-release risks per the existing credential/OAuth
  planning docs.
