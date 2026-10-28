#!/usr/bin/env python3
"""Synchronise un plan backlog enrichi (markdown) vers des issues GitHub parent / sous-issues.

Réglages via variables d'environnement (avec valeurs par défaut présentes pour éviter une chaîne vide trompeuse) :

* ``GH_ISSUE_OWNER`` — propriétaire du dépôt (défaut : ``agencewonderful``).
* ``GH_ISSUE_REPO_NAME`` — nom du dépôt (défaut : ``ReseauEcolesCreatives``).
* ``GH_ISSUE_ORG`` — organisation pour les *issue types* et le Project v2 (défaut : ``GH_ISSUE_OWNER``).
* ``GITHUB_PROJECT_NODE_ID`` — nœud GraphQL du projet (optionnel ; sinon résolution par numéro).
* ``GITHUB_PROJECT_NUMBER`` — numéro du Project v2 organisation (défaut : ``15``).

Règles :

* Ignore les tickets dont ``assignee``, ``category`` ou ``body`` contiennent encore la mention ``NEEDS CLARIFICATION``.
* Ignore les blocs qui portent déjà ``- tracker_url:``.
* Crée l’epic parente si au moins un enfant est créable et que l’epic n’a pas de ``tracker_url``.
* Attache les enfants comme sous-issues GitHub ; associe le type d’issue d’organisation dont le libellé
  correspond exactement à ``category`` (cf. ``agents.md`` du dépôt consommateur).
* Dépendance : ``gh auth login`` ou ``GITHUB_TOKEN`` / ``GH_TOKEN``.

Exemple::

  python3 scripts/sync_backlog_plan_to_github.py --plan /chemin/vers/plan.md --dry-run
  python3 scripts/sync_backlog_plan_to_github.py --plan /chemin/vers/plan.md --write-back
"""

from __future__ import annotations

import argparse
import json
import os
import re
import subprocess
import sys
import urllib.error
import urllib.request
from dataclasses import dataclass
from pathlib import Path
from typing import Any

OWNER = os.environ.get("GH_ISSUE_OWNER") or "agencewonderful"
REPO = os.environ.get("GH_ISSUE_REPO_NAME") or "ReseauEcolesCreatives"
ORG = os.environ.get("GH_ISSUE_ORG") or OWNER
DEFAULT_PROJECT_NUMBER = int(os.environ.get("GITHUB_PROJECT_NUMBER", "15"))


def gh_token() -> str:
    try:
        return subprocess.check_output(["gh", "auth", "token"], text=True).strip()
    except (FileNotFoundError, subprocess.CalledProcessError) as exc:
        token = os.environ.get("GITHUB_TOKEN") or os.environ.get("GH_TOKEN")
        if not token:
            raise SystemExit(
                "Need `gh auth login` or GITHUB_TOKEN / GH_TOKEN in the environment."
            ) from exc
        return token


def graphql(payload: dict[str, Any]) -> dict[str, Any]:
    req = urllib.request.Request(
        "https://api.github.com/graphql",
        data=json.dumps(payload).encode(),
        headers={
            "Authorization": f"bearer {gh_token()}",
            "Content-Type": "application/json",
        },
        method="POST",
    )
    try:
        with urllib.request.urlopen(req) as resp:
            data = json.loads(resp.read())
    except urllib.error.HTTPError as e:
        print(e.read().decode(), file=sys.stderr)
        raise
    if data.get("errors"):
        raise SystemExit(json.dumps(data["errors"], indent=2))
    return data


def gql_repo_id(owner: str, name: str) -> str:
    q = """query Repo($owner: String!, $name: String!) {
      repository(owner: $owner, name: $name) { id databaseId }
    }"""
    data = graphql({"query": q, "variables": {"owner": owner, "name": name}})
    return data["data"]["repository"]["id"]


def gql_project_v2_id(org_login: str, number: int) -> str | None:
    q = """query P($login: String!, $number: Int!) {
      organization(login: $login) {
        projectV2(number: $number) { id title }
      }
    }"""
    data = graphql({"query": q, "variables": {"login": org_login, "number": number}})
    pv2 = data["data"]["organization"]["projectV2"]
    return pv2["id"] if pv2 else None


def gql_org_issue_types(org_login: str) -> dict[str, str]:
    q = """query IT($login: String!) {
      organization(login: $login) {
        issueTypes(first: 100) {
          nodes { id name }
        }
      }
    }"""
    data = graphql({"query": q, "variables": {"login": org_login}})
    nodes = data["data"]["organization"]["issueTypes"]["nodes"]
    return {n["name"]: n["id"] for n in nodes}


def gql_user_id(login: str) -> str:
    q = """query U($login: String!) { user(login: $login) { id } }"""
    data = graphql({"query": q, "variables": {"login": login}})
    u = data["data"]["user"]
    if not u:
        raise SystemExit(f"Unknown GitHub user login: {login}")
    return u["id"]


def create_issue(
    repo_id: str,
    title: str,
    body: str,
    *,
    parent_node_id: str | None,
    issue_type_id: str | None,
    assignee_node_id: str | None,
    project_v2_id: str | None,
) -> dict[str, Any]:
    mutation = """
    mutation CreateIssue($input: CreateIssueInput!) {
      createIssue(input: $input) {
        issue { number url title issueType { name } }
      }
    }
    """
    inp: dict[str, Any] = {
        "repositoryId": repo_id,
        "title": title,
        "body": body,
    }
    if parent_node_id:
        inp["parentIssueId"] = parent_node_id
    if issue_type_id:
        inp["issueTypeId"] = issue_type_id
    if assignee_node_id:
        inp["assigneeIds"] = [assignee_node_id]
    if project_v2_id:
        inp["projectV2Ids"] = [project_v2_id]
    data = graphql({"query": mutation, "variables": {"input": inp}})
    return data["data"]["createIssue"]["issue"]


def needs_clarification(value: str | None) -> bool:
    if value is None:
        return True
    return "NEEDS CLARIFICATION" in value


@dataclass
class Ticket:
    title: str
    assignee_raw: str | None
    category: str | None
    body: str | None
    visual_ref: str | None
    tracker_url: str | None


@dataclass
class Epic:
    title: str
    slug: str | None
    summary: str | None
    tracker_url: str | None
    tickets: list[Ticket]


EPIC_SPLIT = re.compile(r"^### Epic:\s*(.+)\s*$", re.MULTILINE)
TICKET_SPLIT = re.compile(r"^#### Ticket:\s*(.+)\s*$", re.MULTILINE)
LIST_FIELD = re.compile(r"^-\s*([a-zA-Z_]+):\s?(.*)$")


def _bullet_field(line: str) -> tuple[str, str | None]:
    lm = LIST_FIELD.match(line)
    if not lm:
        return ("", None)
    return lm.group(1), lm.group(2)


def _parse_ticket_blob(title: str, blob: str) -> Ticket:
    lines = blob.splitlines()
    assignee = category = body = vis = t_url = None
    body_lines: list[str] = []
    in_body = False
    for raw in lines:
        line = raw.rstrip()
        if not line.strip():
            if in_body:
                body_lines.append(line)
            continue
        field, rest = _bullet_field(line)
        if field:
            if in_body:
                body_out = "\n".join(body_lines).rstrip()
                body = None if body_out == "" else body_out
                in_body = False
                body_lines = []
            if field == "assignee":
                assignee = rest.strip() if rest else None
            elif field == "category":
                category = rest.strip() if rest else None
            elif field == "visual_reference":
                vis = rest.strip() if rest else None
            elif field == "tracker_url":
                t_url = rest.strip() if rest else None
            elif field == "tracker_id":
                pass
            elif field == "body":
                r = rest.strip() if rest else ""
                if r == "|":
                    in_body = True
                    body_lines = []
                else:
                    body = r
            continue
        if in_body:
            body_lines.append(line)
            continue
    if in_body and body_lines:
        body_out = "\n".join(body_lines).rstrip()
        body = body_out if body_out else body
    return Ticket(
        title=title.strip(),
        assignee_raw=assignee,
        category=category,
        body=body,
        visual_ref=vis,
        tracker_url=t_url,
    )


def parse_markdown(text: str) -> list[Epic]:
    epic_matches = list(EPIC_SPLIT.finditer(text))
    epics: list[Epic] = []
    for ei, em in enumerate(epic_matches):
        epic_title = em.group(1).strip()
        chunk_start = em.end()
        chunk_end = (
            epic_matches[ei + 1].start() if ei + 1 < len(epic_matches) else len(text)
        )
        epic_chunk = text[chunk_start:chunk_end]
        tm_list = list(TICKET_SPLIT.finditer(epic_chunk))
        preamble_end = tm_list[0].start() if tm_list else len(epic_chunk)
        preamble = epic_chunk[:preamble_end]
        slug = summary = epic_tracker = None
        for pl in preamble.splitlines():
            fld, rst = _bullet_field(pl)
            if fld == "slug" and rst is not None:
                slug = rst.strip()
            elif fld == "summary" and rst is not None:
                summary = rst.strip()
            elif fld == "tracker_url" and rst is not None:
                epic_tracker = rst.strip()
            elif fld == "tracker_id":
                pass
        tickets: list[Ticket] = []
        for ti, tmat in enumerate(tm_list):
            t_title = tmat.group(1).strip()
            t_start = tmat.end()
            t_next = tm_list[ti + 1].start() if ti + 1 < len(tm_list) else len(
                epic_chunk
            )
            t_blob = epic_chunk[t_start:t_next]
            tickets.append(_parse_ticket_blob(t_title, t_blob))
        epics.append(
            Epic(
                title=epic_title,
                slug=slug,
                summary=summary,
                tracker_url=epic_tracker,
                tickets=tickets,
            )
        )
    return epics


def epic_body_md(epic: Epic) -> str:
    parts = []
    if epic.slug:
        parts.append(f"- **slug :** `{epic.slug}`")
    if epic.summary:
        parts.append(f"- **Résumé :** {epic.summary}")
    parts.append("")
    parts.append(
        "Créée via le script ``sync_backlog_plan_to_github.py`` (plugin wonderwp-github)."
    )
    return "\n".join(parts).strip()


def ticket_body_md(
    ticket: Ticket, *, parent_issue_number: int | None
) -> str:
    chunks: list[str] = []
    if ticket.body:
        chunks.append(ticket.body.strip())
    if ticket.visual_ref and ticket.visual_ref.upper() != "NONE":
        chunks.append("")
        chunks.append("## Référence visuelle")
        chunks.append(ticket.visual_ref.strip())
    if parent_issue_number is not None:
        chunks.append("")
        chunks.append(f"Fait partie de #{parent_issue_number}")
    return "\n".join(chunks).strip()


def inject_epic_tracker(md: str, epic_title: str, url: str, num: int) -> str:
    anchor = f"### Epic: {epic_title}"
    idx = md.find(anchor)
    if idx == -1:
        return md
    sub = md[idx:]
    m = re.search(r"^-\s*summary:\s*.+$", sub, re.MULTILINE)
    if not m:
        return md
    insert_at = idx + m.end()
    block = f"\n- tracker_url: {url}\n- tracker_id: \"{num}\""
    if f"- tracker_url: {url}" in md:
        return md
    return md[:insert_at] + block + md[insert_at:]


def inject_ticket_tracker(md: str, ticket_title: str, url: str, num: int) -> str:
    anchor = f"#### Ticket: {ticket_title}"
    idx = md.find(anchor)
    if idx == -1:
        return md
    rest = md[idx + len(anchor) :]
    nxt = re.search(r"^#### |^### ", rest, re.MULTILINE)
    end = idx + len(anchor) + (nxt.start() if nxt else len(rest))
    block = md[idx:end]
    if "- tracker_url:" in block:
        return md
    trail = block.rstrip()
    insert = f"\n- tracker_url: {url}\n- tracker_id: \"{num}\""
    new_block = trail + insert + "\n"
    return md[:idx] + new_block + md[end:]


def normalize_assignee(raw: str) -> str:
    r = raw.strip()
    if r.startswith("@"):
        r = r[1:]
    return r


def main() -> None:
    ap = argparse.ArgumentParser(description=__doc__)
    ap.add_argument(
        "--plan",
        type=Path,
        required=True,
        help="Chemin du markdown de plan backlog enrichi.",
    )
    ap.add_argument(
        "--dry-run",
        action="store_true",
        help="Affiche les actions sans appeler l’API GitHub.",
    )
    ap.add_argument(
        "--write-back",
        action="store_true",
        help="Réécrit le markdown avec tracker_url / tracker_id après création.",
    )
    args = ap.parse_args()

    md = args.plan.read_text(encoding="utf-8")
    epics = parse_markdown(md)

    proj_id = os.environ.get("GITHUB_PROJECT_NODE_ID") or gql_project_v2_id(
        ORG, DEFAULT_PROJECT_NUMBER
    )
    if not proj_id:
        print(
            f"WARN: no org projectV2 id for {ORG} number {DEFAULT_PROJECT_NUMBER}",
            file=sys.stderr,
        )

    issue_types = gql_org_issue_types(ORG)
    repo_id = gql_repo_id(OWNER, REPO)
    user_cache: dict[str, str] = {}

    def assignee_id(login: str) -> str:
        if login not in user_cache:
            user_cache[login] = gql_user_id(login)
        return user_cache[login]

    out_md = md

    for epic in epics:
        creatable = [
            t
            for t in epic.tickets
            if not needs_clarification(t.assignee_raw)
            and not needs_clarification(t.category)
            and not needs_clarification(t.body)
            and not t.tracker_url
        ]
        epic_num: int | None = None
        epic_node: str | None = None

        if epic.tracker_url:
            m = re.search(r"/issues/(\d+)", epic.tracker_url)
            if m:
                epic_num = int(m.group(1))

        if epic.tracker_url and epic_num is None:
            print(f"SKIP epic (unparsed tracker_url): {epic.title}", file=sys.stderr)
        elif epic.tracker_url is None:
            if not creatable:
                print(
                    f"SKIP epic (no creatable tickets, no tracker): {epic.title}",
                    file=sys.stderr,
                )
                continue
            if args.dry_run:
                print(f"DRY-RUN create EPIC: {epic.title}", file=sys.stderr)
                epic_node = "DRY"
            else:
                issue = create_issue(
                    repo_id,
                    epic.title,
                    epic_body_md(epic),
                    parent_node_id=None,
                    issue_type_id=None,
                    assignee_node_id=None,
                    project_v2_id=proj_id,
                )
                epic_num = int(issue["number"])
                m2 = graphql(
                    {
                        "query": """query Iss($owner:String!,$repo:String!,$n:Int!){
                           repository(owner:$owner,name:$repo){
                             issue(number:$n){ id }
                           }
                        }""",
                        "variables": {
                            "owner": OWNER,
                            "repo": REPO,
                            "n": epic_num,
                        },
                    }
                )
                epic_node = m2["data"]["repository"]["issue"]["id"]
                print(f"CREATED EPIC #{epic_num} {issue['url']}", file=sys.stderr)
                if args.write_back:
                    out_md = inject_epic_tracker(
                        out_md, epic.title, issue["url"], epic_num
                    )
        else:
            if epic_num is None:
                print(
                    f"WARN epic has tracker_url but no number: {epic.title}",
                    file=sys.stderr,
                )
                continue
            m2 = graphql(
                {
                    "query": """query Iss($owner:String!,$repo:String!,$n:Int!){
                       repository(owner:$owner,name:$repo){
                         issue(number:$n){ id }
                       }
                    }""",
                    "variables": {"owner": OWNER, "repo": REPO, "n": epic_num},
                }
            )
            epic_node = m2["data"]["repository"]["issue"]["id"]

        if epic_node is None:
            continue

        for ticket in epic.tickets:
            if ticket.tracker_url:
                continue
            if (
                needs_clarification(ticket.assignee_raw)
                or needs_clarification(ticket.category)
                or needs_clarification(ticket.body)
            ):
                continue
            if args.dry_run:
                print(f"  DRY-RUN ticket: {ticket.title}", file=sys.stderr)
                continue
            itype = issue_types.get(ticket.category or "")
            if not itype:
                raise SystemExit(
                    f"Unknown category / issue type for ticket {ticket.title!r}: {ticket.category!r}"
                )
            login = normalize_assignee(ticket.assignee_raw or "")
            aid = assignee_id(login)
            body = ticket_body_md(ticket, parent_issue_number=epic_num)
            issue = create_issue(
                repo_id,
                ticket.title,
                body,
                parent_node_id=epic_node if epic_node != "DRY" else None,
                issue_type_id=itype,
                assignee_node_id=aid,
                project_v2_id=proj_id,
            )
            print(f"  CREATED #{issue['number']} {issue['url']}", file=sys.stderr)
            if args.write_back:
                out_md = inject_ticket_tracker(
                    out_md, ticket.title, issue["url"], int(issue["number"])
                )

    if args.write_back and out_md != md and not args.dry_run:
        args.plan.write_text(out_md, encoding="utf-8")
        print(f"Updated {args.plan}", file=sys.stderr)

    print("Done.", file=sys.stderr)


if __name__ == "__main__":
    main()
