<?php
global $sb_admin;
$tabs = $sb_admin->get_vchat_setting_tabs();
$tabs_html = SB_Admin_Custom::build_row_setting_tab('vchat', $tabs, 'sbt-vchat-tabs');

function sb_theme_setting_vchat_content_page_callback() {
	global $sb_admin;
	$tabs = $sb_admin->get_vchat_setting_tabs();
	SB_Admin_Custom::do_row_setting_content('vchat', $tabs);
}

echo '<form class="vchat-form" method="post" action="' . esc_url(admin_url('options.php')) . '">';
settings_fields( 'sb-setting' );
$args = array(
	'tab_html' => $tabs_html,
	'callback' => 'sb_theme_setting_vchat_content_page_callback',
	'container_class' => 'sbt-vchat-setting',
	'title' => __('vChat: Hỗ trợ trực tuyến trên website', 'sb-theme')
);
SB_Admin_Custom::row_setting_page_content($args);
submit_button(SB_Message::get_save_changes());
echo '</form>';