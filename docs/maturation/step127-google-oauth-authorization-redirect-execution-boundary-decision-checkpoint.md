# Step 127: Google OAuth Authorization Redirect Execution Boundary Decision Checkpoint

## Step Summary

Step 127 is a docs-only decision checkpoint before Google authorization
redirect execution.

This step clarifies redirect execution, browser OAuth navigation, external
Google navigation, QA boundaries, support/debug evidence rules, and the next
implementation slice scope. It does not change code, redirect to Google, call
external APIs, exchange tokens, store tokens, refresh tokens, revoke access, or
change credential storage.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step126-google-oauth-authorization-url-construction-helper-boundary-results.md`
- `docs/maturation/step125-google-oauth-redirect-uri-generation-settings-display-copy-wording-results.md`
- `docs/maturation/step124-google-oauth-client-configuration-constants-status-boundary-implementation-results.md`
- `docs/maturation/step123-google-oauth-client-configuration-token-exchange-boundary-plan.md`
- `docs/maturation/step122-google-oauth-authorization-redirect-token-exchange-boundary-decision-checkpoint.md`
- `docs/maturation/step121-google-oauth-state-protection-callback-validation-boundary-implementation-results.md`

## Current Baseline

| Area | Baseline |
|---|---|
| Local OAuth connect / callback skeleton | Implemented in Step 120. |
| Temporary state generation / callback validation boundary | Implemented in Step 121. |
| Client configuration constants / status boundary | Implemented in Step 124. |
| Redirect URI generation / Settings display boundary | Implemented in Step 125. |
| Authorization URL construction helper boundary | Implemented in Step 126. |
| Authorization URL helper execution | Helper exists, but the current flow does not call it. |
| Google authorization redirect execution | Not implemented. |
| Token endpoint request | Not implemented. |
| Token exchange | Not implemented. |
| Token storage | Not implemented. |
| WordPress.org release | `Hold`. |

## Redirect Execution Options

### Option A: Add Google Authorization Redirect Execution Next, Without Token Exchange

| Category | Notes |
|---|---|
| Pros | Moves the OAuth flow to the first real browser-navigation step. Reuses the Step 121 state boundary and Step 126 authorization URL helper. Avoids token endpoint calls and token storage for now. |
| Cons | A visible browser redirect can look like a working connection before callback/token handling is complete. It also introduces external Google navigation before browser evidence rules are finalized. |
| Security / privacy risk | Medium. Browser URL, provider screens, state, client ID, redirect URI, and provider error details can leak through screenshots, browser history, logs, or support evidence. |
| QA impact | Medium to high. Source review is straightforward, but browser verification needs a controlled evidence plan. |
| External navigation impact | High. Executing the flow navigates the administrator's browser to Google. |
| Rework risk | Medium. If browser QA/evidence boundaries change later, UI wording and verification steps may need revision. |
| Release readiness impact | Useful progress, but not sufficient without token exchange, storage, expiry, revoke/reconnect, uninstall cleanup, and QA. |
| Recommendation | Not recommended as the immediate next step. |

### Option B: Add Redirect Execution And Token Exchange Boundary Skeleton In One Slice

| Category | Notes |
|---|---|
| Pros | Could define more of the end-to-end OAuth path in one implementation slice while still avoiding real token endpoint calls. |
| Cons | Too broad for the current boundary. It combines redirect execution, callback handling changes, token exchange skeleton, error categories, and future storage assumptions. |
| Security / privacy risk | High. This increases the chance of accidental authorization code, client secret, state, provider error, or token evidence exposure. |
| QA impact | High. Even without real token endpoint calls, source review and browser behavior become harder to isolate. |
| External navigation impact | High if redirect execution is reachable. |
| Rework risk | High. Token exchange skeleton decisions may need to change after browser OAuth verification boundaries are finalized. |
| Release readiness impact | Potentially useful later, but premature before controlled browser OAuth evidence rules are settled. |
| Recommendation | Not recommended. |

### Option C: Create A Controlled Browser OAuth Verification And Evidence Boundary Plan

| Category | Notes |
|---|---|
| Pros | Keeps this phase docs-only and settles browser OAuth execution rules before enabling external Google navigation. Separates source review from human browser verification. Reduces evidence leakage risk. |
| Cons | Adds one more planning step before visible OAuth behavior advances. |
| Security / privacy risk | Low for the checkpoint itself because no code, browser OAuth execution, external API call, token exchange, or token storage occurs. |
| QA impact | Positive. Defines what can be verified by source review and what requires explicit human approval. |
| External navigation impact | None in this step. Future external navigation can be gated by the plan. |
| Rework risk | Low. Clear evidence rules should reduce later UI/support/docs churn. |
| Release readiness impact | Strong planning progress for a high-risk release blocker without widening runtime behavior. |
| Recommendation | Recommended. |

### Option D: Defer Redirect Execution And Return To OpenAI Storage Or Uninstall Cleanup

| Category | Notes |
|---|---|
| Pros | Avoids browser OAuth complexity for now and could advance adjacent credential blockers. |
| Cons | Leaves the Google OAuth browser-navigation boundary unresolved. OpenAI storage and uninstall cleanup may need to align with later Google token lifecycle decisions. |
| Security / privacy risk | Low for the decision itself, but the main Google OAuth blocker remains open. |
| QA impact | Low to medium depending on the alternate slice. |
| External navigation impact | None if the alternate slice stays local. |
| Rework risk | Medium. Adjacent credential work may need revision after Google OAuth execution/storage decisions. |
| Release readiness impact | Partial progress only; the primary OAuth blocker remains. |
| Recommendation | Not recommended as the immediate next step. |

## Recommended Decision

Recommended: Option C - create a controlled browser OAuth verification and
evidence boundary plan before enabling redirect execution.

Rationale:

- Authorization redirect execution is local code, but running it causes external
  Google browser navigation.
- OAuth consent screens, provider screens, browser URLs, query parameters, and
  callback URLs can be captured by screenshots, browser history, logs, support
  messages, or Network evidence.
- Step 126 and earlier OAuth slices deliberately avoided external API calls and
  browser OAuth execution.
- Source review and human browser verification should be separated before
  enabling redirect execution.
- Deciding evidence rules first reduces the risk of recording full
  authorization URLs, raw state values, client ID values, hostname/domain,
  provider errors, or authorization codes.

## Controlled Browser OAuth Verification Plan Topics

Step 128 should define:

- whether redirect execution implementation can be source-reviewed without
  browser execution,
- when human browser OAuth execution is allowed,
- who provides Google client constants,
- whether the local development redirect URI is acceptable for Google OAuth
  client setup,
- what must not be recorded during browser OAuth,
- no screenshots by default,
- no browser Network tab evidence,
- no full authorization URL recording,
- no authorization code, raw state, or provider error recording,
- status-level evidence only,
- rollback or abort behavior if redirect execution behaves unexpectedly,
- how to verify no token exchange occurs,
- how to verify callback returns status-level results only,
- how to keep GA4 Fetch and OpenAI Generate out of scope.

## Proposed Next Steps

| Step | Scope |
|---|---|
| Step 128 | Controlled browser OAuth verification and evidence boundary plan, docs-only. |
| Step 129 | Google authorization redirect execution implementation, source review only, no browser execution unless separately approved. |
| Step 130 | Human-controlled browser OAuth redirect smoke, if explicitly approved, status-level evidence only, no token exchange, no screenshots, no Network evidence. |

## Recommended Next Step

Recommended next step:

```text
Step 128: Controlled browser OAuth verification and evidence boundary plan
```

Step 128 should remain docs-only. It should finalize browser OAuth
verification, external Google navigation, and evidence rules before any redirect
execution implementation.

## Support / Debug Evidence Boundary

The support/debug evidence boundary remains:

- Do not record credentials, API keys, access tokens, or Authorization headers.
- Do not record client secrets.
- Do not record client ID values.
- Do not record plugin option values.
- Do not record full authorization URLs.
- Do not record authorization codes, raw state values, or raw provider errors.
- Do not record token endpoint requests or raw token responses.
- Do not record request bodies, raw responses, AI payload JSON, OpenAI request
  bodies, or generated report bodies.
- Do not record GA4 Property ID, hostname/domain, analytics values, page path,
  source, or city values.
- Do not use screenshots or browser Network tab data by default.
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
- No credentials, client secret, client ID value, authorization URL, raw state,
  authorization code, provider error, option value, hostname/domain, analytics
  value, payload, raw response, generated report body, screenshot, browser
  Network data, cookie, session, or nonce value was recorded.
