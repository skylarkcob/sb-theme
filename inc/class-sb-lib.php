<?php
class SB_Lib {
    public static function load_iframe_resizer() {
        wp_register_script('iframe-resizer-window', SB_THEME_LIB_URL . '/iframe-resizer/iframeResizerWindow.min.js', array('jquery'), false, true);
        wp_enqueue_script('iframe-resizer', SB_THEME_LIB_URL . '/iframe-resizer/iframeResizer.min.js', array('iframe-resizer-window'), false, true);
    }

    public static function load_jquery_datetime_picker() {
        sb_theme_load_jquery_datetimepicker();
    }

    public static function load_owl_carousel() {
        sb_theme_load_owl_carousel();
    }

    public static function load_jquery_raty() {
        sb_theme_load_jquery_raty();
    }

    public static function load_zero_clipboard() {
        sb_theme_load_zeroclipboard();
    }
}