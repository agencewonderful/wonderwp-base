# PHP and Node policy

## PHP

- **Floor:** 8.3 — refuse below.
- **Target:** 8.5.
- **Kinsta / OVEA:** enforce target (easy to raise).
- **Detect hosting first** from `CI/*_credentials.json` (SSH host, paths, `wpCliCommand`, provider clues) — see [ci-credentials.md](./ci-credentials.md). Then set `ledger.json` → `hosting`.
- **Unknown hosting:** only ask the operator if credentials / project docs do not reveal the provider; still ask for the PHP target version if needed.
- Run `ddev wp maintenance:checkPhpVersion --hosting=kinsta|ovea|unknown --format=json`.
- Embedded php.net-style calendar:
  - past **security** support → critical warning
  - past **active** support but still security → suggest upgrade
- When raising PHP: align `composer.json` (`require.php`), `.ddev/config.yaml`, Jenkins PHP bins, crons/readme.

## Node

- Goal: raise Node so frontend still compiles and the version is supported by the **ops Jenkins** skill Node list.
- If upgrade breaks the build irreconcilably → **stop** the Node bump; document in session.
- Sync: `package.json` engines, DDEV Node, Jenkins NVM path.

## Absorb milestones

After a successful PHP 8.5 (or agreed target) rollout on the site, absorb `milestone.php.target-8.5`.  
After a successful Node bump aligned with ops, absorb `milestone.node.upgrade-if-supported`.
