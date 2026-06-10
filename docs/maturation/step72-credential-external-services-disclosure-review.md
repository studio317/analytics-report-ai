# Step 72: Credential / External Services Disclosure Review

## Purpose

This document records the Step 72 credential and external services disclosure
review for Analytics Report AI.

The purpose is to organize the remaining release-risk questions around
credential handling, external services disclosure, privacy wording, and
WordPress.org readiness.

This is a docs-only review. It is not a release-readiness decision.

WordPress.org release remains `Hold`.

## Review Method

This review was performed by reading existing repository documentation and
source text at a status and behavior level.

Reviewed areas:

- Existing maturation docs from the pre-release risk inventory.
- `readme.txt` external services and credential disclosure sections.
- Settings screen disclosure text.
- Report Builder disclosure text and staged action flow.
- Credential field behavior at source level.
- GA4 and OpenAI action boundaries at source level.

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
- Real GA4 Property ID, hostname/domain, request bodies, AI payload bodies,
  raw external responses, and generated report bodies were not recorded.

## Credential Handling Inventory

| Item | Current MVP Handling | Public Release Concern | Disclosure / Decision Needed | Status |
|---|---|---|---|---|
| GA4 Property ID | Saved in plugin settings and used to identify the GA4 property for Google Analytics Data API requests. | A property identifier may be sensitive in support, screenshots, logs, or shared docs. | Explain that the ID is used for GA4 requests, avoid recording real IDs in evidence, and decide support redaction guidance. | Needs review |
| Google Access Token | Saved in the WordPress database as part of plugin settings. The Settings form shows saved status only and does not refill the token value. Used as the authorization credential for Google Analytics Data API requests. | Manual token entry is developer-oriented. Public release needs a clear decision on OAuth, expiry, scope, revocation, reconnect, and storage strategy. | Decide whether manual-token storage blocks public release or is redesigned before release readiness. | Hold |
| OpenAI API Key | Saved in the WordPress database as part of plugin settings. The Settings form shows saved status only and does not refill the key value. Used as the authorization credential for OpenAI API requests. | Stored API keys can be accessed by database administrators, backups, server administrators, or code able to read options. Public guidance and support rules are needed. | Decide public key handling strategy, recommend restricted keys, and document that key values must not be shared in support. | Needs decision |
| Hostname filter | Saved in plugin settings when configured. Used to scope GA4 queries and may be included as context in the AI payload. | Hostname/domain details can be sensitive in screenshots, support records, and payload previews. | Document redaction rules and explain when hostname context may be sent to OpenAI. | Needs review |
| Report conditions | Report date range, comparison mode, scope, and path conditions are submitted through admin actions and used to build GA4 requests and AI payload context. | Report conditions may reveal business or site analysis intent. | Explain that these values are sent to GA4 and selected values may be included in the OpenAI payload. | Needs review |
| AI payload transient | Formatted GA4 summary data is stored temporarily in a user-scoped transient for preview and later AI generation. | The preview is useful but may expose analytics data to administrators. Temporary storage and visibility should be clearly documented. | Confirm minimization, transient behavior, preview visibility, and disclosure language before release readiness. | Needs review |
| Generated report text | Returned by OpenAI and displayed in an editable textarea. The MVP does not persist the generated body as plugin data. | Generated content may contain business-sensitive analysis and should be reviewed by the administrator before use. | Explain user review responsibility and non-storage behavior. | Known pass / needs disclosure review |

## External Services Inventory

| Service | Trigger | Data Sent | Data Received | Stored by Plugin | User-visible Disclosure Needed | Status |
|---|---|---|---|---|---|---|
| Google Analytics Data API | Administrator clicks the GA4 Fetch action in Report Builder. | GA4 property identifier, Google authorization credential in the request header, date range, comparison/date-range options, hostname filter when configured, path/scope conditions, requested metrics, and requested dimensions. | Selected analytics metrics and dimensions for the requested report period, such as summary metrics, trends, pages, traffic channels/sources, and regional breakdowns. | Selected response data is formatted into an AI payload preview and stored temporarily in a user-scoped transient. Credential values are stored in settings. | Must explain provider, trigger, purpose, data categories sent and received, credential use, storage behavior, and links to provider terms/privacy. | Needs review |
| OpenAI API | Administrator reviews the AI Payload Preview and clicks the AI report generation action. | OpenAI authorization credential in the request header, selected model name, fixed system instructions, and the reviewed AI payload containing selected GA4 summary data and report context. | Generated report draft text. | Generated report body is displayed in the admin textarea and is not persisted as plugin data by the MVP. OpenAI API Key is stored in settings. | Must explain provider, trigger, purpose, payload categories, generated content handling, credential use, cost/quota implications, and links to provider terms/privacy. | Needs review |

## Current Disclosure Gap Analysis

Already disclosed or substantially covered:

- The current `readme.txt` describes Google Analytics Data API and OpenAI API
  as external services.
- The current `readme.txt` identifies user-triggered actions for GA4 Fetch and
  AI report generation.
- The current `readme.txt` lists the service endpoint URLs and links to provider
  terms and privacy policies.
- The current `readme.txt` describes credential storage as MVP/developer
  verification oriented.
- The current Settings screen warns that Google Access Token and OpenAI API Key
  are saved in the WordPress database and are not redisplayed.
- The current Report Builder describes the staged flow:
  GA4 Fetch, AI Payload Preview, then AI report generation.
- The current admin UI explains that the OpenAI request happens only after the
  administrator reviews the AI payload and submits the generation action.

Gaps or release-risk areas:

- The public release credential strategy is not decided.
- Manual Google Access Token handling remains a blocker candidate for public
  distribution.
- OAuth connection, expiry tracking, scope guidance, revoke, and reconnect
  behavior are not implemented.
- OpenAI API Key storage and public support guidance need a release decision.
- Support/debug redaction rules are not yet consolidated into release-facing
  guidance.
- The release-grade privacy statement is not yet finalized.
- The uninstall/cleanup position for saved credentials and transient data should
  be confirmed before release readiness.
- Error-path disclosure and troubleshooting guidance still need review for
  invalid token, expired token, missing permission, invalid API key, quota,
  rate limit, timeout, and empty-data paths.
- Screenshots, support requests, QA notes, and docs need a clear rule against
  recording real identifiers, credentials, payload bodies, raw responses, or
  generated report bodies.

Disclosure should be explicit before any public release:

- Which external services are used.
- Which user action triggers each external service request.
- What categories of data are sent.
- What categories of data are received.
- Why the data is sent.
- Where credentials are stored in the MVP.
- Which credential values are not redisplayed.
- What is temporarily stored by the plugin.
- What generated content is not persisted by the plugin.
- Which provider terms and privacy policies apply.
- What users should avoid sharing in support channels.

## Credential Strategy Open Questions

- Is storing the Google Access Token in the plugin settings option acceptable
  for any public release, or should public release remain blocked until OAuth is
  implemented?
- If OAuth is required, what connection, refresh, expiry, scope, revocation,
  and reconnect behaviors are mandatory for release readiness?
- Should Google Access Token manual entry remain available only for developer
  verification?
- Is storing the OpenAI API Key in the plugin settings option acceptable for
  public release, or should key storage be separated or redesigned first?
- Should constant-based configuration be supported for OpenAI API Key or other
  credentials before public release?
- What should happen to saved credentials on uninstall?
- What support policy should explicitly forbid users from sharing tokens, keys,
  screenshots of credential values, option dumps, raw request bodies, raw
  responses, and full AI payloads?
- Should admin screenshots and QA evidence require redaction of GA4 property
  identifiers, hostnames, paths, analytics data, and generated report text?

## External Services Disclosure Draft Requirements

Before release readiness, the external services disclosure should provide
release-grade wording for:

- Google Analytics Data API provider name and purpose.
- OpenAI API provider name and purpose.
- The exact admin action that triggers each external request.
- Data categories sent to Google Analytics Data API.
- Data categories received from Google Analytics Data API.
- Data categories sent to OpenAI API.
- Generated report content received from OpenAI API.
- Credential categories used in request headers without exposing their values.
- Temporary AI payload storage behavior.
- Generated report non-storage behavior.
- User review-before-send behavior.
- Cost, quota, and account responsibility notes.
- Provider terms and privacy policy links.
- Support redaction rules.
- A plain statement that no external call is made merely by opening the admin
  screens.

These requirements should be reflected consistently across:

- `readme.txt`
- Settings screen copy
- Report Builder copy
- Future privacy/support documentation
- Future troubleshooting documentation

## Risk Classification

High:

- Public release strategy for Google Access Token storage is unresolved.
- Manual Google Access Token entry is developer-oriented and may not be suitable
  for public distribution.
- OAuth, expiry, scope, revocation, and reconnect behavior are not implemented.
- Public release strategy for OpenAI API Key storage is unresolved.
- Release-grade privacy and external services disclosure are not finalized.
- Support/debug redaction policy is not consolidated.

Medium:

- Invalid credential, expired token, permission, quota, rate limit, timeout, and
  empty-data troubleshooting need release-focused QA.
- Generated report and AI payload privacy guidance should be tightened before
  public use.
- Uninstall behavior for credential-bearing settings should be confirmed.
- Admin screenshots and browser evidence rules should be consolidated for
  future support and release review.

Known pass:

- Saved Google Access Token and OpenAI API Key are not redisplayed in the
  Settings form.
- Credential inputs use status-level display for saved state.
- The current admin flow separates GA4 Fetch from OpenAI generation.
- OpenAI generation is staged behind AI Payload Preview.
- Existing docs and admin copy already disclose the main external service
  providers and action triggers at a high level.

## Recommended Decisions Before Release Readiness

- Decide whether WordPress.org release is blocked until Google OAuth replaces
  manual token entry.
- Decide whether the current credential storage model is acceptable, redesigned,
  or explicitly deferred.
- Decide whether OpenAI API Key storage should support a constant-based or
  separated configuration path before public release.
- Decide whether uninstall should remove saved credentials by default.
- Decide which exact release-facing disclosure wording belongs in `readme.txt`.
- Decide what privacy/support documentation is required outside `readme.txt`.
- Decide redaction rules for all future QA, support, screenshots, and docs.
- Decide whether error-path QA must pass before entering release readiness.

## Recommended Next Steps

Do not proceed directly to WordPress.org release readiness.

Recommended next sequence:

1. Step 73: Admin security review checklist.
2. Step 74: Error-handling QA checklist.
3. Step 75: Data minimization and privacy review.
4. Step 76: WordPress.org compliance checklist.
5. Step 77: WordPress.org readiness checkpoint.

The most useful immediate next step is an admin security review checklist,
because credential handling, external actions, nonces, capabilities,
sanitization, escaping, redirects, and no-leak behavior are all release-critical
and should be reviewed before broader compliance work.

## Release Position

```text
WordPress.org release: Hold
```

Reason:

- Credential strategy is not release-decided.
- Google OAuth/token lifecycle remains unresolved.
- External services and privacy disclosure still need release-grade review.
- Admin security and error-path QA remain pending.
- No release-readiness checkpoint has been completed.

## Outcome

- Credential handling inventory was documented at a category level.
- External services usage was documented at a category level.
- Current disclosure strengths and gaps were identified.
- Public release blocker candidates remain.
- No production code was changed.
- No external API communication was performed.
- No credentials, identifiers, request bodies, payload bodies, raw responses, or
  generated report bodies were recorded.
- WordPress.org release remains `Hold`.
