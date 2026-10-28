<?php

namespace [PluginNamespace]\Hooks;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use WonderWp\Component\Hook\Traits\HasHookManager;
use WonderWp\Component\Hook\Traits\HasHookManagerInterface;
use WonderWp\Component\PluginSkeleton\Service\RegistrableInterface;

class Register[Collection]IconCollectionHook implements RegistrableInterface, HasHookManagerInterface
{
    use HasHookManager;

    private const ICONS_DIR = '[relative/path/to/icons]'; // ex. assets/icons/rec
    private const TEXT_DOMAIN = '[text-domain]';

    public function register(): void
    {
        $this->addAction('init', [$this, 'registerIconCollection']);
    }

    public function registerIconCollection(): void
    {
        if (!function_exists('wp_register_icon_collection')) {
            return;
        }

        // One folder = one collection. Call multiple times for multiple collections.
        $this->loadCollectionFromPath([PluginNamespace]::PATH . self::ICONS_DIR);
        // $this->loadCollectionFromPath([PluginNamespace]::PATH . 'assets/icons', 'rec');
    }

    /**
     * Register one icon collection from an absolute directory of SVG files (recursive).
     * Collection slug defaults to the directory basename; pass $collectionSlug to override.
     *
     * @throws \RuntimeException If the directory is missing or contains no SVG files.
     */
    protected function loadCollectionFromPath(string $iconsDir, ?string $collectionSlug = null): void
    {
        if (!is_dir($iconsDir)) {
            throw new \RuntimeException(sprintf(
                'Icon collection directory does not exist: %s',
                $iconsDir
            ));
        }

        $collectionSlug  = $this->formatSlug($collectionSlug ?? basename(rtrim($iconsDir, '/\\')));
        $collectionLabel = __(ucfirst($collectionSlug), self::TEXT_DOMAIN);

        wp_register_icon_collection($collectionSlug, [
            'label'       => $collectionLabel,
            'description' => sprintf(
                __('Icons from %s', self::TEXT_DOMAIN),
                $collectionLabel
            ),
        ]);

        $svgFiles = $this->discoverSvgFiles($iconsDir);
        $svgFiles = apply_filters(
            static::class . '/svg_files',
            $svgFiles,
            $collectionSlug,
            $iconsDir
        );

        if ($svgFiles === []) {
            throw new \RuntimeException(sprintf(
                'Icon collection directory contains no SVG files: %s',
                $iconsDir
            ));
        }

        foreach ($svgFiles as $filePath) {
            $iconSlug = $this->formatSlug($this->iconSlugFromPath($iconsDir, $filePath));

            wp_register_icon($collectionSlug . '/' . $iconSlug, [
                'label'     => __(ucfirst($iconSlug), self::TEXT_DOMAIN),
                'file_path' => $filePath,
            ]);
        }
    }

    /**
     * @return list<string> Absolute paths to SVG files, sorted.
     */
    protected function discoverSvgFiles(string $iconsDir): array
    {
        $svgFiles = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($iconsDir, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (!$file->isFile() || strtolower($file->getExtension()) !== 'svg') {
                continue;
            }

            $svgFiles[] = $file->getPathname();
        }

        sort($svgFiles);

        return $svgFiles;
    }

    /**
     * Build a flat icon slug from a file path relative to the collection root.
     * Root-level `foo.svg` → `foo`; nested `line/foo.svg` → `line-foo`.
     */
    protected function iconSlugFromPath(string $iconsDir, string $filePath): string
    {
        $base = rtrim(str_replace('\\', '/', $iconsDir), '/');
        $path = str_replace('\\', '/', $filePath);
        $relative = ltrim(substr($path, strlen($base)), '/');
        $relative = preg_replace('/\.svg$/i', '', $relative) ?? $relative;

        return str_replace('/', '-', $relative);
    }

    /**
     * Normalize a collection or icon slug for the WordPress Icon Registration API.
     * Rules: lowercase only; start/end with letter or digit; hyphens and underscores allowed inside.
     *
     * @throws \RuntimeException If the slug is empty after normalization.
     */
    protected function formatSlug(string $slug): string
    {
        $original = $slug;
        $slug = strtolower($slug);
        $slug = preg_replace('/[\s\/\\\\]+/', '-', $slug) ?? $slug;
        $slug = preg_replace('/[^a-z0-9_-]+/', '-', $slug) ?? $slug;
        $slug = preg_replace('/-+/', '-', $slug) ?? $slug;
        $slug = preg_replace('/_+/', '_', $slug) ?? $slug;
        $slug = trim($slug, '-_');

        if ($slug === '') {
            throw new \RuntimeException(sprintf(
                'Icon slug could not be normalized: %s',
                $original
            ));
        }

        return $slug;
    }
}
