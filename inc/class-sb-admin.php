<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Admin {

    private $sb_admin_added = false;
    private $tabs = array();
    private $advanced_tabs = array();
    private $checkout_tabs = array();
    private $store_tabs = array();
	private $vchat_tabs = array();
    private $socials_tabs = array();

    public function __construct() {
        if($this->has_sb_admin()) {
            return;
        }
        $this->init();
        $this->action();
        $this->filter();
    }

    private function init() {
        $this->sb_admin_added = true;
        $this->sb_tab_init();
        do_action('sb_theme_option_construct');
    }

    private function action() {
        add_action('admin_menu', array($this, 'action_admin_menu'));
        add_action('admin_head', array($this, 'action_admin_head'));
        add_action('sb_theme_admin_enqueue_scripts', array($this, 'admin_style_and_script'));
        add_action('sb_theme_admin_init', array($this, 'action_admin_init'));
    }

    private function tab_filter() {
        add_filter('sb_admin_tabs', array($this, 'option_tab'));
        add_filter('sb_theme_advanced_setting_tabs', array($this, 'advanced_setting_tabs'));
        add_filter('sb_theme_checkout_setting_tabs', array($this, 'checkout_setting_tabs'));
        add_filter('sb_theme_store_setting_tabs', array($this, 'store_setting_tabs'));
	    add_filter('sb_theme_vchat_setting_tabs', array($this, 'vchat_setting_tabs'));
        add_filter('sb_theme_socials_setting_tabs', array($this, 'socials_setting_tabs'));
    }

    private function filter() {

    }

    public function checkout_setting_tabs($tabs) {
        $defaults = array(
            'ngan_luong' => array(
                'name' => __('Ngân Lượng', 'sb-theme')
            )
        );
        $tabs = wp_parse_args($tabs, $defaults);
        return $tabs;
    }

	public function vchat_setting_tabs($tabs) {
		$defaults = array(
			'dashboard' => array(
				'name' => __('Quản lý trò chuyện', 'sb-theme')
			),
			'setting' => array(
				'name' => __('Cài đặt', 'sb-theme')
			)
		);
		$tabs = wp_parse_args($tabs, $defaults);
		return $tabs;
	}

    public function socials_setting_tabs($tabs) {
        $defaults = array(
            'facebook' => array(
                'name' => __('Facebook', 'sb-theme')
            ),
            'google' => array(
                'name' => __('Google', 'sb-theme')
            )
        );
        $tabs = wp_parse_args($tabs, $defaults);
        return $tabs;
    }

    public function advanced_setting_tabs($tabs) {
        $defaults = array(
            'general' => array(
                'name' => __('Tổng quan', 'sb-theme')
            ),
            'writing' => array(
                'name' => __('Viết', 'sb-theme')
            ),
            'reading' => array(
                'name' => __('Đọc', 'sb-theme')
            ),
            'discussion' => array(
                'name' => __('Thảo luận', 'sb-theme')
            ),
            'media' => array(
                'name' => __('Tập tin', 'sb-theme')
            ),
            'permalinks' => array(
                'name' => __('Đường dẫn tĩnh', 'sb-theme')
            ),
            'membership' => array(
                'name' => __('Thành viên', 'sb-theme')
            )
        );
        $tabs = wp_parse_args($tabs, $defaults);
        return $tabs;
    }

    public function store_setting_tabs($tabs) {
        $defaults = array(
            'general' => array(
                'name' => __('Tổng quan', 'sb-theme')
            ),
            'text' => array(
                'name' => __('Chữ hiển thị', 'sb-theme')
            )
        );
        $tabs = wp_parse_args($tabs, $defaults);
        return $tabs;
    }

    public function get_advanced_setting_tabs() {
        return apply_filters('sb_theme_advanced_setting_tabs', $this->advanced_tabs);
    }

    public function get_store_setting_tabs() {
        return apply_filters('sb_theme_store_setting_tabs', $this->store_tabs);
    }

    public function get_checkout_setting_tabs() {
        return apply_filters('sb_theme_checkout_setting_tabs', $this->checkout_tabs);
    }

	public function get_vchat_setting_tabs() {
		return apply_filters('sb_theme_vchat_setting_tabs', $this->vchat_tabs);
	}

    public function get_socials_setting_tabs() {
        return apply_filters('sb_theme_socials_setting_tabs', $this->socials_tabs);
    }

    private function has_sb_admin() {
        global $sb_admin;
        if($sb_admin && $sb_admin->sb_admin_added) {
            return true;
        }
        return false;
    }

    public function submenu_exists($name) {
        global $submenu;
        if(isset( $submenu[ $name ] )) {
            return true;
        }
        return false;
    }

    public function settings_page() {
        SB_Admin_Custom::setting_page_callback();
    }

    public function admin_style_and_script() {
        if(SB_Admin_Custom::is_sb_page()) {
            wp_enqueue_media();
        }
    }

    public function sanitize($input) {
        //$options = get_option('sb_options');
	    $options = SB_Option::get();
        $input = wp_parse_args($input, $options);
        $input = apply_filters('sb_options_sanitize', $input);
	    $input = apply_filters('sb_theme_sanitize_option', $input);
        do_action('sb_theme_sanitize_option_done', $input);
	    return $input;
    }

    private function register_sb_setting() {
        register_setting('sb-setting', 'sb_options', array($this, 'sanitize'));
        do_action('sb_theme_register_setting');
    }

    private function add_sb_options_section() {
        if(SB_Admin_Custom::is_about_page()) {
            $title = __('About SB', 'sb-theme');
            if('vi' == sb_theme_get_language()) {
                $title = 'Giới thiệu SB';
            }
            add_settings_section('sb_options_section', $title, array($this, 'print_section_info'), 'sb_options');
        }
    }

    private function add_default_section() {
        $this->add_sb_options_section();
    }

    public function action_admin_init() {
        $this->register_sb_setting();
        $this->add_default_section();
        do_action('sb_admin_init');
        do_action('sb_theme_option_page_init');
    }

    public function sb_options_callback() {
        sb_theme_get_content('sb-admin-about');
    }

    public function print_section_info($args) {
        $lang = sb_theme_get_language();
        if($args['id'] == 'sb_options_section') {
            if('vi' == $lang) {
                echo 'Giới thiệu sơ lượt về mã nguồn SB Theme và SB Options.';
            } else {
                _e('Short description about SB Theme and SB Options.', 'sb-theme');
            }
        } else {
            if('vi' == $lang) {
                echo 'Thay đổi thông tin cài đặt tùy chọn của bạn ở bên dưới:';
            } else {
                _e('Change your settings below:', 'sb-theme');
            }
        }
    }

    private function sb_tab_init() {
        $this->tab_filter();
        $title = __('About SB', 'sb-theme');
        if('vi' == sb_theme_get_language()) {
            $title = 'Giới thiệu SB';
        }
        $this->add_tab('sb_options', $title, 'sb_options_section');
    }

    private function add_tab($key, $title, $section_id) {
        $this->tabs[$key] = array('title' => $title, 'section_id' => $section_id);
    }

    public function option_tab($tabs) {
        $tabs = array_merge($tabs, $this->tabs);
        $tabs = apply_filters('sb_theme_sidebar_tabs', $tabs);
        return $tabs;
    }

	public function action_admin_head() {
		do_action('sb_admin_head');
		do_action('sb_theme_admin_head');
	}

    public function action_admin_menu() {
        $this->add_menu_page();
        $this->add_default_submenu();
        do_action('sb_admin_menu');
	    do_action('sb_theme_admin_menu');
    }

    private function add_default_submenu() {
        $this->add_submenu_page();
    }

    public function add_submenu_page() {
        if(!$this->submenu_exists('sb_options')) {
            $title = __('About SB', 'sb-theme');
            if('vi' == sb_theme_get_language()) {
                $title = 'Giới thiệu SB';
            }
            add_submenu_page('sb_options', $title, $title, 'manage_options', 'sb_options', array($this, 'settings_page'));
        }
        do_action('sb_theme_add_submenu_page');
    }

    public function add_menu_page() {
        if(empty($GLOBALS['admin_page_hooks']['sb_options'])) {
            add_menu_page('SB Options', 'SB Options', 'manage_options', 'sb_options', '', sb_theme_get_image_url('px.png'), 71);
        }
        do_action('sb_theme_add_menu_page');
    }
}