<?php
class SB_Theme {
    public static function the_title() {
        if(is_home()) {
            echo get_bloginfo('name') . ' - ' . get_bloginfo('description');
        } elseif(is_post_type_archive('product')) {
            _e('Products List', 'sb-theme');
        } elseif(is_post_type_archive('forum')) {
            printf(__('%s forum', 'sb-theme'), get_bloginfo('name'));
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

    public static function created_by() {
        printf('Giao diện được tạo bởi <a href="%1$s">%2$s</a>', SB_THEME_WEBSITE, SB_THEME_AUTHOR);
    }

    public static function the_copyright_html() {
        printf('Copyright © %1$s %2$s - Giao diện được tạo bởi <a href="%3$s">%4$s</a>', date('Y'), get_bloginfo('name'), SB_THEME_WEBSITE, SB_THEME_AUTHOR);
    }

	public static function enqueue_custom_script($handle, $name) {
		$file_path = SB_THEME_CUSTOM_URL . '/js/' . $name . '.js';
		SB_Core::enqueue_script($handle, $file_path);
	}

	public static function enqueue_custom_style($handle, $name) {
		$file_path = SB_THEME_CUSTOM_URL . '/css/' . $name . '.css';
		wp_enqueue_style($handle, $file_path);
	}

    public static function the_clear_div() {
        echo '<div class="clear"></div>';
    }

	public static function enqueue_custom_responsive_style($handle, $name, $max_width = 1024) {
		$file_path = SB_THEME_CUSTOM_URL . '/css/' . $name . '.css';
		wp_enqueue_style($handle, $file_path, array(), false, 'screen and (max-width: ' . $max_width . 'px)');
	}

    public static function sidebar($name) {
        if(!dynamic_sidebar($name)) {
            dynamic_sidebar('primary');
        }
    }

    public static function container_class($class = '') {
        $class = SB_PHP::add_string_with_space_before($class, 'sb-container sb-wrap container');
        echo $class;
    }

    public static function the_favicon_html() {
        $favicon_url = SB_Option::get_favicon_url();
        if(!empty($favicon_url)) {
            printf('<link type="images/x-icon" href="%s" rel="icon">', $favicon_url);
        }
        $manifest_file = ABSPATH . 'manifest.json';
        ?>
        <?php if(file_exists($manifest_file)) : ?>
            <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
            <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
            <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
            <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
            <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
            <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
            <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
            <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
            <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
            <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
            <link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
            <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
            <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
            <link rel="manifest" href="/manifest.json">
            <meta name="msapplication-TileColor" content="#da532c">
            <meta name="msapplication-TileImage" content="/mstile-144x144.png">
            <meta name="theme-color" content="#ffffff">
        <?php endif; ?>
        <?php
    }

    public static function section_before($name, $class = '') {
        $class = SB_PHP::add_string_with_space_before($name, $class);
        ?>
        <div class="<?php echo $class; ?>">
            <div class="sb-wrap container">
                <div class="<?php echo $name; ?>-container">
        <?php
    }

    public static function section_after() {
        ?>
                </div>
            </div>
        </div>
        <?php
    }

    public static function the_date_meta_html() {
        if(is_single() || is_page()) {
            printf('<meta content="%s" property="article:published_time">', get_the_date('c'));
            printf('<meta content="%s" property="article:modified_time">', get_the_modified_time('c'));
        }
    }

    public static function add_meta_box($args = array()) {
        $meta_box = new SB_Meta_Box($args);
    }

    public static function the_paginate($args = array()) {
        if(function_exists('sb_paginate')) {
            sb_paginate($args);
        }
    }

    public static function the_social_share_button() {
        sb_theme_get_content('content-social-share');
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
        } else {
            comments_template();
        }
    }

    public static function the_comments() {
        if(function_exists('sb_comments')) {
            sb_comments();
        } else {
            sb_theme_get_content('comment-template');
        }
    }

    public static function the_footer_text() {
        echo wpautop(SB_Option::get_theme_footer_text());
    }

    public static function the_leaderboard_ads() {
        self::the_ads_widget_area('leaderboard-ads');
    }

    public static function the_ads_widget_area($name) {
        $class = 'sb-ads';
        $class = SB_PHP::add_string_with_space_before($class, $name);
        echo '<div class="' . $class . '">';
        dynamic_sidebar($name);
        echo '</div>';
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
        if(is_array($logo_url)) {
            $logo_url = SB_Option::get_media_detail($logo_url);
            $logo_url = $logo_url['url'];
        }
        if(!empty($logo_url)) {
            printf('<img src="%1$s" alt="%2$s">', $logo_url, esc_attr(get_bloginfo('name')));
        } else {
            bloginfo('name');
        }
    }

    public static function meta_box_before($class = '') {
        $class = SB_PHP::add_string_with_space_before($class, 'sbmb-container sb-meta-box-container');
        echo '<div class="' . $class . '">';
    }

    public static function meta_box_after() {
        echo '</div>';
    }

    public static function object_embed($args = array()) {
        $url = isset($args['url']) ? $args['url'] : '';
        $flash_var = isset($args['flash_var']) ? $args['flash_var'] : '';
        $id = isset($args['id']) ? $args['id'] : '';
        $name = isset($args['name']) ? $args['name'] : '';
        $classid = isset($args['classid']) ? $args['classid'] : 'clsid:d27cdb6e-ae6d-11cf-96b8-444553540000';
        $version = isset($args['version']) ? $args['version'] : '9,0,0,0';
        ?>
        <object width="100%" height="100%" align="middle" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=<?php echo $version; ?>" classid="<?php echo $classid; ?>" id="<?php echo $id; ?>">
            <param value="transparent" name="wmode">
            <param value="high" name="quality">
            <param value="always" name="allowscriptaccess">
            <param value="internal" name="allowNetworking">
            <param value="<?php echo $flash_var; ?>" name="FlashVars">
            <param value="<?php echo $url; ?>" name="movie">
            <embed width="100%" height="100%" align="middle" src="<?php echo $url; ?>" quality="high" allowscriptaccess="always" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allownetworking="internal" name="<?php echo $name; ?>" wmode="transparent" flashvars="<?php echo $flash_var; ?>">
        </object>
        <?php
    }

    public static function the_breadcrumb() {
        if(function_exists('yoast_breadcrumb') && SB_Option::yoast_breadcrumb_enabled()) {
            yoast_breadcrumb('<div class="sb-breadcrumb breadcrumb yoast">', '</div>');
        } elseif(function_exists('bcn_display')) {
            echo '<div class="sb-breadcrumb breadcrumb bcn">';
            bcn_display();
            echo '</div>';
        }
    }

    public static function the_logo() {
        sb_theme_the_logo();
    }

    public static function the_menu($args = array()) {
        $theme_location = isset($args['theme_location']) ? $args['theme_location'] : '';
        $locations = SB_Core::get_menu_location();
	    $menu_id = isset($locations[$theme_location]) ? $locations[$theme_location] : 0;
        $menu = wp_get_nav_menu_object($menu_id);
        $mobile = isset($args['mobile']) ? $args['mobile'] : false;
        $position = isset($args['position']) ? $args['position'] : 'left';
        $class = 'sb-mobile-menu';
        $class = SB_PHP::add_string_with_space_before($class, $position);
        if(!SB_Core::is_error($menu)) {
            $sb_menu = self::get_menu($args);
            echo $sb_menu;
            if($mobile) {
                echo '<div class="' . $class . '"><span class="mobile-menu-button"><i class="fa fa fa-bars"></i></span>';
                echo $sb_menu;
                echo '</div>';
            }
        } else {
            self::the_menu_default($args);
            if($mobile) {
                echo '<div class="' . $class . '"><span class="mobile-menu-button"><i class="fa fa fa-bars"></i></span>';
                self::the_menu_default($args);
                echo '</div>';
            }
        }
    }

    public static function get_menu($args = array()) {
        $theme_location = isset($args['theme_location']) ? $args['theme_location'] : '';
        if(empty($theme_location)) {
            return '';
        }
        $transient_name = SB_Cache::build_menu_transient_name($theme_location);
        $args['echo'] = false;
        $menu_class = isset($args['menu_class']) ? $args['menu_class'] : '';
        $superfish = isset($args['superfish']) ? (bool)$args['superfish'] : true;
        if($superfish) {
            $menu_class = SB_PHP::add_string_with_space_before($menu_class, 'sf-menu');
        }
        $menu_class = SB_PHP::add_string_with_space_before($menu_class, 'sb-menu');
        $menu_class = SB_PHP::add_string_with_space_before($menu_class, $theme_location);
        $args['menu_class'] = $menu_class;
        if(false == ($sb_menu = get_transient($transient_name))) {
            $sb_menu = wp_nav_menu($args);
            set_transient($transient_name, $sb_menu, WEEK_IN_SECONDS);
        }
        return $sb_menu;
    }

    public static function the_mobile_menu($args = array()) {
        $theme_location = isset($args['theme_location']) ? $args['theme_location'] : '';
        $locations = SB_Core::get_menu_location();
        $menu_id = isset($locations[$theme_location]) ? $locations[$theme_location] : 0;
        $menu = wp_get_nav_menu_object($menu_id);
        $position = isset($args['position']) ? $args['position'] : 'left';
        $class = 'sb-mobile-menu an-aside-mobile-menu';
        $class = SB_PHP::add_string_with_space_before($class, $position);
        $class = SB_PHP::add_string_with_space_before($class, $theme_location);
        $button_text = isset($args['button_text']) ? trim($args['button_text']) : '';
        $search = isset($args['search']) ? (bool)$args['search'] : false;
        $search_args = array(
            ''
        );
        if($search) {
            $class = SB_PHP::add_string_with_space_before($class, 'search');
        }
        if(!SB_Core::is_error($menu)) {
            echo '<div class="' . $class . '">';
            self::the_mobile_menu_button($button_text);
            if($search) {
                self::the_search_form($search_args);
            }
            $sb_menu = self::get_menu($args);
            echo $sb_menu;
            echo '</div>';
        } else {
            echo '<div class="' . $class . '">';
            self::the_mobile_menu_button($button_text);
            if($search) {
                self::the_search_form($search_args);
            }
            self::the_menu_default($args);
            echo '</div>';
        }
    }

    public static function the_mobile_menu_button($button_text = '') {
        if(!empty($button_text)) {
            $button_text = '<span class="text">' . $button_text . '</span>';
        }
        echo '<span class="mobile-menu-button"><i class="fa fa fa-bars"></i>' . $button_text . '</span>';
    }

    public static function google_analytics_tracking() {
        $ga_id = SB_Option::get_theme_option_single_key('google_analytics');
        if(empty($ga_id)) {
            return;
        }
        ?>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', '<?php echo $ga_id; ?>', 'auto');
            ga('send', 'pageview');

        </script>
        <?php
    }

    private static function the_menu_default($args = array()) {
        $theme_location = isset($args['theme_location']) ? $args['theme_location'] : '';
        $menu_class = isset($args['menu_class']) ? $args['menu_class'] : '';
        $superfish = isset($args['superfish']) ? (bool)$args['superfish'] : true;
        if($superfish) {
            $menu_class = SB_PHP::add_string_with_space_before($menu_class, 'sf-menu');
        }
        $menu_class = SB_PHP::add_string_with_space_before($menu_class, 'sb-menu');
        $menu_class = SB_PHP::add_string_with_space_before($menu_class, $theme_location);
        $args['menu_class'] = $menu_class;
        ?>
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
        <?php
    }

    public static function register_sidebar($sidebar_id, $sidebar_name, $sidebar_description) {
        if(!class_exists('SB_Core')) {
            return;
        }
        SB_Core::register_sidebar($sidebar_id, $sidebar_name, $sidebar_description);
    }

    public static function register_sidebar_leaderboard_ads() {
        sb_theme_register_sidebar('leaderboard-ads', 'Leaderboard ads', __('The avertising on top of site.', 'sb-theme'));
    }

    public static function register_sidebar_float_ads() {
        sb_theme_register_sidebar('float-ads-left', 'Float ads left', __('The avertising on the left of site.', 'sb-theme'));
        sb_theme_register_sidebar('float-ads-right', 'Float ads right', __('The avertising on the right of site.', 'sb-theme'));
    }

    public static function the_loading_dotted() {
        sb_theme_get_content('loading-dotted');
    }

    public static function get_social_share_url($args = array()) {
        return SB_Core::get_social_share_url($args);
    }

    public static function the_login_page_header() {
        do_action('sb_login_page_init');
        get_header();
    }

    public static function get_page_not_found_image_url() {
        if(!class_exists('SB_Core')) {
            return;
        }
        return SB_CORE_URL . '/images/page-not-found.png';
    }

    public static function get_custom_image_url($name) {
        return SB_THEME_CUSTOM_URL . '/images/' . $name;
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

    public static function get_game_no_thumbnail_url() {
        return SB_THEME_URL . '/images/icon-game-no-thumbnail.png';
    }

    public static function get_no_thumbnail_url($name = '') {
        if(empty($name)) {
            $name = 'no-thumbnail-bg.png';
        }
        return SB_THEME_URL . '/images/' . $name;
    }

    public static function the_raty($args = array()) {
        $class = isset($args['class']) ? $args['class'] : '';
        $class = SB_PHP::add_string_with_space_before($class, 'sb-theme-raty');
        $score = isset($args['score']) ? floatval($args['score']) : 0;
        ?>
        <div class="<?php echo $class; ?>" data-score="<?php echo $score; ?>" data-star-on="<?php echo SB_THEME_LIB_URL . '/jquery-raty/images/star-on.png'; ?>" data-star-half="<?php echo SB_THEME_LIB_URL . '/jquery-raty/images/star-half.png'; ?>" data-star-off="<?php echo SB_THEME_LIB_URL . '/jquery-raty/images/star-off.png'; ?>"></div>
        <?php
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
    public static function sharethis() {
        ?>
        <span class='st_facebook_hcount sharethis-facebook' displayText='Facebook'></span>
        <span class='st_twitter_hcount sharethis-twitter' displayText='Tweet'></span>
        <span class='st_googleplus_hcount sharethis-googleplus' displayText='Google +'></span>
        <span class='st_linkedin_hcount sharethis-linkedin' displayText='LinkedIn'></span>
        <span class='st_pinterest_hcount sharethis-pinterest' displayText='Pinterest'></span>
        <span class='st_email_hcount sharethis-email' displayText='Email'></span>
        <?php
    }

    public static function sharethis_facebook() {
        echo '<span class="st_facebook_hcount sharethis-facebook" displayText="Facebook"></span>';
    }
    
    public static function sharethis_twitter() {
        echo '<span class="st_twitter_hcount sharethis-twitter" displayText="Tweet"></span>';
    }
    
    public static function sharethis_pinterest() {
        echo '<span class="st_pinterest_hcount sharethis-pinterest" displayText="Pinterest"></span>';
    }
    
    public static function sharethis_googleplus() {
        echo '<span class="st_googleplus_hcount sharethis-googleplus" displayText="Google +"></span>';
    }
    
    public static function sharethis_linkedin() {
        echo '<span class="st_linkedin_hcount sharethis-linkedin" displayText="LinkedIn"></span>';
    }
    
    public static function sharethis_email() {
        echo '<span class="st_email_hcount sharethis-email" displayText="Email"></span>';
    }
}