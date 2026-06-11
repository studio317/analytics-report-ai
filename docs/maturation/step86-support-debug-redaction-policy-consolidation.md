# Step 86: Support and Debug Redaction Policy Consolidation

## Step Summary

This document records the Step 86 support and debug redaction policy
consolidation for Analytics Report AI.

The purpose is to consolidate the support/debug redaction guidance that has
remained a blocker or follow-up item across the recent release-readiness
checkpoints. This draft is intended to feed later `readme.txt`, admin help,
privacy wording, support guidance, QA evidence rules, screenshot rules, and
external API error-path evidence rules.

This is a docs-only policy consolidation. It does not change production code,
`readme.txt`, Settings save logic, GA4 client behavior, OpenAI client behavior,
admin UI, JavaScript, CSS, Composer/npm configuration, release packaging, or
runtime behavior.

This is not release-final wording. All policy and wording in this document is
`Draft / Needs review`.

WordPress.org release remains `Hold`.

This document does not record real credentials, real payloads, real analytics
values, real generated report bodies, real GA4 property identifiers, real
hostname/domain values, real page path/source/city values, request bodies, raw
external responses, nonce values, cookie values, or session values.

## Consolidated Redaction Policy Scope

This policy draft applies to:

- Release-readiness docs.
- QA evidence.
- Browser screenshots.
- Admin screen screenshots.
- Network/debug notes.
- Support requests.
- Public issue reports.
- Private support replies.
- Copy/paste diagnostics.
- Plugin settings screenshots.
- Payload Preview screenshots.
- Generated report screenshots.
- External API error-path evidence.
- Plugin Check, PHPCS, and package-review notes when they include file names,
  command summaries, or diagnostic excerpts.
- Any future troubleshooting guide, readme support note, privacy note, or admin
  help text derived from these maturation docs.

## Never Share / Never Record Categories

The following categories must not be shared, requested, pasted, screenshotted,
logged, or recorded in release-readiness docs, QA evidence, support threads,
public issues, private support replies, or debug notes:

- Google Access Token.
- OpenAI API Key.
- Authorization headers.
- Credential fragments, prefixes, or suffixes.
- `sk-` values.
- JWT fragments.
- `wp_options` credential values or plugin settings option values.
- GA4 Property ID real values.
- Hostname/domain real values.
- Page path real values.
- Traffic source real values.
- City/regional real values.
- Analytics metric values.
- AI payload JSON body.
- OpenAI request body.
- GA4 request body.
- GA4 raw response body.
- OpenAI raw response body.
- Generated report body.
- Nonce values.
- Cookie values.
- Session values.
- Full URLs containing sensitive paths or query values.
- Server logs containing sensitive request details.
- Database dumps.
- Option dumps.
- Browser Network tab headers, bodies, cookies, sessions, or full sensitive
  URLs.
- Screenshots that show any of the categories above.

## Allowed Evidence Categories

The following evidence may be shareable when it avoids the never-share
categories above:

| Evidence Category | Allowed Conditions / Notes |
|---|---|
| Status-level descriptions | Use high-level descriptions such as "credential missing", "permission error", "payload validation failed", or "copy action succeeded" without raw values. |
| Generic error category | Safe when it names the category of failure without headers, bodies, credentials, payload JSON, or raw provider messages containing sensitive data. |
| HTTP status code without headers/body | Allowed only when it is recorded as a number or class and no headers, request body, response body, URL identifiers, or credentials are included. |
| Screen name | Allowed when it names screens such as Settings, Report Builder, Payload Preview, or Generated Report without visible sensitive content. |
| Action name | Allowed for high-level actions such as GA4 Fetch, Generate AI Report, Copy Report Text, Save Settings, or Delete saved credential. |
| Step name | Allowed for maturation docs, QA phases, or checklist IDs. |
| Sanitized setting saved/not-saved state | Allowed only as status-level wording, for example saved/not saved, connected/not connected, enabled/disabled, without values. |
| Credential saved-state indicator without value | Allowed when it shows only that a credential category is saved or not saved. No credential value or fragment may appear. |
| Redacted screenshot | Allowed only after cropping/redaction removes credentials, identifiers, hostnames, paths, sources, city names, analytics values, payload JSON, generated report text, browser URL sensitive parts, headers, bodies, cookies, nonces, and sessions. |
| File path of changed source file | Allowed when the path itself does not include secret material or private customer data. |
| `git status` output | Allowed when it does not expose secrets through file names, generated artifacts, dump file names, or private paths. |
| `git diff --stat` / `git diff --name-only` | Allowed when file names do not expose sensitive data and full diff content is not pasted if it contains secrets or private data. |
| Test command success/failure summary | Allowed as command name plus pass/fail summary when no sensitive command output, environment variable, body, header, or option value is included. |
| Plugin/package scan summary | Allowed when it reports status-level findings and does not paste detected secrets, payloads, raw responses, option dumps, or private generated report content. |

## Redaction Matrix

| Item / Evidence Type | May Be Shared? | Required Redaction | Allowed Substitute | Risk If Mishandled | Notes |
|---|---|---|---|---|---|
| Settings screen screenshot | Yes, only if redacted | Credential fields, saved values if any, GA4 Property ID, hostname/domain, browser URL sensitive parts. | "Settings screen opens; credential saved-state indicator visible without value." | Leaks identifiers or credential state details that can enable account targeting. | Saved/not-saved state can be shown only without values. |
| Report Builder conditions screenshot | Yes, only if redacted | Dates if sensitive, scope values, path values, hostname/domain, property identifiers, browser URL sensitive parts. | "Report Builder conditions are visible; scope controls render." | Reveals analysis intent, content areas, campaigns, or site identity. | Prefer status-level notes over screenshots. |
| AI Payload Preview rendered tables screenshot | Usually avoid; allowed only if fully redacted | Analytics values, page paths, sources, city/region values, host context, scope values, dates if sensitive. | "Payload Preview rendered tables display after GA4 Fetch." | Exposes selected analytics and business context. | Use synthetic/redacted data only if screenshots are required later. |
| AI Payload Preview JSON screenshot | No for support/public evidence | Entire JSON body, all values, identifiers, paths, sources, cities, metrics, conditions. | "Payload JSON preview exists/hidden/debug-only depending on policy." | High-risk bulk exposure of the reviewed payload. | Should be prohibited in support screenshots unless a future controlled redacted process is approved. |
| Generated report textarea screenshot | Usually no; allowed only if fully redacted/cropped | Generated report body, user-edited text, business recommendations, analytics interpretation. | "Generated report textarea is visible/editable." | Exposes business-sensitive analysis and user-added confidential text. | Prefer cropped UI chrome only if needed. |
| External API error message | Yes, if normalized/status-level | Raw provider body, request body, headers, tokens, keys, endpoint identifiers, full URLs. | "GA4 authorization error category shown" or "OpenAI quota/rate-limit category shown." | Raw messages can include identifiers or diagnostic details. | External API error-path QA must stay status-level. |
| Network tab capture | No by default | Headers, bodies, cookies, sessions, full URLs, query strings, request payloads, response bodies. | HTTP status code and high-level action name only. | One of the easiest paths to leak Authorization headers, cookies, and request/response bodies. | Avoid requesting Network captures for public support. |
| `wp_options` output | No | Entire output; option values; serialized settings. | "Plugin settings option exists" only if needed and obtained without dumping values. | Can expose credentials, property IDs, hostnames, and settings. | Do not run option value dump commands for support evidence. |
| WP-CLI option command output | No for value output | Any option value, serialized data, credential status with values. | Command success/failure status without values. | Same as option dump risk. | Avoid `wp option get` for plugin settings or credential-bearing values. |
| GA4 Fetch result summary | Yes, status-level only | Analytics values, property IDs, hostnames, paths, sources, city names, raw response, request body. | "GA4 Fetch completed" or "GA4 returned empty-data category." | Reveals analytics data or identifiers. | Do not paste tables or raw responses. |
| OpenAI Generate result summary | Yes, status-level only | OpenAI request body, raw response body, generated report body, model credentials, payload JSON. | "Generate AI Report completed" or "OpenAI timeout category shown." | Leaks generated analysis or OpenAI request details. | Do not paste generated report text. |
| Generated report text | No | Entire generated body and user-edited additions. | "Generated report textarea contained output" without content. | Exposes business-sensitive interpretation and recommendations. | Support should not request full bodies. |
| Support issue description | Yes, if status-level | Credentials, identifiers, payload JSON, report text, screenshots with sensitive data. | "On Report Builder, Generate returned a normalized error category." | Users may include too much context by copying UI content. | Provide a template that asks for status-level facts only. |
| `git diff` / `git status` output | Usually yes | Secret-looking file names, dumped data files, full diffs containing secrets or private data. | `git status --short` or `git diff --stat` after checking filenames. | File names or diffs may reveal data if dumps are accidentally created. | Full diffs are not automatically safe. |
| Release package scan output | Yes, status-level only | Detected secret values, payload files, raw response files, private report text. | "No bundled credentials found" or "Potential secret-like artifact path reviewed." | Secret scanners can print the secret they detected. | Configure future scans to avoid echoing secret values. |

## Screenshot Guidance

Screenshot guidance for QA, support, release review, and documentation:

- Credentials must never be visible.
- Saved credential state may be shown only when no value or fragment is
  visible.
- GA4 Property ID must be cropped or redacted.
- Hostname/domain must be cropped or redacted.
- Payload Preview JSON should not be included in support screenshots.
- Generated report textarea content should be cropped or redacted.
- Analytics values, page paths, traffic source values, and city names must be
  cropped or redacted.
- Browser URL bar must be checked before sharing because it can contain paths,
  query values, nonces, or other sensitive data.
- Developer tools and Network tabs must not show headers, request bodies,
  response bodies, cookies, nonces, sessions, Authorization headers, or full
  URLs containing sensitive paths/query values.
- Screenshot examples should be described only at category level in
  release-readiness docs, not with real values.
- If a screenshot cannot be made safe through cropping/redaction, replace it
  with a status-level written result.

## External API Error-path Evidence Guidance

Allowed for GA4/OpenAI external API error-path QA:

- Normalized error category.
- High-level action name, such as GA4 Fetch or Generate AI Report.
- Screen name.
- Step/check ID.
- HTTP status code only if no headers, body content, credentials, endpoint
  identifiers, or sensitive URL values are included.
- Pass/fail/block/not-tested status.
- Status-level note about whether the user-facing error stayed generic and
  safe.

Prohibited for GA4/OpenAI external API error-path QA:

- Authorization header.
- Request body.
- Raw response body.
- Full endpoint URL if it includes identifiers or sensitive query values.
- Access token, API key, or fragments.
- Raw payload JSON.
- Generated report body.
- Provider response bodies or stack traces that include identifiers, request
  metadata, or sensitive values.
- Browser Network exports.
- Screenshots of raw request/response panels.

## Candidate Public Support Wording

### Candidate public support redaction guidance

Status: `Draft / Needs review`

```text
When requesting support, do not share API keys, access tokens, Authorization
headers, credential fragments, option values, GA4 property identifiers,
hostnames, page paths, traffic source values, city values, analytics metric
values, Payload Preview JSON, request bodies, raw API responses, generated
report text, cookies, nonces, or session values. Describe the problem using
status-level information such as the screen, action, and generic error
category.
```

### Candidate admin help warning

Status: `Draft / Needs review`

```text
Analytics report data and generated report drafts can contain sensitive
business information. Before sharing screenshots or support details, redact
identifiers, analytics values, payload JSON, generated report text,
credentials, cookies, nonces, and session values.
```

### Candidate QA evidence rule

Status: `Draft / Needs review`

```text
QA evidence should record status-level results only. Do not copy raw request
bodies, raw response bodies, Authorization headers, option values, Payload
Preview JSON, generated report bodies, or screenshots that show sensitive
analytics data.
```

### Candidate screenshot sharing warning

Status: `Draft / Needs review`

```text
Before sharing a screenshot, check the full image including the browser URL
bar and any developer tools panels. Crop or redact credentials, identifiers,
hostnames, paths, sources, city names, metric values, payload JSON, generated
report text, cookies, nonces, and sessions. If the screenshot cannot be made
safe, use a written status-level summary instead.
```

### Candidate external API debug warning

Status: `Draft / Needs review`

```text
Do not share Network tab captures, request bodies, response bodies, headers,
full endpoint URLs, access tokens, API keys, or raw provider responses when
debugging Google Analytics Data API or OpenAI API errors. Share only the
screen, action, normalized error category, and safe status code when needed.
```

## Relationship To Previous Checkpoints

| Checkpoint / Blocker | Relationship |
|---|---|
| Step 82 external services / privacy disclosure draft | Step 82 drafted provider and privacy wording; this policy supplies the redaction rules that future disclosure/support text should reference. |
| Step 83 AI payload category acceptance matrix | Step 83 identified sensitive payload categories; this policy defines what may or may not be shared as evidence for those categories. |
| Step 84 Payload Preview JSON visibility checkpoint | Step 84 treated full JSON preview as high-risk; this policy states that Payload Preview JSON should not be shared in support screenshots or copied diagnostics. |
| Step 85 generated report body handling checkpoint | Step 85 preferred generated report non-persistence; this policy states that generated report bodies should not be shared or recorded in support/evidence. |
| Credential / OAuth strategy blockers | Until OAuth/token lifecycle and credential storage are decided, support must avoid any credential value, option dump, header, or token fragment. |
| OpenAI API Key storage strategy blockers | Until OpenAI key storage is decided, support must avoid key values, fragments, option dumps, and settings screenshots that expose key material. |
| Uninstall cleanup blockers | Cleanup policy remains separate, but redaction guidance reduces the chance of credential-bearing data being copied into support or docs before cleanup is resolved. |

## Release Blockers / Follow-up Decisions

| Blocker / Decision Item | Status After Step 86 | Notes |
|---|---|---|
| Support/debug redaction guidance not final | Needs review | This consolidated policy is draft material and not final release wording. |
| AI Payload Preview JSON visibility not final | Hold | Redaction policy recommends not sharing JSON, but visibility decision still needs human approval. |
| Generated report handling policy not final | Needs human decision | Non-persistence and no full-body support sharing remain preferred candidates. |
| AI payload category acceptance not final | Hold | Sensitive category acceptance still needs human decision. |
| External services / privacy wording not release-finalized | Hold | Future wording should incorporate the consolidated redaction rules after review. |
| OAuth / token lifecycle strategy unresolved | Hold | Manual Google Access Token entry remains developer-verification oriented. |
| OpenAI API Key storage strategy unresolved | Hold | Settings-based key storage needs explicit acceptance or redesign. |
| Uninstall credential cleanup policy unresolved | Hold | Credential-bearing settings cleanup needs a release decision. |
| External API error-path QA not executed | Hold | This policy can guide later error-path QA evidence. |
| Plugin Check / PHPCS refresh not executed | Needs review | Tooling refresh remains later release-readiness work. |
| Release package contents not reviewed | Needs review | Package contents and secret/data scan remain later work. |
| WordPress.org release remains Hold | Hold | Release readiness should not proceed until blockers are closed or explicitly deferred. |

## Recommended Next Step

Recommended next step:

```text
Step 87: External API error-path QA execution plan
```

Rationale:

- External API error-path QA is still unexecuted and repeatedly appears as a
  release-readiness follow-up.
- This Step 86 redaction policy gives the evidence boundaries needed before
  planning or executing GA4/OpenAI failure-path checks.
- The next step should remain a plan/checklist unless a later explicit request
  authorizes real external API error-path execution.

## Existing Docs Referenced

- `docs/maturation/step72-credential-external-services-disclosure-review.md`
- `docs/maturation/step78-data-minimization-privacy-review.md`
- `docs/maturation/step82-external-services-privacy-disclosure-draft.md`
- `docs/maturation/step83-ai-payload-category-acceptance-decision-matrix.md`
- `docs/maturation/step84-ai-payload-preview-json-visibility-decision-checkpoint.md`
- `docs/maturation/step85-generated-report-body-handling-policy-decision-checkpoint.md`

## Outcome

- Consolidated support/debug redaction scope: documented.
- Never-share / never-record categories: documented.
- Allowed evidence categories: documented.
- Redaction matrix: documented.
- Screenshot guidance: documented.
- External API error-path evidence guidance: documented.
- Candidate public support/admin/QA/screenshot/API debug wording: drafted and
  marked `Draft / Needs review`.
- Relationship to previous checkpoints: documented.
- Release blockers and follow-up decisions: documented.
- Production code changed: no.
- `readme.txt` changed: no.
- Admin UI, JavaScript, and CSS changed: no.
- External API calls performed: no.
- Real credentials, identifiers, analytics values, page paths, traffic source
  values, city values, request bodies, payload bodies, raw responses, generated
  reports, nonces, cookies, and sessions recorded: no.
- WordPress.org release position: `Hold`.
