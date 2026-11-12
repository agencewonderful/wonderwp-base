# Remove `roots/wp-password-bcrypt` + bump `composer/installers`

Concern: `legacy.composer.wp-password-bcrypt-remove` (`skill_step`: `remove-wp-password-bcrypt`).

## Goal

Drop the abandoned `roots/wp-password-bcrypt` package (it pins `composer/installers` to `~1.0`) and raise `composer/installers` to **`^2.3`**, so PHP **8.5** no longer emits Deprecation Notices on installer v1.12. WordPress **≥ 6.8** already provides native bcrypt.

## When

Phase **backend**, during / right after atom `02` (`maintenance:checkComposerInfos` + `composer update`). Triggered when `checkComposerInfos` reports either:

- `composer/installers` below `^2.3`, or
- `roots/wp-password-bcrypt` still required while `johnpbloch/wordpress` is `>= 6.8`

## Do

1. Confirm WordPress constraint is **≥ 6.8** (WonderWP sites on 7.x are fine). If still on WP &lt; 6.8, **do not** remove bcrypt yet — leave the concern pending with a runbook `[!]` note.
2. Remove the package and bump installers (prefer DDEV):

   ```bash
   ddev composer remove roots/wp-password-bcrypt
   ddev composer require "composer/installers:^2.3" -W
   ```

   If `remove` alone already unlocks installers, still ensure the root `composer.json` constraint is **`^2.3`** (not `^1.9`).
3. Re-run atom `02` (or at least `ddev composer update` + `ddev wp maintenance:checkComposerInfos --format=json`) until clean for these rules.
4. Absorb:

   ```bash
   ddev wp maintenance:absorb legacy.composer.wp-password-bcrypt-remove --session=…
   ```

## Rules

- Do **not** absorb while `roots/wp-password-bcrypt` remains in `require` on WP ≥ 6.8, or while `composer/installers` stays on 1.x.
- Password hashes already stored with bcrypt remain valid under core WP bcrypt — no user password reset required for this migration.
- Coordinate with `recurring.backend.composer-audit`: advisories and this one-shot often surface in the same atom `02` pass.
