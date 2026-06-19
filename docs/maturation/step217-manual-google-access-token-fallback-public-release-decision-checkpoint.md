# Step 217: Manual Google Access Token Fallback Public-release Decision Checkpoint

## Step Purpose

Step 217 is a docs-only and planning-only decision checkpoint for the manual
Google Access Token fallback before public-release readiness.

Step 216 concluded that the manual fallback is acceptable only within the
current MVP / developer-verification boundary and is not recommended for public
release without retirement or restriction. This step selects the public-release
direction before any implementation planning.

No production code is changed in this step.

WordPress.org release readiness remains `Hold`.

## Scope

In scope:

- public-release decision for the manual Google Access Token fallback,
- comparison of full retirement, developer-only restriction, transitional
  bridge, and no-change options,
- Settings UI decision,
- credential resolver decision,
- saved fallback value handling decision,
- local-only disconnect and uninstall cleanup relationship,
- support/debug evidence boundary,
- public-release decision table,
- implementation planning requirements,
- recommended next step.

Out of scope:

- production implementation,
- Settings UI changes,
- credential resolver changes,
- GA4 client changes,
- OpenAI client changes,
- readme changes,
- option value inspection,
- database inspection,
- browser smoke,
- external API calls,
- release-readiness approval.

## Explicit Non-goals

Step 217 does not:

- change production code,
- change `uninstall.php`,
- change `readme.txt`,
- change tools or build scripts,
- change JavaScript or CSS,
- change Settings UI,
- change the credential resolver,
- change GA4 client behavior,
- change OpenAI client behavior,
- run Plugin Check,
- run GA4 Fetch,
- run OpenAI Generate,
- start OAuth Connect / Authorize,
- navigate to Google,
- call the token endpoint,
- execute refresh requests,
- execute revoke requests,
- run browser admin smoke,
- execute plugin uninstall,
- collect screenshots,
- collect browser Network evidence,
- inspect database dumps,
- run `wp option get` for plugin option values,
- inspect option values,
- inspect token values,
- inspect credential values,
- inspect OAuth client values,
- inspect request bodies,
- inspect raw responses,
- inspect AI payload JSON,
- inspect generated report bodies.

## Referenced Prior Steps

- `docs/maturation/step216-manual-google-access-token-fallback-retirement-plan.md`
- `docs/maturation/step215-uninstall-cleanup-maturation-checkpoint.md`
- `docs/maturation/step209-credential-storage-public-release-posture-checkpoint.md`
- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`
- `docs/maturation/step164-manual-token-fallback-retirement-decision-checkpoint.md`
- `docs/maturation/step153-ga4-oauth-credential-source-source-level-verification-results.md`
- `docs/maturation/step152-narrow-ga4-oauth-credential-source-production-implementation-results.md`

## Decision Inputs From Step 216

Step 216 established these inputs:

| Input | Status/category | Decision impact |
|---|---|---|
| OAuth credential source | Preferred | Public release should use OAuth as the normal GA4 credential source. |
| Manual Google Access Token fallback | MVP / developer-verification fallback | Acceptable in MVP only; public release needs a decision. |
| Manual fallback Settings UI | Currently visible as a value-hidden password field | Public release should remove, hide, or gate the normal UI path. |
| Manual fallback resolver branch | Currently active as `credential_source_manual_token` / `manual_token_fallback_used` category | Public release should remove or gate this branch. |
| Saved manual fallback value | Stored under `google_tokens` in `analytics_report_ai_settings` | Handling requires a narrow implementation plan. |
| Local-only OAuth disconnect | Does not delete manual fallback | Correct separation; do not redefine local disconnect as fallback cleanup. |
| Uninstall cleanup | Deletes the whole main settings option | Cleans saved fallback only when the plugin is uninstalled. |
| Support/debug posture | Status/category-level evidence only | Must remain strict in future implementation and support wording. |

This checkpoint records only source file names, function names, option key
names, UI field names, credential source categories, storage categories, docs
references, and decision classifications. It does not inspect or record option
values, credential values, token values, OAuth client values, serialized option
values, database rows, request bodies, raw responses, AI payload JSON,
generated report bodies, screenshots, browser Network evidence, GA4 Property ID
values, hostname/domain values, or analytics values.

## Public-release Options

| Option | Summary | Decision classification |
|---|---|---|
| Option 1: Full retirement before public release | Remove or hide the normal Settings UI manual token field, remove the manual fallback source from the GA4 credential resolver, and define saved fallback handling. | Selected / Recommended |
| Option 2: Developer-only / diagnostic-only restriction | Allow manual fallback only when an explicit developer/diagnostic gate is enabled. Hide it from normal public UI. | Deferred fallback option |
| Option 3: Short transitional bridge | Keep the path temporarily, but do not connect it to release readiness and define a firm removal/restriction deadline. | Deferred / Not preferred |
| Option 4: No change | Keep the current MVP behavior as public-release posture. | Rejected / Not recommended |

## Selected Decision

Selected decision:

```text
Manual Google Access Token fallback public-release decision: Full retirement before public release
OAuth credential source: Preferred / normal GA4 credential source
Manual fallback in normal Settings UI: Remove or hide before public release
Manual fallback in credential resolver: Remove before public release
Saved manual fallback value handling: Needs narrow implementation plan
Developer-only fallback: Deferred unless explicitly required
Keep visible but discouraged: Rejected / Not recommended
No change: Rejected / Not recommended
Production code changes: Not implemented in Step 217
WordPress.org release readiness: Hold
```

Rationale:

- OAuth is already the preferred GA4 credential source.
- The manual fallback is a temporary MVP / developer-verification path.
- Keeping direct access token entry in normal public Settings UI weakens the
  public credential posture.
- Removing the resolver branch reduces ambiguity about which credential model
  is supported for public release.
- Full retirement simplifies support/debug guidance and privacy/readme wording.
- Uninstall cleanup already covers plugin-owned saved fallback data when the
  plugin is uninstalled, but normal operation retirement still needs a separate
  implementation plan.

## Rejected Options

| Option | Classification | Reason |
|---|---|---|
| Keep visible but discouraged | Rejected / Not recommended | It still exposes direct access token entry in normal public admin UI and keeps support/privacy burden high. |
| No change | Rejected / Not recommended | It would silently carry an MVP developer-verification path into public-release posture. |
| Short transitional bridge | Deferred / Not preferred | It may be useful only if implementation sequencing requires a bridge, but it should not be treated as release-ready. |

Developer-only / diagnostic-only restriction remains a fallback decision only
if a controlled recovery path is explicitly required after implementation
planning. It is not the selected public-release direction in this checkpoint.

## Settings UI Decision

Decision:

```text
Manual fallback in normal Settings UI: Remove or hide before public release
```

Future implementation should plan:

- remove or hide the `Manual Google Access Token` normal Settings field,
- remove or hide the `Manual Google Access Token Status` normal Settings row,
- remove or hide the normal manual fallback delete checkbox unless a migration
  cleanup control remains necessary,
- preserve OAuth as the normal GA4 credential source in Settings wording,
- keep support/debug wording status-level only,
- avoid showing any saved fallback value or value fragment.

Step 217 does not implement these UI changes.

## Credential Resolver Decision

Decision:

```text
Manual fallback in credential resolver: Remove before public release
```

Future implementation should plan:

- remove the manual fallback source from
  `analytics_report_ai_resolve_google_ga4_credential_source()`,
- stop returning manual fallback runtime access token data for GA4 requests in
  normal public-release behavior,
- remove or retire status paths such as `credential_source_manual_token` and
  `manual_token_fallback_used` from normal public-release resolver behavior,
- keep OAuth connected, refresh-needed/reconnect-needed, and missing credential
  status categories as the normal safe categories,
- avoid changing GA4 API request construction beyond the credential source
  boundary.

Step 217 does not change resolver behavior.

## Saved Fallback Value Handling Decision

Decision:

```text
Saved manual fallback value handling: Needs narrow implementation plan
```

Implementation planning should decide how to handle saved manual fallback data
without displaying or inspecting values.

Candidate approaches:

- ignore saved manual fallback values after resolver retirement, then rely on
  uninstall cleanup or a future explicit cleanup control,
- remove the saved manual fallback category during Settings save after the
  field is retired,
- provide a value-hidden cleanup notice/control if saved fallback state can
  still exist,
- delete the category as part of a narrowly scoped migration if that can be done
  without value display or broad storage churn.

Step 218 should choose a narrow approach before production implementation.

## Local Disconnect and Uninstall Cleanup Relationship

Local-only OAuth disconnect:

- deletes only local OAuth token data,
- does not contact Google,
- does not revoke provider-side access,
- does not delete the manual Google Access Token fallback,
- does not delete the OpenAI API key.

Manual fallback retirement:

- is a normal-operation public-release credential model decision,
- should not redefine local-only OAuth disconnect,
- should not imply provider-side revoke,
- should not require GA4 Fetch or token endpoint communication.

Uninstall cleanup:

- deletes the whole main plugin settings option,
- therefore deletes saved manual fallback data as plugin-owned settings data
  when the plugin is uninstalled,
- is not a substitute for normal Settings UI / resolver retirement before
  public release.

## Support/debug Evidence Boundary

Support/debug evidence must remain status/category-level only.

Allowed evidence:

- source file name,
- function / method / option key name,
- UI field name,
- credential source category,
- storage category,
- docs-level reference,
- decision option,
- Recommended / Accepted / Hold / Needs implementation / Deferred /
  Not recommended classification.

Forbidden evidence:

- option values,
- credential values,
- API keys,
- access token values,
- refresh token values,
- OAuth client ID values,
- OAuth client secret values,
- Authorization headers,
- serialized option values,
- database row contents,
- request bodies,
- raw responses,
- AI payload JSON,
- generated report bodies,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces,
- GA4 Property ID values,
- hostname/domain values,
- analytics values.

Future support wording should not ask users to provide manual access token
values, option values, screenshots containing credential state, browser Network
evidence, Authorization headers, request bodies, or raw responses.

## Public Release Decision Table

| Decision area | Selected posture | Classification |
|---|---|---|
| OAuth credential source | Preferred / normal GA4 credential source | Recommended |
| Manual fallback public-release posture | Full retirement before public release | Selected / Needs implementation |
| Manual fallback normal Settings UI | Remove or hide before public release | Needs implementation |
| Manual fallback credential resolver branch | Remove before public release | Needs implementation |
| Saved manual fallback value handling | Decide narrow value-free handling in Step 218 | Needs implementation plan |
| Developer-only fallback | Defer unless explicitly required | Deferred |
| Keep visible but discouraged | Do not adopt as public-release posture | Rejected / Not recommended |
| No change | Do not adopt as public-release posture | Rejected / Not recommended |
| Local-only disconnect relationship | Keep separate from manual fallback cleanup | Accepted |
| Uninstall cleanup relationship | Keep as uninstall-only whole settings option cleanup | Accepted within MVP boundary |
| Support/debug evidence | Status/category-level only | Required |
| Production code changes in Step 217 | None | Not implemented |
| WordPress.org release readiness | Hold | Hold |

## Implementation Planning Requirements

Step 218 should plan the narrow implementation before any production change.

Required planning areas:

- exact Settings UI changes,
- exact credential resolver changes,
- saved fallback value handling,
- Settings delete checkbox behavior after field removal/hiding,
- status/category wording after resolver retirement,
- Report Builder credential source messaging,
- support/debug wording updates,
- readme/privacy wording updates,
- source-level verification commands,
- human admin smoke plan after implementation,
- forbidden evidence boundary.

Step 218 should keep GA4 client request construction, OpenAI client behavior,
OAuth token storage, local-only disconnect, uninstall cleanup, and plugin
release packaging outside the immediate implementation scope unless explicitly
authorized.

## Recommended Next Step

Recommended next step:

```text
Step 218: Manual Google Access Token fallback retirement implementation plan
```

Step 218 should remain docs-only / planning-only and define the narrow
implementation plan for Settings UI, credential resolver, saved fallback
handling, support/debug wording, and readme/privacy wording before any
production change.

## Result Classification

```text
Manual Google Access Token fallback public-release decision checkpoint: Completed
Manual Google Access Token fallback public-release decision: Full retirement before public release
OAuth credential source: Preferred / normal GA4 credential source
Manual fallback in normal Settings UI: Remove or hide before public release
Manual fallback in credential resolver: Remove before public release
Saved manual fallback value handling: Needs narrow implementation plan
Developer-only fallback: Deferred unless explicitly required
Keep visible but discouraged: Rejected / Not recommended
No change: Rejected / Not recommended
Production code changes: Not implemented in Step 217
WordPress.org release readiness: Hold
```
