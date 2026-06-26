# OAuth Lifecycle and Credential Public-Release Scope Decision Checkpoint

## 1. Scope and Explicit Exclusions

Step 295.20.3 is a docs-only / decision-only OAuth and credential
public-release scope checkpoint.

This Step selects only the OAuth and credential public-release scope.

This Step does not:

- establish OAuth / credential final release readiness;
- resolve the Step 295.20 Held outcome;
- implement, execute, or validate OAuth lifecycle behavior;
- make a final release-decision authorization;
- make a final WordPress.org release decision;
- establish zero findings;
- resolve strict Plugin Check aggregate evidence.

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

OAuth and credential public-release scope selection is not:

- strict Plugin Check evidence;
- a zero-findings conclusion;
- provider-security certification;
- final OAuth / credential release readiness;
- final release-decision authorization;
- WordPress.org public release approval;
- a prediction of WordPress.org acceptance.

## 2. Baseline and Preservation Summary

Baseline classification:

```text
Clean committed Step 295.20.2 remediation-sequencing-plan baseline
```

Baseline and preservation summary:

| Category | Status | Safe category-level note |
|---|---|---|
| Step 295.20 Held identity | Preserved | Final release-decision authorization remains Held. |
| Step 295.20.1 remediation disposition identity | Preserved | Remediation and later validation required remains preserved. |
| Step 295.20.2 sequencing plan identity | Preserved | The sequencing plan identity remains preserved. |
| OAuth lifecycle and local disconnect boundary continuity | Preserved | Refresh-deferred, reconnect-required, revoke-deferred, and local-only disconnect boundaries remain preserved. |
| OpenAI credential posture continuity | Preserved | Constant-first and developer-only / transitional fallback posture remains preserved. |
| Candidate/package continuity | Preserved | Candidate and package identity remain preserved according to predecessor records. |
| Release-affecting delta | None introduced | This docs-only decision record introduces no release-affecting delta. |

## 3. Decision Criteria Assessment

| Criterion | Status | Concise safe category-level rationale |
|---|---|---|
| A. Non-refresh scope compatibility | Satisfied | Automatic refresh is not represented as complete; refresh-deferred status is compatible with the selected non-refresh scope and does not imply automatic token renewal. |
| B. Reconnect-required and expired-token recovery boundary | Satisfied | Reconnect-required remains the selected recovery posture; expired-token recovery is not claimed as automatic or fully validated; later controlled validation remains mandatory. |
| C. Local disconnect and non-revoke boundary | Satisfied | Local-only disconnect remains explicitly local-only; provider-side revoke is not selected as a public capability. |
| D. Credential source and visibility posture | Satisfied | OAuth and OpenAI credential values remain hidden; constant-first OpenAI posture and developer-only / transitional fallback posture remain preserved. |
| E. Public wording and disclosure dependency | Satisfied | Selected scope requires later wording, privacy, support, readme, and disclosure alignment; wording alignment is not claimed as complete here. |
| F. Controlled validation dependency | Satisfied | Selected scope requires later controlled final-scope validation planning, authorization, and execution; historical or partial evidence is not treated as final validation evidence. |
| G. Candidate/package and governance invalidation boundary | Satisfied | Later release-affecting source, scope, wording, privacy, credential, OAuth, functional, metadata, or package changes invalidate affected candidate-specific evidence. |
| H. Relationship to other final release prerequisites | Satisfied | This OAuth / credential scope selection does not satisfy multisite, uninstall, data-handling, final-scope functional, or safe error-path readiness. |
| I. Fail-closed and non-claim integrity | Satisfied | Unavailable or ambiguous mandatory prerequisites prevent readiness claims; strict Plugin Check limitation remains separately unresolved. |
| J. Scope isolation | Satisfied | The decision is limited to the selected OAuth / credential public-release scope and does not authorize implementation, validation, or final release-decision work. |

## 4. Selected Scope and Exact Meaning

Decision outcome:

```text
Scope selected
```

Refresh public-release scope:

```text
Explicit non-refresh / reconnect-required public-release scope
```

Provider-side revoke public-release scope:

```text
Explicit non-revoke public disposition
```

Local disconnect:

```text
Local-only disconnect boundary retained
```

OpenAI API key posture:

```text
Constant-first public configuration posture retained;
Settings fallback remains developer-only / transitional
```

Selected scope meaning:

- automatic OAuth refresh is not a public-release capability;
- OAuth token expiry or refresh-unavailable state must not be represented as
  automatically recoverable;
- reconnect-required behavior is the bounded user-recovery posture for the
  selected scope;
- local-only disconnect remains distinct from provider-side revoke;
- provider-side revoke is not a public-release capability in the selected
  scope;
- public wording must not imply provider-side token revocation from local-only
  disconnect behavior;
- OpenAI credential values remain hidden;
- no new Settings-based primary OpenAI credential save path is introduced.

Selected scope non-claims:

- OAuth refresh implementation is not complete;
- OAuth provider-runtime correctness is not established;
- expired-token recovery validation is not complete;
- provider-side revoke implementation or validation is not complete;
- OAuth / credential final release readiness is not established;
- final release-decision authorization is not granted;
- WordPress.org public release is not approved;
- WordPress.org acceptance is not predicted;
- strict Plugin Check limitation is not resolved;
- zero findings are not concluded.

## 5. Required Future Work Classes

| Future work class | Status | Safe category-level note |
|---|---|---|
| OAuth refresh implementation | Not required for selected scope | Future addition of refresh capability would invalidate this selected scope and require return to the affected scope-decision / authorization boundary. |
| OAuth refresh runtime validation | Not required for selected scope | Refresh validation is not required while refresh remains outside the selected public scope. |
| Reconnect-required and expired-token recovery controlled validation | Required | The selected recovery posture must be validated at the final-scope category level. |
| Local-only disconnect controlled validation | Required | The local-only disconnect boundary must remain observable without implying provider-side revoke. |
| Provider-side revoke implementation | Not required for selected scope | Future addition of provider-side revoke would invalidate this selected scope and require return to the affected scope-decision / authorization boundary. |
| Provider-side revoke runtime validation | Not required for selected scope | Runtime validation is not required while revoke remains outside the selected public scope. |
| Explicit non-revoke public wording and disclosure alignment | Required | Public wording must preserve the distinction between local-only disconnect and provider-side revoke. |
| OAuth / credential privacy, support, readme, and public wording alignment | Required | Public and support wording must match the selected non-refresh / non-revoke scope. |
| OpenAI constant-first and transitional fallback public-posture recheck | Deferred by selected non-claim boundary | Recheck is required only if affected wording, privacy, support, or release artifact boundaries change. |
| Credential value-hidden and safe error / recovery wording validation | Required | Credential values must remain hidden and safe error/recovery wording must match the selected scope. |
| Selected public-scope user-flow controlled validation | Required | User-facing flows must be validated only for the selected public scope. |
| Exact final-candidate freeze and package / isolated-install evidence | Required | Candidate/package evidence must be regenerated after all release-affecting work is complete. |
| OAuth and credential prerequisite re-evaluation | Required | Re-evaluation is required after applicable remediation, wording, validation, and new candidate/package evidence are complete. |

## 6. Invalidation

The selected scope is invalidated by:

- candidate source identity change;
- release package identity, metadata, contents, or build-procedure change;
- selected OAuth refresh, reconnect, disconnect, revoke, storage, or
  credential-source boundary change;
- refresh-capable or provider-revoke capability being added or publicly
  claimed;
- OpenAI key source, fallback, clear, visibility, or storage posture change;
- privacy, disclosure, support, readme, or public wording change;
- public-release scope or non-claim boundary change;
- functional-flow or safe error-path boundary change;
- toolchain or evidence-interface category change;
- role separation loss;
- required safe evidence category becoming unavailable;
- prohibited evidence becoming required;
- strict Plugin Check and alternative evidence becoming conflated;
- a newly eligible public supported Plugin Check contract becoming available.

After invalidation, the selected scope must not be silently reused.

## 7. Next-Step Boundary

Step 295.20 remains:

```text
Held
```

Final release-decision authorization was not re-evaluated.

Final WordPress.org release decision was not started.

OAuth implementation, provider execution, browser interaction, controlled
validation, and Plugin Check were not run.

Candidate/package was not modified.

Recommended next checkpoint:

```text
Step 295.20.4:
OAuth Lifecycle and Credential Public-Release Scope Decision
Independent Review Preparation and Human Disposition Record
```

Step 295.20.4 should remain docs-only / review-preparation-only. It should
prepare a review package for an independent human reviewer to confirm whether
the selected non-refresh / reconnect-required scope, explicit non-revoke
disposition, local-only disconnect distinction, OpenAI credential posture,
required future work classes, non-claim boundary, and invalidation boundary are
fixed correctly at the safe category level.

Step 295.20.4 must not change the scope selection, implement, validate, grant
final release authorization, approve public release, resolve strict Plugin
Check limitation, or conclude zero findings.

## 8. Result Classification

Step 295.20.3 result classification:

```text
OAuth and credential public-release scope selected:
explicit non-refresh / reconnect-required and explicit non-revoke disposition;
remediation, wording alignment, controlled validation, and final-candidate
evidence remain required
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
