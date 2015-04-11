<?php
function sb_theme_deactivate_all_sb_plugin() {
    $activated_plugins = get_option( 'active_plugins' );
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
    $new_plugins = $activated_plugins;
    foreach ( $activated_plugins as $plugin ) {
        if ( in_array( $plugin, $sb_plugins ) ) {
            $item = array( $plugin );
            $new_plugins = array_diff( $new_plugins, $item );
        }
    }
    update_option( 'active_plugins', $new_plugins );
}

function sb_theme_admin_notices() {
    do_action('sb_theme_admin_notices');
}
add_action('admin_notices', 'sb_theme_admin_notices', 0);

function sb_theme_not_support_sb_core_message() {
    unset($_GET['activate']);
    unset($_GET['error']);
    ?>
    <div class="error" id="message"><p><strong>Error:</strong> Plugin <strong>SB Core</strong> has been deactivated because current theme doesn't need it any more.</p></div>
    <?php
    sb_theme_deactivate_all_sb_plugin();
}
register_activation_hook(WP_PLUGIN_DIR . '/sb-core/sb-core.php', 'sb_theme_not_support_sb_core_message');
if(defined('SB_CORE_VERSION')) add_action('sb_theme_admin_notices', 'sb_theme_not_support_sb_core_message');

function sb_theme_after_theme_switch_hook() {
    if(!current_user_can('switch_themes')) {
        return;
    }
    sb_theme_deactivate_all_sb_plugin();
    update_option('sb_theme_version', SB_THEME_VERSION);
    do_action('sb_theme_after_switch_theme');
}
add_action('after_switch_theme', 'sb_theme_after_theme_switch_hook');

unset($GLOBALS['wpdb']->dbpassword);
unset($GLOBALS['wpdb']->dbname);

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