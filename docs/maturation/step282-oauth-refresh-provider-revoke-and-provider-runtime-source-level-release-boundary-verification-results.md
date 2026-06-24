# Step 282: OAuth Refresh, Provider-side Revoke, and Provider-runtime Source-level Release-boundary Verification Results

## Step Objective and Verification Limits

Step 282 performs the docs-only / verification-only follow-up selected by Step
281.

The objective is to verify the current source-level boundaries for:

- OAuth authorization-code exchange;
- refresh execution;
- provider-side revoke;
- local-only disconnect;
- uninstall cleanup;
- token lifecycle categories;
- reconnect-required, refresh-deferred, and revoke-deferred wording;
- provider/runtime non-inference.

This verification reviews source structure, symbols, branches, external-request
API categories, and static wording only. It does not execute source behavior or
establish provider outcomes.

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

Repository history confirmed that Step 281 was committed at the baseline.
There were no pre-existing source, public-documentation, maturation-document,
tool, or environment changes.

## Evidence Sources and Evidence-level Boundary

Current source and public wording inspected:

- `includes/class-admin.php`;
- `includes/class-settings.php`;
- `includes/functions-utils.php`;
- `includes/class-report-builder.php`;
- `includes/class-ga4-client.php`;
- `includes/class-openai-client.php`;
- `uninstall.php`;
- `readme.txt`.

Current boundary records inspected:

- `docs/maturation/step205-oauth-token-lifecycle-source-level-verification-results.md`;
- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`;
- `docs/maturation/step215-uninstall-cleanup-maturation-checkpoint.md`;
- `docs/maturation/step223-manual-google-access-token-fallback-retirement-maturation-checkpoint.md`;
- `docs/maturation/step227-readme-privacy-wording-alignment-after-manual-token-retirement-maturation-checkpoint.md`;
- `docs/maturation/step280-wordpress-org-hold-gate-prioritization-checkpoint.md`;
- `docs/maturation/step281-oauth-lifecycle-remaining-hold-gate-release-boundary-planning-checkpoint.md`.

Supporting implementation and category-level records:

- `docs/maturation/step145-narrow-token-exchange-production-implementation-results.md`;
- `docs/maturation/step147-human-controlled-token-exchange-smoke-results.md`;
- `docs/maturation/step152-narrow-ga4-oauth-credential-source-production-implementation-results.md`;
- `docs/maturation/step153-ga4-oauth-credential-source-source-level-verification-results.md`;
- `docs/maturation/step198-oauth-client-configuration-hybrid-source-final-maturation-checkpoint.md`.

Permitted evidence:

- source file and symbol names;
- action and method categories;
- branch presence or absence;
- WordPress API family usage;
- static status/category wording;
- docs-level boundary decisions;
- command-result categories.

Excluded evidence:

- credential, API key, or OAuth token values;
- option or constant values;
- Authorization headers;
- request or response bodies;
- payload JSON;
- generated report text;
- actual analytics values;
- screenshots or browser Network evidence;
- database content;
- provider responses.

Source review can identify whether a request category or control-flow branch is
present. It cannot establish provider authorization, token validity, provider
response behavior, refresh or revoke outcomes, or complete provider lifecycle
behavior.

## OAuth External-request Path Inventory

### Authorization Redirect Category

`Analytics_Report_AI_Admin::handle_google_oauth_connect()`:

- applies the administrative capability and nonce boundaries;
- resolves OAuth client configuration;
- prepares local state;
- constructs the authorization redirect target;
- delegates to a browser redirect helper.

The connect action does not call token exchange, refresh, provider-side revoke,
or local disconnect.

Controlled conclusion:

```text
Source-level release-boundary alignment observed
```

### Authorization-code Token Exchange Category

`Analytics_Report_AI_Admin::handle_google_oauth_callback()`:

- classifies callback state and provider-error/code-presence categories;
- invokes token exchange only for the valid-state and code-present category;
- redirects to Settings with a category-level result.

`Analytics_Report_AI_Admin::exchange_google_oauth_authorization_code_for_tokens()`:

- resolves the current OAuth client source;
- calls the dedicated authorization-code exchange helper;
- stores token material only after the exchange-success category;
- returns category-level results.

`Analytics_Report_AI_Admin::request_google_oauth_tokens()` is the current
OAuth server-side WordPress HTTP API request path. Its source branch is
specific to authorization-code exchange.

Controlled conclusion:

```text
Source-level release-boundary alignment observed
```

Non-inference boundary:

The presence of this source path does not establish current provider
authorization, token validity, token endpoint results, or complete callback
runtime behavior.

### Refresh Request Category

Repository-wide targeted inspection found:

- storage and classification references for optional refresh-token material;
- no refresh-specific action, callback, method, helper, or execution branch;
- no refresh-specific token endpoint request construction;
- no refresh response handling or token-replacement branch;
- no retry path;
- no refresh-before-GA4-Fetch path.

Refresh-token presence is used by
`analytics_report_ai_get_google_oauth_token_lifecycle_categories()` to select
status categories. It does not trigger an external request.

Controlled conclusion:

```text
Source-level release-boundary alignment observed
```

### Provider-side Revoke Category

Repository-wide targeted inspection found:

- provider-revoke deferred categories and static wording;
- no revoke-specific action, callback, method, helper, or execution branch;
- no revoke endpoint request construction;
- no revoke communication or response handling;
- no provider token-invalidation or revoke retry path.

Controlled conclusion:

```text
Source-level release-boundary alignment observed
```

### Other WordPress HTTP API Categories

The repository also contains WordPress HTTP API calls for:

- GA4 report retrieval in `Analytics_Report_AI_GA4_Client`;
- OpenAI report generation in `Analytics_Report_AI_OpenAI_Client`.

Those calls are separate report-service client paths. Their existence does not
create an OAuth refresh or provider-side revoke path.

Controlled conclusion:

```text
Source-level release-boundary alignment observed
```

### Local-only Disconnect and Uninstall Categories

Local disconnect calls the dedicated OAuth token deletion helper and returns a
category-level Settings result. It does not use a remote-request API.

Root `uninstall.php` uses deterministic local option deletion after the
standard uninstall guard. It does not load an OAuth provider request path.

Controlled conclusion:

```text
Source-level release-boundary alignment observed
```

## Refresh Execution Boundary Verification

### Current Source Finding

No refresh execution path was found in the reviewed current source.

Specifically:

- the token lifecycle helper classifies local token state only;
- refresh-token material, when present, affects lifecycle categories only;
- an expired state with refresh-token presence produces
  refresh-needed/deferred categories;
- an expired state without refresh-token presence produces
  reconnect-required/refresh-unavailable categories;
- the GA4 credential resolver returns no runtime access credential when the
  OAuth lifecycle is not connected;
- Report Builder returns category-level reconnect guidance when no usable
  credential is resolved;
- GA4 Fetch proceeds to the GA4 client only after a non-empty runtime
  credential is resolved;
- no branch attempts refresh before GA4 Fetch.

Static Settings, Report Builder, and readme wording describe refresh execution
as deferred. That wording matches the reviewed source boundary.

Controlled conclusion:

```text
Source-level release-boundary alignment observed
```

### Non-inference Boundary

This source finding does not establish:

- whether refresh would succeed at the provider;
- token endpoint runtime behavior;
- token validity;
- provider authorization;
- complete expired-token behavior across every runtime environment.

Provider and runtime behavior remain:

```text
Deferred / separate provider-runtime gate
```

## Provider-side Revoke Boundary Verification

### Current Source Finding

No provider-side revoke execution path was found in the reviewed current
source.

Specifically:

- no revoke-specific WordPress action is registered;
- no revoke helper or method is present;
- no revoke endpoint request is constructed;
- no provider-side revoke communication or response handler is present;
- no provider-side cleanup or retry branch is present;
- the lifecycle helper assigns a deferred provider-revoke category;
- Settings and readme wording distinguish provider revoke from local
  disconnect and uninstall.

Controlled conclusion:

```text
Source-level release-boundary alignment observed
```

### Non-inference Boundary

This source finding does not establish:

- provider-side revoke success;
- provider account cleanup;
- provider-side token invalidation;
- provider response behavior.

Provider and runtime behavior remain:

```text
Deferred / separate provider-runtime gate
```

## Authorization-code Exchange Versus Refresh Distinction

The current authorization-code exchange path is bound to:

- the OAuth callback action;
- local state validation;
- provider-error absence;
- authorization-code presence;
- a dedicated authorization-code exchange helper;
- category-level exchange and storage outcomes.

The reviewed token endpoint request branch is specific to authorization-code
exchange. No branch changes that request into a refresh operation, and no
refresh-specific helper reuses it.

The Connect action prepares authorization and browser redirection only. The
callback action can enter authorization-code exchange after its preconditions.
Neither action directly starts refresh or provider-side revoke.

Controlled conclusion:

```text
Source-level release-boundary alignment observed
```

Non-inference boundary:

This verifies source control-flow distinction only. It does not establish
authorization-code exchange success, current provider acceptance, token
validity, or provider lifecycle completion.

## Local-only Disconnect Separation Verification

`Analytics_Report_AI_Admin::handle_google_oauth_disconnect()`:

- requires the administrative capability;
- verifies the disconnect nonce;
- calls `analytics_report_ai_delete_google_oauth_tokens()`;
- redirects with a category-level local result.

`analytics_report_ai_delete_google_oauth_tokens()`:

- checks only the dedicated local OAuth token storage category;
- deletes only the dedicated local OAuth token option;
- does not call the OAuth token endpoint;
- does not call provider-side revoke;
- does not delete OAuth client Settings fallback configuration;
- does not delete or alter OpenAI configuration.

Static Settings notices and descriptions preserve the same local-only
boundary.

Controlled conclusion:

```text
Source-level release-boundary alignment observed
```

Non-inference boundary:

Local deletion does not establish provider-side token invalidation or provider
account cleanup.

## Uninstall and Provider Lifecycle Separation Verification

Root `uninstall.php`:

- checks the standard `WP_UNINSTALL_PLUGIN` guard;
- deletes the main plugin Settings option;
- deletes the dedicated OAuth token option;
- contains no provider request construction;
- contains no refresh, revoke, token endpoint, or external HTTP path;
- does not load the runtime plugin bootstrap.

No network iteration or network-option cleanup is present. Multisite and
network cleanup remain outside this verification and the initial support
scope.

Current public wording states that uninstall cleanup is a separate
plugin-owned option cleanup boundary and does not mean provider-side revoke.

Controlled conclusion:

```text
Source-level release-boundary alignment observed
```

Non-inference boundary:

The reviewed source does not establish provider-side cleanup, complete network
cleanup, or universal database cleanup.

## Lifecycle Categories and Static Wording Alignment

### Lifecycle Category Calculation

`analytics_report_ai_get_google_oauth_token_lifecycle_categories()` returns
category fields for:

- OAuth connection status;
- token lifecycle status;
- refresh status;
- disconnect status;
- provider-revoke status;
- local storage status.

The helper reads request-local option data for classification and does not call
an external endpoint. It does not display credential material.

Controlled conclusion:

```text
Source-level release-boundary alignment observed
```

### Reconnect-required Boundary

When the lifecycle is not usable, the GA4 credential resolver withholds the
runtime credential and returns a refresh-needed or error category. Report
Builder converts those categories into reconnect guidance rather than
attempting refresh.

Controlled conclusion:

```text
Source-level release-boundary alignment observed
```

### Refresh-deferred Wording

Settings, Report Builder, and `readme.txt` describe refresh requests as
deferred rather than implemented. No contradicting refresh execution path was
found.

Controlled conclusion:

```text
Source-level release-boundary alignment observed
```

### Provider-revoke-deferred Wording

Settings and `readme.txt` describe provider-side revoke as separate from
local-only disconnect and uninstall. No contradicting revoke execution path
was found.

Controlled conclusion:

```text
Source-level release-boundary alignment observed
```

## Verification Summary Table

| Verification topic | Current source / wording evidence surface | Source-level finding | Controlled conclusion | Non-inference / escalation boundary |
| --- | --- | --- | --- | --- |
| OAuth authorization-code exchange path | `includes/class-admin.php` callback classification, exchange helper, request helper, storage handoff | One OAuth server-side HTTP request category exists for callback-bound authorization-code exchange. | Source-level release-boundary alignment observed | Does not establish provider acceptance, token validity, or current runtime outcome. |
| OAuth refresh execution path | `includes/class-admin.php`; `includes/functions-utils.php`; Report Builder credential resolution | No refresh-specific helper, action, request construction, response handling, retry, replacement, or before-fetch path was found. | Source-level release-boundary alignment observed | Refresh outcome and token endpoint behavior remain a Deferred / separate provider-runtime gate. |
| OAuth provider-side revoke path | Admin actions, lifecycle helper, Settings wording, readme wording | No revoke-specific helper, action, request construction, communication, response handling, or retry path was found. | Source-level release-boundary alignment observed | Provider revoke outcome and provider cleanup remain a Deferred / separate provider-runtime gate. |
| Callback / redirect separation from refresh and revoke | Connect and callback handlers in `includes/class-admin.php` | Connect handles authorization redirect; callback may enter authorization-code exchange. Neither directly starts refresh or revoke. | Source-level release-boundary alignment observed | Source distinction does not establish provider/runtime outcome. |
| Local-only disconnect boundary | Disconnect handler, local token deletion helper, Settings notices | Disconnect deletes only the dedicated local OAuth token option and uses a category-level result; no remote-request API is called. | Source-level release-boundary alignment observed | Does not invalidate provider-side access or establish provider cleanup. |
| Uninstall / provider lifecycle separation | `uninstall.php`; readme cleanup wording | Guarded deterministic local option deletion only; no refresh, revoke, token endpoint, provider cleanup, or external HTTP path. | Source-level release-boundary alignment observed | Network cleanup and provider cleanup are not inferred. |
| Lifecycle category and reconnect-required boundary | Lifecycle category helper, GA4 credential resolver, Report Builder error wording | Lifecycle classification withholds unusable credentials and routes to reconnect guidance without external refresh. | Source-level release-boundary alignment observed | Does not establish token validity or complete expired-token runtime behavior. |
| Refresh-deferred wording alignment | Settings, Report Builder, `readme.txt` | Static wording describes refresh as deferred and matches the absence of a refresh execution path. | Source-level release-boundary alignment observed | Whether deferral is acceptable for initial release requires a separate decision. |
| Provider-revoke-deferred wording alignment | Settings and `readme.txt` | Static wording separates provider revoke from local disconnect/uninstall and matches the absence of a revoke path. | Source-level release-boundary alignment observed | Whether deferral is acceptable for initial release requires a separate decision. |
| OAuth external-request path inventory | Repository-wide WordPress HTTP API and OAuth action search | OAuth browser authorization redirect and authorization-code token exchange are present; refresh and revoke request paths are absent. GA4 and OpenAI HTTP clients are separate service paths. | Source-level release-boundary alignment observed | Request path presence or absence cannot establish provider responses or complete runtime behavior. |
| Provider/runtime evidence limitation | Current source and maturation evidence boundary | Source review can verify control-flow categories only. | Deferred / separate provider-runtime gate | Provider authorization, refresh/revoke outcomes, token endpoint behavior, and provider-side cleanup require separately authorized evidence if selected. |

## Overall Conclusion and Remaining Hold Classification

Overall verification disposition:

```text
Source-level release-boundary alignment observed
```

The reviewed current source aligns with the established boundary:

- authorization-code exchange exists as a callback-bound path;
- no refresh execution path was found;
- no provider-side revoke path was found;
- local disconnect remains local-only;
- uninstall remains deterministic local option cleanup;
- lifecycle categories lead to reconnect guidance rather than refresh
  execution;
- refresh and provider-side revoke wording remain deferred;
- no source/wording contradiction requiring alignment work was identified.

Remaining classifications:

```text
Refresh execution:
Deferred / separate provider-runtime gate

Provider-side revoke:
Deferred / separate provider-runtime gate

Complete OAuth provider/runtime lifecycle evidence:
Deferred / separate provider-runtime gate

WordPress.org public release readiness:
Hold
```

This verification does not decide whether refresh execution or provider-side
revoke is required for the initial public-release boundary.

## Explicit Non-claims and Excluded Evidence

Step 282 does not determine or prove:

- OAuth provider authorization;
- token validity;
- authorization-code exchange success in the current environment;
- refresh success;
- revoke success;
- token endpoint runtime behavior;
- provider response behavior;
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

Step 282 did not inspect, display, record, or share:

- credentials;
- API keys;
- OAuth tokens;
- option values;
- constant values;
- Authorization headers;
- request or response bodies;
- payload JSON;
- generated report text;
- actual analytics values;
- screenshots;
- browser Network evidence;
- database content;
- provider responses.

Step 282 did not execute:

- browser OAuth or redirects;
- OAuth callbacks;
- authorization-code exchange;
- token refresh;
- provider-side revoke;
- token endpoint communication;
- local disconnect;
- plugin uninstall;
- Settings save;
- GA4 Fetch;
- OpenAI Generate;
- Plugin Check;
- external HTTP or provider communication.

## Public Release Implication

```text
WordPress.org public release readiness remains Hold.
```

Step 282 verifies current source-level OAuth lifecycle boundaries only. It
does not authorize release, execute or validate provider behavior, establish
refresh or revoke success, certify storage, determine legal, privacy-law, or
WordPress.org policy compliance, or replace final package, isolated Plugin
Check, or release validation.

## Recommended Next Step

```text
Step 283 candidate —
OAuth refresh and provider-side revoke initial public-release
disposition decision checkpoint
```

Step 283 should remain docs-only / decision-only. It should decide whether
refresh execution and provider-side revoke are implementation requirements for
the selected initial public-release boundary or can remain explicitly
deferred, and whether provider/runtime verification is an initial-release gate
or a future implementation gate.

Step 283 must not implement or execute refresh, revoke, OAuth provider
requests, token endpoint communication, browser OAuth, Settings save, local
disconnect, GA4 Fetch, OpenAI Generate, or Plugin Check.

## Result Classification

```text
OAuth refresh, provider-side revoke, and provider-runtime source-level
release-boundary verification:
Source-level release-boundary alignment observed

WordPress.org public release readiness:
Hold
```
