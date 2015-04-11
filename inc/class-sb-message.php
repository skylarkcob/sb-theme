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
}