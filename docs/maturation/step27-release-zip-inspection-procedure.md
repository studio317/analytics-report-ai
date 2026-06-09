# Step 27 Release Zip Inspection Procedure

## Scope

This step records a future inspection procedure for WordPress.org / release zip
contents. It is docs-only.

This step does not create a release zip, measure zip contents, add build scripts,
change `.distignore`, run SVN commands, delete or move files, change production
PHP / JavaScript / CSS, change `readme.txt`, or change plugin header metadata.

No GA4, OpenAI, or other external API communication is performed by this
procedure. No credential value, API key value, Authorization header, request body,
payload dump, local log, or debug output should be included in the package or
printed during inspection.

## Zip root directory

Future release zips should contain a single top-level directory:

```text
analytics-report-ai/
```

The zip should not place plugin files directly at the archive root, and it should
not use a versioned root such as `analytics-report-ai-0.1.0/` unless a later
release policy intentionally changes that expectation.

## Required runtime files

Confirm that the release zip includes at least:

```text
analytics-report-ai/analytics-report-ai.php
analytics-report-ai/includes/
analytics-report-ai/assets/
analytics-report-ai/readme.txt
```

If a standalone license file is added later, confirm that it is also included in
the package.

## Excluded development files

Based on the Step 26 `.distignore` draft, confirm that the release zip excludes:

- `.git/`
- `.github/`
- `.gitignore`
- `.distignore`
- `docs/maturation/`
- `tools/`
- `tests/`
- `node_modules/`
- `vendor/`, when there is no runtime Composer dependency
- Composer metadata such as `composer.json` and `composer.lock`
- npm metadata such as `package.json`, `package-lock.json`, and `npm-debug.log`
- PHPCS and PHPUnit configuration files
- coverage reports
- logs
- temporary files
- build artifacts
- IDE/editor project files
- OS metadata files

`docs/maturation/` should remain in the GitHub source repository as a development
record, but it should be absent from the WordPress.org / release zip package by
default.

## `.distignore` effect check

When a release zip is created in a later step, inspect the archive contents
against the Step 26 `.distignore` draft. Treat any included source-only or
development-only file as a packaging issue unless there is a documented reason to
include it.

Do not change `.distignore` during the inspection pass without making that change
an explicit release-scope decision.

## `vendor/` caveat

The current MVP has no runtime Composer dependency, so `vendor/` is a valid
exclusion candidate.

Before every release package, confirm whether runtime Composer dependencies have
been added. If `vendor/` contains runtime code required by the plugin, it may need
to be included or handled through a documented dependency strategy. Do not exclude
runtime dependencies blindly.

## Readme and header metadata

Confirm in the unpacked release package that:

- `analytics-report-ai/analytics-report-ai.php` is present.
- The plugin header `Version` matches the intended release version.
- The plugin header `Text Domain` remains `analytics-report-ai`.
- `readme.txt` is present.
- `Stable tag` in `readme.txt` matches the plugin header `Version`.
- `Requires at least`, `Tested up to`, and `Requires PHP` reflect verified release targets.
- `== Changelog ==` describes the release accurately.

## External Services disclosure

Confirm that `readme.txt` still includes the user-facing external service
disclosure, including:

- Google Analytics Data API usage.
- OpenAI API usage.
- Data sent to Google.
- Data sent to OpenAI.
- Data intentionally not sent, such as credential values.
- Credential storage limitations.
- Payload Preview behavior.
- Transient validation and short-term storage behavior.
- Official Google and OpenAI Terms / Privacy links.

If `docs/maturation/` is excluded from the package, the essential user-facing
disclosure must remain available in `readme.txt` and the admin UI.

## Security and privacy inspection

Inspect the package for accidental inclusion of:

- Real API keys.
- Google Access Tokens.
- Authorization headers.
- Local config files.
- Logs.
- Debug outputs.
- Request body dumps.
- Payload dumps.
- Temporary files that may include site-specific data.

Any such file should block packaging until removed from the release artifact and
investigated in source control history as appropriate.

## Future command candidates

The following commands are examples for a future release inspection step. Do not
run them in this docs-only step.

```sh
zipinfo -1 analytics-report-ai-0.1.0.zip
unzip -l analytics-report-ai-0.1.0.zip
unzip -q analytics-report-ai-0.1.0.zip -d /tmp/analytics-report-ai-release-check
find /tmp/analytics-report-ai-release-check -maxdepth 4 -type f | sort
php -l /tmp/analytics-report-ai-release-check/analytics-report-ai/analytics-report-ai.php
find /tmp/analytics-report-ai-release-check/analytics-report-ai/includes -name '*.php' -print0 | xargs -0 -n1 php -l
grep -nE "Version|Text Domain|Requires at least|Requires PHP" /tmp/analytics-report-ai-release-check/analytics-report-ai/analytics-report-ai.php
grep -nE "Stable tag|Tested up to|External Services|Changelog" /tmp/analytics-report-ai-release-check/analytics-report-ai/readme.txt
```

Use a fresh temporary directory for each inspection and delete the extracted copy
after review in the dedicated release step.

## Zip contents checklist

Before publishing or copying files to WordPress.org SVN, confirm:

- The zip has one top-level `analytics-report-ai/` directory.
- Required runtime files are included.
- `readme.txt` is included.
- `== External Services ==` is included in `readme.txt`.
- Google and OpenAI Terms / Privacy links are present.
- `docs/maturation/` is excluded.
- `.git/` is excluded.
- `.distignore` is excluded, if that remains the desired policy.
- Development tools, tests, package manager metadata, logs, coverage reports,
  temporary files, and build artifacts are excluded.
- No credential, token, Authorization header, request body dump, or payload dump
  is included.
- Plugin header `Version` and readme `Stable tag` match.
- `Tested up to` matches the WordPress version actually verified for release.
- Runtime Composer dependency status has been checked before excluding `vendor/`.

## Plugin Check, WPCS, and smoke readiness

Before WordPress.org submission, run Plugin Check and WPCS in a controlled
environment when those tools are available. Record any baseline findings and
blockers before publishing.

Manual admin smoke testing should confirm that the Settings and Report Builder
screens load from the packaged plugin, assets are present, localized admin UI
strings still render, and the GA4 fetch to Payload Preview to AI generation flow
is ready for a release smoke test. Any real external API smoke test should be an
explicit later release action with approved test credentials and no credential
values printed in logs or output.

## SVN preflight

Before copying to WordPress.org SVN:

- Recheck final package contents.
- Confirm `/trunk` contents match the intended release candidate.
- Confirm `/tags/{version}` contents match the exact packaged release.
- Confirm source-only files excluded from the zip are also intentionally excluded
  from SVN release locations unless a later policy says otherwise.

## Intentionally not implemented

This step intentionally does not implement:

- Release zip creation.
- Zip contents measurement.
- Build script or release script creation.
- `.distignore` changes.
- SVN operations.
- Plugin Check or WPCS installation.
- File deletion or movement.
- Production code changes.
- `readme.txt` changes.
- Plugin header metadata changes.
