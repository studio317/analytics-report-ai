# OAuth Lifecycle and Credential Public-Release Remediation Sequencing Plan

## 1. Scope and Explicit Exclusions

Step 295.20.2 is a docs-only / planning-only checkpoint.

This plan responds to the OAuth and credential prerequisite disposition:

```text
Remediation and later validation required
```

The plan defines dependency order, scope-decision branches, implementation /
validation boundaries, public wording boundaries, and candidate/package
re-baselining boundaries for future separate steps.

This Step does not:

- select a public-release scope;
- implement refresh;
- implement provider-side revoke;
- execute OAuth or provider runtime;
- modify credential source or storage behavior;
- modify OpenAI fallback behavior;
- perform browser interaction or controlled validation;
- re-evaluate final release-decision authorization;
- make a final WordPress.org release decision;
- approve public release.

Current release posture:

```text
WordPress.org public release readiness:
Hold

Final WordPress.org release-decision authorization:
Held

Final WordPress.org release decision:
Not performed

Strict Plugin Check aggregate evidence:
Unavailable / unresolved
```

OAuth lifecycle and credential remediation sequencing is not:

- a strict Plugin Check finding conclusion;
- a zero-findings conclusion;
- a provider-security certification;
- a final release-decision authorization;
- a WordPress.org public release approval;
- a prediction of WordPress.org acceptance.

## 2. Baseline and Preservation Gate

Baseline classification:

```text
Clean committed Step 295.20.1 OAuth and credential prerequisite-disposition
baseline
```

Baseline and preservation gate status:

| Gate category | Status | Safe category-level note |
|---|---|---|
| Step 295.20 Held identity | Preserved | Final release-decision authorization remains Held. |
| Step 295.20.1 disposition identity | Preserved | Remediation and later validation required remains the active prerequisite disposition. |
| Required predecessor governance chain | Preserved | Required predecessor records remain present at the category level. |
| OAuth lifecycle boundaries | Preserved | Reconnect, refresh-deferred, revoke-deferred, and local-only disconnect boundaries remain preserved. |
| OpenAI key-source and fallback posture | Preserved | Constant-first and developer-only / transitional fallback posture records remain preserved. |
| Credential redaction and value-hidden boundary | Preserved | Credential values remain outside release-governance evidence. |
| Alternative evidence governance chain | Preserved | Alternative evidence governance and sufficiency-review records remain preserved. |
| Candidate and package identity | Preserved | Candidate and package identity remain preserved according to predecessor records. |
| Release-affecting delta | None introduced | This planning-only record introduces no release-affecting candidate/package change. |

## 3. Planning Principles

1. A public OAuth lifecycle claim must not exceed implemented and final-scope
   validated behavior.
2. Refresh, reconnect, local disconnect, and provider-side revoke are separate
   capabilities and must not be silently conflated.
3. Local-only disconnect must never be described as provider-side revoke.
4. A non-refresh public scope is not assumed safe merely because
   reconnect-required wording exists; it requires an explicit scope decision,
   wording/disclosure alignment, and applicable final-scope validation.
5. A refresh-capable public scope requires separate implementation,
   controlled validation, public wording, and exact-candidate evidence gates.
6. Provider-side revoke requires either bounded implementation and controlled
   validation, or an explicit non-revoke public disposition with wording that
   preserves the distinction from local-only disconnect.
7. OpenAI constant-first / developer-only transitional fallback posture remains
   unchanged unless a separately authorized future change modifies it.
8. Any release-affecting source, package, metadata, privacy, disclosure,
   public wording, scope, credential, OAuth, or functional-flow change
   invalidates affected candidate/package evidence and must not silently reuse
   the preserved candidate/package governance chain.
9. OAuth / credential remediation alone cannot resolve the other Step 295.20
   prerequisite categories, including multisite / uninstall / data handling
   and final-scope functional / safe error-path readiness.

## 4. Public-Release Scope Branches

This plan defines the following branches without selecting one.

### Branch A: Refresh-Capable OAuth Public-Release Scope

Branch A may be considered only through future separate authorization and
implementation stages.

Required order:

1. Scope decision: refresh is an intended public capability.
2. Narrow implementation authorization: define only approved refresh lifecycle
   behavior and safe status boundary.
3. Narrow implementation: add only the authorized refresh behavior.
4. Source-level / boundary review: confirm refresh, reconnect, failure,
   persistence, disclosure, and non-claim boundaries at category level.
5. Controlled validation planning and authorization: define permitted
   validation method, redaction boundary, human role boundary, and success /
   fail-closed conditions.
6. Controlled final-scope validation: validate applicable refresh,
   expired-token, reconnect, and user-facing failure categories without
   recording prohibited material.
7. Public wording / privacy / support alignment: update and validate public
   claims only after actual capability and validation boundary are established.
8. Exact final-candidate freeze and package evidence: create a new
   candidate-specific evidence chain after all release-affecting changes are
   complete.
9. OAuth / credential readiness re-evaluation: perform only after all above
   gates are complete.

Branch A does not automatically require provider-side revoke implementation.
Provider-side revoke remains a separate disposition branch.

### Branch B: Explicit Non-Refresh / Reconnect-Required Public-Release Scope

Branch B may be considered only through a future explicit public-release scope
decision.

Required order:

1. Scope decision: automatic refresh is not a public-release capability.
2. Exact non-claim definition: token expiry, refresh-unavailable state,
   reconnect-required state, and user recovery path are explicitly bounded.
3. Local disconnect / provider-revoke distinction: local-only disconnect
   remains explicit and provider-side revoke is either separately implemented
   later or explicitly not claimed.
4. Public wording / privacy / support alignment: all user-facing wording avoids
   implying automatic refresh, provider-side revocation, or unsupported
   lifecycle recovery.
5. Controlled final-scope validation planning and authorization: validate the
   applicable non-refresh, expired-token, reconnect-required,
   local-disconnect, and safe-error categories.
6. Controlled final-scope validation: validate only the selected public scope
   and stated non-claim boundary.
7. Exact final-candidate freeze and package evidence: create a new
   candidate-specific evidence chain after all release-affecting wording,
   scope, or implementation changes are complete.
8. OAuth / credential readiness re-evaluation: perform only after all above
   gates are complete.

Branch B is not a claim that the scope is acceptable for final release. Its
suitability remains subject to later final release prerequisite and
authorization checkpoints.

### Provider-Side Revoke Disposition

Provider-side revoke is a separate branch with two future options. This plan
does not select either option.

| Option | Future disposition | Required boundary |
|---|---|---|
| R1 | Provider-side revoke is intended as a public capability. | Separate authorization, bounded implementation, controlled validation, and public wording alignment. |
| R2 | Provider-side revoke is not a public capability. | Explicit non-revoke public disposition, local-only disconnect wording, privacy/support disclosure alignment, and applicable final-scope validation. |

Neither option may silently treat local-only disconnect as provider-side revoke.

## 5. Mandatory Sequencing Model

Each phase below is a later separate checkpoint. This Step starts none of them.

| Phase | Purpose | Boundary |
|---|---|---|
| Phase 0 | Preserve clean committed baseline, identities, and safe evidence boundaries. | No release-affecting work. |
| Phase 1 | OAuth and credential public-release scope decision. | Choose neither branch in this Step; define decision inputs, allowed outcomes, non-claim requirements, and fail-closed conditions. |
| Phase 2 | Conditional remediation authorization. | Authorize only the minimum future work required by the selected scope branch. |
| Phase 3 | Conditional implementation or public-scope / wording disposition. | Perform only after separate authorization; do not mix implementation with validation. |
| Phase 4 | Controlled validation planning and authorization. | Define exact final-scope validation classes, human role boundary, redaction boundary, and fail-closed conditions. |
| Phase 5 | Controlled final-scope validation. | Perform only after separate authorization and only for the selected scope. |
| Phase 6 | Privacy, support, disclosure, readme, and public wording consistency recheck. | Complete after selected capability / non-capability boundaries are fixed. |
| Phase 7 | Exact final-candidate freeze, package build, package contents inspection, isolated package-install validation, and distribution-artifact consistency. | Use the exact final candidate/package after all release-affecting changes are complete. |
| Phase 8 | OAuth and credential prerequisite re-evaluation. | Reconsider Step 295.20 final release-decision authorization only if all other mandatory release prerequisites are also current. |

## 6. Dependency Table

| Item | Dependency / predecessor category | Future work class | Branch applicability | Earliest start | Fail-closed stop condition |
|---|---|---|---|---|---|
| OAuth refresh lifecycle | Step 295.20.1 refresh disposition | Scope decision / implementation / controlled validation | Conditional | Phase 1 | Refresh scope cannot be safely selected or validated. |
| Reconnect and expired-token recovery | Existing reconnect-required and lifecycle category boundaries | Scope decision / controlled validation | Common | Phase 1 | Recovery state is ambiguous or unsupported by selected scope. |
| Local disconnect | Existing local-only disconnect boundary | Wording alignment / controlled validation | Common | Phase 1 | Local cleanup is conflated with provider-side revoke. |
| Provider-side revoke | Revoke-deferred boundary | Scope decision / implementation or non-claim disposition / controlled validation | Conditional | Phase 1 | Revoke claim lacks implementation/validation or explicit non-claim wording. |
| OpenAI key source and transitional fallback posture | Constant-first and developer-only / transitional fallback records | Wording alignment / re-evaluation | Common | Phase 1 | Selected scope or wording contradicts preserved OpenAI posture. |
| Credential visibility and value-hidden boundary | Redaction and value-hidden predecessor records | Controlled validation / wording alignment | Common | Phase 1 | Any future method requires value inspection or disclosure. |
| Public wording and non-claim alignment | Current wording boundary | Wording alignment | Common | Phase 3 after scope branch | Wording implies unsupported refresh, revoke, or lifecycle behavior. |
| Privacy and external-data-transmission disclosure | Privacy and external service wording records | Wording alignment | Common | Phase 3 after scope branch | Disclosure contradicts selected scope or non-claim boundary. |
| Support/debug redaction wording | Support/debug redaction boundary | Wording alignment | Common | Phase 3 after scope branch | Support evidence would require prohibited material. |
| Controlled final-scope validation | Selected branch and validation plan | Controlled validation | Common | Phase 4 | Required validation class is unavailable or ambiguous. |
| Final candidate freeze | Completed release-affecting work | Candidate-package re-baselining | Common | Phase 7 | Any release-affecting change remains pending. |
| Release package / isolated-install evidence | Frozen final candidate | Candidate-package re-baselining | Common | Phase 7 | Package or install evidence is stale, incomplete, or out of scope. |
| OAuth / credential prerequisite re-evaluation | Completed branch, wording, validation, and package evidence | Re-evaluation | Common | Phase 8 | Any selected prerequisite remains incomplete or unsupported. |
| Step 295.20 final authorization re-evaluation | OAuth / credential re-evaluation and all other release prerequisites | Re-evaluation | Common | Phase 8 | Any mandatory release prerequisite remains unavailable, incomplete, or ambiguous. |

## 7. Future Final-Scope Validation Categories

This Step performs no validation. Future controlled validation categories must
be selected only after the public-release scope decision.

| Validation category | Applies when | Current plan status |
|---|---|---|
| Refresh success / failure category | Refresh is public scope | Future conditional validation class |
| Expired-token and reconnect-required category | All branches | Future common validation class |
| Local disconnect category | All branches | Future common validation class |
| Provider-side revoke category | Revoke is public scope | Future conditional validation class |
| Explicit non-revoke public wording category | Revoke is not public scope | Future conditional validation class |
| OpenAI constant-first readiness category | All branches | Future common validation class |
| Transitional fallback non-primary-public-guidance category | All branches | Future common validation class |
| Credential value-hidden category | All branches | Future common validation class |
| Credential and OAuth safe error / recovery wording category | All branches | Future common validation class |
| Selected public scope user-flow category | Selected branch only | Future selected-scope validation class |

For every future validation category:

- raw credentials, tokens, API keys, request/response data, screenshots,
  browser Network evidence, analytics data, and generated report text are
  prohibited;
- validation must be defined for the exact final candidate/package scope;
- an unavailable or ambiguous required validation category prevents OAuth /
  credential final-release readiness from being claimed;
- passing a validation category does not establish zero findings, strict Plugin
  Check evidence, WordPress.org approval, or WordPress.org acceptance.

## 8. OpenAI Credential Posture Boundary

The current OpenAI credential posture remains preserved:

- constant-first configuration remains the intended public configuration
  posture;
- Settings fallback remains developer-only / transitional;
- normal Settings UI does not create or replace fallback credentials;
- permitted clear behavior remains bounded;
- credential values remain hidden.

No OpenAI implementation change is authorized by this Step.

Future public wording / disclosure alignment may require recheck only if OAuth /
credential scope, wording, privacy, support, or release artifacts change.

## 9. Candidate, Package, and Governance Invalidation Boundary

The following trigger invalidation of affected candidate-specific evidence and
require a later scoped re-baselining decision:

- candidate source identity change;
- release package identity, metadata, contents, or build-procedure change;
- OAuth lifecycle, reconnect, disconnect, revoke, storage, or credential source
  change;
- OpenAI source / fallback posture change;
- privacy, disclosure, support, readme, or public wording change;
- public-release scope or non-claim boundary change;
- functional-flow or safe error-path boundary change;
- toolchain or evidence-interface category change;
- role separation loss;
- required safe evidence category becoming unavailable;
- prohibited evidence becoming required;
- strict Plugin Check and alternative evidence becoming conflated;
- a newly eligible public supported Plugin Check contract becoming available.

After such a change, the preserved candidate/package governance chain must not
be silently reused. Future work returns to the earliest affected evidence,
validation, packaging, or governance checkpoint.

## 10. Relationship to Other Step 295.20 Prerequisites

Step 295.20 remains:

```text
Held
```

This sequencing plan does not satisfy or reclassify:

- multisite, uninstall, and data-handling readiness;
- final-scope functional and safe error-path readiness;
- any later candidate/package or distribution-artifact readiness requirement.

OAuth / credential remediation may be necessary but is not sufficient for final
release-decision authorization.

## 11. Next-Step Boundary

This Step did not start:

- OAuth public-release scope decision;
- OAuth refresh implementation;
- provider-side revoke implementation;
- provider runtime execution;
- browser interaction;
- controlled validation;
- final release-decision authorization re-evaluation;
- final WordPress.org release decision;
- Plugin Check rerun or tool/version change;
- candidate or package modification;
- package rebuild;
- Step 295.20.3;
- Step 295.21;
- Step 296.

Recommended next checkpoint:

```text
Step 295.20.3:
OAuth Lifecycle and Credential Public-Release Scope Decision Checkpoint
```

Step 295.20.3 must be docs-only / decision-only. It must select, or hold
without selecting, the public-release scope for refresh and provider-side
revoke. It must not implement, execute, validate, approve public release,
re-evaluate Step 295.20, resolve strict Plugin Check evidence, or conclude zero
findings.

## 12. Result Classification

Step 295.20.2 result classification:

```text
OAuth and credential public-release remediation sequencing plan prepared;
public-release scope decision pending
```

This result is not:

- OAuth / credential final release readiness;
- final release-decision authorization;
- final WordPress.org release decision;
- WordPress.org public release approval;
- strict Plugin Check limitation resolution;
- zero findings conclusion;
- WordPress.org acceptance prediction.

WordPress.org public release readiness remains:

```text
Hold
```
