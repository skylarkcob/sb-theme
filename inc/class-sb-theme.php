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
        sb_get_template_part('sb-theme-header');
    }

    public static function the_footer() {
        sb_get_template_part('sb-theme-footer');
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
            yoast_breadcrumb('<div class="sb-breadcrumb">','</div>');
        }
    }

    public static function the_logo() {
        sb_theme_the_logo();
    }

    public static function the_menu($args = array()) {
        $args['menu_class'] = trim((isset($args['menu_class']) ? $args['menu_class'] : '') . ' sf-menu');
        wp_nav_menu($args);
    }

    public static function register_sidebar($sidebar_id, $sidebar_name, $sidebar_description) {
        SB_Core::register_sidebar($sidebar_id, $sidebar_name, $sidebar_description);
    }
}