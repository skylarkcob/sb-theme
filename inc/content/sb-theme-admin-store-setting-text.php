<?php
/*
 * Cài đặt hệ thống tạo cache cho website
 */
$args = array(
    'title' => 'Giỏ hàng',
    'description' => 'Khai báo các chữ hiển thị liên quan đến giỏ hàng.',
    'callback' => 'sb_theme_store_setting_text_cart'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_store_setting_text_cart() {
    $menu_base = 'store';
    $tab_base_option_name = 'text';

    $key = 'add_to_cart';
    $value = SB_Option::get_by_base_option_name($menu_base, array('keys' => array($tab_base_option_name, $key)));
    if(empty($value)) {
        $value = __('Thêm vào giỏ hàng', 'sb-theme');
    }
    $args = array(
        'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_option_name(array($menu_base, $tab_base_option_name, $key)),
        'label' => __('Thêm vào giỏ hàng:', 'sb-theme'),
        'description' => __('Chữ hiển thị cho nút thêm sản phẩm vào giỏ hàng.', 'sb-theme'),
        'value' => $value
    );
    SB_Field::text($args);

    $key = 'view_cart';
    $value = SB_Option::get_by_base_option_name($menu_base, array('keys' => array($tab_base_option_name, $key)));
    if(empty($value)) {
        $value = __('Xem giỏ hàng', 'sb-theme');
    }
    $args = array(
        'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_option_name(array($menu_base, $tab_base_option_name, $key)),
        'label' => __('Xem giỏ hàng:', 'sb-theme'),
        'description' => __('Chữ hiển thị cho đường link người dùng nhấn chuột vào thì chuyển đến trang giỏ hàng.', 'sb-theme'),
        'value' => $value
    );
    SB_Field::text($args);
}