# Step 96: Generated Report Handling Policy Finalization

## Decision Summary

The generated report is the primary output of the Analytics Report AI MVP.
The admin UI should continue to let the user review, edit, and copy the
generated report after AI generation.

However, the generated report body must be treated as business-sensitive
content. The public-release posture is that the plugin should not persist the
generated report body, and support/debug processes should not request it as
evidence.

This is a docs-only policy finalization step. It does not change production
PHP, JavaScript, CSS, `readme.txt`, admin UI behavior, Settings save logic,
GA4 client behavior, OpenAI client behavior, credential storage, release
packaging, or WordPress.org metadata.

Plugin Check was not executed. No external API communication was performed.
`wp-dev-check` was not touched. GA4 Fetch and OpenAI Generate were not run.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step95-payload-preview-json-visibility-final-decision.md`
- `docs/maturation/step94-release-readiness-blocker-priority-decision.md`
- `docs/maturation/step93-external-api-error-path-recheck-results.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`

## Final Decision

Adopt the following generated report handling policy for public-release
readiness:

- The generated report body may be displayed in the normal admin UI after AI
  generation.
- The display purpose is review, editing, and copying by the user who generated
  the report.
- The plugin should not persist the generated report body in the WordPress
  database, options, post meta, transients, log files, or debug output.
- Generated report history, saved report management, and later re-display of
  past generated reports are not part of the MVP.
- Copying generated report text is an explicit user action.
- Support/debug processes should not request the generated report body.
- If screenshots are requested, they should be cropped or redacted so the
  generated report body is not visible.
- Pasting generated report bodies into issues, support tickets, chat, email,
  logs, or release-readiness docs should not be a normal diagnostic step.
- Any future generated-report storage or history feature remains on `Hold`
  until a separate step designs storage, deletion, privacy wording, user
  consent, export/delete behavior, and support boundaries.

The final posture is:

| Area | Decision |
|---|---|
| Admin UI display after generation | Allowed. |
| User review/edit/copy flow | Preserve as MVP value. |
| Plugin persistence of generated report body | Do not persist. |
| Generated report history / management | Not in MVP. |
| Copy action | User-initiated action. |
| Support/debug evidence | Do not request generated report body. |
| Screenshots | Crop or redact generated report body. |
| Future storage/history feature | Hold pending separate design. |

## Rationale

Generated reports can summarize GA4-derived analysis, site-specific context,
traffic patterns, regional trends, top page trends, comparison limitations, and
business interpretation. Even when no raw analytics table is shown, the prose
can still reveal sensitive business context.

User-visible generated output is still central to the MVP. The user needs to
review, edit, and copy the result in order to use the plugin. Removing admin UI
display would undermine the main product workflow.

The safer MVP boundary is therefore:

```text
Display for the user, but do not persist and do not request as support evidence.
```

Support/debug diagnosis does not need the generated report body. Diagnosis
should rely on status-level information such as generation status, warning
state, error category, generation allowed/blocked state, and redacted UI state.

Step 95 finalized that raw AI payload JSON should not be normal support
evidence. Step 96 applies the same data visibility posture to AI output: the
generated report body should not become normal support/debug evidence either.

This keeps the AI input and AI output policies consistent while preserving the
actual user workflow.

## Current MVP Posture

Current implementation alignment is not changed in Step 96.

If the current implementation displays a generated report after AI generation,
that behavior is not changed by this document. Step 96 records the policy
decision only.

Current posture:

| Area | Status |
|---|---|
| WordPress.org release | `Hold` |
| Generated report admin UI display | Allowed as user review/edit/copy flow. |
| Generated report plugin persistence | Public-release posture says no persistence. |
| Generated report support evidence | Do not request. |
| Implementation alignment | Not changed in Step 96. |
| Admin UI wording alignment | Follow-up needed. |
| Support/privacy wording alignment | Follow-up needed. |
| Plugin Check | Not run. |

A later step should decide whether admin UI wording, support wording, privacy
wording, and implementation alignment need changes to match this policy.

## Support / Debug Policy Impact

Support should not request:

- generated report body,
- raw AI payload JSON,
- OpenAI request body,
- raw GA4 response,
- raw OpenAI response,
- credentials,
- API keys,
- access tokens,
- Authorization headers,
- plugin settings option values,
- GA4 Property ID real values,
- hostname/domain real values,
- analytics values,
- page path / source / city values,
- cookies / sessions / nonces,
- browser Network tab data.

Support/debug diagnosis should rely on:

- status-level labels,
- warnings,
- generation allowed/blocked state,
- error code/category,
- no-data / zero-activity / partial-data / comparison availability state,
- redacted UI state,
- command success/failure summaries that do not include sensitive values.

If screenshots are used at all, they should exclude generated report body,
payload JSON, credentials, site-specific analytics data, browser Network tab
data, cookies, sessions, and nonces.

## Relationship To Step 95

Step 95 finalized that raw AI payload JSON should not remain visible in the
normal admin UI for public-release posture.

Step 96 complements Step 95 by finalizing that the generated report body can
remain visible to the user in the admin UI, but should not be persisted by the
plugin or requested as support/debug evidence.

Together, Step 95 and Step 96 define the main data visibility boundary:

| Data area | Public-release posture |
|---|---|
| AI input / payload preview | Structured, human-readable review; no normal raw JSON evidence. |
| AI output / generated report | User-visible for review/edit/copy; no plugin persistence and no support evidence body. |

These two decisions should guide later privacy wording, support wording, UI
alignment, and QA evidence rules.

## Implementation Follow-up

Step 96 intentionally does not change code.

Recommended next step:

```text
Step 97: Privacy/support wording finalization after payload/report visibility decisions
```

Reason:

- Step 95 and Step 96 finalize the visibility policy for AI input and AI
  output.
- Privacy/support wording can now be aligned against both decisions.
- Plugin Check should still wait until wording and policy alignment are stable.

Possible later implementation planning:

- Payload Preview UI alignment plan.
- Generated report admin wording alignment.
- Support/debug wording updates.
- Privacy/readme wording updates if explicitly scoped.
- Verification pass after wording/UI alignment.

## Explicit Non-goals

This step does not:

- change production code,
- change PHP, JavaScript, or CSS,
- change `readme.txt`,
- change admin UI behavior,
- change Settings save logic,
- change GA4 client behavior,
- change OpenAI client behavior,
- change credential storage,
- run Plugin Check,
- touch `wp-dev-check`,
- call external APIs,
- run GA4 Fetch,
- run OpenAI Generate,
- inspect or display plugin settings option values,
- inspect or display credentials,
- record raw payloads,
- record raw request bodies,
- record raw response bodies,
- record generated report bodies,
- capture screenshots,
- inspect browser Network tab data.

## Security / Evidence Notes

This document records only status-level policy decisions.

It does not record real credentials, API keys, access tokens, Authorization
headers, plugin settings option values, GA4 Property IDs, hostname/domain
values, analytics values, page paths, traffic sources, city values, request
bodies, AI payload JSON, OpenAI request bodies, raw GA4/OpenAI response bodies,
generated report bodies, screenshots, browser Network tab data, cookies,
sessions, or nonces.

## Next Step Recommendation

Proceed with:

```text
Step 97: Privacy/support wording finalization after payload/report visibility decisions
```

WordPress.org release remains `Hold`.
