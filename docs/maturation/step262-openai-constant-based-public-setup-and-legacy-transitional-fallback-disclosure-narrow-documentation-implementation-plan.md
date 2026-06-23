# Step 262: OpenAI Constant-based Public Setup and Legacy / Transitional Fallback Disclosure Narrow Documentation Implementation Plan

## Step Purpose

Step 262 is a docs-only / narrow documentation implementation planning-only
checkpoint.

It translates the Step 261 disclosure plan into a future minimal documentation
implementation tranche. It does not change public documentation, Settings UI,
Report Builder UI, production code, runtime behavior, fixtures, or release
state.

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
- `docs/maturation/step259-openai-settings-fallback-legacy-transitional-controlled-human-admin-smoke-results.md`
- `docs/maturation/step260-openai-settings-fallback-legacy-transitional-post-smoke-release-boundary-checkpoint.md`
- `docs/maturation/step261-openai-constant-based-public-setup-and-legacy-transitional-fallback-disclosure-plan.md`

## Scope

In scope:

- read-only source inventory for relevant documentation and UI wording;
- classification of documentation artifacts into a narrow tranche, preserved
  surfaces, or separate release gates;
- future readme placement planning;
- value-free / deployment-neutral candidate wording direction;
- UI wording preservation / candidate-delta review;
- unresolved release gate tracking.

Out of scope:

- production PHP / JavaScript / CSS changes;
- `readme.txt` changes;
- Settings or Report Builder UI changes;
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

## Fixed Disclosure Principles

Preferred public configuration route:

```text
constant-based configuration

ANALYTICS_REPORT_AI_OPENAI_API_KEY
```

Settings fallback disposition:

```text
legacy / transitional compatibility state only

Not:
- ordinary new-user setup route
- primary public setup route
- credential entry UI
- general-purpose public configuration feature
```

Developer-only wording boundary:

```text
"developer-only" is a release-disposition concept only.

It does not establish:
- role-gated access
- capability enforcement
- technical access control
- a separate developer-only UI
```

`developer-only` should not be used as the primary public-facing technical
explanation for the current implementation.

Credential and example boundary:

- no PHP `define()` code snippet;
- no fake API key;
- no placeholder key;
- no API key format example;
- no copy-paste-ready secret assignment;
- no host-specific secret-management guidance presented as universally
  supported;
- no actual credential or value.

## Source-based Artifact Classification

| Artifact | Current relevant wording or current role | Step 261 alignment | Step 262 disposition | Reason | Proposed later action |
| -------- | ---------------------------------------- | ------------------ | -------------------- | ------ | --------------------- |
| `readme.txt` | Existing sections include `External Services`, `OpenAI API`, `Credential Storage and Payload Review`, and `Support and Debug Evidence`. Current readme explains OpenAI API use and storage posture, but does not yet provide a dedicated value-free constant-based setup explanation. | Partially aligned. It already avoids value examples and has strong support/debug boundaries, but needs clearer constant-based public setup guidance. | Modify in narrow tranche. | The public normal setup route needs documentation, and readme is the primary public artifact. | Add a value-free, deployment-neutral OpenAI configuration note in `Credential Storage and Payload Review`; optionally add a short support-safe status evidence sentence without credential examples. |
| `includes/class-settings.php` | Settings UI shows `OpenAI API Key Source`, active source, Settings fallback, value display status, constant-first guidance, legacy / transitional saved fallback wording, and fallback-only clear scope. | Aligned. | Preserve in this tranche. | Current UI already covers constant-first, value-hidden, saved fallback as legacy / transitional, clear-only scope, no new fallback entry route, and no credential value display. | No Step 263 UI change. Revisit only if readme wording later exposes a UI mismatch. |
| `includes/class-report-builder.php` | Report Builder shows `OpenAI API Key Source`, source category, fallback status, value visibility, constant-preferred guidance, legacy / transitional fallback compatibility, and provider/request limitation wording. | Aligned. | Preserve in this tranche. | Current wording states source category does not prove provider acceptance or request success and directs missing state to preferred constant-based configuration. | No Step 263 UI change. Revisit only if future release wording requires a small copy alignment. |
| `support/debug guidance` | Public readme includes `Support and Debug Evidence` and prohibits credentials, API keys, option values, raw API evidence, generated report text, identifiers, and analytics values. | Mostly aligned. | Preserve in this tranche. | The existing public support boundary is already broad and conservative. The readme setup note can mention safe status categories without creating a separate support rewrite. | Keep as-is for Step 263 unless final copy review decides to add a small source-category example in readme. Dedicated support docs remain a separate gate. |
| `privacy / external-transmission disclosure` | Readme has `External Services`, `Google Analytics Data API`, `OpenAI API`, and `Credential Storage and Payload Review` sections aligned with actual runtime behavior. | Related but not finalized by Step 261. | Separate gate. | Privacy/external-transmission wording must remain aligned with actual runtime behavior and should not be widened in this narrow setup-doc tranche. | Revisit after readme setup implementation and any storage/provider decisions. |
| `storage / migration / uninstall documentation` | Existing readme mentions MVP storage posture and local-only disconnect/uninstall boundaries. Storage / migration / uninstall disposition for OpenAI fallback remains unresolved. | Not ready for final public-release disposition. | Separate gate. | Pre-existing fallback storage, migration, uninstall, and multisite implications are not settled by Step 262. | Keep separate from Step 263. Plan a later storage / migration / uninstall documentation checkpoint if needed. |

## Minimum Future Documentation Implementation Tranche

### Option A: `readme.txt` Only

This option adds or adjusts only the public readme guidance for OpenAI
constant-based configuration and conditional legacy / transitional fallback
disclosure.

Pros:

- documents the public normal setup route where public readers will look first;
- avoids churn in Settings / Report Builder wording that already aligns with
  Step 261;
- avoids reintroducing fallback as a UI route;
- keeps privacy, provider/runtime, storage, migration, and uninstall decisions
  in separate gates.

Cons:

- does not further refine admin UI wording;
- may require a later UI copy review after final public-doc wording is chosen.

### Option B: `readme.txt` + Narrowly Scoped Settings Explanatory Copy

This option updates readme and a small amount of Settings explanatory copy.

Pros:

- could mirror public setup language in the Settings screen.

Cons:

- current Settings wording already meets the Step 261 disclosure direction;
- unnecessary UI churn could increase verification scope without adding a clear
  release-boundary benefit.

### Option C: `readme.txt` + Settings Explanatory Copy + Report Builder Wording

This option updates readme, Settings, and Report Builder wording together.

Pros:

- keeps all public/admin surfaces synchronized in one step if a future copy
  review finds a mismatch.

Cons:

- current UI wording is already aligned;
- widest scope and highest churn;
- would require broader source-level and human admin verification.

### Recommended Option

```text
Recommended option: Option A — readme.txt only
```

Reason:

- public normal setup route needs a clear public documentation location;
- Settings fallback must not be re-exposed as a new setup route;
- current Settings and Report Builder wording already supports constant-first,
  value-hidden, legacy / transitional fallback, and provider/request limitation
  boundaries;
- avoiding unnecessary UI wording churn keeps the next implementation narrow;
- privacy / provider-runtime / migration / uninstall topics remain separate
  gates.

## Candidate Public Wording Plan

This section plans future readme wording only. It does not change `readme.txt`
and does not finalize public copy.

### Constant-based Guidance Content

Candidate wording direction:

```text
For OpenAI configuration, use the preferred constant-based source: ANALYTICS_REPORT_AI_OPENAI_API_KEY. Define this constant through a configuration mechanism that is loaded before WordPress plugins. The exact placement depends on your hosting or deployment configuration. The plugin does not display or edit this value.
```

Additional limitation direction:

```text
The OpenAI source category or readiness status shows which configuration source the plugin can use. It does not verify provider authorization or prove that an OpenAI API request will succeed.
```

Support-safety direction:

```text
Do not share credentials, configuration values, screenshots containing credential-related details, or public posts with configuration values when requesting support.
```

Do not include:

- PHP assignment examples;
- fake values;
- placeholder keys;
- API key format examples;
- host-specific setup tutorials;
- provider diagnostic procedures.

### Conditional Fallback Disclosure Content

Candidate wording direction for existing installations only:

```text
Existing installations may show a saved legacy / transitional Settings fallback as a hidden compatibility state. This is not the normal configuration route. If a removal control is visible, it applies only to the saved Settings fallback and does not edit constant-based configuration.
```

Do not include:

- fallback creation instructions;
- fallback restoration instructions;
- fallback injection instructions;
- direct option modification instructions;
- any instruction to inspect or share option values.

### Provider / Runtime Limitation Content

Candidate wording direction:

```text
Configured source status is separate from provider acceptance and runtime request results. Provider or request failures must be diagnosed separately from configuration source status.
```

Do not include provider diagnostic procedures or API request examples in this
narrow readme tranche.

## UI Wording Preservation / Candidate-delta Table

| Surface | Current wording role | Meets Step 261 disclosure plan? | Step 262 decision | Candidate delta, if any |
| ------- | -------------------- | ------------------------------- | ----------------- | ----------------------- |
| Settings OpenAI API Key Source row | Shows active source, Settings fallback status, value display status, constant-first guidance, legacy / transitional fallback wording, and fallback-only clear scope. | Yes. | Preserve current wording. | None — preserve current wording. |
| Settings Credential Storage (MVP) block | Explains constant-first OpenAI API key source, legacy / transitional fallback, database-access risk, and Restricted key recommendation. | Yes for this tranche. | Preserve current wording. | None — preserve current wording. |
| Report Builder Current Settings | Shows OpenAI source category and states source category does not confirm provider acceptance or request success. | Yes. | Preserve current wording. | None — preserve current wording. |
| OpenAI missing-key error wording | Directs administrators to configure the preferred OpenAI API key constant source before generating. | Yes. | Preserve current wording. | None — preserve current wording. |

No new controls, settings, input fields, UI redesign, or public setup route is
planned in Step 262.

## Readme Placement Map

| Proposed readme location | Current section / heading | Planned message | Why this placement | Not included |
| ------------------------ | ------------------------- | --------------- | ------------------ | ------------ |
| Primary OpenAI configuration note | `= Credential Storage and Payload Review =` | Add value-free guidance that `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is the preferred OpenAI configuration source, must be loaded before WordPress plugins, and is not displayed or edited by the plugin. | This section already discusses credential storage, value non-redisplay, MVP storage posture, and public-release decisions. | No code sample, fake key, placeholder key, API key format, host-specific tutorial, or provider success guarantee. |
| Conditional existing-fallback note | `= Credential Storage and Payload Review =` | State that an existing saved fallback may appear only as a hidden legacy / transitional compatibility state and is not the normal configuration route; removal control affects only the saved fallback. | This is the current section most directly tied to fallback storage posture and clear-only behavior. | No fallback creation/restoration/injection/direct-option instructions. |
| Support-safe evidence reminder | `= Support and Debug Evidence =` | Optionally preserve or lightly connect existing support guidance to source category / fallback status / value display status if final copy review needs it. | This section already prohibits credential and raw evidence sharing. | No credentials, option values, screenshots, Network evidence, raw request/response, payload JSON, or generated report body. |

## Explicit Non-goals for Future Documentation Implementation

Even in the future narrow documentation implementation, the following remain
out of scope:

- credential examples;
- secret assignment code;
- host-specific configuration tutorial;
- fallback creation or restoration instructions;
- provider success guarantee;
- runtime request troubleshooting procedure;
- public assertion of WordPress.org policy compliance;
- storage / migration / uninstall decision;
- privacy / external-transmission finalization;
- OAuth lifecycle / reconnect / revocation implementation.

## Unresolved Release Gates

The following release gates remain unresolved:

- final public documentation copy review;
- exact placement / information architecture decision;
- environment-specific appendix versus deployment-neutral guidance;
- final conditional fallback disclosure wording;
- support escalation boundary for configured source with provider failure;
- storage / migration / uninstall disposition;
- privacy / external-transmission disclosure;
- provider/runtime verification;
- Plugin Check at the appropriate later stage;
- OAuth lifecycle and reconnect/revocation full-release gates.

## Explicit Non-conclusions

Step 262 does not:

- change production behavior;
- change `readme.txt` or UI wording;
- approve a public documentation implementation;
- approve public release;
- establish actual API key validity;
- establish actual constant value or preservation;
- establish Settings fallback option contents;
- establish provider authorization;
- establish OpenAI request or response success;
- establish real external communication behavior;
- establish Plugin Check status;
- establish WordPress.org policy compliance.

This step does not research, quote, or assert current WordPress.org policy.

## Result Classification

```text
Step 262 planning conclusion: Narrow documentation implementation plan completed
Recommended minimum future implementation tranche: readme.txt only
Settings UI disposition: preserve in this tranche
Report Builder UI disposition: preserve in this tranche
Support/debug guidance disposition: preserve in this tranche, with later gate if needed
Privacy / external-transmission disclosure: separate gate
Storage / migration / uninstall documentation: separate gate
WordPress.org release readiness: Hold
```

## Recommended Next Step

```text
Step 263 candidate — OpenAI constant-based public setup and legacy/transitional fallback readme-only narrow documentation implementation
```

Step 263 is not started in this document.
