<?php

namespace [PluginNamespace]\Hooks;

use WonderWp\Component\Hook\Traits\HasHookManager;
use WonderWp\Component\Hook\Traits\HasHookManagerInterface;
use WonderWp\Component\PluginSkeleton\Service\RegistrableInterface;
use WonderWp\Plugin\GutenbergUtils\Extensions\FilteredQueryLoopByQueryIdentifier\FilteredQueryLoopByQueryIdentifierInterface;
use WonderWp\Plugin\GutenbergUtils\Extensions\FilteredQueryLoopByQueryIdentifier\FilteredQueryLoopByQueryIdentifierTrait;

class [HookName]QueryLoopHook implements RegistrableInterface, HasHookManagerInterface, FilteredQueryLoopByQueryIdentifierInterface
{
    use HasHookManager;
    use FilteredQueryLoopByQueryIdentifierTrait;

    const TARGETED_QUERY_IDENTIFIER = '[query-identifier]';

    public function register()
    {
        $postTypesToFilter = apply_filters('[HookName]QueryLoopHook/postTypesToFilter', [/* post types */]);
        $this->registerRestQueryLoopByQueryIdentifierFiltering($postTypesToFilter);

        $this->registerBlockQueryLoopByQueryIdentifierFiltering();
    }

    /** @inheritDoc */
    public function filterRestQueryVars(array $args, \WP_REST_Request $request): array
    {
        // Editor-side REST filtering logic
        return $args;
    }

    /** @inheritDoc */
    public function filterBlockQueryVars(array $query, \WP_Block $block): array
    {
        // Frontend query filtering logic
        return $query;
    }
}
