# Step 90.5: Plugin Check Environment Isolation Results

## Step Summary

This preparation step separates the normal Analytics Report AI functional QA
environment from the Plugin Check / WordPress.org readiness environment.

No production plugin code, `readme.txt`, admin UI, JavaScript, CSS, settings
save logic, GA4 client, OpenAI client, Composer/npm configuration, or release
package files were changed. Plugin Check was not executed.

WordPress.org release status remains **Hold**.

## Purpose

`wp-dev` is used for normal functional verification, including staged admin
flows such as GA4 Fetch, Payload Preview, OpenAI Generate, textarea editing,
copy behavior, and future no-data handling QA.

`wp-dev-check` is reserved for Plugin Check and WordPress.org readiness /
static-ish compliance checks. Keeping Plugin Check out of the normal functional
QA environment reduces admin-screen, hook, dependency, and check-result noise.

## Environment Roles

| Environment | URL | Intended role | Plugin Check role |
|---|---|---|---|
| `/var/www/html/wp-dev` | `http://localhost/wp-dev` | Normal Analytics Report AI functional QA. | Installed but inactive after this step. |
| `/var/www/html/wp-dev-check` | `http://localhost/wp-dev-check` | Plugin Check / WordPress.org readiness checks. | Installed and active. |

## Operations Performed

- Confirmed both WordPress environments exist.
- Confirmed `plugin-check` exists in both environments.
- Confirmed `analytics-report-ai` was missing from `wp-dev-check` before this
  step.
- Deactivated `plugin-check` in `wp-dev`.
- Created an `analytics-report-ai` symlink in `wp-dev-check`.
- Activated `analytics-report-ai` in `wp-dev-check`.
- Confirmed final plugin status in both environments.

`plugin-check` was already present in `wp-dev-check`, so no plugin install,
download, copy, or move was required. The `wp-dev` copy was left installed but
inactive as the smaller, reversible change; it can be removed in a future
cleanup step if the team wants `wp-dev` to contain no Plugin Check files at all.

## Symlink State

Expected final symlink:

```text
/var/www/html/wp-dev-check/wp-content/plugins/analytics-report-ai
  -> /var/www/html/analytics-report-ai
```

Observed final state:

```text
lrwxrwxrwx /var/www/html/wp-dev-check/wp-content/plugins/analytics-report-ai -> /var/www/html/analytics-report-ai
```

## Plugin Check Placement

| Environment | Path | Placement state | Activation state |
|---|---|---|---|
| `wp-dev` | `/var/www/html/wp-dev/wp-content/plugins/plugin-check` | Present | Inactive |
| `wp-dev-check` | `/var/www/html/wp-dev-check/wp-content/plugins/plugin-check` | Present | Active |

## Plugin Status Summary

| Environment | Plugin | Status | Version observed |
|---|---|---|---|
| `wp-dev` | `analytics-report-ai` | Active | `0.1.0` |
| `wp-dev` | `plugin-check` | Inactive | `2.0.0` |
| `wp-dev-check` | `analytics-report-ai` | Active | `0.1.0` |
| `wp-dev-check` | `plugin-check` | Active | `2.0.0` |

## Future Usage

- Use `wp-dev` for normal feature QA and controlled admin workflows.
- Use `wp-dev-check` for Plugin Check and WordPress.org readiness checks.
- Do not run Plugin Check in `wp-dev` unless intentionally reintroducing it for
  a specific investigation.
- Do not enter real credentials in `wp-dev-check`.
- Do not use `wp-dev-check` for GA4 Fetch, OpenAI Generate, OAuth flows, or
  external API smoke tests.

## Safety Notes

- No settings option values were read or recorded.
- No credentials, API keys, access tokens, authorization headers, nonces,
  cookies, request bodies, AI payloads, raw API responses, generated reports, or
  analytics values were displayed or recorded.
- No GA4, OpenAI, Google OAuth, or other external API communication was
  performed.
- Plugin Check was not executed.
- WordPress.org release remains **Hold**.
