# Step 291R: Clean Committed Release-candidate Baseline Refreeze After Package-procedure Remediation

## 1. Step Purpose and Final-stage Sequence Position

Step 291R establishes a new release-candidate content baseline after the
canonical package scanner remediation completed by Step 292.2.

The package-procedure change superseded the original Step 291 candidate for
future package evidence. Step 291R captures the clean committed
post-remediation state before this document is added.

Final-stage position:

```text
Phase 1:
Final public wording / release-boundary consistency
Completed by Step 290.3 and accepted as committed predecessor evidence

Initial Phase 2:
Original Step 291 baseline freeze
Superseded for future package evidence

Initial Phase 3:
Step 292 package build
Failed before archive creation; historical failure evidence only

Remediation:
Step 292.2 canonical scanner narrow refinement
Completed and committed

Refreeze:
Step 291R clean committed release-candidate baseline refreeze
Established by this record

Next:
Step 292R release-candidate package build retry
Not performed
```

```text
WordPress.org public release readiness:
Hold
```

## 2. Scope and Explicit Non-scope

In scope:

- confirm all required final-stage predecessor records;
- confirm the Step 292.2 remediation and results are committed;
- confirm historical candidate ancestry and expected delta scope;
- confirm the pre-document working tree is clean;
- capture the new full Git commit identity;
- reconfirm updated package-procedure safeguards;
- reconfirm Step 291R documentation package exclusion;
- define the new freeze and downstream invalidation contract;
- define the required Step 292R package-build preflight.

Out of scope:

- source, public wording, Settings, version, scanner, package-rule, or tool
  changes;
- package build or retry;
- ZIP or archive creation;
- package contents inspection;
- package installation;
- Plugin Check;
- browser, provider, OAuth, GA4, or OpenAI actions;
- credential, option, database, request, response, payload, or analytics-data
  inspection;
- public-release approval.

## 3. Initial Baseline and Predecessor Gate Result

The following read-only checks were completed before adding this document:

```text
git status --short --untracked-files=all
git diff --name-only
git diff --check
git rev-parse HEAD
git log -1 --oneline
git log -1 --name-status
git ls-files --error-unmatch <each required predecessor document>
git merge-base --is-ancestor <historical frozen SHA> HEAD
git diff --name-only <historical frozen SHA>..HEAD
```

Results:

- the working tree was clean;
- all required Step 289 through Step 292.2 records were tracked and committed;
- Step 292.2 remediation source and result record were committed at `HEAD`;
- the historical Step 291 frozen SHA remained an ancestor of current `HEAD`;
- the historical delta contained only the expected maturation records and
  the canonical scanner remediation path;
- no unexpected production, public wording, uninstall, `.distignore`,
  version-bearing, asset, or package-tool path was present;
- no repository ZIP or staging content existed;
- an empty ignored `build/` directory existed but contained no artifact,
  stage, or file and did not appear in Git state.

Initial baseline classification:

```text
Clean committed post-remediation release-candidate refreeze baseline
```

Predecessor gate result:

```text
Passed
```

## 4. Sensitive-information / Evidence Boundary

Evidence was limited to:

- Git commit identity and ancestry;
- tracked-file and historical-delta path categories;
- source-level package safeguard categories;
- scanner blocking/warning rule counts;
- shell syntax and clean-tree result categories;
- package exclusion categories.

This checkpoint did not inspect, output, or record:

- credentials, API keys, OAuth tokens, or refresh tokens;
- OAuth client, option, or constant values;
- URLs or callback values;
- scanner patterns, scanner matches, or synthetic fixture content;
- source literals associated with credential-like structures;
- Authorization headers;
- request or response bodies;
- payload JSON;
- generated report text;
- analytics values;
- database contents;
- screenshots;
- browser Network evidence.

## 5. Historical Step 291 Candidate Supersession

Historical Step 291 frozen candidate:

```text
721653d3be6276c2e3103a3092d765ec934ece7a
```

The historical candidate remains valid traceability evidence for:

- the original Phase 2 freeze;
- the failed Step 292 build attempt;
- the Step 292.1 scanner-finding investigation.

It is superseded for future package evidence because Step 292.2 changed the
canonical package procedure.

Classification:

```text
Historical Step 291 frozen candidate:
Superseded for future package evidence by committed package-procedure
remediation
```

Step 292 remains historical failed-build evidence only. It is not reusable as
successful package evidence.

## 6. Phase 1 Prerequisite Confirmation

The committed Step 290.3 record states:

```text
Final public wording and release-boundary consistency recheck:
Pass
```

The Step 292.2 implementation changed only the canonical package scanner and
its maturation result record. It did not change:

- production runtime source;
- `readme.txt` or public wording;
- Settings wording;
- OAuth or OpenAI behavior;
- uninstall behavior;
- initial single-site support boundary;
- the applicable source/documentation assertions.

Phase 1 classification:

```text
Accepted as committed predecessor evidence
```

Step 291R does not rerun Step 290.3.

## 7. New Frozen Release-candidate Content Baseline Identity

The new release-candidate content baseline is the clean committed `HEAD`
captured immediately before this Step 291R document was added.

Full Git commit SHA:

```text
ec54318d1de447aefb5044384a22d55901b1455c
```

One-line commit subject:

```text
Refine release credential scan
```

Pre-document working-tree state:

```text
Clean
```

New frozen identity classification:

```text
Clean committed post-remediation release-candidate content baseline
```

This identity includes the committed scanner refinement and its result record.
It does not represent a built package.

## 8. Historical-delta Scope Review

From the historical Step 291 frozen SHA through the new frozen baseline, the
committed paths were classified as follows:

Permitted documentation-only paths:

- Step 291 baseline-freeze record;
- Step 292 failed-build result;
- Step 292.1 investigation/remediation plan;
- Step 292.2 implementation-results record.

Permitted release-affecting remediation path:

- `tools/build-release-zip-dry-run.sh`.

Unexpected release-affecting paths:

```text
None
```

No production source, `readme.txt`, uninstall, `.distignore`,
version-bearing, asset, or additional package-tool change was present.

Historical continuity result:

```text
Confirmed
```

## 9. Updated Canonical Package-procedure Safeguard Recheck

The post-remediation package procedure was reviewed read-only.

| Safeguard | Result |
| --- | --- |
| `docs/maturation/` excluded from staging | Confirmed |
| `docs/maturation/` rejected from archive output | Confirmed |
| `tools/` excluded from package staging | Confirmed |
| `tools/` rejected from archive output | Confirmed |
| Canonical script remains package-procedure owner | Confirmed |
| Repository-external build-root guard | Confirmed |
| Symlinked build-root refusal | Confirmed |
| `.distignore` unchanged by remediation | Confirmed |
| Staged PHP syntax checks | Confirmed |
| High-risk scanner before archive creation | Confirmed |
| Four blocking high-risk rule categories active | Confirmed |
| Warning-only credential-keyword categories active | Confirmed |
| Path plus generic-category reporting | Confirmed |
| No file/helper/directory/PHP-wide allowlist | Confirmed |
| Remediation limited to assignment-like distinction | Confirmed |
| Archive structure, staging, output, metadata, and other package rules unchanged | Confirmed |

Shell syntax result:

```text
Pass
```

Updated package-procedure safeguards:

```text
Reconfirmed at source level
```

No package build was performed.

## 10. Step 291R Documentation Package-exclusion Boundary

The Step 291R record path is:

```text
docs/maturation/step291r-clean-committed-release-candidate-baseline-refreeze-after-package-procedure-remediation.md
```

The current source-level package procedure:

- excludes the complete `docs/maturation/` path from staging;
- rejects that path if found in staging;
- rejects that path from archive contents.

Classification:

```text
Step 291R maturation record is source-level confirmed as excluded from the
release package procedure
```

A later commit that adds only this record does not change candidate package
content while the reviewed exclusion procedure remains unchanged.

No package was built to reach this conclusion.

## 11. Freeze Contract and Downstream Invalidation Rule

The Step 291R freeze contract is:

1. The new frozen release-candidate content baseline is commit
   `ec54318d1de447aefb5044384a22d55901b1455c`.
2. The old Step 291 candidate remains historical evidence only and is
   superseded for future package evidence.
3. Step 291R adds only a package-excluded maturation record after capturing
   the new identity.
4. No release-affecting change may be combined with the Step 291R
   documentation-only commit and still be treated as the same candidate.
5. Future package, inspection, install, Plugin Check, and release-decision
   evidence must be traceable to the new frozen baseline.
6. Any future release-affecting change invalidates affected downstream
   evidence and requires return to the earliest affected final-stage gate.

Release-affecting changes include:

- production PHP, JavaScript, CSS, or assets;
- `readme.txt` or public wording;
- `uninstall.php`;
- `.distignore`;
- package tools or package procedure;
- plugin Version, version constant, or Stable tag;
- shipped runtime configuration.

Evidence from different candidate baselines must not be combined as if it
described one artifact.

Any uncertainty about candidate identity or evidence-to-artifact association
is:

```text
Blocked
```

## 12. Required Step 292R Package-build Preflight Contract

Before one canonical Step 292R build attempt, the following must all pass:

1. The new frozen baseline SHA recorded by Step 291R is an ancestor of current
   `HEAD`.
2. The committed difference from the new frozen baseline through current
   `HEAD` contains only:

   ```text
   docs/maturation/step291r-clean-committed-release-candidate-baseline-refreeze-after-package-procedure-remediation.md
   ```

   if this Step 291R record has been committed.
3. The Step 291R record remains excluded from staging and archive output.
4. No release-affecting source, public wording, uninstall, `.distignore`,
   package-tool, package-procedure, version, Stable tag, or metadata change
   occurred after the new baseline.
5. The canonical package procedure remains the remediated procedure reviewed
   by this checkpoint.
6. The working tree is clean.
7. The output remains repository-external and protected by source-tree and
   symlink guards.
8. The build candidate identity is recorded and traceable to the new Step
   291R baseline.

Required comparison:

```text
git diff --name-only <new-frozen-baseline-sha>..HEAD
```

If any item fails:

- Step 292R must not build a package;
- no alternate or manual archive construction is permitted;
- the result is `Blocked`;
- the sequence returns to the earliest affected final-stage gate.

Step 292R preflight contract status:

```text
Defined
```

## 13. Final Artifact Gates Not Yet Performed

```text
Release-candidate package:
Not built

Package contents inspection:
Not performed

Isolated package install validation:
Not performed

Final isolated Plugin Check:
Not performed

Final WordPress.org release decision:
Not performed
```

No artifact identity, package contents result, install result, Plugin Check
result, or release approval is established by Step 291R.

## 14. Explicitly Non-executed Actions

Step 291R did not perform:

- production PHP, JavaScript, or CSS modification;
- `readme.txt` modification;
- Settings wording modification;
- version, Stable tag, or version-constant modification;
- `.distignore` modification;
- package-tool or scanner-rule modification;
- package build or retry;
- ZIP or archive creation;
- package contents inspection;
- archive listing, extraction, integrity testing, or comparison;
- package installation;
- plugin activation, deactivation, or uninstall;
- Plugin Check;
- browser OAuth;
- provider interaction;
- callback execution;
- token endpoint communication;
- Settings save;
- local disconnect;
- GA4 Fetch;
- OpenAI Generate;
- refresh;
- provider-side revoke;
- screenshot collection;
- browser Network inspection;
- WP-CLI;
- option inspection;
- raw SQL;
- database dump;
- raw scanner pattern, match, or fixture output;
- credential, token, option, constant, or OAuth client value inspection;
- commit;
- push.

## 15. Step 291R Conclusion

Clean committed release-candidate content baseline refreeze:

```text
Established
```

Historical Step 291 frozen candidate:

```text
Superseded for future package evidence by committed package-procedure
remediation
```

Phase 1 final public wording / release-boundary consistency:

```text
Accepted as committed predecessor evidence
```

Updated package-procedure safeguards:

```text
Reconfirmed at source level
```

New frozen baseline:

```text
The clean committed HEAD captured before the Step 291R record was added
```

Step 291R documentation exclusion:

```text
Source-level confirmed
```

Release-candidate package:

```text
Not built
```

Package contents inspection:

```text
Not performed
```

Isolated package install validation:

```text
Not performed
```

Final isolated Plugin Check:

```text
Not performed
```

Any release-affecting modification after the new frozen baseline invalidates
affected downstream evidence and requires return to the earliest affected
final-stage gate.

## 16. Release Readiness Statement

```text
WordPress.org public release readiness:
Hold
```

The Hold remains because:

- the remediated release-candidate package has not been built;
- package contents have not been inspected;
- isolated package install validation has not been performed;
- final isolated Plugin Check has not been performed;
- no final WordPress.org release decision has been completed.

## 17. Recommended Next Gate

Recommended next step:

```text
Step 292R: Release-candidate package build retry after package-procedure
remediation
```

Step 292R must first satisfy the preflight contract defined by this
checkpoint, then may execute one canonical build attempt. It must not use an
alternate or manual package procedure.
