# Step 97: Privacy / Support Wording Finalization

## Decision Summary

Step 95 finalized the visibility boundary for AI input. Raw AI payload JSON
should not be treated as normal admin UI visibility or normal support evidence
for public-release posture.

Step 96 finalized the visibility boundary for AI output. Generated report
bodies may remain visible to the user in the admin UI for review, editing, and
copying, but should not be persisted by the plugin or requested as
support/debug evidence.

Step 97 integrates those visibility decisions into the public-release posture
for privacy and support wording.

This is a docs-only wording policy finalization step. It does not change
production PHP, JavaScript, CSS, `readme.txt`, admin UI behavior, Settings save
logic, GA4 client behavior, OpenAI client behavior, credential storage, release
packaging, or WordPress.org metadata.

Plugin Check was not executed. No external API communication was performed.
`wp-dev-check` was not touched. GA4 Fetch and OpenAI Generate were not run.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step96-generated-report-handling-policy-finalization.md`
- `docs/maturation/step95-payload-preview-json-visibility-final-decision.md`
- `docs/maturation/step94-release-readiness-blocker-priority-decision.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`
- `docs/maturation/step93-external-api-error-path-recheck-results.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`

## Final Wording Policy

Adopt the following privacy/support wording policy for public-release
readiness:

- Support/debug processes should not request raw AI payload JSON.
- Support/debug processes should not request generated report bodies.
- Support/debug processes should not request OpenAI request bodies, raw GA4
  responses, or raw OpenAI responses.
- Support/debug processes should not request credentials, API keys, access
  tokens, Authorization headers, or plugin settings option values.
- Support/debug processes should not request GA4 Property ID real values,
  hostname/domain real values, analytics values, page path values, traffic
  source values, or city values.
- Screenshots, if requested at all, should be limited to cropped or redacted UI
  state.
- Browser Network tab data, cookies, sessions, and nonces should not be
  requested.
- Diagnosis should rely on status-level labels, warnings, error code/category,
  generation allowed/blocked state, no-data / zero-activity / partial-data /
  comparison availability state, and redacted UI state.

This policy should guide later admin help text, support guidance, privacy
wording, readme wording, and QA evidence rules.

## Admin / Help Wording Direction

Future admin/help wording should explain Payload Preview as a structured
pre-send review, not as a raw data export.

Recommended direction:

- Describe Payload Preview as a summary view for reviewing what will be sent
  to AI before generation.
- Avoid wording that encourages copying, pasting, or screenshotting raw JSON.
- Explain Generated Report as the generated output that the user can review,
  edit, and copy.
- When useful, state that the plugin does not persist generated report bodies.
- For support requests, ask users to share status, warning, or error category
  information rather than report bodies or payload bodies.

Admin/help wording should stay consistent with the two data visibility
boundaries:

| Area | Wording direction |
|---|---|
| Payload Preview | Structured summary for pre-send review. |
| Raw AI payload JSON | Do not encourage copy, screenshot, or support paste. |
| Generated Report | User-visible output for review, editing, and copy. |
| Generated report body | Do not ask users to submit it for support/debug. |
| Support evidence | Prefer status, warning, error category, and redacted UI state. |

## Privacy Wording Direction

Future privacy wording should state that Analytics Report AI can send GA4-based
report data to OpenAI for AI report generation.

Recommended direction:

- The plugin may send analytics-derived report data from GA4 to OpenAI when
  the user generates an AI report.
- The sent data is based on the selected date range, comparison setting, data
  scope, and report presets.
- Data categories may include summary, trend, page, channel, source, and
  regional report data needed for report generation.
- The plugin's public-release posture is not to persist generated report
  bodies.
- The plugin's public-release posture is not to request raw AI payload JSON or
  generated report bodies as support evidence.
- Final implementation and user-facing wording alignment should be handled in
  later scoped steps.

This document records wording direction and policy. It does not update
`readme.txt`, admin UI text, or privacy copy in production files.

## Relationship To Previous Steps

| Step | Relationship |
|---|---|
| Step 95 | Finalized that raw AI payload JSON should not remain visible in the normal admin UI for public-release posture and should not be normal support evidence. |
| Step 96 | Finalized that generated report bodies can remain visible to the user in admin UI, but should be excluded from plugin persistence and support/debug evidence. |
| Step 97 | Integrates the Step 95 and Step 96 visibility decisions into privacy/support wording policy. |

Together, these steps define the main AI input and AI output support boundary:

| Data area | Public-release support posture |
|---|---|
| Raw AI payload JSON | Do not request. |
| Generated report body | Do not request. |
| OpenAI request body | Do not request. |
| Raw GA4/OpenAI responses | Do not request. |
| Credentials / option values / headers | Do not request. |
| Analytics identifiers and values | Do not request. |
| Status-level labels and categories | Prefer for diagnosis. |

## Implementation Follow-up

Step 97 intentionally does not change code.

Recommended next step:

```text
Step 98: Admin UI and wording alignment plan for payload/report visibility policies
```

Reason:

- Step 95 through Step 97 have finalized the data visibility, support, and
  privacy wording policy for AI input and AI output.
- Before implementation changes, the project should identify which admin UI
  labels, help text, notices, support guidance, readme/privacy wording, and QA
  evidence instructions need updates.
- Plugin Check should still wait until wording and policy alignment are stable
  or implemented.

Possible Step 98 planning targets:

- Payload Preview wording.
- Generated Report wording.
- Support/debug guidance.
- Admin notices and help text.
- `readme.txt` privacy/support wording, if later explicitly scoped.
- QA evidence guidance.
- Verification commands and browser/manual checks after implementation.

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

This document records only status-level wording policy decisions.

It does not record real credentials, API keys, access tokens, Authorization
headers, plugin settings option values, GA4 Property IDs, hostname/domain
values, analytics values, page paths, traffic sources, city values, request
bodies, AI payload JSON, OpenAI request bodies, raw GA4/OpenAI response bodies,
generated report bodies, screenshots, browser Network tab data, cookies,
sessions, or nonces.

## Next Step Recommendation

Proceed with:

```text
Step 98: Admin UI and wording alignment plan for payload/report visibility policies
```

WordPress.org release remains `Hold`.
