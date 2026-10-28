<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
/** @var array    $attributes */
/** @var string   $content */
/** @var WP_Block $block */

$markup = apply_filters(
  '<the-block-namespace>/<the-block-name>/markup',
  'Please provide the markup for the <the-block-namespace>/<the-block-name> block.',
  $attributes,
  $content,
  $block
);

if (empty($markup)) {
    return;
}
?>

<div <?php echo get_block_wrapper_attributes(); ?>>
    <?php echo $markup; ?>
</div>
