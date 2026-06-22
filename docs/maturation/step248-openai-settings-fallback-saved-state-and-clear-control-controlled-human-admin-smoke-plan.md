# Step 248: OpenAI Settings Fallback Saved-state and Clear-control Controlled Human Admin Smoke Plan

## Step Purpose

Step 248 is a docs-only / planning-only controlled human admin smoke plan for
the current MVP OpenAI Settings fallback saved-state and clear-control coverage.

This plan follows the Step 247 Priority 1 decision and defines how a future
human smoke step can safely create a temporary `settings_saved` UI state, record
only status/category-level observations, and confirm cleanup at UI category
level.

Step 248 does not execute browser smoke, save Settings, input anything into the
OpenAI fallback field, operate `clear_openai_api_key`, create constants, run
OpenAI Generate, or perform external communication.

WordPress.org release readiness remains `Hold`.

## Scope

Target human-visible coverage:

```text
settings_saved source category
fallback field value-hidden posture
fallback-saved clear_openai_api_key checkbox visibility
clear-control scope wording
post-clear cleanup UI status
```

The plan is limited to current MVP Settings fallback UI coverage. It does not
validate a real credential, actual API key value, provider-side acceptance,
quota, permission, endpoint availability, model availability, OpenAI request
success, or public-release approval of Settings storage.

## Explicit Non-goals

Step 248 does not:

- run browser admin smoke,
- save Settings,
- input into the OpenAI fallback field,
- operate the `clear_openai_api_key` checkbox,
- add, change, or remove constants,
- change `wp-config.php`,
- create, place, or modify a mu-plugin,
- create or run a temporary helper or harness,
- write or delete options,
- run `wp option get`,
- inspect database dumps,
- run OpenAI Generate,
- run GA4 Fetch,
- run OAuth Connect / Authorize,
- navigate to Google,
- call token endpoints,
- execute refresh requests,
- execute revoke requests,
- perform external HTTP communication,
- run Plugin Check,
- collect screenshots,
- collect browser Network evidence,
- change production PHP,
- change JavaScript,
- change CSS,
- change `readme.txt`,
- change tools,
- change `uninstall.php`,
- display, record, or output credentials, API keys, tokens, Authorization
  headers, constant values, option values, request bodies, raw responses, AI
  payload JSON, or generated report bodies.

## Referenced Steps

- `docs/maturation/step231-openai-api-key-constant-based-configuration-narrow-production-implementation-results.md`
- `docs/maturation/step242-openai-api-key-source-aware-wording-controlled-human-admin-smoke-results.md`
- `docs/maturation/step246-openai-error-wording-controlled-local-only-runtime-verification-maturation-checkpoint.md`
- `docs/maturation/step247-openai-credential-source-human-coverage-prioritization-checkpoint.md`

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

Public-release target:

```text
Option B: constant-based OpenAI API key configuration preferred over Settings storage
```

WordPress.org release readiness:

```text
Hold
```

Terminology boundaries:

- `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is the constant source name, not a source
  category.
- `constant_configured`, `settings_saved`, and `missing` are safe source
  categories.
- A source category does not guarantee actual credential validity, provider
  acceptance, quota, permission, endpoint availability, model availability, or
  OpenAI request success.
- Settings fallback is a current MVP fallback and does not mean Settings-based
  storage is approved for public release.

## Target And Excluded Coverage

### Target

Settings page:

- `settings_saved` category,
- Settings fallback saved status,
- value-hidden display status,
- password field value remains undisclosed / empty,
- saved-fallback wording,
- `clear_openai_api_key` checkbox visibility,
- clear-control fallback-only scope wording,
- constant-based configuration is not changed wording.

Report Builder:

- `settings_saved` category / readiness,
- value-hidden posture,
- safe status/category labels.

Post-clear:

- `missing` category,
- fallback status no longer saved,
- password field remains undisclosed / empty,
- clear checkbox no longer visible,
- cleanup status at UI category level.

### Explicit Exclusions

- `constant_configured` human browser path,
- actual constant configuration,
- actual constant value inspection,
- actual constant mutation testing,
- real credential validation,
- actual API key value confirmation,
- OpenAI Generate,
- GA4 Fetch,
- OAuth,
- external HTTP communication,
- provider-side behavior,
- screenshots,
- Network evidence,
- option value display or recording,
- request / response evidence.

## Step 249 Baseline Gate

Before any future test-state creation, the human operator should load the
canonical Settings page and confirm only status/category-level baseline state.

Baseline preflight observations:

```text
OpenAI API key source category: missing
Settings fallback status: not_saved
OpenAI API key value display status: hidden
OpenAI password field value: empty / not visible
clear_openai_api_key checkbox: hidden
```

Step 249 must be classified as `Blocked` and must not save or clear if any of
the following is true:

```text
source category is not missing
fallback status is not not_saved
clear checkbox is already visible
baseline state cannot be safely interpreted
canonical Settings page cannot be used
visible fatal error occurs
```

The baseline gate exists to avoid overwriting existing fallback values or
unknown persistent configuration.

## Controlled Fallback-saved State Plan

If and only if the baseline gate passes, the future Step 249 human operator may
create a temporary Settings fallback saved state.

Allowed future controlled action:

- enter a locally generated, disposable, non-secret test marker into the OpenAI
  API key fallback field one time,
- do not use a real credential, real API key, or secret-like value,
- do not record the marker string in screenshots, terminal output, docs, result
  notes, or logs,
- do not run OpenAI Generate, GA4 Fetch, OAuth, or any external action,
- do not change Settings fields other than the OpenAI fallback field,
- save Settings once,
- treat this as temporary local test state for UI coverage only, not as provider
  authentication.

The test marker must not be described as an actual API key, credential, or
secret.

Expected saved-state observations:

- OpenAI API key source category becomes `settings_saved`,
- Settings fallback status becomes saved,
- value display status remains hidden,
- password field value remains empty / not visible,
- saved-fallback wording is visible,
- `clear_openai_api_key` checkbox is visible,
- clear-control wording is limited to Settings fallback,
- clear-control wording states constant-based configuration is not changed.

## Controlled Clear And Cleanup Plan

After saved-state observation is complete, Step 249 may allow one controlled
clear operation only when all of the following are true:

```text
clear_openai_api_key checkbox is visible
fallback-saved scope wording is visible
source category is settings_saved
password field value remains hidden / empty
```

Controlled clear operation:

- leave the password field empty,
- select only `clear_openai_api_key`,
- save Settings once,
- confirm post-clear `missing` category at UI category level,
- confirm fallback status returns to `not_saved`,
- confirm password field remains empty / not visible,
- confirm `clear_openai_api_key` checkbox becomes hidden,
- do not re-enter the test marker,
- do not read or output actual option values,
- do not use `wp option get`, database dumps, or terminal inspection.

Cleanup confirmation must remain UI category-level only.

## Constant Non-mutation Boundary

This track targets only the `settings_saved` state and does not create a
constant-configured state.

Step 249 can confirm only this human-visible wording:

```text
Human-visible clear-control wording states that constant-based configuration is not changed.
```

Step 249 must not claim observation of:

```text
Actual constant value existence
Actual constant value preservation
Actual constant mutation behavior under a configured constant
constant_configured source category
```

Actual constant behavior remains separate from this `settings_saved`-only human
track and should not be conflated with source-level boundaries or a later
`constant_configured` human coverage track.

## Step 249 Normalized Human Observation Template

```text
Step 249 normalized human observation:

Baseline:
- Canonical Settings page used: Yes / No
- Settings page loaded: Pass / Fail / Blocked
- Visible fatal error on Settings: Yes / No
- Baseline OpenAI API key source category: missing / other / blocked
- Baseline Settings fallback status: not_saved / other / blocked
- Baseline value display status: hidden / visible_but_unclear / blocked
- Baseline password field value visible: No / Yes / Blocked
- Baseline clear_openai_api_key checkbox visible: No / Yes / Blocked
- Baseline safe to create temporary fallback-saved test state: Yes / No / Blocked

Temporary fallback-saved state:
- Non-secret disposable test marker used: Yes / No
- Actual credential / actual API key used: No / Yes / Blocked
- Settings saved once for controlled state: Yes / No / Blocked
- OpenAI API key source category: settings_saved / visible_but_unclear / blocked
- Settings fallback status: saved / visible_but_unclear / blocked
- Value display status: hidden / visible_but_unclear / blocked
- Password field value visible: No / Yes / Blocked
- Saved fallback wording visible: Yes / No / Blocked
- clear_openai_api_key checkbox visible: Yes / No / Blocked
- Clear-control wording states Settings fallback only: Yes / No / Blocked
- Clear-control wording states constant-based configuration is not changed: Yes / No / Blocked

Report Builder:
- Report Builder page loaded: Pass / Fail / Blocked
- Visible fatal error on Report Builder: Yes / No
- OpenAI key source/category readiness visible: Yes / No / Blocked
- Report Builder source category: settings_saved / visible_but_unclear / blocked
- Report Builder value-hidden status visible: Yes / No / Blocked
- Report Builder source-aware saved-fallback wording visible where applicable: Yes / No / Not applicable / Blocked

Controlled clear and cleanup:
- Password field left empty for clear operation: Yes / No / Blocked
- clear_openai_api_key checkbox selected: Yes / No / Blocked
- Settings saved once for controlled clear: Yes / No / Blocked
- Post-clear source category: missing / visible_but_unclear / blocked
- Post-clear Settings fallback status: not_saved / visible_but_unclear / blocked
- Post-clear value display status: hidden / visible_but_unclear / blocked
- Post-clear password field value visible: No / Yes / Blocked
- Post-clear clear_openai_api_key checkbox visible: No / Yes / Blocked
- Temporary test state cleanup confirmed at UI category level: Yes / No / Blocked

Safety:
- OpenAI Generate executed: No
- GA4 Fetch executed: No
- OAuth Connect / Authorize executed: No
- Google navigation executed: No
- Token endpoint communication executed: No
- Refresh request executed: No
- Revoke request executed: No
- External provider communication intentionally triggered: No
- Screenshots collected: No
- Network evidence collected: No
- Option / token / credential / constant values inspected or recorded: No
- API key / Authorization header inspected or recorded: No
- Request body / raw response / AI payload JSON / generated report body inspected or recorded: No
- Forbidden evidence recorded: No
```

## Pass / Fail / Blocked Criteria

### Scope-bound Pass

Classify Step 249 as `Scope-bound Pass` only when all of the following are true:

- baseline `missing` / `not_saved` state is observed before test-state creation,
- a non-secret disposable marker is used,
- no actual credential or API key is used,
- the controlled saved state displays `settings_saved`,
- Settings fallback status displays saved,
- value-hidden posture is visible,
- password field does not reveal the stored marker,
- clear checkbox is visible only in the saved-fallback condition,
- clear scope wording is visibly limited to Settings fallback,
- constant non-change wording is visibly present,
- Report Builder displays safe `settings_saved` category / readiness and
  value-hidden posture,
- one controlled clear operation returns the visible UI state to `missing` /
  `not_saved`,
- clear checkbox becomes hidden after the controlled clear,
- no prohibited action or forbidden evidence occurs.

### Fail

Classify as `Fail` if any of the following occurs:

- actual credential or actual API key is used,
- API key, marker value, constant value, option value, token, Authorization
  header, request body, raw response, payload, generated report body,
  screenshot, or Network evidence is recorded,
- password field displays the saved marker,
- `settings_saved` cannot be safely observed after controlled save,
- clear checkbox visibility does not match saved-fallback state,
- clear scope wording indicates broader deletion than Settings fallback,
- clear wording claims constant mutation,
- post-clear UI does not return to `missing` / `not_saved`,
- clear checkbox remains visible after the post-clear state,
- OpenAI Generate, GA4 Fetch, OAuth, provider communication, or another
  prohibited operation occurs.

### Blocked

Classify as `Blocked` without saving or clearing if any of the following occurs:

- baseline is not `missing` / `not_saved`,
- existing fallback or unknown persistent configuration may be overwritten,
- test marker cannot be established as clearly non-secret and disposable,
- canonical Settings page is unavailable,
- visible fatal error occurs,
- UI status/category labels cannot be safely interpreted,
- cleanup cannot be safely confirmed at UI category level,
- execution would require option inspection, database dump, actual credential,
  external communication, or widening into `constant_configured`.

### Not applicable

Use `Not applicable` for:

- actual constant preservation behavior in this `settings_saved`-only track,
- constant-configured human coverage,
- provider-side acceptance, validity, quota, permission, endpoint availability,
  model availability, or request success,
- runtime OpenAI error branch verification.

## Step 232 / Step 246 Relationship

Historical Step 232 residual:

```text
OpenAI error wording source-awareness: Needs follow-up wording alignment
```

Step 248 position:

```text
Static source-aware wording: Implemented
Source-level verification: Pass
Limited missing-state Settings / Report Builder human-visible confirmation: Scope-bound Pass
Controlled local-only runtime wording verification: Pass
Settings fallback saved-state / clear-control human coverage: Planned, not executed
Real OpenAI provider communication: Not performed
Provider-side behavior verification: Not performed
Residual fully resolved: Do not claim
```

Step 248 plans the next credential-source UI coverage step only. It does not
execute runtime wording verification, provider-side verification, or
public-release approval.

## Safety / Evidence Posture

Allowed future evidence is limited to:

- source category,
- fallback status,
- value-hidden status,
- password field visible / hidden status,
- control visibility,
- scope wording,
- cleanup status,
- Pass / Fail / Blocked / Not applicable classification.

Forbidden evidence remains:

- credentials,
- API keys,
- marker values,
- key fragments,
- constant values,
- option values,
- tokens,
- Authorization headers,
- request bodies,
- raw responses,
- AI payload JSON,
- generated report bodies,
- screenshots,
- browser Network evidence,
- database dumps,
- cookies,
- sessions,
- nonces.

Step 248 itself collects no new browser, runtime, option, constant, provider, or
database evidence.

## Recommended Next Step

Recommended next step:

```text
Step 249: OpenAI Settings fallback saved-state and clear-control controlled human admin smoke results
```

Step 249 should execute only if the baseline gate passes. If the baseline gate
does not pass, Step 249 should perform no browser save or clear operation and
should remain a docs-only `Blocked` results checkpoint.

## Result Classification

```text
Step 248 result: Controlled human admin smoke plan prepared
Step 249 baseline gate: Defined
Temporary fallback-saved state plan: Defined
Controlled clear and cleanup plan: Defined
Constant non-mutation boundary: Human-visible wording only
Browser smoke executed: No
Settings saved: No
Clear control operated: No
Constant changed: No
External communication: No
Forbidden evidence recorded: No
WordPress.org release readiness: Hold
```
