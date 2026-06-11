# Step 91: GA4 Empty / No-data Handling Implementation Results

## Step Summary

Step 91 implements GA4 empty / no-data handling for the MVP Report Builder
flow.

Implementation follows the Step 90 selected posture:

```text
Option E: Granular handling by preset / data category
```

This step changes production PHP only within the GA4 no-data handling scope. It
does not change `readme.txt`, admin JavaScript, CSS, Settings save logic,
credential storage, GA4 request transport, OpenAI request transport, Composer,
npm, release packaging, or WordPress.org metadata.

No external API communication was performed. Plugin Check was not executed.
WordPress.org release remains **Hold**.

This document records status-level results only. It does not include real
credentials, access tokens, API keys, authorization headers, request bodies,
payload JSON, raw GA4/OpenAI responses, generated report text, GA4 property
identifiers, hostnames, page paths, traffic sources, city values, or analytics
metric values.

## Changed Files

- `includes/class-ga4-client.php`
- `includes/class-report-data-formatter.php`
- `includes/functions-utils.php`
- `includes/class-report-builder.php`
- `includes/class-prompt-builder.php`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`

## Implementation Summary

### GA4 extraction

- Summary extraction now preserves status metadata under an internal
  `_availability` key in the returned summary array.
- Summary metadata distinguishes:
  - missing row,
  - missing metric values,
  - partial metric values,
  - explicit zero values,
  - present non-zero values.
- Explicit zero summary values are classified as measured zero activity, not as
  missing data or a transport/API error.
- Detail report rows remain normal arrays. Preset-level detail availability is
  classified in the formatter from row counts.

### Payload metadata

The formatter now adds additive metadata to the AI payload:

- `payload_status`
- `data_availability`
- `value_semantics`

The metadata records only safe status/category/boolean/list information. It does
not include raw response bodies, credential values, request bodies, or additional
analytics rows.

Payload version was **not changed**. The metadata is additive to the existing
MVP payload structure, and keeping the version stable avoids a wider schema
renaming in this focused step. Validation now requires the new metadata, so any
old temporary transient payload created before Step 91 will be rejected and the
admin will be asked to fetch GA4 data again. That compatibility impact is
limited to temporary user-scoped transients.

### Classification / severity model

| Scenario | Status behavior | Generation behavior |
|---|---|---|
| Complete current-period no-data | Blocking, not normal success. | Blocked before OpenAI client. |
| Current period missing summary and all detail rows empty | Blocking. | Blocked before OpenAI client. |
| Current period missing summary but detail rows present | Warning / partial data. | Allowed with metadata context. |
| Explicit zero activity | Warning, not API error. | Allowed with metadata context. |
| Summary present but detail presets empty | Warning / partial data. | Allowed with metadata context. |
| Some detail presets empty | Warning / partial data. | Allowed with metadata context. |
| Comparison period no-data | Warning. | Allowed, with comparison limitation context. |

### Server-side generation gate

`handle_generate_ai_report()` now checks `payload_status.generation_allowed`
after payload validation and before reading settings for OpenAI generation.

If generation is blocked:

- OpenAI client is not called.
- A safe user-facing error is returned.
- Existing payload preview can still render for review when a blocked payload is
  already present.

The gate is server-side and does not rely on button state.

### Admin notice / preview warnings

Report Builder now shows safe no-data and partial-data notices outside the raw
JSON preview:

- Complete/current-period no-data shows a blocking message and does not present
  a normal `payload_created` success.
- Warning payloads show a warning notice after fetch.
- Payload Preview shows warning details before generation.
- Blocked preview payloads disable the Generate button and show a blocking
  message.

Messages are translatable and escaped. They describe only status/category-level
availability and do not include raw response bodies, identifiers, credentials,
page paths, source values, city values, or analytics metric values.

### Prompt context

`Analytics_Report_AI_Prompt_Builder::build_system_prompt()` now tells the AI to:

- treat `payload_status`, `data_availability`, and `value_semantics` as data
  availability context,
- avoid inferring missing categories,
- avoid comparison claims when comparison data is unavailable,
- distinguish measured zero activity from missing/no-data states,
- describe zero activity as measured zero activity, not as a GA4 API failure.

The OpenAI client transport was not changed.

## QA Recheck Results

QA used `/var/www/html/wp-dev`, synthetic data, and WordPress HTTP API
interception only. No request was allowed to reach GA4, OpenAI, Google OAuth, or
any other external service.

| ID | Scenario | Status | Result |
|---|---|---|---|
| NO-DATA-01 | Complete empty synthetic response | Pass | Payload metadata classified current period as `no_data`; generation was blocked. |
| NO-DATA-02 | Summary present / all detail presets empty | Pass | Payload validation passed; generation was allowed with warnings. |
| NO-DATA-03 | Some detail presets empty | Pass | Payload validation passed; generation was allowed with warnings. |
| NO-DATA-04 | All detail presets empty and summary missing | Pass | Classified as current-period no-data; generation was blocked. |
| NO-DATA-05 | Current period no-data / comparison present | Pass | Current period no-data blocked generation despite comparison availability. |
| NO-DATA-06 | Current period present / comparison no-data | Pass | Payload validation passed; generation was allowed with comparison warning. |
| NO-DATA-07 | Zero values vs missing metric values | Pass | GA4 extraction classified explicit zero as `present_zero` / `zero`; missing row and missing metric values remained missing. |
| NO-DATA-08 | Blocked payload submitted to Generate | Pass | Generation gate returned blocked; OpenAI call delta was `0`. |
| NO-DATA-09 | Warning payload submitted to Generate | Pass | Generation was allowed; OpenAI call was intercepted locally and returned a synthetic success. |
| NO-DATA-10 | No raw body / no credential leakage | Pass | QA output stayed status-level and did not print payload bodies, raw responses, credentials, or analytics values. |
| NO-DATA-11 | Browser rendering of warnings | Not tested | No real browser check was performed in this step. |
| NO-DATA-12 | Payload Preview warning visibility | Partial pass | Buffer-level Report Builder rendering confirmed warning/blocking message visibility. Real browser rendering remains untested. |

### Controlled command results

GA4 extraction helper:

```text
missing_row => missing / not reportable
missing_metric_values => missing / not reportable
explicit_zero => present_zero / reportable / zero
non_zero => present_non_zero / reportable / non_zero
```

No-data metadata and generation gate helper:

```text
NO-DATA-01, NO-DATA-04, NO-DATA-05, NO-DATA-07-missing => generation blocked
NO-DATA-02, NO-DATA-03, NO-DATA-06, NO-DATA-07-zero => generation allowed with warnings
NO-DATA-08 => blocked payload did not call OpenAI
NO-DATA-09 => warning payload reached only intercepted OpenAI path
NO-DATA-10 => status-level evidence only
```

Report Builder buffer-level helper:

```text
complete empty fetch => blocking message yes, success message no, generate button no, transient absent
zero activity fetch => blocking message no, warning message yes, generate button yes, transient present
```

## Known Limitations

- Real GA4 provider behavior was not tested.
- Real OpenAI provider behavior was not tested.
- Browser rendering was not tested with a real browser.
- The metadata is additive but now required by payload validation; old
  pre-Step-91 temporary transients will be rejected and require a new GA4 fetch.
- Warning wording is still MVP/admin-oriented and should be reviewed before
  public release.
- Payload Preview JSON remains visible as in the existing MVP flow; this step
  adds warnings outside JSON but does not resolve the broader JSON visibility
  release decision.

## Remaining Blockers

- Browser/admin warning rendering needs a real browser smoke pass.
- External API error-path QA should be rechecked after this implementation.
- Plugin Check has not been run for this implementation and should be run later
  in `wp-dev-check`.
- Support/debug redaction wording remains draft material.
- AI Payload Preview JSON visibility remains a release decision.
- Generated report handling policy remains a release decision.
- External services / privacy wording may need an update for no-data metadata.
- Google OAuth and token lifecycle remain unresolved.
- OpenAI API key storage remains unresolved.
- Uninstall credential cleanup remains unresolved.
- Release package contents have not been reviewed.
- WordPress.org release remains **Hold**.
