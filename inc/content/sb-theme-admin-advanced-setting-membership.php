<?php
defined('ABSPATH') or die('Please do not pip me!');
$lang = SB_Core::get_language();
$text = 'Với hệ thống thành viên có trả phí, bạn có thể quản lý người dùng, yêu cầu họ nạp tài khoản để sử dụng cho việc đăng bài, tải tài liệu,' . esc_html(SB_THEME_THREE_DOT);
if('vi' != $lang) {
    $text = __('With paid membership system, you can manage users, request user to add fund before adding new post, download document,...', 'sb-theme');
}
$args = array(
    'title' => ('vi' == $lang) ? 'Hệ thống thành viên trả phí' : __('Paid membership', 'sb-theme'),
    'description' => $text,
    'callback' => 'sb_theme_advanced_setting_membership_paid_membership'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_membership_paid_membership() {
    $lang = SB_Core::get_language();
    $base_id = 'advanced';
    $base_option_name = 'sbt_advanced';
    $tab_base_option_name = 'membership';

    $key = 'use_paid_membership';
    $value = SB_Option::get_advanced_membership_setting($key);
    $value = SB_Option::check_switch_value($value, 0);
    $args = array(
        'id' => 'sb_theme_advanced_membership_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => ('vi' == $lang) ? 'Kích hoạt hệ thống tính phí cho thành viên?' : __('Activate the paid membership system?', 'sb-theme'),
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
            'label' => ('vi' == $lang) ? 'Số lượng bài viết miễn phí:' : __('Free post number:', 'sb-theme'),
            'value' => $value,
            'type' => 'number',
            'field_class' => 'width-super-small',
            'description' => ('vi' == $lang) ? 'Sau khi đăng ký, mỗi thành viên được đăng bài viết miễn phí trước khi bắt đầu tính phí. Thiết lập về 0 nếu bạn không cho phép thành viên đăng bài miễn phí.' : __('After users registered, each user can create new posts before they must add fund. Set the number to 0 if you don\'t allow user to create free post.', 'sb-theme')
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
            'label' => ('vi' == $lang) ? 'Chi phí đăng bài:' : __('Create post costs:', 'sb-theme'),
            'value' => $post_cost_coin,
            'type' => 'number',
            'field_class' => 'width-small',
            'attributes' => array(
                'min' => 1
            ),
            'container_class' => 'margin-top-10',
            'description' => ('vi' == $lang) ? 'Số coin được trừ vào tài khoản thành viên cho mỗi bài viết được đăng.' : __('Number of coin was deducted from membership account for each post published.', 'sb-theme')
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
            'label' => ('vi' == $lang) ? 'Số Coin tối thiểu để đăng bài:' : __('Minimum coin to create post:', 'sb-theme'),
            'value' => $value,
            'type' => 'number',
            'field_class' => 'width-small',
            'container_class' => 'margin-top-10',
            'description' => ('vi' == $lang) ? 'Mỗi thành viên cần có ít nhất một số Coin nhất định để có thể đăng bài. Thiết lập về 0 để bỏ tính năng này.' : __('Each user must have at least a certain number of coins to be able to create post. Set to 0 if you want disable this feature.', 'sb-theme')
        );
        SB_Field::text($args);

        $key = 'coin_rate';
        $value = SB_Membership::get_coin_rate();
        $coin_rate = $value;
        $args = array(
            'id' => 'sb_theme_advanced_membership_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => ('vi' == $lang) ? 'Tỷ lệ chuyển đổi Coin:' : __('Coin transfer rate:', 'sb-theme'),
            'value' => $value,
            'type' => 'number',
            'field_class' => 'width-small',
            'container_class' => 'margin-top-10',
            'attributes' => array(
                'min' => 1
            ),
            'autocomplete' => false,
            'description' => ('vi' == $lang) ? 'Tỷ lệ chuyển đổi giữa tiền Việt Nam và Coin, nhập số lượng Coin nhận được tương ứng với 1000đ.' : __('Currency conversion rate between real money and Coin, enter the number corresponding Coin $1 received.', 'sb-theme')
        );
        SB_Field::convert_rate($args);

        $key = 'minimum_coin_add_fund';
        $value = SB_Membership::get_minimum_coin_add_fund($coin_rate);
        $args = array(
            'id' => 'sb_theme_advanced_membership_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => ('vi' == $lang) ? 'Số Coin tối thiểu cần nạp:' : __('Minimum coin to be added:', 'sb-theme'),
            'value' => $value,
            'type' => 'number',
            'field_class' => 'width-small',
            'container_class' => 'margin-top-10',
            'attributes' => array(
                'min' => $coin_rate * 2
            ),
            'autocomplete' => false,
            'description' => ('vi' == $lang) ? 'Nhập số lượng Coin tối thiểu cho người dùng cần nạp.' : __('Enter the minimum number of coins for the user to add.', 'sb-theme')
        );
        SB_Field::text($args);

        $key = 'add_coin_page';
        $value = SB_Option::get_advanced_membership_setting($key);
        $args = array(
            'id' => 'sb_theme_' . $base_id . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_option_name(array($base_option_name, $tab_base_option_name, $key)),
            'label' => ('vi' == $lang) ? 'Trang nạp tài khoản:' : __('Add fund page:', 'sb-theme'),
            'value' => $value,
            'field_class' => 'width-medium',
            'container_class' => 'margin-top-10',
            'description' => ('vi' == $lang) ? 'Trang nạp coin cho tài khoản.' : __('The page for user to add coin.', 'sb-theme')
        );
        SB_Field::select_page($args);
    }
}