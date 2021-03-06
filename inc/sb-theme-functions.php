<?php
defined('ABSPATH') or die('Please do not pip me!');

function sb_core_get_default_theme() {
    SB_Core::get_default_theme();
}

function sb_build_meta_name( $meta_name ) {
    return SB_Core::build_meta_box_field_name( $meta_name );
}

function sb_meta_box_nonce() {
    wp_nonce_field( 'sb_meta_box', 'sb_meta_box_nonce' );
    wp_nonce_field('sb_theme_meta_box', 'sb_theme_meta_box_nonce');
}

function sb_post_meta_box_nonce() {
    sb_meta_box_nonce();
}

function sb_term_meta_nonce() {
    wp_nonce_field( 'sb_term_meta', 'sb_term_meta_nonce' );
    wp_nonce_field('sb_theme_term_meta', 'sb_theme_term_meta_nonce');
}

function sb_core_owner() {
    return SB_Core::is_founder();
}

function sb_core_get_image_url( $name ) {
    return sb_theme_get_image_url($name);
}

function sb_core_ajax_loader($image = '') {
    if(empty($image)) {
        $image = apply_filters('sb_theme_main_ajax_loader_image', $image);
        if(empty($image)) {
            $image = sb_core_get_image_url('icon-ajax-loader.gif');
        }
    }
    echo '<div class="sb-ajax-loader center sb-theme-ajax-loader sb-theme-ajax-full-page"><img alt="" src="' . $image . '"></div>';
}

function sb_admin_need_ui() {
    return apply_filters( 'sb_admin_need_ui', false );
}

function sb_get_core_template_part( $name ) {
    $name .= '.php';
    include SB_CORE_INC_PATH . '/' . $name;
}

function sb_core_get_loop( $name ) {
    sb_get_core_template_part( 'loop/' . $name );
}

function sb_core_get_ajax( $name ) {
    sb_get_core_template_part( 'ajax/' . $name );
}

function sb_core_get_content( $name ) {
    sb_get_core_template_part( 'content/' . $name );
}

function sb_theme_error_checking() {
    if(!is_admin()) {
        if(!defined('SB_CORE_VERSION')) {
            wp_die(sprintf(__('You must install and activate plugin %1$s first! Click here to %2$s.', 'sb-theme'), '<a href="https://wordpress.org/plugins/sb-core/">SB Core</a>', sprintf('<a target="_blank" href="%1$s">%2$s</a>', admin_url('themes.php'), __('go back', 'sb-theme'))));
            exit;
        } elseif(!defined('SB_THEME_VERSION') || !class_exists('SB_Theme')) {
            wp_die(sprintf(__('It looks like you\'re using incorrect %1$s pack! Click here to %2$s.', 'sb-theme'), '<strong>SB Theme Core</strong>', sprintf('<a target="_blank" href="%1$s">%2$s</a>', 'https://github.com/skylarkcob/sb-theme/', __('re-download', 'sb-theme'))));
            exit;
        }
    }
}

if(!isset($content_width)) {
    $content_width = 800;
}

function sb_theme_register_sidebar($sidebar_id, $sidebar_name, $sidebar_description) {
    SB_Theme::register_sidebar($sidebar_id, $sidebar_name, $sidebar_description);
}

function sb_get_template_part($slug, $name = null) {
    $slug = 'sb-theme/inc/' . $slug;
    get_template_part($slug, $name);
}

function sb_theme_get_content($slug, $name = null) {
    sb_get_template_part('content/' . $slug, $name);
}

function sb_theme_get_ajax($slug, $name = null) {
    sb_get_template_part('ajax/' . $slug, $name);
}

function sb_theme_get_meta_box($slug, $name = null) {
    sb_get_template_part('meta-box/' . $slug, $name);
}

function sb_theme_get_template($slug, $name = null) {
    sb_get_template_part('templates/' . $slug, $name);
}

function sb_get_custom_template_part($slug, $name = null) {
    $slug = 'sb-theme-custom/inc/' . $slug;
    get_template_part($slug, $name);
}

function sb_get_custom_loop($slug, $name = null) {
    sb_get_custom_template_part('loop/' . $slug, $name);
}

function sb_get_custom_meta_box($slug, $name = null) {
    sb_get_custom_template_part('meta-box/' . $slug, $name);
}

function sb_get_custom_content($slug, $name = null) {
    sb_get_custom_template_part('content/' . $slug, $name);
}

function sb_get_custom_content_default_query($slug, $name = null) {
    while(have_posts()) {
        the_post();
        sb_get_custom_content($slug);
    }
}

function sb_theme_load_jquery_raty() {
    wp_enqueue_style('jquery-raty-style', SB_THEME_LIB_URL . '/jquery-raty/jquery.raty.css');
    wp_enqueue_script('jquery-raty', SB_THEME_LIB_URL . '/jquery-raty/jquery.raty.js', array('jquery'), false, true);
}

function sb_theme_load_jquery_datetimepicker() {
    wp_enqueue_style('datetimepicker-style', SB_THEME_LIB_URL . '/datetimepicker/jquery.datetimepicker.css');
    wp_enqueue_script('datetimepicker', SB_THEME_LIB_URL . '/datetimepicker/jquery.datetimepicker.js', array('jquery'), false, true);
}

function sb_theme_load_zeroclipboard() {
    wp_enqueue_script('zeroclipboard', SB_THEME_LIB_URL . '/zeroclipboard/ZeroClipboard.min.js', array('jquery'), false, true);
}

function sb_theme_load_owl_carousel() {
    wp_register_style( 'owl-carousel-style', SB_THEME_LIB_URL. '/owl-carousel/owl.carousel.css' );
    wp_register_style( 'owl-carousel-theme-style', SB_THEME_LIB_URL. '/owl-carousel/owl.theme.css', array('owl-carousel-style') );

    wp_register_script('owl-carousel', SB_THEME_LIB_URL. '/owl-carousel/owl.carousel.js', array('jquery'), false, true);

    wp_enqueue_style('owl-carousel-theme-style');
    wp_enqueue_script('owl-carousel');
}

function sb_get_custom_ajax($slug, $name = null) {
    sb_get_custom_template_part('ajax/' . $slug, $name);
}

function sb_get_custom_module($slug, $name = null) {
    sb_get_custom_template_part('module/' . $slug, $name);
}

function sb_get_custom_modal($slug, $name = null) {
    sb_get_custom_template_part('modal/' . $slug, $name);
}

function sb_get_custom_carousel($slug, $name = null) {
    sb_get_custom_template_part('carousel/' . $slug, $name);
}

function sb_theme_add_term_meta($args = array()) {
    if(!class_exists('SB_Term_Meta')) {
        require SB_CORE_INC_PATH . '/class-sb-term-meta.php';
    }
    $term_meta = new SB_Term_Meta($args);
}

function sb_theme_term_meta_field_term_select($args = array()) {
    if(!class_exists('SB_Term_Field')) {
        require SB_CORE_INC_PATH . '/class-sb-term-field.php';
    }
    SB_Term_Field::term_select($args);
}

function sb_theme_add_setting_field($field_id, $field_title, $callback) {
    SB_Admin_Custom::add_setting_field($field_id, $field_title, 'sb_theme_setting_section', $callback, 'sb_theme');
}

function sb_theme_add_smtp_email_setting_field($id, $title, $callback) {
    SB_Admin_Custom::add_setting_field($id, $title, 'sb_theme_smtp_email_setting_section', $callback, 'sbt_smtp_email');
}

function sb_theme_add_utilities_setting_field($field_id, $field_title, $callback) {
    SB_Admin_Custom::add_setting_field($field_id, $field_title, 'sb_utilities_section', $callback, 'sb_utilities');
}

function sb_theme_add_statistics_setting_field($field_id, $field_title, $callback) {
    SB_Admin_Custom::add_setting_field($field_id, $field_title, 'sb_statistics_section', $callback, 'sb_statistics');
}

function sb_theme_the_logo() {
    sb_theme_get_content('sb-theme-logo');
}

function sb_theme_support($args = null) {
    if(null === $args) {
        return;
    }
    if(is_array($args)) {
        foreach($args as $feature) {
            add_theme_support($feature);
        }
    } else {
        add_theme_support($args);
    }
}

function sb_get_theme_support() {
    global $_wp_theme_features;
    return $_wp_theme_features;
}

function sb_theme_support_shop() {
    return sb_theme_check_support('shop');
}

function sb_theme_support_addthis() {
    return sb_theme_check_support('addthis');
}

function sb_theme_support_term_meta() {
    return sb_theme_check_support('term_meta');
}

function sb_theme_check_support($name) {
    return current_theme_supports($name);
}

function sb_theme_the_post_thumbnail_crop($width, $height) {
    SB_Post::the_thumbnail_crop_html($width, $height);
}

function sb_theme_the_post_thumbnail_crop_only_image($width, $height) {
    SB_Post::the_thumbnail_crop_only_image_html($width, $height);
}

function sb_theme_the_post_thumbnail_only_image_html($width, $height) {
    $args = array(
        'width' => $width,
        'height' => $height,
        'crop' => false
    );
    SB_Post::the_thumbnail_only_image_html($args);
}

function sb_theme_the_post_thumbnail_only_link_image($width, $height) {
    SB_Post::the_thumbnail_crop_only_link_image_html($width, $height);
}

function sb_theme_the_post_thumbnail_crop_by_id($post_id, $width, $height) {
    SB_Post::the_thumbnail_crop_html_by_id($post_id, $width, $height);
}

function sb_theme_the_post_thumbnail($args = array()) {
    SB_Post::the_thumbnail($args);
}

function sb_theme_change_default_image_setting() {
    update_option('image_default_align', 'center');
	$link_to_type = SB_Option::get_media_link_to_type();
	if(empty($link_to_type)) {
		update_option('image_default_link_type', 'none');
	}
    update_option('image_default_size', 'large');
}

function sb_theme_get_custom_image_url($name) {
    return SB_THEME_CUSTOM_URL . '/images/' . $name;
}

function sb_theme_get_image_url($name) {
    return SB_THEME_URL . '/images/' . $name;
}

function sb_theme_paginate($args = array()) {
    SB_Theme::the_paginate($args);
}

function sb_theme_comment_template() {
    SB_Theme::the_comment_template();
}

function sb_theme_update_default_options() {
    sb_theme_change_default_image_setting();
    SB_Core::regenerate_htaccess_file();
    SB_Option::edit_breadcrumb_sep();
    SB_Option::edit_bcn_breadcrumb_sep();
	update_option('timezone_string', 'Asia/Ho_Chi_Minh');
	update_option('date_format', 'd/m/Y');
	update_option('page_comments', 1);
	update_option('comments_per_page', 15);
	update_option('default_comments_page', 'oldest');
	update_option('comment_order', 'desc');
	sb_theme_update_comment_spam_text();
	$permalink_struct = SB_Option::get_permalink_struct();
	if(empty($permalink_struct)) {
		SB_Core::update_default_permalink_struct();
	}
}

function sb_theme_counter() {
    if(is_admin()) {
        return;
    }
    sb_theme_visits_counter();
    sb_theme_visitor_online_counter();
}

function sb_theme_visitor_online_counter() {
    $user_id = 0;
    if(SB_User::is_logged_in()) {
        $user_id = SB_User::get_current()->ID;
    } elseif(SB_Detect::is_bots()) {
        $user_id = -1;
    }
    $support_bot_count = SB_Option::get_statistics_switch('bots_statistics');
    if($user_id < 0 && !(bool)$support_bot_count) {
        return;
    }
    $user_online_counted = SB_PHP::get_session('sb_count_online');
    $current_ip = SB_Detect::get_visitor_ip();
    $uname = php_uname();
    $local_ip = SB_PHP::get_pc_ip();
    $user_agent = SB_PHP::get_user_agent();
    $current_date_time = SB_Core::get_current_date_time();
    $sb_user_online_option = get_option('sb_user_online');
    $sb_user_online_option = (array)$sb_user_online_option;
    $new_sb_user_online_option = array();
    $same_user = false;
    $count_user_online = 0;
    $count_bot_online = 0;
    $count_guest_online = 0;
    foreach($sb_user_online_option as $item) {
        $ip = isset($item['ip']) ? $item['ip'] : '';
        $last = isset($item['last']) ? $item['last'] : 0;
        $id = isset($item['id']) ? $item['id'] : 0;
        $pc_info = isset($item['uname']) ? $item['uname'] : '';
        $pc_ip = isset($item['local_ip']) ? $item['local_ip'] : '';
        $agent = isset($item['user_agent']) ? $item['user_agent'] : '';
        $minutes = (strtotime($current_date_time) - $last)/60;
        $minutes = absint($minutes);
        if($minutes < 15) {
            array_push($new_sb_user_online_option, $item);
            if($id > 0) {
                $count_user_online++;
            }
            if($ip == $current_ip && $id == $user_id && $pc_info == $uname && $pc_ip == $local_ip && $user_agent == $agent) {
                $same_user = true;
            }
            if(SB_Detect::is_bots()) {
                $count_bot_online++;
            }
            if($id == 0) {
                $count_guest_online++;
            }
        }
    }
    if(!$same_user) {
        $user_online_item = array(
            'ip' => $current_ip,
            'last' => strtotime($current_date_time),
            'id' => $user_id,
            'uname' => $uname,
            'local_ip' => $local_ip,
            'user_agent' => $user_agent
        );
        array_push($new_sb_user_online_option, $user_online_item);
    }
    update_option('sb_user_online', $new_sb_user_online_option);
    update_option('visitor_online', count($new_sb_user_online_option));
    update_option('user_online', $count_user_online);
    update_option('bot_online', $count_bot_online);
    update_option('guest_online', $count_guest_online);
}

function sb_theme_visits_counter() {
    $support_bot_count = SB_Option::get_statistics_switch('bots_statistics');
    if(!(bool)$support_bot_count && SB_Detect::is_bots()) {
        return;
    }
    $visits_session = intval(SB_PHP::get_session('sb_visits'));
    $visits_cookie = intval(SB_PHP::get_cookie('sb_visits'));
    $current_date_time = SB_Core::get_current_date_time();
    if($visits_session != 1 && $visits_cookie != 1) {
        SB_PHP::set_session('sb_visits', 1);
        SB_PHP::set_cookie_minute('sb_visits', 1, 15);

        $daily_stats = SB_Option::get_statistics_switch('daily_statistics');
        if((bool)$daily_stats) {
            $today_date = strtotime(date('Y-m-d', strtotime($current_date_time)));
            update_option('sb_today', $today_date);

            $key = 'visits_today';
            $count = intval(get_option($key));
            $tomorrow_date = strtotime('+1 day', $today_date);
            $yesterday_date = strtotime('-1 day', $today_date);
            $tomorrow_option = SB_Option::get_tomorrow();
            $yesterday_option = SB_Option::get_yesterday();
            if(SB_Option::get_tomorrow() == '' || SB_Option::get_yesterday() == '' || ($tomorrow_option - $today_date) < 0  || $tomorrow_date != $tomorrow_option || $yesterday_date != $yesterday_option) {
                update_option('sb_tomorrow', $tomorrow_date);
                update_option('sb_yesterday', $yesterday_date);
            }
            if($today_date == $tomorrow_option) {
                update_option('visits_yesterday', $count);
                $count = 0;
                update_option('sb_tomorrow', $tomorrow_date);
                update_option('sb_yesterday', $yesterday_date);
            }
            $count++;
            update_option($key, $count);
        }

        $weekly_stats = SB_Option::get_statistics_switch('weekly_statistics');
        if((bool)$weekly_stats) {
            $key = 'visits_this_week';
            $count = intval(get_option($key));
            $count++;
            update_option($key, $count);
        }

        $monthly_stats = SB_Option::get_statistics_switch('monthly_statistics');
        if((bool)$monthly_stats) {
            $key = 'visits_this_month';
            $count = intval(get_option($key));
            $count++;
            update_option($key, $count);
        }

        SB_Option::update_visits();
    }
}

function sb_theme_track_post_views() {
    if(!is_admin() && (is_singular() || is_single()) && !SB_Core::wp_postviews_activated() && !SB_Detect::is_bots()) {
        $post_id = get_the_ID();
        if(empty($post_id)) {
            global $wp_query;
            $post = $wp_query->post;
            if(SB_Core::is_error($post)) {
                return;
            }
            $post_id = $post->ID;
        }
        $post_views_session_key = 'post_' . $post_id . '_views';
        $post_views_session = intval(SB_PHP::get_session($post_views_session_key));
        if($post_views_session != 1) {
            SB_PHP::set_session($post_views_session_key, 1);
            SB_Post::update_views($post_id);
        }
    }
}

function sb_theme_clean_wp_files() {
    $file_path = trailingslashit(ABSPATH) . 'readme.html';
    SB_PHP::delete_file($file_path);
    $file_path = trailingslashit(ABSPATH) . 'license.txt';
    SB_PHP::delete_file($file_path);
}

function sb_comment_navigation($type) {
    if(get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
        <nav id="comment-nav-<?php echo $type; ?>" class="navigation comment-navigation">
            <h4 class="screen-reader-text"><?php _e('Phân trang bình luận', 'sb-theme'); ?></h4>
            <div class="nav-previous"><?php previous_comments_link(__('&larr; Bình luận cũ hơn'), 'sb-theme'); ?></div>
            <div class="nav-next"><?php next_comments_link(__('Bình luận mới hơn &rarr;', 'sb-theme')); ?></div>
        </nav>
    <?php endif;
}

function sb_comment_template() {
    if(!SB_Comment::is_spam_session() && (comments_open() || get_comments_number())) {
        comments_template();
    } else {
        echo '<div class="no-comment"></div>';
    }
}

function sb_comment_form_args() {
    global $user_identity;
    $lang = SB_Core::get_language();
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ($req ? ' aria-required="true"' : '' );
    $reply_title = ('vi' == $lang) ? 'Gửi bình luận' : __('Leave a reply', 'sb-theme');
    $logged_in_as_text = ('vi' == $lang) ? sprintf('Bạn đang đăng nhập với tên tài khoản %s', sprintf(' <a href="%1$s">%2$s</a>. <a href="%3$s" title="%4$s">%5$s?</a>', admin_url( 'profile.php' ), esc_attr( $user_identity ), wp_logout_url(apply_filters('the_permalink', get_permalink( ) ) ), __('Thoát khỏi tài khoản này', 'sb-theme'), __('Thoát', 'sb-theme'))) : sprintf(__('Logged in as %s', 'sb-theme'), sprintf(' <a href="%1$s">%2$s</a>. <a href="%3$s" title="%4$s">%5$s?</a>', admin_url( 'profile.php' ), esc_attr( $user_identity ), wp_logout_url(apply_filters('the_permalink', get_permalink( ) ) ), __('Log out of this account', 'sb-theme'), __('Log out', 'sb-theme')));
    $comment_notes_after_text = ('vi' == $lang) ? sprintf('Bạn có thể sử dụng những thẻ %1$s được liệt kê như sau: %2$s', '<abbr title="Ngôn ngữ đánh dấu siêu văn bản bằng thẻ">HTML</abbr>', ' <code>' . allowed_tags() . '</code>' ) : sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <code>' . allowed_tags() . '</code>' );
    $args = array(
        'fields' => apply_filters( 'comment_form_default_fields', array(
                'author' => '<p class="comment-form-author name">' . '<label for="author">' . __( 'Tên', 'sb-theme' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="author" placeholder="'.__('Họ và tên', 'sb-theme').' *" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $aria_req . ' class="sb-author-info"></p>',
                'email' => '<p class="comment-form-email email">' . '<label for="email">' . __( 'Địa chỉ email', 'sb-theme' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="email" placeholder="'.__('Địa chỉ email', 'sb-theme').' *" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" ' . $aria_req . ' class="sb-author-info"></p>',
                'url' => '<p class="comment-form-url website">' . '<label for="url">' . __( 'Trang web', 'sb-theme' ) . '</label>' . '<input id="url" name="url" placeholder="'.__('Địa chỉ trang web', 'sb-theme').'" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" class="sb-author-info"></p>'
            )
        ),
        'comment_field'			=> '<p class="comment-form-comment">' . '<label for="comment">' . __( 'Nội dung', 'sb-theme' ) . '</label>' . '<textarea id="comment" name="comment" placeholder="" aria-required="true" class="sb-comment-msg"></textarea></p>',
        'comment_notes_before'	=> '<p class="comment-notes before">' . __( 'Địa chỉ email của bạn sẽ được giữ bí mật.', 'sb-theme' ) . __( $req ? ' '.sprintf(__('Những mục được đánh dấu %s là bắt buộc.', 'sb-theme'), '(*)') : '' ) . '</p>',
        'comment_notes_after'	=> '<p class="form-allowed-tags comment-notes after">' . $comment_notes_after_text . '</p>',
        'must_log_in'			=> '<p class="must-log-in">' . sprintf(__( 'Bạn phải %s trước khi tiến hành gửi bình luận.', 'sb-theme' ), sprintf('<a href="%1$s">%2$s</a>', wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ), __('đăng nhập', 'sb-theme')) ) . '</p>',
        'logged_in_as'			=> '<p class="logged-in-as">' . $logged_in_as_text . '</p>',
        'title_reply'			=> '<a id="leaveyourcomment"></a><span class="comment-title">' . $reply_title . '</span>',
        'label_submit'			=> ('vi' == $lang) ? 'Gửi bình luận' : __('Post comment', 'sb-theme'),
        'title_reply_to'		=>  __( 'Trả lời %s', 'sb-theme' ),
        'cancel_reply_link'		=> __('Hủy trả lời', 'sb-theme')
    );
    if(SB_Comment::disable_website_url()) {
        unset($args['fields']['url']);
    }
    return $args;
}

function sb_comment_allowed() {
    return (bool) comments_open(get_the_ID());
}

function sb_user_can_post_comment() {
    return apply_filters('sb_user_can_post_comment', true);
}

function sb_comments() {
    sb_theme_get_content('sb-comment-template');
}

function sb_comment_spam($commentdata) {
    return SB_Comment::is_spam($commentdata);
}

function sb_comment_empty_spam_cron_function(){
    if(SB_Comment::enable_auto_empty_spam()) {
        SB_Comment::delete_spam();
    }
}

function sb_login_page_page_template_arg() {
    $templates = array(
        array(
            'file_name' => SB_LOGIN_PAGE_ACCOUNT_TEMPLATE,
            'title' => __('Account', 'sb-theme')
        ),
        array(
            'file_name' => SB_LOGIN_PAGE_LOGIN_TEMPLATE,
            'title' => __('Login', 'sb-theme')
        ),
        array(
            'file_name' => SB_LOGIN_PAGE_LOST_PASSWORD_TEMPLATE,
            'title' => __('Lost password', 'sb-theme')
        ),
        array(
            'file_name' => SB_LOGIN_PAGE_REGISTER_TEMPLATE,
            'title' => __('Register', 'sb-theme')
        )
    );
    $args = array(
        'plugin_path' => SB_THEME_INC_PATH,
        'folder_path' => 'templates',
        'templates' => $templates
    );
    return $args;
}

function sb_login_page_create_page_templates() {
    $args = sb_login_page_page_template_arg();
    SB_Core::create_page_template($args);
}

function sb_login_page_delete_page_templates() {
    $args = sb_login_page_page_template_arg();
    SB_Core::delete_page_template($args);
}

function sb_login_page_get_user_page_url($args = array()) {
    $page_id = isset($args['page_id']) ? absint($args['page_id']) : 0;
    $page_template = isset($args['page_template']) ? $args['page_template'] : '';
    $action = isset($args['action']) ? $args['action'] : '';
    $url = '';
    if($page_id > 0) {
        $template_name = get_post_meta($page_id, '_wp_page_template', true);
        if('page-templates/' . $page_template == $template_name) {
            if(SB_Post::get_status($page_id) == 'publish') {
                $url = get_permalink($page_id);
            }
        }
    }
    if(empty($url)) {
        $url = sb_login_page_get_page_account_url();
        if(!empty($url)) {
            $url = add_query_arg(array('action' => $action), $url);
        }
    }
    return $url;
}

function sb_login_page_get_page_lost_password_id() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_lost_password']) ? $options['login_page']['page_lost_password'] : 0;
    $value = intval($value);
    return $value;
}

function sb_login_page_get_page_lost_password_url() {
    $args = array(
        'page_id' => sb_login_page_get_page_lost_password_id(),
        'page_template' => SB_LOGIN_PAGE_LOST_PASSWORD_TEMPLATE,
        'action' => 'lostpassword'
    );
    return sb_login_page_get_user_page_url($args);
}

function sb_login_page_get_page_register_id() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_register']) ? $options['login_page']['page_register'] : 0;
    $value = intval($value);
    return $value;
}

function sb_login_page_get_page_register_url() {
    $args = array(
        'page_id' => sb_login_page_get_page_register_id(),
        'page_template' => SB_LOGIN_PAGE_REGISTER_TEMPLATE,
        'action' => 'register'
    );
    return sb_login_page_get_user_page_url($args);
}

function sb_login_page_get_page_account_id() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_account']) ? $options['login_page']['page_account'] : 0;
    $value = intval($value);
    return $value;
}

function sb_login_page_get_page_account_url() {
    $page_account_id = sb_login_page_get_page_account_id();
    $login_url = '';
    if($page_account_id > 0) {
        $template_name = get_post_meta($page_account_id, '_wp_page_template', true);
        if('page-templates/' . SB_LOGIN_PAGE_ACCOUNT_TEMPLATE == $template_name) {
            if(SB_Post::get_status($page_account_id) == 'publish') {
                $login_url = get_permalink($page_account_id);
            }
        }
    }
    return $login_url;
}

function sb_login_page_get_page_login_id() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_login']) ? $options['login_page']['page_login'] : 0;
    $value = intval($value);
    return $value;
}

function sb_login_page_get_page_login_url() {
    $args = array(
        'page_id' => sb_login_page_get_page_login_id(),
        'page_template' => SB_LOGIN_PAGE_LOGIN_TEMPLATE,
        'action' => 'login'
    );
    return sb_login_page_get_user_page_url($args);
}

function sb_login_page_get_login_redirect_url() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['login_redirect']) ? $options['login_page']['login_redirect'] : 'current';
    $url = SB_Core::get_current_url();
    switch($value) {
        case 'home':
            $url = home_url('/');
            break;
        case 'profile':
            $url = SB_User::get_profile_url();
            break;
        case 'dashboard':
            $url = admin_url();
            break;
    }
    return $url;
}

function sb_login_page_signup_required_fields($args = array()) {
    $result = apply_filters('sb_login_page_signup_required_fields', $args);
    $defaults = array(
        'email',
        'password'
    );
    $result = wp_parse_args($result, $defaults);
    return $result;
}

function sb_login_page_get_logout_redirect_url() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['logout_redirect']) ? $options['login_page']['logout_redirect'] : 'home';
    $url = home_url('/');
    switch($value) {
        case 'current':
            $url = SB_Core::get_current_url();
            break;
    }
    return $url;
}

function sb_login_page_signup_captcha() {
    return SB_Tool::custom_signup_use_captcha();
}

function sb_login_page_use_captcha() {
    return SB_Tool::custom_login_use_captcha();
}

function sb_login_page_use_sb_login() {
    return SB_Tool::use_custom_login();
}

function sb_login_page_is_login_custom_page() {
    return is_page_template('page-template-login.php');
}

function sb_login_page_is_register_custom_page() {
    return is_page_template('page-template-register.php');
}

function sb_login_page_is_lost_password_custom_page() {
    return is_page_template('page-template-lost-password.php');
}

function sb_login_page_is_account_custom_page() {
    return is_page_template('page-template-account.php');
}

function sb_login_page_can_deactivate_account() {
    return apply_filters('sb_login_page_can_deactivate_account', true);
}

function sb_login_page_user_signup($args = array()) {
    $email = isset($args['email']) ? $args['email'] : '';
    $password = isset($args['password']) ? $args['password'] : '';
    $username = isset($args['username']) ? $args['username'] : '';
    if(empty($username)) {
        $username = $email;
    }
    $user_args = array(
        'username' => $username,
        'email' => $email,
        'password' => $password
    );
    $user_args = wp_parse_args($user_args, $args);
    $user_id = SB_User::add($user_args);
    if($user_id > 0) {
        $user = SB_User::get_by('id', $user_id);
        $name = isset($args['name']) ? $args['name'] : '';
        $name_arr = explode(' ', $name);
        $first_name = array_pop($name_arr);
        $last_name = trim(implode(' ', $name_arr));
        $nice_name = SB_PHP::remove_vietnamese($name);
        $nice_name = str_replace(' ', '-', $nice_name);
        $user_data = array(
            'user_nicename' => $nice_name,
            'display_name' => $name,
            'first_name' => $first_name,
            'last_name' => $last_name
        );
        SB_User::update($user, $user_data);
        $phone = isset($args['phone']) ? $args['phone'] : '';
        SB_User::update_meta($user_id, 'phone', $phone);
        $address = isset($args['address']) ? $args['address'] : '';
        SB_User::update_meta($user_id, 'address', $address);
        $check_activation = isset($args['check_activation']) ? $args['check_activation'] : true;
        if(!isset($args['check_activation'])) {
            $check_activation = isset($args['verify_email']) ? $args['verify_email'] : true;
        }
        if($check_activation) {
            SB_User::update_status($user, 6);
            SB_User::generate_activation_code($user);
            SB_User::send_signup_verify_email($user);
        }
        $force_login = isset($args['force_login']) ? (bool)$args['force_login'] : false;
        if($force_login) {
            SB_User::login($email, $password, true);
        }
        return true;
    }
    return false;
}

function sb_login_page_signup_ajax($args = array()) {
    $result = array(
        'redirect' => ''
    );
    $email = isset($args['email']) ? trim($args['email']) : '';
    $phone = isset($args['phone']) ? trim($args['phone']) : '';
    $name = isset($args['name']) ? trim($args['name']) : '';
    $password = isset($args['password']) ? trim($args['password']) : '';
    $address = isset($args['address']) ? trim($args['address']) : '';
    $result['successful'] = true;
    $result['valid'] = 1;
    $result['success_field'] = '<input type="hidden" value="1" class="success-field" name="singup-success">';
    $use_captcha = isset($args['use_captcha']) ? (bool)$args['use_captcha'] : true;

    if(!SB_PHP::is_email_valid($email)) {
        $result['successful'] = false;
        $result['valid'] = 0;
        $result['message'] = __('Địa chỉ email của bạn không đúng', 'sb-theme');
    } elseif(email_exists($email) || username_exists($email)) {
        $result['successful'] = false;
        $result['valid'] = 0;
        $result['message'] = __('Địa chỉ email của bạn đã tồn tại', 'sb-theme');
    } elseif(sb_login_page_signup_captcha() && $use_captcha) {
        if(sb_login_page_use_captcha()) {
            $captcha = isset($args['captcha']) ? $args['captcha'] : '';
            if(!SB_Core::check_captcha($captcha)) {
                $result['valid'] = 0;
                $result['message'] = __('Mã bảo mật bạn nhập không đúng', 'sb-theme');
                $result['successful'] = false;
            }
            $result['captcha'] = SB_Captcha::generate_image();
        }
    }

    $insert = isset($args['insert']) ? (bool)$args['insert'] : false;
    if($result['successful'] && $insert) {
        $success = sb_login_page_user_signup($args);
        if(!$success) {
            $result['valid'] = 0;
            $result['message'] = __('Đã có lỗi xảy ra, xin vui lòng thử lại', 'sb-theme');
            $result['successful'] = false;
        } else {
            $result['redirect'] = SB_User::get_login_url();
        }
    }
    return $result;
}

function sb_login_page_login_ajax($args = array()) {
    $count_logged_in_fail = isset($args['count_logged_in_fail']) ? absint($args['count_logged_in_fail']) : 0;
    $cookie = isset($args['cookie']) ?  absint($args['cookie']) : 0;
    $result = array();
    if($count_logged_in_fail < 3) {
        $login_email = isset($args['email']) ? trim($args['email']) : '';
        $login_password = isset($args['password']) ? trim($args['password']) : '';
        $remember = isset($args['remember']) ? (bool)$args['remember'] : true;
        $check_activation = isset($args['check_activation']) ? (bool)$args['check_activation'] : true;
        $member = SB_User::get_by_email_or_login($login_email);
        if(SB_User::is($member) && SB_User::is_awaiting_activation($member->ID) && $check_activation) {
            $result['logged_in'] = false;
            $result['user_id'] = $member->ID;
            $result['message'] = __('Tài khoản của bạn chưa được kích hoạt', 'sb-theme');
            $result['redirect'] = SB_User::get_account_verify_url($member->ID);
        } else {
            $user = SB_User::login($login_email, $login_password, $remember);
            if(is_wp_error($user) || !SB_User::is($user)) {
                $result['logged_in'] = false;
                $result['user_id'] = -1;
                $result['message'] = __('Tài khoản hoặc mật khẩu không đúng, xin vui lòng thử lại.', 'sb-theme');
                SB_User::update_logged_in_fail_session();
            } else {
                $result['logged_in'] = true;
                $result['user_id'] = $user->ID;
            }
        }
    } else {
        $result['logged_in'] = false;
        $result['user_id'] = -1;
        if(1 != $cookie) {
            SB_User::update_logged_in_fail_session();
            SB_User::update_logged_in_fail_cookie();
            $result['block_login'] = true;
        }
    }
    if(!isset($result['redirect'])) {
        $result['redirect'] = '';
    }
    return $result;
}

if(!function_exists('sb_paginate')) {
    function sb_paginate($args = array()) {
        SB_Paginate::show($args);
    }
}

function sb_tab_widget_get_sidebars() {
    $options = SB_Option::get();
    $sidebar_count = isset($options['tab_widget']['sidebar']['count']) ? $options['tab_widget']['sidebar']['count'] : 0;
    $list_sidebars = array();
    for($i = 1; $i <= $sidebar_count; $i++) {
        $sidebar_name = isset($options['tab_widget']['sidebar'][$i]['name']) ? $options['tab_widget']['sidebar'][$i]['name'] : '';
        $sidebar_description = isset($options['tab_widget']['sidebar'][$i]['description']) ? $options['tab_widget']['sidebar'][$i]['description'] : '';
        $sidebar_id = isset($options['tab_widget']['sidebar'][$i]['id']) ? $options['tab_widget']['sidebar'][$i]['id'] : '';
        $sidebar = array(
            'id' => $sidebar_id,
            'name' => $sidebar_name,
            'description' => $sidebar_description
        );
        array_push($list_sidebars, $sidebar);
    }
    return $list_sidebars;
}

function sb_theme_get_social_login_facebook() {
    $facebook = SB_Option::get_social_login_app('facebook');
    $callback_url = isset($facebook['redirect_uri']) ? $facebook['redirect_uri'] : SB_User::get_login_url();
    $callback_url = SB_Core::trailing_slash_url($callback_url);
    $args = array(
        'app_id' => isset($facebook['app_id']) ? $facebook['app_id'] : '',
        'app_secret' => isset($facebook['app_secret']) ? $facebook['app_secret'] : '',
        'callback_url' => $callback_url
    );
    $sb_login = new SB_Login('facebook');
    $sb_login->set_facebook_arg($args);
    $sb_login->facebook_login();
    return $sb_login;
}

function sb_theme_get_social_login_google($code = '') {
    $google = SB_Option::get_social_login_app('google');
    $callback_url = isset($google['redirect_uri']) ? $google['redirect_uri'] : SB_User::get_login_url();
    $callback_url = SB_Core::trailing_slash_url($callback_url);
    $args = array(
        'client_id' => isset($google['client_id']) ? $google['client_id'] : '',
        'client_secret' => isset($google['client_secret']) ? $google['client_secret'] : '',
        'api_key' => isset($google['api_key']) ? $google['api_key'] : '',
        'callback_url' => $callback_url,
        'code' => $code
    );
    $sb_login = new SB_Login('google');
    $sb_login->set_google_arg($args);
    $sb_login->google_login();
    return $sb_login;
}

function sb_theme_social_login_facebook_error_message($message) {
    $message = SB_Message::login_error_message(SB_Message::connect_facebook_error());
    return $message;
}

function sb_theme_social_login_facebook_verify_error_message($message) {
    $message = SB_Message::login_error_message(SB_Message::facebook_account_not_verify());
    return $message;
}

function sb_theme_social_login_twitter_error_message($message) {
    $message = SB_Message::login_error_message(SB_Message::connect_twitter_error());
    return $message;
}

function sb_theme_social_login_twitter_verify_error_message($message) {
    $message = SB_Message::login_error_message(SB_Message::twitter_account_not_verify());
    return $message;
}

function sb_theme_social_login_google_error_message($message) {
    $message = SB_Message::login_error_message(SB_Message::connect_google_error());
    return $message;
}

function sb_theme_social_login_google_verify_error_message($message) {
    $message = SB_Message::login_error_message(SB_Message::google_account_not_verify());
    return $message;
}

function sb_theme_social_login_email_exists($message) {
    $message = SB_Message::login_error_message(SB_Message::email_exists_please_login());
    return $message;
}

function sb_theme_remove_facebook_login_special_char() {
    sb_theme_remove_special_char_on_url_script();
}

function sb_theme_remove_special_char_on_url_script() {
    ?>
    <script type="text/javascript">
        if(window.location.hash && (window.location.hash == '#' || window.location.hash == '#_=_')) {
            if(window.history && history.pushState) {
                window.history.pushState('', document.title, window.location.pathname);
            } else {
                var scroll = {
                    top: document.body.scrollTop,
                    left: document.body.scrollLeft
                };
                window.location.hash = '';
                document.body.scrollTop = scroll.top;
                document.body.scrollLeft = scroll.left;
            }
        }
    </script>
<?php
}

function sb_theme_admin_confirm_publish_post() {
    $text = 'Bạn có thật sự muốn đăng bài viết hay không?';
    if('vi' != SB_Core::get_language()) {
        $text = __('Are you sure you want to publish this post?', 'sb-theme');
    }
    ?>
    <script type="text/javascript">
        (function($){
            $('input[name="publish"]').on('click', function(e){
                if(!confirm('<?php echo $text; ?>')) {
                    if(!$.trim($('#title').val())) {
                        $('#title').focus();
                    }
                    return false;
                }
            });
        })(jQuery);
    </script>
    <?php
}

function sb_theme_social_login_facebook_check_data_back() {
    $sb_login = sb_theme_get_social_login_facebook();
    $facebook = $sb_login->get_facebook();
    $profile = isset($facebook['profile']) ? $facebook['profile'] : '';
    if(is_array($profile)) {
        $verified = isset($profile['verified']) ? (bool)$profile['verified'] : false;
        if($verified) {
            $user_email = isset($profile['email']) ? $profile['email'] : '';
            if(email_exists($user_email)) {
                $user = SB_User::get_by('email', $user_email);
                if(SB_User::is($user)) {
                    $facebook_integrated = (bool)SB_User::get_meta($user->ID, 'facebook_integrated');
                    if($facebook_integrated || (is_page() && isset($profile['verified']) && (bool)$profile['verified'])) {
                        SB_User::force_login($user->ID);
                        wp_redirect(sb_login_page_get_login_redirect_url());
                        exit;
                    } else {
                        add_filter('sb_theme_login_message', 'sb_theme_social_login_email_exists');
                    }
                } else {
                    add_filter('sb_theme_login_message', 'sb_theme_social_login_email_exists');
                }
            } else {
                $args = array(
                    'email' => $user_email,
                    'password' => wp_generate_password(),
                    'check_activation' => false,
                    'force_login' => true
                );
                if(SB_Membership::is_paid_membership_enabled()) {
                    $args['role'] = 'standard';
                }
                $result = sb_login_page_user_signup($args);
                if($result) {
                    $user = SB_User::get_by('email', $user_email);
                    $user_id = $user->ID;
                    if(SB_User::is($user)) {
                        SB_User::update_meta($user_id, 'facebook_profile', $profile);
                        SB_User::update_meta($user_id, 'facebook_integrated', 1);
                        $first_name = isset($profile['first_name']) ? $profile['first_name'] : '';
                        $last_name = isset($profile['last_name']) ? $profile['last_name'] : '';
                        $middle_name = isset($profile['middle_name']) ? $profile['middle_name'] : '';
                        if(!empty($last_name) && !empty($middle_name)) {
                            $last_name .= ' ' . $middle_name;
                        }
                        $gender = isset($profile['gender']) ? $profile['gender'] : '';
                        $name = isset($profile['name']) ? $profile['name'] : '';
                        if(empty($name)) {
                            $name = $last_name . ' ' . $first_name;
                        }
                        $user_data = array(
                            'display_name' => $name,
                            'first_name' => $first_name,
                            'last_name' => $last_name
                        );
                        SB_User::update($user_id, $user_data);
                        SB_User::update_meta($user_id, 'gender', SB_Core::esc_gender_sql($gender));
                    }
                    wp_redirect(sb_login_page_get_login_redirect_url());
                    exit;
                } else {
                    add_filter('sb_theme_login_message', 'sb_theme_social_login_facebook_error_message');
                }
            }
        } else {
            add_filter('sb_theme_login_message', 'sb_theme_social_login_facebook_verify_error_message');
        }
    } else {
        add_filter('sb_theme_login_message', 'sb_theme_social_login_facebook_error_message');
    }
}

function sb_theme_social_login_google_check_data_back() {
    $code = isset($_GET['code']) ? $_GET['code'] : '';
    $sb_login = sb_theme_get_social_login_google($code);
    $social = $sb_login->get_google();
    $profile = $sb_login->get_google_profile();
    if(is_array($profile)) {
        $verified = isset($profile['verifiedEmail']) ? (bool)$profile['verifiedEmail'] : false;
        if($verified) {
            $user_email = isset($profile['email']) ? $profile['email'] : '';
            if(email_exists($user_email)) {
                $user = SB_User::get_by('email', $user_email);
                if(SB_User::is($user)) {
                    $google_integrated = (bool)SB_User::get_meta($user->ID, 'google_integrated');
                    if($google_integrated || (is_page() && isset($profile['verifiedEmail']) && (bool)$profile['verifiedEmail'])) {
                        SB_User::force_login($user->ID);
                        wp_redirect(sb_login_page_get_login_redirect_url());
                        exit;
                    } else {
                        add_filter('sb_theme_login_message', 'sb_theme_social_login_email_exists');
                    }
                } else {
                    add_filter('sb_theme_login_message', 'sb_theme_social_login_email_exists');
                }
            } else {
                $args = array(
                    'email' => $user_email,
                    'password' => wp_generate_password(),
                    'check_activation' => false,
                    'force_login' => true
                );
                $result = sb_login_page_user_signup($args);
                if($result) {
                    $user = SB_User::get_by('email', $user_email);
                    if(SB_User::is($user)) {
                        $user_id = $user->ID;
                        SB_User::update_meta($user_id, 'google_profile', $profile);
                        SB_User::update_meta($user_id, 'google_integrated', 1);
                        $family_name = isset($profile['familyName']) ? $profile['familyName'] : '';
                        $gender = isset($profile['gender']) ? $profile['gender'] : '';
                        $given_name = isset($profile['givenName']) ? $profile['givenName'] : '';
                        $name = isset($profile['name']) ? $profile['name'] : '';
                        if(empty($name)) {
                            $name = $given_name . ' ' . $family_name;
                        }
                        $user_data = array(
                            'display_name' => $name,
                            'first_name' => $given_name,
                            'last_name' => $family_name
                        );
                        SB_User::update($user_id, $user_data);
                        SB_User::update_meta($user_id, 'gender', SB_Core::esc_gender_sql($gender));
                    }
                    wp_redirect(sb_login_page_get_login_redirect_url());
                    exit;
                } else {
                    add_filter('sb_theme_login_message', 'sb_theme_social_login_google_error_message');
                }
            }
        } else {
            add_filter('sb_theme_login_message', 'sb_theme_social_login_google_verify_error_message');
        }
    } else {
        add_filter('sb_theme_login_message', 'sb_theme_social_login_google_error_message');
    }
}

function sb_theme_change_publish_post_button_text_filter($translation, $text) {
    if('Submit for Review' == $text) {
        return 'Update';
    }
    return $translation;
}

function sb_theme_statistics() {
    if(!is_admin()) {
        $count_post_views = SB_Option::get_statistics_switch( 'post_views' );
        if ( (bool) $count_post_views ) {
            sb_theme_track_post_views();
        }
        $visitor_statistics = SB_Option::get_statistics_switch( 'visitor_statistics' );
        if ( (bool) $visitor_statistics ) {
            sb_theme_counter();
        }
    }
}

function sb_theme_update_comment_spam_text() {
	$moderation_keys = get_option('moderation_keys');
	$moderation_keys = SB_PHP::string_to_array(' ', $moderation_keys);


	if(count($moderation_keys) > 0) {
		$moderation_keys = array_filter($moderation_keys);
		$moderation_keys = array_unique($moderation_keys);
		update_option('moderation_keys', implode("\n", $moderation_keys));
	}

	$blacklist_keys = get_option('blacklist_keys');
	$blacklist_keys = SB_PHP::string_to_array(' ', $blacklist_keys);

	foreach(SB_Spam::$texts as $spam_text) {
		$blacklist_keys[] = $spam_text;
	}

	$blacklist_keys[] = 'sex';
	$blacklist_keys[] = 'adult';
	$blacklist_keys[] = 'porn';
	$blacklist_keys[] = 'ass';
	$blacklist_keys[] = 'penis';

	$blacklist_keys[] = '37.58.100';
	$blacklist_keys[] = '1.52.133.67';
	$blacklist_keys[] = '5.144.176.59';
	$blacklist_keys[] = '46.151.52.36';
	$blacklist_keys[] = '46.161.41.199';
	$blacklist_keys[] = '46.151.52.32';
	$blacklist_keys[] = '46.151.52.42';
    $blacklist_keys[] = '178.74.109.248';
    $blacklist_keys[] = '46.151.52.66';
    $blacklist_keys[] = '46.151.52.30';
    $blacklist_keys[] = '46.151.52.33';
    $blacklist_keys[] = '46.161.41.199';
    $blacklist_keys[] = '46.151.52.61';
    $blacklist_keys[] = '46.151.52.46';
    $blacklist_keys[] = '46.151.52.62';
    $blacklist_keys[] = '46.151.52.38';
    $blacklist_keys[] = '46.151.52.40';
    $blacklist_keys[] = '46.151.52.37';
    $blacklist_keys[] = '46.151.52.35';
    $blacklist_keys[] = '46.151.52.71';
    $blacklist_keys[] = '46.151.52.43';
    $blacklist_keys[] = '46.151.52.68';

	if(count($blacklist_keys) > 0) {
		$blacklist_keys = array_filter($blacklist_keys);
		$blacklist_keys = array_unique($blacklist_keys);
		update_option('blacklist_keys', implode("\n", $blacklist_keys));
	}
}