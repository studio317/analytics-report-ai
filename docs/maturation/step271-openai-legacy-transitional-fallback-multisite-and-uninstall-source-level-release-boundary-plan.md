# Step 271: OpenAI Legacy / Transitional Fallback Multisite and Uninstall Source-level Release-boundary Plan

## Step Purpose

Step 271 is a docs-only / multisite and uninstall source-level
release-boundary planning-only step.

Step 270 classified multisite / network activation / uninstall behavior as:

```text
Deferred / separate release gate
```

The purpose of Step 271 is to define a safe future source-level verification
plan for that gate without enabling multisite, inspecting stored values,
executing uninstall, or changing production behavior.

This step does not approve multisite support and does not approve public
release.

WordPress.org release readiness remains:

```text
Hold
```

## Working-tree Baseline

Baseline commands were run before adding this Step 271 document:

```text
git status --short --untracked-files=all
git diff --name-only
git diff --check
```

Baseline classification:

```text
Clean working tree
```

No Step 270 uncommitted docs or unexpected production-facing changes were
present at the Step 271 baseline.

## Referenced Documents

Requested docs were checked against the repository. The existing docs used for
this plan are:

- `docs/maturation/step210-uninstall-cleanup-boundary-inventory.md`
- `docs/maturation/step211-uninstall-cleanup-policy-decision-checkpoint.md`
- `docs/maturation/step212-uninstall-cleanup-narrow-implementation-plan.md`
- `docs/maturation/step213-uninstall-cleanup-narrow-production-implementation-results.md`
- `docs/maturation/step214-uninstall-cleanup-source-level-verification-results.md`
- `docs/maturation/step215-uninstall-cleanup-maturation-checkpoint.md`
- `docs/maturation/step253-openai-constant-based-configuration-public-release-boundary-checkpoint.md`
- `docs/maturation/step254-openai-settings-fallback-public-release-storage-disposition-decision-checkpoint.md`
- `docs/maturation/step256-openai-settings-fallback-legacy-transitional-narrow-production-implementation-results.md`
- `docs/maturation/step257-openai-settings-fallback-legacy-transitional-source-level-verification-results.md`
- `docs/maturation/step260-openai-settings-fallback-legacy-transitional-post-smoke-release-boundary-checkpoint.md`
- `docs/maturation/step270-openai-legacy-transitional-fallback-storage-migration-and-uninstall-release-boundary-decision-checkpoint.md`

No missing filename was filled in by inference.

## Read-only Source Inventory

Reviewed source files:

- `analytics-report-ai.php`
- `uninstall.php`
- `includes/class-plugin.php`
- `includes/class-settings.php`
- `includes/class-report-builder.php`
- `includes/class-openai-client.php`
- `includes/functions-utils.php`

Reviewed source terms and boundaries:

- `register_activation_hook`
- activation callback and `network_wide` parameter handling
- `get_option()`, `add_option()`, `update_option()`, `delete_option()`
- absence or presence of `get_site_option()`, `update_site_option()`,
  `delete_site_option()`
- absence or presence of `get_sites()`, `switch_to_blog()`,
  `restore_current_blog()`
- `WP_UNINSTALL_PLUGIN` guard
- OpenAI source categories: `constant_configured`, `settings_saved`, `missing`
- OpenAI Settings fallback clear control: `clear_openai_api_key`

No actual credential, API key value, constant value, Settings option value,
token, serialized option data, database content, request/response body, payload
JSON, or generated report body was inspected or recorded.

## Source-level Snapshot for Future Verification

This is planning context only. Step 272 must verify these again if it is run.

- `analytics-report-ai.php` registers activation through
  `register_activation_hook()`.
- `includes/class-plugin.php` accepts the activation hook `network_wide`
  parameter and comments that multisite network activation is outside MVP
  support scope.
- plugin settings use the site-level option family in the reviewed source.
- OAuth token storage uses the site-level option family in the reviewed source.
- OpenAI constant source resolution is evaluated before Settings fallback.
- `uninstall.php` has a `WP_UNINSTALL_PLUGIN` guard and deletes deterministic
  plugin-owned option keys with `delete_option()`.
- `uninstall.php` does not show per-site iteration, site-option cleanup, or
  network-level cleanup in the reviewed source.

These are category-level observations only and do not prove runtime deployment
state.

## Required Source-level Verification Plan

### A. Activation and Network Scope Plan

Future Step 272 should review:

- activation hook registration location;
- activation callback behavior;
- whether network activation is explicitly accepted, rejected, ignored, or not
  handled;
- whether activation code branches on multisite/network context;
- whether the current MVP scope statement is reflected in implementation.

Source terms to inspect:

- `register_activation_hook`
- `activate`
- `network_wide`
- `is_multisite`
- `is_network_admin`
- `get_sites`
- `switch_to_blog`
- `restore_current_blog`

Required boundary:

```text
No conclusion about functional network activation support may be made from documentation wording alone.
```

Step 272 should classify activation / network activation as one of:

- `Source-confirmed single-site boundary`
- `Source-confirmed multisite/network behavior`
- `Deferred / separate release gate`
- `Blocked`

### B. Option-storage Scope Plan

Future Step 272 should review:

- the option API family used for plugin settings;
- the option API family used for OAuth token storage;
- whether any network-level option API is used;
- whether existing fallback is structurally site-local or network-level in
  current source;
- whether source code includes explicit multisite branches.

Source terms to inspect:

- `get_option`
- `add_option`
- `update_option`
- `delete_option`
- `get_site_option`
- `update_site_option`
- `delete_site_option`
- `ANALYTICS_REPORT_AI_OPTION_NAME`
- `ANALYTICS_REPORT_AI_GOOGLE_OAUTH_TOKEN_OPTION_NAME`

Required boundary:

```text
Do not inspect any stored option value or database contents.
Do not infer actual deployment state from option API names alone.
```

Step 272 should identify whether source-level storage appears site-level only,
network-level, mixed, or inconclusive.

### C. Constant and Multisite Scope Plan

Future Step 272 should review:

- whether constant resolution is evaluated before Settings fallback;
- whether the resolver contains multisite-specific branching;
- whether the source code documents or enforces per-site versus shared constant
  scope;
- whether no such scope can be concluded from source alone.

Source terms to inspect:

- `ANALYTICS_REPORT_AI_OPENAI_API_KEY`
- `analytics_report_ai_get_openai_api_key_constant_name`
- `analytics_report_ai_get_openai_api_key_source`
- `analytics_report_ai_resolve_openai_api_key`
- `constant_configured`
- `settings_saved`
- `missing`

Required boundary:

```text
A PHP constant may be deployment-controlled, but Step 271 must not assume whether a particular multisite deployment shares or separates the constant.
```

Step 272 must not inspect the actual constant value.

### D. Legacy Fallback Compatibility Plan

Future Step 272 should review:

- whether Settings fallback source resolution uses the same site-local settings
  path as normal Settings behavior;
- whether any network-level fallback source exists;
- whether source priority remains constant-first, then legacy fallback, then
  missing;
- whether fallback clear behavior is scoped only to the current Settings
  fallback slot;
- whether the source contains per-site iteration or cross-site fallback cleanup.

Source terms to inspect:

- `openai_api_key`
- `clear_openai_api_key`
- `openai_api_key_source_category`
- `openai_api_key_settings_fallback_status`
- `openai_api_key_value_visibility`
- `analytics_report_ai_get_settings`
- `analytics_report_ai_get_openai_api_key_source`

Required boundary:

```text
Do not verify actual fallback contents, actual fallback existence, or usable provider status.
```

### E. Uninstall Source-level Plan

Future Step 272 should review `uninstall.php` for:

- `WP_UNINSTALL_PLUGIN` guard;
- deterministic option-key deletion;
- single-site `delete_option()` use;
- `get_sites()`, `switch_to_blog()`, and `restore_current_blog()` presence or
  absence;
- `delete_site_option()` / network-level cleanup presence or absence;
- provider/revoke/external request behavior presence or absence;
- whether cleanup scope can be stated as single-site only.

Source terms to inspect:

- `WP_UNINSTALL_PLUGIN`
- `delete_option`
- `delete_site_option`
- `get_sites`
- `switch_to_blog`
- `restore_current_blog`
- `wp_remote`
- `revoke`
- `refresh`

Required boundary:

```text
Absence of multisite iteration in the current uninstall source means only that multisite cleanup is not source-confirmed. It does not prove that no site data exists or that all deployment modes behave identically.
```

Step 272 must not execute plugin uninstall.

### F. Release-boundary Decision Plan

Future Step 272 should classify each topic as exactly one of:

```text
Source-confirmed single-site boundary
Source-confirmed multisite/network behavior
Deferred / separate release gate
Blocked
```

Required topics:

- activation / network activation;
- Settings fallback storage scope;
- OAuth token storage scope;
- constant resolution scope;
- fallback-only compatibility scope;
- fallback removal scope;
- uninstall option cleanup;
- uninstall provider-side behavior;
- multisite / network uninstall completeness;
- public readme wording scope;
- WordPress.org release readiness.

## Required Evidence Matrix

| Topic | Source files / source terms to inspect | Permitted evidence | Forbidden evidence | Possible conclusion | Non-conclusion boundary |
| ----- | -------------------------------------- | ------------------ | ------------------ | ------------------- | ----------------------- |
| Network activation | `analytics-report-ai.php`, `includes/class-plugin.php`; `register_activation_hook`, `network_wide`, `activate` | File names, hook names, parameter handling, category-level branch behavior | Runtime activation, network activation, site list, option values | Single-site boundary, multisite behavior, or Deferred gate | Documentation wording alone cannot prove functional network support. |
| Multisite detection | `includes/`, `uninstall.php`; `is_multisite`, `is_network_admin` | Presence/absence of source terms | Multisite setup, runtime admin checks | Source-confirmed detection or Deferred gate | Absence of terms does not prove runtime deployment data state. |
| Settings fallback storage | `includes/functions-utils.php`, `includes/class-settings.php`; `get_option`, `openai_api_key` | Option key name, API family, helper names, category labels | Stored option values, serialized data, credentials | Site-level source boundary or Deferred gate | API family does not reveal actual deployment contents. |
| OAuth token storage | `includes/functions-utils.php`; token option constant and option API terms | Option key name and API family only | Token values, database rows, token endpoint data | Site-level source boundary or Deferred gate | Source review cannot prove token contents or provider state. |
| Constant resolution | `includes/functions-utils.php`; constant helper and resolver helpers | Constant name symbol and source ordering | Actual constant value | Constant-first source boundary | Source review cannot determine deployment-wide constant sharing. |
| Fallback clear scope | `includes/class-settings.php`; `clear_openai_api_key` | Checkbox name, branch target, wording | Executing clear, option values | Slot-limited clear boundary | Source review cannot prove actual stored fallback contents. |
| Uninstall cleanup | `uninstall.php`; `WP_UNINSTALL_PLUGIN`, `delete_option` | Option key names and guard presence | Plugin uninstall execution, option values | Source-confirmed single-site boundary | Single-site option deletion does not prove multisite completeness. |
| Per-site uninstall iteration | `uninstall.php`; `get_sites`, `switch_to_blog`, `restore_current_blog` | Presence/absence of iteration terms | Runtime multisite enumeration | Source-confirmed behavior or Deferred gate | Absence does not prove no per-site data exists. |
| Network option cleanup | `uninstall.php`; `delete_site_option`, site-option APIs | Presence/absence of network option APIs | Network option values | Source-confirmed network cleanup or Deferred gate | Absence means network cleanup is not source-confirmed. |
| External/provider behavior on uninstall | `uninstall.php`; `wp_remote`, `revoke`, `refresh` | Presence/absence of provider call terms | External HTTP, provider response, credentials | Provider-side behavior excluded or separate gate | Source review cannot validate provider account state. |

## Required Implementation Classification Matrix

| Concern | Step 270 current classification | Step 271 verification objective | What Step 271 cannot establish |
| ------- | ------------------------------- | ------------------------------- | ------------------------------ |
| Single-site uninstall cleanup | Decided for current MVP boundary | Plan source verification for guard and deterministic site-level option cleanup. | Actual uninstall result or option contents. |
| Legacy fallback storage | Provisional release-boundary decision | Plan source verification for site-level Settings fallback storage path. | Actual fallback value, existence, or provider usability. |
| Fallback-only existing installation | Provisional release-boundary decision | Plan source verification for `settings_saved` compatibility behavior. | Public-release approval or future deprecation timing. |
| Constant plus fallback | Provisional release-boundary decision | Plan source verification for constant-first priority with saved fallback status retained. | Actual constant preservation or actual fallback contents. |
| Normal update behavior | Provisional release-boundary decision | Plan source review for absence of automatic migration/deletion paths. | Runtime update outcomes across deployments. |
| Automatic migration | Decided for current compatibility boundary | Plan source review for absence of plugin-written constant migration. | Any future migration UI or policy. |
| Automatic deletion | Provisional release-boundary decision | Plan source review for absence of automatic deletion on constant activation. | Future deprecation or removal timing. |
| Network activation | Deferred / separate release gate | Plan source review of hook and `network_wide` handling. | Functional multisite support. |
| Multisite uninstall | Deferred / separate release gate | Plan source review for per-site iteration and network cleanup APIs. | Runtime multisite cleanup completeness. |
| Provider-side revoke | Deferred / separate release gate | Plan source review that uninstall does not call external provider APIs. | Provider account revocation state. |

## Required Future Decision Boundaries

### Current Single-site Boundary

- current single-site plugin-owned option cleanup is already source-level
  verified;
- current source-level verification does not establish multisite completeness;
- provider-side revoke remains outside uninstall cleanup;
- actual option contents are not inspected.

### Current OpenAI Fallback Boundary

- constant-first remains preferred source ordering;
- existing fallback remains legacy / transitional compatibility only;
- fallback clear remains explicit and slot-limited;
- no automatic migration or automatic deletion is introduced.

### Multisite Release Boundary

- no multisite or network behavior may be declared release-ready without
  source-level confirmation;
- source-level confirmation alone does not substitute for later controlled
  runtime validation if multisite support is pursued;
- until an explicit support decision exists, multisite/network remains
  Deferred / separate release gate.

## Explicit Non-goals

Step 271 does not:

- implement multisite support;
- activate a network plugin;
- create a multisite fixture;
- modify `uninstall.php`;
- modify option storage APIs;
- add per-site iteration;
- add network option cleanup;
- change fallback source priority;
- change fallback clear behavior;
- add automatic migration or automatic deletion;
- modify `readme.txt`;
- inspect actual option or credential values;
- execute uninstall;
- verify actual provider authorization;
- verify OpenAI request or response success;
- verify real external communication;
- run Plugin Check;
- approve public release;
- establish WordPress.org policy compliance.

## Security and Evidence Boundary

Step 271 did not inspect or record:

- actual credentials;
- API key values;
- constant values;
- Settings option values;
- token values;
- serialized option data;
- database contents;
- request bodies;
- response bodies;
- payload JSON;
- generated report bodies;
- screenshots;
- browser Network evidence.

Step 271 did not perform:

- multisite setup or network activation;
- browser admin smoke;
- Settings save;
- fallback removal operation;
- WP-CLI state mutation;
- `wp option get`;
- `wp site list`;
- raw SQL / database dump;
- option / constant / credential value inspection;
- plugin uninstall execution;
- OpenAI Generate;
- GA4 Fetch;
- OAuth;
- external HTTP;
- provider communication;
- Plugin Check.

## Result Classification

```text
Multisite and uninstall source-level release-boundary plan completed
```

WordPress.org release readiness remains:

```text
Hold
```

## Recommended Next Step

```text
Step 272 candidate — OpenAI legacy/transitional fallback multisite and uninstall source-level verification
```

Step 272 is not started in this document.
