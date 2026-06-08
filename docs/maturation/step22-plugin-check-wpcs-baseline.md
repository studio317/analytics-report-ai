# Step 22 Plugin Check / WPCS Baseline

## 1. Overview

This step records the current Plugin Check / PHPCS / WPCS baseline before broader WordPress.org readiness remediation.

This is a baseline audit only. No production PHP, JavaScript, CSS, package, Composer, or formatting changes were made. No external API communication was performed, and no credential values, Authorization headers, request bodies, or API keys were printed or recorded.

## 2. Environment and Available Tools

Working directory:

- `/var/www/html/analytics-report-ai`

WP-CLI environment:

- WP-CLI version: `2.12.0`
- PHP binary: `/usr/bin/php8.1`
- PHP version: `8.1.2-1ubuntu2.24`
- MySQL client: `8.0.46`

Tool availability:

| Tool | Status | Evidence |
| --- | --- | --- |
| WP-CLI | Available | `wp --info` succeeded. |
| Plugin Check | Not available in this WordPress install | `wp --path=/var/www/html/wp-dev plugin list --field=name` listed `akismet`, `cuerda-feed-total`, and `object-cache.php`; `plugin-check` was not listed. |
| `wp plugin check` command | Not registered | `wp --path=/var/www/html/wp-dev help plugin check` returned `Error: 'check' is not a registered subcommand of 'plugin'.` |
| PHPCS | Not available | `phpcs --version` returned `phpcs: command not found`. |
| WPCS standards | Not available | `phpcs -i` also returned `phpcs: command not found`, so installed standards could not be inspected. |
| Composer | Available for inspection only | `composer --version` returned `Composer 2.2.6`. No install/update command was run. |
| `vendor/` / `node_modules/` | Not present in the plugin checkout | `find . -maxdepth 3 -type d -name vendor -o -name node_modules` returned no paths. |

## 3. Commands Attempted

Syntax and repository checks:

```bash
git status --short --untracked-files=all
php -l analytics-report-ai.php
find includes -name '*.php' -print0 | xargs -0 -n1 php -l
```

Tool discovery:

```bash
wp --info
wp --path=/var/www/html/wp-dev plugin list --field=name
wp --path=/var/www/html/wp-dev help plugin check
phpcs --version
phpcs -i
composer --version
find . -maxdepth 3 -type d -name vendor -o -name node_modules
```

Manual static scan helpers:

```bash
rg -n "Text Domain|Plugin Name|Version|Description|License|load_plugin_textdomain|wp_enqueue|wp_localize_script|wp_remote_post|wp_remote_get|Authorization|Bearer|current_user_can|wp_nonce|check_admin_referer|sanitize_|esc_html|esc_attr|__\(|_e\(|esc_html__|esc_attr__" analytics-report-ai.php includes assets readme.txt docs/maturation
rg -n "if \( ! defined\( 'ABSPATH' \) \)|exit;|defined\( 'ABSPATH' \)" analytics-report-ai.php includes
rg -n "TODO|FIXME|dummy|mock|implemented later|will be implemented later|console\.log|var_dump|print_r|error_log|access_token|openai_api_key|request_body|Authorization|Bearer" analytics-report-ai.php includes assets readme.txt docs/maturation
```

Final diff checks:

```bash
git diff --stat
git diff --name-only
git diff --check
```

## 4. Plugin Check Baseline

Plugin Check was not executed because it is not installed in the local WordPress environment and the WP-CLI command is not registered.

Observed command results:

- `wp --path=/var/www/html/wp-dev plugin list --field=name`
  - Installed/listed plugin names: `akismet`, `cuerda-feed-total`, `object-cache.php`
  - `plugin-check` was not present.
- `wp --path=/var/www/html/wp-dev help plugin check`
  - Failed with: `Error: 'check' is not a registered subcommand of 'plugin'.`

Baseline result:

- Plugin Check errors: not measured.
- Plugin Check warnings: not measured.
- Runtime checks: not executed.
- Static checks: not executed.

No attempt was made to install Plugin Check.

## 5. PHPCS / WPCS Baseline

PHPCS and WPCS were not executed because `phpcs` is not available in the local shell.

Observed command results:

- `phpcs --version`
  - Failed with: `phpcs: command not found`.
- `phpcs -i`
  - Failed with: `phpcs: command not found`.

Baseline result:

- PHPCS errors: not measured.
- PHPCS warnings: not measured.
- WPCS standards: not measured.
- WordPress / WordPress-Core / WordPress-Extra / WordPress-Docs availability: not measured.

No Composer package, global tool, or coding standard was installed.

## 6. Findings Summary

Manual scans were used only to classify obvious WordPress.org readiness work while formal Plugin Check and WPCS were unavailable.

Positive baseline observations:

- Every PHP entry file checked has a direct access guard using `if ( ! defined( 'ABSPATH' ) ) { exit; }`.
- Admin screens check `current_user_can( 'manage_options' )`.
- Report Builder POST handling verifies a nonce before dispatching actions.
- Settings uses the WordPress Settings API with a sanitize callback.
- Output is broadly escaped with `esc_html()`, `esc_attr()`, `esc_html__()`, `esc_attr__()`, and `esc_textarea()`.
- Admin JavaScript strings are passed through `wp_localize_script()` with the object name `analyticsReportAiAdmin`.
- Remote requests are limited to the GA4 Data API and OpenAI Responses API clients.
- UI and maturation docs include external service and credential storage explanations.
- No `vendor/` or `node_modules/` directory was present in the plugin checkout.

Measured syntax result:

- `php -l analytics-report-ai.php`: pass.
- `find includes -name '*.php' -print0 | xargs -0 -n1 php -l`: all includes files passed.

## 7. Classification

| ID | Priority | Tool | Severity | Code/sniff | File | Line | Summary | Recommended action | Suggested step |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PC-001 | P1 | Environment | Blocked | Plugin Check unavailable | n/a | n/a | Plugin Check could not be run because `plugin-check` is not installed and `wp plugin check` is not registered. | Install or make Plugin Check available in a separate readiness environment, then rerun static and runtime checks. | Step 23 |
| WPCS-001 | P1 | Environment | Blocked | PHPCS unavailable | n/a | n/a | PHPCS/WPCS could not be run because `phpcs` is not installed. | Add a controlled PHPCS/WPCS toolchain or run in an environment where WPCS is already installed. Do not auto-fix in the baseline step. | Step 24 |
| READ-001 | P1 | Manual static scan | WordPress.org readiness | External service disclosure | `readme.txt` | 13 | The readme description is still minimal and does not disclose Google Analytics Data API or OpenAI API usage, data sent, service URLs, terms, or privacy policy links. | Draft WordPress.org-style external service disclosure in `readme.txt`. Align with existing Settings and Report Builder disclosures. | Step 25 |
| READ-002 | P1 | Manual static scan | WordPress.org readiness | Readme metadata/content | `readme.txt` | 5, 21 | `Tested up to` must be verified before submission, and the changelog still says `Initial plugin skeleton` even though the MVP now includes GA4 fetch, payload preview, OpenAI generation, and maturity hardening. | Update readme metadata and changelog once target WordPress version and release scope are confirmed. | Step 26 |
| AUTH-001 | P1 | Manual static scan | Public-use readiness | Credential/OAuth model | `includes/class-settings.php` | 191 | The UI correctly labels credential storage as MVP, but public use still depends on manual Google Access Token entry and database-stored credentials. | Keep blocked from broad/public release until OAuth, expiry/scope handling, revocation UX, and a public credential storage strategy are designed. | Step 27 |
| REMOTE-001 | P2 | Manual static scan | Disclosure alignment | Remote requests | `includes/class-ga4-client.php`, `includes/class-openai-client.php` | 324, 38 | Remote request code is clear and UI disclosure exists, but readme/package-level disclosure is not yet aligned. | After Plugin Check is available, confirm whether external service checks require readme text changes beyond the UI copy. | Step 25 |
| PKG-001 | P2 | Manual static scan | Packaging | Development docs in package | `docs/maturation/*` | n/a | Maturation docs are useful for development but may be noisy or unsuitable for a public plugin package if included directly. | Decide whether to exclude `docs/maturation/` from the distribution artifact or keep it only in source control. | Step 26 |
| I18N-001 | P3 | Manual static scan | Potential false positive | `load_plugin_textdomain()` | `includes/class-plugin.php` | 78 | Some checkers may warn about translation-loading patterns depending on WordPress version and standards configuration. No formal tool result is available yet. | Reassess only after Plugin Check/WPCS is available. Avoid changing this in the baseline step. | Step 24 |
| JS-001 | P3 | Manual static scan | Intentional fallback | JS fallback strings | `assets/js/admin.js` | n/a | Step 19 localized admin strings via PHP but left minimal English fallback strings so JS does not fail if the object is missing. | Keep unless a later i18n pass chooses a different fallback policy. | Future i18n pass |
| DOC-001 | P3 | Manual static scan | Historical docs | Past audit records | `docs/maturation/step12-mvp-code-audit.md` | 292, 360, 372 | Older maturity docs intentionally preserve historical findings such as stale copy and dummy fixture cleanup debt that has since been addressed. | Keep as historical record unless packaging policy excludes maturity docs. | Step 26 |

Classification counts:

- P0: 0
- P1: 5
- P2: 2
- P3: 3

No formal Plugin Check or PHPCS issue count is available in this environment.

## 8. Recommended Fix Order

1. Step 23: Make Plugin Check available in a controlled environment and run a real Plugin Check baseline against the plugin.
2. Step 24: Make PHPCS/WPCS available and run WordPress / WordPress-Extra checks without auto-fixing.
3. Step 25: Draft `readme.txt` external service disclosures for Google Analytics Data API and OpenAI API, including what data is sent and links to service terms/privacy policies.
4. Step 26: Align plugin header/readme metadata, changelog, tested WordPress version, and packaging policy for `docs/maturation/`.
5. Step 27: Revisit public-use credential and OAuth readiness before broad or multi-user distribution.

## 9. Not Implemented in This Step

This step intentionally did not:

- install Plugin Check,
- install PHPCS,
- install WordPress Coding Standards,
- run `phpcbf`,
- add Composer dependencies,
- add a `vendor/` directory,
- modify production PHP/JS/CSS,
- change plugin headers,
- change `readme.txt`,
- make GA4 API calls,
- make OpenAI API calls,
- print credentials, Authorization headers, full request bodies, or full payload bodies.

## 10. Next Steps

The next useful step is to run the same audit in an environment with Plugin Check and WPCS available. Until then, the highest-confidence remediation work is documentation and packaging readiness, especially readme-level external service disclosure and release metadata alignment.

Once tooling is available, use its exact error/warning codes to replace the environment-blocked baseline with a measured baseline and then perform focused remediation in small steps.
