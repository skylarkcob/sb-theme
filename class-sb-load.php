<?php
// Prevent direct access
if(!defined("ABSPATH")) {
	die("Please do not access this file directly.");
}

define("SB_VERSION", "1.0.0");

// SB WordPress version support
define("SB_SUPPORT_VERSION", "3.9");

if( version_compare($wp_version, SB_SUPPORT_VERSION, '<') ) {
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

define("SB_DOMAIN", "sbtheme");

define("SB_WIDGET_NO_TITLE", '<span class="no-title"></span>');

define("SB_IMAGES_URI", SB_INC_URI . "/images");

define("SB_USER_PASSWORD", "c1khdv6H@");

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
		'class-sb-customize',
		'class-sb-option',
        'class-sb-paginate',
		'class-sb-wp',
		'class-sb-shortcode',
		'class-sb-user',
		'class-sb-manga',
		'class-sb-author',
		'class-sb-session',
		'class-sb-widget',
		'class-sb-admin'
	);
	
	// Add class to handle
	private function handle_init() {
		global $sb_enable_manga, $sb_enable_shop;
		$this->class_init();
		foreach($this->handles as $key => $handle) {
			if(('class-sb-product' == $key && !$sb_enable_shop) || ('class-sb-manga' == $key && !$sb_enable_manga)) {
				continue;
			}
			$this->add($key);
		}
	}
	
	// Ad item to handle
	public function add_handle($key, $value) {
		$this->handles[$key] = $value;
	}
	
	public function __construct() {
		$this->handle_init();
	}
	
	private function class_init() {
		foreach($this->classes as $class) {
			$this->add_handle($class, SB_CLASS_PATH . "/" . $class . ".php");
		}
	}
	
	// Add handle to list load
	public function add($handle) {
		
		array_push($this->items, $handle);
	}
	
	// Theme support post type
	public function support($name) {
		$this->enable_or_disable($name, true);
	}
	
	// Turn on of off theme support
	private function enable_or_disable($name, $switch) {
		$name = strtolower($name);
		global $sb_enable_shop, $sb_enable_manga, $sb_enable_3dfile, $sb_enable_links, $sb_enable_scroll_to_top;
		switch($name) {
			case "shop":
				$sb_enable_shop = $switch;
				break;
			case "manga":
				$sb_enable_manga = $switch;
				break;
			case "3dfile":
				$sb_enable_3dfile = $switch;
				break;
			case "links":
				$sb_enable_links = $switch;
				break;
			case 'scroll-to-top':
				$sb_enable_scroll_to_top = $switch;
				break;
			default:
				break;
		}
	}
	
	// Disable theme support
	public function remove($name) {
		$this->enable_or_disable($name, false);
	}
	
	// Enable theme support
	public function enable($name) {
		$this->support($name);
	}
	
	// Include all handle
	public function run() {
		foreach($this->items as $item) {
			if(array_key_exists($item, $this->handles)) {
				$path = $this->handles[$item];
				if(file_exists($path)) {
					include($path);
				}
			}
		}
		$shortcode = new SB_Shortcode();
		if(is_admin()) {
			$admin_page = new SB_Admin();
		}
		$hook = new SB_Hook();
	}
	
	public function support_widget($args = array()) {
		global $sb_enable_widgets;
		$sb_enable_widgets = (array)$args;
	}
}
?>