# Step 296: Public Plugin Identity and Owner Alignment Implementation Results

## Step Conclusion

Public identity alignment completed for the current source tree.

The public display name, desired permanent slug, text domain, bundled translation filenames, package dry-run root, public readme identity, admin menu label, admin page title, and release tooling slug now align to:

- Display name: `Studio317 Report Drafts for Google Analytics`
- Desired permanent slug: `studio317-report-drafts-google-analytics`
- Text domain: `studio317-report-drafts-google-analytics`
- Public author: `Kimiya Watabe / Studio317`
- Author URI: `https://business.s317.com/`

WordPress.org release readiness is still a separate final decision. This step prepares the identity boundary for reviewer discussion and package validation; it does not perform submission, upload, Plugin Check, browser smoke, or external-service execution.

## Baseline Classification

Baseline issue: public identity mixed the previous broad name and owner signals with the newer Studio317 public identity.

Observed baseline categories before this step:

- Display name category: previous broad name
- Slug/text-domain category: previous slug
- Public owner category: mixed previous owner and Studio317 signals
- Package root category: previous slug
- Runtime internal prefix category: existing `analytics_report_ai` / `Analytics_Report_AI` / `ANALYTICS_REPORT_AI` prefixes

## New Public Identity

| Item | Result |
|---|---|
| Plugin display name | `Studio317 Report Drafts for Google Analytics` |
| Desired permanent slug | `studio317-report-drafts-google-analytics` |
| Text domain | `studio317-report-drafts-google-analytics` |
| Main plugin file | `studio317-report-drafts-google-analytics.php` |
| Bundled POT file | `languages/studio317-report-drafts-google-analytics.pot` |
| Bundled Japanese PO file | `languages/studio317-report-drafts-google-analytics-ja.po` |
| Bundled Japanese MO file | `languages/studio317-report-drafts-google-analytics-ja.mo` |
| Release dry-run package root | `studio317-report-drafts-google-analytics/` |

## Author and URI State

| Field | Final state | Notes |
|---|---|---|
| Author | `Kimiya Watabe / Studio317` | Updated in the plugin header and translation metadata. |
| Author URI | `https://business.s317.com/` | Updated in the plugin header and translation metadata. |
| Plugin URI | Omitted / unresolved | No dedicated Studio317 plugin introduction URL was safely confirmed in this environment. The previous owner URI was not retained as public plugin metadata. |
| Readme contributor metadata | `cuerda` | Confirmed WordPress.org directory account username. This account identifier is separate from the plugin display name, author wording, and Studio317 public identity. |

## Changed Files

Current public identity and package-alignment changes affect:

- `.distignore`
- `studio317-report-drafts-google-analytics.php`
- `readme.txt`
- `composer.json`
- `phpcs.xml.dist`
- `tools/build-release-zip-dry-run.sh`
- `uninstall.php`
- `assets/css/admin.css`
- `assets/css/index.php`
- `assets/css/settings-help.css`
- `assets/index.php`
- `assets/js/admin.js`
- `assets/js/index.php`
- `assets/js/settings-help.js`
- `docs/DATA-HANDLING.md`
- `docs/DEVELOPMENT.md`
- `docs/README.md`
- `docs/RELEASE.md`
- `includes/class-admin.php`
- `includes/class-ga4-client.php`
- `includes/class-openai-client.php`
- `includes/class-plugin.php`
- `includes/class-prompt-builder.php`
- `includes/class-report-builder.php`
- `includes/class-report-data-formatter.php`
- `includes/class-settings.php`
- `includes/functions-utils.php`
- `includes/index.php`
- `index.php`
- `languages/index.php`
- `languages/studio317-report-drafts-google-analytics.pot`
- `languages/studio317-report-drafts-google-analytics-ja.po`
- `languages/studio317-report-drafts-google-analytics-ja.mo`

The previous main plugin file and previous language files are replaced by the new slug-aligned filenames:

- `analytics-report-ai.php` -> `studio317-report-drafts-google-analytics.php`
- `languages/analytics-report-ai.pot` -> `languages/studio317-report-drafts-google-analytics.pot`
- `languages/analytics-report-ai-ja.po` -> `languages/studio317-report-drafts-google-analytics-ja.po`
- `languages/analytics-report-ai-ja.mo` -> `languages/studio317-report-drafts-google-analytics-ja.mo`

## Old Identifier Inventory

Source-level grep excluding historical maturation docs and binary MO files found:

- Public display string `Analytics Report AI`: not present in current non-historical text sources after this step.
- Old hyphenated slug `analytics-report-ai`: not present in current non-historical text sources after this step.
- Previous owner label `cuerda`: no longer used as public owner/developer wording. It remains only as the confirmed WordPress.org `Contributors` account username in `readme.txt`.
- Previous owner URI `cuerda.org`: not present in current non-historical text sources after this step.

Intentional residual internal identifiers remain:

- PHP class prefix: `Analytics_Report_AI`
- PHP function prefix: `analytics_report_ai`
- PHP constant prefix: `ANALYTICS_REPORT_AI`
- Option keys: `analytics_report_ai_settings`, `analytics_report_ai_oauth_tokens`
- Nonce/action/transient/internal status keys using `analytics_report_ai`
- PHPCS global-prefix allowlist entries for the existing internal prefixes
- `@package Analytics_Report_AI` comments

These are intentionally preserved to avoid migration risk, option-key churn, nonce/action churn, and unnecessary runtime namespace changes in a public identity patch.

## Google Analytics Naming and Non-Affiliation

The new name uses `for Google Analytics` to describe the service category without implying Google affiliation, approval, sponsorship, or official status.

The readme now includes a minimal non-affiliation statement:

- The plugin is developed by `Kimiya Watabe / Studio317`.
- The plugin is not affiliated with, endorsed by, or sponsored by Google.

No Google branding, OAuth, GA4 request behavior, token lifecycle behavior, or external-service execution behavior was changed.

## Translation Changes

Changed now:

- Text domain changed to `studio317-report-drafts-google-analytics`.
- `load_plugin_textdomain()` remains in place and now loads the new text domain.
- POT filename changed to match the new text domain.
- Japanese PO/MO filenames changed to match the new text domain.
- POT was regenerated from source.
- Japanese PO was merged, obsolete old-name entries were removed, and MO was regenerated.

Deferred:

- Translation-loading strategy review remains separate from this step.
- WordPress AI Client migration remains separate from this step.

## Runtime Boundaries

This step did not change the following runtime boundaries:

- OpenAI direct integration implementation
- WordPress AI Client migration state
- OpenAI API key storage, source category, deletion, or UI behavior
- Google OAuth authorization, callback, token exchange, token lifecycle, refresh, revoke, or disconnect behavior
- GA4 Fetch behavior
- Report Builder staged workflow
- Settings save logic
- Credential or token option values

The only OpenAI-payload-adjacent change is the public identity metadata label used in the generated AI payload. The payload structure, validation boundary, request endpoint, model configuration, and API execution flow were not changed.

## Prohibited Actions Confirmation

Not performed:

- External API communication
- Browser admin smoke
- Google navigation
- OAuth Connect / Authorize
- GA4 Fetch
- OpenAI Generate
- Token endpoint communication
- Refresh request
- Revoke request
- Plugin Check
- Credential, token, OAuth client, or option value inspection
- WordPress.org slug reservation or upload
- Commit or push

No credentials, tokens, Authorization headers, option values, request bodies, raw responses, AI payload JSON, generated report bodies, screenshots, cookies, sessions, nonces, or browser Network evidence were displayed or recorded.

## Verification Results

| Check | Result |
|---|---|
| `git diff --check` before edits | Pass |
| `php -l studio317-report-drafts-google-analytics.php` | Pass |
| `find includes -name '*.php' -print0 \| xargs -0 -n1 php -l` | Pass |
| `node --check assets/js/admin.js` | Pass |
| `node --check assets/js/settings-help.js` | Pass |
| `wp i18n make-pot` for new text domain | Pass |
| `msgmerge` Japanese PO with regenerated POT | Pass |
| `msgattrib --no-obsolete` for Japanese PO | Pass |
| `msgfmt --check` and Japanese MO regeneration | Pass |
| Source grep for old public name / slug / owner outside historical docs and MO files | Pass: no current non-historical text matches |
| Release dry-run build script | Pass |
| Release dry-run package root | `studio317-report-drafts-google-analytics/` |
| Release dry-run metadata check | Pass: Version and Stable tag remain `0.1.0` |
| Release dry-run high-risk credential pattern scan | Pass |
| Release dry-run documentation keyword scan | Warning-only credential-related documentation keywords present in expected support/security wording |

## Human Checks Before Reviewer Reply

Before replying to the WordPress.org reviewer or reserving the slug, a human should verify:

- The WordPress.org account public profile, official Studio317 email, and forum profile are aligned as human administrative actions.
- Whether a dedicated official Studio317 plugin introduction URL exists and should become `Plugin URI`.
- Whether the reviewer wants the GitHub repository name changed, which was intentionally out of scope here.
- Whether the desired permanent slug `studio317-report-drafts-google-analytics` is available or should be adjusted by reviewer guidance.

## Recommended Next Steps

1. Ask the WordPress.org reviewer to reserve or approve the new slug `studio317-report-drafts-google-analytics`.
2. Decide the final `Plugin URI` once an official Studio317 plugin introduction page exists or is confirmed.
3. Continue the separate AI Client migration design track.
4. Continue the separate `load_plugin_textdomain()` review track after the WordPress minimum version and AI Client direction are settled.
