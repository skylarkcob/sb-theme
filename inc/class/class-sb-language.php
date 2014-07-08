<?php
class SB_Language {
	private $translators = array();
	private $languages = array();
	private $language = "vi";
	
	public function __construct($lang = "vi") {
		$this->init();
		$this->translator_init();
		if("vi" != $lang) {
			$this->language = $lang;
		} else {
			global $sb_options;
			if(isset($sb_options['language'])) {
				$this->language = $sb_options['language'];
			} else {
				SB_Option::update("language", "vi");
			}
		}
	}
	
	private function init() {
		$this->add("vi", "Tiếng Việt");
		$this->add("en", "English");
	}
	
	private function translator_init() {
		$this->overwrite_translator("vi", "on", "Bật");
		$this->overwrite_translator("en", "on", "On");
		
		$this->overwrite_translator("vi", "off", "Tắt");
		$this->overwrite_translator("en", "off", "Off");
		
		//$this->overwrite_translator("vi", "sb_not_found", "Xin vui lòng đặt thư mục sb vào giao diện của bạn");
		
		$this->overwrite_translator("vi", "theme_settings", "Cài đặt giao diện");
		$this->overwrite_translator("en", "theme_settings", "Theme Settings");
		
		$this->overwrite_translator("vi", "general_settings", "Thiết lập chung");
		$this->overwrite_translator("en", "general_settings", "General Settings");
		
		$this->overwrite_translator("vi", "home_page_settings", "Cài đặt trang chủ");
		$this->overwrite_translator("en", "home_page_settings", "Home page Settings");
		
		$this->overwrite_translator("vi", "social_network_settings", "Thông tin mạng xã hội");
		$this->overwrite_translator("en", "social_network_settings", "Social Settings");
		
		$this->overwrite_translator("vi", "utility_management", "Quản lý tiện ích");
		$this->overwrite_translator("en", "utility_management", "Utility Management");
		
		$this->overwrite_translator("vi", "about_sb", "Giới thiệu SB Framework");
		$this->overwrite_translator("en", "about_sb", "About SB Framework");
		
		$this->overwrite_translator("vi", "version", "Phiên bản");
		$this->overwrite_translator("en", "version", "Version");
		
		$this->overwrite_translator("vi", "choose_language_description", "Lựa chọn ngôn ngữ để sử dụng trên giao diện được tạo bởi SB Team.");
		$this->overwrite_translator("en", "choose_language_description", "Choose language to use on SB Framework.");
		
		$this->overwrite_translator("vi", "choose_language", "Lựa chọn ngôn ngữ");
		$this->overwrite_translator("en", "choose_language", "Choose language");
		
		$this->overwrite_translator("vi", "settings_saved", "Thiếp lập của bạn đã được lưu thành công.");
		$this->overwrite_translator("en", "settings_saved", "Your settings have been saved successfully.");
		
		$this->overwrite_translator("vi", "your_settings_saved", "Thiếp lập của bạn đã được lưu thành công.");
		$this->overwrite_translator("en", "your_settings_saved", "Your settings have been saved successfully.");
		
		$this->overwrite_translator("vi", "fill_your_settings_below", "Thiết lập thông tin cài đặt của bạn ở bên dưới:");
		$this->overwrite_translator("en", "fill_your_settings_below", "Fill your settings below:");
		
		$this->overwrite_translator("vi", "save_changes", "Lưu thiết lập");
		$this->overwrite_translator("en", "save_changes", "Save Changes");
		
		$this->overwrite_translator("vi", "off", "Off");
		
		$this->overwrite_translator("vi", "off", "Off");
		
		$this->overwrite_translator("vi", "off", "Off");
		
		$this->overwrite_translator("vi", "off", "Off");
		
		$this->overwrite_translator("vi", "off", "Off");
	}
	
	
	public function set($lang) {
		if($this->exists($lang)) {
			$this->language = $lang;
		}
	}
	
	public function exists($lang) {
		foreach($this->languages as $key => $value) {
			if($lang == $key) {
				return true;
			}
		}
		return false;
	}
	
	
	
	public function add($lang_code, $lang_title) {
		if(!in_array($lang_code, $this->languages)) {
			$this->languages[$lang_code] = $lang_title;
		}
	}
	
	
	
	public function add_text($lang, $phrase, $text) {
		if(!$this->translator_exists($phrase)) {
			$this->overwrite_text($lang, $phrase, $text);
		}
	}
	
	public function add_translator($lang, $phrase, $text) {
		$this->add_text($lang, $phrase, $text);
	}
	
	public function overwrite_text($lang, $phrase, $text) {
		$this->translators[$lang][$phrase] = $text;
	}
	
	public function overwrite_translator($lang, $phrase, $text) {
		$this->overwrite_text($lang, $phrase, $text);
	}
	
	public function translator_exists($phrase) {
		foreach($this->translators as $key => $value) {
			if(array_key_exists($phrase, $value)) {
				return true;
			}
		}
		return false;
	}
	
	public function text_exists($phrase) {
		return $this->translator_exists($phrase);
	}
	
	public function phrase_exists($phrase) {
		return $this->text_exists($phrase);
	}
	
	public function phrase($phrase) {
		$text = '';
		if($this->phrase_exists($phrase)) {
			$text = $this->translators[$this->language][$phrase];
		}
		return $text;
	}
	
	public function get() {
		return $this->language;
	}
	
	public function get_array() {
		return $this->translators;
	}
	
	public function get_list() {
		return $this->languages;
	}
}
?>