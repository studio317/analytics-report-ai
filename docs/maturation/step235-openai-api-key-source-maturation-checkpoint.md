# Step 235: OpenAI API Key Source Maturation Checkpoint

## Step Purpose

Step 235 is a docs-only / maturation checkpoint for the OpenAI API key source
and storage posture track from Step 228 through Step 234.

The purpose is to summarize what has matured within the current MVP boundary,
what remains explicitly unverified, and what wording / release-readiness
decisions remain open before any public-release claim.

No production code, Settings UI, Report Builder UI, credential resolver,
OpenAI client, GA4 client, OAuth implementation, `uninstall.php`, tools,
JavaScript, CSS, or `readme.txt` files are changed in this step.

WordPress.org release readiness remains `Hold`.

## Track Scope And Current Decision

This track covers the OpenAI API key source and storage posture, including:

- current MVP Settings fallback storage,
- constant-based OpenAI API key source priority,
- source/category labels,
- value-hidden UI posture,
- Settings fallback clear-scope boundaries,
- OpenAI client request-local key resolution,
- limited human admin smoke coverage,
- remaining wording and verification coverage.

Step 229 selected the public-release target:

```text
Option B: constant-based OpenAI API key configuration preferred over settings storage
```

Current MVP posture:

```text
OpenAI API key source priority: Constant first / Settings fallback / Missing
Settings-saved key: fallback retained within current MVP boundary
Public-release posture final approval: Hold
WordPress.org release readiness: Hold
```

The Settings-saved OpenAI API key fallback remains available in the current MVP
implementation, but this does not mean Settings storage is finally accepted as
the public-release posture.

## Referenced Steps

- `docs/maturation/step228-openai-api-key-storage-posture-checkpoint.md`
- `docs/maturation/step229-openai-api-key-storage-public-release-decision-checkpoint.md`
- `docs/maturation/step230-openai-api-key-constant-based-configuration-implementation-plan.md`
- `docs/maturation/step231-openai-api-key-constant-based-configuration-narrow-production-implementation-results.md`
- `docs/maturation/step232-openai-api-key-constant-based-configuration-source-level-verification-results.md`
- `docs/maturation/step233-openai-api-key-source-human-admin-smoke-plan.md`
- `docs/maturation/step234-openai-api-key-source-controlled-human-admin-smoke-results.md`

This checkpoint records only docs-level references and category/status-level
evidence. It does not record credential values, constant values, option values,
API keys, request bodies, raw responses, AI payload JSON, or generated report
bodies.

## Summary Of Steps 228-234

| Step | Scope | Result |
|---|---|---|
| Step 228 | OpenAI API key storage posture inventory and options | Completed; Option B identified as preferred candidate. |
| Step 229 | Public-release decision checkpoint | Completed; Option B selected as public-release target. |
| Step 230 | Constant-based configuration implementation plan | Completed; planned constant first / Settings fallback / missing priority. |
| Step 231 | Narrow production implementation | Completed; implemented constant-first resolver and source/category UI labels. |
| Step 232 | Source-level verification | Pass; helper, resolver, UI categories, fallback handling, and request boundary verified. |
| Step 233 | Human admin smoke plan | Completed; defined safe observation template. |
| Step 234 | Human admin smoke result recording | Scope-bound Pass for `missing` source category only. |

## Matured Within Current MVP Boundary

The following items can be treated as matured within the current MVP boundary:

| Area | Maturity classification | Notes |
|---|---|---|
| Constant-first priority | Matured within current MVP boundary | `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is the preferred source category. |
| Settings-saved fallback | Matured within current MVP boundary | Settings-saved key remains a fallback, not the preferred public-release source. |
| Source category model | Matured within current MVP boundary | Source categories are limited to `constant_configured`, `settings_saved`, and `missing`. |
| Value-hidden posture | Matured within current MVP boundary | API key value visibility is represented as `hidden`; values are not redisplayed. |
| Settings fallback clear scope | Matured at source level | Clear control is designed to affect only the Settings fallback key. |
| Constant mutation boundary | Matured at source level | Settings save and clear flows do not modify constants. |
| OpenAI client resolver boundary | Matured within current MVP boundary | OpenAI client obtains request-local key material through the resolver. |
| Administrator-triggered request boundary | Preserved | OpenAI request remains tied to Generate AI Report. |
| Generated report storage posture | Preserved | Generated report body remains non-storage. |
| Source-level verification | Pass | Step 232 verified the implementation. |
| Missing-state human smoke | Scope-bound Pass | Step 234 verified the `missing` source category UI path at status/category level. |
| Settings / Report Builder value-hidden readiness for missing state | Scope-bound Pass | Step 234 verified visible category/readiness without forbidden evidence. |

Checkpoint classification:

```text
OpenAI API key source model within current MVP boundary: Matured
OpenAI API key value-hidden posture within current MVP boundary: Matured
OpenAI API key missing-state human admin smoke: Scope-bound Pass
```

## Remaining Verification Coverage

The following items remain explicit coverage gaps, not confirmed defects:

| Area | Status | Notes |
|---|---|---|
| `constant_configured` human browser coverage | Not tested | Step 234 observed only `missing`. |
| `settings_saved` human browser coverage | Not tested | Step 234 observed only `missing`. |
| Clear checkbox visible state with saved Settings fallback | Not tested | Step 234 recorded the checkbox as not visible because the observed state was missing / fallback-empty. |
| Clear checkbox scope wording under saved-fallback condition | Not applicable in Step 234 | Needs a saved-fallback condition before human confirmation. |
| Constant not deleted/modified wording when clear checkbox is visible | Not applicable in Step 234 | Needs the relevant UI state before human confirmation. |
| Actual API key / constant / option state | Not inspected / not inferred | This checkpoint does not infer real credential or option state from UI categories. |

These items should be treated as `remaining verification coverage`, not as
failures.

Step 235 does not infer:

- whether the OpenAI API key constant is actually defined,
- whether a Settings option value exists,
- whether a real API key exists,
- any credential value,
- any option value.

## Wording Alignment Items

Two wording-related items remain separate and should not be conflated.

### A. Step 232 Residual

Step 232 recorded:

```text
OpenAI error wording source-awareness: Needs follow-up wording alignment
```

Checkpoint status:

- Not resolved by Step 234.
- Not reclassified by Step 235.
- Not judged through an external API or error-path smoke.

Reason:

- Step 234 did not run OpenAI Generate.
- Step 234 did not trigger OpenAI error paths.
- Step 234 did not inspect request bodies, raw responses, Authorization
  headers, AI payload JSON, or generated report bodies.

This remains a wording-focused follow-up item.

### B. Step 234 Report Builder Guidance Observation

Step 234 recorded:

```text
Report Builder Settings-only OpenAI key guidance visible
```

Checkpoint status:

- Follow-up observation.
- Not a failure for the limited `missing` source category smoke.
- Not a reason to invalidate the Step 234 scope-bound Pass.
- Potential input for a future source-aware wording alignment plan.

The observation matters because the public-release target is constant-first
configuration. Normal user-facing guidance should eventually be checked for
consistency across:

- constant-first source model,
- Settings fallback model,
- missing state,
- OpenAI error guidance.

Step 235 does not implement or require production wording changes.

## Security / Evidence Posture

Safe evidence remains limited to:

- source category,
- storage category,
- value-hidden category,
- status/category-level wording,
- UI label names,
- scope/readiness categories,
- docs-level references,
- command result categories,
- file-level change summaries.

Forbidden evidence remains outside this track:

- credentials,
- API keys,
- key fragments, prefixes, or suffixes,
- access token values,
- refresh token values,
- OAuth client values,
- Authorization headers,
- option values,
- serialized option values,
- database row contents,
- request bodies,
- raw responses,
- AI payload JSON,
- generated report bodies,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces,
- GA4 Property ID values,
- hostname/domain values,
- analytics values.

Step 235 itself performs no new browser evidence collection, no external API
communication, no option inspection, and no credential inspection.

## Public Release Boundary

WordPress.org release readiness remains:

```text
Hold
```

Reasons:

- public-release readiness was not the scope of this checkpoint,
- OpenAI API key source-aware wording alignment remains open,
- `constant_configured` human browser coverage remains untested,
- `settings_saved` human browser coverage remains untested,
- final readme/privacy wording alignment for the source model remains future
  work,
- broader credential storage posture remains a release-readiness concern,
- Plugin Check / release package review / final QA are outside this step.

Step 235 does not change the final public-release approval state for OpenAI API
key storage.

## Overall Checkpoint Decision

Checkpoint decision:

```text
OpenAI API key source maturation within current MVP boundary:
Matured with bounded remaining verification and wording-alignment items.
```

Detailed decision:

```text
OpenAI API key constant-first source model: Matured within current MVP boundary
Settings-saved key as fallback: Matured within current MVP boundary
Source categories constant_configured / settings_saved / missing: Matured within current MVP boundary
Value-hidden posture: Matured within current MVP boundary
Settings fallback clear-scope source design: Matured at source level
OpenAI client resolver boundary: Matured within current MVP boundary
Step 232 source-level verification: Pass
Step 234 missing-state human admin smoke: Scope-bound Pass
constant_configured human coverage: Remaining verification coverage
settings_saved human coverage: Remaining verification coverage
OpenAI error wording source-awareness: Needs follow-up wording alignment
Report Builder Settings-only guidance observation: Follow-up wording candidate
WordPress.org release readiness: Hold
```

## Recommended Next Step

Recommended next step:

```text
Step 236: OpenAI API key source-aware wording alignment plan
```

Step 236 should be docs-only / planning-only. It should inventory Settings,
Report Builder, and OpenAI error wording that may need source-aware alignment
for:

- constant-first configuration,
- Settings fallback,
- missing state,
- support/debug evidence boundaries.

`constant_configured` and `settings_saved` human browser confirmation should
not be executed in Step 235. If needed later, each should be covered by a
separate controlled human smoke plan before result recording.

## Result Classification

```text
OpenAI API key source maturation checkpoint: Completed
Track scope: OpenAI API key source / storage posture
Current public-release target: Option B / constant-based configuration preferred over settings storage
Current MVP source model: Constant first / Settings fallback / Missing
Matured within current MVP boundary: Yes, with bounded remaining verification and wording-alignment items
Step 232 source-level verification: Pass
Step 234 missing-state human admin smoke: Scope-bound Pass
constant_configured human coverage: Not tested
settings_saved human coverage: Not tested
Step 232 residual wording item: Still open
Step 234 guidance observation: Follow-up wording candidate
Production code changes: None in Step 235
WordPress.org release readiness: Hold
```
