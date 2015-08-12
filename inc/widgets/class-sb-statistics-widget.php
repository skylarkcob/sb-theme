<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Statistics_Widget extends WP_Widget {
    private $lang;
    public function __construct() {
        $this->lang = SB_Core::get_language();
        parent::__construct('sb_statistics_widget', 'SB Statistics', array(
            'classname'   => 'widget_sb_statistics widget-sb-statistics sb-statistics-widget',
            'description' => ('vi' == $this->lang) ? 'Thống kê truy cập.' : __('Visitor counter.', 'sb-theme'),
        ));
    }

    public function widget($args, $instance) {
        $title  = apply_filters('widget_title', SB_PHP::get_single_line_value($instance['title']));
        echo $args['before_widget'];
        if(!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
            $current_date_time = SB_Core::get_current_date_time();
            $sb_user_online_option = get_option('sb_user_online');
            $today_label = apply_filters('sb_theme_statistics_today_label', __('Hôm nay', 'sb-theme'));
            $yesterday_label = apply_filters('sb_theme_statistics_yesterday_label', __('Hôm qua', 'sb-theme'));
            $total_label = apply_filters('sb_theme_statistics_total_label', __('Tổng truy cập', 'sb-theme'));
            $online_label = apply_filters('sb_theme_statistics_online_label', __('Đang trực tuyến', 'sb-theme'));
            ?>
            <div class="sb-statistics-widget">
                <dl>
                    <dt class="today-label"><?php echo $today_label; ?></dt>
                    <dd class="today-count"><?php echo SB_Option::get_visits_today(); ?></dd>
                    <dt class="yesterday-label"><?php echo $yesterday_label; ?></dt>
                    <dd class="yesterday-count"><?php echo SB_Option::get_visits_yesterday(); ?></dd>
                    <dt class="total-label"><?php echo $total_label; ?></dt>
                    <dd class="total-count"><?php echo SB_Option::get_visits(); ?></dd>
                    <dt class="online-label"><?php echo $online_label; ?></dt>
                    <dd class="online-count"><?php echo SB_Option::get_visitor_online(); ?></dd>
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
        SB_Widget_Field::before('sb-theme');
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