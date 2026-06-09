# Step 33 WPCS / PHPCS Measured Baseline

## 1. Overview

This step adds project-local PHPCS / WPCS development tooling and records the
first measured WPCS baseline for Analytics Report AI runtime PHP files.

This is a setup, measurement, and classification step only. No WPCS findings are
fixed here, and `phpcbf` is not run.

No production PHP, JavaScript, CSS, `readme.txt`, plugin header metadata,
version, `Stable tag`, dry-run script behavior, GA4 request logic, OpenAI request
logic, transient behavior, or payload validation behavior is changed.

## 2. Tooling Setup

Initial state:

- `composer --version`: `Composer 2.2.6`
- global `phpcs`: not installed
- project-local `vendor/bin/phpcs`: not installed

Composer dev tooling was added locally:

```sh
composer config allow-plugins.dealerdirect/phpcodesniffer-composer-installer true
composer require --dev wp-coding-standards/wpcs:"^3.0" --no-interaction
```

The first `composer config` attempt failed because `composer.json` did not exist.
A minimal project-local `composer.json` was added, then the config and require
commands succeeded.

## 3. Composer Dependencies

Composer installed these locked dev packages:

- `dealerdirect/phpcodesniffer-composer-installer` `v1.2.1`
- `phpcsstandards/phpcsextra` `1.5.0`
- `phpcsstandards/phpcsutils` `1.2.2`
- `squizlabs/php_codesniffer` `3.13.5`
- `wp-coding-standards/wpcs` `3.3.0`

`composer.json` and `composer.lock` are source-controlled development tooling
metadata. `vendor/` is not source-controlled.

## 4. PHPCS Standards Available

The measured PHPCS executable is:

```text
vendor/bin/phpcs
```

Version:

```text
PHP_CodeSniffer version 3.13.5 (stable) by Squiz and PHPCSStandards
```

Available standards:

```text
MySource, PEAR, PSR1, PSR2, PSR12, Squiz, Zend, Modernize, NormalizedArrays,
Universal, PHPCSUtils, WordPress, WordPress-Core, WordPress-Docs,
WordPress-Extra
```

Installed paths:

```text
../../phpcsstandards/phpcsextra,../../phpcsstandards/phpcsutils,../../wp-coding-standards/wpcs
```

## 5. Ruleset File

Added:

```text
phpcs.xml.dist
```

The ruleset targets runtime PHP files only:

- `analytics-report-ai.php`
- `includes/`

It excludes development and generated paths:

- `build/`
- `docs/`
- `tools/`
- `vendor/`
- `.git/`

The ruleset uses the `WordPress` standard, sets `testVersion` to `7.4-`, sets
the text domain to `analytics-report-ai`, and sets accepted project prefixes:

- `analytics_report_ai`
- `Analytics_Report_AI`
- `ANALYTICS_REPORT_AI`

The ruleset was added to `.distignore` as `phpcs.xml.dist` so it is excluded
from future release zip packages.

## 6. Commands Run

Setup and inspection:

```sh
composer --version
phpcs --version || true
phpcs -i || true
test -x vendor/bin/phpcs && vendor/bin/phpcs --version || true
test -x vendor/bin/phpcs && vendor/bin/phpcs -i || true
composer config allow-plugins.dealerdirect/phpcodesniffer-composer-installer true
composer require --dev wp-coding-standards/wpcs:"^3.0" --no-interaction
vendor/bin/phpcs --version
vendor/bin/phpcs -i
vendor/bin/phpcs --config-show installed_paths
```

Syntax and WPCS measurement:

```sh
php -l analytics-report-ai.php
find includes -name '*.php' -print0 | xargs -0 -n1 php -l
vendor/bin/phpcs -ps
vendor/bin/phpcs --report=summary
vendor/bin/phpcs --report=source
```

Packaging and Plugin Check follow-up:

```sh
bash -n tools/build-release-zip-dry-run.sh
./tools/build-release-zip-dry-run.sh
zipinfo -1 build/release-dry-run/analytics-report-ai-0.1.0.zip | grep -E "vendor/|composer.json|composer.lock|phpcs.xml|phpcs.xml.dist|\.phpcs"
wp --path=/var/www/html/wp-dev plugin check /var/www/html/analytics-report-ai/build/release-dry-run/stage/analytics-report-ai
```

## 7. Baseline Result Summary

Measured command:

```sh
vendor/bin/phpcs -ps
```

Summary:

| Type | Count |
| --- | ---: |
| Errors | 16 |
| Warnings | 13 |
| Total violations | 29 |
| Files with violations | 9 |
| PHPCBF-fixable violations | 12 |

`vendor/bin/phpcs --report=summary` reported:

```text
A TOTAL OF 16 ERRORS AND 13 WARNINGS WERE FOUND IN 9 FILES
PHPCBF CAN FIX 12 OF THESE SNIFF VIOLATIONS AUTOMATICALLY
```

`phpcbf` was not run.

## 8. Findings Groups

| Priority | Standard | Sniff code | Severity | Count | Affected files / lines | Summary | Recommended action | Suggested step |
| --- | --- | --- | --- | ---: | --- | --- | --- | --- |
| P1 | WordPress | `WordPress.Files.FileName.InvalidClassFileName` | Error | 8 | all class files in `includes/class-*.php`, line 1 | Class file names do not include the full class-derived filename expected by WPCS. | Decide whether to rename files and update loader references, or document a ruleset exclusion if the shorter MVP file names are intentionally kept. | Step 34 |
| P1 | Squiz | `Squiz.Commenting.ClassComment.Missing` | Error | 8 | all class declarations, line 12 | Class-level doc comments are missing. | Add concise class docblocks without changing behavior. | Step 35 |
| P2 | Generic | `Generic.Formatting.MultipleStatementAlignment.NotSameWarning` | Warning | 5 | `includes/class-report-builder.php` lines 40-45 | Assignment alignment in adjacent statements differs from the configured style. | Apply focused formatting cleanup later. | Step 36 |
| P2 | WordPress | `WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned` | Warning | 4 | `includes/class-openai-client.php` lines 32-35 | Array double arrows are not aligned to WPCS expectations. | Apply focused formatting cleanup later. | Step 36 |
| P2 | Universal | `Universal.Operators.DisallowStandalonePostIncrementDecrement.PostDecrementFound` | Warning | 3 | `includes/functions-utils.php` lines 323, 327, 330 | Stand-alone post-decrement is used where pre-decrement is preferred. | Replace with pre-decrement in a small behavior-equivalent cleanup step. | Step 37 |
| P3 | Generic | `Generic.CodeAnalysis.UnusedFunctionParameter.Found` | Warning | 1 | `includes/class-plugin.php` line 40 | Activation hook parameter `$network_wide` is declared but not used. | Review whether to remove, document, or use the parameter for multisite handling in a later step. | Step 38 |

## 9. P0/P1/P2/P3 Classification

Row-level counts:

| Priority | Count | Notes |
| --- | ---: | --- |
| P0 | 0 | No fatal or high-risk security issue was found by this WPCS baseline. |
| P1 | 16 | WPCS errors for class filenames and class doc comments should be resolved or intentionally configured before WordPress.org release readiness. |
| P2 | 12 | Formatting/operator warnings are acceptable for MVP verification but are straightforward cleanup candidates. |
| P3 | 1 | The unused activation-hook parameter is low risk and may be kept, removed, or documented depending on multisite policy. |

Grouped counts:

| Priority | Groups | Notes |
| --- | ---: | --- |
| P0 | 0 | None. |
| P1 | 2 | Filename convention and class docblocks. |
| P2 | 3 | Assignment alignment, array alignment, post-decrement. |
| P3 | 1 | Unused activation hook parameter. |

## 10. Auto-Fix Status

PHPCS reported 12 fixable violations:

- 5 assignment alignment warnings
- 4 array double-arrow alignment warnings
- 3 post-decrement warnings

`phpcbf` was intentionally not run in this step. These are recorded for later
focused remediation.

## 11. Intentionally Not Fixed

This step intentionally does not:

- rename PHP files
- update loader paths for renamed files
- add class docblocks
- apply whitespace/formatting changes
- replace post-decrement operators
- change activation behavior
- run `phpcbf`
- change production runtime behavior
- change readme or plugin metadata
- run GA4 or OpenAI API communication
- print credentials, API keys, Authorization headers, full request bodies, or
  full payload bodies

## 12. Packaging and Plugin Check Recheck

The Step 28 dry-run zip was regenerated successfully after adding Composer
tooling.

The release zip exclusion check returned no output for:

```text
vendor/|composer.json|composer.lock|phpcs.xml|phpcs.xml.dist|\.phpcs
```

This confirms the dry-run package did not include Composer tooling, `vendor/`, or
PHPCS ruleset files.

Plugin Check was rerun against the dry-run stage directory and remained clean:

```text
Success: チェックが完了しました。エラーは見つかりませんでした。
```

## 13. Recommended Next Steps

Suggested follow-up order:

1. Decide whether to conform class filenames to WPCS expectations or explicitly
   configure the ruleset around the plugin's current file naming.
2. Add class-level docblocks.
3. Apply narrow formatting cleanups for fixable WPCS warnings.
4. Review the activation hook `$network_wide` parameter and multisite policy.
5. Rerun PHPCS and Plugin Check after each focused cleanup.

## 14. Safety Notes

Only Composer package downloads for local PHPCS / WPCS tooling were performed.

No GA4 API request, OpenAI API request, SVN operation, GitHub release, or
WordPress.org publish operation was performed.

No credential value, API key value, token value, Authorization header value, full
request body, or full payload body is recorded in this document.
