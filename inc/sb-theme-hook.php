<?php
do_action('sb_theme_setup');

function sb_theme_init_before_running() {
    sb_theme_error_checking();
}
add_action('sb_theme_init', 'sb_theme_init_before_running');

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
    if(SB_Option::statistics_enabled() && class_exists('SB_Statistics_Widget')) {
        register_widget('SB_Statistics_Widget');
    }
    sb_theme_register_sidebar('primary', 'Primary Sidebar', __('Main sidebar on your site.', 'sb-theme'));
    sb_theme_register_sidebar('secondary', 'Secondary Sidebar', __('Secondary sidebar on your site.', 'sb-theme'));
    if(SB_Option::utility_enabled('leaderboard_ads')) {
        sb_theme_register_sidebar('leaderboard-ads', 'Leaderboard ads', __('The avertising on top of site.', 'sb-theme'));
    }
    if(SB_Option::utility_enabled('float_ads')) {
        sb_theme_register_sidebar('float-ads-left', 'Float ads left', __('The avertising on the left of site.', 'sb-theme'));
        sb_theme_register_sidebar('float-ads-right', 'Float ads right', __('The avertising on the right of site.', 'sb-theme'));
    }
    sb_theme_register_sidebar('footer', 'Footer Widget Area', __('Appears in the footer section of the site.', 'sb-theme'));
}
add_action('widgets_init', 'sb_theme_widgets_init');

function sb_theme_after_switch() {
    if(!current_user_can('switch_themes')) {
        return;
    }
    sb_theme_error_checking();
    if(is_admin() && defined('SB_CORE_VERSION')) {
        sb_theme_update_default_options();
    }
}
add_action('after_switch_theme', 'sb_theme_after_switch');

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

if(sb_theme_support_term_meta() || SB_Option::utility_enabled('term_meta')) {
    function sb_theme_custom_term_meta() {
        wp_enqueue_media();
    }
    if(SB_Admin_Custom::is_edit_term_page()) add_action('admin_enqueue_scripts', 'sb_theme_custom_term_meta');
}

function sb_theme_admin_style_and_script() {
    $screen = SB_Admin_Custom::get_current_page();
    if(strrpos($screen, 'sb_theme') !== false) {
        if(sb_theme_testing()) {
            wp_enqueue_script('sb-theme-admin', SB_THEME_URL . '/js/sb-theme-admin-script.js', array('jquery'), false, true);
            wp_enqueue_style('sb-theme-admin-style', SB_THEME_URL . '/css/sb-theme-admin-style.css');
        } else {
            wp_enqueue_script('sb-theme-admin', SB_THEME_URL . '/js/sb-theme-admin-script.min.js', array('jquery'), false, true);
            wp_enqueue_style('sb-theme-admin-style', SB_THEME_URL . '/css/sb-theme-admin-style.min.css');
        }
    }
}
add_action('admin_enqueue_scripts', 'sb_theme_admin_style_and_script');

function sb_theme_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'sb_theme_excerpt_more');

function sb_theme_wp_head() {
    SB_Theme::the_favicon_html();
    SB_Theme::the_date_meta_html();
    if(SB_Option::utility_enabled('google_analytics')) {
        SB_Theme::google_analytics_tracking();
    }
}
add_action('wp_head', 'sb_theme_wp_head');

function sb_theme_wordpress_seo_activation() {
    SB_Option::edit_breadcrumb_sep();
}
register_activation_hook(WP_PLUGIN_DIR . '/wordpress-seo/wp-seo.php', 'sb_theme_wordpress_seo_activation');

function sb_theme_bcn_activation() {
    SB_Option::edit_bcn_breadcrumb_sep();
}
register_activation_hook(WP_PLUGIN_DIR . '/breadcrumb-navxt/breadcrumb-navxt.php', 'sb_theme_bcn_activation');

function sb_theme_style_and_script() {
    wp_register_script('superfish', SB_THEME_LIB_URL . '/superfish/js/superfish.min.js', array('jquery', 'hoverIntent'), false, true);
    wp_register_script('supersubs', SB_THEME_LIB_URL . '/superfish/js/supersubs.js', array('superfish'), false, true);
    wp_enqueue_script('supersubs');

    wp_register_style('superfish-style', SB_THEME_LIB_URL . '/superfish/css/superfish.css');
    wp_register_style('superfish-vertical-style', SB_THEME_LIB_URL . '/superfish/css/superfish-vertical.css', array('superfish-style'));
    wp_register_style('superfish-navbar-style', SB_THEME_LIB_URL . '/superfish/css/superfish-navbar.css', array('superfish-vertical-style'));
    wp_enqueue_style('superfish-navbar-style');
    if(sb_theme_testing()) {
        wp_enqueue_style('sb-theme-style', SB_THEME_URL . '/css/sb-theme-style.css');
        wp_enqueue_script('sb-theme', SB_THEME_URL . '/js/sb-theme-script.js', array('jquery'), false, true);
    } else {
        wp_enqueue_style('sb-theme-style', SB_THEME_URL . '/css/sb-theme-style.min.css');
        wp_enqueue_script('sb-theme', SB_THEME_URL . '/js/sb-theme-script.min.js', array('jquery'), false, true);
    }
    if(SB_Option::utility_enabled('jquery_marquee')) {
        wp_enqueue_script('jquery-marquee', SB_THEME_LIB_URL . '/jquery-marquee/jquery.marquee.min.js', array('jquery'), false, true);
    }
    if(SB_Option::utility_enabled('jquery_snowfall')) {
        wp_enqueue_script('jquery-snowfall', SB_THEME_LIB_URL . '/jquery-snowfall/snowfall.jquery.min.js', array('jquery'), false, true);
    }
}
add_action('wp_enqueue_scripts', 'sb_theme_style_and_script');

function sb_theme_statistics() {
    $count_post_views = SB_Option::get_statistics_switch('post_views');
    if((bool)$count_post_views) {
        sb_theme_track_post_views();
    }
    $visitor_statistics = SB_Option::get_statistics_switch('visitor_statistics');
    if((bool)$visitor_statistics) {
        sb_theme_counter();
    }
}
if(SB_Option::statistics_enabled()) add_action('sb_theme_init', 'sb_theme_statistics');

function sb_theme_check_license() {
    if(sb_core_owner()) {
        return;
    }
    $is_valid = true;
    if(!function_exists('sb_core_check_license') || !method_exists('SB_Core', 'check_license') || !has_action('wp_head', array('SB_Core', 'check_license'))) {
        $is_valid = false;
    }
    if(!$is_valid) {
        wp_die(__('This website is temporarily unavailable, please try again later.', 'sb-theme'));
    }
}
add_action('init', 'sb_theme_check_license');