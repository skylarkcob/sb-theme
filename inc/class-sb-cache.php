<?php
class SB_Cache {
    public static function build_post_transient_name($post_id, $suffix = '') {
        return 'sb_theme_post_' . $post_id . $suffix;
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

    public static function build_user_transient_name($user_id, $suffix = '') {
        return 'sb_theme_user_' . $user_id . $suffix;
    }
}