# Step 37 Post-Decrement Cleanup

## 1. Overview

This step fixes the WPCS warning group
`Universal.Operators.DisallowStandalonePostIncrementDecrement.PostDecrementFound`
that remained after Step 36.

Only three standalone post-decrement statements are changed to equivalent
standalone pre-decrement statements. Date comparison behavior, date clamping,
function signatures, return values, strings, numbers, variable names,
`phpcs.xml.dist`, `readme.txt`, plugin metadata, `.distignore`, and the dry-run
script are unchanged.

## 2. Target Finding

Step 36 left this P2 WPCS warning group:

| Sniff | Severity | Count | File |
| --- | --- | ---: | --- |
| `Universal.Operators.DisallowStandalonePostIncrementDecrement.PostDecrementFound` | Warning | 3 | `includes/functions-utils.php` |

The affected function was:

```text
analytics_report_ai_shift_date_with_clamp()
```

## 3. Target Statements

The three target statements were standalone statements:

```php
$month--;
$year--;
$year--;
```

They were not used inside assignments, function arguments, comparison
expressions, array indexes, or any other expression context.

## 4. Fix Policy

The replacement was behavior-equivalent because each decrement expression was a
standalone statement:

```php
--$month;
--$year;
--$year;
```

No date calculation rule was refactored. The previous-month and previous-year
comparison behavior remains the same.

`phpcbf` was not run.

## 5. WPCS Remeasurement

Commands:

```sh
vendor/bin/phpcs -ps
vendor/bin/phpcs --report=summary
vendor/bin/phpcs --report=source
```

Result after the post-decrement cleanup:

| Type | Count |
| --- | ---: |
| Errors | 0 |
| Warnings | 1 |
| Total violations | 1 |
| Files with violations | 1 |

`Universal.Operators.DisallowStandalonePostIncrementDecrement.PostDecrementFound`
no longer appears in the source summary.

## 6. Remaining WPCS Finding

| Priority | Sniff code | Count | Status |
| --- | --- | ---: | --- |
| P3 | `Generic.CodeAnalysis.UnusedFunctionParameter.Found` | 1 | deferred to Step 38 |

Updated row-level classification:

| Priority | Count | Notes |
| --- | ---: | --- |
| P0 | 0 | No P0 issue is introduced by this operator-only change. |
| P1 | 0 | No WPCS errors remain. |
| P2 | 0 | The remaining P2 post-decrement warnings are resolved. |
| P3 | 1 | Only the unused activation-hook parameter remains. |

## 7. Intentionally Not Changed

This step intentionally does not:

- change date calculation or comparison-period behavior
- refactor `analytics_report_ai_shift_date_with_clamp()`
- change function signatures, return values, strings, numbers, or variable names
- address the unused activation hook parameter
- change `phpcs.xml.dist`
- change `readme.txt`
- change plugin header metadata
- change `.distignore`
- change the dry-run script
- run `phpcbf`
- run Composer install or update
- run GA4 or OpenAI API communication
- print credentials, API keys, Authorization headers, full request bodies, or
  full payload bodies
