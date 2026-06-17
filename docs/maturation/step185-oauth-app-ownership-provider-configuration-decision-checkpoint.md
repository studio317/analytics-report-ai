# Step 185: OAuth App Ownership And Provider Configuration Decision Checkpoint

## Step Purpose

Step 185 is a docs-only and planning-only decision checkpoint for the Google
OAuth app ownership and provider configuration model needed before a future
WordPress.org public release.

The purpose is to compare the public-release options for who owns and operates
the Google OAuth app configuration, then select a strategy direction that can
feed the next implementation planning step.

This step does not change production code, `readme.txt`, tools, build scripts,
JavaScript, CSS, admin behavior, credential storage, OAuth lifecycle behavior,
GA4 behavior, OpenAI behavior, payload handling, transient handling, release
packaging, or generated report persistence.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step184-oauth-source-inventory-current-boundary-review.md`
- `docs/maturation/step183-oauth-public-release-readiness-implementation-plan.md`
- `docs/maturation/step182-oauth-credential-public-release-strategy-checkpoint.md`
- `docs/maturation/step181-public-release-blocker-prioritization-checkpoint.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`
- `docs/maturation/step129-google-authorization-redirect-execution-implementation-results.md`
- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`
- `docs/maturation/step179-support-debug-wording-maturation-checkpoint.md`

## Evidence Boundary

This checkpoint records only status-level and category-level decision
information.

It does not display, inspect, or record:

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

## Decision Scope

Step 185 covers:

- OAuth app ownership model,
- Google Cloud project / OAuth client configuration responsibility,
- consent screen / app verification responsibility,
- redirect URI setup responsibility,
- client ID / client secret source model,
- Settings UI implications,
- support/debug evidence boundary,
- privacy / `readme.txt` wording implications,
- release support burden,
- future QA / smoke requirements.

This step does not decide token lifecycle, credential storage implementation,
manual token fallback implementation, or uninstall cleanup implementation. It
selects the app ownership direction those later decisions should assume.

## Current Source Boundary Summary

| Area | Current boundary | Public-release status |
|---|---|---|
| OAuth-first wording | `matured_for_mvp_scope` | Preserve. |
| OAuth connect action | `implemented_narrow_boundary` | Not enough by itself for public release. |
| OAuth callback action | `partial_boundary_exists` | Needs lifecycle/storage strategy. |
| Authorization redirect | `implemented_narrow_boundary` | Needs provider configuration strategy. |
| Token exchange construction | `implemented_narrow_boundary` | Needs public-release QA and lifecycle decisions. |
| Token storage helper | `implemented_narrow_boundary` | Needs storage/cleanup policy. |
| Refresh / revoke / reconnect / disconnect | `not_implemented` | Release blocker until scoped or explicitly deferred. |
| Manual token fallback | `mvp_maturation_fallback` | Public-release treatment unresolved. |
| Client configuration source | `constant_status_presence_boundary_exists` | Needs public-release source strategy. |
| Support/debug wording | `matured_for_mvp_scope` | Preserve status/category-only evidence. |
| WordPress.org release | `Hold` | Remains `Hold`. |

## Option Comparison

| Option | Description | Benefits | Risks / costs | Implementation impact | Documentation impact | Support impact | Release impact | Recommended status |
|---|---|---|---|---|---|---|---|---|
| Option A: site-owner provided OAuth client configuration | Each site owner creates and configures their own Google OAuth client and provides the plugin with the client configuration source selected by the release strategy. | Avoids shared app operation by plugin developer; aligns ownership of GA4 access with the site owner; reduces centralized operational responsibility; fits current constant/status source boundary. | Setup is more complex; site owners must configure provider settings correctly; support must explain setup categories without collecting forbidden evidence. | Needs client source strategy, Settings wording, redirect URI guidance, setup validation categories, and QA plan. | Needs clear setup docs, privacy wording, and status-level troubleshooting guidance. | Setup burden shifts toward configuration support. | Preferred initial public-release strategy candidate, but not release-ready until implementation and docs are complete. | `preferred` |
| Option B: developer-managed OAuth app | Plugin developer operates a shared OAuth app for plugin users. | May simplify setup for site owners; can reduce per-site configuration steps. | Adds consent screen, app verification, privacy, support, security, quota, and operational responsibility for plugin developer; harder to keep public support evidence value-free; may require more formal provider governance. | Requires a separate product/security/operations design before implementation. | Requires stronger public privacy, provider, support, and operational disclosures. | Support burden shifts to app operator and provider verification issues. | Not recommended for the initial public-release path in the current maturity state. | `not_recommended_for_initial_release` |
| Option C: continue release hold until app ownership / provider configuration strategy is resolved | Do not select an app ownership model yet; keep release blocked. | Avoids premature public-release claims; leaves time to align storage, lifecycle, and uninstall cleanup. | Maturation can stall; implementation planning remains blocked; public release cannot progress. | No immediate implementation. | Existing hold rationale remains but final docs cannot be completed. | Avoids new support burden but does not improve setup path. | Acceptable only if Option A planning cannot proceed safely. | `acceptable_with_followup` |

## Option A: Site-owner Provided OAuth Client Configuration

Option A assumes each site owner creates and maintains the OAuth client
configuration for their own site.

Expected responsibilities:

- Site owner manages the Google Cloud project and OAuth client setup.
- Site owner is responsible for consent screen configuration and any provider
  eligibility or testing posture.
- Site owner registers the redirect URI category shown by the plugin.
- Plugin provides status-level setup guidance and client configuration source
  handling.
- Plugin does not operate a shared OAuth app for users.

Implications:

- Client ID / client secret source strategy still needs a dedicated plan.
- Constant-based configuration is a strong fit for the current source boundary,
  but Settings UI storage/display and value-hidden behavior still need
  strategy decisions.
- Redirect URI setup wording must avoid asking users to send full URLs,
  screenshots, browser address bars, provider console screenshots, or client
  values as support evidence.
- Support should ask for setup status categories only.
- Public release still needs token lifecycle, manual fallback, storage, and
  uninstall cleanup decisions.

Public-release benefit:

- App ownership and analytics access stay with the site owner.
- Plugin developer does not become the operator of a shared Google OAuth app.
- This is the lowest-operational-risk initial public-release strategy
  candidate among the compared options.

Public-release cost:

- Setup complexity is higher.
- Documentation and Settings guidance must be strong enough to prevent unsafe
  support requests and configuration confusion.

## Option B: Developer-managed OAuth App

Option B assumes the plugin developer operates a shared OAuth app for plugin
users.

Expected responsibilities:

- Plugin developer owns provider configuration and app verification posture.
- Plugin developer carries more responsibility for consent screen, privacy,
  support, operational reliability, and provider-policy changes.
- Site-owner setup may be simpler, but support and compliance burden becomes
  more centralized.

Implications:

- Requires a broader product and operations decision before implementation.
- Requires stronger privacy, support, and provider-facing documentation.
- May require additional verification, monitoring, and incident-response
  planning outside the current MVP maturation scope.

Current status:

```text
developer_managed_oauth_app_initial_release_status: not_recommended_for_initial_release
```

This option can remain a future phase candidate, but it should not be the
initial public-release strategy while storage, lifecycle, uninstall cleanup,
and public support evidence boundaries are still being finalized.

## Option C: Continue Release Hold

Option C keeps the release blocked until app ownership and provider
configuration strategy are fully resolved.

Benefits:

- Avoids making an unsafe public-release claim.
- Gives storage, lifecycle, provider configuration, and uninstall cleanup
  decisions time to align.

Costs:

- Delays public release.
- Leaves implementation planning blocked if no ownership direction is selected.

Current status:

```text
continue_release_hold_status: acceptable_as_guardrail_not_primary_strategy
```

This remains the fallback if Option A planning exposes a blocker that cannot be
resolved safely. It is not the preferred active strategy because Step 186 can
now plan the site-owner configuration source model without executing OAuth or
recording forbidden evidence.

## Recommended Decision

Recommended OAuth app ownership direction:

```text
Site-owner provided OAuth client configuration is the preferred initial public-release strategy candidate.
```

Decision status:

```text
OAuth app ownership decision status: Strategy direction selected, implementation plan still required
WordPress.org release status: Hold
```

Rationale:

- It aligns OAuth app ownership with the site owner who controls the GA4
  property access and provider configuration.
- It avoids placing shared OAuth app operation, verification, support, and
  privacy obligations on the plugin developer during the initial public-release
  path.
- It fits the current source boundary where client configuration can be
  detected as status-level presence without exposing values.
- It still requires implementation planning for client source strategy,
  Settings UI, redirect URI guidance, token lifecycle, manual fallback
  treatment, storage, uninstall cleanup, privacy wording, and QA.

This is not a release-ready decision. It is a strategy direction for the next
planning step.

## Follow-up Implications

Follow-up needed after selecting Option A as the strategy candidate:

| Follow-up | Needed outcome |
|---|---|
| client ID / client secret source strategy | Decide constants-only, Settings storage, or hybrid source model. |
| constant-based configuration vs Settings storage | Define which sources are allowed for public release and how active source is displayed safely. |
| value-hidden posture | Preserve non-redisplay and avoid exposing client or token values. |
| delete semantics | Define what Settings delete controls can remove when constants or dedicated token options are active. |
| redirect URI setup wording | Provide setup guidance without requesting unsafe support evidence. |
| consent screen setup documentation | Explain site-owner responsibility using category-level guidance only. |
| token exchange lifecycle plan | Define callback, exchange, expiry, refresh/reconnect, disconnect, and revoke behavior. |
| manual token fallback restriction/removal plan | Decide whether manual fallback is hidden, removed, or developer-only before release. |
| privacy / `readme.txt` wording alignment | Recheck after client source and lifecycle decisions. |
| QA / smoke requirements | Plan human-controlled OAuth verification without recording URLs, codes, tokens, screenshots, or Network evidence. |

## Acceptance Criteria

Step 185 is complete when:

- this docs-only decision checkpoint file is added,
- production code, `readme.txt`, tools, build scripts, JavaScript, and CSS have
  no additional Step 185 changes,
- OAuth app ownership options are compared,
- recommended app ownership direction is explicit,
- remaining implementation, documentation, and QA follow-up is clear,
- WordPress.org release remains `Hold`,
- forbidden-evidence non-recording policy remains explicit,
- the recommended next step is explicit.

## Recommended Next Step

Recommended next step:

```text
Step 186: OAuth client configuration source strategy implementation plan
```

Recommended Step 186 scope:

- docs-only,
- planning-only,
- assume site-owner provided OAuth client configuration as the initial
  public-release strategy candidate,
- plan client ID / client secret source strategy,
- compare constant-based configuration, Settings UI storage/display, active
  source labels, value-hidden posture, and delete semantics,
- do not change production code,
- do not execute OAuth,
- do not navigate to Google,
- do not call token endpoints,
- do not inspect option values or credential values,
- do not collect screenshots or browser Network evidence.

## Not Executed

Step 185 did not execute:

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

Safe docs-only commands for this checkpoint:

```bash
git status --short --untracked-files=all
test -f docs/maturation/step185-oauth-app-ownership-provider-configuration-decision-checkpoint.md && echo "step185_doc_exists"
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```

## Result Classification

Result: `OAuth app ownership and provider configuration decision checkpoint completed`

WordPress.org release remains `Hold`.
