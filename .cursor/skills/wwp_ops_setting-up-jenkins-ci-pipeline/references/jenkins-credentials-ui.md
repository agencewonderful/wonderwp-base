# Jenkins credentials UI

How to upload per-branch credential secret files in Jenkins (Wonderful CI workflow).

For JSON field definitions, local file naming (environment), and branch→env mapping, see [credential-files.md](./credential-files.md).

## Jenkins secret file IDs (branch-based)

After the first push, Jenkins expects secret file **IDs** named exactly like the pipeline (`$BRANCH_NAME`):

- `{slug}_develop_credentials` — loaded when building `develop` (typically preprod/staging content)
- `{slug}_main_credentials` — loaded when building `main` (typically production content) — **default for new projects**
- `{slug}_master_credentials` — **legacy only**, when the repo still uses `master` as production branch

The **uploaded file** on disk is named by environment (`{slug}_preprod_credentials.json`, `{slug}_prod_credentials.json`); only the Jenkins **ID** uses the branch name.

## UI steps

1. Open the project in Jenkins (e.g. `http://jenkins.example.com/job/Org/job/project/`)
2. Left sidebar → **Credentials**
3. **Folder** → **Add domain** — name it like the project → Save
4. **Add Credentials**
5. Kind: **Secret file**
6. Upload the **environment** JSON file (e.g. `direct-region_preprod_credentials.json`)
7. **ID / name:** set the **branch** ID the pipeline expects (e.g. `direct-region_develop_credentials` — not `direct-region_preprod_credentials`)
8. Repeat for each branch/environment pair:
   - preprod file → `{slug}_develop_credentials`
   - prod file → `{slug}_main_credentials` (or `{slug}_master_credentials` on legacy repos)
9. Re-run the failed first build

## Demo video

Wonderful documentation links a walkthrough: [jenkins_credentials.mov](http://www.wonderwp.com.wdf-02.ovea.com/app/uploads/jenkins_credentials.mov)

## Slack

Slack is pre-configured in [assets/Jenkinsfile.template](../assets/Jenkinsfile.template) per [org-ci-conventions.md](./org-ci-conventions.md) — no per-project credential upload.
