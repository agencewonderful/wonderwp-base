<?php
/**
 * Title: Footer
 * Slug: wwp_child_theme/footer
 * Categories: footer
 */
if (isEditorContext()) {
    echo 'Footer';
} else {
    get_footer();
}
