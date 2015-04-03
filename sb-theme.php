<?php
/**
 * Khai báo các hằng cần thiết load SB Theme.
 *
 * @package SB Theme
 */

/** Khai báo phiên bản hiện tại của SB Theme */
define( 'SB_THEME_VERSION', '1.5.8' );

/** Khai báo phiên bản SB Core tối thiểu để SB Theme hoạt động */
define( 'SB_THEME_USE_CORE_VERSION', '1.6.2' );

/** Khai báo đường dẫn đến thư mục sb-theme */
define( 'SB_THEME_PATH', untrailingslashit(get_template_directory()) . '/sb-theme' );

/** Khai báo đường dẫn đến thư mục sb-theme */
define( 'SB_THEME_URL', untrailingslashit(get_template_directory_uri()) . '/sb-theme' );

/** Khai báo đường dẫn đến thư mục inc */
define( 'SB_THEME_INC_PATH', SB_THEME_PATH . '/inc' );

/** Load tập tin chính để khởi tạo SB Theme */
require SB_THEME_INC_PATH . '/sb-theme-load.php';