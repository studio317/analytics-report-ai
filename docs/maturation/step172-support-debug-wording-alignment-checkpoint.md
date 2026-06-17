# Step 172: Support And Debug Wording Alignment Checkpoint

## Step Purpose

Step 172 is a docs-only and planning-only checkpoint to align support/debug
wording with the credential source UI wording posture established across Step
165 through Step 170.

The purpose is to:

- align support/debug requests with category-level evidence,
- preserve the value-hidden credential posture,
- clarify which support/debug evidence is safe to share,
- clarify which evidence must not be requested or recorded,
- keep this as a checkpoint before any production wording change.

Result classification: `Support/debug wording alignment checkpoint completed`

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`
- `docs/maturation/step170-credential-source-ui-wording-human-admin-smoke-results.md`
- `docs/maturation/step169-credential-source-ui-wording-admin-smoke-plan.md`
- `docs/maturation/step168-credential-source-ui-wording-source-level-verification-results.md`
- `docs/maturation/step167-credential-source-ui-wording-narrow-production-implementation-results.md`
- `docs/maturation/step165-credential-source-ui-wording-and-status-label-alignment-plan.md`
- `docs/maturation/step164-manual-token-fallback-retirement-decision-checkpoint.md`
- `docs/maturation/step163-oauth-credential-source-remaining-gap-decision-checkpoint.md`
- `docs/maturation/step162-admin-smoke-instruction-slug-alignment-docs-update-results.md`
- `docs/maturation/step159-missing-credential-ui-path-controlled-local-only-execution-results.md`
- `docs/maturation/step155-human-controlled-credential-source-admin-smoke-results.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`

## Execution Boundary

Step 172 is docs-only and planning-only.

This step did not perform:

- production PHP changes,
- JavaScript, CSS, `readme.txt`, or tools changes,
- admin UI or browser operations,
- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- browser DevTools / Network evidence collection,
- screenshot recording,
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

Evidence in this checkpoint is limited to status-level categories and planning
language.

## Existing Posture Summary

| Source | Existing posture |
|---|---|
| Step 86 | Support/debug evidence should avoid credentials, option values, raw request/response bodies, payload JSON, generated report bodies, analytics identifiers/values, screenshots with sensitive content, and browser Network evidence. Status-level descriptions and safe categories are preferred. |
| Step 97 | Support/privacy wording should not request raw AI payload JSON, generated report bodies, OpenAI request bodies, raw GA4/OpenAI responses, credentials, API keys, access tokens, Authorization headers, or plugin settings option values. Diagnosis should rely on status labels, warnings, error categories, allowed/blocked state, and redacted UI state. |
| Step 165 through Step 170 | Credential source UI wording moved toward OAuth-first language, manual token fallback labeling, value-hidden status, safe category labels, and no credential/token/option value exposure. |
| Step 171 | The credential source UI wording track is matured for the current MVP maturation scope, and support/debug wording alignment is the recommended next track. |

Combined posture:

- Support/debug should request category/status only.
- Credential values should remain hidden.
- Saved/not-saved and connected/not-connected states may be described only as
  status-level labels.
- Missing credential behavior may be discussed as a safe error/category, not as
  raw option or token evidence.
- Screenshots, browser Network captures, database dumps, option dumps, raw
  payloads, raw responses, and generated report bodies are not needed for the
  support/debug path.

## Allowed Support / Debug Evidence Categories

The following categories may be shareable when they remain category-level only
and do not include raw values, identifiers, URLs, request/response bodies,
payloads, generated report bodies, screenshots, or Network evidence.

| Category | Meaning | Safe to share | Notes |
|---|---|---|---|
| `page_slug_category` | The admin page category or registered slug category being checked. | Yes | Use canonical category names, not full browser URLs. |
| `screen_category` | The high-level admin screen, such as Settings or Report Builder. | Yes | Do not include screenshots or visible sensitive screen content. |
| `status_category` | A normalized high-level state, pass/fail/block/not tested, or similar status. | Yes | Keep it status-level and value-free. |
| `credential_source_category` | The resolved credential source category shown in UI wording. | Yes | Share labels only, never token or option values. |
| `safe_admin_error_category` | A user-facing admin error category that has been normalized for support. | Yes | Do not paste raw provider errors, request data, or response data. |
| `fatal_error_yes_no` | Whether a visible fatal error occurred. | Yes | Share yes/no and a safe category only; do not share screenshots. |
| `warning_label_category` | A warning category or label shown by the UI. | Yes | Do not include analytics values or raw payload details. |
| `missing_google_credential_category` | The missing Google credential branch or warning category. | Yes | Use only to indicate the safe blocked/missing-credential state. |
| `credential_source_missing` | The source/status category for no usable GA4 credential. | Yes | Do not inspect or share option values to prove it. |
| `credential_source_oauth_connected` | The source/status category for OAuth-connected credential posture. | Yes | Do not share OAuth token values or OAuth option contents. |
| `credential_source_manual_token` | The source/status category for the manual token fallback posture. | Yes | Do not share the manual token value or fragments. |
| `oauth_status_level_only` | OAuth state summarized without URLs, codes, tokens, provider raw errors, or account identifiers. | Yes | Keep to configured/connected/not connected/error-category labels. |
| `manual_token_saved_not_saved_status` | Whether the manual fallback token appears saved or not saved at UI status level. | Yes | Do not include any token value, prefix, suffix, or option output. |
| `value_hidden_status` | Whether a credential/token/option value remains hidden in the UI. | Yes | Ask whether values are hidden, not what the values are. |
| `wording_visibility_category` | Whether expected wording category appears in the UI. | Yes | Use visible/not visible category labels, not screenshots or copied private content. |

## Forbidden Support / Debug Evidence

The following must not be requested, pasted, screenshotted, logged, dumped, or
recorded for support/debug:

- credentials,
- API keys,
- Google access tokens,
- Google refresh tokens,
- Authorization headers,
- OAuth token option values,
- plugin settings option values,
- serialized option values,
- OAuth client ID values,
- OAuth client secret values,
- GA4 Property ID values,
- hostnames / domains,
- analytics metric values,
- page paths,
- traffic sources,
- city / region values,
- request bodies,
- raw GA4 responses,
- AI payload JSON,
- raw OpenAI responses,
- generated report bodies,
- browser Network evidence,
- screenshots,
- cookies,
- sessions,
- nonces,
- database rows,
- database dumps.

Forbidden evidence should be replaced with status-level labels, safe categories,
or yes/no observations.

## Support Wording Principles

Support/debug wording should follow these principles:

- Ask for category/status only, not raw data.
- Ask for page slug category, not full sensitive URLs.
- Ask for fatal yes/no, not screenshots.
- Ask for safe admin error category, not raw request/response.
- Ask whether values are hidden, not what the values are.
- Do not ask users to paste API keys, OAuth tokens, option values,
  Authorization headers, payload JSON, generated reports, or analytics data.
- Do not ask for browser Network screenshots.
- For missing credential issues, ask only for category labels and whether the
  action was blocked before an external request.
- For support/debug, prefer status-level reproduction steps over evidence
  files.

## Candidate Support / Debug Wording Buckets

The following buckets are candidates for future production/readme/support
wording. Step 172 does not finalize exact user-facing copy.

| Bucket | Purpose | Notes |
|---|---|---|
| Shareable status categories | Tell users what category-level information is safe to share. | Examples include screen category, status category, warning category, credential source category, and safe admin error category. |
| Do not share credentials or tokens | Reinforce that credentials, tokens, API keys, Authorization headers, and OAuth client values should not be pasted into support requests. | Should apply to public issues, private support, docs, and QA evidence. |
| Credentials are saved but values remain hidden | Explain value-hidden posture without revealing values. | Ask only whether saved/not-saved or connected/not-connected state is shown. |
| Missing credential category | Provide a safe way to report missing credential behavior. | Ask for the missing credential category and whether the action was blocked before external communication. |
| External action not triggered | Allow users/testers to state that GA4/OpenAI/OAuth actions were not performed. | Useful for safe smoke results and support triage. |
| OAuth status-level only | Keep OAuth troubleshooting to configured/connected/not connected/error category labels unless a future scoped process says otherwise. | Do not ask for URLs, raw provider errors, query strings, codes, tokens, or account identifiers. |
| Support-safe admin error category | Ask for normalized admin error categories rather than raw request/response evidence. | Keeps external provider and WordPress admin errors safe for support. |
| No screenshots or Network evidence needed | Steer support/debug away from screenshots and browser Network captures. | If a future screenshot process is introduced, it needs separate redaction rules and review. |

## Current Alignment Decision

Decision:

```text
Support/debug wording alignment status: Aligned at planning level
```

Rationale:

- Step 86 and Step 97 already prefer status-level support/debug evidence and
  prohibit raw credentials, payloads, responses, generated report bodies, and
  unsafe screenshots.
- Step 165 through Step 170 extended the same posture to credential source UI
  wording through category labels and value-hidden status.
- Step 171 marked the credential source UI wording track as matured for the
  current MVP maturation scope and selected support/debug wording alignment as
  the next safe track.
- The Step 172 categories and wording principles align those decisions without
  changing production code, `readme.txt`, or runtime behavior.

Production UI, `readme.txt`, privacy wording, support templates, and future QA
templates still need separate scoped implementation or docs update steps.

## Future Implementation Candidates

Future work may include:

- support/debug docs wording update,
- `readme.txt` privacy/support wording update,
- admin UI support hint wording update,
- support template / issue template draft,
- source-level helper or constants for safe categories,
- future admin smoke template update.

These are candidates only. Step 172 does not implement them.

## Recommended Next Track

| Option | Candidate Step 173 | Scope | Recommendation |
|---|---|---|---|
| Option A | `Step 173: Support/debug wording implementation plan` | Docs-only / planning-only. Decide where support/debug wording should be implemented or reflected. | Recommended. |
| Option B | `Step 173: Readme/privacy wording alignment checkpoint` | Align readme/privacy wording with UI/support posture. | Useful later, but should follow a support/debug wording implementation plan. |
| Option C | `Step 173: OAuth lifecycle planning checkpoint` | Move into refresh, reconnect, disconnect, and revoke planning. | Not immediate because it is closer to token lifecycle behavior and should follow stable support/debug boundaries. |

Recommended:

```text
Step 173: Support/debug wording implementation plan
```

Reason:

- The support/debug boundary is now aligned at planning level.
- A follow-up implementation plan can decide which docs, admin hints, support
  guidance, or templates should be updated without jumping directly into
  production code.
- Real GA4 Fetch, OpenAI Generate, OAuth Connect / Authorize, token endpoint
  communication, and Plugin Check are not needed as immediate next steps.

## Decision Output

```text
WordPress.org release remains: Hold
support/debug wording alignment status: Aligned at planning level
production code change immediate next step: No
readme change immediate next step: No
real GA4 Fetch immediate next step: No
OpenAI Generate immediate next step: No
OAuth Connect / Authorize immediate next step: No
OAuth token endpoint immediate next step: No
Plugin Check immediate next step: No
recommended Step 173: Support/debug wording implementation plan
```

## Result Classification

Result: `Support/debug wording alignment checkpoint completed`

Rationale:

- Existing support/debug redaction and privacy wording posture was summarized.
- Credential source UI wording category/value-hidden posture was incorporated.
- Allowed support/debug evidence categories were listed.
- Forbidden support/debug evidence was listed.
- Support wording principles were defined.
- Candidate support/debug wording buckets were identified.
- The current planning-level alignment decision was made.
- Step 173 was recommended as a support/debug wording implementation plan.

WordPress.org release remains `Hold`.

## Notes And Limitations

- This checkpoint does not change production code.
- This checkpoint does not change `readme.txt`.
- This checkpoint does not change admin UI behavior.
- This checkpoint does not run Plugin Check.
- This checkpoint does not run GA4 Fetch.
- This checkpoint does not run OpenAI Generate.
- This checkpoint does not perform OAuth Connect / Authorize.
- This checkpoint does not perform Google navigation.
- This checkpoint does not perform token endpoint communication.
- This checkpoint does not inspect stored option values.
- This checkpoint does not record forbidden evidence.
- This checkpoint does not decide OAuth lifecycle behavior.
- This checkpoint does not change WordPress.org release status.

## Recommended Step 173

Recommended next step:

```text
Step 173: Support/debug wording implementation plan
```

Recommended scope:

- docs-only,
- planning-only,
- identify which support/debug docs, `readme.txt` privacy/support areas, admin
  support hints, issue/support templates, or QA evidence templates should be
  updated,
- avoid production code changes unless a later step explicitly scopes them,
- avoid Plugin Check, GA4 Fetch, OpenAI Generate, OAuth Connect / Authorize,
  Google navigation, and token endpoint communication.

## Commands Executed

Safe docs-only commands:

```bash
git status --short --untracked-files=all
test -f docs/maturation/step172-support-debug-wording-alignment-checkpoint.md && echo "step172_doc_exists"
git diff -- docs/maturation/step172-support-debug-wording-alignment-checkpoint.md
git diff --name-only
git status --short --untracked-files=all
```

