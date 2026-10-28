---
name: commit-changes
description: >-
  Stages and commits repository changes using Conventional Commits conventions.
  Use when the user wants to create a commit after implementing changes.
metadata:
  domain: wonderwp-version-control
---

# Commit Changes

## Instructions

### Step 1: Inspect repository state

1. Run:
   - `git status`
2. Identify which files are:
   - changed
   - staged
   - untracked
3. Confirm the user intent for what should be included in the commit.

**Success criteria:** You have a clear list of what will be committed.

### Step 2: Stage changes

Choose the least risky staging approach:

1. If the user wants “everything”, stage all changes:
   - `git add -A`
2. If the user wants selective staging (recommended when changes are mixed), stage interactively:
   - `git add -p`
3. If the user wants to stage only specific files, ask for the file paths and use:
   - `git add <path1> <path2>`

**Success criteria:** `git status` shows the intended changes as “staged”.

### Step 3: Prepare a Conventional Commit message

Follow this structure:

```text
type(scope): subject
```

Where:
- `type` is one of: `feat`, `fix`, `chore`, `docs`, `refactor`, `test`, `build`, `ci`, `perf`
- `scope` is optional (e.g. `git`, `hooks`, `docs`, `version-control`)
- `subject` is a short imperative description (no trailing period)

**Do:**
1. Propose 1-3 candidate messages based on the staged changes.
2. Ask the user to pick one (or provide the desired `type`, `scope`, and `subject`).

**Success criteria:** The final message follows Conventional Commits formatting.

### Step 4: Commit

1. Commit with the chosen message:
   - `git commit -m "<type>(<scope>): <subject>"`
2. Run `git status` again.

**Success criteria:** Working tree is clean (or only contains expected follow-up changes).

## Examples

### Example – Feature

```text
feat(version-control): add PR creation helper
```

### Example – Fix

```text
fix(git): handle detached HEAD during branch switching
```

## Next Steps

- Use `create-or-manage-git-branches` if you still need to create/switch the target branch.
- Use `create-pull-request` once the commit(s) are on the branch you want to open a PR from.

