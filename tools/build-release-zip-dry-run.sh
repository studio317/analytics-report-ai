#!/usr/bin/env bash
set -euo pipefail

PLUGIN_SLUG="studio317-report-drafts-google-analytics"

fail() {
	printf 'ERROR: %s\n' "$*" >&2
	exit 1
}

info() {
	printf '[dry-run] %s\n' "$*"
}

require_command() {
	command -v "$1" >/dev/null 2>&1 || fail "Required command not found: $1"
}

extract_plugin_version() {
	awk -F ':' '
		/^[[:space:]]*\* Version:/ {
			value = $2
			sub(/^[[:space:]]+/, "", value)
			sub(/[[:space:]]+$/, "", value)
			print value
			exit
		}
	' "$1"
}

extract_stable_tag() {
	awk -F ':' '
		/^Stable tag:/ {
			value = $2
			sub(/^[[:space:]]+/, "", value)
			sub(/[[:space:]]+$/, "", value)
			print value
			exit
		}
	' "$1"
}

require_zip_entry() {
	local entry="$1"

	grep -Fxq "$entry" "$ZIP_CONTENTS_FILE" || fail "Missing required zip entry: $entry"
}

require_zip_prefix() {
	local prefix="$1"

	grep -Fq "$prefix" "$ZIP_CONTENTS_FILE" || fail "Missing required zip path prefix: $prefix"
}

reject_zip_prefix() {
	local prefix="$1"

	if grep -Fq "$prefix" "$ZIP_CONTENTS_FILE"; then
		fail "Excluded path is present in zip: $prefix"
	fi
}

reject_zip_entry() {
	local entry="$1"

	if grep -Fxq "$entry" "$ZIP_CONTENTS_FILE"; then
		fail "Excluded file is present in zip: $entry"
	fi
}

scan_stage_patterns() {
	local mode="$1"
	shift

	local pattern
	local file
	local rel_file
	local found=0

	for pattern in "$@"; do
		while IFS= read -r file; do
			rel_file="${file#"$STAGE_DIR/"}"
			if [[ "$mode" == "fail" ]]; then
				printf 'ERROR: %s: potential secret pattern found.\n' "$rel_file" >&2
			else
				printf 'WARNING: %s: credential-related documentation keyword found; review if unexpected.\n' "$rel_file" >&2
			fi
			found=1
		done < <(LC_ALL=C grep -RIlE -- "$pattern" "$STAGE_DIR" 2>/dev/null || true)
	done

	if [[ "$mode" == "fail" && "$found" -eq 1 ]]; then
		return 1
	fi

	return 0
}

SCRIPT_DIR="$(CDPATH= cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)"
MAIN_FILE="$REPO_ROOT/$PLUGIN_SLUG.php"
README_FILE="$REPO_ROOT/readme.txt"
DISTIGNORE_FILE="$REPO_ROOT/.distignore"
BUILD_ROOT="${ANALYTICS_REPORT_AI_RELEASE_BUILD_ROOT:-${TMPDIR:-/tmp}/$PLUGIN_SLUG-release-dry-run}"
BUILD_PARENT="$(dirname -- "$BUILD_ROOT")"
BUILD_BASENAME="$(basename -- "$BUILD_ROOT")"
mkdir -p "$BUILD_PARENT"
BUILD_ROOT="$(CDPATH= cd -- "$BUILD_PARENT" && pwd -P)/$BUILD_BASENAME"
STAGE_PARENT="$BUILD_ROOT/stage"
STAGE_DIR="$STAGE_PARENT/$PLUGIN_SLUG"
ZIP_CONTENTS_FILE="$BUILD_ROOT/zip-contents.txt"

[[ -f "$MAIN_FILE" ]] || fail "Main plugin file not found: $MAIN_FILE"
[[ -f "$README_FILE" ]] || fail "readme.txt not found: $README_FILE"
[[ -f "$DISTIGNORE_FILE" ]] || fail ".distignore not found: $DISTIGNORE_FILE"

require_command awk
require_command find
require_command grep
require_command php
require_command rsync
require_command xargs
require_command zipinfo

VERSION="$(extract_plugin_version "$MAIN_FILE")"
[[ -n "$VERSION" ]] || fail "Could not read plugin header Version."

STABLE_TAG="$(extract_stable_tag "$README_FILE")"
[[ -n "$STABLE_TAG" ]] || fail "Could not read readme Stable tag."
[[ "$VERSION" == "$STABLE_TAG" ]] || fail "Plugin header Version ($VERSION) does not match readme Stable tag ($STABLE_TAG)."

case "$BUILD_ROOT/" in
	"$REPO_ROOT"/*) fail "Build output must be outside the plugin source tree: $BUILD_ROOT" ;;
esac

if [[ -e "$BUILD_ROOT" && -L "$BUILD_ROOT" ]]; then
	fail "Refusing to write build output through a symlink: $BUILD_ROOT"
fi

ZIP_FILE="$BUILD_ROOT/$PLUGIN_SLUG-$VERSION.zip"

info "Creating dry-run release package for $PLUGIN_SLUG $VERSION."
info "This artifact is for local inspection only; it is not a formal release."

rm -rf -- "$BUILD_ROOT"
mkdir -p "$STAGE_PARENT"

info "Copying source to stage with .distignore exclusions."
rsync -a --delete --exclude-from="$DISTIGNORE_FILE" "$REPO_ROOT/" "$STAGE_DIR/"
find "$STAGE_DIR" -type d -empty -delete

[[ -f "$STAGE_DIR/$PLUGIN_SLUG.php" ]] || fail "Stage is missing main plugin file."
[[ -d "$STAGE_DIR/includes" ]] || fail "Stage is missing includes directory."
[[ -d "$STAGE_DIR/assets" ]] || fail "Stage is missing assets directory."
[[ -d "$STAGE_DIR/languages" ]] || fail "Stage is missing languages directory."
[[ -f "$STAGE_DIR/readme.txt" ]] || fail "Stage is missing readme.txt."
[[ -f "$STAGE_DIR/uninstall.php" ]] || fail "Stage is missing uninstall.php."
[[ -f "$STAGE_DIR/languages/$PLUGIN_SLUG.pot" ]] || fail "Stage is missing POT translation template."
[[ -f "$STAGE_DIR/languages/$PLUGIN_SLUG-ja.po" ]] || fail "Stage is missing Japanese PO translation."
[[ -f "$STAGE_DIR/languages/$PLUGIN_SLUG-ja.mo" ]] || fail "Stage is missing Japanese MO translation."

if [[ -d "$STAGE_DIR/.git" || -e "$STAGE_DIR/.distignore" || -d "$STAGE_DIR/docs" || -d "$STAGE_DIR/build" || -d "$STAGE_DIR/tools" ]]; then
	fail "Stage contains an excluded development path."
fi

STAGE_VERSION="$(extract_plugin_version "$STAGE_DIR/$PLUGIN_SLUG.php")"
STAGE_STABLE_TAG="$(extract_stable_tag "$STAGE_DIR/readme.txt")"
[[ "$STAGE_VERSION" == "$VERSION" ]] || fail "Stage Version changed unexpectedly."
[[ "$STAGE_STABLE_TAG" == "$VERSION" ]] || fail "Stage Stable tag does not match Version."
grep -Fxq '== External Services ==' "$STAGE_DIR/readme.txt" || fail "readme.txt is missing == External Services ==."

info "Running PHP syntax checks in stage."
php -l "$STAGE_DIR/$PLUGIN_SLUG.php"
find "$STAGE_DIR/includes" -name '*.php' -print0 | xargs -0 -n1 php -l

info "Scanning stage for high-risk credential patterns without printing matched values."
scan_stage_patterns fail \
	'sk-[A-Za-z0-9_-]{20,}' \
	'Authorization:[[:space:]]*Bearer[[:space:]]+[A-Za-z0-9._-]{10,}' \
	'ya29\.[A-Za-z0-9._-]{10,}' \
	'client_secret[[:space:]]*[:=][[:space:]]*(["'\''][A-Za-z0-9._-]{10,}["'\'']|[A-Za-z0-9._-]{10,}([[:space:]]*[,;})#]|[[:space:]]*//|[[:space:]]*$))' \
	|| fail "High-risk credential pattern found in stage."

info "Scanning documentation keywords as warnings only."
scan_stage_patterns warn \
	'access_token' \
	'openai_api_key' \
	'client_secret'

if command -v zip >/dev/null 2>&1; then
	info "Creating zip with zip."
	( cd "$STAGE_PARENT" && zip -qr "$ZIP_FILE" "$PLUGIN_SLUG" )
else
	require_command python3
	info "zip command not found; creating zip with python3 -m zipfile fallback."
	( cd "$STAGE_PARENT" && python3 -m zipfile -c "$ZIP_FILE" "$PLUGIN_SLUG" )
fi

info "Inspecting zip contents."
zipinfo -1 "$ZIP_FILE" > "$ZIP_CONTENTS_FILE"
cat "$ZIP_CONTENTS_FILE"

TOP_LEVELS="$(sed 's#/.*##' "$ZIP_CONTENTS_FILE" | sort -u)"
[[ "$TOP_LEVELS" == "$PLUGIN_SLUG" ]] || fail "Zip must contain only the $PLUGIN_SLUG/ top-level directory."

require_zip_entry "$PLUGIN_SLUG/$PLUGIN_SLUG.php"
require_zip_entry "$PLUGIN_SLUG/readme.txt"
require_zip_entry "$PLUGIN_SLUG/uninstall.php"
require_zip_prefix "$PLUGIN_SLUG/includes/"
require_zip_prefix "$PLUGIN_SLUG/assets/"
require_zip_entry "$PLUGIN_SLUG/languages/$PLUGIN_SLUG.pot"
require_zip_entry "$PLUGIN_SLUG/languages/$PLUGIN_SLUG-ja.po"
require_zip_entry "$PLUGIN_SLUG/languages/$PLUGIN_SLUG-ja.mo"

reject_zip_prefix "$PLUGIN_SLUG/.git/"
reject_zip_entry "$PLUGIN_SLUG/.distignore"
reject_zip_prefix "$PLUGIN_SLUG/docs/"
reject_zip_prefix "$PLUGIN_SLUG/build/"
reject_zip_prefix "$PLUGIN_SLUG/tools/"
reject_zip_prefix "$PLUGIN_SLUG/tests/"
reject_zip_prefix "$PLUGIN_SLUG/node_modules/"
reject_zip_prefix "$PLUGIN_SLUG/vendor/"

info "Dry-run release zip created: $ZIP_FILE"
info "Stage directory: $STAGE_DIR"
info "Zip root directory: $PLUGIN_SLUG/"
info "Metadata check passed: Version and Stable tag are $VERSION."
info "External Services check passed."
info "Credential scan completed without high-risk matches."
info "This is still a dry-run artifact, not a formal release."
