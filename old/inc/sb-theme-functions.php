<?php
function sb_theme_init() {
    do_action('sb_theme_init');
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

function sb_theme_testing() {
    return apply_filters('sb_theme_testing', false);
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

function sb_theme_get_media_detail($value) {
    $value_id = (is_array($value)) ? (isset($value['id']) ? absint($value['id']) : '') : '';
    $value_url = (is_array($value)) ? (isset($value['url']) ? $value['url'] : '') : $value;
    if($value_id > 0) {
        $media_url = wp_get_attachment_url($value_id);
        if(!empty($media_url)) {
            $value_url = $media_url;
        }
    }
    return array('id' => $value_id, 'url' => $value_url);
}

function sb_theme_get_media_url($value) {
    $url = sb_theme_get_media_detail($value);
    return $url['url'];
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
    wp_enqueue_style( 'owl-carousel-style', SB_THEME_LIB_URL. '/owl-carousel/owl.carousel.css' );
    wp_enqueue_style( 'owl-carousel-theme-style', SB_THEME_LIB_URL. '/owl-carousel/owl.theme.css' );
    wp_enqueue_script('owl-carousel', SB_THEME_LIB_URL. '/owl-carousel/owl.carousel.js', array('jquery'), false, true);
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
    global $sb_theme_supports;
    if($args) {
        if(!is_array($sb_theme_supports)) {
            if(!is_array($args)) {
                $args = array($args);
            }
            $sb_theme_supports = $args;
        } else {
            if(!is_array($args)) {
                array_push($sb_theme_supports, $args);
            } else {
                $sb_theme_supports = array_merge($sb_theme_supports, $args);
            }
        }
    }
    if(!is_array($sb_theme_supports)) {
        $sb_theme_supports = array();
    }
    $sb_theme_supports = array_unique($sb_theme_supports);
    $sb_theme_supports = apply_filters('sb_theme_supports', $sb_theme_supports);
    return $sb_theme_supports;
}

function sb_get_theme_support() {
    global $sb_theme_supports;
    if(!is_array($sb_theme_supports)) {
        $sb_theme_supports = array();
    }
    $sb_theme_supports = apply_filters('sb_theme_supports', $sb_theme_supports);
    return $sb_theme_supports;
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
    $supports = sb_get_theme_support();
    if(in_array($name, $supports)) {
        return true;
    }
    return false;
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
    $thumbnail_url = SB_Post::get_thumbnail_url($args);
    $width = isset($args['width']) ? intval($args['width']) : 0;
    $height = isset($args['height']) ? intval($args['height']) : 0;
    if($width < 16) {
        $width = $height;
    }
    if($height < 16) {
        $height = $width;
    }
    if($width > 15) {
        $crop = isset($args['crop']) ? $args['crop'] : false;
        $crop = (bool)$crop;
        $defaults = array(
            'width' => $width,
            'height' => $height,
            'crop' => $crop
        );
        $params = isset($args['params']) ? $args['params'] : array();
        $params = (array)$params;
        $params = wp_parse_args($params, $defaults);
        $bfi_thumb = isset($args['bfi_thumb']) ? $args['bfi_thumb'] : true;
        if($bfi_thumb) {
            $thumbnail_url = bfi_thumb($thumbnail_url, $params);
        }
    }
    $args['thumbnail_url'] = $thumbnail_url;
    SB_Post::the_thumbnail_html($args);
}

function sb_theme_change_default_image_setting() {
    update_option('image_default_align', 'center');
    update_option('image_default_link_type', 'none');
    update_option('image_default_size', 'large');
}

function sb_theme_get_custom_image_url($name) {
    return SB_THEME_CUSTOM_URL . '/images/' . $name;
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
    if(!is_admin() && is_singular() && !SB_Core::wp_postviews_activated() && !SB_Detect::is_bots()) {
        global $wp_query;
        $post = $wp_query->post;
        if(SB_Core::is_error($post)) {
            return;
        }
        $post_id = $post->ID;
        $post_views_session_key = 'post_' . $post_id . '_views';
        $post_views_session = intval(SB_PHP::get_session($post_views_session_key));
        if($post_views_session != 1) {
            SB_PHP::set_session($post_views_session_key, 1);
            SB_Post::update_views($post_id);
        }
    }
}