<?php

namespace [PluginNamespace]\Hooks;

use WonderWp\Component\Hook\Traits\HasHookManager;
use WonderWp\Component\Hook\Traits\HasHookManagerInterface;
use WonderWp\Component\PluginSkeleton\Service\RegistrableInterface;

class BlockEditorAssetsEnqueuer implements RegistrableInterface, HasHookManagerInterface
{
    use HasHookManager;

    public function register()
    {
        $this->addAction('enqueue_block_editor_assets', [$this, 'enqueueBlockEditorAssets']);
    }

    public function enqueueBlockEditorAssets(): void
    {
        $pluginRoot = plugin_dir_path(dirname(dirname(__FILE__)));
        $buildPath = $pluginRoot . 'build/';
        $buildUrl = plugin_dir_url($pluginRoot) . 'build/';

        wp_enqueue_script(
            '[plugin-slug]-block-editor',
            $buildUrl . 'js/block-editor.js',
            ['wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-hooks'],
            filemtime($buildPath . 'js/block-editor.js'),
            true
        );

        wp_enqueue_style(
            '[plugin-slug]-block-editor',
            $buildUrl . 'css/block-editor.css',
            [],
            filemtime($buildPath . 'css/block-editor.css')
        );
    }
}
