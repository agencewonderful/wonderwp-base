# Enable auto-updates (architectural)

Concern: `legacy.backend.enable-auto-updates` (`skill_step`: `enable-auto-updates`).

## Goal

Turn on WordPress updates (automatic + manual, including **major** core) on a WonderWP Bedrock site that still has Bedrock’s default “updates off” posture, while keeping the file editor disabled and blocking plugin/theme install/delete from the admin.

## When

Phase **backend**, after atom `02` / composer green. Triggered when pending in `maintenance:pending`, or when a quick scan shows any of:

- `AUTOMATIC_UPDATER_DISABLED` still `true` in `config/application.php`
- `WP_AUTO_UPDATE_CORE` missing
- `DISALLOW_FILE_MODS` still `true` on `production.php` / `staging.php`
- child `ThemeManager` without `enable_auto_updates` → `true` before `parent::register()`
- BO **Dashboard → Updates** still says automatic updates are disabled

## Do

1. Delegate to the ops skill **`enable-auto-update`** (`wwp_ops_enable-auto-update` / `.wonderful` path `plugins/wonderwp-ops/skills/enable-auto-update/SKILL.md`) and follow it end-to-end (theme-core version, Bedrock constants including `WP_AUTO_UPDATE_CORE`, child ThemeManager config, cron execution, BO checks).
2. Do **not** treat SecuPress « minor updates » as a substitute for Bedrock constants + theme-core hooks.
3. Absorb only when the architecture matches the ops skill success criteria:

   ```bash
   ddev wp maintenance:absorb legacy.backend.enable-auto-updates --session=…
   ```

## Rules

- Keep `DISALLOW_FILE_EDIT` at `true`.
- `AUTOMATIC_UPDATER_DISABLED=false` alone is **not** enough — major core updates need `WP_AUTO_UPDATE_CORE=true`.
- Coordinate with `recurring.backend.verify-auto-updates` (same maintenance pass after absorb) and with `recurring.plugins.verify-wwp-cron` (host must actually run `wp cron event run --due-now`).
