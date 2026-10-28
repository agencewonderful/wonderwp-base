# PR à fusionner — montée de dépendances framework (PHP ^8.3) + vague tests

**Généré le :** 2026-10-28  
**Mis à jour le :** 2026-10-28 (PHPUnit plugins verts + PRs K ; vague tests J/E encore ouvertes ; deps C+D+F mergées)  
**Chantiers :** Dependabot + [Monter au maximum les versions…](https://app.notion.com/p/403d89b334fb8138856dfa67460682a8) + fiabilité Unit/Integration (P0–P2)  
**Branche feature typique :** `chore/deps-max` → bases `feature/php_8` / variantes  
**Règle :** Jeremy merge (pas d’auto-merge). Après merges framework → `composer update` + crawl wonderwp-base.

---

## Légende

| Colonne | Signification |
|--------|----------------|
| **Ordre** | Ordre de fusion recommandé (croissant) |
| **Vague** | Groupe logique (fondations → API → méta → plugins) |
| **Base** | Branche cible de la PR |
| **État** | Suivi : `✅ MERGED` · `🔲 OPEN` · `⚠️ CONFLICT` · `⛔ CLOSED` |

**Restant à merger :** **30** framework (5 E deps+tests · 24 test-only · 1 méta conflit) + **6** plugins WPApi (H) + **6** fix PHPUnit (K).  
**Déjà mergées (deps) :** **27** framework (C 18 + D 7 + F 2) + Documentation#2 + Repository#5 (1ʳᵉ vague tests).

---

## Vue d’ensemble par catégorie

| # | Catégorie | Nb | Mergées | Restantes | Action |
|---|-----------|---:|--------:|----------:|--------|
| A | À **fermer sans merger** | 1 | — | 0 | ✅ Asset#5 déjà fermée |
| B | **Sécurité Dependabot** | 1 | 1 | 0 | Documentation#2 mergée |
| C | Framework — **vague 1** fondations (deps) | 18 (+ Repo#5 tests) | **19** | **0** | ✅ Terminée |
| D | Framework — **vague 2** intermédiaires (deps) | 7 | **7** | **0** | ✅ Terminée |
| E | Framework — **vague 3** sensibles (deps + tests) | 5 | 0 | 5 | **Prochaine** — Unit verts avant merge |
| F | Framework — **vague 4** agrégateurs | 2 deps + 2 tests | **2** deps | **2** tests | Search#9 · PluginSkeleton#8 |
| G | Framework — **vague 5** méta-package | 1 | 0 | 1 | Rebase puis merger |
| J | Framework — **vague tests** (post-deps C/D) | 22 | 0 | 22 | Merger en parallèle (bases déjà à jour) |
| H | Plugins — **attributs WPApi** | 6 | 0 | 6 | Après API#5 |
| K | Plugins / core / base — **fix PHPUnit suite** | 6 | 0 | 6 | Indépendant H — merger dès CI verte |
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

## E — Vague 3 — Ruptures / packages sensibles (deps + tests) (ordre 26–30)

Ces PR portent encore les **deps** *et* les commits Unit/Integration / CI. Gate = Unit verts.

| Ordre | Package | PR | Base | Contenu principal | État | Lien |
|------:|---------|-----|------|-------------------|------|------|
| 26 | **api** | [#5](https://github.com/wonderwp/API/pull/5) | `feature/php_8` | php ^8.3, **attributs PHP** ; Unit endpoints + Integration smoke | 🔲 OPEN | [PR](https://github.com/wonderwp/API/pull/5) |
| 27 | asset | [#6](https://github.com/wonderwp/Asset/pull/6) | `feature/php_8` | php ^8.3, **symfony/asset ^7.4**, phpunit ^12, tests adaptés | 🔲 OPEN | [PR](https://github.com/wonderwp/Asset/pull/6) |
| 28 | hook | [#2](https://github.com/wonderwp/Hook/pull/2) | `feature/php_8` | php ^8.3 + Unit deepen + CI | 🔲 OPEN | [PR](https://github.com/wonderwp/Hook/pull/2) |
| 29 | media | [#3](https://github.com/wonderwp/Media/pull/3) | `feature/php_8` | php ^8.3 + Unit deepen + CI | 🔲 OPEN | [PR](https://github.com/wonderwp/Media/pull/3) |
| 30 | routing | [#2](https://github.com/wonderwp/Routing/pull/2) | `feature/php_8` | php ^8.3 + Unit deepen + CI | 🔲 OPEN | [PR](https://github.com/wonderwp/Routing/pull/2) |

---

## F — Vague 4 — Agrégateurs

Deps mergées ; PRs **tests** restantes.

| Ordre | Package | PR | Base | Contenu principal | État | Lien |
|------:|---------|-----|------|-------------------|------|------|
| 31 | search | [#8](https://github.com/wonderwp/Search/pull/8) | `feature/php_8` | php ^8.3, wp-dev ^7 | ✅ MERGED | [PR](https://github.com/wonderwp/Search/pull/8) |
| 31b | search | [#9](https://github.com/wonderwp/Search/pull/9) | `feature/php_8` | Unit SQL/limit-offset + CI stubs | 🔲 OPEN | [PR](https://github.com/wonderwp/Search/pull/9) |
| 32 | plugin-skeleton | [#7](https://github.com/wonderwp/PluginSkeleton/pull/7) | `feature/php_8` | php ^8.3, wp-dev ^7 | ✅ MERGED | [PR](https://github.com/wonderwp/PluginSkeleton/pull/7) |
| 32b | plugin-skeleton | [#8](https://github.com/wonderwp/PluginSkeleton/pull/8) | `feature/php_8` | AbstractManager Unit + CI | 🔲 OPEN | [PR](https://github.com/wonderwp/PluginSkeleton/pull/8) |

---

## G — Vague 5 — Méta-package (ordre 33)

| Ordre | Package | PR | Base | Contenu principal | État | Lien | Attention |
|------:|---------|-----|------|-------------------|------|------|-----------|
| 33 | wonderwp | [#5](https://github.com/wonderwp/wonderwp/pull/5) | `feature` | php ^8.3 + `docs/testing.md` + `bin/run-all-{unit,integration}.sh` + meta Unit | ⚠️ CONFLICT | [PR](https://github.com/wonderwp/wonderwp/pull/5) | Rebase sur `feature` avant merge |

---

## J — Vague tests — post-deps C/D (parallèle OK)

Deps déjà sur la base : merger dès que CI Unit verte. Inclut bootstrap host-autoload (évite clash PHPUnit 9 hôte / 12 package).

### J1 — P0 contrats critiques

| Ordre | Package | PR | Base | Contenu | État | Lien |
|------:|---------|-----|------|---------|------|------|
| 40 | repository | [#6](https://github.com/wonderwp/Repository/pull/6) | `feature/php_8` | Post/User Unit + smoke-posts + host-autoload | 🔲 OPEN | [PR](https://github.com/wonderwp/Repository/pull/6) |
| 41 | plugin-skeleton | → F #8 | — | voir F | 🔲 OPEN | — |
| 42 | customposttype | [#4](https://github.com/wonderwp/CPT/pull/4) | `feature/php_8` | Unit/Integration rewrites + registration + CI stubs | 🔲 OPEN | [PR](https://github.com/wonderwp/CPT/pull/4) |
| 43 | taxonomy | [#2](https://github.com/wonderwp/Taxonomy/pull/2) | `feature/php8` | Unit/Integration registerTaxonomy + CI stubs | 🔲 OPEN | [PR](https://github.com/wonderwp/Taxonomy/pull/2) |
| 44 | sanitizer | [#3](https://github.com/wonderwp/Sanitizer/pull/3) | `feature/php_8` | Unit normalize + sanitizeData edges + CI | 🔲 OPEN | [PR](https://github.com/wonderwp/Sanitizer/pull/3) |
| 45 | custom-fields | [#2](https://github.com/wonderwp/CustomFields/pull/2) | `feature/php8` | **fix** registry `getKey` default + Unit + CI | 🔲 OPEN | [PR](https://github.com/wonderwp/CustomFields/pull/2) |

### J2 — P1 deepen / CI

| Ordre | Package | PR | Base | Contenu | État | Lien |
|------:|---------|-----|------|---------|------|------|
| 46 | service | [#4](https://github.com/wonderwp/Service/pull/4) | `feature/php_8` | GHA CI, cache pin, host-autoload | 🔲 OPEN | [PR](https://github.com/wonderwp/Service/pull/4) |
| 47 | http-foundation | [#5](https://github.com/wonderwp/HttpFoundation/pull/5) | `feature/increase_http_foundation_lib_version` | Request singleton Unit + CI | 🔲 OPEN | [PR](https://github.com/wonderwp/HttpFoundation/pull/5) |
| 48 | search | → F #9 | — | voir F | 🔲 OPEN | — |
| 49 | cache | [#5](https://github.com/wonderwp/Cache/pull/5) | `feature/php_8` | TransientCache empty-value Unit + CI | 🔲 OPEN | [PR](https://github.com/wonderwp/Cache/pull/5) |

*(hook / media / routing / api deepen = déjà dans les PR E)*

### J3 — P2 breadth (scaffolds Unit + CI)

| Ordre | Package | PR | Base | État | Lien |
|------:|---------|-----|------|------|------|
| 50 | form | [#11](https://github.com/wonderwp/Form/pull/11) | `feature/increase_validation_lib_version` | 🔲 OPEN | [PR](https://github.com/wonderwp/Form/pull/11) |
| 51 | functions | [#3](https://github.com/wonderwp/Functions/pull/3) | `feature/php_8` | 🔲 OPEN | [PR](https://github.com/wonderwp/Functions/pull/3) |
| 52 | dependency-injection | [#3](https://github.com/wonderwp/DependencyInjection/pull/3) | `feature/php_8` | 🔲 OPEN | [PR](https://github.com/wonderwp/DependencyInjection/pull/3) |
| 53 | filter | [#3](https://github.com/wonderwp/Filter/pull/3) | `feature/php_8` | 🔲 OPEN | [PR](https://github.com/wonderwp/Filter/pull/3) |
| 54 | http | [#3](https://github.com/wonderwp/Http/pull/3) | `feature/php_8` | 🔲 OPEN | [PR](https://github.com/wonderwp/Http/pull/3) |
| 55 | logging | [#3](https://github.com/wonderwp/Logging/pull/3) | `feature/php_8` | 🔲 OPEN | [PR](https://github.com/wonderwp/Logging/pull/3) |
| 56 | mailing | [#4](https://github.com/wonderwp/Mailing/pull/4) | `feature/php_8` | 🔲 OPEN | [PR](https://github.com/wonderwp/Mailing/pull/4) |
| 57 | notification | [#4](https://github.com/wonderwp/Notification/pull/4) | `feature/php_8` | 🔲 OPEN | [PR](https://github.com/wonderwp/Notification/pull/4) |
| 58 | panel | [#3](https://github.com/wonderwp/Panel/pull/3) | `feature/php_8` | 🔲 OPEN | [PR](https://github.com/wonderwp/Panel/pull/3) |
| 59 | response | [#2](https://github.com/wonderwp/Response/pull/2) | `feature/php8` | 🔲 OPEN | [PR](https://github.com/wonderwp/Response/pull/2) |
| 60 | shortcode | [#3](https://github.com/wonderwp/ShortCode/pull/3) | `feature/php_8` | 🔲 OPEN | [PR](https://github.com/wonderwp/ShortCode/pull/3) |
| 61 | task | [#3](https://github.com/wonderwp/Task/pull/3) | `feature/php_8` | 🔲 OPEN | [PR](https://github.com/wonderwp/Task/pull/3) |
| 62 | template | [#4](https://github.com/wonderwp/Template/pull/4) | `feature/php_8` | 🔲 OPEN | [PR](https://github.com/wonderwp/Template/pull/4) |
| 63 | block-bindings-source | [#2](https://github.com/wonderwp/block-bindings-source/pull/2) | `feature/definition` | 🔲 OPEN | [PR](https://github.com/wonderwp/block-bindings-source/pull/2) |

Skipped (pas de scaffold tests dans cette vague) : `block-style`, `block-type`, `block-variations`.

---

## H — Plugins consommateurs — après API#5 (ordre 34–39)

Migrent `@WPApi*` → `#[WPApiNamespace]` / `#[WPApiEndpoint]`. **Ne merger qu’après** [wonderwp/API#5](https://github.com/wonderwp/API/pull/5).

| Ordre | Plugin | PR | Base | État | Lien |
|------:|--------|-----|------|------|------|
| 34 | wwp-cache | [#14](https://github.com/agencewonderful/wwp-cache/pull/14) | `develop` | 🔲 OPEN | [PR](https://github.com/agencewonderful/wwp-cache/pull/14) |
| 35 | wwp-alerte | [#8](https://github.com/agencewonderful/wwp-alerte/pull/8) | `develop` | 🔲 OPEN | [PR](https://github.com/agencewonderful/wwp-alerte/pull/8) |
| 36 | wwp-rgpd | [#22](https://github.com/agencewonderful/wwp-rgpd/pull/22) | `develop` | 🔲 OPEN | [PR](https://github.com/agencewonderful/wwp-rgpd/pull/22) |
| 37 | wwp-translator | [#14](https://github.com/agencewonderful/wwp-translator/pull/14) | `develop` | 🔲 OPEN | [PR](https://github.com/agencewonderful/wwp-translator/pull/14) |
| 38 | wwp-contact | [#55](https://github.com/agencewonderful/wwp-contact/pull/55) | `develop` | 🔲 OPEN | [PR](https://github.com/agencewonderful/wwp-contact/pull/55) |
| 39 | wwp-espace-restreint | [#42](https://github.com/agencewonderful/wwp-espace-restreint/pull/42) | `develop` | 🔲 OPEN | [PR](https://github.com/agencewonderful/wwp-espace-restreint/pull/42) |

---

## K — Fix PHPUnit suites (hôte Bedrock) — indépendant de H

Suite locale `CI/run-phpunit-tests.sh` + runners framework : après corrections, **25 plugins PASS** (0 FAIL) · framework Unit **30/0**.  
Ces PR peuvent merger **sans attendre** API#5.

| Ordre | Repo | PR | Base | Contenu | État | Lien |
|------:|------|-----|------|---------|------|------|
| 70 | wwp-cache | [#15](https://github.com/agencewonderful/wwp-cache/pull/15) | `develop` | `/me` session no-op si headers envoyés + stubs `WP_REST_*` | 🔲 OPEN | [PR](https://github.com/agencewonderful/wwp-cache/pull/15) |
| 71 | wwp-contact | [#56](https://github.com/agencewonderful/wwp-contact/pull/56) | `develop` | test IP IPv6 : tables via `$table_prefix` / `DB_PREFIX` | 🔲 OPEN | [PR](https://github.com/agencewonderful/wwp-contact/pull/56) |
| 72 | wwp-security | [#8](https://github.com/agencewonderful/wwp-security/pull/8) | `develop` | SecuPress whitelist : skip si `$wpdb` absent (PHPUnit) | 🔲 OPEN | [PR](https://github.com/agencewonderful/wwp-security/pull/8) |
| 73 | wwp-espace-restreint | [#43](https://github.com/agencewonderful/wwp-espace-restreint/pull/43) | `develop` | DI `UnlockedAccountEmailClass` (typo config) | 🔲 OPEN | [PR](https://github.com/agencewonderful/wwp-espace-restreint/pull/43) |
| 74 | wonderwp-plugin (core) | [#60](https://github.com/agencewonderful/wonderwp-plugin/pull/60) | `develop` | `ModelForm` : `optional(length)` pour Respect Validation 2.5 | 🔲 OPEN | [PR](https://github.com/agencewonderful/wonderwp-plugin/pull/60) |
| 75 | wonderwp-base | *(ci-dessous)* | `develop` | propager exit code des suites PHPUnit dans `CI/run-phpunit-tests.sh` | 🔲 OPEN | voir PR base |

---

## I — Hors périmètre (ne pas fusionner dans ce chantier)

| Repo | PR | Titre | État | Pourquoi hors scope |
|------|-----|-------|------|---------------------|
| wonderwp/Generator | [#7](https://github.com/wonderwp/Generator/pull/7) | Feature/block theme gen improvements | 🔲 OPEN | Feature unrelated |
| wonderwp/Sanitizer | [#1](https://github.com/wonderwp/Sanitizer/pull/1) | Update URL regex… | 🔲 OPEN | Feature unrelated (garder distinct de Sanitizer#2/#3) |

---

## Stratégie de tests

**Source de vérité :** [`wonderwp/wonderwp` → `docs/testing.md`](https://github.com/wonderwp/wonderwp/blob/chore/deps-max/docs/testing.md) (sur `chore/deps-max` / PR #5).

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
4. ~~Merger vague F deps (Search#8, PluginSkeleton#7)~~ ✅.
5. Committer/pousser les alignments de signatures locaux (plugins / mu-plugins) si pas déjà fait — sinon fatal PHP avec le typage Repository.
6. **Merger vague K** (fix PHPUnit plugins/core/base) — suites locales déjà vertes.
7. **Merger vague J** (tests post-deps, parallèle) — CI Unit verte.
8. **Merger vague E** (API#5 → asset → hook → media → routing).
9. Merger Search#9 + PluginSkeleton#8.
10. Rebase + merger wonderwp#5 (docs + runners).
11. Merger vague H (plugins) après API#5 ; smoke `vendor/wonderwp/api/tests/Integration/smoke-endpoints.php`.
12. Sur wonderwp-base : `composer update` pointant vers les branches/tags à jour.
13. Crawl local des URLs publiques.
14. Issue HttpFoundation#3 : après merge HttpFoundation#4 ✅, **tagger une release** (`1.2.0`+) ; HttpFoundation#5 = tests à merger ensuite.

---

## Synthèse chiffres

| | Nb |
|--|---:|
| Framework deps ✅ MERGED | **27** (C 18 + Repo#5 + D 7 + F 2) + Documentation |
| Framework 🔲 OPEN tests-only (J + F-tests) | **24** |
| Framework 🔲 OPEN deps+tests (E) | **5** |
| Framework ⚠️ CONFLICT (G) | **1** |
| Plugins WPApi 🔲 OPEN (H) | **6** |
| Fix PHPUnit 🔲 OPEN (K) | **6** |
| ⛔ CLOSED (Asset#5, wonderwp#4) | **2** |
| **Restant à merger (utile)** | **42** (30 framework + 6 H + 6 K) |

---

## Liens utiles

- Plan : [`ai_resources/plans/2610252115-maximize-framework-deps.md`](plans/2610252115-maximize-framework-deps.md)
- Rapport d’implémentation : [`ai_resources/wonderwp/implementation/2610252115-maximize-framework-deps.md`](wonderwp/implementation/2610252115-maximize-framework-deps.md)
- Docs tests (branche) : https://github.com/wonderwp/wonderwp/blob/chore/deps-max/docs/testing.md
- Issue Symfony HttpFoundation : https://github.com/wonderwp/HttpFoundation/issues/3
