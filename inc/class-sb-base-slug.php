<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Base_Slug {
    private $taxonomies = array();

    public function __construct() {

    }

    public function add_taxonomy($tax_name) {
        array_push($this->taxonomies, $tax_name);
    }

    public function flush_rewrite_rule() {
        flush_rewrite_rules();
    }

    public function execute() {
        if(!$this->use_pretty_permalinks()) {
            return;
        }
        foreach($this->taxonomies as $tax) {
            switch($tax) {
                case 'post_tag':
                    $this->tag_hook();
                    break;
                case 'category':
                    $this->category_hook();
                    break;
            }
        }
    }

    public function use_pretty_permalinks() {
        $permalink_structure = get_option('permalink_structure');
        return (empty($permalink_structure)) ? false : true;
    }

    public function extra_permastructs($taxonomy_name, $tag_struct) {
        global $wp_rewrite;
        $is_old_wp_version = version_compare($GLOBALS['wp_version'], '3.4', '<');
        if($is_old_wp_version) {
            $wp_rewrite->extra_permastructs[$taxonomy_name][0] = $tag_struct;
        } else {
            $wp_rewrite->extra_permastructs[$taxonomy_name]['struct'] = $tag_struct;
        }
    }

    public function get_tax_base_slug($key, $default) {
        $saved = get_option($key);
        $tax_base = (empty($saved)) ? $default : $saved;
        return $tax_base;
    }

    public function add_query_var($query_vars, $query_var) {
        $query_vars[] = $query_var;
        return $query_vars;
    }

    public function control_request($taxonomy_name, $query_vars, $query_var) {
        if(isset($query_vars[$query_var])) {
            $term_name = user_trailingslashit($query_vars[$query_var], $taxonomy_name);
            $term_permalink = home_url($term_name);
            wp_redirect($term_permalink, 301);
            exit;
        }
    }

    public function add_rewrite_rule($taxonomy_name, $tax_base, $wp_query_var, $query_var, $rules) {
        $rules = array();
        $terms = get_terms($taxonomy_name, array('hide_empty' => false));
        foreach($terms as $term) {
            $rules['(' . $term->slug . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?' . $wp_query_var . '=$matches[1]&feed=$matches[2]';
            $rules['(' . $term->slug . ')/page/?([0-9]{1,})/?$'] = 'index.php?' . $wp_query_var . '=$matches[1]&paged=$matches[2]';
            $rules['(' . $term->slug . ')/?$'] = 'index.php?' . $wp_query_var . '=$matches[1]';
        }
        $tax_base = trim($tax_base, '/');
        $rules[$tax_base . '/(.*)$'] = 'index.php?' . $query_var . '=$matches[1]';
        return $rules;
    }

    /*
     * Xóa category base, khi kích hoạt thay vì đường dẫn lưu trữ của category sẽ là http://tenmien.com/category/ten-category thì nay
     * sẽ chuyển thành http://tenmien.com/ten-category
     */
    public function category_hook() {
        add_action('init', array($this, 'category_extra_permastructs'));
        add_filter('query_vars', array($this, 'category_query_vars'));
        add_filter('request', array($this, 'category_request'));
        add_filter('category_rewrite_rules', array($this, 'category_rewrite_rules'));
        add_filter('category_link', array($this, 'category_link'));
        add_action('created_category', array($this, 'flush_rewrite_rule'));
        add_action('edited_category', array($this, 'flush_rewrite_rule'));
        add_action('delete_category', array($this, 'flush_rewrite_rule'));
    }

    public function get_category_base() {
        return $this->get_tax_base_slug('category_base', 'category');
    }

    public function category_extra_permastructs() {
        $this->extra_permastructs('category', '%category%');
    }

    public function category_query_vars($public_query_vars) {
        return $this->add_query_var($public_query_vars, 'category_redirect');
    }

    public function category_request($query_vars) {
        $this->control_request('category', $query_vars, 'category_redirect');
        return $query_vars;
    }

    public function category_rewrite_rules($rules) {
        $rules = $this->add_rewrite_rule('category', $this->get_category_base(), 'category_name', 'category_redirect', $rules);
        return $rules;
    }

    public function category_link($termlink) {
        $category_base = $this->get_category_base();
        return str_replace('/' . $category_base . '/', '/' , $termlink);
    }
    
    /*
     * Xóa tag base, khi kích hoạt thay vì đường dẫn lưu trữ của post_tag sẽ là http://tenmien.com/tag/ten-tag thì nay
     * sẽ chuyển thành http://tenmien.com/ten-tag
     */
    public function tag_hook() {
        add_action('init', array($this, 'tag_extra_permastructs'));
        add_filter('query_vars', array($this, 'tag_query_vars'));
        add_filter('request', array($this, 'tag_request'));
        add_filter('tag_rewrite_rules', array($this, 'tag_rewrite_rules'));
        add_filter('tag_link', array($this, 'tag_link'));
        add_action('created_post_tag', array($this, 'flush_rewrite_rule'));
        add_action('edited_post_tag', array($this, 'flush_rewrite_rule'));
        add_action('delete_post_tag', array($this, 'flush_rewrite_rule'));
    }

    public function get_tag_base() {
        return $this->get_tax_base_slug('tag_base', 'tag');
    }

    public function tag_extra_permastructs() {
        $this->extra_permastructs('post_tag', '%post_tag%');
    }

    public function tag_query_vars($public_query_vars) {
        return $this->add_query_var($public_query_vars, 'tag_redirect');
    }

    public function tag_request($query_vars) {
        $this->control_request('post_tag', $query_vars, 'tag_redirect');
        return $query_vars;
    }

    public function tag_rewrite_rules($rules) {
        $rules = $this->add_rewrite_rule('post_tag', $this->get_tag_base(), 'tag', 'tag_redirect', $rules);
        return $rules;
    }

    public function tag_link($termlink) {
        $tag_base = $this->get_tag_base();
        return str_replace('/' . $tag_base . '/', '/' , $termlink);
    }
}