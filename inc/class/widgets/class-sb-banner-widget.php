<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SB_Banner_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct( 'sb_banner_widget', __( 'SB Banner', SB_DOMAIN ), array(
			'classname'   => 'widget_sb_banner',
			'description' => __( SB_PHP::add_dotted(SB_WP::phrase("show_banner_on_sidebar")), SB_DOMAIN ),
		));
	}
	
	public function widget($args, $instance) {
		$title  = apply_filters('widget_title', SB_PHP::get_single_line_value($instance['title']));
		$banner_image = SB_WP::get_url_value($instance['banner_image']);
		$banner_url = SB_WP::get_url_value($instance['banner_url']);
		$show_title = (isset($instance['use_title'])) ? $instance['use_title'] : 0;
		if(!empty($banner_image)) {
			echo $args['before_widget'];
			if(!empty($title) && (bool)$show_title) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			if(!empty($banner_url)) {
				echo '<a class="sb-banner-link" title="'.$title.'" href="'.$banner_url.'">';
			}
			echo '<img class="sb-banner-image" alt="'.$title.'" src="'.$banner_image.'">';
			if(!empty($banner_url)) {
				echo '</a>';
			}
			echo $args['after_widget'];
		}
	}
	
	public function form($instance) {
		global $sb_media_upload;
		$sb_media_upload = true;
		if($instance) {
			$show_title = (isset($instance['use_title'])) ? $instance['use_title'] : 0;
			$title = SB_PHP::get_single_line_value($instance['title']);
			$banner_image = SB_WP::get_url_value($instance['banner_image']);
			$banner_url = SB_WP::get_url_value($instance['banner_url']);
		} else {
			$show_title = 0;
			$title = '';
			$banner_image = '';
			$banner_url = '';
		}
		//echo $show_title;
		?>
		<div class="sb-banner-widget sb-widget">
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( SB_PHP::add_colon(SB_WP::phrase("title")), SB_DOMAIN ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p id="sbBannerImage" class="sb-media-upload">
				<label for="<?php echo esc_attr( $this->get_field_id( 'banner_image' ) ); ?>"><?php _e( SB_PHP::add_colon(SB_WP::phrase("banner_image_url")), SB_DOMAIN ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'banner_image' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'banner_image' ) ); ?>" type="text" value="<?php echo esc_attr( $banner_image ); ?>">
				<?php SB_WP::insert_media_button(); ?>
			</p>
			<p id="sbBannerUrl" class="sb-input-url">
				<label for="<?php echo esc_attr( $this->get_field_id( 'banner_url' ) ); ?>"><?php _e( SB_PHP::add_colon(SB_WP::phrase("banner_url")), SB_DOMAIN ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'banner_url' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'banner_url' ) ); ?>" type="text" value="<?php echo esc_attr( $banner_url ); ?>">
			</p>
			
			<?php
			$args = array(
				'id'			=> $this->get_field_id('use_title'),
				'name'			=> $this->get_field_name('use_title'),
				'value'			=> $show_title,
				'description'	=> SB_PHP::add_dotted(SB_WP::phrase("show_title")),
				'paragraph_id'	=> 'useTitle'
			);
			?>
			<?php SB_Theme::widget_field_checkbox($args); ?>
		</div>
		<?php
	}
	
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$show_title = (isset($new_instance['use_title'])) ? 1 : 0;
		$title = SB_PHP::get_single_line_value($new_instance['title']);
		$banner_image = SB_WP::get_url_value($new_instance['banner_image']);
		$banner_url = SB_WP::get_url_value($new_instance['banner_url']);
		
		$instance['title'] = $title;
		$instance['banner_image'] = $banner_image;
		$instance['banner_url'] = $banner_url;
		$instance['use_title'] = $show_title;
		
		return $instance;
	}
}
?>