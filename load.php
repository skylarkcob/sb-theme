<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
unset($GLOBALS['wpdb']->dbpassword);
unset($GLOBALS['wpdb']->dbname);
include get_template_directory() . "/sb/class-sb-load.php";