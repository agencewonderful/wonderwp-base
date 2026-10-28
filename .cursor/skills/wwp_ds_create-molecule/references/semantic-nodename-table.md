# Molecule semantic `nodeName` suggestions

When picking the root (or any molecule-level) semantic type, suggest a
PascalCase `nodeName`. The transform / load phase decides how to map it. See
`rules/contract-schema.md` for the canonical naming conventions and the
reserved-nodeName list (`Slot`, `Fragment`).

A molecule names **containers**. Everything it names is a box; everything that
renders content is a child component reached through a `Slot`.

| Molecule kind | Suggested `nodeName` |
|---|---|
| Card / tile / panel | `Section` |
| Form group / field | `Group` |
| Navigation item / menu entry | `NavigationItem` |
| List entry composite | `ListItem` |
| Hero block | `Section` |
| Banner / callout | `Section` |
| Layout wrapper | `Layout` |

Molecule-level part Elements commonly use `Layout`, `Header`, `Body`,
`Footer`, `Media`, `Content`. This container list is **not closed** — pick the
most fitting PascalCase name, invented ones included (`Ribbon`, `Gutter`).

## Reserved for atoms — never inline in a molecule

These names render content or an interactive control, which makes them
components (R-CONTRACT-9). In a molecule they appear only as the `atom`
attribute of a `Slot`, never as an Element:

`Badge` · `Button` · `Heading` · `Icon` · `Image` · `Input` · `Label` ·
`Link` · `Paragraph` · `Separator` · `Text` · `Textarea` · `Toggle`

Reaching for one of them means a leaf of the source is a component you have
not declared yet — go create the atom (`create-atom`), then slot it. Renaming
the wrapper to dodge the list only hides the free electron.

Authoritative copy of both lists: `scripts/lib/constants.mjs`.
