# Step 29 Plugin Check Measured Baseline

## 1. Overview

This step installs and runs Plugin Check in the local WordPress verification
environment, then records a measured baseline for Analytics Report AI.

This is a measurement and classification step only. No Plugin Check findings are
fixed here.

No production PHP, JavaScript, CSS, `readme.txt`, plugin header metadata,
`.distignore`, or dry-run script behavior is changed in this step. No GA4,
OpenAI, SVN, GitHub release, WordPress.org publish, or other external service
operation is performed, except the allowed WordPress.org plugin-directory
download for `plugin-check`.

## 2. Environment

- Plugin source repository: `/var/www/html/analytics-report-ai`
- WordPress verification environment: `/var/www/html/wp-dev`
- WP-CLI version: `2.12.0`
- PHP CLI version reported by WP-CLI: `8.1.2-1ubuntu2.24`
- Plugin Check plugin version: `2.0.0`
- Plugin Check status: active in `/var/www/html/wp-dev`

## 3. Plugin Check Installation Status

Plugin Check was not available before installation:

```text
wp --path=/var/www/html/wp-dev help plugin check
Error: 'check' is not a registered subcommand of 'plugin'.
```

The plugin was installed and activated in the local WordPress verification
environment only:

```sh
wp --path=/var/www/html/wp-dev plugin install plugin-check --activate
```

Installation result:

```text
Installing Plugin Check (PCP) (2.0.0)
Plugin 'plugin-check' activated.
Success: Installed 1 of 1 plugins.
```

The Analytics Report AI source repository does not include the Plugin Check
plugin.

## 4. Target Artifact

The Step 28 dry-run script was run before measurement:

```sh
bash -n tools/build-release-zip-dry-run.sh
./tools/build-release-zip-dry-run.sh
```

Dry-run output:

```text
build/release-dry-run/analytics-report-ai-0.1.0.zip
build/release-dry-run/stage/analytics-report-ai/
```

The dry-run script passed its own package checks:

- zip root is `analytics-report-ai/`
- runtime files are included
- development files are excluded
- plugin header `Version` and readme `Stable tag` both resolve to `0.1.0`
- `readme.txt` contains `== External Services ==`
- staged PHP syntax checks pass
- no high-risk credential pattern was detected

## 5. Commands Attempted

The dry-run zip was tried first:

```sh
wp --path=/var/www/html/wp-dev plugin check /var/www/html/analytics-report-ai/build/release-dry-run/analytics-report-ai-0.1.0.zip
```

Result:

```text
Error: Invalid plugin slug: Plugin with slug /var/www/html/analytics-report-ai/build/release-dry-run/analytics-report-ai-0.1.0.zip is not installed.
```

Plugin Check 2.0.0 WP-CLI treated the zip path as a plugin slug, so the zip file
itself could not be used as the measured target.

The source checkout path was then run as requested:

```sh
wp --path=/var/www/html/wp-dev plugin check /var/www/html/analytics-report-ai
```

That command completed, but it scanned local dry-run output and tooling that are
not part of the release package. It reported 6 errors and 12 warnings, including
expected source-only findings such as the dry-run zip being a compressed file,
the shell script being an application file, and hidden tooling files.

For the measured distribution baseline below, the dry-run stage directory was
used because it mirrors the contents of the dry-run zip after `.distignore`
exclusions:

```sh
wp --path=/var/www/html/wp-dev plugin check /var/www/html/analytics-report-ai/build/release-dry-run/stage/analytics-report-ai
```

## 6. Static Check Result Summary

Measured target:

```text
/var/www/html/analytics-report-ai/build/release-dry-run/stage/analytics-report-ai
```

Result counts:

| Type | Count |
| --- | ---: |
| Error | 2 |
| Warning | 5 |
| Info | 0 |

The measured baseline is static analysis only. No GA4 or OpenAI action was
triggered.

## 7. Runtime Check Status

Runtime checks were not run separately in this step.

The installed WP-CLI help for `wp plugin check` exposes check selection, output
format, severity, mode, and optional AI analysis, but no simple runtime smoke
flag was used here. Because this step is limited to Plugin Check setup and a
measured static baseline, no extra bootstrap, admin workflow, external API smoke
test, or credential validation was attempted.

The `--ai` option was not used.

## 8. Findings Table

| Priority | Tool | Severity | Check code | File | Line | Summary | Recommended action | Suggested step |
| --- | --- | --- | --- | --- | ---: | --- | --- | --- |
| P1 | Plugin Check | ERROR | `WordPress.WP.I18n.MissingTranslatorsComment` | `includes/class-report-builder.php` | 163 | Placeholder string passed to `esc_html__()` lacks a preceding `translators:` comment. | Add a translator comment explaining the `%d` report-day placeholder. | Step 30 |
| P1 | Plugin Check | ERROR | `WordPress.WP.I18n.MissingTranslatorsComment` | `includes/class-report-builder.php` | 862 | Placeholder string passed to `esc_html__()` lacks a preceding `translators:` comment. | Add a translator comment explaining the `%d` minutes placeholder. | Step 30 |
| P3 | Plugin Check | WARNING | `WordPress.Security.NonceVerification.Missing` | `includes/class-report-builder.php` | 343 | `$_POST['analytics_report_ai_report']` is read inside `handle_fetch_ga4_summary()`. | Review as likely local false positive because nonce verification occurs before this handler is called; consider a small structure/comment change if needed for tooling. | Step 31 |
| P3 | Plugin Check | WARNING | `WordPress.Security.NonceVerification.Missing` | `includes/class-report-builder.php` | 343 | Second access on the same conditional line is also flagged. | Same as above. | Step 31 |
| P3 | Plugin Check | WARNING | `WordPress.Security.NonceVerification.Missing` | `includes/class-report-builder.php` | 344 | `$_POST['analytics_report_ai_report']` is unslashed after the earlier nonce check in `maybe_handle_request()`. | Same as above. | Step 31 |
| P3 | Plugin Check | WARNING | `WordPress.Security.ValidatedSanitizedInput.InputNotSanitized` | `includes/class-report-builder.php` | 344 | The raw unslashed report array is assigned before field-level sanitization. | Review as likely local false positive because individual fields are sanitized immediately after assignment; consider refactoring if needed for tooling clarity. | Step 31 |
| P2 | Plugin Check | WARNING | `PluginCheck.CodeAnalysis.DiscouragedFunctions.load_plugin_textdomainFound` | `includes/class-plugin.php` | 78 | `load_plugin_textdomain()` is discouraged for WordPress.org-hosted plugins since WordPress 4.6 loads translations automatically. | Revisit textdomain loading strategy before WordPress.org publication. | Step 32 |

## 9. P0/P1/P2/P3 Classification

Row-level counts:

| Priority | Count | Notes |
| --- | ---: | --- |
| P0 | 0 | No fatal or immediate high-risk issue was found in the measured stage baseline. |
| P1 | 2 | Translator comments should be fixed before WordPress.org publication. |
| P2 | 1 | `load_plugin_textdomain()` warning is acceptable for MVP verification but should be revisited before public release. |
| P3 | 4 | Nonce/sanitization warnings appear to be local static-analysis limitations because nonce verification happens before handler dispatch and fields are sanitized after unslashing. |

Grouped issue counts:

| Priority | Groups | Notes |
| --- | ---: | --- |
| P0 | 0 | None. |
| P1 | 1 | Missing translator comments for placeholder strings. |
| P2 | 1 | Discouraged textdomain loading call. |
| P3 | 1 | POST nonce/sanitization warnings in a pre-verified handler flow. |

## 10. Recommended Remediation Order

1. Add translator comments for the two placeholder strings.
2. Review whether the POST handling can be made clearer to Plugin Check without
   changing the Report Builder flow.
3. Revisit `load_plugin_textdomain()` before WordPress.org publication.
4. Re-run the dry-run package build and Plugin Check baseline after each focused
   remediation step.

## 11. Intentionally Not Fixed In This Step

This step intentionally does not fix:

- i18n translator comments
- nonce/static-analysis warnings
- input sanitization/static-analysis warnings
- `load_plugin_textdomain()` usage
- packaging-tool findings from checking the full source checkout
- any production PHP, JavaScript, CSS, `readme.txt`, metadata, `.distignore`, or
  dry-run script behavior

## 12. Next Steps

Suggested follow-up steps:

- Step 30: i18n translator-comment cleanup for the P1 findings.
- Step 31: focused review of the POST nonce/sanitization static-analysis findings.
- Step 32: WordPress.org translation-loading policy decision.
- Future release step: re-run Plugin Check against the dry-run stage or the
  formal package artifact after remediation.

## 13. Safety Notes

No GA4 API request, OpenAI API request, credential validation, SVN operation,
GitHub release, or WordPress.org publish operation was performed.

No credential value, API key value, token value, Authorization header value, full
request body, or full payload body is recorded in this document.
