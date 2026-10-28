# Post-deploy finalize (`finalizeDistantMigration`)

Remote WP-CLI steps after rsync deploy. **Not database migration** — plugin lifecycle and optional cache rebuild only.

Runs when `creds.cacheEnabled || env.remoteCommandNeeded` (`remoteCommandNeeded` is set on first builds, restarted builds, or when composer-related files changed).

## Remote SSH execution (mandatory pattern)

Use the **`sshRemoteSh()` helper** from [assets/Jenkinsfile.template](../assets/Jenkinsfile.template). It runs remote commands via **stdin heredoc** (`ssh … /bin/bash -s <<'JENKINS_REMOTE_EOF'`), with Jenkins **`timeout()` step** wrappers — not inline `timeout:` on `sh()`.

### Do not use (known failures on Jenkins Pipeline)

| Anti-pattern | Symptom |
| --- | --- |
| `sh(script: '…', timeout: 60)` | Warning: `Unknown parameter(s) … ShellStep: timeout` — timeout is ignored |
| `ssh host "cd … && wp …"` as a single `sh(script: …)` string | Quotes stripped → `&&` runs **locally** on the Jenkins agent → exit **127** |
| Groovy `"""\` line continuations in heredoc scripts | Newlines eaten → `<<'EOF'set -ecd …` — invalid remote script → exit **127** |

### Expected console output

```text
+ ssh … user@host /bin/bash -s
```

The remote script (`set -e`, `cd …`, `WP_CLI_PHP=… wp …`) is sent on stdin — **no** `&&` on the same line as `ssh`.

**UI display caveat:** the Jenkins step *label* strips newlines, so the UI shows the heredoc collapsed on one line (`<<'JENKINS_REMOTE_EOF'set -ecd …`). This is **cosmetic** — the executed script keeps its newlines (a remote error such as `/bin/bash: line 3: …` proves the multiline script arrived intact). Do not "fix" the helper because of this display.

## Remote WP-CLI command (`creds.wpCliCommand`)

The way WP-CLI is invoked is a property of the **hosting environment**, so it lives in the credentials JSON — see [credential-files.md](./credential-files.md#wpclicommand-per-hosting):

```groovy
def wpCliBase = creds.wpCliCommand ?: "WP_CLI_PHP=${creds.phpPath ?: '{WP_CLI_PHP}'} vendor/wp-cli/wp-cli/bin/wp";
```

- **Kinsta:** set `"wpCliCommand": "wp"` — WP-CLI is pre-installed on the PATH with the right PHP.
- **Classic hosts (Ovea/OVH):** omit the field — the vendor binary fallback is used.

### Exec-bit guard (exit 126)

The deploy rsync (`-uvr`, no `-p`) can leave `vendor/wp-cli/wp-cli/bin/wp` **without its exec bit** → every WP-CLI call fails with exit **126** (`Permission denied`). The template guards this automatically before using the vendor fallback:

```groovy
if (wpCliBase.contains('vendor/')) {
    sshRemoteSh(sshBase, "cd … || exit 1\nchmod +x vendor/wp-cli/wp-cli/bin/wp || true", 30);
}
```

Do not rely on a manual `chmod +x` on the server — the next vendor rsync can undo it.

### Probe first, never hide stderr

The template runs `wp cli version` as a **probe** before real commands, and the doctrine-plugin-list call must **not** end with `2>/dev/null`: that redirect swallows bash errors like `vendor/wp-cli/wp-cli/bin/wp: Permission denied`, leaving only a bare `exit code 126` in the log (multi-build debugging incident). Keep stderr visible.

## Recommended flow (feocc2025 + baillargues)

1. SSH with timeouts: `ConnectTimeout=10`, `ServerAliveInterval=60`
2. Resolve WP-CLI via `creds.wpCliCommand` (Kinsta: `wp`) with the vendor + `WP_CLI_PHP` fallback; `chmod +x` guard when the vendor binary is used
3. `sshRemotePath` must be the Bedrock **project root** (where `vendor/` and `wp-cli.yml` live)
4. If composer ran this build (`env.runComposer`):
   - Fetch doctrine plugins: `wwp:doctrine-plugin-list`
   - Clean output (strip `#` comments, newlines)
   - If list non-empty:
     - `plugin deactivate wwp-cache`
     - `plugin deactivate {doctrinePluginList}`
     - `plugin activate {doctrinePluginList}`
     - `plugin activate wwp-cache`
     - `rewrite flush`
5. If `creds.cacheEnabled == true`:
   - `wwp-cache:rebuild` (timeout 300s via `sshRemoteSh`)
6. Wrap in try/catch — log warning on failure, do not fail the whole deploy for cache issues

Use **separate `sshRemoteSh()` calls** per remote command (baillargues) — do not chain multiple WP-CLI commands in one SSH argument string.

## Pre-deploy verification (ask user / run once over SSH)

Before the first successful finalize, confirm on the **remote server**:

```bash
cd {sshRemotePath}
which wp && wp cli version   # if this works → set "wpCliCommand": "wp" in credentials (Kinsta)
# otherwise, vendor fallback:
WP_CLI_PHP={WP_CLI_PHP} vendor/wp-cli/wp-cli/bin/wp cli version
WP_CLI_PHP={WP_CLI_PHP} vendor/wp-cli/wp-cli/bin/wp wwp:doctrine-plugin-list
```

If `wp` is on the PATH, prefer `"wpCliCommand": "wp"` — it sidesteps both the exec-bit issue and PHP-path mismatches. Otherwise, if the hosting PHP differs from the Jenkins agent (e.g. server runs 8.5, composer says `>=8.3`), set `{WP_CLI_PHP}` to the remote binary and/or add `"phpPath": "/usr/bin/php8.5"` in credentials JSON.

## When doctrine cycle is skipped

- No doctrine plugins in the project → `wwp:doctrine-plugin-list` returns empty → skip deactivate/activate
- Composer did not run this build → skip doctrine cycle (cache rebuild may still run if `cacheEnabled`)

## `cacheEnabled` credential flag

| Value | Behaviour |
| --- | --- |
| `true` | Run `wwp-cache:rebuild` after deploy finalize |
| `false` | Skip cache rebuild; doctrine cycle still runs when composer ran |

Some projects (baillargues) always rebuild cache regardless of flag — prefer the gated pattern unless the project explicitly requires always-on rebuild.

## Optional: PHP-FPM reload (Advanced)

OT_cap and vinci-elearning reload FPM on **develop** only:

```groovy
if(BRANCH_NAME=='develop'){
  sh "ssh wonderful@wdf-02.ovea.com sudo /etc/init.d/php8.3-fpm reload";
}
```

Hosting-specific — ask the user whether preprod requires FPM reload after deploy.

## Optional: project-specific plugin activation (Advanced)

Never add to the generic template. Example: vinci-elearning forces `wwp-espace-restreint` activation inside the doctrine cycle — customize only when the project documents that requirement.

## What this skill excludes

- `deployDbChanges` / `execDbChanges`
- `CI/DB/` scripts
- `dbFolder` in credentials
