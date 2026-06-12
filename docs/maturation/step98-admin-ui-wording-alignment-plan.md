# Step 98: Admin UI and Wording Alignment Plan

## Step Summary

Step 95 through Step 97 finalized the data visibility, support, and privacy
policies for AI input and AI output in Analytics Report AI.

Step 98 creates a docs-only alignment plan for later admin UI wording, help
text, support guidance, QA evidence guidance, and readme/privacy wording.

This step does not implement wording changes. It identifies likely update
targets and the desired wording direction so a later implementation step can
make scoped, reviewable changes.

Production PHP, JavaScript, CSS, `readme.txt`, admin UI behavior, Settings save
logic, GA4 client behavior, OpenAI client behavior, credential storage, release
packaging, and WordPress.org metadata were not changed.

Plugin Check was not executed. No external API communication was performed.
`wp-dev-check` was not touched. GA4 Fetch and OpenAI Generate were not run.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step96-generated-report-handling-policy-finalization.md`
- `docs/maturation/step95-payload-preview-json-visibility-final-decision.md`
- `docs/maturation/step94-release-readiness-blocker-priority-decision.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`
- `docs/maturation/step93-external-api-error-path-recheck-results.md`

## Alignment Target Inventory

The following areas should be reviewed in later implementation planning.

This inventory is based on source/doc wording only. It does not inspect option
values, credentials, real payloads, generated report bodies, analytics values,
screenshots, browser Network data, cookies, sessions, or nonces.

| Target category | Likely area | Alignment need |
|---|---|---|
| Payload Preview heading / description | Report Builder Payload Preview section | Reframe as structured pre-send summary rather than raw payload review. |
| Payload Preview table labels | Summary and preset preview tables | Keep useful human-readable labels for status, warnings, and report categories. |
| Payload Preview warning labels | No-data / zero / partial / comparison warnings | Keep status-level wording and ensure warning text does not encourage payload/body sharing. |
| Raw JSON preview area, if currently present | Payload Preview details/raw JSON area | Plan replacement, removal, or explicit gating so normal UI does not rely on full raw JSON. |
| AI Generate button surrounding description | Generate AI Report form and helper text | Clarify that generation should happen after reviewing the structured preview and warnings. |
| Generated Report heading / description | Generated report draft section | Preserve review/edit/copy workflow and clarify that generated report body is not saved by the plugin. |
| Generated Report textarea surrounding description | Generated report textarea helper copy | Avoid support-sharing cues; emphasize user review/edit responsibility. |
| Copy button surrounding description | Copy Report Text action | Treat copying as explicit user action; avoid implying plugin persistence. |
| Error / warning admin notices | Fetch/generate notices and warning lists | Keep evidence status-level: error category, warnings, allowed/blocked state. |
| Support/debug guidance text | Future admin help, docs, or support template | State that support should not request raw payloads, report bodies, raw responses, credentials, option values, or analytics identifiers/values. |
| Privacy/readme wording | `readme.txt` and future privacy/help text | Align external service and storage disclosures with Step 95 through Step 97. |
| QA evidence instructions in docs | Maturation docs and future QA checklists | Keep status-level evidence rules and prohibit screenshots/body captures that expose sensitive data. |

## Source Areas Reviewed At Wording Level

The later implementation plan should consider these source/document areas:

- `includes/class-report-builder.php`
  - Payload Preview heading and description.
  - Payload Preview warning notices.
  - Raw JSON preview area.
  - Data sent to OpenAI section.
  - Generate AI Report button helper text.
  - Generated Report heading and description.
  - Copy Report Text action and status text.
- `includes/class-admin.php`
  - Localized JavaScript copy status strings.
- `includes/class-settings.php`
  - External service and credential guidance, only if the later step needs
    cross-screen wording consistency.
- `readme.txt`
  - External Services section.
  - Credential Storage and Payload Review section.
  - Future privacy/support wording.
- `docs/maturation/*`
  - Support/debug guidance.
  - QA evidence rules.
  - Release-readiness notes.

No production files were modified in Step 98.

## Desired Wording Direction

### Payload Preview

Payload Preview should be explained as a summary review before AI submission.

Desired direction:

- Describe it as a structured summary of what will be sent to AI.
- Emphasize status, warnings, data availability, and generation allowed/blocked
  state.
- Keep no-data, zero-activity, partial-data, and comparison availability
  warnings visible and understandable.
- Avoid wording that encourages raw JSON copy, raw JSON screenshots, or raw
  JSON support paste.
- If raw JSON behavior remains temporarily during implementation alignment,
  treat it as a known mismatch with the Step 95 public-release posture.

### Generated Report

Generated Report should be explained as the user-facing result to review, edit,
and copy.

Desired direction:

- Describe it as generated output for the current user to review and edit.
- Preserve Copy Report Text as an explicit user action.
- Mention, where useful, that the plugin does not persist the generated report
  body.
- Avoid wording that suggests support/debug should request generated report
  bodies.
- Keep draft/review language so users understand the generated text should be
  checked before publishing, sharing, or sending.

### Support Guidance

Support/debug guidance should not request:

- raw AI payload JSON,
- generated report body,
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

Support/debug diagnosis should use:

- status-level labels,
- warnings,
- error code/category,
- generation allowed/blocked state,
- no-data / zero-activity / partial-data / comparison availability state,
- redacted UI state,
- command success/failure summaries that do not include sensitive values.

### Privacy / Readme Wording

Privacy/readme wording should explain:

- GA4-derived report data can be sent to OpenAI when the user generates an AI
  report.
- The data is based on selected date range, comparison setting, data scope, and
  report presets.
- Generated report body is not intended to be persisted by the plugin.
- Raw AI payload JSON and generated report bodies are not requested as support
  evidence.
- Credential and external-service disclosures remain separate but should not
  conflict with these visibility policies.

## Implementation Planning

Step 98 is docs-only and intentionally does not change code.

Later implementation should split the work into smaller scoped steps:

| Candidate step | Scope |
|---|---|
| Step 99A | Admin UI wording alignment implementation. |
| Step 99B | Payload Preview raw JSON replacement / gating implementation plan. |
| Step 99C | `readme.txt` / privacy wording update. |
| Step 99D | Support/debug guidance docs update. |

Recommended next step:

```text
Step 99: Admin UI wording alignment implementation plan
```

Reason:

- Do not jump directly into implementation before deciding which labels, help
  text, notices, and UI sections should change.
- Raw JSON preview handling is tightly connected to admin UI wording, so the
  implementation plan should explicitly define the intended change boundary.
- Plugin Check should wait until UI / wording alignment is either implemented
  or at least planned tightly enough that findings will not be invalidated by
  immediate wording changes.

## Risk Notes

- Keeping raw JSON preview as public-release-ready normal UI may conflict with
  the Step 95 policy.
- Adding generated report storage would conflict with the Step 96 policy unless
  a separate storage/privacy design step is completed first.
- Support wording that asks for payload bodies or generated report bodies would
  conflict with the Step 97 policy.
- Advancing to Plugin Check or release package review before privacy/readme
  wording alignment may create avoidable rework.
- Any implementation that changes UI visibility must preserve the MVP flow:
  GA4 Fetch, pre-send review, AI Generate, generated draft review/edit/copy.
- Any later wording implementation must remain translatable and escaped in
  production code.

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

This document records only status-level planning information.

It does not record real credentials, API keys, access tokens, Authorization
headers, plugin settings option values, GA4 Property IDs, hostname/domain
values, analytics values, page paths, traffic sources, city values, request
bodies, AI payload JSON, OpenAI request bodies, raw GA4/OpenAI response bodies,
generated report bodies, screenshots, browser Network tab data, cookies,
sessions, or nonces.

## Next Step Recommendation

Proceed with:

```text
Step 99: Admin UI wording alignment implementation plan
```

WordPress.org release remains `Hold`.
