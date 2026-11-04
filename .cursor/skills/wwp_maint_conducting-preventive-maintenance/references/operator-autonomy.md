# Operator autonomy — when to pause vs enchaîner

The runbook gives the **human** visibility; the agent should **not** treat every section boundary as a chat stop.

## Default behaviour

**Enchaîner** through the maintenance routine (runbook §1→§10) **without interrupting** the operator between steps, as long as:

- Gates already recorded in the runbook are satisfied, or
- The step can be completed with defaults documented in the skill (e.g. skip conditional BO migration when role `client` absent, report milestone deferred when operator chose PHP 8.3).

After each block: **update the runbook** (checkboxes, **Étape courante**, **Où en est-on ?**). The operator follows progress in the IDE; chat stays quiet unless a rule below applies.

**Do not** ask « continue ? », « on enchaîne ? », or re-summarize the whole plan after every atom.

## Commit plan & PRs (not a pre-commit gate)

Maintenance **produces** commits; it does not stop and ask « on commit ? » before version control.

**Everything touched by maintenance belongs in the commit plan**, including:

- Third-party dependency version bumps (composer, npm) chosen during the session
- Files edited deliberately (CI, config, plugin code, docs)
- Files changed **indirectly** by commands you ran (lockfiles, compiled assets, codegen, proxy classes, etc.)

Build the plan **during** the session (runbook §8 or a **Plan de commit** subsection): list repos, branches, intended commits, and what is **excluded with reason** (e.g. local-only WP Rocket drift on `web/wp-config.php`).

### Multi-repo PRs (default)

1. **Site project** — one PR from the maintenance branch → `develop` with all in-scope site changes.
2. **Every modified Wonderful package** — separate branch + PR on its own repo (e.g. `wwp-cron`, `wwp-maintenance`, theme/plugin Composer paths that are git clones). Inventory nested repos (`git status` inside `web/app/plugins/*`, mu-plugins, etc.) before opening PRs.

**Order of operator contact for VCS:**

1. **Do first:** commit, push, open **all PRs you can** with the changes you already know belong there; document exclusions in PR bodies.
2. **Then:** one message with **links to every PR** (site + packages + catalogue/ai-rules proposals if any).
3. **Only then:** ask for **arbitrage** — files to add/remove from a PR, merge order, close duplicate PRs (e.g. Jenkins wave already on maintenance branch), not permission to start committing.

Delegating to `wwp_vcs` is fine; the agent still owns assembling the full PR list.

**Not a gate:** « voulez-vous que je committe ? » before §8 when the runbook work is done.

## When you MAY stop and ask (real gates)

| Situation | Example |
| --------- | ------- |
| **Irreversible / production** | Merge to prod, semver tag push, push that bypasses PR policy |
| **Operator-only input** | MigrateDB profile pick, SQL dump path, explicit **skip** vs sync DB, missing secret/credential |
| **Ambiguous strategy** | Composer/security fix: upgrade major vs ignore vs defer — several valid paths with different risk |
| **Catalogue gap** | New issue: should it join shared routine? (see catalogue-gap-proposals) |
| **Hard blocker** | Command fails and no safe default; need operator to choose retry / skip / abort |
| **Reduced scope** | Operator asked local-only or preprod-only — confirm before deploy phases |
| **Post-PR arbitrage** | After PR links are shown: include/exclude files, squash vs split, which duplicate PR to close |

## When you must NOT stop

- **Host vs DDEV PHP/Node mismatch** — fix script routing or DDEV config per [env-toolchain-alignment.md](./env-toolchain-alignment.md), re-run the atom, continue.
- Between atoms `02`–`07`, plugin checks, Jenkinsfile diff review (apply templates, note deferrals in runbook).
- After successful composer/npm/build — proceed to next runbook section.
- To « check in » or recap every section — use the runbook instead.
- To re-ask scope (**complète** is default) or a gate **already decided** in **§ Décisions & gates**.
- Before frontend build when Node/PHP policy was already set.

## Chat rhythm (suggested)

1. **Opening:** mode maintenance + scans + invite runbook (Step 0–2).
2. **Gate batch:** only unresolved gates in one short message (not 50 pending ids).
3. **During work:** silence or one line if a long command runs; no permission ping.
4. **Interrupt:** gate, blocker, preprod smoke URL check, or **after all PRs are opened** — links + arbitrage questions (not before commits/PRs exist).
5. **End of session / pause:** short status + runbook path if stopping mid-flight.

## Runbook is the progress UI

If the operator wants to know where things stand, they read the runbook — not a repeated chat questionnaire.
