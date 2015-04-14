<?php
/**
 * Khai báo các hằng cần thiết load SB Theme.
 *
 * @package SB Theme
 */

/** Khai báo phiên bản hiện tại của SB Theme */
define( 'SB_THEME_VERSION', '1.7.3' );

/** Khai báo đường dẫn đến thư mục wp-admin */
define( 'SB_THEME_WP_ADMIN_PATH', ABSPATH . 'wp-admin' );

/** Khai báo đường dẫn đến thư mục wp-admin/includes */
define( 'SB_THEME_ADMIN_INC_PATH', SB_THEME_WP_ADMIN_PATH . '/includes' );

/** Khai báo đường dẫn đến thư mục sb-theme */
define( 'SB_THEME_PATH', dirname( __FILE__ ) );

/** Khai báo đường dẫn đến thư mục sb-theme */
define( 'SB_THEME_URL', untrailingslashit( get_template_directory_uri() ) . '/sb-theme' );

/** Khai báo đường dẫn đến thư mục inc */
define( 'SB_THEME_INC_PATH', SB_THEME_PATH . '/inc' );

/** Load tập tin chính để khởi tạo SB Theme */
require SB_THEME_INC_PATH . '/sb-theme-load.php';