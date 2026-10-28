# Interactive remediation plan

## Intent

Convert ranked findings into an executable plan **without** skipping human judgment. Many findings look urgent in traces but are wrong layer, wrong trade-off, or OOS.

## Mode

Use Plan mode (or an explicit conversational checklist). One finding (or coherent lot) at a time.

## Per-finding questions

1. Is the measured cost still relevant after earlier steps in the lot?
2. Fix in which package? (framework → `wwp-*` → project)
3. What is the acceptance metric?
4. Keep / defer / cancel?
5. Any bench required before merge? (especially SQL shape changes)

## Plan file shape

Path: `ai_resources/plans/<YYMMDDHHmm>-<topic>-remediation.md`

Frontmatter statuses over time:

- `validated` — human agreed scope
- `implemented` — code landed / PRs opened
- `closed` — re-measured, probe removed, docs updated

Body sections:

1. Summary of intent + Done criteria
2. Related context (report path, branches, diffusion rules)
3. Mermaid flow (lots / steps)
4. Out-of-scope table
5. Implementation steps (checkbox, files, agent/skill, acceptance, result)
6. Risks
7. Handoff / diffusion (1 PR per repo; human merge; human `composer update`)

## Dedicated child plans

Keep the **main** remediation plan focused on the chantier Done criteria.

Spin a **dedicated plan** when a finding (or group) would otherwise dominate the parent:

- multi-package redesign or long discovery;
- a different success metric / release train;
- work that can ship independently without blocking the main close.

Then:

1. Create `ai_resources/plans/<stamp>-<subtopic>-remediation.md` with its own status lifecycle.
2. In the main plan, replace the fat step with a short pointer (link + acceptance gate, e.g. “child plan closed or deferred”).
3. Agree with the human whether the main chantier can close while child plans remain open.

## Sequencing heuristics

- Kill **blocking outbound I/O** on the editor request first (warmup, sync HTTP).
- Then **reduce fan-out** (URL count), then **cheapen remaining work** (N+1, memo).
- Bootstrap/autoload last unless it dominates every request including GET.
- Consumer project wiring PR **last** (depends on upstream tags/commits).

## Validation during review

If a “faster” SQL path introduces N+1 (`get_posts` ids → per-row `get_post_meta`), require a join or `update_meta_cache` / `_prime_post_caches` and a local query-count bench before approving the PR.

## After close

- Mark plan `closed_at`
- Record before/after in perf README
- Ensure temporary measurement code is gone from `main`/`master` deploy
