<?php
class SB_Option {
	private $option;
	public function __construct() {
		global $sb_options;
		$this->option = $sb_options;
	}
	
	public function get_gplus_uri() {
		return $this->get('gplus');
	}
	
	public function get($key) {
		if(isset($this->option[$key])) {
			return $this->option[$key];
		}
		return '';
	}
	
	public function get_facebook_uri() {
		return $this->get('facebook');
	}
	
	public function get_logo_uri() {
		return $this->get('logo');
	}
	
	public function get_favicon_uri() {
		return $this->get('favicon');
	}
	
	public function get_twitter_uri() {
		return $this->get('twitter');
	}
	
	public function get_pinterest_uri() {
		return $this->get('pinterest');
	}
	
	public function get_rss_uri() {
		return $this->get('rss');
	}
	
	public function get_youtube_uri() {
		return $this->get('youtube');
	}
	
	public function get_linkedin_uri() {
		return $this->get('linkedin');
	}
}
?>