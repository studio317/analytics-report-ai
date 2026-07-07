# Studio317 Report Drafts for Google Analytics Documentation

Studio317 Report Drafts for Google Analytics uses selected Google Analytics (GA4) data to prepare an AI-assisted report draft in the current WordPress user language. Administrators can review, edit, and copy the draft in WordPress before using it.

## User Workflow

Initial setup:

1. Configure the GA4 property and optional host filter.
2. Configure Google OAuth client settings or provide them by server configuration.
3. Configure a compatible AI text-generation provider in WordPress Settings > Connectors, where the site administrator manages the provider and its credentials.
4. Click Connect Google Account to connect a Google account that can read the selected GA4 property. Google may ask you to grant read-only access to that property.

Regular use:

1. In Report Builder, choose the report conditions.
2. Click Create AI Report.
3. If the conditions are valid and Google Analytics and the configured AI provider are ready, the plugin fetches the required GA4 report data and requests a report draft through the WordPress AI Client.
4. Review, edit, and copy the draft.

If the plugin cannot continue because of report conditions, Google connection, GA4 data retrieval, or AI provider setup, it shows a message explaining the problem and stops. The selected report conditions remain available so you can correct them and try again.

## More Information

See:

- [Data Handling](DATA-HANDLING.md)
- [Development](DEVELOPMENT.md)
- [Release](RELEASE.md)
