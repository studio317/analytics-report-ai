# Step 202: OAuth Token Lifecycle Source-level Inventory

## Step Purpose

Step 202 is a docs-only and inspection-only source-level inventory for the
current OAuth token lifecycle boundary.

The purpose is to identify which OAuth token storage, expiry, refresh,
reconnect, disconnect, revoke, GA4 token-use, admin notice, and support-safe
label pieces currently exist in source, and which pieces remain missing or not
public-release finalized before Step 203 planning.

This step does not change production code, `readme.txt`, tools, build scripts,
JavaScript, CSS, OAuth runtime behavior, OAuth resolver behavior, Settings
save/delete behavior, credential storage behavior, token storage behavior, GA4
behavior, or OpenAI behavior.

This step does not execute OAuth, Google navigation, authorization approval,
token endpoint communication, refresh requests, revoke requests, browser admin
smoke, GA4 Fetch, OpenAI Generate, or Plugin Check.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step201-oauth-token-lifecycle-implementation-plan.md`
- `docs/maturation/step200-oauth-production-readiness-token-lifecycle-decision-checkpoint.md`
- `docs/maturation/step199-public-release-remaining-blocker-reprioritization-checkpoint.md`
- `docs/maturation/step198-oauth-client-configuration-hybrid-source-final-maturation-checkpoint.md`
- `docs/maturation/step184-oauth-source-inventory-current-boundary-review.md`
- `docs/maturation/step182-oauth-credential-public-release-strategy-checkpoint.md`
- `docs/maturation/step179-support-debug-wording-maturation-checkpoint.md`
- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`

## Inspection Boundary

Inspection was limited to source-level and docs-level review.

Inspected production files:

- `analytics-report-ai.php`
- `includes/class-admin.php`
- `includes/functions-utils.php`
- `includes/class-settings.php`
- `includes/class-ga4-client.php`
- `includes/class-report-builder.php`

This step did not inspect or display stored values. It reviewed source paths,
function boundaries, status labels, and docs categories only.

Not performed:

- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- refresh request,
- revoke request,
- GA4 Fetch,
- OpenAI Generate,
- Plugin Check,
- browser admin smoke,
- screenshots,
- browser Network evidence,
- database dump,
- option value inspection,
- token value inspection,
- credential value inspection,
- OAuth client value inspection.

## Source Inventory Table

| Area | Source file | Class / method / function | Current implementation status | Lifecycle relevance | Boundary / limitation | Potential Step 203 action | Risk level |
|---|---|---|---|---|---|---|---|
| OAuth token option/storage structure | `analytics-report-ai.php`, `includes/functions-utils.php` | `ANALYTICS_REPORT_AI_GOOGLE_OAUTH_TOKEN_OPTION_NAME`, `analytics_report_ai_store_google_oauth_tokens()` | `implemented_narrow_boundary` | Dedicated local OAuth token storage exists. | Stores token material and metadata through helper boundary; public storage posture remains not finalized. | `plan_storage_alignment` | High |
| Token exchange response handling | `includes/class-admin.php` | `exchange_google_oauth_authorization_code_for_tokens()`, `request_google_oauth_tokens()` | `implemented_narrow_boundary` | Authorization-code exchange can classify and store successful token material. | Token endpoint communication was not executed in this step; request/response bodies are not recorded. | `no_change_for_step_203_inventory`, `plan_failure_category_preservation` | High |
| Access token storage | `includes/functions-utils.php` | `analytics_report_ai_store_google_oauth_tokens()` | `implemented_narrow_boundary` | Access token can be stored for later GA4 runtime use. | Stored value is credential-bearing; no public-release storage acceptance yet. | `plan_storage_alignment` | High |
| Refresh token storage | `includes/functions-utils.php` | `analytics_report_ai_store_google_oauth_tokens()` | `partial_boundary_exists` | Refresh token may be stored if present in classified token material. | Refresh-capable lifecycle does not exist yet; storage policy and cleanup remain unresolved. | `plan_refresh_metadata`, `plan_storage_alignment` | High |
| Expiry metadata storage | `includes/functions-utils.php` | `analytics_report_ai_store_google_oauth_tokens()` | `implemented_narrow_boundary` | Expiry metadata is stored when a response includes usable lifetime metadata. | Expiry metadata is used for status categories, not automatic refresh. | `plan_refresh_metadata` | Medium |
| Token retrieval for GA4 | `includes/functions-utils.php`, `includes/class-report-builder.php` | `analytics_report_ai_resolve_google_ga4_credential_source()`, report fetch handling | `implemented_narrow_boundary` | GA4 runtime can receive a request-local token selected by resolver. | Token values are returned only to request-local runtime; UI/docs/support should use status labels. | `plan_refresh_before_fetch_boundary` | High |
| GA4 credential source resolver | `includes/functions-utils.php` | `analytics_report_ai_resolve_google_ga4_credential_source()` | `implemented_narrow_boundary` | Resolver chooses OAuth, manual fallback, missing, refresh-needed, or error category. | Manual fallback remains available during MVP maturation; refresh is not attempted. | `plan_refresh_failure_category`, `plan_manual_fallback_separation` | High |
| Manual Google Access Token fallback relationship | `includes/functions-utils.php`, `includes/class-settings.php`, `includes/class-report-builder.php` | Settings save/rendering and GA4 credential resolver | `not_public_release_finalized` | Manual fallback can be used when OAuth is missing/unusable under current MVP maturation behavior. | Public-release treatment remains unresolved and should not be solved inside lifecycle implementation. | `plan_manual_fallback_separate_track` | High |
| Access token expiry detection | `includes/functions-utils.php` | `analytics_report_ai_get_google_oauth_connection_state()`, resolver expiry branch | `partial_boundary_exists` | Expired token can produce safe connection/credential categories. | Expiry detection does not trigger refresh; behavior with manual fallback is still an MVP policy boundary. | `plan_refresh_before_fetch_boundary`, `plan_reconnect_status` | High |
| Invalid/expired token handling | `includes/class-ga4-client.php`, `includes/class-report-builder.php`, `includes/functions-utils.php` | safe GA4 API error messages, credential source messages | `partial_boundary_exists` | GA4 auth errors and resolver expiry states can tell users to reconnect or use fallback. | No automatic refresh or refresh-after-invalid-token behavior exists. | `plan_refresh_after_invalid_token_boundary`, `plan_safe_admin_notice_category` | High |
| Refresh flow | source/docs | No refresh helper found in inspected source | `not_implemented` | Needed for refresh-capable lifecycle. | Refresh endpoint communication, trigger, retry, success, and failure paths are absent. | `plan_refresh_request_boundary` | High |
| Refresh request construction | source/docs | No refresh request construction found in inspected source | `not_implemented` | Needed if refresh-capable lifecycle is selected. | No refresh request was executed or implemented in this source review. | `plan_refresh_request_boundary` | High |
| Refresh response handling | source/docs | No refresh response classifier found in inspected source | `not_implemented` | Needed for success/failure categories and storage updates. | Existing token exchange categories are not refresh categories. | `plan_refresh_failure_category` | High |
| Refresh failure handling | source/docs, `includes/functions-utils.php` | Existing `token_expired_or_refresh_needed` / `credential_source_oauth_refresh_needed` categories | `partial_boundary_exists` | Source can indicate refresh is needed, but cannot perform refresh or classify refresh failure. | No `token_refresh_status_category` result path exists. | `plan_refresh_failure_category`, `plan_reconnect_status` | High |
| Reconnect UX | `includes/class-settings.php`, `includes/class-report-builder.php`, `includes/functions-utils.php` | Settings connection state display and safe credential messages | `partial_boundary_exists` | Existing wording says reconnect may be needed; source can produce `reconnect_required`. | No dedicated reconnect control separate from starting OAuth authorization. | `plan_reconnect_status` | High |
| Disconnect local token deletion | source/docs | No disconnect action or local token deletion UI found in inspected source | `not_implemented` | Required for user-controlled local OAuth cleanup. | No admin-post action, delete helper, or notice category identified for disconnect. | `plan_disconnect_local_token_deletion` | High |
| Provider-side revoke | source/docs | No provider revoke action or request helper found in inspected source | `not_implemented` | Needed if plugin supports in-plugin provider revocation. | No revoke request boundary exists; revoke remains policy decision. | `plan_revoke_policy` | High |
| Manual revoke guidance | `includes/class-settings.php`, docs | General wording says revoke controls are not implemented yet | `needs_policy_decision` | Needed if provider-side revoke is deferred. | No dedicated manual revoke guidance UI found in inspected source. | `plan_manual_revoke_guidance` | Medium |
| Admin notices | `includes/class-settings.php`, `includes/class-report-builder.php`, `includes/class-ga4-client.php` | `render_google_oauth_status_notice()`, credential source messages, safe GA4 error messages | `implemented_narrow_boundary` | Notices classify callback/token exchange/credential-source outcomes without values. | No refresh/disconnect/revoke notice categories exist yet. | `plan_safe_admin_notice_category` | Medium |
| Support/debug safe labels | `includes/class-settings.php`, `includes/class-report-builder.php`, docs | support-safe wording and status/category labels | `implemented_narrow_boundary` | Existing UI asks support to use status/category labels only. | Lifecycle label set for refresh/disconnect/revoke is not implemented. | `plan_support_safe_lifecycle_labels` | Medium |
| Uninstall cleanup relationship | source/docs | No `uninstall.php` found in source-level check | `not_implemented` | Credential-bearing OAuth and settings data cleanup remains release blocker. | No cleanup target implementation exists in production source. | `plan_uninstall_cleanup_dependency` | High |
| Privacy/readme wording impact | `readme.txt`, docs | Previous wording exists, but lifecycle-specific wording not final | `not_public_release_finalized` | Final wording must follow lifecycle/storage/revoke/disconnect decisions. | Step 202 does not change readme or privacy wording. | `plan_privacy_wording_after_lifecycle` | Medium |

## Current Implementation Boundary

Source-level current boundary:

- A dedicated OAuth token option name exists as a source-level storage target.
- Token exchange response handling exists for authorization-code callback flow.
- Successful token exchange can hand classified token material to a storage
  helper.
- OAuth token storage helper stores access-token material, optional refresh
  token material, expiry metadata, and connection metadata through a dedicated
  non-autoloaded option boundary.
- OAuth connection state can be derived without displaying token values.
- Expired token metadata can produce safe categories such as reconnect needed
  or refresh needed.
- GA4 credential source resolver can choose usable OAuth token material,
  temporary manual token fallback, missing credential state, refresh-needed
  state, or OAuth error category.
- Token material returned by the resolver is request-local for GA4 runtime use,
  while admin UI/support should use status labels.
- Settings UI displays OAuth connection state and safe OAuth callback/token
  exchange notice categories.
- Report Builder displays credential source labels and safe missing /
  refresh-needed / OAuth-error messages.
- GA4 client has safe invalid/expired credential messages that do not expose
  raw response bodies.
- Settings and Report Builder support/debug wording remains status/category
  level and tells users not to share forbidden evidence.

## Missing / Not Finalized For Lifecycle

Compared with the Step 201 lifecycle model, the following remain missing,
partial, or not public-release finalized:

| Lifecycle item | Inventory result | Step 203 planning need |
|---|---|---|
| Expiry detection | `partial_boundary_exists` | Decide exact refresh-before-fetch and reconnect behavior. |
| Refresh token storage policy | `not_public_release_finalized` | Decide whether refresh token persistence is acceptable and how it is disclosed/cleaned. |
| Refresh-before-GA4-Fetch | `not_implemented` | Plan trigger boundary before GA4 Fetch. |
| Refresh-after-invalid-token | `not_implemented` | Decide whether one safe retry after invalid-token category is allowed. |
| Refresh success handling | `not_implemented` | Plan storage updates and safe success label. |
| Refresh failure handling | `partial_boundary_exists` | Existing refresh-needed category is not a refresh-failure category. |
| `reconnect_required` status | `partial_boundary_exists` | Existing source can derive it, but UX/control is incomplete. |
| Disconnect local token deletion | `not_implemented` | Plan admin action, local deletion target, notice, and scope. |
| Provider-side revoke | `not_implemented` | Decide explicit revoke request vs manual guidance vs defer. |
| Manual revoke guidance | `needs_policy_decision` | Add only if revoke is deferred or not implemented initially. |
| GA4 Fetch behavior when token expired | `partial_boundary_exists` | Currently blocks OAuth token use or uses manual fallback under MVP behavior; refresh plan needed. |
| Support-safe token lifecycle labels | `partial_boundary_exists` | Existing labels cover connection/credential source; refresh/disconnect/revoke labels need plan. |
| Uninstall cleanup for OAuth tokens / OpenAI key | `not_implemented` | Plan cleanup after storage inventory and policy. |
| Readme/privacy wording impact | `not_public_release_finalized` | Recheck after lifecycle/storage decisions. |

## Manual Token Fallback Relationship

Source-level relationship:

```text
manual_token_fallback_status_category: public_release_unresolved
manual_token_fallback_status_category: separate_track
manual_token_fallback_status_category: controlled_maturation_fallback
```

Inventory result:

- Manual Google Access Token remains available in Settings as an MVP maturation
  fallback.
- The GA4 credential source resolver can use manual fallback when OAuth is
  missing or unusable under current MVP maturation behavior.
- When OAuth token storage exists but is expired or unusable, manual fallback
  can still be selected with a safe fallback category if a manual token exists.
- Manual fallback is not public-release finalized.
- Manual fallback policy should remain separate from the OAuth token lifecycle
  implementation plan.

Potential Step 203 action:

```text
plan_manual_fallback_separate_track
```

## Credential Storage / OpenAI Key / Uninstall Relationship

| Area | Source-level / planning-level inventory | Current classification |
|---|---|---|
| OAuth access token storage | Dedicated OAuth token storage helper exists and stores access-token material in a local option boundary. | `implemented_narrow_boundary_not_public_release_finalized` |
| OAuth refresh token storage | Helper can store refresh-token material when present, but no refresh lifecycle exists. | `partial_boundary_exists_not_public_release_finalized` |
| Token expiry metadata | Helper stores expiry metadata and connection-state helper reads it. | `implemented_narrow_boundary` |
| OpenAI API key storage | Settings storage remains separate from OAuth lifecycle and is still part of broader credential storage posture. | `linked_release_blocker` |
| Value-hidden non-redisplay | Settings wording and inputs preserve value-hidden posture for credentials/client settings. | `preserve` |
| Option storage disclosure | Settings/docs disclose MVP database storage for relevant credential settings; lifecycle-specific disclosure still depends on final storage model. | `needs_final_alignment` |
| Uninstall cleanup dependency | No production uninstall cleanup implementation was found in this source-level check. | `release_blocker_remaining` |

## Safe Labels Inventory

| Label family | Existing / candidate labels | Inventory classification | Notes |
|---|---|---|---|
| `oauth_connection_status_category` | `not_connected`, `connected`, `reconnect_required`, `token_expired_or_refresh_needed`, `oauth_error_category` | `partial_boundary_exists` | Existing source uses connection-state strings, but the label family is not fully formalized. |
| `token_lifecycle_status_category` | `not_finalized`, `refresh_capable_planned`, `refresh_not_available`, `refresh_failed`, `reconnect_required` | `planned_candidate` | Candidate labels from Step 201; not implemented as a dedicated family. |
| `token_refresh_status_category` | `not_attempted`, `success`, `failed`, `unavailable` | `not_implemented_planned_candidate` | No refresh request/response path exists yet. |
| `token_disconnect_status_category` | `local_tokens_deleted` | `not_implemented_planned_candidate` | No local disconnect action exists yet. |
| `token_revoke_status_category` | `not_attempted`, `success`, `failed`, `manual_action_required` | `not_implemented_planned_candidate` | No provider-side revoke request exists yet. |
| `manual_token_fallback_status_category` | `public_release_unresolved`, `separate_track`, `controlled_maturation_fallback` | `planned_policy_category` | Current resolver has fallback status labels; public-release policy remains unresolved. |
| `safe_admin_notice_category` | OAuth redirect, callback, token exchange, token storage, credential source, and GA4 error categories | `implemented_narrow_boundary` | Existing notices avoid raw token/client/request/response values. Refresh/disconnect/revoke notice categories are still missing. |

## Forbidden Evidence Check

Source-level review result:

```text
token_lifecycle_forbidden_evidence_request_found: No
token_lifecycle_forbidden_evidence_display_found: No
support_debug_evidence_boundary_status: status_category_only
forbidden_evidence_recorded_in_step_202_doc: No
```

Checked forbidden evidence categories:

- OAuth client ID values,
- OAuth client secret values,
- OAuth client value fragments,
- access token values,
- refresh token values,
- Authorization headers,
- credentials,
- API keys,
- plugin option values,
- OAuth token option values,
- serialized option values,
- request bodies,
- raw responses,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces,
- database dumps,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- AI payload JSON,
- generated report bodies.

Observed safe source patterns:

- OAuth callback/token exchange notices use status categories.
- Token exchange comments state raw code/token values are not returned,
  displayed, logged, or saved in admin notices.
- Token storage helper comments state token values are not returned or
  displayed.
- Credential source resolver comments state token values are for request-local
  GA4 runtime use and status labels should be used for UI/docs/logs/support.
- Settings and Report Builder support/debug wording asks for status/category
  labels and warns against sharing forbidden evidence.

## Current Decision

Current Step 202 decision:

```text
OAuth token lifecycle source inventory status: Completed
OAuth token storage boundary: implemented_narrow_boundary_not_public_release_finalized
OAuth expiry category boundary: partial_boundary_exists
OAuth refresh flow: not_implemented
OAuth reconnect UX: partial_boundary_exists_not_public_release_finalized
OAuth local disconnect: not_implemented
OAuth provider-side revoke: not_implemented
Manual token fallback status: public_release_unresolved_separate_track
Credential storage status: linked_release_blocker
Uninstall cleanup status: release_blocker_remaining
WordPress.org release status: Hold
```

This inventory is not a production implementation and not a release-readiness
decision.

## Acceptance Criteria

| Criterion | Status | Notes |
|---|---|---|
| Docs-only source inventory file added | Pass | This file records Step 202. |
| Production code / readme / tools / JS / CSS unchanged | Pass | Step 202 adds this docs file only. |
| OAuth token lifecycle source boundaries organized | Pass | Source inventory table covers token storage, expiry, refresh, reconnect, disconnect, revoke, GA4 use, notices, and support labels. |
| Missing / not finalized lifecycle items organized | Pass | Missing lifecycle table records refresh, reconnect, disconnect, revoke, cleanup, and wording gaps. |
| Manual fallback relationship organized | Pass | Manual fallback remains a separate public-release unresolved track. |
| Credential storage / uninstall cleanup relationship organized | Pass | Storage and cleanup dependencies are recorded without changing behavior. |
| Forbidden evidence request/display checked at category level | Pass | Source-level review found no request/display path for forbidden evidence in inspected lifecycle support/debug wording. |
| WordPress.org release remains `Hold` | Pass | Release status is unchanged. |
| Next recommended step is clear | Pass | Step 203 is recommended below. |

## Not Executed

Not executed in Step 202:

- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- refresh request,
- revoke request,
- browser admin smoke,
- screenshots,
- browser Network evidence collection,
- database dump,
- option value output.

## Recommended Next Step

Recommended next step:

```text
Step 203: OAuth token lifecycle narrow implementation plan
```

Step 203 should be docs-only / planning-only. It should convert this inventory
into a narrow implementation plan for refresh, expiry, reconnect, disconnect,
revoke, local token deletion, admin notices, safe labels, and storage
relationships.

Step 203 should not execute OAuth, token endpoint communication, refresh
requests, revoke requests, Plugin Check, browser admin smoke, screenshots,
browser Network evidence, GA4 Fetch, OpenAI Generate, option value inspection,
or database dumps.

## Result Classification

```text
OAuth token lifecycle source-level inventory completed
```
