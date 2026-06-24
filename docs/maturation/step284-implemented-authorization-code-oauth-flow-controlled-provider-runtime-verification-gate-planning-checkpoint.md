# Step 284: Implemented Authorization-code OAuth Flow Controlled Provider/runtime Verification-gate Planning Checkpoint

## Step Objective and Planning Limits

Step 284 is a docs-only / planning-only checkpoint for the initial-release
validation gate selected by Step 283.

The purpose is to design a future controlled provider/runtime verification
limited to the currently implemented authorization-code OAuth flow. This
document defines:

- the exact bounded flow;
- evidence and privacy limits;
- clean-state and environment preconditions;
- explicit operator-authorization requirements;
- observation phases;
- stop and containment conditions;
- optional local-only cleanup limits;
- result routing;
- sequencing before final-stage release work.

Step 284 does not authorize or execute the future verification.

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
Clean working tree
```

Repository history confirmed that Step 283 was committed at the baseline.
There were no pre-existing source, public-documentation, maturation-document,
tool, or environment changes.

## Step 283 Validation-gate Input and Non-scope

Step 283 selected this initial-release disposition:

```text
Refresh execution:
Explicitly deferred / future implementation gate

Provider-side revoke:
Explicitly deferred / future implementation gate

Complete OAuth provider/runtime lifecycle verification:
Explicitly deferred / future implementation gate

Narrow provider/runtime validation of the implemented authorization-code
OAuth flow:
Initial-release validation gate
```

Step 284 preserves that disposition.

The future controlled verification may cover only:

- browser authorization redirect category;
- callback and state-validation category;
- authorization-code exchange category;
- local OAuth token-storage handoff category;
- lifecycle/readiness category after callback return;
- Report Builder OAuth credential-use entry category without GA4 Fetch.

The Report Builder observation is limited to how the UI classifies OAuth
credential readiness. It does not permit a GA4 API request, analytics
retrieval, report payload creation, OpenAI request, or generated report.

Explicitly outside both Step 284 and the future bounded verification:

- refresh execution or a refresh-token grant;
- refresh response handling, retry, or token replacement;
- provider-side revoke or token invalidation;
- provider-side cleanup validation;
- complete OAuth lifecycle validation;
- broad provider-account behavior;
- GA4 Fetch or analytics-data inspection;
- OpenAI Generate or generated-report behavior;
- credential-source or storage-model redesign;
- multisite or network lifecycle behavior;
- final package validation;
- isolated Plugin Check;
- a WordPress.org release decision.

## Evidence Sources

Primary planning input:

- `docs/maturation/step283-oauth-refresh-provider-revoke-and-provider-runtime-initial-public-release-disposition-decision-checkpoint.md`.

Supporting records:

- `docs/maturation/step145-narrow-token-exchange-production-implementation-results.md`;
- `docs/maturation/step147-human-controlled-token-exchange-smoke-results.md`;
- `docs/maturation/step198-oauth-client-configuration-hybrid-source-final-maturation-checkpoint.md`;
- `docs/maturation/step204-oauth-token-lifecycle-narrow-production-implementation-results.md`;
- `docs/maturation/step205-oauth-token-lifecycle-source-level-verification-results.md`;
- `docs/maturation/step207-oauth-token-lifecycle-controlled-human-admin-smoke-results.md`;
- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`;
- `docs/maturation/step215-uninstall-cleanup-maturation-checkpoint.md`;
- `docs/maturation/step223-manual-google-access-token-fallback-retirement-maturation-checkpoint.md`;
- `docs/maturation/step227-readme-privacy-wording-alignment-after-manual-token-retirement-maturation-checkpoint.md`;
- `docs/maturation/step280-wordpress-org-hold-gate-prioritization-checkpoint.md`;
- `docs/maturation/step281-oauth-lifecycle-remaining-hold-gate-release-boundary-planning-checkpoint.md`;
- `docs/maturation/step282-oauth-refresh-provider-revoke-and-provider-runtime-source-level-release-boundary-verification-results.md`.

Historical human observations show that status/category-level OAuth evidence
can be recorded without copying credential-bearing material. They do not
authorize a new provider interaction and do not replace the Step 285 preflight.

## Evidence and Privacy Boundary

### Permitted Evidence Categories

The future controlled verification may record only:

- verification phase category;
- local precondition category;
- OAuth client-source readiness category without configuration values;
- browser redirect initiation category without a URL;
- callback return category;
- state-validation outcome category;
- authorization-code exchange outcome category;
- local token-storage handoff outcome category;
- lifecycle/readiness category after callback return;
- Report Builder OAuth credential-use entry category;
- local-only cleanup initiation and completion category, only when separately
  authorized;
- stop-condition category.

Each observation must be normalized into a predefined category. Raw provider
or browser text must not be copied into the result record.

### Prohibited Evidence

The future verification must not display, inspect, copy, record, summarize, or
capture:

- credentials or passwords;
- API keys;
- access, refresh, or other OAuth token material;
- authorization codes;
- callback or redirect URLs;
- query parameters;
- option or constant values;
- Authorization or other request headers;
- request or response bodies;
- raw provider errors;
- raw payload JSON;
- actual analytics values;
- generated report text;
- browser Network evidence;
- screenshots;
- database contents;
- provider account details;
- personally identifying account information.

The operator must not use browser developer tools, Network inspection, option
value commands, SQL, database dumps, logs containing request material, or
screen captures as evidence.

### Evidence Sufficiency

The selected boundary needs only enough category-level evidence to determine
whether the implemented flow reached its planned phase categories.

It does not need:

- credential-value confirmation;
- URL inspection;
- token-value confirmation;
- provider response inspection;
- GA4 data retrieval;
- provider account-state inspection.

`Controlled provider/runtime category observed` means only that the approved
category-level observation was reported for the bounded phase. It does not
establish provider account state, token validity, authorization scope,
complete OAuth lifecycle behavior, refresh, revoke, provider cleanup, or GA4
access behavior.

## Current Implemented-flow Scope

The future verification may observe this sequence once:

1. The controlled Settings screen reports a suitable OAuth client-source
   readiness category without displaying configuration values.
2. The human operator manually starts the existing browser authorization
   action.
3. The browser reaches the provider flow without recording the destination or
   browser URL.
4. The human operator uses only the pre-authorized controlled provider
   account/context.
5. One callback return is observed.
6. The callback/state-validation result is recorded as a predefined category.
7. The authorization-code exchange result is recorded as a predefined
   category.
8. The local token-storage handoff result is recorded as a predefined
   category.
9. The Settings lifecycle/readiness return category is recorded.
10. Report Builder is opened only to observe the OAuth credential-use entry
    category.
11. GA4 Fetch is not used.
12. If local token material was created, an independently authorized
    local-only cleanup may be performed once under the existing disconnect
    boundary.

The plan permits no repeated consent attempts, alternate account attempts,
retry loops, branch exploration, or expansion into another service action.

## Future Verification Phases

### Phase 0: Planning Completed

Step 284 itself:

- adds this planning document only;
- performs no provider interaction;
- performs no browser OAuth;
- performs no Settings save;
- performs no local-state mutation;
- performs no cleanup.

Phase 0 result routing:

```text
Deferred / separate release gate
```

The initial-release validation gate remains open.

### Phase 1: Preflight and Explicit Operator-authorization Checkpoint

Before any provider interaction, a separate docs-only / preflight-only
checkpoint must confirm category-level facts:

- the repository has a clean committed baseline;
- the controlled development environment is selected;
- no multisite or network lifecycle scope is involved;
- no unrelated working-tree change is pending;
- no in-progress OAuth test state is being reused;
- the OAuth client-source readiness category is suitable without viewing
  configuration values;
- the operator identifies that an authorized controlled provider account will
  be used without recording its identity;
- the operator explicitly authorizes one bounded provider interaction;
- the operator acknowledges all stop conditions;
- the operator separately authorizes or declines one local-only cleanup after
  the observation if local token material is created.

Phase 1 must be completed before Phase 2. A missing, ambiguous, stale, or
declined confirmation routes to:

```text
Blocked
```

Step 284 does not perform Phase 1.

### Phase 2: Narrow Controlled Provider/runtime Observation

Only after a completed Phase 1 may a later explicitly authorized step perform:

- one manually initiated browser authorization redirect;
- one bounded callback return observation;
- one category-level state-validation observation;
- one category-level authorization-code exchange observation;
- one category-level local token-storage handoff observation;
- one category-level lifecycle/readiness observation;
- one Report Builder OAuth credential-use entry observation without GA4
  Fetch.

Phase 2 explicitly prohibits:

- retries or repeated consent attempts;
- alternate account attempts;
- GA4 Fetch or analytics-data inspection;
- OpenAI Generate;
- report payload inspection;
- refresh;
- provider-side revoke;
- provider-side cleanup;
- browser developer tools or Network inspection;
- screenshots;
- raw URL or callback parameter inspection;
- token, credential, option, or constant inspection.

Step 284 does not perform Phase 2.

### Phase 3: Stop, Containment, and Optional Local-only Cleanup

The future execution must stop immediately if any of the following occurs:

- unexpected navigation or redirect category;
- unexpected account context;
- unexpected external-request category;
- unexpected Settings or Report Builder state;
- unexpected callback/state category;
- unexpected authorization-code exchange or storage category;
- any sensitive value appears;
- a provider error category appears;
- a category cannot be normalized into the approved vocabulary;
- the operator is uncertain about the active phase or account context;
- the scope would need a retry, alternate account, refresh, revoke, GA4 Fetch,
  OpenAI Generate, raw inspection, or Network inspection.

Containment rules:

- no automatic or manual retry within the same execution record;
- do not inspect raw callback, provider, request, or response material;
- record only the execution phase and predefined stop category;
- do not broaden into another action;
- route unexpected category behavior to
  `Needs follow-up provider/runtime alignment`;
- route missing prerequisites or forbidden-evidence risk to `Blocked`.

Optional local-only cleanup may be used only when:

- local token-storage handoff was observed at category level;
- Phase 1 explicitly authorized cleanup before provider interaction;
- the existing local-only disconnect boundary is used;
- cleanup is limited to local state restoration;
- no token or option value is inspected.

Local cleanup must not:

- be represented as provider-side revoke;
- be represented as provider-side cleanup;
- inspect token material;
- delete OAuth client Settings fallback configuration;
- alter OpenAI configuration;
- imply account-level or complete lifecycle cleanup.

If cleanup was not explicitly authorized in Phase 1, the execution must stop
after recording the bounded observation and must not improvise cleanup.

Step 284 does not perform Phase 3.

## Preconditions and Explicit Operator-authorization Requirements

The future preflight must produce explicit category-level confirmations for:

| Requirement | Required preflight disposition |
| --- | --- |
| Clean committed repository baseline | `Preflight category confirmed` |
| Controlled development environment selected | `Preflight category confirmed` |
| Single-site scope only | `Preflight category confirmed` |
| No unrelated working-tree changes | `Preflight category confirmed` |
| No reused or uncertain OAuth test state | `Preflight category confirmed` |
| OAuth client-source readiness category suitable | `Preflight category confirmed` |
| Controlled provider-account use confirmed without identity recording | `Explicit operator authorization confirmed` |
| One bounded provider interaction explicitly authorized | `Explicit operator authorization confirmed` |
| Stop conditions acknowledged | `Explicit operator authorization confirmed` |
| Local-only cleanup choice explicitly authorized or declined | `Explicit operator authorization confirmed` |

Provider interaction must not begin when any required confirmation is absent.
Planning, source evidence, or historical observations do not imply operator
authorization.

The operator authorization must be:

- specific to one controlled execution;
- specific to the implemented authorization-code flow;
- explicit about external provider interaction;
- separate from any cleanup authorization;
- revocable before the browser action begins;
- recorded only as a category, without account identity or configuration
  values.

## Permitted Evidence and Prohibited Evidence

### Permitted Phase Observations

The future execution record may contain:

- `phase_1_preflight`;
- `phase_2_browser_redirect`;
- `phase_2_callback_return`;
- `phase_2_state_validation`;
- `phase_2_authorization_code_exchange`;
- `phase_2_local_storage_handoff`;
- `phase_2_lifecycle_readiness`;
- `phase_2_report_builder_credential_entry`;
- `phase_3_stop_containment`;
- `phase_3_local_cleanup`.

For each phase, the record may contain only one controlled outcome from the
vocabulary defined below.

### Prohibited Observation Methods

The execution record must not rely on:

- source or configuration value output;
- browser address-bar transcription;
- provider screen transcription;
- raw error text;
- request or response inspection;
- database or option inspection;
- token-presence inspection outside category-level UI state;
- screenshot or video evidence;
- Network evidence;
- analytics or generated-content inspection.

## Preconditions and Stop-condition Matrix

| Phase | Preconditions | Permitted action category | Permitted evidence category | Prohibited action or evidence | Stop condition | Result routing |
| --- | --- | --- | --- | --- | --- | --- |
| Planning-only baseline | Clean Step 283 committed baseline | Add Step 284 planning document | Planning phase and command-result categories | Provider interaction, browser OAuth, Settings mutation, cleanup | Any non-clean baseline or scope conflict | `Blocked` |
| Preflight readiness | Separate preflight step; clean repository; controlled single-site environment; no reused test state | Read-only category checks | Local precondition and client-source readiness categories | Value inspection, Settings save, provider interaction | Missing, ambiguous, stale, or contradictory precondition | `Blocked` |
| Explicit provider authorization | Preflight readiness confirmed | Record one-execution operator decision | Operator authorization category only | Account identity, provider details, implied or standing authorization | Authorization absent, declined, withdrawn, or unclear | `Blocked` |
| Browser authorization redirect | Explicit one-execution provider authorization confirmed | One manual redirect initiation | Redirect initiation category without URL | URL recording, retries, alternate accounts, Network inspection | Unexpected redirect, account context, sensitive display, or operator uncertainty | `Needs follow-up provider/runtime alignment` |
| Callback / state-validation return | One bounded redirect initiated | Observe one callback return and state category | Callback and state-validation categories | Query inspection, raw state, callback URL, raw provider error | Missing normalization category, provider error category, unexpected return | `Needs follow-up provider/runtime alignment` |
| Authorization-code exchange category | Expected callback/state category observed | Observe the existing exchange outcome category | Authorization-code exchange outcome category | Code, headers, request/response material, retry | Unexpected category, sensitive display, or additional request required | `Needs follow-up provider/runtime alignment` |
| Local storage handoff category | Expected exchange category observed | Observe the existing local storage handoff category | Local token-storage handoff category | Option/database/token inspection | Unexpected category, sensitive display, or ambiguous local state | `Needs follow-up provider/runtime alignment` |
| Lifecycle / readiness category | Expected callback return completed | Observe Settings lifecycle/readiness category | Lifecycle/readiness category | Token inspection, refresh, revoke, Settings save | Unexpected or unavailable approved category | `Needs follow-up provider/runtime alignment` |
| Report Builder credential-use entry category | Expected lifecycle/readiness category observed | Open Report Builder and observe credential-use entry category | Report Builder OAuth credential-use entry category | GA4 Fetch, analytics inspection, payload creation | Any action would be required beyond category observation | `Deferred / separate release gate` |
| Local-only cleanup, if explicitly authorized | Local storage handoff observed; cleanup pre-authorized | One existing local-only disconnect action | Local cleanup initiation/completion category | Provider revoke, option inspection, OAuth-client or OpenAI changes | Cleanup not pre-authorized, category mismatch, or sensitive display | `Local-only cleanup category observed` or `Needs follow-up provider/runtime alignment` |
| Unexpected path or sensitive-exposure containment | Any phase detects an unexpected condition | Stop immediately; no retry | Phase and stop-condition category | Additional inspection, retry, alternate account, scope expansion | Immediate on detection | `Needs follow-up provider/runtime alignment` or `Blocked` |

## Stop Conditions, Containment, and Local Cleanup Boundary

The future execution is one bounded attempt. A stop condition closes the
attempt.

After a stop:

- no retry is permitted in the same step;
- no additional provider or browser investigation is permitted;
- no raw material is inspected;
- no new fixture, script, or workaround is introduced;
- no GA4 or OpenAI action is used to diagnose the result;
- no refresh or revoke path is introduced or attempted;
- only the phase and controlled outcome are recorded.

Routing:

- expected approved phase categories:
  `Controlled provider/runtime category observed`;
- unexpected category or source/runtime mismatch:
  `Needs follow-up provider/runtime alignment`;
- missing precondition, absent authorization, or evidence-boundary risk:
  `Blocked`;
- work intentionally outside the authorization-code flow:
  `Deferred / separate release gate`.

Local-only cleanup:

- is optional and separately authorized;
- restores local plugin state only;
- uses the current bounded disconnect behavior;
- does not contact the provider;
- does not revoke provider access;
- does not prove provider cleanup;
- does not inspect or record token material;
- does not affect OAuth client Settings fallback or OpenAI configuration.

## Controlled Outcome Vocabulary

The future controlled execution record must use only:

- `Preflight category confirmed`;
- `Explicit operator authorization confirmed`;
- `Controlled provider/runtime category observed`;
- `Local-only cleanup category observed`;
- `Needs follow-up provider/runtime alignment`;
- `Blocked`;
- `Deferred / separate release gate`.

The vocabulary is deliberately narrower than source status labels or raw
provider messages.

`Controlled provider/runtime category observed` means only that a predefined
category was observed for the approved phase. It does not establish:

- provider account identity or state;
- token validity;
- authorization scope;
- provider-side persistence or cleanup;
- complete OAuth lifecycle behavior;
- refresh or revoke behavior;
- GA4 request or retrieval behavior;
- release readiness.

## Future Execution Routing Alternatives

### Option A: Proceed Directly to Controlled Provider/runtime Execution

What it would clarify:

- category-level observations for the implemented flow.

What it would not clarify:

- refresh, revoke, complete provider lifecycle, or final release status.

Privacy and containment implications:

- the required environment, authorization, account, stop, and cleanup
  confirmations would be combined with execution, reducing the separation
  between permission and action.

Relationship to Step 283:

- it targets the selected validation gate but does not provide an independent
  preflight record.

Explicit authorization:

- possible, but insufficiently separated from action.

Static-check substitution risk:

- low, but the execution gate would begin without a dedicated clean-state and
  authorization checkpoint.

Disposition:

```text
Deferred / separate release gate
```

Option A is not selected.

### Option B: Require a Separate Preflight and Explicit Operator-authorization Checkpoint

What it would clarify:

- clean-state and environment readiness;
- the bounded client-source readiness category;
- single-site scope;
- no reused OAuth test state;
- explicit permission for one provider interaction;
- explicit cleanup choice;
- stop-condition acknowledgment.

What it would not clarify:

- provider/runtime behavior, because the preflight remains non-executing;
- refresh, revoke, provider cleanup, or complete lifecycle behavior.

Privacy and containment implications:

- separates permission and environment confirmation from provider action;
- permits a stop before any external interaction;
- records only category-level decisions.

Relationship to Step 283:

- preserves the initial-release validation gate without silently authorizing
  execution.

Explicit authorization:

- required and independently recorded before action.

Static-check substitution risk:

- low; the later controlled observation remains required after preflight, and
  final static checks remain downstream.

Disposition:

```text
Preflight category confirmed
```

Option B is selected as the routing approach. The category above describes the
required result of the future preflight; Step 284 itself does not produce that
result.

### Option C: Defer Provider/runtime Execution and Proceed to Final-stage Planning

What it would clarify:

- package and static-check sequencing only.

What it would not clarify:

- the current provider/runtime category behavior of the implemented OAuth
  flow.

Privacy and containment implications:

- avoids external interaction but leaves the Step 283 validation gate open.

Relationship to Step 283:

- conflicts with the selected initial-release validation gate.

Explicit authorization:

- avoided rather than designed.

Static-check substitution risk:

- high, because package or Plugin Check evidence could be mistaken for the
  missing implemented-flow observation.

Disposition:

```text
Deferred / separate release gate
```

Option C is not selected.

### Option D: Replace Provider/runtime Validation With Source-only or Local-only Verification

What it would clarify:

- source or local category behavior already covered by Steps 205, 207, 208,
  and 282.

What it would not clarify:

- the selected provider/runtime category boundary for the implemented
  authorization-code flow.

Privacy and containment implications:

- avoids external interaction, but repeats matured or current source-level
  evidence.

Relationship to Step 283:

- does not satisfy the selected validation gate.

Explicit authorization:

- unnecessary because the selected external observation would not occur.

Static-check substitution risk:

- high, because repeated local evidence would replace the explicitly selected
  provider/runtime gate.

Disposition:

```text
Deferred / separate release gate
```

Option D is not selected.

## Preferred Next-step Decision

Selected future execution routing:

```text
Option B:
Require a separate preflight and explicit operator-authorization checkpoint
before any provider/runtime execution.
```

Reason:

- the selected initial-release validation gate involves external provider
  interaction;
- planning alone must not turn an external interaction into permission to
  execute;
- clean-state, controlled-environment, single-site, client-readiness,
  account-authorization, cleanup-choice, and stop-condition categories must be
  confirmed before browser OAuth;
- a separate checkpoint permits a no-action stop when any condition is absent;
- the preflight does not replace the later controlled observation;
- the later controlled observation does not expand into refresh, revoke, GA4
  Fetch, OpenAI Generate, or complete lifecycle behavior;
- final package and Plugin Check remain downstream and cannot replace the
  selected provider/runtime gate.

Step 284 planning conclusion:

```text
Deferred / separate release gate
```

The gate remains open until a future preflight and separately authorized
controlled observation are completed within their approved boundaries.

## Relationship to Final-stage Release Work

- Step 284 does not close the initial-release validation gate.
- Step 285 preflight will not close the gate because it performs no provider
  interaction.
- A later controlled verification, if separately authorized and recorded
  within the approved boundary, is upstream of final package build and
  inspection.
- Final install validation remains later.
- Final isolated Plugin Check remains later.
- Final public-wording consistency review remains later if the controlled
  observation identifies a mismatch.
- The final WordPress.org release decision remains later.
- Final package or Plugin Check cannot replace the selected controlled
  provider/runtime validation gate.
- Refresh and provider-side revoke remain future implementation gates even if
  the narrow authorization-code flow is later observed.

## Explicit Non-claims

Step 284 does not determine or prove:

- provider authorization;
- provider account identity;
- token validity;
- authorization-code exchange outcome;
- refresh outcome;
- revoke outcome;
- provider-side cleanup;
- complete OAuth lifecycle behavior;
- GA4 retrieval behavior;
- analytics-data correctness;
- OpenAI behavior;
- credential-storage certification;
- encryption at rest;
- legal or privacy-law compliance;
- WordPress.org policy compliance;
- final package correctness;
- final Plugin Check result;
- public-release approval.

Step 284 also does not assert that a future category observation will establish
any of those matters.

## Public Release Implication

```text
WordPress.org public release readiness remains Hold.
```

Step 284 designs a future controlled provider/runtime verification gate for
the implemented authorization-code OAuth flow only.

It does not authorize or execute provider interaction, browser OAuth,
authorization-code exchange, refresh, provider-side revoke, GA4 Fetch, OpenAI
Generate, local cleanup, final packaging, isolated Plugin Check, or public
release.

## Recommended Next Step

```text
Step 285 candidate —
Implemented authorization-code OAuth flow controlled provider/runtime
verification preflight and explicit operator-authorization checkpoint
```

Step 285 should remain docs-only / preflight-only. It should confirm:

- clean committed baseline;
- controlled single-site development environment;
- no unrelated changes or reused OAuth test state;
- category-level OAuth client readiness without values;
- explicit permission for one bounded provider interaction;
- explicit local-only cleanup choice;
- stop-condition acknowledgment.

Step 285 must not execute provider interaction, browser redirect, callback,
authorization-code exchange, token endpoint communication, Settings save,
local disconnect, GA4 Fetch, OpenAI Generate, refresh, revoke, Plugin Check, or
external HTTP.

## Result Classification

```text
Implemented authorization-code OAuth controlled provider/runtime
verification-gate planning:
Deferred / separate release gate

Selected future execution routing:
Option B

WordPress.org public release readiness:
Hold
```
