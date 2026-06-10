# Step 77: External API Error-path QA Checklist

## Purpose

This document creates the Step 77 external API error-path QA checklist for
Analytics Report AI.

Step 76 documented Phase 1 error-handling QA results for local validation,
Settings validation, nonce handling, capability handling, and evidence safety.
External API error-path QA was deferred. This checklist prepares a later safe
execution step for GA4 and OpenAI failure cases using status-level, redacted
evidence only.

This step does not execute external API QA, change implementation, or make a
release-readiness decision.

WordPress.org release remains `Hold`.

## Scope

In scope:

- GA4 Fetch error paths.
- OpenAI Generate error paths.
- Missing, invalid, or expired credential states.
- Permission errors.
- Empty data responses.
- Timeout and network failures.
- Quota and rate limit failures.
- Malformed or unexpected responses.
- AI payload transient failure before OpenAI calls.
- Browser console status-level confirmation.
- Network tab evidence safety.
- Screenshot and support evidence redaction.

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
| Controlled MVP E2E has passed. | Known pass | Happy-path GA4 Fetch and OpenAI Generate were previously confirmed under controlled conditions. |
| Error-handling QA Phase 1 has passed for local validation / settings validation / nonce / capability checks. | Known pass | Step 76 recorded 35 Pass, 0 Fail, 0 Blocked, and 7 Not tested for Phase 1. |
| External API error-path QA has not yet been executed. | Hold | This document prepares the checklist only. |
| WordPress.org release remains `Hold`. | Hold | Release readiness has not started. |
| No real credentials or option values should be inspected. | Required | Later QA must not record credential-bearing option values or credential fragments. |
| No external API calls should be made in this checklist step. | Required | GA4 Fetch, OpenAI Generate, and OAuth are out of scope here. |

## Safe Execution Strategy for Later Step

Use this strategy when a later step executes external API error-path QA:

- Separate GA4 error-path QA from OpenAI error-path QA.
- Do not mix GA4 error-path QA and OpenAI error-path QA in the same execution
  step.
- Never record real credential values.
- Never record invalid credential values, credential fragments, prefixes,
  suffixes, or token segments.
- Never record authorization headers.
- Never record raw external responses.
- Never record request bodies.
- Never record AI payload bodies.
- Avoid Network tab evidence by default.
- If Network tab evidence is necessary, record only method, status, and service
  category.
- Screenshots must exclude credentials, property IDs, hostnames/domains,
  payload content, generated report content, cookies, sessions, and nonce
  values.
- OpenAI Generate should be tested only after a valid AI Payload Preview exists.
- If GA4 Fetch fails, confirm OpenAI Generate remains unavailable or safely
  blocked because no valid payload exists.
- Change only one failure condition per verification run.
- If a failure condition must be reverted and the happy path rechecked, perform
  that happy-path recheck in a separate step.
- If a scenario requires unsafe credential sharing, unsafe credential pasting,
  or excessive API usage, mark it `Blocked` instead of proceeding.

## External API Error-path QA Checklist

Use this checklist in a later execution step. Record only status-level,
redacted evidence. Do not record credentials, credential fragments, property
IDs, hostnames/domains, request bodies, AI payload bodies, raw responses,
generated report bodies, copied report text, nonce values, browser cookies,
session values, or plugin settings option dumps.

### 5.1 GA4 Credential / Configuration Errors

| ID | Error Area | Scenario | Expected Safe Behavior | Evidence Rule | Status |
|---|---|---|---|---|---|
| GA4-CRED-001 | GA4 configuration | Missing GA4 Property ID. | User-facing error is clear and redacted; GA4 request should not proceed usefully. | Do not record real property IDs. | Pending QA |
| GA4-CRED-002 | GA4 configuration | Invalid GA4 Property ID. | User-facing error is clear and redacted; raw GA4 response is not displayed. | Do not record property ID values or raw responses. | Pending QA |
| GA4-CRED-003 | GA4 configuration | GA4 Measurement ID used instead of Property ID. | User-facing validation/error is clear; GA4 request is not treated as success. | Use synthetic identifier category only. | Pending QA |
| GA4-CRED-004 | Google credential | Missing Google Access Token. | User-facing error explains that a token is needed; token value is not displayed. | Do not inspect or record option values. | Pending QA |
| GA4-CRED-005 | Google credential | Invalid Google Access Token. | Error is clear and redacted; token and token fragments are not displayed. | Do not record token values, fragments, headers, or raw response. | Pending QA |
| GA4-CRED-006 | Google credential | Expired Google Access Token. | Error explains invalid/expired token state safely. | Do not record token values, fragments, headers, or raw response. | Pending QA |
| GA4-CRED-007 | Google credential | Google token with insufficient scope. | Permission/scope error is clear and redacted. | Record only status-level error category. | Pending QA |
| GA4-CRED-008 | Google credential | Google token with no access to configured property. | Permission/property access error is clear and redacted. | Do not record token, property ID, or raw response. | Pending QA |
| GA4-CRED-009 | Host filter | Hostname filter enabled but hostname invalid or empty. | User-facing error or normalized state is clear; hostname is not recorded in docs. | Do not record real hostname/domain. | Pending QA |
| GA4-CRED-010 | Scope filters | Directory/page scope produces no matching data. | Empty/no-data state is clear; AI Payload Preview is not falsely shown as success if payload is invalid. | Do not record real paths. | Pending QA |

### 5.2 GA4 API / Response Errors

| ID | Error Area | Scenario | Expected Safe Behavior | Evidence Rule | Status |
|---|---|---|---|---|---|
| GA4-API-001 | Permission | GA4 permission denied. | Error notice is normalized and actionable; no raw response body appears. | Do not record property ID, token, headers, or response body. | Pending QA |
| GA4-API-002 | Quota / rate limit | GA4 API quota or rate limit. | Error notice is normalized and actionable; partial state is not marked as success. | Record only status-level category. | Pending QA |
| GA4-API-003 | Timeout | GA4 API timeout. | Timeout error is clear and redacted; no raw response or request body appears. | Do not record headers, bodies, or endpoint details beyond service category. | Pending QA |
| GA4-API-004 | Network | GA4 API network failure. | Network error is clear and redacted; AI Payload Preview state remains accurate. | Status-level only. | Pending QA |
| GA4-API-005 | Response parsing | Malformed GA4 API response. | Safe unreadable-response or payload validation error appears; raw body is not displayed. | Do not record raw response body. | Pending QA |
| GA4-API-006 | Empty response | Unexpected empty response. | Error or empty state is clear; no false success notice appears. | Do not record raw response body. | Pending QA |
| GA4-API-007 | Empty data | Property has no data for selected period. | Empty-data state is clear and not misrepresented as complete success. | Do not record analytics data values. | Pending QA |
| GA4-API-008 | Comparison data | Selected comparison period has no data. | Comparison state is clear; current data is not misrepresented. | Do not record analytics data values. | Pending QA |
| GA4-API-009 | Preset rows | City, traffic, or top pages report returns no rows. | Empty preset table/state is clear and does not break payload validation. | Do not record real page paths, cities, or sources. | Pending QA |
| GA4-API-010 | Partial preset | Partial preset failure, if applicable. | Partial data is not reported as full success; user-facing notice remains redacted. | Status-level only. | Pending QA |

### 5.3 OpenAI Credential / Configuration Errors

| ID | Error Area | Scenario | Expected Safe Behavior | Evidence Rule | Status |
|---|---|---|---|---|---|
| OAI-CRED-001 | OpenAI credential | Missing OpenAI API Key. | User-facing error asks for saved key; no key value or fragment appears. | Do not inspect or record option values. | Pending QA |
| OAI-CRED-002 | OpenAI credential | Invalid OpenAI API Key. | Error is clear and redacted; raw OpenAI response is not displayed. | Do not record key values, fragments, headers, or response body. | Pending QA |
| OAI-CRED-003 | OpenAI credential | Revoked OpenAI API Key. | Authentication error is clear and redacted. | Do not record key values, fragments, headers, or response body. | Pending QA |
| OAI-CRED-004 | OpenAI credential | API key lacks required permission. | Permission error is clear and actionable for restricted keys. | Record only status-level error category. | Pending QA |
| OAI-CRED-005 | OpenAI configuration | Model unavailable or unsupported model. | Model error is clear and redacted; generated report textarea is not shown as success. | Do not record request body or raw response. | Pending QA |
| OAI-CRED-006 | OpenAI configuration | Model configuration missing, if applicable. | Configuration error is clear and redacted. | Status-level only. | Pending QA |

### 5.4 OpenAI API / Response Errors

| ID | Error Area | Scenario | Expected Safe Behavior | Evidence Rule | Status |
|---|---|---|---|---|---|
| OAI-API-001 | Quota | OpenAI quota exceeded. | Error notice explains quota/billing safely; raw response is not displayed. | Record only status-level category. | Pending QA |
| OAI-API-002 | Rate limit | OpenAI rate limit. | Error notice explains rate limit safely; generated report is not shown as success. | Record only status-level category. | Pending QA |
| OAI-API-003 | Timeout | OpenAI timeout. | Timeout error is clear and redacted; request body is not displayed. | Do not record prompt, payload, headers, or body. | Pending QA |
| OAI-API-004 | Network | OpenAI network failure. | Network error is clear and redacted; textarea state accurately reflects failure. | Status-level only. | Pending QA |
| OAI-API-005 | Response parsing | Malformed OpenAI response. | Safe unreadable-response error appears; raw response is not displayed. | Do not record raw response body. | Pending QA |
| OAI-API-006 | Empty generated text | Response has no usable generated text. | Empty-text error appears; generated report body is not falsely shown. | Do not record raw response body. | Pending QA |
| OAI-API-007 | Policy/refusal | Content policy or refusal-like response, if applicable. | UI does not misrepresent the result; generated text is reviewed before use. | Do not record generated body. | Pending QA |
| OAI-API-008 | Duplicate submit | Duplicate Generate click during pending request. | Duplicate request is prevented where possible; no false success state appears. | Do not record request body or generated text. | Pending QA |

### 5.5 AI Payload / State Errors Before OpenAI Call

| ID | Error Area | Scenario | Expected Safe Behavior | Evidence Rule | Status |
|---|---|---|---|---|---|
| PAYLOAD-001 | Payload transient | Missing AI payload transient. | OpenAI call does not occur; user is asked to fetch GA4 data again. | Do not record transient key or value. | Pending QA |
| PAYLOAD-002 | Payload transient | Expired AI payload transient. | OpenAI call does not occur; user is asked to fetch GA4 data again. | Do not record transient key or value. | Pending QA |
| PAYLOAD-003 | Payload validation | Invalid or malformed AI payload transient. | Payload is rejected before OpenAI call; body is not displayed or recorded. | Do not record payload body. | Pending QA |
| PAYLOAD-004 | Stale state | Stale payload after changing conditions. | Stale payload is not silently sent; user-visible state remains understandable. | Do not record payload body. | Pending QA |
| PAYLOAD-005 | Multi-admin state | Cross-user payload attempt, if safely testable later. | Cross-user payload is not used. | Do not record transient keys or payload body. | Pending QA |
| PAYLOAD-006 | Action order | OpenAI Generate attempted before GA4 Fetch. | OpenAI call does not occur without valid payload. | Status-level only. | Pending QA |
| PAYLOAD-007 | Failed fetch state | OpenAI Generate attempted after GA4 Fetch failure. | OpenAI call remains unavailable or safely blocked. | Status-level only. | Pending QA |

### 5.6 UI / Evidence Safety for External Failures

| ID | Error Area | Scenario | Expected Safe Behavior | Evidence Rule | Status |
|---|---|---|---|---|---|
| EVIDENCE-EXT-001 | Browser console | Browser console after GA4 error. | Console evidence is clean or recorded as status-level error category only. | Do not paste full console dumps if sensitive. | Pending QA |
| EVIDENCE-EXT-002 | Browser console | Browser console after OpenAI error. | Console evidence is clean or recorded as status-level error category only. | Do not paste full console dumps if sensitive. | Pending QA |
| EVIDENCE-EXT-003 | Network tab | Network tab usage policy. | Network evidence excludes headers, bodies, cookies, sessions, and Authorization values. | Method/status/service category only if needed. | Pending QA |
| EVIDENCE-EXT-004 | Screenshots | Screenshot evidence policy. | Screenshots are cropped or redacted to exclude credentials, identifiers, payloads, and generated text. | No secret or full-body screenshots. | Pending QA |
| EVIDENCE-EXT-005 | Terminal / WP-CLI | Terminal and WP-CLI evidence policy. | Terminal output excludes option values, headers, request bodies, raw responses, payload bodies, and generated text. | Review before recording. | Pending QA |
| EVIDENCE-EXT-006 | Support notes | Support-note evidence policy. | Support notes request redacted status-level information only. | No secrets, raw responses, payloads, or generated report text. | Pending QA |
| EVIDENCE-EXT-007 | Generated report | Generated report evidence policy. | Generated report body is not recorded; only display/edit/copy status may be recorded. | Do not paste generated report body. | Pending QA |
| EVIDENCE-EXT-008 | AI payload preview | AI payload preview evidence policy. | Full AI payload is not recorded; only preview presence/status may be recorded. | Do not paste payload body. | Pending QA |

## Stop Conditions

Stop the QA attempt immediately if any of the following occurs or becomes
necessary:

- A credential, token, or API key appears on screen, in logs, in the console, in
  Network tools, or in terminal output.
- A credential fragment, prefix, suffix, or token segment appears.
- An authorization header appears or is about to be recorded.
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
- Reproducing quota or rate limit failures would require excessive API usage.

After stopping, record only:

```text
Blocked / Fail
Screen or area
Action category
Redacted reason category
Confirmation that no secret/full body was recorded
```

## Result Recording Template

Use this category summary template in the later QA execution step. Keep notes
redacted and status-level only.

| Category | Reviewed | Pass items | Fail items | Blocked items | Not tested items | High-risk unresolved | Release blocker candidates | Notes |
|---|---|---:|---:|---:|---:|---|---|---|
| GA4 Credential / Configuration Errors | yes / no | 0 | 0 | 0 | 0 | yes / no | yes / no | redacted / status-level only |
| GA4 API / Response Errors | yes / no | 0 | 0 | 0 | 0 | yes / no | yes / no | redacted / status-level only |
| OpenAI Credential / Configuration Errors | yes / no | 0 | 0 | 0 | 0 | yes / no | yes / no | redacted / status-level only |
| OpenAI API / Response Errors | yes / no | 0 | 0 | 0 | 0 | yes / no | yes / no | redacted / status-level only |
| AI Payload / State Errors Before OpenAI Call | yes / no | 0 | 0 | 0 | 0 | yes / no | yes / no | redacted / status-level only |
| UI / Evidence Safety for External Failures | yes / no | 0 | 0 | 0 | 0 | yes / no | yes / no | redacted / status-level only |

Use this individual result template for each checked item:

```text
ID:
Scenario:
Result: Pass / Fail / Blocked / Not tested
External service involved: GA4 / OpenAI / none
External API call made: yes / no / not applicable
Observed safe behavior: status-level only
Secret/raw body leakage observed: yes / no
Notes: redacted / status-level only
```

## Recommended Next Step

Step 78 should document GA4 external API error-path QA results if GA4
error-path QA is executed next.

OpenAI external API error-path QA should remain a separate later step.

Alternatively, Step 78 may prepare Data minimization / privacy review if
external API error-path execution is deferred.

WordPress.org release remains `Hold`.

## Release Position

```text
WordPress.org release: Hold
Reason: External API error-path QA has not yet been executed, and credential strategy, Google OAuth/token lifecycle, data minimization/privacy review, WordPress.org compliance review, and release readiness decision remain incomplete.
```

## Outcome

- External API error-path QA checklist: created.
- Error-handling QA Phase 1: passed with deferred items.
- External API error-path QA execution: not started.
- Release readiness decision: not started.
- WordPress.org release remains `Hold`.
- Next recommended step: Step 78 GA4 external API error-path QA results, or Data
  minimization / privacy review if external QA execution is deferred.
