<?php

namespace [PluginNamespace]\Hooks;

use WonderWp\Component\Hook\Traits\HasHookManager;
use WonderWp\Component\Hook\Traits\HasHookManagerInterface;
use WonderWp\Component\PluginSkeleton\Service\RegistrableInterface;

class [HookName] implements RegistrableInterface, HasHookManagerInterface
{
    use HasHookManager;

    public function register(): void
    {
        $this->addFilter('[wordpress-hook-name]', [$this, 'on[HookName]']);
    }

    /**
     * Simple hook: inline glue only (typically ≤10 lines, no I/O, no service delegation).
     */
    public function on[HookName](array $items): array
    {
        // Example: append a block category, tweak an array, early return.
        return $items;
    }
}
