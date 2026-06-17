# Step 177: Support / Debug Wording Human Admin Smoke Plan

## Step Purpose

Step 177 creates a human admin smoke plan for the support/debug-safe admin UI
wording added in Step 175 and source-level verified in Step 176.

This is a docs-only and planning-only step. Codex does not perform browser
admin smoke, page load verification, screenshots, browser Network evidence
collection, GA4 Fetch, OpenAI Generate, OAuth Connect / Authorize, Google
navigation, or token endpoint communication.

The planned smoke is limited to Settings and Report Builder page load plus
status/category-level wording confirmation.

Result classification: `Support/debug wording human admin smoke plan completed`

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step172-support-debug-wording-alignment-checkpoint.md`
- `docs/maturation/step173-support-debug-wording-implementation-plan.md`
- `docs/maturation/step174-support-debug-wording-source-level-inventory.md`
- `docs/maturation/step175-support-debug-wording-narrow-implementation-results.md`
- `docs/maturation/step176-support-debug-wording-source-level-verification-results.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`

## Smoke Boundary

Human verification scope:

- Settings page load,
- Report Builder page load,
- support/debug-safe wording visibility,
- status/category-only wording confirmation,
- forbidden-evidence avoidance wording confirmation,
- value-hidden posture confirmation at status level.

Canonical admin page categories:

```text
Settings: page=analytics-report-ai-settings
Report Builder: page=analytics-report-ai
```

Do not use:

```text
page=analytics-report-ai-report-builder
```

Out of scope:

- settings save,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- browser Network evidence,
- screenshots,
- option value inspection,
- credential/token/API key inspection,
- raw request/response inspection,
- AI payload JSON inspection,
- generated report body inspection or recording.

Evidence must remain status-level or category-level only. Do not record real
values, identifiers, payloads, responses, generated report bodies, screenshots,
browser URLs, browser Network details, cookies, sessions, nonces, or database
contents.

## Human Checklist

### Settings

| ID | Check | Allowed result values | Evidence category |
|---|---|---|---|
| S1 | Settings page loads using canonical Settings page category. | Pass / Fail / Not tested | `page_load_status` |
| S2 | Visible fatal error is absent. | Yes / No / Not tested | `fatal_error_status` |
| S3 | Credential Storage area support-safe hint is visible. | Pass / Fail / Not visible / Not tested | `support_hint_visible_status` |
| S4 | Hint directs support/debug sharing to status or category labels only. | Pass / Fail / Not visible / Not tested | `status_category_only_wording_status` |
| S5 | Hint says not to share forbidden evidence categories. | Pass / Fail / Not visible / Not tested | `forbidden_evidence_avoidance_wording_status` |
| S6 | Credential/token values are not exposed in the checked area. | Yes / No / Not tested | `value_hidden_status` |
| S7 | No external action is executed during the check. | No / Yes | `external_action_status` |

Settings checks must not include:

- saving settings,
- clicking OAuth Connect / Authorize,
- navigating to Google,
- token endpoint communication,
- option value inspection,
- screenshot capture,
- browser Network evidence collection.

### Report Builder

| ID | Check | Allowed result values | Evidence category |
|---|---|---|---|
| R1 | Report Builder page loads using canonical Report Builder page category. | Pass / Fail / Not tested | `page_load_status` |
| R2 | Visible fatal error is absent. | Yes / No / Not tested | `fatal_error_status` |
| R3 | Payload Preview support hint is visible when the UI state makes that section available. | Pass / Fail / Not visible / Not tested | `support_hint_visible_status` |
| R4 | Hint directs support/debug sharing to status/category labels, warning messages, or error categories only. | Pass / Fail / Not visible / Not tested | `status_category_only_wording_status` |
| R5 | Hint says not to share forbidden evidence categories. | Pass / Fail / Not visible / Not tested | `forbidden_evidence_avoidance_wording_status` |
| R6 | Credential source labels retain value-hidden posture. | Pass / Fail / Not visible / Not tested | `value_hidden_status` |
| R7 | No external action is executed during the check. | No / Yes | `external_action_status` |

Report Builder checks must not include:

- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- token endpoint communication,
- screenshot capture,
- browser Network evidence collection,
- generated report body inspection or recording,
- AI payload JSON inspection or recording.

## Expected Results

Expected status-level results:

| Check | Expected result |
|---|---|
| Settings page load | Pass |
| Report Builder page load | Pass |
| Fatal error observed | No |
| Support/debug-safe hint visible | Pass or `expected_visible` when the target section is available |
| Forbidden evidence sharing request | Not observed |
| Credential/token value exposure | Not observed |
| GA4 Fetch | Not executed |
| OpenAI Generate | Not executed |
| OAuth Connect | Not executed |
| Token endpoint communication | Not executed |
| Screenshots / Network evidence | Not collected |

If the Payload Preview support hint is not visible because no Payload Preview is
available without GA4 Fetch, record `Not visible` and do not click GA4 Fetch to
force that state.

## Result Recording Template

Use this status-level template for Step 178 human-provided results:

```text
Settings page load: Pass / Fail / Not tested
Settings fatal error observed: Yes / No
Settings support-safe hint visible: Pass / Fail / Not visible
Settings status/category-only wording observed: Pass / Fail / Not visible
Settings forbidden evidence avoidance wording observed: Pass / Fail / Not visible
Settings credential/token value exposure observed: Yes / No
Settings external action executed: No

Report Builder page load: Pass / Fail / Not tested
Report Builder fatal error observed: Yes / No
Report Builder support-safe hint visible: Pass / Fail / Not visible
Report Builder status/category-only wording observed: Pass / Fail / Not visible
Report Builder forbidden evidence avoidance wording observed: Pass / Fail / Not visible
Report Builder credential/token value exposure observed: Yes / No
Report Builder external action executed: No

GA4 Fetch executed: No
OpenAI Generate executed: No
OAuth Connect executed: No
Token endpoint communication executed: No
Screenshots collected: No
Browser Network evidence collected: No
Forbidden evidence recorded: No
```

Do not add raw screen text beyond status/category-level summaries. Do not paste
values, identifiers, URLs, payloads, responses, generated report bodies,
screenshots, or Network details.

## Forbidden Evidence Policy For Smoke Results

Step 178 results must not include:

- credentials,
- API keys,
- access tokens,
- refresh tokens,
- Authorization headers,
- OAuth client ID values,
- OAuth client secret values,
- plugin option values,
- OAuth token option values,
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

If a value or sensitive screen content appears during human checking, do not
record it. Record only a status/category-level result such as value hidden /
not hidden, visible / not visible, or pass / fail.

## Acceptance Criteria

Step 177 is complete when:

- the docs-only smoke plan file is added,
- Step 177 adds no production code changes,
- Step 177 adds no `readme.txt` changes,
- Step 177 adds no tools or build script changes,
- Settings and Report Builder check scope is clear,
- canonical admin slugs are documented,
- the deprecated Report Builder slug is explicitly excluded,
- the human result recording template is included,
- forbidden evidence non-recording policy is explicit,
- WordPress.org release remains `Hold`.

## Commands Executed

Safe docs-only commands:

```bash
git status --short --untracked-files=all
test -f docs/maturation/step177-support-debug-wording-human-admin-smoke-plan.md && echo "step177_doc_exists"
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```

## Not Executed

Codex did not execute:

- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- browser admin smoke,
- screenshots,
- browser Network evidence collection,
- database dump,
- option value inspection.

## Recommended Next Step

Recommended next step:

```text
Step 178: Support/debug wording human admin smoke results
```

Recommended Step 178 scope:

- docs-only result recording,
- human-provided status-level observation only,
- Settings and Report Builder page load / wording category confirmation,
- no GA4 Fetch,
- no OpenAI Generate,
- no OAuth Connect / Authorize,
- no token endpoint communication,
- no screenshots,
- no browser Network evidence,
- no forbidden evidence recording.

## Result Classification

Result: `Support/debug wording human admin smoke plan completed`

Rationale:

- Settings and Report Builder smoke boundaries were defined.
- Canonical slugs were documented.
- Deprecated slug use was excluded.
- Human checklist and result template were created.
- External actions and forbidden evidence collection were excluded.

WordPress.org release remains `Hold`.

