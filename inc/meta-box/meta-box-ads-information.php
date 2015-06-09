<?php
global $post;
$post_id = $post->ID;
SB_Theme::the_meta_box_nonce();

SB_Theme::meta_box_before();

$key = 'ads_position';
$value = SB_Post::get_sb_meta($post_id, $key);
$all_options = '<option value="">-- ' . __('Chọn vị trí', 'sb-theme') . ' --</option>';
$ads_items = SB_Theme::get_ads_items();
foreach($ads_items as $ads) {
    $all_options .= SB_Field::get_option(array('value' => $ads['id'], 'text' => $ads['name'], 'selected' => $value));
}
$args = array(
    'id' => 'sb_theme_' . $key,
    'name' => SB_Post::build_meta_name($key),
    'label' => __('Vị trí:', 'sb-theme'),
    'description' => __('Lựa chọn vị trí bạn muốn quảng cáo hiển thị.', 'sb-theme'),
    'field_class' => 'display-block',
    'all_option' => $all_options,
    'container_class' => 'margin-bottom-10'
);
SB_Field::select($args);

$key = 'ads_expire_date';
$value = SB_Post::get_sb_meta($post_id, $key);
$args = array(
    'id' => 'sb_theme_' . $key,
    'name' => SB_Post::build_meta_name($key),
    'value' => $value,
    'label' => __('Ngày hết hạn:', 'sb-theme'),
    'description' => __('Ngày hết hạn của quảng cáo.', 'sb-theme'),
    'min_date' => 0,
    'container_class' => 'margin-bottom-10'
);
SB_Field::datetime_picker($args);

$key = 'ads_link';
$value = SB_Post::get_sb_meta($post_id, $key);
$args = array(
    'id' => 'sb_theme_' . $key,
    'name' => SB_Post::build_meta_name($key),
    'value' => $value,
    'label' => __('Link:', 'sb-theme'),
    'description' => __('Đường dẫn đến trang đích quảng cáo.', 'sb-theme'),
    'container_class' => 'margin-bottom-10'
);
SB_Field::text($args);

$key = 'ads_image';
$value = SB_Post::get_sb_meta($post_id, $key);
$args = array(
    'id' => 'sb_theme_' . $key,
    'name' => SB_Post::build_meta_name($key),
    'value' => $value,
    'label' => __('Hình ảnh:', 'sb-theme'),
    'description' => __('Hình ảnh quảng cáo hiển thị trên trang web.', 'sb-theme'),
    'field_class' => 'widefat',
    'label_class' => 'display-block',
    'container_class' => 'margin-bottom-10'
);
SB_Field::media_upload_with_remove_and_preview($args);

$key = 'ads_code';
$value = SB_Post::get_sb_meta($post_id, $key);
$args = array(
    'id' => 'sb_theme_' . $key,
    'name' => SB_Post::build_meta_name($key),
    'value' => $value,
    'label' => __('Mã quảng cáo:', 'sb-theme'),
    'description' => __('Bạn có thể chèn các đoạn mã hiển thị quảng cáo tại đây.', 'sb-theme'),
    'container_class' => 'margin-bottom-10'
);
SB_Field::textarea($args);

$key = 'ads_active';
$value = SB_Post::get_sb_meta($post_id, $key);
$args = array(
    'id' => 'sb_theme_' . $key,
    'name' => SB_Post::build_meta_name($key),
    'value' => $value,
    'label' => __('Kích hoạt quảng cáo?', 'sb-theme')
);
SB_Field::checkbox($args);

SB_Theme::meta_box_after();