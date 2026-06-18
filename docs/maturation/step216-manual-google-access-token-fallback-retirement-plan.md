# Step 216: Manual Google Access Token Fallback Retirement Plan

## Step Purpose

Step 216 is a docs-only and planning-only retirement plan for the temporary
manual Google Access Token fallback in Analytics Report AI.

The purpose is to decide how to approach the fallback before public-release
readiness: retire it, restrict it to a developer-only / diagnostic-only
boundary, keep it visible but strongly discouraged, or leave it unchanged.

This step does not change production code, Settings UI, credential resolver
behavior, GA4 client behavior, readme wording, tools, JavaScript, or CSS.

WordPress.org release readiness remains `Hold`.

## Scope

In scope:

- current manual Google Access Token fallback source-level inventory,
- Settings UI treatment,
- OAuth credential source relationship,
- local-only disconnect relationship,
- uninstall cleanup relationship,
- support/debug evidence boundary,
- public-release risk assessment,
- retirement / restriction options,
- impact on future Settings UI and credential resolver changes,
- future implementation candidates,
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

Step 216 does not:

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

- `docs/maturation/step215-uninstall-cleanup-maturation-checkpoint.md`
- `docs/maturation/step209-credential-storage-public-release-posture-checkpoint.md`
- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`
- `docs/maturation/step164-manual-token-fallback-retirement-decision-checkpoint.md`
- `docs/maturation/step153-ga4-oauth-credential-source-source-level-verification-results.md`
- `docs/maturation/step152-narrow-ga4-oauth-credential-source-production-implementation-results.md`
- `docs/maturation/step15-credential-storage-policy.md`

## Current Manual Fallback Inventory

| Area | Source-level reference | Current category | Notes |
|---|---|---|---|
| Default settings storage | `includes/functions-utils.php`, `analytics_report_ai_get_default_settings()` | `google_tokens` settings category | Manual fallback is part of the main settings option structure. |
| Main settings option | `ANALYTICS_REPORT_AI_OPTION_NAME` / `analytics_report_ai_settings` | WordPress option storage | The manual fallback is stored under the plugin-owned settings option. |
| Settings save handling | `includes/class-settings.php`, settings sanitization flow | `google_access_token` input category | Empty input keeps the saved fallback; delete checkbox clears the saved fallback category. |
| Settings UI field | `includes/class-settings.php` | `Manual Google Access Token` password field | Field value is empty; saved state is shown only by placeholder / description. |
| Settings UI status | `includes/class-settings.php` | `Manual Google Access Token Status` | Displays status-level saved/not-saved posture without showing the token value. |
| Credential resolver | `includes/functions-utils.php`, `analytics_report_ai_resolve_google_ga4_credential_source()` | request-local credential source resolver | OAuth token source is preferred when connected; manual fallback can still return request-local access token for GA4 runtime use. |
| Resolver status labels | `analytics_report_ai_resolve_google_ga4_credential_source()` | `credential_source_manual_token`, `manual_token_fallback_used` | Status labels identify manual source/fallback categories without exposing values. |
| Local-only disconnect | `includes/class-admin.php`, OAuth local disconnect boundary | local OAuth token cleanup only | Local disconnect does not delete the manual Google Access Token fallback. |
| Uninstall cleanup | `uninstall.php` from Step 213 | whole main settings option cleanup | Plugin uninstall deletes the main settings option, which includes the manual fallback category. |
| Support/debug boundary | Settings / maturation docs | status/category-level evidence only | Support should use status labels and never request token values or option values. |

This inventory records source file names, function names, option key names,
field names, and status categories only. It does not inspect or record option
values, credential values, token values, OAuth client values, serialized option
values, database rows, request bodies, raw responses, AI payload JSON,
generated report bodies, screenshots, browser Network evidence, GA4 Property ID
values, hostname/domain values, or analytics values.

## Current Risk Assessment

The manual Google Access Token fallback remains useful for MVP and developer
verification, but it is weak as a public-release posture.

Risks if kept for public release:

- It encourages direct handling of a Google access token in Settings UI.
- It can be confused with the preferred OAuth credential source.
- It adds support/debug burden because users may be tempted to share token
  values or option data.
- It bypasses a clearer OAuth lifecycle model for expiry, reconnect, scope, and
  provider-side revoke.
- It complicates privacy/readme wording by keeping a temporary developer path
  in the public admin flow.
- It creates more credential storage posture to explain and defend.

MVP boundaries already reducing risk:

- saved values are not redisplayed,
- empty input keeps existing value,
- delete checkbox exists,
- OAuth is described as preferred,
- support/debug wording forbids sharing token or option values,
- uninstall cleanup deletes the main plugin-owned settings option,
- local-only disconnect explicitly does not touch the manual fallback.

These mitigations are acceptable for current MVP maturation, but they do not
settle public-release readiness.

## Retirement Options

| Option | Summary | Public-release classification | Pros | Cons |
|---|---|---|---|---|
| Option 1: Full retirement before public release | Remove the Settings UI field and remove manual fallback from the GA4 credential resolver. | Recommended candidate | Strongest OAuth-first posture; simpler support and privacy story; reduces direct token handling. | Requires migration/cleanup wording, implementation, and source/admin verification. |
| Option 2: Developer-only / constant-gated / diagnostic-only restriction | Hide or disable the manual fallback unless an explicit developer/diagnostic boundary is enabled. | Recommended fallback candidate | Preserves controlled recovery path while keeping public UI OAuth-first. | Requires clear gate, wording, support policy, and tests so it cannot silently become public behavior. |
| Option 3: Keep in Settings UI but strongly discourage | Keep field visible with stronger warning and public-release wording. | Not recommended except as temporary bridge | Lowest implementation churn; preserves current workflow. | Still exposes manual token entry in public admin UI and leaves credential posture harder to defend. |
| Option 4: No change | Keep current MVP behavior and wording for public release. | Not recommended | No immediate implementation work. | Weak public-release posture; leaves temporary developer-verification path in public UX. |

## Recommended Direction

Recommended direction:

```text
Preferred direction: retire or restrict before public release
OAuth credential source: Preferred
Current manual fallback: Accept only within MVP/developer-verification boundary
Current manual fallback for public release: Not recommended without restriction
Production code changes: Not implemented in Step 216
WordPress.org release readiness: Hold
```

Practical ordering:

1. Decide whether full retirement or developer-only restriction is the target.
2. Plan Settings UI changes and resolver changes together.
3. Plan saved manual fallback handling.
4. Update support/debug and readme/privacy wording after the target behavior is
   selected.
5. Implement narrowly in a later production step.

Option 1 is the cleanest public-release posture if OAuth behavior is considered
sufficient for the release boundary. Option 2 is the safer fallback if a
controlled recovery path is still needed during late maturation.

## Impact on Settings UI

If fully retired:

- remove or hide the manual Google Access Token field from normal Settings UI,
- remove the manual fallback saved/not-saved status from normal Settings UI,
- remove the manual fallback delete checkbox from normal Settings UI unless a
  migration/cleanup control is still needed,
- update help text to present OAuth as the GA4 credential source.

If developer-only / diagnostic-only:

- hide the field by default,
- require an explicit developer/diagnostic gate before showing it,
- clearly label it as non-public-release / diagnostic-only,
- keep value-hidden behavior,
- keep status/category evidence only,
- keep delete control if saved fallback values can exist.

If kept visible but discouraged:

- strengthen warning copy,
- explain OAuth-first precedence,
- warn that public/multi-user use should not rely on manual token entry,
- maintain value-hidden field behavior and support/debug restrictions.

Step 216 does not implement any of these UI changes.

## Impact on Credential Resolver

Current resolver posture:

- OAuth token option is preferred when the OAuth lifecycle category is
  connected.
- Manual fallback can be used when no OAuth token source is available.
- Manual fallback can also be used as a fallback category when OAuth token state
  exists but is not usable.
- Missing credential status remains available when neither source is usable.

If fully retired:

- remove manual fallback as a GA4 credential source,
- return missing/reconnect/refresh-needed categories instead of manual fallback
  categories,
- decide how to handle any saved manual fallback value still present in the
  settings option.

If developer-only / diagnostic-only:

- resolver should only consider manual fallback when the explicit gate is
  enabled,
- resolver should otherwise ignore the manual fallback even if a saved value
  exists,
- status labels should make the active source category clear without exposing
  values.

If kept visible but discouraged:

- resolver behavior can remain unchanged, but public-release risk remains.

Step 216 does not change resolver behavior.

## Impact on Local Disconnect and Uninstall Cleanup

Local-only OAuth disconnect:

- deletes only local OAuth token data,
- does not contact Google,
- does not revoke provider-side access,
- does not delete the manual Google Access Token fallback,
- does not delete the OpenAI API key.

Uninstall cleanup:

- root `uninstall.php` deletes the whole main settings option,
- the main settings option includes the manual fallback category,
- uninstall cleanup therefore removes the saved manual fallback as plugin-owned
  data,
- uninstall cleanup does not contact Google and does not perform provider-side
  revoke.

Retirement/restriction planning should preserve this distinction. Local
disconnect should not be redefined as manual fallback cleanup unless a later
step explicitly changes that boundary.

## Support/debug Evidence Boundary

Support/debug should continue to use status/category-level evidence only.

Allowed evidence:

- source file name,
- function / method / option key name,
- UI field name,
- credential source category,
- storage category,
- visible status labels,
- Pass / Hold / Deferred / Recommended / Not recommended classification.

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

Future support wording should not request manual access token values, option
values, screenshots showing credentials, browser Network evidence, or request
headers/bodies.

## Public Release Decision Table

| Decision area | Current state | Public-release posture | Step 216 classification |
|---|---|---|---|
| OAuth credential source | Implemented and preferred | Preferred GA4 credential source | Recommended |
| Manual Google Access Token fallback | Available in Settings and resolver | Temporary MVP/developer-verification path | Hold / Needs decision |
| Manual fallback visible in normal Settings UI | Currently visible | Risky for public release | Not recommended without restriction |
| Manual fallback resolver path | Currently active fallback | Risky for public release if unrestricted | Needs decision |
| Saved manual fallback value handling | Value-hidden, delete checkbox, uninstall cleanup | Needs migration/cleanup policy if retired/restricted | Needs decision |
| Local-only OAuth disconnect | Does not touch manual fallback | Correct separation | Accept within MVP boundary |
| Uninstall cleanup | Deletes main settings option | Cleans plugin-owned saved fallback on uninstall | Matured within MVP boundary |
| Support/debug evidence | Status/category-level only | Must remain strict | Recommended |
| Full retirement | Not implemented | Strongest public-release posture | Recommended candidate |
| Developer-only restriction | Not implemented | Acceptable fallback if recovery path needed | Recommended fallback candidate |
| Keep visible but discouraged | Not implemented | Weak bridge posture | Not recommended except temporary bridge |
| No change | Current behavior | Weak public-release posture | Not recommended |
| WordPress.org release readiness | Hold | Not cleared by this plan | Hold |

## Implementation Candidates for Future Steps

Candidate implementation tracks:

1. Full retirement plan:
   - remove manual fallback field from normal Settings UI,
   - remove manual fallback branch from GA4 credential resolver,
   - keep or add safe cleanup handling for previously saved fallback values,
   - update support/debug and readme/privacy wording.

2. Developer-only restriction plan:
   - define an explicit developer/diagnostic gate,
   - hide the field and resolver path unless the gate is enabled,
   - keep values hidden,
   - keep delete control if saved fallback values may exist,
   - update support/debug and readme/privacy wording.

3. Transitional wording plan:
   - keep behavior unchanged,
   - strengthen UI copy to state the fallback is deprecated or temporary,
   - set a required follow-up to remove/restrict before release readiness.

Step 216 recommends choosing between Option 1 and Option 2 before any
production implementation.

## Recommended Next Step

Recommended next step:

```text
Step 217: Manual Google Access Token fallback public-release decision checkpoint
```

Step 217 should remain docs-only / planning-only and decide whether the target
is:

- full retirement before public release,
- developer-only / diagnostic-only restriction before public release,
- or a short transitional bridge with an explicit removal/restriction deadline.

It should not yet change Settings UI, resolver behavior, GA4 client behavior,
readme wording, or support/debug wording.

## Result Classification

```text
Manual Google Access Token fallback retirement plan: Completed
Manual Google Access Token fallback public-release posture: Hold / Needs decision
Preferred direction: retire or restrict before public release
OAuth credential source: Preferred
Current manual fallback: Accept only within MVP/developer-verification boundary
Current manual fallback for public release: Not recommended without restriction
Production code changes: Not implemented in Step 216
WordPress.org release readiness: Hold
```
