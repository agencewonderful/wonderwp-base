<?php

namespace MyPluginNamespace\BlockBindingsSources;

use WonderWp\Component\BlockBindingsSource\Definition\AbstractBlockBindingsSource;
use WonderWp\Component\BlockBindingsSource\Traits\HasBlockBindingsSourceDefinitions;
use WonderWp\Component\BlockBindingsSource\Traits\HasBlockBindingsSourceDefinitionsInterface;
use WonderWp\Component\PluginSkeleton\ManagerAwareInterface;
use WonderWp\Component\PluginSkeleton\ManagerAwareTrait;

class MyMetaFieldSource extends AbstractBlockBindingsSource implements HasBlockBindingsSourceDefinitionsInterface, ManagerAwareInterface
{
    use HasBlockBindingsSourceDefinitions;
    use ManagerAwareTrait;

    public static function provideSourceName(): string
    {
        return 'my-source-namespace/my-source-key';
    }

    public function provideSourceProperties(): array
    {
        return [
            'label' => __('My source label', 'my-plugin-textdomain'),
            'get_value_callback' => [$this, 'getValue'],
            'uses_context' => ['postId', 'postType'],
        ];
    }

    /**
     * Usage (with meta key):
     * {"metadata":{"bindings":{"content":{"source":"my-source-namespace/my-source-key","args":{"key":"my-key-name"}}}}}
     */
    public function getValue(array $sourceArgs, $blockInstance, string $attributeName): ?string
    {
        if (empty($sourceArgs['key'])) {
            return null;
        }

        if (empty($blockInstance->context['postId'])) {
            return null;
        }

        $postId = $blockInstance->context['postId'];

        $repository = $this->getManager()->getService(\WonderWp\Component\Service\ServiceInterface::REPOSITORY_SERVICE_NAME);
        $post = $repository->find($postId);

        if (!$post instanceof \WP_Post) {
            return null;
        }

        $metaKey = $sourceArgs['key'];
        $value = $repository->getPostMeta($post, $metaKey);

        return apply_filters('my-plugin/my-source-namespace/my-source-key/get_value', $value, $sourceArgs, $blockInstance, $attributeName);
    }
}
