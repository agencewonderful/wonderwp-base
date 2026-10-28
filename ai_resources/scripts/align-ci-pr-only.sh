#!/usr/bin/env bash
# Align wonderwp package CI to pull_request-only (drop bare push).
set -euo pipefail

ROOT="/Users/jeremy/Sites/Wonderful/wonderwp-base/vendor/wonderwp"
MSG="$(cat <<'EOF'
ci: run unit workflow on pull_request only

Drop bare `push` to avoid duplicate runs when syncing an open PR.
EOF
)"

BODY="$(cat <<'EOF'
## Summary
- CI `unit` job triggers on `pull_request` only (remove bare `push`)
- Avoids double runs when pushing to a branch that already has an open PR

## Test plan
- [ ] Open/sync a PR → unit job runs once
- [ ] Push to a non-PR branch → no unit job
EOF
)"

patch_ci() {
  local file="$1"
  python3 - "$file" <<'PY'
from pathlib import Path
import sys
path = Path(sys.argv[1])
text = path.read_text()
old = """on:
  push:
  pull_request:
"""
new = """on:
  pull_request:
"""
if old not in text:
    # already patched or different layout
    if "push:" not in text.split("jobs:")[0]:
        print(f"SKIP already pr-only: {path}")
        sys.exit(0)
    raise SystemExit(f"Unexpected on: block in {path}")
path.write_text(text.replace(old, new, 1))
print(f"PATCHED {path}")
PY
}

process_open() {
  local dir="$1" remote="$2"
  cd "$dir"
  git checkout chore/deps-max
  git pull --ff-only origin chore/deps-max || true
  patch_ci ".github/workflows/ci.yml"
  if git diff --quiet -- .github/workflows/ci.yml; then
    echo "NOOP open $remote (no diff)"
    return 0
  fi
  git add .github/workflows/ci.yml
  git commit -m "$MSG"
  git push origin HEAD
  echo "PUSHED open $remote"
}

process_merged() {
  local dir="$1" remote="$2" base="$3"
  cd "$dir"
  git fetch origin "$base"
  git checkout -B chore/ci-pr-only "origin/$base"
  patch_ci ".github/workflows/ci.yml"
  if git diff --quiet -- .github/workflows/ci.yml; then
    # maybe already on base from a previous run
    if git rev-parse --verify origin/chore/ci-pr-only >/dev/null 2>&1; then
      echo "NOOP merged $remote (already patched upstream?)"
      return 0
    fi
    echo "NOOP merged $remote (no diff on base)"
    return 0
  fi
  git add .github/workflows/ci.yml
  git commit -m "$MSG"
  git push -u origin HEAD
  # create PR if none open for this head
  existing=$(gh pr list --repo "$remote" --head chore/ci-pr-only --state open --json number -q '.[0].number' || true)
  if [ -n "${existing:-}" ]; then
    echo "PR exists $remote #$existing"
  else
    url=$(gh pr create --repo "$remote" --base "$base" --head chore/ci-pr-only --title "ci: run unit workflow on pull_request only" --body "$BODY")
    echo "CREATED $url"
  fi
}

# pkg|status|base|remote  (base empty if OPEN on chore/deps-max)
declare -a ROWS=(
  "api|merged|feature/php_8|wonderwp/API"
  "asset|merged|feature/php_8|wonderwp/Asset"
  "block-bindings-source|open|feature/definition|wonderwp/block-bindings-source"
  "cache|merged|feature/php_8|wonderwp/Cache"
  "custom-fields|merged|feature/php8|wonderwp/CustomFields"
  "customposttype|merged|feature/php_8|wonderwp/CPT"
  "dependency-injection|merged|feature/php_8|wonderwp/DependencyInjection"
  "filter|merged|feature/php_8|wonderwp/Filter"
  "form|merged|feature/increase_validation_lib_version|wonderwp/Form"
  "functions|merged|feature/php_8|wonderwp/Functions"
  "hook|merged|feature/php_8|wonderwp/Hook"
  "http-foundation|merged|feature/increase_http_foundation_lib_version|wonderwp/HttpFoundation"
  "http|merged|feature/php_8|wonderwp/Http"
  "logging|merged|feature/php_8|wonderwp/Logging"
  "mailing|merged|feature/php_8|wonderwp/Mailing"
  "media|merged|feature/php_8|wonderwp/Media"
  "notification|merged|feature/php_8|wonderwp/Notification"
  "panel|open|feature/php_8|wonderwp/Panel"
  "plugin-skeleton|merged|feature/php_8|wonderwp/PluginSkeleton"
  "repository|merged|feature/php_8|wonderwp/Repository"
  "response|open|feature/php8|wonderwp/Response"
  "routing|merged|feature/php_8|wonderwp/Routing"
  "sanitizer|merged|feature/php_8|wonderwp/Sanitizer"
  "search|merged|feature/php_8|wonderwp/Search"
  "service|merged|feature/php_8|wonderwp/Service"
  "shortcode|open|feature/php_8|wonderwp/ShortCode"
  "task|open|feature/php_8|wonderwp/Task"
  "taxonomy|merged|feature/php8|wonderwp/Taxonomy"
  "template|open|feature/php_8|wonderwp/Template"
  "wonderwp|merged|feature|wonderwp/wonderwp"
)

for row in "${ROWS[@]}"; do
  IFS='|' read -r pkg status base remote <<< "$row"
  dir="$ROOT/$pkg"
  echo "======== $pkg ($status → $base) ========"
  if [ "$status" = "open" ]; then
    process_open "$dir" "$remote"
  else
    process_merged "$dir" "$remote" "$base"
  fi
done

echo "ALL_DONE"
