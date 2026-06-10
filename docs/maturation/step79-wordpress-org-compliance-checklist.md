# Step 79: WordPress.org Compliance Checklist

## Purpose

This document creates the Step 79 WordPress.org compliance checklist for
Analytics Report AI.

Step 78 documented the data minimization and privacy review. This checklist
turns the remaining privacy, credential, disclosure, security, packaging, and
repository-hygiene concerns into review criteria for later steps.

This is not a release-readiness decision. It does not change `readme.txt`,
plugin headers, production code, runtime behavior, assets, packaging scripts,
or release metadata.

WordPress.org release remains `Hold`.

## Scope

In scope:

- `readme.txt`.
- Plugin headers.
- Stable tag and version alignment.
- License and GPL compatibility.
- External services disclosure.
- Privacy and data handling disclosure.
- Credential storage disclosure.
- Remote call disclosure.
- No hidden remote calls.
- Admin security posture.
- Uninstall behavior.
- Internationalization and text domain.
- Assets, screenshots, banners, and icons.
- Plugin Check and PHPCS posture.
- Distribution package contents.
- No bundled secrets.
- Support and debug redaction guidance.

Out of scope:

- Production code changes.
- `readme.txt` changes.
- Version bump.
- Stable tag change.
- Plugin header change.
- Asset changes.
- Release package creation.
- SVN commit.
- WordPress.org submission.
- GA4 Fetch execution.
- OpenAI Generate execution.
- Google OAuth execution.
- External API execution.
- Release-readiness decision.

## Review Preconditions

| Precondition | Status | Notes |
|---|---|---|
| Controlled MVP E2E has passed. | Known pass | Prior maturation docs record controlled GA4 Fetch and OpenAI Generate happy-path completion. This step did not rerun them. |
| Error-handling QA Phase 1 has passed with deferred items. | Known pass | Step 76 recorded Phase 1 local validation / settings validation / nonce / capability coverage with external API items deferred. |
| External API error-path QA checklist exists. | Known pass | Step 77 created the checklist. Execution has not started. |
| External API error-path QA execution is not started. | Hold | GA4 and OpenAI failure-path execution remains a later step. |
| Data minimization / privacy review is documented. | Known pass | Step 78 documented the review and remaining release decisions. |
| WordPress.org release remains `Hold`. | Hold | Release readiness has not started. |
| No real credentials or option values should be inspected. | Known pass | This checklist step did not inspect plugin settings option values. |
| No external API calls should be made in this checklist step. | Known pass | GA4 Fetch, OpenAI Generate, and Google OAuth were not executed. |

## WordPress.org Compliance Checklist

### 4.1 readme.txt / Directory Listing

| ID | Compliance Area | Check / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| README-001 | readme.txt | Does `readme.txt` exist in the plugin root? | Known pass | Confirm during release package review. | Read-only inspection found `readme.txt`. |
| README-002 | Plugin name / short description | Does the readme title and short description match the plugin purpose without overpromising? | Needs review | Review final wording before release readiness. | Current wording describes AI-assisted Japanese report drafts from GA4 data. |
| README-003 | Contributors | Is the Contributors field present and correct? | Needs review | Confirm intended WordPress.org contributor slug before release readiness. | Current readme has a contributor value. |
| README-004 | Tags | Are tags relevant and within directory expectations? | Needs review | Confirm tag count and relevance. | Current tags are short and relevant, but should be reviewed before submission. |
| README-005 | Requires at least | Is the minimum WordPress version accurate? | Needs review | Verify against supported runtime before release readiness. | Current value is present. |
| README-006 | Tested up to | Is the tested WordPress version accurate for the final release? | Needs review | Run final compatibility checks and update only in a later explicit step if needed. | This checklist does not change metadata. |
| README-007 | Requires PHP | Is the PHP requirement accurate? | Needs review | Confirm against syntax and supported APIs before release readiness. | Current plugin header and readme include a PHP requirement. |
| README-008 | Stable tag | Does the Stable tag align with the intended release package? | Needs review | Confirm behavior and alignment with plugin version before packaging. | Current stable tag appears aligned with current version, but release handling remains later. |
| README-009 | License | Is the license field GPL-compatible and consistent with plugin header? | Needs review | Confirm exact license wording before release readiness. | Readme and plugin header use GPL-compatible wording in different formats. |
| README-010 | License URI | Is the license URI present and consistent? | Needs review | Confirm final license URI and license file policy. | URI is present in readme and plugin header. |
| README-011 | Description | Does Description explain the workflow clearly? | Needs review | Confirm no misleading claims and no unsupported release claims. | Current description says the MVP is intended for development and verification. |
| README-012 | Installation | Does the readme include installation instructions if needed for directory users? | Needs review | Decide whether to add an Installation section before release readiness. | No change is made in this step. |
| README-013 | FAQ | Does the readme need an FAQ for credentials, OAuth, external services, or privacy? | Needs decision | Decide whether FAQ is required for public support. | Credential and external-service questions are likely candidates. |
| README-014 | Screenshots | Does the readme declare screenshots only if directory assets are prepared? | Needs review | Confirm screenshot list and asset privacy before release. | Screenshots are not changed in this step. |
| README-015 | Changelog | Is the changelog accurate for the current release scope? | Needs review | Confirm final changelog before readiness. | Changelog currently summarizes the MVP. |
| README-016 | External services section | Does the readme disclose GA4 and OpenAI usage, triggers, data categories, provider links, and user responsibilities? | Needs review | Finalize release-grade disclosure after privacy and credential decisions. | Current readme substantially covers this, but Step 78 left disclosure finalization open. |
| README-017 | Privacy / data handling wording | Does the readme clearly describe persistent storage, transient storage, generated report handling, and sensitive payload categories? | Needs review | Align final wording with Step 78 decisions. | Current wording covers key categories but is not release-finalized. |
| README-018 | Credential setup wording | Does credential setup avoid presenting developer-only token entry as public-ready? | Hold | Decide credential and OAuth strategy first. | Manual Google token entry remains a release blocker candidate. |
| README-019 | Support / troubleshooting wording | Does readme support guidance avoid asking for secrets, raw responses, payload bodies, or generated report bodies? | Needs implementation | Add or update release-facing support guidance in a later explicit step if required. | This checklist records the need only. |
| README-020 | No keyword stuffing | Are tags and description free of keyword stuffing? | Needs review | Confirm in final readme review. | No obvious stuffing was targeted in this docs-only step. |
| README-021 | No misleading claims | Does the readme avoid claims that imply guaranteed accuracy, legal compliance, or automated publication safety? | Needs review | Confirm before release readiness. | AI output remains described as a draft requiring review. |
| README-022 | No legal compliance guarantee claims | Does the readme avoid guaranteeing privacy, compliance, or regulatory outcomes? | Needs review | Confirm before release readiness. | Compliance wording should stay factual and limited. |
| README-023 | Stable tag behavior | Is the team ready to manage Stable tag behavior correctly for WordPress.org? | Needs decision | Decide release branching / tag process before submission. | Release package and SVN steps are out of scope here. |

### 4.2 Plugin Headers / Versioning

| ID | Compliance Area | Check / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| HEADER-001 | Plugin Name | Is Plugin Name present and consistent with readme? | Known pass | Reconfirm before release package review. | Header uses Analytics Report AI. |
| HEADER-002 | Description | Is Description present and consistent with readme? | Needs review | Final wording should match release scope. | Current description matches the MVP purpose. |
| HEADER-003 | Version | Is Version present and aligned with the release plan? | Needs review | Confirm version before package creation. | Current version is unchanged in this step. |
| HEADER-004 | Author | Is Author present and intended for public release? | Needs review | Confirm author metadata before release readiness. | Present in header. |
| HEADER-005 | Text Domain | Is Text Domain present and consistent? | Known pass | Preserve text domain consistency. | Header uses `analytics-report-ai`. |
| HEADER-006 | Requires at least | Is the header requirement aligned with readme and tested support? | Needs review | Confirm before release readiness. | Present in header. |
| HEADER-007 | Requires PHP | Is the header PHP requirement aligned with readme and tooling? | Needs review | Confirm final PHP support range. | Present in header. |
| HEADER-008 | License | Is the header license GPL-compatible? | Needs review | Confirm exact license representation and license file policy. | Header uses GPL-compatible SPDX-style wording. |
| HEADER-009 | License URI | Is the License URI present and correct? | Needs review | Confirm before release readiness. | Present in header. |
| HEADER-010 | Stable tag / Version alignment | Does `readme.txt` Stable tag match plugin Version for the intended release? | Needs review | Recheck immediately before packaging. | Current source appears aligned, but this is not a release decision. |
| HEADER-011 | Version bump policy | Is there a documented policy for version bumps, changelog, package name, and release tag? | Needs decision | Decide before release packaging. | Do not bump in this step. |
| HEADER-012 | Release hold state | Is release hold explicitly documented? | Known pass | Preserve until blocker candidates are closed. | This checklist keeps WordPress.org release on Hold. |

### 4.3 License / Bundled Assets

| ID | Compliance Area | Check / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| LICENSE-001 | GPL compatibility | Is the plugin license GPL-compatible? | Needs review | Confirm legal/license wording before release readiness. | Current metadata indicates GPL-compatible licensing. |
| LICENSE-002 | License file presence | Is a standalone license file included or intentionally omitted? | Needs decision | Decide whether to add a license file before packaging. | Read-only inspection did not find a root license file. |
| LICENSE-003 | Bundled assets license | Are bundled CSS/JS/assets authored or licensed compatibly? | Needs review | Confirm asset ownership/license status before release. | Current asset files are project admin CSS and JS. |
| LICENSE-004 | Third-party libraries | Are third-party libraries included only when compatible and necessary? | Needs review | Review Composer dev tooling and package exclusions. | Current `vendor/` appears tooling-related and should not be shipped by default. |
| LICENSE-005 | Proprietary code | Is there any bundled proprietary code? | Needs review | Confirm during package hygiene review. | No proprietary bundle was identified in this limited checklist inspection. |
| LICENSE-006 | External service SDK secrets | Are no service SDK secrets, private keys, or credentials bundled? | Needs review | Run release-package secret scan before readiness. | This step did not inspect option values and did not find bundled secret files by path review. |
| LICENSE-007 | Duplicated WordPress libraries | Are default WordPress libraries not duplicated unnecessarily? | Needs review | Confirm during package review. | No duplicated WP library paths were identified in this limited inspection. |
| LICENSE-008 | Composer dependencies | Are Composer dependencies dev-only or intentionally runtime-bundled? | Needs decision | Decide package strategy if runtime dependencies are introduced. | Current Composer file declares dev tooling; `.distignore` excludes Composer metadata and `vendor/`. |
| LICENSE-009 | Package dependencies | Are npm/package dependencies absent or intentionally managed? | Known pass | Reconfirm before release. | No `package.json` path was found in root-level inspection. |

### 4.4 External Services Disclosure

| ID | Compliance Area | Check / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| EXT-001 | Google Analytics Data API disclosure | Does the readme disclose Google Analytics Data API usage? | Needs review | Finalize wording before readiness. | Current readme names the service and trigger. |
| EXT-002 | OpenAI API disclosure | Does the readme disclose OpenAI API usage? | Needs review | Finalize wording before readiness. | Current readme names the service and trigger. |
| EXT-003 | Provider names | Are provider names clearly identified? | Known pass | Preserve and verify final wording. | Google and OpenAI are identified. |
| EXT-004 | Service purpose | Is the purpose for each external service explained? | Needs review | Confirm release-grade clarity. | Current copy explains analytics fetch and report generation. |
| EXT-005 | Trigger action | Does disclosure say which admin action triggers each external request? | Known pass | Preserve staged action wording. | Fetch and Generate triggers are documented. |
| EXT-006 | Endpoint/provider category | Are endpoint or provider categories disclosed appropriately? | Needs review | Confirm final readme and admin wording. | Current readme includes service URLs. |
| EXT-007 | Data categories sent | Are categories sent to each provider disclosed without exposing real data? | Needs review | Align with final payload decisions. | Step 78 identified remaining payload-category decisions. |
| EXT-008 | Data categories received | Are categories received from each provider disclosed? | Needs review | Confirm before release readiness. | Current readme lists GA4 response categories and generated report draft. |
| EXT-009 | Credential usage | Does disclosure explain credentials are used in request headers without exposing values? | Needs review | Finalize wording with credential strategy. | Current readme and admin copy describe credential categories. |
| EXT-010 | Provider terms/privacy links | Are provider terms and privacy links present and accurate? | Needs review | Verify links before release readiness. | Current readme includes provider policy links. |
| EXT-011 | No external calls on admin screen load | Is it documented and preserved that screen load does not call GA4/OpenAI? | Known pass | Preserve and browser-check as needed. | Current readme states screen viewing does not by itself send data. |
| EXT-012 | No external calls on Settings save | Is it documented or clear that Settings save does not call GA4/OpenAI? | Needs review | Confirm and disclose if needed. | Step 78 categorized Settings save as no GA4/OpenAI external request expected. |
| EXT-013 | Review-before-send flow | Does disclosure explain Payload Preview before OpenAI generation? | Known pass | Preserve and verify final wording. | Current admin and readme copy cover this. |
| EXT-014 | Cost/quota/account responsibility | Does OpenAI/GA4 disclosure mention usage implications where relevant? | Needs review | Confirm final wording. | Current readme mentions OpenAI credits/quota. |
| EXT-015 | Disclosure consistency | Are readme, Settings, Report Builder, and support docs consistent? | Needs review | Reconcile wording after credential/privacy decisions. | Consistency must be checked in a later results step. |

### 4.5 Privacy / Data Handling

| ID | Compliance Area | Check / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| PRIV-001 | Persistent storage | Is persistent plugin storage fully documented? | Needs review | Finalize settings/credential disclosure. | Persistent plugin settings include credential-bearing values. |
| PRIV-002 | Temporary storage | Is temporary AI payload transient storage documented? | Needs review | Finalize transient visibility and expiration disclosure. | Current readme and admin copy describe temporary user-scoped storage. |
| PRIV-003 | Credential-bearing settings | Is credential storage risk disclosed and acceptable for public distribution? | Hold | Decide storage strategy before release readiness. | Step 78 keeps this as a high-risk release decision. |
| PRIV-004 | AI payload transient | Is payload transient content minimized and release-approved? | Needs decision | Decide accepted payload categories and disclosure wording. | Payload is selected and row-limited but sensitive. |
| PRIV-005 | Generated report non-storage | Is generated report non-storage behavior disclosed and verified? | Needs review | Preserve and confirm in release review. | Current MVP displays generated draft without plugin persistence. |
| PRIV-006 | Copy action non-storage | Is copy behavior described without implying plugin persistence? | Needs review | Add support guidance if needed. | Copy action is user-controlled and not plugin persistence. |
| PRIV-007 | Analytics data sent to OpenAI | Are all analytics categories sent to OpenAI release-approved? | Needs decision | Resolve Step 78 minimization decisions. | Top pages, traffic sources, city rows, and paths remain sensitive categories. |
| PRIV-008 | Page path sensitivity | Is page path sensitivity disclosed and supported by redaction guidance? | Needs review | Finalize wording and support policy. | Admin copy already warns that page paths can be sensitive. |
| PRIV-009 | Source sensitivity | Is traffic source sensitivity disclosed and supported by redaction guidance? | Needs decision | Decide payload retention and disclosure level. | Traffic sources may reveal business relationships or campaigns. |
| PRIV-010 | City sensitivity | Is city-level data accepted for release or minimized further? | Needs decision | Decide whether to keep, aggregate, or remove city rows. | Step 78 leaves this unresolved. |
| PRIV-011 | Hostname sensitivity | Is hostname/domain exposure handled in disclosure and screenshots? | Needs review | Define redaction rules. | Hostnames may identify sites or clients. |
| PRIV-012 | Support/debug redaction | Are support and debug rules consolidated? | Needs implementation | Create release-facing guidance before readiness. | Avoid secrets, identifiers, payloads, raw responses, and generated report bodies. |
| PRIV-013 | Screenshot/evidence redaction | Are screenshot/evidence rules documented for public support? | Needs implementation | Add clear redaction guidance before readiness. | Step 78 documents internal evidence rules. |
| PRIV-014 | Privacy policy suggestion | Should users be told to update their site privacy policy? | Needs decision | Decide before release-facing disclosure draft. | External analytics and AI processing may require site-owner disclosure. |
| PRIV-015 | User review responsibility | Is generated-report review responsibility clear? | Needs review | Preserve clear draft/review wording. | Current readme and admin copy call generated text a draft. |

### 4.6 Security Posture

| ID | Compliance Area | Check / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| SEC-001 | Capability checks | Are admin screens and actions capability-gated? | Known pass | Preserve and run final negative checks. | Source-level review found `manage_options` usage in prior steps. |
| SEC-002 | Nonce checks | Are state-changing Report Builder actions nonce-protected? | Known pass | Preserve and run final negative checks. | Step 74 identified source-level nonce checks. |
| SEC-003 | Sanitization | Are settings and report inputs sanitized/normalized? | Needs review | Run final static and behavior checks. | Prior reviews found sanitization paths; release pass still needed. |
| SEC-004 | Escaping | Are admin outputs escaped for their contexts? | Needs review | Run final escaping pass before release readiness. | Prior reviews found escaping patterns, but final audit remains. |
| SEC-005 | Safe redirects | Are redirects and URLs safe and free of sensitive values? | Needs review | Verify Settings and Report Builder flows. | No credential or payload query-string behavior was targeted in this step. |
| SEC-006 | No raw response leakage | Are GA4/OpenAI raw response bodies never displayed in admin errors? | Needs review | Execute external API error-path QA with redacted evidence. | Step 77 prepares this but execution is pending. |
| SEC-007 | No credential redisplay | Are saved credentials never redisplayed? | Known pass | Preserve in future changes. | Controlled checks and source review support this. |
| SEC-008 | No request body exposure | Are full request bodies never displayed or logged by plugin UI? | Needs review | Confirm in error-path QA and support guidance. | Do not record bodies in future evidence. |
| SEC-009 | No hidden remote calls | Are remote calls limited to disclosed user-triggered GA4/OpenAI actions? | Needs review | Reconfirm with final source/static review. | Remote-call locations were identifiable by static inspection. |
| SEC-010 | External action separation | Are GA4 Fetch and OpenAI Generate kept as separate actions? | Known pass | Preserve staged flow. | Current architecture separates fetch, preview, and generate. |
| SEC-011 | Error-path QA status | Have external API error paths passed safely? | Hold | Execute Step 77 checklist in later steps. | Not executed in this checklist step. |
| SEC-012 | Plugin Check refresh | Has Plugin Check been run for release readiness? | Needs review | Rerun before readiness in the target environment. | Earlier maturation docs mention tooling, but this step did not run Plugin Check. |
| SEC-013 | PHPCS refresh | Has PHPCS/WPCS been run for release readiness? | Needs review | Rerun before readiness. | PHPCS config exists, but this docs-only step did not run it. |

### 4.7 Uninstall / Cleanup

| ID | Compliance Area | Check / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| UNINSTALL-001 | uninstall.php or hook | Is uninstall behavior implemented through `uninstall.php` or a registered uninstall hook? | Needs implementation | Decide and implement cleanup if required before release. | Root-level inspection did not find `uninstall.php`. |
| UNINSTALL-002 | Credential cleanup policy | Should uninstall remove credential-bearing settings by default? | Hold | Decide before release readiness. | Step 78 marks this as a release decision. |
| UNINSTALL-003 | Transient cleanup policy | Should uninstall clean user-scoped payload transients if discoverable? | Needs decision | Decide before release readiness. | Transient key strategy may affect cleanup feasibility. |
| UNINSTALL-004 | User expectation | Is data removal behavior explained to users? | Needs implementation | Add release-facing wording after policy decision. | Current release policy is not finalized. |
| UNINSTALL-005 | Saved credentials | Are saved credentials handled safely during deactivation/uninstall? | Hold | Decide retention/removal and document clearly. | Credential-bearing options are high-risk. |
| UNINSTALL-006 | Cleanup documentation | Is cleanup documented in readme/support docs? | Needs implementation | Document after implementation or explicit retention decision. | No docs change is made in this step. |
| UNINSTALL-007 | Release decision | Is uninstall cleanup release-decided? | Hold | Resolve before readiness checkpoint. | This remains a blocker candidate. |

### 4.8 Internationalization / Text Domain

| ID | Compliance Area | Check / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| I18N-001 | Text Domain consistency | Is the text domain consistently `analytics-report-ai`? | Known pass | Preserve and rerun static checks. | Header and strings use the plugin text domain. |
| I18N-002 | load textdomain | Is translation loading handled appropriately for the target WordPress version and standards? | Needs review | Reassess with Plugin Check/WPCS before readiness. | Earlier docs noted possible tooling interpretation. |
| I18N-003 | PHP string readiness | Are PHP admin strings wrapped for translation? | Needs review | Run final i18n scan. | Existing code uses WordPress translation helpers broadly. |
| I18N-004 | JS string readiness | Are admin JS UI strings localized from PHP? | Known pass | Preserve localized object and fallback behavior. | Step 19 localized admin JS strings. |
| I18N-005 | readme language posture | Is readme language appropriate for WordPress.org listing and users? | Needs review | Confirm final readme language strategy. | Current readme is English. |
| I18N-006 | Japanese fixed output | Is Japanese report output disclosed as MVP behavior? | Needs review | Confirm disclosure wording before readiness. | Current description says Japanese report draft. |
| I18N-007 | Mixed-domain strings | Are there no strings using a wrong text domain? | Needs review | Run final static scan before readiness. | This step did not perform a full i18n audit. |

### 4.9 Assets / Screenshots / Directory Presentation

| ID | Compliance Area | Check / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| ASSET-001 | Plugin icon | Is a plugin icon planned, licensed, and safe? | Needs decision | Decide before WordPress.org submission. | No icon asset was reviewed or added in this step. |
| ASSET-002 | Banner | Is a banner planned, licensed, and safe? | Needs decision | Decide before WordPress.org submission. | No banner asset was reviewed or added in this step. |
| ASSET-003 | Screenshots | Are screenshots planned and mapped to readme entries? | Needs decision | Decide screenshot strategy before submission. | No screenshots are added in this step. |
| ASSET-004 | Credential-free screenshots | Do screenshots avoid credentials, property IDs, hostnames, payloads, generated report text, cookies, and sessions? | Needs implementation | Create or review screenshots only with redacted/synthetic data. | Step 78 evidence rules should apply. |
| ASSET-005 | Actual UI representation | Do screenshots reflect the real admin UI without misleading edits? | Needs review | Verify if screenshots are prepared. | Screenshot work is out of this step. |
| ASSET-006 | No private analytics data | Do assets avoid real analytics data, paths, sources, cities, and report text? | Needs implementation | Use synthetic or redacted examples only. | Required for any future assets. |
| ASSET-007 | No misleading visual claims | Do assets avoid unsupported claims about automation, compliance, accuracy, or outcomes? | Needs review | Review final assets and directory copy. | Marketing language should stay restrained. |
| ASSET-008 | Excessive marketing language | Is directory presentation factual and not overpromising? | Needs review | Review final readme/assets together. | MVP limitations must remain clear. |

### 4.10 Distribution Package / Repository Hygiene

| ID | Compliance Area | Check / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| DIST-001 | No bundled credentials | Does the release package exclude credentials, local config, and private values? | Needs review | Run package secret scan before readiness. | This step did not build a package. |
| DIST-002 | No local config | Are local `.env` or machine-specific config files excluded? | Needs review | Confirm during package review. | Root-level inspection did not target hidden files exhaustively beyond allowed static checks. |
| DIST-003 | No logs | Are logs excluded from release packages? | Needs review | Confirm package contents. | `.distignore` excludes log patterns. |
| DIST-004 | No raw test responses | Are raw GA4/OpenAI responses excluded from docs/assets/package? | Needs review | Confirm with secret/data scan before readiness. | Future QA must avoid recording raw responses. |
| DIST-005 | No generated report bodies | Are generated report bodies excluded from docs/assets/package? | Needs review | Confirm before package review. | Current maturity docs use status-level evidence. |
| DIST-006 | No option dumps | Are option dumps excluded from docs/assets/package? | Needs review | Confirm before package review. | Do not run option value dump commands. |
| DIST-007 | No dependency bloat | Are `node_modules` and dev-only `vendor` excluded unless intentionally required? | Needs review | Confirm package contents. | `.distignore` excludes these by default; runtime dependency policy must be revisited if architecture changes. |
| DIST-008 | No build artifacts | Are build artifacts excluded from the final package? | Needs review | Build and inspect package in a later step. | `.distignore` excludes build and archive patterns. |
| DIST-009 | No development-only files | Are docs, tools, PHPCS config, Composer metadata, and internal checklists excluded as intended? | Needs review | Run dry-run package and inspect zip contents. | `.distignore` excludes docs/maturation and tooling paths. |
| DIST-010 | Runtime files included | Are `analytics-report-ai.php`, `includes/`, `assets/`, and `readme.txt` included? | Needs review | Confirm in package inspection. | Do not exclude runtime files. |
| DIST-011 | Release package review | Has a release package contents review been completed after final decisions? | Hold | Complete after compliance decisions and implementation changes. | No package is created in this step. |

### 4.11 Support / Documentation Safety

| ID | Compliance Area | Check / Question | Current Status | Required Before Release Readiness | Notes |
|---|---|---|---|---|---|
| SUPPORT-001 | No secret requests | Do support instructions avoid requesting access tokens, API keys, credential fragments, or Authorization headers? | Needs implementation | Add release-facing support safety wording. | This is not yet consolidated. |
| SUPPORT-002 | No raw response requests | Do support instructions avoid requesting raw GA4/OpenAI responses? | Needs implementation | Add redacted evidence rules before release. | Record only status-level categories. |
| SUPPORT-003 | No full payload requests | Do support instructions avoid requesting full AI payloads? | Needs implementation | Add guidance before public support. | Payloads can contain sensitive analytics categories. |
| SUPPORT-004 | No generated body requests | Do support instructions avoid requesting full generated report bodies? | Needs implementation | Add guidance before public support. | Generated text may reveal business-sensitive interpretation. |
| SUPPORT-005 | Troubleshooting categories | Does troubleshooting use safe status-level categories? | Needs implementation | Draft troubleshooting guidance after error-path QA. | Step 77 provides a structure. |
| SUPPORT-006 | Redacted screenshots | Are screenshots required to be redacted before sharing? | Needs implementation | Add support guidance. | Redact identifiers, payloads, credentials, cookies, and generated text. |
| SUPPORT-007 | Network evidence constraints | Is Network tab evidence discouraged or limited to safe metadata? | Needs implementation | Add support guidance. | Avoid headers, bodies, cookies, sessions, and sensitive URLs. |
| SUPPORT-008 | External services clarity | Do docs explain user-triggered external actions clearly? | Needs review | Reconcile readme, admin UI, and support docs. | Current readme and admin copy already cover major triggers. |

## Severity Guide

Use this guide when Step 80 records compliance review results.

| Severity | Meaning |
|---|---|
| High | Public release should not proceed before this compliance item is resolved or explicitly accepted. |
| Medium | Should be reviewed and preferably resolved before the release-readiness decision. |
| Low | Can be documented or deferred if it does not affect compliance, privacy, credentials, external calls, or core UX. |
| Known pass | Already confirmed in controlled checks or source-level review and should be preserved. |
| Out of MVP scope | Not required for MVP, but should remain documented as a non-goal. |

## Result Recording Template

Use this template in the later compliance review results step. Keep notes
redacted and status-level only.

```text
Category: readme.txt / Directory Listing
Reviewed: yes / no
Pass items: number
Fail items: number
Blocked items: number
Not tested items: number
High-risk unresolved: yes / no
Release blocker candidates: yes / no
Notes: redacted / status-level only
```

```text
Category: Plugin Headers / Versioning
Reviewed: yes / no
Pass items: number
Fail items: number
Blocked items: number
Not tested items: number
High-risk unresolved: yes / no
Release blocker candidates: yes / no
Notes: redacted / status-level only
```

```text
Category: License / Bundled Assets
Reviewed: yes / no
Pass items: number
Fail items: number
Blocked items: number
Not tested items: number
High-risk unresolved: yes / no
Release blocker candidates: yes / no
Notes: redacted / status-level only
```

```text
Category: External Services Disclosure
Reviewed: yes / no
Pass items: number
Fail items: number
Blocked items: number
Not tested items: number
High-risk unresolved: yes / no
Release blocker candidates: yes / no
Notes: redacted / status-level only
```

```text
Category: Privacy / Data Handling
Reviewed: yes / no
Pass items: number
Fail items: number
Blocked items: number
Not tested items: number
High-risk unresolved: yes / no
Release blocker candidates: yes / no
Notes: redacted / status-level only
```

```text
Category: Security Posture
Reviewed: yes / no
Pass items: number
Fail items: number
Blocked items: number
Not tested items: number
High-risk unresolved: yes / no
Release blocker candidates: yes / no
Notes: redacted / status-level only
```

```text
Category: Uninstall / Cleanup
Reviewed: yes / no
Pass items: number
Fail items: number
Blocked items: number
Not tested items: number
High-risk unresolved: yes / no
Release blocker candidates: yes / no
Notes: redacted / status-level only
```

```text
Category: Internationalization / Text Domain
Reviewed: yes / no
Pass items: number
Fail items: number
Blocked items: number
Not tested items: number
High-risk unresolved: yes / no
Release blocker candidates: yes / no
Notes: redacted / status-level only
```

```text
Category: Assets / Screenshots / Directory Presentation
Reviewed: yes / no
Pass items: number
Fail items: number
Blocked items: number
Not tested items: number
High-risk unresolved: yes / no
Release blocker candidates: yes / no
Notes: redacted / status-level only
```

```text
Category: Distribution Package / Repository Hygiene
Reviewed: yes / no
Pass items: number
Fail items: number
Blocked items: number
Not tested items: number
High-risk unresolved: yes / no
Release blocker candidates: yes / no
Notes: redacted / status-level only
```

```text
Category: Support / Documentation Safety
Reviewed: yes / no
Pass items: number
Fail items: number
Blocked items: number
Not tested items: number
High-risk unresolved: yes / no
Release blocker candidates: yes / no
Notes: redacted / status-level only
```

## Initial Release Blocker Candidates

- Credential storage / public distribution strategy unresolved.
- Manual Google Access Token / OAuth strategy unresolved.
- Token lifecycle / refresh / reconnect strategy unresolved.
- OpenAI API Key storage strategy unresolved.
- External services disclosure not release-finalized.
- Privacy / data handling wording not release-finalized.
- AI payload category minimization not release-decided.
- AI Payload Preview JSON exposure not release-decided.
- Support/debug redaction policy not consolidated.
- Uninstall credential cleanup policy unresolved.
- External API error-path QA not executed.
- Plugin Check / PHPCS refresh not yet run for release readiness.
- Release package contents not yet reviewed.
- Asset and screenshot privacy strategy not decided.
- Readme support/troubleshooting safety wording not finalized.
- WordPress.org release readiness decision not started.

## Recommended Decisions Before Release Readiness

- Decide whether current credential storage is acceptable, redesigned, or a
  release blocker.
- Decide whether Google OAuth is required, manual token entry is acceptable, or
  release remains blocked until redesign.
- Decide token lifecycle strategy, including refresh, expiry, reconnect,
  revocation, and scope expectations.
- Decide OpenAI API Key storage strategy.
- Decide final external services disclosure wording.
- Decide final privacy and data handling wording.
- Decide AI payload category acceptance.
- Decide AI Payload Preview JSON visibility for public multi-admin use.
- Decide uninstall cleanup policy for credential-bearing settings and temporary
  payload data.
- Decide support/debug redaction policy.
- Decide whether external API error-path QA must pass before readiness.
- Decide whether Plugin Check and PHPCS must be clean before readiness.
- Decide release package contents and `.distignore` policy after final runtime
  dependency decisions.
- Decide asset, screenshot, icon, and banner policy before WordPress.org
  submission.

## Recommended Next Steps

Do not proceed directly to the WordPress.org readiness checkpoint.

Recommended priority order:

1. Step 80: WordPress.org compliance review results.
2. Step 81: Credential / OAuth strategy decision checkpoint.
3. Step 82: External services / privacy disclosure draft.
4. Step 83: External API error-path QA execution plan.
5. Step 84: Plugin Check / PHPCS / package hygiene refresh.
6. Step 85: WordPress.org readiness checkpoint.

The most useful immediate next step is Step 80, because this checklist should
first be converted into status-level compliance review results before runtime
changes, readme changes, release package work, or submission planning begin.

## Release Position

```text
WordPress.org release: Hold
Reason: WordPress.org compliance checklist has been created, but compliance review results, credential/OAuth strategy decisions, external services/privacy disclosure finalization, uninstall cleanup policy, external API error-path QA, Plugin Check/PHPCS refresh, package hygiene review, and release readiness decision remain incomplete.
```

## Outcome

- WordPress.org compliance checklist: created.
- Controlled MVP E2E flow: passed.
- Error-handling QA Phase 1: passed with deferred items.
- Data minimization / privacy review: documented.
- Compliance review execution: not started.
- Release readiness decision: not started.
- WordPress.org release remains `Hold`.
- Next recommended step: Step 80 WordPress.org compliance review results.
- Production code changes: none.
- `readme.txt` changes: none.
- GA4 Fetch: not executed.
- OpenAI Generate: not executed.
- Google OAuth: not started.
- External API communication: not performed.
- Credentials, API keys, access tokens, Authorization headers, option values,
  real GA4 Property ID values, real hostnames/domains, analytics values,
  request bodies, AI payload bodies, raw responses, generated report bodies,
  nonce values, cookies, and session values were not recorded.
