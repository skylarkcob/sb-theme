<?php
class SB_Banner_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct( 'sb_banner_widget', __( 'SB Banner', SB_DOMAIN ), array(
			'classname'   => 'widget_sb_banner',
			'description' => __( 'Hiển thị banner trên sidebar.', SB_DOMAIN ),
		));
	}
	
	public function widget($args, $instance) {
		
	}
	
	public function form($instance) {
	
	}
	
	public function update($new_instance, $instance) {
	
	}
}
?>