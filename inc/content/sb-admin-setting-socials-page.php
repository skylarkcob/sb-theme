<?php
defined('ABSPATH') or die('Please do not pip me!');

global $sb_admin;
$tabs = $sb_admin->get_socials_setting_tabs();
$tabs_html = SB_Admin_Custom::build_row_setting_tab('socials', $tabs, 'sbt-socials-tabs');

function sb_theme_setting_socials_content_page_callback() {
	global $sb_admin;
	$tabs = $sb_admin->get_socials_setting_tabs();
	SB_Admin_Custom::do_row_setting_content('socials', $tabs);
}

SB_Admin_Custom::form_before('socials-form');

$args = array(
	'tab_html' => $tabs_html,
	'callback' => 'sb_theme_setting_socials_content_page_callback',
	'container_class' => 'sbt-socials-setting',
	'title' => __('Socials: Cài đặt các thông tin liên quan đến tài khoản mạng xã hội', 'sb-theme')
);
SB_Admin_Custom::row_setting_page_content($args);

SB_Admin_Custom::form_after();