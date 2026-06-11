# Step 84: AI Payload Preview JSON Visibility Decision Checkpoint

## Step Summary

This document records the Step 84 AI Payload Preview JSON visibility decision
checkpoint for Analytics Report AI.

The purpose is to decide which candidate posture should be reviewed next for
the full JSON preview shown as part of the AI Payload Preview flow before
public release readiness continues.

This is a docs-only decision checkpoint. It does not change production code,
`readme.txt`, Settings save logic, GA4 client behavior, OpenAI client behavior,
admin UI, JavaScript, CSS, Composer/npm configuration, release packaging, or
runtime behavior.

This is not a release-final decision. It is review material for human decision
and later implementation planning.

WordPress.org release remains `Hold`.

This document does not record real payload JSON, real analytics values, real
credentials, real GA4 property identifiers, real hostname/domain values, real
page path/source/city values, request bodies, raw external responses,
generated report bodies, nonce values, cookie values, or session values.

## Current MVP Behavior

Current behavior at category level:

- After an administrator runs the GA4 Fetch action, the plugin creates a
  temporary AI payload and displays an AI Payload Preview.
- The Payload Preview includes rendered summary tables that help the
  administrator review selected analytics categories before AI generation.
- The Payload Preview may also expose a full structured JSON preview of the
  reviewed payload.
- The preview is part of the review-before-send workflow: the administrator
  should inspect the payload before using Generate AI Report.
- The full JSON preview is useful for developer verification because it shows
  the payload contract and category structure before OpenAI generation.
- The full JSON preview is not likely to be essential for normal public
  administrators if the rendered summary tables provide enough review context.
- Full JSON visibility is high risk in public multi-admin environments,
  screenshots, copied support notes, or shared issue reports because it can
  gather multiple sensitive analytics categories into one copyable body.
- Viewing the Payload Preview alone should not call OpenAI.
- When Generate AI Report is used, the reviewed payload is sent to OpenAI API
  for report draft generation.
- Real payload bodies and real values must not be copied into release
  readiness docs or support evidence.

## Sensitive Categories In JSON Preview

| Category | Why it may appear in JSON preview | Sensitivity | Developer usefulness | Normal user usefulness | Support/screenshot risk | Candidate handling |
|---|---|---|---|---|---|---|
| Site host / hostname | It may be included as site context or host-filter context in the reviewed payload. | High: can identify the site, client, environment, or project. | Useful for confirming host filtering and payload context. | Usually low to medium; rendered status may be enough. | High if screenshots or copied JSON include identifying host context. | Hide by default, redact in JSON, or expose only in developer/debug mode. |
| Report period | Included to describe the report time window. | Medium: can reveal campaign timing or analysis windows. | Useful for validating period handling. | Useful, but summary tables can usually show it. | Medium if tied to sensitive business timing. | Keep in rendered summary; acceptable in JSON only with disclosure/warning. |
| Comparison period | Included when comparison mode is active. | Medium: can reveal comparative business timing. | Useful for validating comparison logic. | Useful, but summary tables can usually show it. | Medium if tied to campaign, incident, or seasonal context. | Keep in rendered summary; acceptable in JSON only with disclosure/warning. |
| Scope type | Included to describe site-wide, directory, or page scope. | Medium: reveals analysis intent. | Useful for validating scoping logic. | Useful in rendered preview. | Medium; lower than actual scope value. | Keep in summary tables; acceptable in debug JSON with warning. |
| Normalized directory/page scope values | Included when directory/page scope is selected. | High: can reveal content areas, campaigns, products, or private-looking paths. | Useful for verifying normalization and filter behavior. | Medium for confirming selected scope, but full JSON is not required. | High because path values are easy to copy or screenshot. | Redact in JSON, make JSON developer/debug only, or provide warning before display. |
| Aggregated summary metrics | Included as core report input. | Medium to high: reveals business performance. | Useful for checking payload contract and metric casting. | High in rendered summary tables; JSON is usually unnecessary. | High if full values are copied into support or public issues. | Keep rendered tables; avoid full JSON by default. |
| Comparison metrics / deltas / rates | Included when comparison data exists. | Medium to high: reveals performance changes. | Useful for validating calculations and payload shape. | High in rendered summary; JSON is usually unnecessary. | High when copied with real values. | Keep rendered tables; hide JSON by default or warn before display. |
| Daily trend rows | Included for narrative trend context. | Medium to high: reveals timing patterns. | Useful for row-limit and trend validation. | Medium; normal users may only need table summary. | High because row data can be copied in bulk. | Keep summarized tables; hide full JSON by default or use debug mode. |
| Top page paths | Included for content-performance report context. | High: reveals content strategy, products, campaigns, or sensitive URLs. | Useful for validating path rows and limits. | Medium to high in summary tables, but full JSON is not essential. | Very high if copied or screenshotted. | Strong candidate for redaction or developer/debug-only JSON. |
| Traffic channels | Included for acquisition category context. | Medium: reveals acquisition mix. | Useful for validating category rows. | Useful in rendered summary. | Medium. | Accept in summary; JSON with warning/debug mode. |
| Traffic sources | Included for source-level acquisition context. | High: can reveal partners, campaigns, referrers, or business dependencies. | Useful for validating source rows and limits. | Medium; summary view may be enough. | Very high if copied or shared. | Redact, aggregate, or restrict JSON to developer/debug mode. |
| City / regional analytics dimensions | Included for regional trend context where available. | High: can reveal local demand or operating focus. | Useful for validating regional rows. | Medium; not always essential. | High if real location rows appear in shared evidence. | Redact, aggregate, make optional, or restrict JSON to developer/debug mode. |
| Generated report-related context if any | Payload context may include report type or generation context before the generated report exists. | Low to medium unless it includes user-entered context. | Useful for prompt/payload contract validation. | Low. | Low to medium depending on context. | Keep category-level metadata only; do not include generated body text in docs. |
| Plugin/report metadata | Payload version, report type, language, and similar metadata may be included. | Low. | Useful for validation and compatibility checks. | Low. | Low. | Accept if JSON remains available; disclose as fixed report context. |

## Option Comparison

| Option | Description | Benefit | Risk | Privacy impact | Developer/debug usefulness | Normal admin usability | Support/debug impact | Implementation impact if later implemented | Disclosure requirement | Release-readiness implication | Candidate recommendation | Decision status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|
| Option A: Keep full JSON preview | Leave full JSON visible as it is in the current MVP. | Maximum transparency; no implementation cost; strong developer verification utility. | High copy/paste, screenshot, support sharing, and multi-admin exposure risk. | Weakest privacy posture because sensitive categories remain visible together. | Excellent. | Mixed; may overwhelm normal administrators. | High support risk unless guidance is extremely strict. | None if kept. | Strong disclosure and redaction guidance required. | Should not be accepted for public release without explicit human acceptance. | Not preferred. | Hold / Needs human decision. |
| Option B: Keep summary tables only and remove full JSON preview | Remove the full JSON view and rely on rendered preview tables. | Reduces copyable full-payload exposure while preserving review-before-send. | Less useful for developer verification and payload contract debugging. | Strong privacy improvement. | Low unless separate diagnostics exist. | Best for most normal administrators. | Easier support guidance; screenshots still need redaction. | Medium UI/PHP change later. | Disclose summary-table preview and no full JSON display. | Strong candidate if release prioritizes minimization over developer diagnostics. | Viable alternative. | Needs human decision. |
| Option C: Collapse JSON preview behind warning text | Keep JSON available but hidden behind an explicit warning/expansion step. | Preserves transparency with added friction and user awareness. | Users can still expand, copy, screenshot, or share full JSON. | Moderate improvement over always-visible JSON. | Good. | Reasonable if warning is clear and not noisy. | Support risk remains but guidance is easier to anchor. | Low to medium UI change later. | Warning and support redaction wording required. | Possible compromise if full removal/debug gating is not accepted. | Alternative candidate. | Needs human decision. |
| Option D: Redact sensitive fields in JSON preview | Show JSON structure while replacing sensitive field values with redacted placeholders. | Maintains structural insight while reducing exposure of host/path/source/city/value categories. | Preview may differ from actual OpenAI payload; users may misunderstand what is sent. | Strong improvement if redaction is complete and clearly labeled. | Medium to high for structure checks; lower for exact payload debugging. | Moderate; may be clearer than full raw JSON. | Lower support risk, but redaction correctness must be trusted. | Medium to high redaction-layer and QA work later. | Must disclose that redacted preview may differ from actual sent payload. | Good candidate if JSON structure must remain public. | Alternative candidate with design work. | Needs design / human decision. |
| Option E: Developer/debug mode only | Hide full JSON in normal public UI and expose it only when a deliberate developer/debug mode is enabled. | Keeps developer verification utility while improving default public privacy posture. | Requires a mode gate, definition, and support boundary; debug captures can still leak data. | Strong default privacy improvement. | Excellent when debug mode is enabled. | Good; normal UI can focus on summary tables. | Support can discourage debug JSON except in controlled, redacted workflows. | Medium implementation later. | Must document debug mode, warning text, and redaction rules. | Strong candidate before public release because it separates normal review from developer diagnostics. | Preferred candidate. | Recommended for human review, not final. |

## Recommended Candidate Posture

Preferred candidate:

```text
Option E: Developer/debug mode only
```

Alternative candidates:

```text
Option C: Collapse JSON preview behind warning text
Option D: Redact sensitive fields in JSON preview
```

This recommendation still requires human decision and later implementation
planning.

Rationale:

- Full JSON is useful for developer verification because it exposes payload
  structure, row categories, and contract details before OpenAI generation.
- Full JSON is probably not essential for most normal administrators if
  rendered summary tables preserve the review-before-send workflow.
- Summary tables can keep the key transparency benefit: the administrator can
  review what categories will be sent before using Generate AI Report.
- Full JSON has high copy/paste, screenshot, and support-sharing risk because
  it can combine host context, scope values, metrics, trends, page path
  categories, traffic source categories, and regional dimensions in one body.
- Once JSON visibility is decided, external services and privacy wording can
  be finalized more cleanly because the docs can state whether full payload
  JSON is normally visible, hidden, redacted, or debug-only.
- Once JSON visibility is decided, future UI work can be split into a narrow
  implementation step instead of mixed with larger OAuth or credential storage
  work.

Non-recommended baseline:

- Option A should remain on hold for public release unless humans explicitly
  accept the risk and finalize strong disclosure/support guidance.

## Human Decision Checklist

- [ ] Should public release show full JSON preview by default?
- [ ] Should full JSON preview be restricted to developer/debug mode only?
- [ ] What should enable debug mode: constant, filter, setting, query flag,
  environment flag, or another explicit mechanism?
- [ ] Should debug mode require an additional warning before JSON is shown?
- [ ] Should warning text be added even if JSON remains always visible?
- [ ] Should sensitive fields be redacted in JSON preview?
- [ ] If fields are redacted, should the redacted JSON be clearly labeled as
  different from the actual OpenAI payload?
- [ ] Are summary tables alone sufficient for review-before-send in normal
  public use?
- [ ] Should screenshots and support requests prohibit Payload Preview JSON?
- [ ] Should readme/admin/privacy wording explicitly describe JSON visibility?
- [ ] Should support/debug guidance forbid full payload JSON except in
  controlled redacted developer verification?
- [ ] Should JSON visibility be decided before finalizing AI payload category
  acceptance?

## Candidate Wording

### Candidate admin warning text for full JSON preview

Status: `Draft / Needs review`

```text
This JSON preview may include sensitive analytics categories such as report
conditions, path categories, traffic source categories, regional dimensions,
and aggregated metric values. Do not share this JSON in screenshots, support
requests, public issues, or documentation unless it has been fully redacted.
Viewing this preview does not send data to OpenAI. The reviewed payload is sent
only when you use Generate AI Report.
```

### Candidate debug-mode help text

Status: `Draft / Needs review`

```text
Full JSON preview is intended for controlled developer verification. Normal
administrators should use the rendered Payload Preview tables to review the
report data categories before generation. If debug JSON is enabled, treat the
output as sensitive and do not copy it into support requests or public records.
```

### Candidate support guidance text

Status: `Draft / Needs review`

```text
Do not send Payload Preview JSON in support requests. Describe the issue using
status-level information instead, and redact screenshots so they do not show
credentials, identifiers, hostnames, paths, traffic source values, city values,
metric values, request bodies, payload JSON, raw external API responses,
generated report text, cookies, nonces, or session values.
```

## Release Blockers / Follow-up Decisions

| Blocker / Decision Item | Status After Step 84 | Notes |
|---|---|---|
| AI Payload Preview JSON visibility not final | Hold | Preferred candidate is debug-mode-only, but human decision is still required. |
| AI payload category acceptance not final | Hold | JSON visibility affects how sensitive payload categories are displayed and supported. |
| Support/debug redaction guidance not final | Needs implementation | Candidate wording exists here, but it is not final release-facing policy. |
| External services / privacy wording not release-finalized | Hold | Final wording depends on the JSON visibility decision and payload category acceptance. |
| OAuth / token lifecycle strategy unresolved | Hold | Manual Google Access Token entry remains developer-verification oriented. |
| OpenAI API Key storage strategy unresolved | Hold | Settings-based storage needs explicit acceptance or redesign. |
| Uninstall credential cleanup policy unresolved | Hold | Credential-bearing settings cleanup needs a release decision. |
| Plugin Check / PHPCS refresh not executed | Needs review | Tooling refresh remains later release-readiness work. |
| Release package contents not reviewed | Needs review | Package contents and secret/data scan remain later work. |
| WordPress.org release remains Hold | Hold | Release readiness should not proceed until blockers are closed or explicitly deferred. |

## Recommended Next Step

Recommended next step:

```text
Step 85: Generated report body handling policy decision checkpoint
```

Rationale:

- Generated report body handling is the next highest-impact display and support
  evidence category after full payload JSON.
- The current non-persistence behavior is a strong baseline, but it should be
  explicitly accepted before release-facing privacy wording is finalized.
- A focused decision checkpoint can keep generated report storage, support
  sharing, copy behavior, and future save-history ideas separate from JSON
  visibility and credential/OAuth work.

## Existing Docs Referenced

- `docs/maturation/step78-data-minimization-privacy-review.md`
- `docs/maturation/step82-external-services-privacy-disclosure-draft.md`
- `docs/maturation/step83-ai-payload-category-acceptance-decision-matrix.md`

## Outcome

- Current MVP JSON preview behavior: documented at category level.
- Sensitive categories that may appear in JSON preview: documented without
  real values.
- Option A through Option E comparison: documented.
- Recommended candidate posture: Option E, developer/debug mode only.
- Alternative candidates: Option C warning/collapse and Option D redacted JSON.
- Human decision checklist: documented.
- Candidate admin/support wording: drafted and marked `Draft / Needs review`.
- Release blockers and follow-up decisions: documented.
- Production code changed: no.
- `readme.txt` changed: no.
- Admin UI, JavaScript, and CSS changed: no.
- External API calls performed: no.
- Real credentials, identifiers, analytics values, page paths, traffic source
  values, city values, request bodies, payload bodies, raw responses, generated
  reports, nonces, cookies, and sessions recorded: no.
- WordPress.org release position: `Hold`.
