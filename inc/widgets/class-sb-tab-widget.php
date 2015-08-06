<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Tab_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct( 'sb_tab_widget', 'SB Tab', array(
			'classname'   => 'widget_sb_tab sb-tab-widget',
			'description' => __('Hiển thị widget dưới dạng tab.', 'sb-theme'),
		));
	}
		
	public function widget($args, $instance) {
		add_filter('dynamic_sidebar_params', array($this, 'sb_tab_widget_param'));
		$title  = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$sidebar = isset($instance['sidebar']) ? $instance['sidebar'] : '';
		echo $args['before_widget'];
		if(empty($sidebar)) {
			echo '<p>'.__('Xin vui lòng chọn sidebar chứa các tab widget trước.', 'sb-theme').'</p>';
		} elseif($args['id'] != $sidebar) { ?>
			<div class="sb-tab-content">
				<ul class="nav nav-pills list-tab sb-tabs"></ul>
				<div class="tab-content sb-tab-container">
					<?php
					if(is_active_sidebar($sidebar)) {
						dynamic_sidebar($sidebar);
					} else {
						$sidebar_tmp = SB_Core::get_sidebar_by('id', $sidebar);
						if($sidebar_tmp) {
							$sidebar_name = $sidebar_tmp['name'];
						}
						?>
						<p><?php printf(__('Xin vui lòng kéo các widget cần hiển thị vào sidebar %s.', 'sb-theme'), $sidebar_name); ?></p>
						<?php
					}
					?>
				</div>
			</div>
		<?php }
		echo $args['after_widget'];
		remove_filter('dynamic_sidebar_params', array($this, 'sb_tab_widget_param'));
	}
	
	public function form($instance) {
		$title  = empty( $instance['title'] ) ? '' : esc_attr( $instance['title'] );
		$sidebar  = empty( $instance['sidebar'] ) ? '' : esc_attr( $instance['sidebar'] );
		$sidebars = SB_Core::get_sidebar(); ?>
		<div class="sb-tab-widget sb-widget">
            <?php
			SB_Widget_Field::title($this->get_field_id('title'), $this->get_field_name('title'), $title);

            $args = array(
                'id' => $this->get_field_id('sidebar'),
                'name' => $this->get_field_name('sidebar'),
                'value' => $sidebar,
                'list_options' => $sidebars,
                'label' => __('Chọn sidebar:', 'sb-theme' ),
                'field_class' => 'widefat sb-list-sidebars',
                'paragraph_class' => 'list-sidebar'
            );
            SB_Widget_Field::select_sidebar($args);
            ?>
		</div> <?php
	}
	
	public function update($new_instance, $instance) {
		$instance['title'] = isset($new_instance['title']) ? strip_tags($new_instance['title']) : '';
		$instance['sidebar'] = isset($new_instance['sidebar']) ? $new_instance['sidebar'] : '';
		return $instance;
	}
	
	public function sb_tab_widget_param($params) {
		$widget_id = $params[0]['widget_id'];
		$widget_class = SB_Core::build_widget_class($widget_id);
        $params[0]['before_widget'] = '<div id="' . $widget_id . '" class="tab-item tab-pane ' . $widget_class . '">';
        $params[0]['after_widget'] = '</div>';
        $params[0]['before_title'] = '<a href="#" class="tab-title" data-toggle="tab">';
        $params[0]['after_title'] = '</a>';
        return $params;
    }

}