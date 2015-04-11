<?php
/**
 * Gọi và chạy tất cả các hook trên giao diện.
 *
 * @package SB Theme
 */

function sb_theme_session_start() {
    if ( version_compare( phpversion(), '5.4.0', '>=' ) ) {
        if ( PHP_SESSION_NONE == session_status() ) {
            session_start();
        }
    } else {
        if ( '' == session_id() ) {
            session_start();
        }
    }
}
add_action( 'init', 'sb_theme_session_start' );

function sb_theme_mail_from_name( $name ) {
    if ( 'wordpress' == strtolower( $name ) ) {
        $name = get_bloginfo( 'name' );
    }
    return $name;
}
add_filter( 'wp_mail_from_name', 'sb_theme_mail_from_name' );

function sb_theme_admin_style_and_script() {
    wp_enqueue_media();
    wp_enqueue_script( 'jquery-ui-core' );

    wp_register_style('sb-theme-admin-style', SB_THEME_URL . '/css/sb-theme-admin-style.css');
    wp_register_script( 'sb-theme-admin', SB_THEME_URL . '/js/sb-theme-admin-script.js', array( 'jquery' ), false, true );

    wp_localize_script( 'sb-theme-admin', 'sb_core_admin_ajax', array( 'url' => SB_Core::get_admin_ajax_url() ) );

    wp_enqueue_style('sb-theme-admin-style');
    wp_enqueue_script( 'sb-theme-admin' );
}
add_action( 'admin_enqueue_scripts', 'sb_theme_admin_style_and_script' );

function sb_theme_style_and_script() {
    if(sb_login_page_is_lost_password_custom_page() || sb_login_page_is_account_custom_page()) {
        wp_enqueue_script('password-strength-meter');
        wp_localize_script('password-strength-meter', 'pwsL10n', array(
            'empty' => __('Độ mạnh mật khẩu', 'sb-theme'),
            'short' => __('Rất yếu', 'sb-theme'),
            'bad' => __('Yếu', 'sb-theme'),
            'good' => _x('Trung bình', 'sb-theme'),
            'strong' => __('Mạnh', 'sb-theme'),
            'mismatch' => __('Không khớp', 'sb-theme')
        ));
    }

    wp_enqueue_script('comment-reply');

    do_action('sb_theme_load_lib_scripts');

    wp_register_script( 'superfish', SB_THEME_LIB_URL . '/superfish/js/superfish.min.js', array( 'jquery', 'hoverIntent' ), false, true );
    wp_register_script( 'supersubs', SB_THEME_LIB_URL . '/superfish/js/supersubs.js', array( 'superfish' ), false, true );

    wp_register_style( 'superfish-style', SB_THEME_LIB_URL . '/superfish/css/superfish.css' );
    wp_register_style( 'superfish-vertical-style', SB_THEME_LIB_URL . '/superfish/css/superfish-vertical.css', array( 'superfish-style' ) );
    wp_register_style( 'superfish-navbar-style', SB_THEME_LIB_URL . '/superfish/css/superfish-navbar.css', array( 'superfish-vertical-style' ) );

    wp_register_style( 'sb-theme-style', SB_THEME_URL . '/css/sb-theme-style.css', array( 'superfish-navbar-style' ) );
    wp_register_script( 'sb-theme', SB_THEME_URL . '/js/sb-theme-script.js', array( 'supersubs' ), false, true );
    wp_localize_script( 'sb-theme', 'sb_core_ajax', array( 'url' => SB_Core::get_admin_ajax_url() ) );

    wp_enqueue_style( 'sb-theme-style' );

    wp_enqueue_script( 'sb-theme' );
}
add_action( 'wp_enqueue_scripts', 'sb_theme_style_and_script' );

function sb_login_page_style_and_script() {
    wp_register_style('sb-login-style', SB_THEME_URL . '/css/sb-login-style.css');
    wp_enqueue_style('sb-login-style');
    wp_register_script('sb-login', SB_THEME_URL . '/js/sb-login-script.js', array('jquery'), false, true);
    wp_enqueue_script('sb-login');
    $logo_url = SB_Option::get_login_logo_url();
    if(!empty($logo_url)) {
        echo '<style>';
        echo 'body.login div#login h1 a{background-image:url("'.$logo_url.'");}';
        echo '</style>';
    } else {
        printf('<style>body.login div#login h1 a{display:none;}</style>');
    }
}
add_action('login_enqueue_scripts', 'sb_login_page_style_and_script');

function sb_custom_post_type_and_taxonomy() {
    do_action( 'sb_post_type_and_taxonomy' );
}
add_action( 'init', 'sb_custom_post_type_and_taxonomy', 0 );

add_action( 'wp_head', array( 'SB_Core', 'check_license' ) );

function sb_core_admin_footer() {
    sb_core_ajax_loader();
}
add_action( 'admin_footer', 'sb_core_admin_footer' );

do_action('sb_theme_setup');

function sb_theme_body_class($classes) {
    $browser_code = SB_Browser::get_name();
    if($browser_code != 'ie' && strlen($browser_code) > 2 && SB_PHP::is_string_contain($browser_code, 'ie')) {
        $browser_code .= ' browser-ie';
    }
    $name = 'browser-' . $browser_code;
    $name = trim($name, '-');
    $classes[] = $name;
    if(SB_Detect::is_mobile()) {
        $classes[] = 'mobile';
    } else {
        $classes[] = 'pc';
    }
    if(is_singular()) {
        $classes[] = 'sb-singular';
        if(!is_page()) {
            $classes[] = 'sb-singular-post';
        }
    }
    if(is_404()) {
        $classes[] = 'sb-not-found';
    }
    if(SB_Option::utility_enabled('jquery_snowfall')) {
        $classes[] = 'snowfall';
    }
    $classes[] = 'sb-theme sb-team';
    if(SB_User::is_logged_in()) {
        $classes[] = 'sb-user';
    } else {
        $classes[] = 'sb-guest';
    }
    return $classes;
}
add_filter('body_class', 'sb_theme_body_class');

function sb_theme_post_class($classes) {
    global $post;
    if(!SB_Core::is_error($post)) {
        if(is_sticky($post->ID)) {
            $classes[] = 'sb-post-sticky';
        }
    }
    $classes[] = 'sb-post';
    return $classes;
}
add_filter('post_class', 'sb_theme_post_class');

function sb_theme_after_setup() {
    load_theme_textdomain('sb-theme', get_template_directory() . '/languages');
    add_theme_support( 'automatic-feed-links' );
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption'
        )
    );
    register_nav_menus(
        array(
            'top' => __('Top menu', 'sb-theme'),
            'primary'   => __('Primary menu', 'sb-theme'),
            'secondary' => __('Secondary menu', 'sb-theme'),
            'footer' => __('Footer menu', 'sb-theme')
        )
    );
}
add_action('after_setup_theme', 'sb_theme_after_setup');

function sb_theme_remove_default_image_size($sizes) {
    unset($sizes['thumbnail']);
    unset($sizes['medium']);
    unset($sizes['large']);
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'sb_theme_remove_default_image_size');

function sb_theme_widgets_init() {
    register_widget('SB_Banner_Widget');
    register_widget('SB_Post_Widget');
    register_widget('SB_Tab_Widget');
    sb_theme_register_sidebar('primary', 'Primary Sidebar', __('Main sidebar on your site.', 'sb-theme'));
    sb_theme_register_sidebar('secondary', 'Secondary Sidebar', __('Secondary sidebar on your site.', 'sb-theme'));
    sb_theme_register_sidebar('footer', 'Footer Widget Area', __('Appears in the footer section of the site.', 'sb-theme'));
    do_action('sb_theme_widgets_init');
}
add_action('widgets_init', 'sb_theme_widgets_init');

function sb_theme_after_switch() {
    sb_theme_error_checking();
    if(is_admin() && defined('SB_CORE_VERSION')) {
        sb_theme_update_default_options();
    }

    sb_theme_clean_wp_files();

    update_option('image_default_align', 'center');
    update_option('image_default_link_type', 'none');
    update_option('image_default_size', 'large');

    sb_login_page_create_user_role();

    flush_rewrite_rules();
}
add_action('sb_theme_after_switch_theme', 'sb_theme_after_switch');

function sb_theme_wp_footer() {
    $scroll_top = SB_Option::get_scroll_top();
    if($scroll_top) {
        echo '<a id="sb-scroll-top" href="javascript:;" class="go-top-button"><i class="fa fa-chevron-up"></i></a>';
    }
    if(SB_Option::utility_enabled('float_ads')) {
        sb_theme_get_content('content-float-ads');
    }
    if(sb_theme_support_addthis() || SB_Option::utility_enabled('addthis')) {
        include SB_THEME_LIB_PATH . '/addthis/config.php';
    }
    if(SB_Option::utility_enabled('sharethis')) {
        include SB_THEME_LIB_PATH . '/sharethis/config.php';
    }
    sb_core_ajax_loader();
}
add_action('wp_footer', 'sb_theme_wp_footer');

function sb_theme_wp_head_hook() {
    if(SB_Option::utility_enabled('add_to_head')) {
        echo SB_Option::get_theme_option_single_key('add_to_head');
    }
}
add_action('wp_head', 'sb_theme_wp_head_hook');

function sb_theme_excerpt_more($more) {
    return '...';
}
add_filter( 'excerpt_more', 'sb_theme_excerpt_more' );

function sb_theme_wp_head() {
    SB_Theme::the_favicon_html();
    SB_Theme::the_date_meta_html();
    if ( SB_Option::utility_enabled( 'google_analytics' ) ) {
        SB_Theme::google_analytics_tracking();
    }
}
add_action( 'wp_head', 'sb_theme_wp_head' );

function sb_theme_wordpress_seo_activation() {
    SB_Option::edit_breadcrumb_sep();
}
register_activation_hook( WP_PLUGIN_DIR . '/wordpress-seo/wp-seo.php', 'sb_theme_wordpress_seo_activation' );

function sb_theme_bcn_activation() {
    SB_Option::edit_bcn_breadcrumb_sep();
}
register_activation_hook( WP_PLUGIN_DIR . '/breadcrumb-navxt/breadcrumb-navxt.php', 'sb_theme_bcn_activation' );

function sb_theme_on_sb_core_activation() {
    sb_theme_deactivate_all_sb_plugin();
}
register_activation_hook(WP_PLUGIN_DIR . '/sb-core/sb-core.php', 'sb_theme_on_sb_core_activation');

function sb_theme_statistics() {
    $count_post_views = SB_Option::get_statistics_switch( 'post_views' );
    if ( (bool) $count_post_views ) {
        sb_theme_track_post_views();
    }
    $visitor_statistics = SB_Option::get_statistics_switch( 'visitor_statistics' );
    if ( (bool) $visitor_statistics ) {
        sb_theme_counter();
    }
}

function sb_theme_check_license() {
    if ( sb_core_owner() ) {
        return;
    }
    $is_valid = true;
    if ( ! function_exists( 'sb_theme_check_license' ) || ! method_exists( 'SB_Core', 'check_license' ) || ! has_action( 'wp_head', array( 'SB_Core', 'check_license' ) ) ) {
        $is_valid = false;
    }
    if ( ! $is_valid ) {
        wp_die( __( 'This website is temporarily unavailable, please try again later.', 'sb-theme' ) );
    }
}
add_action( 'init', 'sb_theme_check_license' );

function sb_theme_more_mce_buttons_toolbar_1( $buttons ) {
    $buttons[] = 'fontselect';
    $buttons[] = 'fontsizeselect';
    $buttons[] = 'styleselect';
    return $buttons;
}
add_filter( 'mce_buttons', 'sb_theme_more_mce_buttons_toolbar_1' );

function sb_theme_more_mce_buttons_toolbar_2( $buttons ) {
    $buttons[] = 'subscript';
    $buttons[] = 'superscript';
    $buttons[] = 'hr';
    $buttons[] = 'cut';
    $buttons[] = 'copy';
    $buttons[] = 'paste';
    $buttons[] = 'backcolor';
    $buttons[] = 'newdocument';
    return $buttons;
}
add_filter( 'mce_buttons_2', 'sb_theme_more_mce_buttons_toolbar_2' );

function sb_theme_on_nav_menu_update( $id ) {
    $locations = get_nav_menu_locations();
    if( is_array( $locations ) && $locations ) {
        $locations = array_keys( $locations, $id );
        if( $locations ) {
            foreach( $locations as $location ) {
                delete_transient( 'sb_menu_' . $location );
            }
        }
    }
}
add_action( 'wp_update_nav_menu', 'sb_theme_on_nav_menu_update' );

function sb_theme_sanitize_media_file_name($filename) {
    $filename = SB_PHP::remove_vietnamese(SB_PHP::lowercase($filename));
    return $filename;
}
add_filter('sanitize_file_name', 'sb_theme_sanitize_media_file_name', 10);

function sb_theme_self_ping(&$links) {
    $home = get_option('home');
    foreach($links as $l => $link) {
        if( 0 === strpos($link, $home)) {
            unset($links[$l]);
        }
    }
}
add_action('pre_ping', 'sb_theme_self_ping');

function sb_theme_shortcode_from_excerpt($excerpt) {
    $excerpt = trim(preg_replace('|\[(.+?)\](.+?\[/\\1\])?|s', '', $excerpt));
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $excerpt = trim(trim($excerpt, '&nbsp;'));
    $excerpt = wpautop($excerpt);
    return trim($excerpt);
}
add_filter('the_excerpt', 'sb_theme_shortcode_from_excerpt');

function sb_theme_pre_upload_file( $file ){
    $file['name'] = SB_PHP::remove_vietnamese(SB_PHP::lowercase($file['name']));
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'sb_theme_pre_upload_file' );

function sb_theme_default_image_sizes($sizes) {
    if(isset($sizes['thumbnail'])) {
        unset($sizes['thumbnail']);
    }
    if(isset($sizes['medium'])) {
        unset($sizes['medium']);
    }
    if(isset($sizes['large'])) {
        unset($sizes['large']);
    }
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'sb_theme_default_image_sizes');

remove_filter('the_content', 'wpautop');
add_filter('the_content', 'wpautop' , 99);
add_filter('the_content', 'shortcode_unautop', 100);

remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'feed_links');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');

function sb_core_default_avatar($avatar_defaults) {
    $myavatar = SB_CORE_URL . '/images/sb-default-avatar-32.png';
    $avatar_defaults[$myavatar] = 'SB default avatar';
    return $avatar_defaults;
}
if(SB_Comment::use_default_avatar()) add_filter('avatar_defaults', 'sb_core_default_avatar');

function sb_comment_get_avatar($avatar, $id_or_email, $size, $default, $alt) {
    if(SB_PHP::is_string_contain($avatar, 'avatar-default') && SB_PHP::is_string_contain($avatar, 'sb-default-avatar')) {
        $class = 'avatar avatar-default photo avatar-' . $size;
        $image_source = SB_Comment::get_default_avatar_url();
        if(empty($image_source)) {
            $image_source = SB_THEME_URL . '/images/sb-default-avatar-' . $size . '.png';
            $image_file = SB_THEME_URL . '/images/sb-default-avatar-' . $size . '.png';
            if(!file_exists($image_file)) {
                $image_source = SB_THEME_URL . '/images/sb-default-avatar-100.png';
            }
        }
        $image_source = apply_filters('sb_theme_avatar_default', $image_source);
        $avatar = '<img class="' . $class . '" src="' . $image_source . '" width="' . $size . '" height="' . $size . '" alt="' . $alt . '">';
    }
    return $avatar;
}
if(SB_Comment::use_default_avatar()) add_filter('get_avatar', 'sb_comment_get_avatar', 10, 5);

function sb_comment_get_avatar_options_discussion($avatar, $id_or_email, $size, $default, $alt) {
    if(SB_PHP::is_string_contain($avatar, 'sb-default-avatar')) {
        $class = 'avatar photo avatar-' . $size;
        $avatar_url = SB_Comment::get_default_avatar_url();
        if(empty($avatar_url)) {
            $avatar_url = SB_THEME_URL . '/images/sb-default-avatar-32.png';
        }
        $avatar = '<img class="' . $class . '" src="' . $avatar_url . '" width="' . $size . '" height="' . $size . '" alt="' . $alt . '">';
    }
    return $avatar;
}
if(SB_Comment::use_default_avatar() && $GLOBALS['pagenow'] == 'options-discussion.php') add_filter('get_avatar', 'sb_comment_get_avatar_options_discussion', 10, 5);

function sb_insert_comment($comment_id, $comment_object) {
    if(SB_Comment::enable_spam_check() && empty($comment_object->comment_content)) {
        SB_Comment::delete($comment_id);
        wp_die(__('Bình luận spam đã được phát hiện!', 'sb-theme'));
    }
}
add_action('wp_insert_comment', 'sb_insert_comment', 10, 2);

function sb_transition_comment_status($new_status, $old_status, $comment) {
    if($new_status != $old_status) {
        if('approved' == $new_status && SB_Comment::enable_notify_comment_approved()) {
            SB_Mail::notify_user_for_comment_approved($comment);
        } elseif('spam' == $new_status && SB_Comment::enable_auto_empty_spam()) {
            SB_Comment::delete($comment->comment_ID);
        }
    }
}
add_action('transition_comment_status', 'sb_transition_comment_status', 10, 3);

function sb_preprocess_comment($commentdata) {
    if(SB_Comment::enable_spam_check() && sb_comment_spam($commentdata)) {
        $commentdata['comment_content'] = '';
        SB_Comment::set_spam_session(1);
        return $commentdata;
    }
    $comment_author_url = isset($commentdata['comment_author_url']) ? $commentdata['comment_author_url'] : '';
    if(!empty($comment_author_url) && (!SB_PHP::is_url($comment_author_url) || SB_Comment::disable_website_url())) {
        unset( $commentdata['comment_author_url'] );
    } else {
        $commentdata['comment_author_url'] = SB_PHP::get_domain_name_with_http($comment_author_url);
    }
    if( $commentdata['comment_content'] == SB_PHP::strtoupper( $commentdata['comment_content'] )) {
        $commentdata['comment_content'] = SB_PHP::strtolower( $commentdata['comment_content'] );
    }
    return $commentdata;
}
add_filter('preprocess_comment', 'sb_preprocess_comment', 1);

function sb_comment_nonce_field() {
    wp_nonce_field('sb_comment_form');
}
add_action('comment_form', 'sb_comment_nonce_field');

function sb_comment_stop() {
    if(!wp_verify_nonce($_REQUEST['_wpnonce'], 'sb_comment_form')) {
        wp_die(__('Bình luận của bạn không hợp lệ!', 'sb-theme'));
    }
}
add_action('pre_comment_on_post', 'sb_comment_stop');

function sb_comment_empty_spam_schedule(){
    if(!wp_next_scheduled('sb_comment_empty_spam_cron_job')) {
        wp_schedule_event(time(), 'hourly', 'sb_comment_empty_spam_cron_job');
    }
}
add_action('init', 'sb_comment_empty_spam_schedule');
add_action('sb_comment_empty_spam_cron_job', 'sb_comment_empty_spam_cron_function');

function sb_login_page_logo() {
    return home_url('/');
}
add_filter( 'login_headerurl', 'sb_login_page_logo');

function sb_login_page_logo_title() {
    return get_bloginfo('description');
}
add_filter('login_headertitle', 'sb_login_page_logo_title');

function sb_login_page_init() {
    if(SB_Core::is_login_page()) {
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        if('logout' == $action) {
            return;
        }
        $login_url = sb_login_page_get_page_account_url();
        if(!empty($login_url) && !empty($action)) {
            $login_url = add_query_arg(array('action' => $action), $login_url);
        }
        if(!empty($login_url)) {
            wp_redirect($login_url);
            exit();
        }
    }
}
if(sb_login_page_use_sb_login()) add_action('init', 'sb_login_page_init');

function sb_login_page_custom_init() {
    if(SB_User::is_logged_in()) {
        if(sb_login_page_is_login_custom_page() || sb_login_page_is_lost_password_custom_page() || sb_login_page_is_register_custom_page()) {
            wp_redirect(sb_login_page_get_page_account_url());
            exit;
        }
    } else {
        if(!SB_User::can_register() && sb_login_page_is_register_custom_page()) {
            wp_redirect(home_url('/'));
            exit;
        }
        $action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : '';
        if(sb_login_page_is_account_custom_page()) {
            if('register' == $action && !SB_User::can_register()) {
                wp_redirect(home_url('/'));
                exit;
            }
            if('login' != $action && 'register' != $action && 'lostpassword' != $action && 'verify' != $action) {
                $account_url = sb_login_page_get_page_account_url();
                $account_url = add_query_arg(array('action' => 'login'), $account_url);
                wp_safe_redirect($account_url);
                exit();
            }
            if('verify' == $action) {
                $user_id = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
                if($user_id > 0) {
                    $user = SB_User::get_by('id', $user_id);
                    if(SB_User::is($user)) {
                        $verify_email_session = SB_User::get_verify_email_session($user);
                        if(1 == $verify_email_session) {
                            SB_User::set_verify_email_cookie($user);
                        }
                    }
                }
            }
        } elseif(sb_login_page_is_lost_password_custom_page()) {
            $step = isset($_REQUEST['step']) ? trim($_REQUEST['step']) : '';
            $code = isset($_REQUEST['code']) ? trim($_REQUEST['code']) : '';
            $id = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
            if('verify' == $step && !empty($code) && $id > 0) {
                $user = SB_User::get_by('id', $id);
                if(!is_wp_error($user) && is_a($user, 'WP_User') && SB_User::check_activation_code($user, $code)) {
                    $lost_password_url = SB_User::get_lost_password_verify_url($code);
                    $lost_password_url = add_query_arg(array('step' => 'reset', 'user_id' => $id));
                    wp_safe_redirect($lost_password_url);
                    exit;
                }
            } elseif('reset' == $step) {
                $user = SB_User::get_by('id', $id);
                if(empty($code) || $id < 1 || 'lostpassword' != $action || !SB_User::is($user) || !SB_User::check_activation_code($user, $code)) {
                    wp_redirect(home_url('/'));
                    exit;
                }
            }
        }
    }
}
add_action('sb_login_page_init', 'sb_login_page_custom_init');

function sb_login_page_body_class($classes) {
    if(is_page() && is_page_template()) {
        global $post;
        $login_page_id = sb_login_page_get_page_login_id();
        $account_page_id = sb_login_page_get_page_account_id();
        $lost_password_page_id = sb_login_page_get_page_lost_password_id();
        $register_page_id = sb_login_page_get_page_register_id();
        if($post->ID == $login_page_id) {
            $classes[] = 'sb-theme';
        } elseif($post->ID == $account_page_id) {
            $action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : '';
            if($action == 'verify') {
                $classes[] = 'sb-verify-account';
            }
            $classes[] = 'sb-account-page';
        } elseif($post->ID == $lost_password_page_id) {
            $classes[] = 'sb-lost-password-page';
        } elseif($post->ID == $register_page_id) {
            $classes[] = 'sb-register-page';
        }
        if(SB_User::is_logged_in()) {
            $classes[] = 'sb-user';
        } else {
            $classes[] = 'sb-guest';
        }
    }
    return $classes;
}
add_filter('body_class', 'sb_login_page_body_class');

function sb_login_page_plugin_loaded() {

}
add_action('plugins_loaded', 'sb_login_page_plugin_loaded');

function sb_login_page_plugin_activated() {
    sb_login_page_create_page_templates();
}
add_action('sb_login_page_activation', 'sb_login_page_plugin_activated');

function sb_login_page_plugin_deactivated() {
    sb_login_page_delete_page_templates();
}
add_action('sb_login_page_deactivation', 'sb_login_page_plugin_deactivated');

function sb_login_page_custom_logout_redirect($logout_url, $redirect) {
    $redirect = SB_User::get_logout_redirect();
    $logout_url = add_query_arg(array('redirect_to' => $redirect), $logout_url);
    return $logout_url;
}
if(sb_login_page_use_sb_login()) add_filter('logout_url', 'sb_login_page_custom_logout_redirect', 10, 2);

function sb_login_page_custom_login_redirect($login_url, $redirect) {
    $redirect = SB_User::get_login_redirect();
    $login_url = add_query_arg(array('redirect_to' => $redirect), $login_url);
    return $login_url;
}
if(sb_login_page_use_sb_login()) add_filter('login_url', 'sb_login_page_custom_login_redirect', 10, 2);

function sb_login_page_user_contact_info($fields) {
    $fields['phone'] = __('Phone', 'sb-theme');
    $fields['address'] = __('Address', 'sb-theme');
    $fields['identity'] = __('Identity', 'sb-theme');
    return $fields;
}
add_filter('user_contactmethods', 'sb_login_page_user_contact_info');

function sb_login_page_editable_roles($roles){
    if(!current_user_can('administrator')) {
        unset($roles['administrator']);
    }
    return $roles;
}
add_filter('editable_roles', 'sb_login_page_editable_roles');

function sb_login_page_user_profile_extra_field($user) {
    $user_id = $user->ID;
    $gender = get_the_author_meta('gender', $user_id);
    $user_data = SB_User::get_data($user_id);
    ?>
    <h3><?php _e('Extra information', 'sb-theme'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="gender"><?php _e('Gender', 'sb-theme'); ?></label></th>
            <td>
                <?php
                $args = array(
                    'name' => 'gender',
                    'value' => $gender
                );
                SB_Field::select_gender($args);
                ?>
            </td>
        </tr>
        <tr>
            <th><label for="birthday"><?php _e('Birthday', 'sb-theme'); ?></label></th>
            <td>
                <?php
                $birthday = SB_User::get_birthday_timestamp($user_id);
                $args = array(
                    'value' => $birthday
                );
                SB_Field::select_birthday($args);
                ?>
            </td>
        </tr>
        <?php if(SB_User::is_admin()) : ?>
            <tr>
                <th><label for="user_nicename"><?php _e('User nice name', 'sb-theme'); ?></label></th>
                <td>
                    <input type="text" class="regular-text" value="<?php echo $user_data->user_nicename; ?>" id="user_nicename" name="user_nicename">
                </td>
            </tr>
            <tr>
                <th><label for="activation_code"><?php _e('Activation code', 'sb-theme'); ?></label></th>
                <td>
                    <?php $code = SB_User::get_activation_code($user); ?>
                    <input type="text" class="regular-text" value="<?php echo $code; ?>" id="activation_code" name="activation_code" readonly>
                </td>
            </tr>
        <?php endif; ?>
    </table>
<?php
}
add_action('show_user_profile', 'sb_login_page_user_profile_extra_field');
add_action('edit_user_profile', 'sb_login_page_user_profile_extra_field');

function sb_login_page_save_profile($user_id) {
    update_user_meta($user_id, 'gender', isset($_POST['gender']) ? $_POST['gender'] : 0);
    $birth_day = isset($_POST['user_birth_day']) ? $_POST['user_birth_day'] : date('d');
    $birth_month = isset($_POST['user_birth_month']) ? $_POST['user_birth_month'] : date('m');
    $birth_year = isset($_POST['user_birth_year']) ? $_POST['user_birth_year'] : date('Y');
    $birthday = $birth_year . '-' . $birth_month . '-' . $birth_day;
    $birthday = strtotime($birthday);
    update_user_meta($user_id, 'birthday', $birthday);
    $user_nicename = isset($_POST['user_nicename']) ? $_POST['user_nicename'] : '';
    if(!empty($user_nicename)) {
        $user_data = array(
            'user_nicename' => $user_nicename
        );
        SB_User::update($user_id, $user_data);
    }
}
add_action('personal_options_update', 'sb_login_page_save_profile');
add_action('edit_user_profile_update', 'sb_login_page_save_profile');

function sb_tab_widget_load_sidebar() {
    register_sidebar(array(
        'name'          => __( 'Tabber Widgets', 'sb-theme' ),
        'id'            => 'sb-tabber',
        'description'   => __( 'Display widgets as tabber.', 'sb-theme' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
    $list_sidebars = sb_tab_widget_get_sidebars();
    foreach($list_sidebars as $sidebar) {
        $sidebar_id = $sidebar['id'];
        $sidebar_name = $sidebar['name'];
        $sidebar_description = $sidebar['description'];
        if(!empty($sidebar_id) && !empty($sidebar_name) && !empty($sidebar_description)) {
            SB_Core::register_sidebar($sidebar_id, $sidebar_name, $sidebar_description);
        }
    }
}
add_action('wp_loaded', 'sb_tab_widget_load_sidebar');

function sb_theme_get_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
    if(is_object($id_or_email)) {
        if($id_or_email->comment_ID && $id_or_email->comment_ID > 0) {
            $comment_author = $id_or_email->comment_author;
            $comment_author_ip = $id_or_email->comment_author_IP;
            $id_or_email = $comment_author . '_' . $comment_author_ip;
        }
    }
    $user_key = str_replace('@', '_', $id_or_email);
    $transient_name = 'sb_theme_avatar_' . $user_key;
    if(false === ($avatar_url = get_transient($transient_name))) {
        $avatar_url = SB_PHP::get_image_source($avatar);
        set_transient($transient_name, $avatar_url, DAY_IN_SECONDS);
    }
    $avatar = '<img width="' . $size . '" height="' . $size . '" class="avatar avatar-' . $size . ' photo" src="' . $avatar_url . '" alt="">';
    return $avatar;
}
add_filter( 'get_avatar' , 'sb_theme_get_avatar' , 1 , 5 );