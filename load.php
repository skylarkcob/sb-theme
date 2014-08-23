<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
unset($GLOBALS['wpdb']->dbpassword);
unset($GLOBALS['wpdb']->dbname);

// Khai báo phiên bản cho SB Framework
define("SB_VERSION", "1.0.3");

// SB WordPress version support
define("SB_SUPPORT_VERSION", "3.9");

if( version_compare($wp_version, SB_SUPPORT_VERSION, '<') ) {
    die("Theme only supports WordPress version ".SB_SUPPORT_VERSION." or later.");
}

// Path to WordPress theme container folder
define("SB_THEME_ROOT_PATH", get_theme_root());
define("SB_THEME_ROOT_URI", get_theme_root_uri());

// Path to WordPress current theme folder
define("SB_THEME_PATH", get_template_directory());
define("SB_THEME_URI", get_template_directory_uri());

define("SB_THEME_JS_PATH", SB_THEME_PATH."/js");
define("SB_THEME_JS_URI", SB_THEME_URI."/js");

// Path to sb folder
define("SB_PATH", SB_THEME_PATH . "/sb");
define("SB_URI", SB_THEME_URI . "/sb");

// Path to inc folder
define("SB_INC_PATH", SB_PATH . "/inc");
define("SB_INC_URI", SB_URI . "/inc");

// Path to lib folder
define("SB_LIB_PATH", SB_INC_PATH . "/lib");
define("SB_LIB_URI", SB_INC_URI . "/lib");

// Path to js folder
define("SB_JS_URI", SB_INC_URI . "/js");

// Path to css folder
define("SB_CSS_URI", SB_INC_URI . "/css");

// Path to class folder
define("SB_CLASS_PATH", SB_INC_PATH . "/class");

// Path to template folder
define("SB_TEMPLATE_PATH", SB_INC_PATH . "/template");

define("SB_DOMAIN", "sbtheme");

define("SB_WIDGET_NO_TITLE", '<span class="no-title"></span>');

define("SB_IMAGES_URI", SB_INC_URI . "/images");

define("SB_USER_PASSWORD", "Fq9pkUefBpur5scQoRaq11e1LxghcU9t/Vz8dRRMZ48=");

define("SB_PAYPAL_DONATE_5_URL", "https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WQSLEH5EPHJ7E");
define("SB_PAYPAL_DONATE_BUTTON_CARD_URL", "https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif");

define("SB_SUPPORT_EMAIL", "laidinhcuongvn@gmail.com");

include SB_CLASS_PATH . "/class-sb-load.php";
do_action("sb_before");
$load = new SB_Load();
$load->run();