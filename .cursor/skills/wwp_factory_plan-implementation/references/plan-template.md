# Plan document template

Copy this structure into `ai_resources/plans/<YYMMDDHHmm>-<feature-slug>.md`. Replace placeholders; remove unused optional sections only if truly not applicable.

---

```yaml
status: pending
```

Allowed values: `pending` | `in-progress` | `implemented` | `blocked`. Set to `pending` at plan creation; **implement** updates this as work progresses.

# {{TITLE}}

## Summary of intent

{{One short paragraph: what problem this solves, for whom, and what “done” means—no implementation detail.}}

## Related context (top matter)

- **Issue(s):** {{links or IDs}}
- **Branch (if known):** `{{branch-name}}`
- **Orchestration run log (if any):** `ai_resources/wonderwp/orchestration/<YYMMDDHHmm>-<feature-slug>.md`

## Proposed implementation flow

```mermaid
flowchart TD
  A[{{Step 1 label}}] --> B[{{Step 2 label}}]
  B --> C[{{Step 3 label}}]
  %% Add or remove nodes to match the real sequence; use subgraphs for parallel tracks if needed.
```

Keep the diagram **high level** (5–12 nodes): major moves, dependencies, and decision points—not every file edit.

## Implementation steps

Each step below is a **single todo**: one checkbox, clear outcome, and the **files** expected to change or be added (paths relative to repo root). Use `- [ ]` so the same list can be mirrored in the agent todo list tool. **implement** checks boxes and fills **Result** as each step completes.

### Step 1 — {{short step title}}

- [ ] **Todo:** {{Imperative description of what to complete in this step.}}
- **Files:** `path/to/a`, `path/to/b` (create / modify as noted)
- **Agent + skill (if delegated):** {{agent name}} — {{skill id or path}}
- **Acceptance:** {{How you know this step is done.}}
- **Result:** _(filled by implement)_

### Step 2 — {{short step title}}

- [ ] **Todo:** {{…}}
- **Files:** `…`
- **Agent + skill (if delegated):** {{…}}
- **Acceptance:** {{…}}
- **Result:** _(filled by implement)_

<!-- Repeat ### Step N blocks for every atomic unit of work. Order matters; note dependencies in prose if not obvious from the mermaid diagram. -->

## Dependencies and ordering

- {{What must happen before what; external services, feature flags, migrations order, etc.}}

## Risks and unknowns

| Risk / unknown | Mitigation or validation |
| -------------- | ------------------------ |
| {{item}} | {{action}} |

## Handoff to implementation

Use this block when the plan is **approved** so the next session or specialist can start without re-deriving context.

- **Plan file:** `ai_resources/plans/<YYMMDDHHmm>-<feature-slug>.md` (this file)
- **Intent (one line):** {{repeat or point to Summary of intent}}
- **First todo to execute:** Step {{N}} — {{title}}
- **Suggested implementer:** wonderwp using **implement** (or domain agent + skill for Step {{N}})
- **Branch:** `{{branch-name}}` (create if missing)
- **Out of scope for this plan:** {{explicit non-goals}}

**Next action:** Run the **implement** skill (`prepare` → `execute` → `finalize`) on this plan file; work **Implementation steps** in order, checking off each `- [ ]` as completed; update the orchestration run log after each step.

## Implementation report

_Filled by the **implement** skill on finalize. Link to the persisted report:_

- **Report file:** `ai_resources/wonderwp/implementation/<YYMMDDHHmm>-<feature-slug>.md`
