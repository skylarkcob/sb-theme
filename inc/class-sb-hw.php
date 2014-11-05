<?php
class SB_HW {
    public static function get_reference_cats($args = array()) {
        return SB_Term::get('rcat', $args);
    }

    public static function get_projects($args = array()) {
        $args['post_type'] = 'project';
        $all_project = SB_Post::get_by_slug('tat-ca-du-an', 'project');
        if($all_project) {
            $args['post__not_in'] = array($all_project->ID);
        }
        return new WP_Query($args);
    }

    public static function get_project_cats($args = array()) {
        return SB_Term::get('pcat', $args);
    }

    public static function get_project_by_category($cat_id, $args = array()) {
        if($cat_id < 1) {
            return self::get_projects($args);
        }
        $tax_query = array(
            'taxonomy' => 'pcat',
            'field' => 'id',
            'terms' => $cat_id
        );
        $args = SB_Query::build_tax_query($tax_query, $args);
        return self::get_projects($args);
    }

    public static function get_project_price($project_id) {
        return SB_Post::get_meta($project_id, 'wpcf-project-price');
    }

    public static function the_project_price($project_id) {
        $price = self::get_project_price($project_id);
        $price = number_format($price, 0, ',', '.') . ' đ';
        echo $price;
    }

    public static function set_go_url($url) {
        global $sb_go_url;
        $sb_go_url = esc_url($url);
    }

    public static function get_go_url() {
        global $sb_go_url;
        return $sb_go_url;
    }

    public static function get_aff_url($post_id) {
        return SB_Post::get_meta($post_id, 'wpcf-affiliate-url');
    }

    public static function get_aff_by_slug($slug) {
        return SB_Post::get_by_slug($slug, 'aff');
    }
}