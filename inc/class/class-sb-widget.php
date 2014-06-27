<?php
class SB_Widget {
	public $widgets = array(
		'SB_Post_Widget',
		'SB_Ads_Widget'
	);
	
	public function __construct() {
		global $sb_enable_widgets;
		$sb_enable_widgets = (array)$sb_enable_widgets;
		foreach($sb_enable_widgets as $widget) {
			if(in_array($widget, $this->widgets)) {
				$this->register($widget);
			}
		}
	}
	
	public function register($name) {		
		if(class_exists($name)) {
			register_widget($name);
		}
	}
}

class SB_Post_Widget extends WP_Widget {
	public $types = array(
		'recent'	=> 'Bài viết mới',
		'random'	=> 'Bài viết ngẫu nhiên',
		'comment'	=> 'Bài viết nhiều bình luận',
		'view'		=> 'Bài viết được xem nhiều',
		'like'		=> 'Bài viết được yêu thích',
		'category'	=> 'Bài viết theo chuyên mục'
	);
	
	private $default_number = 5;

	public function __construct() {
		parent::__construct( 'sb_post_widget', __( 'SB Post', 'sbtheme' ), array(
			'classname'   => 'widget_twentyfourteen_ephemera',
			'description' => __( 'Hiển thị bài viết trên sidebar.', 'sbtheme' ),
		));
	}
	
	public function widget($args, $instance) {
		$arr_tmp = $args;
		$number = empty( $instance['number'] ) ? 2 : absint( $instance['number'] );
		$title  = apply_filters( 'widget_title', empty( $instance['title'] ) ? $format_string : $instance['title'], $instance, $this->id_base );
		$type = $instance['type'];
		switch($type) {
			case 'random':
				$args = array(
					'posts_per_page'	=> $number,
					'orderby'			=> 'rand'
				);
				break;
			case 'comment':
				$args = array(
					'posts_per_page'	=> $number,
					'orderby'			=> 'comment_count'
				);
				break;
			case 'view':
				$args = array(
					'posts_per_page'	=> $number,
					'meta_key'			=> 'views',
					'orderby'			=> 'meta_value_num'
				);
				break;
			case 'like':
				$args = array(
					'posts_per_page'	=> $number,
					'meta_key'			=> 'likes',
					'orderby'			=> 'meta_value_num'
				);
				break;
			case 'category':
				break;
			default:
				$args = array(
					'posts_per_page'	=> $number
				);
		}
		$sb_post = new WP_Query($args);
		if($sb_post->have_posts()) {
			$args = $arr_tmp;
			echo $args['before_widget'];
			echo $args['before_title'] . $title . $args['after_title'];
			?>
			<div class="row">
				<div class="col-xs-12">
					<ol class="list-unstyled">
						<?php while($sb_post->have_posts()) : $sb_post->the_post(); $a_post = new SB_Post(); ?>
							<li>
								<article id="post-<?php the_ID(); ?>" <?php post_class("sb-post-widget"); ?>>
									<?php $a_post->thumbnail(); ?>
									<header class="entry-header">
										<div class="entry-meta">
											<?php $a_post->title("h3"); ?>
											<?php $a_post->meta(); ?>
											<?php $a_post->comment_link(); ?>											
										</div>
									</header>
								</article>
							</li>
						<?php endwhile; wp_reset_postdata(); ?>
					</ol>
				</div>
			</div>
			<?php
			echo $args['after_widget'];
		}
	}
	
	public function form($instance) {
		$title  = empty( $instance['title'] ) ? '' : esc_attr( $instance['title'] );
		$number = empty( $instance['number'] ) ? $this->default_number : absint( $instance['number'] );
		$type = isset( $instance['type'] ) ? $instance['type'] : 'recent';
		?>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Tiêu đề:', 'sbtheme' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Số lượng bài viết:', SB_DOMAIN ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3" autocomplete="off">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php _e( 'Chọn kiểu hiển thị:', SB_DOMAIN ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>">
				<?php foreach ( $this->types as $key => $value ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $type, $key ); ?>><?php echo $value; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}
	
	public function update($new_instance, $instance) {
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags( $new_instance['title'] ) : '';
		$instance['type'] = $new_instance['type'];
		$instance['number'] = empty( $new_instance['number'] ) ? $this->default_number : absint( $new_instance['number'] );
		return $instance;
	}
}
?>