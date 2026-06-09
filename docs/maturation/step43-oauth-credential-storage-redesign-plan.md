# Step 43 OAuth And Credential Storage Redesign Plan

## 1. Overview

This step is a docs-only redesign plan for Google credential handling and
OpenAI API key storage before any public or WordPress.org release decision.

No OAuth flow, encryption layer, database schema, Settings UI, production PHP,
JavaScript, CSS, `readme.txt`, plugin header metadata, `phpcs.xml.dist`,
`.distignore`, `.gitignore`, Composer file, release script, version, or
`Stable tag` is changed in this step.

The current MVP can continue for private or developer verification, but manual
Google Access Token entry and MVP database credential storage are not
public-ready. WordPress.org public submission should remain `Hold` until a
credential and OAuth direction is selected, implemented, reviewed, and tested.

## 2. Current MVP Credential Model

| Credential | Current storage | Current UI behavior | Current release fit |
| --- | --- | --- | --- |
| Google Access Token | Saved inside the plugin settings option in the WordPress database. | Entered manually, not redisplayed after save, and removable through the Settings clear control. | Private/developer verification only. |
| OpenAI API Key | Saved inside the plugin settings option in the WordPress database. | Entered manually, not redisplayed after save, and removable through the Settings clear control. | Private/developer verification only unless the storage risk is explicitly accepted for a limited non-public scope. |

Current hardening is intentionally minimal:

- Saved credential values are not placed back into form field values.
- Saved credential status is shown without revealing the stored value.
- Clear controls exist for saved Google and OpenAI credentials.
- The current storage risk is disclosed in the admin UI and `readme.txt`.
- New default settings option creation avoids autoloading the settings option.

The current storage model still allows possible access by database
administrators, backups, server administrators, or code that can read WordPress
options. It should not be described as encrypted, isolated, or suitable for
general multi-user public operation.

## 3. Google OAuth Options

### Option A: Manual Google Access Token

Manual token entry is the current MVP approach.

| Area | Notes |
| --- | --- |
| Advantages | Small implementation, useful for trusted local or developer verification, no OAuth setup in the plugin. |
| Limitations | Tokens expire, scopes are not validated by the plugin, revocation is manual, reconnect UX does not exist, and users must obtain a token outside the plugin. |
| Security / privacy impact | A bearer token is stored in the WordPress database. Anyone who can read that option may be able to use the token until it expires or is revoked. |
| Support burden | High for public users because token acquisition and expiry handling happen outside the product flow. |
| Public release fit | Not public-ready. Acceptable only for private/developer verification. |

Recommendation: do not submit publicly with manual Google Access Token entry as
the primary Google connection model.

### Option B: Site-Local OAuth Client

Each site owner creates or configures their own Google OAuth client and the
plugin performs the OAuth connection on the WordPress site.

| Area | Notes |
| --- | --- |
| Advantages | No hosted broker dependency, site owner controls the Google Cloud project, and the plugin can implement expiry tracking, refresh, reconnect, and revoke UI locally. |
| Required implementation | OAuth authorization URL, callback route, `state` protection, administrator capability checks, redirect URI validation, token exchange, refresh token storage, access token expiry tracking, scope validation, reconnect UI, revoke/disconnect UI, and error normalization. |
| Credential storage impact | Refresh tokens become long-lived secrets. They need a stronger storage policy than the current MVP option storage. |
| Google app verification / support | Site owners may face Google Cloud setup, consent screen, redirect URI, publishing status, and scope support questions. |
| Public release fit | Possible, but only after substantial UX, documentation, storage, and support work. |

This option keeps token exchange local to the WordPress site, but it shifts a
large amount of setup and troubleshooting to each site owner.

### Option C: Hosted OAuth Broker

A hosted service operated by the plugin owner manages the Google OAuth client
and brokered token exchange.

| Area | Notes |
| --- | --- |
| Advantages | Easier end-user setup, centralized Google OAuth app management, more consistent reconnect and revoke behavior, and a path to avoid storing Google client secrets in the plugin. |
| Required implementation | Hosted authorization service, broker API, secure site-to-broker binding, token lifecycle handling, reconnect/disconnect UI, service status handling, abuse controls, and support processes. |
| Credential storage impact | The WordPress site may store broker-issued credentials or short-lived tokens instead of raw Google refresh tokens, depending on the architecture. The broker becomes security-critical. |
| Google app verification / support | The plugin owner must handle Google app verification, support, privacy documentation, and operational maintenance for the broker. |
| External service disclosure impact | `readme.txt`, admin notices, privacy notes, and support docs must disclose the hosted broker as an additional external service, including what is sent, when, and why. |
| Public release fit | Possible if the plugin owner is prepared to operate the external service and maintain disclosure, security, and support obligations. |

This option can improve user experience, but it changes the product from a
local WordPress plugin into a plugin plus hosted service.

### Option D: Service Account / Internal Flow

The site uses a Google service account or organization-managed internal
credential to access GA4 properties that explicitly grant access.

| Area | Notes |
| --- | --- |
| Advantages | Useful for controlled internal deployments, avoids per-user browser OAuth, and can be managed by an organization. |
| Required implementation | Service account credential handling, property access setup documentation, key rotation policy, storage policy, and failure messaging. |
| Credential storage impact | Service account JSON or private key material is highly sensitive and should not be stored casually in WordPress options. A constant, environment variable, external secret store, or hosted/proxy model may be preferable. |
| Google app verification / support | Lower end-user OAuth consent burden, but higher administrative setup burden. |
| Public release fit | Not a default public-user path. Better suited to internal or enterprise-style deployments. |

This option may be useful for managed environments, but it is not a simple
replacement for user-facing OAuth in a general WordPress.org plugin.

## 4. Google Token Lifecycle Requirements

Any public-ready Google connection model should define and implement:

- Access token expiry tracking.
- Refresh token storage and refresh behavior, if refresh tokens are used.
- Reconnect flow when tokens expire, are revoked, or become invalid.
- Disconnect and revoke flow that explains local deletion versus provider-side
  revocation.
- Scope selection and scope validation.
- Clear explanation of which GA4 permissions are required.
- Redirect URI registration and validation for OAuth flows.
- CSRF protection for OAuth callback state.
- Capability checks so only authorized administrators can connect or disconnect.
- Error messages that avoid exposing tokens, Authorization headers, raw
  responses, full request bodies, or full payloads.
- Support guidance for consent screen setup, app publishing status, Google app
  verification, and common misconfiguration cases.

## 5. OpenAI API Key Storage Options

### Option A: Current Database Storage With Strong Disclosure

The current MVP stores the key inside the plugin settings option in the
WordPress database.

| Area | Notes |
| --- | --- |
| Advantages | Simple, already implemented, works for private/developer verification, and has clear UI controls. |
| Limitations | Database administrators, backups, server administrators, or option-reading code may access the key. |
| Current hardening | The key is not redisplayed after save and can be cleared from Settings. |
| Public release fit | Weak for broad public use unless explicitly accepted for a narrow scope with strong disclosure. |

This option should continue to recommend a restricted OpenAI API key with the
minimum permissions needed by the plugin.

### Option B: Encrypted At Rest In WordPress

The plugin encrypts the stored OpenAI API key before writing it to the database.

| Area | Notes |
| --- | --- |
| Advantages | Reduces direct exposure from raw database reads and backups. |
| Limitations | The WordPress runtime must still decrypt the key to call OpenAI. If the decryption key is available to the application, a compromised runtime or malicious privileged code can still access the secret. |
| Required implementation | Key management, rotation, migration, error handling, backup/restore behavior, and documentation of the limits of encryption at rest. |
| Public release fit | Useful hardening, but not a complete secret-management solution by itself. |

This option should not be described as making credentials inaccessible to the
site runtime.

### Option C: `wp-config.php` / Environment Constant

The OpenAI key is provided through `wp-config.php`, a server environment
variable, or a constant, and not saved through the Settings form.

| Area | Notes |
| --- | --- |
| Advantages | Keeps the key out of the WordPress options table and common database backups. Good fit for developer-managed or host-managed sites. |
| Limitations | Less friendly for non-technical administrators, requires deployment access, and still exposes the key to code running in the WordPress process. |
| Required implementation | Constant/env lookup order, Settings UI status without revealing values, clear precedence rules, and documentation. |
| Public release fit | Strong candidate for developer/agency users and a useful alternative to database storage. |

This option pairs well with a Settings UI that shows whether a constant-based
key is configured without displaying the key.

### Option D: Hosted OpenAI Proxy

A hosted service performs OpenAI API calls on behalf of the WordPress site.

| Area | Notes |
| --- | --- |
| Advantages | Avoids storing the site owner's OpenAI API key in WordPress, allows centralized quota controls, and may simplify setup. |
| Limitations | Adds a new external service, operational cost, privacy review, service reliability requirements, abuse prevention, and support burden. |
| External service disclosure impact | The hosted proxy must be disclosed separately from OpenAI, including what analytics/report data is sent to the proxy, when, and why. |
| Public release fit | Possible only if the plugin owner is prepared to operate and support the service. |

This option changes the trust model: analytics payloads and report-generation
inputs flow through the hosted proxy before reaching OpenAI.

## 6. Current Recommendation

Recommended classification for the current codebase:

- Private/developer verification: continue with the current MVP model.
- WordPress.org public submission: `Hold`.
- Manual Google Access Token entry: not public-ready.
- OpenAI database key storage: acceptable only for private/developer
  verification or a deliberately limited scope with explicit disclosure.

Recommended redesign direction before public release:

1. Choose a Google connection model first.
2. Decide whether public users are expected to configure their own Google Cloud
   OAuth client or use a hosted broker.
3. Define refresh token, expiry, scope, reconnect, and revoke behavior.
4. Choose an OpenAI key storage model, preferably supporting a
   `wp-config.php`/environment constant path even if database storage remains
   available.
5. Update admin UI, readme External Services disclosure, privacy/support docs,
   and release decision evidence after implementation.

## 7. Public Release Decision Gates

Do not move from `Hold` to `Go` until the following are complete:

- Google OAuth or alternate Google connection model is selected.
- Token lifecycle requirements are implemented or consciously scoped for the
  selected release audience.
- Refresh token or long-lived credential storage policy is selected and tested.
- Reconnect, disconnect, and revoke behavior is implemented or clearly
  documented.
- Required Google scopes are documented and validated.
- Redirect URI behavior is documented and tested for OAuth models.
- Google app verification and support responsibilities are accepted.
- OpenAI key storage policy is selected and documented.
- External Services disclosure is updated for any hosted broker or proxy.
- Admin notices explain when data leaves the site and which service receives it.
- Static checks, dry-run package inspection, Plugin Check, and manual smoke/E2E
  evidence remain clean.

## 8. Evidence And Recording Policy

Release-readiness evidence may include:

- Status-level command results.
- Redacted screenshots.
- Redacted error categories.
- Confirmation that Settings fields do not redisplay saved credentials.
- Confirmation that external transmission requires intentional administrator
  actions.

Release-readiness evidence must not include:

- Credential values.
- Google access tokens or refresh tokens.
- OpenAI API keys.
- Authorization headers.
- Full request bodies.
- Full AI payload bodies.
- Raw GA4 API responses.
- Raw OpenAI API responses.
- Full generated report text.
- Database exports containing credential options.

## 9. What This Step Does Not Implement

This step intentionally does not implement:

- Google OAuth flow.
- Google refresh token handling.
- Token expiry tracking.
- Scope validation.
- Reconnect or revoke UI.
- Service account support.
- Hosted OAuth broker.
- OpenAI hosted proxy.
- Credential encryption.
- Secret manager integration.
- Database schema or option migration.
- Settings UI changes.
- GA4 request changes.
- OpenAI request changes.
- AI payload structure changes.
- Transient behavior changes.
- Composer install/update.
- PHPCS or Plugin Check remediation.
- Release zip publication.
- SVN or WordPress.org submission actions.

## 10. Safety Notes

This step does not perform GA4 API communication, OpenAI API communication,
other external API communication, Composer install/update, `phpcbf`, SVN
operations, GitHub release operations, WordPress.org publication actions, or
release zip publication.

This document intentionally does not include credential values, access tokens,
refresh tokens, OpenAI API keys, Authorization headers, full request bodies, full
AI payload bodies, raw GA4 responses, raw OpenAI responses, or full generated
report text.
