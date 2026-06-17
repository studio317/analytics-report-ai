# Step 166: Credential Source UI Wording Implementation Plan

## Step Purpose

Step 166 is a docs-only and planning-only implementation plan for applying the
Step 165 credential source UI wording and status label alignment to a future
narrow production change.

This step does not change production code. It defines future touchpoints, copy
buckets, safety boundaries, and verification scope before any Step 167
implementation.

Result classification: `Implementation plan completed`

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step165-credential-source-ui-wording-and-status-label-alignment-plan.md`
- `docs/maturation/step164-manual-token-fallback-retirement-decision-checkpoint.md`
- `docs/maturation/step163-oauth-credential-source-remaining-gap-decision-checkpoint.md`
- `docs/maturation/step162-admin-smoke-instruction-slug-alignment-docs-update-results.md`
- `docs/maturation/step159-missing-credential-ui-path-controlled-local-only-execution-results.md`
- `docs/maturation/step155-human-controlled-credential-source-admin-smoke-results.md`
- `docs/maturation/step153-ga4-oauth-credential-source-source-level-verification-results.md`
- `docs/maturation/step152-narrow-ga4-oauth-credential-source-production-implementation-results.md`

## Execution Boundary

Step 166 is docs-only and planning-only.

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

Evidence is limited to source symbol names, file responsibilities, status-level
categories, and planning notes.

## Source Touchpoint Inventory

Future implementation touchpoints identified at source/responsibility level:

| File | Responsibility | Future wording relevance | Step 167 target status |
|---|---|---|---|
| `includes/class-settings.php` | Registers, sanitizes, and renders admin Settings fields, credential notices, OAuth connection status, and manual Google Access Token status. | Primary place for OAuth-first wording, manual token fallback wording, value-hidden reminders, and public-release re-evaluation note placement. | Candidate target |
| `includes/class-report-builder.php` | Renders Report Builder, handles GA4 fetch flow, displays admin notices/errors, payload preview, and generated report area. | Primary place for missing credential safe category wording and GA4 Fetch credential status guidance. | Candidate target |
| `includes/functions-utils.php` | Contains helper functions, default settings helpers, OAuth token state helper, and GA4 credential source resolver. | Candidate location only if a safe status label taxonomy helper is needed later. Step 167 should avoid behavior changes here unless strictly limited to display mapping. | Defer unless needed |
| `includes/class-admin.php` | Registers admin menus, enqueues admin assets, and contains admin action wiring. | Generally not needed for copy-only credential source wording, except if future admin notices or localized strings require it. | Not primary target |
| `analytics-report-ai.php` | Defines plugin constants and bootstraps the plugin. | No credential source wording implementation expected. | Not target |

Source review for Step 166 was limited to symbol, category, and responsibility
level. It did not inspect or record credential values, option values, token
values, Authorization headers, request bodies, raw responses, payloads, or
generated reports.

## Implementation Candidate Boundaries

### Settings Page Wording Update

Candidate boundaries:

- display OAuth connection state at status level,
- describe OAuth as the preferred credential source,
- keep manual token saved/not-saved display value-hidden,
- describe manual token fallback as an MVP maturation fallback,
- avoid presenting manual token fallback as final public-release posture,
- decide in implementation whether the public-release re-evaluation note is
  shown in UI or kept as internal maturity wording,
- do not change settings save logic,
- do not change credential storage,
- do not redisplay token, API key, option value, OAuth client value, or
  Authorization header content.

### Report Builder Credential Status Wording Update

Candidate boundaries:

- show missing credential as a safe actionable category,
- use or align wording around `missing_google_credential_category` and
  `credential_source_missing`,
- explain credential state before or after a GA4 Fetch failure without exposing
  internals,
- do not change GA4 Fetch behavior,
- do not add a real credential check,
- do not change payload generation, transient behavior, or OpenAI behavior,
- do not display request bodies, raw responses, payload JSON, generated report
  body, token values, option values, or Authorization headers.

### Safe Status Label Taxonomy Helper

Candidate boundaries:

- evaluate whether status categories should be mapped in one helper,
- keep mapping output display-safe and translatable,
- avoid returning raw provider errors, option details, token metadata, or
  request details,
- do not implement in Step 166,
- consider deferring until after a narrow wording-only implementation proves the
  actual duplication.

### Support / Debug Wording Alignment

Candidate boundaries:

- state that support evidence should use category-level status only,
- allow safe categories such as credential source state, page slug, warning
  label, fatal yes/no, and safe admin error category,
- do not ask users to share credentials, token values, option values,
  Authorization headers, request bodies, raw responses, payload JSON, browser
  Network evidence, screenshots, or generated report body.

### Future Readme / Privacy Wording Alignment

Candidate boundaries:

- keep this as a future candidate until public-release credential posture is
  clearer,
- do not update `readme.txt` in Step 166 or the first narrow implementation
  unless explicitly scoped later,
- revisit after manual token fallback public-release posture is decided.

## Candidate Production Copy Buckets

These buckets are planning labels, not final production strings.

| Copy bucket | Direction | Future i18n expectation |
|---|---|---|
| OAuth connected / preferred source | Explain that OAuth is the preferred Google credential source when connected. | Use WordPress translation helpers such as `__()` or `esc_html__()` with text domain `analytics-report-ai`. |
| Manual token fallback during MVP maturation | Explain that manual token fallback remains for MVP maturation, not final public-release posture. | Use translatable admin text and escaped output. |
| Missing Google credential | Give safe action guidance without exposing option names or internals. | Use translatable admin text and escaped output. |
| OAuth refresh or reconnect needed | Indicate status-level recovery need without token details. | Use translatable admin text and escaped output. |
| OAuth error category | Present category-level error status, not raw provider details. | Use translatable admin text and escaped output. |
| Credentials saved but hidden | Reinforce value-hidden posture for saved credentials. | Use translatable admin text and escaped output. |
| Unknown credential status | Provide safe fallback wording for unmapped category. | Use translatable admin text and escaped output. |
| Safe support/debug category | Tell users to share category/status labels only. | Use translatable admin text and escaped output. |

Japanese admin UI wording may be introduced in a later step if explicitly
scoped. Any production strings should remain translation-ready and safely
escaped.

## Recommended Implementation Scope For Step 167

Recommended Step 167 scope:

```text
Option A: Step 167: Credential source UI wording narrow production implementation
```

Reason:

- The next useful change is likely copy-only and user-facing.
- Existing Settings and Report Builder surfaces already contain credential
  status and safety wording areas.
- A narrow wording implementation can improve clarity without introducing new
  storage, resolver behavior, OAuth lifecycle behavior, or token endpoint
  behavior.
- A taxonomy helper can wait until real duplication or mapping complexity is
  visible.

Recommended Step 167 boundaries:

- adjust existing Settings / Report Builder display wording only,
- keep all saved credential values hidden,
- keep OAuth-first wording,
- label manual token fallback as MVP maturation fallback,
- make missing credential wording safe and actionable,
- do not change settings save logic,
- do not change OAuth token option behavior,
- do not change GA4 client behavior,
- do not change OpenAI behavior,
- do not change payload/transient behavior,
- do not add refresh/reconnect/revoke/disconnect behavior.

Alternative:

```text
Option B: Step 167: Credential source status label taxonomy helper implementation
```

Option B remains available if the implementation review finds duplicated status
label mapping that would be safer to centralize before UI copy changes.

## Future Verification Plan

Future Step 167 or later implementation should verify:

- PHP syntax check for touched PHP files,
- full `find includes -name '*.php' -print0 | xargs -0 -n1 php -l` if any PHP
  changes are made,
- `git diff --check`,
- source-level grep for forbidden value display patterns around credential
  source UI,
- source-level grep for translatable strings and safe escaping,
- admin smoke page load only, if browser verification is explicitly scoped,
- canonical slugs from Step 162:

```text
Report Builder: page=analytics-report-ai
Settings: page=analytics-report-ai-settings
```

Immediate implementation verification should not include:

- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- token endpoint communication,
- Plugin Check unless separately scoped,
- screenshots,
- browser Network evidence,
- credential or option value inspection.

Observations should remain status-level only.

## Non-Goals

Step 166 and the recommended future narrow implementation do not include:

- OAuth refresh implementation,
- reconnect implementation,
- revoke implementation,
- disconnect implementation,
- uninstall cleanup,
- manual token retirement implementation,
- credential storage redesign,
- real GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- token endpoint communication,
- Plugin Check,
- public release readiness decision.

## Safety Boundary For Future Steps

Future steps should preserve these boundaries unless a later step explicitly
narrows and authorizes otherwise:

- no credential value inspection,
- no option value inspection,
- no token endpoint communication,
- no real GA4 request,
- no OpenAI request,
- no OAuth Connect / Authorize,
- no Authorization header inspection,
- no browser Network evidence,
- no screenshots,
- no request body recording,
- no raw response recording,
- no AI payload JSON recording,
- no generated report body recording,
- status-level category only.

## Decision Output

```text
WordPress.org release remains: Hold
Step 166 production code change: No
OAuth-first wording direction retained: Yes
manual token fallback wording direction: MVP maturation fallback
value-hidden posture retained: Yes
recommended Step 167 scope: Option A
real GA4 Fetch immediate next step: No
OpenAI Generate immediate next step: No
OAuth token endpoint immediate next step: No
Plugin Check immediate next step: No
```

## Result Classification

Result: `Implementation plan completed`

Rationale:

- Source touchpoints were identified at responsibility level.
- Candidate implementation boundaries were separated from non-goals.
- Candidate copy buckets were defined without final production string changes.
- Recommended Step 167 scope was selected as narrow UI wording implementation.
- No production code was changed.
- No external action or forbidden evidence collection occurred.

WordPress.org release remains `Hold`.

## Notes And Limitations

- This step does not inspect live credential or option values.
- This step does not change production UI wording.
- This step does not decide public-release credential posture.
- This step does not validate runtime admin display in a browser.
- This step does not validate GA4 Fetch, OpenAI Generate, OAuth lifecycle,
  token endpoint behavior, Plugin Check, or release readiness.

## Recommended Step 167

Recommended Step 167:

```text
Step 167: Credential source UI wording narrow production implementation
```

Recommended scope:

- production PHP wording only,
- likely limited to existing Settings and Report Builder UI copy,
- no JS, CSS, readme, tools, storage, resolver, GA4 client, OpenAI client,
  payload, transient, or OAuth lifecycle behavior changes,
- translation-ready strings,
- escaped output,
- value-hidden posture retained,
- status-level category wording only.

## Commands Executed

Safe docs-only and source-symbol commands:

```bash
git status --short --untracked-files=all
rg -n "credential_source|manual_token|oauth|Google Access Token|missing_google|settings|report" includes analytics-report-ai.php docs/maturation/step165-credential-source-ui-wording-and-status-label-alignment-plan.md
test -f docs/maturation/step166-credential-source-ui-wording-implementation-plan.md && echo "step166_doc_exists"
git diff -- docs/maturation/step166-credential-source-ui-wording-implementation-plan.md
git diff --name-only
git status --short --untracked-files=all
```

Command result summary:

- The source-symbol review identified Settings, Report Builder, utility helper,
  admin bootstrap, and plugin bootstrap touchpoints at responsibility level.
- Step 166 docs file exists.
- Production code was not changed.
- `git diff --name-only` does not include untracked docs until they are staged;
  use `git status --short --untracked-files=all` for the full working tree
  view.
