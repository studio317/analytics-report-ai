# Step 258: OpenAI Settings Fallback Legacy / Transitional Controlled Human Admin Smoke Plan

## Plan Purpose and Step 256 / 257 Inputs

Step 258 is a docs-only / planning-only smoke plan for a future controlled human
admin smoke of the Step 256 OpenAI Settings fallback legacy / transitional
implementation.

Step 256 implemented the narrow production boundary:

```text
OpenAI Settings fallback legacy/transitional narrow production implementation
```

Step 257 classified the source-level verification as:

```text
Source-level Pass
```

Confirmed inputs from Step 256 / Step 257:

- resolver priority remains `constant_configured -> settings_saved -> missing`;
- normal Settings UI no longer renders a password input for new Settings
  fallback creation or replacement;
- normal Settings save handling does not use non-empty `openai_api_key`
  submissions to create or replace a fallback;
- existing `settings_saved` remains operational as a legacy / transitional
  compatibility state;
- `clear_openai_api_key` is the only normal fallback mutation route;
- clear deletes only the Settings fallback and does not change the constant
  source;
- `constant_configured + saved fallback` keeps the constant active / preferred
  while allowing lower-priority fallback cleanup;
- Settings and Report Builder source-aware wording use constant-first /
  legacy-transitional framing;
- missing-key wording points to preferred constant-based configuration.

This plan prepares a human-visible confirmation of those boundaries. It does
not prove actual credential validity, actual constant preservation, actual
Settings option contents, OpenAI request success, provider acceptance, or
public-release approval.

WordPress.org release readiness remains `Hold`.

## Scope and Non-goals

In scope:

- planning a controlled human admin smoke for `wp-dev` only;
- defining baseline gates before any future temporary state;
- defining a value-free S0 -> S1 -> S2 -> S3 -> S4 state sequence;
- defining allowed status/category-level observations;
- defining forbidden evidence and stop conditions;
- defining a compact human observation template.

Out of scope for Step 258:

- production PHP changes;
- JavaScript or CSS changes;
- `readme.txt` changes;
- `uninstall.php` changes;
- tools changes;
- existing docs changes;
- `wp-dev` file or fixture creation;
- `wp-dev-check` use or modification;
- browser admin smoke;
- Settings save;
- `clear_openai_api_key` operation;
- temporary fixture or helper creation;
- WP-CLI state mutation or option inspection;
- OpenAI Generate;
- GA4 Fetch;
- OAuth Connect / Authorize;
- external HTTP;
- provider communication;
- Plugin Check;
- screenshots;
- browser Network evidence collection.

Technical wording boundary:

```text
developer-only / transitional route: release-disposition concept only
legacy / transitional fallback: technically accurate current UI wording target
```

The future smoke must not claim developer-only access enforcement, role-gated
fallback access, provider authorization, real OpenAI request success, or
WordPress.org release approval.

## Safety and Baseline Gates

Future Step 259 execution may proceed only if all preflight gates pass before
temporary state is created:

1. `wp-dev` only is the target environment.
2. `wp-dev-check` is not used or modified.
3. No existing Step 258 / Step 259 temporary fixture or helper is present.
4. Current visible or category-level baseline can be safely interpreted as
   `missing / not_saved / hidden`.
5. No actual OpenAI API key, actual constant-based configuration, or existing
   saved fallback is suspected.
6. No fatal error, unexpected admin failure, or source-state ambiguity exists.
7. Temporary state can be created without exposing any value.
8. All temporary artifacts and temporary fallback state can be removed at
   cleanup.

If any gate fails, Step 259 must be classified as `Blocked`; no temporary
fixture, helper, browser mutation, or state preparation may occur.

Allowed evidence:

- screen name;
- visible status/category labels;
- visible wording category;
- clear-control visibility;
- pass / fail / blocked / not applicable classification;
- command result category when command output does not contain values.

Forbidden evidence:

- actual credential values;
- actual API key values;
- actual constant values;
- actual Settings fallback option values;
- serialized option values;
- token values;
- Authorization headers;
- request bodies;
- raw responses;
- payload JSON;
- generated report text;
- screenshots;
- browser Network evidence;
- cookies, sessions, or nonces;
- database query results or dumps.

## Temporary Local-only State Preparation Boundary

Step 258 does not create any artifact.

Future Step 259 may use temporary local-only state preparation only after all
preflight gates pass.

State preparation rules:

- target `wp-dev` only;
- never touch `wp-dev-check`;
- never alter production repository files;
- never use an actual OpenAI API key or credential;
- never display, copy, log, or record temporary placeholder values;
- never inspect option values;
- remove all temporary artifacts during cleanup;
- record only status/category-level observations.

For the temporary constant fixture, future Step 259 may use the local mu-plugin
location:

```text
/var/www/html/wp-dev/wp-content/mu-plugins/
```

A Step 259-specific filename may be chosen in that step. Step 258 does not
create the file, provide fixture source, or record any placeholder / marker /
constant value.

## State-transition Model: S0 -> S1 -> S2 -> S3 -> S4

### S0: Baseline Missing

Expected safe categories:

```text
source_category=missing
settings_fallback_status=not_saved
value_visibility=hidden
clear_control=hidden
```

Human confirmation should occur before any temporary state preparation.

### S1: Controlled `settings_saved` Compatibility State

Future Step 259 may create a temporary local-only, non-credential placeholder
fallback state in `wp-dev` only.

Requirements:

- no actual API key;
- no actual credential;
- no browser entry of an API key;
- no value displayed, copied, logged, or recorded;
- no OpenAI Generate;
- no external HTTP;
- no provider communication;
- no production repository file change;
- no `wp-dev-check` touch.

Expected safe categories:

```text
source_category=settings_saved
settings_fallback_status=saved
value_visibility=hidden
clear_control=visible
```

### S2: Controlled `constant_configured + saved fallback`

After S1 is safely established, future Step 259 may add a temporary local-only
constant fixture in `wp-dev` only.

Fixture requirements:

- local-only mu-plugin fixture;
- outside the production repository;
- not present in `wp-dev-check`;
- non-credential placeholder only;
- constant value, marker, and fixture source are not copied into docs or
  results;
- no external HTTP;
- removed during cleanup.

Expected safe categories:

```text
source_category=constant_configured
settings_fallback_status=saved
value_visibility=hidden
clear_control=visible
```

### S3: Explicit Fallback Clear While Constant Is Active

Future Step 259 may include exactly one intentional browser Settings save action
for:

```text
clear_openai_api_key
```

This is permitted only when:

- the saved fallback was created as the Step 259 temporary non-credential state;
- the constant fixture is temporary and local-only;
- no actual credential is present or inspected;
- browser action is limited to the displayed clear control and normal Settings
  save;
- no API key field exists or is used;
- no OpenAI Generate, GA4 Fetch, OAuth, external HTTP, screenshot, or Network
  inspection occurs.

Expected post-clear category-level result:

```text
source_category=constant_configured
settings_fallback_status=not_saved
value_visibility=hidden
clear_control=hidden
```

This verifies only visible source/category continuity. It does not verify an
actual constant value or provider usability.

### S4: Final Cleanup Back to Missing

After controlled clear is observed:

- remove the temporary local constant fixture;
- remove any temporary helper or local-only artifact;
- ensure the temporary fallback state is absent;
- return to the missing state without inspecting option values.

Expected human-visible / category-level result:

```text
source_category=missing
settings_fallback_status=not_saved
value_visibility=hidden
clear_control=hidden
```

## Human Browser Checklist by State

### S0: Missing Baseline

For Settings and Report Builder:

- page loads without visible fatal error;
- source category / readiness is `missing`;
- fallback status is `not_saved` where visible;
- value-hidden posture is visible or preserved;
- no fallback password input is visible;
- `clear_openai_api_key` is not visible;
- constant-based configuration is primary guidance;
- Settings storage is not presented as ordinary primary setup route.

### S1: `settings_saved` Legacy / Transitional State

For Settings and Report Builder:

- page loads without visible fatal error;
- source category / readiness is `settings_saved`;
- fallback saved status is visible where applicable;
- legacy / transitional compatibility framing is visible;
- constant-based configuration remains the preferred direction;
- fallback value and password field value are not visible;
- no new fallback entry / replacement input is visible;
- `clear_openai_api_key` is visible in Settings only;
- Settings storage is not presented as ordinary new-user primary guidance.

### S2: `constant_configured + saved fallback`

For Settings and Report Builder:

- page loads without visible fatal error;
- active source / readiness is `constant_configured`;
- preferred constant source wording is visible;
- legacy / transitional saved fallback is described as lower priority where
  applicable;
- fallback value and password field value are not visible;
- no fallback entry / replacement input is visible;
- `clear_openai_api_key` is visible in Settings because saved fallback exists;
- wording does not imply that the constant source is missing, replaced, or
  modified.

### S3: Clear Operation With Active Constant

In Settings only:

- select `clear_openai_api_key`;
- use the normal Settings save action once;
- do not enter or inspect any credential value;
- do not perform any external action.

Then verify in Settings and Report Builder:

- no visible fatal error;
- active source / readiness remains `constant_configured`;
- fallback status becomes `not_saved` where visible;
- `clear_openai_api_key` is no longer visible;
- constant-preferred wording remains visible;
- no password input appears;
- no value becomes visible.

### S4: Cleanup

After removal of temporary local-only state, verify in Settings and Report
Builder:

- no visible fatal error;
- source category / readiness is `missing`;
- fallback status is `not_saved` where visible;
- value-hidden posture remains;
- password input is not visible;
- `clear_openai_api_key` is not visible;
- constant-based configuration remains primary guidance.

## Clear-operation Safety Boundary

The future clear operation is allowed only for the temporary non-credential
fallback created during Step 259.

Clear-operation boundaries:

- clear is fallback-only;
- clear must not change the temporary constant fixture;
- clear must not inspect values;
- clear must not perform external HTTP;
- clear must not trigger OpenAI Generate, GA4 Fetch, or OAuth;
- clear result is recorded only as visible status/category-level evidence.

If the wrong control is visible, the clear control is missing when expected, or
diagnosis would require inspecting a value, Step 259 must stop.

## Cleanup and Aborted-run Cleanup Requirements

Normal cleanup after S3:

1. Remove the temporary local constant fixture.
2. Remove any temporary helper or local-only artifact.
3. Confirm human-visible S4 categories only.
4. Do not inspect option values.
5. Do not record temporary placeholder values.

Abort cleanup if the smoke stops before S3:

- remove any temporary local constant fixture if present;
- remove any temporary helper or local-only artifact if present;
- remove or clear only temporary fallback state by the safest available
  preplanned method that does not print or inspect values;
- if safe cleanup cannot be confirmed without violating evidence boundaries,
  record `Blocked` and leave the next step as cleanup remediation planning.

No cleanup path may use screenshots, browser Network evidence, database dumps,
`wp option get`, raw SQL output, or value inspection.

## Stop Conditions and Result Classification

Future Step 259 must stop immediately, remove any safe temporary state it can
remove, and record `Blocked` or `Fail / Needs narrow correction` if any of the
following occurs:

- baseline is not safely `missing / not_saved / hidden`;
- actual credential / constant / existing saved fallback is suspected;
- pre-existing temporary artifact is found;
- a visible fatal error occurs;
- source category or fallback status is inconsistent with the intended
  controlled state;
- password input remains visible in any state;
- clear control appears when no temporary saved fallback exists;
- clear control is absent when the controlled saved fallback should be present;
- post-clear active source no longer appears `constant_configured`;
- cleanup cannot restore `missing / not_saved / hidden`;
- any evidence boundary would need to be violated to diagnose the issue.

Result classification rules:

```text
Scope-bound Pass:
All applicable state transitions and human-visible checks pass, cleanup succeeds,
and no prohibited evidence or external action occurs.

Fail / Needs narrow correction:
A Step 256 human-visible behavior mismatch occurs, while safety boundaries remain intact.

Blocked:
Baseline, temporary-state, safety, or cleanup preconditions are not safely met.
```

A Scope-bound Pass must not be described as proof of actual credential validity,
actual constant preservation, actual saved fallback contents, OpenAI provider
success, real external communication, WordPress.org release readiness, or public
release approval.

## Human Observation Template

Future Step 259 may use this compact value-free template.

### Baseline S0

```text
Settings loaded: Pass / Fail / Blocked
Visible fatal error on Settings: Yes / No
Report Builder loaded: Pass / Fail / Blocked
Visible fatal error on Report Builder: Yes / No
Settings source category: missing / unexpected / not_observed
Report Builder readiness/source category: missing / unexpected / not_observed
Fallback status: not_saved / unexpected / not_observed
Password input visible: Yes / No
Password field value visible: Yes / No
Clear control visible: Yes / No
Primary guidance route: constant_based / settings_primary / unclear
```

### S1 `settings_saved`

```text
Settings source category: settings_saved / unexpected / not_observed
Report Builder readiness/source category: settings_saved / unexpected / not_observed
Fallback status: saved / unexpected / not_observed
Legacy / transitional wording visible: Yes / No
Preferred constant direction visible: Yes / No
Password input visible: Yes / No
Password field value visible: Yes / No
Clear control visible in Settings: Yes / No
Settings-only ordinary primary route shown: Yes / No
```

### S2 `constant_configured + saved`

```text
Settings source category: constant_configured / unexpected / not_observed
Report Builder readiness/source category: constant_configured / unexpected / not_observed
Fallback status: saved / unexpected / not_observed
Preferred constant source wording visible: Yes / No
Legacy / transitional lower-priority fallback wording visible: Yes / No
Password input visible: Yes / No
Password field value visible: Yes / No
Clear control visible in Settings: Yes / No
Constant source misrepresented as missing/replaced/modified: Yes / No
```

### S3 Clear

```text
Clear action performed only on temporary state: Yes / No
Post-clear Settings source category: constant_configured / unexpected / not_observed
Post-clear Report Builder readiness/source category: constant_configured / unexpected / not_observed
Post-clear fallback status: not_saved / unexpected / not_observed
Post-clear clear control visible: Yes / No
Post-clear password input visible: Yes / No
Post-clear password field value visible: Yes / No
Visible fatal error: Yes / No
```

### S4 Cleanup

```text
Temporary fixture/helper removed: Yes / No
wp-dev mu-plugin location clean of Step 259 fixture: Yes / No
Settings source category: missing / unexpected / not_observed
Report Builder readiness/source category: missing / unexpected / not_observed
Fallback status: not_saved / unexpected / not_observed
Password input visible: Yes / No
Password field value visible: Yes / No
Clear control visible: Yes / No
Visible fatal error: Yes / No
```

### Safety

```text
Actual credential / constant / option values inspected or recorded: Yes / No
OpenAI Generate executed: Yes / No
GA4 Fetch executed: Yes / No
OAuth executed: Yes / No
External provider communication intentionally triggered: Yes / No
Screenshots collected: Yes / No
Network evidence collected: Yes / No
Forbidden evidence recorded: Yes / No
```

## Explicit Non-conclusions

Step 258 does not conclude:

- human-visible UI pass;
- actual API key validity;
- actual constant value;
- actual constant preservation;
- actual Settings fallback option value;
- actual saved fallback contents;
- actual provider authorization;
- actual OpenAI request success;
- actual request / response behavior;
- OpenAI provider success;
- real external communication;
- Plugin Check status;
- WordPress.org release readiness;
- public-release approval.

## Recommended Step 259

Recommended next step:

```text
Step 259 candidate — OpenAI Settings fallback legacy/transitional controlled human admin smoke results
```

Step 259 must not begin in Step 258. It should record only human-provided,
value-free, status/category-level results after the controlled smoke is
performed separately.
