<?php

namespace MyPluginNamespace\BlockBindingsSources;

use WonderWp\Component\BlockBindingsSource\Definition\AbstractBlockBindingsSource;
use WonderWp\Component\BlockBindingsSource\Traits\HasBlockBindingsSourceDefinitions;
use WonderWp\Component\BlockBindingsSource\Traits\HasBlockBindingsSourceDefinitionsInterface;

class MySourceNameSource extends AbstractBlockBindingsSource implements HasBlockBindingsSourceDefinitionsInterface
{
    use HasBlockBindingsSourceDefinitions;

    public static function provideSourceName(): string
    {
        return 'my-source-namespace/my-source-key';
    }

    public function provideSourceProperties(): array
    {
        return [
            'label' => __('My source label', 'my-plugin-textdomain'),
            'get_value_callback' => [$this, 'getValue'],
        ];
    }

    /**
     * @param array       $sourceArgs
     * @param mixed       $blockInstance
     * @param string      $attributeName
     * Usage (without args):
     * {"metadata":{"bindings":{"content":{"source":"my-source-namespace/my-source-key"}}}}
     *
     * @return ?string
     */
    public function getValue(array $sourceArgs, $blockInstance, string $attributeName): ?string
    {
        $value = 'Hello from my source label';

        return apply_filters(
            'my-plugin/my-source-namespace/my-source-key/get_value',
            $value,
            $sourceArgs,
            $blockInstance,
            $attributeName
        );
    }
}
