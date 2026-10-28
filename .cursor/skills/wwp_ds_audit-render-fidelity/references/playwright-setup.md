# Playwright / Chromium installation guide

The EXIT phase of `audit-render-fidelity` requires Playwright with the Chromium
browser. This guide covers detection, installation, and troubleshooting.

## Detection

The script runs this check automatically at startup:

```js
// audit-render-fidelity.mjs — startup probe
import { execFileSync } from 'node:child_process';
try {
  execFileSync('node', ['-e', "require('@playwright/test')"], { stdio: 'pipe' });
  // found — proceed
} catch {
  // not found — surface guidance below
}
```

If Playwright is absent, the script prints:

```
⚠  Playwright not found. The EXIT phase requires Playwright + Chromium.
   Install it with:

     npm install --save-dev @playwright/test
     npx playwright install chromium

   Then re-run this audit. The ENTRY phase will still run without Playwright.
```

The script then continues with ENTRY-only mode automatically (it does not
crash).

## Installation (project-local)

Run once from the project root (or from `{p}/` if you prefer plugin-local
dependencies):

```bash
npm install --save-dev @playwright/test
npx playwright install chromium
```

Chromium download is ~150 MB. It is cached in `~/.cache/ms-playwright/` and
shared across projects.

## Installation (global / CI)

For CI environments or global availability:

```bash
npm install -g @playwright/test
npx playwright install chromium
```

Or via the standalone installer:

```bash
npx playwright install --with-deps chromium
```

`--with-deps` installs OS-level dependencies on Linux (libnss3, libatk, etc.).
On macOS and Windows these are bundled with the Chromium download.

## Verification

```bash
npx playwright --version
# Playwright version X.Y.Z
node -e "require('@playwright/test'); console.log('ok')"
# ok
```

## Sandbox issues (Linux / Docker)

If Chromium fails to launch with `ENOENT` or sandbox errors, add
`--no-sandbox` via the launch options. The script already passes
`{ args: ['--no-sandbox', '--disable-setuid-sandbox'] }` when it detects a
Linux environment (`process.platform === 'linux'`).

## Offline / air-gapped environments

Download the Chromium archive on a connected machine:

```bash
npx playwright install --dry-run chromium
# Shows the download URL — fetch it manually, place it in the cache path shown
```

Then set `PLAYWRIGHT_BROWSERS_PATH` to point to your local cache.

## Scoping to the plugin only

If you prefer not to touch the project's `package.json`, install inside the
plugin directory:

```bash
cd plugins/wonderwp-design-system
npm install --save-dev @playwright/test
npx playwright install chromium
```

The script resolves `@playwright/test` from the nearest `node_modules/` via
Node's standard module resolution, so a plugin-local install works.
