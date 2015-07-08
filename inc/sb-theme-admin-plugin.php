<?php
defined('ABSPATH') or die('Please do not pip me!');

/*
 * Plugin SB Clean
 */
function sb_clean_menu() {
    SB_Admin_Custom::add_submenu_page('Dọn dẹp', 'sb_clean', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_clean_menu');

function sb_clean_tab($tabs) {
    $tabs['sb_clean'] = array('title' => 'Dọn dẹp', 'section_id' => 'sb_clean_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_clean_tab');

function sb_clean_setting_field() {
    SB_Admin_Custom::add_section('sb_clean_section', __('Trang cài đặt tùy chọn dọn dẹp hệ thống', 'sb-theme'), 'sb_clean');
    SB_Admin_Custom::add_setting_field('sb_clean_wpdb', __('Xóa thông tin WPDB', 'sb-theme'), 'sb_clean_section', 'sb_clean_wpdb_callback', 'sb_clean');
    SB_Admin_Custom::add_setting_field('sb_clean_head_meta', __('Rút gọn thẻ head', 'sb-theme'), 'sb_clean_section', 'sb_clean_head_meta_callback', 'sb_clean');
    SB_Admin_Custom::add_setting_field('sb_theme_optimize_database', __('Tối ưu cơ sở dữ liệu', 'sb-theme'), 'sb_clean_section', 'sb_theme_optimize_database_setting_callback', 'sb_clean');
}
add_action('sb_admin_init', 'sb_clean_setting_field');

function sb_theme_optimize_database_setting_callback() {
    $args = array(
        'label' => __('Xóa tất cả bài viết tạm', 'sb-theme'),
        'field_class' => 'delete-revision',
        'description' => 'Bài viết tạm (revision) là những phiên bản sao lưu khác nhau của cùng một bài viết.',
        'container_class' => 'margin-bottom',
        'value' => 1
    );
    SB_Field::checkbox($args);

    $args = array(
        'label' => __('Xóa tất cả bài viết nháp được lưu tự động', 'sb-theme'),
        'field_class' => 'delete-auto-draft',
        'description' => 'Các bài viết nháp (auto draft) được tự động tạo ra khi bạn soạn thảo bài viết.',
        'container_class' => 'margin-bottom',
        'value' => 1
    );
    SB_Field::checkbox($args);

    $args = array(
        'label' => __('Xóa tất cả bình luận spam và chưa được xét duyệt', 'sb-theme'),
        'field_class' => 'delete-spam-comment',
        'container_class' => 'margin-bottom',
        'value' => 1
    );
    SB_Field::checkbox($args);

    $args = array(
        'label' => __('Làm trống thùng rác', 'sb-theme'),
        'field_class' => 'delete-trash',
        'description' => 'Xóa tất cả các bài viết trong thùng rác, bao gồm thùng rác của bình luận.',
        'container_class' => 'margin-bottom',
        'value' => 1
    );
    SB_Field::checkbox($args);

    $args = array(
        'label' => __('Xóa transient', 'sb-theme'),
        'field_class' => 'delete-transient',
        'description' => 'Transient là các giá trị được lưu trữ tạm thời trong cơ sở dữ liệu của bạn.',
        'container_class' => 'margin-bottom',
        'value' => 0
    );
    SB_Field::checkbox($args);

    $args = array(
        'text' => __('Tiến hành', 'sb-theme'),
        'field_class' => 'sb-theme-optimize-database',
        'description' => __('Bạn nên sao lưu cơ sở dữ liệu trước khi tiến hành.', 'sb-theme')
    );
    SB_Field::button($args);
}

function sb_clean_wpdb_callback() {
    $name = 'sb_options[clean][wpdb]';
    $options = SB_Option::get();
    $value = isset($options['clean']['wpdb']) ? $options['clean']['wpdb'] : 1;
    $description = __('Bạn có thể bật hoặc tắt chức năng xóa thông tin tên cơ sở dữ liệu và mật khẩu đăng nhập.', 'sb-theme');
    $args = array(
        'id' => 'sb_clean_wpdb',
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_clean_head_meta_callback() {
    $name = 'sb_options[clean][head_meta]';
    $options = SB_Option::get();
    $value = isset($options['clean']['head_meta']) ? $options['clean']['head_meta'] : 1;
    $description = __('Bạn có thể bật hoặc tắt chức năng cho phép xóa và rút gọn bớt các thẻ meta trong thẻ head.', 'sb-theme');
    $args = array(
        'id' => 'sb_clean_head_meta',
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_clean_sanitize($input) {
    $data = $input;
    $data['clean']['wpdb'] = isset($input['clean']['wpdb']) ? $input['clean']['wpdb'] : 1;
    $data['clean']['head_meta'] = isset($input['clean']['head_meta']) ? $input['clean']['head_meta'] : 1;
    return $data;
}
//add_filter('sb_options_sanitize', 'sb_clean_sanitize');

/*
 * Plugin SB Comment
 */
function sb_comment_menu() {
    SB_Admin_Custom::add_submenu_page(__('Thảo luận', 'sb-theme'), 'sb_comment', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_comment_menu');

function sb_comment_tab($tabs) {
    $tabs['sb_comment'] = array('title' => __('Thảo luận', 'sb-theme'), 'section_id' => 'sb_comment_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_comment_tab');

function sb_comment_setting_field() {
    SB_Admin_Custom::add_section('sb_comment_section', __('Trang cài đặt tùy chọn cho thảo luận', 'sb-theme'), 'sb_comment');
    SB_Admin_Custom::add_setting_field('sb_comment_spam_check', __('Kiểm tra spam', 'sb-theme'), 'sb_comment_section', 'sb_comment_spam_check_callback', 'sb_comment');
    SB_Admin_Custom::add_setting_field('sb_comment_notify_user', __('Thông báo người dùng', 'sb-theme'), 'sb_comment_section', 'sb_comment_notify_user_callback', 'sb_comment');
    SB_Admin_Custom::add_setting_field('sb_comment_auto_empty_spam', __('Tự động xóa spam', 'sb-theme'), 'sb_comment_section', 'sb_comment_auto_empty_spam_callback', 'sb_comment');
    SB_Admin_Custom::add_setting_field('sb_comment_remove_url', __('Ẩn url khi bình luận', 'sb-theme'), 'sb_comment_section', 'sb_comment_remove_url_callback', 'sb_comment');
    SB_Admin_Custom::add_setting_field('sb_comment_tools', __('Công cụ bình luận', 'sb-theme'), 'sb_comment_section', 'sb_comment_tools_callback', 'sb_comment');
    SB_Admin_Custom::add_setting_field('sb_comment_default_avatar', __('Avatar mặc định', 'sb-theme'), 'sb_comment_section', 'sb_comment_default_avatar_callback', 'sb_comment');
    if(SB_Comment::use_default_avatar()) {
        SB_Admin_Custom::add_setting_field('sb_comment_default_avatar_url', __('Đường dẫn avatar mặc định', 'sb-theme'), 'sb_comment_section', 'sb_comment_default_avatar_url_callback', 'sb_comment');
    }
}
add_action('sb_admin_init', 'sb_comment_setting_field');

function sb_comment_default_avatar_url_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['default_avatar_url']) ? $options['comment']['default_avatar_url'] : '';
    $id = 'sb_comment_default_avatar_url';
    $name = 'sb_options[comment][default_avatar_url]';
    $description = __('Bạn có thể bật hoặc tắt chức năng cho phép sử dụng avatar mặc định cục bộ.', 'sb-theme');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::media_image($args);
}

function sb_comment_default_avatar_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['default_avatar']) ? $options['comment']['default_avatar'] : 0;
    $id = 'sb_comment_default_avatar';
    $name = 'sb_options[comment][default_avatar]';
    $description = __('Bạn có thể bật hoặc tắt chức năng cho phép sử dụng avatar mặc định cục bộ.', 'sb-theme');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_comment_remove_url_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['website_url']) ? $options['comment']['website_url'] : 0;
    $id = 'sb_comment_remove_url';
    $name = 'sb_options[comment][website_url]';
    $description = __('Bạn có thể bật hoặc tắt chức năng cho phép ẩn thông tin url khi gửi bình luận.', 'sb-theme');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_comment_tools_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['tools']) ? $options['comment']['tools'] : 1;
    $id = 'sb_comment_tools';
    $name = 'sb_options[comment][tools]';
    $description = __('Bạn có thể bật hoặc tắt chức năng cho phép hiển thị thanh công cụ khi bình luận', 'sb-theme');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_comment_auto_empty_spam_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['auto_empty_spam']) ? $options['comment']['auto_empty_spam'] : 1;
    $id = 'sb_comment_auto_empty_spam';
    $name = 'sb_options[comment][auto_empty_spam]';
    $description = __('Bạn có thể bật hoặc tắt chức năng cho phép xóa các bình luận spam một cách tự động.', 'sb-theme');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_comment_notify_user_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['notify_user']) ? $options['comment']['notify_user'] : 1;
    $id = '';
    $name = 'sb_options[comment][notify_user]';
    $description = __('Bạn có thể bật hoặc tắt chức năng cho phép thông báo người dùng khi bình luận được duyệt.', 'sb-theme');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_comment_spam_check_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['spam_check']) ? $options['comment']['spam_check'] : 1;
    $id = 'sb_comment_spam_check';
    $name = 'sb_options[comment][spam_check]';
    $description = __('Bạn có thể bật hoặc tắt chức năng kiểm tra bình luận spam trước khi nhập vào cơ sở dữ liệu.', 'sb-theme');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_comment_sanitize($input) {
    $data = $input;
    $data['comment']['spam_check'] = SB_Core::sanitize(isset($input['comment']['spam_check']) ? $input['comment']['spam_check'] : 1, 'bool');
    return $data;
}
//add_filter('sb_options_sanitize', 'sb_comment_sanitize');

/*
 * Plugin SB Login Page
 */
function sb_login_page_menu() {
    SB_Admin_Custom::add_submenu_page(__('Đăng nhập', 'sb-theme'), 'sb_login_page', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_login_page_menu');

function sb_login_page_tab($tabs) {
    $tabs['sb_login_page'] = array('title' => __('Đăng nhập', 'sb-theme'), 'section_id' => 'sb_login_page_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_login_page_tab');

function sb_login_page_add_admin_setting_field($id, $title, $callback) {
    SB_Admin_Custom::add_setting_field($id, $title, 'sb_login_page_section', $callback, 'sb_login_page');
}

function sb_login_page_setting_field() {
    SB_Admin_Custom::add_section('sb_login_page_section', __('Trang cài đặt tùy chọn cho đăng nhập', 'sb-theme'), 'sb_login_page');
    sb_login_page_add_admin_setting_field('sb_login_page_user_can_register', __('Cho phép đăng ký', 'sb-theme'), 'sb_login_page_user_can_register_callback');
    sb_login_page_add_admin_setting_field('sb_login_page_use_sb_login', __('Đăng nhập tùy chỉnh', 'sb-theme'), 'sb_login_page_use_sb_login_callback');
    SB_Admin_Custom::add_setting_field('sb_login_page_logo', __('Logo', 'sb-theme'), 'sb_login_page_section', 'sb_login_page_logo_callback', 'sb_login_page');
    if(sb_login_page_use_sb_login()) {
        sb_login_page_add_admin_setting_field('sb_login_page_page_account', __('Trang tài khoản', 'sb-theme'), 'sb_login_page_page_account_callback');
        sb_login_page_add_admin_setting_field('sb_login_page_page_login', __('Trang đăng nhập', 'sb-theme'), 'sb_login_page_page_login_callback');
        sb_login_page_add_admin_setting_field('sb_login_page_page_lost_password', __('Trang khôi phục mật khẩu', 'sb-theme'), 'sb_login_page_page_lost_password_callback');
        sb_login_page_add_admin_setting_field('sb_login_page_page_register', __('Trang đăng ký', 'sb-theme'), 'sb_login_page_page_register_callback');
    }
    sb_login_page_add_admin_setting_field('sb_login_page_login_redirect', __('Chuyển tiếp khi đăng nhập', 'sb-theme'), 'sb_login_page_login_redirect_callback');
    sb_login_page_add_admin_setting_field('sb_login_page_logout_redirect', __('Chuyển tiếp khi đăng xuất', 'sb-theme'), 'sb_login_page_logout_redirect_callback');
    sb_login_page_add_admin_setting_field('sb_login_page_social_login', __('Kết nối mạng xã hội', 'sb-theme'), 'sb_login_page_social_login_callback');
    sb_login_page_add_admin_setting_field('sb_login_page_use_captcha', __('Sử dụng captcha', 'sb-theme'), 'sb_login_page_use_captcha_callback');
}
add_action('sb_admin_init', 'sb_login_page_setting_field');

function sb_login_page_use_captcha_callback() {
    $value = absint(SB_Option::use_login_captcha());
    $args = array(
        'id' => 'sb_login_page_use_captcha',
        'name' => 'sb_options[login_page][use_captcha]',
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép sử dụng mã captcha khi đăng ký và đăng nhập.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_login_page_social_login_callback() {
    $value = absint(SB_Option::social_login_enabled());
    $args = array(
        'id' => 'sb_login_page_social_login',
        'name' => 'sb_options[login_page][social_login]',
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép đăng nhập với tài khoản mạng xã hội.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_login_page_use_sb_login_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['use_sb_login']) ? intval($options['login_page']['use_sb_login']) : 1;
    $args = array(
        'id' => 'sb_login_page_use_sb_login',
        'name' => 'sb_options[login_page][use_sb_login]',
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng sử dụng hệ thống đăng nhập và đăng ký của SB Team.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_login_page_logout_redirect_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['logout_redirect']) ? $options['login_page']['logout_redirect'] : 'home';
    $options = array(
        'home' => __('Trang chủ', 'sb-theme'),
        'current' => __('Trang hiện tại', 'sb-theme')
    );
    $args = array(
        'id' => 'sb_login_page_logout_redirect',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'logout_redirect')),
        'value' => $value,
        'options' => $options,
        'description' => __('Lựa chọn trang chuyển tiếp đến khi người dùng đăng xuất khỏi hệ thống.', 'sb-theme')
    );
    SB_Field::select($args);
}

function sb_login_page_login_redirect_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['login_redirect']) ? $options['login_page']['login_redirect'] : 'current';
    $options = array(
        'home' => __('Home', 'sb-theme'),
        'profile' => __('Profile', 'sb-theme'),
        'dashboard' => __('Dashboard', 'sb-theme'),
        'current' => __('Current', 'sb-theme')
    );
    $args = array(
        'id' => 'sb_login_page_login_redirect',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'login_redirect')),
        'value' => $value,
        'options' => $options,
        'description' => __('Lựa chọn trang chuyển tiếp đến khi người dùng đăng nhập vào hệ thống.', 'sb-theme')
    );
    SB_Field::select($args);
}

function sb_login_page_user_can_register_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['users_can_register']) ? intval($options['login_page']['users_can_register']) : 0;
    $users_can_register = intval(get_option('users_can_register'));
    if($value != $users_can_register) {
        $value = $users_can_register;
    }
    $args = array(
        'id' => 'sb_login_page_user_can_register',
        'name' => 'sb_options[login_page][users_can_register]',
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép người dùng đăng ký tài khoản.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_login_page_page_register_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_register']) ? $options['login_page']['page_register'] : 0;
    $args = array(
        'id' => 'sb_login_page_page_register',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'page_register')),
        'value' => $value,
        'description' => __('Chọn trang cho người dùng đăng ký tài khoản.', 'sb-theme')
    );
    SB_Field::select_page($args);
}

function sb_login_page_page_lost_password_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_lost_password']) ? $options['login_page']['page_lost_password'] : 0;
    $args = array(
        'id' => 'sb_login_page_page_lost_password',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'page_lost_password')),
        'value' => $value,
        'description' => __('Chọn trang cho người dùng khôi phục mật khẩu.', 'sb-theme')
    );
    SB_Field::select_page($args);
}

function sb_login_page_page_account_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_account']) ? $options['login_page']['page_account'] : 0;
    $args = array(
        'id' => 'sb_login_page_page_account',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'page_account')),
        'value' => $value,
        'description' => __('Chọn trang cho người dùng quản lý tài khoản.', 'sb-theme')
    );
    SB_Field::select_page($args);
}

function sb_login_page_page_login_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_login']) ? $options['login_page']['page_login'] : 0;
    $args = array(
        'id' => 'sb_login_page_page_login',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'page_login')),
        'value' => $value,
        'description' => __('Chọn trang cho người dùng đăng nhập.', 'sb-theme')
    );
    SB_Field::select_page($args);
}

function sb_login_page_logo_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['logo']) ? $options['login_page']['logo'] : '';
    $args = array(
        'id' => 'sb_login_page_logo',
        'name' => 'sb_options[login_page][logo]',
        'value' => $value,
        'description' => __('Bạn có thể nhập đường dẫn hoặc tải lên hình ảnh mới.', 'sb-theme')
    );
    SB_Field::media_image($args);
}

function sb_login_page_sanitize($input) {
    $data = $input;
    $users_can_register = isset($input['login_page']['users_can_register']) ? (bool)$input['login_page']['users_can_register'] : false;
    if($users_can_register) {
        update_option('users_can_register', 1);
    } else {
        update_option('users_can_register', 0);
    }
    $data['login_page']['logo'] = SB_Core::sanitize(isset($input['login_page']['logo']) ? $input['login_page']['logo'] : '', 'url');
    return $data;
}
add_filter('sb_options_sanitize', 'sb_login_page_sanitize');

/*
 * Plugin SB Paginate
 */
function sb_paginate_menu() {
    SB_Admin_Custom::add_submenu_page(__('Phân trang', 'sb-theme'), 'sb_paginate', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_paginate_menu');

function sb_paginate_tab($tabs) {
    $tabs['sb_paginate'] = array('title' => __('Phân trang', 'sb-theme'), 'section_id' => 'sb_paginate_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_paginate_tab');

function sb_paginate_setting_field() {
    SB_Admin_Custom::add_section('sb_paginate_section', __('Trang cài đặt tùy chọn cho phân trang', 'sb-theme'), 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_label', __('Tiêu đề', 'sb-theme'), 'sb_paginate_section', 'sb_paginate_label_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_next_text', __('Trang tiếp', 'sb-theme'), 'sb_paginate_section', 'sb_paginate_next_text_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_previous_text', __('Trang trước', 'sb-theme'), 'sb_paginate_section', 'sb_paginate_previous_text_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_range', __('Range', 'sb-theme'), 'sb_paginate_section', 'sb_paginate_range_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_anchor', __('Anchor', 'sb-theme'), 'sb_paginate_section', 'sb_paginate_anchor_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_gap', __('Gap', 'sb-theme'), 'sb_paginate_section', 'sb_paginate_gap_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_style', __('Kiểu hiển thị', 'sb-theme'), 'sb_paginate_section', 'sb_paginate_style_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_border_radius', __('Bo viền nút', 'sb-theme'), 'sb_paginate_section', 'sb_paginate_border_radius_callback', 'sb_paginate');
}
add_action('sb_admin_init', 'sb_paginate_setting_field');

function sb_paginate_next_text_callback() {
    $options = SB_Option::get();
    $value = isset($options['paginate']['next_text']) ? $options['paginate']['next_text'] : '&raquo;';
    $id = 'sb_paginate_next_text';
    $name = 'sb_options[paginate][next_text]';
    $description = __('Chữ hiển thị cho nút trang tiếp theo.', 'sb-theme');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::text_field($args);
}

function sb_paginate_previous_text_callback() {
    $options = SB_Option::get();
    $value = isset($options['paginate']['previous_text']) ? $options['paginate']['previous_text'] : '&laquo;';
    $id = 'sb_paginate_previous_text';
    $name = 'sb_options[paginate][previous_text]';
    $description = __('Chữ hiển thị cho nút trang trước đó.', 'sb-theme');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::text_field($args);
}

function sb_paginate_label_callback() {
    $options = SB_Option::get();
    $value = isset($options['paginate']['label']) ? $options['paginate']['label'] : __('Trang', 'sb-theme');
    $id = 'sb_paginate_label';
    $name = 'sb_options[paginate][label]';
    $description = __('Dòng chữ được hiển thị phía đầu của phân trang.', 'sb-theme');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::text_field($args);
}

function sb_paginate_range_callback() {
    $options = SB_Option::get();
    $value = isset($options['paginate']['range']) ? $options['paginate']['range'] : 3;
    $description = __('Số lượng nút được hiển thị phía trước và sau nút của trang hiện tại.', 'sb-theme');
    printf('<input type="number" id="%1$s" name="%2$s" value="%3$s" class="" min="1" max="100"><p class="description">%4$s</p>', 'sb_paginate_range', esc_attr('sb_options[paginate][range]'), $value, $description);
}

function sb_paginate_anchor_callback() {
    $options = SB_Option::get();
    $value = isset($options['paginate']['anchor']) ? $options['paginate']['anchor'] : 1;
    $description = __('Số lượng nút hiển thị ở phía đầu và phía cuối phân trang.', 'sb-theme');
    printf('<input type="number" id="%1$s" name="%2$s" value="%3$s" class="" min="1" max="10"><p class="description">%4$s</p>', 'sb_paginate_anchor', esc_attr('sb_options[paginate][anchor]'), $value, $description);
}

function sb_paginate_gap_callback() {
    $options = SB_Option::get();
    $value = isset($options['paginate']['gap']) ? $options['paginate']['gap'] : 3;
    $description = __('Số lượng nút tối thiểu hiển thị trước khi xuất hiện dấu ba chấm.', 'sb-theme');
    printf('<input type="number" id="%1$s" name="%2$s" value="%3$s" class="" min="1" max="100"><p class="description">%4$s</p>', 'sb_paginate_gap', esc_attr("sb_options[paginate][gap]"), $value, $description);
}

function sb_paginate_style_callback() {
    $args = array(
        'default' => __('Mặc định', 'sb-theme'),
        'orange' => __('Màu cam', 'sb-theme'),
        'dark' => __('Màu tối', 'sb-theme')
    );
    $styles = apply_filters('sb_paginate_style', $args);
    $name = 'sb_paginate_style';
    $options = SB_Option::get();
    $value = isset($options['paginate']['style']) ? $options['paginate']['style'] : 'default';
    $description = __('Lựa chọn tông màu cho phân trang.', 'sb-theme');
    ?>
    <label for="<?php echo $name; ?>"></label>
    <select id="<?php echo $name; ?>" name="<?php echo esc_attr('sb_options[paginate][style]'); ?>">
        <?php foreach($styles as $key => $title) : ?>
            <option value="<?php echo $key; ?>"<?php selected( $value, $key ); ?>><?php echo $title; ?></option>
        <?php endforeach; ?>
    </select>
    <p class="description"><?php echo $description; ?></p>
<?php
}

function sb_paginate_border_radius_callback() {
    $args = array(
        'default' => __('Mặc định', 'sb-theme'),
        'elipse' => __('Hình elip', 'sb-theme'),
        'none' => __('Không', 'sb-theme')
    );
    $styles = apply_filters('sb_paginate_border_radius', $args);
    $name = 'sb_paginate_border_radius';
    $options = SB_Option::get();
    $value = isset($options['paginate']['border_radius']) ? $options['paginate']['border_radius'] : 'default';
    $description = __('Bạn có thể chọn cho các nút phân trang có hình bo viền hoặc không.', 'sb-theme');
    ?>
    <label for="<?php echo $name; ?>"></label>
    <select id="<?php echo $name; ?>" name="<?php echo esc_attr('sb_options[paginate][border_radius]'); ?>">
        <?php foreach($styles as $key => $title) : ?>
            <option value="<?php echo $key; ?>"<?php selected( $value, $key ); ?>><?php echo $title; ?></option>
        <?php endforeach; ?>
    </select>
    <p class="description"><?php echo $description; ?></p>
<?php
}

function sb_paginate_sanitize($input) {
    $data = $input;
    $data['paginate']['range'] = isset($input['paginate']['range']) ? $input['paginate']['range'] : 3;
    $data['paginate']['gap'] = isset($input['paginate']['gap']) ? $input['paginate']['gap'] : 3;
    $data['paginate']['anchor'] = isset($input['paginate']['anchor']) ? $input['paginate']['anchor'] : 1;
    $data['paginate']['style'] = isset($input['paginate']['style']) ? $input['paginate']['style'] : 'default';
    $data['paginate']['border_radius'] = isset($input['paginate']['border_radius']) ? $input['paginate']['border_radius'] : 'default';
    return $data;
}
//add_filter('sb_options_sanitize', 'sb_paginate_sanitize');

/*
 * Plugin SB Post Widget
 */
function sb_post_widget_menu() {
    SB_Admin_Custom::add_submenu_page('Post Widget', 'sb_post_widget', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_post_widget_menu');

function sb_post_widget_tab($tabs) {
    $tabs['sb_post_widget'] = array('title' => 'Post Widget', 'section_id' => 'sb_post_widget_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_post_widget_tab');

function sb_post_widget_setting_field() {
    SB_Admin_Custom::add_section('sb_post_widget_section', __('Trang cài đặt tùy chọn cho Post Widget', 'sb-theme'), 'sb_post_widget');
    SB_Admin_Custom::add_setting_field('sb_post_widget_no_thumbnail', __('Ảnh thumbnail mặc định', 'sb-theme'), 'sb_post_widget_section', 'sb_post_widget_no_thumbnail_callback', 'sb_post_widget');
}
add_action('sb_admin_init', 'sb_post_widget_setting_field');

function sb_post_widget_no_thumbnail_callback() {
    $id = 'sb_post_widget_no_thumbnail';
    $name = 'sb_options[post_widget][no_thumbnail]';
    $value = SB_Option::get_widget_thumbnail_url();
    $description = __('Bạn có thể nhập vào được dẫn hoặc tải lên hình ảnh mới.', 'sb-theme');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::media_image($args);
}

/*
 * Plugin SB Tab Widget
 */
function sb_tab_widget_menu() {
    SB_Admin_Custom::add_submenu_page('Tab Widget', 'sb_tab_widget', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_tab_widget_menu');

function sb_tab_widget_tab($tabs) {
    $tabs['sb_tab_widget'] = array('title' => 'Tab Widget', 'section_id' => 'sb_tab_widget_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_tab_widget_tab');

function sb_tab_widget_setting_field() {
    SB_Admin_Custom::add_section('sb_tab_widget_section', __('Trang cài đặt tùy chọn cho Tab Widget', 'sb-tab-widget'), 'sb_tab_widget');
    SB_Admin_Custom::add_setting_field('sb_tab_widget_sidebar', __('Sidebar', 'sb-tab-widget'), 'sb_tab_widget_section', 'sb_tab_widget_sidebar_callback', 'sb_tab_widget');
}
add_action('sb_admin_init', 'sb_tab_widget_setting_field');

function sb_tab_widget_sidebar_callback() {
    $id = 'sb_tab_widget_sidebar';
    $name = 'sb_options[tab_widget][sidebar]';
    $list_sidebars = sb_tab_widget_get_sidebars();
    $description = __('Bạn có thể tạo hoặc xóa sidebar cho phép hiển thị các widget dưới dạng tab.', 'sb-tab-widget');
    $tabber_sidebar = SB_Core::get_sidebar_by('id', 'sb-tabber');
    $default_sidebars = array(
        array(
            'id' => $tabber_sidebar['id'],
            'name' => $tabber_sidebar['name'],
            'description' => $tabber_sidebar['description']
        )
    );
    $args = array(
        'id' => $id,
        'name' => $name,
        'list_sidebars' => $list_sidebars,
        'description' => $description,
        'default_sidebars' => $default_sidebars
    );
    SB_Field::widget_area($args);
}