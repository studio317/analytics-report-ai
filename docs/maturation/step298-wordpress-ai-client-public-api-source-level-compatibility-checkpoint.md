# Step 298: WordPress AI Client Public API Source-Level Compatibility Checkpoint

## 1. Step Conclusion

Result classification: `Implementation-ready`.

The WordPress 7.0 public AI Client contract is sufficiently confirmed for the next narrow production implementation, provided the implementation stays provider-neutral and uses only the public prompt-builder API confirmed in local WordPress 7.0 source.

The next implementation should replace the plugin-owned direct OpenAI HTTP flow with WordPress AI Client text generation, retire plugin-owned OpenAI key storage and readiness UI, and avoid provider-specific assumptions.

WordPress.org release readiness remains `Hold`.

## 2. Baseline Classification

Baseline classification: Step 296 public identity alignment and Step 297 WordPress AI Client and translation-loading migration plan are treated as the committed baseline for this checkpoint.

Current public identity baseline:

| Item | Classification |
| --- | --- |
| Display name | `Studio317 Report Drafts for Google Analytics` |
| Slug / Text Domain | `studio317-report-drafts-google-analytics` |
| Public author wording | `Kimiya Watabe / Studio317` |
| WordPress.org username | `cuerda` |

Current planned migration baseline:

| Item | Planned posture |
| --- | --- |
| Minimum WordPress version | `Requires at least: 7.0` |
| Direct OpenAI HTTP integration | Retire |
| WordPress AI Client | Adopt |
| Plugin-owned OpenAI API key UI / storage / resolver | Retire |
| Explicit `load_plugin_textdomain()` call | Retire |
| GA4 Fetch -> Data Preview -> Generate AI Report flow | Retain |

## 3. Authoritative Evidence Classification

Evidence classification: `Source-level public API review completed`.

The checkpoint used local WordPress 7.0 source as the authoritative evidence boundary. It did not use trunk-only APIs, experimental provider details, private provider implementation behavior, browser admin smoke, AI Connector setup, AI generation execution, or external provider communication.

Only file-level, symbol-level, and category-level evidence is recorded here. Credential values, option values, provider account details, request bodies, raw responses, generated content, screenshots, and browser Network evidence were not inspected or recorded.

## 4. Local WordPress Core Version / Source Availability Classification

| Environment | Classification |
| --- | --- |
| `wp-dev` WordPress version | `7.0` |
| `wp-dev` AI Client source availability | `Pass` |
| `wp-dev-check` WordPress version | `7.0` |
| `wp-dev-check` AI Client source availability | `Pass` |

Source-level evidence:

| Source file | Public API evidence |
| --- | --- |
| `/var/www/html/wp-dev/wp-includes/ai-client.php` | Defines `wp_supports_ai()` and `wp_ai_client_prompt()` with `@since 7.0.0`. |
| `/var/www/html/wp-dev/wp-includes/ai-client/class-wp-ai-client-prompt-builder.php` | Public wrapper method annotations include `using_system_instruction()`, `using_max_tokens()`, `is_supported_for_text_generation()`, `generate_text()`, `generate_text_result()`, and `using_model_preference()`. |
| `/var/www/html/wp-dev/wp-includes/php-ai-client/src/Builders/PromptBuilder.php` | SDK methods behind the wrapper include text support checks, text generation, system instruction, max tokens, and model preference. |
| `/var/www/html/wp-dev/wp-includes/php-ai-client/src/Results/DTO/GenerativeAiResult.php` | Result object includes candidates, token usage, provider metadata, model metadata, and additional data. |

## 5. Confirmed WordPress 7.0 Public API Call Shape

Confirmed provider-neutral generation call shape:

```php
$generated_text = wp_ai_client_prompt( $user_prompt )
	->using_system_instruction( $system_instruction )
	->using_max_tokens( $max_tokens )
	->generate_text();
```

Classification: `Implementation-ready`.

Implementation notes:

| Decision point | Classification |
| --- | --- |
| User prompt entry | Use `wp_ai_client_prompt( $user_prompt )`. |
| System instruction | Use `using_system_instruction( $system_instruction )`. |
| Output bound | Use `using_max_tokens( $max_tokens )`. |
| Generation | Use `generate_text()`. |
| Error handling | Use `is_wp_error( $generated_text )`. |
| Provider selection | Do not specify a provider in the plugin. |
| OpenAI endpoint / Authorization header | Do not construct in plugin runtime. |

## 6. Confirmed Support-Preflight Call Shape

Confirmed provider-neutral support preflight call shape:

```php
$is_supported = wp_ai_client_prompt( $user_prompt )
	->using_system_instruction( $system_instruction )
	->using_max_tokens( $max_tokens )
	->is_supported_for_text_generation();
```

Classification: `Implementation-ready`.

The support check should be configured with the same prompt, system instruction, and max-token boundary as the generation call so the readiness decision matches the actual generation path.

If the support check returns `false`, the plugin should show a provider-neutral local category such as `ai_text_generation_unavailable`. The initial production implementation should not attempt to distinguish missing connector setup, unavailable runtime, unsupported text capability, prompt prevention, provider authentication, quota, or model-level causes unless the WordPress 7.0 public API exposes a safe cause boundary that does not require provider-specific inspection.

## 7. `generate_text()` Return / `WP_Error` / Empty-Text Handling Plan

Source-level classification:

| API | Public wrapper return boundary |
| --- | --- |
| `generate_text()` | `string|WP_Error` |
| `generate_text_result()` | `GenerativeAiResult|WP_Error` |
| `is_supported_for_text_generation()` | `bool` |

Handling plan:

| Condition | Plugin category |
| --- | --- |
| `is_supported_for_text_generation()` returns `false` | `ai_text_generation_unavailable` |
| `generate_text()` returns `WP_Error` | `ai_generation_failed` |
| `generate_text()` returns a non-string or empty trimmed string | `ai_generation_empty_text` |
| `generate_text()` returns non-empty string | Report draft text available for administrator review/edit/copy |

The next implementation should not record raw `WP_Error` data, provider responses, request bodies, generated content, provider account details, or connector credentials in support/debug evidence.

## 8. `generate_text_result()` Non-Adoption Rationale

Decision: Do not adopt `generate_text_result()` in the initial production migration.

Rationale:

| Reason | Classification |
| --- | --- |
| The plugin only needs the generated report draft text. | `Text-only need` |
| `GenerativeAiResult` contains provider metadata, model metadata, token usage, candidates, and additional data. | `Metadata exposure boundary` |
| Provider/model/token metadata is not needed for current admin UI, persistence, or support/debug evidence. | `Do not expose` |
| Avoiding the result object reduces the risk of accidentally displaying, logging, or support-pasting provider metadata. | `Safer initial implementation` |

The plugin should use `generate_text()` and keep support evidence at status/category level.

## 9. Model Preference Decision

Source-level classification: `using_model_preference()` is available through the WordPress AI Client prompt-builder public wrapper.

Initial implementation decision: Do not use model preference in the first production migration.

Rationale:

| Item | Decision |
| --- | --- |
| Provider neutrality | Preserve |
| Fixed OpenAI model requirement | Do not carry forward |
| `ANALYTICS_REPORT_AI_OPENAI_MODEL` | Retire with direct OpenAI integration |
| OpenAI model identifier | Do not introduce into AI Client implementation |
| Provider-specific configuration UI | Do not add |

If a future release needs model preference, it should be designed as a separate provider-neutral compatibility step after the initial migration is stable.

## 10. Provider Metadata Non-Exposure Boundary

Decision: Provider metadata, model metadata, token usage, candidates metadata, and additional provider data are not used, stored, displayed, logged, or requested for support/debug evidence in the initial implementation.

Boundary:

| Evidence type | Initial implementation posture |
| --- | --- |
| Generated report draft text | Display only for administrator review/edit/copy; do not intentionally persist. |
| Provider metadata | Do not use. |
| Model metadata | Do not use. |
| Token usage | Do not use. |
| Provider account details | Do not inspect or record. |
| Raw request / response | Do not inspect or record. |
| Connector credential values | Do not inspect or record. |

## 11. Safe Readiness / Runtime Error Category Decision

Initial implementation categories:

| Category | When to use |
| --- | --- |
| `ai_text_generation_unavailable` | Text generation preflight returns `false`. |
| `ai_generation_failed` | `generate_text()` returns `WP_Error`. |
| `ai_generation_empty_text` | Generation returns no usable text after local validation. |

Categories not adopted in the first implementation:

| Category candidate | Reason to defer |
| --- | --- |
| `ai_connector_not_configured` | Cause classification is not confirmed as a safe public API boundary. |
| `ai_provider_missing` | Could imply provider internals. |
| `ai_provider_authentication_error` | Could require provider-specific inspection or error disclosure. |
| `ai_provider_quota_error` | Could require provider-specific inspection or error disclosure. |
| Provider-specific HTTP category | Direct provider HTTP is retired from plugin runtime. |
| Provider name / model name exposure | Avoid provider assumptions and metadata exposure. |

Runtime wording should direct administrators to configure AI generation through WordPress / Connectors without naming or guessing a specific provider.

## 12. OpenAI Direct Integration Retirement Mapping

Retire in the next implementation:

| Obsolete target | Current source-level location / symbol |
| --- | --- |
| OpenAI Responses API endpoint | `includes/class-openai-client.php` direct endpoint |
| Direct OpenAI `wp_remote_post()` request | `Analytics_Report_AI_OpenAI_Client::generate_report()` |
| OpenAI Authorization header construction | `includes/class-openai-client.php` |
| OpenAI request body construction | `includes/class-openai-client.php` |
| OpenAI raw response parsing | `includes/class-openai-client.php` |
| OpenAI API key resolver | `analytics_report_ai_resolve_openai_api_key()` |
| OpenAI API key constant fallback | `ANALYTICS_REPORT_AI_OPENAI_API_KEY` helper boundary |
| Saved OpenAI API key Settings UI | `includes/class-settings.php` |
| Clear OpenAI API key checkbox | `includes/class-settings.php` |
| OpenAI key source / lifecycle helper | `analytics_report_ai_get_openai_api_key_source()` and related lifecycle categories |
| OpenAI-specific HTTP/auth/quota wording | `includes/class-openai-client.php`, `includes/class-settings.php`, `includes/class-report-builder.php`, `readme.txt` |
| Fixed OpenAI model constant | `ANALYTICS_REPORT_AI_OPENAI_MODEL` |
| OpenAI-specific public external-service wording | `readme.txt` and related public docs |

Do not retire in this migration:

| Preserved target | Posture |
| --- | --- |
| GA4 Fetch | Preserve |
| Google OAuth / GA4 token flow | Preserve |
| GA4 property / host / path filters | Preserve |
| Payload validation and transient flow | Preserve |
| Data Preview / Payload Preview summary | Preserve |
| Generate AI Report explicit action | Preserve |
| Generated draft review/edit/copy workflow | Preserve |
| Uninstall cleanup of plugin-owned options | Preserve |

## 13. Legacy OpenAI Key Cleanup Recommendation

Recommended cleanup option: `B`.

Recommendation:

During the public-release migration implementation, deterministically unset only the `openai_api_key` entry from the main settings option during first post-update / first post-migration execution, without displaying, comparing, logging, or recording the value.

Required guardrails:

| Guardrail | Required posture |
| --- | --- |
| OpenAI key value | Never display, log, compare, or record. |
| Main settings option | Do not delete the whole option. |
| Google OAuth / GA4 / host / report settings | Preserve. |
| Cleanup target | Only the legacy `openai_api_key` entry. |
| Re-execution | Idempotent and safe. |
| User-visible behavior | No saved OpenAI key UI remains after migration. |

Why not only option A:

Waiting until the next Settings save can leave a provider-specific credential stored longer than necessary.

Why not option C as the primary path:

Separating cleanup to a later step may delay public-release credential retirement. A narrow implementation can still separate source-level verification and human smoke into follow-up steps without postponing the cleanup decision.

## 14. `load_plugin_textdomain()` Retirement Confirmation

Classification: `Implementation-ready after minimum WordPress version is raised to 7.0`.

Current removal candidates:

| Candidate | Location |
| --- | --- |
| `add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );` | `includes/class-plugin.php` |
| `Analytics_Report_AI_Plugin::load_textdomain()` | `includes/class-plugin.php` |
| `load_plugin_textdomain()` call | `includes/class-plugin.php` |

Retention plan:

| Item | Posture |
| --- | --- |
| Text Domain | Keep `studio317-report-drafts-google-analytics`. |
| Domain Path | Keep `/languages`. |
| Bundled POT / PO / MO | Keep and regenerate after wording changes. |
| Old WordPress compatibility translation loader | Do not add. |
| Minimum WordPress version | Raise `Requires at least` to `7.0` in implementation. |

## 15. Implementation-Ready Changed-File Inventory

Candidate files for the next implementation:

| File | Expected implementation role |
| --- | --- |
| `studio317-report-drafts-google-analytics.php` | Raise `Requires at least` to `7.0`; retire fixed OpenAI model constant if unused. |
| `includes/class-plugin.php` | Remove explicit translation-loading hook and method; update dependency loading if OpenAI client class is renamed or removed. |
| `includes/class-openai-client.php` | Replace direct OpenAI client behavior with a provider-neutral WordPress AI Client wrapper, or replace with a renamed AI client class if appropriate. |
| `includes/functions-utils.php` | Remove plugin-owned OpenAI key default, resolver, constant fallback, and lifecycle helpers; add narrow legacy key cleanup helper if selected. |
| `includes/class-settings.php` | Remove OpenAI API key input, clear checkbox, source labels, and help text; replace with WordPress AI Client / Connectors readiness wording if needed. |
| `includes/class-report-builder.php` | Replace OpenAI key readiness with AI text-generation readiness; call AI Client wrapper; keep GA4/Data Preview/Generate flow. |
| `readme.txt` | Rewrite external-service and privacy wording from direct OpenAI API to administrator-configured WordPress AI provider / Connectors boundary. |
| `languages/analytics-report-ai.pot` and localized files | Regenerate after source string changes. |
| `tools/build-release-zip-dry-run.sh` | Revisit credential scan warning terms only if direct OpenAI key terms become historical false positives. |
| `uninstall.php` | No expected runtime change; main settings option cleanup already covers legacy key if present at uninstall. |

## 16. Unresolved Items, If Any

No unresolved public API contract blocker remains for the next provider-neutral implementation.

Remaining implementation-design items:

| Item | Classification |
| --- | --- |
| Exact class naming for the AI Client wrapper | Implementation decision |
| Exact one-time legacy key cleanup timing and marker | Implementation decision |
| Whether to expose a Settings-side AI readiness label | Implementation decision |
| Human validation of a configured WordPress AI provider / Connectors environment | Future controlled validation |
| Provider-specific cause classification | Deferred / do not implement initially |
| Model preference configuration | Deferred |

## 17. Prohibited Operations Confirmation

Confirmed not performed in this checkpoint:

| Operation | Status |
| --- | --- |
| Production code change | Not performed |
| `readme.txt` change | Not performed |
| Existing public docs change | Not performed |
| Build script change | Not performed |
| Translation file change | Not performed |
| Version change | Not performed |
| AI Connector setup | Not performed |
| API key input / confirmation / option value inspection | Not performed |
| OpenAI / Google / GA4 / OAuth / AI provider external communication | Not performed |
| Browser admin smoke | Not performed |
| AI generation execution | Not performed |
| Plugin Check | Not performed |
| Package install | Not performed |
| Commit / push | Not performed |
| WordPress.org upload / slug reservation | Not performed |

## 18. Next Recommended Production Implementation Step

Recommended next step:

`Step 299: WordPress AI Client migration narrow production implementation`

Recommended implementation scope:

1. Raise minimum WordPress version to `7.0`.
2. Replace direct OpenAI request execution with provider-neutral `wp_ai_client_prompt()` generation.
3. Use `is_supported_for_text_generation()` preflight with the same prompt configuration as generation.
4. Use `generate_text()` and `is_wp_error()` handling.
5. Add local empty-text validation.
6. Retire plugin-owned OpenAI key UI, resolver, constant fallback, source labels, clear checkbox, direct endpoint/header/body/response parser, and fixed OpenAI model constant.
7. Deterministically remove only legacy `openai_api_key` from the main settings option without exposing values.
8. Retire explicit `load_plugin_textdomain()` hook and method.
9. Update readme, public wording, and translation files in the same or follow-up bounded implementation steps if the production scope is split.

Result classification: `Implementation-ready`.
