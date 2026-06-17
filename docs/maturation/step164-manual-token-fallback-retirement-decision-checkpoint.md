# Step 164: Manual Token Fallback Retirement Decision Checkpoint

## Step Purpose

Step 164 is a docs-only and planning-only decision checkpoint for the manual
Google Access Token fallback that remains in the GA4 credential source model.

The purpose is to separate the current MVP maturation posture from the
public-release posture before any production code change, external
communication, browser operation, token endpoint action, or credential value
inspection.

Result classification: `Decision checkpoint completed`

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step163-oauth-credential-source-remaining-gap-decision-checkpoint.md`
- `docs/maturation/step162-admin-smoke-instruction-slug-alignment-docs-update-results.md`
- `docs/maturation/step159-missing-credential-ui-path-controlled-local-only-execution-results.md`
- `docs/maturation/step155-human-controlled-credential-source-admin-smoke-results.md`
- `docs/maturation/step153-ga4-oauth-credential-source-source-level-verification-results.md`
- `docs/maturation/step152-narrow-ga4-oauth-credential-source-production-implementation-results.md`
- `docs/maturation/step151-ga4-oauth-token-integration-implementation-boundary.md`
- `docs/maturation/step150-ga4-oauth-token-integration-boundary.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`

## Execution Boundary

Step 164 is docs-only and planning-only.

This step did not perform:

- production PHP changes,
- JavaScript, CSS, `readme.txt`, or tools changes,
- admin UI or browser operations,
- temporary guard / mu-plugin / helper / test script creation,
- Plugin Check,
- GA4 Fetch,
- OpenAI Generate,
- OAuth Connect / Authorize,
- Google navigation,
- token endpoint communication,
- browser DevTools / Network evidence collection,
- screenshot recording,
- `wp option get analytics_report_ai_oauth_tokens`,
- database dump,
- plugin settings option value display,
- OAuth token option value display,
- serialized option value display,
- credential, API key, access token, refresh token, or Authorization header
  display,
- OAuth client ID or client secret value display,
- GA4 Property ID, hostname/domain, analytics values, page path/source/city
  display,
- request body, GA4 raw response, AI payload JSON, OpenAI raw response, or
  generated report body display.

Evidence is limited to status-level categories and decision planning.

## Current Manual Token Fallback Status

The current GA4 credential source model is:

```text
1. usable OAuth token option
2. manual Google Access Token fallback during MVP maturation
3. missing credential category
```

Status-level summary:

- manual token fallback remains available as a fallback source in the GA4
  credential source resolver,
- usable OAuth token option takes precedence when available,
- manual token fallback is treated as an MVP maturation recovery path,
- manual token saved/not-saved posture must remain value-hidden,
- token values are not inspected or recorded in this decision checkpoint,
- public-release handling remains unresolved and must be revisited before
  release readiness.

## Decision Options

| Option | Summary | Pros | Cons | Risk |
|---|---|---|---|---|
| Option A: Keep manual token fallback through MVP maturation only | Keep the manual fallback during the current maturation phase, then require explicit public-release re-evaluation before release readiness. | Preserves a recovery path while OAuth source behavior matures; avoids immediate behavior churn; fits current OAuth-first precedence. | Leaves a public-release decision open; requires clear UI wording so the fallback is not mistaken for the preferred long-term model. | Medium if the re-evaluation is skipped before release. |
| Option B: Deprecate manual token fallback now, but keep source path temporarily | Plan UI wording that treats the manual token as deprecated while leaving source behavior unchanged for now. | Makes OAuth-first direction clearer; can be planned without production code change; reduces future support ambiguity. | Could create mismatch if UI says deprecated before migration/cleanup details are ready. | Medium if wording gets ahead of actual implementation. |
| Option C: Hide manual token UI before public release, keep internal fallback only temporarily | Move toward hiding manual token entry in public-release UI while retaining an internal fallback for migration or recovery until separately retired. | Reduces exposure of manual credential entry in public UI; supports OAuth-first posture. | Requires UI, migration, and support wording planning; hidden fallback can be confusing if not documented internally. | Medium to high if hidden state and cleanup are not explicit. |
| Option D: Fully retire manual token fallback before public release | Remove the manual token path and move to OAuth-only credential sourcing before release readiness. | Strongest long-term credential posture; simplest public model. | Requires migration, cleanup, UI, docs, and support policy work; may remove a useful MVP recovery path too early. | High if attempted before OAuth refresh/reconnect/revoke behavior is mature. |
| Option E: Keep manual token fallback for public release | Keep manual token fallback after public release. | Lowest near-term implementation churn. | Keeps manual credential handling in public posture; increases security, UX, support, and documentation burden. | High; not recommended as the current direction. |

## Recommended Decision

Recommended decision:

```text
Recommended: Option A for current MVP maturation, with explicit public-release re-evaluation before release readiness
```

Decision rationale:

- The credential source resolver is already OAuth-first when a usable OAuth
  token option is available.
- The missing credential path has been safely observed at status level.
- Manual fallback remains useful as a maturation recovery path while OAuth
  credential source behavior continues to stabilize.
- Public-release posture still needs an explicit decision before release
  readiness; the manual token path should not silently carry forward into
  public release without review.
- This decision allows the next UI wording / status label alignment plan to
  proceed without inspecting real token values or option values.

This step does not deprecate, hide, or retire the manual token fallback in
production code. It records the current MVP maturation posture and the
public-release re-evaluation requirement.

## Public Release Posture

WordPress.org release remains `Hold`.

Public-release posture:

- manual token fallback is allowed to remain during MVP maturation,
- manual token fallback public-release status is unresolved,
- public-release readiness must include a manual token fallback re-evaluation,
- if manual token fallback remains for public release, privacy, support,
  documentation, and Settings UI wording require additional review,
- if OAuth-only is selected for public release, UI, storage, migration, and
  cleanup planning are required,
- this Step 164 does not decide release readiness.

## Impact On Future Tracks

| Future track | Impact from Step 164 decision |
|---|---|
| Track E: Credential source UI wording/status label alignment | Can proceed next with language that treats OAuth as preferred and manual token fallback as an MVP maturation fallback, while keeping values hidden. |
| OAuth refresh behavior planning | Should happen after the credential model wording is stable, because refresh behavior belongs to the OAuth-first path. |
| Reconnect / disconnect / revoke behavior planning | Should separate OAuth lifecycle UI from manual fallback state and avoid implying provider revoke for manually entered tokens. |
| Uninstall cleanup boundary planning | Should include both OAuth token option and manual fallback storage implications, without inspecting values. |
| Readme / privacy wording | Should reflect the chosen public-release credential model after the manual fallback re-evaluation. |
| Future admin smoke instructions | Should use Step 162 canonical slugs and status-level credential source categories only. |

## Safety Boundary For Future Steps

Future steps should preserve these boundaries unless a later step explicitly
narrows and authorizes otherwise:

- no credential value inspection,
- no option value inspection,
- no token endpoint communication,
- no real GA4 request,
- no OpenAI request,
- no OAuth Connect / Authorize,
- no Authorization header inspection,
- no browser Network evidence,
- no screenshots,
- no request body recording,
- no raw response recording,
- no AI payload JSON recording,
- no generated report body recording,
- status-level category only.

## Decision Output

```text
WordPress.org release remains: Hold
manual token fallback current MVP maturation status: keep
manual token fallback public release status: unresolved / must re-evaluate before release
OAuth-first precedence remains: Yes
production code change immediate next step: No
real GA4 Fetch immediate next step: No
OpenAI Generate immediate next step: No
OAuth token endpoint immediate next step: No
Plugin Check immediate next step: No
```

## Result Classification

Result: `Decision checkpoint completed`

Rationale:

- Manual token fallback options were compared.
- Current MVP maturation posture was separated from public-release posture.
- Option A was selected for current maturation with an explicit public-release
  re-evaluation requirement.
- No production code was changed.
- No external action or forbidden evidence collection occurred.

WordPress.org release remains `Hold`.

## Notes And Limitations

- This step does not inspect live credential or option values.
- This step does not change UI wording, Settings behavior, resolver behavior,
  OAuth behavior, GA4 behavior, or OpenAI behavior.
- This step does not validate refresh, reconnect, disconnect, revoke, uninstall
  cleanup, Plugin Check, or release readiness.
- This step does not decide whether manual token fallback will remain in a
  public release.

## Recommended Step 165

Recommended Step 165:

```text
Step 165: Credential source UI wording and status label alignment plan
```

Recommended scope:

- docs-only,
- planning-only,
- no production code change,
- no browser action,
- no external communication,
- no token endpoint communication,
- no credential or option value inspection,
- status-level category only.

Rationale:

- Step 164 keeps manual token fallback for MVP maturation but marks
  public-release status as unresolved.
- The next safe step is to plan user-facing wording and status labels that make
  OAuth-first precedence, MVP fallback status, missing credential state, and
  value-hidden posture clear without changing behavior yet.

## Commands Executed

Safe docs-only commands:

```bash
git status --short --untracked-files=all
test -f docs/maturation/step163-oauth-credential-source-remaining-gap-decision-checkpoint.md && echo "step163_doc_exists"
test -f docs/maturation/step152-narrow-ga4-oauth-credential-source-production-implementation-results.md && echo "step152_doc_exists"
test -f docs/maturation/step153-ga4-oauth-credential-source-source-level-verification-results.md && echo "step153_doc_exists"
test -f docs/maturation/step159-missing-credential-ui-path-controlled-local-only-execution-results.md && echo "step159_doc_exists"
test -f docs/maturation/step164-manual-token-fallback-retirement-decision-checkpoint.md && echo "step164_doc_exists"
git diff -- docs/maturation/step164-manual-token-fallback-retirement-decision-checkpoint.md
git diff --name-only
git status --short --untracked-files=all
```

Command result summary:

- Required recent reference docs checked by file existence were present.
- Step 164 docs file exists.
- Production code was not changed.
- `git diff --name-only` does not include untracked docs until they are staged;
  use `git status --short --untracked-files=all` for the full working tree
  view.
