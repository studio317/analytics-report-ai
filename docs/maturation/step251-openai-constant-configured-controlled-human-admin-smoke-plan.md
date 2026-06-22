# Step 251: OpenAI Constant-configured Controlled Human Admin Smoke Plan

## Step Purpose

Step 251 is a docs-only / planning-only controlled human admin smoke plan for
future `constant_configured` source category UI coverage.

The purpose is to define how a future Step 252 human smoke can safely create a
temporary local-only constant fixture, observe Settings / Report Builder UI
status/category labels, and clean up the fixture without using real credentials,
real API keys, OpenAI Generate, real provider communication, or external HTTP.

Step 251 does not create the fixture, define a constant, run browser smoke, save
Settings, operate clear controls, or execute OpenAI Generate.

WordPress.org release readiness remains `Hold`.

## Scope

Future Step 252 observation target:

```text
constant_configured source category
Settings / Report Builder safe source category and readiness labels
value-hidden posture
preferred constant source active wording
Settings fallback is lower-priority / not saved wording where applicable
temporary fixture removal after visible UI cleanup
```

This track is UI coverage only. It does not validate real OpenAI credentials,
provider authentication, request success, constant values, actual constant
preservation, actual constant mutation behavior, or interactions between
`constant_configured` and saved fallback states.

## Explicit Non-goals

Step 251 does not:

- run browser admin smoke,
- create, place, execute, or delete a temporary fixture,
- create, place, or modify a mu-plugin,
- add, change, or remove constants,
- change `wp-config.php`,
- save Settings,
- input into the OpenAI fallback field,
- operate the `clear_openai_api_key` checkbox,
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
- create or run a temporary helper or harness,
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
- `docs/maturation/step249-openai-settings-fallback-saved-state-and-clear-control-controlled-human-admin-smoke-results.md`
- `docs/maturation/step250-openai-credential-source-coverage-maturation-checkpoint.md`

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
- A source category does not confirm actual credential validity, provider
  acceptance, quota, permission, endpoint availability, model availability, or
  OpenAI request success.
- Settings fallback is a current MVP fallback and does not mean Settings-based
  storage is accepted for public release.

## Step 252 Baseline Gate

Before any future fixture creation, the human operator should use the canonical
Settings page and confirm only status/category-level baseline state:

```text
OpenAI API key source category: missing
Settings fallback status: not_saved
Value display status: hidden
Password field value visible: No
clear_openai_api_key checkbox visible: No
Existing temporary constant fixture present: No
```

Step 252 should be classified as `Blocked` and should not create a fixture or
perform browser confirmation if any of the following is true:

```text
source category is not missing
Settings fallback status is not not_saved
clear checkbox is already visible
existing temporary fixture may remain
canonical Settings page cannot be used
visible fatal error occurs
baseline cannot be safely interpreted
```

The baseline gate prevents confusion with existing fallback state, existing
constant state, or leftover temporary local fixtures.

## Temporary Constant Fixture Plan

If and only if the baseline gate passes, a future Step 252 may use a temporary
local-only fixture to create the `constant_configured` UI state.

### Fixture Location And Load-order Boundary

Future fixture location candidate:

```text
/var/www/html/wp-dev/wp-content/mu-plugins/
```

Reason:

- mu-plugins normally load before regular plugins,
- that makes the location a candidate for defining the temporary constant before
  Analytics Report AI resolves the OpenAI API key source,
- `/var/www/html/wp-dev-check` must not be used,
- the repository, plugin source, `wp-config.php`, and database must not retain
  test fixture state.

Future execution should use one fixed temporary file name, for example:

```text
arai-step252-openai-constant-fixture.php
```

The fixture source, marker, constant value, and execution logs must not be
included in docs, results, screenshots, or completion reports.

### Fixture Value Boundary

Future fixture value rules:

- define the constant source name once for the local-only fixture condition,
- use a locally generated, disposable, non-secret test marker,
- do not use a real API key, real credential, or secret-like value,
- do not record the marker string in browser output, terminal output, docs,
  results, logs, or screenshots,
- do not run OpenAI Generate,
- do not use the fixture value for provider authentication,
- do not write options,
- do not save Settings,
- do not write to the database,
- do not make HTTP requests,
- do not emit debug output,
- avoid constant redefinition warnings by requiring a safe execution condition.

### Fixture Cleanup

After Settings / Report Builder observation, future Step 252 must remove the
temporary fixture and confirm cleanup at UI category level.

Post-cleanup observation targets:

```text
temporary fixture removed: Yes / No / Blocked
mu-plugin location clean: Yes / No / Blocked
post-cleanup Settings source category: missing / visible_but_unclear / blocked
post-cleanup Settings fallback status: not_saved / visible_but_unclear / blocked
post-cleanup clear checkbox visible: No / Yes / Blocked
post-cleanup password field value visible: No / Yes / Blocked
```

Do not inspect or record actual constant values, actual constant existence,
`wp-config.php` values, option values, or database contents.

## Step 252 Target UI Observations

### Settings

Future Step 252 should observe, without saving Settings:

```text
constant_configured source category visible
value-hidden status visible
password field value visible: No
Settings fallback status: not_saved / visible_but_unclear / blocked
clear_openai_api_key checkbox visible: No / Yes / Blocked
preferred constant source active wording visible
Settings fallback described as lower priority where applicable
Settings-only configuration route presented as sole guidance: No / Yes / Blocked
```

The clear checkbox is generally expected to be `No` because the future
condition is not a fallback-saved state. Step 252 should keep static expectation
and human observation separate.

### Report Builder

Future Step 252 should observe, without pressing OpenAI Generate:

```text
Report Builder page loaded: Pass / Fail / Blocked
Visible fatal error on Report Builder: Yes / No
OpenAI key source/category readiness visible: Yes / No / Blocked
Report Builder source category: constant_configured / visible_but_unclear / blocked
Report Builder value-hidden status visible: Yes / No / Blocked
Preferred constant source active wording visible where applicable: Yes / No / Blocked
Settings-only recovery guidance presented as sole route: No / Yes / Blocked
```

## `constant_configured` Track Boundary

Verified within scope only if future Step 252 passes:

```text
Temporary local-only fixture can produce constant_configured UI category.
Settings and Report Builder display safe constant_configured category/readiness.
Value-hidden posture is maintained.
No Settings-only sole configuration route is shown.
Fixture cleanup returns visible UI category to missing / not_saved.
```

Still not verified by Step 252:

```text
Actual constant value existence
Actual production constant setup
Actual constant value preservation
Actual constant mutation behavior
Constant-configured interaction with settings_saved fallback
Actual credential validity
OpenAI request behavior
Provider-side authentication acceptance
Provider response behavior
Quota / permission / endpoint / model availability
OpenAI request success
```

Future Step 252 must not overstate constant-state, provider-side, or release
readiness.

## Step 252 Normalized Human Observation Template

```text
Step 252 normalized human observation:

Baseline:
- Canonical Settings page used: Yes / No
- Settings page loaded: Pass / Fail / Blocked
- Visible fatal error on Settings: Yes / No
- Baseline source category: missing / other / blocked
- Baseline fallback status: not_saved / other / blocked
- Baseline value display status: hidden / visible_but_unclear / blocked
- Baseline password field value visible: No / Yes / Blocked
- Baseline clear_openai_api_key checkbox visible: No / Yes / Blocked
- Existing temporary fixture present: No / Yes / Blocked
- Baseline safe to create temporary constant fixture: Yes / No / Blocked

Fixture state:
- Temporary local-only fixture created: Yes / No / Blocked
- Temporary fixture non-secret disposable marker used: Yes / No / Blocked
- Actual credential / actual API key used: No / Yes / Blocked
- Constant redefinition warning or visible fatal error: No / Yes / Blocked
- Settings source category: constant_configured / visible_but_unclear / blocked
- Settings value display status: hidden / visible_but_unclear / blocked
- Settings password field value visible: No / Yes / Blocked
- Settings fallback status: not_saved / visible_but_unclear / blocked
- clear_openai_api_key checkbox visible: No / Yes / Blocked
- Preferred constant source active wording visible: Yes / No / Blocked
- Settings-only configuration route presented as sole guidance: No / Yes / Blocked

Report Builder:
- Report Builder page loaded: Pass / Fail / Blocked
- Visible fatal error on Report Builder: Yes / No
- OpenAI key source/category readiness visible: Yes / No / Blocked
- Report Builder source category: constant_configured / visible_but_unclear / blocked
- Report Builder value-hidden status visible: Yes / No / Blocked
- Preferred constant source active wording visible where applicable: Yes / No / Blocked
- Report Builder Settings-only configuration route presented as sole guidance: No / Yes / Blocked

Cleanup:
- Temporary fixture removed: Yes / No / Blocked
- Mu-plugin location clean: Yes / No / Blocked
- Post-cleanup Settings page loaded: Pass / Fail / Blocked
- Post-cleanup visible fatal error: Yes / No
- Post-cleanup source category: missing / visible_but_unclear / blocked
- Post-cleanup fallback status: not_saved / visible_but_unclear / blocked
- Post-cleanup value display status: hidden / visible_but_unclear / blocked
- Post-cleanup password field value visible: No / Yes / Blocked
- Post-cleanup clear_openai_api_key checkbox visible: No / Yes / Blocked
- Temporary constant fixture cleanup confirmed at UI category level: Yes / No / Blocked

Safety:
- Settings saved: No
- clear_openai_api_key operated: No
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
- Constant / option / token / credential values inspected or recorded: No
- API key / Authorization header inspected or recorded: No
- Request body / raw response / AI payload JSON / generated report body inspected or recorded: No
- Forbidden evidence recorded: No
```

## Pass / Fail / Blocked Criteria

### Scope-bound Pass

Classify Step 252 as `Scope-bound Pass` only if all of the following are true:

- baseline `missing / not_saved / hidden` is observed,
- no existing temporary fixture is present,
- temporary fixture is limited to the local `wp-dev` mu-plugin location,
- no actual credential or actual API key is used,
- fixture state shows `constant_configured` in Settings and Report Builder,
- value-hidden posture is maintained,
- password field does not display the fixture marker,
- Settings-only route is not shown as the sole configuration route,
- Settings fallback does not become a saved state,
- clear checkbox is hidden,
- fixture removal returns visible Settings UI category to `missing / not_saved`,
- fixture and mu-plugin location become clean,
- Settings save, clear operation, OpenAI Generate, GA4 Fetch, OAuth, external
  communication, screenshots, and Network evidence are not performed,
- forbidden evidence is not recorded.

### Fail

Classify as `Fail` if any of the following occurs:

- actual credential, actual API key, or secret-like value is used,
- constant value, marker value, option value, credential, token, header,
  request body, raw response, payload, generated report body, screenshot, or
  Network evidence is displayed or recorded,
- constant redefinition warning or visible fatal error occurs,
- `constant_configured` category cannot be safely confirmed,
- password field displays the fixture marker,
- Settings-only route appears as the sole configuration route,
- cleanup does not return to `missing / not_saved` UI state,
- fixture remains or mu-plugin location is not clean,
- Settings save, clear operation, OpenAI Generate, GA4 Fetch, OAuth, external
  communication, or another prohibited operation occurs.

### Blocked

Classify as `Blocked` without creating a fixture or performing browser
observation if any of the following occurs:

- baseline is not `missing / not_saved`,
- existing unknown fixture or unknown constant state may be present,
- constant redefinition cannot be safely prevented,
- temporary fixture creation / cleanup cannot be guaranteed within local
  `wp-dev`,
- cleanup cannot be confirmed at UI category level,
- execution would require a real credential, option inspection, actual constant
  value confirmation, or external communication,
- canonical admin page is unavailable,
- visible fatal error occurs.

### Not applicable

Use `Not applicable` for:

- actual constant existence / value / preservation / mutation behavior,
- `constant_configured` and `settings_saved` fallback priority conflict,
- provider-side behavior,
- quota,
- permission,
- endpoint availability,
- model availability,
- OpenAI request success,
- runtime error branch verification.

## Step 250 Relationship

Step 250 checkpoint:

```text
OpenAI credential-source coverage within the current MVP boundary:
Matured for missing and Settings fallback saved-state human-visible paths,
fallback-only clear-control UI cleanup,
and controlled local-only error wording branches,
with bounded constant_configured, real-runtime,
and provider-side verification coverage remaining.
```

Step 251 is the execution plan for the remaining `constant_configured` human UI
path. It does not complete that coverage and does not approve public release.

## Recommended Next Step

Recommended next step:

```text
Step 252: OpenAI constant-configured controlled human admin smoke results
```

Step 252 should execute only if the baseline gate and temporary fixture
creation / cleanup safety conditions are satisfied. If they are not satisfied,
Step 252 should not create a fixture and should remain a docs-only `Blocked`
results checkpoint.

## Safety / Evidence Posture

Step 251 itself obtains no new browser, runtime, fixture, option, constant,
provider, or database evidence.

Allowed future evidence is limited to:

- source category,
- fallback status,
- value-hidden status,
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
- database dumps.

WordPress.org release readiness remains:

```text
Hold
```

## Result Classification

```text
Step 251 result: Controlled human admin smoke plan prepared
Step 252 baseline gate: Defined
Temporary local-only fixture plan: Defined
Mu-plugin load-order boundary: Defined
Fixture cleanup plan: Defined
Settings / Report Builder observation template: Defined
Browser smoke executed: No
Fixture created: No
Constant changed: No
Settings saved: No
Clear control operated: No
External communication: No
Forbidden evidence recorded: No
WordPress.org release readiness: Hold
```
