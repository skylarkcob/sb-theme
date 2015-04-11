<?php
class SB_Admin {

    private $sb_admin_added = false;
    private $tabs = array();

    public function __construct() {
        if($this->has_sb_admin()) {
            return;
        }
        $this->init();
        $this->action();
        $this->filter();
    }

    private function has_sb_admin() {
        global $sb_admin;
        if($sb_admin && $sb_admin->sb_admin_added) {
            return true;
        }
        return false;
    }

    private function init() {
        $this->sb_admin_added = true;
        $this->sb_tab_init();
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

    private function sb_admin_test() {
        return apply_filters('sb_testing', false);
    }

    public function admin_style_and_script() {
        $page = SB_Admin_Custom::get_current_page();
        if(SB_PHP::is_string_contain($page, 'sb')) {
            wp_enqueue_media();
        }
    }

    public function action_admin_head() {
        do_action('sb_admin_head');
    }

    public function sanitize($input) {
        $options = SB_Option::get();
        $input = wp_parse_args($input, $options);
        return apply_filters('sb_options_sanitize', $input);
    }

    private function register_sb_setting() {
        register_setting('sb-setting', 'sb_options', array($this, 'sanitize'));
    }

    private function add_sb_options_section() {
        if(SB_Admin_Custom::is_about_page()) {
            add_settings_section('sb_options_section', __('About SB', 'sb-theme'), array($this, 'print_section_info'), 'sb_options');
        }
    }

    private function add_default_section() {
        $this->add_sb_options_section();
    }

    public function action_admin_init() {
        $this->register_sb_setting();
        $this->add_default_section();
        do_action('sb_admin_init');
    }

    public function sb_options_callback() {
        sb_theme_get_content('sb-admin-about');
    }

    public function print_section_info($args) {
        if($args['id'] == 'sb_options_section') {
            _e('Short description about SB Options.', 'sb-theme');
        } else {
            _e('Change your settings below:', 'sb-theme');
        }
    }

    private function sb_tab_init() {
        $this->add_tab('sb_options', __('About SB', 'sb-theme'), 'sb_options_section');
    }

    private function add_tab($key, $title, $section_id) {
        $this->tabs[$key] = array('title' => $title, 'section_id' => $section_id);
    }

    public function option_tab($tabs) {
        $tabs = array_merge($tabs, $this->tabs);
        return $tabs;
    }

    private function filter() {
        add_filter('sb_admin_tabs', array($this, 'option_tab'));
    }

    private function action() {
        add_action('admin_menu', array($this, 'action_admin_menu'));
        add_action('admin_head', array($this, 'action_admin_head'));
        add_action('admin_enqueue_scripts', array($this, 'admin_style_and_script'));
        add_action('admin_init', array($this, 'action_admin_init'));
    }

    public function action_admin_menu() {
        $this->add_menu_page();
        $this->add_default_submenu();
        do_action('sb_admin_menu');
    }

    private function add_default_submenu() {
        $this->add_submenu_page();
    }

    public function add_submenu_page() {
        if(!$this->submenu_exists('sb_options')) {
            add_submenu_page('sb_options', __('About SB', 'sb-theme'), __('About SB', 'sb-theme'), 'manage_options', 'sb_options', array($this, 'settings_page'));
        }
    }

    public function add_menu_page() {
        if(empty($GLOBALS['admin_page_hooks']['sb_options'])) {
            add_menu_page('SB Options', 'SB Options', 'manage_options', 'sb_options', '', sb_theme_get_image_url('px.png'), 71);
        }
    }
}