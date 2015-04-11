<?php
/**
 * Load các tập tin và thư viện cần thiết để giao diện hoạt động.
 *
 * @package SB Theme
 */

/** Load tập tin cài đặt SB Theme */
require SB_THEME_INC_PATH . '/sb-theme-install.php';

if(defined('SB_CORE_VERSION')) {
    sb_theme_deactivate_all_sb_plugin();
    return;
}

/** Load tập tin khai báo các hằng sử dụng trên SB Theme */
require SB_THEME_INC_PATH . '/sb-theme-constant.php';

do_action('sb_theme_load');

require SB_THEME_INC_PATH . '/class-sb-text.php';

require SB_THEME_INC_PATH . '/class-sb-php.php';

require SB_THEME_INC_PATH . '/class-sb-message.php';

require SB_THEME_INC_PATH . '/class-sb-default-setting.php';

require SB_THEME_INC_PATH . '/class-sb-core.php';

require SB_THEME_INC_PATH . '/class-sb-option.php';

require SB_THEME_INC_PATH . '/class-sb-captcha.php';

require SB_THEME_INC_PATH . '/class-sb-mail.php';

require SB_THEME_INC_PATH . '/class-sb-user.php';

require SB_THEME_INC_PATH . '/class-sb-query.php';

require SB_THEME_INC_PATH . '/class-sb-post.php';

require SB_THEME_INC_PATH . '/class-sb-html.php';

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

require SB_THEME_INC_PATH . '/class-sb-comment.php';

require SB_THEME_INC_PATH . '/class-sb-paginate.php';

require SB_THEME_INC_PATH . '/widgets/class-sb-post-widget.php';

require SB_THEME_INC_PATH . '/widgets/class-sb-tab-widget.php';

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

/*
 * Kiểm tra nếu class SB_Detect không tồn tại thì load class này.
 */
if(!class_exists('SB_Detect')) {

    /** Load class SB_Detect */
    require SB_THEME_INC_PATH . '/class-sb-detect.php';

}

/** Load tập tin khởi tạo chức năng quản lý trong Dashboard */
require SB_THEME_INC_PATH . '/sb-theme-admin.php';

/** Load class SB_Theme */
require SB_THEME_INC_PATH . '/class-sb-theme.php';

/** Load tập tin khai báo các hàm callback của bảng điều khiển */
require SB_THEME_INC_PATH . '/sb-theme-setting-callback.php';

/** Load tập tin khai báo các hàm callback của các tiện ích */
require SB_THEME_INC_PATH . '/sb-utilities-setting-callback.php';

/** Load tập tin khai báo hook trên SB Theme */
require SB_THEME_INC_PATH . '/sb-theme-hook.php';

require SB_THEME_INC_PATH . '/sb-theme-ajax.php';

do_action('sb_theme_loaded');

/*
 * Kiểm tra nếu giao diện có hỗ trợ SB Theme Custom thì load các chức năng tùy chỉnh.
 */
if(file_exists(SB_THEME_CUSTOM_PATH . '/sb-theme-custom.php')) {

    /** Load tập tin cài đặt giao diện tùy chỉnh */
    require SB_THEME_CUSTOM_PATH . '/sb-theme-custom.php';

}