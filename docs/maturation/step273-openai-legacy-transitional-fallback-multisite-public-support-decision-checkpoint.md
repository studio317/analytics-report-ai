# Step 273: OpenAI Legacy / Transitional Fallback Multisite Public-support Decision Checkpoint

## Step Objective and Decision Limits

Step 273 is a docs-only / decision-only checkpoint that defines the WordPress
multisite and network lifecycle public-support boundary for the initial public
release posture.

The decision is based on the source-level boundaries recorded in Steps
270-272. It does not implement multisite support, execute runtime verification,
change public documentation, or authorize release.

WordPress.org public release readiness remains:

```text
Hold
```

## Working-tree Baseline Classification

The following commands were run before this Step 273 document was added:

```text
git status --short --untracked-files=all
git diff --name-only
git diff --check
```

All three commands returned no output.

Baseline classification:

```text
Clean working tree
```

Step 272 was present in the baseline and no uncommitted production-facing or
documentation changes were present.

## Decision Inputs and Evidence Boundary

Primary decision inputs:

- `docs/maturation/step270-openai-legacy-transitional-fallback-storage-migration-and-uninstall-release-boundary-decision-checkpoint.md`
- `docs/maturation/step271-openai-legacy-transitional-fallback-multisite-and-uninstall-source-level-release-boundary-plan.md`
- `docs/maturation/step272-openai-legacy-transitional-fallback-multisite-and-uninstall-source-level-release-boundary-results.md`

Step 272 established the following source-level boundary:

- activation has a WordPress activation hook and callback, but no
  source-confirmed network-wide initialization, site iteration, or explicit
  network rejection branch;
- Settings and OAuth token storage use the site-level option API family;
- no network option API or explicit multisite storage branch was confirmed;
- OpenAI resolution remains constant first, then legacy / transitional
  Settings fallback, then missing;
- constant deployment scope across a multisite network cannot be determined
  from plugin source alone;
- fallback clear behavior is limited to the current Settings fallback slot;
- no cross-site fallback cleanup or per-site iteration was confirmed;
- the current uninstall source provides deterministic site-level option
  cleanup, but no site iteration or network-option cleanup;
- no topic was classified as source-confirmed multisite/network behavior;
- actual multisite and network lifecycle behavior was not executed.

Permitted evidence for Step 273 is limited to docs-level references,
source-level category conclusions, support-policy classifications, file-level
change summaries, and command-result categories.

No credential, API key, OAuth token, option value, constant value, serialized
data, Authorization header, request/response body, payload JSON, generated
report text, screenshot, Network evidence, database content, or analytics
value was inspected or recorded.

## Considered Public-support Options

### Option A: Exclude Multisite from Initial Public Support

The initial public release explicitly treats WordPress multisite, network
activation, network deactivation, network uninstall, and cross-site lifecycle
behavior as outside the supported scope.

Advantages:

- matches the currently source-confirmed single-site implementation boundary;
- avoids presenting unverified network lifecycle behavior as supported;
- allows multisite design and controlled verification to remain a separate
  future track;
- does not require an immediate storage or lifecycle redesign.

Limit:

- does not establish whether a particular multisite installation works,
  fails, or behaves in any specific way.

### Option B: Delay the Support Decision

The project could leave the support boundary undecided until a controlled
multisite runtime verification track is completed.

Advantages:

- could produce runtime evidence before public wording is selected.

Limit:

- leaves the initial public-support boundary ambiguous;
- requires environment, lifecycle, evidence, and cleanup planning before a
  support statement can be made.

Disposition:

```text
Future option; not adopted or started in Step 273
```

### Option C: Begin Multisite-aware Implementation

The project could begin multisite-aware storage, activation, deactivation,
uninstall, and controlled verification work before deciding public support.

Advantages:

- could establish an intentional multisite architecture.

Limit:

- expands implementation and cleanup scope substantially;
- requires product, storage ownership, lifecycle, isolation, and testing
  decisions that are outside this decision-only step.

Disposition:

```text
Future option; not adopted or started in Step 273
```

### Adopted Option

```text
Option A
```

Reason:

```text
The repository currently provides source-confirmed single-site boundaries,
but does not provide source-confirmed multisite/network lifecycle behavior
or controlled runtime verification.
```

## Adopted Initial Public-release Policy

Initial public-release multisite policy:

```text
WordPress multisite, network activation, network deactivation,
network lifecycle, network uninstall, and cross-site storage and cleanup
behavior are outside the supported scope for the initial public release.

The currently documented implementation and support boundary is single-site
only.
```

This is a support-boundary and release-policy decision. It is not a statement
that every multisite installation fails, cannot install the plugin, or is
technically impossible.

The policy means:

- initial public-release materials must not describe multisite or network
  lifecycle behavior as verified, supported, safe, or complete;
- network activation, network deactivation, network uninstall, cross-site
  Settings behavior, cross-site OAuth token behavior, and cross-site legacy
  fallback cleanup are excluded from the initial supported scope;
- source-confirmed single-site boundaries may be documented without being
  presented as full public-release validation;
- future multisite support requires a separate design, implementation, and
  controlled verification track;
- this decision does not authorize release or resolve other release gates.

## Scope Included in the Current Documented Boundary

The following value-free source-level behaviors remain inside the current
documented single-site boundary:

- constant-first OpenAI source ordering;
- existing OpenAI Settings fallback as legacy / transitional compatibility
  only;
- current Settings fallback slot-limited clear behavior;
- current site-level plugin Settings option path;
- current site-level OAuth token option path;
- deterministic current site-level uninstall cleanup;
- no provider-side request in the reviewed uninstall source.

These boundaries are not evidence that public release is fully validated or
that every deployment mode is supported.

## Scope Explicitly Outside Initial Public Support

The initial public-support boundary excludes:

- actual WordPress multisite setup behavior;
- per-site activation behavior in a multisite deployment;
- network activation;
- network deactivation;
- network lifecycle behavior;
- network-level Settings storage;
- network-level OAuth token storage;
- constant deployment scope across a multisite network;
- cross-site Settings behavior;
- cross-site legacy fallback behavior;
- cross-site fallback clear behavior;
- cross-site OAuth token behavior;
- per-site iteration;
- network uninstall;
- network-option cleanup;
- cross-site uninstall cleanup;
- provider behavior during runtime multisite operation.

These areas remain future support and release gates. Step 273 does not classify
their runtime behavior.

## Non-claims and Prohibited Wording

This decision must not be represented with statements such as:

- "Multisite is broken."
- "Multisite cannot work."
- "Network activation is rejected."
- "Network uninstall is safe."
- "All multisite behavior is unsupported because WordPress core does X."
- "The plugin is fully single-site safe."
- "The public release is ready."
- "Multisite support is complete."

Step 273 also does not claim:

- technical incompatibility with every multisite installation;
- automatic prevention of network activation;
- runtime failure or success on multisite;
- complete cleanup across a network;
- WordPress core compensation for missing plugin lifecycle handling;
- provider-side cleanup or revocation;
- WordPress.org policy compliance or release approval.

Behavior that was not executed is not classified as Pass, safe, supported, or
complete.

## Future Multisite Support Entry Criteria

If a multisite support track is opened later, entry criteria should include:

1. an explicit product and public-support scope decision;
2. a multisite-aware storage and lifecycle design review;
3. activation, deactivation, and uninstall lifecycle design;
4. a site-level versus network-level option ownership decision;
5. a cross-site cleanup and isolation policy;
6. a constant deployment-scope documentation boundary;
7. a controlled multisite test environment isolated from normal functional
   QA;
8. a non-secret, value-free controlled runtime verification plan;
9. a network activation, deactivation, and uninstall verification matrix;
10. per-site Settings, OAuth token, and legacy fallback behavior scenarios;
11. failure and rollback boundaries that do not expose stored values;
12. post-verification documentation and release review.

These criteria are future gates only. Step 273 does not start implementation,
environment setup, or runtime verification.

## Documentation Disposition

Step 273 remains an internal maturation decision checkpoint.

- `readme.txt` is not changed in this step.
- adoption and exact wording of a public-facing multisite support statement
  remain a future documentation gate.
- no installation or network activation tutorial is added.
- no credential configuration instruction is added.
- no secret-bearing example is added.
- no Settings, Report Builder, support, or privacy wording is changed.

## Public Release Implication

Step 273 narrows and clarifies the initial public-support policy. It does not
complete the release-readiness process.

```text
WordPress.org public release readiness: Hold
```

Step 273 does not replace or resolve other existing release gates, including:

- OAuth lifecycle work that remains deferred or held in its existing track;
- OpenAI storage posture and legacy fallback release boundaries;
- uninstall and multisite future scope;
- privacy and public documentation finalization;
- final packaging, Plugin Check, and release validation.

No existing unresolved item is reclassified by this checkpoint except that the
initial multisite public-support boundary is now explicitly selected as
outside supported scope.

## Explicit Non-goals and Execution Boundaries

Step 273 did not:

- modify production source;
- modify existing docs or `readme.txt`;
- modify Settings or Report Builder UI;
- modify `uninstall.php` or tools;
- modify `wp-dev` or `wp-dev-check`;
- configure multisite;
- activate, deactivate, or uninstall the plugin;
- perform network activation, deactivation, or uninstall;
- run browser admin smoke;
- save Settings or remove a fallback;
- run a WP-CLI mutation;
- run `wp option get` or `wp site list`;
- run SQL or a database dump;
- inspect option, constant, token, or credential values;
- run OpenAI Generate, GA4 Fetch, or OAuth;
- make an external HTTP or provider request;
- run Plugin Check;
- create a test fixture or mu-plugin;
- collect screenshots or browser Network evidence.

## Result Classification

```text
Initial public-release multisite support-boundary decision completed
Adopted option: Option A
Current documented implementation/support boundary: single-site only
Multisite/network lifecycle initial public support: Outside supported scope
WordPress.org public release readiness: Hold
```

## Recommended Next Step

```text
Step 274 candidate — OpenAI legacy/transitional fallback release-boundary maturation checkpoint
```

Step 274 should remain docs-only / planning-only. It should consolidate the
constant-based public route, legacy / transitional fallback compatibility,
storage and cleanup boundaries, single-site uninstall boundary, and the Step
273 multisite support policy. It must not treat that consolidation as public
release approval or change the existing Hold status.
