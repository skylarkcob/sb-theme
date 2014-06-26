<?php
class SB_Post {
	public $post;
	public function __construct() {
		$this->post = get_post();
	}
	
	public function init($post) {
		$this->post = $post;
	}
	
	public function set($post) {
		$this->init($post);
	}
	
	public function get_by_id($id) {
		$this->post = get_post($id);
	}
	
	public function get_meta($key) {
		return get_post_meta($this->post->ID, $key, true);
	}
	
	public function update_meta($key, $value) {
		update_post_meta($this->post->ID, $key, $value);
	}
	
	public function get_views() {
		$views = 0;
		if($this->get_meta('views')) {
			$views = $this->get_meta('views');
		}
		return $views;
	}
	
	public function get_likes() {
		$result = 0;
		if($this->get_meta('likes')) {
			$result = $this->get_meta('likes');
		}
		return $result;
	}
	
	public function update_likes($value) {
		$likes = $this->get_likes();
		if(0 == $value && $likes > 0) {
			$likes--;
		} else {
			$likes++;
		}
		$this->update_meta('likes', $likes);
	}
	
	public function is_in_favorite() {
		$kq = false;
		if(is_user_logged_in()) {
			$user = new SB_User();
			$favorites = $user->get_favorite_post();
			if(in_array($this->post->ID, $favorites)) {
				$kq = true;
			}
		}
		return $kq;
	}
}
?>