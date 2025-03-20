<?php if (!\WonderWp\Functions\isAjax()): ?>
<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package    WordPress
 * @subpackage WonderWp_theme
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width">

    <title><?php
        /*
         * Print the <title> tag based on what is being viewed.
         */
        global $page, $paged;

        $pageTitle = stripslashes(wp_title('|', false, 'right'));
        $pageTitle = str_replace(['|'], [' '], $pageTitle);
        if (!empty($pageTitle)) {
            echo $pageTitle . ' | ';
        }

        // Add the blog name.
        echo get_bloginfo('name');

        // Add the blog description for the home/front page.
        if (is_home() || is_front_page()) {
            $site_description = get_bloginfo('description', 'display');
            if ($site_description) {
                echo " | $site_description";
            }
        }

        // Add a page number if necessary:
        if ($paged >= 2 || $page >= 2) {
            echo ' | ' . sprintf(__('Page %s', 'twentyten'), max($paged, $page));
        }

        ?></title>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php endif; /* isAjax */ ?>

<div class="wp-site-blocks">
    <div class="wp-block-template-part">
        <?php
        $headerContentFile = locate_template('parts/header.html');
        if (!empty($headerContentFile)) {
            $headerContent = file_get_contents($headerContentFile);
            if (!empty($headerContent)) {
                echo do_blocks($headerContent);
            }
        }
        ?>
