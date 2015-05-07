<?php
function sb_theme_create_post_types_and_taxonomies_hook() {
    if(SB_Option::use_administrative_boundaries()) {
        SB_Core::create_administrative_boundaries_taxonomy();
    }
}
add_action('sb_theme_post_types_and_taxonomies', 'sb_theme_create_post_types_and_taxonomies_hook');