<?php
defined('ABSPATH') or die('Please do not pip me!');
require get_template_directory() . '/sb-theme-custom/inc/sb-theme-custom-filter-utilities.php';
do_action('sb_theme_load_before');
require SB_THEME_INC_PATH . '/sb-theme-install.php';
if(defined('SB_CORE_VERSION')) {
    sb_theme_deactivate_all_sb_plugin();
    return;
}
require SB_THEME_INC_PATH . '/sb-theme-constant.php';
do_action('sb_theme_load');
require SB_THEME_INC_PATH . '/class-sb-cache.php';
require SB_THEME_INC_PATH . '/class-sb-text.php';
require SB_THEME_INC_PATH . '/class-sb-php.php';
require SB_THEME_INC_PATH . '/class-sb-message.php';
require SB_THEME_INC_PATH . '/class-sb-default-setting.php';
require SB_THEME_INC_PATH . '/class-sb-core.php';
require SB_THEME_INC_PATH . '/class-sb-option.php';
require SB_THEME_INC_PATH . '/class-sb-tool.php';
require SB_THEME_INC_PATH . '/sb-theme-global-variable.php';
require SB_THEME_INC_PATH . '/class-sb-cart.php';
require SB_THEME_INC_PATH . '/class-sb-captcha.php';
require SB_THEME_INC_PATH . '/class-sb-mail.php';
require SB_THEME_INC_PATH . '/class-sb-user.php';
require SB_THEME_INC_PATH . '/class-sb-query.php';
require SB_THEME_INC_PATH . '/class-sb-membership.php';
require SB_THEME_PLUGIN_PATH . '/really-simple-captcha/really-simple-captcha.php';
if(SB_Tool::use_custom_menu()) {
    require SB_THEME_INC_PATH . '/class-sb-menu-walker.php';
    require SB_THEME_INC_PATH . '/class-sb-menu-edit-walker.php';
    require SB_THEME_INC_PATH . '/class-sb-custom-menu.php';
    $GLOBALS['sb_theme_custom_menu'] = new SB_Custom_Menu();
    require SB_THEME_INC_PATH . '/widgets/class-sb-menu-widget.php';
}
do_action('sb_theme_load_middle');
require SB_THEME_INC_PATH . '/class-sb-payment.php';
require SB_THEME_INC_PATH . '/class-sb-ngan-luong.php';
require SB_THEME_INC_PATH . '/class-sb-post.php';
require SB_THEME_INC_PATH . '/class-sb-product.php';
require SB_THEME_INC_PATH . '/class-sb-ads.php';
require SB_THEME_INC_PATH . '/class-sb-html.php';
require SB_THEME_INC_PATH . '/class-sb-video.php';
require SB_THEME_INC_PATH . '/class-sb-term.php';
require SB_THEME_INC_PATH . '/class-sb-plugin.php';
require SB_THEME_INC_PATH . '/class-sb-list-plugin.php';
require SB_THEME_INC_PATH . '/class-sb-meta-box.php';
require SB_THEME_INC_PATH . '/class-sb-meta-field.php';
require SB_THEME_INC_PATH . '/class-sb-page-template.php';
require SB_THEME_INC_PATH . '/class-sb-geo.php';
require SB_THEME_INC_PATH . '/class-sb-ajax.php';
require SB_THEME_INC_PATH . '/class-sb-field.php';
require SB_THEME_INC_PATH . '/class-sb-widget-field.php';
require SB_THEME_INC_PATH . '/class-sb-admin-custom.php';
require SB_THEME_INC_PATH . '/class-sb-admin.php';
require SB_THEME_INC_PATH . '/widgets/class-sb-banner-widget.php';
require SB_THEME_INC_PATH . '/class-sb-spam.php';
require SB_THEME_INC_PATH . '/class-sb-comment.php';
require SB_THEME_INC_PATH . '/class-sb-paginate.php';
require SB_THEME_INC_PATH . '/class-sb-base-slug.php';
require SB_THEME_INC_PATH . '/widgets/class-sb-support-widget.php';
require SB_THEME_INC_PATH . '/widgets/class-sb-post-widget.php';
require SB_THEME_INC_PATH . '/widgets/class-sb-tab-widget.php';
require SB_THEME_INC_PATH . '/widgets/class-sb-video-widget.php';
require SB_THEME_INC_PATH . '/widgets/class-sb-icon-widget.php';
require SB_THEME_INC_PATH . '/widgets/class-sb-subscribe-widget.php';
if(SB_Option::social_login_enabled()) {
    $facebook = SB_Option::get_social_login_app('facebook');
    $facebook_enabled = isset($facebook['enabled']) ? (bool)$facebook['enabled'] : false;
    $google = SB_Option::get_social_login_app('google');
    $google_enabled = isset($google['enabled']) ? (bool)$google['enabled'] : false;
    $twitter = SB_Option::get_social_login_app('twitter');
    $twitter_enabled = isset($twitter['enabled']) ? (bool)$twitter['enabled'] : false;
    if($facebook_enabled) {
        if(SB_PHP::compare_version('5.4', '<')) {
            require SB_THEME_LIB_PATH . '/facebook-php-sdk/facebook.php';
        } else {
            require SB_THEME_LIB_PATH . '/facebook-php-sdk-v4/autoload.php';
        }
    }
    if($google_enabled) {
        if(!class_exists('Google_Client')) {
            require SB_THEME_LIB_PATH . '/google-api-php-client/src/Google/autoload.php';
        }
    }
}
require SB_THEME_INC_PATH . '/class-sb-login.php';
require SB_THEME_INC_PATH . '/sb-theme-functions.php';
require SB_THEME_INC_PATH . '/class-sb-lib.php';
require SB_THEME_INC_PATH . '/class-sb-css.php';
if(!function_exists('bfi_thumb')) {
    require SB_THEME_LIB_PATH . '/bfi-thumb/BFI_Thumb.php';
}
require SB_THEME_INC_PATH . '/class-sb-browser.php';
require SB_THEME_INC_PATH . '/class-sb-detect.php';
if(SB_Tool::use_term_meta()) {
    if(!class_exists('SB_Term_Field')) {
        require SB_CORE_INC_PATH . '/class-sb-term-field.php';
    }
    if(!class_exists('SB_Term_Meta')) {
        require SB_CORE_INC_PATH . '/class-sb-term-meta.php';
    }
}
require SB_THEME_INC_PATH . '/class-sb-theme.php';
require SB_THEME_INC_PATH . '/sb-theme-admin.php';
require SB_THEME_INC_PATH . '/sb-theme-admin-plugin.php';
require SB_THEME_INC_PATH . '/sb-theme-setting-callback.php';
require SB_THEME_INC_PATH . '/sb-utilities-setting-callback.php';
if(SB_Option::statistics_enabled() || SB_Theme::support('sb_statistics')) {
    require SB_THEME_INC_PATH . '/sb-statistics-setting-callback.php';
    require SB_THEME_INC_PATH . '/widgets/class-sb-statistics-widget.php';
}
require SB_THEME_INC_PATH . '/sb-theme-meta.php';
require SB_THEME_INC_PATH . '/sb-theme-hook.php';
require SB_THEME_INC_PATH . '/sb-theme-post-types-and-taxonomies.php';
require SB_THEME_INC_PATH . '/sb-theme-ajax.php';
do_action('sb_theme_loaded');
if(file_exists(SB_THEME_CUSTOM_PATH . '/sb-theme-custom.php')) {
    require SB_THEME_CUSTOM_PATH . '/sb-theme-custom.php';
}
do_action('sb_theme_load_after');