# Step 31 POST Input Static Analysis

## Overview

This step addresses only the Plugin Check POST nonce and sanitization warnings
remaining after Step 30.

Target warnings from `includes/class-report-builder.php`:

- `WordPress.Security.NonceVerification.Missing` x3
- `WordPress.Security.ValidatedSanitizedInput.InputNotSanitized` x1

No `load_plugin_textdomain()` warning is fixed in this step.

## POST Flow Confirmed

The Report Builder form submits:

- `analytics_report_ai_report_action`
- `analytics_report_ai_report_builder_nonce`
- `analytics_report_ai_report[...]` condition fields

`maybe_handle_request()` performs the request gate:

1. It returns early when no report action is posted.
2. It sanitizes the posted action with `sanitize_key()`.
3. It checks `current_user_can( 'manage_options' )`.
4. It reads and sanitizes the nonce field.
5. It verifies the nonce with `wp_verify_nonce()`.
6. It dispatches to `handle_fetch_ga4_summary()` only after the capability and
   nonce checks pass.

If nonce verification fails, the fetch handler is not reached.

## Sanitization and Validation

The report condition array is now read in the verified dispatcher path, sanitized
with `map_deep( wp_unslash( ... ), 'sanitize_text_field' )`, normalized to the
expected scalar fields, and then passed to `handle_fetch_ga4_summary()`.

`handle_fetch_ga4_summary()` no longer reads `$_POST` directly. It receives the
sanitized condition values and passes them into the existing
`validate_report_conditions()` flow.

The existing validation behavior remains responsible for date validity, date
range order, maximum day count, comparison mode, scope, and path normalization.

Raw POST input is not saved, displayed, stored in transients, sent to GA4, or sent
to OpenAI directly.

## Change Summary

Changed `includes/class-report-builder.php` only:

- moved report input retrieval into the nonce-verified dispatcher branch
- added `normalize_report_input()` for expected scalar condition fields
- changed `handle_fetch_ga4_summary()` to accept sanitized form values

No action name, nonce name, form field name, UI flow, GA4 condition semantics,
transient handling, payload validation, or OpenAI generation behavior was changed.

No `phpcs:ignore` comment was added.

## Plugin Check Recheck

After regenerating the Step 28 dry-run package and running Plugin Check against
the dry-run stage directory:

```text
Errors: 0
Warnings: 1
Info: 0
```

The POST nonce and sanitization warnings no longer appear.

## Remaining Warning

The only remaining Plugin Check warning is intentionally not fixed in this step:

- `PluginCheck.CodeAnalysis.DiscouragedFunctions.load_plugin_textdomainFound`
  in `includes/class-plugin.php`

This remains the Step 29 / Step 30 P2 follow-up item.

## Not Changed

This step does not:

- change `load_plugin_textdomain()` usage
- change Report Builder UI flow
- change GA4 request logic
- change transient storage or payload validation
- change OpenAI generation logic
- change `readme.txt`
- change plugin header metadata
- change `.distignore`
- change the dry-run script
- run GA4 or OpenAI API communication
- print credentials, API keys, Authorization headers, full request bodies, or
  full payload bodies
