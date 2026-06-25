# Step 295.1: Plugin Check JSON Aggregate Evidence Format Investigation Plan

## 1. Step Purpose and Relation to Step 295 Evidence-unavailable Result

Step 295.1 investigates the installed Plugin Check command interface and local
implementation without rerunning Plugin Check or recovering the deleted Step
295 report.

Step 295 established:

```text
Plugin Check command attempts:
One

Plugin Check command exit status:
Success

Safe aggregate JSON evidence:
Unavailable

JSON report parse:
Fail

Errors / Warnings / Notices:
Unavailable
```

This step determines whether a later repository-external parser can safely
derive aggregate counts from a future controlled command invocation.

## 2. Scope and Explicit Non-scope

In scope:

- confirm the committed Step 291R through Step 295 predecessor chain;
- confirm frozen-candidate continuity and retained-target preservation;
- inspect the Plugin Check command help interface without running a check;
- inspect the installed Plugin Check implementation statically;
- classify JSON modes, record shape, severity representation, and output
  channel behavior;
- classify the Step 295 parser failure at source/interface level;
- define a fail-closed parser design and synthetic verification plan;
- determine whether Step 295.2 and a later Plugin Check rerun can be
  authorized.

Explicitly outside scope:

- Plugin Check execution or rerun;
- recovery or inspection of the deleted Step 295 report;
- parser implementation;
- synthetic fixture creation or execution;
- package build, archive inspection, package installation, or plugin
  lifecycle changes;
- production, public-wording, uninstall, package-tool, or metadata changes;
- browser, provider, OAuth, GA4, or OpenAI actions;
- final public-release approval.

## 3. Initial Baseline and Predecessor Gate Result

The initial repository working tree was clean.

The Step 291R, Step 292R, Step 293, Step 294, and Step 295 records were tracked
and committed.

Current committed `HEAD` before this document:

```text
a54eaef187303f1e57f421af0ae90f594b12fca7
```

Commit subject:

```text
Record final Plugin Check evidence boundary
```

The frozen candidate remains an ancestor of current `HEAD`. The post-baseline
Git difference contains only the committed Step 291R through Step 295
maturation records. No release-affecting source, public wording, uninstall,
`.distignore`, package-tool, package-procedure, version, Stable tag,
version-constant, asset, shipped runtime configuration, or package metadata
change was present.

Initial baseline classification:

```text
Clean committed Plugin Check aggregate-format investigation baseline
```

Predecessor gate:

```text
Passed
```

## 4. Historical Step 295 Report Non-recovery Boundary

The Step 295 historical facts are preserved as fixed evidence:

- Plugin Check ran exactly once;
- command exit status was `Success`;
- safe aggregate JSON evidence was unavailable;
- the temporary report was removed;
- raw output was not displayed, inspected, or recorded.

This investigation did not attempt to locate, recover, reconstruct, or reuse
that report. It did not inspect shell history, temporary storage, process
logs, system logs, terminal history, cache, or swap.

```text
Historical Step 295 raw report:
Not recovered, not inspected, and not reused
```

The Step 295 command exit status is not treated as proof of zero findings.

## 5. Sensitive-information and Output-safety Boundary

Evidence was limited to:

- command-interface availability categories;
- installed implementation owner and version categories;
- declared output-format categories;
- JSON shape, collection, severity, and channel categories;
- parser fail-closed requirements;
- Git and retained-target categories.

Command help output was captured outside the repository, reduced to safe
interface categories, and deleted. Static implementation review produced only
category-level conclusions.

This step did not display, quote, inspect, or record:

- raw Plugin Check output or a raw JSON body;
- issue objects, issue messages, source paths, line numbers, snippets, or
  scanner patterns;
- arbitrary output field values or implementation excerpts;
- credentials, tokens, OAuth client values, option values, or constant values;
- URLs, callback values, request or response material;
- payloads, analytics data, or generated report text;
- screenshots, browser Network evidence, or database content.

## 6. Retained Target Preservation Confirmation

The retained candidate target was confirmed read-only as:

| Check | Result |
|---|---|
| Package target installed | Pass |
| Package target active | Pass |
| Direct directory and non-symlink | Pass |
| Resolved inside the `wp-dev-check` plugin directory | Pass |
| Resolved outside the source repository | Pass |
| Expected Version category `0.1.0` | Pass |

Retained target category:

```text
Package-installed, active, non-symlink, and source-isolated in wp-dev-check
```

No target mutation was performed.

## 7. Plugin Check Command-interface and Implementation Discovery Method

The investigation used only:

- the non-mutating Plugin Check help interface;
- safe installed-plugin metadata categories;
- read-only local implementation discovery;
- static source-level control-flow and formatter-branch classification;
- safe Git and filesystem checks.

Results:

| Discovery item | Result |
|---|---|
| Plugin Check command available | Yes |
| `json` output format declared | Yes |
| `strict-json` output format declared | Yes |
| Implementation owner category | Installed `plugin-check` plugin |
| Installed Plugin Check version category | `2.0.0` |
| Installed WP-CLI version category | `2.12.0` |
| Local command and result-export implementation available | Yes |

No Plugin Check command against the candidate was run during this
investigation.

## 8. Safe JSON Output-shape / Channel-behavior Findings

Static interface and implementation findings:

| Category | Finding |
|---|---|
| Normal `json` output | File-grouped presentation; not one strict JSON document |
| `strict-json` output when the formatter is reached | One top-level result collection |
| Result collection | Flattened finding records |
| Finding severity representation | Record `type` category |
| Supported finding type categories | Error and warning |
| Notice finding category | Not declared by the inspected command result path |
| Clean-result behavior | Formatter is bypassed by an earlier human-readable success response |
| Machine-report channel | Formatter and presentation output use stdout |
| Diagnostic channel | Complete stderr behavior is not guaranteed solely by the inspected plugin source |

The source-level contract therefore distinguishes:

1. a finding-bearing `strict-json` collection that can be structurally
   traversed; and
2. a clean-result branch that does not emit the same strict JSON collection.

Separating stdout and stderr remains necessary, but channel separation alone
does not solve the clean-result strict-JSON gap.

## 9. Prior Parser Failure Classification

The supported classification is:

```text
3. The current Plugin Check JSON mode does not provide a safely countable JSON
report structure under the currently installed implementation.
```

More specifically:

- the Step 295 `json` mode was not a single strict JSON document;
- the implementation has a separate `strict-json` mode for finding-bearing
  output;
- a clean result exits before the strict formatter branch and therefore does
  not provide a strict empty JSON collection.

The Step 295 parser failure is not classified from exit status alone.

## 10. Safe Aggregate-parser Design Contract

A later parser must remain repository-external and emit only:

```text
JSON_REPORT_PARSE:
Pass / Fail

JSON_SCHEMA_CLASS:
Safe category only

PLUGIN_CHECK_EXIT_STATUS:
Success / Non-zero

ERRORS:
Integer count only / Unavailable

WARNINGS:
Integer count only / Unavailable

NOTICES:
Integer count only / Unavailable

STDERR_CATEGORY:
Empty / Present / Unavailable
```

Required design rules:

- capture stdout and stderr into separate temporary files;
- never print either channel;
- use stdout only as report input;
- use strict JSON decoding, not regular-expression scraping;
- accept only a top-level result collection for the finding-bearing
  `strict-json` schema;
- require every record to match the approved structural schema;
- count only approved error and warning `type` categories;
- do not infer arbitrary fields or recursively count similarly named keys;
- represent notices as zero only after a separate contract confirms that the
  selected command path cannot emit a notice finding category;
- record command exit status independently from report counts;
- fail closed on malformed JSON, unknown type, missing required structure,
  schema drift, or ambiguous count derivation;
- emit no issue content, paths, lines, snippets, or arbitrary values;
- create no repository artifact.

### Output-channel Contract

The future controlled execution must use:

```text
stdout:
Candidate machine-readable Plugin Check report only

stderr:
Captured separately and reduced only to Empty / Present / Unavailable

parser input:
stdout file only

raw channel display:
Prohibited
```

### Unresolved Clean-result Contract

The installed implementation does not currently provide a strict empty JSON
collection through the inspected clean-result branch.

The parser must not:

- treat a success exit status as zero findings;
- scrape or compare the human-readable success text;
- silently normalize non-JSON output to an empty collection;
- classify an empty or non-JSON stdout stream as JSON parse `Pass`.

Consequently, a complete release-gate parser contract that can establish
strict JSON parse `Pass` plus `0 / 0 / 0` is not yet established.

Safe aggregate parser contract:

```text
Partially specified for finding-bearing strict JSON output
Not established for the clean zero-finding release-gate case
```

## 11. Synthetic Verification Plan

No fixture was created or executed in Step 295.1.

A future repository-external synthetic verification plan must cover:

| Synthetic case | Expected aggregate-only result |
|---|---|
| Clean strict JSON collection | Parse Pass; Errors 0; Warnings 0; Notices 0 |
| Error-only collection | Non-zero Errors only |
| Warning-only collection | Non-zero Warnings only |
| Notice-only representation | Rejected unless a notice contract is separately approved |
| Mixed error/warning collection | Independent aggregate counts |
| Unknown type or schema drift | Fail closed; counts Unavailable |
| Malformed or non-JSON stdout | Parse Fail; counts Unavailable |
| Stderr present | Stderr categorized without contaminating stdout parsing |
| Raw-output emission check | Fixed aggregate fields only |

All synthetic fixtures must:

- use fabricated non-working markers only;
- exist in a new temporary directory outside the repository;
- contain no real provider, credential, analytics, customer, source, or Plugin
  Check finding data;
- never be printed or recorded;
- be removed by the owning procedure.

This synthetic plan cannot be executed until the clean-result machine-readable
contract is clarified.

## 12. Candidate Continuity and Future Rerun Sequence

Step 295.1 changes documentation only and remains package-excluded under the
current `docs/maturation/` exclusion.

The frozen candidate, package identity, package inspection, isolated install
validation, and retained target remain unchanged.

A future rerun sequence must not begin until a separate clarification step
establishes a value-safe clean-result contract.

After that clarification:

1. implement a temporary parser outside the repository;
2. verify it only with fabricated repository-external fixtures;
3. commit documentation only;
4. reconfirm frozen-candidate continuity and the retained target;
5. run Plugin Check exactly once in `wp-dev-check`;
6. capture stdout and stderr separately;
7. parse stdout only;
8. require command success, strict parse Pass, and zero approved aggregate
   counts before proceeding.

Plugin Check rerun:

```text
Not authorized by Step 295.1
```

## 13. Final Release-decision Dependency

Step 296 must not begin unless a later controlled Plugin Check gate establishes:

```text
Command exit status:
Success

Strict JSON report parse:
Pass

Errors:
0

Warnings:
0

Notices:
0
```

Step 295 command success alone does not satisfy this dependency.

## 14. Final Artifact Gates Still Not Completed

```text
Safe aggregate parser implementation:
Not performed

Synthetic parser verification:
Not performed

Final isolated Plugin Check aggregate-evidence rerun:
Not performed

Final WordPress.org release decision:
Not performed
```

## 15. Explicitly Non-executed Actions

This step did not perform:

- Plugin Check or a Plugin Check rerun;
- Plugin Check in the normal `wp-dev` environment;
- Step 295 report recovery or raw-output inspection;
- parser implementation;
- synthetic fixture creation or execution;
- package build, archive inspection, package installation, or plugin
  lifecycle changes;
- production PHP, JavaScript, CSS, asset, `readme.txt`, Settings, version,
  Stable tag, `.distignore`, package-tool, or scanner changes;
- browser or admin UI access;
- Settings save;
- OAuth redirect, callback, provider interaction, token endpoint
  communication, refresh, provider-side revoke, or local disconnect;
- GA4 Fetch or OpenAI Generate;
- option or credential inspection, raw SQL, or database dump;
- screenshots or browser Network inspection;
- commit or push.

## 16. Step 295.1 Conclusion

```text
Plugin Check JSON aggregate evidence format investigation:
Completed but blocked

Historical Step 295 raw report:
Not recovered, not inspected, and not reused

Finding-bearing strict JSON structural contract:
Identified

Clean zero-finding strict JSON contract:
Not established

Safe aggregate parser contract:
Not established for the required release-gate case

Plugin Check rerun:
Not authorized

Final WordPress.org release decision:
Not performed
```

## 17. Release Readiness Statement

```text
WordPress.org public release readiness:
Hold
```

The release gate remains blocked until a value-safe machine-readable
clean-result contract and verified aggregate parser are established.

## 18. Recommended Next Gate

```text
Step 295.2: Plugin Check clean-result machine-readable evidence-boundary
clarification plan
```

The next step should remain docs-only / source-level and determine whether an
existing command option or supported interface can emit a strict empty JSON
collection for a clean result without modifying the candidate, Plugin Check,
or package procedure. It must not rerun Plugin Check.
