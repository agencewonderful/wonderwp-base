# Plugin conditional checks

## Recurring

- Ensure `google-sitemap-generator` active (composer require if missing).
- Ensure `secupress` installed and reasonably configured (gate human for full config).
- If `wwp-cron` present: activate and verify **full** `cron.inventory` per [verify-wwp-cron-inventory.md](./verify-wwp-cron-inventory.md) (Default WP Cron + `cron:prun-results --prune-storage-path` + other plugin rows; prove execution, not only crontab presence); if broken, prepare hosting support mail from plugin UI.

## Ledger one-shots (only if pending)

- Lock `wwp-contact` / `wwp-espace-restreint` to `^2.0` when a v2→v3 bump would break the site.
- Install `wwp-cron` when contact/RGPD present without cron.
- Map API key / `jsAssetGroup` retrocompat.
- Contact `FileField` allowed extensions.

## Conditional (framework readiness)

- WP role `client`: if the role exists, migrate client-looking administrator accounts to `client`. If the role does not exist yet, skip and note in the session.
