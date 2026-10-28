# Uploads proxy `.htaccess` (Apache, Bedrock)

Use when the local `web/app/uploads` tree is empty or incomplete and you want missing files to **redirect** to production for local development only.

## Placement

File path: **`web/app/uploads/.htaccess`** (Bedrock public uploads URL is `/app/uploads/...`).

Do not put this in the repository root `.htaccess`; that path is unrelated to WordPress routing for this project.

## Template

Replace placeholders:

- `{LOCAL_DDEV_HOST}` — HTTP `Host` for local only, e.g. `ot-cap.ddev.site` (no port, lowercase).
- `{PRODUCTION_ORIGIN}` — Production origin **without** trailing slash, e.g. `https://www.capdagde.com`.

```apache
RewriteEngine On
RewriteBase /app/uploads/

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{HTTP_HOST} ^{LOCAL_DDEV_HOST_REGEX}$
RewriteRule ^(.+)$ {PRODUCTION_ORIGIN}/app/uploads/$1 [L,R=301,NE]
```

For `{LOCAL_DDEV_HOST_REGEX}`, escape dots for a regex character class or pattern, e.g. host `ot-cap.ddev.site` → `^ot-cap\.ddev\.site$`.

### Example (filled in)

```apache
RewriteEngine On
RewriteBase /app/uploads/

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{HTTP_HOST} ^ot-cap\.ddev\.site$
RewriteRule ^(.+)$ https://www.example.com/app/uploads/$1 [L,R=301,NE]
```

## Flags

- **`!-f`** — only redirect when the file is not on disk.
- **`R=301`** — permanent redirect; browsers may cache. For pure local dev, **`R=302`** reduces caching surprises.
- **`NE`** — no escape of the substitution (safe for typical URL paths).

## Security note

This pattern is for **local** hosts only. Never deploy an `.htaccess` that redirects production traffic to another origin unless that is an explicit requirement.
