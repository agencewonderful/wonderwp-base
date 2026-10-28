# Kinsta MU plugin — Composer vs SFTP

## Use Composer (WonderWP / Bedrock)

When the project root has `composer.json`, `config/application.php`, and `web/app/mu-plugins/bedrock-autoloader.php`:

- Manage `kinsta/kinsta-mu-plugins` via the package repository + `composer require`.
- Keep the plugin in `web/app/mu-plugins/kinsta-mu-plugins/` — do not flatten files to the mu-plugins root.
- Set `KINSTA_CDN_USERDIRS` and `KINSTAMU_CUSTOM_MUPLUGIN_URL` in `config/application.php`.

Kinsta will **not** auto-install or auto-update the MU plugin on repos that manage `mu-plugins` in Git — that is expected.

## Use SFTP (legacy / non-Composer)

When WordPress is not Composer-managed:

1. Create `/wp-content/mu-plugins/` if missing.
2. Download [kinsta-mu-plugins.zip](https://kinsta.com/kinsta-tools/kinsta-mu-plugins.zip).
3. Upload `kinsta-mu-plugins.php` and the `kinsta-mu-plugins/` folder to `/wp-content/mu-plugins/`.

No Bedrock constants are required; paths follow standard WordPress layout.

## Git-managed mu-plugins

If the entire `mu-plugins` directory is in Git (WonderWP default for autoloader files + Composer-installed MU plugins):

- Install via Composer in the repo, commit lock file, deploy via CI/rsync.
- Do not rely on Kinsta's periodic auto-install on the server.
