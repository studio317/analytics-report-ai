=== Studio317 Report Drafts for Google Analytics ===
Contributors: cuerda
Tags: analytics, ai, ga4, reports
Requires at least: 7.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.3.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Uses selected Google Analytics (GA4) data to prepare AI-assisted report drafts that administrators can review, edit, and copy in WordPress.

== Description ==

This plugin uses selected Google Analytics (GA4) data to prepare an AI-assisted report draft. You can review, edit, and copy the draft in WordPress before using it. AI provider setup and credentials are managed in WordPress Settings > Connectors, where the site administrator configures the AI text-generation provider and its credentials.

This plugin is developed by Kimiya Watabe / Studio317. It is not affiliated with, endorsed by, or sponsored by Google.

This plugin is not a replacement for the Google Analytics dashboard. It provides a focused admin workflow for turning selected GA4 report data into a reviewed draft that can be edited before use.

= Workflow =

Initial setup:

1. Configure the GA4 property and optional host filter.
2. Configure a compatible AI text-generation provider in WordPress Settings > Connectors.
3. Click Connect Google Account.
4. Approve read-only Google Analytics access in Google.
5. Open Report Builder and click Create AI Report after choosing the report conditions.

New installations and sites without legacy OAuth configuration or token storage use Studio317-managed Google OAuth by default. They normally do not need a Google Cloud OAuth Client ID or Client Secret. Existing installations that already have self-managed OAuth configuration or legacy token storage remain in the legacy compatibility mode so their current connection is not disrupted.

Regular use:

1. In Report Builder, choose the report conditions, such as date range, comparison, and scope.
2. Click Create AI Report.
3. If the conditions are valid and Google Analytics and the configured AI provider are ready, the plugin fetches the required GA4 report data and requests a report draft through the WordPress AI Client.
4. Review, edit, and copy the generated draft.

If the plugin cannot continue because of report conditions, Google connection, GA4 data retrieval, or AI provider setup, it shows a message explaining the problem and stops. The selected report conditions remain available so you can correct them and try again.

Report output language follows the WordPress language setting for the administrator running Report Builder. If the user locale is unavailable, the plugin falls back to the site locale and then to English. The WordPress timezone is used for report periods and date handling, not for choosing the report language.

= Supported site scope =

The initial supported scope is single-site WordPress. Multisite network activation, network uninstall, and cross-site storage behavior are not covered by the initial support scope.

== External Services ==

Studio317 Report Drafts for Google Analytics contacts third-party services only when an administrator explicitly starts Google authorization or clicks Create AI Report. Viewing Settings or Report Builder alone does not contact Google or an AI provider.

= Google OAuth authorization =

When an administrator clicks Connect Google Account, new installations and sites without legacy OAuth configuration or token storage use the Studio317 OAuth service to coordinate Google authorization and the token exchange. The browser is redirected to Google, where the administrator may grant read-only access to Analytics data available to the Google account. The authorization scope is https://www.googleapis.com/auth/analytics.readonly. Actual report requests use the GA4 Property ID selected in the plugin Settings.

Service endpoints used by this flow:

* Studio317 OAuth service: https://oauth.s317.jp/
* Google authorization endpoint: https://accounts.google.com/o/oauth2/v2/auth
* Google token endpoint: https://oauth2.googleapis.com/token

The WordPress site sends the Studio317 OAuth service the site callback URL, temporary site and transaction identifiers needed for OAuth processing, request timestamps, and temporary cryptographic transaction material. When token refresh is needed, the site sends an encrypted refresh capability and signed request metadata. The Studio317 OAuth service communicates with Google's OAuth and token endpoints and returns the authorization or refresh result to the WordPress site.

The Studio317 OAuth service does not persist per-user Google OAuth tokens or GA4 report data in its database. GA4 reporting data does not pass through the Studio317 OAuth service: Google Analytics Data API requests are sent directly from the WordPress site to Google. AI report data is not sent to the Studio317 OAuth service.

Existing installations that already use self-managed OAuth may continue in the legacy compatibility mode. Data sent to Google in that mode may include OAuth client configuration, the plugin redirect URI, requested Google Analytics read-only access, and a local state value. Data received from Google may include an authorization result and token response data. Authorization codes, token values, provider responses, and option values are not displayed in the plugin admin UI.

Terms and privacy information:

* Studio317 Privacy Policy: https://business.s317.com/privacy_policy/
* Studio317 Terms of Service: https://business.s317.com/legal/
* Google APIs Terms of Service: https://developers.google.com/terms
* Google Privacy Policy: https://policies.google.com/privacy

= Google Analytics Data API =

When an administrator clicks Create AI Report, the plugin first validates the selected report conditions. If Google connection settings are available, it sends requests to the Google Analytics Data API to fetch the required report data.

Service URL: https://analyticsdata.googleapis.com/

Data sent to Google may include:

* GA4 property ID.
* Google OAuth access token in the Authorization header.
* Selected date range.
* Comparison setting and comparison date range, when comparison is enabled.
* Host name filter, when enabled.
* Page path filter, when directory or page scope is selected.
* Required metrics and dimensions for the report presets.

Data received from Google may include:

* Summary metrics.
* Daily trend data.
* Top pages.
* Traffic channels.
* Traffic sources.
* Regional trends by city and country, where available.

The Google Analytics Data API request body is designed not to include AI provider credentials, WordPress user identifiers, cookies, or IP addresses.

= AI generation provider =

When the GA4 data fetched during Create AI Report is reportable, the plugin requests a draft through the WordPress AI Client and the AI provider configured by the site administrator in WordPress Settings > Connectors.

This plugin does not define a fixed AI provider endpoint. Provider terms, privacy practices, billing, retention, and credential management depend on the AI provider configured by the site administrator through WordPress.

Data sent through the WordPress AI Client may include:

* System instructions, including the selected report output language.
* GA4-derived report data prepared from the selected report conditions.
* Report output language and locale information resolved from WordPress locale settings.

Report data sent through the WordPress AI Client may include:

* Host name.
* Date range and comparison information.
* Normalized path condition.
* Summary metrics and calculated differences.
* Daily trend data.
* Top pages.
* Traffic channels.
* Traffic sources.
* Regional trends by city and country, where available.

The report data sent through the WordPress AI Client is designed not to include the Google OAuth token, AI provider credentials, GA4 property ID, WordPress user identifiers, cookies, or IP addresses.

AI generation may consume provider usage, credits, or quota depending on the configured AI provider. Generated report text is shown for administrator review, editing, and copying. The plugin does not intentionally save generated report text. Generated report text is a draft, and administrators should review and edit it before publishing, sharing, or sending it.

== Data Storage and Review ==

The plugin stores plugin-owned settings in the WordPress database. Stored settings include the GA4 property ID and host filter settings. Existing self-managed installations may also retain legacy OAuth client configuration for compatibility.

The plugin does not store plugin-owned AI provider API keys. AI provider credentials and provider selection are managed through WordPress Connectors.

In Studio317-managed OAuth mode, Google OAuth token material is stored in encrypted form as plugin-owned data within the WordPress site. Credential and token values are not displayed again in the plugin admin UI. Existing self-managed installations may continue to use legacy OAuth configuration and connection storage.

Temporary report data used during generation is stored briefly for the current administrator and expires automatically. Data validation runs before temporary storage and again before AI generation; missing, expired, old, or invalid report data is not sent through the WordPress AI Client.

Local Google disconnect deletes only local OAuth token data stored by this plugin. It does not contact Google, revoke provider access, delete saved OAuth client settings, or change AI provider configuration.

On single-site uninstall, the plugin deletes its main settings, legacy OAuth connection data, and Studio317-managed OAuth connection data. Uninstall and local disconnect do not revoke provider-side Google access. Provider-side access should be reviewed separately in Google Account settings when needed.

Database administrators, backups, server administrators, or code that can read WordPress options may be able to access stored credential values.

== Installation ==

1. In the WordPress admin area, go to Plugins > Add New.
2. Search for `Studio317 Report Drafts for Google Analytics`.
3. Click Install Now, then Activate.
4. Open Studio317 Report Drafts for Google Analytics > Settings.
5. Enter the numeric GA4 property ID and optional host filter.
6. Configure a compatible text-generation provider in WordPress Settings > Connectors.
7. Click Connect Google Account and approve read-only Google Analytics access in Google.
8. Open Report Builder, choose the report conditions, and click Create AI Report.
9. Review, edit, and copy the generated report draft.

New and clean installations use Studio317-managed Google OAuth and normally do not require a Google Cloud OAuth Client ID or Client Secret. Existing installations with self-managed OAuth configuration or legacy token storage continue in the legacy compatibility mode.

== Frequently Asked Questions ==

= Does this plugin replace Google Analytics? =

No. It uses selected GA4 data to help create a report draft in the current WordPress user language. Use Google Analytics for full analytics exploration, attribution, and dashboard workflows.

= When does the plugin contact Google? =

Google can be contacted when an administrator starts Google authorization or clicks Create AI Report. Viewing Settings or Report Builder alone does not fetch GA4 data.

= Do I need to create a Google OAuth Client ID and Client Secret? =

No. New and clean installations use Studio317-managed OAuth and normally do not require you to create Google OAuth client credentials. Existing installations that already use self-managed OAuth may continue using the legacy compatibility mode.

= When does the plugin contact an AI provider? =

The configured AI provider is contacted through the WordPress AI Client only during Create AI Report, after the selected conditions are valid and the required GA4 report data is available for generation.

= Can I edit the generated report? =

Yes. The generated report is shown as a draft in a textarea so administrators can review, edit, and copy it.

= Does the plugin save generated report text? =

The plugin does not intentionally save generated report text. Administrators can copy the text and store or publish it elsewhere.

= Are credential values displayed again after saving? =

No. Google OAuth token values are hidden in the plugin admin UI. Existing self-managed installations may retain legacy OAuth client configuration, but saved client secrets are not displayed again. AI provider credentials are managed by WordPress Connectors, not by this plugin.

= How should support requests be prepared? =

Do not send credentials, API keys, access tokens, Authorization headers, plugin option values, request bodies, raw API responses, AI data JSON, generated report text, screenshots, browser Network evidence, GA4 property identifiers, host names, page paths, traffic source values, city values, or analytics metric values. Describe the screen, action, visible status message, warning message, or general error name instead.

== Changelog ==

= 0.3.0 =
* Added Studio317-managed Google OAuth as the default for new and clean installations, so users normally no longer need to create their own Google Cloud OAuth client credentials.
* Added encrypted local storage and on-demand refresh handling for managed Google OAuth connections through the Studio317 OAuth service, while GA4 Data API requests continue to be sent directly from WordPress to Google.
* Preserved legacy self-managed OAuth compatibility for existing installations with previous OAuth configuration or token storage.
* Updated Google connection status, external-service documentation, and regional data wording for the managed OAuth workflow.
* Verified compatibility with WordPress 7.1.

= 0.2.0 =
* Simplified Report Builder so administrators can create an AI-assisted report draft from selected report conditions in one action.
* Added a Current Status screen for read-only setup and Google account connection status.
* Improved setup guidance, settings organization, and Japanese administrator-facing wording.
* Updated public documentation to clarify the integrated report creation workflow and external-service data handling.

= 0.1.0 =
* Added GA4 data retrieval, structured Data Preview, AI-assisted report draft generation through the WordPress AI Client, and admin review/edit/copy workflow.
* Added Google OAuth connection handling, hidden credential values, provider-neutral AI setup guidance, external service disclosures, data validation, and localized admin strings.
