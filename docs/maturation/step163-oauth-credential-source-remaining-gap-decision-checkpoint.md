# Step 163: OAuth Credential Source Remaining-Gap Decision Checkpoint

## Step Purpose

Step 163 is a docs-only and planning-only decision checkpoint for the remaining
GA4 OAuth credential source maturation gaps.

The purpose is to organize the next safe maturation order before any further
implementation, browser action, external communication, token endpoint action,
or credential value inspection.

Result classification: `Decision checkpoint completed`

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step162-admin-smoke-instruction-slug-alignment-docs-update-results.md`
- `docs/maturation/step161-admin-page-slug-alignment-source-level-review-results.md`
- `docs/maturation/step160-local-http-hard-block-guard-cleanup-results.md`
- `docs/maturation/step159-missing-credential-ui-path-controlled-local-only-execution-results.md`
- `docs/maturation/step158-missing-credential-ui-path-local-http-guard-preparation-results.md`
- `docs/maturation/step155-human-controlled-credential-source-admin-smoke-results.md`
- `docs/maturation/step153-ga4-oauth-credential-source-source-level-verification-results.md`
- `docs/maturation/step152-narrow-ga4-oauth-credential-source-production-implementation-results.md`
- `docs/maturation/step151-ga4-oauth-token-integration-implementation-boundary.md`
- `docs/maturation/step150-ga4-oauth-token-integration-boundary.md`
- `docs/maturation/step149-human-controlled-oauth-token-option-cleanup-execution-results.md`
- `docs/maturation/step147-human-controlled-oauth-token-exchange-smoke-results.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`

## Execution Boundary

Step 163 is docs-only, planning-only, and limited to status-level source/docs
alignment.

This step did not perform:

- production PHP changes,
- JavaScript, CSS, `readme.txt`, or tools changes,
- admin UI or browser operations,
- temporary guard / mu-plugin / helper / test script creation,
- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- browser DevTools / Network evidence collection,
- screenshot recording,
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

Evidence is limited to status-level categories and document references.

## Completed Scope Summary

Status-level completed scope from Step 152 through Step 162:

| Step | Status-level summary |
|---|---|
| Step 152 | GA4 credential source resolver was added to production PHP with precedence for usable OAuth token option, manual Google Access Token fallback during MVP maturation, and missing credential category. The GA4 client token handoff is request-local runtime only. |
| Step 153 | Source-level verification confirmed resolver definition, call site, precedence, request-local token flow, and redaction boundary. |
| Step 155 | Human admin smoke confirmed Settings and Report Builder page load, safe credential source label posture, and value-hidden posture. |
| Step 159 | A human-controlled local-only guarded GA4 Fetch observed the missing credential path as `missing_google_credential_category`; external request attempted: No; fatal error observed: No. |
| Step 160 | Temporary local HTTP hard-block guard cleanup was completed. |
| Step 161 | Source-level review confirmed the admin page slugs and classified the previous slug mismatch as a smoke instruction/docs alignment finding. |
| Step 162 | Future admin smoke instructions were aligned to the source-confirmed canonical slugs. |

No token values, option values, credential values, request bodies, raw
responses, payloads, generated reports, screenshots, or browser Network
evidence are recorded in this summary.

## Remaining Gap Inventory

| Gap | Current status | Risk | Suggested handling | Immediate next step suitability |
|---|---|---|---|---|
| OAuth refresh behavior | Not implemented or not confirmed at maturity level. | Expired-token recovery may be unclear, and token endpoint handling would increase credential lifecycle risk if handled too early. | Start with docs-only/source-level planning before any token endpoint execution. | Not suitable for immediate execution. |
| Reconnect behavior | Not fully specified for an existing-connection state. | Reconnect could overwrite or confuse existing credential state if UI/storage boundaries are unclear. | Plan UI state, storage behavior, and safety labels before implementation. | Not suitable for immediate execution. |
| Revoke / disconnect behavior | Not fully specified for UI, option cleanup, or provider-side expectations. | Disconnect can create false confidence if local cleanup and provider revocation expectations are not separated. | Plan local disconnect and provider revoke boundaries separately. | Not suitable for immediate execution. |
| Uninstall cleanup | Cleanup boundary for dedicated OAuth token option and plugin settings needs a WordPress.org-readiness decision. | Stored credential material could remain after uninstall if cleanup policy is incomplete. | Create an uninstall cleanup boundary plan after credential model direction is settled. | Suitable later as planning, not immediate execution. |
| Manual token fallback / retirement | MVP fallback remains part of the credential source model. | Public-release posture may be inconsistent if manual token and OAuth token paths remain equally prominent. | Decide whether manual token fallback remains MVP-only, becomes hidden/deprecated, or is retired before public release. | Suitable as immediate docs-only planning. |
| Credential source UI wording / status labels | Safe labels and value-hidden posture have been observed, but final wording/status taxonomy still needs alignment. | User-facing state could be confusing if OAuth/manual/missing states are not described consistently. | Align labels after the credential model direction is decided. | Suitable as near-term planning. |
| Canonical admin smoke slug usage | Step 162 completed canonical slug alignment. | Future smoke docs could drift if they do not cite Step 162. | Use Step 162 as the canonical reference in future admin smoke instructions. | Already ready for future use. |
| WordPress.org release readiness | Release remains `Hold`. | Credential lifecycle gaps remain larger than packaging/checklist completion. | Keep release readiness behind credential model, lifecycle, privacy/support, and package checks. | Not suitable for immediate release. |

## Candidate Maturity Tracks

| Track | Description | Strength | Risk / dependency |
|---|---|---|---|
| Track A: OAuth refresh / expired-token behavior planning | Define refresh and expired-token recovery behavior without calling the token endpoint. | Addresses long-lived OAuth usability. | Depends on credential model and lifecycle decisions. |
| Track B: Reconnect / disconnect / revoke behavior planning | Define reconnect, local disconnect, provider revoke expectations, and UI state transitions. | Clarifies connection lifecycle. | Depends on whether manual fallback remains visible or is retired. |
| Track C: Manual token fallback retirement decision | Decide whether the manual Google Access Token fallback remains, is deprecated, is hidden, or is retired before public release. | Settles the credential model before deeper lifecycle work. | May require later UI wording and Settings changes. |
| Track D: Uninstall cleanup boundary planning | Define uninstall cleanup boundaries for OAuth token option and plugin settings. | Directly supports WordPress.org readiness. | Should follow credential model and storage boundary decisions. |
| Track E: Credential source UI wording/status label alignment | Align safe labels, user-facing wording, missing/connected/manual/OAuth states, and value-hidden posture. | Improves admin clarity without needing external calls. | Best after Track C determines whether manual fallback remains. |
| Track F: Release readiness checkpoint | Reassess release blockers after credential lifecycle and UI wording are settled. | Keeps release decisions explicit. | Premature until credential lifecycle gaps are reduced. |

## Recommended Next Track

Recommended immediate next track:

```text
Track C: Manual token fallback retirement decision
```

Rationale:

- The current credential source model still includes manual Google Access Token
  fallback during MVP maturation.
- Before moving into refresh, reconnect, revoke, or uninstall lifecycle work,
  the project should decide whether manual token fallback remains available,
  becomes deprecated/hidden, or is retired before public release.
- This decision informs Credential source UI wording/status label alignment in
  Track E.
- This can be handled as docs-only and planning-only without external API
  communication or credential value inspection.

Immediate next step should not be:

- real GA4 Fetch,
- OpenAI Generate,
- OAuth token endpoint communication,
- Plugin Check,
- production code change.

## Safety Boundary For Future Steps

Future steps in this credential source maturity area should preserve these
boundaries unless a later step explicitly narrows and authorizes otherwise:

- no real token endpoint communication unless explicitly planned later,
- no real GA4 request unless explicitly planned later,
- no OpenAI request unless explicitly planned later,
- no OAuth Connect / Authorize unless explicitly planned later,
- no credential value inspection,
- no option value inspection,
- no Authorization header inspection,
- no browser Network evidence,
- no screenshots,
- no request body recording,
- no raw response recording,
- no AI payload JSON recording,
- no generated report body recording,
- status-level category only.

## Decision Output

```text
WordPress.org release remains: Hold
real GA4 Fetch immediate next step: No
OpenAI Generate immediate next step: No
OAuth token endpoint immediate next step: No
Plugin Check immediate next step: No
production code change immediate next step: No
recommended next track: Track C: Manual token fallback retirement decision
```

## Result Classification

Result: `Decision checkpoint completed`

Rationale:

- Completed credential source maturation steps were summarized at status level.
- Remaining gaps were grouped and prioritized.
- The immediate next track was kept docs-only/planning-only.
- No production code was changed.
- No external action or forbidden evidence collection occurred.

WordPress.org release remains `Hold`.

## Notes And Limitations

- This step does not inspect live option values.
- This step does not prove OAuth refresh, reconnect, revoke, disconnect, or
  uninstall cleanup behavior.
- This step does not validate GA4 Fetch, OpenAI Generate, Plugin Check, or
  release readiness.
- This step does not change UI wording or credential storage behavior.
- The manual token fallback retirement decision is still pending and should be
  handled in the next docs-only checkpoint.

## Recommended Step 164

Recommended Step 164:

```text
Step 164: Manual token fallback retirement decision checkpoint
```

Recommended scope:

- docs-only,
- planning-only,
- no production code change,
- no browser action,
- no external communication,
- no token endpoint communication,
- no credential or option value inspection,
- status-level category only.

Track E, `Credential source UI wording/status label alignment`, should follow
after the manual token fallback direction is clarified.

## Commands Executed

Safe docs-only commands:

```bash
git status --short --untracked-files=all
test -f docs/maturation/step162-admin-smoke-instruction-slug-alignment-docs-update-results.md && echo "step162_doc_exists"
test -f docs/maturation/step161-admin-page-slug-alignment-source-level-review-results.md && echo "step161_doc_exists"
test -f docs/maturation/step160-local-http-hard-block-guard-cleanup-results.md && echo "step160_doc_exists"
test -f docs/maturation/step159-missing-credential-ui-path-controlled-local-only-execution-results.md && echo "step159_doc_exists"
test -f docs/maturation/step163-oauth-credential-source-remaining-gap-decision-checkpoint.md && echo "step163_doc_exists"
git diff -- docs/maturation/step163-oauth-credential-source-remaining-gap-decision-checkpoint.md
git diff --name-only
git status --short --untracked-files=all
```

Command result summary:

- Required recent reference docs checked by file existence were present.
- Step 163 docs file exists.
- Production code was not changed.
- `git diff --name-only` does not include untracked docs until they are staged;
  use `git status --short --untracked-files=all` for the full working tree
  view.
