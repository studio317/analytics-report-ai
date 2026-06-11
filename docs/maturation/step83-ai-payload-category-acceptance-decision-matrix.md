# Step 83: AI Payload Category Acceptance Decision Matrix

## Step Summary

This document records the Step 83 AI payload category acceptance decision
matrix for Analytics Report AI.

The purpose is to organize the AI payload, admin display, external service,
generated report, support/debug, and credential-bearing categories into review
material for public release readiness decisions.

This is a docs-only step. It does not change production code, `readme.txt`,
Settings save logic, GA4 client behavior, OpenAI client behavior, admin UI,
JavaScript, CSS, Composer/npm configuration, release packaging, or runtime
behavior.

This is not a release-final decision. The postures below are candidate review
material intended to help humans decide whether each category should be
accepted as-is, accepted with disclosure, minimized, redacted, made
configurable, removed before public release, or held for further decision.

WordPress.org release remains `Hold`.

No real analytics data, real credentials, real identifiers, real payload
bodies, real request bodies, raw external responses, generated report bodies,
nonce values, cookie values, or session values are recorded in this document.

## Decision Criteria

Use these criteria when deciding the release posture for each payload or admin
display category:

- Privacy sensitivity: Could this category reveal personal, location,
  behavioral, or otherwise sensitive information?
- Business sensitivity: Could this category reveal performance, campaigns,
  editorial strategy, partners, operational issues, or revenue-adjacent
  information?
- Site/client identifiability: Could this category identify the site, client,
  environment, GA4 property, or private project?
- Usefulness for report generation: Is the category necessary for a useful
  Japanese report draft, or only helpful in edge cases?
- Further aggregation: Can the category be grouped, row-limited, bucketed, or
  summarized without materially weakening the report?
- Redaction or normalization: Can the category be normalized, redacted, or
  replaced with category-level labels before display or OpenAI submission?
- Administrator preview: Is review in Payload Preview enough mitigation, or is
  the category too sensitive for preview alone?
- Disclosure sufficiency: Is clear readme/admin/privacy wording enough, or is
  a runtime behavior change needed?
- Support/debug practicality: Can support guidance realistically prevent users
  from sharing this category in screenshots, option dumps, payload JSON, or
  copied text?
- Public multi-admin risk: Would additional administrators seeing this
  category increase privacy or business exposure?
- Release dependency: Can WordPress.org release proceed without resolving this
  category, or should release remain blocked until it is decided?

## Category Decision Matrix

| Category | Current MVP behavior | Sent to GA4? | Sent to OpenAI? | Displayed in admin? | Stored by plugin? | Privacy / business sensitivity | Usefulness for report generation | Risk notes | Possible mitigations | Candidate release posture | Decision status | Implementation impact if changed | Disclosure / redaction requirement |
|---|---|---:|---:|---:|---:|---|---|---|---|---|---|---|---|
| Site host / hostname | Can be configured in Settings, used for host filtering, and included as site context in the reviewed payload. | Yes, when host filtering is enabled. | Yes, as context when present in payload. | Yes, as configuration/status context. | Yes, in plugin settings; may also be in temporary payload. | High: identifies a site, client, or environment. | Useful for scoped reports and explaining site context. | Site/client identification in payload preview, screenshots, and support notes. | Make inclusion configurable; redact from JSON preview; disclose clearly; require screenshot redaction. | Hold / Needs human decision, with likely Accept with disclosure or Make configurable. | Needs decision | Medium if configurable or removed from payload; low if disclosure-only. | Must disclose when included and must not record real hostnames/domains in docs/support. |
| GA4 Property ID category | Stored in Settings and used to target GA4 API requests; designed not to be included in the AI payload. | Yes, as property identifier category. | No, by design. | Yes, as configuration value in Settings/admin context. | Yes, in plugin settings. | High: identifies the analytics property. | Required for GA4 fetch, not useful for OpenAI report text. | Support screenshots or option dumps can expose identifiers. | Keep out of OpenAI payload; redact in support; avoid option dumps. | Accept with disclosure for GA4 use; Redact from docs/support; keep excluded from OpenAI payload. | Needs review | High if removed from GA4; low for redaction guidance. | Must disclose GA4 use and forbid real property IDs in evidence. |
| Report period | Used in GA4 requests and AI payload context. | Yes. | Yes. | Yes. | Temporary payload after fetch. | Medium: reveals reporting cadence and campaign timing. | Required for meaningful report generation. | Could reveal sensitive analysis windows. | Keep; disclose; avoid screenshots with real business context when sensitive. | Accept with disclosure. | Needs review | High if removed; report quality would suffer. | Disclose as report condition category. |
| Comparison period | Used when comparison is enabled. | Yes, when enabled. | Yes, when enabled. | Yes. | Temporary payload after fetch. | Medium: reveals comparative timing and performance framing. | Very useful for period-over-period analysis. | Could reveal campaign, incident, or seasonality context. | Keep optional via comparison setting; disclose. | Accept with disclosure. | Needs review | Medium if removed; comparison reports weaken. | Disclose as optional comparison category. |
| Scope type | Indicates site-wide, directory, or page scope. | Yes, affects request filtering. | Yes, as report condition. | Yes. | Temporary payload after fetch. | Medium: reveals analysis intent. | Required to explain what the report covers. | Lower risk than the actual scope value. | Keep; disclose. | Accept with disclosure. | Needs review | Medium if removed. | Disclose as report condition category. |
| Normalized directory scope value | Normalized directory path category used for directory-scoped reports. | Yes, when selected. | Yes, when selected. | Yes. | Temporary payload after fetch. | High: can reveal content areas, campaigns, products, or internal naming. | Useful for focused directory reports. | Path category may be sensitive even after normalization. | Make configurable; redact from JSON preview; allow summary-only display; disclose and redaction guidance. | Hold / Needs human decision; candidate Make configurable or Accept with disclosure and redaction. | Needs decision | Medium if made configurable; high if removed from directory reports. | Must disclose and never record real path values in support/docs. |
| Normalized page scope value | Normalized page path category used for page-scoped reports. | Yes, when selected. | Yes, when selected. | Yes. | Temporary payload after fetch. | High: can reveal specific content, campaigns, products, or private-looking paths. | Useful for page-specific reports. | More identifiable than directory scope. | Make configurable; redact from JSON preview; consider path label abstraction; disclose strongly. | Hold / Needs human decision; candidate Make configurable or Minimize. | Needs decision | Medium to high depending on redaction/removal. | Must disclose and never record real page paths. |
| Aggregated summary metrics | Selected aggregate metrics are included in preview and payload. | Received from GA4, not sent as input except requested metric categories. | Yes, after preview/generation. | Yes. | Temporary payload after fetch. | Medium to high: reveals business performance. | Core report input. | Useful but sensitive in public support/screenshots. | Keep row/field limits; disclose; support redaction. | Accept with disclosure. | Needs review | High if removed; report value collapses. | Disclose aggregate metrics and avoid recording values. |
| Comparison metrics / deltas / rates | Calculated comparison results included when comparison exists. | No as calculated values; GA4 receives the underlying period requests. | Yes, when comparison exists. | Yes. | Temporary payload after fetch. | Medium to high: reveals performance changes. | Important for useful analysis. | Reveals growth/decline and campaign effects. | Keep optional via comparison; disclose; redact values in support. | Accept with disclosure. | Needs review | Medium if removed. | Disclose comparison metrics/deltas/rates as payload categories. |
| Daily trend rows | Trend rows are fetched/formatted for the report period. | Received from GA4 after requested dimensions/metrics. | Yes. | Yes. | Temporary payload after fetch. | Medium to high: reveals timing patterns. | Useful for narrative and anomaly detection. | Can reveal publishing cadence, campaign timing, or incidents. | Row limits; aggregate further; disclose; avoid support screenshots with real values. | Accept with row limits and disclosure. | Needs review | Medium if aggregated further; low if only disclosure. | Disclose trend rows and never record real values. |
| Top page paths | Top page path categories are included in preview and payload. | Received from GA4 after requested dimensions/metrics. | Yes. | Yes. | Temporary payload after fetch. | High: reveals content strategy, private-looking URLs, products, campaigns, or customer context. | Very useful for content-performance reports. | One of the most sensitive analytics categories. | Row limits; make optional; redact from JSON preview; path grouping; disclosure; support redaction. | Hold / Needs human decision; candidate Accept with row limits and disclosure or Make configurable. | Needs decision | Medium if optional; high if removed from content reports. | Strong disclosure and redaction required; real paths must not be recorded. |
| Traffic channels | Channel categories are included in preview and payload. | Received from GA4. | Yes. | Yes. | Temporary payload after fetch. | Medium: reveals acquisition mix. | Useful for marketing context. | Less identifying than source-level values. | Keep; disclose; aggregate as channel categories. | Accept with disclosure. | Needs review | Low to medium. | Disclose channel categories. |
| Traffic sources | Source categories are included in preview and payload. | Received from GA4. | Yes. | Yes. | Temporary payload after fetch. | High: can reveal partners, campaigns, referrers, and business dependencies. | Useful for acquisition analysis. | Source names can be sensitive and identifying. | Make optional; aggregate to channel where possible; redact from JSON preview; disclose strongly. | Hold / Needs human decision; candidate Make configurable or Accept with disclosure/redaction. | Needs decision | Medium if optional/aggregated; high if removed entirely. | Strong disclosure and support redaction required; real sources must not be recorded. |
| City / regional analytics dimensions | Regional/city dimension category may be included in preview and payload. | Received from GA4. | Yes. | Yes. | Temporary payload after fetch. | High: local patterns can be sensitive. | Useful for geographic trend context, but not always essential. | City-level data can expose local demand or operational focus. | Aggregate to broader region; make optional; remove from OpenAI payload; disclose if retained. | Hold / Needs human decision; candidate Minimize, Make configurable, or Remove from OpenAI payload. | Needs decision | Medium if aggregated; low to medium if made optional; medium if removed. | Disclose regional category if retained; never record real city names. |
| AI Payload Preview rendered summary tables | Admin tables show selected payload categories before OpenAI generation. | No. | No by viewing alone; data can later be sent when Generate is used. | Yes. | Backed by temporary payload transient. | Medium to high depending on categories displayed. | High for administrator review-before-send. | Screenshots can expose sensitive analytics. | Keep review tables; add redaction guidance; maybe hide high-sensitivity rows by default later. | Accept only after admin review, with disclosure/redaction. | Needs review | Medium if redesigned. | Must explain preview purpose and screenshot redaction. |
| AI Payload Preview JSON body | Full structured preview is visible in the admin preview area. | No. | No by viewing alone; same content can later be sent to OpenAI. | Yes. | Backed by temporary payload transient. | High: copyable combined payload with many sensitive categories. | Useful for developer verification and transparency; less necessary for normal users. | Public multi-admin, support, and screenshot exposure risk is high. | Remove; collapse behind warning; redact sensitive fields; developer/debug mode only. | Hold / Needs human decision; candidate Developer/debug mode only or Collapse with warning. | Needs decision | Medium depending on chosen UI behavior. | Strong disclosure/redaction required if retained. |
| OpenAI prompt instructions | Fixed instructions are sent with payload for report generation. | No. | Yes. | Not displayed as full request body. | Not persisted as plugin data in reviewed flow. | Low: little direct privacy risk. | Required to shape report output and safety. | Must avoid implying prompt body should be copied into support docs. | Keep; disclose as fixed report-generation instructions. | Accept with disclosure. | Known pass / Needs disclosure review | Low if wording changes only. | Disclose category, not full request body. |
| OpenAI selected model name | Selected model name is included in the OpenAI request. | No. | Yes. | May be referenced in settings/admin text depending on UI. | Stored as settings/default configuration category if configured by plugin. | Low privacy; medium cost/support relevance. | Required for API request. | Model changes affect cost, quality, and support expectations. | Keep; disclose cost/quota/model category. | Accept with disclosure. | Needs review | Low. | Disclose model category and cost/quota responsibility. |
| Generated report body | Returned by OpenAI and displayed as an editable draft; not persisted as plugin data in reviewed MVP flow. | No. | Received from OpenAI, not sent onward by plugin copy action. | Yes. | Not persisted as plugin data in reviewed flow. | High: can summarize sensitive business analysis. | Core feature output. | Support screenshots or copied text can leak business context. | Preserve non-persistence; add support warning; avoid save history unless explicitly designed. | Accept with disclosure; continue non-persistence. | Needs human confirmation | Medium if persistence/history added; low if policy/docs only. | Disclose non-storage and forbid full generated bodies in support/docs. |
| Admin textarea output | Editable textarea displays the generated draft and allows manual edits. | No. | No external request by editing. | Yes. | Not persisted as plugin data in reviewed flow. | High if generated or edited content contains sensitive analysis. | Required for review/edit/copy workflow. | Edited content may contain user-added sensitive text. | Keep; disclose not saved by plugin; support redaction guidance. | Accept with disclosure. | Needs review | Medium if UI warning added later. | Do not record generated/edited body text. |
| Copy-to-clipboard behavior | Copies current textarea content under administrator control. | No. | No external request by plugin copy action. | Status feedback only. | Not stored by plugin; browser/OS clipboard is outside plugin storage. | Medium to high depending on copied text. | Useful for workflow completion. | User can paste sensitive text elsewhere. | Keep; disclose user responsibility; support warning. | Accept as-is with disclosure. | Needs review | Low. | Explain copy action does not add plugin persistence; never request copied full text in support. |
| Error messages | Normalized status-level errors are shown for validation/API failure categories. | No, except external services may return failure categories after a user action. | No, except OpenAI may return failure categories after Generate. | Yes. | Not intended as persistent plugin data. | Low to medium if messages remain generic; high if raw bodies leak. | Required for usability and troubleshooting. | External error-path QA is not fully executed. | Keep normalized; run error-path QA; support status-level evidence only. | Accept with disclosure after QA. | Needs review / QA pending | Low to medium. | Must not display or record raw headers/bodies/credentials. |
| Support/debug screenshots | Future evidence may show admin screens or statuses. | No. | No. | Potentially, if user captures UI. | Not plugin storage, but docs/support may store evidence. | High if screenshots include identifiers, payloads, credentials, or generated text. | Useful for support if redacted. | Users may share sensitive data accidentally. | Require redaction; prefer status-level text; avoid network/body captures. | Redact / Hold until guidance finalized. | Needs implementation | Low for docs; medium if UI support text added later. | Must redact credentials, identifiers, payloads, generated text, cookies, sessions. |
| Stored plugin settings | Persistent settings include configuration categories and credential-bearing categories. | Used for GA4 request configuration. | Used for OpenAI key lookup; non-secret config may affect payload. | Yes, non-secret config and saved-state indicators. | Yes, persistent option storage. | High when including identifiers and credentials. | Required for configuration but not all fields are needed in payload. | Option dumps can expose sensitive values. | Avoid option dumps; support constant-based secrets; uninstall cleanup; disclosure. | Hold / Needs human decision for public release storage posture. | Needs decision | Medium to high if storage model changes. | Do not record option values; disclose storage risk. |
| Credential-bearing settings | Google Access Token and OpenAI API Key categories are saved in current MVP settings and not redisplayed. | Google credential category is sent to GA4 header during Fetch. | OpenAI credential category is sent to OpenAI header during Generate. | Saved status only; value not redisplayed. | Yes, persistent settings in current MVP. | Critical: credentials can grant external service access. | Required for current MVP API calls, but not payload content. | Public release strategy unresolved; database/backups/server/code access risk. | OAuth/token redesign; constant-based OpenAI key option; delete controls; uninstall cleanup; support prohibition. | Hold / Needs human decision; Google manual token not public-ready. | Hold | High if redesigned; low if docs-only acceptance, but acceptance risk is high. | Never copy credential values/fragments; disclose storage and non-redisplay behavior. |

## Candidate Release Posture Summary

| Category Group | Candidate Posture | Rationale | Human Decision Needed |
|---|---|---|---|
| Report period, comparison period, scope type | Accept with disclosure | These are necessary report conditions and relatively manageable with clear disclosure. | Confirm disclosure wording. |
| Aggregated summary metrics and comparison metrics | Accept with disclosure | These are core report inputs; removing them would make the report ineffective. | Confirm value redaction in support/docs. |
| Daily trend rows | Accept with row limits and disclosure | Useful for narrative; should remain bounded and not be copied into docs. | Confirm current row limits are acceptable. |
| Traffic channels | Accept with disclosure | Useful and less identifying than source-level values. | Confirm category-level disclosure. |
| Site host / hostname | Hold / likely Make configurable or Accept with disclosure | Useful for scoping, but site/client identifiability is high. | Decide whether host context may be included in OpenAI payload. |
| Normalized directory/page scope values | Hold / likely Make configurable or Minimize | Useful for focused reports, but path values may reveal sensitive content. | Decide inclusion and redaction posture. |
| Top page paths | Hold / likely Accept with row limits and disclosure or Make configurable | Very useful for analytics reporting, but high business sensitivity. | Decide keep/remove/configure/redact posture. |
| Traffic sources | Hold / likely Make configurable or Accept with disclosure/redaction | Useful but can reveal partners, campaigns, referrers, or dependencies. | Decide whether source values remain in OpenAI payload. |
| City / regional analytics dimensions | Hold / likely Minimize, Make configurable, or Remove from OpenAI payload | Useful in some reports but not always essential and can be sensitive. | Decide city-level vs broader-region posture. |
| AI Payload Preview rendered tables | Accept only after admin review, with disclosure/redaction | Review-before-send is central to transparency. | Confirm screenshot/support redaction guidance. |
| AI Payload Preview JSON body | Hold / likely Developer-debug only, collapsed warning, or redacted | Full JSON is valuable for development but risky for public multi-admin/support contexts. | Decide JSON visibility before release. |
| OpenAI prompt instructions and selected model name | Accept with disclosure | Required for OpenAI request construction and relatively low privacy risk. | Confirm final external-service wording. |
| Generated report body and admin textarea output | Accept with disclosure; continue non-persistence | Core output is useful; non-persistence is privacy-positive. | Confirm no save history for first public release. |
| Copy-to-clipboard behavior | Accept as-is with disclosure | User-controlled and adds no plugin persistence. | Confirm support guidance against pasting full output. |
| Error messages | Accept with disclosure after QA | Normalized messages are useful; raw response leakage must remain prohibited. | Complete external API error-path QA. |
| Support/debug screenshots | Redact / Hold until guidance finalized | Evidence can easily expose sensitive data. | Finalize support/debug redaction policy. |
| Stored plugin settings | Hold / Needs human decision | Persistent settings include identifiers and credential-bearing categories. | Decide public release storage and uninstall policies. |
| Credential-bearing settings | Hold / Needs human decision | Current Google manual token storage is developer-verification oriented; OpenAI key storage needs explicit acceptance or redesign. | Decide OAuth/token lifecycle, OpenAI key storage, and uninstall cleanup. |

## Payload Preview JSON Visibility Decision Options

| Option | Benefit | Risk | Usability Impact | Privacy Impact | Implementation Impact | Release-readiness Implication | Recommended Posture / Decision Status |
|---|---|---|---|---|---|---|---|
| Option A: Keep full JSON preview | Maximum transparency; useful for developer verification and debugging payload contracts. | High risk of copy/paste, screenshots, support tickets, and multi-admin exposure containing sensitive analytics categories. | Best for technical users; potentially confusing for normal users. | Highest exposure among options. | None if kept as-is. | Should not be accepted for public release without explicit human acceptance and strong support guidance. | Hold / Needs human decision. |
| Option B: Keep summary tables only and remove full JSON preview | Reduces copyable full-payload exposure while preserving human-readable review. | Less transparent for technical debugging and harder to verify exact payload shape. | Simpler for most users. | Strong privacy improvement. | Medium UI/PHP changes later. | Strong candidate if public release prioritizes minimization. | Candidate: Minimize before release. |
| Option C: Collapse JSON preview behind warning text | Preserves access while making sensitivity explicit. | Users can still expand and copy full payload. | Balanced; adds friction before exposure. | Moderate improvement, not full mitigation. | Low to medium UI change later. | May be acceptable with disclosure and support redaction if humans accept the residual risk. | Candidate: Accept with disclosure and warning / Needs decision. |
| Option D: Redact sensitive fields in JSON preview | Maintains structural transparency while reducing exposure of paths, host, sources, and city values. | Redacted preview may differ from the actual OpenAI payload and create confusion unless clearly labeled. | Useful but requires careful labels. | Strong improvement if sensitive fields are actually redacted. | Medium to high, depending on redaction layer and tests. | Good candidate if full JSON is still desired. | Candidate: Redact / Needs design. |
| Option E: Developer/debug mode only | Keeps debugging utility for controlled verification while hiding full JSON from normal public UI. | Requires a mode flag and clear support boundary; users may need support without JSON access. | Better default UX for public users; debug users retain access. | Strong improvement for normal public use. | Medium implementation later. | Strong candidate before public release if developer diagnostics are still needed. | Recommended candidate for next checkpoint. |

## Generated Report Body Handling Decision Options

| Option | Benefit | Risk | Implementation Impact | Privacy Impact | Support/debug Impact | Recommended Posture / Decision Status |
|---|---|---|---|---|---|---|
| Option A: Continue non-persistence as current MVP | Preserves current privacy-positive behavior and keeps storage scope small. | Generated text can still be exposed through screenshots, clipboard, or support requests. | None if preserved. | Best current baseline because the plugin does not persist report bodies. | Support must still forbid full generated report bodies. | Recommended baseline / Needs human confirmation. |
| Option B: Add optional save history | Improves user workflow and auditability for drafts. | Adds persistent sensitive business analysis to WordPress storage and increases cleanup/export/support obligations. | High: storage, UI, permissions, deletion, migration, and uninstall policy. | Worse unless carefully designed. | Support and privacy guidance become much more complex. | Not recommended for first public release without a dedicated design. |
| Option C: Add copy-only / no-save explicit policy | Clarifies current behavior and discourages assuming stored history exists. | Does not prevent users from copying sensitive text elsewhere. | Low if wording-only later. | Good, because it reinforces non-persistence. | Helps support avoid requesting full bodies. | Recommended wording candidate after human confirmation. |
| Option D: Add admin warning not to paste sensitive content into support | Directly addresses likely leakage path. | More admin copy; must avoid warning fatigue. | Low to medium depending on placement. | Improves user awareness. | Strong support benefit. | Recommended after support/debug policy is finalized. |

## Credential-bearing Settings Category

Credential-bearing settings are not an AI payload category, but they are
release-critical because the current MVP uses them to authorize external
service requests.

| Item | Current MVP Status | Release Concern | Candidate Posture | Required Human Decision |
|---|---|---|---|---|
| Google Access Token category | Manually entered for developer verification, stored in plugin settings, not redisplayed, and sent as an authorization credential category during GA4 Fetch. | Manual token entry, expiration, scope, reconnect, provider-side revocation, and persistent option storage are not public-release ready. | Hold; keep developer-verification only until OAuth/token lifecycle strategy is chosen. | Whether public release can proceed without full OAuth implementation. |
| OpenAI API Key category | Entered in Settings, stored in plugin settings, not redisplayed, and sent as an authorization credential category during OpenAI Generate. | Settings-based key storage exposes the key category to database/server/backup/option-reading code paths. | Hold until settings-based storage, constant-based configuration, both, or another model is accepted. | Whether public release can proceed with settings-based OpenAI API Key storage. |
| Saved credential non-redisplay behavior | Saved credential values are not refilled in the Settings form; saved status and delete/replace controls are shown. | Positive control that must be preserved. | Accept as preserved behavior. | Confirm it remains a release requirement. |
| `wp_options` storage risk | Current plugin settings option stores credential-bearing categories in the WordPress database. | Option dumps, backups, database access, compromised code, or server access can expose values. | Hold / Needs human decision. | Whether storage is redesigned, accepted with disclosure, or supplemented with safer configuration paths. |
| Constant-based configuration option | Not implemented in the current MVP; previously identified as a candidate for OpenAI key handling. | Reduces option-table exposure but adds configuration complexity. | Candidate for implementation planning. | Whether to support constants before public release. |
| Uninstall cleanup relationship | Credential-bearing settings cleanup policy is unresolved. | Credentials may remain after uninstall if cleanup is not implemented or documented. | Hold. | Whether uninstall should delete credential-bearing settings and related temporary data. |
| Support/debug redaction requirements | Internal docs prohibit sharing secrets, option dumps, full request/response bodies, payloads, and generated report bodies. | Public support guidance is not finalized. | Needs implementation before release readiness. | What exact release-facing support policy should say. |

Credential-bearing settings must not be copied into docs or support because
they can grant access to external services, can expose billing or account risk,
can persist in public issue trackers or shared tickets, and can remain visible
in backups or screenshots long after the original support request is closed.

## Recommended Decisions Requiring Human Approval

The following decisions should be made by humans before release readiness
continues:

- Whether hostname may be included in the AI payload.
- Whether normalized directory scope values may be included in the AI payload.
- Whether normalized page scope values may be included in the AI payload.
- Whether top page paths remain in the OpenAI payload.
- Whether traffic sources remain in the OpenAI payload.
- Whether city/regional dimensions remain in the OpenAI payload.
- Whether full AI Payload Preview JSON remains visible.
- Whether generated report body remains non-persistent for public release.
- Whether generated report save history is explicitly out of scope for the
  first public release.
- Whether public release can proceed with settings-based OpenAI API Key
  storage.
- Whether public release can proceed without full OAuth implementation.
- Whether manual Google Access Token entry remains developer-verification only.
- Whether constant-based OpenAI key configuration should be implemented before
  public release.
- Whether uninstall should delete credential-bearing settings.
- Whether support/debug screenshots are allowed only after strict redaction.
- Whether external services/privacy wording can be finalized only after the
  payload and credential decisions above are closed.

## Release Blockers / Follow-up Decisions

| Blocker / Decision Item | Status After Step 83 | Notes |
|---|---|---|
| OAuth / token lifecycle strategy unresolved | Hold | Manual Google Access Token entry remains developer-verification oriented. |
| OpenAI API Key storage strategy unresolved | Hold | Settings-based storage needs explicit acceptance or redesign; constant-based configuration remains a candidate. |
| AI payload category acceptance not final | Hold | This matrix is review material, not a final decision. |
| AI Payload Preview JSON visibility not final | Hold | Full JSON preview remains one of the clearest category-specific release decisions. |
| Generated report handling policy not final | Needs decision | Current non-persistence is a strong baseline but should be explicitly approved. |
| Support/debug redaction guidance not final | Needs implementation | Public support wording is still not release-facing policy. |
| Uninstall credential cleanup policy unresolved | Hold | Credential-bearing settings cleanup needs a release decision. |
| External services / privacy wording not release-finalized | Hold | Draft wording depends on payload and credential decisions. |
| Plugin Check / PHPCS refresh not executed | Needs review | Tooling refresh remains later release-readiness work. |
| Release package contents not reviewed | Needs review | Package contents and secret/data scan remain later work. |
| WordPress.org release remains Hold | Hold | Release readiness should not proceed until blockers are closed or explicitly deferred. |

## Recommended Next Step

Recommended next step:

```text
Step 84: AI Payload Preview JSON visibility decision checkpoint
```

Rationale:

- AI Payload Preview JSON visibility is a narrow, high-impact decision.
- It directly affects public multi-admin exposure, screenshot/support risk,
  disclosure wording, and potential UI changes.
- It can be decided before larger OAuth/OpenAI key storage implementation
  planning begins.

## Existing Docs Referenced

- `docs/maturation/step71-pre-release-risk-inventory-results.md`
- `docs/maturation/step72-credential-external-services-disclosure-review.md`
- `docs/maturation/step78-data-minimization-privacy-review.md`
- `docs/maturation/step79-wordpress-org-compliance-checklist.md`
- `docs/maturation/step80-wordpress-org-compliance-review-results.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`
- `docs/maturation/step82-external-services-privacy-disclosure-draft.md`

## Outcome

- Decision criteria: documented.
- Category decision matrix: documented at category level.
- Candidate release posture summary: documented.
- Payload Preview JSON visibility options: documented.
- Generated report body handling options: documented.
- Credential-bearing settings category: documented separately.
- Human approval decision list: documented.
- Release blockers and follow-up decisions: documented.
- Production code changed: no.
- `readme.txt` changed: no.
- Admin UI, JavaScript, and CSS changed: no.
- External API calls performed: no.
- Real credentials, identifiers, analytics values, page paths, traffic source
  values, city values, request bodies, payload bodies, raw responses, generated
  reports, nonces, cookies, and sessions recorded: no.
- WordPress.org release position: `Hold`.
