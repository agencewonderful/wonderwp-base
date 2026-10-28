<?php

namespace WonderWp\Plugin\Bp\Child;

use WonderWp\Component\DependencyInjection\Container;
use WonderWp\Plugin\Bp\BpManager;

class BpThemeManager extends BpManager
{
    public function register(Container $container)
    {
        parent::register ($container);

        return $this;
    }
}