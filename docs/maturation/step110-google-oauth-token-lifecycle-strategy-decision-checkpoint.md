# Step 110: Google OAuth / Token Lifecycle Strategy Decision Checkpoint

## Step Summary

This step records a decision checkpoint for Google OAuth and token lifecycle
strategy before public release.

Step 109 selected Google OAuth and token lifecycle as the next
release-readiness blocker to close. Step 110 does not implement OAuth,
authorization callbacks, token exchange, refresh handling, revocation,
reconnect UI, credential storage changes, or uninstall behavior changes.

The purpose is to separate posture decisions from implementation. This document
clarifies the current MVP/developer verification posture, the public release
posture, and the later token lifecycle topics that must be designed before
WordPress.org release can proceed.

Production code was not changed. PHP, JavaScript, CSS, `readme.txt`,
`.distignore`, build scripts, release packages, Plugin Check, admin UI
behavior, Settings save logic, GA4 client behavior, OpenAI client behavior,
credential storage, and uninstall behavior were not changed.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step109-release-readiness-blocker-reprioritization-after-plugin-check-pass.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step96-generated-report-handling-policy-finalization.md`
- `docs/maturation/step95-payload-preview-json-visibility-final-decision.md`
- `docs/maturation/step104-readme-privacy-wording-alignment-implementation-results.md`

## Current State

The current MVP has used manual Google Access Token entry to verify GA4 Fetch
in controlled developer verification.

That manual token entry model is useful for trusted local verification because
it keeps the MVP flow small and allows the GA4 Fetch, Payload Preview, and AI
Generate flow to be tested without implementing a full Google connection
experience.

However, manual token entry is not sufficient as a public WordPress.org release
posture. It does not provide a normal user-facing connection flow and does not
fully address token lifecycle, expiration, refresh, revocation, reconnect, error
recovery, support expectations, or long-term storage posture.

Current status:

- Manual Google Access Token entry is acceptable only for controlled developer
  verification.
- Manual token entry should not be treated as public-release ready.
- Token lifecycle and OAuth connection behavior remain unresolved.
- WordPress.org release remains `Hold`.

## Release Posture Options

### Option A: Keep Manual Google Access Token Entry In Public Release

Pros:

- Lowest implementation effort.
- Preserves the current MVP settings and GA4 Fetch flow.
- Keeps developer verification behavior unchanged.

Cons:

- Requires users to obtain and paste a Google credential manually.
- Does not provide a normal OAuth connection flow.
- Does not resolve expiration, refresh, reconnect, or revocation behavior.
- Creates a high support burden for token setup, expiry, permission, and
  property-access failures.
- Keeps a developer-oriented credential flow in a public-facing plugin.

Release readiness impact:

- Not release-ready as the public default posture.
- Would keep a major credential lifecycle blocker open.

Privacy / support impact:

- Support pressure may lead users to share credentials, token fragments, option
  values, screenshots, or other sensitive evidence unless guidance is extremely
  strict.
- The support posture would remain fragile because manual token setup errors
  are hard to diagnose safely.

Recommendation:

- Not recommended.

### Option B: Keep Manual Token Entry For Developer Verification Only And Keep Public Release On Hold

Pros:

- Matches the current MVP/developer verification reality.
- Avoids presenting manual token entry as public-release ready.
- Keeps the existing controlled verification path available.
- Allows OAuth, token lifecycle, storage, support, and uninstall decisions to
  be planned before implementation.
- Keeps release-readiness evidence honest by leaving WordPress.org release on
  hold.

Cons:

- Does not itself solve public release authentication.
- Delays WordPress.org release.
- Requires later design and implementation before public release can proceed.

Release readiness impact:

- WordPress.org release remains `Hold`.
- This is the safest current posture until OAuth/token lifecycle decisions are
  implemented or an alternate release strategy is accepted.

Privacy / support impact:

- Support can continue treating the manual token flow as developer-only.
- Public support wording can avoid normalizing credential sharing or manual
  token troubleshooting as a release feature.

Recommendation:

- Recommended as the current posture.

### Option C: Implement OAuth Authorization Flow And Token Lifecycle Before Public Release

Pros:

- Provides a normal Google connection experience for public users.
- Creates a path for authorization start, callback handling, scope clarity,
  expiry handling, reconnect, disconnect, revocation, and safer support
  evidence.
- Reduces confusion from manual token setup.
- Better aligns with public release expectations.

Cons:

- Larger design, implementation, and QA scope.
- Requires careful handling of callback state, capability checks, redirect
  behavior, token exchange, expiry, refresh or reconnect strategy, revocation,
  storage, and uninstall cleanup.
- May require Google app configuration, consent screen decisions, or
  site-owner configuration guidance.

Release readiness impact:

- Required before public release if Google integration remains in public scope.
- Should be handled in a dedicated design and implementation phase after the
  strategy is accepted.

Privacy / support impact:

- Improves the public support posture by replacing manual token handling with
  connection status, safe error categories, and controlled reconnect flows.
- Still requires strict redaction rules for credentials, option values, raw
  responses, request bodies, payloads, and generated report bodies.

Recommendation:

- Recommended before public release, but not implemented in Step 110.

### Option D: Remove Google Integration From Public Release Scope Or Delay Release

Pros:

- Avoids shipping an unresolved Google authentication model.
- Keeps credential lifecycle risk out of the public package until the team is
  ready to solve it.
- Allows the project to remain private/developer-only while the authentication
  strategy is designed.

Cons:

- Reduces or removes the core GA4-based product value for public users.
- May require product scope changes, admin copy changes, and readme/privacy
  updates before any public release.
- Still requires a future strategy if GA4 integration returns to public scope.

Release readiness impact:

- Public release can only proceed under this option if the public feature scope
  is intentionally changed and reviewed.
- Otherwise, release remains delayed.

Privacy / support impact:

- Reduces immediate public credential support risk.
- Requires clear communication that GA4 integration is not public-release
  ready or is outside the release scope.

Recommendation:

- Acceptable only if the project intentionally delays release or removes
  Google integration from public scope. Not the preferred path while GA4 report
  generation remains the core feature.

## Recommended Decision

Recommended:

```text
Option B as current posture, with Option C as required before public release.
```

Decision:

- Manual Google Access Token entry remains developer verification only.
- WordPress.org public release remains `Hold`.
- Before public release proceeds, the project must design and implement an
  OAuth authorization flow and token lifecycle strategy, unless a later
  decision removes Google integration from public release scope.
- Step 110 does not implement OAuth or token lifecycle behavior.

## Token Lifecycle Topics To Resolve Later

Later design and implementation planning should address at least the following
topics:

- Authorization start behavior.
- OAuth callback handling.
- Callback state protection.
- Administrator capability checks.
- Redirect and return URL behavior.
- Access token expiration handling.
- Refresh token handling, if applicable.
- Reconnect flow.
- Disconnect behavior.
- Provider-side revoke behavior.
- Difference between local disconnect and provider-side revocation.
- Admin-facing expired-token messaging.
- Admin-facing insufficient-permission messaging.
- Admin-facing property-access failure messaging.
- Required scope definition.
- Scope validation or safe scope failure handling.
- Storage location and storage posture.
- Redaction expectations for stored token categories.
- Multi-admin / site-owner expectations.
- Support/debug evidence boundaries.
- Relationship to credential-bearing settings.
- Relationship to uninstall cleanup.

## Why Direct Implementation Is Not Next

OAuth is closely tied to credential lifecycle, credential storage, uninstall
cleanup, support wording, and privacy posture.

Starting implementation before deciding the public release posture could cause
large rework. For example, an implementation that assumes stored refresh tokens
has different storage, revocation, support, and uninstall implications than an
implementation that uses an explicit reconnect-only model.

OpenAI API key storage posture and uninstall credential cleanup are adjacent
release blockers. Implementing Google OAuth alone would split related
credential decisions and may force later redesign after the OpenAI key and
cleanup policies are settled.

Therefore, Step 110 is intentionally a docs-only decision checkpoint. It keeps
the current developer verification behavior classified correctly and reserves
OAuth implementation for a later scoped design and implementation phase.

## Recommended Next Step

Recommended next step:

```text
Step 111: OpenAI API key storage posture decision checkpoint
```

Step 111 should also be docs-only and should not implement storage changes,
constant-based configuration, option migration, encryption, secret manager
integration, Settings save logic changes, or uninstall behavior changes.

## Explicit Non-goals

This step does not:

- change code,
- change `readme.txt`,
- change `.distignore`,
- change build scripts,
- rebuild a release package,
- rerun Plugin Check,
- run Plugin Check in `wp-dev`,
- touch `wp-dev-check`,
- call external APIs,
- run GA4 Fetch,
- run OpenAI Generate,
- implement OAuth,
- implement token exchange,
- implement token refresh,
- implement token revocation,
- implement reconnect UI,
- change credential storage,
- change uninstall behavior,
- record raw payloads,
- record raw request bodies,
- record raw response bodies,
- record generated report bodies,
- capture screenshots,
- inspect browser Network tab data,
- inspect or display credentials,
- inspect or display plugin settings option values,
- change admin UI behavior.

## Security / Evidence Notes

This document records only status-level strategy decisions.

It does not record real credentials, API keys, access tokens, Authorization
headers, plugin settings option values, GA4 Property IDs, hostname/domain
values, analytics values, page paths, traffic sources, city values, request
bodies, AI payload JSON, OpenAI request bodies, raw GA4/OpenAI response bodies,
generated report bodies, screenshots, browser Network tab data, cookies,
sessions, or nonces.

## Verification

Commands run for this docs-only step:

```text
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```

Expected result:

- whitespace check passes,
- no production code files change,
- no PHP, JavaScript, CSS, `readme.txt`, `.distignore`, or build script files
  change,
- only this Step 110 documentation file is added.
