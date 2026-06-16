# Step 138: Human-Controlled OAuth Scope Category UI Recheck Results

## Step Summary

Step 138 records human-controlled OAuth scope category UI recheck results as
status-level evidence only.

This step follows Step 136 and Step 137. Step 137 confirmed the source-defined
requested scope category as `analytics_readonly_scope_expected`. Step 138 adds
human-provided status-level results for the Google Auth Platform Data Access UI
category and the consent screen displayed scope category.

This is a docs-only result recording step. Production PHP, JavaScript, CSS,
assets, `readme.txt`, build scripts, release package files, settings save
logic, GA4 client behavior, OpenAI client behavior, OAuth behavior, token
lifecycle behavior, and admin UI behavior were not changed.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step137-source-defined-oauth-scope-category-recheck-results.md`
- `docs/maturation/step136-oauth-scope-category-follow-up-plan.md`
- `docs/maturation/step135-human-controlled-oauth-provider-configuration-recheck-results.md`
- `docs/maturation/step134-google-oauth-provider-configuration-error-triage-plan.md`

## Evidence Boundary

Only normalized human-provided status-level evidence is recorded.

This document does not record:

- authorization URLs,
- browser address bar URLs,
- callback URLs,
- query strings,
- raw state values,
- authorization codes,
- raw provider errors,
- provider error codes,
- actual scope strings,
- actual scope-bearing URLs or query values,
- raw Google screen text,
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
- generated report bodies,
- email addresses or Google account identifiers,
- project IDs or project identifiers.

## Step 137 Baseline

The source-defined scope category result from Step 137 is carried forward as:

| Field | Status-Level Result |
|---|---|
| Source-defined requested scope category reviewed | Yes |
| Source-defined scope category result | `analytics_readonly_scope_expected` |

## Step 138-A: Google Auth Platform Data Access UI Category Result

Human-provided status-level result:

| Field | Status-Level Result |
|---|---|
| Google Auth Platform Data Access UI category reviewed | Yes |
| Google Data Access category result | `analytics_readonly_scope_expected` |
| Forbidden evidence recorded | No |

No actual Google Console values, raw UI text, screenshots, project identifiers,
account identifiers, client values, URLs, query values, or scope-bearing values
are recorded.

## Step 138-B0: Local Constants Preparation Status

Human-provided status-level result:

| Field | Status-Level Result |
|---|---|
| `wp-config.php` constants added | Yes |
| PHP syntax check passed | Yes |
| Client ID value shared | No |
| Client secret value shared | No |
| Forbidden evidence recorded | No |

This records only local preparation status. It does not record constant values,
client ID values, client secret values, plugin settings option values, host
values, screenshots, or command output containing sensitive information.

## Step 138-B: Consent Screen Displayed Scope Category Result

Human-provided status-level result:

| Field | Status-Level Result |
|---|---|
| Consent screen displayed scope category reviewed | No |
| Consent screen scope category result | `scope_category_not_confirmed` |
| Connect action was triggered from Settings | Yes |
| Redirect attempt occurred | Yes |
| Browser reached Google OAuth-related screen | Yes |
| Returned to Analytics Report AI Settings manually | Yes |
| Google connection state shown in Settings after return | `not_connected` |
| WordPress admin notice category | `no_notice_observed` |
| Authorization approval was completed | No |
| Authorization code was observed/recorded | No |
| Token exchange occurred | No |
| Token was stored | No |
| Forbidden evidence recorded | No |

The consent screen displayed scope category remains unconfirmed because the
human tester did not confirm the displayed scope category at status level. No
raw Google screen text, actual scope string, screenshot, browser URL, query
string, account identifier, provider raw error, or provider error code is
recorded.

## Final Status-Level Result

| Source | Status-Level Result |
|---|---|
| Source-defined scope category result | `analytics_readonly_scope_expected` |
| Google Data Access category result | `analytics_readonly_scope_expected` |
| Consent screen scope category result | `scope_category_not_confirmed` |
| Scope category final status-level result | `analytics_readonly_scope_expected_with_consent_screen_not_confirmed` |

Interpretation: production source and Google Auth Platform Data Access UI were
confirmed at status level as the Analytics read-only category. The Google
consent screen displayed scope category remains unconfirmed because the human
tester did not confirm it before stopping the smoke.

## OAuth Lifecycle Boundary

Authorization approval was not completed. Authorization code was not observed
or recorded. Token exchange did not occur. Token storage did not occur.

After manually returning to Analytics Report AI Settings, the Google connection
state shown in the Settings UI was recorded as:

```text
not_connected
```

The following remain out of scope and were not executed or implemented in this
step:

- token endpoint requests,
- revoke endpoint requests,
- token exchange,
- token storage,
- token refresh,
- token revoke,
- reconnect UI,
- GA4 Fetch,
- OpenAI Generate.

## Local Configuration Safety Note

If the next work does not proceed to a controlled OAuth approval, callback, or
token exchange step, it is safer to remove the local OAuth client ID and client
secret constants from `wp-config.php` after this smoke sequence. Record only
whether local constants are present or removed at status level. Do not record
the constant values.

## CODEX Execution Boundary

CODEX did not run browser OAuth, navigate to Google, operate Google Cloud
Console, call external APIs, approve OAuth authorization, execute callback
handling, exchange tokens, store tokens, refresh tokens, revoke tokens, run GA4
Fetch, run OpenAI Generate, run Plugin Check, or access `wp-dev-check`.

CODEX did not generate, display, inspect, or record authorization URLs, browser
URLs, callback URLs, query strings, scope-bearing URLs, raw Google screen text,
client ID values, client secret values, hostnames, credentials, tokens, option
values, screenshots, browser Network evidence, request bodies, payloads, raw
responses, or generated report bodies.

## Known Limitations

- Consent screen displayed scope category remains `scope_category_not_confirmed`.
- The final result is therefore
  `analytics_readonly_scope_expected_with_consent_screen_not_confirmed`, not a
  fully confirmed end-to-end scope UI result.
- No authorization approval, callback execution, token exchange, token storage,
  token refresh, revoke, reconnect UI, GA4 Fetch, or OpenAI Generate behavior
  was tested.

## Next Step Candidates

Recommended next step if continuing the scope evidence path:

```text
Step 139: Human-controlled consent screen scope category recheck
```

Purpose: confirm the consent screen displayed scope category at status level
only, without completing authorization approval and without recording raw screen
text, scope strings, URLs, query values, screenshots, Network evidence, account
identifiers, or provider raw evidence.

Alternative next step if moving toward the OAuth lifecycle path:

```text
Step 139: OAuth approval and callback boundary decision checkpoint
```

Purpose: define an evidence boundary before any approval, callback, token
exchange, token storage, refresh, revoke, or reconnect UI work.

## Confirmation Commands

Planned docs-only confirmation commands:

```bash
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```
