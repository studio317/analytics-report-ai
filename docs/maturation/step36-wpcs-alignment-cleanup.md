# Step 36 WPCS Alignment Cleanup

## 1. Overview

This step fixes the WPCS formatting alignment warnings that remained after Step
35.

Only whitespace alignment is changed. Production logic, conditionals, function
calls, array keys, array values, strings, numbers, variable names, method
signatures, properties, class names, file names, loader paths, UI copy,
translation strings, `phpcs.xml.dist`, `readme.txt`, plugin metadata,
`.distignore`, and the dry-run script are unchanged.

## 2. Target Findings

Step 35 left these alignment warning groups:

| Sniff | Severity | Count | File |
| --- | --- | ---: | --- |
| `Generic.Formatting.MultipleStatementAlignment.NotSameWarning` | Warning | 5 | `includes/class-report-builder.php` |
| `WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned` | Warning | 4 | `includes/class-openai-client.php` |

These nine warnings were the only target of this step.

## 3. Fix Policy

The cleanup was applied manually. `phpcbf` was not run.

Changes:

- aligned adjacent assignment operators in
  `Analytics_Report_AI_Report_Builder::render_page()`
- aligned `=>` spacing in the OpenAI request body array in
  `Analytics_Report_AI_OpenAI_Client::generate_report()`

No request body keys, request body values, headers, payload construction, or API
flow were changed.

## 4. WPCS Remeasurement

Commands:

```sh
vendor/bin/phpcs -ps
vendor/bin/phpcs --report=summary
vendor/bin/phpcs --report=source
```

Result after the alignment cleanup:

| Type | Count |
| --- | ---: |
| Errors | 0 |
| Warnings | 4 |
| Total violations | 4 |
| Files with violations | 2 |
| PHPCBF-fixable violations | 3 |

The following source groups no longer appear:

- `Generic.Formatting.MultipleStatementAlignment.NotSameWarning`
- `WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned`

## 5. Remaining WPCS Findings

| Priority | Sniff code | Count | Status |
| --- | --- | ---: | --- |
| P2 | `Universal.Operators.DisallowStandalonePostIncrementDecrement.PostDecrementFound` | 3 | deferred |
| P3 | `Generic.CodeAnalysis.UnusedFunctionParameter.Found` | 1 | deferred |

Updated row-level classification:

| Priority | Count | Notes |
| --- | ---: | --- |
| P0 | 0 | No P0 issue is introduced by this whitespace-only change. |
| P1 | 0 | No WPCS errors remain. |
| P2 | 3 | Only the post-decrement warnings remain. |
| P3 | 1 | The unused activation-hook parameter remains unchanged. |

## 6. Intentionally Not Changed

This step intentionally does not:

- change production PHP logic
- change API request body structure, headers, payload structure, model
  selection, or response handling
- change method bodies beyond whitespace alignment
- change method signatures, properties, class names, file names, or loader paths
- change UI copy or translation strings
- change `phpcs.xml.dist`
- change `readme.txt`
- change plugin header metadata
- change `.distignore`
- change the dry-run script
- fix post-decrement warnings
- address the unused activation hook parameter
- run `phpcbf`
- run Composer install or update
- run GA4 or OpenAI API communication
- print credentials, API keys, Authorization headers, full request bodies, or
  full payload bodies
