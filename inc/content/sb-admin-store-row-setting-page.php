<?php
global $sb_admin;
$tabs = $sb_admin->get_store_setting_tabs();
$tabs_html = SB_Admin_Custom::build_row_setting_tab('store', $tabs, 'sbt-store-tabs');

function sb_theme_setting_store_content_page_callback() {
    global $sb_admin;
    $tabs = $sb_admin->get_store_setting_tabs();
    SB_Admin_Custom::do_row_setting_content('store', $tabs);
}

$args = array(
    'tab_html' => $tabs_html,
    'callback' => 'sb_theme_setting_store_content_page_callback',
    'container_class' => 'sbt-store-setting'
);
SB_Admin_Custom::row_setting_page_content($args);