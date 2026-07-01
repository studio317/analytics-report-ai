# Data Handling

This document summarizes the public data-handling boundary for Studio317 Report Drafts for Google Analytics.

## External Services

Studio317 Report Drafts for Google Analytics contacts third-party services only after explicit administrator actions:

- Google authorization can be started from Settings.
- Google Analytics Data API requests run when an administrator clicks Fetch GA4 Data.
- OpenAI API requests run when an administrator clicks Generate AI Report after reviewing the Data Preview.

Viewing Settings or Report Builder does not contact Google or OpenAI.

## Google

Google OAuth can be used to connect a Google account that can read the selected GA4 property. The plugin uses callback state validation before token exchange. Authorization codes, token values, provider responses, and option values are not displayed in the plugin admin UI.

Fetch GA4 Data sends selected report conditions and required report metrics or dimensions to the Google Analytics Data API. The request can include the GA4 property ID, date range, comparison settings, host filter, path filter, and Google OAuth access token in the Authorization header.

## OpenAI

Generate AI Report sends the reviewed report data to the OpenAI API. The data can include selected GA4-derived report data, such as report conditions, summary metrics, daily trends, top pages, channels, sources, and regional trends. The selected report output language and locale are included so OpenAI can generate the report draft in the appropriate language.

The OpenAI request uses the configured API key in the Authorization header. The plugin admin UI does not display saved key values.

Generated report text is shown to the administrator as a draft for review, editing, and copying. The plugin does not intentionally save generated report text.

## Local Storage

Plugin-owned settings can include the GA4 property ID, host filter settings, saved Google OAuth client settings, and a saved OpenAI API key when entered in Settings.

Google OAuth token data is stored in a dedicated plugin-owned option. Saved credential values are hidden in the admin UI.

The reviewed report data is stored temporarily in a user-scoped WordPress transient and expires automatically.

On single-site uninstall, the plugin deletes its main settings option and dedicated Google OAuth token option.

## Support Boundary

Support requests should not include credentials, API keys, access tokens, Authorization headers, plugin option values, request bodies, raw API responses, AI data JSON, generated report text, screenshots, browser Network evidence, GA4 property identifiers, host names, page paths, traffic source values, city values, or analytics metric values.
