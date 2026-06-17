# Step 191: OAuth Client Configuration Hybrid Source Human Admin Smoke Plan

## Step Purpose

Step 191 is a docs-only and planning-only human admin smoke plan for the
OAuth client configuration hybrid source Settings UI introduced in Step 189
and verified at source level in Step 190.

This plan is for a human-controlled browser check only. Codex does not perform
browser admin smoke, OAuth Connect, Google navigation, token endpoint
communication, Settings option inspection, screenshots, or browser Network
evidence collection in this step.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step190-oauth-client-configuration-hybrid-source-source-level-verification-results.md`
- `docs/maturation/step189-oauth-client-configuration-hybrid-source-narrow-implementation-results.md`
- `docs/maturation/step188-oauth-client-configuration-hybrid-source-implementation-plan.md`
- `docs/maturation/step187-oauth-client-configuration-source-level-inventory.md`
- `docs/maturation/step179-support-debug-wording-maturation-checkpoint.md`
- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`

## Smoke Boundary

Allowed:

- human browser review of the Analytics Report AI Settings screen,
- status/category-level observation,
- visible UI label checks,
- value-hidden posture checks,
- safe wording checks,
- optional human-controlled save/delete checks using only dummy non-secret
  placeholders, if the human reviewer decides that is appropriate.

Forbidden:

- OAuth Connect / Authorize button click,
- Google navigation,
- token endpoint communication,
- GA4 Fetch,
- OpenAI Generate,
- Plugin Check,
- browser Network tab evidence,
- screenshots,
- option value inspection,
- database dump,
- credential value inspection,
- OAuth client value inspection,
- token value inspection.

## Target Screen

Canonical Settings URL:

```text
http://localhost/wp-dev/wp-admin/admin.php?page=analytics-report-ai-settings
```

Do not use:

```text
page=analytics-report-ai-report-builder
```

## Human Admin Smoke Checklist

| ID | Area | Human check | Expected status-level result | Evidence allowed |
|---|---|---|---|---|
| HASC-01 | Settings page load | Open the canonical Settings URL. | Page loads without visible fatal error. | Pass / Fail / Not tested. |
| HASC-02 | Canonical slug | Confirm the page is the Settings page using `page=analytics-report-ai-settings`. | Correct Settings slug. | Status-level slug category only. |
| HASC-03 | Wrong slug avoided | Do not use `page=analytics-report-ai-report-builder`. | Not used. | Status-level confirmation only. |
| HASC-04 | OAuth client source label | Confirm an OAuth client source category label is visible. | One of `constants`, `settings`, `missing`, `incomplete`, `conflict`. | Category label only. |
| HASC-05 | Value-hidden label | Confirm OAuth client value-hidden label is visible. | `hidden`. | Category label only. |
| HASC-06 | Settings fallback label | Confirm Settings fallback status label is visible. | One of `saved`, `not_saved`, `incomplete`, `deleted` when applicable. | Category label only. |
| HASC-07 | OAuth client ID fallback field | Confirm the fallback input is visible and empty. | Input value is empty / value-hidden. | Pass / Fail / Not tested. |
| HASC-08 | OAuth client secret fallback field | Confirm the fallback input is visible and empty. | Input value is empty / value-hidden. | Pass / Fail / Not tested. |
| HASC-09 | No saved value redisplay | Confirm saved fallback values are not shown. | No redisplay observed. | Yes/no only. |
| HASC-10 | Placeholder / description safety | Confirm placeholder, description, and status labels do not include values, fragments, prefixes, suffixes, or masked values. | No forbidden value display. | Yes/no only. |
| HASC-11 | Support/debug hint | Confirm support wording asks for source/fallback/value-hidden labels only. | Status/category-only guidance. | Pass / Fail / Not tested. |
| HASC-12 | Delete control presence | If fallback status indicates saved/incomplete, confirm delete control is visible. | Delete control visible when applicable. | Pass / Fail / Not applicable / Not tested. |
| HASC-13 | Delete wording scope | Confirm delete wording is limited to Settings fallback OAuth client configuration. | Wording says constants, OAuth tokens, provider access, and manual token fallback are not changed. | Status-level summary only. |
| HASC-14 | Save behavior plan | If human chooses to test save, use dummy non-secret placeholders only and do not record the placeholders. | Save result remains status/category-level. | Status labels only. |
| HASC-15 | Delete behavior plan | If human chooses to test delete, do not inspect options or tokens. | Delete result notice remains status/category-level. | Status label / safe notice category only. |

## OAuth Client Status Labels

Allowed status/category labels:

```text
oauth_client_source_category: constants
oauth_client_source_category: settings
oauth_client_source_category: missing
oauth_client_source_category: incomplete
oauth_client_source_category: conflict
oauth_client_value_hidden_status: hidden
oauth_client_settings_fallback_status: saved
oauth_client_settings_fallback_status: not_saved
oauth_client_settings_fallback_status: incomplete
oauth_client_settings_fallback_status: deleted
```

The human smoke result must not include actual OAuth client values, fragments,
prefixes, suffixes, masked values, copied form values, option values, or
browser URLs beyond the status-level Settings page category.

## Value-hidden Behavior Plan

Human checks:

- OAuth client ID fallback input displays with an empty value.
- OAuth client secret fallback input displays with an empty value.
- Saved fallback values are not redisplayed.
- Placeholder text does not include real values, fragments, prefixes, suffixes,
  or masked values.
- Description text does not ask for values in support/debug reports.
- Status labels are category-level only.

Expected result:

```text
value_hidden_status: pass
```

If a saved fallback exists, the browser should still show empty input values.
The result should record only whether redisplay was observed, not what value may
be saved.

## Settings Fallback Save Plan

This plan does not require a save test. If the human reviewer chooses to test
save behavior, use only dummy non-secret placeholders that cannot be used for
real OAuth and do not record the placeholders.

Planned save observations:

- Empty input keeps existing fallback status.
- New dummy non-secret placeholder input can change the Settings fallback
  category.
- After saving, fallback inputs are still empty.
- Save result is shown as status/category-level UI only.
- No external API call, OAuth Connect, Google navigation, token endpoint
  communication, or option value inspection occurs.

If safe dummy placeholder use is not desirable, mark save behavior as
`Not tested` and keep it for a later controlled step.

## Delete Semantics Plan

Human checks:

- Settings fallback OAuth client configuration delete control appears when
  applicable.
- Delete wording is scoped only to Settings fallback OAuth client configuration.
- Delete wording says constants are not changed.
- Delete wording says OAuth tokens are not changed.
- Delete wording says provider access/revoke is not changed.
- Delete wording says refresh/reconnect are not performed.
- Delete wording says the manual Google Access Token fallback is not changed.
- Delete result notice is category/status-level only.

Do not inspect options, tokens, database rows, provider state, or network
traffic to verify delete behavior during this smoke.

## Forbidden Evidence Boundary

Do not record, request, paste, screenshot, inspect, or infer:

- OAuth client ID values,
- OAuth client secret values,
- credentials,
- API keys,
- access tokens,
- refresh tokens,
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

Allowed evidence is limited to:

- Pass / Fail / Not tested,
- status/category labels,
- safe notice category,
- visible/non-visible category,
- value-hidden yes/no,
- no forbidden evidence yes/no.

## Step 192 Human Result Template

Use this template for Step 192 human-provided results. Keep the result
status/category-level only.

```text
Settings page load status: Pass / Fail / Not tested
Fatal error status: No / Yes / Not tested
Canonical Settings slug used: Yes / No / Not tested
Report Builder slug avoided: Yes / No / Not tested
OAuth client source category visible: Pass / Fail / Not visible / Not tested
Observed OAuth client source category: constants / settings / missing / incomplete / conflict / Not recorded
OAuth client value-hidden status visible: Pass / Fail / Not visible / Not tested
Observed value-hidden category: hidden / Not recorded
Settings fallback status visible: Pass / Fail / Not visible / Not tested
Observed Settings fallback category: saved / not_saved / incomplete / deleted / Not recorded
OAuth client ID fallback input value-hidden: Pass / Fail / Not tested
OAuth client secret fallback input value-hidden: Pass / Fail / Not tested
Saved value redisplay observed: No / Yes / Not tested
Placeholder or description included OAuth client value fragment: No / Yes / Not tested
Support/debug wording status-level only: Pass / Fail / Not tested
Delete control visible when applicable: Pass / Fail / Not applicable / Not tested
Delete wording scoped to Settings fallback only: Pass / Fail / Not applicable / Not tested
Save behavior checked with dummy non-secret placeholders only: Yes / No / Not tested
Saved dummy placeholder values recorded: No
Delete behavior checked: Yes / No / Not tested
Delete result category observed: deleted / no_notice_observed / not_applicable / Not recorded
Forbidden evidence displayed: No / Yes / Not tested
OAuth Connect executed: No
Google navigation executed: No
Token endpoint communication executed: No
GA4 Fetch executed: No
OpenAI Generate executed: No
Plugin Check executed: No
Screenshots collected: No
Network evidence collected: No
Option values inspected: No
Database dump performed: No
Credentials or OAuth client values recorded: No
```

## Acceptance Criteria

| Criterion | Status | Notes |
|---|---|---|
| Docs-only human admin smoke plan file added | Pass | This file records the Step 191 plan. |
| Production code / readme / tools / JS / CSS unchanged | Pass | Step 191 is planning-only. |
| Settings UI status/category-level checklist organized | Pass | Checklist covers page load, labels, value-hidden posture, save plan, and delete plan. |
| Value-hidden behavior checks organized | Pass | Inputs, placeholders, descriptions, and redisplay are covered. |
| Delete semantics checks organized | Pass | Delete scope and non-effects are covered. |
| Forbidden evidence non-recording policy documented | Pass | Forbidden and allowed evidence boundaries are explicit. |
| WordPress.org release remains `Hold` | Pass | Human smoke results and later release readiness steps remain pending. |
| Next recommended step is explicit | Pass | Step 192 is recommended below. |

## Not Executed

Not executed in Step 191:

- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- browser admin smoke by Codex,
- screenshots,
- browser Network evidence collection,
- database dump,
- option value output.

## Recommended Next Step

Recommended next step:

```text
Step 192: OAuth client configuration hybrid source human admin smoke results
```

Step 192 should be docs-only. It should record the human-provided
status/category-level results from the Settings UI smoke without recording
forbidden evidence.

## Result Classification

```text
Human admin smoke plan completed
```
