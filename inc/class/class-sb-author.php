<?php
class SB_Author extends SB_User {
	public $user;
	public function __construct() {
		$this->set(get_user_by('id', $this->get_meta("ID")));
	}
	
	public function set($user) {
		$this->user = $user;
	}
	
	public function init($user) {
		$this->set($user);
	}
	
	public function get_meta($key) {
		return get_the_author_meta($key);
	}
	
	public function get_avatar($size) {
		return get_avatar($this->user->ID, $size);
	}
	
	public function get_url() {
		return $this->get_meta("user_url");
	}
	
	public function get_username() {
		return $this->get_meta("user_login");
	}
	
	public function get_display_name() {
		return $this->get_meta("display_name");
	}
	
	public function get_description() {
		return $this->get_meta("description");
	}
	
	public function get_post_url() {
		return get_author_posts_url($this->user->ID);
	}
	
	public function get_facebook_url() {
		return $this->get_meta("facebook");
	}
	
	public function get_twitter_url() {
		$url = $this->get_meta("twitter");
		if(empty($url)) {
			return '';
		}
		if(!SB_PHP::is_valid_url($url)) {
			$url = "http://twitter.com/".basename($url);
		}
		return $url;
	}
	
	public function get_gplus_url() {
		$url = $this->get_meta("gplus");
		if(empty($url)) {
			$url = $this->get_meta("googleplus");
		}
		if(empty($url)) {
			$url = $this->get_meta("google_plus");
		}
		return $url;
	}
}
?>