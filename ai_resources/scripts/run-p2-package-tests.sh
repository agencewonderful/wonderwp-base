#!/usr/bin/env sh
set -e
ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
cd "$ROOT"
for pkg in form functions dependency-injection notification mailing http panel response filter logging shortcode task template block-bindings-source wonderwp; do
  echo "==== $pkg ===="
  php vendor/bin/phpunit --configuration "vendor/wonderwp/$pkg/phpunit.xml.dist"
done
echo "==== host BaseRepository contract ===="
php vendor/bin/phpunit --configuration ai_resources/scripts/phpunit.xml.dist
