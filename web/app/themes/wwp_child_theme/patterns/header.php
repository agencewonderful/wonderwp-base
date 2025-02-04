<?php
/**
 * Title: Header
 * Slug: wwp_child_theme/header
 * Categories: header
 */
if(isEditorContext()){
    echo 'Header';
} else {
    get_header();
}

