# Step 254: OpenAI Settings Fallback Public-release Storage Disposition Decision Checkpoint

## Decision Purpose

Step 254 is a docs-only / decision-checkpoint-only review of the public-release
disposition for the current MVP OpenAI Settings fallback.

The purpose is to compare possible dispositions for Settings fallback storage
against the already selected public-release direction:

```text
constant-based OpenAI API key configuration preferred over Settings storage
```

This checkpoint recommends one disposition for later planning. It does not
start implementation, change UI wording, change storage behavior, run browser
smoke, run OpenAI Generate, perform Plugin Check, or verify external policy
compliance.

WordPress.org release readiness remains `Hold`.

## Fixed Inputs

The following inputs are unchanged:

```text
Current MVP source model: Constant first / Settings fallback / Missing
Public-release preferred direction: constant-based OpenAI API key configuration preferred over Settings storage
Settings fallback: current MVP fallback
WordPress.org release readiness: Hold
Step 252: Scope-bound Pass within limited human-visible UI scope
Actual credential validity / actual provider behavior / real OpenAI success: Not verified
```

Terminology boundaries:

- `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is the constant source name, not a source
  category.
- `constant_configured`, `settings_saved`, and `missing` are safe source
  categories.
- Source categories do not confirm actual credential validity, provider
  acceptance, quota, permission, endpoint availability, model availability, or
  OpenAI request success.
- Settings fallback remains a current MVP fallback; this checkpoint decides a
  recommended public-release disposition, not an already-completed release
  implementation.

## Referenced Steps

- `docs/maturation/step229-openai-api-key-storage-public-release-decision-checkpoint.md`
- `docs/maturation/step231-openai-api-key-constant-based-configuration-narrow-production-implementation-results.md`
- `docs/maturation/step234-openai-api-key-source-controlled-human-admin-smoke-results.md`
- `docs/maturation/step246-openai-error-wording-controlled-local-only-runtime-verification-maturation-checkpoint.md`
- `docs/maturation/step249-openai-settings-fallback-saved-state-and-clear-control-controlled-human-admin-smoke-results.md`
- `docs/maturation/step250-openai-credential-source-coverage-maturation-checkpoint.md`
- `docs/maturation/step252-openai-constant-configured-controlled-human-admin-smoke-results.md`
- `docs/maturation/step253-openai-constant-based-configuration-public-release-boundary-checkpoint.md`

## Decision Criteria

This checkpoint uses the following criteria:

- public-release security / storage posture,
- administrator usability and deployment clarity,
- source-aware UI consistency,
- value-hidden and clear-control boundaries,
- support/debug redaction safety,
- readme / privacy disclosure alignment,
- uninstall cleanup consistency,
- implementation and migration complexity,
- rollback / compatibility implications,
- effect on remaining release-readiness work.

## Option Comparison Matrix

| Option | Description | Alignment with preferred constant direction | Security / storage posture | UX / deployment implications | Documentation / support implications | Uninstall / migration implications | Release-readiness effect | Risks / unresolved issues |
|---|---|---|---|---|---|---|---|---|
| Option A | Public release removes Settings fallback and allows only constant configuration. | Strongest alignment; constant becomes the only route. | Strongest storage posture because the plugin no longer stores OpenAI key fallback values in Settings. | Higher deployment friction for administrators who cannot edit server configuration. | Simpler user-facing guidance, but requires clear deployment documentation. | Requires removal / migration plan for existing fallback values and clear uninstall implications. | Reduces storage ambiguity but increases implementation and migration scope. | Potential compatibility break from current MVP fallback; requires careful migration and admin messaging. |
| Option B | Public release keeps Settings fallback as a general user-facing route with full storage, support, privacy, and uninstall alignment. | Weakest alignment; constant remains preferred in wording but Settings remains broadly accepted. | Higher storage burden because Settings fallback remains a normal stored credential route. | Easiest for administrators who prefer UI-only setup. | Requires extensive disclosure, support/debug redaction, privacy wording, and storage-risk wording. | Requires strong cleanup and migration guarantees if storage remains accepted. | Could reduce UX friction but keeps a larger public-release storage review surface. | Risks weakening the constant-preferred direction and expanding credential-storage risk. |
| Option C | Settings fallback remains as developer-only / transitional route and is removed from primary user guidance. Exposure, routing, wording, support, and privacy boundaries are strictly limited. | Good alignment; constant remains the primary public-release direction while fallback remains controlled. | Better than broad Settings storage, but not as strict as removing fallback entirely. | Maintains a narrow fallback for development / transition without making it the general route. | Requires precise wording so fallback is not promoted as primary guidance. | Requires review of cleanup, existing fallback state, and any transition messaging. | Narrows the release blocker while preserving compatibility and a safer migration path. | Requires implementation and wording follow-through to avoid ambiguity. |
| Option D | Settings fallback remains current-MVP only and public-release disposition stays unresolved; release readiness stays on Hold. | Partially aligned because constant remains preferred, but the unresolved fallback remains open. | No improvement over current unresolved posture. | No immediate UX change. | Avoids near-term wording decisions but leaves release docs incomplete. | Defers cleanup / migration relationship. | Keeps release readiness blocked by the same decision. | Does not move the project toward release readiness. |

## Recommended Public-release Disposition

```text
Recommended disposition: Option C
```

Rationale:

- Option C preserves the established public-release direction that
  constant-based OpenAI API key configuration is preferred over Settings
  storage.
- Option C does not deny that Settings fallback exists in the current MVP.
- Option C avoids treating Settings fallback as a normal public-release route
  before its storage, disclosure, support, privacy, uninstall, and migration
  boundaries are fully aligned.
- Option C is less disruptive than Option A because it allows a narrow
  transitional / developer-only fallback path while the project matures.
- Option C reduces the unresolved surface compared with Option D, which would
  keep the same release-readiness blocker open.
- Step 249 confirmed value-hidden and clear-control UI behavior for the fallback
  at limited scope, and Step 252 confirmed constant-configured UI behavior at
  limited scope. Neither step verified actual credentials, provider behavior, or
  public-release compliance.

This recommendation does not:

- weaken the constant-based preferred direction,
- classify Settings fallback as an accepted general public-release route,
- convert Step 252's scope-bound UI pass into credential or provider success,
- change WordPress.org release readiness,
- state that public-release implementation is complete.

## Consequences If Option C Is Adopted

### Required Decision Follow-through

- Define exactly what "developer-only / transitional" means for Settings
  fallback.
- Decide whether the fallback remains visible by default, gated by explicit
  wording, or restricted through another narrow mechanism.
- Define whether existing fallback values are retained, cleared, migrated, or
  warned about.

### Potential Narrow Production Implementation

- Adjust Settings UI wording so constant configuration is primary.
- Avoid presenting Settings fallback as a general user setup path.
- Preserve value-hidden posture and fallback-only clear-control behavior.
- Ensure Report Builder readiness wording does not promote Settings as the sole
  route.

### Documentation / Readme / Privacy Alignment

- Update readme/privacy wording after the exact fallback disposition is fixed.
- Explain that constant-based configuration is preferred.
- Explain any remaining Settings fallback as transitional / developer-only if
  retained.
- Avoid requesting credential values as support evidence.

### Support / Debug Wording Alignment

- Keep support/debug evidence status/category-level only.
- Do not ask users to share API keys, constant values, option values, request
  bodies, raw responses, payloads, or generated report bodies.
- Ensure fallback-related support instructions match the selected disposition.

### Uninstall / Migration Review

- Re-check uninstall cleanup expectations if fallback values remain possible.
- Determine whether existing fallback values require migration or admin notice.
- Keep cleanup discussion separate from provider-side behavior.

### Controlled Human Admin Smoke

- Plan and run only after implementation / wording changes are defined.
- Verify UI category, value-hidden, fallback exposure, clear-control scope, and
  support wording.
- Do not verify real credentials or provider success in the same track.

### Plugin Check / Release-readiness Verification

- Defer Plugin Check / release readiness until the disposition follow-through is
  implemented or explicitly deferred with documented rationale.
- Keep WordPress.org release readiness `Hold` until later release checks.

### Provider / Runtime Verification Track

- Keep provider authentication, quota, permission, endpoint, model availability,
  request construction, and request success as separate verification tracks.
- Do not use Settings fallback disposition as evidence of provider behavior.

## Explicit Non-conclusions

This checkpoint does not confirm:

- actual API key validity,
- actual constant value,
- actual Settings fallback value,
- actual Settings fallback storage contents,
- actual constant preservation,
- real OpenAI request success,
- provider authentication,
- quota,
- permission,
- endpoint availability,
- model availability,
- WordPress.org review outcome,
- public-release approval.

## Recommended Step 255

Because the recommended disposition is Option C, the recommended next step is:

```text
Step 255 candidate — OpenAI Settings fallback public-release disposition narrow implementation plan
```

Step 255 should remain a planning step until implementation is explicitly
requested. It should translate Option C into a narrow implementation plan,
including UI wording scope, documentation scope, support/debug wording, cleanup
considerations, and verification steps.

## Maintained Conclusions

The following conclusions are preserved:

```text
constant-based OpenAI API key configuration is the preferred public-release direction
Settings fallback remains a current MVP fallback
WordPress.org release readiness remains Hold
Step 252 remains Scope-bound Pass
Step 252 did not validate actual credential behavior or provider communication
```

## Result Classification

```text
Step 254 result: Decision checkpoint completed
Compared options: A / B / C / D
Recommended disposition: Option C
Settings fallback posture: developer-only / transitional route recommended for public-release follow-through
Preferred public-release direction: constant-based OpenAI API key configuration
Implementation started: No
Browser admin smoke: Not performed
External provider communication: Not performed
WordPress.org release readiness: Hold
Recommended next step: Step 255 candidate — OpenAI Settings fallback public-release disposition narrow implementation plan
```
