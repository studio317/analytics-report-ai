# Step 244: OpenAI Error Wording Controlled Local-only Runtime Verification Plan

## Step Purpose

Step 244 is a docs-only / planning-only local-only runtime verification plan for
OpenAI error wording branches.

The plan defines how a future step could safely verify runtime-related
user-facing wording categories without real credentials, real OpenAI API calls,
real provider-side communication, real Authorization headers, real request
bodies, or real response bodies.

Runtime verification is not executed in Step 244.

WordPress.org release readiness remains `Hold`.

## Scope

Candidate runtime wording branches:

```text
missing key branch
401 branch
authentication error branch
generic fallback branch
```

The target of future verification is the safe user-facing wording category and
status/category-level outcome. The target is not raw provider message
reproduction, provider-side diagnosis, API key validity, quota, permission, or
OpenAI request success.

## Explicit Non-goals

Step 244 does not:

- execute runtime verification,
- create or run a temporary helper,
- create or place a mu-plugin,
- define a test constant,
- execute HTTP interception,
- inject synthetic responses,
- change production PHP,
- change `includes/class-openai-client.php`,
- change `includes/class-settings.php`,
- change `includes/class-report-builder.php`,
- change `includes/functions-utils.php`,
- change the credential resolver,
- change OpenAI request behavior,
- change HTTP handling,
- change response parsing,
- change error classification logic,
- change GA4 behavior,
- change OAuth behavior,
- change Settings save / clear behavior,
- change `readme.txt`,
- change tools,
- change JavaScript or CSS,
- change `uninstall.php`,
- run browser admin smoke,
- run GA4 Fetch,
- run OpenAI Generate,
- run OAuth Connect / Authorize,
- navigate to Google,
- call token endpoints,
- execute refresh requests,
- execute revoke requests,
- run Plugin Check,
- collect screenshots,
- collect browser Network evidence,
- run `wp option get`,
- inspect database dumps,
- inspect or record credential values, API keys, tokens, Authorization headers,
  option values, request bodies, raw responses, AI payload JSON, or generated
  report bodies.

## Referenced Steps

- `docs/maturation/step232-openai-api-key-constant-based-configuration-source-level-verification-results.md`
- `docs/maturation/step239-openai-api-key-source-aware-wording-narrow-production-implementation-results.md`
- `docs/maturation/step240-openai-api-key-source-aware-wording-source-level-verification-results.md`
- `docs/maturation/step242-openai-api-key-source-aware-wording-controlled-human-admin-smoke-results.md`
- `docs/maturation/step243-openai-api-key-source-aware-wording-maturation-checkpoint.md`

## Fixed Source Model

Current MVP source model:

```text
Constant first / Settings fallback / Missing
```

Constant source:

```text
ANALYTICS_REPORT_AI_OPENAI_API_KEY
```

Safe source categories:

```text
constant_configured
settings_saved
missing
```

Terminology boundaries:

- `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is the constant source name.
- `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is not a source category.
- `constant_configured`, `settings_saved`, and `missing` are safe source
  categories.
- Source category does not guarantee API key value validity, provider-side
  acceptance, permission, quota, endpoint availability, model availability, or
  OpenAI request success.
- Future runtime verification must verify only safe user-facing wording
  categories and status/category-level outcomes.

## Branch Inventory

| Branch | Trigger condition to simulate later | Expected safe wording objective | External communication allowed | Credential/value inspection allowed | Runtime execution in Step 244 |
|---|---|---|---|---|---|
| missing key branch | Controlled local condition where resolver outcome is missing and OpenAI request is not reached. | Missing readiness guidance should point to the preferred constant source or current MVP Settings fallback without credential details. | No | No | No |
| 401 branch | Local-only HTTP interception returns synthetic 401 status before any external request leaves the environment. | Authentication review guidance should avoid Settings-only recovery and avoid key values. | No | No | No |
| authentication error branch | Local-only HTTP interception returns a synthetic safe authentication category before any external request leaves the environment. | Authentication review guidance should avoid raw provider wording and credential evidence. | No | No | No |
| generic fallback branch | Local-only HTTP interception returns an unrecognized synthetic non-success category before any external request leaves the environment. | Generic retry / safe configuration review guidance should avoid request/response details. | No | No | No |

For every branch:

- verification target is safe user-facing wording category,
- raw provider messages are not reproduced,
- API keys, tokens, headers, request bodies, and raw responses are not displayed
  or recorded,
- error classification logic is not changed,
- source category is not treated as provider-side diagnosis.

## Local-only Harness Design Candidates

### A. Missing Key Branch

Recommended future direction:

- Use a controlled local-only condition where the OpenAI API key source category
  is `missing`.
- Confirm the missing readiness branch returns the safe user-facing wording
  category.
- Do not allow execution to reach the OpenAI HTTP request.
- Do not inspect real Settings values, real constant values, real option values,
  or real credentials.
- Do not permanently modify production configuration.

HTTP interception requirement:

```text
Not applicable
```

Reason:

- The missing key branch should return before OpenAI HTTP transport is reached.

### B. 401 / Authentication / Generic Fallback Branches

Recommended future direction:

- Use a temporary local-only harness in a later execution step.
- Use WordPress HTTP interception such as `pre_http_request` before any external
  request leaves the local environment.
- Return only synthetic status/category outcomes needed to exercise the safe
  wording branch.
- Keep any synthetic response body empty or minimal and harmless.
- Do not display or record the synthetic body content in terminal output or
  docs.
- Do not include API keys, Authorization headers, request bodies, raw provider
  bodies, payloads, or secret-like strings in the synthetic fixture.
- Remove the temporary harness after execution.
- Confirm no helper remains in the repository, plugin source, mu-plugin
  location, or database.

Selection rationale:

- 401 / authentication / generic fallback branches require reaching OpenAI API
  response handling code, but real provider communication is not acceptable.
- HTTP interception allows a local-only branch check without real OpenAI API
  calls.
- The harness must be temporary and status/category-level only.

Rejected directions:

- real OpenAI API call,
- real API key,
- real Authorization header,
- real request body recording,
- real provider response recording,
- persistent debug/test code in production files,
- persistent mu-plugin helper,
- database option mutation for test state,
- screenshots or browser Network evidence.

## Controlled Conditions Safety Requirements

For any future execution step:

- execution target must be local `/var/www/html/wp-dev` only,
- Plugin Check environment `/var/www/html/wp-dev-check` must not be used for
  runtime verification,
- no real external URL communication may occur,
- WordPress HTTP transport must be intercepted before external communication,
- unexpected external URL attempts must be blocked / fail-safe,
- test output must be status/category-level only,
- `var_dump` is prohibited,
- raw HTTP response dumps are prohibited,
- option dumps are prohibited,
- request dumps are prohibited,
- header dumps are prohibited,
- payload dumps are prohibited,
- API keys, tokens, constant values, and option values must not appear in
  terminal output, docs, logs, or browser UI,
- browser automation is not required,
- screenshots are prohibited,
- Network evidence is prohibited,
- synthetic fixtures must not contain actual secret-like strings,
- temporary helper / harness files must be removed after execution,
- external communication must be recorded only as `No`.

## Normalized Observation Template

Use the following status/category-level template in a future execution step.
Do not include actual wording text, credential information, synthetic body
content, request bodies, raw responses, AI payload JSON, or generated report
bodies.

```text
Controlled runtime verification branch: missing_key / http_401 / authentication_error / generic_fallback
Harness loaded: Yes / No / Blocked
External HTTP communication executed: No
OpenAI provider communication executed: No
Actual credential / key / token / header inspected or recorded: No
Request body / raw response / AI payload JSON / generated report body inspected or recorded: No
Safe error wording category observed: missing_readiness / authentication_review / generic_retry_review / visible_but_unclear / blocked
Settings-only recovery guidance presented as sole route: No / Yes / Blocked
Preferred constant source and current MVP Settings fallback both reflected where applicable: Yes / No / Not applicable / Blocked
Raw provider error text displayed: No / Yes / Blocked
Forbidden evidence recorded: No
Temporary harness removed after execution: Yes / No / Blocked
```

## Branch-specific Pass / Fail / Blocked Criteria

### Pass

Classify a branch as Pass only if:

- intended branch is safely reproduced by a local-only harness,
- external HTTP / provider communication is not executed,
- safe user-facing wording category is observed,
- Settings-only route is not shown as the sole recovery guidance,
- missing branch safely reflects the preferred constant source and current MVP
  Settings fallback,
- authentication / generic branches do not display raw provider text,
  credential details, or request/response details,
- forbidden evidence is not recorded,
- temporary harness is removed after execution.

### Fail

Classify a branch as Fail if any of the following occurs:

- real external communication occurs,
- API key, token, header, option value, request body, raw response, payload, or
  generated report body is displayed or recorded,
- raw provider wording appears as user-facing output,
- Settings-only route is shown as the sole recovery guidance,
- intended branch is unclear and safe error wording category cannot be
  determined,
- temporary harness remains after execution,
- production code, database, plugin source, or mu-plugin location has unintended
  residual test changes.

### Blocked / Not Applicable

Use these classifications as follows:

- `Blocked` if a controlled condition cannot be created without real
  credentials, real provider communication, or unsafe evidence.
- `Not applicable` for HTTP interception in the missing branch if the branch
  returns before HTTP transport.
- `Not applicable` for browser display when the future execution step remains
  non-browser.
- `Not applicable` for provider-side validity, quota, permissions, model
  availability, or request success.

## Cleanup Verification Plan

After any future execution step, verify and record only status/category-level
cleanup results:

```text
temporary helper removed: Yes / No / Blocked
temporary harness removed: Yes / No / Blocked
mu-plugin location clean: Yes / No / Blocked
plugin repository has no test-only production diff: Yes / No / Blocked
wp-dev database not intentionally modified for harness purposes: Yes / No / Blocked
external HTTP communication: No / Yes / Blocked
forbidden evidence recorded: No / Yes / Blocked
```

Do not display database contents or option values during cleanup verification.

## Step 232 Residual Handling

Historical Step 232 residual:

```text
OpenAI error wording source-awareness: Needs follow-up wording alignment
```

Step 244 status:

```text
Static source-aware wording implementation: Completed
Source-level verification: Pass
Limited missing-state Settings / Report Builder human-visible confirmation: Scope-bound Pass
Local-only runtime verification plan: Prepared
Runtime execution: Not performed
Runtime error-path verification: Not performed
Residual fully resolved: Do not claim
```

Step 244 prepares the future local-only runtime verification design. It does not
execute the design and does not classify the residual as fully resolved.

## Separate Tracks Not Included

The following remain outside Step 244:

- `constant_configured` human browser coverage,
- `settings_saved` human browser coverage,
- fallback-saved `clear_openai_api_key` visibility,
- fallback-saved clear scope wording,
- constant non-mutation wording when clear control is visible.

If needed, each should receive a dedicated controlled human smoke plan and must
not be mixed into the local-only runtime error wording verification track.

## Recommended Next Step

Recommended next step:

```text
Step 245: OpenAI error wording controlled local-only runtime verification results
```

Step 245 should execute only if all of the following are true:

- the local-only harness design can run without production code changes,
- real credentials are not required,
- real API keys are not required,
- real provider-side communication is not required,
- all external HTTP is blocked or synthetically intercepted,
- temporary helper / harness cleanup is reliable,
- only status/category-level results will be recorded.

If these conditions cannot be satisfied, Step 245 should not execute runtime
verification. It should instead record a docs-only `Blocked` checkpoint.

## Result Classification

```text
Step 244 result: Local-only runtime verification plan prepared
Docs-only / planning-only: Yes
Runtime verification executed: No
OpenAI provider communication executed: No
Real credential used: No
HTTP interception executed: No
Temporary helper created: No
Synthetic response injected: No
Step 232 residual fully resolved: No
WordPress.org release readiness: Hold
```
