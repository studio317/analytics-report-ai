# Step 152: Narrow GA4 OAuth Credential Source Production Implementation Results

## Step Summary

Step 152 implements a narrow production PHP change for GA4 Fetch credential
source selection.

The implementation adds a request-local GA4 credential source resolver and
wires Report Builder GA4 Fetch to use that resolver before calling the existing
GA4 client methods.

This step does not run GA4 Fetch, run OpenAI Generate, execute browser OAuth,
navigate to Google, operate Google Cloud Console, execute callbacks, call token
endpoints, exchange tokens, inspect token storage values, refresh tokens, revoke
tokens, run Plugin Check, or access `wp-dev-check`.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step151-ga4-oauth-token-integration-implementation-boundary.md`
- `docs/maturation/step150-ga4-oauth-token-integration-boundary.md`
- `docs/maturation/step149-human-controlled-oauth-token-option-cleanup-execution-results.md`
- `docs/maturation/step147-human-controlled-token-exchange-smoke-results.md`
- `docs/maturation/step145-narrow-token-exchange-production-implementation-results.md`
- `docs/maturation/step143-token-storage-lifecycle-decision-checkpoint.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`

## Modified Files

- `includes/functions-utils.php`
- `includes/class-report-builder.php`
- `includes/class-ga4-client.php`
- `docs/maturation/step152-narrow-ga4-oauth-credential-source-production-implementation-results.md`

## Implementation Summary

- Added `analytics_report_ai_resolve_google_ga4_credential_source()`.
- The resolver prefers a usable dedicated OAuth token option.
- The resolver preserves manual Google Access Token fallback during MVP
  maturation.
- The resolver returns safe credential source labels plus request-local token
  material for GA4 runtime use only.
- Report Builder now resolves the GA4 credential source before GA4 client calls.
- Report Builder now passes only the resolver-selected request-local token to
  GA4 client calls.
- Report Builder current settings now shows a credential source status label
  instead of only manual-token saved/not-saved status.
- Missing credential messaging now mentions both Google OAuth and temporary
  manual Google Access Token options.
- GA4 client caller-supplied token boundary is preserved.

## Credential Precedence Implemented

Implemented precedence:

```text
1. Usable OAuth token option
2. Manual Google Access Token fallback during MVP maturation
3. Missing credential category
```

Status-level implementation results:

- resolver helper added: Yes
- OAuth token option preferred: Yes
- manual token fallback preserved: Yes
- missing credential category updated: Yes
- GA4 client caller-supplied token boundary preserved: Yes
- token material request-local only: Yes

## Safe Credential Source Labels

The implementation uses status-level labels only for UI/error status:

- `credential_source_oauth_connected`
- `credential_source_manual_token`
- `credential_source_missing`
- `credential_source_oauth_refresh_needed`
- `credential_source_oauth_error_category`
- `manual_token_fallback_used`

These labels do not contain token values, token fragments, option values,
Authorization headers, request bodies, GA4 raw responses, or analytics values.

## Preserved And Deferred Work

Preserved:

- manual Google Access Token fallback during MVP maturation,
- existing GA4 client caller-supplied token boundary,
- existing GA4 request transport behavior,
- existing OpenAI behavior,
- existing AI payload structure,
- existing generated report behavior,
- Step 91 no-data / `payload_status` / `data_availability` /
  `value_semantics` behavior.

Deferred / not implemented:

- refresh implementation: No
- reconnect implementation: No
- revoke implementation: No
- uninstall cleanup implementation: No
- manual token retirement implementation: No
- GA4 OAuth real fetch smoke: No
- browser OAuth re-run: No
- Plugin Check: No

## Evidence Safety

Status-level results:

- GA4 Fetch executed by CODEX: No
- OpenAI Generate executed by CODEX: No
- browser OAuth executed by CODEX: No
- token endpoint executed by CODEX: No
- OAuth token option value inspected/displayed/recorded: No
- manual token value inspected/displayed/recorded: No
- plugin settings option value displayed/recorded: No
- Authorization header / GA4 raw response / AI payload JSON recorded: No
- refresh / reconnect / revoke implemented: No
- uninstall cleanup implemented: No
- manual token retirement implemented: No
- forbidden evidence recorded: No

This document does not record:

- OAuth token option values,
- serialized option values,
- plugin settings option values,
- manual Google Access Token values,
- access tokens,
- refresh tokens,
- Authorization headers,
- token endpoint requests or responses,
- authorization codes,
- callback URLs,
- query strings,
- raw state values,
- raw provider errors,
- client ID values,
- client secret values,
- GA4 Property ID values,
- analytics values,
- request bodies,
- GA4 raw responses,
- AI payload JSON,
- OpenAI raw responses,
- generated report bodies,
- screenshots,
- browser Network evidence,
- database rows or database dumps,
- email addresses or Google account identifiers,
- project IDs or project identifiers,
- hostname/domain values.

## Next Step Notes

Recommended follow-up:

```text
Step 153: GA4 OAuth credential source source-level verification
```

Rationale: Step 152 changes production PHP but intentionally does not run GA4
Fetch, browser OAuth, token endpoint communication, or Plugin Check. A focused
source-level verification step can confirm resolver labels, fallback behavior,
and missing-credential behavior without exposing values or contacting external
services.
