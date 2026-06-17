# Step 187: OAuth Client Configuration Source-level Inventory

## Step Purpose

Step 187 is a docs-only and inspection-only source-level inventory of the
current OAuth client configuration boundary.

This step follows the Step 186 hybrid strategy planning work and records where
the current source already supports OAuth client configuration, where it remains
constants-only, and what is still missing before a constants-preferred plus
Settings fallback model can be planned for narrow production implementation.

This step does not change production code, `readme.txt`, tools, build scripts,
JavaScript, CSS, admin behavior, OAuth behavior, credential storage, GA4
behavior, OpenAI behavior, payload handling, transient handling, release
packaging, or generated report persistence.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step186-oauth-client-configuration-source-strategy-implementation-plan.md`
- `docs/maturation/step185-oauth-app-ownership-provider-configuration-decision-checkpoint.md`
- `docs/maturation/step184-oauth-source-inventory-current-boundary-review.md`
- `docs/maturation/step183-oauth-public-release-readiness-implementation-plan.md`
- `docs/maturation/step182-oauth-credential-public-release-strategy-checkpoint.md`
- `docs/maturation/step181-public-release-blocker-prioritization-checkpoint.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`
- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`
- `docs/maturation/step179-support-debug-wording-maturation-checkpoint.md`

## Inspection Boundary

This inventory reviewed source structure and status/category-level behavior
only.

Not performed:

- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- GA4 Fetch,
- OpenAI Generate,
- Plugin Check,
- browser admin smoke,
- screenshot capture,
- browser Network evidence collection,
- database dump,
- plugin settings option value inspection,
- OAuth token option value inspection.

Not displayed, copied, recorded, or inferred:

- credentials,
- API keys,
- access tokens,
- refresh tokens,
- Authorization headers,
- OAuth client ID values,
- OAuth client secret values,
- plugin settings option values,
- OAuth token option values,
- serialized option values,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- page path/source/city values,
- request bodies,
- raw GA4 responses,
- raw OpenAI responses,
- AI payload JSON,
- generated report bodies,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces.

## Source Inventory

| Area | Source file | Class / method / function | Current implementation status | Hybrid strategy relevance | Boundary / limitation | Potential Step 188 action | Risk level |
|---|---|---|---|---|---|---|---|
| OAuth client ID constant helper | `includes/class-admin.php` | `Analytics_Report_AI_Admin::get_google_oauth_client_id()` | `implemented_narrow_boundary` | Current runtime source for client ID category. | Reads only the configured constant source and returns the value only for request-local runtime use. No Settings fallback source exists. | `plan_settings_fallback_storage` | High |
| OAuth client secret constant helper | `includes/class-admin.php` | `Analytics_Report_AI_Admin::get_google_oauth_client_secret()` | `implemented_narrow_boundary` | Current runtime source for client secret category. | Reads only the configured constant source and returns the value only for token exchange runtime use. No Settings fallback source exists. | `plan_settings_fallback_storage` | High |
| OAuth client configuration completeness check | `includes/class-settings.php` | `Analytics_Report_AI_Settings::get_google_oauth_client_configuration_status()` | `implemented_narrow_boundary` | Provides status-level client configuration presence labels. | Checks constant presence only. It does not classify Settings fallback, active source, conflict, or replacement status. | `add_active_source_label` | High |
| Constant presence helper | `includes/class-settings.php` | `Analytics_Report_AI_Settings::is_non_empty_constant()` | `implemented_narrow_boundary` | Supports value-hidden presence checks for constants. | Does not return values and does not inspect Settings fallback. | `no_change_or_reuse` | Medium |
| Client source status labels | `includes/class-settings.php` | Settings render status output | `implemented_narrow_boundary` | Existing labels are safe and value-hidden. | Labels are constant-specific and do not match the Step 186 hybrid categories such as `constants`, `settings`, `missing`, `incomplete`, or `conflict`. | `add_active_source_label` | High |
| Settings UI fields for OAuth client configuration | `includes/class-settings.php` | Settings page rendering | `not_implemented` | Required if Settings fallback is accepted. | No OAuth client ID / client secret Settings input fields are currently rendered. | `plan_settings_fallback_storage` | High |
| Settings UI saved/not-saved status display | `includes/class-settings.php` | Settings page rendering | `partial_boundary_exists` | Needed for value-hidden fallback UX. | Existing Settings UI shows constant configuration status and OAuth connection state, but it does not show saved/not-saved status for Settings-stored OAuth client values because those values are not stored. | `add_settings_fallback_status` | Medium |
| Settings save behavior | `includes/class-settings.php` | `Analytics_Report_AI_Settings::sanitize_settings()` | `not_implemented_for_oauth_client_values` | Needed for Settings fallback storage. | Save logic handles GA4 property, host filter, manual Google access token fallback, and OpenAI API key. It does not accept or store OAuth client ID / client secret fallback values. | `add_settings_fallback_storage` | High |
| Settings delete behavior | `includes/class-settings.php` | `Analytics_Report_AI_Settings::sanitize_settings()` and Settings form | `not_implemented_for_oauth_client_values` | Needed for safe fallback value lifecycle. | Delete controls exist for the manual Google access token fallback and OpenAI API key only. No OAuth client Settings values exist to delete. | `add_delete_semantics` | High |
| Active source label | `includes/class-settings.php` / `includes/class-admin.php` | Current status rendering and helpers | `not_implemented` | Required by Step 186 hybrid strategy. | Current source does not distinguish `constants` vs `settings` active source because only constants are implemented. | `add_active_source_label` | High |
| Constants precedence | `includes/class-admin.php` / `includes/class-settings.php` | OAuth client helpers and Settings status | `not_implemented_as_hybrid_precedence` | Required if Settings fallback is added. | Constants effectively dominate today because they are the only source, but explicit precedence over Settings fallback is not implemented. | `plan_constants_precedence` | High |
| Settings fallback behavior | `includes/class-admin.php` / `includes/class-settings.php` | OAuth client helpers and Settings save/render | `not_implemented` | Core Step 186 hybrid requirement. | Runtime helpers do not read Settings-stored OAuth client values. Settings UI does not save them. | `add_settings_fallback_storage` | High |
| Incomplete configuration detection | `includes/class-settings.php` | `get_google_oauth_client_configuration_status()` | `implemented_narrow_boundary` | Required for safe setup guidance. | Detects constant-only missing client ID or missing client secret categories. Does not detect incomplete Settings fallback or mixed-source incomplete states. | `add_incomplete_status` | Medium |
| Conflict detection | `includes/class-admin.php` / `includes/class-settings.php` | Not present | `not_implemented` | Needed if constants and Settings fallback can both exist. | No conflict category is possible while Settings fallback storage is absent. | `add_conflict_status` | Medium |
| OAuth Connect precondition check | `includes/class-admin.php` | `handle_google_oauth_connect()` | `implemented_narrow_boundary` | Ensures OAuth redirect start is blocked when local preconditions fail. | Checks the client ID helper only. With hybrid strategy, this dependency must resolve the active client ID source safely. | `update_precondition_to_resolver` | High |
| Authorization URL construction dependency | `includes/class-admin.php` | `build_google_oauth_authorization_url()` | `implemented_narrow_boundary` | Depends on the current client ID helper. | Builds request-local authorization data without outputting the generated URL. With hybrid strategy, this must use the active source resolver without exposing values. | `update_dependency_to_resolver` | High |
| Callback / token exchange dependency | `includes/class-admin.php` | `exchange_google_oauth_authorization_code_for_tokens()` | `implemented_narrow_boundary` | Token exchange depends on client ID and client secret helpers. | Uses current constants-only helpers and safe status categories. With hybrid strategy, both dependencies must resolve active source values without exposing them. | `update_dependency_to_resolver` | High |
| Support/debug safe wording | `includes/class-settings.php`, `docs/maturation/*` | Settings support-safe hints and maturation docs | `matured_for_current_boundary` | Required for value-hidden public support posture. | Wording already asks for status/category labels only and forbids sharing sensitive evidence. It will need alignment with any new source labels. | `adjust_settings_wording` | Low |
| Value-hidden posture | `includes/class-settings.php`, `includes/class-admin.php` | Status rendering, helper comments, notices | `implemented_narrow_boundary` | Must be preserved in hybrid implementation. | Values are not displayed by current Settings UI. Future Settings fallback fields must preserve empty value attributes, non-redisplay, and delete-only semantics. | `preserve_and_extend_value_hidden_posture` | High |
| Manual token fallback relationship | `includes/functions-utils.php`, `includes/class-settings.php`, `includes/class-report-builder.php` | `analytics_report_ai_resolve_google_ga4_credential_source()` and Settings manual token UI | `not_public_release_finalized` | Separate credential-source track that can affect public release posture. | Manual token fallback remains available for controlled maturation and is separate from OAuth client configuration source strategy. | `defer_until_storage_strategy` | High |

## Current Implementation Boundary

Current source-level boundary:

- OAuth client configuration source is constants-only.
- Client ID and client secret values are not displayed by the Settings UI.
- Settings renders a status-level client configuration label based on constant
  presence.
- OAuth Connect has a local client ID precondition before attempting redirect.
- Authorization URL construction depends on the current client ID helper but is
  not intended to display, log, or store the generated URL.
- Token exchange depends on current client ID and client secret helpers.
- Token exchange and callback paths use status/category-level notices rather
  than displaying raw code, token, request, or response values.
- Redirect URI setup guidance exists in Settings.
- OAuth-first wording exists in Settings.
- Support/debug wording asks for status/category labels and forbids sensitive
  evidence.
- Manual Google Access Token fallback remains a separate MVP maturation fallback
  and is not finalized for public release.

## Missing / Not Finalized for Hybrid Strategy

Against the Step 186 recommended hybrid strategy, the following are not yet
implemented or not finalized:

- Settings UI fallback storage for OAuth client ID / client secret.
- Settings UI stored value non-redisplay for OAuth client values.
- Settings UI saved/not-saved labels for OAuth client fallback values.
- Settings fallback sanitization and replacement behavior.
- Delete controls for Settings-stored OAuth client values.
- Explicit constants precedence over Settings fallback values.
- Active source label for `constants`, `settings`, `missing`, `incomplete`, or
  `conflict`.
- Conflict category when constants and Settings fallback values both exist.
- Incomplete category across constants-only, Settings-only, and mixed-source
  states.
- Notice wording for constants-active vs settings-active states.
- Source resolver shared by Settings status, OAuth Connect preconditions,
  authorization URL construction, and token exchange dependencies.
- Source-level verification plan for the hybrid resolver.
- Human admin smoke plan for active source labels, non-redisplay, delete
  semantics, and missing/incomplete/conflict states.

## Forbidden Evidence Check

Source-level and docs-level wording reviewed in this step continues to align
with the category-only support/debug posture.

Result: `forbidden_evidence_request_not_found_at_category_level`

No support/debug wording was identified that asks users to provide:

- OAuth client ID values,
- OAuth client secret values,
- credentials,
- API keys,
- access or refresh tokens,
- Authorization headers,
- plugin option values,
- OAuth token option values,
- serialized option values,
- request bodies,
- raw responses,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces,
- database dumps.

Future Step 188 planning should preserve this boundary and extend it to any new
Settings fallback source labels, delete controls, and verification instructions.

## Acceptance Criteria Status

| Criterion | Status | Notes |
|---|---|---|
| Docs-only source inventory file added | Pass | This file records the Step 187 inventory. |
| Production code / readme / tools unchanged | Pass | No production file changes are part of Step 187. |
| OAuth client configuration source boundaries organized | Pass | Current constants-only boundary and helper dependencies are documented. |
| Missing / not finalized hybrid items organized | Pass | Settings fallback, active labels, precedence, conflict, incomplete, and delete semantics are listed. |
| Forbidden evidence request wording checked at category level | Pass | No request for forbidden evidence was recorded. |
| WordPress.org release remains `Hold` | Pass | Release readiness is still blocked by unresolved OAuth client configuration implementation and related lifecycle work. |
| Next recommended step is explicit | Pass | Step 188 is recommended below. |

## Recommended Next Step

Recommended next step:

```text
Step 188: OAuth client configuration hybrid source implementation plan
```

Recommended Step 188 scope:

- docs-only,
- planning-only,
- translate the constants-preferred plus Settings fallback hybrid model into a
  narrow production implementation plan,
- preserve value-hidden status/category labels,
- plan Settings fallback storage and non-redisplay without implementing it yet,
- plan delete semantics for Settings-stored OAuth client values,
- plan constants precedence, incomplete status, conflict status, and active
  source labels,
- do not execute OAuth, Google navigation, token endpoint communication,
  external API calls, option value inspection, or browser smoke.

Step 187 result classification:

```text
Source-level inventory completed
```
