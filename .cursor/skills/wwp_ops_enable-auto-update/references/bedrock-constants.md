# Bedrock constants for auto-updates

## Constants to change

| Constant | File | From | To | Role |
|----------|------|------|-----|------|
| `AUTOMATIC_UPDATER_DISABLED` | `config/application.php` | `true` | `false` | Allows WordPress background auto-updates (core, plugins, themes) |
| `WP_AUTO_UPDATE_CORE` | `config/application.php` | undefined | `true` | **Required separately** to opt in to *major* core version auto-updates (e.g. 7.0→7.1). `AUTOMATIC_UPDATER_DISABLED=false` alone only covers minor/security releases; without this, `wp_maybe_auto_update` is never scheduled and the site silently stalls on its current major version. |
| `DISALLOW_FILE_MODS` | `config/environments/production.php` | `true` | `false` | Allows file writes for updates in production |
| `DISALLOW_FILE_MODS` | `config/environments/staging.php` | `true` | `false` | Allows file writes for updates in staging |

## Hardening constants to set alongside (staging + production)

These are set in the same two files as `DISALLOW_FILE_MODS` above, at the same time, since enabling updates on an
environment is the natural moment to also lock down the attack surface that a writable filesystem + an update
cycle opens up. All four go in **both** `config/environments/production.php` and `config/environments/staging.php`.

| Constant | Files | To | Role |
|----------|-------|-----|------|
| `CORE_UPGRADE_SKIP_NEW_BUNDLED` | `config/environments/production.php`, `config/environments/staging.php` | `true` | Skips installing new bundled themes/plugins (e.g. the default Twenty-Twenty-* theme, Akismet, Hello Dolly) during a core upgrade. Checked in `wp-admin/includes/class-core-upgrader.php` and `update-core.php`. Prevents unused bundled software from silently appearing and later going unpatched. |
| `ALLOW_UNFILTERED_UPLOADS` | `config/environments/production.php`, `config/environments/staging.php` | `false` | Checked in `wp-includes/capabilities.php`; when `true` it lets users with the `unfiltered_upload` capability (super admins on multisite) bypass the upload file-type whitelist. Explicitly `false` closes a direct path to uploading a webshell disguised as a media file — directly relevant after the wp2shell / CVE-2026-87902 incidents on this fleet. |
| `WP_ALLOW_REPAIR` | `config/environments/production.php`, `config/environments/staging.php` | `false` | Gates `wp-admin/maint/repair.php` — this page **requires no authentication**; when the constant is undefined or `false` the page refuses to run (`if ( ! defined( 'WP_ALLOW_REPAIR' ) || ! WP_ALLOW_REPAIR )`). Defining it explicitly as `false` documents the intent and guards against it being flipped to `true` for a one-off DB repair and forgotten. |
| `DIEONDBERROR` | `config/environments/production.php`, `config/environments/staging.php` | `false` | Controls whether `wp-includes/class-wpdb.php` calls `wp_die($msg)` with the raw SQL error (query + DB structure) shown to visitors on a database error — an information-disclosure risk. **Caveat:** the core check is `if ( defined( 'DIEONDBERROR' ) )` — definition alone triggers the die, regardless of value. Setting it to `false` matches the SecuPress "Hide Database Errors" hardening module's convention and communicates intent clearly, but if a stricter guarantee is needed, confirm SecuPress's `wordpress-core` → hide-db-errors submodule (which also drops a `db-error.php` catch-all into `wp-content/`) is active rather than relying on the constant's value alone. |

## Constants to leave unchanged

| Constant | File | Value | Role |
|----------|------|-------|------|
| `DISALLOW_FILE_EDIT` | `config/application.php` | `true` | Keeps the built-in theme/plugin code editor disabled |
| `DISABLE_WP_CRON` | `config/application.php` | env-driven | If `true`, a system cron must trigger `wp-cron.php` or scheduled updates will not run |

## Environment notes

- `config/environments/development.php` does not define `DISALLOW_FILE_MODS` — no change needed there.
- On Bedrock projects, plugins and themes are often managed via Composer. BO updates can diverge from Git/Composer lockfiles; prefer staging validation before production.
- Setting the deployed constant is not sufficient proof updates will apply: always confirm WP-Cron is actually executing on the server (see Step 4 of the main skill workflow). A correct crontab line is not the same as a running one, and a fatal PHP error elsewhere in the codebase (broken plugin, missing Composer dependency) can prevent every `wp` invocation — including the cron runner — from completing.
- On mutualized hosting where `crontab -l` is unavailable over SSH, cron cannot be audited this way — say so explicitly instead of assuming it works, and check the hosting panel directly.

## Theme-core complement

When `enable_auto_updates` is `true` on the child `ThemeManager`, `ThemeHookService` blocks install/delete while allowing updates. See the main skill workflow for the `ChildThemeManager` override.
