<?php
define('SB_THEME_VERSION', '1.0.5');

define('SB_THEME_PATH', untrailingslashit(get_template_directory()) . '/sb-theme');

define('SB_THEME_URL', untrailingslashit(get_template_directory_uri()) . '/sb-theme');

define('SB_THEME_INC_PATH', SB_THEME_PATH . '/inc');

global $sb_theme_supports;

require SB_THEME_INC_PATH . '/sb-theme-functions.php';