# Step 40 Admin Smoke Test Checklist

## 1. Overview

This step is a docs-only admin UI smoke test checklist for Analytics Report AI.

It is intended for manual WordPress admin verification after the Step 39 clean
quality baseline. It covers admin page rendering, settings UI, validation,
permissions, notices, JavaScript behavior, and non-network state handling.

GA4 Data API calls and OpenAI API calls are out of scope for Step 40. Any test
that would send a real GA4 or OpenAI request must be skipped, marked as blocked,
or run only in a controlled stub environment. Real end-to-end external API
testing is deferred to Step 41 or later.

No production PHP, JavaScript, CSS, `readme.txt`, plugin metadata, tooling
configuration, version, or release script is changed by this step.

## 2. Test Scope

In scope:

- WordPress admin menu rendering.
- Settings screen rendering and form behavior.
- Report Builder initial state.
- Date, scope, path, comparison, nonce, and permission validation paths that can
  be checked without external API calls.
- JavaScript scope switching, localized strings, confirm prompts, single-submit
  guard, textarea copy behavior where visible.
- Payload state messages that can be observed without triggering real external
  requests.
- Static quality baseline confirmation.

Out of scope:

- Real GA4 Data API requests.
- Real OpenAI API requests.
- Google OAuth implementation.
- Credential storage redesign.
- WordPress.org publication, SVN operations, GitHub release operations, or final
  release packaging.
- Recording credential values, Authorization headers, full request bodies, or
  full AI payload bodies.

## 3. Preconditions

- Test site: `/var/www/html/wp-dev`.
- Plugin is available from `/var/www/html/analytics-report-ai`.
- Tester can log in as a WordPress admin user with `manage_options`.
- Browser dev console and PHP error logs can be checked if needed.
- Do not record real Google Access Tokens, OpenAI API Keys, Authorization
  headers, full request bodies, or full payload bodies in test notes.
- Do not click **Fetch GA4 Data** or **Generate AI Report** with real
  credentials during Step 40.
- If a stub or isolated non-network fixture is unavailable, mark external
  request-dependent checks as `Not tested` or `Blocked`.

## 4. Activation and Menu Checks

### ASM-001: Plugin Activation

- Area: Activation.
- Preconditions: Plugin is installed and inactive.
- Steps:
  1. Activate Analytics Report AI from the WordPress Plugins screen.
  2. Reload the admin dashboard.
- Expected result: Activation completes without PHP fatal errors, warnings, or
  notices visible in the admin UI.
- Notes / risk: Do not test network-wide activation as part of MVP smoke.

### ASM-002: Plugin Deactivation and Reactivation

- Area: Activation.
- Preconditions: Plugin is active.
- Steps:
  1. Deactivate the plugin.
  2. Reactivate the plugin.
  3. Reload the admin dashboard.
- Expected result: Deactivation and reactivation complete without visible PHP
  fatal errors, warnings, or notices.
- Notes / risk: Existing settings may remain; do not treat persistence as a
  failure unless it contradicts current product policy.

### ASM-003: Admin Menu Presence

- Area: Admin menu.
- Preconditions: Plugin is active and admin user is logged in.
- Steps:
  1. Inspect the left admin menu.
  2. Open the Analytics Report AI top-level menu.
- Expected result: The top-level menu appears, and the Report Builder and
  Settings screens are reachable.
- Notes / risk: Menu should require `manage_options`.

### ASM-004: Admin Page Rendering

- Area: Admin rendering.
- Preconditions: Admin user is logged in.
- Steps:
  1. Open Report Builder.
  2. Open Settings.
  3. Check browser console and PHP error log if available.
- Expected result: Both screens render without JS errors, PHP fatal errors,
  warnings, or notices.
- Notes / risk: Do not click external-request buttons during this test.

## 5. Settings Screen Checks

### ASM-010: Settings Screen Baseline Render

- Area: Settings.
- Preconditions: Admin user is logged in.
- Steps:
  1. Open Analytics Report AI > Settings.
  2. Verify GA4 Property ID, Google Access Token, Host Name Filter, Host Name,
     and OpenAI API Key controls are visible.
- Expected result: Settings form renders with explanatory notices and no
  sensitive values displayed.
- Notes / risk: Credential fields may show saved status, but must not show saved
  credential values.

### ASM-011: External Service Usage Notice

- Area: Settings disclosure.
- Preconditions: Settings screen is open.
- Steps:
  1. Locate the external service usage notice.
  2. Confirm it identifies Google Analytics Data API and OpenAI API.
- Expected result: The notice explains which saved credentials are used and that
  settings should be reviewed before use.
- Notes / risk: Do not paste or record real credentials.

### ASM-012: Credential Storage MVP Notice

- Area: Settings disclosure.
- Preconditions: Settings screen is open.
- Steps:
  1. Locate the credential storage notice.
  2. Confirm it explains MVP database storage, non-redisplay, deletion
     checkboxes, access risk, and public-use redesign needs.
- Expected result: The notice makes clear that current storage is for MVP /
  developer verification and is not final for public or multi-user use.
- Notes / risk: This is a disclosure check only.

### ASM-013: Save Empty Credential Inputs

- Area: Settings credentials.
- Preconditions: Settings screen is open.
- Steps:
  1. Leave Google Access Token and OpenAI API Key inputs empty.
  2. Save settings.
  3. Reopen Settings.
- Expected result: Empty credential inputs do not reveal secrets. Existing saved
  credentials, if any, are retained unless a clear checkbox is selected.
- Notes / risk: If no credentials were saved before, status should remain not
  saved.

### ASM-014: Clear Saved Credential Controls

- Area: Settings credentials.
- Preconditions: A non-production dummy credential value may be saved in a safe
  local environment. Do not use real credentials for Step 40.
- Steps:
  1. Check the Google Access Token clear checkbox, if a dummy token is saved.
  2. Check the OpenAI API Key clear checkbox, if a dummy key is saved.
  3. Save settings and reopen the page.
- Expected result: Saved status changes to not saved for cleared credentials,
  and no credential value is redisplayed.
- Notes / risk: Use dummy values only, and do not record them in notes.

### ASM-015: GA4 Property ID Validation

- Area: Settings validation.
- Preconditions: Settings screen is open.
- Steps:
  1. Enter a measurement ID-shaped value such as `G-XXXXXXXXXX`.
  2. Save settings.
  3. Enter a numeric property ID-shaped value.
  4. Save settings.
- Expected result: Measurement ID-shaped values are rejected; numeric property
  ID-shaped values are accepted.
- Notes / risk: Use placeholder values only.

### ASM-016: Host Name Filter Controls

- Area: Settings host filter.
- Preconditions: Settings screen is open.
- Steps:
  1. Toggle Host Name Filter.
  2. Save with a host name.
  3. Save with an empty host name while the filter is enabled.
- Expected result: The host filter state and host name value are saved as
  designed. Empty host handling follows the current default-host restoration
  behavior.
- Notes / risk: Do not use sensitive internal host names in recorded evidence.

## 6. Report Builder Initial UI Checks

### ASM-020: Report Builder Baseline Render

- Area: Report Builder.
- Preconditions: Admin user is logged in.
- Steps:
  1. Open Analytics Report AI > Report Builder.
  2. Verify the Current Settings section is visible.
  3. Verify the report condition form is visible.
- Expected result: The page renders without a saved report body or payload
  preview unless a previous local test state intentionally exists.
- Notes / risk: Do not click Fetch GA4 Data in a real credential environment.

### ASM-021: Default Form State

- Area: Report Builder form.
- Preconditions: Report Builder is open.
- Steps:
  1. Inspect start date and end date.
  2. Inspect comparison radio buttons.
  3. Inspect data scope radio buttons.
- Expected result: Date defaults match the current previous-month default,
  comparison defaults to previous month, and data scope defaults to whole site.
- Notes / risk: Record only dates and UI state, not credentials.

### ASM-022: Usage and External Transmission Notices

- Area: Report Builder disclosure.
- Preconditions: Report Builder is open.
- Steps:
  1. Locate the usage/cost warning.
  2. Locate the GA4 external transmission explanation.
  3. Confirm the staged flow explains fetch, payload preview, and AI generation.
- Expected result: The page explains that GA4 fetch and OpenAI generation are
  separate actions and that payload review happens before AI generation.
- Notes / risk: This is a display check only.

### ASM-023: AI Button Initial State

- Area: Report Builder state.
- Preconditions: Report Builder is open with no freshly fetched payload.
- Steps:
  1. Inspect whether the Generate AI Report button is visible.
  2. Inspect whether the AI Payload Preview section is visible.
- Expected result: Generate AI Report should only appear when a valid payload
  preview is available under the current design.
- Notes / risk: Do not create a real payload via GA4 in Step 40.

## 7. Scope UI Checks

### ASM-030: Whole Site Scope

- Area: Scope UI.
- Preconditions: Report Builder is open.
- Steps:
  1. Select Whole site.
  2. Inspect the path input row.
- Expected result: Path field is hidden or disabled as designed and its value is
  cleared by admin JS.
- Notes / risk: Check browser console for JS errors.

### ASM-031: Directory Scope

- Area: Scope UI.
- Preconditions: Report Builder is open.
- Steps:
  1. Select Directory.
  2. Inspect path field visibility, placeholder, and help text.
- Expected result: Path field appears, placeholder is `/blog/`, and help text is
  the localized directory-scope description.
- Notes / risk: No external request is sent by this UI-only action.

### ASM-032: Page Scope

- Area: Scope UI.
- Preconditions: Report Builder is open.
- Steps:
  1. Select Page.
  2. Inspect path field visibility, placeholder, and help text.
- Expected result: Path field appears, placeholder is `/about`, and help text is
  the localized page-scope description.
- Notes / risk: No external request is sent by this UI-only action.

### ASM-033: Admin JS Safety

- Area: Admin JavaScript.
- Preconditions: Settings and Report Builder screens are loaded.
- Steps:
  1. Open browser dev console.
  2. Switch scope options.
  3. Interact with any visible confirm and copy controls without sending
     external requests.
- Expected result: No JavaScript errors are thrown.
- Notes / risk: Do not trigger real GA4 or OpenAI requests.

## 8. Input Validation Checks Without External API

These tests should be performed in a way that does not reach the GA4 fetch
client. If a submit would proceed to a real GA4 request, stop after confirming
client-side state or run only in a stubbed environment.

### ASM-040: End Date Before Start Date

- Area: Report Builder validation.
- Preconditions: Report Builder is open.
- Steps:
  1. Enter an end date earlier than the start date.
  2. Submit only if no real external credentials are configured, or use a stub.
- Expected result: The form reports a validation error and no external request
  is sent.
- Notes / risk: If real credentials are configured, mark as `Not tested` unless
  the environment blocks external requests.

### ASM-041: Date Range Longer Than Maximum

- Area: Report Builder validation.
- Preconditions: Report Builder is open.
- Steps:
  1. Enter a date range longer than the configured maximum report days.
  2. Submit only in a non-network or stubbed environment.
- Expected result: The form reports a maximum date-range validation error before
  any GA4 request.
- Notes / risk: Maximum is currently configured by plugin constants/helpers.

### ASM-042: Invalid Date Strings

- Area: Report Builder validation.
- Preconditions: Report Builder is open.
- Steps:
  1. Enter invalid date strings if the browser allows it, or use a direct local
     POST in a non-network environment.
- Expected result: Invalid dates are rejected safely.
- Notes / risk: Browser date inputs may prevent some invalid states.

### ASM-043: Full URL Path Rejection

- Area: Report Builder path validation.
- Preconditions: Report Builder is open.
- Steps:
  1. Select Directory or Page scope.
  2. Enter `https://example.com/blog/`.
  3. Submit only in a non-network or stubbed environment.
- Expected result: Full URL input is rejected before any external request.
- Notes / risk: Do not use real private URLs in evidence.

### ASM-044: Directory Path Normalization

- Area: Report Builder path validation.
- Preconditions: Report Builder is open.
- Steps:
  1. Select Directory scope.
  2. Enter `blog`.
  3. Submit only in a non-network or stubbed environment.
- Expected result: Path normalization produces `/blog/` as designed.
- Notes / risk: If submission would call GA4, mark as `Not tested` in Step 40.

### ASM-045: Page Path Normalization

- Area: Report Builder path validation.
- Preconditions: Report Builder is open.
- Steps:
  1. Select Page scope.
  2. Enter `about`.
  3. Submit only in a non-network or stubbed environment.
- Expected result: Path normalization produces `/about` as designed.
- Notes / risk: If submission would call GA4, mark as `Not tested` in Step 40.

### ASM-046: Query and Fragment Handling

- Area: Report Builder path validation.
- Preconditions: Report Builder is open.
- Steps:
  1. Select Directory or Page scope.
  2. Enter a path with query string and fragment, such as `/blog/?utm=x#top`.
  3. Submit only in a non-network or stubbed environment.
- Expected result: Query string and fragment are stripped if current path
  normalization supports it.
- Notes / risk: Confirm expected behavior against helper docs before treating a
  difference as a bug.

### ASM-047: Empty Path for Directory or Page

- Area: Report Builder path validation.
- Preconditions: Report Builder is open.
- Steps:
  1. Select Directory or Page scope.
  2. Leave path empty.
  3. Submit only in a non-network or stubbed environment.
- Expected result: Empty path is rejected before any external request.
- Notes / risk: This should not require real credentials.

### ASM-048: Invalid Comparison or Scope Values

- Area: Report Builder validation.
- Preconditions: Admin user can submit a controlled local request.
- Steps:
  1. Submit invalid comparison or scope values using browser dev tools or a
     local non-network POST.
- Expected result: Invalid values are rejected or reset safely as designed.
- Notes / risk: Include nonce and capability requirements when testing direct
  POST behavior.

## 9. Nonce and Permission Checks

### ASM-050: Direct POST Without Nonce

- Area: Nonce validation.
- Preconditions: Admin user is logged in.
- Steps:
  1. Submit a local POST to the Report Builder endpoint without the expected
     nonce.
- Expected result: The request fails safely with a nonce-related error and does
  not send external requests.
- Notes / risk: Do not include credentials or payload bodies in evidence.

### ASM-051: Direct POST With Invalid Nonce

- Area: Nonce validation.
- Preconditions: Admin user is logged in.
- Steps:
  1. Submit a local POST with an invalid nonce.
- Expected result: The request fails safely and no external request is sent.
- Notes / risk: Confirm no sensitive data appears in the error response.

### ASM-052: Non-Admin Access

- Area: Permissions.
- Preconditions: A non-admin test account is available.
- Steps:
  1. Log in as a user without `manage_options`.
  2. Attempt to access Settings and Report Builder URLs.
  3. Attempt a local POST if feasible.
- Expected result: The user cannot access or submit plugin admin flows.
- Notes / risk: If no non-admin account exists, mark as `Blocked`.

## 10. Payload State Checks Without Real API

### ASM-060: No Payload Transient

- Area: Payload state.
- Preconditions: No current payload transient exists for the test user.
- Steps:
  1. Open Report Builder.
  2. Confirm AI Payload Preview is absent.
  3. Confirm Generate AI Report is absent or unavailable.
- Expected result: AI generation cannot proceed without a valid payload.
- Notes / risk: Do not fetch real GA4 data in Step 40.

### ASM-061: Missing or Expired Payload

- Area: Payload state.
- Preconditions: A controlled local state can remove or expire the payload
  transient without external requests.
- Steps:
  1. Attempt AI generation only if a non-network route can reach the missing
     payload state.
- Expected result: The UI asks the user to fetch GA4 data again.
- Notes / risk: If no safe fixture is available, mark as `Not tested`.

### ASM-062: Payload Preview Disclosure

- Area: Payload preview.
- Preconditions: A payload preview exists from a safe non-network fixture or
  previous controlled local state.
- Steps:
  1. Inspect payload preview notices.
  2. Confirm credentials are described as excluded.
  3. Confirm page paths and analytics data are described as review-worthy.
- Expected result: Payload preview disclosure is visible and understandable.
- Notes / risk: Do not record payload JSON in smoke evidence.

## 11. Copy and Textarea Checks

### ASM-070: Report Textarea Initial State

- Area: Report output.
- Preconditions: Report Builder is open without a generated report.
- Steps:
  1. Inspect whether generated report textarea is absent or empty as designed.
- Expected result: No generated report body is shown unless one was produced in
  a prior controlled local state.
- Notes / risk: OpenAI generation is out of scope for Step 40.

### ASM-071: Copy Button Without Report Text

- Area: Copy UI.
- Preconditions: Copy button is visible only in a safe local state.
- Steps:
  1. Click Copy Report Text with an empty textarea, if visible.
- Expected result: The UI reports that there is nothing to copy and throws no JS
  error.
- Notes / risk: If no textarea exists, mark as `Not applicable`.

### ASM-072: Copy Current Textarea Value

- Area: Copy UI.
- Preconditions: A report textarea exists from a non-network fixture or
  controlled local state.
- Steps:
  1. Edit the textarea.
  2. Click Copy Report Text.
  3. Paste into a temporary local note.
- Expected result: Copied value matches the current textarea content.
- Notes / risk: Do not use or store real generated report text containing
  sensitive analytics.

## 12. Error and Notice Checks

### ASM-080: Notice Escaping and Readability

- Area: Notices.
- Preconditions: Settings and Report Builder validation notices are visible.
- Steps:
  1. Trigger non-network validation errors.
  2. Inspect displayed notices.
- Expected result: Notices are readable, escaped, and do not include raw tokens,
  API keys, Authorization headers, full request bodies, or full payload bodies.
- Notes / risk: Include screenshots only if they do not contain secrets.

### ASM-081: External API Error Message Shape

- Area: Error messaging.
- Preconditions: A non-network stub can simulate GA4 or OpenAI error responses.
- Steps:
  1. Trigger stubbed GA4 and OpenAI failures.
  2. Inspect user-facing messages.
- Expected result: Messages are normalized and do not print raw external
  response bodies or credentials.
- Notes / risk: If no stub is available, mark as `Not tested` and defer to
  Step 41.

### ASM-082: PHP Error Log Review

- Area: Runtime diagnostics.
- Preconditions: PHP error log is accessible.
- Steps:
  1. Load Settings and Report Builder.
  2. Perform non-network UI validation checks.
  3. Review the log for new fatal errors, warnings, or notices.
- Expected result: No new PHP fatal errors, warnings, or notices appear.
- Notes / risk: Do not paste secrets from logs into evidence.

## 13. Packaging and Static Baseline Confirmation

### ASM-090: PHP Lint

- Area: Static baseline.
- Preconditions: Shell access to `/var/www/html/analytics-report-ai`.
- Steps:
  1. Run `php -l analytics-report-ai.php`.
  2. Run `find includes -name '*.php' -print0 | xargs -0 -n1 php -l`.
- Expected result: All PHP files pass syntax checks.
- Notes / risk: No external request is made.

### ASM-091: WPCS / PHPCS

- Area: Static baseline.
- Preconditions: Composer dev tooling is installed locally.
- Steps:
  1. Run `vendor/bin/phpcs -ps`.
  2. Run `vendor/bin/phpcs --report=summary`.
- Expected result: `0 errors / 0 warnings`.
- Notes / risk: Do not run `phpcbf` in this checklist.

### ASM-092: Dry-Run Package

- Area: Packaging.
- Preconditions: Shell access to the repository.
- Steps:
  1. Run `bash -n tools/build-release-zip-dry-run.sh`.
  2. Run `./tools/build-release-zip-dry-run.sh`.
  3. Inspect zip contents.
- Expected result: Dry-run zip is generated and contains runtime files only.
- Notes / risk: This is still a dry-run artifact, not a formal release.

### ASM-093: Tooling File Exclusion

- Area: Packaging.
- Preconditions: Dry-run zip exists.
- Steps:
  1. Run the zip exclusion grep for `vendor/`, Composer files, PHPCS config, and
     `.phpcs` files.
- Expected result: No output.
- Notes / risk: Also confirm `docs/maturation/`, `tools/`, and `build/` are
  absent if inspecting manually.

### ASM-094: Plugin Check

- Area: Static baseline.
- Preconditions: Dry-run package stage exists in `/var/www/html/wp-dev`.
- Steps:
  1. Run Plugin Check against
     `build/release-dry-run/stage/analytics-report-ai`.
- Expected result: Plugin Check is clean.
- Notes / risk: Run against the staged package, not the source repository root.

## 14. Pass / Fail Recording Template

Use this table when executing the manual smoke test:

| Test ID | Result | Evidence | Notes | Follow-up step |
| --- | --- | --- | --- | --- |
| ASM-001 | Pass / Fail / Blocked / Not tested | Screenshot, command summary, or short observation. | Do not include secrets. | Step or issue reference. |

Result definitions:

- Pass: Expected result was observed.
- Fail: Expected result was not observed.
- Blocked: Required account, environment, fixture, or access was unavailable.
- Not tested: Deliberately skipped, usually because it would require real GA4 or
  OpenAI communication.

## 15. Recommended Follow-Up

- Step 41 candidate: manual GA4 / OpenAI end-to-end checklist without recording
  secrets.
- Step 42 candidate: public release readiness decision matrix.
- Step 43 candidate: OAuth and credential storage redesign plan for
  WordPress.org / public use.
- Step 44 candidate: final release package dry-run and install test.

## 16. Safety Notes

Step 40 must not record credential values, API keys, Authorization headers, full
request bodies, full payload bodies, or full generated reports that include
sensitive analytics.

Step 40 must not perform SVN operations, GitHub release operations,
WordPress.org publication actions, Composer install/update, `phpcbf`, GA4 API
requests, OpenAI API requests, or other external API communication.
