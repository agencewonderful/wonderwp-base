# HTML report structure

## Location

`ai_resources/perf/<YYMMDDHHmm>-profiling-<topic>/`

- `index.html` — standalone interactive report (data inlined)
- `README.md` — executive summary + reproducible protocol

## Required tabs

| Tab (suggested FR label) | Content |
| --- | --- |
| Synthèse | Bare timings per scenario, top offenders, caveats (Xdebug inflation, local artefacts) |
| Séquence | Timeline of hooks/callbacks with ms, SQL delta, package |
| Packages | Inclusive/self time by package; compare scenarios |
| Fonctions | Sortable function table; drill-down callers/callees |
| SQL | Normalized query shapes, counts, slow samples |
| Remédiation | Finding sheets F1…Fn |
| Protocole | How to re-run capture/analysis |

## Finding sheet schema

Each finding should expose:

- **Id** — `F1`, `F2`, …
- **Priority** — P1 (blocking prod) … P4 (nice-to-have)
- **Title** — short symptom
- **Evidence** — scenario ids, ms, query counts, code pointers
- **Root cause** — hypothesis grounded in traces
- **Fix layer** — framework / `wwp-*` / project
- **Proposed change** — one paragraph, no full patch
- **Acceptance** — measurable (e.g. flush_urls ≤ 13, 0 warmup HTTP on save)
- **Risks / deferral** — if out of scope, say so explicitly

## README protocol section

Must document:

1. Xdebug ini + trigger cookie
2. Tracer mu-plugin behaviour + cookies
3. Auth / REST replay method
4. Scenario list
5. Analysis commands
6. Cleanup checklist
7. Where raw artefacts live (outside git)

## Generation tips

- Prefer embedding JSON in the HTML so the report opens via `file://`.
- Keep chart/table JS inline; avoid CDNs.
- Map scenario ids identically across capture, analysis, and report configs.
