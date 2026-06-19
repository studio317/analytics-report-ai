# Step 225: Readme/Privacy Wording Alignment After Manual Token Retirement Implementation Results

## Step Purpose

Step 225 implements the narrow `readme.txt` wording alignment planned in Step
224 after the Manual Google Access Token fallback retirement track completed
across Steps 216 through 223.

The implementation updates only `readme.txt` and records this results document.
It does not change production PHP, Settings UI, the credential resolver, GA4
client behavior, OpenAI client behavior, `uninstall.php`, tools, JavaScript, or
CSS.

WordPress.org release readiness remains `Hold`.

## Implementation Summary

Implemented:

- updated Google Analytics Data API credential wording to describe Google OAuth
  as the normal GA4 credential source,
- removed the old wording that described manual Google Access Token entry as
  the current MVP developer-verification credential path,
- stated that the retired manual Google Access Token fallback is not a normal
  public-release credential path,
- preserved GA4 external service action/category-level wording,
- aligned Credential Storage and Payload Review wording with current storage
  categories,
- preserved credential non-redisplay and stored credential access-risk wording,
- preserved OpenAI API / Payload Preview / generated report non-storage
  wording,
- preserved support/debug evidence boundaries,
- added readme wording that local-only disconnect is not provider-side revoke,
  refresh execution, OAuth client Settings fallback deletion, OpenAI API key
  deletion, or uninstall-time provider revoke.

No option values, credential values, token values, OAuth client values,
request bodies, raw responses, AI payload JSON, generated report bodies,
screenshots, browser Network evidence, GA4 Property ID values,
hostname/domain values, or analytics values were displayed or recorded.

## Changed Files

| File | Change category |
|---|---|
| `readme.txt` | Readme/privacy/external-services/credential/support wording alignment. |
| `docs/maturation/step225-readme-privacy-wording-alignment-after-manual-token-retirement-implementation-results.md` | Step 225 implementation results. |

## Readme Sections Updated

Updated `readme.txt` sections:

- `External Services / Google Analytics Data API`,
- `Credential Storage and Payload Review`.

Preserved `readme.txt` sections and wording categories:

- `External Services / OpenAI API`,
- structured Payload Preview wording,
- raw AI payload JSON non-exposure wording,
- generated report draft review/edit/copy wording,
- generated report body non-storage wording,
- `Support and Debug Evidence`.

## Google Analytics Data API Wording Alignment

Implemented:

- the data category list now refers to a Google OAuth access token in the
  Authorization header,
- Google OAuth is described as the normal GA4 credential source,
- the retired manual Google Access Token fallback is explicitly not described
  as a normal public-release credential path,
- refresh request execution and provider-side revoke are described as separate
  deferred tracks rather than implemented behavior.

Preserved:

- GA4 requests are sent only when an administrator clicks Fetch GA4 Data,
- Google Analytics Data API service URL,
- GA4 request data categories,
- GA4 response data categories,
- the request body is designed not to include OpenAI API Key, WordPress user
  information, cookies, or IP addresses.

## Credential Storage And Payload Review Wording Alignment

Implemented:

- Google OAuth token data is described as stored in a dedicated plugin-owned
  option,
- OAuth client Settings fallback configuration and the OpenAI API Key are
  described as plugin settings categories,
- credential values are still described as not redisplayed in the admin screen,
- database administrators, backups, server administrators, or code that can
  read WordPress options are still described as potentially able to access
  stored credential categories,
- OpenAI API key storage and OAuth client Settings fallback storage are still
  described as separate public-release decisions, not release-ready outcomes.

Preserved:

- the plugin does not send the full raw GA4 response to OpenAI,
- selected GA4 results are formatted into report-generation data,
- structured Payload Preview is shown before AI generation,
- reviewed report data is sent only when Generate AI Report is clicked,
- normal admin UI does not expose a full raw AI payload JSON preview,
- reviewed report data is stored temporarily in a user-scoped transient,
- payload validation runs before transient storage and again before OpenAI
  generation.

## Support/debug Evidence Wording Alignment

Preserved:

- support requests should not include credentials,
- support requests should not include API keys,
- support requests should not include access tokens,
- support requests should not include Authorization headers,
- support requests should not include plugin settings option values,
- support requests should not include raw payloads,
- support requests should not include raw API responses,
- support requests should not include OpenAI request bodies,
- support requests should not include generated report text,
- support requests should not include GA4 property identifiers, hostnames, page
  paths, traffic source values, city values, or analytics metric values,
- support guidance remains status-level: screen, action, warning message,
  generic error category, generation allowed/blocked state, or redacted UI
  state.

The readme does not present the retired manual Google Access Token fallback as
a support or recovery path.

## Generated Report / Payload Wording Preservation

Preserved:

- OpenAI API requests are sent only when an administrator clicks Generate AI
  Report,
- structured Payload Preview remains the pre-send review concept,
- normal admin UI does not expose a full raw AI payload JSON preview,
- report data sent to OpenAI is described as selected GA4-derived report data,
- generated report text is shown for user review, editing, and copying,
- the plugin does not save generated report text,
- generated report text is a draft that users should review and edit before
  publishing, sharing, or sending,
- generated report text is not requested as support evidence.

## Disconnect / Revoke / Uninstall Wording Boundary

Implemented:

- local-only Google OAuth disconnect is described as deleting local OAuth token
  data only,
- local-only disconnect is not described as provider-side revoke,
- local-only disconnect is not described as refresh request execution,
- local-only disconnect is not described as deleting OAuth client Settings
  fallback values,
- local-only disconnect is not described as deleting the OpenAI API key,
- plugin uninstall cleanup is described as a separate plugin-owned option
  cleanup boundary,
- plugin uninstall cleanup is not described as provider-side revoke.

## Explicit Unchanged Boundaries

Unchanged in Step 225:

- production PHP,
- Settings UI,
- credential resolver,
- GA4 client,
- OpenAI client,
- `uninstall.php`,
- tools and build scripts,
- JavaScript,
- CSS,
- GA4 request construction,
- OpenAI request construction,
- OAuth token endpoint behavior,
- refresh request behavior,
- provider-side revoke behavior,
- local-only disconnect implementation,
- uninstall cleanup implementation,
- generated report persistence behavior.

## Forbidden Evidence Boundary

Step 225 did not display, inspect, or record:

- option values,
- credential values,
- API keys,
- access token values,
- refresh token values,
- OAuth client ID values,
- OAuth client secret values,
- Authorization headers,
- serialized option values,
- database row contents,
- request bodies,
- raw responses,
- AI payload JSON,
- generated report bodies,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces,
- GA4 Property ID values,
- hostname/domain values,
- analytics values.

## Commands Executed

Commands executed:

```bash
rg -n "Manual Google Access Token|manual Google|access token|Google OAuth|OAuth|privacy|external service|credential|token|OpenAI|GA4|Google Analytics|report body|payload|raw JSON|support|revoke|refresh|disconnect|uninstall" readme.txt
git diff --check
git diff --name-only
git diff --name-only -- analytics-report-ai.php uninstall.php tools assets includes
git status --short --untracked-files=all
```

Command result categories:

- readme wording search: section/wording-category-level review only,
- `git diff --check`: Pass,
- `git diff --name-only`: `readme.txt`,
- unchanged boundary diff check: Pass for production PHP, `uninstall.php`,
  tools, assets, and includes.

## Acceptance Criteria

| Criterion | Result |
|---|---|
| `readme.txt` does not describe manual Google Access Token fallback as a normal public-release path | Pass |
| Google OAuth is described as the normal GA4 credential source | Pass |
| Credential storage wording avoids overclaiming release readiness | Pass |
| OpenAI API key storage posture remains Hold / Separate track | Pass |
| OAuth client Settings fallback posture remains Hold / Separate track | Pass |
| Provider-side revoke is not implied as implemented | Pass |
| Refresh request execution is not implied as implemented | Pass |
| Generated report body non-storage wording is preserved | Pass |
| Raw payload / raw response / generated report support evidence boundary is preserved | Pass |
| GA4 / OpenAI external services wording remains action/category-level | Pass |
| Production PHP / Settings UI / resolver / GA4 client / OpenAI client / uninstall / tools / JS / CSS unchanged | Pass |

## Result Classification

```text
Readme/privacy wording alignment after manual fallback retirement narrow implementation: Completed
Readme changes: Implemented
Manual Google Access Token fallback as normal public path: Removed / Not described
OAuth credential source wording: Implemented as normal GA4 credential source
Credential/support evidence wording: Status/category-level only / Preserved
Generated report body storage wording: Non-storage posture preserved
Provider-side revoke / refresh wording: Separate track / No implementation implied
OpenAI API key storage posture: Hold / Separate track
OAuth client Settings fallback posture: Hold / Separate track
Production PHP changes: None
WordPress.org release readiness: Hold
```

## Recommended Next Step

Recommended next step:

```text
Step 226: Readme/privacy wording alignment after manual fallback retirement source-level verification
```

Step 226 should verify `readme.txt` at source/section/wording-category level
and confirm that production PHP, Settings UI, the credential resolver, GA4
client, OpenAI client, `uninstall.php`, tools, JavaScript, and CSS remain
unchanged.
