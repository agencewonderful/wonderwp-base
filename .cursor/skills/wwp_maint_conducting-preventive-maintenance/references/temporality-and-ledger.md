# Temporality and ledger

## Why

Without dates, every maintenance replayed years of one-shot migrations. The catalogue + ledger skip absorbed concerns.

## Paths

- Catalogue (shared): `web/app/plugins/wwp-maintenance/catalog/catalogue.json`
- Ledger (site): `ai_resources/maintenance/ledger.json`
- Sessions: `ai_resources/maintenance/sessions/<id>.json`
- Operator runbook: `ai_resources/maintenance/sessions/<id>.runbook.md` (human timeline — see [operator-runbook.md](./operator-runbook.md))

## Selection rules

| kind | Rule |
| ---- | ---- |
| `retired` | Never pending |
| `recurring` | Always pending |
| `one_shot` | Pending if id absent from `ledger.absorbed` |
| `milestone_triggered` | Pending if id absent from `ledger.absorbed` |

## Absorb

```bash
ddev wp maintenance:absorb <concernId> --session=<sessionId> [--notes="..."]
```

Commit the ledger after the session so the next maintenance inherits memory (survives DB pulls).
