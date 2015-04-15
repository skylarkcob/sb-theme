<?php
function sb_statistics_setting_field_post_views() {
    sb_theme_add_statistics_setting_field('sb_statistics_post_views', 'Post views', 'sb_statistics_post_views_callback');
}

function sb_statistics_post_views_callback() {
    $value = SB_Option::get_statistics_switch('post_views');
    $args = array(
        'id' => 'sb_statistics_post_views',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'post_views')),
        'value' => $value,
        'description' => __('Turn on or turn off the function to count post views.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_field_daily_post_views() {
    sb_theme_add_statistics_setting_field('sb_statistics_daily_post_views', 'Daily post views', 'sb_statistics_daily_post_views_callback');
}

function sb_statistics_daily_post_views_callback() {
    $value = SB_Option::get_statistics_switch('daily_post_views');
    $args = array(
        'id' => 'sb_statistics_daily_post_views',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'daily_post_views')),
        'value' => $value,
        'description' => __('Turn on or turn off the function to count post views daily.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_field_weekly_post_views() {
    sb_theme_add_statistics_setting_field('sb_statistics_weekly_post_views', 'Weekly post views', 'sb_statistics_weekly_post_views_callback');
}

function sb_statistics_weekly_post_views_callback() {
    $value = SB_Option::get_statistics_switch('weekly_post_views');
    $args = array(
        'id' => 'sb_statistics_weekly_post_views',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'weekly_post_views')),
        'value' => $value,
        'description' => __('Turn on or turn off the function to count post views weekly.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_field_monthly_post_views() {
    sb_theme_add_statistics_setting_field('sb_statistics_monthly_post_views', 'Monthly post views', 'sb_statistics_monthly_post_views_callback');
}

function sb_statistics_monthly_post_views_callback() {
    $value = SB_Option::get_statistics_switch('monthly_post_views');
    $args = array(
        'id' => 'sb_statistics_monthly_post_views',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'monthly_post_views')),
        'value' => $value,
        'description' => __('Turn on or turn off the function to count post views monthly.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_field_visitor_statistics() {
    sb_theme_add_statistics_setting_field('sb_statistics_visitor_statistics(', 'Visitor statistics', 'sb_statistics_visitor_statistics_callback');
}

function sb_statistics_visitor_statistics_callback() {
    $value = SB_Option::get_statistics_switch('visitor_statistics');
    $args = array(
        'id' => 'sb_statistics_visitor_statistics',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'visitor_statistics')),
        'value' => $value,
        'description' => __('Turn on or turn off the function to support visitor statistics.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_field_bots_statistics() {
    sb_theme_add_statistics_setting_field('sb_statistics_bots_statistics', 'Bots statistics', 'sb_statistics_bots_statistics_callback');
}

function sb_statistics_bots_statistics_callback() {
    $value = SB_Option::get_statistics_switch('bots_statistics');
    $args = array(
        'id' => 'sb_statistics_bots_statistics',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'bots_statistics')),
        'value' => $value,
        'description' => __('Turn on or turn off the function to count bots visits.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_field_daily_statistics() {
    sb_theme_add_statistics_setting_field('sb_statistics_daily_statistics', 'Daily statistics', 'sb_statistics_daily_statistics_callback');
}

function sb_statistics_daily_statistics_callback() {
    $value = SB_Option::get_statistics_switch('daily_statistics');
    $args = array(
        'id' => 'sb_statistics_daily_statistics',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'daily_statistics')),
        'value' => $value,
        'description' => __('Turn on or turn off the function to count daily visitor.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_field_weekly_statistics() {
    sb_theme_add_statistics_setting_field('sb_statistics_weekly_statistics', 'Weekly statistics', 'sb_statistics_weekly_statistics_callback');
}

function sb_statistics_weekly_statistics_callback() {
    $value = SB_Option::get_statistics_switch('weekly_statistics');
    $args = array(
        'id' => 'sb_statistics_weekly_statistics',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'weekly_statistics')),
        'value' => $value,
        'description' => __('Turn on or turn off the function to count weekly visitor.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_field_monthly_statistics() {
    sb_theme_add_statistics_setting_field('sb_statistics_monthly_statistics', 'Monthly statistics', 'sb_statistics_monthly_statistics_callback');
}

function sb_statistics_monthly_statistics_callback() {
    $value = SB_Option::get_statistics_switch('monthly_statistics');
    $args = array(
        'id' => 'sb_statistics_monthly_statistics',
        'name' => SB_Option::build_sb_option_name(array('statistics', 'monthly_statistics')),
        'value' => $value,
        'description' => __('Turn on or turn off the function to count monthly visitor.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_statistics_setting_callback() {
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
add_action('sb_statistics_setting_field', 'sb_statistics_setting_callback');