# Step 247: OpenAI Credential-source Human Coverage Prioritization Checkpoint

## Step Purpose

Step 247 is a docs-only / prioritization-checkpoint-only decision point for the
remaining OpenAI credential-source human coverage.

The purpose is to compare the remaining controlled human smoke candidates and
decide which dedicated track should be planned next. Step 247 does not run
browser smoke, does not create test state, does not save Settings, does not
operate clear controls, does not define constants, and does not perform OpenAI
Generate or external communication.

WordPress.org release readiness remains `Hold`.

## Scope

Candidate coverage paths:

```text
constant_configured human browser path
settings_saved human browser path
fallback-saved clear_openai_api_key control path
```

This step compares coverage value, safety, persistence / cleanup risk, and
relationship to the current MVP source model. It treats each item as coverage
prioritization, not as a defect finding.

## Explicit Non-goals

Step 247 does not:

- run browser admin smoke,
- save Settings,
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
- display, record, or output credential values, API keys, tokens,
  Authorization headers, constant values, option values, request bodies, raw
  responses, AI payload JSON, or generated report bodies.

## Referenced Steps

- `docs/maturation/step229-openai-api-key-storage-public-release-decision-checkpoint.md`
- `docs/maturation/step231-openai-api-key-constant-based-configuration-narrow-production-implementation-results.md`
- `docs/maturation/step234-openai-api-key-source-controlled-human-admin-smoke-results.md`
- `docs/maturation/step242-openai-api-key-source-aware-wording-controlled-human-admin-smoke-results.md`
- `docs/maturation/step243-openai-api-key-source-aware-wording-maturation-checkpoint.md`
- `docs/maturation/step246-openai-error-wording-controlled-local-only-runtime-verification-maturation-checkpoint.md`

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
- A source category does not confirm credential validity, provider-side
  acceptance, quota, permission, endpoint availability, model availability, or
  OpenAI request success.
- The Settings fallback remains part of the current MVP model, but that does not
  classify Settings-based storage as accepted for public release.

## Remaining Candidate Inventory

| Candidate | Target source/category state | Expected human-visible checks | Controlled condition requirement | Persistence / cleanup risk | External communication required | Current priority |
|---|---|---|---|---|---|---|
| `constant_configured human browser path` | Constant source configured, safe source category `constant_configured` visible | Settings / Report Builder source category wording, value-hidden posture, constant-first wording, no value display | Requires a controlled constant-configured environment without recording the constant value | Medium; environment setup and cleanup boundaries must avoid constant value exposure and loading-order ambiguity | No | Priority 2 |
| `settings_saved human browser path` | Settings fallback saved, safe source category `settings_saved` visible | Settings / Report Builder fallback wording, value-hidden posture, saved-state labels, source category labels | Requires a controlled saved fallback state without recording option values or secret-like values | Medium; persistent Settings state may need strict cleanup confirmation | No | Priority 1 |
| `fallback-saved clear_openai_api_key control path` | Settings fallback saved with clear control applicable | Clear checkbox visibility, fallback-only scope wording, constant non-mutation wording, cleanup result category if future execution allows clear | Same controlled saved fallback state as `settings_saved`; future plan must decide whether clear execution is allowed | Medium; clear action changes persistent fallback state and requires cleanup/status confirmation without option value display | No | Priority 1 |

All candidates are human-visible UI coverage candidates only. None validates a
real credential, API key value, constant value, option value, provider-side
acceptance, quota, permission, endpoint availability, model availability, or
OpenAI request success.

OpenAI Generate, GA4 Fetch, OAuth, and external communication are not required
for any candidate in this prioritization checkpoint.

## Candidate Value And Safety Comparison

| Criterion | `settings_saved` + fallback clear-control path | `constant_configured` path |
|---|---|---|
| Public-release posture relevance | High; current MVP Settings fallback remains present and must have safe UX, clear scope, and cleanup boundaries while public-release target remains constant-preferred. | High; confirms the preferred constant-first posture is human-visible, but does not itself settle provider-side or storage posture. |
| Value-hidden posture confirmation value | High; fallback saved state directly exercises non-redisplay behavior and saved-state labels. | Medium; constant path should also avoid value exposure, but constant values must not appear in UI or evidence. |
| Settings fallback cleanup / clear scope value | High; the clear control is tied to saved fallback state and can validate fallback-only wording. | Low for clear-control coverage; constants should not be removed or modified by fallback clear behavior. |
| Constant-first policy confirmation value | Medium; can confirm fallback wording does not override the constant-first policy. | High; direct human-visible confirmation target for constant-first source category. |
| Controlled condition safety | Medium; requires a saved fallback condition and cleanup plan, but can be scoped to non-secret test state and status-only evidence. | Medium to lower; temporary constant condition requires careful environment setup, loading-order control, and cleanup without exposing values. |
| Ability to avoid persistent change | Medium; saved fallback state may be persistent unless plan uses a controlled existing state or defines cleanup. | Medium; constant environment may be non-persistent depending on setup, but setup boundaries must be explicit. |
| Cleanup clarity | Medium; cleanup can be tied to clear-control status if future plan allows it, but option values must not be displayed. | Medium; cleanup can be removal of temporary constant setup, but values and paths must not be recorded beyond source/category-level evidence. |
| Misoperation / residual state risk | Medium; accidental Settings persistence or incorrect clear behavior would affect fallback state. | Medium; accidental constant setup drift or loading-order errors can confuse category observations. |
| Overlap with existing `missing` coverage | Lower; saved fallback and clear-control states were not covered by Step 242. | Lower to medium; also not covered by Step 242, but less directly tied to current fallback cleanup UX. |

## Priority Decision

Checkpoint decision:

```text
Priority 1:
settings_saved + fallback-saved clear_openai_api_key control path

Priority 2:
constant_configured human browser path
```

## Priority 1 Rationale

`settings_saved` and fallback-saved `clear_openai_api_key` control coverage
should be planned first because:

- the `settings_saved` state and fallback-saved clear control are likely
  observable from the same controlled condition,
- clear checkbox visibility, fallback-only scope wording, and constant
  non-mutation wording can likely be checked in the same dedicated track,
- while the current MVP Settings fallback remains present, fallback saved /
  clear behavior is important for UX and cleanup boundaries,
- the checks do not require OpenAI Generate or real external communication,
- real Settings option values must not be displayed or recorded,
- any controlled test state creation and cleanup must be fixed in a separate
  plan before execution,
- future evidence can stay limited to source category, value-hidden status,
  control visibility, scope wording, cleanup status, and Pass / Fail / Blocked
  / Not tested classification.

This priority does not classify Settings storage as the public-release target.
It prioritizes safe observation of the remaining current-MVP fallback UX while
the public-release target remains constant-preferred.

## Priority 2 Rationale

`constant_configured` human browser coverage remains valuable because it can
confirm that the constant-first policy is represented in the normal admin UI at
status/category level.

It is second because:

- temporary constant-source conditions require careful handling of environment
  setup, loading order, and cleanup boundaries,
- the constant source name must not be confused with a constant value,
- constant values must not be displayed or recorded,
- finishing the `settings_saved` / clear-control coverage first does not change
  the constant-first policy,
- constant-source human coverage is better handled as a separate dedicated
  track after fallback saved-state and clear-control boundaries are planned.

## Recommended Step 248 Scope

Recommended next step:

```text
Step 248: OpenAI Settings fallback saved-state and clear-control controlled human admin smoke plan
```

Step 248 should be docs-only / planning-only.

Recommended Step 248 target scope:

```text
settings_saved source category
fallback field value-hidden posture
fallback-saved clear_openai_api_key checkbox visibility
clear control is Settings fallback only
constant source is not deleted or modified by clear control
Settings / Report Builder safe wording and source category labels
controlled cleanup of any temporary non-secret test state
```

Recommended Step 248 exclusions:

```text
constant_configured human browser path
real credential validation
actual API key value confirmation
OpenAI Generate
GA4 Fetch
OAuth
external HTTP communication
provider-side behavior
screenshots
Network evidence
option value display or recording
```

Step 248 should decide explicitly whether a future human check may operate the
clear checkbox, and if so, how cleanup confirmation will be recorded without
displaying or recording option values.

## Controlled State Safety Principles

Future controlled state planning should follow these principles:

- test state must not use real credentials,
- if test state is needed, it must not use, display, or record actual
  secret-like values,
- Settings option values, constant values, and API key values must not be
  displayed in terminal output, browser output, docs, or logs,
- test state creation and cleanup methods must be documented before execution,
- execution results should record only source category, value-hidden status,
  clear control visibility, scope wording, cleanup status, and Pass / Fail /
  Blocked / Not tested classification,
- persistent state requires cleanup confirmation,
- cleanup must not require reading or outputting option values,
- whether to execute the clear control must be decided in the dedicated plan,
- Step 247 itself does not save Settings and does not operate the clear control.

## Step 232 / Step 246 Relationship

Step 232 residual:

```text
OpenAI error wording source-awareness: Needs follow-up wording alignment
```

Current progress:

```text
Static source-aware wording: Implemented
Source-level verification: Pass
Limited missing-state Settings / Report Builder human-visible confirmation: Scope-bound Pass
Controlled local-only runtime wording verification: Pass
Remaining credential-source human coverage: Prioritized, not executed
Real OpenAI provider communication: Not performed
Provider-side behavior verification: Not performed
Residual fully resolved: Do not claim
```

Step 247 prioritizes credential-source UI coverage only. It does not execute
runtime wording verification, does not verify provider-side behavior, and does
not approve a public-release posture.

## Safety / Evidence Posture

Step 247 itself obtains no new browser, runtime, option, constant, or provider
evidence.

Allowed evidence for future tracks should remain limited to:

- source category,
- value-hidden status,
- control visibility,
- scope wording,
- cleanup status,
- Pass / Fail / Blocked / Not tested classification.

Forbidden evidence remains:

- credentials,
- API keys,
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
- browser Network evidence.

WordPress.org release readiness remains:

```text
Hold
```

## Overall Decision

```text
OpenAI credential-source human coverage:
Prioritize the Settings fallback saved-state and fallback-only clear-control path
as the next dedicated controlled human smoke track,
while retaining constant_configured coverage as a separate later track.
```

## Result Classification

```text
Step 247 result: Prioritization checkpoint completed
Priority 1: settings_saved + fallback-saved clear_openai_api_key control path
Priority 2: constant_configured human browser path
Browser smoke executed: No
Settings saved: No
Clear control operated: No
Constant changed: No
External communication: No
Forbidden evidence recorded: No
WordPress.org release readiness: Hold
```
