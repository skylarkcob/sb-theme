<?php
defined('ABSPATH') or die('Please do not pip me!');

global $sb_admin;
$tabs = $sb_admin->get_checkout_setting_tabs();
$tabs_html = SB_Admin_Custom::build_row_setting_tab('checkout', $tabs, 'sbt-checkout-tabs');

function sb_theme_setting_advanced_content_page_callback() {
    global $sb_admin;
    $tabs = $sb_admin->get_checkout_setting_tabs();
    SB_Admin_Custom::do_row_setting_content('checkout', $tabs);
}

$args = array(
    'tab_html' => $tabs_html,
    'callback' => 'sb_theme_setting_advanced_content_page_callback',
    'container_class' => 'sbt-adv-setting'
);
SB_Admin_Custom::row_setting_page_content($args);