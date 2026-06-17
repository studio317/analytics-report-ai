# Step 167: Credential Source UI Wording Narrow Production Implementation Results

## Step Purpose

Step 167 implements the narrow production wording changes planned in Step 165
and Step 166 for GA4 credential source UI wording, status labels, and
value-hidden posture.

The implementation is limited to existing Settings and Report Builder UI copy.
It does not change credential storage, credential source resolver behavior,
GA4 requests, OpenAI requests, payload structure, transients, OAuth lifecycle
behavior, admin slugs, JavaScript, CSS, `readme.txt`, or tools.

Result classification: `Implementation completed`

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step166-credential-source-ui-wording-implementation-plan.md`
- `docs/maturation/step165-credential-source-ui-wording-and-status-label-alignment-plan.md`
- `docs/maturation/step164-manual-token-fallback-retirement-decision-checkpoint.md`
- `docs/maturation/step162-admin-smoke-instruction-slug-alignment-docs-update-results.md`
- `docs/maturation/step159-missing-credential-ui-path-controlled-local-only-execution-results.md`
- `docs/maturation/step155-human-controlled-credential-source-admin-smoke-results.md`
- `docs/maturation/step153-ga4-oauth-credential-source-source-level-verification-results.md`
- `docs/maturation/step152-narrow-ga4-oauth-credential-source-production-implementation-results.md`

## Changed Files

Production PHP changed:

- `includes/class-settings.php`
- `includes/class-report-builder.php`

Docs added:

- `docs/maturation/step167-credential-source-ui-wording-narrow-production-implementation-results.md`

Unchanged areas:

- JavaScript,
- CSS,
- `readme.txt`,
- tools,
- GA4 client,
- OpenAI client,
- payload formatter,
- transient helpers,
- credential source resolver precedence,
- settings save / sanitize behavior,
- OAuth token storage/access behavior,
- admin menu slug registration.

## Implementation Summary

### Settings Wording Changes

Settings copy now more clearly communicates:

- GA4 requests use the resolved Google credential source,
- OAuth is the preferred GA4 credential source,
- the temporary manual Google Access Token is an MVP maturation fallback,
- manual token saved/not-saved status is value-hidden,
- token values, token endpoint responses, option values, and OAuth client
  values are not displayed,
- refresh, revoke, and reconnect controls are still not implemented.

The manual Google Access Token status and field labels were narrowed to make
clear they refer to the manual fallback path, not the preferred OAuth path.

### Report Builder Wording Changes

Report Builder copy now more clearly communicates:

- the GA4 credential source status is a safe category label,
- OAuth is the preferred Google credential source,
- the manual Google Access Token is an MVP maturation fallback,
- credential values are hidden,
- missing credential and OAuth credential error messages are safe actionable
  categories that do not expose token values, option values, or request details.

### Value-Hidden Posture

The implementation retains value-hidden posture:

- token values are not redisplayed,
- saved/not-saved state remains status-level,
- credential source categories are displayed as labels,
- support/debug guidance remains category-level.

## Behavior Unchanged

The following were not changed:

- credential source resolver precedence,
- OAuth token storage/access behavior,
- manual token storage/access behavior,
- settings save behavior,
- settings sanitize behavior,
- GA4 client request behavior,
- OpenAI request behavior,
- AI payload structure,
- payload formatter behavior,
- transient key / expiration / validation policy,
- OAuth refresh behavior,
- reconnect / revoke / disconnect behavior,
- uninstall cleanup,
- admin page slugs,
- JavaScript behavior,
- CSS behavior.

## Safety / Forbidden Evidence Posture

Step 167 did not inspect, display, log, or record:

- credential values,
- API keys,
- access tokens,
- refresh tokens,
- Authorization headers,
- plugin settings option values,
- OAuth token option values,
- serialized option values,
- OAuth client ID values,
- OAuth client secret values,
- GA4 Property ID values,
- hostname/domain values,
- analytics values,
- page path/source/city values,
- request bodies,
- raw GA4 responses,
- AI payload JSON,
- raw OpenAI responses,
- generated report bodies,
- browser DevTools or Network evidence,
- screenshots.

The implementation is source-level only and uses status-level wording.

## Verification Performed

Commands executed:

```bash
git status --short --untracked-files=all
php -l includes/class-settings.php
php -l includes/class-report-builder.php
git diff --check
git diff --stat
git diff --name-only
rg -n "Authorization|access_token|refresh_token|option value|serialized|raw response|payload JSON|generated report|MVP maturation fallback|OAuth-first|preferred Google credential|missing_google_credential|credential_source_missing" includes/class-settings.php includes/class-report-builder.php includes/functions-utils.php
test -f docs/maturation/step167-credential-source-ui-wording-narrow-production-implementation-results.md && echo "step167_doc_exists"
git status --short --untracked-files=all
```

Verification summary:

- PHP syntax checks passed for touched PHP files.
- `git diff --check` passed.
- Source-level safety grep was limited to symbols and strings.
- No Plugin Check was executed.
- No external API action was executed.
- No admin browser smoke was executed.

## Result Classification

Result: `Implementation completed`

Rationale:

- Settings and Report Builder wording now aligns with OAuth-first credential
  source posture.
- Manual Google Access Token wording is limited to MVP maturation fallback.
- Missing credential wording is safe and actionable.
- Value-hidden posture is retained.
- No behavior, storage, external communication, payload, transient, OAuth
  lifecycle, JavaScript, CSS, readme, or tools changes were made.

WordPress.org release remains `Hold`.

## Notes And Limitations

- This step does not validate the new wording in a browser.
- This step does not run GA4 Fetch.
- This step does not run OpenAI Generate.
- This step does not run OAuth Connect / Authorize.
- This step does not run Plugin Check.
- This step does not decide public-release credential posture.

## Recommended Next Step

Recommended next step:

```text
Step 168: Credential source UI wording source-level verification
```

Recommended scope:

- source-level verification of the Step 167 wording implementation,
- verify touched production strings remain translation-ready and escaped,
- verify value-hidden posture and forbidden evidence boundaries,
- no GA4 Fetch,
- no OpenAI Generate,
- no OAuth Connect / Authorize,
- no token endpoint communication,
- no Plugin Check unless explicitly scoped later.
