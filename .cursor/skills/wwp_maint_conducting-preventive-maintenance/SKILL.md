---
name: conducting-preventive-maintenance
description: >-
  Orchestrates WonderWP preventive maintenance with an operator-facing checkbox
  runbook (timeline + resume), temporal catalogue/ledger under
  ai_resources/maintenance/, atomic scripts, WP-CLI pending/absorb/session,
  PHP/Node policy, PR to develop, semver minor tag, critical smoke heuristics,
  and a French client PDF report. Use when running a maintenance session,
  absorbing legacy concerns, resuming a paused maintenance, or modernizing the
  maintenance process on a WonderWP Bedrock site.
metadata:
  domain: wonderwp-maintenance
---

# Conducting Preventive Maintenance

## Goal

Run a complete preventive maintenance on a WonderWP site: only pending concerns (catalogue − ledger), with atomic scripts for deterministic work and human gates for deploy/security — while keeping the operator **guided step by step** via a durable checklist runbook (spirit of the old interactive script, without Podio / media / `maintenance.sh`).

Act as the **maintenance operator assistant**: announce mode maintenance **before** any scan; after scans, **invite opening the runbook**; **tick runbook boxes** as work completes; **enchaîner** through the routine **without stopping** the operator unless a **real gate** applies ([operator-autonomy.md](./references/operator-autonomy.md)).

## Rules

- **Never** use Podio. Ledger/sessions/runbooks live in `ai_resources/maintenance/`.
- **Never** run deprecated `maintenance.sh` (it exits 1). Use this skill + `scripts/run-atom.sh`.
- **No media sync step** — do not download/optimize/reupload medias as part of maintenance.
- **Operator guidance:** maintain `sessions/<sessionId>.runbook.md` as the human timeline (checkboxes). Update checkboxes **in the same turn** as completed work. Do **not** dump raw `pending` JSON as the operator plan — see [operator-runbook.md](./references/operator-runbook.md).
- **Catalogue gaps:** if the operator reports an issue not in the catalogue, ask whether it should become shared routine; if **yes**, open **separate proposal PRs** on `agencewonderful/wwp-maintenance` and `agencewonderful/ai-rules` — see [catalogue-gap-proposals.md](./references/catalogue-gap-proposals.md). Never mix those PRs with the site maintenance PR.
- Read `ddev wp maintenance:pending` (JSON) for **agent** planning; mirror progress into the runbook for the human.
- Absorb `one_shot` / `milestone_triggered` concerns with `maintenance:absorb` after they are done.
- `recurring` concerns are never absorbed as “done forever”; record session results only.
- Deploy: maintenance branch → **PR to `develop`** → CI preprod; then merge to prod branch + **semver minor** tag.
- PHP: floor **8.3**, target **8.5** (enforce Kinsta/OVEA; ask if hosting unknown). See [php-node-policy.md](./references/php-node-policy.md).
- **Hosting / URLs / accès :** often already in `CI/*_credentials.json` (preprod/prod). Read those first — see [ci-credentials.md](./references/ci-credentials.md). Do not commit or echo secrets.
- Node: upgrade if build succeeds and version is supported by ops Jenkins skill; sync DDEV + Jenkins; stop if irreconcilable.
- Client role migration is **conditional** — skip unless WP role `client` exists.
- **Scope:** **full maintenance** (runbook §1→§10) is the **default**. Do **not** ask the operator to pick “maintenance complète” unless they want a **reduced** scope (local-only, preprod-only, partial) — then note it in the runbook.
- **DB sync:** prefer **WP Migrate DB** when present — see [env-database-sync.md](./references/env-database-sync.md). Operator may **skip** DB import.
- **Autonomy:** after initial gates are recorded, **chain** §2→§10; do **not** ask « continue ? » between sections. Pause chat only for [operator-autonomy.md](./references/operator-autonomy.md) gates/blockers. Progress lives in the runbook.
- **Toolchain alignment:** if host PHP/Node lags behind Composer/DDEV/project config, **fix routing** (e.g. phpunit via DDEV) without bothering the operator — [env-toolchain-alignment.md](./references/env-toolchain-alignment.md).
- Delegate: `wwp_ops` (Jenkins templates/DDEV), `wwp_vcs` (branch/PR/tags), `wwp_backend` (code fixes).

```bash
ddev wp plugin activate wwp-maintenance
ddev wp maintenance:pending
ddev wp maintenance:session start --session="$(date +%Y-%m-%d)-$(basename "$PWD")" --branch="$(git branch --show-current)"
# then generate sessions/<id>.runbook.md from assets/operator-runbook.template.md
bash web/app/plugins/wwp-maintenance/scripts/run-atom.sh 02
```

## Workflow

```mermaid
flowchart LR
  O[AnnounceMode] --> A[Prepare]
  A --> B[Pending]
  B --> R[OperatorRunbook]
  R --> C[LocalAtoms]
  C --> D[LedgerFixes]
  D --> E[DevOpsBO]
  E --> F[PRtoDevelop]
  F --> G[CriticalSmoke]
  G --> H[ProdSemver]
  H --> I[ClientPdfLedger]
```

### Step 0: Announce maintenance mode (mandatory first response)

**Do:**

1. Send the operator the **Mode maintenance activé** message ([operator-runbook.md](./references/operator-runbook.md) — Session opening). No scans in chat before this.
2. Then proceed to Step 1–2 scans in the same turn if possible.

**Success criteria:** Operator knows maintenance is engaged and that initial scans are starting.

### Step 1: Prepare branch and tooling

**Do:**

1. Create/switch to a maintenance branch (`maintenance/YYYY-MM-DD` or similar) via vcs skill.
2. Ensure `.wonderful` submodule points to `agencewonderful/ai-rules`; update it; run `.wonderful/scripts/install.sh cursor` if present.
3. `ddev composer update agencewonderful/wwp-maintenance` when needed; activate plugin.
4. Database sync per [env-database-sync.md](./references/env-database-sync.md) (MigrateDB profiles → operator choice or skip; else SQL dump). Languages after DB if needed. **No media download.**
5. Start session: `ddev wp maintenance:session start --session=... --operator=... --branch=...`

**Success criteria:** Session JSON exists under `ai_resources/maintenance/sessions/`; plugin active.

### Step 2: Pending → operator runbook (guided plan)

**Do:**

1. Run `ddev wp maintenance:pending` and read JSON (**agent only**).
2. Group by `phase`: prepare → env → backend → frontend → devops → backoffice → plugins → preprod → prod → finalize.
3. Infer hosting / site URLs from `CI/*_credentials.json` when present ([ci-credentials.md](./references/ci-credentials.md)); only ask the operator if still unknown.
4. **Generate** `ai_resources/maintenance/sessions/<sessionId>.runbook.md` from [assets/operator-runbook.template.md](./assets/operator-runbook.template.md) following [operator-runbook.md](./references/operator-runbook.md):
   - Human French checkboxes by section (old-script spirit).
   - Pre-tick what Step 1 already completed.
   - Put concern ids under section footnotes / machine appendix — not as primary labels.
   - Set **Étape courante** and a one-paragraph **Où en est-on ?**
5. **Invite the operator to open the runbook** (required): give the path to `sessions/<sessionId>.runbook.md` and ask them to open it in the IDE. Optionally mention navigateur / canvas if they prefer — markdown stays source of truth ([operator-runbook.md](./references/operator-runbook.md)).
6. Short human summary only (branch, session, hosting, current step). **Default scope = complète** — state that once, do not quiz the operator on scope. Collect **only unresolved gates** in one batch (DB sync, PHP policy if unknown, …) — then **enchaîner** without re-asking.

**Success criteria:** Runbook exists and operator invited to open it; hosting known (`kinsta`/`ovea`/`unknown`) preferably from `CI/`; unresolved gates listed once or already recorded in runbook.

**Resume:** If the user says to continue / resume maintenance, **read the runbook first**, then session JSON + pending; continue from the first unchecked / `[!]` item.

### Step 3: Backend / frontend atoms

**Do:**

1. `HOSTING=... bash …/scripts/run-atom.sh 02` (PHP + composer checks/update).
2. Apply JSON check fixes; handle `milestone.php.target-8.5` / Node policy per [php-node-policy.md](./references/php-node-policy.md).
3. Run `03` plugins cycle, `04` doctrine proxies, `05` frontend build.
4. For pending legacy compliance / typing notices: fix via backend skill, then `maintenance:absorb`.
5. After each meaningful block: **update runbook checkboxes** + **Étape courante**. **Continue** to next atoms/sections in the same session without operator ping unless [operator-autonomy.md](./references/operator-autonomy.md) requires a stop.

**Success criteria:** Composer/npm green locally; absorbed one_shots recorded; runbook reflects progress; no unnecessary chat stops.

### Step 4: DevOps, BO, plugins

**Do:**

1. Align `CI/run-phpunit-tests.sh` with **wonderwp-base** fail-fast driver (`recurring.devops.phpunit-driver-wonderwp-base`); then phpunit `06`; cypress `07` if installed. PHPUnit: full driver output / exit code — per-suite results in runbook ([phpunit-runbook.md](./references/phpunit-runbook.md)); never mark OK on partial log tail.
2. Align Jenkinsfile / exclude-file from **ops skill templates** (preserve project-specific excludes + credential prefix).
3. If `ci.jenkinsfile-wave-2026-10-28` pending: search remote PRs about Jenkinsfile update (catalogue date 2026-10-28); ask to merge if found; absorb when done.
4. BO: audit admins, suspicious content; client-role migration only if role exists.
5. Plugins: ensure sitemap + SecuPress; verify wwp-cron; absorb remaining plugin one_shots when applicable.
6. Keep the runbook in sync (sections 5–7).

**Success criteria:** Tests addressed; CI files updated or deferred with notes; BO gates completed; runbook current.

### Step 5: PR → develop → preprod smoke

**Do:**

1. **Commit plan → PRs** ([operator-autonomy.md](./references/operator-autonomy.md)): inventory site + nested Wonderful repos; commit and push everything in scope (deps, lockfiles, CI, artefacts from atoms); open **site PR to `develop`** and **one PR per modified package repo**; then show all PR links and ask only for **arbitrage** (add/remove files, duplicate PRs). Do not ask permission to commit first.
2. After merge, CI deploys preprod.
3. Upload doctrine proxies to preprod if needed.
4. Confirm Jenkins green; visual smoke; run **critical smoke** heuristics ([critical-smoke-heuristics.md](./references/critical-smoke-heuristics.md) + optional `ai_resources/maintenance/critical-paths.json`).
5. WP DB update in admin if prompted.
6. Tick runbook section 8 when PRs are open and preprod path is clear.

**Success criteria:** Preprod OK + critical smoke reported; runbook section 8 accurate.

### Step 6: Prod + finalize

**Do:**

1. Merge develop → `master`/`main`.
2. Create **semver minor** tag from latest existing tags (bump middle digit).
3. Push prod; proxies; Jenkins; visual + critical smoke; translations check.
4. Generate client report PDF FR from [assets/client-maintenance-report.template.md](./assets/client-maintenance-report.template.md).
5. `maintenance:session finish` + ensure ledger `lastMaintenanceAt` updated; mark runbook status `done`; commit `ai_resources/maintenance/` (ledger + session JSON + **runbook**).

**Success criteria:** Prod live; ledger + runbook committed; PDF delivered to operator.

## Advanced Features

- **Operator autonomy (enchaîner)**: [references/operator-autonomy.md](./references/operator-autonomy.md)
- **Env toolchain alignment**: [references/env-toolchain-alignment.md](./references/env-toolchain-alignment.md)
- **PHPUnit runbook**: [references/phpunit-runbook.md](./references/phpunit-runbook.md)
- **Operator runbook**: [references/operator-runbook.md](./references/operator-runbook.md)
- **Catalogue gap PRs**: [references/catalogue-gap-proposals.md](./references/catalogue-gap-proposals.md)
- **DB sync (env)**: [references/env-database-sync.md](./references/env-database-sync.md)
- **Phases map**: [references/phases.md](./references/phases.md)
- **Inventory triage**: [references/inventory-triage.md](./references/inventory-triage.md)
- **Temporality**: [references/temporality-and-ledger.md](./references/temporality-and-ledger.md)
- **PHP/Node**: [references/php-node-policy.md](./references/php-node-policy.md)
- **CI credentials**: [references/ci-credentials.md](./references/ci-credentials.md)
- **Critical smoke**: [references/critical-smoke-heuristics.md](./references/critical-smoke-heuristics.md)
- **Plugin conditionals**: [references/plugin-conditional-checks.md](./references/plugin-conditional-checks.md)

## Resources

| Type | Path | Description |
| ---- | ---- | ----------- |
| Catalogue | `web/app/plugins/wwp-maintenance/catalog/catalogue.json` | Shared concerns |
| Scripts | `web/app/plugins/wwp-maintenance/scripts/` | Atomic runners |
| Ledger | `ai_resources/maintenance/ledger.json` | Site absorption memory |
| Session | `ai_resources/maintenance/sessions/<id>.json` | Machine session record |
| Runbook | `ai_resources/maintenance/sessions/<id>.runbook.md` | Operator timeline / checkboxes |
| Runbook template | `assets/operator-runbook.template.md` | Checklist scaffold |
| Client PDF template | `assets/client-maintenance-report.template.md` | Client PDF source (FR) |
