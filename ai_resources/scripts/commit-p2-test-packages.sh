#!/usr/bin/env sh
set -e
ROOT="/Users/jeremy/Sites/Wonderful/wonderwp-base/vendor/wonderwp"
MSG='test: P2 Unit scaffold (tests/Unit, phpunit, GHA CI)

Normalize legacy or add minimal smoke tests per wonderwp/docs/testing.md.'
PKGS="form functions dependency-injection notification mailing http panel response filter logging shortcode task template block-bindings-source wonderwp"
HASH_FILE="/tmp/p2-test-commits.txt"
: > "$HASH_FILE"
for pkg in $PKGS; do
  dir="$ROOT/$pkg"
  if [ ! -d "$dir/.git" ]; then
    echo "skip $pkg (no git)"
    continue
  fi
  cd "$dir"
  git add -A
  if git diff --cached --quiet; then
    echo "skip $pkg (clean)"
    continue
  fi
  git commit -m "$MSG"
  git push origin chore/deps-max
  hash=$(git rev-parse --short HEAD)
  echo "$pkg|$hash" >> "$HASH_FILE"
  echo "committed $pkg $hash"
done
cat "$HASH_FILE"
