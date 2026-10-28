# Provider config — annotated fields

Live file: `{ds}/providers/wonderwp.json` (**project data** — the plugin ships
only the spec, the validator and `scripts/providers/wonderwp.reference.json`).
Normative schema: `rules/provider-wonderwp.md`.

| Field | Required | Meaning |
|---|---|---|
| `$type` / `$provider` / `$version` | yes | `"provider-config"` / `"wonderwp"` / `1` — validation gate |
| `themePath` | yes | Theme root. Absolute, or relative to the project root (cwd). Must exist — the provider installs INTO a theme, never scaffolds one. |
| `php.namespace` | yes | Namespace of the generated classes (e.g. `WonderWp\Theme\DesignSystem`). Sub-namespaces `Atoms` / `Molecules` are appended per layer. |
| `php.textdomain` | no | Reserved for the patterns route (iteration 2). |
| `routes.themeJson` | yes | Fill `{theme}/theme.json` (settings + styles.blocks autowire). |
| `routes.tokensCss` | yes | Copy the `--ds-*` token CSS into the theme. |
| `routes.componentsCss` | yes | One BEM stylesheet per component into the theme. |
| `routes.phpComponents` | yes | Generate the PHP component layer. |
| `routes.blockStyles` / `.patterns` / `.customBlocks` | yes | **Reserved — must be `false` in v1** (validated). |
| `contracts` | no | Contracts root relative to `{ds}` (default `components`). |
| `vocabulary` | no | Target vocabulary (default `""` = the design system's own, framework-agnostic one). Set it and every component declaring a co-located `contract.<vocabulary>.remap.json` is loaded in that vocabulary; the others keep their native identity. No transformed contracts copy to point at. `--vocabulary` overrides it per run. |
| `mappings.html` | no | `null` → plugin reference `to-html.json` (the same mapping as `template.html` / the styleguide); or a path **relative to `{ds}`** for a project-owned mapping. |
| `mappings.themeJsonVariant` | no | `"vars"` (default) or `"literal"` — picks `to-theme-json-<variant>.json`. The vars variant requires the token CSS enqueued in editor + front. |
| `paths.themeJson` | no | theme.json location relative to `themePath` (default `theme.json`). |
| `paths.designSystem` | no | Root of the installed design system (default `design-system`) — hosts `Component.php` / `AbstractComponent.php`, `tokens/`, and one self-contained folder per component (`<layer>/<slug>/` with PHP + CSS + future JS). |

Validation: `scripts/lib/providers/validate-provider.mjs` — pure, collects
every error at once; the façade and the generators refuse an invalid config
before any write (exit 2).
