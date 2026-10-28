<?php

namespace [PluginNamespace]\Hooks;

use [PluginNamespace]\Services\FrontMapper;
use [PluginNamespace]\Repositories\PostRepository;
use WonderWp\Component\Hook\Traits\HasHookManager;
use WonderWp\Component\Hook\Traits\HasHookManagerInterface;
use WonderWp\Component\PluginSkeleton\Service\RegistrableInterface;
use WonderWp\Component\PluginSkeleton\ManagerAwareInterface;
use WonderWp\Component\PluginSkeleton\ManagerAwareTrait;

class MapBlocksOnComponents implements RegistrableInterface, HasHookManagerInterface, ManagerAwareInterface
{
    use HasHookManager;
    use ManagerAwareTrait;

    protected FrontMapper $frontMapper;
    protected PostRepository $postRepository;

    public function wire(): void
    {
        $this->frontMapper = $this->manager->getService($this->manager::FRONT_MAPPER_SERVICE);
        $this->postRepository = $this->manager->getService(\WonderWp\Component\Service\ServiceInterface::REPOSITORY_SERVICE_NAME);
    }

    public function register()
    {
        $this->wire();
        $this->addFilter('<the-block-namespace>/<the-block-name>/markup', [$this, 'mapQueryLoopPostCardBlock'], 10, 4);
    }

    public function mapQueryLoopPostCardBlock(string $markup, array $attributes, string $content, \WP_Block $block): string
    {
        $postId = $block->context['postId'] ?? null;
        if (!$postId) {
            return '';
        }

        $post = $this->postRepository->find($postId);
        if (!$post instanceof \WP_Post) {
            return '';
        }

        $mappedComponent = $this->frontMapper->mapQueryLoopPostCardBlock($post, $this->postRepository);
        if (!$mappedComponent) {
            return '';
        }

        return $mappedComponent->getMarkup();
    }
}
