# Step 92.9: Safe Synthetic Browser Smoke Method Design

## Step Summary

Step 92.9 designs a safe method for reproducing the remaining no-data browser
smoke scenarios from Step 92.8:

- Scenario A: complete empty fetch.
- Scenario B: zero activity fetch.
- Scenario C: partial data fetch.
- Scenario D: comparison no-data.

This is a docs-only method design. It does not implement a helper, create a
temporary mu-plugin, run browser smoke, run Plugin Check, use `wp-dev-check`,
perform GA4 Fetch or OpenAI Generate, log in to WordPress admin, or communicate
with external APIs.

Production PHP, `readme.txt`, admin UI, JavaScript, CSS, Settings save logic,
GA4 client, OpenAI client, Composer/npm configuration, release package files,
and WordPress.org metadata were not changed.

WordPress.org release remains `Hold`.

## Background

Step 91 confirmed no-data handling with synthetic data, WordPress HTTP API
interception, and buffer-level rendering. Step 92 recorded that browser
automation was blocked. Step 92.8 recorded a manual browser result where
Payload Preview warning visibility passed at status level, but Scenario A
through Scenario D remained blocked because a safe way to create synthetic
browser states was not established.

The current source flow is:

- Report Builder uses a nonce-verified, `manage_options`-gated POST action.
- `handle_fetch_ga4_summary()` validates conditions and reads plugin settings.
- GA4 requests are made through `wp_remote_post()` in
  `Analytics_Report_AI_GA4_Client::run_report()`.
- Successful synthetic GA4 responses are formatted by
  `Analytics_Report_AI_Report_Data_Formatter::create_payload_from_ga4_summary()`.
- Valid reportable payloads are stored in the user-scoped transient returned by
  `analytics_report_ai_get_payload_transient_key()`.
- Complete current-period no-data is blocked before normal payload success and
  deletes the payload transient.
- Payload Preview and Generate AI Report availability are driven by
  `payload_status.generation_allowed`.

Because `run_report()` checks for a GA4 Property ID and Google Access Token
before `wp_remote_post()`, a browser smoke helper that only intercepts
`pre_http_request` may not reach the HTTP layer on an unconfigured test site.
The recommended method therefore needs both a local synthetic settings filter
and a hard GA4 HTTP interception layer.

## Referenced Docs

- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`
- `docs/maturation/step92-admin-browser-smoke-no-data-warnings-results.md`
- `docs/maturation/step92-8-manual-admin-browser-smoke-results.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`
- `docs/maturation/step90-ga4-empty-no-data-handling-implementation-plan.md`
- `docs/maturation/step92-5-browser-automation-environment-setup-results.md`
- `docs/maturation/step92-6-browser-automation-setup-verification-results.md`
- `docs/maturation/step92-7-chromium-launch-remediation-results.md`

## Synthetic Browser Smoke Method Options

| Option | Description | How it would reproduce Scenario A | How it would reproduce Scenario B | How it would reproduce Scenario C | How it would reproduce Scenario D | Security / privacy risk | Impact on production code | Cleanup difficulty | Browser fidelity | Recommended posture |
|---|---|---|---|---|---|---|---|---|---|---|
| Option A: temporary mu-plugin in `wp-dev` only | Add a temporary mu-plugin under the `wp-dev` WordPress tree, outside the Analytics Report AI repo. It supplies non-secret synthetic settings at runtime and intercepts GA4 HTTP requests. | Intercept current-period summary and detail requests with no reportable current data so the normal fetch POST reaches the no-data block path. | Intercept current-period summary with explicit zero metrics and no detail rows. | Intercept current-period data with reportable summary or selected detail rows while other detail presets are empty. | Intercept current-period data as reportable and comparison-period summary as no-data. | Low if scoped to `wp-dev`, gated to admin, never logs bodies, blocks real GA4/OpenAI calls, and uses non-secret synthetic settings. | None. | Moderate: remove one mu-plugin file and cleanup temporary transient/state. | High: uses the real admin form, nonce, capability check, fetch handler, formatter, transient behavior, and preview UI. | Recommended. Best balance of safety and browser fidelity. |
| Option B: temporary plugin file outside Analytics Report AI repo | Add a normal temporary plugin in `wp-dev/wp-content/plugins`, activate it for smoke, then deactivate/remove. | Same as Option A if it uses the same filters. | Same as Option A. | Same as Option A. | Same as Option A. | Low to moderate. Activation state must be managed and accidental persistence is easier than with a clearly named mu-plugin. | None. | Moderate to high: deactivate and remove plugin directory/file. | High once activated. | Acceptable fallback if mu-plugins are unavailable, but less preferred because activation adds another state to manage. |
| Option C: WP-CLI helper that creates synthetic transient/payload states | Use WP-CLI to create payload transients directly without running the fetch POST. | Hard to represent the fetch-time block, because complete no-data deletes the normal payload transient and returns an error notice. | Can create a warning payload transient, but does not prove fetch notice behavior. | Can create a warning payload transient, but does not prove fetch notice behavior. | Can create comparison warning payload transient, but does not prove fetch notice behavior. | Low if payload bodies are not printed, but direct transient creation risks stale state and bypasses user flow. | None. | Moderate: delete transients precisely. | Medium to low: bypasses GA4 fetch form and submission notices. | Useful for narrow Payload Preview checks, not recommended for A-D fetch smoke. |
| Option D: local-only filter hook/bootstrap helper for `pre_http_request` interception | Bootstrap a local helper that intercepts HTTP requests only, without settings filtering. | Works only if settings already contain non-empty GA4 config. Otherwise the GA4 client returns a settings error before HTTP. | Same limitation. | Same limitation. | Same limitation. | Low if all matching GA4/OpenAI requests are intercepted or blocked, but unsafe if it relies on real saved credentials. | None. | Low to moderate. | High only on already-configured environments. | Not sufficient alone. Use as part of Option A, not standalone. |
| Option E: manual URL/action harness with nonce/capability preserved | Build a temporary admin-only harness that triggers selected synthetic states by URL or custom action. | Could call formatter or handler-adjacent logic directly. | Same. | Same. | Same. | Moderate. Easy to drift away from the real Report Builder POST flow or accidentally expose state in URLs. | None if outside repo, but higher boundary risk. | Moderate. | Medium: may preserve login/capability but can bypass the exact fetch form. | Not preferred for this step. Consider only if the real form cannot be used. |

## Recommended Method

Recommended method:

```text
Temporary mu-plugin in wp-dev only, outside the Analytics Report AI repo,
removed immediately after smoke.
```

The temporary mu-plugin should combine:

- A local synthetic scenario selector.
- A runtime-only `pre_option_analytics_report_ai_settings` filter that supplies
  non-secret synthetic settings only while smoke mode is active.
- A `pre_http_request` filter that intercepts all GA4 Data API `runReport`
  requests and returns synthetic responses.
- A defensive `pre_http_request` block for OpenAI and Google OAuth endpoints so
  accidental external calls fail locally.
- No logging of request bodies, headers, credentials, payload JSON, response
  bodies, nonce values, cookies, sessions, analytics values, or generated report
  text.

This posture preserves the most important production boundaries for browser
smoke:

- The admin user still uses the real Report Builder screen.
- The real nonce and `manage_options` checks still run.
- The real form validation still runs.
- The real GA4 client methods still call through to the WordPress HTTP API.
- The real formatter, payload validation, generation gate, transient handling,
  notices, Payload Preview UI, and Generate AI Report availability are exercised.
- Analytics Report AI production code remains unchanged.
- Real GA4, OpenAI, and OAuth endpoints are not reached.

The helper should live only in:

```text
/var/www/html/wp-dev/wp-content/mu-plugins/
```

or another clearly temporary location inside `/var/www/html/wp-dev`, never under:

```text
/var/www/html/analytics-report-ai
```

The helper should be removed after the smoke run.

## Scenario Setup Design

### Scenario A Complete Empty Fetch

| Item | Design |
|---|---|
| Scenario | Complete empty fetch. |
| Synthetic setup concept | Use the real Report Builder fetch form with synthetic settings active. Intercept current-period summary and all current-period detail report requests with no rows or missing metric values. If comparison is enabled, comparison data may also be empty or ignored because current-period no-data is the blocking condition. |
| Expected admin browser result | Blocking no-data message is visible. Normal success message is not visible. The state is not treated as normal `payload_created` success. |
| Expected Generate AI Report state | Not shown, disabled, or blocked. No normal reportable payload transient should remain. |
| Allowed evidence | Status-level notes: blocking message visible yes/no, normal success visible yes/no, Generate state blocked yes/no, transient cleanup status if checked without values. |
| Prohibited evidence | Request body, response body, payload JSON, raw GA4 response, settings option values, credentials, identifiers, analytics values, page paths, screenshot unless fully redacted. |
| Cleanup required | Delete helper scenario state and delete the user-scoped Analytics Report AI payload transient. |

### Scenario B Zero Activity Fetch

| Item | Design |
|---|---|
| Scenario | Zero activity fetch. |
| Synthetic setup concept | Use the real Report Builder fetch form. Intercept current-period summary with all requested summary metrics explicitly present as zero values. Intercept detail presets as empty. |
| Expected admin browser result | Warning or informational notice is visible, not a blocking error. Payload Preview is visible. Zero activity is described as measured zero activity, not an API error or missing data. |
| Expected Generate AI Report state | Available because current summary has explicit reportable zero values. |
| Allowed evidence | Status-level notes: zero-activity warning visible yes/no, Payload Preview visible yes/no, Generate available yes/no. |
| Prohibited evidence | Actual metric values, payload JSON, GA4 response body, request body, generated report body, identifiers, credentials, screenshots unless fully redacted. |
| Cleanup required | Delete helper scenario state and delete the user-scoped Analytics Report AI payload transient. |

### Scenario C Partial Data Fetch

| Item | Design |
|---|---|
| Scenario | Partial data fetch. |
| Synthetic setup concept | Use the real Report Builder fetch form. Intercept current-period summary as reportable and at least one detail preset as present while one or more other detail presets return empty rows. |
| Expected admin browser result | Warning notice is visible. Payload Preview is visible. Missing detail category warning is visible at status level. |
| Expected Generate AI Report state | Available because current-period data is reportable, with warnings. |
| Allowed evidence | Status-level notes: partial-data warning visible yes/no, Payload Preview visible yes/no, Generate available yes/no, missing detail warning visible yes/no. |
| Prohibited evidence | Detail row values, page paths, sources, cities, metric values, payload JSON, request/response bodies, generated report body, credentials, screenshots unless fully redacted. |
| Cleanup required | Delete helper scenario state and delete the user-scoped Analytics Report AI payload transient. |

### Scenario D Comparison No-data

| Item | Design |
|---|---|
| Scenario | Comparison no-data. |
| Synthetic setup concept | Use the real Report Builder fetch form with comparison enabled. Intercept current-period summary and enough current-period detail data as reportable. Intercept comparison-period summary as no-data or missing metric values. |
| Expected admin browser result | Comparison limitation warning is visible. Payload Preview is visible. The UI makes clear that generated text should avoid comparison claims. |
| Expected Generate AI Report state | Available because current-period data is reportable, with comparison warning. |
| Allowed evidence | Status-level notes: comparison warning visible yes/no, Payload Preview visible yes/no, Generate available yes/no. |
| Prohibited evidence | Comparison values, metric values, payload JSON, request/response bodies, generated report body, identifiers, credentials, screenshots unless fully redacted. |
| Cleanup required | Delete helper scenario state and delete the user-scoped Analytics Report AI payload transient. |

## Hook / Interception Design

Recommended hook boundary:

```text
pre_option_analytics_report_ai_settings
pre_http_request
delete_transient cleanup for analytics_report_ai_get_payload_transient_key()
```

### `pre_option_analytics_report_ai_settings`

Purpose:

- Avoid using or changing real saved settings.
- Supply non-secret synthetic settings only during local smoke mode.
- Let `Analytics_Report_AI_GA4_Client::run_report()` pass its local
  configuration checks without storing real credentials.

Rules:

- Scope to `wp-dev` only.
- Scope to admin smoke only.
- Use non-secret dummy values, not real credentials or identifiers.
- Do not print or log the synthetic values.
- Do not dump the real option before or after.
- Avoid screenshots that show the Current Settings table unless fully redacted.

### `pre_http_request`

Purpose:

- Intercept GA4 Data API `runReport` requests before network I/O.
- Return synthetic GA4-shaped responses for the selected scenario.
- Block accidental OpenAI, Google OAuth, or other external service calls during
  smoke.

Rules:

- Match only the expected GA4 Data API host/path for synthetic responses.
- Do not log request bodies or headers.
- Do not log Authorization headers.
- Do not log generated synthetic response bodies.
- If an unexpected external URL is reached during smoke mode, return a local
  `WP_Error` rather than allowing network access.
- Keep nonce and capability boundaries unchanged.

The helper may inspect the request body in memory only to distinguish summary
requests from dimension preset requests. It must not echo, log, store, or record
the body.

### Scenario Selection

Preferred selector:

```text
wp-dev-only temporary smoke scenario state
```

The scenario selector can be a temporary transient or other local-only state
used by the helper. It should contain only a scenario key such as:

```text
complete_empty
zero_activity
partial_data
comparison_no_data
```

It must not contain credentials, request bodies, payload JSON, analytics values,
identifiers, paths, sources, cities, or generated report text.

### Non-recommended Boundaries

Avoid these as primary mechanisms:

- Directly writing full payload transients for A-D, because it bypasses the
  fetch result notices and no-data block path.
- Custom admin harnesses that call internal methods directly, because they can
  drift from the real Report Builder POST flow.
- Modifying Analytics Report AI production code to expose test fixtures.
- Using real saved credentials and relying on live GA4 responses.

## Cleanup Plan

Cleanup should happen after every smoke run, even on failure:

1. Remove the temporary mu-plugin/helper file from `/var/www/html/wp-dev`.
2. Clear the temporary scenario selector state.
3. Delete the current admin user's Analytics Report AI payload transient using
   `analytics_report_ai_get_payload_transient_key()` or the equivalent resolved
   transient key.
4. If a temporary normal plugin fallback was used, deactivate it and remove the
   plugin file/directory.
5. Confirm `plugin-check` remains inactive in `wp-dev`.
6. Confirm `wp-dev-check` was not touched.
7. Confirm no helper files were created under `/var/www/html/analytics-report-ai`.
8. Confirm `git status --short --untracked-files=all` in the Analytics Report
   AI repo shows only the expected docs changes.
9. Confirm no external API communication occurred.
10. Confirm no sensitive evidence was recorded.

Do not run `wp option get` for plugin settings as part of cleanup evidence.

## Safety / Evidence Rules

Record status-level evidence only.

Allowed:

- Scenario ID.
- Pass / Fail / Blocked / Not tested.
- Whether the expected notice category was visible.
- Whether Payload Preview was visible.
- Whether Generate AI Report was available or blocked.
- Whether visible PHP fatal / warning / notice output appeared.
- Whether obvious browser console errors appeared, summarized by category only.
- Whether cleanup completed.

Prohibited:

- Credential.
- API key.
- Access token.
- Authorization header.
- Credential fragments, prefixes, or suffixes.
- `wp_options` values or plugin settings option values.
- GA4 Property ID real value.
- Hostname / domain real value.
- Analytics values.
- Page path, source, or city values.
- Request body.
- AI payload JSON.
- OpenAI request body.
- Raw GA4 / OpenAI response.
- Generated report body.
- Nonce, cookie, or session values.
- Browser Network tab headers, bodies, cookies, or sessions.
- Screenshot unless fully redacted.

If a screenshot cannot be made safe through cropping/redaction, replace it with
a written status-level result.

## Execution Checklist For Next Step

Use this checklist for the next execution step. This Step 92.9 does not execute
it.

### Pre-check

1. Confirm `git status --short --untracked-files=all` in
   `/var/www/html/analytics-report-ai`.
2. Confirm `analytics-report-ai` is active in `/var/www/html/wp-dev`.
3. Confirm `plugin-check` is inactive or not installed in `/var/www/html/wp-dev`.
4. Confirm `wp-dev-check` will not be used.
5. Confirm no real credentials are needed.
6. Confirm no external API communication is allowed.

### Helper setup

1. Create the temporary helper only under `/var/www/html/wp-dev`, preferably as
   a clearly named mu-plugin.
2. Ensure the helper is outside `/var/www/html/analytics-report-ai`.
3. Enable only local synthetic smoke mode.
4. Provide non-secret synthetic settings through runtime filter only.
5. Intercept GA4 requests through `pre_http_request`.
6. Block accidental OpenAI, OAuth, or other external service requests.
7. Do not log request bodies, headers, responses, payloads, options, cookies,
   nonces, sessions, analytics values, or generated report text.

### Scenario A

1. Set scenario selector to `complete_empty`.
2. Open the real Report Builder screen in the browser.
3. Submit the real GA4 fetch form with nonce/capability preserved.
4. Record status-level result only:
   - blocking message visible,
   - normal success absent,
   - Generate AI Report blocked or absent,
   - no sensitive evidence recorded.

### Scenario B

1. Set scenario selector to `zero_activity`.
2. Submit the real GA4 fetch form.
3. Record status-level result only:
   - zero-activity warning/information visible,
   - Payload Preview visible,
   - Generate AI Report available,
   - no sensitive evidence recorded.

### Scenario C

1. Set scenario selector to `partial_data`.
2. Submit the real GA4 fetch form.
3. Record status-level result only:
   - partial-data warning visible,
   - missing detail category warning visible,
   - Payload Preview visible,
   - Generate AI Report available,
   - no sensitive evidence recorded.

### Scenario D

1. Set scenario selector to `comparison_no_data`.
2. Submit the real GA4 fetch form with comparison enabled.
3. Record status-level result only:
   - comparison limitation warning visible,
   - Payload Preview visible,
   - Generate AI Report available,
   - no sensitive evidence recorded.

### Cleanup and verification

1. Remove the temporary helper file.
2. Clear the temporary scenario selector.
3. Delete the user-scoped Analytics Report AI payload transient.
4. Confirm `plugin-check` remains inactive in `wp-dev`.
5. Confirm `wp-dev-check` was not touched.
6. Confirm no helper or `node_modules` files were created inside the Analytics
   Report AI repo.
7. Confirm no external API communication occurred.
8. Confirm no sensitive evidence was recorded.
9. Confirm repo status.

## Known Limitations

- This step designs the method only. It does not prove the helper works.
- The exact helper implementation still needs review before execution.
- Synthetic responses must be shaped carefully enough to match GA4 response
  expectations without recording raw bodies.
- Complete no-data browser behavior must be checked through the real fetch
  result path, not by manually creating a transient.
- Payload Preview still contains JSON in the MVP UI; evidence must not capture
  or record it.
- Browser automation in CODEX remains blocked by Chromium runtime constraints.
- Plugin Check was not run.
- WordPress.org release remains `Hold`.

## Next Step Recommendation

Recommended next step:

```text
Step 92.10: Implement temporary wp-dev-only synthetic smoke helper and run A-D browser smoke
```

That step should implement the helper outside the Analytics Report AI repo,
execute the Scenario A-D browser smoke manually or in a browser-capable
environment, record status-level results only, and remove the helper and
temporary state afterward.

WordPress.org release remains `Hold`.
