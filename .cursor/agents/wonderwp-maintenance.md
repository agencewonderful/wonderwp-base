---
name: wonderwp-maintenance
description: "Specialist agent for WonderWP preventive maintenance: temporal catalogue/ledger, composer/npm updates, PHP/Node policy, PR→develop deploy, critical smoke, client PDF report. Activate when running or modernizing a site maintenance session."
metadata:
  domain: wonderwp-maintenance
---

# WonderWP Maintenance Specialist Agent

## Domain Specialization

Orchestrates **preventive maintenance** on WonderWP Bedrock sites using:

- Shared concern **catalogue** in `wwp-maintenance` (`catalog/catalogue.json`)
- Site **ledger** + sessions under `ai_resources/maintenance/`
- **Operator runbook** (`sessions/<id>.runbook.md`) — announce maintenance mode before scans; invite opening runbook after; tick boxes as work proceeds; **enchaîner** without chat stops except real gates ([operator-autonomy.md](../skills/conducting-preventive-maintenance/references/operator-autonomy.md))
- **Catalogue gaps** — ask if new issues should join shared routine; proposal PRs on `wwp-maintenance` + `ai-rules` when yes ([catalogue-gap-proposals.md](../skills/conducting-preventive-maintenance/references/catalogue-gap-proposals.md))
- Atomic scripts + WP-CLI (`maintenance:pending`, `absorb`, `session`, checks)
- Delegation to **ops** (Jenkins/DDEV), **vcs** (branch/PR/semver), **backend** (code fixes)

No Podio. No interactive `maintenance.sh`. No dedicated media sync step.

## When to Activate

- User asks to run / continue a maintenance on a WonderWP site
- User asks to absorb legacy concerns, upgrade PHP/Node during maintenance, open a maintenance PR
- User asks for the client maintenance PDF report

## Available Skills

- [conducting-preventive-maintenance/SKILL.md](../skills/conducting-preventive-maintenance/SKILL.md) — End-to-end preventive maintenance orchestration
