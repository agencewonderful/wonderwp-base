# Wonderful org CI conventions

Fixed defaults for every WonderWP Jenkins pipeline. **Do not re-derive these per project** — copy from [assets/Jenkinsfile.template](../assets/Jenkinsfile.template) as-is unless the user explicitly requests a different channel or no Cypress.

## Slack notifications

All Wonderful Bedrock projects use the same Jenkins → Slack integration:

| Setting | Value |
| --- | --- |
| Channel | `#jenkins` |
| Team domain | `wdf-team` |
| Token | `ebmZ6kgsvWsgFVUY3UViZPOS` |

`notify()` in the Jenkinsfile template is pre-filled with these values (same as feocc, condichef, and other recent org pipelines).

## Cypress

### Jenkins integration stage (full suite)

Always use the **full Cypress command** in the Jenkinsfile:

```groovy
sh "cypress run --env host=$host"
```

`host` comes from `siteUrl` in the branch credential JSON (set during Deploy). This runs every spec under `cypress/integration/` that is not excluded by Cypress config.

Do **not** narrow the Jenkins command to `--spec` unless the user explicitly asks to change pipeline behaviour later.

### Initial project setup (smoke-first)

To ease onboarding, commit a minimal **`cypress.json`** at project root (excluded from rsync via `CI/exclude-file.txt`). Copy from [assets/cypress.json.template](../assets/cypress.json.template):

- `host` — local DDEV URL (`https://{slug}.ddev.site`) for local runs; Jenkins overrides via `--env host`
- `smoke-pages` — start with `["/?locale=fr_FR"]` so `cypress/integration/smoke_test.js` is meaningful on day one

The smoke spec reads `Cypress.config('smoke-pages')` and calls `cy.checkNoFatal()` on each page. Other specs in `cypress/integration/` can be added over time; Jenkins will pick them up automatically with `cypress run`.

### Disabling Cypress

If the project has no Cypress or the user opts out, remove the Integration tests stage or gate `runCypress` to `false` permanently.
