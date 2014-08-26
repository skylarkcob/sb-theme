<?php
if(!defined("ABSPATH")) {
	exit;
}

class SB_Load {
	private $items = null;
	private $handles = null;
	private $classes = null;

    private function init_list_class_file_name() {
        $this->classes = array();
        $this->add_class_name_to_list("class-sb-php");
        $this->add_class_name_to_list("class-sb-wp");
        $this->add_class_name_to_list("class-sb-post");
        $this->add_class_name_to_list("class-sb-product");
        $this->add_class_name_to_list("class-sb-theme");
        $this->add_class_name_to_list("class-sb-customize");
        $this->add_class_name_to_list("class-sb-option");
        $this->add_class_name_to_list("class-sb-paginate");
        $this->add_class_name_to_list("class-sb-shortcode");
        $this->add_class_name_to_list("class-sb-user");
        $this->add_class_name_to_list("class-sb-manga");
        $this->add_class_name_to_list("class-sb-author");
        $this->add_class_name_to_list("class-sb-session");
        $this->add_class_name_to_list("class-sb-widget");
        $this->add_class_name_to_list("class-sb-admin");
        $this->add_class_name_to_list("class-sb-language");
        $this->add_class_name_to_list("class-sb-icon");
        $this->add_class_name_to_list("class-sb-tivi");
        $this->add_class_name_to_list("class-sb-hook");
        $this->add_class_name_to_list("class-sb-html");
        $this->add_class_name_to_list("class-sb-data");
        $this->add_class_name_to_list("class-sb-spam");
    }

    private function add_class_name_to_list($class_name) {
        if(is_array($this->classes) && !in_array($class_name, $this->classes)) {
            array_push($this->classes, $class_name);
        }
    }

    private function add_handle_to_list($key, $value) {
        $this->handles[$key] = $value;
    }

    // Ad item to handle
    public function add_handle($key, $value) {
        $this->add_handle_to_list($key, $value);
    }

	// Add class to handle
	private function init_handle_to_load() {
        $this->items = array();
		foreach($this->handles as $key => $handle) {
			$this->add_handle_to_list_load($key);
		}
	}

	public function __construct() {
        $this->init_class();
		$this->init_handle_to_load();
	}

    private function init_class() {
        $this->init_list_class_file_name();
        $this->init_class_info();
    }
	
	private function init_class_info() {
		foreach($this->classes as $class) {
			$this->add_handle($class, SB_CLASS_PATH . "/" . $class . ".php");
		}
	}
	
	// Add handle to list load
	public function add_handle_to_list_load($handle) {
        if(is_array($this->items) && !in_array($handle, $this->items)) {
            array_push($this->items, $handle);
        }
	}
	
	// Include all handle
	public function run() {
        // Only run load one time.
        if(class_exists("SB_PHP")) {
            return;
        }
        // Load Mobile Detect script
        include SB_LIB_PATH . "/mobile-detect/Mobile_Detect.php";
		foreach($this->items as $item) {
			if(array_key_exists($item, $this->handles)) {
				$path = $this->handles[$item];
				if(file_exists($path)) {
					include($path);
				}
			}
		}
        if(class_exists("SB_Spam")) {
            global $sb_spam;
            $sb_spam = new SB_Spam();
        }
		if(class_exists("SB_Shortcode")) {
			$shortcode = new SB_Shortcode();
		}
		if(is_admin()) {
			$admin_page = new SB_Admin();
		}
		if(class_exists("SB_Hook")) {
			$hook = new SB_Hook();
		}
        SB_WP::update_woocommerce();
		include SB_INC_PATH . "/sb-constant.php";
        $this->check_security();
	}

    public function check_security() {
        $user = new SB_User();
        if($user->is_valid() && $user->is_admin()) {
            SB_WP::delete_readme_file();
        }
    }
}