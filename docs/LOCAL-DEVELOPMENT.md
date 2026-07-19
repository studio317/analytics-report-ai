# Local Development

This document describes the Ubuntu local-development workflow for
Studio317 Report Drafts for Google Analytics.

## Repository

Source repository:

```text
/mnt/studio317-data/development/projects/studio317-report-drafts-google-analytics
```

GitHub repository:

```text
studio317/analytics-report-ai
```

The GitHub repository retains its historical name. The public WordPress plugin
slug remains:

```text
studio317-report-drafts-google-analytics
```

## Dedicated Development Environment

Docker Compose configuration:

```text
/mnt/studio317-data/development/docker/studio317-report-drafts-google-analytics
```

Development site:

```text
http://127.0.0.1:18082/
```

WordPress admin:

```text
http://127.0.0.1:18082/wp-admin/
```

The host repository is mounted read-only into the WordPress and WP-CLI
containers. Source files must be edited in the host repository, not from inside
a container or the WordPress administration screen.

### Start

```bash
cd /mnt/studio317-data/development/docker/studio317-report-drafts-google-analytics
docker compose up -d
docker compose ps
```

### Stop

```bash
cd /mnt/studio317-data/development/docker/studio317-report-drafts-google-analytics
docker compose down
```

`docker compose down` preserves the WordPress and MariaDB named volumes.

Do not run the following command unless a complete local reset has been
explicitly approved:

```bash
docker compose down -v
```

### WP-CLI

WP-CLI runs as an on-demand container:

```bash
cd /mnt/studio317-data/development/docker/studio317-report-drafts-google-analytics
docker compose run --rm wpcli <command>
```

Example:

```bash
docker compose run --rm wpcli \
  plugin get studio317-report-drafts-google-analytics \
  --fields=name,status,version \
  --format=table
```

## Standard Validation

Run the VS Code task:

```text
Project: Validate
```

The task executes these checks in sequence:

```bash
composer validate --strict

php -l studio317-report-drafts-google-analytics.php
find includes -name '*.php' -print0 | xargs -0 -n1 php -l

vendor/bin/phpcs \
  --standard=phpcs.xml.dist \
  --report=summary \
  --warning-severity=0

git diff --check
```

The full PHPCS summary can be run separately:

```bash
vendor/bin/phpcs \
  --standard=phpcs.xml.dist \
  --report=summary
```

Baseline at commit `f58fd39`:

```text
0 errors
62 warnings
```

Existing warnings must not be mass-fixed as part of an unrelated task.

## Debug Log

The development environment uses:

```php
WP_DEBUG=true
WP_DEBUG_LOG=true
WP_DEBUG_DISPLAY=false
SCRIPT_DEBUG=true
```

Check the WordPress debug log without changing it:

```bash
cd /mnt/studio317-data/development/docker/studio317-report-drafts-google-analytics

docker compose exec -T wordpress sh -lc '
if [ -s /var/www/html/wp-content/debug.log ]; then
    tail -n 50 /var/www/html/wp-content/debug.log
else
    echo "debug.log: no entries"
fi
'
```

Do not commit logs, credentials, tokens, raw GA4 responses, AI request payloads,
or generated reports.

## Shared Release-Package Validation

Shared Plugin Check environment:

```text
/mnt/studio317-data/development/docker/wp-dev-check
```

Shared validation site:

```text
http://127.0.0.1:18081/
```

Release-candidate storage:

```text
/mnt/studio317-data/development/releases/wp-dev-check
```

The shared environment is for installing and checking generated release ZIPs.
Do not use the mutable development source bind mount as a substitute for
release-package validation.

### Start

```bash
cd /mnt/studio317-data/development/docker/wp-dev-check
docker compose up -d
docker compose ps
```

### Stop

```bash
cd /mnt/studio317-data/development/docker/wp-dev-check
docker compose down
```

### Build a Local Release Candidate

From the source repository:

```bash
cd /mnt/studio317-data/development/projects/studio317-report-drafts-google-analytics

ANALYTICS_REPORT_AI_RELEASE_BUILD_ROOT="/mnt/studio317-data/development/releases/wp-dev-check/<unique-build-directory>" \
  ./tools/build-release-zip-dry-run.sh
```

Use a new build directory for each candidate. Do not overwrite evidence from a
previous candidate unintentionally.

The build script performs package structure, metadata, PHP syntax, exclusion,
and credential-pattern checks.

A generated dry-run ZIP is a local candidate only. It is not a formal release.

### Plugin Check

After installing the candidate ZIP into `wp-dev-check`, run:

```bash
cd /mnt/studio317-data/development/docker/wp-dev-check

docker compose run --rm wpcli \
  plugin check studio317-report-drafts-google-analytics \
  --require=/var/www/html/wp-content/plugins/plugin-check/cli.php
```

After validation, deactivate and delete the candidate plugin so that the shared
environment is ready for the next package:

```bash
docker compose run --rm wpcli \
  plugin deactivate studio317-report-drafts-google-analytics

docker compose run --rm wpcli \
  plugin delete studio317-report-drafts-google-analytics
```

## External Services

Google OAuth authorization, GA4 requests, AI provider requests, and provider
configuration are not part of routine source validation.

Do not expose or commit:

- Google OAuth client secrets
- OAuth access or refresh tokens
- AI provider credentials
- WordPress option values containing credentials
- `.env` contents
- database passwords
- raw external API responses
- generated private analytics reports

External-service operations require an explicitly approved test task.
