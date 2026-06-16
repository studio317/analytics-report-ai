# Step 154: Controlled Credential Source Admin Smoke Plan

## Step Summary

Step 154 creates a docs-only and planning-only admin smoke plan for the GA4
credential source changes introduced in Step 152 and verified at source level in
Step 153.

This step does not execute the admin smoke. It does not run GA4 Fetch, OpenAI
Generate, browser OAuth, Google navigation, Google Cloud Console operations,
callback browser execution, token endpoint communication, token exchange, token
storage real data confirmation, refresh, revoke, Plugin Check, database dumps,
or `wp-dev-check` access.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step153-ga4-oauth-credential-source-source-level-verification-results.md`
- `docs/maturation/step152-narrow-ga4-oauth-credential-source-production-implementation-results.md`
- `docs/maturation/step151-ga4-oauth-token-integration-implementation-boundary.md`
- `docs/maturation/step150-ga4-oauth-token-integration-boundary.md`
- `docs/maturation/step149-human-controlled-oauth-token-option-cleanup-execution-results.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`

## Planning Scope

The planned smoke is limited to external-API-free WordPress admin UI checks:

- Settings page load does not fatal.
- Report Builder page load does not fatal.
- Credential source status label appears as a safe label.
- Missing credential behavior can be observed without GA4 Fetch when the
  current environment naturally allows it.
- Manual token saved/not-saved status remains value-hidden.
- OAuth connection state remains status-level only.
- No token value, option value, or Authorization header is displayed.

The planned smoke must not click GA4 Fetch, OpenAI Generate, or OAuth Connect.
It must not use browser DevTools, Network tab evidence, screenshots, database
dumps, `wp option get`, credential value inspection, or plugin settings option
value inspection.

## Baseline Assumptions

Status-level baseline for the future human smoke:

```text
Step 149 OAuth token option cleanup status: option_deleted_category
Local OAuth constants after Step 149: removed
Manual Google Access Token path: still preserved
OAuth state transients cleanup: deferred
Step 152 resolver implementation: present
Step 153 source-level verification: passed
```

These are baseline categories only. They do not prove or reveal current stored
option values, manual token values, OAuth token values, connection material,
client values, GA4 identifiers, hostnames, or analytics values.

## Human Smoke Method

The future human smoke should use a logged-in WordPress admin session and
confirm the relevant admin screens by visual inspection only.

Allowed screens:

- Analytics Report AI Settings screen.
- Analytics Report AI Report Builder screen.

Allowed observation type:

- status-level labels,
- page load success/failure,
- visible fatal error absence/presence,
- value-hidden credential field posture,
- safe UI state categories.

Disallowed actions:

- clicking GA4 Fetch,
- clicking OpenAI Generate,
- clicking OAuth Connect / Authorization,
- navigating to Google,
- operating Google Cloud Console,
- executing a callback in the browser,
- executing token endpoint communication,
- confirming token storage values,
- changing or deleting manual token values,
- changing or deleting OAuth token option values,
- using `wp option get analytics_report_ai_oauth_tokens`,
- displaying plugin settings option values,
- dumping the database,
- using screenshots,
- using browser DevTools / Network evidence,
- running Plugin Check,
- accessing `wp-dev-check`.

## Missing Credential Observation Notes

Missing credential behavior should be observed only if the current environment
naturally shows a missing credential state.

Do not delete, edit, or inspect manual token values, OAuth option values,
plugin settings option values, database rows, or OAuth state material to force a
missing credential condition in this smoke.

If the current admin UI cannot safely show a missing credential condition
without changing stored values, record:

```text
missing credential behavior: not_observed
```

The missing credential check must remain external-API-free and must not trigger
GA4 Fetch.

## Safe Label Normalization

When recording the human smoke result, normalize any localized UI wording to
one of these safe categories:

```text
credential_source_oauth_connected
credential_source_manual_token
credential_source_missing
credential_source_oauth_refresh_needed
credential_source_oauth_error_category
manual_token_fallback_used
not_visible
unknown_label
```

If the UI is localized or uses human-readable text, record the matching safe
label category only. Do not copy full screen text if it includes site-specific,
credential-related, analytics-related, or environment-specific details.

## Step 155 Human Observation Template

Use the following status-level template for the next result-recording step:

```text
Step 155 human admin smoke observation:
- Settings page loaded: Yes / No
- Report Builder page loaded: Yes / No
- Fatal error observed: No / Yes
- Credential source status label shown in Settings or Report Builder:
  credential_source_oauth_connected /
  credential_source_manual_token /
  credential_source_missing /
  credential_source_oauth_refresh_needed /
  credential_source_oauth_error_category /
  manual_token_fallback_used /
  not_visible /
  unknown_label
- Missing credential behavior observed without GA4 Fetch: Yes / No / not_observed
- Manual token saved/not-saved status remains value-hidden: Yes / No / not_visible
- OAuth connection state remains status-level only: Yes / No / not_visible
- Manual token value displayed: No
- OAuth token option value displayed: No
- Authorization header displayed: No
- Plugin settings option value displayed: No
- GA4 Fetch triggered: No
- OpenAI Generate triggered: No
- Browser OAuth triggered: No
- Token endpoint communication occurred: No
- Token storage real data inspected: No
- Network evidence recorded: No
- Screenshot recorded: No
- Forbidden evidence recorded: No
```

If a fatal error is observed, record only a short status-level category and
avoid copying environment-specific paths, option values, token values,
credentials, request bodies, raw responses, payload JSON, generated report
bodies, screenshots, or Network evidence.

## Evidence Safety

The future smoke result must not record:

- OAuth token option values,
- serialized option values,
- plugin settings option values,
- manual Google Access Token values,
- access tokens,
- refresh tokens,
- Authorization headers,
- token endpoint requests or responses,
- authorization codes,
- callback URLs,
- query strings,
- raw state values,
- raw provider errors,
- client ID values,
- client secret values,
- GA4 Property ID values,
- analytics values,
- request bodies,
- GA4 raw responses,
- AI payload JSON,
- OpenAI raw responses,
- generated report bodies,
- screenshots,
- browser Network evidence,
- database rows or database dumps,
- email addresses or Google account identifiers,
- project IDs or project identifiers,
- hostname/domain values.

## Planned Status Results

Step 154 records the following planning-only status:

```text
admin smoke executed by CODEX: No
GA4 Fetch executed by CODEX: No
OpenAI Generate executed by CODEX: No
browser OAuth executed by CODEX: No
token endpoint executed by CODEX: No
Plugin Check executed by CODEX: No
OAuth token option value inspected/displayed/recorded: No
manual token value inspected/displayed/recorded: No
plugin settings option value inspected/displayed/recorded: No
planned evidence type: status_level_only
forbidden evidence recorded: No
```

## Next Step Options

| Option | Candidate | Result |
|---|---|---|
| A | `Step 155: Human-controlled credential source admin smoke result recording` | Recommended |
| B | `Step 155: Human-controlled GA4 OAuth credential source smoke plan` | Later |
| C | `Step 155: Refresh / reconnect lifecycle plan` | Later |

Recommended next step:

```text
Step 155: Human-controlled credential source admin smoke result recording
```

Rationale:

- Step 154 is planning-only.
- Step 152 changed production PHP and Step 153 verified the implementation at
  source level.
- Before any real GA4 Fetch or OAuth smoke, the safer next step is a
  human-controlled admin UI smoke that confirms page load, safe status labels,
  fatal absence, and value-hidden credential posture without external API
  communication.
- Real GA4 OAuth Fetch smoke, refresh, reconnect, revoke, and token lifecycle
  work should remain separate later steps.

## Explicit Non-Goals

- production PHP changes,
- JavaScript, CSS, asset, `readme.txt`, build script, or package changes,
- admin smoke execution by CODEX,
- GA4 Fetch,
- OpenAI Generate,
- browser OAuth,
- Google navigation,
- Google Cloud Console operation,
- OAuth authorization,
- callback browser execution,
- token endpoint communication,
- token exchange,
- token storage real data confirmation,
- refresh,
- revoke,
- Plugin Check,
- `wp-dev-check` access,
- `wp option get analytics_report_ai_oauth_tokens`,
- plugin settings option value display,
- manual token value display,
- database dump,
- screenshots,
- browser DevTools / Network evidence.
