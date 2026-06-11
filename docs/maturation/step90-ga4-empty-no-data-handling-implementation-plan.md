# Step 90: GA4 Empty / No-data Handling Implementation Plan

## Scope

This Step is a docs-only implementation plan for GA4 empty and no-data handling.
It does not change production PHP, JavaScript, CSS, `readme.txt`, settings,
credential storage, GA4 request logic, OpenAI request logic, transient storage,
release packaging, or WordPress.org metadata.

No external API communication was performed for this plan. The plan intentionally
does not include real credentials, access tokens, API keys, authorization
headers, full request payloads, raw GA4 responses, generated reports, real
property identifiers, host names, domains, page paths, source values, city
values, or other customer analytics values.

WordPress.org release status remains **Hold** until the no-data behavior is
implemented and rechecked.

## Referenced Documents

- `docs/maturation/step75-error-handling-qa-checklist.md`
- `docs/maturation/step76-error-handling-qa-phase1-results.md`
- `docs/maturation/step77-external-api-error-path-qa-checklist.md`
- `docs/maturation/step87-external-api-error-path-qa-execution-plan.md`
- `docs/maturation/step88-external-api-error-path-qa-controlled-execution-results.md`
- `docs/maturation/step89-ga4-empty-no-data-handling-decision.md`

Related maturation context:

- `docs/maturation/step78-data-minimization-privacy-review.md`
- `docs/maturation/step82-external-services-privacy-disclosure-draft.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`

## Selected Planning Posture

Step 90 follows the Step 89 direction of a granular no-data handling model.
The plugin should not treat every successful GA4 HTTP response as a clean report
success. It should classify data availability before a payload is accepted for
preview and before an AI report can be generated.

Planning assumptions:

- A fully empty GA4 result is not an ordinary success state.
- Partial data can still be useful when the missing category is clearly
  disclosed to the admin and to the AI prompt context.
- Zero metric values must be distinguished from missing GA4 rows or missing
  metric values.
- Current-period no-data is more severe than comparison-period no-data.
- Detail presets with empty row sets should be classified separately from
  summary metrics.
- OpenAI generation should be blocked for complete no-data and allowed only
  when the payload contains enough meaningful current-period data.
- Final implementation requires human approval because it can affect payload
  schema, preview UI, and generation gating.

## Current Suspected Behavior

Source review suggests the current MVP can convert a synthetic empty GA4 result
into a valid-looking payload:

- `Analytics_Report_AI_GA4_Client::extract_summary_values()` initializes
  expected summary metrics to zero and returns the zero-filled summary when GA4
  rows or metric values are absent.
- `Analytics_Report_AI_GA4_Client::extract_dimension_rows()` returns an empty
  array when dimension rows are absent.
- `Analytics_Report_AI_Report_Data_Formatter::create_payload_from_ga4_summary()`
  formats summary metrics and detail rows without a no-data status field.
- `analytics_report_ai_validate_ai_payload()` validates shape, types, payload
  version, and row limits, but it does not determine whether the payload has
  meaningful data.
- `Analytics_Report_AI_Report_Builder::handle_fetch_ga4_summary()` creates and
  stores the payload when shape validation passes.
- `Analytics_Report_AI_Report_Builder::render_payload_preview()` shows the
  preview and generation form for `payload_created` and `report_generated`
  states, without a dedicated no-data notice.
- `Analytics_Report_AI_Report_Builder::handle_generate_ai_report()` validates
  payload shape before generation, but it does not currently block no-data
  payloads.

This is a source-level suspicion only. Step 90 does not verify provider behavior
against real GA4.

## Proposed Classification Rules

| Classification | Detection concept | Payload creation behavior | Preview behavior | OpenAI generation behavior | User-facing status category | Risk notes |
|---|---|---|---|---|---|---|
| Complete no-data | No meaningful summary metrics and no detail rows for the selected current period. | Do not create a normal success payload, or create a blocked diagnostic payload only if approved. | Show a blocking no-data notice. | Block. | `ga4_no_data` | Avoid presenting an empty report as successful. |
| Summary missing | Summary response has no row or required metric values are absent. | Mark summary availability as missing. | Show a warning or blocking notice depending on detail data. | Block if no current-period detail data exists. | `ga4_empty_summary` | Missing is not the same as real zero. |
| Summary zero but present | GA4 response includes required metric values and all are zero. | Payload may be created with `zero_values_are_real`. | Show a no-activity notice, not a transport error. | Allow only if this state is intentionally accepted. | `ga4_zero_activity` | Requires reliable detection that zero values were present, not defaulted. |
| Summary present | Required summary metrics are present. | Create payload with summary availability present. | Normal preview, with detail warnings if needed. | Allow if current period has enough context. | `ga4_payload_created` | Existing behavior mostly applies. |
| Detail preset empty | A specific detail report has no rows. | Include empty rows and preset-level status. | Show per-preset informational or warning message. | Allow when summary/current data is meaningful. | `ga4_empty_detail_rows` | AI should not infer missing categories. |
| Detail preset present | A specific detail report has one or more rows. | Include rows up to the existing row limit. | Normal preview. | Allow. | `ga4_detail_rows_present` | Existing row-limit validation still applies. |
| All detail presets empty | Summary may exist, but all detail reports are empty. | Create payload only if summary is present and meaningful. | Show a clear warning. | Allow only with prompt context that details are unavailable. | `ga4_partial_data` | Generated report may be thin. |
| Some detail presets empty | At least one detail preset has rows and at least one is empty. | Create payload with warnings. | Show scoped warning for missing categories. | Allow with metadata. | `ga4_payload_created_with_warnings` | The report should mention limitations. |
| Current period no-data | Current-period summary and details have no meaningful data. | Do not create a normal success payload. | Blocking notice. | Block. | `ga4_current_period_no_data` | The report subject has no current data. |
| Comparison period no-data | Current period has data, but comparison period is missing or empty. | Create payload with comparison availability warning. | Show comparison warning. | Allow with explicit prompt context. | `ga4_comparison_period_no_data` | Avoid false trend claims. |
| Current present / comparison missing | Current period has data, comparison period unavailable. | Create payload with comparison unavailable metadata. | Show warning. | Allow. | `ga4_partial_data` | AI must avoid comparing against absent baseline. |
| Current missing / comparison present | Comparison has data, current period unavailable. | Treat as blocking unless a special diagnostic mode is approved. | Blocking notice. | Block. | `ga4_current_period_no_data` | Avoid generating a current report from comparison-only data. |
| Filtered scope no-data | Selected path/scope produces no rows, while the property may have data elsewhere. | Create blocked or warning state depending on summary availability. | Explain that the selected scope returned no data. | Block if current selected scope has no meaningful data. | `ga4_no_data_for_scope` | Message must avoid exposing sensitive path details. |

## Proposed Status Categories and Severity Model

| Status category | Severity | Where displayed | Payload created? | Generate AI Report allowed? | Included in AI payload metadata? | Suggested user-facing message intent |
|---|---|---|---|---|---|---|
| `ga4_no_data` | Blocking | Fetch result notice and Payload Preview area. | No normal success payload. | No. | Optional diagnostic metadata only if approved. | The selected date range and scope returned no reportable GA4 data. |
| `ga4_partial_data` | Warning | Payload Preview area. | Yes. | Yes, if current-period data is meaningful. | Yes. | Some report categories are unavailable; review before generation. |
| `ga4_current_period_no_data` | Blocking | Fetch result notice and Payload Preview area. | No normal success payload. | No. | Optional diagnostic metadata only if approved. | Current period has no reportable data. |
| `ga4_comparison_period_no_data` | Warning | Payload Preview area. | Yes. | Yes. | Yes. | Comparison data is unavailable, so trend statements will be limited. |
| `ga4_empty_summary` | Blocking or warning | Fetch result notice and Payload Preview area. | Depends on detail data. | Depends on detail data. | Yes if payload is created. | Summary metrics are unavailable. |
| `ga4_empty_detail_rows` | Informational or warning | Payload Preview area near the affected category. | Yes. | Yes when summary/current data is meaningful. | Yes. | A detail category has no rows. |
| `ga4_payload_created_with_warnings` | Warning | Payload Preview area. | Yes. | Yes. | Yes. | Payload was created, but the AI report should account for missing categories. |
| `ga4_generation_blocked_no_data` | Blocking | Generate action response and Payload Preview area. | Existing payload may remain for review. | No. | Yes if the payload exists. | AI generation was blocked because the payload has no reportable current data. |

Final severity values should be reviewed with the desired UX. The main product
decision is whether "summary zero but explicitly present" should be considered a
valid no-activity report or blocked as insufficient for MVP.

## Payload Metadata Plan

The implementation should add a small, explicit no-data metadata section rather
than relying on admins or prompt logic to infer availability from empty arrays.

Conceptual structure, not a final schema:

```text
payload_status:
  overall_status
  generation_allowed
  generation_block_reason
  warnings

data_availability:
  current_period
  comparison_period
  summary
  detail_presets

value_semantics:
  zero_values_are_real
  missing_values_are_unavailable
```

Recommended metadata fields:

| Metadata area | Purpose | Notes |
|---|---|---|
| Overall payload status | Distinguish clean success, warning success, and blocked no-data. | Should drive preview notices and server-side generation gating. |
| Generation allowed flag | Avoid relying only on disabled buttons. | Server-side check must remain authoritative. |
| Generation block reason | Explain why AI generation is unavailable. | Use safe, translatable, non-secret categories. |
| Warnings array | Carry non-blocking missing-data notices. | Should not include raw response snippets or identifiers. |
| Current-period availability | Decide whether the primary report period has reportable data. | Blocking when unavailable. |
| Comparison-period availability | Decide whether comparison claims are allowed. | Warning when unavailable but current data exists. |
| Summary availability | Distinguish present values, explicit zeros, missing row, and missing metric values. | Requires GA4 client extraction changes. |
| Detail preset availability | Record row presence per category. | Keep existing row limits. |
| Zero-vs-missing semantics | Prevent defaulted zeros from being mistaken for real zero values. | This is the highest-risk ambiguity in the current flow. |

Potential schema change questions:

- Should payload version be incremented when no-data metadata is added?
- Should metadata be included in the AI-facing payload or only in the preview
  and prompt context?
- Should blocked diagnostic payloads be stored in transient storage, or should
  no transient be created for complete no-data?
- Should existing payload validation reject missing no-data metadata after the
  implementation, or allow old payloads during transition?

## UI / Admin Notice Plan

The admin UI should expose no-data states without requiring the user to inspect
the full JSON preview.

Recommended UI behavior:

- Show a blocking notice after GA4 fetch when the selected date range and scope
  have no reportable current-period data.
- Do not show the normal "payload created" success state for complete no-data.
- Show a warning notice in Payload Preview when data is partial.
- Keep the existing staged flow: GA4 fetch, Payload Preview, AI generation.
- Disable or hide the AI generation action only when the server-side generation
  gate would block it.
- Show comparison-period limitations near the preview when comparison data is
  unavailable.
- Show detail-category warnings without exposing sensitive path, source, city,
  or device values beyond what the normal preview already shows.
- Use translatable, escaped WordPress admin copy.
- Do not include raw GA4 responses, authorization headers, request payloads, or
  credential values in notices, logs, screenshots, or support guidance.

## OpenAI Generation Rule Plan

OpenAI generation should be governed by classified payload status:

- Block generation when current-period data is completely absent.
- Block generation when summary values are missing and all detail rows are empty.
- Allow generation when current-period summary data is present and detail rows
  are partially empty, but include explicit warning context.
- Allow generation when comparison data is missing, but instruct the prompt not
  to make period-over-period claims.
- Allow generation for explicit zero-activity data only if final product
  decision accepts zero-activity reports.
- The prompt context should tell the AI not to infer missing categories.
- The generated report should mention limitations when comparison or detail data
  is unavailable.

The transport layer in `includes/class-openai-client.php` should not need a
request/response behavior change for this Step. The gate should prevent
generation before the OpenAI client is called.

## Implementation Impact Analysis

| Area | Likely file | Expected change | Risk level | Notes |
|---|---|---|---|---|
| GA4 response extraction | `includes/class-ga4-client.php` | Preserve whether summary metric values were present, explicitly zero, or missing. Add availability metadata to normalized data. | Medium | Must avoid changing GA4 transport behavior. |
| Dimension report extraction | `includes/class-ga4-client.php` | Preserve row-count and availability status per detail report. | Low to medium | Existing empty arrays can remain, but should be accompanied by status. |
| Payload formatting | `includes/class-report-data-formatter.php` | Add no-data metadata to formatted payload. | Medium | May require payload version decision. |
| Payload validation | `includes/functions-utils.php` | Validate new metadata shape and generation-gate fields. | Medium | Must preserve existing row limit and version checks. |
| GA4 fetch action | `includes/class-report-builder.php` | Classify payload after fetch and before normal success state. | Medium | Must keep nonce/capability checks and existing staged flow. |
| Payload Preview rendering | `includes/class-report-builder.php` | Render blocking/warning notices outside full JSON. | Medium | Must keep existing preview behavior and secret redaction assumptions. |
| AI generation action | `includes/class-report-builder.php` | Enforce server-side generation block for no-data payloads. | Medium | Button state alone is insufficient. |
| Prompt construction | `includes/class-prompt-builder.php` | Include no-data limitations where generation is allowed. | Low to medium | Must avoid changing unrelated prompt structure. |
| OpenAI client transport | `includes/class-openai-client.php` | No expected transport change. | Low | Should only be reached after gating. |
| Support/debug guidance | Docs / future admin guidance | Update support guidance so no-data categories can be reported without secrets. | Low | Coordinate with Step 78. |

## QA Recheck Plan

QA should use synthetic or controlled local conditions only until explicit
external-service testing is approved. The following matrix is a proposed
recheck plan after implementation.

| ID | Scenario | Expected fetch result | Expected preview result | Expected generation result | Notes |
|---|---|---|---|---|---|
| NO-DATA-01 | Complete empty synthetic response. | Blocking no-data status, no normal success. | Blocking notice visible. | Blocked before OpenAI client. | No raw response recorded. |
| NO-DATA-02 | Summary present, all detail rows empty. | Payload created with warnings. | Summary visible, detail warnings visible. | Allowed only with warning context. | Human decision needed if report is too thin. |
| NO-DATA-03 | Some detail presets empty. | Payload created with warnings. | Per-category warning visible. | Allowed with missing-category context. | AI must not infer missing rows. |
| NO-DATA-04 | All detail presets empty and summary missing. | Blocking no-data status. | Blocking notice visible. | Blocked. | Highest-risk current behavior. |
| NO-DATA-05 | Current period no-data, comparison present. | Blocking current-period no-data status. | Blocking notice visible. | Blocked. | Avoid generating from comparison-only data. |
| NO-DATA-06 | Current period present, comparison no-data. | Payload created with comparison warning. | Warning visible. | Allowed with no comparison claims. | Trend sections should be limited. |
| NO-DATA-07 | Explicit zero values vs missing metric values. | Different statuses for real zero and missing. | Correct notice text for each. | Depends on final zero-activity decision. | Requires extractor-level evidence. |
| NO-DATA-08 | Blocked payload submitted to Generate. | Existing payload may remain for review. | Blocking status remains visible. | Server-side block. | Protect against crafted POST. |
| NO-DATA-09 | Warning payload submitted to Generate. | Payload remains valid. | Warning status remains visible. | Allowed with warning context. | Verify prompt limitations. |
| NO-DATA-10 | Support/debug review. | No secrets displayed. | No raw bodies or identifiers recorded. | No OpenAI call for blocked cases. | Coordinate with redaction guidance. |
| NO-DATA-11 | Browser rendering. | Notices visible without PHP warnings. | Preview and button state clear. | Block/allow state clear. | Requires admin browser smoke. |
| NO-DATA-12 | Payload Preview JSON visibility. | Metadata visible only as appropriate. | Warning does not require reading full JSON. | N/A. | Coordinate with payload visibility decision. |

## Human Decision Checklist

Before implementation, decide:

- Should an explicitly present all-zero summary be a valid "zero activity"
  report, or should it be blocked in the MVP?
- Should complete no-data create no transient payload, or create a blocked
  diagnostic payload for admin review?
- Should no-data metadata be part of the AI-facing payload, prompt context only,
  or both?
- Should the payload version be incremented when metadata is added?
- Which detail presets should be warning-only vs blocking when empty?
- Should the AI generation button be hidden, disabled, or left visible with a
  blocking server-side response?
- How much of the no-data metadata should be visible in Payload Preview JSON?
- What exact wording should be used for support reports without exposing
  analytics values or secrets?

## Release Blockers / Follow-up Decisions

Release remains on hold because:

- GA4 empty/no-data handling is not implemented.
- No-data QA recheck has not been executed.
- External API error-path QA needs recheck after no-data implementation.
- Support/debug redaction guidance is not release-final for this new category.
- AI Payload Preview JSON visibility policy is not final for no-data metadata.
- Generated report handling policy is not final for partial-data reports.
- AI payload category acceptance is not final.
- External services and privacy wording may need a release-final pass.
- Google OAuth and token lifecycle remain unresolved.
- OpenAI API key storage remains unresolved.
- Uninstall credential cleanup remains unresolved.
- Plugin Check and PHPCS refresh have not been executed for the final package.
- Release package contents have not been reviewed.

## This Step Does Not Implement

- No production PHP changes.
- No JavaScript or CSS changes.
- No UI copy changes.
- No `readme.txt` changes.
- No GA4 client transport changes.
- No OpenAI client transport changes.
- No transient key or expiration changes.
- No credential storage changes.
- No OAuth changes.
- No release zip or package changes.
- No external API calls.

## Recommended Next Step

Recommended next Step: **Step 91: GA4 empty/no-data handling implementation**.

Step 91 should be treated as a production-code Step and should require explicit
approval for payload metadata shape, generation gating behavior, and admin
notice wording before code changes begin.
