<?php
class SB_Meta_Box {
    private $id;
    private $post_type;
    private $post_types;
    private $fields;
    private $title;
    private $callback;
    private $context;
    private $priority;
    private $callback_args;
    private $administrative_boundaries_names = array('sbmb_province', 'sbmb_district', 'sbmb_ward', 'sbmb_hamlet', 'sbmb_street');

    public function __construct($args = array()) {
        $this->extract_args($args);
        add_action('add_meta_boxes', array($this, 'add'));
        add_action('save_post', array($this, 'save'));
    }

    private function extract_args($args) {
        if(!is_array($args)) {
            return;
        }
        $id = isset($args['id']) ? $args['id'] : '';
        $post_type = isset($args['post_type']) ? $args['post_type'] : '';
        $post_types = isset($args['post_types']) ? $args['post_types'] : array('post');
        $fields = isset($args['fields']) ? $args['fields'] : array();
        $title = isset($args['title']) ? $args['title'] : apply_filters('sb_theme_metabox_default_title', __('SB Meta Box', 'sb-theme'));
        $callback = isset($args['callback']) ? $args['callback'] : '';
        $context = isset($args['context']) ? $args['context'] : apply_filters('sb_theme_metabox_default_context', 'normal');
        $priority = isset($args['priority']) ? $args['priority'] : apply_filters('sb_theme_metabox_defautl_priority', 'high');
        $callback_args = isset($args['callback_args']) ? $args['callback_args'] : null;
	    if(empty($id)) {
		    $id = 'default';
	    }
	    $id = 'sb_theme_metabox_' . $id;
        $this->id = $id;
        $this->post_type = $post_type;
        $this->post_types = $post_types;
        $this->fields = array();
        $this->title = $title;
        $this->callback = $callback;
        $this->context = $context;
        $this->priority = $priority;
        $this->callback_args = $callback_args;
        foreach($fields as $field) {
            $field['name'] = SB_Core::build_meta_box_field_name($field['name']);
            array_push($this->fields, $field);
        }
    }

    public function add() {
        $post_type = $this->post_type;
        if(empty($post_type)) {
            $post_type = $this->post_types;
        }
        if(is_array($post_type)) {
            foreach($post_type as $screen) {
                add_meta_box($this->id, $this->title, $this->callback, $screen, $this->context, $this->priority, $this->callback_args);
            }
        } else {
            add_meta_box($this->id, $this->title, $this->callback, $post_type, $this->context, $this->priority, $this->callback_args);
        }
    }

    public function save($post_id) {
        if(!SB_Core::check_before_save_post_meta($post_id)) {
            return $post_id;
        }
        foreach($this->fields as $field) {
            $type = isset($field['type']) ? $field['type'] : '';
            $name = isset($field['name']) ? $field['name'] : '';
            if(empty($name)) {
                continue;
            }
            if('checkbox' == $type) {
                $value = isset($_POST[$name]) ? 1 : 0;
            } elseif('datetime' == $type) {
                $value = isset($_POST[$name]) ? SB_PHP::string_to_datetime($_POST[$name], 'Y-m-d') : '';
                if(!empty($value)) {
                    $value = strtotime($value);
                }
            } else {
                $value = isset($_POST[$name]) ? $_POST[$name] : '';
            }
            $meta_value = SB_Core::sanitize($value, $type);
            SB_Post::update_meta($post_id, $name, $meta_value);
            do_action('sb_theme_post_update_meta_box', $post_id, $name, $meta_value);
	        $meta_name = $name;
	        do_action('sb_theme_update_post_meta', $post_id, $meta_name, $meta_value);
            if(SB_Tool::use_administrative_boundaries() && in_array($name, $this->administrative_boundaries_names)) {
                $meta_value = absint($meta_value);
                switch($name) {
                    case 'sbmb_province':
                        if($meta_value > 0) {
                            wp_set_post_terms($post_id, array($meta_value), 'province');
                        }
                        break;
                    case 'sbmb_district':
                        if($meta_value > 0) {
                            wp_set_post_terms($post_id, array($meta_value), 'district');
                        }
                        break;
                    case 'sbmb_ward':
                        if($meta_value > 0) {
                            wp_set_post_terms($post_id, array($meta_value), 'ward');
                        }
                        break;
                    case 'sbmb_hamlet':
                        if($meta_value > 0) {
                            wp_set_post_terms($post_id, array($meta_value), 'hamlet');
                        }
                        break;
                    case 'sbmb_street':
                        if($meta_value > 0) {
                            wp_set_post_terms($post_id, array($meta_value), 'street');
                        }
                        break;
                }
            }
        }
        return $post_id;
    }
}