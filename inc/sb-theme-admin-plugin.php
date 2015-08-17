<?php
defined('ABSPATH') or die('Please do not pip me!');

/*
 * Plugin SB Clean
 */
function sb_clean_menu() {
    $title = __('Clean', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $title = 'Dọn dẹp';
    }
    SB_Admin_Custom::add_submenu_page($title, 'sb_clean', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_clean_menu');

function sb_clean_tab($tabs) {
    $title = __('Clean', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $title = 'Dọn dẹp';
    }
    $tabs['sb_clean'] = array('title' => $title, 'section_id' => 'sb_clean_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_clean_tab');

function sb_clean_setting_field() {
    $lang = SB_Core::get_language();
    $text = ('vi' == $lang) ? 'Trang cài đặt tùy chọn dọn dẹp hệ thống' : __('Clean settings page', 'sb-theme');
    SB_Admin_Custom::add_section('sb_clean_section', $text, 'sb_clean');
    $text = ('vi' == $lang) ? 'Xóa thông tin WPDB' : __('Remove WPDB information', 'sb-theme');
    SB_Admin_Custom::add_setting_field('sb_clean_wpdb', $text, 'sb_clean_section', 'sb_clean_wpdb_callback', 'sb_clean');
    $text = ('vi' == $lang) ? 'Rút gọn thẻ head' : __('Optimize head tag', 'sb-theme');
    SB_Admin_Custom::add_setting_field('sb_clean_head_meta', $text, 'sb_clean_section', 'sb_clean_head_meta_callback', 'sb_clean');
    $text = ('vi' == $lang) ? 'Tối ưu cơ sở dữ liệu' : __('Optimize database', 'sb-theme');
    SB_Admin_Custom::add_setting_field('sb_theme_optimize_database', $text, 'sb_clean_section', 'sb_theme_optimize_database_setting_callback', 'sb_clean');
}
add_action('sb_admin_init', 'sb_clean_setting_field');

function sb_theme_optimize_database_setting_callback() {
    $lang = SB_Core::get_language();
    $args = array(
        'label' => ('vi' == $lang) ? 'Xóa tất cả bài viết tạm' : __('Remove all revisions', 'sb-theme'),
        'field_class' => 'delete-revision',
        'description' => ('vi' == $lang) ? 'Bài viết tạm (revision) là những phiên bản sao lưu khác nhau của cùng một bài viết.' : __('The WordPress revisions system stores a record of each saved draft or published update.', 'sb-theme'),
        'container_class' => 'margin-bottom',
        'value' => 1
    );
    SB_Field::checkbox($args);

    $args = array(
        'label' => ('vi' == $lang) ? 'Xóa tất cả bài viết nháp được lưu tự động' : __('Remove all auto saved posts', 'sb-theme'),
        'field_class' => 'delete-auto-draft',
        'description' => ('vi' == $lang) ? 'Các bài viết nháp (auto draft) được tự động tạo ra khi bạn soạn thảo bài viết.' : __('Autosave is a feature in WordPress which automatically saves changes made to a post, page, or custom post type.', 'sb-theme'),
        'container_class' => 'margin-bottom',
        'value' => 1
    );
    SB_Field::checkbox($args);

    $args = array(
        'label' => ('vi' == $lang) ? 'Xóa tất cả bình luận spam và chưa được xét duyệt' : __('Remove all spam comments and awaiting moderation comments', 'sb-theme'),
        'field_class' => 'delete-spam-comment',
        'container_class' => 'margin-bottom',
        'value' => 1
    );
    SB_Field::checkbox($args);

    $args = array(
        'label' => ('vi' == $lang) ? 'Làm trống thùng rác' : __('Empty trash', 'sb-theme'),
        'field_class' => 'delete-trash',
        'description' => ('vi' == $lang) ? 'Xóa tất cả các bài viết trong thùng rác, bao gồm thùng rác của bình luận.' : __('Empty all trashs, include post trash and comment trash.', 'sb-theme'),
        'container_class' => 'margin-bottom',
        'value' => 1
    );
    SB_Field::checkbox($args);

    $args = array(
        'label' => ('vi' == $lang) ? 'Xóa transient' : __('Delete trasinent', 'sb-theme'),
        'field_class' => 'delete-transient',
        'description' => ('vi' == $lang) ? 'Transient là các giá trị được lưu trữ tạm thời trong cơ sở dữ liệu của bạn.' : __('Transient Options are like a basic cache system used by wordpress. Clearing these options before a backup will help to save space in your backup files.', 'sb-theme'),
        'container_class' => 'margin-bottom',
        'value' => 0
    );
    SB_Field::checkbox($args);

    $args = array(
        'text' => ('vi' == $lang) ? 'Tiến hành' : __('Process', 'sb-theme'),
        'field_class' => 'sb-theme-optimize-database',
        'description' => ('vi' == $lang) ? 'Bạn nên sao lưu cơ sở dữ liệu trước khi tiến hành.' : __('Please backup database before you start.', 'sb-theme')
    );
    SB_Field::button($args);
}

function sb_clean_wpdb_callback() {
    $name = 'sb_options[clean][wpdb]';
    $options = SB_Option::get();
    $value = isset($options['clean']['wpdb']) ? $options['clean']['wpdb'] : 1;
    $description = ('vi' == SB_Core::get_language()) ? 'Bạn có thể bật hoặc tắt chức năng xóa thông tin tên cơ sở dữ liệu và mật khẩu đăng nhập.' : __('Enable or disable the functions to remove database connection information on $wpdb variable.', 'sb-theme');
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
    $description = ('vi' == SB_Core::get_language()) ? 'Bạn có thể bật hoặc tắt chức năng cho phép xóa và rút gọn bớt các thẻ meta trong thẻ head.' : __('Enable or disable the functions to delete extra information on head tag.', 'sb-theme');
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
    $title = __('Discussion', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $title = 'Thảo luận';
    }
    SB_Admin_Custom::add_submenu_page($title, 'sb_comment', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_comment_menu');

function sb_comment_tab($tabs) {
    $title = __('Discussion', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $title = 'Thảo luận';
    }
    $tabs['sb_comment'] = array('title' => $title, 'section_id' => 'sb_comment_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_comment_tab');

function sb_comment_setting_field() {
    $lang = SB_Core::get_language();
    $text = ('vi' == $lang) ? 'Trang cài đặt tùy chọn cho thảo luận' : __('Discussion settings page', 'sb-theme');
    SB_Admin_Custom::add_section('sb_comment_section', $text, 'sb_comment');
    $text = ('vi' == $lang) ? 'Kiểm tra spam' : __('Spam checks', 'sb-theme');
    SB_Admin_Custom::add_setting_field('sb_comment_spam_check', $text, 'sb_comment_section', 'sb_comment_spam_check_callback', 'sb_comment');
    $text = ('vi' == $lang) ? 'Thông báo người dùng' : __('Notify user', 'sb-theme');
    SB_Admin_Custom::add_setting_field('sb_comment_notify_user', $text, 'sb_comment_section', 'sb_comment_notify_user_callback', 'sb_comment');
    $text = ('vi' == $lang) ? 'Tự động xóa spam' : __('Auto delete spam', 'sb-theme');
    SB_Admin_Custom::add_setting_field('sb_comment_auto_empty_spam', $text, 'sb_comment_section', 'sb_comment_auto_empty_spam_callback', 'sb_comment');
    $text = ('vi' == $lang) ? 'Ẩn url khi bình luận' : __('Hide commenter url', 'sb-theme');
    SB_Admin_Custom::add_setting_field('sb_comment_remove_url', $text, 'sb_comment_section', 'sb_comment_remove_url_callback', 'sb_comment');
    $text = ('vi' == $lang) ? 'Công cụ bình luận' : __('Comment tools', 'sb-theme');
    SB_Admin_Custom::add_setting_field('sb_comment_tools', $text, 'sb_comment_section', 'sb_comment_tools_callback', 'sb_comment');
    $text = ('vi' == $lang) ? 'Avatar mặc định' : __('Default avatar', 'sb-theme');
    SB_Admin_Custom::add_setting_field('sb_comment_default_avatar', $text, 'sb_comment_section', 'sb_comment_default_avatar_callback', 'sb_comment');
    if(SB_Comment::use_default_avatar()) {
        $text = ('vi' == $lang) ? 'Đường dẫn avatar mặc định' : __('Default avatar image', 'sb-theme');
        SB_Admin_Custom::add_setting_field('sb_comment_default_avatar_url', $text, 'sb_comment_section', 'sb_comment_default_avatar_url_callback', 'sb_comment');
    }
}
add_action('sb_admin_init', 'sb_comment_setting_field');

function sb_comment_default_avatar_url_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['default_avatar_url']) ? $options['comment']['default_avatar_url'] : '';
    $id = 'sb_comment_default_avatar_url';
    $name = 'sb_options[comment][default_avatar_url]';
    $description = ('vi' == SB_Core::get_language()) ? 'Bạn có thể nhập đường dẫn hoặc tải lên hình ảnh mới.' : __('You can enter image url or upload new image.', 'sb-theme');
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
    $description = ('vi' == SB_Core::get_language()) ? 'Bạn có thể bật hoặc tắt chức năng cho phép sử dụng avatar mặc định cục bộ.' : __('Enable or disable the functions for using local avatar.', 'sb-theme');
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
    $description = ('vi' == SB_Core::get_language()) ? 'Bạn có thể bật hoặc tắt chức năng cho phép ẩn thông tin url khi gửi bình luận.' : __('Enable or disable the functions to hidding commenter website url.', 'sb-theme');
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
    $description = ('vi' == SB_Core::get_language()) ? 'Bạn có thể bật hoặc tắt chức năng cho phép hiển thị thanh công cụ khi bình luận.' : __('Enable or disable the functions to uses comment tools.', 'sb-theme');
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
    $description = ('vi' == SB_Core::get_language()) ? 'Bạn có thể bật hoặc tắt chức năng cho phép xóa các bình luận spam một cách tự động.' : __('Enable or disable the functions to delete spam comment automatically.', 'sb-theme');
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
    $description = ('vi' == SB_Core::get_language()) ? 'Bạn có thể bật hoặc tắt chức năng cho phép thông báo người dùng khi bình luận được duyệt.' : __('Enable or disable the functions to notify users when their comment was approved.', 'sb-theme');
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
    $description = ('vi' == SB_Core::get_language()) ? 'Bạn có thể bật hoặc tắt chức năng kiểm tra bình luận spam trước khi nhập vào cơ sở dữ liệu.' : __('Enable or disable the functions to check spam comment before inserted to database.', 'sb-theme');
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
    $title = __('Login', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $title = 'Đăng nhập';
    }
    SB_Admin_Custom::add_submenu_page($title, 'sb_login_page', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_login_page_menu');

function sb_login_page_tab($tabs) {
    $title = __('Login', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $title = 'Đăng nhập';
    }
    $tabs['sb_login_page'] = array('title' => $title, 'section_id' => 'sb_login_page_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_login_page_tab');

function sb_login_page_add_admin_setting_field($id, $title, $callback) {
    SB_Admin_Custom::add_setting_field($id, $title, 'sb_login_page_section', $callback, 'sb_login_page');
}

function sb_login_page_setting_field() {
    $lang = SB_Core::get_language();
    $text = ('vi' == $lang) ? 'Trang cài đặt tùy chọn cho đăng nhập' : __('Login settings page', 'sb-theme');
    SB_Admin_Custom::add_section('sb_login_page_section', $text, 'sb_login_page');
    $text = ('vi' == $lang) ? 'Cho phép đăng ký' : __('Allow user registration', 'sb-theme');
    sb_login_page_add_admin_setting_field('sb_login_page_user_can_register', $text, 'sb_login_page_user_can_register_callback');
    $text = ('vi' == $lang) ? 'Đăng nhập tùy chỉnh' : __('Custom login', 'sb-theme');
    sb_login_page_add_admin_setting_field('sb_login_page_use_sb_login', $text, 'sb_login_page_use_sb_login_callback');
    SB_Admin_Custom::add_setting_field('sb_login_page_logo', __('Logo', 'sb-theme'), 'sb_login_page_section', 'sb_login_page_logo_callback', 'sb_login_page');
    if(sb_login_page_use_sb_login()) {
        $text = ('vi' == $lang) ? 'Trang tài khoản' : __('Account page', 'sb-theme');
        sb_login_page_add_admin_setting_field('sb_login_page_page_account', $text, 'sb_login_page_page_account_callback');
        $text = ('vi' == $lang) ? 'Trang đăng nhập' : __('Login page', 'sb-theme');
        sb_login_page_add_admin_setting_field('sb_login_page_page_login', $text, 'sb_login_page_page_login_callback');
        $text = ('vi' == $lang) ? 'Trang khôi phục mật khẩu' : __('Lost password page', 'sb-theme');
        sb_login_page_add_admin_setting_field('sb_login_page_page_lost_password', $text, 'sb_login_page_page_lost_password_callback');
        $text = ('vi' == $lang) ? 'Trang đăng ký' : __('Signup page', 'sb-theme');
        sb_login_page_add_admin_setting_field('sb_login_page_page_register', $text, 'sb_login_page_page_register_callback');
    }
    $text = ('vi' == $lang) ? 'Chuyển tiếp khi đăng nhập' : __('Login redirect', 'sb-theme');
    sb_login_page_add_admin_setting_field('sb_login_page_login_redirect', $text, 'sb_login_page_login_redirect_callback');
    $text = ('vi' == $lang) ? 'Chuyển tiếp khi đăng xuất' : __('Logout redirect', 'sb-theme');
    sb_login_page_add_admin_setting_field('sb_login_page_logout_redirect', $text, 'sb_login_page_logout_redirect_callback');
    $text = ('vi' == $lang) ? 'Kết nối mạng xã hội' : __('Social connection', 'sb-theme');
    sb_login_page_add_admin_setting_field('sb_login_page_social_login', $text, 'sb_login_page_social_login_callback');
    $text = ('vi' == $lang) ? 'Sử dụng captcha' : __('Use captcha', 'sb-theme');
    sb_login_page_add_admin_setting_field('sb_login_page_use_captcha', $text, 'sb_login_page_use_captcha_callback');
}
add_action('sb_admin_init', 'sb_login_page_setting_field');

function sb_login_page_use_captcha_callback() {
    $value = absint(SB_Option::use_login_captcha());
    $desc = ('vi' == SB_Core::get_language()) ? 'Bật hoặc tắt chức năng cho phép sử dụng mã captcha khi đăng ký và đăng nhập.' : __('Enable or disable the function that allows uses captcha on login form.', 'sb-theme');
    $args = array(
        'id' => 'sb_login_page_use_captcha',
        'name' => 'sb_options[login_page][use_captcha]',
        'value' => $value,
        'description' => $desc
    );
    SB_Field::switch_button($args);
}

function sb_login_page_social_login_callback() {
    $value = absint(SB_Option::social_login_enabled());
    $args = array(
        'id' => 'sb_login_page_social_login',
        'name' => 'sb_options[login_page][social_login]',
        'value' => $value,
        'description' => ('vi' == SB_Core::get_language()) ? 'Bật hoặc tắt chức năng cho phép đăng nhập với tài khoản mạng xã hội.' : __('Enable or disable the function that allows user login with social account.', 'sb-theme')
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
        'description' => ('vi' == SB_Core::get_language()) ? 'Bật hoặc tắt chức năng sử dụng hệ thống đăng nhập và đăng ký của SB Team.' : __('Enable or disable the function that allows uses custom login.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_login_page_logout_redirect_callback() {
    $options = SB_Option::get();
    $lang = SB_Core::get_language();
    $value = isset($options['login_page']['logout_redirect']) ? $options['login_page']['logout_redirect'] : 'home';
    $options = array(
        'home' => ('vi' == $lang) ? 'Trang chủ' : __('Homepage', 'sb-theme'),
        'current' => ('vi' == $lang) ? 'Trang hiện tại' : __('Current page', 'sb-theme')
    );
    $args = array(
        'id' => 'sb_login_page_logout_redirect',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'logout_redirect')),
        'value' => $value,
        'options' => $options,
        'description' => ('vi' == $lang) ? 'Lựa chọn trang chuyển tiếp đến khi người dùng đăng xuất khỏi hệ thống.' : __('Choose the page redirect to when user logout.', 'sb-theme')
    );
    SB_Field::select($args);
}

function sb_login_page_login_redirect_callback() {
    $lang = SB_Core::get_language();
    $options = SB_Option::get();
    $value = isset($options['login_page']['login_redirect']) ? $options['login_page']['login_redirect'] : 'current';
    $options = array(
        'home' => ('vi' == $lang) ? 'Trang chủ' : __('Home', 'sb-theme'),
        'profile' => ('vi' == $lang) ? 'Trang thông tin cá nhân' : __('Profile', 'sb-theme'),
        'dashboard' => ('vi' == $lang) ? 'Bảng điều khiển' : __('Dashboard', 'sb-theme'),
        'current' => ('vi' == $lang) ? 'Trang hiện tại' : __('Current page', 'sb-theme')
    );
    $args = array(
        'id' => 'sb_login_page_login_redirect',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'login_redirect')),
        'value' => $value,
        'options' => $options,
        'description' => ('vi' == $lang) ? 'Lựa chọn trang chuyển tiếp đến khi người dùng đăng nhập vào hệ thống.' : __('Choose the page for reidirecting to after user logged in.', 'sb-theme')
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
        'description' => ('vi' == SB_Core::get_language()) ? 'Bật hoặc tắt chức năng cho phép người dùng đăng ký tài khoản.' : __('Enable or disable the function that allows user to register.', 'sb-theme')
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
        'description' => ('vi' == SB_Core::get_language()) ? 'Chọn trang cho người dùng đăng ký tài khoản.' : __('Choose the custom page for user to register.', 'sb-theme')
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
        'description' => ('vi' == SB_Core::get_language()) ? 'Chọn trang cho người dùng khôi phục mật khẩu.' : __('Choose the custom page for user to recover password.', 'sb-theme')
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
        'description' => ('vi' == SB_Core::get_language()) ? 'Chọn trang cho người dùng quản lý tài khoản.' : __('Choose the custom page for user to manage account.', 'sb-theme')
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
        'description' => ('vi' == SB_Core::get_language()) ? 'Chọn trang cho người dùng đăng nhập.' : __('Choose the custom page for user to login.', 'sb-theme')
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
        'description' => ('vi' == SB_Core::get_language()) ? 'Bạn có thể nhập đường dẫn hoặc tải lên hình ảnh mới.' : __('You can enter image url or upload new image.', 'sb-theme')
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
    $title = __('Pagination', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $title = 'Phân trang';
    }
    SB_Admin_Custom::add_submenu_page($title, 'sb_paginate', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_paginate_menu');

function sb_paginate_tab($tabs) {
    $title = __('Pagination', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $title = 'Phân trang';
    }
    $tabs['sb_paginate'] = array('title' => $title, 'section_id' => 'sb_paginate_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_paginate_tab');

function sb_paginate_setting_field() {
    $lang = SB_Core::get_language();
    $text = ('vi' == $lang) ? 'Trang cài đặt tùy chọn cho phân trang' : __('Pagination settings page', 'sb-theme');
    SB_Admin_Custom::add_section('sb_paginate_section', $text, 'sb_paginate');
    $text = ('vi' == $lang) ? 'Tiêu đề' : __('Label', 'sb-theme');
    SB_Admin_Custom::add_setting_field('sb_paginate_label', $text, 'sb_paginate_section', 'sb_paginate_label_callback', 'sb_paginate');
    $text = ('vi' == $lang) ? 'Trang tiếp' : __('Next label', 'sb-theme');
    SB_Admin_Custom::add_setting_field('sb_paginate_next_text', $text, 'sb_paginate_section', 'sb_paginate_next_text_callback', 'sb_paginate');
    $text = ('vi' == $lang) ? 'Trang trước' : __('Previous label', 'sb-theme');
    SB_Admin_Custom::add_setting_field('sb_paginate_previous_text', $text, 'sb_paginate_section', 'sb_paginate_previous_text_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_range', __('Range', 'sb-theme'), 'sb_paginate_section', 'sb_paginate_range_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_anchor', __('Anchor', 'sb-theme'), 'sb_paginate_section', 'sb_paginate_anchor_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_gap', __('Gap', 'sb-theme'), 'sb_paginate_section', 'sb_paginate_gap_callback', 'sb_paginate');
    $text = ('vi' == $lang) ? 'Kiểu hiển thị' : __('Style', 'sb-theme');
    SB_Admin_Custom::add_setting_field('sb_paginate_style', $text, 'sb_paginate_section', 'sb_paginate_style_callback', 'sb_paginate');
    $text = ('vi' == $lang) ? 'Bo viền nút' : __('Border radius', 'sb-theme');
    SB_Admin_Custom::add_setting_field('sb_paginate_border_radius', $text, 'sb_paginate_section', 'sb_paginate_border_radius_callback', 'sb_paginate');
}
add_action('sb_admin_init', 'sb_paginate_setting_field');

function sb_paginate_next_text_callback() {
    $options = SB_Option::get();
    $value = isset($options['paginate']['next_text']) ? $options['paginate']['next_text'] : '&raquo;';
    $id = 'sb_paginate_next_text';
    $name = 'sb_options[paginate][next_text]';
    $description = ('vi' == SB_Core::get_language()) ? 'Chữ hiển thị cho nút trang tiếp theo.' : __('The label text for next page button.', 'sb-theme');
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
    $description = ('vi' == SB_Core::get_language()) ? 'Chữ hiển thị cho nút trang trước đó.' : __('The label text for previous page button.', 'sb-theme');
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
    $value = isset($options['paginate']['label']) ? $options['paginate']['label'] : ('vi' == SB_Core::get_language()) ? 'Trang' : __('Pages', 'sb-theme');
    $id = 'sb_paginate_label';
    $name = 'sb_options[paginate][label]';
    $description = ('vi' == SB_Core::get_language()) ? 'Dòng chữ được hiển thị phía đầu của phân trang.' : __('The label text displayed at the beginning of pagination.', 'sb-theme');
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
    $description = ('vi' == SB_Core::get_language()) ? 'Số lượng nút được hiển thị phía trước và sau nút của trang hiện tại.' : __('Number of button displayed before and after the current button.', 'sb-theme');
    printf('<input type="number" id="%1$s" name="%2$s" value="%3$s" class="" min="1" max="100"><p class="description">%4$s</p>', 'sb_paginate_range', esc_attr('sb_options[paginate][range]'), $value, $description);
}

function sb_paginate_anchor_callback() {
    $options = SB_Option::get();
    $value = isset($options['paginate']['anchor']) ? $options['paginate']['anchor'] : 1;
    $description = ('vi' == SB_Core::get_language()) ? 'Số lượng nút hiển thị ở phía đầu và phía cuối phân trang.' : __('Number of button displayed at the beginning and the end of pagination.', 'sb-theme');
    printf('<input type="number" id="%1$s" name="%2$s" value="%3$s" class="" min="1" max="10"><p class="description">%4$s</p>', 'sb_paginate_anchor', esc_attr('sb_options[paginate][anchor]'), $value, $description);
}

function sb_paginate_gap_callback() {
    $options = SB_Option::get();
    $value = isset($options['paginate']['gap']) ? $options['paginate']['gap'] : 3;
    $description = ('vi' == SB_Core::get_language()) ? 'Số lượng nút tối thiểu hiển thị trước khi xuất hiện dấu ba chấm.' : __('The minimum number of buttons displayed before replace with ellipse.', 'sb-theme');
    printf('<input type="number" id="%1$s" name="%2$s" value="%3$s" class="" min="1" max="100"><p class="description">%4$s</p>', 'sb_paginate_gap', esc_attr("sb_options[paginate][gap]"), $value, $description);
}

function sb_paginate_style_callback() {
    $lang = SB_Core::get_language();
    $args = array(
        'default' => ('vi' == $lang) ? 'Mặc định' : __('Default', 'sb-theme'),
        'orange' => ('vi' == $lang) ? 'Màu cam' : __('Orange', 'sb-theme'),
        'dark' => ('vi' == $lang) ? 'Màu tối' : __('Dark', 'sb-theme')
    );
    $styles = apply_filters('sb_paginate_style', $args);
    $name = 'sb_paginate_style';
    $options = SB_Option::get();
    $value = isset($options['paginate']['style']) ? $options['paginate']['style'] : 'default';
    $description = ('vi' == $lang) ? 'Lựa chọn tông màu cho phân trang.' : __('Choose the style for pagination.', 'sb-theme');
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
    $lang = SB_Core::get_language();
    $args = array(
        'default' => ('vi' == $lang) ? 'Mặc định' : __('Default', 'sb-theme'),
        'elipse' => ('vi' == $lang) ? 'Hình elip' : __('Ellipse', 'sb-theme'),
        'none' => ('vi' == $lang) ? 'Không' : __('None', 'sb-theme')
    );
    $styles = apply_filters('sb_paginate_border_radius', $args);
    $name = 'sb_paginate_border_radius';
    $options = SB_Option::get();
    $value = isset($options['paginate']['border_radius']) ? $options['paginate']['border_radius'] : 'default';
    $description = ('vi' == $lang) ? 'Bạn có thể chọn cho các nút phân trang có hình bo viền hoặc không.' : __('Choose border radius style for pagination button.', 'sb-theme');
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
    $lang = SB_Core::get_language();
    SB_Admin_Custom::add_section('sb_post_widget_section', ('vi' == $lang) ? 'Trang cài đặt tùy chọn cho Post Widget' : __('Post Widget settings page', 'sb-theme'), 'sb_post_widget');
    SB_Admin_Custom::add_setting_field('sb_post_widget_no_thumbnail', ('vi' == $lang) ? 'Ảnh thumbnail mặc định' : __('Default thumbnail', 'sb-theme'), 'sb_post_widget_section', 'sb_post_widget_no_thumbnail_callback', 'sb_post_widget');
}
add_action('sb_admin_init', 'sb_post_widget_setting_field');

function sb_post_widget_no_thumbnail_callback() {
    $id = 'sb_post_widget_no_thumbnail';
    $name = 'sb_options[post_widget][no_thumbnail]';
    $value = SB_Option::get_widget_thumbnail_url();
    $description = ('vi' == SB_Core::get_language()) ? 'Bạn có thể nhập vào đường dẫn hoặc tải lên hình ảnh mới.' : __('You can enter image url or upload new image.', 'sb-theme');
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