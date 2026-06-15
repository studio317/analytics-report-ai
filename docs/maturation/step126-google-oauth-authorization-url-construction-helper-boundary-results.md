# Step 126: Google OAuth Authorization URL Construction Helper Boundary Results

## Step Summary

Step 126 adds a narrow internal helper boundary for constructing a future
Google OAuth authorization URL.

This step does not execute a Google authorization redirect, display the
authorization URL in the admin UI, call Google token or revoke endpoints,
exchange authorization codes, store tokens, refresh tokens, revoke access, add
reconnect UI, change GA4 client behavior, change OpenAI storage, change
Settings save logic, rebuild packages, rerun Plugin Check, or call external
APIs.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step125-google-oauth-redirect-uri-generation-settings-display-copy-wording-results.md`
- `docs/maturation/step124-google-oauth-client-configuration-constants-status-boundary-implementation-results.md`
- `docs/maturation/step123-google-oauth-client-configuration-token-exchange-boundary-plan.md`
- `docs/maturation/step122-google-oauth-authorization-redirect-token-exchange-boundary-decision-checkpoint.md`
- `docs/maturation/step121-google-oauth-state-protection-callback-validation-boundary-implementation-results.md`
- `docs/maturation/step120-google-oauth-skeleton-admin-action-boundary-implementation-results.md`

## Changed Files

- `includes/class-admin.php`
- `docs/maturation/step126-google-oauth-authorization-url-construction-helper-boundary-results.md`

No new PHP include file was added. `analytics-report-ai.php`,
`includes/class-settings.php`, GA4/OpenAI clients, Report Builder, assets,
`readme.txt`, `.distignore`, tools, and uninstall files were not changed.

## Implementation Scope

Implemented:

- An internal Google OAuth authorization URL construction helper in the admin
  controller.
- A Google OAuth authorization endpoint constant.
- A Google OAuth client ID constant-name boundary.
- A Google Analytics read-only scope constant.
- Parameter assembly for the future authorization request.
- Separation between authorization URL construction and redirect execution.

Not implemented:

- Calling the helper from the current placeholder connect action.
- Redirecting the browser to Google.
- Displaying the authorization URL in Settings or any admin UI.
- Logging, documenting, or debug-outputting the full authorization URL.
- Token endpoint or revoke endpoint behavior.
- Token exchange, token storage, refresh, revoke, or reconnect UI.

## Authorization URL Construction Helper Boundary

The new helper can build a Google OAuth authorization URL from local inputs, but
the current flow does not execute or display the URL.

The helper handles the construction boundary only:

- Google authorization endpoint.
- Client ID read from the configured constant without outputting the value.
- Redirect URI based on the local `admin-post.php` callback action.
- OAuth response type.
- OAuth scope.
- OAuth state value.

The helper intentionally does not:

- call Google,
- redirect the browser,
- exchange an authorization code,
- store tokens,
- refresh tokens,
- revoke access,
- output the URL,
- log the URL.

## Scope Boundary

The helper uses the Google Analytics Data API read-only scope only:

```text
https://www.googleapis.com/auth/analytics.readonly
```

No write scopes are used. No Drive, Gmail, Search Console, or unrelated Google
scopes are included.

## OAuth Parameters Boundary

The helper assembles these OAuth authorization parameters:

- `client_id`
- `redirect_uri`
- `response_type=code`
- `scope`
- `state`

The step does not add `access_type` or `prompt`.

Reason: Step 123 keeps the first public-ready direction on reconnect-only
authorization with refresh token strategy deferred. Adding refresh-token-related
parameters in this step would widen the lifecycle boundary beyond URL
construction.

## Redirect Execution Separation

Authorization URL construction and redirect execution are separated.

The current connect handler still prepares only the local state placeholder and
returns to Settings with a status-level message. It does not call the new helper
for browser navigation and does not redirect to Google.

Step 127 should decide the redirect execution boundary before any browser OAuth
navigation is implemented or tested.

## Evidence Boundary

This document intentionally does not record:

- full authorization URLs,
- actual redirect URI values,
- hostname/domain values,
- client ID values,
- client secret values,
- raw OAuth state values,
- authorization codes,
- raw provider errors,
- credentials,
- access tokens,
- Authorization headers,
- plugin option values,
- request bodies,
- AI payload JSON,
- OpenAI request bodies,
- raw GA4/OpenAI responses,
- generated report bodies,
- screenshots,
- browser Network tab data,
- cookies,
- sessions,
- nonce values.

Support/debug evidence should remain limited to status-level labels, safe result
categories, redacted UI state, and error categories.

## Explicitly Not Implemented

- Google redirect execution.
- Authorization URL UI display.
- Token endpoint call.
- Revoke endpoint call.
- Token exchange.
- Token storage.
- Refresh.
- Revoke.
- Reconnect UI.
- GA4 client integration.
- OpenAI storage.
- Settings save logic changes.
- Credential storage changes.
- Manual Google Access Token removal.
- Uninstall cleanup.
- `uninstall.php`.
- Package rebuild.
- Plugin Check.
- External API calls.
- Browser OAuth execution.
- GA4 Fetch.
- OpenAI Generate.

## Verification Results

Executed checks:

- `git diff --check`
- `git diff --name-only`
- `git diff --stat`
- `php -l analytics-report-ai.php`
- `php -l includes/class-admin.php`
- `php -l includes/class-settings.php`
- source review for external API calls, Google authorization redirect
  execution, authorization URL UI display, full authorization URL logging/docs,
  Google token endpoint calls, Google revoke endpoint calls, GA4 Fetch, OpenAI
  Generate, token exchange, token storage, client secret output, credential
  output, option value output, raw `state` / `code` / `error` output, raw
  request/response logging, screenshots, Plugin Check, and package rebuilds
- `git status --short --untracked-files=all`

Observed result:

- PHP syntax checks passed for the checked files.
- Diff whitespace check passed.
- Source review found no external API call, Google authorization redirect
  execution, authorization URL UI display, full authorization URL logging/docs,
  Google token endpoint call, Google revoke endpoint call, GA4 Fetch, OpenAI
  Generate, token exchange, token storage, client secret output, credential
  value output, option value output, raw callback value output, raw
  request/response logging, screenshot capture, Plugin Check, or package
  rebuild.
- Full authorization URL, actual redirect URI, client ID value, client secret
  value, raw state value, and hostname/domain were not recorded.

## Next Step Notes

Recommended next step:

```text
Step 127: Google OAuth authorization redirect execution boundary decision checkpoint
```

Step 127 should be docs-only. Before redirect execution is implemented, it
should confirm the browser OAuth execution boundary, external Google navigation
boundary, QA boundary, and evidence boundary.
