# Maintenance — {{SITE_NAME}}

| | |
| --- | --- |
| **Session** | `{{SESSION_ID}}` |
| **Branche** | `{{GIT_BRANCH}}` |
| **Opérateur** | {{OPERATOR}} |
| **Démarrée le** | {{STARTED_AT}} |
| **Statut** | {{STATUS}} <!-- in_progress \| paused \| done --> |
| **Étape courante** | {{CURRENT_STEP}} |
| **Hébergement** | {{HOSTING}} <!-- kinsta \| ovea \| unknown (+ notes) --> |
| **URLs** | local {{URL_LOCAL}} · preprod {{URL_PREPROD}} · prod {{URL_PROD}} |

> **À l’opérateur :** ce document est ta **timeline guidée** (équivalent moderne du script pas-à-pas).  
> Cases : `[ ]` à faire · `[x]` fait · `[-]` sauté / N/A · `[!]` bloqué (note obligatoire).  
> **Reprise :** rouvre ce fichier et dis à l’agent *« reprends la maintenance »* — il lit le runbook + la session JSON.

## Où en est-on ?

{{PROGRESS_SUMMARY}}

---

## 1. Préparation

- [ ] Branche de maintenance créée / checkout (`maintenance/YYYY-MM-DD`)
- [ ] Submodule `.wonderful` à jour + skills installées (si applicable)
- [ ] Plugin `wwp-maintenance` à jour (`composer`) et **activé**
- [ ] Session WP-CLI démarrée (`maintenance:session start`)
- [ ] Runbook généré et ouvert pour l’opérateur

_Concerns : `recurring.git.maintenance-branch`, `recurring.composer.update-wwp-maintenance`, `recurring.plugin.activate-wwp-maintenance`_

## 2. Environnement local

_Scope session : **maintenance complète** (défaut skill) — ne demander autre profil que si l’opérateur le demande._

- [ ] Contexte hébergement / URLs lu depuis `CI/*_credentials.json` (sans coller de secrets)
- [ ] Base de données : MigrateDB détecté → `wp migratedb profiles` listé **ou** fallback SQL **ou** `[-]` skip (DB locale conservée)
- [ ] Migration exécutée (`wp migratedb profile …` ou `ddev import-db`) si applicable
- [ ] Languages / traductions synchronisées (pas de sync médias)
- [ ] Alignement éventuel avec `develop` si pertinent (`recurring.git.sync-develop`)

_Concerns : `recurring.env.*`, `recurring.wonderful.submodule-and-install-skills`_

## 3. Backend

- [ ] Check version PHP (`maintenance:checkPhpVersion`) — floor 8.3, cible 8.5 selon policy / hébergeur
- [ ] Milestone PHP 8.5 traitée ou reportée avec note explicite
- [ ] Check infos Composer + corrections appliquées
- [ ] `composer update` (atome `02`) OK
- [ ] Cycle plugins inactifs / réactivation (atome `03`)
- [ ] Proxies Doctrine générés (atome `04`)
- [ ] One-shots / legacy compliance restants corrigés + `maintenance:absorb` si applicable

_Concerns : `recurring.backend.*`, `milestone.php.target-8.5`, éventuels `legacy.*` encore pending_

## 4. Frontend

- [ ] Policy Node : version actuelle vs `package.json` / DDEV / Jenkins
- [ ] Milestone Node : bump tenté ou stop documenté si irréconciliable
- [ ] `npm install` / audit (atome `05`)
- [ ] Build assets / export (atome `05`) OK
- [ ] Smoke JS front (pages clés)
- [ ] Smoke JS admin (back-office)
- [ ] Doc SCSS plugins versionnés si pertinent

_Concerns : `recurring.frontend.*`, `milestone.node.upgrade-if-supported`_

## 5. DevOps & CI

- [ ] PHPUnit — `CI/run-phpunit-tests.sh` aligné **wonderwp-base** (fail-fast, toutes suites)
- [ ] PHPUnit — atome `06` / driver (via **DDEV** si stack up)
- [ ] PHPUnit — **toutes les suites vertes** (code sortie 0) ou `[!]` avec tableau des échecs ci-dessous
- [ ] PHPUnit — détail par suite (plugin) :

| Suite | Résultat | Notes |
| ----- | -------- | ----- |
| | | |

- [ ] Cypress (atome `07`) — lancé ou N/A noté
- [ ] Jenkinsfile aligné template ops (préserver exclus + préfixe credentials projet)
- [ ] Exclude-file aligné template ops
- [ ] Wave Jenkinsfile (`ci.jenkinsfile-wave-2026-10-28`) : PR remote cherchée / mergée / absorbée ou reportée
- [ ] Branches stale nettoyées ou reportées

_Concerns : `recurring.devops.*`, `ci.jenkinsfile-wave-2026-10-28`_

## 6. Back-office

- [ ] Audit comptes admins
- [ ] Contenu suspect / spam
- [ ] Rôle `client` — migration comptes + alignement des caps (**uniquement si** le rôle WP existe, sinon `[-]`) — voir `client-role-migration`

_Concerns : `recurring.bo.*`_

## 7. Plugins métier

- [ ] Plugins versionnés commités si besoin
- [ ] `wwp-cron` vérifié
- [ ] Sitemap (Google XML / équivalent) présent
- [ ] SecuPress présent / configuré a minima

_Concerns : `recurring.plugins.*`_

## 8. Préproduction

- [ ] **Gate :** PR maintenance → **`develop`** ouverte (pas de push direct)
- [ ] Merge + déploiement CI preprod
- [ ] Upload proxies Doctrine preprod si besoin
- [ ] Jenkins preprod vert
- [ ] Smoke visuel preprod
- [ ] Smoke **chemins critiques** preprod
- [ ] Mise à jour BDD WP admin preprod si proposée

_Concerns : `recurring.deploy.*` (phase preprod)_

## 9. Production

- [ ] **Gate :** merge `develop` → branche prod (`main` / `master`)
- [ ] Tag **semver minor** créé et poussé
- [ ] Push / déploiement prod
- [ ] Upload proxies prod si besoin
- [ ] Jenkins prod vert
- [ ] Smoke visuel + chemins critiques prod
- [ ] Mise à jour BDD WP admin prod si proposée
- [ ] Traductions manquantes contrôlées

_Concerns : `recurring.deploy.*` (phase prod), `recurring.deploy.semver-minor-tag`_

## 10. Finalisation

- [ ] Rapport client PDF (FR) généré et remis à l’opérateur
- [ ] `maintenance:session finish`
- [ ] Ledger mis à jour (`lastMaintenanceAt`, absorbs, hosting)
- [ ] Commit de `ai_resources/maintenance/` (runbook + session + ledger)

_Concerns : `recurring.finalize.*`_

---

## Décisions & gates en attente

_Scope : **complète** par défaut (skill). Gates réels ci-dessous._

| Date | Sujet | Décision / blocage |
| ---- | ----- | ------------------ |
| {{STARTED_AT}} | Sync base (MigrateDB / SQL / skip) | {{DB_SYNC_NOTES}} |

## One-shots & milestones (détail machine)

Liste issue de `maintenance:pending` — **ne pas** coller ça brut dans le chat opérateur ; servir au runbook et à l’agent.

{{PENDING_ONESHOTS_MILESTONES}}

## Hors catalogue (session)

_Soucis signalés pendant la maintenance, absents du catalogue — voir skill `catalogue-gap-proposals`._

| Date | Problème | Routine partagée ? | PR proposal wwp-maintenance / ai-rules |
| ---- | -------- | ------------------ | -------------------------------------- |
| | | | |

## Notes de session

{{SESSION_NOTES}}

---

*Fichier canonique de suivi opérateur — skill `conducting-preventive-maintenance`.  
Companion machine : `sessions/{{SESSION_ID}}.json` · ledger : `../ledger.json`.*
