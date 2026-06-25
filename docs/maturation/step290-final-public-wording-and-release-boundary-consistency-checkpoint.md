# Step 290: Final Public Wording and Release-boundary Consistency Checkpoint

## 1. Step Purpose and Final-stage Position

Step 290 is a docs-only / review-only checkpoint immediately before a clean
release-candidate baseline is frozen.

The purpose is to compare current production source, static administrator
wording, `readme.txt`, and the latest maturation decisions for consistency
across:

- release identity;
- OpenAI configuration and value-hidden boundaries;
- Google OAuth authorization and lifecycle boundaries;
- local disconnect and uninstall cleanup;
- external-service disclosures;
- GA4 Fetch and OpenAI Generate action boundaries;
- the initial single-site public-support boundary.

This checkpoint is not a public-release approval, package approval, install
validation, or Plugin Check result.

```text
WordPress.org public release readiness:
Hold
```

## 2. Scope and Explicit Non-scope

In scope:

- read-only review of current source symbols and static wording;
- read-only review of current public wording;
- comparison with current maturation policy and bounded verification records;
- classification of source-documentation alignment;
- identification of the smallest follow-up gate required before baseline
  freeze.

Out of scope:

- any production PHP, JavaScript, or CSS change;
- any `readme.txt`, version, package tool, or `.distignore` change;
- package build or archive creation;
- package contents inspection;
- package installation, activation, deactivation, or uninstall;
- Plugin Check;
- browser, provider, OAuth, GA4, or OpenAI runtime execution;
- Settings save or local disconnect;
- database, option, credential, token, constant-value, request, response, or
  analytics-data inspection;
- release approval.

## 3. Initial Baseline Classification

Before this document was added, the following read-only commands were run:

```text
git status --short --untracked-files=all
git diff --name-only
git diff --check
git log -1 --oneline
```

The status and diff commands returned no output. Repository history showed
Step 289 committed at `HEAD`.

Initial baseline classification:

```text
Clean committed baseline
```

This classification applies to the repository state before the new Step 290
document was added.

## 4. Sensitive-information / Evidence Boundary

Review evidence was limited to:

- source file names;
- source symbols and branch categories;
- static, value-free UI wording;
- public documentation wording;
- maturation-document conclusions;
- Git command-result categories.

The review did not inspect or record:

- credential, API key, OAuth token, or refresh token values;
- option values or constant values;
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

Current release and runtime surfaces reviewed:

- `analytics-report-ai.php`;
- `readme.txt`;
- `uninstall.php`;
- `includes/class-admin.php`;
- `includes/class-settings.php`;
- `includes/class-report-builder.php`;
- `includes/functions-utils.php`.

Relevant current source boundaries included:

- plugin header and version constant declarations;
- OpenAI source-category and request-local resolver helpers;
- OAuth client-source resolution;
- OAuth Connect redirect handling;
- callback state classification;
- callback-bound authorization-code exchange;
- local OAuth token storage handoff;
- lifecycle/readiness categories;
- Report Builder credential-source entry;
- local-only disconnect;
- deterministic uninstall option cleanup.

Primary maturation records reviewed:

- Steps 239-257 for OpenAI source, wording, Settings fallback, and
  value-hidden boundaries;
- Step 271 and related OpenAI storage/uninstall records;
- Steps 274-279 for OpenAI release-boundary and initial single-site public
  wording;
- Steps 283-288 for the implemented authorization-code OAuth bounded
  validation track;
- Step 289 for final-stage release sequencing.

Historical records were treated as context only where later source,
documentation, or policy records superseded them.

## 6. Version and Release Identity Consistency Result

The plugin header Version, plugin version constant, and `readme.txt` Stable tag
were compared without changing or reproducing their values in this document.

Result:

```text
Consistent
```

Classification:

```text
Aligned at source-documentation review level
```

This result is a static identity comparison only. It is not package identity
validation because no release package was built or inspected.

## 7. OpenAI Configuration and Value-hidden Wording Consistency Result

Current source and wording consistently preserve these boundaries:

- constant-based OpenAI configuration is preferred;
- a saved Settings fallback is legacy / transitional only;
- the normal Settings UI does not create or replace the fallback;
- an existing saved fallback can be explicitly cleared locally;
- clearing the fallback does not edit constant-based configuration;
- credential values are not redisplayed;
- Settings and Report Builder expose source/readiness and value-hidden
  categories rather than credential material;
- source/readiness categories do not claim provider acceptance or request
  success;
- support/debug evidence is limited to status/category-level information.

Public wording does not need to expose every internal resolver detail. No
omission was found that makes the current OpenAI configuration or storage
wording misleading within the selected public boundary.

Classification:

```text
Aligned at source-documentation review level
```

## 8. OAuth Lifecycle / Refresh / Revoke / Local-only Disconnect Wording Result

Current source implements a bounded authorization-code flow containing:

- OAuth client-source readiness;
- browser authorization redirect;
- callback and state validation;
- callback-bound authorization-code exchange;
- local token-storage handoff;
- lifecycle/readiness categories;
- Report Builder OAuth credential-use entry;
- local-only disconnect.

Current Settings and Report Builder wording correctly state that:

- Google OAuth is the normal GA4 credential source;
- token values are hidden;
- source and lifecycle labels are category-level;
- authorization can proceed through callback-bound token exchange;
- refresh execution is deferred;
- provider-side revoke is deferred;
- local disconnect removes local token data only;
- local disconnect does not contact Google or alter unrelated credential
  categories.

The bounded lifecycle, refresh, revoke, and local-disconnect statements are
aligned. Complete provider/runtime lifecycle validation remains outside the
current bounded evidence.

Two public-wording issues remain:

1. The Settings redirect URI label and its adjacent explanation still use
   future/preparation wording even though the bounded authorization-code flow
   is currently implemented.
2. The public external-service summary does not account for the implemented
   OAuth authorization redirect and callback-bound token exchange actions.

These are wording issues, not findings that the bounded source flow itself is
missing or that refresh or provider-side revoke is implemented.

Overall classification:

```text
Needs follow-up wording alignment
```

## 9. Uninstall and Cleanup Wording Result

Current source and wording distinguish:

- local-only OAuth disconnect from provider-side revoke;
- local-only disconnect from OAuth client Settings fallback deletion;
- local-only disconnect from OpenAI configuration deletion;
- plugin uninstall cleanup from provider action;
- deterministic plugin-owned option cleanup from broader or network-wide
  cleanup.

Root `uninstall.php` deletes the deterministic plugin-owned Settings and OAuth
token option categories. It does not load a provider communication path.

Current public wording does not claim:

- provider-side revoke during disconnect or uninstall;
- refresh during disconnect or uninstall;
- complete network-wide cleanup;
- multisite uninstall support;
- cleanup of every possible runtime data category.

Classification:

```text
Aligned at source-documentation review level
```

No uninstall or cleanup action was executed.

## 10. External-service Disclosure Wording Result

The Google Analytics Data API and OpenAI API subsections identify:

- the user actions that initiate GA4 Fetch and OpenAI Generate;
- the relevant external services;
- category-level data sent and received;
- credential placement categories;
- data categories intentionally excluded from the respective request bodies.

The top-level External Services statement currently says third-party services
are used only when an administrator starts a report action. That statement is
too narrow for the current source because the separate Settings action for
Google OAuth authorization can:

- redirect the browser to Google; and
- after a valid callback, initiate an authorization-code token exchange.

Ordinary screen viewing remains non-transmitting, but the public wording does
not currently identify the separate user-initiated OAuth action boundary.
This creates an action-disclosure omission and makes the broad "report action"
statement incomplete.

Classification:

```text
Needs follow-up wording alignment
```

The follow-up should remain category-level and should not reproduce
authorization URLs, callback values, client values, tokens, request bodies, or
provider responses.

## 11. GA4 Fetch and OpenAI Generate Wording Result

The current wording accurately distinguishes:

- `Fetch GA4 Data` as the administrator action that initiates Google Analytics
  Data API requests;
- `Generate AI Report` as the administrator action that initiates the OpenAI
  request;
- structured pre-send review before OpenAI generation;
- ordinary Settings or Report Builder viewing from these external requests;
- user-initiated execution from automatic background processing.

No wording was found that implies automatic background GA4 Fetch or OpenAI
Generate execution.

Classification:

```text
Aligned at source-documentation review level
```

This result does not cover the separate OAuth authorization action identified
in the external-service disclosure finding.

## 12. Initial Single-site Support and Multisite Boundary Result

Current public wording states that:

- the initial supported scope is single-site WordPress;
- multisite, network activation/deactivation, network uninstall, and
  cross-site storage or cleanup are outside the initial supported scope;
- the statement is a support-scope boundary;
- the statement does not claim that the plugin cannot run in a particular
  multisite installation.

Current uninstall and storage wording does not promise network-wide cleanup or
multisite lifecycle support.

Classification:

```text
Aligned at source-documentation review level
```

## 13. Alignment Matrix

| Review item | Classification | Controlled conclusion |
| --- | --- | --- |
| Version and release identity | Aligned at source-documentation review level | Header, constant, and Stable tag are consistent at the current source level. |
| OpenAI constant-first configuration | Aligned at source-documentation review level | Preferred constant source and provider-readiness non-claim are consistent. |
| OpenAI legacy / transitional Settings fallback | Aligned at source-documentation review level | Normal UI does not create or replace it; existing fallback is hidden and locally clearable. |
| OpenAI value-hidden and support evidence | Aligned at source-documentation review level | Values remain hidden and support evidence remains status/category-level. |
| OAuth client-source and bounded flow description | Aligned at source-documentation review level | Source and most static wording describe the implemented bounded flow accurately. |
| Settings OAuth redirect URI preparation wording | Needs follow-up wording alignment | Future/preparation phrasing is stale relative to the implemented bounded flow. |
| Refresh execution boundary | Aligned at source-documentation review level | Refresh remains explicitly deferred. |
| Provider-side revoke boundary | Aligned at source-documentation review level | Revoke remains explicitly deferred and separate from local cleanup. |
| Local-only disconnect | Aligned at source-documentation review level | Local token deletion is separated from provider and unrelated credential actions. |
| Uninstall cleanup | Aligned at source-documentation review level | Deterministic plugin-owned option cleanup is separated from provider and network cleanup. |
| External-service top-level disclosure | Needs follow-up wording alignment | Report-action-only wording omits the separate user-initiated OAuth redirect and token-exchange boundary. |
| GA4 Fetch action wording | Aligned at source-documentation review level | Fetch is a distinct administrator-triggered Google Analytics Data API action. |
| OpenAI Generate action wording | Aligned at source-documentation review level | Generate is a distinct administrator-triggered OpenAI action after structured review. |
| Initial single-site support boundary | Aligned at source-documentation review level | Public wording states the support scope without declaring multisite failure. |
| Final package build | Not applicable | Not performed in Step 290. |
| Package contents inspection | Not applicable | Not performed in Step 290. |
| Isolated package install validation | Not applicable | Not performed in Step 290. |
| Final isolated Plugin Check | Not applicable | Not performed in Step 290. |

No review item was blocked by baseline or evidence limitation.

## 14. Findings Requiring Follow-up

### Finding A: External-service Action Disclosure

Current public wording limits third-party service use to a report action, but
current source also contains a separate administrator-triggered OAuth
authorization action with a browser redirect and callback-bound token
exchange.

Required follow-up boundary:

- state that viewing the Settings or Report Builder screens alone does not
  contact Google or OpenAI;
- distinguish the user-initiated OAuth authorization action from GA4 Fetch;
- disclose at a category level that OAuth authorization can redirect to Google
  and that a validated callback can initiate token exchange;
- preserve the separate GA4 Fetch and OpenAI Generate disclosures;
- preserve refresh and provider-side revoke as deferred;
- do not include sensitive values, generated URLs, raw requests, or raw
  responses.

### Finding B: Stale Future/preparation Wording in Settings

The redirect URI label and adjacent help text still frame OAuth setup/support
as future preparation. The bounded authorization-code flow is now implemented,
so that wording can misrepresent the current source state.

Required follow-up boundary:

- replace only the stale future/preparation framing;
- preserve the redirect URI setup purpose;
- avoid claiming complete provider lifecycle validation;
- preserve value-hidden, refresh-deferred, revoke-deferred, and local-only
  disconnect wording.

The two findings belong to one narrow OAuth public-wording alignment track.
Step 290 does not implement the correction.

## 15. Explicitly Non-executed Actions

Step 290 did not perform:

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
- credential, token, option, or constant-value inspection;
- commit;
- push.

## 16. Step 290 Conclusion

Public wording / release-boundary alignment status:

```text
Needs follow-up wording alignment
```

Most reviewed boundaries are aligned at the source-documentation review
level. A narrow OAuth wording follow-up is required because:

- the top-level external-service disclosure omits the user-initiated OAuth
  redirect and callback-bound token-exchange action boundary; and
- two Settings strings retain stale future/preparation framing.

Final artifact gate status:

```text
Final package build: Not performed
Package contents inspection: Not performed
Isolated package install validation: Not performed
Final isolated Plugin Check: Not performed
```

The clean initial baseline was suitable for review, but a release-candidate
baseline must not be frozen until the identified wording follow-up is
completed, verified, and committed.

## 17. Release Readiness Statement

```text
WordPress.org public release readiness:
Hold
```

Step 290 does not authorize package creation, package installation, isolated
Plugin Check, or public release.

The Hold remains because:

- the identified OAuth public-wording alignment is incomplete;
- no final release package exists for the candidate;
- package contents have not been inspected;
- the final package has not been installed in the isolated validation target;
- final isolated Plugin Check has not been run;
- no final WordPress.org release decision checkpoint has been completed.

## 18. Recommended Next Gate

Recommended next step:

```text
Step 290.1: Google OAuth external-service and current-flow wording narrow
alignment plan
```

The plan should define the smallest `readme.txt` and Settings wording changes
needed to:

- include the user-initiated OAuth external-service action boundary;
- remove stale future/preparation framing;
- preserve current single-site, value-hidden, refresh-deferred,
  revoke-deferred, local-disconnect, GA4 Fetch, and OpenAI Generate boundaries.

After the narrow wording change is implemented, source/documentation wording
is reverified, and the resulting changes are committed, the sequence may
return to:

```text
Step 291: Clean committed release-candidate baseline freeze
```
