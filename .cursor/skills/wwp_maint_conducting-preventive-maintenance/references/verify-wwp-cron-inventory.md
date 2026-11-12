# Verify wwp-cron inventory (recurring)

Concern: `recurring.plugins.verify-wwp-cron` (`skill_step`: `verify-wwp-cron-inventory`).

## Goal

Ensure **every** row contributed to `cron.inventory` is actually scheduled on the host and has evidence of recent execution — including the wwp-cron built-ins and inventories from other plugins (contact, RGPD, cache, security, …).

## When

Phase **plugins**, when `wwp-cron` is present (activate if inactive). Always recurring.

## Inventory baseline (wwp-cron itself)

`CronCronService::getCronInventory()` currently exposes at least:

| Task | Expected command (shape) | Cadence |
| ---- | ------------------------ | ------- |
| Default WordPress Cron | `… wp cron event run --due-now` | ~ every 10 minutes |
| `cron:prun-results` (+ log storage rotation) | `… wp cron:prun-results --prune-storage-path=~/logs_cron` | nightly |

**Gap to watch:** older host crontabs may still run bare `wp cron:prun-results` **without** `--prune-storage-path`. On hosts without native log rotation (e.g. Kinsta), that leaves cron log files growing forever. Maintenance must upgrade the host line to match the current inventory (or document why the flag is intentionally omitted).

Other plugins append more sections via `apply_filters('cron.inventory', …)` — treat those rows with the same rigor.

## Do

1. Activate `wwp-cron` if needed.
2. Build the expected list from BO **Inventaire des crons** (or equivalent dump of `cron.inventory`) — not from memory.
3. On preprod/prod (SSH or hosting panel), compare crontab / scheduled tasks to **each** inventory row (command shape + cadence).
4. For each row, prove execution (log mtime / content, wwp-cron result entries, or `wp cron event list` movement for WP-driven hooks). A matching crontab line alone is **not** enough.
5. Special cases:
   - **`--prune-storage-path`:** required on Kinsta / hosts without native rotation; ensure `~/logs_cron` (or project path) exists and is writable; after a night run, expect rotated archives or prune log activity.
   - **Default WP Cron:** ties to auto-updates (`wp_version_check` / `wp_maybe_auto_update`) — see [verify-auto-updates.md](./verify-auto-updates.md).
   - **Unverifiable SSH crontab** (some mutualized offers): mark `[!]` and check the panel; do not pretend OK.
6. If anything is missing/broken: use the BO inventory mail template toward hosting; leave runbook `[!]` until confirmed.
7. Tick runbook §7 only when the full inventory is covered (or gaps explicitly documented).

## Rules

- Do not stop at “wwp-cron is active”.
- Do not verify only the prune DB command while ignoring Default WP Cron or `--prune-storage-path`.
- Coordinate with `legacy.plugins.install-wwp-cron-if-contact` when contact/RGPD needs cron but the plugin is absent.
