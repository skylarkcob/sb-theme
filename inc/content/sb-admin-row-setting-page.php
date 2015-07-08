<?php
defined('ABSPATH') or die('Please do not pip me!');

global $sb_admin;
$adv_tabs = $sb_admin->get_advanced_setting_tabs();
$transient_name = SB_Cache::build_admin_advanced_setting_tab_transient_name();
if(false === ($tabs_html = get_transient($transient_name)) || true) {
    $tabs_html = SB_Admin_Custom::build_row_setting_tab('adv', $adv_tabs, 'sbt-adv-tabs');
    set_transient($transient_name, $tabs_html, 4 * WEEK_IN_SECONDS);
}

function sb_theme_setting_advanced_content_page_callback() {
    global $sb_admin;
    $adv_tabs = $sb_admin->get_advanced_setting_tabs();
    SB_Admin_Custom::do_row_setting_content('advanced', $adv_tabs);
}

$args = array(
    'tab_html' => $tabs_html,
    'callback' => 'sb_theme_setting_advanced_content_page_callback',
    'container_class' => 'sbt-adv-setting'
);
SB_Admin_Custom::row_setting_page_content($args);