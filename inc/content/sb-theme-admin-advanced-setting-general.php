<?php
/*
 * Cài đặt thêm ảnh thumbnail cho chuyên mục
 */
$args = array(
    'title' => 'Ảnh thumbnail cho chuyên mục',
    'description' => 'Tương tự như bài viết, bạn cũng có thể tạo ảnh đại diện cho chuyên mục.',
    'callback' => 'sb_theme_advanced_setting_general_term_thumbnail'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_general_term_thumbnail() {
    $tab_base_option_name = 'general';
    $key = 'use_term_thumbnail';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $value = SB_Option::check_switch_value($value, 0);
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => __('Sử dụng ảnh thumbnail cho chuyên mục?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);

    if((bool)$value) {
        $args = array(
            'title' => 'Chọn taxonomy hỗ trợ thumbnail',
            'description' => 'Hãy chọn những taxonomy mà bạn muốn hỗ trợ sử dụng ảnh thumbnail cho term.',
            'callback' => 'sb_theme_advanced_setting_general_taxonomy_use_thumbnail'
        );
        SB_Admin_Custom::row_setting_field($args);
    }
}

function sb_theme_advanced_setting_general_taxonomy_use_thumbnail() {
    $tab_base_option_name = 'general';
    $key = 'taxonomy_use_thumbnail';
	$actives = SB_Option::get_taxonomy_use_thumbnail();
    //$actives = apply_filters('sb_theme_taxonomy_use_thumbnail', $actives);
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'sortable_callback' => 'sb_theme_advanced_setting_general_taxonomy_use_thumbnail_sortable_callback',
        'sortable_active_callback' => 'sb_theme_advanced_setting_general_taxonomy_use_thumbnail_sortable_active_callback',
        'sortable_description' => __('Tất cả các taxonomy hiện có trên website.', 'sb-theme'),
        'sortable_active_description' => __('Taxonomy được hỗ trợ cho phép term hiển thị ảnh thumbnail.', 'sb-theme'),
        'before' => '',
        'field_class' => 'display-inline',
        'click_to_connect' => true,
	    'value' => implode(',', $actives)
    );
    SB_Field::sortable_ui_connect_row($args);
}

function sb_theme_advanced_setting_general_taxonomy_use_thumbnail_sortable_callback() {
    $taxs = get_taxonomies(array(), 'objects');
    $actives = SB_Option::get_taxonomy_use_thumbnail();
    //$actives = apply_filters('sb_theme_taxonomy_use_thumbnail', $actives);
    foreach($taxs as $key => $value) {
        if(in_array($key, $actives)) {
            continue;
        }
        $class = 'ui-state-default';
        if('nav_menu' == $key || 'link_category' == $key || 'post_format' == $key) {
            $class = 'ui-state-disabled';
        }
        echo '<li data-value="' . $key . '" class="' . $class . '">' . $value->labels->singular_name . '</li>';
    }
}

function sb_theme_advanced_setting_general_taxonomy_use_thumbnail_sortable_active_callback() {
    $actives = SB_Option::get_taxonomy_use_thumbnail();
    foreach($actives as $key) {
        $tax = get_taxonomy($key);
        if(!is_object($tax)) {
            continue;
        }
        $value = $tax->labels->singular_name;
        $class = 'ui-state-default';
        echo '<li data-value="' . $key . '" class="' . $class . '">' . $value . '</li>';
    }
}

/*
 * Cài đặt hệ thống địa giới hành chính
 */
$args = array(
    'title' => 'Địa giới hành chính',
    'description' => 'Bật tính năng tạo các taxonomy để quản lý thông tin địa giới hành chính.',
    'callback' => 'sb_theme_advanced_setting_general_administrative_boundaries'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_general_administrative_boundaries() {
    $tab_base_option_name = 'general';
    $key = 'use_administrative_boundaries';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $value = SB_Option::check_switch_value($value, 0);
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => __('Tạo taxonomy quản lý thông tin các tỉnh, quận, huyện?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);

    if((bool)$value) {
        $args = array(
            'title' => 'Chọn post type sử dụng địa giới hành chính',
            'description' => 'Hãy chọn những post type mà bạn muốn sử dụng thông tin địa giới hành chính.',
            'callback' => 'sb_theme_advanced_setting_general_post_type_use_administrative_boundaries'
        );
        SB_Admin_Custom::row_setting_field($args);
    }
}

function sb_theme_advanced_setting_general_post_type_use_administrative_boundaries() {
    $tab_base_option_name = 'general';
    $key = 'post_type_use_administrative_boundaries';
	$actives = SB_Option::get_post_type_use_administrative_boundaries();
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'sortable_callback' => 'sb_theme_advanced_setting_general_post_type_use_administrative_boundaries_sortable_callback',
        'sortable_active_callback' => 'sb_theme_advanced_setting_general_post_type_use_administrative_boundaries_sortable_active_callback',
        'sortable_description' => __('Tất cả các post type hiện có trên website.', 'sb-theme'),
        'sortable_active_description' => __('Post type được hỗ trợ cho phép sử dụng thông tin địa giới hành chính.', 'sb-theme'),
        'before' => '',
        'field_class' => 'display-inline',
        'click_to_connect' => true,
	    'value' => implode(',', $actives)
    );
    SB_Field::sortable_ui_connect_row($args);
}

function sb_theme_advanced_setting_general_post_type_use_administrative_boundaries_sortable_callback() {
    $lists = get_post_types(array('public' => true), 'objects');
    $actives = SB_Option::get_post_type_use_administrative_boundaries();
    foreach($lists as $key => $value) {
        if(in_array($key, $actives)) {
            continue;
        }
        $class = 'ui-state-default';
        if('attachment' == $key || 'revision' == $key || 'nav_menu_item' == $key) {
            $class = 'ui-state-disabled';
        }
        echo '<li data-value="' . $key . '" class="' . $class . '">' . $value->labels->singular_name . '</li>';
    }
}

function sb_theme_advanced_setting_general_post_type_use_administrative_boundaries_sortable_active_callback() {
    $actives = SB_Option::get_post_type_use_administrative_boundaries();
    foreach($actives as $key) {
        $tax = get_post_type_object($key);
        if(!is_object($tax)) {
            continue;
        }
        $value = $tax->labels->singular_name;
        $class = 'ui-state-default';
        echo '<li data-value="' . $key . '" class="' . $class . '">' . $value . '</li>';
    }
}

/*
 * Cài đặt hệ thống tạo cache cho website
 */
$args = array(
    'title' => 'Hệ thống cache',
    'description' => 'Cache là chức năng cho phép bạn lưu lại dữ liệu duyệt web, bạn có thể dùng chức năng này để tăng tốc độ tải trang web.',
    'callback' => 'sb_theme_advanced_setting_general_cache'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_general_cache() {
    $menu_base = 'advanced';
    $tab_base_option_name = 'general';

    $key = 'cache_enabled';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $value = SB_Option::check_switch_value($value, 0);
    $args = array(
        'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => __('Kích hoạt chức năng lưu cache?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);

    if((bool)$value) {
        $key = 'query_cache_enabled';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        $args = array(
            'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => __('Lưu cache cho truy vấn bài viết?', 'sb-theme'),
            'value' => $value
        );
        SB_Field::checkbox($args);

        $key = 'menu_cache_enabled';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        $args = array(
            'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => __('Lưu cache cho menu?', 'sb-theme'),
            'value' => $value
        );
        SB_Field::checkbox($args);

        $key = 'widget_cache_enabled';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        $args = array(
            'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => __('Lưu cache cho widget?', 'sb-theme'),
            'value' => $value
        );
        SB_Field::checkbox($args);

        $key = 'post_images_cache_enabled';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        $args = array(
            'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => __('Lưu cache cho hình ảnh trong bài viết?', 'sb-theme'),
            'value' => $value
        );
        SB_Field::checkbox($args);

        $key = 'post_comment_count_cache_enabled';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        $args = array(
            'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => __('Lưu cache cho số bình luận của bài viết?', 'sb-theme'),
            'value' => $value
        );
        SB_Field::checkbox($args);
    }
}