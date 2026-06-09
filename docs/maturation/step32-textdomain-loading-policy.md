# Step 32 Textdomain Loading Policy

## Overview

This step reviews and fixes only the remaining Plugin Check warning after Step 31:

- `PluginCheck.CodeAnalysis.DiscouragedFunctions.load_plugin_textdomainFound`
- `includes/class-plugin.php`

The plugin is being prepared for WordPress.org-style distribution. In that
context, manual `load_plugin_textdomain()` loading is unnecessary when the plugin
has a matching `Text Domain` header and no bundled translation files.

## Current Text Domain State

The main plugin header keeps:

```text
Text Domain: analytics-report-ai
```

Translation function calls continue to use the `analytics-report-ai` text domain.
No translation strings or text domains were changed in this step.

## Translation File Inventory

No bundled translation directories were found:

```sh
find . -maxdepth 3 -type d \( -name languages -o -name lang \) -print
```

No bundled `.mo`, `.po`, or `.pot` files were found:

```sh
find . -maxdepth 4 \( -name '*.mo' -o -name '*.po' -o -name '*.pot' \) -print
```

Because there is no bundled translation directory or translation file, there is
no current plugin-local translation bundle that requires manual loading.

## Change Summary

Changed `includes/class-plugin.php` only:

- removed the `plugins_loaded` hook registration for `load_textdomain()`
- removed the `load_textdomain()` method
- removed the `load_plugin_textdomain()` call

The `init` hook for `boot()` remains unchanged.

## Plugin Check Recheck

After regenerating the Step 28 dry-run package and running Plugin Check against
the dry-run stage directory:

```text
Errors: 0
Warnings: 0
Info: 0
```

`PluginCheck.CodeAnalysis.DiscouragedFunctions.load_plugin_textdomainFound` no
longer appears.

## Not Changed

This step does not:

- change admin menu registration
- change Settings behavior
- change Report Builder behavior
- change enqueue logic
- change GA4 request logic
- change OpenAI generation logic
- change transient storage or payload validation
- change display strings
- change translation function text domains
- change `readme.txt`
- change plugin header metadata
- change `.distignore`
- change version or `Stable tag`
- run GA4 or OpenAI API communication
- print credentials, API keys, Authorization headers, full request bodies, or
  full payload bodies
