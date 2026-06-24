# Step 277: OpenAI Storage, Privacy, and Public-documentation Multisite Support-boundary Narrow Wording-correction Plan

## Step Objective and Planning Limits

Step 277 is a docs-only / planning-only step for the single public-wording
omission identified by Step 276.

The objective is to plan a minimal correction that makes the selected initial
single-site support boundary and multisite/network lifecycle exclusion
discoverable to readers of the public plugin documentation.

This step does not modify `readme.txt`, admin wording, production source,
storage, uninstall behavior, multisite behavior, or any runtime environment.
It does not determine technical multisite compatibility, legal compliance,
WordPress.org policy compliance, or public-release approval.

WordPress.org public release readiness remains:

```text
Hold
```

## Working-tree Baseline Classification

The following commands were run before this Step 277 document was added:

```text
git status --short --untracked-files=all
git diff --name-only
git diff --check
```

All three commands returned no output.

Baseline classification:

```text
Clean working tree
```

Step 276 was present in the baseline. No uncommitted production-facing,
public-documentation, existing maturation-document, environment, or tool
changes were present.

## Triggering Step 276 Finding

Step 276 found:

```text
Current readme.txt and reviewed static admin wording do not contradict the
selected initial public-release multisite policy.

However, the adopted initial single-site support boundary and the exclusion
of multisite, network lifecycle, and cross-site cleanup are not currently
discoverable through those public-facing surfaces.
```

The finding is classified as:

```text
Omission / discoverability gap
Needs narrow wording correction plan
```

No material source-behavior contradiction was identified. The finding does
not mean that multisite is broken, cannot work, necessarily fails, or is
automatically rejected.

## Existing Policy and Non-claim Boundary

The Step 273 policy remains unchanged:

```text
Initial public-release support boundary:
single-site only

Outside initial public support:
- WordPress multisite
- network activation
- network deactivation
- network lifecycle behavior
- network uninstall
- cross-site Settings behavior
- cross-site OAuth token behavior
- cross-site storage
- cross-site fallback cleanup
- per-site iteration
- network-option cleanup
```

This is a public-support and release-policy boundary. It is not a runtime
compatibility verdict.

The future correction must not state or imply:

- multisite is broken;
- multisite cannot work;
- every multisite installation fails;
- network activation is automatically rejected;
- network uninstall is verified, complete, or guaranteed;
- all network data is deleted;
- WordPress core supplies missing plugin lifecycle handling;
- source behavior has changed;
- storage architecture has been redesigned;
- public release is authorized;
- WordPress.org has approved the plugin.

## Current Public-surface Inventory

### Current Readme Structure

The reviewed `readme.txt` structure is:

```text
Plugin header and short description
Description
External Services
  Google Analytics Data API
  OpenAI API
  Credential Storage and Payload Review
  Support and Debug Evidence
Changelog
```

The Description section currently contains:

- a concise feature/workflow summary;
- an MVP development-and-verification statement;
- no explicit single-site or multisite support-scope statement.

No existing compatibility, requirements, FAQ, installation, or support-scope
subsection provides a proportionate location for the missing boundary.

### Current Static Admin Wording

Settings and Report Builder currently provide:

- credential-source and readiness categories;
- OpenAI constant-first and fallback wording;
- storage and value-hidden wording;
- OAuth lifecycle wording;
- structured preview and external-transmission wording;
- generated-report handling wording;
- support/debug redaction wording.

The support-boundary omission is not tied to an individual Settings or Report
Builder action. Adding multisite wording to those task-focused screens would
duplicate general product-scope information without improving the relevant
workflow.

Inventory conclusion:

```text
readme.txt is the primary and sufficient public-facing correction surface.
No distinct source-supported user-flow reason requires matching static admin
wording in this narrow correction.
```

## Alternatives Considered

### Option A: Readme-only Correction

Add one concise support-boundary statement or subsection to `readme.txt`.

Advantages:

- directly addresses the public-facing discoverability gap;
- places the support scope before detailed provider and credential
  disclosures;
- keeps the correction independent from admin task flows;
- produces the smallest public-facing diff;
- avoids duplication across Settings and Report Builder;
- requires no production source change.

Limitation:

- the statement is available through public documentation rather than being
  repeated inside every admin screen.

Disposition:

```text
Preferred
```

### Option B: Readme Plus Static Admin Wording

Add the readme statement and a matching brief admin help statement.

Potential benefit:

- repeats the support scope for administrators already using the plugin.

Why it is not selected:

- Step 276 identified a public-documentation discoverability gap rather than
  an action-specific admin misunderstanding;
- no Settings or Report Builder workflow depends on multisite guidance;
- repeating the statement would widen production PHP and translation scope
  without a distinct source-supported need;
- the additional wording could distract from credential, external-service,
  and report-generation guidance.

Disposition:

```text
Not selected for the narrow correction
```

### Option C: Static Admin Wording Only

Add only admin help wording and leave `readme.txt` unchanged.

Why it is not selected:

- it would not resolve the public-documentation discoverability gap;
- support scope should be available before installation and configuration;
- it would require production PHP changes while leaving the primary public
  surface incomplete.

Disposition:

```text
Not selected
```

## Preferred Correction Approach

```text
Preferred correction approach:
Option A — readme.txt only
```

Reason:

The Step 276 finding concerns public-facing support-boundary discoverability.
The current readme has an early Description section where a short scope
statement can be found before external-service and credential details.
Static admin wording should remain unchanged because no separate
action-specific need was identified.

## Candidate Placement Decision

### Placement Options Reviewed

| Candidate Location | Discoverability | Scope Fit | Duplication / Risk | Decision |
| --- | --- | --- | --- | --- |
| Existing compatibility, requirements, support, or FAQ-adjacent section | Not available in the current readme structure. | Would be suitable if present. | Creating a large new section would exceed the narrow need. | Not available |
| Installation or usage-scope section | No installation section exists. | Could communicate scope, but creating installation material risks expanding into unsupported setup guidance. | Could invite host-specific or network-activation instructions. | Not selected |
| Credential Storage and Payload Review | Existing section is available. | Multisite is a product-support boundary, not only a credential-storage topic. | Would bury general scope inside dense storage/privacy wording. | Not selected |
| Support and Debug Evidence | Existing section is available. | This section governs evidence sharing, not deployment support. | Could confuse support scope with redaction procedure. | Not selected |
| Small new subsection in Description | Immediately visible after the feature/MVP summary and before External Services. | Matches a general product-support boundary. | Minimal duplication and no credential/provider setup material. | Selected |

### Selected Placement

Add a small first-level readme subsection:

```text
= Supported Site Scope =
```

Placement:

```text
Inside the Description section, immediately after the current MVP
development-and-verification sentence and before the External Services
section.
```

Rationale:

- readers encounter the support boundary before provider, credential, and
  payload details;
- the statement remains product-scope information rather than installation
  instructions;
- the placement is concise and proportionate;
- the correction does not duplicate OpenAI storage, privacy, or support/debug
  wording;
- the planned diff is limited to one subsection and a short paragraph.

## Candidate Wording Boundary

The future implementation may use wording in this direction:

```text
The initial supported scope is single-site WordPress installations.
WordPress multisite, network activation and deactivation, network uninstall,
and cross-site storage or cleanup behavior are outside the initial supported
scope. This is a support-scope boundary and does not state that the plugin
cannot run on a multisite installation.
```

This text is a planning candidate, not an implemented change.

The implementation may refine sentence flow for natural public English, but
must preserve these meanings:

- the initial supported scope is single-site;
- WordPress multisite and network lifecycle behavior are outside the initial
  supported scope;
- cross-site storage and cleanup are outside the initial supported scope;
- the statement is a support boundary, not a technical-failure claim.

The implementation must not add:

- PHP snippets;
- API key or credential examples;
- credential configuration instructions;
- network activation instructions;
- fallback creation or restoration instructions;
- direct option-editing instructions;
- storage-model redesign claims;
- provider authorization or runtime-success claims;
- privacy-law, legal-compliance, or WordPress.org approval claims.

## Preservation Matrix

| Area | Preserved Current Position | May Step 277 Alter It? | Wording-correction Boundary | Required Escalation if Contradiction Is Found |
| --- | --- | --- | --- | --- |
| OpenAI constant-based public route | Constant-based configuration remains the preferred public route. | No | Future correction must not mention or alter OpenAI setup instructions. | Needs separate source-boundary review or storage-model decision, depending on the contradiction |
| Legacy / transitional fallback compatibility wording | Existing fallback remains compatibility-only and not a normal setup route. | No | Do not edit fallback disclosure or add fallback-entry guidance. | Needs narrow wording correction plan if wording-only; Needs separate source-boundary review if behavior differs |
| Fallback removal and non-migration wording | Removal remains explicit and slot-limited; no automatic migration/deletion; constants are unchanged. | No | Do not edit removal, migration, update, or constant behavior wording. | Needs separate source-boundary review |
| Storage and residual-access wording | Current site-level option categories and residual database/backup/server/code access risk remain disclosed without security certification. | No | Do not alter storage architecture or residual-risk wording. | Needs separate storage-model decision |
| Local uninstall and provider-side non-action wording | Current uninstall remains local plugin-data cleanup and does not mean provider revoke. | No | Do not change cleanup or provider-action claims. | Needs separate source-boundary review |
| AI payload / external-transmission wording | Existing action-triggered, category-level GA4/OpenAI disclosure remains unchanged. | No | Do not edit payload, provider, trigger, or transient wording. | Needs narrow wording correction plan or separate privacy / legal / policy review |
| Generated report handling wording | Generated text remains a user-reviewed draft not persisted by the reviewed plugin flow. | No | Do not edit persistence, editing, copying, or provider-handling wording. | Needs separate source-boundary review or privacy / legal / policy review |
| Support/debug redaction wording | Support remains value-hidden and status/category-level. | No | Do not change evidence-sharing rules. | Needs narrow wording correction plan |
| Initial multisite support-boundary wording | Initial supported scope is single-site; multisite/network lifecycle and cross-site cleanup are outside initial public support. | Yes, wording only | Add one concise readme subsection without changing runtime behavior or making technical-failure claims. | Needs separate source-boundary review if later source evidence contradicts the recorded policy |
| WordPress.org Hold wording | Public release readiness remains `Hold`. | No | Do not state or imply approval, compliance, or release readiness. | Deferred / separate release gate |

Only the initial multisite support-boundary wording is approved as the subject
of the future narrow correction.

## Planned Implementation Scope and Explicit Non-scope

### Expected Step 278 Implementation Scope

- modify `readme.txt` only as the public-facing implementation surface;
- add one concise `Supported Site Scope` subsection inside Description;
- add one short support-boundary paragraph;
- preserve all existing OpenAI configuration, fallback, storage, uninstall,
  payload, generated-report, and support/debug wording;
- add a Step 278 implementation-results document if explicitly requested by
  that step;
- make no runtime or source-behavior change.

No Settings or Report Builder static wording change is planned.

### Explicit Non-scope

The future narrow wording implementation must not include:

- production PHP, JavaScript, or CSS changes;
- Settings or Report Builder UI changes;
- multisite implementation;
- network activation, deactivation, or lifecycle implementation;
- network uninstall implementation;
- per-site iteration or network-option cleanup;
- source-level multisite verification;
- controlled multisite runtime verification;
- credential-storage redesign;
- migration or cleanup changes;
- OpenAI source-order or fallback lifecycle changes;
- privacy-policy or legal wording revision;
- external-transmission or support-redaction policy changes;
- WordPress.org policy review;
- Plugin Check;
- public-release action.

## Planned Verification Method

The future wording implementation should be verified through documentation
and source-scope checks only.

### Baseline Gate

Before modification:

```text
git status --short --untracked-files=all
git diff --name-only
git diff --check
```

The implementation step should classify the baseline before editing and
account for any existing unrelated changes without modifying them.

### Targeted Diff Review

Verify:

- the public-facing change is limited to `readme.txt`;
- any new results doc is the only additional file if one is requested;
- the new subsection is under Description and before External Services;
- the change is one concise support-boundary addition;
- no existing public wording is removed or rewritten.

Suggested checks:

```text
git diff -- readme.txt
git diff --name-only
git diff --stat
git diff --check
```

### Wording Review

Confirm that the implemented text:

- states the initial single-site support boundary;
- excludes multisite/network lifecycle and cross-site cleanup from the
  initial supported scope;
- identifies the statement as a support boundary rather than a technical
  incompatibility verdict;
- does not claim failure, rejection, safety, completeness, provider behavior,
  privacy/legal compliance, WordPress.org approval, or release readiness;
- contains no credential, secret, API, fallback-entry, host-specific, or
  network-activation instructions.

### Preservation Review

Confirm that unrelated wording remains unchanged for:

- OpenAI constant-based configuration;
- legacy / transitional fallback;
- fallback clear and non-migration behavior;
- storage and residual access;
- uninstall and provider-side non-action;
- AI payload and external transmission;
- generated report handling;
- support/debug redaction;
- WordPress.org `Hold`.

### Excluded Verification

Do not use:

- runtime or browser evidence;
- multisite setup or network lifecycle execution;
- provider communication;
- screenshots or browser Network evidence;
- stored values, credentials, option data, or database dumps;
- OpenAI Generate, GA4 Fetch, or OAuth;
- Plugin Check;
- legal or policy-compliance assessment.

### Verification Conclusion Vocabulary

Use only:

- `Source/documentation wording alignment observed`
- `Needs follow-up wording alignment`
- `Deferred / separate release gate`
- `Blocked`

Do not use `Pass`, `safe`, `secure`, `compliant`, `approved`, or
`release-ready`.

## Public Release Implication

```text
WordPress.org public release readiness remains Hold.
```

Step 277 plans a minimal public-wording correction for the already selected
initial single-site support boundary.

It does not:

- authorize public release;
- establish multisite runtime support or incompatibility;
- resolve OAuth lifecycle work;
- redesign credential storage;
- determine legal, privacy-law, or WordPress.org policy compliance;
- replace final packaging, isolated Plugin Check, or release validation.

## Result Classification

```text
Multisite support-boundary narrow wording-correction plan completed

Preferred correction approach:
Option A — readme.txt only

Planned public placement:
Description / Supported Site Scope, before External Services

WordPress.org public release readiness:
Hold
```

## Recommended Next Step

```text
Step 278 candidate —
Initial single-site support-boundary narrow public-wording implementation
```

Step 278 should be limited to the approved `readme.txt` wording correction and
an implementation-results document if requested. It must not change source
behavior, Settings UI, Report Builder UI, storage, uninstall, multisite
implementation, or the WordPress.org `Hold` status.
