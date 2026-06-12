# Step 107: Release Package Contents Remediation Implementation Results

## Step Summary

Step 107 implemented release package contents remediation for the Plugin Check
distribution/package findings recorded in Step 105.

The implementation keeps development-only files in the GitHub source
repository, while ensuring the dry-run release package is built from a clean
distribution stage and that dry-run zip artifacts are not left inside the
plugin source tree.

Production runtime code was not changed. The runtime plugin files
`analytics-report-ai.php`, `includes/`, and `assets/` were not modified.
`readme.txt` was not changed. Admin UI behavior, Settings save logic, GA4
client behavior, OpenAI client behavior, OAuth behavior, credential storage
behavior, and uninstall behavior were not changed.

Plugin Check was not rerun. `wp-dev-check` was not touched. GA4 Fetch and
OpenAI Generate were not run. No external API communication was performed.

WordPress.org release remains `Hold` until Plugin Check is rerun against a
clean release package or clean distribution target.

## Changed Files

- `tools/build-release-zip-dry-run.sh`
- `docs/maturation/step107-release-package-contents-remediation-implementation-results.md`

## Referenced Docs

- `docs/maturation/step106-plugin-check-findings-triage-release-package-remediation-plan.md`
- `docs/maturation/step105-isolated-plugin-check-results.md`
- `docs/maturation/step104-readme-privacy-wording-alignment-implementation-results.md`
- `docs/maturation/step86-support-debug-redaction-policy-consolidation.md`

## `.distignore` / Package Exclusion Policy

`.distignore` was reviewed and did not require a content change in this step.

The existing exclusion policy already covers the Step 105 package findings:

| Step 105 finding file | Existing `.distignore` coverage |
|---|---|
| `.gitignore` | `.gitignore` |
| `.distignore` | `.distignore` |
| `phpcs.xml.dist` | `phpcs.xml.dist` |
| `tools/build-release-zip-dry-run.sh` | `tools/` |
| `build/release-dry-run/analytics-report-ai-0.1.0.zip` | `build/` and `*.zip` |

The existing policy also excludes `docs/maturation/` from release packages,
which matches the current public package posture for maturation records.

Runtime files remain intended for the package:

- `analytics-report-ai.php`
- `includes/`
- `assets/`
- `readme.txt`

## Build Artifact Location / Build Script Policy

`tools/build-release-zip-dry-run.sh` was updated so dry-run build output is no
longer written under the plugin source tree by default.

New default output root:

```text
${TMPDIR:-/tmp}/analytics-report-ai-release-dry-run
```

The script now:

- supports overriding the dry-run build root with
  `ANALYTICS_REPORT_AI_RELEASE_BUILD_ROOT`,
- resolves the build root to an absolute path,
- fails if the resolved build root is inside the plugin source tree,
- refuses to write through a symlinked build root,
- writes the stage directory, zip file, and zip contents list outside the
  plugin source tree by default.

Legacy ignored dry-run artifacts that were present under
`build/release-dry-run/` were moved out of the plugin source tree to `/tmp`
for this remediation step.

## Step 105 Findings Mapping

| Step 105 Rule / Code | File | Step 107 remediation |
|---|---|---|
| `compressed_files` | `build/release-dry-run/analytics-report-ai-0.1.0.zip` | Build script default output moved outside plugin source tree. Existing ignored dry-run artifact moved out of source tree. `.distignore` already excludes `build/` and `*.zip`. |
| `hidden_files` | `.gitignore` | `.distignore` already excludes `.gitignore` from package staging. |
| `hidden_files` | `.distignore` | `.distignore` already excludes itself from package staging. |
| `application_detected` | `phpcs.xml.dist` | `.distignore` already excludes `phpcs.xml.dist` from package staging. |
| `application_detected` | `tools/build-release-zip-dry-run.sh` | `.distignore` already excludes `tools/` from package staging. |

## Dry-run Package Verification

The dry-run release script was executed once to confirm the new output
location and staged package contents.

Command:

```text
./tools/build-release-zip-dry-run.sh
```

Result:

```text
Pass. Dry-run package created outside the plugin source tree.
```

Observed output location:

```text
/tmp/analytics-report-ai-release-dry-run/analytics-report-ai-0.1.0.zip
```

The environment did not have the `zip` command available, so the script used
the existing `python3 -m zipfile` fallback.

The staged package PHP syntax checks passed. The high-risk credential pattern
scan completed without matches. Documentation keyword warnings were reported
for credential-related terms in source text, but the script treats those as
review warnings rather than high-risk secret matches.

Observed zip contents were limited to:

```text
analytics-report-ai/
analytics-report-ai/analytics-report-ai.php
analytics-report-ai/assets/
analytics-report-ai/assets/css/
analytics-report-ai/assets/css/admin.css
analytics-report-ai/assets/js/
analytics-report-ai/assets/js/admin.js
analytics-report-ai/includes/
analytics-report-ai/includes/class-admin.php
analytics-report-ai/includes/class-ga4-client.php
analytics-report-ai/includes/class-openai-client.php
analytics-report-ai/includes/class-plugin.php
analytics-report-ai/includes/class-prompt-builder.php
analytics-report-ai/includes/class-report-builder.php
analytics-report-ai/includes/class-report-data-formatter.php
analytics-report-ai/includes/class-settings.php
analytics-report-ai/includes/functions-utils.php
analytics-report-ai/readme.txt
```

The observed zip contents did not include:

- `.gitignore`
- `.distignore`
- `phpcs.xml.dist`
- `tools/`
- `build/`
- `docs/maturation/`
- `vendor/`
- `node_modules/`

After the dry run, no zip artifact remained under the plugin source tree
`build/` path.

## Unchanged Runtime / Production Files

This step did not change:

- `analytics-report-ai.php`
- `includes/`
- `assets/`
- `readme.txt`
- production PHP runtime behavior
- JavaScript
- CSS
- admin UI behavior
- Settings save logic
- GA4 client behavior
- OpenAI client behavior
- OAuth behavior
- credential storage behavior
- uninstall behavior
- version number
- Stable tag

## Security / Evidence Notes

This step did not display, inspect, or record:

- real credentials,
- API keys,
- access tokens,
- Authorization headers,
- plugin settings option values,
- GA4 Property ID real values,
- hostname/domain real values,
- analytics values,
- page path / source / city values,
- request bodies,
- AI payload JSON,
- OpenAI request bodies,
- raw GA4/OpenAI response bodies,
- generated report bodies,
- screenshots,
- browser Network tab data,
- cookies,
- sessions,
- nonces.

## Commands Executed

```text
git status --short --untracked-files=all
sed -n '1,240p' .distignore
sed -n '1,260p' tools/build-release-zip-dry-run.sh
find build -maxdepth 3 -type f | sort
rg -n "^\\.gitignore$|^\\.distignore$|^phpcs\\.xml\\.dist$|^tools/$|^build/$|^docs/maturation/|zip|tar|vendor|runtime" .distignore
./tools/build-release-zip-dry-run.sh
zipinfo -1 /tmp/analytics-report-ai-release-dry-run/analytics-report-ai-0.1.0.zip
git diff --name-only -- '*.php' 'assets/*.js' 'assets/*.css' 'readme.txt' 'analytics-report-ai.php'
git diff --check
git diff --stat
git diff --name-only
git status --short --untracked-files=all
```

## Verification Results

```text
git diff --check
```

Result:

```text
No output; whitespace check passed.
```

```text
git diff --stat
```

Result:

```text
tools/build-release-zip-dry-run.sh | 19 +++++++++++--------
1 file changed, 11 insertions(+), 8 deletions(-)
```

Note: `docs/maturation/step107-release-package-contents-remediation-implementation-results.md`
is a new untracked file, so it is listed by `git status` rather than plain
`git diff --stat`.

```text
git diff --name-only
```

Result:

```text
tools/build-release-zip-dry-run.sh
```

```text
git status --short --untracked-files=all
```

Result:

```text
 M tools/build-release-zip-dry-run.sh
?? docs/maturation/step107-release-package-contents-remediation-implementation-results.md
```

```text
git diff --name-only -- '*.php' 'assets/*.js' 'assets/*.css' 'readme.txt' 'analytics-report-ai.php'
```

Result:

```text
No output; runtime PHP / JS / CSS / readme / main plugin files were not changed.
```

```text
find build -maxdepth 3 -type f | sort
```

Result:

```text
No output; no dry-run zip artifact remains in the plugin source tree build path.
```

## Not Executed

- Plugin Check rerun.
- Plugin Check in `wp-dev`.
- `wp-dev-check` operations.
- GA4 Fetch.
- OpenAI Generate.
- External API calls.
- Browser screenshots.
- Browser Network tab inspection.
- Credential or option value inspection.
- Production runtime code changes.

## Next Step Recommendation

Recommended next step:

```text
Step 108: Isolated Plugin Check rerun against clean release package or clean distribution target
```

Scope:

- Use `wp-dev-check` only.
- Run Plugin Check against the clean release package or clean distribution
  target produced by the remediation path.
- Do not run Plugin Check against the raw source tree.
- Record findings in docs.
- Do not mix Plugin Check rerun with unrelated production code changes.
