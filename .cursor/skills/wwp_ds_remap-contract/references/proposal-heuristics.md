# Proposing a remap mapping for one component

How to turn a source `contract.json` + a target vocabulary into a proposed
`contract.<vocabulary>.remap.json` — co-located with the contract it overrides —
**without inventing** anything the user has not agreed to.

## Procedure

1. **Inventory the source.** From the component's `contract.json`:
   - **semantic** — every typed Element `nodeName` (skip reserved `Slot`,
     `Fragment`, `#text`, `#document-fragment`) and, per nodeName, every
     non-reserved attribute (everything except `part`, `atom`, `props`).
   - **variations** — the variant folders `{axis}/{value}/contract.json`: per
     axis its group, values, default and current selector.
   - **aesthetic** (only if remapping tokens) — the token refs (`{path}`) and
     `com.wonderwp.cssVar` names actually used.

2. **Decide one strategy per variation axis** — `block-attributes` /
   `block-styles` / `css-selector` — following
   [variation-strategies.md](../../remap-contract/references/variation-strategies.md)
   (pre-fill the default per axis group; each row shows the induced target
   selector). Record the confirmed decisions under the mapping's `variations`
   key (declarative in v1 — validated and cross-checked, never executed:
   `R-REMAP-8`); carry the reusable ones forward in the session.

3. **Map each source nodeName → target nodeName.** Look it up in
   [wordpress-core-vocabulary.md](wordpress-core-vocabulary.md) (or the active
   vocabulary cheatsheet / pre-built block index). If absent → write the entry as
   `NEEDS DECISION` and ask. Never guess a block name.

4. **Decide each non-reserved attribute.** For every source attribute choose
   exactly one of `keep` / `rename` / `drop` / `values` / `omitWhen`. If you
   cannot justify the choice from the cheatsheet or a convention already
   confirmed in this session → ask.

5. **Add target-required attributes.** Use `defaults` (added if absent) or
   `constant` (always set) for attributes the target block needs but the source
   lacks — e.g. `core/heading` needs `level`. Only add what the cheatsheet
   declares as required; otherwise ask.

6. **Leave plumbing alone.** `part`, `atom`, `props` carry over automatically —
   never list them. `Slot`s are preserved (the seam); do not rename them here.

7. **Reuse confirmed conventions.** Pre-fill from the conventions confirmed
   earlier in the session — and from the remaps already written for sibling
   components, which are the durable record — so repeated
   patterns (e.g. "drop `type` on buttons") need only a `yes`.

8. **Dry-run mentally against exhaustiveness (`R-REMAP-3`).** Every source
   nodeName and non-reserved attribute must be acknowledged, or the engine
   rejects the mapping. Treat a rejection as a checklist of what you missed.

## No silent invention — always ask when

- The target nodeName for a source type is not in the cheatsheet / index.
- A target attribute name or its accepted values are unknown.
- The source has an attribute with no obvious target home (rename vs drop).
- A literal value would have to be translated (enum) and the table is incomplete.

Write the unresolved spot as `NEEDS DECISION: <what is missing>` in the proposal
and surface it; do not fabricate a plausible value.

## The open questions (carry them, don't pre-decide)

These were explicitly left open in design discussion — record the user's answer in
the session's conventions the first time it comes up, then reuse it.

1. **Variant axis — semantic or aesthetic?** *Superseded by the per-axis
   strategy review* (procedure step 2): `block-attributes` puts the axis on the
   semantic side (a target-block attribute, e.g. a heading's `level`);
   `block-styles` and `css-selector` keep it aesthetic (a selector). The
   decision is per axis, confirmed by the user, recorded in the mapping's
   `variations` key.

2. **WordPress comment delimiters.** Block markup uses `<!-- wp:x -->` delimiters.
   In the meeting these were considered "nodes à part entière", but the
   `contract-schema` does **not** use Comment nodes (`nodeType 8`). Default: keep
   the semantic tree clean and let the **Load** `to-wordpress-block` mapping emit
   the delimiters. Representing them inside the contract would require a schema
   extension — flag it, do not add stray nodes.

3. **Slot identity.** The engine keeps a `Slot`'s `atom` slug **stable** and changes
   identity inside the **child's** remapped contract (recursion = one output per
   child). The alternative — literally rewriting the slot to the block name in the
   parent — would break the parent's `part` jointure and is not done in v1. If the
   user wants the literal rewrite, flag it as a future engine option; do not inline.

## Output

A complete remap per [rules/remap-format.md](../../../rules/remap-format.md), with
`NEEDS DECISION` markers for anything still open. Confirm with the user before
writing it — co-located with the contract it overrides, at
`{ds}/components/<layer>s/<slug>/contract.<vocabulary>.remap.json` (a deviating
variant gets its own in its `<axis>/<value>/` folder).
