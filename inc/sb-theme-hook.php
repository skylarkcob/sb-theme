<?php
do_action('sb_theme_setup');

if(sb_theme_support_shop()) {
    require SB_THEME_INC_PATH . '/class-sb-product.php';
}

if(sb_theme_support_addthis()) {
    function sb_theme_addthis_config() {
        include SB_THEME_LIB_PATH . '/addthis/config.php';
    }
    add_action('wp_footer', 'sb_theme_addthis_config');
}