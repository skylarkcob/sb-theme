<?php
// Prevent direct access
if(!defined("ABSPATH")) {
	die("Please do not access this file directly.");
}

define("SB_VERSION", "1.0.0");

// SB WordPress version support
define("SB_SUPPORT_VERSION", "3.9");

if( strcmp($wp_version, SB_SUPPORT_VERSION) < 0 ) {
	die("Theme only supports WordPress version ".SB_SUPPORT_VERSION." or later.");
}

// Path to WordPress theme container folder
define("SB_THEME_ROOT_PATH", get_theme_root());
define("SB_THEME_ROOT_URI", get_theme_root_uri());

// Path to WordPress current theme folder
define("SB_THEME_PATH", get_template_directory());
define("SB_THEME_URI", get_template_directory_uri());

// Path to sb folder
define("SB_PATH", SB_THEME_PATH . "/sb");
define("SB_URI", SB_THEME_URI . "/sb");

// Path to inc folder
define("SB_INC_PATH", SB_PATH . "/inc");
define("SB_INC_URI", SB_URI . "/inc");

// Path to lib folder
define("SB_LIB_PATH", SB_INC_PATH . "/lib");
define("SB_LIB_URI", SB_INC_URI . "/lib");

// Path to js folder
define("SB_JS_URI", SB_INC_URI . "/js");

// Path to css folder
define("SB_CSS_URI", SB_INC_URI . "/css");

// Path to class folder
define("SB_CLASS_PATH", SB_INC_PATH . "/class");

// Path to template folder
define("SB_TEMPLATE_PATH", SB_INC_PATH . "/template");

// Get sb_options
$sb_options = get_option('sb_options');

class SB_Load {
	private $items = array();
	private $handles = array();
	private $classes = array(
		'class-sb-php',
		'class-sb-post',
		'class-sb-hook',
		'class-sb-product',
		'class-sb-theme',
		'class-sb-hook'
	);
	
	private function generate_handle() {
		foreach($this->classes as $class) {
			$this->add_handle($class, SB_CLASS_PATH . "/" . $class . ".php");
		}
	}
	
	public function add_handle($key, $value) {
		$this->handles[$key] = $value;
	}
	
	public function __construct() {
		$this->generate_handle();
		$this->add('class-sb-php');
		$this->add('class-sb-theme');
		$this->add('class-sb-hook');
	}
	
	public function add($handle) {
		
		array_push($this->items, $handle);
	}
	
	public function run() {
		foreach($this->items as $item) {
			if(array_key_exists($item, $this->handles)) {
				$path = $this->handles[$item];
				if(file_exists($path)) {
					include($path);
				}
			}
		}
		$hook = new SB_Hook();
	}
}
?>