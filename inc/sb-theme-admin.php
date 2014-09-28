<?php
function sb_theme_menu() {
    SB_Admin_Custom::add_submenu_page(__("Theme Settings", 'sbteam'), "sb_theme", array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action("sb_admin_menu", "sb_theme_menu");

function sb_theme_setting_tab($tabs) {
    $tabs["sb_theme"] = array('title' => __("Theme Settings", 'sbteam'), 'section_id' => "sb_theme_setting_section", "type" => "theme");
    return $tabs;
}
add_filter("sb_admin_tabs", "sb_theme_setting_tab");

function sb_theme_setting_field() {
    SB_Admin_Custom::add_section("sb_theme_setting_section", __("SB Theme options page", "sbteam"), "sb_theme");
    SB_Admin_Custom::add_setting_field("sb_theme_logo", "Logo", "sb_theme_setting_section", "sb_theme_logo_callback", "sb_theme");
    do_action("sb_theme_setting_field");
}
add_action("sb_admin_init", "sb_theme_setting_field");

function sb_theme_logo_callback() {
    $options = get_option("sb_options");
    $value = isset($options["theme"]["logo"]) ? $options["theme"]["logo"] : '';
    SB_Field::media_image("sb_theme_logo", "sb_options[theme][logo]", $value, __("You can enter url or upload new logo image file.", "sbteam"));
}

function sb_theme_setting_sanitize($input) {
    $data = $input;
    $data["theme"]["logo"] = sb_admin_sanitize_data(isset($input["theme"]["logo"]) ? $input["theme"]["logo"] : '', "url");
    return apply_filters("sb_theme_options_sanitize", $data);
}
add_filter("sb_options_sanitize", "sb_theme_setting_sanitize");