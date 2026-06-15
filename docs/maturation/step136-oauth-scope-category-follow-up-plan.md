# Step 136: OAuth Scope Category Follow-Up Plan

## Step Summary

Step 136 creates a docs-only follow-up plan for the remaining OAuth scope
category uncertainty recorded in Step 135.

Step 135 improved the human-controlled browser smoke result to the
`google_oauth_screen_reached` category, but the scope category result remained:

```text
not_found_or_not_sure
```

This plan defines how to confirm the OAuth scope category safely without
recording scope-bearing URLs, authorization URLs, query strings, account
identifiers, client values, screenshots, Network evidence, or other forbidden
evidence.

This step does not change production PHP, JavaScript, CSS, assets,
`readme.txt`, build scripts, release package files, settings save logic, GA4
client behavior, OpenAI client behavior, OAuth behavior, token lifecycle
behavior, or admin UI behavior.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step135-human-controlled-oauth-provider-configuration-recheck-results.md`
- `docs/maturation/step134-google-oauth-provider-configuration-error-triage-plan.md`
- `docs/maturation/step133-human-controlled-configured-oauth-redirect-smoke-result-recording.md`
- `docs/maturation/step132-configured-environment-oauth-redirect-smoke-plan.md`
- `docs/maturation/step131-human-provided-oauth-redirect-smoke-result-recording.md`

## Evidence Boundary

Only status-level scope category evidence should be recorded.

Allowed evidence examples:

- `source_scope_category_reviewed`,
- `google_data_access_category_reviewed`,
- `consent_screen_scope_category_reviewed`,
- `analytics_readonly_scope_expected`,
- `scope_category_not_confirmed`,
- `unexpected_scope_category_suspected`,
- `authorization_approval_completed_no`,
- `forbidden_evidence_recorded_no`.

Forbidden evidence:

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
- generated report bodies,
- email addresses or Google account identifiers,
- project IDs or project identifiers,
- actual scope-bearing URLs or query values.

## Follow-Up Review Areas

| Review Area | Purpose | Evidence Allowed | Evidence Not Allowed |
|---|---|---|---|
| Source-defined requested scope category review | Confirm whether the production source helper that defines requested OAuth scope category is intended to request the Analytics read-only category. | `source_scope_category_reviewed`, `analytics_readonly_scope_expected`, `scope_category_not_confirmed`, `unexpected_scope_category_suspected` | Authorization URL, query string, generated URL output, raw scope-bearing URL, client values, option values. |
| Google Auth Platform Data Access UI category review | Confirm whether Google-side Data Access category appears consistent with Analytics read-only access at status level. | `google_data_access_category_reviewed`, `analytics_readonly_scope_expected`, `scope_category_not_confirmed`, `unexpected_scope_category_suspected` | Actual Console values, screenshots, project identifiers, account identifiers, raw UI text copied from the provider. |
| Consent screen displayed scope category review | Confirm whether the Google OAuth-related screen displays an expected permission category during a human browser smoke. | `consent_screen_scope_category_reviewed`, `analytics_readonly_scope_expected`, `scope_category_not_confirmed`, `unexpected_scope_category_suspected` | Browser URL, authorization URL, query string, screenshot, raw screen text, account identifiers, approval completion. |
| Mismatch handling when Google Console UI label is unclear | Decide how to record ambiguous Google-side labels without collecting raw provider evidence. | `scope_category_not_confirmed`, `unexpected_scope_category_suspected`, `needs_follow_up` | Raw provider error, provider error code, screenshots, project identifiers, copied Console values. |
| Decision boundary before authorization approval | Keep the next smoke limited to scope category confirmation before any authorization approval. | `authorization_approval_completed_no`, `authorization_code_recorded_no`, `token_exchange_occurred_no`, `token_stored_no` | Authorization approval, authorization code, callback URL, token endpoint evidence, token values. |

## Source-Defined Requested Scope Category Review

The source-defined review should inspect the production source helper or
constant path that defines the requested OAuth scope category.

The review goal is limited to this question:

```text
Does the source-defined requested scope category match the intended Analytics read-only category?
```

Allowed status-level outcomes:

- `source_scope_category_reviewed`
- `analytics_readonly_scope_expected`
- `scope_category_not_confirmed`
- `unexpected_scope_category_suspected`

The review must not generate, display, or record authorization URLs, callback
URLs, query strings, raw state values, browser URLs, actual scope-bearing URLs,
or actual query values.

## Google Auth Platform Data Access UI Category Review

The Google-side Data Access UI category review should be performed by the human
tester if needed. CODEX must not operate Google Cloud Console.

If the Google UI label is clear, record only a status-level category such as:

- `analytics_readonly_scope_expected`

If the Google UI label is unclear, do not copy raw Google UI text and do not
record screenshots. Instead, record one of:

- `scope_category_not_confirmed`
- `unexpected_scope_category_suspected`

The review must not record actual Console values, project identifiers, account
identifiers, client values, screenshots, raw provider errors, or provider error
codes.

## Consent Screen Displayed Scope Category Review

If the next human-controlled browser smoke reaches a Google OAuth-related
screen, the human tester may visually confirm the displayed permission category
before authorization approval.

The smoke must stop before approval. It must not complete authorization,
observe or record an authorization code, trigger callback handling, exchange
tokens, or store tokens.

Allowed status-level outcomes:

- `consent_screen_scope_category_reviewed`
- `analytics_readonly_scope_expected`
- `scope_category_not_confirmed`
- `unexpected_scope_category_suspected`

The review must not record screen text, URLs, query strings, screenshots,
Network evidence, account identifiers, provider raw errors, or provider error
codes.

## Mismatch Handling

If the source-defined review, Google Auth Platform review, and consent screen
review do not align, record the mismatch at category level only.

Allowed mismatch outcomes:

- `scope_category_not_confirmed`
- `unexpected_scope_category_suspected`
- `needs_follow_up`

Do not resolve ambiguity by copying forbidden evidence into docs, support
messages, screenshots, or issue descriptions. If deeper diagnosis is required,
create a separate evidence-boundary plan before collecting additional material.

## Decision Boundary Before Authorization Approval

Step 137 should not complete authorization approval. The confirmation boundary
is:

```text
Scope category status-level confirmation before approval.
```

The next step may record whether a human reached a Google OAuth-related screen
and visually confirmed the scope category at status level. It must not record
authorization codes, callback URLs, query strings, token endpoint evidence,
token values, or provider raw evidence.

## Human-Side Observation Template For Step 137

Use this status-level template for the next human-controlled scope category
recheck:

```text
- Source-defined requested scope category reviewed: Yes / No
- Source-defined scope category result:
  - analytics_readonly_scope_expected
  - scope_category_not_confirmed
  - unexpected_scope_category_suspected
  - not_reviewed
- Google Auth Platform Data Access UI category reviewed: Yes / No
- Google Data Access category result:
  - analytics_readonly_scope_expected
  - scope_category_not_confirmed
  - unexpected_scope_category_suspected
  - not_reviewed
- Consent screen displayed scope category reviewed: Yes / No / Not reached
- Consent screen scope category result:
  - analytics_readonly_scope_expected
  - scope_category_not_confirmed
  - unexpected_scope_category_suspected
  - not_reached
  - not_reviewed
- Scope category final status-level result:
  - analytics_readonly_scope_expected
  - scope_category_not_confirmed
  - unexpected_scope_category_suspected
- Authorization approval was completed: No
- Authorization code was observed/recorded: No
- Token exchange occurred: No
- Token was stored: No
- Forbidden evidence recorded: No
```

Do not add actual URLs, query values, account identifiers, client values,
provider raw errors, provider error codes, screenshots, Network evidence,
scope-bearing URLs, or raw screen text to this template.

## Step 137 Boundary

Recommended next step:

```text
Step 137: Human-controlled OAuth scope category recheck
```

Step 137 should remain status-level. It may record the source-defined category
review, Google Auth Platform Data Access UI category review, and consent screen
displayed scope category review. It must not complete authorization approval.

Step 137 must not observe or record authorization URLs, browser URLs, callback
URLs, query strings, raw state values, authorization codes, raw provider errors,
provider error codes, client ID values, client secret values, hostnames,
screenshots, Network evidence, cookies, sessions, nonces, credentials, option
values, GA4 Property IDs, analytics values, payloads, raw responses, generated
report bodies, email addresses, Google account identifiers, project IDs, or
actual scope-bearing URLs or query values.

## Out Of Scope

The following remain out of scope and were not executed or implemented in this
step:

- browser OAuth by CODEX,
- Google navigation by CODEX,
- Google Cloud Console operation by CODEX,
- external API communication,
- OAuth authorization approval,
- callback execution,
- token endpoint requests,
- revoke endpoint requests,
- token exchange,
- token storage,
- token refresh,
- token revoke,
- reconnect UI,
- GA4 Fetch,
- OpenAI Generate,
- Plugin Check,
- `wp-dev-check` access.

## Confirmation Commands

Planned docs-only confirmation commands:

```bash
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```
