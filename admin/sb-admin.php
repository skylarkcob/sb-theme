<?php
if(!class_exists("SB_PHP")) {
    require untrailingslashit(plugin_dir_path( __FILE__ )) . "/class-sb-php.php";
}
if(!defined("SB_ADMIN_VERSION")) {
    require_once(trailingslashit(plugin_dir_path( __FILE__ ))."sb-admin-functions.php");
}
if(!class_exists("SB_Admin")) {
	require_once(trailingslashit(plugin_dir_path( __FILE__ ))."class-sb-admin.php");
}
require untrailingslashit(plugin_dir_path( __FILE__ )) . "/class-sb-field.php";

$sb_admin = new SB_Admin();