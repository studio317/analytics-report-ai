# Repository Agent Instructions

## Project identity

This repository contains the WordPress plugin:

- Display name: Studio317 Report Drafts for Google Analytics
- Public plugin slug: `studio317-report-drafts-google-analytics`
- Main plugin file: `studio317-report-drafts-google-analytics.php`
- Text domain: `studio317-report-drafts-google-analytics`
- Minimum WordPress version: 7.0
- Minimum PHP version: 7.4

The GitHub repository retains the historical repository name
`studio317/analytics-report-ai`.

Do not rename the GitHub repository, Git remote, plugin slug, main plugin file,
or text domain unless explicitly requested.

## Architecture boundaries

Preserve the current architecture unless the task explicitly requires a change.

- AI generation uses the WordPress AI Client.
- AI provider credentials and model configuration are managed through the
  WordPress Connectors settings.
- Do not add a direct OpenAI API integration, API key field, or model selector
  to this plugin.
- Google OAuth credentials and tokens must remain hidden from normal status
  output and logs.
- Do not restore the retired manual Google access-token fallback.
- Do not add provider-side token revocation without an explicitly approved
  design and implementation task.
- Report output language is based on the current WordPress user language, with
  site-language and English fallbacks.

## Local paths

Source repository:

`/mnt/studio317-data/development/projects/studio317-report-drafts-google-analytics`

Dedicated development Compose environment:

`/mnt/studio317-data/development/docker/studio317-report-drafts-google-analytics`

Development site:

`http://127.0.0.1:18082/`

Shared release-package validation environment:

`/mnt/studio317-data/development/docker/wp-dev-check`

Release-candidate storage:

`/mnt/studio317-data/development/releases/wp-dev-check`

The source repository is bind-mounted read-only into WordPress containers.
Edit source files only in the host repository.

## Required workflow

Before changing files:

1. Run `git status --short --branch`.
2. Inspect the exact target files and nearby code.
3. Confirm that the requested change fits the current architecture.
4. Keep the change narrowly scoped.

While changing files:

- Prefer the smallest practical diff.
- Do not perform unrelated cleanup or refactoring.
- Do not reformat entire files.
- Do not run project-wide `phpcbf`.
- Preserve compatibility with PHP 7.4 and later.
- Preserve WordPress Coding Standards conventions already used by the project.
- Do not update dependency versions unless explicitly requested.
- Do not edit historical files under `docs/maturation/` unless the task
  specifically concerns those records.

After changing files:

1. Run targeted PHP syntax checks.
2. Run `git diff --check`.
3. Run relevant PHPCS checks.
4. Review `git diff` before reporting completion.
5. Report changed files, tests performed, test results, and anything not
   performed.

## Standard validation commands

Composer metadata:

```bash
composer validate --strict
```

Main PHP syntax:

```bash
php -l studio317-report-drafts-google-analytics.php
find includes -name '*.php' -print0 | xargs -0 -n1 php -l
```

Coding standards:

```bash
vendor/bin/phpcs --standard=phpcs.xml.dist --report=summary
```

Whitespace and patch integrity:

```bash
git diff --check
```

The PHPCS baseline at commit `f58fd39` is:

- 0 errors
- 62 warnings

Do not mass-fix the existing warnings. Identify newly introduced violations
separately.

## Security and privacy

Never read, print, copy into reports, commit, or expose:

- OAuth client secrets
- OAuth access or refresh tokens
- AI provider credentials
- WordPress option values containing credentials or tokens
- Raw GA4 API responses
- AI request payload JSON
- Generated report bodies containing private analytics information
- Browser Network evidence containing request or response data
- Database passwords or `.env` contents

Do not add credentials, tokens, customer data, generated reports, screenshots,
database dumps, or local environment files to Git.

Do not modify `.env` files unless explicitly requested.

## External operations

Do not perform any of the following without explicit approval:

- Google OAuth authorization or reconnection
- GA4 API requests
- AI provider requests
- Email transmission
- Browser automation
- External service configuration
- WordPress database or option changes unrelated to the requested task
- Docker Compose start, stop, rebuild, reset, or volume deletion
- APT or system package operations
- Service, firewall, Tailscale, Apache, PHP, or MariaDB configuration changes

## Release packaging

The existing local package command is:

```bash
./tools/build-release-zip-dry-run.sh
```

Follow `docs/RELEASE.md`, `.distignore`, and the build script as the source of
truth for package contents.

Release build output must remain outside the source repository.

A dry-run ZIP is only a local release candidate. It is not a formal release.

Validate the generated ZIP by installing that ZIP into `wp-dev-check`.
Do not substitute the mutable source bind mount for package validation.

Do not perform any of the following without explicit approval:

- Create or move a Git tag
- Create a GitHub release
- Push release artifacts
- Modify WordPress.org SVN trunk
- Create or modify a WordPress.org SVN tag
- Commit or publish to WordPress.org SVN

## Git operations

Do not run these operations unless explicitly requested:

- `git commit`
- `git push`
- `git pull --rebase`
- `git rebase`
- `git reset`
- `git checkout` or `git switch` that discards work
- force push
- branch deletion
- tag creation or deletion

Never discard existing user changes.

## Response language

Respond in Japanese unless the user explicitly requests another language.

Use exact file paths, commands, and test results. Clearly distinguish between:

- actions completed
- actions proposed
- actions not performed
