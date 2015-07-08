<?php
defined('ABSPATH') or die('Please do not pip me!');

$args = array(
	'title' => 'Cài đặt vChat',
	'description' => 'Cài đặt các thông tin tùy chọn để vChat hiển thị trên website.',
	'callback' => 'sb_theme_vchat_setting_setting'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_vchat_setting_setting() {
	$base_option_name = 'sbt_vchat';
	$tab_base_option_name = 'setting';
	$base_id = 'vchat';

	$key = 'code';
	$value = SB_Option::get_option_by_base($base_option_name, $tab_base_option_name, $key);
	$args = array(
		'id' => 'sb_theme_' . $base_id . '_' . $tab_base_option_name . '_' . $key,
		'name' => SB_Option::build_sb_option_name(array($base_option_name, $tab_base_option_name, $key)),
		'label' => __('Mã hiển thị:', 'sb-theme'),
		'value' => $value,
		'row' => 10
	);
	SB_Field::textarea($args);
}