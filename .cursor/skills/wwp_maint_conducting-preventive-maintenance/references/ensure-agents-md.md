# Ensure project-root `agents.md`

Concern: `one_shot.ctx.ensure-agents-md` (`skill_step`: `ensure-agents-md`).

## Goal

Every WonderWP consumer project should have a root **`agents.md`** so assistants know product context, git/PR tooling, and issue-tracker categories. This one-shot runs once per site (then `maintenance:absorb`).

## When

Phase **env**, after `.wonderful` submodule sync + skill install (`recurring.wonderful.submodule-and-install-skills`) **and** after `maintenance:session start`, so `initializing-agents-md` is available and `maintenance:absorb` has a session id.

## Do

1. Check for `./agents.md` at the **project root** (same root as `.cursor/` / Bedrock root).
2. **If the file exists:** do not overwrite. Tick the runbook box and absorb:

   ```bash
   ddev wp maintenance:absorb one_shot.ctx.ensure-agents-md --session=…
   ```

3. **If the file is missing:** invoke the context-engineer skill **`initializing-agents-md`** (`wwp_ctx_initializing-agents-md` / `.wonderful/plugins/wonderwp-context-engineer/skills/initializing-agents-md/`). Follow that skill end-to-end (infer from README when possible; ask only for missing tracker/categories facts). Commit `agents.md` on the maintenance branch with the rest of the session work.
4. After `agents.md` is present: absorb the concern as above.

## Rules

- Never paste secrets into `agents.md`.
- Do **not** absorb while the file is still missing.
- If the operator refuses to create it now: mark runbook `[!]` with reason; leave pending (do not absorb).
