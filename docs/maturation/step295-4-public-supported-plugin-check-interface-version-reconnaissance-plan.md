# Step 295.4: Public Supported Plugin Check Interface / Version Reconnaissance Plan

## 1. Step Purpose and Relation to Steps 295 Through 295.3

Step 295.4 defines a future non-mutating reconnaissance process for identifying
whether a publicly supported Plugin Check interface or version can provide
deterministic machine-readable clean-result evidence.

The predecessor sequence established:

- Step 295 ran one isolated Plugin Check command, but safe aggregate evidence
  was unavailable;
- Step 295.1 identified finding-bearing strict machine-readable structure but
  no clean-result strict JSON contract;
- Step 295.2 established that the current public supported interface does not
  provide a deterministic clean-result machine-readable route;
- Step 295.3 selected Option B for future non-mutating reconnaissance while
  retaining Option A as the immediate Hold posture.

This step plans the evidence hierarchy, comparison method, adoption criteria,
containment, and rollback boundaries. It does not retrieve external evidence
or alter the current toolchain.

## 2. Scope and Explicit Non-scope

In scope:

- confirm the committed Step 291R through Step 295.3 predecessor chain;
- confirm frozen-candidate continuity and a clean planning baseline;
- define authoritative-source tiers for future reconnaissance;
- define candidate interface/version eligibility criteria;
- define a non-mutating future comparison method;
- define a separate comparison-environment boundary;
- define adoption, evidence-invalidation, rollback, and containment rules;
- define category-level evidence requirements for a future execution step;
- add this planning record.

Explicitly outside scope:

- external web search, release lookup, package lookup, or version comparison;
- Plugin Check execution or rerun;
- raw report recovery or inspection;
- Plugin Check, WP-CLI, WordPress, PHP, OS, or environment changes;
- parser implementation or synthetic fixture work;
- package build, archive operation, package installation, or plugin lifecycle
  change;
- access to or mutation of `wp-dev-check`;
- any operation in the normal `wp-dev` environment;
- production, public-wording, uninstall, package-tool, or metadata changes;
- browser, provider, OAuth, GA4, or OpenAI actions;
- final public-release approval.

## 3. Initial Baseline and Predecessor Gate Result

The initial repository working tree was clean.

The Step 291R, Step 292R, Step 293, Step 294, Step 295, Step 295.1, Step
295.2, and Step 295.3 records were tracked and committed.

Current committed `HEAD` before this document:

```text
367815ace5301df0266555e824811c2307fd3c2b
```

Commit subject:

```text
Decide Plugin Check evidence route
```

The frozen candidate remains an ancestor of current `HEAD`. The post-baseline
Git difference contains only the committed Step 291R through Step 295.3
maturation records.

No release-affecting source, public wording, uninstall, `.distignore`,
package-tool, package-procedure, version, Stable tag, version-constant, asset,
shipped runtime configuration, or package metadata change was present.

Initial baseline classification:

```text
Clean committed Plugin Check reconnaissance-plan baseline
```

Predecessor gate:

```text
Passed
```

## 4. Historical Plugin Check Evidence Limitation

The following facts remain fixed:

- Step 295 ran exactly one isolated Plugin Check command;
- the command exit status was successful;
- Errors / Warnings / Notices aggregate evidence was unavailable;
- raw output was not displayed, inspected, recorded, recovered, or reused;
- finding-bearing strict machine-readable structure exists;
- no current public supported deterministic clean-result machine-readable
  route has been established;
- Step 295.3 selected future reconnaissance while preserving the current Hold.

No future capability may be inferred from version chronology, a version name,
private implementation behavior, or command success alone.

## 5. Sensitive-information and Output-safety Boundary

Planning evidence is limited to:

- source tier and authority categories;
- public-support and documentation categories;
- interface/version eligibility criteria;
- machine-readable output and clean-result categories;
- comparison, adoption, invalidation, containment, and rollback categories;
- Git and candidate-continuity categories.

This step did not display, inspect, retrieve, or record:

- raw Plugin Check output or JSON reports;
- issue objects, issue text, source paths, line numbers, snippets, or scanner
  patterns;
- arbitrary command help or implementation excerpts;
- credentials, tokens, OAuth client values, option values, or constant values;
- URLs, callback values, request or response material;
- payloads, analytics data, or generated report text;
- screenshots, browser Network evidence, or database content.

Future evidence records must use the same category-level boundary.

## 6. Current Operational Posture and Fixed Non-authorizations

The committed Step 295.3 posture remains:

```text
Current Plugin Check version category:
2.0.0

Current supported clean-result machine-readable route:
Not established

Immediate operational posture:
Retain the current Plugin Check interface/version unchanged

WordPress.org public release readiness:
Hold
```

The retained candidate state is accepted from the committed Step 295.3 record;
`wp-dev-check` was not accessed in this step.

Step 295.4 does not authorize:

- Plugin Check update, downgrade, installation, replacement, or modification;
- WP-CLI, WordPress, PHP, OS, or environment changes;
- Plugin Check rerun;
- parser implementation;
- synthetic verification;
- candidate, package, package-procedure, or retained-target changes;
- final release approval.

## 7. Authoritative-source Hierarchy for Future Reconnaissance

### Tier 1: Required Authoritative Evidence

Any eligibility claim must use:

1. official Plugin Check distribution or release metadata;
2. official Plugin Check documentation or officially maintained command
   interface documentation;
3. official release notes, changelog, or version-tagged project documentation
   for the exact candidate version;
4. official WP-CLI documentation when global output or channel behavior is
   material;
5. future local installed metadata and non-mutating command-interface evidence
   from an isolated comparison environment.

No single Tier 1 source is sufficient when the clean-result contract depends
on runtime command behavior.

### Tier 2: Supporting Technical Evidence

Permitted supporting evidence:

- version-tagged source code for the exact candidate version;
- official issue or discussion material that clarifies a documented contract;
- local static inspection of the exact installed comparison version;
- non-mutating command help and metadata checks in the comparison environment.

Tier 2 may explain implementation behavior but cannot replace a missing public
support contract.

### Tier 3: Context Only

Non-authoritative context includes:

- blog posts;
- third-party tutorials;
- community answers;
- copied command examples;
- informal comments without official contract status;
- search-result summaries.

Tier 3 may identify questions but cannot establish release-gate eligibility.

## 8. Candidate Interface / Version Eligibility Criteria

A future Plugin Check interface/version is eligible for a separate
qualification step only if every criterion passes:

| Criterion | Required result |
|---|---|
| Public availability | Publicly distributed; not private or development-only |
| Public supported contract | Documented or officially supported for the exact version |
| Clean-result representation | Deterministic and machine-readable |
| Strict machine readability | One strict document or equivalent deterministic envelope |
| Aggregate-only safety | Counts derivable without exposing finding details |
| Severity semantics | Error, warning, and notice behavior publicly established |
| Channel separation | Report and diagnostics separable or equivalently contracted |
| Fail-closed feasibility | Malformed, unknown, and ambiguous output rejectable |
| Isolated compatibility | Testable outside existing project environments |
| Reproducibility | Exact tool/interface categories recordable and repeatable |

A version that provides only finding-bearing structured output is ineligible.

Eligibility outcomes for future comparison:

```text
Eligible
Ineligible
Insufficient evidence
```

Eligibility does not itself authorize adoption.

## 9. Future Non-mutating Comparison Method

The future reconnaissance must compare each candidate interface/version with:

```text
Current baseline version category:
2.0.0

Current limitation:
No public supported deterministic machine-readable clean-result route

Current operational posture:
Hold
```

For each candidate, the future execution may answer only:

- whether a public supported machine-readable interface exists;
- whether clean output is deterministic and strict;
- whether clean output avoids human-text conversion;
- whether finding-bearing output can be aggregated without issue details;
- whether error, warning, and notice semantics are established;
- whether output channels are safely separable;
- whether a fail-closed parser is feasible;
- whether the candidate qualifies for isolated local evaluation.

Future comparison stages:

1. collect Tier 1 source categories;
2. add Tier 2 evidence only to clarify the exact public contract;
3. classify each criterion without installing or running a candidate;
4. stop early as `Ineligible` when an authoritative source disproves a
   mandatory criterion;
5. use `Insufficient evidence` rather than infer missing behavior;
6. propose local isolated qualification only for an `Eligible` candidate;
7. require a separate adoption decision before any environment mutation.

## 10. Isolated Comparison-environment Boundary

Future local runtime verification must not use:

- the normal `wp-dev` environment;
- the retained package target or current Plugin Check installation in
  `wp-dev-check`;
- the source repository.

Any later local evaluation requires a separately authorized environment that
is:

- newly prepared and isolated;
- separate from `wp-dev`, `wp-dev-check`, and the source repository;
- free of source-tree symlinks;
- free of the retained release-candidate target;
- disposable without changing current release evidence;
- scoped only to the exact approved comparison version/interface.

This step does not authorize creation of that environment.

Source/interface reconnaissance should remain non-runtime when authoritative
evidence already establishes ineligibility.

## 11. Adoption Prerequisites and Evidence-invalidation Boundary

Before any future toolchain change:

- one exact Plugin Check version/interface must be selected by a separate
  decision;
- every eligibility criterion must pass;
- exact public support and source identity categories must be recorded;
- isolated comparison must complete before any `wp-dev-check` change;
- parser design and synthetic verification must be separately authorized;
- candidate ZIP and source must remain unchanged;
- retained-target continuity must be reconfirmed;
- stdout/stderr and fail-closed behavior must be demonstrated safely;
- adoption and cleanup procedures must be explicit and reversible.

Evidence invalidation rules:

- Plugin Check evidence is toolchain-specific;
- Step 295 evidence cannot be silently reclassified after a toolchain change;
- evidence from a comparison environment is not final candidate evidence;
- any adopted toolchain requires a new isolated qualification and final Plugin
  Check aggregate-evidence gate;
- any release-affecting candidate change requires re-entry at the earliest
  affected release phase;
- Step 296 remains unavailable until the new final gate passes.

## 12. Rollback and Containment Boundary

Future rollback is an environment-containment action, not an evidence shortcut.

Rules:

- do not overwrite the current `wp-dev-check` Plugin Check installation before
  isolated qualification completes;
- reject or abandon a candidate only in its separate comparison environment;
- remove a rejected comparison environment only under a separately authorized
  cleanup step;
- do not alter the candidate repository, frozen SHA, ZIP, package procedure,
  or retained target during rollback;
- do not reuse rejected output as release evidence;
- do not update, downgrade, or replace `wp-dev-check` merely to recover from a
  failed comparison;
- preserve enough category-level metadata to explain why the candidate was
  rejected without retaining raw output.

## 13. Future Evidence-record Requirements

A future reconnaissance record may contain only:

- authoritative-source tier availability;
- exact candidate version category;
- public support classification;
- machine-readable format availability;
- deterministic clean-result representation category;
- finding-bearing aggregation eligibility;
- severity semantics category;
- stdout/stderr contract category;
- fail-closed parser feasibility;
- comparison outcome;
- adoption status: not authorized or requires separate decision.

It must not contain:

- raw Plugin Check output or JSON;
- issue objects or issue details;
- source paths, lines, snippets, or scanner patterns;
- arbitrary source excerpts;
- credentials, tokens, OAuth client values, options, or constants;
- URLs, callback values, request or response material;
- payloads, analytics data, or generated report text;
- screenshots, Network evidence, or database content.

Future records must distinguish clearly:

```text
Authoritative contract evidence
Supporting implementation evidence
Local interface confirmation
Runtime qualification evidence
Final candidate evidence
```

## 14. Candidate Continuity and Final Release-decision Dependency

Frozen release-candidate content baseline:

```text
ec54318d1de447aefb5044384a22d55901b1455c
```

Step 295.4 preserves:

- release ZIP identity;
- package contents-inspection evidence;
- isolated package-install evidence;
- committed retained-target state;
- current Plugin Check installation/version;
- Option A operational Hold;
- source repository and package procedure.

No Plugin Check rerun is authorized.

Step 296 remains blocked until a newly authorized evidence gate establishes:

```text
Plugin Check command exit status:
Success

Machine-readable report parse:
Pass

Errors:
0

Warnings:
0

Notices:
0 under the approved public interface contract
```

## 15. Final Artifact Gates Still Not Completed

```text
Authoritative public interface/version reconnaissance execution:
Not performed

Candidate interface/version qualification:
Not performed

Comparison environment authorization:
Not performed

Toolchain adoption decision:
Not performed

Parser implementation:
Not authorized

Synthetic verification:
Not authorized

Final isolated Plugin Check aggregate-evidence rerun:
Not authorized

Final WordPress.org release decision:
Not performed
```

## 16. Explicitly Non-executed Actions

This step did not perform:

- external web search, release lookup, package lookup, or version comparison;
- access to or mutation of `wp-dev-check`;
- Plugin Check or a Plugin Check rerun;
- raw report recovery or inspection;
- Plugin Check, WP-CLI, WordPress, PHP, OS, or environment changes;
- parser implementation or synthetic fixture work;
- package build, archive operation, package installation, or plugin lifecycle
  change;
- any WordPress or filesystem mutation in `wp-dev`;
- production PHP, JavaScript, CSS, asset, `readme.txt`, Settings, version,
  Stable tag, `.distignore`, package-tool, or scanner changes;
- browser or admin UI access;
- Settings save;
- OAuth, provider, token endpoint, refresh, revoke, or local disconnect;
- GA4 Fetch or OpenAI Generate;
- option or credential inspection, raw SQL, or database dump;
- screenshots or browser Network inspection;
- commit or push.

## 17. Step 295.4 Conclusion

```text
Public supported Plugin Check interface/version reconnaissance plan:
Completed

Current Plugin Check interface/version:
Unchanged

Current operational posture:
Maintain WordPress.org public release readiness Hold

Future reconnaissance:
Authorized only as a separate non-mutating source/interface evidence step

Plugin Check update, replacement, local installation, parser implementation,
synthetic verification, and rerun:
Not authorized by this Step

Final WordPress.org release decision:
Not performed
```

## 18. Release Readiness Statement

```text
WordPress.org public release readiness:
Hold
```

The plan preserves the existing release candidate and evidence boundaries
while allowing a future authoritative-source reconnaissance to determine
whether an eligible public interface/version exists.

## 19. Recommended Next Gate

```text
Step 295.5: Public supported Plugin Check interface/version reconnaissance execution
```

Step 295.5 should retrieve and compare only authoritative public
source/interface evidence under this hierarchy. It must not update, install,
replace, run, or modify Plugin Check, WP-CLI, WordPress, the candidate package,
or the retained target.
