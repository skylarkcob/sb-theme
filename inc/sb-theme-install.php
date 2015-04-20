<?php
/** Tạo hook trước khi cài đặt SB Theme */
do_action('sb_theme_install_before');

/**
 * Hàm khai báo thông tin đường dẫn SB Plugins.
 */
function sb_theme_get_sb_plugins_file() {
    $sb_plugins = array(
        'sb-banner-widget/sb-banner-widget.php',
        'sb-clean/sb-clean.php',
        'sb-comment/sb-comment.php',
        'sb-core/sb-core.php',
        'sb-login-page/sb-login-page.php',
        'sb-paginate/sb-paginate.php',
        'sb-post-widget/sb-post-widget.php',
        'sb-tab-widget/sb-tab-widget.php',
        'sb-tbfa/sb-tbfa.php'
    );
    return $sb_plugins;
}

/**
 * Ngừng hoạt động toàn bộ SB Plugins.
 */
function sb_theme_deactivate_all_sb_plugin() {
    $activated_plugins = get_option( 'active_plugins' );
    $sb_plugins = sb_theme_get_sb_plugins_file();
    $new_plugins = $activated_plugins;
    foreach ( $activated_plugins as $plugin ) {
        if ( in_array( $plugin, $sb_plugins ) ) {
            $item = array( $plugin );
            $new_plugins = array_diff( $new_plugins, $item );
        }
    }
    update_option( 'active_plugins', $new_plugins );
}

/**
 * Xóa toàn bộ SB Plugins.
 */
function sb_theme_remove_all_sb_plugin($cache_day = 1) {
    $transient_name = 'sb_plugins_deleted';
    if(false === get_transient($transient_name)) {
        $sb_plugins = sb_theme_get_sb_plugins_file();
        if(!function_exists('delete_plugins')) {
            require SB_THEME_ADMIN_INC_PATH . '/file.php';
            require SB_THEME_ADMIN_INC_PATH . '/plugin.php';
        }
        delete_plugins($sb_plugins);
        set_transient($transient_name, 1, $cache_day * DAY_IN_SECONDS);
    }
}

/**
 * Chạy hook admin_init trong dashboard.
 */
function sb_theme_admin_init_hook() {
    if(file_exists(WP_PLUGIN_DIR . '/sb-core')) {
        sb_theme_remove_all_sb_plugin(10);
    }
    $saved_version = get_option('sb_theme_version');
    $transient_name = SB_Cache::build_init_role_transient_name();
    if(version_compare($saved_version, SB_THEME_VERSION, '<')) {
        update_option('sb_theme_version', SB_THEME_VERSION);
        delete_transient($transient_name);
        do_action('sb_theme_upgrade');
    }
    if(false === get_transient($transient_name)) {
        SB_Membership::init_roles_and_capabilities();
        set_transient($transient_name, 1, YEAR_IN_SECONDS);
    }

    do_action('sb_theme_admin_init');
}
add_action('admin_init', 'sb_theme_admin_init_hook');

/**
 * Chạy hook thông báo trong dashboard.
 */
function sb_theme_admin_notices() {
    do_action('sb_theme_admin_notices');
}
add_action('admin_notices', 'sb_theme_admin_notices', 0);

/**
 * Kiểm tra và xóa toàn bộ SB Plugins.
 */
function sb_theme_not_support_sb_core_message() {
    unset($_GET['activate']);
    unset($_GET['error']);
    ?>
    <div class="updated" id="message"><p><strong>Note:</strong> Plugin <strong>SB Core</strong> has been deactivated because current theme doesn't need it any more.</p></div>
    <?php
    sb_theme_deactivate_all_sb_plugin();
}
register_activation_hook(WP_PLUGIN_DIR . '/sb-core/sb-core.php', 'sb_theme_not_support_sb_core_message');
if(defined('SB_CORE_VERSION')) add_action('sb_theme_admin_notices', 'sb_theme_not_support_sb_core_message');

/**
 * Chạy hook sau khi giao diện được kích hoạt.
 */
function sb_theme_after_theme_switch_hook() {
    if(!current_user_can('switch_themes')) {
        return;
    }
    sb_theme_deactivate_all_sb_plugin();
    update_option('sb_theme_version', SB_THEME_VERSION);
    sb_theme_remove_all_sb_plugin();
    SB_Membership::init_roles_and_capabilities();
    do_action('sb_theme_after_switch_theme');
}
add_action('after_switch_theme', 'sb_theme_after_theme_switch_hook');

/** Xóa mật khẩu đăng nhập cơ sở dữ liệu */
unset($GLOBALS['wpdb']->dbpassword);

/** Xóa tên cơ sở dữ liệu */
unset($GLOBALS['wpdb']->dbname);

/**
 * Thêm menu vào admin bar.
 */
function sb_theme_add_to_admin_bar( $wp_admin_bar ) {
    if ( current_user_can( 'manage_options' ) ) {
        $args = array(
            'id'        => 'sb-options',
            'title'     => 'SB Options',
            'href'      => admin_url( 'admin.php?page=sb_options' ),
            'meta'      => array( 'class' => 'sb-options' ),
            'parent'    => 'site-name',
            'tabindex'  => '10'
        );
        $wp_admin_bar->add_node( $args );
    }
}
add_action( 'admin_bar_menu', 'sb_theme_add_to_admin_bar' );

/** Tạo hook sau khi cài đặt SB Theme */
do_action('sb_theme_install_after');