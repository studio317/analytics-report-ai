# Step 297: WordPress AI Client and Translation Loading Migration Plan

## 1. Step Conclusion

Planning completed.

The recommended implementation direction is:

- Raise the target minimum WordPress version to `Requires at least: 7.0`.
- Replace the plugin-owned OpenAI direct HTTP integration with the WordPress AI Client public API, using `wp_ai_client_prompt()` as the planned text-generation entry point.
- Keep the existing explicit server-side `Generate AI Report` action, the `Fetch GA4 Data` -> `Data Preview` -> `Generate AI Report` two-step flow, payload validation, temporary transient boundary, generated-draft review/edit/copy behavior, and support/debug redaction posture.
- Remove plugin-owned OpenAI API key input, storage, resolver, clear UI, direct endpoint, Authorization header construction, and OpenAI-specific error wording.
- Treat AI provider setup as a WordPress Connectors / AI Client environment concern, not as plugin-owned provider configuration.
- Retire the plugin's explicit `load_plugin_textdomain()` call when the minimum WordPress version is raised to 7.0, while keeping the text domain and bundled translation files.

This is a docs-only / planning-only step. No production files, readme, build scripts, or translation files were changed.

## 2. Baseline Classification

Step 296 public identity alignment is treated as the committed baseline.

Current baseline categories:

- Public display name: `Studio317 Report Drafts for Google Analytics`
- Desired permanent slug / text domain: `studio317-report-drafts-google-analytics`
- Public author wording: `Kimiya Watabe / Studio317`
- WordPress.org directory account username: `cuerda`
- WordPress minimum version: `6.0`
- AI generation integration: direct OpenAI Responses API HTTP request
- OpenAI API key handling: plugin-owned Settings fallback plus server constant fallback
- Translation loading: explicit `load_plugin_textdomain()` on `plugins_loaded`
- Release readiness: not final until AI Client migration, translation-loading retirement, package validation, install validation, and final review evidence are re-established

## 3. Reviewer Finding Mapping

| Reviewer concern | Current baseline | Planned disposition |
|---|---|---|
| Plugin directly integrates with OpenAI instead of WordPress AI Client | `includes/class-openai-client.php` sends direct HTTP to OpenAI Responses API | Replace with WordPress AI Client text-generation call. |
| Plugin stores or reads provider-specific OpenAI API key | Settings value and `ANALYTICS_REPORT_AI_OPENAI_API_KEY` constant are supported | Remove OpenAI key UI, resolver, storage category, and direct secret use. |
| Provider-specific endpoint/header remains in plugin | OpenAI endpoint and Authorization header are constructed in plugin code | Remove endpoint/header construction from plugin runtime. |
| Provider setup should be handled by WordPress Connectors | Plugin owns OpenAI API key setup UI | Move setup guidance to Connector/AI Client readiness messaging only. |
| Translation loading should follow modern WordPress behavior | Plugin calls `load_plugin_textdomain()` explicitly | Raise minimum to WordPress 7.0 and remove explicit call. |

## 4. Current Direct OpenAI Integration Inventory

Current source-level inventory:

- `includes/class-openai-client.php`
  - `Analytics_Report_AI_OpenAI_Client::generate_report()`
  - Resolves plugin-owned OpenAI API key.
  - Builds OpenAI Responses request body with model, system prompt, user input, and output token limit.
  - Calls `wp_remote_post()` against the OpenAI Responses endpoint.
  - Builds provider-specific Authorization and JSON headers.
  - Parses OpenAI Responses API output.
  - Maps OpenAI-specific HTTP status and provider error categories to user-facing messages.
- `includes/functions-utils.php`
  - Default settings include `openai_api_key`.
  - Settings normalization keeps `openai_api_key`.
  - `analytics_report_ai_get_openai_api_key_constant_name()`
  - `analytics_report_ai_get_openai_api_key_source()`
  - `analytics_report_ai_resolve_openai_api_key()`
  - `analytics_report_ai_get_openai_api_key_lifecycle_categories()`
- `includes/class-settings.php`
  - Saves and clears `openai_api_key`.
  - Detects OpenAI constant source.
  - Renders OpenAI API key Settings input, hidden-value status, clear checkbox, and help dialog.
  - Mentions restricted OpenAI key / Responses API permissions.
- `includes/class-report-builder.php`
  - Shows OpenAI API key readiness in Report Builder.
  - Calls `Analytics_Report_AI_OpenAI_Client::generate_report()`.
  - Uses OpenAI-specific preview and disclosure wording.
- `readme.txt` and public docs
  - Describe OpenAI API, OpenAI endpoint, OpenAI API key, OpenAI terms/privacy, and OpenAI-specific contact timing.
- `uninstall.php`
  - Deletes the main settings option, which can contain saved OpenAI key data in the current baseline.
- `tools/build-release-zip-dry-run.sh`
  - Scans package stage for credential patterns and warning-only credential documentation keywords, including `openai_api_key`.

No credential values, option values, request bodies, or raw responses were inspected for this inventory.

## 5. Target WordPress 7.0 / AI Client Architecture

Target architecture:

- Minimum WordPress version becomes `7.0`.
- AI generation uses WordPress AI Client public API only.
- Planned generation entry point: `wp_ai_client_prompt()`.
- The plugin supplies the already-reviewed report data and prompt instructions to the AI Client, then receives generated text or `WP_Error`.
- Provider selection, provider credentials, endpoint configuration, account connection, and provider-specific transport belong to WordPress Connectors / AI Client configuration.
- The plugin does not keep provider-specific OpenAI API key storage, direct endpoint constants, direct Authorization headers, OpenAI request body construction, or OpenAI response body parsing.
- The plugin keeps its own GA4 payload validation and redaction boundary before invoking the AI Client.
- The plugin must treat missing Connectors, unavailable AI Client, unsupported text-generation capability, and generation failures as safe local `WP_Error` categories before any external transmission.

Implementation should confirm the exact WordPress 7.0 AI Client function signature and return shape at implementation time against the public API. This plan assumes the reviewer-directed `wp_ai_client_prompt()` API is available in WordPress 7.0.

## 6. Target User/Admin Flow

Preserved flow:

1. Administrator configures GA4 and Google connection settings.
2. Administrator opens Report Builder.
3. Administrator explicitly clicks `Fetch GA4 Data`.
4. Plugin fetches GA4 data only from Google Analytics Data API.
5. Administrator reviews structured Data Preview, warnings, generation readiness, and report language.
6. Administrator explicitly clicks `Generate AI Report`.
7. Plugin calls WordPress AI Client for text generation only if data is valid, reportable, and an AI text-generation provider is available.
8. Generated text is shown as an editable draft.
9. Plugin does not intentionally persist generated report text.

Changed flow:

- Settings no longer asks for an OpenAI API key.
- Report Builder no longer requires an OpenAI API key source category.
- AI provider readiness is described as a WordPress AI Client / Connectors readiness state.
- Connectors not configured or text generation unavailable results in local guidance and no AI generation request.

## 7. Settings / Storage / Cleanup Migration Plan

OpenAI settings disposition:

- Remove the `openai_api_key` field from default settings for new installs.
- Remove save/update behavior for `openai_api_key`.
- Remove clear checkbox and OpenAI key deletion Settings notice.
- Remove OpenAI key constant fallback support for runtime generation.
- Remove OpenAI key source/lifecycle helper methods or replace them with AI Client readiness category helpers.
- Do not remove or alter Google OAuth, GA4 property, host filter, or OAuth client settings.

Migration compatibility classification:

- Version `0.1.0` is still pre-public-release, so backwards compatibility for saved OpenAI API keys is not required as a public release contract.
- To minimize credential retention risk, the implementation should stop reading saved `openai_api_key` and should remove it from the settings array on the next Settings save.
- A separate source-level check should confirm whether an activation/update cleanup is needed before public release. If implemented, cleanup must be deterministic and limited to the OpenAI key entry, without deleting GA4 or Google OAuth settings.
- Existing `uninstall.php` remains aligned because it deletes the main settings option; if `openai_api_key` remains in an old database before uninstall, uninstall still removes it through the main option cleanup.

Storage target:

- Plugin-owned provider-specific OpenAI API key persistence: retired.
- Plugin-owned AI provider selection persistence: not introduced.
- WordPress AI Client / Connectors provider state: external to this plugin.

## 8. Runtime Error and Safe Wording Plan

Replace OpenAI-specific runtime categories with provider-neutral AI Client categories:

- `ai_client_unavailable`
- `ai_connector_not_configured`
- `ai_text_generation_unavailable`
- `ai_generation_failed`
- `ai_generation_empty_text`
- `ai_generation_invalid_response`

Wording direction:

- Do not infer or display the configured provider name unless WordPress AI Client returns an explicit safe display label intended for admin UI.
- Do not mention OpenAI authentication, OpenAI quota, OpenAI endpoint, Responses API permissions, or OpenAI API key setup in plugin-owned runtime errors.
- For missing provider readiness: "Configure a text generation provider in WordPress AI settings or Connectors before generating a report draft."
- For generation failure: "AI generation failed. Review the AI provider configuration and try again without sharing request, response, credential, or generated report details."
- Preserve support/debug guidance: share status labels, warning messages, and general error categories only.

## 9. Readme / Privacy / External-Services Rewrite Plan

Public documentation should be rewritten from provider-specific OpenAI wording to WordPress AI Client / configured provider wording.

Candidate changes:

- Replace `OpenAI API` section with `AI generation provider` or `WordPress AI Client`.
- Explain that when the administrator clicks `Generate AI Report`, reviewed GA4-derived report data and selected report language are sent through WordPress AI Client to the AI provider configured by the site administrator.
- Remove fixed OpenAI endpoint and OpenAI terms/privacy URLs from plugin-specific external-service documentation.
- Explain that provider terms/privacy depend on the administrator-configured Connector/provider.
- Remove OpenAI API key setup instructions from Installation, FAQ, and Data Storage.
- Keep "generated report text is a draft", "plugin does not intentionally save generated report text", and support/debug redaction posture.
- Keep Google OAuth / GA4 disclosures unchanged except for any references to OpenAI key exclusion.

Documentation must avoid naming a provider as contacted by the plugin unless that provider is fixed by plugin code. After migration, provider selection is controlled by WordPress AI Client / Connectors.

## 10. `load_plugin_textdomain()` Retirement Plan

Current state:

- Main plugin header has `Text Domain: studio317-report-drafts-google-analytics`.
- Main plugin header has `Domain Path: /languages`.
- Bundled files exist for POT, Japanese PO, and Japanese MO under the new text domain.
- `Analytics_Report_AI_Plugin::__construct()` hooks `load_textdomain()` to `plugins_loaded`.
- `Analytics_Report_AI_Plugin::load_textdomain()` calls `load_plugin_textdomain()`.

Target state for WordPress 7.0 minimum:

- Remove the `plugins_loaded` hook for manual textdomain loading.
- Remove the `load_textdomain()` method if it becomes unused.
- Keep `Text Domain` unchanged.
- Keep `Domain Path: /languages` unless final WordPress.org packaging guidance says otherwise.
- Keep bundled POT/PO/MO and regenerate them after source wording changes.
- Update `Requires at least` in plugin header and readme to `7.0`.
- Update public docs and release docs to mention WordPress 7.0 minimum.
- Do not add old-WordPress compatibility shims or conditional fallback loaders.

## 11. Changed-File Candidate Inventory

Likely implementation files:

- `studio317-report-drafts-google-analytics.php`
  - Raise `Requires at least` to `7.0`.
  - Remove `ANALYTICS_REPORT_AI_OPENAI_MODEL` if no longer used.
- `includes/class-plugin.php`
  - Remove `load_plugin_textdomain()` hook and method.
  - Consider replacing `includes/class-openai-client.php` dependency with an AI Client wrapper file if the class is renamed.
- `includes/class-openai-client.php`
  - Replace direct OpenAI client with provider-neutral AI generation wrapper or rename in a separate careful step.
- `includes/functions-utils.php`
  - Remove OpenAI API key defaults, resolvers, lifecycle helpers, and validation copy.
  - Add AI Client readiness/status helpers if needed.
- `includes/class-settings.php`
  - Remove OpenAI API key field, clear checkbox, help dialog, save logic, and source labels.
  - Add provider-neutral WordPress AI Client readiness guidance if useful.
- `includes/class-report-builder.php`
  - Replace OpenAI key readiness row with AI Client text-generation readiness.
  - Replace OpenAI-specific disclosure copy with configured AI provider copy.
  - Continue using the explicit `Generate AI Report` POST action.
- `includes/class-prompt-builder.php`
  - Reuse existing prompt construction unless AI Client requires different prompt packaging.
- `readme.txt`
  - Raise `Requires at least` to `7.0`.
  - Rewrite OpenAI-specific external-service, setup, FAQ, privacy, and changelog wording.
- `docs/DATA-HANDLING.md`, `docs/README.md`, `docs/DEVELOPMENT.md`, `docs/RELEASE.md`
  - Align data-handling and release guidance.
- `languages/studio317-report-drafts-google-analytics.pot`
- `languages/studio317-report-drafts-google-analytics-ja.po`
- `languages/studio317-report-drafts-google-analytics-ja.mo`
  - Regenerate after implementation wording changes.
- `tools/build-release-zip-dry-run.sh`
  - Revisit warning-only keyword scans after `openai_api_key` is removed from package source.
- `uninstall.php`
  - Likely no change required because the main settings option remains deleted on uninstall.

## 12. Implementation Sequence

Recommended implementation sequence:

1. Source-level implementation plan checkpoint for AI Client API call shape.
2. Raise WordPress minimum to 7.0 in plugin header and readme.
3. Introduce a provider-neutral AI generation wrapper around `wp_ai_client_prompt()`.
4. Keep prompt construction and payload validation intact.
5. Replace `Analytics_Report_AI_OpenAI_Client::generate_report()` call path with the AI Client wrapper.
6. Add safe readiness / unavailable / unsupported / failure `WP_Error` categories.
7. Remove direct OpenAI endpoint, Authorization header, request body construction, response-body parser, model constant usage, and OpenAI-specific error mapper.
8. Remove OpenAI API key Settings save logic, UI, clear logic, help dialog, source labels, and constant fallback support.
9. Add provider-neutral AI Client / Connectors guidance in Settings and Report Builder.
10. Rewrite readme and public docs to describe WordPress AI Client / administrator-configured AI provider.
11. Retire `load_plugin_textdomain()` hook and method.
12. Regenerate POT/PO/MO.
13. Run syntax/static checks and source-level grep.
14. Run release dry-run.
15. Prepare isolated package install validation and Plugin Check as later steps, not in the initial implementation commit.

## 13. Verification Plan

Minimum static verification after implementation:

- `php -l studio317-report-drafts-google-analytics.php`
- `find includes -name '*.php' -print0 | xargs -0 -n1 php -l`
- `node --check assets/js/admin.js`
- `node --check assets/js/settings-help.js`
- `wp i18n make-pot` for the current text domain
- `msgmerge`
- `msgattrib --no-obsolete`
- `msgfmt --check`
- `git diff --check`
- Source grep confirming no plugin-owned OpenAI endpoint, OpenAI Authorization header, OpenAI API key input, OpenAI API key resolver, or `load_plugin_textdomain()` remains.
- Source grep confirming Google OAuth / GA4 settings remain present.
- Source grep confirming `Requires at least: 7.0` in plugin header and readme.
- Release dry-run package build.

Later controlled validation:

- Admin source-level review for Settings and Report Builder wording.
- Human admin smoke for Connectors missing / unavailable states only, without entering credentials or running provider Network inspection.
- Controlled AI generation smoke only after explicit human authorization and configured AI Client environment, with no raw request/response or generated body recording.
- Isolated package install validation.
- Isolated Plugin Check.

## 14. Prohibited Operations in This Step

Not performed and not authorized in Step 297:

- Production code changes
- `readme.txt` changes
- Build script changes
- Translation file changes
- Text Domain changes
- OpenAI / Google / GA4 / OAuth / AI provider external communication
- Browser admin smoke
- Plugin Check
- Credential, token, OAuth client, Connector, provider, or option value inspection
- AI Connector configuration
- Commit or push
- WordPress.org upload or slug reservation

## 15. Risks / Unresolved Items

- Exact WordPress 7.0 AI Client public API shape, argument schema, return object, and generated-text extraction must be confirmed before code implementation.
- Availability and naming of text-generation capability checks must be confirmed.
- Whether `wp_ai_client_prompt()` accepts model preferences, provider preferences, or only prompt/input must be confirmed. Initial implementation should avoid provider-specific model selection unless the public API provides a safe generic mechanism.
- If the AI Client returns provider names, decide whether those labels are safe and intended for plugin admin UI before displaying them.
- Saved pre-public OpenAI API key values may remain in the main settings option until a Settings save or explicit cleanup path is executed. Decide whether implementation should include deterministic cleanup.
- Readme/provider terms wording must avoid creating an incomplete fixed provider disclosure after provider selection moves to Connectors.
- Package credential scan rules may need adjustment once `openai_api_key` becomes a historical/deferred term rather than runtime source.
- Translation-loading retirement depends on final confirmation that WordPress 7.0 is the minimum version for the release candidate.
- Human validation for configured AI Client generation remains separate and must preserve no raw request/response / generated-body evidence recording.

## 16. Next Recommended Implementation Step

Recommended next step:

`Step 298: WordPress AI Client migration source-level implementation plan checkpoint`

Purpose:

- Confirm the exact public WordPress 7.0 AI Client function signature and return handling before editing production PHP.
- Decide whether to keep the existing `Analytics_Report_AI_OpenAI_Client` class as a temporary compatibility wrapper with provider-neutral internals or rename it in a separate mechanical step.
- Decide whether OpenAI key cleanup happens in the first implementation step or in a narrowly scoped follow-up.
- Finalize provider-neutral error categories and admin wording before implementation.

