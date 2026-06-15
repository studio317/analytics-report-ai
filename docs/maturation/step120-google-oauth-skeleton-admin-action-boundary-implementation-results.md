# Step 120: Google OAuth Skeleton / Admin Action Boundary Implementation Results

## Step Summary

Step 120 implements the first narrow credential lifecycle slice selected in
Step 119: a Google OAuth skeleton / admin action boundary.

This step adds local admin action and callback placeholders for a future Google
OAuth connection flow. It does not implement external Google authorization
redirects, token exchange, token storage, refresh, revoke, reconnect UI, GA4
client integration, OpenAI credential storage changes, uninstall cleanup,
package rebuilds, Plugin Check, or external API calls.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step119-credential-implementation-readiness-checkpoint.md`
- `docs/maturation/step116-google-oauth-token-lifecycle-implementation-plan.md`
- `docs/maturation/step115-credential-implementation-roadmap-phase-boundary-plan.md`
- `docs/maturation/step114-integrated-credential-architecture-plan.md`
- `docs/maturation/step110-google-oauth-token-lifecycle-strategy-decision-checkpoint.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`

## Changed Files

- `includes/class-admin.php`
- `includes/class-settings.php`
- `docs/maturation/step120-google-oauth-skeleton-admin-action-boundary-implementation-results.md`

## Implementation Scope

Implemented:

- Local `admin-post.php` action registration for a Google OAuth connect
  placeholder.
- Local `admin-post.php` action registration for a Google OAuth callback
  placeholder.
- Capability checks for both placeholder handlers.
- Nonce check for the connect placeholder action.
- Status-level redirect back to the Settings screen.
- Status-level Settings notice for placeholder results.
- Settings screen placeholder card that clearly says Google OAuth is planned
  and incomplete.

Not implemented:

- External Google authorization URL redirect.
- Token endpoint request.
- Token exchange.
- Token storage.
- Refresh.
- Revoke.
- Reconnect UI.
- GA4 client integration.
- OpenAI API key storage changes.
- Uninstall cleanup.
- Package rebuild.
- Plugin Check rerun.
- External API calls.

## Connect Action / Route Skeleton

The connect boundary is registered through a local admin action:

```text
admin_post_analytics_report_ai_google_oauth_connect
```

The handler:

- requires `manage_options`,
- requires a WordPress nonce,
- does not redirect to Google,
- does not call a Google endpoint,
- does not exchange a code,
- does not save a token,
- redirects back to the Settings screen with a safe status-level placeholder.

The Settings screen includes a local placeholder form. The wording makes clear
that the OAuth flow is planned and not complete, and that the action does not
contact Google or change GA4 behavior.

## Callback Action / Route Skeleton

The callback boundary is registered through a local admin action:

```text
admin_post_analytics_report_ai_google_oauth_callback
```

The handler:

- requires `manage_options`,
- intentionally ignores raw OAuth query values for now,
- does not record `state`, `code`, or `error` values,
- does not exchange authorization codes,
- does not save tokens,
- does not display credential or authorization-code fragments,
- redirects back to the Settings screen with a safe status-level placeholder.

Future steps can add OAuth state validation, provider error mapping, token
exchange, and safe connection-state transitions behind this boundary.

## Capability / Nonce / State Placeholder Boundary

The current boundary is:

- connect placeholder: capability check plus nonce check,
- callback placeholder: capability check plus no raw query display or storage,
- future state validation: intentionally deferred to a later step,
- future token exchange and storage: intentionally deferred to a later step.

This keeps public or insufficiently privileged users from running the
credential-related local admin action, while avoiding token handling in this
slice.

## Settings UI Placeholder

A Settings screen placeholder card was added.

The placeholder:

- says Google OAuth is planned and incomplete,
- says it does not contact Google,
- says it does not exchange authorization codes,
- says it does not save tokens,
- says it does not refresh, revoke, or change GA4 fetch behavior,
- leaves the temporary manual Google Access Token field in place for controlled
  developer verification.

The existing manual Google Access Token field, saved/not-saved state, empty
input preservation, and delete checkbox behavior were not changed.

## Explicitly Not Implemented

- Token exchange.
- Token storage.
- Refresh.
- Revoke.
- Reconnect UI.
- GA4 client integration.
- OpenAI API key storage.
- Settings save logic changes.
- Credential storage changes.
- Manual Google Access Token removal or behavior changes.
- Uninstall cleanup.
- `uninstall.php`.
- Option deletion.
- Release package rebuild.
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
- plugin option values,
- GA4 Property IDs,
- hostname/domain values,
- analytics values,
- page path/source/city values,
- request bodies,
- AI payload JSON,
- OpenAI request bodies,
- raw GA4/OpenAI responses,
- generated report bodies,
- screenshots,
- browser Network tab data,
- cookies,
- sessions,
- nonces.

Future OAuth verification should continue to use status-level evidence only:
connection state, safe placeholder result, error category, and redacted UI
state.

## Verification Results

Executed checks:

- `git diff --check`
- `git diff --name-only`
- `git diff --stat`
- `php -l analytics-report-ai.php`
- `php -l includes/class-admin.php`
- `php -l includes/class-settings.php`
- source review for external API calls, Google token/revoke endpoint calls,
  GA4 Fetch, OpenAI Generate, token storage, credential output, raw
  request/response logging, screenshots, Plugin Check, and package rebuilds
- `git status --short --untracked-files=all`

Observed result:

- PHP syntax checks passed for the checked files.
- Diff whitespace check passed.
- No new PHP include file was added.
- No JavaScript, CSS, `readme.txt`, `.distignore`, build script, release
  package, `wp-dev-check`, Plugin Check, or package rebuild work was performed.
- Source review found the new OAuth skeleton limited to local admin action
  registration, capability / nonce boundary, and safe Settings redirects.
- No external API communication was performed.
- No Google token endpoint, revoke endpoint, GA4 Fetch, or OpenAI Generate was
  executed.
- No token storage, credential value output, option value output, raw request
  logging, raw response logging, screenshot capture, or browser/network
  evidence collection was performed.

## Next Step Notes

Recommended next step:

```text
Step 121: Google OAuth state protection and callback validation boundary
```

Step 121 should still avoid external API calls, token exchange, token storage,
refresh, revoke, reconnect UI, GA4 client behavior changes, OpenAI storage
changes, uninstall cleanup, Plugin Check, and package rebuilds unless a later
accepted plan explicitly widens the scope.
