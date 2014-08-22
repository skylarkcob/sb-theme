<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SB_Option {
	private $option;
	public function __construct() {
		global $sb_options;
		$sb_options = $this->get_option();
		$this->option = $sb_options;
	}
	
	public function get_all_option() {
		return $this->option;
	}
	
	public static function update($key, $value) {
		$options = (array) get_option('sb_options');
		$options[$key] = $value;
		update_option("sb_options", $options);
	}
	
	public function get_option() {
		return (array) get_option('sb_options');
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

    public function change_data_url($old_url, $new_url) {
        if(0 == strcmp($old_url, $new_url)) return;
        foreach($this->option as $key => $option) {
            if(SB_PHP::is_string_contain($option, $old_url)) {
                $option = str_replace($old_url, $new_url, $option);
                $this->update($key, $option);
            }
        }
    }
}