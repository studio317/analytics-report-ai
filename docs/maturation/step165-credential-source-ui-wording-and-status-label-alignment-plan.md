# Step 165: Credential Source UI Wording And Status Label Alignment Plan

## Step Purpose

Step 165 is a docs-only and planning-only alignment plan for GA4 credential
source UI wording, status labels, and value-hidden posture before any
production code change.

The plan carries forward Step 164:

```text
manual token fallback current MVP maturation status: keep
manual token fallback public release status: unresolved / must re-evaluate before release
OAuth-first precedence remains: Yes
production code change immediate next step: No
```

The goal is to keep OAuth-first wording clear while describing manual Google
Access Token fallback as an MVP maturation fallback, not a final public-release
posture.

Result classification: `Alignment plan completed`

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step164-manual-token-fallback-retirement-decision-checkpoint.md`
- `docs/maturation/step163-oauth-credential-source-remaining-gap-decision-checkpoint.md`
- `docs/maturation/step162-admin-smoke-instruction-slug-alignment-docs-update-results.md`
- `docs/maturation/step159-missing-credential-ui-path-controlled-local-only-execution-results.md`
- `docs/maturation/step155-human-controlled-credential-source-admin-smoke-results.md`
- `docs/maturation/step153-ga4-oauth-credential-source-source-level-verification-results.md`
- `docs/maturation/step152-narrow-ga4-oauth-credential-source-production-implementation-results.md`

## Execution Boundary

Step 165 is docs-only and planning-only.

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

Evidence is limited to status-level categories, wording direction, and
implementation planning.

## Current Credential Source Status Categories

| Category | Meaning | User-facing wording direction | Value-hidden requirement | Immediate implementation required | Notes |
|---|---|---|---|---|---|
| `credential_source_oauth_connected` | A usable OAuth credential source is available and takes precedence. | "Connected via OAuth" / "Google connection is active" style wording. | Do not show access token, refresh token, token metadata, option values, or Authorization headers. | No | Preferred credential source for future public posture. |
| `credential_source_manual_token` | Manual Google Access Token fallback is available or selected after OAuth is not usable. | "Manual token fallback is available during MVP maturation" style wording. | Do not redisplay token value or option value; only saved/not-saved status may be shown. | No | Must not be framed as final public-release posture. |
| `credential_source_missing` | No usable OAuth source or manual fallback is available. | "Google credential is missing. Connect or configure a credential before fetching GA4 data." | Do not expose internals, option names, or stored value state beyond safe status. | No | Should be actionable without revealing values. |
| `credential_source_oauth_refresh_needed` | OAuth credential may need refresh or reconnect before use. | "Google connection needs refresh or reconnect" style wording. | Do not show token expiry details, token values, provider response, or request details. | No | Requires future refresh/reconnect planning before implementation. |
| `credential_source_oauth_error_category` | OAuth source exists but is not usable due to a status-level OAuth category. | "Google connection error category observed" style wording. | Do not show raw provider error, token data, request URL, callback URL, or query string. | No | Keep support/debug evidence category-level. |
| `manual_token_fallback_used` | Runtime selected the manual token fallback because OAuth was not usable. | "Manual token fallback was used for this MVP maturation path" style wording. | Do not show token value, option value, or Authorization header. | No | Should remain a status-level cue, not a prompt to paste tokens into support. |
| `missing_google_credential_category` | Fetch path reached the safe missing Google credential category. | "Missing Google credential" with next action guidance. | Do not expose option values, credential values, or request details. | No | Step 159 observed this path with no external request attempted. |
| `safe_admin_error_category` | Admin-facing error category is safe to share for support/debug. | "Share this status category, not raw credentials or request data." | Only category/status may be copied or reported. | No | Useful for support posture. |
| `unknown_category` | Status category is absent, unexpected, or not mapped yet. | "Unknown credential status category" with non-sensitive guidance. | Do not infer or expose values while diagnosing. | No | Should prompt future taxonomy review, not raw evidence collection. |
| `not_visible` | No credential source status is visible in the current UI context. | "Credential status not shown in this view" if needed. | Do not add hidden value exposure to compensate. | No | May be acceptable depending on screen context. |

## Wording Principles

- Use OAuth-first wording.
- Treat manual token fallback as an MVP maturation fallback, not final
  public-release posture.
- Keep public-release status unresolved until a later release-readiness
  decision explicitly revisits manual token fallback.
- Do not redisplay token values.
- Do not display option values.
- Do not display Authorization headers.
- Keep explanations status-level and category-level.
- Missing credential wording should explain the required action without
  revealing internals.
- Refresh-needed and OAuth error wording should remain status-level only.
- Support/debug evidence should remain category-level.
- Avoid wording that encourages copying credentials, raw request data, raw
  responses, payload JSON, or generated report text into support channels.

## Candidate UI Wording Buckets

These are wording buckets, not final production strings:

| Bucket | Direction | Status |
|---|---|---|
| Connected via OAuth | Communicate that the preferred OAuth credential source is active. | Candidate wording bucket |
| Manual token fallback available / used during MVP maturation | Communicate fallback status without presenting it as recommended public-release posture. | Candidate wording bucket |
| Missing Google credential | Explain that GA4 fetch cannot proceed until a safe credential source is available. | Candidate wording bucket |
| OAuth refresh required / reconnect required | Indicate that OAuth state needs refresh or reconnect without exposing token details. | Candidate wording bucket |
| OAuth connection error category | Present a shareable category, not raw provider details. | Candidate wording bucket |
| Credentials saved but values are hidden | Reinforce value-hidden posture for saved credential states. | Candidate wording bucket |
| Public release posture unresolved / internal maturation note | Clarify that manual token fallback requires re-evaluation before public release. | Internal planning bucket |

Final production copy should be handled in a later implementation plan and
should use translatable WordPress admin strings.

## Settings Page Implications

Settings should move toward these wording directions:

- show OAuth connection state at status level,
- show manual token saved/not-saved state without redisplaying values,
- describe manual token fallback as an MVP maturation fallback rather than a
  recommended long-term or public-release credential model,
- state that public-release posture requires re-evaluation before release,
- avoid showing token values, option values, OAuth client values, request
  details, or Authorization headers,
- avoid asking users to share raw credential material for support,
- keep delete/disconnect/reconnect wording separate from provider-side revoke
  semantics until those lifecycle steps are planned.

## Report Builder Implications

Report Builder should move toward these wording directions:

- show credential source status or missing credential state as a safe category,
- explain missing credential state before or during GA4 Fetch failure without
  exposing internals,
- map missing credential behavior to `missing_google_credential_category` and
  `credential_source_missing`,
- keep GA4 Fetch errors status-level where possible,
- avoid displaying or recording external request data, GA4 raw responses,
  payload JSON, generated report body, credentials, token values, option
  values, or Authorization headers,
- use Step 162 canonical slugs in future smoke instructions:

```text
Report Builder: page=analytics-report-ai
Settings: page=analytics-report-ai-settings
```

## Support / Debug Posture

Support/debug sharing should be limited to:

- status category,
- safe error category,
- page slug category,
- connected/missing/fallback state category,
- fatal yes/no,
- warning label,
- safe admin notice category.

Support/debug sharing should not include:

- credentials,
- access tokens,
- refresh tokens,
- API keys,
- Authorization headers,
- option values,
- OAuth client ID or client secret values,
- request bodies,
- raw GA4 responses,
- raw OpenAI responses,
- AI payload JSON,
- generated report body,
- browser Network evidence,
- screenshots containing sensitive data,
- hostnames/domains,
- GA4 Property ID values,
- analytics values,
- page path/source/city values.

Future smoke instructions should cite Step 162 for canonical page slug usage.

## Recommended Alignment Decision

Recommended alignment:

```text
OAuth-first wording: Yes
manual token fallback label: MVP maturation fallback
missing credential wording: safe actionable category
value-hidden wording: retained
production code change now: No
```

Decision rationale:

- Step 164 keeps manual token fallback during MVP maturation but leaves
  public-release status unresolved.
- OAuth-first wording matches the current source precedence.
- Missing credential behavior has been safely observed as a category.
- Value-hidden posture has already been part of the safety boundary and should
  remain central.
- Public-release wording should be rechecked after the manual token fallback
  public-release posture decision is finalized.

## Future Implementation Candidates

Potential future implementation areas, not implemented in Step 165:

- Settings page wording update,
- Report Builder credential status wording update,
- safe status label taxonomy helper,
- docs/readme privacy wording alignment,
- support/debug wording alignment,
- admin smoke instruction update using Step 162 canonical slug.

Any implementation step should remain narrowly scoped and should not inspect or
display credential values or option values.

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
OAuth-first wording direction: Yes
manual token fallback wording direction: MVP maturation fallback
manual token fallback public-release wording: unresolved / must re-evaluate before release
value-hidden posture retained: Yes
production code change immediate next step: No
real GA4 Fetch immediate next step: No
OpenAI Generate immediate next step: No
OAuth token endpoint immediate next step: No
Plugin Check immediate next step: No
```

## Result Classification

Result: `Alignment plan completed`

Rationale:

- Credential source status categories were mapped to user-facing wording
  directions.
- OAuth-first wording and MVP manual fallback wording were separated.
- Value-hidden posture remains required.
- Future implementation candidates were identified without changing code.
- No production code was changed.
- No external action or forbidden evidence collection occurred.

WordPress.org release remains `Hold`.

## Notes And Limitations

- This step does not inspect live credential or option values.
- This step does not change UI wording in production code.
- This step does not add a taxonomy helper.
- This step does not validate GA4 Fetch, OpenAI Generate, OAuth refresh,
  reconnect, revoke, disconnect, token endpoint behavior, Plugin Check, or
  release readiness.
- Public-release wording remains subject to a later manual token fallback
  public-release posture decision.

## Recommended Step 166

Recommended Step 166:

```text
Step 166: Credential source UI wording implementation plan
```

Recommended scope:

- docs-only,
- planning-only,
- no production code change,
- no browser action,
- no external communication,
- no token endpoint communication,
- no credential or option value inspection,
- status-level category only.

The Step 166 plan should identify exact future production code touchpoints and
copy buckets before any implementation work starts.

## Commands Executed

Safe docs-only commands:

```bash
git status --short --untracked-files=all
test -f docs/maturation/step164-manual-token-fallback-retirement-decision-checkpoint.md && echo "step164_doc_exists"
test -f docs/maturation/step163-oauth-credential-source-remaining-gap-decision-checkpoint.md && echo "step163_doc_exists"
test -f docs/maturation/step159-missing-credential-ui-path-controlled-local-only-execution-results.md && echo "step159_doc_exists"
test -f docs/maturation/step155-human-controlled-credential-source-admin-smoke-results.md && echo "step155_doc_exists"
test -f docs/maturation/step165-credential-source-ui-wording-and-status-label-alignment-plan.md && echo "step165_doc_exists"
git diff -- docs/maturation/step165-credential-source-ui-wording-and-status-label-alignment-plan.md
git diff --name-only
git status --short --untracked-files=all
```

Command result summary:

- Required recent reference docs checked by file existence were present.
- Step 165 docs file exists.
- Production code was not changed.
- `git diff --name-only` does not include untracked docs until they are staged;
  use `git status --short --untracked-files=all` for the full working tree
  view.
