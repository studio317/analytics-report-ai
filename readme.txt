=== Analytics Report AI ===
Contributors: cuerda
Tags: analytics, ai, ga4, reports
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Creates AI-assisted Japanese report drafts from GA4 data with structured preview, editing, and copy tools.

== Description ==

Analytics Report AI helps administrators fetch selected GA4 report data, review a structured pre-send preview, generate a Japanese report draft with OpenAI, edit the draft, and copy the final text.

This MVP version is intended for development and verification.

= Supported Site Scope =

The initial supported scope is limited to single-site WordPress installations. WordPress multisite, network activation and deactivation, network uninstall, and cross-site storage or cleanup behavior are outside the initial supported scope. This is a support-scope boundary and does not determine whether the plugin can run in a particular multisite installation.

== External Services ==

Analytics Report AI contacts third-party services only when an administrator explicitly starts Google OAuth authorization, clicks Fetch GA4 Data, or clicks Generate AI Report. Viewing Settings or Report Builder alone does not contact Google or OpenAI.

= Google OAuth Authorization =

When an administrator starts Google OAuth authorization, the browser can be redirected to Google. After the browser returns, the plugin can attempt a callback-bound authorization-code exchange only after callback state validation. This authorization action is separate from Fetch GA4 Data and does not itself retrieve GA4 report data. Automatic refresh is not a public-release capability; reconnect is the bounded recovery posture. Provider-side revoke is not performed in the selected public scope.

= Google Analytics Data API =

When an administrator clicks Fetch GA4 Data, the plugin sends requests to the Google Analytics Data API to fetch the selected GA4 report data.

Service URL: https://analyticsdata.googleapis.com/

Data sent to Google may include:

* GA4 property ID.
* Google OAuth access token in the Authorization header.
* Selected date range.
* Comparison setting and comparison date range, when comparison is enabled.
* HostName filter, when enabled.
* PagePath filter, when directory or page scope is selected.
* Required metrics and dimensions for the report presets.

Data received from Google may include:

* Summary metrics.
* Daily trend.
* Top pages.
* Traffic channels.
* Traffic sources.
* City-level regional trends for Japan.

The Google Analytics Data API request body is designed not to include the OpenAI API Key, WordPress user information, cookies, or IP addresses.

Google OAuth is the normal GA4 credential source. The retired manual Google Access Token fallback is not a normal public-release credential path. If OAuth token recovery is needed, reconnect is the bounded recovery posture. Refresh request execution and provider-side revoke remain separate deferred tracks and are not described as implemented behavior.

Google terms and privacy information:

* Google APIs Terms of Service: https://developers.google.com/terms
* Google Privacy Policy: https://policies.google.com/privacy

= OpenAI API =

When an administrator clicks Generate AI Report, the plugin sends a request to the OpenAI API to generate a Japanese report draft from the reviewed report data.

Service URL: https://api.openai.com/v1/responses

Data sent to OpenAI may include:

* OpenAI API Key in the Authorization header.
* Selected model name.
* Fixed system instructions.
* GA4-derived report data reviewed through the structured Payload Preview.

When the administrator generates an AI report, report data derived from GA4 may be sent to OpenAI. The data is based on the selected date range, comparison setting, data scope, filters, and report presets.

Report data sent to OpenAI may include:

* Host name.
* Date range and comparison information.
* Normalized path condition.
* Summary metrics and calculated differences.
* Daily trend.
* Top pages.
* Traffic channels.
* Traffic sources.
* City-level regional trends.

The plugin shows a structured Payload Preview before AI generation. The normal admin UI does not expose a full raw AI payload JSON preview.

The report data sent to OpenAI is designed not to include the Google Access Token, OpenAI API Key, GA4 property ID, WordPress user information, cookies, or IP addresses.

OpenAI API usage may consume API credits or quota. Generated report text is shown for user review, editing, and copying. The plugin does not save generated report text. Generated report text is a draft, and users should review and edit it before publishing, sharing, or sending it.

OpenAI terms and privacy information:

* OpenAI Service Terms: https://openai.com/policies/service-terms/
* OpenAI Privacy Policy: https://openai.com/policies/privacy-policy/

= Credential Storage and Payload Review =

In the MVP, Google OAuth token data is stored in a dedicated plugin-owned option. OAuth client Settings fallback configuration and existing legacy / transitional OpenAI API key fallback values may be stored in plugin settings. Saved credential values are not displayed again in the admin screen. Database administrators, backups, server administrators, or code that can read WordPress options may be able to access stored credential categories. This storage posture is for MVP and developer verification, and existing legacy / transitional OpenAI API key fallback storage and OAuth client Settings fallback storage remain separate public-release decisions.

For OpenAI configuration, use the preferred constant-based source: ANALYTICS_REPORT_AI_OPENAI_API_KEY. Define this constant through a configuration mechanism that is loaded before WordPress plugins. The exact placement depends on your hosting or deployment configuration. The plugin does not display or edit this value.

OpenAI source category or readiness status indicates which configuration source the plugin can use. It does not verify provider authorization or prove that an OpenAI API request will succeed. Provider/runtime failures remain separate from configuration-source status.

Existing installations may show a saved legacy / transitional Settings fallback as a hidden compatibility state. This is a developer-only / transitional fallback and is not the normal public configuration route. If a removal control is visible, it applies only to the saved Settings fallback and does not edit constant-based configuration.

The plugin does not send the full raw GA4 response to OpenAI. It formats selected GA4 results into report-generation data, shows a structured Payload Preview before AI generation, and sends the reviewed report data only when Generate AI Report is clicked. The normal admin UI does not expose a full raw AI payload JSON preview.

The reviewed report data is stored temporarily in a user-scoped WordPress transient and expires automatically. Payload validation runs before transient storage and again before OpenAI generation; missing, expired, old, or invalid payloads are not sent to OpenAI.

Local-only Google OAuth disconnect deletes local OAuth token data only. It does not perform provider-side revoke, execute refresh requests, delete OAuth client Settings fallback values, or delete the OpenAI API key. Plugin uninstall cleanup is a separate plugin-owned option cleanup boundary and does not mean provider-side revoke.

= Support and Debug Evidence =

Support requests should not include credentials, API keys, access tokens, Authorization headers, plugin settings option values, raw payloads, raw API responses, OpenAI request bodies, generated report text, GA4 property identifiers, hostnames, page paths, traffic source values, city values, or analytics metric values.

For support, describe the issue using status-level information such as the screen, action, warning message, generic error category, generation allowed or blocked state, or redacted UI state.

== Changelog ==

= 0.1.0 =
* Added the MVP flow for GA4 data retrieval, AI payload preview, OpenAI report generation, and admin review/edit/copy workflow.
* Added admin-side safety notices, external service disclosures, credential storage guidance, payload validation, usage guardrails, localized admin JavaScript strings, and maturation documentation.
