# Step 94: Release-readiness Blocker Priority Decision

## Step Summary

This document records the Step 94 release-readiness blocker priority decision
checkpoint for Analytics Report AI.

The purpose is to decide the next release-readiness blocker to close after the
Step 91 no-data handling implementation, Step 92.10 synthetic browser smoke,
and Step 93 external API error-path recheck.

This is a docs-only decision checkpoint. It does not change production PHP,
JavaScript, CSS, `readme.txt`, Settings save logic, GA4 client behavior,
OpenAI client behavior, credential storage, OAuth behavior, release package
files, or WordPress.org metadata.

Plugin Check was not executed. No external API communication was performed.
No real credentials, API keys, access tokens, Authorization headers, plugin
settings option values, GA4 property identifiers, hostname/domain values,
analytics values, page path/source/city values, request bodies, AI payload JSON,
OpenAI request bodies, raw GA4/OpenAI responses, generated report bodies,
screenshots, browser Network tab captures, cookies, sessions, or nonces are
recorded in this document.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`
- `docs/maturation/step92-10-synthetic-browser-smoke-results.md`
- `docs/maturation/step93-external-api-error-path-recheck-results.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`

## Current State After Step 93

Status-level checkpoints completed:

- Step 91 implemented granular no-data handling and server-side generation
  gating.
- Step 92.10 confirmed the remaining synthetic browser no-data warning
  scenarios at status level.
- Step 93 rechecked external API error paths with synthetic HTTP interception.
- Step 93 rechecked the previous GA4 no-data failure and recorded `GA4-07` as
  `Pass`.
- Step 93 confirmed blocked payloads do not call OpenAI and warning payloads
  can reach only a locally intercepted OpenAI path.

These results reduce the no-data and external-error blockers, but they do not
settle release-readiness decisions around data visibility, support evidence,
credential lifecycle, Plugin Check, or packaging.

## Remaining Blockers From Step 93

| Blocker | Current status | Notes |
|---|---|---|
| Plugin Check isolated run in `wp-dev-check` | Open | Must be run only in the isolated Plugin Check environment when explicitly scoped. |
| Payload Preview JSON visibility decision | Open | Needs final decision before public support/privacy wording can be considered stable. |
| Generated report handling policy decision | Open | Needs final decision for support evidence, screenshots, and generated body sharing. |
| Privacy/support wording finalization | Open | Depends on visibility decisions for payload JSON and generated report body. |
| Google OAuth and token lifecycle | Open | Manual token flow remains developer-verification oriented and not public-release ready. |
| OpenAI API key storage | Open | Settings-based storage remains a release decision and should align with credential strategy. |
| Uninstall credential cleanup | Open | Cleanup policy for credential-bearing settings remains undecided. |
| Release package review | Open | Should happen after policy and implementation blockers are closed or explicitly deferred. |

## Blocker Classification

| Blocker | Classification | Rationale |
|---|---|---|
| Plugin Check isolated run in `wp-dev-check` | Verification blocker | It is a required readiness signal, but primarily checks implementation/package conformance. |
| Payload Preview JSON visibility decision | Data visibility / privacy blocker | The preview can expose high-sensitivity report context and affects admin UI, support evidence, and privacy wording. |
| Generated report handling policy decision | Data visibility / privacy blocker | Generated text can be business-sensitive and needs a support/screenshot/sharing policy. |
| Privacy/support wording finalization | Data visibility / privacy blocker | Public/admin/support language depends on the final payload and generated-report visibility boundaries. |
| Google OAuth and token lifecycle | Credential lifecycle blocker | Manual access-token entry, expiry, scope, reconnect, and revocation are not public-release ready. |
| OpenAI API key storage | Credential lifecycle blocker | API key persistence needs a final strategy before public release. |
| Uninstall credential cleanup | Credential lifecycle blocker | Credential-bearing settings need an explicit cleanup decision. |
| Release package review | Release packaging blocker | Package contents should be reviewed after policy and runtime blockers are resolved or deferred. |

## Recommended Priority

| Priority | Blocker | Recommended next action |
|---:|---|---|
| 1 | Payload Preview JSON visibility decision | Decide final MVP/public-release posture for raw JSON visibility. |
| 2 | Generated report handling policy decision | Decide persistence, sharing, screenshot, and support-evidence boundaries for generated report bodies. |
| 3 | Privacy/support wording finalization | Align support/redaction/privacy wording with the chosen visibility policies. |
| 4 | Isolated Plugin Check run in `wp-dev-check` | Run only after data visibility and wording decisions are stable enough for meaningful findings. |
| 5 | Google OAuth / token lifecycle | Decide or plan the public authentication model after UI/data boundaries are fixed. |
| 6 | OpenAI API key storage | Decide storage/configuration posture in coordination with broader credential lifecycle work. |
| 7 | Uninstall credential cleanup | Decide cleanup behavior for credential-bearing settings. |
| 8 | Release package review | Review final distribution contents after decisions and verification blockers are closed or deferred. |

## Decision Rationale

Plugin Check is important, but it is mostly a mechanical verification step at
this point. Running it before the data visibility and privacy boundaries are
settled would not produce a final readiness judgment because the plugin could
still need admin UI, help text, readme, or policy changes afterward.

Payload Preview JSON visibility should be closed first because it affects:

- what the admin UI is allowed to show by default,
- whether support/debug policy can prohibit JSON screenshots or copied
  diagnostics cleanly,
- whether privacy wording needs to disclose raw payload visibility,
- how generated report handling should be framed,
- how future QA evidence should be collected without recording sensitive data.

Generated report handling depends on the same data visibility boundary. The
generated report body is not persisted as reviewed in the current MVP flow, but
it can still be sensitive when displayed, copied, screenshotted, or pasted into
support channels.

Privacy/support wording should follow the two visibility decisions. Final
wording should not be frozen while Payload Preview JSON visibility and
generated report handling remain open.

Credential lifecycle remains a major public-release blocker, especially Google
OAuth/token lifecycle and OpenAI API key storage. However, the recommended
order is to first close the UI/data visibility boundaries, because those
boundaries inform the support/redaction posture that credential work will also
need to follow.

Release package review should happen late. Package contents can be checked
more confidently after the policy, wording, credential, and verification
decisions are either implemented or explicitly deferred.

## Decision

Adopt the following next step:

```text
Step 95: Payload Preview JSON visibility final decision
```

In the existing candidate naming, this means adopting `Step 94B` as the next
work item.

The Step 95 decision should stay docs-first unless an explicit implementation
scope is approved afterward.

## Explicit Non-goals For Step 94

This step does not:

- change production code,
- change PHP, JavaScript, or CSS,
- change `readme.txt`,
- change admin UI behavior,
- change Settings save logic,
- change GA4 client behavior,
- change OpenAI client behavior,
- change credential storage,
- implement OAuth,
- run Plugin Check,
- touch `wp-dev-check`,
- run GA4 Fetch,
- run OpenAI Generate,
- call external APIs,
- inspect or display plugin settings option values,
- record raw payloads,
- record raw responses,
- record generated report bodies,
- capture screenshots,
- inspect browser Network tab data.

## Security / Evidence Notes

This decision checkpoint follows the Step 86 redaction policy. It records only
status-level blocker names, classifications, rationale, and next-step
decisions.

Do not use this document as permission to run real provider tests, inspect
credentials, dump options, capture payload JSON, capture generated report text,
or execute Plugin Check outside an explicitly scoped isolated step.

## Next Step Recommendation

Proceed with:

```text
Step 95: Payload Preview JSON visibility final decision
```

Suggested Step 95 focus:

- Decide whether raw JSON remains visible, becomes hidden behind an explicit
  disclosure/debug control, is removed from normal admin view, or is replaced
  by safer structured summaries.
- Confirm support/debug evidence rules for Payload Preview.
- Confirm whether any later implementation change is needed.
- Keep external API communication, credentials, raw payload bodies, raw
  responses, generated report bodies, screenshots, cookies, sessions, and
  nonces out of the record.

WordPress.org release remains `Hold`.
