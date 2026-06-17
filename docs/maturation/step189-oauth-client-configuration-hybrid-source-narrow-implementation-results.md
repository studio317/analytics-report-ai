# Step 189: OAuth Client Configuration Hybrid Source Narrow Implementation Results

## Step Purpose

Step 189 implements a narrow production change for the OAuth client
configuration hybrid source model.

Implemented strategy:

```text
constants preferred + Settings UI fallback
```

This implementation keeps OAuth client values hidden from normal admin display,
uses status/category labels for UI and support/debug evidence, and keeps
WordPress.org release at `Hold`.

## Changed Files

Production files changed:

- `includes/functions-utils.php`
- `includes/class-admin.php`
- `includes/class-settings.php`

Docs added:

- `docs/maturation/step189-oauth-client-configuration-hybrid-source-narrow-implementation-results.md`

File categories not changed:

- `readme.txt`
- tools / build scripts
- JavaScript
- CSS
- GA4 client
- OpenAI client
- report builder flow
- release package files

## Implementation Summary

### Hybrid source resolver

Added a shared OAuth client configuration resolver in `includes/functions-utils.php`.

The resolver:

- reads constant source category internally,
- reads Settings fallback category internally,
- returns safe status/category labels,
- enforces constants precedence when constants are complete,
- allows Settings fallback only when constants are missing and Settings fallback
  is complete,
- blocks OAuth runtime when no single complete source exists,
- does not mix client ID and client secret values from different sources,
- returns runtime values only for request-local OAuth use.

### Constants precedence

Complete constants are the preferred active source.

If constants are complete and Settings fallback values also exist, constants
remain active and Settings fallback is inactive.

### Conservative conflict / incomplete handling

The Step 188 unresolved mixed-source case was resolved conservatively:

```text
constants incomplete + settings complete => conflict / blocked
```

This avoids partial override, mixed-source pairs, and support/debug ambiguity.

OAuth Connect is blocked when the resolver category is `missing`, `incomplete`,
or `conflict`.

### Settings fallback storage

Settings now has value-hidden fallback fields for:

- OAuth client ID fallback,
- OAuth client secret fallback.

Behavior:

- saved values are never redisplayed in input `value` attributes,
- empty input keeps existing Settings fallback values,
- entering a new value replaces only the Settings fallback value for that field,
- saved/not-saved/incomplete fallback status is shown only as a category label,
- OAuth client values, fragments, prefixes, and suffixes are not displayed.

### Delete semantics

Added a delete control for Settings fallback OAuth client configuration.

Delete behavior:

- deletes only Settings fallback OAuth client values,
- does not modify constants,
- does not delete OAuth tokens,
- does not revoke provider-side access,
- does not refresh tokens,
- does not affect reconnect UI,
- does not modify the manual Google Access Token fallback,
- reports only a safe status/category-level settings notice.

### Active source labels

Settings displays status/category labels such as:

- `oauth_client_source_category: constants`
- `oauth_client_source_category: settings`
- `oauth_client_source_category: missing`
- `oauth_client_source_category: incomplete`
- `oauth_client_source_category: conflict`
- `oauth_client_value_hidden_status: hidden`
- `oauth_client_settings_fallback_status: saved`
- `oauth_client_settings_fallback_status: not_saved`
- `oauth_client_settings_fallback_status: incomplete`

### OAuth runtime dependency update

Updated OAuth runtime dependencies to use the shared resolver:

- OAuth Connect precondition,
- authorization URL construction,
- token exchange client pair dependency.

Missing, incomplete, or conflict states stop before redirect or token exchange
with safe status/category notices.

Step 189 did not execute OAuth Connect, Google navigation, token endpoint
communication, GA4 Fetch, or OpenAI Generate.

## Safety Boundary

The implementation and this results record do not display, record, log, or ask
for:

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

Allowed evidence remains status/category-level only.

## Verification

Commands executed:

```bash
php -l includes/class-settings.php
php -l includes/class-admin.php
php -l includes/functions-utils.php
find includes -name '*.php' -print0 | xargs -0 -n1 php -l
git diff --check
git diff --stat
git diff --name-only
git status --short --untracked-files=all
```

Results:

- `php -l includes/class-settings.php`: pass
- `php -l includes/class-admin.php`: pass
- `php -l includes/functions-utils.php`: pass
- `find includes -name '*.php' -print0 | xargs -0 -n1 php -l`: pass for all included PHP files
- `git diff --check`: pass, no output
- source-level grep confirmed OAuth client fallback inputs render with
  `value=""`
- source-level grep confirmed delete wording is limited to Settings fallback
  OAuth client configuration and does not affect constants, OAuth tokens,
  provider access, or manual Google Access Token fallback
- source-level grep found no remaining references to the removed
  constants-only admin helper methods

## Not Executed

Not executed in Step 189:

- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- browser admin smoke,
- screenshots,
- browser Network evidence collection,
- database dump,
- option value output.

## Recommended Next Step

Recommended next step:

```text
Step 190: OAuth client configuration hybrid source source-level verification
```

Step 190 should verify the Step 189 production changes at source level only.
It should not execute OAuth, token endpoint communication, Plugin Check,
browser admin smoke, screenshots, or Network evidence collection.

## Result Classification

```text
Narrow production implementation completed
```
