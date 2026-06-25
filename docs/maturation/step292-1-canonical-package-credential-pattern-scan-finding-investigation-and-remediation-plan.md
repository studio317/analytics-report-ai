# Step 292.1: Canonical Package Credential-pattern Scan Finding Investigation and Remediation Plan

## 1. Step Purpose and Relation to Step 292 Failure

Step 292.1 is a docs-only investigation and remediation-planning checkpoint
for the canonical package-build failure recorded by Step 292.

Step 292 established:

```text
Release-candidate package build:
Failed before archive creation

Reported safe file-path category:
includes/functions-utils.php

Archive:
Not created

Package contents inspection:
Not performed
```

The purpose of Step 292.1 is to classify that finding using only value-free
source structure, select the narrowest safe remediation direction, and define
the final-stage invalidation route.

Step 292.1 does not implement remediation or authorize a package-build retry.

## 2. Scope and Explicit Non-scope

In scope:

- confirm the Step 291 and Step 292 predecessor records;
- confirm frozen-candidate documentation-only continuity;
- review the canonical scanner architecture;
- identify the failing rule category without reproducing its pattern payload;
- localize the finding by file, count, line category, enclosing helper, token
  categories, and syntax category;
- classify the finding;
- compare narrow remediation routes;
- define later implementation and verification obligations;
- define release-candidate invalidation and final-stage re-entry.

Out of scope:

- production source changes;
- package-tool or scanner-rule changes;
- package-build retry;
- ZIP or archive creation;
- package contents inspection;
- package installation;
- Plugin Check;
- runtime, provider, OAuth, GA4, or OpenAI actions;
- credential or value inspection;
- public-release approval.

## 3. Initial Baseline and Predecessor Gate Result

The following read-only checks were completed before investigation:

```text
git status --short --untracked-files=all
git diff --name-only
git diff --check
git rev-parse HEAD
git log -1 --oneline
git ls-files --error-unmatch <Step 291 document>
git ls-files --error-unmatch <Step 292 document>
git merge-base --is-ancestor <frozen candidate SHA> HEAD
git diff --name-only <frozen candidate SHA>..HEAD
```

Results:

- the working tree was clean;
- Step 291 was tracked and committed;
- Step 292 was tracked and committed;
- Step 292 records failure before archive creation;
- the Step 291 frozen candidate remains an ancestor of current `HEAD`;
- the difference from the frozen candidate through current `HEAD` contains
  only the committed Step 291 and Step 292 maturation records;
- no release-affecting path is present in that difference;
- `docs/maturation/` remains excluded from the canonical package procedure.

Initial baseline classification:

```text
Clean committed scanner-investigation baseline
```

Predecessor gate result:

```text
Passed
```

## 4. Sensitive-information and Value-free Evidence Boundary

The investigation used only:

- file-path category;
- scanner architecture and rule-count categories;
- blocking rule category;
- match-count category;
- line-number category;
- enclosing helper symbol;
- token-type categories;
- syntactic construct categories;
- Git and package-procedure categories.

The investigation did not output, inspect, copy, or record:

- raw scanner matched text;
- raw source line content at the match;
- credentials, API keys, OAuth tokens, refresh tokens, client secrets, or
  passwords;
- option values or constant values;
- authorization or redirect URLs;
- callback values or authorization codes;
- Authorization headers;
- request or response bodies;
- payload JSON;
- generated report text;
- analytics values;
- database contents;
- screenshots;
- browser Network evidence.

The value-free source mapping reported classifications only. It did not print
the source expression or the scanner pattern payload.

## 5. Frozen Candidate and Package-status Context

Frozen release-candidate content baseline:

```text
721653d3be6276c2e3103a3092d765ec934ece7a
```

Current final-stage state:

```text
Phase 1 public wording / release-boundary consistency:
Completed

Phase 2 clean committed candidate baseline freeze:
Completed

Phase 3 package build:
Failed

Release-candidate package:
Not created

Phase 4 package contents inspection:
Blocked because no candidate archive exists
```

Step 292.1 is inside the excluded `docs/maturation/` path and does not change
candidate package content while the current package procedure remains
unchanged.

Any later scanner-tool or production-source remediation is release-affecting
and will require a new committed baseline before another build.

## 6. Canonical Scanner Architecture, at Safe Category Level

The canonical package procedure is owned by:

```text
tools/build-release-zip-dry-run.sh
```

Source-level scanner architecture:

- the scanner runs against the isolated staged plugin tree;
- it has four blocking high-risk rule categories;
- it has three warning-only credential-keyword categories;
- blocking matches report only a relative file path and a generic finding
  message;
- raw matched material is not printed;
- any blocking match returns failure;
- the procedure stops before archive creation when the blocking scan fails;
- archive creation and internal archive checks occur only after the blocking
  scan passes.

Value-safe reporting boundary:

```text
Existing and preserved:
file path plus generic category only
```

The failing blocking category was identified without reproducing its raw
regular expression:

```text
Client-secret assignment-like blocking rule
```

The other three blocking rule categories had no occurrence in the reported
source file.

## 7. Safe Finding-localization Method and Evidence Obtained

The finding was localized without printing source text.

Value-free result:

```text
Reported file:
includes/functions-utils.php

Match count:
1

Line-number category:
Single source line

Enclosing helper:
analytics_report_ai_resolve_google_oauth_client_configuration

Syntactic category:
Identifier assignment to a helper-call expression

Right-hand-side category:
Helper-call expression

Credential-related literal category:
Array-key label only

Raw line output:
No
```

Token-category inspection reported:

- a variable token;
- an assignment punctuation category;
- function-name/call token categories;
- a constant-encapsed string token used as a label category;
- no evidence of a runtime credential literal.

The scan matched the structural sequence formed by a credential-category
identifier, an assignment operator, and a long identifier-like helper call.
It did not establish that the assigned runtime value is present as source
literal material.

## 8. Finding Classification

Finding classification:

```text
Scanner rule too broad for a legitimate current source pattern
```

This is not classified as:

- actual credential material risk, because no value-free structural evidence
  indicates a credential value embedded in source;
- a simple structural false positive with an otherwise sufficiently
  discriminating rule, because the current assignment-like rule cannot
  distinguish a helper-call expression from a credential-like assignment
  payload;
- evidence insufficient, because token categories, syntax shape, match count,
  enclosing helper, and other-rule counts are sufficient for a value-free
  structural classification.

## 9. Classification Rationale Without Raw Matched Content

The classification is based on these safe facts:

1. Exactly one blocking occurrence was found in the reported file.
2. It is inside the OAuth client configuration resolver.
3. The occurrence is an assignment to a helper-call expression.
4. The credential-related string token is an array-key label category, not
   evidence of embedded credential material.
5. No high-risk literal category was established by token inspection.
6. The other blocking scanner rule categories did not match the file.
7. The current assignment-like rule treats a sufficiently long
   identifier-like expression after an assignment operator as if it were a
   possible credential payload.
8. A function call is therefore indistinguishable from an actual bare
   credential-like token under the current rule shape.

The result is structural and value-free. It does not claim that runtime
credential values are safe, valid, or absent from storage.

## 10. Remediation Principles and Prohibited Weak Mitigations

Any remediation must preserve:

- blocking high-risk credential scanning;
- detection of genuine credential-like literals;
- failure before archive creation;
- file-path-only reporting;
- no secret-bearing log output;
- current staging and external output isolation;
- current `.distignore` behavior.

Prohibited weak mitigations:

- disabling the high-risk scan;
- changing the finding to warning-only;
- excluding `includes/functions-utils.php`;
- excluding all PHP files;
- allowlisting the enclosing helper or complete file;
- accepting all assignments to credential-related identifiers;
- suppressing the result without structural proof;
- changing `.distignore` to remove production source from the package;
- manually constructing a package;
- changing production formatting only to evade the current rule without
  fixing the scanner distinction.

## 11. Candidate Remediation Options and Likely Change Owner

### Option 1: Narrow Scanner-rule Refinement

Preferred option.

Concept:

- retain the existing client-secret assignment risk category;
- distinguish quoted literal payloads from executable expressions;
- distinguish unquoted credential-like token payloads from helper/function
  calls by requiring a safe payload terminator category rather than accepting
  an opening call delimiter;
- keep the other blocking rules unchanged;
- keep output limited to file path and generic category.

Expected effect:

- the legitimate helper-call assignment no longer blocks packaging;
- quote-delimited credential-like assignment material remains blocking;
- bare token-like credential assignment material remains blocking;
- helper-call expressions are not treated as embedded credential values.

Likely change owner:

```text
Canonical package tool / scanner rule
```

### Option 2: Production-source Form Refactor

Not preferred.

Concept:

- alter the legitimate assignment's formatting or local identifier structure
  so the existing scanner does not match.

Reason not selected:

- it changes production source solely to accommodate an overbroad scanner;
- it leaves the scanner unable to distinguish the same structural issue
  elsewhere;
- it creates unnecessary runtime-source review and invalidation scope;
- formatting-only evasion is less durable than correcting the scanner rule.

### Option 3: Both Source and Scanner Changes

Not currently justified.

The value-free evidence identifies one tool-level discrimination problem. No
production behavior defect or embedded source credential was identified.

Narrow remediation planning:

```text
Ready for separate implementation step
```

## 12. Frozen-candidate / Final-stage Invalidation Routing Matrix

| Later action | Release-affecting | Earliest required routing | Phase 1 wording recheck | Candidate/package consequence |
| --- | --- | --- | --- | --- |
| Step 292.1 documentation-only commit | No, while `docs/maturation/` exclusion remains unchanged | Remains in investigation record | Not required | Frozen package content remains unchanged; build still failed. |
| Canonical scanner-rule refinement | Yes | Reconfirm package procedure/exclusion and establish a new clean committed candidate baseline before build | Not required unless public wording or source/documentation boundary is also changed | Step 291 frozen baseline is superseded for future package evidence; rerun package build from the new baseline. |
| `.distignore` or package-exclusion change | Yes | Return to package-boundary review and new baseline freeze | Only if public/documentation alignment is affected | Reconfirm Step records exclusion and all downstream artifact gates. |
| Production source change only | Yes | Determine affected source/documentation gate, then establish a new clean committed candidate baseline | Required when public wording, release boundary, or source/documentation alignment is affected; otherwise route to the earliest affected source gate | Old failed build is not successful package evidence; rebuild and repeat downstream gates. |
| Public wording change | Yes | Return to Phase 1 wording/release-boundary consistency, then new baseline freeze | Required | Rebuild and repeat package, inspection, install, and Plugin Check gates. |
| No remediation implementation authorized | No new release content | Remain blocked after Step 292 failure | Not applicable | Package build remains failed; Step 293 remains blocked. |

No successful package evidence may be inferred from Step 292. Its failure
record remains historical evidence for the old build attempt only.

## 13. Required Later Implementation-verification Obligations

A later narrow implementation step must:

1. Modify only the canonical package scanner and its result documentation,
   unless new evidence justifies broader scope.
2. Keep all four blocking risk categories active.
3. Keep the other three blocking categories semantically unchanged.
4. Preserve failure before archive creation.
5. Preserve path-only/generic-category output.
6. Demonstrate, without printing matched material, that:
   - the legitimate helper-call assignment category is not flagged;
   - a quoted credential-like literal assignment category remains flagged;
   - a bare token-like credential assignment category remains flagged;
   - an opening function-call delimiter is not accepted as a credential-value
     terminator;
   - other blocking rule categories remain active.
7. Run shell syntax validation on the build script.
8. Run `git diff --check`.
9. Record the exact changed-file scope.
10. Reconfirm `.distignore`, documentation exclusion, external output, and
    symlink safeguards.
11. Avoid a package-build retry within the remediation implementation unless a
    separate later gate explicitly authorizes it.
12. Commit the remediation and establish a new clean committed candidate
    baseline before retrying package construction.

Synthetic verification, if used, must remain outside the repository, contain
no real credential material, print result categories only, and be removed by
its owning verification procedure.

## 14. Package-build Retry Boundary

Release-candidate package build retry:

```text
Not authorized
```

A retry is permitted only after:

1. the narrow scanner remediation is implemented;
2. the remediation is verified with value-free positive and negative
   structural cases;
3. the remediation is committed;
4. package-exclusion and safe output boundaries are reconfirmed;
5. a new clean committed release-candidate content baseline is established;
6. a later package-build gate explicitly authorizes one canonical build
   attempt.

No alternate/manual build procedure may be used.

## 15. Final Artifact Gates Still Not Performed

```text
Release-candidate package:
Not created

Package contents inspection:
Blocked because no candidate archive exists

Isolated package install validation:
Not performed

Final isolated Plugin Check:
Not performed

Final WordPress.org release decision:
Not performed
```

## 16. Explicitly Non-executed Actions

Step 292.1 did not perform:

- production PHP, JavaScript, or CSS modification;
- `readme.txt` modification;
- Settings wording modification;
- version, Stable tag, or version-constant modification;
- `.distignore` modification;
- package-tool or scanner-rule modification;
- package-build retry;
- ZIP or archive creation;
- manual or alternate package construction;
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
- raw scanner-match output;
- raw matched source-line output;
- credential, token, option, constant, or OAuth client value inspection;
- commit;
- push.

## 17. Step 292.1 Conclusion

Canonical package credential-pattern scan finding investigation:

```text
Completed
```

Finding classification:

```text
Scanner rule too broad for a legitimate current source pattern
```

Value-free evidence:

```text
One identifier-assignment-to-helper-call occurrence inside the OAuth client
configuration resolver; no runtime credential literal evidence established
```

Narrow remediation planning:

```text
Ready for separate implementation step
```

Likely change owner:

```text
Canonical package tool / scanner rule
```

Frozen-candidate consequence if remediation is implemented:

```text
Release-affecting package-procedure change; reconfirm package boundaries and
establish a new clean committed candidate baseline before another build
```

Release-candidate package build retry:

```text
Not authorized until remediation is implemented, committed, and all affected
final-stage gates are rerun against the appropriate new baseline
```

## 18. Release Readiness Statement

```text
WordPress.org public release readiness:
Hold
```

The Hold remains because:

- the canonical package build failed;
- no candidate archive exists;
- scanner remediation has not been implemented;
- package contents inspection remains blocked;
- isolated package install validation has not been performed;
- final isolated Plugin Check has not been performed;
- no final WordPress.org release decision has been completed.

## 19. Recommended Next Gate

Recommended next step:

```text
Step 292.2: Canonical package credential-pattern scan narrow remediation
implementation
```

Step 292.2 should refine only the assignment-like blocking rule, preserve all
other scanner protections and value-safe output, perform value-free
structural verification, and defer the next package-build attempt until a new
clean committed candidate baseline has been established.
