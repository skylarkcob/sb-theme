<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Icon_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct('sb_icon_widget', 'SB Icon',
            array(
                'classname' => 'widget_sb_icon widget-sb-icon',
                'description' => __('Widget hỗ trợ hiển thị icon.', 'sb-theme' ),
            ),
            array(
                'width' => 400
            )
        );
    }

    public function widget($args, $instance) {
        $title  = apply_filters('widget_title', SB_PHP::get_single_line_value($instance['title']));
        $icon = isset($instance['icon']) ? $instance['icon'] : '';
        $link = isset($instance['link']) ? $instance['link'] : '';
        echo $args['before_widget'];
        do_action('sb_theme_sidebar_' . $args['id'] . '_widget_display', $instance);
        echo $args['after_widget'];
    }

    public function form($instance) {
        global $sb_media_upload;
        $sb_media_upload = true;
        $title = '';
        $icon = '';
        $link = '';
        $text = '';
        if($instance) {
            $title = isset($instance['title']) ? $instance['title'] : '';
            $icon = isset($instance['icon']) ? $instance['icon'] : '';
            $link = isset($instance['link']) ? $instance['link'] : '';
            $text = isset($instance['text']) ? $instance['text'] : '';
        }
        SB_Widget_Field::before('sb-theme');
        SB_Widget_Field::title($this->get_field_id('title'), $this->get_field_name('title'), $title);

        $args = array(
            'id' => $this->get_field_id('icon'),
            'name' => $this->get_field_name('icon'),
            'value' => $icon,
            'label' => __('Icon:', 'sb-theme')
        );
        SB_Widget_Field::media_upload($args);

        $args = array(
            'id' => $this->get_field_id('link'),
            'name' => $this->get_field_name('link'),
            'value' => $link,
            'label' => __('Đường link cho hình ảnh:', 'sb-theme')
        );
        SB_Widget_Field::text($args);

        $args = array(
            'id' => $this->get_field_id('text'),
            'name' => $this->get_field_name('text'),
            'value' => $text,
            'label' => __('Nội dung:', 'sb-theme')
        );
        SB_Widget_Field::textarea($args);
        SB_Widget_Field::after();
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $title = isset($new_instance['title']) ? $new_instance['title'] : '';
        $icon = isset($new_instance['icon']) ? $new_instance['icon'] : '';
        $link = isset($new_instance['link']) ? $new_instance['link'] : '';
        $text = isset($new_instance['text']) ? $new_instance['text'] : '';
        $instance['title'] = $title;
        $instance['icon'] = $icon;
        $instance['link'] = $link;
        $instance['text'] = $text;
        return $instance;
    }
}