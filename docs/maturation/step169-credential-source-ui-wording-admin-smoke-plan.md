# Step 169: Credential Source UI Wording Admin Smoke Plan

## Step Purpose

Step 169 is a docs-only and planning-only checkpoint for a future human admin
smoke of the Step 167 credential source UI wording implementation.

The purpose is to define target admin screens, observation categories,
forbidden actions, abort conditions, and a status-level observation template
for future Step 170.

Step 169 does not execute browser/admin verification.

Result classification: `Admin smoke plan completed`

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step168-credential-source-ui-wording-source-level-verification-results.md`
- `docs/maturation/step167-credential-source-ui-wording-narrow-production-implementation-results.md`
- `docs/maturation/step166-credential-source-ui-wording-implementation-plan.md`
- `docs/maturation/step165-credential-source-ui-wording-and-status-label-alignment-plan.md`
- `docs/maturation/step162-admin-smoke-instruction-slug-alignment-docs-update-results.md`
- `docs/maturation/step159-missing-credential-ui-path-controlled-local-only-execution-results.md`
- `docs/maturation/step155-human-controlled-credential-source-admin-smoke-results.md`

## Execution Boundary

Step 169 is docs-only and planning-only.

This step did not perform:

- production PHP changes,
- JavaScript, CSS, `readme.txt`, or tools changes,
- admin UI or browser operations,
- temporary guard / mu-plugin / helper / test script creation,
- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
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

Evidence is limited to status-level planning categories and canonical page slug
references.

## Target Admin Pages

Future Step 170 should use the Step 162 canonical admin page slugs:

```text
Settings: page=analytics-report-ai-settings
Report Builder: page=analytics-report-ai
```

Future Step 170 should not use:

```text
page=analytics-report-ai-report-builder
```

Full local URLs are not required for the observation record. If a future smoke
operator needs local navigation, record only the page slug category in the
results unless explicitly scoped otherwise.

## Future Step 170 Human Observation Scope

Future Step 170 should be a human browser observation with status-level results
only.

In scope:

- Settings page load,
- Report Builder page load,
- fatal error absence,
- OAuth-first wording category visibility,
- manual token fallback wording category visibility,
- value-hidden wording category visibility,
- missing credential safe wording category visibility where visible without
  GA4 Fetch,
- no token / option / Authorization header value display,
- no OAuth client ID or client secret value display,
- no GA4 Fetch,
- no OpenAI Generate,
- no OAuth Connect / Authorize,
- no Network evidence,
- no screenshots.

Out of scope:

- clicking Fetch GA4 Data,
- clicking Generate AI Report,
- starting OAuth Connect / Authorize,
- navigating to Google,
- token endpoint communication,
- viewing or copying option values,
- inspecting database rows,
- recording raw URLs, request bodies, raw responses, payloads, or report body
  content.

## Wording Category Normalization

Future Step 170 should normalize visible wording to categories rather than
copying UI text.

Allowed wording categories:

```text
oauth_first_wording_visible
manual_token_mvp_fallback_wording_visible
value_hidden_wording_visible
credential_source_safe_category_label_visible
missing_credential_safe_actionable_wording_visible
oauth_status_level_only_visible
wording_not_visible
unknown_wording_category
```

Do not record full UI paragraphs, screenshots, raw screen contents, credential
fragments, option values, token values, request details, or generated content.

## Forbidden Evidence Boundaries

Future Step 170 must not record:

- token values,
- option values,
- OAuth token option values,
- serialized option values,
- Authorization headers,
- OAuth client ID values,
- OAuth client secret values,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- page path/source/city values,
- request bodies,
- raw responses,
- AI payload JSON,
- generated report body,
- browser Network evidence,
- screenshots,
- database rows or dumps,
- cookies, sessions, or nonces.

## Future Step 170 Observation Template

Use this status-level template for future human observation:

```text
Step 170 credential source UI wording admin smoke observation:
- Settings page loaded: Yes / No
- Report Builder page loaded: Yes / No
- Fatal error observed: No / Yes
- Settings OAuth-first wording category:
  oauth_first_wording_visible / wording_not_visible / unknown_wording_category
- Settings manual token fallback wording category:
  manual_token_mvp_fallback_wording_visible / wording_not_visible / unknown_wording_category
- Settings value-hidden wording category:
  value_hidden_wording_visible / wording_not_visible / unknown_wording_category
- Settings OAuth state remains status-level only: Yes / No / not_visible
- Settings manual token saved/not-saved remains value-hidden: Yes / No / not_visible
- Report Builder credential source safe label category:
  credential_source_safe_category_label_visible / wording_not_visible / unknown_wording_category
- Report Builder OAuth-first wording category:
  oauth_first_wording_visible / wording_not_visible / unknown_wording_category
- Report Builder manual token fallback wording category:
  manual_token_mvp_fallback_wording_visible / wording_not_visible / unknown_wording_category
- Report Builder missing credential safe wording category:
  missing_credential_safe_actionable_wording_visible / wording_not_visible / unknown_wording_category
- Manual token value displayed: No
- OAuth token option value displayed: No
- Authorization header displayed: No
- Plugin settings option value displayed: No
- OAuth client ID value displayed: No
- OAuth client secret value displayed: No
- GA4 Fetch triggered: No
- OpenAI Generate triggered: No
- Browser OAuth triggered: No
- Token endpoint communication occurred: No
- Network evidence recorded: No
- Screenshot recorded: No
- Forbidden evidence recorded: No
```

If a field cannot be observed without leaving the allowed scope, record it as
`not_visible` or `unknown_wording_category` rather than expanding the smoke.

## Abort Conditions

Future Step 170 should stop immediately if any of the following would be
needed or observed:

- token value display,
- option value display,
- OAuth token option value display,
- serialized option value display,
- Authorization header display,
- OAuth client ID or client secret value display,
- raw request / response / payload / generated report recording,
- browser Network evidence collection,
- screenshot recording,
- GA4 Fetch click,
- OpenAI Generate click,
- OAuth Connect / Authorize click,
- Google navigation,
- token endpoint communication,
- full credential or database value inspection.

If an abort condition occurs, record only a status-level abort category and do
not preserve the forbidden evidence.

## Result Classification

Result: `Admin smoke plan completed`

Rationale:

- Target admin page slugs were defined using Step 162 canonical slugs.
- Future human observation scope was limited to page load and wording/status
  categories.
- Wording categories were normalized for safe reporting.
- Forbidden evidence boundaries and abort conditions were defined.
- No production code was changed.
- No browser/admin execution or external action occurred.

WordPress.org release remains `Hold`.

## Notes And Limitations

- Step 169 is a plan only.
- Step 169 does not prove the wording visually appears in a browser.
- Step 169 does not perform admin login or page load.
- Step 169 does not validate GA4 Fetch, OpenAI Generate, OAuth flows, token
  endpoint behavior, Plugin Check, or release readiness.
- Future Step 170 should remain status-level and should not expand into
  external-service testing.

## Recommended Step 170

Recommended next step:

```text
Step 170: Credential source UI wording human admin smoke
```

Recommended Step 170 boundaries:

- human browser observation,
- Settings and Report Builder page load only,
- use Step 162 canonical slugs,
- no GA4 Fetch,
- no OpenAI Generate,
- no OAuth Connect / Authorize,
- no Google navigation,
- no token endpoint communication,
- no Network evidence,
- no screenshots,
- no credential, token, option, request, response, payload, or generated report
  evidence,
- status-level category recording only.

## Commands Executed

Safe docs-only commands:

```bash
git status --short --untracked-files=all
test -f docs/maturation/step169-credential-source-ui-wording-admin-smoke-plan.md && echo "step169_doc_exists"
git diff -- docs/maturation/step169-credential-source-ui-wording-admin-smoke-plan.md
git diff --name-only
git status --short --untracked-files=all
```

Command result summary:

- Step 169 docs file exists.
- Production code was not changed.
- `git diff --name-only` does not include untracked docs until they are staged;
  use `git status --short --untracked-files=all` for the full working tree
  view.
