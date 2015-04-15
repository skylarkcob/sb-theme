<?php
/**
 * Load các tập tin và thư viện cần thiết để giao diện hoạt động.
 *
 * @package SB Theme
 */

/** Load tập tin khai báo các hằng sử dụng trên SB Theme */
require SB_THEME_INC_PATH . '/sb-theme-constant.php';

/** Load tập tin cài đặt SB Theme */
require SB_THEME_INC_PATH . '/sb-theme-install.php';

/*
* Kiểm tra SB Core có tồn tại hay không, nếu SB Core có tồn tại thì kiểm
* tra phiên bản SB Core tối thiểu có phù hợp với phiên bản của SB Theme
* hiện tại hay không, nếu không phù hợp thì dừng load các tập tin.
*/
if(!sb_theme_check_core() || !sb_theme_is_valid_core_version()) {

    return;

}

/** Load tập tin khai báo các hàm trên SB Theme */
require SB_THEME_INC_PATH . '/sb-theme-functions.php';

require SB_THEME_INC_PATH . '/class-sb-lib.php';

/*
 * Kiểm tra nếu giao diện có hỗ trợ meta cho term hoặc tiện ích chức năng
 * meta cho term được bật thì load class cần thiết.
 */
if(sb_theme_support_term_meta() || SB_Option::utility_enabled('term_meta')) {

    /*
     * Kiểm tra nếu không tồn tại class SB_Term_Meta thì load class này.
     */
    if(!class_exists('SB_Term_Meta')) {

        /** Load class SB_Term_Meta */
        require SB_CORE_INC_PATH . '/class-sb-term-meta.php';

    }

    // Kiểm tra nếu không tồn tại class SB_Term_Field thì load class này.
    if(!class_exists('SB_Term_Field')) {

        /** Load class SB_Term_Field */
        require SB_CORE_INC_PATH . '/class-sb-term-field.php';

    }

    /** Load tập tin khai báo meta */
    require SB_THEME_INC_PATH . '/sb-theme-meta.php';

}

/** Load class SB_CSS */
require SB_THEME_INC_PATH . '/class-sb-css.php';

/*
 * Kiểm tra xem giao diện có phải là trang bán hàng hay không, hoặc chức năng trang bán hàng được
 * kích hoạt, và class SB_Product không tồn tại.
 */
if((sb_theme_support_shop() || SB_Option::utility_enabled('shop')) && !class_exists('SB_Product')) {

    /** Load class SB_Product */
    require SB_THEME_INC_PATH . '/class-sb-product.php';

}

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

/** Load tập tin khai báo các hàm callback của các tiện ích */
require SB_THEME_INC_PATH . '/sb-utilities-setting-callback.php';

/*
 * Kiểm tra nếu chức năng thống kê được kích hoạt thì load các hàm callback, load
 * widget hiển thị thống kê.
 */
if(SB_Option::statistics_enabled()) {

    /** Load tập tin khai báo các hàm callback của chức năng thống kê */
    require SB_THEME_INC_PATH . '/sb-statistics-setting-callback.php';

    /** Load class SB_Statistics_Widget */
    require SB_THEME_INC_PATH . '/widgets/class-sb-statistics-widget.php';

}

/** Load tập tin khai báo các hàm callback của bảng điều khiển */
require SB_THEME_INC_PATH . '/sb-theme-setting-callback.php';

/** Load tập tin khai báo hook trên SB Theme */
require SB_THEME_INC_PATH . '/sb-theme-hook.php';

/*
 * Kiểm tra giao diện cần sử dụng Facebook PHP SDK, nếu cần thì load thư viện này.
 */
if(SB_Option::utility_enabled('facebook_php_sdk') && !class_exists('Facebook')) {

    /** Load Facebook PHP SDK */
    require SB_THEME_LIB_PATH . '/facebook-php-sdk/facebook.php';

}

/*
 * Kiểm tra nếu giao diện có hỗ trợ SB Theme Custom thì load các chức năng tùy chỉnh.
 */
if(file_exists(SB_THEME_CUSTOM_PATH . '/sb-theme-custom.php')) {

    /** Load tập tin cài đặt giao diện tùy chỉnh */
    require SB_THEME_CUSTOM_PATH . '/sb-theme-custom.php';

}