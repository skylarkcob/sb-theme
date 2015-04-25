<?php
$args = array(
    'title' => 'Hệ thống cache',
    'description' => 'Cache là chức năng cho phép bạn lưu lại dữ liệu duyệt web, bạn có thể dùng chức năng này để tăng tốc độ tải trang web.',
    'callback' => 'sb_theme_advanced_setting_general_cache'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_general_cache() {
    $menu_base = 'advanced';
    $tab_base_option_name = 'general';

    $key = 'cache_enabled';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $value = SB_Option::check_switch_value($value, 0);
    $args = array(
        'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => __('Kích hoạt chức năng lưu cache?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);

    if((bool)$value) {
        $key = 'query_cache_enabled';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        $args = array(
            'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => __('Lưu cache cho truy vấn bài viết?', 'sb-theme'),
            'value' => $value
        );
        SB_Field::checkbox($args);

        $key = 'menu_cache_enabled';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        $args = array(
            'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => __('Lưu cache cho menu?', 'sb-theme'),
            'value' => $value
        );
        SB_Field::checkbox($args);

        $key = 'widget_cache_enabled';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        $args = array(
            'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => __('Lưu cache cho widget?', 'sb-theme'),
            'value' => $value
        );
        SB_Field::checkbox($args);

        $key = 'post_images_cache_enabled';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        $args = array(
            'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => __('Lưu cache cho hình ảnh trong bài viết?', 'sb-theme'),
            'value' => $value
        );
        SB_Field::checkbox($args);

        $key = 'post_comment_count_cache_enabled';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        $args = array(
            'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => __('Lưu cache cho số bình luận của bài viết?', 'sb-theme'),
            'value' => $value
        );
        SB_Field::checkbox($args);
    }
}