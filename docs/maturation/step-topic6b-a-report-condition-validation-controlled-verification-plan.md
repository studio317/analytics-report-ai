# Topic 6B-A: Report Condition Validation Controlled Verification Plan

## 1. Step Purpose

Topic 6B-A covers Topic 6A scenario A: report condition validation failure in the integrated `create_ai_report` flow.

This is a docs-only / planning-only step. It prepares a human browser confirmation plan for `wp-dev`; it does not execute browser operations, OAuth, GA4 Fetch, AI Generate, external communication, Plugin Check, commits, pushes, package work, or WordPress.org / SVN operations.

## 2. Baseline Classification

- Topic 6A plan committed baseline: Confirmed by latest commit `docs(qa): plan integrated report error path checks`.
- Working tree at start: Clean.
- Production code / translation / CSS / JS changes: None.
- Settings, Report Builder UI, action, nonce, payload, transient, TTL changes: None.
- Credential, token, option, transient, GA4 value, AI payload, generated report body inspection: Not performed.

## 3. Selected Invalid Condition

Selected validation case:

```text
Start date is later than end date.
```

Source-level validation basis:

- `validate_report_conditions()` reads `start_date` and `end_date` from normalized form values.
- It first checks that both are valid date strings.
- If both are valid and `start_date > end_date`, it adds the safe validation error:

```text
End date must be the same as or later than start date.
```

This case is safe because it fails before any Google credential lookup, GA4 client call, payload build, payload transient storage, or AI Client call.

Recommended human test values:

- Use any normal valid existing conditions as the baseline.
- Change only the date relationship so that start date is later than end date.
- Do not record or share actual site settings, GA4 values, credential state, option values, transient values, payloads, or generated report content.

## 4. Source-Level Stop Boundary

The current `create_ai_report` request path is:

```text
maybe_handle_request()
→ nonce/capability checks
→ sanitize POST input
→ normalize_report_input()
→ execute_ai_report_from_conditions()
→ prepare_payload_from_report_conditions()
→ validate_report_conditions()
```

For validation failure:

- `prepare_payload_from_report_conditions()` returns the validation result immediately when `validate_report_conditions()` returns `status = error`.
- `fetch_ga4_data_for_conditions()` is not called.
- `build_payload_from_ga4_result()` is not called.
- `store_payload_for_generation()` is not called.
- `generate_ai_report_from_payload()` is not called.
- `Analytics_Report_AI_GA4_Client` is not reached.
- `Analytics_Report_AI_AI_Client` is not reached.

Expected boundary classification:

```text
Stop boundary: validate_report_conditions()
GA4 Fetch: Not called
AI Generate: Not called
Payload transient write: Not expected
```

## 5. Form Values Preservation Basis

Validation failure returns:

```text
status: error
errors: safe validation messages
form_values: original normalized form values
```

`render_page()` merges `submission_result['form_values']` into the default form values before rendering the form. Therefore the following fields should remain visible after the failed submission:

- Start date
- End date
- Comparison
- Data scope
- Path

For the selected date-order case, the intentionally invalid start/end values should remain visible so the user can correct them without the form resetting to defaults.

## 6. Safe Notice and Non-Display Basis

Validation failure uses the generic error notice renderer:

```text
Please fix the following errors.
```

The notice should list the safe validation message for the invalid date relationship.

The validation notice should not include:

- GA4 request or response data
- AI request or response data
- AI payload JSON
- credentials
- OAuth tokens
- OAuth client ID / secret values
- option values
- transient values
- internal exception details
- generated report text

Success notice should not render because the status is not `report_generated`.

Generated report draft should not render because `render_generated_report()` only renders for `status = report_generated`.

The old Preview UI should not render because Topic 5 removed the Report Builder Data Preview / AI Data Preview rendering path.

## 7. Human Browser Confirmation Procedure

CODEX does not perform this browser check. The human operator may perform the following in controlled `wp-dev` only.

1. Open the WordPress admin in the controlled `wp-dev` environment.
2. Navigate to Studio317 Report Drafts for Google Analytics > Report Builder.
3. Start from otherwise normal report conditions.
4. Change only the date relationship:
   - Set Start Date to a valid date later than End Date.
   - Keep Comparison, Data Scope, and Path in their current intended states.
5. Click `AIレポートを作成` exactly once.
6. Observe only the visible page result.

Do not use:

- screenshots,
- browser Developer Tools,
- Network tab,
- cookies/session/nonce inspection,
- option/transient inspection,
- database dumps,
- credential/token inspection,
- GA4 Fetch or AI provider evidence outside the visible UI.

## 8. Expected Human Observations

Expected result:

- A validation error corresponding to the invalid date relationship is visible.
- The page does not show a success notice.
- The generated report draft area is not shown.
- Start Date remains the intentionally later date.
- End Date remains the intentionally earlier date.
- Comparison remains the selected value.
- Data Scope remains the selected value.
- Path remains unchanged.
- No Google connection, GA4, or AI provider error appears for this validation-only case.
- GA4 Data Preview is not shown.
- AI Payload Preview is not shown.
- GA4 values are not shown.
- AI payload JSON is not shown.
- Generated report body is not shown.

Expected internal boundary, inferred from source-level review:

```text
Validation failure stops before GA4 Fetch and before AI Generate.
```

## 9. Failure Judgment

Classify the human browser check as failed if any of these are observed:

- The invalid date relationship does not produce a validation error.
- The form resets unexpectedly to default start/end dates.
- Comparison, Data Scope, or Path resets unexpectedly.
- A success notice appears.
- A generated report draft appears.
- GA4 Data Preview or AI Payload Preview appears.
- GA4 values, AI payload JSON, request/response details, credentials, tokens, option values, or generated report content are displayed.
- A Google connection, GA4 Fetch, or AI provider error appears instead of the date validation error.
- The operator feels they need Network, screenshot, option, transient, credential, or raw response evidence to continue.

If a failure condition appears, stop the browser check and record only status/category-level observations.

## 10. Cleanup / Reset After Human Check

No fixture, helper, option change, transient change, or external communication is planned.

After observation, the human operator may manually restore the start/end dates to the intended valid range in the form if they want to continue using the page. Saving settings or running other actions is not required for cleanup because this scenario only changes unsaved form inputs and submits a validation-failing request.

## 11. Prohibited Operations

Not allowed for Topic 6B-A:

- production PHP / JS / CSS / translation / readme changes,
- existing docs edits,
- Settings save,
- Report Builder UI changes,
- action / nonce changes,
- payload / transient / TTL changes,
- browser operation by CODEX,
- OAuth Connect / Reconnect / Disconnect,
- GA4 Fetch,
- AI Generate,
- external API communication,
- Plugin Check,
- commit / push / SVN operation,
- credential/token/option/transient/GA4 value/payload/generated body inspection.

## 12. Result Classification

```text
Topic 6B-A report condition validation controlled verification plan: Completed
Selected invalid condition: start date later than end date
Expected stop boundary: validate_report_conditions()
Expected GA4 Fetch: Not called
Expected AI Generate: Not called
Human browser execution: Not performed by CODEX
Production changes: None
```
