# Subprocedure: Functional domains

## Goal

Establish whether the project is organized around **functional domains** (bounded areas of product behavior or ownership) and capture them so the backlog can group epics or tickets accordingly.

Classify each domain into **one of three subtypes** so the backlog reflects the right kind of work (CPT-driven plugin, custom technological scope, or reuse of a WonderWp Base plugin).

## Analysis order: WonderWp Base **first**

**Always treat WonderWp Base coverage before custom domains.** That keeps reusable, catalog-mapped work visible early and makes it easier to target **CPT-driven** and **custom technological** domains afterward (what truly needs bespoke plugins or hooks).

| Phase | What to do |
| ----- | ----------- |
| **1 — WonderWp Base** | Map project needs to the [WonderWp Base catalog](#wonderwp-base-catalog-domain-need--plugin). Add epics and install (Composer) issues per matching plugins. |
| **2 — Custom domains** | Then capture **CPT-driven** business domains and **custom technological** domains that are *not* satisfied by a WonderWp Base plugin alone. |

**Transcript-informed sessions:** When reading the transcript or handoff, **scan for WonderWp-aligned signals first** (search, map, events, newsletter, RGPD, etc.), then mine other domain language.

**Live workshop:** **Ask WonderWp Base coverage first** (which catalog needs apply), then ask what **additional** custom functional domains exist.

## Three functional-domain subtypes

| Subtype | Short description |
| ------- | ----------------- |
| **CPT-driven business domain** | A plugin built around a **custom post type** created for this project. |
| **Custom technological domain** | Project-specific plugin or technical scope (hooks, services, integrations). |
| **WonderWp Base–covered domain** | A need already met by an **existing WonderWp plugin** (catalog below)—usually add an **install** task (Composer) then functional follow-up. |

**Artifact language:** Epic headings stay **full uppercase**; issue titles and the saved backlog draft remain **in French** per [artifact-language-fr.md](../../../references/artifact-language-fr.md). This reference file is in English for authors; apply French when writing the user-facing artifact.

## When to use

This subprocedure is used for **two** workshop questions in order (see [Questions to ask](../SKILL.md#questions-to-ask)):

1. **WonderWp Base** — which catalog needs apply?
2. **Custom functional domains** — what CPT-driven or custom technological domains remain beyond WonderWp Base?

## Instructions

### Phase 1 — WonderWp Base (do this first)

1. **Transcript / handoff (mode B):** Before other domain mining, scan for mentions or implied needs that match rows in the [WonderWp Base catalog](#wonderwp-base-catalog-domain-need--plugin) (e.g. multilingual, FAQ, contact form, map, search, RGPD).
2. **Live workshop (mode A):** Ask explicitly which **WonderWp Base** needs apply—walk the catalog categories if helpful, without forcing extras.
3. For each **match**, add backlog items per **Subtype: WonderWp Base** (below): full-uppercase epic, install issue via Composer, optional config/integration issues.
4. If **none** apply, record that and continue to Phase 2.

### Phase 2 — Custom functional domains (after WonderWp Base)

5. Ask whether the project has **additional** functional domains **not** covered by the WonderWp installs already captured—**CPT-driven** and/or **custom technological** work.
6. If **no** further domains, record and move on (blocks / templates next).
7. If **yes**, collect a **bullet list** of domain names. If the user is vague, ask: “Which areas of the product are distinct enough to plan or ship separately?”
8. For each **custom** domain, capture:
   - a **stable slug** (kebab-case);
   - a **one-line description** if volunteered; otherwise `NEEDS CLARIFICATION`;
   - subtype: **CPT-driven** or **custom technological** (not WonderWp Base).
9. Map to backlog: prefer **one epic per domain** when few and stable, or labels in ticket bodies if many/overlapping.

### Subtype reference: CPT-driven (custom post type)

Plugins built around a **custom post type** for this project. Typical issues under the epic (**issue titles in French** in the artifact):

- Plugin generation
- Custom post type creation
- Custom fields creation (if any : ask the user if there are custom fields to create)
- Taxonomy creation (if any : ask the user if there are taxonomies to create)
- Archive template creation (if any : ask the user if there are archive templates to create)
- Single template creation (if any : ask the user if there are single templates to create)
- Query loop block creation (if any : ask the user if there are query loop blocks to create)
- Domain-specific features (refine with the user)
- Archive template filters creation (if any : ask the user if there are archive template filters to create. If yes create them as subtasks of the archive template creation issue)

### Subtype reference: Custom technological domain

Project-specific plugins or modules (hooks, services, API integration, technical business rules). **Ask** if the user already has issue titles or deliverables; otherwise `NEEDS CLARIFICATION` on scope.

### Subtype reference: WonderWp Base (existing plugins)

If a need **matches** the catalog:

- Add a **full-uppercase** epic (e.g. `### Epic: SEARCH` in this reference; French label in the saved backlog per [artifact-language-fr.md](../../../references/artifact-language-fr.md)).
- Add at least one issue to **install** the corresponding WonderWp plugin (e.g. *wwp-search*) via **Composer**; follow each repo’s README.
- Add configuration or integration issues if the user specifies.

10. Reflect the combined list back to the user for confirmation before locking into the backlog draft.

## WonderWp Base catalog (domain need → plugin)

| Need / domain | Repository | Role |
| ------------- | ---------- | ---- |
| Consent / trackers | https://github.com/agencewonderful/wwp-tracker | Consent manager for accepting or refusing trackers |
| Games / gamification | https://github.com/agencewonderful/wwp-jeux | Gaming-style features on the site |
| Multilingual | https://github.com/agencewonderful/wwp-translator | Multilingual site |
| Gutenberg block library | https://github.com/agencewonderful/wwp-gutenberg-utils | WonderWp blocks (accordion, slider, video player, breadcrumb, single post back button, social share, time to read, etc.) |
| News | https://github.com/agencewonderful/wwp-actu | News with CPT and taxonomies |
| Contact | https://github.com/agencewonderful/wwp-contact | Contact forms / contact capability |
| Interactive map | https://github.com/agencewonderful/wwp-map | Interactive map |
| Events | https://github.com/agencewonderful/wwp-event | Event management |
| Personal data / compliance | https://github.com/agencewonderful/wwp-rgpd | Personal data handling |
| Search | https://github.com/agencewonderful/wwp-search | Site search |
| FAQ (CPT) | https://github.com/agencewonderful/wwp-faq | FAQ based on a custom post type |
| Newsletter | https://github.com/agencewonderful/wwp-newsletter | Newsletter subscription |
| Restricted area / accounts | https://github.com/agencewonderful/wwp-espace-restreint | Sign up, sign in, password reset, restricted content area |

**Example backlog snippet** (search need identified):

```markdown
### Epic: SEARCH
- Install WonderWp Search plugin (wwp-search)
```

When **writing the project backlog file**, use **French** issue titles and French epic wording where applicable, full-uppercase epics, per [artifact-language-fr.md](../../../references/artifact-language-fr.md) (e.g. `### Epic: RECHERCHE` and a French install line for the same work). Adjust the epic label to project vocabulary; the install issue may cite the exact Composer package from the repository README.

## Output shape (session notes or backlog intro)

```markdown
## WonderWp Base (phase 1)

- <matched need> — <plugin slug> — <hypothesis or confirmed>

## Custom functional domains (phase 2)

- `<slug>` — <subtype: cpt | techno> — <short description or NEEDS CLARIFICATION>
```

## Anti-patterns

- Starting with CPT or custom technological domains before **WonderWp Base** is at least considered—defeats the ordered analysis.
- Inventing domain names the user did not mention.
- Collapsing distinct domains into one epic without agreement.
- Assigning a WonderWp plugin when no real need was identified (transcript or workshop).
- Forgetting that WonderWp installs are usually via **Composer**—do not invent steps not described in the plugin README.
