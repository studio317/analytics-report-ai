# Step 125: Google OAuth Redirect URI Generation / Settings Display Copy Wording Results

## Step Summary

Step 125 implements the narrow redirect URI generation and Settings display
boundary selected after Step 124.

This step generates the Google OAuth callback redirect URI as site-specific
setup data and displays it on the Settings screen for future Google OAuth
client setup. It does not generate a Google authorization URL, redirect to
Google, call token or revoke endpoints, exchange tokens, store tokens, refresh
tokens, revoke access, add reconnect UI, change GA4 client behavior, change
OpenAI storage, change Settings save logic, create `uninstall.php`, rebuild
packages, rerun Plugin Check, or call external APIs.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step124-google-oauth-client-configuration-constants-status-boundary-implementation-results.md`
- `docs/maturation/step123-google-oauth-client-configuration-token-exchange-boundary-plan.md`
- `docs/maturation/step122-google-oauth-authorization-redirect-token-exchange-boundary-decision-checkpoint.md`
- `docs/maturation/step121-google-oauth-state-protection-callback-validation-boundary-implementation-results.md`
- `docs/maturation/step120-google-oauth-skeleton-admin-action-boundary-implementation-results.md`

## Changed Files

- `includes/class-settings.php`
- `docs/maturation/step125-google-oauth-redirect-uri-generation-settings-display-copy-wording-results.md`

No new PHP include file was added. `analytics-report-ai.php` and
`includes/class-admin.php` were not changed.

## Implementation Scope

Implemented:

- A private Settings helper for generating the Google OAuth callback redirect
  URI.
- Settings UI display of the generated redirect URI as setup information.
- Readonly input display for manual copy.
- Wording that states the redirect URI is for future Google OAuth client setup.
- Wording that the placeholder does not contact Google, generate an
  authorization URL, exchange authorization codes, save tokens, refresh tokens,
  revoke access, or change GA4 fetch behavior.

Not implemented:

- Google authorization redirect.
- Authorization URL construction.
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

## Redirect URI Generation Boundary

The redirect URI is generated from the local `admin-post.php` callback action.

Generation boundary:

```text
admin-post.php action: analytics_report_ai_google_oauth_callback
```

The generated URI is site-specific setup data. It is not treated as a secret,
but it can include site-specific host information. For that reason, the actual
generated redirect URI value and hostname/domain are not recorded in this
document, command output summaries, or support/debug evidence.

This step does not construct a Google authorization URL. It only generates the
local callback redirect URI needed for future OAuth client setup.

## Settings UI Display / Copy Wording Boundary

The Settings screen Google OAuth planned placeholder now displays the generated
redirect URI in a readonly text input for manual copy.

The UI wording explains:

- Google OAuth is planned and incomplete.
- Client configuration status is still status-level only.
- Client ID and client secret values are not displayed.
- Client secret is expected outside plugin settings by constant.
- Redirect URI is shown only for future Google OAuth client setup.
- The URI can be copied into Google OAuth client configuration when OAuth
  support is completed.
- The placeholder does not contact Google.
- The placeholder does not generate a Google authorization URL.
- The placeholder does not exchange authorization codes.
- The placeholder does not save tokens.
- The temporary manual Google Access Token field remains available for
  controlled developer verification only.

No JavaScript copy-to-clipboard behavior was added. No CSS was changed.

## Actual Redirect URI / Hostname Evidence Boundary

This step intentionally does not record:

- the actual generated redirect URI,
- hostname/domain values,
- screenshots of the Settings screen,
- browser Network data,
- authorization URLs,
- raw OAuth state values,
- authorization codes,
- raw provider errors.

The redirect URI is visible in the local admin UI for setup, but docs and
support/debug evidence should keep it out of recorded artifacts unless a later
redaction policy explicitly allows otherwise.

## Relationship To Client Configuration Status Boundary

Step 124 added status-level detection for Google OAuth client configuration
constants. Step 125 keeps that boundary unchanged and adds redirect URI setup
display next to it.

The client configuration status remains category-only:

- `google_oauth_client_not_configured`
- `google_oauth_client_id_configured_secret_missing`
- `google_oauth_client_id_missing_secret_configured`
- `google_oauth_client_configured`

The Settings UI still does not display client ID or client secret values and
does not add a client secret input field.

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
- actual redirect URI values,
- hostname/domain values,
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
- Actual generated redirect URI and hostname/domain were not recorded.

## Next Step Notes

Recommended next step:

```text
Step 126: Google OAuth authorization URL construction helper plan or implementation boundary
```

Step 126 should still avoid Google authorization redirect execution, token
endpoint requests, token exchange, token storage, refresh, revoke, reconnect
UI, GA4 client behavior changes, OpenAI storage changes, Settings save logic
changes, uninstall cleanup, Plugin Check, package rebuilds, external API calls,
and recording full authorization URLs unless a later accepted plan explicitly
widens the boundary.
