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

function sb_theme_admin_sidebar_change_ajax_callback() {
    delete_transient(SB_Cache::build_admin_sidebar_tab_transient_name());
    die();
}
add_action('wp_ajax_sb_theme_admin_sidebar_change', 'sb_theme_admin_sidebar_change_ajax_callback');

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

/*
 * Chọn thông tin địa giới hành chính
 */
function sb_theme_administrative_boundaries_change_ajax_callback() {
    sb_theme_get_ajax('ajax-change-administrative-boundaries');
    die();
}
add_action('wp_ajax_sb_theme_administrative_boundaries_change', 'sb_theme_administrative_boundaries_change_ajax_callback');

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

function sb_theme_change_captcha_ajax_callback() {
    $len = isset( $_POST['len'] ) ? $_POST['len'] : 4;
    $args = array( 'len' => $len, 'force' => true );
    echo SB_Captcha::generate_image($args);
    die();
}
add_action( 'wp_ajax_sb_reload_captcha', 'sb_theme_change_captcha_ajax_callback' );
add_action( 'wp_ajax_nopriv_sb_reload_captcha', 'sb_theme_change_captcha_ajax_callback' );
add_action( 'wp_ajax_sb_theme_change_captcha', 'sb_theme_change_captcha_ajax_callback' );
add_action( 'wp_ajax_nopriv_sb_theme_change_captcha', 'sb_theme_change_captcha_ajax_callback' );

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

function sb_theme_test_smtp_mail_ajax_callback() {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $result = array(
        'successful' => false,
        'debug' => ''
    );
    if(empty($email)) {
        $result['message'] = __('Xin vui lòng nhập địa chỉ email người nhận.', 'sb-theme');
    } else {
        global $phpmailer;
        if ( !is_object( $phpmailer ) || !is_a( $phpmailer, 'PHPMailer' ) ) {
            require_once ABSPATH . WPINC . '/class-phpmailer.php';
            require_once ABSPATH . WPINC . '/class-smtp.php';
            $phpmailer = new PHPMailer( true );
        }
        $phpmailer->SMTPDebug = true;
        $to = $email;
        $subject = sprintf(__('Thử gửi mail bằng SMTP tới %s', 'sb-theme'), $to);
        $subject .= ' ' . SB_PHP::get_current_date_time();
        $message = sprintf(__('Đây là thư được gửi thử từ blog %s.', 'sb-theme'), get_bloginfo('name'));
        ob_start();
        $mail_result = SB_Mail::send_html($to, $subject, $message);
        $debug = ob_get_clean();
        if($mail_result) {
            $result['successful'] = true;
            $result['message'] = __('Gửi mail thành công.', 'sb-theme');
        } else {
            $result['message'] = __('Gửi mail thất bại.', 'sb-theme');
        }
        $pre = '<pre>';
        $pre_close = '</pre>';
        $result['debug'] = $pre . esc_html( $debug ) . $pre_close;
        unset($phpmailer);
    }
    echo json_encode($result);
    die();
}
add_action('wp_ajax_sb_theme_test_smtp_mail', 'sb_theme_test_smtp_mail_ajax_callback');

function sb_theme_login_social_ajax_callback() {
    $result = array(
        'successful' => false
    );
    $data_social = isset($_POST['data_social']) ? $_POST['data_social'] : '';
    $url = '';
    if(!empty($data_social)) {
        unset($_SESSION['access_token']);
        switch($data_social) {
            case 'facebook':
                $sb_login = sb_theme_get_social_login_facebook();
                $url = $sb_login->get_facebook_login_url();
                break;
            case 'google':
                $sb_login = sb_theme_get_social_login_google();
                $url = $sb_login->get_google_login_url();
                break;
            case 'gplus':
                $sb_login = sb_theme_get_social_login_google();
                $url = $sb_login->get_google_login_url();
                break;
        }
    }
    if(empty($url)) {
        $result['message'] = __('Đã có lỗi xảy ra, xin vui lòng kiểm tra lại.', 'sb-theme');
    }
    $result['url'] = $url;
    echo json_encode($result);
    die();
}
add_action('wp_ajax_nopriv_sb_theme_login_social', 'sb_theme_login_social_ajax_callback');

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

function sb_theme_post_widget_change_post_type_taxonomy_ajax_callback() {
    $post_type = isset($_POST['post_type']) ? $_POST['post_type'] : '';
    $result = array(
        'successful' => false,
        'html_data' => ''
    );
    if(!empty($post_type)) {
        $taxs = SB_Core::get_taxonomies_of_post_type($post_type);
        if($taxs && SB_PHP::is_array_has_value($taxs)) {
            $all_option = '';
            $show_count = isset($_POST['show_count']) ? (bool)$_POST['show_count'] : true;
            if(count($taxs) > 1) {
                foreach($taxs as $tax) {
                    $terms = get_terms($tax->name);
                    if(!SB_Core::is_error($terms) && count($terms) > 0) {
                        $tmp = '<optgroup label="' . $tax->labels->singular_name . '">';
                        foreach($terms as $cat) {
                            $option_text = $cat->name . (($show_count) ? ' (' . $cat->count . ')' : '');
                            $tmp .= SB_Field::get_option(array('value' => $cat->term_id, 'attributes' => array('data-taxonomy' => $tax->name), 'selected' => 0, 'text' => $option_text));
                        }
                        $tmp .= '</optgroup>';
                        $all_option .= $tmp;
                    }
                }
            } else {
                $tax = array_shift($taxs);
                $terms = get_terms($tax->name);
                if(!SB_Core::is_error($terms) && count($terms) > 0) {
                    foreach($terms as $cat) {
                        $option_text = $cat->name . (($show_count) ? ' (' . $cat->count . ')' : '');
                        $all_option .= SB_Field::get_option(array('value' => $cat->term_id, 'attributes' => array('data-taxonomy' => $tax->name), 'selected' => 0, 'text' => $option_text));
                    }
                }
            }
            $result['html_data'] = $all_option;
            $result['successful'] = true;
        }
    }
    echo json_encode($result);
    die();
}
add_action('wp_ajax_sb_theme_post_widget_change_post_type_taxonomy', 'sb_theme_post_widget_change_post_type_taxonomy_ajax_callback');

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

function sb_theme_signup_ajax_callback() {
    $result = array(
        'valid' => 0,
        'successful' => false
    );
    if(check_ajax_referer('sb-signup-page', 'security')) {
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $address = isset($_POST['address']) ? trim($_POST['address']) : '';
        $captcha = isset($_POST['captcha']) ? $_POST['captcha'] : '';
        $args = array(
            'email' => $email,
            'phone' => $phone,
            'name' => $name,
            'password' => $password,
            'address' => $address,
            'captcha' => $captcha,
            'insert' => true,
            'verify_email' => false
        );
        $result = sb_login_page_signup_ajax($args);
    } else {
        $result['message'] = __('Đã có lỗi xảy ra, xin vui lòng thử lại.', 'sb-theme');
    }
    echo json_encode($result);
    die();
}
add_action('wp_ajax_nopriv_sb_login_page_signup', 'sb_theme_signup_ajax_callback');
add_action('wp_ajax_nopriv_sb_theme_signup', 'sb_theme_signup_ajax_callback');

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

function sb_comment_like_ajax_callback() {
    $comment_id = isset($_POST['comment_id']) ? $_POST['comment_id'] : 0;
    $session_key = isset($_POST['session_key']) ? $_POST['session_key'] : 'comment_' . $comment_id . '_likes';
    if($comment_id > 0) {
        SB_Comment::update_likes($comment_id);
        SB_PHP::set_session($session_key, 1);
        echo 1;
    }
    die();
}
add_action('wp_ajax_sb_comment_like', 'sb_comment_like_ajax_callback');
add_action('wp_ajax_nopriv_sb_comment_like', 'sb_comment_like_ajax_callback');

function sb_comment_ajax_callback() {
    $comment_body = isset($_POST['comment_body']) ? $_POST['comment_body'] : '';
    $comment_name = isset($_POST['comment_name']) ? $_POST['comment_name'] : '';
    $comment_email = isset($_POST['comment_email']) ? $_POST['comment_email'] : '';
    $comment_url = isset($_POST['comment_url']) ? $_POST['comment_url'] : '';
    $comment_data = array();
    $comment_data['comment_content'] = $comment_body;
    $comment_data['comment_author_name'] = $comment_name;
    $comment_data['comment_author_url'] = $comment_url;
    if(SB_Comment::is_spam($comment_data)) {
        echo 1;
    } else {
        echo 0;
    }
    die();
}
add_action('wp_ajax_sb_comment', 'sb_comment_ajax_callback');
add_action('wp_ajax_nopriv_sb_comment', 'sb_comment_ajax_callback');