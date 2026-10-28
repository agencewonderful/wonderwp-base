---
name: building-backlog-plan-from-draft
description: >-
  Takes a title-only backlog draft (epics and issue titles), then interactively
  enriches each issue with assignee, category, body, and optional visual reference,
  producing an enriched backlog plan for creating-epics-and-issues-from-backlog-plan.
metadata:
  domain: wonderwp-product-manager
---

# Building a backlog plan from a draft

## Goal

Turn a **backlog draft** ([references/backlog-draft.md](references/backlog-draft.md)) into a single **enriched backlog plan** ([creating-epics-and-issues-from-backlog-plan/references/enriched-backlog-plan.md](../creating-epics-and-issues-from-backlog-plan/references/enriched-backlog-plan.md)) by asking the user, for each issue line, for assignee, category, task body, and whether a visual reference is needed—leading each **category** question with a **recommended default or three ranked guesses** when confidence is low (**Category proposals**)—then hand off to **creating-epics-and-issues-from-backlog-plan**, unless **auto-create mode** is active (see below). When the repo defines **GitHub issue templates** under `.github/ISSUE_TEMPLATE/`, use them to shape ticket bodies when a template matches the ticket’s **category** (see **Issue templates** below).

The draft is normally produced by **co-constructing-backlog** (after an optional **planning-backlog-from-conversation-transcript** handoff), which saves it to `ai_resources/backlog-drafts/<yymmdd>-backlog-draft.md` at project root. See [backlog-draft.md](references/backlog-draft.md) for when this artifact exists.

This skill writes the **enriched** plan to **`ai_resources/backlog-plans/<yymmdd>-backlog-plan.md`** at project root (`yymmdd` = **YYMMDD**), so work is visible on disk from the first moment and after every update.

### Auto-create mode (optional)

When the user **activates auto-create mode** explicitly at the start (e.g. « sync au tracker à chaque ticket », « auto-create », « créer dans GitHub à chaque étape »), the agent **materializes the backlog in the remote tracker incrementally** instead of only at handoff:

- **Delegation:** Use **creating-an-epic** for parent epics and **creating-an-issue** for each ticket—the same contracts as **creating-epics-and-issues-from-backlog-plan** (categories from `./agents.md`, French titles/bodies per artefact rules, parent reference in child bodies when applicable).
- **When to create remotely:**
  - **Epic with zero tickets:** After the initial plan file is written (or on first visit to that epic block), create the epic once if `- tracker_url:` is not already present under that epic’s bullets—same epic body composition as **creating-epics-and-issues-from-backlog-plan** (`slug`, `summary`, optional `milestone` / `iteration`).
  - **Epic with tickets:** Before creating the **first** issue under that epic, ensure the epic exists (create per **creating-an-epic** if there is no `- tracker_url:` yet).
  - **After each issue enrichment turn:** Once the ticket’s required fields are no longer `NEEDS CLARIFICATION` (assignee, category, body per project rules), **create that issue** via **creating-an-issue**, linking to the parent epic URL/ID already recorded on the epic block. **Do not** create when the user **skipped** that ticket this session (**Skipping a ticket**).
  - **Unscoped tickets:** After the turn that resolves the ticket, create it without a parent epic line (same as bulk flow).
- **Do not create** a tracker item while **category** or other required fields remain `NEEDS CLARIFICATION`, or when **creating-epics-and-issues-from-backlog-plan** would pause (e.g. unknown category vs `agents.md`).
- **Persist tracker links in the markdown:** Immediately after a successful API create, add under the epic or ticket bullets:
  - `- tracker_url: <canonical URL>`
  - `- tracker_id: <id or number>` (when the tool returns one)
  If creation fails, **do not** write these lines; leave the enriched text as-is and report the error.
- **Idempotency / resume:** Before creating, if `- tracker_url:` (or equivalent) is already present for that epic or ticket, **skip** remote creation for that block.
- **End of session:** If every epic and ticket has been created remotely, tell the user the plan file is already synced and **creating-epics-and-issues-from-backlog-plan** is only needed for **remaining** blocks without tracker links—or for a fresh repo after manual edits.

### Issue templates (optional)

Repos may ship **[GitHub issue templates](https://docs.github.com/en/communities/using-templates-to-encourage-useful-issues-and-pull-requests/manually-creating-a-single-issue-template-for-your-repository)** under **project root** `.github/ISSUE_TEMPLATE/` — markdown templates (`.md` with YAML frontmatter), issue **forms** (`.yml`), and optionally `config.yml` for the template chooser. Use them **only when present**; if the folder is missing or empty, enrichment behaves as before (freeform body).

**Session start — discover and align with categories**

1. After reading `./agents.md`, check whether `.github/ISSUE_TEMPLATE/` exists; if not, skip the rest of this subsection for the session (no repeated warnings unless the user asks).
2. **Load templates:** Read each template file GitHub would expose (typically `*.md` and issue-form `*.yml`; you may skip `config.yml` except to learn template labels or ordering). For `.md` templates, parse YAML frontmatter (`name`, `about`, `title`, optional `labels`, assignees, etc.). For `.yml` issue forms, parse `name`, `description`, and the `body:` list of inputs (`type`, `id`, `attributes` such as `label`, `description`, `value`, `options`, `validations`).
3. **Match templates to ticket categories** from the **allowed categories** (or equivalent list) in `./agents.md`:
   - **Primary:** If a template’s **labels** (frontmatter or form) include or imply a category value used in `agents.md` (exact match, case-insensitive, or obvious normalization such as `category:Foo` vs `Foo`), record **category → template path** in working memory.
   - **Secondary:** If `./agents.md` (or a linked team doc the user points to) defines an explicit **mapping** table (e.g. category `Bug` → `bug-report.md`), **prefer that** over guessing.
   - **Ambiguity:** If several templates map to one category or none map cleanly, **tell the user once** which templates were loaded and how they align with categories; ask for a one-line mapping correction or accept “no template for category X” for the session.
4. Optionally summarize for the user: “Categories with templates: …” so they know bodies may follow those structures.

**During enrichment — when to apply a template**

- After the user chooses a **category** (or describes a **task type** that clearly resolves to one allowed category), if that category has a **matched template**, **do not** settle for a vague one-line body: **open that template file**, then **complete** it.
- **Fill strategy:**
  - Use **draft title**, epic context, prior answers, and anything already implied by the ticket line to pre-fill every section or form field you can **without inventing facts**.
  - For each placeholder, instruction, or required form input still missing factual content, **ask the user** concise, targeted questions (group related gaps in one message when reasonable).
  - **Issue forms (YAML):** Treat each `input`, `textarea`, `dropdown`, etc., as a required slot unless marked optional; render the eventual `- body:` as structured markdown that mirrors the template sections (headings from labels, answers under each), still in **French** for narrative content per artefact rules.
  - **Markdown templates:** Preserve headings and section order from the template; replace instructional comments / placeholders with completed content; omit GitHub-only frontmatter from the body stored in the backlog plan (the **creating-an-issue** step still receives plain title/body/category).
- **No matching template:** Collect body as today (freeform or bullet list).
- **Traceability (optional):** You may add `- issue_template: <repo-relative path>` under the `#### Ticket:` block when a template was applied, so reviewers see which shape was used.

**Language:** Template files may be English; the **enriched plan** body text remains **French** unless the user overrides for the project.

### Category proposals (mandatory UX)

Whenever you ask for **category** / **task type** during the ticket-by-ticket loop:

- **Infer** the best fit from the ticket title, epic context, neighbouring tickets in the draft, wording the user already gave, issue-template names/descriptions (`name`, `about`, `labels`), and the allowed category list in `./agents.md`.
- **High confidence:** Propose **one** primary value—the **single most plausible** allowed category—as the suggested default framed for fast acceptance (e.g. propose the value explicitly and invite confirmation or a quick correction).
- **Low confidence:** Do **not** force a lone guess. Propose the **three** most plausible allowed categories, ordered from most to least likely, each with **one short reason** tying it to what is known about the ticket; invite the user to pick one or supply another allowed value. If fewer than three categories exist in `./agents.md`, propose **up to** every remaining plausible one (still ordered by likelihood).
- Never suggest values **outside** the allowed list unless the workflow is extending `agents.md` with the user; the user stays authoritative.

### Skipping a ticket (user directive)

When the user asks to **skip** the current ticket—for example *skip*, *skip this task*, *pass this task for now*, *move on*, *next task*, or equivalent—in **this** enrichment loop:

- **Do not modify** that ticket’s `#### Ticket:` block: leave every field **exactly** as it was **before** any edits made during the current ticket turn. If you already applied partial updates this turn (including after an intermediate save), **revert** that block so the next persisted file matches the pre-turn content for that ticket.
- **Advance** immediately to the **next** issue in document order (epic-scoped then unscoped); acknowledge briefly (e.g. deferred until later).
- **Auto-create mode:** **Do not** create a remote issue for a skipped ticket in that turn.
- **No extra persistence churn:** If skipping implies reverting in-flight edits, write the full plan once so the skipped ticket’s subtree matches the pre-turn state; if nothing had changed yet, you may skip an unnecessary rewrite.

## Rules

- **Language (artefacts):** Plan enrichi en **français** (`summary`, `body`, titres de tickets) — [references/artifact-language-fr.md](../../references/artifact-language-fr.md).
- **Input:** Must match [references/backlog-draft.md](references/backlog-draft.md).
- **Epic lines:** Do not run an interactive “enrichment interview” for epics. When emitting the enriched document, keep each `### Epic:` title in **full uppercase** (per [artifact-language-fr.md](../../references/artifact-language-fr.md)); normalize from the draft if needed. Add `- slug:` and `- summary:` under each `### Epic:` by deriving from the epic title (kebab-case slug) and a short summary; use `NEEDS CLARIFICATION` only when derivation is impossible.
- **Epics with no issue lines:** Still emit the epic block with `slug` / `summary` only (no `#### Ticket:` under that epic). Skip interactive enrichment for that epic until the draft gains bullets or the user adds tickets manually to the enriched file later.
- **Issue lines:** For each `- <issue title>` bullet (and each unscoped title), collect interactively:
  - Who should be **assigned**?
  - **Category** (only values allowed in `./agents.md`; same rules as other product-manager skills). Present a **recommended default or three ranked alternatives** per **Category proposals** before awaiting the user’s choice. If this category **matches** an issue template per **Issue templates**, drive body authoring by **filling that template** (autonomous where context suffices; otherwise ask for missing fields) instead of only asking “what is the task about?” once.
  - What is the task **about** (**body**): either the completed template output (**Issue templates**) or freeform description when no template applies.
  - **Visual reference:** screenshot path, Figma URL, or none — maps to `- visual_reference:` per [creating-an-issue/references/issue-dto.md](../creating-an-issue/references/issue-dto.md)
- **Categories:** Align with `./agents.md`; if the list is missing, ask and persist to `agents.md` before finalizing categories, or use `NEEDS CLARIFICATION`. Still apply **Category proposals** whenever you ask about type/category (using whatever list ends up authoritative).
- **No silent invention:** Do not fabricate requirements; use `NEEDS CLARIFICATION` where the user leaves detail open.
- **Output:** One markdown file matching [enriched-backlog-plan.md](../creating-epics-and-issues-from-backlog-plan/references/enriched-backlog-plan.md); each ticket block aligns with [issue-dto.md](../creating-an-issue/references/issue-dto.md).
- **Canonical backlog plan file (mandatory):** At **project root**, persist the backlog plan to `ai_resources/backlog-plans/<yymmdd>-backlog-plan.md`, where `<yymmdd>` is **YYMMDD** (e.g. `250326` for 2025-03-26). Create the directory `ai_resources/backlog-plans/` if it does not exist.
- **Initialize and create the file immediately:** On entering this skill, **without waiting** for the first enrichment Q&A turn: resolve today’s path; load the backlog draft (default: today’s `ai_resources/backlog-drafts/<yymmdd>-backlog-draft.md` if the user does not supply another path); validate against [references/backlog-draft.md](references/backlog-draft.md). **If** that plan file **does not exist** or is empty, **build** the initial enriched document from the draft—same epic order and titles (`### Epic:` in full uppercase), derived `- slug:` / `- summary:` per the **Epic lines** rule above, each draft issue line as a `#### Ticket:` with `- assignee:`, `- category:`, `- body:`, and `- visual_reference:` all set to `NEEDS CLARIFICATION` until filled—and **write the file to disk**. **If** the plan file **already exists** and is non-empty (resume same day), **load** it as the working plan—do **not** re-seed on top unless the user asks to reset.
- If the visual reference is not provided, there's no need to write a - visual_reference: NONE line in the plan file.
- **Persist after every turn:** After each issue is enriched (or any loop iteration that changes the plan), **write the full current enriched markdown** to the same file so interruption does not lose work. **Skipping a ticket** may require restoring that ticket’s block and then saving once.
- **Auto-create mode:** When active, after each write that completes a creatable epic or ticket (per **Auto-create mode** above), invoke the tracker via **creating-an-epic** / **creating-an-issue**, then **rewrite the file again** including `- tracker_url:` / `- tracker_id:` so the markdown stays the single source of truth.

## Instructions

1. Read `./agents.md` for allowed ticket categories and tracker context. **If** `.github/ISSUE_TEMPLATE/` exists, load templates and **build category ↔ template matches** per **Issue templates** (resolve ambiguities with the user once).
2. Read or receive the backlog draft; validate shape against [references/backlog-draft.md](references/backlog-draft.md).
3. **Create or resume the plan file:** Ensure `ai_resources/backlog-plans/` exists. Use `ai_resources/backlog-plans/<yymmdd>-backlog-plan.md` per the **Canonical backlog plan file** rule above. If new/empty, seed from the draft with `NEEDS CLARIFICATION` stubs as above and **write**; if non-empty, **read** as the working plan. **Do not** delay file creation until after the first answer.
4. Tell the user the **absolute or workspace-relative path** to the plan file.
5. Walk epics in order; for each epic, keep the epic heading and add derived `slug` / `summary` lines when building the enriched output (or preserve them if resuming from file).
6. For each issue title bullet under that epic (if any), then for `### Unscoped`, ask the four question groups (assignee, category—with **Category proposals**, body, visual reference) and update the corresponding `#### Ticket:` block—replacing `NEEDS CLARIFICATION` where answered—or **skip** the ticket per **Skipping a ticket** without altering its block. When category maps to an issue template, **complete the template** for `- body:` (and optional `- issue_template:`) per **Issue templates**. Epics with **no** bullets contribute metadata only.
7. **Write the full enriched markdown** to `ai_resources/backlog-plans/<yymmdd>-backlog-plan.md` **after every turn** that changes the plan.
8. **If auto-create mode is active:** after step 7 when applicable, create or skip remote epic/issue per **Auto-create mode**, append tracker lines, and persist again.
9. At the end, confirm the plan path. If auto-create mode was **off**, point to **creating-epics-and-issues-from-backlog-plan**. If **on** and everything linked, summarize URLs from the file; otherwise point to bulk creation **only for blocks still missing tracker links**.

## Workflow

### Step 1: Load draft, agents, and plan file

**Do:**

1. Accept the backlog draft (path or paste; if omitted, prefer today’s `ai_resources/backlog-drafts/<yymmdd>-backlog-draft.md`).
2. Read `./agents.md` for categories and tooling hints. **If** `.github/ISSUE_TEMPLATE/` exists, scan templates and align them with allowed categories per **Issue templates**; surface mapping gaps once if needed.
3. **Create or resume** `ai_resources/backlog-plans/<yymmdd>-backlog-plan.md`: if missing or empty, seed the enriched structure from the draft (epic `slug` / `summary`, each ticket with `NEEDS CLARIFICATION` for assignee, category, body, visual reference) and **write**; if already has content, **load** as the working plan.
4. State the **resolved path** to the plan file so the user can open it.
5. **If auto-create mode** and the plan was just seeded (or resumed without `- tracker_url:` on epic-only rows): create **epics that have no tickets yet** per **Auto-create mode**, write `- tracker_url:` / `- tracker_id:` under those epic bullets, and **save**—before the first issue enrichment question.

**Success criteria:** Draft text is available; category rules are known or flagged as missing; issue templates (if any) are matched to categories or ambiguity was cleared with the user; the plan file exists on disk with initial or resumed content before the first enrichment question. With auto-create, epic-only parents are linked remotely when applicable before scoped ticket interviews begin.

### Step 2: Enrich each issue

**Do:**

1. Iterate issue lines in document order (epic-scoped then unscoped).
2. For each issue, ask: assignee; **category** with **Category proposals** (single best default vs top three); then **body** (using **Issue templates** when the category matches—gather missing fields by asking); then visual reference (or none). **Or**, if the user invokes **Skipping a ticket**, leave that ticket unchanged and continue with the next issue.
3. Emit or update `#### Ticket:` blocks per [enriched-backlog-plan.md](../creating-epics-and-issues-from-backlog-plan/references/enriched-backlog-plan.md); `- body:` should contain the **filled template** when a template applied.
4. **Write the complete current plan** to `ai_resources/backlog-plans/<yymmdd>-backlog-plan.md` after each update (progressive save).
5. **If auto-create mode:** after saving, ensure parent epic exists if needed; **create the ticket** when resolvable; append `- tracker_url:` / `- tracker_id:`; save again. Never auto-create for tickets the user **skipped** (**Skipping a ticket**).

**Success criteria:** Every draft issue line has a corresponding enriched ticket block (some may remain stubbed if skipped). Epics with zero issue lines appear in the enriched file with `slug` / `summary` only; the on-disk file matches the plan after each turn except skipped tickets revert to their pre-turn content when applicable. Category prompts included a suggested default or three ranked options whenever type was unclear. Where templates matched the category, bodies reflect completed templates (French narrative text). With auto-create, each completed epic/ticket block gains tracker links when creation succeeds.

### Step 3: Hand off

**Do:**

1. Confirm the enriched plan path: `ai_resources/backlog-plans/<yymmdd>-backlog-plan.md` (final write should already match the last turn).
2. **Without auto-create:** point to **creating-epics-and-issues-from-backlog-plan** for tracker creation.
3. **With auto-create:** report created URLs (from `- tracker_url:` lines); run bulk creation only for items still missing tracker links, or after manual edits.

**Success criteria:** User can open the file and either run bulk creation once or rely on incremental creates plus optional cleanup for any skipped blocks. Tickets skipped during enrichment remain at their pre-turn content until a future session edits them.

## Resources

| Type      | Path | Description |
| --------- | ---- | ----------- |
| Reference | [references/backlog-draft.md](references/backlog-draft.md) | Input artifact (title-only draft) |
| Reference | [creating-epics-and-issues-from-backlog-plan/references/enriched-backlog-plan.md](../creating-epics-and-issues-from-backlog-plan/references/enriched-backlog-plan.md) | Output artifact shape |
| Reference | [creating-an-issue/references/issue-dto.md](../creating-an-issue/references/issue-dto.md) | Ticket field semantics |
| Procedure | [creating-an-epic/SKILL.md](../creating-an-epic/SKILL.md), [creating-an-issue/SKILL.md](../creating-an-issue/SKILL.md) | Tracker calls used in auto-create mode |
| Convention | `ai_resources/backlog-plans/<yymmdd>-backlog-plan.md` (project root) | Plan persisté à l’init et après chaque tour (`yymmdd` = YYMMDD) |
| Convention | `- tracker_url:` / `- tracker_id:` under epic or ticket bullets | Written after successful remote create (auto-create); skip create when already present |
| Convention | `.github/ISSUE_TEMPLATE/` (project root) | GitHub markdown / YAML issue templates; optional `- issue_template:` on ticket when body was generated from one |
| External | [GitHub issue templates](https://docs.github.com/en/communities/using-templates-to-encourage-useful-issues-and-pull-requests/about-issue-and-pull-request-templates) | Template formats (frontmatter, forms) |
