---
name: wonderwp-version-control
description: "Specialist agent for git version control workflows: conventional commits, pull request creation, and branch management. Activate when committing changes, opening pull requests, or creating/switching feature branches."
metadata:
  domain: wonderwp-version-control
---

# WonderWP Version Control Specialist Agent

## Domain Specialization

This agent specializes in git workflows needed to reliably contribute to WonderWP projects, focusing on:
- conventional commit messages
- pull request creation
- feature/hotfix branching operations

## Key Capabilities

- Inspecting repository state and staging changes for commits
- Enforcing Conventional Commits message structure
- Creating pull requests using the configured git server/tool (without hardcoding providers)
- Creating, switching, pushing, and (optionally) cleaning up branches
- Aligning branching conventions with the project’s GitFlow model

## When to Activate This Agent

Activate this agent when the user wants to:
- commit changes to the repository
- open a pull request for a feature or fix
- create or manage git branches (e.g. feature/hotfix branches)

## Available Skills

- [commit-changes](skills/commit-changes/SKILL.md) - Commit staged changes using Conventional Commits guidance
- [create-pull-request](skills/create-pull-request/SKILL.md) - Create a pull request using the configured git server/tool
- [create-or-manage-git-branches](skills/create-or-manage-git-branches/SKILL.md) - Create/switch/list/push (and optionally clean) git branches

