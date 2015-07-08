<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_User {
    public static function is_admin($user_id = 0) {
        if($user_id == 0) {
            return current_user_can('manage_options');
        }
        if($user_id < 1) {
            $user = self::get_current();
        } else {
            $user = get_user_by('id', $user_id);
        }
        if(is_a($user, 'WP_User')) {
            foreach($user->roles as $key => $value) {
                if('administrator' == $value || 'super_administrator' == $value) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function get_super_admin_define_ids_array() {
        $ids = (defined('SB_THEME_SUPER_ADMIN')) ? SB_THEME_SUPER_ADMIN : '';
        $ids = SB_PHP::string_to_array(',', $ids);
        $option_ids = SB_Option::get_theme_advanced_option_by_key(array('super_admin_ids'));
        $admin_ids = strval($option_ids);
        $admin_ids = SB_PHP::string_to_array(',', $admin_ids);
        $ids = wp_parse_args($admin_ids, $ids);
        return apply_filters('sb_theme_super_admin_ids', $ids);
    }

    public static function get_avatar_image($id_or_email, $size) {
        $image = get_avatar($id_or_email, $size);
        if(has_filter('get_avatar', 'sb_comment_get_avatar')) {
			$image_url = SB_PHP::get_first_image($image);
			if(!SB_PHP::is_image_url_exists($image_url)) {
				$image_url = 'http://www.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536';
				$image_url = add_query_arg(array('size' => $size), $image_url);
				$image = '<img src="' . $image_url . '" alt="">';
			}
        }
        return apply_filters('get_avatar', $image, $id_or_email, $size, '', '');
    }

    public static function save_profile_posted($user_id) {
        $gender = isset($_POST['gender']) ? absint($_POST['gender']) : 0;
        self::update_meta($user_id, 'gender', $gender);
        $birth_day = isset($_POST['user_birth_day']) ? $_POST['user_birth_day'] : date('d');
        $birth_month = isset($_POST['user_birth_month']) ? $_POST['user_birth_month'] : date('m');
        $birth_year = isset($_POST['user_birth_year']) ? $_POST['user_birth_year'] : date('Y');
        $birthday = $birth_year . '-' . $birth_month . '-' . $birth_day;
        $birthday = strtotime($birthday);
        self::update_meta($user_id, 'birthday', $birthday);
        $coin = isset($_POST['coin']) ? absint($_POST['coin']) : 0;
        self::update_meta($user_id, 'coin', $coin);
    }

    public static function get_coin($user_id) {
        $coin = absint(self::get_meta($user_id, 'coin'));
        return $coin;
    }

    public static function plus_coin($user_id, $coin) {
        $current_coin = self::get_coin($user_id);
        $current_coin += $coin;
        self::update_meta($user_id, 'coin', $current_coin);
        return $current_coin;
    }

    public static function minus_coin($user_id, $coin) {
        $current_coin = self::get_coin($user_id);
        $current_coin -= $coin;
        self::update_meta($user_id, 'coin', $current_coin);
        return $current_coin;
    }

    public static function add_more_profile_field($user) {
        $args = array(
            'title' => __('Thông tin mở rộng', 'sb-theme'),
            'class' => 'extra-profile-field sb-theme-user-extra-information',
            'callback' => array('SB_User', 'extra_profile_field'),
            'callback_params' => array($user)
        );
        self::extra_profile_field_group($args);
    }

    public static function get_author_meta($user_id, $meta_name) {
        return get_the_author_meta($meta_name, $user_id);
    }

    public static function extra_profile_field_group($args = array()) {
        $title = isset($args['title']) ? $args['title'] : '';
        $class = isset($args['class']) ? $args['class'] : '';
        $callback = isset($args['callback']) ? $args['callback'] : '';
        if(empty($title) || empty($callback) || (!is_array($callback) && !function_exists($callback)) || (is_array($callback) && !method_exists($callback[0], $callback[1]))) {
            return;
        }
        $class = SB_PHP::add_string_with_space_before($class, 'sb-theme-user-profile-group sbt-profile-row form-table');
        $callback_params = isset($args['callback_params']) ? (array)$args['callback_params'] : array();
        echo '<h3>' . $title . '</h3>';
        echo '<table class="' . $class . '">';
        call_user_func_array($callback, $callback_params);
        echo '</table>';
    }

    public static function extra_profile_field($user) {
        $user_id = $user->ID;
        $gender = self::get_meta($user_id, 'gender');
        $user_data = self::get_data($user_id);
        $coin = absint(self::get_meta($user_id, 'coin'));
        $args = array(
            'id' => 'coin',
            'label' => __('Coin', 'sb-theme'),
            'field_class' => 'width-small',
            'value' => $coin,
            'type' => 'number'
        );
        if(!current_user_can('update_core')) {
            $args['attributes'] = array(
                'readonly' => 'readonly',
                'disabled' => 'disabled'
            );
        }
        SB_Field::row_user_profile_field($args);

        $args = array(
            'name' => 'gender',
            'label' => __('Giới tính', 'sb-theme'),
            'value' => $gender,
            'field_type' => 'select_gender'
        );
        SB_Field::row_user_profile_field($args);

        $birthday = self::get_birthday_timestamp($user_id);
        $args = array(
            'name' => 'user_birth',
            'label' => __('Ngày sinh', 'sb-theme'),
            'value' => $birthday,
            'field_type' => 'select_birthday'
        );
        SB_Field::row_user_profile_field($args);
        if(current_user_can('update_core')) {
            $args = array(
                'name' => 'user_nicename',
                'label' => __('Tên slug', 'sb-theme'),
                'value' => $user_data->user_nicename,
                'field_type' => 'text',
                'field_class' => 'width-small',
                'attributes' => array(
                    'readonly' => 'readonly',
                    'disabled' => 'disabled'
                )
            );
            SB_Field::row_user_profile_field($args);

            $code = self::get_activation_code($user);
            $args = array(
                'name' => 'activation_code',
                'label' => __('Mã kích hoạt', 'sb-theme'),
                'value' => $code,
                'field_type' => 'text',
                'field_class' => 'width-medium',
                'attributes' => array(
                    'readonly' => 'readonly'
                )
            );
            SB_Field::row_user_profile_field($args);
        }
    }

    public static function get_avatar_url($id_or_email, $size) {
        $avatar = self::get_avatar_image($id_or_email, $size);
        return SB_PHP::get_first_image($avatar);
    }

    public static function exists($login_or_email) {
        if(email_exists($login_or_email) || username_exists($login_or_email)) {
            return true;
        }
        return false;
    }

    public static function get($args = array()) {
        return get_users($args);
    }

    public static function check_activation_code($user, $code) {
        $saved_code = self::get_activation_code($user);
        $result = true;
        if($saved_code != $code) {
            $result = false;
        }
        return $result;
    }

    public static function is($user) {
        return is_a($user, 'WP_User');
    }

    public static function send_lost_password_email($user) {
        $cookie = self::get_lost_password_email_cookie($user);
        if(1 == $cookie) {
            return;
        }
        $code = self::generate_activation_code($user);
        $reset_url = self::get_lost_password_verify_url($code);
        $reset_url = add_query_arg(array('user_id' => $user->ID), $reset_url);
        $subject = __('Thông tin khôi phục mật khẩu của bạn', 'sb-theme');
        $paragraph = new SB_HTML('p');
        $paragraph->set_text(sprintf(__('Chúng tôi nhận được thông tin yêu cầu lấy lại mật khẩu của bạn cho tài khoản %s.', 'sb-theme'), $user->user_login));
        $body = $paragraph->build();
        $paragraph->set_text(__('Nếu bạn là người đã gửi yêu cầu, xin vui lòng nhấn vào nút bên dưới để tiến hành đổi lại mật khẩu mới. Nếu bạn không phải là người đã gửi yêu cầu, bạn có thể bỏ qua hoặc xóa đi thư yêu cầu khổi phục mật khẩu này.', 'sb-theme'));
        $body .= $paragraph->build();
        $paragraph->set_attribute('style', 'display: block; clear: both; padding: 20px 0px;');
        $paragraph->set_text('<a target="_blank" style="white-space: nowrap; text-align: center; height: 28px; background: none repeat scroll 0% 0% rgb(77, 144, 254); border: 1px solid rgb(48, 121, 237); color: rgb(255, 255, 255); text-decoration: none; padding: 5px 30px; font-size: 13px; font-weight: 700;" href="' . $reset_url . '">'.__('Cập nhật lại mật khẩu của bạn', 'sb-theme').'</a>');
        $body .= $paragraph->build();
        $paragraph->set_attribute('style', 'padding: 0;');
        $paragraph->set_text(__('Hoặc bạn có thể sử dụng đường dẫn bên dưới:', 'sb-theme') . '<br><a target="_blank" style="border:none;color:#0084b4;text-decoration:none" href="' . $reset_url . '">' . $reset_url . '</a>');
        $body .= $paragraph->build();
        $paragraph->set_text(sprintf(__('Mã xác nhận: %s', 'sb-theme'), $code));
        $body .= $paragraph->build();
        $result = SB_Mail::send_html($user->user_email, $subject, $body);
        if($result) {
            self::set_lost_password_email_cookie($user);
        }
    }

    public static function send_signup_verify_email($user) {
        $cookie = self::get_verify_email_cookie($user);
        $session = self::get_verify_email_session($user);
        if(1 == $cookie || 1 == $session) {
            return;
        }
        $code = self::generate_activation_code($user);
        $url = self::get_account_verify_url($user->ID, $code);
        $subject = __('Xác nhận đăng ký tài khoản', 'sb-theme');
        $paragraph = new SB_HTML('p');
        $paragraph->set_text(sprintf(__('Cảm ơn bạn đã đăng ký với tên tài khoản của bạn là %s, hãy nhấn chuột vào nút bên dưới để xác thực địa chỉ email của bạn.', 'sb-theme'), $user->user_login));
        $body = $paragraph->build();
        $paragraph->set_attribute('style', 'display: block; clear: both; padding: 20px 0px;');
        $paragraph->set_text('<a target="_blank" style="white-space: nowrap; text-align: center; height: 28px; background: none repeat scroll 0% 0% rgb(77, 144, 254); border: 1px solid rgb(48, 121, 237); color: rgb(255, 255, 255); text-decoration: none; padding: 5px 30px; font-size: 13px; font-weight: 700;" href="' . $url . '">'.__('Xác nhận địa chỉ email', 'sb-theme').'</a>');
        $body .= $paragraph->build();
        $paragraph->set_attribute('style', 'padding: 0;');
        $paragraph->set_text(__('Hoặc bạn có thể sử dụng đường dẫn bên dưới:', 'sb-theme') . '<br><a target="_blank" style="border:none;color:#0084b4;text-decoration:none" href="' . $url . '">' . $url . '</a>');
        $body .= $paragraph->build();
        $paragraph->set_text(sprintf(__('Mã xác nhận: %s', 'sb-theme'), $code));
        $body .= $paragraph->build();
        $result = SB_Mail::send_html($user->user_email, $subject, $body);
        if($result) {
            self::set_verify_email_session($user);
        }
    }

    public static function get_lost_password_email_cookie($user) {
        $cookie = intval(SB_PHP::get_cookie('sb_lost_password_email_' . $user->ID));
        return $cookie;
    }

    public static function get_verify_email_cookie($user) {
        $cookie = intval(SB_PHP::get_cookie('sb_verify_email_' . $user->ID));
        return $cookie;
    }

    public static function set_lost_password_email_cookie($user) {
        SB_PHP::set_cookie_day('sb_lost_password_email_' . $user->ID, 1, 1);
    }

    public static function set_verify_email_cookie($user) {
        SB_PHP::set_cookie_day('sb_verify_email_' . $user->ID, 1, 1);
    }

    public static function set_verify_email_session($user) {
        SB_PHP::set_session('sb_verify_email_' . $user->ID, 1);
    }

    public static function get_verify_email_session($user) {
        return intval(SB_PHP::get_session('sb_verify_email_' . $user->ID));
    }

    public static function remove_lost_password_email_cookie($user) {
        SB_PHP::set_cookie_day('sb_lost_password_email_' . $user->ID, 1, 0);
    }

    public static function get_lost_password_verify_url($code = '') {
        $url = add_query_arg(array('action' => 'lostpassword', 'step' => 'verify'), self::get_lost_password_url());
        if(!empty($code)) {
            $url = add_query_arg(array('code' => $code), $url);
        }
        return $url;
    }

    public static function get_account_verify_url($user_id, $code = '') {
        $url = sb_login_page_get_page_account_url();
        $url = add_query_arg(array('action' => 'verify', 'user_id' => $user_id), $url);
        if(!empty($code)) {
            $url = add_query_arg(array('code' => $code), $url);
        }
        return $url;
    }

    public static function set_activation_expired($user) {
        $current_date_time = SB_Core::get_current_date_time('Y-m-d H:i:s');
        $next_date_time = strtotime('+1 day', strtotime($current_date_time));
        update_user_meta($user->ID, 'activation_expired', $next_date_time);
    }

    public static function get_activation_expired($user) {
        return get_user_meta($user->ID, 'activation_expired', true);
    }

    public static function is_activation_expired($user) {
        $expired = self::get_activation_expired($user);
        $current_date_time = SB_Core::get_current_date_time('Y-m-d H:i:s');
        $result = strtotime($current_date_time) - $expired;
        if($result >= 0) {
            return true;
        }
        return false;
    }

    public static function update_activation_code($user, $code = '') {
        global $wpdb;
        $wpdb->update($wpdb->users, array('user_activation_key' => $code), array('ID' => $user->ID));
        self::set_activation_expired($user);
    }

    public static function remove_activation_code($user) {
        global $wpdb;
        $wpdb->update($wpdb->users, array('user_activation_key' => ''), array('ID' => $user->ID));
    }

    public static function update_status($user, $status = 0) {
        global $wpdb;
        $wpdb->update($wpdb->users, array('user_status' => $status), array('ID' => $user->ID));
        $status_text = 'verified';
        switch($status) {
            case 6:
                $status_text = 'pending';
                break;
        }
        self::update_meta($user->ID, 'status', $status_text);
    }

    public static function force_login($user_id) {
        wp_set_auth_cookie($user_id, true);
    }

    public static function update_email($user_id, $new_email) {
        $data = array(
            'user_email' => $new_email
        );
        $next_update = SB_Core::get_current_datetime();
        $next_update = strtotime('+1 day', strtotime($next_update));
        update_user_meta($user_id, 'next_email_update', $next_update);
        return self::update($user_id, $data);
    }

    public static function update($user_or_id, $data) {
        if(!self::is($user_or_id) && is_numeric($user_or_id)) {
            $user_id = $user_or_id;
        } else {
            $user_id = $user_or_id->ID;
        }
        $data['ID'] = $user_id;
        return wp_update_user($data);
    }

    public static function get_activation_code($user) {
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare("SELECT ID, user_activation_key FROM $wpdb->users WHERE user_login = %s", $user->user_login));
        $code = '';
        if($row) {
            $code = $row->user_activation_key;
        }
        return $code;
    }

    public static function get_current_role($current_user = null) {
        if(self::is($current_user)) {
            $user = $current_user;
        } else {
            $user = self::get_current();
        }
        if(self::is($user)) {
            $roles = (array)$user->roles;
            return array_shift($roles);
        }
        return '';
    }

    public static function can($action) {
        return current_user_can($action);
    }

    public static function authenticate($login_name, $password) {
        return wp_authenticate($login_name, $password);
    }

    public static function generate_activation_code($user) {
        if(!self::is($user)) {
            return '';
        }
        $code = self::get_activation_code($user);
        if(empty($code) || self::is_activation_expired($user)) {
            $code = sha1($user->ID . mt_rand(10000, 99999) . $user->user_login . time() . $user->user_email);
            self::update_activation_code($user, $code);
        }
        return $code;
    }

    public static function count_logged_in_fail_session() {
        $logged_in_fail = intval(SB_PHP::get_session('sb_logged_in_fail'));
        return $logged_in_fail;
    }

    public static function update_logged_in_fail_session() {
        $cookie = self::get_logged_in_fail_cookie();
        $logged_in_fail = self::count_logged_in_fail_session();
        if(1 != $cookie && $logged_in_fail > 2) {
            $logged_in_fail = 0;
        }
        $logged_in_fail++;
        SB_PHP::set_session('sb_logged_in_fail', $logged_in_fail);
    }

    public static function update_logged_in_fail_cookie() {
        SB_PHP::set_cookie_minute('sb_logged_in_fail', 1, 10);
    }

    public static function get_logged_in_fail_cookie() {
        $logged_in_fail = intval(SB_PHP::get_cookie('sb_logged_in_fail'));
        return $logged_in_fail;
    }

    public static function login($username, $password, $remember = true) {
        $credentials = array();
        $credentials['user_login'] = $username;
        $credentials['user_password'] = $password;
        $credentials['remember'] = $remember;
        $user = wp_signon($credentials, false);
        if(is_wp_error($user)) {
            if(SB_PHP::is_email_valid($username)) {
                $new_user = self::get_by('email', $username);
                if(self::compare_user_password($new_user, $password)) {
                    $credentials['user_login'] = $new_user->user_login;
                    $credentials['user_password'] = $password;
                    $credentials['remember'] = $remember;
                    $user = wp_signon($credentials, false);
                }
            }
        }
        return $user;
    }

    public static function can_register() {
        return (bool)get_option('users_can_register');
    }

    public static function compare_user_password($user, $new_password) {
        if(is_wp_error($user) || !self::is($user)) {
            return false;
        }
        return self::check_password($new_password, $user->user_pass, $user->ID);
    }

    public static function check_password($new_password, $hash_password, $user_id) {
        return wp_check_password($new_password, $hash_password, $user_id);
    }

    public static function check_login_password($user, $password) {
        return self::check_password($password, $user->user_pass, $user->ID);
    }

    public static function get_by($field, $value) {
        return get_user_by($field, $value);
    }

    public static function get_by_email_or_login($email_or_login) {
        $user = self::get_by('login', $email_or_login);
        if(!self::is($user)) {
            $user = self::get_by('email', $email_or_login);
        }
        return $user;
    }

    public static function get_administrators($args = array()) {
        $args['role'] = 'administrator';
        return self::get($args);
    }

    public static function get_first_admin($args = array()) {
        $users = self::get_administrators($args);
        $user = new WP_User();
        foreach($users as $value) {
            $user = $value;
            break;
        }
        return $user;
    }

    public static function only_view_own_media($query) {
        global $current_user;
        if($current_user && !self::is_admin($current_user->ID)) {
            $query->set('author', $current_user->ID );
        }
        return $query;
    }

    public static function get_status($user_id) {
        $user_info = get_userdata($user_id);
        return intval($user_info->user_status);
    }

    public static function get_status_text($user_id) {
        return self::get_meta($user_id, 'status');
    }

    public static function is_awaiting_activation($user_id) {
        if(6 == self::get_status($user_id) || 'pending' == self::get_status_text($user_id)) {
            return true;
        }
        return false;
    }

    public static function add($args = array()) {
        $result = 0;
        $password = isset($args['password']) ? $args['password'] : '';
        $role = isset($args['role']) ? $args['role'] : '';
        $username = isset($args['username']) ? $args['username'] : '';
        $email = isset($args['email']) ? $args['email'] : '';
        if(!empty($password) && !empty($username) && !empty($email) && !username_exists($username) && !email_exists($email)) {
            $user_id = wp_create_user( $username, $password, $email );
            $user = get_user_by('id', $user_id);
            self::remove_all_role($user);
            if(empty($role)) {
                $role = get_option('default_role');
                if(empty($role)) {
                    $role = 'subscriber';
                }
            }
            $user->add_role($role);
            $result = $user_id;
        }
        return $result;
    }

    public static function add_admin($args = array()) {
        $args['role'] = 'administrator';
        self::add($args);
    }

    public static function remove_all_role($user) {
        foreach($user->roles as $role) {
            $user->remove_role($role);
        }
    }

    public static function set_password($user_id, $new_password) {
        wp_set_password($new_password, $user_id);
    }

    public static function change_password($username, $new_password) {
        $user = get_user_by('login', $username);
        if($user) {
            self::set_password($user->ID, $new_password);
        }
    }

    public static function update_password($username, $password) {
        self::change_password($username, $password);
    }

    public static function is_logged_in() {
        return is_user_logged_in();
    }

    public static function get_current() {
        return wp_get_current_user();
    }

    public static function get_data($user_id) {
        return get_userdata($user_id);
    }

    public static function get_meta($user_id, $meta_key) {
        return get_user_meta($user_id, $meta_key, true);
    }

    public static function update_meta($user_id, $meta_key, $meta_value) {
        if($user_id > 0) {
            update_user_meta($user_id, $meta_key, $meta_value);
        }
    }

    public static function get_following_stores($user_id) {
        $store_ids = self::get_favorite_stores($user_id);
        if(!is_array($store_ids) || count($store_ids) < 1) {
            $store_ids = self::get_meta($user_id, 'following-stores');
        }
        return $store_ids;
    }

    public static function get_favorite_stores($user_id) {
        $store_ids = self::get_meta($user_id, 'favorite_stores');
        $store_ids = SB_PHP::json_string_to_array($store_ids);
        $store_ids = array_filter($store_ids);
        return $store_ids;
    }

    public static function get_following_stores_array($user_id) {
        $stores = (array)self::get_following_stores($user_id);
        return $stores;
    }

    public static function update_following_stores($user_id, $store_id, $remove = false) {
        if($store_id > 0) {
            $stores = self::get_following_stores_array($user_id);
            if($remove) {
                $key = array_search($store_id, $stores);
                unset($stores[$key]);
            } else {
                if(!in_array($store_id, $stores)) {
                    array_push($stores, $store_id);
                }
            }
            self::update_meta($user_id, 'favorite_stores', $stores);
        }
    }

    public static function remove_following_store($user_id, $store_id) {
        self::update_following_stores($user_id, $store_id, true);
    }

    public static function unfollow_store($user_id, $store_id) {
        self::remove_following_store($user_id, $store_id);
    }

    public static function get_saving_coupons_array($user_id) {
        $coupons = self::get_meta($user_id, 'saving_coupons');
        if(!is_array($coupons) || count($coupons) < 1) {
            $coupons = self::get_meta($user_id, 'saving-coupons');
        }
        return (array)$coupons;
    }

    public static function update_saving_coupons($user_id, $coupon_id, $remove = false) {
        if($coupon_id > 0) {
            $coupons = self::get_saving_coupons_array($user_id);
            if($remove) {
                $key = array_search($coupon_id, $coupons);
                unset($coupons[$key]);
            } else {
                if(!in_array($coupon_id, $coupons)) {
                    array_push($coupons, $coupon_id);
                }
            }
            self::update_meta($user_id, 'saving_coupons', $coupons);
        }
    }

    public static function remove_saving_coupon($user_id, $coupon_id) {
        self::update_saving_coupons($user_id, $coupon_id, true);
    }

    public static function count_saving_coupon($user_id) {
        $coupons = self::get_saving_coupons_array($user_id);
        $count = count($coupons);
        return $count;
    }

    public static function count_following_store($user_id) {
        $stores = self::get_following_stores_array($user_id);
        $count = count($stores);
        return $count;
    }

    public static function get_login_url($redirect = '') {
        $url = '';
        if(function_exists('sb_login_page_get_page_login_url') && SB_Tool::use_custom_login()) {
            $url = sb_login_page_get_page_login_url();
            if(!empty($url) && !empty($redirect)) {
                $url = add_query_arg(array('redirect_to' => $redirect), $url);
            }
        }
        if(empty($url)) {
            $url = wp_login_url($redirect);
        }
        return $url;
    }

    public static function get_logout_url($redirect = '') {
        return wp_logout_url($redirect);
    }

    public static function get_birthday_timestamp($user_id) {
        $birthday = self::get_meta($user_id, 'birthday');
        if(empty($birthday)) {
            $birthday = SB_Core::get_current_datetime();
            $birthday = strtotime($birthday);
        }
        return $birthday;
    }

    public static function get_profile_url() {
        $url = '';
        if(function_exists('sb_login_page_get_page_account_url')) {
            $url = sb_login_page_get_page_account_url();
        }
        if(empty($url)) {
            $url = admin_url('profile.php');
        }
        return $url;
    }

    public static function get_lost_password_url($redirect = '') {
        $url = '';
        if(function_exists('sb_login_page_get_page_lost_password_url')) {
            $url = sb_login_page_get_page_lost_password_url();
        }
        if(empty($url)) {
            $url = wp_lostpassword_url($redirect);
        }
        return $url;
    }

    public static function check_social_login_data_send_back($social) {
        $social = trim($social);
        switch($social) {
            case 'facebook':
                sb_theme_social_login_facebook_check_data_back();
                break;
            case 'google':
                sb_theme_social_login_google_check_data_back();
                break;
        }
    }

    public static function add_transaction_ngan_luong($post_data, $secure_code, $meta = array()) {
        $post_id = 0;
        if(self::is_logged_in()) {
            if(!isset($post_data['post_author'])) {
                $user = self::get_current();
                $post_data['post_author'] = $user->ID;
            }
            $post_data['post_type'] = 'transaction';
            $post_id = SB_Post::insert($post_data);
            if($post_id > 0) {
                $transient_name = SB_Cache::build_transaction_secure_code_transient_name($secure_code);
                foreach($meta as $key => $value) {
                    SB_Post::update_sb_meta($post_id, $key, $value);
                }
                SB_Post::update_sb_meta($post_id, 'ngan_luong_secure_code', $secure_code);
                delete_transient($transient_name);
                $_SESSION[$transient_name] = $post_id;
            }
        }
        return $post_id;
    }

    public static function get_login_redirect() {
        $url = '';
        if(function_exists('sb_login_page_get_login_redirect_url')) {
            $url = sb_login_page_get_login_redirect_url();
        }
        return $url;
    }

    public static function get_logout_redirect() {
        $url = '';
        if(function_exists('sb_login_page_get_logout_redirect_url')) {
            $url = sb_login_page_get_logout_redirect_url();
        }
        return $url;
    }

    public static function get_signup_url() {
        $url = '';
        if(function_exists('sb_login_page_get_page_register_url')) {
            $url = sb_login_page_get_page_register_url();
        }
        if(empty($url)) {
            $url = wp_registration_url();
        }
        return $url;
    }
    
    public static function get_register_url() {
        return self::get_signup_url();
    }

    public static function go_to_login() {
        wp_redirect(self::get_login_url());
        die();
    }

    public static function is_following_store($user_id, $store_id) {
        if($user_id > 0 && $store_id > 0) {
            $stores = self::get_following_stores_array($user_id);
            if(in_array($store_id, $stores)) {
                return true;
            }
        }
        return false;
    }

    public static function is_current_following_store($store_id) {
        if($store_id > 0) {
            if(self::is_logged_in()) {
                $user = self::get_current();
                return self::is_following_store($user->ID, $store_id);
            }
        }
        return false;
    }

    public static function must_login() {
        if(!self::is_logged_in()) {
            self::go_to_login();
        }
    }

    public static function get_by_meta($meta_key, $args = array()) {
        $args['meta_key'] = $meta_key;
        return self::get($args);
    }

    public static function count_post($user_id, $post_type = 'post') {
        return count_user_posts($user_id, $post_type);
    }

    public static function count_all_post($user_id, $post_type = 'post') {
        $args = array(
            'post_type' => $post_type,
            'author' => $user_id,
            'status' => 'any',
            'posts_per_page' => -1,
            'transient_name' => SB_Cache::build_user_transient_name($user_id, '_count_post')
        );
        $query = SB_Query::get($args);
        return $query->post_count;
    }
}