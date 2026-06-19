# Step 233: OpenAI API Key Source Human Admin Smoke Plan

## Step Purpose

Step 233 is a docs-only / planning-only human admin smoke plan for the OpenAI
API key source UI after the Step 231 implementation and Step 232 source-level
verification.

The purpose is to define how a human tester should verify the Settings and
Report Builder admin UI at status/category level only, without exposing
credential values, option values, API keys, Authorization headers, request
bodies, raw responses, AI payload JSON, generated report bodies, screenshots,
or browser Network evidence.

Step 233 does not execute browser admin smoke. It only prepares the plan and
the Step 234 human observation template.

WordPress.org release readiness remains `Hold`.

## Scope

In scope:

- Settings page load observation plan,
- Settings UI OpenAI API key source category observation plan,
- `constant_configured` / `settings_saved` / `missing` status/category
  observation plan,
- `openai_api_key_value_visibility: hidden` observation plan,
- Settings fallback field value-empty / value-hidden observation plan,
- Settings fallback lower-priority wording observation plan,
- `clear_openai_api_key` Settings-fallback-only wording observation plan,
- constant not deleted/modified by Settings clear wording observation plan,
- Report Builder page load observation plan,
- Report Builder OpenAI API key source/category readiness observation plan,
- Report Builder Settings-only OpenAI key guidance observation plan,
- Step 232 source-awareness wording follow-up handling,
- allowed and forbidden evidence boundaries,
- Pass / Fail / Blocked / Not applicable criteria,
- Step 234 human result template.

Out of scope:

- production code changes,
- `readme.txt` changes,
- Settings UI changes,
- credential resolver changes,
- OpenAI client changes,
- GA4 client changes,
- `uninstall.php` changes,
- tools, JavaScript, or CSS changes,
- actual browser admin smoke execution,
- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- external API calls,
- option value inspection.

## Explicit Non-goals

Step 233 does not:

- change production code,
- change `readme.txt`,
- change Settings UI,
- change the credential resolver,
- change OpenAI client behavior,
- change GA4 client behavior,
- change `uninstall.php`,
- change tools or build scripts,
- change JavaScript or CSS,
- run browser admin smoke,
- run Plugin Check,
- run GA4 Fetch,
- run OpenAI Generate,
- start OAuth Connect / Authorize,
- navigate to Google,
- call token endpoints,
- execute refresh requests,
- execute revoke requests,
- execute plugin uninstall,
- collect screenshots,
- collect browser Network evidence,
- inspect database dumps,
- run `wp option get` for plugin option values,
- inspect option values,
- inspect token values,
- inspect credential values,
- inspect OAuth client values,
- inspect API keys,
- inspect Authorization headers,
- inspect request bodies,
- inspect raw responses,
- inspect AI payload JSON,
- inspect generated report bodies.

## Referenced Prior Steps

- `docs/maturation/step232-openai-api-key-constant-based-configuration-source-level-verification-results.md`
- `docs/maturation/step231-openai-api-key-constant-based-configuration-narrow-production-implementation-results.md`
- `docs/maturation/step230-openai-api-key-constant-based-configuration-implementation-plan.md`
- `docs/maturation/step229-openai-api-key-storage-public-release-decision-checkpoint.md`
- `docs/maturation/step228-openai-api-key-storage-posture-checkpoint.md`
- `docs/maturation/step227-readme-privacy-wording-alignment-after-manual-token-retirement-maturation-checkpoint.md`

## Preconditions

Before human admin smoke:

- Step 231 implementation is present.
- Step 232 source-level verification is complete and recorded as Pass.
- The human tester can access WordPress admin.
- The human tester uses the canonical Settings page for Analytics Report AI.
- The human tester does not run GA4 Fetch.
- The human tester does not run OpenAI Generate.
- The human tester does not run OAuth Connect / Authorize.
- The human tester does not navigate to Google.
- The human tester does not inspect option values, token values, credential
  values, OAuth client values, API keys, Authorization headers, request bodies,
  raw responses, AI payload JSON, or generated report bodies.
- The human tester does not collect screenshots or browser Network evidence.

Step 233 does not require any specific source state. The Step 234 template
allows category-level outcomes for `constant_configured`, `settings_saved`, and
`missing`.

## Human Admin Smoke Scenarios

### Scenario A: Settings Page Load

Goal:

- confirm the Settings screen loads,
- confirm no visible fatal error is present,
- confirm the canonical Settings page is used.

Allowed observation:

- screen name,
- page load Pass / Fail / Blocked,
- visible fatal error Yes / No,
- canonical page Yes / No.

Forbidden observation:

- option values,
- credential values,
- API keys,
- OAuth client values,
- screenshots,
- Network evidence.

### Scenario B: Settings OpenAI Source Category

Goal:

- confirm the OpenAI API key source category is visible,
- record only one status/category-level result:
  - `constant_configured`,
  - `settings_saved`,
  - `missing`,
  - `visible_but_unclear`,
  - `blocked`.

Allowed observation:

- visible label names,
- source category,
- Pass / Fail / Blocked classification.

Forbidden observation:

- constant value,
- Settings-saved key value,
- option value,
- placeholder fragments that include secret material.

### Scenario C: Value-hidden / Field-empty Posture

Goal:

- confirm `openai_api_key_value_visibility: hidden` is visible,
- confirm the Settings fallback password field does not show a key value,
- confirm the field value appears empty / hidden.

Allowed observation:

- value-hidden category,
- field present Yes / No,
- field value visible Yes / No / Blocked,
- field empty Yes / No / Blocked.

Forbidden observation:

- any credential value,
- key fragment,
- prefix/suffix,
- browser password manager reveal evidence,
- screenshots.

### Scenario D: Settings Fallback Priority Wording

Goal:

- confirm Settings wording explains constant-based configuration is preferred,
- confirm Settings fallback is lower priority when applicable,
- confirm a saved Settings fallback is described as hidden and preserved when
  the field is left empty.

Allowed observation:

- status/category-level wording,
- visible label names,
- `Yes / No / Not applicable / Blocked`.

Forbidden observation:

- actual constant value,
- actual Settings fallback value,
- option value.

### Scenario E: Clear Control Scope

Goal:

- confirm `clear_openai_api_key` checkbox visibility if a Settings fallback key
  is saved,
- confirm the clear checkbox is described as deleting only the Settings
  fallback key,
- confirm wording says constant-based configuration is not changed by the
  Settings clear control.

Allowed observation:

- checkbox visible category,
- clear-scope wording category,
- constant-not-changed wording category,
- `Yes / No / Not applicable / Blocked`.

Forbidden observation:

- executing clear unless a separate future step explicitly asks for a controlled
  clear behavior smoke,
- option value inspection,
- API key inspection.

### Scenario F: Report Builder Page Load

Goal:

- confirm the Report Builder screen loads,
- confirm no visible fatal error is present,
- confirm OpenAI key source/category readiness is visible.

Allowed observation:

- screen name,
- page load Pass / Fail / Blocked,
- visible fatal error Yes / No,
- source/category readiness visibility.

Forbidden observation:

- GA4 Fetch,
- OpenAI Generate,
- AI payload JSON,
- generated report body,
- screenshots,
- Network evidence.

### Scenario G: Report Builder Source-aware Guidance

Goal:

- confirm Report Builder does not present OpenAI key state as Settings-only,
- confirm source/category readiness uses safe wording,
- record whether source-awareness follow-up is still needed.

Allowed observation:

- Settings-only guidance visible: Yes / No / Blocked,
- source-awareness follow-up needed: Yes / No / Not applicable / Blocked.

Forbidden observation:

- OpenAI Generate execution,
- request body inspection,
- raw response inspection,
- generated report body inspection.

## Allowed Evidence

Allowed evidence for Step 234 result recording:

- screen name,
- visible UI label names,
- source category,
- storage category,
- value-hidden category,
- status/category-level wording,
- Pass / Fail / Blocked / Not applicable classification,
- visible notice category,
- docs-level reference,
- command result category,
- file-level change summary.

## Forbidden Evidence

Do not collect, inspect, paste, or record:

- option values,
- token values,
- credential values,
- OAuth client values,
- API keys,
- key fragments, prefixes, or suffixes,
- access token values,
- refresh token values,
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

## Pass / Fail / Blocked / Not Applicable Criteria

| Classification | Criteria |
|---|---|
| Pass | The expected label/state is visible and no forbidden evidence is observed or recorded. |
| Fail | The UI displays a forbidden value, hides a required category, shows misleading Settings-only source guidance where source-aware wording is expected, or shows a visible fatal error. |
| Blocked | The screen cannot be opened, admin access is unavailable, the relevant UI area cannot be observed safely, or confirming it would require forbidden evidence. |
| Not applicable | The check depends on a state not present in the test environment, such as clear checkbox visibility when no Settings fallback is saved. |

## Expected Observations

Expected status-level observations:

```text
Settings page loaded: expected Pass
OpenAI API key source category: expected visible
OpenAI API key source category result: expected constant_configured / settings_saved / missing
OpenAI API key value visibility: expected hidden
Settings fallback field value: expected empty / hidden
Settings fallback priority wording: expected visible when applicable
clear_openai_api_key scope wording: expected Settings fallback only
constant delete/modify wording: expected constant not changed by Settings clear
Report Builder page loaded: expected Pass
Report Builder OpenAI key source readiness: expected visible
Report Builder Settings-only guidance: expected absent or needs follow-up only
Forbidden evidence recorded: expected No
```

External action expectations:

```text
GA4 Fetch executed: expected No
OpenAI Generate executed: expected No
OAuth Connect / Authorize executed: expected No
Google navigation executed: expected No
Token endpoint communication executed: expected No
Refresh request executed: expected No
Revoke request executed: expected No
Screenshots collected: expected No
Network evidence collected: expected No
Forbidden evidence recorded: expected No
```

## Source-awareness Wording Follow-up Boundary

Step 232 recorded:

```text
OpenAI error wording source-awareness: Needs follow-up wording alignment
```

Human admin smoke should not try to trigger OpenAI API errors because that would
require OpenAI Generate or external API communication.

For Step 234, the human tester should only record whether normal Settings and
Report Builder guidance appears source-aware at status/category level:

- if Settings and Report Builder source labels are clear, record Pass for those
  UI checks,
- if any visible normal admin guidance still implies Settings is the only
  source, record the wording category as `Needs follow-up wording alignment`,
- do not execute OpenAI Generate,
- do not inspect OpenAI request/response details,
- do not record API keys, Authorization headers, request bodies, raw responses,
  AI payload JSON, or generated report bodies.

OpenAI error wording alignment should remain a future wording follow-up unless
a human-visible non-external UI path exposes it safely.

## Risk Boundaries

Risk boundaries to preserve:

- constant values must never be shown or recorded,
- Settings fallback values must never be redisplayed,
- Settings fallback clear controls must not claim to modify constants,
- normal admin UI must remain status/category-level,
- support evidence must remain status/category-level,
- no browser Network evidence should be collected,
- no screenshots should be collected,
- no external API actions should be executed,
- WordPress.org release readiness remains `Hold`.

## Step 234 Human Result Template

```text
Step 234 normalized human observation:
- Settings page loaded: Pass / Fail / Blocked
- Visible fatal error on Settings: Yes / No
- Canonical Settings page used: Yes / No
- OpenAI API key source category visible: Yes / No / Blocked
- OpenAI API key source category result: constant_configured / settings_saved / missing / visible_but_unclear / blocked
- OpenAI API key value visibility shown as hidden: Yes / No / Blocked
- OpenAI API key field value visible: No / Yes / Blocked
- Settings fallback field present: Yes / No / Blocked
- Settings fallback field value empty: Yes / No / Blocked
- Settings fallback priority wording visible: Yes / No / Not applicable / Blocked
- clear_openai_api_key checkbox visible: Yes / No / Not applicable / Blocked
- clear_openai_api_key scope described as Settings fallback only: Yes / No / Not applicable / Blocked
- Constant delete/modify by Settings clear described as No: Yes / No / Not applicable / Blocked
- Report Builder page loaded: Pass / Fail / Blocked
- Visible fatal error on Report Builder: Yes / No
- Report Builder OpenAI key source/category readiness visible: Yes / No / Blocked
- Report Builder Settings-only OpenAI key guidance visible: No / Yes / Blocked
- OpenAI error wording source-awareness follow-up needed: Yes / No / Not applicable / Blocked
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
- API key / Authorization header inspected or recorded: No
- Request body / raw response / AI payload JSON / generated report body inspected or recorded: No
- Forbidden evidence recorded: No
```

## Recommended Next Step

Recommended next step:

```text
Step 234: OpenAI API key source controlled human admin smoke results
```

Step 234 should record human-provided status/category-level observations only.
Codex should not run browser admin smoke, GA4 Fetch, OpenAI Generate, OAuth
Connect / Authorize, external API communication, Plugin Check, screenshots,
Network evidence collection, option inspection, or forbidden evidence
collection.

## Result Classification

```text
OpenAI API key source human admin smoke plan: Completed
Plan type: Docs-only / planning-only
Browser admin smoke by Codex: Not executed
Settings UI source/category checks: Planned
Report Builder source/category checks: Planned
Value-hidden posture checks: Planned
Settings fallback clear-scope checks: Planned
Source-awareness wording follow-up boundary: Planned
Forbidden evidence boundary: Preserved
Production code changes: None in Step 233
WordPress.org release readiness: Hold
```
