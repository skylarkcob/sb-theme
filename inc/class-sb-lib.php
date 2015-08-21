<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Lib {
    public static function load_iframe_resizer() {
        wp_register_script('iframe_resizer_window', SB_THEME_LIB_URL . '/iframe_resizer/iframeResizerWindow.min.js', array('jquery'), false, true);
        wp_register_script('iframe_resizer', SB_THEME_LIB_URL . '/iframe_resizer/iframeResizer.min.js', array('iframe_resizer_window'), false, true);
        wp_enqueue_script('iframe_resizer');
    }

    public static function load_lazyload() {
        wp_register_script('lazyload', SB_THEME_LIB_URL . '/jquery_lazyload/jquery.lazyload.min.js', array('jquery'), false, true);
        wp_enqueue_script('lazyload');
    }

    public static function load_jquery_datetime_picker() {
        sb_theme_load_jquery_datetimepicker();
    }

    public static function load_jquery_ui() {
        wp_enqueue_script('jquery_ui_core');
        wp_enqueue_script('jquery_ui_datepicker');
        wp_enqueue_style('wp_jquery_ui_dialog');
        wp_enqueue_style('jquery_ui_style', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery_ui.css');
    }

    public static function load_fancybox() {
        wp_register_style('fancybox_style', SB_THEME_LIB_URL . '/fancybox/jquery.fancybox.min.css');
        wp_register_script('fancybox', SB_THEME_LIB_URL . '/fancybox/jquery.fancybox.min.js', array('jquery'), false, true);
        wp_enqueue_style('fancybox_style');
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
        wp_enqueue_script('jquery_marquee', SB_THEME_LIB_URL . '/jquery_marquee/jquery.marquee.min.js', array('jquery'), false, true);
    }

    public static function load_jquery_simplyscroll() {
        wp_enqueue_style('jquery_simplyscroll_style', SB_THEME_LIB_URL . '/jquery_simplyscroll/jquery.simplyscroll.css');
        wp_enqueue_script('jquery_simplyscroll', SB_THEME_LIB_URL . '/jquery_simplyscroll/jquery.simplyscroll.min.js', array('jquery'), false, true);
    }

    public static function load_nivo_slider() {
        wp_register_style('nivo_slider_style', SB_THEME_LIB_URL . '/nivo_slider/nivo_slider.css');
        wp_register_style('nivo_slider_theme_default_style', SB_THEME_LIB_URL . '/nivo_slider/themes/default/default.css', array('nivo_slider_style'));
        wp_register_style('nivo_slider_theme_light_style', SB_THEME_LIB_URL . '/nivo_slider/themes/light/light.css', array('nivo_slider_style'));
        wp_register_style('nivo_slider_theme_dark_style', SB_THEME_LIB_URL . '/nivo_slider/themes/dark/dark.css', array('nivo_slider_style'));
        wp_register_style('nivo_slider_theme_bar_style', SB_THEME_LIB_URL . '/nivo_slider/themes/bar/bar.css', array('nivo_slider_style'));
        wp_register_script('nivo_slider', SB_THEME_LIB_URL . '/nivo_slider/jquery.nivo.slider.min.js', array('jquery'), false, true);
        $themes = apply_filters('sb_theme_nivo_slider_themes', array('default'));
        foreach($themes as $theme) {
            wp_enqueue_style('nivo_slider_theme_' . $theme . '_style');
        }
        wp_enqueue_script('nivo_slider');
    }

    public static function load_flex_slider() {
        wp_register_style('flexslider_style', SB_THEME_LIB_URL . '/flexslider/flexslider.min.css');
        wp_register_script('flexslider', SB_THEME_LIB_URL . '/flexslider/jquery.flexslider_min.js', array('jquery'), false, true);
        wp_enqueue_style('flexslider_style');
        wp_enqueue_script('flexslider');
    }

    public static function restore_link_title() {
        wp_register_script('wplinkTitle', SB_THEME_PLUGIN_URL . '/restore_link_title_field/restore_link_title_field.js', array('jquery', 'wplink'), false, true);
        wp_localize_script('wplinkTitle', 'wpLinkTitleL10n', array(
                'titleLabel' => __('Title', 'sb_theme'),
            )
        );
        wp_enqueue_script('wplinkTitle');
    }

    public static function load_bootstrap() {
        wp_register_style('bootstrap_style', SB_THEME_LIB_URL . '/bootstrap/css/bootstrap.min.css');
        wp_enqueue_style('bootstrap_style');

        wp_register_script('bootstrap', SB_THEME_LIB_URL . '/bootstrap/js/bootstrap.min.js', array('jquery'), false, true);
        wp_enqueue_script('bootstrap');
    }

    public static function load_font_awesome() {
        wp_register_style('font_awesome_style', SB_THEME_LIB_URL . '/font-awesome/css/font-awesome.min.css');
        wp_enqueue_style('font_awesome_style');
    }

	public static function load_bootstrap_and_font_awesome() {
		self::load_bootstrap();
		self::load_font_awesome();
	}

    public static function load_boostrap_chosen() {
        wp_enqueue_style('bootstrap_shosen_style', SB_THEME_LIB_URL . '/bootstrap_chosen/bootstrap_chosen.css');
        wp_enqueue_script('bower_chosen', SB_THEME_LIB_URL . '/bower_chosen/chosen.jquery.js', array('jquery'), false, true);
    }

    public static function load_bower_chosen() {
        wp_enqueue_style('bower_shosen_style', SB_THEME_LIB_URL . '/bower_chosen/chosen.css');
        wp_enqueue_script('bower_chosen', SB_THEME_LIB_URL . '/bower_chosen/chosen.jquery.js', array(), false, false);
    }

    public static function load_p7vscroller($style = 's4') {
        $base_name = 'p7vscroller';
        $style_name = '04';
        switch($style) {
            case 's1':
                $style_name = '01';
                break;
            case 's2':
                $style_name = '02';
                break;
            case 's3':
                $style_name = '03';
                break;
            case 's4':
                $style_name = '04';
                break;
            case 's5':
                $style_name = '05';
                break;
            case 's6':
                $style_name = '06';
                break;
            case 's7':
                $style_name = '07';
                break;
            default:
                $style_name = '04';
                break;
        }
        wp_register_style($base_name . '_01_style', SB_THEME_LIB_URL . '/p7vscroller/s1/p7VSC01.css');
        wp_enqueue_style($base_name . '_01_style');
        $style_number = $style_name;
        $style_name = 'p7VSC' . $style_name . '.css';
        $style_url = SB_THEME_LIB_URL . '/' . $base_name . '/' . $style . '/' . $style_name;
        wp_register_style($base_name . '_' . $style_number . '_style', $style_url);
        wp_register_script($base_name, SB_THEME_LIB_URL . '/' . $base_name . '/p7vscscripts.js', array('jquery'), false, true);
        wp_enqueue_script($base_name);
        wp_enqueue_style($base_name . '_' . $style_number . '_style');
    }

    public static function load_jquery_scrollbar() {
        wp_register_style('jquery_scrollbar_style', SB_THEME_LIB_URL . '/jquery.scrollbar/jquery.scrollbar.css');
        wp_register_script('jquery_scrollbar', SB_THEME_LIB_URL . '/jquery.scrollbar/jquery.scrollbar.min.js', array('jquery'), false, true);
        wp_enqueue_style('jquery_scrollbar_style');
        wp_enqueue_script('jquery_scrollbar');
    }
    
    public static function load_malihu_custom_scrollbar_plugin() {
        wp_register_style('malihu-custom-scrollbar-plugin-style', SB_THEME_LIB_URL . '/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css');
        wp_register_script('malihu-custom-scrollbar-plugin', SB_THEME_LIB_URL . '/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js', array('jquery'), false, true);
        wp_enqueue_style('malihu-custom-scrollbar-plugin-style');
        wp_enqueue_script('malihu-custom-scrollbar-plugin');
    }
}