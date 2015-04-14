<?php
function sb_core_deactivate_ajax_callback() {
    echo SB_Message::get_deactivate_sb_core_confirm_text();
    die();
}
add_action( 'wp_ajax_sb_core_deactivate_message', 'sb_core_deactivate_ajax_callback' );

function sb_core_get_admin_url_callback() {
    $name = isset( $_POST['name'] ) ? $_POST['name'] : '';
    echo admin_url( $name );
    die();
}
add_action( 'wp_ajax_sb_core_get_admin_url', 'sb_core_get_admin_url_callback' );

function sb_plugins_ajax_callback() {
    sb_core_get_ajax( 'sb-plugins-ajax' );
    die();
}
add_action( 'wp_ajax_sb_plugins', 'sb_plugins_ajax_callback' );

function sb_option_reset_ajax_callback() {
    $sb_page = isset( $_POST['sb_option_page'] ) ? $_POST[ 'sb_option_page' ] : '';
    $data = array();
    switch ( $sb_page ) {
        case 'sb_paginate':
            $data = SB_Default_Setting::sb_paginate();
            break;
        default:
            break;
    }
    echo json_encode( $data );
    die();
}
add_action( 'wp_ajax_sb_option_reset', 'sb_option_reset_ajax_callback' );

function sb_add_ui_item_ajax_callback() {
    $type = isset( $_POST['data_type'] ) ? $_POST['data_type'] : '';
    switch ( $type ) {
        case 'rss_feed':
            sb_core_get_ajax( 'ajax-add-rss-feed' );
            break;
    }
    die();
}
add_action( 'wp_ajax_sb_add_ui_item', 'sb_add_ui_item_ajax_callback' );

function sb_ui_reset_ajax_callback() {
    $type = isset( $_POST['data_type'] ) ? $_POST['data_type'] : '';
    switch ( $type ) {
        case 'rss_feed':
            $options = SB_Option::get();
            unset( $options['theme']['rss_feed'] );
            SB_Option::update( $options );
            break;
    }
    die();
}
add_action( 'wp_ajax_sb_ui_reset', 'sb_ui_reset_ajax_callback' );

function sb_deactivate_all_sb_product_ajax_callback() {
    update_option( 'sb_core_activated', 0 );
    update_option( 'sb_core_deactivated_caller', 'user' );
    sb_switch_to_default_theme();
    sb_deactivate_all_sb_plugin();
    die();
}
add_action( 'wp_ajax_sb_deactivate_all_sb_product', 'sb_deactivate_all_sb_product_ajax_callback' );

function sb_deactivate_all_sb_plugin() {
    sb_theme_deactivate_all_sb_plugin();
}

function sb_switch_to_default_theme() {

}

function sb_core_captcha_reload_ajax_callback() {
    $len = isset( $_POST['len'] ) ? $_POST['len'] : 4;
    $args = array( 'len' => $len, 'force' => true );
    echo SB_Captcha::generate_image($args);
    die();
}
add_action( 'wp_ajax_sb_reload_captcha', 'sb_core_captcha_reload_ajax_callback' );
add_action( 'wp_ajax_nopriv_sb_reload_captcha', 'sb_core_captcha_reload_ajax_callback' );

function sb_login_page_login_ajax_callback() {
    check_ajax_referer('sb-theme', 'security');
    $login_email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $login_password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $remember = isset($_POST['remember']) ? (bool)$_POST['remember'] : true;
    $count_logged_in_fail = SB_User::count_logged_in_fail_session();
    $cookie = SB_User::get_logged_in_fail_cookie();
    $args = array(
        'email' => $login_email,
        'password' => $login_password,
        'remember' => $remember,
        'count_logged_in_fail' => $count_logged_in_fail,
        'cookie' => $cookie
    );
    $result = sb_login_page_login_ajax($args);
    echo json_encode($result);
    die();
}
add_action('wp_ajax_nopriv_sb_login_page_login', 'sb_login_page_login_ajax_callback');

function sb_login_page_verify_email_ajax_callback() {
    check_ajax_referer('sb-verify-email-page', 'security');
    $code = isset($_POST['code']) ? trim($_POST['code']) : '';
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $user = SB_User::get_by('id', $id);
    if(SB_User::is($user) && SB_User::check_activation_code($user, $code)) {
        SB_User::remove_activation_code($user);
        SB_User::update_status($user, 1);
        SB_User::force_login($user->ID);
        echo 1;
    } else {
        echo 0;
    }
    die();
}
add_action('wp_ajax_nopriv_sb_login_page_verify_email', 'sb_login_page_verify_email_ajax_callback');

function sb_login_page_lost_password_ajax_callback() {
    check_ajax_referer('sb-lost-password-page', 'security');
    $login_email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $redirect_url = SB_User::get_lost_password_verify_url();
    if(SB_PHP::is_email_valid($login_email)) {
        $user = SB_User::get_by('email', $login_email);
    } else {
        $user = SB_User::get_by('login', $login_email);
    }
    $result = array();
    if(is_wp_error($user) || !is_a($user, 'WP_User') || SB_User::is_awaiting_activation($user->ID)) {
        $result['user_id'] = -1;
    } else {
        SB_User::send_lost_password_email($user);
        $result['user_id'] = $user->ID;
        $redirect_url = add_query_arg(array('user_id' => $user->ID), $redirect_url);
    }
    $result['redirect'] = $redirect_url;
    echo json_encode($result);
    die();
}
add_action('wp_ajax_nopriv_sb_login_page_lost_password', 'sb_login_page_lost_password_ajax_callback');

function sb_login_page_verify_activation_code_ajax_callback() {
    check_ajax_referer('sb-lost-password-page', 'security');
    $code = isset($_POST['code']) ? trim($_POST['code']) : '';
    $user_id = isset($_POST['user_id']) ? trim($_POST['user_id']) : 0;
    $user = SB_User::get_by('id', $user_id);
    $redirect_url = SB_User::get_lost_password_verify_url($code);
    $result = array();
    if(is_wp_error($user) || !is_a($user, 'WP_User')) {
        $result['user_id'] = -1;
        $result['valid'] = false;
    } else {
        $result['user_id'] = $user->ID;
        $result['valid'] = false;
        if(SB_User::check_activation_code($user, $code)) {
            $result['valid'] = true;
            $redirect_url = add_query_arg(array('step' => 'reset', 'user_id' => $user_id), $redirect_url);
        }
    }
    $code = SB_User::check_activation_code($user, $code);
    $result['code'] = $code;
    $result['redirect'] = $redirect_url;
    echo json_encode($result);
    die();
}
add_action('wp_ajax_nopriv_sb_login_page_verify_activation_code', 'sb_login_page_verify_activation_code_ajax_callback');

function sb_login_page_reset_password_ajax_callback() {
    check_ajax_referer('sb-lost-password-page', 'security');
    $code = isset($_POST['code']) ? trim($_POST['code']) : '';
    $user_id = isset($_POST['user_id']) ? trim($_POST['user_id']) : 0;
    $user = SB_User::get_by('id', $user_id);
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $result = array();
    if(SB_User::is($user) && SB_User::check_activation_code($user, $code)) {
        SB_User::update_password($user->user_login, $password);
        $result['updated'] = true;
        $result['redirect'] = SB_User::get_login_url();
        SB_User::remove_lost_password_email_cookie($user);
    } else {
        $result['updated'] = false;
    }
    echo json_encode($result);
    die();
}
add_action('wp_ajax_nopriv_sb_login_page_reset_password', 'sb_login_page_reset_password_ajax_callback');

function sb_login_page_signup_ajax_callback() {
    check_ajax_referer('sb-signup-page', 'security');
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $args = array(
        'email' => $email,
        'phone' => $phone,
        'name' => $name,
        'password' => $password,
        'address' => $address
    );
    $result = sb_login_page_signup_ajax($args);
    echo json_encode($result);
    die();
}
add_action('wp_ajax_nopriv_sb_login_page_signup', 'sb_login_page_signup_ajax_callback');

function sb_login_page_change_email_ajax_callback() {
    SB_Core::check_ajax_referer();
    $user_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $result = array();
    if($user_id > 0) {
        $user = SB_User::get_by('id', $user_id);
        $result['email'] = $email;
        $email_exists = email_exists($email);
        $can_update = true;
        $next_update = SB_User::get_meta($user_id, 'next_email_update');
        $now_timestamp = strtotime(SB_Core::get_current_datetime());
        if(($now_timestamp - $next_update) < 0) {
            $can_update = false;
        }
        if(SB_PHP::is_email_valid($email) && !$email_exists && $can_update) {
            SB_User::update_email($user_id, $email);
            $result['updated'] = true;
        } else {
            $result['updated'] = false;
            if ($email_exists) {
                $result['message'] = __('Địa chỉ email của bạn đã có người sử dụng', 'sb-theme');
            } elseif(!$can_update) {
                $result['message'] = __('Bạn chỉ có thể thay đổi email được 1 lần trong vòng 24 giờ', 'sb-theme');
            } else {
                $result['message'] = __('Địa chỉ email của bạn nhập không đúng', 'sb-theme');
            }
        }
    }
    echo json_encode($result);
    die();
}
add_action('wp_ajax_sb_login_page_change_email', 'sb_login_page_change_email_ajax_callback');

function sb_login_page_change_password_ajax_callback() {
    SB_Core::check_ajax_referer();
    $user_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $result = array();
    $result['updated'] = false;
    if($user_id > 0) {
        $user = SB_User::get_by('id', $user_id);
        if(SB_User::is($user)) {
            $current_password = isset($_POST['current_password']) ? trim($_POST['current_password']): '';
            $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
            $re_new_password = isset($_POST['re_new_password']) ? trim($_POST['re_new_password']) : '';
            if(!SB_User::check_login_password($user, $current_password)) {
                $result['updated'] = false;
                $result['message'] = __('Mật khẩu của bạn nhập không đúng', 'sb-theme');
                $result['field'] = 'current_password';
            } elseif(empty($new_password)) {
                $result['updated'] = false;
                $result['message'] = __('Mật khẩu mới của bạn nhập không đúng', 'sb-theme');
                $result['field'] = 'new_password';
            } elseif($re_new_password != $new_password) {
                $result['updated'] = false;
                $result['message'] = __('Mật khẩu xác nhận phải giống với mục bên trên', 'sb-theme');
                $result['field'] = 're_new_password';
            } else {
                SB_User::update_password($user->user_login, $new_password);
                $result['updated'] = true;
            }
        }
    }
    echo json_encode($result);
    die();
}
add_action('wp_ajax_sb_login_page_change_password', 'sb_login_page_change_password_ajax_callback');

function sb_login_page_change_personal_info_ajax_callback() {
    $result = array();
    $result['update'] = true;
    $user_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if($user_id > 0) {
        $user = SB_User::get_by('id', $user_id);
        if(SB_User::is($user)) {
            $data = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
            $data = sanitize_text_field($data);
            $name_arr = explode(' ', $data);
            $first_name = array_pop($name_arr);
            $last_name = trim(implode(' ', $name_arr));
            $nice_name = SB_PHP::remove_vietnamese($data);
            $nice_name = str_replace(' ', '-', $nice_name);
            $user_data = array(
                'display_name' => $data,
                'first_name' => $first_name,
                'last_name' => $last_name
            );
            SB_User::update($user, $user_data);
            $data = isset($_POST['user_gender']) ? trim($_POST['user_gender']) : '';
            SB_User::update_meta($user_id, 'gender', $data);
            $birth_day = isset($_POST['user_birth_day']) ? $_POST['user_birth_day'] : date('d');
            $birth_month = isset($_POST['user_birth_month']) ? $_POST['user_birth_month'] : date('m');
            $birth_year = isset($_POST['user_birth_year']) ? $_POST['user_birth_year'] : date('Y');
            $birthday = $birth_year . '-' . $birth_month . '-' . $birth_day;
            $birthday = strtotime($birthday);
            SB_User::update_meta($user_id, 'birthday', $birthday);
            $data = isset($_POST['user_phone']) ? trim($_POST['user_phone']) : '';
            $data = sanitize_text_field($data);
            SB_User::update_meta($user_id, 'phone', $data);
            $data = isset($_POST['user_identity']) ? trim($_POST['user_identity']) : '';
            $data = sanitize_text_field($data);
            SB_User::update_meta($user_id, 'identity', $data);
            $data = isset($_POST['user_address']) ? trim($_POST['user_address']) : '';
            $data = sanitize_text_field($data);
            SB_User::update_meta($user_id, 'address', $data);
        }
    }
    echo json_encode($result);
    die();
}
add_action('wp_ajax_sb_login_page_change_personal_info', 'sb_login_page_change_personal_info_ajax_callback');