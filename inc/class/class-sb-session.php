<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SB_Session {
	public static function get($key) {
		$result = "";
		if(isset($_SESSION[$key])) {
			$result = $_SESSION[$key];
		}
		return $result;
	}
	
	public static function set($key, $value) {
		$_SESSION[$key] = $value;
	}
	
	public static function set_favorite_post($id) {
		$favorites = self::get_favorite_post();
		array_push($favorites, $id);
		$favorites = array_unique($favorites);
		self::set('favorite_post', $favorites);
	}
	
	public static function get_favorite_post() {
		return (array)self::get('favorite_post');
	}
	
	public static function is_favorite_post($post_id) {
		$favorites = self::get_favorite_post();
		if(in_array($post_id, $favorites)) {
			return true;
		}
		return false;
	}
}