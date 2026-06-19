# Step 221: Manual Google Access Token Fallback Retirement Human Admin Smoke Plan

## Step Purpose

Step 221 is a docs-only and planning-only human admin smoke plan for verifying
the admin UI after the manual Google Access Token fallback retirement completed
in Steps 219 and 220.

The purpose is to define how a human reviewer should confirm that the normal
Settings UI and Report Builder no longer present the manual Google Access Token
fallback as a normal public-release path, while keeping evidence collection
strictly status/category-level.

This step does not execute browser admin smoke.

WordPress.org release readiness remains `Hold`.

## Scope

In scope for the future human admin smoke:

- Settings page load confirmation,
- absence of the `Manual Google Access Token` normal Settings field,
- absence of the `Manual Google Access Token Status` normal Settings row,
- absence of the `clear_google_access_token` normal Settings checkbox,
- OAuth-first / Google OAuth normal GA4 credential source wording in Settings,
- confirmation that Settings save does not reintroduce the retired manual
  fallback UI,
- Report Builder page load confirmation,
- Report Builder wording that does not present the manual fallback as a normal
  path,
- Report Builder missing credential / reconnect / refresh-deferred wording at
  status/category level,
- forbidden evidence rules for the human observation,
- Pass / Fail / Blocked / Not applicable criteria,
- Step 222 human result template.

Out of scope for Step 221 itself:

- browser admin smoke execution,
- Settings save execution,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- production code changes.

## Explicit Non-goals

Step 221 does not:

- change production code,
- change Settings UI,
- change the credential resolver,
- change GA4 client behavior,
- change OpenAI client behavior,
- change `uninstall.php`,
- change `readme.txt`,
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

- `docs/maturation/step220-manual-google-access-token-fallback-retirement-source-level-verification-results.md`
- `docs/maturation/step219-manual-google-access-token-fallback-retirement-narrow-production-implementation-results.md`
- `docs/maturation/step218-manual-google-access-token-fallback-retirement-implementation-plan.md`
- `docs/maturation/step217-manual-google-access-token-fallback-public-release-decision-checkpoint.md`
- `docs/maturation/step216-manual-google-access-token-fallback-retirement-plan.md`
- `docs/maturation/step215-uninstall-cleanup-maturation-checkpoint.md`
- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`

## Preconditions

Before a future Step 222 human admin smoke execution:

- Step 219 implementation is present.
- Step 220 source-level verification is complete and classified as `Pass`.
- The reviewer can access WordPress admin in a controlled local verification
  environment.
- The reviewer understands that only screen names, visible UI labels,
  absent-field categories, visible notice categories, and status/category-level
  wording may be recorded.
- The reviewer must not record credential values, token values, option values,
  OAuth client values, screenshots, browser Network evidence, request bodies,
  raw responses, AI payload JSON, generated report bodies, GA4 Property ID
  values, hostname/domain values, or analytics values.
- OAuth Connect / Authorize must not be clicked.
- GA4 Fetch must not be clicked.
- OpenAI Generate must not be clicked.
- Browser screenshots and Network evidence must not be collected.

## Human Admin Smoke Scenarios

| ID | Screen | Scenario | Action boundary | Expected status/category-level observation |
|---|---|---|---|---|
| S1 | Settings | Page loads without visible fatal error. | Open canonical Settings page only. | `settings_page_loaded`; no visible fatal error category. |
| S2 | Settings | `Manual Google Access Token` field is absent. | Observe visible field labels only. | `manual_google_access_token_field_absent`. |
| S3 | Settings | `Manual Google Access Token Status` row is absent. | Observe visible row labels only. | `manual_google_access_token_status_row_absent`. |
| S4 | Settings | `clear_google_access_token` checkbox is absent. | Observe visible controls only. | `clear_google_access_token_checkbox_absent`. |
| S5 | Settings | OAuth-first guidance is visible. | Observe wording only. | `oauth_first_settings_guidance_visible`; Google OAuth is described as the normal GA4 credential source. |
| S6 | Settings | Retired manual fallback is not presented as a normal path. | Observe descriptions and help text only. | Manual fallback may be mentioned only as retired; no manual token entry path is offered. |
| S7 | Settings | Settings save does not reintroduce retired manual fallback UI. | Optional human-only save with no credential entry; do not record values. | After save, retired manual fallback field/status/control remain absent. |
| S8 | Report Builder | Page loads without visible fatal error. | Open canonical Report Builder page only. | `report_builder_page_loaded`; no visible fatal error category. |
| S9 | Report Builder | Manual fallback guidance is absent. | Observe credential guidance only. | `report_builder_manual_fallback_guidance_absent`. |
| S10 | Report Builder | OAuth-first credential guidance is visible. | Observe credential guidance only; do not click GA4 Fetch. | `report_builder_oauth_first_credential_guidance_visible`. |
| S11 | Report Builder | Missing credential guidance is status/category-level. | Observe visible notice/category text only; do not click GA4 Fetch. | Missing credential guidance points to Google OAuth in Settings and does not reveal values. |
| S12 | Report Builder | Reconnect / refresh-deferred wording is status/category-level. | Observe wording only; do not execute OAuth, refresh, or GA4 Fetch. | Reconnect / refresh-needed guidance remains status/category-level and value-hidden. |
| S13 | Evidence boundary | Forbidden evidence is not collected. | Do not collect screenshots, Network evidence, option values, credential values, payloads, or reports. | `forbidden_evidence_recorded: No`. |

## Allowed Evidence

Allowed evidence for the future human smoke result:

- screen name,
- visible UI label names,
- absent UI field/status/control category,
- status/category-level wording,
- Pass / Fail / Blocked / Not applicable classification,
- visible notice category,
- command result category,
- file-level change summary.

Examples of allowed observations:

```text
Settings page loaded: Pass
Manual Google Access Token field: absent
Manual Google Access Token Status row: absent
clear_google_access_token checkbox: absent
OAuth-first Settings guidance: visible
Settings save reintroduced manual fallback UI: No
Report Builder page loaded: Pass
Report Builder manual fallback guidance: absent
Report Builder OAuth-first credential guidance: visible
Forbidden evidence recorded: No
```

## Forbidden Evidence

The future human smoke must not record, display, request, paste, summarize, or
infer:

- credential values,
- API keys,
- access token values,
- refresh token values,
- OAuth client ID values,
- OAuth client secret values,
- OAuth client value fragments,
- Authorization headers,
- plugin option values,
- OAuth token option values,
- serialized option values,
- request bodies,
- raw responses,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces,
- database dumps,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- page path/source/city values,
- AI payload JSON,
- generated report bodies.

## Pass / Fail / Blocked / Not Applicable Criteria

| Result | Criteria |
|---|---|
| Pass | The expected Settings or Report Builder UI state is observed, retired manual fallback controls are absent where expected, OAuth-first guidance is visible where expected, and no forbidden evidence is recorded. |
| Fail | A visible fatal error appears, a retired manual fallback field/status/control remains visible as a normal path, Report Builder still guides users to the manual fallback as a normal path, forbidden evidence is shown by the UI, or the observation requires a prohibited action. |
| Blocked | The screen cannot be reached, login/admin access is unavailable, the environment state prevents safe observation, or the check would require forbidden evidence or a prohibited action. |
| Not applicable | The check depends on an optional human action that is intentionally skipped, such as a no-credential Settings save confirmation. |

## Expected Observations

Expected status/category-level observations:

```text
Settings page loaded: expected Pass
Manual Google Access Token field: expected absent
Manual Google Access Token Status row: expected absent
clear_google_access_token checkbox: expected absent
OAuth-first Settings guidance: expected visible
Report Builder page loaded: expected Pass
Report Builder manual fallback guidance: expected absent
Report Builder OAuth-first credential guidance: expected visible
Forbidden evidence recorded: expected No
```

Additional expected Settings observations:

- Settings page uses the canonical Settings admin page.
- Settings UI may mention the manual Google Access Token fallback only as a
  retired category, not as an entry field or normal credential path.
- OAuth client Settings fallback value-hidden posture remains separate from the
  retired manual access token fallback.
- OpenAI API key value-hidden behavior remains separate from this check.
- Credential values, token values, option values, and OAuth client values are
  not displayed.

Additional expected Report Builder observations:

- Report Builder page uses the canonical Report Builder admin page.
- GA4 credential source guidance describes Google OAuth as the normal GA4
  credential source.
- Missing credential guidance points to Google OAuth in Settings.
- Reconnect / refresh-deferred guidance remains status/category-level.
- No raw values or credential values are displayed.

## Risk Boundaries

Risk boundaries for Step 222:

- Do not click GA4 Fetch.
- Do not click OpenAI Generate.
- Do not click OAuth Connect / Authorize.
- Do not navigate to Google.
- Do not call the token endpoint.
- Do not execute refresh requests.
- Do not execute revoke requests.
- Do not run Plugin Check.
- Do not execute plugin uninstall.
- Do not inspect database rows or option values.
- Do not record screenshots or browser Network evidence.
- Do not record request bodies, raw responses, AI payload JSON, or generated
  report bodies.

If a human reviewer cannot confirm a scenario without crossing one of these
boundaries, the scenario should be recorded as `Blocked` rather than expanded.

## Step 222 Human Result Template

Use this template for Step 222 human-provided status/category-level results:

```text
Step 222 normalized human observation:
- Settings page loaded: Pass / Fail / Blocked
- Visible fatal error on Settings: Yes / No
- Canonical Settings page used: Yes / No
- Manual Google Access Token field visible: No / Yes / Blocked
- Manual Google Access Token field result category: absent / visible_unexpected / blocked
- Manual Google Access Token Status row visible: No / Yes / Blocked
- Manual Google Access Token Status row result category: absent / visible_unexpected / blocked
- clear_google_access_token checkbox visible: No / Yes / Blocked
- clear_google_access_token checkbox result category: absent / visible_unexpected / blocked
- OAuth-first Settings guidance visible: Yes / No / Blocked
- Settings save checked without credential entry: Yes / No / Not applicable
- Manual fallback UI reappeared after Settings save: No / Yes / Not applicable / Blocked
- Report Builder page loaded: Pass / Fail / Blocked
- Visible fatal error on Report Builder: Yes / No
- Report Builder manual fallback guidance visible: No / Yes / Blocked
- Report Builder OAuth-first credential guidance visible: Yes / No / Blocked
- Missing credential guidance status/category-level only: Yes / No / Not applicable / Blocked
- Reconnect / refresh-deferred wording status/category-level only: Yes / No / Not applicable / Blocked
- GA4 Fetch executed: No
- OpenAI Generate executed: No
- OAuth Connect / Authorize executed: No
- Google navigation executed: No
- Token endpoint communication executed: No
- Refresh request executed: No
- Revoke request executed: No
- Screenshots collected: No
- Network evidence collected: No
- Option/token/credential/OAuth client values inspected or recorded: No
- Forbidden evidence recorded: No
```

Do not add raw URLs, option values, credential values, token values, OAuth
client values, screenshots, Network evidence, payloads, raw responses, or
generated report bodies to Step 222.

## Recommended Next Step

Recommended next step:

```text
Step 222: Manual Google Access Token fallback retirement controlled human admin smoke execution
```

Step 222 should record only the human-provided status/category-level
observations from this plan. CODEX should not execute the browser admin smoke.

## Result Classification

```text
Manual Google Access Token fallback retirement human admin smoke plan: Completed
Production code changes: None
Settings UI changes: None
Credential resolver changes: None
GA4 client behavior: Unchanged
OpenAI client behavior: Unchanged
Uninstall cleanup boundary: Unchanged
Readme/privacy wording: Unchanged
Browser admin smoke execution: Not executed in Step 221
WordPress.org release readiness: Hold
```
