<?php

namespace WonderWp\Plugin\Map\Child;

use WonderWp\Component\DependencyInjection\Container;
use WonderWp\Plugin\Map\MapManager;

class MapThemeManager extends MapManager
{
    public function register(Container $container)
    {
        parent::register ($container);

        return $this;
    }
}