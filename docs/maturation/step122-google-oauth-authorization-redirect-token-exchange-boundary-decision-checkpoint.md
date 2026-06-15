# Step 122: Google OAuth Authorization Redirect / Token Exchange Boundary Decision Checkpoint

## Step Summary

This step records a docs-only decision checkpoint for the Google OAuth
authorization redirect and token exchange boundary before implementation
continues.

Step 120 implemented the local OAuth connect / callback skeleton. Step 121
implemented temporary OAuth state generation and callback validation
boundaries. Before adding Google authorization redirects, token endpoint
requests, token response handling, or credential storage, this step clarifies
the unresolved client configuration, redirect URI, scope, token exchange,
client secret, storage, QA, and support/debug evidence boundaries.

This step does not change code, redirect to Google, call a token endpoint,
call a revoke endpoint, exchange tokens, store tokens, refresh tokens, revoke
access, add reconnect UI, change GA4 client behavior, change OpenAI storage,
change Settings save logic, create `uninstall.php`, rebuild packages, rerun
Plugin Check, or call external APIs.

WordPress.org release remains `Hold`.

## Referenced Docs

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
| Local OAuth connect skeleton | Implemented in Step 120. |
| Local OAuth callback skeleton | Implemented in Step 120. |
| Temporary OAuth state generation | Implemented in Step 121. |
| Temporary state storage | User-scoped transient storing a state hash, not the raw state value. |
| Raw state value visibility | Raw state value is not displayed or recorded. |
| Callback query handling | `state`, `code`, and `error` are classified only as status-level categories. |
| Google authorization redirect | Not implemented. |
| Google token endpoint request | Not implemented. |
| Token exchange | Not implemented. |
| Token storage | Not implemented. |
| Manual Google Access Token entry | Still developer verification only. |
| WordPress.org release | `Hold`. |

Step 121 gives the project a local state and callback validation boundary. It
does not yet define how Google OAuth client configuration, authorization URL
construction, token exchange, token response normalization, or token storage
should be handled.

## Boundary Problem

The next code implementation cannot safely proceed until these boundaries are
resolved:

- Where Google OAuth client ID and client secret are managed.
- How the redirect URI is generated and validated.
- Where the Google authorization URL is constructed.
- Which OAuth scopes are requested.
- How the raw state value is carried into the Google redirect while still
  avoiding logs, docs, screenshots, and support evidence.
- Which layer receives the authorization code and passes it to token exchange.
- Whether client secret is stored in plugin settings or provided through
  constant-based configuration.
- Which layer normalizes token responses.
- Where access token, refresh token if adopted, and `expires_at` are stored.
- How token exchange failures are mapped to safe error categories.
- What information is allowed or forbidden in support/debug evidence.

These decisions connect directly to credential storage posture, support
evidence, uninstall cleanup, Settings wording, and future QA.

## Authorization Redirect Options

### Option A: Add Google Authorization Redirect Next, Without Token Exchange

| Category | Notes |
|---|---|
| Pros | Moves the OAuth flow one visible step forward. Can reuse the Step 121 state boundary. Avoids token endpoint calls and token storage for now. |
| Cons | Still requires client ID source, redirect URI, scope, and raw state lifecycle decisions. A redirect-only flow may look user-facing before token exchange and storage are ready. |
| Security / privacy risk | Medium. Raw state handling and redirect URI construction become security-sensitive even without token exchange. |
| Rework risk | Medium to high if client configuration, redirect URI, or scope decisions change later. |
| QA impact | Medium. Safe verification would still need to avoid real browser OAuth execution unless explicitly approved. |
| External API impact | A real redirect would involve Google browser navigation, so external interaction would need a later approved QA boundary. |
| Release readiness impact | Medium. Useful progress, but incomplete without token exchange, storage, expiry, reconnect, and cleanup decisions. |
| Recommendation | Not recommended as the immediate next step. |

### Option B: Add Authorization Redirect And Token Exchange In The Same Slice

| Category | Notes |
|---|---|
| Pros | Could create a more complete OAuth connection path in fewer implementation steps. Clarifies redirect, callback, token exchange, and storage as one flow. |
| Cons | Too broad for the current maturity phase. It combines client configuration, redirect, token endpoint request, client secret handling, response normalization, token storage, error mapping, and QA. |
| Security / privacy risk | High. This is the point where client secret, authorization code, access token, refresh token, and raw provider errors can accidentally leak or be stored incorrectly. |
| Rework risk | High if storage, refresh/reconnect, uninstall cleanup, or support evidence boundaries are not settled first. |
| QA impact | High. It would require tightly controlled external API and browser OAuth verification later. |
| External API impact | Requires external Google authorization and token endpoint behavior if executed. |
| Release readiness impact | Potentially high eventually, but risky and premature without a detailed boundary plan. |
| Recommendation | Not recommended. |

### Option C: Detail Google OAuth Client Configuration And Token Exchange Boundary Before Code

| Category | Notes |
|---|---|
| Pros | Keeps the next step docs-only while resolving client ID, client secret, redirect URI, scope, raw state lifecycle, token exchange, token response normalization, storage, and safe error mapping. Reduces rework before credential-bearing implementation. |
| Cons | Adds one more planning step before visible OAuth behavior advances. |
| Security / privacy risk | Low for the checkpoint itself because no code, redirect, token exchange, token storage, or external API call is performed. |
| Rework risk | Low. It should reduce downstream rework in credential storage, support evidence, uninstall cleanup, and QA. |
| QA impact | Low. Verification stays at docs/source-review level. |
| External API impact | None. |
| Release readiness impact | High as planning evidence. It prepares the project for safer implementation of the next credential blocker slice. |
| Recommendation | Recommended. |

### Option D: Pause Google OAuth And Move To OpenAI Storage Or Uninstall Cleanup

| Category | Notes |
|---|---|
| Pros | Avoids immediate Google OAuth complexity. Could advance other credential blockers. |
| Cons | Google OAuth remains the largest public-release credential blocker. OpenAI storage and uninstall cleanup are easier to align after Google credential boundaries are clearer. |
| Security / privacy risk | Low for this decision, but unresolved Google boundaries continue to block release readiness. |
| Rework risk | Medium. OpenAI storage or uninstall cleanup may need to be revisited after Google token storage and lifecycle decisions are made. |
| QA impact | Low to medium depending on the alternate implementation slice. |
| External API impact | None if the alternate slice stays local. |
| Release readiness impact | Medium. It may close adjacent blockers, but leaves the primary Google OAuth blocker untouched. |
| Recommendation | Not recommended as the immediate next step. |

## Recommended Decision

Recommended: Option C: create a detailed Google OAuth client configuration and
token exchange boundary plan before adding authorization redirect or token
exchange code.

Rationale:

- Authorization redirect implementation needs an accepted client ID source,
  redirect URI, scope, and raw state value lifecycle.
- Token exchange implementation needs accepted client secret handling, token
  endpoint boundary, token response normalization, token storage categories,
  and safe error mapping.
- Entering code before these decisions are explicit increases rework risk
  across credential storage, support evidence, and uninstall cleanup.
- Step 121 already established a local state validation boundary, so the next
  safe step is detailed design rather than redirect or token exchange code.
- External API communication should still remain out of scope.

## Google OAuth Client Configuration Topics To Resolve

The next detailed design should cover:

- Client ID source.
- Client secret source.
- Constant-based configuration option.
- Settings UI configuration option, if any.
- Whether client secret should be stored in plugin settings.
- Redirect URI generation.
- Redirect URI display / copy behavior.
- Required Google scopes.
- Authorization URL construction.
- State raw value lifecycle during redirect.
- Callback validation order.
- Authorization code handling boundary.
- Token exchange boundary.
- Token response normalization.
- Access token storage category.
- Refresh token storage category, if adopted.
- `expires_at` storage.
- Reconnect-only versus refresh-token strategy.
- Disconnect / revoke relationship.
- Safe error mapping.
- Support/debug evidence boundary.
- QA plan without recording secrets.

## Recommended Next Step

Recommended next step:

```text
Step 123: Google OAuth client configuration and token exchange boundary plan
```

Step 123 should also be docs-only. It should not change code, redirect to
Google, call external APIs, exchange tokens, store tokens, inspect credential
or option values, rebuild packages, or rerun Plugin Check.

## Support / Debug Evidence Boundary

The support/debug evidence boundary remains:

- Do not record credentials, API keys, access tokens, or Authorization headers.
- Do not record client secrets.
- Do not record option values.
- Do not record authorization codes, raw state values, or raw provider errors.
- Do not record request bodies, raw responses, AI payload JSON, or generated
  report bodies.
- Do not record GA4 Property ID, hostname/domain, analytics values, page path,
  source, or city values.
- Keep support evidence to status-level labels, redacted saved-state,
  error category, connection state, generation allowed/blocked state, and safe
  UI wording.
- Do not use screenshots or browser Network tab data by default.
- Future OAuth verification must also avoid recording secrets, identifiers,
  analytics values, raw responses, and generated report bodies.

## Explicit Non-goals

- Code change.
- `readme.txt` change.
- `.distignore` / build script change.
- Package rebuild.
- Plugin Check rerun.
- External API call.
- Google authorization redirect.
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
authorization redirect, token endpoint request, revoke endpoint request, GA4
Fetch, OpenAI Generate, credential inspection, option value inspection,
screenshot capture, or browser/network evidence collection is part of this
step.
