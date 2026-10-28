---
name: syncing-backlog-plan-with-github
description: >-
  Synchronise un fichier markdown de plan backlog enrichi vers GitHub : création d’épics (issues parentes),
  sous-issues liées, types d’issue d’organisation, assignations et ajout au Project v2, avec idempotence
  via tracker_url dans le markdown. Use when the user wants to push an enriched backlog plan to GitHub issues,
  bulk-create sub-issues from markdown, or automate creating-epics-and-issues-from-backlog-plan with the
  bundled Python script; align repo and categories with the consumer project agents.md.
metadata:
  domain: wonderwp-github
---

# Synchronisation d’un plan backlog avec GitHub

## Goal

Matérialiser sur GitHub les **épics** et **tickets** décrits dans un **plan backlog enrichi** (markdown), en respectant les types d’issue d’organisation et l’idempotence (`tracker_url`), à l’aide du script Python fourni dans cette skill.

## Rules

- Lire le `agents.md` du **dépôt consommateur** pour le dépôt GitHub par défaut, le projet (#), les catégories autorisées et les logins assignables avant d’ajuster les variables d’environnement ou d’exécuter le script.
- Aligner les variables `GH_ISSUE_OWNER`, `GH_ISSUE_REPO_NAME`, `GH_ISSUE_ORG`, `GITHUB_PROJECT_NUMBER` (ou `GITHUB_PROJECT_NODE_ID`) sur la configuration tracker du projet ; ne pas cibler une autre instance sans validation.
- Les tickets dont **assignee**, **category** ou **body** contiennent encore **`NEEDS CLARIFICATION`** ne sont **pas** créés ; l’épic sans `tracker_url` n’est créée que s’il existe **au moins un** enfant créable.
- Éviter les doublons : ne pas retirer les lignes `tracker_url` / `tracker_id` ajoutées par une exécution précédente ; le script ignore tout bloc qui a déjà un `tracker_url`.
- Titres et corps des issues suivent le plan (français par défaut pour les artefacts WonderWP).
- Point d’entrée du script : `skills/syncing-backlog-plan-with-github/scripts/sync_backlog_plan_to_github.py` (chemins relatifs au plugin `wonderwp-github`).

```bash
# Chemins relatifs depuis la racine du dépôt consommateur (ex. REC_prep)
python3 .wonderful/plugins/wonderwp-github/skills/syncing-backlog-plan-with-github/scripts/sync_backlog_plan_to_github.py \
  --plan ai_resources/backlog-plans/260505-backlog-plan.md --dry-run

python3 .wonderful/plugins/wonderwp-github/skills/syncing-backlog-plan-with-github/scripts/sync_backlog_plan_to_github.py \
  --plan ai_resources/backlog-plans/260505-backlog-plan.md --write-back
```

## Workflow

### Step 1 — Vérifier le plan et agents.md

**Do:**

1. Ouvrir le markdown du plan backlog enrichi ; repérer les blocs prêts (plus de `NEEDS CLARIFICATION` sur assignee/category/body) et les `tracker_url` déjà présents.
2. Lire `./agents.md` : dépôt, projet GitHub par défaut, liste des catégories (doit correspondre aux issue types d’organisation).
3. Exporter ou confirmer les variables d’environnement nécessaires si la cible n’est pas celle des défauts du script.

**Success criteria:** Cible repo/org/projet connue ; aucune catégorie hors liste autorisée pour les tickets à créer.

### Step 2 — Exécuter le script

**Do:**

1. Préparer l’auth (`gh auth token` fonctionnel ou token en variable).
2. Lancer `--dry-run` et contrôler la sortie stderr (épics / tickets candidats, skips).
3. Relancer sans `--dry-run`, avec `--write-back` si le plan doit être mis à jour avec les nouveaux `tracker_url`.

**Success criteria:** Issues créées sur GitHub ; si `--write-back`, le markdown reflète les `tracker_url` / `tracker_id`.

### Step 3 — Optionnel : projet et épics dupliquées

**Do:**

1. Si le dépôt attend les épics sur un Project org, vérifier que les issues apparaissent (le script envoie `projectV2Ids` lorsque le nœud projet est résolu).
2. Pour les épics dupliquées créées manuellement auparavant, traiter la consolidation hors script (fermer / archiver) selon la gouvernance projet.

**Success criteria:** Backlog GitHub lisible sans doublons inutiles.

## Advanced Features

- **Référence format & env** — [references/enriched-backlog-dependencies.md](references/enriched-backlog-dependencies.md)
- **Orchestration amont / aval** — enchaîner avec les skills PM *building-backlog-plan-from-draft* et *creating-epics-and-issues-from-backlog-plan* quand une procédure textuelle hors script est préférée.

## Resources

| Type | Path | Description |
| ---- | ---- | ----------- |
| Script | [scripts/sync_backlog_plan_to_github.py](scripts/sync_backlog_plan_to_github.py) | Synchronisation plan markdown → GitHub (GraphQL). |
| Reference | [references/enriched-backlog-dependencies.md](references/enriched-backlog-dependencies.md) | Format, catégories, idempotence, auth. |
