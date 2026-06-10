# Step 78: Data Minimization / Privacy Review

## Purpose

This document records the Step 78 data minimization and privacy review for
Analytics Report AI.

The purpose is to inventory the data categories handled by the current MVP,
review whether the data sent to external services is limited to the report
workflow, identify remaining privacy and disclosure decisions, and keep the
WordPress.org release position explicit.

This is a docs-only review. It does not change implementation, execute external
actions, inspect stored secrets, or make a release-readiness decision.

WordPress.org release remains `Hold`.

## Review Method

This review used category-level, read-only inspection of repository documents
and source files.

Reviewed areas:

- `readme.txt` external services, credential storage, and payload review
  disclosure.
- Step 71 pre-release risk inventory results.
- Step 72 credential / external services disclosure review.
- Step 74 admin security review results.
- Step 77 external API error-path QA checklist.
- Report Builder action boundaries and admin display behavior at source level.
- GA4-to-AI payload formatter structure at source level.
- AI payload validation and transient helper behavior at source level.
- OpenAI request construction boundary at source level.

Safety boundaries:

- Production PHP, JavaScript, CSS, and assets were not changed.
- `readme.txt` was not changed.
- Settings save logic was not changed.
- GA4 client logic was not changed.
- OpenAI client logic was not changed.
- Admin UI behavior was not changed.
- GA4 Fetch was not executed.
- OpenAI Generate was not executed.
- Google OAuth was not started.
- External API calls were not performed.
- Real credentials were not inspected, entered, saved, displayed, or recorded.
- Credential-bearing option values were not inspected.
- Real GA4 Property ID values were not inspected or recorded.
- Real hostnames/domains, page paths, traffic sources, city names, or analytics
  metric values were not inspected or recorded.
- Request bodies, AI payload bodies, raw external responses, generated report
  bodies, nonce values, browser cookies, and session values were not recorded.

## Data Flow Inventory

| Flow | Data Categories | Stored by Plugin | Sent to External Service | Displayed in Admin UI | Privacy / Minimization Concern | Status |
|---|---|---|---|---|---|---|
| Settings configuration | GA4 property identifier category, hostname filter setting, hostname category, Google credential saved state, OpenAI credential saved state. | Yes. Persistent plugin settings include credential-bearing fields and report configuration. | No external request is made by merely viewing or saving the Settings screen. | Yes. Configuration values and credential saved-state indicators are shown. Credential values are not redisplayed. | Persistent credential-bearing settings and site identifiers remain public-release concerns. | Hold |
| Report Builder conditions | Date range, comparison mode, comparison date range, scope, normalized path condition. | Not as a separate persistent record. Included in the temporary AI payload after a successful GA4 Fetch. | Sent to Google Analytics Data API when GA4 Fetch is clicked; selected categories are included in the OpenAI payload after Generate is clicked. | Yes. Validated conditions are displayed after action handling. | Conditions may reveal site analysis intent, campaign timing, or sensitive content paths. | Needs review |
| GA4 Fetch request | GA4 property identifier category, Google authorization credential in request header, date ranges, hostname filter when enabled, path filter when scope requires it, requested metrics and dimensions. | Raw GA4 request body is not persisted by the plugin in the reviewed source path. | Yes. Sent to Google Analytics Data API when the administrator clicks Fetch GA4 Data. | Not as a raw request body. User-facing copy describes the action and categories. | External sharing with Google must remain explicit; headers and request bodies must never be recorded in support or QA evidence. | Needs review |
| GA4 response handling | Aggregate summary metrics, daily trend rows, top pages, traffic channels, traffic sources, and regional trend rows. | Selected data is formatted into a temporary AI payload transient after validation. | Not sent to OpenAI until the administrator separately clicks Generate AI Report. | Yes. Preview tables and JSON preview can display selected payload content. | Analytics data may reveal business performance, content strategy, traffic sources, and location-level patterns. | Needs review |
| AI payload formatting | Plugin metadata, payload version, language, report type, site context, report conditions, summary metrics, comparison deltas, trend rows, page rows, traffic rows, and regional rows. | Yes. Temporarily stored in a user-scoped WordPress transient. | Sent to OpenAI API only when Generate AI Report is clicked. | Yes. Payload Preview displays the content to review before sending. | Payload is selected and row-limited, but still contains sensitive analytics categories. | Needs decision |
| AI Payload Preview | Human-reviewable payload tables and optional payload JSON preview. | Same temporary payload transient already created after GA4 Fetch. | No external request is made by viewing the preview alone. | Yes. This is intentionally visible to administrators before OpenAI generation. | Full preview improves transparency but can expose sensitive analytics in screenshots, support notes, and shared admin sessions. | Needs decision |
| AI payload transient | Current user's formatted AI payload. | Yes. Temporary user-scoped transient with automatic expiration. | Only used for OpenAI Generate after validation. | Yes, through Payload Preview and generated-report state. | Multi-admin assumptions, stale payload behavior, and support/debug redaction rules need release-level confirmation. | Needs review |
| OpenAI request construction | OpenAI authorization credential in request header, selected model, fixed system instructions, and user input containing the reviewed payload. | OpenAI request body is not persisted by the plugin in the reviewed source path. | Yes. Sent to OpenAI API when Generate AI Report is clicked. | Not as a raw request body. Payload content is visible separately in Payload Preview. | Sending analytics categories to OpenAI needs release-grade disclosure and minimization decisions. | Needs review |
| OpenAI response handling | Generated Japanese report draft. | The generated report body is displayed in the admin textarea and is not persisted as plugin data by the reviewed MVP flow. | Received from OpenAI API. | Yes. Displayed in an editable textarea. | Generated business analysis may be sensitive; support evidence should not include full generated text. | Known pass / needs disclosure review |
| Copy action | Generated textarea content selected by the administrator for copying. | No plugin persistence is introduced by the copy action. | No external service request is made by the plugin copy action. | Yes. Copy status feedback is displayed. | Clipboard handling is user-controlled; docs and support guidance should avoid requesting copied report bodies. | Known pass / needs disclosure review |
| Cleanup and uninstall position | Persistent settings, credentials, host configuration, and temporary transients. | Current persistence behavior exists, but release cleanup policy remains a decision area. | Not applicable by itself. | Not directly, except saved-state/configuration display. | Public release should decide uninstall cleanup and support-safe remediation steps. | Hold |

## AI Payload Minimization Review

| Payload Category | Purpose | Minimization Concern | Release Disclosure Needed | Status |
|---|---|---|---|---|
| Plugin metadata, payload version, language, and report type | Let validation and prompt generation understand the payload contract. | Low privacy concern, but still included in the OpenAI input. | Mention that the request includes fixed report-generation context. | Known pass |
| Site context | Tell the report whether a hostname filter applies. | Hostname/domain context can identify the site or property being analyzed. | Disclose that hostname context may be included in the reviewed payload sent to OpenAI. | Needs review |
| Report conditions | Explain selected period, comparison, scope, and path context. | Date ranges, scope, and path conditions may reveal business priorities or sensitive content areas. | Disclose that report conditions may be sent to GA4 and selected conditions may be sent to OpenAI. | Needs review |
| Summary metrics | Provide the core report basis. | Traffic and engagement totals can reveal business performance. | Disclose that aggregate analytics metrics may be sent to OpenAI after review. | Needs review |
| Comparison metrics, differences, and change rates | Allow the generated report to explain period-over-period changes. | Changes can reveal performance shifts, incidents, campaign effects, or business-sensitive trends. | Disclose comparison information and calculated differences as OpenAI payload categories. | Needs review |
| Daily trend rows | Support trend narrative over the selected period. | Daily patterns may reveal campaign timing, publishing cadence, or operational events. | Disclose that trend data may be sent in the reviewed payload. | Needs review |
| Top pages | Explain content-level performance. | Page paths can reveal editorial strategy, private-looking URLs, products, campaigns, or internal naming conventions. | Disclose page path exposure clearly and decide whether current row limits are sufficient. | Needs decision |
| Traffic channels | Explain acquisition mix. | Channel-level data can reveal marketing performance and dependency on acquisition sources. | Disclose traffic channel categories in the OpenAI payload. | Needs review |
| Traffic sources | Explain source-level acquisition patterns. | Source values may reveal partner, campaign, referrer, or business relationship information. | Disclose traffic source exposure clearly and decide support redaction rules. | Needs decision |
| Regional trends | Explain city-level patterns where available. | City-level analytics can be more sensitive than broad regional aggregates. | Decide whether city-level rows remain acceptable for release and disclose if retained. | Needs decision |
| Fixed prompt instructions | Constrain the generated report to the reviewed payload and cautious wording. | Lower privacy risk than analytics data, but still sent to OpenAI. | Disclose that fixed instructions are sent for report generation. | Known pass / needs disclosure review |

## External Service Data Sharing Review

| Service | Trigger | Data Sent | Data Received | Stored by Plugin | User Awareness / Disclosure Need | Status |
|---|---|---|---|---|---|---|
| Google Analytics Data API | Administrator clicks Fetch GA4 Data in Report Builder. | GA4 property identifier category, Google authorization credential in the request header, selected date range, comparison settings, hostname filter when enabled, path/scope filters when selected, requested metrics, and requested dimensions. | Selected aggregate analytics data such as summary metrics, daily trend, top pages, traffic channels, traffic sources, and regional trends. | Selected returned data is formatted into the temporary AI payload transient. Credential-bearing settings are stored persistently. | Must clearly explain provider, trigger, purpose, data categories sent and received, credential use, and provider terms/privacy links. | Needs review |
| OpenAI API | Administrator reviews Payload Preview and clicks Generate AI Report. | OpenAI authorization credential in the request header, selected model, fixed instructions, and the reviewed AI payload containing selected analytics categories. | Generated Japanese report draft. | Generated report body is displayed in the admin textarea and is not persisted as plugin data by the reviewed MVP flow. OpenAI API Key is stored in settings. | Must clearly explain provider, trigger, payload categories, generated content handling, user review responsibility, cost/quota implications, and provider terms/privacy links. | Needs review |
| Admin screen load | Administrator opens plugin admin screens. | None expected from the plugin merely by opening the screens. | None expected from external GA4/OpenAI services. | No external-service data is created merely by screen load. | Disclosure should continue to state that viewing screens does not by itself send data to external services. | Known pass / needs browser evidence |
| Settings save | Administrator saves plugin settings. | No GA4/OpenAI external request is expected from saving settings alone. | None expected from external GA4/OpenAI services. | Persistent settings are updated, including credential-bearing values when supplied. | Settings copy should continue to explain storage risk and credential non-redisplay. | Known pass / needs release review |
| Copy generated report | Administrator clicks copy for textarea content. | No external service request is made by the plugin copy action. | None. | No plugin persistence is introduced by copy. | Support guidance should avoid asking users to paste full generated report bodies unless explicitly redacted and necessary. | Known pass / needs disclosure review |

## Storage / Persistence Review

| Storage Area | Data Categories | Persistence | Access Scope | Privacy Concern | Required Decision Before Release Readiness | Status |
|---|---|---|---|---|---|---|
| Plugin settings option | GA4 property identifier category, hostname filter settings, Google credential field, OpenAI credential field, and related saved-state data. | Persistent WordPress option. | Administrators through UI for non-secret configuration; database administrators, backups, server administrators, and code able to read options may access stored values. | Credential-bearing option storage is the highest-impact public release concern. | Decide whether current settings storage is acceptable, blocked, or redesigned before public release. | Hold |
| Google Access Token field | Google authorization credential category. | Persistent inside plugin settings in the MVP. | Not redisplayed in the UI, but accessible to database/server/backup/code paths with option access. | Manual token entry and persistent token storage are developer-verification oriented. | Decide OAuth, refresh/expiry tracking, scope validation, revocation, reconnect, and token storage strategy. | Hold |
| OpenAI API Key field | OpenAI authorization credential category. | Persistent inside plugin settings in the MVP. | Not redisplayed in the UI, but accessible to database/server/backup/code paths with option access. | Public release needs a clear key storage and support policy. | Decide whether to keep settings storage, add constant-based configuration, separate storage, or redesign before release. | Needs decision |
| GA4 Property ID setting | GA4 property identifier category. | Persistent inside plugin settings. | Displayed to administrators and available to option readers. | Property identifiers can be sensitive in screenshots, logs, support notes, and docs. | Decide redaction rules and release disclosure wording for property identifiers. | Needs review |
| Hostname filter setting | Host/domain category and host filter enabled state. | Persistent inside plugin settings. | Displayed to administrators and available to option readers. | Hostnames/domains can identify customers, sites, environments, or private projects. | Decide screenshot/support redaction guidance and OpenAI payload disclosure wording. | Needs review |
| AI payload transient | Selected analytics categories and report conditions. | Temporary user-scoped transient with automatic expiration. | Current admin user flow; stored in WordPress transient storage. | Temporary storage is useful for review but contains sensitive analytics categories. | Confirm expiration, user-scope assumptions, stale-state handling, and support/debug rules before release readiness. | Needs review |
| AI Payload Preview display | Tables and optional JSON view of the reviewed payload. | Display-only for the current admin page, backed by the temporary payload transient. | Administrators who can access the Report Builder page. | Preview transparency can conflict with screenshot/support safety if full payloads are shared. | Decide if JSON preview should remain as-is for public release and document redaction rules. | Needs decision |
| Generated report textarea | OpenAI-generated draft text. | Not persisted as plugin data in the reviewed MVP flow. | Displayed to the administrator after generation. | Generated text may contain business-sensitive interpretation of analytics data. | Confirm non-storage disclosure and support rule against recording full generated bodies. | Known pass / needs disclosure review |
| Browser clipboard | Text copied by the administrator. | Controlled by browser/OS clipboard, not plugin storage. | User environment. | Copied report text can be shared outside WordPress by user action. | Document user responsibility and support redaction expectations if needed. | Low / needs disclosure review |
| Uninstall cleanup | Settings and transient data categories. | Release cleanup policy not finalized in this review. | WordPress uninstall behavior and database state. | Credential-bearing options should have an explicit release cleanup position. | Decide uninstall cleanup for credentials/settings/transients before release readiness. | Hold |

## Admin Display / Evidence Review

Admin display areas reviewed at category level:

- Settings screen displays GA4 Property ID and hostname configuration values.
- Settings screen displays saved-state information for Google Access Token and
  OpenAI API Key without redisplaying the credential values.
- Report Builder displays configuration status and report-condition controls.
- Validated report conditions can display date, comparison, scope, and path
  categories after action handling.
- AI Payload Preview displays selected analytics categories in preview tables.
- AI Payload Preview includes a JSON preview of the reviewed payload.
- Generated report text is displayed in an editable textarea after OpenAI
  Generate succeeds.
- Copy status text is displayed without adding plugin persistence.

Evidence rules for future QA, support, and release review:

- Do not record credential values, credential fragments, token prefixes,
  token suffixes, API key prefixes, API key suffixes, Authorization headers, or
  option dumps.
- Do not record real GA4 Property ID values.
- Do not record real hostnames/domains, page paths, traffic source values, city
  values, or analytics metric values unless a later human-approved review uses
  redacted/synthetic examples.
- Do not record request bodies, AI payload bodies, OpenAI request bodies, raw
  external responses, generated report bodies, nonce values, browser cookies,
  or session values.
- Screenshot evidence should be cropped or redacted to avoid credentials,
  identifiers, payloads, raw responses, generated text, and personal or
  business-sensitive analytics details.
- Console and Network evidence, if needed later, should be status-level only
  and should avoid headers, bodies, cookies, sessions, and full URLs containing
  sensitive query/path information.

## Privacy / Disclosure Gap Analysis

Already covered or substantially covered:

- `readme.txt` identifies Google Analytics Data API and OpenAI API as external
  services.
- `readme.txt` states that viewing plugin screens does not by itself send data
  to the external services.
- `readme.txt` describes the administrator action that triggers GA4 Fetch.
- `readme.txt` describes the administrator action that triggers OpenAI report
  generation.
- `readme.txt` lists major GA4 request categories and response categories.
- `readme.txt` lists major OpenAI request categories and AI payload categories.
- Existing admin copy explains the staged flow: GA4 Fetch, Payload Preview,
  then Generate AI Report.
- Existing admin copy explains that credentials are not included in the AI
  payload by design.
- Existing admin copy warns that page paths and traffic sources can be
  sensitive business analytics information.
- Settings copy explains MVP credential storage and non-redisplay behavior.

Remaining release gaps:

- Final privacy wording has not been reviewed against WordPress.org
  expectations.
- Current credential storage and manual Google Access Token entry remain
  unresolved for public release.
- OAuth/token lifecycle requirements are not implemented or release-decided.
- AI payload category minimization has not been approved for public release.
- City-level regional data needs an explicit keep/remove/disclose decision.
- Top pages and traffic sources need a clear release decision because they can
  reveal sensitive business context.
- AI Payload Preview JSON visibility needs a release decision for public
  multi-admin use.
- Support/debug redaction policy is not yet consolidated into release-facing
  guidance.
- Uninstall cleanup for credential-bearing settings and related data needs a
  release decision.
- External API error-path QA remains prepared but not executed.
- Browser/admin evidence policies need to remain strict for all future QA and
  support documentation.

## Risk Classification

High:

- Public release strategy for Google Access Token storage is unresolved.
- Manual Google Access Token entry remains developer-verification oriented.
- OAuth connection, refresh/expiry tracking, scope validation, revocation, and
  reconnect behavior are not implemented.
- Public release strategy for OpenAI API Key storage is unresolved.
- AI payload categories sent to OpenAI have not received a final
  data-minimization decision.
- AI Payload Preview and payload JSON visibility can expose sensitive analytics
  categories in multi-admin, screenshot, and support contexts.
- Release-grade privacy and external-services disclosure are not finalized.
- Support/debug redaction policy is not consolidated.
- Uninstall cleanup for credential-bearing data is not release-decided.

Medium:

- Top pages, traffic sources, and city-level regional rows may reveal business
  or operational patterns and need explicit release wording.
- GA4 property identifiers and hostnames/domains need consistent redaction
  guidance.
- Transient payload user-scoping and stale-state behavior need broader
  multi-admin validation before release readiness.
- External API error-path QA remains pending.
- Generated report text non-storage behavior is favorable, but disclosure and
  support guidance still need release review.

Known pass:

- Saved Google Access Token and OpenAI API Key are not redisplayed in the
  Settings form.
- Credential status is shown at status level rather than value level.
- Opening plugin admin screens does not, by design, trigger GA4 or OpenAI
  external requests.
- The admin workflow separates GA4 Fetch from OpenAI Generate.
- OpenAI Generate is staged behind AI Payload Preview.
- AI payload validation runs before transient storage and before OpenAI
  generation.
- The generated report body is not persisted as plugin data in the reviewed MVP
  flow.
- Current public-facing readme copy already names the external service
  providers, action triggers, endpoint categories, and provider policy links.

## Recommended Decisions Before Release Readiness

- Decide whether WordPress.org release is blocked until Google OAuth replaces
  manual Google Access Token entry.
- Decide Google token lifecycle requirements: refresh, expiry, scope
  validation, revoke, reconnect, and access-removal behavior.
- Decide whether the current credential-bearing settings option remains
  acceptable for any public release.
- Decide whether OpenAI API Key storage should support constant-based
  configuration, separated storage, encryption, or another redesign before
  public release.
- Decide whether hostname context should be included in the AI payload by
  default.
- Decide whether top pages should remain in the OpenAI payload, and whether
  row limits or redaction should change.
- Decide whether traffic source values should remain in the OpenAI payload, and
  whether disclosure or redaction should be stronger.
- Decide whether city-level regional rows should remain in the OpenAI payload.
- Decide whether the full payload JSON preview should remain visible in a
  public multi-admin release.
- Decide final release-facing privacy and external-services wording for
  `readme.txt`, admin copy, and support documentation.
- Decide support/debug evidence rules for credentials, identifiers, payloads,
  request bodies, raw responses, and generated report bodies.
- Decide uninstall cleanup for credential-bearing settings and temporary
  payload data.
- Decide whether external API error-path QA must pass before entering release
  readiness.

## Recommended Next Steps

Do not proceed directly to WordPress.org release readiness.

Recommended next sequence:

1. Step 79: WordPress.org compliance checklist focused on external services,
   privacy disclosure, readme requirements, assets, licensing, uninstall
   behavior, and no-hidden-remote-call expectations.
2. Step 80: Credential and OAuth strategy decision checkpoint.
3. Step 81: Release-facing external services and privacy disclosure draft.
4. Step 82: External API error-path QA execution plan with redacted
   status-level evidence only.
5. Step 83: WordPress.org readiness checkpoint after the release-blocking
   decisions above are closed or explicitly deferred.

The most useful immediate next step is the WordPress.org compliance checklist,
because it can translate the privacy and external-services findings into the
specific release-review criteria without changing runtime behavior.

## Release Position

```text
WordPress.org release: Hold
Reason: Data minimization and privacy review identified remaining release decisions around AI payload categories, AI Payload Preview exposure, credential storage, Google OAuth/token lifecycle, external services disclosure, support/debug redaction, uninstall cleanup, and WordPress.org compliance.
```

## Outcome

- Data flow inventory: documented at category level.
- AI payload minimization review: documented at category level.
- External service data sharing review: documented at category level.
- Storage and persistence review: documented at category level.
- Admin display and evidence rules: documented.
- Privacy and disclosure gaps: documented.
- Risk classification: documented.
- Release position: `Hold`.
- Production code changes: none.
- `readme.txt` changes: none.
- GA4 Fetch: not executed.
- OpenAI Generate: not executed.
- Google OAuth: not started.
- External API communication: not performed.
- Credentials, API keys, access tokens, Authorization headers, option values,
  real GA4 Property ID values, real hostnames/domains, analytics values,
  request bodies, AI payload bodies, raw responses, generated report bodies,
  nonce values, cookies, and session values were not recorded.
