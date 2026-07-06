# Topic 6A: Integrated Report Creation Error Path Controlled Verification Plan

## 1. Step Conclusion

Topic 6A is a docs-only / planning-only checkpoint for the integrated Report Builder flow introduced in Topic 5.

Baseline classification:

- Topic 5 committed baseline confirmed: `feat(report): streamline AI report creation`
- Working tree at start: clean
- Plan status: Planning completed
- Production code change: Not performed
- Browser / OAuth / GA4 Fetch / AI Generate / external API communication: Not performed
- WordPress.org / SVN / Plugin Check / package activity: Not performed

The controlled execution should verify that `create_ai_report` stops at the correct boundary, shows only safe notices, preserves report condition form values, and does not expose credentials, tokens, GA4 values, AI payloads, provider responses, or generated report bodies on failure paths.

## 2. Source-Level Baseline

Source-level review was limited to status/category and control-flow evidence:

- `includes/class-report-builder.php`
  - `create_ai_report` is the only current Report Builder POST action exposed by the form.
  - `maybe_handle_request()` keeps the existing capability check, nonce check, `map_deep()` sanitization, and `normalize_report_input()` path before calling `execute_ai_report_from_conditions()`.
  - `execute_ai_report_from_conditions()` calls `prepare_payload_from_report_conditions()` first and only calls `generate_ai_report_from_payload()` when the payload result status is `payload_created`.
  - `prepare_payload_from_report_conditions()` preserves the sequence: condition validation, GA4 fetch, AI payload build / validation, generation allowed gate, transient storage.
  - Failure results carry `form_values` where the current flow can restore start date, end date, comparison, scope, and path.
  - reconnect-required notices use the existing Settings link renderer without showing token or client values.
- `includes/class-ga4-client.php`
  - GA4 client returns safe `WP_Error` messages for missing configuration, network failure, invalid JSON, and status-level API errors.
  - HTTP status can be shown, but request body and response body are not surfaced by the error builder.
- `includes/class-ai-client.php`
  - AI Client wrapper exposes provider-neutral categories: unavailable, failed, empty text.
  - Provider name, model name, credential, request, and response details are not exposed by the wrapper.
- `includes/functions-utils.php` and `includes/class-report-data-formatter.php`
  - Payload validation and `analytics_report_ai_payload_allows_generation()` gate AI generation.
  - No-data / not-reportable status maps to a blocking payload status before AI generation.

## 3. Execution Boundary for Future Controlled Runs

Future controlled execution should use `wp-dev` only. It must not use `wp-dev-check`.

Do not mix browser smoke observations with CLI / fixture observations in a single evidence claim. Each future execution should declare whether it is:

- browser-only observation,
- CLI-only local fixture observation,
- temporary mu-plugin / filter observation,
- or source-level only.

Future execution must prevent external network calls before any scenario that could reach GA4 or AI provider code. The preferred boundary is a local-only temporary guard such as `pre_http_request` that blocks outbound HTTP and, when needed, returns synthetic status-level responses. The guard must be removed after the scenario and verified absent.

Forbidden evidence for all future runs:

- API keys, access tokens, refresh tokens, Authorization headers
- OAuth client ID or client secret values
- GA4 Property ID, hostname/domain values, page paths, source/city/analytics values
- plugin option values, token option values, serialized option values, transient values
- request bodies, raw responses, AI payload JSON, generated report text
- screenshots, browser Network evidence, cookies, sessions, nonces

## 4. Controlled Verification Matrix

| ID | Verification purpose | Preconditions | Safe state setup | Local fixture / filter candidate | External communication prevention | GA4 Fetch should be called? | AI Generate should be called? | Expected safe notice / direction | Form values to verify | Non-display checks | Cleanup | Classification |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| A | Report condition validation failure | Report Builder accessible in `wp-dev`; no credential changes required | Submit deliberately invalid condition values through a controlled local harness or human form entry without external-action buttons beyond `Create AI Report` | Prefer browser-only invalid date/scope/path cases; CLI helper may POST invalid normalized values if nonce/session is controlled by human | Not needed if validation fails before GA4; still keep no-network guard in CLI harness | No | No | Existing validation notice such as invalid start/end date, invalid comparison, invalid scope, invalid path | start date, end date, comparison, scope, path | No GA4 values, no payload, no credential, no internal error details | Remove any temporary harness; no option/transient writes expected | Implementable |
| B | Google OAuth client configuration missing / readiness gap | Controlled environment where OAuth client config is category-level missing; no values inspected | Use status/category-only observation from Settings / Current Status, then attempt integrated flow only if no external communication can occur | Prefer source-level plus browser category observation; fixture to force config missing only if it does not inspect or overwrite real settings | No-network guard required if create action is submitted | No, if no usable Google credential exists before client call; otherwise stop boundary must be reclassified | No | Existing safe Settings guidance or generic Google connection/configuration notice | all report condition fields | No client ID, client secret, option value, or readiness internals | Remove any temporary config fixture; confirm no residual helper | Preconditions need confirmation |
| C | Google account not connected | OAuth client may be configured, but no usable local Google access token exists | Use category-level UI state `not_connected` or a local-only fixture that returns credential source missing without exposing values | Browser-only category observation plus integrated form submit with no-network guard; no token inspection | No-network guard required as defense-in-depth | No, because Report Builder should stop before GA4 client when access token is empty | No | Safe Google connection-not-configured notice directing to Settings | all report condition fields | No token, Authorization header, option value, provider account detail | Remove temporary guard/helper | Implementable |
| D | Google reconnect required | Existing token lifecycle safe category is reconnect-required / refresh-needed / error category | Use local-only fixture or controlled token-lifecycle category injection; do not inspect real token store | Temporary filter/helper that supplies reconnect-required category without token value, if available; otherwise browser category observation only | No-network guard required | Existing implementation stops before GA4 when resolved access token is empty; if a future lifecycle path reaches GA4, classify precisely | No | Existing reconnect-required Settings link notice | all report condition fields | No token value, provider response, internal category value, raw OAuth details | Remove helper and verify absent | Preconditions need confirmation |
| E | GA4 Fetch failure | Valid conditions and a locally simulated credential boundary that lets code reach GA4 client without real credential disclosure | Use a temporary no-network guard that intercepts GA4 HTTP and returns a safe synthetic `WP_Error` or safe status-level response | `pre_http_request` guard for GA4 endpoint only; synthetic non-2xx response may exercise safe API error messages | Required; outbound HTTP must be blocked and counted category-level only | Yes, into controlled local intercept only | No | Existing safe GA4 error notice; no request/response body | all report condition fields | No GA4 response body, request body, analytics values, property ID, hostname, token | Remove temporary mu-plugin/helper and confirm no residual artifact | Implementable with local guard |
| F | GA4 no-data / not-reportable | Valid conditions and synthetic GA4 responses that produce no current-period reportable data | Use local-only synthetic GA4 response fixture matching only schema/category needs; do not record synthetic payload body | `pre_http_request` synthetic GA4 response, or lower-level helper if one exists; avoid storing or displaying payload content | Required | Yes, into controlled local synthetic response only | No | Safe no-data / not-reportable notice from generation block message | all report condition fields | No Preview, no payload JSON, no analytics values | Remove synthetic fixture and clear only fixture-created transient if created; do not inspect real transient values | Implementable with local guard |
| G | AI provider unavailable | Valid conditions and synthetic GA4 responses that produce a generation-allowed payload; AI Client text generation support unavailable | Prefer environment with no configured AI provider or temporary AI Client support guard; synthetic GA4 response guarded locally | `pre_http_request` for GA4 plus provider-neutral local AI support unavailable condition; do not configure Connectors | Required for GA4; AI provider call should not occur if support check returns unavailable | Yes, into controlled local synthetic response only | No actual generation; wrapper should stop before `generate_text()` | Safe AI text-generation unavailable notice | all report condition fields | No provider name, model, credential, payload, request/response | Remove guard/helper; do not alter Connector settings | Preconditions need confirmation |
| H | AI Generate failure | Valid conditions, generation-allowed synthetic GA4 payload, AI Client support check passes but generation fails locally | Use a temporary local-only AI Client failure fixture if public API can be safely shimmed without provider communication | Local guard around AI Client or test double; no external provider configured or contacted | Required; also block all outbound HTTP | Yes, into controlled synthetic GA4 response only | Yes, but only into local synthetic failure path; no provider communication | Safe AI generation failed notice; no success notice; no generated report area | all report condition fields | No provider response, request body, payload, credential, generated text | Remove AI fixture and HTTP guard; confirm no residual artifact | Preconditions need confirmation |

## 5. GA4 Fetch / AI Generate Call Expectations

| ID | GA4 Fetch expected? | AI Generate expected? | Stop boundary |
| --- | --- | --- | --- |
| A | No | No | `validate_report_conditions()` |
| B | No, if no usable Google credential exists | No | credential/configuration readiness before GA4 client |
| C | No | No | empty access token / missing credential source |
| D | Existing reconnect-required behavior decides whether it stops before GA4; current credential-source path should stop before AI | No | reconnect-required / refresh-needed category |
| E | Yes, but only into local intercept | No | GA4 client error path |
| F | Yes, but only into local synthetic response | No | payload `generation_allowed` false |
| G | Yes, but only into local synthetic response | No actual generation | AI Client support unavailable |
| H | Yes, but only into local synthetic response | Yes, but only into local synthetic failure | AI Client generation failure |

## 6. Fixture and Helper Policy

Allowed future fixture shapes:

- temporary mu-plugin loaded only in `wp-dev`,
- temporary helper script that does not print option values,
- temporary filter callback for `pre_http_request`,
- local-only synthetic response with category-level assertions only.

Fixture requirements:

- must not read, print, diff, dump, or log real option/token/credential/transient values,
- must not overwrite existing settings, OAuth tokens, or user transients,
- must not create persistent data unless the plan explicitly includes scoped creation and deletion,
- must be removed after the scenario,
- cleanup must verify artifact absence by file/path/status only, not by value inspection.

If fixture safety is unclear, classify the scenario as `現時点では実施保留` and do not run it.

## 7. External Communication Blocking Policy

For any future execution that can reach GA4 or AI provider code:

- install a temporary `pre_http_request` guard before triggering the action,
- block all outbound requests by default,
- allow only local synthetic responses needed for the scenario,
- record only category-level counts such as `outbound_blocked=yes` or `synthetic_response_used=yes`,
- never record URL, Authorization header, request body, response body, provider detail, token, or account identifiers.

Browser observations should not use Network tools. If the operator feels Network evidence is needed, stop the scenario and reclassify it as blocked.

## 8. Human Result Template for Future Execution

Use this status-level template per scenario:

```text
Scenario ID:
Execution mode: browser-only / CLI-only / temporary helper / source-level
Controlled environment: wp-dev only
Temporary guard installed: Yes / No / Not applicable
External communication occurred: No
GA4 Fetch boundary: not_called / locally_intercepted / blocked_before_call
AI Generate boundary: not_called / local_failure_only / blocked_before_call
Expected notice category observed: Yes / No / Blocked
Form values retained: Yes / No / Blocked
Success notice absent when failure expected: Yes / No / Not applicable
Generated report body displayed on failure: No
Preview / payload JSON displayed on failure: No
Forbidden evidence recorded: No
Fixture cleanup completed: Yes / No / Not applicable
Residual temporary artifact check: Pass / Fail / Not applicable
Result classification: Pass / Fail / Blocked
```

## 9. Stop Conditions

Stop immediately if:

- a scenario would require real OAuth Connect, Reconnect, Disconnect, callback, token exchange, refresh, or revoke,
- a scenario would require real GA4 API or AI provider communication,
- a credential, token, option value, transient value, GA4 value, payload, request body, response body, or generated report body would need to be inspected,
- browser Network evidence or screenshots would be needed,
- a fixture might overwrite existing settings, tokens, transients, or credentials,
- source-level behavior does not clearly identify the expected stop boundary.

## 10. Recommended Controlled Execution Order

1. A: condition validation failure, because it should stop before any external boundary.
2. C: Google not connected, because it should stop before GA4 when no usable access token exists.
3. D: reconnect-required, after confirming a safe category-only way to create the lifecycle state.
4. E: GA4 fetch failure with `pre_http_request` local intercept.
5. F: no-data / not-reportable with synthetic GA4 response.
6. G: AI provider unavailable after synthetic generation-allowed GA4 data.
7. H: AI generation failure only if a safe local AI Client failure shim is confirmed.
8. B: OAuth client config missing, either as source-level/status-level confirmation or a controlled browser case if it can be separated from general not-connected state.

## 11. Prohibited Operations in This Topic

Not performed in Topic 6A:

- production PHP / JS / CSS / translation / readme changes,
- Settings, Report Builder, action, nonce, payload, transient, or TTL changes,
- temporary fixture creation,
- browser admin smoke,
- OAuth Connect / Reconnect / Disconnect,
- GA4 Fetch,
- AI Generate,
- external API communication,
- Plugin Check,
- package install,
- commit / push / SVN operation,
- credential/token/option/transient value inspection.

## 12. Result Classification

Topic 6A result:

```text
Integrated report creation error-path controlled verification plan: Completed
Production runtime changes: None
Execution performed: None
Release readiness impact: Planning only
```
