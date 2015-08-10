<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Message {
    public static function get_confirm_text() {
        $text = __('Are you sure?', 'sb-theme');
        return apply_filters('sb_theme_confirm_text', $text);
    }

    public static function get_confirm_delete_text() {
        $text = __('Are you sure you want to delete?', 'sb-theme');
        return apply_filters('sb_theme_confirm_delete_text', $text);
    }

    public static function get_redirecting_text() {
        $text = __('Redirecting', 'sb-theme');
        return apply_filters('sb_theme_redirecting_text', $text);
    }

    public static function get_redirecting_to_text() {
        $text = __('Redirecting to %s', 'sb-theme');
        return apply_filters('sb_teme_redirecting_to_text', $text);
    }

    public static function get_deactivate_sb_core_confirm_text() {
        return '';
    }

    public static function empty_captcha_error() {
        return new WP_Error('empty_captcha_code', __('Bạn chưa nhập mã bảo mật.', 'sb-theme'));
    }

    public static function invalid_captcha_error() {
        return new WP_Error('invalid_captcha_code', __('Mã bảo mật bạn nhập không đúng.', 'sb-theme'));
    }

	public static function get_browser_not_support_javascript() {
		$text = __('Options page will not work if your browser doesn\'t support javascript!', 'sb-theme');
		return apply_filters('sb_theme_browser_not_support_javascript_text', $text);
	}

	public static function get_changes_saved_successfully() {
		$text = __('Tất cả thiết lập của bạn đã được lưu thành công.', 'sb-theme');
		return apply_filters('sb_theme_all_changes_saved_successfully_text', $text);
	}

	public static function get_theme_no_name() {
		$text = __('No name theme', 'sb-theme');
		return apply_filters('sb_theme_theme_no_name_text', $text);
	}

	public static function get_all_rights_reserved() {
		$text = __('All Rights Reserved', 'sb-theme');
		return apply_filters('sb_theme_all_rights_reserved_text', $text);
	}

	public static function get_created_by() {
		$text = __('Created by %s.', 'sb-theme');
        if('vi' == SB_Core::get_language()) {
            $text = 'Được tạo bởi %s.';
        }
		return apply_filters('sb_theme_created_by_text', $text);
	}

	public static function get_ask_question_via_email() {
		$text = __('If you have any questions, please send mail to: %s', 'sb-theme');
        if('vi' == SB_Core::get_language()) {
            $text = 'Nếu bạn có bất kỳ câu hỏi nào, xin vui lòng gửi thư về địa chỉ: %s';
        }
		return apply_filters('sb_theme_ask_question_via_email_text', $text);
	}

	public static function get_save_changes() {
		$text = __('Save changes', 'sb-theme');
        if('vi' == SB_Core::get_language()) {
            $text = 'Lưu thiết lập';
        }
		return apply_filters('sb_theme_save_changes_text', $text);
	}

	public static function get_reset() {
		$text = __('Reset', 'sb-theme');
        if('vi' == SB_Core::get_language()) {
            $text = 'Khôi phục mặc định';
        }
		return apply_filters('sb_theme_reset_text', $text);
	}

	public static function get_error() {
		$text = __('Error', 'sb-theme');
		return apply_filters('sb_theme_error_text', $text);
	}

	public static function get_suspended() {
		$text = __( ' This website is temporarily unavailable, please try again later.', 'sb-theme' );
		return apply_filters('sb_theme_suspended_text', $text);
	}

	public static function get_password_strength() {
		$text = __('Độ mạnh mật khẩu', 'sb-theme');
		return apply_filters('sb_theme_password_strength_text', $text);
	}

	public static function get_password_strength_short() {
		$text = __('Rất yếu', 'sb-theme');
		return apply_filters('sb_theme_password_strength_short_text', $text);
	}

	public static function get_password_strength_bad() {
		$text = __('Yếu', 'sb-theme');
		return apply_filters('sb_theme_password_strength_bad_text', $text);
	}

	public static function get_password_strength_good() {
		$text = __('Trung bình', 'sb-theme');
		return apply_filters('sb_theme_password_strength_good_text', $text);
	}

	public static function get_password_strength_strong() {
		$text = __('Mạnh', 'sb-theme');
		return apply_filters('sb_theme_password_strength_strong_text', $text);
	}

	public static function get_password_strength_mismatch() {
		$text = __('Không khớp', 'sb-theme');
		return apply_filters('sb_theme_password_strength_mismatch_text', $text);
	}

    public static function get_category() {
        $text = __('Chuyên mục', 'sb-theme');
        $text = apply_filters('sb_theme_category_text', $text);
        return $text;
    }

    public static function get_comment() {
        $text = __('Bình luận', 'sb-theme');
        $text = apply_filters('sb_theme_comment_text', $text);
        return $text;
    }

    public static function get_posts_by() {
        $text = __('Bài viết bởi %s', 'sb-theme');
        $text = apply_filters('sb_theme_post_by_person_text', $text);
        return $text;
    }

    public static function admin_notice_error($text) {
        SB_Core::admin_notices_message(array('message' => $text, 'is_error' => true));
    }

    public static function admin_notice_warning($text) {
        SB_Core::admin_notices_message(array('message' => $text, 'type' => 'warning', 'label' => __('Chú ý:', 'sb-theme')));
    }

    public static function admin_notices_message($args = array()) {
        SB_Core::admin_notices_message($args);
    }

    public static function login_error_message($text) {
        return '<div id="login_error"> <strong>' . __('Lỗi', 'sb-theme') . '</strong>: ' . $text . '</div>';
    }

    public static function connect_google_error() {
        $message = apply_filters('sb_theme_connect_google_error_text', __('Không thể kết nối tới tài khoản Google của bạn!', 'sb-theme'));
        return $message;
    }

    public static function google_account_not_verify() {
        $message = apply_filters('sb_theme_google_account_not_verify_text', __('Tài khoản Google của bạn chưa được xác thực!', 'sb-theme'));
        return $message;
    }

    public static function connect_twitter_error() {
        $message = apply_filters('sb_theme_connect_twitter_error_text', __('Không thể kết nối tới tài khoản Twitter của bạn!', 'sb-theme'));
        return $message;
    }

    public static function twitter_account_not_verify() {
        $message = apply_filters('sb_theme_twitter_account_not_verify_text', __('Tài khoản Twitter của bạn chưa được xác thực!', 'sb-theme'));
        return $message;
    }

    public static function connect_facebook_error() {
        $message = apply_filters('sb_theme_connect_facebook_error_text', __('Không thể kết nối tới tài khoản Facebook của bạn!', 'sb-theme'));
        return $message;
    }

    public static function facebook_account_not_verify() {
        $message = apply_filters('sb_theme_facebook_account_not_verify_text', __('Tài khoản Facebook của bạn chưa được xác thực!', 'sb-theme'));
        return $message;
    }

    public static function email_exists_please_login() {
        $message = apply_filters('sb_theme_email_exists_please_login_text', __('Địa chỉ email đã tồn tài, xin vui lòng đăng nhập.'));
        return $message;
    }

    public static function warning_missing_really_simple_captcha_plugin() {
        if(SB_Tool::use_login_captcha() && !SB_Captcha::required_plugins_installed()) {
            $args = array(
                'message' => sprintf(__('Để chức năng captcha được hoạt động, bạn phải cài đặt và kích hoạt plugin %s.', 'sb-theme'), '<a target="_blank" href="https://wordpress.org/plugins/really-simple-captcha/">Really Simple CAPTCHA</a>'),
                'type' => 'warning'
            );
            self::admin_notices_message($args);
        }
    }
}