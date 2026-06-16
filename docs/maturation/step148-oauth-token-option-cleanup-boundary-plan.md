# Step 148: OAuth Token Option Cleanup Boundary Plan

## Step Summary

Step 148 creates a docs-only cleanup boundary plan for OAuth token material that
may exist in the local development database after the Step 147 human-controlled
token exchange smoke.

This step does not execute cleanup, delete options, inspect option values, dump
the database, execute browser OAuth, navigate to Google, operate Google Cloud
Console, execute callbacks, call token endpoints, exchange tokens, inspect token
storage data, refresh tokens, revoke tokens, run GA4 Fetch, run OpenAI Generate,
run Plugin Check, or access `wp-dev-check`.

This step does not change production PHP, JavaScript, CSS, assets, `readme.txt`,
build scripts, package files, settings save logic, GA4 behavior, OpenAI
behavior, OAuth behavior, token storage behavior, or admin UI behavior.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step147-human-controlled-token-exchange-smoke-results.md`
- `docs/maturation/step146-controlled-token-exchange-smoke-plan.md`
- `docs/maturation/step145-narrow-token-exchange-production-implementation-results.md`
- `docs/maturation/step143-token-storage-lifecycle-decision-checkpoint.md`

## Background

Step 147 recorded a human-controlled smoke result where:

- token exchange status category: `token_exchange_success_category`
- token storage status category: `token_storage_success_category`
- Settings connection state after return: `connected`

Those are status-level results only. They imply that real OAuth token material
may exist in the local development database, but this document does not inspect,
display, record, dump, or otherwise confirm the underlying option value.

## Cleanup Targets

Potential cleanup targets, recorded at status level only:

- dedicated OAuth token option: `analytics_report_ai_oauth_tokens`
- OAuth state transients
- token-related connection state markers, if any
- token-related admin notice leftovers, if any

No option values, serialized values, token values, token fragments, transient
values, database rows, screenshots, or Network evidence should be used to
identify or confirm these targets.

## Allowed Cleanup Evidence

Cleanup evidence should remain status-level only.

Allowed examples:

- `option_delete_attempted_yes_no`
- `option_delete_result_category`
- `state_transient_cleanup_attempted_yes_no`
- `cleanup_completed_status_category`
- `forbidden_evidence_recorded_no`

Allowed result categories may include:

- `option_deleted_category`
- `option_not_found_category`
- `option_delete_failed_category`
- `state_transient_cleanup_deferred_category`
- `state_transient_cleanup_completed_category`
- `cleanup_completed_category`
- `cleanup_partially_completed_category`
- `cleanup_not_executed`
- `unknown_cleanup_status`

## Forbidden Cleanup Evidence

The cleanup process must not use or record:

- `wp option get analytics_report_ai_oauth_tokens`
- database dumps
- serialized option output
- option value display
- token value display
- token fragment display
- access token display
- refresh token display
- screenshots
- browser Network evidence
- authorization URLs
- browser address bar URLs
- callback URLs
- query strings
- raw state values
- authorization codes
- raw provider errors
- provider error codes
- token endpoint request bodies
- token endpoint response bodies
- token endpoint URLs with parameters
- ID tokens
- token type values or token fragments tied to a real response
- real `expires_in` values from a real response
- Authorization headers
- plugin settings option values
- OAuth token option values
- serialized option values
- database rows containing token material
- client ID values
- client secret values
- hostname/domain values
- cookies, sessions, or nonce values
- credentials or API keys
- GA4 Property ID values
- analytics values
- request bodies
- AI payload JSON
- raw API responses
- generated report bodies
- email addresses or Google account identifiers
- project IDs or project identifiers

## Human And CODEX Execution Boundary

The future cleanup step should follow this execution boundary:

- CODEX may prepare cleanup commands only if they do not display values.
- CODEX may not execute cleanup against the user's local database unless
  explicitly instructed.
- If cleanup is executed by the human user, command output must not include
  option values.
- Cleanup result should be recorded only as a status-level result.
- Cleanup should not use `wp option get`, database dumps, raw SQL selection, or
  any command that prints stored values.
- Cleanup should not include screenshots or browser Network evidence.

## Cleanup Command Candidate

Candidate command for a future cleanup execution step:

```bash
wp option delete analytics_report_ai_oauth_tokens
```

This command candidate is included because it is intended to delete the option
without displaying the option value. Step 148 does not execute this command.

If this command is used in a later step, record only status-level output such
as whether deletion was attempted and whether the result category was deleted,
not found, failed, or unknown. Do not record the option value.

## State Transient Cleanup Boundary

OAuth state transient cleanup remains a decision item for the next cleanup step.

At this point:

- do not record transient names,
- do not record transient values,
- do not inspect transient payloads,
- do not dump database rows,
- treat state transient cleanup as a source-defined naming / prefix category
  review item.

A later cleanup step may decide whether a source-defined transient naming or
prefix category can be safely used for cleanup without printing transient
values.

## Post-Cleanup Confirmation Boundary

Cleanup confirmation should avoid option value inspection.

Allowed status-level confirmation examples:

- Settings connection state becomes `not_connected` / `unknown` / `not_visible`
- cleanup command reports deleted / not found category
- no option value displayed
- `forbidden_evidence_recorded_no`

Forbidden confirmation methods:

- `wp option get analytics_report_ai_oauth_tokens`
- database dump
- serialized option output
- token value output
- token fragment output
- screenshot or Network evidence
- any confirmation that prints credentials or stored material

## Next Step Options

| Option | Candidate | Summary | Notes |
|---|---|---|---|
| A | `Step 149: Human-controlled OAuth token option cleanup execution` | Execute or record human execution of value-hidden cleanup for the dedicated OAuth token option. | Recommended because Step 147 may have stored real OAuth token material in the local dev database. Local cleanup should be completed without viewing values before moving deeper into GA4 integration unless immediate integration work is intentional. |
| B | `Step 149: GA4 OAuth token integration boundary` | Plan how GA4 access would use the dedicated OAuth token option. | Useful later, but it may leave local token material in place without a cleanup posture if done first. |
| C | `Step 149: Refresh / reconnect / revoke lifecycle plan` | Plan refresh, reconnect, local disconnect, remote revoke, and lifecycle UI boundaries. | Important public-release work, but broader than immediate local cleanup after a successful storage smoke. |

Recommended next step:

```text
Step 149: Human-controlled OAuth token option cleanup execution
```

Rationale: Step 147's successful token storage smoke means real OAuth token
material may exist in the local dev database. Before GA4 OAuth token integration
or broader lifecycle work, local cleanup should be planned and, if the human
user chooses, executed without displaying, inspecting, dumping, logging, or
recording token values.

## Explicit Non-Goals

- production code changes
- JavaScript, CSS, asset, `readme.txt`, build script, or package changes
- cleanup execution
- option deletion
- option inspection
- `wp option get analytics_report_ai_oauth_tokens`
- database dump
- browser OAuth execution by CODEX
- Google navigation by CODEX
- Google Cloud Console operation by CODEX
- OAuth approval by CODEX
- callback live browser execution by CODEX
- token endpoint live communication by CODEX
- token exchange execution by CODEX
- token storage data inspection
- refresh implementation
- revoke implementation
- reconnect UI implementation
- uninstall cleanup implementation
- manual Google Access Token retirement
- GA4 use of the OAuth token option
- GA4 Fetch
- OpenAI Generate
- Plugin Check
- `wp-dev-check` access
- forbidden evidence recording
