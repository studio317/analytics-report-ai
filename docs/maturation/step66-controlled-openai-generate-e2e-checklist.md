# Step 66: Controlled OpenAI Generate E2E Checklist

## Purpose

This document prepares a human-run checklist for controlled OpenAI Generate E2E
verification in Analytics Report AI.

Step 66 is a checklist only. It is not an execution result. The later human
browser run should verify only the OpenAI Generate / Generate AI Report flow
after a successful GA4 Fetch state is prepared.

The Step 64 / Step 65 Controlled GA4 Fetch E2E result is treated as the
precondition for this checklist. Generated report body content must not be
recorded in full.

Codex does not click GA4 Fetch, does not click OpenAI Generate, does not start
Google OAuth, does not use real credentials, and does not perform external API
communication in this step.

## Scope

In scope:

- WordPress admin Report Builder screen.
- Status-level confirmation that required Settings state is prepared.
- OpenAI API Key saved-state status-level confirmation.
- GA4 Fetch completed state, either from an existing same-browser run or from a
  controlled GA4 Fetch immediately before OpenAI Generate.
- AI Payload Preview displayed state before generation.
- OpenAI Generate / Generate AI Report action.
- Generated report textarea display state.
- Generated report textarea editability.
- Copy button display state.
- UI notice or error notice status-level confirmation.
- Browser console status-level confirmation.
- Secret-free result recording template for a later human browser run.

Out of scope:

- Google OAuth.
- Credential re-entry testing.
- Credential removal testing.
- Raw GA4 response inspection.
- Full AI payload recording.
- OpenAI request body inspection.
- Raw OpenAI response inspection.
- Full generated report recording.
- WordPress.org release decision changes.

No production PHP, JavaScript, CSS, `readme.txt`, Composer file, PHPCS config,
distribution file, version, or metadata file is changed by this step.

## Preconditions

The later human browser run should confirm the following at status level only:

- Plugin is active.
- WordPress administrator is logged in.
- GA4 Property ID is `configured`.
- Google Access Token is `saved`.
- OpenAI API Key is `saved`.
- Credential values are not displayed.
- Report Builder screen is accessible.
- Controlled GA4 Fetch E2E passed in Step 64 / Step 65.
- AI Payload Preview is `displayed`, or GA4 Fetch completes with controlled
  success immediately before OpenAI Generate in the same browser run.
- WordPress.org release remains `Hold`.

Do not record actual GA4 Property ID, actual hostname, credential values,
credential fragments, option values, full AI payload, OpenAI request body, raw
OpenAI response, raw GA4 response, or generated report body.

## Redaction Policy

Allowed examples:

```text
GA4 Property ID: configured
Google Access Token: saved
OpenAI API Key: saved
Hostname filter: enabled / disabled
Hostname: redacted
AI Payload Preview: displayed
OpenAI Generate result: success / controlled error / blocked
Generated report textarea: displayed / not displayed
Generated report editability: editable / not editable / not checked
Copy button: displayed / not displayed / not tested
Copy action: clicked / not clicked / not tested
Browser console: no new visible error / error observed
WordPress.org release: Hold
```

Disallowed examples:

```text
Actual GA4 Property ID
Actual Google Access Token
Actual OpenAI API Key
Authorization header
Credential prefix or suffix
Values beginning with sk-
JWT fragments
Actual hostname or private site domain
Full request body
Full AI payload
OpenAI request body
Raw OpenAI API response
Raw GA4 API response
Generated report body
Browser cookie, session, or nonce values
wp_options credential or plugin settings option values
```

If an error occurs, record only a short redacted summary such as:

- `controlled OpenAI error notice displayed`
- `API key or permission-related error summary shown`
- `payload missing or expired notice shown`
- `network/browser issue observed`

Do not copy raw response details, headers, request bodies, payloads, generated
report text, or private identifiers.

## Controlled Browser Checklist

Use only `Pass`, `Fail`, `Blocked`, `Not tested`, or
`Pending manual verification` in the Result column. Initial values are
`Pending manual verification`.

| ID | Action / Check | Expected Result | Result | Notes |
|---|---|---|---|---|
| OAI-001 | Log in to WordPress admin. | Admin area is reachable. | Pending manual verification | Do not record usernames, passwords, cookies, or session values. |
| OAI-002 | Open Plugins screen. | Analytics Report AI is shown as active. | Pending manual verification | Status-level only. |
| OAI-003 | Open Settings screen. | Settings screen opens. | Pending manual verification | Do not edit credentials in this checklist. |
| OAI-004 | Confirm GA4 Property ID status. | GA4 Property ID is `configured`. | Pending manual verification | Do not record actual property ID. |
| OAI-005 | Confirm Google Access Token status. | Google Access Token is `saved`. | Pending manual verification | Do not record token value or fragments. |
| OAI-006 | Confirm OpenAI API Key status. | OpenAI API Key is `saved`. | Pending manual verification | Do not record key value, prefix, suffix, or fragments. |
| OAI-007 | Confirm credential values are not displayed. | Credential values are not shown on Settings screen. | Pending manual verification | If exposure occurs, stop immediately. |
| OAI-008 | Open Report Builder screen. | Report Builder screen opens. | Pending manual verification | Do not record private site/analytics details. |
| OAI-009 | Confirm GA4 Fetch completed state. | GA4 Fetch has controlled success in the current browser context, or is completed immediately before generation. | Pending manual verification | If GA4 Fetch is run, record only status-level result. |
| OAI-010 | Confirm AI Payload Preview displayed. | AI Payload Preview is `displayed`. | Pending manual verification | Do not copy or record full payload. |
| OAI-011 | Confirm AI Payload Preview full content is not recorded. | Full payload is not copied, pasted, logged, or screenshotted. | Pending manual verification | Status-level only. |
| OAI-012 | Click OpenAI Generate / Generate AI Report once. | OpenAI Generate is attempted once under controlled conditions. | Pending manual verification | Do not repeat-click unless a later checklist explicitly allows it. |
| OAI-013 | Confirm Google OAuth remains untouched. | Google OAuth flow is not started. | Pending manual verification | If OAuth starts, stop immediately. |
| OAI-014 | Confirm OpenAI Generate outcome. | Outcome is `success`, `controlled error`, or `blocked`. | Pending manual verification | Do not record raw OpenAI response. |
| OAI-015 | On success, confirm generated report textarea state. | Generated report textarea is `displayed`. | Pending manual verification | Do not copy or record full generated report body. |
| OAI-016 | Confirm generated report full content is not recorded. | Generated report body is not copied, pasted, logged, or screenshotted in full. | Pending manual verification | Short quality/status summaries only. |
| OAI-017 | Confirm textarea editability. | Generated report textarea is `editable`, `not editable`, or `not checked`. | Pending manual verification | Do not record generated body. |
| OAI-018 | Confirm copy button display state. | Copy button is `displayed`, `not displayed`, or `not tested`. | Pending manual verification | Do not copy report text into evidence. |
| OAI-019 | If copy button is tested, record action status only. | Copy action is `clicked`, `not clicked`, or `not tested`; copied content is not recorded. | Pending manual verification | Do not paste copied content into docs, chat, logs, or terminal. |
| OAI-020 | On controlled error, confirm UI notice state. | Error notice type and short redacted summary are recorded. | Pending manual verification | Do not record raw response or headers. |
| OAI-021 | Confirm OpenAI request body is not recorded. | Request body is not copied, pasted, logged, or screenshotted. | Pending manual verification | If about to record it, stop immediately. |
| OAI-022 | Confirm raw OpenAI response is not recorded. | Raw response body is not copied, pasted, logged, or screenshotted. | Pending manual verification | Status-level only. |
| OAI-023 | Confirm raw GA4 response remains unrecorded. | Raw GA4 response is not recorded during this run. | Pending manual verification | This remains from the GA4 Fetch safety rule. |
| OAI-024 | Check browser console status-level only. | Console is `no new visible error` or redacted `error observed`. | Pending manual verification | Do not copy sensitive console output. |
| OAI-025 | If Network tab is opened, avoid sensitive details. | URL, request body, response body, headers, and Authorization are not recorded. | Pending manual verification | Prefer not using Network tab unless needed. |
| OAI-026 | Confirm credential values are not displayed after generation. | No credential, token, API key, Authorization header, or secret-like value appears on screen. | Pending manual verification | If exposure occurs, stop immediately. |
| OAI-027 | Confirm generated report body is handled safely. | Full body is not recorded; only status-level generated report UI evidence is recorded. | Pending manual verification | Do not paste report text into docs. |
| OAI-028 | Confirm WordPress.org release position. | WordPress.org release remains `Hold`. | Pending manual verification | This checklist does not change release status. |

## Stop Conditions

Stop immediately if any of the following happens:

- Credential, token, API key, or secret-like value appears on screen, in console,
  in logs, or in copied text.
- Authorization header becomes visible or is about to be recorded.
- OpenAI request body is about to be copied, pasted, screenshotted, or recorded.
- Raw OpenAI response is about to be copied, pasted, screenshotted, or recorded.
- Full AI Payload Preview content is about to be copied, pasted,
  screenshotted, or recorded.
- Full generated report body is about to be copied, pasted, screenshotted, or
  recorded.
- Raw GA4 response is about to be copied, pasted, screenshotted, or recorded.
- Google OAuth flow is about to start or has started.
- Plugin settings option or credential option value needs to be displayed to
  continue.
- Browser Network tab requires recording URL, request body, response body, or
  headers.
- The tester cannot continue without exposing private analytics data,
  generated report body, payload content, or credentials.

After stopping, record only:

- `Blocked` or `Fail`.
- Screen name.
- Action name.
- Redacted reason category.
- Confirmation that no secret/full body was recorded.

## Result Recording Template

Use this template in the later human run result step. Do not fill it in during
Step 66.

### Environment

- WordPress admin accessible: Pass / Fail / Blocked / Not tested
- Plugin active: Pass / Fail / Blocked / Not tested
- GA4 Property ID configured status: Pass / Fail / Blocked / Not tested
- Google Access Token saved status: Pass / Fail / Blocked / Not tested
- OpenAI API Key saved status: Pass / Fail / Blocked / Not tested
- Credential values not displayed: Pass / Fail / Blocked / Not tested

### Pre-generate State

- Report Builder accessible: Pass / Fail / Blocked / Not tested
- Controlled GA4 Fetch completed: Pass / Fail / Blocked / Not tested
- AI Payload Preview displayed: Pass / Fail / Blocked / Not tested
- AI Payload Preview full content not recorded: Pass / Fail / Blocked / Not tested

### OpenAI Generate

- OpenAI Generate clicked once: Pass / Fail / Blocked / Not tested
- Google OAuth not started: Pass / Fail / Blocked / Not tested
- OpenAI Generate completed with controlled outcome: Pass / Fail / Blocked / Not tested
- Raw OpenAI response not recorded: Pass / Fail / Blocked / Not tested
- OpenAI request body not recorded: Pass / Fail / Blocked / Not tested

### Generated Report UI

- Generated report textarea displayed on success: Pass / Fail / Blocked / Not tested
- Generated report body full content not recorded: Pass / Fail / Blocked / Not tested
- Generated report textarea editable: Pass / Fail / Blocked / Not tested
- Copy button displayed: Pass / Fail / Blocked / Not tested
- Copy action status checked without recording content: Pass / Fail / Blocked / Not tested

### Browser / Safety

- Browser console checked status-level only: Pass / Fail / Blocked / Not tested
- No credential leakage observed: Pass / Fail / Blocked / Not tested
- No request/response body recorded: Pass / Fail / Blocked / Not tested
- No generated report body recorded: Pass / Fail / Blocked / Not tested
- WordPress.org release remains Hold: Pass / Fail / Blocked / Not tested

### Summary

| Category | Pass | Fail | Blocked | Not tested |
|---|---:|---:|---:|---:|
| Environment | 0 | 0 | 0 | 0 |
| Pre-generate state | 0 | 0 | 0 | 0 |
| OpenAI Generate | 0 | 0 | 0 | 0 |
| Generated report UI | 0 | 0 | 0 | 0 |
| Browser / Safety | 0 | 0 | 0 | 0 |
| Total | 0 | 0 | 0 | 0 |

### Notes

- OpenAI Generate result: success / controlled error / blocked
- Generated report textarea: displayed / not displayed
- Generated report editability: editable / not editable / not checked
- Copy button: displayed / not displayed / not tested
- Copy action: clicked / not clicked / not tested
- Browser console: no new visible error / error observed
- Credential leakage observed: yes / no
- Request/response body recorded: no
- Generated report body recorded in full: no
- Google OAuth started: no
- WordPress.org release: Hold

## Expected Next Step

- Step 67 should be the human/browser execution result for this checklist.
- OpenAI Generate result should be recorded only as status-level or redacted
  evidence.
- Generated report body should not be recorded in full.
- WordPress.org release remains `Hold`.
