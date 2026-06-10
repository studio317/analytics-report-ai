# Step 63: Controlled GA4 Fetch E2E Checklist

## Purpose

This document prepares a human-run checklist for controlled GA4 Fetch E2E
verification in Analytics Report AI.

Step 63 is a checklist only. It is not an execution result. The later human
browser run should verify only the GA4 Fetch flow and the resulting AI Payload
Preview state. OpenAI Generate / Generate AI Report remains out of scope.

Codex does not click GA4 Fetch, does not click OpenAI Generate, does not start
Google OAuth, does not use real credentials, and does not perform external API
communication in this step.

## Scope

In scope:

- WordPress admin Report Builder screen.
- Status-level confirmation that required Settings state is prepared.
- GA4 Fetch button / GA4 data fetch flow.
- AI Payload Preview display state after GA4 Fetch.
- UI notice or error notice confirmation.
- Browser console status-level confirmation.
- Secret-free result recording template for a later human browser run.

Out of scope:

- OpenAI Generate / Generate AI Report.
- Google OAuth.
- Credential re-entry testing.
- Credential removal testing.
- Raw GA4 response inspection.
- Full AI payload recording.
- Raw OpenAI response inspection.
- Generated report body inspection.
- WordPress.org release decision changes.

No production PHP, JavaScript, CSS, `readme.txt`, Composer file, PHPCS config,
distribution file, version, or metadata file is changed by this step.

## Preconditions

The later human browser run should confirm the following at status level only:

- Plugin is active.
- WordPress administrator is logged in.
- GA4 Property ID is configured.
- Google Access Token is saved.
- OpenAI API Key is not required for GA4 Fetch; if its status is visible, record
  status-level only.
- Hostname filter status is known as `enabled` or `disabled`.
- Hostname, if relevant, is recorded only as `redacted` or status-level.
- Report Builder screen is accessible.
- The Step 59 Google Access Token saved-state issue is resolved by the Step 60
  fix and Step 61 browser recheck.
- WordPress.org release remains `Hold`.

Do not record actual GA4 Property ID, actual hostname, credential values,
credential fragments, option values, request bodies, payload bodies, raw
responses, or generated reports.

## Redaction Policy

Allowed examples:

```text
GA4 Property ID: configured
Google Access Token: saved
OpenAI API Key: saved / not saved / not checked
Hostname filter: enabled / disabled
Hostname: redacted
Fetch result: success / controlled error / blocked
AI Payload Preview: displayed / not displayed
GA4 summary metrics: present / absent
Daily trend table: present / absent
Top pages table: present / absent
Traffic channels table: present / absent
Traffic sources table: present / absent
Region table: present / absent
Browser console: no new visible error / error observed
WordPress.org release: Hold
```

Disallowed examples:

```text
Actual GA4 Property ID
Actual Google Access Token
Actual OpenAI API Key
Credential prefix or suffix
Values beginning with sk-
JWT fragments
Authorization header
Actual hostname or private site domain
Full request body
Full AI payload
Raw GA4 API response
Raw OpenAI response
Generated report body
wp_options credential or plugin settings option values
Browser cookie, session, or nonce values
```

If an error occurs, record only a short redacted summary such as:

- `controlled GA4 error notice displayed`
- `permission-related error summary shown`
- `date range validation notice shown`
- `network/browser issue observed`

Do not copy raw response details, headers, request bodies, payloads, or private
identifiers.

## Controlled Browser Checklist

Use only `Pass`, `Fail`, `Blocked`, `Not tested`, or
`Pending manual verification` in the Result column. Initial values are
`Pending manual verification`.

| ID | Action / Check | Expected Result | Result | Notes |
|---|---|---|---|---|
| GA4-001 | Log in to WordPress admin. | Admin area is reachable. | Pending manual verification | Do not record usernames, passwords, cookies, or session values. |
| GA4-002 | Open Plugins screen. | Analytics Report AI is shown as active. | Pending manual verification | Status-level only. |
| GA4-003 | Open Settings screen. | Settings screen opens. | Pending manual verification | Do not edit credentials in this checklist. |
| GA4-004 | Confirm GA4 Property ID status. | GA4 Property ID is `configured`. | Pending manual verification | Do not record actual property ID. |
| GA4-005 | Confirm Google Access Token status. | Google Access Token is `saved`. | Pending manual verification | Do not record token value or fragments. |
| GA4-006 | Confirm OpenAI API Key status, if visible. | Status is recorded only as `saved`, `not saved`, or `not checked`. | Pending manual verification | OpenAI key is not used for GA4 Fetch. |
| GA4-007 | Confirm hostname filter status. | Status is recorded as `enabled` or `disabled`. | Pending manual verification | Do not record actual hostname/domain. |
| GA4-008 | Open Report Builder screen. | Report Builder screen opens. | Pending manual verification | Do not click OpenAI Generate. |
| GA4-009 | Inspect initial Report Builder display. | No visible fatal error, warning, or unexpected notice appears. | Pending manual verification | Record only secret-free summary if failed. |
| GA4-010 | Set safe verification period. | Date range is suitable for controlled GA4 Fetch. | Pending manual verification | Do not record private analytics details. |
| GA4-011 | Set comparison option. | Comparison option is selected intentionally. | Pending manual verification | Record only status-level selected state if needed. |
| GA4-012 | Set scope option. | Scope is selected intentionally. | Pending manual verification | If path/host/domain is private, record as redacted. |
| GA4-013 | Click only the GA4 data fetch action. | GA4 Fetch is attempted once under controlled conditions. | Pending manual verification | Do not click OpenAI Generate / Generate AI Report. |
| GA4-014 | Confirm OpenAI Generate remains untouched. | OpenAI Generate / Generate AI Report is not clicked. | Pending manual verification | If clicked accidentally, stop immediately. |
| GA4-015 | Confirm Google OAuth remains untouched. | Google OAuth flow is not started. | Pending manual verification | If OAuth starts, stop immediately. |
| GA4-016 | Confirm fetch outcome. | Outcome is `success`, `controlled error`, or `blocked`. | Pending manual verification | Do not record raw GA4 response. |
| GA4-017 | On success, confirm AI Payload Preview state. | AI Payload Preview is `displayed`. | Pending manual verification | Do not copy or record full payload. |
| GA4-018 | On controlled error, confirm UI notice state. | Error notice type and short summary are recorded redacted. | Pending manual verification | Do not record raw response or headers. |
| GA4-019 | Confirm Summary Metrics presence. | Summary metrics are `present` or `absent`. | Pending manual verification | Do not record metric values. |
| GA4-020 | Confirm Daily Trend presence. | Daily Trend data/table is `present` or `absent`. | Pending manual verification | Do not record row contents. |
| GA4-021 | Confirm Top Pages presence. | Top Pages data/table is `present` or `absent`. | Pending manual verification | Do not record page paths or values. |
| GA4-022 | Confirm Traffic Channels presence. | Traffic Channels data/table is `present` or `absent`. | Pending manual verification | Do not record values. |
| GA4-023 | Confirm Traffic Sources presence. | Traffic Sources data/table is `present` or `absent`. | Pending manual verification | Do not record source names if private. |
| GA4-024 | Confirm Region presence. | Region data/table is `present` or `absent`. | Pending manual verification | Do not record values. |
| GA4-025 | Confirm Preview full content is not recorded. | Full AI Payload Preview content is not copied, pasted, logged, or screenshotted. | Pending manual verification | Status-level only. |
| GA4-026 | Check browser console status-level only. | Console is `no new visible error` or redacted `error observed`. | Pending manual verification | Do not copy sensitive console output. |
| GA4-027 | If Network tab is opened, avoid sensitive details. | URL, request body, response body, headers, and Authorization are not recorded. | Pending manual verification | Prefer not using Network tab unless needed. |
| GA4-028 | Confirm credential values are not displayed. | No credential, token, API key, Authorization header, or secret-like value appears on screen. | Pending manual verification | If exposure occurs, stop immediately. |
| GA4-029 | Confirm generated report is not created. | Generated report body is not present because OpenAI Generate was not clicked. | Pending manual verification | Do not proceed to generation. |
| GA4-030 | Confirm WordPress.org release position. | WordPress.org release remains `Hold`. | Pending manual verification | This checklist does not change release status. |

## Stop Conditions

Stop immediately if any of the following happens:

- Credential, token, API key, or secret-like value appears on screen, in console,
  in logs, or in copied text.
- Authorization header becomes visible or is about to be recorded.
- Raw GA4 response is about to be copied, pasted, screenshotted, or recorded.
- Full AI Payload Preview content is about to be copied, pasted,
  screenshotted, or recorded.
- OpenAI Generate / Generate AI Report is about to be clicked or was clicked.
- Google OAuth flow is about to start or has started.
- Plugin settings option or credential option value needs to be displayed to
  continue.
- Browser Network tab requires recording URL, request body, response body, or
  headers.
- The tester cannot continue without exposing private analytics data or
  credentials.

After stopping, record only:

- `Blocked` or `Fail`.
- Screen name.
- Action name.
- Redacted reason category.
- Confirmation that no secret/full body was recorded.

## Result Recording Template

Use this template in the later human run result step. Do not fill it in during
Step 63.

### Environment

- WordPress admin accessible: Pass / Fail / Blocked / Not tested
- Plugin active: Pass / Fail / Blocked / Not tested
- GA4 Property ID configured status: Pass / Fail / Blocked / Not tested
- Google Access Token saved status: Pass / Fail / Blocked / Not tested
- Credential values not displayed: Pass / Fail / Blocked / Not tested

### GA4 Fetch

- Report Builder accessible: Pass / Fail / Blocked / Not tested
- Controlled conditions entered: Pass / Fail / Blocked / Not tested
- GA4 Fetch clicked only: Pass / Fail / Blocked / Not tested
- OpenAI Generate not clicked: Pass / Fail / Blocked / Not tested
- Google OAuth not started: Pass / Fail / Blocked / Not tested
- Fetch completed with controlled outcome: Pass / Fail / Blocked / Not tested

### AI Payload Preview

- Preview displayed on success: Pass / Fail / Blocked / Not tested
- Preview full content not recorded: Pass / Fail / Blocked / Not tested
- Summary metrics presence checked status-level only: Pass / Fail / Blocked / Not tested
- Tables presence checked status-level only: Pass / Fail / Blocked / Not tested
- Raw GA4 response not recorded: Pass / Fail / Blocked / Not tested

### Browser / Safety

- Browser console checked status-level only: Pass / Fail / Blocked / Not tested
- No credential leakage observed: Pass / Fail / Blocked / Not tested
- No request/response body recorded: Pass / Fail / Blocked / Not tested
- WordPress.org release remains Hold: Pass / Fail / Blocked / Not tested

### Summary

| Category | Pass | Fail | Blocked | Not tested |
|---|---:|---:|---:|---:|
| Environment | 0 | 0 | 0 | 0 |
| GA4 Fetch | 0 | 0 | 0 | 0 |
| AI Payload Preview | 0 | 0 | 0 | 0 |
| Browser / Safety | 0 | 0 | 0 | 0 |
| Total | 0 | 0 | 0 | 0 |

### Notes

- Fetch result: success / controlled error / blocked
- AI Payload Preview: displayed / not displayed
- Summary metrics: present / absent
- Tables: present / absent
- Browser console: no new visible error / error observed
- Credential leakage observed: yes / no
- External API action limited to GA4 Fetch: yes / no
- OpenAI Generate clicked: no
- Google OAuth started: no
- WordPress.org release: Hold

## Expected Next Step

- Step 64 should be the human/browser execution result for this checklist.
- OpenAI Generate remains blocked until Controlled GA4 Fetch E2E result is
  successful.
- GA4 Fetch E2E should record only status-level or redacted evidence.
- OpenAI Generate E2E should remain a separate later step.
- WordPress.org release remains `Hold`.
