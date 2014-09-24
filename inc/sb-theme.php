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
        wp_enqueue_script("sb-custom");
    }
}
add_action("wp_enqueue_scripts", "sb_theme_script_and_style");

$file = get_template_directory() . "/sb-custom/sb-custom-functions.php";
if(file_exists($file)) {
    require get_template_directory() . "/sb-custom/sb-custom-functions.php";
}