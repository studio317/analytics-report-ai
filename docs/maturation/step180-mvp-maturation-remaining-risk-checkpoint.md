# Step 180: MVP Maturation Remaining-risk Checkpoint

## Step Purpose

Step 180 is a docs-only and planning-only checkpoint for the overall MVP
maturation track.

Step 179 classified the support/debug wording track as matured for the current
MVP maturation scope. This step now inventories which broader maturation tracks
can be treated as matured, verified, or sufficiently complete for the current
scope, and which remaining risks or release blockers still keep public release
on hold.

This step does not change production code, `readme.txt`, tools, build scripts,
JavaScript, CSS, admin behavior, credential storage, OAuth lifecycle behavior,
GA4 behavior, OpenAI behavior, payload handling, transient handling, release
packaging, or generated report persistence.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step81-credential-oauth-strategy-decision-checkpoint.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`
- `docs/maturation/step97-privacy-support-wording-finalization.md`
- `docs/maturation/step104-readme-privacy-wording-alignment-implementation-results.md`
- `docs/maturation/step108-isolated-plugin-check-rerun-clean-package-results.md`
- `docs/maturation/step129-google-authorization-redirect-execution-implementation-results.md`
- `docs/maturation/step171-credential-source-ui-wording-maturation-checkpoint.md`
- `docs/maturation/step179-support-debug-wording-maturation-checkpoint.md`

Additional maturation context referenced at status/category level:

- `docs/maturation/step64-controlled-ga4-fetch-e2e-browser-run-results.md`
- `docs/maturation/step67-controlled-openai-generate-e2e-browser-run-results.md`
- `docs/maturation/step69-post-e2e-maturation-checkpoint.md`
- `docs/maturation/step77-external-api-error-path-qa-checklist.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`
- `docs/maturation/step95-payload-preview-json-visibility-final-decision.md`
- `docs/maturation/step96-generated-report-handling-policy-finalization.md`
- `docs/maturation/step102-payload-preview-raw-json-removal-implementation-results.md`

## Evidence Boundary

This checkpoint records only status-level and category-level risk information.

It does not display, inspect, or record:

- credentials,
- API keys,
- access tokens,
- refresh tokens,
- Authorization headers,
- OAuth client ID values,
- OAuth client secret values,
- plugin settings option values,
- OAuth token option values,
- serialized option values,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- page path/source/city values,
- request bodies,
- raw GA4 responses,
- raw OpenAI responses,
- AI payload JSON,
- generated report bodies,
- screenshots,
- browser Network evidence,
- cookies,
- sessions,
- nonces,
- database rows,
- database dumps.

## Matured / Completed Tracks

| Track | Current status | Evidence / source step | Release impact | Recommended next action |
|---|---|---|---|---|
| Credential source UI wording track | `matured_for_mvp_scope` | Step 171 | `none_for_mvp` | Preserve category labels, value-hidden posture, and OAuth-first/manual-fallback wording boundaries. |
| Support/debug wording track | `matured_for_mvp_scope` | Step 179 | `none_for_mvp` | Preserve status/category-only support evidence and forbidden-evidence avoidance wording. |
| Privacy/support wording alignment | `verified_for_current_scope` | Step 97, Step 104 | `documentation_risk` | Reconfirm during final release wording review, but current posture is aligned. |
| Raw AI payload JSON removal from normal admin UI | `verified_for_current_scope` | Step 95, Step 102, Step 104 | `none_for_mvp` | Preserve structured Payload Preview posture and do not reintroduce raw JSON support evidence. |
| Generated report non-persistence posture | `verified_for_current_scope` | Step 96, Step 104 | `none_for_mvp` | Preserve non-persistence and do not request generated report bodies for support. |
| Isolated Plugin Check clean-package result | `verified_for_current_scope` | Step 108 | `release_readiness_risk` | Rerun close to final release package timing after any later release-affecting changes. |
| GA4 no-data / partial-data / generation-blocked handling | `verified_for_current_scope` | Step 91 | `qa_risk` | Preserve server-side generation gate and warning/blocking status behavior; recheck only in scoped QA. |
| Controlled GA4 Fetch / OpenAI Generate E2E happy path | `verified_for_current_scope` | Step 64, Step 67, Step 69, Step 77 | `qa_risk` | Preserve as known pass; rerun only in a future controlled external-action step if explicitly authorized. |
| Data minimization and support evidence boundaries | `verified_for_current_scope` | Step 86, Step 97, Step 179 | `documentation_risk` | Continue using status/category-level evidence in docs, support guidance, and QA notes. |

## Remaining Risks / Blockers

| Risk category | Current status | Evidence / source step | Release impact | Recommended next action | Priority |
|---|---|---|---|---|---|
| OAuth production readiness / app verification / consent screen readiness | `needs_followup` | Step 81, Step 129 | `release_blocker` | Continue OAuth lifecycle planning and human-controlled verification without recording forbidden evidence. | High |
| Token exchange, refresh, revoke, reconnect, and lifecycle UX | `needs_followup` | Step 81, Step 129 | `release_blocker` | Define and verify token lifecycle behavior before public release readiness. | High |
| Credential storage strategy for public release | `hold_for_public_release` | Step 81 | `release_blocker` | Decide whether current WordPress option storage remains acceptable, needs constant-based alternatives, or needs a redesigned storage model. | High |
| Token encryption / secret handling strategy | `needs_followup` | Step 81 | `release_blocker` | Decide secret handling posture before public release; avoid ad hoc encryption/storage changes without a scoped design. | High |
| OpenAI API key storage posture | `needs_followup` | Step 81 | `release_readiness_risk` | Reconfirm public-release posture and documentation around API key storage and non-redisplay. | High |
| Manual Google Access Token fallback public-release posture | `hold_for_public_release` | Step 81, Step 171 | `release_blocker` | Decide whether to remove, hide, restrict, or keep as developer-verification-only before release readiness. | High |
| Uninstall cleanup policy for credential-bearing data | `needs_followup` | Step 81 | `release_blocker` | Define uninstall cleanup behavior and evidence boundaries before release readiness. | High |
| Privacy policy / external services wording final release alignment | `needs_followup` | Step 97, Step 104 | `documentation_risk` | Perform a final release wording review after remaining credential/OAuth decisions. | Medium |
| Readme final release review | `needs_followup` | Step 104 | `documentation_risk` | Recheck `readme.txt` after any OAuth, credential, support, or package changes. | Medium |
| Browser automation limitations / Playwright blocked status | `blocked_by_environment` | Step 92-series context | `qa_risk` | Keep human-controlled smoke as the fallback until automation is available and safe. | Medium |
| Release package process stability | `verified_for_current_scope` | Step 108 | `release_readiness_risk` | Rerun clean package build and isolated Plugin Check near release timing. | Medium |
| Final isolated Plugin Check timing | `needs_followup` | Step 108 | `release_readiness_risk` | Run a final isolated Plugin Check only after release-affecting changes are complete. | Medium |
| Final admin smoke timing | `needs_followup` | Step 170, Step 178 | `qa_risk` | Run final safe admin smoke after remaining wording, credential, and release package decisions. | Medium |
| WordPress.org assets / screenshots / FAQ / support instructions | `needs_followup` | Step 86, Step 97, Step 179 | `documentation_risk` | Prepare public assets and instructions without screenshots or support evidence that expose forbidden data. | Medium |
| Production external API behavior risk | `needs_followup` | Step 64, Step 67, Step 77, Step 91 | `operational_risk` | Keep controlled E2E and error-path QA separated; do not run external calls outside explicit future QA steps. | Medium |
| Data minimization / support evidence boundary maintenance | `verified_for_current_scope` | Step 86, Step 97, Step 179 | `documentation_risk` | Preserve current boundaries in future docs, release materials, and support templates. | Low |

## Release Status Decision

Decision:

```text
WordPress.org release status: Hold
```

Rationale:

- The support/debug wording track is matured for the current MVP maturation
  scope, but the full MVP maturation state still has remaining public-release
  risks.
- Credential storage, OAuth/token lifecycle, manual token fallback posture, and
  uninstall cleanup remain release blocker categories.
- Final release readiness should be decided in a separate scoped step after
  blocker prioritization and any required follow-up work.

This checkpoint does not classify the plugin as release-ready.

## Recommended Next Phase / Next Step

Recommended next step:

```text
Step 181: Public release blocker prioritization checkpoint
```

Recommended Step 181 scope:

- docs-only,
- planning-only,
- classify the Step 180 remaining risks into:
  - release blocker,
  - pre-release follow-up,
  - post-release defer,
- keep WordPress.org release status as `Hold`,
- do not run Plugin Check, GA4 Fetch, OpenAI Generate, OAuth Connect,
  token endpoint communication, browser admin smoke, screenshots, or Network
  evidence collection.

## Acceptance Criteria

Step 180 is complete when:

- this docs-only checkpoint file is added,
- production code, `readme.txt`, tools, build scripts, JavaScript, and CSS have
  no additional Step 180 changes,
- matured tracks and remaining risks are organized at category/status level,
- WordPress.org release remains `Hold`,
- forbidden-evidence non-recording policy remains explicit,
- the recommended next step is explicit.

## Not Executed

Step 180 did not execute:

- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- browser admin smoke,
- screenshots,
- browser Network evidence collection,
- database dump,
- option value inspection.

## Commands Executed

Safe docs-only commands for this checkpoint:

```bash
git status --short --untracked-files=all
test -f docs/maturation/step180-mvp-maturation-remaining-risk-checkpoint.md && echo "step180_doc_exists"
git diff --check
git diff --name-only
git diff --stat
git status --short --untracked-files=all
```

## Result Classification

Result: `MVP maturation remaining-risk checkpoint completed`

WordPress.org release remains `Hold`.
