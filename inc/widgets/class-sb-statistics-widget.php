<?php
class SB_Statistics_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct('sb_statistics_widget', 'SB Statistics', array(
            'classname'   => 'widget_sb_statistics widget-sb-statistics',
            'description' => __('Visitor counter.', 'sb-theme'),
        ));
    }

    public function widget($args, $instance) {
        $title  = apply_filters('widget_title', SB_PHP::get_single_line_value($instance['title']));
        echo $args['before_widget'];
        if(!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
            $current_date_time = SB_Core::get_current_date_time();
            $sb_user_online_option = get_option('sb_user_online');
            //print_r($sb_user_online_option);
            ?>
            <div class="sb-statistics-widget">
                <dl>
                    <dt><?php _e('Hôm nay', 'sb-theme'); ?></dt>
                    <dd><?php echo SB_Option::get_visits_today(); ?></dd>
                    <dt><?php _e('Hôm qua', 'sb-theme'); ?></dt>
                    <dd><?php echo SB_Option::get_visits_yesterday(); ?></dd>
                    <dt><?php _e('Tổng truy cập', 'sb-theme'); ?></dt>
                    <dd><?php echo SB_Option::get_visits(); ?></dd>
                    <dt><?php _e('Đang trực tuyến', 'sb-theme'); ?></dt>
                    <dd><?php echo SB_Option::get_visitor_online(); ?></dd>
                </dl>
            </div>
            <?php
        }
        echo $args['after_widget'];
    }

    public function form($instance) {
        if($instance) {
            $title = isset($instance['title']) ? $instance['title'] : '';
        } else {
            $title = '';
        }
        SB_Widget_Field::before('sb-banner-widget');
        SB_Widget_Field::title($this->get_field_id('title'), $this->get_field_name('title'), $title);
        SB_Widget_Field::after();
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $title = isset($new_instance['title']) ? $new_instance['title'] : '';
        $instance['title'] = $title;
        return $instance;
    }
}