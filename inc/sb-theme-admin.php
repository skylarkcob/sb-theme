<?php
$sb_admin = new SB_Admin();

add_action( 'init', 'sb_theme_check_license' );

function is_sb_admin_page() {
    $result = SB_Admin_Custom::is_sb_page();
    return apply_filters( 'sb_admin_page', $result );
}

function sb_theme_menu() {
    SB_Admin_Custom::add_submenu_page(__('Theme Settings', 'sb-theme'), 'sb_theme', array('SB_Admin_Custom', 'setting_page_callback'));
    SB_Admin_Custom::add_submenu_page('SB Utilities', 'sb_utilities', array('SB_Admin_Custom', 'setting_page_callback'));
	SB_Admin_Custom::add_submenu_page('SB Statistics', 'sb_statistics', array('SB_Admin_Custom', 'setting_page_callback'));
    SB_Admin_Custom::add_submenu_page(__('Advanced Settings', 'sb-theme'), 'sbt_advanced', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_theme_menu');

function sb_theme_setting_tab($tabs) {
    $tabs['sb_theme'] = array('title' => __('Theme Settings', 'sb-theme'), 'section_id' => 'sb_theme_setting_section', 'type' => 'theme');
    $tabs['sb_utilities'] = array('title' => 'Utilities', 'section_id' => 'sb_utilities_section', 'type' => 'theme');
	$tabs['sb_statistics'] = array('title' => 'Statistics', 'section_id' => 'sb_statistics_section', 'type' => 'theme');
    $tabs['sbt_advanced'] = array('title' => __('Advanced Settings', 'sb-theme'), 'section_id' => 'sb_theme_advanced_setting_section', 'type' => 'theme');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_theme_setting_tab');

function sb_theme_advanced_setting_field() {
    SB_Admin_Custom::add_section('sb_theme_advanced_setting_section', __('SB Theme Advanced Settings Page', 'sb-theme'), 'sbt_advanced');
    SB_Admin_Custom::add_setting_field('sb_theme_advanced_setting_page_content', '', 'sb_theme_advanced_setting_section', array('SB_Admin_Custom', 'row_setting_page_callback'), 'sbt_advanced');
}
add_action('sb_theme_option_page_init', 'sb_theme_advanced_setting_field');

function sb_theme_setting_field() {
    SB_Admin_Custom::add_section('sb_theme_setting_section', __('SB Theme options page', 'sb-theme'), 'sb_theme');
    SB_Admin_Custom::add_setting_field('sb_theme_default_language', __('Language', 'sb-theme'), 'sb_theme_setting_section', 'sb_theme_default_language_callback', 'sb_theme');
    SB_Admin_Custom::add_setting_field('sb_theme_logo', 'Logo', 'sb_theme_setting_section', 'sb_theme_logo_callback', 'sb_theme');
    SB_Admin_Custom::add_setting_field('sb_theme_favicon', 'Favicon', 'sb_theme_setting_section', 'sb_theme_favicon_callback', 'sb_theme');
    if(SB_Option::utility_enabled('add_to_head')) {
        sb_theme_setting_field_add_to_head();
    }
    if(SB_Option::utility_enabled('default_thumbnail')) {
        sb_theme_setting_field_no_thumbnail();
    }
    if(SB_Option::utility_enabled('google_analytics')) {
        sb_theme_setting_field_google_analtyics();
    }
    if(SB_Option::utility_enabled('category_widget')) {
        sb_theme_setting_field_category_widget();
    }
    if(SB_Option::utility_enabled('facebook_php_sdk')) {
        sb_theme_setting_field_facebook_api();
    }
    if(SB_Option::utility_enabled('sharethis')) {
        sb_theme_setting_field_sharethis();
    }
    if(SB_Option::utility_enabled('addthis') || sb_theme_support_addthis()) {
        sb_theme_setting_field_addthis();
    }
    if(SB_Option::utility_enabled('facebook_fanpage')) {
        sb_theme_setting_field_facebook_fanpage();
    }
    do_action('sb_theme_setting_field');
    do_action('sb_theme_setting_page');
    if(SB_Option::utility_enabled('social')) {
        sb_theme_setting_field_social();
    }
    if(SB_Option::utility_enabled('footer_text')) {
        sb_theme_setting_field_footer_text();
    }
}
add_action('sb_admin_init', 'sb_theme_setting_field');

function sb_utilities_setting_field() {
    SB_Admin_Custom::add_section('sb_utilities_section', __('SB Utilities options page', 'sb-theme'), 'sb_utilities');
    do_action('sb_utilities_setting_field');
}
add_action('sb_admin_init', 'sb_utilities_setting_field');

function sb_statistics_setting_field() {
    SB_Admin_Custom::add_section('sb_statistics_section', __('SB Statistics options page', 'sb-theme'), 'sb_statistics');
    do_action('sb_statistics_setting_field');
	do_action('sb_theme_statistics_setting_page');
}
add_action('sb_admin_init', 'sb_statistics_setting_field');

function sb_theme_default_language_callback() {
    $lang = SB_Option::get_default_language();
    $options = array(
        'vi' => __('Vietnamese', 'sb-theme'),
        'en' => __('English', 'sb-theme')
    );
    $args = array(
        'id' => 'sb_theme_default_language',
        'name' => 'sb_options[theme][default_language]',
        'value' => $lang,
        'options' => $options,
        'description' => __('Choose language to use on front-end.', 'sb-theme')
    );
    SB_Field::select($args);
}

function sb_theme_logo_callback() {
    $value = SB_Option::get_logo_url();
    $args = array(
        'id' => 'sb_theme_logo',
        'name' => 'sb_options[theme][logo]',
        'value' => $value,
        'container_class' => 'margin-bottom',
        'description' => __('You can enter url or upload new logo image file.', 'sb-theme')
    );
    SB_Field::media_image($args);
    $value = SB_Option::get_logo_type();
    $list_options = array(
        'background' => __('Background image', 'sb-theme'),
        'image' => __('Image', 'sb-theme'),
        'text' => __('Text', 'sb-theme')
    );
    $args = array(
        'id' => 'sb_theme_logo_type',
        'name' => 'sb_options[theme][logo_type]',
        'value' => $value,
        'list_options' => $list_options,
        'container_class' => 'margin-bottom',
        'field_class' => 'logo-type',
        'description' => __('Choose the way you want logo to be displayed.', 'sb-theme')
    );
    SB_Field::select($args);
    $container_class = 'hidden';
    if($value == 'text') {
        $container_class = 'visible';
    }
    $container_class .= ' logo-text';
    $value = SB_Option::get_logo_text();
    $args = array(
        'id' => 'sb_theme_logo_text',
        'name' => 'sb_options[theme][logo_text]',
        'value' => $value,
        'container_class' => $container_class,
        'description' => __('Enter the text you want to display as logo.', 'sb-theme')
    );
    SB_Field::text_field($args);
}

function sb_theme_favicon_callback() {
    $options = SB_Option::get();
    $value = isset($options['theme']['favicon']) ? $options['theme']['favicon'] : '';
    $args = array(
        'id' => 'sb_theme_favicon',
        'name' => 'sb_options[theme][favicon]',
        'value' => $value,
        'container_class' => 'small',
        'description' => __('You can enter url or upload new favicon image file.', 'sb-theme')
    );
    SB_Field::media_image($args);
}

function sb_theme_advanced_setting_membership_hook() {
    $args = array(
        'id' => 'sb_theme_social_login',
        'name' => SB_Option::build_sb_theme_option_name(array('social_login')),
    );
}
add_action('sb_theme_advanced_setting_membership_field', 'sb_theme_advanced_setting_membership_hook');

function sb_theme_advanced_setting_social_login_hook() {
    sb_theme_get_content('sb-theme-admin-advanced-setting-social-login');
}
add_action('sb_theme_advanced_setting_social_login_field', 'sb_theme_advanced_setting_social_login_hook');

function sb_theme_advanced_setting_tabs_filter($tabs) {
    if(SB_Option::social_login_enabled()) {
        $tabs['social_login'] = array(
            'name' => __('Social login', 'sb-theme')
        );
    }
    return $tabs;
}
add_filter('sb_theme_advanced_setting_tabs', 'sb_theme_advanced_setting_tabs_filter');

function sb_theme_sanitize($input) {
    return $input;
}
add_filter('sb_options_sanitize', 'sb_theme_sanitize');