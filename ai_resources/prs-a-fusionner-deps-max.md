# PR à fusionner — montée de dépendances framework (PHP ^8.3) + vague tests

**Généré le :** 2026-10-28  
**Mis à jour le :** 2026-10-28 (vague **N** ✅ MERGED · `composer update` hôte · PR wonderwp-base `chore/php-8.5-compat`)  
**Chantiers :** Dependabot + [Monter au maximum les versions…](https://app.notion.com/p/403d89b334fb8138856dfa67460682a8) + fiabilité Unit/Integration (P0–P2)  
**Branche feature typique :** `chore/deps-max` → bases `feature/php_8` / variantes  
**Règle :** Jeremy merge (pas d’auto-merge), sauf vagues triviales (ex. L CI) déléguées. **Pas de stash « perdu »** : PR ou drop explicite.

---

## Légende

| Colonne | Signification |
|--------|----------------|
| **Ordre** | Ordre de fusion recommandé (croissant) |
| **Vague** | Groupe logique (fondations → API → méta → plugins) |
| **Base** | Branche cible de la PR |
| **État** | Suivi : `✅ MERGED` · `🔲 OPEN` · `⚠️ CONFLICT` · `⛔ CLOSED` |

**Restant à merger (utile) :** **1** — PR hôte wonderwp-base `chore/php-8.5-compat` (PHP 8.5 + lock deps-max/N + runners Integration + SessionHelper).  
**Vagues C–H / J / K / L / M / N :** ✅ terminées.  
**Plugins locaux :** develop tip · **0 dirty · 0 stash**.

---

## Vue d’ensemble par catégorie

| # | Catégorie | Nb | Mergées | Restantes | Action |
|---|-----------|---:|--------:|----------:|--------|
| A | À **fermer sans merger** | 1 | — | 0 | ✅ Asset#5 déjà fermée |
| B | **Sécurité Dependabot** | 1 | 1 | 0 | Documentation#2 mergée |
| C | Framework — **vague 1** fondations (deps) | 18 (+ Repo#5 tests) | **19** | **0** | ✅ Terminée |
| D | Framework — **vague 2** intermédiaires (deps) | 7 | **7** | **0** | ✅ Terminée |
| E | Framework — **vague 3** sensibles (deps + tests) | 5 | **5** | **0** | ✅ Terminée |
| F | Framework — **vague 4** agrégateurs | 2 deps + 2 tests | **4** | **0** | ✅ Terminée |
| G | Framework — **vague 5** méta-package | 1 | **1** | **0** | ✅ Terminée |
| J | Framework — **vague tests** (post-deps C/D) | 22 | **22** | **0** | ✅ Terminée (J1+J2+J3) |
| L | Framework — **CI `pull_request` only** | 24 | **24** | **0** | ✅ Terminée (squash-merge autonome) |
| H | Plugins — **attributs WPApi** | 6 | **6** | **0** | ✅ Terminée (API#5 + 6 plugins) |
| K | Plugins / core / base — **fix PHPUnit suite** | 6 | **5** | **0** utiles | 1 CLOSED (fix déjà sur develop) |
| M | Unblockers / PHP8 suite | 3 | **3** | **0** | ✅ Taxonomy#4 · plugin#61 · [#62](https://github.com/agencewonderful/wonderwp-plugin/pull/62) |
| N | Plugins / theme — **nullables PHP 8.4** (ex-dirty/stash) | 17 | **17** | **0** | ✅ Terminée |
| O | Hôte wonderwp-base — **PHP 8.5 + lock** | 1 | 0 | **1** | 🔲 OPEN `chore/php-8.5-compat` |
| I | Hors périmètre | 2 | — | — | Ignorer |

---

## A — Fermer sans merger

| Ordre | Repo | PR | Titre | Base | État | Pourquoi |
|------:|------|-----|-------|------|------|----------|
| — | [wonderwp/Asset](https://github.com/wonderwp/Asset) | [#5](https://github.com/wonderwp/Asset/pull/5) | fix(security): bump phpunit à ^9.6 | `feature/php_8` | ⛔ CLOSED | Doublon / supersédé par Asset#6 |

---

## B — Sécurité Dependabot (référence)

| Ordre | Repo | PR | Titre | Base | État |
|------:|------|-----|-------|------|------|
| — | [wonderwp/Documentation](https://github.com/wonderwp/Documentation) | [#2](https://github.com/wonderwp/Documentation/pull/2) | fix(security): league/commonmark 2.10.4 | `master` | ✅ MERGED |
| — | wonderwp/wonderwp | #4 | phpunit (ancienne) | — | ⛔ CLOSED |

---

## C — Vague 1 — Fondations deps (ordre 1–18) — ✅ MERGED

| Ordre | Package | PR | Base | Contenu principal | État | Lien |
|------:|---------|-----|------|-------------------|------|------|
| 1 | functions | [#2](https://github.com/wonderwp/Functions/pull/2) | `feature/php_8` | php ^8.3 | ✅ MERGED | [PR](https://github.com/wonderwp/Functions/pull/2) |
| 2 | logging | [#2](https://github.com/wonderwp/Logging/pull/2) | `feature/php_8` | php ^8.3 | ✅ MERGED | [PR](https://github.com/wonderwp/Logging/pull/2) |
| 3 | dependency-injection | [#2](https://github.com/wonderwp/DependencyInjection/pull/2) | `feature/php_8` | php ^8.3, pimple ^3.5 | ✅ MERGED | [PR](https://github.com/wonderwp/DependencyInjection/pull/2) |
| 4 | filter | [#2](https://github.com/wonderwp/Filter/pull/2) | `feature/php_8` | php ^8.3 | ✅ MERGED | [PR](https://github.com/wonderwp/Filter/pull/2) |
| 5 | response | [#1](https://github.com/wonderwp/Response/pull/1) | `feature/php8` | php ^8.3 ; **drop** `johnpbloch/wordpress` require-dev | ✅ MERGED | [PR](https://github.com/wonderwp/Response/pull/1) |
| 6 | notification | [#3](https://github.com/wonderwp/Notification/pull/3) | `feature/php_8` | php ^8.3 | ✅ MERGED | [PR](https://github.com/wonderwp/Notification/pull/3) |
| 7 | cache | [#4](https://github.com/wonderwp/Cache/pull/4) | `feature/php_8` | php ^8.3, wp-dev ^7 | ✅ MERGED | [PR](https://github.com/wonderwp/Cache/pull/4) |
| 8 | shortcode | [#2](https://github.com/wonderwp/ShortCode/pull/2) | `feature/php_8` | php ^8.3 | ✅ MERGED | [PR](https://github.com/wonderwp/ShortCode/pull/2) |
| 9 | task | [#2](https://github.com/wonderwp/Task/pull/2) | `feature/php_8` | php ^8.3 | ✅ MERGED | [PR](https://github.com/wonderwp/Task/pull/2) |
| 10 | template | [#3](https://github.com/wonderwp/Template/pull/3) | `feature/php_8` | php ^8.3 | ✅ MERGED | [PR](https://github.com/wonderwp/Template/pull/3) |
| 11 | panel | [#2](https://github.com/wonderwp/Panel/pull/2) | `feature/php_8` | php ^8.3, wp-dev ^7 | ✅ MERGED | [PR](https://github.com/wonderwp/Panel/pull/2) |
| 12 | mailing | [#3](https://github.com/wonderwp/Mailing/pull/3) | `feature/php_8` | php ^8.3 | ✅ MERGED | [PR](https://github.com/wonderwp/Mailing/pull/3) |
| 13 | repository | [#4](https://github.com/wonderwp/Repository/pull/4) | `feature/php_8` | php ^8.3 ; typage | ✅ MERGED | [PR](https://github.com/wonderwp/Repository/pull/4) |
| 13b | repository | [#5](https://github.com/wonderwp/Repository/pull/5) | `feature/php_8` | Unit + Integration Taxonomy smoke | ✅ MERGED | [PR](https://github.com/wonderwp/Repository/pull/5) |
| 14 | http | [#2](https://github.com/wonderwp/Http/pull/2) | `feature/php_8` | php ^8.3, wp-dev ^7 | ✅ MERGED | [PR](https://github.com/wonderwp/Http/pull/2) |
| 15 | block-bindings-source | [#1](https://github.com/wonderwp/block-bindings-source/pull/1) | `feature/definition` | php ^8.3 | ✅ MERGED | [PR](https://github.com/wonderwp/block-bindings-source/pull/1) |
| 16 | block-style | [#1](https://github.com/wonderwp/BlockStyle/pull/1) | `feature/definition` | php ^8.3 | ✅ MERGED | [PR](https://github.com/wonderwp/BlockStyle/pull/1) |
| 17 | block-type | [#1](https://github.com/wonderwp/BlockType/pull/1) | `feature/blocktype_definition` | php ^8.3 | ✅ MERGED | [PR](https://github.com/wonderwp/BlockType/pull/1) |
| 18 | block-variations | [#1](https://github.com/wonderwp/BlockVariation/pull/1) | `feature/definition` | php ^8.3 | ✅ MERGED | [PR](https://github.com/wonderwp/BlockVariation/pull/1) |

---

## D — Vague 2 — Couches intermédiaires deps (ordre 19–25) — ✅ MERGED

| Ordre | Package | PR | Base | Contenu principal | État | Lien |
|------:|---------|-----|------|-------------------|------|------|
| 19 | service | [#3](https://github.com/wonderwp/Service/pull/3) | `feature/php_8` | php ^8.3, phpunit ^12 | ✅ MERGED | [PR](https://github.com/wonderwp/Service/pull/3) |
| 20 | sanitizer | [#2](https://github.com/wonderwp/Sanitizer/pull/2) | `feature/php_8` | php ^8.3, phpunit ^12 | ✅ MERGED | [PR](https://github.com/wonderwp/Sanitizer/pull/2) |
| 21 | http-foundation | [#4](https://github.com/wonderwp/HttpFoundation/pull/4) | `feature/increase_http_foundation_lib_version` | php ^8.3, **symfony/http-foundation ^7.4** | ✅ MERGED | [PR](https://github.com/wonderwp/HttpFoundation/pull/4) |
| 22 | form | [#10](https://github.com/wonderwp/Form/pull/10) | `feature/increase_validation_lib_version` | php ^8.3, **respect/validation ^2.5** | ✅ MERGED | [PR](https://github.com/wonderwp/Form/pull/10) |
| 23 | customposttype | [#3](https://github.com/wonderwp/CPT/pull/3) | `feature/php_8` | php ^8.3, wp-dev ^7 | ✅ MERGED | [PR](https://github.com/wonderwp/CPT/pull/3) |
| 24 | custom-fields | [#1](https://github.com/wonderwp/CustomFields/pull/1) | `feature/php8` | php ^8.3, wp-dev ^7 | ✅ MERGED | [PR](https://github.com/wonderwp/CustomFields/pull/1) |
| 25 | taxonomy | [#1](https://github.com/wonderwp/Taxonomy/pull/1) | `feature/php8` | php ^8.3, wp-dev ^7 | ✅ MERGED | [PR](https://github.com/wonderwp/Taxonomy/pull/1) |

---

## E — Vague 3 — Ruptures / packages sensibles (deps + tests) (ordre 26–30) — ✅ MERGED

Deps + Unit/Integration / CI pour api · asset · hook · media · routing.

| Ordre | Package | PR | Base | Contenu principal | État | Lien |
|------:|---------|-----|------|-------------------|------|------|
| 26 | **api** | [#5](https://github.com/wonderwp/API/pull/5) | `feature/php_8` | php ^8.3, **attributs PHP** ; Unit endpoints + Integration smoke | ✅ MERGED | [PR](https://github.com/wonderwp/API/pull/5) |
| 27 | asset | [#6](https://github.com/wonderwp/Asset/pull/6) | `feature/php_8` | php ^8.3, **symfony/asset ^7.4**, phpunit ^12, tests adaptés | ✅ MERGED | [PR](https://github.com/wonderwp/Asset/pull/6) |
| 28 | hook | [#2](https://github.com/wonderwp/Hook/pull/2) | `feature/php_8` | php ^8.3 + Unit deepen + CI | ✅ MERGED | [PR](https://github.com/wonderwp/Hook/pull/2) |
| 29 | media | [#3](https://github.com/wonderwp/Media/pull/3) | `feature/php_8` | php ^8.3 + Unit deepen + CI | ✅ MERGED | [PR](https://github.com/wonderwp/Media/pull/3) |
| 30 | routing | [#2](https://github.com/wonderwp/Routing/pull/2) | `feature/php_8` | php ^8.3 + Unit deepen + CI | ✅ MERGED | [PR](https://github.com/wonderwp/Routing/pull/2) |

---

## F — Vague 4 — Agrégateurs — ✅ MERGED

Deps + tests Search / PluginSkeleton.

| Ordre | Package | PR | Base | Contenu principal | État | Lien |
|------:|---------|-----|------|-------------------|------|------|
| 31 | search | [#8](https://github.com/wonderwp/Search/pull/8) | `feature/php_8` | php ^8.3, wp-dev ^7 | ✅ MERGED | [PR](https://github.com/wonderwp/Search/pull/8) |
| 31b | search | [#9](https://github.com/wonderwp/Search/pull/9) | `feature/php_8` | Unit SQL/limit-offset + CI stubs | ✅ MERGED | [PR](https://github.com/wonderwp/Search/pull/9) |
| 32 | plugin-skeleton | [#7](https://github.com/wonderwp/PluginSkeleton/pull/7) | `feature/php_8` | php ^8.3, wp-dev ^7 | ✅ MERGED | [PR](https://github.com/wonderwp/PluginSkeleton/pull/7) |
| 32b | plugin-skeleton | [#8](https://github.com/wonderwp/PluginSkeleton/pull/8) | `feature/php_8` | AbstractManager Unit + CI | ✅ MERGED | [PR](https://github.com/wonderwp/PluginSkeleton/pull/8) |

---

## G — Vague 5 — Méta-package (ordre 33) — ✅ MERGED

| Ordre | Package | PR | Base | Contenu principal | État | Lien |
|------:|---------|-----|------|-------------------|------|------|
| 33 | wonderwp | [#5](https://github.com/wonderwp/wonderwp/pull/5) | `feature` | php ^8.3 + `docs/testing.md` + `bin/run-all-{unit,integration}.sh` + meta Unit | ✅ MERGED | [PR](https://github.com/wonderwp/wonderwp/pull/5) |

---

## J — Vague tests — post-deps C/D (parallèle OK)

Deps déjà sur la base : merger dès que CI Unit verte. Inclut bootstrap host-autoload (évite clash PHPUnit 9 hôte / 12 package).

### J1 — P0 contrats critiques

| Ordre | Package | PR | Base | Contenu | État | Lien |
|------:|---------|-----|------|---------|------|------|
| 40 | repository | [#6](https://github.com/wonderwp/Repository/pull/6) | `feature/php_8` | Post/User Unit + smoke-posts + host-autoload | ✅ MERGED | [PR](https://github.com/wonderwp/Repository/pull/6) |
| 41 | plugin-skeleton | → F #8 | — | voir F | ✅ MERGED | — |
| 42 | customposttype | [#4](https://github.com/wonderwp/CPT/pull/4) | `feature/php_8` | Unit/Integration rewrites + registration + CI stubs | ✅ MERGED | [PR](https://github.com/wonderwp/CPT/pull/4) |
| 43 | taxonomy | [#2](https://github.com/wonderwp/Taxonomy/pull/2) | `feature/php8` | Unit/Integration registerTaxonomy + CI stubs | ✅ MERGED | [PR](https://github.com/wonderwp/Taxonomy/pull/2) |
| 44 | sanitizer | [#3](https://github.com/wonderwp/Sanitizer/pull/3) | `feature/php_8` | Unit normalize + sanitizeData edges + CI | ✅ MERGED | [PR](https://github.com/wonderwp/Sanitizer/pull/3) |
| 45 | custom-fields | [#2](https://github.com/wonderwp/CustomFields/pull/2) | `feature/php8` | **fix** registry `getKey` default + Unit + CI | ✅ MERGED | [PR](https://github.com/wonderwp/CustomFields/pull/2) |

### J2 — P1 deepen / CI

| Ordre | Package | PR | Base | Contenu | État | Lien |
|------:|---------|-----|------|---------|------|------|
| 46 | service | [#4](https://github.com/wonderwp/Service/pull/4) | `feature/php_8` | GHA CI, cache pin, host-autoload | ✅ MERGED | [PR](https://github.com/wonderwp/Service/pull/4) |
| 47 | http-foundation | [#5](https://github.com/wonderwp/HttpFoundation/pull/5) | `feature/increase_http_foundation_lib_version` | Request singleton Unit + CI | ✅ MERGED | [PR](https://github.com/wonderwp/HttpFoundation/pull/5) |
| 48 | search | → F #9 | — | voir F | ✅ MERGED | — |
| 49 | cache | [#5](https://github.com/wonderwp/Cache/pull/5) | `feature/php_8` | TransientCache empty-value Unit + CI | ✅ MERGED | [PR](https://github.com/wonderwp/Cache/pull/5) |

*(api / asset / hook / media / routing deepen = tous mergés via E)*

### J3 — P2 breadth (scaffolds Unit + CI) — ✅ MERGED

| Ordre | Package | PR | Base | État | Lien |
|------:|---------|-----|------|------|------|
| 50 | form | [#11](https://github.com/wonderwp/Form/pull/11) | `feature/increase_validation_lib_version` | ✅ MERGED | [PR](https://github.com/wonderwp/Form/pull/11) |
| 51 | functions | [#3](https://github.com/wonderwp/Functions/pull/3) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Functions/pull/3) |
| 52 | dependency-injection | [#3](https://github.com/wonderwp/DependencyInjection/pull/3) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/DependencyInjection/pull/3) |
| 53 | filter | [#3](https://github.com/wonderwp/Filter/pull/3) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Filter/pull/3) |
| 54 | http | [#3](https://github.com/wonderwp/Http/pull/3) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Http/pull/3) |
| 55 | logging | [#3](https://github.com/wonderwp/Logging/pull/3) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Logging/pull/3) |
| 56 | mailing | [#4](https://github.com/wonderwp/Mailing/pull/4) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Mailing/pull/4) |
| 57 | notification | [#4](https://github.com/wonderwp/Notification/pull/4) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Notification/pull/4) |
| 58 | panel | [#3](https://github.com/wonderwp/Panel/pull/3) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Panel/pull/3) |
| 59 | response | [#2](https://github.com/wonderwp/Response/pull/2) | `feature/php8` | ✅ MERGED | [PR](https://github.com/wonderwp/Response/pull/2) |
| 60 | shortcode | [#3](https://github.com/wonderwp/ShortCode/pull/3) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/ShortCode/pull/3) |
| 61 | task | [#3](https://github.com/wonderwp/Task/pull/3) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Task/pull/3) |
| 62 | template | [#4](https://github.com/wonderwp/Template/pull/4) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Template/pull/4) |
| 63 | block-bindings-source | [#2](https://github.com/wonderwp/block-bindings-source/pull/2) | `feature/definition` | ✅ MERGED | [PR](https://github.com/wonderwp/block-bindings-source/pull/2) |

Skipped (pas de scaffold tests dans cette vague) : `block-style`, `block-type`, `block-variations`.

**J3 / vague J :** ✅ **MERGED** (14/14 + J1/J2). Le fix CI `pull_request` only était déjà sur ces branches `chore/deps-max`.

---

## L — CI : `pull_request` only (plus de `push` nu) — ✅ MERGED

Correctif `chore/ci-pr-only` squash-mergé sur les bases feature (2026-10-28). Diff vérifié : uniquement retrait de `push:` dans `.github/workflows/ci.yml`.

Les 6 J3 (Panel · Response · ShortCode · Task · Template · block-bindings-source) ont reçu le même fix via `chore/deps-max` avant merge — pas de PR L séparée.

| Package | PR | Base | État | Lien |
|---------|-----|------|------|------|
| api | [#6](https://github.com/wonderwp/API/pull/6) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/API/pull/6) |
| asset | [#7](https://github.com/wonderwp/Asset/pull/7) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Asset/pull/7) |
| cache | [#6](https://github.com/wonderwp/Cache/pull/6) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Cache/pull/6) |
| custom-fields | [#3](https://github.com/wonderwp/CustomFields/pull/3) | `feature/php8` | ✅ MERGED | [PR](https://github.com/wonderwp/CustomFields/pull/3) |
| customposttype | [#5](https://github.com/wonderwp/CPT/pull/5) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/CPT/pull/5) |
| dependency-injection | [#4](https://github.com/wonderwp/DependencyInjection/pull/4) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/DependencyInjection/pull/4) |
| filter | [#4](https://github.com/wonderwp/Filter/pull/4) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Filter/pull/4) |
| form | [#12](https://github.com/wonderwp/Form/pull/12) | `feature/increase_validation_lib_version` | ✅ MERGED | [PR](https://github.com/wonderwp/Form/pull/12) |
| functions | [#4](https://github.com/wonderwp/Functions/pull/4) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Functions/pull/4) |
| hook | [#3](https://github.com/wonderwp/Hook/pull/3) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Hook/pull/3) |
| http-foundation | [#6](https://github.com/wonderwp/HttpFoundation/pull/6) | `feature/increase_http_foundation_lib_version` | ✅ MERGED | [PR](https://github.com/wonderwp/HttpFoundation/pull/6) |
| http | [#4](https://github.com/wonderwp/Http/pull/4) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Http/pull/4) |
| logging | [#4](https://github.com/wonderwp/Logging/pull/4) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Logging/pull/4) |
| mailing | [#5](https://github.com/wonderwp/Mailing/pull/5) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Mailing/pull/5) |
| media | [#4](https://github.com/wonderwp/Media/pull/4) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Media/pull/4) |
| notification | [#5](https://github.com/wonderwp/Notification/pull/5) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Notification/pull/5) |
| plugin-skeleton | [#9](https://github.com/wonderwp/PluginSkeleton/pull/9) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/PluginSkeleton/pull/9) |
| repository | [#7](https://github.com/wonderwp/Repository/pull/7) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Repository/pull/7) |
| routing | [#3](https://github.com/wonderwp/Routing/pull/3) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Routing/pull/3) |
| sanitizer | [#4](https://github.com/wonderwp/Sanitizer/pull/4) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Sanitizer/pull/4) |
| search | [#10](https://github.com/wonderwp/Search/pull/10) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Search/pull/10) |
| service | [#5](https://github.com/wonderwp/Service/pull/5) | `feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Service/pull/5) |
| taxonomy | [#3](https://github.com/wonderwp/Taxonomy/pull/3) | `feature/php8` | ✅ MERGED | [PR](https://github.com/wonderwp/Taxonomy/pull/3) |
| wonderwp | [#6](https://github.com/wonderwp/wonderwp/pull/6) | `feature` | ✅ MERGED | [PR](https://github.com/wonderwp/wonderwp/pull/6) |

---

## H — Plugins consommateurs — attributs WPApi (ordre 34–39)

Migrent `@WPApi*` → `#[WPApiNamespace]` / `#[WPApiEndpoint]`. Gate [wonderwp/API#5](https://github.com/wonderwp/API/pull/5) ✅ **MERGED** — merger H possible.

### Gate locale merge (Integration smoke, pas Cypress)

Chaque plugin H versionne `tests/Integration/smoke-api-routes.php` (contrat = `wonderwp/api` Integration : `wp eval-file` + `rest_get_server()`). Agrégateur hôte :

```bash
# un plugin
ONLY=wwp-cache WP_BIN="ddev wp" bash bin/run-plugin-integration.sh
# ou
ddev wp eval-file web/app/plugins/wwp-cache/tests/Integration/smoke-api-routes.php

# toute la vague H (plugins)
WP_BIN="ddev wp" bash bin/run-plugin-integration.sh

# framework + plugins (miroir de CI/run-phpunit-tests.sh)
WP_BIN="ddev wp" bash CI/run-integration-tests.sh
# plugins only / un plugin :
SKIP_FRAMEWORK=1 ONLY=wwp-cache WP_BIN="ddev wp" bash CI/run-integration-tests.sh
```

**Avant de merger une PR H** : smoke du plugin = PASS. Si `enableApi=false` (alerte / contact / rgpd / translator / ER), le smoke instancie quand même l’`ApiService` pour vérifier la déclaration d’attributs. **cache** : API `/me` toujours enregistrée (pas de switch).

| Ordre | Plugin | PR | Base | État | Lien |
|------:|--------|-----|------|------|------|
| 34 | wwp-cache | [#14](https://github.com/agencewonderful/wwp-cache/pull/14) · revert [#16](https://github.com/agencewonderful/wwp-cache/pull/16) | `develop` | ✅ MERGED | [PR#14](https://github.com/agencewonderful/wwp-cache/pull/14) · [PR#16](https://github.com/agencewonderful/wwp-cache/pull/16) (retire `enableApi` ajouté par erreur) |
| 35 | wwp-alerte | [#8](https://github.com/agencewonderful/wwp-alerte/pull/8) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-alerte/pull/8) (`enableApi` défaut `false`) |
| 36 | wwp-rgpd | [#22](https://github.com/agencewonderful/wwp-rgpd/pull/22) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-rgpd/pull/22) (`enableApi` déjà présent, défaut `false`) |
| 37 | wwp-translator | [#14](https://github.com/agencewonderful/wwp-translator/pull/14) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-translator/pull/14) (`enableApi` déjà présent, défaut `false`) |
| 38 | wwp-contact | [#55](https://github.com/agencewonderful/wwp-contact/pull/55) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-contact/pull/55) (`enableApi` déjà présent, défaut `false`) |
| 39 | wwp-espace-restreint | [#42](https://github.com/agencewonderful/wwp-espace-restreint/pull/42) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-espace-restreint/pull/42) (`enableApi` déjà présent, défaut `false`) |

**Note smoke (2026-10-28) :** `tests/Integration/smoke-api-routes.php` mergé avec chaque PR H. ER couvre **toutes** les routes `#[WPApiEndpoint]` (url-registry, auth, account CRUD, account-validation, password forgot/reset).

---

## K — Fix PHPUnit suites (hôte Bedrock) — indépendant de H

Suite locale `CI/run-phpunit-tests.sh` + runners framework : après corrections, **25 plugins PASS** (0 FAIL) · framework Unit **30/0**.  
Ces PR peuvent merger **sans attendre** API#5.

| Ordre | Repo | PR | Base | Contenu | État | Lien |
|------:|------|-----|------|---------|------|------|
| 70 | wwp-cache | [#15](https://github.com/agencewonderful/wwp-cache/pull/15) | `develop` | `/me` session no-op si headers envoyés + stubs `WP_REST_*` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-cache/pull/15) |
| 71 | wwp-contact | [#56](https://github.com/agencewonderful/wwp-contact/pull/56) | `develop` | test IP IPv6 : tables via `$table_prefix` / `DB_PREFIX` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-contact/pull/56) |
| 72 | wwp-security | [#8](https://github.com/agencewonderful/wwp-security/pull/8) | `develop` | SecuPress whitelist : skip si `$wpdb` absent (PHPUnit) | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-security/pull/8) |
| 73 | wwp-espace-restreint | [#43](https://github.com/agencewonderful/wwp-espace-restreint/pull/43) | `develop` | DI `UnlockedAccountEmailClass` (typo config) | ⛔ CLOSED | [PR](https://github.com/agencewonderful/wwp-espace-restreint/pull/43) — fix déjà présent sur `develop` |
| 74 | wonderwp-plugin (core) | [#60](https://github.com/agencewonderful/wonderwp-plugin/pull/60) | `develop` | `ModelForm` : `optional(length)` pour Respect Validation 2.5 | ✅ MERGED | [PR](https://github.com/agencewonderful/wonderwp-plugin/pull/60) |
| 75 | wonderwp-base | [#44](https://github.com/agencewonderful/wonderwp-base/pull/44) | `develop` | propager exit code des suites PHPUnit dans `CI/run-phpunit-tests.sh` | ✅ MERGED | [PR](https://github.com/agencewonderful/wonderwp-base/pull/44) |

---

## M — Unblockers composer + PHP 8 signatures (plugin-core)

| Ordre | Repo | PR | Base | Contenu | État | Lien |
|------:|------|-----|------|---------|------|------|
| 80 | Taxonomy | [#4](https://github.com/wonderwp/Taxonomy/pull/4) | `feature/php8` | `wonderwp/service` : `dev-chore/deps-max` → `dev-feature/php_8` | ✅ MERGED | [PR](https://github.com/wonderwp/Taxonomy/pull/4) |
| 81 | wonderwp-plugin | [#61](https://github.com/agencewonderful/wonderwp-plugin/pull/61) | `develop` | `symfony/cache` `^6.4.12 \|\| ^7.1.5` (compat HttpFoundation ^7.4) | ✅ MERGED | [PR](https://github.com/agencewonderful/wonderwp-plugin/pull/61) |
| 82 | wonderwp-plugin | [#62](https://github.com/agencewonderful/wonderwp-plugin/pull/62) | `develop` | nullable PHP 8.4+ **+ `doctrine/annotations` ^2.0** — **ex-stash** | ✅ MERGED | [PR](https://github.com/agencewonderful/wonderwp-plugin/pull/62) |

### Incident 2026-10-28 — front fatal AnnotationReader — ✅ résolu

| | |
|--|--|
| **Cause** | `wonderwp/api` (vague E) attributs PHP → plus de require `doctrine/annotations` → package sauté du lock. |
| **Effet** | Fatal `AnnotationReader not found` (gutenberg-utils / boot). |
| **Décision** | Framework : pas d’annotations. **plugin-core** les déclare (`^2.0`). |
| **Fix** | ✅ plugin#62 mergée · hôte `composer update` plugin-core · require racine annotations retiré (transitif) |
| **État** | Front HTTP **200** · `AnnotationReader` OK |

### État local hôte (pas de stash)

| Élément | Où | Action |
|---------|-----|--------|
| `composer.lock` (+ twig ^3, plugin-core tip, …) | branche `chore/php-8.5-compat` | À committer / PR wonderwp-base |
| Stash | — | **Vide** |
| Checkout `wonderwp-plugin-core` | `develop` @ `#62` | ✅ aligné |
| Checkouts `vendor/wonderwp/*` | tip feature | Alignés via composer |

---

## N — Nullables PHP 8.4+ (ex-WIP local dirty / stash) — ✅ MERGED

Promu depuis dirty/stash locaux → PRs `fix/php84-explicit-nullables` ; stash cache droppé (déjà sur develop via #15). Mergées 2026-10-28. Checkouts locaux remis sur `develop`.

| Repo | PR | Base | État | Lien |
|------|-----|------|------|------|
| wwp-actu | [#18](https://github.com/agencewonderful/wwp-actu/pull/18) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-actu/pull/18) |
| wwp-emploi | [#20](https://github.com/agencewonderful/wwp-emploi/pull/20) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-emploi/pull/20) |
| wwp-event | [#32](https://github.com/agencewonderful/wwp-event/pull/32) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-event/pull/32) |
| wwp-faq | [#7](https://github.com/agencewonderful/wwp-faq/pull/7) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-faq/pull/7) |
| wwp-gutenberg-utils | [#15](https://github.com/agencewonderful/wwp-gutenberg-utils/pull/15) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-gutenberg-utils/pull/15) |
| wwp-map | [#29](https://github.com/agencewonderful/wwp-map/pull/29) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-map/pull/29) |
| wwp-newsletter | [#33](https://github.com/agencewonderful/wwp-newsletter/pull/33) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-newsletter/pull/33) |
| wwp-newsroom | [#9](https://github.com/agencewonderful/wwp-newsroom/pull/9) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-newsroom/pull/9) |
| wwp-search | [#9](https://github.com/agencewonderful/wwp-search/pull/9) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-search/pull/9) |
| wwp-slider | [#10](https://github.com/agencewonderful/wwp-slider/pull/10) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-slider/pull/10) |
| wonderwp-theme | [#41](https://github.com/agencewonderful/wonderwp-theme/pull/41) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wonderwp-theme/pull/41) |
| wwp-security | [#10](https://github.com/agencewonderful/wwp-security/pull/10) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-security/pull/10) |
| wwp-alerte | [#9](https://github.com/agencewonderful/wwp-alerte/pull/9) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-alerte/pull/9) |
| wwp-rgpd | [#23](https://github.com/agencewonderful/wwp-rgpd/pull/23) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-rgpd/pull/23) |
| wwp-translator | [#15](https://github.com/agencewonderful/wwp-translator/pull/15) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-translator/pull/15) |
| wwp-espace-restreint | [#46](https://github.com/agencewonderful/wwp-espace-restreint/pull/46) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-espace-restreint/pull/46) |
| wwp-contact | [#57](https://github.com/agencewonderful/wwp-contact/pull/57) | `develop` | ✅ MERGED | [PR](https://github.com/agencewonderful/wwp-contact/pull/57) |

## O — Hôte wonderwp-base (`chore/php-8.5-compat`) — 🔲 OPEN

Contenu typique : toolchain PHP 8.5 / Node 22 · patch WP Migrate DB Pro · `twig/twig` ^3 · `composer.lock` post deps-max/N · `SessionHelper` · runners Integration · stubs theme BP/MAP · `ai_resources/` suivi · agents/skills Cursor.

| Repo | Branche | Base | État |
|------|---------|------|------|
| wonderwp-base | [#45](https://github.com/agencewonderful/wonderwp-base/pull/45) `chore/php-8.5-compat` | `develop` | 🔲 OPEN | [PR](https://github.com/agencewonderful/wonderwp-base/pull/45) |

---

## I — Hors périmètre (ne pas fusionner dans ce chantier)

| Repo | PR | Titre | État | Pourquoi hors scope |
|------|-----|-------|------|---------------------|
| wonderwp/Generator | [#7](https://github.com/wonderwp/Generator/pull/7) | Feature/block theme gen improvements | 🔲 OPEN | Feature unrelated |
| wonderwp/Sanitizer | [#1](https://github.com/wonderwp/Sanitizer/pull/1) | Update URL regex… | 🔲 OPEN | Feature unrelated (garder distinct de Sanitizer#2/#3) |

---

## Stratégie de tests

**Source de vérité :** [`wonderwp/wonderwp` → `docs/testing.md`](https://github.com/wonderwp/wonderwp/blob/feature/docs/testing.md) (mergé via wonderwp#5).

| Suite | Où | CI package | Exécution |
|-------|-----|------------|-----------|
| **Unit** | `tests/Unit/` | Oui (`composer test`) | Stubs / spies — **pas** de WP/BDD |
| **Integration** | `tests/Integration/` | Non | Hôte WP : `wp eval-file vendor/wonderwp/<pkg>/tests/Integration/…` |

**Runners méta (hôte Bedrock / DDEV) :**

```bash
ddev exec bash vendor/wonderwp/wonderwp/bin/run-all-unit.sh
ddev exec bash vendor/wonderwp/wonderwp/bin/run-all-integration.sh
```

Gate merge PR package = Unit verts. Integration = confiance runtime.  
Bootstrap PHPUnit : préférer autoload hôte (`dirname(__DIR__, 3)/autoload.php`) pour éviter le clash PHPUnit 9 (hôte) / 12 (package).

**Vérifié localement (2026-10-28) :**  
- Framework Unit **30/0** · Integration **6/0** (scan non récursif des smokes package).  
- Plugins hôte `CI/run-phpunit-tests.sh` : **25/25** sans FAIL (cache 1 skipped session ; gutenberg-utils 1 risky).

---

## Checklist post-merge (wonderwp-base)

1. ~~Fermer Asset#5~~ ✅.
2. ~~Merger vague C (deps) + Repository#5~~ ✅.
3. ~~Merger vague D~~ ✅.
4. ~~Merger vague F (Search#8/#9, PluginSkeleton#7/#8)~~ ✅.
5. ~~Merger plugin#62~~ ✅ (nullables + `doctrine/annotations` ^2.0).
6. ~~Merger vague K~~ ✅ (5/5 utiles ; ER#43 CLOSED, fix déjà sur develop).
7. ~~Merger vague J (tests J1–J3)~~ ✅.
8. ~~Merger vague E~~ ✅.
9. ~~wonderwp#5~~ ✅ · ~~vague L~~ ✅ · ~~Taxonomy#4 / plugin#61~~ ✅.
10. ~~Merger vague H (plugins)~~ ✅ (cache#14 · alerte#8 · rgpd#22 · translator#14 · contact#55 · ER#42).
11. ~~`composer update wonderwp/wonderwp`~~ ✅ — **rester :** committer `composer.lock` sur wonderwp-base + `composer update` des 6 plugins H.
12. Crawl local des URLs publiques.
13. Issue HttpFoundation#3 : tagger une release (`1.2.0`+) ; HttpFoundation#5 tests déjà mergés via vague.

---

## Synthèse chiffres

| | Nb |
|--|---:|
| Framework C–G + J + L ✅ | Terminé |
| Unblockers M ✅ | Taxonomy#4 · plugin#61 · plugin#62 |
| Plugins WPApi H | **6/6** ✅ |
| Fix PHPUnit K | **5** ✅ · **1** ⛔ CLOSED (ER#43) |
| Vague N nullables PHP 8.4 | **17/17** ✅ |
| Hôte wonderwp-base (O) | `chore/php-8.5-compat` 🔲 OPEN |
| Plugins locaux dirty/stash | **0** |
| **Restant à merger (utile)** | **1** (O hôte) |

---

## Liens utiles

- Plan : [`ai_resources/plans/2610252115-maximize-framework-deps.md`](plans/2610252115-maximize-framework-deps.md)
- Rapport d’implémentation : [`ai_resources/wonderwp/implementation/2610252115-maximize-framework-deps.md`](wonderwp/implementation/2610252115-maximize-framework-deps.md)
- Docs tests : https://github.com/wonderwp/wonderwp/blob/feature/docs/testing.md
- Issue Symfony HttpFoundation : https://github.com/wonderwp/HttpFoundation/issues/3
- Ex-stash → PR : https://github.com/agencewonderful/wonderwp-plugin/pull/62
