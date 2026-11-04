# PHPUnit in maintenance (runbook + accountability)

## Catalogue concern

`recurring.devops.phpunit-driver-wonderwp-base` — each maintenance:

1. Compare project `CI/run-phpunit-tests.sh` to the **wonderwp-base** reference (same repo path: `CI/run-phpunit-tests.sh` on [wonderwp-base](https://github.com/agencewonderful/wonderwp-base) or copy in plugin `catalog/assets/CI-run-phpunit-tests.sh.reference`).
2. **Upgrade** the project file when it lacks fail-fast behaviour (loop all `tests/suites/*`, skip `.gitkeep`, `exit 1` if any suite fails).
3. Run atom `06` (prefer DDEV); record per-suite results in the runbook.

Legacy drivers that always `exit 0` must be treated as **non-compliant** until updated.

## Runbook visibility

Section **§5 DevOps** must include **explicit** PHPUnit lines — not only « atome 06 OK »:

- [ ] Atome `06` lancé (`CI/run-phpunit-tests.sh` ou équivalent via DDEV)
- [ ] **Résultat par suite** (table below or inline list)
- [ ] Code de sortie global **0** (sinon `[!]` et détail des échecs)

Example table in session runbook:

| Suite | Résultat | Détail |
| ----- | -------- | ------ |
| wwp-contact | OK | 34 tests |
| wwp-cron | **FAIL** | 8 errors — `WP_Filesystem_Base` |
| … | | |

Also note **how** tests were run (`ddev` vs host) when it matters for the operator reproducing locally.

## Success criteria for the atom

- Driver script **exits non-zero** if any suite fails (`CI/run-phpunit-tests.sh` must propagate failures).
- Agent marks `[x]` only when **full output** reviewed or driver exit 0 after fix.
- **Never** mark OK from `tail` of the last suite only.

## Local vs DDEV

- Project PHP **≥8.3** in Composer → run suites **via DDEV** in atoms (`06-run-phpunit.sh`).
- If the operator runs `vendor/bin/phpunit` on **host PHP 8.2**, they hit `platform_check` — document in runbook: « repro locale : `ddev exec bash CI/run-phpunit-tests.sh` ».
- Fixing DDEV routing **does not** ignore failing tests inside DDEV — failures stay `[!]` until fixed or explicitly deferred with notes.

## When tests fail during maintenance

1. Record in runbook + session JSON (`maintenance:session record` with result `failed` / notes).
2. Attempt fix if in scope (bootstrap, mock, plugin test fix) per backend skill.
3. If out of scope: `[!]` on §5, note for PR — **do not** silently continue as if green.
