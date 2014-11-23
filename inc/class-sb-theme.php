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

    public static function sidebar($name) {
        if(!dynamic_sidebar($name)) {
            dynamic_sidebar('primary');
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

    public static function the_paginate($args = array()) {
        if(function_exists('sb_paginate')) {
            sb_paginate($args);
        }
    }

    public static function the_archive_title() {
        if(is_category()) {
            single_cat_title();
        } elseif(is_tag()) {
            single_tag_title();
        } elseif(is_author()) {
            the_author();
        } elseif(is_day()) {
            printf( __( 'Lưu trữ ngày %s' ), get_the_date() );
        } elseif(is_month()) {
            printf( __( 'Lưu trữ tháng %s' ), get_the_date('F Y') );
        } elseif(is_year()) {
            printf( __( 'Lưu trữ năm %s' ), get_the_date('Y') );
        } else {
            _e('Lưu trữ', 'sb-theme');
        }
    }

    public static function the_comment_template() {
        if(function_exists('sb_comment_template')) {
            sb_comment_template();
        }
    }

    public static function the_comments() {
        if(function_exists('sb_comments')) {
            sb_comments();
        }
    }

    public static function get_text($en, $vi) {
        return sprintf(__('<!--:en-->%1$s<!--:--><!--:vi-->%2$s<!--:-->'), $en, $vi);
    }

    public static function the_text($en, $vi) {
        echo self::get_text($en, $vi);
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
            $menu_class = isset($args['menu_class']) ? $args['menu_class'] : '';
            $menu_class = SB_PHP::add_string_with_space_before($menu_class, 'sf-menu');
            $args['menu_class'] = $menu_class;
        }
        $theme_location = isset($args['theme_location']) ? $args['theme_location'] : '';
        $locations = SB_Core::get_menu_location();
        if(empty($theme_location) || !array_key_exists($theme_location, $locations)) {
            return;
        }
        $location_name = $locations[$theme_location];
        switch($theme_location) {
            case 'primary':
                $location_name = 'Primary menu';
                break;
            case 'secondary':
                $location_name = 'Secondary menu';
                break;
            case 'footer':
                $location_name = 'Footer menu';
                break;
        }
        $menu = wp_get_nav_menu_object($location_name);
        if($menu && !is_wp_error($menu)) {
            wp_nav_menu($args);
        } else { ?>
            <div class="sb-menu-container">
                <ul class="<?php echo $args['menu_class']; ?>">
                    <?php $default = isset($args['default']) ? $args['default'] : 'page';
                    $posts_per_page = 8;
                    if(isset($args['posts_per_page'])) {
                        $posts_per_page = $args['posts_per_page'];
                    }
                    if('page' == $default) {
                        $posts_per_page -= 2;
                        $pages = SB_Query::get_pages(array('number' => $posts_per_page));
                        foreach($pages as $page) : ?>
                            <li class="menu-item"><a href="<?php echo get_permalink($page->ID); ?>"><?php echo $page->post_title; ?></a></li>
                        <?php endforeach;
                    } else {
                        $taxonomy = isset($args['taxonomy']) ? $args['taxonomy'] : 'category';
                        $terms = SB_Term::get($taxonomy, array('number' => $posts_per_page));
                        foreach($terms as $term) : ?>
                            <li class="menu-item"><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?></a></li>
                        <?php endforeach;
                    } ?>
                </ul>
            </div>
        <?php }
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

    public static function get_social_share_url($args = array()) {
        $result = '';
        $title = '';
        $permalink = '';
        $url = '';
        $social_name = '';
        $thumbnail = '';
        $excerpt = '';
        $language = SB_Option::get_default_language();
        $twitter_account = '';
        extract($args, EXTR_OVERWRITE);
        if(empty($permalink)) {
            $permalink = $url;
        }
        if(empty($twitter_account)) {
            $twitter_account = SB_Option::get_theme_social('twitter');
            $twitter_account = basename($twitter_account);
        }
        if(empty($twitter_account)) {
            $twitter_account = 'skylarkcob';
        }
        switch($social_name) {
            case 'facebook':
                $url = 'https://www.facebook.com/sharer/sharer.php';
                $url = add_query_arg('u', $permalink, $url);
                $url = add_query_arg('t', $title, $url);
                $result = $url;
                break;
            case 'googleplus':
                $url = 'http://plusone.google.com/_/+1/confirm';
                $url = add_query_arg('hl', $language, $url);
                $url = add_query_arg('url', $permalink, $url);
                $result = $url;
                break;
            case 'twitter':
                $url = 'http://twitter.com/share';
                $url = add_query_arg('url', $permalink, $url);
                $url = add_query_arg('text', $title, $url);
                $url = add_query_arg('via', $twitter_account, $url);
                $result = $url;
                break;
            case 'pinterest':
                $url = 'http://www.pinterest.com/pin/create/button';
                $url = add_query_arg('media', $thumbnail, $url);
                $url = add_query_arg('url', $permalink, $url);
                $url = add_query_arg('description', $title . ' ' . $permalink, $url);
                $result = $url;
                break;
            case 'zingme':
                $url = 'http://link.apps.zing.vn/share';
                $url = add_query_arg('t', $title, $url);
                $url = add_query_arg('u', $permalink, $url);
                $url = add_query_arg('desc', $excerpt, $url);
                $result = $url;
                break;
        }
        return $result;
    }

    public static function get_page_not_found_image_url() {
        if(!class_exists('SB_Core')) {
            return;
        }
        return SB_CORE_URL . '/images/page-not-found.png';
    }

    public static function set_carousel_argument($args = array()) {
        global $sb_carousel_argument;
        $sb_carousel_argument = $args;
    }

    public static function get_carousel_argument() {
        global $sb_carousel_argument;
        if(!is_array($sb_carousel_argument)) {
            $sb_carousel_argument = array();
        }
        return $sb_carousel_argument;
    }

    public static function set_nav_pills_argument($args = array()) {
        global $sb_nav_pills_argument;
        $sb_nav_pills_argument = $args;
    }

    public static function get_nav_pills_argument() {
        global $sb_nav_pills_argument;
        return $sb_nav_pills_argument;
    }

    public static function nav_pills($args = array()) {
        self::set_nav_pills_argument($args);
        sb_theme_get_content('nav-pills');
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

    public static function carousel($args = array()) {
        self::set_carousel_argument($args);
        sb_theme_get_content('carousel');
    }

    public static function set_search_form_args($args = array()) {
        global $sb_search_form_args;
        $sb_search_form_args = $args;
    }

    public static function get_search_form_args() {
        global $sb_search_form_args;
        return $sb_search_form_args;
    }

    public static function the_search_form($args = array()) {
        self::set_search_form_args($args);
        sb_theme_get_content('search-form');
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

    public static function addthis() {
        echo '<div class="addthis_native_toolbox addthis_toolbox"></div>';
    }
}