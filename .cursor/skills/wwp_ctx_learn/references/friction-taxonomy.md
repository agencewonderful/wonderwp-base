# Friction taxonomy

Use these labels when mining a session conversation for harness gaps. Assign **one primary type** per friction; add a secondary type only if clearly distinct.

| Type | Signal in conversation | Typical harness fix |
| ---- | ---------------------- | ------------------- |
| `ambiguity` | User had to clarify intent, scope, or naming the agent guessed wrong | Tighten skill Rules / Workflow; add decision defaults |
| `missing_constraint` | User corrected a path, convention, tool, or policy the agent ignored or never knew | Add an explicit Rule or reference; link from the skill |
| `wrong_default` | Agent chose a valid but wrong default; user overrode it | Change the documented default; document when to ask |
| `missing_template` | Agent invented structure; user supplied or corrected a pattern | Add/update `assets/*.tpl` or a reference example |
| `stale_reference` | Skill pointed at outdated paths, APIs, or steps; user corrected facts | Update references, templates, or Workflow steps |
| `missing_routing` | Wrong skill/agent activated, or skill not discovered | Fix frontmatter `description` / `Use when`; agent Available Skills |
| `late_question` | Agent asked something that should have been in Rules or asked earlier | Move constraint into skill; add a pre-flight check |
| `retry_loop` | Multiple attempts / « non pas comme ça » before success | Add Success criteria, anti-patterns, or a worked example |

## What counts as evidence

- Explicit user corrections or “do it this way instead”
- Clarifications that unblocked autonomy
- Repeated failed approaches then a user-directed path
- Undocumented expectations (naming, folder layout, install steps)

## What does not count

- Pure product/domain requirements unrelated to harness clarity
- One-off preferences the user says should stay session-local
- Speculations without a conversational proof
