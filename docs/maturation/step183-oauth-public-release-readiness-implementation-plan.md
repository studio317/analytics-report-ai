# Step 183: OAuth Public-release Readiness Implementation Plan

## Step Purpose

Step 183 is a docs-only and planning-only implementation plan for making the
Google OAuth path ready for a future WordPress.org public release.

The purpose is to define the OAuth readiness decisions, implementation order,
verification boundaries, and release judgment conditions before additional
OAuth implementation work proceeds.

This step does not change production code, `readme.txt`, tools, build scripts,
JavaScript, CSS, admin behavior, credential storage, OAuth lifecycle behavior,
GA4 behavior, OpenAI behavior, payload handling, transient handling, release
packaging, or generated report persistence.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step182-oauth-credential-public-release-strategy-checkpoint.md`
- `docs/maturation/step181-public-release-blocker-prioritization-checkpoint.md`
- `docs/maturation/step180-mvp-maturation-remaining-risk-checkpoint.md`
- `docs/maturation/step129-google-authorization-redirect-execution-implementation-results.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`
- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`
- `docs/maturation/step179-support-debug-wording-maturation-checkpoint.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`

## Evidence Boundary

This plan records only status-level and category-level OAuth readiness
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

## OAuth Readiness Scope

The OAuth public-release readiness plan covers:

- OAuth provider configuration model,
- site owner provided OAuth client configuration vs developer managed OAuth
  app,
- consent screen / app verification posture,
- redirect URI setup UX,
- authorization redirect boundary,
- callback handling boundary,
- token exchange boundary,
- token storage boundary,
- token refresh / expiry / reconnect / disconnect / revoke lifecycle,
- manual Google Access Token fallback relationship,
- support/debug safe evidence boundary,
- privacy / `readme.txt` wording implications,
- future human smoke and controlled OAuth verification needs.

## Current OAuth Posture

| Area | Current posture | Public-release impact |
|---|---|---|
| OAuth-first wording | `matured_for_mvp_scope` | Preserve as the preferred user-facing direction. |
| Credential source UI wording | `matured_for_mvp_scope` | Preserve status/category labels and value-hidden posture. |
| Support/debug wording | `matured_for_mvp_scope` | Preserve status/category-level evidence only. |
| Authorization redirect execution boundary | `implemented_narrow_boundary` | Existing redirect slice is not enough for public release by itself. |
| Callback handling boundary | `partial_boundary_exists` | Needs inventory and public-release plan before token exchange is finalized. |
| Token exchange | `not_public_release_finalized` | Release blocker until implementation scope and QA boundary are decided. |
| Refresh / expiry / reconnect / disconnect / revoke lifecycle | `not_public_release_finalized` | Release blocker until lifecycle UX and storage policy are defined. |
| Manual Google Access Token fallback | `mvp_maturation_fallback` | Needs remove/restrict/accept decision before public release. |
| Credential storage strategy | `unresolved` | Must be aligned with OAuth and OpenAI secret storage decisions. |
| WordPress.org release | `Hold` | Remains `Hold`. |

## Readiness Decision Points

| Decision | Options | Recommended direction | Release impact | Needs implementation? | Needs QA? | Priority |
|---|---|---|---|---|---|---|
| Decision 1: OAuth app ownership model | Site-owner client configuration; developer-managed OAuth app; release hold until app strategy is complete. | Decide ownership model before more OAuth implementation. | `release_blocker` | Yes, after decision. | Yes. | P0 |
| Decision 2: Consent screen / app verification posture | Site owner handles consent; developer app verification; explicit release hold. | Define consent and verification expectations before release wording. | `release_blocker` | Possibly. | Yes. | P0 |
| Decision 3: Client ID / client secret input and storage model | Constants only; Settings UI storage; hybrid with active-source labels; external-only setup. | Prefer a deliberate storage/source model aligned with broader credential strategy. | `release_blocker` | Yes. | Yes. | P0 |
| Decision 4: Redirect URI setup guidance | Display guidance only; copyable setup value; automated validation; documentation-only. | Provide safe guidance without exposing support evidence that includes URLs or identifiers. | `release_readiness_risk` | Likely. | Yes. | P1 |
| Decision 5: Token exchange and callback handling scope | Full token exchange now; staged exchange without refresh; defer exchange until lifecycle/storage strategy is complete. | Do not finalize token exchange without storage, cleanup, and lifecycle decisions. | `release_blocker` | Yes. | Yes. | P0 |
| Decision 6: Refresh / expiry / reconnect / revoke / disconnect lifecycle | Full lifecycle before release; reconnect-only model; defer public release. | Define lifecycle UX before accepting public OAuth readiness. | `release_blocker` | Yes. | Yes. | P0 |
| Decision 7: Manual token fallback public-release treatment | Remove; restrict to developer/debug mode; keep as documented public fallback. | Remove or restrict from normal public release path unless explicitly accepted. | `release_blocker` | Likely. | Yes. | P0 |
| Decision 8: Support/debug evidence boundary | Status/category only; redacted UI state; no raw OAuth/provider evidence. | Preserve status/category-only evidence boundary. | `documentation_risk` | Wording may need updates. | Yes. | P1 |
| Decision 9: Privacy / readme wording impact | Update after OAuth strategy; keep current wording until strategy; defer release. | Recheck after OAuth and storage decisions are stable. | `documentation_risk` | Likely. | Yes. | P1 |

## Recommended Implementation Sequence

Recommended safe sequence for Step 184 and later:

1. OAuth source inventory / current implementation boundary review.
2. OAuth app ownership / provider configuration decision.
3. Client configuration storage/display wording plan.
4. Token callback / exchange implementation plan.
5. Lifecycle UX plan: connected, expired, reconnect, disconnect, revoke.
6. Manual token fallback removal/restriction plan.
7. Privacy / `readme.txt` wording alignment plan.
8. Source-level verification.
9. Human-controlled OAuth smoke plan.
10. Controlled OAuth execution, only if explicitly authorized in a later step.

The sequence intentionally starts with source inventory and decision work. It
does not start with token endpoint communication or browser OAuth execution.

## Out Of Scope For Step 183

Step 183 does not include:

- production code change,
- actual OAuth Connect,
- Google navigation,
- token endpoint communication,
- token storage changes,
- option value inspection,
- credential value inspection,
- external API communication,
- browser smoke,
- screenshots,
- browser Network evidence,
- Plugin Check.

## Recommended Strategy After Step 183

Recommended OAuth public-release direction:

- Do not mark OAuth public-release ready yet.
- Decide OAuth app ownership model first.
- Keep manual token fallback restricted or remove it from the normal public
  release path.
- Do not finalize refresh-capable token behavior until storage, lifecycle, and
  uninstall cleanup policies are decided.
- Keep support/debug evidence category/status-level only.
- Keep WordPress.org release status as `Hold`.

## Release Judgment Conditions

OAuth should not be treated as public-release ready until all of the following
are satisfied at status/category level:

- OAuth app ownership model is decided.
- Consent screen / app verification posture is decided.
- Client configuration source and storage model is decided.
- Redirect URI setup guidance is release-safe.
- Callback and token exchange behavior is implemented or explicitly deferred
  with release impact accepted.
- Refresh, expiry, reconnect, disconnect, and revoke lifecycle behavior is
  implemented or explicitly scoped with user-facing limitations.
- Manual token fallback treatment is decided.
- Credential storage and uninstall cleanup policy are aligned.
- Support/debug evidence boundary remains status/category-level only.
- Privacy and `readme.txt` wording are rechecked after strategy decisions.
- Human-controlled OAuth verification is planned and executed only in a later
  explicitly authorized step.

## Acceptance Criteria

Step 183 is complete when:

- this docs-only implementation plan file is added,
- production code, `readme.txt`, tools, build scripts, JavaScript, and CSS have
  no additional Step 183 changes,
- OAuth public-release decision points are organized,
- implementation sequence is explicit,
- WordPress.org release remains `Hold`,
- forbidden-evidence non-recording policy remains explicit,
- the recommended next step is explicit.

## Recommended Next Step

Recommended next step:

```text
Step 184: OAuth source inventory and current implementation boundary review
```

Recommended Step 184 scope:

- docs-only,
- inspection-only,
- source-level inventory of current OAuth implementation boundaries,
- no production code changes,
- no OAuth execution,
- no token endpoint communication,
- no option value inspection,
- no credential value inspection,
- no screenshots or browser Network evidence.

## Not Executed

Step 183 did not execute:

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
test -f docs/maturation/step183-oauth-public-release-readiness-implementation-plan.md && echo "step183_doc_exists"
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```

## Result Classification

Result: `OAuth public-release readiness implementation plan completed`

WordPress.org release remains `Hold`.
