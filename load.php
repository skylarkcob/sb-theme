<?php
if(!defined("ABSPATH")) exit;
if(!defined("SB_PATH")) {
    define("SB_PATH", get_template_directory() . "/sb");
}

if(!defined("SB_THEME_VERSION")) {
    if(defined("SB_VERSION")) {
        define("SB_THEME_VERSION", SB_VERSION);
    } else {
        define("SB_THEME_VERSION", "2.0.0");
    }
}

add_filter("sb_admin_test", "__return_true");

require SB_PATH . "/admin/sb-admin.php";
require SB_PATH . "/inc/sb-theme-admin.php";
require SB_PATH . "/inc/sb-theme.php";