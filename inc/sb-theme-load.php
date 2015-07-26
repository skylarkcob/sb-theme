<?php
defined('ABSPATH') or die('Please do not pip me!');

/**
 * Load các tập tin và thư viện cần thiết để giao diện hoạt động.
 *
 * @package SB Theme
 */

require get_template_directory() . '/sb-theme-custom/inc/sb-theme-custom-filter-utilities.php';

/** Tạo hook trước khi load SB Theme */
do_action('sb_theme_load_before');

/** Load tập tin cài đặt SB Theme */
require SB_THEME_INC_PATH . '/sb-theme-install.php';

if(defined('SB_CORE_VERSION')) {
    sb_theme_deactivate_all_sb_plugin();
    return;
}

/** Load tập tin khai báo các hằng sử dụng trên SB Theme */
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

require SB_THEME_INC_PATH . '/sb-theme-global-variable.php';

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

if(SB_Option::social_login_enabled()) {
    $facebook = SB_Option::get_social_login_app('facebook');
    $facebook_enabled = isset($facebook['enabled']) ? (bool)$facebook['enabled'] : false;
    $google = SB_Option::get_social_login_app('google');
    $google_enabled = isset($google['enabled']) ? (bool)$google['enabled'] : false;
    $twitter = SB_Option::get_social_login_app('twitter');
    $twitter_enabled = isset($twitter['enabled']) ? (bool)$twitter['enabled'] : false;
    if($facebook_enabled) {
        if(SB_PHP::compare_version('5.4', '<')) {
            /** Load Facebook PHP SDK */
            require SB_THEME_LIB_PATH . '/facebook-php-sdk/facebook.php';
        } else {
            /** Load Facebook PHP SDK version 4 */
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

/** Load tập tin khai báo các hàm trên SB Theme */
require SB_THEME_INC_PATH . '/sb-theme-functions.php';

require SB_THEME_INC_PATH . '/class-sb-lib.php';

/** Load class SB_CSS */
require SB_THEME_INC_PATH . '/class-sb-css.php';

/*
 * Kiểm tra nếu hàm bfi_thumb không tồn tại thì load tập tin.
 */
if(!function_exists('bfi_thumb')) {

    /** Load BFI Thumb */
    require SB_THEME_LIB_PATH . '/bfi-thumb/BFI_Thumb.php';

}

/** Load class SB_Browser */
require SB_THEME_INC_PATH . '/class-sb-browser.php';

/** Load class SB_Detect */
require SB_THEME_INC_PATH . '/class-sb-detect.php';

/*
 * Kiểm tra nếu giao diện có hỗ trợ meta cho term hoặc tiện ích chức năng
 * meta cho term được bật thì load class cần thiết.
 */
if(SB_Tool::use_term_meta()) {
    // Kiểm tra nếu không tồn tại class SB_Term_Field thì load class này.
    if(!class_exists('SB_Term_Field')) {
        /** Load class SB_Term_Field */
        require SB_CORE_INC_PATH . '/class-sb-term-field.php';
    }

    /*
     * Kiểm tra nếu không tồn tại class SB_Term_Meta thì load class này.
     */
    if(!class_exists('SB_Term_Meta')) {
        /** Load class SB_Term_Meta */
        require SB_CORE_INC_PATH . '/class-sb-term-meta.php';
    }
}

/** Load class SB_Theme */
require SB_THEME_INC_PATH . '/class-sb-theme.php';

/** Load tập tin khởi tạo chức năng quản lý trong Dashboard */
require SB_THEME_INC_PATH . '/sb-theme-admin.php';

require SB_THEME_INC_PATH . '/sb-theme-admin-plugin.php';

/** Load tập tin khai báo các hàm callback của bảng điều khiển */
require SB_THEME_INC_PATH . '/sb-theme-setting-callback.php';

/** Load tập tin khai báo các hàm callback của các tiện ích */
require SB_THEME_INC_PATH . '/sb-utilities-setting-callback.php';

/*
 * Kiểm tra nếu chức năng thống kê được kích hoạt thì load các hàm callback, load
 * widget hiển thị thống kê.
 */
if(SB_Option::statistics_enabled() || SB_Theme::support('sb_statistics')) {
    /** Load tập tin khai báo các hàm callback của chức năng thống kê */
    require SB_THEME_INC_PATH . '/sb-statistics-setting-callback.php';
    /** Load class SB_Statistics_Widget */
    require SB_THEME_INC_PATH . '/widgets/class-sb-statistics-widget.php';
}

/** Load tập tin khai báo meta */
require SB_THEME_INC_PATH . '/sb-theme-meta.php';

/** Load tập tin khai báo hook trên SB Theme */
require SB_THEME_INC_PATH . '/sb-theme-hook.php';

require SB_THEME_INC_PATH . '/sb-theme-post-types-and-taxonomies.php';

require SB_THEME_INC_PATH . '/sb-theme-ajax.php';

do_action('sb_theme_loaded');

/*
 * Kiểm tra nếu giao diện có hỗ trợ SB Theme Custom thì load các chức năng tùy chỉnh.
 */
if(file_exists(SB_THEME_CUSTOM_PATH . '/sb-theme-custom.php')) {

    /** Load tập tin cài đặt giao diện tùy chỉnh */
    require SB_THEME_CUSTOM_PATH . '/sb-theme-custom.php';

}

/** Tạo hook sau khi load SB Theme */
do_action('sb_theme_load_after');