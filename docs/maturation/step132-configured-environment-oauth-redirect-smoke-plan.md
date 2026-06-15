# Step 132: Configured-Environment OAuth Redirect Smoke Plan

## Step Summary

Step 132 defines a docs-only plan for the next human-controlled OAuth redirect
smoke in a client ID constant configured environment.

This step does not run browser OAuth, navigate to Google, call external APIs,
complete OAuth authorization, execute callback handling, exchange tokens, store
tokens, refresh tokens, revoke access, run GA4 Fetch, run OpenAI Generate, run
Plugin Check, access `wp-dev-check`, inspect plugin settings option values, or
change production code.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step131-human-provided-oauth-redirect-smoke-result-recording.md`
- `docs/maturation/step130-human-controlled-browser-oauth-redirect-smoke-results.md`
- `docs/maturation/step129-google-authorization-redirect-execution-implementation-results.md`
- `docs/maturation/step128-controlled-browser-oauth-verification-evidence-boundary-plan.md`
- `docs/maturation/step127-google-oauth-authorization-redirect-execution-boundary-decision-checkpoint.md`
- `docs/maturation/step126-google-oauth-authorization-url-construction-helper-boundary-results.md`
- `docs/maturation/step125-google-oauth-redirect-uri-generation-settings-display-copy-wording-results.md`
- `docs/maturation/step124-google-oauth-client-configuration-constants-status-boundary-implementation-results.md`
- `docs/maturation/step121-google-oauth-state-protection-callback-validation-boundary-implementation-results.md`
- `docs/maturation/step120-google-oauth-skeleton-admin-action-boundary-implementation-results.md`

## Current Baseline

Step 131 recorded a human-provided status-level smoke result for the
client-configuration-missing branch:

```text
blocked_client_config_missing
```

That result was a safe blocked behavior, not a failure. It confirmed that the
flow returned to WordPress admin before contacting Google when the client ID
constant was not configured.

Full Google OAuth authorization redirect smoke remains pending for a later
client ID constant configured environment.

## Configured-Environment Scope

The next smoke should be performed only when a client ID constant configured
environment is intentionally prepared by the user.

Even in that configured environment, the smoke must not record:

- client ID value,
- client secret value,
- authorization URL,
- browser address bar URL,
- callback URL,
- query string,
- raw state,
- authorization code,
- provider raw error,
- hostname/domain,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonce values,
- credentials,
- API keys,
- access tokens,
- Authorization headers,
- plugin settings option values,
- GA4 Property ID values,
- analytics values,
- request bodies,
- AI payload JSON,
- raw API responses,
- generated report bodies.

## Browser Smoke Boundary

The configured-environment smoke should stop before authorization approval.

If the browser reaches a Google OAuth-related screen, the human tester should
record only a status-level category and stop. The tester should not complete
authorization approval. This keeps token exchange and token storage out of
scope.

If Google displays a configuration mismatch or provider-side error screen, the
tester should not record raw provider error text, URLs, query values, client ID
values, hostnames, or screenshots. Record only a status-level category.

CODEX should not perform this browser smoke. CODEX should only record
human-provided status-level results in a later step.

## Human Observation Template

Use this status-level template for the next human-controlled smoke:

```text
- Client ID constant configured before smoke: Yes / No
- Client secret constant configured before smoke: Yes / No / Not required for redirect-only smoke
- Connect action was triggered from Settings: Yes / No
- Redirect attempt occurred: Yes / No
- Browser reached Google OAuth-related screen: Yes / No / Not reached
- Observed result category:
  - google_oauth_screen_reached
  - google_oauth_config_error_screen
  - wordpress_admin_error_notice
  - redirect_blocked_or_failed
  - unexpected_result
- Returned to WordPress admin automatically: Yes / No
- WordPress admin notice category, if any:
  - oauth_config_missing
  - redirect_url_unavailable
  - oauth_callback_error_category_only
  - no_notice_observed
  - not_applicable
- Authorization approval was completed: No
- Authorization code was observed/recorded: No
- Token exchange occurred: No
- Token was stored: No
- Forbidden evidence recorded: No
```

Do not add actual URLs, identifiers, secrets, raw query values, screenshots, or
Network evidence to this template.

## Allowed Evidence

Allowed evidence is limited to:

- status-level labels,
- safe result categories,
- redacted UI state,
- connection state categories,
- error categories,
- no-authorization-approval status,
- no-token-exchange status,
- no-token-storage status,
- no-forbidden-evidence status.

## Disallowed Evidence

Do not record:

- authorization URLs,
- browser address bar URLs,
- callback URLs,
- query strings,
- raw state values,
- authorization codes,
- raw provider errors,
- client ID values,
- client secrets,
- hostname/domain values,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonce values,
- credentials,
- API keys,
- access tokens,
- Authorization headers,
- plugin settings option values,
- GA4 Property ID values,
- analytics values,
- request bodies,
- AI payload JSON,
- raw API responses,
- generated report bodies.

## Expected Safe Outcomes

Expected safe configured-environment outcomes include:

| Status-level outcome | Meaning |
|---|---|
| `google_oauth_screen_reached` | Browser reached a Google OAuth-related screen and the tester stopped before approval. |
| `google_oauth_config_error_screen` | Browser reached a Google-side configuration/error category, recorded without raw error details. |
| `wordpress_admin_error_notice` | Flow returned to WordPress admin with a safe notice category. |
| `redirect_blocked_or_failed` | Redirect attempt did not reach Google and no forbidden evidence was recorded. |
| `unexpected_result` | A status-level unexpected result occurred; stop and create a separate investigation step without recording forbidden evidence. |

None of these outcomes should include authorization approval, authorization code
recording, token endpoint calls, token exchange, token storage, GA4 Fetch, or
OpenAI Generate.

## Abort Rules

Abort the smoke without recording forbidden evidence if:

- the tester would need to copy or paste a URL,
- the tester would need to inspect the browser address bar,
- the tester would need to inspect a callback URL or query string,
- raw state, authorization code, provider error, client ID value, client secret,
  hostname/domain, token, or option value might be recorded,
- authorization approval would be required to continue,
- token exchange appears likely,
- screenshots or Network evidence would be needed to explain the result,
- GA4 Fetch or OpenAI Generate appears in scope.

Record only the closest safe status-level category and defer details to a later
boundary decision step.

## Explicit Non-goals

- Production code change.
- PHP / JavaScript / CSS / assets change.
- `readme.txt` change.
- `.distignore` / build script change.
- Release package rebuild.
- Plugin Check rerun.
- Plugin Check execution in `wp-dev`.
- `wp-dev-check` access.
- Browser OAuth execution by CODEX.
- Google navigation by CODEX.
- External API communication by CODEX.
- OAuth authorization approval.
- Callback execution by CODEX.
- Token endpoint request.
- Revoke endpoint request.
- Token exchange implementation or execution.
- Token storage implementation or execution.
- Refresh.
- Revoke.
- Reconnect UI.
- GA4 Fetch.
- OpenAI Generate.
- GA4 client behavior change.
- OpenAI API key storage change.
- Settings save logic change.
- Credential storage change.
- `uninstall.php` creation.
- Option deletion implementation.
- Manual Google Access Token entry removal or behavior change.
- Plugin settings option value inspection.
- Client ID value or client secret value inspection.

## Verification Results

Executed checks:

- `git diff --check`
- `git diff --name-only`
- `git diff --stat`
- `git status --short --untracked-files=all`

Observed result:

- Diff whitespace check passed.
- No tracked production code, PHP, JavaScript, CSS, assets, `readme.txt`,
  `.distignore`, build script, tool, package, or runtime file changes were made.
- The only repository change for this step is this new docs file.
- CODEX did not perform browser OAuth execution, Google navigation, external API
  communication, OAuth authorization, callback execution, token exchange, token
  storage, GA4 Fetch, OpenAI Generate, Plugin Check, or `wp-dev-check` access.
- CODEX did not inspect plugin settings option values, client ID values, or
  client secret values.
- Planned evidence remains status-level only.

## Recommended Next Step

Recommended next step:

```text
Step 133: Human-controlled configured OAuth redirect smoke result recording
```

Step 133 should record only the human-provided status-level observation
template result. It should not record full URLs, browser URLs, callback query
values, raw state, authorization codes, provider raw errors, client ID values,
client secrets, screenshots, Network evidence, cookies, sessions, nonces,
credentials, option values, analytics values, payloads, raw responses, or
generated report bodies.
