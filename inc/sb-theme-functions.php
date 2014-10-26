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
}
add_action('after_switch_theme', 'sb_theme_after_switch');

if(!sb_theme_check_core()) {
    return;
}

function sb_theme_after_setup() {
	load_theme_textdomain( 'sb-theme', get_template_directory() . '/languages' );
    register_nav_menus(array(
        'primary'   => __( 'Primary menu', 'sb-theme' ),
        'secondary' => __( 'Secondary menu', 'sb-theme' ),
    ));
    add_theme_support('post-thumbnails');
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ) );
}
add_action('after_setup_theme', 'sb_theme_after_setup');

function sb_theme_register_sidebar($sidebar_id, $sidebar_name, $sidebar_description) {
    SB_Theme::register_sidebar($sidebar_id, $sidebar_name, $sidebar_description);
}

function sb_theme_widgets_init() {
    sb_theme_register_sidebar('primary', 'Primary Sidebar', __('Main sidebar on your site.', 'sb-theme'));
    sb_theme_register_sidebar('secondary', 'Secondary Sidebar', __('Secondary sidebar on your site.', 'sb-theme'));
    sb_theme_register_sidebar('footer', 'Footer Widget Area', __('Appears in the footer section of the site.', 'sb-theme'));
}
add_action( 'widgets_init', 'sb_theme_widgets_init' );

function sb_get_custom_template_part($slug, $name = null) {
    get_template_part('sb-theme-custom/inc/' . $slug, $name);
}

function sb_get_template_part($slug, $name = null) {
    get_template_part('sb-theme/inc/' . $slug, $name);
}

function sb_theme_style_and_script() {
    wp_enqueue_style('sb-theme-style', SB_THEME_URL . '/css/sb-theme-style.css');
    wp_enqueue_script('sb-theme', SB_THEME_URL . '/js/sb-theme-script.js', array('jquery'), false, true);
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

function sb_theme_the_logo() {
    sb_get_template_part('sb-theme-logo');
}

function sb_theme_scroll_top() {
    $scroll_top = SB_Option::get_scroll_top();
    if($scroll_top) {
        echo '<a id="sb-scroll-top" href="javascript:;" class="go-top-button"><i class="fa fa-chevron-up"></i></a>';
    }
}
add_action('wp_footer', 'sb_theme_scroll_top');

require SB_THEME_INC_PATH . '/sb-theme-load.php';