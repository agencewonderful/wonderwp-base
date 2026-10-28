# Atom semantic `nodeName` suggestions

When picking the root (or any child) semantic type for an atom, suggest a
PascalCase `nodeName` derived from the atom's nature. The list is **not
closed** — pick the most fitting PascalCase name. The transform / load phase
decides how to map it (HTML `<button>`, theme.json `core/button`, React
`<Button>`, etc.). See `rules/contract-schema.md` for the canonical naming
conventions and the reserved-nodeName list.

| Atom kind | Suggested `nodeName` |
|---|---|
| Button (action) | `Button` |
| Link (navigation) | `Link` |
| Text input | `Input` |
| Textarea | `Textarea` |
| Checkbox / radio / switch | `Toggle` |
| Static label / paragraph | `Label` or `Text` |
| Icon (decorative) | `Icon` |
| Image (semantic) | `Image` |
| Badge / chip | `Badge` |
| Divider / separator | `Separator` |
| List wrapper | `List` |
| List entry | `ListItem` |
