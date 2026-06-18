# Step 211: Uninstall Cleanup Policy Decision Checkpoint

## Step Purpose

Step 211 is a docs-only and planning-only checkpoint for Analytics Report AI
uninstall cleanup policy.

Step 210 completed the source-level uninstall cleanup boundary inventory and
confirmed that root `uninstall.php` is missing, `register_uninstall_hook` was
not found, plugin-owned cleanup candidates are identifiable at source/category
level, and uninstall cleanup remains `Hold / Needs implementation`.

This step decides the preferred cleanup policy before any implementation step.
It does not create uninstall files, hooks, or cleanup code.

WordPress.org release readiness remains `Hold`.

## Scope

In scope:

- cleanup mechanism policy,
- root `uninstall.php` versus `register_uninstall_hook`,
- main settings option cleanup policy,
- dedicated OAuth token option cleanup policy,
- whole-option cleanup versus credential-category-only cleanup,
- manual Google Access Token fallback cleanup category,
- OpenAI API key cleanup category,
- OAuth client Settings fallback cleanup category,
- runtime transient-like data cleanup policy,
- explicit non-cleanup targets,
- multisite boundary decision,
- public-release minimum cleanup scope,
- implementation readiness assessment,
- recommended next step.

Out of scope:

- production implementation,
- uninstall file creation,
- uninstall hook registration,
- option value inspection,
- database inspection,
- browser smoke,
- Plugin Check,
- external API calls,
- release-readiness approval.

## Explicit Non-goals

Step 211 does not:

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

- `docs/maturation/step210-uninstall-cleanup-boundary-inventory.md`
- `docs/maturation/step209-credential-storage-public-release-posture-checkpoint.md`
- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`
- `docs/maturation/step204-oauth-token-lifecycle-narrow-production-implementation-results.md`
- `docs/maturation/step118-uninstall-credential-cleanup-implementation-plan.md`
- `docs/maturation/step15-credential-storage-policy.md`

## Policy Decision Inputs From Step 210

Step 210 established the following source/category-level inputs:

| Input | Step 210 result | Policy impact |
|---|---|---|
| Root `uninstall.php` | Missing | Cleanup mechanism needs implementation. |
| `register_uninstall_hook` | Not found | Hook-based cleanup is not currently implemented. |
| Main settings option | `ANALYTICS_REPORT_AI_OPTION_NAME` / `analytics_report_ai_settings` | Plugin-owned cleanup candidate. |
| Dedicated OAuth token option | `ANALYTICS_REPORT_AI_GOOGLE_OAUTH_TOKEN_OPTION_NAME` / `analytics_report_ai_oauth_tokens` | Strong plugin-owned cleanup candidate. |
| Manual Google Access Token fallback | Stored as a category inside the main settings option | Cleanup should be covered by main option policy. |
| OpenAI API key | Stored as a category inside the main settings option | Cleanup should be covered by main option policy unless later separated. |
| OAuth client Settings fallback | Stored as a category inside the main settings option | Cleanup should be covered by main option policy. |
| Constants / `wp-config.php` style configuration | Outside plugin-owned storage | Not cleanup target. |
| Payload transient | Dynamic user-scoped transient-like data | Cleanup policy needs bounded decision. |
| OAuth state transient | Dynamic user-scoped transient-like data | Cleanup policy needs bounded decision. |
| Generated report body | Not persisted by plugin | Not applicable. |
| Provider-side revoke | Separate lifecycle concern | Not uninstall cleanup. |
| Multisite cleanup | Not defined | Hold unless explicitly scoped. |

No option values, credential values, token values, OAuth client values,
serialized option values, database rows, request bodies, raw responses, AI
payload JSON, generated report bodies, screenshots, browser Network evidence,
GA4 Property ID values, hostname/domain values, or analytics values were
inspected or recorded.

## Cleanup Mechanism Decision

Decision:

```text
Uninstall cleanup mechanism: root uninstall.php preferred / Needs implementation
```

Rationale:

- A root `uninstall.php` is explicit and easy to inspect in a distribution
  package.
- It keeps uninstall cleanup independent from normal runtime bootstrap.
- It avoids loading more plugin runtime code than required during uninstall.
- It can define a narrow allowlist of plugin-owned option keys without relying
  on active plugin state.

`register_uninstall_hook` remains a fallback candidate, but it is not the
preferred policy for this plugin at this checkpoint.

Implementation constraints for a future step:

- verify the WordPress uninstall context before executing cleanup,
- delete only plugin-owned data,
- do not inspect or log option values,
- do not call external services,
- do not execute provider-side revoke,
- keep cleanup code minimal and auditable.

## Main Settings Option Cleanup Policy

Decision:

```text
Main settings option cleanup: Delete whole plugin-owned option / Needs implementation
```

The first-choice policy is to delete the entire `analytics_report_ai_settings`
option on uninstall.

Rationale:

- The option is plugin-owned.
- It contains both non-secret plugin settings and Settings fallback credential
  categories.
- Whole-option deletion is simpler and less error-prone than reading,
  modifying, and rewriting nested credential categories.
- Uninstall semantics normally mean the site owner is removing plugin-owned
  configuration, not merely disconnecting one credential category.
- Whole-option deletion avoids accidentally preserving credential fallback
  categories when nested structure changes later.

Tradeoff:

- Whole-option deletion also removes non-secret configuration categories.
- This is acceptable for uninstall policy, but should be described as deleting
  plugin-owned settings rather than provider-side data.

## Dedicated OAuth Token Option Cleanup Policy

Decision:

```text
Dedicated OAuth token option cleanup: Delete plugin-owned option / Needs implementation
```

The `analytics_report_ai_oauth_tokens` option is a strong cleanup target.

Rationale:

- It is a plugin-owned dedicated token storage option.
- Step 204 / Step 208 matured the local-only disconnect boundary for the
  current MVP, but local disconnect is not uninstall cleanup.
- Uninstall should remove local plugin-owned token storage without contacting
  Google.

Future implementation should delete the option by key and should not inspect,
log, display, or serialize token values.

## Credential Category Cleanup Policy

Decision:

```text
Credential-category-only cleanup: Deferred unless later required
```

Credential-category-only cleanup is not the first-choice policy for uninstall.

Rationale:

- Manual Google Access Token fallback, OpenAI API key, and OAuth client Settings
  fallback are nested categories inside the main settings option.
- Selective cleanup would require reading and rewriting the settings structure.
- Selective cleanup is more fragile if settings keys change in future steps.
- Whole-option deletion covers these credential categories and avoids retaining
  stale nested credential data.

Category-level posture:

| Credential category | Cleanup policy | Classification |
|---|---|---|
| Manual Google Access Token fallback | Covered by whole main settings option deletion | Needs implementation |
| OpenAI API key | Covered by whole main settings option deletion | Needs implementation |
| OAuth client Settings fallback | Covered by whole main settings option deletion | Needs implementation |
| Dedicated OAuth token option | Deleted separately by option key | Needs implementation |
| Constants / `wp-config.php` style configuration | Not plugin-owned storage | Not applicable |

Credential-category-only cleanup can be reconsidered later only if the product
intentionally preserves non-secret settings after uninstall. That is not the
preferred policy in this checkpoint.

## Runtime Transient Cleanup Policy

Decision:

```text
Runtime transient cleanup: Hold / limited implementation scope decision needed
```

Runtime transient-like data includes:

- AI payload transient keys created through `analytics_report_ai_get_payload_transient_key()`,
- OAuth state transient keys created through the admin OAuth state boundary.

Policy direction:

- Do not make dynamic transient cleanup a blocker for the first uninstall
  cleanup implementation unless a safe, narrow, plugin-owned key cleanup method
  is selected.
- Do not perform broad database scans in a first implementation step without a
  separate implementation plan.
- Prefer deleting deterministic known option keys first.
- If transient cleanup is added, it must be constrained to plugin-owned key
  prefixes and must not inspect transient values.

Recommended classification:

```text
Payload transient cleanup: Hold / Needs implementation scope decision
OAuth state transient cleanup: Hold / Needs implementation scope decision
```

The first public-release minimum can proceed with plugin-owned option cleanup
if transient cleanup remains documented as Hold, provided this is accepted in
the implementation plan.

## Explicit Non-cleanup Targets

The uninstall cleanup policy excludes:

- constants / `wp-config.php` style configuration,
- provider-side Google OAuth authorization state,
- provider-side revoke,
- refresh request execution,
- token endpoint communication,
- OpenAI account-side API key lifecycle,
- GA4 property-side data,
- OpenAI service-side data,
- generated report body storage, because the plugin does not persist generated
  report text,
- browser cookies, sessions, nonces, screenshots, or browser Network evidence,
- arbitrary WordPress options not owned by this plugin,
- database rows outside a narrow plugin-owned cleanup allowlist.

Provider-side revoke remains a separate future lifecycle track and should not
be implied by uninstall wording.

## Multisite Boundary Decision

Decision:

```text
Multisite uninstall cleanup: Hold unless explicitly scoped
```

Rationale:

- The current inventory is scoped to the current single-site MVP boundary.
- No network-option cleanup policy has been finalized.
- `delete_site_option()` was not identified as an existing cleanup boundary in
  Step 210.
- Multisite uninstall behavior can broaden the cleanup blast radius and should
  be planned separately if WordPress.org public-release requirements make it
  necessary.

Future implementation should state whether it is single-site only or whether it
handles multisite/network activation. This checkpoint does not implement either.

## Public Release Cleanup Policy Table

| Area | Decision | Classification | Notes |
|---|---|---|---|
| Cleanup mechanism | Prefer root `uninstall.php` | Needs implementation | Keep cleanup explicit and independent from runtime bootstrap. |
| `register_uninstall_hook` | Fallback candidate, not preferred | Deferred | Use only if later implementation planning finds root uninstall unsuitable. |
| Main settings option | Delete whole `analytics_report_ai_settings` option | Needs implementation | First-choice policy for plugin-owned settings and nested fallback credentials. |
| Dedicated OAuth token option | Delete `analytics_report_ai_oauth_tokens` option | Needs implementation | Strong cleanup target. No value inspection. |
| Credential-category-only cleanup | Do not use as first-choice policy | Deferred | Reconsider only if preserving non-secret settings becomes a requirement. |
| Manual Google Access Token fallback | Covered by whole-option cleanup | Needs implementation | Public-release fallback posture remains a separate Hold item. |
| OpenAI API key | Covered by whole-option cleanup | Needs implementation | OpenAI API key storage posture still needs dedicated checkpoint. |
| OAuth client Settings fallback | Covered by whole-option cleanup | Needs implementation | Constants remain outside cleanup. |
| Constants / `wp-config.php` style config | Do not clean | Not applicable | Not plugin-owned option storage. |
| Payload transient | Hold pending narrow scope decision | Hold | Dynamic cleanup may be deferred from minimum implementation. |
| OAuth state transient | Hold pending narrow scope decision | Hold | Usually short-lived; safe cleanup needs separate design. |
| Generated report body | Do not clean | Not applicable | Plugin does not persist generated report text. |
| Provider-side revoke | Do not include in uninstall cleanup | Deferred / Separate track | No external calls during uninstall. |
| Multisite cleanup | Hold unless explicitly scoped | Hold | Needs separate boundary if required. |
| WordPress.org release readiness | Not ready | Hold | Cleanup implementation and verification still pending. |

## Implementation Readiness Assessment

Step 211 is enough to move to a narrow implementation plan, but not directly to
a broad cleanup implementation.

Ready for implementation planning:

- root `uninstall.php` preferred mechanism,
- delete the main plugin settings option,
- delete the dedicated OAuth token option,
- avoid credential-category-only cleanup,
- exclude constants and provider-side data,
- exclude generated report body cleanup,
- keep external calls out of uninstall.

Still needing a decision before or during implementation planning:

- whether dynamic payload transient cleanup is included,
- whether dynamic OAuth state transient cleanup is included,
- whether multisite cleanup is included or explicitly deferred,
- exact allowlist of plugin-owned keys,
- verification commands that do not inspect option values.

Minimum public-release cleanup implementation candidate:

```text
Delete plugin-owned options only:
- analytics_report_ai_settings
- analytics_report_ai_oauth_tokens

Do not inspect values.
Do not call external services.
Do not perform provider-side revoke.
Hold dynamic transient cleanup unless a narrow safe scope is accepted.
```

## Recommended Next Step

Recommended next step:

```text
Step 212: Uninstall cleanup narrow implementation plan
```

Step 212 should remain docs-only / planning-only and define:

- root `uninstall.php` implementation shape,
- exact plugin-owned option key allowlist,
- single-site versus multisite scope,
- whether transient cleanup is included or explicitly deferred,
- verification commands that do not reveal option values,
- forbidden evidence boundaries for implementation and review.

Only after Step 212 should a production implementation step create uninstall
cleanup code.

## Result Classification

```text
Uninstall cleanup policy decision checkpoint: Completed
Uninstall cleanup mechanism: root uninstall.php preferred / Needs implementation
Main settings option cleanup: Delete whole plugin-owned option / Needs implementation
Dedicated OAuth token option cleanup: Delete plugin-owned option / Needs implementation
Credential-category-only cleanup: Deferred unless later required
Runtime transient cleanup: Hold / Needs decision or limited implementation scope
Constants / wp-config.php style configuration cleanup: Not applicable
Provider-side revoke on uninstall: Deferred / Separate track
Generated report body cleanup: Not applicable
Multisite uninstall cleanup: Hold unless explicitly scoped
WordPress.org release readiness: Hold
```
