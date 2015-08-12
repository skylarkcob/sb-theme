<?php
defined('ABSPATH') or die('Please do not pip me!');

global $post;
$post_id = $post->ID;
$lang = SB_Core::get_language();
SB_Theme::the_meta_box_nonce();

SB_Theme::meta_box_before();

$key = 'slider_position';
$value = SB_Post::get_sb_meta($post_id, $key);
$all_options = '<option value="">-- ' . ('vi' == $lang) ? 'Chọn vị trí' : __('Choose position', 'sb-theme') . ' --</option>';
$slider_items = SB_Theme::get_slider_items();
foreach($slider_items as $slider) {
    $all_options .= SB_Field::get_option(array('value' => $slider['id'], 'text' => $slider['name'], 'selected' => $value));
}
$args = array(
    'id' => 'sb_theme_' . $key,
    'name' => SB_Post::build_meta_name($key),
    'label' => ('vi' == $lang) ? 'Vị trí:' : __('Position:', 'sb-theme'),
    'description' => ('vi' == $lang) ? 'Lựa chọn vị trí bạn muốn slider hiển thị.' : __('Choose the position you want slider to be displayed.', 'sb-theme'),
    'field_class' => 'display-block',
    'all_option' => $all_options,
    'container_class' => 'margin-bottom-10'
);
SB_Field::select($args);

$key = 'slider_active';
$value = SB_Post::get_sb_meta($post_id, $key);
$args = array(
    'id' => 'sb_theme_' . $key,
    'name' => SB_Post::build_meta_name($key),
    'value' => $value,
    'label' => ('vi' == $lang) ? 'Kích hoạt slider?' : __('Active slider?', 'sb-theme')
);
SB_Field::checkbox($args);

SB_Theme::meta_box_after();