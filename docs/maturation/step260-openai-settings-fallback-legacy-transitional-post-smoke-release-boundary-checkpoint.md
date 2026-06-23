# Step 260: OpenAI Settings Fallback Legacy / Transitional Post-smoke Release-boundary Checkpoint

## Step Purpose

Step 260 is a docs-only / decision-checkpoint-only record created after the
Step 259 controlled human admin smoke.

The purpose is to separate:

1. what Step 259 established within the human-visible temporary local-only
   smoke scope; and
2. what remains unresolved before any public-release readiness conclusion.

Step 259 is classified as:

```text
Scope-bound Pass
```

That pass must not be expanded into actual credential validity, OpenAI provider
acceptance, real request success, WordPress.org release approval, or external
policy compliance.

WordPress.org release readiness remains:

```text
Hold
```

## Referenced Documents

- `docs/maturation/step253-openai-constant-based-configuration-public-release-boundary-checkpoint.md`
- `docs/maturation/step254-openai-settings-fallback-public-release-storage-disposition-decision-checkpoint.md`
- `docs/maturation/step255-openai-settings-fallback-public-release-disposition-narrow-implementation-plan.md`
- `docs/maturation/step256-openai-settings-fallback-legacy-transitional-narrow-production-implementation-results.md`
- `docs/maturation/step257-openai-settings-fallback-legacy-transitional-source-level-verification-results.md`
- `docs/maturation/step258-openai-settings-fallback-legacy-transitional-controlled-human-admin-smoke-plan.md`
- `docs/maturation/step259-openai-settings-fallback-legacy-transitional-controlled-human-admin-smoke-results.md`

## Scope

In scope:

- post-smoke release-boundary checkpoint for the OpenAI Settings fallback;
- category/status-level interpretation of Step 259;
- public configuration route disposition;
- legacy / transitional Settings fallback disposition;
- unresolved public-release gates;
- decision matrix for remaining release boundaries.

Out of scope:

- production PHP changes;
- JavaScript or CSS changes;
- `readme.txt` changes;
- `uninstall.php` changes;
- tools changes;
- existing docs changes;
- `wp-dev` fixture/helper creation or removal;
- `wp-dev-check` use or modification;
- browser admin smoke;
- Settings save;
- `clear_openai_api_key` operation;
- WP-CLI state mutation;
- `wp option get`;
- raw SQL / database dump;
- option, constant, credential, or token value inspection;
- OpenAI Generate;
- GA4 Fetch;
- OAuth;
- external HTTP;
- provider communication;
- Plugin Check;
- screenshots;
- browser Network evidence.

## Step 259 Established Scope

Step 259 established the following state transition within a temporary
local-only, non-credential, human-visible admin smoke scope:

```text
S0:
missing / not_saved / hidden / clear hidden

S1:
settings_saved / saved / hidden / clear visible

S2:
constant_configured / saved / hidden / clear visible

S3:
constant_configured / not_saved / hidden / clear hidden

S4:
missing / not_saved / hidden / clear hidden
```

Within that limited scope, Step 259 confirmed:

- normal Settings UI does not present a new fallback password-entry route;
- existing saved fallback is represented as a legacy / transitional
  compatibility state;
- saved fallback is value-hidden;
- constant source is presented as preferred when configured;
- clear control is scoped to a saved Settings fallback;
- controlled clear leaves `constant_configured` as the active visible category;
- temporary state cleanup restores the missing baseline.

These are UI and category/status conclusions only. They do not inspect, prove,
or preserve actual credential material.

## Current Checkpoint Decision

### Public Normal Configuration Route

Preferred public configuration route:

```text
constant-based configuration
```

Constant name:

```text
ANALYTICS_REPORT_AI_OPENAI_API_KEY
```

Current checkpoint decision:

- normal public user guidance should treat constant-based configuration as the
  primary route;
- Settings fallback should not be described as the ordinary public setup method;
- public-facing setup guidance must avoid credential examples or actual values;
- a source category must not be treated as proof of provider acceptance or
  request success.

### Settings Fallback Public Disposition

Current disposition:

```text
Settings fallback:
legacy / transitional compatibility state only

Not:
ordinary new-user setup route
primary public setup route
credential entry UI
general-purpose public configuration feature
```

Step 256 and Step 259 together establish the current UI boundary:

- existing `settings_saved` fallback can still be represented for compatibility;
- the saved fallback remains value-hidden;
- a saved fallback may expose a clear-only control;
- the clear control is scoped to the Settings fallback only;
- normal Settings UI no longer presents a new OpenAI fallback password-entry
  route.

This does not establish that fallback storage is safe or final for public
release. It also does not establish a public support commitment for retaining
Settings fallback storage indefinitely.

### "Developer-only" Wording Boundary

Current checkpoint decision:

```text
"developer-only" is a release-disposition concept only.
```

It does not establish:

- role-gated access;
- capability enforcement;
- technical access control;
- a separate developer-only UI.

Public-facing UI and documentation should not rely on `developer-only` as the
primary technical description unless a later implementation adds a real gate.
For the current implementation, `legacy / transitional compatibility` is the
technically accurate wording boundary.

## Public-release Unresolved Gates

### A. Constant-based Public Setup Documentation

Status:

```text
Needs decision / implementation
```

Remaining gates:

- public-facing setup instructions for constant-based configuration;
- placement / scope explanation appropriate for WordPress administrators;
- value-hidden and non-redisplay explanation;
- no credential examples or actual values in docs.

### B. Legacy / Transitional Fallback Documentation and Support Boundary

Status:

```text
Needs decision / implementation
```

Remaining gates:

- whether legacy fallback behavior is mentioned in public docs;
- whether it is limited to migration / compatibility notes only;
- support wording that does not invite new fallback use;
- clear-only behavior disclosure;
- no claim of technical developer-only enforcement.

### C. Storage / Migration / Uninstall Disposition

Status:

```text
Needs decision / implementation
```

Remaining gates:

- public-release treatment of pre-existing Settings fallback values;
- whether compatibility remains, is removed, or is further constrained before
  release;
- migration decision, if any;
- uninstall and multisite review scope;
- no automatic deletion or migration is assumed by this checkpoint.

### D. Provider / Runtime Verification Boundary

Status:

```text
Not established by Step 259
```

Remaining gates:

- actual credential validity remains unverified;
- provider authorization remains unverified;
- OpenAI request success remains unverified;
- external communication behavior remains unverified;
- source category does not prove provider acceptance.

### E. Broader Release Quality Gates

Status:

```text
Hold / separate release tracks
```

Remaining gates related to the broader release posture:

- Plugin Check rerun at the appropriate later stage;
- privacy and external-transmission disclosures finalization;
- support/debug redaction wording alignment;
- OAuth lifecycle and reconnect/revocation remaining work, where relevant to
  full plugin release;
- final readme / user guidance review.

Step 260 does not execute, solve, or verify these gates.

## Decision Matrix

| Topic | Current disposition | Established by Step 259? | Public-release status |
| ----- | ------------------- | ------------------------ | --------------------- |
| Constant-based configuration as preferred route | Preferred public configuration route using `ANALYTICS_REPORT_AI_OPENAI_API_KEY`. | Human-visible scope only for preferred wording; source-level priority came from prior steps. | Needs public setup documentation and final guidance. |
| Normal Settings fallback entry | Normal new fallback password-entry route is not presented in the current Settings UI. | Human-visible scope only. | Accept as current boundary; keep from being re-promoted without a later decision. |
| Existing Settings fallback compatibility | Legacy / transitional compatibility state remains possible when a fallback is already saved. | Human-visible scope only for S1 / S2. | Hold as compatibility; not accepted as general public setup. |
| Clear-only fallback behavior | Clear control is visible only for saved fallback state and is scoped to Settings fallback. | Human-visible scope only; source-level boundary came from prior verification. | Accept as current MVP boundary; migration / uninstall relationship remains separate. |
| Actual credential validity | Not determined. | Not established. | Hold / separate provider verification. |
| Actual provider request success | Not determined. | Not established. | Hold / separate runtime/provider verification. |
| Public documentation | Constant-based direction and fallback disposition are checkpointed here. | Not established by Step 259. | Needs implementation / review. |
| Storage / migration / uninstall disposition | No automatic deletion or migration assumed; root uninstall relationship remains a separate boundary. | Not established by Step 259. | Needs decision / implementation. |
| WordPress.org release readiness | Release remains on Hold. | Not established. | Hold. |

## Explicit Non-conclusions

Step 260 does not:

- change production behavior;
- approve public release;
- establish actual API key validity;
- establish actual constant value or preservation;
- establish Settings fallback option contents;
- establish provider authorization;
- establish OpenAI request or response success;
- establish real external communication behavior;
- establish Plugin Check status;
- establish WordPress.org policy compliance.

This checkpoint does not research, quote, or assert current WordPress.org
policy. It records only the current project boundary and remaining gates.

## Result Classification

```text
Step 260 checkpoint conclusion: Decision checkpoint completed
Step 259 result carried forward: Scope-bound Pass
Preferred public route: constant-based configuration
Settings fallback disposition: legacy / transitional compatibility state only
Developer-only wording boundary: release-disposition concept only, not technical enforcement
WordPress.org release readiness: Hold
```

## Recommended Next Step

```text
Step 261 candidate — OpenAI constant-based public setup and legacy/transitional fallback disclosure plan
```

Step 261 is not started in this document.
