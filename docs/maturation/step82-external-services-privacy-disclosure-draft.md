# Step 82: External Services / Privacy Disclosure Draft

## Step Summary

This document records the Step 82 external services and privacy disclosure
draft for Analytics Report AI.

The purpose is to turn the previous release-risk, compliance, privacy, and
credential strategy findings into draft wording and decision tables that can be
reviewed before any public release work.

This is a docs-only step. It does not change production code, `readme.txt`,
admin UI copy, JavaScript, CSS, Settings save behavior, GA4 client behavior,
OpenAI client behavior, payload structure, credentials, distribution files, or
release metadata.

This is not release-final wording. All candidate wording blocks in this file
are marked as draft material and need human review before being copied into
release-facing documentation or UI.

WordPress.org release remains `Hold`.

## External Services Inventory

### Google Analytics Data API

| Item | Draft Inventory |
|---|---|
| Service purpose | Fetch selected GA4 report data from the administrator's configured GA4 property so the plugin can build a report preview and AI payload. |
| When data may be sent | Only when an authorized administrator starts the GA4 report fetch action from Report Builder. Merely viewing plugin admin screens should not send GA4 data requests. |
| Data categories that may be sent | Google authorization credential category in the request header, GA4 property identifier category, selected report period, comparison setting and comparison period when enabled, host filter when enabled, normalized directory/page path filter when selected, and requested GA4 metric/dimension presets. |
| Data categories that may be received | Aggregated metrics and dimensions such as summary metrics, daily trend rows, top page path categories, traffic channel/source categories, and city or regional analytics dimensions. |
| Data categories that should not be intentionally sent | OpenAI API Key, WordPress user passwords, WordPress cookies, WordPress nonces, browser session values, unrelated WordPress user profile data, raw plugin settings option dumps, and generated report bodies. |
| Credential / authorization handling status | The current MVP uses manual Google Access Token entry for developer verification. Saved credential values are not redisplayed in the Settings form, but the credential category is stored in the WordPress database as plugin settings. |
| Current MVP limitations | Manual access token entry is temporary. OAuth connection flow, token expiry handling, refresh/reconnect behavior, scope validation, provider-side revocation guidance, and release-grade support guidance are not implemented. |
| Unresolved release-readiness issues | OAuth/token lifecycle strategy, access-removal semantics, credential storage strategy, final disclosure wording, redaction policy, external API error-path evidence, and uninstall cleanup policy remain unresolved. |

### OpenAI API

| Item | Draft Inventory |
|---|---|
| Service purpose | Generate an editable Japanese report draft from the administrator-reviewed analytics summary payload. |
| When data may be sent | Only after GA4 data has been fetched, the AI Payload Preview exists, and an authorized administrator starts the AI report generation action. Viewing the preview alone should not call OpenAI. |
| Data categories that may be sent | OpenAI authorization credential category in the request header, selected model name, fixed report-generation instructions, and the reviewed structured AI payload containing selected analytics categories. |
| Data categories that may be received | Generated report draft text returned by OpenAI for display in the admin textarea, manual editing, and copying. |
| Data categories that should not be intentionally sent | Google Access Token, OpenAI API Key inside the payload body, WordPress user passwords, cookies, nonces, access tokens, raw credential values, unrelated WordPress user information, raw plugin settings option dumps, and full raw GA4 responses. |
| Credential / authorization handling status | The current MVP stores the OpenAI API Key in the WordPress database as plugin settings. Saved credential values are not redisplayed in the Settings form. |
| Current MVP limitations | Public release storage strategy for the OpenAI API Key is unresolved. Constant-based configuration, separated storage, encryption, external secret storage, and final support guidance are not implemented in the MVP. |
| Unresolved release-readiness issues | OpenAI API Key storage strategy, AI payload category acceptance, AI Payload Preview JSON visibility, final privacy wording, support/debug redaction guidance, generated report evidence rules, and uninstall cleanup policy remain unresolved. |

## Google Analytics Data API Disclosure Draft

Status: `Draft / Needs review`

Candidate readme or admin help wording:

```text
Analytics Report AI can connect to the Google Analytics Data API to fetch
selected GA4 report data from the GA4 property configured by the site
administrator. The request is started only when an authorized administrator
uses the GA4 fetch action in Report Builder.

The request may include the configured GA4 property identifier category,
Google authorization credential category in the request header, selected
report period, comparison period when enabled, host filtering when enabled,
normalized directory or page scope filters when selected, and the report
metrics and dimensions needed for the selected report preview.

The fetched analytics data may include aggregated metrics and dimensions such
as summary metrics, daily trends, top page path categories, traffic channel or
source categories, and city or regional analytics dimensions. Review your GA4
configuration and report scope before fetching data.

The current MVP uses manually entered Google Access Tokens for developer
verification. This token-entry model is temporary. A public release needs a
final strategy for OAuth connection, token expiry handling, refresh or
reconnect behavior, scope validation, and revoke or disconnect guidance.

This plugin is not endorsed by Google. Google Analytics and Google APIs are
services provided by Google and are subject to Google's terms and privacy
policies.
```

Disclosure notes:

- Do not claim Google endorsement, certification, or partnership.
- Do not include real GA4 property identifiers, hostnames, page paths, traffic
  sources, city names, metric values, access tokens, request bodies, or raw
  responses in release-readiness docs.
- Keep release status on `Hold` until the Google authorization strategy is
  finalized.

## OpenAI API Disclosure Draft

Status: `Draft / Needs review`

Candidate readme or admin help wording:

```text
Analytics Report AI can send a structured analytics summary payload to the
OpenAI API to generate an editable Japanese report draft. The OpenAI request is
started only when an authorized administrator reviews the AI Payload Preview
and uses the AI report generation action.

The payload may include selected, aggregated GA4 report categories such as the
report period, comparison period, scope type, normalized scope value, summary
metrics, comparison data, daily trend values, top page path categories, traffic
channel categories, traffic source categories, and city or regional analytics
dimensions.

The plugin does not need to send WordPress user passwords, WordPress cookies,
WordPress nonces, Google Access Tokens, OpenAI API Keys, Authorization header
values, or raw credential values in the AI payload body. The generated report
draft returned by OpenAI is displayed for review, editing, and copying.

In the current MVP, the generated report body is not persisted by the plugin
as plugin data. The current OpenAI API Key storage strategy is not final for
public release and must be decided before WordPress.org release readiness.
```

Disclosure notes:

- The current MVP sends the OpenAI API Key as an authorization credential
  category in the request header, but release-readiness documentation must not
  record the value or any credential fragment.
- Generated report text can contain business-sensitive interpretation and
  should not be copied into support tickets or release-readiness docs.
- Keep release status on `Hold` until OpenAI key storage, payload categories,
  and privacy wording are finalized.

## AI Payload Category Acceptance Draft

| Category | Current MVP Behavior | Privacy Sensitivity | Disclosure Need | Release-readiness Decision Status |
|---|---|---|---|---|
| Site host / hostname | May be stored in Settings and included as site context or GA4 filtering context when configured. | High, because it can identify a site, client, environment, or property. | Disclose that host context may be used for GA4 filtering and may appear in the reviewed AI payload, without recording real hostnames. | Needs decision |
| Report period and comparison period | Included in GA4 request construction and AI payload context. | Medium, because dates can reveal campaign timing, reporting cadence, or incident windows. | Disclose selected periods and comparison periods as sent categories. | Needs review |
| Scope type and normalized scope value | Used to constrain directory/page reports and included in payload context. | High when scope values reveal sensitive content areas, campaign paths, products, or internal naming. | Disclose normalized scope values and decide whether public release needs additional redaction or minimization. | Needs decision |
| Aggregated summary metrics | Included in payload as primary report metrics. | Medium to high, because totals and rates can reveal business performance. | Disclose aggregate analytics metrics as OpenAI payload categories. | Needs review |
| Daily trend values | Included in payload for trend narrative. | Medium to high, because daily changes can reveal campaigns, publishing schedules, incidents, or operational timing. | Disclose trend values and keep row-limit/minimization decisions explicit. | Needs review |
| Top page paths | Included in payload preview and OpenAI payload after GA4 Fetch and Generate flow. | High, because paths can reveal editorial plans, product pages, private-looking URLs, campaigns, or customer context. | Disclose page path category exposure clearly; release should decide keep/remove/limit/redact posture. | Needs decision |
| Traffic channels | Included in payload as acquisition category data. | Medium, because channel mix can reveal marketing strategy. | Disclose traffic channel categories. | Needs review |
| Traffic sources | Included in payload as acquisition source category data. | High, because sources can reveal partners, campaigns, referral relationships, or business dependencies. | Disclose traffic source category exposure clearly; define support redaction rules. | Needs decision |
| City / regional analytics dimensions | Included in current regional trend payload category when available. | High when city-level patterns can identify local demand, operations, or customer concentration. | Disclose regional/city dimension category and decide whether city-level rows remain acceptable for public release. | Needs decision |
| Generated report body | Returned from OpenAI and displayed in an editable textarea; not persisted as plugin data in the reviewed MVP flow. | High, because generated analysis can summarize sensitive performance or business implications. | Disclose non-storage behavior and support guidance against sharing full generated text. | Needs review |
| AI Payload Preview JSON visibility | Optional admin preview exposes the structured payload before OpenAI generation. | High, because it can expose multiple sensitive analytics categories in one copyable view. | Disclose preview behavior and decide whether JSON visibility is acceptable for public multi-admin release. | Needs decision |

## Candidate Wording Blocks

### Candidate readme external services wording

Status: `Draft / Needs review`

```text
Analytics Report AI uses external services only when an authorized
administrator starts a report action. Opening the plugin screens does not, by
itself, send data to Google Analytics Data API or OpenAI API.

When Fetch GA4 Data is used, the plugin sends the selected report conditions
and required GA4 request categories to Google Analytics Data API. Google may
return aggregated analytics metrics and dimensions used to build the Payload
Preview.

When Generate AI Report is used, the plugin sends the reviewed structured
analytics payload to OpenAI API to generate an editable report draft. The
payload may include aggregated metrics, trend data, path categories, traffic
categories, source categories, regional dimensions, and report conditions.

Administrators should review the Payload Preview before generating a report
and should avoid sharing credentials, identifiers, payload bodies, raw
responses, or generated reports in public support channels.
```

### Candidate admin help wording

Status: `Draft / Needs review`

```text
Report Builder uses a staged workflow. Fetch GA4 Data sends the selected
conditions to Google Analytics Data API and creates a Payload Preview. Viewing
the preview does not send data to OpenAI. Generate AI Report sends the reviewed
payload to OpenAI API and returns an editable draft.

Review the payload categories before generation. Do not share credentials,
authorization headers, property identifiers, hostnames, full payload JSON, raw
API responses, or generated report text in screenshots or support requests.
```

### Candidate privacy notice wording

Status: `Draft / Needs review`

```text
This plugin processes analytics report data selected by an administrator.
Depending on the configured report, processed categories may include report
periods, comparison periods, hostname context, normalized directory or page
scope, aggregated metrics, trend rows, top page path categories, traffic
channel categories, traffic source categories, and regional or city analytics
dimensions.

The plugin stores configuration settings in WordPress. The current MVP stores
Google and OpenAI credential categories as plugin settings and does not
redisplay saved credential values in the admin form. This storage strategy is
not finalized for public release.

The generated report draft is displayed for administrator review and editing.
In the reviewed MVP flow, the generated report body is not persisted by the
plugin as plugin data.
```

### Candidate support/debug redaction guidance

Status: `Draft / Needs review`

```text
Before sharing support or debug information, remove credentials, access
tokens, API keys, Authorization headers, credential fragments, GA4 property
identifiers, hostnames, page paths, traffic source values, city names,
analytics values, request bodies, payload JSON, raw external API responses,
generated report bodies, nonces, cookies, and session values.

Use status-level descriptions instead of raw data. For example, say that GA4
returned an authorization error or that payload validation failed, but do not
paste headers, bodies, option values, full payloads, or generated report text.
```

## Redaction and Non-recording Rules

The following rules apply to release-readiness docs, support drafts, QA
evidence, screenshots, console notes, network notes, and future public-support
guidance:

- Real credentials must never be logged or documented.
- API keys, access tokens, and Authorization headers must not be recorded.
- Credential fragments, prefixes, suffixes, `sk-` values, and JWT fragments
  must not be recorded.
- `wp_options` credential values and plugin settings option values must not be
  displayed or recorded.
- Real GA4 Property ID values must not be recorded.
- Real hostname/domain values must not be recorded.
- Analytics values, page paths, traffic sources, and city names must not be
  recorded in release-readiness docs.
- Full request bodies, AI payload JSON, OpenAI request bodies, raw external API
  responses, and generated report bodies must not be copied into docs.
- Nonce, cookie, and session values must not be recorded.
- Screenshot evidence must be cropped or redacted so that it does not show
  credentials, identifiers, payload bodies, generated reports, cookies,
  sessions, or personal/business-sensitive analytics data.
- Network evidence, if ever needed, should be limited to safe status-level
  categories and must not include headers, bodies, cookies, sessions, or full
  URLs containing sensitive paths or query values.

## Release Blockers / Follow-up Decisions

| Blocker / Decision | Current Status | Notes |
|---|---|---|
| OAuth / token lifecycle strategy | Unresolved | Manual Google Access Token entry remains developer-verification oriented. OAuth flow, expiry tracking, refresh/reconnect, scope validation, and revoke/disconnect behavior need a public-release decision. |
| OpenAI API Key storage strategy | Unresolved | Settings-based storage, constant-based configuration, separated storage, external-only configuration, or another model needs explicit acceptance. |
| External services disclosure wording | Not release-finalized | This Step 82 file provides draft blocks only. |
| Privacy / data handling wording | Not release-finalized | Needs review after payload category and credential decisions. |
| AI payload category acceptance | Unresolved | Top page paths, traffic sources, city/regional dimensions, host context, and scope values need explicit release decisions. |
| AI Payload Preview JSON visibility | Unresolved | Public multi-admin and screenshot/support exposure risk remains. |
| Support/debug redaction guidance | Not finalized | Candidate guidance exists in this file but is not release-facing policy. |
| Uninstall credential cleanup policy | Unresolved | Need a decision on whether and how credential-bearing settings are removed on uninstall. |
| WordPress.org release | Hold | Release readiness should not proceed until these blockers are resolved or explicitly deferred by human decision. |

## Recommended Next Step

Recommended next step:

```text
Step 83: AI payload category acceptance decision matrix
```

That step should decide, category by category, whether each current payload and
admin-display category is accepted for public release, minimized further,
redacted, made configurable, or removed before release readiness resumes.

## Existing Docs Referenced

- `docs/maturation/step71-pre-release-risk-inventory-results.md`
- `docs/maturation/step72-credential-external-services-disclosure-review.md`
- `docs/maturation/step78-data-minimization-privacy-review.md`
- `docs/maturation/step79-wordpress-org-compliance-checklist.md`
- `docs/maturation/step80-wordpress-org-compliance-review-results.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`

## Outcome

- External services inventory: drafted at category level.
- Google Analytics Data API disclosure draft: added.
- OpenAI API disclosure draft: added.
- AI payload category acceptance draft: added.
- Candidate readme/admin/privacy/support wording blocks: added and marked
  `Draft / Needs review`.
- Redaction and non-recording rules: consolidated.
- Release blockers and follow-up decisions: recorded.
- Production code changed: no.
- `readme.txt` changed: no.
- Admin UI, JavaScript, and CSS changed: no.
- External API calls performed: no.
- Real credentials, identifiers, analytics values, request bodies, payloads,
  raw responses, generated reports, nonces, cookies, and sessions recorded: no.
- WordPress.org release position: `Hold`.
