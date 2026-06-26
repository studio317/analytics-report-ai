# Selected-Scope OAuth and Credential Public Wording and Controlled Validation Preparation Checkpoint

## 1. Step Purpose

Step 295.20.6 is a docs-only and planning-only checkpoint for the selected OAuth and credential public-release scope.

The purpose is to prepare future dependency order, scope boundary, safe evidence boundary, and exact final-candidate boundary for:

- public wording and disclosure alignment;
- privacy, support, and readme consistency alignment;
- controlled final-scope validation planning;
- controlled validation authorization preparation;
- exact final-candidate and package re-baselining preparation.

This checkpoint does not modify public wording, privacy wording, support wording, user-facing admin wording, readme guidance, release artifacts, source code, package contents, candidate identity, OAuth runtime behavior, or credential behavior.

This checkpoint does not authorize or execute controlled validation.

## 2. Scope

This checkpoint preserves the selected scope confirmed by the independent human review in Step 295.20.5.

The selected scope remains:

| Area | Preserved selected scope |
|---|---|
| Refresh public-release scope | Explicit non-refresh / reconnect-required public-release scope |
| Provider-side revoke public-release scope | Explicit non-revoke public disposition |
| Local disconnect | Local-only disconnect boundary retained |
| OpenAI API key posture | Constant-first public configuration posture retained; Settings fallback remains developer-only / transitional |

The following non-claim boundary is preserved:

- automatic OAuth refresh is not a public-release capability;
- OAuth token expiry or refresh-unavailable state must not be represented as automatically recoverable;
- reconnect-required is the bounded user-recovery posture;
- local-only disconnect is not provider-side revoke;
- provider-side revoke is not a public-release capability;
- credential values remain hidden;
- Settings fallback is not primary public guidance;
- no new Settings-based primary OpenAI credential save path is introduced.

## 3. Explicit Non-Goals

This checkpoint does not:

- complete public wording alignment;
- complete privacy or disclosure alignment;
- authorize controlled validation;
- execute controlled validation;
- implement OAuth refresh;
- execute OAuth reconnect or provider runtime;
- implement or execute provider-side revoke;
- modify credential source, storage, visibility, fallback, or clear behavior;
- modify Settings, option, or constant configuration;
- modify candidate or package identity;
- rebuild a package;
- rerun, inspect, parse, replace, install, remove, downgrade, or update Plugin Check;
- re-evaluate final release-decision authorization;
- make a final WordPress.org release decision;
- approve public release.

## 4. Baseline and Preservation Gate

The baseline and preservation gate is classified at a safe category level.

| Gate item | Classification | Notes |
|---|---|---|
| Clean committed predecessor baseline | Preserved | Step 295.20.5 independent human review result is treated as the committed predecessor state. |
| Step 295.20 Held identity | Preserved | Final release-decision authorization remains Held. |
| Step 295.20.1 prerequisite disposition | Preserved | Remediation and later validation required remains the active prerequisite disposition. |
| Step 295.20.2 sequencing-plan identity | Preserved | Sequencing-plan identity remains preserved. |
| Step 295.20.3 selected-scope identity | Preserved | Selected scope remains unchanged. |
| Step 295.20.5 review-result identity | Preserved | Scope decision classification confirmed remains the latest review result. |
| Non-refresh / reconnect-required scope identity | Preserved | No automatic refresh claim is introduced. |
| Explicit non-revoke disposition identity | Preserved | No provider-side revoke claim is introduced. |
| Local-only disconnect boundary | Preserved | Local disconnect remains distinct from provider-side revoke. |
| OpenAI constant-first posture | Preserved | Constant-first posture remains the intended public posture. |
| Developer-only / transitional fallback posture | Preserved | Settings fallback remains developer-only / transitional. |
| Required future work class identity | Preserved | Future wording, validation, and re-baselining classes remain pending. |
| Candidate and package continuity | Preserved according to predecessor records | No candidate or package change is introduced at this planning-only boundary. |
| Release-affecting delta | None introduced | This checkpoint adds no release-affecting implementation, wording, package, or policy change. |

Baseline blocker classification: No baseline blocker identified at this safe category-level gate.

## 5. Current Release Posture

The current release posture remains:

| Area | Status |
|---|---|
| WordPress.org public release readiness | Hold |
| Final WordPress.org release-decision authorization | Held |
| Final WordPress.org release decision | Not performed |
| Strict Plugin Check aggregate evidence | Unavailable / unresolved |

This checkpoint does not relax, complete, or reclassify those statuses.

## 6. Permanent Non-Evidence and Category-Separation Rules

Plugin Check command success, silence, human-readable success output, and private implementation behavior do not prove zero findings.

Alternative final release evidence is not strict Plugin Check evidence.

Strict Plugin Check aggregate evidence remains Unavailable / unresolved.

Selected-scope OAuth and credential public wording and controlled validation preparation is not:

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

## 7. Required Planning Principles

The following principles govern future work:

1. Public wording must not claim automatic refresh, automatic expired-token recovery, provider-side revoke, provider-runtime correctness, or security certification.
2. Public wording must preserve the distinction between refresh-unavailable / token-expiry condition, reconnect-required recovery posture, local-only disconnect, and provider-side revoke not provided in the selected scope.
3. Privacy, support, readme, and user-facing wording must describe only the selected public scope and must not rely on credential values, raw provider information, or unsupported runtime inference.
4. Constant-first OpenAI configuration remains the intended public posture. Settings fallback remains developer-only / transitional and must not be presented as primary public setup guidance.
5. Credential visibility remains value-hidden. Public wording and validation methods must not require credential, token, option, constant, or password values to be revealed.
6. Controlled validation must be defined for the exact final candidate/package scope only after all release-affecting wording, implementation, metadata, privacy, support, readme, or artifact changes are complete.
7. Historical, partial, pre-final-candidate, or planning-only evidence cannot be treated as final controlled-validation evidence.
8. Any release-affecting change invalidates affected candidate/package evidence and requires return to the earliest affected wording, validation, package, or governance checkpoint.
9. Completion of public wording / controlled validation preparation alone does not resolve Step 295.20 Held or other release prerequisites.

## 8. Public Wording and Disclosure Alignment Classes

This checkpoint defines future alignment categories only. It does not draft or change final user-facing copy.

| Class | Future work class | Dependency category | Earliest authorization point | Safe completion condition | Fail-closed stop condition |
|---|---|---|---|---|---|
| OAuth capability and recovery wording | Wording alignment | Selected non-refresh / reconnect-required scope | Separate wording-alignment authorization checkpoint | Public wording preserves non-refresh and reconnect-required boundaries without automatic recovery claims | Any wording implies automatic refresh, automatic expiry recovery, or provider-runtime correctness |
| Disconnect and revoke wording | Wording alignment / disclosure alignment | Local-only disconnect and explicit non-revoke disposition | Separate wording-alignment authorization checkpoint | Public wording distinguishes local-only disconnect from provider-side revoke | Any wording implies provider-side revocation or provider-side cleanup |
| Credential source and visibility wording | Wording alignment | OpenAI constant-first posture, developer-only / transitional Settings fallback, value-hidden credential boundary | Separate wording-alignment authorization checkpoint | Wording preserves constant-first public posture and value-hidden boundary | Any wording presents Settings fallback as primary public setup or requires value disclosure |
| Privacy and external-data-transmission disclosure | Disclosure alignment | Selected OAuth / credential public scope and existing data-handling boundaries | Separate disclosure-alignment authorization checkpoint | Disclosure aligns with selected public scope and does not introduce unsupported claims | Any disclosure relies on raw/private evidence or unsupported runtime inference |
| Support and debug disclosure | Public artifact consistency / disclosure alignment | Safe category-level support evidence boundary | Separate support/debug wording authorization checkpoint | Support guidance asks for category-level information only | Any support path asks for raw credential, token, request/response, analytics, generated report, screenshot, or browser Network evidence |
| Readme and distribution-facing consistency | Public artifact consistency | Selected scope and release-boundary wording consistency | Separate public artifact wording authorization checkpoint | Description, setup guidance, FAQ, privacy/disclosure, and release-boundary wording remain mutually consistent | Any public artifact contradicts selected scope or implies unsupported capability |

## 9. Controlled Validation Preparation Classes

This checkpoint defines future validation preparation categories only. It does not authorize or execute validation.

| Class | Validation objective | Selected-scope dependency | Permitted safe observable category | Prohibited evidence boundary | Exact final-candidate/package prerequisite | Separate authorization mandatory |
|---|---|---|---|---|---|---|
| Reconnect-required and expired-token recovery category | Confirm selected non-refresh / reconnect-required boundary | Non-refresh public scope and reconnect-required posture | Status/category-level recovery state | Runtime provider correctness, automatic refresh claim, raw provider data | Fixed final candidate/package after release-affecting changes | Yes |
| Local-only disconnect category | Confirm local disconnect remains distinct from provider-side revoke | Local-only disconnect boundary | Visible local control or result category | Provider-side revoke evidence or provider-side cleanup claim | Fixed final candidate/package after release-affecting changes | Yes |
| Explicit non-revoke wording / disclosure category | Confirm public wording does not imply provider-side revoke | Explicit non-revoke public disposition | Wording-boundary category | Provider revocation proof, raw provider state, provider security certification | Fixed final candidate/package after release-affecting changes | Yes |
| Credential value-hidden category | Confirm credential values are not exposed in applicable surfaces | Value-hidden credential boundary | Hidden/value-not-displayed category | Credential, token, option, constant, or password values | Fixed final candidate/package after release-affecting changes | Yes |
| Credential and OAuth safe error / recovery wording category | Confirm category-level user guidance and non-claim wording | Selected OAuth and credential public scope | Status/category-level error or recovery label | Raw provider errors, request/response material, unsupported inference | Fixed final candidate/package after release-affecting changes | Yes |
| Selected public-scope user-flow category | Confirm selected non-refresh / reconnect-required user-flow boundary | Selected public scope only | Status/category-level flow result | Treating unavailable provider runtime behavior as validated | Fixed final candidate/package after release-affecting changes | Yes |
| OpenAI constant-first and transitional fallback posture category | Recheck only if affected by wording, privacy, support, readme, or artifact changes | Constant-first public posture and developer-only / transitional fallback posture | Source category / readiness category | Credential values, option values, constant values | Fixed final candidate/package after affected changes | Yes |

## 10. Validation Method Boundary

Any future controlled validation method must be separately authorized and must define:

- exact final candidate/package identity;
- validation role boundary;
- allowed test context;
- safe category-level observations;
- prohibited raw/private/sensitive evidence;
- whether provider-runtime claims are outside the approved validation scope;
- pass / fail / blocked / unresolved classification;
- cleanup and non-persistence boundary, where applicable;
- invalidation conditions.

A validation method cannot silently expand from UI/status-category confirmation into provider-runtime correctness, refresh execution, provider-side revoke, or external-service certification.

If a future validation requirement cannot be safely met without prohibited evidence, unsupported inference, or unapproved provider-runtime execution, OAuth / credential final-release readiness must remain unclaimed and Step 295.20 must remain Held.

## 11. Required Preparation Order

Each phase below is a separate future checkpoint. This checkpoint starts none of them.

| Phase | Future checkpoint category | Boundary |
|---|---|---|
| Phase 0 | Baseline preservation | Preserve clean committed baseline, selected scope identity, and safe evidence boundaries. |
| Phase 1 | Public wording / privacy / support / readme alignment authorization | Decide only whether bounded future wording-alignment change may be authorized. |
| Phase 2 | Conditional public wording / disclosure / public artifact alignment | Perform only after separate authorization. Any change is release-affecting and invalidates affected candidate/package evidence. |
| Phase 3 | Source-level or category-level wording-boundary review | Confirm selected non-refresh, reconnect-required, local-only disconnect, explicit non-revoke, OpenAI posture, redaction, and non-claim consistency. |
| Phase 4 | Controlled validation plan definition and authorization | Define selected-scope validation classes, exact final candidate/package boundary, safe observations, human role boundary, and fail-closed conditions. |
| Phase 5 | Controlled final-scope validation | Perform only after separate authorization and only after the relevant final candidate/package is fixed. |
| Phase 6 | Final public wording / privacy / support / readme consistency recheck | Complete after controlled validation and before final candidate/package evidence. |
| Phase 7 | Exact final-candidate freeze and package evidence | Freeze exact candidate, build package, inspect contents, validate isolated install, and confirm distribution-artifact consistency after all release-affecting changes. |
| Phase 8 | OAuth and credential prerequisite re-evaluation | Re-evaluate only after selected-scope wording, disclosure, validation, and new candidate/package evidence gates are complete. |
| Phase 9 | Step 295.20 final release-decision authorization re-evaluation | Consider only if all other final release prerequisites are current and independently sufficient at the safe category level. |

## 12. Dependency Table

| Item | Dependency / predecessor category | Future work class | Earliest point it may begin | Scope status | Fail-closed stop condition |
|---|---|---|---|---|---|
| Non-refresh / reconnect-required public wording | Selected non-refresh scope | Wording alignment | Phase 1 authorization, then Phase 2 | Common | Wording implies automatic refresh or automatic expiry recovery |
| Local-only disconnect wording | Local disconnect boundary | Wording alignment | Phase 1 authorization, then Phase 2 | Common | Wording implies provider-side revoke |
| Explicit non-revoke disclosure | Explicit non-revoke disposition | Disclosure alignment | Phase 1 authorization, then Phase 2 | Common | Disclosure implies provider-side authorization revocation |
| OAuth / credential privacy disclosure | Selected OAuth / credential scope | Disclosure alignment | Phase 1 authorization, then Phase 2 | Common | Disclosure introduces unsupported data-handling or runtime claims |
| Support/debug redaction wording | Safe support evidence boundary | Wording alignment / public artifact consistency | Phase 1 authorization, then Phase 2 | Common | Support path requests prohibited evidence |
| Readme and distribution-facing consistency | Selected public scope and public artifact boundary | Public artifact consistency | Phase 1 authorization, then Phase 2 | Common | Public artifact contradicts selected scope |
| OpenAI constant-first and transitional fallback wording recheck | OpenAI posture preserved by Step 295.20.5 | Wording alignment / re-evaluation | Phase 3 after affected wording changes | Conditional | Wording makes Settings fallback primary public guidance |
| Credential value-hidden validation | Credential visibility boundary | Controlled-validation preparation / controlled validation | Phase 4 authorization, then Phase 5 | Common | Validation requires value inspection or disclosure |
| Reconnect-required / expired-token validation | Non-refresh and reconnect-required scope | Controlled-validation preparation / controlled validation | Phase 4 authorization, then Phase 5 | Common | Validation expands into refresh execution or provider-runtime certification |
| Local-only disconnect validation | Local-only disconnect boundary | Controlled-validation preparation / controlled validation | Phase 4 authorization, then Phase 5 | Common | Validation implies provider-side cleanup or revoke |
| Selected-scope user-flow validation | Selected public scope | Controlled-validation preparation / controlled validation | Phase 4 authorization, then Phase 5 | Common | Validation treats unavailable provider runtime behavior as validated |
| Controlled validation plan authorization | Phase 3 wording-boundary review | Controlled-validation preparation | Phase 4 | Common | Plan lacks exact candidate/package boundary or safe evidence boundary |
| Exact final-candidate freeze | All release-affecting changes complete | Candidate-package re-baselining | Phase 7 | Common | Any affected release evidence predates a release-affecting change |
| Final release package / isolated-install evidence | Exact final-candidate freeze | Candidate-package re-baselining | Phase 7 | Common | Package identity, contents, metadata, or procedure changes after evidence |
| OAuth / credential prerequisite re-evaluation | Complete selected-scope wording, validation, and candidate/package gates | Re-evaluation | Phase 8 | Common | Any prerequisite remains unavailable, unresolved, or unsupported |
| Step 295.20 final authorization re-evaluation | All final release prerequisites current and sufficient | Re-evaluation | Phase 9 | Conditional | Any final prerequisite remains Held, unavailable, unresolved, or insufficient |

## 13. Candidate, Package, and Governance Invalidation Boundary

Any of the following invalidates affected candidate-specific evidence and requires a later scoped re-baselining decision:

- candidate source identity change;
- release package identity, metadata, contents, or build-procedure change;
- OAuth scope, reconnect, disconnect, revoke, storage, or credential-source boundary change;
- refresh or provider-side revoke capability being added or publicly claimed;
- OpenAI source, fallback, clear, visibility, or storage posture change;
- privacy, disclosure, support, readme, Settings, Report Builder, or public wording change;
- selected public-release scope or non-claim boundary change;
- functional-flow or safe error-path boundary change;
- toolchain or evidence-interface category change;
- role separation loss;
- required safe evidence category becoming unavailable;
- prohibited evidence becoming required;
- strict Plugin Check and alternative evidence becoming conflated;
- newly eligible public supported Plugin Check contract becoming available.

After invalidation, the preserved candidate/package governance chain must not be silently reused. The process must return to the earliest affected wording, validation, packaging, or governance checkpoint.

## 14. Relationship to Other Step 295.20 Prerequisites

Step 295.20 remains Held.

This plan does not satisfy or reclassify:

- multisite, uninstall, and data-handling readiness;
- final-scope functional and safe error-path readiness;
- final candidate/package readiness;
- distribution-artifact readiness;
- strict Plugin Check aggregate evidence.

Selected-scope OAuth / credential wording and controlled validation work may be necessary, but is not sufficient for final release-decision authorization.

## 15. Next-Step Boundary

This checkpoint does not start:

- public wording or disclosure modification;
- readme, privacy, support, Settings, Report Builder, or release-artifact modification;
- OAuth implementation;
- provider runtime execution;
- browser interaction;
- controlled validation authorization or execution;
- final release-decision authorization re-evaluation;
- final WordPress.org release decision;
- Plugin Check rerun or tool/version change;
- candidate or package modification;
- package rebuild;
- Step 295.20.7;
- Step 295.21;
- Step 296.

## 16. Result Classification

Selected-scope OAuth and credential public wording and controlled validation preparation plan completed; alignment and validation authorization pending.

This result is not:

- public wording alignment completion;
- privacy or disclosure completion;
- controlled validation authorization;
- controlled validation execution;
- OAuth / credential final release readiness;
- final release-decision authorization;
- final WordPress.org release decision;
- WordPress.org public release approval;
- strict Plugin Check limitation resolution;
- zero findings conclusion;
- WordPress.org acceptance prediction.

## 17. Recommended Next Checkpoint

Recommended next checkpoint:

Step 295.20.7: Selected-Scope OAuth and Credential Public Wording and Controlled Validation Authorization Checkpoint.

The next checkpoint should remain docs-only and decision-only. It should preserve the selected scope and decide only whether bounded public wording / disclosure alignment and controlled validation plan definition may be authorized as separate future steps.

The next checkpoint must not perform public wording modification, controlled validation execution, OAuth implementation, provider execution, final release-decision authorization re-evaluation, final WordPress.org release decision, Plugin Check rerun, zero findings conclusion, or public release approval.
