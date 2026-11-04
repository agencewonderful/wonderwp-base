# Catalogue gap — new maintenance concerns

When the **human operator** reports a problem discovered **during** maintenance that is **not** covered by an existing catalogue concern (or runbook checkbox tied to the catalogue), treat it as a **catalogue gap**.

## Step 1 — Ask (before fixing blindly)

Use plain French, one question:

> Ce souci est-il susceptible de se reproduire sur **d’autres sites WonderWP** pendant une maintenance, et faut-il l’**ajouter à la routine partagée** (catalogue + skill) ?

| Answer | Action |
| ------ | ------ |
| **Non** (site-specific) | Fix only on this project; document in runbook **Notes de session** + session JSON; **no** upstream PR |
| **Oui** (should become routine) | Continue site fix if needed, **and** open **two separate proposal PRs** (see below) for Wonderful to review before merging into the shared routine |

Do not merge upstream PRs without explicit operator / maintainer approval.

## Step 2 — Proposal PRs (when answer is Oui)

Create **independent** branches and PRs — not mixed with the site maintenance PR to `develop`.

| Repo | Remote (typical) | What to change |
| ---- | ---------------- | -------------- |
| **wwp-maintenance** | `agencewonderful/wwp-maintenance` | `catalog/catalogue.json` — new concern (`id`, `kind`, `phase`, `severity`, `skill_step` / `script` / `check` as appropriate); optional atom script or WP-CLI check if deterministic |
| **ai-rules** | `agencewonderful/ai-rules` | `plugins/wonderwp-maintenance/` — skill step, runbook template section, or reference doc so agents orchestrate the new concern |

Use **wwp_github** + **wwp_vcs** skills; `gh pr create` with a body that includes:

1. **Contexte** — site where the gap was found (name only, no secrets)
2. **Problème** — symptom, when it appears in maintenance
3. **Cause** — if known
4. **Solution proposée** — code/config change + where it lives in the routine (phase, atom, gate)
5. **Kind** — suggest `one_shot`, `recurring`, or `milestone_triggered` and proposed `id` (e.g. `recurring.backend.foo` or `legacy.plugins.bar`)
6. **Décision demandée** — integrate into shared catalogue or reject

Link the two PRs to each other in the description.

Branch naming suggestion: `proposal/catalogue-<short-slug>-YYYY-MM-DD` on each repo.

## Step 3 — This site’s runbook

- Add a line under **Notes de session** or a short **Hors catalogue** subsection with the issue and whether a proposal PR was opened.
- Do **not** `maintenance:absorb` a concern id that does not exist in the deployed catalogue yet.

## Anti-patterns

- Silently patching only on one site when the operator said it should be global
- Stuffing site-specific hacks into `catalogue.json` on the project copy without an upstream PR
- One mega-PR mixing Soluna maintenance + ai-rules + wwp-maintenance
