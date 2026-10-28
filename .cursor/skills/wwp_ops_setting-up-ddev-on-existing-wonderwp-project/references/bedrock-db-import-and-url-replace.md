# Bedrock database import and URL replacement

## Table prefix (`DB_PREFIX`)

Bedrock reads `DB_PREFIX` from `.env`. It must match the **actual** prefix of tables in the imported database.

**Symptom:** `ddev wp` reports the site is not installed, or mentions a mismatch like: configured `wp_` but tables use `otc_`.

**Fix:** Set `DB_PREFIX` in `.env` to the imported prefix (e.g. `otc_`), save, then run `ddev restart` if needed and retry WP-CLI.

## Inputs the agent must collect

1. **Dump file path (user):** absolute path on the **host** to the database archive or SQL file. The agent must **ask** for this; do not guess.
2. **Production URL (user):** canonical public URL to replace everywhere it appears in the DB (old string in `wp search-replace`).
3. **Local URL (DDEV):** read from `ddev describe` (HTTPS primary URL). Do not ask the user unless DDEV output is unavailable.

## Import

```bash
ddev import-db --file="<ABSOLUTE_PATH_FROM_USER>"
```

Supported formats depend on DDEV version (commonly `.sql`, `.sql.gz`, `.tar`, `.tar.gz`, `.zip`).

## URL search-replace

After the prefix is correct and WordPress loads, replace the **user-provided** production URL with the **DDEV** local URL. Always use **`--all-tables`** so every table in the database is scanned (plugin tables, Action Scheduler, custom post meta in non-standard tables, etc.), not only tables WP-CLI treats as core by default.

Preview without writing (optional):

```bash
ddev wp search-replace '<PRODUCTION_URL_FROM_USER>' '<LOCAL_URL_FROM_DDEV_DESCRIBE>' --all-tables --dry-run
```

Apply for real:

```bash
ddev wp search-replace '<PRODUCTION_URL_FROM_USER>' '<LOCAL_URL_FROM_DDEV_DESCRIBE>' --all-tables --report-changed-only
```

(`--report-changed-only` limits the report to rows that changed; it does not skip the replacement.)

Typical extra passes (only if serialized content or hard-coded links remain):

- `http://www.production.example` → local HTTPS URL
- `https://production.example` (no `www`)
- Path-only or CDN host variants documented for that project

## Order of operations

1. Ask user for dump path and production URL; read local URL from `ddev describe`  
2. Import DB with `ddev import-db --file=...`  
3. Fix `DB_PREFIX` if WP-CLI reports a mismatch  
4. Ensure `WP_HOME` / `WP_SITEURL` in `.env` match the local DDEV URL  
5. `ddev wp search-replace` from production URL to local URL  

## Multisite

If the install is multisite, additional flags (for example `--network`) may be required. Confirm with `ddev wp --info` and project docs.
