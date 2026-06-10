# Step 70: Pre-release Risk Inventory Checklist

## Purpose

This document creates the pre-release risk inventory checklist for Analytics
Report AI.

Controlled MVP E2E has passed for the local developer flow, including Settings
credential saved-state, controlled GA4 Fetch, AI Payload Preview, controlled
OpenAI Generate, generated report textarea display/editability, and copy action
status-level confirmation.

This checklist is not a release-readiness decision. It is a structured risk
inventory to execute before any WordPress.org release-readiness discussion.

WordPress.org release remains `Hold`.

## Scope

In scope:

- Credential / token / API key handling risks.
- External services disclosure risks.
- Google OAuth / token lifecycle risks.
- OpenAI API key handling risks.
- Admin security risks.
- External API communication risks.
- Data minimization risks.
- Generated report handling risks.
- WordPress.org compliance risks.
- QA coverage risks.
- UX / operational risks.
- Documentation risks.

Out of scope:

- Production code changes.
- Actual credential inspection.
- Actual API calls.
- GA4 Fetch execution.
- OpenAI Generate execution.
- Google OAuth execution.
- Release-readiness decision.
- Version bump.
- `readme.txt` release update.
- WordPress.org submission.

## Risk Inventory Checklist

Use this checklist in a later risk inventory execution step. Do not record
credential values, option values, real GA4 Property ID, real hostname, payload
bodies, request bodies, raw responses, generated report body, or copied report
content.

### 3.1 Credential / Secret Handling

| ID | Risk Area | Risk / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| CRED-001 | Google Access Token storage | Is current Google Access Token storage acceptable for public distribution? | Needs decision | Decide whether MVP database storage is acceptable, redesigned, or blocked. | Existing design is developer/manual-token oriented. |
| CRED-002 | OpenAI API Key storage | Is current OpenAI API Key storage acceptable for public distribution? | Needs decision | Decide storage strategy and public guidance. | Current MVP stores the key in plugin settings. |
| CRED-003 | Non-redisplay behavior | Are saved credentials prevented from being redisplayed in plaintext? | Known pass | Keep confirmed behavior and document release expectation. | Controlled browser checks passed. |
| CRED-004 | Credential removal behavior | Is credential removal via UI reliable and documented? | Known pass | Confirm expected release behavior and documentation. | Controlled cleanup checks passed. |
| CRED-005 | Accidental logging prevention | Are credentials prevented from appearing in logs, notices, console, or docs? | Needs review | Review logging/notice/console paths and document redaction policy. | No leakage observed in controlled E2E. |
| CRED-006 | Admin screen exposure prevention | Are credential values absent from admin form values and visible UI? | Known pass | Preserve non-redisplay and status-level UI. | Inputs remain empty for saved secrets. |
| CRED-007 | `wp_options` handling | Are credential-bearing options protected from unnecessary display and inspection? | Needs review | Define internal handling and support/debug policy. | Do not dump option values in support docs. |
| CRED-008 | Public distribution implications | Does public distribution need a different secret storage strategy? | Needs decision | Decide before WordPress.org readiness. | High-impact release concern. |
| CRED-009 | Developer/manual-token orientation | Is manual Google Access Token entry acceptable beyond local verification? | Needs decision | Decide whether manual token flow remains or blocks release. | OAuth remains unresolved. |
| CRED-010 | Release acceptability | Can current credential handling be accepted before public release? | Hold | Resolve or explicitly accept risks before release-readiness decision. | Likely release blocker candidate. |

### 3.2 Google OAuth / Token Lifecycle

| ID | Risk Area | Risk / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| OAUTH-001 | OAuth flow | The current MVP does not implement a full Google OAuth flow. | Needs implementation | Decide and implement public OAuth strategy or explicitly defer release. | Manual-token flow is not general-user friendly. |
| OAUTH-002 | Token refresh lifecycle | Is refresh/expiry behavior designed? | Needs implementation | Define refresh token, expiry tracking, or reconnect policy. | Current token may expire. |
| OAUTH-003 | Expired token handling | Are expired token errors clear and recoverable? | Needs review | QA expired-token path and user-facing errors. | Error handling should be status-level/redacted. |
| OAUTH-004 | Invalid token handling | Are invalid token errors clear and recoverable? | Needs review | QA invalid-token path. | No secret values should be shown. |
| OAUTH-005 | Permission errors | Are GA4 permission/scope errors understandable? | Needs review | QA permission error path. | Scope and property access need guidance. |
| OAUTH-006 | Token update guidance | Is user guidance for obtaining/updating token safe and clear? | Needs review | Write or revise setup/troubleshooting docs. | Avoid asking users to paste tokens into support channels. |
| OAUTH-007 | Public support burden | Is support burden acceptable without OAuth? | Needs decision | Decide before release-readiness discussion. | Manual tokens may create high support load. |
| OAUTH-008 | OAuth redesign requirement | Must OAuth strategy be redesigned before public release? | Needs decision | Make explicit go/hold decision. | Likely release blocker candidate. |

### 3.3 OpenAI Integration

| ID | Risk Area | Risk / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| OAI-001 | OpenAI API Key handling | Is API key entry/storage/usage acceptable for public distribution? | Needs decision | Decide public key handling strategy. | Current MVP uses saved admin key. |
| OAI-002 | Invalid key handling | Are invalid API key errors clear and safe? | Needs review | QA invalid-key path. | No raw response or key fragments should be shown. |
| OAI-003 | Quota / rate limit | Are quota/rate-limit errors clear and safe? | Needs review | QA quota/rate-limit path. | Need user-facing guidance. |
| OAI-004 | Timeout handling | Are OpenAI timeouts handled safely? | Needs review | QA timeout path. | Avoid duplicate submissions. |
| OAI-005 | Model selection policy | Is the model selection policy appropriate and documented? | Needs review | Confirm model, cost, capability, and support stance. | Release docs may need explanation. |
| OAI-006 | Prompt/payload minimization | Is only necessary analytics data sent to OpenAI? | Needs review | Review payload minimization before release. | Do not record full payload in docs. |
| OAI-007 | Generated report non-storage | Is generated report non-storage policy preserved and explained? | Known pass | Document and preserve behavior. | Controlled E2E did not record full body. |
| OAI-008 | External services disclosure | Is OpenAI disclosure complete for public release? | Needs review | Review readme/admin disclosure. | Must explain what is sent and when. |
| OAI-009 | User consent/awareness | Does UI clearly warn before sending analytics data to OpenAI? | Needs review | Verify staged review-before-send clarity. | Core staged flow is in place. |

### 3.4 GA4 Integration

| ID | Risk Area | Risk / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| GA4-001 | GA4 Property ID handling | Is property ID validation and guidance sufficient? | Needs review | Review validation and support docs. | Do not record real property IDs. |
| GA4-002 | Hostname filter | Does hostname filtering behave clearly when enabled/disabled? | Needs review | QA both states. | Hostname should be redacted in evidence. |
| GA4-003 | Directory scope | Does directory scope work and explain matching behavior? | Needs review | QA directory scope. | Record status-level only. |
| GA4-004 | Page scope | Does page scope work and explain exact matching behavior? | Needs review | QA page scope. | Record status-level only. |
| GA4-005 | Comparison options | Are comparison disabled/previous period/previous year paths covered? | Needs review | QA all comparison options. | Controlled E2E covered happy path only. |
| GA4-006 | Empty data handling | Is empty GA4 data handled gracefully? | Needs review | QA empty data path. | Important UX/error path. |
| GA4-007 | Permission errors | Are GA4 permission errors clear and safe? | Needs review | QA permission denied path. | No raw GA4 response should be shown. |
| GA4-008 | Invalid property ID | Is invalid property ID handling clear and safe? | Needs review | QA invalid property path. | Validation exists; release UX still needs review. |
| GA4-009 | Date range validation | Are date range and maximum range rules clear? | Needs review | QA invalid and max-range paths. | Step 16 introduced max range behavior. |
| GA4-010 | API timeout/failure | Are GA4 timeout/failure states clear and safe? | Needs review | QA timeout/failure paths. | User-facing messages should be redacted. |

### 3.5 Admin Security

| ID | Risk Area | Risk / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| ADMIN-001 | Capability checks | Are all admin actions protected by appropriate capabilities? | Needs review | Review all admin entry/action points. | Expected capability is admin-only. |
| ADMIN-002 | Nonce checks | Are POST/action flows nonce-protected? | Needs review | Review all form/action handlers. | Include GA4/OpenAI actions. |
| ADMIN-003 | Sanitization | Are all admin inputs sanitized consistently? | Needs review | Review Settings and Report Builder inputs. | Include paths, dates, scope, host. |
| ADMIN-004 | Escaping | Is all admin output escaped appropriately? | Needs review | Review UI output and notices. | Include dynamic payload/summary areas. |
| ADMIN-005 | Safe redirects | Are redirects safe and status-preserving? | Needs review | Review post-action redirects. | Avoid leaking sensitive data into URLs. |
| ADMIN-006 | Form handling | Are Settings and action forms isolated and predictable? | Needs review | Review form boundaries. | Keep GA4/OpenAI actions separated. |
| ADMIN-007 | Admin notices | Are notices clear and secret-free? | Known pass | Preserve behavior and QA error cases. | Controlled E2E notices were secret-free. |
| ADMIN-008 | Settings save behavior | Is Settings save stable after the saved-state fix? | Known pass | Preserve behavior and cover regression checklist. | Step 60/62 resolved token saved-state issue. |
| ADMIN-009 | Action separation | Are GA4 Fetch and OpenAI Generate clearly separated? | Known pass | Preserve staged review-before-send flow. | Controlled E2E confirmed separated flow. |
| ADMIN-010 | Unintended API calls | Can users avoid accidental external API calls? | Needs review | Review disabled/loading/confirmation/submit states. | Important operational safety concern. |

### 3.6 Data Minimization / Privacy

| ID | Risk Area | Risk / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| DATA-001 | AI payload minimization | Is the AI payload limited to necessary analytics data? | Needs review | Review payload contents before release. | Do not record full payload in evidence. |
| DATA-002 | Full payload preview exposure | Is payload preview appropriate for admin-only use? | Needs decision | Decide whether additional masking/UX is required. | Preview is useful but sensitive. |
| DATA-003 | Generated report non-storage | Is generated report non-storage policy clear and preserved? | Known pass | Document behavior. | E2E did not store/report full generated body. |
| DATA-004 | Raw response non-storage | Are raw GA4/OpenAI responses not stored/exposed unnecessarily? | Needs review | Review client/action behavior. | Evidence recorded no raw response. |
| DATA-005 | Redacted logging policy | Is there a defined policy for logs/support evidence? | Needs decision | Create/confirm redacted logging policy. | Especially important for public support. |
| DATA-006 | Analytics data sent to OpenAI | Is user-facing explanation sufficient? | Needs review | Review admin and readme disclosure. | Must explain when data is sent. |
| DATA-007 | Privacy/external services disclosure | Is privacy language complete? | Needs review | Review privacy/readme/admin docs. | Required before release-readiness discussion. |
| DATA-008 | Screenshot/docs safety | Are screenshot and documentation safety rules clear? | Needs review | Create or consolidate redaction guidance. | Existing maturation docs use status-level evidence. |

### 3.7 WordPress.org Compliance

| ID | Risk Area | Risk / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| WPO-001 | `readme.txt` | Is readme complete and aligned with behavior? | Needs review | Review description, FAQ, external services, changelog. | Release wording may need update. |
| WPO-002 | Plugin headers | Are plugin headers complete and accurate? | Needs review | Verify metadata before release-readiness checkpoint. | Version remains `0.1.0`. |
| WPO-003 | Stable tag | Is stable tag aligned with release package? | Needs review | Confirm before release package. | No release decision yet. |
| WPO-004 | Assets | Are assets complete and appropriate? | Needs review | Review banners/icons/screenshots if any. | No asset work in this step. |
| WPO-005 | Licensing | Are licenses and bundled assets compliant? | Needs review | Review license files and notices. | Important before distribution. |
| WPO-006 | Uninstall behavior | Is uninstall behavior safe and documented? | Needs review | Review cleanup expectations. | Avoid deleting unexpected data. |
| WPO-007 | Text domain / i18n | Are strings translatable and text domain consistent? | Needs review | Run/review i18n readiness. | JS localization readiness was improved earlier. |
| WPO-008 | External services disclosure | Does disclosure meet WordPress.org expectations? | Needs review | Review what is sent, when, to whom, and links. | High priority. |
| WPO-009 | Privacy statement | Is privacy/data handling described clearly? | Needs review | Draft/review privacy language. | External analytics/AI services involved. |
| WPO-010 | No bundled secrets | Are there no bundled or committed secrets? | Needs review | Run secret-pattern checks before release-readiness. | Do not expose real credentials. |
| WPO-011 | No hidden remote calls | Are all remote calls user-triggered and disclosed? | Needs review | Review admin flow and docs. | Staged flow is current design. |
| WPO-012 | Plugin Check / PHPCS posture | Are Plugin Check and coding standards acceptable? | Needs review | Run current checks before readiness. | Previous checks were clean, but refresh later. |

### 3.8 QA Coverage

| ID | Risk Area | Risk / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| QA-001 | Successful E2E | Has the happy-path MVP E2E passed? | Known pass | Preserve evidence. | GA4 Fetch and OpenAI Generate passed. |
| QA-002 | Empty GA4 data | Is empty GA4 data handled well? | Needs review | Execute QA case. | Important report-quality edge case. |
| QA-003 | Invalid date range | Are invalid date ranges blocked and explained? | Needs review | Execute QA case. | Include max-date-range behavior. |
| QA-004 | Expired Google token | Is expired token behavior clear? | Needs review | Execute QA case safely. | Status-level evidence only. |
| QA-005 | Invalid Google token | Is invalid token behavior clear? | Needs review | Execute QA case safely. | No token values in evidence. |
| QA-006 | Invalid OpenAI API key | Is invalid key behavior clear? | Needs review | Execute QA case safely. | No key values in evidence. |
| QA-007 | OpenAI quota/rate limit | Are quota/rate-limit errors clear? | Needs review | Execute or simulate QA case if feasible. | Avoid raw responses. |
| QA-008 | GA4 permission error | Is GA4 permission failure handled safely? | Needs review | Execute QA case if feasible. | Avoid raw responses. |
| QA-009 | Hostname filter enabled/disabled | Are both hostname filter states covered? | Needs review | Execute QA cases. | Redact hostnames. |
| QA-010 | Site-wide scope | Is site-wide scope covered? | Needs review | Execute QA case. | Status-level only. |
| QA-011 | Directory scope | Is directory scope covered? | Needs review | Execute QA case. | Redact paths if needed. |
| QA-012 | Page scope | Is page scope covered? | Needs review | Execute QA case. | Redact paths if needed. |
| QA-013 | Comparison disabled | Is no-comparison path covered? | Needs review | Execute QA case. | Status-level only. |
| QA-014 | Previous period comparison | Is previous-period comparison covered? | Needs review | Execute QA case. | Status-level only. |
| QA-015 | Previous year comparison | Is previous-year comparison covered? | Needs review | Execute QA case. | Status-level only. |
| QA-016 | Browser console checks | Are console checks part of all browser QA? | Known pass | Keep in QA template. | Controlled E2E included console checks. |
| QA-017 | No credential leakage checks | Are no-leak checks part of all browser QA? | Known pass | Keep in QA template. | Controlled E2E included safety checks. |

### 3.9 UX / Operations

| ID | Risk Area | Risk / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| UX-001 | Loading states | Are loading states clear during GA4/OpenAI actions? | Needs review | Review browser behavior and UX copy. | Prevent confusion during external calls. |
| UX-002 | Disabled states | Are buttons disabled when actions should not run? | Needs review | Review action gating. | Helps avoid accidental calls. |
| UX-003 | Duplicate click prevention | Are duplicate submissions prevented? | Known pass | Preserve and review edge cases. | Previous hardening added submit guards. |
| UX-004 | Success notices | Are success notices clear and secret-free? | Known pass | Preserve and QA across paths. | Settings and E2E notices were secret-free. |
| UX-005 | Error notices | Are error notices clear, actionable, and redacted? | Needs review | QA error matrix. | External API errors need care. |
| UX-006 | Copy feedback | Is copy feedback clear and reliable? | Known pass | Preserve and document. | Copy action status was checked. |
| UX-007 | Textarea behavior | Is generated report textarea behavior intuitive? | Known pass | Preserve and consider polish. | Textarea displayed and editable. |
| UX-008 | Review-before-send flow | Is staged review-before-send flow clear? | Known pass | Preserve flow and review copy. | GA4 Fetch precedes OpenAI Generate. |
| UX-009 | Credential guidance | Is credential setup guidance understandable? | Needs review | Improve docs/UI if needed. | Manual-token flow is a known risk. |
| UX-010 | Support expectations | Are support boundaries clear for MVP? | Needs decision | Define support docs/FAQ before release-readiness. | Public support burden may be high. |

### 3.10 Documentation

| ID | Risk Area | Risk / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| DOC-001 | Local setup docs | Are local setup steps documented? | Needs review | Review/update docs. | Developer verification depends on setup. |
| DOC-002 | Credential setup docs | Are credential setup steps safe and clear? | Needs review | Review/update docs. | Do not encourage sharing secrets. |
| DOC-003 | E2E procedure docs | Are controlled E2E procedures documented? | Known pass | Preserve and consolidate if needed. | Steps 63-68 provide detailed procedures/results. |
| DOC-004 | Redaction policy docs | Is redaction policy easy to find and follow? | Needs review | Consolidate policy for future QA/support. | Many maturation docs include rules. |
| DOC-005 | Release hold rationale | Is release hold rationale documented? | Known pass | Preserve and update as risks change. | Step 69 records Hold. |
| DOC-006 | External services disclosure draft | Is disclosure complete and release-ready? | Needs review | Review readme/admin copy. | Must be clear before release-readiness. |
| DOC-007 | Troubleshooting docs | Are common failure modes documented? | Needs review | Add or review troubleshooting guidance. | Token/key/permission/quota cases. |
| DOC-008 | Known MVP boundaries | Are non-goals and MVP boundaries documented? | Known pass | Preserve and keep current. | Step 69 lists boundaries. |

## Risk Severity Guide

Use this severity guide when executing the risk inventory:

```text
High: Public release should not proceed before this is resolved or explicitly accepted.
Medium: Should be reviewed and preferably resolved before release-readiness decision.
Low: Can be documented or deferred if it does not affect safety/compliance/core UX.
Out of MVP: Not required for MVP, but should remain documented as non-goal.
```

Suggested interpretation:

- High risk may become a release blocker candidate.
- Medium risk should receive an owner or explicit defer decision.
- Low risk can be tracked as polish or documentation.
- Out of MVP should be retained as a boundary, not treated as missing scope.

## Initial Risk Position

```text
Controlled MVP E2E: Passed
Pre-release risk inventory: Not yet executed
Release readiness decision: Not started
WordPress.org release: Hold
```

## Expected Result Recording Template

Use this template in a later risk inventory execution or results step. Notes
must remain redacted and status-level only.

| Category | Reviewed | High-risk items | Medium-risk items | Low-risk items | Out-of-MVP items | Release blocker candidates | Notes |
|---|---|---:|---:|---:|---:|---|---|
| Credential / Secret Handling | yes / no | 0 | 0 | 0 | 0 | yes / no | redacted / status-level only |
| Google OAuth / Token Lifecycle | yes / no | 0 | 0 | 0 | 0 | yes / no | redacted / status-level only |
| OpenAI Integration | yes / no | 0 | 0 | 0 | 0 | yes / no | redacted / status-level only |
| GA4 Integration | yes / no | 0 | 0 | 0 | 0 | yes / no | redacted / status-level only |
| Admin Security | yes / no | 0 | 0 | 0 | 0 | yes / no | redacted / status-level only |
| Data Minimization / Privacy | yes / no | 0 | 0 | 0 | 0 | yes / no | redacted / status-level only |
| WordPress.org Compliance | yes / no | 0 | 0 | 0 | 0 | yes / no | redacted / status-level only |
| QA Coverage | yes / no | 0 | 0 | 0 | 0 | yes / no | redacted / status-level only |
| UX / Operations | yes / no | 0 | 0 | 0 | 0 | yes / no | redacted / status-level only |
| Documentation | yes / no | 0 | 0 | 0 | 0 | yes / no | redacted / status-level only |

## Recommended Next Step

- Step 71 should execute or document the pre-release risk inventory results.
- Alternatively, Step 71 may focus specifically on Credential / external
  services disclosure review if preferred.
- WordPress.org release remains `Hold`.

## Outcome

- Pre-release risk inventory checklist: created.
- Controlled MVP E2E flow: already passed.
- Release readiness decision: not started.
- WordPress.org release remains `Hold`.
- Next recommended step: Step 71 risk inventory results or credential /
  external services disclosure review.

## Safety Notes

- This checklist does not record real credentials.
- This checklist does not record API keys, access tokens, Authorization
  headers, credential fragments, prefixes, suffixes, values beginning with
  `sk-`, or JWT fragments.
- This checklist does not record `wp_options` credential or plugin settings
  option values.
- This checklist does not record actual GA4 Property ID.
- This checklist does not record actual hostname or site domain.
- This checklist does not record request bodies.
- This checklist does not record full AI payload.
- This checklist does not record OpenAI request body.
- This checklist does not record raw OpenAI response.
- This checklist does not record raw GA4 response.
- This checklist does not record generated report body.
- This checklist does not run GA4 Fetch or OpenAI Generate.
- This checklist does not start Google OAuth.
