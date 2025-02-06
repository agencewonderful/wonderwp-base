<?php

namespace WonderWp\Theme\Child\Service;

use WonderWp\Component\Asset\AssetEnqueuerInterface;
use WonderWp\Component\DependencyInjection\Container;
use WonderWp\Theme\Core\Service\AssetManipulatorService;

class ChildThemeAssetsManipulator extends AssetManipulatorService
{
    public function enqueueStyleGuideStyles()
    {
        $container = Container::getInstance();
        if ($container->offsetExists('wwp.asset.enqueuer')) {
            /** @var AssetEnqueuerInterface $assetsEnqueuer */
            $assetsEnqueuer = $container->offsetGet('wwp.asset.enqueuer');
            $assetsEnqueuer->enqueueStyleGroup('styleguide');
            wp_print_styles('wwp_modern_styleguide');
        }
    }
    public function enqueueStyleGuideJavaScripts()
    {
        $container = Container::getInstance();
        if ($container->offsetExists('wwp.asset.enqueuer') && $container->offsetExists('wwp.asset.frontgroups')) {
            $groupNames     = $container->offsetGet('wwp.asset.frontgroups');
            $assetsEnqueuer = $container->offsetGet('wwp.asset.enqueuer');

            $assetsEnqueuer->enqueueScriptGroups($groupNames);
            wp_print_scripts();
        }
    }

    /**
     * Enqueues individual block stylesheets.
     *
     * @since 1.0
     */
    public function enqueueBlockStyles(): void
    {
        $themeBlockStyles = $this->getThemeBlockStyles('/assets/final/theme/css/blocks');
        if (!empty($themeBlockStyles)) {
            foreach ($themeBlockStyles as $blockName => $styleAttributes) {
                // Enqueue individual block stylesheets.
                wp_enqueue_block_style(
                    $blockName,
                    $styleAttributes
                );
            }
        }
    }
}
