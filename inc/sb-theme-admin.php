<?php
function sb_theme_menu() {
    SB_Admin_Custom::add_submenu_page(__('Theme Settings', 'sb-theme'), 'sb_theme', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_theme_menu');

function sb_theme_setting_tab($tabs) {
    $tabs['sb_theme'] = array('title' => __('Theme Settings', 'sb-theme'), 'section_id' => 'sb_theme_setting_section', 'type' => 'theme');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_theme_setting_tab');

function sb_theme_setting_field() {
    SB_Admin_Custom::add_section('sb_theme_setting_section', __('SB Theme options page', 'sbteam'), 'sb_theme');
    SB_Admin_Custom::add_setting_field('sb_theme_logo', 'Logo', 'sb_theme_setting_section', 'sb_theme_logo_callback', 'sb_theme');
    SB_Admin_Custom::add_setting_field('sb_theme_favicon', 'Favicon', 'sb_theme_setting_section', 'sb_theme_favicon_callback', 'sb_theme');
    sb_theme_setting_field_no_thumbnail();
    do_action('sb_theme_setting_field');
}
add_action('sb_admin_init', 'sb_theme_setting_field');

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
        'description' => __('You can enter url or upload new favicon image file.', 'sb-theme')
    );
    SB_Field::media_image($args);
}

function sb_theme_sanitize($input) {
    $data = $input;
    $data['theme']['logo_type'] = isset($input['theme']['logo_type']) ? $input['theme']['logo_type'] : 'background';
    return $data;
}
add_filter('sb_options_sanitize', 'sb_theme_sanitize');