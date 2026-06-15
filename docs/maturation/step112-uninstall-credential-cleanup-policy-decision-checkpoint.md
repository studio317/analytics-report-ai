# Step 112: Uninstall Credential Cleanup Policy Decision Checkpoint

## Step Summary

This step records a decision checkpoint for uninstall credential cleanup policy
before public release.

Step 109 listed uninstall credential cleanup as a remaining release-readiness
blocker, and Step 111 recommended this checkpoint as the next step after the
OpenAI API key storage posture decision.

This step does not create `uninstall.php`, delete options, migrate options,
add retention prompts, change Settings save logic, change credential storage,
or change uninstall behavior.

The purpose is to separate cleanup policy decisions from implementation. This
document clarifies the current MVP/developer verification posture, the public
release posture, and the later cleanup topics that must be designed before
WordPress.org release can proceed.

Production code was not changed. PHP, JavaScript, CSS, `readme.txt`,
`.distignore`, build scripts, release packages, Plugin Check, admin UI
behavior, Settings save logic, GA4 client behavior, OpenAI client behavior,
credential storage, and uninstall behavior were not changed.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step111-openai-api-key-storage-posture-decision-checkpoint.md`
- `docs/maturation/step110-google-oauth-token-lifecycle-strategy-decision-checkpoint.md`
- `docs/maturation/step109-release-readiness-blocker-reprioritization-after-plugin-check-pass.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step96-generated-report-handling-policy-finalization.md`
- `docs/maturation/step95-payload-preview-json-visibility-final-decision.md`
- `docs/maturation/step104-readme-privacy-wording-alignment-implementation-results.md`

## Current State

The MVP/developer verification flow has used credential-bearing settings for
Google Access Token and OpenAI API key categories.

Step 110 classified manual Google Access Token entry as developer verification
only. Step 111 classified current settings-based OpenAI API key storage as
developer verification only.

Those decisions mean the credential storage posture still needs redesign or an
explicit acceptance decision before public release. Uninstall cleanup is
closely related to that storage posture because cleanup rules need to decide
what happens to credential-bearing settings when the plugin is removed.

Status-level source review found no `uninstall.php` file in the current source
repository. This step records that absence only; it does not create an
uninstall file or implement cleanup behavior.

Current status:

- Credential-bearing settings exist in the MVP/developer verification posture.
- Google credential posture remains developer verification only.
- OpenAI API key storage posture remains developer verification only.
- Public release credential storage posture is not finalized.
- Uninstall cleanup policy is not finalized.
- WordPress.org release remains `Hold`.

## Cleanup Posture Options

### Option A: Do Not Delete Plugin Settings Or Credential-bearing Options On Uninstall

Pros:

- Lowest implementation effort.
- Avoids accidental deletion of administrator configuration.
- Allows a site to reinstall the plugin and recover previous settings.

Cons:

- Credential-bearing data may remain after uninstall.
- Users may reasonably expect uninstall to remove plugin-owned credential
  categories.
- Leaves database/backups/server-admin/option-reader exposure in place after
  plugin removal.
- Does not resolve the public release cleanup blocker.

Release readiness impact:

- Not recommended as the public-release default without explicit retention
  disclosure and human acceptance.
- Would keep a privacy and credential cleanup blocker open.

Privacy / support impact:

- Support would need to explain that uninstall does not remove credential-
  bearing settings.
- Users may share database or option evidence while trying to verify cleanup,
  which conflicts with support/debug redaction policy.

Recommendation:

- Not recommended as the current public-release posture.

### Option B: Delete All Plugin Settings And Credential-bearing Options On Uninstall

Pros:

- Simple mental model: uninstall removes plugin-owned settings.
- Reduces retained credential-bearing data after plugin removal.
- Easier to explain from a privacy standpoint than silent retention.

Cons:

- May delete non-sensitive preferences that users expected to keep for
  reinstall.
- Needs careful implementation to delete only plugin-owned options and
  transients.
- Does not distinguish credential-bearing data from harmless preferences.
- Could conflict with a later storage posture that uses constants or external
  configuration, where plugin uninstall cannot and should not remove the
  external secret source.

Release readiness impact:

- A strong candidate if the chosen public release posture is to remove all
  plugin-owned persistent settings on uninstall.
- Still needs implementation and QA before public release.

Privacy / support impact:

- Improves local cleanup expectations for plugin-owned settings.
- Support still must distinguish local deletion from provider-side revocation
  or external secret removal.

Recommendation:

- Potential candidate, but not recommended to implement before credential
  storage posture is finalized.

### Option C: Decide Cleanup After Credential Storage Posture Is Finalized

Policy direction:

- Credential-bearing data should be explicitly included in the cleanup policy.
- Non-sensitive preferences can be retained or deleted depending on the final
  public release posture.
- Cleanup behavior should distinguish local plugin-owned data from provider-
  side revocation and external configuration.

Pros:

- Aligns cleanup behavior with the eventual Google and OpenAI credential
  storage model.
- Allows credential-bearing settings to receive stricter cleanup treatment than
  non-sensitive preferences.
- Supports future models such as OAuth tokens, constant-based configuration, or
  reduced UI persistence without premature cleanup assumptions.
- Creates clearer readme/admin/support wording.

Cons:

- Requires another design and implementation step.
- Keeps release on hold until policy and implementation are completed or
  explicitly deferred.
- Requires careful QA around uninstall and reinstall behavior.

Release readiness impact:

- Required before public release after credential storage posture is finalized.
- Best fit for a clean public release path.

Privacy / support impact:

- Can give users clearer expectations about what uninstall removes locally.
- Can preserve support boundaries by avoiding option dumps and distinguishing
  local cleanup from revoke/disconnect behavior.

Recommendation:

- Recommended before public release, after Google/OpenAI credential posture is
  finalized.

### Option D: Keep Public Release On Hold And Defer Uninstall Cleanup Implementation During Developer Verification

Pros:

- Matches the current developer verification posture.
- Avoids implementing cleanup behavior before Google and OpenAI credential
  storage decisions are settled.
- Reduces risk of rework if credential storage is redesigned.
- Keeps release-readiness evidence honest by leaving WordPress.org release on
  hold.

Cons:

- Does not itself solve public release cleanup.
- Delays WordPress.org release.
- Requires later design and implementation before public release can proceed.

Release readiness impact:

- WordPress.org release remains `Hold`.
- Cleanup remains a required blocker before public release.

Privacy / support impact:

- Support and internal docs must continue treating current credential-bearing
  settings as developer verification only.
- Public release support wording should not imply uninstall cleanup is
  finalized until implementation and QA are complete.

Recommendation:

- Recommended as the current posture.

## Recommended Decision

Recommended:

```text
Option D as current posture, with Option C required before public release after Google/OpenAI credential storage posture is finalized.
```

Decision:

- WordPress.org public release remains `Hold`.
- Step 110 and Step 111 keep Google and OpenAI credentials in a developer
  verification posture.
- Uninstall cleanup implementation should not start yet.
- Before public release proceeds, the project must decide a cleanup policy that
  matches the final credential storage posture.
- Credential-bearing data must be explicitly covered by the public release
  cleanup policy.
- Step 112 does not implement uninstall cleanup behavior.

## Cleanup Topics To Resolve Later

Later design and implementation planning should address at least the following
topics:

- Whether to introduce `uninstall.php`.
- Exact `delete_option()` targets.
- Exact transient cleanup targets, if any.
- Cleanup scope for credential-bearing settings.
- Retention or deletion policy for non-sensitive settings.
- Relationship to Google credential / token lifecycle policy.
- Relationship to OpenAI API key storage posture.
- Cleanup behavior if constant-based configuration is adopted.
- Difference between plugin-local deletion and provider-side revocation.
- Difference between OAuth disconnect and uninstall cleanup.
- Whether provider-side revoke behavior belongs in uninstall, admin UI, or a
  separate disconnect flow.
- Multisite support requirements.
- Activation, deactivation, and uninstall responsibility boundaries.
- Support/debug evidence boundaries.
- `readme.txt` or uninstall wording requirements.

## Why Direct Implementation Is Not Next

Uninstall cleanup is tightly coupled to credential storage posture.

Google OAuth/token lifecycle and OpenAI API key storage are not yet finalized as
public release implementation decisions. If `uninstall.php`, option deletion,
or migration behavior is implemented now, the deletion targets and semantics
may change after credential storage is redesigned.

For example, a settings-only model, OAuth token model, constant-based OpenAI key
model, or mixed model would each require different cleanup behavior and
different user-facing wording.

Therefore, Step 112 is intentionally a docs-only decision checkpoint. It keeps
uninstall cleanup classified as a release blocker without prematurely choosing
implementation details.

## Recommended Next Step

Recommended next step:

```text
Step 113: Credential lifecycle decision summary and next implementation boundary
```

Step 113 should also be docs-only. It should consolidate the Step 110, Step
111, and Step 112 decisions and decide whether the project should move into
implementation planning or insert another policy review before implementation.

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
- create `uninstall.php`,
- implement option deletion,
- implement migration,
- implement retention prompts,
- change Settings save logic,
- change credential storage,
- implement OAuth disconnect,
- implement token revocation,
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

This document records only status-level cleanup policy decisions.

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
- no PHP, JavaScript, CSS, `readme.txt`, `.distignore`, build script, or
  uninstall behavior files change,
- only this Step 112 documentation file is added.
