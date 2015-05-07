<?php
class SB_Option {
    public static function update($sb_options) {
        self::update_option('sb_options', $sb_options);
    }
    public static function get_date_format() {
        return get_option('date_format');
    }

    public static function get_admin_email() {
        return get_option('admin_email');
    }

    public static function get_recaptcha_site_key() {
        $value = self::get_option_by_key(array('global', 'recaptcha', 'site_key'));
        return $value;
    }

    public static function get_recaptcha_secret_key() {
        $value = self::get_option_by_key(array('global', 'recaptcha', 'secret_key'));
        return $value;
    }

    public static function get_default_language() {
        global $sb_default_language;
        if(empty($sb_default_language)) {
            $sb_default_language = self::get_theme_option_single_key('default_language');
        }
        if(empty($sb_default_language)) {
            $sb_default_language = SB_THEME_DEFAULT_LANGUAGE;
        }
        $sb_default_language = apply_filters('sb_theme_default_language', $sb_default_language);
        return apply_filters('sb_default_language', $sb_default_language);
    }

    public static function get_page_add_post_front_end() {
        $tab_base_option_name = 'writing';
        $key = 'page_add_post_front_end';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        if(is_numeric($value) && $value > 0) {
            return get_post($value);
        }
        return null;
    }

    public static function get_page_add_post_front_end_url() {
        $url = '';
        $page = self::get_page_add_post_front_end();
        if(is_object($page) && !is_wp_error($page)) {
            $url = get_permalink($page);
        } else {
            $page = SB_Post::get_by_slug('dang-tin', 'page');
            if(is_object($page)) {
                $url = get_permalink($page);
            }
        }
        $url = apply_filters('sb_theme_page_add_post_front_end_url', $url);
        return $url;
    }

    public static function update_breadcrumb_sep($value) {
        $options = get_option('wpseo_internallinks');
        $options['breadcrumbs-sep'] = $value;
        update_option('wpseo_internallinks', $options);
    }

    public static function get_bcn_options() {
        global $sb_bcn_options;
        if(empty($sb_bcn_options) || !is_array($sb_bcn_options)) {
            $sb_bcn_options = get_option('bcn_options');
        }
        return $sb_bcn_options;
    }

    public static function edit_bcn_breadcrumb_sep() {
        $options = self::get_bcn_options();
        $sep = isset($options['hseparator']) ? $options['hseparator'] : '/';
        if(SB_PHP::is_string_contain($sep, 'span') && !SB_PHP::is_string_contain($sep, 'class')) {
            $options['hseparator'] = '<span class="sep">' . trim($sep) . '</span>';
        }
        update_option('bcn_options', $options);
    }

    public static function responsive_enabled() {
        return self::utility_enabled('responsive');
    }

    public static function get_wpseo_internallinks() {
        global $sb_wpseo_internallinks;
        if(!$sb_wpseo_internallinks || empty($sb_wpseo_internallinks) ||!is_array($sb_wpseo_internallinks)) {
            $sb_wpseo_internallinks = get_option('wpseo_internallinks');
        }
        return $sb_wpseo_internallinks;
    }

    public static function edit_breadcrumb_sep() {
        $options = self::get_wpseo_internallinks();
        $sep = isset($options['breadcrumbs-sep']) ? $options['breadcrumbs-sep'] : '/';
        if(!SB_PHP::is_string_contain($sep, 'span') && !SB_PHP::is_string_contain($sep, 'class')) {
            $options['breadcrumbs-sep'] = '<span class="sep">' . trim($sep) . '</span>';
        }
        update_option('wpseo_internallinks', $options);
    }

    public static function yoast_breadcrumb_enabled() {
        $options = self::get_wpseo_internallinks();
        $enabled = isset($options['breadcrumbs-enable']) ? $options['breadcrumbs-enable'] : 0;
        return (bool)$enabled;
    }

    public static function get_time_fortmat() {
        return get_option('time_format');
    }

    public static function get_date_time_format() {
        return self::get_date_format() . ' ' . self::get_time_fortmat();
    }

    public static function get_timezone_string() {
        return get_option('timezone_string');
    }

    public static function update_permalink($struct) {
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure( $struct );
    }

    public static function get_permalink_struct() {
        return get_option('permalink_structure');
    }

    public static function get() {
        global $sb_options;
        if(empty($sb_options)) {
            $sb_options = get_option('sb_options');
        }
        return $sb_options;
    }

    public static function get_media_detail($value) {
        $value_id = (is_array($value)) ? (isset($value['id']) ? absint($value['id']) : '') : '';
        $value_url = (is_array($value)) ? (isset($value['url']) ? $value['url'] : '') : $value;
        if($value_id > 0) {
            $media_url = SB_Post::get_media_url($value_id);
            if(!empty($media_url)) {
                $value_url = $media_url;
            }
        }
        return array('id' => $value_id, 'url' => $value_url);
    }

    public static function get_media_url($array_key = array()) {
        $value = self::get_option_by_key($array_key);
        $media_detail = self::get_media_detail($value);
        $value = $media_detail['url'];
        return $value;
    }

    public static function get_favicon_url() {
        return self::get_media_url(array('theme', 'favicon'));
    }

    public static function get_logo_url() {
        $logo = self::get_logo_detail();
        $logo = self::get_media_detail($logo);
        return $logo['url'];
    }

    public static function get_logo_detail() {
        return self::get_theme_option(array('keys' => array('logo')));
    }

    public static function get_logo_type() {
        $type = self::get_theme_option(array('keys' => array('logo_type')));
        if(empty($type)) {
            $type = 'background';
        }
        return apply_filters('sb_logo_type', $type);
    }

    public static function get_term_metas() {
        global $sb_term_metas;
        if(!is_array($sb_term_metas) || count($sb_term_metas) < 1) {
            $sb_term_metas = get_option('sb_term_metas');
        }
        return $sb_term_metas;
    }

    public static function update_term_metas($new_value) {
        update_option('sb_term_metas', $new_value);
    }

    public static function get_logo_text() {
        $result = self::get_theme_option(array('keys' => array('logo_text')));
        if(empty($result)) {
            $result = get_bloginfo('name');
        }
        return $result;
    }

    public static function the_footer_text_html() {
        $footer_text = self::get_theme_footer_text();
        $footer_text = wpautop($footer_text);
        echo $footer_text;
    }

    public static function get_login_logo_url() {
        $logo_url = self::get_media_url(array('login_page', 'logo'));
        if(empty($logo_url) && defined('SB_THEME_VERSION')) {
            $logo_url = self::get_logo_url();
        }
        return $logo_url;
    }

    public static function check_switch_value($result, $default) {
        if(!is_numeric($result) && (null == $result || empty($result))) {
            $result = $default;
        }
        return (bool)$result;
    }

    public static function support_link_title() {
        $tab_base_option_name = 'writing';
        $key = 'restore_link_title';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 1);
        return (bool)$value;
    }

    public static function social_login_enabled() {
        $result = self::get_option_by_key(array('login_page', 'social_login'));
        return self::check_switch_value($result, 1);
    }

    public static function get_social_login_app($social_name) {
        return self::get_theme_advanced_option(array('keys' => array('social_login', $social_name)));
    }

    public static function get_advanced_membership_setting($option_name) {
        return self::get_advanced_setting('membership', $option_name);
    }

    public static function get_advanced_setting($base_option_name, $option_name) {
        return self::get_theme_advanced_option(array('keys' => array($base_option_name, $option_name)));
    }

    public static function get_taxonomy_use_thumbnail() {
        $tab_base_option_name = 'general';
        $key = 'taxonomy_use_thumbnail';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        return SB_PHP::string_to_array(',', $value);
    }

    public static function get_post_type_use_administrative_boundaries() {
        $tab_base_option_name = 'general';
        $key = 'post_type_use_administrative_boundaries';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        return SB_PHP::string_to_array(',', $value);
    }

    public static function use_administrative_boundaries() {
        $tab_base_option_name = 'general';
        $key = 'use_administrative_boundaries';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        $value = apply_filters('sb_theme_use_administrative_boundaries', $value);
        return (bool)$value;
    }

    public static function use_term_thumbnail() {
        $tab_base_option_name = 'general';
        $key = 'use_term_thumbnail';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        return (bool)$value;
    }

    public static function confirm_publish_post() {
        $tab_base_option_name = 'writing';
        $key = 'confirm_publish';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 1);
        return $value;
    }

    public static function get_theme_thumbnail_url() {
        $options = self::get();
        $url = self::get_media_url(array('theme', 'thumbnail'));
        if(empty($url)) {
            $url = isset($options['post_widget']['no_thumbnail']) ? $options['post_widget']['no_thumbnail'] : '';
        }
        if(empty($url)) {
            $url = SB_Post::get_default_thumbnail_url();
        }
        $url = apply_filters('sb_theme_post_thumbnail_url_default', $url);
        return $url;
    }

    public static function get_widget_thumbnail_url() {
        $options = self::get();
        $url = isset($options['post_widget']['no_thumbnail']) ? $options['post_widget']['no_thumbnail'] : '';
        if(empty($url)) {
            $url = self::get_theme_option(array('keys' => array('thumbnail')));
        }
        if(empty($url)) {
            $url = SB_Post::get_default_thumbnail_url();
        }
        return $url;
    }

    public static function use_smtp_mail() {
        $key = 'enabled';
        $value = SB_Option::get_option_by_key(array('smtp_email', $key));
        return (bool)$value;
    }

    public static function get_option_by_key($array_key = array(), $default = '') {
        $args = array(
            'keys' => $array_key,
            'default' => $default
        );
        return self::get_by_key($args);
    }

    public static function use_login_captcha() {
        $use_captcha = self::get_option_by_key(array('login_page', 'use_captcha'));
        $use_captcha = self::check_switch_value($use_captcha, 1);
        return $use_captcha;
    }

    public static function get_current_wordpress_version() {
        return get_bloginfo('version');
    }

    public static function get_activated_plugins() {
        return get_option('active_plugins');
    }

    public static function get_by_key($args = array()) {
        $default = isset($args['default']) ? $args['default'] : '';
        $options = self::get();
        $keys = isset($args['keys']) ? $args['keys'] : array();
        $value = $default;
        $tmp = $options;
        if(!is_array($keys)) {
            return $value;
        }
        foreach($keys as $key) {
            $tmp = (array)$tmp;
            if(is_array($key)) {
                continue;
            }
            $tmp = isset($tmp[$key]) ? $tmp[$key] : '';
            if(empty($tmp) || !is_array($tmp)) {
                break;
            }
        }
        if(!empty($tmp)) {
            $value = $tmp;
        }
        return $value;
    }

    public static function get_option_single_key($base_name, $key_name, $default = '') {
        $keys = array(
            $base_name,
            $key_name
        );
        return self::get_option_by_key($keys, $default);
    }

    public static function get_theme_option_single_key($key_name, $default = '') {
        return self::get_option_single_key('theme', $key_name, $default);
    }

    public static function build_sb_option_name($key_array) {
        if(!is_array($key_array)) {
            return '';
        }
        $result = 'sb_options';
        foreach($key_array as $key) {
            if(!empty($key)) {
                $result .= '[' . $key . ']';
            }
        }
        return $result;
    }

    public static function build_sb_theme_option_name($key_array) {
        array_unshift($key_array, 'theme');
        $key_name = self::build_sb_option_name($key_array);
        return $key_name;
    }

    public static function build_sb_theme_advanced_option_name($key_array) {
        array_unshift($key_array, 'sbt_advanced');
        $key_name = self::build_sb_option_name($key_array);
        return $key_name;
    }

    public static function get_color_schemes() {
        $options = (array)self::get_theme_option_single_key('color_schemes');
        return $options;
    }

    public static function build_sb_utility_option_name($key_array) {
        if(!is_array($key_array)) {
            return '';
        }
        array_unshift($key_array, 'utilities');
        return self::build_sb_option_name($key_array);
    }

    public static function get_theme_social($social_key) {
        return self::get_by_key(array('keys' => array('theme', 'social', $social_key)));
    }

    public static function get_theme_option($args = array()) {
        if(isset($args['keys']) && is_array($args['keys'])) {
            array_unshift($args['keys'], 'theme');
        }
        return self::get_by_key($args);
    }

    public static function get_theme_advanced_option($args = array()) {
        return self::get_by_base_option_name('sbt_advanced', $args);
    }

    public static function get_by_base_option_name($base_name, $args) {
        if(isset($args['keys']) && is_array($args['keys'])) {
            array_unshift($args['keys'], $base_name);
        }
        return self::get_by_key($args);
    }

    public static function get_theme_checkout_option($args = array()) {
        return self::get_by_base_option_name('sbt_checkout', $args);
    }

    public static function get_ngan_luong_info() {
        return SB_Option::get_checkout_by_base('ngan_luong');
    }

    public static function get_checkout_by_base($base, $option_name = '') {
        $args = array(
            'keys' => array(
                $base
            )
        );
        if(!empty($option_name)) {
            $args['keys'][] = $option_name;
        }
        return self::get_theme_checkout_option($args);
    }

    public static function get_theme_advanced_option_by_key($key_array) {
        $args = array(
            'keys' => $key_array
        );
        return self::get_theme_advanced_option($args);
    }

    public static function get_utility_option($args = array()) {
        if(isset($args['keys']) && is_array($args['keys'])) {
            array_unshift($args['keys'], 'utilities');
        }
        return self::get_by_key($args);
    }

    public static function get_statistics_option($args = array()) {
        if(isset($args['keys']) && is_array($args['keys'])) {
            array_unshift($args['keys'], 'statistics');
        }
        return self::get_by_key($args);
    }

    public static function get_statistics_switch($name) {
        $value = self::get_statistics_option(array('keys' => array($name)));
        return intval($value);
    }

    public static function get_utility($name) {
        $value = self::get_utility_option(array('keys' => array($name)));
        return intval($value);
    }

    public static function statistics_enabled() {
        return self::utility_enabled('statistics');
    }

    public static function utility_enabled($name) {
        $value = self::get_utility($name);
        return (bool)$value;
    }

    public static function get_channel_ui_connect_array() {
        $channel_ids = self::get_theme_option_single_key('channel_ui_connect');
        $result = array();
        if(!empty($channel_ids)) {
            $result = explode(',', $channel_ids);
        }
        return $result;
    }

    public static function get_theme_footer_text() {
        $args = array(
            'keys' => array('footer_text')
        );
        return self::get_theme_option($args);
    }

    public static function get_scroll_top() {
        $result = self::get_theme_option(array('keys' => array('scroll_top')));
        if(!(bool)$result) {
            $result = self::utility_enabled('scroll_top');
        }
        return (bool)$result;
    }

    public static function get_bool_value_by_key($args = array()) {
        $value = self::get_by_key($args);
        return (bool)$value;
    }

    public static function get_int_value_by_key($args = array()) {
        $value = self::get_by_key($args);
        return intval($value);
    }

    public static function get_home_url() {
        return get_option('home');
    }

    public static function get_site_url() {
        return get_option('siteurl');
    }

    public static function change_option_array_url(&$options, $args = array()) {
        if(!is_array($options)) {
            return;
        }
        $site_url = '';
        $url = '';
        extract($args, EXTR_OVERWRITE);
        if(empty($site_url) || empty($url) || $url == $site_url) {
            return;
        }
        foreach($options as $key => &$value) {
            if(is_array($value)) {
                self::change_option_array_url($value, $args);
            } elseif(!empty($value) && !is_numeric($value)) {
                $value = str_replace($url, $site_url, $value);
            }
        }
    }

    public static function change_option_url($args = array()) {
        $site_url = '';
        $url = '';
        extract($args, EXTR_OVERWRITE);
        if(empty($site_url) || empty($url) || $url == $site_url) {
            return;
        }
        $options = self::get();
        self::change_option_array_url($options, $args);
        self::update($options);
    }

    public static function change_widget_text_url($args = array()) {
        $site_url = '';
        $url = '';
        extract($args, EXTR_OVERWRITE);
        if(empty($site_url) || empty($url) || $url == $site_url) {
            return;
        }
        $text_widgets = get_option('widget_text');
        foreach($text_widgets as $key => $widget) {
            if(isset($widget['text'])) {
                $text_widgets[$key]['text'] = str_replace($url, $site_url, $widget['text']);
            }
        }
        update_option('widget_text', $text_widgets);
    }

    public static function update_option($option_name, $option_value) {
        update_option($option_name, $option_value);
    }

    public static function get_visits() {
        $value = intval(get_option('visits'));
        if($value < 1) {
            $value = 1;
        }
        return $value;
    }

    public static function get_visits_today() {
        $value = intval(get_option('visits_today'));
        if($value < 1) {
            $value = 1;
        }
        return $value;
    }

    public static function get_visitor_online() {
        $value = intval(get_option('visitor_online'));
        if($value < 1) {
            $value = 1;
        }
        return $value;
    }

    public static function get_user_online() {
        return intval(get_option('user_online'));
    }

    public static function get_bot_online() {
        return intval(get_option('bot_online'));
    }

    public static function get_guest_online() {
        return intval(get_option('guest_online'));
    }

    public static function get_visits_yesterday() {
        $value = intval(get_option('visits_yesterday'));
        return $value;
    }

    public static function get_visits_this_week() {
        return intval(get_option('visits_this_week'));
    }

    public static function get_visits_this_month() {
        return intval(get_option('visits_this_month'));
    }

    public static function get_today() {
        return get_option('sb_today');
    }

    public static function get_tomorrow() {
        return get_option('sb_tomorrow');
    }

    public static function get_yesterday() {
        return get_option('sb_yesterday');
    }

    public static function update_visits() {
        $visits = intval(get_option('visits'));
        $visits++;
        update_option('visits', $visits);
    }

    public static function get_theme_rss_feed() {
        $result = array();
        $count = self::get_theme_option(array('keys' => array('rss_feed', 'count')));
        if(!is_numeric($count)) {
            $count = 0;
        }
        $order = self::get_theme_option(array('keys' => array('rss_feed', 'order')));
        $order = explode(',', $order);
        for($i = 1; $i <= $count; $i++) {
            $title = self::get_theme_option(array('keys' => array('rss_feed', $i, 'title')));
            $number = self::get_theme_option(array('keys' => array('rss_feed', $i, 'number')));
            $url = self::get_theme_option(array('keys' => array('rss_feed', $i, 'url')));
            $id = self::get_theme_option(array('keys' => array('rss_feed', $i, 'id')));
            if((empty($number) || empty($url)) || (empty($title) && empty($id))) {
                continue;
            }
            $feed = array('title' => $title, 'number' => $number, 'url' => $url, 'id' => $id);
            array_push($result, $feed);
        }
        $ordered = array();
        foreach($order as $id) {
            $count = 0;
            foreach($result as $item) {
                if($item['id'] == $id) {
                    array_push($ordered, $item);
                    unset($result[$count]);
                    break;
                }
                $count++;
            }
        }
        return $ordered + $result;
    }
}