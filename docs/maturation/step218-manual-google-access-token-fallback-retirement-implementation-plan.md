# Step 218: Manual Google Access Token Fallback Retirement Implementation Plan

## Step Purpose

Step 218 is a docs-only and planning-only narrow implementation plan for full
retirement of the manual Google Access Token fallback before public release.

Step 217 selected full retirement before public release. This step translates
that decision into an implementation plan for Settings UI, credential resolver,
saved fallback handling, Report Builder wording, support/debug wording, and
future verification.

No production code is changed in this step.

WordPress.org release readiness remains `Hold`.

## Scope

In scope:

- normal Settings UI retirement plan for the manual Google Access Token field,
- Manual Google Access Token Status row handling,
- manual fallback delete checkbox handling,
- credential resolver manual fallback branch retirement plan,
- status/category path retirement for normal public-release behavior,
- saved manual fallback value handling plan,
- Settings save sanitization impact,
- default settings structure impact,
- Report Builder wording impact,
- support/debug and readme/privacy wording impact,
- local-only disconnect and uninstall cleanup boundaries,
- GA4 client and OAuth boundary,
- source-level verification plan,
- human admin smoke planning need,
- Step 219 acceptance criteria.

Out of scope:

- production implementation,
- Settings UI changes,
- credential resolver changes,
- GA4 client changes,
- OpenAI client changes,
- readme changes,
- option value inspection,
- database inspection,
- browser smoke,
- external API calls,
- release-readiness approval.

## Explicit Non-goals

Step 218 does not:

- change production code,
- change `uninstall.php`,
- change `readme.txt`,
- change tools or build scripts,
- change JavaScript or CSS,
- change Settings UI,
- change the credential resolver,
- change GA4 client behavior,
- change OpenAI client behavior,
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

- `docs/maturation/step217-manual-google-access-token-fallback-public-release-decision-checkpoint.md`
- `docs/maturation/step216-manual-google-access-token-fallback-retirement-plan.md`
- `docs/maturation/step215-uninstall-cleanup-maturation-checkpoint.md`
- `docs/maturation/step209-credential-storage-public-release-posture-checkpoint.md`
- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`
- `docs/maturation/step164-manual-token-fallback-retirement-decision-checkpoint.md`

## Implementation Decision Inputs From Step 217

Step 217 selected:

```text
Manual Google Access Token fallback public-release decision: Full retirement before public release
OAuth credential source: Preferred / normal GA4 credential source
Manual fallback in normal Settings UI: Remove or hide before public release
Manual fallback in credential resolver: Remove before public release
Saved manual fallback value handling: Needs narrow implementation plan
Developer-only fallback: Deferred unless explicitly required
Keep visible but discouraged: Rejected / Not recommended
No change: Rejected / Not recommended
Production code changes: Not implemented in Step 217
WordPress.org release readiness: Hold
```

Step 218 accepts this direction and plans a narrow retirement implementation
that removes the manual fallback from normal public-release behavior while
preserving OAuth as the preferred GA4 credential source.

## Current Implementation Areas

| Area | Source-level reference | Current role |
|---|---|---|
| Default settings structure | `includes/functions-utils.php`, `analytics_report_ai_get_default_settings()` | Defines the `google_tokens` settings category. |
| Settings read normalization | `includes/functions-utils.php`, `analytics_report_ai_get_settings()` | Normalizes `google_tokens['access_token']` when present. |
| Settings save sanitization | `includes/class-settings.php`, `sanitize_settings()` | Accepts `google_access_token`, keeps empty input, and supports `clear_google_access_token`. |
| Settings UI status row | `includes/class-settings.php` | Displays `Manual Google Access Token Status` as value-hidden saved/not-saved state. |
| Settings UI field | `includes/class-settings.php` | Displays the `Manual Google Access Token` password field with empty `value`. |
| Settings delete checkbox | `includes/class-settings.php` | Displays a delete checkbox when a saved manual fallback is present. |
| Credential resolver | `includes/functions-utils.php`, `analytics_report_ai_resolve_google_ga4_credential_source()` | Can return `credential_source_manual_token` or `manual_token_fallback_used` and a request-local access token. |
| Report Builder wording | `includes/class-report-builder.php` | Mentions the manual fallback in credential source labels and error guidance. |
| Local-only disconnect wording | `includes/class-admin.php`, `includes/class-settings.php` | States local OAuth disconnect does not delete the manual fallback. |
| Uninstall cleanup | `uninstall.php` | Deletes the whole main settings option on plugin uninstall. |

This plan records only source file names, function names, option key names, UI
field names, credential source categories, and storage categories. It does not
inspect or record option values, credential values, token values, OAuth client
values, serialized option values, database rows, request bodies, raw responses,
AI payload JSON, generated report bodies, screenshots, browser Network
evidence, GA4 Property ID values, hostname/domain values, or analytics values.

## Proposed Settings UI Changes

Planned direction:

```text
Manual fallback Settings UI retirement: Planned
Manual fallback field: Remove from normal Settings UI
Manual fallback status row: Remove from normal Settings UI
Manual fallback delete checkbox: Remove from normal Settings UI unless a cleanup-only control is explicitly retained
```

Step 219 should plan to remove or hide these normal Settings UI elements:

- `Manual Google Access Token Status` row,
- `Manual Google Access Token` password field,
- saved/not-saved placeholder and description for the manual fallback,
- `clear_google_access_token` checkbox from normal public-release UI.

If saved fallback cleanup cannot be completed safely during Settings save,
Step 219 may temporarily retain a value-hidden cleanup-only control. The
preferred narrow implementation, however, is Settings save value-free cleanup,
so a separate cleanup-only control should not be required unless source-level
review finds a safe-save limitation.

Future Settings wording should present Google OAuth as the GA4 credential
source and should not invite manual access token entry.

## Proposed Credential Resolver Changes

Planned direction:

```text
Manual fallback resolver retirement: Planned
OAuth credential source: Preferred / normal GA4 credential source
```

Step 219 should remove the manual fallback from normal resolver behavior in
`analytics_report_ai_resolve_google_ga4_credential_source()`.

Planned resolver behavior:

- use usable OAuth token state when available,
- return reconnect / refresh-needed / OAuth error categories when OAuth token
  state exists but is not usable,
- return `credential_source_missing` when no usable OAuth credential source is
  available,
- stop returning `credential_source_manual_token` in normal public-release
  behavior,
- stop returning `manual_token_fallback_used` in normal public-release
  behavior,
- stop returning a request-local access token from the manual settings fallback.

Step 219 should avoid changing GA4 client request construction. The GA4 client
should continue to receive an access token from the resolved credential source
only when the resolver supplies one.

## Saved Fallback Value Handling Plan

Selected plan for Step 219:

```text
Saved manual fallback value handling: Settings save value-free cleanup / Planned
```

Rationale:

- It removes the saved manual fallback category during normal Settings save
  without displaying, logging, or recording the value.
- It avoids retaining a retired credential source indefinitely.
- It avoids adding a new migration framework for a narrow retirement step.
- It keeps the change inside existing Settings save boundaries.

Planned approach:

- remove support for accepting `google_access_token` input in normal Settings
  save,
- remove support for accepting nested `google_tokens['access_token']` input in
  normal Settings save,
- unset or clear the saved manual fallback category during Settings save,
- set manual fallback storage to an empty category or otherwise omit the access
  token key without reading or displaying the stored value,
- do not inspect or record the saved value,
- keep unrelated OAuth token option storage unchanged,
- keep OpenAI API key handling unchanged,
- keep OAuth client Settings fallback handling unchanged.

Fallback plan if implementation complexity is higher than expected:

```text
Saved manual fallback value handling: Deferred with explicit rationale
```

If deferred, Step 219 should still retire resolver usage and normal UI entry,
and a follow-up cleanup-only step should be required before release readiness.

## Default Settings Structure Impact

Recommended Step 219 approach:

```text
Default settings google_tokens category: Keep as empty compatibility category for the narrow implementation
```

Rationale:

- Existing settings normalization expects the `google_tokens` category.
- Keeping the category empty avoids unnecessary structural churn.
- Full removal of the category can be considered later after source-level
  verification confirms no compatibility issue.
- The public-release behavior is controlled by removing UI entry and resolver
  usage of the manual fallback, not by relying on default settings structure
  removal.

Step 219 should not introduce a broad settings schema migration.

## Report Builder Wording Impact

Step 219 should update Report Builder wording that currently references the
manual fallback.

Planned wording direction:

- describe OAuth as the normal Google credential source,
- remove instructions to configure the temporary manual Google Access Token
  fallback,
- keep status/category-level labels,
- keep credential values hidden,
- keep reconnect/refresh-deferred wording for OAuth state,
- keep missing credential guidance focused on OAuth connection or configured
  OAuth credential state.

Report Builder behavior should remain otherwise unchanged. GA4 fetch flow,
payload handling, OpenAI generation, and generated report behavior are out of
scope for manual fallback retirement.

## Support/debug and Readme Wording Impact

Support/debug wording:

- should stop referring to manual Google Access Token as a normal support or
  recovery path,
- should continue to forbid credential values, option values, tokens,
  Authorization headers, request bodies, raw responses, AI payload JSON,
  generated report bodies, screenshots, and browser Network evidence,
- should use status/category-level labels only.

Readme/privacy wording:

- should be updated after behavior implementation,
- should describe OAuth as the GA4 credential path,
- should remove or revise references to temporary manual token fallback once
  the retirement implementation is verified,
- should not imply provider-side revoke or refresh execution if those remain
  Deferred / Hold.

Step 218 does not change readme or support/debug wording.

## Local Disconnect and Uninstall Cleanup Boundaries

Local-only OAuth disconnect remains unchanged:

- deletes only local OAuth token data,
- does not contact Google,
- does not revoke provider-side access,
- does not delete OpenAI API keys,
- should not become the manual fallback cleanup mechanism.

Uninstall cleanup remains unchanged:

- root `uninstall.php` deletes the whole main settings option,
- uninstall cleanup removes saved manual fallback data only when the plugin is
  uninstalled,
- uninstall cleanup is not a substitute for normal Settings UI and resolver
  retirement,
- uninstall cleanup does not contact Google or perform provider-side revoke.

Step 219 should not change `uninstall.php`.

## GA4 Client and OAuth Boundary

GA4 client boundary:

- no GA4 request construction changes are planned,
- no Authorization header construction changes are planned,
- no raw request or response handling changes are planned,
- no external API call should be performed during implementation or
  verification.

OAuth boundary:

- OAuth remains the preferred / normal GA4 credential source,
- token endpoint communication remains outside this step,
- refresh request execution remains Deferred / Hold,
- provider-side revoke remains Separate track,
- local-only disconnect remains unchanged.

Manual fallback retirement should be implemented at the Settings UI, Settings
save, resolver, and wording boundaries only.

## Source-level Verification Plan

Step 219 source-level verification should confirm:

- normal Settings UI no longer renders the manual Google Access Token field,
- normal Settings UI no longer renders the manual fallback status row,
- normal Settings UI no longer renders the manual fallback delete checkbox
  unless a cleanup-only control is explicitly planned,
- `sanitize_settings()` no longer accepts or preserves normal
  `google_access_token` input as a credential source,
- saved manual fallback category is cleaned or explicitly deferred without
  value display,
- `analytics_report_ai_resolve_google_ga4_credential_source()` no longer
  returns manual fallback credential source categories in normal behavior,
- `credential_source_manual_token` and `manual_token_fallback_used` are absent
  from normal resolver behavior or explicitly documented as retired,
- Report Builder wording no longer suggests using the temporary manual
  fallback,
- GA4 client files are unchanged,
- OpenAI client files are unchanged,
- `uninstall.php` is unchanged,
- no option values or credential values are displayed or logged.

Suggested safe source-level commands for Step 219:

```bash
rg -n "google_access_token|credential_source_manual_token|manual_token_fallback_used|Manual Google Access Token|clear_google_access_token|google_tokens\\['access_token'\\]" includes
git diff --check
git diff --name-only
git status --short --untracked-files=all
```

These checks should record only file-level, symbol-level, and category-level
evidence.

## Human Admin Smoke Planning Need

After Step 219 implementation and Step 220 source-level verification, a human
admin smoke plan should confirm status/category-level UI behavior without
recording forbidden evidence.

Human smoke should verify:

- Settings page loads,
- no visible fatal error,
- manual Google Access Token normal field is absent or hidden,
- manual fallback status row is absent or hidden,
- manual fallback delete checkbox is absent or restricted to an approved
  cleanup-only control,
- OAuth credential source wording remains visible,
- Report Builder credential source wording no longer directs users to manual
  fallback,
- GA4 Fetch is not executed unless a later step explicitly scopes it,
- no credential values, option values, screenshots, or Network evidence are
  recorded.

Step 218 does not run browser admin smoke.

## Forbidden Evidence Boundary

Step 219 and later verification must not inspect, display, record, or log:

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

Allowed evidence remains limited to:

- source file name,
- function / method / option key name,
- UI field name,
- credential source category,
- storage category,
- proposed implementation area,
- docs-level reference,
- Recommended / Planned / Hold / Deferred / Not applicable /
  Needs implementation classification,
- file-level change summary,
- command result category.

## Acceptance Criteria for Step 219

Step 219 can be accepted if:

- manual fallback normal Settings field is removed or hidden,
- manual fallback normal status row is removed or hidden,
- manual fallback normal delete checkbox is removed unless a cleanup-only
  control is explicitly retained,
- Settings save no longer accepts manual fallback input as a normal credential
  source,
- saved manual fallback category is cleaned value-free during Settings save or
  explicitly deferred with rationale,
- credential resolver no longer uses manual fallback as normal GA4 credential
  source,
- manual fallback status paths are removed from normal resolver behavior or
  documented as retired,
- OAuth remains the preferred / normal GA4 credential source,
- GA4 client request construction is unchanged,
- OpenAI client behavior is unchanged,
- local-only disconnect boundary is unchanged,
- uninstall cleanup boundary is unchanged,
- forbidden evidence is not inspected or recorded,
- source-level verification is prepared for Step 220,
- WordPress.org release readiness remains `Hold`.

## Recommended Next Step

Recommended next step:

```text
Step 219: Manual Google Access Token fallback retirement narrow production implementation
```

Step 219 should be a narrow production implementation limited to Settings UI,
Settings save handling, credential resolver behavior, and directly related
status/wording updates. Step 219 should not change GA4 client request
construction, OpenAI client behavior, OAuth token endpoint behavior,
provider-side revoke, local-only disconnect, uninstall cleanup, tools, build
scripts, or release packaging.

After Step 219, run:

```text
Step 220: Manual Google Access Token fallback retirement source-level verification
```

Step 219 and Step 220 can then be committed together if verification passes.

## Result Classification

```text
Manual Google Access Token fallback retirement implementation plan: Completed
Manual fallback Settings UI retirement: Planned
Manual fallback resolver retirement: Planned
Saved manual fallback value handling: Settings save value-free cleanup planned
OAuth credential source: Preferred / normal GA4 credential source
GA4 client request construction: Unchanged
Local-only disconnect boundary: Unchanged
Uninstall cleanup boundary: Unchanged
Production code changes: Not implemented in Step 218
WordPress.org release readiness: Hold
```
