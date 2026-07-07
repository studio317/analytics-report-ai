# Data Handling

This document summarizes the public data-handling boundary for Studio317 Report Drafts for Google Analytics.

## External Services

Studio317 Report Drafts for Google Analytics contacts third-party services only after explicit administrator actions:

- Google OAuth authorization can be started from Settings by clicking Connect Google Account.
- Google Analytics Data API requests can run after an administrator clicks Create AI Report and the selected report conditions are valid.
- AI generation requests run through the WordPress AI Client during Create AI Report only when the required GA4 data is available and a compatible text-generation provider is ready.

Viewing Settings or Report Builder does not contact Google or an AI provider.

## Google

Google OAuth can be used to connect a Google account that can read the selected GA4 property. When a Google Account is connected, Google may ask the administrator to grant this plugin read-only access to the selected Google Analytics property. The plugin uses callback state validation before token exchange. Authorization codes, token values, provider responses, and option values are not displayed in the plugin admin UI.

Create AI Report validates selected report conditions before requesting GA4 data. When Google connection settings are available, the Google Analytics Data API request can include the GA4 property ID, date range, comparison settings, host filter, path filter, required metrics or dimensions, and Google OAuth access token in the Authorization header.

## AI Generation Provider

When the GA4 data fetched during Create AI Report is reportable, the plugin sends the report data through the WordPress AI Client to the AI provider configured by the site administrator in WordPress Settings > Connectors, where the site administrator manages the AI text-generation provider and its credentials. The data can include selected GA4-derived report data, such as report conditions, summary metrics, daily trends, top pages, channels, sources, and regional trends. The selected report output language and locale are included so the configured AI provider can generate the report draft in the appropriate language.

This plugin does not define a fixed AI provider endpoint, store AI provider API keys, or display AI provider credential values. Provider terms, privacy practices, billing, retention, and credential management depend on the AI provider configured by the site administrator through WordPress.

Generated report text is shown to the administrator as a draft for review, editing, and copying. The plugin does not intentionally save generated report text.

## Local Storage

Plugin-owned settings can include the GA4 property ID, host filter settings, and saved Google OAuth client settings.

Google OAuth token data is stored in a dedicated plugin-owned option. Saved credential values are hidden in the admin UI.

Report data prepared during Create AI Report is stored temporarily in a user-scoped WordPress transient and expires automatically.

On single-site uninstall, the plugin deletes its main settings option and dedicated Google OAuth token option.

## Support Boundary

Support requests should not include credentials, API keys, access tokens, Authorization headers, plugin option values, request bodies, raw API responses, AI data JSON, generated report text, screenshots, browser Network evidence, GA4 property identifiers, host names, page paths, traffic source values, city values, or analytics metric values.
