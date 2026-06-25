# Step 290.2: Google OAuth External-service and Current-flow Wording Narrow Implementation Results

## 1. Step Purpose and Relation to Steps 290 / 290.1

Step 290.2 implements the two narrow wording corrections identified by Step
290 and planned by Step 290.1:

- expand the top-level External Services disclosure so that it does not limit
  third-party communication to report actions;
- remove stale future/preparation framing from the Settings redirect URI
  label and its directly associated help text.

The implementation is wording-only. It does not expand OAuth implementation
or verification scope.

## 2. Initial Baseline and Predecessor Gate Result

The following read-only checks were completed before editing:

```text
git status --short --untracked-files=all
git diff --name-only
git diff --check
git log -1 --oneline
git ls-files --error-unmatch <Step 290 document>
git ls-files --error-unmatch <Step 290.1 document>
```

Results:

- the working tree was clean;
- Step 290 was tracked and committed;
- Step 290.1 was tracked and committed;
- repository `HEAD` contained the Step 290.1 plan;
- no tracked or untracked predecessor change was present.

Baseline classification:

```text
Clean committed implementation baseline
```

Predecessor gate result:

```text
Passed
```

## 3. Scope and Explicit Non-scope

Implementation scope:

- top-level External Services wording in `readme.txt`;
- one concise Google OAuth Authorization disclosure in `readme.txt`;
- the Settings redirect URI label;
- the directly associated Settings redirect URI setup help text;
- this implementation-results document.

Explicitly outside the implementation:

- OAuth redirect, callback, state-validation, exchange, or storage logic;
- lifecycle category logic;
- refresh execution;
- provider-side revoke or cleanup;
- GA4 Fetch behavior;
- OpenAI Generate behavior;
- credential storage or source resolution;
- local-only disconnect;
- uninstall behavior;
- multisite behavior;
- version or Stable tag;
- package or build tooling;
- JavaScript or CSS.

## 4. Sensitive-information / Evidence Boundary

Implementation and verification evidence was limited to:

- file paths;
- static wording;
- translation and escaping patterns;
- source category boundaries;
- syntax and Git command-result categories.

No credential, token, OAuth client, option, constant, authorization URL,
redirect URI value, callback value, authorization code, request/response
material, payload, analytics value, generated report text, database content,
screenshot, or browser Network evidence was inspected or recorded.

## 5. Changed Files

Modified:

- `readme.txt`;
- `includes/class-settings.php`.

Added:

- `docs/maturation/step290-2-google-oauth-external-service-and-current-flow-wording-narrow-implementation-results.md`.

No other file is part of this implementation.

## 6. readme.txt External-service Wording Change

The top-level External Services statement now distinguishes three explicit
administrator-initiated actions:

- Google OAuth authorization;
- Fetch GA4 Data;
- Generate AI Report.

The statement continues to clarify that viewing Settings or Report Builder
alone does not contact Google or OpenAI.

A concise Google OAuth Authorization subsection now explains, at category
level, that:

- starting authorization can redirect the browser to Google;
- after the browser returns, the plugin can attempt a callback-bound
  authorization-code exchange only after callback state validation;
- OAuth authorization is separate from Fetch GA4 Data;
- OAuth authorization does not itself retrieve GA4 report data;
- refresh execution and provider-side revoke remain deferred.

The existing Google Analytics Data API and OpenAI API subsections remain
separate and were not redesigned.

## 7. Settings Redirect URI Wording Change

The Settings redirect URI label now uses present-tense Google OAuth setup
wording.

The directly associated help text now explains that:

- the redirect URI is used for Google OAuth client setup;
- the displayed value should be copied into the Google OAuth client
  configuration used by the site.

The changes preserve:

- the `analytics-report-ai` text domain;
- the existing `esc_html__()` output pattern;
- the current HTML structure;
- the read-only field behavior;
- the existing value-rendering boundary.

No other Settings string was changed.

## 8. Boundaries Explicitly Preserved

The implementation preserves these boundaries:

- Google OAuth authorization, GA4 Fetch, and OpenAI Generate are separate
  administrator-initiated actions;
- ordinary Settings and Report Builder viewing does not contact Google or
  OpenAI;
- OAuth authorization can redirect the browser to Google;
- authorization-code exchange remains callback-bound and follows state
  validation;
- OAuth authorization does not retrieve GA4 report data;
- no automatic background communication is implied;
- token and credential values remain hidden;
- refresh execution remains deferred;
- provider-side revoke remains deferred;
- local-only disconnect remains separate from provider-side revoke and
  provider cleanup;
- uninstall cleanup remains unchanged;
- the initial public support scope remains single-site only;
- OpenAI constant-preferred, legacy/transitional fallback, and value-hidden
  policies remain unchanged;
- complete OAuth provider/runtime lifecycle support is not claimed.

## 9. Static Verification Performed

Static verification included:

- PHP syntax validation for `includes/class-settings.php`;
- targeted review of the changed External Services wording;
- targeted review of the two Settings strings;
- confirmation that the three external-service actions remain distinct;
- confirmation that screen-viewing non-transmission wording remains present;
- confirmation that redirect and callback-bound exchange wording is
  category-level;
- confirmation that GA4 Fetch and OpenAI Generate subsections remain
  independent;
- confirmation that refresh and provider-side revoke remain deferred;
- confirmation that local-only disconnect wording remains intact;
- confirmation that initial single-site support wording remains intact;
- confirmation that OpenAI storage/value-hidden wording remains intact;
- file-scope and Git diff checks.

No runtime action was part of verification.

## 10. Static Verification Results

The final verification records:

```text
External Services action distinction:
Pass

Ordinary screen-viewing non-transmission wording:
Pass

OAuth browser redirect disclosure:
Pass

Validated callback-bound authorization-code exchange disclosure:
Pass

OAuth authorization separation from GA4 Fetch:
Pass

Settings stale future/preparation wording removal:
Pass

Settings redirect URI setup purpose:
Pass

Refresh deferred boundary:
Pass

Provider-side revoke deferred boundary:
Pass

Local-only disconnect wording preservation:
Pass

Initial single-site support wording preservation:
Pass

OpenAI storage/value-hidden wording preservation:
Pass

PHP syntax:
Pass

Git diff check:
Pass

Allowed file scope:
Pass
```

No prohibited value or raw protocol material was introduced.

## 11. Explicitly Non-executed Actions

Step 290.2 did not perform:

- package build;
- ZIP or archive creation;
- package contents inspection;
- package installation;
- plugin activation, deactivation, or uninstall;
- Plugin Check;
- browser OAuth or browser redirect execution;
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

## 12. Step 290.2 Conclusion

Baseline gate result:

```text
Passed
```

Google OAuth external-service and current-flow wording narrow implementation:

```text
Completed
```

Static verification result:

```text
Passed
```

Runtime behavior change status:

```text
No OAuth, token storage, GA4, OpenAI, uninstall, multisite, version, or
package-tool behavior changed
```

Step 290 findings:

```text
Implemented wording correction pending Step 290.3 recheck
```

Step 290.3:

```text
Required
```

Final artifact gate status:

```text
Final package build: Not performed
Package contents inspection: Not performed
Isolated package install validation: Not performed
Final isolated Plugin Check: Not performed
```

## 13. Release Readiness Statement

```text
WordPress.org public release readiness:
Hold
```

Step 290.2 does not freeze a release-candidate baseline, validate a release
package, run isolated Plugin Check, or authorize public release.

## 14. Required Step 290.3 Recheck

Step 290.3 must perform the final public wording and release-boundary
consistency recheck against this corrected working state.

At minimum, it must recheck:

- External Services disclosure;
- Settings OAuth wording;
- bounded OAuth scope;
- refresh and provider-revoke deferred boundaries;
- local-only disconnect;
- GA4 Fetch and OpenAI Generate action boundaries;
- OpenAI storage/value-hidden policy;
- uninstall cleanup;
- initial single-site support wording;
- version and release identity consistency.

Step 290.3 does not replace package build, package contents inspection,
isolated package install validation, final isolated Plugin Check, or the final
WordPress.org release decision.

## 15. Recommended Next Gate

Recommended next step:

```text
Step 290.3: Final public wording and release-boundary consistency recheck
```
