# CI credentials (site access context)

## Where to look

Credential files for WonderWP sites are **usually under the project `CI/` folder**, for example:

- `CI/{slug}_preprod_credentials.json`
- `CI/{slug}_prod_credentials.json`
- sometimes `CI/credentials.template.json` (template only — no secrets)

These files are often gitignored when filled; the operator may still have them locally.

## What they contain

Use them (read-only) to learn site context for maintenance — **do not commit secrets** or paste passwords into ledger/session reports:

- **Hébergement** — host / provider clues (Kinsta, OVEA, SSH host patterns, `wpCliCommand`, etc.)
- **URLs** — `siteUrl` (and related) for preprod / prod
- **Accès** — SSH user, remote path, ports, deploy targets needed for proxies upload and remote checks

## How the maintenance skill uses them

1. At session start, scan `CI/*credentials*.json` (and ops skill docs) **before** asking the operator for hosting if the answer is already there.
2. Map provider → PHP policy (`kinsta` / `ovea` / `unknown`) and set `ledger.json` → `hosting` when confident.
3. Reuse `siteUrl` for smoke / critical-paths hints when the project has no `critical-paths.json` yet.
4. Never print full credential JSON into chat logs or the client PDF.

See also ops skill **setting-up-jenkins-ci-pipeline** → `references/credential-files.md`.
