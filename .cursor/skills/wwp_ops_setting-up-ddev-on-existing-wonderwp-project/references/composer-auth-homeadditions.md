# Composer authentication inside DDEV (homeadditions)

## Problem

`ddev composer` runs Composer in the **web container**. The container has its own home directory. The host file `~/.composer/auth.json` is **not** used automatically.

## Global solution (all projects)

1. Resolve the DDEV global directory:

   ```bash
   ddev version | grep global-ddev-dir
   ```

   Default is `~/.ddev`. If `XDG_CONFIG_HOME` is set, it may be `$XDG_CONFIG_HOME/ddev`.

2. Symlink the host auth file into global `homeadditions`:

   ```bash
   mkdir -p ~/.ddev/homeadditions/.composer
   ln -sf ~/.composer/auth.json ~/.ddev/homeadditions/.composer/auth.json
   ```

   Adjust paths if your global DDEV dir is not `~/.ddev`.

3. Restart projects so the web container picks up the copy:

   ```bash
   ddev restart
   ```

DDEV merges **global** `homeadditions` with **project** `.ddev/homeadditions` on start; for the same relative path, the **project** copy wins.

## Project-only solution

```bash
mkdir -p .ddev/homeadditions/.composer
ln -sf ~/.composer/auth.json .ddev/homeadditions/.composer/auth.json
ddev restart
```

Do not commit secrets. Ensure `auth.json` is gitignored if placed under the repo.

## Alternative: `COMPOSER_AUTH` environment variable

Set JSON matching Composer’s expected structure, for example:

```bash
ddev config global --web-environment-add="COMPOSER_AUTH={\"github-oauth\":{\"github.com\":\"YOUR_TOKEN\"}}"
```

Then restart DDEV projects. Prefer `homeadditions` if you already maintain `auth.json` on the host and want a single source of truth.

## Reference

- [DDEV in-container home directory](https://docs.ddev.com/en/stable/users/extend/in-container-configuration/) (Composer section)
