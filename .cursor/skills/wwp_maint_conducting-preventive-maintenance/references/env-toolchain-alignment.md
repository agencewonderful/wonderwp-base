# Environment toolchain alignment (autonomous fixes)

When the **project** declares a toolchain (Composer `require.php`, `package.json` engines, DDEV `php_version` / `nodejs_version`) but **scripts or the host** still use an older PHP/Node, **align without asking** the operator — unless changing DDEV/Jenkins affects shared team policy and fails irreconcilably (then note in runbook + session).

## Detect

| Signal | Typical fix |
| ------ | ----------- |
| Atom / CI script runs `vendor/bin/phpunit` or `composer` on **host** while `ddev describe` works | Route through **DDEV** (`ddev exec`, `ddev composer`, `ddev wp`) — same pattern as `02-backend-update.sh` |
| Host `php -v` &lt; Composer floor (e.g. 8.2 vs `>=8.3`) | Do not run PHP tooling on host; use DDEV or bump local PHP (prefer DDEV on WonderWP) |
| Host `node -v` ≠ DDEV / `package.json` engines | Prefer **DDEV Node** for `npm` in atoms when DDEV is the project standard; align `.ddev/config.yaml` `nodejs_version` with engines when maintenance already touches DDEV |
| `06-run-phpunit.sh` prefers `CI/run-phpunit-tests.sh` on host | Plugin script should run that CI driver **inside** DDEV when DDEV is up |

## Do (maintenance session)

1. Fix the **atom script** in `wwp-maintenance/scripts/` or the **project** `CI/*.sh` wrapper so the next maintenance run is correct.
2. Re-run the failed atom; tick the runbook **only if tests actually pass** (exit 0, all suites — see [phpunit-runbook.md](./phpunit-runbook.md)).
3. If the fix belongs in **shared** maintenance (all sites), plan a **catalogue-gap** PR on `wwp-maintenance` (+ ai-rules if skill text changes) — do not block the site session on upstream merge.

## Do not

- Stop and ask « votre PHP local est 8.2, que faire ? » when DDEV is already configured for 8.3.
- Leave phpunit/composer failing on host while DDEV is running and unused.

## Related

- [env-database-sync.md](./env-database-sync.md)
- [operator-autonomy.md](./operator-autonomy.md)
- [php-node-policy.md](./php-node-policy.md)
