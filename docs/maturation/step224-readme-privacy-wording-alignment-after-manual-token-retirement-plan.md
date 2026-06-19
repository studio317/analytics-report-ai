# Step 224: Readme/Privacy Wording Alignment After Manual Token Retirement Plan

## Step Purpose

Step 224 is a docs-only and planning-only implementation plan for aligning
`readme.txt` and privacy / external services / credential wording after the
Manual Google Access Token fallback retirement completed across Steps 216
through 223.

This step inventories current readme wording categories, identifies manual
fallback wording that needs alignment, and defines a narrow Step 225 scope for
updating `readme.txt`.

No `readme.txt` or production code changes are implemented in Step 224.

WordPress.org release readiness remains `Hold`.

## Scope

In scope:

- source-level / wording-level inventory of current `readme.txt`,
- identify manual Google Access Token fallback wording that needs alignment,
- plan OAuth-first GA4 credential source wording,
- plan credential and support evidence wording alignment,
- plan GA4 / OpenAI external services wording alignment,
- plan generated report / payload wording alignment,
- plan disconnect / revoke / uninstall wording boundaries,
- define narrow Step 225 `readme.txt` implementation scope,
- define Step 226 source-level verification focus.

Out of scope:

- changing `readme.txt`,
- changing production code,
- changing Settings UI,
- changing the credential resolver,
- changing GA4 client behavior,
- changing OpenAI client behavior,
- changing `uninstall.php`,
- changing tools, JavaScript, or CSS,
- running browser smoke or external API checks,
- release-readiness approval.

## Explicit Non-goals

Step 224 does not:

- change production code,
- change `readme.txt`,
- change Settings UI,
- change the credential resolver,
- change GA4 client behavior,
- change OpenAI client behavior,
- change `uninstall.php`,
- change tools or build scripts,
- change JavaScript or CSS,
- run Plugin Check,
- run GA4 Fetch,
- run OpenAI Generate,
- start OAuth Connect / Authorize,
- navigate to Google,
- call the token endpoint,
- execute refresh requests,
- execute revoke requests,
- run browser admin smoke,
- execute plugin uninstall,
- collect screenshots,
- collect browser Network evidence,
- inspect database dumps,
- run `wp option get` for plugin option values,
- inspect option values,
- inspect token values,
- inspect credential values,
- inspect OAuth client values,
- inspect request bodies,
- inspect raw responses,
- inspect AI payload JSON,
- inspect generated report bodies.

## Referenced Prior Steps

- `docs/maturation/step223-manual-google-access-token-fallback-retirement-maturation-checkpoint.md`
- `docs/maturation/step222-manual-google-access-token-fallback-retirement-controlled-human-admin-smoke-results.md`
- `docs/maturation/step221-manual-google-access-token-fallback-retirement-human-admin-smoke-plan.md`
- `docs/maturation/step220-manual-google-access-token-fallback-retirement-source-level-verification-results.md`
- `docs/maturation/step219-manual-google-access-token-fallback-retirement-narrow-production-implementation-results.md`
- `docs/maturation/step218-manual-google-access-token-fallback-retirement-implementation-plan.md`
- `docs/maturation/step217-manual-google-access-token-fallback-public-release-decision-checkpoint.md`
- `docs/maturation/step216-manual-google-access-token-fallback-retirement-plan.md`
- `docs/maturation/step209-credential-storage-public-release-posture-checkpoint.md`
- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`

## Current Readme/Privacy Wording Inventory

Inventory method:

- reviewed `readme.txt` headings and relevant wording categories,
- searched only file/section/wording categories,
- did not inspect or record option values, credential values, token values,
  request bodies, raw responses, analytics values, screenshots, browser
  Network evidence, or generated report bodies.

Current relevant `readme.txt` sections:

| Section | Current wording category | Alignment status |
|---|---|---|
| `Description` | Describes GA4 data review, OpenAI draft generation, editing, and copying. | Mostly aligned. |
| `External Services / Google Analytics Data API` | Lists Google Analytics Data API usage and data categories sent to Google. | Needs alignment for credential wording after manual fallback retirement. |
| `External Services / Google Analytics Data API` | Still describes manual Google Access Token entry as the current MVP developer-verification path. | Needs alignment / Planned. |
| `External Services / OpenAI API` | Describes OpenAI API usage, reviewed report data, structured Payload Preview, raw JSON non-exposure, and generated draft handling. | Mostly aligned; preserve current payload/report boundaries. |
| `Credential Storage and Payload Review` | Describes Google Access Token and OpenAI API Key as saved plugin settings. | Needs alignment after manual fallback retirement. |
| `Credential Storage and Payload Review` | Describes credential non-redisplay and database/backups/server/code access risk. | Keep and adapt to current credential categories. |
| `Support and Debug Evidence` | Says support should not include credentials, tokens, raw payloads, raw responses, generated report text, identifiers, hostnames, paths, sources, cities, or analytics values. | Aligned; preserve. |

Current readme wording that needs Step 225 alignment:

- Manual Google Access Token fallback as current MVP credential entry path.
- Credential storage wording that still treats Google Access Token and OpenAI
  API Key as the main saved plugin settings pair.
- Any wording that could imply manual access token fallback remains a normal
  public-release path.

## Manual Fallback Retirement Alignment Needs

Step 225 should align `readme.txt` with the Step 223 conclusion:

```text
Manual fallback public-release decision: Full retirement before public release / Matured
Manual fallback Settings UI retirement: Matured within current MVP boundary
Manual fallback resolver retirement: Matured within current MVP boundary
Saved manual fallback value handling: Settings save value-free cleanup / Matured within current MVP boundary
OAuth credential source: Preferred / normal GA4 credential source
WordPress.org release readiness: Hold
```

Planned alignment:

- remove or rewrite readme wording that says the current MVP uses manual Google
  Access Token entry,
- do not describe manual Google Access Token fallback as a normal
  public-release path,
- describe Google OAuth as the normal GA4 credential source,
- keep status/category-level language for support and debugging,
- keep public-release readiness on `Hold`,
- avoid claiming that refresh requests or provider-side revoke are implemented.

## OAuth-first Wording Plan

Step 225 should update readme wording so that:

- Google OAuth is described as the normal GA4 credential source,
- the GA4 request still uses a Google credential in the Authorization header,
  but the readme should not frame manual access token entry as the normal way
  that credential is supplied,
- reconnect / refresh-deferred language should remain careful and
  status/category-level,
- provider-side revoke and refresh request execution should not be implied as
  implemented,
- OAuth token storage should not be over-described as release-ready.

Planned wording category:

```text
OAuth credential source wording: Planned as normal GA4 credential source
Manual fallback as normal public path: Not to be described
```

## External Services Wording Plan

Google Analytics Data API:

- keep the explanation that requests are sent only when an administrator starts
  GA4 data retrieval,
- keep the service URL,
- keep the data category list for selected GA4 request inputs,
- keep the statement that the Google Analytics Data API request body is not
  designed to include OpenAI API Key, WordPress user information, cookies, or
  IP addresses,
- update credential wording to reflect OAuth-first behavior after manual
  fallback retirement.

OpenAI API:

- keep the explanation that requests are sent only when an administrator starts
  AI report generation,
- keep the service URL,
- keep the reviewed report data category list,
- keep structured Payload Preview wording,
- keep the normal admin UI raw JSON non-exposure wording,
- keep generated report draft review/edit/copy wording,
- keep the statement that the plugin does not save generated report text.

## Credential And Support Evidence Wording Plan

Step 225 should keep support/debug wording strict:

- support should not ask for credentials,
- support should not ask for API keys,
- support should not ask for access tokens or refresh tokens,
- support should not ask for Authorization headers,
- support should not ask for plugin settings option values,
- support should not ask for raw payloads, raw API responses, OpenAI request
  bodies, generated report text, GA4 property identifiers, hostnames, page
  paths, traffic source values, city values, or analytics metric values,
- support should use status-level information such as screen, action, warning
  message, generic error category, generation allowed/blocked state, or
  redacted UI state.

Credential storage wording should be aligned without overclaiming:

- manual Google Access Token fallback is retired from normal public-release
  behavior,
- Google OAuth token storage exists as a dedicated plugin-owned storage
  category,
- OAuth client Settings fallback posture remains `Hold / Separate track`,
- OpenAI API key storage posture remains `Hold / Separate track`,
- credential values should not be redisplayed in admin UI,
- database administrators, backups, server administrators, or code that can
  read WordPress options may still have access to stored credential categories.

## Generated Report / Payload Wording Plan

Step 225 should preserve existing payload/report boundaries:

- the plugin does not send the full raw GA4 response to OpenAI,
- selected GA4 results are formatted into report-generation data,
- structured Payload Preview remains the pre-send review concept,
- the normal admin UI does not expose a full raw AI payload JSON preview,
- reviewed report data is stored temporarily in a user-scoped transient and
  expires automatically,
- payload validation runs before transient storage and again before OpenAI
  generation,
- generated report text is shown for user review, editing, and copying,
- the plugin does not save generated report text,
- generated report text should be reviewed and edited before publishing,
  sharing, or sending,
- raw AI payload JSON, request bodies, raw responses, and generated report body
  should not be requested as support evidence.

## Disconnect / Revoke / Uninstall Wording Boundary

Step 225 should avoid conflating separate lifecycle boundaries:

- local-only disconnect deletes only local OAuth token data,
- local-only disconnect is not provider-side revoke,
- provider-side revoke remains a separate deferred track,
- refresh request execution remains a separate deferred track,
- uninstall cleanup is separate from local disconnect and Settings save cleanup,
- uninstall cleanup covers deterministic plugin-owned options in its current
  narrow track but does not imply provider-side revoke,
- readme wording should not imply token endpoint refresh, provider revoke, or
  uninstall-time revoke behavior has been implemented.

## Public Release Boundary

Step 224 does not make the plugin release-ready.

Reasons:

- `readme.txt` changes are not implemented in Step 224,
- OpenAI API key storage posture remains `Hold / Separate track`,
- OAuth client Settings fallback posture remains `Hold / Separate track`,
- provider-side revoke and refresh request execution remain
  `Deferred / Separate track`,
- final credential storage posture remains unresolved,
- Plugin Check, release package review, and final release-readiness checks are
  outside this step.

WordPress.org release readiness remains `Hold`.

## Proposed Step 225 Implementation Scope

Recommended Step 225:

```text
Step 225: Readme/privacy wording alignment after manual fallback retirement narrow implementation
```

Narrow Step 225 scope:

- change only `readme.txt`,
- update Google Analytics Data API credential wording to OAuth-first,
- remove or rewrite manual Google Access Token fallback as the current MVP
  credential entry path,
- update Credential Storage and Payload Review wording to distinguish:
  - Google OAuth token storage category,
  - OAuth client Settings fallback category,
  - OpenAI API key storage category,
  - credential non-redisplay posture,
  - remaining public-release holds,
- preserve GA4 / OpenAI external service disclosures,
- preserve payload/raw JSON/support evidence boundaries,
- preserve generated report body non-storage wording,
- avoid claiming provider-side revoke or refresh request execution,
- avoid changing production PHP, Settings UI, resolver, GA4 client, OpenAI
  client, `uninstall.php`, tools, JavaScript, or CSS.

Out of scope for Step 225:

- production PHP changes,
- Settings UI changes,
- credential resolver changes,
- GA4 / OpenAI behavior changes,
- uninstall cleanup changes,
- Plugin Check,
- external API communication,
- browser admin smoke.

## Source-level Verification Plan For Step 226

Recommended Step 226:

```text
Step 226: Readme/privacy wording alignment after manual fallback retirement source-level verification
```

Step 226 should verify:

- `readme.txt` no longer describes manual Google Access Token fallback as a
  normal public-release path,
- OAuth is described as the normal GA4 credential source,
- credential storage wording does not overclaim release readiness,
- OpenAI API key storage posture remains clear and cautious,
- OAuth client Settings fallback posture is not overclaimed,
- provider-side revoke and refresh request execution are not implied as
  implemented,
- generated report body non-storage wording is preserved,
- raw payload / raw response / generated report support evidence boundaries are
  preserved,
- GA4 / OpenAI external services wording remains accurate at
  action/category-level,
- production PHP, Settings UI, resolver, GA4 client, OpenAI client,
  `uninstall.php`, tools, JavaScript, and CSS are unchanged.

Verification should use only source/section/wording-category evidence and
command result categories. It must not record option values, credential values,
token values, request bodies, raw responses, AI payload JSON, generated report
bodies, screenshots, browser Network evidence, GA4 Property ID values,
hostname/domain values, or analytics values.

## Recommended Next Step

Recommended next step:

```text
Step 225: Readme/privacy wording alignment after manual fallback retirement narrow implementation
```

Step 225 should update only `readme.txt` according to this plan.

## Result Classification

```text
Readme/privacy wording alignment plan after manual fallback retirement: Completed
Manual Google Access Token fallback readme wording: Needs alignment / Planned
OAuth credential source wording: Planned as normal GA4 credential source
Manual fallback as normal public path: Not to be described
Credential/support evidence wording: Status/category-level only / Planned
Generated report body storage wording: Preserve non-storage posture
Provider-side revoke / refresh wording: Separate track / Do not imply implementation
OpenAI API key storage posture: Hold / Separate track
OAuth client Settings fallback posture: Hold / Separate track
Production code changes: Not implemented in Step 224
Readme changes: Not implemented in Step 224
WordPress.org release readiness: Hold
```
