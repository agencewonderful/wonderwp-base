# Proposal format

Present harness change proposals in **French** (paths and technical ids in English). Do not apply edits until the user validates.

## Summary table (required first)

| # | Type | Target | Change type | Confidence | One-line impact |
| - | ---- | ------ | ----------- | ---------- | --------------- |
| 1 | `missing_constraint` | `plugins/.../SKILL.md` | `edit` | high | … |

Sort by confidence then by how often the friction recurred in the session.

## Per-item proposal (required for each row)

### Proposal N — \<short title\>

- **signal:** short quote or paraphrase from the conversation (proof)
- **friction type:** one label from [friction-taxonomy.md](./friction-taxonomy.md)
- **root cause:** what was missing or unclear in the harness
- **target:** exact path under `plugins/...` (or note that a **new** file is needed)
- **change type:** `edit` | `add-section` | `new-reference` | `new-template` | `new-skill` | `routing-frontmatter`
- **proposed patch:** concrete prose to inject, or a unified diff hunk
- **impact:** how this prevents the same friction next time
- **confidence:** `high` | `medium` | `low`

## Change-type guidance

| Change type | Prefer when |
| ----------- | ----------- |
| `edit` | Existing section is wrong or incomplete |
| `add-section` | Gap needs a new Rule / Workflow step / Resources row |
| `new-reference` | Long guidance does not belong inline in `SKILL.md` |
| `new-template` | Repeatable file shape (`assets/*.tpl`) was missing |
| `new-skill` | No existing skill covers the task (last resort) |
| `routing-frontmatter` | Discovery / `Use when` / agent skill list was wrong |

## Closing prompt

After listing proposals, ask the user which items to apply (`all`, numbers, or `none`). Remind that after any applied patch they should reinstall Cursor skills:

```bash
./.wonderful/scripts/install.sh cursor
```
