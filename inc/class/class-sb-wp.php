<?php
class SB_WP {
	public static function get_redirect_uri() {
		if(is_single() || is_page()) {
			return get_permalink();
		}
		return home_url('/');
	}
	
	public static function get_logout_uri() {
		return wp_logout_url(self::get_redirect_uri());
	}
	
	public static function logout_uri() {
		echo self::get_logout_uri();
	}
	
	public static function get_page_uri_by_slug($slug) {
		return get_permalink(get_page_by_path($slug));
	}
	
	public static function remove_trailing_slash($string) {
		return untrailingslashit($string);
	}
	
	public static function get_login_uri() {
		return wp_login_url(self::get_redirect_uri());
	}
	
	public static function login_uri() {
		echo self::get_login_uri();
	}
	
	public static function get_post_per_page() {
		return get_option('posts_per_page');
	}
}
?>