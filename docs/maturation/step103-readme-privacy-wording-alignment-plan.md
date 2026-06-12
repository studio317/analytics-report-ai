# Step 103: Readme / Privacy Wording Alignment Plan

## Step Summary

Step 103 creates a docs-only plan for aligning `readme.txt` privacy, external
services, credential storage, Payload Preview, generated report, and
support/debug wording with the Step 95 through Step 102 visibility decisions.

Step 95 finalized that the normal admin UI should not use full raw AI payload
JSON as the public-release posture. Step 96 finalized that generated report
text may be shown to the user for review, editing, and copying, but should not
be persisted by the plugin or requested as support/debug evidence. Step 97
consolidated those decisions into privacy/support wording. Step 102 removed
the normal admin UI raw JSON details area while preserving structured Payload
Preview.

This step does not change production code, PHP, JavaScript, CSS, `readme.txt`,
admin UI behavior, Settings save logic, GA4 client behavior, OpenAI client
behavior, credential storage, release packaging, or WordPress.org metadata.

Plugin Check was not executed. No external API communication was performed.
`wp-dev-check` was not touched. GA4 Fetch and OpenAI Generate were not run.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step102-payload-preview-raw-json-removal-implementation-results.md`
- `docs/maturation/step101-payload-preview-raw-json-replacement-gating-plan.md`
- `docs/maturation/step100-admin-ui-wording-alignment-implementation-results.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step96-generated-report-handling-policy-finalization.md`
- `docs/maturation/step95-payload-preview-json-visibility-final-decision.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`

## Review Method

`readme.txt` was reviewed at source wording level only.

`includes/class-report-builder.php` and `includes/class-settings.php` were
referenced only to compare current admin wording direction with the planned
readme direction.

This review did not inspect or record real credentials, option values, raw
payload bodies, generated report bodies, analytics values, screenshots, browser
Network data, cookies, sessions, or nonces.

## Current Readme Wording Inventory

| Area | Current readme wording state | Alignment need |
|---|---|---|
| Description | Describes fetching selected GA4 report data, reviewing an AI payload, generating a Japanese report draft with OpenAI, editing, and copying final text. | Update "review an AI payload" toward "review a structured preview" so it matches Step 95 / Step 102. |
| External Services introduction | States that third-party services are used only when an administrator starts a report action and that viewing plugin screens alone does not send data. | Keep. This remains aligned with the staged admin flow. |
| Google Analytics Data API | Explains that Fetch GA4 Data sends selected report conditions to Google Analytics Data API and lists data sent/received categories. | Mostly aligned. Keep action-triggered wording and selected report data framing. |
| GA4 data acquisition | Lists date range, comparison, host/path filters, metrics/dimensions, summary metrics, daily trend, top pages, traffic channels, traffic sources, and city-level regional trends. | Keep category-level disclosure. Later wording can make clear these are report generation data categories. |
| OpenAI API | States that Generate AI Report sends a request to OpenAI to generate a Japanese report draft from the reviewed payload. | Update to "reviewed report data" / "structured preview" language to avoid implying a raw JSON preview is the normal UI. |
| Data sent to OpenAI | Lists API key header, selected model, fixed system instructions, and "Reviewed AI payload shown in Payload Preview." | Update the payload line to explain that GA4-derived report data reviewed through the structured Payload Preview may be sent. |
| AI payload categories | Lists host name, date/comparison information, normalized path condition, summary metrics/differences, daily trend, top pages, traffic channels, traffic sources, and city-level regional trends. | Keep category-level disclosure, but align with "summary / trend / page / channel / source / region report data" wording. |
| Credential storage | States that Google Access Token and OpenAI API Key are saved in the WordPress database as plugin settings, not displayed again, and require redesign before public or multi-user use. | Keep, with minimal consistency edits only if needed in the next readme step. |
| Payload Preview / payload review | States that the plugin formats selected GA4 results into an AI payload, shows that payload in Payload Preview, and sends the reviewed payload only when Generate AI Report is clicked. | Update to say Payload Preview is a structured summary / human-readable preview and that the normal admin UI does not expose the full raw AI payload JSON preview. |
| Generated report | States that generated result is a draft and users should review/edit it before publishing, sharing, or sending. | Add that generated report text is shown for review/edit/copy but is not saved by the plugin. |
| Support/debug evidence | No explicit support/debug evidence boundary was found in `readme.txt`. | Add a short support evidence safety note aligned with Step 97 and Step 86. |
| Privacy / data handling | External Services and Credential Storage sections cover provider endpoints, action triggers, data categories, credential storage, and payload validation. | Add the Step 95 through Step 102 boundaries: structured preview, no normal raw JSON preview, no generated report persistence, and no sensitive support evidence requests. |

## Alignment Needs

The next readme wording update should align the following points:

- GA4-derived report generation data may be sent to OpenAI when an
  administrator generates an AI report.
- Data sent to OpenAI is based on selected date range, comparison setting,
  data scope, filters, and report presets.
- Data categories may include summary, trend, page, channel, source, and
  regional report data needed to generate the report.
- The normal admin UI no longer exposes a full raw AI payload JSON preview.
- Payload Preview remains as a structured summary / human-readable preview for
  pre-send review.
- Generated report text may be shown in the admin UI for user review, editing,
  and copying.
- The plugin does not persist generated report text as part of the MVP
  posture.
- Support/debug requests should not ask for raw AI payload JSON, generated
  report bodies, raw GA4/OpenAI responses, OpenAI request bodies, credentials,
  option values, analytics identifiers, or analytics values.
- Diagnosis should rely on status-level labels, warnings, error category,
  generation allowed/blocked state, and redacted UI state.

## Proposed Readme Update Scope For Next Step

Recommended next implementation scope:

### In Scope

- `readme.txt` wording updates only.
- External Services wording alignment.
- Data sent to OpenAI wording alignment.
- Payload Preview wording alignment.
- Generated report non-persistence wording.
- Support/debug evidence safety wording.
- Minimal Credential Storage wording consistency, only if needed.

### Out Of Scope

- Production PHP changes.
- Admin UI changes.
- JavaScript changes.
- CSS changes.
- OAuth implementation.
- Credential storage redesign.
- Uninstall cleanup.
- Plugin Check.
- External API calls.
- GA4 Fetch.
- OpenAI Generate.
- Browser screenshots.
- `wp-dev-check` operations.

## Draft Wording Direction

The following English drafts are candidates for Step 104. They are not applied
to `readme.txt` in Step 103.

### Description Draft

```text
Analytics Report AI helps administrators fetch selected GA4 report data,
review a structured pre-send preview, generate a Japanese report draft with
OpenAI, edit the draft, and copy the final text.
```

### External Services Draft

```text
Analytics Report AI connects to Google Analytics Data API to fetch report data
selected by the site administrator. Viewing the plugin screens does not, by
itself, send data to Google or OpenAI.
```

### OpenAI Data Disclosure Draft

```text
When the administrator generates an AI report, report data derived from GA4
may be sent to OpenAI. The data is based on the selected date range,
comparison setting, data scope, filters, and report presets.
```

```text
The data may include summary metrics, daily trends, top pages, traffic
channels, traffic sources, and regional report data needed to generate the
report.
```

```text
The plugin shows a structured Payload Preview before AI generation. The normal
admin UI does not expose a full raw AI payload JSON preview.
```

### Payload Review Draft

```text
The plugin does not send the full raw GA4 response to OpenAI. It formats
selected GA4 results into report-generation data, shows a structured preview
before AI generation, and sends the reviewed report data only when Generate AI
Report is clicked.
```

```text
The reviewed report data is stored temporarily in a user-scoped WordPress
transient and expires automatically. Payload validation runs before transient
storage and again before OpenAI generation; missing, expired, old, or invalid
payloads are not sent to OpenAI.
```

### Generated Report Draft

```text
Generated report text is shown for user review, editing, and copying. The
plugin does not save generated report text.
```

```text
Generated report text is a draft, and users should review and edit it before
publishing, sharing, or sending it.
```

### Support / Debug Evidence Draft

```text
Support requests should not include credentials, API keys, access tokens,
Authorization headers, plugin settings option values, raw payloads, raw API
responses, OpenAI request bodies, generated report text, GA4 property
identifiers, hostnames, page paths, traffic source values, city values, or
analytics metric values.
```

```text
For support, describe the issue using status-level information such as the
screen, action, warning message, generic error category, generation
allowed/blocked state, or redacted UI state.
```

### Credential Storage Consistency Draft

```text
In the MVP, the Google Access Token and OpenAI API Key are saved in the
WordPress database as plugin settings. Saved credential values are not
displayed again in the admin screen. This storage method is for MVP and
developer verification and needs redesign before public or multi-user use.
```

## Verification Plan For Next Step

For Step 104, run:

- `git diff --check`
- readme wording grep/source review for:
  - `External Services`
  - `Google Analytics Data API`
  - `OpenAI API`
  - `Payload Preview`
  - `structured`
  - `raw`
  - `generated report`
  - `support`
  - `credentials`
- `git diff --name-only` to confirm only `readme.txt` changed, plus any
  explicitly scoped Step 104 results doc if requested.
- `git status --short --untracked-files=all`

Confirm during Step 104:

- no PHP files changed,
- no JavaScript files changed,
- no CSS files changed,
- no production behavior changed,
- no external API calls were made,
- Plugin Check was not run unless explicitly scoped later,
- no credentials, option values, raw payloads, raw responses, generated report
  bodies, analytics values, screenshots, browser Network data, cookies,
  sessions, or nonces were recorded.

## Recommended Next Step

Proceed with:

```text
Step 104: Readme/privacy wording alignment implementation
```

Reason:

- Step 103 defines the readme wording inventory and alignment direction.
- Step 104 can be a small `readme.txt`-only wording update.
- After readme/privacy wording is aligned, the project can more cleanly move
  toward an isolated Plugin Check run in `wp-dev-check`.

## Explicit Non-goals

This step does not:

- change production code,
- change PHP, JavaScript, or CSS,
- change `readme.txt`,
- change admin UI behavior,
- change Settings save logic,
- change GA4 client behavior,
- change OpenAI client behavior,
- change credential storage,
- implement OAuth,
- redesign credential storage,
- run Plugin Check,
- touch `wp-dev-check`,
- call external APIs,
- run GA4 Fetch,
- run OpenAI Generate,
- inspect or display plugin settings option values,
- inspect or display credentials,
- record raw payloads,
- record raw request bodies,
- record raw response bodies,
- record generated report bodies,
- capture screenshots,
- inspect browser Network tab data.

## Security / Evidence Notes

This document records source wording inventory, policy alignment needs, and
draft readme wording only.

It does not record real credentials, API keys, access tokens, Authorization
headers, plugin settings option values, GA4 Property IDs, hostname/domain
values, analytics values, page paths, traffic sources, city values, request
bodies, AI payload JSON, OpenAI request bodies, raw GA4/OpenAI response bodies,
generated report bodies, screenshots, browser Network tab data, cookies,
sessions, or nonces.
