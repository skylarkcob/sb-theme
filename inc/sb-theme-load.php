<?php
require SB_THEME_INC_PATH . '/sb-theme-constant.php';

require SB_THEME_INC_PATH . '/sb-theme-install.php';

require SB_THEME_INC_PATH . '/sb-theme-functions.php';

if(sb_theme_support_term_meta() || SB_Option::utility_enabled('term_meta')) {
    if(!class_exists('SB_Term_Meta')) {
        require SB_CORE_INC_PATH . '/class-sb-term-meta.php';
    }
    if(!class_exists('SB_Term_Field')) {
        require SB_CORE_INC_PATH . '/class-sb-term-field.php';
    }
    require SB_THEME_INC_PATH . '/sb-theme-meta.php';
}

if((sb_theme_support_shop() || SB_Option::utility_enabled('shop')) && !class_exists('SB_Product')) {
    require SB_THEME_INC_PATH . '/class-sb-product.php';
}

if(!function_exists('bfi_thumb')) {
    require SB_THEME_LIB_PATH . '/bfi-thumb/BFI_Thumb.php';
}

require SB_THEME_INC_PATH . '/class-sb-browser.php';

if(!class_exists('SB_Detect')) {
    require SB_THEME_INC_PATH . '/class-sb-detect.php';
}

require SB_THEME_INC_PATH . '/sb-theme-admin.php';

require SB_THEME_INC_PATH . '/class-sb-theme.php';

require SB_THEME_INC_PATH . '/sb-utilities-setting-callback.php';

if(SB_Option::statistics_enabled()) {
    require SB_THEME_INC_PATH . '/sb-statistics-setting-callback.php';
}

require SB_THEME_INC_PATH . '/sb-theme-setting-callback.php';

if(SB_Option::statistics_enabled()) {
    require SB_THEME_INC_PATH . '/widgets/class-sb-statistics-widget.php';
}

require SB_THEME_INC_PATH . '/sb-theme-hook.php';

if(SB_Option::utility_enabled('facebook_php_sdk') && !class_exists('Facebook')) {
    require SB_THEME_LIB_PATH . '/facebook-php-sdk/facebook.php';
}

if(file_exists(SB_THEME_CUSTOM_PATH . '/sb-theme-custom.php')) {
    require SB_THEME_CUSTOM_PATH . '/sb-theme-custom.php';
}