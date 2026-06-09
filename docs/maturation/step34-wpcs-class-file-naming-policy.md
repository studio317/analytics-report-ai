# Step 34 WPCS Class File Naming Policy

## 1. Overview

This step decides how to handle the Step 33 WPCS finding
`WordPress.Files.FileName.InvalidClassFileName`.

The decision is to keep the current role-based class file names and add a
targeted ruleset exclusion for the existing `includes/class-*.php` files.

No PHP class files are renamed. No loader, `require_once`, production runtime
logic, UI copy, `readme.txt`, plugin metadata, `.distignore`, or dry-run script
behavior is changed.

## 2. Step 33 Finding

Step 33 measured this P1 WPCS error group:

| Sniff | Severity | Count | Scope |
| --- | --- | ---: | --- |
| `WordPress.Files.FileName.InvalidClassFileName` | Error | 8 | all class files in `includes/class-*.php` |

The affected files were:

- `includes/class-admin.php`
- `includes/class-ga4-client.php`
- `includes/class-openai-client.php`
- `includes/class-plugin.php`
- `includes/class-prompt-builder.php`
- `includes/class-report-builder.php`
- `includes/class-report-data-formatter.php`
- `includes/class-settings.php`

WPCS expects class file names derived more directly from class names. For
example, `Analytics_Report_AI_Report_Builder` can lead to a longer expected file
name than the project's current `includes/class-report-builder.php`.

## 3. Current Project Convention

The current project convention is:

```text
includes/class-{role}.php
```

This keeps class file names short and role-focused:

- `class-admin.php`
- `class-settings.php`
- `class-report-builder.php`
- `class-report-data-formatter.php`

This convention is already consistent across the MVP class files. It is also a
common practical convention in WordPress plugin codebases.

## 4. Rename Option

Potential benefits:

- removes the WPCS filename finding without a ruleset exception
- makes filenames closer to the full class names

Potential drawbacks:

- requires renaming eight production PHP files
- requires updating loader / `require_once` paths
- increases the risk of accidental include-path regressions
- makes the Step 34 change broader than the filename policy decision itself
- adds history churn for files that already have consistent project-local names

Because there is no runtime behavior issue and no packaging issue from the
current names, the rename option is deferred.

## 5. Ruleset Exclusion Option

Potential benefits:

- keeps the existing project convention intact
- avoids loader and require-path changes
- removes only the known filename-convention mismatch from the baseline
- preserves WPCS coverage for the rest of the codebase

Potential drawbacks:

- records a project-local exception to a WPCS convention
- future class files using this pattern will also be excluded if they match
  `includes/class-*.php`

This option is appropriate for the current MVP because the short class file
naming is intentional and consistent.

## 6. Adopted Policy

For the current MVP and pre-release maturation work:

- keep role-based class file names under `includes/class-*.php`
- do not rename existing class files in Step 34
- do not update loader or `require_once` paths in Step 34
- exclude only `WordPress.Files.FileName.InvalidClassFileName` for
  `includes/class-*.php`
- keep other WPCS findings visible for later focused steps

If a future architecture pass introduces a different autoloading strategy or a
broader file organization change, this policy can be revisited.

## 7. Ruleset Change

`phpcs.xml.dist` now includes this targeted exclusion:

```xml
<!--
	The project intentionally uses short role-based class file names such as
	includes/class-report-builder.php. Renaming them would broaden the change to
	loader paths without improving runtime behavior for the current MVP.
-->
<rule ref="WordPress.Files.FileName.InvalidClassFileName">
	<exclude-pattern>includes/class-*.php</exclude-pattern>
</rule>
```

The exclusion is intentionally limited to the single sniff
`WordPress.Files.FileName.InvalidClassFileName`. It does not exclude the broader
`WordPress.Files.FileName` category.

## 8. WPCS Remeasurement

Commands:

```sh
vendor/bin/phpcs -ps
vendor/bin/phpcs --report=summary
vendor/bin/phpcs --report=source
```

Result after the targeted exclusion:

| Type | Count |
| --- | ---: |
| Errors | 8 |
| Warnings | 13 |
| Total violations | 21 |
| Files with violations | 9 |
| PHPCBF-fixable violations | 12 |

`WordPress.Files.FileName.InvalidClassFileName` no longer appears in the source
summary.

## 9. Remaining WPCS Findings

| Priority | Sniff code | Count | Status |
| --- | --- | ---: | --- |
| P1 | `Squiz.Commenting.ClassComment.Missing` | 8 | deferred to Step 35 |
| P2 | `Generic.Formatting.MultipleStatementAlignment.NotSameWarning` | 5 | deferred |
| P2 | `WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned` | 4 | deferred |
| P2 | `Universal.Operators.DisallowStandalonePostIncrementDecrement.PostDecrementFound` | 3 | deferred |
| P3 | `Generic.CodeAnalysis.UnusedFunctionParameter.Found` | 1 | deferred |

Updated row-level classification:

| Priority | Count | Notes |
| --- | ---: | --- |
| P0 | 0 | No P0 WPCS issue is introduced by this policy. |
| P1 | 8 | Only the class docblock errors remain in P1. |
| P2 | 12 | Formatting and operator warnings remain unchanged. |
| P3 | 1 | The unused activation-hook parameter remains unchanged. |

## 10. Intentionally Not Changed

This step intentionally does not:

- rename PHP class files
- update loader or `require_once` paths
- add class docblocks
- fix alignment warnings
- replace post-decrement operators
- address the unused activation hook parameter
- run `phpcbf`
- change production PHP logic
- change UI copy
- change `readme.txt`
- change plugin header metadata
- change `.distignore`
- change the dry-run script
- run GA4 or OpenAI API communication
- print credentials, API keys, Authorization headers, full request bodies, or
  full payload bodies
