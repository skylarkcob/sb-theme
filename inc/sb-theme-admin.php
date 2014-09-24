<?php
function sb_theme_menu() {
    sb_add_submenu_page("Theme Settings", "sb_theme", "sb_admin_setting_callback");
}
add_action("sb_admin_menu", "sb_theme_menu");

function sb_theme_setting_tab($tabs) {
    $tabs["sb_theme"] = array('title' => "Theme Settings", 'section_id' => "sb_theme_setting_section", "type" => "theme");
    return $tabs;
}
add_filter("sb_admin_tabs", "sb_theme_setting_tab");

function sb_theme_setting_field() {
    sb_add_setting_section("sb_theme_setting_section", __("SB Theme options page", "sbteam"), "sb_theme");
    sb_add_setting_field("sb_theme_logo", "Logo", "sb_theme_setting_section", "sb_theme_logo_callback", "sb_theme");
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
    return $data;
}
add_filter("sb_options_sanitize", "sb_theme_setting_sanitize");