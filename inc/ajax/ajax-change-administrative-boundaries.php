<?php
$result = array(
    'successful' => false
);
$taxonomy = isset($_POST['taxonomy']) ? $_POST['taxonomy'] : '';
$term = isset($_POST['term']) ? $_POST['term'] : '';
$tax = SB_Core::get_taxonomy_info($taxonomy);
$term = absint($term);
if(is_object($tax)) {
    $result['successful'] = true;
    $result['html_data'] = '';
    $terms = array();
    switch($taxonomy) {
        case 'province':
            $result['html_data'] = '<option value="0">Chọn quận huyện</option>';
            $terms = SB_Term::get_district_by_province($term);
            break;
        case 'district':
            $result['html_data'] = '<option value="0">Chọn phường xã</option>';
            $terms = SB_Term::get_ward_by_district($term);
            $result['html_street'] = '<option value="0">Chọn đường phố</option>';
            $streets = SB_Term::get_street_by_district($term);
            if(is_array($streets)) {
                foreach($streets as $term) {
                    if(is_object($term)) {
                        $result['html_street'] .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
                    }
                }
            }
            break;
        case 'ward':
            $result['html_data'] = '<option value="0">Chọn thôn xóm</option>';
            $terms = SB_Term::get_hamlet_by_ward($term);
            break;
        case 'hamlet':
            break;
    }
    if(is_array($terms)) {
        foreach($terms as $term) {
            if(is_object($term)) {
                $result['html_data'] .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
            }
        }
    }
}
echo json_encode($result);