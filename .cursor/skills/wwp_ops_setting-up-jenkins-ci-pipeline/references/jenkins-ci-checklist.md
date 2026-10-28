# Jenkins CI checklist

Condensed from Wonderful documentation and latest project Jenkinsfiles. **`CI/Jenkinsfile`** (auto-detected on push).

## Repo artefacts

- [ ] `Jenkinsfile` at **`CI/Jenkinsfile`** (not at project root — legacy repos may still use root `Jenkinsfile`)
- [ ] `CI/exclude-file.txt` present (copy from [assets/exclude-file.txt.template](../assets/exclude-file.txt.template) if missing)
- [ ] Project-specific excludes added (e.g. `.ddev/` under « Project Specific excludes »)
- [ ] `{projectSlug}_*_credentials.json` in `.gitignore` (never commit filled credential files)
- [ ] `CI/credentials.template.json` committed; filled files named by **environment**: `CI/{slug}_preprod_credentials.json`, `CI/{slug}_prod_credentials.json`
- [ ] Jenkins secret file **IDs** are **branch-based** and match `loadCreds("{slug}_${BRANCH_NAME}_credentials")` (preprod file → `{slug}_develop_credentials`, prod file → `{slug}_main_credentials`; legacy repos on `master` → `{slug}_master_credentials`)
- [ ] `wpCliCommand` set per hosting in each credential JSON (**Kinsta: `"wp"`**; classic hosts: omit → vendor fallback) — see [credential-files.md](./credential-files.md#wpclicommand-per-hosting)
- [ ] `cypress.json` committed (from [assets/cypress.json.template](../assets/cypress.json.template)); Jenkins runs full `cypress run --env host=$host`

## Branches and environments

- Jenkins auto-builds **`develop`**, **`main`**, and **`master`** (legacy) only
- Typical mapping: **`develop` → staging/preprod**, **`main` → production** (legacy: **`master` → production**)
- Jenkins credential IDs (branch): `{slug}_develop_credentials`, `{slug}_main_credentials` — each holds the corresponding environment JSON (preprod / prod)

## First-time Jenkins setup

1. Commit and push `CI/Jenkinsfile` (+ `CI/exclude-file.txt` if new)
2. Jenkins creates the job automatically when `CI/Jenkinsfile` is detected
3. **First build fails** — credentials not uploaded yet (expected)
4. Upload secret JSON files in Jenkins UI (see [jenkins-credentials-ui.md](./jenkins-credentials-ui.md))
5. Re-run build — should succeed
6. **Verify the deploy log rsync target** (`user@host:path`) belongs to this project and environment — a wrong secret under the right ID deploys to another project's server with `--delete` ([credential-files.md](./credential-files.md#verify-the-secret-content-matches-its-id-critical))

## Build retention

- Jenkins keeps **5 builds** max; older builds are discarded on each new run

## Incremental builds

`defineVariables()` skips composer, npm, asset build, and Cypress unless changed files match triggers, **except**:

- First **10** builds always run everything
- **Restarted** builds run everything

Changed-file triggers:

| Flag | Triggered by |
| --- | --- |
| `runComposer` | `composer.json`, `composer.lock`, `CI/Jenkinsfile` |
| `runNpm` | `package.json`, `package.lock`, `CI/Jenkinsfile` |
| `runBuild` | `.css`, `.scss`, `.js`, `.svg`, composer files, `CI/Jenkinsfile` |
| `runCypress` | `.php`, `.js`, `cypress`, composer files, `CI/Jenkinsfile` |

## Force a full rebuild

If the site looks stale after a long idle period and only non-trigger files changed:

- Touch `CI/Jenkinsfile` (e.g. add a space), commit, push — forces composer, npm, build, and remote finalize

## Troubleshooting post-deploy finalize

| Log / symptom | Likely cause | Fix |
| --- | --- | --- |
| `Unknown parameter(s) … ShellStep: timeout` | Invalid `timeout:` on `sh()` | Use template `sshRemoteSh()` with `timeout()` step |
| `ssh … cd … && wp …` on one `+` line (no heredoc) | Quoted SSH args stripped | Use `sshRemoteSh()` heredoc — see [post-deploy-finalize.md](./post-deploy-finalize.md) |
| `<<'EOF'set -ecd …` shown on one line in the step **label** | **Cosmetic** — Jenkins UI strips newlines in labels | Nothing to fix if the script itself is multiline (`/bin/bash: line N: …` errors prove it); real newline loss only comes from Groovy `\` line continuations |
| Exit **126** on finalize (often with no visible error) | `vendor/wp-cli/wp-cli/bin/wp` lost its **exec bit** (rsync), and `2>/dev/null` hid the `Permission denied` | Template `chmod +x` guard + never `2>/dev/null` on WP-CLI calls; on Kinsta set `"wpCliCommand": "wp"` — see [post-deploy-finalize.md](./post-deploy-finalize.md) |
| Exit **127** on finalize | WP-CLI/PHP not found **on remote** | Check `sshRemotePath` (Bedrock root with `vendor/`), `wpCliCommand` / `{WP_CLI_PHP}` / `phpPath` |
| Exit **127** with `&&` on Jenkins agent | Remote command ran locally | Same as quoting issue — fix `sshRemoteSh()` |
| rsync / ssh target shows **another project's** user or path | Wrong JSON uploaded under the branch credential ID | Fix the Jenkins secret; re-deploy the damaged project from its own job — see [credential-files.md](./credential-files.md#verify-the-secret-content-matches-its-id-critical) |

Manual check on the server:

```bash
cd {sshRemotePath}
which wp && wp cli version   # works → "wpCliCommand": "wp" in credentials
WP_CLI_PHP={WP_CLI_PHP} vendor/wp-cli/wp-cli/bin/wp wwp:doctrine-plugin-list
```

## Out of scope for this checklist

- Database migration (`CI/DB/`, `deployDbChanges`, `execDbChanges`) — not used in the generic pipeline
