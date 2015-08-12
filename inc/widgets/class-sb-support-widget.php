<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Support_Widget extends WP_Widget {
    private $lang;
    public function __construct() {
        $this->lang = SB_Core::get_language();
        parent::__construct('sb_support_widget', 'SB Support',
            array(
                'classname' => 'widget_sb_support sb-support-widget',
                'description' => ('vi' == SB_Core::get_language()) ? 'Thông tin hỗ trợ khách hàng.' : __('Display support information on sidebar.', 'sb-theme' ),
            ),
            array(
                'width' => 400
            )
        );
    }

    public function widget($args, $instance) {
        $title  = apply_filters('widget_title', SB_PHP::get_single_line_value($instance['title']));

        echo $args['before_widget'];

        if(!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        ?>
        <div class="widget-content">
            <?php
            do_action('sb_theme_support_widget_display', $instance);
            $sidebar_id = isset($args['id']) ? $args['id'] : '';
            if(!empty($sidebar_id)) {
                $sidebar_id = SB_PHP::esc_id($sidebar_id);
                do_action('sb_theme_support_widget_display_' . $sidebar_id, $instance);
            }
            ?>
        </div>
        <?php

        echo $args['after_widget'];
    }

    public function form($instance) {
        if($instance) {
            $title = isset($instance['title']) ? $instance['title'] : '';
            $phone = isset($instance['phone']) ? $instance['phone'] : '';
            $yahoo = isset($instance['yahoo']) ? $instance['yahoo'] : '';
            $skype = isset($instance['skype']) ? $instance['skype'] : '';
            $viber = isset($instance['viber']) ? $instance['viber'] : '';
        } else {
            $title = '';
            $phone = '';
            $yahoo = '';
            $skype = '';
            $viber = '';
        }
        SB_Widget_Field::before('sb-theme');

        SB_Widget_Field::title($this->get_field_id('title'), $this->get_field_name('title'), $title);

        $args = array(
            'id' => $this->get_field_id('phone'),
            'name' => $this->get_field_name('phone'),
            'value'	=> $phone,
            'label' => __('Số điện thoại:', 'sb-theme'),
            'description' => __('Số điện thoại liên hệ.', 'sb-theme'),
            'field_class' => 'widefat'
        );
        SB_Widget_Field::text($args);

        $args = array(
            'id' => $this->get_field_id('yahoo'),
            'name' => $this->get_field_name('yahoo'),
            'value'	=> $yahoo,
            'label' => __('Yahoo:', 'sb-theme'),
            'description' => __('Tài khoản Yahoo của bạn.', 'sb-theme'),
            'field_class' => 'widefat'
        );
        SB_Widget_Field::text($args);

        $args = array(
            'id' => $this->get_field_id('skype'),
            'name' => $this->get_field_name('skype'),
            'value'	=> $skype,
            'label' => __('Skype:', 'sb-theme'),
            'description' => __('Tài khoản Skype của bạn.', 'sb-theme'),
            'field_class' => 'widefat'
        );
        SB_Widget_Field::text($args);

        $args = array(
            'id' => $this->get_field_id('viber'),
            'name' => $this->get_field_name('viber'),
            'value'	=> $viber,
            'label' => __('Viber:', 'sb-theme'),
            'description' => __('Tài khoản Viber của bạn.', 'sb-theme'),
            'field_class' => 'widefat'
        );
        SB_Widget_Field::text($args);

        SB_Widget_Field::after();
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['title'] = isset($new_instance['title']) ? $new_instance['title'] : '';
        $instance['phone'] = isset($new_instance['phone']) ? $new_instance['phone'] : '';
        $instance['yahoo'] = isset($new_instance['yahoo']) ? $new_instance['yahoo'] : '';
        $instance['skype'] = isset($new_instance['skype']) ? $new_instance['skype'] : '';
        $instance['viber'] = isset($new_instance['viber']) ? $new_instance['viber'] : '';

        return $instance;
    }
}