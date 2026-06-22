# Step 237: OpenAI API Key Source-aware Wording Decision Checkpoint

## Step Purpose

Step 237 is a docs-only / decision-only checkpoint for future OpenAI API key
source-aware wording.

It fixes the wording policy needed before any narrow production wording
implementation for Settings, Report Builder, and OpenAI error messages.

This step does not change production wording, runtime behavior, credential
resolution, OpenAI request behavior, GA4 behavior, or release readiness.

WordPress.org release readiness remains `Hold`.

## Scope

The decisions in this document apply to the current MVP OpenAI API key source
model:

```text
Constant first / Settings fallback / Missing
```

Fixed terms:

| Term | Meaning |
|---|---|
| Constant source | The implemented constant-based configuration source represented by `ANALYTICS_REPORT_AI_OPENAI_API_KEY`. |
| Settings fallback source | The current MVP fallback source saved through the plugin Settings UI. |
| Source category | A safe status/category label used for UI, support, and verification. |

Valid safe source categories:

```text
constant_configured
settings_saved
missing
```

Terminology boundary:

- `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is the constant source name.
- `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is not a source category.
- `constant_configured`, `settings_saved`, and `missing` are safe source
  categories.
- The constant-first source model was implemented in Step 231, so the constant
  source should not be described as merely proposed.
- The current MVP Settings fallback remains available, but it is not the
  preferred public-release storage target.

Public-release target remains:

```text
Option B: constant-based OpenAI API key configuration preferred over settings storage
```

## Explicit Non-goals

Step 237 does not:

- change production PHP,
- change Settings UI,
- change Report Builder UI,
- change the credential resolver,
- change the OpenAI client,
- change the GA4 client,
- change OAuth implementation,
- change `uninstall.php`,
- change tools,
- change JavaScript or CSS,
- change `readme.txt`,
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

- `docs/maturation/step228-openai-api-key-storage-posture-checkpoint.md`
- `docs/maturation/step229-openai-api-key-storage-public-release-decision-checkpoint.md`
- `docs/maturation/step230-openai-api-key-constant-based-configuration-implementation-plan.md`
- `docs/maturation/step231-openai-api-key-constant-based-configuration-narrow-production-implementation-results.md`
- `docs/maturation/step232-openai-api-key-constant-based-configuration-source-level-verification-results.md`
- `docs/maturation/step234-openai-api-key-source-controlled-human-admin-smoke-results.md`
- `docs/maturation/step235-openai-api-key-source-maturation-checkpoint.md`
- `docs/maturation/step236-openai-api-key-source-aware-wording-alignment-plan.md`

This checkpoint records only docs-level, status-level, and category-level
evidence. It does not record credential material or runtime request/response
content.

## Decision Summary

Step 237 fixes these wording decisions:

- Settings should make constant-first priority clear.
- Settings should describe the Settings-saved OpenAI API key as a lower-priority
  fallback for the current MVP.
- Value-hidden wording should remain explicit: values are not redisplayed.
- Missing-state guidance should mention the preferred constant source first,
  then the current MVP Settings fallback.
- Clear checkbox wording should appear only when the fallback-clear control is
  visible and should state that it affects only the Settings fallback.
- Report Builder should avoid Settings-only guidance and use source-aware
  readiness/action guidance.
- OpenAI error wording should avoid raw provider text and avoid implying that
  Settings is the only valid source.
- Support/debug evidence boundaries should stay the same across UI, docs, and
  support guidance.

## Settings Wording Decision

Settings wording should use a short source-aware explanation rather than
credential details.

Decisions:

- Constant source priority should be explicit.
- The Settings-saved key should be described as a lower-priority fallback in the
  current MVP.
- Value-hidden posture should be explicit: saved values are hidden and never
  redisplayed.
- Missing-state guidance should mention constant source first and Settings
  fallback second.
- The fallback clear checkbox should be described only when the relevant clear
  control is visible.
- Clear wording should state that clearing affects only the Settings fallback
  and does not change or delete the constant source.
- Settings save wording should not imply that the plugin can edit, display, or
  delete the constant source.

Rejected directions:

- Do not present Settings storage as the preferred public-release route.
- Do not expose actual saved state beyond safe source/readiness categories.
- Do not display, request, or describe credential values, constant values, or
  option values.

## Report Builder Wording Decision

Report Builder wording should be readiness/action guidance, not credential
inspection guidance.

Decisions:

- For `missing`, avoid Settings-only guidance.
- For `missing`, guide the administrator to configure the preferred constant
  source or the current MVP Settings fallback.
- Use safe readiness/action guidance as the default display style.
- Avoid direct credential-source internals unless a safe source category is
  already used by the UI.
- For `constant_configured`, wording may say that OpenAI readiness is satisfied
  by the configured constant source category.
- For `settings_saved`, wording may say that OpenAI readiness is satisfied by
  the Settings fallback category, while preserving constant source as the
  preferred public-release target.
- Generate-before-readiness wording and Generate-after-error wording should stay
  separate.

Rejected directions:

- Do not imply that Settings is the only valid OpenAI API key source.
- Do not expose key presence, key value, key fragments, option values, or
  configuration internals beyond safe category/readiness labels.
- Do not turn Report Builder guidance into a support/debug evidence collection
  request.

## OpenAI Error Wording Decision

Step 232 recorded the residual:

```text
OpenAI error wording source-awareness: Needs follow-up wording alignment
```

Step 237 fixes the future wording policy for that residual, but it does not
execute or verify runtime OpenAI error paths.

Decisions:

- Prefer safe readiness/action guidance over direct disclosure of credential
  source internals.
- Do not reproduce raw provider-side error text.
- Do not imply that Settings is the only valid source.
- Do not ask for or display API keys, tokens, Authorization headers, request
  bodies, raw responses, AI payload JSON, or generated report bodies.
- For missing readiness, guide configuration of the preferred constant source
  or the current MVP Settings fallback.
- For non-missing OpenAI request failures, use generic safe retry and
  source-aware configuration review guidance.
- Missing readiness and non-missing OpenAI request failures should not share the
  exact same wording, because the administrator action and evidence boundary are
  different.

Rejected directions:

- Do not show raw provider error text as user-facing recovery guidance.
- Do not make Settings-only recovery guidance the default.
- Do not request screenshots, Network evidence, request bodies, or raw response
  content for support/debug.

## Support/debug Evidence Decision

Allowed evidence for UI, support, debug docs, and future verification:

- source category,
- readiness category,
- value-hidden status,
- scope labels,
- action outcome category,
- visible status/category-level wording,
- Pass / Fail / Blocked / Not tested classification.

Prohibited evidence:

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
- screenshots,
- browser Network evidence,
- cookies, sessions, or nonces,
- database dumps.

Decision:

- User-facing wording and support/debug wording must use the same evidence
  boundary.
- Support guidance should ask for status/category-level labels and action
  outcomes only.
- Support guidance should not ask for raw credential, payload, request, response,
  screenshot, or Network evidence.

## Public-release / Current MVP Explanation Decision

Public-release posture:

- constant-based OpenAI API key configuration is the preferred public-release
  target,
- Settings storage remains available in the current MVP as a fallback,
- retaining the Settings fallback in the current MVP is not a final public
  release approval of Settings-based key storage,
- WordPress.org release readiness remains `Hold`.

Step 237 does not approve public release. It only fixes wording decisions for a
future implementation plan.

## Decision Matrix

| Wording surface | Decision | Rationale | Safe evidence level | Prohibited disclosure | Future implementation implication |
|---|---|---|---|---|---|
| Settings constant-first priority wording | Explicitly state that the constant source is preferred. | Aligns admin wording with the implemented constant-first source model and public-release target. | Source model category and priority label. | Constant value, key value, option value. | Add or adjust translatable Settings help text in a later implementation step. |
| Settings fallback wording | Describe Settings-saved key as current MVP fallback and lower priority than constant source. | Avoids presenting Settings storage as the preferred public-release route. | Fallback category and value-hidden status. | Saved key value, option value, key fragment. | Keep fallback wording narrow and scoped to Settings. |
| Settings missing-state guidance | Mention constant source first, then Settings fallback. | Preserves public-release target while acknowledging current MVP fallback. | `missing` category and action guidance. | Actual saved state, option contents, constant contents. | Replace Settings-only missing guidance with source-aware guidance. |
| Settings fallback clear wording | Show clear explanation only when fallback-clear control is visible; say it affects Settings fallback only. | Prevents confusion with constant source and avoids overexplaining absent controls. | Clear control visibility and fallback scope category. | Saved key value, constant value, option value. | Ensure clear checkbox label/help text is source-scoped. |
| Report Builder readiness wording | Use readiness/action guidance rather than credential details. | Report Builder should help decide whether Generate can proceed, not expose configuration internals. | Readiness category and visible action guidance. | Credential values, headers, request/response. | Align visible readiness text with source-aware terminology. |
| Report Builder missing-state guidance | Avoid Settings-only guidance; mention preferred constant source and current MVP Settings fallback. | Addresses the Step 234 observation without reclassifying that smoke result as failed. | `missing` category and action guidance. | Settings value, constant value, option value. | Replace Settings-only guidance in a later implementation step. |
| OpenAI error wording for missing readiness | Use source-aware missing-readiness recovery guidance. | Missing source category requires configuration action before retry. | Missing readiness category and safe action guidance. | Key values, raw provider error, request/response. | Implement wording separately from non-missing request failures. |
| OpenAI error wording for non-missing request failure | Use generic safe retry / configuration review guidance without raw provider text. | Non-missing request failures may have causes unrelated to missing key readiness. | Error category and safe action guidance. | Raw provider error, Authorization header, request/response body. | Keep error wording safe and source-aware without exposing sensitive internals. |
| Support/debug wording | Share only status/category labels, warnings, and action outcomes. | Keeps support evidence aligned with privacy and credential-safety posture. | Source/readiness category, action outcome, value-hidden status. | Credentials, payloads, raw responses, screenshots, Network evidence. | Align future docs/help/support text with the same evidence boundary. |
| Future readme alignment | Explain constant-preferred public-release target and current MVP fallback without credential details. | Avoids mismatch between admin UI wording and public documentation. | Source model summary and storage posture category. | Credential values, option values, request/response content. | Handle readme changes only in a dedicated future step. |

## Step 232 Residual

Step 232 residual:

```text
OpenAI error wording source-awareness: Needs follow-up wording alignment
```

Step 237 classification:

- wording decision fixed,
- runtime error path not executed,
- runtime error path not verified,
- production wording not changed,
- implementation remains required in a later step,
- source-level and/or controlled verification remains required after
  implementation.

The residual is therefore still open as an implementation and verification
follow-up, even though the wording direction is now decided.

## Step 234 Observation

Step 234 observation:

```text
Report Builder Settings-only OpenAI key guidance visible
```

Step 237 classification:

- source-aware guidance decision input,
- not a failure of the Step 234 scope-bound Pass,
- not a defect finding in Step 237,
- not a production change in Step 237,
- should be addressed through the future implementation plan and implementation
  sequence.

This observation is separate from the Step 232 OpenAI error wording residual.

## Human Smoke Coverage Boundary

Step 237 does not run controlled human smoke.

Coverage remains:

| Source category | Human smoke status | Step 237 treatment |
|---|---|---|
| `constant_configured` | Not tested | Do not overclaim; require a separate controlled human smoke plan if future coverage is needed. |
| `settings_saved` | Not tested | Do not overclaim; require a separate controlled human smoke plan if future coverage is needed. |
| `missing` | Scope-bound Pass in Step 234 | Use as the only human-observed category-level path so far. |

`constant_configured` and `settings_saved` controlled human smoke should not be
executed as part of Step 237.

## Future Implementation Boundary

A future implementation should remain narrow:

- production wording changes only,
- translatable WordPress admin strings,
- safe escaping,
- no credential resolver behavior changes,
- no OpenAI request changes,
- no GA4 behavior changes,
- no OAuth changes,
- no `readme.txt` change unless explicitly scoped,
- no screenshots or Network evidence,
- no credential, option, request, response, payload, or generated report body
  output.

Future implementation should be followed by source-level verification. Human
smoke for `constant_configured` or `settings_saved` should be separately planned
before execution if it becomes necessary.

## Recommended Next Step

Recommended next step:

```text
Step 238: OpenAI API key source-aware wording narrow production implementation plan
```

Step 238 should remain docs-only / planning-only. It should identify:

- exact Settings wording targets,
- exact Report Builder wording targets,
- exact OpenAI error wording targets,
- strings that should not change,
- translation and escaping expectations,
- source-level verification scope,
- safe evidence boundary,
- whether any later human smoke plan is needed.

Step 238 should not run `constant_configured` or `settings_saved` controlled
human smoke. If those paths need human browser coverage later, they should first
receive a dedicated controlled human smoke plan.

## Result Classification

```text
Step 237 result: Decision checkpoint completed
Docs-only / decision-only: Yes
Settings wording decision: Fixed
Report Builder wording decision: Fixed
OpenAI error wording decision: Fixed
Support/debug evidence decision: Fixed
Constant source and source category terminology: Separated
Step 232 residual: Preserved for future implementation and verification
Step 234 observation: Preserved as source-aware wording input
Production implementation: Not performed
WordPress.org release readiness: Hold
```
