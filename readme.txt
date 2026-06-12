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

== External Services ==

Analytics Report AI uses third-party services only when an administrator starts a report action. Viewing the plugin screens does not, by itself, send data to Google or OpenAI.

= Google Analytics Data API =

When an administrator clicks Fetch GA4 Data, the plugin sends requests to the Google Analytics Data API to fetch the selected GA4 report data.

Service URL: https://analyticsdata.googleapis.com/

Data sent to Google may include:

* GA4 property ID.
* Google Access Token in the Authorization header.
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

The current MVP uses manual Google Access Token entry for developer verification. This is temporary and must be redesigned with an OAuth flow, expiry handling, scope checks, and revoke or reconnect controls before public use.

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

In the MVP, the Google Access Token and OpenAI API Key are saved in the WordPress database as plugin settings. Saved credential values are not displayed again in the admin screen. Database administrators, backups, server administrators, or code that can read WordPress options may be able to access stored credentials. This storage method is for MVP and developer verification, and it needs redesign before public or multi-user use.

The plugin does not send the full raw GA4 response to OpenAI. It formats selected GA4 results into report-generation data, shows a structured Payload Preview before AI generation, and sends the reviewed report data only when Generate AI Report is clicked. The normal admin UI does not expose a full raw AI payload JSON preview.

The reviewed report data is stored temporarily in a user-scoped WordPress transient and expires automatically. Payload validation runs before transient storage and again before OpenAI generation; missing, expired, old, or invalid payloads are not sent to OpenAI.

= Support and Debug Evidence =

Support requests should not include credentials, API keys, access tokens, Authorization headers, plugin settings option values, raw payloads, raw API responses, OpenAI request bodies, generated report text, GA4 property identifiers, hostnames, page paths, traffic source values, city values, or analytics metric values.

For support, describe the issue using status-level information such as the screen, action, warning message, generic error category, generation allowed or blocked state, or redacted UI state.

== Changelog ==

= 0.1.0 =
* Added the MVP flow for GA4 data retrieval, AI payload preview, OpenAI report generation, and admin review/edit/copy workflow.
* Added admin-side safety notices, external service disclosures, credential storage guidance, payload validation, usage guardrails, localized admin JavaScript strings, and maturation documentation.
