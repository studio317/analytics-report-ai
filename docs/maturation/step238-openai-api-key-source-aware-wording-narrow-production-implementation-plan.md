# Step 238: OpenAI API Key Source-aware Wording Narrow Production Implementation Plan

## Step Purpose

Step 238 is a docs-only / planning-only implementation plan for future OpenAI
API key source-aware admin wording.

It converts the Step 237 wording decisions into a narrow candidate list for a
future production wording implementation. It does not change production wording,
UI rendering, credential resolution, OpenAI request behavior, GA4 behavior, or
release readiness.

WordPress.org release readiness remains `Hold`.

## Scope

This plan covers static admin wording candidates for:

- Settings,
- Report Builder,
- OpenAI error wording.

The current MVP source model remains:

```text
Constant first / Settings fallback / Missing
```

Fixed terminology:

| Term | Meaning |
|---|---|
| Constant source | The implemented constant-based configuration source represented by `ANALYTICS_REPORT_AI_OPENAI_API_KEY`. |
| Settings fallback source | The current MVP fallback source saved through the plugin Settings UI. |
| Safe source category | `constant_configured`, `settings_saved`, or `missing`. |

Important boundary:

- `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is the constant source name, not a source
  category.
- A safe source category does not prove credential value validity, provider-side
  acceptance, OpenAI request success, or future request success.
- The current MVP Settings fallback remains available, but this does not approve
  Settings-based OpenAI API key storage as the final public-release posture.

## Explicit Non-goals

Step 238 does not:

- implement production wording,
- change production PHP,
- change Settings UI behavior,
- change Report Builder UI behavior,
- change the credential resolver,
- change OpenAI client request behavior,
- change GA4 client behavior,
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
- `docs/maturation/step231-openai-api-key-constant-based-configuration-narrow-production-implementation-results.md`
- `docs/maturation/step232-openai-api-key-constant-based-configuration-source-level-verification-results.md`
- `docs/maturation/step234-openai-api-key-source-controlled-human-admin-smoke-results.md`
- `docs/maturation/step235-openai-api-key-source-maturation-checkpoint.md`
- `docs/maturation/step236-openai-api-key-source-aware-wording-alignment-plan.md`
- `docs/maturation/step237-openai-api-key-source-aware-wording-decision-checkpoint.md`

## Implementation Candidate Inventory

Candidate entries are file-level / symbol-level / rendering-area references
only. They do not include credential values, option values, API keys, raw
provider errors, request bodies, raw responses, AI payload JSON, or generated
report bodies.

| Surface | Candidate file / class / method or rendering area | Current wording role | Planned wording change objective | Runtime behavior change required | Sensitive disclosure risk | Implementation inclusion |
|---|---|---|---|---|---|---|
| Settings | `includes/class-settings.php` / `Analytics_Report_AI_Settings::render_settings_page()` / external service and credential storage guidance | Explains OpenAI requests, source usage, database/fallback posture, and support safety. | Keep source-aware guidance aligned with constant-first priority and current MVP fallback without adding credential details. | No | Low if limited to category-level wording. | Include only if wording drift remains after more specific Settings field text. |
| Settings | `includes/class-settings.php` / `Analytics_Report_AI_Settings::render_settings_page()` / OpenAI API key fallback field area | Shows source category, fallback status, value-hidden status, fallback input, and per-state guidance. | Clarify preferred constant source, current MVP Settings fallback, value-hidden posture, and missing-state guidance. | No | Medium if wording accidentally implies actual value inspection. | Include. |
| Settings | `includes/class-settings.php` / `Analytics_Report_AI_Settings::render_settings_page()` / `clear_openai_api_key` checkbox area | Explains fallback deletion when saved Settings fallback exists. | Confirm wording is visible only when relevant and states Settings fallback-only scope; keep constant source unaffected. | No | Low if wording remains scoped to fallback and category/status. | Include only when clear control is present. |
| Settings | `includes/class-settings.php` / `Analytics_Report_AI_Settings::sanitize_settings()` / Settings save notices | Saves or clears Settings fallback values without redisplay. | Do not change save/clear behavior; only consider safe notice wording if future implementation explicitly targets notice text. | No | Medium if notices imply constant mutation or expose values. | Hold unless a specific notice wording gap is identified. |
| Report Builder | `includes/class-report-builder.php` / `Analytics_Report_AI_Report_Builder::render()` / Current Settings table | Shows OpenAI API key source category, fallback status, and value-hidden status. | Keep labels source-aware and avoid implying source category proves request success. | No | Low if limited to category labels. | Include if wording needs clarity. |
| Report Builder | `includes/class-report-builder.php` / `Analytics_Report_AI_Report_Builder::render()` / missing OpenAI readiness guidance near current settings | Provides admin action guidance before Generate. | Replace any Settings-only guidance with source-aware guidance: preferred constant source first, current MVP Settings fallback second. | No | Medium if wording implies actual key value or provider acceptance. | Include. |
| Report Builder | `includes/class-report-builder.php` / `Analytics_Report_AI_Report_Builder::render_payload_preview()` / Generate AI Report guidance | Explains Generate sends reviewed data to OpenAI and may consume API usage. | Do not change request flow; only ensure readiness wording does not conflict with source-aware key guidance. | No | Low if not expanded into credential details. | Exclude unless direct wording conflict is found. |
| Report Builder | `includes/class-report-builder.php` / generated report rendering area | Explains generated draft handling and non-persistence. | No OpenAI key source wording needed here. | No | Low. | Exclude. |
| OpenAI error wording | `includes/class-openai-client.php` / `Analytics_Report_AI_OpenAI_Client::generate_report()` / missing key error branch | Handles missing readiness before an OpenAI request can proceed. | Use source-aware missing-readiness guidance: preferred constant source or current MVP Settings fallback. | No | Medium if wording asks for key values. | Include. |
| OpenAI error wording | `includes/class-openai-client.php` / `Analytics_Report_AI_OpenAI_Client::get_safe_error_message()` / API authentication and request failure categories | Provides safe user-facing OpenAI API error messages after request failures. | Avoid Settings-only recovery guidance; keep non-missing failures generic and safe. | No | Medium if raw provider text or key details leak into wording. | Include. |
| OpenAI error wording | `includes/class-openai-client.php` / HTTP request construction and response parsing | Builds request and parses response. | No wording-only implementation should change request construction, headers, body, status parsing, or response parsing. | No | High if modified incorrectly. | Explicitly exclude. |
| Utility/source model | `includes/functions-utils.php` / OpenAI API key source helper functions | Defines source categories and resolves request-local key material. | No changes; use only as source-level verification reference. | No | High if resolver behavior is changed. | Explicitly exclude. |

## Settings Implementation Target Policy

Future narrow wording implementation may update static Settings wording for:

- constant source as the preferred route,
- Settings-saved key as current MVP fallback,
- value-hidden / non-redisplay posture,
- `missing` category guidance,
- fallback clear checkbox scope when the clear checkbox is visible,
- Settings save / clear not changing or deleting the constant source.

Implementation boundaries:

- Do not add clear-control explanations when the clear control is absent.
- Do not expand existing source category labels into credential detail display.
- Do not change resolver logic.
- Do not change option handling.
- Do not change save logic.
- Do not change clear logic.
- Do not display actual saved state beyond safe category/status labels.

Planned wording style:

- constant source first,
- current MVP Settings fallback second,
- value-hidden always explicit,
- fallback-only scope explicit when relevant,
- no API key values, key fragments, constant values, or option values.

## Report Builder Implementation Target Policy

### A. Generate-before Readiness / Missing Guidance

Future wording implementation may replace Settings-only guidance with
source-aware readiness/action guidance.

Planned direction:

- mention preferred constant source first,
- mention current MVP Settings fallback second,
- keep guidance focused on readiness before Generate,
- avoid actual key presence, saved state, or option internals,
- avoid suggesting that source category proves OpenAI request success,
- do not change Generate action behavior,
- do not change request execution logic.

This candidate addresses the Step 234 observation:

```text
Report Builder Settings-only OpenAI key guidance visible
```

That observation is an input to wording alignment, not a failure of the Step 234
scope-bound Pass.

### B. `constant_configured` / `settings_saved` Readiness Wording

Future wording implementation may align category label or readiness guidance
where existing UI safely displays the source category.

Planning boundaries:

- `constant_configured` may indicate that the constant source category satisfies
  OpenAI key readiness for UI purposes.
- `settings_saved` may indicate that the Settings fallback source category
  satisfies current MVP OpenAI key readiness for UI purposes.
- Neither category guarantees provider-side validity, permissions, quota,
  endpoint availability, model availability, or request success.
- Human browser coverage for `constant_configured` and `settings_saved` remains
  untested and must not be overclaimed.

Step 238 does not run or plan direct execution of those human smoke paths. If
needed later, each path should receive a separate controlled human smoke plan
with status/category-only evidence boundaries.

## OpenAI Error Wording Implementation Target Policy

Step 232 residual:

```text
OpenAI error wording source-awareness: Needs follow-up wording alignment
```

The future implementation should treat missing readiness and non-missing request
failure as different wording contexts.

| Error-related context | Planned wording objective | Out of scope |
|---|---|---|
| Missing readiness before Generate | Safely guide the administrator to configure the preferred constant source or current MVP Settings fallback. | Request execution, provider communication, credential inspection. |
| Non-missing OpenAI request failure after Generate | Use generic retry / safe configuration review guidance without Settings-only recovery wording. | Raw provider errors, headers, request/response, payload, generated report body. |

Implementation boundaries:

- Do not reproduce raw provider response wording in user-facing messages.
- Do not imply Settings is the only recovery path.
- Do not use source category as provider-side error diagnosis.
- Do not change OpenAI client request behavior.
- Do not change HTTP handling.
- Do not change error classification logic unless a future step explicitly
  scopes that behavior change.
- Do not run or verify runtime failure paths in Step 238.

## Explicit Non-change Targets For Future Narrow Implementation

A future wording implementation must not change:

- credential resolver priority logic,
- constant / Settings fallback resolution behavior,
- option save / clear behavior,
- API key value-hidden behavior,
- OpenAI request payload construction,
- OpenAI request execution,
- GA4 Fetch behavior,
- OAuth behavior,
- generated report storage posture,
- raw provider response handling,
- `readme.txt`,
- CSS,
- JavaScript,
- uninstall cleanup.

These areas are outside the wording-only implementation boundary even if nearby
strings are edited.

## Translation, Escaping, And Safe Evidence Boundary

Future implementation principles:

- Use WordPress-translatable admin strings with the `analytics-report-ai` text
  domain.
- Follow existing project escaping conventions for the output context.
- Display only source category, readiness category, value-hidden status, scope,
  and safe action guidance.
- Keep user-facing wording and support/debug wording aligned to the same
  evidence boundary.
- Do not require screenshots or browser Network evidence for verification.

Do not output or request:

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
- cookies, sessions, or nonces,
- database dumps.

## Verification Plan

### A. Source-level Verification

After future implementation, source-level verification should confirm:

- targeted static wording exists in the expected rendering area or message
  branch,
- translation functions use the `analytics-report-ai` text domain,
- escaping matches the output context,
- source categories remain `constant_configured`, `settings_saved`, and
  `missing`,
- resolver priority remains constant first / Settings fallback / missing,
- OpenAI client request behavior has no intended behavior change,
- OpenAI request execution logic has no intended behavior change,
- GA4 behavior has no intended behavior change,
- credential values, option values, request bodies, raw responses, AI payload
  JSON, and generated report bodies are not output.

Suggested command categories for that future verification:

- source grep for targeted wording and source category names,
- `php -l` for touched PHP files,
- `find includes -name '*.php' -print0 | xargs -0 -n1 php -l`,
- `git diff --check`,
- `git diff --name-only`,
- `git status --short --untracked-files=all`.

### B. Controlled Human Admin Smoke

Step 238 does not run human admin smoke.

After implementation, controlled human admin smoke should be considered only if
source-level verification leaves a user-visible coverage gap.

If `constant_configured` or `settings_saved` paths are included, a separate
controlled plan must define:

- controlled condition setup,
- status/category-only observation template,
- no credential value recording,
- no option value inspection,
- no screenshots,
- no Network evidence,
- no OpenAI Generate unless a separate step explicitly scopes it,
- no GA4 Fetch,
- no OAuth or Google navigation.

The `missing` path had a Step 234 scope-bound Pass. After wording changes, it may
be a limited recheck candidate, but only under a separate plan.

## Step 232 Residual And Step 234 Observation

### Step 232 Residual

```text
OpenAI error wording source-awareness: Needs follow-up wording alignment
```

Step 238 treatment:

- implementation candidate identified,
- still not resolved,
- requires future production wording implementation,
- requires future source-level verification after implementation,
- runtime error path execution is not performed in this step.

### Step 234 Observation

```text
Report Builder Settings-only OpenAI key guidance visible
```

Step 238 treatment:

- source-aware readiness wording candidate identified,
- not a failure of the Step 234 scope-bound Pass,
- not resolved by this planning step,
- should be handled in the future narrow implementation.

These are related wording inputs, but they are not the same issue.

## Recommended Next Step

Recommended next step:

```text
Step 239: OpenAI API key source-aware wording narrow production implementation
```

Step 239 should be limited to:

- translatable static admin wording identified in this plan,
- no production behavior changes,
- no resolver changes,
- no request logic changes,
- no `readme.txt` changes,
- no external API calls,
- no OAuth,
- no browser smoke,
- no Plugin Check.

After Step 239, a separate source-level verification step should be proposed.

## Result Classification

```text
Step 238 result: Narrow production wording implementation plan completed
Docs-only / planning-only: Yes
Settings wording candidates: Planned
Report Builder wording candidates: Planned
OpenAI error wording candidates: Planned
Explicit non-change targets: Fixed
Source category versus request success boundary: Fixed
Translation / escaping / safe evidence boundary: Fixed
Post-implementation verification plan: Fixed
Step 232 residual: Preserved as implementation and verification follow-up
Step 234 observation: Preserved as Report Builder wording input
Production implementation: Not performed
WordPress.org release readiness: Hold
```
