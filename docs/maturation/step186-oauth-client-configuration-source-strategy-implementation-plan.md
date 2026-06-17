# Step 186: OAuth Client Configuration Source Strategy Implementation Plan

## Step Purpose

Step 186 is a docs-only and planning-only implementation plan for the OAuth
client ID / client secret source strategy under the site-owner provided OAuth
client configuration direction selected in Step 185.

The purpose is to compare constant-based configuration, Settings UI storage,
and hybrid source models, then define the active source labels, precedence,
value-hidden posture, delete semantics, and support/debug evidence boundary
needed before production implementation planning.

This step does not change production code, `readme.txt`, tools, build scripts,
JavaScript, CSS, admin behavior, credential storage, OAuth lifecycle behavior,
GA4 behavior, OpenAI behavior, payload handling, transient handling, release
packaging, or generated report persistence.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step185-oauth-app-ownership-provider-configuration-decision-checkpoint.md`
- `docs/maturation/step184-oauth-source-inventory-current-boundary-review.md`
- `docs/maturation/step183-oauth-public-release-readiness-implementation-plan.md`
- `docs/maturation/step182-oauth-credential-public-release-strategy-checkpoint.md`
- `docs/maturation/step181-public-release-blocker-prioritization-checkpoint.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`
- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`
- `docs/maturation/step179-support-debug-wording-maturation-checkpoint.md`

## Evidence Boundary

This implementation plan records only status-level and category-level strategy
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

## Strategy Assumptions

Step 186 assumes:

- Initial public release strategy candidate:
  `site_owner_provided_oauth_client_configuration`.
- Developer-managed OAuth app:
  `not_selected_for_initial_release`.
- OAuth client values are not support/debug evidence.
- OAuth client ID and client secret values must not be displayed or recorded in
  UI, docs, logs, support, QA evidence, screenshots, or Network evidence.
- Admin UI and support/debug should use status/category-level presence labels
  only.
- Manual Google Access Token fallback remains a separate unresolved
  public-release track and is not finalized in this plan.
- WordPress.org release remains `Hold`.

## Source Strategy Options

| Option | Description | Benefits | Risks / costs | Security posture | UX impact | Support/debug impact | Implementation impact | Release impact | Recommended status |
|---|---|---|---|---|---|---|---|---|---|
| Option A: constants-only OAuth client configuration | Site owner defines client ID and client secret outside plugin settings using constants. | Strongest fit for value-hidden posture; avoids storing OAuth client values in plugin options; current source already has constant presence checks. | Less friendly for non-technical admins; requires file/server configuration; cannot be edited from Settings UI. | Better than option storage for many site-owner deployments because secrets are not saved by plugin settings. | Setup is more technical. | Support can ask for presence status only, not values. | Smallest storage surface; Settings needs status labels and guidance. | Good candidate, but may limit usability for public release. | `acceptable_with_followup` |
| Option B: Settings UI storage with value-hidden non-redisplay | Site owner enters client ID and client secret in Settings, values are stored and never redisplayed. | Easier setup for admins; aligns with common plugin settings patterns. | Adds another credential-bearing option surface; delete/replacement behavior and storage disclosure must be clear. | Weaker than constants-only unless explicitly accepted and disclosed. | Easier setup; more familiar UI. | Higher risk of users pasting values into support unless wording is strong. | Requires sanitize/save/delete/non-redisplay implementation and docs. | Possible but not recommended alone for initial strategy. | `not_recommended_for_initial_release` |
| Option C: hybrid model: constants preferred, Settings UI fallback | Constants take precedence when present; Settings UI values can be used only when constants are missing. Both paths remain value-hidden and status/category-labeled. | Balances safer constant path with setup usability; supports advanced and less technical deployments; active source can be shown safely. | More complex precedence, conflict, delete, and support semantics. | Strong if constants are preferred and Settings storage is disclosed/value-hidden. | Better than constants-only while preserving a safer preferred path. | Requires clear source labels and no value sharing. | Requires source resolver, active source labels, Settings save/delete behavior, and verification. | Preferred initial implementation strategy candidate, pending source inventory. | `preferred` |
| Option D: defer client configuration public-release support until storage strategy is finalized | Do not implement public client source support yet; keep release blocked. | Avoids premature storage decisions. | Blocks OAuth public-release readiness and delays release path. | Safest short-term hold. | No public setup improvement. | Avoids support burden but leaves no release path. | No immediate implementation. | Useful only if hybrid planning exposes unacceptable risk. | `defer` |

## Recommended Source Strategy

Recommended OAuth client configuration source strategy:

```text
Hybrid model with constants preferred and Settings UI fallback, using value-hidden status/category labels only.
```

Rationale:

- Constants match the current source boundary and avoid storing OAuth client
  values in plugin settings when site owners can configure them outside the UI.
- Settings UI fallback can reduce setup friction for site owners who cannot
  edit configuration files, but it must remain value-hidden and explicitly
  disclosed.
- A hybrid model gives a clear public-release path while still encouraging the
  safer source when available.
- Constants precedence keeps operationally managed deployments predictable.

This is an implementation-plan recommendation, not a production change or
release-ready decision.

## Active Source Labels

Public-release UI and support/debug may use only status/category labels such as:

| Label | Meaning |
|---|---|
| `oauth_client_source_category: constants` | OAuth client configuration is available from constants. |
| `oauth_client_source_category: settings` | OAuth client configuration is available from plugin Settings storage. |
| `oauth_client_source_category: missing` | Required OAuth client configuration is not available. |
| `oauth_client_source_category: incomplete` | Only part of the required OAuth client configuration is available. |
| `oauth_client_source_category: conflict` | Constants and Settings storage both have configuration categories and precedence rules apply. |
| `oauth_client_value_hidden_status: hidden` | OAuth client values are not displayed. |

These labels must not include actual OAuth client ID values, OAuth client
secret values, fragments, prefixes, suffixes, option values, provider console
details, URLs, query strings, screenshots, or Network evidence.

## Settings UI Implications

Recommended Settings UI plan for the hybrid model:

| Scenario | Planned UI behavior |
|---|---|
| Constants are defined and complete | Show active source category as `constants`. Do not show values. Settings fallback fields may remain empty and optional. |
| Settings UI values are saved and constants are missing | Show active source category as `settings`. Do not show saved values. Use saved/not-saved presence labels only. |
| Constants and Settings UI values both exist | Constants take precedence. Show active source category as `constants` and conflict category as applicable. Do not show either source value. |
| Missing configuration | Show `missing` status and explain that OAuth cannot start until required configuration is present. |
| Incomplete configuration | Show `incomplete` status without saying which value is present if that would encourage value sharing; use safe category wording. |
| Replacement behavior | Empty inputs keep existing Settings UI values; entering a new value replaces only the Settings UI stored value. |
| Delete behavior | Delete controls remove only Settings UI stored OAuth client values, not constants and not OAuth tokens. |
| Value-hidden non-redisplay | Saved client values are never placed in input `value` attributes or support evidence. |
| Support-safe hint | Ask for source category, presence status, and value-hidden status only. |

## Delete Semantics

Planned delete semantics:

- Settings UI stored OAuth client values can be deleted from Settings.
- Constants-derived values cannot be deleted from Settings UI.
- If constants are active, a Settings delete action should clearly state that
  it affects only Settings UI stored fallback values.
- OAuth client configuration delete is separate from OAuth token storage delete,
  disconnect, revoke, and reconnect behavior.
- Deleting Settings UI client values does not imply provider-side revoke.
- Deleting Settings UI client values does not delete stored OAuth tokens unless
  a later lifecycle/uninstall design explicitly says so.
- Result notices should remain status/category-level and should not display
  values, fragments, option dumps, request bodies, or provider responses.

## Conflict / Precedence Handling

Recommended precedence:

```text
Constants should take precedence over Settings UI stored values.
```

Planned conflict handling:

- If constants and Settings UI values both exist, runtime should use constants.
- Admin UI should show the active source as a category label.
- Admin UI may show a safe conflict category if Settings fallback values exist
  but are inactive because constants take precedence.
- Support/debug should ask only for active source category, conflict category,
  and value-hidden status.
- No actual client values, option values, snippets, screenshots, or Network
  evidence should be requested or recorded to resolve conflicts.

## Support / Debug Evidence Boundary

Forbidden evidence remains:

- OAuth client ID values,
- OAuth client secret values,
- credentials,
- API keys,
- access tokens,
- refresh tokens,
- Authorization headers,
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
- database dumps.

Allowed support/debug evidence is limited to:

- OAuth client source category,
- required configuration presence status,
- active source label,
- incomplete/conflict category,
- value-hidden status,
- safe admin notice category.

## Implementation Sequence

Recommended safe sequence for Step 187 and later:

1. Source-level inventory of current client configuration helpers.
2. Source strategy decision checkpoint against current helpers.
3. Narrow production implementation plan.
4. Settings wording / active source label implementation.
5. Settings UI storage and value-hidden fallback implementation, if accepted.
6. Delete semantics implementation for Settings UI stored client values.
7. Source-level verification.
8. Human admin smoke plan.
9. Human admin smoke results.

This sequence should precede any OAuth execution, token endpoint
communication, or browser smoke involving Google.

## Acceptance Criteria

Step 186 is complete when:

- this docs-only implementation plan file is added,
- production code, `readme.txt`, tools, build scripts, JavaScript, and CSS have
  no additional Step 186 changes,
- OAuth client configuration source options are compared,
- recommended source strategy is explicit,
- active source labels, delete semantics, and precedence are organized,
- WordPress.org release remains `Hold`,
- forbidden-evidence non-recording policy remains explicit,
- the recommended next step is explicit.

## Recommended Next Step

Recommended next step:

```text
Step 187: OAuth client configuration source-level inventory
```

Recommended Step 187 scope:

- docs-only,
- inspection-only,
- source-level inventory of current OAuth client configuration helpers,
  constant source, Settings UI storage/display, delete semantics, and active
  source label status,
- no production code changes,
- no OAuth execution,
- no token endpoint communication,
- no option value inspection,
- no credential value inspection,
- no screenshots or browser Network evidence.

## Not Executed

Step 186 did not execute:

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
test -f docs/maturation/step186-oauth-client-configuration-source-strategy-implementation-plan.md && echo "step186_doc_exists"
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```

## Result Classification

Result: `OAuth client configuration source strategy implementation plan completed`

WordPress.org release remains `Hold`.
