=== Studio317 Report Drafts for Google Analytics ===
Contributors: cuerda
Tags: analytics, ai, ga4, reports
Requires at least: 7.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Creates AI-assisted report drafts from GA4 data in the WordPress user language with structured review, editing, and copy tools.

== Description ==

Studio317 Report Drafts for Google Analytics helps WordPress administrators fetch selected GA4 report data, review a structured pre-send preview, generate a report draft in the current WordPress user language through the WordPress AI Client, edit the draft, and copy the final text. AI provider setup and credentials are managed in WordPress Settings > Connectors, not by this plugin.

This plugin is developed by Kimiya Watabe / Studio317. It is not affiliated with, endorsed by, or sponsored by Google.

This plugin is not a replacement for the Google Analytics dashboard. It provides a focused admin workflow for turning selected GA4 report data into a reviewed draft that can be edited before use.

= Workflow =

1. Configure the GA4 property and optional host filter.
2. Configure Google OAuth client settings or use server configuration.
3. Configure a compatible AI text-generation provider in WordPress Settings > Connectors.
4. Connect a Google account that can read the selected GA4 property.
5. Open Report Builder and click Fetch GA4 Data.
6. Review the Data Preview.
7. Click Generate AI Report to send the reviewed report data through the WordPress AI Client.
8. Review, edit, and copy the generated draft.

Fetch GA4 Data and Generate AI Report are separate actions. Fetching GA4 data does not contact an AI provider. Generating an AI report does not run until the administrator reviews the Data Preview and clicks the generate button.

Report output language follows the WordPress language setting for the administrator running Report Builder. If the user locale is unavailable, the plugin falls back to the site locale and then to English. The WordPress timezone is used for report periods and date handling, not for choosing the report language.

= Supported site scope =

The initial supported scope is single-site WordPress. Multisite network activation, network uninstall, and cross-site storage behavior are not covered by the initial support scope.

== External Services ==

Studio317 Report Drafts for Google Analytics contacts third-party services only when an administrator explicitly starts Google authorization, clicks Fetch GA4 Data, or clicks Generate AI Report. Viewing Settings or Report Builder alone does not contact Google or an AI provider.

= Google OAuth authorization =

When an administrator clicks Connect Google Account, the browser can be redirected to Google for authorization. After the browser returns, the plugin validates the local callback state and can exchange the authorization result for Google OAuth tokens.

Service endpoints used by this flow:

* Google authorization endpoint: https://accounts.google.com/o/oauth2/v2/auth
* Google token endpoint: https://oauth2.googleapis.com/token

Data sent to Google during this flow may include OAuth client configuration, the plugin redirect URI, requested Google Analytics read-only access, and a local state value. Data received from Google may include an authorization result and token response data. Authorization codes, token values, provider responses, and option values are not displayed in the plugin admin UI.

Google terms and privacy information:

* Google APIs Terms of Service: https://developers.google.com/terms
* Google Privacy Policy: https://policies.google.com/privacy

= Google Analytics Data API =

When an administrator clicks Fetch GA4 Data, the plugin sends requests to the Google Analytics Data API to fetch the selected report data.

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
* City-level regional trends, where available.

The Google Analytics Data API request body is designed not to include AI provider credentials, WordPress user identifiers, cookies, or IP addresses.

= AI generation provider =

When an administrator clicks Generate AI Report, the plugin sends the reviewed report data through the WordPress AI Client to the AI provider configured by the site administrator in WordPress Settings > Connectors.

This plugin does not define a fixed AI provider endpoint. Provider terms, privacy practices, billing, retention, and credential management depend on the AI provider configured by the site administrator through WordPress.

Data sent through the WordPress AI Client may include:

* System instructions, including the selected report output language.
* GA4-derived report data reviewed through the Data Preview.
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
* City-level regional trends, where available.

The report data sent through the WordPress AI Client is designed not to include the Google OAuth token, AI provider credentials, GA4 property ID, WordPress user identifiers, cookies, or IP addresses.

AI generation may consume provider usage, credits, or quota depending on the configured AI provider. Generated report text is shown for administrator review, editing, and copying. The plugin does not intentionally save generated report text. Generated report text is a draft, and administrators should review and edit it before publishing, sharing, or sending it.

== Data Storage and Review ==

The plugin stores plugin-owned settings in the WordPress database. Stored settings can include the GA4 property ID, host filter settings, and saved Google OAuth client settings.

The plugin does not store plugin-owned AI provider API keys. AI provider credentials and provider selection are managed through WordPress Connectors.

Google OAuth token data is stored in a dedicated plugin-owned option. The option is created without autoloading on new storage. Saved credential values are not displayed again in the admin UI.

Server configuration can also provide Google OAuth client settings. Server-managed values take precedence and cannot be edited or deleted from the plugin Settings screen.

The reviewed report data is stored temporarily in a user-scoped WordPress transient and expires automatically. Data validation runs before temporary storage and again before AI generation; missing, expired, old, or invalid report data is not sent through the WordPress AI Client.

Local Google disconnect deletes only local OAuth token data stored by this plugin. It does not contact Google, revoke provider access, delete saved OAuth client settings, or change AI provider configuration.

On single-site uninstall, the plugin deletes its main settings option and its dedicated Google OAuth token option. Provider-side access should be reviewed separately in Google account or Google Cloud settings when needed.

Database administrators, backups, server administrators, or code that can read WordPress options may be able to access stored credential values.

== Installation ==

1. In the WordPress admin area, go to Plugins > Add New.
2. Search for `Studio317 Report Drafts for Google Analytics`.
3. Click Install Now, then Activate.
4. Open Studio317 Report Drafts for Google Analytics > Settings.
5. Enter the numeric GA4 property ID and optional host filter.
6. Configure Google OAuth client settings or provide them by server configuration.
7. Configure a compatible text-generation provider in WordPress Settings > Connectors.
8. Connect a Google account with access to the GA4 property.
9. Open Report Builder to fetch GA4 data, review the Data Preview, and generate a report draft.

== Frequently Asked Questions ==

= Does this plugin replace Google Analytics? =

No. It uses selected GA4 data to help create a report draft in the current WordPress user language. Use Google Analytics for full analytics exploration, attribution, and dashboard workflows.

= When does the plugin contact Google? =

Google can be contacted when an administrator starts Google authorization or clicks Fetch GA4 Data. Viewing Settings or Report Builder alone does not fetch GA4 data.

= When does the plugin contact an AI provider? =

The configured AI provider is contacted through the WordPress AI Client only when an administrator clicks Generate AI Report after reviewing the Data Preview.

= Can I edit the generated report? =

Yes. The generated report is shown as a draft in a textarea so administrators can review, edit, and copy it.

= Does the plugin save generated report text? =

The plugin does not intentionally save generated report text. Administrators can copy the text and store or publish it elsewhere.

= Are credential values displayed again after saving? =

No. Saved Google OAuth client secrets and Google OAuth tokens are hidden in the plugin admin UI. AI provider credentials are managed by WordPress Connectors, not by this plugin.

= How should support requests be prepared? =

Do not send credentials, API keys, access tokens, Authorization headers, plugin option values, request bodies, raw API responses, AI data JSON, generated report text, screenshots, browser Network evidence, GA4 property identifiers, host names, page paths, traffic source values, city values, or analytics metric values. Describe the screen, action, visible status message, warning message, or general error name instead.

== Changelog ==

= 0.1.0 =
* Added GA4 data retrieval, structured Data Preview, AI-assisted report draft generation through the WordPress AI Client, and admin review/edit/copy workflow.
* Added Google OAuth connection handling, hidden credential values, provider-neutral AI setup guidance, external service disclosures, data validation, and localized admin strings.
