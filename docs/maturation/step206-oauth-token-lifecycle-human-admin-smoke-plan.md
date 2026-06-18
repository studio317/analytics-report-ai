# Step 206: OAuth Token Lifecycle Human Admin Smoke Plan

## Step Purpose

Step 206 is a docs-only and planning-only human admin smoke plan for the OAuth
token lifecycle UI added in Step 204 and source-verified in Step 205.

The purpose is to define how a human reviewer should verify visible
Settings and Report Builder status/category labels, reconnect-required wording,
and the local-only disconnect boundary without exposing credentials, token
values, option values, screenshots, browser Network evidence, request bodies,
raw responses, payloads, or generated report bodies.

This step does not execute the browser smoke. It only defines the plan for a
later controlled human run.

WordPress.org release remains `Hold`.

## Scope

In scope for the future human admin smoke:

- Settings UI status/category labels for OAuth token lifecycle.
- Settings UI wording that refresh requests are deferred.
- Settings UI wording that provider-side revoke requests are deferred.
- Local-only disconnect control visibility when local OAuth token storage is
  present.
- Local-only disconnect visible result categories if a later controlled human
  run explicitly executes that local action.
- Report Builder GA4 credential source status/category labels.
- Report Builder reconnect-required / refresh-needed / refresh-unavailable
  notice wording.
- Human-visible confirmation that local disconnect does not target the temporary
  manual Google Access Token fallback.
- Human-visible confirmation that local disconnect does not target the OpenAI
  API key.
- Evidence boundaries for status/category-level reporting only.

Out of scope for Step 206 itself:

- Any browser action,
- any production code change,
- any settings save or credential entry,
- any local disconnect execution.

## Explicit Non-goals

Step 206 does not:

- change production code,
- change `readme.txt`,
- change tools or build scripts,
- change JavaScript or CSS,
- run Plugin Check,
- run GA4 Fetch,
- run OpenAI Generate,
- start OAuth Connect / Authorize,
- navigate to Google,
- call the token endpoint,
- execute refresh requests,
- execute revoke requests,
- run browser admin smoke,
- collect screenshots,
- collect browser Network evidence,
- inspect database dumps,
- inspect option values,
- inspect token values,
- inspect credential values,
- inspect OAuth client values,
- inspect request bodies,
- inspect raw responses,
- inspect AI payload JSON,
- inspect generated report bodies.

## Preconditions

Before a future Step 207 human admin smoke execution:

- Step 204 implementation is present.
- Step 205 source-level verification is complete.
- The reviewer can access WordPress admin in a controlled local verification
  environment.
- The reviewer understands that visible status/category labels may be recorded,
  but actual credential/token/option values must not be recorded.
- OAuth Connect / Authorize must not be clicked.
- GA4 Fetch must not be clicked.
- OpenAI Generate must not be clicked.
- Browser screenshots and Network evidence must not be collected.
- If local disconnect execution is included, it must be a deliberate local-only
  action in a controlled environment, and the reviewer must record only the
  visible status/category-level result.

## Human Admin Smoke Scenarios

| ID | Screen | Scenario | Action boundary | Expected status-level observation |
|---|---|---|---|---|
| S1 | Settings | Page loads without visible fatal error. | Open canonical Settings page only. | Settings page visible; no visible fatal error category. |
| S2 | Settings | OAuth lifecycle category labels are visible. | Observe labels only. | `oauth_connection_status_category`, `token_lifecycle_status_category`, `token_refresh_status_category`, `token_disconnect_status_category`, and `token_revoke_status_category` are visible as status/category labels. |
| S3 | Settings | Refresh request deferred wording is visible. | Observe wording only. | UI communicates refresh requests are deferred; no refresh action is offered or executed. |
| S4 | Settings | Provider-side revoke deferred wording is visible. | Observe wording only. | UI communicates provider-side revoke is deferred or not performed by local disconnect. |
| S5 | Settings | Local-only disconnect control visibility is correct. | Observe control only unless a later controlled run explicitly includes local disconnect execution. | Control is visible only when applicable according to local OAuth token storage state; if absent, record `not_visible_not_applicable` or `not_visible_unexpected` as status-level only. |
| S6 | Settings | Local disconnect wording excludes manual fallback and OpenAI key. | Observe wording only. | UI says local disconnect deletes only local OAuth token data and does not delete the temporary manual Google Access Token fallback or OpenAI API key. |
| S7 | Settings | Local disconnect controlled execution result, if explicitly included in Step 207. | Click only the local disconnect control; do not use OAuth Connect. | Record only `local_tokens_deleted`, `failed`, `not_available`, or `not_executed` category. |
| S8 | Report Builder | Page loads without visible fatal error. | Open Report Builder page only. | Report Builder visible; no visible fatal error category. |
| S9 | Report Builder | GA4 credential source category labels are visible. | Observe labels only. | Safe GA4 credential source category and lifecycle/refresh status labels are visible. |
| S10 | Report Builder | Reconnect-required / refresh-needed notice is status-level. | Observe wording only; do not click GA4 Fetch. | Notice refers to reconnect/refresh-needed category and credential-hidden posture without values. |
| S11 | Support/debug wording | Support evidence boundary is visible. | Observe wording only. | UI asks for status/category labels or warning/error categories, not credentials, option values, request/response bodies, screenshots, or Network evidence. |

## Local-only Disconnect Execution Boundary For Step 207

Local disconnect execution is not part of Step 206. If Step 207 includes this
controlled local action, the reviewer may record only:

- whether the control was visible,
- whether the local disconnect action was clicked,
- visible result category:
  - `local_tokens_deleted`,
  - `failed`,
  - `not_available`,
  - `not_executed`,
- whether Settings still communicates that manual Google Access Token fallback
  and OpenAI API key are separate from local disconnect.

The reviewer must not inspect database rows, option values, serialized option
values, token values, credential values, OAuth client values, screenshots, or
browser Network evidence before or after local disconnect.

## Allowed Evidence

Allowed evidence for the future human smoke result:

- source-level reference,
- screen name,
- visible UI label names,
- status/category-level wording,
- Pass / Fail / Blocked classification,
- visible notice category,
- visible control state category,
- command result category,
- file-level change summary.

Examples of allowed status/category-level observations:

- `settings_page_loaded`,
- `report_builder_page_loaded`,
- `oauth_connection_status_category_visible`,
- `token_lifecycle_status_category_visible`,
- `token_refresh_status_category_visible`,
- `token_disconnect_status_category_visible`,
- `token_revoke_status_category_visible`,
- `refresh_deferred_wording_visible`,
- `provider_revoke_deferred_wording_visible`,
- `local_disconnect_control_visible`,
- `local_disconnect_control_not_visible`,
- `local_tokens_deleted`,
- `manual_fallback_separate_wording_visible`,
- `openai_key_separate_wording_visible`,
- `forbidden_evidence_not_recorded`.

## Forbidden Evidence

The future human smoke must not record, display, request, paste, summarize, or
infer:

- OAuth client ID values,
- OAuth client secret values,
- OAuth client value fragments,
- credential values,
- API keys,
- access token values,
- refresh token values,
- Authorization headers,
- plugin option values,
- OAuth token option values,
- serialized option values,
- request bodies,
- raw responses,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces,
- database dumps,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- page path/source/city values,
- AI payload JSON,
- generated report bodies.

## Pass / Fail / Blocked Criteria

| Result | Criteria |
|---|---|
| Pass | The expected UI/status/category-level label, wording, or control state is visible and no forbidden evidence is recorded. |
| Fail | The screen shows a visible fatal error, the expected status/category wording is missing or misleading, forbidden evidence is shown by the UI, or a control appears to imply refresh/revoke/external communication contrary to the Step 204 boundary. |
| Blocked | The screen cannot be reached, login/admin access is unavailable, local token storage state cannot safely support the planned observation, or the observation would require forbidden evidence or a prohibited action. |
| Not applicable | A check depends on a state that is not present, such as local disconnect control visibility when local OAuth token storage is absent. |

## Expected Observations

Expected Settings observations:

- Settings page loads using the canonical Settings slug.
- OAuth token lifecycle status/category labels are visible.
- Refresh status is shown as status/category-level only.
- Provider revoke status is shown as deferred/category-level only.
- Local disconnect wording says it deletes only local OAuth token data.
- Local disconnect wording says Google is not contacted.
- Local disconnect wording says provider-side revoke is not performed.
- Local disconnect wording says the temporary manual Google Access Token
  fallback is not deleted.
- Local disconnect wording says the OpenAI API key is not deleted.
- Credential values, token values, option values, OAuth client values, and token
  endpoint responses are not displayed.

Expected Report Builder observations:

- Report Builder page loads using the canonical Report Builder slug.
- GA4 credential source status/category labels are visible.
- OAuth lifecycle / refresh status labels are visible near the GA4 credential
  source area.
- Reconnect-required or refresh-needed notice text, when visible, is
  category-level and states that credential values are not displayed.
- Manual Google Access Token fallback remains described as temporary MVP
  maturation fallback / separate developer-verification path.
- No GA4 Fetch is clicked.
- No OpenAI Generate action is clicked.

Expected local disconnect observations if included in Step 207:

- The reviewer records only whether the control was visible and the visible
  result category after the local action.
- The reviewer does not inspect option values or database state.
- The reviewer does not infer token values from UI state.
- The reviewer does not collect screenshots or Network evidence.

## Risk Boundaries

Primary risk boundaries:

- Local disconnect can be confused with provider-side revoke; the human smoke
  must verify that the UI separates these concepts.
- Refresh deferred wording must not imply that refresh is already implemented
  or executed.
- Reconnect-required wording must not ask users to share tokens, option values,
  screenshots, Network traces, request bodies, or raw responses.
- Local disconnect must not appear to remove manual Google Access Token fallback
  or OpenAI API key.
- Human smoke must avoid GA4 Fetch, OpenAI Generate, OAuth Connect, Google
  navigation, token endpoint communication, refresh requests, and revoke
  requests.
- Human smoke evidence must remain status/category-level only.

## Step 207 Human Result Template

Use this template in the future Step 207 result recording.

```text
Settings page loaded: Pass / Fail / Blocked
Visible fatal error on Settings: Yes / No
OAuth lifecycle labels visible: Pass / Fail / Blocked
Observed oauth_connection_status_category label: visible / not_visible / blocked
Observed token_lifecycle_status_category label: visible / not_visible / blocked
Observed token_refresh_status_category label: visible / not_visible / blocked
Observed token_disconnect_status_category label: visible / not_visible / blocked
Observed token_revoke_status_category label: visible / not_visible / blocked
Refresh deferred wording visible: Pass / Fail / Blocked
Provider revoke deferred wording visible: Pass / Fail / Blocked
Local disconnect control state: visible / not_visible / not_applicable / blocked
Local disconnect executed: Yes / No
Local disconnect visible result category: local_tokens_deleted / failed / not_available / not_executed
Manual Google Access Token fallback separate wording visible: Pass / Fail / Blocked
OpenAI API key separate wording visible: Pass / Fail / Blocked
Report Builder page loaded: Pass / Fail / Blocked
GA4 credential source category labels visible: Pass / Fail / Blocked
Reconnect-required / refresh-needed notice status-level only: Pass / Fail / Blocked / Not applicable
GA4 Fetch executed: No
OpenAI Generate executed: No
OAuth Connect / Authorize executed: No
Google navigation executed: No
Token endpoint communication executed: No
Refresh request executed: No
Revoke request executed: No
Screenshots collected: No
Network evidence collected: No
Option/token/credential/OAuth client values inspected or recorded: No
Forbidden evidence recorded: No
```

## Recommended Next Step

Recommended next step:

```text
Step 207: OAuth token lifecycle controlled human admin smoke execution
```

Step 207 should record human-provided status/category-level observations from
the Settings and Report Builder admin screens. It should not execute OAuth
Connect, Google navigation, token endpoint communication, refresh requests,
revoke requests, GA4 Fetch, OpenAI Generate, Plugin Check, screenshots, browser
Network evidence collection, database dumps, or option value inspection.

## Result Classification

```text
OAuth token lifecycle human admin smoke plan completed
WordPress.org release status: Hold
```
