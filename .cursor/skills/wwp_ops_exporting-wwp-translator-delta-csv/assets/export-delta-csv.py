#!/usr/bin/env python3
"""Build wwp-translator import CSVs from PO deltas.

Column index must equal language id (pad gaps). See references/csv-format.md.
"""

from __future__ import annotations

import argparse
import csv
import pathlib
import re
import subprocess
import sys
from typing import Dict, Iterable, List, Optional, Sequence, Tuple


def parse_po(path: pathlib.Path) -> Dict[str, str]:
    text = path.read_text(encoding="utf-8", errors="replace")
    entries: Dict[str, str] = {}
    for block in re.split(r"\n\n+", text):
        m = re.search(
            r'^msgid "(.*)"\n((?:".*"\n)*)msgstr "(.*)"\s*$',
            block,
            re.M,
        )
        if m:
            msgid = m.group(1) + "".join(re.findall(r'^"(.*)"$', m.group(2), re.M))
            msgstr = m.group(3)
            after = block[m.end() :]
            msgstr += "".join(re.findall(r'^"(.*)"$', after, re.M))
        else:
            m2 = re.search(
                r'^msgid "(.*)"\n((?:".*"\n)*)msgstr ""\n((?:".*"\n?)*)',
                block,
                re.M,
            )
            if not m2:
                mid = re.search(r'^msgid "(.*)"\s*$', block, re.M)
                ms = re.search(r'^msgstr "(.*)"\s*$', block, re.M)
                if not mid or not ms:
                    continue
                msgid, msgstr = mid.group(1), ms.group(1)
            else:
                msgid = m2.group(1) + "".join(re.findall(r'^"(.*)"$', m2.group(2), re.M))
                msgstr = "".join(re.findall(r'^"(.*)"$', m2.group(3), re.M))

        def unesc(s: str) -> str:
            return (
                s.replace("\\n", "\n")
                .replace("\\t", "\t")
                .replace('\\"', '"')
                .replace("\\\\", "\\")
            )

        msgid_u = unesc(msgid)
        if msgid_u == "":
            continue
        entries[msgid_u] = unesc(msgstr)
    return entries


def load_langs_from_ddev(table: str) -> List[Tuple[int, str, str]]:
    cmd = [
        "ddev",
        "mysql",
        "-N",
        "-e",
        f"SELECT id, locale, title FROM {table} ORDER BY id;",
    ]
    proc = subprocess.run(cmd, capture_output=True, text=True, check=False)
    if proc.returncode != 0:
        raise SystemExit(
            f"Failed to query {table} via ddev mysql:\n{proc.stderr or proc.stdout}"
        )
    langs: List[Tuple[int, str, str]] = []
    for line in proc.stdout.strip().splitlines():
        if not line.strip():
            continue
        parts = line.split("\t")
        if len(parts) < 3:
            raise SystemExit(f"Unexpected lang row: {line!r}")
        langs.append((int(parts[0]), parts[1], parts[2]))
    if not langs:
        raise SystemExit(f"No rows in {table}")
    return langs


def pad_lang_columns(
    langs: Sequence[Tuple[int, str, str]],
) -> List[Tuple[Optional[int], Optional[str], str]]:
    """Return slots for ids 1..max(id); missing ids get (None, None, '')."""
    by_id = {lid: (lid, locale, title) for lid, locale, title in langs}
    max_id = max(by_id)
    slots: List[Tuple[Optional[int], Optional[str], str]] = []
    for i in range(1, max_id + 1):
        if i in by_id:
            lid, locale, title = by_id[i]
            slots.append((lid, locale, title))
        else:
            slots.append((None, None, ""))
    return slots


def keys_from_git_diff(domain: str, base: str, lang_dir: pathlib.Path) -> List[str]:
    pattern = str(lang_dir / f"{domain}-*.po")
    proc = subprocess.run(
        ["git", "diff", f"{base}...HEAD", "--", pattern],
        capture_output=True,
        text=True,
        check=False,
    )
    if proc.returncode != 0:
        # fallback without triple-dot
        proc = subprocess.run(
            ["git", "diff", base, "HEAD", "--", pattern],
            capture_output=True,
            text=True,
            check=False,
        )
    added = set(re.findall(r'^\+msgid "(.+)"\s*$', proc.stdout, re.M))
    removed = set(re.findall(r'^-msgid "(.+)"\s*$', proc.stdout, re.M))
    # new keys = added msgids that were not present before in the same hunk sense
    keys = sorted(k for k in (added - removed) if k)
    return keys


def read_keys_file(path: pathlib.Path) -> List[str]:
    keys = []
    for line in path.read_text(encoding="utf-8").splitlines():
        line = line.strip()
        if line and not line.startswith("#"):
            keys.append(line)
    return keys


def collect_msgstr(
    domain: str, locale: str, lang_dir: pathlib.Path
) -> Dict[str, str]:
    path = lang_dir / f"{domain}-{locale}.po"
    if not path.exists():
        return {}
    return parse_po(path)


def write_csv(
    out_path: pathlib.Path,
    keys: Sequence[str],
    slots: Sequence[Tuple[Optional[int], Optional[str], str]],
    by_locale: Dict[str, Dict[str, str]],
) -> None:
    headers = ["Clé de traduction"] + [title for _, _, title in slots]
    rows: List[List[str]] = [headers]
    for key in keys:
        row = [key]
        for _, locale, _ in slots:
            if not locale:
                row.append("")
            else:
                row.append(by_locale.get(locale, {}).get(key, ""))
        rows.append(row)

    out_path.parent.mkdir(parents=True, exist_ok=True)
    with out_path.open("w", encoding="utf-8", newline="") as fh:
        writer = csv.writer(fh, delimiter=";", quoting=csv.QUOTE_MINIMAL, lineterminator="\n")
        writer.writerows(rows)


def main(argv: Optional[Sequence[str]] = None) -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--domain", required=True, help="Textdomain, e.g. wwp-newsletter")
    parser.add_argument("--key", action="append", default=[], help="msgid (repeatable)")
    parser.add_argument("--keys-file", type=pathlib.Path, help="File with one msgid per line")
    parser.add_argument(
        "--git-diff",
        metavar="BASE",
        help="Collect newly added msgids vs BASE...HEAD for this domain",
    )
    parser.add_argument(
        "--lang-table",
        default="pl_wwp_lang",
        help="DB table for languages (default: pl_wwp_lang)",
    )
    parser.add_argument(
        "--lang-dir",
        type=pathlib.Path,
        default=pathlib.Path("web/app/languages/plugins"),
        help="Directory containing <domain>-<locale>.po files",
    )
    parser.add_argument(
        "--out-dir",
        type=pathlib.Path,
        default=pathlib.Path("web/app/uploads/wwp-translator"),
        help="Output directory",
    )
    parser.add_argument(
        "--suffix",
        default="delta",
        help="Filename suffix: <domain>-<suffix>.csv",
    )
    args = parser.parse_args(argv)

    keys: List[str] = list(args.key)
    if args.keys_file:
        keys.extend(read_keys_file(args.keys_file))
    if args.git_diff:
        keys.extend(keys_from_git_diff(args.domain, args.git_diff, args.lang_dir))

    # dedupe preserve order
    seen = set()
    ordered_keys = []
    for k in keys:
        if k not in seen:
            seen.add(k)
            ordered_keys.append(k)

    if not ordered_keys:
        print("No keys to export. Pass --key, --keys-file, or --git-diff.", file=sys.stderr)
        return 1

    langs = load_langs_from_ddev(args.lang_table)
    slots = pad_lang_columns(langs)

    by_locale: Dict[str, Dict[str, str]] = {}
    for _, locale, _ in slots:
        if locale:
            by_locale[locale] = collect_msgstr(args.domain, locale, args.lang_dir)

    out = args.out_dir / f"{args.domain}-{args.suffix}.csv"
    write_csv(out, ordered_keys, slots, by_locale)

    print(f"Wrote {out} ({len(ordered_keys)} key(s), {1 + len(slots)} columns)")
    for key in ordered_keys:
        missing = [
            locale
            for _, locale, _ in slots
            if locale and not by_locale.get(locale, {}).get(key)
        ]
        print(f"  - {key}")
        if missing:
            print(f"    missing locales: {', '.join(missing)}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
