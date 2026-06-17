# Step 156: Missing-Credential UI Path Decision Checkpoint

## Step Summary

Step 156 is a docs-only and planning-only decision checkpoint for the remaining
missing-credential UI path gap after the Step 152 GA4 credential source
implementation.

Step 155 confirmed that the relevant admin pages load, the credential source
label is shown as a safe category, and credential values remain hidden. Because
GA4 Fetch was intentionally not triggered, missing-credential behavior during
the GA4 Fetch path remains `not_observed`.

Result classification: `Decision checkpoint completed`

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step155-human-controlled-credential-source-admin-smoke-results.md`
- `docs/maturation/step154-controlled-credential-source-admin-smoke-plan.md`
- `docs/maturation/step153-ga4-oauth-credential-source-source-level-verification-results.md`
- `docs/maturation/step152-narrow-ga4-oauth-credential-source-production-implementation-results.md`
- `docs/maturation/step151-ga4-oauth-token-integration-implementation-boundary.md`
- `docs/maturation/step150-ga4-oauth-token-integration-boundary.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`

## Background

Status-level background:

- Step 152 added a GA4 credential source resolver in production PHP.
- Step 153 verified the resolver and GA4 Fetch credential source path at source
  level.
- Step 155 confirmed that the Settings and Report Builder admin pages loaded
  without a visible fatal error.
- Step 155 confirmed that credential-related UI remained value-hidden.
- Step 155 observed the safe credential source label category:
  `credential_source_missing`.
- Step 155 intentionally did not trigger GA4 Fetch, so missing-credential
  behavior during the fetch path remains `not_observed`.

No option values, credential values, Authorization headers, request bodies, raw
responses, payload JSON, generated report bodies, analytics values, screenshots,
browser Network evidence, or database rows were used for this checkpoint.

## Decision Question

Decision question:

```text
Should the missing-credential UI path be verified next, and if so, can it be
verified without external communication or credential value inspection?
```

Sub-questions:

- Should verification continue without clicking GA4 Fetch?
- Should verification remain source-level only?
- Should a controlled local-only execution be planned with safe stubs / guards
  so the missing credential category can be observed without a real GA4 request?
- Should a real GA4 Fetch be used now?

The immediate next step must not include real GA4 Fetch, OpenAI Generate,
browser OAuth, token endpoint communication, option value inspection, database
dumps, screenshots, or Network evidence.

## Candidate Options

| Option | Description | Benefits | Risks / Gaps | Decision |
|---|---|---|---|---|
| A | Human browser only; continue not clicking GA4 Fetch and leave missing-credential UI path unverified. | Preserves the Step 155 safety posture. | Does not close the `not_observed` gap. | Not recommended as the next checkpoint. |
| B | Source-level review only of the missing credential branch and admin notice/error rendering. | Very safe and no external communication. | Step 153 already covered much of the source-level path; still may not observe rendered admin behavior. | Acceptable fallback. |
| C | Local-only controlled execution plan using safe stubs / guards, with no real GA4 request. | Best balances safety and closing the observation gap. Can plan how to observe the missing credential error category without external communication. | Requires careful guard design before any execution. | Recommended. |
| D | Real GA4 Fetch verification. | Would exercise the live fetch path. | Violates the immediate safety posture and risks external communication and credential handling. | Hold / not recommended. |

## Recommended Option

Recommended option: `Option C`

Recommended next action is not execution yet. The next step should be a
planning step for controlled local-only verification.

Rationale:

- Step 155 already confirmed page load, safe status labels, and value-hidden
  posture.
- The remaining gap is the missing-credential UI behavior that would normally
  be reached through the GA4 Fetch path.
- A real GA4 Fetch should not be the immediate next step.
- A safe local-only plan can define guards, stubs, expected categories, and
  forbidden evidence boundaries before any controlled execution occurs.
- Source-only review remains a safe fallback, but it is less likely to close the
  UI observation gap because Step 153 already verified the source path.

## Safety Boundaries For Future Execution

Any future verification must keep these boundaries:

- no real GA4 request,
- no OpenAI request,
- no OAuth request,
- no token endpoint communication,
- no credential value inspection,
- no option value inspection,
- no `wp option get analytics_report_ai_oauth_tokens`,
- no database dump,
- no browser DevTools / Network evidence,
- no screenshots,
- no raw request recording,
- no raw response recording,
- no AI payload JSON recording,
- no generated report body recording,
- status-level categories only.

Allowed future evidence should be limited to:

- page/action status category,
- missing credential error category,
- safe admin notice category,
- visible fatal error yes/no,
- forbidden evidence recorded yes/no,
- external request attempted yes/no.

## Proposed Step 157

Recommended Step 157:

```text
Step 157: Missing credential UI path controlled local-only execution plan
```

Step 157 should remain planning-only and should not change production code. It
should define how a future controlled check could verify the missing credential
UI path without a real GA4 request and without credential or option value
inspection.

Step 157 should include:

- exact local-only guard requirements,
- how to prevent real external requests,
- what status-level observation template to use,
- what admin UI path is in scope,
- what evidence remains forbidden,
- fallback to source-level-only review if safe local-only execution cannot be
  guaranteed.

Real GA4 Fetch, OpenAI Generate, browser OAuth, token endpoint communication,
refresh, reconnect, revoke, and uninstall cleanup should remain out of scope
unless explicitly requested later.

## Explicit Non-Goals

Step 156 does not perform:

- production PHP changes,
- JavaScript, CSS, `readme.txt`, or tools changes,
- source changes of any kind,
- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- token exchange,
- token storage real data confirmation,
- refresh,
- revoke,
- browser DevTools / Network evidence,
- screenshots,
- `wp option get analytics_report_ai_oauth_tokens`,
- database dump,
- plugin settings option value display,
- OAuth token option value display,
- serialized option value display,
- credential, API key, access token, refresh token, or Authorization header
  display,
- OAuth client ID or client secret value display,
- GA4 Property ID, hostname/domain, analytics values, page path/source/city
  display,
- request body, GA4 raw response, AI payload JSON, OpenAI raw response, or
  generated report body display.

## Evidence Safety

This document records status-level planning only. It does not record:

- OAuth token option values,
- serialized option values,
- plugin settings option values,
- manual Google Access Token values,
- access tokens,
- refresh tokens,
- Authorization headers,
- OAuth client ID values,
- OAuth client secret values,
- token endpoint requests or responses,
- authorization codes,
- callback URLs,
- query strings,
- raw state values,
- raw provider errors,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- page path/source/city values,
- request bodies,
- GA4 raw responses,
- AI payload JSON,
- OpenAI raw responses,
- generated report bodies,
- screenshots,
- browser Network evidence,
- database rows or database dumps,
- email addresses or Google account identifiers,
- project IDs or project identifiers.

## Commands Executed

Safe repository checks:

```bash
git status --short --untracked-files=all
test -f docs/maturation/step156-missing-credential-ui-path-decision-checkpoint.md && echo "step156_doc_exists"
git diff -- docs/maturation/step156-missing-credential-ui-path-decision-checkpoint.md
git diff --name-only
```

Command result summary:

- Step 156 docs file exists.
- Production code was not changed.
- `git diff --name-only` does not include untracked docs until they are staged;
  use `git status --short --untracked-files=all` for the full working tree
  view.
