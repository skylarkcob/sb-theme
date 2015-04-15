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

    public static function load_nivo_slider() {
        wp_enqueue_style('nivo-slider-style', SB_THEME_LIB_URL . '/nivo-slider/nivo-slider.css');
        wp_enqueue_style('nivo-slider-theme-default-style', SB_THEME_LIB_URL . '/nivo-slider/themes/default/default.css');
        wp_enqueue_style('nivo-slider-theme-light-style', SB_THEME_LIB_URL . '/nivo-slider/themes/light/light.css');
        wp_enqueue_style('nivo-slider-theme-dark-style', SB_THEME_LIB_URL . '/nivo-slider/themes/dark/dark.css');
        wp_enqueue_style('nivo-slider-theme-bar-style', SB_THEME_LIB_URL . '/nivo-slider/themes/bar/bar.css');
        wp_enqueue_script('nivo-slider', SB_THEME_LIB_URL . '/nivo-slider/jquery.nivo.slider.min.js', array('jquery'), false, true);
    }
}