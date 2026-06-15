# Step 124: Google OAuth Client Configuration Constants / Status Boundary Implementation Results

## Step Summary

Step 124 implements the narrow client configuration constants / status
boundary selected by Step 123.

This step adds status-level detection for Google OAuth client configuration
constants. It does not define actual credential values, display client ID or
client secret values, save client secret values in plugin settings, construct
authorization URLs, redirect to Google, exchange tokens, store tokens, refresh
tokens, revoke access, add reconnect UI, change GA4 client behavior, change
OpenAI storage, change Settings save logic, create `uninstall.php`, rebuild
packages, rerun Plugin Check, or call external APIs.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step123-google-oauth-client-configuration-token-exchange-boundary-plan.md`
- `docs/maturation/step122-google-oauth-authorization-redirect-token-exchange-boundary-decision-checkpoint.md`
- `docs/maturation/step121-google-oauth-state-protection-callback-validation-boundary-implementation-results.md`
- `docs/maturation/step120-google-oauth-skeleton-admin-action-boundary-implementation-results.md`
- `docs/maturation/step119-credential-implementation-readiness-checkpoint.md`
- `docs/maturation/step116-google-oauth-token-lifecycle-implementation-plan.md`

## Changed Files

- `includes/class-settings.php`
- `docs/maturation/step124-google-oauth-client-configuration-constants-status-boundary-implementation-results.md`

No new PHP include file was added. `analytics-report-ai.php` was not changed.

## Implementation Scope

Implemented:

- Google OAuth client configuration constant names in the Settings screen
  class.
- A private status helper that checks whether each constant is defined and
  non-empty.
- Status-level categories for Google OAuth client configuration.
- Settings UI display of the status category only.
- Settings UI wording that explains client secret is expected outside plugin
  settings by constant.

Not implemented:

- Defining actual client ID or client secret values.
- Saving client ID or client secret values to plugin settings.
- Displaying client ID or client secret values.
- Authorization URL construction.
- Google authorization redirect.
- Token endpoint request.
- Revoke endpoint request.
- Token exchange.
- Token storage.
- Refresh.
- Revoke.
- Reconnect UI.
- GA4 client integration.
- OpenAI storage changes.
- Settings save logic changes.
- Manual Google Access Token removal or behavior changes.
- Uninstall cleanup.
- Package rebuild.
- Plugin Check rerun.
- External API calls.

## Constant Names / Status Categories

Expected constant names:

```text
ANALYTICS_REPORT_AI_GOOGLE_OAUTH_CLIENT_ID
ANALYTICS_REPORT_AI_GOOGLE_OAUTH_CLIENT_SECRET
```

The implementation checks whether each constant is defined and non-empty. It
does not return the raw constant value to the UI layer.

Status categories:

| Category | Meaning |
|---|---|
| `google_oauth_client_not_configured` | Client ID and client secret constants are missing or empty. |
| `google_oauth_client_id_configured_secret_missing` | Client ID constant is present and non-empty; client secret constant is missing or empty. |
| `google_oauth_client_id_missing_secret_configured` | Client secret constant is present and non-empty; client ID constant is missing or empty. |
| `google_oauth_client_configured` | Client ID and client secret constants are both present and non-empty. |

These categories are status-level only. They do not include constant values,
client secret fragments, client ID values, credentials, tokens, authorization
codes, raw state values, request bodies, raw responses, analytics values, or
generated report bodies.

## Client Secret Constant-only Boundary

The client secret boundary follows the Step 123 planning direction:

- Client secret is expected to be configured outside plugin settings.
- Client secret is detected only by constant presence.
- Client secret value is not displayed.
- Client secret value is not saved to plugin settings.
- Client secret value is not passed to the UI layer.
- Client secret value is not recorded in docs, logs, debug output, or support
  evidence.

This step does not decide final public-release acceptance for the configuration
model. It only implements the constant/status boundary needed for future
authorization and token exchange planning.

## Settings UI Status Boundary

The Settings screen Google OAuth placeholder now shows:

- Google OAuth is planned and incomplete.
- Client configuration status as a category.
- Client ID / client secret values are not displayed.
- Client secret is expected outside plugin settings by constant.
- The placeholder does not contact Google.
- The placeholder does not exchange authorization codes.
- The placeholder does not save tokens.
- The temporary manual Google Access Token field remains available for
  controlled developer verification only.

No client secret input field was added. No client ID value field was added. No
Settings save logic was changed.

## Helper Boundary

The Settings screen class includes private helpers that:

- check constant presence without returning values,
- return only a status category,
- keep client secret raw value out of the UI layer,
- perform no external API call,
- perform no authorization URL construction,
- perform no token exchange,
- perform no token storage.

The helpers are intentionally private in this slice. A future OAuth-focused
include file may be introduced later if the authorization URL or token exchange
boundaries need shared behavior.

## Explicitly Not Implemented

- Google redirect.
- Authorization URL construction.
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

## Support / Debug Evidence Boundary

This step did not record:

- credentials,
- API keys,
- access tokens,
- Authorization headers,
- client secrets,
- client ID values,
- plugin option values,
- authorization codes,
- raw state values,
- raw provider errors,
- full authorization URLs,
- token endpoint request bodies,
- raw token responses,
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

Future support/debug evidence should remain limited to status-level labels,
safe result categories, redacted UI state, and error categories.

## Verification Results

Executed checks:

- `git diff --check`
- `git diff --name-only`
- `git diff --stat`
- `php -l analytics-report-ai.php`
- `php -l includes/class-admin.php`
- `php -l includes/class-settings.php`
- `find includes -name '*.php' -print0 | xargs -0 -n1 php -l`
- source review for external API calls, Google authorization redirect,
  authorization URL construction, Google token endpoint calls, Google revoke
  endpoint calls, GA4 Fetch, OpenAI Generate, token exchange, token storage,
  client secret output, credential output, option value output, raw
  `state` / `code` / `error` output, raw request/response logging,
  screenshots, Plugin Check, and package rebuilds
- `git status --short --untracked-files=all`

Observed result:

- PHP syntax checks passed for the checked files.
- Diff whitespace check passed.
- Source review found no external API call, Google authorization redirect,
  authorization URL construction, Google token endpoint call, Google revoke
  endpoint call, GA4 Fetch, OpenAI Generate, token exchange, token storage,
  client secret output, credential value output, option value output, raw
  callback value output, raw request/response logging, screenshot capture,
  Plugin Check, or package rebuild.

## Next Step Notes

Recommended next step:

```text
Step 125: Google OAuth redirect URI generation and Settings display/copy wording
```

Step 125 should still avoid Google authorization redirect execution,
authorization URL construction, token endpoint requests, token exchange, token
storage, refresh, revoke, reconnect UI, GA4 client behavior changes, OpenAI
storage changes, Settings save logic changes, uninstall cleanup, Plugin Check,
package rebuilds, and external API calls unless a later accepted plan
explicitly widens the boundary.
