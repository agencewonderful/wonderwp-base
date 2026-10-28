# Token families, allowed `$type`s, and naming

The authoritative per-family `$type` allow-list lives in
`scripts/lib/constants.mjs` (`TYPE_ALLOWLIST`) and is enforced by the
`validate-tokens` skill. The table below is the human-readable view used when
inferring a family + `$type` from a raw value. If the two ever diverge,
`constants.mjs` wins.

## Families and allowed `$type`s

This table mirrors `TYPE_ALLOWLIST` exactly — the parity test
`tests/token-families-parity.test.mjs` fails the build if they ever diverge.

| Family | Allowed `$type`s |
|---|---|
| color | `color`, `gradient` (composite) |
| typography | `fontFamily`, `fontWeight`, `dimension`, `number`, `typography` (composite) |
| spacing | `dimension` |
| sizing | `dimension`, `number` |
| border | `dimension`, `strokeStyle` |
| shadow | `shadow` (composite), `gradient`, `dimension` |
| animation | `duration`, `cubicBezier`, `transition` (composite) |
| opacity | `number` |
| layout | `dimension` |

**DTCG defines no per-property `$type`s** (no fontSize, lineHeight,
letterSpacing, fontStyle, borderWidth, borderRadius). The CSS property lives
in the token **path** (`typography.size.lg`, `border.radius.md`); the `$type`
stays DTCG: font-size / letter-spacing / border width / border radius →
`dimension`; unitless line-height → `number`; border style → `strokeStyle`.

## Naming conventions

| Family | Path pattern | Example |
|---|---|---|
| color | `color.<shade>.<weight>`, `color.gradient.<name>` | `color.blue.500`, `color.gradient.primary` |
| typography | `typography.<property>.<scale>` | `typography.size.lg` |
| spacing | `spacing.<scale>` | `spacing.xl` |
| sizing | `sizing.<context>.<scale>` | `sizing.icon.md` |
| border | `border.<property>.<scale>` | `border.radius.lg` |
| shadow | `shadow.<intensity>` | `shadow.md` |
| layout | `layout.<mesure>` | `layout.contentSize`, `layout.wideSize`, `layout.breakpoint.mobile` |
| animation | `animation.<property>.<scale>` | `animation.duration.fast` |
| opacity | `opacity.<value>` | `opacity.50` |

Aliases use DTCG reference syntax: `"$value": "{color.primary.blue.500}"`.

## Value → family / `$type` inference

| Value shape | Family | `$type` |
|---|---|---|
| Hex / `rgb()` / `hsl()` | color | `color` |
| `linear-gradient(...)` / `radial-gradient(...)` | color | `gradient` |
| `px` / `rem` / `%` dimension | spacing or sizing (ask user which) | `dimension` |
| Font family string | typography | `fontFamily` |
| Numeric or named font weight | typography | `fontWeight` |
| Font size / letter-spacing (`px`/`rem`/`em`) | typography | `dimension` |
| Unitless line-height | typography | `number` |
| `ms` / `s` duration | animation | `duration` |
| `cubic-bezier(...)` | animation | `cubicBezier` |
| `0`–`1` decimal or `%` opacity | opacity | `number` |
| Shadow shorthand (`0px 4px 8px rgba(...)`) | shadow | `shadow` |
| Border width / radius | border | `dimension` |
| Border style (`solid`, `dashed`, …) | border | `strokeStyle` |
| DTCG reference `{...}` | alias — keep `$type` matching the referenced token | (referenced) |
