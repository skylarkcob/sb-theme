<?php
defined('ABSPATH') or die('Please do not pip me!');

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
    define( 'SB_THEME_VERSION', '2.5.0' );

    define('SB_THEME_JQUERY_VERSION', '1.11.1');

    define( 'SB_THEME_WP_ADMIN_PATH', ABSPATH . 'wp-admin' );

    define( 'SB_THEME_ADMIN_INC_PATH', SB_THEME_WP_ADMIN_PATH . '/includes' );

    define( 'SB_THEME_PATH', dirname( __FILE__ ) );

    define( 'SB_THEME_URL', untrailingslashit( get_template_directory_uri() ) . '/sb-theme' );

    define( 'SB_THEME_INC_PATH', SB_THEME_PATH . '/inc' );

    define( 'SB_THEME_PLUGIN_PATH', SB_THEME_PATH . '/plugins' );

    define( 'SB_THEME_PLUGIN_URL', SB_THEME_URL . '/plugins' );

    require SB_THEME_INC_PATH . '/sb-theme-load.php';
}