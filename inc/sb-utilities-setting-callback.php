<?php
defined('ABSPATH') or die('Please do not pip me!');

function sb_utilities_setting_field_jquery_marquee() {
    sb_theme_add_utilities_setting_field('sb_utilities_jquery_marquee', 'jQuery Marquee', 'sb_utilities_jquery_marquee_callback');
}

function sb_utilities_jquery_marquee_callback() {
    $value = SB_Option::get_utility('jquery_marquee');
    $args = array(
        'id' => 'sb_utilities_jquery_marquee',
        'name' => 'sb_options[utilities][jquery_marquee]',
        'value' => $value,
        'description' => __('Turn on or turn off the function to load jQuery Marquee plugin.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_jquery_snowfall() {
    sb_theme_add_utilities_setting_field('sb_utilities_jquery_snowfall', 'jQuery Snowfall', 'sb_utilities_jquery_snowfall_callback');
}

function sb_utilities_jquery_snowfall_callback() {
    $value = SB_Option::get_utility('jquery_snowfall');
    $args = array(
        'id' => 'sb_utilities_jquery_snowfall',
        'name' => 'sb_options[utilities][jquery_snowfall]',
        'value' => $value,
        'description' => __('Turn on or turn off the function to load snow fall on your website.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_facebook_fanpage() {
    sb_theme_add_utilities_setting_field('sb_utilities_facebook_fanpage', 'Facebook fanpage', 'sb_utilities_facebook_fanpage_callback');
}

function sb_utilities_facebook_fanpage_callback() {
    $value = SB_Tool::use_utility_facebook_fanpage();
    $args = array(
        'id' => 'sb_utilities_facebook_fanpage',
        'name' => 'sb_options[utilities][facebook_fanpage]',
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép bạn nhập Facebook fanpage sử dụng trên website của bạn.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_footer_text() {
    sb_theme_add_utilities_setting_field('sb_utilities_footer_text', __('Chữ dưới footer', 'sb-theme'), 'sb_utilities_footer_text_callback');
}

function sb_utilities_footer_text_callback() {
    $value = SB_Tool::use_utility_footer_text();
    $args = array(
        'id' => 'sb_utilities_footer_text',
        'name' => 'sb_options[utilities][footer_text]',
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép bạn nhập thông tin chữ hiển thị dưới chân trang web.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_social() {
    sb_theme_add_utilities_setting_field('sb_utilities_social', __('Mạng xã hội', 'sb-theme'), 'sb_utilities_social_callback');
}

function sb_utilities_social_callback() {
    $value = SB_Tool::use_utility_social_list();
    $args = array(
        'id' => 'sb_utilities_social',
        'name' => 'sb_options[utilities][social]',
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép bạn cài đặt thông tin các trang mạng xã hội.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_category_widget() {
    sb_theme_add_utilities_setting_field('sb_utilities_category_widget', __('Use category widget', 'sb-theme'), 'sb_utilities_category_widget_callback');
}

function sb_utilities_category_widget_callback() {
    $value = SB_Tool::use_utility_category_widget();
    $args = array(
        'id' => 'sb_utilities_category_widget',
        'name' => 'sb_options[utilities][category_widget]',
        'value' => $value,
        'description' => __('Turn on or turn off the function to setup category widget.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_default_thumbnail() {
    sb_theme_add_utilities_setting_field('sb_utilities_default_thumbnail', __('Ảnh thumbnail mặc định', 'sb-theme'), 'sb_utilities_default_thumbnail_callback');
}

function sb_utilities_default_thumbnail_callback() {
    $value = SB_Tool::use_utility_default_thumbnail();
    $args = array(
        'id' => 'sb_utilities_default_thumbnail',
        'name' => 'sb_options[utilities][default_thumbnail]',
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cài đặt hình ảnh thumbnail mặc định cho bài viết.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_shop() {
    sb_theme_add_utilities_setting_field('sb_utilities_shop', __('Shop', 'sb-theme'), 'sb_utilities_shop_callback');
}

function sb_utilities_shop_callback() {
    $value = SB_Option::get_utility('shop');
    $args = array(
        'id' => 'sb_utilities_shop',
        'name' => 'sb_options[utilities][shop]',
        'value' => $value,
        'description' => __('Turn on or turn off the function to setup e-commerce website.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_addthis() {
    sb_theme_add_utilities_setting_field('sb_utilities_addthis', __('AddThis', 'sb-theme'), 'sb_utilities_addthis_callback');
}

function sb_utilities_addthis_callback() {
    $value = SB_Tool::use_utility_addthis();
    $args = array(
        'id' => 'sb_utilities_addthis',
        'name' => 'sb_options[utilities][addthis]',
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng sử dụng các nút chia sẻ của AddThis.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_sharethis() {
    sb_theme_add_utilities_setting_field('sb_utilities_sharethis', __('ShareThis', 'sb-theme'), 'sb_utilities_sharethis_callback');
}

function sb_utilities_sharethis_callback() {
    $value = SB_Tool::use_utility_sharethis();
    $args = array(
        'id' => 'sb_utilities_sharethis',
        'name' => 'sb_options[utilities][sharethis]',
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng sử dụng các nút chia sẻ của ShareThis.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_term_meta() {
    sb_theme_add_utilities_setting_field('sb_utilities_term_meta', __('Term meta', 'sb-theme'), 'sb_utilities_term_meta_callback');
}

function sb_utilities_term_meta_callback() {
    $value = SB_Option::get_utility('term_meta');
    $args = array(
        'id' => 'sb_utilities_term_meta',
        'name' => 'sb_options[utilities][term_meta]',
        'value' => $value,
        'description' => __('Turn on or turn off the function to add meta for the terms.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_responsive() {
	sb_theme_add_utilities_setting_field('sb_utilities_responsive', __('Responsive', 'sb-theme'), 'sb_utilities_responsive_callback');
}

function sb_utilities_responsive_callback() {
	$value = SB_Option::get_utility('responsive');
	$args = array(
		'id' => 'sb_utilities_responsive',
		'name' => 'sb_options[utilities][responsive]',
		'value' => $value,
		'description' => __('Turn on or turn off the function to load responsive style on your site.', 'sb-theme')
	);
	SB_Field::switch_button($args);
}

function sb_utilities_setting_field_statistics() {
    sb_theme_add_statistics_setting_field('sb_utilities_statistics', __('Thống kê', 'sb-theme'), 'sb_utilities_statistics_callback');
}

function sb_utilities_statistics_callback() {
    $value = absint(SB_Option::statistics_enabled());
    $args = array(
        'id' => 'sb_utilities_statistics',
        'name' => 'sb_options[utilities][statistics]',
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép thống kê lượt truy cập và lượt xem bài viết trên website.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_float_ads() {
    sb_theme_add_utilities_setting_field('sb_utilities_float_ads', __('Float ads', 'sb-theme'), 'sb_utilities_float_ads_callback');
}

function sb_utilities_float_ads_callback() {
    $value = SB_Option::get_utility('float_ads');
    $args = array(
        'id' => 'sb_utilities_float_ads',
        'name' => SB_Option::build_sb_utility_option_name(array('float_ads')),
        'value' => $value,
        'description' => __('Turn on or turn off the float ads on your site.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_leaderboard_ads() {
    sb_theme_add_utilities_setting_field('sb_utilities_leaderboard_ads', __('Leaderboard ads', 'sb-theme'), 'sb_utilities_leaderboard_ads_callback');
}

function sb_utilities_leaderboard_ads_callback() {
    $value = SB_Option::get_utility('leaderboard_ads');
    $args = array(
        'id' => 'sb_utilities_leaderboard_ads',
        'name' => SB_Option::build_sb_utility_option_name(array('leaderboard_ads')),
        'value' => $value,
        'description' => __('Turn on or turn off the leaderboard ads on your site.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_scroll_top() {
    sb_theme_add_utilities_setting_field('sb_utilities_scroll_top', __('Nút về đầu trang', 'sb-theme'), 'sb_utilities_scroll_top_callback');
}

function sb_utilities_scroll_top_callback() {
    $value = SB_Tool::use_utility_scroll_to_top();
    $args = array(
        'id' => 'sb_utilities_scroll_top',
        'name' => SB_Option::build_sb_utility_option_name(array('scroll_top')),
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng hiển thị nút quay về đầu trang trên website của bạn.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_facebook_php_sdk() {
    sb_theme_add_utilities_setting_field('sb_utilities_facebook_php_sdk', __('Facebook PHP SDK', 'sb-theme'), 'sb_utilities_facebook_php_sdk_callback');
}

function sb_utilities_facebook_php_sdk_callback() {
    $value = SB_Tool::use_utility_facebook_php_sdk();
    $args = array(
        'id' => 'sb_utilities_facebook_php_sdk',
        'name' => SB_Option::build_sb_utility_option_name(array('facebook_php_sdk')),
        'value' => $value,
        'description' => __('Turn on or turn off the scroll top button on your site.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_google_analytics() {
    sb_theme_add_utilities_setting_field('sb_utilities_google_analytics', __('Google Analytics', 'sb-theme'), 'sb_utilities_google_analytics_callback');
}

function sb_utilities_google_analytics_callback() {
    $value = SB_Tool::use_utility_google_analytics();
    $args = array(
        'id' => 'sb_utilities_google_analytics',
        'name' => SB_Option::build_sb_utility_option_name(array('google_analytics')),
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng chèn code thống kê của Google Analytics vào website của bạn.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_add_to_head() {
    sb_theme_add_utilities_setting_field('sb_utilities_add_to_head', __('Thêm vào thẻ head', 'sb-theme'), 'sb_utilities_add_to_head_callback');
}

function sb_utilities_add_to_head_callback() {
    $value = SB_Tool::use_utility_add_to_head();
    $args = array(
        'id' => 'sb_utilities_add_to_head',
        'name' => SB_Option::build_sb_utility_option_name(array('add_to_head')),
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép bạn thêm thông tin vào giữa thẻ head.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_utilities_setting_field_callback() {
    sb_utilities_setting_field_add_to_head();
    sb_utilities_setting_field_google_analytics();
	//sb_utilities_setting_field_responsive();

    sb_utilities_setting_field_default_thumbnail();
    //sb_utilities_setting_field_term_meta();
    //sb_utilities_setting_field_shop();
    sb_utilities_setting_field_sharethis();
    sb_utilities_setting_field_addthis();
    //sb_utilities_setting_field_jquery_snowfall();
    //sb_utilities_setting_field_jquery_marquee();
    //sb_utilities_setting_field_facebook_php_sdk();
    sb_utilities_setting_field_facebook_fanpage();
    //sb_utilities_setting_field_category_widget();
    //sb_utilities_setting_field_leaderboard_ads();
    //sb_utilities_setting_field_float_ads();
    sb_utilities_setting_field_social();
    sb_utilities_setting_field_scroll_top();
    sb_utilities_setting_field_footer_text();
}
add_action('sb_utilities_setting_field', 'sb_utilities_setting_field_callback');