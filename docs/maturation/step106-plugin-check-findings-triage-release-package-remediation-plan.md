# Step 106: Plugin Check Findings Triage / Release Package Remediation Plan

## Step Summary

Step 106 triages the five Plugin Check errors recorded in Step 105.

The findings are classified as distribution/package contents findings, not as
runtime code issues. This step creates a remediation plan only. It does not
change files, rebuild packages, rerun Plugin Check, or modify release tooling.

Production PHP, JavaScript, CSS, `readme.txt`, `.distignore`, build scripts,
admin UI behavior, Settings save logic, GA4 client behavior, OpenAI client
behavior, credential storage, uninstall behavior, and package artifacts were
not changed.

Plugin Check was not rerun. No external API communication was performed.
GA4 Fetch and OpenAI Generate were not run. `wp-dev` was not used for Plugin
Check.

WordPress.org release remains `Hold`.

## Referenced Docs

- `docs/maturation/step105-isolated-plugin-check-results.md`
- `docs/maturation/step104-readme-privacy-wording-alignment-implementation-results.md`
- `docs/maturation/step102-payload-preview-raw-json-removal-implementation-results.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`

## Findings From Step 105

| Severity | Rule / Code | File | Interpretation |
|---|---|---|---|
| ERROR | `compressed_files` | `build/release-dry-run/analytics-report-ai-0.1.0.zip` | Release dry-run zip artifact should not be included in the checked distribution/source target. |
| ERROR | `hidden_files` | `.gitignore` | Source-control hidden file should not be included in the WordPress.org distribution package. |
| ERROR | `hidden_files` | `.distignore` | Distribution ignore file itself should not be included in the final package. |
| ERROR | `application_detected` | `phpcs.xml.dist` | Development/tooling config should not be included in the WordPress.org distribution package. |
| ERROR | `application_detected` | `tools/build-release-zip-dry-run.sh` | Build tooling script should not be included in the WordPress.org distribution package. |

## Triage Classification

| Classification | Result | Notes |
|---|---|---|
| Runtime blocker | No | No GA4/OpenAI runtime behavior finding was reported. |
| Admin UI blocker | No | No Report Builder or Settings UI behavior finding was reported. |
| GA4/OpenAI behavior blocker | No | No API request, payload structure, or response handling finding was reported. |
| Privacy/support wording blocker | No | Step 104 wording alignment remains separate from these package contents findings. |
| Release packaging blocker | Yes | The reported files should not be shipped in the WordPress.org distribution package. |
| WordPress.org readiness blocker | Yes | Readiness remains blocked until package contents are remediated and Plugin Check passes against the intended distribution target. |

## Remediation Strategy Options

| Option | Summary | Pros | Cons | Recommendation |
|---|---|---|---|---|
| Option A | Delete development files from source tree. | A raw source-tree Plugin Check run may pass more easily. | Loses useful GitHub source repository tooling and ignore policy files. This is not ideal for development. | Do not adopt as default. |
| Option B | Improve `.distignore` and release package build process. | Preserves source repo tooling while excluding non-distribution files from release packages. | Plugin Check must be run against the built package or a clean distribution directory, not the raw source tree. | Strong candidate. |
| Option C | Move build artifacts outside plugin tree. | Avoids `build/release-dry-run/*.zip` being scanned as part of the plugin. | Requires build script or path policy updates. | Strong candidate, especially for zip artifacts. |
| Option D | Run Plugin Check against a clean release package or extracted distribution directory. | Matches WordPress.org release reality more closely than checking the raw source tree. | Requires reliable package generation and install/check flow in `wp-dev-check`. | Strong candidate. |
| Option E | Keep current source tree and ignore Plugin Check errors. | No immediate work. | Not acceptable for WordPress.org readiness. The release blocker would remain open. | Do not adopt. |

## Recommended Remediation Plan

Recommended direction:

- Keep development-only files in the GitHub source repository where useful.
- Do not include development-only files in the WordPress.org release package.
- Keep source repository files such as `.gitignore`, `.distignore`,
  `phpcs.xml.dist`, and `tools/*` available for development unless a later
  release policy explicitly removes them from source.
- Exclude development-only files from the final distribution package.
- Do not keep release zip artifacts such as `build/release-dry-run/*.zip`
  inside the plugin tree scanned by Plugin Check, or ensure the Plugin Check
  target excludes build artifacts.
- Review `.distignore` so the final distribution package excludes at least:
  - `.gitignore`
  - `.distignore`
  - `phpcs.xml.dist`
  - `tools/`
  - `build/`
  - `docs/maturation/` if the public package policy keeps maturation docs out
    of distributed packages
  - any other development-only files
- Run the next Plugin Check against a clean release package or clean
  distribution directory rather than the raw source tree.

Step 106 does not implement these changes. It does not change `.distignore`,
build scripts, package output paths, package contents, or Plugin Check target
installation.

## Proposed Next Implementation Steps

Recommended next step:

```text
Step 107: Release package contents remediation implementation
```

Scope:

- Implement `.distignore` / build package exclusion policy updates.
- Confirm or adjust build artifact location.
- Confirm release dry-run package generation method.
- Keep production runtime code unchanged.
- Avoid GA4/OpenAI behavior changes.
- Avoid admin UI behavior changes.

Then:

```text
Step 108: Isolated Plugin Check rerun against clean release package or clean distribution target
```

Scope:

- Use `wp-dev-check` only.
- Check the remediated package or clean distribution target, not the raw source
  tree.
- Record findings in docs.
- Do not mix Plugin Check rerun with unrelated production code changes.

## Risk Notes

- Running Plugin Check against the raw source tree can keep producing
  release-packaging errors for development tooling files.
- WordPress.org readiness should ultimately be judged against the package
  contents that will actually be distributed.
- `.distignore` itself can trigger a hidden-file finding if it is included in
  the final package.
- Build zip files inside the plugin tree can trigger `compressed_files`
  findings.
- `docs/maturation/` package inclusion should be governed by the distribution
  package policy; if maturation docs are not intended for public packages, the
  release process should exclude them.
- A package generation process that excludes source-only files must still
  preserve runtime files such as `analytics-report-ai.php`, `includes/`,
  `assets/`, and `readme.txt`.

## Explicit Non-goals

This step does not:

- change production code,
- change PHP, JavaScript, or CSS,
- change `readme.txt`,
- change `.distignore`,
- change build scripts,
- rebuild release packages,
- rerun Plugin Check,
- run Plugin Check in `wp-dev`,
- call external APIs,
- run GA4 Fetch,
- run OpenAI Generate,
- inspect or display plugin settings option values,
- inspect or display credentials,
- record raw payloads,
- record raw request bodies,
- record raw response bodies,
- record generated report bodies,
- capture screenshots,
- inspect browser Network tab data.

## Security / Evidence Notes

This document records only status-level Plugin Check triage and package
remediation planning.

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
- no tracked production files change,
- only this Step 106 documentation file is added.
