# Subprocedure: Templates

## Goal

Capture **template and layout** needs so the backlog can schedule design and integration work. In this subprocedure, “templates” covers:

1. **CPT-based** views (archive and single for each CPT-driven domain).
2. **Content / page layouts**—not theme template files in the narrow sense, but **specific page layouts** applied to selected pages (marketing, landing, key routes).

Stay aligned with [functional-domains.md](./functional-domains.md) for CPT-driven domains.

## Two template types

| Type | Meaning | Backlog rule |
| ---- | ------- | -------------- |
| **CPT-based** | Views **induced by CPT-driven functional domains** (custom post type plugins). | **One epic per** archive or single: **one epic** for `<Domain> / Archive`, **one epic** for `<Domain> / Single` per domain—do **not** fold both into a single domain epic for the template pass. |
| **Content templates (page layouts)** | Layouts for **specific pages** (hero, sections, bespoke composition). Ask the user to name **concrete layouts** (which pages, what purpose). | **One epic per layout** the user identifies. **No** default “declare template in admin” task (unlike CPT-based epics). |

**Epic titles:** Full uppercase per [artifact-language-fr.md](../../../references/artifact-language-fr.md) (e.g. `### Epic: NEWS / ARCHIVE`, `### Epic: LANDING PARTENAIRES`). **Issue titles** under those epics in French where applicable.

**Artifact language:** Saved backlog text in **French**; this reference uses English examples—translate epic/issue wording for the artifact.

## When to use

Invoke this subprocedure when asking: **What templates and page layouts does this project need?** Run it **after** functional domains are known so **CPT-based epics** can be derived and **content layouts** collected from the user.

## Instructions

### CPT-based — one epic each for Archive and Single

1. Read **CPT-driven** domains from [functional-domains.md](./functional-domains.md) (phase 2 — custom domains, subtype CPT-driven).
2. For **each** CPT-driven domain (e.g. News, Events), create **two separate epics** (unless the user explicitly drops one):
   - `### Epic: <DOMAIN LABEL> / ARCHIVE`
   - `### Epic: <DOMAIN LABEL> / SINGLE`
3. **Naming pattern:** domain label, **` / `** (spaces around slash), **ARCHIVE** or **SINGLE** in the epic title (uppercase in the draft). French artifacts: e.g. `### Epic: ACTUALITÉS / ARCHIVE`, `### Epic: ACTUALITÉS / SINGLE`.
4. **Default task (every CPT-based template epic):** Under each `… / ARCHIVE` or `… / SINGLE` epic, add **at least one** standard issue whose **title includes the same template reference as the epic** (so the admin task is traceable):
   - English reference wording: e.g. `Declare template in admin — NEWS / ARCHIVE`, `Declare template in admin — NEWS / SINGLE`.
   - French artifact (preferred for saved backlog): e.g. `Déclarer le template dans l’admin — ACTUALITÉS / ARCHIVE` (repeat the `Domain / Archive` or `Domain / Single` label after the em dash).
   This covers registering or declaring the template in the WordPress admin for that CPT view.
5. Under each epic, **then** add further issue bullets for implementation work (integration, blocks, QA)—reuse or reconcile with the CPT checklist in [functional-domains.md](./functional-domains.md) (plugin generation, CPT creation, query loop, etc.) so work is not duplicated; split checklist items across **Archive** vs **Single** epics where it makes sense, or reference shared foundation in the domain’s plugin epic if you keep one.
6. **Example** (two CPT-driven domains: News, Events)—**four** epics at the template layer:

```markdown
### Epic: NEWS / ARCHIVE
- Declare template in admin — NEWS / ARCHIVE

### Epic: NEWS / SINGLE
- Declare template in admin — NEWS / SINGLE

### Epic: EVENTS / ARCHIVE
- Declare template in admin — EVENTS / ARCHIVE

### Epic: EVENTS / SINGLE
- Declare template in admin — EVENTS / SINGLE
```

7. Do **not** merge Archive and Single into one epic for the same domain unless the user explicitly asks.

### Content templates (page layouts) — one epic per layout

8. Explain that these are **page layouts** for **specific pages**, not generic “a template exists.”
9. **Ask the user** to list **named layouts** (e.g. “Partner landing”, “Campaign page with form + video”, “About — timeline section”). One row or bullet per distinct layout.
10. For **each** named layout, create **one epic** whose title reflects that layout (uppercase, short label), e.g. `### Epic: LANDING PARTENAIRES` or `### Epic: PAGE CAMPAGNE PRINTEMPS`.
11. Under each epic, add issues for design integration, blocks, content entry, QA—`NEEDS CLARIFICATION` until the user specifies sections or blocks (link [blocks.md](./blocks.md) when known). **Do not** add the CPT default task `Declare template in admin — …` here; content layouts are **not** CPT archive/single registrations and have **no** mandatory admin-declaration boilerplate.
12. Detect **duplication** between layouts that differ only in copy; merge only with user agreement.

### Cross-cutting

13. Summarize: CPT epics (count + names) + content-layout epics; align with [functional-domains.md](./functional-domains.md) and [blocks.md](./blocks.md); get user approval.

## Output shape (session notes)

```markdown
## Templates

### CPT-based (one epic per archive / single)

| Domain | Epic: Archive | Epic: Single |
| ------ | ------------- | -------------- |
| News | NEWS / ARCHIVE | NEWS / SINGLE |

Default issue under each epic: `<DOMAIN / ARCHIVE|SINGLE> / Declare template in admin` (French in artifact).

### Content page layouts (one epic per layout)

| Layout name | Epic title | Notes |
| ----------- | ---------- | ----- |
| Partner landing | LANDING PARTENAIRES | … |
```

For **content** layout epics: **do not** add a default admin-declaration task—only user-driven issues (integration, blocks, etc.).

## Anti-patterns

- CPT template epics **without** the default “declare template in admin” issue, or an admin task **without** the **`Domain / ARCHIVE`** or **`Domain / SINGLE`** reference in the title.
- Putting **Archive** and **Single** for the same CPT domain in **one** epic when the rule is **one epic per** archive and **one epic per** single.
- Treating **content layouts** as a single vague epic; need **explicit** layout names from the user.
- Adding the **CPT-only** default “declare template in admin” task to **content layout** epics.
- Listing every stock theme template; focus on **project-specific** CPT views and **named** page layouts.
- Forgetting blocks cross-reference—see [blocks.md](./blocks.md).
- Inconsistent **`Domain / ARCHIVE`** vs **`Domain / SINGLE`** naming; keep the slash pattern stable.
