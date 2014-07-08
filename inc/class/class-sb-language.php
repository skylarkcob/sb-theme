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
		}
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
	
	private function init() {
		$this->add("vi", "Tiếng Việt");
		$this->add("en", "English");
	}
	
	public function add($lang_code, $lang_title) {
		if(!in_array($lang_code, $this->languages)) {
			array_push($this->languages, array($lang_code => $lang_title));
		}
	}
	
	private function translator_init() {
		$this->overwrite_translator("vi", "on", "Bật");
		$this->overwrite_translator("en", "on", "On");
		
		$this->overwrite_translator("vi", "off", "Tắt");
		$this->overwrite_translator("en", "off", "Off");
		
		//$this->overwrite_translator("vi", "sb_not_found", "Xin vui lòng đặt thư mục sb vào giao diện của bạn");
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