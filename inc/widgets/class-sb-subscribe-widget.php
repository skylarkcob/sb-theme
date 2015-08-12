<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Subscribe_Widget extends WP_Widget {
	private $lang;
	public function __construct() {
		$this->lang = SB_Core::get_language();
		parent::__construct('sb_subscribe_widget', 'SB Subscribe',
			array(
				'classname' => 'widget_sb_subscribe widget-sb-subscribe sb-subscribe-widget',
				'description' => ('vi' == $this->lang) ? 'Widget đăng ký nhận tin.' : __('Feedburner subscribe box.', 'sb-theme' ),
			),
			array(
				'width' => 400
			)
		);
	}

	public function widget($args, $instance) {
		$title  = apply_filters('widget_title', SB_PHP::get_single_line_value($instance['title']));
		$account = isset($instance['account']) ? $instance['account'] : '';
		$show_title = (isset($instance['show_title'])) ? $instance['show_title'] : 0;
		$show_title = (bool)$show_title;
		echo $args['before_widget'];
		if($show_title && !empty($title)) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		SB_Theme::the_subscribe_box(array('name' => $account));
		echo $args['after_widget'];
	}

	public function form($instance) {
		$title = '';
		$account = '';
		$show_title = 0;
		if($instance) {
			$title = isset($instance['title']) ? $instance['title'] : '';
			$account = isset($instance['account']) ? $instance['account'] : '';
			$show_title = (isset($instance['show_title'])) ? $instance['show_title'] : 0;
		}
		SB_Widget_Field::before('sb-theme');
		SB_Widget_Field::title($this->get_field_id('title'), $this->get_field_name('title'), $title);

		$args = array(
			'id' => $this->get_field_id('account'),
			'name' => $this->get_field_name('account'),
			'value' => $account,
			'label' => ('vi' == $this->lang) ? 'Tài khoản Feedburner:' : __('Feedburner account:', 'sb-theme')
		);
		SB_Widget_Field::text($args);

		SB_Widget_Field::show_title($this->get_field_id('show_title'), $this->get_field_name('show_title'), $show_title);
		SB_Widget_Field::after();
	}

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = isset($new_instance['title']) ? $new_instance['title'] : '';
		$instance['account'] = isset($new_instance['account']) ? $new_instance['account'] : '';
		$instance['show_title'] = (isset($new_instance['show_title'])) ? $new_instance['show_title'] : 0;
		return $instance;
	}
}