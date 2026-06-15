# Step 123: Google OAuth Client Configuration / Token Exchange Boundary Plan

## Step Summary

This step records a docs-only detailed plan for Google OAuth client
configuration and token exchange boundaries.

Step 122 recommended creating this plan before adding Google authorization
redirect or token exchange code. This document details client configuration,
redirect URI, scope, authorization URL construction, client secret handling,
token exchange boundary, token response normalization, token storage boundary,
safe error mapping, QA boundary, and recommended implementation slices.

This step does not change code, redirect to Google, construct a live
authorization URL, call token or revoke endpoints, exchange tokens, store
tokens, refresh tokens, revoke access, add reconnect UI, change GA4 client
behavior, change OpenAI storage, change Settings save logic, create
`uninstall.php`, rebuild packages, rerun Plugin Check, or call external APIs.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step122-google-oauth-authorization-redirect-token-exchange-boundary-decision-checkpoint.md`
- `docs/maturation/step121-google-oauth-state-protection-callback-validation-boundary-implementation-results.md`
- `docs/maturation/step120-google-oauth-skeleton-admin-action-boundary-implementation-results.md`
- `docs/maturation/step119-credential-implementation-readiness-checkpoint.md`
- `docs/maturation/step116-google-oauth-token-lifecycle-implementation-plan.md`
- `docs/maturation/step115-credential-implementation-roadmap-phase-boundary-plan.md`
- `docs/maturation/step114-integrated-credential-architecture-plan.md`
- `docs/maturation/step110-google-oauth-token-lifecycle-strategy-decision-checkpoint.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`

## Current Baseline

| Area | Baseline |
|---|---|
| Local OAuth connect / callback skeleton | Implemented in Step 120. |
| Temporary OAuth state generation / callback validation boundary | Implemented in Step 121. |
| Authorization redirect / token exchange checkpoint | Step 122 decided a detailed boundary plan is needed before code. |
| Google authorization redirect | Not implemented. |
| Authorization URL construction | Not implemented. |
| Token exchange | Not implemented. |
| Token storage | Not implemented. |
| Manual Google Access Token entry | Still developer verification only. |
| WordPress.org release | `Hold`. |

The current code has a local admin action skeleton and a temporary
state/callback validation boundary. It does not yet have an accepted client
configuration model, redirect URI display policy, scope policy, token exchange
layer, token response normalization layer, or Google credential storage model.

## Client Configuration Options

### Option A: Store Google OAuth Client ID And Client Secret In Plugin Settings

| Category | Notes |
|---|---|
| Pros | Familiar for site administrators. Keeps all plugin setup in the Settings screen. Does not require editing `wp-config.php` or deployment configuration. |
| Cons | Stores the client secret in WordPress options, which may be readable by database administrators, backups, server administrators, or code that can read options. Adds another credential-bearing setting. |
| Security / privacy risk | High. Client secret becomes part of plugin settings storage and uninstall cleanup scope. Non-redisplay helps UI exposure but does not remove database/backup exposure. |
| Support impact | Higher. Users may paste client secret values into support requests unless wording is very strict. Rotation and deletion expectations must be clear. |
| WordPress.org public release impact | Risky unless the storage, redaction, rotation, and uninstall behavior are explicitly accepted and disclosed. |
| Recommendation | Not recommended as the default planning direction. |

### Option B: Configure Google OAuth Client ID And Client Secret With WordPress Constants

| Category | Notes |
|---|---|
| Pros | Keeps the client secret out of plugin settings. Fits deployment-managed secret handling. Reduces database and option-reader exposure. |
| Cons | Harder for non-technical administrators. Requires editing deployment configuration. May need clear admin status messages for missing constants. |
| Security / privacy risk | Lower than settings storage, provided constants are managed outside the database and not exposed in UI, logs, docs, or support evidence. |
| Support impact | Medium. Support can ask for status categories, not values. Setup instructions need to explain where constants belong without requesting secret values. |
| WordPress.org public release impact | Better security posture, but user setup friction is higher. Needs careful readme/privacy wording. |
| Recommendation | Acceptable candidate, but may be too strict if client ID also needs an admin-editable path. |

### Option C: Client ID From Settings Or Constant, Client Secret Constant-only

| Category | Notes |
|---|---|
| Pros | Treats client ID as non-secret while keeping client secret out of plugin settings. Allows flexible client ID management while keeping the credential-bearing secret deployment-managed. Aligns with reduced option exposure. |
| Cons | Mixed configuration sources need clear precedence and status messaging. Settings UI must avoid making the OAuth flow look complete when client secret is missing. |
| Security / privacy risk | Medium-low. Client secret avoids plugin option storage, but client ID and redirect URI still need safe handling and support wording. |
| Support impact | Moderate. Support can work from status labels such as configured/missing without requesting values. |
| WordPress.org public release impact | Strong planning direction for now because it balances usability and credential minimization. |
| Recommendation | Recommended client configuration direction for now, with final acceptance pending. |

### Option D: Use A Built-in Shared OAuth Client For Public Release

| Category | Notes |
|---|---|
| Pros | Simplifies setup for administrators if allowed and maintained. Avoids each site owner creating their own OAuth client. |
| Cons | Raises distribution, ownership, quota, consent screen, app verification, support, abuse handling, revocation, and long-term maintenance questions. Makes the plugin author responsible for shared OAuth infrastructure. |
| Security / privacy risk | High at the project/operational level. A shared client concentrates responsibility and failure modes. |
| Support impact | High. Consent screen, quota, verification, and account access issues become shared support burdens. |
| WordPress.org public release impact | Not a safe assumption without a separate operational and policy decision. |
| Recommendation | Not recommended at this stage. |

Recommended client configuration direction: Option C as the preferred planning
direction for now, while keeping final acceptance pending.

Rationale:

- Storing client secret in plugin settings is part of the same database,
  backup, server-admin, and option-reader exposure category as other
  credential storage.
- Constant-based client secret configuration fits site-owner /
  deployment-managed secret handling.
- Client ID is not a secret, but it still affects setup, redirect URI, and
  support wording.
- A built-in shared OAuth client has large distribution, ownership, quota,
  consent screen, and support responsibilities and should not be assumed for
  this phase.

## Redirect URI Boundary

Planning direction:

- Use the local `admin-post.php` callback action as the redirect URI target.
- Generate the redirect URI per site.
- Candidate callback action:

```text
analytics_report_ai_google_oauth_callback
```

- Candidate generation approach:

```text
admin_url( 'admin-post.php?action=analytics_report_ai_google_oauth_callback' )
```

- The redirect URI itself is not a secret, but it should still be treated as
  setup data rather than broad support evidence.
- Settings UI may display/copy the redirect URI in a later step so site owners
  can configure the Google OAuth client.
- Site URL changes, HTTP/HTTPS mismatches, reverse proxy behavior,
  staging/production mismatch, and copied redirect URI mismatch are likely
  support topics.
- Support should prioritize status-level guidance such as redirect URI
  mismatch category instead of screenshots or full environment dumps.

## Scope Boundary

Planning direction:

- Request the minimum Google Analytics Data API read-only scope needed for GA4
  reporting.
- Do not request write scopes.
- Do not request unrelated scopes such as Drive, Gmail, Search Console, or
  broad account scopes.
- Scope should be explicit in code, admin/help wording, and privacy/readme
  wording.
- The requested scope should be explained as required only to fetch selected
  GA4 report data for report generation.
- Support/debug evidence should not require screenshots of the OAuth grant
  screen.

Scope selection must be finalized before authorization URL construction is
implemented.

## Authorization URL Construction Boundary

Planning direction:

- Build the authorization URL plugin-side in a future helper or OAuth-focused
  boundary.
- Inputs to the authorization URL should be limited to accepted client ID,
  redirect URI, scope, response type, state, and explicitly accepted OAuth
  parameters.
- `state` raw value must be sent to Google during redirect, but it must not be
  displayed, logged, saved in docs, or requested as support evidence.
- `access_type` and `prompt` behavior must be designed before implementation
  because it affects refresh-token availability and consent behavior.
- The authorization URL should not be stored as persistent plugin data.
- The full authorization URL should not be logged, written into docs as
  observed evidence, or requested from users for support.
- Redirect execution is a later implementation step and is not performed in
  this step.

## Token Exchange Boundary

Planning direction:

- Token exchange happens only after callback state validation succeeds.
- State missing, expired, or invalid categories must never proceed to token
  exchange.
- Authorization code handling should be short-lived and in-memory for the
  request that performs token exchange.
- Authorization code raw value must not be stored, displayed, logged, written
  to docs, or requested for support.
- Token endpoint request body must not be logged or recorded.
- Raw token endpoint response must not be logged, displayed, or recorded.
- Token exchange layer should return only a normalized result or safe error
  category to upper layers.
- Token exchange failure should map to status-level categories without raw
  provider errors, response bodies, request bodies, codes, tokens, or secrets.

The token exchange implementation should be a separate, explicitly approved
implementation slice after storage and QA boundaries are accepted.

## Token Storage Boundary

Planning topics:

- Access token storage category.
- Refresh token storage category, if refresh tokens are adopted.
- `expires_at`, `issued_at`, scope, and token type storage.
- Reconnect-only strategy versus refresh-token strategy.
- Redaction and non-redisplay guarantees for all token values.
- Support/debug exclusion for token values, authorization codes, and raw
  provider responses.
- Whether Google credential storage remains inside existing plugin settings or
  moves to a dedicated Google credential storage boundary.
- How Google credential storage aligns with OpenAI API key storage.
- How uninstall cleanup treats Google credential-bearing data.
- How disconnect and revoke behavior interact with local storage.

Planning direction:

- Token values must never be redisplayed.
- Token values must never be used as support/debug evidence.
- Google credential storage should be treated as a credential-bearing category
  separate from non-sensitive plugin preferences.
- A dedicated Google credential storage boundary remains a candidate, but no
  option migration is implemented in this step.

## Refresh Vs Reconnect Strategy

### Option A: Store Refresh Token And Support Automatic Refresh

| Category | Notes |
|---|---|
| Pros | Better user experience because reports can continue after access-token expiry. Fewer manual reconnect events. |
| Cons | Requires storing a high-value long-lived credential category. Requires refresh token lifecycle, rotation handling, failed-refresh handling, revoke/disconnect behavior, and uninstall cleanup. |
| Security / privacy risk | High. Refresh token storage has stronger credential lifecycle implications than short-lived access tokens. |
| UX impact | Best UX if implemented well. |
| Support impact | Higher complexity around refresh failures, revoked grants, consent changes, and token rotation. |
| Implementation complexity | High. |
| Recommendation | Not recommended for the first public-ready implementation planning phase. |

### Option B: Do Not Store Refresh Token; Reconnect When Access Token Expires

| Category | Notes |
|---|---|
| Pros | Reduces long-lived credential storage. Keeps token lifecycle smaller. Easier uninstall cleanup. |
| Cons | Worse UX because users must reconnect when access token expires. May make report generation unreliable for repeated use. |
| Security / privacy risk | Lower than refresh-token storage. |
| UX impact | Lower. |
| Support impact | Medium. Clear reconnect messaging is required. |
| Implementation complexity | Medium. |
| Recommendation | Viable but should be framed as reconnect-only behavior. |

### Option C: First Public-ready Plan Uses Reconnect-only; Refresh Token Support Deferred

| Category | Notes |
|---|---|
| Pros | Keeps the first public-ready storage scope smaller while leaving a future refresh-token phase possible. Reduces credential storage and uninstall complexity for the first release posture. |
| Cons | UX remains limited until refresh support is added. More explicit wording is needed so users understand reconnect behavior. |
| Security / privacy risk | Lower for the first public-ready implementation. |
| UX impact | Moderate to low, depending on how clearly reconnect is presented. |
| Support impact | Medium. Support can focus on status-level reconnect categories. |
| Implementation complexity | Lower for the first implementation phase. |
| Recommendation | Recommended lifecycle direction for first public-ready implementation planning unless later requirements require refresh token support before release. |

Recommended lifecycle direction: Option C for first public-ready
implementation planning, unless later user requirements require refresh token
support before release.

Rationale:

- Refresh token storage expands credential lifecycle, uninstall cleanup, revoke,
  and support/debug risk.
- Reconnect-only has weaker UX but keeps initial storage scope smaller.
- Refresh token support can be treated as a later phase with a dedicated
  storage, revoke, cleanup, and QA plan.

## Safe Error Mapping

All OAuth errors should map to safe status-level categories. Raw provider
errors, raw responses, request bodies, authorization codes, token values,
client secrets, and raw state values must not be displayed or recorded.

| Category | Meaning | Evidence boundary |
|---|---|---|
| `client_config_missing` | Required OAuth client configuration is missing. | Do not show client secret or option values. |
| `redirect_uri_mismatch` | Provider rejected or could not match the configured redirect URI. | Do not request screenshots or full authorization URL. |
| `state_missing` | Callback did not contain a state value. | Do not show raw query values. |
| `state_expired` | Local temporary state was no longer available. | Do not show raw state. |
| `state_invalid` | Callback state did not match the local state boundary. | Do not show raw state. |
| `authorization_denied` | User or provider denied authorization. | Do not show raw provider error text. |
| `authorization_code_missing` | Callback did not contain an authorization code after valid state. | Do not show raw query values. |
| `token_exchange_failed` | Token endpoint exchange failed. | Do not show request body, response body, client secret, code, or token values. |
| `token_response_invalid` | Token response could not be normalized. | Do not show raw token response. |
| `credential_not_connected` | No usable Google credential is connected. | Show only connection state. |
| `credential_expired_or_reconnect_required` | Credential cannot be used and reconnect is required. | Do not show token values or provider response. |
| `insufficient_permission` | Connected grant does not have required GA4 read permission. | Do not show identifiers or raw provider errors. |
| `property_access_failed` | Connected credential cannot access the selected property. | Do not show GA4 Property ID or raw analytics identifiers in support evidence. |
| `unknown_google_auth_error` | Unclassified Google auth failure. | Keep message generic and status-level. |

## Recommended Implementation Slices After Step 123

### Slice A: Client Configuration Constants And Status Detection

Purpose:

- Add constants/status boundary for Google OAuth client configuration.
- Detect configured/missing client ID and client secret categories without
  exposing values.

Likely changed files:

- `analytics-report-ai.php` only if constants are defined there.
- `includes/class-admin.php`
- `includes/class-settings.php`
- possible OAuth-focused include file if justified.
- docs under `docs/maturation/`.

Explicit non-goals:

- No authorization URL construction.
- No Google redirect.
- No token endpoint request.
- No token exchange.
- No token storage.
- No Settings save logic change.
- No credential value display.

External API impact:

- None.

QA scope:

- PHP syntax checks.
- Source review for constant detection and safe status output.
- No browser OAuth execution.

Risk level:

- Low to medium.

### Slice B: Redirect URI Generation And Settings Display / Copy Wording

Purpose:

- Generate the local redirect URI for setup.
- Provide safe Settings wording for copying/configuring the redirect URI.

Likely changed files:

- `includes/class-settings.php`
- possibly OAuth helper include.
- docs under `docs/maturation/`.

Explicit non-goals:

- No authorization URL construction.
- No Google redirect.
- No token exchange.
- No token storage.
- No support request for screenshots or full environment dumps.

External API impact:

- None.

QA scope:

- PHP syntax checks.
- Source review for escaping and safe display.
- Confirm redirect URI is status/setup data, not secret evidence.

Risk level:

- Medium, because redirect URI mismatch can become a support issue.

### Slice C: Authorization URL Construction Helper, No Redirect Execution

Purpose:

- Build an authorization URL helper without executing redirect.
- Keep full URL out of logs/docs/support evidence.

Likely changed files:

- OAuth helper include or `includes/class-admin.php`.
- `includes/class-settings.php` if status wording is needed.
- docs under `docs/maturation/`.

Explicit non-goals:

- No browser redirect to Google.
- No token exchange.
- No token storage.
- No raw state display.

External API impact:

- None if no redirect is executed.

QA scope:

- PHP syntax checks.
- Source review for parameter construction, escaping, and no logging.

Risk level:

- Medium.

### Slice D: Authorization Redirect Execution Using Existing State Boundary

Purpose:

- Execute the Google authorization redirect using the accepted state,
  client ID, redirect URI, and scope boundaries.

Likely changed files:

- `includes/class-admin.php`
- OAuth helper include if introduced.
- `includes/class-settings.php`
- docs under `docs/maturation/`.

Explicit non-goals:

- No token exchange.
- No token storage.
- No refresh/revoke/reconnect.
- No GA4 client behavior changes.

External API impact:

- Yes if exercised in a browser, because it navigates to Google. Implementation
  source review can still avoid executing it.

QA scope:

- PHP syntax checks.
- Source review.
- No browser OAuth execution unless explicitly approved in a later verification
  step.

Risk level:

- Medium to high.

### Slice E: Token Exchange Boundary Skeleton Returning Safe Placeholder Categories

Purpose:

- Add a token exchange boundary interface that returns safe placeholder
  categories without calling the real token endpoint.

Likely changed files:

- OAuth helper/client include.
- `includes/class-admin.php`
- `includes/class-settings.php`
- docs under `docs/maturation/`.

Explicit non-goals:

- No real token endpoint request.
- No token storage.
- No raw request/response logging.
- No credential display.

External API impact:

- None.

QA scope:

- PHP syntax checks.
- Source review for safe category mapping and no external request.

Risk level:

- Medium.

### Slice F: Real Token Exchange Implementation After Explicit Approval

Purpose:

- Implement real token endpoint exchange after client configuration, storage,
  refresh/reconnect, safe error mapping, and QA boundaries are accepted.

Likely changed files:

- OAuth helper/client include.
- `includes/class-admin.php`
- `includes/class-settings.php`
- possible settings/storage helpers.
- docs under `docs/maturation/`.

Explicit non-goals:

- No implementation until explicitly approved.
- No raw code, client secret, request body, raw token response, or token value
  output.
- No GA4 report logic changes unless separately planned.

External API impact:

- Yes. Any real token exchange requires a separate explicit implementation and
  controlled verification plan.

QA scope:

- PHP syntax checks.
- Source review for redaction, non-redisplay, and safe error mapping.
- Controlled external verification only after explicit approval.

Risk level:

- High.

## Recommended Next Step

Recommended next step:

```text
Step 124: Google OAuth client configuration constants and status boundary implementation
```

Step 124 should be a narrow production PHP implementation step limited to
client configuration constants and status detection.

Step 124 should not implement:

- Google authorization redirect.
- Authorization URL construction.
- Token endpoint request.
- Token exchange.
- Token storage.
- Refresh.
- Revoke.
- Reconnect UI.
- GA4 client behavior changes.
- OpenAI storage changes.
- Uninstall cleanup.
- External API calls.

## Support / Debug Evidence Boundary

The support/debug evidence boundary remains:

- Do not record credentials, API keys, access tokens, or Authorization headers.
- Do not record client secrets.
- Do not record option values.
- Do not record authorization codes, raw state values, or raw provider errors.
- Do not record full authorization URLs.
- Do not record token endpoint request bodies or raw token responses.
- Do not record request bodies, raw responses, AI payload JSON, or generated
  report bodies.
- Do not record GA4 Property ID, hostname/domain, analytics values, page path,
  source, or city values.
- Keep support evidence to status-level labels, redacted saved-state,
  error category, connection state, generation allowed/blocked state, and safe
  UI wording.
- Do not use screenshots or browser Network tab data by default.

## Explicit Non-goals

- Code change.
- `readme.txt` change.
- `.distignore` / build script change.
- Package rebuild.
- Plugin Check rerun.
- External API call.
- Google authorization redirect.
- Authorization URL construction implementation.
- Token endpoint request.
- Revoke endpoint request.
- GA4 Fetch / OpenAI Generate.
- Token exchange implementation.
- Token storage implementation.
- Refresh implementation.
- Revoke implementation.
- Reconnect UI implementation.
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

## Verification

Planned verification for this docs-only step:

- `git diff --check`
- `git diff --name-only`
- `git diff --stat`
- `git status --short --untracked-files=all`

No Plugin Check rerun, package rebuild, external API communication, Google
authorization redirect, authorization URL construction, token endpoint request,
revoke endpoint request, GA4 Fetch, OpenAI Generate, credential inspection,
option value inspection, screenshot capture, or browser/network evidence
collection is part of this step.
