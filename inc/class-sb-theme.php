<?php
defined('ABSPATH') or die('Please do not pip me!');

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

    public static function use_custom_menu() {
        return SB_Tool::use_custom_menu();
    }

    public static function add_term_field_thumbnail($taxonomies = array()) {
        sb_theme_term_meta_thumbnail($taxonomies);
    }

    public static function add_category_field_thumbnail() {
        self::add_term_field_thumbnail(array('category'));
    }

    public static function add_term_field_color($taxonomies = array()) {
        sb_theme_term_meta_color($taxonomies);
    }

    public static function the_yahoo_button($args = array()) {
        $title = isset($args['title']) ? $args['title'] : 'Liên hệ qua Yahoo';
        $account = isset($args['account']) ? $args['account'] : '';
        $type = isset($args['type']) ? $args['type'] : 1;
        echo '<a class="btn-yahoo" target="_blank" title="' . $title . '" rel="nofollow" href="ymsgr:sendim?' . $account . '"><img border="0" align="absmiddle" alt="' . $account . '" src="http://opi.yahoo.com/online?u=' . $account . '&amp;m=g&amp;t=' . $type . '"></a>';
    }

    public static function the_skype_button($args = array()) {
        $title = isset($args['title']) ? $args['title'] : 'Liên hệ qua Skype';
        $account = isset($args['account']) ? $args['account'] : '';
        $type = isset($args['type']) ? $args['type'] : 1;
        $image = isset($args['image']) ? $args['image'] : '';
        if(empty($image)) {
            $image = self::get_image_url('icon-skype-49.png');
        }
        echo '<a class="btn-skype" target="_blank" title="' . $title . '" rel="nofollow" href="skype:' . $account . '?chat"><img alt="' . $account . '" src="' . $image . '" style="border:none;"></a>';
    }

    public static function add_category_field_color() {
        self::add_term_field_color(array('category'));
    }

    public static function get_rss_feed_url() {
        $url = '';
        if(empty($url)) {
            $url = get_bloginfo('rss2_url');
        }
        return $url;
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

    public static function enqueue_password_strength_meter() {
        wp_enqueue_script('password-strength-meter');
        wp_localize_script('password-strength-meter', 'pwsL10n', array(
            'empty' => SB_Message::get_password_strength(),
            'short' => SB_Message::get_password_strength_short(),
            'bad' => SB_Message::get_password_strength_bad(),
            'good' => SB_Message::get_password_strength_good(),
            'strong' => SB_Message::get_password_strength_strong(),
            'mismatch' => SB_Message::get_password_strength_mismatch()
        ));
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

    public static function add_theme_setting_field($id, $title, $callback) {
        if(SB_PHP::callback_exists($callback)) {
            sb_theme_add_setting_field($id, $title, $callback);
        }
    }

    public static function add_theme_setting_field_category_widget() {
        sb_theme_setting_field_category_widget();
    }

    public static function add_theme_setting_field_hotline() {
        sb_theme_setting_field_hotline();
    }

	public static function add_theme_support_woocommerce() {
		add_theme_support( 'woocommerce' );
	}

    public static function container_class($class = '') {
        $class = SB_PHP::add_string_with_space_before($class, 'sb-container sb-wrap container');
        echo $class;
    }

    public static function get_supported_socials() {
        return apply_filters('sb_theme_social', array('facebook' => 'Facebook', 'gplus' => 'Google Plus', 'twitter' => 'Twitter', 'youtube' => 'YouTube', 'linkedin' => 'LinkedIn', 'pinterest' => 'Pinterest', 'zingme' => 'Zing Me', 'rss' => 'RSS'));
    }

    public static function the_social_list() {
        $socials = self::get_supported_socials();
        echo '<ul class="social list-unstyled list-socials">';
        foreach($socials as $social_name => $text) {
            $url = SB_Option::get_theme_social($social_name);
            if(!empty($url)) {
                $social_name = str_replace('_', '-', $social_name);
                if('gplus' == $social_name) {
                    $social_name = 'google-plus';
                }
                ?>
                <li class="<?php echo $social_name; ?>">
                    <a target="_blank" rel="nofollow" href="<?php echo $url; ?>">
                        <i class="fa fa-<?php echo $social_name; ?> btn-social btn-<?php echo $social_name; ?>"></i><span class="text"><?php echo $text; ?></span>
                    </a>
                </li>
                <?php
            }
        }
        echo '</ul>';
    }

    public static function get_current_theme_info() {
        return wp_get_theme();
    }

    public static function is_founder() {
        $value = sb_core_owner();
        return apply_filters('sb_theme_founder', $value);
    }

    public static function the_recent_comment_list($comments, $args = array()) {
        $avatar_size = isset($args['avatar_size']) ? absint($args['avatar_size']) : 116;
        $content_length = isset($args['content_length']) ? absint($args['content_length']) : 80;
        ?>
        <ul class="list-unstyled list-recent-comments">
            <?php foreach($comments as $comment) : ?>
                <?php
                $author_url = $comment->comment_author_url;
                $post_comment_url = SB_Post::get_comment_link($comment->post_id);
                $author_link = '<b>';
                if(!empty($author_url)) {
                    $author_link .= '<a itemprop="url" class="url fn n" href="' . $author_url . '">';
                    $author_link .= '<span itemprop="name">' . $comment->comment_author . '</span>';
                    $author_link .= '</a>';
                } else {
                    $author_link .= '<span itemprop="name">' . $comment->comment_author . '</span>';
                }
                $author_link .= '</b>';
                ?>
                <li class="a-comment">
                    <?php
                    echo get_avatar($comment->comment_author_email, $avatar_size);
                    echo $author_link . ': ';
                    echo SB_PHP::substr($comment->comment_content, $content_length); ?> <a href="<?php echo $post_comment_url; ?>">»</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php
    }

    public static function the_widget_recent_post_most_views($args = array(), &$exclude_ids = array()) {
        $posts_per_page = isset($args['posts_per_page']) ? $args['posts_per_page'] : '';
        if(empty($posts_per_page)) {
            $posts_per_page = SB_Query::get_posts_per_page();
        }
        $post_type = isset($args['post_type']) ? $args['post_type'] : 'post';
        $post_not_in = isset($args['post__not_in']) ? $args['post__not_in'] : $exclude_ids;
        $widget_title = isset($args['widget_title']) ? $args['widget_title'] : __('Được xem nhiều', 'sb-theme');
        $thumbnail_size = isset($args['thumbnail_size']) ? $args['thumbnail_size'] : array(85, 64);
        $thumbnail_width = isset($thumbnail_size[0]) ? $thumbnail_size[0] : 85;
        $thumbnail_height = isset($thumbnail_size[1]) ? $thumbnail_size[1] : 64;
        $title_length = isset($args['title_length']) ? $args['title_length'] : 40;
        $transient_name = 'sb_theme_query_recent_post_by_view';
        if(false === ($query = get_transient($transient_name))) {
            $args = array(
                'posts_per_page' => $posts_per_page,
                'post_type' => $post_type,
                'post__not_in' => $post_not_in
            );
            $query = SB_Query::get_recent_post_by_view($args);
            set_transient($transient_name, $query, DAY_IN_SECONDS);
        }
        if($query->have_posts()) : ?>
            <section class="widget slide-posts recent-most-views widget-posts">
                <h4 class="widget-title"><?php echo $widget_title; ?></h4>
                <div class="sb-post-widget">
                    <div class="sb-post-widget-inner">
                        <ol class="list-unstyled list-posts" data-count="<?php echo $query->post_count; ?>">
                            <?php while($query->have_posts()) : $query->the_post();
                                $exclude_ids[] = get_the_ID();
                                if('' == get_the_content()) {
                                    continue;
                                }
                                ?>
                                <li class="sb-post">
                                    <?php SB_Post::the_thumbnail_crop_only_link_image_html($thumbnail_width, $thumbnail_height); ?>
                                    <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ol>
                    </div>
                </div>
            </section>
        <?php endif;
    }

    public static function the_widget_recent_post_most_view_in_week($args = array(), &$exclude_ids = array()) {
        $posts_per_page = isset($args['posts_per_page']) ? $args['posts_per_page'] : '';
        $post_type = isset($args['post_type']) ? $args['post_type'] : 'post';
        $widget_title = isset($args['widget_title']) ? $args['widget_title'] : __('Nổi bật trong tuần', 'sb-theme');
        $post_not_in = isset($args['post__not_in']) ? $args['post__not_in'] : $exclude_ids;
        if(empty($posts_per_page)) {
            $posts_per_page = SB_Query::get_posts_per_page();
        }
        $thumbnail_size = isset($args['thumbnail_size']) ? $args['thumbnail_size'] : array(85, 64);
        $thumbnail_width = isset($thumbnail_size[0]) ? $thumbnail_size[0] : 85;
        $thumbnail_height = isset($thumbnail_size[1]) ? $thumbnail_size[1] : 64;
        $title_length = isset($args['title_length']) ? $args['title_length'] : 40;
        $transient_name = 'sb_theme_query_most_view_of_week';
        if(false === ($query = get_transient($transient_name))) {
            $args = array(
                'posts_per_page' => $posts_per_page,
                'post_type' => $post_type,
                'post__not_in' => $post_not_in
            );
            $query = SB_Query::get_most_view_of_week($args);
            set_transient($transient_name, $query, DAY_IN_SECONDS);
        }
        if($query->have_posts()) : ?>
            <section class="widget slide-posts most-views-week widget-posts">
                <h4 class="widget-title"><?php echo $widget_title; ?></h4>
                <div class="sb-post-widget">
                    <div class="sb-post-widget-inner">
                        <ol class="list-unstyled list-posts week-views" data-count="<?php echo $query->post_count; ?>">
                            <?php while($query->have_posts()) : $query->the_post();
                                array_push($exclude_ids, get_the_ID());
                                if('' == get_the_content()) {
                                    continue;
                                }
                                ?>
                                <li class="sb-post">
                                    <?php SB_Post::the_thumbnail_crop_only_link_image_html($thumbnail_width, $thumbnail_height); ?>
                                    <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ol>
                    </div>
                </div>
            </section>
        <?php endif;
    }

    public static function get_category_widget_ids() {
        $cats = SB_Option::get_theme_option_single_key('category_widget');
        return SB_PHP::string_to_array(',', $cats);
    }

    public static function the_favicon_html() {
        $favicon_url = SB_Option::get_favicon_url();
        if(!empty($favicon_url)) {
            printf('<link type="images/x-icon" href="%s" rel="icon">', $favicon_url);
        }
        $manifest_file = ABSPATH . 'manifest.json';
        if(file_exists($manifest_file)) : ?>
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
        <?php endif;
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

    public static function the_robots_meta() {
        if(!SB_Core::is_wpseo_yoast_installed()) {
            $private_types = SB_Post::get_private_post_types();
            if(in_array(get_post_type(), $private_types)) {
                echo '<meta name="robots" content="noindex, nofollow">', "\n";
            }
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
        } elseif(is_tax()) {
            $term = SB_Term::get_single();
            echo $term->name;
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

    public static function disable_admin_bar() {
        show_admin_bar(false);
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

    public static function run_statistics() {
        sb_theme_statistics();
    }

    public static function the_logo() {
        sb_theme_the_logo();
    }

    public static function the_facebook_javascript_sdk($api_id = '1425884427679175') {
        ?>
        <div id="fb-root"></div>
        <script type="text/javascript">(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.async = 1;
                js.defer = 1;
                js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.3&appId=<?php echo $api_id; ?>";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
        <?php
    }

    public static function use_facebook_javascript_sdk() {
        return SB_Tool::use_facebook_javascript_sdk();
    }

    public static function the_facebook_share_and_like_buttons($url = '') {
        if(empty($url) || !self::use_facebook_javascript_sdk()) {
            return;
        }
        ?>
        <div class="fb-like" data-href="<?php echo $url; ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
        <?php
    }

    public static function the_menu($args = array()) {
        $theme_location = isset($args['theme_location']) ? $args['theme_location'] : '';
        if(empty($theme_location)) {
            $theme_location = isset($args['location']) ? $args['location'] : '';
        }
        $locations = SB_Core::get_menu_location();
	    $menu_id = isset($locations[$theme_location]) ? $locations[$theme_location] : 0;
        $menu = wp_get_nav_menu_object($menu_id);
        $mobile = isset($args['mobile']) ? $args['mobile'] : false;
        $position = isset($args['position']) ? $args['position'] : 'left';
        $button_text = isset($args['button_text']) ? trim($args['button_text']) : '';
        $class = 'sb-mobile-menu';
        $class = SB_PHP::add_string_with_space_before($class, $position);
        $home_icon = isset($args['home_icon']) ? $args['home_icon'] : false;
        $mobile_button = isset($args['mobile_button']) ? $args['mobile_button'] : false;
        if($home_icon) {
            echo '<a class="home-link" href="' . home_url('/') . '"><i class="fa fa-home home-icon"></i></a>';
        }
        if(!SB_Core::is_error($menu)) {
            $sb_menu = self::get_menu($args);
            echo $sb_menu;
            if($mobile) {
                echo '<div class="' . $class . '">';
                echo $sb_menu;
                echo '</div>';
            }
        } else {
            self::the_menu_default($args);
            if($mobile) {
                echo '<div class="' . $class . '">';
                self::the_menu_default($args);
                echo '</div>';
            }
        }
        if($mobile_button) {
            self::the_mobile_menu_button($button_text, 'hidden button-' . $position);
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
        if(!SB_Cache::menu_cache() || false == ($sb_menu = get_transient($transient_name))) {
            $sb_menu = wp_nav_menu($args);
            if(SB_Cache::menu_cache()) {
                set_transient($transient_name, $sb_menu, WEEK_IN_SECONDS);
            }
        }
        return $sb_menu;
    }

    public static function destroy_social_login() {
        global $sb_facebook_v3;
        unset($_SESSION['access_token']);
        if(is_object($sb_facebook_v3) && method_exists($sb_facebook_v3, 'destroySession')) {
            $sb_facebook_v3->destroySession();
        }
        session_destroy();
        if(isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time()-1000);
                setcookie($name, '', time()-1000, '/');
            }
        }
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
        $search_args = isset($args['search_args']) ? $args['search_args'] : array();
        if(!$search) {
            $search = isset($args['with_search']) ? $args['with_search'] : false;
        }
        if($search) {
            $class = SB_PHP::add_string_with_space_before($class, 'search');
        }
        $search_args['search_icon'] = true;
        $mobile_button = isset($args['mobile_button']) ? $args['mobile_button'] : false;
        if(!SB_Core::is_error($menu)) {
            echo '<div class="' . $class . '">';
            if($mobile_button) {
                self::the_mobile_menu_button($button_text);
            }
            if($search) {
                self::the_search_form($search_args);
            }
            $sb_menu = self::get_menu($args);
            echo $sb_menu;
            echo '</div>';
        } else {
            echo '<div class="' . $class . '">';
            if($mobile_button) {
                self::the_mobile_menu_button($button_text);
            }
            if($search) {
                self::the_search_form($search_args);
            }
            self::the_menu_default($args);
            echo '</div>';
        }
    }

    public static function the_mobile_menu_button($button_text = '', $class = '') {
        if(!empty($button_text)) {
            $button_text = '<span class="text">' . $button_text . '</span>';
        }
        $class = SB_PHP::add_string_with_space_before($class, 'mobile-menu-button');
        echo '<span class="' . $class . '"><i class="fa fa fa-bars"></i>' . $button_text . '</span>';
    }

    public static function google_analytics_tracking() {
        self::get_content('google-analytics');
    }

    public static function the_term_meta_nonce() {
        sb_term_meta_nonce();
    }

    public static function the_meta_box_nonce() {
        sb_meta_box_nonce();
    }

    public static function get_contact_form_departments() {
        $default_departments = array(
            'question' => 'Câu hỏi',
            'advertising' => 'Quảng cáo',
            'technical_assistance' => 'Hỗ trợ kỹ thuật',
            'service' => 'Dịch vụ'
        );
        $result = apply_filters('sb_theme_contact_form_departments', $default_departments);
        return $result;
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

    public static function support($feature) {
        return (bool)current_theme_supports($feature);
    }

    public static function register_sidebar($sidebar_id, $sidebar_name, $sidebar_description) {
        if(!class_exists('SB_Core')) {
            return;
        }
        SB_Core::register_sidebar($sidebar_id, $sidebar_name, $sidebar_description);
    }

    public static function register_sidebar_leaderboard_ads() {
        sb_theme_register_sidebar('leaderboard_ads', 'Quảng cáo đầu trang', __('Quảng cáo xuất hiện phía đầu của trang web.', 'sb-theme'));
    }

    public static function register_sidebar_float_ads() {
        sb_theme_register_sidebar('float_ads_left', 'Quảng cáo bên trái', __('Quảng cáo xuất hiện phía bên trái trang web.', 'sb-theme'));
        sb_theme_register_sidebar('float_ads_right', 'Quảng cáo bên phải', __('Quảng cáo xuất hiện phía bên phải trang web.', 'sb-theme'));
    }

    public static function the_float_ads() {
        ?>
        <div class="hidden-sm float-ads left sb-float-ads"><?php dynamic_sidebar('float_ads_left'); ?></div>
        <div class="hidden-sm float-ads right sb-float-ads"><?php dynamic_sidebar('float_ads_right'); ?></div>
        <?php
    }

    public static function the_loading_dotted() {
        sb_theme_get_content('loading-dotted');
    }

    public static function get_social_share_url($args = array()) {
        return SB_Core::get_social_share_url($args);
    }

    public static function the_login_page_header() {
        do_action('sb_theme_login_page_init');
        do_action('sb_login_page_init');
        get_header();
    }

    public static function get_page_not_found_image_url() {
        if(!class_exists('SB_Core')) {
            return '';
        }
        return SB_CORE_URL . '/images/page-not-found.png';
    }

    public static function get_image_url($name) {
        return SB_THEME_URL . '/images/' . $name;
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

    public static function get_post_type_archive_link($post_type) {
        return get_post_type_archive_link($post_type);
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

    public static function set_contact_form_arg($args = array()) {
        global $sb_contact_form_args;
        $sb_contact_form_args = $args;
    }

    public static function get_contact_form_arg() {
        global $sb_contact_form_args;
        return (array)$sb_contact_form_args;
    }

    public static function modal($args = array()) {
        self::set_modal_argument($args);
        sb_theme_get_content('modal');
    }

    public static function carousel($args = array()) {
        self::set_carousel_argument($args);
        sb_theme_get_content('carousel');
    }

    public static function get_woocommerce_template($slug, $name = '') {
        wc_get_template_part($slug, $name);
    }

    public static function get_content_woocommerce($name) {
        self::get_content('woocommerce/' . $name);
    }

    public static function get_content($name) {
        sb_theme_get_content($name);
    }

    public static function get_meta_box($name) {
        sb_theme_get_meta_box($name);
    }

    public static function get_custom_loop($name) {
        sb_get_custom_loop($name);
    }

    public static function get_ads_items() {
        global $sb_theme_ads_items;
        if(!is_array($sb_theme_ads_items)) {
            $sb_theme_ads_items = array();
        }
        return $sb_theme_ads_items;
    }

    public static function register_ads($id, $name, $description) {
        global $sb_theme_ads_items;
        if(!is_array($sb_theme_ads_items)) {
            $sb_theme_ads_items = array();
        }
        if(array_key_exists($id, $sb_theme_ads_items)) {
            return;
        }
        $new_ads_item = array(
            'id' => $id,
            'name' => $name,
            'description' => $description
        );
        $sb_theme_ads_items[$id] = $new_ads_item;
    }

    public static function get_post_type_ads_name() {
        return SB_Core::get_post_type_ads_name();
    }

    public static function get_ads_by_position($ads_id) {
        return SB_Ads::get_by_position($ads_id);
    }

    public static function get_custom_metabox($name) {
        sb_get_custom_meta_box($name);
    }

    public static function get_custom_content($name) {
        sb_get_custom_content($name);
    }

    public static function get_custom_module($name) {
        sb_get_custom_module($name);
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

    public static function the_search_query_text() {
        echo get_search_query();
    }

    public static function the_feedburner_form($args = array()) {
        $name = isset($args['name']) ? $args['name'] : '';
        $locale = isset($args['locale']) ? $args['locale'] : 'en_US';
        $submit_button_text = isset($args['submit_button_text']) ? $args['submit_button_text'] : __('Đăng ký', 'sb-theme');
        $placeholder = isset($args['placeholder']) ? $args['placeholder'] : __('Nhập địa chỉ email của bạn', 'sb-theme') . SB_THEME_THREE_DOT;
        ?>
        <form class="feedburner-form" action="https://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('https://feedburner.google.com/fb/a/mailverify?uri=<?php echo $name; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
            <?php do_action('sb_theme_feedburner_before'); ?>
            <input class="email-field" type="text" placeholder="<?php echo $placeholder; ?>" name="email" autocomplete="off">
            <input type="hidden" value="<?php echo $name; ?>" name="uri">
            <input type="hidden" name="loc" value="<?php echo $locale; ?>">
            <input class="btn btn-submit" type="submit" value="<?php echo $submit_button_text; ?>">
            <?php do_action('sb_theme_feedburner_after'); ?>
        </form>
        <?php
    }

    public static function get_loading_image($url = '') {
        if(!class_exists('SB_Core')) {
            return '';
        }
        if(empty($url)) {
            $url = SB_CORE_URL . '/images/ajax-loader.gif';
        }
        return sprintf('<img alt="" src="%s" class="ajax-loading loading-image hidden">', $url);
    }

    public static function addthis() {
        echo '<div class="addthis_native_toolbox addthis_toolbox addthis-share socials-share"></div>';
    }

	public static function use_user_custom_style() {
		return SB_Tool::user_use_custom_style();
	}

    public static function use_custom_login() {
        $result = SB_Tool::use_custom_login();
        return $result;
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