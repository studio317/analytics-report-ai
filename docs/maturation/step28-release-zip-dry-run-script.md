# Step 28 Release Zip Dry-Run Script

## Scope

This step adds local dry-run tooling for inspecting a distribution candidate zip.
It is not a formal release step and does not publish to WordPress.org, SVN, GitHub
Releases, or any external service.

Production PHP, JavaScript, CSS, `readme.txt`, plugin header metadata, plugin
version, and `Stable tag` are unchanged.

## Script

The dry-run script is:

```text
tools/build-release-zip-dry-run.sh
```

It resolves the repository root from the script location, reads the plugin header
`Version` from `analytics-report-ai.php`, compares it with the `Stable tag` in
`readme.txt`, stages files with `.distignore`, creates a local inspection zip, and
runs package checks.

The script uses `rsync --exclude-from=.distignore` to copy source files into a
temporary stage directory. It uses `zip` when available. If `zip` is not available,
it uses the Python standard library `zipfile` module so the dry-run can still be
inspected without adding dependencies.

## Output

Dry-run artifacts are written under:

```text
build/release-dry-run/
```

The expected zip path for version `0.1.0` is:

```text
build/release-dry-run/analytics-report-ai-0.1.0.zip
```

`build/` is ignored by Git through `.gitignore`, so dry-run artifacts are not
source-controlled.

## Zip root policy

The zip root directory is:

```text
analytics-report-ai/
```

The dry-run does not use a versioned root such as `analytics-report-ai-0.1.0/`.

## Included runtime files

The script checks that the staged package and zip include:

- `analytics-report-ai/analytics-report-ai.php`
- `analytics-report-ai/includes/`
- `analytics-report-ai/assets/`
- `analytics-report-ai/readme.txt`

## Excluded development files

The script checks that the stage and zip exclude development-only paths from the
Step 26 `.distignore` policy, including:

- `.git/`
- `.distignore`
- `docs/maturation/`
- `build/`
- `tools/`
- `tests/`
- `node_modules/`
- `vendor/`

`vendor/` is treated as excluded because the current MVP has no runtime Composer
dependency. If runtime Composer dependencies are added later, this rule must be
reviewed before release packaging.

## Metadata and disclosure checks

The dry-run checks:

- Plugin header `Version` is readable.
- `readme.txt` `Stable tag` is readable.
- `Version` and `Stable tag` match.
- `readme.txt` contains `== External Services ==`.
- PHP syntax passes for the staged main plugin file and staged `includes/` files.

## Credential and token scan

The script scans the staged package without printing matched values.

High-risk secret-like patterns fail the dry-run, including:

- OpenAI-style `sk-` key patterns.
- Literal `Authorization: Bearer` values.
- Google token-like `ya29.` values.
- `client_secret` assignments that look value-bearing.

Documentation keywords such as `access_token`, `openai_api_key`, and
`client_secret` are warning-only because the source code and readme may mention
credential field names or storage policy without containing real secrets.

If a warning appears, review the filename and context locally without printing
credential values in shared logs.

## Intentionally not implemented

This step intentionally does not:

- Create a formal release.
- Create a GitHub release.
- Commit or deploy to WordPress.org or SVN.
- Change plugin version or `Stable tag`.
- Change production PHP, JavaScript, or CSS.
- Change `readme.txt`.
- Change plugin header metadata.
- Add Composer, npm, Plugin Check, WPCS, or PHPUnit tooling.
- Run GA4, OpenAI, or other external API communication.
- Print credential values, API key values, Authorization header values, or full
  request bodies.

## Future release connection

A future release step can use this dry-run as a preflight before building the
formal release artifact. Before publishing, re-run the dry-run, inspect zip
contents, run Plugin Check and WPCS when available, confirm `vendor/` runtime
dependency status, and verify WordPress.org SVN `/trunk` and `/tags/{version}`
contents.
