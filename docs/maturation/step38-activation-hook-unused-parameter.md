# Step 38 Activation Hook Unused Parameter

## 1. Overview

This step fixes the final WPCS warning that remained after Step 37:
`Generic.CodeAnalysis.UnusedFunctionParameter.Found`.

The target is the `$network_wide` parameter on the plugin activation hook
callback. The callback signature is kept for WordPress compatibility, and the
parameter is explicitly marked as unused for the MVP.

No multisite activation behavior is added. Production option initialization,
settings behavior, class names, file names, loader paths, `phpcs.xml.dist`,
`readme.txt`, plugin metadata, `.distignore`, and the dry-run script are
unchanged.

## 2. Target Finding

Step 37 left this P3 WPCS warning:

| Sniff | Severity | Count | File |
| --- | --- | ---: | --- |
| `Generic.CodeAnalysis.UnusedFunctionParameter.Found` | Warning | 1 | `includes/class-plugin.php` |

Target method and parameter:

```php
Analytics_Report_AI_Plugin::activate( $network_wide = false )
```

The method is registered from the main plugin file:

```php
register_activation_hook( ANALYTICS_REPORT_AI_FILE, array( 'Analytics_Report_AI_Plugin', 'activate' ) );
```

## 3. MVP Multisite Policy

The MVP does not formally support multisite network activation.

This step does not add:

- network-wide site iteration
- per-site option initialization
- multisite activation branching
- new settings storage behavior

The activation hook callback parameter remains in place because WordPress may
provide it to activation callbacks.

## 4. Fix Policy

The warning is resolved by explicitly unsetting the unused parameter near the
start of the activation callback:

```php
/*
 * Multisite network activation is outside the MVP support scope.
 * Keep the parameter for the WordPress activation hook signature.
 */
unset( $network_wide );
```

This keeps the callback signature stable while avoiding a `phpcs:ignore`
exception. The existing default settings option initialization remains
unchanged.

`phpcbf` was not run.

## 5. WPCS Remeasurement

Commands:

```sh
vendor/bin/phpcs -ps
vendor/bin/phpcs --report=summary
vendor/bin/phpcs --report=source
```

Result after the cleanup:

| Type | Count |
| --- | ---: |
| Errors | 0 |
| Warnings | 0 |
| Total violations | 0 |

Both `vendor/bin/phpcs --report=summary` and
`vendor/bin/phpcs --report=source` returned no violation output.

## 6. Plugin Check Recheck

The dry-run release stage was regenerated and checked with:

```sh
wp --path=/var/www/html/wp-dev plugin check /var/www/html/analytics-report-ai/build/release-dry-run/stage/analytics-report-ai
```

Plugin Check remained clean.

## 7. Intentionally Not Changed

This step intentionally does not:

- add multisite activation support
- change activation option initialization
- change settings storage behavior
- change method signatures beyond keeping the existing activation callback
  signature
- change class names, file names, or loader paths
- change UI copy or translation strings
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
