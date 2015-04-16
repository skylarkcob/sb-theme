<?php
class SB_Cache {
    public static function build_post_transient_name($post_id, $suffix = '') {
        return 'sb_theme_post_' . $post_id . $suffix;
    }

    public static function build_widget_transient_name($widget_id) {
        $widget_id = str_replace('-', '_', $widget_id);
        return 'sb_theme_widget_cache_' . $widget_id;
    }

    public static function delete_widget_cache($widget_id) {
        delete_transient(self::build_widget_transient_name($widget_id));
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

    public static function build_user_avatar_transient_name($user_key) {
        return 'sb_theme_avatar_' . $user_key;
    }

    public static function build_license_transient_name() {
        return 'sb_theme_license';
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

    public static function delete_captcha_transient($file_name) {
        $transient_keys = SB_Cache::build_captcha_transient_name($file_name);
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
}