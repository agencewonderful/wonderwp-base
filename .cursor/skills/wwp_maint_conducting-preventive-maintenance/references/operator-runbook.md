# Operator runbook (guided timeline)

## Why

The old `maintenance.sh` guided the operator **step by step** with a clear path.  
`maintenance:pending` JSON (dozens of concern ids) is for the **agent**, not a substitute for that guidance.

Every real maintenance therefore maintains a **human runbook**: a single checklist document that shows the routine, where we are, and how to resume after a pause.

## Session opening (before any scan)

On **start** or **relance depuis le début** of this skill, the **first chat message** to the operator must happen **before** running WP-CLI, git, or composer. Use this spirit (adapt site name):

```markdown
## Mode maintenance activé

Je prends le rôle d’**assistant opérateur de maintenance** pour **[SITE]**.

Je vais d’abord effectuer les **premiers scans** (environnement, branche, plugin maintenance, accès CI, état du ledger) pour savoir où en est le site, puis générer la **session** et le **document de suivi (runbook)**.

Tu n’as rien à faire pour l’instant — je reviens avec le runbook à ouvrir.
```

Then run scans. **Do not** dump pending JSON or long technical lists in that first message.

## After scans — offer the runbook

When session JSON + runbook exist:

1. One short **synthèse humaine** (3–5 lignes max : branche, session id, hébergement, étape courante, gates ouverts).
2. **Explicitly ask** the operator to open the runbook in the IDE (path below). Offer HTML or canvas only if they want an alternate view.
3. List **only unresolved gates** in one short batch — then **enchaîner** (see [operator-autonomy.md](./operator-autonomy.md)). Do not stop after every section for « continue ? ».

## Canonical file

```
ai_resources/maintenance/sessions/<sessionId>.runbook.md
```

Optional companion for browser:

```
ai_resources/maintenance/sessions/<sessionId>.runbook.html
```

Template: [../assets/operator-runbook.template.md](../assets/operator-runbook.template.md)

Machine companion (unchanged): `sessions/<sessionId>.json` from `maintenance:session`.

## When to create / update

| Moment | Action |
| ------ | ------ |
| After `maintenance:session start` + first `maintenance:pending` | **Generate** the runbook from the template; pre-check items already done in prepare |
| After each completed checklist item / atom / gate | **Immediately** tick `[x]` / `[-]` / `[!]` in the runbook file; refresh **Étape courante** + **Où en est-on ?** (same turn as the work when possible) |
| End of each agent turn that did maintenance work | Runbook must reflect reality — never leave completed steps unchecked |
| Before asking the operator anything | Point to the runbook section, not a dump of 50 pending ids |
| On pause / end of turn mid-session | Set status `paused` if stopping; leave **Étape courante** accurate |
| On resume (“reprends la maintenance”) | **Read the runbook first**, then session JSON, then pending — continue from first unchecked item |
| On `maintenance:session finish` | Status `done`; keep the runbook committed with the ledger |

## Writing rules (operator-facing)

1. **French**, short verbs, same spirit as the old script (“Pull DB prod”, “Build front”, …).
2. Group by the **10 sections** of the template (prepare → … → finalize) — not by raw catalogue order dumps in chat.
3. Put **catalogue concern ids** only under each section’s `_Concerns : …_` line or in the machine appendix — never as the primary checkbox label.
4. Mark **gates** explicitly (prod, secrets, …) and maintain a **Plan de commit** table for §8 (site + packages Wonderful); PR links go there once opened — not a pre-commit gate.
5. Collapse many `recurring.*` into **one** human checkbox when they share one atom/script (e.g. one line for atome `05`, not three identical npm lines).
6. Never paste secrets from `CI/*_credentials.json` into the runbook.
7. Do **not** replace the runbook with a chat-only bullet list. Chat may summarize (“on est à l’étape 3, case PHP”) and link the file.
8. **Scope:** assume **maintenance complète**; do not ask the operator to confirm full scope by default. Only document a different scope if they request it.
9. **DB sync:** follow [env-database-sync.md](./env-database-sync.md) — MigrateDB `profiles` first, operator picks profile or skips; SQL file only as fallback.

## Presenting to the operator

After generating or updating the runbook, **offer three ways to follow along** (operator picks; do not force all three):

1. **IDE** — open `…/sessions/<id>.runbook.md` in the editor (default, always available).
2. **Navigateur** — if asked, generate a minimal self-contained `.runbook.html` (checkboxes read-only snapshot or mirrored list) and open it; or use Markdown preview in the IDE.
3. **Canvas interactif** — if the environment supports Cursor Canvas (or equivalent), offer a live checklist canvas synced from the same sections; the **markdown runbook remains source of truth** for git/resume.

Always say the absolute or repo-relative path in one short sentence.

## Resume protocol

```text
1. Open ai_resources/maintenance/sessions/<id>.runbook.md
2. Read status + Étape courante + first [ ] / [!] items
3. Read sessions/<id>.json + ledger.json + ddev wp maintenance:pending
4. Continue from that step; do not restart completed sections
```

If several session runbooks exist, use `ledger.lastSessionId` or the most recent `in_progress` / `paused` runbook.

## Anti-patterns

- Asking the operator to confirm every section (« on enchaîne sur le front ? ») when gates are already decided
- Stopping the process for status recap instead of updating the runbook
- Dumping full `pending` JSON into chat as the “plan”
- Updating only session JSON and leaving the runbook stale
- Inventing a second parallel tracker (Notion/Podio/etc.) as source of truth
- Skipping runbook creation because “the agent remembers”
