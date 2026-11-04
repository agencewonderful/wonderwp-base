# Client role migration / capability alignment

Catalogue concern: `recurring.bo.client-role-migration` (`skill_step`: `client-role-migration`).

## When

Run during the **backoffice** phase **only if** the WordPress role `client` exists (`wp role list` / `get_role('client')`). Otherwise mark the runbook line `[-]` and skip.

## Checklist

1. **Role present after core update** — After Composer update of `wonderwp/plugin` (or mu-plugin sync), confirm `client` exists. Hit wp-admin once (or any `admin_init`) so `WwpRoleService::ensureClientRole()` syncs baseline caps.
2. **Account migration** — Where customers still hold `administrator` (or overly broad roles) solely for BO access, migrate them to `client` when appropriate. Wonderful keeps true admin accounts.
3. **Capability alignment** — Compare site `client` caps to the current core baseline (Editor clone + user-management caps). Re-apply site-specific extras via `wwp.roles.client.capabilities` or a documented `add_cap` list — do not leave one-off prod-only caps undocumented.
4. **Discovery backlog** — If a test Client user still lacks a needed screen/cap, record it for a follow-up `wonderwp-plugin` release (do not grant `administrator` as a workaround).

## Commands (examples)

```bash
ddev wp role list
ddev wp user list --role=client --fields=ID,user_login,roles
# migrate one user (example):
# ddev wp user set-role <login> client
```

## Related

- Core implementation: `wonderwp-plugin` → `WwpRoleService`
- Filter: `wwp.roles.client.capabilities`
