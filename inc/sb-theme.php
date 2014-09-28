<?php
function sb_theme_script_and_style() {
    $file = get_template_directory() . "/sb-custom/sb-custom-style.css";
    if(file_exists($file)) {
        wp_register_style("sb-custom-style", get_template_directory_uri() . "/sb-custom/sb-custom-style.css");
        wp_enqueue_style("sb-custom-style");
    }
    $file = get_template_directory() . "/sb-custom/sb-custom-script.js";
    if(file_exists($file)) {
        wp_register_script("sb-custom", get_template_directory_uri() . "/sb-custom/sb-custom-script.js", array("jquery"), false, true);
        wp_localize_script( 'sb-custom', 'sb_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
        wp_enqueue_script("sb-custom");
    }
}
add_action("wp_enqueue_scripts", "sb_theme_script_and_style");

$file = get_template_directory() . "/sb-custom/sb-custom-functions.php";
if(file_exists($file)) {
    require get_template_directory() . "/sb-custom/sb-custom-functions.php";
}

function sb_theme_default_setup() {
    add_theme_support("post-thumbnails");
}
add_action( 'after_setup_theme', 'sb_theme_default_setup' );

function sb_theme_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'sb_theme_excerpt_more');

function sb_theme_add_dotted_into_excerpt($excerpt) {
    $excerpt = strip_tags($excerpt);
    return $excerpt;
}
//add_filter( 'the_excerpt', 'sb_theme_add_dotted_into_excerpt' );