# Step 280: WordPress.org Hold-gate Prioritization Checkpoint

## Step Objective and Decision Limits

Step 280 is a docs-only / decision-only checkpoint for the remaining
WordPress.org public-release `Hold` gates.

Its purpose is to:

- inventory the current documented Hold gates by topic;
- distinguish completed or matured tracks from unresolved release gates;
- distinguish current work candidates from future-trigger-only and final-stage
  gates;
- select exactly one next active workstream;
- define the minimum scope and evidence boundary for the next step.

This checkpoint does not implement, execute, or verify runtime behavior. It
does not approve release or convert documentation, policy, source-level, or
controlled human evidence into provider, security, legal, policy, runtime, or
release-validation evidence.

```text
WordPress.org public release readiness:
Hold
```

## Working-tree Baseline Classification

The following commands were run before this document was added:

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

The committed baseline included Step 279. No pre-existing production,
public-documentation, maturation-document, tool, or environment changes were
present.

## Evidence Sources and Evidence-level Boundary

Primary current evidence:

- `docs/maturation/step198-oauth-client-configuration-hybrid-source-final-maturation-checkpoint.md`;
- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`;
- `docs/maturation/step215-uninstall-cleanup-maturation-checkpoint.md`;
- `docs/maturation/step223-manual-google-access-token-fallback-retirement-maturation-checkpoint.md`;
- `docs/maturation/step227-readme-privacy-wording-alignment-after-manual-token-retirement-maturation-checkpoint.md`;
- `docs/maturation/step274-openai-legacy-transitional-fallback-release-boundary-maturation-checkpoint.md`;
- `docs/maturation/step276-openai-storage-privacy-and-public-documentation-final-release-boundary-review-results.md`;
- `docs/maturation/step277-openai-storage-privacy-and-public-documentation-multisite-support-boundary-narrow-wording-correction-plan.md`;
- `docs/maturation/step278-initial-single-site-support-boundary-narrow-public-wording-implementation-results.md`;
- `docs/maturation/step279-initial-single-site-support-boundary-post-implementation-source-documentation-wording-verification-results.md`;
- `docs/maturation/step108-isolated-plugin-check-rerun-clean-package-results.md`;
- `docs/maturation/step199-public-release-remaining-blocker-reprioritization-checkpoint.md`;
- `docs/maturation/step272-openai-legacy-transitional-fallback-multisite-and-uninstall-source-level-release-boundary-results.md`;
- `docs/maturation/step273-openai-legacy-transitional-fallback-multisite-public-support-decision-checkpoint.md`.

Historical records were used only where later records did not supersede them.
In particular:

- the OAuth client configuration source track is superseded by the Step 198
  maturation checkpoint;
- the narrow OAuth lifecycle status/UI and local disconnect track is
  superseded by Step 208;
- the uninstall option-cleanup track is superseded by Step 215;
- the manual Google Access Token fallback retirement track is superseded by
  Steps 223 and 227;
- the OpenAI fallback and public-wording tracks are superseded by Steps
  274-279;
- the Step 108 clean-package Plugin Check result remains historical package
  evidence and is not treated as a final release-candidate result after later
  source and readme changes.

Evidence levels remain separate:

- policy decisions define intended boundaries;
- source-level confirmation describes reviewed control flow;
- controlled human admin smoke records bounded visible observations;
- documentation / wording verification evaluates public wording alignment;
- isolated package / Plugin Check results apply to the inspected package at
  that point in history;
- provider/runtime behavior requires separate evidence and is not inferred.

No credential, API key, OAuth token, option value, constant value,
Authorization header, request or response body, payload JSON, generated report
text, actual analytics value, screenshot, browser Network evidence, database
content, or provider response was inspected or recorded.

## Current Hold-gate Inventory

| Hold-gate topic | Current documented position | Latest evidence level | Why it remains Hold or deferred | Dependency / sequencing status | Current routing classification |
| --- | --- | --- | --- | --- | --- |
| OAuth lifecycle status/category UI and local-only disconnect | Lifecycle labels, reconnect-required wording, refresh-deferred wording, provider-revoke-deferred wording, and local-only disconnect are matured within the current MVP boundary. | Source-level confirmation; Controlled human admin smoke | The matured boundary covers UI/status and local deletion only. It does not prove refresh, provider-side revoke, or complete provider lifecycle behavior. | Preserve as established evidence while the remaining provider lifecycle boundary is decided. | Completed / matured unless reopened by trigger |
| OAuth client configuration hybrid source | Constants-preferred plus Settings fallback source strategy, value-hidden posture, conflict handling, and delete semantics are matured for the current MVP scope. | Source-level confirmation; Controlled human admin smoke | Full OAuth provider/runtime readiness was outside this source-configuration track. | Not an active source-strategy task. It is an input to the remaining lifecycle decision. | Completed / matured unless reopened by trigger |
| Manual Google Access Token fallback | The normal public/admin path was retired; OAuth-first wording and related public wording were aligned. | Source-level confirmation; Controlled human admin smoke; Documentation / wording verification | No remaining immediate fallback-retirement work is documented. A relevant source or support-policy change could reopen it. | Do not reopen while prioritizing provider lifecycle work. | Completed / matured unless reopened by trigger |
| OAuth refresh execution | Refresh remains intentionally unimplemented and described as deferred. | Policy decision; Source-level confirmation; Deferred / separate release gate | Refresh would introduce token endpoint communication, response handling, failure/retry policy, storage updates, and controlled provider QA. | Must be dispositioned before a final OAuth public-release boundary can be claimed. | Candidate for next active workstream |
| OAuth provider-side revoke | Local disconnect is explicitly not provider revoke; provider-side revoke remains unimplemented and deferred. | Policy decision; Source-level confirmation; Deferred / separate release gate | Revoke would introduce provider communication, response classification, support wording, and external verification. | Must be considered with refresh and reconnect policy; it is not part of uninstall cleanup. | Candidate for next active workstream |
| OAuth provider/runtime and full-flow evidence | Redirect, callback, source configuration, token storage, and status boundaries have separate records, but current maturity checkpoints do not promote them to complete provider lifecycle readiness. | Source-level confirmation; Controlled human admin smoke; Deferred / separate release gate | Source/docs evidence cannot establish provider authorization, refresh/revoke success, or complete production OAuth behavior. | Requires an explicit release-boundary decision before choosing source review, narrow planning, local-only verification, or a later provider/runtime gate. | Candidate for next active workstream |
| Uninstall cleanup | Guarded root `uninstall.php` and deterministic deletion of the two plugin-owned site-level options are matured within the current MVP boundary. Runtime transients, multisite cleanup, and provider revoke remain separate. | Source-level confirmation | The narrow option-cleanup mechanism is complete for its selected boundary, but it is not network cleanup or provider cleanup. | Preserve; reopen only if storage ownership, transient policy, or multisite support changes. | Completed / matured unless reopened by trigger |
| OpenAI legacy / transitional Settings fallback | Constant-based configuration is preferred; fallback is compatibility-only, cannot be normally created or replaced, and has an explicit slot-limited clear boundary. | Policy decision; Source-level confirmation; Controlled human admin smoke | The bounded track is matured. It does not certify storage security or provider success. | No current implementation work without a source, storage-model, support, wording, or release-review trigger. | Completed / matured unless reopened by trigger |
| OpenAI storage / privacy / public documentation | Current wording and source boundaries were reviewed; the identified single-site support-boundary omission was planned, implemented, and verified in Steps 277-279. | Documentation / wording verification | Documentation review cannot determine legal/privacy-law adequacy, independent storage acceptance, provider behavior, or WordPress.org policy compliance. | Current wording work is complete. Formal privacy/legal/policy review remains separate where required. | Needs separate privacy / legal / policy review |
| Initial single-site support boundary | Initial public support is documented as single-site; multisite, network lifecycle, network uninstall, and cross-site cleanup are outside initial support. | Policy decision; Source-level confirmation; Documentation / wording verification | This policy does not establish multisite runtime support or incompatibility. | No multisite implementation is required for the selected initial support scope. Reopen only after an explicit support decision. | Completed / matured unless reopened by trigger |
| Multisite / network implementation and runtime verification | Not part of the initial supported scope. No complete network lifecycle or cleanup behavior is claimed. | Policy decision; Deferred / separate release gate; Future-trigger-only | Starting implementation would require a new product/support decision plus storage, lifecycle, cleanup, and controlled runtime design. | Not a prerequisite for the current single-site support posture. | Deferred pending explicit product decision |
| Clean package / isolated Plugin Check | Step 108 passed against the then-current clean package with no observed findings. | Isolated package / Plugin Check result | Many release-affecting source and readme changes occurred later, so the result is useful historical evidence but not a final release-candidate check. | Rerun only after upstream Hold decisions and release-affecting changes are complete. | Final-stage gate |
| Final package contents and install validation | Packaging exclusions and the clean-package process have prior evidence, but final package correctness has not been established for the future release candidate. | Final-stage release gate | A final candidate must be rebuilt and inspected after upstream work is closed. | Depends on active Hold-gate resolution and a stable release candidate. | Final-stage gate |
| Final release validation and WordPress.org decision | No current record authorizes submission or establishes policy, legal, provider, security, or complete runtime readiness. | Final-stage release gate | It depends on upstream product decisions, final wording review where needed, final package checks, final Plugin Check, and any explicitly selected final QA. | Last in sequence; cannot be substituted by one prior package or wording result. | Final-stage gate |

## Completed / Matured Tracks Not Selected for Immediate Reopening

The following are not immediate active workstreams:

1. OpenAI legacy / transitional fallback policy and source-boundary
   maturation.
2. OpenAI storage / privacy / public-documentation wording review.
3. Initial single-site support-boundary wording correction and verification.
4. OAuth client configuration hybrid source maturation.
5. Narrow OAuth lifecycle status/category UI and local-only disconnect
   maturation.
6. Manual Google Access Token fallback retirement and related readme wording.
7. Narrow deterministic single-site uninstall option cleanup.

These tracks may be reopened only by a defined trigger, such as:

- a relevant production source change;
- a storage-model change;
- a public-support decision change;
- a multisite support decision;
- a public-wording revision;
- a release-review or Plugin Check finding that affects the established
  boundary.

This routing does not mean all release gates are closed. It prevents completed
tracks from being repeated merely because the overall release status remains
`Hold`.

## Prioritization Criteria

Candidates were compared using:

1. directness to the current `Hold` status;
2. importance as a prerequisite for a public-release decision;
3. maturity of existing evidence and the size of remaining uncertainty;
4. dependency relationships with later gates;
5. ability to advance safely through docs-only, source-level, or controlled
   verification;
6. ability to avoid provider communication, secret inspection, legal
   judgment, or runtime mutation in the immediate next step;
7. whether the candidate would improperly repeat a completed track.

The prioritization does not treat:

- a completed wording track as unfinished solely because later step numbers
  are available;
- multisite implementation as required for the selected initial single-site
  support posture;
- final Plugin Check as a substitute for unresolved upstream lifecycle
  decisions;
- documentation review as a legal compliance determination;
- source/docs review as proof of OAuth provider/runtime success.

## Candidate Active Workstreams

### Candidate A: OAuth Lifecycle Remaining Hold-gate Prioritization and Release-boundary Planning

Current evidence:

- OAuth client configuration hybrid source track matured in Step 198;
- lifecycle status/category UI and local-only disconnect matured in Step 208;
- manual Google Access Token fallback retired in Step 223;
- related public wording aligned in Step 227;
- uninstall option cleanup matured in Step 215;
- refresh execution and provider-side revoke remain explicitly deferred.

What it can resolve:

- distinguish already-matured OAuth boundaries from remaining provider
  lifecycle questions;
- decide the release impact of deferred refresh and provider-side revoke;
- decide which remaining issue needs source review, narrow implementation
  planning, controlled local-only verification, or a separate provider/runtime
  gate;
- prevent old, superseded OAuth blockers from being reopened as one broad
  undifferentiated task.

What it cannot resolve:

- provider authorization;
- refresh or revoke success;
- token endpoint runtime behavior;
- security, legal, policy, package, or release approval.

Begin now:

```text
Yes
```

Main risk of beginning now:

- overstating docs/source maturity as complete OAuth runtime readiness.

Dependency relationship:

- upstream of final packaging, final Plugin Check, and final release
  validation;
- independent of completed OpenAI wording and single-site wording tracks;
- does not require multisite implementation.

### Candidate B: OpenAI Storage, Privacy, and Public-documentation Formal Handoff

Current evidence:

- OpenAI fallback policy/source boundary matured in Step 274;
- final documentation/source-boundary review completed in Step 276;
- its direct wording omission was planned, implemented, and verified in Steps
  277-279.

What it can resolve:

- record or route a formal privacy/legal/policy handoff without making a legal
  determination.

What it cannot resolve:

- provider behavior;
- independent storage security acceptance;
- legal/privacy-law compliance;
- WordPress.org policy compliance.

Begin now:

```text
No, not as the immediate active workstream
```

Main risk of beginning now:

- repeating a completed documentation/source-boundary review or implying that
  plugin documentation can make a formal legal conclusion.

Dependency relationship:

- a separate qualified review may remain necessary, but it is not shown by the
  inspected docs to be a higher technical prerequisite than the unresolved
  OAuth lifecycle release boundary.

### Candidate C: Final Packaging, Isolated Plugin Check, and Final Release-validation Planning

Current evidence:

- Step 108 recorded a clean-package isolated Plugin Check pass;
- package exclusion and clean-target preparation have historical evidence.

What it can resolve:

- define final package build, inspection, install, and isolated Plugin Check
  sequencing once upstream release-affecting work is stable.

What it cannot resolve:

- OAuth lifecycle policy or provider/runtime uncertainty;
- privacy/legal/policy review;
- product support decisions;
- release approval by itself.

Begin now:

```text
No, keep as a final-stage gate
```

Main risk of beginning now:

- creating stale package evidence that must be repeated after an upstream
  OAuth lifecycle decision or related change.

Dependency relationship:

- downstream of the selected active Hold-gate workstream and any resulting
  release-affecting change.

### Candidate D: Multisite Support Implementation and Controlled Runtime Verification

Current evidence:

- site-level source boundaries were documented in Step 272;
- Step 273 selected an initial single-site public-support policy;
- Steps 277-279 made that boundary public and verified the wording.

What it can resolve:

- future multisite architecture and runtime support only after a new explicit
  product decision.

What it cannot resolve:

- current initial single-site release gates without expanding product scope.

Begin now:

```text
No
```

Main risk of beginning now:

- expanding storage, activation, lifecycle, uninstall, and QA scope without a
  product requirement and reopening a settled initial support boundary.

Dependency relationship:

- future-trigger-only; not a prerequisite for the current initial
  single-site support posture.

## Priority Decision

Selected next active workstream:

```text
Candidate A:
OAuth lifecycle remaining Hold-gate prioritization and release-boundary
decision / planning track
```

Decision rationale:

- refresh execution and provider-side revoke are still explicitly documented
  as deferred / Hold;
- current source, policy, UI, and human-smoke evidence is mature enough to
  support a focused decision checkpoint without repeating implementation;
- the remaining lifecycle boundary is closer to core external-service
  behavior than final packaging;
- final packaging and Plugin Check would become stale if performed before a
  release-affecting lifecycle decision;
- the OpenAI and single-site wording tracks are completed absent a defined
  trigger;
- multisite implementation is not required by the adopted initial support
  policy;
- the immediate next step can remain docs-only and avoid provider
  communication, token inspection, runtime mutation, and legal judgment.

Priority conclusion:

```text
Candidate A selected as the single next active workstream.
```

## Deferred, Future-trigger-only, and Final-stage Gates

Deferred or separate gates:

- formal privacy / legal / policy review, without a legal conclusion in this
  repository;
- independent credential-storage or security acceptance if required by the
  release decision;
- provider/runtime verification for refresh, revoke, authorization, and token
  lifecycle behavior;
- controlled external QA only if explicitly selected by a later plan.

Future-trigger-only:

- multisite implementation and controlled runtime verification;
- network activation/deactivation/uninstall support;
- cross-site storage and cleanup;
- reopening completed OpenAI fallback, wording, or single-site support tracks.

Final-stage gates:

- final release-candidate package build and contents inspection;
- final install validation;
- final isolated Plugin Check against the final clean target;
- final public documentation/readme consistency review after upstream changes;
- final WordPress.org release decision.

## Next-step Scope Boundary

Recommended Step 281 scope:

- docs-only / planning-only;
- inventory current OAuth lifecycle implementation and wording boundaries;
- preserve the local-only disconnect boundary;
- preserve refresh-deferred and provider-side-revoke-deferred non-claims;
- distinguish source-confirmed, policy-fixed, runtime-unverified, and future
  implementation candidate items;
- decide whether the next follow-up should be:
  - source-level review;
  - narrow implementation plan;
  - controlled local-only verification plan;
  - separate provider/runtime gate;
- keep final packaging and Plugin Check downstream.

Step 281 must not:

- execute OAuth provider requests;
- execute refresh or revoke;
- make external HTTP requests;
- run browser OAuth;
- inspect token, option, credential, or constant values;
- save Settings;
- run GA4 Fetch or OpenAI Generate;
- run Plugin Check;
- change production source or public documentation.

## Explicit Non-claims

Step 280 does not determine or prove:

- public-release approval;
- WordPress.org policy compliance;
- legal or privacy-law compliance;
- secret-storage certification;
- encryption at rest;
- provider authorization;
- OAuth refresh or revoke success;
- complete provider-side cleanup;
- multisite runtime support or incompatibility;
- final package correctness;
- final Plugin Check success;
- complete release validation.

Step 280 also does not classify the full OAuth lifecycle as complete. It
classifies selected status/UI, source-configuration, local-disconnect,
fallback-retirement, wording, and uninstall boundaries as already matured,
while routing the unresolved provider lifecycle boundary to the next active
workstream.

## Public Release Implication

```text
WordPress.org public release readiness remains Hold.
```

Step 280 prioritizes existing documented Hold gates only. It does not resolve
every Hold gate, authorize release, or convert a documentation/source-boundary
decision into provider, security, legal, policy, runtime, or release
validation.

## Recommended Next Step

```text
Step 281 candidate —
OAuth lifecycle remaining Hold-gate release-boundary planning checkpoint
```

Step 281 should remain docs-only / planning-only and should select the next
minimum OAuth lifecycle follow-up without executing provider communication,
refresh, revoke, external HTTP, browser OAuth, token inspection, Settings
save, GA4 Fetch, OpenAI Generate, or Plugin Check.

## Result Classification

```text
WordPress.org Hold-gate prioritization checkpoint: Completed
Selected next active workstream: Candidate A
WordPress.org public release readiness: Hold
```
