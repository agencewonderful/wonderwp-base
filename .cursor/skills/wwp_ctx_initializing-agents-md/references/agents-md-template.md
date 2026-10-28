# Project agent configuration

This file gives AI assistants **project context** (what it is, how it is built, how it is structured) and **which tools** to use for git, pull requests, and issue tracking in **this** repository. Other skills read `./agents.md` from the **consumer project root** (the folder that contains `.cursor/` or `.wonderful/`), not from the `ai-rules` plugin repository unless you are configuring `ai-rules` itself.

Keep entries factual: descriptions aligned with the codebase, real stack versions where known, and accurate tooling (MCP server names, repo identifiers, lists the team actually uses).

---

## Project overview and description

<!-- Short summary: what the product/service is, who it is for, and the main problems it solves. -->

- **Purpose:**
- **Audience / users:**
- **Key capabilities:**

---

## Technical Stack

<!-- Languages, frameworks, runtime, major libraries, infra—as actually used in this repo. -->

- **Languages:**
- **Frameworks / platforms:**
- **Data stores:**
- **Build / test / deploy:** <!-- CI, package manager, hosting -->

---

## Architecture

<!-- How the system is broken down: apps, services, notable directories, boundaries, integration points. -->

- **High-level shape:** <!-- e.g. monolith, modular WordPress, SPA + API -->
- **Main modules / packages:**
- **External dependencies:** <!-- third-party APIs, payment, auth providers -->
- **Conventions:** <!-- e.g. Bedrock layout, DDD folders -->
- If it's a WordPress project under a Bedrock architecture, use the [working-with-a-bedrock-architecture](./working-with-a-bedrock-architecture.md) reference to fill this section.

---

## Git and pull requests

- **Provider:** <!-- e.g. GitHub, GitLab, self-hosted -->
- **PR creation:** <!-- e.g. MCP server id, CLI, or IDE integration -->
- **Notes:** <!-- base branch convention (e.g. develop), required reviewers, draft PR policy -->

---

## Issue-tracker configuration

- **Issue creation:** <!-- e.g. MCP server id, REST API, web UI only. If the issue tracker is GitHub, prefer the use of gh command line tool over MCP. -->
- **Default repository / scope:** <!-- org/repo or project id -->
- **Epic convention:** <!-- label, issue type, or parent link pattern -->
- **List of users associated with the project:** <!-- list of users associated with the project (if any). --> <!-- If the issue tracker is GitHub, use the gh command line tool to query the users from the issue tracker project-->
- **Allowed ticket categories:** <!-- canonical list; product-manager skills validate against this. -->
<!-- If the issue tracker is GitHub, use the gh command line tool to query the issue types from the issue tracker project -->

<!--
  Example categories (replace with your team’s list):

  - bug
  - feature
  - chore
  - docs
  - NEEDS CLARIFICATION
-->

---

## Optional: other MCP or local tools

- **MCP servers:** <!-- names as shown in the IDE, if any -->
- **Secrets / auth:** <!-- never paste tokens; note where agents should expect the user to authenticate -->
