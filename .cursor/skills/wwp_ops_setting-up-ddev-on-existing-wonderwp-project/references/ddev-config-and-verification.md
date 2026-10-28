# DDEV config and verification (WonderWP Bedrock)

## Project type and docroot

- **Type:** `wp-bedrock` — DDEV treats Composer root as the project root and the web root as Bedrock’s `web/` directory.
- **Docroot:** `web` — must contain `index.php` that loads `wp/wp-blog-header.php`.

## Deriving versions

| Source | Field | Example | DDEV field |
| ------ | ----- | ------- | ---------- |
| `composer.json` | `require.php` | `>=8.3` | `php_version: "8.3"` (pick lowest supported major.minor that satisfies the constraint) |
| `package.json` | `engines.node` | `>=16 <17` | `nodejs_version: "16"` |

If `engines.node` is missing, ask the user or use the LTS line the build toolchain expects.

## Web server

- **Apache:** `webserver_type: apache-fpm`
- **Nginx (default in many DDEV examples):** `nginx-fpm` — omit `--webserver-type` or set explicitly.

## Non-interactive bootstrap

```bash
ddev config --auto \
  --project-type=wp-bedrock \
  --docroot=web \
  --php-version=<from_composer> \
  --nodejs-version=<from_package_json> \
  --webserver-type=apache-fpm
ddev start
```

For scripts or CI where prompts are unwanted:

```bash
DDEV_NONINTERACTIVE=true ddev start
```

## Smoke checks

```bash
ddev describe
ddev exec php -v
ddev exec node -v
```

## URLs

Primary site URL is shown in `ddev describe`, typically `https://<normalized-project-name>.ddev.site`. Use that value for `WP_HOME` / `WP_SITEURL` in `.env` and for `wp search-replace` targets.

## Further reading

- [DDEV WordPress Bedrock quickstart](https://docs.ddev.com/en/stable/users/quickstart/#wordpress-bedrock)
