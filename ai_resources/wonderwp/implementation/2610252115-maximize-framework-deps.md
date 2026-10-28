# Implementation report — maximize-framework-deps

## Outcome
Executed under PHP `^8.3` ceilings. Opened **33** wonderwp PRs + **6** consumer plugin PRs. No merges.

## Acceptance
| Criterion | Status |
|-----------|--------|
| php ^8.3 on wonderwp/* | pass |
| Symfony ^7.4 (asset, http-foundation) | pass (constraints) |
| PHPUnit ^12 + Asset tests | pass locally (78 OK) |
| Respect ^2.5 | pass (constraint) |
| annotations → attributes | pass (api + 6 plugins) |
| wordpress-dev ^7.0 / pimple ^3.5 | pass |
| composer update + crawl wonderwp-base | **pending** (à faire après merge des packages) |

## Notable API breaks
- `wonderwp/api`: Doctrine annotations removed; use `#[WPApiNamespace]` / `#[WPApiEndpoint]`.
- Symfony 8 / Respect 3 / PHPUnit 13 not done (PHP floor).

## PR index
See chat summary / gh search `chore/deps-max`.
