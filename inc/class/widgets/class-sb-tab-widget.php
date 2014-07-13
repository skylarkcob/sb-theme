<?php
class SB_Tab_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct( 'sb_tab_widget', __( 'SB Tab', SB_DOMAIN ), array(
			'classname'   => 'widget_sb_tab',
			'description' => __( 'Tạo widget tab.', SB_DOMAIN ),
		));
	}
		
	public function widget($args, $instance) {
		add_filter('dynamic_sidebar_params', array($this, 'sb_tab_widget_param'));
		$title  = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$sidebar = $instance['sidebar'];
		echo $args['before_widget'];
		if(empty($sidebar)) {
			echo '<p>Bạn chưa chọn sidebar để hiển thị.</p>';
		} elseif($args['id'] != $sidebar) { ?>
			<div class="sb-tab-widget">
				<ul class="nav nav-pills list-tab"></ul>
				<div class="tab-content">
					<?php if(is_active_sidebar($sidebar)) : ?>
						<?php dynamic_sidebar($sidebar); ?>
					<?php else : ?>
						<?php
						$sidebar_tmp = SB_WP::get_sidebar_by('id', $sidebar);
						if($sidebar_tmp) {
							$sidebar_name = $sidebar_tmp['name'];
						}
						?>
						<p>Xin vui lòng đặt widget vào sidebar <?php echo $sidebar_name; ?>.</p>
					<?php endif; ?>
				</div>
			</div>
		<?php }
		echo $args['after_widget'];
		remove_filter('dynamic_sidebar_params', array($this, 'sb_tab_widget_param'));
	}
	
	public function form($instance) {
		$title  = empty( $instance['title'] ) ? '' : esc_attr( $instance['title'] );
		$sidebar  = empty( $instance['sidebar'] ) ? '' : esc_attr( $instance['sidebar'] );
		$sidebars = SB_WP::get_sidebar(); ?>
		<div class="sb-tab-widget sb-widget">
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Tiêu đề:', SB_DOMAIN ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p id="listSidebars">
				<label for="<?php echo esc_attr( $this->get_field_id( 'sidebar' ) ); ?>"><?php _e( 'Chọn sidebar:', SB_DOMAIN ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'sidebar' ) ); ?>" class="widefat sb-list-sidebars" name="<?php echo esc_attr( $this->get_field_name( 'sidebar' ) ); ?>">
					<?php
					foreach ( $sidebars as $id => $sb ) {
						$name = $sb['name'];
						if(SB_PHP::is_string_contain(strtolower($name), 'inactive')) continue;
						$selected = $instance['sidebar'] == $id ? ' selected="selected"' : '';
						printf('<option value="%s"%s>%s</option>', $id, $selected, $name);
					} ?>
				</select>
				<em>Không được chọn sidebar đang chứa widget này, nếu không widget sẽ không hiển thị.</em>
			<p>
		</div> <?php
	}
	
	public function update($new_instance, $instance) {
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags( $new_instance['title'] ) : '';
		$instance['sidebar'] = $new_instance['sidebar'];
		return $instance;
	}
	
	public function sb_tab_widget_param($params) {
		$widget_id = $params[0]['widget_id'];
		$widget_class = SB_WP::build_widget_class($widget_id);
        $params[0]['before_widget'] = '<div id="'.$widget_id.'" class="tab-item tab-pane '.$widget_class.'">';
        $params[0]['after_widget'] = '</div>';
        $params[0]['before_title'] = '<a href="#" class="tab-title" data-toggle="tab">';
        $params[0]['after_title'] = '</a>';
        return $params;
    }

}
?>