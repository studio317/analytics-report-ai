# Step 209: Credential Storage Public-release Posture Checkpoint

## Step Purpose

Step 209 is a docs-only and planning-only checkpoint for Analytics Report AI
credential storage posture before any public-release readiness decision.

The purpose is to inventory the current credential-related storage categories,
fallback paths, constant-based configuration paths, Settings UI behavior,
support/debug boundaries, and uninstall cleanup relationship. This checkpoint
classifies each area as `Accept`, `Hold`, `Needs decision`, or
`Needs implementation` for WordPress.org public-release readiness.

This step does not implement credential storage changes. It records only
source-level, symbol-level, docs-level, and status/category-level evidence.

WordPress.org release remains `Hold`.

## Scope

In scope:

- Google OAuth token storage posture.
- Temporary manual Google Access Token fallback posture.
- OpenAI API key storage posture.
- OAuth client configuration posture.
- Constants / `wp-config.php` style configuration posture.
- Settings fallback storage posture.
- Credential non-redisplay posture.
- Local-only disconnect versus uninstall cleanup.
- Support/debug evidence boundary.
- Public-release decision table.
- Recommended next maturation track.

Out of scope:

- Production implementation,
- database inspection,
- option value inspection,
- browser smoke,
- external API calls,
- release-readiness approval.

## Explicit Non-goals

Step 209 does not:

- change production code,
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
- inspect option values,
- inspect token values,
- inspect credential values,
- inspect OAuth client values,
- inspect request bodies,
- inspect raw responses,
- inspect AI payload JSON,
- inspect generated report bodies.

## Referenced Prior Steps

- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`
- `docs/maturation/step204-oauth-token-lifecycle-narrow-production-implementation-results.md`
- `docs/maturation/step205-oauth-token-lifecycle-source-level-verification-results.md`
- `docs/maturation/step207-oauth-token-lifecycle-controlled-human-admin-smoke-results.md`
- `docs/maturation/step15-credential-storage-policy.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`
- `docs/maturation/step111-openai-api-key-storage-posture-decision-checkpoint.md`
- `docs/maturation/step118-uninstall-credential-cleanup-implementation-plan.md`

## Current Credential Storage Inventory

| Area | Source-level reference | Storage category | Current posture |
|---|---|---|---|
| Main plugin settings option | `ANALYTICS_REPORT_AI_OPTION_NAME` / `analytics_report_ai_settings` | WordPress option for plugin settings | Holds non-secret settings and current Settings fallback credential categories. Public-release storage posture remains Hold. |
| Dedicated Google OAuth token option | `ANALYTICS_REPORT_AI_GOOGLE_OAUTH_TOKEN_OPTION_NAME` / `analytics_report_ai_oauth_tokens` | Dedicated WordPress option for OAuth token material | Non-autoloaded storage helper exists; lifecycle UI/local disconnect matured within MVP. Public-release storage posture remains Hold. |
| Default settings option creation | `analytics_report_ai_maybe_add_default_settings_option()` | New-install default option creation | New installs use non-autoloaded default settings option. Existing credential posture remains separate. |
| OAuth token storage helper | `analytics_report_ai_store_google_oauth_tokens()` | Dedicated OAuth token option storage | Stores token material for runtime use and does not display values. Needs broader public-release storage decision. |
| OAuth token local delete helper | `analytics_report_ai_delete_google_oauth_tokens()` | Local OAuth token deletion helper | Matured within MVP as local disconnect. Not uninstall cleanup and not provider-side revoke. |
| Manual Google Access Token fallback | `google_tokens['access_token']` within `analytics_report_ai_settings` | Temporary Settings fallback credential | Developer-verification fallback. Public-release posture remains Hold / Needs decision. |
| OpenAI API key | `openai_api_key` within `analytics_report_ai_settings` | Settings-stored API key | Values hidden and delete control exists. Public-release posture remains Hold / Needs dedicated checkpoint. |
| OAuth client Settings fallback | `google_oauth_client['client_id']` and `google_oauth_client['client_secret']` within `analytics_report_ai_settings` | Settings fallback for OAuth client configuration | Constants are preferred; Settings fallback values are hidden. Public-release posture remains Needs decision. |
| OAuth client constants | `ANALYTICS_REPORT_AI_GOOGLE_OAUTH_CLIENT_ID`, `ANALYTICS_REPORT_AI_GOOGLE_OAUTH_CLIENT_SECRET` | Constant-based configuration | Preferred source category for OAuth client config. Public-release posture is promising but still needs documented deployment guidance. |
| Credential non-redisplay UI | `includes/class-settings.php` password fields with empty `value` attributes and saved/not-saved placeholders | UI value-hidden posture | Accept within MVP; remains required for public release. |
| Support/debug boundary | Settings and Report Builder status/category wording | Evidence policy | Accept within MVP; must remain enforced in support docs and future QA. |
| Uninstall cleanup | No root `uninstall.php` observed during source-level inventory | Cleanup boundary | Hold / Needs implementation. Local disconnect is not uninstall cleanup. |

This inventory records file, symbol, option key, and category names only. It
does not record option values, credential values, token values, OAuth client
values, request bodies, raw responses, analytics values, screenshots, or browser
Network evidence.

## Google OAuth Token Storage Posture

Current state:

- OAuth token material is stored in a dedicated option identified by
  `ANALYTICS_REPORT_AI_GOOGLE_OAUTH_TOKEN_OPTION_NAME`.
- `analytics_report_ai_store_google_oauth_tokens()` uses non-autoloaded option
  creation/update behavior for the dedicated OAuth token option.
- `analytics_report_ai_get_google_oauth_token_lifecycle_categories()` exposes
  only status/category labels for admin UI and support/debug evidence.
- `analytics_report_ai_delete_google_oauth_tokens()` provides local-only token
  deletion and does not contact Google.

MVP boundary:

```text
Google OAuth token lifecycle UI/status boundary: Accept within current MVP boundary
Local-only OAuth disconnect boundary: Accept within current MVP boundary
Google OAuth token storage public-release posture: Hold
```

Public-release concerns:

- token storage remains in WordPress options,
- refresh request execution is deferred,
- provider-side revoke is deferred,
- uninstall cleanup is not implemented,
- broader storage posture is not finalized.

## Manual Google Access Token Fallback Posture

Current state:

- The temporary manual Google Access Token fallback is stored under the main
  plugin settings option as a Settings fallback credential category.
- Settings UI uses a password field with an empty `value` attribute and
  saved/not-saved status wording.
- Empty input keeps existing saved value.
- Delete checkbox exists for the saved fallback category.
- OAuth is described as the preferred GA4 credential source.

Checkpoint classification:

```text
Manual Google Access Token fallback public-release posture: Hold / Needs decision
```

Public-release decision needed:

- retire the fallback,
- restrict it to developer-only / diagnostic mode,
- keep it with explicit public-release limitations,
- or replace it with a more complete OAuth-only credential strategy.

This decision should happen before WordPress.org release readiness is claimed.

## OpenAI API Key Storage Posture

Current state:

- The OpenAI API key is stored under the main plugin settings option.
- Settings UI uses a password field with an empty `value` attribute and
  saved/not-saved status wording.
- Empty input keeps existing saved value.
- Delete checkbox exists for the saved OpenAI API key category.
- Support/debug wording tells users not to share API keys or option values.

Checkpoint classification:

```text
OpenAI API key storage public-release posture: Hold / Needs dedicated checkpoint
```

Public-release decision needed:

- whether Settings-stored OpenAI API key is acceptable,
- whether constant-based configuration should be supported or preferred,
- whether storage separation is needed,
- whether uninstall cleanup is required,
- whether additional admin guidance is needed for restricted keys.

## OAuth Client Configuration Posture

Current state:

- OAuth client configuration can be resolved from constants or Settings
  fallback.
- Constants are preferred when complete.
- Settings fallback is used only when constants are missing and Settings
  fallback is complete.
- Partial or conflicting source categories are classified using safe
  status/category labels.
- Values are returned only for request-local OAuth runtime use and are not
  displayed in admin UI or support/debug evidence.

Checkpoint classification:

```text
OAuth client configuration posture: Partially Accept within MVP / Needs public-release deployment decision
```

Public-release decision needed:

- whether Settings fallback remains available,
- whether constants become the preferred or required public-release posture,
- whether Settings fallback should be developer-only,
- how support/docs should explain configuration without exposing values.

## Constants / Settings Fallback Posture

| Source | Current behavior | MVP classification | Public-release posture |
|---|---|---|---|
| Constants | Preferred complete source for OAuth client configuration. | Accept within MVP | Needs deployment guidance before public release. |
| Settings fallback OAuth client config | Stored under main plugin settings and hidden after save. | Accept for controlled MVP fallback | Needs decision before public release. |
| Main Settings option | Stores fallback credential categories and non-secret settings. | Accept within MVP with value-hidden UI | Broader storage posture Hold. |
| Dedicated OAuth token option | Stores OAuth token material separately from main settings. | Accept within MVP lifecycle boundary | Public-release storage posture Hold until cleanup/revoke/refresh/storage decisions mature. |

The current posture favors constants where available but still permits Settings
fallback. Public-release readiness needs a clear rule for what remains exposed
in the admin UI and what is documented as preferred.

## Settings UI Storage Posture

Current Settings UI posture:

- credential input values are not redisplayed,
- saved state is indicated with status/placeholder text,
- empty input preserves existing saved values,
- delete controls exist for Settings fallback credential categories,
- local OAuth token disconnect is separate from Settings fallback deletion,
- support/debug wording asks for status/category labels only.

MVP classification:

```text
Settings UI value-hidden posture: Accept within current MVP boundary
Settings storage public-release posture: Hold
```

The UI non-redisplay posture is sound for MVP, but it does not resolve whether
the underlying WordPress option storage model is acceptable for public release.

## Credential Non-redisplay Posture

Credential non-redisplay is one of the strongest matured parts of the current
credential posture.

Verified categories:

- manual Google Access Token fallback value hidden,
- OpenAI API key value hidden,
- OAuth client Settings fallback values hidden,
- OAuth token values not displayed,
- support/debug evidence limited to status/category labels.

Checkpoint classification:

```text
Credential non-redisplay posture: Accept within current MVP boundary
Credential non-redisplay posture: Required baseline for public release
```

This is necessary but not sufficient for release readiness.

## Local Disconnect Versus Uninstall Cleanup

Local-only disconnect and uninstall cleanup are different boundaries.

| Boundary | Current implementation | Scope | Public-release classification |
|---|---|---|---|
| Local-only OAuth disconnect | Implemented through `analytics_report_ai_delete_google_oauth_tokens()` and admin-post disconnect action. | Deletes local OAuth token option only. Does not revoke provider access and does not delete Settings fallback credentials. | Matured within current MVP boundary. |
| Settings fallback delete controls | Implemented for Settings fallback credential categories. | Deletes selected Settings fallback categories when explicitly saved by admin. | Accept within MVP; public-release storage posture still Hold. |
| Uninstall cleanup | No root `uninstall.php` observed in this source-level inventory. | Would remove plugin-owned credential storage when the plugin is uninstalled, according to a dedicated uninstall policy. | Hold / Needs implementation. |

Why uninstall cleanup is not matured:

- local disconnect is user-triggered and OAuth-token-specific,
- Settings delete controls are user-triggered and category-specific,
- uninstall cleanup must define what plugin-owned options are removed on plugin
  uninstall,
- uninstall cleanup must avoid removing unrelated site data,
- uninstall cleanup must be reviewed against WordPress.org expectations,
- uninstall cleanup must be verified without recording option values.

## Support / Debug Evidence Boundary Relationship

Current support/debug boundary:

- use visible status/category labels,
- do not request credentials,
- do not request API keys,
- do not request tokens,
- do not request OAuth client values,
- do not request option values,
- do not request serialized option values,
- do not request request bodies,
- do not request raw responses,
- do not request screenshots or Network evidence,
- do not request analytics values, AI payload JSON, or generated report bodies.

Checkpoint classification:

```text
Support/debug evidence boundary: Accept within current MVP boundary
Support/debug evidence boundary: Must be preserved in all future credential storage tracks
```

## Public Release Decision Table

| Item | Current MVP classification | Public-release classification | Required before release readiness |
|---|---|---|---|
| OAuth token lifecycle status/category UI boundary | Accept / Matured within MVP | Accept as UI/status baseline | Preserve while resolving storage, refresh, revoke, and cleanup tracks. |
| Local-only OAuth disconnect boundary | Accept / Matured within MVP | Partial accept | Keep local-only wording; decide whether provider-side revoke is required. |
| OAuth token storage option | Accept within MVP | Hold | Decide storage posture, cleanup, refresh/revoke relationship, and public documentation. |
| Refresh request execution | Deferred | Hold | Separate plan, implementation, and controlled external QA if adopted. |
| Provider-side revoke | Deferred | Hold | Separate plan, implementation, and controlled external QA if adopted. |
| Manual Google Access Token fallback | MVP developer-verification fallback | Hold / Needs decision | Retire, restrict, or explicitly keep with public-release rationale. |
| OpenAI API key storage | Value-hidden Settings storage | Hold / Needs dedicated checkpoint | Decide Settings storage versus constants/storage separation/other posture. |
| OAuth client constants | Preferred source | Needs decision | Decide public deployment guidance and whether constants should be required/preferred. |
| OAuth client Settings fallback | Controlled fallback | Needs decision | Decide whether this remains available for public release. |
| Credential non-redisplay UI | Accept within MVP | Accept baseline | Preserve and verify in future changes. |
| Settings delete controls | Accept within MVP | Partial accept | Preserve; align with uninstall cleanup and storage posture. |
| Uninstall cleanup | Not matured | Needs implementation | Inventory, plan, implement, and verify plugin-owned credential cleanup. |
| WordPress.org release readiness | Hold | Hold | Resolve storage, fallback, uninstall, refresh/revoke, and final release checks. |

## Hold / Needs Decision / Needs Implementation Items

Hold:

- WordPress.org release readiness,
- broader credential storage posture,
- OAuth token storage public-release posture,
- manual Google Access Token fallback public-release posture,
- OpenAI API key storage public-release posture,
- provider-side revoke,
- refresh request execution.

Needs decision:

- whether manual Google Access Token fallback is retired, restricted, or kept,
- whether OpenAI API key remains Settings-stored,
- whether OAuth client Settings fallback remains available,
- whether constants should be preferred or required for OAuth client config,
- whether provider-side revoke is required for public release,
- whether refresh request execution is required for public release.

Needs implementation:

- uninstall cleanup boundary,
- any selected manual fallback retirement/restriction,
- any selected OpenAI API key storage changes,
- any selected OAuth client Settings fallback restriction,
- any selected provider-side revoke implementation,
- any selected refresh request implementation.

Accept within current MVP boundary:

- credential non-redisplay UI,
- status/category support evidence boundary,
- OAuth token lifecycle status/category UI boundary,
- local-only OAuth disconnect boundary,
- constants-preferred OAuth client source behavior,
- Settings fallback visibility labels.

## Recommended Next Step

Recommended next step:

```text
Step 210: Uninstall cleanup boundary inventory
```

Reason:

- Step 209 confirms that local disconnect is not uninstall cleanup.
- Public-release storage posture cannot be closed while plugin-owned credential
  cleanup on uninstall is still unplanned/unimplemented.
- An uninstall cleanup inventory can safely remain docs-only/source-level and
  list plugin-owned options, transient-like data, and credential categories
  without inspecting values.
- After uninstall cleanup is inventoried, the project can decide whether to
  implement cleanup first or return to manual Google Access Token fallback /
  OpenAI API key storage posture decisions.

Alternative later tracks:

- manual Google Access Token fallback retirement plan,
- OpenAI API key storage posture checkpoint,
- OAuth provider-side revoke / refresh future-track planning,
- OAuth client Settings fallback public-release decision.

## Result Classification

```text
Credential storage public-release posture checkpoint completed
OAuth token lifecycle UI/status boundary: Accept / Matured within current MVP boundary
Local-only OAuth disconnect boundary: Accept / Matured within current MVP boundary
Credential storage public-release posture: Hold
Manual Google Access Token fallback public-release posture: Hold / Needs decision
OpenAI API key storage public-release posture: Hold / Needs dedicated checkpoint
Uninstall cleanup: Hold / Needs implementation
WordPress.org release status: Hold
```
