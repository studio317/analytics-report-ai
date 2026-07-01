# Development

## Local Checks

Useful local checks before packaging:

```bash
php -l studio317-report-drafts-google-analytics.php
find includes -name '*.php' -print0 | xargs -0 -n1 php -l
git diff --check
```

If WordPress i18n tooling is available, regenerate translation files from the current source before a package build.

## Development Boundaries

- Keep credential values out of logs, screenshots, docs, and command output.
- Do not record option values, OAuth token values, authorization codes, raw provider responses, OpenAI request bodies, AI data JSON, or generated report text.
- Keep Fetch GA4 Data and Generate AI Report as separate administrator actions.
- Keep generated report text as a user-reviewed draft.
- Keep report output language resolved from the Report Builder user's WordPress locale, with site locale and English fallbacks.
- Preserve the hidden-value posture for saved credentials.

## Text Domain

The plugin text domain is `studio317-report-drafts-google-analytics`.

Translation files live in `languages/`.
