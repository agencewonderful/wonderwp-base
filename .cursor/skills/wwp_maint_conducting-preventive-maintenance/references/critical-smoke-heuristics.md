# Critical smoke heuristics

Used on preprod and prod after deploy.

## Build the checklist

1. From the session / git diff, list upgraded Composer packages, npm packages, and touched theme/CI files.
2. Map packages → smoke items using the table below.
3. Merge with optional project file `ai_resources/maintenance/critical-paths.json`:

```json
{
  "urls": ["/", "/contact/", "/wp/wp-admin/"],
  "notes": "Business-critical paths for this site"
}
```

4. Execute (browser and/or targeted Cypress) and record pass/fail on the session.

## Heuristic map

| Signal | Smoke |
| ------ | ----- |
| Any maintenance | Homepage 200, WP admin login page loads |
| `wwp-contact` / contact forms | Submit or at least render contact page / form markup |
| `wwp-map` | Map container present, no JS console fatal |
| `wwp-cache` | Front cache rebuild / page still serves |
| `wwp-social` | Social block/query page if used |
| Theme / webpack assets | CSS/JS 200 on front; no obvious unstyled FOUC break |
| Jenkins / CI only | Skip functional smoke beyond homepage |
| Cypress `cypress.json` smoke pages | Prefer those URLs when present |

Always report results before asking for go-prod.
