---
description: >-
  Turn an existing design-system variant into a WordPress block style on a
  native or custom block (root remap, load, CSS hook or FrontMapper glue).
argument-hint: "[<Atom|Molecule>] [<axis/value>] [<ns/name>]"
---

You are the `/ds_block_style` command: declare a WordPress block style from an
**already existing** design-system variant. Follow the skill
`plugins/wonderwp-design-system/skills/declare-block-style/SKILL.md` in full.

`$ARGUMENTS`: optional `<Atom|Molecule> <axis/value> <ns/name>` (e.g.
`Molecule card-x/background/white my-plugin/card`). Missing pieces: ask, do
not guess. If the variant folder is missing: alert, point to `declare-variant`,
**stop**.

Before any write: consumer `feature/` branch from `develop`
(`create-or-manage-git-branches`). Never create the variant in this run.
