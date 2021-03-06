<?php
defined('ABSPATH') or die('Please do not pip me!');

global $sb_admin;
$tabs = $sb_admin->get_vchat_setting_tabs();
$tabs_html = SB_Admin_Custom::build_row_setting_tab('vchat', $tabs, 'sbt-vchat-tabs');

function sb_theme_setting_vchat_content_page_callback() {
	global $sb_admin;
	$tabs = $sb_admin->get_vchat_setting_tabs();
	SB_Admin_Custom::do_row_setting_content('vchat', $tabs);
}

SB_Admin_Custom::form_before('vchat-form');

$args = array(
	'tab_html' => $tabs_html,
	'callback' => 'sb_theme_setting_vchat_content_page_callback',
	'container_class' => 'sbt-vchat-setting',
	'title' => __('vChat: Hỗ trợ trực tuyến trên website', 'sb-theme')
);
SB_Admin_Custom::row_setting_page_content($args);

SB_Admin_Custom::form_after();