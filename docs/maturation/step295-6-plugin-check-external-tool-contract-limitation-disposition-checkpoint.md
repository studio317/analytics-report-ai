# Step 295.6: Plugin Check External-tool Contract Limitation Disposition Checkpoint

## 1. Step Purpose

Step 295.6 is a docs-only and decision-only checkpoint for the external-tool
contract limitation established by the preceding Plugin Check evidence track.

The current public supported Plugin Check interface/version cannot
simultaneously establish:

```text
Strict parse success
Errors 0
Warnings 0
Notices 0
```

This checkpoint decides how that limitation should be governed. It does not
reassess the candidate classifications from Step 295.5 and is not a final
public-release decision.

## 2. Scope and Decision Boundary

This decision concerns only the disposition of the final Plugin Check evidence
limitation.

It does not conclude that Plugin Check lacks quality or value. The limitation
is narrower: the current public supported contract does not provide the
deterministic machine-readable clean-result representation required by this
project's strict release gate.

Out of scope:

- reconsidering the Step 295.5 ineligible classifications;
- supplementing the prior Plugin Check result;
- changing the candidate, package, target, tool, or environment;
- defining or validating alternative evidence;
- starting the final public-release decision.

## 3. Baseline and Preserved State

Baseline classification:

```text
Clean committed external-tool limitation disposition baseline
```

Predecessor and continuity results:

```text
Required predecessor chain:
Passed

Frozen candidate continuity:
Preserved

Post-baseline release-affecting delta:
None
```

The committed predecessor evidence continues to preserve:

- the frozen release-candidate content baseline;
- release package identity;
- package contents-inspection evidence;
- isolated package-install evidence;
- the retained isolated candidate-target category;
- the current Plugin Check installation/version category.

## 4. Historical Evidence Limitation

The historical evidence remains:

- one isolated Plugin Check command completed successfully;
- aggregate Errors / Warnings / Notices evidence was not established;
- raw output was not displayed, inspected, recovered, or reused;
- no eligible public supported interface/version was identified by the
  authoritative-source reconnaissance;
- the current release posture is Hold.

This checkpoint does not add execution evidence and does not reinterpret the
historical result.

## 5. Permanent Non-evidence Rule

The following rule applies to both disposition options and to all later
release-evidence decisions:

```text
Plugin Check command success, silence, human-readable success output, and
private implementation behavior do not prove zero findings.
```

Accordingly:

- a successful command exit is not Errors 0 / Warnings 0 / Notices 0;
- quiet-mode silence is not a machine-readable clean report;
- human-readable success text is not aggregate evidence;
- internal result objects, exporters, or private branches are not public
  release-evidence contracts.

This boundary is not relaxed by the selected decision.

## 6. Option A Analysis

Option A retains the current Hold posture until a future eligible public
supported Plugin Check interface/version is identified.

### Benefits

- preserves the strict public supported contract requirement unchanged;
- avoids false assurance;
- keeps candidate and package evidence internally consistent;
- requires no tool, environment, parser, or policy change;
- remains simple to audit.

### Constraints

- cannot close the final Plugin Check release gate;
- leaves public-release eligibility unresolved;
- may create indefinite stagnation if no eligible tool contract appears;
- provides no separately governed route for other release evidence.

### Operational Meaning

Under Option A:

- the current candidate is not reclassified as zero findings;
- release readiness remains Hold;
- public-release eligibility remains undecided;
- a future eligible Plugin Check contract would require a separate
  qualification and evidence gate.

Option A is evidence-safe but does not create a path beyond the current
external-tool limitation.

## 7. Option B Analysis

Option B permits a future, separately governed alternative final
release-evidence policy while preserving the strict Plugin Check limitation as
unresolved.

Option B is not permission to reduce evidence quality. It separates two
different evidence categories:

```text
Strict Plugin Check evidence:
Still unavailable under the required clean-result contract

Alternative final release evidence:
Not yet defined, adopted, implemented, or validated
```

### Minimum Governance Conditions

Any future alternative evidence policy must:

- never translate Plugin Check command success, silence, human-readable output,
  or private behavior into zero findings;
- keep strict Plugin Check status separate from alternative evidence status;
- define authority, scope, inputs, acceptance criteria, failure handling,
  reproducibility, and disclosure boundaries in a separate decision;
- define candidate, package, and tool identity controls;
- require an independent review and approval checkpoint before adoption;
- define invalidation and rollback conditions;
- avoid credentials, secret values, raw output, and private implementation
  evidence;
- retain Step 295.5 as an unresolved strict Plugin Check limitation;
- avoid automatically changing public release readiness to Pass.

### Operational Meaning

Option B creates a planning route that may avoid indefinite stagnation without
weakening the existing strict gate or concealing its unresolved status.

It does not authorize alternative evidence implementation, validation, or use.

## 8. Selected Decision

```text
Selected option:
Option B
```

The project will allow a future planning-only governance track for an
alternative final release-evidence policy.

The current operational posture remains:

```text
Strict Plugin Check release evidence:
Unresolved

Alternative release-evidence policy:
Not defined or adopted

WordPress.org public release readiness:
Hold
```

## 9. Decision Rationale

Option B is selected because it:

- preserves the integrity and meaning of the strict Plugin Check gate;
- does not create false zero-findings assurance;
- keeps the frozen candidate and package evidence auditable;
- requires explicit governance before any alternative evidence can be used;
- preserves a return-to-Hold path;
- avoids treating external-tool contract stagnation as an automatic permanent
  release veto;
- creates a possible future route without authorizing release or weakening
  current evidence standards.

Option A remains the fallback operational state until every required Option B
governance and validation gate is completed.

## 10. Adoption Preconditions

An alternative evidence policy may be considered for adoption only after:

- a separate planning record defines the policy completely;
- the evidence authority and intended scope are explicit;
- inputs and acceptance criteria are deterministic;
- failure and unavailable-evidence outcomes fail closed;
- candidate, package, and tool identity controls are defined;
- raw output and sensitive values are excluded;
- reproducibility requirements are documented;
- independent review approves the policy;
- a separate validation plan is approved;
- validation completes without weakening the non-evidence rule;
- a distinct adoption decision is recorded.

Completion of these conditions would not automatically approve public release.

## 11. Invalidation Triggers

Any future alternative policy or evidence becomes invalid when:

- the candidate source or release package changes;
- package identity or metadata changes;
- the evidence-producing tool or interface changes;
- the evidence contract, parser, or acceptance criteria changes;
- required inputs are missing or ambiguous;
- raw or sensitive evidence is required to interpret the result;
- reproducibility cannot be established;
- a finding or failure category cannot be classified safely;
- a newly eligible public supported Plugin Check contract changes the
  applicable release-evidence decision.

Invalidated evidence must not be silently reused.

## 12. Rollback and Return-to-Hold Behavior

The default rollback result is:

```text
WordPress.org public release readiness:
Hold
```

Return to Hold is required when:

- alternative policy planning is incomplete;
- independent review rejects the policy;
- validation fails or is inconclusive;
- evidence becomes invalid;
- candidate or package continuity is lost;
- the evidence boundary requires raw or sensitive material;
- release governance cannot distinguish strict Plugin Check status from
  alternative evidence status.

Rollback does not alter the frozen candidate, package, retained target, or
current Plugin Check installation.

## 13. Reconsideration Boundaries

Direct strict Plugin Check proof may be reconsidered if a future exact public
supported interface/version provides:

- deterministic machine-readable clean-result representation;
- strict parse success;
- safe and explicit Errors / Warnings / Notices semantics;
- separable or equivalently safe output channels;
- fail-closed aggregate evidence.

That interface/version must be reevaluated under the comparison criteria
established by Step 295.5 and must pass a separate qualification and evidence
gate.

This disposition decision must be revisited if:

- an eligible public Plugin Check contract becomes available;
- the project's final release-evidence requirements change;
- WordPress.org submission requirements materially change;
- an approved alternative evidence policy reaches adoption review;
- candidate continuity or package identity changes.

## 14. Candidate and Evidence Continuity Boundary

This decision changes no release artifact or runtime state.

Preserved categories:

- frozen candidate identity;
- release package identity;
- package contents-inspection evidence;
- isolated package-install evidence;
- committed retained-target state;
- current Plugin Check installation/version;
- source repository and package procedure.

Strict Plugin Check evidence and future alternative evidence must remain
separately labeled and independently auditable.

## 15. Next-step Boundary

This checkpoint does not start or authorize:

- alternative evidence policy implementation;
- alternative evidence validation;
- Plugin Check rerun;
- Plugin Check tool or version change;
- parser implementation or synthetic verification;
- package rebuild;
- final release decision;
- Step 296.

Only a planning-only governance checkpoint may follow.

## 16. Final Artifact Gates Still Not Completed

```text
Alternative release-evidence policy definition:
Not performed

Alternative release-evidence authority review:
Not performed

Alternative release-evidence validation:
Not performed

Alternative release-evidence adoption decision:
Not performed

Strict Plugin Check aggregate evidence:
Unresolved

Final WordPress.org release decision:
Not performed
```

## 17. Explicitly Non-executed Actions

This step did not perform:

- Plugin Check execution or rerun;
- Plugin Check update, replacement, installation, or removal;
- WP-CLI, WordPress, PHP, OS, or environment changes;
- candidate or package modification;
- package build or rebuild;
- parser implementation or synthetic verification;
- fixture creation;
- source-code modification;
- final WordPress.org release decision;
- Step 296.

No existing Plugin Check result was supplemented through execution, analysis,
inference, or private implementation investigation.

## 18. Step 295.6 Conclusion

```text
Plugin Check external-tool contract limitation disposition checkpoint:
Completed

Selected option:
Option B

Strict Plugin Check limitation:
Unresolved and explicitly preserved

Alternative final release-evidence policy:
Authorized for future planning only

Alternative policy adoption, implementation, and validation:
Not authorized

WordPress.org public release readiness:
Hold

Final WordPress.org release decision:
Not performed
```

## 19. Recommended Next Planning Checkpoint

```text
Step 295.7:
Alternative final release-evidence policy governance plan
```

The next checkpoint should remain docs-only and planning-only. It should define
the alternative evidence policy's authority, scope, inputs, acceptance
criteria, failure handling, reproducibility, disclosure, invalidation,
rollback, independent review, and adoption boundaries without implementing or
validating the policy.
