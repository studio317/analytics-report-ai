# Step 219: Manual Google Access Token Fallback Retirement Narrow Production Implementation Results

## Step Purpose

Step 219 implements the narrow production changes planned in Step 218 to retire
the manual Google Access Token fallback from normal public-release behavior.

The implementation is limited to Settings UI, Settings save handling, the GA4
credential source resolver, and directly related Report Builder wording.

WordPress.org release readiness remains `Hold`.

## Implementation Summary

Completed implementation categories:

- removed the normal Settings UI field for `Manual Google Access Token`,
- removed the normal Settings UI status row for the manual fallback,
- removed the normal Settings UI delete checkbox for `clear_google_access_token`,
- stopped normal Settings save from accepting `google_access_token` as a GA4
  credential source,
- stopped normal Settings save from accepting nested
  `google_tokens['access_token']` input as a GA4 credential source,
- added value-free cleanup of the retired manual fallback category during
  Settings save,
- removed the manual fallback branch from
  `analytics_report_ai_resolve_google_ga4_credential_source()`,
- stopped returning `credential_source_manual_token` and
  `manual_token_fallback_used` from normal resolver behavior,
- updated Report Builder credential source guidance so OAuth is the normal GA4
  credential source.

No credential values, token values, option values, OAuth client values,
serialized option values, database rows, request bodies, raw responses, AI
payload JSON, generated report bodies, screenshots, browser Network evidence,
GA4 Property ID values, hostname/domain values, or analytics values were
displayed or recorded.

## Changed Files

| File | Change category |
|---|---|
| `includes/class-settings.php` | Settings UI retirement, Settings save value-free cleanup, related Settings wording. |
| `includes/functions-utils.php` | Settings normalization cleanup and resolver manual fallback retirement. |
| `includes/class-report-builder.php` | Report Builder credential source and missing credential wording updates. |
| `docs/maturation/step219-manual-google-access-token-fallback-retirement-narrow-production-implementation-results.md` | Step 219 implementation results. |

## Settings UI Retirement

Implemented:

- `Manual Google Access Token Status` is no longer rendered in the normal
  Settings UI.
- `Manual Google Access Token` is no longer rendered in the normal Settings UI.
- `clear_google_access_token` is no longer rendered in the normal Settings UI.
- Settings wording now presents Google OAuth as the normal GA4 credential
  source.
- Credential and token values remain hidden.

The normal Settings UI no longer invites manual access token entry as a public
release path.

## Settings Save Value-free Cleanup

Implemented:

- normal Settings save no longer reads `google_access_token` as a GA4
  credential source,
- normal Settings save no longer reads nested `google_tokens['access_token']`
  as a GA4 credential source,
- the retired manual fallback category is reset to an empty local category
  without displaying, logging, or recording values,
- `google_auth_status` is normalized to `not_connected` for the retired manual
  fallback category,
- OpenAI API key handling is unchanged,
- OAuth client Settings fallback handling is unchanged,
- dedicated OAuth token option storage is unchanged.

This is classified as:

```text
Saved manual fallback value handling: Settings save value-free cleanup implemented
```

## Credential Resolver Retirement

Implemented in `analytics_report_ai_resolve_google_ga4_credential_source()`:

- OAuth usable state remains the normal GA4 credential source.
- OAuth refresh-needed / reconnect-needed state returns status/category-level
  guidance without falling back to a manual token.
- OAuth error / missing credential states remain status/category-level results.
- Settings-stored manual fallback access token is not returned as a GA4 runtime
  credential.
- Normal resolver behavior no longer returns `credential_source_manual_token`.
- Normal resolver behavior no longer returns `manual_token_fallback_used`.

GA4 client request construction was not changed.

## Report Builder Wording Updates

Implemented:

- credential source guidance now describes Google OAuth as the normal GA4
  credential source,
- refresh-needed and OAuth error guidance no longer points users to a temporary
  manual Google Access Token fallback,
- missing credential guidance points users to Google OAuth in Settings,
- wording remains status/category-level and value-hidden.

## Explicit Unchanged Boundaries

Unchanged in Step 219:

- GA4 client request construction,
- OpenAI client behavior,
- OAuth token endpoint behavior,
- refresh request behavior,
- provider-side revoke behavior,
- local-only disconnect boundary,
- uninstall cleanup boundary,
- `uninstall.php`,
- `readme.txt`,
- tools and build scripts,
- JavaScript,
- CSS,
- generated report persistence,
- OpenAI API key handling,
- OAuth client Settings fallback handling,
- dedicated OAuth token option storage.

## Forbidden Evidence Boundary

Step 219 did not display, inspect, or record:

- credential values,
- API keys,
- access token values,
- refresh token values,
- OAuth client ID values,
- OAuth client secret values,
- Authorization headers,
- plugin option values,
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

## Commands Executed

Safe verification commands executed after implementation:

```bash
php -l includes/functions-utils.php
php -l includes/class-settings.php
php -l includes/class-report-builder.php
find includes -name '*.php' -print0 | xargs -0 -n1 php -l
rg -n "google_access_token|credential_source_manual_token|manual_token_fallback_used|Manual Google Access Token|clear_google_access_token|google_tokens\\['access_token'\\]" includes
git diff --check
git diff --name-only
git diff --name-only -- uninstall.php readme.txt tools assets includes/class-ga4-client.php includes/class-openai-client.php
git status --short --untracked-files=all
```

Command result categories:

- PHP syntax checks: Pass.
- `git diff --check`: Pass.
- requested source search: one unchanged GA4 client hook/category name remains
  in `includes/class-ga4-client.php`; no credential value or option value was
  displayed or recorded.
- changed production files are limited to the intended PHP files.
- `uninstall.php`, `readme.txt`, tools, assets,
  `includes/class-ga4-client.php`, and `includes/class-openai-client.php`:
  unchanged.

The source search is used only at file/symbol/category level. It does not
display or record credential values.

## Acceptance Criteria

| Criterion | Result |
|---|---|
| Manual Google Access Token normal Settings field removed or hidden | Pass |
| Manual fallback status row removed or hidden | Pass |
| Manual fallback delete checkbox removed or hidden | Pass |
| Settings save no longer accepts `google_access_token` as a credential source | Pass |
| Settings save performs value-free cleanup of retired manual fallback category | Pass |
| Resolver no longer returns manual fallback as normal GA4 credential source | Pass |
| `credential_source_manual_token` removed from normal resolver behavior | Pass |
| `manual_token_fallback_used` removed from normal resolver behavior | Pass |
| Report Builder no longer guides users to manual fallback as normal path | Pass |
| GA4 client / OpenAI client / uninstall / readme / tools / JS / CSS unchanged | Pass |

## Result Classification

```text
Manual Google Access Token fallback retirement narrow production implementation: Completed
Manual fallback Settings UI retirement: Implemented
Manual fallback resolver retirement: Implemented
Saved manual fallback value handling: Settings save value-free cleanup implemented
OAuth credential source: Preferred / normal GA4 credential source
GA4 client request construction: Unchanged
Local-only disconnect boundary: Unchanged
Uninstall cleanup boundary: Unchanged
Readme/privacy wording: Not changed in Step 219
WordPress.org release readiness: Hold
```

## Recommended Next Step

Recommended next step:

```text
Step 220: Manual Google Access Token fallback retirement source-level verification
```

Step 220 should verify the implementation at source level, including Settings
UI removal, value-free Settings save cleanup, resolver behavior, unchanged GA4
client request construction, unchanged local-only disconnect boundary, and
unchanged uninstall cleanup boundary.
