#!/usr/bin/env bash
# Run all WonderWP Integration smokes (framework packages + Bedrock plugins).
#
# Requires a booted WordPress (WP-CLI). Examples:
#   ddev exec bash CI/run-integration-tests.sh
#   WP_BIN="ddev wp" bash CI/run-integration-tests.sh
#
# Optional:
#   ONLY=wwp-cache   — forwarded to the plugin runner only
#   SKIP_FRAMEWORK=1 — plugins only
#   SKIP_PLUGINS=1   — framework only

set -u

ROOT="$(CDPATH= cd -- "$(dirname "$0")/.." && pwd)"
cd "$ROOT" || exit 1

WP_BIN="${WP_BIN:-wp}"
failed=0

echo ""
echo "==[ Running integration smokes ]=========================="
echo "WP_BIN=$WP_BIN"
echo ""

if [ "${SKIP_FRAMEWORK:-0}" != "1" ]; then
  echo ""
  echo "----[ Framework: vendor/wonderwp/*/tests/Integration ]----------------"
  if ! WP_BIN="$WP_BIN" bash "$ROOT/vendor/wonderwp/wonderwp/bin/run-all-integration.sh"; then
    failed=$((failed + 1))
  fi
  echo "-------------------------------------------------"
fi

if [ "${SKIP_PLUGINS:-0}" != "1" ]; then
  echo ""
  echo "----[ Plugins: web/app/plugins/*/tests/Integration ]----------------"
  if ! WP_BIN="$WP_BIN" ONLY="${ONLY:-}" bash "$ROOT/bin/run-plugin-integration.sh"; then
    failed=$((failed + 1))
  fi
  echo "-------------------------------------------------"
fi

echo ""
echo "======================================================"
echo ""

if [ "$failed" -gt 0 ]; then
  echo "Integration runners with failures: $failed" >&2
  exit 1
fi

echo "✅ All Integration smokes passed. ✅"
exit 0
