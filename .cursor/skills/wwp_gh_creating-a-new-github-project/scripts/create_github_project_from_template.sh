#!/usr/bin/env bash
# Copy the Wonderful org GitHub Project v2 template and optionally link a repository.
#
# Environment (defaults):
#   GH_PROJECT_TEMPLATE_OWNER   agencewonderful
#   GH_PROJECT_TEMPLATE_NUMBER  7
#   GH_PROJECT_TARGET_OWNER     same as template owner
#
# Requires: gh CLI with `project` scope (gh auth refresh -s project).

set -euo pipefail

TEMPLATE_OWNER="${GH_PROJECT_TEMPLATE_OWNER:-agencewonderful}"
TEMPLATE_NUMBER="${GH_PROJECT_TEMPLATE_NUMBER:-7}"
TARGET_OWNER="${GH_PROJECT_TARGET_OWNER:-$TEMPLATE_OWNER}"

TITLE=""
LINK_REPO=""
DRY_RUN=0

usage() {
  cat <<'EOF'
Usage: create_github_project_from_template.sh --title TITLE [options]

Options:
  --title TITLE           Title for the new project (required)
  --link-repo OWNER/REPO  Link repository after creation (optional)
  --target-owner LOGIN    Owner of the new project (default: template owner)
  --template-number N     Source template project number (default: 7)
  --template-owner LOGIN  Source template owner (default: agencewonderful)
  --dry-run               Print actions without creating
  -h, --help              Show this help

Examples:
  ./create_github_project_from_template.sh --title "My Site Project" --dry-run
  ./create_github_project_from_template.sh --title "My Site Project" --link-repo agencewonderful/my-repo
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --title)
      TITLE="$2"
      shift 2
      ;;
    --link-repo)
      LINK_REPO="$2"
      shift 2
      ;;
    --target-owner)
      TARGET_OWNER="$2"
      shift 2
      ;;
    --template-number)
      TEMPLATE_NUMBER="$2"
      shift 2
      ;;
    --template-owner)
      TEMPLATE_OWNER="$2"
      shift 2
      ;;
    --dry-run)
      DRY_RUN=1
      shift
      ;;
    -h|--help)
      usage
      exit 0
      ;;
    *)
      echo "Unknown option: $1" >&2
      usage >&2
      exit 1
      ;;
  esac
done

if [[ -z "$TITLE" ]]; then
  echo "Error: --title is required." >&2
  usage >&2
  exit 1
fi

if ! command -v gh >/dev/null 2>&1; then
  echo "Error: gh CLI not found." >&2
  exit 1
fi

echo "Template: ${TEMPLATE_OWNER}/projects/${TEMPLATE_NUMBER}" >&2
echo "Target owner: ${TARGET_OWNER}" >&2
echo "New project title: ${TITLE}" >&2
[[ -n "$LINK_REPO" ]] && echo "Link repo: ${LINK_REPO}" >&2

if [[ "$DRY_RUN" -eq 1 ]]; then
  echo "[dry-run] gh project copy ${TEMPLATE_NUMBER} --source-owner ${TEMPLATE_OWNER} --target-owner ${TARGET_OWNER} --title \"${TITLE}\"" >&2
  if [[ -n "$LINK_REPO" ]]; then
    echo "[dry-run] gh project link <new-number> --owner ${TARGET_OWNER} --repo ${LINK_REPO}" >&2
  fi
  echo "[dry-run] gh project field-list <new-number> --owner ${TARGET_OWNER}" >&2
  exit 0
fi

# Verify template is reachable
if ! gh project view "$TEMPLATE_NUMBER" --owner "$TEMPLATE_OWNER" --format json >/dev/null 2>&1; then
  echo "Error: cannot read template project ${TEMPLATE_NUMBER} (check auth: gh auth refresh -s project)." >&2
  exit 1
fi

echo "Copying template project..." >&2
COPY_JSON="$(gh project copy "$TEMPLATE_NUMBER" \
  --source-owner "$TEMPLATE_OWNER" \
  --target-owner "$TARGET_OWNER" \
  --title "$TITLE" \
  --format json)"

NEW_NUMBER="$(echo "$COPY_JSON" | python3 -c "import json,sys; d=json.load(sys.stdin); print(d.get('number',''))")"
NEW_URL="$(echo "$COPY_JSON" | python3 -c "import json,sys; d=json.load(sys.stdin); print(d.get('url',''))")"
NEW_ID="$(echo "$COPY_JSON" | python3 -c "import json,sys; d=json.load(sys.stdin); print(d.get('id',''))")"

if [[ -z "$NEW_NUMBER" ]]; then
  echo "Error: copy succeeded but project number missing from response:" >&2
  echo "$COPY_JSON" >&2
  exit 1
fi

echo "Created project #${NEW_NUMBER}: ${NEW_URL}" >&2
echo "Node ID: ${NEW_ID}" >&2

if [[ -n "$LINK_REPO" ]]; then
  echo "Linking repository ${LINK_REPO}..." >&2
  gh project link "$NEW_NUMBER" --owner "$TARGET_OWNER" --repo "$LINK_REPO"
fi

# Verify expected template fields exist
FIELD_NAMES="$(gh project field-list "$NEW_NUMBER" --owner "$TARGET_OWNER" --format json \
  | python3 -c "import json,sys; d=json.load(sys.stdin); print(','.join(f['name'] for f in d.get('fields',[])))")"

REQUIRED="Priority,Size,Estimate,Iteration,Start date,End date"
MISSING=()
IFS=',' read -ra REQ <<< "$REQUIRED"
for name in "${REQ[@]}"; do
  if [[ "$FIELD_NAMES" != *"$name"* ]]; then
    MISSING+=("$name")
  fi
done

if [[ ${#MISSING[@]} -gt 0 ]]; then
  echo "Warning: expected template fields missing: ${MISSING[*]}" >&2
else
  echo "Template fields verified." >&2
fi

# Machine-readable summary on stdout
python3 -c "import json; print(json.dumps({'number': int('${NEW_NUMBER}'), 'url': '${NEW_URL}', 'id': '${NEW_ID}', 'title': '''${TITLE}'''}))"
