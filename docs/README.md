# Studio317 Report Drafts for Google Analytics Documentation

Studio317 Report Drafts for Google Analytics is a WordPress admin plugin that helps administrators turn selected GA4 report data into an AI-assisted report draft in the current WordPress user language.

## User Workflow

1. Configure the GA4 property and optional host filter.
2. Configure Google OAuth client settings or provide them by server configuration.
3. Configure an OpenAI API key in Settings or provide it by server configuration.
4. Connect a Google account that can read the selected GA4 property.
5. Fetch GA4 data in Report Builder.
6. Review the Data Preview.
7. Generate a report draft in the current WordPress user language with OpenAI.
8. Review, edit, and copy the draft.

Fetch GA4 Data and Generate AI Report are separate actions. OpenAI is not contacted when GA4 data is fetched, and Google Analytics data is not fetched when only generating from already reviewed report data.

## More Information

See:

- [Data Handling](DATA-HANDLING.md)
- [Development](DEVELOPMENT.md)
- [Release](RELEASE.md)
