<?php
class SB_Post_Widget extends WP_Widget {
	public $types = array();
	private $order_by = array();
	private $order_type = array();
	
	private $default_number = 5;
	private $excerpt_length = 75;
	private $thumbnail_size = array(128, 100);
    private $title_length = 50;

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

		$only_thumbnail = isset($instance['only_thumbnail']) ? absint($instance['only_thumbnail']) : 0;
		$show_excerpt = isset($instance['show_excerpt']) ? absint($instance['show_excerpt']) : 0;
		
		$thumbnail_width = empty( $instance['thumbnail_width'] ) ? $this->thumbnail_size[0] : absint( $instance['thumbnail_width'] );
		$thumbnail_height = empty( $instance['thumbnail_height'] ) ? $this->thumbnail_size[1] : absint( $instance['thumbnail_height'] );
		$thumbnail_size = array($thumbnail_width, $thumbnail_height);
		
		$excerpt_length = empty( $instance['excerpt_length'] ) ? $this->excerpt_length : absint( $instance['excerpt_length'] );
		
		$show_author = isset($instance['show_author']) ? absint($instance['show_author']) : 0;
		$show_date = isset($instance['show_date']) ? absint($instance['show_date']) : 0;
		$show_comment_count = isset($instance['show_comment_count']) ? absint($instance['show_comment_count']) : 0;

        $title_length = empty( $instance['title_length'] ) ? $this->title_length : absint( $instance['title_length'] );
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

		$sb_post = new WP_Query($args);

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
									<?php SB_Theme::post_thumbnail(array('size' => $thumbnail_size)); ?>
									<?php if(1 != $only_thumbnail) : ?>
										<div class="post-content">
											<div class="entry-header">
												<h3 class="entry-title"><a rel="bookmark" href="<?php the_permalink(); ?>"><?php echo SB_PHP::substr(get_the_title(), $title_length); ?></a></h3>
											</div>
											<?php if((bool)$show_excerpt) : ?>
                                                <div class="excerpt">
                                                    <?php echo SB_PHP::substr(get_the_excerpt(), $excerpt_length); ?>
                                                </div>
											<?php endif; // Check show_excerpt ?>
											<?php if((bool)$show_author || (bool)$show_date || (bool)$show_comment_count) : ?>
											<div class="post-meta">
												<?php
													if((bool)$show_author) {
														SB_Theme::post_author();
													}
													if((bool)$show_date) {
														SB_Theme::post_date();
													}
													if((bool)$show_comment_count) {
														SB_Theme::post_comment_link();
													}
												?>
											</div>
											<?php endif; // Check if show post meta ?>
										</div>
									<?php endif; // Check only_thumbnail ?>
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
		$show_excerpt = isset($instance['show_excerpt']) ? absint($instance['show_excerpt']) : 0;
		$order_by = isset( $instance['order_by'] ) ? $instance['order_by'] : 'title';
		$order_type = isset($instance['order_type']) ? $instance['order_type'] : 'desc';
		
		$thumbnail_width = empty( $instance['thumbnail_width'] ) ? $this->thumbnail_size[0] : absint( $instance['thumbnail_width'] );
		$thumbnail_height = empty( $instance['thumbnail_height'] ) ? $this->thumbnail_size[1] : absint( $instance['thumbnail_height'] );
		$thumbnail_size = array($thumbnail_width, $thumbnail_height);
		
		$excerpt_length = empty( $instance['excerpt_length'] ) ? $this->excerpt_length : absint( $instance['excerpt_length'] );
        $title_length = empty( $instance['title_length'] ) ? $this->title_length : absint( $instance['title_length'] );

		$show_author = isset($instance['show_author']) ? absint($instance['show_author']) : 0;
		$show_date = isset($instance['show_date']) ? absint($instance['show_date']) : 0;
		$show_comment_count = isset($instance['show_comment_count']) ? absint($instance['show_comment_count']) : 0;
		?>
		<div class="sb-post-widget sb-widget">
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( SB_PHP::add_colon(SB_WP::phrase("title")), SB_DOMAIN ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<?php
			$args = array(
				'id'				=> $this->get_field_id('number'),
				'name'				=> $this->get_field_name('number'),
				'value'				=> $number,
				'description'		=> SB_PHP::add_colon(SB_WP::phrase("post_number")),
				'paragraph_id'		=> 'postNumber',
				'paragraph_class'	=> 'post-number'
			);
			?>
			<?php SB_Theme::widget_field_number($args); ?>

			<p class="post-type">
				<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php _e( SB_PHP::add_colon(SB_WP::phrase("get_post_by")), SB_DOMAIN ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" class="widefat sb-post-type" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>">
					<?php foreach ( $this->types as $key => $value ) : ?>
					<?php if(("view" == $key && !SB_WP::is_support_post_views()) || ("like" == $key && !SB_WP::is_support_post_likes())) continue; ?>
					<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $type, $key ); ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<?php $taxs = SB_WP::get_all_taxonomy_hierarchical(); ?>
			<?php if($taxs) : ?>
				<?php if("category" == $type) : ?>
					<?php $style = "display: block"; ?>
				<?php else : ?>
					<?php $style = "display: none"; ?>
				<?php endif; ?>
				<p class="post-cat" style="<?php echo $style; ?>">
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
			<p style="display: none">
				<label for="<?php echo esc_attr( $this->get_field_id( 'category_type' ) ); ?>"><?php _e( SB_PHP::add_colon(SB_WP::phrase("category_type")), SB_DOMAIN ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'category_type' ) ); ?>" class="widefat sb-category-type" name="<?php echo esc_attr( $this->get_field_name( 'category_type' ) ); ?>">
					<?php foreach ( $taxs as $tax ) : ?>
					<option value="<?php echo $tax->name; ?>"<?php selected( $taxonomy, $tax->name ); ?>><?php echo $tax->labels->name; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			
			<?php
			$args = array(
				'id'				=> $this->get_field_id('only_thumbnail'),
				'name'				=> $this->get_field_name('only_thumbnail'),
				'value'				=> $only_thumbnail,
				'description'		=> SB_PHP::add_dotted(SB_WP::phrase("only_show_thumbnail")),
				'paragraph_id'		=> 'onlyThumbnail',
				'paragraph_class'	=> 'only-thumbnail'
			);
			?>
			<?php SB_Theme::widget_field_checkbox($args); ?>
			
			<?php
			$args = array(
				'id_width'			=> $this->get_field_id('thumbnail_width'),
				'name_width'		=> $this->get_field_name('thumbnail_width'),
				'id_height'			=> $this->get_field_id('thumbnail_height'),
				'name_height'		=> $this->get_field_name('thumbnail_height'),
				'value'				=> $thumbnail_size,
				'description'		=> SB_PHP::add_colon(SB_WP::phrase("image_size")),
				'paragraph_id'		=> 'thumbnailSize',
				'paragraph_class'	=> 'thumbnail-size'
			);
			?>
			<?php SB_Theme::widget_field_image_size($args); ?>
			
			<?php
			$args = array(
				'id'				=> $this->get_field_id('show_excerpt'),
				'name'				=> $this->get_field_name('show_excerpt'),
				'value'				=> $show_excerpt,
				'description'		=> SB_PHP::add_dotted(SB_WP::phrase("show_excerpt")),
				'paragraph_id'		=> 'showExcerpt',
				'paragraph_class'	=> 'show-excerpt'
			);
			?>
			<?php SB_Theme::widget_field_checkbox($args); ?>

			<?php
			$args = array(
				'id'				=> $this->get_field_id('excerpt_length'),
				'name'				=> $this->get_field_name('excerpt_length'),
				'value'				=> $excerpt_length,
				'description'		=> SB_PHP::add_colon(SB_WP::phrase("excerpt_length")),
				'paragraph_id'		=> 'excerptLength',
				'display'			=> ((bool)$show_excerpt) ? true : false,
				'paragraph_class'	=> 'excerpt-length'
			);
			?>
			<?php SB_Theme::widget_field_number($args); ?>

            <?php
            $args = array(
                'id'				=> $this->get_field_id('title_length'),
                'name'				=> $this->get_field_name('title_length'),
                'value'				=> $title_length,
                'description'		=> SB_PHP::add_colon(SB_WP::phrase("title_length")),
                'paragraph_id'		=> 'titleLength',
                'display'			=> ((bool)$title_length) ? true : false,
                'paragraph_class'	=> 'title-length'
            );
            ?>
            <?php SB_Theme::widget_field_number($args); ?>

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
			
			<fieldset>
				<legend><?php echo SB_PHP::add_colon(SB_WP::phrase('post_information')); ?></legend>
				<?php
				$args = array(
					'id'				=> $this->get_field_id('show_author'),
					'name'				=> $this->get_field_name('show_author'),
					'value'				=> $show_author,
					'description'		=> SB_PHP::add_dotted(SB_WP::phrase("show_author")),
					'paragraph_id'		=> 'showAuthor',
					'paragraph_class'	=> 'show-author'
				);
				?>
				<?php SB_Theme::widget_field_checkbox($args); ?>
				
				<?php
				$args = array(
					'id'				=> $this->get_field_id('show_date'),
					'name'				=> $this->get_field_name('show_date'),
					'value'				=> $show_date,
					'description'		=> SB_PHP::add_dotted(SB_WP::phrase("show_date")),
					'paragraph_class'	=> 'show-date'
				);
				?>
				<?php SB_Theme::widget_field_checkbox($args); ?>
				
				<?php
				$args = array(
					'id'				=> $this->get_field_id('show_comment_count'),
					'name'				=> $this->get_field_name('show_comment_count'),
					'value'				=> $show_comment_count,
					'description'		=> SB_PHP::add_dotted(SB_WP::phrase("show_comment_count")),
					'paragraph_class'	=> 'show-comment-count'
				);
				?>
				<?php SB_Theme::widget_field_checkbox($args); ?>
			</fieldset>
			
		</div>
		<?php
	}
	
	public function update($new_instance, $instance) {
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags( $new_instance['title'] ) : '';
		$instance['type'] = $new_instance['type'];
		$instance['category'] = $new_instance['category'];
		$instance['number'] = empty( $new_instance['number'] ) ? $this->default_number : absint( $new_instance['number'] );
		$instance['taxonomy'] = $new_instance['taxonomy'];
		$instance['only_thumbnail'] = isset($new_instance['only_thumbnail']) ? 1 : 0;
		$instance['show_excerpt'] = isset($new_instance['show_excerpt']) ? 1 : 0;
		
		$instance['show_author'] = isset($new_instance['show_author']) ? 1 : 0;
		$instance['show_date'] = isset($new_instance['show_date']) ? 1 : 0;
		$instance['show_comment_count'] = isset($new_instance['show_comment_count']) ? 1 : 0;
		
		$instance['order_by'] = $new_instance['order_by'];
		$instance['order_type'] = $new_instance['order_type'];
		
		$instance['thumbnail_width'] = empty( $new_instance['thumbnail_width'] ) ? $this->thumbnail_size[0] : absint( $new_instance['thumbnail_width'] );
		$instance['thumbnail_height'] = empty( $new_instance['thumbnail_height'] ) ? $this->thumbnail_size[1] : absint( $new_instance['thumbnail_height'] );
		
		$instance['excerpt_length'] = empty( $new_instance['excerpt_length'] ) ? $this->excerpt_length : absint( $new_instance['excerpt_length'] );
        $instance['title_length'] = empty( $new_instance['title_length'] ) ? $this->title_length : absint( $new_instance['title_length'] );
		return $instance;
	}
}
?>