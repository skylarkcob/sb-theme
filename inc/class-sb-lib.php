<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Lib {
    public static function load_iframe_resizer() {
        wp_register_script('iframe-resizer-window', SB_THEME_LIB_URL . '/iframe-resizer/iframeResizerWindow.min.js', array('jquery'), false, true);
        wp_register_script('iframe-resizer', SB_THEME_LIB_URL . '/iframe-resizer/iframeResizer.min.js', array('iframe-resizer-window'), false, true);
        wp_enqueue_script('iframe-resizer');
    }

    public static function load_jquery_datetime_picker() {
        sb_theme_load_jquery_datetimepicker();
    }

    public static function load_jquery_ui() {
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('wp-jquery-ui-dialog');
        wp_enqueue_style('jquery-ui-style', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
    }

    public static function load_fancybox() {
        wp_register_style('fancybox-style', SB_THEME_LIB_URL . '/fancybox/jquery.fancybox.min.css');
        wp_register_script('fancybox', SB_THEME_LIB_URL . '/fancybox/jquery.fancybox.min.js', array('jquery'), false, true);
        wp_enqueue_style('fancybox-style');
        wp_enqueue_script('fancybox');
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

    public static function load_jquery_marquee() {
        wp_enqueue_script('jquery-marquee', SB_THEME_LIB_URL . '/jquery-marquee/jquery.marquee.min.js', array('jquery'), false, true);
    }

    public static function load_jquery_simplyscroll() {
        wp_enqueue_style('jquery-simplyscroll-style', SB_THEME_LIB_URL . '/jquery-simplyscroll/jquery.simplyscroll.css');
        wp_enqueue_script('jquery-simplyscroll', SB_THEME_LIB_URL . '/jquery-simplyscroll/jquery.simplyscroll.min.js', array('jquery'), false, true);
    }

    public static function load_nivo_slider() {
        wp_register_style('nivo-slider-style', SB_THEME_LIB_URL . '/nivo-slider/nivo-slider.css');
        wp_register_style('nivo-slider-theme-default-style', SB_THEME_LIB_URL . '/nivo-slider/themes/default/default.css', array('nivo-slider-style'));
        wp_register_style('nivo-slider-theme-light-style', SB_THEME_LIB_URL . '/nivo-slider/themes/light/light.css', array('nivo-slider-style'));
        wp_register_style('nivo-slider-theme-dark-style', SB_THEME_LIB_URL . '/nivo-slider/themes/dark/dark.css', array('nivo-slider-style'));
        wp_register_style('nivo-slider-theme-bar-style', SB_THEME_LIB_URL . '/nivo-slider/themes/bar/bar.css', array('nivo-slider-style'));
        wp_register_script('nivo-slider', SB_THEME_LIB_URL . '/nivo-slider/jquery.nivo.slider.min.js', array('jquery'), false, true);
        $themes = apply_filters('sb_theme_nivo_slider_themes', array('default'));
        foreach($themes as $theme) {
            wp_enqueue_style('nivo-slider-theme-' . $theme . '-style');
        }
        wp_enqueue_script('nivo-slider');
    }

    public static function restore_link_title() {
        wp_register_script('wplinkTitle', SB_THEME_PLUGIN_URL . '/restore-link-title-field/restore-link-title-field.js', array('jquery', 'wplink'), false, true);
        wp_localize_script('wplinkTitle', 'wpLinkTitleL10n', array(
                'titleLabel' => __('Title', 'sb-theme'),
            )
        );
        wp_enqueue_script('wplinkTitle');
    }

    public static function load_bootstrap() {
        wp_register_style('bootstrap-style', 'https://maxcdn.bootstrapcdn.com/bootstrap/' . SB_THEME_BOOTSTRAP_VERSION . '/css/bootstrap.min.css');
        wp_enqueue_style('bootstrap-style');

        wp_register_script('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/' . SB_THEME_BOOTSTRAP_VERSION . '/js/bootstrap.min.js', array('jquery'), false, true);
        wp_enqueue_script('bootstrap');
    }

    public static function load_font_awesome() {
        wp_register_style('font-awesome-style', '//maxcdn.bootstrapcdn.com/font-awesome/' . SB_THEME_FONT_AWESOME_VERSION . '/css/font-awesome.min.css');
        wp_enqueue_style('font-awesome-style');
    }

	public static function load_bootstrap_and_font_awesome() {
		self::load_bootstrap();
		self::load_font_awesome();
	}

    public static function load_boostrap_chosen() {
        wp_enqueue_style('bootstrap-shosen-style', SB_THEME_LIB_URL . '/bootstrap-chosen/bootstrap-chosen.css');
        wp_enqueue_script('bower-chosen', SB_THEME_LIB_URL . '/bower-chosen/chosen.jquery.js', array('jquery'), false, true);
    }

    public static function load_bower_chosen() {
        wp_enqueue_style('bower-shosen-style', SB_THEME_LIB_URL . '/bower-chosen/chosen.css');
        wp_enqueue_script('bower-chosen', SB_THEME_LIB_URL . '/bower-chosen/chosen.jquery.js', array('jquery'), false, true);
    }
}