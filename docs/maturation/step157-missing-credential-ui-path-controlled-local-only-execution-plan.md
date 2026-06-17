# Step 157: Missing Credential UI Path Controlled Local-Only Execution Plan

## Step Summary

Step 157 creates a docs-only, planning-only, future execution plan for a
controlled local-only check of the missing-credential UI path.

Step 157 does not execute the check. It does not change production PHP,
JavaScript, CSS, `readme.txt`, tools, assets, build scripts, or package files.
It does not create helpers, mu-plugins, test scripts, or temporary execution
files.

Result classification: `Planning completed`

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step156-missing-credential-ui-path-decision-checkpoint.md`
- `docs/maturation/step155-human-controlled-credential-source-admin-smoke-results.md`
- `docs/maturation/step154-controlled-credential-source-admin-smoke-plan.md`
- `docs/maturation/step153-ga4-oauth-credential-source-source-level-verification-results.md`
- `docs/maturation/step152-narrow-ga4-oauth-credential-source-production-implementation-results.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`

## Background

Status-level background:

- Step 152 added the GA4 credential source resolver in production PHP.
- Step 153 source-level verification passed for resolver definition, call
  sites, credential precedence, manual fallback, missing credential category,
  request-local token flow, and redaction boundaries.
- Step 155 human-controlled admin smoke passed for Settings page load, Report
  Builder page load, safe label visibility, and value-hidden posture.
- Step 155 observed the safe label category `credential_source_missing`.
- Step 155 intentionally did not trigger GA4 Fetch, so missing credential UI
  behavior remains `not_observed`.
- Step 156 recommended Option C: local-only controlled execution with safe
  stubs / guard only, but required a planning step before any execution.

No option values, credential values, Authorization headers, request bodies, raw
responses, payload JSON, generated report bodies, analytics values, screenshots,
browser Network evidence, or database rows are used in this plan.

## Step 157 Boundary

Step 157 is planning-only.

This step does not:

- execute controlled local-only verification,
- click GA4 Fetch,
- run OpenAI Generate,
- run browser OAuth,
- navigate to Google,
- communicate with token endpoints,
- inspect token storage real data,
- inspect plugin settings option values,
- inspect OAuth token option values,
- inspect manual token values,
- create credentials,
- save credentials,
- change credentials,
- create helper files,
- create mu-plugins,
- create test scripts,
- change production source.

Any concrete execution must be separated into Step 158 or later.

## Future Step 158 Candidate Objective

Future objective:

```text
Verify the missing credential UI path locally without external communication.
```

The goal is to confirm that, when the GA4 credential source is missing, the
admin UI produces a safe error/status category without a fatal error, without
displaying credential or option values, and without allowing any external
request to reach the network.

Out of scope for the future Step 158 objective:

- real GA4 API data retrieval,
- OpenAI generation,
- browser OAuth,
- OAuth token endpoint execution,
- refresh,
- reconnect,
- revoke,
- uninstall cleanup,
- credential storage inspection,
- raw request / response evidence collection.

## Required Local-Only Guards

Any future execution must be guarded before the missing credential path is
triggered.

Required guard posture:

- Use a deny-by-default hard-block for WordPress HTTP API requests.
- If `pre_http_request` or a similar WordPress hook is used, the plan must
  block all outbound HTTP by default rather than relying on an allowlist.
- Treat attempted Google, GA4, OAuth, OpenAI, or unknown external URLs as:
  `external_request_attempt_blocked`.
- Do not let attempted external requests reach the network.
- Do not record raw URLs.
- Do not record Authorization headers.
- Do not record request bodies.
- Do not record response bodies.
- Do not record browser Network evidence.
- Do not record screenshots.
- If a temporary helper is used in a future execution step, remove it after the
  check and verify that no helper, mu-plugin, or script remains.

If a hard-block cannot be installed and verified before the UI path is touched,
abort execution and use source-level fallback.

## Credential State Assumptions

Future execution should rely only on safe status categories:

- Step 155 observed `credential_source_missing`.
- Future execution should not inspect option values to prove the missing state.
- Future execution should not use `wp option get analytics_report_ai_oauth_tokens`.
- Future execution should not display plugin settings option values.
- Future execution should not display manual Google Access Token values.
- Future execution should not display OAuth token option values.
- Future execution should not create, save, modify, delete, or migrate
  credentials.

The missing state should be treated as a safe UI/source category, not as a
database value to inspect.

## Future Execution Candidate Approaches

| Approach | Summary | Benefits | Risks / Gaps | Boundary Result |
|---|---|---|---|---|
| A | Human browser + local HTTP hard-block guard | Best matches the admin UI behavior gap because it observes the real admin page path. | Requires a robust local HTTP hard-block before any Fetch action. Must avoid screenshots, Network evidence, nonce/session recording, and value inspection. | Recommended if the hard-block guarantee is in place. |
| B | WP-CLI or local helper controlled invocation | Keeps execution more deterministic and can avoid browser evidence. | UI rendering observation is weaker; may not close the admin UI path gap. | Acceptable if browser execution is too risky. |
| C | Source-level fallback | Safest because no execution occurs. | Does not fully close Step 155 `not_observed` gap. | Required fallback if local-only safety cannot be guaranteed. |

## Recommended Future Approach

Recommended future approach: `Approach A: Human browser + local HTTP hard-block guard`

Rationale:

- The remaining gap is specifically the admin UI missing-credential path.
- Human browser execution with a hard-block guard can observe the UI path while
  preventing any real external request.
- The Step 155 safety posture can be preserved if the future step records only
  status-level categories and does not use screenshots, browser Network
  evidence, credentials, option values, raw requests, or raw responses.

Fallback:

```text
If the local HTTP hard-block cannot be guaranteed before execution, use
Approach C: Source-level fallback.
```

Approach B remains a secondary option if a browser check is not acceptable but
local helper invocation can be guarded without exposing values. Because the
current gap is UI-specific, Approach B should not be the first choice unless
Approach A becomes unsafe or impractical.

## Future Step 158 Observation Template

Use this status-level template for the future result-recording step:

```text
Step 158 missing credential UI path local-only observation:
- Execution mode: human_browser_with_http_guard / local_helper_with_http_guard / source_level_fallback
- Settings page loaded before check: Yes / No / not_applicable
- Report Builder page loaded before check: Yes / No / not_applicable
- Missing credential path triggered: Yes / No / not_observed
- Missing credential UI/status category observed:
  missing_google_credential_category /
  credential_source_missing /
  safe_admin_error_category /
  not_observed /
  unknown_category
- Fatal error observed: No / Yes
- External request attempted: No / Yes / blocked
- External request reached network: No
- GA4 request reached network: No
- OpenAI request reached network: No
- OAuth/token endpoint request reached network: No
- Manual token value displayed: No
- OAuth token option value displayed: No
- Authorization header displayed: No
- Plugin settings option value displayed: No
- Request body recorded: No
- Raw response recorded: No
- AI payload JSON recorded: No
- Generated report body recorded: No
- Network evidence recorded: No
- Screenshot recorded: No
- Forbidden evidence recorded: No
- Temporary helper created: No / Yes
- Temporary helper removed: Yes / No / not_applicable
- Residual helper/mu-plugin found after cleanup: No / Yes / not_applicable
```

## Abort Conditions

Abort future execution if any of these conditions occur:

- external request hard-block cannot be guaranteed,
- a real GA4 request might reach the network,
- an OpenAI request might reach the network,
- an OAuth or token endpoint request might reach the network,
- credential values might be displayed,
- option values might be displayed,
- token values or token fragments might be displayed,
- Authorization headers might be displayed,
- browser Network evidence becomes necessary,
- screenshots become necessary,
- raw request or response recording becomes necessary,
- AI payload JSON or generated report body recording becomes necessary,
- temporary helper cleanup cannot be confirmed,
- any unexpected path would require database dumps or stored option value
  inspection.

If an abort condition is met, record only:

```text
execution_aborted_safety_boundary
```

Do not record raw URLs, headers, request bodies, response bodies, option values,
token values, screenshots, or Network evidence.

## Proposed Step 158

Recommended Step 158:

```text
Step 158: Missing credential UI path controlled local-only execution
```

Step 158 should still avoid real credentials, real GA4 requests, OpenAI
requests, OAuth requests, token endpoint communication, option value
inspection, database dumps, screenshots, and browser Network evidence.

If Step 158 cannot guarantee a local-only hard-block, it should stop before
execution and record a source-level fallback result instead.

## Explicit Non-Goals

Step 157 does not perform:

- production PHP changes,
- JavaScript, CSS, `readme.txt`, or tools changes,
- helper creation,
- mu-plugin creation,
- test script creation,
- controlled execution,
- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- token exchange,
- token storage real data confirmation,
- refresh,
- revoke,
- browser DevTools / Network evidence,
- screenshots,
- `wp option get analytics_report_ai_oauth_tokens`,
- database dump,
- plugin settings option value display,
- OAuth token option value display,
- serialized option value display,
- credential, API key, access token, refresh token, or Authorization header
  display,
- OAuth client ID or client secret value display,
- GA4 Property ID, hostname/domain, analytics values, page path/source/city
  display,
- request body, GA4 raw response, AI payload JSON, OpenAI raw response, or
  generated report body display.

## Evidence Safety

This document records planning categories only. It does not record:

- OAuth token option values,
- serialized option values,
- plugin settings option values,
- manual Google Access Token values,
- access tokens,
- refresh tokens,
- Authorization headers,
- OAuth client ID values,
- OAuth client secret values,
- token endpoint requests or responses,
- authorization codes,
- callback URLs,
- query strings,
- raw state values,
- raw provider errors,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- page path/source/city values,
- request bodies,
- GA4 raw responses,
- AI payload JSON,
- OpenAI raw responses,
- generated report bodies,
- screenshots,
- browser Network evidence,
- database rows or database dumps,
- email addresses or Google account identifiers,
- project IDs or project identifiers.

## Commands Executed

Safe repository checks:

```bash
git status --short --untracked-files=all
test -f docs/maturation/step157-missing-credential-ui-path-controlled-local-only-execution-plan.md && echo "step157_doc_exists"
git diff -- docs/maturation/step157-missing-credential-ui-path-controlled-local-only-execution-plan.md
git diff --name-only
```

Command result summary:

- Step 157 docs file exists.
- Production code was not changed.
- No helper, mu-plugin, or test script was created.
- `git diff --name-only` does not include untracked docs until they are staged;
  use `git status --short --untracked-files=all` for the full working tree
  view.
