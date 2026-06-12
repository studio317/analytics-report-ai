# Step 95: Payload Preview JSON Visibility Final Decision

## Decision Summary

Payload Preview remains part of the Analytics Report AI MVP because the user
must be able to review what will be sent to AI before generation.

However, the public-release posture is not to continue showing the full raw AI
payload JSON in the normal admin UI.

The MVP value is pre-send review. The value is not raw JSON visibility itself.
For public-release readiness, Payload Preview should remain as a structured,
human-readable preview focused on the information needed to decide whether AI
generation is appropriate.

This is a docs-only final decision. It does not change production PHP,
JavaScript, CSS, `readme.txt`, admin UI behavior, Settings save logic, GA4
client behavior, OpenAI client behavior, credential storage, or release
packaging.

Plugin Check was not executed. No external API communication was performed.
`wp-dev-check` was not touched.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step94-release-readiness-blocker-priority-decision.md`
- `docs/maturation/step93-external-api-error-path-recheck-results.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`

## Final Decision

Adopt the following policy for public-release readiness:

- Avoid displaying the full raw AI payload JSON in the normal admin UI.
- Preserve Payload Preview as a structured summary / human-readable preview.
- Limit the normal preview to information required for pre-send judgment, such
  as:
  - selected report conditions,
  - metric presence state,
  - `payload_status`,
  - `data_availability`,
  - `value_semantics`,
  - no-data / zero-activity / partial-data / comparison warnings,
  - whether AI generation is allowed or blocked.
- Do not add UI paths that encourage raw JSON copy, raw JSON screenshots, or
  raw JSON support paste.
- Do not request raw AI payload JSON as support/debug evidence.
- Keep any future debug-mode raw JSON view on `Hold` until a separate step
  designs explicit access, warnings, redaction expectations, and non-public
  usage boundaries.

The final decision separates two concepts:

| Concept | Decision |
|---|---|
| Pre-send review before AI generation | Keep as MVP value. |
| Full raw AI payload JSON in normal admin UI | Do not adopt for public-release posture. |
| Structured / human-readable Payload Preview | Keep and align later. |
| Raw JSON support/debug evidence | Do not request. |
| Future debug-only raw JSON mode | Hold pending separate design. |

## Rationale

AI payloads can contain sensitive site and analytics context derived from GA4,
including page paths, traffic sources, city or regional values, analytics
values, selected conditions, and other site-specific context.

An administrator may be allowed to see this information inside their own
WordPress admin area, but copying it into support tickets, screenshots, issue
reports, chat transcripts, logs, or debugging notes can expose more information
than necessary.

The MVP user value is that the administrator can review the AI input before
generation. That value can be preserved without showing a full raw JSON body.
A structured preview can still answer the key user questions:

- What report conditions were selected?
- Did GA4 return enough data for the current period?
- Is the current period no-data, zero activity, partial data, or normal data?
- Is comparison data available?
- Are there warnings that should constrain the generated report?
- Is AI generation allowed or blocked?

The Step 91 metadata additions make structured preview more viable. In
particular, `payload_status`, `data_availability`, and `value_semantics`
provide explicit status-level context that maps better to UI summaries than to
raw JSON review.

Closing this decision first also stabilizes later privacy/support wording. If
raw payload JSON is not normal support evidence, support guidance can stay
clear: use status-level labels, warnings, categories, and redacted UI state
instead of asking for full payload bodies.

## Current MVP Posture

Current implementation may still include raw JSON preview behavior. Step 95
does not change that behavior.

This step is a policy and release-readiness decision only. A later step should
decide whether implementation alignment is needed and, if so, how to align the
admin UI, wording, support guidance, and QA expectations with this decision.

Current posture:

| Area | Status |
|---|---|
| WordPress.org release | `Hold` |
| Raw JSON visibility policy | Final decision recorded here. |
| Production implementation alignment | Not changed in Step 95. |
| Support/debug wording alignment | Follow-up needed. |
| Generated report handling policy | Follow-up needed. |
| Plugin Check | Not run. |

## Support / Debug Policy Impact

Support should not request raw AI payload JSON.

Support should not request:

- OpenAI request body.
- Raw GA4 response.
- Raw OpenAI response.
- Generated report body.
- Credentials.
- API keys.
- Access tokens.
- Authorization headers.
- Plugin settings option values.
- GA4 Property ID real values.
- Hostname/domain real values.
- Analytics values.
- Page path, source, or city values.
- Cookies, sessions, or nonces.

If screenshots are requested, they should be limited to cropped or redacted UI
state that excludes:

- payload JSON,
- generated report body,
- credentials,
- site-specific analytics data,
- browser Network tab data,
- cookies,
- sessions,
- nonces.

Diagnosis should rely on:

- status-level labels,
- warnings,
- error code/category,
- generation allowed/blocked state,
- no-data / zero / partial / comparison availability state,
- redacted UI state,
- command success/failure summaries that do not include sensitive values.

This matches the Step 86 redaction policy and gives future support wording a
clear boundary: do not ask users to paste the full AI payload.

## Implementation Follow-up

Step 95 intentionally does not change code.

Possible follow-up steps:

```text
Step 96: Payload Preview UI alignment plan
```

or:

```text
Step 96: Generated report handling policy finalization
```

Recommended next step:

```text
Step 96: Generated report handling policy finalization
```

Rationale:

- Payload Preview and generated report output are both data visibility /
  privacy decisions.
- Closing both policies first makes the later UI, wording, support guidance,
  and implementation alignment easier to plan coherently.
- After both policies are final, a dedicated Payload Preview UI alignment plan
  can decide whether to remove, hide, gate, or replace the current raw JSON
  behavior.

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
Step 96: Generated report handling policy finalization
```

After that, plan Payload Preview UI alignment against both final visibility
policies.

WordPress.org release remains `Hold`.
