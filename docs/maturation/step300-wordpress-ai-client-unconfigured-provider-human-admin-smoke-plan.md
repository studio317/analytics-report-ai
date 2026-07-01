# Step 300: WordPress AI Client Unconfigured-Provider Human Admin Smoke Plan

## 1. Step Conclusion

Step 300 is a docs-only / planning-only checkpoint for a bounded human admin smoke after the Step 299 WordPress AI Client migration.

Step classification: `Planning completed`.

The immediate human smoke should cover:

- Settings screen verification after plugin-owned OpenAI UI retirement.
- Report Builder initial-screen verification for provider-neutral AI generation wording and setup boundaries.

The Data Preview / valid-payload unavailable-provider scenario is classified as `Blocked for immediate human smoke` in this step because safely reaching the preview state without GA4 Fetch, Generate AI Report, fixture creation, manual transient mutation, manual option mutation, or browser Network evidence requires a separate controlled fixture plan.

WordPress.org release readiness remains `Hold`.

## 2. Baseline Classification

Committed baseline:

- Step 296: public plugin identity and owner alignment.
- Step 297: WordPress AI Client and translation loading migration plan.
- Step 298: WordPress AI Client public API source-level compatibility checkpoint.
- Step 299: WordPress AI Client migration and OpenAI credential retirement implementation.

Current release posture:

- WordPress.org release readiness remains `Hold`.
- No push, WordPress.org reply, slug reservation, ZIP upload, package install, or Plugin Check has been performed after Step 299 in this Step 300 planning pass.

Source-level cleanup boundary from Step 299:

- `Analytics_Report_AI_Plugin::__construct()` registers `Analytics_Report_AI_Plugin::boot()` on `init`.
- `Analytics_Report_AI_Plugin::boot()` calls `analytics_report_ai_cleanup_legacy_openai_api_key()` before admin UI construction.
- `analytics_report_ai_cleanup_legacy_openai_api_key()` reads the main settings option as an array boundary and, only if the legacy `openai_api_key` key exists, unsets that key and updates the main settings option.
- The cleanup does not delete the settings option as a whole and is intended not to alter GA4 property settings, host filter settings, host name, Google OAuth client settings, report settings, the OAuth token option, or unrelated settings.
- The key value is not displayed, recorded, compared, logged, or documented.
- Cleanup execution state for a human page-load smoke is `Not observed / may have run`.

## 3. Exact Smoke Objective

The Step 300 smoke objective is to let a human operator verify only status-level / UI-level behavior after Step 299:

- Settings page loads without fatal error, visible PHP warning, or admin page load failure.
- Plugin-owned OpenAI API key UI is absent.
- Provider-neutral WordPress Settings > Connectors guidance is visible.
- Google OAuth / GA4 settings UI remains present.
- Report Builder initial screen loads without fatal error, visible PHP warning, or admin page load failure.
- Report Builder does not claim that a configured AI provider exists merely because the WordPress AI Client function exists.
- Report Builder displays provider-neutral AI generation guidance without provider name, model name, connector credential, request detail, or response detail.

This step does not plan or authorize GA4 Fetch, Generate AI Report, OAuth, Google navigation, Connector configuration, AI provider configuration, external API communication, manual option mutation, manual transient mutation, option inspection, transient inspection, screenshot capture, or browser Network inspection.

Important execution-boundary correction:

- No manual option mutation: the human operator does not save Settings, edit fields, use clear/delete controls, create fixtures, create mu-plugins, create transients, delete transients, or manually update options.
- Possible bounded automatic migration cleanup: opening Settings or Report Builder can run the Step 299 plugin bootstrap. If a legacy `openai_api_key` key exists, the bounded automatic cleanup may update the main settings option by unsetting only that key.
- Cleanup execution state: `Not observed / may have run`.
- The human operator must not inspect, infer, or record whether the legacy key existed or whether the cleanup actually executed.

## 4. Settings-Only Verification Checklist

Human operator should verify the Settings screen only by visible UI observation:

- Settings page load status: `Pass / Fail / Blocked`.
- Visible fatal error: `No / Yes`.
- Visible PHP warning / notice: `No / Yes`.
- Plugin-owned OpenAI API key UI absent: `Pass / Fail / Blocked`.
- OpenAI source category absent: `Pass / Fail / Blocked`.
- OpenAI password field absent: `Pass / Fail / Blocked`.
- OpenAI clear checkbox absent: `Pass / Fail / Blocked`.
- OpenAI-specific help / warning / setup guidance absent: `Pass / Fail / Blocked`.
- Provider-neutral WordPress Settings > Connectors guidance visible: `Pass / Fail / Blocked`.
- Google OAuth settings UI still visible: `Pass / Fail / Blocked`.
- GA4 settings UI still visible: `Pass / Fail / Blocked`.
- Credential values displayed: `No / Yes`.
- Option values displayed: `No / Yes`.
- Screenshot collected: `No`.
- Browser Network evidence collected: `No`.

Allowed Settings evidence is limited to screen name, visible label names, status/category-level result, and absence/presence classification.

## 5. Report Builder Initial-Screen Verification Checklist

Human operator should verify the Report Builder initial screen only by visible UI observation:

- Report Builder page load status: `Pass / Fail / Blocked`.
- Visible fatal error: `No / Yes`.
- Visible PHP warning / notice: `No / Yes`.
- AI generation provider row visible: `Pass / Fail / Blocked`.
- Provider-neutral WordPress Settings > Connectors guidance visible: `Pass / Fail / Blocked`.
- UI avoids configured-provider claim when only the WordPress AI Client function may exist: `Pass / Fail / Blocked`.
- Provider name displayed: `No / Yes`.
- Model name displayed: `No / Yes`.
- Connector credential detail displayed: `No / Yes`.
- Request / response detail displayed: `No / Yes`.
- GA4 Fetch executed: `No`.
- Generate AI Report executed: `No`.
- Screenshot collected: `No`.
- Browser Network evidence collected: `No`.

This initial-screen checklist does not require a Data Preview payload and does not test `is_supported_for_text_generation()` against a valid payload.

## 6. Data Preview / Unavailable-Provider Verification Options

The unavailable-provider behavior that matters after Step 299 is:

- A schema-valid payload exists.
- Current-period data is reportable for selected conditions.
- `Analytics_Report_AI_AI_Client::is_text_generation_available( $payload )` evaluates the provider-neutral WordPress AI Client support preflight.
- If `is_supported_for_text_generation()` returns false, Generate AI Report is disabled and provider-neutral `ai_text_generation_unavailable` equivalent guidance is shown.
- No provider name, model name, connector credential, request detail, or response detail is displayed.
- Generate AI Report is not submitted and no AI provider communication occurs.

Source-level review found:

- `analytics_report_ai_get_payload_transient_key()` creates a current-user-scoped transient key.
- `analytics_report_ai_validate_ai_payload()` requires a structured schema including payload status, availability, value semantics, site, conditions, summary, daily trend, top pages, traffic channels, traffic sources, regional trends, and report language.
- `render_payload_preview()` renders preview UI from a current request `submission_result` with status `payload_created`, `report_generated`, or `generation_blocked`.
- The normal path to `payload_created` is GA4 Fetch, which is prohibited for this unconfigured-provider smoke because it would contact the Google Analytics Data API.
- The Generate AI Report path reads the transient but submitting Generate AI Report is prohibited for this smoke.

Therefore, a valid Data Preview unavailable-provider browser observation cannot be safely included in the immediate human smoke without a separately designed local-only fixture mechanism.

## 7. Fixture-Option Comparison

Option A: user-scoped transient fixture.

- Description: put a schema-valid minimal synthetic payload into the current user's payload transient.
- Pros: uses the existing user-scoped transient key and existing validation boundary.
- Cons: current Data Preview rendering is driven by same-request `submission_result`, not by simply loading an existing transient on the initial screen. Additional handling would be needed to make the preview visible without GA4 Fetch or Generate AI Report.
- Safety classification in Step 300: `Not selected / needs separate fixture plan`.

Option B: temporary helper or mu-plugin fixture.

- Description: use a temporary local-only helper to supply a minimal payload for Data Preview display.
- Pros: could make the preview state observable without external GA4 or AI-provider communication.
- Cons: would require temporary code, hook boundaries, cleanup rules, and explicit validation that it does not alter settings, credentials, tokens, options, production source, provider configuration, or generated report text.
- Safety classification in Step 300: `Not selected / needs separate fixture plan`.

Option C: no fixture; Settings and Report Builder initial-screen smoke only.

- Description: perform only visible Settings and Report Builder initial-screen checks, leaving Data Preview unavailable-provider behavior for a later controlled local-only fixture plan.
- Pros: no transient creation, no option update, no fixture cleanup risk, no external communication, no Generate AI Report, no generated report text, no Network evidence, no screenshot.
- Cons: does not observe the valid-payload disabled Generate button state or `ai_text_generation_unavailable` guidance inside Data Preview.
- Safety classification in Step 300: `Chosen immediate approach`.

## 8. Chosen Fixture Approach / Blocked Classification

Chosen immediate approach: `Option C: no fixture`.

Data Preview unavailable-provider verification classification: `Blocked for immediate Step 300 human smoke`.

Blocked reason:

- GA4 Fetch is prohibited because it would contact the Google Analytics Data API.
- Generate AI Report is prohibited.
- Manual transient creation/deletion and manual option mutation are prohibited in this step.
- The Step 299 bounded automatic legacy `openai_api_key` cleanup may run on admin bootstrap if the legacy key exists, but its execution state must remain `Not observed / may have run`.
- Existing option/transient values must not be inspected, displayed, recorded, or modified.
- Current Data Preview rendering depends on a valid same-request payload result and cannot be safely reached by initial page observation alone.
- A fixture that safely supplies a schema-valid minimal payload without persistence or existing-data overwrite needs its own controlled local-only plan.

## 9. No-Network / No-Provider / No-GA4 / No-OAuth Boundary

The human smoke planned by this step must not perform:

- GA4 Fetch.
- Google OAuth.
- OAuth Connect / Authorize.
- Google navigation.
- Connector configuration.
- AI provider configuration.
- AI generation.
- Generate AI Report submission.
- external API communication.
- Plugin Check.
- package install.

Viewing Settings and Report Builder initial pages is expected to be a no-network observation with respect to Google, GA4, OAuth, OpenAI, AI providers, and Connectors.

Do not use browser Network evidence to prove this. The observation boundary is visible UI only.

Viewing Settings or Report Builder initial pages is allowed even though the Step 299 bounded automatic credential-retirement cleanup may run during plugin bootstrap. The human operator must not trigger manual settings saves, field edits, checkbox actions, clear/delete controls, fixture creation, option/transient operations, GA4 Fetch, Generate AI Report, Google OAuth, Connector configuration, AI provider configuration, screenshots, or browser Network inspection.

## 10. Safe Observation Rules

Human operator may record:

- Screen name.
- Visible UI label names.
- Status/category-level result.
- Pass / Fail / Blocked classification.
- Whether a class of UI element is visible or absent.
- Whether a prohibited operation was avoided.

Human operator must not record:

- whether the legacy `openai_api_key` key existed.
- whether the automatic cleanup actually executed.
- option state before or after automatic cleanup.
- database state caused by automatic cleanup.
- exact credentials.
- token values.
- option values.
- transient values.
- connector credential values.
- provider account details.
- provider/model metadata.
- browser URLs or query strings.
- raw request / response data.
- payload JSON.
- generated report text.
- screenshots.
- browser Network evidence.

If any sensitive value appears unexpectedly, stop the smoke and record only `unexpected_sensitive_value_exposure_category`.

Do not infer cleanup success or cleanup non-execution from UI observation. The cleanup state for this human smoke remains `Not observed / may have run`.

## 11. Prohibited Evidence Categories

Do not collect, inspect, display, or record:

- credentials.
- API keys.
- access tokens.
- refresh tokens.
- Authorization headers.
- Google OAuth client values.
- AI provider connector credential values.
- plugin settings option values.
- OAuth token option values.
- serialized option values.
- transient values.
- GA4 Property ID values.
- hostname/domain values.
- analytics metric values.
- page paths.
- traffic sources.
- city / region values.
- request bodies.
- raw GA4 responses.
- AI payload JSON.
- raw AI provider responses.
- generated report body.
- cookies.
- sessions.
- nonces.
- screenshots.
- browser Network evidence.
- database dumps.

## 12. Cleanup And Non-Persistence Requirements

For the immediate Step 300 human smoke execution:

- No fixture is created.
- No transient is manually created, updated, inspected, or deleted.
- No option is manually created, updated, inspected, or deleted.
- No mu-plugin is created.
- No production code is changed.
- No generated report text is created.
- No human cleanup action should be needed because the smoke is visible-UI-only.
- The Step 299 bounded automatic cleanup may run on bootstrap only if a legacy `openai_api_key` key exists; this is not manually triggered, not inspected, and not recorded as executed or not executed.

If a later Step designs a local-only fixture, that later Step must define:

- exact fixture entry point.
- exact fixture cleanup point.
- no-overwrite rule for existing transient/option state.
- synthetic payload schema source.
- validation-only proof that no external communication occurs.
- no generated report text.
- no screenshots or Network evidence.
- status/category-level result recording only.

## 13. Human Result Template

Use this template for the next human execution step:

```text
Step 300/301 human observation:

Settings screen:
- Settings page loaded: Pass / Fail / Blocked
- Visible fatal error: No / Yes
- Visible PHP warning / notice: No / Yes
- Plugin-owned OpenAI API key UI absent: Pass / Fail / Blocked
- OpenAI source category absent: Pass / Fail / Blocked
- OpenAI password field absent: Pass / Fail / Blocked
- OpenAI clear checkbox absent: Pass / Fail / Blocked
- OpenAI-specific help / warning / setup guidance absent: Pass / Fail / Blocked
- Provider-neutral WordPress Settings > Connectors guidance visible: Pass / Fail / Blocked
- Google OAuth settings UI visible: Pass / Fail / Blocked
- GA4 settings UI visible: Pass / Fail / Blocked

Report Builder initial screen:
- Report Builder page loaded: Pass / Fail / Blocked
- Visible fatal error: No / Yes
- Visible PHP warning / notice: No / Yes
- AI generation provider row visible: Pass / Fail / Blocked
- Provider-neutral WordPress Settings > Connectors guidance visible: Pass / Fail / Blocked
- Configured-provider claim avoided: Pass / Fail / Blocked
- Provider name/model/credential/request/response detail displayed: No / Yes

Data Preview unavailable-provider check:
- Performed: No
- Classification: Blocked / separated to later controlled local-only fixture plan

Boundary confirmation:
- Automatic legacy OpenAI key cleanup: Possible bounded migration side effect on bootstrap; execution state not observed and not inspected.
- Manual option/transient mutation: No.
- GA4 Fetch executed: No
- Generate AI Report executed: No
- Google OAuth executed: No
- Connector or AI provider configured: No
- External API communication intentionally triggered: No
- Plugin Check executed: No
- Package installed: No
- Credential / option / transient values inspected or recorded: No
- Option/transient/credential/token values inspected or recorded: No
- Screenshots collected: No
- Browser Network evidence collected: No
- Forbidden evidence recorded: No
```

## 14. Execution Stop Conditions

Stop immediately if any of the following occurs:

- Browser is redirected to Google, an AI provider, or an unexpected external service.
- A connector setup, provider setup, OAuth, GA4 Fetch, or Generate AI Report action is accidentally initiated.
- A credential, token, option value, transient value, provider account detail, request/response detail, payload JSON, or generated report body becomes visible.
- Browser Network evidence or screenshot capture would be needed to continue.
- Settings or Report Builder page load produces a visible fatal error.
- Human operator cannot distinguish initial-screen observation from a state-changing action.
- Any uncertainty appears about whether an action will create, update, delete, or inspect option/transient/credential/token data.
- Continuing UI observation appears to require checking whether automatic cleanup ran or inspecting option contents.
- UI changes suggest cleanup may have exceeded the bounded Step 299 scope.
- Any credential exposure, settings-save prompt, clear/delete control action, or external-communication path would be needed to continue.

Most important stop condition:

`Stop before any action that could create external communication, manually mutate settings, or expose credential / option / transient / provider evidence.`

## 15. Next Recommended Execution Step

Recommended next step:

`Step 301: WordPress AI Client unconfigured-provider Settings and initial Report Builder human admin smoke results`

Step 301 should record only human-provided status/category-level observations for Settings and Report Builder initial screens.

The Data Preview valid-payload unavailable-provider scenario should remain separated until a later docs-only plan defines a controlled local-only fixture that does not overwrite existing state, does not persist after cleanup, does not execute Generate AI Report, and does not contact Google, GA4, OAuth, OpenAI, AI providers, or Connectors.
