<?php
require SB_THEME_INC_PATH . '/sb-theme-constant.php';

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

require SB_THEME_INC_PATH . '/sb-theme-setting-callback.php';

require SB_THEME_INC_PATH . '/sb-theme-lib.php';

if(file_exists(SB_THEME_CUSTOM_PATH . '/sb-theme-custom.php')) {
    require SB_THEME_CUSTOM_PATH . '/sb-theme-custom.php';
}

require SB_THEME_INC_PATH . '/sb-theme-hook.php';