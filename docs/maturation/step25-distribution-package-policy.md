# Step 25 Distribution Package Policy

## 1. Overview

This step records the source repository versus future distribution package policy for Analytics Report AI.

This is a docs-only policy step. No production PHP, JavaScript, CSS, readme metadata, plugin header metadata, build script, release script, `.distignore`, zip file, Composer package, npm package, file deletion, or file move was introduced. No external API communication was performed, and no credential value, API key value, Authorization header, full request body, or full payload body was recorded.

## 2. Current Repository Inventory

The repository currently contains these non-`.git` files at max depth 3:

```text
analytics-report-ai.php
assets/css/admin.css
assets/js/admin.js
docs/maturation/step12-mvp-code-audit.md
docs/maturation/step15-credential-storage-policy.md
docs/maturation/step16-report-usage-guardrails.md
docs/maturation/step17-transient-payload-policy.md
docs/maturation/step20-dummy-fixture-cleanup.md
docs/maturation/step21-focused-verification.md
docs/maturation/step22-plugin-check-wpcs-baseline.md
docs/maturation/step23-readme-external-service-disclosure.md
docs/maturation/step24-readme-metadata-alignment.md
includes/class-admin.php
includes/class-ga4-client.php
includes/class-openai-client.php
includes/class-plugin.php
includes/class-prompt-builder.php
includes/class-report-builder.php
includes/class-report-data-formatter.php
includes/class-settings.php
includes/functions-utils.php
readme.txt
```

Directory checks:

- `.git/` exists and is repository metadata only.
- `docs/` exists and currently contains maturation records.
- `.github/` does not exist.
- `tools/` does not exist.
- `tests/` does not exist.
- `vendor/` does not exist.
- `node_modules/` does not exist.
- `.gitignore` was not present in the max-depth file inventory.
- `composer.json`, `composer.lock`, `package.json`, and lockfiles were not present in the max-depth file inventory.
- No separate license file was present; license metadata is currently in the plugin header and `readme.txt`.

The command `find . -maxdepth 3 -type f | sort` also lists `.git/` internals because it does not exclude `.git/`; those files are not considered plugin source or distribution candidates.

## 3. Runtime Package Candidates

These files and directories are required or expected runtime/user-facing package candidates for a future WordPress.org or release zip package:

- `analytics-report-ai.php`
- `includes/`
- `assets/`
- `readme.txt`
- a license file, if one is added in a later step

Rationale:

- `analytics-report-ai.php` is the main plugin file and contains WordPress plugin metadata.
- `includes/` contains runtime PHP classes and helper functions.
- `assets/` contains admin CSS and JavaScript used by plugin screens.
- `readme.txt` contains WordPress.org-facing metadata, changelog, and the Step 23 `== External Services ==` disclosure.

## 4. Source-Only Development Records

The current source-only development record candidate is:

- `docs/maturation/`

This directory contains development, audit, policy, verification, and maturation notes from Steps 12 through 24. These records are valuable in the GitHub source repository because they explain why the MVP hardening decisions were made.

They are not required at runtime and should be treated separately from files needed by WordPress administrators using the plugin.

## 5. `docs/maturation/` Policy

Recommended policy:

- Keep `docs/maturation/` in the GitHub source repository.
- Treat `docs/maturation/` as a source-only development record.
- Exclude `docs/maturation/` from the future WordPress.org / release zip package by default, unless a later review identifies a user-facing reason to include it.

Reasons to keep it in GitHub:

- It provides transparent development history.
- It records security, privacy, external service, credential storage, payload validation, and release-readiness decisions.
- It gives future maintainers context for why the MVP is intentionally scoped.

Reasons to exclude it from release packages:

- Distribution users do not need detailed maturation notes to run the plugin.
- The folder contains many development-only audit details and historical findings.
- Detailed external service and credential notes are useful for maintainers, but noisy inside a runtime package.
- A WordPress.org plugin package should focus on runtime files and user-facing documentation.

This policy depends on `readme.txt` continuing to carry the important user-facing information. Excluding `docs/maturation/` is acceptable only if `readme.txt` and admin UI disclosures remain sufficient.

## 6. Future `.distignore` Candidates

Do not add `.distignore` in this step. If a distribution ignore file is added later, consider excluding:

- `.git/`
- `.github/`
- `docs/maturation/`
- `tools/`
- `tests/`
- `vendor/`, if there are no runtime Composer dependencies
- `node_modules/`
- `composer.json`
- `composer.lock`
- `package.json`
- `package-lock.json`
- `phpcs.xml`
- `.phpcs.xml`
- coverage reports
- logs
- temporary files
- editor/project metadata
- generated build artifacts not needed at runtime

Important caveat:

- If a future architecture introduces runtime dependencies in `vendor/`, do not exclude `vendor/` blindly. Reassess whether dependencies should be bundled, scoped, prefixed, or avoided before building a release package.

## 7. WordPress.org SVN / Release Considerations

Before WordPress.org submission or release packaging, confirm:

- `readme.txt` is included.
- `analytics-report-ai.php` is included.
- `Stable tag` in `readme.txt` matches the plugin header `Version`.
- WordPress.org SVN `/trunk` and `/tags/{version}` contents are intentionally selected.
- Runtime files are present in both SVN and release zip artifacts.
- Source-only development files are either excluded from the package or intentionally kept with a documented reason.
- Plugin Check and WPCS results are reviewed before final packaging.

No SVN operation, release zip creation, or packaging automation is performed in this step.

## 8. Readme Disclosure Dependency

`readme.txt` must remain in the distribution package.

The Step 23 `== External Services ==` section is required user-facing documentation for:

- Google Analytics Data API usage,
- OpenAI API usage,
- data sent to Google,
- data sent to OpenAI,
- credential storage limitations,
- Payload Preview behavior,
- transient storage and validation,
- official Google and OpenAI terms/privacy links.

If `docs/maturation/` is excluded from a release package, this is acceptable only because the essential external service disclosure, credential limitation, and payload review information remains in `readme.txt` and the admin UI.

Before actually excluding `docs/maturation/`, recheck that `readme.txt` still contains the necessary user-facing disclosure and that it has not drifted from runtime behavior.

## 9. Intentionally Not Implemented

This step intentionally does not implement:

- `.distignore`,
- build script,
- release script,
- release zip,
- WordPress.org SVN operations,
- packaging automation,
- file deletion or move,
- production PHP changes,
- production JavaScript changes,
- production CSS changes,
- `readme.txt` changes,
- plugin header metadata changes,
- Composer setup,
- npm setup,
- package installation,
- Plugin Check installation,
- WPCS installation.

## 10. Recommended Next Steps

Recommended next steps:

1. Create a draft `.distignore` in a later packaging step, based on this policy.
2. Decide whether `docs/maturation/` stays source-only in GitHub or appears in any public source archive.
3. Re-run Plugin Check and WPCS when those tools are available.
4. Recheck `readme.txt` external service disclosure immediately before packaging.
5. Decide whether a standalone license file should be added.
6. Create and inspect a release zip in a future step.
7. Verify final SVN `/trunk` and `/tags/{version}` contents before WordPress.org submission.
