<?php
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

	public static function get_browser_not_support_javascript() {
		$text = __('Options page will not work if your browser doesn\'t support javascript!', 'sb-theme');
		return apply_filters('sb_theme_browser_not_support_javascript_text', $text);
	}

	public static function get_changes_saved_successfully() {
		$text = __('All your changes have been saved successfully.', 'sb-theme');
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
		return apply_filters('sb_theme_created_by_text', $text);
	}

	public static function get_ask_question_via_email() {
		$text = __('If you have any question, please send us an email via: %s', 'sb-theme');
		return apply_filters('sb_theme_ask_question_via_email_text', $text);
	}

	public static function get_save_changes() {
		$text = __('Save changes', 'sb-theme');
		return apply_filters('sb_theme_save_changes_text', $text);
	}

	public static function get_reset() {
		$text = __('Reset', 'sb-theme');
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
}