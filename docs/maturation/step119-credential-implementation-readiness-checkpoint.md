# Step 119: Credential Implementation Readiness Checkpoint

## Step Summary

This step records a docs-only readiness checkpoint before credential lifecycle
implementation begins.

Step 116, Step 117, and Step 118 completed implementation plans for Google
OAuth / token lifecycle, OpenAI API key storage, and uninstall credential
cleanup. Step 119 integrates those plans and decides the first implementation
slice, scope boundary, non-goals, QA boundary, and the handling of external API
communication, Plugin Check, and package rebuilds.

This step does not implement OAuth, token exchange, token storage, refresh,
revoke, reconnect UI, OpenAI API key storage changes, uninstall cleanup,
`uninstall.php`, Settings save logic changes, credential storage changes,
admin UI behavior changes, `readme.txt` changes, package rebuilds, Plugin
Check reruns, or external API calls.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step118-uninstall-credential-cleanup-implementation-plan.md`
- `docs/maturation/step117-openai-api-key-storage-implementation-plan.md`
- `docs/maturation/step116-google-oauth-token-lifecycle-implementation-plan.md`
- `docs/maturation/step115-credential-implementation-roadmap-phase-boundary-plan.md`
- `docs/maturation/step114-integrated-credential-architecture-plan.md`
- `docs/maturation/step113-credential-lifecycle-decision-summary-next-implementation-boundary.md`
- `docs/maturation/step112-uninstall-credential-cleanup-policy-decision-checkpoint.md`
- `docs/maturation/step111-openai-api-key-storage-posture-decision-checkpoint.md`
- `docs/maturation/step110-google-oauth-token-lifecycle-strategy-decision-checkpoint.md`
- `docs/maturation/step109-release-readiness-blocker-reprioritization-after-plugin-check-pass.md`
- `docs/maturation/step108-isolated-plugin-check-rerun-clean-package-results.md`

## Current Planning Baseline

| Area | Baseline |
|---|---|
| Google OAuth / token lifecycle | Step 116 implementation plan completed. |
| OpenAI API key storage | Step 117 implementation plan completed. |
| Uninstall credential cleanup | Step 118 implementation plan completed. |
| Manual Google Access Token entry | Developer verification only. |
| Current settings-based OpenAI API key storage | Developer verification only. |
| Uninstall credential cleanup implementation | Not implemented. |
| `uninstall.php` | Status-level source review found it absent in the current source repository. |
| Clean package Plugin Check | Step 108 clean package Plugin Check is recorded as Pass. |
| Package contents blocker | Resolved for the clean package target. |
| Credential lifecycle blocker | Not resolved. |
| External API communication | Not allowed by default until Phase 8. Phase 8 still requires explicit approval for any real external API end-to-end verification. |
| Plugin Check / package rebuild | Deferred until Phase 9. |
| WordPress.org release | `Hold`. |

The Step 108 clean package Plugin Check pass is useful package-readiness
evidence, but it does not resolve the remaining credential lifecycle blocker.

## Candidate First Implementation Slices

### Option A: Step 116 Slice A: OAuth Skeleton / Admin Action Boundary

| Category | Notes |
|---|---|
| Pros | Starts with the largest public-release credential blocker. Establishes the admin action / callback boundary before token exchange, storage, refresh, revoke, or reconnect behavior. Can be reviewed without external API calls if kept to skeleton routing and placeholders. |
| Cons | Even a skeleton introduces security-sensitive admin and callback surfaces. It must avoid implying that OAuth is complete. |
| Rework risk | Medium. The boundary should be designed so later state protection, capability checks, and token exchange can be added without reshaping the route surface. |
| Security / privacy risk | Medium. The risk is controlled by keeping the slice narrow, avoiding token exchange and storage, and documenting capability / nonce / state placeholders clearly. |
| QA impact | Low to medium. Verification should be limited to PHP syntax, source review, safe route/action registration review, and no browser OAuth execution. |
| Release readiness impact | High. This begins closing the largest credential lifecycle blocker while preserving the no-external-API boundary. |
| Recommendation | Recommended. |

### Option B: Step 117 Slice A: OpenAI Credential Source Resolution Skeleton

| Category | Notes |
|---|---|
| Pros | Can start clarifying OpenAI key source precedence and future constant-based configuration without immediately changing external API behavior. May be smaller than OAuth routing. |
| Cons | OpenAI storage design is easier to align after the Google credential boundary is clearer. Starting here may force precedence decisions before the overall credential architecture has its first concrete boundary. |
| Rework risk | Medium. Source precedence may need to change after Google OAuth storage and status behavior become clearer. |
| Security / privacy risk | Medium. Even a skeleton around key resolution can accidentally drift into storage behavior or option handling. |
| QA impact | Low if kept to source review only, but risky if it touches runtime resolution paths before the Google boundary is established. |
| Release readiness impact | Medium. Important, but less foundational than the Google OAuth surface. |
| Recommendation | Not recommended as the first implementation slice. |

### Option C: Step 118 Slice A: Cleanup Target Inventory Docs / Source Review Only

| Category | Notes |
|---|---|
| Pros | Lowest runtime risk. Keeps the next step documentation-only or source-review-only and can clarify delete targets before code changes. |
| Cons | Does not begin closing the primary Google OAuth blocker. It may duplicate the Step 118 planning posture without moving implementation forward. |
| Rework risk | Low. Inventory can be updated after Google/OpenAI storage changes. |
| Security / privacy risk | Low if option values and credential values are not inspected or recorded. |
| QA impact | Low. Verification remains status-level source review and diff checks. |
| Release readiness impact | Low to medium. Useful, but it does not establish an implementation boundary for the main credential blocker. |
| Recommendation | Not recommended as the first implementation slice. Keep it available before uninstall implementation. |

### Option D: Step 118 Slice B: `uninstall.php` Skeleton With Guard Only

| Category | Notes |
|---|---|
| Pros | Creates a safe uninstall entry point and could later host cleanup behavior. A guard-only file has limited runtime impact. |
| Cons | Creates a production file before Google/OpenAI credential storage behavior is known. It may make the cleanup phase look more mature than the delete-target policy actually is. |
| Rework risk | Medium. Cleanup targets and multisite behavior may change after credential implementation. |
| Security / privacy risk | Low to medium. A guard-only skeleton is low risk, but uninstall behavior is security-sensitive and user-trust-sensitive once deletion is added. |
| QA impact | Low for syntax/source review, higher once cleanup behavior is added. |
| Release readiness impact | Medium. It supports uninstall readiness but does not address the largest OAuth lifecycle blocker. |
| Recommendation | Not recommended as the first implementation slice. Defer until Google/OpenAI credential behavior is clearer. |

## Recommended Decision

Recommended: Option A: start with Step 116 Slice A: OAuth skeleton / admin
action boundary.

Rationale:

- Google OAuth is the largest public-release credential blocker.
- Slice A can avoid token exchange, token storage, refresh, revoke, reconnect,
  GA4 behavior changes, and external API calls.
- Establishing the admin action / callback boundary first creates the later
  footing for state protection, capability checks, callback validation, token
  exchange, and connection status handling.
- OpenAI storage and uninstall cleanup can be aligned more cleanly after the
  Google credential boundary is visible.
- Even as a skeleton, admin action and callback surfaces are
  security-sensitive, so the implementation scope must remain narrow and
  reviewable.

## Step 120 Proposed Scope

Recommended next implementation step:

```text
Step 120: Google OAuth skeleton / admin action boundary implementation
```

Step 120 should be limited to:

- OAuth connect action / route skeleton.
- OAuth callback action / route skeleton.
- Settings UI connect affordance placeholder, only if needed.
- Callback result routing placeholder.
- No external token exchange.
- No token storage.
- No refresh.
- No revoke.
- No reconnect UI.
- No GA4 client behavior change.
- No OpenAI storage change.
- No uninstall cleanup.
- No package rebuild.
- No Plugin Check rerun.
- No external API call.

## Step 120 Expected File Boundary

Expected changed-file boundary:

- `includes/class-admin.php`
- `includes/class-settings.php`
- Possible new OAuth-focused include file, only if justified.
- Docs under `docs/maturation/`.

Files and areas that should normally remain unchanged in Step 120:

- `includes/class-ga4-client.php`
- `includes/class-openai-client.php`
- `includes/class-report-builder.php`
- `readme.txt`
- `.distignore`
- Build scripts.
- `uninstall.php`
- Assets, unless a later accepted plan explicitly requires them.

## Step 120 Verification Boundary

Step 120 verification should include:

- PHP syntax checks.
- Source review for route/action registration.
- Source review for nonce, capability, and state placeholder boundaries.
- Confirmation that no browser OAuth execution was performed.
- Confirmation that no external API call was performed.
- Confirmation that no token exchange was performed.
- Confirmation that no credential or option value inspection was performed.
- Confirmation that no screenshots were recorded.
- Confirmation that Plugin Check was not run.
- Confirmation that no package rebuild was performed.
- Git diff checks.

## Risks To Control In Step 120

- Admin action / callback URLs are security-sensitive.
- Callback skeleton work must not block later capability, nonce, and state
  validation design.
- The implementation must not drift into token exchange or credential storage.
- UI wording must not imply that public-ready OAuth is complete.
- Manual token entry must not be removed or changed in Step 120.
- GA4 Fetch behavior must not change in Step 120.
- OpenAI API key storage must not change in Step 120.
- Uninstall cleanup must not start in Step 120.

## Support / Debug Evidence Boundary

The support/debug evidence boundary remains:

- Do not record credentials, API keys, access tokens, or Authorization headers.
- Do not record option values.
- Do not record request bodies, raw responses, AI payload JSON, or generated
  report bodies.
- Do not record GA4 Property ID, hostname/domain, analytics values, page path,
  source, or city values.
- Keep support evidence to status-level labels, redacted saved-state,
  error category, connection state, generation allowed/blocked state, and safe
  UI wording.
- Do not use screenshots or browser Network tab data by default.
- Future OAuth verification must also avoid recording secrets, identifiers,
  analytics values, raw responses, payload bodies, and generated report bodies.

## Recommended Next Step

Recommended next step:

```text
Step 120: Google OAuth skeleton / admin action boundary implementation
```

Step 120 should be a narrow production PHP implementation step. It should not
perform external API communication, token exchange, token storage, GA4 Fetch,
OpenAI Generate, Plugin Check, or package rebuild.

## Explicit Non-goals

- Code change.
- `readme.txt` change.
- `.distignore` / build script change.
- Package rebuild.
- Plugin Check rerun.
- External API call.
- GA4 Fetch / OpenAI Generate.
- OAuth implementation beyond a skeleton boundary.
- Token exchange implementation.
- Refresh implementation.
- Revoke implementation.
- Reconnect UI implementation.
- OpenAI API key storage implementation.
- `uninstall.php` creation.
- Option deletion implementation.
- Settings save logic change.
- Credential storage change.
- Raw payload / raw response / generated report body recording.
- Screenshots.
- Credential / option value inspection.
- UI behavior change.

## Verification

Planned verification for this docs-only step:

- `git diff --check`
- `git diff --name-only`
- `git diff --stat`
- `git status --short --untracked-files=all`

No Plugin Check rerun, package rebuild, external API communication,
GA4 Fetch, OpenAI Generate, credential inspection, option value inspection,
screenshot capture, or browser/network evidence collection is part of this
step.
