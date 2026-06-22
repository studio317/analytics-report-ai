# Step 236: OpenAI API Key Source-aware Wording Alignment Plan

## Step Scope

Step 236 is a docs-only / planning-only wording alignment plan for the OpenAI
API key source model.

The current MVP source model is:

```text
Constant first / Settings fallback / Missing
```

This step plans future source-aware wording for Settings, Report Builder, and
OpenAI error wording. It does not implement production wording changes.

WordPress.org release readiness remains `Hold`.

## Explicit Non-goals

Step 236 does not:

- change production PHP,
- change Settings UI,
- change Report Builder UI,
- change the credential resolver,
- change OpenAI client behavior,
- change GA4 client behavior,
- change OAuth implementation,
- change `uninstall.php`,
- change tools,
- change JavaScript or CSS,
- change `readme.txt`,
- re-verify source category resolution,
- re-verify OpenAI request execution,
- run `constant_configured` human browser smoke,
- run `settings_saved` human browser smoke,
- run browser admin smoke,
- run Plugin Check,
- run GA4 Fetch,
- run OpenAI Generate,
- start OAuth Connect / Authorize,
- navigate to Google,
- call token endpoints,
- execute refresh requests,
- execute revoke requests,
- collect screenshots,
- collect browser Network evidence,
- inspect database dumps,
- run `wp option get`,
- inspect or record credential values, API keys, tokens, Authorization headers,
  option values, request bodies, raw responses, AI payload JSON, or generated
  report bodies.

## Referenced Steps

- `docs/maturation/step228-openai-api-key-storage-posture-checkpoint.md`
- `docs/maturation/step229-openai-api-key-storage-public-release-decision-checkpoint.md`
- `docs/maturation/step230-openai-api-key-constant-based-configuration-implementation-plan.md`
- `docs/maturation/step231-openai-api-key-constant-based-configuration-narrow-production-implementation-results.md`
- `docs/maturation/step232-openai-api-key-constant-based-configuration-source-level-verification-results.md`
- `docs/maturation/step233-openai-api-key-source-human-admin-smoke-plan.md`
- `docs/maturation/step234-openai-api-key-source-controlled-human-admin-smoke-results.md`
- `docs/maturation/step235-openai-api-key-source-maturation-checkpoint.md`

This plan records only docs-level and category/status-level evidence. It does
not record API key values, constant values, option values, credentials,
Authorization headers, request/response bodies, AI payload JSON, or generated
report bodies.

## Fixed Source Model

The source model terms are:

| Term | Meaning |
|---|---|
| Constant source | The configuration source represented by `ANALYTICS_REPORT_AI_OPENAI_API_KEY`. |
| Settings fallback source | The OpenAI API key fallback saved through the plugin Settings UI. |
| Source category | The safe UI/support category returned by the source model. |

Valid source categories:

```text
constant_configured
settings_saved
missing
```

Important terminology boundary:

- `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is the proposed constant name / constant
  source.
- `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is not a source category.
- `constant_configured` is the source category for an active constant source.

Public-release target remains:

```text
Option B: constant-based OpenAI API key configuration preferred over settings storage
```

Current MVP still retains Settings-saved key handling as a fallback. That
fallback remains distinct from the preferred public-release target.

## Wording Alignment Surfaces

### A. Settings

Settings wording should describe source state and user actions without
revealing credential material.

Planning targets:

- explain constant-first priority,
- explain that a Settings-saved key is a fallback,
- provide safe guidance for the `missing` state,
- explain value-hidden posture,
- explain the meaning of the Settings fallback field,
- explain `clear_openai_api_key` scope only when the clear control is present,
- explain that Settings save/clear actions do not change or delete the constant
  source,
- avoid any statement that implies the plugin can display or manage the
  constant value.

Out of scope for Settings wording:

- real credential values,
- constant values,
- option values,
- actual existence or content of saved values beyond safe source categories.

### B. Report Builder

Report Builder wording should describe readiness and next action at a safe
status/category level.

Planning targets:

- explain OpenAI API key source/category readiness,
- provide safe guidance for the `missing` state,
- avoid Settings-only guidance when both constant source and Settings fallback
  are possible,
- align wording with the constant-first public-release target,
- keep generated report and payload safety boundaries unchanged,
- preserve the fact that OpenAI Generate is the only action that can trigger an
  OpenAI request.

Step 234 observation:

```text
Report Builder Settings-only OpenAI key guidance visible
```

This is a future wording alignment candidate. It is not, by itself, a defect
or immediate production-change requirement. It did not invalidate the Step 234
scope-bound Pass for the observed `missing` category path.

### C. OpenAI Error Wording

Step 232 residual:

```text
OpenAI error wording source-awareness: Needs follow-up wording alignment
```

Planning targets:

- avoid Settings-only error guidance when the key source model is constant
  first / Settings fallback / missing,
- describe credential source category or readiness state without revealing key
  material,
- give a safe next action to the administrator,
- avoid raw provider response text,
- avoid request body, Authorization header, raw response, AI payload JSON, and
  generated report body evidence.

Step 236 does not run OpenAI Generate or trigger error paths. It does not judge
the real runtime error contents beyond the prior source-level residual.

## Source Category Wording Matrix

| Source category | Settings wording objective | Report Builder wording objective | OpenAI error wording objective | Permitted evidence level | Prohibited disclosure | Human smoke coverage status | Implementation status |
|---|---|---|---|---|---|---|---|
| `constant_configured` | State that constant source is active and preferred; Settings fallback, if present, is lower priority. | State that OpenAI key readiness is satisfied by the constant source category. | If an OpenAI key-related error occurs, avoid implying Settings is the only source; point to source-aware configuration checks. | Source category, value-hidden status, readiness category. | Constant value, key fragments, option values, Authorization header, request/response. | Not tested in human browser smoke. | Source model implemented; wording alignment planning only. |
| `settings_saved` | State that Settings fallback is saved and hidden; explain it is fallback rather than preferred public-release target. | State that readiness is satisfied by Settings fallback while keeping constant source as preferred posture. | If an OpenAI key-related error occurs, guide review of source-aware configuration without revealing or requesting the saved value. | Source category, fallback status, value-hidden status. | Settings value, option value, key fragments, Authorization header, request/response. | Not tested in human browser smoke. | Source model implemented; wording alignment planning only. |
| `missing` | State that no OpenAI API key source category is configured; offer safe guidance to configure the constant source or Settings fallback. | State that OpenAI key readiness is missing and that generation should not be attempted until a safe source is configured. | State that the key source is missing or unavailable at readiness level; avoid exposing configuration internals or raw error details. | Missing category, readiness category, action-oriented guidance. | Credential values, option values, constant values, headers, request/response, generated report body. | Scope-bound Pass in Step 234. | Source model implemented; wording alignment planning only. |

## Safe Wording Principles

Future wording should be:

- category-level,
- value-hidden,
- action-oriented,
- source-aware,
- safe for support/debug evidence.

Wording must not include:

- credential values,
- API key values,
- key fragments, prefixes, or suffixes,
- constant values,
- option values,
- tokens,
- Authorization headers,
- request bodies,
- raw responses,
- AI payload JSON,
- generated report bodies,
- screenshots or Network evidence.

Terms such as "configured", "saved", or "missing" should refer only to safe UI
source categories / readiness categories. They must not imply that a real
stored value was inspected.

The wording must keep these distinctions clear:

- constant source is preferred,
- Settings fallback remains available in the current MVP,
- Settings fallback is not the preferred public-release target,
- Settings clear controls affect only Settings fallback values,
- Settings clear controls do not change or delete the constant source.

## Step 234 Observation And Step 232 Residual

These two items are related but separate.

### Step 234 Observation

```text
Report Builder Settings-only OpenAI key guidance visible
```

Classification:

- future wording alignment candidate,
- not a failure of the `missing` category scope-bound smoke,
- not an immediate production-change requirement in Step 236.

### Step 232 Residual

```text
OpenAI error wording source-awareness: Needs follow-up wording alignment
```

Classification:

- still open,
- not resolved by Step 234,
- not resolved by Step 236,
- should be addressed through a future wording decision and implementation
  sequence.

Connection:

- both relate to source-aware wording,
- both should inform the future wording decision checkpoint,
- they should not be treated as the same problem,
- neither should trigger implementation in Step 236.

## Decision Points Before Implementation

Before a narrow production wording implementation begins, a decision checkpoint
should answer:

| Decision point | Options to decide later |
|---|---|
| Settings source priority wording | Minimal category labels only, or brief explanatory text for constant-first priority. |
| Settings fallback wording | Keep as "fallback" consistently, or add stronger public-release caveat. |
| Missing-state guidance | Mention constant source and Settings fallback together, or emphasize constant source first with fallback secondary. |
| Clear control wording | Whether to repeat "Settings fallback only" in label, description, or both when the checkbox is visible. |
| Constant non-mutation wording | Whether to show only near clear control, or also in general credential storage guidance. |
| Report Builder readiness wording | Show source category only, or add short source-aware action guidance. |
| Report Builder Settings-only guidance | Replace with source-aware guidance, or preserve temporarily with a follow-up notice. |
| OpenAI error wording | Show source category directly, or keep a safer readiness/action message without source category labels. |
| Support/debug wording | Decide shared evidence level for UI, docs, and support instructions. |
| Readme alignment | Decide how future readme text should distinguish public-release target from current MVP fallback. |

No production wording should be changed until these decisions are fixed.

## Future Implementation Boundary

A future wording implementation should remain narrow:

- translatable WordPress admin strings only,
- safe escaping,
- no credential or option value output,
- no source model logic changes,
- no OpenAI request changes,
- no GA4 changes,
- no readme change unless the step explicitly includes readme wording,
- no external API or browser smoke execution.

Any future implementation should be followed by source-level verification and,
if needed, controlled human admin smoke planning.

## Recommended Next Step

Recommended next step:

```text
Step 237: OpenAI API key source-aware wording decision checkpoint
```

Step 237 should be docs-only / planning-only. It should choose the wording
decisions needed before production wording implementation.

`constant_configured` and `settings_saved` controlled human smoke should not be
executed in Step 236 or Step 237. If needed later, each should first receive a
separate controlled human smoke plan.

## Result Classification

```text
OpenAI API key source-aware wording alignment plan: Completed
Plan type: Docs-only / planning-only
Source model: Constant first / Settings fallback / Missing
Constant source: ANALYTICS_REPORT_AI_OPENAI_API_KEY
Source categories: constant_configured / settings_saved / missing
Settings wording surface: Planned
Report Builder wording surface: Planned
OpenAI error wording surface: Planned
Step 232 residual: Preserved as wording follow-up
Step 234 Report Builder guidance observation: Preserved as wording candidate
Production implementation: Not performed
WordPress.org release readiness: Hold
```
