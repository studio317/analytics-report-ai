# Step 261: OpenAI Constant-based Public Setup and Legacy / Transitional Fallback Disclosure Plan

## Step Purpose

Step 261 is a docs-only / disclosure-planning-only checkpoint after Step 260.

The purpose is to design, at implementation-planning granularity:

1. minimal, safe public guidance for constant-based OpenAI configuration; and
2. a limited disclosure boundary for the legacy / transitional Settings fallback
   that does not invite new fallback use.

This step does not approve public release and does not change any production
behavior, public documentation, Settings UI, Report Builder UI, fixture, or
runtime state.

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

## Fixed Inputs

Preferred public configuration route:

```text
constant-based configuration
```

Constant name:

```text
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

"Developer-only" wording boundary:

```text
"developer-only" is a release-disposition concept only.

It does not establish:
- role-gated access
- capability enforcement
- technical access control
- a separate developer-only UI
```

Public-facing documentation should not use `developer-only` as the primary
technical explanation for the current implementation. The current
implementation is more accurately described as a `legacy / transitional
compatibility` boundary when the Settings fallback must be mentioned.

## Public Documentation Content Model

### A. Normal Setup: Constant-based Configuration

Planned guidance direction for administrators and deployment owners:

- OpenAI configuration uses `ANALYTICS_REPORT_AI_OPENAI_API_KEY` as the
  preferred route.
- The constant should be defined through a configuration mechanism loaded before
  WordPress plugins.
- The exact placement method depends on the site's deployment, hosting, and
  configuration process.
- The plugin does not display or edit the constant value.
- Source category / readiness does not prove provider acceptance or successful
  API requests.
- Credentials must not be copied into support requests, screenshots, or public
  documentation.

Value-free, deployment-neutral wording direction for a future implementation:

```text
Define ANALYTICS_REPORT_AI_OPENAI_API_KEY through a configuration mechanism that is loaded before WordPress plugins. Use the configuration and secret-handling process appropriate to your hosting or deployment environment. The plugin does not display or edit this value.
```

This is a wording direction, not finalized public copy.

Step 261 intentionally does not propose:

- a PHP `define()` snippet;
- a fake or placeholder key;
- API key format examples;
- real or sample credential values;
- copy-paste-ready secret assignment examples;
- host-specific or deployment-specific secret-management instructions presented
  as universally supported.

### B. Existing Legacy / Transitional Fallback Disclosure

Planned guidance direction for existing installations:

- The fallback is not introduced as a setup method.
- The fallback is not described as a normal alternative to constants.
- Documentation should not explain how to create, restore, inject, or manually
  modify a fallback.
- Documentation should not ask users to inspect Settings option values.
- Support should not request values.
- Value-hidden posture should be stated when a saved fallback status is visible.
- Clear-only behavior may be described only as removal / cleanup for an
  already-saved compatibility state.
- Clearing the fallback must not be described as editing the constant-based
  source.

Limited disclosure wording direction for a future implementation:

```text
If this site already has a saved legacy / transitional Settings fallback, the plugin may show that status without revealing its value. This compatibility state is not the normal setup route. Where a removal control is shown, it applies only to the saved Settings fallback and does not edit the constant-based configuration.
```

This is a wording direction, not finalized public copy.

### C. Status / Visibility / Troubleshooting Disclosure

Safe evidence categories for public documentation and support guidance:

- source category;
- Settings fallback status;
- value display status;
- whether the clear control is visible;
- page loaded / visible fatal error presence.

Support wording should ask for status/category-level evidence only. It should
not ask users to share credentials, configuration values, option contents, raw
runtime evidence, screenshots, or browser Network data.

Forbidden support/debug evidence:

- credentials;
- API keys;
- constant values;
- Settings option values;
- serialized option data;
- tokens;
- Authorization headers;
- request bodies;
- raw responses;
- payload JSON;
- generated report body;
- screenshots;
- browser Network evidence;
- cookies, sessions, nonces.

### D. Provider / Request Boundary Disclosure

Public guidance must not overstate configuration status.

Planned disclosure boundary:

- Constant source category is not proof of provider authorization.
- Source readiness is not proof of OpenAI request success.
- Actual credential validity remains a separate verification boundary.
- Provider request / response behavior remains separate from configuration
  status.

External communication procedures and API request examples are outside the
Step 261 documentation plan.

## Disclosure Audience Matrix

| Audience | Need | Recommended disclosure | Do not disclose | Implementation target |
| -------- | ---- | ---------------------- | --------------- | --------------------- |
| New administrator / deployment owner | Understand the normal OpenAI setup route. | Constant-based configuration is preferred; the constant must be loaded before WordPress plugins; the plugin does not display or edit the value. | Secret values, code assignment examples, API key examples, provider success guarantees. | `readme.txt` setup section; Settings explanatory copy. |
| Existing installation with legacy / transitional saved fallback | Understand a saved fallback status without being invited to create one. | Existing saved fallback may be shown as a hidden legacy / transitional compatibility state; clear-only control removes only that fallback. | How to create, restore, inject, or manually edit a fallback; option values; credential values. | Settings explanatory copy; support / troubleshooting guidance. |
| Support / troubleshooting requester | Share safe evidence for diagnosis. | Source category, fallback status, value display status, clear-control visibility, page loaded, visible fatal error presence. | Credentials, option values, request bodies, raw responses, payload JSON, generated report bodies, screenshots, Network evidence. | Support / troubleshooting guidance. |
| Public reader evaluating plugin setup | Understand the release posture and limitations. | Constant-based configuration is the intended normal route; legacy fallback is conditional compatibility; source status does not prove provider success. | Public-release approval claims, policy compliance claims, actual credential details, universal hosting instructions. | `readme.txt`; privacy / external-transmission disclosure. |

## Documentation Artifact Map

| Artifact | Required message | Fallback mention permitted? | Credential/value examples permitted? | Decision / implementation status |
| -------- | ---------------- | --------------------------- | ------------------------------------ | -------------------------------- |
| `readme.txt` | Constant-based public setup is required before release; the plugin does not display or edit the constant value; source status is not provider success. | Conditional and minimal, only as legacy / transitional compatibility if needed. | No. No secret, placeholder, API key, or code assignment example. | Needs narrow documentation implementation plan. |
| Settings explanatory copy | Preserve constant-first / value-hidden posture and clarify status categories. | Yes, only when explaining an existing saved fallback and clear-only cleanup. | No. | Current UI already narrowed; future public wording still needs implementation review. |
| Report Builder source/readiness wording | Preserve source/readiness limitation wording and avoid implying provider acceptance from source category. | Minimal, only if source category is `settings_saved` and framed as compatibility. | No. | Current UI already narrowed; future public wording still needs implementation review. |
| Support/debug guidance | Use category/status-level evidence only and prohibit credential or option value sharing. | Yes, only to explain safe fallback status labels and clear-control visibility. | No. | Needs dedicated support guidance update. |
| Privacy / external-transmission disclosure | Must remain aligned with actual runtime behavior and external-service disclosures. | Only if storage / setup posture requires a compatibility note. | No. | Separate review; Step 261 does not finalize it. |

## Wording Rules

### Preferred Wording Categories

- preferred constant-based configuration;
- configuration mechanism loaded before WordPress plugins;
- value is not displayed or edited by the plugin;
- legacy / transitional compatibility state;
- existing saved fallback;
- removal / cleanup control for a saved fallback;
- source category does not confirm provider acceptance;
- do not share credentials or configuration values.

### Avoided Wording Categories

- developer-only, when it appears to claim access enforcement;
- enter an API key in Settings;
- Settings fallback as an alternative setup method;
- fallback as a recommended migration destination;
- credentials are securely stored;
- provider connection is verified merely because source status is configured;
- request success is guaranteed;
- source status proves valid API access.

## Unresolved Decisions and Release Gates

Step 261 carries forward Step 260's unresolved gates and adds documentation
planning detail. The following remain unresolved:

- exact public documentation placement and information architecture;
- whether public docs need an environment-specific appendix or only
  deployment-neutral guidance;
- final public disclosure wording for legacy / transitional fallback;
- support escalation boundary when source category is configured but provider
  requests fail;
- storage / migration / uninstall relation;
- privacy / external-transmission disclosure;
- provider/runtime verification;
- Plugin Check;
- OAuth lifecycle and reconnect/revocation as separate full-release gates.

This step does not pre-approve implementation, release, policy compliance, or
provider behavior.

## Decision Matrix

| Topic | Step 261 planning decision | Ready for public-doc implementation? | Still requires separate gate? |
| ----- | -------------------------- | ------------------------------------ | ----------------------------- |
| Constant name disclosure | The constant name `ANALYTICS_REPORT_AI_OPENAI_API_KEY` may be disclosed as the preferred configuration source. | Yes, with value-free wording. | Final placement / copy review. |
| Constant setup wording | Use deployment-neutral wording: configuration mechanism loaded before WordPress plugins. | Yes, as planned wording direction. | Final public copy implementation. |
| Code or credential examples | Do not include code assignment examples, fake keys, placeholder keys, or API key format examples. | Yes, as a prohibition. | Re-check during implementation. |
| Existing fallback disclosure | Mention only conditionally as a legacy / transitional compatibility state when relevant. | Partially. | Final public wording and support boundary. |
| Clear-only fallback wording | Describe only as removal / cleanup for an already-saved fallback and not as editing constants. | Partially. | UI / docs alignment review. |
| Developer-only terminology | Avoid as primary public wording because it can imply technical enforcement. | Yes, as an avoided wording category. | Re-check if future technical gate is implemented. |
| Status-level troubleshooting evidence | Use source category, fallback status, value display status, clear-control visibility, page load, and visible fatal error presence. | Yes. | Support/debug guidance implementation. |
| Provider/request success wording | State that source category/readiness does not prove provider authorization or OpenAI request success. | Yes. | Provider/runtime verification remains separate. |
| Privacy / external-transmission disclosure | Keep aligned with actual runtime behavior; Step 261 does not finalize it. | No. | Separate disclosure review. |
| Storage / migration / uninstall | No automatic deletion or migration is assumed; relation remains unresolved. | No. | Separate storage / migration / uninstall decision. |
| WordPress.org release readiness | Remains `Hold`. | No. | Later release-readiness track, including Plugin Check when appropriate. |

## Explicit Non-conclusions

Step 261 does not:

- change production behavior;
- add or revise public documentation;
- add a constant configuration code sample;
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
Step 261 planning conclusion: Disclosure plan completed
Preferred public route: constant-based configuration
Settings fallback disclosure: conditional legacy / transitional compatibility only
Credential/value examples: prohibited
Provider success claims: prohibited
WordPress.org release readiness: Hold
```

## Recommended Next Step

```text
Step 262 candidate — OpenAI constant-based public setup and legacy/transitional fallback disclosure narrow documentation implementation plan
```

Step 262 is not started in this document.
