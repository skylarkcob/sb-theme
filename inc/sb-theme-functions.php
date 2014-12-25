<?php
function sb_theme_init() {
    do_action('sb_theme_init');
}

function sb_theme_error_checking() {
    if(!is_admin()) {
        if(!defined('SB_CORE_VERSION')) {
            wp_die(sprintf(__('You must install and activate plugin %1$s first! Click here to %2$s.', 'sb-theme'), '<a href="https://wordpress.org/plugins/sb-core/">SB Core</a>', sprintf('<a target="_blank" href="%1$s">%2$s</a>', admin_url('themes.php'), __('go back', 'sb-theme'))));
        } elseif(!defined('SB_THEME_VERSION') || !class_exists('SB_Theme')) {
            wp_die(sprintf(__('It looks like you\'re using incorrect %1$s pack! Click here to %2$s.', 'sb-theme'), '<strong>SB Theme Core</strong>', sprintf('<a target="_blank" href="%1$s">%2$s</a>', 'https://github.com/skylarkcob/sb-theme/', __('re-download', 'sb-theme'))));
        }
    }
}

function sb_theme_get_default_theme() {
    $themes = wp_get_themes();
    $wp_theme = '';
    foreach($themes as $theme) {
        $author_uri = $theme->get('AuthorURI');
        if(strrpos($author_uri, 'wordpress.org') !== false) {
            $wp_theme = $theme;
            break;
        }
    }
    if(empty($wp_theme)) {
        foreach($themes as $theme) {
            $text_domain = $theme->get('TextDomain');
            if(strrpos($text_domain, 'sb-theme') === false) {
                $wp_theme = $theme;
                break;
            }
        }
    }
    return $wp_theme;
}

function sb_theme_check_admin_notices() {
    if(!defined('SB_CORE_VERSION') && defined('SB_THEME_VERSION')) {
        unset($_GET['activated']);
        $my_theme = wp_get_theme();
        $theme_name = $my_theme->get('Name');
        printf('<div class="error"><p><strong>' . __('Error', 'sb-theme') . ':</strong> ' . __('The theme with name %1$s will be deactivated because of missing %2$s plugin', 'sb-theme') . '.</p></div>', '<strong>' . $theme_name . '</strong>', sprintf('<a target="_blank" href="%s" style="text-decoration: none">SB Core</a>', 'https://wordpress.org/plugins/sb-core/'));
        sb_theme_switch_to_default_theme();
    } elseif(!class_exists('SB_Theme') && defined('SB_THEME_VERSION')) {
        printf('<div class="error"><p><strong>' . __('Error', 'sb-theme') . ':</strong> ' . __('It looks like you\'re using incorrect %1$s pack! Click here to %2$s.', 'sb-theme') . '</p></div>', '<strong>SB Theme Core</strong>', sprintf('<a target="_blank" href="%1$s" style="text-decoration: none">%2$s</a>', 'https://github.com/skylarkcob/sb-theme/', __('re-download', 'sb-theme')));
    }
}
if(!empty($GLOBALS['pagenow']) && 'themes.php' === $GLOBALS['pagenow']) {
    add_action('admin_notices', 'sb_theme_check_admin_notices', 0);
}

function sb_theme_switch_to_default_theme() {
    $theme = sb_theme_get_default_theme();
    if(!empty($theme)) {
        switch_theme($theme->get('TextDomain'));
    }
}

function sb_theme_check_core() {
    return defined('SB_CORE_VERSION');
}

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

function sb_theme_init_before_running() {
    sb_theme_error_checking();
}
add_action('sb_theme_init', 'sb_theme_init_before_running');

if(!defined('SB_CORE_VERSION')) {
    return;
}

if(!isset($content_width)) {
    $content_width = 800;
}

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

function sb_theme_testing() {
    return apply_filters('sb_theme_testing', false);
}

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

function sb_theme_register_sidebar($sidebar_id, $sidebar_name, $sidebar_description) {
    SB_Theme::register_sidebar($sidebar_id, $sidebar_name, $sidebar_description);
}

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

function sb_get_template_part($slug, $name = null) {
    $slug = 'sb-theme/inc/' . $slug;
    get_template_part($slug, $name);
}

function sb_theme_get_content($slug, $name = null) {
    sb_get_template_part('content/' . $slug, $name);
}

function sb_get_custom_template_part($slug, $name = null) {
    $slug = 'sb-theme-custom/inc/' . $slug;
    get_template_part($slug, $name);
}

function sb_get_custom_loop($slug, $name = null) {
    sb_get_custom_template_part('loop/' . $slug, $name);
}

function sb_get_custom_meta_box($slug, $name = null) {
    sb_get_custom_template_part('meta-box/' . $slug, $name);
}

function sb_get_custom_content($slug, $name = null) {
    sb_get_custom_template_part('content/' . $slug, $name);
}

function sb_get_custom_content_default_query($slug, $name = null) {
    while(have_posts()) {
        the_post();
        sb_get_custom_content($slug);
    }
}

function sb_get_custom_ajax($slug, $name = null) {
    sb_get_custom_template_part('ajax/' . $slug, $name);
}

function sb_get_custom_module($slug, $name = null) {
    sb_get_custom_template_part('module/' . $slug, $name);
}

function sb_get_custom_modal($slug, $name = null) {
    sb_get_custom_template_part('modal/' . $slug, $name);
}

function sb_get_custom_carousel($slug, $name = null) {
    sb_get_custom_template_part('carousel/' . $slug, $name);
}

function sb_theme_add_term_meta($args = array()) {
    if(!class_exists('SB_Term_Meta')) {
        require SB_CORE_INC_PATH . '/class-sb-term-meta.php';
    }
    $term_meta = new SB_Term_Meta($args);
}

function sb_theme_term_meta_field_term_select($args = array()) {
    if(!class_exists('SB_Term_Field')) {
        require SB_CORE_INC_PATH . '/class-sb-term-field.php';
    }
    SB_Term_Field::term_select($args);
}

function sb_theme_style_and_script() {
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

function sb_theme_add_setting_field($field_id, $field_title, $callback) {
    SB_Admin_Custom::add_setting_field($field_id, $field_title, 'sb_theme_setting_section', $callback, 'sb_theme');
}

function sb_theme_add_utilities_setting_field($field_id, $field_title, $callback) {
    SB_Admin_Custom::add_setting_field($field_id, $field_title, 'sb_utilities_section', $callback, 'sb_utilities');
}

function sb_theme_add_statistics_setting_field($field_id, $field_title, $callback) {
    SB_Admin_Custom::add_setting_field($field_id, $field_title, 'sb_statistics_section', $callback, 'sb_statistics');
}

function sb_theme_the_logo() {
    sb_theme_get_content('sb-theme-logo');
}

function sb_theme_support($args = null) {
    global $sb_theme_supports;
    if($args) {
        if(!is_array($sb_theme_supports)) {
            if(!is_array($args)) {
                $args = array($args);
            }
            $sb_theme_supports = $args;
        } else {
            if(!is_array($args)) {
                array_push($sb_theme_supports, $args);
            } else {
                $sb_theme_supports = array_merge($sb_theme_supports, $args);
            }
        }
    }
    if(!is_array($sb_theme_supports)) {
        $sb_theme_supports = array();
    }
    $sb_theme_supports = array_unique($sb_theme_supports);
    $sb_theme_supports = apply_filters('sb_theme_supports', $sb_theme_supports);
    return $sb_theme_supports;
}

function sb_get_theme_support() {
    global $sb_theme_supports;
    if(!is_array($sb_theme_supports)) {
        $sb_theme_supports = array();
    }
    $sb_theme_supports = apply_filters('sb_theme_supports', $sb_theme_supports);
    return $sb_theme_supports;
}

function sb_theme_support_shop() {
    return sb_theme_check_support('shop');
}

function sb_theme_support_addthis() {
    return sb_theme_check_support('addthis');
}

function sb_theme_support_term_meta() {
    return sb_theme_check_support('term_meta');
}

function sb_theme_check_support($name) {
    $supports = sb_get_theme_support();
    if(in_array($name, $supports)) {
        return true;
    }
    return false;
}

function sb_theme_the_post_thumbnail_crop($width, $height) {
    SB_Post::the_thumbnail_crop_html($width, $height);
}

function sb_theme_the_post_thumbnail_crop_only_image($width, $height) {
    SB_Post::the_thumbnail_crop_only_image_html($width, $height);
}

function sb_theme_the_post_thumbnail_only_link_image($width, $height) {
    SB_Post::the_thumbnail_crop_only_link_image_html($width, $height);
}

function sb_theme_the_post_thumbnail_crop_by_id($post_id, $width, $height) {
    SB_Post::the_thumbnail_crop_html_by_id($post_id, $width, $height);
}

function sb_theme_the_post_thumbnail($args = array()) {
    $thumbnail_url = SB_Post::get_thumbnail_url($args);
    $width = isset($args['width']) ? intval($args['width']) : 0;
    $height = isset($args['height']) ? intval($args['height']) : 0;
    if($width < 16) {
        $width = $height;
    }
    if($height < 16) {
        $height = $width;
    }
    if($width > 15) {
        $crop = isset($args['crop']) ? $args['crop'] : false;
        $crop = (bool)$crop;
        $defaults = array(
            'width' => $width,
            'height' => $height,
            'crop' => $crop
        );
        $params = isset($args['params']) ? $args['params'] : array();
        $params = (array)$params;
        $params = wp_parse_args($params, $defaults);
        $thumbnail_url = bfi_thumb($thumbnail_url, $params);
    }
    $args['thumbnail_url'] = $thumbnail_url;
    SB_Post::the_thumbnail_html($args);
}

function sb_theme_change_default_image_setting() {
    update_option('image_default_align', 'center');
    update_option('image_default_link_type', 'none');
    update_option('image_default_size', 'large');
}

function sb_theme_get_custom_image_url($name) {
    return SB_THEME_CUSTOM_URL . '/images/' . $name;
}

function sb_theme_paginate($args = array()) {
    SB_Theme::the_paginate($args);
}

function sb_theme_comment_template() {
    SB_Theme::the_comment_template();
}

function sb_theme_frontend_language($locale) {
    if(!is_admin()) {
        $locale = SB_Option::get_default_language();
        return $locale;
    }
}
add_filter('locale', 'sb_theme_frontend_language', 1, 1);

function sb_theme_backend_language($locale) {
    if(is_admin()) {
        return 'en';
    }
}
add_filter('locale', 'sb_theme_backend_language');

function sb_theme_update_default_options() {
    sb_theme_change_default_image_setting();
    SB_Core::regenerate_htaccess_file();
    SB_Option::edit_breadcrumb_sep();
    SB_Option::edit_bcn_breadcrumb_sep();
}

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

require SB_THEME_INC_PATH . '/sb-theme-load.php';