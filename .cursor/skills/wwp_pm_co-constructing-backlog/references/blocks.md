# Subprocedure: Custom blocks

## Goal

Identify **custom blocks** (CMS/editor blocks or project-specific block types) the build needs so tickets can cover implementation, QA, and documentation.

## When to use

Invoke this subprocedure when asking: **Does this project have specific custom blocks needed? If yes, which ones?**

## Instructions

1. Clarify context if needed: “blocks” here means **custom block types** the project must provide (e.g. Gutenberg blocks, page builder blocks, or equivalent), not generic HTML elements.
2. If **no** custom blocks are required, record that and proceed.
3. If **yes**, collect for each block at minimum:
   - **Name** (user-facing or internal);
   - **Purpose** in one sentence;
   - **Dependencies** (other blocks, APIs, design assets) if mentioned; else `NEEDS CLARIFICATION`.
4. Ask whether blocks are **independent** or **shared across templates**; note implications for ordering tickets (shared primitives first).
5. Add backlog items: typically **one ticket per block** or one epic **Custom blocks** with child tickets—match user preference and granularity.
6. Confirm the list with the user before finalizing.

## Output shape (session notes)

```markdown
## Custom blocks

| Block | Purpose | Notes |
| ----- | ------- | ----- |
| ... | ... | NEEDS CLARIFICATION if needed |
```

## Anti-patterns

- Assuming framework (e.g. WordPress) without checking `agents.md` or the user.
- Merging unrelated blocks into a single vague ticket without user consent.
