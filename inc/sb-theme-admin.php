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
    $options = SB_Option::get();
    $value = isset($options['theme']['logo']) ? $options['theme']['logo'] : '';
    $args = array(
        'id' => 'sb_theme_logo',
        'name' => 'sb_options[theme][logo]',
        'value' => $value,
        'description' => __('You can enter url or upload new logo image file.', 'sb-theme')
    );
    SB_Field::media_image($args);
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