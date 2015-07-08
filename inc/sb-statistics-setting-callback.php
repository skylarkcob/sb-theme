<?php
defined('ABSPATH') or die('Please do not pip me!');

function sb_statistics_setting_field_post_views() {
    sb_theme_add_statistics_setting_field('sb_statistics_post_views', 'Lượt xem bài viết', 'sb_statistics_post_views_callback');
}

function sb_statistics_post_views_callback() {
    $value = SB_Option::get_statistics_switch('post_views');
    $args = array(
        'id' => 'sb_statistics_post_views',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'post_views')),
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép đếm lượt xem cho bài viết.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_field_daily_post_views() {
    sb_theme_add_statistics_setting_field('sb_statistics_daily_post_views', 'Lượt xem hàng ngày', 'sb_statistics_daily_post_views_callback');
}

function sb_statistics_daily_post_views_callback() {
    $value = SB_Option::get_statistics_switch('daily_post_views');
    $args = array(
        'id' => 'sb_statistics_daily_post_views',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'daily_post_views')),
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép đếm lượt xem cho bài viết hàng ngày.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_field_weekly_post_views() {
    sb_theme_add_statistics_setting_field('sb_statistics_weekly_post_views', 'Lượt xem hàng tuần', 'sb_statistics_weekly_post_views_callback');
}

function sb_statistics_weekly_post_views_callback() {
    $value = SB_Option::get_statistics_switch('weekly_post_views');
    $args = array(
        'id' => 'sb_statistics_weekly_post_views',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'weekly_post_views')),
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép đếm lượt xem cho bài viết hàng tuần.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_field_monthly_post_views() {
    sb_theme_add_statistics_setting_field('sb_statistics_monthly_post_views', 'Lượt xem hàng tháng', 'sb_statistics_monthly_post_views_callback');
}

function sb_statistics_monthly_post_views_callback() {
    $value = SB_Option::get_statistics_switch('monthly_post_views');
    $args = array(
        'id' => 'sb_statistics_monthly_post_views',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'monthly_post_views')),
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép đếm lượt xem cho bài viết hàng tháng.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_field_visitor_statistics() {
    sb_theme_add_statistics_setting_field('sb_statistics_visitor_statistics(', 'Thống kê truy cập', 'sb_statistics_visitor_statistics_callback');
}

function sb_statistics_visitor_statistics_callback() {
    $value = SB_Option::get_statistics_switch('visitor_statistics');
    $args = array(
        'id' => 'sb_statistics_visitor_statistics',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'visitor_statistics')),
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép thống kê lượt truy cập trên website.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_field_bots_statistics() {
    sb_theme_add_statistics_setting_field('sb_statistics_bots_statistics', 'Thống kê bot', 'sb_statistics_bots_statistics_callback');
}

function sb_statistics_bots_statistics_callback() {
    $value = SB_Option::get_statistics_switch('bots_statistics');
    $args = array(
        'id' => 'sb_statistics_bots_statistics',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'bots_statistics')),
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép thống kê bot truy cập trên website.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_field_daily_statistics() {
    sb_theme_add_statistics_setting_field('sb_statistics_daily_statistics', 'Thống kê hàng ngày', 'sb_statistics_daily_statistics_callback');
}

function sb_statistics_daily_statistics_callback() {
    $value = SB_Option::get_statistics_switch('daily_statistics');
    $args = array(
        'id' => 'sb_statistics_daily_statistics',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'daily_statistics')),
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép thống kê lượt truy cập hàng ngày.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_field_weekly_statistics() {
    sb_theme_add_statistics_setting_field('sb_statistics_weekly_statistics', 'Thống kê hàng tuần', 'sb_statistics_weekly_statistics_callback');
}

function sb_statistics_weekly_statistics_callback() {
    $value = SB_Option::get_statistics_switch('weekly_statistics');
    $args = array(
        'id' => 'sb_statistics_weekly_statistics',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'weekly_statistics')),
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép thống kê lượt truy cập hàng tuần.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_field_monthly_statistics() {
    sb_theme_add_statistics_setting_field('sb_statistics_monthly_statistics', 'Thống kê hàng tháng', 'sb_statistics_monthly_statistics_callback');
}

function sb_statistics_monthly_statistics_callback() {
    $value = SB_Option::get_statistics_switch('monthly_statistics');
    $args = array(
        'id' => 'sb_statistics_monthly_statistics',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'monthly_statistics')),
        'value' => $value,
        'description' => __('Bật hoặc tắt chức năng cho phép thống kê lượt truy cập hàng tháng.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_callback() {
	sb_utilities_setting_field_statistics();
    if(SB_Option::statistics_enabled()) {
	    sb_statistics_setting_field_post_views();
	    $count_post_views = SB_Option::get_statistics_switch('post_views');
	    if((bool)$count_post_views) {
		    sb_statistics_setting_field_daily_post_views();
		    sb_statistics_setting_field_weekly_post_views();
		    sb_statistics_setting_field_monthly_post_views();
	    }
	    sb_statistics_setting_field_visitor_statistics();
	    $visitor_statistics = SB_Option::get_statistics_switch('visitor_statistics');
	    if((bool)$visitor_statistics) {
		    sb_statistics_setting_field_daily_statistics();
		    sb_statistics_setting_field_weekly_statistics();
		    sb_statistics_setting_field_monthly_statistics();
		    sb_statistics_setting_field_bots_statistics();
	    }
    }
}
add_action('sb_statistics_setting_field', 'sb_statistics_setting_callback');