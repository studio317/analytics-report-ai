# Step 272: OpenAI Legacy / Transitional Fallback Multisite and Uninstall Source-level Release-boundary Results

## Step Objective and Verification Limits

Step 272 performs a docs-only / verification-only review of the current
multisite, network activation, option-storage, OpenAI legacy / transitional
Settings fallback, and uninstall source boundaries.

The review is limited to repository source and existing maturation documents.
It does not execute multisite, activation, Settings save, fallback removal, or
uninstall behavior. Findings describe only control flow and API categories
visible in the current source.

WordPress.org public release readiness remains:

```text
Hold
```

## Working-tree Baseline Classification

The following commands were run before this results document was added:

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

Step 271 was present in the baseline and no uncommitted production-facing or
documentation changes were present.

## Read-only Inspection Scope

Source files inspected:

- `analytics-report-ai.php`
- `includes/class-plugin.php`
- `includes/functions-utils.php`
- `includes/class-settings.php`
- `includes/class-report-builder.php`
- `includes/class-openai-client.php`
- `uninstall.php`

Primary documents inspected:

- `docs/maturation/step271-openai-legacy-transitional-fallback-multisite-and-uninstall-source-level-release-boundary-plan.md`
- `docs/maturation/step270-openai-legacy-transitional-fallback-storage-migration-and-uninstall-release-boundary-decision-checkpoint.md`
- `docs/maturation/step214-uninstall-cleanup-source-level-verification-results.md`
- `docs/maturation/step215-uninstall-cleanup-maturation-checkpoint.md`

Supporting uninstall policy and implementation documents from Steps 210-213
were searched for the existing single-site and deferred multisite boundaries.

Permitted evidence was limited to file names, symbols, option key names, API
families, branch presence or absence, and status/category-level conclusions.
No stored value or runtime deployment state was inspected.

## A. Activation and Network Scope Findings

### Source Findings

- `analytics-report-ai.php` registers
  `Analytics_Report_AI_Plugin::activate()` with
  `register_activation_hook()`.
- `Analytics_Report_AI_Plugin::activate()` accepts a `$network_wide`
  parameter.
- The callback comment states that multisite network activation is outside the
  MVP support scope.
- The callback immediately discards `$network_wide` and then calls the default
  Settings option creation helper.
- No `is_multisite()`, `is_network_admin()`, network-wide branch, site
  iteration, or explicit network-activation rejection was found in the
  reviewed production source.

### Source-level Conclusion

The activation hook and callback are source-confirmed, but the callback does
not use the network context to implement per-site initialization, explicit
network handling, or explicit rejection.

Classification:

```text
Source-confirmed single-site boundary
+
Deferred / separate release gate
```

This review does not classify actual network activation as Pass, safe,
supported, or complete.

## B. Option-storage Scope Findings

### Main Settings Option

- The option key is `analytics_report_ai_settings`.
- `analytics_report_ai_get_settings()` reads it with `get_option()`.
- activation-time default creation checks it with `get_option()` and creates
  it with `add_option()`.
- the Settings screen registers the same option with `register_setting()` and
  a sanitization callback.
- uninstall deletes the option with `delete_option()`.
- no `get_site_option()`, `add_site_option()`, `update_site_option()`, or
  `delete_site_option()` path was found for this option.
- no explicit multisite branch was found around this storage path.

Classification:

```text
Source-confirmed single-site boundary
+
Deferred / separate release gate
```

The use of the site-level option API does not establish actual per-site
behavior across a multisite deployment.

### Dedicated OAuth Token Option

- The option key is `analytics_report_ai_oauth_tokens`.
- token storage and lifecycle helpers use `get_option()`, `add_option()`,
  `update_option()`, and `delete_option()`.
- uninstall deletes the option with `delete_option()`.
- no site-option API or explicit multisite branch was found around this
  storage path.

Classification:

```text
Source-confirmed single-site boundary
+
Deferred / separate release gate
```

No token value, option value, or provider state was inspected.

## C. Constant and Multisite Scope Findings

### Source Findings

- `analytics_report_ai_get_openai_api_key_constant_name()` identifies
  `ANALYTICS_REPORT_AI_OPENAI_API_KEY` as the preferred constant symbol.
- `analytics_report_ai_get_openai_api_key_source()` checks the constant source
  before the Settings fallback.
- `analytics_report_ai_resolve_openai_api_key()` also resolves the constant
  before returning the Settings fallback.
- source categories remain ordered as:

```text
constant_configured -> settings_saved -> missing
```

- no multisite-specific branch was found in the constant-name helper, source
  category helper, or request-local resolver.

### Source-level Conclusion

Constant-first resolution is source-confirmed. The reviewed plugin source does
not define whether a deployment supplies that constant per site, across a
network, or through a shared bootstrap. That scope is deployment-controlled
and cannot be classified from this source review alone.

Classification:

```text
Source-confirmed single-site boundary
+
Deferred / separate release gate
```

No constant value or scope-bearing runtime configuration was inspected.

## D. Legacy Fallback Compatibility Findings

### Source Findings

- the legacy / transitional OpenAI Settings fallback is stored in the main
  `analytics_report_ai_settings` option path.
- no network-level OpenAI fallback source was found.
- source priority remains constant first, Settings fallback second, and
  missing last.
- normal Settings sanitization preserves the existing fallback.
- the normal Settings UI does not create or replace a new fallback.
- `clear_openai_api_key` sets only the current Settings array's
  `openai_api_key` slot to an empty value.
- no cross-site cleanup, site iteration, or network fallback removal path was
  found.
- the clear branch does not define, update, or delete
  `ANALYTICS_REPORT_AI_OPENAI_API_KEY`.

### Source-level Conclusion

The current compatibility and clear-operation behavior is source-confirmed
only for the current Settings option path. The clear operation is slot-limited
and does not alter constant-based configuration in the reviewed source.

Legacy fallback storage classification:

```text
Source-confirmed single-site boundary
+
Deferred / separate release gate
```

Fallback clear-operation classification:

```text
Source-confirmed single-site boundary
+
Deferred / separate release gate
```

This review does not establish actual fallback existence, cross-site behavior,
or runtime removal results.

## E. Uninstall Source-level Boundary Findings

### Source Findings

- root `uninstall.php` checks the `WP_UNINSTALL_PLUGIN` guard.
- it performs two deterministic site-level deletions:
  - `analytics_report_ai_settings` through `delete_option()`;
  - `analytics_report_ai_oauth_tokens` through `delete_option()`.
- it does not read either option before deletion.
- no `get_sites()`, `switch_to_blog()`, or `restore_current_blog()` call was
  found.
- no `delete_site_option()` or other network-option cleanup path was found.
- no network-level branch or per-site iteration was found.
- no provider revoke, refresh, token endpoint, remote request, or runtime
  bootstrap call was found.

### Source-level Conclusion

The guarded deterministic option cleanup is source-confirmed for the current
site-level implementation. Network uninstall completeness and cross-site
cleanup are not source-confirmed.

Single-site uninstall classification:

```text
Source-confirmed single-site boundary
```

Network uninstall classification:

```text
Deferred / separate release gate
```

Provider communication is absent from the reviewed uninstall source. This is
not runtime proof of provider account state, revocation, or every possible
deployment behavior.

## Topic-by-topic Conclusion Table

| Topic | Source-level finding | Classification | Non-conclusion boundary |
| --- | --- | --- | --- |
| Activation / network activation handling | Activation hook and callback exist; `$network_wide` is accepted and discarded; no network branch or explicit rejection is present. | Source-confirmed single-site boundary + Deferred / separate release gate | Actual network activation behavior is not verified and is not classified as supported or complete. |
| Settings storage scope | Main settings use the site-level option family and `register_setting()`; no network option API or multisite branch was found. | Source-confirmed single-site boundary + Deferred / separate release gate | Actual per-site behavior across a multisite deployment is not verified. |
| OAuth token storage scope | Dedicated token storage uses the site-level option family; no network option API or multisite branch was found. | Source-confirmed single-site boundary + Deferred / separate release gate | Cross-site token behavior and stored values are not verified. |
| Constant resolver multisite scope | Constant-first resolution is present; no multisite-specific resolver branch is present. | Source-confirmed single-site boundary + Deferred / separate release gate | Per-site, shared, or network-wide deployment scope cannot be determined from plugin source alone. |
| Legacy fallback multisite scope | The fallback uses the main site-level Settings path; no network-level fallback source was found. | Source-confirmed single-site boundary + Deferred / separate release gate | Actual cross-site fallback behavior is not verified. |
| Fallback clear-operation scope | `clear_openai_api_key` clears only the current Settings fallback slot; no site iteration exists and constant configuration is not modified. | Source-confirmed single-site boundary + Deferred / separate release gate | Actual runtime removal and cross-site cleanup are not verified. |
| Uninstall scope | Guarded `delete_option()` calls target the two deterministic plugin-owned option keys. | Source-confirmed single-site boundary | Plugin uninstall was not executed and option contents were not inspected. |
| Network uninstall scope | No site iteration, network option cleanup, or network branch is present. | Deferred / separate release gate | Actual network uninstall and WordPress runtime behavior are not inferred. |

No topic was classified as `Source-confirmed multisite/network behavior`.
No topic was `Blocked` for source inspection; the unresolved runtime and
support questions remain separate release gates.

## Explicitly Unverified / Deferred Items

The following were not executed or verified:

- actual multisite setup;
- actual network activation;
- actual network deactivation;
- actual per-site Settings behavior in multisite;
- actual cross-site OpenAI fallback behavior;
- actual cross-site OAuth token behavior;
- actual network uninstall;
- actual site iteration behavior;
- actual network-option cleanup behavior;
- actual provider communication absence during runtime execution;
- actual fallback removal;
- actual option or credential contents;
- actual constant deployment scope;
- functional multisite support;
- WordPress core behavior outside the reviewed plugin source.

These remain:

```text
Deferred / separate release gate
```

## Public Release Implication

The current source provides a bounded single-site implementation for option
storage, OpenAI source resolution, fallback cleanup, and deterministic
uninstall cleanup. It does not provide source-confirmed multisite/network
activation or network uninstall completeness.

Therefore:

- the existing single-site compatibility boundary remains documented;
- multisite/network support must not be represented as verified, safe,
  supported, or complete;
- a public support decision is still required before claiming multisite or
  network-install compatibility;
- if multisite support is pursued, implementation planning and controlled
  runtime verification must be separate later steps;
- WordPress.org public release readiness remains `Hold`.

## Security and Evidence Boundary

Step 272 did not inspect, display, record, or share:

- credentials or API key values;
- OAuth token values;
- option values or serialized option data;
- constant values;
- Authorization headers;
- request or response bodies;
- payload JSON;
- generated report text;
- database contents;
- screenshots or browser Network evidence;
- actual analytics values.

Step 272 did not perform:

- multisite setup;
- network activation, deactivation, or uninstall;
- plugin activation, deactivation, or uninstall;
- browser admin smoke;
- Settings save;
- fallback removal;
- WP-CLI mutation;
- `wp option get`;
- `wp site list`;
- raw SQL or database dump;
- OpenAI Generate;
- GA4 Fetch;
- OAuth;
- external HTTP or provider communication;
- Plugin Check;
- fixture or mu-plugin creation.

## Result Classification

```text
Multisite and uninstall source-level release-boundary verification completed
```

WordPress.org release readiness:

```text
Hold
```

## Commands Executed

Read-only command categories used:

```text
git status --short --untracked-files=all
git diff --name-only
git diff --check
sed / nl source and docs inspection
rg source and docs searches
```

Final repository checks are recorded in the completion report.

## Recommended Next Step

```text
Step 273 candidate — OpenAI legacy/transitional fallback multisite public-support decision checkpoint
```

The next step should remain docs-only / planning-only and decide whether
multisite/network use is explicitly unsupported for the initial public
release, or whether a separate implementation and controlled runtime
verification track is required. It should not enable multisite or execute
network activation/uninstall as part of the decision checkpoint.
