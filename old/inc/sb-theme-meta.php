<?php
function sb_theme_term_meta_thumbnail($taxonomies = array()) {
    $fields = array(
        array(
            'name' => 'thumbnail',
            'type' => 'image_url'
        )
    );
    $args = array(
        'taxonomies' => $taxonomies,
        'callback' => 'sb_theme_meta_term_thumbnail_callback',
        'fields' => $fields
    );
    $term_meta = new SB_Term_Meta($args);
}

function sb_theme_meta_term_thumbnail_callback( $term ) {
    sb_term_meta_nonce();
    $value = SB_Term::get_meta($term->term_id, $term->taxonomy, 'thumbnail');
    $args = array(
        'id' => 'sb_category_thumbnail',
        'label' => __('Thumbnail', 'sb-theme'),
        'name' => 'thumbnail',
        'value' => $value,
        'description' => __('You can enter url or upload new image file.', 'sb-theme')
    );
    SB_Term_Field::image_upload($args);
}