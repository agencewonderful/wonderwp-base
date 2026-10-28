# Dépendances et format (sync backlog → GitHub)

## Fichiers consommateur

Le script attend un markdown aligné sur l’artifact **enriched backlog plan** tel que décrit dans les skills product-manager Wonderful (titres d’épics en majuscules, blocs `#### Ticket:` avec `assignee`, `category`, `body`, optionnellement `visual_reference`).

- Les **catégories** (`- category:`) doivent correspondre **exactement** aux types d’issues d’organisation GitHub déclarés pour l’organisation cible ; la liste canonique vivra en pratique dans le `agents.md` du dépôt projet (section *Allowed ticket categories*).

## Marqueurs idempotence

Ajouter après synchronisation :

- `- tracker_url: <URL issue GitHub>`
- `- tracker_id: "<numéro>"`

sur l’épic ou le ticket évite une double création au prochain passage.

## Outils

- **Authentification** : `gh auth login` ou variable `GITHUB_TOKEN` / `GH_TOKEN`.
- **Projet** : par défaut le script résout le Project v2 organisationnel via `GITHUB_PROJECT_NUMBER` (défaut `15`) ; un org différent se règle avec `GH_ISSUE_ORG` et éventuellement `GITHUB_PROJECT_NODE_ID`.