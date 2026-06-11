# Step 87: External API Error-path QA Execution Plan

## Step Summary

This document records the Step 87 external API error-path QA execution plan for
Analytics Report AI.

The purpose is to convert the deferred external API error-path QA blocker into
a safe execution plan and checklist before any real GA4 or OpenAI failure-path
testing is attempted.

This is a docs-only QA execution plan. It does not execute QA, trigger GA4
Fetch, trigger OpenAI Generate, start Google OAuth, change settings, inspect
stored option values, or make external API calls.

This document is not release-final evidence. It is a planning artifact for a
later controlled execution step.

This step does not change production code, `readme.txt`, Settings save logic,
GA4 client behavior, OpenAI client behavior, admin UI, JavaScript, CSS,
Composer/npm configuration, release packaging, or runtime behavior.

WordPress.org release remains `Hold`.

This document does not record real credentials, real API keys, real access
tokens, real Authorization headers, real request or response bodies, real AI
payload bodies, real generated report bodies, real analytics values, real
hostname/domain values, real GA4 property identifiers, or real page
path/source/city values.

## Scope

The later external API error-path QA execution should cover the following
areas, using the safety rules in this document:

- GA4 Fetch action.
- OpenAI Generate action.
- Settings saved-state prerequisites.
- Missing credential category.
- Invalid / expired credential category.
- Permission / property access failure category.
- Network / timeout category.
- Rate limit / quota category.
- Provider-side validation error category.
- Empty / no-data response category.
- Safe user-facing error display.
- Safe admin notice behavior.
- No raw body disclosure behavior.
- No credential disclosure behavior.

This plan covers two service groups:

- Google Analytics Data API error paths.
- OpenAI API error paths.

Out of scope for this planning step:

- Executing GA4 Fetch.
- Executing OpenAI Generate.
- Performing real external API failure tests.
- Inspecting credential-bearing option values.
- Entering or changing real credentials.
- Capturing request or response bodies.
- Capturing browser Network tab headers, bodies, cookies, or sessions.
- Recording Payload Preview JSON.
- Recording generated report bodies.
- Declaring release readiness.

## Pre-execution Safety Rules

Before any later QA execution step starts, the operator should accept and apply
these safety rules:

- Do not run `wp option get` or similar commands that display plugin settings
  option values.
- Do not display, confirm, copy, paste, screenshot, or record credential values
  or credential fragments.
- Do not record browser Network tab headers, bodies, cookies, sessions, full
  sensitive URLs, or authorization data.
- Do not record request bodies.
- Do not record response bodies.
- Do not record Payload Preview JSON.
- Do not record generated report bodies.
- Do not record analytics values, page path rows, source rows, city rows,
  hostname/domain values, or GA4 property identifiers.
- Screenshots should be replaced by status-level written evidence whenever
  possible.
- If a screenshot is unavoidable, it must be fully redacted before being added
  to any note or document.
- Evidence should be limited to normalized error category, action name, screen
  name, scenario ID, pass/fail/block/not-tested status, and safe HTTP status
  code when applicable.
- If a required observation would reveal prohibited evidence, stop the scenario
  and mark it `Blocked`.
- If a scenario requires real credentials or real failure-triggering changes,
  obtain human approval in the execution step before proceeding.

## QA Scenario Matrix

All scenarios in this matrix are `Not executed` in Step 87.

### GA4 Scenarios

| Scenario ID | Service | Action | Precondition category | Trigger method category | Expected user-facing behavior | Allowed evidence | Prohibited evidence | Pass criteria | Risk notes | Execution status |
|---|---|---|---|---|---|---|---|---|---|---|
| GA4-01 | Google Analytics Data API | GA4 Fetch | Missing Google credential category | Controlled missing saved-state setup in later approved execution | Safe notice asks for Google credential category without showing values. | Scenario ID, action, screen, normalized missing-credential category. | Option values, token values/fragments, headers, request/response bodies. | Notice is safe; no credential value or raw body appears. | Settings manipulation must avoid option dumps. | Not executed |
| GA4-02 | Google Analytics Data API | GA4 Fetch | Invalid / expired Google credential category | Controlled invalid/expired credential condition in later approved execution | Safe authorization error category appears. | Scenario ID, safe status code if available, normalized invalid/expired category. | Token value/fragment, Authorization header, raw GA4 body. | Error is understandable and redacted. | Requires controlled credential strategy and approval. | Not executed |
| GA4-03 | Google Analytics Data API | GA4 Fetch | Insufficient permission / property access failure category | Controlled property-access failure in later approved execution | Safe permission/property access error appears. | Scenario ID, action, normalized permission category. | Property ID value, token, headers, raw response body. | User-facing error does not expose identifiers or raw response. | Must avoid recording real property identifiers. | Not executed |
| GA4-04 | Google Analytics Data API | GA4 Fetch | Invalid GA4 Property ID category | Controlled invalid property identifier category in later approved execution | Safe invalid-property or validation error appears. | Scenario ID, normalized invalid-property category. | Real property ID, full endpoint URL, raw response. | Error is safe and no false success state appears. | Synthetic or placeholder identifiers only in notes. | Not executed |
| GA4-05 | Google Analytics Data API | GA4 Fetch | Network timeout / connection failure category | Controlled network/timeout condition in later approved execution | Safe timeout/network error appears. | Scenario ID, action, safe status or timeout category. | Network export, endpoint details, headers, request/response bodies. | Error is safe and payload preview is not treated as valid success. | Network tooling must not record sensitive panels. | Not executed |
| GA4-06 | Google Analytics Data API | GA4 Fetch | Rate limit / quota category | Controlled rate-limit/quota condition in later approved execution | Safe quota/rate-limit error appears. | Scenario ID, normalized quota or rate-limit category. | Raw provider message containing identifiers, headers, bodies. | Notice is normalized and redacted. | Avoid excessive API usage. | Not executed |
| GA4-07 | Google Analytics Data API | GA4 Fetch | Empty / no-data response category | Controlled no-data report scope in later approved execution | Empty/no-data state is clear and not reported as full success. | Scenario ID, empty/no-data category, action, screen. | Analytics values, paths, sources, cities, hostnames. | Empty state is safe and OpenAI remains unavailable or safely blocked if payload invalid. | No real table values may be recorded. | Not executed |
| GA4-08 | Google Analytics Data API | GA4 Fetch | Provider validation error category | Controlled provider validation failure in later approved execution | Safe provider-validation error appears. | Scenario ID, normalized validation category. | Raw response, request body, endpoint URL with identifiers. | Error is safe and does not expose raw provider body. | Requires careful trigger design. | Not executed |
| GA4-09 | Google Analytics Data API | GA4 Fetch | Safe handling of GA4 raw response body category | Any GA4 error-path execution in later approved step | Raw GA4 response body is not displayed in UI or recorded as evidence. | Pass/fail status for raw-body exposure check. | Raw GA4 response body, full terminal/browser output containing body. | No raw body appears in admin notice, docs, screenshots, or terminal notes. | Stop immediately if raw body is visible. | Not executed |
| GA4-10 | Google Analytics Data API | GA4 Fetch | No credential leakage in user-facing error | Any GA4 credential/error-path execution in later approved step | User-facing error contains no token, key, header, or fragment. | Pass/fail status for credential leakage check. | Credential values/fragments, Authorization header, option values. | No credential material appears in UI, logs, console notes, screenshots, or docs. | Stop immediately if credential material appears. | Not executed |

### OpenAI Scenarios

| Scenario ID | Service | Action | Precondition category | Trigger method category | Expected user-facing behavior | Allowed evidence | Prohibited evidence | Pass criteria | Risk notes | Execution status |
|---|---|---|---|---|---|---|---|---|---|---|
| OAI-01 | OpenAI API | OpenAI Generate | Missing OpenAI API Key category | Controlled missing saved-state setup in later approved execution | Safe notice asks for OpenAI credential category without showing values. | Scenario ID, action, screen, normalized missing-credential category. | Option values, key values/fragments, headers, request/response bodies. | Notice is safe; no key value or raw body appears. | Settings manipulation must avoid option dumps. | Not executed |
| OAI-02 | OpenAI API | OpenAI Generate | Invalid OpenAI API Key category | Controlled invalid key condition in later approved execution | Safe authentication error category appears. | Scenario ID, safe status code if available, normalized invalid-key category. | API key value/fragment, Authorization header, raw OpenAI body. | Error is understandable and redacted. | Requires controlled credential strategy and approval. | Not executed |
| OAI-03 | OpenAI API | OpenAI Generate | Quota / billing / rate limit category | Controlled quota/rate-limit condition in later approved execution | Safe quota, billing, or rate-limit category appears. | Scenario ID, normalized quota/billing/rate-limit category. | Raw provider message, request body, response body, headers. | Notice is safe and generated report is not shown as success. | Avoid excessive API usage. | Not executed |
| OAI-04 | OpenAI API | OpenAI Generate | Model / provider validation error category | Controlled provider validation condition in later approved execution | Safe model/provider validation error appears. | Scenario ID, normalized provider-validation category. | OpenAI request body, payload body, raw response. | Error is safe and does not expose request or payload body. | Trigger method must be approved before execution. | Not executed |
| OAI-05 | OpenAI API | OpenAI Generate | Network timeout / connection failure category | Controlled network/timeout condition in later approved execution | Safe timeout/network error appears. | Scenario ID, action, safe status or timeout category. | Network export, endpoint details, headers, request/response bodies. | Error is safe and textarea is not falsely shown as success. | Network tooling must not record sensitive panels. | Not executed |
| OAI-06 | OpenAI API | OpenAI Generate | Malformed or unavailable payload category | Controlled missing/expired/invalid payload state in later approved execution | Generate is blocked before OpenAI request or shows safe payload-state error. | Scenario ID, payload-state category, action, screen. | Payload body, transient value/key, request body, generated report body. | OpenAI request is not made when valid payload is unavailable. | Prefer local state testing before external API testing. | Not executed |
| OAI-07 | OpenAI API | OpenAI Generate | Provider-side refusal / safety category if applicable | Controlled refusal/safety category in later approved execution | UI handles provider-side refusal/safety category without raw body exposure. | Scenario ID, normalized refusal/safety category if applicable. | Raw provider response, generated body, request body, payload JSON. | Result is not misrepresented as ordinary success. | Applicability may depend on provider behavior and policy. | Not executed |
| OAI-08 | OpenAI API | OpenAI Generate | Safe handling of OpenAI raw response body category | Any OpenAI error-path execution in later approved step | Raw OpenAI response body is not displayed in UI or recorded as evidence. | Pass/fail status for raw-body exposure check. | Raw OpenAI response body, full terminal/browser output containing body. | No raw body appears in admin notice, docs, screenshots, or terminal notes. | Stop immediately if raw body is visible. | Not executed |
| OAI-09 | OpenAI API | OpenAI Generate | Generated report body not shown on failed generation | Any OpenAI failed generation in later approved step | Generated report textarea is not falsely populated as success. | Pass/fail status for textarea success/failure state only. | Generated report body, copied report text, user-edited report text. | Failure state is clear and no generated body is recorded. | Do not screenshot report body. | Not executed |
| OAI-10 | OpenAI API | OpenAI Generate | No credential leakage in user-facing error | Any OpenAI credential/error-path execution in later approved step | User-facing error contains no key, header, or fragment. | Pass/fail status for credential leakage check. | Credential values/fragments, Authorization header, option values. | No credential material appears in UI, logs, console notes, screenshots, or docs. | Stop immediately if credential material appears. | Not executed |

## Safe Evidence Template

Use this template in a later controlled QA execution step. Keep all notes
status-level only and do not fill it with real identifiers, credentials,
request bodies, response bodies, payload bodies, analytics values, or generated
report bodies.

```text
Scenario ID:
Execution date:
Environment category:
Action:
Result: Pass / Fail / Blocked / Not tested
Observed normalized error category:
Safe HTTP status code, if applicable:
User-facing message safety: Pass / Fail / Not applicable
Credential leakage check: Pass / Fail / Not applicable
Raw body exposure check: Pass / Fail / Not applicable
Screenshot used: No / Redacted only
Notes: status-level only
```

Allowed example style:

```text
Scenario ID: GA4-XX
Result: Blocked
Observed normalized error category: permission category
Notes: status-level only; no credential, body, payload, analytics value, or
generated report content recorded.
```

Do not replace placeholders with real credential, identifier, payload,
analytics, or generated report values.

## Prohibited Evidence Examples

The following evidence categories must not be captured or recorded during the
later execution step:

- Authorization header.
- API key value or fragment.
- Access token value or fragment.
- Full endpoint URL containing identifiers or sensitive query values.
- Request body.
- Raw response body.
- Browser Network tab export.
- Payload Preview JSON.
- Generated report text.
- `wp_options` output.
- Plugin settings serialized value.
- Browser cookie, session, or nonce value.
- Unredacted screenshot.
- Analytics table values.
- Page path rows.
- Traffic source rows.
- City rows.
- GA4 Property ID real value.
- Hostname/domain real value.

## Execution Prerequisites and Blockers

Before any controlled external API error-path QA execution, confirm the
following prerequisites:

| Prerequisite / Blocker | Required State Before Execution | Notes |
|---|---|---|
| Safe test environment confirmation | Required | Use a non-production environment and document only environment category, not sensitive site identifiers. |
| No production/customer data use | Required | Do not use production/customer analytics values, report bodies, paths, source rows, or city rows as evidence. |
| Safe synthetic or controlled credentials strategy | Required | Human-approved strategy is needed before any credential-related failure test. |
| Permission to intentionally trigger failure categories | Required | Failure triggers may involve external calls or settings changes and need explicit approval. |
| Evidence redaction policy accepted | Required | Step 86 rules should be accepted before execution. |
| Scenario-specific test data strategy | Required | Decide how each scenario will be triggered without recording sensitive data. |
| No `wp_options` value dumps | Required | Do not display plugin settings option values. |
| External API call permission | Human decision required | Step 87 does not grant permission to make GA4/OpenAI calls. |
| Rollback / restore approach | Required | Any settings changed during later QA need a safe restore plan that does not record secret values. |
| Human approval before real error-path execution | Required | Step 88 or any execution step should begin only after explicit approval. |

If these prerequisites cannot be met, mark the affected scenario `Blocked`
rather than improvising.

## Relationship To Previous QA Docs

| Previous Doc | Relationship |
|---|---|
| Step 75 error handling QA checklist | Step 75 defined the broad error-handling checklist and stop conditions. Step 87 narrows the external API portion into an execution plan. |
| Step 76 error handling QA phase1 results | Step 76 confirmed local validation, Settings validation, nonce/capability handling, and evidence safety without external API calls. Step 87 preserves those safety boundaries for later external API QA. |
| Step 77 external API error-path QA checklist | Step 77 listed deferred GA4/OpenAI error-path checks. Step 87 turns those unexecuted items into a controlled scenario matrix with allowed/prohibited evidence. |
| Step 82 external services / privacy disclosure draft | Step 82 identified external service data categories and release wording drafts. Step 87 keeps evidence scoped so QA does not record the sensitive categories identified there. |
| Step 86 support/debug redaction policy consolidation | Step 86 consolidated never-share and allowed-evidence categories. Step 87 applies those rules directly to future GA4/OpenAI error-path execution. |

## Release Blockers / Follow-up Decisions

| Blocker / Decision Item | Status After Step 87 | Notes |
|---|---|---|
| External API error-path QA not executed | Hold | This step creates the execution plan only. |
| Support/debug redaction guidance not final | Needs review | Step 86 and Step 87 are draft planning material. |
| AI Payload Preview JSON visibility not final | Hold | Step 87 prohibits recording JSON evidence but does not settle the UI visibility decision. |
| Generated report handling policy not final | Needs human decision | Step 87 prohibits recording report bodies but does not settle final handling policy. |
| AI payload category acceptance not final | Hold | Sensitive payload category acceptance still needs human decision. |
| External services / privacy wording not release-finalized | Hold | Draft wording still needs review before release-facing use. |
| OAuth / token lifecycle strategy unresolved | Hold | Manual Google Access Token entry remains developer-verification oriented. |
| OpenAI API Key storage strategy unresolved | Hold | Settings-based key storage needs explicit acceptance or redesign. |
| Uninstall credential cleanup policy unresolved | Hold | Credential-bearing settings cleanup still needs a release decision. |
| Plugin Check / PHPCS refresh not executed | Needs review | Tooling refresh remains later release-readiness work. |
| Release package contents not reviewed | Needs review | Package contents and secret/data scan remain later work. |
| WordPress.org release remains Hold | Hold | Release readiness should not proceed until blockers are closed or explicitly deferred. |

## Recommended Next Step

Recommended next step:

```text
Step 88: External API error-path QA controlled execution
```

Step 88 may require real external API calls, controlled settings changes, and
intentional failure triggers. It should not begin without explicit human
approval for:

- Whether GA4/OpenAI external API calls are allowed.
- Which credential strategy may be used.
- Which scenarios are safe to execute.
- How settings will be restored without recording credential values.
- Which evidence template and redaction rules are accepted.

If approval is not granted, Step 88 should remain blocked or be replaced by a
docs-only human decision checkpoint.

## Outcome

- External API error-path QA execution plan: documented.
- GA4 scenario matrix: documented; all scenarios `Not executed`.
- OpenAI scenario matrix: documented; all scenarios `Not executed`.
- Safe evidence template: documented.
- Prohibited evidence categories: documented.
- Execution prerequisites and blockers: documented.
- Relationship to previous QA docs: documented.
- Release blockers and follow-up decisions: documented.
- Production code changed: no.
- `readme.txt` changed: no.
- Admin UI, JavaScript, and CSS changed: no.
- External API calls performed: no.
- Real credentials, identifiers, analytics values, page paths, traffic source
  values, city values, request bodies, payload bodies, raw responses, generated
  reports, nonces, cookies, and sessions recorded: no.
- WordPress.org release position: `Hold`.
