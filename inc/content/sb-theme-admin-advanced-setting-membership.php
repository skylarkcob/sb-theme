<?php
$args = array(
    'title' => 'Hệ thống thành viên trả phí',
    'description' => 'Với hệ thống thành viên có trả phí, bạn có thể quản lý người dùng, yêu cầu họ nạp tài khoản để sử dụng cho việc đăng bài, tải tài liệu,...',
    'callback' => 'sb_theme_advanced_setting_membership_paid_membership'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_membership_paid_membership() {
    $base_id = 'advanced';
    $base_option_name = 'sbt_advanced';
    $tab_base_option_name = 'membership';

    $key = 'use_paid_membership';
    $value = SB_Option::get_advanced_membership_setting($key);
    $value = SB_Option::check_switch_value($value, 0);
    $args = array(
        'id' => 'sb_theme_advanced_membership_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => __('Kích hoạt hệ thống tính phí cho thành viên?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);

    if((bool)$value) {
        $key = 'paid_free_post_number';
        $value = SB_Option::get_advanced_membership_setting($key);
        $value = absint($value);
        $args = array(
            'id' => 'sb_theme_advanced_membership_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => __('Số lượng bài viết miễn phí:', 'sb-theme'),
            'value' => $value,
            'type' => 'number',
            'field_class' => 'width-super-small',
            'description' => __('Sau khi đăng ký, mỗi thành viên được đăng bài viết miễn phí trước khi bắt đầu tính phí. Thiết lập về 0 nếu bạn không cho phép thành viên đăng bài miễn phí.')
        );
        SB_Field::text($args);

        $key = 'post_cost_coin';
        $value = SB_Option::get_advanced_membership_setting($key);
        $value = absint($value);
        $post_cost_coin = $value;
        if($post_cost_coin < 1) {
            $post_cost_coin = 1;
        }
        $args = array(
            'id' => 'sb_theme_advanced_membership_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => __('Chi phí đăng bài:', 'sb-theme'),
            'value' => $post_cost_coin,
            'type' => 'number',
            'field_class' => 'width-small',
            'attributes' => array(
                'min' => 1
            ),
            'container_class' => 'margin-top-10',
            'description' => __('Số coin được trừ vào tài khoản thành viên cho mỗi bài viết được đăng.')
        );
        SB_Field::text($args);

        $key = 'minimum_coin_can_post';
        $value = SB_Option::get_advanced_membership_setting($key);
        $value = absint($value);
        if($value < $post_cost_coin) {
            $value = $post_cost_coin;
        }
        $args = array(
            'id' => 'sb_theme_advanced_membership_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => __('Số Coin tối thiểu để đăng bài:', 'sb-theme'),
            'value' => $value,
            'type' => 'number',
            'field_class' => 'width-small',
            'container_class' => 'margin-top-10',
            'description' => __('Mỗi thành viên cần có ít nhất một số Coin nhất định để có thể đăng bài. Thiết lập về 0 để bỏ tính năng này.')
        );
        SB_Field::text($args);

        $key = 'coin_rate';
        $value = SB_Membership::get_coin_rate();
        $coin_rate = $value;
        $args = array(
            'id' => 'sb_theme_advanced_membership_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => __('Tỷ lệ chuyển đổi Coin:', 'sb-theme'),
            'value' => $value,
            'type' => 'number',
            'field_class' => 'width-small',
            'container_class' => 'margin-top-10',
            'attributes' => array(
                'min' => 1
            ),
            'autocomplete' => false,
            'description' => __('Tỷ lệ chuyển đổi giữa tiền Việt Nam và Coin, nhập số lượng Coin nhận được tương ứng với 1000đ.')
        );
        SB_Field::convert_rate($args);

        $key = 'minimum_coin_add_fund';
        $value = SB_Membership::get_minimum_coin_add_fund($coin_rate);
        $args = array(
            'id' => 'sb_theme_advanced_membership_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => __('Số Coin tối thiểu cần nạp:', 'sb-theme'),
            'value' => $value,
            'type' => 'number',
            'field_class' => 'width-small',
            'container_class' => 'margin-top-10',
            'attributes' => array(
                'min' => $coin_rate * 2
            ),
            'autocomplete' => false,
            'description' => __('Nhập số lượng Coin tối thiểu cho người dùng cần nạp.')
        );
        SB_Field::text($args);

        $key = 'add_coin_page';
        $value = SB_Option::get_advanced_membership_setting($key);
        $args = array(
            'id' => 'sb_theme_' . $base_id . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_option_name(array($base_option_name, $tab_base_option_name, $key)),
            'label' => __('Nạp tài khoản:', 'sb-theme'),
            'value' => $value,
            'field_class' => 'width-medium',
            'container_class' => 'margin-top-10',
            'description' => __('Trang nạp coin cho tài khoản.')
        );
        SB_Field::select_page($args);
    }
}