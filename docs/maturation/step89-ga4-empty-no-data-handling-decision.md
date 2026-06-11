# Step 89: GA4 Empty / No-data Handling Decision

## Step Summary

This document records the Step 89 GA4 empty/no-data handling decision
checkpoint for Analytics Report AI.

The purpose is to decide, before implementation, how GA4 empty/no-data
responses should be classified for public release readiness after the Step 88
controlled QA finding.

This is a docs-only decision checkpoint. It is not release-final
implementation, does not change runtime behavior, and does not close the
release-readiness blockers by itself.

This step does not change production code, `readme.txt`, Settings save logic,
GA4 client behavior, OpenAI client behavior, admin UI, JavaScript, CSS,
Composer/npm configuration, release packaging, or runtime behavior.

WordPress.org release remains `Hold`.

This document does not record real GA4 response bodies, real analytics values,
real hostname/domain values, real page path/source/city values, real GA4
property identifiers, real credentials, real access tokens, API keys,
Authorization headers, request bodies, payload JSON, raw responses, generated
report bodies, nonces, cookies, or session values.

## Step 88 Finding Recap

| Item | Status-level Recap |
|---|---|
| Scenario ID | `GA4-07` |
| Expected category | Empty / no-data response category |
| Observed category | Synthetic empty data was treated as `payload_created` success. |
| Risk | A user may believe a meaningful payload/report was created even when GA4 returned no usable data. |
| Evidence boundary | Evidence remained status-level only. No raw response body, analytics values, identifiers, credentials, request body, payload JSON, or generated report body were recorded. |
| Real provider behavior | Not tested. Step 88 used synthetic HTTP interception. |
| Browser rendering | Not checked. Step 88 used WP-CLI controlled execution. |
| Release impact | Needs follow-up before public release readiness because no-data success can create misleading UX and report-quality risk. |

## Empty / No-data Definitions

| Category | Possible cause | User-facing meaning | Should payload be created? | Should OpenAI generation be allowed? | Recommended status category | Risk notes |
|---|---|---|---|---|---|---|
| Completely empty response | GA4 returns no rows and no useful summary for the requested report category. | No usable analytics data was available for the selected request. | No, unless represented as an explicit no-data state rather than success. | No. | `ga4_no_data` or `ga4_generation_blocked_no_data` | Treating this as success is misleading and can produce low-quality generated reports. |
| Valid response with zero rows | GA4 request is valid, but the selected dimensions/filters return no rows. | The selected scope or dimension category has no matching rows. | Maybe, if the payload records the no-row category explicitly. | Depends on whether other meaningful categories exist. | `ga4_empty_detail_rows` | Common for narrow filters; should not automatically fail the entire report if summary data exists. |
| Valid response with zero metric values | GA4 returns metrics that are present but all zero for the selected period or scope. | Data exists structurally but the selected metrics have zero activity. | Yes, if zero values are clearly distinguished from missing values. | Maybe, with explicit zero-data context. | `ga4_no_data` or `ga4_payload_created_with_warnings` | Zero values can be meaningful, but users must not confuse zero with missing/unavailable data. |
| Summary exists but detail tables are empty | Summary metrics are usable, but daily trend, top pages, traffic, or regional rows are empty. | The report has top-level data but lacks breakdown detail. | Yes, with warnings/status metadata. | Yes, if payload includes explicit limitations. | `ga4_partial_data` or `ga4_empty_detail_rows` | Blocking the entire flow may be too strict; silent success hides missing detail. |
| Some presets empty but summary metrics present | One or more preset reports have no rows, while other categories have usable data. | The report is partially useful but incomplete for certain breakdowns. | Yes, with per-preset warnings. | Yes, if OpenAI receives explicit no-data context. | `ga4_partial_data` | Missing preset context should be visible before generation. |
| All presets empty | Preset reports return no rows across detail categories. Summary may or may not exist. | The report lacks meaningful breakdowns and may not support a useful narrative. | Only if summary metrics are meaningful and warnings are explicit. | Usually no if summary is also empty; maybe yes if summary exists. | `ga4_empty_summary` or `ga4_generation_blocked_no_data` | High risk of a thin or misleading AI report if generation proceeds without context. |
| Filtered scope returns no rows | Directory/page/host filters are too narrow, incorrect, or have no traffic. | The selected scope has no matching analytics data. | No for completely empty scope; maybe with warning if unfiltered summary exists separately. | No unless payload explicitly says the selected scope had no data. | `ga4_no_data` or `ga4_empty_detail_rows` | Users may need to revise scope rather than generate a report. |
| Comparison period empty but current period has data | Prior month/year has no data or comparison filter does not match. | Current report is meaningful, but comparison is unavailable. | Yes, with comparison warning/status metadata. | Yes, with explicit comparison limitation. | `ga4_comparison_period_no_data` | Report should avoid false comparison claims. |
| Current period empty but comparison period has data | Selected current period/scope has no data while the comparison period has data. | The report may describe a current-period absence, but current performance is missing. | Maybe, if the absence itself is the intended report subject. | Human decision; default should be warning or block depending on severity. | `ga4_current_period_no_data` | AI generation could over-focus on comparison or imply current data exists. |

## Handling Option Comparison

| Option | Description | Benefit | Risk | User experience impact | Report quality impact | Privacy impact | Implementation impact if later implemented | QA impact | Recommended posture | Decision status |
|---|---|---|---|---|---|---|---|---|---|---|
| Option A: Treat empty/no-data as success with zero/empty payload | Always create a payload and allow the flow to continue when GA4 returns structurally valid empty data. | Simple behavior and minimal implementation. | Misleads users into thinking useful data exists; may produce thin or inaccurate reports. | Smooth but potentially deceptive. | Poor for completely empty data. | Neutral to privacy, but may encourage unnecessary OpenAI transmission. | Minimal. | Requires QA to ensure generated text does not invent unavailable data. | Not preferred for completely empty data. | Not accepted |
| Option B: Treat completely empty response as blocking error | Block payload creation when no meaningful current data is available. | Clear and safe for no-data cases. | Can be too strict if only one preset is empty or if zero data is meaningful. | Clear stop state; user may need to adjust filters/date range. | Strong protection against misleading AI output. | Reduces unnecessary OpenAI transmission. | Moderate; needs no-data detection before transient storage. | Requires recheck of GA4-07 and action-order behavior. | Preferred for completely empty current data. | Candidate |
| Option C: Treat empty/no-data as warning and allow preview, but block OpenAI generation | Create a preview with warning metadata but prevent Generate when meaningful data is missing. | Lets users inspect what is missing without sending weak payloads to OpenAI. | Adds state complexity; may frustrate users who expect generation. | Transparent and conservative. | Protects report quality when data is insufficient. | Reduces OpenAI data transfer for weak/no-data payloads. | Moderate to high; requires warning state and generation guard. | Requires preview and button-state QA. | Fallback candidate for severe no-data. | Candidate |
| Option D: Treat empty/no-data as warning and allow OpenAI generation with explicit no-data context | Allow generation when payload clearly states which data categories are empty. | Supports reports that need to explain no-data situations. | AI may still produce overconfident text if prompt/payload status is insufficient. | Flexible but needs clear warnings. | Acceptable when explicit limitations are included. | Sends no-data status metadata to OpenAI; may still transmit other analytics categories. | Moderate; requires payload metadata and prompt guidance. | Requires AI output review for no-data limitations. | Fallback candidate when some meaningful data exists. | Candidate |
| Option E: Granular handling by preset / data category | Classify summary, current, comparison, scope, and preset categories separately, then decide preview/generation behavior from severity. | Best balance between clear UX and report usefulness. | More complex; requires clear rules to avoid inconsistent states. | More informative; users can see partial data and limitations. | Best chance of useful reports without false success. | Can minimize OpenAI transmission when no meaningful data exists while allowing useful partial reports. | Highest among options; likely needs payload metadata, notices, and generation rules. | Requires scenario-specific QA for each no-data class. | Preferred candidate. | Needs human decision |

## Recommended Candidate Posture

Preferred candidate:

```text
Option E: Granular handling by preset / data category
```

Fallback candidates:

```text
Option C or Option D depending on severity.
```

Not preferred:

```text
Option A for completely empty data.
```

Rationale:

- Treating a completely empty response as ordinary success can mislead users.
- If only some preset reports are empty, failing the whole flow may be too
  strict.
- Summary metrics and all-preset-empty cases should be treated separately.
- Current-period and comparison-period no-data states should be treated
  separately because they have different reporting meanings.
- If current data exists and comparison data is empty, a report may still be
  useful if it clearly says comparison data is unavailable.
- If current data is empty and only comparison data exists, generation should
  be conservative and may need to be blocked or explicitly framed as a
  no-current-data report.
- Before sending to OpenAI, the payload should either include explicit no-data
  context or generation should be blocked.
- User-facing notices must remain safe and must not show raw GA4 response
  bodies, identifiers, credentials, request bodies, analytics values, paths,
  source rows, or city rows.

Human decision required:

- Step 89 recommends Option E as the preferred candidate, but this is not a
  final implementation decision. Product/release owner approval is needed
  before any production code changes.

## Candidate User-facing Status Categories

| Status category | When to use | User-facing message intent | OpenAI generation allowed? | Evidence allowed | Evidence prohibited |
|---|---|---|---|---|---|
| `ga4_no_data` | No meaningful current GA4 data is available for the selected scope/period. | Tell the user no usable GA4 data was found and suggest changing range/scope/settings. | No by default. | Status category, action name, screen name. | Raw response body, analytics values, identifiers, paths, sources, cities, credentials. |
| `ga4_partial_data` | Some report categories have data and others are empty. | Tell the user the preview is partial and identify category-level limitations. | Yes, if no-data context is included. | Category-level empty/non-empty labels. | Raw rows, metric values, payload JSON, identifiers. |
| `ga4_current_period_no_data` | Current period has no meaningful data. | Tell the user current period data is unavailable for the selected conditions. | No by default; human decision for special cases. | Status category and selected action only. | Current/comparison values, raw rows, paths, sources, cities. |
| `ga4_comparison_period_no_data` | Current period has data but comparison period is empty. | Tell the user comparison is unavailable and report will avoid comparison claims. | Yes, if current data is meaningful and payload marks comparison unavailable. | Status category and comparison-unavailable note. | Comparison rows/values, identifiers, raw body. |
| `ga4_empty_summary` | Summary metrics are absent, empty, or not meaningful. | Tell the user the top-level report cannot be created reliably. | No by default. | Status category and blocking reason category. | Raw summary response, metric values, payload JSON. |
| `ga4_empty_detail_rows` | Detail tables such as pages, sources, channels, city, or trend rows are empty. | Tell the user a specific breakdown is unavailable. | Yes if summary/current data is meaningful and limitations are explicit. | Category-level detail table status. | Table rows, page paths, source names, city names, metric values. |
| `ga4_payload_created_with_warnings` | Payload can be created but contains category-level no-data warnings. | Tell the user to review warnings before generation. | Maybe; allow only when warnings are included in payload/prompt context. | Warning category and affected preset category names. | Payload JSON, raw data rows, generated report body. |
| `ga4_generation_blocked_no_data` | No meaningful data exists for a useful AI report. | Tell the user generation is blocked until GA4 data is available. | No. | Status category and safe action guidance. | Raw response body, request body, analytics values, credentials. |

## Payload / OpenAI Generation Implications

Open questions for implementation planning:

- Empty rows should not be silently omitted if omission would hide a meaningful
  limitation from the user or from OpenAI.
- Empty/no-data categories may need explicit status metadata in the AI payload
  so the preview and prompt can distinguish missing data from zero-valued data.
- OpenAI should receive explicit no-data context only when generation remains
  allowed.
- Report generation should be blocked when all meaningful categories are empty
  or when current-period data is unavailable and no useful narrative can be
  produced safely.
- If generated reports are allowed with partial data, the report should mention
  no-data limitations and avoid unsupported comparisons.
- Comparison calculations need special handling when either current or
  comparison data is missing.
- Zero values and missing values must be distinguished. Zero can be a real
  measurement; missing means GA4 did not provide usable data for that category.
- Payload Preview should show no-data/warning status at category level without
  requiring users to inspect raw JSON.
- Prompt guidance may need to state that empty categories must be reported as
  unavailable rather than inferred.

## QA Follow-up Plan

After a human decision and any later implementation, run focused QA for:

- Synthetic empty response recheck.
- Partial-empty preset recheck.
- Current-only empty recheck.
- Comparison-only empty recheck.
- All-presets-empty recheck.
- Summary-present/detail-empty recheck.
- UI notice safety recheck.
- Payload Preview warning/status display recheck.
- OpenAI generation blocking/allowing recheck.
- Prompt/report limitation wording recheck if generation is allowed.
- No raw body / no credential leakage recheck.
- No payload JSON / generated report body evidence recheck.
- Browser rendering recheck if implementation later changes UI.

Evidence must remain status-level and follow Step 86 / Step 87 redaction
rules.

## Human Decision Checklist

- [ ] Decide whether a completely empty GA4 response should be a blocking
      error.
- [ ] Decide whether partial empty data should be a warning state.
- [ ] Decide whether OpenAI generation should be blocked when all presets are
      empty.
- [ ] Decide whether OpenAI generation should be allowed when summary metrics
      exist but detail rows are empty.
- [ ] Decide whether comparison-period-only empty data should allow report
      generation.
- [ ] Decide whether current-period-only empty data should block report
      generation.
- [ ] Decide whether no-data status metadata should be included in the AI
      payload.
- [ ] Decide whether user-facing warning wording should be added.
- [ ] Decide whether Payload Preview should display category-level no-data
      warnings outside raw JSON.
- [ ] Decide whether Step 90 should move into implementation planning.

## Release Blockers / Follow-up Decisions

| Blocker / Decision Item | Status After Step 89 | Notes |
|---|---|---|
| GA4 empty/no-data handling not implemented | Hold | Step 89 is decision-only and does not change runtime behavior. |
| External API error-path QA needs recheck after no-data handling decision | Hold | Step 88 GA4-07 needs recheck after any implementation. |
| Support/debug redaction guidance not final | Needs review | Redaction guidance remains draft material. |
| AI Payload Preview JSON visibility not final | Hold | No-data warnings may reduce reliance on raw JSON, but visibility decision remains open. |
| Generated report handling policy not final | Needs human decision | No-data generation rules affect whether generated reports should mention limitations. |
| AI payload category acceptance not final | Hold | No-data metadata would be a new payload category if implemented. |
| External services / privacy wording not release-finalized | Hold | Future wording may need to disclose no-data warnings and AI payload status metadata. |
| OAuth / token lifecycle strategy unresolved | Hold | Manual Google Access Token entry remains developer-verification oriented. |
| OpenAI API Key storage strategy unresolved | Hold | Settings-based key storage needs explicit acceptance or redesign. |
| Uninstall credential cleanup policy unresolved | Hold | Credential-bearing settings cleanup still needs a release decision. |
| Plugin Check / PHPCS refresh not executed | Needs review | Tooling refresh remains later release-readiness work. |
| Release package contents not reviewed | Needs review | Package contents and secret/data scan remain later work. |
| WordPress.org release remains Hold | Hold | Release readiness should not proceed until blockers are closed or explicitly deferred. |

## Recommended Next Step

Recommended next step:

```text
Step 90: GA4 empty/no-data handling implementation plan
```

Rationale:

- Step 89 recommends Option E but requires human approval before code changes.
- An implementation plan should define the exact no-data classification rules,
  payload metadata shape, UI warning behavior, generation block/allow rules,
  and QA recheck matrix before touching production code.
- If the human decision rejects Option E, Step 90 should instead document the
  selected option and its narrower implementation plan.

## Existing Docs Referenced

- `docs/maturation/step75-error-handling-qa-checklist.md`
- `docs/maturation/step76-error-handling-qa-phase1-results.md`
- `docs/maturation/step77-external-api-error-path-qa-checklist.md`
- `docs/maturation/step78-data-minimization-privacy-review.md`
- `docs/maturation/step82-external-services-privacy-disclosure-draft.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`
- `docs/maturation/step87-external-api-error-path-qa-execution-plan.md`
- `docs/maturation/step88-external-api-error-path-qa-controlled-execution-results.md`

## Outcome

- Step 88 GA4-07 finding recap: documented.
- Empty/no-data definitions: documented.
- Handling option comparison: documented.
- Recommended candidate posture: Option E preferred, Option C/D fallback,
  Option A not preferred for completely empty data.
- Candidate user-facing status categories: documented.
- Payload / OpenAI generation implications: documented.
- QA follow-up plan: documented.
- Human decision checklist: documented.
- Release blockers and follow-up decisions: documented.
- Production code changed: no.
- `readme.txt` changed: no.
- Admin UI, JavaScript, and CSS changed: no.
- External API calls performed: no.
- Real credentials, identifiers, analytics values, page paths, traffic source
  values, city values, request bodies, payload bodies, raw responses, generated
  reports, nonces, cookies, and sessions recorded: no.
- WordPress.org release position: `Hold`.
