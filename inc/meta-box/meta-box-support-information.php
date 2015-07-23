<?php
defined('ABSPATH') or die('Please do not pip me!');

global $post;
$post_id = $post->ID;
SB_Theme::the_meta_box_nonce();

SB_Theme::meta_box_before();

$key = 'support_position';
$value = SB_Post::get_sb_meta($post_id, $key);
$all_options = '<option value="">-- ' . __('Chọn vị trí', 'sb-theme') . ' --</option>';
$support_items = SB_Theme::get_registered_supports();
foreach($support_items as $support) {
	$all_options .= SB_Field::get_option(array('value' => $support['id'], 'text' => $support['name'], 'selected' => $value));
}
$args = array(
	'id' => 'sb_theme_' . $key,
	'name' => SB_Post::build_meta_name($key),
	'label' => __('Vị trí:', 'sb-theme'),
	'description' => __('Lựa chọn vị trí bạn muốn hiển thị hỗ trợ.', 'sb-theme'),
	'field_class' => 'display-block',
	'all_option' => $all_options,
	'container_class' => 'margin-bottom-10'
);
SB_Field::select($args);

$key = 'support_name';
$value = SB_Post::get_sb_meta($post_id, $key);
$args = array(
	'id' => 'sb_theme_' . $key,
	'name' => SB_Post::build_meta_name($key),
	'value' => $value,
	'label' => __('Họ và tên:', 'sb-theme'),
	'field_class' => 'width-small display-block',
	'description' => __('Họ và tên của người hỗ trợ, nếu để trống thì tiêu đề sẽ được sử dụng.', 'sb-theme'),
	'container_class' => 'margin-bottom-10'
);
SB_Field::text($args);

$key = 'support_yahoo';
$value = SB_Post::get_sb_meta($post_id, $key);
$args = array(
	'id' => 'sb_theme_' . $key,
	'name' => SB_Post::build_meta_name($key),
	'value' => $value,
	'label' => __('Yahoo:', 'sb-theme'),
	'description' => __('Nhập tài khoản Yahoo của người hỗ trợ.', 'sb-theme'),
	'field_class' => 'width-small display-block',
	'container_class' => 'margin-bottom-10'
);
SB_Field::text($args);

$key = 'support_skype';
$value = SB_Post::get_sb_meta($post_id, $key);
$args = array(
	'id' => 'sb_theme_' . $key,
	'name' => SB_Post::build_meta_name($key),
	'value' => $value,
	'label' => __('Skype:', 'sb-theme'),
	'description' => __('Nhập tài khoản Skype của người hỗ trợ.', 'sb-theme'),
	'field_class' => 'width-small display-block',
	'container_class' => 'margin-bottom-10'
);
SB_Field::text($args);

$key = 'support_phone';
$value = SB_Post::get_sb_meta($post_id, $key);
$args = array(
	'id' => 'sb_theme_' . $key,
	'name' => SB_Post::build_meta_name($key),
	'value' => $value,
	'label' => __('Điện thoại:', 'sb-theme'),
	'description' => __('Nhập số điện thoại của người hỗ trợ.', 'sb-theme'),
	'field_class' => 'width-small display-block',
	'container_class' => 'margin-bottom-10'
);
SB_Field::text($args);

$key = 'support_chuc_vu';
$value = SB_Post::get_sb_meta($post_id, $key);
$args = array(
	'id' => 'sb_theme_' . $key,
	'name' => SB_Post::build_meta_name($key),
	'value' => $value,
	'label' => __('Chức vụ:', 'sb-theme'),
	'description' => __('Nhập chức vụ của người hỗ trợ.', 'sb-theme'),
	'field_class' => 'width-small display-block',
	'container_class' => 'margin-bottom-10'
);
SB_Field::text($args);

$key = 'support_avatar';
$value = SB_Post::get_sb_meta($post_id, $key);
$args = array(
	'id' => 'sb_theme_' . $key,
	'name' => SB_Post::build_meta_name($key),
	'value' => $value,
	'label' => __('Hình đại diện:', 'sb-theme'),
	'field_class' => 'widefat',
	'label_class' => 'display-block',
	'container_class' => 'margin-bottom-10'
);
SB_Field::media_upload_with_remove_and_preview($args);

$key = 'support_active';
$value = SB_Post::get_sb_meta($post_id, $key);
$args = array(
	'id' => 'sb_theme_' . $key,
	'name' => SB_Post::build_meta_name($key),
	'value' => $value,
	'label' => __('Kích hoạt hỗ trợ?', 'sb-theme')
);
SB_Field::checkbox($args);

SB_Theme::meta_box_after();