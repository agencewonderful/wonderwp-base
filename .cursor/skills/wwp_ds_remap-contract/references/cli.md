# remap-contract CLI

Two commands, run from the project root: `scripts/semantic-tree.mjs` answers
*can I remap this one yet*, `scripts/remap-contract.mjs` does the remapping. On
any exhaustiveness violation the engine writes nothing and lists every failing
path (R-REMAP-6).

## Before you remap: the semantic tree

How the component is composed, and what its remap is still waiting on
(`R-REMAP-9`):

```bash
node plugins/wonderwp-design-system/scripts/semantic-tree.mjs \
  --contract {ds}/components/molecules/icons-and-text-grid-2 \
  --vocabulary wordpress-core
```

```text
Layout[root]
└─ ✅ Slot[grid-slot] → columns [semantic]   props={"style": "grided"}
   ├─ ✅ Slot[column-slot-1] → column [semantic+aesthetic]
   │  └─ ❌ Slot[item-slot-1] → icon-and-text
   │     ╎ from icon-and-text/contract.json
   │     ├─ ❌ Slot[icon-slot] → icon-wrapper
   │     │  ╎ from icon-wrapper/contract.json
   │     │  └─ ✅ Slot[glyph-slot] → brand-icon [semantic]
   │     └─ ✅ Slot[text-slot] → text [aesthetic]
   └─ ✅ Slot[column-slot-2] → column [semantic+aesthetic]
      └─ ❌ Slot[item-slot-2] → icon-and-text ⤴

Verdict: BLOCKED — 2 dependencies still pending. Remap them first, then come
back to icons-and-text-grid-2.

Work order (dependencies first):
   1. ❌ icon-wrapper           molecule
   2. ❌ icon-and-text          molecule
   3. ❌ icons-and-text-grid-2  molecule  ← the component you asked for
```

Reading it:

| Notation | Meaning |
|---|---|
| `NodeName[part]` | a typed Element of this contract |
| `Slot[part] → slug` | an insertion point and the component it slots |
| `[semantic]` / `[aesthetic]` / `[semantic+aesthetic]` | the axes that child's remap declares (`remaps` in the mapping) |
| `props={…}` | what the parent pins on that child (truncated past 56 chars) |
| `╎ from <slug>/contract.json` | the tree leaves this contract and continues in the child's own — **Slots only** from there |
| `⤴` | that component was already expanded above |
| `⚠ inlined component` | a typed Element whose nodeName IS a component — an atom written inline instead of slotted (R-CONTRACT-9) |
| `✅` / `❌` | the component **and its whole closure** are remapped / something in there is not |

The icons carry **two states, not three**, because that is what `--recurse`
enforces: a component whose own remap exists but whose child is pending is `❌`,
since the recursive run refuses it just the same. The work order under the tree
is where "its own remap exists" shows up.

A `✅` sitting above a `❌` is not a contradiction — each icon scores **its own**
component's closure. `columns` above is remapped, and so is everything `columns`
declares; `icon-and-text` hangs under it because the *parent* pins it there, so
it counts against the parent's verdict, not against `columns`.

**An inlined component is reported, not counted.** A node like `Link[root]`
looks like plain structure, but its nodeName is a component: the atom it
duplicates may carry a remap of its own that will never apply to it, since the
node is not a Slot and the component is not in `childComponents`. It does not
move the verdict — `--recurse` walks declared dependencies and never reaches it,
so counting it would make the verdict disagree with the command it predicts. The
classification comes from `constants.mjs`, the same set the style guide and the
validator read, so the three cannot disagree about what a component is.

**Either axis unblocks a parent.** `text` above is `✅` on `[aesthetic]` alone:
`--recurse` requires that a remap exist, never that it carry a particular axis.
The labels are what the icon cannot tell you — `[semantic]` means the child's
target identity is settled and the Load can emit it as block markup;
`[aesthetic]` means only its token references move and it keeps its native
identity. A remap file that exists but declares no usable `remaps` axis (or does
not parse) counts as **not remapped**, with the reason on a `warning:` line —
the engine would reject it anyway.

Flags: `--contract <dir|contract.json>` (required), `--vocabulary <id>` (omit for
the structure alone — no icons, no verdict), `--components <dir>` (defaults to
the contract's `components/` root), `--json` (`ready`, the pending list, the work
order and the nodes, for a caller).

Exit codes: `0` = tree produced — **BLOCKED is an answer, not an error** — `1` =
no contract found, `2` = bad arguments.

## Remapping

Remaps are **co-located** with the contract they override — the only accepted
location — so a remap is addressed by **vocabulary**, the same flag the Load CLIs
take. There is no path flag: `--mapping` was removed, and passing it fails with
that explanation rather than being ignored.

### Single contract

Streams the remapped contract to stdout, or writes `--out`:

```bash
node plugins/wonderwp-design-system/scripts/remap-contract.mjs \
  --contract {ds}/components/atoms/heading \
  --vocabulary wordpress-core \
  [--out {ds}/.remapped/wordpress-core/atoms/heading/contract.json]
```

Resolves `{ds}/components/atoms/heading/contract.wordpress-core.remap.json`. When
the component declares no remap for that vocabulary the engine says so and exits
`1` — it never falls back to the native contract silently.

### Molecule with its whole dependency closure

Parent + one output per child atom, each resolved from its own co-located
`contract.<vocabulary>.remap.json` (the vocabulary comes from the parent's
remap):

```bash
node plugins/wonderwp-design-system/scripts/remap-contract.mjs \
  --contract {ds}/components/molecules/card-news-push \
  --vocabulary wordpress-core \
  --recurse --out-dir {ds}/.remapped/wordpress-core
```

A child missing its remap aborts the whole batch with the expected path listed —
nothing is written (`R-PIPE-5`).

### What the engine reports about the `variations` carnet

Recorded strategies are validated and cross-checked, never executed
(`R-REMAP-8`). On stderr, per component:

```text
warning: variations.size: strategy recorded for an axis with no size/ folder — …
warning: variations: axis "interaction" exists on disk with no strategy recorded — …
notice: RECORDED, NOT EXECUTED (v1) — 1 variation axis/axes:
notice:   style (block-styles): variant values would become registered block styles
          (is-style-*); variant files NOT rewritten
```

Drift and undecided axes are **warnings** (exit stays 0 — an unexecuted decision
is not a broken contract). A malformed carnet is a **validation error**: nothing
is written.

### Flags

- `--contract`: component directory OR direct path to a contract JSON
- `--vocabulary`: **required** — target vocabulary; resolves the remap
  co-located with the contract
- `--out`: optional; single mode; defaults to stdout
- `--recurse --out-dir <dir>`: also remap every child contract; writes one
  `contract.json` per component under `<dir>/<layer>s/<slug>/`
- `--components-dir`: defaults to the source contract's `components/` root
  (where `--recurse` looks up each child's contract + remap)
