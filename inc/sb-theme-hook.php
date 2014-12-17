<?php
do_action('sb_theme_setup');

if((sb_theme_support_shop() || SB_Option::utility_enabled('shop')) && !class_exists('SB_Product')) {
    require SB_THEME_INC_PATH . '/class-sb-product.php';
}

if(sb_theme_support_addthis() || SB_Option::utility_enabled('addthis')) {
    function sb_theme_addthis_config() {
        include SB_THEME_LIB_PATH . '/addthis/config.php';
    }
    add_action('wp_footer', 'sb_theme_addthis_config');
}

if(sb_theme_support_term_meta() || SB_Option::utility_enabled('term_meta')) {
    if(!class_exists('SB_Term_Meta')) {
        require SB_CORE_INC_PATH . '/class-sb-term-meta.php';
    }
    if(!class_exists('SB_Term_Field')) {
        require SB_CORE_INC_PATH . '/class-sb-term-field.php';
    }
    require SB_THEME_INC_PATH . '/sb-theme-meta.php';
    function sb_theme_custom_term_meta() {
        wp_enqueue_media();
    }
    if(SB_Admin_Custom::is_edit_term_page()) add_action('admin_enqueue_scripts', 'sb_theme_custom_term_meta');
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
            $today_date = date('Y-m-d', strtotime($current_date_time));
            update_option('sb_today', $today_date);

            $key = 'visits_today';
            $count = intval(get_option($key));
            if(SB_Option::get_tomorrow() == '' || SB_Option::get_yesterday() == '') {
                update_option('sb_tomorrow', date('Y-m-d', strtotime('+1 day', strtotime($current_date_time))));
                update_option('sb_yesterday', date('Y-m-d', strtotime('-1 day', strtotime($current_date_time))));
            }
            if($today_date == SB_Option::get_tomorrow()) {
                update_option('visits_yesterday', $count);
                $count = 0;
                update_option('sb_tomorrow', date('Y-m-d', strtotime('+1 day', strtotime($current_date_time))));
                update_option('sb_yesterday', date('Y-m-d', strtotime('-1 day', strtotime($current_date_time))));
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
        }
        if($ip == $current_ip && $id == $user_id && $pc_info == $uname && $pc_ip == $local_ip && $user_agent == $agent) {
            $same_user = true;
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

function sb_theme_counter() {
    if(is_admin()) {
        return;
    }
    sb_theme_visits_counter();
    sb_theme_visitor_online_counter();
}

function sb_theme_statistics() {
    $count_post_views = SB_Option::get_statistics_switch('post_views');
    if((bool)$count_post_views) {
        sb_theme_track_post_views();
    }
    $visitor_statistics = SB_Option::get_statistics_switch('visitor_statistics');
    if((bool)$visitor_statistics) {
        sb_theme_counter();
    }
}
if(SB_Option::statistics_enabled()) add_action('sb_theme_init', 'sb_theme_statistics');