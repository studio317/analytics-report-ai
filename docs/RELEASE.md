# Release

## Package Build

Use the release build script to create a package from the current source tree:

```bash
./tools/build-release-zip-dry-run.sh
```

The package should include runtime PHP, assets, `readme.txt`, `uninstall.php`, and translation files. Development history, build output, tools, tests, and historical verification notes should not be included.

## Suggested Checks

Before using a package candidate, run:

```bash
git diff --check
php -l analytics-report-ai.php
find includes -name '*.php' -print0 | xargs -0 -n1 php -l
```

Inspect the package contents and verify that it does not include development-only files, hidden files, build artifacts, or historical verification documentation.

Do not include credentials, tokens, option values, raw responses, AI data JSON, generated report bodies, screenshots, or browser Network evidence in release notes or support evidence.
