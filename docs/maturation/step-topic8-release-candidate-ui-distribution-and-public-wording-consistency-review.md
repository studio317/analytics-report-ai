# Topic 8A: Release-candidate UI, distribution, and public wording consistency review

## Step purpose

Topic 8A is a docs-only / review-only checkpoint before release-candidate version updates, changelog updates, package generation, package inspection, and Plugin Check.

The review checks the current source tree after Topic 7 for:

- Admin screen structure and responsibility boundaries.
- Current two-step Report Builder operation.
- Removed legacy preview / three-step UI remnants.
- Help dialog and admin asset enqueue boundaries.
- Settings form wording, translation, and value-hidden behavior.
- Current Status read-only and status/category-only boundaries.
- Source-level distribution boundaries.
- Public readme / version boundary consistency and next release follow-ups.

No production PHP, CSS, JavaScript, translation, readme, package configuration, version, or build script changes were made in this step.

## Baseline classification

Classification: `Aligned`

Baseline evidence:

- Latest committed HEAD: `style(admin): refine management screen layout`.
- Start-of-step working tree: clean.
- Topic 6C results document is present.
- This step did not run browser admin smoke, OAuth, GA4 Fetch, AI Generate, external API communication, Plugin Check, package build, commit, push, WordPress.org upload, or SVN operation.

## Review boundary

Allowed evidence:

- Source file names.
- Symbol / action / asset names.
- Visible source strings and translation categories.
- Version / Stable tag metadata.
- Status-level classifications.
- Command result categories.

Forbidden evidence not inspected or recorded:

- API keys.
- OAuth access tokens or refresh tokens.
- OAuth Client ID or Client Secret values.
- GA4 Property ID values.
- Hostname values.
- Analytics values.
- AI payload JSON.
- Generated report bodies.
- Option values.
- Transient values.
- Request or response bodies.
- Screenshots.
- Browser Network evidence.
- Cookie, session, or nonce values.

## Admin screen structure and responsibilities

Classification: `Aligned`

Source-level review:

- `includes/class-admin.php` registers the plugin menu and submenus in this order:
  - `Report Builder`
  - `Current Status`
  - `Settings`
- All three screens use `manage_options`.
- Report Builder uses the top-level slug and submenu slug `studio317-report-drafts-google-analytics`.
- Current Status uses `studio317-report-drafts-google-analytics-status`.
- Settings uses `studio317-report-drafts-google-analytics-settings`.

Responsibility review:

- `includes/class-report-builder.php` is focused on report conditions and the `Create AI Report` submission.
- `includes/class-status-page.php` renders read-only setup and Google connection status/category labels.
- `includes/class-settings.php` keeps the editable setup flow in this order:
  - GA4 report settings.
  - Google OAuth client settings.
  - Google connection.

Safety boundary:

- Current Status does not contain Settings forms.
- Current Status does not contain OAuth connect / reconnect / disconnect forms.
- Current Status does not display credential, token, option, analytics, payload, or generated report values.
- Report Builder and Settings do not retain the large status blocks that were moved into Current Status.

## Report Builder current operation and legacy UI remnants

Classification: `Aligned`

Current operation:

- The visible primary action source string is `Create AI Report`.
- The active Japanese translation is `AIレポートを作成`.
- The Report Builder form posts `analytics_report_ai_report_action` with value `create_ai_report`.
- `maybe_handle_request()` routes the current form action to `execute_ai_report_from_conditions()`.
- The current flow is source-level two-step:
  - Set report conditions.
  - Create AI report.

Legacy UI / action review:

- No production UI route for old `fetch_ga4_summary` was found.
- No production UI route for old `generate_ai_report` was found.
- No active production / asset / translation source string was found for:
  - `Fetch GA4 Data`
  - `Generate AI Report`
  - `GA4 Data Preview`
  - `AI Payload Preview`
  - `AI Data Preview`
  - `Validated Conditions`
- `generate_ai_report_from_payload()` remains only as an internal method name used by the integrated flow; it is not a public POST action or visible UI route.

Removed preview UI review:

- Removed preview UI CSS classes were not found in `includes/` or `assets/`:
  - `studio317-report-drafts-google-analytics-preview-table`
  - `studio317-report-drafts-google-analytics-json-preview`
  - `studio317-report-drafts-google-analytics-generate-form`
- `assets/js/admin.js` contains scope-field behavior, copy behavior, confirm behavior, and single-submit behavior; it does not contain legacy preview rendering or old three-step action handling.

Preserved current behavior:

- Successful generation still renders the generated report draft section.
- Copy action remains available for generated report text.
- Failure paths use safe notices and do not expose GA4 values, payload JSON, credentials, tokens, request bodies, or response bodies.

## Help dialog and explanation boundaries

Classification: `Aligned`

Source-level review:

- `includes/class-help-dialog.php` provides the shared help trigger and dialog renderer.
- `assets/css/help-dialog.css` and `assets/js/help-dialog.js` are enqueued only for:
  - Report Builder.
  - Settings.
- Current Status is not included in the help-dialog hook suffix allowlist.
- `settings-help.css` / `settings-help.js` were not found as production files or enqueue targets.

Help availability review:

- Report Builder exposes the GA4 Data API help dialog.
- Settings exposes:
  - Setup checklist help.
  - External service usage help.
  - Google OAuth Client ID help.
  - Google OAuth Client Secret help.
  - Google OAuth setup help.

Visible safety review:

- Validation errors and submission notices remain visible as notices.
- OAuth operation result notices remain in Settings.
- Reconnect-required Report Builder guidance remains visible as a warning with a Settings link.
- Main short explanatory text remains visible; long setup and data-use guidance is in help dialogs.
- Google OAuth Client ID / Secret help triggers use accessible labels and the visible default `Help` text where no explicit button text is provided; tooltip-style redundant UI was not found.

## Settings form, translation, and safe saved-value display

Classification: `Aligned`

Source-level review:

- `includes/class-settings.php` renders:
  - `GA4 Property ID`.
  - `Host Name Filter`.
  - `Host Name`.
  - `Google OAuth Client ID`.
  - `Google OAuth Client Secret`.
  - `Redirect URI for Google OAuth setup`.
  - `Delete saved Google OAuth client settings`.
- `settings-form.css` is enqueued only for the Settings hook suffix.

Translation review:

- `Host Name Filter` active Japanese PO/MO translation: `ホスト名フィルター`.
- `Host Name` active Japanese PO/MO translation: `ホスト名`.
- `When Host Name Filter is enabled, GA4 data is filtered by this host name.` active Japanese PO/MO translation uses the unified `ホスト名フィルター` / `ホスト名` terminology.
- No POT / PO / MO changes were made in this review step.

Safe saved-value review:

- Saved Google OAuth Client ID and Client Secret fields render as password inputs with `value=""`.
- Saved-value state uses hidden placeholder/status wording and does not display exact value, partial value, suffix, length, or hash.
- Server configuration source disables the editable field and uses safe explanatory text.
- Redirect URI remains a read-only Settings field and was not duplicated into Current Status or docs.
- External links are emitted through `get_external_link_html()` with `target="_blank"` and `rel="external noreferrer noopener"`.

## Current Status safe boundary

Classification: `Aligned`

Source-level review:

- `includes/class-status-page.php` reads safe resolver categories and lifecycle categories.
- OAuth client resolver output is reduced to:
  - source category.
  - Settings fallback status.
  - value-hidden status.
- `client_id` and `client_secret` keys are explicitly unset after the status categories are extracted.
- Google connection and token lifecycle display uses safe labels:
  - connection status.
  - token lifecycle status.
  - token storage status.
  - refresh handling status.
  - disconnect handling status.
  - provider revoke handling status.

Read-only boundary:

- Current Status contains Settings links only.
- It does not contain Settings save controls.
- It does not contain OAuth connect / reconnect / disconnect controls.
- It does not display API keys, OAuth tokens, OAuth Client ID / Secret values, GA4 Property ID values, hostnames, analytics values, payloads, generated report text, option values, or transient values.

## Asset and distribution boundary

Classification: `Aligned`

Referenced runtime assets and classes:

- `assets/css/admin.css` is enqueued on plugin admin screens.
- `assets/js/admin.js` is enqueued on plugin admin screens.
- `assets/css/help-dialog.css` is enqueued on Report Builder and Settings.
- `assets/js/help-dialog.js` is enqueued on Report Builder and Settings.
- `assets/css/settings-form.css` is enqueued on Settings only.
- `includes/class-help-dialog.php` is loaded by `includes/class-plugin.php`.
- `includes/class-status-page.php` is loaded by `includes/class-plugin.php`.

Deleted / obsolete asset reference review:

- No production reference to `settings-help.css` or `settings-help.js` was found.
- No production reference to removed preview CSS classes was found in `includes/` or `assets/`.

Distribution source-level review:

- `.distignore` keeps `includes/`, `assets/`, `languages/`, `readme.txt`, the main plugin file, and `uninstall.php` as runtime package content.
- `.distignore` excludes docs, tools, tests, build artifacts, Composer metadata, npm metadata, logs, temp files, and editor metadata.
- `tools/build-release-zip-dry-run.sh` checks for required package entries/prefixes:
  - main plugin file.
  - `readme.txt`.
  - `uninstall.php`.
  - `includes/`.
  - `assets/`.
  - language POT/PO/MO.
- The script also rejects development paths including docs, tools, tests, build, vendor, and node_modules.

No package was built or inspected in this step.

## Public wording, readme, and version boundary

Classification: `Needs follow-up`

Version boundary:

- Plugin header version: `0.1.0`.
- `ANALYTICS_REPORT_AI_VERSION`: `0.1.0`.
- `readme.txt` Stable tag: `0.1.0`.
- Current version metadata is mutually aligned.

Public wording review:

- `readme.txt` still describes the old three-step / preview-based flow in multiple places:
  - `Fetch GA4 Data`.
  - `Data Preview`.
  - `Generate AI Report`.
  - separate fetch and generate actions.
- `docs/README.md`, `docs/DATA-HANDLING.md`, and `docs/DEVELOPMENT.md` also contain old fetch / preview / generate wording. These docs are excluded from release packages by `.distignore`, but they are still source-level public/support documentation candidates and should be aligned separately.
- Current production UI and translations now use `Create AI Report` / `AIレポートを作成` as the integrated action.

External service wording review:

- Existing public wording still correctly communicates Google Analytics Data API and WordPress AI Client involvement, but the action boundary is outdated because it references separate Fetch and Generate steps.
- The next public wording update should rewrite action descriptions around `Create AI Report`:
  - It validates conditions.
  - It fetches GA4 preset reports.
  - It generates a draft through the WordPress AI Client when provider generation is available.

Regional wording review:

- No Japan-specific regional limitation wording was found in `readme.txt`.
- Public wording refers to `City-level regional trends, where available`, which is compatible with the current city/country source-level implementation.

Next release update items:

- Version, Stable tag, and changelog remain aligned at `0.1.0` now, but they will need intentional update only when the release candidate version is chosen.
- `readme.txt` needs a narrow public wording update before packaging.
- Source-only docs may need a separate docs wording alignment if they are intended to remain public GitHub documentation.

## Decision table

| Area | Classification | Notes |
| --- | --- | --- |
| Baseline / working tree | `Aligned` | Clean committed Topic 7 baseline confirmed. |
| Admin menu order and screen responsibilities | `Aligned` | Report Builder, Current Status, Settings are registered in order with separate responsibilities. |
| Report Builder production UI | `Aligned` | Current form uses only `create_ai_report`; old visible action strings are absent from production sources. |
| Removed preview UI remnants | `Aligned` | Old preview CSS/classes/forms are absent from production `includes/` and `assets/`. |
| Help dialog enqueue and removed settings-help assets | `Aligned` | Help dialog assets are limited to Report Builder and Settings; old settings-help assets are absent. |
| Settings form / Japanese translations | `Aligned` | Host name terminology is aligned in PO and MO; saved OAuth client values remain hidden. |
| Current Status safe display | `Aligned` | Read-only, status/category-level only, with client ID / secret removed from resolver output before display. |
| Asset / distribution source boundary | `Aligned` | Runtime assets/classes are referenced and not excluded by `.distignore`; package generation was not performed. |
| Version / Stable tag | `Aligned` | Header, constant, and Stable tag all remain `0.1.0`. |
| Public readme / source docs wording | `Needs follow-up` | Old three-step / preview wording remains and should be updated before release packaging. |
| Browser / external runtime validation | `Not assessed` | Intentionally not run in this docs-only review. |
| Plugin Check / package validation | `Not assessed` | Intentionally not run in this docs-only review. |

## Limited follow-up proposal

Recommended next limited topic:

`Topic 8B: Public readme and source documentation wording alignment for integrated Create AI Report flow`

Scope:

- Update `readme.txt` to replace old `Fetch GA4 Data` / `Data Preview` / `Generate AI Report` wording with the current integrated `Create AI Report` flow.
- Update release-facing external-service wording to describe the current action boundary accurately.
- Update changelog draft only if the user explicitly authorizes release wording / version work.
- Optionally update source-only docs (`docs/README.md`, `docs/DATA-HANDLING.md`, `docs/DEVELOPMENT.md`) in a separate docs-focused pass if they remain public support references.

Non-goals for the follow-up:

- No runtime behavior change.
- No OAuth, GA4, AI Client, Settings, Current Status, Help dialog, CSS, or JavaScript behavior change.
- No package build, Plugin Check, WordPress.org upload, SVN operation, commit, or push unless separately requested.

## Result classification

Overall Topic 8A classification: `Needs follow-up`

Reason:

- Production admin UI, translations, asset enqueue, and safe display boundaries are aligned with the current two-step Report Builder implementation.
- Public readme / source documentation still contains old three-step / preview wording and should be updated before release-candidate packaging.

WordPress.org release-candidate progression remains gated on the public wording follow-up and any later explicit version / changelog / package / Plugin Check steps.
