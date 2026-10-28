<?php

namespace [PluginNamespace]\Services;

use [PluginNamespace]\Repositories\PostRepository;
use WonderWp\Theme\Core\Component\AbstractComponent;

class FrontMapper
{
    public function mapQueryLoopPostCardBlock(\WP_Post $post, PostRepository $postRepository): ?AbstractComponent
    {
        $dataToMap = $this->getDataToMap($post, $postRepository);
        // Build and return an AbstractComponent using $dataToMap
        return null;
    }

    protected function getDataToMap(\WP_Post $post, PostRepository $postRepository): array
    {
        $data = [];

        $data['title'] = stripslashes($postRepository->getPostTitle($post));
        $imgSize = !empty($attributes['defaultImgSize']) ? $attributes['defaultImgSize'] : 'thumbnail';
        $data['img'] = $postRepository->getFeaturedImage($post, $imgSize, false, ['itemprop' => 'image']);
        $data['link'] = $postRepository->getPostLink($post);
        $data['excerpt'] = $postRepository->getPostExcerpt($post);
        $data['date'] = $postRepository->getPostDate($post, 'd/m/Y');

        return $data;
    }
}
