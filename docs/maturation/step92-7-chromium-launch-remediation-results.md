# Step 92.7: Chromium Launch Remediation Results

## Step Summary

Step 92.7 diagnosed the headless Chromium launch failure that remained after
Step 92.6.

This step used only the existing repo-external browser tooling environment. It
did not install or update Node.js, npm, Playwright, Chromium, or system
packages. It did not run Plugin Check, did not touch `wp-dev-check`, and did not
perform WordPress admin login or no-data admin smoke testing.

Production PHP, `readme.txt`, admin UI, JavaScript, CSS, Settings save logic,
GA4 client, OpenAI client, Composer/npm configuration, and release package files
were not changed.

No external API communication was performed. WordPress.org release remains
`Hold`.

## Purpose

The purpose was to determine whether a minimal launch option or executable
adjustment could make the existing Playwright / Chromium installation usable
for a later browser smoke test against:

```text
http://localhost/wp-dev
```

The remaining target checks are:

- `NO-DATA-11`: browser rendering of warnings.
- `NO-DATA-12`: Payload Preview warning visibility.

## Referenced Docs

- `docs/maturation/step92-admin-browser-smoke-no-data-warnings-results.md`
- `docs/maturation/step92-5-browser-automation-environment-setup-results.md`
- `docs/maturation/step92-6-browser-automation-setup-verification-results.md`
- `docs/maturation/step90-5-plugin-check-environment-isolation-results.md`
- `docs/maturation/step91-ga4-empty-no-data-handling-implementation-results.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`

## Repo Status Pre-check

Initial repository status before adding this document:

```text
No output from git status --short --untracked-files=all.
```

No files were added to staging and no commit was made.

## Browser Tooling Status

Browser tools directory:

```text
/var/www/html/browser-smoke-tools
```

Observed status:

| Check | Result |
|---|---|
| `node --version` | `v22.22.3` |
| `npm --version` | `10.9.8` |
| `npx --version` | `10.9.8` |
| `npx playwright --version` | `Version 1.60.0` |
| `package.json` in tools directory | Present |
| `package-lock.json` in tools directory | Present |
| `node_modules` in tools directory | Present |
| Analytics Report AI repo `node_modules` | Not present |

Result: **Pass**. Tooling is present outside the Analytics Report AI
repository.

No `npm install`, `npx playwright install`, `apt install`, or `sudo apt-get`
command was run.

## Playwright / Chromium Executable Status

Playwright status:

```text
PLAYWRIGHT_REQUIRE=OK
PLAYWRIGHT_CHROMIUM_NAME=chromium
PLAYWRIGHT_CHROMIUM_EXECUTABLE=/home/watabe/.cache/ms-playwright/chromium-1223/chrome-linux64/chrome
```

Chromium executable status:

| Check | Result |
|---|---|
| Chromium executable path | `/home/watabe/.cache/ms-playwright/chromium-1223/chrome-linux64/chrome` |
| Executable bit | `CHROMIUM_EXECUTABLE=YES` |
| `chrome --version` | `Google Chrome for Testing 148.0.7778.96` |

Playwright cache status:

```text
Playwright version: 1.60.0
Browsers:
  /home/watabe/.cache/ms-playwright/chromium-1223
  /home/watabe/.cache/ms-playwright/chromium_headless_shell-1223
  /home/watabe/.cache/ms-playwright/ffmpeg-1011
References:
  /var/www/html/browser-smoke-tools/node_modules/playwright-core
```

Headless shell executable status:

| Check | Result |
|---|---|
| Headless shell executable path | `/home/watabe/.cache/ms-playwright/chromium_headless_shell-1223/chrome-headless-shell-linux64/chrome-headless-shell` |
| Executable bit | `HEADLESS_SHELL_EXECUTABLE=YES` |
| `chrome-headless-shell --version` | `Google Chrome for Testing 148.0.7778.96` |

## Dependency Status

`ldd` was checked for both the Chromium executable and the Chromium headless
shell executable.

| Executable | Missing dependency result |
|---|---|
| Chromium executable | No `not found` entries reported |
| Chromium headless shell | No `not found` entries reported |

`/dev/shm` was also checked and did not appear capacity-constrained:

```text
/dev/shm size: 1.9G
```

One namespace-related status file was not present:

```text
/proc/sys/kernel/unprivileged_userns_clone: not present
```

`/proc/sys/user/max_user_namespaces` was readable:

```text
15103
```

These status checks suggest the immediate failure is not a missing shared
library or `/dev/shm` capacity problem.

## Direct Chromium Execution Checks

Direct Chromium execution with `about:blank` failed before producing page
output:

```text
Chromium direct execution: BLOCKED
Reason category: crashpad setsockopt Operation not permitted; command dumped core.
```

Adding crash reporter disable options and using a temporary user-data directory
did not change the failure category:

```text
Chromium direct execution with crash reporter disable options: BLOCKED
Reason category: crashpad setsockopt Operation not permitted; command dumped core.
```

Direct Chromium headless shell execution also failed before producing page
output:

```text
Headless shell direct execution: BLOCKED
Reason category: sandbox_host_linux shutdown Operation not permitted; command dumped core.
```

Adding `--no-zygote` and `--single-process` did not change the headless shell
failure category.

## Launch Option Matrix Results

Target URL:

```text
http://localhost/wp-dev
```

The following Playwright launch cases were tried. Each case recorded only
status-level output: result, HTTP status when available, title-present state
when available, and error category when blocked.

| Case | Result | Status | Title present | Error category |
|---|---|---|---|---|
| `default` | Blocked | Not reached | Not reached | `browserType.launch: Target page, context or browser has been closed` |
| `chromium-shell-new-headless-disabled-sandbox` | Blocked | Not reached | Not reached | `browserType.launch: Target page, context or browser has been closed` |
| `legacy-headless-disabled-gpu-sandbox` | Blocked | Not reached | Not reached | `browserType.launch: Target page, context or browser has been closed` |
| `headless-shell-executable-disabled-sandbox` | Blocked | Not reached | Not reached | `browserType.launch: Target page, context or browser has been closed` |
| `headless-shell-executable-no-zygote-single-process` | Blocked | Not reached | Not reached | `browserType.launch: Target page, context or browser has been closed` |

## Browser Precheck Result

Result: **Blocked**.

No launch option reached `http://localhost/wp-dev`. Because Chromium never
completed launch, no HTTP response status and no title-present result could be
recorded.

The strongest status-level cause candidates are:

- Chromium executable is present but exits immediately.
- Permission / sandbox / namespace limitation in the current runtime.
- Crashpad-related permission failure for the default Chromium executable.
- Sandbox host permission failure for the Chromium headless shell executable.
- Browser runtime limitation in the current CODEX / WSL environment.

No system package installation or Chromium reinstallation was attempted.

## wp-dev Plugin Status

WP-CLI checks were run only in:

```text
/var/www/html/wp-dev
```

| Plugin | Result |
|---|---|
| `analytics-report-ai` | Active, version `0.1.0` |
| `plugin-check` | Inactive, version `2.0.0` |

`wp-dev-check` was not touched.

## Admin Login / No-data Smoke

Not performed.

This step did not log in to WordPress admin, did not open admin screens, did not
click GA4 Fetch, did not click OpenAI Generate, and did not run the no-data
warning browser smoke scenarios.

The remaining Step 92 browser checks are still blocked:

- `NO-DATA-11`: browser rendering of warnings.
- `NO-DATA-12`: Payload Preview warning visibility.

## Safety Confirmation

| Safety item | Result |
|---|---|
| Production PHP changed | No |
| `readme.txt` changed | No |
| Admin UI / JS / CSS changed | No |
| Settings save logic changed | No |
| GA4 client / OpenAI client changed | No |
| Composer/npm configuration in Analytics Report AI repo changed | No |
| Analytics Report AI repo `node_modules` created | No |
| External API communication | No |
| GA4 Fetch / OpenAI Generate executed | No |
| Plugin Check executed | No |
| `wp-dev-check` touched | No |
| WordPress admin login performed | No |
| Credentials / API keys / access tokens recorded | No |
| Authorization headers recorded | No |
| Settings option values recorded | No |
| Request bodies / payload JSON / raw responses recorded | No |
| Generated report body recorded | No |
| Analytics values, page paths, traffic sources, or city values recorded | No |
| Nonce / cookie / session values recorded | No |
| Screenshots captured | No |

## Known Limitations

- Chromium launch remains blocked in this environment.
- The local WordPress URL was not reached through headless Chromium.
- Real browser rendering of Step 91 no-data warning UI remains unverified.
- Payload Preview warning visibility remains unverified in a real browser.
- Admin login and form-flow smoke testing were intentionally not performed.
- Plugin Check was intentionally not run.
- WordPress.org release remains `Hold`.

## Next Step Recommendation

Recommended next step:

```text
Step 92.8: Manual admin browser smoke in a browser-capable environment
```

The current CODEX environment has Playwright and cached Chromium artifacts, but
Chromium exits immediately with permission-related runtime failures. The lowest
risk next step is to run the admin browser smoke in a normal browser-capable
environment and record only status-level results.

If browser automation remains desired in CODEX, investigate the runtime
permission / sandbox / namespace constraints outside the Analytics Report AI
repository, without changing production plugin code or installing packages in
the plugin repository.
