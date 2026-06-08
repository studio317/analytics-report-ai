# Step 15 Credential Storage Policy

## 1. Scope

This step addresses the Step 12 P0-2 credential storage risk for the current MVP.

It focuses on:

- documenting the current storage policy,
- making the Settings screen clearer for administrators,
- preserving the existing MVP flow,
- adding a small new-install autoload hardening path.

No external API communication was performed. No credential value was recorded.

## 2. Current MVP storage model

The MVP stores plugin settings in the `analytics_report_ai_settings` WordPress option.

The credential values currently stored there are:

- Google Access Token: `google_tokens['access_token']`
- OpenAI API Key: `openai_api_key`

These values remain in the same option for this step. This step does not split credentials into separate options, add encryption, connect an external secret manager, or change the GA4/OpenAI request flow.

## 3. Display and deletion policy

Saved credential values must not be written back into Settings form `value` attributes.

The Settings screen only shows saved/not-saved state through placeholder text and descriptions. When the credential field is left empty, the existing saved value is kept. When the delete checkbox is submitted, the saved value is removed.

This behavior is retained for:

- Google Access Token
- OpenAI API Key

## 4. Remaining risk

The current MVP storage method saves credentials in the WordPress database as ordinary plugin settings. Database administrators, backup systems, server administrators, object-cache/debug tooling, or code that can read WordPress options may be able to access these values.

This is acceptable only for local MVP and developer verification where trusted administrators understand the storage limitation. It is not sufficient for public use, multi-user use, or WordPress.org-oriented release readiness.

## 5. Minimal hardening added in this step

New installs now attempt to add the default `analytics_report_ai_settings` option with autoload disabled during plugin activation. Existing sites are not overwritten, and existing option values are not migrated or changed.

Credential sanitization now trims input and removes control characters while preserving opaque non-control credential characters. This avoids echoing or logging values and keeps empty-field behavior unchanged.

## 6. Required redesign before public use

Before public use or multi-user use, credential handling needs a broader redesign. Future candidates include:

- Google OAuth flow
- refresh token and expiry tracking
- scope validation
- revoke/reconnect UI
- OpenAI API key storage separation
- constant-based configuration
- stronger autoload suppression or separate non-autoloaded credential options
- encryption or external secret storage

## 7. Intentionally not implemented

This step intentionally does not implement:

- formal Google OAuth connection,
- Google refresh token handling,
- credential encryption,
- external secret manager integration,
- migration to separate credential options,
- GA4 API logic changes,
- OpenAI API logic changes,
- AI payload changes,
- transient storage changes,
- Report Builder flow changes.
