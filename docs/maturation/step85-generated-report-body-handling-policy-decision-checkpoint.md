# Step 85: Generated Report Body Handling Policy Decision Checkpoint

## Step Summary

This document records the Step 85 generated report body handling policy
decision checkpoint for Analytics Report AI.

The purpose is to organize the public release readiness posture for the
generated report body returned by OpenAI, displayed in the admin textarea, and
edited/copied by the administrator.

This is a docs-only decision checkpoint. It does not change production code,
`readme.txt`, Settings save logic, GA4 client behavior, OpenAI client behavior,
admin UI, JavaScript, CSS, Composer/npm configuration, release packaging, or
runtime behavior.

This is not a release-final decision. It is review material for human decision
and later implementation planning.

WordPress.org release remains `Hold`.

This document does not record real generated report bodies, real payloads, real
analytics values, real credentials, real GA4 property identifiers, real
hostname/domain values, real page path/source/city values, request bodies, raw
external responses, nonce values, cookie values, or session values.

## Current MVP Behavior

Current behavior at category level:

- After an authorized administrator uses the OpenAI Generate action, OpenAI
  returns a generated report body category.
- The generated report body is displayed in an admin textarea.
- The administrator can edit the textarea content before using it elsewhere.
- The workflow includes copy-to-clipboard behavior for the current textarea
  content.
- The current MVP does not persist the generated report body as plugin data.
- The generated report body can contain business-sensitive analysis because it
  may interpret analytics trends, page/content performance, traffic sources,
  regional patterns, and possible business implications.
- Support screenshots, copied text, public issue reports, and shared documents
  are high-risk channels for accidental disclosure of generated report content.
- Generated report bodies must not be copied into release-readiness docs, QA
  evidence, public support issues, or debugging notes.

## Sensitivity Assessment

| Category | Why it may appear in generated report body | Sensitivity | User value | Support/screenshot risk | Candidate handling |
|---|---|---|---|---|---|
| Aggregated analytics interpretation | The generated draft summarizes the reviewed aggregate analytics payload. | Medium to high: can reveal business performance and audience behavior. | High, because interpretation is the core report value. | High if full text is pasted into support or screenshots. | Continue display/edit/copy, but do not persist as plugin data and require redaction guidance. |
| Performance trend commentary | The generated draft may explain period trends and changes. | High when it reveals growth, decline, incidents, campaign effects, or seasonality. | High for reporting. | High if commentary exposes operational timing or performance shifts. | Preserve non-persistence; avoid full-body support sharing. |
| Page / content performance commentary | The generated draft may discuss page or content performance categories. | High when content areas, campaigns, products, or internal naming are implied. | High for content-performance reporting. | High if shared publicly or in support tickets. | Redact or summarize before support; avoid saving history in first public release. |
| Traffic source / acquisition commentary | The generated draft may discuss acquisition channels or source categories. | High when sources imply partners, campaigns, referrers, or dependencies. | High for marketing analysis. | High if copied outside intended reporting workflow. | Strong support guidance; do not record full generated text in docs. |
| City / regional commentary | The generated draft may discuss regional patterns if those categories are in the payload. | High when location patterns reveal demand or operational focus. | Medium to high depending on report needs. | High if real location commentary is shared. | Pair with later payload category decisions and redact from support evidence. |
| Business implication or recommendation wording | The generated draft may include analysis, risks, suggested focus areas, or next actions. | High: can reveal strategy, priorities, or internal decision context. | High, because recommendations make the report useful. | Very high in public issues or vendor support channels. | Continue non-persistence; discourage support sharing; consider admin warning later. |
| User-edited additions in textarea | Administrators can edit the generated draft and may add their own details. | Potentially critical if users add confidential or personal information. | High for workflow flexibility. | Very high because user-added content may exceed plugin-generated categories. | Treat textarea content as sensitive user-controlled content; do not persist; avoid support collection. |
| Copy-to-clipboard destination outside plugin control | Copied content can be pasted into documents, messaging tools, CMS posts, or support systems. | Depends on destination; can become high once shared outside WordPress. | High for workflow completion. | High because the plugin cannot control where copied content goes. | Clarify that copy action does not add plugin persistence but destination handling is user responsibility. |

## Handling Option Comparison

| Option | Description | Benefit | Risk | Privacy impact | User workflow impact | Support/debug impact | Implementation impact if later implemented | Disclosure requirement | Release-readiness implication | Candidate recommendation | Decision status |
|---|---|---|---|---|---|---|---|---|---|---|---|
| Option A: Continue non-persistence as current MVP | Keep generated report text displayed in the admin textarea without saving it as plugin data. | Strong privacy-positive baseline; keeps storage scope small; avoids new cleanup/export obligations. | Content can still leak through screenshots, clipboard, browser state, or support sharing. | Best current posture because the plugin avoids persistent generated body storage. | Preserves current edit/copy workflow. | Support must still prohibit full generated body sharing. | None if preserved. | Disclose that generated drafts are displayed for review/edit/copy and are not persisted as plugin data. | Strong candidate for public release baseline after human confirmation. | Preferred baseline. | Needs human confirmation. |
| Option B: Add optional save history | Add plugin-managed storage for generated report history. | Convenient for users who want draft history or audit trail. | Adds sensitive persistent content, deletion needs, permissions, export/privacy obligations, and uninstall complexity. | Significantly increases privacy and data retention risk. | Improves convenience but expands product scope. | Support/debug guidance becomes harder because stored drafts can be requested or exposed. | High: storage schema, UI, permissions, deletion, migration, retention, and uninstall policy. | Must disclose persistent report body storage, retention, deletion, and access scope. | Not suitable for first public release without dedicated design. | Future/out-of-scope for first public release. | Hold / Not recommended now. |
| Option C: Add copy-only / no-save explicit policy | Keep current behavior and make the no-save policy explicit in readme/admin/privacy wording. | Clarifies expectations and reinforces privacy posture. | Does not prevent users from copying content elsewhere. | Positive if paired with support guidance. | Low friction; matches current workflow. | Helps support avoid asking for full body text. | Low if implemented as wording later. | Wording should state that copy is user-controlled and does not add plugin persistence. | Strong companion to Option A. | Recommended wording/support addition. | Needs human approval. |
| Option D: Add admin warning not to paste sensitive content into support | Add a visible warning or help text around generated report support sharing. | Directly addresses likely leakage path. | Extra admin copy may create warning fatigue if overused. | Positive because users are warned before support sharing. | Minimal workflow impact if placed carefully. | Strong support benefit. | Low to medium depending on placement. | Warning must be concise and translatable if implemented later. | Strong companion to Option A and C after support policy is finalized. | Recommended wording/support addition. | Needs human approval. |
| Option E: Add explicit clear/reset behavior after generation | Add a deliberate UI action to clear generated textarea content after review/copy. | Gives administrators a visible way to remove generated text from the current screen. | May create false confidence if browser history, clipboard, or copied destinations still hold content. | Moderate improvement for shared admin screens. | Useful but adds another control/state to understand. | Can reduce screenshot risk after clearing, but support still needs redaction rules. | Medium UI/JS/PHP behavior work later. | Must explain what clear/reset does and does not remove. | Optional later UX improvement, not a blocker if non-persistence is accepted. | Optional later UX consideration. | Needs human decision. |

## Recommended Candidate Posture

Preferred baseline:

```text
Option A: Continue non-persistence as current MVP
```

Recommended wording/support addition:

```text
Option C: Add copy-only / no-save explicit policy
Option D: Add admin warning not to paste sensitive content into support
```

Future/out-of-scope for first public release:

```text
Option B: Add optional save history
```

Optional later UX consideration:

```text
Option E: Add explicit clear/reset behavior after generation
```

This recommendation still requires human decision and later implementation
planning.

Rationale:

- The generated report body is central to the feature, but it has high privacy
  and business sensitivity because it can summarize performance, acquisition,
  content, regional patterns, and recommendations.
- Non-persistence is a strong privacy-positive baseline for release readiness
  because it avoids adding generated-report storage, retention, deletion,
  export, and uninstall obligations.
- Save history would be useful for workflow convenience, but it would
  materially expand storage, deletion, uninstall, support, and privacy policy
  complexity.
- The copy action does not add plugin persistence, but destinations after copy
  are outside plugin control.
- Support/debug guidance should prohibit, or at minimum strongly discourage,
  sharing full generated report bodies.
- Once non-persistence is explicitly accepted, release-facing privacy wording
  can state the generated draft behavior more clearly.

## Human Decision Checklist

- [ ] Should public release adopt the policy that generated report bodies are
  not persisted as plugin data?
- [ ] Should save history be explicitly excluded from the first public release?
- [ ] Should copy-only / no-save policy be stated in `readme.txt`, admin help,
  and privacy wording?
- [ ] Should full generated report bodies be prohibited in support/debug
  requests?
- [ ] Should admin warning text be added near the generated report textarea?
- [ ] Is explicit clear/reset behavior required before public release?
- [ ] Should generated report body non-persistence be stated directly in the
  admin UI?
- [ ] Should generated report body handling be reflected in release-facing
  privacy wording?
- [ ] Should support guidance distinguish between status-level issue
  descriptions and copied generated report content?
- [ ] Should any future save-history feature require a separate storage,
  retention, deletion, and uninstall design?

## Candidate Wording

### Candidate admin help text for generated report body

Status: `Draft / Needs review`

```text
The generated report draft is displayed here for review, editing, and copying.
In the current workflow, the plugin does not save the generated report body as
plugin data. Review the text before using it outside WordPress.
```

### Candidate privacy notice wording for non-persistence

Status: `Draft / Needs review`

```text
When an administrator generates a report, OpenAI returns a draft report body
for display in the plugin admin screen. The administrator can edit and copy
the draft. The plugin does not persist the generated report body as plugin data
in the current workflow.
```

### Candidate support guidance text

Status: `Draft / Needs review`

```text
Do not send full generated report bodies in support requests, public issues,
screenshots, or release-readiness evidence. Describe the problem using
status-level information and redact any analytics interpretation, business
recommendations, identifiers, paths, traffic source values, city values,
payload bodies, request bodies, raw external API responses, credentials,
cookies, nonces, and session values.
```

### Candidate copy behavior note

Status: `Draft / Needs review`

```text
The copy action copies the current textarea content under administrator
control. It does not add plugin-side persistence. After copying, the
destination where the text is pasted is outside the plugin's control.
```

## Relationship To Other Blockers

| Related Blocker | Relationship To Generated Report Body Handling |
|---|---|
| AI Payload Preview JSON visibility | JSON preview determines pre-generation payload exposure; generated report body handling determines post-generation output exposure. Both need support redaction guidance. |
| AI payload category acceptance | Generated report content is derived from accepted payload categories, so sensitive payload categories can reappear as natural-language analysis. |
| External services / privacy wording | OpenAI disclosure should explain that a generated draft is returned for display/edit/copy and whether it is persisted by the plugin. |
| Support/debug redaction guidance | Generated report bodies should be treated as sensitive support evidence and should not be requested in full. |
| Uninstall credential cleanup policy | If generated report bodies remain non-persistent, uninstall cleanup does not need to handle generated report history; if save history is added later, uninstall policy must expand. |
| OpenAI API Key storage strategy | Key storage is separate from generated body handling, but both appear in OpenAI/privacy disclosure and support guidance. |
| OAuth / token lifecycle strategy | Google auth does not directly control generated body persistence, but unresolved auth keeps release on hold and affects the broader privacy/disclosure package. |

## Release Blockers / Follow-up Decisions

| Blocker / Decision Item | Status After Step 85 | Notes |
|---|---|---|
| Generated report handling policy not final | Needs human decision | Preferred baseline is current non-persistence with stronger wording/support guidance. |
| AI Payload Preview JSON visibility not final | Hold | Step 84 preferred debug-mode-only, but human decision remains. |
| AI payload category acceptance not final | Hold | Generated report body sensitivity depends on what categories are sent to OpenAI. |
| Support/debug redaction guidance not final | Needs implementation | Generated report body sharing rules should be included in consolidated guidance. |
| External services / privacy wording not release-finalized | Hold | Wording should include generated draft return, review/edit/copy, and non-persistence. |
| OAuth / token lifecycle strategy unresolved | Hold | Manual Google Access Token entry remains developer-verification oriented. |
| OpenAI API Key storage strategy unresolved | Hold | Settings-based key storage needs explicit acceptance or redesign. |
| Uninstall credential cleanup policy unresolved | Hold | Credential-bearing settings cleanup still needs decision; generated body non-persistence would avoid adding report-history cleanup. |
| Plugin Check / PHPCS refresh not executed | Needs review | Tooling refresh remains later release-readiness work. |
| Release package contents not reviewed | Needs review | Package contents and secret/data scan remain later work. |
| WordPress.org release remains Hold | Hold | Release readiness should not proceed until blockers are closed or explicitly deferred. |

## Recommended Next Step

Recommended next step:

```text
Step 86: Support and debug redaction policy consolidation
```

Rationale:

- Recent checkpoints repeatedly identify support/debug sharing as the place
  where credentials, identifiers, payload JSON, generated report text, and raw
  diagnostic data can leak.
- A consolidated support/debug redaction policy can feed later `readme.txt`,
  admin help, privacy wording, screenshot rules, and external API QA evidence.
- This should remain docs-only unless a later explicit implementation step
  requests UI or documentation changes.

## Existing Docs Referenced

- `docs/maturation/step78-data-minimization-privacy-review.md`
- `docs/maturation/step82-external-services-privacy-disclosure-draft.md`
- `docs/maturation/step83-ai-payload-category-acceptance-decision-matrix.md`
- `docs/maturation/step84-ai-payload-preview-json-visibility-decision-checkpoint.md`

## Outcome

- Current MVP generated report body behavior: documented at category level.
- Generated report body sensitivity assessment: documented without real
  generated text or real values.
- Option A through Option E comparison: documented.
- Recommended candidate posture: continue non-persistence as the baseline,
  add copy-only/no-save wording, and add support warning guidance.
- Save history: recorded as future/out-of-scope for first public release.
- Human decision checklist: documented.
- Candidate admin/privacy/support/copy wording: drafted and marked
  `Draft / Needs review`.
- Relationship to other blockers: documented.
- Release blockers and follow-up decisions: documented.
- Production code changed: no.
- `readme.txt` changed: no.
- Admin UI, JavaScript, and CSS changed: no.
- External API calls performed: no.
- Real credentials, identifiers, analytics values, page paths, traffic source
  values, city values, request bodies, payload bodies, raw responses, generated
  reports, nonces, cookies, and sessions recorded: no.
- WordPress.org release position: `Hold`.
