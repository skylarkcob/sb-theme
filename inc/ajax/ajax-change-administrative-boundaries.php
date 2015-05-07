<?php
$result = array(
    'successful' => false
);
$taxonomy = isset($_POST['taxonomy']) ? $_POST['taxonomy'] : '';
$term = isset($_POST['term']) ? $_POST['term'] : '';
$tax = SB_Core::get_taxonomy_info($taxonomy);
$term = absint($term);
$term_id = $term;
if(is_object($tax)) {
    $result['successful'] = true;
    $result['html_data'] = '';
    $terms = array();
    switch($taxonomy) {
        case 'province':
            $result['html_data'] = '<option value="0" data-taxonomy="">-- Chọn quận huyện --</option>';
            $terms = SB_Term::get_district_by_province($term);
            break;
        case 'district':
            $result['html_data'] = '<option value="0" data-taxonomy="">-- Chọn phường xã --</option>';
            $terms = SB_Term::get_ward_by_district($term);
            $result['html_street'] = '<option value="0" data-taxonomy="">-- Chọn đường phố --</option>';
            $streets = SB_Term::get_street_by_district($term);
            if(is_array($streets)) {
                foreach($streets as $term) {
                    if(is_object($term)) {
                        $result['html_street'] .= '<option value="' . $term->term_id . '" data-taxonomy="street">' . $term->name . '</option>';
                    }
                }
            }
            $_SESSION['sb_theme_current_district'] = $term_id;
            break;
        case 'ward':
            $result['html_data'] = '<option value="0" data-taxonomy="">-- Chọn thôn xóm --</option>';
            $terms = SB_Term::get_hamlet_by_ward($term);
            $streets = SB_Term::get_street_by_ward($term);
            $result['html_street'] = '<option value="0" data-taxonomy="">-- Chọn đường phố --</option>';
            if(is_array($streets)) {
                foreach($streets as $term) {
                    if(is_object($term)) {
                        $result['html_street'] .= '<option value="' . $term->term_id . '" data-taxonomy="street">' . $term->name . '</option>';
                    }
                }
            }
            break;
        case 'hamlet':
            break;
    }
    if(is_array($terms)) {
        foreach($terms as $term) {
            if(is_object($term)) {
                $result['html_data'] .= '<option value="' . $term->term_id . '" data-taxonomy="' . $taxonomy . '">' . $term->name . '</option>';
            }
        }
    }
}
echo json_encode($result);