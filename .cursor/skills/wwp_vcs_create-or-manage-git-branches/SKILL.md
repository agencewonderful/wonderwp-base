---
name: create-or-manage-git-branches
description: >-
  Creates, lists, and manages git branches for WonderWP projects following GitFlow-style conventions.
  Use when the user needs to create a feature/hotfix branch, switch branches, and push them to the remote.
metadata:
  domain: wonderwp-version-control
---

# Create or Manage Git Branches

## Instructions

### Step 1: Choose the branching intent and base

1. Ask what kind of work the user is starting:
   - `feature`
   - `release`
   - `hotfix`
2. Ask for (or suggest) the base branch:
   - `feature/` branches come from `develop` (GitFlow-style)
   - `release/` branches come from `develop` (GitFlow-style)
   - `hotfix/` branches come from `main` (GitFlow-style)
3. Determine the target branch name:
   - feature: `feature/<short-description>`
   - release: `release/<short-description>`
   - hotfix: `hotfix/<short-description>`

**Success criteria:** Branch type, base branch, and final branch name are defined.

### Step 2: Ensure the working tree is ready

1. Run:
   - `git status`
2. If there are uncommitted changes:
   - prefer committing first (via `commit changes`)
   - or, if appropriate, stash:
     - `git stash push -u -m "<optional-note>"`

**Success criteria:** You can safely create/switch branches without losing intended work.

### Step 3: Fetch latest refs and update base branch

1. Run:
   - `git fetch --all --prune`
2. Checkout the base branch and update it:
   - `git switch <base>`
   - `git pull`

**Success criteria:** Base branch reflects the latest remote state.

### Step 4: Create or switch to the target branch

1. Check whether the branch already exists:
   - `git branch --list <branch>`
2. If it does not exist locally:
   - `git switch -c <branch>`
3. If it exists locally:
   - `git switch <branch>`
4. If the branch exists only remotely, create it tracking remote:
   - `git switch --track <branch>`

**Success criteria:** The target branch is checked out.

### Step 5: Push the branch to the remote

Push and set upstream:
- `git push -u origin <branch>`

**Success criteria:** The remote branch exists and the local branch tracks it.

### Step 6: Optional cleanup after merge

If the branch has been merged already, you may clean up:
- Delete local:
  - `git branch -d <branch>`
- Delete remote:
  - `git push origin --delete <branch>`

**Success criteria:** Both local and remote are removed (when deletion is requested and safe).

## Examples

### Example – Feature branch

Create `feature/add-user-search` from `develop`, then push it:
- base: `develop`
- branch: `feature/add-user-search`
- commands (conceptually):
  - `git switch develop && git pull`
  - `git switch -c feature/add-user-search`
  - `git push -u origin feature/add-user-search`

### Example – Hotfix branch

Create `hotfix/fix-login-redirect` from `main`, then push it:
- base: `main`
- branch: `hotfix/fix-login-redirect`

## Next Steps

- Use `commit changes` while working on this branch.
- Use `create-pull-request` once the branch is pushed and ready for review.

