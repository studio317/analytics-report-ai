# Step 281: OAuth Lifecycle Remaining Hold-gate Release-boundary Planning Checkpoint

## Step Objective and Decision Limits

Step 281 is a docs-only / decision-only checkpoint for the OAuth lifecycle
workstream selected by Step 280.

The purpose is to preserve the OAuth boundaries already matured within the
current MVP scope, isolate the remaining refresh, provider-side revoke, and
provider/runtime questions, and select exactly one minimum follow-up.

This checkpoint does not implement or execute refresh, revoke, token endpoint,
OAuth provider, callback, local disconnect, Settings, GA4, OpenAI, package, or
Plugin Check behavior.

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

Repository history confirmed that Step 280 was committed at the baseline.
There were no pre-existing source, public-documentation, maturation-document,
tool, or environment changes.

## Evidence Sources and Evidence-level Boundary

Primary decision records:

- `docs/maturation/step280-wordpress-org-hold-gate-prioritization-checkpoint.md`;
- `docs/maturation/step198-oauth-client-configuration-hybrid-source-final-maturation-checkpoint.md`;
- `docs/maturation/step204-oauth-token-lifecycle-narrow-production-implementation-results.md`;
- `docs/maturation/step205-oauth-token-lifecycle-source-level-verification-results.md`;
- `docs/maturation/step207-oauth-token-lifecycle-controlled-human-admin-smoke-results.md`;
- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`;
- `docs/maturation/step215-uninstall-cleanup-maturation-checkpoint.md`;
- `docs/maturation/step223-manual-google-access-token-fallback-retirement-maturation-checkpoint.md`;
- `docs/maturation/step227-readme-privacy-wording-alignment-after-manual-token-retirement-maturation-checkpoint.md`;
- `docs/maturation/step270-openai-legacy-transitional-fallback-storage-migration-and-uninstall-release-boundary-decision-checkpoint.md`;
- `docs/maturation/step272-openai-legacy-transitional-fallback-multisite-and-uninstall-source-level-release-boundary-results.md`;
- `docs/maturation/step274-openai-legacy-transitional-fallback-release-boundary-maturation-checkpoint.md`.

Supporting token exchange evidence:

- `docs/maturation/step145-narrow-token-exchange-production-implementation-results.md`;
- `docs/maturation/step147-human-controlled-token-exchange-smoke-results.md`;
- `docs/maturation/step152-narrow-ga4-oauth-credential-source-production-implementation-results.md`;
- `docs/maturation/step153-ga4-oauth-credential-source-source-level-verification-results.md`.

Read-only source orientation was limited to symbols, control-flow categories,
external-request API presence, and static wording in:

- `includes/class-admin.php`;
- `includes/class-settings.php`;
- `includes/functions-utils.php`;
- `uninstall.php`;
- `readme.txt`.

The targeted orientation found current symbols for:

- OAuth Connect, authorization redirect, callback classification, and
  authorization-code token exchange;
- dedicated OAuth token storage and lifecycle category calculation;
- local-only disconnect;
- reconnect-required, refresh-deferred, and provider-revoke-deferred wording.

The targeted orientation was not treated as the exhaustive current-source
verification proposed for Step 282.

Evidence levels remain distinct:

- `Policy decision`;
- `Source-level confirmation`;
- `Controlled human admin smoke`;
- `Documentation / wording verification`;
- `Runtime-unverified / provider gate`;
- `Deferred / separate release gate`;
- `Future implementation candidate`.

Step 205 is the latest dedicated source verification of the narrow lifecycle
implementation, but later production changes affected OAuth client
configuration, manual fallback handling, uninstall cleanup, Settings wording,
and adjacent credential paths. Those later changes have their own scoped
verification records, but there is no later single verification record that
rechecks the complete current refresh/revoke/non-execution boundary together.

No credential, API key, OAuth token, option value, constant value,
Authorization header, request or response body, payload JSON, generated report
text, actual analytics value, screenshot, browser Network evidence, database
content, or provider response was inspected or recorded.

## Current OAuth Lifecycle Release-boundary Inventory

| OAuth lifecycle topic | Current documented implementation / wording position | Latest evidence level | Confirmed boundary | Unresolved or non-inference boundary | Current routing |
| --- | --- | --- | --- | --- | --- |
| OAuth client configuration source | Complete constants are preferred; Settings fallback, source categories, value-hidden fields, conflict handling, and Settings-fallback-only deletion are implemented. | Source-level confirmation; Controlled human admin smoke | Step 198 classifies the hybrid source track as matured for the current MVP scope. | Does not establish provider authorization, credential validity, token lifecycle completion, or provider request outcome. | Completed / matured; do not reopen without a source or policy trigger |
| OAuth authorization redirect / callback boundary | Connect, authorization URL, redirect, state validation, callback classification, authorization-code token exchange, and storage handoff have production boundaries. A historical human-controlled smoke recorded token exchange and storage success categories. | Source-level confirmation; Controlled human admin smoke | Exchange is gated behind callback/state/provider-error/code-presence control flow and reports category-level results. | Historical category-level provider evidence does not establish current full-flow behavior after later changes, refresh behavior, revoke behavior, or a final release candidate. | Preserve existing boundary; include current path inventory in Option A verification |
| Token storage / lifecycle status boundary | Dedicated non-autoloaded OAuth token storage and lifecycle category helpers exist; GA4 credential resolution uses lifecycle categories. | Source-level confirmation; Controlled human admin smoke | Token values are not normal admin evidence; lifecycle status is represented through categories. | Storage source/category evidence does not establish storage certification, token validity, provider authorization, refresh execution, or every current runtime outcome. | Matured status/category boundary; current control flow to be rechecked by Option A |
| Reconnect-required wording | Settings and Report Builder expose reconnect-required / refresh-needed categories without displaying credential material. | Source-level confirmation; Controlled human admin smoke; Documentation / wording verification | The visible boundary tells the administrator that reconnection can be required when the stored lifecycle state is not usable. | Wording does not prove a reconnect attempt, provider acceptance, token refresh, or token validity. | Completed / matured wording boundary |
| Refresh execution | Current policy and UI describe refresh as deferred. Step 205 found no refresh helper, request construction, communication, response handling, or refresh-before-fetch path in its reviewed scope. | Policy decision; Source-level confirmation; Deferred / separate release gate | Refresh is not represented as implemented behavior. Expired or refresh-needed states route to status categories and reconnection guidance. | A later holistic current-source verification has not yet reconfirmed every refresh-related path after subsequent adjacent source changes. Provider behavior cannot be inferred from source absence. | Option A first; possible future implementation candidate only after verification and decision |
| Provider-side revoke | Current policy and wording distinguish provider revoke from local disconnect and classify revoke as deferred. Step 205 found no revoke request construction, communication, or response handling in its reviewed scope. | Policy decision; Source-level confirmation; Deferred / separate release gate | Local token deletion is not represented as provider revocation. | A current holistic source verification is still needed before choosing implementation planning. Provider-side results cannot be inferred without a separate provider gate. | Option A first; possible future implementation candidate |
| Local-only disconnect | Admin action, capability/nonce boundary, dedicated local token deletion helper, category-only result, and explicit provider non-action wording are implemented. | Source-level confirmation; Controlled human admin smoke | Step 208 classifies local-only disconnect as matured within the current MVP boundary. | Does not revoke provider access, refresh tokens, alter OAuth client fallback configuration, or perform provider cleanup. | Completed / matured; verify preservation only, do not redesign |
| Manual Google Access Token fallback retirement | Normal Settings controls and resolver fallback were retired; OAuth-first admin and public wording was aligned. | Source-level confirmation; Controlled human admin smoke; Documentation / wording verification | Step 223 classifies the retirement track as matured within its selected boundary. | Does not settle refresh, revoke, provider runtime, or general storage certification. | Completed / matured; do not reopen |
| Uninstall relationship to OAuth provider lifecycle | Root `uninstall.php` deletes deterministic plugin-owned site-level options without provider communication. | Source-level confirmation; Policy decision | Step 215 classifies deterministic option cleanup as matured within the current MVP boundary. | Uninstall does not imply provider revoke, refresh, token endpoint communication, network cleanup, or provider-side deletion. | Completed narrow cleanup; preserve provider separation in Option A verification |
| Provider/runtime full-flow evidence | Historical controlled human records cover selected authorization, callback, token exchange, storage, and UI categories. | Controlled human admin smoke; Runtime-unverified / provider gate | Category-level evidence exists for selected historical flow segments. | No current evidence establishes complete OAuth provider lifecycle behavior, refresh, revoke, all failure paths, current release-candidate behavior, or provider-side cleanup. | Separate provider/runtime gate only after current source boundary is verified and a policy decision requires it |
| Final package / final release dependency | Historical clean-package Plugin Check evidence exists, but it predates later release-affecting source and readme changes. | Deferred / separate release gate; Final-stage release gate | Packaging and isolated-check procedures have prior evidence. | Final package correctness and final Plugin Check results cannot be inferred for a future release candidate. | Downstream of Option A and any resulting lifecycle decision or change |

## Matured OAuth Tracks Not Reopened

The following are not selected as immediate implementation work:

- OAuth client configuration hybrid-source maturity;
- credential source/readiness category and value-hidden UI;
- reconnect-required status wording;
- refresh-deferred and provider-revoke-deferred wording;
- local-only disconnect UI and bounded behavior;
- manual Google Access Token fallback retirement;
- deterministic plugin-owned OAuth option cleanup on uninstall.

Step 281 preserves these results as inputs. It does not reopen their
implementation solely because refresh, revoke, and provider/runtime evidence
remain unresolved.

Reopening requires a defined trigger, such as:

- a relevant source change;
- a changed credential or storage model;
- a changed disconnect/revoke product policy;
- a changed public-support or wording decision;
- a release review or Plugin Check finding affecting the established
  boundary.

This preservation does not classify refresh, provider revoke, or the complete
provider lifecycle as matured.

## Candidate Follow-up Comparison

| Option | Current evidence that supports or limits the option | What it can clarify | What it cannot resolve | Begin now? | Major risk or precondition | Relationship to final package / Plugin Check / release validation |
| --- | --- | --- | --- | --- | --- | --- |
| Option A: OAuth refresh / provider-side revoke / provider-runtime source-level release-boundary verification | Step 205 verified the narrow lifecycle implementation, while later adjacent OAuth, credential, uninstall, and wording changes have separate scoped records. Current targeted orientation shows the relevant symbols still exist, but is not an exhaustive verification. | Reconfirm current refresh request path presence/absence, revoke path presence/absence, local-disconnect/provider separation, static wording, token lifecycle control flow, uninstall separation, and all current external-request paths without executing them. | Cannot establish provider authorization, token validity, refresh/revoke outcomes, or provider/runtime success. | Yes | Must remain read-only, value-free, and explicit that source absence is not provider evidence. | Upstream. Establishes the current source boundary before implementation planning, provider-gate planning, or final package evidence. |
| Option B: OAuth refresh and provider-side revoke narrow implementation plan | Existing policy identifies refresh and revoke as deferred future candidates. | Could define request construction, response categories, storage updates, UX, and QA requirements. | Cannot determine whether current source already contains adjacent behavior or whether implementation is required for the selected release policy without a current verification. | No | Premature architecture and provider-scope expansion before the current boundary is reconfirmed and release disposition is selected. | Would create potential release-affecting work, so package and Plugin Check must remain later. |
| Option C: Controlled local-only OAuth lifecycle verification plan | Local disconnect and category UI already have source and human-smoke evidence. | Could re-observe local category transitions without provider requests. | Cannot clarify provider refresh/revoke paths or provider lifecycle behavior. It would largely repeat a matured track. | No | Repetition of Step 207/208 unless Option A finds a source change or evidence gap. | Does not remove the upstream refresh/revoke decision; final checks would still wait. |
| Option D: Separate provider/runtime OAuth verification-gate plan | Historical human-controlled category evidence exists for selected authorization/token-exchange segments, while complete provider lifecycle evidence remains absent. | Could later define environment, evidence, redaction, rollback, and stop conditions for explicitly selected provider behavior. | Cannot be scoped responsibly until current source paths and the release-required provider behaviors are identified. | No | Provider communication, credential-bearing state, redaction, cleanup, and rollback require a separate explicit plan and authorization. | Later than Option A and any policy/implementation decision; still before final release validation if selected as required evidence. |
| Option E: Maintain deferred policy with no immediate OAuth follow-up | Current UI and public wording already disclose refresh/revoke deferral and local-only disconnect. | Could preserve the current MVP behavior without implementation expansion. | Does not determine whether the deferred posture is sufficient for the initial public-release boundary or whether current source still matches all established non-claims. | No | Could carry an unresolved upstream release decision into final packaging and make final evidence ambiguous. | Final package and Plugin Check should not be used to conceal this unresolved disposition. |

## Priority Decision and Rationale

Selected minimum follow-up:

```text
Option A:
OAuth refresh / provider-side revoke / provider-runtime
source-level release-boundary verification
```

Rationale:

- Step 205 is the latest dedicated narrow lifecycle source verification, but
  later release-affecting changes touched adjacent OAuth client, credential
  resolver, Settings, uninstall, and public-wording boundaries;
- the current targeted orientation confirms relevant exchange, storage,
  lifecycle-category, and local-disconnect symbols, but it is intentionally
  not an exhaustive current-source verification;
- a current source-level pass can distinguish an actual source gap from an
  intentionally deferred policy before any implementation plan is considered;
- it can verify that local-only disconnect and uninstall remain separate from
  provider revoke;
- it can inventory existing external-request paths while preserving the rule
  that source review cannot establish provider/runtime outcomes;
- it does not require provider communication, token inspection, Settings
  mutation, browser execution, or package work;
- Options B and D are downstream of this boundary;
- Option C would repeat a matured local-only track unless Option A identifies a
  new gap;
- Option E would leave the release impact of the deferred posture insufficiently
  classified before final-stage validation.

Planning conclusion:

```text
Preferred next follow-up: Option A
```

Step 281 selects Option A but does not execute it.

## Next-step Scope Boundary

Recommended Step 282 scope:

- docs-only / verification-only;
- inspect current source without executing it;
- confirm the presence or absence of refresh request and token endpoint
  execution paths;
- confirm the presence or absence of provider-side revoke request paths;
- confirm that local-only disconnect does not call provider revoke;
- confirm current refresh-deferred, revoke-deferred, and reconnect-required
  static wording;
- confirm OAuth token lifecycle categories and source-level control flow;
- confirm uninstall/provider lifecycle separation;
- inventory current external-request path categories without recording request
  or response material;
- distinguish source-confirmed facts from provider/runtime non-inferences;
- classify whether the remaining Hold is:
  - a policy decision;
  - a source gap;
  - a future implementation candidate;
  - a separate provider/runtime gate.

Step 282 must not:

- execute OAuth provider requests;
- execute refresh or revoke;
- make external HTTP requests;
- run browser OAuth or callback execution;
- inspect tokens, options, credentials, constants, or database values;
- save Settings;
- execute local disconnect;
- run GA4 Fetch or OpenAI Generate;
- run Plugin Check;
- change production source, public documentation, or existing maturation docs.

## Deferred and Final-stage Dependencies

Deferred pending Step 282:

- deciding whether refresh needs an implementation plan;
- deciding whether provider-side revoke needs an implementation plan;
- deciding whether the selected initial release policy can retain either
  deferred boundary;
- deciding whether a provider/runtime verification gate is required;
- deciding whether another local-only verification is necessary.

Separate provider/runtime gate:

- provider authorization and account state;
- token endpoint behavior;
- refresh execution outcomes;
- provider-side revoke outcomes;
- provider-side cleanup;
- credential-bearing failure paths.

Final-stage gates:

- release-candidate package build and contents inspection;
- final install validation;
- final isolated Plugin Check against the final clean target;
- final public-documentation consistency review after any resulting change;
- final WordPress.org release decision.

The selected Option A follow-up is upstream of final packaging, final isolated
Plugin Check, and final release validation. Those final-stage checks should not
be treated as substitutes for the remaining OAuth lifecycle disposition.

## Explicit Non-claims

Step 281 does not determine or prove:

- OAuth provider authorization;
- token validity;
- refresh success;
- revoke success;
- token endpoint behavior;
- provider-side cleanup;
- complete OAuth lifecycle behavior;
- storage security certification;
- encryption at rest;
- legal or privacy-law compliance;
- WordPress.org policy compliance;
- multisite support;
- final package correctness;
- final Plugin Check success;
- public-release approval.

Step 281 also does not determine that refresh or provider-side revoke must be
implemented. It selects a current source-level verification so that the next
policy or implementation decision can be based on the current repository
boundary.

## Public Release Implication

```text
WordPress.org public release readiness remains Hold.
```

Step 281 selects the next minimum OAuth lifecycle release-boundary follow-up.
It does not authorize release, resolve provider/runtime behavior, validate
refresh or revoke, redesign storage, or replace final packaging, isolated
Plugin Check, or final release validation.

## Recommended Next Step

```text
Step 282 candidate —
OAuth refresh, provider-side revoke, and provider-runtime
source-level release-boundary verification
```

Step 282 should remain docs-only / verification-only and must not execute
OAuth provider requests, refresh, revoke, external HTTP, browser OAuth, token
inspection, Settings save, local disconnect, GA4 Fetch, OpenAI Generate, or
Plugin Check.

## Result Classification

```text
OAuth lifecycle remaining Hold-gate release-boundary planning checkpoint:
Completed

Preferred next follow-up:
Option A

WordPress.org public release readiness:
Hold
```
