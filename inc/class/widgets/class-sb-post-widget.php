<?php
class SB_Post_Widget extends WP_Widget {
	public $types = array();
	private $order_by = array();
	private $order_type = array();
	
	private $default_number = 5;

	public function __construct() {
		$this->init();
	
		parent::__construct( 'sb_post_widget', __( 'SB Post', SB_DOMAIN ), array(
			'classname'   => 'widget_sb_post',
			'description' => __( SB_PHP::add_dotted(SB_WP::phrase("show_post_on_sidebar")), SB_DOMAIN ),
		));
	}
	
	private function init() {
		$this->type_init();
		$this->order_by_init();
		$this->order_type_init();
	}
	
	private function type_init() {
		$this->types = array(
			'recent'	=> SB_WP::phrase("new_post"),
			'random'	=> SB_WP::phrase("random_post"),
			'comment'	=> SB_WP::phrase("most_comment_post"),
			'view'		=> SB_WP::phrase("most_view_post"),
			'like'		=> SB_WP::phrase("most_like_post"),
			'category'	=> SB_WP::phrase("post_by_category"),
			'favorite'	=> SB_WP::phrase("favorite_post")
		);
	}
	
	private function order_type_init() {
		$this->order_type = array(
			'desc'	=> SB_WP::phrase('order_desc'),
			'asc'	=> SB_WP::phrase('order_asc')
		);
	}
	
	private function order_by_init() {
		$this->order_by = array(
			'title'		=> SB_WP::phrase('title'),
			'post_date'	=> SB_WP::phrase('post_date')
		);
	}
	
	public function widget($args, $instance) {
		$arr_tmp = $args;
		$number = empty( $instance['number'] ) ? $this->default_number : absint( $instance['number'] );
		$title  = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$type = isset($instance['type']) ? $instance['type'] : 'recent';
		$taxonomy = isset($instance['taxonomy']) ? $instance['taxonomy'] : 'category';
		$order_by = isset($instance['order_by']) ? $instance['order_by'] : 'title';
		$order_type = $instance['order_type'];
		$order_type = strtoupper($order_type);
		//echo 'AAA'.$order_type;
		$only_thumbnail = isset($instance['only_thumbnail']) ? absint($instance['only_thumbnail']) : 0;
		switch($type) {
			case 'random':
				$args = array(
					'posts_per_page'	=> $number,
					'orderby'			=> 'rand',
					'order'				=> $order_type
				);
				break;
			case 'comment':
				$args = array(
					'posts_per_page'	=> $number,
					'orderby'			=> 'comment_count',
					'order'				=> $order_type
				);
				break;
			case 'view':
				$args = array(
					'posts_per_page'	=> $number,
					'meta_key'			=> 'views',
					'orderby'			=> 'meta_value_num',
					'order'				=> $order_type
				);
				break;
			case 'like':
				$args = array(
					'posts_per_page'	=> $number,
					'meta_key'			=> 'likes',
					'orderby'			=> 'meta_value_num',
					'order'				=> $order_type
				);
				break;
			case 'favorite':
				$user = new SB_User();
				$list_posts = array();
				if(!empty($user)) {
					$list_posts = (array)$user->get_favorite_post();
				}				
				if(count($list_posts) < 1) {
					array_push($list_posts, 0);
				}
				$args = array(
					'posts_per_page'	=> $number,
					'post__in'			=> $list_posts,
					'orderby'			=> $order_by,
					'order'				=> $order_type
				);
				break;
			case 'category':
				$args = array();
				$category = $instance['category'];
				if($category > 0) {
					//$child_cats = get_term_children($category, $taxonomy);
					//array_push($child_cats, $category);
					//$child_cats = array_merge($child_cats);
					$args = array(
						'posts_per_page'	=> $number,
						'orderby'			=> $order_by,
						'order'				=> $order_type,
						'tax_query'		=> array(
							array(
								'taxonomy'	=> $taxonomy,
								'field'		=> 'id',
								'terms'		=> $category
							)
						)
					);
				}
				break;
			default:
				$args = array(
					'posts_per_page'	=> $number,
					'orderby'			=> $order_by,
					'order'				=> $order_type
				);
		}
		//print_r($args);
		$sb_post = new WP_Query($args);
		//print_r($sb_post);
		if($sb_post->have_posts()) {
			if("favorite" == $type && !is_user_logged_in()) return;
			$args = $arr_tmp;
			echo $args['before_widget'];
			if(!empty($title)) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			?>
			<div class="sb-post-widget">
				<div class="sb-post-widget-inner">
					<ol class="list-unstyled">
						<?php while($sb_post->have_posts()) : $sb_post->the_post(); $a_post = new SB_Post(); ?>
							<li>
								<div <?php post_class("sb-post-widget"); ?>>
									<?php $a_post->thumbnail(); ?>
									<?php if(1 != $only_thumbnail) : ?>
									<header class="entry-header">
										<div class="entry-meta">
											<?php $a_post->title("h3"); ?>
											<div class="entry-info">
												<?php $a_post->meta(); ?>
												<?php $a_post->comment_link(); ?>
											</div>
										</div>
									</header>
									<?php endif; ?>
								</div>
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
		$category = isset( $instance['category'] ) ? $instance['category'] : 0;
		$taxonomy = isset( $instance['taxonomy'] ) ? $instance['taxonomy'] : 'category';
		$only_thumbnail = isset($instance['only_thumbnail']) ? absint($instance['only_thumbnail']) : 0;
		$order_by = isset( $instance['order_by'] ) ? $instance['order_by'] : 'title';
		$order_type = isset($instance['order_type']) ? $instance['order_type'] : 'desc';
		?>
		<div class="sb-post-widget sb-widget">
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Tiêu đề:', 'sbtheme' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Số lượng bài viết:', SB_DOMAIN ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3" autocomplete="off">
			</p>

			<p id="sbPostType">
				<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php _e( 'Chọn kiểu hiển thị:', SB_DOMAIN ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" class="widefat sb-post-type" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>">
					<?php foreach ( $this->types as $key => $value ) : ?>
					<?php if(("view" == $key && !SB_WP::is_support_post_views()) || ("like" == $key && !SB_WP::is_support_post_likes())) continue; ?>
					<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $type, $key ); ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<?php $taxs = SB_WP::get_all_taxonomy_hierarchical(); ?>
			<?php if($taxs) : ?>
				<?php if("category" == $type || true) : ?>
					<?php $style = "display: block"; ?>
				<?php endif; ?>
				<p id="sbPostCats" style="<?php echo $style; ?>">
					<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php _e( SB_WP::phrase('choose_category').':', SB_DOMAIN ); ?></label>
					<select id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" class="widefat sb-post-cat" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>">
						<?php foreach($taxs as $tax) : ?>
							<?php $terms = get_terms($tax->name); ?>
							<optgroup label="<?php echo $tax->labels->name; ?>">
								<?php foreach ( $terms as $cat ) : ?>
								<option value="<?php echo $cat->term_id; ?>"<?php selected( $category, $cat->term_id ); ?> data-taxonomy="<?php echo $tax->name; ?>"><?php echo $cat->name; ?> (<?php echo $cat->count; ?>)</option>
								<?php endforeach; ?>
							</optgroup>
						<?php endforeach; ?>
					</select>
					<input id="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>" class="widefat taxonomy" name="<?php echo esc_attr( $this->get_field_name( 'taxonomy' ) ); ?>" type="hidden" value="<?php echo esc_attr( $taxonomy ); ?>">
				</p>
			<?php endif; ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'category_type' ) ); ?>"><?php _e( SB_PHP::add_colon(SB_WP::phrase("category_type")), SB_DOMAIN ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'category_type' ) ); ?>" class="widefat sb-category-type" name="<?php echo esc_attr( $this->get_field_name( 'category_type' ) ); ?>">
					<?php foreach ( $taxs as $tax ) : ?>
					<option value="<?php echo $tax->name; ?>"<?php selected( $taxonomy, $tax->name ); ?>><?php echo $tax->labels->name; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'only_thumbnail' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'only_thumbnail' ) ); ?>" type="checkbox" value="<?php echo esc_attr( $only_thumbnail ); ?>" size="3" autocomplete="off" <?php checked( $only_thumbnail, 1, true ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'only_thumbnail' ) ); ?>"><?php _e( SB_WP::phrase("only_show_thumbnail"), SB_DOMAIN ); ?></label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'order_by' ) ); ?>"><?php _e( SB_PHP::add_colon(SB_WP::phrase("order_by")), SB_DOMAIN ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'order_by' ) ); ?>" class="widefat sb-category-type" name="<?php echo esc_attr( $this->get_field_name( 'order_by' ) ); ?>">
					<?php foreach ( $this->order_by as $key => $order ) : ?>
					<option value="<?php echo $key; ?>"<?php selected( $order_by, $key ); ?>><?php echo $order; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'order_type' ) ); ?>"><?php _e( SB_PHP::add_colon(SB_WP::phrase("order_type")), SB_DOMAIN ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'order_type' ) ); ?>" class="widefat sb-category-type" name="<?php echo esc_attr( $this->get_field_name( 'order_type' ) ); ?>">
					<?php foreach ( $this->order_type as $key => $value ) : ?>
					<option value="<?php echo $key; ?>"<?php selected( $order_type, $key ); ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			
		</div>
		<?php
	}
	
	public function update($new_instance, $instance) {
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags( $new_instance['title'] ) : '';
		$instance['type'] = $new_instance['type'];
		$instance['category'] = $new_instance['category'];
		$instance['number'] = empty( $new_instance['number'] ) ? $this->default_number : absint( $new_instance['number'] );
		$instance['taxonomy'] = $new_instance['category_type'];
		$instance['only_thumbnail'] = isset($new_instance['only_thumbnail']) ? 1 : 0;
		$instance['order_by'] = $new_instance['order_by'];
		$instance['order_type'] = $new_instance['order_type'];
		return $instance;
	}
}
?>