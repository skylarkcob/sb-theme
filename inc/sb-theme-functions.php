<?php
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

function sb_theme_check_core() {
    $user_deactivate_sb_core = false;
    $sb_core_activated = intval(get_option('sb_core_activated'));
    if($sb_core_activated == 0) {
        $caller = get_option('sb_core_deactivated_caller');
        if('user' == $caller || empty($caller) || 'wp' != $caller) {
            $user_deactivate_sb_core = true;
        }
    }
    if(is_admin() && !$user_deactivate_sb_core) {
        return true;
    }
	$sb_core_installed = class_exists('SB_Core');
	if(!$sb_core_installed) {
        $theme = sb_theme_get_default_theme();
        if(!empty($theme)) {
            switch_theme($theme->get('TextDomain'));
        }
	}
	return $sb_core_installed;
}

function sb_theme_after_switch() {
	if(!sb_theme_check_core()) {
		wp_die(sprintf(__('You must install and activate plugin %1$s first! Click here to %2$s.', 'sb-theme'), '<a href="https://wordpress.org/plugins/sb-core/">SB Core</a>', sprintf('<a href="%1$s">%2$s</a>', admin_url('themes.php'), __('go back', 'sb-theme'))));
	}
    sb_theme_change_default_image_setting();
    SB_Core::regenerate_htaccess_file();
}
add_action('after_switch_theme', 'sb_theme_after_switch');

if(!sb_theme_check_core()) {
    return;
}

if(!isset($content_width)) {
    $content_width = 800;
}

function sb_theme_body_class($classes) {
    $name = 'browser-' . SB_Browser::get_name();
    $classes[] = $name;
    if(SB_Detect::is_mobile()) {
        $classes[] = 'mobile';
    } else {
        $classes[] = 'pc';
    }
    $classes[] = 'sb-theme sb-team';
    return $classes;
}
add_filter('body_class', 'sb_theme_body_class');

function sb_theme_post_class($classes) {
    $classes[] = 'sb-post';
    return $classes;
}
add_filter('post_class', 'sb_theme_post_class');

function sb_theme_after_setup() {
    load_theme_textdomain('sb-theme', get_template_directory() . '/languages');
    register_nav_menus(
        array(
            'primary'   => __('Primary menu', 'sb-theme'),
            'secondary' => __('Secondary menu', 'sb-theme'),
            'footer' => __('Footer menu', 'sb-theme')
        )
    );
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
    sb_theme_register_sidebar('primary', 'Primary Sidebar', __('Main sidebar on your site.', 'sb-theme'));
    sb_theme_register_sidebar('secondary', 'Secondary Sidebar', __('Secondary sidebar on your site.', 'sb-theme'));
    sb_theme_register_sidebar('footer', 'Footer Widget Area', __('Appears in the footer section of the site.', 'sb-theme'));
}
add_action( 'widgets_init', 'sb_theme_widgets_init' );

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
    wp_enqueue_style('sb-theme-style', SB_THEME_URL . '/css/sb-theme-style.css');
    wp_enqueue_script('sb-theme', SB_THEME_URL . '/js/sb-theme-script.js', array('jquery'), false, true);
    if(SB_Option::utility_enabled('jquery_marquee')) {
        wp_enqueue_script('jquery-marquee', SB_THEME_LIB_URL . '/jquery-marquee/jquery.marquee.min.js', array('jquery'), false, true);
    }
}
add_action('wp_enqueue_scripts', 'sb_theme_style_and_script');

function sb_theme_admin_style_and_script() {
    $screen = get_current_screen();
    if(strrpos($screen->base, 'sb-options') !== false) {
        wp_enqueue_script('sb-theme-admin', SB_THEME_URL . '/js/sb-theme-admin-script.js', array('jquery'), false, true);
        wp_enqueue_style('sb-theme-admin-style', SB_THEME_URL . '/css/sb-theme-admin-style.css');
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

function sb_theme_the_logo() {
    sb_theme_get_content('sb-theme-logo');
}

function sb_theme_scroll_top() {
    $scroll_top = SB_Option::get_scroll_top();
    if($scroll_top) {
        echo '<a id="sb-scroll-top" href="javascript:;" class="go-top-button"><i class="fa fa-chevron-up"></i></a>';
    }
}
add_action('wp_footer', 'sb_theme_scroll_top');

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

require SB_THEME_INC_PATH . '/sb-theme-load.php';