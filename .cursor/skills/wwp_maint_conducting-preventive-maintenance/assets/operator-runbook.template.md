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
- [ ] `agents.md` présent à la racine (générer via skill `initializing-agents-md` si absent) + absorb `one_shot.ctx.ensure-agents-md`
- [ ] Base de données : MigrateDB détecté → `wp migratedb profiles` listé **ou** fallback SQL **ou** `[-]` skip (DB locale conservée)
- [ ] Migration exécutée (`wp migratedb profile …` ou `ddev import-db`) si applicable
- [ ] Languages / traductions synchronisées (pas de sync médias)
- [ ] Alignement éventuel avec `develop` si pertinent (`recurring.git.sync-develop`)

_Concerns : `recurring.env.*`, `recurring.wonderful.submodule-and-install-skills`, `one_shot.ctx.ensure-agents-md`_

## 3. Backend

- [ ] Check version PHP (`maintenance:checkPhpVersion`) — floor 8.3, cible 8.5 selon policy / hébergeur
- [ ] Milestone PHP 8.5 traitée ou reportée avec note explicite
- [ ] Check infos Composer + corrections appliquées
- [ ] `composer update` + `composer audit` (atome `02`) OK
- [ ] `roots/wp-password-bcrypt` retiré (WP ≥ 6.8) + `composer/installers` ^2.3 si pending — puis absorb
- [ ] `config.fund: false` dans `composer.json` si pending (`legacy.composer.disable-fund`) — puis absorb
- [ ] Auto-updates activées architecturalement (`legacy.backend.enable-auto-updates`) si pending — puis absorb
- [ ] Auto-updates **vérifiées** (constantes + `enable_auto_updates` + cron `wp_version_check` / exécution réelle)
- [ ] Cycle plugins inactifs / réactivation (atome `03`)
- [ ] Proxies Doctrine générés (atome `04`)
- [ ] One-shots / legacy compliance restants corrigés + `maintenance:absorb` si applicable

_Concerns : `recurring.backend.*`, `milestone.php.target-8.5`, `legacy.composer.wp-password-bcrypt-remove`, `legacy.composer.disable-fund`, `legacy.backend.enable-auto-updates`, éventuels `legacy.*` encore pending_

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
- [ ] Page 403 custom Kinsta : `web/errors/403.html` depuis asset maintenance + ticket support (`legacy.hosting.custom-403-page`) — ou `[-]` si hors nginx-origin
- [ ] Branches stale nettoyées ou reportées

_Concerns : `recurring.devops.*`, `ci.jenkinsfile-wave-2026-10-28`, `legacy.hosting.custom-403-page`_

## 6. Back-office

- [ ] Audit comptes admins
- [ ] Contenu suspect / spam
- [ ] Migration rôle `client` — **uniquement si** le rôle WP existe, sinon `[-]`

_Concerns : `recurring.bo.*`_

## 7. Plugins métier

- [ ] Plugins versionnés commités si besoin
- [ ] `wwp-cron` : inventaire **complet** planifié + exécuté (Default WP Cron, `cron:prun-results --prune-storage-path=~/logs_cron`, + inventaires plugins)
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
