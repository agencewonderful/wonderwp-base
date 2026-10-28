---
description: Extract design system components from Figma into contract.json files, au fil de l'eau. Detects whether the Figma URL points to a single node or a page; for pages, iterates over every COMPONENT-type child. Classifies each component as atom or molecule via an auto-heuristic with user confirmation, then delegates to create-atom or create-molecule with Figma context pre-filled. The E in the design system's ETL.
argument-hint: "[<figma-url>] [--refresh] [--interactive]  (omit URL to ask)"
allowed-tools: AskUserQuestion, Read, Write, Glob, Grep, Bash, Skill, mcp__plugin_figma_figma__whoami, mcp__plugin_figma_figma__get_metadata, mcp__plugin_figma_figma__get_design_context, mcp__plugin_figma_figma__get_variable_defs, mcp__plugin_figma_figma__get_screenshot
---

You are the `/extract-from-figma` command: the Extract phase of the design
system's ETL. Pull components from Figma, classify each as atom or molecule,
and delegate creation to `create-atom` / `create-molecule` with Figma context
pre-filled.

`$ARGUMENTS`: a Figma URL (empty → ask), plus optional flags:

- `--refresh` — mark every cache entry stale (R-CACHE-4).
- `--interactive` — step-by-step dialogue instead of the default batch
  profile (R-ASK-1, `rules/interaction.md`); forward the flag to the create
  skills.

Every Figma MCP payload goes through the **snapshot cache**
(`rules/figma-cache.md`): check the cache before any MCP call, persist every
fetch before use. A fully cached run needs no Figma session at all.

Harness note: DELEGATE / TRACK / Figma MCP operations below are abstract
capability verbs — per-harness bindings in `rules/harness.md` (R-HARNESS-1).

## Pre-flight

1. **Parse the URL + check the cache:**

   ```bash
   node plugins/wonderwp-design-system/scripts/figma-cache.mjs init --url "<figma-url>"
   ```

   Returns `fileKey` / `nodeId` / `nodeDir`, cache paths, per-artifact
   `exists` + `ageHours`; scaffolds the cache folders + `{ds}/.gitignore`
   line (idempotent). Mode selection:
   - `node-id` query param present → **single-node mode**.
   - No `node-id` (or page reference only) → **page mode**: every
     `COMPONENT` / `COMPONENT_SET` on the page.
   - Malformed URL (script exits 1) → ask the user for a valid one.
2. **Authenticate only if fetching.** Any required artifact missing (or
   `--refresh`) → run the Figma MCP `whoami` once; on failure surface the
   error and stop. Everything cached → skip this step entirely.
3. **Surface reuse.** Report what is served from cache and its age, e.g.
   "3 nodes from cache (fetched 26h ago) — pass `--refresh` to re-fetch"
   (R-CACHE-4).

## Single-node mode

1. Metadata through the cache (R-CACHE-1): `nodes/<node-dir>/metadata.json`
   exists and no `--refresh` → Read it; else call the Figma MCP
   `get_metadata` on the node-id and Write the payload to that cache path
   (R-CACHE-2).
2. Node type must be `COMPONENT` or `COMPONENT_SET`. Otherwise (e.g. a
   `FRAME` selected by mistake) inform the user and ask whether to extract
   anyway as a one-off atom.
3. Run **Per-component extraction** (below) on that node.

## Page mode

1. Page metadata through the cache (R-CACHE-1): `<file-key>/metadata.json`
   exists and no `--refresh` → Read it; else call the Figma MCP
   `get_metadata` on the page-id (the URL minus `node-id`) and Write the
   payload there (R-CACHE-2).
2. Variables once per file (R-CACHE-3): `<file-key>/variables.json` missing
   (or `--refresh`) → call the Figma MCP `get_variable_defs` on the page
   node and Write the payload there. Every per-component extraction reuses
   this one artifact — no per-node MCP call.
3. Collect every direct child of type `COMPONENT` / `COMPONENT_SET`. Ignore
   nested components — they resolve as child atoms during molecule extraction.
4. Ask (showing component name + Figma-side type): "Found N components on
   this page. Extract all sequentially, pick a subset, or abort?"
5. For the chosen subset —
   **Batch profile** (default — R-ASK-2/3): gather Figma context for EVERY
   selected component first (§1 — cache hits in bulk, misses in parallel) →
   auto-classify all (§2) → confirm ALL classifications in ONE table round
   (§3; one row per component: name, classification + reasoning, screenshot
   path; each row amendable to `switch` / `skip`) → run §4–§6 per component
   in page order, without further classification stops.
   **Interactive profile** (`--interactive`): run §1–§6 fully per component,
   one at a time.
   Either way, TRACK per-component progress (`rules/harness.md`) so the user
   sees forward motion.

## Per-component extraction

For each target component node:

### 1. Gather Figma context (through the cache — R-CACHE-1)

Per artifact: hit → Read from disk; miss or `--refresh` → fetch via the MCP,
persist to the cache (R-CACHE-2), then use. Misses may fetch in parallel.

| Artifact | Cache path | On miss (Figma MCP op) |
|---|---|---|
| Design context — full payload (hierarchy, styles, declared variables) | `nodes/<node-dir>/context.json` | `get_design_context`; Write the payload verbatim |
| Variables | `<file-key>/variables.json` (fetched once in page mode) | Only when this node references variables absent from it, or in single-node mode: `get_variable_defs` on the node → `nodes/<node-dir>/variables.json` (R-CACHE-3) |
| Screenshot | `nodes/<node-dir>/screenshot.png` | `get_screenshot`; download straight to the cache path (`curl -L -o`). Reused by `capture-figma-screenshots` at Step 7 — never fetched twice in one run |

### 2. Auto-classify atom vs molecule

Apply in order against the design context:

1. Count descendants of Figma type `INSTANCE` (instances of *other*
   components): ≥ 1 → provisional **molecule**; 0 → provisional **atom**.
2. **Override A** — `COMPONENT_SET` whose every variant is a single-element
   atom (no instances) → **atom** with variants, not a molecule.
3. **Override B** — no instances, only `TEXT`/`VECTOR` children of trivial
   depth → **atom** (typical for labels, badges, icons).

Record the classification AND one-sentence reasoning — both shown at the
confirmation step.

### 3. Confirm with the user

Batch profile: this confirmation happens ONCE for all selected components
(page mode step 5 — one table, R-ASK-3); per component only in single-node
mode or `--interactive`. Show:

- Component name + Figma node-id (with the URL)
- Auto-classification + reasoning
- The cached screenshot path `nodes/<node-dir>/screenshot.png`
  (question UIs cannot render images — give the path so the user can open it)
- Detected child component instances (molecule cases)
- Declared variables (token-mapping preview)

Options: `Yes — create as <atom|molecule>` (recommended) / `Switch to
<molecule|atom>` / `Skip this component` / `Abort the run`. Respect the
user's choice; if switched, re-confirm before delegating.

### 4. Pre-fill context for the create skill

Derive as much as possible from the Figma payload so the create skill's
prompts are mostly pre-answered:

**For atoms (`create-atom`):**
- `name` ← Figma node name
- `slug` ← kebab-case of the name (confirm with user)
- `source.url` ← the full Figma URL with `node-id`
- `source.type` ← `"figma"`
- `parts` ← top-level child layer names (kebab-cased)
- Variant axes ← `COMPONENT_SET` axis definitions when present
- `defaultVariant` ← Figma's "default" variant when declared
- Token candidates ← from the cached variable defs (`<file-key>/variables.json`,
  node-scoped fallback), map each Figma variable reference to a probable
  `{family.path}` in `{ds}/tokens.json`. Surface unmapped variables to the user.
- Semantic tree skeleton ← a first-pass abstract tree derived from the
  Figma layer hierarchy (PascalCase nodeName guess from layer name +
  role hints). Present as a draft for the user to refine.

**For molecules (`create-molecule`):**
- All of the above, plus:
- `childComponents` ← every `INSTANCE` descendant, with its slug (the
  Figma main-component name kebab-cased). The user confirms each slug
  and decides whether to create it on the fly if missing.

### 5. Delegate to the skill

DELEGATE (`rules/harness.md`) to the matching create skill, passing the
pre-filled context as its input string:
- Atom → DELEGATE(`create-atom`, `<pre-filled-context-as-string>`)
- Molecule → DELEGATE(`create-molecule`, `<pre-filled-context-as-string>`)

The pre-filled context is the skill's first input: its Step 1 (Define
identity) reads from it instead of asking from scratch. The skill still owns
confirmation and writes; this command only pre-fills. Wait for completion;
surface its summary verbatim.

### 6. Move on

On success (`contract.json` written, validation passed): next component in
page mode, or end the run in single-node mode.

## Step 7: Capture screenshots (Figma + Playwright)

After all create-skill calls have completed (and before opening any PR):

DELEGATE(`capture-figma-screenshots`, `"<comma-separated list of
<layer>/<slug> for every component successfully created in this run>"`)

Example args: `"atoms/badge, atoms/button, molecules/card-editorial"`. Wait
for completion and store the returned **capture manifest**. Two images per
component:

- `screenshots/figma-reference.png` — from the snapshot cache when the node
  was fetched during this run (R-CACHE-1 — no second MCP fetch), from the
  Figma MCP otherwise.
- `screenshots/rendered.png` — Playwright on `{ds}/preview.html` (only when
  the file exists and Playwright is installed; skipped gracefully otherwise).

If capture fails entirely (Figma auth lost, all targets errored): warn, do
NOT abort — the contracts are already written and valid. Screenshots are
supplementary evidence, not blocking artefacts.

## Step 8: Compose PR description

Build from the capture manifest:

### Section A — Visual conformance table

One row per manifest result — include ALL components regardless of
conformance status, so the reviewer sees the full picture. Image base URL
(the PNGs are committed to the PR branch):

    {RAW} = https://github.com/<owner>/<repo>/raw/<branch>

`<owner>/<repo>` from `git remote get-url origin`; `<branch>` = the pushed PR
branch. Reference each manifest `figmaPath` / `renderedPath` (already
repo-root-relative) as `{RAW}/<path>`:

```markdown
## Visual conformance

| Component | Figma reference | Rendered (Playwright) | Status |
|---|---|---|---|
| `atoms/badge` | ![Figma]({RAW}/<figmaPath>) | ![Rendered]({RAW}/<renderedPath>) | ✅ both |
| `atoms/button` | ![Figma]({RAW}/<figmaPath>) | ![Rendered]({RAW}/<renderedPath>) | ✅ both |
| `molecules/card-editorial` | ![Figma]({RAW}/<figmaPath>) | _(card not found — rebuild styleguide)_ | ⚠ partial |
| `atoms/heading` | _(no Figma node-id)_ | _(no preview target)_ | ❔ skipped |
```

Status legend: ✅ both images / ⚠ one image only / ❔ no images / ✗ capture errors.

**Do not use repo-relative image paths in the PR body** — GitHub resolves
those only inside committed Markdown files, never in a PR description. The
branch must be pushed before the `{RAW}` URLs resolve. Branch name contains
slashes → use the unambiguous `.../raw/refs/heads/<branch>/<path>` form.

### Section B — How to use the components

For each component (manifest fields `componentName`, `defaultVariant`,
`cascadeOrder`, `variantKeys`, `cssContract`, `templateHtml`), generate a
subsection following
`skills/capture-figma-screenshots/references/manifest-format.md`
(§ "PR section 2 — How to use"):

1. One-line purpose (from `contract.json`
   `$extensions.com.wonderwp.component` + the semantic root nodeName).
2. Default-variant HTML snippet (from `templateHtml`).
3. Variant table: axis / value / BEM modifier class; mark the default
   "_(default)_"; skip if `variantKeys` is empty.
4. Per-axis usage example (modifier class applied to the base HTML).
5. CSS custom properties table (required vs optional from `cssContract`).

`templateHtml` null (contract has no `semantic` section) → note: "_Template
unavailable — semantic section is null. Run `create-atom` / `create-molecule`
to populate it._"

## End-of-run summary

After all targets are processed (including screenshot capture), print:

```
extract-from-figma summary
─────────────────────────────────
✓ atoms created:     N (slugs: ...)
✓ molecules created: M (slugs: ...)
⊘ skipped:           K (reasons: classification rejected / user skip / Figma error)
📷 figma screenshots: S captured, K skipped, E errors
🎭 playwright:        S captured, K skipped, E errors
```

Next steps to suggest:
- Playwright skipped for missing `preview.html` → "Run `/build-design-system`
  then re-run `/extract-from-figma` (or `capture-figma-screenshots`) to add
  rendered screenshots."
- Otherwise → "Run `/generate-styleguide` to regenerate `preview.html` and
  inspect freshly extracted components in the browser."

## Boundaries

- Writes NO design-system artifacts (contracts, tokens, `components.json`) —
  only orchestrates `create-atom` / `create-molecule`, the sole authorities
  for contract emission. Its only writes are snapshot-cache entries under
  `{ds}/.cache/figma/` (R-CACHE-2) — derived, gitignored, disposable
  (R-CACHE-5).
- Invokes NO transform (`load-contract`, `generate-styleguide`, …) — those
  run downstream, on the user's request.
- Modifies NO existing contract unless the user explicitly chooses
  "Overwrite" inside the create skill's idempotence check.
- Figma MCP session unauthenticated or rate-limited → stop with a clear
  error, never degrade silently.
