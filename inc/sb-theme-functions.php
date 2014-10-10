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
	$activated_plugins = get_option('active_plugins');
	$sb_core_installed = in_array('sb-core/sb-core.php', $activated_plugins);
	if(!$sb_core_installed) {
		$theme = wp_get_theme();
		$text_domain = $theme->get('TextDomain');
		if('sb-theme' == $text_domain) {
			$theme = sb_theme_get_default_theme();
			if(!empty($theme)) {
				switch_theme($theme->get('TextDomain'));
			}
		}
	}
	return $sb_core_installed;
}

sb_theme_check_core();

function sb_theme_after_switch() {
	if(!sb_theme_check_core()) {
		wp_die(sprintf(__('You must install and activate the plugin %1$s first! Click here to %2$s.', 'sb-theme'), '<a href="https://wordpress.org/plugins/sb-core/">SB Core</a>', sprintf('<a href="%1$s">%2$s</a>', admin_url('themes.php'), __('go back', 'sb-theme'))));
	}
}
add_action('after_switch_theme', 'sb_theme_after_switch');

function sb_theme_after_setup() {
	load_theme_textdomain( 'sb-theme', get_template_directory() . '/languages' );
    register_nav_menus(array(
        'primary'   => __( 'Primary menu', 'sb-theme' ),
        'secondary' => __( 'Secondary menu', 'sb-theme' ),
    ));
    add_theme_support( 'post-thumbnails' );
}
add_action('after_setup_theme', 'sb_theme_after_setup');

function sb_theme_register_sidebar($sidebar_id, $sidebar_name, $sidebar_description) {
    SB_Theme::register_sidebar($sidebar_id, $sidebar_name, $sidebar_description);
}

function sb_theme_widgets_init() {
    sb_theme_register_sidebar('primary', 'Primary Sidebar', 'Main sidebar on your site.');
    sb_theme_register_sidebar('secondary', 'Secondary Sidebar', 'Secondary sidebar on your site.');
    sb_theme_register_sidebar('footer', 'Footer Widget Area', 'Appears in the footer section of the site.');
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

require SB_THEME_INC_PATH . '/sb-theme-load.php';