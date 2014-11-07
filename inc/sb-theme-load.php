<?php
require SB_THEME_INC_PATH . '/sb-theme-constant.php';

require SB_THEME_INC_PATH . '/sb-theme-admin.php';

require SB_THEME_INC_PATH . '/class-sb-theme.php';

require SB_THEME_INC_PATH . '/sb-theme-setting-callback.php';

require SB_THEME_INC_PATH . '/sb-theme-lib.php';

if(file_exists(SB_THEME_CUSTOM_PATH . '/sb-theme-custom.php')) {
    require SB_THEME_CUSTOM_PATH . '/sb-theme-custom.php';
}

require SB_THEME_INC_PATH . '/sb-theme-hook.php';