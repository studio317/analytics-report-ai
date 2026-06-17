# Step 182: OAuth and Credential Public-release Strategy Checkpoint

## Step Purpose

Step 182 is a docs-only and planning-only strategy checkpoint for the P0
OAuth, credential, token lifecycle, and uninstall cleanup release blockers
classified in Step 181.

The purpose is to treat those blockers as one public-release strategy track so
later implementation planning can avoid contradictory choices across OAuth,
manual token fallback, credential storage, OpenAI API key storage, secret
handling, uninstall cleanup, and release/privacy wording.

This step does not change production code, `readme.txt`, tools, build scripts,
JavaScript, CSS, admin behavior, credential storage, OAuth lifecycle behavior,
GA4 behavior, OpenAI behavior, payload handling, transient handling, release
packaging, or generated report persistence.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step181-public-release-blocker-prioritization-checkpoint.md`
- `docs/maturation/step180-mvp-maturation-remaining-risk-checkpoint.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`
- `docs/maturation/step129-google-authorization-redirect-execution-implementation-results.md`
- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`
- `docs/maturation/step179-support-debug-wording-maturation-checkpoint.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`

## Evidence Boundary

This checkpoint records only status-level and category-level strategy
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

## Strategy Scope

Step 182 treats the following as one public-release strategy track:

- Google OAuth production readiness,
- Google OAuth consent screen / app verification posture,
- token exchange / refresh / revoke / reconnect lifecycle,
- manual Google Access Token fallback,
- Google credential storage,
- OpenAI API key storage,
- token encryption / secret handling,
- uninstall cleanup for credential-bearing data,
- release wording / privacy wording implications.

The track is intentionally grouped because decisions in one area affect the
others. For example, refresh-capable Google credentials should not be finalized
without a storage and cleanup policy, and manual token fallback should not be
positioned for public release without a clear OAuth strategy.

## Current Posture Summary

| Area | Current posture | Source | Public-release status |
|---|---|---|---|
| OAuth-first admin wording | Matured for current MVP scope. | Step 171 | Preserve. |
| Manual Google Access Token wording | Framed as `MVP maturation fallback`. | Step 171 | Needs public-release decision. |
| Credential source UI wording | Matured for current MVP scope. | Step 171 | Preserve value-hidden/category posture. |
| Support/debug wording | Matured for current MVP scope. | Step 179 | Preserve status/category-only evidence boundary. |
| OAuth redirect execution boundary | Narrow redirect execution exists; token exchange and storage were not completed in that slice. | Step 129 | Needs production readiness plan. |
| Public-release credential storage strategy | Not finalized. | Step 81, Step 181 | Release blocker. |
| Token lifecycle completeness | Not finalized. | Step 81, Step 129, Step 181 | Release blocker. |
| OpenAI API key storage posture | Not finalized for public release. | Step 81, Step 181 | Release blocker. |
| Uninstall cleanup policy | Not finalized. | Step 81, Step 181 | Release blocker. |
| Privacy/support wording | Aligned for current scope. | Step 97, Step 179 | Needs final wording recheck after strategy. |
| WordPress.org release | `Hold`. | Step 181 | Remains `Hold`. |

## Strategy Decision Options

### Google OAuth Production Readiness

| Option | Description | Release implication |
|---|---|---|
| Option A | Require site owner provided OAuth client configuration. | Avoids a shared developer-managed app, but creates setup and support burden. Needs clear docs and safe status-level diagnostics. |
| Option B | Use a plugin developer managed OAuth app. | May simplify setup, but increases app verification, privacy, support, and operational responsibility. Requires a separate product/security decision. |
| Option C | Defer public release until the OAuth app verification strategy is complete. | Keeps release blocked, but avoids presenting an incomplete OAuth posture as public-ready. |

Recommended direction: `needs_dedicated_production_readiness_plan`.

Near-term strategy should plan OAuth production readiness before another
implementation slice. The plan should decide whether the release path is
site-owner client configuration, developer-managed app, or continued release
hold until the verification model is complete.

### Token Exchange / Refresh / Revoke / Reconnect Lifecycle

| Option | Description | Release implication |
|---|---|---|
| Option A | Implement full token exchange, expiry tracking, refresh, revoke, disconnect, and reconnect before release. | Stronger public UX, but larger implementation and QA scope. Must include storage and cleanup policy. |
| Option B | Implement token exchange without refresh-capable lifecycle and require explicit reconnect. | Smaller scope, but public UX and documentation must clearly explain expiry and reconnect behavior. |
| Option C | Keep token endpoint behavior out of public release until lifecycle/storage strategy is complete. | Preserves safety while delaying public release readiness. |

Recommended direction: `needs_lifecycle_plan_before_token_finalization`.

Refresh-capable or long-lived credential behavior should not be finalized until
storage, support evidence, reconnect/disconnect, revoke, and uninstall cleanup
policy are defined.

### Manual Google Access Token Fallback

| Option | Description | Release implication |
|---|---|---|
| Option A | Remove from public release UI. | Reduces public credential handling risk and support confusion; requires OAuth path to be ready or release remains blocked. |
| Option B | Hide behind developer/debug mode. | Preserves developer verification while avoiding normal public UI exposure; needs explicit wording and access boundary. |
| Option C | Keep as documented MVP fallback. | Lowest implementation change, but conflicts with prior release-risk posture unless explicitly accepted as a public risk. |

Recommended direction: `remove_or_restrict_before_public_release`.

Manual Google Access Token entry should not be treated as public-release ready
without a deliberate exception. The safer direction is to remove it from normal
public UI or restrict it to a developer/debug posture after OAuth strategy is
decided.

### Credential Storage Strategy

| Option | Description | Release implication |
|---|---|---|
| Option A | Continue `wp_options` storage with non-redisplay and clear disclosure. | Keeps implementation simpler, but requires explicit public acceptance of database/backup/server-admin access risk. |
| Option B | Support `wp-config.php` constants for secrets. | Avoids admin-form persistence for some deployments and aligns with site-owner secret management, but needs clear active-source UI and delete semantics. |
| Option C | Redesign storage before public release. | Best if refresh-capable tokens or broader public use are required, but increases scope. |

Recommended direction: `decide_constant_based_option_and_or_disclosed_wp_options_posture`.

The public-release strategy should decide whether constant-based configuration
is required, whether `wp_options` storage remains acceptable with disclosure,
and whether Google and OpenAI secrets share the same storage posture.

### Token Encryption / Secret Handling

| Option | Description | Release implication |
|---|---|---|
| Option A | No ad hoc encryption; rely on WordPress admin/database security plus disclosure. | Avoids misleading or incomplete encryption, but requires explicit acceptance of storage risk. |
| Option B | Implement scoped encryption design. | May reduce some database exposure risks, but must define keys, rotation, backup behavior, migration, and failure modes. |
| Option C | Avoid storing refresh-capable tokens until lifecycle/storage strategy is complete. | Keeps public release blocked or limited, but avoids premature storage of higher-impact credentials. |

Recommended direction: `avoid_ad_hoc_encryption_decision`.

Do not add opportunistic encryption as a narrow patch. Secret handling should
be decided with the credential storage model and token lifecycle model.

### OpenAI API Key Storage

| Option | Description | Release implication |
|---|---|---|
| Option A | Continue Settings storage with non-redisplay and disclosure. | Simple and already familiar in the MVP, but requires explicit public acceptance of option-storage risk. |
| Option B | Support `wp-config.php` constant. | Gives site owners a non-admin-form storage path and can align with broader secret handling. |
| Option C | Require user-supplied key per request without persistence. | Reduces persistence, but likely harms usability and may increase accidental exposure in forms/support. |

Recommended direction: `align_with_broader_credential_storage_strategy`.

OpenAI API key storage should be decided alongside Google credential storage
so the Settings UI, support wording, and release disclosures do not conflict.

### Uninstall Cleanup

| Option | Description | Release implication |
|---|---|---|
| Option A | Delete credential-bearing plugin options on uninstall. | Strong privacy posture, but users lose configuration on reinstall. Needs explicit docs and safe implementation. |
| Option B | Preserve non-secret settings but delete tokens/secrets. | Balanced posture, but requires a reliable data inventory and source separation. |
| Option C | Provide an explicit setting before uninstall cleanup behavior. | Gives user control, but increases UI and lifecycle complexity. |

Recommended direction: `define_before_release_readiness`.

Uninstall cleanup should follow the final storage model. If secret-bearing and
non-secret settings remain mixed, cleanup policy and implementation need a
clear inventory before release readiness.

## Recommended Public-release Strategy Direction

Recommended public-release strategy direction:

- Google OAuth: `needs_dedicated_production_readiness_plan`.
- Manual token fallback: `remove_or_restrict_before_public_release`.
- Credential storage: `decide_constant_based_option_and_or_disclosed_wp_options_posture`.
- Refresh-capable tokens: `do_not_finalize_until_lifecycle_and_cleanup_policy_are_defined`.
- Token encryption / secret handling: `avoid_ad_hoc_encryption_decision`.
- OpenAI API key: `align_with_broader_credential_storage_strategy`.
- Uninstall cleanup: `define_before_release_readiness`.
- Release wording / privacy wording: `recheck_after_strategy_decisions`.

This direction keeps release on hold until the strategy is converted into
implementation plans and any accepted release risks are documented explicitly.

## Release Blocker Status After Step 182

| P0 item | Step 182 status | Next need |
|---|---|---|
| OAuth production readiness / app verification / consent screen readiness | `needs_implementation_plan` | Dedicated OAuth public-release readiness implementation plan. |
| Token exchange / refresh / revoke / reconnect lifecycle | `needs_policy_decision` | Decide lifecycle scope before token endpoint behavior is public-release scoped. |
| Manual Google Access Token fallback public-release posture | `needs_policy_decision` | Decide remove, restrict, or explicitly accept as public fallback. |
| Credential storage strategy for public release | `needs_policy_decision` | Decide constant-based, disclosed `wp_options`, redesign, or combined model. |
| Token encryption / secret handling strategy | `defer_until_storage_strategy_complete` | Decide only after credential storage and lifecycle strategy are scoped. |
| OpenAI API key storage posture | `needs_policy_decision` | Align with broader credential storage strategy. |
| Uninstall cleanup policy for credential-bearing data | `needs_source_inventory` | Inventory credential-bearing options and define cleanup behavior. |
| Release wording / privacy wording implications | `needs_docs_alignment` | Recheck after OAuth/storage/uninstall strategy decisions. |

## Recommended Next Steps

Recommended next step:

```text
Step 183: OAuth public-release readiness implementation plan
```

Recommended Step 183 scope:

- docs-only,
- planning-only,
- decide the OAuth public-release readiness path before additional OAuth
  implementation,
- cover provider configuration model, app verification posture, consent screen
  readiness, token exchange boundary, refresh/reconnect/revoke expectations,
  safe support evidence, and remaining browser/human smoke needs,
- keep WordPress.org release status as `Hold`,
- do not execute OAuth Connect, Google navigation, token endpoint
  communication, GA4 Fetch, OpenAI Generate, Plugin Check, browser admin smoke,
  screenshots, or browser Network evidence collection.

Additional later candidates:

- `Step 184: Credential storage public-release strategy implementation plan`
- `Step 185: Uninstall cleanup policy checkpoint`

## Acceptance Criteria

Step 182 is complete when:

- this docs-only strategy checkpoint file is added,
- production code, `readme.txt`, tools, build scripts, JavaScript, and CSS have
  no additional Step 182 changes,
- P0 release blockers are organized at strategy level,
- public-release strategy direction is explicit,
- remaining decision and implementation needs are clear,
- WordPress.org release remains `Hold`,
- forbidden-evidence non-recording policy remains explicit,
- the recommended next step is explicit.

## Not Executed

Step 182 did not execute:

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
test -f docs/maturation/step182-oauth-credential-public-release-strategy-checkpoint.md && echo "step182_doc_exists"
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```

## Result Classification

Result: `OAuth and credential public-release strategy checkpoint completed`

WordPress.org release remains `Hold`.
