#!/usr/bin/env bash

set -u

search_dir=tests/suites/
failed=0

echo "\n\n==[ Running phpunit tests / $search_dir ]==========================\n";

for entry in "$search_dir"/*
do
  [ -f "$entry" ] || continue
  [ "$(basename "$entry")" = ".gitkeep" ] && continue
  echo "\n\n----[ Executing $entry file ]----------------";
  if ! sh "$entry"; then
    failed=$((failed + 1))
  fi
  echo "-------------------------------------------------";
done

echo "\n\n======================================================\n";

if [ "$failed" -gt 0 ]; then
  echo "PHPUnit suites with failures: $failed" >&2
  exit 1
fi

exit 0
