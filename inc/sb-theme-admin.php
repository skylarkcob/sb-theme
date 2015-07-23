<?php
defined('ABSPATH') or die('Please do not pip me!');

$sb_admin = new SB_Admin();

add_action( 'init', 'sb_theme_check_license' );

function is_sb_admin_page() {
    $result = SB_Admin_Custom::is_sb_page();
    return apply_filters( 'sb_admin_page', $result );
}

function sb_theme_menu() {
    SB_Admin_Custom::add_submenu_page(__('Cài đặt giao diện', 'sb-theme'), 'sb_theme', array('SB_Admin_Custom', 'setting_page_callback'));
    SB_Admin_Custom::add_submenu_page('Tiện ích', 'sb_utilities', array('SB_Admin_Custom', 'setting_page_callback'));
	if(SB_Theme::support('sb_statistics')) {
        SB_Admin_Custom::add_submenu_page('Thống kê', 'sb_statistics', array('SB_Admin_Custom', 'setting_page_callback'));
    }
    SB_Admin_Custom::add_submenu_page(__('Cài đặt nâng cao', 'sb-theme'), 'sbt_advanced', array('SB_Admin_Custom', 'setting_page_callback'));
    SB_Admin_Custom::add_submenu_page('SMTP Email', 'sbt_smtp_email', array('SB_Admin_Custom', 'setting_page_callback'));
    if(SB_Theme::support('sb_checkout')) {
        SB_Admin_Custom::add_submenu_page(__('Thanh toán', 'sb-theme'), 'sbt_checkout', array('SB_Admin_Custom', 'setting_page_callback'));
    }
    if(SB_Theme::support('woocommerce')) {
        SB_Admin_Custom::add_submenu_page(__('Cửa hàng', 'sb-theme'), 'sbt_store', array('SB_Admin_Custom', 'setting_page_callback'));
    }
	if(SB_Tool::use_vchat()) {
		SB_Admin_Custom::add_submenu_page(__('vChat', 'sb-theme'), 'sbt_vchat', array('SB_Admin_Custom', 'vchat_setting_page_callback'));
	}
    SB_Admin_Custom::add_submenu_page(__('Socials', 'sb-theme'), 'sbt_socials', array('SB_Admin_Custom', 'socials_setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_theme_menu');

function sb_theme_setting_tab($tabs) {
    $tabs['sb_theme'] = array('title' => __('Cài đặt giao diện', 'sb-theme'), 'section_id' => 'sb_theme_setting_section', 'type' => 'theme');
    $tabs['sb_utilities'] = array('title' => 'Tiện ích', 'section_id' => 'sb_utilities_section', 'type' => 'theme');
	if(SB_Theme::support('sb_statistics')) {
        $tabs['sb_statistics'] = array('title' => 'Thống kê', 'section_id' => 'sb_statistics_section', 'type' => 'theme');
    }
    $tabs['sbt_advanced'] = array('title' => __('Cài đặt nâng cao', 'sb-theme'), 'section_id' => 'sb_theme_advanced_setting_section', 'type' => 'theme');
    $tabs['sbt_smtp_email'] = array('title' => __('SMTP Email', 'sb-theme'), 'section_id' => 'sb_theme_smtp_email_setting_section', 'type' => 'theme');
    if(SB_Theme::support('sb_checkout')) {
        $tabs['sbt_checkout'] = array('title' => __('Thanh toán', 'sb-theme'), 'section_id' => 'sb_theme_checkout_setting_section', 'type' => 'theme');
    }
    if(SB_Theme::support('woocommerce')) {
        $tabs['sbt_store'] = array('title' => __('Cửa hàng', 'sb-theme'), 'section_id' => 'sb_theme_store_setting_section', 'type' => 'theme');
    }
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_theme_setting_tab');

function sb_theme_advanced_setting_field() {
    SB_Admin_Custom::add_section('sb_theme_advanced_setting_section', __('Trang cài đặt các tùy chọn nâng cao', 'sb-theme'), 'sbt_advanced');
    SB_Admin_Custom::add_setting_field('sb_theme_advanced_setting_page_content', '', 'sb_theme_advanced_setting_section', array('SB_Admin_Custom', 'row_setting_page_callback'), 'sbt_advanced');
}
add_action('sb_theme_option_page_init', 'sb_theme_advanced_setting_field');

function sb_theme_store_setting_field() {
    SB_Admin_Custom::add_section('sb_theme_store_setting_section', __('Trang cài đặt các tùy chọn cho cửa hàng', 'sb-theme'), 'sbt_store');
    SB_Admin_Custom::add_setting_field('sb_theme_store_setting_page_content', '', 'sb_theme_store_setting_section', array('SB_Admin_Custom', 'store_row_setting_page_callback'), 'sbt_store');
}
add_action('sb_theme_option_page_init', 'sb_theme_store_setting_field');

function sb_theme_checkout_setting_field() {
    SB_Admin_Custom::add_section('sb_theme_checkout_setting_section', __('Trang cài đặt tùy chọn cho thanh toán', 'sb-theme'), 'sbt_checkout');
    SB_Admin_Custom::add_setting_field('sb_theme_checkout_setting_page_content', '', 'sb_theme_checkout_setting_section', array('SB_Admin_Custom', 'checkout_setting_page_callback'), 'sbt_checkout');
}
add_action('sb_theme_option_page_init', 'sb_theme_checkout_setting_field');

function sb_theme_smtp_email_setting_field() {
    SB_Admin_Custom::add_section('sb_theme_smtp_email_setting_section', __('Trang cài đặt tùy chọn cho SMTP Email', 'sb-theme'), 'sbt_smtp_email');
    sb_theme_add_smtp_email_setting_field('sb_theme_smtp_email_enabled', __('Gửi mail bằng SMTP', 'sb-theme'), 'sb_theme_smpt_email_enabled_callback');
    if(SB_Tool::use_smtp_email()) {
        sb_theme_add_smtp_email_setting_field('sb_theme_smtp_email_from_name', __('Tên người gửi', 'sb-theme'), 'sb_theme_smpt_email_from_name_callback');
        sb_theme_add_smtp_email_setting_field('sb_theme_smtp_email_from_email', __('Email người gửi', 'sb-theme'), 'sb_theme_smpt_email_from_email_callback');
        sb_theme_add_smtp_email_setting_field('sb_theme_smtp_email_info', __('Thông tin SMTP', 'sb-theme'), 'sb_theme_smpt_email_info_callback');
        sb_theme_add_smtp_email_setting_field('sb_theme_smtp_email_encryption', __('Kiểu mã hóa', 'sb-theme'), 'sb_theme_smpt_email_encryption_callback');
        sb_theme_add_smtp_email_setting_field('sb_theme_smtp_email_username', __('Tài khoản', 'sb-theme'), 'sb_theme_smpt_email_username_callback');
        sb_theme_add_smtp_email_setting_field('sb_theme_smtp_email_password', __('Mật khẩu', 'sb-theme'), 'sb_theme_smpt_email_password_callback');
        sb_theme_add_smtp_email_setting_field('sb_theme_smtp_email_test', __('Thử gửi mail', 'sb-theme'), 'sb_theme_smpt_email_test_callback');
    }
    do_action('sb_theme_smtp_email_setting_page');
}
add_action('sb_theme_option_page_init', 'sb_theme_smtp_email_setting_field');

function sb_theme_smpt_email_test_callback() {
    $key = 'test_smtp_mail';
    $value = SB_Option::get_option_by_key(array('smtp_email', $key));

    $args = array(
        'id' => 'sb_theme_smtp_email_' . $key,
        'name' => $key,
        'value' => $value,
        'description' => __('Nhập địa chỉ email và thử gửi mail với thiết lập của bạn bên trên.'),
        'field_class' => 'width-medium test-field',
        'type' => 'email',
        'placeholder' => 'emailcuaban@gmail.com',
        'button' => true,
        'autocomplete' => false,
        'button_args' => array(
            'text' => __('Gửi', 'sb-theme'),
            'field_class' => 'test-smtp-mail',
            'autocomplete' => false
        )
    );
    SB_Field::text($args);
    echo '<div class="test-mail-debug"></div>';
}

function sb_theme_smpt_email_password_callback() {
    $key = 'password';
    $value = SB_Option::get_option_by_key(array('smtp_email', $key));

    $args = array(
        'id' => 'sb_theme_smtp_email_' . $key,
        'name' => SB_Option::build_sb_option_name(array('smtp_email', $key)),
        'value' => $value,
        'description' => __('Mật khẩu đăng nhập cho tài khoản của bạn.'),
        'field_class' => 'width-medium',
        'type' => 'password',
        'autocomplete' => false
    );
    SB_Field::text($args);
}

function sb_theme_smpt_email_username_callback() {
    $key = 'username';
    $value = SB_Option::get_option_by_key(array('smtp_email', $key));

    $args = array(
        'id' => 'sb_theme_smtp_email_' . $key,
        'name' => SB_Option::build_sb_option_name(array('smtp_email', $key)),
        'value' => $value,
        'description' => __('Tài khoản đăng nhập vào SMTP server.'),
        'field_class' => 'width-medium',
        'autocomplete' => false
    );
    SB_Field::text($args);
}

function sb_theme_smpt_email_encryption_callback() {
    $key = 'encryption';
    $value = SB_Option::get_option_by_key(array('smtp_email', $key));
    if(empty($value)) {
        $value = 'none';
    }
    $args = array(
        'id' => 'sb_theme_smtp_email_' . $key,
        'name' => SB_Option::build_sb_option_name(array('smtp_email', $key)),
        'value' => $value,
        'field_class' => 'width-medium',
        'type' => 'radio',
        'label' => __('Không mã hóa', 'sb-theme'),
        'option_value' => 'none'
    );
    SB_Field::text($args);

    $args = array(
        'id' => 'sb_theme_smtp_email_' . $key,
        'name' => SB_Option::build_sb_option_name(array('smtp_email', $key)),
        'value' => $value,
        'field_class' => 'width-medium',
        'type' => 'radio',
        'label' => __('Mã hóa SSL', 'sb-theme'),
        'option_value' => 'ssl',
        'container_class' => 'margin-top-10'
    );
    SB_Field::text($args);

    $args = array(
        'id' => 'sb_theme_smtp_email_' . $key,
        'name' => SB_Option::build_sb_option_name(array('smtp_email', $key)),
        'value' => $value,
        'field_class' => 'width-medium',
        'type' => 'radio',
        'label' => __('Mã hóa TLS', 'sb-theme'),
        'option_value' => 'tls',
        'container_class' => 'margin-top-10'
    );
    SB_Field::text($args);
}

function sb_theme_smpt_email_info_callback() {
    $key = 'smtp_host';
    $value = SB_Option::get_option_by_key(array('smtp_email', $key));
    $args = array(
        'id' => 'sb_theme_smtp_email_' . $key,
        'name' => SB_Option::build_sb_option_name(array('smtp_email', $key)),
        'value' => $value,
        'description' => __('Địa chỉ SMTP server, ví dụ: smtp.gmail.com.'),
        'field_class' => 'width-medium',
        'placeholder' => 'SMTP host'
    );
    SB_Field::text($args);

    $key = 'smtp_port';
    $value = SB_Option::get_option_by_key(array('smtp_email', $key));
    if(empty($value)) {
        $value = 25;
    }
    $args = array(
        'id' => 'sb_theme_smtp_email_' . $key,
        'name' => SB_Option::build_sb_option_name(array('smtp_email', $key)),
        'value' => $value,
        'description' => __('Port sử dụng gửi mail bằng SMTP.'),
        'field_class' => 'width-medium',
        'placeholder' => 'SMTP port',
        'container_class' => 'margin-top-10'
    );
    SB_Field::text($args);
}

function sb_theme_smpt_email_enabled_callback() {
    $key = 'enabled';
    $value = SB_Option::get_option_by_key(array('smtp_email', $key));
    $value = SB_Option::check_switch_value($value, 0);
    $args = array(
        'id' => 'sb_theme_smtp_email_' . $key,
        'name' => SB_Option::build_sb_option_name(array('smtp_email', $key)),
        'value' => $value,
        'description' => __('Kích hoạt chức năng gửi mail thông qua SMTP.'),
        'field_class' => 'width-medium'
    );
    SB_Field::switch_button($args);
}

function sb_theme_smpt_email_from_email_callback() {
    $key = 'from_email';
    $value = SB_Option::get_option_by_key(array('smtp_email', $key));
    if(empty($value)) {
        $value = SB_Option::get_admin_email();
    }
    $args = array(
        'id' => 'sb_theme_smtp_email_' . $key,
        'name' => SB_Option::build_sb_option_name(array('smtp_email', $key)),
        'value' => $value,
        'description' => __('Thiết lập địa chỉ email của người gửi mail, nếu để trống thì địa chỉ email quản trị blog sẽ được sử dụng.'),
        'field_class' => 'width-medium'
    );
    SB_Field::text($args);
}

function sb_theme_smpt_email_from_name_callback() {
    $key = 'from_name';
    $value = SB_Option::get_option_by_key(array('smtp_email', $key));
    if(empty($value)) {
        $value = get_bloginfo('name');
    }
    $args = array(
        'id' => 'sb_theme_smtp_email_' . $key,
        'name' => SB_Option::build_sb_option_name(array('smtp_email', $key)),
        'value' => $value,
        'description' => __('Thiết lập tên người gửi mail, nếu để trống thì tên của blog sẽ được sử dụng.'),
        'field_class' => 'width-medium'
    );
    SB_Field::text($args);
}

function sb_theme_setting_field() {
    SB_Admin_Custom::add_section('sb_theme_setting_section', __('Trang cài đặt tùy chọn cho giao diện', 'sb-theme'), 'sb_theme');
    SB_Admin_Custom::add_setting_field('sb_theme_default_language', __('Ngôn ngữ', 'sb-theme'), 'sb_theme_setting_section', 'sb_theme_default_language_callback', 'sb_theme');
    SB_Admin_Custom::add_setting_field('sb_theme_logo', 'Logo', 'sb_theme_setting_section', 'sb_theme_logo_callback', 'sb_theme');
    SB_Admin_Custom::add_setting_field('sb_theme_favicon', 'Favicon', 'sb_theme_setting_section', 'sb_theme_favicon_callback', 'sb_theme');
    if(SB_Option::utility_enabled('add_to_head')) {
        sb_theme_setting_field_add_to_head();
    }
    if(SB_Option::utility_enabled('default_thumbnail')) {
        sb_theme_setting_field_no_thumbnail();
    }
    if(SB_Option::utility_enabled('google_analytics')) {
        sb_theme_setting_field_google_analtyics();
    }
    if(SB_Option::utility_enabled('category_widget')) {
        sb_theme_setting_field_category_widget();
    }
    if(SB_Option::utility_enabled('facebook_php_sdk')) {
        sb_theme_setting_field_facebook_api();
    }
    if(SB_Option::utility_enabled('sharethis')) {
        sb_theme_setting_field_sharethis();
    }
    if(SB_Option::utility_enabled('addthis') || sb_theme_support_addthis()) {
        sb_theme_setting_field_addthis();
    }
    if(SB_Option::utility_enabled('facebook_fanpage')) {
        sb_theme_setting_field_facebook_fanpage();
    }
    do_action('sb_theme_setting_field');
    do_action('sb_theme_setting_page');
    if(SB_Option::utility_enabled('social')) {
        sb_theme_setting_field_social();
    }
    if(SB_Option::utility_enabled('footer_text')) {
        sb_theme_setting_field_footer_text();
    }
}
add_action('sb_admin_init', 'sb_theme_setting_field');

function sb_utilities_setting_field() {
    SB_Admin_Custom::add_section('sb_utilities_section', __('Trang cài đặt tùy chọn cho các tiện ích', 'sb-theme'), 'sb_utilities');
    do_action('sb_utilities_setting_field');
}
add_action('sb_admin_init', 'sb_utilities_setting_field');

function sb_statistics_setting_field() {
    SB_Admin_Custom::add_section('sb_statistics_section', __('Trang cài đặt tùy chọn cho thống kê', 'sb-theme'), 'sb_statistics');
    do_action('sb_statistics_setting_field');
	do_action('sb_theme_statistics_setting_page');
}
add_action('sb_admin_init', 'sb_statistics_setting_field');

function sb_theme_default_language_callback() {
    $lang = SB_Option::get_default_language();
    $options = array(
        'vi' => __('Tiếng Việt', 'sb-theme'),
        'en' => __('English', 'sb-theme')
    );
    $args = array(
        'id' => 'sb_theme_default_language',
        'name' => 'sb_options[theme][default_language]',
        'value' => $lang,
        'options' => $options,
        'description' => __('Lựa chọn ngôn ngữ sử dụng bên ngoài front-end.', 'sb-theme')
    );
    SB_Field::select($args);
}

function sb_theme_logo_callback() {
    $value = SB_Option::get_logo_detail();
    $args = array(
        'id' => 'sb_theme_logo',
        'name' => 'sb_options[theme][logo]',
        'value' => $value,
        'container_class' => 'margin-bottom',
        'description' => __('You can enter url or upload new logo image file.', 'sb-theme')
    );
    SB_Field::media_image($args);
    $value = SB_Option::get_logo_type();
    $list_options = array(
        'background' => __('Hình nền', 'sb-theme'),
        'image' => __('Hình ảnh', 'sb-theme'),
        'text' => __('Chữ', 'sb-theme')
    );
    $args = array(
        'id' => 'sb_theme_logo_type',
        'name' => 'sb_options[theme][logo_type]',
        'value' => $value,
        'list_options' => $list_options,
        'container_class' => 'margin-bottom',
        'field_class' => 'logo-type',
        'description' => __('Lựa chọn kiểu hiển thị cho logo.', 'sb-theme')
    );
    SB_Field::select($args);
    $container_class = 'hidden';
    if($value == 'text') {
        $container_class = 'visible';
    }
    $container_class .= ' logo-text';
    $value = SB_Option::get_logo_text();
    $args = array(
        'id' => 'sb_theme_logo_text',
        'name' => 'sb_options[theme][logo_text]',
        'value' => $value,
        'container_class' => $container_class,
        'description' => __('Enter the text you want to display as logo.', 'sb-theme')
    );
    SB_Field::text_field($args);
}

function sb_theme_favicon_callback() {
    $options = SB_Option::get();
    $value = isset($options['theme']['favicon']) ? $options['theme']['favicon'] : '';
    $args = array(
        'id' => 'sb_theme_favicon',
        'name' => 'sb_options[theme][favicon]',
        'value' => $value,
        'container_class' => 'small',
        'description' => __('You can enter url or upload new favicon image file.', 'sb-theme')
    );
    SB_Field::media_image($args);
}

function sb_theme_store_setting_general_hook() {
    SB_Theme::get_content('sb-theme-admin-store-setting-general');
}
add_action('sb_theme_store_setting_general_field', 'sb_theme_store_setting_general_hook');

function sb_theme_store_setting_text_hook() {
    SB_Theme::get_content('sb-theme-admin-store-setting-text');
}
add_action('sb_theme_store_setting_text_field', 'sb_theme_store_setting_text_hook');

/*
 * Trang cài đặt vChat
 */
function sb_theme_vchat_setting_dashboard_field() {
	SB_Theme::get_content('sb-theme-admin-vchat-setting-dashboard');
}
add_action('sb_theme_vchat_setting_dashboard_field', 'sb_theme_vchat_setting_dashboard_field');

function sb_theme_vchat_setting_setting_field() {
	SB_Theme::get_content('sb-theme-admin-vchat-setting-setting');
}
add_action('sb_theme_vchat_setting_setting_field', 'sb_theme_vchat_setting_setting_field');

/*
 * Trang cài đặt Socials
 */
function sb_theme_socials_setting_facebook_field() {
	SB_Theme::get_content('sb-theme-admin-socials-setting-facebook');
}
add_action('sb_theme_socials_setting_facebook_field', 'sb_theme_socials_setting_facebook_field');

function sb_theme_socials_setting_google_field() {
	SB_Theme::get_content('sb-theme-admin-socials-setting-google');
}
add_action('sb_theme_socials_setting_google_field', 'sb_theme_socials_setting_google_field');

/*
 * Thêm trường cài đặt vào tab Membership trong bảng điều khiển nâng cao
 */
function sb_theme_advanced_setting_membership_hook() {
    sb_theme_get_content('sb-theme-admin-advanced-setting-membership');
}
add_action('sb_theme_advanced_setting_membership_field', 'sb_theme_advanced_setting_membership_hook');

/*
 * Thêm trường cài đặt vào tab Membership trong bảng điều khiển nâng cao
 */
function sb_theme_advanced_setting_writing_hook() {
    sb_theme_get_content('sb-theme-admin-advanced-setting-writing');
}
add_action('sb_theme_advanced_setting_writing_field', 'sb_theme_advanced_setting_writing_hook');

/*
 * Thêm trường cài đặt vào tab Membership trong bảng điều khiển nâng cao
 */
function sb_theme_advanced_setting_general_hook() {
    sb_theme_get_content('sb-theme-admin-advanced-setting-general');
}
add_action('sb_theme_advanced_setting_general_field', 'sb_theme_advanced_setting_general_hook');

/*
 * Thêm trường cài đặt vào tab Social Login trong bảng điều khiển nâng cao
 */
function sb_theme_advanced_setting_social_login_hook() {
    sb_theme_get_content('sb-theme-admin-advanced-setting-social-login');
}
add_action('sb_theme_advanced_setting_social_login_field', 'sb_theme_advanced_setting_social_login_hook');

function sb_theme_checkout_setting_ngan_luong_hook() {
    sb_theme_get_content('sb-theme-admin-checkout-setting-ngan-luong');
}
add_action('sb_theme_checkout_setting_ngan_luong_field', 'sb_theme_checkout_setting_ngan_luong_hook');

/*
 * Thêm tab chức năng vào bảng cài đặt nâng cao
 */
function sb_theme_advanced_setting_tabs_filter($tabs) {
    if(SB_Option::social_login_enabled()) {
        $tabs['social_login'] = array(
            'name' => __('Đăng nhập mạng xã hội', 'sb-theme')
        );
    }
    return $tabs;
}
add_filter('sb_theme_advanced_setting_tabs', 'sb_theme_advanced_setting_tabs_filter');

/*
 * Kiểm tra thông tin cài đặt người dùng nhập trước khi lưu vào cơ sở dữ liệu
 */
function sb_theme_sanitize($input) {
	//$options = get_option('sb_options');
	//$input = wp_parse_args($input, $options);
    return $input;
}
add_filter('sb_options_sanitize', 'sb_theme_sanitize');