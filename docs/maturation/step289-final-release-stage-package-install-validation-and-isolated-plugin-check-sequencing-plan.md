# Step 289: Final Release-stage Package, Install-validation, and Isolated Plugin Check Sequencing Plan

## 1. Step Objective and Planning Limits

Step 289 is a docs-only / planning-only checkpoint for the final release-stage
sequence after Step 288 classified the narrow authorization-code OAuth
validation gate as matured for its current bounded scope.

This plan defines:

- final-stage phase ordering;
- clean committed release-candidate dependencies;
- source and artifact identity boundaries;
- package build and contents-inspection categories;
- isolated package install-validation boundaries;
- `wp-dev-check`-only Plugin Check sequencing;
- invalidation and re-entry rules;
- dependencies for a separate final WordPress.org release decision.

Step 289 does not execute any final-stage action.

```text
WordPress.org public release readiness:
Hold
```

## 2. Working-tree Baseline Classification

The following commands were run before this document was added:

```text
git status --short --untracked-files=all
git diff --name-only
git diff --check
git log --oneline -8 --decorate
```

The status and diff commands returned no output.

Repository history showed Step 288 committed at `HEAD`, with the corrected
Step 287 result committed immediately upstream.

Baseline classification:

```text
Clean committed Step 288 baseline
```

## 3. Step 288 Input and Preserved Boundaries

Step 288 upstream conclusions:

```text
Source/documentation alignment observed
Narrow authorization-code OAuth validation gate matured
Explicitly deferred / future implementation gate
Final-stage release gate
```

Step 289 preserves these classifications:

| Boundary | Preserved classification |
|---|---|
| Narrow authorization-code OAuth validation | `Matured for the current bounded initial-release scope` |
| Refresh execution | `Explicitly deferred / future implementation gate` |
| Provider-side revoke | `Explicitly deferred / future implementation gate` |
| Complete provider/runtime lifecycle | `Explicitly deferred / future implementation gate` |
| GA4 Fetch runtime behavior | `Deferred / separate release gate` |
| OpenAI Generate runtime behavior | `Deferred / separate release gate` |
| Final public wording review | `Final-stage release gate` |
| Final package and contents inspection | `Final-stage release gate` |
| Final install validation | `Final-stage release gate` |
| Final isolated Plugin Check | `Final-stage release gate` |
| Final WordPress.org release decision | `Final-stage release gate` |

Step 289 does not reopen or repeat:

- the one-time authorization-code OAuth observation;
- local-only cleanup observation;
- OAuth client hybrid-source maturity;
- manual Google Access Token fallback retirement;
- initial single-site support policy;
- refresh or provider-side revoke implementation decisions.

No browser OAuth, provider interaction, callback, token endpoint
communication, Settings save, local disconnect, GA4 Fetch, OpenAI Generate,
refresh, or provider-side revoke is part of the final package sequence.

## 4. Historical Package / Plugin Check Evidence Boundary

The requested historical topics exist under these current repository
filenames:

- `docs/maturation/step105-isolated-plugin-check-results.md`;
- `docs/maturation/step106-plugin-check-findings-triage-release-package-remediation-plan.md`;
- `docs/maturation/step107-release-package-contents-remediation-implementation-results.md`;
- `docs/maturation/step108-isolated-plugin-check-rerun-clean-package-results.md`.

Earlier package-policy records:

- `docs/maturation/step25-distribution-package-policy.md`;
- `docs/maturation/step26-distignore-draft.md`.

Current package procedure surfaces:

- `.distignore`;
- `tools/build-release-zip-dry-run.sh`;
- `analytics-report-ai.php`;
- `readme.txt`;
- `uninstall.php`.

Historical evidence establishes that:

- raw source-tree Plugin Check can report development-only package contents;
- `.distignore` excludes repository, development, maturation, build, test, and
  other non-runtime categories;
- the current build helper stages a distribution outside the source tree;
- the build helper compares plugin header Version and readme Stable tag;
- the build helper checks required runtime paths and excluded development
  paths;
- a prior clean package was installed into `wp-dev-check`;
- a prior isolated Plugin Check against that clean installed package reported
  zero observed Errors, Warnings, and Notices.

That evidence is historical support only. Step 108 predates many later
release-affecting source and public-wording changes. It cannot establish:

- final-candidate artifact identity;
- final package contents;
- final install behavior;
- final Plugin Check outcome;
- final WordPress.org release disposition.

No evidence may be combined across different candidate baselines as if it
described one final artifact.

## 5. Final-stage Sequencing Alternatives

### Option A: Immediate Package, Install, and Plugin Check

Sequence:

```text
Step 288
-> package build
-> install validation
-> isolated Plugin Check
```

What it would clarify:

- package and isolated-check behavior for the current source at execution time.

What it would not clarify:

- whether public wording and release boundaries were frozen first;
- whether a later wording or metadata correction invalidates the artifact.

Relationship to later release-affecting changes:

- any later wording, version, `.distignore`, tool, source, or uninstall change
  would invalidate the results.

Artifact/baseline risk:

- high risk of validating a candidate that is immediately replaced.

Final public wording relationship:

- wording review occurs too late or implicitly.

Final release decision relationship:

- leaves candidate identity and review ordering ambiguous.

Prior-evidence risk:

- does not itself misuse Step 108, but repeats its historical sequence without
  first closing the current wording/baseline dependency.

Disposition:

```text
Needs follow-up release-boundary alignment
```

Option A is not selected.

### Option B: Wording Review, Baseline Freeze, Then Artifact Gates

Sequence:

```text
final public wording / release-boundary consistency checkpoint
-> clean committed release-candidate baseline freeze
-> release-candidate package build
-> package contents inspection
-> isolated package install validation
-> isolated Plugin Check in wp-dev-check only
-> final WordPress.org release decision checkpoint
```

What it would clarify:

- public wording and metadata alignment before artifact creation;
- one committed candidate identity for all downstream evidence;
- package contents, install, and Plugin Check evidence in dependency order.

What it would not clarify:

- legal or policy compliance;
- provider authorization, token validity, refresh, revoke, GA4, or OpenAI
  runtime outcomes.

Relationship to later release-affecting changes:

- explicit invalidation and re-entry rule protects artifact identity.

Artifact/baseline risk:

- lowest among the considered routes when each phase records its candidate
  baseline.

Final public wording relationship:

- wording is reviewed before package creation.

Final release decision relationship:

- creates an ordered evidence chain for a separate final decision.

Prior-evidence risk:

- treats Step 108 as historical procedure evidence, not final-candidate
  evidence.

Disposition:

```text
Final-stage release sequence planned
```

Option B is selected.

### Option C: Source-tree Plugin Check Before Final Package

What it would clarify:

- some source-tree findings.

What it would not clarify:

- distribution contents after `.distignore`;
- installed final-package behavior;
- final package Plugin Check results.

Relationship to later release-affecting changes:

- source results can become stale and may include source-only development
  files.

Artifact/baseline risk:

- high; the checked target is not the final distribution artifact.

Final public wording relationship:

- does not ensure wording freeze before artifact creation.

Final release decision relationship:

- provides the wrong evidence target for the final package gate.

Prior-evidence risk:

- repeats the source-versus-package mismatch identified in Step 105.

Disposition:

```text
Blocked
```

Option C is not selected.

### Option D: Reuse Step 108 as Final Evidence

What it would clarify:

- nothing new about the future final candidate.

What it would not clarify:

- every final-stage gate after later release-affecting changes.

Relationship to later release-affecting changes:

- Step 108 predates them.

Artifact/baseline risk:

- highest; historical and final artifact baselines would be conflated.

Final public wording relationship:

- later wording is absent from the historical candidate.

Final release decision relationship:

- cannot support a final decision for a different candidate.

Prior-evidence risk:

- explicitly treats prior evidence as final-candidate evidence.

Disposition:

```text
Blocked
```

Option D is not selected.

## 6. Preferred Final-stage Sequence

Selected direction:

```text
Option B
```

Required order:

```text
Phase 0: Current gate record and no-reopen boundary
Phase 1: Final public wording and release-boundary consistency checkpoint
Phase 2: Clean committed release-candidate baseline freeze
Phase 3: Release-candidate package build
Phase 4: Package contents inspection
Phase 5: Isolated package install validation
Phase 6: Final isolated Plugin Check in wp-dev-check only
Phase 7: Final WordPress.org release decision checkpoint
```

No later phase may start before the immediately preceding phase has a
completed, candidate-specific record without an unresolved mismatch.

## 7. Phase Definitions

### Phase 0: Current Gate Record and No-reopen Boundary

Entry state:

- Step 288 is committed;
- the working tree is clean;
- the narrow authorization-code OAuth gate remains matured for its bounded
  scope;
- refresh and provider-side revoke remain deferred;
- no provider/runtime action will be repeated during final-stage work.

Permitted future action category:

- docs-only recording of preserved gate classifications.

Evidence category:

- committed step references and Git baseline category.

Prohibited:

- reopening bounded provider/runtime observation without a new trigger and
  authorization;
- treating deferred features as implemented;
- using Step 108 as final-candidate evidence.

Disposition:

```text
Final-stage release gate
```

### Phase 1: Final Public Wording and Release-boundary Consistency Checkpoint

Review-only surfaces:

- `readme.txt`;
- plugin header metadata and version surfaces;
- Stable tag consistency;
- Settings credential, OAuth, lifecycle, refresh, revoke, and local-disconnect
  wording;
- Report Builder credential, lifecycle, GA4 action, and OpenAI action wording;
- uninstall and local-cleanup public wording;
- initial single-site support wording;
- external-service disclosures;
- credentials-hidden and support-evidence boundaries.

Permitted future action category:

- docs-only / source-and-wording review.

Evidence category:

- file, symbol, metadata, section, and category-level wording evidence.

If a release-affecting wording change is needed:

1. stop before package build;
2. route to the minimum required wording-alignment implementation;
3. commit that change;
4. restart Phase 1 against the new clean baseline.

Disposition:

```text
Final-stage release gate
```

### Phase 2: Release-candidate Baseline Freeze Checkpoint

Required conditions:

- all intended production, `readme.txt`, uninstall, tool, package-policy, and
  public-wording changes are committed;
- no modified or untracked file remains;
- plugin header Version, version constant, readme Stable tag, and package
  identity surfaces are mutually consistent;
- no pending release-affecting decision or source/documentation alignment item
  remains;
- no provider/runtime test is scheduled or reused during package work;
- current package procedure and target are identified from repository
  evidence.

Current metadata category observed during Step 289 planning:

```text
Plugin header Version: 0.1.0
Version constant: 0.1.0
readme Stable tag: 0.1.0
```

These current values are orientation only. Phase 2 must reconfirm them on the
future frozen candidate.

If any required condition is absent:

```text
Blocked
```

No package build may begin.

### Phase 3: Release-candidate Package Build

Current repository procedure:

```text
tools/build-release-zip-dry-run.sh
```

Current supporting exclusion mechanism:

```text
.distignore
```

The future package phase must:

- run only from the frozen committed release-candidate baseline;
- use the repository-defined build procedure without hand-editing an archive;
- make no source change during the build;
- tie artifact identity to the committed candidate baseline;
- keep generated output outside the plugin source tree;
- treat stage, archive, and contents listing as generated artifacts;
- exclude credential-bearing local configuration, private files,
  development-only paths, and unrelated generated artifacts;
- retain required runtime files.

Step 289 does not execute the build helper or create an archive.

Disposition:

```text
Final-stage release gate
```

### Phase 4: Package Contents Inspection

Required inspection categories:

- one expected plugin root directory;
- required main plugin file;
- `includes/`;
- `assets/`;
- `readme.txt`;
- `uninstall.php`;
- mutually consistent package metadata surfaces;
- absence of repository metadata;
- absence of `.distignore`, development tools, maturation docs, tests, build
  directories, nested archives, local configuration, and unintended
  artifacts;
- no path traversal or nested-root anomaly;
- no contamination from unrelated working-tree content.

The current `.distignore` excludes source-only and development categories, but
Phase 4 must inspect the generated final candidate rather than infer contents
from ignore rules alone.

Inspection evidence must remain path/category-level and must not print
credential or configuration values.

On mismatch:

```text
Needs follow-up release-boundary alignment
```

Return to the package/distribution alignment phase. Do not continue to install
validation or Plugin Check.

### Phase 5: Isolated Package Install Validation

The future install target must:

- be separate from the active development source tree;
- be isolated from public and production environments;
- receive the built final package artifact, not a source-tree symlink;
- preserve package identity from Phase 3;
- keep install, activation, deactivation, and uninstall categories separate
  from provider/runtime behavior.

Historical Step 108 used `wp-dev-check` as a clean installed package target.
The final plan may use `wp-dev-check` for the isolated final package target
when Phase 5 confirms it is not pointing at the source tree and its target
preparation is explicitly recorded.

Plugin Check remains permitted only in `wp-dev-check`.

Install validation must not execute:

- browser OAuth;
- GA4 Fetch;
- OpenAI Generate;
- refresh;
- provider-side revoke;
- provider interaction.

Step 289 does not install, activate, deactivate, or uninstall the plugin.

Disposition:

```text
Final-stage release gate
```

### Phase 6: Final Isolated Plugin Check

Future execution requirements:

- execute in `wp-dev-check` only;
- target the installed final clean package candidate;
- record command-result categories and permitted summary counts only;
- do not substitute a source-tree Plugin Check;
- do not run browser, provider, OAuth, GA4, or OpenAI actions;
- route any unexpected finding to a targeted package, source, metadata, or
  public-wording follow-up;
- after any release-affecting correction, rebuild and rerun all affected
  downstream phases.

Step 108 is historical support for this procedure, not the final result.

Step 289 does not run Plugin Check or modify `wp-dev-check`.

Disposition:

```text
Final-stage release gate
```

### Phase 7: Final WordPress.org Release Decision Checkpoint

Entry prerequisites:

- Phase 1 final wording/boundary review completed;
- Phase 2 frozen committed candidate recorded;
- Phase 3 artifact tied to that candidate;
- Phase 4 contents inspection completed without unresolved mismatch;
- Phase 5 isolated package install validation completed within its defined
  scope;
- Phase 6 final isolated Plugin Check completed in `wp-dev-check`;
- no unresolved release-affecting source, documentation, metadata, package, or
  environment finding remains;
- deferred features remain accurately described as deferred.

This phase is a separate decision checkpoint. It must not infer:

- legal or privacy-law compliance;
- WordPress.org policy compliance;
- security certification;
- provider lifecycle completion;
- public-release authorization from any single prior gate.

Disposition:

```text
Final-stage release gate
```

## 8. Invalidation and Re-entry Rule

Any release-affecting modification after a completed final-stage phase
invalidates that phase's affected downstream evidence.

Release-affecting examples:

- production source change;
- `readme.txt` or other public-wording change;
- uninstall change;
- package tool or `.distignore` change;
- plugin Version, version constant, Stable tag, or package identity change;
- package procedure change;
- final install-validation target change;
- Plugin Check target or invocation-procedure change.

Required routing:

1. identify the earliest affected phase;
2. return to that phase;
3. establish a new clean committed candidate baseline where applicable;
4. rerun every downstream gate against the new candidate and artifact;
5. do not merge evidence from the old and new artifact baselines.

Docs-only maturation records that do not alter the release artifact may be
recorded separately. They must state whether the candidate artifact changed
and must not obscure candidate identity.

Any uncertainty about the candidate commit, built artifact, installed target,
or evidence-to-artifact relationship is:

```text
Blocked
```

## 9. Phase Matrix

| Final-stage phase | Entry prerequisites | Permitted future action category | Evidence category | Prohibited action or inference | Failure / mismatch routing | Downstream dependency |
|---|---|---|---|---|---|---|
| Phase 0 current gate record | Clean committed Step 288 baseline | Docs-only gate preservation | Commit and category references | Repeating provider/runtime work; reopening deferred features | `Blocked` if baseline or preserved boundary is unclear | Phase 1 |
| Phase 1 final public wording consistency review | Phase 0 recorded | Read-only source/public-wording review | File, section, metadata, symbol, and category evidence | Package build; runtime/provider action; inferring policy compliance | Minimum wording alignment, commit, then restart Phase 1 | Phase 2 |
| Phase 2 release-candidate baseline freeze | Phase 1 completed without unresolved wording mismatch | Clean-baseline and metadata checkpoint | Git state, commit identity, version/stable-tag/package identity categories | Package build from dirty or ambiguous state | `Blocked` until one clean committed candidate is recorded | Phase 3 |
| Phase 3 release-candidate package build | Frozen Phase 2 candidate | Repository-defined build procedure | Artifact identity and build-result category | Hand-edited archive; source changes; secret-bearing or source-tree output | Package procedure follow-up, then return to earliest affected phase | Phase 4 |
| Phase 4 package contents inspection | Artifact tied to Phase 2 candidate | Archive listing and path/category inspection | Required/excluded path and metadata categories | Value inspection; assuming `.distignore` alone proves contents | `Needs follow-up release-boundary alignment`; do not install | Phase 5 |
| Phase 5 isolated package install validation | Phase 4 completed without mismatch | Isolated package install and permitted lifecycle categories | Install-target, package identity, install/activation category | Source symlink target; provider/GA4/OpenAI action; production target | Target/package follow-up; rebuild or reinstall as required | Phase 6 |
| Phase 6 final isolated Plugin Check | Installed final package in `wp-dev-check` | WP-CLI Plugin Check in `wp-dev-check` only | Command category and permitted finding summary | Source-tree substitution; `wp-dev` Plugin Check; external runtime action | Targeted correction, then return to earliest affected phase | Phase 7 |
| Phase 7 final WordPress.org release decision | Phases 1-6 completed against one candidate without unresolved finding | Separate decision checkpoint | Consolidated candidate-specific gate categories | Inferring compliance, certification, or authorization from one result | `Blocked` until unresolved release-affecting item is closed | No public release before separate decision |
| Any release-affecting change after a final-stage phase | A completed phase exists | Invalidation record and re-entry | Changed surface and earliest affected phase | Combining evidence across candidates | Return to earliest affected phase and rerun downstream gates | Replaces stale downstream evidence |

## 10. Remaining Deferred and Final-stage Release Gates

| Gate | Classification |
|---|---|
| Narrow authorization-code OAuth validation | `Matured for the current bounded initial-release scope` |
| Refresh execution | `Explicitly deferred / future implementation gate` |
| Provider-side revoke | `Explicitly deferred / future implementation gate` |
| Complete provider/runtime lifecycle | `Explicitly deferred / future implementation gate` |
| GA4 Fetch runtime behavior | `Deferred / separate release gate` |
| OpenAI Generate runtime behavior | `Deferred / separate release gate` |
| Final public wording review | `Final-stage release gate` |
| Final package / contents inspection | `Final-stage release gate` |
| Final install validation | `Final-stage release gate` |
| Final isolated Plugin Check | `Final-stage release gate` |
| Final WordPress.org release decision | `Final-stage release gate` |

Step 289 does not require GA4 Fetch or OpenAI Generate to be re-executed.
Historical E2E and Step 108 records remain supporting history, not final
candidate evidence.

## 11. Explicit Non-claims

Step 289 does not determine or prove:

- final package correctness;
- package contents correctness;
- installation outcome;
- activation outcome;
- uninstall outcome;
- Plugin Check outcome;
- WordPress.org policy compliance;
- legal or privacy-law compliance;
- credential-storage certification;
- security certification;
- provider authorization;
- token validity;
- refresh or revoke behavior;
- complete OAuth lifecycle behavior;
- GA4 retrieval behavior;
- analytics correctness;
- OpenAI behavior;
- public-release approval.

## 12. Public Release Implication

```text
WordPress.org public release readiness remains Hold.
```

Step 289 defines the final-stage sequence only.

It does not build or inspect a package, install or validate a package, run
Plugin Check, execute provider/runtime behavior, validate GA4 or OpenAI
runtime behavior, or authorize public release.

## 13. Recommended Next Step

```text
Step 290 candidate —
Final public wording and release-boundary consistency checkpoint
```

Step 290 should be docs-only / review-only.

It should review:

- public-facing wording;
- plugin Version and readme Stable tag surfaces;
- credential, privacy, and support-evidence boundaries;
- external-service disclosures;
- initial single-site support wording;
- refresh-deferred and provider-revoke-deferred wording;
- uninstall and local-cleanup wording;
- current source/documentation consistency.

Step 290 must not build or install a package, run Plugin Check, execute browser
OAuth or provider interaction, save Settings, disconnect local tokens, run GA4
Fetch or OpenAI Generate, refresh tokens, or request provider-side revoke.

Step 289 conclusion:

```text
Final-stage release sequence planned
Final-stage release gate
```
