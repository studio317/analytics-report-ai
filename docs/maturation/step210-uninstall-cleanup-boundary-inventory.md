# Step 210: Uninstall Cleanup Boundary Inventory

## Step Purpose

Step 210 is a docs-only and source-level inventory for the Analytics Report AI
uninstall cleanup boundary.

Step 209 classified credential storage public-release posture as `Hold` and
separated local-only disconnect from uninstall cleanup. This step inventories
plugin-owned storage categories, cleanup candidates, non-cleanup targets, and
the decisions needed before any uninstall cleanup implementation.

No uninstall behavior is implemented in this step.

WordPress.org release status remains `Hold`.

## Scope

In scope:

- root `uninstall.php` presence check,
- `register_uninstall_hook` presence check,
- plugin-owned option key inventory,
- main settings option inventory,
- dedicated Google OAuth token option inventory,
- credential-related storage categories,
- transient-like runtime data categories,
- generated report persistence relationship,
- local-only disconnect versus uninstall cleanup,
- Settings delete controls versus uninstall cleanup,
- public-release cleanup decision table,
- recommended next step.

Out of scope:

- production code changes,
- uninstall implementation,
- option value inspection,
- database inspection,
- browser smoke,
- external API calls,
- provider-side revoke,
- release readiness approval.

## Explicit Non-goals

Step 210 does not:

- change production code,
- create `uninstall.php`,
- add `register_uninstall_hook`,
- change `readme.txt`,
- change tools or build scripts,
- change JavaScript or CSS,
- run Plugin Check,
- run GA4 Fetch,
- run OpenAI Generate,
- start OAuth Connect / Authorize,
- navigate to Google,
- call the token endpoint,
- execute refresh requests,
- execute revoke requests,
- run browser admin smoke,
- collect screenshots,
- collect browser Network evidence,
- inspect database dumps,
- run `wp option get` for plugin option values,
- inspect option values,
- inspect token values,
- inspect credential values,
- inspect OAuth client values,
- inspect request bodies,
- inspect raw responses,
- inspect AI payload JSON,
- inspect generated report bodies.

## Referenced Prior Steps

- `docs/maturation/step209-credential-storage-public-release-posture-checkpoint.md`
- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`
- `docs/maturation/step207-oauth-token-lifecycle-controlled-human-admin-smoke-results.md`
- `docs/maturation/step205-oauth-token-lifecycle-source-level-verification-results.md`
- `docs/maturation/step204-oauth-token-lifecycle-narrow-production-implementation-results.md`
- `docs/maturation/step118-uninstall-credential-cleanup-implementation-plan.md`
- `docs/maturation/step15-credential-storage-policy.md`

## Inventory Method

The inventory used source-level checks only.

Commands and checks were limited to:

- repository status,
- root `uninstall.php` presence,
- source search for uninstall hooks, option helpers, and transient helpers,
- prior maturation docs review.

Allowed evidence was limited to:

- source file names,
- function / method / constant / option key names,
- storage category,
- cleanup category,
- source-level conclusion,
- docs-level reference,
- `Hold`, `Needs implementation`, `Not applicable`, or `Candidate for cleanup`
  classification.

No option values, credential values, token values, OAuth client values,
serialized option values, database row contents, request bodies, raw responses,
AI payload JSON, generated report bodies, screenshots, browser Network evidence,
cookies, sessions, nonces, GA4 Property ID values, hostname/domain values, or
analytics values were inspected or recorded.

## Current Uninstall Mechanism Inventory

| Mechanism | Source-level result | Cleanup classification | Notes |
|---|---|---|---|
| Root `uninstall.php` | `root_uninstall_missing` | Hold / Needs implementation | No root uninstall file was found during source-level inventory. |
| `register_uninstall_hook` | Not found in production source search | Hold / Needs decision | No uninstall hook boundary is currently implemented. |
| Main settings option delete on uninstall | Not implemented | Candidate for cleanup / Needs decision | Requires policy decision before implementation. |
| Dedicated OAuth token option delete on uninstall | Not implemented as uninstall cleanup | Candidate for cleanup / Needs implementation | Local-only disconnect exists, but uninstall cleanup is separate. |
| `delete_option()` | Present for local OAuth token deletion helper | Not uninstall cleanup | Current helper supports explicit local disconnect only. |
| `delete_site_option()` | Not found in source-level search | Needs decision if multisite is supported | Multisite cleanup boundary is not defined in this step. |
| Runtime `delete_transient()` | Present for OAuth state and payload runtime cleanup | Not uninstall cleanup | Runtime transient cleanup is separate from uninstall cleanup. |
| Runtime `set_transient()` | Present for OAuth state and payload preview flow | Candidate for cleanup / Needs decision | Dynamic transient cleanup feasibility needs a separate decision. |

## Plugin-owned Storage Inventory

| Storage area | Source-level reference | Storage category | Cleanup classification | Notes |
|---|---|---|---|---|
| Main plugin settings option | `ANALYTICS_REPORT_AI_OPTION_NAME` / `analytics_report_ai_settings` | Plugin settings option | Candidate for cleanup / Needs decision | Contains non-secret settings and credential fallback categories. Deleting the whole option versus selective cleanup needs policy decision. |
| Dedicated Google OAuth token option | `ANALYTICS_REPORT_AI_GOOGLE_OAUTH_TOKEN_OPTION_NAME` / `analytics_report_ai_oauth_tokens` | Dedicated OAuth token option | Candidate for cleanup / Needs implementation | Local-only disconnect exists. Uninstall cleanup should be decided separately. |
| Default settings creation helper | `analytics_report_ai_maybe_add_default_settings_option()` | Default option initialization | Not cleanup target by itself | Relevant because the main option is plugin-owned and non-autoloaded on new installs. |
| OAuth token storage helper | `analytics_report_ai_store_google_oauth_tokens()` | Dedicated OAuth token storage helper | Candidate for cleanup via option deletion | Cleanup should not inspect or record token values. |
| OAuth token local delete helper | `analytics_report_ai_delete_google_oauth_tokens()` | Local-only disconnect helper | Not uninstall cleanup | Deletes local OAuth token option by explicit admin action. |
| Manual Google Access Token fallback category | `google_tokens['access_token']` within main settings | Temporary Settings fallback credential category | Candidate for cleanup / Needs decision | Public-release posture remains Hold. |
| OpenAI API key category | `openai_api_key` within main settings | Settings-stored API key category | Candidate for cleanup / Needs decision | Public-release posture remains Hold and needs dedicated checkpoint. |
| OAuth client Settings fallback category | `google_oauth_client` within main settings | Settings-stored OAuth client fallback category | Candidate for cleanup / Needs decision | Constants are preferred; Settings fallback cleanup policy remains undecided. |
| OAuth client constants | `ANALYTICS_REPORT_AI_GOOGLE_OAUTH_CLIENT_ID`, `ANALYTICS_REPORT_AI_GOOGLE_OAUTH_CLIENT_SECRET` | Constant-based configuration | Not applicable | Constants / `wp-config.php` style configuration are outside plugin-owned uninstall cleanup. |
| AI payload transient key helper | `analytics_report_ai_get_payload_transient_key()` | User-scoped payload transient key | Candidate for cleanup / Needs decision | Dynamic per-user transient cleanup requires careful design. |
| OAuth state transient key boundary | admin OAuth state transient helper boundary | User-scoped OAuth state transient | Candidate for cleanup / Needs decision | Runtime callback deletes state; uninstall cleanup feasibility remains undecided. |
| Generated report body | Report Builder generated report UI | Not persisted by plugin | Not applicable | Generated report text is not plugin-owned persisted data. |

## Credential-related Cleanup Candidates

| Candidate | Cleanup category | Public-release posture | Decision needed |
|---|---|---|---|
| `analytics_report_ai_settings` | Main plugin option cleanup | Hold / Needs decision | Decide whether uninstall deletes the entire settings option or only credential categories. |
| `analytics_report_ai_oauth_tokens` | Dedicated OAuth token option cleanup | Hold / Needs implementation | Deleting this plugin-owned token option is a strong uninstall cleanup candidate. |
| Manual Google Access Token fallback category | Credential fallback cleanup | Hold / Needs decision | Decide whether fallback is retired, restricted, or cleaned through full option deletion. |
| OpenAI API key category | Credential cleanup | Hold / Needs decision | Requires OpenAI API key storage posture checkpoint before implementation. |
| OAuth client Settings fallback category | Credential-like configuration cleanup | Needs decision | Decide whether Settings fallback remains public-release supported and how uninstall should treat it. |

## Non-credential Cleanup Candidates

| Candidate | Cleanup category | Classification | Notes |
|---|---|---|---|
| Non-secret settings in the main option | Plugin-owned settings cleanup | Candidate for cleanup / Needs decision | Includes configuration categories needed by the plugin. Delete-all versus preserve settings is a product policy decision. |
| AI payload transient | Runtime transient-like data cleanup | Candidate for cleanup / Needs decision | Dynamic per-user transient keys make implementation design non-trivial. |
| OAuth state transient | Runtime transient-like data cleanup | Candidate for cleanup / Needs decision | Usually short-lived; uninstall cleanup may be optional if safe discovery is not available. |
| Generated report body | Generated output cleanup | Not applicable | The plugin does not persist generated report text. |
| External service data | Provider-side data | Not applicable | GA4/OpenAI/provider data is not plugin-owned uninstall data. |

## Explicit Non-cleanup Targets

The uninstall cleanup boundary should not target:

- constants / `wp-config.php` style configuration,
- Google provider-side authorization state,
- provider-side revoke operations,
- refresh request execution,
- OpenAI account-side API key lifecycle,
- GA4 property-side data,
- OpenAI service-side data,
- generated report text that the plugin does not persist,
- browser cookies, sessions, nonces, or browser cache,
- screenshots or browser Network evidence,
- database rows not owned by the plugin,
- arbitrary site options outside the plugin-owned option keys.

Provider-side revoke remains a separate future track, not an uninstall cleanup
substitute.

## Local Disconnect Versus Uninstall Cleanup

Local-only OAuth disconnect and uninstall cleanup have different boundaries.

| Area | Local-only disconnect | Uninstall cleanup |
|---|---|---|
| Trigger | Explicit admin action in Settings UI | WordPress plugin uninstall lifecycle |
| Current implementation | Present for dedicated OAuth token option | Not implemented |
| Primary target | `analytics_report_ai_oauth_tokens` | Plugin-owned options/transients to be decided |
| Manual Google Access Token fallback | Out of scope | Candidate if main option or credential categories are cleaned |
| OpenAI API key | Out of scope | Candidate if main option or credential categories are cleaned |
| OAuth client Settings fallback | Out of scope | Candidate if main option or credential categories are cleaned |
| Provider-side revoke | Deferred / Hold | Separate track, not uninstall cleanup |
| Option value inspection | Not required for status-level UI evidence | Must not be required for docs or support evidence |

Local-only disconnect is therefore not a replacement for uninstall cleanup.

## Settings Delete Controls Versus Uninstall Cleanup

Settings delete controls are user-initiated, category-specific cleanup controls
inside the active plugin.

Current Settings delete controls cover saved fallback credential categories, but
they do not run automatically on uninstall and do not define the public-release
uninstall cleanup boundary.

Uninstall cleanup needs its own decision because it must answer:

- whether the entire main settings option should be deleted,
- whether dedicated OAuth token storage should always be deleted,
- whether transient-like data should be deleted,
- whether multisite behavior needs site-option or network-option handling,
- whether uninstall cleanup should be implemented through `uninstall.php` or
  an uninstall hook.

## Public Release Cleanup Decision Table

| Item | Current state | Public-release classification | Recommended handling |
|---|---|---|---|
| Root `uninstall.php` | Missing | Hold / Needs implementation | Decide implementation mechanism before public release. |
| `register_uninstall_hook` | Not found | Hold / Needs decision | Decide hook versus root uninstall file. |
| Main settings option cleanup | Not implemented | Hold / Needs decision | Decide delete-all versus selective cleanup. |
| Dedicated OAuth token option cleanup | Local-only delete helper exists, uninstall cleanup missing | Hold / Needs implementation | Strong cleanup candidate for uninstall. |
| Manual Google Access Token fallback cleanup | Category-specific Settings delete exists | Hold / Needs decision | Tie to fallback retirement/restriction decision. |
| OpenAI API key cleanup | Category-specific Settings delete exists | Hold / Needs decision | Tie to OpenAI API key storage posture checkpoint. |
| OAuth client Settings fallback cleanup | Settings fallback exists | Needs decision | Tie to OAuth client configuration public-release posture. |
| Constants cleanup | Outside plugin-owned storage | Not applicable | Do not touch constants or `wp-config.php` style configuration. |
| AI payload transient cleanup | Runtime transient exists | Candidate for cleanup / Needs decision | Decide feasibility of dynamic transient cleanup. |
| OAuth state transient cleanup | Runtime transient exists | Candidate for cleanup / Needs decision | Decide whether auto-expiry/runtime deletion is sufficient. |
| Generated report body cleanup | Plugin does not persist generated report text | Not applicable | No plugin-owned persisted report body to clean. |
| Provider-side revoke | Deferred / Hold | Not uninstall cleanup | Keep in separate revoke future track. |
| WordPress.org release readiness | Hold | Hold | Do not mark release-ready until cleanup policy and implementation are resolved. |

## Risks and Constraints

- Deleting only credential categories from the main settings option may require
  reading and rewriting option structure, increasing implementation complexity.
- Deleting the entire main settings option is simpler but removes non-secret
  configuration as well as credential fallback categories.
- Dynamic transient cleanup may be difficult without broad database queries or
  tracked transient keys.
- Provider-side revoke is a separate lifecycle decision and should not be
  implied by uninstall cleanup wording.
- Constants / `wp-config.php` style configuration cannot and should not be
  removed by the plugin.
- Any future cleanup implementation must avoid logging or displaying option
  values, token values, credential values, OAuth client values, request bodies,
  raw responses, AI payload JSON, or generated report bodies.

## Recommended Next Step

Recommended next step:

```text
Step 211: Uninstall cleanup policy decision checkpoint
```

Step 211 should remain docs-only / planning-only and decide:

- whether to implement cleanup with root `uninstall.php` or
  `register_uninstall_hook`,
- whether uninstall should delete the entire main settings option,
- whether credential-category-only cleanup is worth the added complexity,
- whether dynamic payload / OAuth state transients are in scope,
- whether multisite cleanup is in scope for the MVP/public-release boundary,
- how uninstall wording should distinguish local deletion from provider-side
  revoke.

Implementation should happen only after this policy decision is closed.

## Result Classification

```text
Uninstall cleanup boundary inventory: Completed
Root uninstall.php: Missing
register_uninstall_hook: Not found
Plugin-owned cleanup candidates: Identified at source/category level
Uninstall cleanup implementation: Hold / Needs implementation
WordPress.org release status: Hold
```
