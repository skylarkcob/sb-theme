<?php
defined('ABSPATH') or die('Please do not pip me!');
$lang = SB_Core::get_language();
$args = array(
    'title' => ('vi' == $lang) ? 'Thanh toán bằng Mal\'s E-commerce' : __('Payment by Mal\'s E-commerce', 'sb-theme'),
    'description' => ('vi' == $lang) ? 'Cài đặt cổng thanh toán thông qua dịch vụ của Mal\'s E-commerce.' : __('Installing payment gateway services through Mal\'s E-commerce.', 'sb-theme'),
    'callback' => 'sb_theme_checkout_setting_mals_e'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_checkout_setting_mals_e() {
    $base_option_name = 'sbt_checkout';
    $tab_base_option_name = 'mals_e';
    $base_id = 'checkout';
    $lang = SB_Core::get_language();
    $key = 'enabled';
    $value = SB_Option::get_checkout_by_base($tab_base_option_name, $key);
    $value = SB_Option::check_switch_value($value, 0);
    $args = array(
        'id' => 'sb_theme_' . $base_id . '_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_option_name(array($base_option_name, $tab_base_option_name, $key)),
        'label' => ('vi' == $lang) ? 'Sử dụng cổng thanh toán Mal\'s E-commerce?' : __('Use Mal\'s E-commerce payment gateway?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);

    if((bool)$value) {
        $key = 'user_id';
        $value = SB_Option::get_checkout_by_base($tab_base_option_name, $key);
        $args = array(
            'id' => 'sb_theme_' . $base_id . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_option_name(array($base_option_name, $tab_base_option_name, $key)),
            'label' => ('vi' == $lang) ? 'Mã người dùng:' : __('User ID:', 'sb-theme'),
            'value' => $value,
            'field_class' => 'width-medium',
            'description' => ('vi' == $lang) ? 'Mã người dùng của bạn trên trang web của Mal\'s E-commerce.' : __('Your user ID on Mal\'s E-commerce website.', 'sb-theme')
        );
        SB_Field::text($args);

        $key = 'server_number';
        $value = SB_Option::get_checkout_by_base($tab_base_option_name, $key);
        $args = array(
            'id' => 'sb_theme_' . $base_id . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_option_name(array($base_option_name, $tab_base_option_name, $key)),
            'label' => ('vi' == $lang) ? 'Số của server:' : __('Server number:', 'sb-theme'),
            'value' => $value,
            'field_class' => 'width-medium',
            'container_class' => 'margin-top-10',
            'description' => ('vi' == $lang) ? 'Mỗi tài khoản được đặt thông tin trên một server khác nhau, bạn có thể tìm thấy số này trên địa chỉ của giỏ hàng.' : __('Each account is placed on a different server, you can find this number on your cart page.', 'sb-theme')
        );
        SB_Field::number($args);
    }
}