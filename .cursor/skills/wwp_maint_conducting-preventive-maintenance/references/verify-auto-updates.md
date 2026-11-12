# Verify auto-updates (recurring)

Concern: `recurring.backend.verify-auto-updates` (`skill_step`: `verify-auto-updates`).

## Goal

Each maintenance, confirm the site still has the architectural opt-in for WordPress updates and that the update poller actually runs — not only that git constants “look” correct.

## When

Phase **backend** (after enable one-shot if pending). Always run as recurring; never absorb as “done forever”.

## Do

1. If `legacy.backend.enable-auto-updates` is still pending → follow [enable-auto-updates.md](./enable-auto-updates.md) first, then continue here.
2. Re-read constants and child theme config against ops skill reference `bedrock-constants.md` (`AUTOMATIC_UPDATER_DISABLED=false`, `WP_AUTO_UPDATE_CORE=true`, `DISALLOW_FILE_MODS=false` on prod/staging, `enable_auto_updates` on child ThemeManager, `DISALLOW_FILE_EDIT` still `true`).
3. On the target host (prefer preprod then prod when in those phases; local/DDEV only proves config, not host cron):
   - Confirm crontab / panel runs `wp cron event run --due-now` on a short interval.
   - `wp cron event list --fields=hook,next_run_relative` → `wp_version_check` has a future run.
   - If a core update is available, expect `wp_maybe_auto_update` to become scheduled; its absence with `WP_AUTO_UPDATE_CORE=true` usually means deployed `DISALLOW_FILE_MODS` is still blocking, or cron is not executing.
4. Spot-check BO **Dashboard → Updates**: message « All automatic updates are disabled » must not appear.
5. Tick the runbook checkbox; note `[!]` if cron is unverifiable (e.g. mutualized host without SSH crontab) and say so explicitly.

## Rules

- Presence of a crontab line ≠ proof of execution — prefer log tail / `next_run_relative` movement.
- Do not mark OK based solely on SecuPress minor-update toggles.
- Cross-link host cron proof with [verify-wwp-cron-inventory.md](./verify-wwp-cron-inventory.md) (Default WordPress Cron row).
