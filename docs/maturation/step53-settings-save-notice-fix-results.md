# Step 53: Settings Save Notice Fix Results

## Purpose

This step investigates and fixes the Step 52 Settings save smoke failure:

- Save completion notice was not displayed after clicking Save Settings.

Step 52 also showed that non-credential values were retained after reload, the
Settings screen could be reloaded, Report Builder still opened, JavaScript
console checks passed, and credentials were not exposed. Therefore this step is
limited to the Settings save UI feedback / admin notice display path.

## Step 52 Failure

| Area | Result |
|---|---|
| Failing item | Save completion notice was not displayed after clicking Save Settings. |
| Persistence signal | Non-credential values were retained after reload. |
| Preliminary classification | Likely UI feedback / admin notice issue, not necessarily a persistence failure. |
| Scope for this fix | Settings admin notice rendering only. |

## Investigation Result

The Settings screen uses the WordPress Settings API. The form submits to
`options.php`, and WordPress redirects back with `settings-updated=true` after
a successful save.

The Settings page rendered notices with:

```php
settings_errors( ANALYTICS_REPORT_AI_OPTION_NAME );
```

That displayed option-specific validation messages added with
`add_settings_error( ANALYTICS_REPORT_AI_OPTION_NAME, ... )`, but it filtered
out the standard Settings API success notice.

WordPress creates the default success notice as the general
`settings_updated` notice when `settings-updated=true` is present. Because the
page asked only for messages matching `ANALYTICS_REPORT_AI_OPTION_NAME`, the
general success notice was hidden.

## Fix Approach

The fix changes the Settings page notice rendering from option-specific
filtering to the standard unfiltered Settings API notice display:

```php
settings_errors();
```

This lets the page show:

- The standard Settings API success notice after successful saves.
- Existing plugin-specific validation messages from
  `ANALYTICS_REPORT_AI_OPTION_NAME`.

No custom redirect, custom query arg, custom success message, credential logic,
GA4 logic, OpenAI logic, or Report Builder flow was added.

## Changed Files

| File | Change |
|---|---|
| `includes/class-settings.php` | Changed Settings notice rendering from `settings_errors( ANALYTICS_REPORT_AI_OPTION_NAME )` to `settings_errors()`. |
| `docs/maturation/step53-settings-save-notice-fix-results.md` | Added this investigation, fix, verification, and manual recheck record. |

## Change Summary

- Root cause: the Settings page filtered notices by the plugin option name and
  hid WordPress' general `settings_updated` success notice.
- Runtime fix: use the standard `settings_errors()` call so the success notice
  can render after `settings-updated=true`.
- Validation messages remain available because the unfiltered call also
  includes plugin-specific `add_settings_error()` messages.
- The displayed success message is provided by WordPress core.
- No credential save, mask, clear, redisplay, GA4 request, OpenAI request, AI
  payload, transient, or Report Builder behavior was changed.

## Checks Performed

| Check | Result |
|---|---|
| Preflight `git status --short --untracked-files=all` | Clean before this step. |
| Step 49 docs existence | Present. |
| Step 50 docs existence | Present. |
| Step 52 docs existence | Present. |
| Notice-related code search | Found the Settings page used `settings_errors( ANALYTICS_REPORT_AI_OPTION_NAME )`. |
| PHP lint | Clean for all PHP files found outside `vendor`, `release`, and `dist`; this also linted the existing dry-run stage files. |
| PHPCS / WPCS | Clean: `vendor/bin/phpcs` produced no output. |
| Source plugin check | `wp plugin check analytics-report-ai` scanned the source symlink and reported development/package files such as the dry-run zip, `phpcs.xml.dist`, `.gitignore`, `.distignore`, and the dry-run shell script. These are source-tree packaging findings, not findings in the staged release package. |
| Dry-run package regeneration | Passed. The staged package included runtime files only and completed credential-pattern scanning without high-risk matches. |
| Staged Plugin Check | Clean against `build/release-dry-run/stage/analytics-report-ai`. |

## Not Performed

- Browser Settings save retest was not performed by Codex.
- GA4 Fetch was not clicked.
- Generate AI Report / OpenAI Generate was not clicked.
- Google OAuth flow was not started.
- Real Google Access Token was not entered or saved.
- Real OpenAI API Key was not entered or saved.
- Credential option values were not displayed or recorded.
- `wp_options` credential or plugin settings option values were not displayed
  or recorded.
- Full request bodies, full payload bodies, raw responses, and generated report
  bodies were not displayed or recorded.
- Composer install/update, `phpcbf`, SVN, GitHub release, and WordPress.org
  publication actions were not performed.

## Security / Credential / External API Safety

- No external API communication was performed.
- No real credentials were used.
- No API keys were displayed or recorded.
- No access tokens were displayed or recorded.
- No Authorization headers were displayed or recorded.
- No full request bodies were displayed or recorded.
- No full AI payload bodies were displayed or recorded.
- No raw GA4 responses were displayed or recorded.
- No raw OpenAI responses were displayed or recorded.
- No generated report text was displayed or recorded.
- Credential storage behavior was not changed.
- Google Access Token and OpenAI API Key non-redisplay behavior was not changed.

## Manual Recheck Checklist

Use the same safe test data policy from Step 50:

- GA4 Property ID: `123456789`
- Hostname filter: checked
- Hostname: `localhost`
- Google Access Token: leave empty
- OpenAI API Key: leave empty

Manual recheck:

| ID | Check | Expected Result | Result |
|---|---|---|---|
| RECHECK-001 | Open Settings. | Settings screen opens. | Pending manual verification |
| RECHECK-002 | Save dummy non-credential values. | Save action completes. | Pending manual verification |
| RECHECK-003 | Confirm success notice. | A save completion notice is displayed. | Pending manual verification |
| RECHECK-004 | Confirm notice content. | Notice text is secret-free. | Pending manual verification |
| RECHECK-005 | Reload Settings. | Dummy non-credential values are retained as expected. | Pending manual verification |
| RECHECK-006 | Confirm credential fields. | Credential values are not redisplayed in plaintext. | Pending manual verification |
| RECHECK-007 | Open Report Builder. | Report Builder still opens. | Pending manual verification |
| RECHECK-008 | Avoid external actions. | GA4 Fetch, Generate AI Report, and Google OAuth are not executed. | Pending manual verification |

## Next Step Notes

- Step 54 can perform the human browser retest for the Settings save success
  notice using the checklist above.
- If the notice still does not appear, investigate the redirect URL and
  `settings-updated=true` behavior before touching save logic.
- Keep any follow-up remediation narrowly scoped to Settings notice display.
- Do not change credential handling, GA4 request behavior, OpenAI request
  behavior, AI payload behavior, or Report Builder flow unless a later issue
  directly requires it.
