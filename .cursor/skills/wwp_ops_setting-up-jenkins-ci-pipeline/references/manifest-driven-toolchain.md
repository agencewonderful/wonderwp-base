# Manifest-driven toolchain

Map project manifests to Jenkinsfile values before committing.

## PHP (Composer + WP-CLI)

Read `composer.json` → `require.php` for the **Jenkins agent** Composer line (`{PHP_BIN}`).

For **remote WP-CLI** (`{WP_CLI_PHP}`), use the PHP binary installed on the **hosting server** — they often differ (e.g. composer `>=8.3` but server runs 8.5). **Ask the user** or verify over SSH (`which php8.5`, `php -v`).

| Manifest (minimum) | Jenkins `{PHP_BIN}` (agent) | Default `{WP_CLI_PHP}` (remote) |
| --- | --- | --- |
| `>=8.5` | `/usr/bin/php8.5` | `/usr/bin/php8.5` |
| `>=8.4` | `/usr/bin/php8.4` | `/usr/bin/php8.4` |
| `>=8.3` | `/usr/bin/php8.3` | `/usr/bin/php8.3` |
| `>=8.2` | `/usr/bin/php8.2` | `/usr/bin/php8.2` |

Composer install line:

```groovy
sh '{PHP_BIN} /usr/local/bin/composer install --no-dev --prefer-dist';
```

Remote WP-CLI uses `sshRemoteSh()` + `WP_CLI_PHP={WP_CLI_PHP}` in the template — see [post-deploy-finalize.md](./post-deploy-finalize.md).

### Remote WP-CLI resolution order

The Jenkinsfile template resolves the remote WP-CLI command as:

```groovy
def wpCliBase = creds.wpCliCommand ?: "WP_CLI_PHP=${creds.phpPath ?: '{WP_CLI_PHP}'} vendor/wp-cli/wp-cli/bin/wp";
```

1. **`creds.wpCliCommand`** (highest priority) — full invocation per hosting; **Kinsta: `"wp"`** (pre-installed on PATH). See [credential-files.md](./credential-files.md#wpclicommand-per-hosting).
2. **`creds.phpPath`** — remote PHP for the vendor fallback when it differs from `{WP_CLI_PHP}` (e.g. `"/usr/bin/php8.5"`); useful when preprod and prod run different PHP paths.
3. **`{WP_CLI_PHP}`** — scaffold-time default; prefer setting it correctly from the start.

`{WP_CLI_PHP}` may also be a bare binary name (`php8.3`) when the remote PATH resolves it — some hostings reject absolute `/usr/bin/…` paths that don't exist there.

## Node (NVM)

Read `package.json` → `engines.node`. Pick an installed version from [jenkins-node-versions.md](./jenkins-node-versions.md) — do not hardcode patch versions in the skill.

```groovy
env.PATH="{NODE_BIN_DIR}:${env.PATH}";
sh 'node -v';
```

**Always confirm** the exact patch version installed on the Jenkins agent (`ls /var/lib/jenkins/.nvm/versions/node/`).

## npm scripts

Minimum expected scripts:

- `sprites`
- `build` (non-master branches)
- `build:prod` (master branch)

Discover additional scripts in `package.json` and chain them in `{EXTRA_NPM_BUILD}`:

```groovy
// master
sh 'npm run build:prod && npm run build:theme';
// develop
sh 'npm run build && npm run build:theme';
```

Examples from recent projects:

| Project | Extra scripts |
| --- | --- |
| feocc2025 | `build:theme`, `build:feocc-catalogue` |
| esma-3D | `build:all-others` |
| baillargues | single `build:all` (no branch split) |

Before committing, verify every referenced script exists:

```bash
node -e "const p=require('./package.json'); ['sprites','build','build:prod',...].forEach(s=>{if(!p.scripts[s])console.error('missing',s)})"
```

## Legacy edge cases

- **Node 14 + node-sass:** vinci-elearning runs `npm rebuild node-sass` before `npm install` — only for old stacks
- **Single build command:** baillargues uses `npm run build:all` for all branches — skip master/develop split when prod and staging share the same command

## Org-fixed values (do not substitute)

See [org-ci-conventions.md](./org-ci-conventions.md):

- Slack: `#jenkins` / `wdf-team` (already in Jenkinsfile template)
- Jenkins Cypress: `cypress run --env host=$host` (full suite)
- Day-one Cypress setup: root `cypress.json` from [assets/cypress.json.template](../assets/cypress.json.template) with `smoke-pages` only

## Placeholder summary (per project)

| Placeholder | Source |
| --- | --- |
| `{PROJECT_SLUG}` | repo / folder name |
| `{PHP_BIN}` | Jenkins agent PHP matching `composer.json` (confirm binary exists on agent) |
| `{WP_CLI_PHP}` | **Remote hosting** PHP binary (verify over SSH — may differ from `{PHP_BIN}`) |
| `{NODE_BIN_DIR}` | `package.json` → `engines.node` + [jenkins-node-versions.md](./jenkins-node-versions.md) |
| `{EXTRA_NPM_BUILD}` | extra `npm run` scripts in `package.json` |
