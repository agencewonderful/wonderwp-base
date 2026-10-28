# Environment credential JSON files

Local secret files consumed by `loadCreds("{slug}_${BRANCH_NAME}_credentials")` in the Jenkinsfile. **Never commit** — gitignore the filled files under `CI/`.

## Two naming layers (do not confuse them)

| Layer | Based on | Purpose |
| --- | --- | --- |
| **Local filename** | **Environment** (preprod, prod, …) | Human-readable: which hosting target the JSON describes |
| **Jenkins secret file ID** | **Git branch** (`develop`, `main`, …) | What the pipeline resolves at deploy time via `$BRANCH_NAME` |

The Jenkinsfile always loads credentials by **branch**:

```groovy
loadCreds("{slug}_${BRANCH_NAME}_credentials")
```

So the **ID** in Jenkins must match the branch name. The **file on disk** should describe the environment — when uploading, you assign the branch ID to the environment file content.

**Example mapping (new projects — production branch is `main`):**

| Local file (under `CI/`) | Jenkins secret file ID | Branch | Environment |
| --- | --- | --- | --- |
| `{slug}_preprod_credentials.json` | `{slug}_develop_credentials` | `develop` | Staging / preprod |
| `{slug}_prod_credentials.json` | `{slug}_main_credentials` | `main` | Production |

This makes the Jenkins credentials list readable: each **branch** credential points at an **environment** file — e.g. `develop` → preprod, `main` → prod.

**Legacy:** older repos still use `master` as the production branch → Jenkins ID `{slug}_master_credentials` (same prod JSON content). All **new** Wonderful projects use `main`, not `master`.

Use `preprod` / `prod` as default environment suffixes; `staging` / `production` are acceptable when they better match the hosting labels — **never** use branch names (`develop`, `main`, `master`) in local filenames.

## Location in the consumer project

Store credential files in **`CI/`** alongside `exclude-file.txt`:

```
CI/
├── exclude-file.txt
├── credentials.template.json       ← committed (empty template)
├── {slug}_preprod_credentials.json   ← local only, gitignored (staging env)
└── {slug}_prod_credentials.json      ← local only, gitignored (production env)
```

During pipeline setup:

1. Ensure `CI/` exists; copy [assets/credentials.template.json](../assets/credentials.template.json) to `CI/credentials.template.json` if missing.
2. Copy the template twice into **environment-named** filled files (still under `CI/`).
3. Add `CI/{slug}_*_credentials.json` to the **repo root** `.gitignore`.

`CI/` is already excluded from rsync deploy via `exclude-file.txt`, so credential JSON never reaches the remote server.

## JSON shape

Copy `CI/credentials.template.json` (same fields as the skill asset):

```json
{
  "sshServer": "host.example.com",
  "sshPort": 22,
  "sshUser": "ssh_user",
  "sshRemotePath": "/var/www/project",
  "siteUrl": "https://preprod.example.com",
  "cacheEnabled": true,
  "wpCliCommand": "wp",
  "phpPath": "/usr/bin/php8.5"
}
```

(`wpCliCommand` and `phpPath` are optional — see below.)

### Fields

| Field | Required | Notes |
| --- | --- | --- |
| `sshServer` | yes | Hostname or IP from hosting provider email |
| `sshPort` | when non-22 | SSH port (Kinsta / Cloudways / OVH often use custom ports per env) |
| `sshUser` | yes | SSH username |
| `sshRemotePath` | yes | Absolute path to Bedrock **project root** on the server (where `vendor/` and `wp-cli.yml` live after rsync) — run `pwd` there once after SSH; **not** the web subdirectory alone |
| `siteUrl` | yes | Public HTTPS URL (no trailing slash). Used for Slack links and Cypress `--env host` |
| `cacheEnabled` | recommended | `true` when `wwp-cache` is in `composer.json` (triggers post-deploy `wwp-cache:rebuild`) |
| `wpCliCommand` | per hosting | Full remote WP-CLI invocation. **Kinsta: `"wp"`** (WP-CLI is on the PATH with the right PHP). Omit / empty → template fallback `WP_CLI_PHP={WP_CLI_PHP} vendor/wp-cli/wp-cli/bin/wp` |
| `phpPath` | advanced | Remote PHP binary for the vendor fallback only, when it differs from `{WP_CLI_PHP}` — ignored when `wpCliCommand` is set |

### `wpCliCommand` per hosting

| Hosting | Value | Why |
| --- | --- | --- |
| Kinsta | `"wp"` | WP-CLI pre-installed on PATH, correct PHP, no exec-bit concern on vendor files |
| Ovea / OVH / classic | omit or `""` | Fallback `WP_CLI_PHP={WP_CLI_PHP} vendor/wp-cli/wp-cli/bin/wp` (template chmods the binary first) |
| Custom | any command | e.g. `"WP_CLI_PHP=php8.4 vendor/wp-cli/wp-cli/bin/wp"` |

### What does **not** go in this file

- **SSH passwords** — used only for initial access / installing Jenkins SSH keys on the agent; not stored in the JSON uploaded to Jenkins
- **Database credentials** — out of scope (this pipeline has no DB migration stage)
- **API keys** — stay in remote `.env`, never in Jenkins credential JSON

## Workflow

1. Copy `CI/credentials.template.json` → `CI/{slug}_preprod_credentials.json` (staging) and `CI/{slug}_prod_credentials.json` (production).
2. Fill values from the hosting provider email; confirm `sshRemotePath` via SSH + `pwd`.
3. Set `cacheEnabled: true` when `wwp-cache` is active.
4. Confirm `CI/{slug}_*_credentials.json` is in `.gitignore`.
5. Upload each filled file in Jenkins as a **Secret file** — **ID = branch**, not environment (see table above and [jenkins-credentials-ui.md](./jenkins-credentials-ui.md)):
   - Upload `{slug}_preprod_credentials.json` → ID `{slug}_develop_credentials`
   - Upload `{slug}_prod_credentials.json` → ID `{slug}_main_credentials` (or `{slug}_master_credentials` on legacy repos)
6. Re-run the failed first build and **verify the rsync target** (see below).

## Verify the secret content matches its ID (critical)

Jenkins cannot detect that the wrong JSON was uploaded under an ID. A mismatch makes the pipeline **deploy project A's code to project B's server with `rsync --delete`** — it wipes the other project's files (real incident: a narbovia build deployed to the nadorcott Kinsta site because `narbovia_develop_credentials` contained nadorcott's JSON).

After uploading (or whenever a deploy behaves oddly), check the **first `rsync` line** of the Deploy stage log:

```text
+ rsync … workspace/…_narbovia_develop/* narbovia@…:/www/narbovia_383/public
```

The remote `user@host:path` **must** belong to the same project and environment as the job. If the user or path names another project — stop, fix the secret in Jenkins, and re-deploy the damaged project from its own job to restore its files.

When several projects share a hosting provider IP (common on Kinsta), the SSH user and remote path are the only distinguishing values — double-check them at upload time.

## SSH port in the pipeline

When `sshPort` is set, the Jenkinsfile passes `-p` to `ssh` and `-e 'ssh -p …'` to `rsync`. Omit `sshPort` (or use `22`) for standard port hosts.
