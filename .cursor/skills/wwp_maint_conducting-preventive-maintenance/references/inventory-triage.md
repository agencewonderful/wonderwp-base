# Inventory triage (source decisions)

Status vocabulary used when modernizing the old `maintenance.sh` checklist:

- **garder** → recurring concern or transformed recurring step
- **supprimer** → `retired` in catalogue
- **ledger** → `one_shot` (absorb once)
- **transformer** → keep intent, change mechanics (PR flow, ops templates, semver)
- **nouveau** → new recurring/milestone concerns

Key removals: Podio (agency-wide), entire media step, IE, SecuPress logs review, social gateway classname rename, password-to-Podio, `maintenance_YYMMDD` tag.

Key additions: `.wonderful` submodule sync, root `agents.md` one-shot (`initializing-agents-md`), PHP 8.5 / Node policies, Jenkins wave 2026-10-28, critical smoke, client PDF FR, PR→develop, semver minor, conditional WP `client` role.
