# Step 286: OAuth Client Configuration Readiness Restoration and Controlled Preflight Re-entry Plan

## Step Objective and Planning Limits

Step 286 is a docs-only / planning-only response to the category-level
condition reported during Step 285 Stage 1:

```text
oauth_client_source_category: missing
```

The objective is to define how a human operator may establish OAuth client
configuration readiness in the controlled `wp-dev` environment and then return
to a new Step 285 preflight re-entry checkpoint.

Step 286 does not configure an OAuth client source, save Settings, inspect
configuration values, start browser OAuth, contact a provider, or complete
Step 285.

```text
WordPress.org public release readiness:
Hold
```

## Working-tree Baseline Classification

The following commands were run before this document was added:

```text
git status --short --untracked-files=all
git diff --name-only
git diff --check
```

All three commands returned no output.

Baseline classification:

```text
Clean committed Step 284 baseline
```

Repository history showed Step 284 at `HEAD`. No Step 285 document existed,
consistent with Stage 2 not having started.

## Triggering Step 285 Blocked Finding

Human-controlled category observation:

```text
oauth_client_source_category: missing
```

Stage 2 preflight recording:

```text
Blocked
```

Boundary at the time of the finding:

```text
No provider interaction occurred.
No browser OAuth occurred.
No human authorization was inferred.
No Step 285 document was added.
```

This finding means only that the controlled `wp-dev` environment did not
present a complete active OAuth client configuration from either permitted
source.

It does not mean:

- Google OAuth authorization failed;
- provider authorization failed;
- a callback failed;
- authorization-code exchange failed;
- a token endpoint request failed;
- refresh failed;
- provider-side revoke failed;
- current source behavior is contradictory;
- a secret value was inspected;
- a secret value must be disclosed to CODEX.

## OAuth Client Configuration Versus Authorization State

OAuth client configuration readiness and provider authorization are separate
boundaries.

An OAuth client source category describes only whether the plugin can select a
complete request-local client configuration under its current resolver rules.
It does not describe:

- provider account state;
- browser redirect outcome;
- provider consent;
- callback or state-validation outcome;
- authorization-code exchange outcome;
- token validity or storage;
- GA4 credential usability;
- refresh or provider-side revoke.

Changing a permitted configuration source and then observing a category label
does not authorize or begin browser OAuth.

## Evidence Sources

The requested Step 186 through Step 188 concepts exist under these current
repository filenames:

- `docs/maturation/step186-oauth-client-configuration-source-strategy-implementation-plan.md`;
- `docs/maturation/step187-oauth-client-configuration-source-level-inventory.md`;
- `docs/maturation/step188-oauth-client-configuration-hybrid-source-implementation-plan.md`.

Additional reviewed records:

- `docs/maturation/step198-oauth-client-configuration-hybrid-source-final-maturation-checkpoint.md`;
- `docs/maturation/step280-wordpress-org-hold-gate-prioritization-checkpoint.md`;
- `docs/maturation/step281-oauth-lifecycle-remaining-hold-gate-release-boundary-planning-checkpoint.md`;
- `docs/maturation/step282-oauth-refresh-provider-revoke-and-provider-runtime-source-level-release-boundary-verification-results.md`;
- `docs/maturation/step283-oauth-refresh-provider-revoke-and-provider-runtime-initial-public-release-disposition-decision-checkpoint.md`;
- `docs/maturation/step284-implemented-authorization-code-oauth-flow-controlled-provider-runtime-verification-gate-planning-checkpoint.md`.

Current source and wording were reviewed read-only in:

- `includes/functions-utils.php`;
- `includes/class-settings.php`;
- `includes/class-admin.php`;
- `includes/class-report-builder.php`;
- `readme.txt`.

The inspection was limited to symbols, control-flow categories, and static
wording. No option, constant, identifier, secret, URL, token, account, request,
or response value was inspected.

## Current Source-category Model

| Category | Configuration availability meaning | Browser OAuth preflight | Controlled readiness state | Value-free observation |
|---|---|---|---|---|
| `oauth_client_source_category: constants` | A complete constants-based source is selected under the current resolver rules. | May return to a new Step 285 preflight; browser OAuth still requires new explicit authorization. | Acceptable controlled readiness category. | Yes, through the category label only. |
| `oauth_client_source_category: settings` | Constants are absent and a complete Settings fallback source is selected. | May return to a new Step 285 preflight only when the human operator intentionally chose the permitted fallback route; browser OAuth still requires new explicit authorization. | Acceptable controlled fallback readiness category. | Yes, through the category label only. |
| `oauth_client_source_category: missing` | No complete permitted source is available. | Must not proceed. | `Blocked`. | Yes, through the category label only. |
| `oauth_client_source_category: incomplete` | At least one configuration source is partial and no complete active source can be selected. | Must not proceed. | `Blocked`. | Yes, through the category label only. |
| `oauth_client_source_category: conflict` | Current conservative precedence rules reject the available source combination. | Must not proceed. | `Blocked`. | Yes, through the category label only. |
| `oauth_client_value_hidden_status: hidden` | OAuth client values are not displayed through the status UI. | Required alongside an acceptable source category before preflight re-entry. | Required evidence category. | Yes. |

The `constants` and `settings` categories indicate configuration availability,
not completed Google authorization.

## Existing Hybrid-source Policy Boundary

The current maturation and source boundary is:

```text
Preferred controlled source:
constants

Permitted fallback source:
settings, only when constants are missing, the Settings fallback is complete,
and the human operator intentionally selects that controlled route

Blocked categories:
missing
incomplete
conflict

Required display boundary:
oauth_client_value_hidden_status: hidden
```

Current resolver behavior gives complete constants precedence. A complete
Settings fallback is selected only when constants are missing. Incomplete
constants combined with a complete Settings fallback are treated
conservatively as `conflict`; the resolver does not combine configuration
parts from different sources.

The Settings fallback is an established source route, but it stores
configuration in plugin Settings. It is therefore a permitted fallback for
this controlled development readiness restoration, not the preferred route.

## Alternatives Considered

### Option A: Human-managed Constants-based Controlled Readiness

Current policy and source evidence:

- complete constants are the preferred source;
- complete constants take precedence over Settings fallback;
- the active UI evidence remains a category label;
- the configuration mechanism is loaded before plugin runtime.

Readiness result it can establish:

```text
oauth_client_source_category: constants
oauth_client_value_hidden_status: hidden
```

Operation boundary:

- a human operator manages the controlled environment configuration;
- CODEX does not create, edit, inspect, or receive the configuration;
- the operator returns category-only observations.

Production/public change:

- no production source or public wording change is required.

Compatibility:

- compatible with the Step 284 / Step 285 containment boundary;
- can return to Step 285 without provider interaction.

Major risk:

- configuration must not be copied into chat, docs, source files, logs, or
  evidence;
- the human operator must avoid accidental provider action after restoration.

Disposition:

```text
Preferred controlled source route selected
```

### Option B: Human-managed Settings Fallback

Current policy and source evidence:

- the hybrid policy permits a complete Settings fallback when constants are
  missing;
- values are not redisplayed in the normal Settings UI;
- source, fallback, and value-hidden states are exposed as categories;
- delete semantics are scoped to Settings fallback configuration.

Readiness result it can establish:

```text
oauth_client_source_category: settings
oauth_client_value_hidden_status: hidden
```

Operation boundary:

- the human operator must intentionally choose this route;
- only the existing controlled Settings mechanism may be used;
- CODEX does not perform the save or inspect the stored result;
- the operator returns category-only observations.

Production/public change:

- no production source or public wording change is required.

Compatibility:

- compatible with Step 284 / Step 285 only when the human operator separately
  selects it and stops before OAuth Connect;
- can return to Step 285 without provider interaction.

Major risk:

- it creates or changes credential-bearing Settings storage;
- incomplete or conflicting source state must remain `Blocked`;
- saved configuration must not be redisplayed or used as support evidence.

Disposition:

```text
Permitted fallback route recorded
```

### Option C: Treat Missing as a Product/source Defect

Current policy and source evidence:

- the resolver deliberately defines `missing` for an environment with no
  complete source;
- existing maturation records describe that category and the resulting
  pre-redirect block;
- no category-level evidence indicates contradictory source behavior.

Readiness result it can establish:

- none without a new product or source decision.

Secret handling:

- redesign is not needed merely to avoid disclosing a secret;
- no secret evidence would justify source modification in this checkpoint.

Production/public change:

- would unnecessarily reopen a matured hybrid-source track.

Compatibility:

- not compatible with the narrow restoration objective unless a future
  category-level finding contradicts current policy.

Major risk:

- architecture churn and scope expansion based on an expected environmental
  category.

Disposition:

```text
Deferred / separate release gate
```

Option C is not selected. A future contradictory category-level finding would
route to `Needs policy escalation`.

### Option D: Start Browser OAuth While Missing

Current policy and source evidence:

- OAuth Connect checks the shared resolver;
- `missing`, `incomplete`, and `conflict` do not satisfy its start
  precondition;
- Step 285 explicitly blocks provider interaction for these categories.

Readiness result it can establish:

- none.

Secret handling:

- does not resolve configuration availability and creates unnecessary external
  interaction risk.

Production/public change:

- none, but it would violate the controlled execution plan.

Compatibility:

- incompatible with Step 284 / Step 285.

Major risk:

- unauthorized provider interaction despite a known blocked precondition.

Disposition:

```text
Blocked
```

Option D is not selected.

## Preferred Controlled Route and Permitted Fallback Route

Selected planning disposition:

```text
Preferred route:
Option A — human-managed constants-based controlled readiness

Fallback route:
Option B — human-managed Settings fallback, only when intentionally selected
under the existing hybrid-source policy
```

Neither route is performed by Step 286. Neither route authorizes OAuth Connect,
browser navigation, provider sign-in, consent, callback, token exchange, or
another external action.

## Human-operation and Non-disclosure Boundary

The human operator may later:

- choose exactly one permitted source route;
- provide OAuth client configuration only through that chosen controlled
  environment mechanism;
- avoid showing or copying identifiers, secrets, URLs, tokens, account
  information, option values, or constant values to CODEX or chat;
- return only the post-change source and value-hidden categories;
- stop before OAuth Connect or any provider interaction.

If the Settings route is intentionally selected, the separately chosen
readiness-establishment save is the only permitted Settings mutation. It is
not browser OAuth authorization.

The human operator must not perform as part of readiness restoration:

- OAuth Connect;
- browser redirect, provider sign-in, or consent;
- callback or authorization-code exchange;
- token endpoint communication;
- refresh or provider-side revoke;
- GA4 Fetch or OpenAI Generate;
- local disconnect;
- screenshots or browser Network inspection.

CODEX must not:

- request, generate, paste, or receive secret values;
- create a secret-bearing file;
- modify `wp-config.php` or deployment configuration;
- perform a Settings save;
- inspect option or constant values;
- execute browser OAuth or provider interaction;
- infer provider authorization from configuration readiness.

## Controlled Readiness-restoration Result Templates

### Acceptable Category Result

The human operator may return this template after completing exactly one
chosen readiness-restoration operation:

```text
OAuth client readiness restoration result

1. Chosen source route
[ ] constants
OR
[ ] settings

2. Post-change source category
[ ] oauth_client_source_category: constants
OR
[ ] oauth_client_source_category: settings

3. Value-hidden status
[ ] oauth_client_value_hidden_status: hidden

4. Boundary confirmation
[ ] No client identifier, client secret, URL, token, provider account detail,
    option value, or constant value was viewed, copied, recorded, or shared.

5. No provider interaction confirmation
[ ] No OAuth Connect, browser redirect, provider sign-in, consent, callback,
    authorization-code exchange, token endpoint communication, refresh,
    revoke, GA4 Fetch, OpenAI Generate, local disconnect, screenshot, or
    Network inspection was performed.
```

### Blocked Category Result

If an acceptable category is not obtained, the human operator may return:

```text
OAuth client readiness restoration blocked result

1. Post-change source category
[ ] oauth_client_source_category: missing
OR
[ ] oauth_client_source_category: incomplete
OR
[ ] oauth_client_source_category: conflict

2. Boundary confirmation
[ ] No client identifier, client secret, URL, token, provider account detail,
    option value, or constant value was viewed, copied, recorded, or shared.

3. No provider interaction confirmation
[ ] No browser OAuth or provider interaction was performed.
```

This blocked route does not request secret disclosure, direct database
operation, source modification, or browser OAuth.

## Step 285 Controlled Preflight Re-entry Routing

If the returned category is `constants` or `settings` and the value-hidden
category is `hidden`:

1. Request a new controlled Step 285 preflight re-entry checkpoint.
2. Do not begin browser OAuth.
3. Reconfirm the clean committed baseline.
4. Record the new category-level readiness observation.
5. Confirm the controlled `wp-dev` environment and initial single-site scope.
6. Confirm that no uncertain or in-progress OAuth test state will be reused.
7. Obtain new explicit authorization for one future bounded provider
   interaction.
8. Obtain a separate local-only cleanup authorization choice.
9. Obtain a new stop-condition acknowledgment.
10. Do not reuse or infer authorization from any prior Step 285 exchange.

If the returned category is `missing`, `incomplete`, or `conflict`:

```text
Blocked
```

Browser OAuth must not begin. A narrowly scoped source-policy or
configuration-readiness follow-up is warranted only when the new
category-level finding cannot be explained by the current resolver policy.
Such a contradiction routes to:

```text
Needs policy escalation
```

The earlier Step 285 read-only review need not be repeated wholesale. Re-entry
must focus on the changed category, current clean baseline, no reused state,
fresh authorizations, and stop acknowledgment.

## Plan Matrix

| Source route or category | Current policy / source evidence | Human operation boundary | Permitted category-only evidence | Prohibited evidence or action | Preflight re-entry disposition | Escalation boundary |
|---|---|---|---|---|---|---|
| Constants route | Complete constants are preferred and take precedence. | Human manages controlled pre-plugin configuration; CODEX does not edit or inspect it. | `oauth_client_source_category: constants` | Values, identifiers, secret-bearing files, configuration output, provider action. | Return to a new Step 285 preflight when `hidden` is also observed. | Unexpected non-`constants` category after a confirmed human-managed route remains `Blocked`; escalate policy only if current rules cannot explain it. |
| Settings fallback route | Permitted only when constants are missing and fallback is complete. | Human intentionally chooses and uses only the existing Settings mechanism. | `oauth_client_source_category: settings` | Value redisplay, option inspection, database access, provider action, mixed-source completion. | Return to a new Step 285 preflight when `hidden` is also observed. | Unexpected incomplete/conflict behavior remains `Blocked`; escalate policy only for a category-level contradiction. |
| Missing category | No complete source is available. | No configuration inference or OAuth action. | `oauth_client_source_category: missing` | Browser OAuth, secret request, source redesign based only on absence. | `Blocked`. | Follow up only after a human-managed source choice or new contradictory category evidence. |
| Incomplete category | A source is partial and no complete active source is available. | Stop without identifying or sharing which configuration part exists. | `oauth_client_source_category: incomplete` | Component/value inspection, mixed-source completion, browser OAuth. | `Blocked`. | Human corrects the chosen route privately or requests a category-only policy follow-up. |
| Conflict category | Conservative resolver rules reject the source combination. | Stop without inspecting values or combining sources. | `oauth_client_source_category: conflict` | Source mixing, option/constant inspection, browser OAuth. | `Blocked`. | Human restores one coherent permitted route; policy escalation only for a source-rule contradiction. |
| Post-change value-hidden status | Current UI exposes a non-value status. | Human observes only the status label. | `oauth_client_value_hidden_status: hidden` | Values, fragments, masked values, screenshots, Network evidence. | Required with `constants` or `settings`. | Missing or contradictory value-hidden category remains `Blocked`. |
| Browser OAuth non-occurrence | Step 286 is restoration planning only. | Human stops before OAuth Connect. | Confirmation that no provider interaction occurred. | Redirect, provider sign-in, consent, callback, exchange, retry. | Required before Step 285 re-entry. | Any provider interaction outside a later explicit authorization requires immediate stop. |
| Step 285 preflight re-entry | Step 284 requires separate fresh preflight and authorization. | Human supplies new category observations and all confirmation categories. | Clean baseline, permitted source, `hidden`, no reused state, fresh authorization and stop categories. | Reused authorization, implied permission, provider action during preflight. | Begin a new preflight checkpoint, not provider execution. | Any missing or ambiguous confirmation remains `Blocked`. |

## Explicit Non-claims

Step 286 does not determine or prove:

- OAuth client value validity;
- OAuth provider authorization;
- provider account identity;
- browser OAuth outcome;
- callback outcome;
- authorization-code exchange outcome;
- token endpoint outcome;
- token validity;
- refresh or revoke outcome;
- provider-side cleanup;
- GA4 retrieval behavior;
- OpenAI behavior;
- storage certification;
- encryption at rest;
- legal or privacy-law compliance;
- WordPress.org policy compliance;
- final package correctness;
- final Plugin Check outcome;
- public-release approval.

## Public Release Implication

```text
WordPress.org public release readiness remains Hold.
```

Step 286 plans controlled restoration of OAuth client-configuration readiness
only.

It does not configure credentials, authorize or execute provider interaction,
begin browser OAuth, perform token exchange, refresh, revoke, GA4 Fetch,
OpenAI Generate, local cleanup, final packaging, isolated Plugin Check, or
public release.

The narrow provider/runtime validation gate selected by Step 283 remains open.
Step 285 remains incomplete and must be re-entered with fresh category-level
evidence and explicit human confirmations after readiness restoration.

## Recommended Next Step

```text
Human-controlled OAuth client-readiness restoration,
followed by a new Step 285 preflight re-entry checkpoint
```

The human-controlled operation must use exactly one permitted source route and
return only the applicable result template.

A new Step 285 re-entry request may be prepared only after:

```text
oauth_client_source_category: constants
OR
oauth_client_source_category: settings

AND

oauth_client_value_hidden_status: hidden
```

have been reported with confirmation that no provider interaction occurred.

Step 286 conclusion:

```text
OAuth client-readiness restoration plan completed
Preferred controlled source route selected
Permitted fallback route recorded
Deferred / separate release gate
```
