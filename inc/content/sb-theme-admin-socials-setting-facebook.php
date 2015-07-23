<?php
defined('ABSPATH') or die('Please do not pip me!');

$args = array(
	'title' => 'Thông tin APP Facebook',
	'description' => 'Cài đặt các thông tin liên quan đến tài khoản Facebook APP.',
	'callback' => 'sb_theme_socials_setting_facebook'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_socials_setting_facebook() {
	$base_option_name = 'sbt_socials';
	$tab_base_option_name = 'facebook';
	$key = 'app_id';
	$value = SB_Option::get_option_by_base($base_option_name, $tab_base_option_name, $key);
	$base_id = 'socials';
	$args = array(
		'id' => 'sb_theme_' . $base_id . '_' . $tab_base_option_name . '_' . $key,
		'name' => SB_Option::build_sb_option_name(array($base_option_name, $tab_base_option_name, $key)),
		'label' => __('APP ID:', 'sb-theme'),
		'description' => __('APP ID là một mã định danh cho tài khoản APP của Facebook.', 'sb-theme'),
		'value' => $value
	);
	SB_Field::text($args);
}