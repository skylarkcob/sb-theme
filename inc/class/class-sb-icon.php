<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class SB_Icon {
	public static function url($name) {
		return trailingslashit(SB_IMAGES_URI) . $name;
	}
	
	public static function post_type_icon($type) {
		return self::url("post-type-menu-icon-".$type.".png");
	}
}