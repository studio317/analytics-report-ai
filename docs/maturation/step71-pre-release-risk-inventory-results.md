# Step 71: Pre-release Risk Inventory Results

## Purpose

This document records the Step 71 pre-release risk inventory results for
Analytics Report AI.

The review is based on the Step 70 pre-release risk inventory checklist.
Controlled MVP E2E has already passed for the local developer happy path, but
this is not a release-readiness decision.

WordPress.org release remains `Hold`.

## Review Method

This was a docs-only review.

Status-level review inputs:

- Step 70 checklist was used as the baseline.
- Existing repository docs were reviewed.
- Source was inspected read-only for structure, action flow, disclosure strings,
  capability checks, nonce use, credential/status handling, remote-call
  locations, and output/escaping patterns.
- Production code was not changed.
- External API calls were not performed.
- Credentials were not inspected.
- `wp_options` credential/settings values were not inspected.
- GA4 Fetch was not executed.
- OpenAI Generate was not executed.
- Google OAuth was not started.

## Executive Summary

| Item | Status |
|---|---|
| Controlled MVP E2E | Passed |
| Pre-release risk inventory | Reviewed |
| Release readiness decision | Not started |
| WordPress.org release | Hold |

High-level conclusion:

- MVP happy path is working locally.
- Public release still has blocker candidates around credential strategy,
  Google OAuth/token lifecycle, external services disclosure, privacy/data
  handling, admin security review, and broader error-path QA.
- The next step should not be WordPress.org readiness yet.
- The next recommended step is Credential / external services disclosure
  review.

## Risk Results by Category

### 1. Credential / Secret Handling

Reviewed: yes

Overall severity: High

Release blocker candidates: yes

Summary:

- Controlled browser checks confirmed credential non-redisplay, cleanup, and no
  observed leakage in the local happy path.
- The current public distribution strategy for storing Google Access Token and
  OpenAI API Key still needs a release decision.
- Manual-token orientation and `wp_options` handling remain high-impact
  pre-release concerns.

Key findings:

- Known pass: credential non-redisplay, credential cleanup, admin screen
  non-redisplay.
- Needs decision: current credential storage for public distribution.
- Needs review: accidental logging/support/debug policy.

Recommended next actions:

- Decide whether current credential storage can be accepted, redesigned, or
  explicitly blocked for public release.
- Document support/debug rules that forbid option value dumps and secret
  sharing.
- Pair this with the external services disclosure review.

### 2. Google OAuth / Token Lifecycle

Reviewed: yes

Overall severity: High

Release blocker candidates: yes

Summary:

- The current MVP remains developer/manual-token oriented.
- Full OAuth flow, refresh/expiry lifecycle, reconnect/revoke UX, and public
  support expectations remain unresolved.

Key findings:

- Needs implementation or explicit release deferral: OAuth strategy.
- Needs decision: whether manual Google Access Token flow can be publicly
  shipped.
- Needs review: expired/invalid token and permission error handling.

Recommended next actions:

- Decide whether public release requires OAuth redesign.
- Define token lifecycle, reconnect, and revocation expectations.
- QA expired/invalid/permission paths with redacted evidence only.

### 3. OpenAI Integration

Reviewed: yes

Overall severity: Medium

Release blocker candidates: needs decision

Summary:

- Controlled OpenAI Generate happy path passed.
- OpenAI API Key storage/public handling and user-facing disclosure still need
  review.
- Error paths such as invalid key, quota/rate limit, and timeout have not been
  broadly covered.

Key findings:

- Known pass: generated report textarea display/editability and copy action
  status-level confirmation.
- Needs decision: public OpenAI API key handling strategy.
- Needs review: invalid key, quota, rate-limit, timeout, model policy, and
  payload minimization.

Recommended next actions:

- Review disclosure language for what is sent to OpenAI and when.
- QA key/error/quota paths without recording request/response bodies.
- Confirm model/cost/support stance before release-readiness.

### 4. GA4 Integration

Reviewed: yes

Overall severity: Medium

Release blocker candidates: no

Summary:

- Controlled GA4 Fetch happy path passed.
- Edge cases around empty data, invalid/permission errors, scope options,
  hostname filtering, comparison modes, and timeout handling still need QA.

Key findings:

- Known pass: GA4 happy-path fetch and AI Payload Preview display.
- Needs review: empty data, invalid property, permission errors, scopes,
  comparison options, and timeout/failure states.

Recommended next actions:

- Build and execute a GA4 error-path QA checklist.
- Keep evidence status-level and redact property IDs, hostnames, paths, and
  analytics details.

### 5. Admin Security

Reviewed: yes

Overall severity: High

Release blocker candidates: yes

Summary:

- Source inspection shows admin-only menus and action handling patterns are in
  place, but a release-grade security review has not been completed.
- Capability, nonce, sanitization, escaping, redirects, form boundaries, and
  unintended API-call prevention should be reviewed explicitly.

Key findings:

- Known pass: staged GA4 Fetch before OpenAI Generate flow is working.
- Needs review: capability checks, nonce checks, input sanitization, output
  escaping, safe redirects, and action separation under failure conditions.

Recommended next actions:

- Create and execute an admin security review checklist.
- Review both success and failure paths.
- Confirm no sensitive values can be exposed through notices, URLs, logs, or
  admin output.

### 6. Data Minimization / Privacy

Reviewed: yes

Overall severity: High

Release blocker candidates: yes

Summary:

- Controlled E2E did not record full payloads, raw responses, or generated
  report body.
- Public release still needs a formal review of what analytics data is shown in
  preview, sent to OpenAI, stored temporarily, and disclosed to users.

Key findings:

- Known pass: generated report non-storage behavior was confirmed at
  status-level in controlled E2E.
- Needs review: payload minimization, payload preview exposure, raw response
  non-storage, analytics data sent to OpenAI, and privacy disclosure.
- Needs decision: redacted logging/support evidence policy.

Recommended next actions:

- Review AI payload minimization and preview exposure.
- Review privacy/external services disclosure.
- Define screenshot/support/debug evidence rules for public support.

### 7. WordPress.org Compliance

Reviewed: yes

Overall severity: High

Release blocker candidates: yes

Summary:

- WordPress.org release remains on hold.
- Readme, external services disclosure, privacy language, assets, licensing,
  uninstall behavior, i18n, no hidden remote calls, Plugin Check, and PHPCS
  posture all need a dedicated release-readiness review.

Key findings:

- Needs review: `readme.txt`, plugin headers, stable tag, assets, licensing,
  uninstall behavior, text domain/i18n, external services disclosure, privacy
  statement, no bundled secrets, no hidden remote calls, Plugin Check/PHPCS.

Recommended next actions:

- Do not proceed directly to release readiness.
- Review external services and privacy first.
- Later refresh Plugin Check/PHPCS and distribution package checks.

### 8. QA Coverage

Reviewed: yes

Overall severity: High

Release blocker candidates: yes

Summary:

- Happy-path E2E has passed.
- Broader error-path and matrix QA are incomplete.

Key findings:

- Known pass: successful GA4 Fetch, successful OpenAI Generate, console checks,
  no credential leakage checks.
- Needs review: empty GA4 data, invalid date range, expired/invalid Google
  token, invalid OpenAI API key, quota/rate limit, GA4 permission errors,
  hostname filter states, scopes, and comparison modes.

Recommended next actions:

- Create an error-handling QA checklist.
- Execute QA cases with status-level/redacted evidence only.

### 9. UX / Operations

Reviewed: yes

Overall severity: Medium

Release blocker candidates: no

Summary:

- Core staged review-before-send flow passed.
- Loading states, disabled states, error notices, credential guidance, and
  public support expectations remain to be reviewed.

Key findings:

- Known pass: duplicate click prevention, success notices, copy feedback,
  textarea behavior, staged review-before-send flow.
- Needs review: loading states, disabled states, error notices, credential
  guidance.
- Needs decision: support expectations for manual-token/public distribution.

Recommended next actions:

- Review UX polish alongside error-path QA.
- Clarify user guidance and support boundaries.

### 10. Documentation

Reviewed: yes

Overall severity: Medium

Release blocker candidates: no

Summary:

- E2E procedure and release-hold rationale are documented.
- Local setup, credential setup, redaction policy consolidation, external
  services disclosure, troubleshooting, and known boundaries still need
  release-oriented review.

Key findings:

- Known pass: controlled E2E procedure docs and release-hold rationale.
- Needs review: setup docs, credential docs, redaction policy, disclosure,
  troubleshooting.

Recommended next actions:

- Consolidate redaction and E2E guidance.
- Draft/update troubleshooting for token/key/permission/quota cases.
- Keep release hold rationale current.

## Detailed Risk Table

| ID | Category | Risk / Question | Severity | Release Blocker Candidate | Result | Recommended Next Action |
|---|---|---|---|---|---|---|
| CRED-001 | Credential / Secret Handling | Current Google Access Token storage for public distribution. | High | yes | Current MVP database storage is not release-accepted. | Decide redesign, deferral, or explicit acceptance. |
| CRED-002 | Credential / Secret Handling | Current OpenAI API Key storage for public distribution. | High | yes | Public handling strategy unresolved. | Decide storage/support model before readiness. |
| CRED-005 | Credential / Secret Handling | Accidental logging/support/debug exposure prevention. | Medium | needs decision | No happy-path leakage observed, but policy review remains. | Define redacted logging/support policy. |
| CRED-007 | Credential / Secret Handling | `wp_options` credential option handling. | High | yes | Option values were not inspected; support/debug policy unresolved. | Forbid value dumps and decide public storage strategy. |
| CRED-009 | Credential / Secret Handling | Developer/manual-token orientation. | High | yes | Manual token remains MVP-oriented. | Decide whether public release requires OAuth. |
| CRED-010 | Credential / Secret Handling | Overall credential handling release acceptability. | High | yes | Hold. | Resolve credential strategy before readiness. |
| OAUTH-001 | Google OAuth / Token Lifecycle | No full OAuth flow. | High | yes | Public OAuth strategy unresolved. | Decide and design OAuth/reconnect flow or defer release. |
| OAUTH-002 | Google OAuth / Token Lifecycle | Token refresh/expiry lifecycle. | High | yes | Refresh/expiry strategy unresolved. | Define lifecycle and expiry handling. |
| OAUTH-003 | Google OAuth / Token Lifecycle | Expired token handling. | Medium | needs decision | Error path not broadly QAed. | Add expired-token QA case. |
| OAUTH-004 | Google OAuth / Token Lifecycle | Invalid token handling. | Medium | needs decision | Error path not broadly QAed. | Add invalid-token QA case. |
| OAUTH-005 | Google OAuth / Token Lifecycle | GA4 permission/scope errors. | Medium | needs decision | Error path not broadly QAed. | Add permission/scope QA case. |
| OAUTH-007 | Google OAuth / Token Lifecycle | Public support burden without OAuth. | High | yes | Support burden unresolved. | Decide support model before readiness. |
| OAUTH-008 | Google OAuth / Token Lifecycle | OAuth redesign requirement before public release. | High | yes | Needs decision. | Make explicit go/hold decision. |
| OAI-001 | OpenAI Integration | OpenAI API Key public handling strategy. | High | needs decision | Public handling strategy unresolved. | Review with credential strategy. |
| OAI-002 | OpenAI Integration | Invalid OpenAI API key handling. | Medium | no | Error path not broadly QAed. | Add invalid-key QA case. |
| OAI-003 | OpenAI Integration | Quota/rate-limit handling. | Medium | no | Error path not broadly QAed. | Add quota/rate-limit QA case. |
| OAI-004 | OpenAI Integration | Timeout handling. | Medium | no | Error path not broadly QAed. | Add timeout QA case. |
| OAI-006 | OpenAI Integration | Prompt/payload minimization. | High | needs decision | Payload minimization needs release review. | Review fields sent to OpenAI. |
| OAI-008 | OpenAI Integration | OpenAI external services disclosure. | High | yes | Disclosure needs release review. | Review readme/admin disclosure. |
| OAI-009 | OpenAI Integration | User consent/awareness before sending analytics data. | High | needs decision | Staged flow exists, release copy needs review. | Review UI and disclosure copy. |
| GA4-002 | GA4 Integration | Hostname filter enabled/disabled behavior. | Medium | no | Matrix QA incomplete. | Add QA cases for both states. |
| GA4-003 | GA4 Integration | Directory scope behavior. | Medium | no | Matrix QA incomplete. | Add directory-scope QA case. |
| GA4-004 | GA4 Integration | Page scope behavior. | Medium | no | Matrix QA incomplete. | Add page-scope QA case. |
| GA4-005 | GA4 Integration | Comparison options. | Medium | no | Matrix QA incomplete. | Add no/previous-period/previous-year QA cases. |
| GA4-006 | GA4 Integration | Empty GA4 data handling. | Medium | no | Error/edge path not QAed. | Add empty-data QA case. |
| GA4-007 | GA4 Integration | GA4 permission errors. | Medium | no | Error path not broadly QAed. | Add permission-error QA case. |
| GA4-008 | GA4 Integration | Invalid property ID. | Medium | no | Validation exists; release UX needs review. | Review validation and error message. |
| GA4-010 | GA4 Integration | GA4 timeout/failure handling. | Medium | no | Error path not broadly QAed. | Add timeout/failure QA case. |
| ADMIN-001 | Admin Security | Capability checks. | High | yes | Needs release-grade review. | Execute admin security checklist. |
| ADMIN-002 | Admin Security | Nonce checks. | High | yes | Needs release-grade review. | Execute admin security checklist. |
| ADMIN-003 | Admin Security | Sanitization. | High | yes | Needs release-grade review. | Execute admin security checklist. |
| ADMIN-004 | Admin Security | Escaping. | High | yes | Needs release-grade review. | Execute admin security checklist. |
| ADMIN-005 | Admin Security | Safe redirects/form state. | Medium | needs decision | Needs review. | Review post/action redirects. |
| ADMIN-010 | Admin Security | Prevention of unintended external API calls. | Medium | needs decision | Needs review beyond happy path. | Review disabled/loading/submit states. |
| DATA-001 | Data Minimization / Privacy | AI payload minimization. | High | yes | Release review incomplete. | Review payload contents without recording full payload. |
| DATA-002 | Data Minimization / Privacy | Full payload preview exposure. | High | needs decision | Preview is useful but sensitive. | Decide masking/UX/disclosure posture. |
| DATA-005 | Data Minimization / Privacy | Redacted logging/support policy. | High | yes | Policy not consolidated. | Create support/debug evidence policy. |
| DATA-006 | Data Minimization / Privacy | Analytics data sent to OpenAI explanation. | High | yes | Disclosure needs release review. | Review admin/readme disclosure. |
| DATA-007 | Data Minimization / Privacy | Privacy/external services disclosure. | High | yes | Privacy language needs release review. | Draft/review privacy language. |
| WPO-001 | WordPress.org Compliance | `readme.txt` release completeness. | Medium | needs decision | Needs release review. | Review readme before readiness. |
| WPO-008 | WordPress.org Compliance | External services disclosure. | High | yes | Needs WordPress.org-focused review. | Review what is sent, when, to whom, and links. |
| WPO-009 | WordPress.org Compliance | Privacy statement. | High | yes | Needs release review. | Draft/review privacy statement. |
| WPO-010 | WordPress.org Compliance | No bundled secrets. | High | yes | Needs refreshed check before readiness. | Run secret-pattern checks later. |
| WPO-011 | WordPress.org Compliance | No hidden remote calls. | High | yes | Needs review. | Confirm remote calls are user-triggered/disclosed. |
| WPO-012 | WordPress.org Compliance | Plugin Check / PHPCS posture. | Medium | needs decision | Needs refreshed checks before readiness. | Run checks at readiness checkpoint. |
| QA-002 | QA Coverage | Empty GA4 data. | Medium | no | Not yet QAed. | Add QA case. |
| QA-003 | QA Coverage | Invalid date range. | Medium | no | Needs QA coverage. | Add QA case. |
| QA-004 | QA Coverage | Expired Google token. | High | needs decision | Token lifecycle/error path unresolved. | Add QA case after strategy decision. |
| QA-005 | QA Coverage | Invalid Google token. | Medium | no | Not yet QAed. | Add QA case. |
| QA-006 | QA Coverage | Invalid OpenAI API key. | Medium | no | Not yet QAed. | Add QA case. |
| QA-007 | QA Coverage | OpenAI quota/rate limit. | Medium | no | Not yet QAed. | Add QA case or safe simulation. |
| QA-008 | QA Coverage | GA4 permission error. | Medium | no | Not yet QAed. | Add QA case. |
| QA-009 | QA Coverage | Hostname filter enabled/disabled. | Medium | no | Not yet QAed. | Add matrix QA cases. |
| QA-011 | QA Coverage | Directory scope. | Medium | no | Not yet QAed. | Add matrix QA case. |
| QA-012 | QA Coverage | Page scope. | Medium | no | Not yet QAed. | Add matrix QA case. |
| QA-014 | QA Coverage | Previous period comparison. | Medium | no | Not yet QAed. | Add matrix QA case. |
| QA-015 | QA Coverage | Previous year comparison. | Medium | no | Not yet QAed. | Add matrix QA case. |
| UX-001 | UX / Operations | Loading states. | Medium | no | Needs review. | Review during error/slow-path QA. |
| UX-002 | UX / Operations | Disabled states. | Medium | no | Needs review. | Review action availability and external-call safety. |
| UX-005 | UX / Operations | Error notices. | Medium | no | Needs review. | Pair with error-path QA. |
| UX-009 | UX / Operations | Credential guidance. | Medium | needs decision | Manual-token guidance is sensitive. | Review with credential/disclosure step. |
| UX-010 | UX / Operations | Support expectations. | Medium | needs decision | Public support model unresolved. | Define support boundaries. |
| DOC-002 | Documentation | Credential setup docs. | Medium | needs decision | Needs safe public guidance. | Review after credential strategy decision. |
| DOC-004 | Documentation | Redaction policy docs. | Medium | no | Needs consolidation. | Consolidate from maturation docs. |
| DOC-006 | Documentation | External services disclosure draft. | High | yes | Needs release review. | Review readme/admin disclosure. |
| DOC-007 | Documentation | Troubleshooting docs. | Medium | no | Needs coverage for common failures. | Draft token/key/permission/quota guidance. |

## Suggested Initial Severity Classification

High or release blocker candidate:

- Current credential storage strategy for public distribution.
- Manual Google Access Token flow / no full OAuth flow.
- Google token lifecycle / refresh strategy.
- External services disclosure completeness.
- Privacy / analytics data sent to OpenAI explanation.
- WordPress.org compliance around external services and privacy.
- Broader error-path QA not yet executed.

Medium:

- OpenAI invalid key / quota / rate-limit handling.
- GA4 invalid property / permission / empty data handling.
- Hostname filter enabled / disabled QA.
- Directory / page scope QA.
- Comparison option QA.
- Admin security review still pending.
- UX loading / disabled / duplicate-click review.
- Documentation consolidation.

Known pass:

- Controlled happy-path GA4 Fetch.
- Controlled happy-path OpenAI Generate.
- Credential non-redisplay in controlled browser checks.
- Generated report textarea display/editability.
- Copy action status-level confirmation.
- No credential leakage observed in controlled E2E.

## Release Blocker Candidate List

Current release blocker candidates:

- Credential / public distribution strategy unresolved.
- Google OAuth / manual token strategy unresolved.
- Token lifecycle / refresh handling unresolved.
- External services disclosure incomplete or not yet reviewed for release.
- Privacy / data handling language incomplete or not yet reviewed for release.
- Error-path QA matrix incomplete.
- Admin security review incomplete.

## Non-blocking / Deferred Candidates

The following remain MVP non-goals and should not be treated as direct release
readiness blockers unless the product scope changes:

- Scheduling.
- PDF output.
- Google Drive save.
- Template save.
- Multiple AI providers.
- Multiple GA4 properties.
- Markdown / HTML output.
- CSV / Excel export.

## Recommended Next Steps

Recommended priority order:

1. Step 72: Credential / external services disclosure review.
2. Step 73: Admin security review checklist.
3. Step 74: Error-handling QA checklist.
4. Step 75: Data minimization / privacy review.
5. Step 76: WordPress.org compliance checklist.
6. Step 77: WordPress.org readiness checkpoint.

Step 71 should not proceed directly to the WordPress.org readiness checkpoint.
The next recommended step is Credential / external services disclosure review.

## Release Position

```text
WordPress.org release: Hold
Reason: Controlled MVP E2E has passed, but release blocker candidates remain unresolved, especially credential/public distribution strategy, Google OAuth/token lifecycle, external services disclosure, privacy/data handling, admin security review, and error-path QA.
```

## Outcome

- Pre-release risk inventory results: documented.
- Controlled MVP E2E flow: passed.
- Release blocker candidates: identified.
- Release readiness decision: not started.
- WordPress.org release remains `Hold`.
- Next recommended step: Step 72 Credential / external services disclosure
  review.

## Safety Notes

- This review does not record real credentials.
- This review does not record API keys, access tokens, Authorization headers,
  credential fragments, prefixes, suffixes, values beginning with `sk-`, or JWT
  fragments.
- This review does not record `wp_options` credential or plugin settings option
  values.
- This review does not run `wp option get` for credential/settings options.
- This review does not record actual GA4 Property ID.
- This review does not record actual hostname or site domain.
- This review does not record request bodies.
- This review does not record full AI payload.
- This review does not record OpenAI request body.
- This review does not record raw OpenAI response.
- This review does not record raw GA4 response.
- This review does not record generated report body.
- This review does not run GA4 Fetch or OpenAI Generate.
- This review does not start Google OAuth.
