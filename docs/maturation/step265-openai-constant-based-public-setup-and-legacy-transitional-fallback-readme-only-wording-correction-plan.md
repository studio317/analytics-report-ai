# Step 265: OpenAI Constant-based Public Setup and Legacy / Transitional Fallback Readme-only Wording Correction Plan

## Step Purpose

Step 265 is a docs-only / wording-correction-planning-only checkpoint.

It carries forward the Step 264 source-level finding that one sentence in
`readme.txt` needs a narrow wording correction. This step plans the correction
only. It does not change `readme.txt`, production behavior, Settings UI,
Report Builder UI, existing docs, runtime state, or release readiness.

WordPress.org release readiness remains:

```text
Hold
```

## Referenced Documents

- `docs/maturation/step260-openai-settings-fallback-legacy-transitional-post-smoke-release-boundary-checkpoint.md`
- `docs/maturation/step261-openai-constant-based-public-setup-and-legacy-transitional-fallback-disclosure-plan.md`
- `docs/maturation/step262-openai-constant-based-public-setup-and-legacy-transitional-fallback-disclosure-narrow-documentation-implementation-plan.md`
- `docs/maturation/step263-openai-constant-based-public-setup-and-legacy-transitional-fallback-readme-only-narrow-documentation-implementation-results.md`
- `docs/maturation/step264-openai-constant-based-public-setup-and-legacy-transitional-fallback-readme-only-source-level-verification-results.md`

## Step 264 Finding Carried Forward

Step 264 classified the readme-only source-level verification as:

```text
Needs narrow wording correction
```

Step 264 confirmed the following Step 263 core disclosures as source-level
aligned:

- preferred constant-based configuration guidance: Pass;
- existing fallback disclosure boundary: Pass;
- provider/runtime limitation wording: Pass;
- Support and Debug Evidence preservation: Pass;
- UI/runtime alignment: Pass.

The remaining issue is sentence-level precision in the
`= Credential Storage and Payload Review =` section.

## Current Wording Scope

Current readme context:

```text
OAuth client Settings fallback configuration and existing legacy / transitional OpenAI API key fallback values may be stored in plugin settings.

...

This storage posture is for MVP and developer verification, and OpenAI API key storage and OAuth client Settings fallback storage remain separate public-release decisions.
```

This wording is not changed in Step 265.

Precision issue:

- the first sentence correctly narrows the OpenAI Settings storage surface to
  existing legacy / transitional OpenAI API key fallback values;
- the later phrase `OpenAI API key storage` is broader than the intended
  fallback-only scope;
- that broader phrase could reasonably be read as treating preferred
  constant-based configuration itself as an unresolved plugin-settings storage
  route.

This is a wording scope issue only. It is not a runtime behavior finding,
credential exposure finding, provider verification finding, or public-release
approval finding.

## Fixed Correction Objective

The future correction must clearly express:

- preferred OpenAI configuration route is constant-based configuration;
- `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is not described as a plugin-settings
  storage route;
- unresolved OpenAI storage decision is limited to an existing legacy /
  transitional OpenAI API key fallback stored in plugin settings;
- OAuth client Settings fallback storage remains a separate public-release
  decision;
- no new fallback setup route is introduced;
- no credential, constant value, option value, or secret example is added.

## Planned Target Wording Direction

Future implementation should replace only the broad storage-scope phrase with
this sentence-level target:

```text
This storage posture is for MVP and developer verification, and existing legacy / transitional OpenAI API key fallback storage and OAuth client Settings fallback storage remain separate public-release decisions.
```

This target satisfies the Step 265 boundary because:

- `existing legacy / transitional` modifies the OpenAI fallback storage scope;
- `OpenAI API key storage` alone is not retained as a broad unresolved
  category;
- constant-based configuration is not described as stored in plugin settings;
- OAuth client Settings fallback storage remains present as a separate
  unresolved category;
- it makes no promise of security, encryption, or public-release approval;
- it does not use `developer-only` as a technical access-control claim.

Minor grammar adjustment may be considered during implementation only if it
preserves the same scope and does not add new concepts.

## Narrow Future Change Boundary

Future correction implementation may change only:

- `readme.txt`

The planned correction should be limited to the sentence in
`= Credential Storage and Payload Review =` that currently contains the broader
`OpenAI API key storage` phrase.

Future correction implementation must not change:

- production PHP;
- JavaScript;
- CSS;
- Settings UI wording;
- Report Builder UI wording;
- `uninstall.php`;
- tools;
- plugin version;
- plugin header;
- Stable tag;
- changelog;
- `wp-dev` files;
- `wp-dev-check` files;
- temporary fixture/helper;
- existing docs.

## Planned Regression Checks

Future source-level verification for the correction should confirm:

1. `= Credential Storage and Payload Review =` only is changed.
2. Preferred constant-based guidance remains present:
   `ANALYTICS_REPORT_AI_OPENAI_API_KEY`.
3. Constant is loaded before WordPress plugins guidance remains present.
4. Plugin does not display or edit constant value wording remains present.
5. Existing legacy / transitional fallback is still conditional compatibility
   only.
6. Fallback is not described as a normal setup route or constant alternative.
7. Fallback removal control still applies only to saved Settings fallback.
8. Source category/readiness still does not verify provider authorization or
   request success.
9. `= Support and Debug Evidence =` remains unchanged unless an unrelated
   pre-existing issue is found.
10. No PHP snippet, fake key, placeholder, API key format, secret assignment,
    host-specific tutorial, or credential value is added.
11. No wording claims actual storage safety, provider success, WordPress.org
    policy compliance, or public-release approval.

## Explicit Non-goals

Step 265 does not:

- change `readme.txt`;
- change production behavior;
- revise Settings or Report Builder UI;
- change OpenAI credential resolver priority;
- change any Settings fallback storage behavior;
- change uninstall behavior;
- decide migration or multisite handling;
- add credential examples;
- verify actual credential validity;
- verify actual constant value or preservation;
- verify actual Settings fallback option contents;
- verify provider authorization;
- verify OpenAI request or response success;
- verify real external communication;
- run Plugin Check;
- approve public release;
- establish WordPress.org policy compliance.

## Security and Evidence Boundary

Step 265 did not inspect or record:

- actual credentials;
- API key values;
- constant values;
- Settings option values;
- tokens;
- placeholders;
- serialized option data;
- request bodies;
- response bodies;
- payload JSON;
- generated report bodies.

Step 265 did not perform:

- browser admin smoke;
- Settings save;
- `clear_openai_api_key` operation;
- WP-CLI state mutation;
- `wp option get`;
- raw SQL / database dump;
- OpenAI Generate;
- GA4 Fetch;
- OAuth;
- external HTTP;
- provider communication;
- Plugin Check;
- screenshots;
- browser Network evidence.

## Result Classification

```text
Step 265 correction-plan conclusion: Narrow wording correction plan completed
Correction scope: readme.txt sentence-level precision only
Implementation started: No
WordPress.org release readiness: Hold
```

## Recommended Next Step

```text
Step 266 candidate — OpenAI constant-based public setup and legacy/transitional fallback readme-only narrow wording correction implementation
```

Step 266 is not started in this document.
