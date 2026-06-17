# Step 188: OAuth Client Configuration Hybrid Source Implementation Plan

## Step Purpose

Step 188 is a docs-only and planning-only implementation plan for the OAuth
client configuration hybrid source model.

This step translates the Step 186 recommended source strategy and the Step 187
source-level inventory into a narrow production implementation plan for later
steps. The planned model is:

```text
Hybrid model with constants preferred and Settings UI fallback, using value-hidden status/category labels only.
```

This step does not change production code, `readme.txt`, tools, build scripts,
JavaScript, CSS, admin behavior, OAuth behavior, credential storage, GA4
behavior, OpenAI behavior, payload handling, transient handling, release
packaging, or generated report persistence.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step187-oauth-client-configuration-source-level-inventory.md`
- `docs/maturation/step186-oauth-client-configuration-source-strategy-implementation-plan.md`
- `docs/maturation/step185-oauth-app-ownership-provider-configuration-decision-checkpoint.md`
- `docs/maturation/step184-oauth-source-inventory-current-boundary-review.md`
- `docs/maturation/step183-oauth-public-release-readiness-implementation-plan.md`
- `docs/maturation/step182-oauth-credential-public-release-strategy-checkpoint.md`
- `docs/maturation/step181-public-release-blocker-prioritization-checkpoint.md`
- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`
- `docs/maturation/step179-support-debug-wording-maturation-checkpoint.md`

## Implementation Scope

Step 188 does not implement code. It plans narrow production changes for Step
189 and later.

Primary implementation candidates:

| File | Planned role | Step 188 action |
|---|---|---|
| `includes/class-settings.php` | Settings UI status labels, value-hidden fallback inputs, save behavior, delete controls, safe wording. | Planning only. |
| `includes/class-admin.php` | OAuth Connect precondition, authorization URL construction dependency, token exchange dependency. | Planning only. |

Possible helper location if implementation needs shared runtime behavior:

| File | Planned role | Step 188 action |
|---|---|---|
| `includes/functions-utils.php` | Shared resolver for active OAuth client source category and request-local client values. | Planning only. |

Out of scope for Step 189 unless separately approved:

- OAuth token refresh,
- OAuth revoke,
- reconnect UI,
- provider-side configuration automation,
- external secret manager,
- encryption,
- release package changes,
- Plugin Check,
- browser admin smoke,
- GA4 Fetch,
- OpenAI Generate.

## Planned Hybrid Behavior

Planned active source behavior:

| Constants status | Settings fallback status | Planned active source category | Planned runtime behavior | Notes |
|---|---|---|---|---|
| complete | missing | `oauth_client_source_category: constants` | Use constants for OAuth Connect, authorization URL construction, and token exchange. | Preferred source. |
| complete | complete | `oauth_client_source_category: constants` | Use constants. Treat Settings fallback as inactive. | May also expose a safe conflict/inactive fallback category. |
| complete | incomplete | `oauth_client_source_category: constants` | Use constants. Report Settings fallback as incomplete/inactive at status level if useful. | Do not combine sources. |
| missing | complete | `oauth_client_source_category: settings` | Use Settings fallback for OAuth Connect, authorization URL construction, and token exchange. | Values remain request-local and hidden. |
| missing | missing | `oauth_client_source_category: missing` | Block OAuth Connect before redirect. | Safe admin notice only. |
| missing | incomplete | `oauth_client_source_category: incomplete` | Block OAuth Connect before redirect. | Do not attempt mixed or partial configuration. |
| incomplete | missing | `oauth_client_source_category: incomplete` | Block OAuth Connect before redirect. | Constants are not complete. |
| incomplete | complete | `oauth_client_source_category: settings` or `oauth_client_source_category: conflict` | Recommended plan: use Settings only if policy accepts complete Settings fallback overriding incomplete constants; otherwise block as conflict. | Step 189 should choose one explicit rule before implementation. |
| incomplete | incomplete | `oauth_client_source_category: incomplete` | Block OAuth Connect before redirect. | Do not combine partial values. |

Recommended Step 189 decision:

- Do not mix client ID from one source with client secret from another source.
- Treat a source as runtime-usable only when that same source is complete.
- Prefer constants when constants are complete.
- Use Settings fallback only when constants are missing and Settings fallback is
  complete, unless Step 189 explicitly decides that complete Settings fallback
  may override incomplete constants.
- Block OAuth Connect when the active source is missing, incomplete, or
  unresolved.

## Planned Source Labels

Allowed display/support labels:

| Label | Meaning |
|---|---|
| `oauth_client_source_category: constants` | OAuth client configuration is active from constants. |
| `oauth_client_source_category: settings` | OAuth client configuration is active from Settings fallback storage. |
| `oauth_client_source_category: missing` | No complete OAuth client configuration source is available. |
| `oauth_client_source_category: incomplete` | A source is partially configured, but no safe complete active source is available. |
| `oauth_client_source_category: conflict` | Multiple source categories exist and precedence or blocking rules apply. |
| `oauth_client_value_hidden_status: hidden` | OAuth client values are not displayed. |
| `oauth_client_settings_fallback_status: saved` | Settings fallback values are saved at category level. |
| `oauth_client_settings_fallback_status: not_saved` | Settings fallback values are not saved at category level. |
| `oauth_client_settings_fallback_status: deleted` | Settings fallback values were deleted at category level. |

These labels must not include OAuth client ID values, OAuth client secret
values, fragments, prefixes, suffixes, option values, provider console details,
URLs, query strings, screenshots, or browser Network evidence.

## Settings UI Plan

Planned Settings UI behavior:

- Add OAuth client ID fallback and OAuth client secret fallback input fields
  only if Step 189 accepts Settings fallback storage.
- Render fallback input `value` attributes as empty strings.
- Use saved/not-saved category labels for Settings fallback values.
- Empty input keeps the existing saved Settings fallback value.
- Entering a new value replaces only the Settings fallback value for that
  field.
- Saved fallback values are never redisplayed in form values, help text,
  notices, docs, logs, support evidence, screenshots, or debug output.
- Delete controls delete only Settings fallback OAuth client values.
- Constants are never deleted or modified from Settings UI.
- Constants-active, settings-active, missing, incomplete, and conflict wording
  should remain status/category-level.
- Support/debug hint should ask only for source category, presence status,
  inactive/conflict category if applicable, and value-hidden status.
- Settings UI should clearly state that Settings fallback storage is separate
  from OAuth token storage and separate from the manual Google Access Token
  fallback.

Planned Settings UI non-goals:

- Do not display OAuth client values.
- Do not validate values against Google from Settings save.
- Do not start OAuth from Settings save.
- Do not exchange tokens from Settings save.
- Do not delete OAuth tokens when deleting OAuth client fallback values.
- Do not revoke provider-side access from Settings fallback delete controls.

## Resolver / Runtime Dependency Plan

Planned resolver responsibilities:

- Return an active source category.
- Return Settings fallback status category.
- Return incomplete/conflict category where needed.
- Return value-hidden status.
- Provide request-local client ID and client secret values internally only when
  a complete active source exists.
- Enforce constants precedence.
- Use Settings fallback only when the selected policy allows it and the fallback
  source is complete.
- Avoid mixing client ID and client secret values from different sources.
- Return missing/incomplete/conflict safe categories when OAuth Connect should
  be blocked.

Planned runtime dependencies:

| Runtime area | Current dependency | Planned dependency |
|---|---|---|
| OAuth Connect precondition | Client ID constant helper. | Active source resolver with safe missing/incomplete/conflict category. |
| Authorization URL construction | Client ID constant helper. | Active source resolver, using request-local client ID internally only. |
| Token exchange | Client ID and client secret constant helpers. | Active source resolver, using request-local complete client pair internally only. |
| Settings status display | Constant presence status helper. | Active source category, Settings fallback status, conflict/incomplete category, and value-hidden status. |

Values used by the resolver must never be included in admin notices, docs, logs,
support evidence, debug output, screenshots, browser Network evidence, or
verification reports.

## Delete Semantics Plan

Planned Settings fallback delete behavior:

- Provide delete controls for Settings fallback OAuth client values only after
  Step 189 accepts Settings fallback storage.
- Deleting Settings fallback client ID removes only the Settings-stored fallback
  client ID category.
- Deleting Settings fallback client secret removes only the Settings-stored
  fallback client secret category.
- If one delete control deletes both fallback values, the UI must say that it
  deletes only Settings fallback OAuth client configuration.
- Delete action does not affect constants.
- Delete action does not disconnect stored OAuth tokens.
- Delete action does not revoke provider-side access.
- Delete action does not refresh tokens.
- Delete action does not change manual Google Access Token fallback storage.
- Delete result notices remain status/category-level and do not display values,
  fragments, option dumps, request bodies, provider responses, or generated
  report content.

Separate future lifecycle controls:

- OAuth token disconnect,
- provider-side revoke,
- refresh,
- reconnect UI,
- uninstall credential cleanup.

## Conflict / Incomplete Handling Plan

Planned handling:

| Case | Planned category | Planned behavior |
|---|---|---|
| constants complete + settings complete | `constants` with optional `conflict` / inactive fallback status | Constants active. Settings fallback inactive. No values displayed. |
| constants incomplete + settings complete | `settings` or `conflict` | Step 189 must choose explicit rule. Recommended conservative rule: avoid mixed-source use and document whether complete Settings fallback can become active when constants are incomplete. |
| constants complete + settings incomplete | `constants` with Settings fallback incomplete status | Constants active. Settings fallback inactive/incomplete. |
| constants incomplete + settings incomplete | `incomplete` | Block OAuth Connect. |
| constants missing + settings incomplete | `incomplete` | Block OAuth Connect. |
| constants missing + settings complete | `settings` | Settings fallback active. |
| constants missing + settings missing | `missing` | Block OAuth Connect. |

Recommended conservative implementation boundary:

- Do not use mixed-source OAuth client pairs.
- Block OAuth Connect when no single complete active source exists.
- Use status/category notices only.
- Keep raw provider errors, request data, option values, and client values out
  of UI and support evidence.

## Safety Boundary

Forbidden evidence remains:

- OAuth client ID values,
- OAuth client secret values,
- credentials,
- API keys,
- access tokens,
- refresh tokens,
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

Allowed evidence remains status/category-level only:

- active OAuth client source category,
- Settings fallback saved/not-saved/deleted category,
- missing/incomplete/conflict category,
- value-hidden status,
- safe admin notice category,
- source-level verification result category.

## Verification Plan

Recommended Step 189 verification after implementation:

- `php -l` for changed PHP files.
- `find includes -name '*.php' -print0 | xargs -0 -n1 php -l`.
- `git diff --check`.
- `git diff --stat`.
- `git diff --name-only`.
- `git status --short --untracked-files=all`.
- Source-level check that OAuth client input values are never rendered back into
  form `value` attributes.
- Source-level check that Settings fallback delete controls affect only
  Settings fallback client configuration.
- Source-level check that constants are not deleted or modified by Settings
  save behavior.
- Source-level check that notices use status/category labels only.
- Source-level check that forbidden evidence is not requested by support/debug
  wording.
- Resolver behavior category checks using local non-secret fixtures or code
  review only.

Not part of Step 189 verification:

- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- GA4 Fetch,
- OpenAI Generate,
- Plugin Check,
- browser admin smoke,
- screenshots,
- browser Network evidence,
- option value output,
- database dump.

## Recommended Implementation Sequence

Recommended sequence:

```text
Step 189: OAuth client configuration hybrid source narrow production implementation
Step 190: OAuth client configuration hybrid source source-level verification
Step 191: OAuth client configuration hybrid source human admin smoke plan
Step 192: OAuth client configuration hybrid source human admin smoke results
Step 193: OAuth client configuration hybrid source maturation checkpoint
```

Step 189 should be a narrow production implementation only. It should not run
OAuth execution, token endpoint communication, Plugin Check, browser admin
smoke, screenshots, or Network evidence collection.

## Acceptance Criteria

| Criterion | Status | Notes |
|---|---|---|
| Docs-only implementation plan file added | Pass | This file records the Step 188 plan. |
| Production code / readme / tools unchanged | Pass | Step 188 does not change production files. |
| Hybrid source implementation scope is clear | Pass | Candidate files, runtime dependencies, and non-goals are documented. |
| Settings UI plan is organized | Pass | Value-hidden fallback inputs, saved/not-saved labels, replacement, and delete controls are planned. |
| Resolver plan is organized | Pass | Active source category, constants precedence, runtime-only values, and safe blocking categories are planned. |
| Delete semantics are organized | Pass | Settings fallback delete is separated from constants, OAuth tokens, revoke, refresh, reconnect, and manual token fallback. |
| Conflict / incomplete handling is organized | Pass | Safe categories and conservative mixed-source boundaries are documented. |
| WordPress.org release remains `Hold` | Pass | OAuth client source implementation and verification are still pending. |
| Forbidden evidence non-recording policy continues | Pass | Only status/category-level evidence is allowed. |
| Next recommended step is explicit | Pass | Step 189 is recommended below. |

## Recommended Next Step

Recommended next step:

```text
Step 189: OAuth client configuration hybrid source narrow production implementation
```

Recommended Step 189 boundary:

- narrow production implementation,
- likely limited to `includes/class-settings.php`, `includes/class-admin.php`,
  and possibly `includes/functions-utils.php`,
- no OAuth execution,
- no token endpoint communication,
- no Plugin Check,
- no browser admin smoke,
- no screenshots,
- no Network evidence collection,
- no option value output,
- no OAuth client value output.

Step 188 result classification:

```text
Implementation plan completed
```
