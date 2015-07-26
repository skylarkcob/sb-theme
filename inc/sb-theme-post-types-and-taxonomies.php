<?php
defined('ABSPATH') or die('Please do not pip me!');

function sb_theme_create_post_types_and_taxonomies_hook() {
    if(SB_Tool::use_administrative_boundaries()) {
        SB_Core::create_administrative_boundaries_taxonomy();
    }

    if(SB_Tool::use_slider()) {
        $args = array(
            'name' => __('Sliders', 'sb-theme'),
            'singular_name' => __('Slider', 'sb-theme'),
            'slug' => SB_Theme::get_post_type_slider_name()
        );
        SB_Core::register_post_type_private($args);
    }

    if(SB_Tool::use_ads_system()) {
        $args = array(
            'name' => __('Advertises', 'sb-theme'),
            'singular_name' => __('Advertise', 'sb-theme'),
            'slug' => SB_Theme::get_post_type_ads_name()
        );
        SB_Core::register_post_type_private($args);
    }

    if(SB_Tool::use_support_system()) {
        $args = array(
            'name' => __('Supports', 'sb-theme'),
            'singular_name' => __('Support', 'sb-theme'),
            'slug' => SB_Theme::get_post_type_support_name()
        );
        SB_Core::register_post_type_private($args);
    }

    if(SB_Tool::use_order_system()) {
        $args = array(
            'name' => __('Orders', 'sb-theme'),
            'singular_name' => __('Order', 'sb-theme'),
            'slug' => SB_Core::get_post_type_order_name()
        );
        SB_Core::register_post_type_private($args);
    }
}
add_action('sb_theme_post_types_and_taxonomies', 'sb_theme_create_post_types_and_taxonomies_hook');