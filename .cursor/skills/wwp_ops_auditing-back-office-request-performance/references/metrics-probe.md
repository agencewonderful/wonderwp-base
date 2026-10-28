# Metrics probe (staging / production)

## Intent

Measure the **real** cost of the request under study on hosting (where local artefacts differ — e.g. Kinsta purge actually hits the network).

## Design rules

1. **Versioned** in the consumer repo (Jenkins deploys it).
2. **Auto-expire** via `EXPIRES_AT` constant (deploy + ~48h) — early `return` when expired.
3. **Narrow activation** — only REST writes to `wp/v2/*` (POST/PUT/PATCH); never CLI.
4. **No DB writes** — log lines only.
5. **Dual-write logs** — `error_log` **and** a file under uploads (`WP_CONTENT_DIR/uploads/<name>.log`). Kinsta SSH users often cannot read PHP `error_log`.
6. **Wrap the hot action** with bookend priorities (`-PHP_INT_MAX` / `PHP_INT_MAX`) to count payloads and duration.
7. **Delete on chantier close** — expiry is a safety net, not the cleanup plan.

## Suggested line format

```text
[<prefix>] total_ms=… flush_calls=… flush_urls=… flush_ms=… post_id=… post_type=… gateway=… uri=…
```

Always include `uri=` so a revision `post_id` can still be mapped to `/wp/v2/pages/{id}`.

## Pitfalls

- Last `save_post` may be a **revision** — do not treat `post_id` alone as the edited entity.
- After factory refactors, resolve gateway via the **current** API (`getActiveGateway()->getName()`), catch `Throwable`, fall back to `unknown`.
- Baseline must be captured **before** remediation packages are deployed; after numbers need the same posts.
- Proxy 500 after ~50s can be edge timeout even if PHP finishes — still log `total_ms` when possible.

## Lifecycle

1. Land probe + deploy → capture baseline saves.
2. Merge remediations + composer update + deploy.
3. Re-save same witnesses → compare.
4. Remove probe file from the repo → deploy once more.
5. Optionally delete the uploads log after archiving numbers into `ai_resources/perf/…-after/README.md`.

See also template: [../assets/save-probe.php.template](../assets/save-probe.php.template).
