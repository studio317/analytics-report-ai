# Step 134: Google OAuth Provider Configuration Error Triage Plan

## Step Summary

Step 134 creates a docs-only triage plan for the status-level
`google_oauth_config_error_screen` result recorded in Step 133.

This step identifies Google Cloud Console configuration categories to review
before the next human-controlled OAuth redirect smoke. It does not run browser
OAuth, navigate to Google, call external APIs, complete OAuth authorization,
execute callback handling, exchange tokens, store tokens, refresh tokens,
revoke access, run GA4 Fetch, run OpenAI Generate, run Plugin Check, access
`wp-dev-check`, inspect plugin settings option values, inspect client ID values,
inspect client secret values, or change production code.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step133-human-controlled-configured-oauth-redirect-smoke-result-recording.md`
- `docs/maturation/step132-configured-environment-oauth-redirect-smoke-plan.md`
- `docs/maturation/step131-human-provided-oauth-redirect-smoke-result-recording.md`
- `docs/maturation/step130-human-controlled-browser-oauth-redirect-smoke-results.md`
- `docs/maturation/step129-google-authorization-redirect-execution-implementation-results.md`
- `docs/maturation/step128-controlled-browser-oauth-verification-evidence-boundary-plan.md`
- `docs/maturation/step127-google-oauth-authorization-redirect-execution-boundary-decision-checkpoint.md`
- `docs/maturation/step126-google-oauth-authorization-url-construction-helper-boundary-results.md`

## Baseline From Step 133

Step 133 recorded this status-level result:

```text
google_oauth_config_error_screen
```

The result means the configured-environment redirect smoke reached a Google
OAuth-related configuration/error screen category. Step 133 did not record raw
provider error text, provider error codes, authorization URLs, browser URLs,
callback URLs, query strings, raw state values, authorization codes, client ID
values, client secret values, hostname/domain values, screenshots, or Network
evidence.

Authorization approval was not completed. Authorization code was not observed
or recorded. Token exchange did not occur. Token storage did not occur.

## Triage Principles

Triage should be performed by category, not by collecting raw provider
evidence.

Allowed triage evidence:

- status-level category labels,
- yes/no configured-state labels,
- safe checklist completion state,
- redacted UI state,
- no-authorization-approval status,
- no-token-exchange status,
- no-token-storage status,
- no-forbidden-evidence status.

Disallowed triage evidence:

- authorization URLs,
- browser address bar URLs,
- callback URLs,
- query strings,
- raw state values,
- authorization codes,
- raw provider errors,
- provider error codes,
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

## Google Cloud Console Configuration Categories

| Category | What To Review Status-Level Only | Evidence Allowed | Evidence Not Allowed |
|---|---|---|---|
| OAuth client type / web application configuration category | Confirm whether the OAuth client is the expected web application category for browser redirects. | `oauth_client_type_reviewed`, `web_application_client_expected`, `client_type_mismatch_suspected` | Client ID value, client secret, screenshots, raw Google screen text, project identifiers. |
| Authorized redirect URI registration category | Confirm whether the plugin-provided redirect URI category is registered in the OAuth client configuration. | `redirect_uri_registration_reviewed`, `redirect_uri_registered_status_yes`, `redirect_uri_registered_status_no_or_mismatch_suspected` | Actual redirect URI, hostname/domain, callback URL, browser URL, query string, screenshots. |
| OAuth consent screen publishing/testing status category | Confirm whether the consent screen status permits the intended test user and scope category. | `consent_screen_status_reviewed`, `consent_screen_testing_status`, `consent_screen_published_status`, `consent_screen_status_mismatch_suspected` | Raw consent screen details, project identifiers, screenshots, provider raw error, provider error code. |
| Test user eligibility category | Confirm whether the human tester account is eligible under the current consent screen testing/publishing category. | `test_user_reviewed`, `test_user_allowed_status_yes`, `test_user_allowed_status_no_or_unknown` | Email addresses, account identifiers, screenshots, raw provider errors. |
| Requested scope category | Confirm whether the requested scope category matches the intended Google Analytics read-only access only. | `scope_category_reviewed`, `analytics_readonly_scope_expected`, `unexpected_scope_category_suspected` | Full authorization URL, query string, raw provider screen, screenshots, account identifiers. |
| Project/API enablement category | Confirm whether the relevant Google project/API enablement category is consistent with OAuth and future GA4 reporting needs. | `project_api_enablement_reviewed`, `analytics_api_enablement_status_yes`, `analytics_api_enablement_status_no_or_unknown` | Project identifiers, API keys, screenshots, raw Google Console values. |
| Local HTTP / localhost redirect compatibility category | Confirm whether the local development redirect environment category is acceptable for the OAuth client configuration. | `local_redirect_compatibility_reviewed`, `localhost_or_local_http_status_expected`, `local_redirect_compatibility_mismatch_suspected` | Actual hostname/domain, redirect URI, callback URL, browser URL, screenshots. |
| Client ID / secret mismatch category | Confirm whether the configured client ID and client secret belong to the same OAuth client category without recording values. | `client_pair_reviewed`, `client_pair_consistent_status_yes`, `client_pair_mismatch_suspected` | Client ID value, client secret value, copied constants, option values, screenshots. |

## Category-Specific Notes

### OAuth Client Type / Web Application Configuration Category

The redirect flow expects an OAuth client category that supports browser-based
redirect URIs.

Record only:

- whether the category was reviewed,
- whether the expected web application category appears to be used,
- whether a mismatch is suspected.

Do not record client IDs, project identifiers, screenshots, or Google Console
raw text.

### Authorized Redirect URI Registration Category

The redirect URI registration category is a likely source of provider-side
configuration screens.

Record only:

- whether redirect URI registration was reviewed,
- whether the expected redirect URI category appears registered,
- whether a mismatch is suspected.

Do not record the redirect URI, hostname/domain, callback URL, browser URL, or
query string.

### OAuth Consent Screen Publishing / Testing Status Category

Consent screen publishing/testing status can affect whether a tester reaches an
authorization screen or a provider-side configuration/error category.

Record only:

- whether consent screen status was reviewed,
- whether the status is testing-like or published-like,
- whether a status mismatch is suspected.

Do not record raw provider errors, provider error codes, project identifiers, or
screenshots.

### Test User Eligibility Category

If the consent screen is in a testing-like status, the tester may need to be in
an allowed user category.

Record only:

- whether test user eligibility was reviewed,
- whether the test user is considered allowed at status level,
- whether eligibility is unknown or mismatch is suspected.

Do not record email addresses, account identifiers, screenshots, or provider raw
errors.

### Requested Scope Category

The plugin's OAuth redirect boundary is expected to request only the Google
Analytics read-only scope category.

Record only:

- whether the requested scope category was reviewed,
- whether the expected Analytics read-only category is used,
- whether an unexpected scope category is suspected.

Do not record full authorization URLs, query strings, raw provider screen
details, screenshots, account identifiers, or raw scope-bearing URLs.

### Project/API Enablement Category

Project/API enablement can affect future GA4 access, though Step 134 does not
test GA4 Fetch or token exchange.

Record only:

- whether relevant project/API enablement category was reviewed,
- whether Analytics-related API enablement is status-level yes/no/unknown,
- whether mismatch is suspected.

Do not record project identifiers, API keys, screenshots, raw Google Console
values, or GA4 Property IDs.

### Local HTTP / Localhost Redirect Compatibility Category

Local development redirect compatibility may differ from production HTTPS
configuration.

Record only:

- whether local redirect compatibility was reviewed,
- whether local HTTP / localhost style configuration is expected for this smoke,
- whether compatibility mismatch is suspected.

Do not record hostnames, domains, redirect URIs, callback URLs, browser URLs,
query strings, or screenshots.

### Client ID / Secret Mismatch Category

Client ID and client secret must be treated as sensitive evidence even when
configuration consistency is being checked.

Record only:

- whether client pair consistency was reviewed,
- whether the pair is status-level consistent,
- whether mismatch is suspected.

Do not record client ID values, client secret values, copied constants, option
values, screenshots, or raw provider errors.

## Human-Side Recheck Observation Template

Use this status-level template for the next human-controlled recheck:

```text
- OAuth client type / web application category reviewed: Yes / No
- OAuth client type result:
  - web_application_client_expected
  - client_type_mismatch_suspected
  - not_reviewed
- Authorized redirect URI registration reviewed: Yes / No
- Authorized redirect URI category result:
  - redirect_uri_registered_status_yes
  - redirect_uri_registered_status_no_or_mismatch_suspected
  - not_reviewed
- OAuth consent screen status reviewed: Yes / No
- Consent screen category result:
  - consent_screen_testing_status
  - consent_screen_published_status
  - consent_screen_status_mismatch_suspected
  - not_reviewed
- Test user eligibility reviewed: Yes / No
- Test user category result:
  - test_user_allowed_status_yes
  - test_user_allowed_status_no_or_unknown
  - not_applicable
  - not_reviewed
- Requested scope category reviewed: Yes / No
- Scope category result:
  - analytics_readonly_scope_expected
  - unexpected_scope_category_suspected
  - not_reviewed
- Project/API enablement category reviewed: Yes / No
- Project/API category result:
  - analytics_api_enablement_status_yes
  - analytics_api_enablement_status_no_or_unknown
  - not_reviewed
- Local HTTP / localhost redirect compatibility reviewed: Yes / No
- Local redirect category result:
  - localhost_or_local_http_status_expected
  - local_redirect_compatibility_mismatch_suspected
  - not_reviewed
- Client ID / secret pair consistency reviewed: Yes / No
- Client pair category result:
  - client_pair_consistent_status_yes
  - client_pair_mismatch_suspected
  - not_reviewed
- Recheck browser smoke performed: Yes / No
- Browser smoke status-level result:
  - google_oauth_screen_reached
  - google_oauth_config_error_screen
  - wordpress_admin_error_notice
  - redirect_blocked_or_failed
  - not_performed
- Authorization approval was completed: No
- Authorization code was observed/recorded: No
- Token exchange occurred: No
- Token was stored: No
- Forbidden evidence recorded: No
```

Do not add actual URLs, identifiers, secrets, raw provider errors, provider
error codes, raw query values, screenshots, or Network evidence to this
template.

## Step 135 Boundary

Recommended next step:

```text
Step 135: Human-controlled OAuth provider configuration recheck
```

Step 135 should remain status-level. It may record the human-side category
review results and, if explicitly performed by the human tester, a browser
smoke result up to one of these categories:

- Google OAuth screen category,
- Google configuration/error screen category,
- WordPress admin notice category,
- redirect blocked/failed category.

Step 135 must not complete authorization approval. It must not observe or
record authorization codes, raw state values, raw provider errors, provider
error codes, full authorization URLs, browser URLs, callback URLs, query
strings, client ID values, client secrets, hostnames, screenshots, Network
evidence, cookies, sessions, nonces, credentials, option values, payloads, raw
responses, or generated report bodies.

Token endpoint requests, revoke endpoint requests, token exchange, token
storage, refresh, revoke, reconnect UI, GA4 Fetch, and OpenAI Generate remain
out of scope.

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
- CODEX did not inspect plugin settings option values, client ID values, client
  secret values, raw provider errors, provider error codes, URLs, query strings,
  screenshots, Network evidence, hostnames, credentials, payloads, raw
  responses, or generated report bodies.
- Planned evidence remains status-level only.
