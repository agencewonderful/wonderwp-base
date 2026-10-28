# GitHub Project template (Wonderful org)

## Canonical template

| Property | Value |
| -------- | ----- |
| Org | `agencewonderful` |
| Project number | **7** |
| Title | Site template |
| URL | https://github.com/orgs/agencewonderful/projects/7 |
| Marked as template | yes (`template: true` in GraphQL) |

## Expected custom fields (beyond GitHub defaults)

After copying the template, the new project should include:

| Field | Type | Notes |
| ----- | ---- | ----- |
| Status | Single select | Backlog, Ready, In progress, In review, Done |
| Priority | Single select | P0, P1, P2 |
| Size | Single select | XS, S, M, L, XL |
| Estimate | Number | |
| Iteration | Iteration | |
| Start date | Date | |
| End date | Date | |

Default-only projects (created with `gh project create` without copy) typically have **Status: Todo / In Progress / Done** and lack Priority, Size, Estimate, Iteration, and date fields.

## Creating from template

**Preferred (CLI):**

```bash
gh project copy 7 \
  --source-owner agencewonderful \
  --target-owner agencewonderful \
  --title "My Site"
```

**GraphQL equivalent:** `copyProjectV2` with `projectId` (template node id), `ownerId`, and `title`.

There is **no** `createProjectV2` input for “use template”; template semantics are implemented by **copying** a project marked as template (or any existing project with the desired structure).

## Reconfiguring an existing non-template project

GitHub does **not** expose an API to “apply template” to an existing Project v2. Options:

### Option A — Copy and migrate (recommended)

1. Copy template #7 to a new project (use the consumer project title from `agents.md` or a distinct board title).
2. Link the consumer repository: `gh project link <n> --owner agencewonderful --repo owner/repo`.
3. Re-add issues: `gh project item-add <n> --owner agencewonderful --url https://github.com/owner/repo/issues/<id>` for each issue, or re-run backlog sync with `GITHUB_PROJECT_NUMBER=<n>`.
4. Update the consumer **`agents.md`** with the new project number.
5. Close or archive the old project after team validation.

### Option B — Patch fields in place (partial)

Add missing fields with `gh project field-create`:

```bash
gh project field-create <number> --owner agencewonderful --name "Priority" \
  --data-type SINGLE_SELECT --single-select-options "P0,P1,P2"
```

Limitations:

- **Status** options on an existing board are hard to align with the template (Todo vs Backlog, missing Ready / In review).
- **Iteration** fields cannot be created via `field-create` (only TEXT, SINGLE_SELECT, DATE, NUMBER).
- Views and workflows from the template are **not** copied.

### Blank project vs template (#7)

A project created with `gh project create` (without copy) typically has **13 fields** (default set). Template **#7** has **19 fields** including Priority, Size, Estimate, Iteration, Start/End date, and an extended Status workflow. Use **Option A** for full parity.

## Auth and tooling

```bash
gh auth refresh -s project
gh project field-list 7 --owner agencewonderful
gh project view 7 --owner agencewonderful --web
```

Backlog sync (`sync_backlog_plan_to_github.py`) attaches new issues via GraphQL `projectV2Ids`; set `GITHUB_PROJECT_NUMBER` to the new board number after migration.
