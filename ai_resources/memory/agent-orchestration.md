# WonderWP agent orchestration map

last_updated: 2026-10-24T14:59:22+02:00

## discovery_sources

- primary: `.cursor/agents/*.md` (10 agents)
- agents.md: **missing** at consumer project root (`wonderwp-base`); tracker/git conventions unavailable until bootstrapped
- skills root: `.cursor/skills/` (wwp_* prefixes)

## Agents

### wonderwp
- path: `.cursor/agents/wonderwp.md`
- summary: Orchestrator — team discovery, orchestration plans, run JSON, SDLC coordination
- Available Skills: sdlc, plan-implementation, implement, learn (downstream)

### wonderwp-product-manager
- path: `.cursor/agents/wonderwp-product-manager.md`
- summary: Epics, issues, backlog workshops, clarify, tracker sync
- Available Skills: clarify, creating-an-epic, read-issue, creating-an-issue, planning-backlog-from-conversation-transcript, co-constructing-backlog, building-backlog-plan-from-draft, creating-epics-and-issues-from-backlog-plan

### wonderwp-github
- path: `.cursor/agents/wonderwp-github.md`
- summary: GitHub org/repos, PRs, issues, Projects (hosted)
- Available Skills: working-with-github, syncing-backlog-plan-with-github, creating-a-new-github-project

### wonderwp-version-control
- path: `.cursor/agents/wonderwp-version-control.md`
- summary: Local git branches, conventional commits, PR creation via git tooling
- Available Skills: commit-changes, create-pull-request, create-or-manage-git-branches

### wonderwp-ops
- path: `.cursor/agents/wonderwp-ops.md`
- summary: DDEV, Jenkins, Composer in containers, Bedrock env, perf audits
- Available Skills: setting-up-ddev-on-existing-wonderwp-project, setting-up-jenkins-ci-pipeline, installing-kinsta-cache, enable-auto-update, auditing-back-office-request-performance, exporting-wwp-translator-delta-csv

### wonderwp-backend
- path: `.cursor/agents/wonderwp-backend.md`
- summary: CPT, taxonomies, hooks, plugins, WP-CLI, importers (application layer)
- Available Skills: creating-a-new-plugin, creating-a-new-custom-posttype, creating-a-new-taxonomy, creating-a-new-hook, creating-a-domain-service, creating-custom-fields, creating-a-liaison-custom-field-to-link-two-post-types, creating-a-post-repository, creating-a-wp-cli-command, creating-a-new-wp-cli-console-command, refactoring-custom-post-type-to-new-architecture, scaffolding-an-importer-architecture, setting-up-a-plugin-theme-override

### wonderwp-doctrine
- path: `.cursor/agents/wonderwp-doctrine.md`
- summary: Doctrine entities, forms, list tables, admin controllers
- Available Skills: (doctrine scaffolding skills)

### wonderwp-block-editor
- path: `.cursor/agents/wonderwp-block-editor.md`
- summary: Gutenberg blocks, bindings, variations, compilation
- Available Skills: (block skills)

### wonderwp-theme
- path: `.cursor/agents/wonderwp-theme.md`
- summary: Theme-level block stylesheets
- Available Skills: adding-a-theme-stylesheet-to-an-existing-block

### wonderwp-design-system
- path: `.cursor/agents/wonderwp-design-system.md`
- summary: DTCG tokens, atoms/molecules, styleguide, theme.json load
- Available Skills: (ds skills)

## Notes for deps-max run

- Multi-repo framework work lives outside wonderwp-base clones: `/Users/jeremy/Sites/WonderWp/dependabot-fixes` (Asset, Documentation, wonderwp) and Notion dossier `/Users/jeremy/Sites/WonderWp/Framework`.
- No dedicated “composer deps bump” skill; inventory/implement use ops + github + backend ad-hoc under factory plan/implement skills.
