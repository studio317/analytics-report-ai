# Step 99: Admin UI Wording Alignment Implementation Plan

## Step Summary

Step 99 turns the Step 98 alignment plan into a concrete docs-only
implementation plan for a later admin UI wording update.

The next implementation step should align admin UI wording with the Step 95
through Step 97 visibility policies while keeping behavior stable. Step 99 does
not implement those changes.

Production PHP, JavaScript, CSS, `readme.txt`, admin UI behavior, Settings save
logic, GA4 client behavior, OpenAI client behavior, credential storage, release
packaging, and WordPress.org metadata were not changed.

Plugin Check was not executed. No external API communication was performed.
`wp-dev-check` was not touched. GA4 Fetch and OpenAI Generate were not run.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step98-admin-ui-wording-alignment-plan.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step96-generated-report-handling-policy-finalization.md`
- `docs/maturation/step95-payload-preview-json-visibility-final-decision.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`
- `docs/maturation/step93-external-api-error-path-recheck-results.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`

## Source Review Scope

The following files were reviewed at wording/UI-structure level only:

- `includes/class-report-builder.php`
- `includes/class-admin.php`
- `includes/class-settings.php`
- `readme.txt`

No real credentials, option values, raw payloads, generated report bodies,
analytics values, screenshots, browser Network data, cookies, sessions, or
nonces were inspected or recorded.

## Source Review Findings

| Area | Current structure observed | Implementation planning note |
|---|---|---|
| Payload Preview heading / description | The Report Builder has an AI Payload Preview section with explanatory copy. | Update wording to emphasize structured pre-send summary and decision readiness. |
| Payload Preview raw JSON | The Payload Preview section includes a raw JSON details area. | Do not change structure in the next wording-only implementation; plan replacement/gating separately. |
| Payload Preview tables | Summary and preset report tables are rendered as human-readable preview tables. | Preserve structured review value and avoid framing the preview as raw export. |
| Payload warnings | No-data, zero-activity, partial-data, and comparison warnings are surfaced near the preview. | Keep warnings status-level and clarify that they support generation judgment. |
| Generate AI Report helper text | Generate action is described as something to use after reviewing Payload Preview. | Align with structured-summary review and warning review. |
| Generated Report heading / description | Generated Report Draft section includes review/edit/copy wording and says generated text is not saved. | Preserve and lightly strengthen non-persistence and user-review framing. |
| Generated report textarea | Generated report body is shown in a textarea after generation. | Do not change behavior in wording step; avoid support-sharing wording. |
| Copy Report Text | Copy action exists as a user-triggered button with JS status text. | Keep as explicit user action; no persistence implication. |
| JS copy status text | Localized strings cover copied, failure, and empty states. | No change needed unless the later wording implementation adds support guidance near the button. |
| Settings guidance | Settings screen includes external service and credential storage guidance. | No immediate wording change required for Step 100 unless cross-screen consistency is needed. |
| `readme.txt` external services / payload wording | Readme explains GA4/OpenAI data flow and current Payload Preview behavior. | Leave readme for a later readme/privacy step after admin UI wording is aligned. |

## Proposed Implementation Boundaries

The next implementation step should be limited to admin UI wording changes.

### In Scope For Step 100

- Reword Payload Preview as a structured summary for pre-send review.
- Clarify that no-data, zero-activity, partial-data, and comparison warnings
  are status-level cues for deciding whether generation is appropriate.
- Reword Generated Report as a draft the user reviews, edits, and copies.
- Add or adjust wording that generated report text is not saved by the plugin.
- Add a light support/debug note telling users to share status, warning, or
  error categories rather than raw payloads or report bodies.
- Keep all changed strings translatable with the `analytics-report-ai` text
  domain.
- Escape all output using the existing project patterns.

### Out Of Scope For Step 100

- Raw JSON preview structure changes, deletion, or gating.
- AI payload data structure changes.
- OpenAI request payload changes.
- GA4 client changes.
- Generated report storage or history features.
- `readme.txt` updates.
- Plugin Check.
- External API communication.
- Settings save logic changes.
- Credential storage changes.

## Raw JSON Preview Handling

Step 95 established that full raw AI payload JSON should not remain a normal
admin UI surface for public-release posture.

The source review confirms that a raw JSON details area is currently present in
the Payload Preview section. That is a policy mismatch that needs a later plan,
but the next implementation should not combine wording changes with raw JSON
structure changes.

Recommended split:

```text
Step 100: Admin UI wording alignment implementation
Step 101: Payload Preview raw JSON replacement / gating plan
```

This split keeps Step 100 low-risk and lets Step 101 decide whether raw JSON
should be removed, replaced with a structured summary, hidden behind an
explicit debug gate, or handled in another controlled way.

## Proposed Wording Direction

The following strings are draft candidates for a later implementation step.
They are not applied to production code in Step 99.

### Payload Preview Drafts

Possible heading/description direction:

```text
Review this structured summary before sending data to AI.
```

```text
This preview focuses on report conditions, data availability, warnings, and generation readiness.
```

```text
Use the status and warning information below to decide whether to generate a report.
```

Possible support-safety note:

```text
Do not share raw payloads or generated report text in support requests.
```

### Warning / Generation Readiness Drafts

Possible wording direction:

```text
Review these status-level GA4 data warnings before generating a report.
```

```text
Generation is available, but warnings may limit what the generated draft should claim.
```

```text
Generation is blocked because the current-period data is not reportable for the selected conditions.
```

### Generated Report Drafts

Possible heading/description direction:

```text
Review and edit the generated draft before using it.
```

```text
The plugin does not save generated report text.
```

```text
Copying the report text is a user-initiated action.
```

Possible draft-safety note:

```text
This AI-generated text is a draft. Review and edit it before publishing, sharing, or sending it.
```

### Support-oriented Notice Draft

Possible compact notice:

```text
For support, share status labels, warning messages, or error categories only. Do not share credentials, raw payloads, raw responses, or generated report text.
```

If added to the admin UI, this notice should be brief and placed where it does
not interrupt the primary Report Builder flow.

## Proposed Step 100 Change Map

| File | Proposed change type | Notes |
|---|---|---|
| `includes/class-report-builder.php` | Admin UI copy updates | Primary target for Payload Preview, warnings, Generate helper, Generated Report, and support-safety note. |
| `includes/class-admin.php` | Likely no change | JS copy status text already covers operational states; revisit only if copy guidance changes. |
| `includes/class-settings.php` | Likely no change | Credential/external-service copy is already separate; avoid scope creep. |
| `readme.txt` | No change in Step 100 | Readme/privacy wording should be Step 99C or later. |

Step 100 should avoid changing CSS or JavaScript unless wording placement
requires it and the implementation scope is explicitly widened.

## Verification Plan For Later Implementation

When Step 100 implementation is executed, run:

- `php -l` for changed PHP files.
- `find includes -name '*.php' -print0 | xargs -0 -n1 php -l` if any PHP file
  changes.
- `git diff --check`.
- Wording grep/source review for:
  - `Payload Preview`
  - `Generated Report`
  - `generated report text`
  - `raw payload`
  - `support`
  - `warning`
  - `generation`
- Browser/manual check in `/var/www/html/wp-dev` only if explicitly scoped.

Do not run during the later implementation unless explicitly scoped:

- external API calls,
- GA4 Fetch,
- OpenAI Generate,
- Plugin Check,
- screenshots,
- credential inspection,
- option value inspection,
- raw payload inspection,
- generated report body inspection.

## Recommended Next Step

Proceed with:

```text
Step 100: Admin UI wording alignment implementation
```

Reason:

- Step 99 defines the specific wording targets and boundaries.
- Step 100 can be a small production PHP wording-only change.
- Raw JSON preview structure changes can remain isolated for Step 101.
- Readme/privacy wording can follow after admin UI wording is stable.

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

This document records only status-level planning information and draft wording.

It does not record real credentials, API keys, access tokens, Authorization
headers, plugin settings option values, GA4 Property IDs, hostname/domain
values, analytics values, page paths, traffic sources, city values, request
bodies, AI payload JSON, OpenAI request bodies, raw GA4/OpenAI response bodies,
generated report bodies, screenshots, browser Network tab data, cookies,
sessions, or nonces.

WordPress.org release remains `Hold`.
