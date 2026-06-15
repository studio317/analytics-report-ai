# Step 128: Controlled Browser OAuth Verification / Evidence Boundary Plan

## Step Summary

Step 128 is a docs-only plan for controlled browser OAuth verification and
evidence boundaries before Google authorization redirect execution is
implemented.

This step does not change code, execute a Google redirect, perform browser
OAuth verification, call external APIs, exchange tokens, store tokens, refresh
tokens, revoke access, or change credential storage.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step127-google-oauth-authorization-redirect-execution-boundary-decision-checkpoint.md`
- `docs/maturation/step126-google-oauth-authorization-url-construction-helper-boundary-results.md`
- `docs/maturation/step125-google-oauth-redirect-uri-generation-settings-display-copy-wording-results.md`
- `docs/maturation/step124-google-oauth-client-configuration-constants-status-boundary-implementation-results.md`
- `docs/maturation/step123-google-oauth-client-configuration-token-exchange-boundary-plan.md`
- `docs/maturation/step121-google-oauth-state-protection-callback-validation-boundary-implementation-results.md`
- `docs/maturation/step120-google-oauth-skeleton-admin-action-boundary-implementation-results.md`

## Current Baseline

| Area | Baseline |
|---|---|
| Local OAuth connect / callback skeleton | Implemented in Step 120. |
| Temporary state generation / callback validation boundary | Implemented in Step 121. |
| Client configuration constants / status boundary | Implemented in Step 124. |
| Redirect URI generation / Settings display boundary | Implemented in Step 125. |
| Authorization URL construction helper boundary | Implemented in Step 126. |
| Redirect execution decision checkpoint | Step 127 decided that a browser OAuth verification and evidence plan is needed before redirect execution. |
| Authorization URL helper execution | Helper exists, but the current flow does not call it. |
| Google authorization redirect execution | Not implemented. |
| Token endpoint request | Not implemented. |
| Token exchange | Not implemented. |
| Token storage | Not implemented. |
| WordPress.org release | `Hold`. |

## Source Review Vs Browser Verification Boundary

Redirect execution implementation and browser OAuth verification must remain
separate phases.

Implementation phase:

- May add narrow production PHP for redirect execution in a later step.
- Must be verified by source review only.
- Must not execute browser OAuth.
- Must not navigate to Google.
- Must verify by source review that token exchange is not implemented.
- Must verify by source review that token storage is not implemented.
- Must verify by source review that token and revoke endpoints are not called.
- Must verify by source review that GA4 Fetch and OpenAI Generate remain out of
  scope.
- Must not record a full authorization URL.

Human browser verification phase:

- Must be a separate step.
- Requires explicit approval before execution.
- Must record only status-level results.
- Must not use screenshots or browser Network evidence.
- Must not record browser address bar URLs, callback URLs, query strings, raw
  state values, authorization codes, provider error values, credentials,
  client ID values, client secrets, hostnames, domains, or option values.

CODEX should not perform real browser OAuth execution by default. Any future
browser OAuth smoke should be human-controlled and explicitly approved.

## Controlled Browser OAuth Verification Rules

Future human-controlled browser OAuth redirect smoke must follow these rules:

- Require explicit approval before execution.
- Treat Google client constants as user-managed setup.
- Do not record client secret values.
- Do not record client ID values.
- Do not record the actual authorization URL.
- Do not record the browser address bar URL.
- Do not record OAuth consent screen screenshots.
- Do not record browser Network tab evidence.
- Do not record raw state values.
- Do not record authorization codes.
- Do not record raw provider error values.
- Do not record callback URLs or query strings.
- Do not record hostname/domain values.
- Record status-level categories only.
- Confirm no token exchange occurs by source-level and status-level evidence.
- Confirm no token storage occurs by source-level and status-level evidence.
- Keep GA4 Fetch and OpenAI Generate out of the same verification step.

## Allowed Evidence

Future browser OAuth verification may record only status-level evidence such as:

```text
redirect_execution_reachable
redirect_execution_not_triggered
google_navigation_started_status_only
callback_returned_status_only
callback_state_missing
callback_state_expired
callback_state_invalid
callback_state_valid_code_present
callback_state_valid_provider_error
no_token_exchange_observed_by_source_boundary
no_token_storage_observed_by_source_boundary
no_ga4_fetch_observed_by_source_boundary
no_openai_generate_observed_by_source_boundary
```

Allowed evidence must not include actual URLs, identifiers, secrets, raw query
values, payloads, responses, analytics values, or generated report text.

## Disallowed Evidence

Do not record:

- screenshots,
- browser Network tab data,
- full authorization URLs,
- browser address bar URLs,
- callback URLs,
- query strings,
- raw state values,
- authorization codes,
- provider error raw values,
- client ID values,
- client secrets,
- token endpoint requests,
- token endpoint responses,
- access tokens,
- refresh tokens,
- Authorization headers,
- plugin option values,
- hostname/domain values,
- GA4 Property ID values,
- analytics values,
- AI payload JSON,
- generated report body.

## Abort / Rollback Behavior

If unexpected behavior appears during a future redirect execution implementation
or human browser OAuth smoke:

- Stop without recording authorization URLs or callback query values.
- Stop if token exchange appears likely to execute.
- Stop if token, authorization code, client secret, raw state, provider error,
  client ID value, hostname/domain, or option value exposure appears likely.
- Do not rely on screenshots or browser Network evidence.
- Record only status-level categories in docs or issue notes.
- Do not perform opportunistic fixes inside the verification step.
- Open a separate follow-up step for any required fix or boundary adjustment.
- Keep GA4 Fetch and OpenAI Generate out of the verification scope.

Rollback for this phase means returning to the prior safe source state or
creating a separate remediation step before any further OAuth browser execution.

## Proposed Next Steps

| Step | Scope |
|---|---|
| Step 129 | Google authorization redirect execution implementation, source review only, no browser execution. |
| Step 130 | Human-controlled browser OAuth redirect smoke, only if explicitly approved, status-level evidence only, no token exchange, no screenshots, no Network evidence. |
| Step 131 | Post-smoke redirect boundary adjustment if needed; otherwise token exchange skeleton decision checkpoint. |

## Recommended Next Step

Recommended next step:

```text
Step 129: Google authorization redirect execution implementation
```

Step 129 should be a narrow production PHP implementation that calls the
authorization URL helper from the connect action and executes the Google
redirect. It should remain source-review-only in Codex, with no browser OAuth
execution unless separately approved.

Step 129 must not include:

- token endpoint request,
- token exchange,
- token storage,
- refresh,
- revoke,
- reconnect UI,
- GA4 Fetch,
- OpenAI Generate,
- Plugin Check,
- package rebuild,
- screenshots,
- browser Network evidence,
- full authorization URL recording.

## Support / Debug Evidence Boundary

The support/debug evidence boundary remains:

- Do not record credentials, API keys, access tokens, or Authorization headers.
- Do not record client secrets.
- Do not record client ID values.
- Do not record option values.
- Do not record full authorization URLs.
- Do not record browser address bar URLs.
- Do not record callback URLs or query strings.
- Do not record authorization codes, raw state values, or raw provider errors.
- Do not record token endpoint requests or raw token responses.
- Do not record request bodies, raw responses, AI payload JSON, OpenAI request
  bodies, or generated report bodies.
- Do not record GA4 Property ID, hostname/domain, analytics values, page path,
  source, or city values.
- Do not use screenshots or Network tab data.
- Keep support evidence limited to status-level labels, safe result categories,
  redacted UI state, connection state, and error category.

## Explicit Non-goals

- Code change.
- `readme.txt` change.
- `.distignore` / build script change.
- Package rebuild.
- Plugin Check rerun.
- External API call.
- Google authorization redirect execution.
- Browser OAuth execution.
- Authorization URL UI display.
- Token endpoint request.
- Revoke endpoint request.
- GA4 Fetch.
- OpenAI Generate.
- Token exchange implementation.
- Token storage implementation.
- Refresh / revoke / reconnect UI implementation.
- GA4 client behavior change.
- OpenAI API key storage implementation.
- `uninstall.php` creation.
- Option deletion implementation.
- Settings save logic change.
- Credential storage change.
- Raw payload / raw response / generated report body recording.
- Screenshots.
- Credential / option value inspection.
- UI change.

## Verification Results

Executed checks:

- `git diff --check`
- `git diff --name-only`
- `git diff --stat`
- `git status --short --untracked-files=all`

Observed result:

- Diff whitespace check passed.
- No production code, PHP, JavaScript, CSS, `readme.txt`, `.distignore`,
  tools, package, or runtime file changes were made.
- The only repository change for this step is this new docs file.
- Plugin Check was not rerun.
- `wp-dev-check` was not touched.
- No external API communication was performed.
- Google authorization redirect and browser OAuth execution were not performed.
- GA4 Fetch and OpenAI Generate were not performed.
- No credentials, client secret, client ID value, authorization URL, browser
  URL, callback URL, query string, raw state, authorization code, provider
  error, option value, hostname/domain, analytics value, payload, raw response,
  generated report body, screenshot, browser Network data, cookie, session, or
  nonce value was recorded.
