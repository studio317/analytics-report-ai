# Selected-Scope OAuth and Credential Public Wording and Controlled Validation Authorization Checkpoint

## 1. Scope and Explicit Exclusions

Step 295.20.7 is a docs-only and decision-only authorization checkpoint.

This checkpoint decides only whether the following future work categories may be considered in separate future steps:

- selected-scope public wording / disclosure / privacy / support / readme alignment work;
- conditional controlled-validation plan definition work, subject to wording-boundary completion and exact final-candidate/package conditions.

This checkpoint does not:

- modify wording, disclosure, privacy, support, readme, Settings, Report Builder, source code, candidate identity, package identity, or release artifacts;
- define, authorize, or execute controlled validation;
- execute OAuth or provider runtime behavior;
- re-evaluate Step 295.20 Held;
- make final release-decision authorization;
- make a final WordPress.org release decision;
- establish zero findings;
- resolve strict Plugin Check aggregate evidence.

## 2. Current Release Posture

The current release posture remains fixed:

| Area | Status |
|---|---|
| WordPress.org public release readiness | Hold |
| Final WordPress.org release-decision authorization | Held |
| Final WordPress.org release decision | Not performed |
| Strict Plugin Check aggregate evidence | Unavailable / unresolved |

This checkpoint does not relax, complete, or reclassify those statuses.

## 3. Selected Public-Release Scope Preserved

The selected scope remains unchanged:

| Area | Preserved selected scope |
|---|---|
| Refresh public-release scope | Explicit non-refresh / reconnect-required public-release scope |
| Provider-side revoke public-release scope | Explicit non-revoke public disposition |
| Local disconnect | Local-only disconnect boundary retained |
| OpenAI API key posture | Constant-first public configuration posture retained; Settings fallback remains developer-only / transitional |

The following non-claim boundary remains unchanged:

- automatic OAuth refresh is not a public-release capability;
- OAuth token expiry or refresh-unavailable state must not be represented as automatically recoverable;
- reconnect-required is the bounded user-recovery posture;
- local-only disconnect is not provider-side revoke;
- provider-side revoke is not a public-release capability;
- credential values remain hidden;
- Settings fallback is not primary public guidance;
- no new Settings-based primary OpenAI credential save path is introduced.

## 4. Baseline and Preservation Summary

The baseline and preservation gate passed at a safe category level.

| Gate item | Classification | Safe rationale |
|---|---|---|
| Baseline classification | Clean committed Step 295.20.6 preparation baseline | The predecessor preparation record is present and the current checkpoint starts from that baseline. |
| Step 295.20 Held continuity | Preserved | Final release-decision authorization remains Held. |
| Step 295.20.1 prerequisite disposition | Preserved | Remediation and later validation required remains the active prerequisite disposition. |
| Step 295.20.2 sequencing-plan identity | Preserved | Sequencing-plan identity remains preserved. |
| Step 295.20.3 selected-scope identity | Preserved | Selected public-release scope remains unchanged. |
| Step 295.20.5 review-result identity | Preserved | Scope decision classification confirmed remains preserved. |
| Step 295.20.6 preparation-plan identity | Preserved | Preparation plan completed; alignment and validation authorization pending remains preserved. |
| Selected-scope and non-claim identity | Preserved | Non-refresh, reconnect-required, non-revoke, local-only disconnect, value-hidden, and fallback boundaries remain unchanged. |
| Public wording / disclosure preparation classes | Preserved | The prepared future alignment classes remain identifiable. |
| Controlled-validation preparation classes | Preserved | The prepared future validation classes remain identifiable. |
| OpenAI posture continuity | Preserved | Constant-first public posture and developer-only / transitional fallback posture remain unchanged. |
| Candidate/package continuity | Preserved according to predecessor records | No candidate or package change is introduced at this docs-only authorization boundary. |
| Release-affecting delta | None introduced | This checkpoint adds no release-affecting implementation, wording, package, policy, or artifact change. |

## 5. Permanent Non-Evidence and Category-Separation Rules

Plugin Check command success, silence, human-readable success output, and private implementation behavior do not prove zero findings.

Alternative final release evidence is not strict Plugin Check evidence.

Strict Plugin Check aggregate evidence remains Unavailable / unresolved.

Selected-scope wording / disclosure alignment authorization and conditional controlled-validation-plan definition authorization are not:

- strict Plugin Check evidence;
- a zero-findings conclusion;
- provider-security certification;
- OAuth / credential final release readiness;
- controlled validation authorization;
- controlled validation execution;
- final release-decision authorization;
- a final WordPress.org release decision;
- WordPress.org public release approval;
- a prediction of WordPress.org acceptance.

## 6. Authorization Criteria Assessment

| Criterion | Status | Safe category-level rationale |
|---|---|---|
| A. Governance-chain and selected-scope continuity | Satisfied | Required predecessor chain, Held state, selected scope, non-claim boundary, and work-class identity remain preserved. |
| B. Wording / disclosure authorization boundary | Satisfied | Future wording / disclosure work can remain bounded to selected-scope non-claims and public artifact consistency without implementation change or prohibited evidence. |
| C. Non-refresh and reconnect-required claim integrity | Satisfied | Future wording can avoid automatic refresh, automatic expired-token recovery, and provider-runtime correctness claims while preserving reconnect-required posture. |
| D. Local-only disconnect and non-revoke claim integrity | Satisfied | Future wording can preserve local-only disconnect as distinct from provider-side revoke and avoid provider-side cleanup claims. |
| E. Credential source, visibility, and OpenAI posture integrity | Satisfied | Future wording can preserve constant-first public configuration, developer-only / transitional fallback posture, and value-hidden credential boundaries. |
| F. Privacy, support, readme, and distribution-artifact alignment boundary | Satisfied | Future alignment can remain category-level and does not require raw/private evidence or unsupported claims. |
| G. Controlled-validation plan-definition readiness boundary | Satisfied | Future plan definition can remain limited to selected-scope validation categories and require exact final-candidate/package identity before execution. |
| H. Sequencing and conditionality integrity | Satisfied | Wording / disclosure alignment and wording-boundary review precede validation-plan definition when release-affecting wording changes occur. |
| I. Candidate/package invalidation and re-baselining boundary | Satisfied | Future release-affecting changes invalidate affected candidate-specific evidence and require later scoped re-baselining. |
| J. Relationship to other Step 295.20 prerequisites | Satisfied | This authorization does not satisfy multisite, uninstall, data-handling, final-scope functional, safe error-path, package, artifact, or strict Plugin Check prerequisites. |
| K. Fail-closed, authority, and scope-isolation boundary | Satisfied | Ambiguity, unavailable mandatory conditions, prohibited evidence requirements, or category conflation would stop the process; no validation execution or final release-decision work is authorized here. |

A-K status summary: all required authorization criteria are Satisfied.

## 7. Authorization Outcome and Exact Meaning

Authorization outcome:

Selected-scope wording alignment and conditional validation-plan definition authorized.

Exact meaning:

- A future bounded selected-scope wording / disclosure alignment authorization-and-implementation Step may be considered.
- A future controlled-validation plan-definition Step may be considered only after selected-scope wording / disclosure alignment, required wording-boundary review, and the stated sequencing conditions are complete.
- Neither authorization object permits validation execution, provider execution, OAuth refresh, provider-side revoke, final release-decision authorization re-evaluation, or a final WordPress.org release decision.

## 8. Authorized Future Work Classes and Conditions

### Authorization Object W: Bounded Selected-Scope Public Wording / Disclosure Alignment Work

| Field | Boundary |
|---|---|
| Status | Authorized for future separate-step consideration |
| Allowed scope | Non-refresh / reconnect-required wording, local-only disconnect wording, explicit non-revoke wording and disclosure, OAuth / credential privacy and external-data-transmission disclosure, support/debug redaction wording, readme and distribution-facing consistency, and affected OpenAI posture wording recheck. |
| Excluded scope | Automatic refresh claims, automatic expired-token recovery claims, provider-runtime correctness claims, provider-side revoke claims, security certification claims, credential value disclosure, Settings fallback as primary public guidance, and OAuth or credential implementation behavior changes. |
| Mandatory predecessor conditions | Selected scope and non-claim boundary remain preserved; wording work remains bounded to public artifact consistency; prohibited evidence is not required. |
| Safe completion condition | A future separate step completes bounded wording / disclosure alignment while preserving selected scope, non-claim boundaries, safe evidence rules, and category separation. |
| Fail-closed stop condition | Any wording change implies unsupported capability, requires prohibited evidence, changes selected scope, or creates unresolved contradiction. |
| Candidate/package invalidation consequence | Any wording / disclosure change is release-affecting and invalidates affected candidate/package evidence. |

### Authorization Object V: Conditional Controlled-Validation Plan Definition Work

| Field | Boundary |
|---|---|
| Status | Conditionally authorized for future separate-step consideration after sequence conditions are met |
| Allowed scope | Plan definition for selected-scope validation categories only, including safe observable categories, prohibited evidence boundaries, role separation, cleanup, non-persistence, invalidation, and fail-closed classification. |
| Excluded scope | Validation execution, browser interaction, provider execution, OAuth refresh execution, provider-side revoke execution, final OAuth / credential readiness conclusion, and final release-decision authorization re-evaluation. |
| Mandatory predecessor conditions | Bounded wording / disclosure alignment is committed if authorized and performed; wording-boundary review is complete; no selected-scope wording contradiction remains; validation categories remain identifiable without prohibited evidence or unsupported inference; exact final candidate/package remains required before validation execution. |
| Safe completion condition | A future separate plan-definition step defines the validation boundary without authorizing or executing validation and without expanding scope. |
| Fail-closed stop condition | Wording alignment is not authorized, not completed, changes selected scope, creates unresolved contradiction, or requires prohibited evidence. |
| Candidate/package invalidation consequence | Any affected release evidence must be re-baselined against the exact final candidate/package after release-affecting changes are complete. |

## 9. Authorization Invalidation

This authorization must not be silently reused after any affected invalidation trigger.

Invalidation triggers include:

- candidate source identity change;
- release package identity, metadata, contents, or build-procedure change;
- OAuth scope, reconnect, disconnect, revoke, storage, or credential-source boundary change;
- refresh or provider-side revoke capability being added or publicly claimed;
- OpenAI source, fallback, clear, visibility, or storage posture change;
- privacy, disclosure, support, readme, Settings, Report Builder, or public wording change;
- selected public-release scope or non-claim boundary change;
- validation-class or safe-evidence-boundary change;
- functional-flow or safe error-path boundary change;
- toolchain or evidence-interface category change;
- role separation loss;
- required safe evidence category becoming unavailable;
- prohibited evidence becoming required;
- strict Plugin Check and alternative evidence becoming conflated;
- newly eligible public supported Plugin Check contract becoming available.

After invalidation, the process must return to the earliest affected wording, validation, packaging, or governance checkpoint.

## 10. Persistent Limitation and Release State

| Area | Status |
|---|---|
| Strict Plugin Check aggregate evidence | Unavailable / unresolved |
| WordPress.org public release readiness | Hold |
| Final WordPress.org release-decision authorization | Held |
| Final WordPress.org release decision | Not performed |

## 11. Next-Step Boundary

At the end of this checkpoint, none of the following have started:

- public wording or disclosure modification;
- controlled-validation plan definition;
- controlled validation authorization or execution;
- OAuth implementation;
- provider runtime execution;
- browser interaction;
- final release-decision authorization re-evaluation;
- final WordPress.org release decision;
- Plugin Check rerun;
- Plugin Check tool/version change;
- candidate or package modification;
- package rebuild;
- Step 295.20.8;
- Step 295.21;
- Step 296.

## 12. Recommended Next Checkpoint

Because the authorization outcome is selected-scope wording alignment and conditional validation-plan definition authorized, the recommended next checkpoint is:

Step 295.20.8: Selected-Scope OAuth and Credential Public Wording and Disclosure Alignment Authorization and Bounded Implementation Plan.

Step 295.20.8 should remain docs-only / authorization-and-bounded-implementation-planning-only. It should preserve the selected scope and non-claim boundary, then define only the later narrow wording / disclosure alignment implementation target surfaces, allowed change categories, excluded claims, review boundary, and candidate/package invalidation boundary.

Step 295.20.8 must not perform actual wording modification, controlled-validation plan definition, controlled validation execution, OAuth implementation, provider execution, final release-decision authorization re-evaluation, final WordPress.org release decision, Plugin Check rerun, zero-findings conclusion, or public release approval.
