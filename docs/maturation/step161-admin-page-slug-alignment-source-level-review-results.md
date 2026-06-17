# Step 161: Admin Page Slug Alignment Source-Level Review Results

## Step Purpose

Step 161 performs a docs-only and source-level review of the Step 159
`permission_denied_page_slug_mismatch` finding.

The purpose is to confirm the admin page slugs currently registered in
production source and align future admin smoke instructions with those
source-confirmed slugs.

Result classification: `Source-level review completed`

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step160-local-http-hard-block-guard-cleanup-results.md`
- `docs/maturation/step159-missing-credential-ui-path-controlled-local-only-execution-results.md`
- `docs/maturation/step158-missing-credential-ui-path-local-http-guard-preparation-results.md`
- `docs/maturation/step155-human-controlled-credential-source-admin-smoke-results.md`

## Execution Boundary

Step 161 reviewed source files and maturation docs only.

This step did not perform:

- production PHP changes,
- JavaScript, CSS, `readme.txt`, or tools changes,
- admin browser operations,
- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- temporary guard / mu-plugin / helper / test script creation,
- browser DevTools / Network evidence collection,
- screenshot recording,
- `wp option get analytics_report_ai_oauth_tokens`,
- database dump,
- plugin settings option value display,
- OAuth token option value display,
- serialized option value display,
- credential, API key, access token, refresh token, or Authorization header
  display,
- OAuth client ID or client secret value display,
- GA4 Property ID, hostname/domain, analytics values, page path/source/city
  display,
- request body, GA4 raw response, AI payload JSON, OpenAI raw response, or
  generated report body display.

Evidence is limited to source-level slug, callback, and status categories.

## Source Files Reviewed

Production source reviewed:

- `includes/class-admin.php`
- `analytics-report-ai.php`

Referenced docs reviewed:

- `docs/maturation/step159-missing-credential-ui-path-controlled-local-only-execution-results.md`
- `docs/maturation/step160-local-http-hard-block-guard-cleanup-results.md`

## Admin Menu / Submenu Registration Findings

Source-level findings from `Analytics_Report_AI_Admin::register_menus()`:

| Registration | Parent slug | Page slug | Callback | Source-level result |
|---|---|---|---|---|
| Top-level menu | Not applicable | `analytics-report-ai` | Report Builder render callback | Registered |
| Report Builder submenu | `analytics-report-ai` | `analytics-report-ai` | Report Builder render callback | Registered |
| Settings submenu | `analytics-report-ai` | `analytics-report-ai-settings` | Settings render callback | Registered |

The Report Builder screen is registered through both the top-level menu and a
matching submenu entry using the same `analytics-report-ai` page slug.

The Settings screen is registered as `analytics-report-ai-settings`.

The source review did not find `analytics-report-ai-report-builder` registered
as an admin page slug in production source.

## Source-Confirmed Admin Page Slugs

Source-confirmed slug summary:

```text
Report Builder page slug: analytics-report-ai
Settings page slug: analytics-report-ai-settings
analytics-report-ai-report-builder registered as page slug: No
analytics-report-ai registered as Report Builder screen: Yes
analytics-report-ai-settings registered as Settings screen: Yes
```

Future admin smoke instructions should use slug-level references:

```text
Report Builder: page=analytics-report-ai
Settings: page=analytics-report-ai-settings
```

This document intentionally records only page slug categories. It does not
record full local URLs, hostnames, domains, browser address bar values, query
strings beyond these safe page slug categories, screenshots, or Network
evidence.

## Step 159 Slug Finding Classification

Step 159 recorded:

```text
Original report-builder URL access result: permission_denied_page_slug_mismatch
Actual Report Builder URL used: page=analytics-report-ai
```

Step 161 classification:

```text
slug finding classification: smoke_instruction_docs_alignment_finding
fatal classification: No
production runtime bug asserted: No
```

The finding is best treated as a smoke instruction / docs alignment issue. The
source-confirmed Report Builder page slug is `analytics-report-ai`, while
`analytics-report-ai-report-builder` is not registered as a production admin
page slug.

This step does not change production code. If future docs or smoke instructions
still reference `page=analytics-report-ai-report-builder`, those instructions
should be aligned to the source-confirmed slug.

## Safety / Evidence Boundary

Step 161 did not inspect or record:

- OAuth token option values,
- serialized option values,
- plugin settings option values,
- manual Google Access Token values,
- access tokens,
- refresh tokens,
- Authorization headers,
- OAuth client ID values,
- OAuth client secret values,
- token endpoint requests or responses,
- authorization codes,
- callback URLs,
- full browser address bar URLs,
- raw state values,
- raw provider errors,
- raw URLs,
- HTTP headers,
- request bodies,
- response bodies,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- page path/source/city values,
- GA4 raw responses,
- AI payload JSON,
- OpenAI raw responses,
- generated report bodies,
- screenshots,
- browser Network evidence,
- database rows or database dumps,
- email addresses or Google account identifiers,
- project IDs or project identifiers.

## Result Classification

Result: `Source-level review completed`

Rationale:

- admin menu and submenu registration was reviewed at source level,
- source-confirmed Report Builder slug is `analytics-report-ai`,
- source-confirmed Settings slug is `analytics-report-ai-settings`,
- `analytics-report-ai-report-builder` was not found as a registered production
  admin page slug,
- Step 159 finding is classified as a smoke instruction / docs alignment
  finding, not as a fatal error,
- no production code was changed,
- no external action or forbidden evidence collection occurred.

## Notes And Limitations

- This review is source-level only.
- No admin browser verification was performed in Step 161.
- This step does not prove runtime WordPress menu registration in a live admin
  session.
- This step does not validate GA4 Fetch, OpenAI Generate, OAuth flows, token
  endpoint behavior, Plugin Check readiness, or WordPress.org release
  readiness.

## Recommended Next Step

Recommended next step:

```text
Step 162: Admin smoke instruction slug alignment docs update
```

Rationale:

- Source review confirms the Report Builder slug that future admin smoke
  instructions should use.
- No production UI slug implementation issue was confirmed in this step.
- A docs-only update can align maturity docs and future smoke procedures to the
  source-confirmed slugs.
- Step 162 should not run Plugin Check or real external API actions unless
  explicitly requested later.

## Commands Executed

Safe source-level commands:

```bash
git status --short --untracked-files=all
grep -R "add_menu_page\|add_submenu_page\|analytics-report-ai-report-builder\|analytics-report-ai-settings\|analytics-report-ai" -n includes *.php
rg -n "add_menu_page|add_submenu_page|analytics-report-ai-report-builder|analytics-report-ai-settings" includes analytics-report-ai.php
rg -n "analytics-report-ai-report-builder" includes analytics-report-ai.php docs/maturation/step154-controlled-credential-source-admin-smoke-plan.md docs/maturation/step159-missing-credential-ui-path-controlled-local-only-execution-results.md
nl -ba includes/class-admin.php | sed -n '88,122p'
rg -n "page=analytics-report-ai|permission_denied_page_slug_mismatch|analytics-report-ai-report-builder" docs/maturation/step159-missing-credential-ui-path-controlled-local-only-execution-results.md docs/maturation/step160-local-http-hard-block-guard-cleanup-results.md
test -f docs/maturation/step161-admin-page-slug-alignment-source-level-review-results.md && echo "step161_doc_exists"
git diff -- docs/maturation/step161-admin-page-slug-alignment-source-level-review-results.md
git diff --name-only
git status --short --untracked-files=all
```

Command result summary:

- `add_menu_page` and `add_submenu_page` registrations were found in
  `includes/class-admin.php`.
- `analytics-report-ai-settings` was found as the Settings page slug.
- `analytics-report-ai-report-builder` was not found as a registered production
  source slug.
- Step 161 docs file exists.
- Production code was not changed.
- `git diff --name-only` does not include untracked docs until they are staged;
  use `git status --short --untracked-files=all` for the full working tree
  view.
