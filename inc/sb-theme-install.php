<?php
defined('ABSPATH') or die('Please do not pip me!');

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

function sb_theme_save_wordpress_default_data() {
    $opts = SB_Option::get();
    $save_roles = isset($opts['wp_default']['roles']) ? $opts['wp_default']['roles'] : '';
    if(!is_array($save_roles) || count($save_roles) < 1) {
        $default_roles = array (
            'administrator' => array (
                'name' => 'Administrator',
                'capabilities' => array (
                    'switch_themes' => true,
                    'edit_themes' => true,
                    'activate_plugins' => true,
                    'edit_plugins' => true,
                    'edit_users' => true,
                    'edit_files' => true,
                    'manage_options' => true,
                    'moderate_comments' => true,
                    'manage_categories' => true,
                    'manage_links' => true,
                    'upload_files' => true,
                    'import' => true,
                    'unfiltered_html' => true,
                    'edit_posts' => true,
                    'edit_others_posts' => true,
                    'edit_published_posts' => true,
                    'publish_posts' => true,
                    'edit_pages' => true,
                    'read' => true,
                    'level_10' => true,
                    'level_9' => true,
                    'level_8' => true,
                    'level_7' => true,
                    'level_6' => true,
                    'level_5' => true,
                    'level_4' => true,
                    'level_3' => true,
                    'level_2' => true,
                    'level_1' => true,
                    'level_0' => true,
                    'edit_others_pages' => true,
                    'edit_published_pages' => true,
                    'publish_pages' => true,
                    'delete_pages' => true,
                    'delete_others_pages' => true,
                    'delete_published_pages' => true,
                    'delete_posts' => true,
                    'delete_others_posts' => true,
                    'delete_published_posts' => true,
                    'delete_private_posts' => true,
                    'edit_private_posts' => true,
                    'read_private_posts' => true,
                    'delete_private_pages' => true,
                    'edit_private_pages' => true,
                    'read_private_pages' => true,
                    'delete_users' => true,
                    'create_users' => true,
                    'unfiltered_upload' => true,
                    'edit_dashboard' => true,
                    'update_plugins' => true,
                    'delete_plugins' => true,
                    'install_plugins' => true,
                    'update_themes' => true,
                    'install_themes' => true,
                    'update_core' => true,
                    'list_users' => true,
                    'remove_users' => true,
                    'add_users' => true,
                    'promote_users' => true,
                    'edit_theme_options' => true,
                    'delete_themes' => true,
                    'export' => true
                )
            ),
            'editor' => array (
                'name' => 'Editor',
                'capabilities' => array (
                    'moderate_comments' => true,
                    'manage_categories' => true,
                    'manage_links' => true,
                    'upload_files' => true,
                    'unfiltered_html' => true,
                    'edit_posts' => true,
                    'edit_others_posts' => true,
                    'edit_published_posts' => true,
                    'publish_posts' => true,
                    'edit_pages' => true,
                    'read' => true,
                    'level_7' => true,
                    'level_6' => true,
                    'level_5' => true,
                    'level_4' => true,
                    'level_3' => true,
                    'level_2' => true,
                    'level_1' => true,
                    'level_0' => true,
                    'edit_others_pages' => true,
                    'edit_published_pages' => true,
                    'publish_pages' => true,
                    'delete_pages' => true,
                    'delete_others_pages' => true,
                    'delete_published_pages' => true,
                    'delete_posts' => true,
                    'delete_others_posts' => true,
                    'delete_published_posts' => true,
                    'delete_private_posts' => true,
                    'edit_private_posts' => true,
                    'read_private_posts' => true,
                    'delete_private_pages' => true,
                    'edit_private_pages' => true,
                    'read_private_pages' => true
                )
            ),
            'author' => array (
                'name' => 'Author',
                'capabilities' => array (
                    'upload_files' => true,
                    'edit_posts' => true,
                    'edit_published_posts' => true,
                    'publish_posts' => true,
                    'read' => true,
                    'level_2' => true,
                    'level_1' => true,
                    'level_0' => true,
                    'delete_posts' => true,
                    'delete_published_posts' => true
                )
            ),
            'contributor' => array (
                'name' => 'Contributor',
                'capabilities' => array (
                    'edit_posts' => true,
                    'read' => true,
                    'level_1' => true,
                    'level_0' => true,
                    'delete_posts' => true
                )
            ),
            'subscriber' => array (
                'name' => 'Subscriber',
                'capabilities' => array (
                    'read' => true,
                    'level_0' => true
                )
            )
        );
        global $wp_roles;
        if(is_object($wp_roles)) {
            $opts['wp_default']['roles'] = $wp_roles->roles;
        } else {
            $opts['wp_default']['roles'] = $default_roles;
        }
        SB_Option::update($opts);
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
    <div class="updated" id="message">
        <p><strong><?php _e('Chú ý:', 'sb-theme'); ?></strong> <?php printf(__('%s đã được ngừng kích hoạt vì giao diện không cần dùng đến nó nữa.', 'sb-theme'), 'Plugin <strong>SB Core</strong>'); ?></p>
    </div>
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
    sb_theme_save_wordpress_default_data();
    sb_theme_deactivate_all_sb_plugin();
    update_option('sb_theme_version', SB_THEME_VERSION);
    sb_theme_remove_all_sb_plugin();
    SB_Membership::init_roles_and_capabilities();
    do_action('sb_theme_after_switch_theme');
    do_action('sb_theme_activation');
}
add_action('after_switch_theme', 'sb_theme_after_theme_switch_hook');

/** Xóa mật khẩu đăng nhập cơ sở dữ liệu */
unset($GLOBALS['wpdb']->dbpassword);

/** Xóa tên cơ sở dữ liệu */
unset($GLOBALS['wpdb']->dbname);

/*
 * Thay đổi jQuery sang CDN của Google
 */
$sb_theme_use_jquery_cdn = apply_filters('sb_theme_use_jquery_cdn', false);

function sb_theme_change_jquery() {
    wp_enqueue_script('jquery');
    $jquery_handle = (version_compare($GLOBALS['wp_version'], '3.6-alpha1', '>=')) ? 'jquery-core' : 'jquery';
    $wp_jquery_ver = $GLOBALS['wp_scripts']->registered[$jquery_handle]->ver;
    $jquery_ver = ($wp_jquery_ver == '') ? SB_THEME_JQUERY_VERSION : $wp_jquery_ver;
    wp_deregister_script('jquery');
    wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/' . $jquery_ver . '/jquery.min.js', array(), false, false);
    wp_enqueue_script('jquery');
}
if($sb_theme_use_jquery_cdn) add_action('wp_enqueue_scripts', 'sb_theme_change_jquery');

function sb_theme_jquery_fallback() {
    echo '<script>window.jQuery || document.write(\'<script type="text/javascript" src="' . includes_url('js/jquery/jquery.js') . '"><\/script>\')</script>' . "\n";
}
if($sb_theme_use_jquery_cdn) add_action('sb_theme_footer_before', 'sb_theme_jquery_fallback');

/**
 * Thêm menu vào admin bar.
 */
function sb_theme_add_to_admin_bar($wp_admin_bar) {
    if ( current_user_can( 'manage_options' ) ) {
        $args = array(
            'id'        => 'sb-options',
            'title'     => 'SB Options',
            'href'      => admin_url( 'admin.php?page=sb_options' ),
            'meta'      => array( 'class' => 'sb-options' ),
            'parent'    => 'site-name',
            'tabindex'  => '100'
        );
        $wp_admin_bar->add_node( $args );

        $args = array(
            'id'        => 'sbt-settings',
            'title'     => __('Cài đặt giao diện', 'sb-theme'),
            'href'      => admin_url( 'admin.php?page=sb_theme' ),
            'parent'    => 'sb-options'
        );
        $wp_admin_bar->add_node( $args );

        $args = array(
            'id'        => 'sbt-smtp-email',
            'title'     => __('SMTP Email', 'sb-theme'),
            'href'      => admin_url( 'admin.php?page=sbt_smtp_email' ),
            'parent'    => 'sb-options'
        );
        $wp_admin_bar->add_node( $args );

        $args = array(
            'id'        => 'sbt-advanced-settings',
            'title'     => __('Cài đặt nâng cao', 'sb-theme'),
            'href'      => admin_url( 'admin.php?page=sbt_advanced' ),
            'parent'    => 'sb-options'
        );
        $wp_admin_bar->add_node( $args );

        $args = array(
            'id'        => 'sbt-pagination',
            'title'     => __('Phân trang', 'sb-theme'),
            'href'      => admin_url( 'admin.php?page=sb_paginate' ),
            'parent'    => 'sb-options'
        );
        $wp_admin_bar->add_node( $args );

	    $args = array(
		    'id'        => 'sbt-options-permalink',
		    'title'     => __('Đường dẫn tĩnh', 'sb-theme'),
		    'href'      => admin_url( 'options-permalink.php' ),
		    'parent'    => 'sb-options'
	    );
	    $wp_admin_bar->add_node( $args );

        $args = array(
            'id'        => 'sbt-widgets',
            'title'     => __('Widgets', 'sb-theme'),
            'href'      => admin_url( 'widgets.php' ),
            'parent'    => 'sb-options'
        );
        $wp_admin_bar->add_node( $args );

        $args = array(
            'id'        => 'sbt-menus',
            'title'     => __('Menus', 'sb-theme'),
            'href'      => admin_url( 'nav-menus.php' ),
            'parent'    => 'sb-options'
        );
        $wp_admin_bar->add_node( $args );

        $args = array(
            'id'        => 'sbt-about',
            'title'     => __('Giới thiệu SB', 'sb-theme'),
            'href'      => admin_url( 'admin.php?page=sb_options' ),
            'parent'    => false
        );
        $wp_admin_bar->add_node( $args );

        $args = array(
            'id'        => 'sbt-plugins',
            'title'     => __('Plugins', 'sb-theme'),
            'href'      => admin_url( 'plugins.php' ),
            'parent'    => 'sb-options'
        );
        $wp_admin_bar->add_node( $args );
    }
}
add_action( 'admin_bar_menu', 'sb_theme_add_to_admin_bar', 999 );

/** Tạo hook sau khi cài đặt SB Theme */
do_action('sb_theme_install_after');