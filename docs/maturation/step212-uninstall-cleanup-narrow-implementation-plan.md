# Step 212: Uninstall Cleanup Narrow Implementation Plan

## Step Purpose

Step 212 is a docs-only and planning-only implementation plan for a narrow
Analytics Report AI uninstall cleanup step.

Step 210 inventoried the uninstall cleanup boundary. Step 211 selected root
`uninstall.php` as the preferred cleanup mechanism and selected whole
plugin-owned option deletion as the first-choice policy. This step converts
that policy into a narrow production implementation plan for a future Step 213.

This step does not change production code, does not create `uninstall.php`, and
does not add `register_uninstall_hook`.

WordPress.org release readiness remains `Hold`.

## Scope

In scope:

- root `uninstall.php` implementation plan,
- `WP_UNINSTALL_PLUGIN` guard requirement,
- deterministic plugin-owned option allowlist,
- main settings option cleanup plan,
- dedicated OAuth token option cleanup plan,
- value-free `delete_option()` implementation approach,
- credential-category-only cleanup deferral,
- runtime transient cleanup decision,
- multisite boundary decision,
- source-level verification plan,
- safe command verification plan,
- forbidden evidence boundary,
- Step 213 acceptance criteria.

Out of scope:

- production implementation,
- `uninstall.php` creation,
- uninstall hook registration,
- option value inspection,
- database inspection,
- browser smoke,
- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- OAuth flows,
- token refresh,
- provider-side revoke.

## Explicit Non-goals

Step 212 does not:

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

- `docs/maturation/step211-uninstall-cleanup-policy-decision-checkpoint.md`
- `docs/maturation/step210-uninstall-cleanup-boundary-inventory.md`
- `docs/maturation/step209-credential-storage-public-release-posture-checkpoint.md`
- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`
- `docs/maturation/step118-uninstall-credential-cleanup-implementation-plan.md`
- `docs/maturation/step15-credential-storage-policy.md`

## Implementation Policy Inputs

Step 211 established the following policy direction:

```text
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

Step 212 accepts that policy direction and narrows the future production
implementation to deterministic plugin-owned option keys only.

## Proposed Uninstall Mechanism

Proposed mechanism for Step 213:

```text
Implementation mechanism: root uninstall.php
Guard: defined( 'WP_UNINSTALL_PLUGIN' ) check
Cleanup action: delete_option() for deterministic plugin-owned options
```

The future `uninstall.php` should:

- start with the standard WordPress uninstall context guard,
- exit immediately when `WP_UNINSTALL_PLUGIN` is not defined,
- avoid loading the plugin runtime bootstrap,
- use a narrow allowlist of plugin-owned option keys,
- call `delete_option()` for each allowlisted option,
- avoid reading, displaying, logging, or recording option values,
- avoid external requests and provider-side operations.

The future implementation should not use `register_uninstall_hook` unless a
later implementation review finds root `uninstall.php` unsuitable.

## Cleanup Allowlist

Planned cleanup allowlist for Step 213:

| Source-level reference | Option key | Cleanup action | Classification |
|---|---|---|---|
| `ANALYTICS_REPORT_AI_OPTION_NAME` | `analytics_report_ai_settings` | `delete_option()` | Planned |
| `ANALYTICS_REPORT_AI_GOOGLE_OAUTH_TOKEN_OPTION_NAME` | `analytics_report_ai_oauth_tokens` | `delete_option()` | Planned |

Implementation note:

Because root `uninstall.php` should not load the plugin runtime bootstrap,
Step 213 should use a small local allowlist of literal plugin-owned option keys
or an equally narrow value-free structure. It should not require reading option
values or loading classes/functions from the main plugin.

Planned cleanup shape:

```text
delete_option( 'analytics_report_ai_settings' )
delete_option( 'analytics_report_ai_oauth_tokens' )
```

This shape records option key names only. It does not record option values,
credential values, token values, OAuth client values, serialized option values,
or database row contents.

## Explicit Non-cleanup Targets

The Step 213 narrow implementation should not clean or touch:

- constants / `wp-config.php` style configuration,
- Google provider-side authorization state,
- provider-side revoke,
- token endpoint communication,
- refresh request execution,
- OpenAI account-side API key lifecycle,
- GA4 property-side data,
- OpenAI service-side data,
- generated report body storage,
- browser cookies, sessions, or nonces,
- screenshots or browser Network evidence,
- arbitrary WordPress options outside the allowlist,
- arbitrary database rows,
- plugin files,
- build artifacts,
- source repository metadata.

Generated report body cleanup is `Not applicable` because the plugin does not
persist generated report text.

## Runtime Transient Cleanup Decision

Decision for the narrow Step 213 implementation:

```text
Runtime transient cleanup: Deferred / Hold
```

Runtime transient-like data includes:

- payload transient keys created through the payload transient helper,
- OAuth state transient keys created through the admin OAuth state boundary.

Rationale for deferring transient cleanup:

- The relevant transient keys are dynamic and user-scoped.
- Safe enumeration and deletion require a separate design.
- Broad database scans would increase implementation risk.
- Transient values must not be inspected or recorded.
- The first uninstall cleanup implementation should prove the deterministic
  plugin-owned option cleanup path first.

Future transient cleanup can be planned separately if public-release policy
requires it.

## Multisite Boundary Decision

Decision for the narrow Step 213 implementation:

```text
Multisite cleanup: Hold / Separate track
Single-site MVP boundary: Explicitly scoped
```

Rationale:

- Current policy checkpoints are scoped to the single-site MVP boundary.
- Network-wide cleanup can broaden deletion behavior and requires separate
  acceptance criteria.
- No network-option cleanup policy has been finalized.
- A first implementation should avoid multisite assumptions unless a later
  checkpoint explicitly scopes them.

Step 213 should state that it implements single-site cleanup only, unless the
user explicitly widens the scope before implementation.

## Proposed File Changes for Step 213

Planned production file addition:

```text
uninstall.php
```

Planned docs addition:

```text
docs/maturation/step213-uninstall-cleanup-narrow-production-implementation-results.md
```

Files not planned for Step 213:

- `analytics-report-ai.php`,
- `includes/`,
- `assets/`,
- `readme.txt`,
- `tools/`,
- build scripts,
- existing Settings UI files,
- GA4 client,
- OpenAI client.

The future implementation should be limited to root uninstall cleanup and a
results doc unless source review finds a narrow syntax or packaging requirement.

## Source-level Verification Plan

Step 213 source-level verification should confirm:

- root `uninstall.php` exists,
- `WP_UNINSTALL_PLUGIN` guard exists,
- cleanup allowlist contains only deterministic plugin-owned option keys,
- `delete_option()` is called for `analytics_report_ai_settings`,
- `delete_option()` is called for `analytics_report_ai_oauth_tokens`,
- `get_option()` is not used,
- `update_option()` is not used,
- `delete_site_option()` is not used unless multisite scope is explicitly
  opened,
- `delete_transient()` is not used unless runtime transient scope is explicitly
  opened,
- external request helpers are not used,
- provider-side revoke logic is not used,
- token endpoint communication is not used,
- no credential/category values are displayed or logged.

Suggested source-level checks for Step 213:

```bash
test -f uninstall.php && echo root_uninstall_exists || echo root_uninstall_missing
rg -n "WP_UNINSTALL_PLUGIN|delete_option|get_option|update_option|delete_site_option|delete_transient|wp_remote|register_uninstall_hook" uninstall.php
php -l uninstall.php
git diff --check
git diff --name-only
git status --short --untracked-files=all
```

These commands should not inspect or display option values.

## Forbidden Evidence Boundary

Step 213 must not inspect, display, record, or log:

- option values,
- credential values,
- API keys,
- access token values,
- refresh token values,
- OAuth client ID values,
- OAuth client secret values,
- Authorization headers,
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

The future implementation should operate only on option key names.

## Acceptance Criteria for Step 213

Step 213 can be accepted if all of the following are true:

- root `uninstall.php` is added,
- `WP_UNINSTALL_PLUGIN` guard is present,
- cleanup is limited to the allowlisted plugin-owned options,
- `analytics_report_ai_settings` is deleted by option key,
- `analytics_report_ai_oauth_tokens` is deleted by option key,
- option values are not read, displayed, logged, or recorded,
- credential-category-only cleanup is not implemented,
- runtime transient cleanup remains deferred unless explicitly scoped,
- multisite cleanup remains Hold unless explicitly scoped,
- provider-side revoke is not performed,
- refresh requests and token endpoint communication are not performed,
- generated report body cleanup is not attempted,
- production behavior outside uninstall is unchanged,
- `readme.txt`, tools, JS, CSS, GA4 client, OpenAI client, and Settings save
  logic are unchanged,
- verification commands complete without exposing forbidden evidence,
- WordPress.org release readiness remains `Hold`.

## Recommended Next Step

Recommended next step:

```text
Step 213: Uninstall cleanup narrow production implementation
```

Step 213 should create the root `uninstall.php` file with a guarded,
value-free, deterministic plugin-owned option cleanup implementation and add a
results doc. It should not expand into transient cleanup, multisite cleanup,
provider-side revoke, refresh execution, or credential storage redesign.

## Result Classification

```text
Uninstall cleanup implementation plan: Completed
Implementation mechanism: root uninstall.php
Cleanup scope: deterministic plugin-owned options only
Main settings option cleanup: Planned
Dedicated OAuth token option cleanup: Planned
Credential-category-only cleanup: Deferred
Runtime transient cleanup: Deferred / Hold
Multisite cleanup: Hold / Separate track
Provider-side revoke on uninstall: Not included / Separate track
WordPress.org release readiness: Hold
```
