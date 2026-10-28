<?php

namespace MyPluginNamespace\PostTypes;

use WonderWp\Component\CPT\Traits\HasCustomTypeDefinitions;
use WonderWp\Component\CPT\Traits\HasCustomTypeDefinitionsInterface;
use WonderWp\Component\CPT\Traits\HasLabels;
use WonderWp\Component\CPT\Traits\HasLabelsInterface;
use WonderWp\Component\CPT\Traits\HasSupportInterface;
use WonderWp\Plugin\Core\Framework\AbstractPlugin\PostTypes\AbstractCustomPostType;

class MyPostType extends AbstractCustomPostType implements HasCustomTypeDefinitionsInterface, HasLabelsInterface
{
    use HasCustomTypeDefinitions;
    use HasLabels;

    public static function provideKey(): string
    {
        return 'my-cpt-key';
    }

    public static function provideArgs(): array
    {
        return [
            'labels' => static::provideLabels(),
            'public' => true,
            'hierarchical' => false,
            'has_archive' => false,
            'show_in_rest' => true,
            'show_in_admin_bar' => true,
            'menu_icon' => 'my-icon',
            'supports' => [
                HasSupportInterface::TITLE,
                HasSupportInterface::EDITOR,
                HasSupportInterface::THUMBNAIL,
                HasSupportInterface::EXCERPT,
                HasSupportInterface::CUSTOM_FIELDS
            ],
            'rewrite' => [
                'slug' => 'my-cpt-slug'
            ],
        ];
    }

    public static function provideLabels(): array
    {
        static::$labels = [
            'name' => __('myCptPluralLabel', MY_PLUGIN_TEXTDOMAIN),
            'singular_name' => __('myCptSingleLabel', MY_PLUGIN_TEXTDOMAIN),
            'add_new_item' => __('Add a myCptSingleLabel', MY_PLUGIN_TEXTDOMAIN),
            'edit_item' => __('Edit a myCptSingleLabel', MY_PLUGIN_TEXTDOMAIN),
            'search_items' => __('Search a myCptSingleLabel', MY_PLUGIN_TEXTDOMAIN),
            'not_found' => __('No myCptSingleLabel found', MY_PLUGIN_TEXTDOMAIN),
        ];

        return static::getLabels();
    }
}
