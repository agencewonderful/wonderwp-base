# ENTRY phase — what audit-render-fidelity checks

Every check below maps to a rule in `rules/source-fidelity.md`. This file is a
reviewer's checklist of how the script applies those rules. The canonical rule
text lives in `rules/source-fidelity.md` (R-FIDELITY-1..5) — do not redefine
them here.

The source URL is read from `com.wonderwp.source.url` in the contract's
`$extensions`. When it is a `figma.com` URL, the Figma MCP `get_screenshot`
tool is used to fetch the reference image. When it is absent the ENTRY phase
degrades gracefully: value-traceability checks still run (R-FIDELITY-3 token
snapping, R-FIDELITY-4 state completeness, R-FIDELITY-5 structural mirror) but
pixel-level comparisons are skipped and a note is appended to the report.

---

## R-FIDELITY-1 — Measure, do not estimate

**What the script checks:**

- Every `$value` in `aesthetic.base` that is a token ref (`{path}`) resolves to
  a concrete value via `tokens.json`. If the resolved value ends in a
  non-standard fraction (e.g. `13.3px`, `1.667rem`) it is flagged as a possible
  estimation candidate — surfaced as a `⚠️ warning` for human review.
- When the source URL is a Figma node: the script uses `get_screenshot` to
  capture the reference image and records the source-vs-contract value pairs for
  every property where an exact numeric measurement is present in the contract.
  Values that differ by more than a configurable tolerance (default: 1px / 1%)
  from what is visible in the source are flagged `❌ error`.
- When the source URL is absent or non-Figma: emit a `⚠️ warning`
  "Source URL not present — pixel-level R-FIDELITY-1 check skipped."

**Correction location:** `contract` — re-read the value off the design source.

---

## R-FIDELITY-2 — Walk every property of every part

**What the script checks:**

- Every part declared in `aesthetic.base` has at least one property entry. A
  part with an empty object (`{}`) is allowed only when it is a pure structural
  wrapper (no styling on the source) — flagged `⚠️ warning` so the author can
  confirm it is intentional, not a skipped property.
- The semantic tree has an Element for each non-empty `aesthetic.base` key (same
  check as `validate-contract`'s reverse-jointure, but here it is surfaced as a
  fidelity finding with the `rules/source-fidelity.md` reference, not just a
  schema finding).

**Correction location:** `contract` — add the missing property, or confirm the
empty part is intentional.

---

## R-FIDELITY-3 — Value → token without silent snapping

**What the script checks:**

- For every token ref `{path}` in `aesthetic.base` and variant overrides: resolve
  the token's `$value` from `tokens.json` and compare it to what the contract
  author annotated in any `$comment` field (if present). Mismatches between the
  comment's "measured value" and the token's resolved value flag `❌ error`.
- Detect silent snapping: for each resolved token value, scan `tokens.json` for
  tokens whose value is within ±5% of the resolved value but whose path differs.
  If the contract binds to the near token rather than a closer (or exact) one,
  flag `⚠️ warning` "Possible silent snap: `{path}` resolves to `<val>`, a
  closer token `{other.path}` (`<other-val>`) exists."
- Detect hardcoded literals (non-ref `$value` in `aesthetic.base`): flag `❌
  error` "Hardcoded value `<val>` — token-first rule violated (R-FIDELITY-3)."

**Correction location:** `contract` (if the wrong token was chosen) or `token`
(if a new token should have been created for the exact measured value).

---

## R-FIDELITY-4 — Capture every state the source shows, and only those

**What the script checks:**

- Lists the variant axis/value folders under
  `{ds}/components/<layer>/<slug>/` and cross-references them against the
  declared `defaultVariant` axes.
- Interaction variants (`interaction`, `hover`, `pressed`, `focus`, `disabled`,
  `active`) present in `aesthetic.variants` (inline) but absent as axis folders
  are flagged `⚠️ warning` "Inline interaction variant — migrate to
  `declare-variant`."
- No automated "missing state" check is possible without the source — this rule
  is a manual review prompt: the report always includes a reminder:
  > "Confirm: every state visible in the source has a declared variant, and no
  > variant exists for a state the source does not show. (R-FIDELITY-4)"

**Correction location:** `contract` (add missing variant) or a call to
`declare-variant` for each missing axis/value.

---

## R-FIDELITY-5 — Semantic tree mirrors source structure

**What the script checks:**

- Walks the `semantic` tree and counts nesting depth vs. the number of declared
  parts. A very shallow tree with many parts may indicate flattened wrappers —
  flagged `⚠️ warning` for review.
- Detects `Slot` elements in atoms (atoms must not have Slot children) — `❌
  error`.
- Detects `semantic: null` — `⚠️ warning` "Semantic section is null — structural
  fidelity cannot be assessed (R-FIDELITY-5)."
- When the source URL is a Figma node: prompts a manual overlay note: "Compare
  the semantic tree nesting to the Figma node hierarchy at `<url>`."

**Correction location:** `contract` — rebuild the semantic tree to mirror the
source structure.

---

## Summary table

| Rule | Automated | Severity when flagged | Correction location |
|---|---|---|---|
| R-FIDELITY-1 (measure) | Partial (Figma URL needed) | `❌` / `⚠️` | contract |
| R-FIDELITY-2 (all properties) | Yes | `⚠️` | contract |
| R-FIDELITY-3 (no silent snapping) | Yes | `❌` / `⚠️` | contract / token |
| R-FIDELITY-4 (states) | Partial (manual reminder) | `⚠️` | contract / declare-variant |
| R-FIDELITY-5 (semantic structure) | Partial (Figma URL helps) | `❌` / `⚠️` | contract |
