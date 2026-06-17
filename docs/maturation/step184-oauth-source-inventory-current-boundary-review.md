# Step 184: OAuth Source Inventory And Current Boundary Review

## Step Purpose

Step 184 is a docs-only and inspection-only source inventory for the current
Google OAuth implementation boundary.

The purpose is to identify which OAuth pieces currently exist in source, which
pieces are only narrow or partial boundaries, and which public-release pieces
remain unimplemented, unverified, or policy-dependent before later
implementation planning.

This step does not change production code, `readme.txt`, tools, build scripts,
JavaScript, CSS, admin behavior, credential storage, OAuth lifecycle behavior,
GA4 behavior, OpenAI behavior, payload handling, transient handling, release
packaging, or generated report persistence.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step183-oauth-public-release-readiness-implementation-plan.md`
- `docs/maturation/step182-oauth-credential-public-release-strategy-checkpoint.md`
- `docs/maturation/step181-public-release-blocker-prioritization-checkpoint.md`
- `docs/maturation/step129-google-authorization-redirect-execution-implementation-results.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`
- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`
- `docs/maturation/step179-support-debug-wording-maturation-checkpoint.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`

Additional source-context docs reviewed at status/category level:

- `docs/maturation/step145-narrow-token-exchange-production-implementation-results.md`
- `docs/maturation/step152-narrow-ga4-oauth-credential-source-production-implementation-results.md`
- `docs/maturation/step153-ga4-oauth-credential-source-source-level-verification-results.md`
- `docs/maturation/step164-manual-token-fallback-retirement-decision-checkpoint.md`

## Inspection Boundary

Inspection was limited to source-level and docs-level review.

This step did not execute:

- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- GA4 Fetch,
- OpenAI Generate,
- Plugin Check,
- browser admin smoke,
- screenshots,
- browser Network evidence,
- database dumps,
- option value inspection,
- credential value inspection.

This step did not display, inspect, or record:

- credentials,
- API keys,
- access tokens,
- refresh tokens,
- Authorization headers,
- OAuth client ID values,
- OAuth client secret values,
- plugin settings option values,
- OAuth token option values,
- serialized option values,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- page path/source/city values,
- request bodies,
- raw GA4 responses,
- raw OpenAI responses,
- AI payload JSON,
- generated report bodies,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces,
- database rows,
- database dumps.

## OAuth Source Inventory

| Area | Source file | Class / method / function | Current implementation status | Public-release relevance | Boundary / limitation | Recommended follow-up | Risk level |
|---|---|---|---|---|---|---|---|
| admin menu / page routing | `includes/class-admin.php` | `add_action()` registrations for OAuth admin-post actions | `implemented` | Required for admin-only OAuth actions. | Capability and nonce boundaries exist for connect; callback is admin-post based. | Confirm full public-release route map in Step 185/186. | Medium |
| OAuth connect action | `includes/class-admin.php` | `handle_google_oauth_connect()` | `implemented_narrow_boundary` | Required for OAuth start. | Redirect execution exists after capability, nonce, client ID, and state preparation. Does not itself exchange, store, refresh, or revoke tokens. | Verify provider configuration strategy and safe smoke plan before public release. | High |
| OAuth callback action | `includes/class-admin.php` | `handle_google_oauth_callback()` | `partial_boundary_exists` | Required for OAuth completion. | Valid-state/code-present callbacks can proceed to token exchange, but public-release lifecycle remains incomplete. | Inventory callback outcomes and finalize lifecycle policy. | High |
| OAuth state generation / validation | `includes/class-admin.php` | state placeholder and callback classification helpers | `implemented_narrow_boundary` | Required for CSRF protection. | User-scoped transient and hash comparison exist; raw state is not displayed in source wording. | Source-level verification plus human smoke only in later authorized steps. | Medium |
| OAuth authorization URL construction | `includes/class-admin.php` | authorization URL helper | `implemented_narrow_boundary` | Required for redirect. | Helper builds an authorization request but URL is not intended for display/logging/support evidence. | Keep URL/query evidence forbidden; verify ownership/config strategy first. | High |
| redirect URI display / setup guidance | `includes/class-settings.php` | Settings OAuth section | `implemented_narrow_boundary` | Needed for site-owner setup if that model is selected. | Redirect URI is shown for setup; release-safe docs and support evidence boundaries still need final strategy. | Decide provider configuration model in Step 185. | Medium |
| client ID / client secret source | `includes/class-admin.php`, `includes/class-settings.php` | constant-based source helpers/status | `implemented_narrow_boundary` | Release-critical for OAuth setup. | Source reads configured constants and displays status-level presence only; values are not displayed. Public release still needs ownership/storage decision. | Decide site-owner vs developer-managed OAuth app. | High |
| token exchange request construction | `includes/class-admin.php` | token exchange helper | `implemented_narrow_boundary` | Required for OAuth completion. | Uses WordPress HTTP API only after callback preconditions; live endpoint communication was not executed in this step. Public-release QA remains. | Plan token exchange verification and lifecycle scope. | High |
| token response classification | `includes/class-admin.php`, `includes/class-settings.php` | token exchange status categories / notices | `implemented_narrow_boundary` | Needed for safe support/debug. | Responses are classified into safe categories; raw response details are not intended for display. | Preserve category-only diagnostics. | Medium |
| token storage | `includes/functions-utils.php` | OAuth token storage helper | `implemented_narrow_boundary` | Required if OAuth stores credentials. | Dedicated non-autoloaded option path exists; option values are not displayed. Public storage strategy remains unresolved. | Align with credential storage and uninstall cleanup policy. | High |
| token refresh | `includes/functions-utils.php`, `includes/class-settings.php` | connection-state derivation / wording | `not_implemented` | Required if refresh-capable credentials are part of release scope. | Expired/refresh-needed status can be derived, but refresh endpoint behavior is not implemented. | Lifecycle UX and refresh policy plan. | High |
| token expiry handling | `includes/functions-utils.php` | connection state / resolver | `partial_boundary_exists` | Required for public OAuth UX. | Expiry can change status categories and resolver behavior, but refresh/reconnect UX is incomplete. | Define connected/expired/reconnect behavior. | High |
| disconnect / revoke / reconnect | `includes/class-settings.php`, `includes/class-report-builder.php` | wording and status messages | `not_implemented` | Public release needs clear user control. | Source wording states refresh, revoke, and reconnect controls are not implemented yet. | Design lifecycle controls before release readiness. | High |
| credential source resolution | `includes/functions-utils.php`, `includes/class-report-builder.php` | GA4 credential resolver and call sites | `implemented_narrow_boundary` | Required for GA4 Fetch. | Resolver prefers usable OAuth token category, preserves manual fallback, and returns request-local token material only for GA4 runtime. | Preserve source labels; decide manual fallback future. | High |
| manual Google Access Token fallback | `includes/class-settings.php`, `includes/functions-utils.php`, `includes/class-report-builder.php` | settings save/rendering and resolver | `not_public_release_finalized` | Major release blocker. | Manual fallback remains available and value-hidden for MVP maturation. | Decide remove/restrict/accept before public release. | High |
| Settings UI wording | `includes/class-settings.php` | OAuth / credential storage sections | `wording_only` | Release documentation and support clarity. | Current wording is OAuth-first and status-level, but public-release wording depends on strategy. | Recheck after provider/storage decisions. | Medium |
| Report Builder credential source display | `includes/class-report-builder.php` | current settings table / credential error messages | `implemented_narrow_boundary` | Supports safe admin status visibility. | Displays safe credential source labels and missing/refresh/error messages without credential values. | Preserve status labels and re-smoke after strategy changes. | Medium |
| support/debug evidence boundary | `includes/class-settings.php`, `includes/class-report-builder.php`, docs | support-safe hints and redaction policy | `matured_for_mvp_scope` | Required for public support posture. | Wording prohibits sharing forbidden evidence and asks for status/category labels. | Preserve and align final support docs. | Low |
| privacy/readme implication | `readme.txt`, maturation docs | wording posture from prior steps | `needs_docs_alignment` | Required before release. | Current wording is aligned for current scope but must be rechecked after OAuth/storage decisions. | Final release wording pass after strategy. | Medium |
| uninstall cleanup relation | source/docs | no release-final cleanup implementation identified in this review | `not_implemented` | Release blocker for credential-bearing data. | OAuth token option and settings storage need cleanup policy; this step did not inspect values. | Dedicated uninstall cleanup policy and source inventory. | High |

## Current OAuth Implementation Boundary

Source-level current boundary:

- OAuth-first admin wording is present and matured for the current MVP scope.
- OAuth Connect action is registered and capability/nonce guarded.
- OAuth callback action is registered and classifies callback outcomes into
  safe status categories.
- Temporary OAuth state generation and validation boundaries exist.
- Authorization redirect construction and redirect execution boundaries exist.
- Client configuration is sourced from constants and shown only as
  status-level presence categories.
- Redirect URI setup guidance exists in Settings.
- Narrow token exchange request construction exists behind callback
  preconditions.
- Token response classification exists as safe categories.
- Dedicated OAuth token storage helper exists and uses non-autoload posture for
  new storage.
- OAuth connection state can be displayed as status-level labels.
- GA4 credential source resolution can prefer OAuth token storage, fall back to
  manual token during MVP maturation, or return safe missing/error categories.
- Manual token fallback remains available, value-hidden, and labeled as an MVP
  maturation fallback.
- Settings and Report Builder display status/category labels rather than
  credential values.
- Support/debug wording asks for status/category labels and prohibits sharing
  forbidden evidence.

## Missing / Not Finalized Public-release Pieces

Public-release pieces that remain missing, partial, or not finalized:

- OAuth app ownership model.
- Consent screen / app verification posture.
- Public client configuration and storage/source strategy.
- Token exchange production QA boundary.
- Refresh token handling.
- Token expiry UX and reconnect behavior.
- Disconnect UI.
- Provider revoke behavior.
- Reconnect UI.
- Token storage public-release acceptance or redesign.
- Uninstall cleanup for OAuth tokens/secrets and related settings.
- Manual token fallback public-release treatment.
- Privacy / `readme.txt` final wording alignment after strategy decisions.
- Controlled OAuth QA plan.
- Final admin smoke after strategy-driven changes.
- Final isolated Plugin Check after release-affecting changes.

## Forbidden Evidence Check

Source-level and docs-level review result:

```text
OAuth forbidden-evidence request wording found: No
OAuth support/debug evidence boundary status: status_category_only
Forbidden evidence recorded in this Step 184 doc: No
```

Observed safe pattern:

- Settings and Report Builder wording asks users to share status/category
  labels, warning messages, or error categories.
- OAuth notices use safe status categories.
- Source comments and docs state that raw OAuth values, token values, option
  values, request/response details, screenshots, and browser Network evidence
  should not be displayed or recorded.

Forbidden evidence categories checked:

- credentials,
- API keys,
- access tokens,
- refresh tokens,
- Authorization headers,
- OAuth client ID values,
- OAuth client secret values,
- plugin option values,
- OAuth token option values,
- serialized option values,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- request bodies,
- raw GA4 responses,
- raw OpenAI responses,
- AI payload JSON,
- generated report bodies,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces,
- database dumps.

## Recommended Next Step

Recommended next step:

```text
Step 185: OAuth app ownership and provider configuration decision checkpoint
```

Recommended Step 185 scope:

- docs-only,
- planning-only,
- decide whether the public release strategy should use site-owner provided
  OAuth client configuration, a developer-managed OAuth app, or continued
  release hold until app ownership and provider configuration are resolved,
- do not change production code,
- do not execute OAuth,
- do not navigate to Google,
- do not call token endpoints,
- do not inspect option values or credential values,
- do not collect screenshots or browser Network evidence.

## Acceptance Criteria

Step 184 is complete when:

- this docs-only source inventory file is added,
- production code, `readme.txt`, tools, build scripts, JavaScript, and CSS have
  no additional Step 184 changes,
- OAuth source implementation boundaries are organized,
- public-release unresolved pieces are organized,
- forbidden-evidence request wording is checked at category level,
- WordPress.org release remains `Hold`,
- the recommended next step is explicit.

## Not Executed

Step 184 did not execute:

- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- browser admin smoke,
- screenshots,
- browser Network evidence collection,
- database dump,
- option value inspection.

## Commands Executed

Safe inspection and docs-only commands for this checkpoint:

```bash
git status --short --untracked-files=all
source-level rg/sed review of OAuth-related files and maturation docs
test -f docs/maturation/step184-oauth-source-inventory-current-boundary-review.md && echo "step184_doc_exists"
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```

Command result summary:

- Source-level review identified current OAuth connect, callback, state,
  authorization redirect, token exchange, token storage, credential resolver,
  Settings wording, and Report Builder credential source boundaries.
- Source-level review identified refresh, revoke, reconnect, disconnect,
  uninstall cleanup, manual token public-release posture, and provider
  configuration strategy as not public-release finalized.
- `git diff --check`: passed.
- `git diff --name-only`: no tracked file changes.
- `git diff --stat`: no tracked file changes.

## Result Classification

Result: `OAuth source inventory and current implementation boundary review completed`

WordPress.org release remains `Hold`.
