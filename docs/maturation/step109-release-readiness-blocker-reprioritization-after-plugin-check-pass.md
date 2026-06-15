# Step 109: Release Readiness Blocker Re-prioritization After Plugin Check Pass

## Step Summary

Step 108 confirmed that Plugin Check passes against the clean package target.
The Step 105 package contents findings are now resolved by the Step 107 release
package remediation and the Step 108 clean package rerun.

This step re-prioritizes the remaining release-readiness blockers and records
the next blocker to close.

This is a docs-only decision checkpoint. It does not change production code,
PHP, JavaScript, CSS, `readme.txt`, `.distignore`, build scripts, package
contents, admin UI behavior, Settings save logic, GA4 client behavior, OpenAI
client behavior, OAuth behavior, credential storage, or uninstall behavior.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step108-isolated-plugin-check-rerun-clean-package-results.md`
- `docs/maturation/step107-release-package-contents-remediation-implementation-results.md`
- `docs/maturation/step106-plugin-check-findings-triage-release-package-remediation-plan.md`
- `docs/maturation/step105-isolated-plugin-check-results.md`
- `docs/maturation/step104-readme-privacy-wording-alignment-implementation-results.md`
- `docs/maturation/step102-payload-preview-raw-json-removal-implementation-results.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step95-payload-preview-json-visibility-final-decision.md`
- `docs/maturation/step93-external-api-error-path-recheck-results.md`
- `docs/maturation/step92-10-synthetic-browser-smoke-results.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`
- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`

## Current Resolved Items

| Area | Status | Supporting steps |
|---|---|---|
| GA4 empty/no-data handling implementation and synthetic browser smoke | Resolved for the current MVP readiness track | Step 91, Step 92.10, Step 93 |
| Payload Preview raw JSON visibility policy | Resolved as a policy decision | Step 95 |
| Generated report handling policy | Resolved as a policy decision | Step 96 |
| Privacy/support wording policy | Resolved as a policy decision | Step 97 |
| Admin UI wording alignment | Resolved for the current alignment pass | Step 100 |
| Raw JSON details area removal from normal admin UI | Resolved for normal admin UI | Step 102 |
| `readme.txt` / privacy wording alignment | Resolved for the current wording pass | Step 104 |
| Plugin Check package contents findings | Resolved for the clean package target | Step 105 findings resolved by Step 107 and Step 108 |
| Clean package Plugin Check | Pass | Step 108: Errors 0, Warnings 0 observed, Notices 0 observed |

## Remaining Blockers

The following release-readiness blockers remain after the clean package Plugin
Check pass:

- Google OAuth and token lifecycle.
- OpenAI API key storage posture.
- Uninstall credential cleanup.
- Final release decision review.
- Final manual admin smoke after UI/readme changes, if needed.
- Release package review / SVN-oriented package policy, if needed.

## Blocker Classification

| Classification | Blockers |
|---|---|
| Credential lifecycle blocker | Google OAuth and token lifecycle; OpenAI API key storage posture; uninstall credential cleanup |
| Verification blocker | Final manual admin smoke after UI/readme changes, if needed |
| Release decision blocker | Final release decision review; release package review / SVN-oriented package policy, if needed |

## Recommended Priority

Recommended order:

1. Google OAuth and token lifecycle strategy decision.
2. OpenAI API key storage posture decision.
3. Uninstall credential cleanup policy decision.
4. Final manual admin smoke after UI/readme changes.
5. Release package review / SVN-oriented package policy.
6. Final release decision review.

The credential lifecycle items should start with decision checkpoints rather
than direct implementation. That keeps the next steps focused on public release
posture, MVP hold posture, and developer verification posture before changing
OAuth, credential storage, or uninstall behavior.

## Decision Rationale

The Plugin Check package blocker is resolved for the clean package target. The
Step 108 clean package rerun passed with no errors and no warnings or notices
observed in the command output.

That removes the immediate package contents blocker, but it does not resolve
the credential lifecycle blockers.

Manual Google Access Token entry remains insufficient for public release. Step
81 treated manual token entry, Google OAuth/token lifecycle, storage posture,
and revocation/reconnect behavior as release blocker candidates. The current
manual token workflow can remain useful for developer verification, but it
should not be treated as public-release ready without an explicit strategy
decision.

OpenAI API key storage also needs a public/multi-user storage posture decision.
The current MVP settings-based model is acceptable for controlled developer
verification only if the release posture continues to say so clearly.

Uninstall cleanup is closely related to credential-bearing settings. It should
be handled near the credential lifecycle decisions so cleanup behavior matches
the chosen release posture for stored Google and OpenAI credentials.

The next step should not jump directly into OAuth implementation. A decision
checkpoint is needed first to decide whether the project is:

- still holding WordPress.org release,
- keeping manual token entry for developer verification only,
- planning full OAuth before public release,
- deferring release until a credential storage strategy is accepted,
- or adopting another scoped public-release posture.

Final manual admin smoke and final release decision review should come after
the credential posture is settled, because otherwise release-readiness evidence
may need to be repeated after credential-facing changes.

## Recommended Next Step

Recommended next step:

```text
Step 110: Google OAuth and token lifecycle strategy decision checkpoint
```

Step 110 should be docs-only and should not implement OAuth, token exchange,
refresh tokens, revocation, reconnect UI, credential storage changes, or
uninstall cleanup.

## Explicit Non-goals

This step does not:

- change code,
- change `readme.txt`,
- change `.distignore`,
- change build scripts,
- rebuild a release package,
- rerun Plugin Check,
- run Plugin Check in `wp-dev`,
- touch `wp-dev-check`,
- call external APIs,
- run GA4 Fetch,
- run OpenAI Generate,
- record raw payloads,
- record raw request bodies,
- record raw response bodies,
- record generated report bodies,
- capture screenshots,
- inspect browser Network tab data,
- inspect or display credentials,
- inspect or display plugin settings option values,
- change admin UI behavior.

## Security / Evidence Notes

This document records only status-level release readiness decisions and blocker
prioritization.

It does not record real credentials, API keys, access tokens, Authorization
headers, plugin settings option values, GA4 Property IDs, hostname/domain
values, analytics values, page paths, traffic sources, city values, request
bodies, AI payload JSON, OpenAI request bodies, raw GA4/OpenAI response bodies,
generated report bodies, screenshots, browser Network tab data, cookies,
sessions, or nonces.

## Verification

Commands run for this docs-only step:

```text
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```

Expected result:

- whitespace check passes,
- no production code files change,
- no PHP, JavaScript, CSS, `readme.txt`, `.distignore`, or build script files
  change,
- only this Step 109 documentation file is added.
