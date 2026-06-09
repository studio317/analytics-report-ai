# Step 26 Distignore Draft

## Scope

This step adds a draft `.distignore` for future WordPress.org / release zip packaging.
It does not create a release zip, add build scripts, run SVN commands, move files,
or change production PHP, JavaScript, CSS, `readme.txt`, or plugin header metadata.

## Draft file

The new `.distignore` is source-control guidance for future packaging. It should be
reviewed again before any release zip is created because packaging needs may change.

## Runtime files intentionally not excluded

The draft keeps these runtime files in future packages:

- `analytics-report-ai.php`
- `includes/`
- `assets/`
- `readme.txt`
- A license file, if one is added later

## Source-only development records

`docs/maturation/` remains part of the GitHub source repository so the MVP maturity
decisions stay reviewable. It is listed in `.distignore` because these internal
notes are not intended for WordPress.org / release zip packages by default.

## Exclusion groups

The draft excludes repository metadata, development tools, tests, coding-standard
configuration, PHPUnit configuration, npm metadata, Composer metadata, coverage
reports, logs, temporary files, build artifacts, IDE files, and OS metadata.

`.distignore` itself is also listed as a distribution exclusion candidate because
it is release tooling rather than runtime plugin code.

## Vendor caveat

`vendor/` is excluded because the current MVP has no runtime Composer dependency.
If a future version adds runtime Composer dependencies under `vendor/`, this rule
must be revisited before packaging so required runtime code is not omitted.

## Intentionally not implemented

This step intentionally does not:

- Create a release zip
- Add a build or release script
- Run SVN commands
- Delete or move source files
- Add Composer, npm, Plugin Check, WPCS, or PHPUnit tooling
- Change runtime plugin code
- Run GA4, OpenAI, or other external API communication
- Print credentials, API keys, Authorization headers, or full request bodies

## Future release zip verification

Before using this draft for a real release package, verify the zip contents:

- Create the release zip in a dedicated release step
- Inspect the zip file list before publishing
- Confirm `analytics-report-ai.php`, `includes/`, `assets/`, and `readme.txt` are included
- Confirm `docs/maturation/` is excluded
- Confirm `.distignore` is excluded if that remains the desired policy
- Confirm future runtime dependencies, including any `vendor/` content, are included when needed
- Re-run syntax checks and any available Plugin Check / WPCS release checks
