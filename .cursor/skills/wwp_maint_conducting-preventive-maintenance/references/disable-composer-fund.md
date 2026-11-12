# Disable Composer funding notices (`config.fund`)

Concern: `legacy.composer.disable-fund` (`skill_step`: `disable-composer-fund`).

## Goal

Set `"fund": false` under the root `composer.json` `config` block so Composer stops listing packages that seek funding during install/update.

## When

Phase **backend**, during / right after atom `02` (`maintenance:checkComposerInfos`). Triggered when `checkComposerInfos` reports that `config.fund` is missing or not `false`.

## Do

1. In the site root `composer.json`, add (or correct) under `config`:

   ```json
   "fund": false
   ```

   Keep existing keys (`sort-packages`, `optimize-autoloader`, `preferred-install`, `allow-plugins`, …).
2. Re-run `ddev wp maintenance:checkComposerInfos --format=json` (or atom `02`) until this rule is clean.
3. Absorb:

   ```bash
   ddev wp maintenance:absorb legacy.composer.disable-fund --session=…
   ```

## Rules

- Do **not** absorb while `config.fund` is absent or set to anything other than JSON `false`.
- This is a one-line config change; no `composer update` is required solely for this concern.
