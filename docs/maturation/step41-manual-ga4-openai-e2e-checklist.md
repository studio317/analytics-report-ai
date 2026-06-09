# Step 41 Manual GA4 / OpenAI E2E Test Checklist

## 1. Overview

This step is a docs-only checklist for a future manual end-to-end test that uses
real Google Analytics Data API and OpenAI API communication.

No real GA4 request, OpenAI request, or other external API request is performed
in Step 41 itself. This document only defines how a future administrator should
run the E2E verification safely and what evidence may be recorded.

The intended E2E flow is:

1. Configure Settings with a GA4 Property ID, Google Access Token, and OpenAI API
   Key.
2. Fetch GA4 data from Report Builder.
3. Review the AI Payload Preview.
4. Generate an AI report draft with OpenAI.
5. Edit the textarea and verify copy behavior.
6. Clear credentials and clean up temporary test state.

## 2. Test Scope

In scope for a future manual E2E run:

- Settings credential setup.
- GA4 fetch with a small, safe test date range.
- AI Payload Preview display and review.
- OpenAI report generation after payload review.
- Generated report textarea editing.
- Copy Report Text behavior.
- Credential clearing after the test.
- Normalized GA4 and OpenAI error handling checks.
- Cost, quota, and external-service notices.
- Static quality baseline after E2E.

Out of scope:

- Implementing Google OAuth.
- Refresh token handling.
- Credential storage redesign.
- WordPress.org publication, SVN operations, or GitHub release operations.
- Production user testing.
- Recording secrets.
- Saving full GA4 raw responses.
- Saving full AI payload JSON.
- Saving full OpenAI request or response bodies.
- Saving full generated report text when it contains sensitive analytics.

## 3. Safety Rules

These rules apply to the future E2E run and to any evidence produced from it:

- Real Google Access Tokens must not be pasted into docs, commit messages,
  screenshots, logs, chat, issue trackers, or pull request descriptions.
- Real OpenAI API Keys must not be pasted into docs, commit messages,
  screenshots, logs, chat, issue trackers, or pull request descriptions.
- Authorization headers must not be recorded.
- Full request bodies must not be recorded.
- Full GA4 raw responses must not be recorded.
- Full AI payload JSON must not be recorded.
- Full OpenAI raw responses must not be recorded.
- Full generated report text should not be recorded if it contains sensitive
  analytics, page paths, traffic sources, city data, or business context.
- Screenshots must be reviewed before saving or sharing. Do not keep screenshots
  that reveal secrets, full payloads, full generated text, or sensitive
  analytics.
- Evidence should be status-level: for example, `GA4 fetch succeeded`,
  `Payload preview rendered`, `AI generation succeeded`, or `Copy button copied
  edited textarea content`.
- If counts are useful, record only non-sensitive high-level counts such as
  `top_pages rows rendered: 10`; do not copy row contents unless they are known
  safe and redacted.
- OpenAI API usage may incur cost. Use a small date range and a safe test
  property.
- Stop immediately if a secret or full payload appears in UI, logs, screenshots,
  terminal output, or docs.

## 4. Preconditions

- WordPress test environment: `/var/www/html/wp-dev`.
- Plugin source: `/var/www/html/analytics-report-ai`.
- Admin user has `manage_options`.
- Plugin is active.
- Test GA4 Property ID is available.
- A valid temporary Google Access Token is available.
- A valid OpenAI API Key is available. A restricted key with only the required
  capabilities is preferred.
- Test date range is short, ideally 1 to 3 days.
- Test GA4 property does not contain highly sensitive production analytics, or
  the tester has explicit approval to use it.
- Host Name Filter and Host Name policy are understood before the test.
- Browser dev console and PHP error log are available for local review.
- OpenAI usage dashboard can be checked separately if cost verification is
  needed.
- No production secrets are saved into the repository or documentation.
- The tester has a secure temporary place to retrieve credentials without
  copying them into notes.

## 5. E2E Test Index

| Test ID | Area | Purpose |
| --- | --- | --- |
| E2E-001 | Settings | Save required GA4 and OpenAI settings. |
| E2E-002 | Settings | Verify credential values are not redisplayed. |
| E2E-010 | GA4 success | Fetch GA4 data for whole-site scope. |
| E2E-011 | GA4 success | Fetch GA4 data with directory scope. |
| E2E-012 | GA4 success | Fetch GA4 data with page scope. |
| E2E-020 | Payload preview | Review payload preview notices and rendered sections. |
| E2E-021 | Payload preview | Verify credentials and raw responses are not displayed. |
| E2E-030 | OpenAI success | Generate an AI report after payload review. |
| E2E-031 | OpenAI success | Verify generated text shape and editability. |
| E2E-040 | Textarea / copy | Verify copy uses the current textarea value. |
| E2E-050 | Cleanup | Clear saved credentials. |
| E2E-060 | GA4 error | Check expired or invalid Google token handling. |
| E2E-061 | GA4 error | Check invalid GA4 Property ID handling. |
| E2E-062 | GA4 edge | Check no-data or restrictive host filter behavior. |
| E2E-070 | OpenAI error | Check invalid OpenAI key handling. |
| E2E-071 | OpenAI error | Check restricted key missing required permission. |
| E2E-080 | Post-test cleanup | Confirm no secrets were recorded and clear local evidence. |
| E2E-090 | Static baseline | Re-run PHP lint, PHPCS, dry-run package, and Plugin Check. |

## 6. Settings Credential Setup Checks

### E2E-001: Save Required Settings

- Area: Settings.
- Preconditions: Admin user is logged in; real test credentials are available
  outside the repository and outside this document.
- Steps:
  1. Open Analytics Report AI > Settings.
  2. Enter the numeric GA4 Property ID.
  3. Enter the temporary Google Access Token.
  4. Configure Host Name Filter and Host Name according to the test property.
  5. Enter the OpenAI API Key.
  6. Save settings.
- Expected result: Settings save successfully and the page returns a success
  notice.
- Evidence to record: `Settings saved successfully`; saved/not saved status for
  each credential; whether Host Name Filter is enabled.
- What must not be recorded: Credential values, token prefixes, API key prefixes,
  screenshots showing credential inputs, option dumps, database rows, or server
  logs containing secrets.
- Notes / risk: Credential storage remains MVP-only and should be cleared after
  the test.

### E2E-002: Verify Credential Non-Redisplay

- Area: Settings.
- Preconditions: E2E-001 completed.
- Steps:
  1. Reopen Settings.
  2. Inspect Google Access Token and OpenAI API Key inputs.
  3. Inspect saved/not saved status text.
- Expected result: Saved status is visible, but credential values are not
  redisplayed in input values or page text.
- Evidence to record: `Google Access Token status: saved`; `OpenAI API Key
  status: saved`.
- What must not be recorded: Screenshots or DOM extracts that include real
  credential values.
- Notes / risk: Stop the test if any saved credential value is displayed.

### E2E-003: Confirm Notices Before E2E

- Area: Settings disclosure.
- Preconditions: Settings page is open.
- Steps:
  1. Confirm External service usage notice is visible.
  2. Confirm Credential Storage (MVP) notice is visible.
  3. Confirm public-use redesign warning is visible.
- Expected result: The tester sees the disclosure and understands that the E2E
  run will use real external services and MVP credential storage.
- Evidence to record: `External service notice visible`; `Credential storage
  notice visible`.
- What must not be recorded: Full settings page screenshots if they include
  sensitive values.
- Notes / risk: This check should happen before sending real requests.

## 7. GA4 Fetch Success Checks

### E2E-010: Whole-Site GA4 Fetch

- Area: GA4 fetch.
- Preconditions: Required settings are saved; test date range is 1 to 3 days;
  tester is prepared for a real GA4 API request.
- Steps:
  1. Open Report Builder.
  2. Select a short safe date range.
  3. Select comparison option.
  4. Select Whole site scope.
  5. Click Fetch GA4 Data.
- Expected result: GA4 preset reports are fetched and AI Payload Preview appears.
- Evidence to record: `GA4 fetch succeeded`; selected date range; selected
  scope; high-level rendered row counts if useful.
- What must not be recorded: Google Access Token, Authorization header, request
  body, full GA4 raw response, full payload JSON, detailed page path rows, or
  traffic-source rows unless safely redacted.
- Notes / risk: This sends a real request to Google Analytics Data API.

### E2E-011: Directory Scope GA4 Fetch

- Area: GA4 fetch.
- Preconditions: E2E-010 succeeded; a safe test directory path is known.
- Steps:
  1. Select Directory scope.
  2. Enter a safe test path such as `/blog/` if appropriate for the property.
  3. Use a short date range.
  4. Click Fetch GA4 Data.
- Expected result: Payload Preview appears for the directory-scoped report.
- Evidence to record: `Directory GA4 fetch succeeded`; normalized path; row
  counts if safe.
- What must not be recorded: Full page path lists, full payload JSON, raw GA4
  response, credential values, or Authorization headers.
- Notes / risk: Use only paths that are safe to mention in high-level evidence.

### E2E-012: Page Scope GA4 Fetch

- Area: GA4 fetch.
- Preconditions: E2E-010 succeeded; a safe test page path is known.
- Steps:
  1. Select Page scope.
  2. Enter a safe test path such as `/about`.
  3. Use a short date range.
  4. Click Fetch GA4 Data.
- Expected result: Payload Preview appears for the page-scoped report, or a
  no-data state is handled safely if the page has no data.
- Evidence to record: `Page GA4 fetch succeeded` or `Page GA4 fetch returned no
  data safely`; normalized path.
- What must not be recorded: Full payload JSON, raw response, credentials, or
  sensitive page analytics.
- Notes / risk: Page scope can reveal business-sensitive page-level analytics.

## 8. Payload Preview Checks

### E2E-020: Payload Preview Sections

- Area: Payload preview.
- Preconditions: A GA4 fetch succeeded.
- Steps:
  1. Review conditions section.
  2. Review summary metrics.
  3. Review daily trend, top pages, traffic channels, traffic sources, and
     regional trends sections if rows are available.
  4. Expand JSON details only if necessary, without copying the full JSON.
- Expected result: Payload Preview renders and presents summarized/limited data
  rather than raw GA4 API responses.
- Evidence to record: Which sections rendered; high-level row counts; whether
  comparison period appears.
- What must not be recorded: Full payload JSON, full row contents, raw GA4
  response, request body, or credentials.
- Notes / risk: Page paths and traffic data may still be sensitive even without
  credentials.

### E2E-021: Payload Safety Review

- Area: Payload preview.
- Preconditions: Payload Preview is visible.
- Steps:
  1. Confirm the preview states that credentials are not included.
  2. Confirm it warns that analytics information may be sensitive.
  3. Confirm GA4 Property ID, WordPress user information, cookies, and IP
     addresses are not displayed as part of the AI payload by design.
- Expected result: Payload safety notice is visible and no credential values are
  present.
- Evidence to record: `Payload credential exclusion notice visible`; `No
  credential values observed`.
- What must not be recorded: Full payload JSON or screenshots showing sensitive
  analytics.
- Notes / risk: Stop if credentials appear in payload preview.

### E2E-022: Payload Transient State

- Area: Payload preview.
- Preconditions: Payload Preview is visible.
- Steps:
  1. Confirm temporary payload retention notice appears.
  2. Note the displayed expiration window in minutes.
- Expected result: UI explains that the reviewed payload is stored temporarily
  and expires automatically.
- Evidence to record: Expiration duration only.
- What must not be recorded: Transient contents, database row dumps, or full
  payload body.
- Notes / risk: Do not inspect transient values in a way that prints payloads.

## 9. OpenAI Generation Checks

### E2E-030: Generate AI Report

- Area: OpenAI generation.
- Preconditions: Payload Preview is visible; tester has reviewed payload; tester
  is prepared for OpenAI API cost.
- Steps:
  1. Confirm usage/cost warning near the Generate AI Report action.
  2. Click Generate AI Report.
  3. Wait for the response.
- Expected result: A Japanese plain-text report draft appears in the textarea.
- Evidence to record: `OpenAI generation succeeded`; response latency range if
  useful; whether a textarea appeared.
- What must not be recorded: OpenAI API Key, Authorization header, full request
  body, full AI payload, full OpenAI raw response, or full generated report.
- Notes / risk: This sends the reviewed payload to OpenAI API and may incur
  cost.

### E2E-031: Generated Text Shape

- Area: OpenAI output.
- Preconditions: E2E-030 succeeded.
- Steps:
  1. Inspect the generated report.
  2. Confirm it is Japanese plain text.
  3. Confirm it is not Markdown, HTML, a table, or a code block.
  4. Confirm it does not claim information absent from the payload, as far as
     the tester can reasonably assess without recording the payload.
- Expected result: Text follows the current prompt policy and reads as an
  editable draft.
- Evidence to record: Status-level result such as `Japanese plain-text draft
  appeared`; optionally a very short redacted excerpt if it contains no
  sensitive analytics.
- What must not be recorded: Full generated report, sensitive analytics, full
  payload, or raw OpenAI response.
- Notes / risk: Generated text is a draft and must be reviewed by a human.

### E2E-032: Review Warning After Generation

- Area: OpenAI output.
- Preconditions: Generated report textarea is visible.
- Steps:
  1. Confirm UI says the generated report text is not saved.
  2. Confirm UI says it can be edited and copied.
- Expected result: Report draft workflow is understandable.
- Evidence to record: `Generated report draft notice visible`.
- What must not be recorded: Full generated report text.
- Notes / risk: Confirm persistence behavior separately if needed, without
  storing sensitive report text.

## 10. Textarea and Copy Checks

### E2E-040: Textarea Editing

- Area: Textarea.
- Preconditions: Generated report textarea is visible.
- Steps:
  1. Make a small safe edit to the textarea.
  2. Confirm the edit remains in the textarea.
- Expected result: The generated report is editable.
- Evidence to record: `Textarea edit succeeded`.
- What must not be recorded: Full generated report text.
- Notes / risk: Use a harmless marker only if evidence is needed.

### E2E-041: Copy Current Textarea Value

- Area: Copy behavior.
- Preconditions: Generated report textarea is visible.
- Steps:
  1. Click Copy Report Text.
  2. Paste into a temporary local scratch buffer.
  3. Confirm copied text matches the current textarea value.
  4. Delete the scratch buffer after verification.
- Expected result: Copied text equals the edited textarea content.
- Evidence to record: `Copy succeeded`; `Copied content matched current
  textarea`.
- What must not be recorded: Full copied report text or screenshots containing
  report contents.
- Notes / risk: Clear local clipboard / scratch data if it contains sensitive
  analytics.

## 11. Error Handling Checks

Use a small date range and safe test data for error scenarios. Record only
status-level evidence and normalized messages. Do not record raw external
responses.

### E2E-060: Expired or Invalid Google Token

- Area: GA4 error handling.
- Preconditions: Tester can safely use an expired or dummy Google token.
- Steps:
  1. Save an expired or invalid Google Access Token.
  2. Attempt a short GA4 fetch.
- Expected result: A normalized GA4 credential or permission error appears. No
  token, Authorization header, or raw API response is displayed.
- Evidence to record: Error category and sanitized user-facing message summary.
- What must not be recorded: Token value, response body, request body, or
  Authorization header.
- Notes / risk: Restore or clear credentials after the check.

### E2E-061: Invalid GA4 Property ID

- Area: GA4 error handling.
- Preconditions: A harmless invalid numeric property ID can be used.
- Steps:
  1. Save an invalid numeric GA4 Property ID.
  2. Attempt a short GA4 fetch.
- Expected result: A normalized GA4 error appears without raw response leakage.
- Evidence to record: `Invalid property ID handled safely`.
- What must not be recorded: Raw GA4 response, request body, token, or
  Authorization header.
- Notes / risk: Settings-level measurement ID rejection is separate from this
  remote API error scenario.

### E2E-062: Host Filter No-Data Scenario

- Area: GA4 edge handling.
- Preconditions: A safe host filter value is known to produce no data, or the
  tester can use a safe local mismatch.
- Steps:
  1. Enable Host Name Filter with a safe no-data value.
  2. Run a short GA4 fetch.
- Expected result: No-data or sparse-data behavior is handled without fatal
  errors and without raw API dumps.
- Evidence to record: `No-data host filter scenario handled safely`; visible
  status or normalized message.
- What must not be recorded: Raw GA4 response or full payload.
- Notes / risk: Confirm this does not accidentally test sensitive production
  host names.

### E2E-070: Invalid OpenAI API Key

- Area: OpenAI error handling.
- Preconditions: A payload preview exists; tester can safely use an invalid
  OpenAI key.
- Steps:
  1. Save an invalid OpenAI API Key.
  2. Attempt Generate AI Report.
- Expected result: A normalized OpenAI authentication error appears. No key,
  Authorization header, request body, payload, or raw response is displayed.
- Evidence to record: Error category and sanitized message summary.
- What must not be recorded: API key, Authorization header, full request body,
  full payload, or raw OpenAI response.
- Notes / risk: Restore or clear credentials after the check.

### E2E-071: Restricted OpenAI Key Missing Permission

- Area: OpenAI error handling.
- Preconditions: A restricted OpenAI key can be configured without exposing it.
- Steps:
  1. Configure a restricted key missing the required Responses permission.
  2. Attempt Generate AI Report.
- Expected result: A normalized permission error explains restricted key /
  Responses permission needs without leaking secrets.
- Evidence to record: `Restricted key permission error handled safely`.
- What must not be recorded: API key, Authorization header, request body, full
  payload, or raw response.
- Notes / risk: This may incur a failed-request entry in provider logs.

### E2E-072: Transport Failure If Safely Simulatable

- Area: External API transport.
- Preconditions: Tester can safely simulate network failure without affecting
  unrelated services.
- Steps:
  1. Simulate GA4 or OpenAI transport failure in a controlled local environment.
  2. Trigger the corresponding action.
- Expected result: User-facing message is normalized and no raw request or
  secret material is printed.
- Evidence to record: `Transport failure handled safely`.
- What must not be recorded: Request bodies, headers, tokens, keys, or raw
  response dumps.
- Notes / risk: Skip if simulation would disrupt the environment.

## 12. Credential Clearing and Cleanup

### E2E-080: Clear Google Access Token

- Area: Cleanup.
- Preconditions: Google Access Token was saved for E2E.
- Steps:
  1. Open Settings.
  2. Check clear Google Access Token.
  3. Save settings.
  4. Reopen Settings.
- Expected result: Google Access Token status changes to not saved and the value
  is not redisplayed.
- Evidence to record: `Google Access Token cleared`.
- What must not be recorded: Token value or option dumps.
- Notes / risk: This should be performed before ending E2E.

### E2E-081: Clear OpenAI API Key

- Area: Cleanup.
- Preconditions: OpenAI API Key was saved for E2E.
- Steps:
  1. Open Settings.
  2. Check clear OpenAI API Key.
  3. Save settings.
  4. Reopen Settings.
- Expected result: OpenAI API Key status changes to not saved and the value is
  not redisplayed.
- Evidence to record: `OpenAI API Key cleared`.
- What must not be recorded: API key value or option dumps.
- Notes / risk: This should be performed before ending E2E.

### E2E-082: Clear Temporary Local Evidence

- Area: Cleanup.
- Preconditions: E2E evidence was collected.
- Steps:
  1. Review screenshots and notes for secrets.
  2. Delete any screenshot or scratch file containing payloads, raw responses,
     generated reports, or sensitive analytics.
  3. Clear clipboard or temporary buffers if they contain report text.
- Expected result: Only sanitized status-level evidence remains.
- Evidence to record: `Redaction review completed`.
- What must not be recorded: Any secret, full payload, raw response, request
  body, or full report.
- Notes / risk: Treat screenshots as sensitive until reviewed.

### E2E-083: Optional Transient Cleanup

- Area: Cleanup.
- Preconditions: A safe local WP-CLI cleanup command is available and does not
  print transient values.
- Steps:
  1. Clear only the current test user's Analytics Report AI payload transient if
     needed.
  2. Do not print transient contents.
- Expected result: Temporary payload state is removed without exposing payload
  data.
- Evidence to record: `Payload transient cleared`, if performed.
- What must not be recorded: Transient contents or payload JSON.
- Notes / risk: Skip if the cleanup command is not known safe.

## 13. Static Baseline After E2E

### E2E-090: PHP Lint

- Area: Static baseline.
- Preconditions: E2E actions are complete.
- Steps:
  1. Run `php -l analytics-report-ai.php`.
  2. Run `find includes -name '*.php' -print0 | xargs -0 -n1 php -l`.
- Expected result: PHP syntax checks remain clean.
- Evidence to record: Command names and clean result summary.
- What must not be recorded: Logs containing secrets.
- Notes / risk: No external request is made.

### E2E-091: WPCS / PHPCS

- Area: Static baseline.
- Preconditions: Local Composer dev tooling is available.
- Steps:
  1. Run `vendor/bin/phpcs -ps`.
  2. Run `vendor/bin/phpcs --report=summary`.
- Expected result: `0 errors / 0 warnings`.
- Evidence to record: `PHPCS clean`.
- What must not be recorded: Any unrelated local files or secrets.
- Notes / risk: Do not run `phpcbf`.

### E2E-092: Dry-Run Release Package

- Area: Packaging.
- Preconditions: E2E actions are complete.
- Steps:
  1. Run `bash -n tools/build-release-zip-dry-run.sh`.
  2. Run `./tools/build-release-zip-dry-run.sh`.
  3. Confirm tooling files are excluded from the zip.
- Expected result: Dry-run package is generated successfully and contains
  runtime files only.
- Evidence to record: Dry-run package success and no-output exclusion grep.
- What must not be recorded: Credential scan matched values, payloads, or full
  generated reports.
- Notes / risk: The dry-run script should not print matched credential values.

### E2E-093: Plugin Check

- Area: Static baseline.
- Preconditions: Dry-run stage exists.
- Steps:
  1. Run Plugin Check against
     `build/release-dry-run/stage/analytics-report-ai`.
- Expected result: Plugin Check remains clean.
- Evidence to record: `Plugin Check clean`.
- What must not be recorded: Secret-containing logs.
- Notes / risk: Run against the staged package.

### E2E-094: Repository Secret Review

- Area: Repository safety.
- Preconditions: E2E notes and local files have been reviewed.
- Steps:
  1. Run `git status --short --untracked-files=all`.
  2. Confirm no accidental screenshots, logs, exported payloads, raw responses,
     generated reports, or secret files appear.
  3. Remove unsafe local files before committing anything.
- Expected result: Only intentional source or docs changes are present, and no
  secret-bearing files are tracked or untracked for commit.
- Evidence to record: `No secret-bearing files in git status`.
- What must not be recorded: File contents containing secrets.
- Notes / risk: Stop before commit if any unexpected file appears.

## 14. Evidence Recording Template

Use this template for future manual execution. Do not paste raw data into the
table.

| Test ID | Result | Time | Environment | Evidence summary | Redaction check done | Follow-up issue |
| --- | --- | --- | --- | --- | --- | --- |
| E2E-001 | Pass / Fail / Blocked / Not tested | YYYY-MM-DD HH:MM TZ | `/var/www/html/wp-dev` | Status-level summary only. | Yes / No | Issue or next step. |

Allowed evidence examples:

- `Settings saved; credential statuses show saved without values.`
- `GA4 fetch succeeded for 2026-06-01 to 2026-06-03; payload preview rendered.`
- `Payload preview rendered 5 sections; full JSON not recorded.`
- `OpenAI generation succeeded; editable textarea appeared.`
- `Copy button copied edited textarea value; scratch buffer deleted.`
- `Credentials cleared after test.`

Disallowed evidence examples:

- Real access token or API key.
- Authorization header.
- Full GA4 raw response.
- Full AI payload JSON.
- Full OpenAI request or response body.
- Full generated report text containing analytics.
- Screenshots showing secrets, full payloads, or full generated reports.

## 15. Recommended Stop Conditions

Stop the E2E run immediately if any of these occur:

- A secret appears in the UI, logs, screenshots, terminal output, docs, or test
  notes.
- A raw Authorization header appears anywhere visible or recorded.
- A full request body appears unexpectedly.
- A full GA4 raw response appears unexpectedly.
- A full AI payload appears unexpectedly outside the intended admin preview.
- A full OpenAI raw response appears unexpectedly.
- OpenAI usage or cost looks unexpectedly high.
- GA4 quota usage looks unexpectedly high.
- Plugin Check or PHPCS baseline changes unexpectedly.
- A generated report includes sensitive analytics that cannot be safely
  redacted.

After stopping:

1. Clear credentials if possible.
2. Delete unsafe screenshots, logs, and scratch files.
3. Do not commit anything until the repository and evidence are reviewed.
4. Record only a sanitized stop reason.

## 16. Step 41 Safety Notes

This Step 41 document creation does not perform GA4 API communication, OpenAI
API communication, other external API communication, Composer install/update,
`phpcbf`, SVN operations, GitHub release operations, or WordPress.org
publication actions.

This document intentionally does not include credential values, access tokens,
OpenAI API keys, Authorization headers, full request bodies, full AI payload
bodies, full GA4 raw responses, full OpenAI raw responses, or full generated
report text.
