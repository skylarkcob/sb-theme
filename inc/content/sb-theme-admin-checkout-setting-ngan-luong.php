<?php
defined('ABSPATH') or die('Please do not pip me!');

$args = array(
    'title' => 'Thanh toán bằng Ngân Lượng',
    'description' => 'Cài đặt cổng thanh toán thông qua dịch vụ của Ngân Lượng.',
    'callback' => 'sb_theme_checkout_setting_ngan_luong'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_checkout_setting_ngan_luong() {
    $base_option_name = 'sbt_checkout';
    $tab_base_option_name = 'ngan_luong';
    $base_id = 'checkout';

    $key = 'enabled';
    $value = SB_Option::get_checkout_by_base($tab_base_option_name, $key);
    $value = SB_Option::check_switch_value($value, 0);
    $args = array(
        'id' => 'sb_theme_' . $base_id . '_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_option_name(array($base_option_name, $tab_base_option_name, $key)),
        'label' => __('Sử dụng cổng thanh toán Ngân Lượng?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);

    if((bool)$value) {
        $key = 'merchant_site_code';
        $value = SB_Option::get_checkout_by_base($tab_base_option_name, $key);
        $args = array(
            'id' => 'sb_theme_' . $base_id . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_option_name(array($base_option_name, $tab_base_option_name, $key)),
            'label' => __('Merchante site code:', 'sb-theme'),
            'value' => $value,
            'field_class' => 'width-medium',
            'description' => __('Mã website của bạn đăng ký trong chức năng tích hợp thanh toán của nganluong.vn.')
        );
        SB_Field::text($args);

        $key = 'secure_pass';
        $value = SB_Option::get_checkout_by_base($tab_base_option_name, $key);
        $args = array(
            'id' => 'sb_theme_' . $base_id . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_option_name(array($base_option_name, $tab_base_option_name, $key)),
            'label' => __('Secure pass:', 'sb-theme'),
            'value' => $value,
            'field_class' => 'width-medium',
            'container_class' => 'margin-top-10',
            'description' => __('Mật khẩu giao tiếp giữa website của bạn và nganluong.vn.')
        );
        SB_Field::text($args);

        $key = 'affiliate_code';
        $value = SB_Option::get_checkout_by_base($tab_base_option_name, $key);
        $args = array(
            'id' => 'sb_theme_' . $base_id . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_option_name(array($base_option_name, $tab_base_option_name, $key)),
            'label' => __('Affiliate code:', 'sb-theme'),
            'value' => $value,
            'field_class' => 'width-medium',
            'container_class' => 'margin-top-10',
            'description' => __('Mã đối tác tham gia chương trình liên kết của nganluong.vn.')
        );
        SB_Field::text($args);

        $key = 'return_url';
        $value = SB_Option::get_checkout_by_base($tab_base_option_name, $key);
        $args = array(
            'id' => 'sb_theme_' . $base_id . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_option_name(array($base_option_name, $tab_base_option_name, $key)),
            'label' => __('Return url:', 'sb-theme'),
            'value' => $value,
            'field_class' => 'width-medium',
            'container_class' => 'margin-top-10',
            'description' => __('Trang xử lý thông tin trả về sau khi thanh toán hoàn tất.')
        );
        SB_Field::select_page($args);

        $key = 'receiver';
        $value = SB_Option::get_checkout_by_base($tab_base_option_name, $key);
        $args = array(
            'id' => 'sb_theme_' . $base_id . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_option_name(array($base_option_name, $tab_base_option_name, $key)),
            'label' => __('Receiver:', 'sb-theme'),
            'value' => $value,
            'field_class' => 'width-medium',
            'container_class' => 'margin-top-10',
            'description' => __('Địa chỉ email tài khoản nhận tiền trên nganluong.vn.')
        );
        SB_Field::text($args);
    }
}