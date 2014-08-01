<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<?php
class SB_User extends WP_User {
	public $user;
	private $favorite_key = 'favorite_';
	public function __construct() {
		if(is_user_logged_in()) {
			$this->user = wp_get_current_user();
		}
	}
	
	public function init($user) {
		$this->user = $user;
	}
	
	public function set($user) {
		$this->init($user);
	}
	
	public function add($user) {
		$this->init($user);
	}
	
	public function remove_role($role) {
		$this->user->remove_role($role);
	}
	
	public function add_role($role) {
		$this->user->add_role($role);
	}
	
	public function get_by($key, $value) {
		$this->init(get_user_by($key, $value));
	}
	
	public function get_meta($key) {
		return get_user_meta($this->user->ID, $key, true);
	}
	
	public function get_favorite($type) {
		return array_filter((array)$this->get_meta($this->favorite_key.$type));
	}
	
	public function update_meta($key, $value) {
		update_user_meta($this->user->ID, $key, $value);
	}
	
	public function add_favorite($type, $value) {
		$favorites = $this->get_favorite($type);
		array_push($favorites, $value);
		$favorites = array_unique($favorites);
		$this->update_meta($this->favorite_key.$type, $favorites);
	}
	
	public function remove_favorite($type, $value) {
		$favorites = $this->get_favorite($type);
		if(($key = array_search($value, $favorites)) !== false) {
			unset($favorites[$key]);
			$favorites = array_filter($favorites);
			$this->update_meta($this->favorite_key.$type, $favorites);
		}
	}
	
	public function get_favorite_post() {
		return $this->get_favorite('post');
	}
	
	public function add_favorite_post($value) {
		$this->add_favorite('post', $value);
	}
	
	public function remove_favorite_post($value) {
		$this->remove_favorite('post', $value);
	}
	
	public function get_favorite_manga() {
		return $this->get_favorite('manga');
	}
	
	public function add_favorite_manga($value) {
		$this->add_favorite('manga', $value);
	}
	
	public function remove_favorite_manga($value) {
		$this->remove_favorite('manga', $value);
	}
	
	public function get_favorite_product() {
		return $this->get_favorite('product');
	}
	
	public function add_favorite_product($value) {
		$this->add_favorite('product', $value);
	}
	
	public function remove_favorite_product($value) {
		$this->remove_favorite('product', $value);
	}
	
	public function get_role() {
		if ( !empty( $this->user->roles ) && is_array( $this->user->roles ) ) {
			return $this->user->roles;
		}
		return array();
	}
}
?>