# Step 75: Error-handling QA Checklist

## Purpose

This document creates the Step 75 error-handling QA checklist for Analytics
Report AI.

Step 74 identified error-path secret/raw-response leakage QA as a release
blocker candidate. This checklist prepares later human browser verification or
controlled local validation for invalid input, invalid credentials, permission
failures, quota/rate limits, timeouts, empty data, nonce failure, capability
failure, transient state issues, UI error states, and evidence redaction.

This step does not execute QA, change implementation, or make a
release-readiness decision.

WordPress.org release remains `Hold`.

## Scope

In scope:

- Local validation errors.
- Settings validation errors.
- Report Builder validation errors.
- GA4 Fetch error paths.
- OpenAI Generate error paths.
- Nonce failure behavior.
- Capability failure behavior.
- Invalid, expired, or missing credentials.
- GA4 permission, invalid property, empty data, and timeout errors.
- OpenAI invalid key, quota, rate limit, and timeout errors.
- UI notice behavior.
- No secret or raw response leakage.
- Browser console status-level confirmation.
- Evidence redaction rules.

Out of scope:

- Production code changes.
- `readme.txt` changes.
- Actual credential inspection.
- Actual API calls in this checklist step.
- GA4 Fetch execution in this checklist step.
- OpenAI Generate execution in this checklist step.
- Google OAuth execution.
- Release readiness decision.
- WordPress.org submission.

## Review Preconditions

| Precondition | Status | Notes |
|---|---|---|
| Controlled MVP E2E has passed. | Known pass | Prior controlled checks covered the local developer happy path. |
| Admin security review results are documented. | Known pass | Step 74 identified remaining admin security and adjacent release blockers. |
| Error-path QA has not yet been executed broadly. | Hold | This document prepares the checklist only. |
| WordPress.org release remains `Hold`. | Hold | Release readiness has not started. |
| No real credentials or option values should be inspected. | Required | Later QA must remain redacted and status-level. |
| No external API calls should be made in this checklist step. | Required | GA4 Fetch, OpenAI Generate, and OAuth are out of scope here. |

## Error-handling QA Checklist

Use this checklist in a later QA execution step. Record only status-level,
redacted evidence. Do not record credentials, credential fragments, GA4 Property
IDs, hostnames/domains, nonce values, browser cookies, session values, request
bodies, AI payload bodies, raw responses, generated report bodies, copied report
text, or plugin settings option dumps.

### 4.1 Local Validation Errors

| ID | Error Area | Check / Scenario | Expected Safe Behavior | Evidence Rule | Status |
|---|---|---|---|---|---|
| LOCAL-001 | Date validation | Missing start date. | Validation fails locally; no external API call occurs; notice is clear and secret-free. | Record status only; do not record real report context. | Pending QA |
| LOCAL-002 | Date validation | Missing end date. | Validation fails locally; no external API call occurs; notice is clear and secret-free. | Record status only. | Pending QA |
| LOCAL-003 | Date validation | End date before start date. | Validation fails locally; no external API call occurs; form state remains safe. | Record synthetic dates only if needed. | Pending QA |
| LOCAL-004 | Date validation | Date range exceeds the MVP maximum. | Validation fails locally; no external API call occurs; notice explains the limit. | Record status only. | Pending QA |
| LOCAL-005 | Date validation | Invalid date format. | Validation fails locally; no external API call occurs; notice is clear. | Record synthetic values only. | Pending QA |
| LOCAL-006 | Comparison validation | Unsupported comparison mode submitted. | Server-side validation rejects or normalizes safely; no external API call occurs. | Record action category only. | Pending QA |
| LOCAL-007 | Scope validation | Unsupported scope mode submitted. | Server-side validation rejects or normalizes safely; no external API call occurs. | Record action category only. | Pending QA |
| LOCAL-008 | Path validation | Full URL submitted to directory scope. | Full URL is rejected before external API calls. | Do not record real URL or domain. | Pending QA |
| LOCAL-009 | Path validation | Full URL submitted to page scope. | Full URL is rejected before external API calls. | Do not record real URL or domain. | Pending QA |
| LOCAL-010 | Path validation | Invalid directory path. | Validation fails or path is normalized safely; no external API call occurs. | Use synthetic paths only. | Pending QA |
| LOCAL-011 | Path validation | Invalid page path. | Validation fails or path is normalized safely; no external API call occurs. | Use synthetic paths only. | Pending QA |
| LOCAL-012 | Path validation | Empty required path for directory/page scope. | Validation fails locally; no external API call occurs. | Record status only. | Pending QA |
| LOCAL-013 | Action validation | Unexpected hidden action value. | Action is rejected as invalid; no external API call occurs. | Do not record nonce or session values. | Pending QA |

### 4.2 Settings / Credential Validation Errors

| ID | Error Area | Check / Scenario | Expected Safe Behavior | Evidence Rule | Status |
|---|---|---|---|---|---|
| SETTINGS-001 | GA4 Property ID | Invalid GA4 Property ID format. | Settings validation rejects or reports the error; no external API call occurs. | Do not record real property IDs. | Pending QA |
| SETTINGS-002 | GA4 Property ID | GA4 Measurement ID entered instead of Property ID. | Settings validation rejects the value with a clear notice. | Use synthetic identifier examples only if needed. | Pending QA |
| SETTINGS-003 | Google credential | Empty Google Access Token field with clear checkbox not selected. | Existing saved token is preserved; token value is not redisplayed. | Do not inspect or record option values. | Pending QA |
| SETTINGS-004 | Google credential | Google Access Token clear checkbox selected. | Saved status is removed or changes safely; credential value is not displayed. | Record status-level saved/not-saved result only. | Pending QA |
| SETTINGS-005 | OpenAI credential | Empty OpenAI API Key field with clear checkbox not selected. | Existing saved key is preserved; key value is not redisplayed. | Do not inspect or record option values. | Pending QA |
| SETTINGS-006 | OpenAI credential | OpenAI API Key clear checkbox selected. | Saved status is removed or changes safely; credential value is not displayed. | Record status-level saved/not-saved result only. | Pending QA |
| SETTINGS-007 | Hostname | Hostname field receives invalid value. | Value is normalized or handled safely; no external API call occurs merely by saving. | Do not record real hostname/domain. | Pending QA |
| SETTINGS-008 | Hostname | Hostname filter enabled with empty hostname. | Safe default or clear validation behavior occurs; notice is secret-free. | Do not record real hostname/domain. | Pending QA |
| SETTINGS-009 | Notices | Settings save notice after validation error. | Notice is escaped, clear, and contains no credential or option value. | Screenshot only if redacted and status-level. | Pending QA |
| SETTINGS-010 | External call boundary | Settings save with any validation error. | Saving settings does not trigger GA4 or OpenAI calls. | Network evidence must not include headers or bodies. | Pending QA |

### 4.3 Nonce / CSRF Failure

| ID | Error Area | Check / Scenario | Expected Safe Behavior | Evidence Rule | Status |
|---|---|---|---|---|---|
| NONCE-001 | Settings nonce | Missing Settings nonce. | Action is blocked by WordPress settings flow; no state change should occur. | Do not record nonce values. | Pending QA |
| NONCE-002 | Settings nonce | Invalid Settings nonce. | Action is blocked by WordPress settings flow; no state change should occur. | Do not record nonce values. | Pending QA |
| NONCE-003 | Report Builder nonce | Missing Report Builder nonce. | Action is blocked; no GA4/OpenAI call occurs; notice is generic. | Do not record nonce values. | Pending QA |
| NONCE-004 | Report Builder nonce | Invalid Report Builder nonce. | Action is blocked; no GA4/OpenAI call occurs; notice is generic. | Do not record nonce values. | Pending QA |
| NONCE-005 | GA4 action nonce | GA4 Fetch attempted with invalid nonce. | GA4 request is not made; state is unchanged. | Record status only. | Pending QA |
| NONCE-006 | OpenAI action nonce | OpenAI Generate attempted with invalid nonce. | OpenAI request is not made; generated report is not shown. | Record status only. | Pending QA |
| NONCE-007 | Replay | Replaying an old form state, if safely testable later. | Stale or invalid action is blocked or safely handled. | Do not record nonce, cookie, or session values. | Pending QA |

### 4.4 Capability / Non-admin Failure

| ID | Error Area | Check / Scenario | Expected Safe Behavior | Evidence Rule | Status |
|---|---|---|---|---|---|
| CAP-001 | Page access | Non-admin direct access to Settings page. | Access is blocked; no credential or setting value appears. | Record role category only. | Pending QA |
| CAP-002 | Page access | Non-admin direct access to Report Builder page. | Access is blocked; no payload or report content appears. | Record role category only. | Pending QA |
| CAP-003 | Settings save | Non-admin attempt to submit Settings save. | Action is blocked; no state change; no external API call occurs. | Do not inspect option values. | Pending QA |
| CAP-004 | GA4 action | Non-admin attempt to trigger GA4 Fetch. | Action is blocked before GA4 request. | No request headers or bodies in evidence. | Pending QA |
| CAP-005 | OpenAI action | Non-admin attempt to trigger OpenAI Generate. | Action is blocked before OpenAI request. | No request headers or bodies in evidence. | Pending QA |

### 4.5 GA4 Fetch Error Paths

| ID | Error Area | Check / Scenario | Expected Safe Behavior | Evidence Rule | Status |
|---|---|---|---|---|---|
| GA4-001 | Missing setting | Missing GA4 Property ID. | GA4 Fetch is blocked or returns a clear safe error before useful external work. | Do not record real property IDs. | Pending QA |
| GA4-002 | Property ID | Invalid GA4 Property ID. | User-facing notice is clear and redacted; no raw response appears. | Do not record real property IDs or raw response. | Pending QA |
| GA4-003 | Credential | Missing Google Access Token. | User-facing notice asks for saved token; no token value appears. | Do not inspect option values. | Pending QA |
| GA4-004 | Credential | Invalid Google Access Token. | User-facing error is clear and redacted; no token or fragment appears. | Do not record token values or raw response. | Pending QA |
| GA4-005 | Credential | Expired Google Access Token. | User-facing error explains invalid/expired state safely. | Do not record token values or raw response. | Pending QA |
| GA4-006 | Permission | Insufficient GA4 permission. | Permission error is clear and redacted; no raw GA4 body appears. | Do not record property ID, token, or response body. | Pending QA |
| GA4-007 | Empty data | GA4 property has no data for selected range. | Empty result is handled gracefully; AI Payload Preview is not falsely successful if payload invalid. | Do not record analytics values. | Pending QA |
| GA4-008 | Timeout | GA4 API timeout. | Timeout error is clear and redacted; no raw response or header appears. | Status-level only. | Pending QA |
| GA4-009 | Network | GA4 API network failure. | Network error is clear and redacted; no request body appears. | Status-level only. | Pending QA |
| GA4-010 | Malformed response | Malformed or unexpected GA4 response. | Safe unreadable-response or validation error appears; no raw body is displayed. | Do not record raw response. | Pending QA |
| GA4-011 | Host filter | Hostname filter returns no data. | Empty state is clear and does not leak hostname in docs. | Do not record real hostname/domain. | Pending QA |
| GA4-012 | Directory scope | Directory scope returns no data. | Empty state is clear; OpenAI Generate is unavailable or safely blocked without valid payload. | Do not record real paths. | Pending QA |
| GA4-013 | Page scope | Page scope returns no data. | Empty state is clear; OpenAI Generate is unavailable or safely blocked without valid payload. | Do not record real paths. | Pending QA |

### 4.6 OpenAI Generate Error Paths

| ID | Error Area | Check / Scenario | Expected Safe Behavior | Evidence Rule | Status |
|---|---|---|---|---|---|
| OAI-001 | Missing credential | Missing OpenAI API Key. | Error asks for saved API key; no key value or fragment appears. | Do not inspect option values. | Pending QA |
| OAI-002 | Invalid credential | Invalid OpenAI API Key. | Error is clear and redacted; no key or raw response appears. | Do not record key values or raw response. | Pending QA |
| OAI-003 | Quota | OpenAI quota exceeded. | Error explains quota/billing safely; no raw response appears. | Status-level only. | Pending QA |
| OAI-004 | Rate limit | OpenAI rate limit. | Error explains rate limit safely; no raw response appears. | Status-level only. | Pending QA |
| OAI-005 | Timeout | OpenAI timeout. | Timeout error is clear and redacted; no request body appears. | Status-level only. | Pending QA |
| OAI-006 | Network | OpenAI network failure. | Network error is clear and redacted; generated textarea is not falsely shown as success. | Status-level only. | Pending QA |
| OAI-007 | Malformed response | Malformed or unexpected OpenAI response. | Safe unreadable-response or empty-text error appears; no raw response appears. | Do not record raw response. | Pending QA |
| OAI-008 | Missing payload | Missing AI payload transient. | OpenAI Generate is blocked with a safe message asking to fetch GA4 data again. | Do not record transient value. | Pending QA |
| OAI-009 | Expired payload | Expired AI payload transient. | OpenAI Generate is blocked with a safe message asking to fetch GA4 data again. | Do not record transient value. | Pending QA |
| OAI-010 | Invalid payload | Invalid AI payload transient. | Invalid payload is rejected before OpenAI request. | Do not record payload body. | Pending QA |
| OAI-011 | Action order | OpenAI Generate attempted before GA4 Fetch. | Generate is unavailable or safely blocked without valid payload. | Record status only. | Pending QA |
| OAI-012 | Duplicate submit | Duplicate OpenAI Generate click during pending request. | Duplicate request is prevented where possible; UI does not imply false success. | No request body or generated text in evidence. | Pending QA |

### 4.7 State / Transient Error Paths

| ID | Error Area | Check / Scenario | Expected Safe Behavior | Evidence Rule | Status |
|---|---|---|---|---|---|
| STATE-001 | Payload transient | Missing user-scoped AI payload transient. | Invalid state is rejected; no OpenAI call occurs. | Do not record transient key/value. | Pending QA |
| STATE-002 | Payload transient | Expired transient. | User is asked to fetch GA4 data again; no OpenAI call occurs. | Do not record transient key/value. | Pending QA |
| STATE-003 | Payload transient | Malformed transient payload. | Payload validation fails; transient may be cleared safely; no OpenAI call occurs. | Do not record payload body. | Pending QA |
| STATE-004 | Multi-admin | Transient belongs to another admin user, if safely testable later. | Cross-user state is not used. | Use status-level evidence only. | Pending QA |
| STATE-005 | Stale state | Stale payload after changing conditions. | User-visible state remains clear; stale data is not silently sent. | Do not record payload body or report content. | Pending QA |
| STATE-006 | Browser history | Repeated browser back/refresh after fetch. | No unintended external call occurs without explicit action. | Browser evidence must be redacted. | Pending QA |
| STATE-007 | Browser history | Repeated browser back/refresh after generate. | No unintended duplicate generation occurs where preventable; generated body is not persisted unexpectedly. | Do not record generated report body. | Pending QA |

### 4.8 UI / Browser / Console Error Paths

| ID | Error Area | Check / Scenario | Expected Safe Behavior | Evidence Rule | Status |
|---|---|---|---|---|---|
| UI-001 | JavaScript | JavaScript disabled. | Core forms remain safe; external actions still require server-side validation. | Record status only. | Pending QA |
| UI-002 | Duplicate click | Duplicate click on external action buttons. | Duplicate external calls are prevented where possible; no false success. | Do not record request details. | Pending QA |
| UI-003 | Pending state | Slow network or pending state. | Loading/disabled state is clear and does not leak sensitive data. | Screenshot only if redacted. | Pending QA |
| UI-004 | Loading state | Loading state during GA4/OpenAI action. | UI communicates pending state without exposing request/response data. | Status-level only. | Pending QA |
| UI-005 | Disabled state | Disabled state after submit. | UI prevents repeated action where intended. | Status-level only. | Pending QA |
| UI-006 | Copy failure | Copy button failure. | Copy failure notice is safe and does not paste report text into docs. | Do not record copied content. | Pending QA |
| UI-007 | Textarea after error | Textarea behavior after OpenAI error. | Generated report textarea is not falsely shown as success with stale/invalid content. | Do not record generated body. | Pending QA |
| UI-008 | Console | Browser console errors. | Console is clean or error class is recorded at status level only. | Do not paste full console dumps if sensitive. | Pending QA |
| UI-009 | Browser history | Browser back/refresh behavior. | No unintended repeated external action; UI state remains understandable. | Status-level only. | Pending QA |
| UI-010 | Network evidence | Network tab evidence safety. | Evidence does not record sensitive URLs, headers, request bodies, response bodies, or authorization values. | Method/status/service category only if needed. | Pending QA |

### 4.9 Evidence / Redaction Rules

| ID | Error Area | Check / Scenario | Expected Safe Behavior | Evidence Rule | Status |
|---|---|---|---|---|---|
| EVIDENCE-001 | Screenshots | Screenshot evidence. | Screenshots avoid credentials, property IDs, hostnames/domains, payload bodies, report bodies, cookies, and nonce values. | Crop or redact before recording. | Pending QA |
| EVIDENCE-002 | Console | Browser console evidence. | Record error class/status only; no secrets, payloads, or raw responses. | No full dumps when sensitive. | Pending QA |
| EVIDENCE-003 | Network tab | Network tab evidence. | Do not record headers, request bodies, response bodies, cookies, sessions, or authorization values. | Method/status/service category only. | Pending QA |
| EVIDENCE-004 | Terminal | Terminal output. | Terminal output does not include option dumps, credentials, payload bodies, raw responses, or generated report bodies. | Review before pasting into docs. | Pending QA |
| EVIDENCE-005 | WP-CLI | WP-CLI output. | Do not run commands that reveal credential-bearing settings option values. | No option-value dumps. | Pending QA |
| EVIDENCE-006 | Docs | Documentation evidence. | Docs remain status-level and redacted. | No real identifiers or full bodies. | Known pass |
| EVIDENCE-007 | Support notes | Support notes. | Support notes ask for redacted status-level data only. | No secrets or copied report text. | Pending QA |
| EVIDENCE-008 | Generated report | Generated report body. | Generated report text is never pasted into QA docs. | Record display/edit/copy status only. | Pending QA |
| EVIDENCE-009 | AI Payload Preview | AI payload preview. | Full AI payload is never pasted into QA docs. | Record preview presence/status only. | Pending QA |
| EVIDENCE-010 | Raw responses | Raw external responses. | Raw GA4/OpenAI responses are never pasted into QA docs. | Record normalized error category only. | Pending QA |
| EVIDENCE-011 | Option values | Credential-bearing option values. | `wp_options` credential/settings values are not displayed or recorded. | Do not run option-value dump commands. | Known pass |

## Stop Conditions

Stop the QA attempt immediately if any of the following occurs or becomes
necessary:

- A credential, token, or API key appears on screen, in logs, in the console, in
  Network tools, or in terminal output.
- A credential fragment, prefix, suffix, or token segment appears.
- An authorization request header appears or is about to be recorded.
- A raw GA4 response or raw OpenAI response appears or is about to be recorded.
- An OpenAI request body or GA4 request body appears or is about to be recorded.
- A full AI payload appears or is about to be recorded.
- A full generated report appears or is about to be recorded.
- A nonce, cookie, or browser session value appears or is about to be recorded.
- Inspecting a credential-bearing `wp_options` or plugin settings option value
  appears necessary.
- A Google OAuth flow starts or is about to start.
- Reproducing an external API failure would require unsafe credential sharing,
  unsafe credential pasting, or recording sensitive data.

After stopping, record only:

```text
Blocked / Fail
Screen or area
Action category
Redacted reason category
Confirmation that no secret/full body was recorded
```

## Severity Guide

Use this guide when executing the error-handling QA checklist:

```text
High: Public release should not proceed before this error path is safe or explicitly accepted.
Medium: Should be reviewed and preferably resolved before release-readiness decision.
Low: Can be documented or deferred if it does not affect credentials, external calls, privacy, or core UX.
Known pass: Already confirmed in controlled checks and should be preserved.
```

Suggested interpretation:

- High items are release blocker candidates when they can expose secrets, raw
  responses, request bodies, payload bodies, generated report bodies, or hidden
  external calls.
- Medium items should be reviewed before release readiness unless explicitly
  accepted with rationale.
- Low items can be tracked if they do not affect credentials, external calls,
  privacy, or core UX.
- Known pass items still need regression protection.

## Result Recording Template

Use this summary template in the later QA execution step. Keep notes redacted
and status-level only.

| Category | Reviewed | Pass items | Fail items | Blocked items | Not tested items | High-risk unresolved | Release blocker candidates | Notes |
|---|---|---:|---:|---:|---:|---|---|---|
| Local Validation Errors | yes / no | 0 | 0 | 0 | 0 | yes / no | yes / no | redacted / status-level only |
| Settings / Credential Validation Errors | yes / no | 0 | 0 | 0 | 0 | yes / no | yes / no | redacted / status-level only |
| Nonce / CSRF Failure | yes / no | 0 | 0 | 0 | 0 | yes / no | yes / no | redacted / status-level only |
| Capability / Non-admin Failure | yes / no | 0 | 0 | 0 | 0 | yes / no | yes / no | redacted / status-level only |
| GA4 Fetch Error Paths | yes / no | 0 | 0 | 0 | 0 | yes / no | yes / no | redacted / status-level only |
| OpenAI Generate Error Paths | yes / no | 0 | 0 | 0 | 0 | yes / no | yes / no | redacted / status-level only |
| State / Transient Error Paths | yes / no | 0 | 0 | 0 | 0 | yes / no | yes / no | redacted / status-level only |
| UI / Browser / Console Error Paths | yes / no | 0 | 0 | 0 | 0 | yes / no | yes / no | redacted / status-level only |
| Evidence / Redaction Rules | yes / no | 0 | 0 | 0 | 0 | yes / no | yes / no | redacted / status-level only |

Use this individual result template for each checked item:

```text
ID:
Scenario:
Result: Pass / Fail / Blocked / Not tested
Observed safe behavior: status-level only
External API call made: yes / no / not applicable
Secret/raw body leakage observed: yes / no
Notes: redacted / status-level only
```

## Recommended Next Step

Step 76 should document error-handling QA results if QA is executed next.

Alternatively, Step 76 may prepare Data minimization / privacy review if
error-path execution is deferred.

WordPress.org release remains `Hold`.

## Release Position

```text
WordPress.org release: Hold
Reason: Error-handling QA has not yet been executed, and credential strategy, Google OAuth/token lifecycle, admin security negative tests, data minimization/privacy review, and WordPress.org compliance review remain incomplete.
```

## Outcome

- Error-handling QA checklist: created.
- Controlled MVP E2E flow: already passed.
- Error-path QA execution: not started.
- Release readiness decision: not started.
- WordPress.org release remains `Hold`.
- Next recommended step: Step 76 Error-handling QA results, or Data
  minimization / privacy review if QA execution is deferred.
