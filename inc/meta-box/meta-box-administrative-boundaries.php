<?php
SB_Theme::the_meta_box_nonce();

$post_id = $GLOBALS['post']->ID;
$areas = SB_Post::get_administrative_boundaries($post_id);

SB_Theme::meta_box_before('administrative-boundaries administrative-areas');

$key = 'province';
$value = $areas[$key];
$province = $value;
$args = array(
    'name' => sb_build_meta_name($key),
    'label' => 'Tỉnh thành',
    'taxonomy' => $key,
    'value' => $value,
    'field_class' => 'display-block width-medium form-control ' . $key,
    'container_class' => 'form-group',
    'attributes' => array(
        'data-taxonomy' => $key
    )
);
SB_Field::select_term($args);

$key = 'district';
$value = $areas[$key];
$district = $value;
$args = array(
    'name' => sb_build_meta_name($key),
    'label' => 'Quận huyện',
    'taxonomy' => $key,
    'value' => $value,
    'field_class' => 'display-block width-medium form-control ' . $key,
    'load_item' => false,
    'container_class' => 'form-group',
    'attributes' => array(
        'data-taxonomy' => $key
    )
);

if($province > 0) {
    $terms = SB_Term::get_district_by_province($areas['province']);
    if(is_array($terms)) {
        $custom_options = '';
        foreach($terms as $term) {
            if(is_object($term)) {
                $custom_options .= '<option value="' . $term->term_id . '" ' . selected($term->term_id, $value, false) . '>' . $term->name . '</option>';
            }
        }
        $args['custom_options'] = $custom_options;
    }
} elseif($district > 0) {
    $province = SB_Term::get_province_of_district($district);
    if($province > 0) {
        $terms = SB_Term::get_district_by_province($province);
        if(is_array($terms)) {
            $custom_options = '';
            foreach($terms as $term) {
                if(is_object($term)) {
                    $custom_options .= '<option value="' . $term->term_id . '" ' . selected($term->term_id, $value, false) . '>' . $term->name . '</option>';
                }
            }
            $args['custom_options'] = $custom_options;
        }
    }
}
SB_Field::select_term($args);

$key = 'ward';
$value = $areas[$key];
$ward = $value;
$args = array(
    'name' => sb_build_meta_name($key),
    'label' => 'Phường xã',
    'taxonomy' => $key,
    'value' => $value,
    'field_class' => 'display-block width-medium form-control ' . $key,
    'container_class' => 'form-group',
    'load_item' => false,
    'attributes' => array(
        'data-taxonomy' => $key
    )
);
if($district > 0) {
    $terms = SB_Term::get_ward_by_district($areas['district']);
    if(is_array($terms)) {
        $custom_options = '';
        foreach($terms as $term) {
            if(is_object($term)) {
                $custom_options .= '<option value="' . $term->term_id . '" ' . selected($term->term_id, $value, false) . '>' . $term->name . '</option>';
            }
        }
        $args['custom_options'] = $custom_options;
    }
}
SB_Field::select_term($args);

$key = 'hamlet';
$value = $areas[$key];
$hamlet = $value;
$args = array(
    'name' => sb_build_meta_name($key),
    'label' => 'Thôn xóm',
    'taxonomy' => $key,
    'value' => $value,
    'field_class' => 'display-block width-medium form-control ' . $key,
    'container_class' => 'form-group',
    'load_item' => false,
    'attributes' => array(
        'data-taxonomy' => $key
    )
);
if($ward > 0) {
    $terms = SB_Term::get_hamlet_by_ward($areas['ward']);
    if(is_array($terms)) {
        $custom_options = '';
        foreach($terms as $term) {
            if(is_object($term)) {
                $custom_options .= '<option value="' . $term->term_id . '" ' . selected($term->term_id, $value, false) . '>' . $term->name . '</option>';
            }
        }
        $args['custom_options'] = $custom_options;
    }
}
SB_Field::select_term($args);

$key = 'street';
$value = $areas[$key];
$street = $value;
$args = array(
    'name' => sb_build_meta_name($key),
    'label' => 'Đường phố',
    'taxonomy' => $key,
    'value' => $value,
    'field_class' => 'display-block width-medium form-control ' . $key,
    'container_class' => 'form-group',
    'load_item' => false,
    'attributes' => array(
        'data-taxonomy' => $key
    )
);
if($ward > 0) {
    $terms = SB_Term::get_street_by_ward($areas['ward']);
    if(is_array($terms)) {
        $custom_options = '';
        foreach($terms as $term) {
            if(is_object($term)) {
                $custom_options .= '<option value="' . $term->term_id . '" ' . selected($term->term_id, $value, false) . '>' . $term->name . '</option>';
            }
        }
        $args['custom_options'] = $custom_options;
    } elseif($district > 0) {
        $terms = SB_Term::get_street_by_district($areas['district']);
        if(is_array($terms)) {
            $custom_options = '';
            foreach($terms as $term) {
                if(is_object($term)) {
                    $custom_options .= '<option value="' . $term->term_id . '" ' . selected($term->term_id, $value, false) . '>' . $term->name . '</option>';
                }
            }
            $args['custom_options'] = $custom_options;
        }
    }
}
SB_Field::select_term($args);

SB_Theme::meta_box_after();