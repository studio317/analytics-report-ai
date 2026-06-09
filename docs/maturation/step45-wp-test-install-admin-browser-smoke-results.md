# Step 45: WP Test Install and Admin Browser Smoke Results

## Scope

This step safely introduced the development checkout of Analytics Report AI into
the WordPress test environment and executed the install / activation smoke
checks that were blocked in Step 44.

This step did not perform browser admin verification in Codex. Browser-only
checks are documented as a manual checklist with relative admin URLs and remain
`Blocked`.

This step did not click Fetch GA4 Data, did not click Generate AI Report, did
not save real credentials, and did not perform GA4, OpenAI, Google OAuth, or
other external API communication.

No production PHP, JavaScript, CSS, `readme.txt`, Composer files, PHPCS config,
distribution config, dry-run script, version, or metadata files were changed.

## Environment

| Item | Value |
|---|---|
| Plugin | Analytics Report AI |
| Slug | `analytics-report-ai` |
| Version | `0.1.0` |
| Source checkout | `/var/www/html/analytics-report-ai` |
| WordPress test environment | `/var/www/html/wp-dev` |
| WordPress plugin path | `/var/www/html/wp-dev/wp-content/plugins/analytics-report-ai` |
| Source branch | `main` |
| Credentials | No real Google Access Token or OpenAI API Key was used. |

## Install Method

The plugin was introduced into the WordPress test environment by symlink.

| Item | Value |
|---|---|
| Link source | `/var/www/html/analytics-report-ai` |
| Link destination | `/var/www/html/wp-dev/wp-content/plugins/analytics-report-ai` |
| Existing destination before install | Not present. |
| Created symlink | Yes. |
| Verified symlink target | `/var/www/html/analytics-report-ai` |

The symlink was created only after confirming that no
`wp-content/plugins/analytics-report-ai` file, directory, or symlink already
existed.

## Commands Executed

Repository state:

```sh
cd /var/www/html/analytics-report-ai
pwd
git status --short --untracked-files=all
git branch --show-current
php -l analytics-report-ai.php
```

WordPress test environment:

```sh
cd /var/www/html/wp-dev
pwd
ls -la wp-content/plugins
```

Symlink install:

```sh
cd /var/www/html/wp-dev/wp-content/plugins
test -e /var/www/html/wp-dev/wp-content/plugins/analytics-report-ai
ln -s /var/www/html/analytics-report-ai analytics-report-ai
ls -la analytics-report-ai
readlink analytics-report-ai
```

WP-CLI smoke:

```sh
cd /var/www/html/wp-dev
wp plugin list --field=name
wp plugin status analytics-report-ai
wp plugin activate analytics-report-ai
wp plugin status analytics-report-ai
wp plugin deactivate analytics-report-ai
wp plugin status analytics-report-ai
wp plugin activate analytics-report-ai
wp plugin status analytics-report-ai
wp plugin list --field=name | grep '^analytics-report-ai$'
```

One `wp plugin list --field=name | grep '^analytics-report-ai$'` run failed in
the sandbox with a local database `Operation not permitted` error. The same
WP-CLI check was rerun with local DB access permitted and passed. This was not
external API communication.

Git verification after docs creation:

```sh
cd /var/www/html/analytics-report-ai
git status --short --untracked-files=all
git diff --stat
```

## Results Summary

| Category | Pass | Fail | Blocked | Not tested |
|---|---:|---:|---:|---:|
| Repository / install preparation | 5 | 0 | 0 | 0 |
| WP-CLI activation smoke | 6 | 0 | 0 | 0 |
| Browser/admin checks | 0 | 0 | 5 | 0 |
| External API / credential checks | 0 | 0 | 0 | 4 |
| Total | 11 | 0 | 5 | 4 |

Overall result: symlink install and WP-CLI activation smoke passed. Browser
admin checks remain blocked for manual verification. External API-dependent
checks remain not tested and deferred.

## Detailed Results

| ID | Check | Status | Notes |
|---|---|---|---|
| WP45-001 | Source checkout path confirmed | Pass | `pwd` returned `/var/www/html/analytics-report-ai`. |
| WP45-002 | Source repository state checked before install | Pass | `git status --short --untracked-files=all` had no output before this docs file was added. |
| WP45-003 | Source branch checked | Pass | Branch was `main`. |
| WP45-004 | WordPress plugins directory inspected | Pass | Existing plugins were inspected before creating the symlink. |
| WP45-005 | Existing destination checked | Pass | `analytics-report-ai` destination was not present before symlink creation. |
| WP45-006 | Symlink created | Pass | `wp-content/plugins/analytics-report-ai` was created as a symlink. |
| WP45-007 | Symlink target verified | Pass | `readlink analytics-report-ai` returned `/var/www/html/analytics-report-ai`. |
| WP45-008 | Plugin appears in WP-CLI plugin list | Pass | WP-CLI listed `analytics-report-ai`. |
| WP45-009 | Initial plugin status | Pass | Initial status after symlink install was `Inactive`. |
| WP45-010 | Plugin activation | Pass | `wp plugin activate analytics-report-ai` completed successfully. |
| WP45-011 | Plugin deactivation | Pass | `wp plugin deactivate analytics-report-ai` completed successfully. |
| WP45-012 | Plugin reactivation and final active state | Pass | Reactivation completed successfully; final status was `Active`. |
| WP45-013 | Browser Plugins screen | Blocked | No browser verification was performed in Codex. Use `/wp-admin/plugins.php`. |
| WP45-014 | Browser admin menu presence | Blocked | No browser verification was performed in Codex. Check for Analytics Report AI in the admin menu. |
| WP45-015 | Settings screen browser render | Blocked | No browser verification was performed in Codex. Use `/wp-admin/admin.php?page=analytics-report-ai-settings`. |
| WP45-016 | Report Builder browser render | Blocked | No browser verification was performed in Codex. Use `/wp-admin/admin.php?page=analytics-report-ai`. |
| WP45-017 | Browser JavaScript UI checks | Blocked | Scope switching, console errors, textarea, and copy UI need manual browser verification. |
| WP45-018 | Fetch GA4 Data click | Not tested | Intentionally not clicked because it can trigger GA4 API communication. |
| WP45-019 | Generate AI Report click | Not tested | Intentionally not clicked because it can trigger OpenAI API communication. |
| WP45-020 | Real credential entry/save | Not tested | Intentionally not performed; no real credentials were used. |
| WP45-021 | Raw response / payload / generated report review | Not tested | Intentionally not performed; full sensitive bodies must not be recorded. |

No Fail items were observed.

## Browser/Admin Manual Checklist

Use these relative URLs after logging in to the WordPress admin for
`/var/www/html/wp-dev`:

- Plugins screen: `/wp-admin/plugins.php`
- Report Builder: `/wp-admin/admin.php?page=analytics-report-ai`
- Settings: `/wp-admin/admin.php?page=analytics-report-ai-settings`

Manual checks to perform:

- Confirm Analytics Report AI appears in the Plugins list.
- Confirm Analytics Report AI is active.
- Confirm the Analytics Report AI admin menu appears for an administrator.
- Open Settings.
- Open Report Builder.
- Confirm Settings shows no visible PHP fatal error, warning, or notice.
- Confirm Report Builder shows no visible PHP fatal error, warning, or notice.
- Confirm there are no obvious JavaScript console errors on initial page load.
- Confirm scope switching UI is visible.
- Confirm generated report textarea and Copy Report Text UI behavior only with
  non-sensitive local text or safe fixture state.
- Do not click Fetch GA4 Data.
- Do not click Generate AI Report.
- Do not enter real Google Access Tokens.
- Do not enter real OpenAI API Keys.
- Do not record screenshots that contain credential status, payload content,
  raw responses, or generated report text unless fully redacted.

## Security Notes

- No GA4 API call was performed.
- No OpenAI API call was performed.
- No Google OAuth flow was performed.
- No other external API call was performed.
- No real Google Access Token was used.
- No real OpenAI API Key was used.
- No Authorization header was displayed or recorded.
- No full request body was displayed or recorded.
- No full payload body was displayed or recorded.
- No raw GA4 or OpenAI response body was displayed or recorded.
- No generated report text was displayed or recorded.
- `wp_options` credential values were not displayed or recorded.

## Blocked Items

The following remain blocked because Codex did not perform browser admin
verification:

- Plugins screen visual confirmation.
- Admin menu visual confirmation.
- Settings screen visual rendering.
- Report Builder visual rendering.
- JavaScript console, scope switching, textarea, and copy UI behavior.

## Not Tested Items

The following were intentionally not tested in Step 45:

- Fetch GA4 Data button click.
- Generate AI Report button click.
- Real Google Access Token entry or save.
- Real OpenAI API Key entry or save.
- Raw GA4 response inspection.
- Raw OpenAI response inspection.
- Full AI payload inspection.
- Generated report text inspection.

These checks require either controlled manual E2E verification or safe stubs and
should not be mixed into this install / activation smoke step.

## Next Step Notes

- The plugin is now symlinked into `/var/www/html/wp-dev/wp-content/plugins/`
  and is active after the WP-CLI reactivation smoke.
- A manual browser pass can now use the URLs and checklist above to resolve the
  remaining browser/admin blocked items.
- If Step 46 proceeds to manual browser smoke, keep evidence status-level or
  redacted and do not click GA4/OpenAI external request buttons unless that step
  explicitly allows it.
- If Step 45 needs to be reverted locally, remove only the symlink
  `/var/www/html/wp-dev/wp-content/plugins/analytics-report-ai`; do not remove
  the source checkout.
