<?php
/*
 * Tạo thumbnail cho chuyên mục
 */
function sb_theme_term_meta_thumbnail($taxonomies = array()) {
    $fields = array(
        array(
            'name' => 'thumbnail',
            'type' => 'image_url'
        )
    );
    $args = array(
        'taxonomies' => $taxonomies,
        'callback' => 'sb_theme_meta_term_thumbnail_callback',
        'fields' => $fields
    );
    $term_meta = new SB_Term_Meta($args);
}

function sb_theme_meta_term_thumbnail_callback( $term ) {
    sb_term_meta_nonce();
    $value = SB_Term::get_meta($term->term_id, $term->taxonomy, 'thumbnail');
    $args = array(
        'id' => 'sb_category_thumbnail',
        'label' => __('Thumbnail', 'sb-theme'),
        'name' => 'thumbnail',
        'value' => $value,
        'description' => __('You can enter url or upload new image file.', 'sb-theme')
    );
    SB_Term_Field::image_upload($args);
}

if(SB_Option::use_term_thumbnail()) {
    $taxs = SB_Option::get_taxonomy_use_thumbnail();
    if(count($taxs) < 1) {
        $taxs[] = 'category';
    }
    sb_theme_term_meta_thumbnail($taxs);
}

/*
 * Tạo mối liên hệ giữa các địa giới hành chính
 */
if(SB_Option::use_administrative_boundaries()) {
    $fields = array(
        array(
            'name' => 'province',
            'type' => 'number'
        )
    );
    $args = array(
        'taxonomies' => array('district'),
        'callback' => 'sb_theme_term_meta_district_province_edit',
        'create_callback' => 'sb_theme_term_meta_district_province_create',
        'fields' => $fields
    );
    $term_meta = new SB_Term_Meta($args);

    $key = 'ward_district';

    $fields = array(
        array(
            'name' => 'district',
            'type' => 'number'
        )
    );
    $args = array(
        'taxonomies' => array('ward'),
        'callback' => 'sb_theme_term_meta_' . $key . '_edit',
        'create_callback' => 'sb_theme_term_meta_' . $key . '_create',
        'fields' => $fields
    );
    $term_meta = new SB_Term_Meta($args);

    $key = 'hamlet_ward';

    $fields = array(
        array(
            'name' => 'ward',
            'type' => 'number'
        )
    );
    $args = array(
        'taxonomies' => array('hamlet'),
        'callback' => 'sb_theme_term_meta_' . $key . '_edit',
        'create_callback' => 'sb_theme_term_meta_' . $key . '_create',
        'fields' => $fields
    );
    $term_meta = new SB_Term_Meta($args);

    $key = 'street_district';

    $fields = array(
        array(
            'name' => 'district',
            'type' => 'number'
        )
    );
    $args = array(
        'taxonomies' => array('street'),
        'callback' => 'sb_theme_term_meta_' . $key . '_edit',
        'create_callback' => 'sb_theme_term_meta_' . $key . '_create',
        'fields' => $fields
    );
    $term_meta = new SB_Term_Meta($args);
}

function sb_theme_term_meta_district_province_edit( $term ) {
    SB_Theme::the_term_meta_nonce();
    $value = SB_Term::get_meta($term->term_id, $term->taxonomy, 'province');
    $args = array(
        'id' => 'sb_theme_term_meta_district_province',
        'label' => __('Tỉnh thành', 'sb-theme'),
        'name' => 'province',
        'value' => $value,
        'taxonomy' => 'province',
        'description' => __('Lựa chọn tỉnh thành mà quận huyện hiện tại thuộc về.', 'sb-theme')
    );
    SB_Term_Field::select_term($args);
}

function sb_theme_term_meta_district_province_create() {
    SB_Theme::the_term_meta_nonce();
    $args = array(
        'id' => 'sb_theme_term_meta_district_province',
        'label' => __('Tỉnh thành', 'sb-theme'),
        'name' => 'province',
        'value' => 0,
        'taxonomy' => 'province',
        'before' => '<div class="form-field term-province-wrap">',
        'after' => '</div>',
        'description' => __('Lựa chọn tỉnh thành mà quận huyện hiện tại thuộc về.', 'sb-theme'),
        'autocomplete' => true
    );
    SB_Term_Field::select_term($args);
}

function sb_theme_term_meta_ward_district_edit( $term ) {
    $name = 'district';
    SB_Theme::the_term_meta_nonce();
    $value = SB_Term::get_meta($term->term_id, $term->taxonomy, $name);
    $key = 'district_province';
    $args = array(
        'id' => 'sb_theme_term_meta_' . $key,
        'label' => __('Quận huyện', 'sb-theme'),
        'name' => $name,
        'value' => $value,
        'taxonomy' => $name,
        'description' => __('Lựa chọn quận huyện mà phường xã hiện tại thuộc về.', 'sb-theme')
    );
    SB_Term_Field::select_term($args);
}

function sb_theme_term_meta_ward_district_create() {
    $key = 'ward_district';
    $name = 'district';
    SB_Theme::the_term_meta_nonce();
    $args = array(
        'id' => 'sb_theme_term_meta_' . $key,
        'label' => __('Quận huyện', 'sb-theme'),
        'name' => $name,
        'value' => 0,
        'taxonomy' => $name,
        'before' => '<div class="form-field term-' . $name . '-wrap">',
        'after' => '</div>',
        'description' => __('Lựa chọn quận huyện mà phường xã hiện tại thuộc về.', 'sb-theme'),
        'autocomplete' => true
    );
    SB_Term_Field::select_term($args);
}

function sb_theme_term_meta_hamlet_ward_edit( $term ) {
    $name = 'ward';
    SB_Theme::the_term_meta_nonce();
    $value = SB_Term::get_meta($term->term_id, $term->taxonomy, $name);
    $key = 'hamlet_ward';
    $args = array(
        'id' => 'sb_theme_term_meta_' . $key,
        'label' => __('Phường xã', 'sb-theme'),
        'name' => $name,
        'value' => $value,
        'taxonomy' => $name,
        'description' => __('Lựa chọn phường xã mà thôn xóm hiện tại thuộc về.', 'sb-theme')
    );
    SB_Term_Field::select_term($args);
}

function sb_theme_term_meta_hamlet_ward_create() {
    $key = 'hamlet_ward';
    $name = 'ward';
    SB_Theme::the_term_meta_nonce();
    $args = array(
        'id' => 'sb_theme_term_meta_' . $key,
        'label' => __('Phường xã', 'sb-theme'),
        'name' => $name,
        'value' => 0,
        'taxonomy' => $name,
        'before' => '<div class="form-field term-' . $name . '-wrap">',
        'after' => '</div>',
        'description' => __('Lựa chọn phường xã mà thôn xóm hiện tại thuộc về.', 'sb-theme'),
        'autocomplete' => true
    );
    SB_Term_Field::select_term($args);
}

function sb_theme_term_meta_street_district_edit( $term ) {
    $name = 'district';
    SB_Theme::the_term_meta_nonce();
    $value = SB_Term::get_meta($term->term_id, $term->taxonomy, $name);
    $key = 'street_district';
    $args = array(
        'id' => 'sb_theme_term_meta_' . $key,
        'label' => __('Quận huyện', 'sb-theme'),
        'name' => $name,
        'value' => $value,
        'taxonomy' => $name,
        'description' => __('Lựa chọn quận huyện mà đường phố hiện tại thuộc về.', 'sb-theme')
    );
    SB_Term_Field::select_term($args);
}

function sb_theme_term_meta_street_district_create() {
    $key = 'street_district';
    $name = 'district';
    SB_Theme::the_term_meta_nonce();
    $args = array(
        'id' => 'sb_theme_term_meta_' . $key,
        'label' => __('Quận huyện', 'sb-theme'),
        'name' => $name,
        'value' => 0,
        'taxonomy' => $name,
        'before' => '<div class="form-field term-' . $name . '-wrap">',
        'after' => '</div>',
        'description' => __('Lựa chọn quận huyện mà đường phố hiện tại thuộc về.', 'sb-theme'),
        'autocomplete' => true
    );
    SB_Term_Field::select_term($args);
}

/*
 * Tạo meta box cho post type, người dùng chọn thông tin địa giới hành chính
 */
if(SB_Option::use_administrative_boundaries()) {
    $post_types = SB_Option::get_post_type_use_administrative_boundaries();
    $post_type = SB_Core::get_add_new_post_type();
    if(in_array($post_type, $post_types)) {
        $fields = array(
            array(
                'name' => 'province',
                'type' => 'number'
            ),
            array(
                'name' => 'district',
                'type' => 'number'
            ),
            array(
                'name' => 'ward',
                'type' => 'number'
            ),
            array(
                'name' => 'hamlet',
                'type' => 'number'
            ),
            array(
                'name' => 'street',
                'type' => 'number'
            )
        );
        $args = array(
            'title' => apply_filters('sb_theme_meta_box_administrative_boundaries_title', __('Thông tin địa giới hành chính', 'sb-theme')),
            'id' => 'sb_theme_metabox_administrative_boundaries',
            'post_types' => SB_Option::get_post_type_use_administrative_boundaries(),
            'fields' => $fields,
            'callback' => 'sb_theme_metabox_administrative_boundaries_callback'
        );
        $meta_box = new SB_Meta_Box($args);
    }
}

function sb_theme_metabox_administrative_boundaries_callback() {
    sb_theme_get_meta_box('meta-box-administrative-boundaries');
}