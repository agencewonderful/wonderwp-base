# WonderWP agent orchestration map

last_updated: 2026-11-04T10:05:23+01:00

## discovery_sources

- primary: `.cursor/agents/*.md` (11 agents)
- agents.md: **missing** at consumer project root (`wonderwp-base`); tracker/git conventions unavailable until bootstrapped — GitHub via `gh` used for this run from issue URL
- skills root: `.cursor/skills/` (wwp_* prefixes)

## Agents

### wonderwp
- path: `.cursor/agents/wonderwp.md`
- summary: Orchestrator — team discovery, orchestration plans, run JSON, SDLC coordination
- Available Skills: sdlc, plan-implementation, implement, learn (downstream)

### wonderwp-product-manager
- path: `.cursor/agents/wonderwp-product-manager.md`
- summary: Epics, issues, backlog workshops, clarify, tracker sync
- Available Skills: clarifying-requests (clarify), creating-an-epic, read-issue, creating-an-issue, planning-backlog-from-conversation-transcript, co-constructing-backlog, building-backlog-plan-from-draft, creating-epics-and-issues-from-backlog-plan

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

### wonderwp-maintenance
- path: `.cursor/agents/wonderwp-maintenance.md`
- summary: Preventive maintenance sessions, catalogue/ledger, smoke, client report
- Available Skills: conducting-preventive-maintenance

### wonderwp-backend
- path: `.cursor/agents/wonderwp-backend.md`
- summary: CPT, taxonomies, hooks, plugins, WP-CLI, importers (application layer)
- Available Skills: creating-a-new-plugin, creating-a-new-custom-posttype, creating-a-new-taxonomy, creating-a-new-hook, creating-a-domain-service, creating-custom-fields, creating-a-liaison-custom-field-to-link-two-post-types, creating-a-post-repository, creating-a-wp-cli-command, creating-a-new-wp-cli-console-command, refactoring-custom-post-type-to-new-architecture, scaffolding-an-importer-architecture, setting-up-a-plugin-theme-override

### wonderwp-doctrine
- path: `.cursor/agents/wonderwp-doctrine.md`
- summary: Doctrine entities, forms, list tables, admin controllers
- Available Skills: wiring-doctrine-manager-and-services, creating-a-doctrine-entity, creating-a-doctrine-form, creating-a-doctrine-repository, creating-a-doctrine-listtable, creating-a-doctrine-admin-controller

### wonderwp-block-editor
- path: `.cursor/agents/wonderwp-block-editor.md`
- summary: Gutenberg blocks, bindings, variations, compilation
- Available Skills: creating-a-new-block, creating-a-new-block-type, creating-a-new-block-variation, creating-a-block-style-variation, creating-a-new-block-extension, creating-a-block-category, creating-an-icon-collection, creating-a-new-block-binding-source, listing-block-bindings-sources, creating-a-new-query-loop-post-card-block, creating-a-query-loop-filtering-hook, creating-a-new-inner-block-parent-wrapper-block, setting-up-block-compilation

### wonderwp-theme
- path: `.cursor/agents/wonderwp-theme.md`
- summary: Theme-level block stylesheets
- Available Skills: adding-a-theme-stylesheet-to-an-existing-block

### wonderwp-design-system
- path: `.cursor/agents/wonderwp-design-system.md`
- summary: DTCG tokens, atoms/molecules, styleguide, theme.json load
- Available Skills: bootstrap-design-system, create-token, create-atom, create-molecule, declare-variant, declare-block-style, generate-tokens-css, validate-contract, validate-tokens, validate-mapping, generate-styleguide, remap-contract, load-contract, load-theme-json, load-wonderwp, build-design-system, capture-figma-screenshots, audit-render-fidelity
