# Implementation report template

Copy this structure into `ai_resources/wonderwp/implementation/<YYMMDDHHmm>-<feature-slug>.md`. Replace placeholders; link to this file from the plan's **Implementation report** section.

---

# Implementation report — {{TITLE}}

## Related context

- **Plan file:** `ai_resources/plans/<YYMMDDHHmm>-<feature-slug>.md`
- **Branch:** `{{branch-name}}`
- **Issue(s):** {{links or IDs}}
- **Orchestration run log (if any):** `ai_resources/wonderwp/orchestration/<YYMMDDHHmm>-<feature-slug>.md`

## Outcome

- **Status:** success | partial | blocked
- **Summary:** {{2–4 sentences: what was delivered vs the plan's Summary of intent; note if anything failed or was skipped}}

## Acceptance criteria

| Step | Criterion | Result | Notes |
| ---- | --------- | ------ | ----- |
| 1 | {{from plan Step 1 Acceptance}} | pass / fail / skipped | {{optional}} |
| 2 | {{…}} | {{…}} | {{…}} |

## Files changed

| Path | Change | Step |
| ---- | ------ | ---- |
| `path/to/file.php` | modified | 2 |
| `path/to/new-file.js` | added | 3 |

Populate from `git diff --name-status` (or project equivalent). Map each file to the step that primarily touched it when possible.

## Deviations from plan

- {{none — or list assumed deviations with brief rationale}}

## Follow-ups / out of scope

- {{remaining work, tech debt, manual QA, docs not written}}

## PR draft inputs

Use this block when handing off to **create-pull-request**.

- **Title suggestion:** {{concise PR title}}
- **Summary bullets:**
  - {{what changed — bullet 1}}
  - {{why — bullet 2}}
  - {{user-visible or ops impact — bullet 3}}
- **Test plan checklist:**
  - [ ] {{manual or automated check 1}}
  - [ ] {{check 2}}
