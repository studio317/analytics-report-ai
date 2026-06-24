# Step 283: OAuth Refresh, Provider-side Revoke, and Provider-runtime Initial Public-release Disposition Decision Checkpoint

## Step Objective and Decision Limits

Step 283 is a docs-only / decision-only checkpoint for the initial
public-release disposition of:

- OAuth refresh execution;
- provider-side revoke execution;
- complete OAuth provider/runtime lifecycle evidence;
- narrow provider/runtime validation of the currently implemented
  authorization-code OAuth flow.

The decision uses the current source-level verification from Step 282 and the
latest maturation records. It does not implement, execute, or validate OAuth
provider behavior.

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

Repository history confirmed that Step 282 was committed at the baseline.
There were no pre-existing source, public-documentation, maturation-document,
tool, or environment changes.

## Evidence Sources and Evidence-level Boundary

Primary decision input:

- `docs/maturation/step282-oauth-refresh-provider-revoke-and-provider-runtime-source-level-release-boundary-verification-results.md`.

Supporting records:

- `docs/maturation/step145-narrow-token-exchange-production-implementation-results.md`;
- `docs/maturation/step147-human-controlled-token-exchange-smoke-results.md`;
- `docs/maturation/step198-oauth-client-configuration-hybrid-source-final-maturation-checkpoint.md`;
- `docs/maturation/step205-oauth-token-lifecycle-source-level-verification-results.md`;
- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`;
- `docs/maturation/step215-uninstall-cleanup-maturation-checkpoint.md`;
- `docs/maturation/step223-manual-google-access-token-fallback-retirement-maturation-checkpoint.md`;
- `docs/maturation/step227-readme-privacy-wording-alignment-after-manual-token-retirement-maturation-checkpoint.md`;
- `docs/maturation/step280-wordpress-org-hold-gate-prioritization-checkpoint.md`;
- `docs/maturation/step281-oauth-lifecycle-remaining-hold-gate-release-boundary-planning-checkpoint.md`.

Step 282 established the current source-level facts used here:

- browser authorization redirect exists;
- callback-bound authorization-code token exchange exists;
- local token-storage handoff exists;
- GA4 credential resolution uses OAuth lifecycle categories;
- local-only disconnect exists;
- deterministic local option cleanup exists on uninstall;
- no refresh execution path was found;
- no provider-side revoke execution path was found;
- no refresh-before-GA4-Fetch path was found;
- current static wording describes refresh and provider-side revoke as
  deferred;
- local disconnect and uninstall remain separate from provider-side actions.

Step 147 provides historical, category-level human evidence for an
authorization-code exchange and storage outcome. It does not cover the current
release candidate, refresh, provider-side revoke, complete provider lifecycle,
or all failure paths.

Evidence levels remain separate:

- policy decisions define the intended initial-release boundary;
- source-level verification describes current control flow;
- historical controlled human evidence describes only its recorded scope;
- a future controlled provider/runtime gate must define its own evidence,
  preconditions, stop conditions, and cleanup boundary;
- final package and release validation remain later gates.

No credential, API key, OAuth token, option value, constant value,
Authorization header, request or response body, payload JSON, generated report
text, actual analytics value, screenshot, browser Network evidence, database
content, or provider response was inspected or recorded.

## Decision Questions

Step 283 answers four questions:

1. Is refresh execution an implementation requirement for the selected initial
   public-release boundary?
2. Is provider-side revoke execution an implementation requirement for the
   selected initial public-release boundary?
3. Is complete provider lifecycle verification, including refresh and revoke,
   an initial-release requirement?
4. Is narrow provider/runtime validation of the already implemented
   authorization-code flow required before the final release decision?

The answers must preserve these distinctions:

```text
authorization-code exchange
!= refresh execution

local-only disconnect
!= provider-side revoke

local uninstall cleanup
!= provider-side cleanup

narrow implemented-flow validation
!= complete provider lifecycle verification

initial-release validation gate
!= final release approval
```

## Current Policy and Source Boundary

### Refresh Execution

Current position:

- not implemented in the reviewed source;
- not represented as implemented behavior;
- distinct from lifecycle category calculation;
- distinct from reconnect-required guidance;
- not inferred from the authorization-code exchange path;
- represented in Settings, Report Builder, and public wording as deferred.

### Provider-side Revoke

Current position:

- not implemented in the reviewed source;
- not represented as implemented behavior;
- distinct from local-only disconnect;
- distinct from uninstall cleanup;
- not inferred from local option deletion;
- represented in Settings and public wording as deferred.

### Authorization-code OAuth Flow

Current position:

- browser authorization redirect, callback/state classification,
  authorization-code exchange, token-storage handoff, lifecycle category
  resolution, and GA4 credential-use entry boundaries exist;
- historical category-level human evidence exists for a selected exchange and
  storage outcome;
- source-level evidence does not establish current provider/runtime results;
- historical evidence does not replace a current, separately planned,
  initial-release validation gate.

### Current Bounded User Experience

The current bounded posture is:

- use the implemented authorization-code flow for the OAuth-based GA4 route;
- classify unusable or expired local lifecycle state;
- provide reconnect guidance rather than execute refresh;
- allow local-only token deletion;
- do not represent local deletion or uninstall as provider revoke;
- disclose refresh and provider-side revoke as deferred.

No inspected current record contradicts this boundary.

## Options Considered

### Option A: Require Refresh Execution Before Initial Public Release

Current evidence:

- no refresh execution path was found;
- refresh-needed state is classified and routed to reconnect guidance;
- public and static admin wording describe refresh as deferred.

What it would resolve:

- automatic renewal behavior for eligible expired credentials;
- some reconnect frequency and support burden.

What it would not resolve:

- provider-side revoke;
- complete provider lifecycle behavior;
- provider authorization across deployments;
- storage certification;
- final release validation.

Compatibility with current MVP scope:

- expands the current scope into a new provider request, storage update, error
  handling, retry, and external verification track.

Operational and support implications:

- introduces new token endpoint behavior, refresh failure categories, token
  replacement rules, retry policy, and credential-bearing QA.

Public wording effect:

- would require later wording and support-boundary updates after
  implementation and verification.

Release sequencing:

- would create release-affecting work before final package and Plugin Check.

Disposition:

```text
Explicitly deferred / future implementation gate
```

Option A is not selected as an immediate implementation requirement.

### Option B: Require Provider-side Revoke Before Initial Public Release

Current evidence:

- no provider-side revoke path was found;
- local disconnect and uninstall are explicitly local-only;
- public and static admin wording describe provider-side revoke as deferred.

What it would resolve:

- an in-plugin provider invalidation action if implemented and separately
  verified.

What it would not resolve:

- refresh behavior;
- every provider account cleanup state;
- complete lifecycle behavior;
- final release validation.

Compatibility with current MVP scope:

- expands local cleanup into a new provider communication and response-handling
  track.

Operational and support implications:

- introduces provider request outcomes, partial cleanup states, failure
  categories, user guidance, and credential-bearing QA.

Public wording effect:

- would require later wording changes to distinguish requested, completed, and
  failed provider actions.

Release sequencing:

- would create release-affecting work before final package and Plugin Check.

Disposition:

```text
Explicitly deferred / future implementation gate
```

Option B is not selected as an immediate implementation requirement.

### Option C: Require Refresh and Provider-side Revoke Before Initial Public Release

Current evidence:

- neither execution path exists;
- both are documented as deferred;
- the current UX uses reconnect guidance and local-only disconnect.

What it would resolve:

- a broader portion of the desired future lifecycle if fully implemented and
  verified.

What it would not resolve:

- every provider lifecycle state;
- provider authorization across deployments;
- storage certification;
- final package or release approval.

Compatibility with current MVP scope:

- creates the largest unreviewed lifecycle expansion among the considered
  options.

Operational and support implications:

- combines two provider-facing request systems, storage transitions, error
  taxonomies, and verification tracks.

Public wording effect:

- requires substantial later alignment after implementation evidence exists.

Release sequencing:

- delays all final-stage checks until both new tracks are implemented and
  verified.

Disposition:

```text
Explicitly deferred / future implementation gate
```

Option C is not selected.

### Option D: Defer Refresh and Revoke, With Complete Lifecycle as a Future Gate

Current evidence:

- aligns with current source and wording;
- preserves reconnect guidance and local-only disconnect.

What it would resolve:

- the policy status of unimplemented refresh and revoke;
- the separation of current scope from future complete-lifecycle work.

What it would not resolve:

- current provider/runtime behavior of the implemented authorization-code
  flow.

Compatibility with current MVP scope:

- high, because it preserves the current implementation boundary.

Operational and support implications:

- users may need reconnection;
- provider-side authorization removal remains outside the plugin;
- wording must keep those limitations explicit.

Public wording effect:

- current deferred and local-only wording remains applicable.

Release sequencing:

- permits moving toward final-stage work only after deciding whether the
  implemented flow needs current provider/runtime validation.

Disposition:

```text
Initial-release disposition selected
```

Option D forms part of the selected policy but does not by itself address the
implemented-flow validation gap.

### Option E: Defer Refresh and Revoke, and Require Narrow Implemented-flow Validation

Current evidence:

- refresh and revoke are absent and accurately described as deferred;
- an authorization-code OAuth flow is implemented;
- historical category-level provider evidence exists but predates later
  release-affecting source and wording changes;
- Step 282 confirms current source alignment but does not execute provider
  behavior.

What it would resolve:

- the initial-release policy status of refresh and revoke;
- the need for a current, bounded validation gate for the provider-facing flow
  the plugin actually exposes;
- the separation between current implemented-flow evidence and future complete
  lifecycle work.

What it would not resolve:

- refresh behavior;
- provider-side revoke;
- complete provider lifecycle behavior;
- provider-side cleanup;
- final package correctness;
- final release approval.

Compatibility with current MVP scope:

- high, because it validates the implemented route without adding unimplemented
  lifecycle features.

Operational and support implications:

- the future validation plan must define prerequisites, permitted
  status/category evidence, value non-disclosure, redaction, stop conditions,
  and cleanup/rollback;
- reconnection and local-only disconnect remain the bounded lifecycle UX.

Public wording effect:

- current refresh-deferred, revoke-deferred, reconnect, local-disconnect, and
  uninstall non-provider-action wording remains unchanged unless later
  evidence identifies a contradiction.

Release sequencing:

- the narrow validation gate is upstream of final package, final isolated
  Plugin Check, and final release validation;
- refresh/revoke future implementation remains outside the initial release
  sequence.

Disposition:

```text
Initial-release disposition selected
```

Option E is selected.

### Option F: Defer All Provider/runtime Validation

Current evidence:

- source and historical category-level evidence exist.

What it would resolve:

- no additional current provider/runtime evidence would be required.

What it would not resolve:

- whether the currently exposed authorization-code route still reaches its
  intended category boundaries in the controlled release environment;
- whether later release-affecting changes introduced an operational
  regression;
- current provider/runtime evidence for the actual OAuth-based GA4 entry path.

Compatibility with current MVP scope:

- avoids provider execution but leaves a core implemented external-service
  route dependent on source/docs and historical evidence only.

Operational and support implications:

- raises the chance that final package and release checks proceed with an
  unresolved implemented-flow evidence gap.

Public wording effect:

- wording could remain unchanged, but its operational basis would not receive
  a current narrow validation gate.

Release sequencing:

- moves prematurely toward final-stage checks.

Disposition:

```text
Deferred / separate provider-runtime gate
```

Option F is not selected for the initial-release boundary.

## Disposition Matrix

| Lifecycle / validation topic | Current implementation and wording position | Current evidence level | Initial public-release disposition | Future implementation or verification route | Non-claim / escalation boundary |
| --- | --- | --- | --- | --- | --- |
| Refresh execution | No execution path found; lifecycle classification and reconnect guidance exist; wording says deferred. | Source-level verification; Documentation / wording boundary | Explicitly deferred / future implementation gate | Separate future refresh policy, implementation, and provider/runtime verification track only if reopened. | Does not establish refresh outcome, token endpoint behavior, token validity, or provider authorization. |
| Provider-side revoke execution | No execution path found; local disconnect and uninstall are separate; wording says deferred. | Source-level verification; Documentation / wording boundary | Explicitly deferred / future implementation gate | Separate future revoke policy, implementation, and provider/runtime verification track only if reopened. | Does not establish provider token invalidation, account cleanup, or provider response behavior. |
| Local-only disconnect | Implemented as local dedicated OAuth token option deletion with category-level result. | Source-level verification; Controlled human admin smoke | Initial-release disposition selected | Preserve current boundary; reopen only after a relevant source or product-policy trigger. | Local deletion is not provider revoke or complete provider cleanup. |
| Uninstall/provider lifecycle separation | Guarded deterministic plugin-owned local option deletion; no provider action. | Source-level verification; Policy decision | Initial-release disposition selected | Preserve current separation; network and provider cleanup remain separate tracks. | Does not establish provider-side cleanup, network cleanup, or universal database cleanup. |
| Reconnect-required and deferred wording | Unusable lifecycle state routes to reconnect guidance; refresh/revoke remain described as deferred. | Source-level verification; Controlled human admin smoke; Documentation / wording verification | Initial-release disposition selected | Preserve wording unless later implementation or validation evidence requires a correction. | Does not imply refresh, revoke, complete lifecycle behavior, or release approval. |
| Authorization-code OAuth flow | Browser redirect, callback/state boundary, authorization-code exchange, token-storage handoff, and GA4 credential-use entry boundary exist. | Source-level verification; Historical controlled human evidence | Initial-release validation gate | Create a separate controlled provider/runtime verification-gate plan limited to the implemented flow. | Does not include refresh, revoke, complete provider lifecycle, provider cleanup, or token-value inspection. |
| Narrow implemented-flow provider/runtime validation | Not performed by Step 282; historical evidence is bounded and predates later release-affecting changes. | Deferred / separate provider-runtime gate | Initial-release validation gate | Plan and later explicitly authorize a controlled provider/runtime verification step. | Validation scope must remain category-level and cannot support broad provider/account/lifecycle claims. |
| Complete provider lifecycle verification | Refresh and revoke are not implemented; complete lifecycle evidence is absent. | Deferred / separate provider-runtime gate | Explicitly deferred / future implementation gate | Revisit only after a future product decision and corresponding implementation scope. | Not an initial-release requirement while unimplemented features remain accurately deferred. |
| Final package / isolated Plugin Check dependency | Historical package evidence exists but predates later release-affecting changes. | Final-stage release gate | Deferred / separate provider-runtime gate | Run only after the narrow implemented-flow validation disposition and any resulting work are closed. | Package and static checks do not replace provider/runtime evidence. |
| Final WordPress.org release decision | No current record authorizes submission. | Final-stage release gate | Deferred / separate provider-runtime gate | Separate final decision after upstream validation and final-stage checks. | Does not determine policy, legal, security, provider, or release approval in Step 283. |

## Preferred Decision and Rationale

Selected disposition:

```text
Option E:
Keep refresh and provider-side revoke explicitly deferred for the initial
public-release boundary, and require a narrow, separately planned
provider/runtime validation of the already implemented authorization-code
OAuth flow before the final release decision.
```

Controlled conclusion:

```text
Initial-release disposition selected
```

Detailed disposition:

```text
Refresh execution:
Explicitly deferred / future implementation gate

Provider-side revoke:
Explicitly deferred / future implementation gate

Complete OAuth provider/runtime lifecycle evidence:
Explicitly deferred / future implementation gate

Narrow provider/runtime validation of the implemented authorization-code flow:
Initial-release validation gate
```

Rationale:

- Step 282 found no current refresh or revoke execution path and no
  source/wording contradiction;
- implementing unrepresented future lifecycle features solely to broaden the
  initial release would expand request, storage, error, support, and
  verification scope;
- current reconnect guidance and local-only disconnect provide a bounded UX
  consistent with the documented deferral;
- local disconnect and uninstall remain distinct from provider-side action;
- the authorization-code flow is already implemented and is the core
  provider-facing path for the OAuth-based GA4 route;
- source review and historical category-level evidence do not establish the
  current provider/runtime behavior of that implemented flow;
- a narrow validation gate advances evidence for the implemented route without
  silently expanding into refresh, revoke, provider cleanup, or complete
  lifecycle claims;
- final package and isolated Plugin Check should follow the selected upstream
  validation gate, not substitute for it.

No finding requires:

```text
Needs policy escalation
```

## Matured Tracks Preserved

The following matured tracks remain closed unless a defined trigger reopens
them:

- OAuth client configuration hybrid-source maturity;
- credential source/readiness categories and value-hidden UI;
- lifecycle status/category UI;
- reconnect-required wording;
- refresh-deferred wording;
- provider-revoke-deferred wording;
- local-only disconnect;
- manual Google Access Token fallback retirement;
- deterministic plugin-owned option cleanup on uninstall;
- related public wording alignment.

Step 283 does not redesign or repeat these tracks. Their preservation does not
classify refresh, provider revoke, complete provider lifecycle, or final
release readiness as resolved.

## Initial-release Validation-gate Boundary

The required narrow gate is limited to the currently implemented
authorization-code OAuth flow:

- browser authorization redirect category;
- callback and state-validation category boundary;
- authorization-code exchange category;
- local token-storage handoff category;
- OAuth lifecycle/status return category;
- GA4 credential-use entry boundary.

The validation-gate plan must exclude:

- refresh execution;
- provider-side revoke execution;
- provider-side cleanup validation;
- complete provider lifecycle validation;
- broad account-level behavior;
- token, credential, option, or constant value inspection;
- request or response body recording;
- screenshots or browser Network evidence;
- final release approval.

The plan must define before execution:

- exact permissible status/category evidence;
- credential and token non-disclosure;
- redaction rules;
- environment and clean-state preconditions;
- explicit external-provider authorization requirement;
- stop conditions;
- rollback and local cleanup boundaries;
- behavior if the expected category boundary is not reached;
- relationship to final package and isolated Plugin Check.

Step 283 does not create or execute that provider/runtime verification.

## Deferred and Final-stage Dependencies

Future implementation gates:

- OAuth refresh execution;
- refresh response and token replacement policy;
- provider-side revoke execution;
- revoke response and partial-cleanup policy;
- complete provider lifecycle verification.

Initial-release validation gate:

- narrow controlled provider/runtime validation of the currently implemented
  authorization-code OAuth flow.

Final-stage gates after the narrow validation track is closed:

- final public wording consistency review if the validation identifies a
  mismatch;
- release-candidate package build and contents inspection;
- final install validation;
- final isolated Plugin Check;
- final WordPress.org release decision.

The final-stage gates do not convert refresh or revoke into initial-release
implementation requirements. They also do not replace the selected narrow
provider/runtime validation gate.

## Explicit Non-claims

Step 283 does not determine or prove:

- OAuth provider authorization;
- token validity;
- current authorization-code exchange outcome;
- refresh behavior or outcome;
- provider-side revoke behavior or outcome;
- token endpoint behavior;
- provider response behavior;
- provider-side cleanup;
- complete OAuth lifecycle behavior;
- credential storage certification;
- encryption at rest;
- legal or privacy-law compliance;
- WordPress.org policy compliance;
- multisite support;
- final package correctness;
- final Plugin Check result;
- public-release approval.

Step 283 does not determine that refresh or provider-side revoke can never be
implemented. It routes them to future implementation gates for the selected
initial-release boundary.

## Public Release Implication

```text
WordPress.org public release readiness remains Hold.
```

Step 283 selects the policy disposition for unimplemented OAuth refresh and
provider-side revoke, and routes the currently implemented authorization-code
OAuth flow to a separate narrow validation-gate plan.

It does not:

- authorize public release;
- execute or validate refresh or revoke;
- establish provider authorization or token validity;
- establish complete OAuth lifecycle behavior;
- determine legal, privacy-law, or WordPress.org policy compliance;
- certify credential storage;
- replace final package, isolated Plugin Check, or final release validation.

## Recommended Next Step

```text
Step 284 candidate —
Implemented authorization-code OAuth flow controlled provider/runtime
verification-gate planning checkpoint
```

Step 284 should remain docs-only / planning-only. It should design a future
controlled provider/runtime verification limited to the implemented
authorization-code OAuth flow, with no refresh, provider-side revoke, provider
cleanup, complete lifecycle assertion, or value inspection.

Step 284 must not execute OAuth, browser redirects, callbacks,
authorization-code exchange, token endpoint communication, GA4 Fetch, local
disconnect, Settings save, refresh, revoke, OpenAI Generate, Plugin Check, or
external HTTP.

## Result Classification

```text
OAuth initial public-release disposition:
Initial-release disposition selected

Refresh execution:
Explicitly deferred / future implementation gate

Provider-side revoke:
Explicitly deferred / future implementation gate

Narrow implemented authorization-code OAuth flow validation:
Initial-release validation gate

Complete provider lifecycle verification:
Explicitly deferred / future implementation gate

WordPress.org public release readiness:
Hold
```
