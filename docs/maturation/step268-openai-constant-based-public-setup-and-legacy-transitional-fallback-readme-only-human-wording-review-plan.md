# Step 268: OpenAI Constant-based Public Setup and Legacy / Transitional Fallback Readme-only Human Wording Review Plan

## Step Purpose

Step 268 is a docs-only / controlled human wording review planning-only step
for the Step 263 through Step 267 `readme.txt` public-documentation work.

Step 267 concluded:

```text
Post-correction Source-level Pass
```

Step 268 does not perform the human wording review. It defines a safe,
repository-text-only review plan so a human reviewer can decide whether the
public-facing readme wording is clear and non-misleading.

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
- `docs/maturation/step265-openai-constant-based-public-setup-and-legacy-transitional-fallback-readme-only-wording-correction-plan.md`
- `docs/maturation/step266-openai-constant-based-public-setup-and-legacy-transitional-fallback-readme-only-narrow-wording-correction-implementation-results.md`
- `docs/maturation/step267-openai-constant-based-public-setup-and-legacy-transitional-fallback-readme-only-post-correction-source-level-verification-results.md`

## Read-only Source Inspection Summary

Reviewed files:

- `readme.txt`
- `includes/class-settings.php`
- `includes/class-report-builder.php`
- `includes/class-openai-client.php`

Reviewed readme sections:

- `= Credential Storage and Payload Review =`
- `= Support and Debug Evidence =`

Review-planning findings:

- the final readme wording for human review is in `= Credential Storage and
  Payload Review =`;
- the Step 263 plus Step 266 readme diff is limited to the OpenAI
  constant-based setup, provider/runtime limitation, and existing fallback
  disclosure wording in that section;
- Settings and Report Builder source wording remains category-level aligned
  with constant-first OpenAI configuration, legacy / transitional fallback
  compatibility, hidden values, and provider/request limitation boundaries;
- the human review must stay text-only and must not become runtime,
  credential, browser, provider, or database verification.

No actual credential, API key value, constant value, Settings option value,
token, placeholder, serialized option data, request body, response body,
payload JSON, or generated report body was inspected or recorded.

## Controlled Human Review Scope

The human reviewer may use only local repository text and diff evidence.

Allowed reviewer actions:

- read `readme.txt` in an editor;
- read `git diff -- readme.txt`;
- check the result of `git diff --check`;
- check `git status --short --untracked-files=all`;
- judge wording clarity, scope, terminology, and misleading implications;
- record status/category-level results only.

Prohibited reviewer actions:

- open WordPress admin;
- save Settings;
- operate a removal control;
- inspect actual constants, credentials, option values, or token values;
- create or share browser screenshots;
- inspect browser Network evidence;
- run OpenAI Generate;
- run GA4 Fetch;
- run OAuth;
- make external HTTP requests;
- run Plugin Check;
- search source code or databases for secret material.

## Human Review Procedure

### H0. Review Baseline and Diff Scope

Confirm:

- `readme.txt` is the only modified production-facing file;
- expected untracked docs are Step 263 through Step 268 results/planning docs
  only;
- no unexpected production PHP, JavaScript, CSS, UI, `uninstall.php`, or tools
  changes are present;
- `git diff --check` passes;
- `git diff -- readme.txt` is limited to `= Credential Storage and Payload
  Review =`.

If H0 cannot be satisfied, classify the review result as:

```text
Blocked
```

Do not continue the wording review until the baseline is safe.

### H1. Constant-based Normal-route Readability

Confirm the readme lets a public reader understand that:

- `ANALYTICS_REPORT_AI_OPENAI_API_KEY` is the preferred OpenAI configuration
  source;
- it must be defined through a configuration mechanism loaded before WordPress
  plugins;
- placement depends on hosting or deployment arrangement;
- the plugin does not display or edit the value;
- the reader is not expected to enter a new OpenAI API key through the normal
  Settings UI.

Reviewer question:

```text
Could a public reader mistake Settings fallback for the normal OpenAI setup route?
```

Expected answer for Pass:

```text
No
```

### H2. Storage Scope Readability

Confirm the readme clearly distinguishes that:

- preferred constant-based configuration is not described as a plugin-settings
  storage route;
- unresolved OpenAI storage wording applies only to existing legacy /
  transitional OpenAI API key fallback storage;
- OAuth client Settings fallback storage remains a separate unresolved
  public-release decision;
- no statement implies that the plugin securely stores or encrypts OpenAI
  credentials.

The reviewer should specifically check this target sentence:

```text
This storage posture is for MVP and developer verification, and existing legacy / transitional OpenAI API key fallback storage and OAuth client Settings fallback storage remain separate public-release decisions.
```

### H3. Existing-fallback Disclosure Readability

Confirm:

- the phrase `Existing installations` clearly limits the fallback note;
- legacy / transitional Settings fallback is described as a hidden
  compatibility state;
- it is not presented as normal setup or an ordinary alternative to
  constant-based configuration;
- visible removal control applies only to the saved Settings fallback;
- removal does not edit constant-based configuration;
- there is no instruction to create, restore, inject, inspect, or directly
  modify fallback data.

### H4. Provider / Runtime Limitation Readability

Confirm:

- source category/readiness explains usable configuration source only;
- it does not verify provider authorization;
- it does not prove OpenAI API request success;
- provider/runtime failure is clearly a separate issue;
- the readme does not include request procedures, provider diagnostics, or
  success guarantees.

### H5. Support-safe Wording Continuity

Confirm the `= Support and Debug Evidence =` section:

- remains unchanged by the Step 263 through Step 266 work;
- does not invite sharing credentials, API keys, tokens, Authorization headers,
  or plugin option values;
- does not invite sharing raw payloads, responses, requests, generated report
  text, analytics identifiers, screenshots, or Network evidence;
- keeps status-level evidence guidance understandable without exposing values.

### H6. Public-facing Tone and Release-boundary Review

Confirm:

- no `developer-only` wording is used as a technical access-control claim;
- no public-release approval claim is made;
- no WordPress.org policy-compliance claim is made;
- no encryption or security guarantee is made;
- no actual provider verification claim is made;
- no host-specific configuration tutorial is presented as universal guidance;
- no PHP `define()` snippet, fake key, placeholder key, API key format, or
  copy-paste secret assignment exists.

## Human Review Result Classifications

Use exactly one classification.

```text
Human wording review Pass
```

Use this when H0 through H6 are clear and aligned. The normal constant-based
route, fallback-only storage scope, conditional fallback disclosure,
provider/runtime limitation, and support-safe boundary are understandable
without misleading implications.

```text
Needs narrow wording correction
```

Use this when the review reveals a sentence or short wording range that could
reasonably mislead a public reader about configuration route, plugin-settings
storage, fallback status, removal scope, provider success, evidence sharing, or
public-release status.

```text
Blocked
```

Use this when the review baseline is not safe, expected readme/diff sources are
unavailable, or unexpected changed production-facing files make the review
scope unreliable.

## Human Result Template

The reviewer should record only value-free, status/category-level information.

```text
Review date:
Reviewer:
Result classification: Human wording review Pass / Needs narrow wording correction / Blocked

H0 baseline and diff scope: Pass / Needs narrow wording correction / Blocked
H1 constant-based normal route: Pass / Needs narrow wording correction / Blocked
H2 storage scope: Pass / Needs narrow wording correction / Blocked
H3 existing fallback disclosure: Pass / Needs narrow wording correction / Blocked
H4 provider/runtime limitation: Pass / Needs narrow wording correction / Blocked
H5 support-safe wording: Pass / Needs narrow wording correction / Blocked
H6 public-facing tone and release boundary: Pass / Needs narrow wording correction / Blocked

Observed wording concern, if any:
- category-level description only
- do not include credentials, values, screenshots, Network evidence, raw payloads, raw responses, or generated report text

Readme changed during review: No
Runtime/provider/browser action performed: No
WordPress.org release readiness: Hold
```

Do not paste actual credential values, option values, constants, request or
response bodies, payload JSON, generated report bodies, screenshots, browser
Network evidence, cookies, sessions, nonces, hostnames, analytics values, or
provider evidence into the result template.

## Explicit Non-goals

Step 268 does not:

- perform the human wording review;
- modify `readme.txt`;
- modify production behavior;
- modify Settings or Report Builder UI;
- modify OpenAI credential resolver priority;
- modify Settings fallback storage behavior;
- modify uninstall behavior;
- decide migration, multisite, or storage disposition;
- add credential examples;
- verify actual API key validity;
- verify actual constant value or preservation;
- verify actual Settings fallback option contents;
- verify provider authorization;
- verify OpenAI request or response success;
- verify real external communication;
- run Plugin Check;
- approve public release;
- establish WordPress.org policy compliance.

## Security and Evidence Boundary

Step 268 did not inspect or record:

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

Step 268 did not perform:

- browser admin smoke;
- Settings save;
- `clear_openai_api_key` operation;
- WP-CLI state mutation;
- `wp option get`;
- raw SQL / database dump;
- option / constant / credential value inspection;
- OpenAI Generate;
- GA4 Fetch;
- OAuth;
- external HTTP;
- provider communication;
- Plugin Check;
- screenshots;
- browser Network evidence.

## Planning Conclusion

```text
Step 268 planning conclusion: Controlled human wording review plan completed
Human review executed: No
readme.txt changes: No
production code changes: No
WordPress.org release readiness: Hold
```

## Recommended Next Step

```text
Step 269 candidate — OpenAI constant-based public setup and legacy/transitional fallback readme-only controlled human wording review results
```

Step 269 is not started in this document.
