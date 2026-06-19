# Step 228: OpenAI API Key Storage Posture Checkpoint

## Step Purpose

Step 228 is a docs-only and planning-only checkpoint for the OpenAI API key
storage posture before public-release readiness.

The purpose is to inventory the current OpenAI API key Settings storage,
non-redisplay posture, OpenAI request boundary, readme/support boundaries, and
uninstall relationship, then compare public-release posture options without
changing production code.

No production code, `readme.txt`, Settings UI, credential resolver, OpenAI
client, GA4 client, `uninstall.php`, tools, JavaScript, or CSS are changed in
this step.

WordPress.org release readiness remains `Hold`.

## Scope

In scope:

- current OpenAI API key storage inventory,
- Settings UI field / save handling / non-redisplay posture,
- OpenAI request usage boundary,
- Payload Preview / Generate AI Report flow relationship,
- support/debug evidence boundary relationship,
- readme/privacy wording relationship,
- uninstall cleanup relationship,
- matured MVP boundary classification,
- Hold / Separate track classification,
- public-release posture option comparison,
- recommended next step.

Out of scope:

- implementation changes,
- Settings UI changes,
- OpenAI client changes,
- readme changes,
- option value inspection,
- external API calls,
- release-readiness approval.

## Explicit Non-goals

Step 228 does not:

- change production code,
- change `readme.txt`,
- change Settings UI,
- change the credential resolver,
- change OpenAI client behavior,
- change GA4 client behavior,
- change `uninstall.php`,
- change tools or build scripts,
- change JavaScript or CSS,
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

- `docs/maturation/step227-readme-privacy-wording-alignment-after-manual-token-retirement-maturation-checkpoint.md`
- `docs/maturation/step226-readme-privacy-wording-alignment-after-manual-token-retirement-source-level-verification-results.md`
- `docs/maturation/step225-readme-privacy-wording-alignment-after-manual-token-retirement-implementation-results.md`
- `docs/maturation/step223-manual-google-access-token-fallback-retirement-maturation-checkpoint.md`
- `docs/maturation/step215-uninstall-cleanup-maturation-checkpoint.md`
- `docs/maturation/step209-credential-storage-public-release-posture-checkpoint.md`
- `docs/maturation/step208-oauth-token-lifecycle-maturation-checkpoint.md`
- `docs/maturation/step96-generated-report-handling-policy-finalization.md`

## Current OpenAI API Key Storage Inventory

Source-level / category-level inventory:

| Area | Source-level reference | Current category |
|---|---|---|
| Default settings category | `includes/functions-utils.php`, `analytics_report_ai_get_default_settings()` | `openai_api_key` exists in the main plugin settings option category. |
| Settings normalization | `includes/functions-utils.php`, `analytics_report_ai_get_settings()` | `openai_api_key` is normalized from plugin settings as a scalar string category. |
| Settings save handling | `includes/class-settings.php`, `sanitize_settings()` | `openai_api_key` input is accepted, sanitized as an opaque credential, preserved when empty, and cleared when `clear_openai_api_key` is set. |
| Settings UI field | `includes/class-settings.php`, Settings page | Password field with empty `value`; saved state is indicated by placeholder/description only. |
| Settings UI delete control | `includes/class-settings.php`, Settings page | `clear_openai_api_key` checkbox appears when applicable. |
| OpenAI request usage | `includes/class-openai-client.php`, `generate_report()` | API key is read from normalized settings and used for OpenAI Authorization header during report generation. |
| Report Builder flow | `includes/class-report-builder.php`, `handle_generate_ai_report()` | OpenAI generation occurs only through the Generate AI Report action after payload validation. |
| Payload Preview relationship | `includes/class-report-builder.php`, Payload Preview UI | Credential values are described as excluded from AI payload, and support evidence is status/category-level only. |
| Generated report handling | `includes/class-report-builder.php`, generated report UI | Generated report text is displayed for user review/edit/copy and is not saved by the plugin. |
| Readme/privacy wording | `readme.txt` | OpenAI requests are action-triggered; OpenAI API Key is listed as Authorization header data category; storage posture remains a separate public-release decision. |
| Uninstall cleanup | `uninstall.php` | Main plugin settings option is deleted on uninstall, which covers the OpenAI API key category as part of deterministic plugin-owned option cleanup. |
| Local-only OAuth disconnect | `includes/functions-utils.php`, Settings wording | Local-only Google OAuth disconnect does not delete the OpenAI API key. |

Storage category:

```text
OpenAI API key storage category: main plugin settings option / wp_options
```

This inventory records only source file names, function/method names, option key
names, UI field names, storage categories, and wording categories. It does not
inspect or record option values or credential values.

## Matured Within Current MVP Boundary

The following can be treated as matured within the current MVP boundary:

| Area | Status | Reason |
|---|---|---|
| Saved credential non-redisplay posture | Matured within current MVP boundary | Settings UI uses an empty password field value and saved/not-saved state text rather than redisplaying the key. |
| OpenAI API key support evidence boundary | Status/category-level only / Preserved | Admin UI and readme/support wording instruct users not to share credentials, API keys, Authorization headers, option values, request/response bodies, payload JSON, or generated report text. |
| OpenAI request action boundary | Administrator-triggered Generate AI Report only / Preserved | Readme and Report Builder wording describe OpenAI communication as tied to Generate AI Report. |
| Payload Preview boundary | Preserved | Structured pre-send review remains before OpenAI generation; normal admin UI does not expose full raw AI payload JSON. |
| Generated report body storage posture | Non-storage / Preserved | Generated report text is displayed for review/edit/copy and is not saved by the plugin. |
| Generated report support evidence boundary | Preserved | Generated report text is not requested as support evidence. |
| Forbidden evidence boundary | Matured within current MVP boundary | This track does not expose or record credential values, option values, request bodies, raw responses, AI payload JSON, generated report bodies, screenshots, or Network evidence. |

This maturity is limited to UI/support/readme boundaries and current MVP
handling. It does not mean OpenAI API key storage is public-release ready.

## Hold / Separate Track Items

The following are not matured in this checkpoint:

| Area | Status | Reason |
|---|---|---|
| OpenAI API key in plugin settings / `wp_options` public-release posture | Hold / Needs decision | Current storage is simple and compatible with MVP, but stored credential exposure risks remain. |
| Encryption / obfuscation posture | Hold / Separate track | No encryption or obfuscation design is selected in this step. |
| Constant-based configuration | Hold / Decision candidate | A constant-based key source could reduce database exposure but needs source-level design and admin wording. |
| External proxy / service-side key management | Deferred | Stronger key isolation but high implementation, operational, and review complexity. |
| Per-run entry or external configuration only | Deferred | Avoids stored key but may hurt usability and workflow continuity. |
| Key rotation guidance | Hold | Current UI supports replacement/clear behavior but no full rotation policy is matured. |
| Key deletion versus uninstall cleanup | Hold / Separate track | Settings delete control and uninstall cleanup exist as separate boundaries; neither is a full storage posture decision. |
| Release-ready claim | Hold | Readme says OpenAI key storage remains a separate public-release decision. |
| WordPress.org release readiness | Hold | Credential storage posture, Plugin Check, release package review, and final release checks remain open. |

## Public-release Posture Options

### Option A: Continue storing OpenAI API Key in plugin settings with strict non-redisplay and explicit disclosure

- User experience impact: Low friction; matches the current MVP workflow.
- Implementation complexity: Low; mostly wording/source-level verification if
  current behavior is accepted.
- Privacy/security posture: Moderate; database administrators, backups, server
  administrators, and code that can read WordPress options may access stored
  credential categories.
- WordPress.org release-review risk: Moderate; depends on clarity of disclosure,
  sanitization, capability checks, non-redisplay, and support evidence posture.
- Support/debug evidence impact: Compatible with current status/category-only
  evidence boundary.
- Compatibility with current MVP: High.
- Classification: Recommended only as current-MVP-compatible candidate; needs
  explicit public-release decision before being treated as accepted.

### Option B: Support constant-based OpenAI API Key configuration and prefer constants over settings

- User experience impact: More technical for site owners; easier for managed
  deployments and developers.
- Implementation complexity: Moderate; needs source-level resolver design,
  admin status wording, non-redisplay behavior, and readme/help updates.
- Privacy/security posture: Improved for database/backups exposure because the
  key can avoid plugin settings storage, though server administrators and code
  with file/config access may still access it.
- WordPress.org release-review risk: Moderate to low if documented clearly and
  Settings fallback behavior remains transparent.
- Support/debug evidence impact: Compatible with status/category labels such as
  `constant_configured`, `settings_saved`, `missing`, or similar categories.
- Compatibility with current MVP: Good, but requires implementation.
- Classification: Preferred decision candidate for public-release posture.

### Option C: Remove stored OpenAI API Key from public-release build and require per-run entry or external configuration

- User experience impact: High friction; every Generate AI Report flow may
  require repeated credential handling unless an external config path exists.
- Implementation complexity: Moderate; requires UI/flow redesign and stronger
  validation.
- Privacy/security posture: Stronger for database storage if no key is stored,
  but repeated UI entry may increase copy/paste and support risk.
- WordPress.org release-review risk: Mixed; avoids stored key concerns but may
  introduce awkward UX and new sensitive entry points.
- Support/debug evidence impact: Requires strict wording to avoid key sharing.
- Compatibility with current MVP: Low to moderate.
- Classification: Deferred / Not recommended as immediate path.

### Option D: Use external proxy / service-side key management

- User experience impact: Potentially simple for end users if managed well.
- Implementation complexity: High; introduces a separate service, operational
  trust model, billing, availability, privacy policy, and review burden.
- Privacy/security posture: Can isolate keys from WordPress database, but moves
  trust to an external service.
- WordPress.org release-review risk: High; requires additional external service
  disclosure and likely broader privacy/security review.
- Support/debug evidence impact: Requires new support boundaries and service
  diagnostics.
- Compatibility with current MVP: Low.
- Classification: Deferred / Not recommended for near-term MVP maturation.

### Option E: Defer final decision and keep WordPress.org release readiness on Hold

- User experience impact: No immediate UX change.
- Implementation complexity: Low now; delays implementation.
- Privacy/security posture: Current MVP risk remains unresolved for public
  release.
- WordPress.org release-review risk: Keeps release readiness on hold rather than
  overclaiming.
- Support/debug evidence impact: Current strict evidence boundary remains.
- Compatibility with current MVP: High.
- Classification: Recommended as Step 228 checkpoint outcome until a dedicated
  Step 229 decision is made.

## Option Comparison Table

| Option | UX impact | Implementation complexity | Privacy/security posture | WordPress.org risk | Current MVP compatibility | Classification |
|---|---|---|---|---|---|---|
| Option A: Settings storage with disclosure | Low friction | Low | Moderate; database/options exposure remains | Moderate | High | Decision candidate, not final in Step 228 |
| Option B: Constants preferred over settings | More technical | Moderate | Improved for DB/backups exposure | Moderate to low if clear | Good with implementation | Preferred decision candidate |
| Option C: No stored key / per-run or external config | High friction | Moderate | Avoids stored settings key, but repeated entry risk | Mixed | Low to moderate | Deferred / Not immediate |
| Option D: External proxy / service-side key management | Potentially simple | High | Moves trust to external service | High | Low | Deferred / Not near-term |
| Option E: Defer final decision | No immediate change | Low now | Current risk unresolved | Keeps release on Hold | High | Recommended checkpoint outcome |

## Recommended Posture

Recommended Step 228 posture:

```text
OpenAI API key public-release posture: Hold / Needs decision
Recommended posture: Option B as preferred public-release decision candidate, with Option A as current MVP-compatible baseline only
```

Rationale:

- The current MVP Settings storage is coherent and has non-redisplay,
  action-triggered OpenAI request, support evidence, and readme disclosure
  boundaries.
- However, storing the OpenAI API key in plugin settings / `wp_options` remains
  a public-release posture decision because database administrators, backups,
  server administrators, and code that can read WordPress options may access
  stored credential categories.
- Option B could reduce database/backups exposure for deployments that can use
  constants, while preserving a status/category-level UI posture.
- Option A may remain viable if explicitly accepted with clear disclosure, but
  Step 228 should not silently convert the current MVP behavior into a
  release-ready decision.

Step 228 therefore recommends a dedicated decision checkpoint before any
implementation.

## Public Release Boundary

This checkpoint does not connect OpenAI API key storage to WordPress.org
release readiness.

Reasons:

- the public-release storage posture is not decided,
- constant-based configuration is not implemented,
- encryption / obfuscation / external secret storage is not selected,
- OpenAI API key rotation policy is not matured,
- OpenAI API key deletion and uninstall cleanup boundaries remain separate,
- OAuth client Settings fallback posture remains on hold,
- final credential storage consolidation remains open,
- Plugin Check and release package checks are outside this step.

WordPress.org release readiness remains `Hold`.

## Remaining Risks

| Risk | Status | Notes |
|---|---|---|
| Stored OpenAI API key exposure through database/options access | Hold | Current MVP disclosure warns about stored credential categories, but public-release acceptance is undecided. |
| Backup/server/code access to stored credential category | Hold | Remains a core risk for settings-based storage. |
| OpenAI key rotation policy | Hold | Current replacement/delete controls exist, but a public-release rotation posture is not matured. |
| Support/debug leakage | Matured boundary, ongoing risk | Current wording says not to share API keys or Authorization headers; future support docs must preserve this. |
| Overclaiming release readiness | Hold | This checkpoint intentionally does not approve release readiness. |
| Constant-based configuration design drift | Needs decision | Option B needs a focused implementation plan if selected. |

## Recommended Next Step

Recommended next step:

```text
Step 229: OpenAI API key storage public-release decision checkpoint
```

Step 229 should decide whether to accept Option A with explicit disclosure,
select Option B as the implementation target, or keep the OpenAI API key
storage posture on hold. It should remain docs-only / planning-only unless the
user explicitly requests implementation.

## Result Classification

```text
OpenAI API key storage posture checkpoint: Completed
Current OpenAI API key handling: MVP settings storage with non-redisplay / Inventory completed
OpenAI API key public-release posture: Hold / Needs decision
OpenAI API key support evidence boundary: Status/category-level only / Preserved
OpenAI API request boundary: Administrator-triggered Generate AI Report only / Preserved
Generated report body storage posture: Non-storage / Preserved
Recommended posture: Option B as preferred public-release decision candidate; Option A as current MVP-compatible baseline only
WordPress.org release readiness: Hold
```
