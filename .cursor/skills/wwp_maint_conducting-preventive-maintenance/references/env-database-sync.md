# Environment — database sync (maintenance §2)

**No media sync** as part of maintenance. Languages may follow DB sync (project habit / profile options).

## Scope default

Maintenance runs in **full routine** mode by default (runbook §1→§10). **Do not ask** the operator to choose “maintenance complète” vs other profiles unless they **request** a reduced scope (local-only, preprod-only, partial). Record implicit default in runbook notes: `Scope : complète (défaut)`.

## Operator choice: sync DB or skip

Always offer **skip** explicitly:

> Tu peux **synchroniser la base** (recommandé) ou **continuer sans import** avec la DB locale actuelle.

If skip: tick runbook `[-] Pull / import DB`, note in session, continue to backend atoms.

## Path A — WP Migrate DB (preferred)

### Detect

1. Plugin active or present: `wp-migrate-db-pro` (Composer: `deliciousbrains-plugin/wp-migrate-db-pro`).
2. `ddev wp help migratedb` — subcommand **`profiles`** must exist.

```bash
ddev wp plugin is-active wp-migrate-db-pro
ddev wp migratedb profiles
```

### List profiles

Run `ddev wp migratedb profiles`. Table columns typically include **NAME**, **ACTION** (PULL / PUSH / …).

**Suggest prod pull** when multiple profiles:

- Prefer **ACTION = PULL** (import remote → local).
- Match **NAME** or connection hints to prod URL from `CI/*_prod*_credentials.json` (`siteUrl`, e.g. `soluna-apple.com`, “live”, “prod”).
- Do not run **PUSH** to prod without an explicit operator gate.

Ask the operator:

> Voici les profils MigrateDB disponibles. Lequel veux-tu lancer pour alimenter le local ? (ou **skip** pour garder la DB actuelle)

Run migration (operator-confirmed profile id or name):

```bash
ddev wp migratedb profile <id-or-name>
# or: ddev wp migratedb migrate <id>
```

Follow plugin output; if find-replace is not in the profile, apply Bedrock URL replace per ops skill ([bedrock-db-import-and-url-replace.md](../../../wonderwp-ops/skills/setting-up-ddev-on-existing-wonderwp-project/references/bedrock-db-import-and-url-replace.md) in ai-rules) using prod URL from CI and local URL from `ddev describe`.

### MigrateDB present but no `profiles` subcommand

Ask the operator which **saved profile name or ID** to use (they see them in WP admin → Migrate DB).

### MigrateDB present but no saved profiles

`wp migratedb profiles` errors with “no saved profiles”. Options:

- Operator creates/configures a profile in admin, then re-runs maintenance step; or
- Fall back to **Path B** (SQL file); or
- **skip**.

## Path B — SQL dump (fallback)

When MigrateDB is **not** available or operator prefers a file:

1. Ask for **absolute path** to dump on the host (`.sql`, `.sql.gz`, …).
2. Prod URL for search-replace: from `CI/*_prod*_credentials.json` → `siteUrl` (do not guess).
3. Local URL: from `ddev describe` (HTTPS primary).

```bash
ddev import-db --file="<ABSOLUTE_PATH>"
ddev wp search-replace 'https://prod.example.com' 'https://project.ddev.site' --all-tables
```

Handle `DB_PREFIX` mismatch per ops Bedrock import reference.

## Runbook checkboxes (§2)

- `[x]` Gate DB : MigrateDB détecté / fallback SQL / skip choisi
- `[x]` Migration exécutée **or** `[-]` skip documenté
- `[ ]` Languages (if separate from profile)

## Concerns

`recurring.env.pull-prod-db`, `recurring.env.pull-languages`
