<?php
class SB_Post_Widget extends WP_Widget {
	public $types = array();
	private $order_by = array();
	private $order_type = array();
	
	private $default_number = 5;
	private $excerpt_length = 75;
	private $thumbnail_size = array(64, 64);
    private $title_length = 50;
    private $show_author = 0;
    private $show_date = 0;
    private $show_comment_count = 0;
    private $post_display = '';
    private $only_thumbnail = false;
    private $disable_thumbnail = false;
    private $post_ranges = array();

	public function __construct() {
		$this->init();
	
		parent::__construct( 'sb_post_widget', 'SB Post', array(
			'classname'   => 'widget_sb_post',
			'description' => __( 'Show custom post on sidebar.', 'sb-theme' ),
		));
	}
	
	private function init() {
		$this->type_init();
		$this->order_by_init();
		$this->order_type_init();
        $this->post_range_init();

        add_action( 'sb_theme_save_post', array( $this, 'delete_cache' ) );
        add_action( 'sb_theme_delete_post', array( $this, 'delete_cache' ) );
        add_action( 'sb_theme_after_switch_theme', array( $this, 'delete_cache' ) );
	}

    public function delete_cache() {
        SB_Cache::delete_widget_cache($this->id);
    }

    private function post_range_init() {
        $this->post_ranges['all'] = __('All times', 'sb-theme');
        $this->post_ranges['daily'] = __('Daily', 'sb-theme');
        $this->post_ranges['weekly'] = __('Weekly', 'sb-theme');
        $this->post_ranges['monthly'] = __('Monthly', 'sb-theme');
        $this->post_ranges['yearly'] = __('Yearly', 'sb-theme');
    }

	private function type_init() {
		$this->types = array(
			'recent'	=> __('Recent posts', 'sb-theme'),
			'random'	=> __('Random posts', 'sb-theme'),
			'comment'	=> __('Most comment posts', 'sb-theme'),
			'category'	=> __('Post by category', 'sb-theme')
		);

        if(SB_Core::is_support_post_likes()) {
            $this->types['like'] = __('Most like posts', 'sb-theme');
        }

        if(SB_Core::is_support_post_views()) {
            $this->types['view'] = __('Most view posts', 'sb-theme');
        }

        if(SB_Core::is_support_post_favorites()) {
            $this->types['favorite'] = __('Favorite posts', 'sb-theme');
        }
	}
	
	private function order_type_init() {
		$this->order_type = array(
			'desc'	=> __('DESC', 'sb-theme'),
			'asc'	=> __('ASC', 'sb-theme')
		);
	}
	
	private function order_by_init() {
		$this->order_by = array(
			'title'		=> __('Title', 'sb-theme'),
			'post_date'	=> __('Post date', 'sb-theme')
		);
	}
	
	public function widget($args, $instance) {
        if(false === ($widget_html = get_transient(SB_Cache::build_widget_transient_name($this->id)))) {
            $arr_tmp = $args;
            $number = empty($instance['number']) ? $this->default_number : absint($instance['number']);
            $title  = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
            $type = isset($instance['type']) ? $instance['type'] : 'recent';
            $taxonomy = isset($instance['taxonomy']) ? $instance['taxonomy'] : 'category';
            $order_by = isset($instance['order_by']) ? $instance['order_by'] : 'post_date';
            $order_type = $instance['order_type'];
            $order_type = strtoupper($order_type);
            $post_range = isset($instance['post_range']) ? $instance['post_range'] : 'all';
            $only_thumbnail = isset($instance['only_thumbnail']) ? absint($instance['only_thumbnail']) : 0;
            $this->only_thumbnail = $only_thumbnail;
            $show_excerpt = isset($instance['show_excerpt']) ? absint($instance['show_excerpt']) : 0;

            $thumbnail_width = empty($instance['thumbnail_width']) ? $this->thumbnail_size[0] : absint($instance['thumbnail_width']);
            $thumbnail_height = empty($instance['thumbnail_height']) ? $this->thumbnail_size[1] : absint($instance['thumbnail_height']);
            $thumbnail_size = array($thumbnail_width, $thumbnail_height);

            $excerpt_length = empty($instance['excerpt_length']) ? $this->excerpt_length : absint($instance['excerpt_length']);

            $show_author = isset($instance['show_author']) ? absint($instance['show_author']) : 0;
            $show_date = isset($instance['show_date']) ? absint($instance['show_date']) : 0;
            $show_comment_count = isset($instance['show_comment_count']) ? absint($instance['show_comment_count']) : 0;

            $title_length = empty($instance['title_length']) ? $this->title_length : absint($instance['title_length']);
            $disable_thumbnail = isset($instance['disable_thumbnail']) ? intval($instance['disable_thumbnail']) : 0;
            $this->post_display = isset($instance['post_display']) ? $instance['post_display'] : '';
            $post_type = isset($instance['post_type']) ? $instance['post_type'] : 'post';
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
                    $user = wp_get_current_user();
                    $list_posts = array();
                    if(!empty($user)) {
                        $list_posts = (array)get_user_meta($user->ID, 'favorite_posts', true);
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
            $args['post_type'] = $post_type;
            switch($post_range) {
                case 'daily':
                    $args = SB_Query::build_daily_post_args($args);
                    break;
                case 'weekly':
                    $args = SB_Query::build_weekly_post_args($args);
                    break;
                case 'monthly':
                    $args = SB_Query::build_monthly_post_args($args);
                    break;
                case 'yearly':
                    $args = SB_Query::build_yearly_post_args($args);
                    break;
            }

            $sb_post = SB_Query::get($args);

            if($sb_post->have_posts()) {
                if('favorite' == $type && !is_user_logged_in()) return;
                $args = $arr_tmp;
                $widget_html = $args['before_widget'];
                if(!empty($title)) {
                    $widget_html .= $args['before_title'] . $title . $args['after_title'];
                }

                $list = new SB_HTML('ul');
                $list->set_attribute('class', 'widgets-list-layout list-widget-posts sb-post-widget list-posts');

                $list_items = '';

                while($sb_post->have_posts()) {
                    $sb_post->the_post();
                    $post_id = get_the_ID();

                    $thumb_args = array(
                        'size' => $thumbnail_size,
                        'post_id' => $post_id,
                        'cache' => false
                    );

                    $thumbnail_link = SB_Post::get_thumbnail_link($thumb_args);

                    $post_link = new SB_HTML('a');
                    $atts = array(
                        'class' => 'post-title',
                        'href' => get_permalink($post_id),
                        'title' => get_the_title($post_id),
                        'text' => get_the_title($post_id)
                    );
                    $post_link->set_attribute_array($atts);

                    $list_item = new SB_HTML('li');
                    $list_item->set_text($thumbnail_link . $post_link->build());
                    $list_items .= $list_item->build();
                }
                wp_reset_postdata();

                $list->set_text($list_items);

                $widget_html .= $list->build();

                $widget_html .= $args['after_widget'];

                set_transient(SB_Cache::build_widget_transient_name($this->id), $widget_html, 4 * WEEK_IN_SECONDS);
            }
        }
        echo $widget_html;
	}
	
	public function form($instance) {
		$title  = empty($instance['title']) ? '' : esc_attr($instance['title']);
		$number = empty($instance['number']) ? $this->default_number : absint($instance['number']);
		$type = isset($instance['type']) ? $instance['type'] : 'recent';
		$category = isset($instance['category']) ? $instance['category'] : 0;
		$taxonomy = isset($instance['taxonomy']) ? $instance['taxonomy'] : 'category';
		$only_thumbnail = isset($instance['only_thumbnail']) ? absint($instance['only_thumbnail']) : 0;
        $this->only_thumbnail = $only_thumbnail;
		$show_excerpt = isset($instance['show_excerpt']) ? absint($instance['show_excerpt']) : 0;
		$order_by = isset($instance['order_by']) ? $instance['order_by'] : 'post_date';
		$order_type = isset($instance['order_type']) ? $instance['order_type'] : 'desc';
		$post_range = isset($instance['post_range']) ? $instance['post_range'] : 'all';
		$thumbnail_width = empty($instance['thumbnail_width']) ? $this->thumbnail_size[0] : absint( $instance['thumbnail_width'] );
		$thumbnail_height = empty($instance['thumbnail_height']) ? $this->thumbnail_size[1] : absint( $instance['thumbnail_height'] );
		$thumbnail_size = array($thumbnail_width, $thumbnail_height);
		
		$excerpt_length = empty($instance['excerpt_length'] ) ? $this->excerpt_length : absint( $instance['excerpt_length'] );
        $title_length = empty( $instance['title_length'] ) ? $this->title_length : absint( $instance['title_length'] );

		$show_author = isset($instance['show_author']) ? absint($instance['show_author']) : 0;
        $this->show_author = $show_author;
		$show_date = isset($instance['show_date']) ? absint($instance['show_date']) : 0;
        $this->show_date = $show_date;
		$show_comment_count = isset($instance['show_comment_count']) ? absint($instance['show_comment_count']) : 0;
        $this->show_comment_count = $show_comment_count;
        if($only_thumbnail) {
            $show_excerpt = false;
        }
        $post_type = isset($instance['post_type']) ? $instance['post_type'] : 'post';
        $this->post_display = isset($instance['post_display']) ? $instance['post_display'] : '';
        $disable_thumbnail = isset($instance['disable_thumbnail']) ? intval($instance['disable_thumbnail']) : 0;
        $this->disable_thumbnail = $disable_thumbnail;

		SB_Widget_Field::before('sb-theme');
        SB_Widget_Field::title($this->get_field_id('title'), $this->get_field_name('title'), $title);

        $args = array(
            'name' => $this->get_field_name('post_type'),
            'field_class' => 'widefat',
            'label' => __('Post type:', 'sb-theme'),
            'value' => $post_type
        );
        SB_Widget_Field::select_post_type($args);

        $args = array(
            'id' => $this->get_field_id('number'),
            'name' => $this->get_field_name('number'),
            'value'	=> $number,
            'label' => __('Post number:', 'sb-theme'),
            'description' => __('The number of posts to be displayed.', 'sb-theme'),
            'container_class' => 'post-number',
            'field_class' => 'widefat'
        );
        SB_Widget_Field::number($args);

        $args = array(
            'id' => $this->get_field_id( 'type' ),
            'name' => $this->get_field_name( 'type' ),
            'value' => $type,
            'label' => __('Get post by:', 'sb-theme'),
            'options' => $this->types,
            'field_class' => 'sb-post-type widefat',
            'description' => __('Choose the way you want to get post.', 'sb-theme'),
            'container_class' => 'post-type'
        );
        SB_Widget_Field::select($args);

        $taxs = SB_Core::get_all_taxonomy_hierarchical();
        $container_class = 'post-cat';
        if('category' != $type) {
            $container_class = SB_PHP::add_string_with_space_before($container_class, 'hidden');
        }
        if($taxs) {
            $args = array(
                'id' => $this->get_field_id('category'),
                'name' => $this->get_field_name('category'),
                'value' => $category,
                'label' => __('Choose category:', 'sb-theme'),
                'options' => $taxs,
                'field_class' => 'widefat',
                'container_class' => $container_class,
                'taxonomy' => $taxonomy,
                'taxonomy_id' => $this->get_field_id('taxonomy'),
                'taxonomy_name' => $this->get_field_name('taxonomy')
            );
            SB_Widget_Field::select_term($args);
        }

        $args = array(
            'id' => $this->get_field_id('post_range'),
            'name' => $this->get_field_name('post_range'),
            'value' => $post_range,
            'label' => __('Post range:', 'sb-theme'),
            'options' => $this->post_ranges,
            'field_class' => 'sb-post-range widefat',
            'container_class' => 'post-range'
        );
        SB_Widget_Field::select($args);

        $args = array(
            'id_width' => $this->get_field_id('thumbnail_width'),
            'name_width' => $this->get_field_name('thumbnail_width'),
            'id_height' => $this->get_field_id('thumbnail_height'),
            'name_height' => $this->get_field_name('thumbnail_height'),
            'label' => __('Image size:', 'sb-theme'),
            'value' => $thumbnail_size,
            'field_class' => 'widefat',
            'container_class' => 'thumbnail-size'
        );
        SB_Widget_Field::size($args);

        $args = array(
            'id' => $this->get_field_id('title_length'),
            'name' => $this->get_field_name('title_length'),
            'value'	=> $title_length,
            'label' => __('Title length:', 'sb-theme'),
            'field_class' => 'widefat',
            'container_class' => 'title-length'
        );
        SB_Widget_Field::number($args);

        $args = array(
            'id' => $this->get_field_id('show_excerpt'),
            'name' => $this->get_field_name('show_excerpt'),
            'value' => $show_excerpt,
            'label' => __('Show excerpt', 'sb-theme'),
            'container_class' => 'show-excerpt'
        );
        SB_Widget_Field::checkbox($args);

        $container_class = 'excerpt-length';
        if(!(bool)$show_excerpt) {
            $container_class = SB_PHP::add_string_with_space_before($container_class, 'hidden');
        }
        $args = array(
            'id' => $this->get_field_id('excerpt_length'),
            'name' => $this->get_field_name('excerpt_length'),
            'value' => $excerpt_length,
            'label' => __('Excerpt length:', 'sb-theme'),
            'container_class' => $container_class,
            'field_class' => 'widefat'
        );
        SB_Widget_Field::number($args);

        $args = array(
            'id' => $this->get_field_id('order_by'),
            'name' => $this->get_field_name('order_by'),
            'value' => $order_by,
            'label' => __('Order by:', 'sb-theme'),
            'options' => $this->order_by,
            'field_class' => 'widefat',
            'container_class' => 'order-by'
        );
        SB_Widget_Field::select($args);

        $args = array(
            'id' => $this->get_field_id('order_type'),
            'name' => $this->get_field_name('order_type'),
            'value' => $order_type,
            'label' => __('Order type:', 'sb-theme'),
            'options' => $this->order_type,
            'field_class' => 'widefat',
            'container_class' => 'order-by'
        );
        SB_Widget_Field::select($args);

        $args = array(
            'label' => __('Thumbnail', 'sb-theme'),
            'callback' => array($this, 'sb_post_thumbnail_option')
        );
        SB_Widget_Field::fieldset($args);

        $args = array(
            'label' => __('Post information', 'sb-theme'),
            'callback' => array($this, 'sb_post_information'),
            'container_class' => 'post-info'
        );
        SB_Widget_Field::fieldset($args);

        $args = array(
            'label' => __('Display', 'sb-theme'),
            'callback' => array($this, 'sb_post_display_type')
        );
        SB_Widget_Field::fieldset($args);
        SB_Widget_Field::after();
	}

    public function sb_post_thumbnail_option() {
        $args = array(
            'id' => $this->get_field_id('only_thumbnail'),
            'name' => $this->get_field_name('only_thumbnail'),
            'value' => $this->only_thumbnail,
            'label' => __('Show only thumbnail', 'sb-theme'),
            'container_class' => 'only-thumbnail'
        );
        SB_Widget_Field::checkbox($args);

        $args = array(
            'id' => $this->get_field_id('disable_thumbnail'),
            'name' => $this->get_field_name('disable_thumbnail'),
            'value' => $this->disable_thumbnail,
            'label' => __('Do not show thumbnail', 'sb-theme'),
            'container_class' => 'disable-thumbnail'
        );
        SB_Widget_Field::checkbox($args);
    }

    public function sb_post_display_type() {
        $options = array(
            'first' => __('First post full width', 'sb-theme'),
            'last' => __('Last post full width', 'sb-theme'),
            'first_last' => __('First and last post full width', 'sb-theme'),
            'all' => __('All post full width', 'sb-theme')
        );
        $args = array(
            'name' => $this->get_field_name('post_display'),
            'options' => $options,
            'value' => $this->post_display
        );
        SB_Widget_Field::radio($args);
    }

    public function sb_post_information() {
        $args = array(
            'id' => $this->get_field_id('show_author'),
            'name' => $this->get_field_name('show_author'),
            'value' => $this->show_author,
            'label' => __('Show author', 'sb-theme'),
            'container_class' => 'show-author'
        );
        SB_Widget_Field::checkbox($args);

        $args = array(
            'id' => $this->get_field_id('show_date'),
            'name' => $this->get_field_name('show_date'),
            'value' => $this->show_date,
            'label' => __('Show date', 'sb-theme'),
            'container_class' => 'show-date'
        );
        SB_Widget_Field::checkbox($args);

        $args = array(
            'id' => $this->get_field_id('show_comment_count'),
            'name' => $this->get_field_name('show_comment_count'),
            'value' => $this->show_comment_count,
            'label' => __('Show comment count', 'sb-theme'),
            'container_class' => 'show-comment-count'
        );
        SB_Widget_Field::checkbox($args);
    }

	public function update($new_instance, $instance) {
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		$instance['type'] = $new_instance['type'];
		$instance['category'] = $new_instance['category'];
		$instance['number'] = empty($new_instance['number']) ? $this->default_number : absint($new_instance['number']);
		$instance['taxonomy'] = $new_instance['taxonomy'];
		$instance['only_thumbnail'] = isset($new_instance['only_thumbnail']) ? 1 : 0;
		$instance['show_excerpt'] = isset($new_instance['show_excerpt']) ? 1 : 0;
		$instance['post_range'] = isset($new_instance['post_range']) ? $new_instance['post_range'] : 'all';
		$instance['show_author'] = isset($new_instance['show_author']) ? 1 : 0;
		$instance['show_date'] = isset($new_instance['show_date']) ? 1 : 0;
		$instance['show_comment_count'] = isset($new_instance['show_comment_count']) ? 1 : 0;
		
		$instance['order_by'] = isset($new_instance['order_by']) ? $new_instance['order_by'] : 'post_date';
		$instance['order_type'] = $new_instance['order_type'];
		
		$instance['thumbnail_width'] = empty($new_instance['thumbnail_width']) ? $this->thumbnail_size[0] : absint($new_instance['thumbnail_width']);
		$instance['thumbnail_height'] = empty($new_instance['thumbnail_height']) ? $this->thumbnail_size[1] : absint($new_instance['thumbnail_height']);
		
		$instance['excerpt_length'] = empty($new_instance['excerpt_length']) ? $this->excerpt_length : absint($new_instance['excerpt_length']);
        $instance['title_length'] = empty($new_instance['title_length']) ? $this->title_length : absint($new_instance['title_length']);

        $instance['post_display'] = isset($new_instance['post_display']) ? $new_instance['post_display'] : '';
        $instance['post_type'] = isset($new_instance['post_type']) ? $new_instance['post_type'] : 'post';
        $instance['disable_thumbnail'] = isset($new_instance['disable_thumbnail']) ? 1 : 0;

        $this->delete_cache();

		return $instance;
	}
}
?>