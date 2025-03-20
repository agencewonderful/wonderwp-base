<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package    WordPress
 * @subpackage WonderWp_theme
 */
?>

    </div><!-- .wp-block-template-part -->
</div><!-- .wp-site-blocks -->

<?php if (!\WonderWp\Functions\isAjax()): ?>

    <?php
    $footerContentFile = locate_template('parts/footer.html');
    if(!empty($footerContentFile)){
        $footerContent = file_get_contents($footerContentFile);
        if(!empty($footerContent)){
            echo '<footer class="site-footer wp-block-template-part">'.do_blocks($footerContent).'</footer>';
        }
    }
    ?>

    </div><!-- .site -->

    <?php do_action('wwp_after_footer'); ?>

    <?php wp_footer(); ?>

    </body>
    </html>
<?php endif; /* isAjax */ ?>
