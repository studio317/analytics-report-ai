# Step 290.1: Google OAuth External-service and Current-flow Wording Narrow Alignment Plan

## 1. Step Purpose and Relation to Step 290 Findings

Step 290.1 is a docs-only / planning-only checkpoint for the two narrow
wording findings recorded by Step 290:

- Finding A: the top-level External Services statement limits third-party
  service use to a report action and therefore omits the separate
  administrator-initiated Google OAuth authorization action;
- Finding B: the Settings redirect URI label and help text retain
  future/preparation framing after the bounded authorization-code flow was
  implemented.

This plan defines the smallest wording-only implementation boundary for Step
290.2 and the required source/documentation recheck for Step 290.3.

It does not reopen or expand OAuth implementation scope.

```text
WordPress.org public release readiness:
Hold
```

## 2. Scope and Explicit Non-scope

In scope:

- identify the current source owners of the two wording findings;
- define the public disclosure meaning required in `readme.txt`;
- define the current-flow wording required around the Settings redirect URI;
- preserve all current bounded OAuth, GA4, OpenAI, cleanup, and support
  boundaries;
- define static verification for Step 290.2;
- define the final wording/release-boundary recheck required in Step 290.3.

Out of scope:

- implementation in this step;
- OAuth runtime behavior changes;
- token storage changes;
- refresh or provider-side revoke implementation;
- GA4 Fetch or OpenAI Generate behavior changes;
- uninstall or multisite behavior changes;
- package, version, tool, or distribution changes;
- runtime or provider verification;
- public-release approval.

## 3. Initial Baseline Classification

The following commands were run before this document was added:

```text
git status --short --untracked-files=all
git diff --name-only
git diff --check
git log -1 --oneline
```

The status and diff commands returned no output. Repository history showed
the Step 290 checkpoint committed at `HEAD`.

Initial baseline classification:

```text
Clean committed baseline
```

No existing predecessor documentation change was present in the working tree.
The final release-candidate baseline is not established by this planning
classification because the Step 290 findings remain unresolved.

## 4. Sensitive-information and Evidence Boundary

Evidence used by this plan was limited to:

- source file and symbol ownership;
- static public and administrator wording;
- source control-flow categories;
- existing maturation conclusions;
- Git command-result categories.

This plan did not inspect, reproduce, or record:

- credentials, API keys, OAuth tokens, or refresh tokens;
- OAuth client values;
- option values or constant values;
- authorization or redirect URLs;
- the displayed redirect URI value;
- callback values or query parameters;
- authorization codes;
- request or response bodies;
- Authorization headers;
- payload JSON;
- generated report text;
- analytics values;
- database content;
- screenshots;
- browser Network evidence.

Any wording candidates in this plan describe meaning and category boundaries
only. They are not runtime observations or implemented text.

## 5. Current Source and Public Wording Surfaces Reviewed

Primary current surfaces:

- `readme.txt`, especially:
  - `External Services`;
  - `Google Analytics Data API`;
  - `OpenAI API`;
  - `Credential Storage and Payload Review`;
  - `Supported Site Scope`;
- `includes/class-settings.php`, especially:
  - the Google OAuth Connection introduction;
  - the redirect URI label;
  - redirect URI setup help;
  - OAuth authorization disclosure;
  - status-level/value-hidden disclosure;
  - refresh and provider-revoke deferred wording;
  - local-only disconnect wording;
  - the Start Google OAuth Authorization control;
- `includes/class-admin.php`, for source confirmation of:
  - OAuth Connect preconditions and redirect;
  - callback/state classification;
  - callback-bound authorization-code exchange;
  - local token-storage handoff;
  - local-only disconnect;
- `includes/class-report-builder.php`, for the separate GA4 credential-use and
  GA4 Fetch boundaries;
- `includes/functions-utils.php`, for category-level source and lifecycle
  boundaries.

Planning and verification records:

- `docs/maturation/step283-oauth-refresh-provider-revoke-and-provider-runtime-initial-public-release-disposition-decision-checkpoint.md`;
- `docs/maturation/step284-implemented-authorization-code-oauth-flow-controlled-provider-runtime-verification-gate-planning-checkpoint.md`;
- `docs/maturation/step285-implemented-authorization-code-oauth-flow-controlled-preflight-and-explicit-operator-authorization-checkpoint.md`;
- `docs/maturation/step286-oauth-client-configuration-readiness-restoration-and-controlled-preflight-re-entry-plan.md`;
- `docs/maturation/step287-implemented-authorization-code-oauth-flow-one-time-controlled-provider-runtime-category-observation-results.md`;
- `docs/maturation/step288-implemented-authorization-code-oauth-flow-post-observation-source-documentation-alignment-and-release-gate-checkpoint.md`;
- `docs/maturation/step289-final-release-stage-package-install-validation-and-isolated-plugin-check-sequencing-plan.md`;
- `docs/maturation/step290-final-public-wording-and-release-boundary-consistency-checkpoint.md`.

## 6. Finding A: External Services Action-disclosure Gap

### Current Public Wording

The top-level External Services statement currently limits third-party
service use to an administrator starting a report action. The subsequent
subsections correctly describe:

- `Fetch GA4 Data` as the Google Analytics Data API action;
- `Generate AI Report` as the OpenAI API action.

### Current Implemented Behavior

Current source also contains a distinct Settings action:

```text
Start Google OAuth Authorization
```

After local capability, nonce, client-source, and state boundaries pass, this
action can redirect the browser to Google. After a callback return, a valid
state and authorization-code category can lead to an authorization-code token
exchange and local token-storage handoff.

This action is not GA4 Fetch, OpenAI Generate, or automatic background
processing.

### Planning Finding

The top-level public statement is incomplete because "report action" does not
reasonably communicate the separate OAuth authorization action.

Planning classification:

```text
Ready for narrow wording implementation
```

## 7. Finding B: Settings Redirect URI Stale Future/preparation Wording

### Current Static Administrator Wording

The redirect URI area in `includes/class-settings.php` contains:

- a label that describes the redirect URI as being for future Google OAuth
  setup;
- help text that tells the administrator to copy the value when preparing
  OAuth support.

Adjacent wording already describes the current bounded flow in present terms:

- starting authorization can redirect the browser to Google;
- a validated callback can attempt token exchange;
- token values and raw endpoint details are not displayed;
- refresh and provider-side revoke remain deferred.

### Current Implemented Behavior

The redirect URI is currently used by the implemented authorization-code
flow. The bounded flow is not merely planned or a future placeholder.

This does not mean:

- complete provider/runtime lifecycle support is implemented;
- refresh is implemented;
- provider-side revoke is implemented;
- every provider/runtime outcome has been validated.

### Planning Finding

Only the stale future/preparation framing in the redirect URI label and its
direct setup help needs correction. The adjacent current-flow, value-hidden,
deferred, and disconnect wording does not need expansion.

Planning classification:

```text
Ready for narrow wording implementation
```

## 8. Current OAuth Bounded-flow Statements That Must Remain Unchanged

Step 290.2 must preserve the following meanings:

- OAuth client-source readiness is category-level;
- OAuth authorization is an explicit administrator action;
- authorization can redirect the browser to Google;
- token exchange is callback-bound and follows state validation;
- local token-storage handoff is part of the bounded implemented flow;
- Settings and Report Builder display status/category-level information;
- credential and token values are not displayed;
- lifecycle/readiness and Report Builder credential-use entry are bounded
  categories;
- refresh execution remains deferred;
- provider-side revoke remains deferred;
- local-only disconnect deletes local OAuth token data only;
- local-only disconnect is not provider-side revoke or provider cleanup;
- uninstall cleanup remains separate from local disconnect and provider
  action;
- complete provider/runtime lifecycle validation remains outside the bounded
  claim.

No wording change may turn a source category or bounded observation into a
claim about credential validity, provider acceptance, token validity, or
complete lifecycle success.

## 9. Narrow Wording-alignment Principles

Step 290.2 should follow these principles:

1. Describe three distinct administrator-initiated external-service action
   categories:
   - Google OAuth authorization;
   - GA4 Fetch;
   - OpenAI Generate.
2. Preserve the statement that ordinary Settings and Report Builder viewing
   alone does not contact Google or OpenAI.
3. Describe OAuth redirect and callback-bound exchange at category level.
4. Keep GA4 Fetch and OpenAI Generate in their existing separate
   subsections.
5. Use present-tense wording for the implemented redirect URI setup purpose.
6. Avoid claims of complete OAuth lifecycle support or guaranteed provider
   success.
7. Preserve refresh and provider-side revoke as deferred.
8. Preserve local-only disconnect and uninstall boundaries.
9. Introduce no values, generated URLs, raw protocol material, or secret-like
   examples.
10. Keep all changed administrator strings translatable and escaped through
    the existing WordPress patterns.

## 10. Proposed Step 290.2 File Scope

Expected wording-change files:

- `readme.txt`
  - replace the overly narrow top-level External Services statement;
  - add only the minimum category-level OAuth action disclosure needed to
    distinguish authorization from GA4 Fetch and OpenAI Generate.
- `includes/class-settings.php`
  - update the redirect URI label;
  - update the directly associated redirect URI setup help text;
  - retain the existing translation domain and escaping pattern.

Expected new result-documentation file:

- `docs/maturation/step290-2-google-oauth-external-service-and-current-flow-wording-narrow-implementation-results.md`.

Files explicitly out of scope:

- `includes/class-admin.php`;
- `includes/class-report-builder.php`;
- `includes/functions-utils.php`;
- GA4 and OpenAI clients;
- JavaScript and CSS;
- `uninstall.php`;
- `.distignore`;
- package and build tools;
- version-bearing production declarations and Stable tag;
- existing maturation documents.

Historical or non-selected placeholder notice labels in
`includes/class-settings.php` are not part of the two Step 290 findings and
must not be swept into Step 290.2 without a separate finding.

## 11. Planned External Services Wording Correction Boundary

Step 290.2 should replace the "report action only" generalization with wording
whose meaning is:

- third-party communication occurs only after an administrator starts a
  relevant external-service action;
- those actions are Google OAuth authorization, GA4 Fetch, and OpenAI
  Generate;
- viewing plugin screens alone does not contact Google or OpenAI.

The public disclosure should also state, at category level, that:

- starting Google OAuth authorization can redirect the browser to Google;
- after the browser returns, the plugin can attempt authorization-code
  exchange only after callback state validation;
- the OAuth action is separate from GA4 data retrieval;
- refresh and provider-side revoke remain deferred.

Implementation choices may use:

- a corrected top-level paragraph plus a short Google OAuth paragraph; or
- a corrected top-level paragraph plus a concise Google OAuth subsection.

The minimum option that remains readable and keeps the three actions distinct
should be selected.

The correction must not:

- merge OAuth authorization into the Google Analytics Data API request;
- imply that OAuth authorization itself fetches analytics data;
- merge OAuth or GA4 Fetch into OpenAI Generate;
- imply automatic background communication;
- state or reproduce an authorization URL, callback value, redirect URI
  value, client value, token, request, or response;
- claim guaranteed authorization, token validity, provider acceptance, or
  complete lifecycle support.

## 12. Planned Settings Wording Correction Boundary

Step 290.2 should change only the two stale redirect URI strings owned by
`includes/class-settings.php`.

Planned label meaning:

```text
Redirect URI for Google OAuth setup
```

Planned help-text meaning:

```text
The redirect URI is used for Google OAuth client setup. Copy the displayed
value into the Google OAuth client configuration used by this site.
```

These are semantic candidates, not implemented wording.

The final strings must:

- use present-tense setup wording;
- preserve the redirect URI setup purpose;
- remain translatable with the `analytics-report-ai` text domain;
- use the existing escaping pattern;
- avoid reproducing the redirect URI value in documentation;
- avoid client ID, client secret, callback parameter, token, or request
  details.

The following adjacent strings should remain unchanged unless a direct
grammar adjustment is strictly necessary:

- Google OAuth preferred-source introduction;
- current authorization redirect/token exchange disclosure;
- status-level/value-hidden disclosure;
- refresh and provider-revoke deferred disclosure;
- manual-token retirement wording;
- local-only disconnect wording;
- Start Google OAuth Authorization button.

## 13. Explicit No-scope-expansion Boundary

Step 290.2 is limited to:

- public wording;
- static administrator wording;
- the directly corresponding implementation-results document.

Step 290.2 does not require or authorize:

- OAuth control-flow changes;
- authorization URL construction changes;
- callback or state-validation changes;
- token exchange changes;
- token storage changes;
- lifecycle category changes;
- refresh implementation;
- provider-side revoke implementation;
- provider-side cleanup;
- GA4 Fetch changes;
- OpenAI Generate changes;
- credential source or storage redesign;
- local disconnect changes;
- uninstall changes;
- multisite changes;
- package-tool changes;
- `.distignore` changes;
- version or Stable tag changes.

No runtime action is needed to implement or verify this static wording change.

## 14. Step 290.2 Static Verification Plan

Step 290.2 should perform static verification only.

Required checks:

1. Confirm only the planned files changed.
2. Confirm the External Services wording distinguishes:
   - OAuth authorization;
   - GA4 Fetch;
   - OpenAI Generate.
3. Confirm ordinary screen-viewing non-transmission wording remains present.
4. Confirm the OAuth disclosure includes browser redirect and validated
   callback-bound authorization-code exchange categories.
5. Confirm the targeted Settings label/help no longer contain stale
   future/preparation framing.
6. Confirm the redirect URI setup purpose remains clear.
7. Confirm refresh and provider-side revoke remain deferred.
8. Confirm local-only disconnect wording remains unchanged.
9. Confirm initial single-site support wording remains unchanged.
10. Confirm OpenAI storage/value-hidden wording remains unchanged.
11. Confirm no credential, token, option value, constant value, URL value,
    callback value, request/response body, payload, analytics value, or
    generated report text was introduced.
12. Run PHP syntax checks because `includes/class-settings.php` is a PHP
    wording source.
13. Run `git diff --check`.
14. Record `git diff --name-only`, `git diff --stat`, and
    `git status --short --untracked-files=all`.

Suggested targeted searches should be value-free and limited to the changed
wording categories. No runtime, provider, WP-CLI, database, or browser check
belongs in Step 290.2.

## 15. Step 290.3 Final Wording/release-boundary Recheck Plan

After Step 290.2 is implemented, Step 290.3 must perform:

```text
Final public wording / release-boundary consistency recheck
```

The recheck must re-establish the Step 290 Phase 1 evidence against the
corrected baseline.

Minimum recheck domains:

- External Services disclosure;
- Settings OAuth wording;
- bounded OAuth authorization-code flow;
- refresh-deferred boundary;
- provider-revoke-deferred boundary;
- local-only disconnect;
- GA4 Fetch action boundary;
- OpenAI Generate action boundary;
- OpenAI storage and value-hidden policy;
- uninstall cleanup boundary;
- initial single-site support wording;
- version and release identity consistency.

Step 290.3 must distinguish:

- corrected wording alignment;
- unchanged source/runtime boundaries;
- package and release gates that remain unperformed.

Step 290.3 does not replace:

- final package build;
- package contents inspection;
- isolated package install validation;
- final isolated Plugin Check;
- final WordPress.org release decision.

Only after Step 290.3 records alignment, the resulting baseline is committed,
and the working tree is clean should the sequence proceed to Step 291.

## 16. Explicitly Non-executed Actions

Step 290.1 did not perform:

- `readme.txt` modification;
- production PHP, JavaScript, or CSS modification;
- Settings wording modification;
- version or Stable tag modification;
- package build;
- ZIP or archive creation;
- package contents inspection;
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
- credential, token, option, constant, or OAuth client value inspection;
- commit;
- push.

## 17. Step 290.1 Conclusion

Step 290.1 planning status:

```text
Planning completed
```

Step 290 findings:

```text
Confirmed and routed to one narrow wording implementation step
```

Step 290.2 implementation readiness:

```text
Ready for narrow wording implementation
```

The source owners and wording scope are sufficiently clear:

- `readme.txt` owns the top-level external-service disclosure;
- `includes/class-settings.php` owns the two stale redirect URI strings.

Step 290.3 necessity:

```text
Required after Step 290.2
```

Final artifact gate status:

```text
Final package build: Not performed
Package contents inspection: Not performed
Isolated package install validation: Not performed
Final isolated Plugin Check: Not performed
```

## 18. Release Readiness Statement

```text
WordPress.org public release readiness:
Hold
```

Step 290.1 does not approve the wording implementation, freeze a
release-candidate baseline, validate a package, run Plugin Check, or authorize
public release.

The Hold remains until:

- Step 290.2 implements the narrow wording correction;
- Step 290.3 re-establishes final wording/release-boundary consistency;
- the corrected state is committed and clean;
- the later package, install, isolated Plugin Check, and release-decision
  gates are completed.

## 19. Recommended Next Gate

Recommended next step:

```text
Step 290.2: Google OAuth external-service and current-flow wording narrow
implementation
```

Step 290.2 should change only:

- `readme.txt`;
- the two targeted static strings in `includes/class-settings.php`;
- its new implementation-results document.

It must preserve all runtime, storage, lifecycle, cleanup, support, and
single-site boundaries defined by this plan.
