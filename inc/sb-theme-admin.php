<?php
defined('ABSPATH') or die('Please do not pip me!');

$sb_admin = new SB_Admin();

add_action( 'init', 'sb_theme_check_license' );

function is_sb_admin_page() {
    $result = SB_Admin_Custom::is_sb_page();
    return apply_filters( 'sb_admin_page', $result );
}

function sb_theme_menu() {
    $lang = SB_Core::get_language();
    $title = __('Theme settings', 'sb-theme');
    if('vi' == $lang) {
        $title = 'Cài đặt giao diện';
    }
    SB_Admin_Custom::add_submenu_page($title, 'sb_theme', array('SB_Admin_Custom', 'setting_page_callback'));
    $title = __('Utilities', 'sb-theme');
    if('vi' == $lang) {
        $title = 'Tiện ích';
    }
    SB_Admin_Custom::add_submenu_page($title, 'sb_utilities', array('SB_Admin_Custom', 'setting_page_callback'));
	if(SB_Theme::support('sb_statistics')) {
        $title = __('Statistics', 'sb-theme');
        if('vi' == $lang) {
            $title = 'Thống kê';
        }
        SB_Admin_Custom::add_submenu_page($title, 'sb_statistics', array('SB_Admin_Custom', 'setting_page_callback'));
    }
    $title = __('Advance setting', 'sb-theme');
    if('vi' == $lang) {
        $title = 'Cài đặt nâng cao';
    }
    SB_Admin_Custom::add_submenu_page($title, 'sbt_advanced', array('SB_Admin_Custom', 'setting_page_callback'));
    SB_Admin_Custom::add_submenu_page('SMTP Email', 'sbt_smtp_email', array('SB_Admin_Custom', 'setting_page_callback'));
    if(SB_Theme::support('sb_checkout')) {
        $title = __('Checkout', 'sb-theme');
        if('vi' == $lang) {
            $title = 'Thanh toán';
        }
        SB_Admin_Custom::add_submenu_page($title, 'sbt_checkout', array('SB_Admin_Custom', 'setting_page_callback'));
    }
    if(SB_Theme::support('woocommerce')) {
        $title = __('Store', 'sb-theme');
        if('vi' == $lang) {
            $title = 'Cửa hàng';
        }
        SB_Admin_Custom::add_submenu_page($title, 'sbt_store', array('SB_Admin_Custom', 'setting_page_callback'));
    }
	if(SB_Tool::use_vchat()) {
		SB_Admin_Custom::add_submenu_page(__('vChat', 'sb-theme'), 'sbt_vchat', array('SB_Admin_Custom', 'vchat_setting_page_callback'));
	}
    $title = __('Socials', 'sb-theme');
    if('vi' == $lang) {
        $title = 'Mạng xã hội';
    }
    SB_Admin_Custom::add_submenu_page($title, 'sbt_socials', array('SB_Admin_Custom', 'socials_setting_page_callback'));
    $title = ('vi' == $lang) ? 'Bản quyền giao diện' : __('Theme license', 'sb-theme');
    SB_Admin_Custom::add_submenu_page($title, 'sbt_theme_license', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_theme_menu');

function sb_theme_setting_tab($tabs) {
    $lang = SB_Core::get_language();
    $title = __('Theme settings', 'sb-theme');
    if('vi' == $lang) {
        $title = 'Cài đặt giao diện';
    }
    $tabs['sb_theme'] = array('title' => $title, 'section_id' => 'sb_theme_setting_section', 'type' => 'theme');
    $title = __('Utilities', 'sb-theme');
    if('vi' == $lang) {
        $title = 'Tiện ích';
    }
    $tabs['sb_utilities'] = array('title' => $title, 'section_id' => 'sb_utilities_section', 'type' => 'theme');
	if(SB_Theme::support('sb_statistics')) {
        $title = __('Statistics', 'sb-theme');
        if('vi' == $lang) {
            $title = 'Thống kê';
        }
        $tabs['sb_statistics'] = array('title' => $title, 'section_id' => 'sb_statistics_section', 'type' => 'theme');
    }
    $title = __('Advance setting', 'sb-theme');
    if('vi' == $lang) {
        $title = 'Cài đặt nâng cao';
    }
    $tabs['sbt_advanced'] = array('title' => $title, 'section_id' => 'sb_theme_advanced_setting_section', 'type' => 'theme');
    $tabs['sbt_smtp_email'] = array('title' => __('SMTP Email', 'sb-theme'), 'section_id' => 'sb_theme_smtp_email_setting_section', 'type' => 'theme');
    if(SB_Theme::support('sb_checkout')) {
        $title = __('Checkout', 'sb-theme');
        if('vi' == $lang) {
            $title = 'Thanh toán';
        }
        $tabs['sbt_checkout'] = array('title' => $title, 'section_id' => 'sb_theme_checkout_setting_section', 'type' => 'theme');
    }
    if(SB_Theme::support('woocommerce')) {
        $title = __('Store', 'sb-theme');
        if('vi' == $lang) {
            $title = 'Cửa hàng';
        }
        $tabs['sbt_store'] = array('title' => $title, 'section_id' => 'sb_theme_store_setting_section', 'type' => 'theme');
    }
    $tabs['sbt_theme_license'] = array(
        'title' => ('vi' == $lang) ? 'Bản quyền giao diện' : __('Theme license', 'sb-theme'),
        'section_id' => 'sb_theme_license_setting_section',
        'type' => 'license'
    );
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_theme_setting_tab');

function sb_theme_advanced_setting_field() {
    $title = __('Advance settings page', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $title = 'Trang cài đặt các tùy chọn nâng cao';
    }
    SB_Admin_Custom::add_section('sb_theme_advanced_setting_section', $title, 'sbt_advanced');
    SB_Admin_Custom::add_setting_field('sb_theme_advanced_setting_page_content', '', 'sb_theme_advanced_setting_section', array('SB_Admin_Custom', 'row_setting_page_callback'), 'sbt_advanced');
}
add_action('sb_theme_option_page_init', 'sb_theme_advanced_setting_field');

function sb_theme_store_setting_field() {
    $title = __('Store settings page', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $title = 'Trang cài đặt các tùy chọn cho cửa hàng';
    }
    SB_Admin_Custom::add_section('sb_theme_store_setting_section', $title, 'sbt_store');
    SB_Admin_Custom::add_setting_field('sb_theme_store_setting_page_content', '', 'sb_theme_store_setting_section', array('SB_Admin_Custom', 'store_row_setting_page_callback'), 'sbt_store');
}
add_action('sb_theme_option_page_init', 'sb_theme_store_setting_field');

function sb_theme_checkout_setting_field() {
    $title = __('Checkout settings page', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $title = 'Trang cài đặt các tùy chọn cho thanh toán';
    }
    SB_Admin_Custom::add_section('sb_theme_checkout_setting_section', $title, 'sbt_checkout');
    SB_Admin_Custom::add_setting_field('sb_theme_checkout_setting_page_content', '', 'sb_theme_checkout_setting_section', array('SB_Admin_Custom', 'checkout_setting_page_callback'), 'sbt_checkout');
}
add_action('sb_theme_option_page_init', 'sb_theme_checkout_setting_field');

function sb_theme_checkout_setting_ngan_luong_hook() {
    sb_theme_get_content('sb-theme-admin-checkout-setting-ngan-luong');
}
add_action('sb_theme_checkout_setting_ngan_luong_field', 'sb_theme_checkout_setting_ngan_luong_hook');

function sb_theme_checkout_setting_mals_e_hook() {
    SB_Theme::get_content('sb-theme-admin-checkout-setting-mals-e');
}
add_action('sb_theme_checkout_setting_mals_e_field', 'sb_theme_checkout_setting_mals_e_hook');

function sb_theme_license_setting_field() {
    $title = __('Theme license settings page', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $title = 'Cài đặt bản quyền cho giao diện';
    }
    SB_Admin_Custom::add_section('sb_theme_license_setting_section', $title, 'sbt_theme_license');
    $title = __('License text', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $title = 'Mã kích hoạt';
    }
    SB_Admin_Custom::add_setting_field('sb_theme_license_text', $title, 'sb_theme_license_setting_section', 'sb_theme_license_text_callback', 'sbt_theme_license');
}
add_action('sb_theme_option_page_init', 'sb_theme_license_setting_field');

function sb_theme_license_text_callback() {
    $key = 'theme_license';
    $value = SB_Option::get_option_by_key(array($key));
    if(empty($value) && defined('SB_THEME_LICENSE_KEY')) {
        $value = SB_THEME_LICENSE_KEY;
    }
    $args = array(
        'id' => 'sb_theme_license_key',
        'name' => SB_Option::build_sb_option_name(array($key)),
        'value' => $value,
        'description' => ('vi' == SB_Core::get_language()) ? 'Mã kích hoạt được cung cấp bởi SB Team, mã này thông thường sẽ có 34 ký tự.' : __('Activation code provided by SB Team, this code will typically have 34 characters.', 'sb-theme'),
        'field_class' => 'width-medium',
        'autocomplete' => false
    );
    SB_Field::text($args);
}

function sb_theme_smtp_email_setting_field() {
    $title = __('SMTP Email settings page', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $title = 'Trang cài đặt các tùy chọn cho SMTP Email';
    }
    SB_Admin_Custom::add_section('sb_theme_smtp_email_setting_section', $title, 'sbt_smtp_email');
    $title = __('Send SMTP email', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $title = 'Gửi mail bằng SMTP';
    }
    sb_theme_add_smtp_email_setting_field('sb_theme_smtp_email_enabled', $title, 'sb_theme_smpt_email_enabled_callback');
    if(SB_Tool::use_smtp_email()) {
        $title = __('Sender name', 'sb-theme');
        if('vi' == SB_Core::get_language()) {
            $title = 'Tên người gửi';
        }
        sb_theme_add_smtp_email_setting_field('sb_theme_smtp_email_from_name', $title, 'sb_theme_smpt_email_from_name_callback');
        $title = __('Sender email', 'sb-theme');
        if('vi' == SB_Core::get_language()) {
            $title = 'Email người gửi';
        }
        sb_theme_add_smtp_email_setting_field('sb_theme_smtp_email_from_email', $title, 'sb_theme_smpt_email_from_email_callback');
        $title = __('SMTP information', 'sb-theme');
        if('vi' == SB_Core::get_language()) {
            $title = 'Thông tin SMTP';
        }
        sb_theme_add_smtp_email_setting_field('sb_theme_smtp_email_info', $title, 'sb_theme_smpt_email_info_callback');
        $title = __('Encryption type', 'sb-theme');
        if('vi' == SB_Core::get_language()) {
            $title = 'Kiểu mã hóa';
        }
        sb_theme_add_smtp_email_setting_field('sb_theme_smtp_email_encryption', $title, 'sb_theme_smpt_email_encryption_callback');
        $title = __('Username', 'sb-theme');
        if('vi' == SB_Core::get_language()) {
            $title = 'Tài khoản';
        }
        sb_theme_add_smtp_email_setting_field('sb_theme_smtp_email_username', $title, 'sb_theme_smpt_email_username_callback');
        $title = __('Password', 'sb-theme');
        if('vi' == SB_Core::get_language()) {
            $title = 'Mật khẩu';
        }
        sb_theme_add_smtp_email_setting_field('sb_theme_smtp_email_password', $title, 'sb_theme_smpt_email_password_callback');
        $title = __('Send test mail', 'sb-theme');
        if('vi' == SB_Core::get_language()) {
            $title = 'Thử gửi mail';
        }
        sb_theme_add_smtp_email_setting_field('sb_theme_smtp_email_test', $title, 'sb_theme_smpt_email_test_callback');
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
        'description' => ('vi' == SB_Core::get_language()) ? 'Nhập địa chỉ email và thử gửi mail với thiết lập của bạn bên trên.' : __('Enter your email address and try to send mail with these settings above.', 'sb-theme'),
        'field_class' => 'width-medium test-field',
        'type' => 'email',
        'placeholder' => ('vi' == SB_Core::get_language()) ? 'emailcuaban@gmail.com' : 'youremail@gmail.com',
        'button' => true,
        'autocomplete' => false,
        'button_args' => array(
            'text' => ('vi' == SB_Core::get_language()) ? 'Gửi' : __('Send', 'sb-theme'),
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
        'description' => ('vi' == SB_Core::get_language()) ? 'Mật khẩu đăng nhập cho tài khoản của bạn.' : __('Password to login your account.', 'sb-theme'),
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
        'description' => ('vi' == SB_Core::get_language()) ? 'Tài khoản đăng nhập vào SMTP server.' : __('The account for login to SMTP server.', 'sb-theme'),
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
        'label' => ('vi' == SB_Core::get_language()) ? 'Không mã hóa' : __('None', 'sb-theme'),
        'option_value' => 'none'
    );
    SB_Field::text($args);

    $args = array(
        'id' => 'sb_theme_smtp_email_' . $key,
        'name' => SB_Option::build_sb_option_name(array('smtp_email', $key)),
        'value' => $value,
        'field_class' => 'width-medium',
        'type' => 'radio',
        'label' => ('vi' == SB_Core::get_language()) ? 'Mã hóa SSL' : __('SSL', 'sb-theme'),
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
        'label' => ('vi' == SB_Core::get_language()) ? 'Mã hóa TLS' : __('TLS', 'sb-theme'),
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
        'description' => ('vi' == SB_Core::get_language()) ? 'Địa chỉ SMTP server, ví dụ: smtp.gmail.com.' : __('SMTP server address, e.g. smtp.gmail.com', 'sb-theme'),
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
        'description' => ('vi' == SB_Core::get_language()) ? 'Port sử dụng gửi mail bằng SMTP.' : __('The SMTP port you are using.', 'sb-theme'),
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
        'description' => ('vi' == SB_Core::get_language()) ? 'Kích hoạt chức năng gửi mail thông qua SMTP.' : __('Activate sending email via SMTP.', 'sb-theme'),
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
        'description' => ('vi' == SB_Core::get_language()) ? 'Thiết lập địa chỉ email của người gửi mail, nếu để trống thì địa chỉ email quản trị blog sẽ được sử dụng.' : __('The sender email address, if empty the blog administrative email will be used.', 'sb-theme'),
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
        'description' => ('vi' == SB_Core::get_language()) ? 'Thiết lập tên người gửi mail, nếu để trống thì tên của blog sẽ được sử dụng.' : __('The sender name, if empty the blog name will be used.', 'sb-theme'),
        'field_class' => 'width-medium'
    );
    SB_Field::text($args);
}

function sb_theme_setting_field() {
    $lang = SB_Core::get_language();
    $title = __('Theme settings page', 'sb-theme');
    if('vi' == $lang) {
        $title = 'Trang cài đặt tùy chọn cho giao diện';
    }
    SB_Admin_Custom::add_section('sb_theme_setting_section', $title, 'sb_theme');
    if('vi' == $lang) {
        SB_Admin_Custom::add_setting_field('sb_theme_default_language', 'Ngôn ngữ', 'sb_theme_setting_section', 'sb_theme_default_language_callback', 'sb_theme');
    }
    SB_Admin_Custom::add_setting_field('sb_theme_logo', 'Logo', 'sb_theme_setting_section', 'sb_theme_logo_callback', 'sb_theme');
    SB_Admin_Custom::add_setting_field('sb_theme_favicon', 'Favicon', 'sb_theme_setting_section', 'sb_theme_favicon_callback', 'sb_theme');
    if(SB_Tool::use_utility_add_to_head()) {
        sb_theme_setting_field_add_to_head();
    }
    if(SB_Tool::use_utility_default_thumbnail()) {
        sb_theme_setting_field_no_thumbnail();
    }
    if(SB_Tool::use_utility_google_analytics()) {
        sb_theme_setting_field_google_analtyics();
    }
    if(SB_Tool::use_utility_category_widget()) {
        sb_theme_setting_field_category_widget();
    }
    if(SB_Tool::use_utility_facebook_php_sdk()) {
        sb_theme_setting_field_facebook_api();
    }
    if(SB_Tool::use_utility_sharethis()) {
        sb_theme_setting_field_sharethis();
    }
    if(SB_Tool::use_utility_addthis() || sb_theme_support_addthis()) {
        sb_theme_setting_field_addthis();
    }
    if(SB_Tool::use_utility_facebook_fanpage()) {
        sb_theme_setting_field_facebook_fanpage();
    }
    do_action('sb_theme_setting_field');
    do_action('sb_theme_setting_page');
    if(SB_Tool::use_utility_social_list()) {
        sb_theme_setting_field_social();
    }
    if(SB_Tool::use_utility_footer_text()) {
        sb_theme_setting_field_footer_text();
    }
}
add_action('sb_admin_init', 'sb_theme_setting_field');

function sb_utilities_setting_field() {
    $text = __('Utilities settings page', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $text = 'Trang cài đặt tùy chọn cho các tiện ích';
    }
    SB_Admin_Custom::add_section('sb_utilities_section', $text, 'sb_utilities');
    do_action('sb_utilities_setting_field');
}
add_action('sb_admin_init', 'sb_utilities_setting_field');

function sb_statistics_setting_field() {
    $text = __('Statistics settings page', 'sb-theme');
    if('vi' == SB_Core::get_language()) {
        $text = 'Trang cài đặt tùy chọn cho thống kê';
    }
    SB_Admin_Custom::add_section('sb_statistics_section', $text, 'sb_statistics');
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
        'background' => ('vi' == SB_Core::get_language()) ? 'Hình nền' : __('Background', 'sb-theme'),
        'image' => ('vi' == SB_Core::get_language()) ? 'Hình ảnh' : __('Image', 'sb-theme'),
        'text' => ('vi' == SB_Core::get_language()) ? 'Chữ' : __('Text', 'sb-theme'),
        'flash' => __('Flash', 'sb-theme')
    );
    $list_options = apply_filters('sb_theme_logo_display_types', $list_options);
    $args = array(
        'id' => 'sb_theme_logo_type',
        'name' => 'sb_options[theme][logo_type]',
        'value' => $value,
        'list_options' => $list_options,
        'container_class' => 'margin-bottom',
        'field_class' => 'logo-type',
        'description' => ('vi' == SB_Core::get_language()) ? 'Lựa chọn kiểu hiển thị cho logo.' : __('Choose the way you want logo displayed.', 'sb-theme')
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

/*
 * Thêm tab chức năng vào bảng cài đặt nâng cao
 */
function sb_theme_advanced_setting_tabs_filter($tabs) {
    if(SB_Option::social_login_enabled()) {
        $tabs['social_login'] = array(
            'name' => ('vi' == SB_Core::get_language()) ? 'Đăng nhập mạng xã hội' : __('Social login', 'sb-theme')
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