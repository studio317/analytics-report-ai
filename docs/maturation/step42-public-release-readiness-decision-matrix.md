# Step 42 Public Release Readiness Decision Matrix

## 1. Overview

This step is a docs-only public release readiness decision matrix for Analytics
Report AI.

It does not decide whether to submit the plugin to WordPress.org. It organizes
Go / Hold / No-Go decision inputs, remaining risks, required evidence, and
recommended next steps.

No production PHP, JavaScript, CSS, `readme.txt`, plugin header metadata,
`phpcs.xml.dist`, `.distignore`, `.gitignore`, Composer files, dry-run script,
version, or `Stable tag` is changed in this step.

This document is not legal advice and does not assert legal compliance. External
service, privacy, credential, and publication requirements must be reviewed
before any public release decision.

## 2. Current Technical Baseline

| Area | Current status |
| --- | --- |
| PHP lint | Clean for the main plugin file and `includes/`. |
| WPCS / PHPCS | Clean: `0 errors / 0 warnings`. |
| Plugin Check | Clean against the dry-run stage directory. |
| Dry-run release package | Generated and inspected. |
| Release package exclusion | Development/tooling paths are excluded, including `vendor/`, Composer files, PHPCS config, `.distignore`, `docs/maturation/`, `tools/`, `tests/`, and `build/`. |
| External Services disclosure | Present in `readme.txt` for Google Analytics Data API and OpenAI API. |
| Admin smoke checklist | Present in Step 40. |
| Manual GA4 / OpenAI E2E checklist | Present in Step 41. |
| Credential storage policy | MVP-only database storage risk is documented. |
| Payload review flow | GA4 fetch, AI Payload Preview, and OpenAI generation are separate administrator actions. |

## 3. Decision Outcomes

| Outcome | Meaning |
| --- | --- |
| Go | WordPress.org submission preparation may continue after required evidence is reviewed and accepted. |
| Hold | Keep the plugin as a private or developer-verification MVP while specific release-readiness areas mature. |
| No-Go | Do not submit publicly until material blockers are redesigned or removed. |

These outcomes are internal decision labels. They should be applied after manual
smoke testing, manual E2E testing, credential/OAuth policy review, and final
package inspection.

## 4. Readiness Matrix

| Area | Current status | Go condition | Hold condition | No-Go condition | Recommended action | Priority |
| --- | --- | --- | --- | --- | --- | --- |
| Core MVP usefulness | MVP flow exists: settings, GA4 fetch, payload review, AI draft, edit, copy. | Step 40 and Step 41 show the workflow is understandable and useful for the intended admin audience. | Flow works but needs UX clarification or more operator guidance. | Core workflow is confusing or fails to produce useful report drafts. | Execute Step 40 and Step 41; record redacted status-level results. | P1 |
| AI report quality | Prompt is Japanese-report focused and treats output as a draft. | Generated drafts are practically useful and avoid unsupported claims in sample E2E runs. | Draft quality is promising but inconsistent or insufficiently reviewed. | Output is misleading, overconfident, or not useful as a draft. | Review multiple safe E2E outputs without storing full sensitive reports. | P1 |
| GA4 fetch reliability | GA4 client and preset reports are implemented. | Short-range whole-site, directory, and page scope checks pass with safe evidence. | Some scopes need more validation or operator guidance. | GA4 fetch is unreliable or leaks raw responses/secrets. | Run Step 41 GA4 success and error checks. | P1 |
| OpenAI generation reliability | OpenAI Responses API client is implemented and errors are normalized. | Report generation succeeds and failures are normalized without secret leakage. | Success path works but restricted-key or cost guidance needs improvement. | Generation fails broadly or leaks raw request/response data. | Run Step 41 OpenAI success and error checks. | P1 |
| Admin UX simplicity | Settings and Report Builder have staged notices and controls. | Admin smoke tests pass and testers can complete the flow without hidden assumptions. | UI is usable but needs clearer warnings or help text. | Users can accidentally trigger external transmission without understanding it. | Execute Step 40 and adjust future UX only if evidence shows friction. | P1 |
| Settings clarity | External service and credential storage notices are present. | Admins understand credential status, non-redisplay, clear controls, and MVP storage risks. | Notices exist but are too dense or need stronger release wording. | Credential behavior is unclear or secrets are displayed. | Use Step 40 Settings checks before release decision. | P1 |
| External service disclosure | `readme.txt` includes Google Analytics Data API and OpenAI API details. | Disclosure remains accurate for the packaged version and user-facing screens. | Disclosure is accurate but consent wording or UI placement needs review. | Disclosure is missing, misleading, or outdated. | Recheck readme and admin notices before packaging. | P0 |
| User consent / intentional action | Viewing screens does not send data; fetch and generate are separate actions. | Users must intentionally configure credentials and intentionally click fetch/generate before external transmission. | Flow is intentional but more explicit confirmation may be desirable. | External transmission can happen without clear user action or notice. | Preserve staged flow; verify via Step 40/41. | P0 |
| Credential storage | Google Access Token and OpenAI API Key are stored in `wp_options` as MVP settings and are not redisplayed. | Storage risk is redesigned or explicitly accepted for the chosen release scope with clear documentation. | Technical baseline is clean, but public release waits on storage/OAuth policy. | Public release would imply a security guarantee the plugin does not provide. | Prefer Step 43 credential/OAuth redesign plan before WordPress.org submission. | P0 |
| Google OAuth / token handling | Manual Google Access Token entry is documented as temporary. | OAuth, expiry handling, scope checks, and revoke/reconnect policy are implemented or consciously scoped with approval. | MVP remains private/developer-verification while OAuth plan is prepared. | Manual token entry is presented as public-user-ready. | Hold public submission until policy is decided. | P0 |
| OpenAI API key handling | Key can be saved, not redisplayed, cleared, and disclosed as MVP storage. | Restricted key guidance is clear and storage approach is accepted or redesigned. | Key handling works but needs stronger public release policy. | Keys are displayed, logged, or bundled in package/evidence. | Verify clear controls and no secret recording in Step 40/41. | P0 |
| Cost / quota explanation | OpenAI cost and GA4/API usage notices are present. | Admins see cost/quota warning before actions and E2E verifies it is understandable. | Cost wording exists but needs UX emphasis. | Users can unknowingly incur significant API cost. | Confirm notices in Step 40/41 and decide whether stronger confirmation is needed. | P1 |
| Privacy / analytics sensitivity | Payload review warns that analytics data may be sensitive. | Payload review is effective and external data transmission is clearly disclosed. | Payload data is disclosed but release notes/support docs need more guidance. | Payloads, raw responses, or sensitive analytics are recorded or leaked. | Keep status-level evidence only; avoid full payload/report storage. | P0 |
| Generated report disclaimer / human review | Generated text is described as a draft for review and editing. | E2E confirms draft warning is visible and output is treated as editable. | Warning exists but publication guidance needs more emphasis. | Plugin implies AI output is authoritative or ready to publish without review. | Confirm draft warning in Step 40/41. | P1 |
| WordPress.org Plugin Check | Clean against dry-run stage. | Clean immediately before submission package review. | Clean now but not yet rechecked after final package/install tests. | Plugin Check regresses or package is checked incorrectly. | Re-run against final staged package. | P1 |
| WPCS / PHPCS | Clean: `0 errors / 0 warnings`. | Clean immediately before submission package review. | Clean now but not yet rechecked after final release candidate. | PHPCS baseline regresses. | Re-run after any change. | P1 |
| Packaging / `.distignore` | Dry-run package excludes development/tooling files. | Final inspected package contains runtime files only and no secrets. | Dry-run works, but final install test is pending. | Package contains development files, secrets, docs-only maturation records, or build artifacts. | Run final package inspection and install test. | P0 |
| Readme clarity | Readme has External Services, credential storage, and payload review sections. | Readme remains accurate and sufficiently clear for public users. | Readme is accurate but needs support/FAQ wording. | Readme omits material external service or storage behavior. | Re-review readme before public submission. | P1 |
| Support burden | GA4 setup, token entry, and OpenAI key setup require admin knowledge. | Support scope and known limitations are documented and accepted. | Support burden is unclear; keep private while instructions mature. | Public users are likely to be blocked by unsupported setup assumptions. | Define support scope before public release. | P2 |
| Multisite support | Network activation is explicitly outside MVP support scope. | Public scope states multisite limitations clearly or multisite is tested. | Multisite remains untested and release is private/single-site only. | Public release implies multisite support that does not exist. | Document multisite limitation if public release proceeds. | P2 |
| Public release scope | MVP is intended for development and verification. | Release scope is narrowed and communicated honestly, or MVP is matured for broader public use. | Keep as private/developer verification until scope is clear. | Public listing over-promises general-user readiness. | Decide public scope separately from technical baseline. | P0 |
| Future feature restraint | Scheduled reports, PDFs, email, multiple providers, and multilingual output are outside MVP. | Public messaging avoids implying those features exist. | Feature requests may shape future roadmap after MVP validation. | Missing future features are marketed as present. | Keep readme/changelog scope conservative. | P2 |

## 5. Suggested Go Criteria

Consider `Go` only if all applicable criteria are met:

- Step 40 admin smoke tests pass with sanitized evidence.
- Step 41 manual GA4 / OpenAI E2E tests pass with status-level or redacted
  evidence only.
- No secrets appear in git status, docs, screenshots, logs, package contents,
  command output, issue text, or release notes.
- External Services disclosure remains accurate for Google Analytics Data API
  and OpenAI API.
- Users must intentionally configure credentials and intentionally click Fetch
  GA4 Data / Generate AI Report before external transmission occurs.
- AI report text is practically useful as a draft and does not overstate its
  certainty.
- Credential storage risk is either redesigned or explicitly accepted for the
  chosen release scope.
- Google OAuth / token handling policy is documented and accepted.
- OpenAI API key handling and restricted-key guidance are clear enough for the
  intended audience.
- OpenAI cost and GA4/API quota implications are clear.
- Plugin Check remains clean.
- WPCS / PHPCS remains clean.
- Final release package inspection passes.
- Support scope and known limitations are documented.

## 6. Suggested Hold Criteria

Use `Hold` when the technical baseline is good but public-release evidence or
policy decisions are not ready:

- PHP lint, PHPCS, Plugin Check, and packaging are clean, but Step 40 or Step 41
  has not been executed.
- E2E quality is promising but not tested across enough safe GA4/OpenAI cases.
- External disclosure exists, but user consent/confirmation UX needs review.
- Credential storage remains MVP-only and no public credential/OAuth decision
  has been made.
- Manual Google Access Token entry is acceptable for private verification but
  not accepted for public users.
- OpenAI restricted-key guidance may create avoidable support requests.
- The plugin is useful as a self-use or developer verification tool, but support
  burden for WordPress.org users is unclear.

## 7. Suggested No-Go Criteria

Use `No-Go` when any material public-release blocker is present:

- Secrets are displayed, logged, packaged, committed, or captured in evidence.
- External transmission can occur without clear user action or notice.
- GA4 or OpenAI error handling leaks raw responses, request bodies, tokens, keys,
  Authorization headers, or full payloads.
- AI output quality is not useful enough as a draft.
- Plugin Check or WPCS / PHPCS baseline regresses.
- Dry-run or final package contains development files, secrets, logs, payloads,
  generated reports, or tooling files.
- Public release would imply unsupported OAuth, credential security, multisite,
  or support guarantees.
- External service disclosure becomes inaccurate or incomplete.

## 8. Public Release Risk Register

| Risk | Current status | Release concern | Mitigation / next action |
| --- | --- | --- | --- |
| Manual Google Access Token flow | Temporary MVP mechanism. | Too fragile and security-sensitive for general users. | Step 43 OAuth and credential storage redesign plan. |
| `wp_options` credential storage | Documented MVP behavior. | Database admins, backups, server admins, or option-reading code may access secrets. | Redesign or explicitly accept for limited scope before public release. |
| No refresh token / expiry tracking / revoke UI | Not implemented. | Users may not understand expiration, scopes, or revocation. | Plan OAuth, expiry, scope, and reconnect/revoke UX. |
| OpenAI API cost and quota | Noticed in UI/readme. | Users may incur unexpected cost. | Verify warnings in Step 40/41; consider stronger confirmation if needed. |
| Analytics data sensitivity | Payload review exists. | Page paths, traffic sources, city data, and business trends can be sensitive. | Keep review-before-send flow and avoid recording payloads. |
| AI overstatement risk | Prompt asks for cautious draft output. | Generated reports may still overstate or infer. | Treat output as draft and verify E2E quality. |
| GA4 setup support burden | Admin must provide property ID and access token. | Public users may need OAuth-style setup support. | Decide support scope and documentation depth. |
| Restricted OpenAI key confusion | Error messaging mentions required permission. | Users may misconfigure restricted keys. | Add clearer setup docs if public release proceeds. |
| Multisite not formally supported | Network activation support is outside MVP. | Public users may expect multisite behavior. | State limitation or test/implement before public release. |
| Japanese-only MVP output | Intentional MVP scope. | Internationalization structure exists, but output language scope is narrow. | Keep release claims Japanese-focused unless expanded. |
| Legal/privacy review | Not determined in this document. | Public listing may require review of disclosures and policies. | Complete separate legal/privacy review before public submission. |

## 9. Recommended Classification For Current State

This step does not make a final publication decision.

Recommended current classification:

- Technical baseline: clean MVP baseline.
- Private/developer verification: suitable to continue.
- WordPress.org public submission: Hold until Step 40 admin smoke results,
  Step 41 redacted E2E results, and credential/OAuth release policy are reviewed
  and accepted.

This recommendation is based on the current clean static/package baseline plus
the remaining public-release concerns around manual Google Access Token entry,
MVP credential storage, external-service user expectations, and E2E evidence.

## 10. Required Evidence Before Go

Before applying a `Go` decision, collect and review:

- Completed Step 40 admin smoke test result summary.
- Completed Step 41 GA4 / OpenAI E2E result summary using redacted or
  status-level evidence only.
- Confirmation that no secrets appear in git status, docs, screenshots, logs,
  package contents, command output, issues, pull requests, or release notes.
- Confirmed External Services disclosure in `readme.txt`.
- Confirmed user-facing notices for GA4 fetch, payload preview, OpenAI
  generation, cost/quota, credential storage, and draft review.
- Confirmed credential/OAuth policy for the chosen release scope.
- Confirmed OpenAI API key handling policy.
- Confirmed support scope and known limitations.
- Final dry-run package inspection.
- Plugin Check clean against the staged package.
- WPCS / PHPCS clean.
- Final install test result for the release candidate package.

Do not include credential values, Authorization headers, full request bodies,
full payloads, raw GA4 responses, raw OpenAI responses, or full generated
reports in the evidence.

## 11. Decision Recording Template

Use this template when a release decision is actually made:

| Field | Value |
| --- | --- |
| Decision date | YYYY-MM-DD |
| Decision owner | Name / role |
| Result | Go / Hold / No-Go |
| Summary | Short decision summary. |
| Evidence reviewed | Step 40 summary, Step 41 summary, package inspection, Plugin Check, PHPCS, readme review. |
| Accepted risks | List only risks explicitly accepted for the chosen scope. |
| Required follow-up | Action items before next review or release. |
| Next review date | YYYY-MM-DD or milestone. |

Decision notes must not include secrets, full payloads, raw responses, or full
generated reports.

## 12. Recommended Next Steps

- Step 43: OAuth and credential storage redesign plan.
- Step 44: execute admin smoke test and record redacted results.
- Step 45: execute manual GA4 / OpenAI E2E and record redacted results.
- Step 46: final release package install test.
- Step 47: WordPress.org submission package review.

## 13. Safety Notes

This step does not perform GA4 API communication, OpenAI API communication,
other external API communication, Composer install/update, `phpcbf`, SVN
operations, GitHub release operations, or WordPress.org publication actions.

This document intentionally does not include credential values, access tokens,
OpenAI API keys, Authorization headers, full request bodies, full AI payload
bodies, full GA4 raw responses, full OpenAI raw responses, full generated report
text, or legal compliance assertions.
