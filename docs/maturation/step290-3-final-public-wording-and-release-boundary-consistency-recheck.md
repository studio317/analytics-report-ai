# Step 290.3: Final Public Wording and Release-boundary Consistency Recheck

## 1. Step Purpose and Relation to Steps 289-290.2

Step 290.3 is a docs-only / review-only recheck of the final public wording
and release boundaries after the narrow wording corrections implemented by
Step 290.2.

The final-stage sequence established by Step 289 requires public wording and
source/documentation consistency to be established before a release-candidate
baseline is frozen.

Step 290 identified two wording findings:

- the top-level External Services disclosure omitted the separate
  administrator-initiated Google OAuth authorization action;
- the Settings redirect URI label and help text retained stale
  future/preparation framing.

Step 290.1 planned the narrow correction, and Step 290.2 implemented and
committed it. Step 290.3 re-establishes the Phase 1 review evidence against
that corrected committed baseline.

```text
WordPress.org public release readiness:
Hold
```

## 2. Scope and Explicit Non-scope

In scope:

- static comparison of release identity surfaces;
- recheck of both Step 290 findings;
- current public External Services wording;
- current static Settings and Report Builder wording;
- current OpenAI source/value-hidden wording;
- current bounded OAuth source and lifecycle wording;
- local-only disconnect and uninstall cleanup boundaries;
- GA4 Fetch and OpenAI Generate action boundaries;
- initial single-site public-support wording;
- classification of the Phase 1 final wording gate.

Out of scope:

- source or public wording changes;
- package build or archive creation;
- package contents inspection;
- package installation or lifecycle execution;
- Plugin Check;
- browser, provider, OAuth, GA4, or OpenAI runtime execution;
- credential, option, database, request, response, payload, or analytics-data
  inspection;
- release-candidate baseline freeze;
- public-release approval.

## 3. Initial Baseline and Predecessor Gate Result

The following read-only checks were run before this document was added:

```text
git status --short --untracked-files=all
git diff --name-only
git diff --check
git log -1 --oneline
git ls-files --error-unmatch <Step 290 document>
git ls-files --error-unmatch <Step 290.1 document>
git ls-files --error-unmatch <Step 290.2 document>
git log -1 --name-status
```

Results:

- the working tree was clean;
- Step 290 was tracked and committed;
- Step 290.1 was tracked and committed;
- Step 290.2 was tracked and committed;
- the committed Step 290.2 baseline contained only:
  - the `readme.txt` wording correction;
  - the two targeted `includes/class-settings.php` wording corrections;
  - the Step 290.2 implementation-results document.

Initial baseline classification:

```text
Clean committed corrected wording baseline
```

Predecessor gate result:

```text
Passed
```

This is a source/documentation review baseline. It is not a frozen
release-candidate artifact baseline.

## 4. Sensitive-information / Evidence Boundary

Evidence was limited to:

- file and source-symbol ownership;
- static public and administrator wording;
- bounded source control-flow categories;
- prior maturation conclusions;
- version-consistency result category;
- Git status, history, and file-change categories.

This recheck did not inspect, reproduce, or record:

- credentials, API keys, OAuth tokens, or refresh tokens;
- OAuth client values;
- option values or constant values;
- redirect URI values;
- authorization URLs;
- callback query values;
- authorization codes;
- Authorization headers;
- request or response bodies;
- payload JSON;
- generated report text;
- analytics values;
- database contents;
- screenshots;
- browser Network evidence.

No provider/runtime result was inferred from static source or wording.

## 5. Sources and Public Documents Reviewed

Current source and public surfaces:

- `analytics-report-ai.php`;
- `readme.txt`;
- `includes/class-settings.php`;
- `includes/class-admin.php`;
- `includes/class-report-builder.php`;
- `includes/functions-utils.php`;
- `uninstall.php`.

Primary final-stage records:

- `docs/maturation/step289-final-release-stage-package-install-validation-and-isolated-plugin-check-sequencing-plan.md`;
- `docs/maturation/step290-final-public-wording-and-release-boundary-consistency-checkpoint.md`;
- `docs/maturation/step290-1-google-oauth-external-service-and-current-flow-wording-narrow-alignment-plan.md`;
- `docs/maturation/step290-2-google-oauth-external-service-and-current-flow-wording-narrow-implementation-results.md`.

Current bounded OAuth context remained anchored to the Step 283-288 decision,
planning, controlled-observation, and source/documentation alignment records.

Historical or non-selected placeholder-oriented notice labels remain in the
source. The selected current callback path replaces the code-present
classification with an exchange-result category before returning to
Settings. Those labels were previously recorded as non-selected historical
wording and do not alter the current selected control flow or the Step 290
findings disposition.

## 6. Version and Release Identity Consistency Result

The following current source-level identity surfaces were compared without
recording their values in this document:

- plugin header Version;
- plugin version constant;
- `readme.txt` Stable tag.

Result:

```text
Consistent
```

Classification:

```text
Aligned at source-documentation review level
```

This is a static source-level comparison. It does not validate package
identity because no package was built or inspected.

## 7. Step 290 Finding A Recheck: External Services Action Disclosure

The corrected top-level External Services wording now:

- avoids limiting third-party communication to report actions;
- distinguishes Google OAuth authorization, Fetch GA4 Data, and Generate AI
  Report as separate administrator-initiated actions;
- states that viewing Settings or Report Builder alone does not contact
  Google or OpenAI.

The Google OAuth Authorization subsection now states, at category level,
that:

- starting authorization can redirect the browser to Google;
- after the browser returns, the plugin can attempt a callback-bound
  authorization-code exchange only after callback state validation;
- the authorization action is separate from Fetch GA4 Data;
- authorization does not itself retrieve GA4 report data;
- refresh execution and provider-side revoke remain deferred.

The existing Google Analytics Data API and OpenAI API subsections remain
separate and preserve their action-specific disclosures.

No authorization URL, callback value, OAuth client value, token, request, or
response material was introduced into the public wording.

Recheck result:

```text
Resolved at source-documentation review level
```

Classification:

```text
Aligned at source-documentation review level
```

## 8. Step 290 Finding B Recheck: Settings Redirect URI Current-flow Wording

The targeted Settings redirect URI label now:

- uses present-tense Google OAuth setup wording;
- contains no stale future framing.

The directly associated setup help now:

- explains that the redirect URI is used for Google OAuth client setup;
- directs the administrator to use the displayed value in the site's Google
  OAuth client configuration;
- contains no preparation-stage framing.

The implementation preserves:

- the `analytics-report-ai` text domain;
- the existing `esc_html__()` output pattern;
- the HTML structure;
- the read-only input behavior;
- the existing escaped value-rendering boundary;
- adjacent authorization, value-hidden, refresh-deferred,
  provider-revoke-deferred, manual-token retirement, and local-disconnect
  wording.

No field behavior or OAuth runtime logic changed.

Recheck result:

```text
Resolved at source-documentation review level
```

Classification:

```text
Aligned at source-documentation review level
```

## 9. OpenAI Configuration and Value-hidden Wording Consistency Result

Current source, Settings wording, Report Builder wording, and public
documentation remain consistent with these boundaries:

- constant-based OpenAI configuration is preferred;
- the Settings fallback is legacy / transitional compatibility only;
- the normal Settings UI does not create or replace a fallback;
- an existing saved fallback can be explicitly cleared locally;
- clearing the saved fallback does not alter constant-based configuration;
- constant values are not displayed or edited by the plugin UI;
- saved credential values are not redisplayed;
- source/readiness and value visibility are exposed as safe categories;
- source/readiness categories do not establish provider acceptance or request
  success;
- support/debug evidence remains status/category-level and excludes raw or
  sensitive material.

The Step 290.2 wording change did not modify any OpenAI source, setting,
storage, request, UI, or support boundary.

Classification:

```text
Aligned at source-documentation review level
```

## 10. OAuth Bounded-flow / Lifecycle / Deferred-boundary Consistency Result

Current source and wording remain consistent for:

- OAuth client-source readiness;
- browser authorization redirect;
- callback and state validation;
- callback-bound authorization-code exchange;
- local token-storage handoff;
- lifecycle/readiness categories;
- Report Builder OAuth credential-use entry;
- local-only disconnect.

Current wording does not claim:

- guaranteed provider acceptance;
- guaranteed authorization success;
- guaranteed token validity;
- refresh support;
- provider-side revoke support;
- provider-side cleanup;
- complete provider/runtime lifecycle validation.

Settings, Report Builder, and `readme.txt` continue to classify refresh
execution and provider-side revoke as deferred. Reconnect guidance remains
separate from refresh execution.

The Step 290.2 wording correctly describes the selected bounded flow without
expanding the implementation or evidence claim.

Classification:

```text
Aligned at source-documentation review level
```

## 11. Local-only Disconnect and Uninstall Cleanup Wording Result

Current source and wording continue to distinguish:

- local-only OAuth disconnect;
- provider-side revoke;
- provider-side cleanup;
- OAuth client Settings fallback deletion;
- OpenAI configuration deletion;
- deterministic plugin-owned uninstall option cleanup;
- network-wide or cross-site cleanup.

The local disconnect source deletes only the dedicated local OAuth token
option and returns a local status category. Current wording states that it
does not contact Google, revoke provider access, or delete unrelated
credential configuration.

Root `uninstall.php` deletes the deterministic plugin-owned Settings and OAuth
token option categories. It contains no provider communication path.

No wording promises network-wide cleanup, cross-site cleanup, provider-side
cleanup, refresh, or revoke.

Classification:

```text
Aligned at source-documentation review level
```

No disconnect or uninstall action was executed.

## 12. GA4 Fetch and OpenAI Generate Action Wording Result

Current public and administrator wording accurately distinguishes:

- Google OAuth authorization as a Settings action that can initiate the
  bounded authorization flow;
- Fetch GA4 Data as the administrator action that initiates Google Analytics
  Data API requests;
- Generate AI Report as the administrator action that initiates the OpenAI
  request after structured review;
- ordinary Settings and Report Builder viewing from external-service action
  execution;
- user-initiated execution from automatic or background processing.

The OAuth disclosure explicitly says that authorization is separate from
Fetch GA4 Data and does not itself retrieve GA4 report data.

The GA4 and OpenAI subsections and Report Builder disclosures remain separate.
No wording implies automatic background GA4 Fetch or OpenAI Generate.

Classification:

```text
Aligned at source-documentation review level
```

## 13. Initial Single-site Support and Multisite Boundary Result

Current public wording continues to state that:

- the initial supported scope is limited to single-site WordPress
  installations;
- multisite, network activation/deactivation, network uninstall, and
  cross-site storage or cleanup are outside the initial supported scope;
- the statement is a support-scope boundary;
- the statement does not determine whether the plugin can run in a
  particular multisite installation.

Current source and public wording do not promise network-wide cleanup or
complete network lifecycle support and do not declare multisite technically
impossible or necessarily non-functional.

Classification:

```text
Aligned at source-documentation review level
```

## 14. Alignment Matrix

| Review item | Classification | Controlled conclusion |
| --- | --- | --- |
| Version and release identity | Aligned at source-documentation review level | Header, version constant, and Stable tag are consistent at the current source level. |
| External Services action disclosure | Aligned at source-documentation review level | OAuth authorization, GA4 Fetch, and OpenAI Generate are distinct administrator-initiated actions. |
| Google OAuth authorization wording | Aligned at source-documentation review level | Redirect and validated callback-bound exchange are disclosed without implying GA4 retrieval or complete lifecycle support. |
| Settings redirect URI current-flow wording | Aligned at source-documentation review level | Targeted label/help text use present-tense setup wording and preserve field and value-rendering behavior. |
| OpenAI constant-first configuration | Aligned at source-documentation review level | Constant-based configuration remains preferred. |
| OpenAI legacy / transitional Settings fallback | Aligned at source-documentation review level | Compatibility-only fallback is not created or replaced by the normal UI and can be explicitly cleared locally. |
| OpenAI value-hidden and support/debug evidence | Aligned at source-documentation review level | Values remain hidden and support evidence remains status/category-level. |
| OAuth bounded-flow wording | Aligned at source-documentation review level | Source and wording reflect the selected redirect, callback, exchange, local storage, lifecycle, Report Builder entry, and local-disconnect boundary. |
| Refresh execution boundary | Aligned at source-documentation review level | Refresh remains explicitly deferred. |
| Provider-side revoke boundary | Aligned at source-documentation review level | Provider-side revoke remains explicitly deferred and separate from local cleanup. |
| Local-only disconnect | Aligned at source-documentation review level | Local token deletion is separated from provider and unrelated credential actions. |
| Uninstall cleanup | Aligned at source-documentation review level | Deterministic plugin-owned option cleanup is separated from provider and network cleanup. |
| GA4 Fetch action wording | Aligned at source-documentation review level | Fetch remains a separate administrator-triggered Google Analytics Data API action. |
| OpenAI Generate action wording | Aligned at source-documentation review level | Generate remains a separate administrator-triggered OpenAI action after structured review. |
| Initial single-site support / multisite boundary | Aligned at source-documentation review level | Public wording states the support scope without claiming multisite failure. |
| Final package build | Not applicable | Not performed in Step 290.3. |
| Package contents inspection | Not applicable | Not performed in Step 290.3. |
| Isolated package install validation | Not applicable | Not performed in Step 290.3. |
| Final isolated Plugin Check | Not applicable | Not performed in Step 290.3. |

No item was classified as blocked, and no follow-up wording or
source/documentation decision was identified by this recheck.

## 15. Findings Requiring Follow-up, If Any

No new public wording or source/documentation discrepancy was identified.

Step 290 findings disposition:

```text
Finding A:
Resolved at source-documentation review level

Finding B:
Resolved at source-documentation review level
```

The historical/non-selected placeholder notice wording noted in earlier OAuth
records remains outside the selected current callback/exchange result path and
does not create a new finding for this final wording gate.

Remaining work belongs to the already planned final artifact sequence, not to
another wording correction before Step 291.

## 16. Explicitly Non-executed Actions

Step 290.3 did not perform:

- `readme.txt` modification;
- production PHP, JavaScript, or CSS modification;
- Settings wording modification;
- version, Stable tag, or version-constant modification;
- package build;
- ZIP or archive creation;
- generated package contents inspection;
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

## 17. Step 290.3 Conclusion

Final public wording and release-boundary consistency recheck:

```text
Pass
```

Step 290 Finding A:

```text
Resolved at source-documentation review level
```

Step 290 Finding B:

```text
Resolved at source-documentation review level
```

New wording or source/documentation discrepancy:

```text
None identified
```

Phase 1 final public wording / release-boundary consistency:

```text
Re-established against corrected committed baseline
```

Corrected baseline status:

```text
Clean committed corrected wording baseline before addition of this Step 290.3
document
```

Final artifact gate status:

```text
Final package build: Not performed
Package contents inspection: Not performed
Isolated package install validation: Not performed
Final isolated Plugin Check: Not performed
```

## 18. Final-stage Sequence Position

Step 290.3 completes the Phase 1 final public wording and release-boundary
consistency recheck described by Step 289.

It does not itself freeze the release candidate. The next gate must record a
clean committed candidate identity before package construction begins.

Required sequence position:

```text
Step 290.3 corrected wording recheck
-> Step 291 clean committed release-candidate baseline freeze
-> release-candidate package build
-> package contents inspection
-> isolated package install validation
-> final isolated Plugin Check
-> separate final WordPress.org release decision
```

Any release-affecting change after the baseline freeze must invalidate the
affected downstream evidence and return the sequence to the earliest affected
gate.

## 19. Release Readiness Statement

```text
WordPress.org public release readiness:
Hold
```

The wording recheck is necessary but not sufficient for public release.

The Hold remains because:

- no release-candidate baseline has been frozen by Step 291;
- no final package has been built;
- package contents have not been inspected;
- the final package has not been installed in the isolated validation target;
- final isolated Plugin Check has not been run;
- no final WordPress.org release decision checkpoint has been completed.

## 20. Recommended Next Gate

Recommended next step:

```text
Step 291: Clean committed release-candidate baseline freeze
```

Step 291 should establish one clean committed candidate identity and preserve
the Phase 1 result before any package artifact is created.
