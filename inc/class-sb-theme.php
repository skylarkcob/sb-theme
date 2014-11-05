<?php
class SB_Theme {
    public static function the_title() {
        if(is_home()) {
            echo get_bloginfo('name') . ' - ' . get_bloginfo('description');
        } elseif(is_post_type_archive('product')) {
            _e('Products List', 'sb-core');
        } elseif(is_post_type_archive('forum')) {
            printf(__('%s forum', 'sb-core'), get_bloginfo('name'));
        } elseif(is_singular('forum')) {
            echo get_the_title().' - '.get_bloginfo('name');
        } elseif(is_singular('topic') || is_single() || is_page()) {
            echo get_the_title();
        } elseif(is_tax()) {
            single_term_title();
        } else {
            wp_title('');
        }
    }

    public static function the_favicon_html() {
        $favicon_url = SB_Option::get_favicon_url();
        if(!empty($favicon_url)) {
            printf('<link type="images/x-icon" href="%s" rel="icon">', $favicon_url);
        }
    }

    public static function the_date_meta_html() {
        if(is_single() || is_page()) {
            printf('<meta content="%s" property="article:published_time">', get_the_date('c'));
            printf('<meta content="%s" property="article:modified_time">', get_the_modified_time('c'));
        }
    }

    public static function the_header() {
        sb_theme_get_content('sb-theme-header');
    }

    public static function the_footer() {
        sb_theme_get_content('sb-theme-footer');
    }

    public static function the_logo_image_html() {
        $logo_url = SB_Option::get_logo_url();
        if(!empty($logo_url)) {
            printf('<img src="%1$s" alt="%2$s">', $logo_url, esc_attr(get_bloginfo('name')));
        } else {
            bloginfo('name');
        }
    }

    public static function the_breadcrumb() {
        if ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb('<div class="sb-breadcrumb breadcrumb">','</div>');
        }
    }

    public static function the_logo() {
        sb_theme_the_logo();
    }

    public static function the_menu($args = array()) {
        $superfish = isset($args['superfish']) ? (bool)$args['superfish'] : true;
        if($superfish) {
            $args['menu_class'] = trim((isset($args['menu_class']) ? $args['menu_class'] : '') . ' sf-menu');
        }
        wp_nav_menu($args);
    }

    public static function register_sidebar($sidebar_id, $sidebar_name, $sidebar_description) {
        if(!class_exists('SB_Core')) {
            return;
        }
        SB_Core::register_sidebar($sidebar_id, $sidebar_name, $sidebar_description);
    }

    public static function the_loading_dotted() {
        sb_theme_get_content('loading-dotted');
    }

    public static function get_page_not_found_image_url() {
        if(!class_exists('SB_Core')) {
            return;
        }
        return SB_CORE_URL . '/images/page-not-found.png';
    }

    public static function set_modal_argument($args = array()) {
        global $sb_modal_argument;
        $sb_modal_argument = $args;
    }

    public static function get_modal_argument() {
        global $sb_modal_argument;
        if(!is_array($sb_modal_argument)) {
            $sb_modal_argument = array();
        }
        return $sb_modal_argument;
    }

    public static function modal($args = array()) {
        self::set_modal_argument($args);
        sb_theme_get_content('modal');
    }

    public static function get_loading_image($url = '') {
        if(!class_exists('SB_Core')) {
            return;
        }
        if(empty($url)) {
            $url = SB_CORE_URL . '/images/ajax-loader.gif';
        }
        return sprintf('<img src="%s" class="ajax-loading loading-image hidden">', $url);
    }
}