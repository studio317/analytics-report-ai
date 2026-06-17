# Step 173: Support / Debug Wording Implementation Plan

## Step Purpose

Step 173 is a docs-only and planning-only implementation plan for support/debug
wording alignment.

Step 172 aligned support/debug wording at planning level with the credential
source UI wording posture established across Step 165 through Step 170. This
step turns that planning-level alignment into a narrow implementation sequence
for future steps.

This step does not change production code, `readme.txt`, tools, build scripts,
admin UI behavior, credential storage, resolver logic, GA4 request behavior,
OpenAI request behavior, OAuth lifecycle behavior, or release packaging.

Result classification: `Support/debug wording implementation plan completed`

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step168-credential-source-ui-wording-source-level-verification-results.md`
- `docs/maturation/step169-credential-source-ui-wording-admin-smoke-plan.md`
- `docs/maturation/step170-credential-source-ui-wording-human-admin-smoke-results.md`
- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`
- `docs/maturation/step172-support-debug-wording-alignment-checkpoint.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`

## Execution Boundary

Step 173 is docs-only and planning-only.

This step did not perform:

- production PHP changes,
- JavaScript, CSS, `readme.txt`, tools, or build script changes,
- admin UI or browser operations,
- browser admin smoke,
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
- request body, raw GA4 response, AI payload JSON, raw OpenAI response, or
  generated report body display.

Evidence in this step is limited to status-level and category-level planning.

## Current Boundary

Support/debug wording should ask for category/status-level evidence only.

Allowed category/status examples:

- `page_slug_category`
- `screen_category`
- `status_category`
- `credential_source_category`
- `safe_admin_error_category`
- `value_hidden_status`
- `generation_allowed_status`
- `data_availability_category`
- `payload_status_category`

These categories are safe only when they do not include raw values, identifiers,
URLs, request/response bodies, payloads, generated report bodies, screenshots,
browser Network evidence, or option dumps.

The following must not be requested, pasted, displayed, logged, dumped, or
recorded for support/debug:

- credentials,
- API keys,
- access tokens,
- refresh tokens,
- Authorization headers,
- OAuth client ID values,
- OAuth client secret values,
- plugin option values,
- serialized option values,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- page path/source/city values,
- request bodies,
- raw GA4 responses,
- raw OpenAI responses,
- AI payload JSON,
- generated report bodies,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces,
- database rows,
- database dumps.

Support/debug wording should prefer a short reproduction description with safe
status labels over evidence files.

## Candidate Implementation Surfaces

Future implementation or wording update steps should first inventory and then
adjust only the surfaces that can safely use category/status-level wording.

| Surface | Candidate adjustment | Immediate Step 173 action |
|---|---|---|
| Settings admin notices / help text | Clarify that support should use status labels and never paste credentials, tokens, option values, or OAuth client values. | Plan only. |
| Report Builder notices / warnings | Keep warning and blocked/allowed states category-level and safe for support reference. | Plan only. |
| Missing credential messages | Ensure wording asks for missing credential category and whether the action was blocked before external communication. | Plan only. |
| Credential source labels | Preserve value-hidden category labels for OAuth/manual/missing states. | Plan only. |
| No-data / partial-data / generation blocked messages | Align support wording around data availability, payload status, and generation allowed/blocked categories. | Plan only. |
| External API error category messages | Prefer normalized safe admin error categories over raw request/response details. | Plan only. |
| Copy/edit/generated report surrounding text | Avoid asking users to share generated report body for support. | Plan only. |
| Future support/debug guidance text | Create a concise category-only evidence guide. | Plan only. |
| Future readme support guidance | Consider alignment after source inventory and implementation planning. | Not immediate. |

The immediate next step should not change `readme.txt`; it should first
inventory current support/debug/admin wording surfaces.

## Non-goals

Step 173 and the immediate next step do not include:

- credential resolver logic changes,
- OAuth lifecycle changes,
- token storage changes,
- GA4 request logic changes,
- OpenAI request logic changes,
- AI prompt changes,
- AI payload schema changes,
- generated report persistence changes,
- raw JSON preview restoration,
- support bundle or export feature additions,
- browser smoke,
- external API execution,
- Plugin Check execution,
- WordPress.org release preparation.

## Proposed Implementation Sequence

Recommended low-risk sequence:

| Step | Scope | Purpose | External actions |
|---|---|---|---|
| Step 174: Support/debug wording source-level inventory | Docs-only / inspection-only. | Inventory current support/debug/admin wording surfaces without changing production files. | None. |
| Step 175: Narrow production wording implementation for support/debug-safe messages | Production wording only, if Step 174 identifies safe targets. | Add or adjust category/status-level support wording without resolver, storage, API, payload, or lifecycle changes. | None. |
| Step 176: Source-level verification | Inspection-only or docs-only. | Verify translation readiness, escaping, scope boundaries, and absence of forbidden evidence wording. | None. |
| Step 177: Human admin smoke plan | Docs-only / planning-only. | Define Settings and Report Builder page-load checks, without GA4 Fetch, OpenAI Generate, or OAuth Connect. | None. |
| Step 178: Human admin smoke results | Human-provided status-level result recording. | Record whether safe support/debug wording appears as expected. | None by CODEX. |
| Step 179: Maturation checkpoint | Docs-only / planning-only. | Decide whether the support/debug wording track is matured for the current MVP scope. | None. |

Sequence rationale:

- Inventory comes before production wording edits.
- Production wording, if needed, stays narrow and status-level.
- Verification checks translation, escaping, and forbidden evidence boundaries.
- Browser/admin smoke remains human-controlled and avoids external actions.
- The track gets its own maturation checkpoint before moving into other release
  blockers.

## Acceptance Criteria

Step 173 is complete when:

- the docs-only file is added,
- production code has no Step 173 changes,
- `readme.txt` has no Step 173 changes,
- tools and build scripts have no Step 173 changes,
- support/debug wording implementation scope is limited to category/status-level
  evidence,
- forbidden evidence non-display and non-recording policy is explicit,
- the next recommended step is clear,
- WordPress.org release remains `Hold`.

## Recommended Next Step

Recommended next step:

```text
Step 174: Support/debug wording source-level inventory
```

Recommended Step 174 scope:

- docs-only,
- inspection-only,
- no production code change,
- no `readme.txt` change,
- no tools or build script change,
- inventory current support/debug/admin wording surfaces in source files,
- avoid plugin option inspection,
- avoid credential/token/OAuth client value inspection,
- avoid GA4 Fetch, OpenAI Generate, OAuth Connect, Google navigation, token
  endpoint communication, browser admin smoke, screenshots, Network evidence,
  and Plugin Check.

## Result Classification

Result: `Support/debug wording implementation plan completed`

Rationale:

- Step 172 planning-level alignment was converted into a future implementation
  sequence.
- The current category/status-level support/debug boundary was restated.
- Candidate implementation surfaces were identified.
- Non-goals were explicitly scoped out.
- Acceptance criteria were defined.
- Step 174 was recommended as the next safe source-level inventory step.

WordPress.org release remains `Hold`.

## Commands Executed

Safe docs-only commands:

```bash
git status --short --untracked-files=all
test -f docs/maturation/step173-support-debug-wording-implementation-plan.md && echo "step173_doc_exists"
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```

