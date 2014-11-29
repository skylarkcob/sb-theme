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

function sb_theme_post_views() {
    sb_theme_track_post_views();
}
if(SB_Option::statistics_enabled()) add_action('sb_theme_init', 'sb_theme_post_views');