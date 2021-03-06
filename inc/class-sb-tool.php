<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Tool {
    public static function use_custom_login() {
        $options = SB_Option::get();
        $value = isset($options['login_page']['use_sb_login']) ? intval($options['login_page']['use_sb_login']) : 1;
        $result = (bool)$value;
        $result = apply_filters('sb_theme_use_sb_login_page', $result);
        $result = apply_filters('sb_theme_use_custom_login', $result);
        return $result;
    }

    public static function use_lazyload() {
        $result = apply_filters('sb_theme_use_lazyload', false);
        return $result;
    }

    public static function use_lazyload_post_thumbnail() {
        $result = false;
        if(self::use_lazyload()) {
            $result = true;
        }
        $result = apply_filters('sb_theme_use_lazyload_post_thumbnail', $result);
        return $result;
    }

    public static function use_utility_add_to_head() {
        $result = apply_filters('sb_theme_use_utility_add_to_head', SB_Option::utility_enabled('add_to_head'));
        return $result;
    }

    public static function use_utility_default_thumbnail() {
        $result = apply_filters('sb_theme_use_utility_default_thumbnail', SB_Option::utility_enabled('default_thumbnail'));
        return $result;
    }

    public static function use_utility_google_analytics() {
        $result = apply_filters('sb_theme_use_utility_google_analytics', SB_Option::utility_enabled('google_analytics'));
        return $result;
    }

    public static function use_utility_category_widget() {
        $result = apply_filters('sb_theme_use_utility_category_widget', SB_Option::utility_enabled('category_widget'));
        return $result;
    }

    public static function use_utility_facebook_javascript_sdk() {
        $result = apply_filters('sb_theme_use_utility_facebook_javascript_sdk', SB_Option::utility_enabled('facebook_javascript_sdk'));
        return $result;
    }

    public static function use_utility_facebook_php_sdk() {
        $result = apply_filters('sb_theme_use_utility_facebook_php_sdk', SB_Option::utility_enabled('facebook_php_sdk'));
        return $result;
    }

    public static function use_utility_sharethis() {
        $result = apply_filters('sb_theme_use_utility_sharethis', SB_Option::utility_enabled('sharethis'));
        return $result;
    }

    public static function use_utility_addthis() {
        $result = apply_filters('sb_theme_use_utility_addthis', SB_Option::utility_enabled('addthis'));
        return $result;
    }

    public static function use_utility_facebook_fanpage() {
        $result = apply_filters('sb_theme_use_utility_facebook_fanpage', SB_Option::utility_enabled('facebook_fanpage'));
        return $result;
    }

    public static function use_utility_social_list() {
        $result = apply_filters('sb_theme_use_utility_social_list', SB_Option::utility_enabled('social'));
        return $result;
    }

    public static function use_utility_footer_text() {
        $result = apply_filters('sb_theme_use_utility_footer_text', SB_Option::utility_enabled('footer_text'));
        return $result;
    }

    public static function use_utility_scroll_to_top() {
        $result = apply_filters('sb_theme_use_utility_scroll_to_top', SB_Option::get_utility('scroll_top'));
        return $result;
    }

    public static function custom_login_use_captcha() {
        $options = SB_Option::get();
        $value = isset($options['login_page']['use_captcha']) ? intval($options['login_page']['use_captcha']) : 1;
        $result = (bool)$value;
        return apply_filters('sb_theme_custom_login_use_captcha', $result);
    }

    public static function custom_signup_use_captcha() {
        $result = self::custom_login_use_captcha();
        $result = apply_filters('sb_login_page_signup_captcha', $result);
        $result = apply_filters('sb_theme_custom_signup_use_captcha', $result);
        return $result;
    }

    public static function use_order_system() {
        $result = apply_filters('sb_theme_use_order_system', false);
        return $result;
    }

    public static function use_captcha() {
        $use_captcha = apply_filters('sb_use_captcha', false);
        $use_captcha = apply_filters('sb_theme_use_captcha', $use_captcha);
        return $use_captcha;
    }

    public static function use_login_captcha() {
        $result = SB_Option::use_login_captcha();
        return apply_filters('sb_theme_use_login_captcha', $result);
    }

    public static function use_default_avatar() {
        $options = SB_Option::get();
        $result = isset($options['comment']['default_avatar']) ? $options['comment']['default_avatar'] : 0;
        $result = (bool)$result;
        return apply_filters('sb_theme_use_default_avatar', $result);
    }

    public static function use_custom_menu() {
        $result = SB_Core::use_custom_menu();
        return apply_filters('sb_theme_use_custom_menu', $result);
    }

    public static function use_facebook_javascript_sdk() {
        return apply_filters('sb_theme_use_facebook_javascript_sdk', false);
    }

    public static function user_use_custom_style() {
        $result = apply_filters('sb_theme_use_user_custom_style', false);
        $result = apply_filters('sb_theme_user_use_custom_style', $result);
        return $result;
    }

    public static function use_vchat() {
        $result = SB_Core::use_vchat();
        return apply_filters('sb_theme_use_vchat', $result);
    }

    public static function use_menu_item_description() {
        $result = SB_Core::use_menu_item_description();
        return apply_filters('sb_theme_use_menu_item_description', $result);
    }

    public static function use_ads_system() {
        $result = SB_Core::use_ads_system();
        return apply_filters('sb_theme_use_ads_system', $result);
    }

    public static function use_support_system() {
        return apply_filters('sb_theme_use_support_system', false);
    }

    public static function use_term_thumbnail() {
        $result = SB_Option::use_term_thumbnail();
        return apply_filters('sb_theme_use_term_thumbnail', $result);
    }

    public static function post_use_featured_meta() {
        $result = SB_Core::add_checkbox_featured_post();
        $result = apply_filters('sb_theme_add_checkbox_featured_post', $result);
        $result = apply_filters('sb_theme_post_use_featured_meta', $result);
        $result = apply_filters('sb_theme_use_featured_meta_for_post', $result);
        return $result;
    }

    public static function use_featured_meta_for_post() {
        return self::post_use_featured_meta();
    }

    public static function use_term_meta() {
        $result = false;
        if(sb_theme_support_term_meta() || SB_Option::utility_enabled('term_meta')) {
            $result = true;
        } elseif(self::use_term_thumbnail()) {
            $result = true;
        } elseif(self::use_administrative_boundaries()) {
            $result = true;
        } elseif(self::post_use_featured_meta()) {
            $result = true;
        }
        $result = apply_filters('sb_theme_use_term_meta', $result);
        return $result;
    }

    public static function use_custom_meta() {
        $result = self::use_term_meta();
        return apply_filters('sb_theme_use_custom_meta', $result);
    }

    public static function use_administrative_boundaries() {
        $value = SB_Option::use_administrative_boundaries();
        $result = apply_filters('sb_theme_use_administrative_boundaries', $value);
        return $result;
    }

    public static function taxonomy_use_thumbnail() {
        $taxonomies = SB_Option::get_taxonomy_use_thumbnail();
        return apply_filters('sb_theme_taxonomy_use_thumbnail', $taxonomies);
    }

    public static function post_type_use_administrative_boundaries() {
        $post_types = SB_Option::get_post_type_use_administrative_boundaries();
        return apply_filters('sb_theme_post_type_use_administrative_boundaries', $post_types);
    }

    public static function use_smtp_email() {
        $result = SB_Option::use_smtp_mail();
        return apply_filters('sb_theme_use_smtp_email', $result);
    }

    public static function async_javascript() {
        return apply_filters('sb_theme_async_javascript', false);
    }

    public static function use_bootstrap_and_fontawesome() {
        $result = true;
        $result = apply_filters('sb_theme_use_bootstrap_and_fontawesome', $result);
        return $result;
    }

    public static function use_superfish() {
        $result = true;
        $result = apply_filters('sb_theme_use_superfish', $result);
        return $result;
    }

    public static function use_slider() {
        $result = false;
        $result = apply_filters('sb_theme_use_slider', $result);
        return $result;
    }

    public static function minify_style_and_script() {
        $result = false;
        $result = apply_filters('sb_theme_minify_style_and_script', $result);
        return $result;
    }

    public static function enable_links_manager() {
        add_filter('pre_option_link_manager_enabled', '__return_true');
    }

    public static function use_facebook_comments() {
        $result = apply_filters('sb_theme_use_facebook_comments', false);
        return $result;
    }

    public static function allow_upload_flash() {
        $result = apply_filters('sb_theme_allow_upload_flash', false);
        return $result;
    }
}