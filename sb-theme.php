<?php
/**
 * Khai báo các hằng cần thiết load SB Theme.
 *
 * @package SB Theme
 */

function sb_theme_use_old_version_update() {
    $trasient_name = 'sb_theme_use_old_version';
    if(false === ($use_old_version = get_transient($trasient_name))) {
        $use_old_version = absint(sb_theme_use_old_version());
        update_option('sb_theme_use_old_version', $use_old_version);
        set_transient($trasient_name, $use_old_version, 4 * DAY_IN_SECONDS);
    }
}
add_action('admin_init', 'sb_theme_use_old_version_update');

if(sb_theme_use_old_version()) {
    require get_template_directory() . '/sb-theme/old/sb-theme.php';
} else {
    /** Khai báo phiên bản hiện tại của SB Theme */
    define( 'SB_THEME_VERSION', '2.2.7' );

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

    define( 'SB_THEME_PLUGIN_PATH', SB_THEME_PATH . '/plugins' );

    define( 'SB_THEME_PLUGIN_URL', SB_THEME_URL . '/plugins' );

    /** Load tập tin chính để khởi tạo SB Theme */
    require SB_THEME_INC_PATH . '/sb-theme-load.php';
}