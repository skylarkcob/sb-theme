<?php
if(!defined("SB_ADMIN_VERSION")) {
    if(defined("SB_THEME_VERSION")) {
        define("SB_ADMIN_VERSION", SB_THEME_VERSION);
    } else {
        define("SB_ADMIN_VERSION", "2.0.0");
    }
}

if(!defined("SB_VERSION")) {
    define("SB_VERSION", SB_ADMIN_VERSION);
}

if(!defined("SB_ADMIN_PATH")) {
    if(defined("SB_THEME_VERSION")) {
        define("SB_ADMIN_PATH", SB_PATH . "/admin");
    } else {
        define("SB_ADMIN_PATH", untrailingslashit(plugin_dir_path( __FILE__ )));
    }
}

if(!function_exists("sb_add_submenu_page")) {
    function sb_add_submenu_page($title, $slug, $callback) {
        if(!sb_admin_menu_exists($slug, true)) {
            add_submenu_page('sb_options', $title, $title, 'manage_options', $slug, $callback);
        }
    }
}

if(!function_exists("sb_get_current_page")) {
    function sb_get_current_page() {
        return isset($_REQUEST["page"]) ? $_REQUEST["page"] : '';
    }
}

if(!function_exists("sb_options_page")) {
    function sb_options_page() {
        $page = sb_get_current_page();
        if("sb_options" == $page) {
            return true;
        }
        return false;
    }
}

if(!function_exists("sb_add_setting_section")) {
    function sb_add_setting_section($section_id, $section_title, $page_slug) {
        add_settings_section($section_id, $section_title, 'sb_option_description_callback', $page_slug);
    }
}

if(!function_exists("")) {
    function sb_admin_menu_exists( $handle, $sub = false ) {
        if( !is_admin() || (defined('DOING_AJAX') && DOING_AJAX) ) {
            return false;
        }
        global $menu, $submenu;
        $check_menu = $sub ? $submenu : $menu;
        if( empty( $check_menu ) ) {
            return false;
        }
        foreach( $check_menu as $k => $item ) {
            if( $sub ) {
                foreach( $item as $sm ) {
                    if($handle == $sm[2]) {
                        return true;
                    }
                }
            } else {
                if( $handle == $item[2] ) {
                    return true;
                }
            }
        }
        return false;
    }
}

if(!function_exists("sb_option_description_callback")) {
    function sb_option_description_callback($args) {
        if($args["id"] == "sb_options_section") {
            echo "Short description about SB Options.";
        } else {
            _e("Change your settings below:", "sbteam");
        }
    }
}

if(!function_exists("sb_add_setting_field")) {
    function sb_add_setting_field($field_id, $field_title, $section_id, $callback, $page_slug) {
        add_settings_field($field_id, $field_title, $callback, $page_slug, $section_id);
    }
}

if(!function_exists("sb_admin_setting_callback")) {
    function sb_admin_setting_callback() {
        include(SB_ADMIN_PATH."/sb-setting-page.php");
    }
}

if(!function_exists("sb_admin_sanitize_data")) {
    function sb_admin_sanitize_data($data, $type) {
        switch($type) {
            case "url":
                $data = esc_url_raw($data);
                if(!SB_PHP::is_valid_url($data) || !SB_PHP::is_valid_image($data)) {
                    $data = '';
                }
                return $data;
            default:
                return $data;
        }
    }
}