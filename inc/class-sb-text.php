<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Text {
    public static function get_add_to_cart() {
        $menu_base = 'store';
        $tab_base_option_name = 'text';

        $key = 'add_to_cart';
        $value = SB_Option::get_by_base_option_name($menu_base, array('keys' => array($tab_base_option_name, $key)));
        if(empty($value)) {
            $value = ('vi' == SB_Core::get_language()) ? 'Thêm vào giỏ' : __('Add to cart', 'sb-theme');
        }
        return apply_filters('sb_theme_add_to_cart_text', $value);
    }

    public static function get_view_cart() {
        $menu_base = 'store';
        $tab_base_option_name = 'text';
        $key = 'view_cart';
        $value = SB_Option::get_by_base_option_name($menu_base, array('keys' => array($tab_base_option_name, $key)));
        if(empty($value)) {
            $value = __('Xem giỏ hàng', 'sb-theme');
        }
        return apply_filters('sb_theme_view_cart_text', $value);
    }
}