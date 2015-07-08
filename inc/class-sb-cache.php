<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Cache {
    public static function build_post_transient_name($post_id, $suffix = '') {
        return 'sb_theme_post_' . $post_id . $suffix;
    }

    public static function build_theme_transient_name($suffix = '') {
        return 'sb_theme' . $suffix;
    }

    public static function build_query_transient_name($name) {
        return self::build_theme_transient_name($name . '_query');
    }

    public static function build_last_date_of_month_transient_name() {
        return 'sb_theme_check_last_date_of_month';
    }

    public static function build_transaction_secure_code_transient_name($secure_code) {
        return self::build_query_transient_name('_transaction_secure_code_' . $secure_code);
    }

    public static function delete_transaction_cache() {
        SB_Core::delete_transient(self::build_theme_transient_name('_transaction'));
    }

    public static function build_post_media_images_transient_name($post_id) {
        return self::build_post_transient_name($post_id, '_media_images');
    }

    public static function build_post_comment_number_transient_name($post_id) {
        return self::build_post_transient_name($post_id, '_comment_number');
    }

    public static function build_post_thumbnail_url_transient_name($post_id, $size) {
        return self::build_post_transient_name($post_id, '_thumbnail_url_' . $size);
    }

    public static function build_post_thumbnail_image_transient_name($post_id, $size) {
        return self::build_post_transient_name($post_id, '_thumbnail_image_' . $size);
    }

    public static function build_post_term_list_transient_name($post_id, $taxonomy) {
        return self::build_post_transient_name($post_id, '_' . $taxonomy . '_term_list');
    }

    public static function build_widget_transient_name($widget_id) {
        $widget_id = str_replace('-', '_', $widget_id);
        return 'sb_theme_widget_cache_' . $widget_id;
    }

    public static function delete_widget_cache($widget_id) {
        delete_transient(self::build_widget_transient_name($widget_id));
    }

    public static function post_images_cache() {
        $key = 'post_images_cache_enabled';
        $value = SB_Option::get_advanced_setting('general', $key);
        $value = SB_Option::check_switch_value($value, 0);
        return self::cache_result($value);
    }

    public static function cache_result($value) {
        if(!self::enabled()) {
            $value = false;
        }
        return (bool)$value;
    }

    public static function post_comment_count_cache() {
        $key = 'post_comment_count_cache_enabled';
        $value = SB_Option::get_advanced_setting('general', $key);
        $value = SB_Option::check_switch_value($value, 0);
        return self::cache_result($value);
    }

    public static function query_cache() {
        $key = 'query_cache_enabled';
        $value = SB_Option::get_advanced_setting('general', $key);
        $value = SB_Option::check_switch_value($value, 0);
        return self::cache_result($value);
    }

    public static function menu_cache() {
        $key = 'menu_cache_enabled';
        $value = SB_Option::get_advanced_setting('general', $key);
        $value = SB_Option::check_switch_value($value, 0);
        return self::cache_result($value);
    }

    public static function widget_cache() {
        $key = 'widget_cache_enabled';
        $value = SB_Option::get_advanced_setting('general', $key);
        $value = SB_Option::check_switch_value($value, 0);
        return self::cache_result($value);
    }

    public static function enabled() {
        $key = 'cache_enabled';
        if(isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
        } else {
            $value = SB_Option::get_advanced_setting('general', $key);
            $value = SB_Option::check_switch_value($value, 0);
            $_SESSION[$key] = absint($value);
        }
        return (bool)$value;
    }

    public static function build_custom_menu_transient_name() {
        return 'sb_theme_menu_custom_item';
    }

    public static function delete_all_sb_cache() {
        SB_Core::delete_transient('sb_theme');
    }

    public static function delete_all_cache() {
        self::delete_all_sb_cache();
    }

    public static function delete_all_post_cache() {
        SB_Core::delete_transient('sb_theme_post');
    }

    public static function delete_all_widget_cache() {
        SB_Core::delete_transient('sb_theme_widget');
    }

    public static function delete_all_query_cache() {
        SB_Core::delete_transient('sb_theme_query');
    }

    public static function build_user_avatar_transient_name($id_or_email, $suffix) {
        if(is_object($id_or_email)) {
            if($id_or_email->comment_ID && $id_or_email->comment_ID > 0) {
                $comment_author = $id_or_email->comment_author;
                $comment_author_ip = $id_or_email->comment_author_IP;
                $id_or_email = $comment_author . '_' . $comment_author_ip;
            }
        }
        $user_key = str_replace('@', '_', $id_or_email);
        return self::build_user_transient_name($user_key . '_avatar', $suffix);
    }

    public static function build_license_transient_name() {
        return 'sb_theme_license';
    }

    public static function build_admin_advanced_setting_tab_transient_name() {
        return 'sb_theme_admin_advanced_setting_tabs';
    }

    public static function build_admin_sidebar_tab_transient_name() {
        return 'sb_theme_admin_sidebar_tabs';
    }

    public static function build_init_role_transient_name() {
        return 'sb_theme_init_role';
    }

    public static function check_name($name) {
        $name = str_replace('-', '_', $name);
        return $name;
    }

    public static function build_menu_transient_name($location) {
        return 'sb_theme_menu_' . self::check_name($location);
    }

    public static function build_captcha_transient_name($file_name) {
        $file_name_only = SB_PHP::get_file_name_without_extension($file_name);
        $result = array(
            'prefix' => 'sb_theme_captcha_' . $file_name_only . '_prefix',
            'code' => 'sb_theme_captcha_' . $file_name_only . '_code'
        );
        return $result;
    }

    public static function delete_expired_transient() {
        self::delete_captcha_expired_cache();
    }

    public static function delete_captcha_transient($file_name) {
        $transient_keys = self::build_captcha_transient_name($file_name);
        delete_transient($transient_keys['code']);
        delete_transient($transient_keys['prefix']);
        SB_PHP::set_session('sb_theme_captcha_image', '');
        self::delete_expired_transient();
    }

    public static function build_default_theme_transient_name() {
        return 'sb_theme_default_theme';
    }

    public static function build_plugin_transient_name($plugin_slug) {
        $key = 'sb_theme_' . self::check_name($plugin_slug) . '_information';
    }

    public static function delete_all_captcha_cache() {
        SB_Core::delete_transient('sb_theme_captcha_');
    }

    public static function delete_captcha_expired_cache() {
        global $wpdb;
        $current_timestamp = time();
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name like %s AND option_value < %d", '_transient_timeout_sb_captcha_%', $current_timestamp ) );
        foreach($results as $row) {
            $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_id = %d", $row->option_id ) );
        }
    }

    public static function build_user_transient_name($user_id, $suffix = '') {
        return 'sb_theme_user_' . $user_id . $suffix;
    }
}