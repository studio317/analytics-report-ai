# Step 101: Payload Preview Raw JSON Replacement / Gating Plan

## Step Summary

Step 101 creates a docs-only plan for aligning the Payload Preview raw JSON
details area with the Step 95 visibility decision and the Step 100 admin UI
wording alignment.

Step 95 finalized that the normal admin UI should not continue displaying the
full raw AI payload JSON as the public-release posture. Step 100 then aligned
the Report Builder wording toward a structured pre-send review.

This step plans how to handle the remaining raw JSON preview surface. It does
not change production code, PHP, JavaScript, CSS, `readme.txt`, admin UI
behavior, Settings save logic, GA4 client behavior, OpenAI client behavior,
payload data structure, OpenAI request payload, generated report persistence,
credential storage, or release packaging.

Plugin Check was not executed. No external API communication was performed.
`wp-dev-check` was not touched. GA4 Fetch and OpenAI Generate were not run.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step100-admin-ui-wording-alignment-implementation-results.md`
- `docs/maturation/step99-admin-ui-wording-alignment-implementation-plan.md`
- `docs/maturation/step98-admin-ui-wording-alignment-plan.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step96-generated-report-handling-policy-finalization.md`
- `docs/maturation/step95-payload-preview-json-visibility-final-decision.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`
- `docs/maturation/step93-external-api-error-path-recheck-results.md`

## Current State

Status-level source and documentation review found the following:

- Payload Preview has structured summary, table, and warning UI surfaces.
- Step 100 aligned wording toward structured pre-send review.
- The raw JSON details area was intentionally left unchanged in Step 100.
- The raw JSON details area may be inconsistent with the Step 95
  public-release posture.
- This step does not display, inspect, or record the raw payload body.

The current user value to preserve is:

```text
GA4 Fetch -> structured pre-send review -> AI Generate -> generated draft review/edit/copy
```

The current mismatch to resolve is:

```text
Normal admin UI raw JSON visibility vs. Step 95 public-release visibility policy
```

## Options Considered

| Option | Summary | Pros | Cons | Recommendation |
|---|---|---|---|---|
| Option A | Keep raw JSON visible in normal admin UI. | Convenient for developer/debug review. | Conflicts with Step 95 more easily, encourages support paste or screenshots, and is weak as a public-release posture. | Do not adopt. |
| Option B | Remove raw JSON details area from normal admin UI. | Aligns most directly with Step 95, lowers support/debug exposure risk, and makes the UI more user-oriented. | Reduces immediate developer-level troubleshooting visibility. | Strong candidate. |
| Option C | Replace raw JSON details area with structured status summary only. | Preserves MVP pre-send review value, fits Step 91 metadata, and reduces raw data exposure. | May duplicate existing structured summary/table UI unless the UI is tightened. | Strong candidate, preferably combined with Option B. |
| Option D | Hide raw JSON behind an explicit debug gate. | Keeps a developer/debug path available. | Adds public-release design questions around gate conditions, capability checks, warnings, redaction, and support policy. Too much for MVP default. | Hold for future design. |
| Option E | Provide redacted JSON export. | Could be useful as debug evidence if designed carefully. | Redaction design is hard because analytics values, page paths, sources, cities, and site context all need careful handling. This is scope creep for the MVP maturation path. | Hold. |

## Recommended Decision

Recommended public-release MVP direction:

- Remove the raw JSON details area from the normal admin UI.
- Keep Payload Preview as a structured summary / human-readable preview.
- Do not adopt a raw JSON debug gate for MVP default behavior.
- Do not adopt raw JSON export or redacted JSON export for MVP default
  behavior.
- Use existing report conditions, data availability, value semantics,
  payload status, warnings, and generation allowed/blocked state as the main
  Payload Preview UI.
- Keep the implementation limited to raw JSON details area removal and any
  necessary small structured-summary wording adjustment.
- Do not change payload data structure.
- Do not change OpenAI request payload.

Preferred implementation posture:

```text
Option B + Option C
```

That means removing the full raw JSON details area from the normal admin UI
while preserving or tightening the structured pre-send review surfaces that
already support the MVP workflow.

## Implementation Boundary For Next Step

Recommended next implementation step scope:

### In Scope

- In `includes/class-report-builder.php`, remove or stop rendering the raw
  JSON details area in the normal admin UI.
- Preserve existing structured preview, table, status, and warning UI.
- Confirm Payload Preview description and support safety note remain aligned
  with Step 100 wording.
- Confirm hidden form values, transient handling, and server-side Generate AI
  Report flow do not depend on the raw JSON UI being visible.

### Out Of Scope

- Payload data structure changes.
- OpenAI request payload changes.
- GA4 client changes.
- OpenAI client changes.
- No-data handling changes.
- Payload validation changes.
- Transient key, expiration, or validation policy changes.
- Generated report storage or history.
- Settings save logic changes.
- JavaScript changes unless later source review proves they are required.
- CSS changes unless later source review proves they are required.
- `readme.txt` updates.
- Plugin Check.
- External API calls.
- Browser screenshots.
- Debug export features.
- Redacted JSON export features.
- Credential or option value inspection.

## Verification Plan For Implementation Step

When the next implementation step removes the normal UI raw JSON details area,
run at minimum:

- `php -l includes/class-report-builder.php`
- `find includes -name '*.php' -print0 | xargs -0 -n1 php -l`
- `git diff --check`
- Source grep to confirm raw JSON details UI wording is removed or no longer
  rendered in the normal admin UI.
- Source review to confirm payload data structure remains unchanged.
- Source review to confirm OpenAI request path remains unchanged.
- Source review to confirm hidden form values, transient flow, and server-side
  generation do not depend on raw JSON UI rendering.

Optional verification only if explicitly scoped later:

- Manual browser check in `/var/www/html/wp-dev` for Payload Preview display.

Do not run during the implementation step unless explicitly scoped:

- GA4 Fetch.
- OpenAI Generate.
- Plugin Check.
- External API calls.
- Browser screenshots.
- Browser Network tab inspection.
- Credential inspection.
- Option value inspection.
- Raw payload inspection.
- Raw response inspection.
- Generated report body inspection.

## Security / Evidence Notes

This document records only status-level planning information.

It does not display, inspect, or record:

- real credentials,
- API keys,
- access tokens,
- Authorization headers,
- plugin settings option values,
- GA4 Property ID real values,
- hostname/domain real values,
- analytics values,
- page path / source / city values,
- request bodies,
- AI payload JSON,
- OpenAI request bodies,
- raw GA4/OpenAI response bodies,
- generated report bodies,
- screenshots,
- browser Network tab data,
- cookies,
- sessions,
- nonces.

## Recommended Next Step

Proceed with:

```text
Step 102: Payload Preview raw JSON removal implementation
```

Reason:

- Step 101 establishes the policy alignment plan.
- Step 102 can be a small implementation step limited to removing the raw JSON
  details area from normal admin UI rendering.
- Existing structured preview, warning UI, generation gating, payload data
  structure, and OpenAI request payload can remain unchanged.
- Debug gate and redacted export ideas should remain on `Hold` for future
  design rather than entering the MVP default path.
- `readme.txt` / privacy wording updates and Plugin Check can follow after the
  UI visibility surface is aligned.

## Explicit Non-goals

This step does not:

- change production code,
- change PHP, JavaScript, or CSS,
- change `readme.txt`,
- change admin UI behavior,
- remove, hide, gate, or restructure the raw JSON preview,
- change payload data structure,
- change OpenAI request payload,
- change GA4 client behavior,
- change OpenAI client behavior,
- add generated report persistence,
- change Settings save logic,
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
