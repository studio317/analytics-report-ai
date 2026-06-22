# Step 241: OpenAI API Key Source-aware Wording Controlled Human Admin Smoke Plan

## Step Purpose

Step 241 is a docs-only / planning-only controlled human admin smoke plan for
the post-Step 239 OpenAI API key source-aware wording changes.

This plan is limited to the already human-observed `missing` OpenAI API key
source category and covers only Settings / Report Builder visible wording,
value-hidden posture, and safe category/readiness labels.

Step 241 does not run browser admin smoke, change production code, change UI
behavior, execute OpenAI runtime error paths, or verify external API behavior.

WordPress.org release readiness remains `Hold`.

## Scope

Target source category:

```text
missing
```

Target admin screens:

```text
Settings
Report Builder
```

Target observations:

- Settings page load status,
- Report Builder page load status,
- visible fatal error status,
- OpenAI API key source/category visibility,
- `missing` source category visibility,
- value-hidden posture,
- Settings fallback field presence and empty-value posture,
- Step 239 Settings missing-state source-aware wording,
- Step 239 Report Builder source-aware missing readiness wording,
- absence of Settings-only guidance as the sole configuration route.

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
  acceptance, permissions, quota, endpoint availability, model availability, or
  OpenAI request success.
- The current MVP Settings fallback does not mean Settings storage is approved
  as the final public-release posture.

## Explicit Non-goals

Step 241 does not include:

- production PHP changes,
- Settings UI changes,
- Report Builder UI changes,
- credential resolver changes,
- OpenAI client behavior changes,
- GA4 client behavior changes,
- OAuth implementation changes,
- `uninstall.php` changes,
- `readme.txt` changes,
- tools changes,
- JavaScript or CSS changes,
- browser admin smoke execution by Codex,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- refresh request,
- revoke request,
- Plugin Check,
- screenshots,
- browser Network evidence collection,
- `wp option get`,
- database dump,
- credential, API key, token, Authorization header, option value, request body,
  raw response, AI payload JSON, or generated report body inspection or
  recording.

## Referenced Steps

- `docs/maturation/step234-openai-api-key-source-controlled-human-admin-smoke-results.md`
- `docs/maturation/step235-openai-api-key-source-maturation-checkpoint.md`
- `docs/maturation/step237-openai-api-key-source-aware-wording-decision-checkpoint.md`
- `docs/maturation/step238-openai-api-key-source-aware-wording-narrow-production-implementation-plan.md`
- `docs/maturation/step239-openai-api-key-source-aware-wording-narrow-production-implementation-results.md`
- `docs/maturation/step240-openai-api-key-source-aware-wording-source-level-verification-results.md`

## Human Smoke Target

The future human smoke should verify only the `missing` source category path.

It should confirm that Step 239 wording is visible in the normal admin UI while
preserving the same safety boundary used in Steps 234 and 240:

- status/category-level labels only,
- no credential values,
- no option values,
- no screenshots,
- no Network evidence,
- no external API actions,
- no OpenAI runtime error path execution.

The smoke should be performed by a human operator in a controlled browser
session. Codex should not perform browser admin smoke for this step.

## Explicitly Excluded From Human Smoke

### OpenAI Runtime Error Wording

The following are not part of the Step 241 / Step 242 human smoke target:

```text
Missing key error branch
401 error wording
authentication error wording
generic OpenAI fallback error wording
```

Reason:

- OpenAI Generate must not be executed.
- OpenAI request, provider response, and runtime error paths must not be
  triggered.
- Step 240 verified these paths only at source level.

Step 241 must not describe the Step 232 residual as resolved,
runtime-verified, or fully resolved.

### Other Source Categories

The following source categories are not tested by this plan:

```text
constant_configured
settings_saved
```

If either category needs human browser coverage later, it should receive a
separate controlled condition, a dedicated human smoke plan, and a
status/category-only observation template before execution.

### Fallback-saved Clear Control

The `clear_openai_api_key` checkbox is not part of this `missing` /
fallback-empty smoke target.

Expected classification:

```text
clear_openai_api_key checkbox: Not applicable if hidden
clear_openai_api_key scope wording: Not applicable if hidden
Constant delete/modify wording: Not applicable if clear control is hidden
```

## Human Browser Prohibitions

The future human smoke plan must prohibit:

- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- refresh request,
- revoke request,
- screenshots,
- browser Network evidence,
- `wp option get` for option values,
- API key / token / Authorization header / option value inspection,
- API key / token / Authorization header / option value recording,
- request body / raw response / AI payload JSON / generated report body
  inspection,
- request body / raw response / AI payload JSON / generated report body
  recording.

## Step 242 Normalized Human Observation Template

Use status/category-level observations only.

```text
Step 242 normalized human observation:
- Settings page loaded: Pass / Fail / Blocked
- Visible fatal error on Settings: Yes / No
- Canonical Settings page used: Yes / No
- OpenAI API key source category visible: Yes / No / Blocked
- OpenAI API key source category result: missing / visible_but_unclear / blocked
- OpenAI API key value visibility shown as hidden: Yes / No / Blocked
- OpenAI API key field value visible: No / Yes / Blocked
- Settings fallback field present: Yes / No / Blocked
- Settings fallback field value empty: Yes / No / Blocked
- Settings missing-state source-aware guidance visible: Yes / No / Blocked
- Settings missing-state guidance presents constant source before Settings fallback: Yes / No / Blocked
- Settings-only configuration route presented as the sole guidance: No / Yes / Blocked
- clear_openai_api_key checkbox visible: No / Yes / Blocked
- clear_openai_api_key scope wording: Not applicable / visible_but_unclear / blocked
- Constant delete/modify wording: Not applicable / visible_but_unclear / blocked

- Report Builder page loaded: Pass / Fail / Blocked
- Visible fatal error on Report Builder: Yes / No
- Report Builder OpenAI key source/category readiness visible: Yes / No / Blocked
- Report Builder source category result: missing / visible_but_unclear / blocked
- Report Builder source-aware missing guidance visible: Yes / No / Blocked
- Report Builder guidance presents constant source before Settings fallback: Yes / No / Blocked
- Report Builder Settings-only configuration route presented as the sole guidance: No / Yes / Blocked
- Report Builder value-hidden status visible: Yes / No / Blocked

- OpenAI Generate executed: No
- OpenAI runtime error path executed: No
- 401 / authentication error path executed: No
- Generic OpenAI error path executed: No
- GA4 Fetch executed: No
- OAuth Connect / Authorize executed: No
- Google navigation executed: No
- Token endpoint communication executed: No
- Refresh request executed: No
- Revoke request executed: No
- Screenshots collected: No
- Network evidence collected: No
- Option/token/credential/OAuth client values inspected or recorded: No
- API key / Authorization header inspected or recorded: No
- Request body / raw response / AI payload JSON / generated report body inspected or recorded: No
- Forbidden evidence recorded: No
```

## Pass / Fail / Blocked Classification

### Scope-bound Pass

Classify Step 242 as scope-bound Pass only if all of the following are true:

- Settings loads.
- Report Builder loads.
- No visible fatal error is observed.
- The `missing` source category is visible as a safe category label.
- API key value is not visible.
- Settings missing guidance is source-aware.
- Report Builder missing guidance is source-aware.
- Constant source is presented as the preferred route before Settings fallback.
- Settings fallback is presented as the current MVP fallback after the constant
  source.
- Settings-only route is not presented as the sole configuration method.
- OpenAI Generate is not executed.
- GA4 Fetch is not executed.
- OAuth, Google navigation, token endpoint communication, refresh, revoke, and
  external communication are not executed.
- Forbidden evidence is not recorded.

### Fail

Classify as Fail if any of the following occurs:

- Settings page load failure.
- Report Builder page load failure.
- visible fatal error.
- `missing` category is not visible or cannot be safely interpreted as a
  category label.
- API key value is visible.
- missing guidance presents Settings-only route as the sole configuration path.
- constant source and Settings fallback ordering cannot be confirmed.
- source-aware wording cannot be confirmed.
- forbidden evidence is recorded.
- any prohibited action is performed.

### Blocked / Not applicable / Not tested

Use these classifications as follows:

- `clear_openai_api_key` checkbox, clear scope, and constant non-mutation wording
  are `Not applicable` unless a fallback-saved state makes the control visible.
- OpenAI runtime error wording is `Not applicable` or `Not tested` because
  Generate and runtime error paths are not executed.
- `constant_configured` is `Not tested`.
- `settings_saved` is `Not tested`.
- Use `Blocked` for page access, login, local environment, or admin navigation
  issues that prevent the safe observation from being made without widening
  scope.

## Safety / Evidence Boundary

Allowed evidence:

- page loaded status,
- visible fatal error status,
- source category,
- readiness category,
- value-hidden status,
- fallback field presence / empty status,
- static wording direction,
- clear control visibility category,
- Pass / Fail / Blocked / Not applicable / Not tested classification.

Forbidden evidence:

- credentials,
- API keys,
- key fragments, prefixes, or suffixes,
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
- cookies,
- sessions,
- nonces,
- database dumps,
- GA4 Property ID values,
- hostname/domain values,
- analytics values.

## Step 232 Residual Boundary

Step 232 residual:

```text
OpenAI error wording source-awareness: Needs follow-up wording alignment
```

Step 241 treatment:

- Step 241 plans only Settings / Report Builder human-visible wording smoke for
  the `missing` category.
- Step 241 does not execute OpenAI Generate.
- Step 241 does not execute runtime OpenAI error paths.
- Step 241 does not verify 401 / authentication / generic OpenAI error wording
  in browser.
- Step 241 must not claim the residual is runtime-verified or fully resolved.

## Step 234 Observation Boundary

Step 234 observation:

```text
Report Builder Settings-only OpenAI key guidance visible
```

Step 241 treatment:

- Step 241 plans a human-visible check that the Step 239 source-aware Report
  Builder missing guidance is visible.
- Step 234 `missing` category Scope-bound Pass remains unchanged.
- Step 241 does not run browser smoke.
- Step 242 should record only human-provided status/category-level observations.

## Result Classification

```text
Step 241 result: Controlled human admin smoke plan prepared
Target source category: missing
Target screens: Settings / Report Builder
Browser smoke: Not performed
Runtime OpenAI error-path verification: Not performed
constant_configured human smoke: Not included
settings_saved human smoke: Not included
WordPress.org release readiness: Hold
```

## Recommended Next Step

Recommended next step:

```text
Step 242: OpenAI API key source-aware wording controlled human admin smoke results
```

Step 242 should record human browser observations for the `missing` category
Settings / Report Builder wording path using status/category-level evidence
only.

If `constant_configured` or `settings_saved` human smoke becomes necessary, each
should receive a separate controlled condition and dedicated plan before any
execution.
