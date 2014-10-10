<?php
function sb_theme_lib_style_and_script() {
    wp_register_script('superfish', SB_THEME_LIB_URL . '/superfish/js/superfish.min.js', array('jquery', 'hoverIntent'), false, true);
    wp_register_script('supersubs', SB_THEME_LIB_URL . '/superfish/js/supersubs.js', array('superfish'), false, true);
    wp_enqueue_script('supersubs');

    wp_register_style('superfish-style', SB_THEME_LIB_URL . '/superfish/css/superfish.css');
    wp_register_style('superfish-vertical-style', SB_THEME_LIB_URL . '/superfish/css/superfish-vertical.css', array('superfish-style'));
    wp_register_style('superfish-navbar-style', SB_THEME_LIB_URL . '/superfish/css/superfish-navbar.css', array('superfish-vertical-style'));
    wp_enqueue_style('superfish-navbar-style');
}
add_action('wp_enqueue_scripts', 'sb_theme_lib_style_and_script');