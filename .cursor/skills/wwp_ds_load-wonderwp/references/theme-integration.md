# Theme integration (v1 — the theme owns the wiring)

No loader is generated in v1 («composants seuls»): the theme requires the
classes and enqueues the CSS itself. Minimal wiring in `functions.php`:

```php
// ── Design-system components (generated — see load-wonderwp skill) ──
// The contracts (interfaces) and capability traits — no base class, each
// component composes exactly the capabilities its contract calls for:
$ds = get_stylesheet_directory() . '/design-system';
foreach (['Component', 'Hydratable', 'Variantable', 'Hydration', 'Markupable', 'Variations'] as $base) {
    require_once $ds . '/' . $base . '.php';
}
// One per generated component (or register your own autoloader for the
// WonderWp\Theme\DesignSystem\ prefix → design-system/{atoms|molecules}/…):
require_once $ds . '/atoms/button/Button.php';
require_once $ds . '/molecules/card-article/CardArticle.php';

// ── Design-system CSS (tokens first — everything references --ds-*) ──
// One folder per component: the CSS sits next to the PHP class.
add_action('wp_enqueue_scripts', static function (): void {
    $base = get_stylesheet_directory_uri() . '/design-system';
    wp_enqueue_style('ds-tokens', $base . '/tokens/_index.css', [], null);
    wp_enqueue_style('ds-button', $base . '/atoms/button/button.css', ['ds-tokens'], null);
    wp_enqueue_style('ds-card-article', $base . '/molecules/card-article/card-article.css', ['ds-tokens'], null);
});

// The editor needs the token CSS too when theme.json uses the vars variant:
add_action('enqueue_block_editor_assets', static function (): void {
    wp_enqueue_style('ds-tokens-editor', get_stylesheet_directory_uri() . '/design-system/tokens/_index.css', [], null);
});
```

## Consuming a component

```php
use WonderWp\Theme\DesignSystem\Atoms\Button;
use WonderWp\Theme\DesignSystem\Atoms\Heading;
use WonderWp\Theme\DesignSystem\Molecules\CardArticle;

// Fluent style (preferred): each call sets one thing, each mechanism is
// explicit — data, variant, attribute.
echo (new Button())                             // PHP 8.4+: new Button()->… works unwrapped
    ->label(__('Get started', 'my-theme'))      // declared data key (IDE: generated @method)
    ->variant('dark-blue')                      // by value — axis inferred from the declaration
    ->href('/signup')                           // anything else → root HTML attribute
    ->ariaLabel(__('Sign up', 'my-theme'))      // camelCase → aria-label
    ->getMarkup();

// Axis-explicit selection — the generated shortcut (a REAL method on the
// class, IDE-visible), or variant(value, axis) for the generic form:
echo (new Button())->style('dark-blue')->label('Hi')->getMarkup();
echo (new Button())->variant('dark-blue', 'style')->label('Hi')->getMarkup();

// Bulk data: one array — handy in CMS loops. A bare string is the
// primary-text shorthand (the first data key the constructor declares).
// data() is DATA only: variants and attributes never transit through it.
echo (new Button())->data(['label' => __('Get started', 'my-theme')])->getMarkup();
echo (new Button())->data(__('Get started', 'my-theme'))->getMarkup();

// Molecule: children are TYPED properties instantiated BARE in the generated
// constructor (the single readable source). Components instantiate EMPTY —
// no contract sample content ships: anything you don't set renders empty.
// Typed getters expose each child's full fluent API with real IDE
// autocompletion; typed setters replace a child (type-checked against the
// contract).
$card = new CardArticle();
$card->getMedia()->src(get_the_post_thumbnail_url());
$card->getHeading()->text(get_the_title());      // Heading's text part is 'text' → fluent __call
$card->getCta()->label(__('Read more', 'my-theme'))->variant('dark-blue');
echo $card->getMarkup();

// Replacing a default child entirely:
echo (new CardArticle())
    ->setCta((new Button())->label(__('Buy now', 'my-theme')))
    ->getMarkup();

// Reading bag data back: ->getData('src'), or the fluent no-arg alias:
$card->getMedia()->src();          // 'https://…' — ->src() === ->getData('src')
```

API summary (see the generated `@method` annotations for each component):

- **Components instantiate EMPTY** — the generated constructor only DEFINES
  the component: its data shape (text-part keys seeded empty — the declared
  keys route the fluent accessors) and its bare composition. No contract
  sample content ever ships: a forgotten setter renders empty, visibly.
- **Fluent accessors** — any undefined method maps to the matching property
  via `__call` (camelCase → kebab-case): one scalar argument = setter
  (`->href('…')`), **no argument = getter**. Routing by DECLARATION: declared
  data keys hit the data bag, anything else is a root HTML attribute. Known
  text parts are advertised with generated `@method` annotations for IDE
  autocompletion. `->attr($name, $value)` is the explicit escape hatch;
  `->addClass('tw-flex')` (or `->attr('class', 'tw-flex')`) merges extra
  classes — the generated markup defines its own base class the same way
  (`addClass('ds-button')`), so user classes are preserved, never duplicated.
- **Variants are an explicit, separate mechanism** (never through `data()` or
  `__call`): `->variant('dark-blue')` selects by **value** (axis inferred
  from the declaration; unknown values throw listing the choices),
  `->variant('dark-blue', 'style')` is the axis-explicit form, and **each
  axis gets a generated REAL shortcut method** — `->style('dark-blue')`,
  `->orientation('horizontal')` — aliases of the axis-explicit form
  (IDE-visible, no magic). `->getVariant('style')` reads the selection.
- **Children (molecules)** — typed properties instantiated BARE in the
  generated constructor (the single readable source), with **typed
  accessors**: `getMedia(): Thumbnail` / `setMedia(Thumbnail $media)`. Wrong
  names are PHP errors caught by the IDE — no runtime slot registry.
- `->data(array|string $data)` — bulk DATA entry point (constructors take no
  arguments — data flows through the fluent API): an **array** merges the
  property → value bag (declared text keys inject escaped text); variants
  and attributes never transit through `data()`. A **string** is the
  primary-text shorthand (first declared data key); a component with no text
  part throws a clear `InvalidArgumentException` (its content lives in its
  typed children).
- **Attributes & variants surface** — `getAttributes(): array` (the full
  name → value map, 'class' included) / `setAttributes(array)` /
  `addClass(...$classes)`; the markup renders everything through ONE
  `getFormattedAttributes()` emission (existing classes merged with the variant
  contribution — empty by default — first, then every attribute).
  `variants()` / `defaultVariant()` read the constructor's defines
  (`declareVariants()` + `useVariant()` — the classless default is EXPLICIT
  and selected from instantiation: `->getVariant('orientation')` reads
  `'vertical'` before any setter runs; without `useVariant()` there is no
  default and every selection emits its class), plus the public
  `variantClasses()`.
- `->getMarkup()` — the component's pure HTML (`ds-*` classes, data escaped
  and injected — the same shape as `template.html` / the styleguide, styled
  by the co-located CSS); the theme echoes it. Components are
  framework-independent — the WordPress glue (patterns, blocks, decoration)
  is a separate layer that consumes them.

Outside WordPress every helper falls back to pure PHP and `getMarkup()`
returns the serialized markup verbatim — handy for unit tests.
