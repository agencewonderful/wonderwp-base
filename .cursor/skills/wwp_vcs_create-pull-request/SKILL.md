---
name: create-pull-request
description: >-
  Opens a pull request from a git branch while remaining agnostic to the git server/provider.
  Use when the user wants to create a PR after committing and pushing changes.
metadata:
  domain: wonderwp-version-control
---

# Create Pull Request

## Instructions

### Step 1: Gather pull request details

A pull request needs to have the following details.
Decide on those autonomously without asking the user.

1. The source branch:
   - Assume the current branch (`git branch --show-current`)
2. The target branch:
   - We are using the GitFlow model, so the target branch is the develop branch.
3. PR title:
   - a short imperative summary that you can come up with based on the changes in the branch
4. PR body:
   - include key changes. 
      - Each key change should be a documented section containing : 
         - a dedicated title, 
         - an explicative paragraph 
         - a list of related folders or files that were changed.
         - a list of tests that were tested and passed. (only if relevant)
         - a list of tests to do on the application after the PR is merged. (only if relevant)
      - Do one section per key change if there are multiple changes.
   - Reference related issue/PR numbers if available. Prefer closing issues with the PR. So for example if you close issue #123, you can use `Closes #123` in the PR body.

**Success criteria:** You have the head/base branches and the proposed title/body.

### Step 2: Ensure the branch is available on the remote

Before opening a PR, ensure the `head` branch exists on the remote:

1. Run:
   - `git status`
2. If `head` is not pushed yet, push it:
   - `git push -u origin <head>`

**Success criteria:** The `head` branch is pushed and trackable by the remote.

### Step 3: Detect the configured git tool/provider from `agents.md`

To keep this skill agnostic to the git server (GitHub/GitLab/etc), do NOT hardcode provider logic.
Instead, instruct the agent to:

1. Locate and read `agents.md` at the project root:
   - `./agents.md`
2. Search inside `agents.md` for the configured git tool / MCP server used for pull requests.
3. Extract from the file:
   - which provider/tool it refers to (e.g. GitHub vs GitLab vs another)
   - any tool/MCP server identifiers and required parameters

If `agents.md` is missing or you cannot detect the git tool/provider:
- Ask the user to provide the git tool/provider configuration or to point you to the correct `agents.md` file path.

**Success criteria:** You can identify the exact PR-creation tool/server referenced by `agents.md`.

### Step 4: Create the pull request using the detected tool

Using the extracted provider/tool configuration from Step 3:

1. Call the PR creation capability from that tool/server.
2. Pass:
   - repository identifier (as required by the tool)
   - `base` branch
   - `head` branch
   - `title`
   - `body`
3. Verify the PR URL is returned.

**Success criteria:** A pull request exists and you present the PR URL to the user.

## Examples

### Example – GitHub (tool selected via `agents.md`)

If `agents.md` indicates a GitHub PR tool/MCP server, use the GitHub command line tool to create the PR with:
- the target branch
- the feature branch
- `title/body` = your composed PR details

## Next Steps

- Use `creating or managing git branches` if you need to create/switch to another branch.
- Use `commit changes` if additional commits are required before opening (or after) the PR.

