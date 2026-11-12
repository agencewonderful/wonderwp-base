# Install custom 403 page (Kinsta / nginx origin)

Concern: `legacy.hosting.custom-403-page` (`skill_step`: `install-custom-403-page`).

## Goal

Publish Wonderful’s branded static **403 / page protégée** HTML on the site so Kinsta (or similar nginx hosts) can point `error_page 403` at it — replacing the default nginx 403.

## When

Phase **devops**, typically during maintenance on **Kinsta** staging/prod that IP-restricts access at the **nginx origin** layer. Triggered when pending in `maintenance:pending`, or when hosting is Kinsta and `web/errors/403.html` is missing.

**Skip / runbook `[-]`** when hosting is not nginx-origin customizable (document why). Do **not** absorb as done if the page is still needed later.

## Source → destination

| Role | Path |
| ---- | ---- |
| Source (plugin, **not** deployed) | `web/app/plugins/wwp-maintenance/assets/error-pages/403-protected.html` |
| Destination (site, **deployed**) | `web/errors/403.html` → public URI `/errors/403.html` |

`wwp-maintenance` is excluded from Jenkins rsync — never rely on the plugin path on the server.

## Do

1. Confirm hosting (prefer `CI/*_credentials.json` / runbook **Hébergement**). If not Kinsta / nginx-origin: mark runbook N/A and stop (do not absorb unless permanently out of scope for this site).
2. Ensure destination dir and copy the asset:

   ```bash
   mkdir -p web/errors
   cp web/app/plugins/wwp-maintenance/assets/error-pages/403-protected.html web/errors/403.html
   ```

3. Commit `web/errors/403.html` on the site maintenance / feature branch (file must ship with rsync).
4. After deploy (or before asking support, if the file will land with the next deploy), send the **support message** below (fill `{SITE_URL}`, `{ENV}`).
5. Absorb only when **both** are true: `web/errors/403.html` is present in the site repo **and** the support ticket was sent **or** nginx `error_page` was confirmed:

   ```bash
   ddev wp maintenance:absorb legacy.hosting.custom-403-page --session=…
   ```

6. After support confirms, smoke-test an **origin nginx** 403 (not only MyKinsta edge Block IP).

## Kinsta constraint

Custom `error_page` applies to **origin nginx** 403s (classic centered page + « nginx »). **Edge** MyKinsta blocks (Block IP, bot protection, geo) often keep the minimal top-left 403 and never hit the origin — ask support which layers are covered.

## Support message — Kinsta (English)

Kinsta WordPress hosting does **not** auto-detect a conventional filename/path (unlike some hosts, or Kinsta **Static Site** “Error file” settings). Dropping `403.html` anywhere under the docroot will **not** wire it automatically — Support must add an nginx `error_page` rule ([Configuration Changes](https://kinsta.com/docs/wordpress-hosting/site-management/configuration-changes/)).

Copy-paste to MyKinsta chat (EN), replace placeholders:

```text
Hello,

On the {ENV} environment ({SITE_URL}), we have deployed a Bedrock static error
page at URI /errors/403.html (file web/errors/403.html, nginx document root =
web/).

Please add an nginx rule along the lines of:
  error_page 403 /errors/403.html;
with:
  location = /errors/403.html { internal; }
(and allow all; if needed under site-wide htpasswd).

Could you please confirm:
(1) that the nginx root is indeed web/,
(2) which 403 sources this covers (origin nginx / htpasswd vs edge Block IP /
    bot protection / geo),
(3) staging and/or production as applicable.

Thank you
```

## Rules

- HTML must stay **self-contained** (inline CSS + base64 logo) — no external CSS/JS that could be excluded from deploy.
- Do not put the source under repo-root `static_templates/` (excluded from rsync).
- Do not treat PHP/plugin 403s (`wwp-security`, SecuPress) as solved by this page — separate surface.
- Catalogue + skill changes for this concern live in **upstream** `wwp-maintenance` / `ai-rules` PRs — not only on one site.
