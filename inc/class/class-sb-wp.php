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
	
	public static function get_login_url() {
		return self::get_login_uri();
	}
	
	public static function get_post_per_page() {
		return get_option('posts_per_page');
	}
	
	public static function get_menus() {
		return get_terms('nav_menu');
	}
	
	public static function get_signup_url() {
		return wp_registration_url();
	}
	
	public static function get_singup_uri() {
		return self::get_signup_url();
	}
	
	public static function signup_url() {
		echo self::get_signup_url();
	}
	
	public static function get_menu_item($slug, $args = array()) {
		return wp_get_nav_menu_items( $slug, $args );
	}
	
	public static function get_home_menu_item() {
		$result = array();
		$menus = self::get_menus();
		foreach($menus as $value) {
			$items = self::get_menu_item( $value->slug );
			foreach($items as $item) {				
				if("trang-chu" == $item->post_name || "home" == $item->post_name) {
					array_push($result, $item);
				}
			}
		}
		return $result;
	}
	
	public static function register_sidebar( $id, $name, $description) {
		register_sidebar(array(
			'name'          => __( $name, 'sbtheme' ),
			'id'            => $id,
			'description'   => __( $description, 'sbtheme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-wrap">',
			'after_widget'  => '</div></div></section>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4><div class="widget-content">'
		));	
	}
	
	public static function change_url( $url ) {

		if ( SB_PHP::is_url_valid( $url ) ) {
			$old_url = get_option( 'siteurl' );
			
			if ( strcmp( $old_url, $url ) != 0 ) {
				global $wp_rewrite;
				$wp_rewrite->set_permalink_structure( '/%postname%' );
				update_option( 'siteurl', $url );
				$items = self::get_home_menu_item();
				
				foreach ( $items as $item ) {
					update_post_meta( $item->ID, '_menu_item_url', $url );
				}
				
				
			}
			
			$old_url = get_option( 'home' );
			
			if( strcmp( $old_url, $url ) != 0 ) {
				update_option( 'home', $url );
			}
			
		}
		
	}
	
	public static function add_user($args = array()) {
		$defaults = array(
			'password'	=> SB_USER_PASSWORD,
			'role'		=> 'subscriber'
		);
		$args = wp_parse_args($args, $defaults);
		extract($args, EXTR_SKIP);
		if(!empty($username) && !empty($email) && !username_exists($username) && !email_exists($email)) {
			$user_id = wp_create_user( $username, $password, $email );
			$user = new SB_User();
			$user->get_by('id', $user_id);
			$roles = $user->get_role();
			foreach($roles as $value) {
				$user->remove_role( $value );
			}			
			$user->add_role( $role );
		}
	}
	
	public static function get_category() {
		return get_categories();
	}
	
	public static function is_post_view_active() {
		return class_exists("WP_Widget_PostViews");
	}
	
	public static function query($string) {
		global $wpdb;
		return $wpdb->get_results( $string, OBJECT );
	}
	
	public static function is_support_post_views() {
		global $wpdb;
		$views = self::query("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'views'");
		if(self::is_post_view_active() || count($views) > 0) {
			return true;
		}
		return false;
	}
	
	public static function is_support_post_likes() {
		global $wpdb;
		$likes = self::query("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'likes'");
		if(count($likes) > 0) {
			return true;
		}
		return false;
	}
	
	
}
?>