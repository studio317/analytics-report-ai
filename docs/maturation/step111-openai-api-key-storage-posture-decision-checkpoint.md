# Step 111: OpenAI API Key Storage Posture Decision Checkpoint

## Step Summary

This step records a decision checkpoint for OpenAI API key storage posture
before public release.

Step 109 listed OpenAI API key storage posture as a remaining
release-readiness blocker, and Step 110 recommended this checkpoint as the next
step after the Google OAuth / token lifecycle decision.

This step does not implement OpenAI API key storage changes, option migration,
encryption, constant-based configuration, secret manager integration, Settings
save logic changes, credential storage changes, or uninstall behavior changes.

The purpose is to separate posture decisions from implementation. This document
clarifies the current MVP/developer verification posture, the public release
posture, and the later storage topics that must be designed before
WordPress.org release can proceed.

Production code was not changed. PHP, JavaScript, CSS, `readme.txt`,
`.distignore`, build scripts, release packages, Plugin Check, admin UI
behavior, Settings save logic, GA4 client behavior, OpenAI client behavior,
credential storage, and uninstall behavior were not changed.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step110-google-oauth-token-lifecycle-strategy-decision-checkpoint.md`
- `docs/maturation/step109-release-readiness-blocker-reprioritization-after-plugin-check-pass.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step96-generated-report-handling-policy-finalization.md`
- `docs/maturation/step95-payload-preview-json-visibility-final-decision.md`
- `docs/maturation/step104-readme-privacy-wording-alignment-implementation-results.md`

## Current State

The current MVP has used an OpenAI API key saved from Settings for controlled
OpenAI Generate verification.

The saved key value is handled under the non-redisplay posture: the admin UI
shows only saved/not-saved state and replacement/delete controls, not the
stored key value.

This model is useful for controlled developer verification because it keeps the
MVP setup small and allows the GA4 Fetch, structured Payload Preview, and
OpenAI Generate flow to be tested end to end.

However, current settings-based OpenAI API key storage still needs an explicit
public / multi-user WordPress.org release posture. The key is a credential
category, and persistent settings storage has database, backup, server-admin,
and option-reader exposure.

Unresolved areas include:

- API key storage posture.
- Redaction and non-redisplay guarantees.
- Rotation behavior.
- Delete / disconnect behavior.
- Multi-admin visibility and capability expectations.
- Site-owner versus administrator responsibility.
- Support/debug evidence boundaries.
- Relationship to generated report non-persistence.
- Relationship to AI payload visibility boundaries.
- Relationship to uninstall cleanup.

Current status:

- Current settings-based OpenAI API key storage is acceptable only for
  controlled developer verification.
- Current settings-based storage should not be treated as public-release ready
  without an explicit acceptance or redesign decision.
- WordPress.org release remains `Hold`.

## Release Posture Options

### Option A: Keep Current Settings-based OpenAI API Key Storage In Public Release

Pros:

- Lowest implementation effort.
- Preserves the current MVP Settings and OpenAI Generate flow.
- Easy for administrators to understand and configure.
- Keeps existing non-redisplay and delete controls available.

Cons:

- Stores the credential category in WordPress settings.
- Database administrators, server administrators, backups, and option-reading
  code may access the stored key category.
- Does not provide a stronger deployment-managed secret path.
- Requires very clear support guidance to avoid option dumps, screenshots, or
  copied secrets.
- Rotation and ownership expectations remain under-specified.

Release readiness impact:

- Not recommended as the default public-release posture without explicit human
  acceptance and additional disclosure.
- Would leave storage posture risk accepted rather than redesigned.

Privacy / support impact:

- Support must strictly prohibit sharing API keys, credential fragments,
  Authorization headers, option values, request bodies, raw responses, payloads,
  and generated report bodies.
- Public support wording would need to explain restricted keys, rotation,
  deletion, and safe evidence without asking for secrets.

Recommendation:

- Not recommended as the current public-release posture without further
  acceptance.

### Option B: Keep Current Settings-based Storage For Developer Verification Only And Keep Public Release On Hold

Pros:

- Matches the current MVP/developer verification reality.
- Avoids presenting settings-based storage as public-release ready.
- Keeps the existing controlled verification path available.
- Allows storage, redaction, rotation, support, and uninstall decisions to be
  planned before implementation.
- Keeps release-readiness evidence honest by leaving WordPress.org release on
  hold.

Cons:

- Does not itself solve public release storage posture.
- Delays WordPress.org release.
- Requires later design and implementation before public release can proceed.

Release readiness impact:

- WordPress.org release remains `Hold`.
- This is the safest current posture until OpenAI API key storage is either
  redesigned or explicitly accepted for public release.

Privacy / support impact:

- Support can continue treating settings-based storage as developer-only for
  the release-readiness track.
- Public support wording can avoid normalizing option-based secret sharing or
  option dumps as acceptable evidence.

Recommendation:

- Recommended as the current posture.

### Option C: Redesign Storage Posture Before Public Release

Examples:

- Constant-based configuration.
- Reduced UI persistence.
- Explicit delete/disconnect semantics.
- Stronger redaction and status-only UI.
- Site-owner controlled configuration.
- A documented precedence model if multiple configuration paths exist.

Pros:

- Improves public release posture before broad distribution.
- Can reduce option-table exposure if constant-based or site-owner managed
  configuration is supported.
- Can clarify whether the key is managed by UI settings, site configuration, or
  another approved path.
- Creates room for clearer rotation, deletion, and support guidance.

Cons:

- Requires additional design, implementation, documentation, and QA.
- Constant-based configuration is less friendly for non-technical
  administrators.
- Supporting multiple paths can complicate Settings UI, precedence rules, and
  support wording.
- Does not eliminate all server-level exposure.

Release readiness impact:

- Required before public release unless a later decision explicitly accepts
  settings-based storage or removes OpenAI integration from release scope.
- Should be handled in a dedicated design and implementation phase after the
  strategy is accepted.

Privacy / support impact:

- Can improve support safety by steering users toward status-level evidence
  and away from sharing keys or option values.
- Still requires strict redaction rules for credentials, option values, raw
  responses, request bodies, payloads, and generated report bodies.

Recommendation:

- Recommended before public release, but not implemented in Step 111.

### Option D: Remove OpenAI Integration From Public Release Scope Or Delay Release

Pros:

- Avoids shipping an unresolved OpenAI credential storage model.
- Keeps AI key storage risk out of the public package until the team is ready
  to solve it.
- Allows the project to remain private/developer-only while the storage
  strategy is designed.

Cons:

- Removes or delays the AI report generation value for public users.
- May require product scope changes, admin copy changes, and readme/privacy
  updates before any public release.
- Still requires a future strategy if OpenAI integration returns to public
  scope.

Release readiness impact:

- Public release can only proceed under this option if the public feature scope
  is intentionally changed and reviewed.
- Otherwise, release remains delayed.

Privacy / support impact:

- Reduces immediate public OpenAI credential support risk.
- Requires clear communication that OpenAI integration is not public-release
  ready or is outside the release scope.

Recommendation:

- Acceptable only if the project intentionally delays release or removes
  OpenAI integration from public scope. Not the preferred path while AI report
  generation remains a core feature.

## Recommended Decision

Recommended:

```text
Option B as current posture, with Option C required before public release unless a later release-scope decision removes OpenAI integration.
```

Decision:

- Current settings-based OpenAI API key storage remains developer verification
  only.
- WordPress.org public release remains `Hold`.
- Before public release proceeds, the project must design and implement or
  explicitly accept an OpenAI API key storage posture, unless a later decision
  removes OpenAI integration from public release scope.
- Step 111 does not implement storage changes.

## Storage Posture Topics To Resolve Later

Later design and implementation planning should address at least the following
topics:

- Storage location.
- Whether UI-based persistent API key storage remains acceptable.
- Constant-based configuration option.
- Configuration precedence if more than one storage/configuration path exists.
- Key rotation behavior.
- Delete / disconnect behavior.
- Difference between removing a saved settings key and changing site
  configuration.
- Redaction and non-redisplay behavior.
- Multi-admin visibility and capability expectations.
- Site-owner versus administrator responsibility.
- Error messaging for missing API key.
- Error messaging for invalid API key.
- Error messaging for permission-limited or restricted API key failures.
- Support/debug evidence boundaries.
- Relationship to generated report non-persistence.
- Relationship to AI payload visibility boundary.
- Relationship to uninstall cleanup.

## Why Direct Implementation Is Not Next

OpenAI API key storage is closely tied to credential lifecycle, support
wording, privacy posture, and uninstall cleanup.

Starting implementation before deciding the public release posture could cause
large rework. For example, settings-only storage, constant-based configuration,
mixed configuration, or site-owner managed configuration each require different
Settings UI wording, precedence rules, support evidence rules, and cleanup
expectations.

As with Step 110 for Google OAuth and token lifecycle, developer verification
posture and public release posture need to be handled separately. The current
MVP can remain useful for controlled verification without presenting its
settings-based credential storage as public-release ready.

Uninstall credential cleanup is the next adjacent blocker. It should be decided
after the Google and OpenAI credential posture decisions so cleanup behavior can
match the chosen model for stored credential-bearing settings.

Therefore, Step 111 is intentionally a docs-only decision checkpoint. It keeps
the current developer verification behavior classified correctly and reserves
OpenAI key storage changes for a later scoped design and implementation phase.

## Recommended Next Step

Recommended next step:

```text
Step 112: Uninstall credential cleanup policy decision checkpoint
```

Step 112 should also be docs-only and should not implement uninstall cleanup,
option deletion, migration, retention prompts, storage changes, or Settings
save logic changes.

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
- change OpenAI API key storage,
- migrate options,
- implement encryption,
- implement constant-based configuration,
- integrate a secret manager,
- change Settings save logic,
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

This document records only status-level storage posture decisions.

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
- only this Step 111 documentation file is added.
