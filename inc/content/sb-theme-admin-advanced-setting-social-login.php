<?php
defined('ABSPATH') or die('Please do not pip me!');
$lang = SB_Core::get_language();
$text = 'Nhập thông tin Facebook APP của bạn, nếu bạn chưa có tài khoản, bạn có thể tạo 1 APP mới <a target="_blank" href="https://developers.facebook.com/">tại đây</a>.';
if('vi' != $lang) {
    $text = sprintf(__('Enter your Facebook APP information, if you don\'t have an account, you can create new APP %s.', 'sb-theme'), '<a target="_blank" href="https://developers.facebook.com/">' . __('at here', 'sb-theme') . '</a>');
}
$args = array(
    'title' => 'Facebook',
    'description' => $text,
    'callback' => 'sb_theme_advanced_setting_social_login_facebook'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_social_login_facebook() {
    $facebook = SB_Option::get_social_login_app('facebook');
    $lang = SB_Core::get_language();
    $value = isset($facebook['enabled']) ? absint($facebook['enabled']) : 0;
    $args = array(
        'id' => 'sb_theme_social_login_facebook_enabled',
        'name' => SB_Option::build_sb_theme_advanced_option_name(array('social_login', 'facebook', 'enabled')),
        'label' => ('vi' == $lang) ? 'Sử dụng đăng nhập bằng tài khoản Facebook?' : __('Allow user to login with Facebook account?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);

    if((bool)$value) {
        $value = isset($facebook['app_id']) ? $facebook['app_id'] : '';
        if(empty($value)) {
            $value = SB_Option::get_socials_facebook_app_id();
        }
        $args = array(
            'id' => 'sb_theme_social_login_facebook_app_id',
            'name' => SB_Option::build_sb_theme_advanced_option_name(array('social_login', 'facebook', 'app_id')),
            'label' => __('APP ID:', 'sb-theme'),
            'value' => $value
        );
        SB_Field::text($args);

        $value = isset($facebook['app_secret']) ? $facebook['app_secret'] : '';
        $args = array(
            'id' => 'sb_theme_social_login_facebook_app_secret',
            'name' => SB_Option::build_sb_theme_advanced_option_name(array('social_login', 'facebook', 'app_secret')),
            'label' => __('APP secret:', 'sb-theme'),
            'value' => $value
        );
        SB_Field::text($args);

        $value = isset($facebook['redirect_uri']) ? $facebook['redirect_uri'] : '';
        $args = array(
            'id' => 'sb_theme_social_login_facebook_redirect_uri',
            'name' => SB_Option::build_sb_theme_advanced_option_name(array('social_login', 'facebook', 'redirect_uri')),
            'label' => __('Redirect uri:', 'sb-theme'),
            'value' => $value
        );
        SB_Field::text($args);
    }
}
$text = 'Nhập thông tin Google APP của bạn, nếu bạn chưa có tài khoản, bạn có thể tạo 1 APP mới <a target="_blank" href="https://console.developers.google.com/project">tại đây</a>.';
if('vi' != $text) {
    $text = sprintf(__('Enter your Google APP information, if you don\'t have an account, you can create one %s.', 'sb-theme'), '<a target="_blank" href="https://console.developers.google.com/project">' . __('at here', 'sb-theme') . '</a>');
}
$args = array(
    'title' => 'Google',
    'description' => $text,
    'callback' => 'sb_theme_advanced_setting_social_login_google'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_social_login_google() {
    $social = SB_Option::get_social_login_app('google');
    $lang = SB_Core::get_language();
    $value = isset($social['enabled']) ? $social['enabled'] : '';
    $value = absint($value);
    $args = array(
        'id' => 'sb_theme_social_login_google_enabled',
        'name' => SB_Option::build_sb_theme_advanced_option_name(array('social_login', 'google', 'enabled')),
        'label' => ('vi' == $lang) ? 'Sử dụng đăng nhập bằng tài khoản Google?' : __('Allow user to login with Google account?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);

    if((bool)$value) {
        $value = isset($social['client_id']) ? $social['client_id'] : '';
        $args = array(
            'id' => 'sb_theme_social_login_google_client_id',
            'name' => SB_Option::build_sb_theme_advanced_option_name(array('social_login', 'google', 'client_id')),
            'label' => __('Client ID:', 'sb-theme'),
            'value' => $value
        );
        SB_Field::text($args);

        $value = isset($social['client_secret']) ? $social['client_secret'] : '';
        $args = array(
            'id' => 'sb_theme_social_login_google_client_secret',
            'name' => SB_Option::build_sb_theme_advanced_option_name(array('social_login', 'google', 'client_secret')),
            'label' => __('Client secret:', 'sb-theme'),
            'value' => $value
        );
        SB_Field::text($args);

        $value = isset($social['api_key']) ? $social['api_key'] : '';
        $args = array(
            'id' => 'sb_theme_social_login_google_api_key',
            'name' => SB_Option::build_sb_theme_advanced_option_name(array('social_login', 'google', 'api_key')),
            'label' => __('API key:', 'sb-theme'),
            'value' => $value
        );
        SB_Field::text($args);

        $value = isset($social['redirect_uri']) ? $social['redirect_uri'] : '';
        $args = array(
            'id' => 'sb_theme_social_login_google_redirect_uri',
            'name' => SB_Option::build_sb_theme_advanced_option_name(array('social_login', 'google', 'redirect_uri')),
            'label' => __('Redirect uri:', 'sb-theme'),
            'value' => $value
        );
        SB_Field::text($args);
    }
}